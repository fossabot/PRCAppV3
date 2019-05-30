<?php

include_once APPPATH . "models/modelBasicExtend.php";

class rfq_item_supplier_sample_request_model extends modelBasicExtend {

    function __construct() {

        $this->table = "RFQ_ITEM_SUPPLIER_SAMPLE_REQUEST";

        $this->pk_field = "cd_rfq_item_supplier_sample_request";
        $this->ds_field = "ds_rfq_item_supplier";
        $this->prodCatUnique = 'N';

        $this->sequence_obj = '"RFQ_ITEM_SUPPLIER_SAMPLE_REQU_cd_rfq_item_supplier_sample_r_seq"';

        $this->controller = 'rfq/rfq_item_supplier_sample_request';

        $this->fieldsforGrid = array(
            ' "RFQ_ITEM_SUPPLIER_SAMPLE_REQUEST".cd_rfq_item_supplier_sample_request',
            ' "RFQ_ITEM_SUPPLIER_SAMPLE_REQUEST".cd_rfq_item_supplier',
            ' ( "SUPPLIER".ds_vendor_code:: text || \' - \' ||  "SUPPLIER".ds_supplier || COALESCE(( CASE WHEN "SUPPLIER".ds_supplier !=  COALESCE("SUPPLIER".ds_supplier_alt, "SUPPLIER".ds_supplier) THEN \' - \' || "SUPPLIER".ds_supplier_alt ELSE \'\' END ), \'\')  )  as ds_supplier ',
            ' "RFQ_ITEM_SUPPLIER_SAMPLE_REQUEST".dt_requested',
            ' "RFQ_ITEM_SUPPLIER_SAMPLE_REQUEST".cd_human_resource_request',
            '( select ds_human_resource_full FROM "HUMAN_RESOURCE" WHERE cd_human_resource =  "RFQ_ITEM_SUPPLIER_SAMPLE_REQUEST".cd_human_resource_request) as ds_human_resource_request',
            ' "RFQ_ITEM_SUPPLIER_SAMPLE_REQUEST".dt_deadline_to_receive',
            ' "RFQ_ITEM_SUPPLIER_SAMPLE_REQUEST".nr_quantity',
            ' "RFQ_ITEM_SUPPLIER_SAMPLE_REQUEST".cd_human_resource_received',
            '( select ds_human_resource_full FROM "HUMAN_RESOURCE" WHERE cd_human_resource =  "RFQ_ITEM_SUPPLIER_SAMPLE_REQUEST".cd_human_resource_received) as ds_human_resource_received',
            ' "RFQ_ITEM_SUPPLIER_SAMPLE_REQUEST".dt_received',
            ' "RFQ_ITEM_SUPPLIER_SAMPLE_REQUEST".cd_approval_status',
            '( select ds_approval_status FROM "APPROVAL_STATUS" WHERE cd_approval_status =  "RFQ_ITEM_SUPPLIER_SAMPLE_REQUEST".cd_approval_status) as ds_approval_status',
            ' "RFQ_ITEM_SUPPLIER_SAMPLE_REQUEST".cd_human_resource_approval',
            '( select ds_human_resource_full FROM "HUMAN_RESOURCE" WHERE cd_human_resource =  "RFQ_ITEM_SUPPLIER_SAMPLE_REQUEST".cd_human_resource_approval) as ds_human_resource_approval',
            ' "RFQ_ITEM_SUPPLIER_SAMPLE_REQUEST".ds_comments',
            ' "RFQ_ITEM_SUPPLIER_SAMPLE_REQUEST".dt_record');
        
        $this->fieldsUpd = array("cd_rfq_item_supplier_sample_request", "cd_rfq_item_supplier", "dt_requested", "cd_human_resource_request", "dt_deadline_to_receive", "nr_quantity", "cd_human_resource_received", "dt_received", "cd_approval_status", "cd_human_resource_approval","ds_comments", "dt_record",);

        $join = array('JOIN "RFQ_ITEM_SUPPLIER" ON ("RFQ_ITEM_SUPPLIER_SAMPLE_REQUEST".cd_rfq_item_supplier = "RFQ_ITEM_SUPPLIER".cd_rfq_item_supplier ) ',
                'JOIN "SUPPLIER" ON ("SUPPLIER".cd_supplier = "RFQ_ITEM_SUPPLIER".cd_supplier ) ');

        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            //"stylecond" => "(CASE WHEN \"RFQ_ITEM_SUPPLIER_SAMPLE_REQUEST\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true,
            'join' => $join
        );


        parent::__construct();
    }

}
