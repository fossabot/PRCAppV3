<?php

include_once APPPATH . "models/modelBasicExtend.php";

class supplier_model extends modelBasicExtend {

    function __construct() {

        $this->table = "SUPPLIER";

        $this->pk_field = "cd_supplier";
        $this->ds_field = "ds_supplier";
        $this->prodCatUnique = 'N';

        $this->sequence_obj = '"SUPPLIER_cd_supplier_seq"';

        $this->controller = 'rfq/supplier';

        $this->fieldsforGrid = array(
            ' "SUPPLIER".cd_supplier',
            ' "SUPPLIER".ds_supplier',
            ' "SUPPLIER".ds_address',
            ' "SUPPLIER".ds_email',
            ' "SUPPLIER".ds_contact_name',
            ' "SUPPLIER".cd_country',
            '( select ds_country FROM "COUNTRY" WHERE cd_country =  "SUPPLIER".cd_country) as ds_country',
            ' "SUPPLIER".ds_phone_number',
            ' "SUPPLIER".dt_deactivated',
            ' "SUPPLIER".ds_vendor_code',
            ' "SUPPLIER".fl_tti_supplier',
            ' "SUPPLIER".nr_latitude',
            ' "SUPPLIER".nr_longitude',
            ' "SUPPLIER".ds_website',
            ' "SUPPLIER".ds_supplier_alt',
            ' "SUPPLIER".nr_tax_default',
            ' ( "SUPPLIER".ds_vendor_code:: text || \' - \' ||  "SUPPLIER".ds_supplier || COALESCE(( CASE WHEN "SUPPLIER".ds_supplier !=  COALESCE("SUPPLIER".ds_supplier_alt, "SUPPLIER".ds_supplier) THEN \' - \' || "SUPPLIER".ds_supplier_alt ELSE \'\' END ), \'\')  )  as ds_supplier_full ');
        
        $this->fieldsUpd = array("nr_longitude", "nr_latitude",  "fl_tti_supplier", "cd_supplier", "ds_supplier", "ds_address", "ds_email", "ds_contact_name", "cd_country", "ds_phone_number", "ds_website", "dt_deactivated", "ds_vendor_code", "ds_supplier_alt", "nr_tax_default");


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "stylecond" => "(CASE WHEN \"SUPPLIER\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );

        

        $this->fieldsForPLBaseDD = array( $this->pk_field, // first always PK
            ' "SUPPLIER".ds_vendor_code:: text || \' - \' || ( "SUPPLIER".ds_supplier || COALESCE(( CASE WHEN "SUPPLIER".ds_supplier !=  COALESCE("SUPPLIER".ds_supplier_alt, "SUPPLIER".ds_supplier) THEN \' - \' || "SUPPLIER".ds_supplier_alt ELSE \'\' END ), \'\')  )',  // second is always the description showing up. on the dropdown
            'nr_tax_default' 
        );        
        
        $this->fieldsForPLBase = array( $this->pk_field, // first always PK
            ' ( "SUPPLIER".ds_vendor_code:: text || \' - \' ||  "SUPPLIER".ds_supplier || COALESCE(( CASE WHEN "SUPPLIER".ds_supplier !=  COALESCE("SUPPLIER".ds_supplier_alt, "SUPPLIER".ds_supplier) THEN \' - \' || "SUPPLIER".ds_supplier_alt ELSE \'\' END ), \'\')  )  as description ',           // second is always the description showing up. on the dropdown
            'nr_tax_default' 
        );        

        parent::__construct();
    }

}
