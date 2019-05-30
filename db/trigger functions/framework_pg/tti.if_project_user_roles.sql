-- Function: audit.if_tablelog()

-- DROP FUNCTION audit.if_tablelog();


CREATE OR REPLACE FUNCTION tti.if_project_user_roles()
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
        v_role_new = COALESCE(NEW.cd_roles, -1);
        v_role_old = COALESCE(OLD.cd_roles, -1);

    --delete
    ELSIF (TG_OP = 'DELETE') THEN
        UPDATE "PROJECT_MODEL" 
           SET dt_update = transaction_timestamp() 
         WHERE "PROJECT_MODEL".cd_project_model = OLD.cd_project_model 
           AND "PROJECT_MODEL".dt_update != transaction_timestamp();


    --insert
    ELSIF (TG_OP = 'INSERT') THEN
        v_role_new = COALESCE(NEW.cd_roles, -1);
        v_role_old = -1;


    ELSE
        RAISE EXCEPTION 'Trigger func added as trigger for unhandled case: %, %',TG_OP, TG_LEVEL;
        RETURN NULL;
    END IF;


   --updates ou insert
    IF (TG_OP = 'UPDATE' OR TG_OP = 'INSERT') THEN
        IF v_role_new != v_role_old AND NEW.cd_notification_type IS NULL THEN
            SELECT cd_notification_type_default INTO NEW.cd_notification_type
              FROM "ROLES" 
             WHERE cd_roles = v_role_new;
        END IF;

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
ALTER FUNCTION tti.if_project_user_roles() SET search_path=pg_catalog, public, tti, rfq;

ALTER FUNCTION tti.if_project_user_roles() OWNER TO postgres;

/*

        CREATE TRIGGER t_if_project_user_roles
          BEFORE UPDATE OR INSERT OR DELETE
          ON tti."PROJECT_USER_ROLES"
          FOR EACH ROW
          EXECUTE PROCEDURE tti.if_project_user_roles();


*/

