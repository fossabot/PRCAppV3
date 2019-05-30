-- Function: audit.if_tablelog()

-- DROP FUNCTION audit.if_tablelog();


CREATE OR REPLACE FUNCTION schedule.if_project_build_comments()
  RETURNS trigger AS
$BODY$
DECLARE
    v_error_text text;
    v_error_code int;
    v_code_deactivated int;
    v_code_reactivate int;


BEGIN
    -- start without error!
    v_error_code = 0;

    v_code_deactivated = public.getSysParameter('PROJECT_BUILD_DEACTIVATE_COMMENT');
    v_code_reactivate  = public.getSysParameter('PROJECT_BUILD_REINSTATE_COMMENT');


   --updates
    IF (TG_OP = 'UPDATE') THEN
        NEW.dt_update = NOW();

    --delete
    ELSIF (TG_OP = 'DELETE') THEN
    UPDATE "PROJECT_MODEL" 
       SET dt_update = transaction_timestamp() 
      FROM "PROJECT_BUILD_SCHEDULE"
     WHERE "PROJECT_BUILD_SCHEDULE".cd_project_build_schedule = OLD.cd_project_build_schedule
       AND "PROJECT_MODEL".cd_project_model = "PROJECT_BUILD_SCHEDULE".cd_project_model 
       AND "PROJECT_MODEL".dt_update != transaction_timestamp();


    --insert
    ELSIF (TG_OP = 'INSERT') THEN
        NEW.dt_update = NOW();

        IF NEW.cd_project_comments_type = v_code_deactivated THEN
            UPDATE "PROJECT_BUILD_SCHEDULE" 
               SET dt_deactivated = NOW(),
                   cd_human_resource_deactivated = get_var('cd_human_resource')::bigint
             WHERE "PROJECT_BUILD_SCHEDULE".cd_project_build_schedule = NEW.cd_project_build_schedule;
        END IF;

        IF NEW.cd_project_comments_type = v_code_reactivate THEN
            UPDATE "PROJECT_BUILD_SCHEDULE" 
               SET dt_deactivated = NULL,
                   cd_human_resource_deactivated = NULL
             WHERE "PROJECT_BUILD_SCHEDULE".cd_project_build_schedule = NEW.cd_project_build_schedule;
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
ALTER FUNCTION schedule.if_project_build_comments() SET search_path=pg_catalog, public, tti, schedule;

ALTER FUNCTION schedule.if_project_build_comments() OWNER TO postgres;





