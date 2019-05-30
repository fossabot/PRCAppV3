<?php

include_once APPPATH . "models/modelBasicExtend.php";

class project_build_schedule_tests_wo_model extends modelBasicExtend {

    function __construct() {

        $this->table = "PROJECT_BUILD_SCHEDULE_TESTS_WO";

        $this->pk_field = "cd_project_build_schedule_tests_wo";
        $this->ds_field = "ds_project_build_schedule_tests";
        $this->prodCatUnique = 'N';
        $this->hasDeactivate = false;
        $this->orderByDefault = ' ORDER BY nr_work_order';

        $this->sequence_obj = '"PROJECT_BUILD_SCHEDULE_TESTS__cd_project_build_schedule_te_seq1"';

        $this->controller = 'schedule/project_build_schedule_tests_wo';



        $this->fieldsforGrid = array(
            ' "PROJECT_BUILD_SCHEDULE_TESTS_WO".cd_project_build_schedule_tests_wo',
            ' "PROJECT_BUILD_SCHEDULE_TESTS_WO".cd_project_build_schedule_tests',
            ' "PROJECT_BUILD_SCHEDULE_TESTS_WO".cd_tr_test_request_work_order',
            ' "TR_TEST_REQUEST_WORK_ORDER".nr_work_order',
            ' "TR_TEST_REQUEST_WORK_ORDER".ds_test_item',
            ' "TR_TEST_REQUEST_WORK_ORDER".ds_test_procedure_name',
            ' "TR_TEST_REQUEST_WORK_ORDER".nr_sample_qtty',
            ' "TR_TEST_REQUEST_WORK_ORDER".ds_sample_list',
            '(SELECT array_to_string(array_agg(s.nr_sample::text), \',\') 
                from ( select x.nr_sample, x.cd_tr_test_request_work_order 
                       from "TR_TEST_REQUEST_WORK_ORDER_SAMPLE" x  
                       where x.cd_tr_test_request_work_order = "TR_TEST_REQUEST_WORK_ORDER".cd_tr_test_request_work_order
                       ORDER BY x.nr_sample) as s) as ds_sample_list_table',
            ' "TR_TEST_REQUEST_WORK_ORDER".ds_goal',
            ' "TR_TEST_REQUEST_WORK_ORDER".cd_tr_test_request_work_order_status',
            '( select ds_tr_test_request_work_order_status FROM "TR_TEST_REQUEST_WORK_ORDER_STATUS" WHERE cd_tr_test_request_work_order_status =  "TR_TEST_REQUEST_WORK_ORDER".cd_tr_test_request_work_order_status) as ds_tr_test_request_work_order_status',
            ' "TR_TEST_REQUEST_WORK_ORDER".dt_assign_to_technician',
            ' "TR_TEST_REQUEST_WORK_ORDER".cd_tr_test_request',
            ' "TR_TEST_REQUEST_WORK_ORDER".ds_type_test',
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
            //' "TR_TEST_REQUEST_WORK_ORDER".ds_remarks',
            //$grid->addColumn('ds_remarks', 'Remarks', '150px', $f->retTypeStringAny(), false);
            ' "PROJECT".ds_project',
            ' "PROJECT".ds_tti_project',
            ' "PROJECT".ds_met_project',
            ' "PROJECT_MODEL".ds_tti_project_model',
            ' "PROJECT_MODEL".ds_met_project_model',
            ' "TR_TEST_REQUEST".cd_project_build',
            '( select ds_project_build FROM "PROJECT_BUILD" WHERE cd_project_build =  "TR_TEST_REQUEST".cd_project_build) as ds_project_build'
        );
        $this->fieldsUpd = array("cd_project_build_schedule_tests_wo", "cd_project_build_schedule_tests", "cd_tr_test_request_work_order");

        $join = array(
            'JOIN "PROJECT_BUILD_SCHEDULE_TESTS" schtable ON (schtable.cd_project_build_schedule_tests = "PROJECT_BUILD_SCHEDULE_TESTS_WO".cd_project_build_schedule_tests) ',
            'JOIN "PROJECT_BUILD_SCHEDULE" ON ("PROJECT_BUILD_SCHEDULE".cd_project_build_schedule = schtable.cd_project_build_schedule) ',
            'JOIN "TR_TEST_REQUEST_WORK_ORDER" ON ("TR_TEST_REQUEST_WORK_ORDER".cd_tr_test_request_work_order = "PROJECT_BUILD_SCHEDULE_TESTS_WO".cd_tr_test_request_work_order )',
            'JOIN "TR_TEST_REQUEST" ON ( "TR_TEST_REQUEST".cd_tr_test_request = "TR_TEST_REQUEST_WORK_ORDER".cd_tr_test_request) ',
            'LEFT OUTER JOIN "PROJECT_MODEL"   ON ("PROJECT_MODEL".cd_project_model = "TR_TEST_REQUEST".cd_project_model ) ',
            'LEFT OUTER JOIN "PROJECT"         ON ("PROJECT".cd_project = "PROJECT_MODEL".cd_project) ',
        );


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            //"stylecond" => "(CASE WHEN \"PROJECT_BUILD_SCHEDULE_TESTS_WO\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true,
            'join' => $join
        );


        $this->load->model('tr/tr_test_request_work_order_sample_model', 'smpmodel');

        parent::__construct();
    }

    public function retTRMETData($cd_project_build_schedule) {
        $this->load->model('tr/tr_test_request_work_order_sample_model', 'smpmodel');
        $this->load->model('mtr/mtr_reports_model', 'mtrmodel');

        $ret = $this->retRetrieveArray(" WHERE \"PROJECT_BUILD_SCHEDULE\".cd_project_build_schedule = $cd_project_build_schedule ");

        $wo = array();

        foreach ($ret as $key => $value) {
            array_push($wo, $value['nr_work_order']);
        }

        
        
        //$mtrdata = $this->mtrmodel->retRetrieveTestReportFileByWO($wo);
        

        $trdata = $this->smpmodel->retRetrieveArray(" WHERE \"PROJECT_BUILD_SCHEDULE\".cd_project_build_schedule = $cd_project_build_schedule ", 'ORDER BY 1', $this->smpmodel->retrOptionsExternal);
 
/*
        foreach ($trdata as $key => $value) {
        foreach ($mtrdata as $keymtr => $valuemtr) {

            if ((float) $valuemtr['WorkOrderId'] == (float) $value['nr_work_order']) {
                $trdata[$key]['ds_filename_tr'] = $valuemtr['AttachmentFileName'];
                break;
            }
        }
        }
*/

        return $trdata;


    }

}
