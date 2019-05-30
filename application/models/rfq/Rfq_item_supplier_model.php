<?php

include_once APPPATH . "models/modelBasicExtend.php";

class rfq_item_supplier_model extends modelBasicExtend {

    function __construct() {

        $this->table = "RFQ_ITEM_SUPPLIER";

        $this->pk_field = "cd_rfq_item_supplier";
        $this->ds_field = "ds_rfq_item";
        $this->prodCatUnique = 'N';
        $this->hasDeactivate = false;
 
        $this->sequence_obj = '"RFQ_ITEM_SUPPLIER_cd_rfq_item_supplier_seq"';

        $this->controller = 'rfq/rfq_item_supplier';

        $this->fieldsforGrid = array(
            ' "RFQ_ITEM_SUPPLIER".cd_rfq_item_supplier',
            ' "RFQ_ITEM_SUPPLIER".cd_rfq_item',
            ' "RFQ_ITEM_SUPPLIER".cd_supplier',
            '( COALESCE (( SELECT MAX(nr_round) FROM "RFQ_ITEM_SUPPLIER_QUOTATION" where "RFQ_ITEM_SUPPLIER_QUOTATION".cd_rfq_item_supplier = "RFQ_ITEM_SUPPLIER".cd_rfq_item_supplier ),0)) as nr_round ',
            ' ( "SUPPLIER".ds_vendor_code || \' - \' ||  "SUPPLIER".ds_supplier || COALESCE(( CASE WHEN "SUPPLIER".ds_supplier !=  COALESCE("SUPPLIER".ds_supplier_alt, "SUPPLIER".ds_supplier) THEN \' - \' || "SUPPLIER".ds_supplier_alt ELSE \'\' END ), \'\')  )  as ds_supplier ',
            ' "RFQ_ITEM_SUPPLIER".ds_supplier_equipment_description',
            ' "RFQ_ITEM_SUPPLIER".ds_supplier_equipment_part_number',
            '"RFQ_ITEM_SUPPLIER".nr_tax',
            ' "RFQ_ITEM_SUPPLIER".dt_record');
        $this->fieldsUpd = array("cd_rfq_item_supplier", "cd_rfq_item", "cd_supplier", "ds_supplier_equipment_description", "ds_supplier_equipment_part_number", "dt_record","nr_tax");
        $join = array('JOIN "SUPPLIER" ON ("SUPPLIER".cd_supplier = "RFQ_ITEM_SUPPLIER".cd_supplier ) '        );


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            //"stylecond" => "(CASE WHEN \"RFQ_ITEM_SUPPLIER\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true,
            'join' => $join     
        );


        parent::__construct();
    }

}
