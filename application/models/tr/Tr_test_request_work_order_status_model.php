<?php

include_once APPPATH . "models/modelBasicExtend.php";

class tr_test_request_work_order_status_model extends modelBasicExtend {

    function __construct() {

        $this->table = "TR_TEST_REQUEST_WORK_ORDER_STATUS";

        $this->pk_field = "cd_tr_test_request_work_order_status";
        $this->ds_field = "ds_tr_test_request_work_order_status";
        $this->prodCatUnique = 'N';

        $this->sequence_obj = '"TR_TEST_REQUEST_WORK_ORDER_ST_cd_tr_test_request_work_order_seq"';

        $this->controller = 'tr/tr_test_request_work_order_status';


        $this->fieldsforGrid = array(
            ' "TR_TEST_REQUEST_WORK_ORDER_STATUS".cd_tr_test_request_work_order_status',
            ' "TR_TEST_REQUEST_WORK_ORDER_STATUS".ds_tr_test_request_work_order_status',
            ' "TR_TEST_REQUEST_WORK_ORDER_STATUS".dt_deactivated',
            ' "TR_TEST_REQUEST_WORK_ORDER_STATUS".dt_record');
        $this->fieldsUpd = array("cd_tr_test_request_work_order_status", "ds_tr_test_request_work_order_status", "dt_deactivated", "dt_record",);


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "stylecond" => "(CASE WHEN \"TR_TEST_REQUEST_WORK_ORDER_STATUS\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );


        parent::__construct();
    }

}
