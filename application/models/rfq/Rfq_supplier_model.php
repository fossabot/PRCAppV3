<?php

include_once APPPATH . "models/modelBasicExtend.php";

class rfq_supplier_model extends modelBasicExtend {

    function __construct() {

        $this->table = "RFQ_SUPPLIER";

        $this->pk_field = "cd_rfq_supplier";
        $this->ds_field = "ds_rfq";
        $this->prodCatUnique = 'N';
        $this->hasDeactivate = false;
        $this->sequence_obj = '"RFQ_SUPPLIER_cd_rfq_supplier_seq"';

        $this->controller = 'rfq/rfq_supplier';

        $this->fieldsforGrid = array(
            ' "RFQ_SUPPLIER".cd_rfq_supplier',
            ' "RFQ_SUPPLIER".cd_rfq',
            '( select fl_is_urgent FROM "RFQ" WHERE cd_rfq =  "RFQ_SUPPLIER".cd_rfq) as ds_rfq',
            ' "RFQ_SUPPLIER".cd_supplier',
            ' ( "SUPPLIER".ds_vendor_code:: text || \' - \' ||  "SUPPLIER".ds_supplier || COALESCE(( CASE WHEN "SUPPLIER".ds_supplier !=  COALESCE("SUPPLIER".ds_supplier_alt, "SUPPLIER".ds_supplier) THEN \' - \' || "SUPPLIER".ds_supplier_alt ELSE \'\' END ), \'\')  )  as ds_supplier ',
            ' "RFQ_SUPPLIER".nr_tax', 
            ' "RFQ_SUPPLIER".nr_round' );
        
        $this->fieldsUpd = array("cd_rfq_supplier", "cd_rfq", "cd_supplier", "nr_tax","nr_round", "fl_tti_supplier");

        $join = array('JOIN "SUPPLIER" ON ("SUPPLIER".cd_supplier = "RFQ_SUPPLIER".cd_supplier ) ' );
        
        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            //"stylecond" => "(CASE WHEN \"RFQ_SUPPLIER\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true,
            'join' => $join
        );


        parent::__construct();
    }
    
    

}
