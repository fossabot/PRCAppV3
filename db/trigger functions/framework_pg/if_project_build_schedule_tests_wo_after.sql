-- Function: audit.if_tablelog()

-- DROP FUNCTION audit.if_tablelog();


CREATE OR REPLACE FUNCTION schedule.if_project_build_schedule_tests_wo_after()
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
        PERFORM updatePlanningCompleteDate(NEW.cd_project_build_schedule_tests);

    --delete
    ELSIF (TG_OP = 'DELETE') THEN
        PERFORM updatePlanningCompleteDate(OLD.cd_project_build_schedule_tests);

    --insert
    ELSIF (TG_OP = 'INSERT') THEN
        PERFORM updatePlanningCompleteDate(NEW.cd_project_build_schedule_tests);
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
ALTER FUNCTION schedule.if_project_build_schedule_tests_wo_after() SET search_path=pg_catalog, public, spec, tr, schedule;

ALTER FUNCTION schedule.if_project_build_schedule_tests_wo_after() OWNER TO postgres;


CREATE TRIGGER if_project_build_schedule_tests_wo_after
  AFTER INSERT OR UPDATE OR DELETE
  ON schedule."PROJECT_BUILD_SCHEDULE_TESTS_WO"
  FOR EACH ROW
  EXECUTE PROCEDURE schedule.if_project_build_schedule_tests_wo_after();


