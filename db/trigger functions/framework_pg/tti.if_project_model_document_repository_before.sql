-- Function: audit.if_tablelog()

-- DROP FUNCTION audit.if_tablelog();


CREATE OR REPLACE FUNCTION docrep.if_project_model_document_repository_before()
  RETURNS trigger AS
$BODY$
DECLARE
    v_error_text text;
    v_error_code int;
    v_role_new   int;
    v_role_old  int;

BEGIN
    -- start without error!
    v_error_code = 0;

   --updates
    IF (TG_OP = 'UPDATE') THEN


    --delete
    ELSIF (TG_OP = 'DELETE') THEN
        UPDATE "PROJECT_MODEL" 
           SET dt_update = transaction_timestamp() 
         WHERE "PROJECT_MODEL".cd_project_model = OLD.cd_project_model 
           AND "PROJECT_MODEL".dt_update != transaction_timestamp();


    --insert
    ELSIF (TG_OP = 'INSERT') THEN
        

    ELSE
        RAISE EXCEPTION 'Trigger func added as trigger for unhandled case: %, %',TG_OP, TG_LEVEL;
        RETURN NULL;
    END IF;


   --updates ou insert
    IF (TG_OP = 'UPDATE' OR TG_OP = 'INSERT') THEN

        UPDATE "PROJECT_MODEL" 
           SET dt_update = transaction_timestamp() 
         WHERE "PROJECT_MODEL".cd_project_model = NEW.cd_project_model 
           AND "PROJECT_MODEL".dt_update != transaction_timestamp();


    END IF;




    -- controle de erro!!!!
    IF v_error_code > 0 THEN

        SELECT getTriggerError (v_error_code, null)
          INTO v_error_text;

        RAISE EXCEPTION '% (%)', v_error_text, v_error_code;
    END IF;


  -- Retorna OK
  IF TG_OP = 'DELETE' THEN 
    RETURN OLD;
  ELSE
    RETURN NEW;
   END IF;

END;
$BODY$
  LANGUAGE plpgsql VOLATILE SECURITY DEFINER
  COST 100;
ALTER FUNCTION docrep.if_project_model_document_repository_before() SET search_path=pg_catalog, public, tti, rfq, schedule, docrep;

ALTER FUNCTION docrep.if_project_model_document_repository_before() OWNER TO postgres;

/*

        CREATE TRIGGER t_if_project_model_document_repository_before
          BEFORE UPDATE OR INSERT OR DELETE
          ON docrep."PROJECT_MODEL_DOCUMENT_REPOSITORY"
          FOR EACH ROW
          EXECUTE PROCEDURE docrep.if_project_model_document_repository_before();


*/

