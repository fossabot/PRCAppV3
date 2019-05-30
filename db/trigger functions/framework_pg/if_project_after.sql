-- Function: audit.if_tablelog()

-- DROP FUNCTION audit.if_tablelog();


CREATE OR REPLACE FUNCTION tti.if_project_after()
  RETURNS trigger AS
$BODY$
DECLARE
    v_error_text text;
    v_error_code int;


BEGIN
    -- start without error!
    v_error_code = 0;

   --updates
    IF (TG_OP = 'UPDATE') THEN

        IF NEW.cd_human_resource_prc_pm != COALESCE(OLD.cd_human_resource_prc_pm, -1) AND NEW.cd_human_resource_prc_pm IS NOT NULL THEN
            INSERT INTO "PROJECT_USER_ROLES" (cd_human_resource, cd_project_model, fl_active)
            SELECT NEW.cd_human_resource_prc_pm, "PROJECT_MODEL".cd_project_model, 'Y'
              FROM "PROJECT_MODEL"
             WHERE "PROJECT_MODEL".cd_project = NEW.cd_project
              AND NEW.cd_human_resource_prc_pm IS NOT NULL
              AND NOT EXISTS ( SELECT 1 FROM "PROJECT_USER_ROLES" x WHERE x.cd_project_model = "PROJECT_MODEL".cd_project_model AND x.cd_human_resource = NEW.cd_human_resource_prc_pm);
        END IF;

        IF NEW.cd_human_resource_eng != COALESCE(OLD.cd_human_resource_eng, -1) AND NEW.cd_human_resource_eng IS NOT NULL THEN
            INSERT INTO "PROJECT_USER_ROLES" (cd_human_resource, cd_project_model, fl_active)
            SELECT NEW.cd_human_resource_eng, "PROJECT_MODEL".cd_project_model, 'Y'
              FROM "PROJECT_MODEL"
             WHERE "PROJECT_MODEL".cd_project = NEW.cd_project
              AND NEW.cd_human_resource_eng IS NOT NULL
              AND NOT EXISTS ( SELECT 1 FROM "PROJECT_USER_ROLES" x WHERE x.cd_project_model = "PROJECT_MODEL".cd_project_model AND x.cd_human_resource = NEW.cd_human_resource_eng);
        END IF;

    --delete
    ELSIF (TG_OP = 'DELETE') THEN


    --insert
    ELSIF (TG_OP = 'INSERT') THEN


    ELSE
        RAISE EXCEPTION 'Trigger func added as trigger for unhandled case: %, %',TG_OP, TG_LEVEL;
        RETURN NULL;
    END IF;


   --updates ou insert
    IF (TG_OP = 'UPDATE' OR TG_OP = 'INSERT') THEN


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

ALTER FUNCTION tti.if_project_after() SET search_path=pg_catalog, public, tti;

ALTER FUNCTION tti.if_project_after() OWNER TO postgres;


CREATE TRIGGER t_project_after
  AFTER UPDATE OR INSERT OR DELETE
  ON tti."PROJECT"
  FOR EACH ROW
  EXECUTE PROCEDURE tti.if_project_after();


