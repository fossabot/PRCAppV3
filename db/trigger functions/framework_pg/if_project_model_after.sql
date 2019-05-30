-- Function: audit.if_tablelog()

-- DROP FUNCTION audit.if_tablelog();


CREATE OR REPLACE FUNCTION tti.if_project_model_after()
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


    --delete
    ELSIF (TG_OP = 'DELETE') THEN


    --insert
    ELSIF (TG_OP = 'INSERT') THEN
        INSERT INTO "PROJECT_USER_ROLES" (cd_human_resource, cd_project_model, fl_active)
        SELECT cd_human_resource_prc_pm, NEW.cd_project_model, 'Y'
          FROM "PROJECT"
         WHERE cd_project = NEW.cd_project
          AND cd_human_resource_prc_pm IS NOT NULL;

        INSERT INTO "PROJECT_USER_ROLES" (cd_human_resource, cd_project_model, fl_active)
        SELECT cd_human_resource_eng, NEW.cd_project_model, 'Y'
          FROM "PROJECT"
         WHERE cd_project = NEW.cd_project
          AND cd_human_resource_eng IS NOT NULL;

        INSERT INTO "PROJECT_USER_ROLES" (cd_human_resource, cd_project_model, fl_active)
        SELECT get_var('cd_human_resource')::integer, NEW.cd_project_model, 'Y'
          FROM "PROJECT"
         WHERE cd_project = NEW.cd_project
           AND get_var('cd_human_resource')::integer != COALESCE("PROJECT".cd_human_resource_eng, 0)
           AND get_var('cd_human_resource')::integer != COALESCE("PROJECT".cd_human_resource_prc_pm, 0);
       
        INSERT INTO "PROJECT_USER_ROLES" (cd_human_resource, cd_project_model, fl_active)
         select h.cd_human_resource, NEW.cd_project_model, 'Y'
           from public."HUMAN_RESOURCE" h
             where getSysParameter('AUTO_ADD_USER_PROJECT_MODEL') like concat('%,', trim(h.cd_human_resource::text), ',%')
             AND NOT EXISTS ( SELECT 1 FROM "PROJECT_USER_ROLES" x WHERE x.cd_project_model = NEW.cd_project_model AND x.cd_human_resource = h.cd_human_resource);



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

ALTER FUNCTION tti.if_project_model_after() SET search_path=pg_catalog, public, tti;

ALTER FUNCTION tti.if_project_model_after() OWNER TO postgres;




CREATE TRIGGER if_project_model_after
  AFTER UPDATE OR INSERT OR DELETE
  ON tti."PROJECT_MODEL"
  FOR EACH ROW
  EXECUTE PROCEDURE tti.if_project_model_after();
