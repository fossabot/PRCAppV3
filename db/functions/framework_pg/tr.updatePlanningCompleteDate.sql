
CREATE OR REPLACE FUNCTION tr.updatePlanningCompleteDate(PAR_cd_project_build_schedule_tests bigint)
  RETURNS void    AS
$$
DECLARE 
q record;
vcd_project_build_schedule_tests bigint;
vdt_start timestamp without time zone;
vdt_end timestamp without time zone;


BEGIN

    
    select c.cd_project_build_schedule_tests, min(COALESCE(a.dt_test_start, a.dt_assign_to_technician)) as dt_test_start, max(a.dt_test_end) as dt_test_end
      INTO vcd_project_build_schedule_tests, vdt_start, vdt_end
      from "TR_TEST_REQUEST_WORK_ORDER" a, "PROJECT_BUILD_SCHEDULE_TESTS_WO" c
     where c.cd_project_build_schedule_tests = PAR_cd_project_build_schedule_tests
       and c.cd_tr_test_request_work_order = a.cd_tr_test_request_work_order
       and not exists ( SELECT 1 
                          FROM "TR_TEST_REQUEST_WORK_ORDER" b, "PROJECT_BUILD_SCHEDULE_TESTS_WO" d
                         WHERE d.cd_project_build_schedule_tests = c.cd_project_build_schedule_tests 
                           AND b.cd_tr_test_request_work_order   = d.cd_tr_test_request_work_order
                           AND b.dt_test_end IS NULL ) 
    group by c.cd_project_build_schedule_tests;

    IF NOT FOUND THEN
        vdt_start = NULL;
        vdt_end = NULL;
    END IF;

    UPDATE "PROJECT_BUILD_SCHEDULE_TESTS"
      SET dt_actual_start  = vdt_start,
          dt_actual_finish = vdt_end
     WHERE cd_project_build_schedule_tests = PAR_cd_project_build_schedule_tests
       AND ( dt_actual_start IS DISTINCT FROM vdt_start OR dt_actual_finish IS DISTINCT FROM vdt_end);

END



$$  LANGUAGE plpgsql;

ALTER FUNCTION tr.updatePlanningCompleteDate(bigint) SET search_path=pg_catalog, public, tr, tti, schedule;

-- for the other procedure

/*

    
*/

