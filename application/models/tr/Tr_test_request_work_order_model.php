<?php

include_once APPPATH . "models/modelBasicExtend.php";

class tr_test_request_work_order_model extends modelBasicExtend {

    function __construct() {

        $this->table = "TR_TEST_REQUEST_WORK_ORDER";

        $this->pk_field = "cd_tr_test_request_work_order";
        $this->ds_field = "nr_work_order";
        $this->prodCatUnique = 'N';

        $this->sequence_obj = '"TR_TEST_REQUEST_WORK_ORDER_cd_tr_test_request_work_order_seq"';

        $this->controller = 'tr/tr_test_request_work_order';
        $this->hasDeactivate = false;

        $this->fieldsforGrid = array(
            ' "TR_TEST_REQUEST_WORK_ORDER".cd_tr_test_request_work_order',
            ' "TR_TEST_REQUEST_WORK_ORDER".nr_work_order',
            ' "TR_TEST_REQUEST_WORK_ORDER".ds_test_item',
            ' "TR_TEST_REQUEST_WORK_ORDER".ds_test_procedure_name',
            ' "TR_TEST_REQUEST_WORK_ORDER".nr_sample_qtty',
            ' "TR_TEST_REQUEST_WORK_ORDER".ds_sample_list',
            ' "TR_TEST_REQUEST_WORK_ORDER".ds_goal',
            ' "TR_TEST_REQUEST_WORK_ORDER".cd_tr_test_request_work_order_status',
            ' "TR_TEST_REQUEST_WORK_ORDER".ds_work_order_name',
            ' "TR_TEST_REQUEST_WORK_ORDER".ds_requirements',
            ' "TR_TEST_REQUEST_WORK_ORDER".ds_goal_unit',
            
                         '(SELECT array_to_string(array_agg(s.nr_sample::text), \',\') 
                from ( select x.nr_sample, x.cd_tr_test_request_work_order 
                       from "TR_TEST_REQUEST_WORK_ORDER_SAMPLE" x  
                       where x.cd_tr_test_request_work_order = "TR_TEST_REQUEST_WORK_ORDER".cd_tr_test_request_work_order
                       ORDER BY x.nr_sample) as s) as ds_sample_list_table',


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
            ' "PROJECT".ds_project',
            ' "PROJECT".ds_tti_project',
            ' "PROJECT".ds_met_project',
            ' "PROJECT_MODEL".ds_tti_project_model',
            ' "PROJECT_MODEL".ds_met_project_model',
            ' "TR_TEST_REQUEST".cd_project_build',
            '( select ds_project_build FROM "PROJECT_BUILD" WHERE cd_project_build =  "TR_TEST_REQUEST".cd_project_build) as ds_project_build'            
            
            );
        $this->fieldsUpd = array("cd_tr_test_request_work_order", "nr_work_order", "ds_test_item", "ds_test_procedure_name", "nr_sample_qtty", "ds_sample_list", "ds_goal", "cd_tr_test_request_work_order_status", "dt_assign_to_technician", "cd_tr_test_request", "ds_type_test",);

        $join = array('JOIN "TR_TEST_REQUEST" ON ( "TR_TEST_REQUEST".cd_tr_test_request = "TR_TEST_REQUEST_WORK_ORDER".cd_tr_test_request) ',
                      'LEFT OUTER JOIN "PROJECT_MODEL"   ON ("PROJECT_MODEL".cd_project_model = "TR_TEST_REQUEST".cd_project_model ) ',
                      'LEFT OUTER JOIN "PROJECT"         ON ("PROJECT".cd_project = "PROJECT_MODEL".cd_project) ',
            );
        

        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true,
            'join' => $join 
        );


        parent::__construct();
    }

}
