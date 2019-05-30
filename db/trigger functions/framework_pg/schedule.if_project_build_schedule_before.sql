-- Function: audit.if_tablelog()

-- DROP FUNCTION audit.if_tablelog();


CREATE OR REPLACE FUNCTION schedule.if_project_build_schedule_before()
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

        IF NEW.cd_human_resource_te != COALESCE(OLD.cd_human_resource_te, -1) AND NEW.cd_human_resource_te IS NOT NULL THEN
            INSERT INTO "PROJECT_USER_ROLES" (cd_human_resource, cd_project_model, fl_active)
            SELECT NEW.cd_human_resource_te, "PROJECT_BUILD_SCHEDULE".cd_project_model, 'Y'
                FROM "PROJECT_BUILD_SCHEDULE"
                WHERE "PROJECT_BUILD_SCHEDULE".cd_project_build_schedule = NEW.cd_project_build_schedule 
                AND NOT EXISTS ( SELECT 1 FROM "PROJECT_USER_ROLES" x WHERE x.cd_project_model = "PROJECT_BUILD_SCHEDULE".cd_project_model AND x.cd_human_resource = NEW.cd_human_resource_te);
        END IF;

    --delete
    ELSIF (TG_OP = 'DELETE') THEN
        UPDATE "PROJECT_MODEL" 
           SET dt_update = transaction_timestamp() 
         WHERE "PROJECT_MODEL".cd_project_model = OLD.cd_project_model 
           AND "PROJECT_MODEL".dt_update != transaction_timestamp();

    --insert
    ELSIF (TG_OP = 'INSERT') THEN
        NEW.nr_version = retNextVersion(NEW.cd_project_build, NEW.cd_project, NEW.cd_project_model);

        IF NEW.cd_human_resource_te IS NOT NULL THEN
            INSERT INTO "PROJECT_USER_ROLES" (cd_human_resource, cd_project_model, fl_active)
            SELECT NEW.cd_human_resource_te, NEW.cd_project_model, 'Y'
             WHERE NOT EXISTS ( SELECT 1 FROM "PROJECT_USER_ROLES" x WHERE x.cd_project_model = NEW.cd_project_model AND x.cd_human_resource = NEW.cd_human_resource_te);
        END IF;



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

        IF NEW.dt_est_start IS NOT NULL AND NEW.dt_est_finish IS NULL THEN
            NEW.dt_est_finish = NEW.dt_est_start;
        END IF;

        IF NEW.dt_est_start IS NOT NULL THEN
            NEW.dt_est_range = daterange(NEW.dt_est_start::date, NEW.dt_est_finish::date, '[]');
        ELSE
            NEW.dt_est_range = NULL;
        END IF;

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
ALTER FUNCTION schedule.if_project_build_schedule_before() SET search_path=pg_catalog, public, schedule, tr, tti;

ALTER FUNCTION schedule.if_project_build_schedule_before() OWNER TO postgres;


/*

CREATE TRIGGER aa_trigger_before
  BEFORE UPDATE OR INSERT OR DELETE
  ON schedule."PROJECT_BUILD_SCHEDULE"
  FOR EACH ROW
  EXECUTE PROCEDURE schedule.if_project_build_schedule_before();
*/


