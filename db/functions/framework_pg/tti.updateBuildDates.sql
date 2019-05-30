
CREATE OR REPLACE FUNCTION tti.updateBuildDates
(
    PAR_cd_project_model bigint
)
RETURNS varchar AS
$$
DECLARE
v_result varchar (10);
v_start timestamp;
v_end timestamp;
v_record record;
BEGIN

    FOR v_record IN
    SELECT min(b.dt_est_start) as dt_est_start, max(b.dt_est_finish) as dt_est_finish, a.cd_project_build_schedule
    FROM "PROJECT_BUILD_SCHEDULE" a, "PROJECT_BUILD_SCHEDULE_TESTS" b
   WHERE a.cd_project_model          = PAR_cd_project_model
     AND b.cd_project_build_schedule = a.cd_project_build_schedule
     AND b.dt_est_start IS NOT NULL AND b.dt_est_finish IS NOT NULL 
     AND b.dt_est_start > '2000-01-01'
   GROUP BY a.cd_project_build_schedule
   LOOP 

        UPDATE "PROJECT_BUILD_SCHEDULE"
           SET dt_est_start  = v_record.dt_est_start,
               dt_est_finish = v_record.dt_est_finish
         WHERE cd_project_build_schedule = v_record.cd_project_build_schedule
           AND ( dt_est_start   IS DISTINCT FROM v_record.dt_est_start OR dt_est_finish  IS DISTINCT FROM v_record.dt_est_finish );



   END LOOP;



return v_result;
END;
$$
LANGUAGE plpgsql;

