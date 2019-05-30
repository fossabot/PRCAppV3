<?php

include_once APPPATH . "models/modelBasicExtend.php";

class test_request_origin_model extends modelBasicExtend {

    function __construct() {

        $this->table = "TEST_REQUEST_ORIGIN";

        $this->pk_field = "cd_test_request_origin";
        $this->ds_field = "ds_test_request_origin";
        $this->prodCatUnique = 'N';

        $this->sequence_obj = '"TEST_REQUEST_ORIGIN_cd_test_request_origin_seq"';

        $this->controller = 'tr/test_request_origin';


        $this->fieldsforGrid = array(
            ' "TEST_REQUEST_ORIGIN".cd_test_request_origin',
            ' "TEST_REQUEST_ORIGIN".ds_test_request_origin',
            ' "TEST_REQUEST_ORIGIN".dt_deactivated',
            ' "TEST_REQUEST_ORIGIN".dt_record');
        $this->fieldsUpd = array("cd_test_request_origin", "ds_test_request_origin", "dt_deactivated", "dt_record",);


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "stylecond" => "(CASE WHEN \"TEST_REQUEST_ORIGIN\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );


        parent::__construct();
    }

}
