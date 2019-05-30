
CREATE OR REPLACE FUNCTION tr.importTRData()
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


    -- import TR data;
    FOR q IN SELECT DISTINCT "TRNumber",                       
                            "TRDraftNumber",                  
                            "ProjectNumber",                  
                            "TTIModelNumber",                 
                            "BrandProjectNum",                
                            "BrandModelNum",                  
                            "SampleProduction",               
                            "TestPhaseNew",                   
                            "StartTestDate",                  
                            "LabEstCompDate",                 
                            "AssignToEngineerTime",           
                            "SupervisorApprovedTime",         
                            "SampleDescription",              
                            "BrandProjectDescription",        
                            "BrandModelDescription",
                            timestamptr
               FROM "TR_IMP_TMP_WORK_ORDER_RAW"
           ORDER BY timestamptr asc
     LOOP

        vcd_tr_test_request = NULL;


        SELECT  cd_tr_test_request INTO vcd_tr_test_request  FROM tr."TR_TEST_REQUEST" where ds_tr_number = q."TRNumber";
        -- Insert
        IF NOT FOUND THEN
            SELECT  nextval('tr."PROJECT_TEST_REQUEST_cd_project_test_request_seq"'::regclass) INTO vcd_tr_test_request;



            INSERT INTO tr."TR_TEST_REQUEST"
            ( cd_tr_test_request,
             ds_tr_number                      ,
             ds_draft_number                   ,
             ds_tti_project_number_tr          ,
             ds_tti_project_model_number_tr    ,
             ds_met_project_number_tr          ,
             ds_met_project_model_number_tr    ,
             ds_sample_production              ,
             ds_test_phase                     ,
             dt_start_test                     ,
             dt_lab_estimated_completion       ,
             dt_assigned_to_engineer           ,
             dt_supervisor_approval            ,
             ds_sample_description             ,
             ds_project_description_tr         ,
             ds_project_model_description_tr   
           ) values (
           vcd_tr_test_request, 
           q."TRNumber",                       
           q."TRDraftNumber",                  
           q."ProjectNumber",                  
           q."TTIModelNumber",                 
           q."BrandProjectNum",                
           q."BrandModelNum",                  
           q."SampleProduction",               
           q."TestPhaseNew",                   
           q."StartTestDate"::timestamp,                  
           q."LabEstCompDate"::timestamp,                 
           q."AssignToEngineerTime"::timestamp,           
           q."SupervisorApprovedTime"::timestamp,         
           q."SampleDescription",              
           q."BrandProjectDescription",        
           q."BrandModelDescription"           
           ) ;


        ELSE
        -- update
            UPDATE tr."TR_TEST_REQUEST"
            SET ds_tr_number                    = q."TRNumber",
                ds_draft_number                 = q."TRDraftNumber",
                ds_tti_project_number_tr        = q."ProjectNumber",
                ds_tti_project_model_number_tr  = q."TTIModelNumber",
                ds_met_project_number_tr        = q."BrandProjectNum",
                ds_met_project_model_number_tr  = q."BrandModelNum", 
                ds_sample_production            = q."SampleProduction",
                ds_test_phase                   = q."TestPhaseNew",
                dt_start_test                   = q."StartTestDate"::timestamp,
                dt_lab_estimated_completion     = q."LabEstCompDate"::timestamp,
                dt_assigned_to_engineer         = q."AssignToEngineerTime"::timestamp,
                dt_supervisor_approval          = q."SupervisorApprovedTime"::timestamp,
                ds_sample_description           = q."SampleDescription",
                ds_project_description_tr       = q."BrandProjectDescription",
                ds_project_model_description_tr = q."BrandModelDescription",
                dt_update                       = now()
            WHERE cd_tr_test_request = vcd_tr_test_request;

        END IF;

    END LOOP;


    INSERT INTO tr."TR_TEST_REQUEST_WORK_ORDER_STATUS"
        (cd_tr_test_request_work_order_status, ds_tr_test_request_work_order_status)
    SELECT distinct "Status", "StatusDescription" 
      FROM  "TR_IMP_TMP_WORK_ORDER_RAW" 
     WHERE NOT EXISTS ( select 1 from tr."TR_TEST_REQUEST_WORK_ORDER_STATUS" x WHERE x.cd_tr_test_request_work_order_status = "TR_IMP_TMP_WORK_ORDER_RAW"."Status" );


    -- Work Order information

    FOR q IN SELECT DISTINCT "WorkOrderID",
                    "TestItem",
                    "TestProcedureName",
                    "ToolQty",
                    "SampleToolsList",
                    "Goal",
                    "Status",
                    "AssignToTechnicianTime",
                    "TypeTest",
                    "TRNumber",
                    "GoalUnits",
                    "WorkOrderName",
                    "MSCDRequirement",
                    "StartDate",
                    "CompletionDate",
                     timestampwo
               FROM "TR_IMP_TMP_WORK_ORDER_RAW"
             ORDER BY timestampwo ASC
         
    LOOP

        vcd_tr_test_request = NULL;
        SELECT  cd_tr_test_request INTO vcd_tr_test_request  FROM tr."TR_TEST_REQUEST" where ds_tr_number = q."TRNumber";

        vcd_tr_test_request_work_order = NULL;
        SELECT  cd_tr_test_request_work_order INTO vcd_tr_test_request_work_order FROM tr."TR_TEST_REQUEST_WORK_ORDER" where nr_work_order = q."WorkOrderID";

        IF NOT FOUND THEN
            SELECT  nextval('tr."TR_TEST_REQUEST_WORK_ORDER_cd_tr_test_request_work_order_seq"'::regclass) INTO vcd_tr_test_request_work_order;

            INSERT INTO  tr."TR_TEST_REQUEST_WORK_ORDER"
            ( nr_work_order                         ,
              ds_test_item                          ,
              ds_test_procedure_name                ,
              nr_sample_qtty                        ,
              ds_sample_list                        ,
              ds_goal                               ,
              cd_tr_test_request_work_order_status  ,
              dt_assign_to_technician               ,
              cd_tr_test_request                    ,
              ds_type_test                          ,
              cd_tr_test_request_work_order         ,
              ds_goal_unit                          ,
              ds_work_order_name                    ,
              ds_requirements                       ,
              dt_test_start                         ,
              dt_test_end
            ) 
            values 
          (	q."WorkOrderID",
            q."TestItem",
            q."TestProcedureName",
            q."ToolQty",
            q."SampleToolsList",
            q."Goal",
            q."Status",
            q."AssignToTechnicianTime"::timestamp,
            vcd_tr_test_request,
            q."TypeTest",
            vcd_tr_test_request_work_order,
            q."GoalUnits",
            q."WorkOrderName",
            q."MSCDRequirement" ,
            q."StartDate",
            q."CompletionDate"
           );

        ELSE

            UPDATE tr."TR_TEST_REQUEST_WORK_ORDER"
            SET nr_work_order                       = q."WorkOrderID",
              ds_test_item                          = q."TestItem",
              ds_test_procedure_name                = q."TestProcedureName",
              nr_sample_qtty                        = q."ToolQty",
              ds_sample_list                        = q."SampleToolsList",
              ds_goal                               = q."Goal",
              cd_tr_test_request_work_order_status  = q."Status",
              dt_assign_to_technician               = q."AssignToTechnicianTime"::timestamp,
              cd_tr_test_request                    = vcd_tr_test_request,
              ds_type_test                          = q."TypeTest",
              ds_goal_unit                          = q."GoalUnits",
              ds_work_order_name                    = q."WorkOrderName",
              ds_requirements                       = q."MSCDRequirement" ,
              dt_test_start                         = q."StartDate",
              dt_test_end                           = q."CompletionDate"
            WHERE  cd_tr_test_request_work_order = vcd_tr_test_request_work_order;
        END IF;


    END LOOP;

    -- work order samples
    FOR q IN SELECT DISTINCT "WorkOrderID",
                    "SampleNumber",
                    "TestResults",
                    "Remark",
                    "UpdatedTime",
                    "UpdatedBy",
                    "ToolStatus",
                    "WUCompleted",
                     timestampsample
               FROM "TR_IMP_TMP_WORK_ORDER_RAW"
               WHERE coalesce("SampleNumber" , '') != ''
             ORDER BY timestampsample ASC
         
    LOOP

        vcd_tr_test_request_work_order = NULL;
        SELECT  cd_tr_test_request_work_order INTO vcd_tr_test_request_work_order FROM tr."TR_TEST_REQUEST_WORK_ORDER" where nr_work_order = q."WorkOrderID";
        
        SELECT  cd_tr_test_request_work_order_sample INTO vcd_tr_test_request_work_order_sample 
         FROM tr."TR_TEST_REQUEST_WORK_ORDER_SAMPLE" 
        WHERE cd_tr_test_request_work_order = vcd_tr_test_request_work_order AND nr_sample = q."SampleNumber"::integer;




        IF NOT FOUND THEN
            SELECT  nextval('tr."TR_TEST_REQUEST_WORK_ORDER_SA_cd_tr_test_request_work_order_seq"'::regclass) INTO vcd_tr_test_request_work_order_sample;

            INSERT INTO tr."TR_TEST_REQUEST_WORK_ORDER_SAMPLE"
           (   cd_tr_test_request_work_order, 
                   nr_sample,    
                   ds_remarks,   
                   dt_update,  
                   ds_updated_by,  
                   ds_test_result,
                   cd_tr_test_request_work_order_sample,
                   ds_sample_status,
                   nr_wu_completed
           ) values (
                   vcd_tr_test_request_work_order,
                   q."SampleNumber"::integer,
                   q."Remark",
                   q."UpdatedTime"::timestamp,
                   q."UpdatedBy",
                   q."TestResults",
                   vcd_tr_test_request_work_order_sample,
                   q."ToolStatus",
                   q."WUCompleted"
           );
        ELSE

            UPDATE tr."TR_TEST_REQUEST_WORK_ORDER_SAMPLE"
            SET cd_tr_test_request_work_order = vcd_tr_test_request_work_order,
                nr_sample                      = q."SampleNumber"::integer,
                ds_remarks                     = q."Remark",
                dt_update                      = q."UpdatedTime"::timestamp,
                ds_updated_by                  = q."UpdatedBy",
                ds_test_result                 = q."TestResults",
                ds_sample_status               = q."ToolStatus",
                nr_wu_completed                = q."WUCompleted"
            WHERE cd_tr_test_request_work_order_sample = vcd_tr_test_request_work_order_sample;



        END IF;


    END LOOP;


    PERFORM adjustTRData();

END



$$  LANGUAGE plpgsql;

ALTER FUNCTION tr.importTRData() SET search_path=pg_catalog, public, rfq, tr, tti;
