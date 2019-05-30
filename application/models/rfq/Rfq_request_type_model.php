<?php

include_once APPPATH . "models/modelBasicExtend.php";

class rfq_request_type_model extends modelBasicExtend {

    function __construct() {

        $this->table = "RFQ_REQUEST_TYPE";

        $this->pk_field = "cd_rfq_request_type";
        $this->ds_field = "ds_rfq_request_type";
        $this->prodCatUnique = 'N';

        $this->sequence_obj = '"RFQ_REQUEST_TYPE_cd_rfq_request_type_seq"';

        $this->controller = 'rfq/rfq_request_type';


        $this->fieldsforGrid = array(
            ' "RFQ_REQUEST_TYPE".cd_rfq_request_type',
            ' "RFQ_REQUEST_TYPE".ds_rfq_request_type',
            ' "RFQ_REQUEST_TYPE".dt_deactivated',
            ' "RFQ_REQUEST_TYPE".fl_is_repair',
            ' "RFQ_REQUEST_TYPE".fl_is_new',
            ' "RFQ_REQUEST_TYPE".dt_record');
        $this->fieldsUpd = array("cd_rfq_request_type", "ds_rfq_request_type", "dt_deactivated", "dt_record","fl_is_repair", "fl_is_new");


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "stylecond" => "(CASE WHEN \"RFQ_REQUEST_TYPE\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );


        parent::__construct();
    }

}
