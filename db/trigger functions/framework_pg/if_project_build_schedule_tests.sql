-- Function: audit.if_tablelog()

-- DROP FUNCTION audit.if_tablelog();


CREATE OR REPLACE FUNCTION schedule.if_project_build_schedule_tests()
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
/*
        IF NEW.cd_human_resource_te != COALESCE(OLD.cd_human_resource_te, -1) AND NEW.cd_human_resource_te IS NOT NULL THEN
            INSERT INTO "PROJECT_USER_ROLES" (cd_human_resource, cd_project_model, fl_active)
            SELECT NEW.cd_human_resource_te, "PROJECT_MODEL".cd_project_model, 'Y'
              FROM "PROJECT_BUILD_SCHEDULE", "PROJECT_MODEL"
             WHERE "PROJECT_BUILD_SCHEDULE".cd_project_build_schedule = NEW.cd_project_build_schedule 
              AND "PROJECT_MODEL".cd_project = "PROJECT_BUILD_SCHEDULE".cd_project
              AND ( "PROJECT_MODEL".cd_project_model = "PROJECT_BUILD_SCHEDULE".cd_project_model OR "PROJECT_BUILD_SCHEDULE".cd_project_model IS NULL )
              AND NOT EXISTS ( SELECT 1 FROM "PROJECT_USER_ROLES" x WHERE x.cd_project_model = "PROJECT_BUILD_SCHEDULE".cd_project_model AND x.cd_human_resource = NEW.cd_human_resource_te);
        END IF;
*/
    --delete
    ELSIF (TG_OP = 'DELETE') THEN
/*        UPDATE "PROJECT_MODEL" 
           SET dt_update = transaction_timestamp() 
          FROM "PROJECT_BUILD_SCHEDULE"
         WHERE "PROJECT_BUILD_SCHEDULE".cd_project_build_schedule = OLD.cd_project_build_schedule
           AND "PROJECT_MODEL".cd_project_model = "PROJECT_BUILD_SCHEDULE".cd_project_model 
           AND "PROJECT_MODEL".dt_update != transaction_timestamp();
*/

    --insert
    ELSIF (TG_OP = 'INSERT') THEN

        IF NEW.cd_human_resource_te IS NOT NULL THEN
            INSERT INTO "PROJECT_USER_ROLES" (cd_human_resource, cd_project_model, fl_active)
            SELECT NEW.cd_human_resource_te, "PROJECT_MODEL".cd_project_model, 'Y'
              FROM "PROJECT_BUILD_SCHEDULE", "PROJECT_MODEL"
             WHERE "PROJECT_BUILD_SCHEDULE".cd_project_build_schedule = NEW.cd_project_build_schedule 
              AND "PROJECT_MODEL".cd_project = "PROJECT_BUILD_SCHEDULE".cd_project
              AND ( "PROJECT_MODEL".cd_project_model = "PROJECT_BUILD_SCHEDULE".cd_project_model OR "PROJECT_BUILD_SCHEDULE".cd_project_model IS NULL )
              AND NOT EXISTS ( SELECT 1 FROM "PROJECT_USER_ROLES" x WHERE x.cd_project_model = "PROJECT_BUILD_SCHEDULE".cd_project_model AND x.cd_human_resource = NEW.cd_human_resource_te);
       END IF;

       IF NEW.nr_priority = 0 THEN 
          NEW.nr_priority = 300;
       END IF;

    ELSE
        RAISE EXCEPTION 'Trigger func added as trigger for unhandled case: %, %',TG_OP, TG_LEVEL;
        RETURN NULL;
    END IF;


   --updates ou insert
    IF (TG_OP = 'UPDATE' OR TG_OP = 'INSERT') THEN

        UPDATE "PROJECT_MODEL" 
           SET dt_update = transaction_timestamp() 
          FROM "PROJECT_BUILD_SCHEDULE"
         WHERE "PROJECT_BUILD_SCHEDULE".cd_project_build_schedule = NEW.cd_project_build_schedule
           AND "PROJECT_MODEL".cd_project_model = "PROJECT_BUILD_SCHEDULE".cd_project_model 
           AND "PROJECT_MODEL".dt_update != transaction_timestamp();

         /*Make sure the start and finish exists*/
        IF NEW.dt_est_start IS NOT NULL AND NEW.dt_est_finish IS NULL THEN
            NEW.dt_est_finish = NEW.dt_est_start;
        END IF;

        IF NEW.dt_start IS NOT NULL AND NEW.dt_finish IS NULL THEN
            NEW.dt_finish = NEW.dt_start;
        END IF;

        IF NEW.dt_actual_finish IS NOT NULL AND NEW.dt_actual_start IS NULL THEN
            NEW.dt_actual_start = NEW.dt_actual_finish;
        END IF;
        /* END of start and finish the same*/

        /*feeding range */
        IF NEW.dt_est_start IS NOT NULL THEN
            NEW.dt_est_range = daterange(NEW.dt_est_start::date, NEW.dt_est_finish::date, '[]');
        ELSE
            NEW.dt_est_range = NULL;
        END IF;

        IF NEW.dt_est_start IS NOT NULL AND NEW.dt_est_finish IS NOT NULL AND NEW.dt_start IS NULL AND NEW.dt_finish IS NULL THEN
            NEW.dt_start = NEW.dt_est_start;
            NEW.dt_finish = NEW.dt_est_finish;
        END IF;

        IF NEW.dt_start IS NOT NULL THEN
            NEW.dt_agreed_range = daterange(NEW.dt_start::date, NEW.dt_finish::date, '[]');
        ELSE
            NEW.dt_agreed_range = NULL;
        END IF;

        IF NEW.dt_actual_start  IS NOT NULL THEN
            NEW.dt_actual_range = daterange(NEW.dt_actual_start::date, NEW.dt_actual_finish::date, '[]');
        ELSE
            NEW.dt_actual_range = NULL;
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

ALTER FUNCTION schedule.if_project_build_schedule_tests() SET search_path=pg_catalog, public, tti, schedule;

ALTER FUNCTION schedule.if_project_build_schedule_tests() OWNER TO postgres;

/*
CREATE TRIGGER t_project_build_schedule_tests
  BEFORE UPDATE OR INSERT OR DELETE
  ON schedule."PROJECT_BUILD_SCHEDULE_TESTS"
  FOR EACH ROW
  EXECUTE PROCEDURE schedule.if_project_build_schedule_tests();
*/

