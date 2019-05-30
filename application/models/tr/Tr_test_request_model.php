<?php

include_once APPPATH . "models/modelBasicExtend.php";

class tr_test_request_model extends modelBasicExtend {

    function __construct() {

        $this->table = "TR_TEST_REQUEST";

        $this->pk_field = "cd_tr_test_request";
        $this->ds_field = "ds_tr_number";
        $this->prodCatUnique = 'N';

        $this->sequence_obj = '"TR_TEST_REQUEST_cd_tr_test_request_seq"';

        $this->controller = 'tr/tr_test_request';
        $this->hasDeactivate = false;

        $this->fieldsforGrid = array(
            ' "TR_TEST_REQUEST".cd_tr_test_request',
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
            '( select ds_project_model FROM "PROJECT_MODEL" WHERE cd_project_model =  "TR_TEST_REQUEST".cd_project_model) as ds_project_model',
            ' "TR_TEST_REQUEST".cd_project_build',
            '( select ds_project_build FROM "PROJECT_BUILD" WHERE cd_project_build =  "TR_TEST_REQUEST".cd_project_build) as ds_project_build');
        $this->fieldsUpd = array("cd_tr_test_request", "ds_tr_number", "ds_draft_number", "ds_tti_project_number_tr", "ds_tti_project_model_number_tr", "ds_met_project_number_tr", "ds_met_project_model_number_tr", "ds_sample_production", "ds_test_phase", "dt_start_test", "dt_lab_estimated_completion", "dt_assigned_to_engineer", "dt_supervisor_approval", "ds_sample_description", "ds_project_description_tr", "ds_project_model_description_tr", "cd_project_model", "cd_project_build",);


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );


        parent::__construct();
    }

}
