
CREATE OR REPLACE FUNCTION tr.adjustTRData()
  RETURNS void    AS
$$
DECLARE 
q record;
vcd_tr_test_request bigint;
vcd_tr_test_request_work_order bigint;
vcd_tr_test_request_work_order_status bigint;
vcd_project_model bigint;
vcd_project_build bigint;
vcd_tr_test_request_work_order_sample bigint;

BEGIN

    drop table if exists tmpModels ; 

    create temporary table tmpModels as
    SELECT cd_project_model, 
           COALESCE(TRIM(LEADING '0' FROM p.ds_met_project), 'a') as ds_met_project,
           COALESCE(TRIM(LEADING '0' FROM p.ds_tti_project), 'a') as ds_tti_project,
           COALESCE(TRIM(LEADING '0' FROM m.ds_met_project_model), 'a') as ds_met_project_model,
           COALESCE(TRIM(LEADING '0' FROM m.ds_tti_project_model), 'a') as ds_tti_project_model
       FROM "PROJECT" p,
            "PROJECT_MODEL" m
    where p.cd_project = m.cd_project;

    drop table if exists tmpTRs; 

    create temporary table tmpTRs as
    SELECT cd_tr_test_request, 
           COALESCE(TRIM(LEADING '0' FROM "TR_TEST_REQUEST".ds_met_project_number_tr ), 'b') as ds_met_project_number_tr,
           COALESCE(TRIM(LEADING '0' FROM "TR_TEST_REQUEST".ds_tti_project_number_tr ), 'b' ) as ds_tti_project_number_tr,
           COALESCE(TRIM(LEADING '0' FROM "TR_TEST_REQUEST".ds_met_project_model_number_tr ), 'b') as ds_met_project_model_number_tr,
            COALESCE(TRIM(LEADING '0' FROM "TR_TEST_REQUEST".ds_tti_project_model_number_tr ), 'b')  as ds_tti_project_model_number_tr
       FROM "TR_TEST_REQUEST"
      WHERE cd_project_model IS NULL;
    



    UPDATE "TR_TEST_REQUEST" 
        SET cd_project_model = r.cd_project_model
         FROM  (select t.cd_tr_test_request, max(m.cd_project_model) as cd_project_model
                 from tmpTRs t, tmpModels m
               where ( m.ds_met_project       =  t.ds_met_project_number_tr        OR m.ds_tti_project      = t.ds_tti_project_number_tr )
                 AND ( m.ds_met_project_model =  t.ds_met_project_model_number_tr  OR m.ds_tti_project_model = t.ds_tti_project_model_number_tr )
                group by t.cd_tr_test_request ) as r
        WHERE r.cd_tr_test_request = "TR_TEST_REQUEST".cd_tr_test_request;


    -- set completed date if exists:
    drop table if exists tmpCompleteDates;

    create temporary table tmpCompleteDates as 
    SELECT distinct c.cd_project_build_schedule_tests
    FROM "TR_IMP_TMP_WORK_ORDER_RAW" a, "TR_TEST_REQUEST_WORK_ORDER" b, "PROJECT_BUILD_SCHEDULE_TESTS_WO" c 
    where b.nr_work_order = a."WorkOrderID"
      and c.cd_tr_test_request_work_order = b.cd_tr_test_request_work_order; 

    PERFORM updatePlanningCompleteDate(cd_project_build_schedule_tests) FROM tmpCompleteDates;

END



$$  LANGUAGE plpgsql;

ALTER FUNCTION tr.adjustTRData() SET search_path=pg_catalog, public, tr, tti, schedule;

-- for the other procedure

/*

    
*/

