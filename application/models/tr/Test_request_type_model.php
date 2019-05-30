<?php

include_once APPPATH . "models/modelBasicExtend.php";

class test_request_type_model extends modelBasicExtend {

    function __construct() {

        $this->table = "TEST_REQUEST_TYPE";

        $this->pk_field = "cd_test_request_type";
        $this->ds_field = "ds_test_request_type";
        $this->prodCatUnique = 'N';

        $this->sequence_obj = '"TEST_REQUEST_TYPE_cd_test_request_type_seq"';

        $this->controller = 'tr/test_request_type';


        $this->fieldsforGrid = array(
            ' "TEST_REQUEST_TYPE".cd_test_request_type',
            ' "TEST_REQUEST_TYPE".ds_test_request_type',
            ' "TEST_REQUEST_TYPE".dt_deactivated',
            ' "TEST_REQUEST_TYPE".dt_record');
        $this->fieldsUpd = array("cd_test_request_type", "ds_test_request_type", "dt_deactivated", "dt_record",);


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "stylecond" => "(CASE WHEN \"TEST_REQUEST_TYPE\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );


        parent::__construct();
    }

}
