
-- Function: audit.if_modified_func()

-- DROP FUNCTION audit.if_modified_func();

CREATE OR REPLACE FUNCTION audit.if_modified_func()
  RETURNS trigger AS
$BODY$
DECLARE
    audit_row audit.logged_actions;
    include_values BOOLEAN;
    log_diffs BOOLEAN;
    h_old hstore;
    h_new hstore;
    excluded_cols text[] = ARRAY[]::text[];
    c_username text;
BEGIN
    IF TG_WHEN <> 'AFTER' THEN
        RAISE EXCEPTION 'audit.if_modified_func() may only run as an AFTER trigger';
    END IF;
 
   c_username = public.get_var('ds_human_resource');
   IF c_username IS NULL THEN
        c_username =         session_user::text;                           -- session_user_name
    END IF;

    audit_row = ROW(
        NEXTVAL('audit.logged_actions_event_id_seq'), -- event_id
        TG_TABLE_SCHEMA::text,                        -- schema_name
        TG_TABLE_NAME::text,                          -- table_name
        TG_RELID,                                     -- relation OID for much quicker searches
        c_username,                           -- session_user_name
        current_timestamp,                            -- action_tstamp_tx
        statement_timestamp(),                        -- action_tstamp_stm
        clock_timestamp(),                            -- action_tstamp_clk
        txid_current(),                               -- transaction ID
        (SELECT setting FROM pg_settings WHERE name = 'application_name'),
        inet_client_addr(),                           -- client_addr
        inet_client_port(),                           -- client_port
        current_query(),                              -- top-level query or queries (if multistatement) from client
        substring(TG_OP,1,1),                         -- action
        NULL, NULL,                                   -- row_data, changed_fields
        'f'                                           -- statement_only
        );
 
    IF NOT TG_ARGV[0]::BOOLEAN IS DISTINCT FROM 'f'::BOOLEAN THEN
        audit_row.client_query = NULL;
    END IF;
 
    IF TG_ARGV[1] IS NOT NULL THEN
        excluded_cols = TG_ARGV[1]::text[];
    END IF;
 
    IF (TG_OP = 'UPDATE' AND TG_LEVEL = 'ROW') THEN
        audit_row.row_data = hstore(OLD.*);
        audit_row.changed_fields =  (hstore(NEW.*) - audit_row.row_data) - excluded_cols;
        IF audit_row.changed_fields = hstore('') THEN
            -- All changed fields are ignored. Skip this update.
            RETURN NULL;
        END IF;
    ELSIF (TG_OP = 'DELETE' AND TG_LEVEL = 'ROW') THEN
        audit_row.row_data = hstore(OLD.*) - excluded_cols;
    ELSIF (TG_OP = 'INSERT' AND TG_LEVEL = 'ROW') THEN
        audit_row.row_data = hstore(NEW.*) - excluded_cols;
    ELSIF (TG_LEVEL = 'STATEMENT' AND TG_OP IN ('INSERT','UPDATE','DELETE','TRUNCATE')) THEN
        audit_row.statement_only = 't';
    ELSE
        RAISE EXCEPTION '[audit.if_modified_func] - Trigger func added as trigger for unhandled case: %, %',TG_OP, TG_LEVEL;
        RETURN NULL;
    END IF;
    INSERT INTO audit.logged_actions VALUES (audit_row.*);
    RETURN NULL;
END;
$BODY$
  LANGUAGE plpgsql VOLATILE SECURITY DEFINER
  COST 100;
ALTER FUNCTION audit.if_modified_func() SET search_path=pg_catalog, public;

ALTER FUNCTION audit.if_modified_func()
  OWNER TO postgres;
COMMENT ON FUNCTION audit.if_modified_func() IS '
Track changes TO a TABLE at the statement AND/OR row level.
 
Optional parameters TO TRIGGER IN CREATE TRIGGER call:
 
param 0: BOOLEAN, whether TO log the query text. DEFAULT ''t''.
 
param 1: text[], COLUMNS TO IGNORE IN updates. DEFAULT [].
 
         Updates TO ignored cols are omitted FROM changed_fields.
 
         Updates WITH only ignored cols changed are NOT inserted
         INTO the audit log.
 
         Almost ALL the processing work IS still done FOR updates
         that ignored. IF you need TO save the LOAD, you need TO USE
         WHEN clause ON the TRIGGER instead.
 
         No warning OR error IS issued IF ignored_cols contains COLUMNS
         that do NOT exist IN the target TABLE. This lets you specify
         a standard SET of ignored COLUMNS.
 
There IS no parameter TO disable logging of VALUES. ADD this TRIGGER AS
a ''FOR EACH STATEMENT'' rather than ''FOR EACH ROW'' TRIGGER IF you do NOT
want TO log row VALUES.
 
Note that the user name logged IS the login role FOR the session. The audit TRIGGER
cannot obtain the active role because it IS reset BY the SECURITY DEFINER invocation
of the audit TRIGGER its self.
';
