<?php

include_once APPPATH . "models/modelBasicExtend.php";

class tr_test_request_work_order_sample_model extends modelBasicExtend {

    function __construct() {

        $this->table = "TR_TEST_REQUEST_WORK_ORDER_SAMPLE";

        $this->pk_field = "cd_tr_test_request_work_order_sample";
        $this->ds_field = "ds_tr_test_request_work_order";
        $this->prodCatUnique = 'N';
        
        $this->sequence_obj = '"TR_TEST_REQUEST_WORK_ORDER_SA_cd_tr_test_request_work_order_seq"';

        $this->controller = 'tr/tr_test_request_work_order_sample';

        $canSeeAll = $this->getCdbhelper()->getUserPermission('fl_see_all_projects');
        $hmcode = $this->session->userdata('cd_human_resource');
        
        $forcedWhereConfidential = '';
        if ($canSeeAll == 'N') {
            $forcedWhereConfidential = " AND ( fl_confidential = 'N' OR EXISTS ( SELECT 1 FROM \"PROJECT_USER_ROLES\" x WHERE x.cd_project_model = \"PROJECT_MODEL\".cd_project_model AND x.cd_human_resource = $hmcode AND fl_active = 'Y') )";
        }
            
            


        $this->fieldsforGrid = array(
            ' "TR_TEST_REQUEST_WORK_ORDER_SAMPLE".cd_tr_test_request_work_order_sample',
            ' "TR_TEST_REQUEST_WORK_ORDER_SAMPLE".cd_tr_test_request_work_order',
            ' "TR_TEST_REQUEST_WORK_ORDER_SAMPLE".nr_sample',
            ' "TR_TEST_REQUEST_WORK_ORDER_SAMPLE".ds_remarks',
            ' "TR_TEST_REQUEST_WORK_ORDER_SAMPLE".dt_update',
            ' "TR_TEST_REQUEST_WORK_ORDER_SAMPLE".ds_updated_by',
            ' "TR_TEST_REQUEST_WORK_ORDER_SAMPLE".ds_sample_status',
            ' "TR_TEST_REQUEST_WORK_ORDER_SAMPLE".ds_test_result');
        $this->fieldsUpd = array("cd_tr_test_request_work_order_sample", "cd_tr_test_request_work_order", "nr_sample", "ds_remarks", "dt_update", "ds_updated_by", "ds_test_result",);


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "stylecond" => "(CASE WHEN \"TR_TEST_REQUEST_WORK_ORDER_SAMPLE\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );



        $this->fieldsforFullGrid = array(
            ' "TR_TEST_REQUEST_WORK_ORDER".cd_tr_test_request_work_order',
            ' "TR_TEST_REQUEST_WORK_ORDER".nr_work_order',
            ' "TR_TEST_REQUEST_WORK_ORDER".ds_test_item',
            ' "TR_TEST_REQUEST_WORK_ORDER".ds_test_procedure_name',
            ' "TR_TEST_REQUEST_WORK_ORDER".nr_sample_qtty',
            ' "TR_TEST_REQUEST_WORK_ORDER".ds_sample_list',
            ' "TR_TEST_REQUEST_WORK_ORDER".ds_work_order_name',
            ' "TR_TEST_REQUEST_WORK_ORDER".ds_requirements',
            ' "TR_TEST_REQUEST_WORK_ORDER".ds_goal_unit',
            ' "TR_TEST_REQUEST_WORK_ORDER".ds_goal',
            ' "TR_TEST_REQUEST_WORK_ORDER".cd_tr_test_request_work_order_status',
            '( select ds_tr_test_request_work_order_status FROM "TR_TEST_REQUEST_WORK_ORDER_STATUS" WHERE cd_tr_test_request_work_order_status =  "TR_TEST_REQUEST_WORK_ORDER".cd_tr_test_request_work_order_status) as ds_tr_test_request_work_order_status',
            ' "TR_TEST_REQUEST_WORK_ORDER".dt_assign_to_technician',
            ' "TR_TEST_REQUEST_WORK_ORDER".cd_tr_test_request',
//            ' "TR_TEST_REQUEST_WORK_ORDER".ds_type_test',
            ' "TR_TEST_REQUEST".ds_tr_number',
            ' "TR_TEST_REQUEST".ds_draft_number',
            ' "TR_TEST_REQUEST".ds_tti_project_number_tr',
            ' "TR_TEST_REQUEST".ds_tti_project_model_number_tr',
            ' "TR_TEST_REQUEST".ds_met_project_number_tr',
            ' "TR_TEST_REQUEST".ds_met_project_model_number_tr',
            ' "TR_TEST_REQUEST".ds_sample_production',
            ' "TR_TEST_REQUEST".ds_test_phase',
            ' "TR_TEST_REQUEST".dt_start_test',
            ' "TR_TEST_REQUEST".dt_lab_estimated_completion',
            ' "TR_TEST_REQUEST".dt_assigned_to_engineer',
            ' "TR_TEST_REQUEST".dt_supervisor_approval',
            ' "TR_TEST_REQUEST".ds_sample_description',
            ' "TR_TEST_REQUEST".ds_project_description_tr',
            ' "TR_TEST_REQUEST".ds_project_model_description_tr',
            ' "TR_TEST_REQUEST".cd_project_model',
            ' "PROJECT_MODEL".ds_project_model',
            ' "PROJECT".ds_project',
            ' "PROJECT".ds_tti_project',
            ' "PROJECT".ds_met_project',
            ' "PROJECT_MODEL".ds_tti_project_model',
            ' "PROJECT_MODEL".ds_met_project_model',
            ' "TR_TEST_REQUEST".cd_project_build',
            '( select ds_project_build FROM "PROJECT_BUILD" WHERE cd_project_build =  "TR_TEST_REQUEST".cd_project_build) as ds_project_build',
            '( select count(1) FROM "TR_TEST_REQUEST_WORK_ORDER_SAMPLE_DOCUMENT_REPOSITORY" WHERE cd_tr_test_request_work_order_sample =  "TR_TEST_REQUEST_WORK_ORDER_SAMPLE".cd_tr_test_request_work_order_sample) as nr_count_attachmnet',
            ' "TR_TEST_REQUEST_WORK_ORDER_SAMPLE".cd_tr_test_request_work_order_sample',
            ' "TR_TEST_REQUEST_WORK_ORDER_SAMPLE".cd_tr_test_request_work_order',
            ' "TR_TEST_REQUEST_WORK_ORDER_SAMPLE".nr_sample',
            ' "TR_TEST_REQUEST_WORK_ORDER_SAMPLE".ds_remarks',
            ' "TR_TEST_REQUEST_WORK_ORDER_SAMPLE".dt_update',
            ' "TR_TEST_REQUEST_WORK_ORDER_SAMPLE".ds_updated_by',
            ' "TR_TEST_REQUEST_WORK_ORDER_SAMPLE".ds_test_result',
            ' "TR_TEST_REQUEST_WORK_ORDER_SAMPLE".ds_sample_status'
        );

        $joinFull = array('JOIN "TR_TEST_REQUEST_WORK_ORDER" ON ( "TR_TEST_REQUEST_WORK_ORDER".cd_tr_test_request_work_order = "TR_TEST_REQUEST_WORK_ORDER_SAMPLE".cd_tr_test_request_work_order) ',
            'JOIN "PROJECT_BUILD_SCHEDULE_TESTS_WO" ON ( "PROJECT_BUILD_SCHEDULE_TESTS_WO".cd_tr_test_request_work_order = "TR_TEST_REQUEST_WORK_ORDER".cd_tr_test_request_work_order ) ',
            'JOIN "PROJECT_BUILD_SCHEDULE_TESTS" ON ("PROJECT_BUILD_SCHEDULE_TESTS".cd_project_build_schedule_tests = "PROJECT_BUILD_SCHEDULE_TESTS_WO".cd_project_build_schedule_tests) ',
            'JOIN "PROJECT_BUILD_SCHEDULE" ON ("PROJECT_BUILD_SCHEDULE".cd_project_build_schedule = "PROJECT_BUILD_SCHEDULE_TESTS".cd_project_build_schedule) ',
            'JOIN "TR_TEST_REQUEST" ON ( "TR_TEST_REQUEST".cd_tr_test_request = "TR_TEST_REQUEST_WORK_ORDER".cd_tr_test_request) ',
            'JOIN "PROJECT_MODEL"   ON ("PROJECT_MODEL".cd_project_model = "PROJECT_BUILD_SCHEDULE".cd_project_model ) ',
            'JOIN "PROJECT"         ON ("PROJECT".cd_project = "PROJECT_MODEL".cd_project) ',
        );


        $this->retrFullOptions = array("fieldrecid" => $this->pk_field,
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforFullGrid),
            "json" => true,
            'join' => $joinFull
        );


        /* grid with external data */

        $this->fieldsforExternal = array(
            '"TR_TEST_REQUEST_WORK_ORDER_SAMPLE".cd_tr_test_request_work_order_sample',
            ' "TR_TEST_REQUEST_WORK_ORDER".cd_tr_test_request_work_order',
            ' "TR_TEST_REQUEST_WORK_ORDER".nr_work_order',
            ' "TR_TEST_REQUEST_WORK_ORDER".ds_test_item',
            ' "TR_TEST_REQUEST_WORK_ORDER".ds_test_procedure_name',
            ' "TR_TEST_REQUEST_WORK_ORDER".nr_sample_qtty',
            ' "TR_TEST_REQUEST_WORK_ORDER".ds_sample_list',
            ' "TR_TEST_REQUEST_WORK_ORDER".ds_work_order_name',
            ' "TR_TEST_REQUEST_WORK_ORDER".ds_requirements',
            ' "TR_TEST_REQUEST_WORK_ORDER".ds_goal_unit',
            ' "TR_TEST_REQUEST_WORK_ORDER".ds_goal',
            '( select ds_tr_test_request_work_order_status FROM "TR_TEST_REQUEST_WORK_ORDER_STATUS" WHERE cd_tr_test_request_work_order_status =  "TR_TEST_REQUEST_WORK_ORDER".cd_tr_test_request_work_order_status) as ds_tr_test_request_work_order_status',
            ' "TR_TEST_REQUEST_WORK_ORDER".dt_assign_to_technician',
            ' "TR_TEST_REQUEST".ds_tr_number',
            ' "TR_TEST_REQUEST".dt_start_test',
            ' "TR_TEST_REQUEST".dt_lab_estimated_completion',
            ' "TR_TEST_REQUEST".dt_assigned_to_engineer',
            ' "TR_TEST_REQUEST".dt_supervisor_approval',
            ' "TR_TEST_REQUEST".ds_sample_description',
            ' "TR_TEST_REQUEST".ds_project_description_tr',
            ' "TR_TEST_REQUEST".ds_project_model_description_tr',
            ' "TR_TEST_REQUEST".cd_project_model',
            ' "PROJECT_MODEL".ds_project_model',
            ' "PROJECT".ds_project',
            ' "PROJECT".ds_tti_project',
            ' "PROJECT".ds_met_project',
            ' "PROJECT_MODEL".ds_tti_project_model',
            ' "PROJECT_MODEL".ds_met_project_model',
            '( select count(1) FROM "TR_TEST_REQUEST_WORK_ORDER_SAMPLE_DOCUMENT_REPOSITORY" WHERE cd_tr_test_request_work_order_sample =  "TR_TEST_REQUEST_WORK_ORDER_SAMPLE".cd_tr_test_request_work_order_sample) as nr_count_attachmnet',
            ' "TR_TEST_REQUEST_WORK_ORDER_SAMPLE".nr_sample',
            ' "TR_TEST_REQUEST_WORK_ORDER_SAMPLE".ds_remarks',
            ' "TR_TEST_REQUEST_WORK_ORDER_SAMPLE".ds_test_result',
            ' "TR_TEST_REQUEST_WORK_ORDER".ds_type_test',
            /* Starting */
            "( CASE WHEN u.nr_workorder_number IS NOT NULL THEN 1 WHEN bl.nr_wo_data IS NOT NULL THEN 2 WHEN mte.nr_wo_code IS NOT NULL THEN 3 ELSE 0 END ) as nr_source_from",
            "( CASE WHEN u.nr_workorder_number IS NOT NULL THEN 'FIXTURE LIFE' WHEN bl.nr_wo_data IS NOT NULL THEN 'FIXTURE LIFE' WHEN mte.nr_wo_code IS NOT NULL THEN 'MTE'  END ) as ds_source",
            "( COALESCE(u.nr_cycle_completed, bl.ds_comp_cycle , mte.nr_completed_cycle) ) as ds_comp_cycle",
            "( COALESCE(u.it_test_elapse_time::text, bl.ds_comp_runtime::text, mte.ds_completed_runtime::text )) as ds_comp_runtime",
            "( mte.nr_completed_app ) as ds_comp_apps ",
            "( mte.nr_completed_discharge ) as ds_comp_discharge",
            "( COALESCE( to_char(mte.dt_wo_start_date, 'mm/dd/yyyy'), to_char(bl.dt_start_date, 'mm/dd/yyyy') , to_char(u.dt_actual_start, 'mm/dd/yyyy') ) ) as ds_start_date ",
            "( to_char(mte.dt_estimate_completion_date, 'mm/dd/yyyy')) as ds_estimated_complete ",
            "( COALESCE( to_char(mte.dt_actual_completion_date, 'mm/dd/yyyy'), to_char(bl.dt_actual_complete, 'mm/dd/yyyy') ) ) as ds_actual_complete ",
            "( COALESCE(u.ds_fixture_id, bl.ds_work_station, mte.ds_workstation )) as ds_work_station",
            "( mte.ds_operator) as ds_operator",
            "( CASE WHEN u.nr_workorder_number IS NOT NULL THEN 'ULBS' WHEN bl.nr_wo_data IS NOT NULL THEN 'F6-3F Fixture Life' WHEN mte.nr_wo_code IS NOT NULL THEN mte.ds_room_code END ) as ds_room_code",
            "( COALESCE(u.ds_test_status, bl.ds_tool_status, mte.ds_tool_status )) as ds_tool_status",
            "mtr.ds_file_name"
        );

        $joinExternal = array('JOIN "TR_TEST_REQUEST_WORK_ORDER" ON ( "TR_TEST_REQUEST_WORK_ORDER".cd_tr_test_request_work_order = "TR_TEST_REQUEST_WORK_ORDER_SAMPLE".cd_tr_test_request_work_order) ',
            'JOIN "PROJECT_BUILD_SCHEDULE_TESTS_WO" ON ( "PROJECT_BUILD_SCHEDULE_TESTS_WO".cd_tr_test_request_work_order = "TR_TEST_REQUEST_WORK_ORDER".cd_tr_test_request_work_order ) ',
            'JOIN "PROJECT_BUILD_SCHEDULE_TESTS" ON ("PROJECT_BUILD_SCHEDULE_TESTS".cd_project_build_schedule_tests = "PROJECT_BUILD_SCHEDULE_TESTS_WO".cd_project_build_schedule_tests) ',
            'JOIN "PROJECT_BUILD_SCHEDULE" ON ("PROJECT_BUILD_SCHEDULE".cd_project_build_schedule = "PROJECT_BUILD_SCHEDULE_TESTS".cd_project_build_schedule) ',
            'JOIN "TR_TEST_REQUEST" ON ( "TR_TEST_REQUEST".cd_tr_test_request = "TR_TEST_REQUEST_WORK_ORDER".cd_tr_test_request) ',
            'JOIN "PROJECT_MODEL"   ON ("PROJECT_MODEL".cd_project_model = "PROJECT_BUILD_SCHEDULE".cd_project_model ) ',
            'JOIN "PROJECT"         ON ("PROJECT".cd_project = "PROJECT_MODEL".cd_project) ',
            'LEFT OUTER JOIN "ULBS_SUMMARY"              u ON ( u.nr_workorder_number =  "TR_TEST_REQUEST_WORK_ORDER".nr_work_order AND u.nr_tool_number = "TR_TEST_REQUEST_WORK_ORDER_SAMPLE".nr_sample ) ',
            'LEFT OUTER JOIN "VIEW_COLLECT_BRAKE_LIFE"  bl ON ( bl.nr_wo_data =  "TR_TEST_REQUEST_WORK_ORDER".nr_work_order AND bl.nr_sample = "TR_TEST_REQUEST_WORK_ORDER_SAMPLE".nr_sample ) ',
            'LEFT OUTER JOIN "VIEW_MTE_WO_SAMPLE"      mte ON ( mte.nr_wo_code  =  "TR_TEST_REQUEST_WORK_ORDER".nr_work_order AND mte.nr_sample = "TR_TEST_REQUEST_WORK_ORDER_SAMPLE".nr_sample ) ',
            'LEFT OUTER JOIN "MTR_REPORT"               mtr ON ( mtr.nr_work_order =  "TR_TEST_REQUEST_WORK_ORDER".nr_work_order AND coalesce(mtr.ds_approved_by, \'\') != \'\' ) ',
        );


        $this->retrOptionsExternal = array("fieldrecid" => $this->pk_field,
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforExternal),
            "json" => true,
            'join' => $joinExternal
        );





        /* grid with external data */

        $this->fieldsforToBrowse = array(
            '"PROJECT".ds_tti_project',
            '"PROJECT".ds_met_project',
            '"PROJECT_MODEL".ds_tti_project_model',
            '"PROJECT_MODEL".ds_met_project_model',
            '"PROJECT".ds_project',
            '"PROJECT_MODEL".ds_project_model',
            '( select ds_project_tool_type FROM "PROJECT_TOOL_TYPE" WHERE cd_project_tool_type =  "PROJECT".cd_project_tool_type) as ds_project_tool_type',
            '( select ds_department FROM "DEPARTMENT" WHERE cd_department =  "PROJECT".cd_department) as ds_department',
            '( select ds_project_status FROM "PROJECT_STATUS" WHERE cd_project_status =  "PROJECT_MODEL".cd_project_status) as ds_project_status',
            '( select ds_project_product FROM "PROJECT_PRODUCT" WHERE cd_project_product =  "PROJECT".cd_project_product) as ds_project_product',
            '( select ds_project_voltage FROM "PROJECT_VOLTAGE" WHERE cd_project_voltage =  "PROJECT_MODEL".cd_project_voltage) as ds_project_voltage',
            ' "PROJECT".fl_confidential ',
            '( select ds_project_power_type FROM "PROJECT_POWER_TYPE" WHERE cd_project_power_type =  "PROJECT".cd_project_power_type) as ds_project_power_type',
            '"TR_TEST_REQUEST_WORK_ORDER_SAMPLE".cd_tr_test_request_work_order_sample',
            ' "TR_TEST_REQUEST_WORK_ORDER".cd_tr_test_request_work_order',
            ' "TR_TEST_REQUEST_WORK_ORDER".nr_work_order',
            ' "TR_TEST_REQUEST_WORK_ORDER".ds_test_item',
            ' "TR_TEST_REQUEST_WORK_ORDER".ds_test_procedure_name',
            ' "TR_TEST_REQUEST_WORK_ORDER".nr_sample_qtty',
            ' "TR_TEST_REQUEST_WORK_ORDER".ds_sample_list',
            ' "TR_TEST_REQUEST_WORK_ORDER".ds_work_order_name',
            ' "TR_TEST_REQUEST_WORK_ORDER".ds_requirements',
            ' "TR_TEST_REQUEST_WORK_ORDER".ds_goal_unit',
            ' "TR_TEST_REQUEST_WORK_ORDER".ds_goal',
            '( select ds_tr_test_request_work_order_status FROM "TR_TEST_REQUEST_WORK_ORDER_STATUS" WHERE cd_tr_test_request_work_order_status =  "TR_TEST_REQUEST_WORK_ORDER".cd_tr_test_request_work_order_status) as ds_tr_test_request_work_order_status',
            ' "TR_TEST_REQUEST_WORK_ORDER".dt_assign_to_technician',
            ' "TR_TEST_REQUEST".ds_tr_number',
            ' "TR_TEST_REQUEST".dt_start_test',
            ' "TR_TEST_REQUEST".dt_lab_estimated_completion',
            ' "TR_TEST_REQUEST".dt_assigned_to_engineer',
            ' "TR_TEST_REQUEST".dt_supervisor_approval',
            ' "TR_TEST_REQUEST".ds_sample_description',
            ' "TR_TEST_REQUEST".ds_project_description_tr',
            ' "TR_TEST_REQUEST".ds_project_model_description_tr',
            ' "TR_TEST_REQUEST".cd_project_model',
            '( select count(1) FROM "TR_TEST_REQUEST_WORK_ORDER_SAMPLE_DOCUMENT_REPOSITORY" WHERE cd_tr_test_request_work_order_sample =  "TR_TEST_REQUEST_WORK_ORDER_SAMPLE".cd_tr_test_request_work_order_sample) as nr_count_attachmnet',
            ' "TR_TEST_REQUEST_WORK_ORDER_SAMPLE".nr_sample',
            ' "TR_TEST_REQUEST_WORK_ORDER_SAMPLE".ds_remarks',
            ' "TR_TEST_REQUEST_WORK_ORDER_SAMPLE".ds_test_result',
            ' "TR_TEST_REQUEST_WORK_ORDER".ds_type_test',
            /* Starting */
            "( CASE WHEN u.nr_workorder_number IS NOT NULL THEN 1 WHEN bl.nr_wo_data IS NOT NULL THEN 2 WHEN mte.nr_wo_code IS NOT NULL THEN 3 ELSE 0 END ) as nr_source_from",
            "( CASE WHEN u.nr_workorder_number IS NOT NULL THEN 'FIXTURE LIFE' WHEN bl.nr_wo_data IS NOT NULL THEN 'FIXTURE LIFE' WHEN mte.nr_wo_code IS NOT NULL THEN 'MTE' END ) as ds_source",
            "( COALESCE(u.nr_cycle_completed, bl.ds_comp_cycle , mte.nr_completed_cycle) ) as ds_comp_cycle",
            "( COALESCE(u.it_test_elapse_time::text, bl.ds_comp_runtime::text, mte.ds_completed_runtime::text )) as ds_comp_runtime",
            "( mte.nr_completed_app ) as ds_comp_apps ",
            "( mte.nr_completed_discharge ) as ds_comp_discharge",
            "( COALESCE( to_char(mte.dt_wo_start_date, 'mm/dd/yyyy'), to_char(bl.dt_start_date, 'mm/dd/yyyy') , to_char(u.dt_actual_start, 'mm/dd/yyyy') ) ) as ds_start_date ",
            "( to_char(mte.dt_estimate_completion_date, 'mm/dd/yyyy')) as ds_estimated_complete ",
            "( COALESCE( to_char(mte.dt_actual_completion_date, 'mm/dd/yyyy'), to_char(bl.dt_actual_complete, 'mm/dd/yyyy') ) ) as ds_actual_complete ",
            "( COALESCE(u.ds_fixture_id, bl.ds_work_station, mte.ds_workstation )) as ds_work_station",
            "( mte.ds_operator) as ds_operator",
            "( CASE WHEN u.nr_workorder_number IS NOT NULL THEN 'ULBS' WHEN bl.nr_wo_data IS NOT NULL THEN 'F6-3F Fixture Life' WHEN mte.nr_wo_code IS NOT NULL THEN mte.ds_room_code END ) as ds_room_code",
            "( COALESCE(u.ds_test_status, bl.ds_tool_status, mte.ds_tool_status )) as ds_tool_status",
            "mtr.ds_file_name",
            '( b.ds_project_build_abbreviation || ( CASE WHEN b.fl_allow_multiples = \'Y\' THEN "PROJECT_BUILD_SCHEDULE".nr_version::text ELSE \'\' END ) ) as ds_project_build_full',
            '( select ds_test_type FROM "TEST_TYPE" WHERE cd_test_type =  "PROJECT_BUILD_SCHEDULE_TESTS".cd_test_type) as ds_test_type',
        );

        $joinToBrowse = array('JOIN "TR_TEST_REQUEST_WORK_ORDER" ON ( "TR_TEST_REQUEST_WORK_ORDER".cd_tr_test_request_work_order = "TR_TEST_REQUEST_WORK_ORDER_SAMPLE".cd_tr_test_request_work_order) ',
            'JOIN "PROJECT_BUILD_SCHEDULE_TESTS_WO" ON ( "PROJECT_BUILD_SCHEDULE_TESTS_WO".cd_tr_test_request_work_order = "TR_TEST_REQUEST_WORK_ORDER".cd_tr_test_request_work_order ) ',
            'JOIN "PROJECT_BUILD_SCHEDULE_TESTS" ON ("PROJECT_BUILD_SCHEDULE_TESTS".cd_project_build_schedule_tests = "PROJECT_BUILD_SCHEDULE_TESTS_WO".cd_project_build_schedule_tests) ',
            'JOIN "PROJECT_BUILD_SCHEDULE" ON ("PROJECT_BUILD_SCHEDULE".cd_project_build_schedule = "PROJECT_BUILD_SCHEDULE_TESTS".cd_project_build_schedule) ',
            'JOIN "TR_TEST_REQUEST" ON ( "TR_TEST_REQUEST".cd_tr_test_request = "TR_TEST_REQUEST_WORK_ORDER".cd_tr_test_request) ',
            'JOIN "PROJECT_MODEL"   ON ("PROJECT_MODEL".cd_project_model = "PROJECT_BUILD_SCHEDULE".cd_project_model ) ',
            'JOIN "PROJECT"         ON ("PROJECT".cd_project = "PROJECT_MODEL".cd_project) ',
            'LEFT OUTER JOIN "ULBS_SUMMARY"              u ON ( u.nr_workorder_number =  "TR_TEST_REQUEST_WORK_ORDER".nr_work_order AND u.nr_tool_number = "TR_TEST_REQUEST_WORK_ORDER_SAMPLE".nr_sample ) ',
            'LEFT OUTER JOIN "VIEW_COLLECT_BRAKE_LIFE"  bl ON ( bl.nr_wo_data =  "TR_TEST_REQUEST_WORK_ORDER".nr_work_order AND bl.nr_sample = "TR_TEST_REQUEST_WORK_ORDER_SAMPLE".nr_sample ) ',
            'LEFT OUTER JOIN "VIEW_MTE_WO_SAMPLE"       mte ON ( mte.nr_wo_code  =  "TR_TEST_REQUEST_WORK_ORDER".nr_work_order AND mte.nr_sample = "TR_TEST_REQUEST_WORK_ORDER_SAMPLE".nr_sample ) ',
            'LEFT OUTER JOIN "MTR_REPORT"               mtr ON ( mtr.nr_work_order =  "TR_TEST_REQUEST_WORK_ORDER".nr_work_order AND coalesce(mtr.ds_approved_by, \'\') != \'\' ) ',
            'LEFT OUTER JOIN "PROJECT_BUILD"            b   ON ( b.cd_project_build  =  "PROJECT_BUILD_SCHEDULE".cd_project_build ) ',
        );

        

        $this->retrOptionstToBrowse = array("fieldrecid" => $this->pk_field,
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforToBrowse),
            "json" => true,
            'join' => $joinToBrowse,
            'forcedWhere' => $forcedWhereConfidential
        );


        parent::__construct();
    }

}
