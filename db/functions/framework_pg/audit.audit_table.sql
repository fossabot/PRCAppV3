-- Function: audit.audit_table(regclass, boolean, boolean, text[])

-- DROP FUNCTION audit.audit_table(regclass, boolean, boolean, text[]);

CREATE OR REPLACE FUNCTION audit.audit_table(target_table regclass, audit_rows boolean, audit_query_text boolean, ignored_cols text[])
  RETURNS void AS
$BODY$
DECLARE
  stm_targets text = 'INSERT OR UPDATE OR DELETE OR TRUNCATE';
  _q_txt text;
  _ignored_cols_snip text = '';
BEGIN
    EXECUTE 'DROP TRIGGER IF EXISTS audit_trigger_row ON ' || quote_ident(target_table::text);
    EXECUTE 'DROP TRIGGER IF EXISTS audit_trigger_stm ON ' || quote_ident(target_table::text);
 
    IF audit_rows THEN
        IF array_length(ignored_cols,1) > 0 THEN
            _ignored_cols_snip = ', ' || quote_literal(ignored_cols);
        END IF;
        _q_txt = 'CREATE TRIGGER audit_trigger_row AFTER INSERT OR UPDATE OR DELETE ON ' || 
                 quote_ident(target_table::text) || 
                 ' FOR EACH ROW EXECUTE PROCEDURE audit.if_modified_func(' ||
                 quote_literal(audit_query_text) || _ignored_cols_snip || ');';
        RAISE NOTICE '%',_q_txt;
        EXECUTE _q_txt;
        stm_targets = 'TRUNCATE';
    ELSE
    END IF;
 
    _q_txt = 'CREATE TRIGGER audit_trigger_stm AFTER ' || stm_targets || ' ON ' ||
             quote_ident(target_table::text) ||
             ' FOR EACH STATEMENT EXECUTE PROCEDURE audit.if_modified_func('||
             quote_literal(audit_query_text) || ');';
    RAISE NOTICE '%',_q_txt;
    EXECUTE _q_txt;
 
END;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION audit.audit_table(regclass, boolean, boolean, text[])
  OWNER TO postgres;
COMMENT ON FUNCTION audit.audit_table(regclass, boolean, boolean, text[]) IS '
ADD auditing support TO a TABLE.
 
Arguments:
   target_table:     TABLE name, schema qualified IF NOT ON search_path
   audit_rows:       Record each row CHANGE, OR only audit at a statement level
   audit_query_text: Record the text of the client query that triggered the audit event?
   ignored_cols:     COLUMNS TO exclude FROM UPDATE diffs, IGNORE updates that CHANGE only ignored cols.
';







-- =======================================================================
-- Function: audit.audit_table(regclass, boolean, boolean)

-- DROP FUNCTION audit.audit_table(regclass, boolean, boolean);

CREATE OR REPLACE FUNCTION audit.audit_table(target_table regclass, audit_rows boolean, audit_query_text boolean)
  RETURNS void AS
$BODY$
SELECT audit.audit_table($1, $2, $3, ARRAY[]::text[]);
$BODY$
  LANGUAGE sql VOLATILE
  COST 100;
ALTER FUNCTION audit.audit_table(regclass, boolean, boolean)
  OWNER TO postgres;

-- =======================================================================


-- Function: audit.audit_table(regclass)

-- DROP FUNCTION audit.audit_table(regclass);

CREATE OR REPLACE FUNCTION audit.audit_table(target_table regclass)
  RETURNS void AS
$BODY$
SELECT audit.audit_table($1, BOOLEAN 't', BOOLEAN 't');
$BODY$
  LANGUAGE sql VOLATILE
  COST 100;
ALTER FUNCTION audit.audit_table(regclass)
  OWNER TO postgres;
COMMENT ON FUNCTION audit.audit_table(regclass) IS '
ADD auditing support TO the given TABLE. Row-level changes will be logged WITH FULL client query text. No cols are ignored.
';


