<?php

include_once APPPATH . "models/modelBasicExtend.php";

class test_request_purpose_model extends modelBasicExtend {

    function __construct() {

        $this->table = "TEST_REQUEST_PURPOSE";

        $this->pk_field = "cd_test_request_purpose";
        $this->ds_field = "ds_test_request_purpose";
        $this->prodCatUnique = 'N';

        $this->sequence_obj = '"TEST_REQUEST_PURPOSE_cd_test_request_purpose_seq"';

        $this->controller = 'tr/test_request_purpose';


        $this->fieldsforGrid = array(
            ' "TEST_REQUEST_PURPOSE".cd_test_request_purpose',
            ' "TEST_REQUEST_PURPOSE".ds_test_request_purpose',
            ' "TEST_REQUEST_PURPOSE".dt_deactivated',
            ' "TEST_REQUEST_PURPOSE".dt_record');
        $this->fieldsUpd = array("cd_test_request_purpose", "ds_test_request_purpose", "dt_deactivated", "dt_record",);


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "stylecond" => "(CASE WHEN \"TEST_REQUEST_PURPOSE\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );


        parent::__construct();
    }

}
