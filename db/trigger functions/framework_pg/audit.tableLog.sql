-- Function: audit.if_tablelog()

-- DROP FUNCTION audit.if_tablelog();

CREATE OR REPLACE FUNCTION audit.if_tablelog()
  RETURNS trigger AS
$BODY$
DECLARE
    audit_row audit.logged_actions;
    include_values BOOLEAN;
    log_diffs BOOLEAN;
    h_old hstore;
    h_new hstore;
    h_changed hstore;
    excluded_cols text[] = ARRAY[]::text[];
    c_username text;
    r record;
    ds_old_text text;
    ds_new_text text;
    l_cd_pk     integer;
    s_cd_pk     text;
    l_count     integer;
    h_ins_del   hstore;
    v_cd_fk_code_old integer;
    v_cd_fk_code_new integer;

BEGIN
    IF TG_WHEN <> 'AFTER' THEN
        RAISE EXCEPTION 'audit.if_tablelog() may only run as an AFTER trigger';
    END IF;
 
    --RAISE NOTICE 'LOG, %', NEW;

   c_username = public.get_var('ds_human_resource');
   IF c_username IS NULL THEN
        c_username =         session_user::text;                           -- session_user_name
   END IF;

  
   -- defino a PL;
   SELECT min(column_name), count(1) 
    into s_cd_pk, l_count
    FROM primary_keys_view 
   WHERE table_name = TG_TABLE_NAME::text;

   IF l_count != 1 THEN
        RAISE NOTICE '[audit.if_tablelog] - Missing Primary key ';
        RETURN NULL;
   END IF;


    IF TG_ARGV[0] IS NOT NULL THEN
        excluded_cols = TG_ARGV[0]::text[];
    END IF;

    -- colunas a serem excluidas para sempre!!!
    excluded_cols = excluded_cols || array['dt_record', 'dt_update'];

    -- alimenta os hstores
    IF (TG_OP = 'UPDATE' AND TG_LEVEL = 'ROW') THEN
        h_old = hstore(OLD.*);
        h_new = hstore(NEW.*);
        h_changed = ( h_new - h_old) - excluded_cols;
        l_cd_pk     = h_new -> s_cd_pk;
        
        IF h_changed = hstore('') THEN
            -- All changed fields are ignored. Skip this update.
            RETURN NULL;
        END IF;

    ELSIF (TG_OP = 'DELETE' AND TG_LEVEL = 'ROW') THEN
        h_old = hstore(OLD.*);
        h_new = hstore(OLD.*);
        h_changed = hstore(array[s_cd_pk], array['!!! DELETED !!!']);
        l_cd_pk     = h_old -> s_cd_pk;
        h_ins_del = hstore(OLD.*) - excluded_cols;

        --audit_row.row_data = hstore(OLD.*) - excluded_cols;
    ELSIF (TG_OP = 'INSERT' AND TG_LEVEL = 'ROW') THEN
        h_old = hstore(NEW.*);
        h_new = hstore(NEW.*);
        h_changed = hstore(array[s_cd_pk], array['!!! INSERTED !!!']);
        l_cd_pk   = h_new -> s_cd_pk;
        h_ins_del = hstore(NEW.*) - excluded_cols;

    END IF;

   FOR r IN SELECT * from each(h_changed)
   LOOP


      ds_old_text = h_old -> r.key;
      ds_new_text = r.value;

     IF substring (r.key, 1, 2) = 'cd' AND r.key != s_cd_pk THEN


         IF ds_old_text  IS NOT NULL THEN
            SELECT retFkDescription(TG_TABLE_NAME::text, r.key, h_old -> r.key)
            INTO  ds_old_text;

            v_cd_fk_code_old = h_old -> r.key; 

         END IF;

         IF ds_new_text  IS NOT NULL THEN
            SELECT retFkDescription(TG_TABLE_NAME::text, r.key, r.value)
            INTO  ds_new_text;
            v_cd_fk_code_new = r.value;

         END IF;
 
         IF ds_old_text IS NULL THEN 
            ds_old_text = h_old -> r.key;
         END IF;

         IF ds_new_text IS NULL THEN 
            ds_new_text = r.value;
         END IF;

     END IF;

      INSERT INTO audit."TABLES_LOG_MASTER" ( 
                                       cd_tables_log_oid, 
                                       ds_table_name,
                                       ds_column_name,
                                       dt_record,
                                       ds_data_before,
                                       ds_data_after,
                                       cd_type_log,
                                       ds_username,
                                       cd_pk,
                                       ds_hstore_ins_del,
                                       cd_fk_code_old,
                                       cd_fk_code_new
                                     )
      values (TG_RELID, 
              TG_TABLE_NAME::text, 
              r.key, 
              clock_timestamp(),
              ds_old_text,
              ds_new_text,
              substring(TG_OP,1,1),
              c_username,
              l_cd_pk,
              h_ins_del,
              v_cd_fk_code_old,
              v_cd_fk_code_new

             );

   END LOOP;


    RETURN NULL;
END;
$BODY$
  LANGUAGE plpgsql VOLATILE SECURITY DEFINER
  COST 100;
ALTER FUNCTION audit.if_tablelog() SET search_path=pg_catalog, public, tti, schedule, tr, translation, reports, rfq ;

ALTER FUNCTION audit.if_tablelog()
  OWNER TO postgres;

   