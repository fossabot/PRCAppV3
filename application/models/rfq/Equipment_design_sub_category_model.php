<?php

include_once APPPATH . "models/modelBasicExtend.php";

class equipment_design_sub_category_model extends modelBasicExtend {

    function __construct() {

        $this->table = "EQUIPMENT_DESIGN_SUB_CATEGORY";

        $this->pk_field = "cd_equipment_design_sub_category";
        $this->ds_field = "ds_equipment_design_sub_category";
        $this->prodCatUnique = 'N';

        $this->sequence_obj = '"EQUIPMENT_SUB_CATEGORY_cd_equipment_sub_category_seq"';

        $this->controller = 'rfq/equipment_design_sub_category';

        $prdcat = $this->session->userdata('system_product_category');

        $join = array('JOIN "EQUIPMENT_DESIGN_CATEGORY" ON ("EQUIPMENT_DESIGN_CATEGORY".cd_equipment_design_category =  "EQUIPMENT_DESIGN_SUB_CATEGORY".cd_equipment_design_category) ',
                        'JOIN "EQUIPMENT_DESIGN_TYPE" ON ("EQUIPMENT_DESIGN_TYPE".cd_equipment_design_type =  "EQUIPMENT_DESIGN_CATEGORY".cd_equipment_design_type AND cd_system_product_category = '.$prdcat.' ) ');
        

        $this->fieldsforGrid = array(
            ' "EQUIPMENT_DESIGN_SUB_CATEGORY".cd_equipment_design_sub_category',
            ' "EQUIPMENT_DESIGN_SUB_CATEGORY".ds_equipment_design_sub_category',
            ' "EQUIPMENT_DESIGN_SUB_CATEGORY".cd_equipment_design_category',
            '"EQUIPMENT_DESIGN_CATEGORY".ds_equipment_design_category',
            ' "EQUIPMENT_DESIGN_SUB_CATEGORY".ds_name_code',
            ' "EQUIPMENT_DESIGN_SUB_CATEGORY".dt_deactivated',
            ' "EQUIPMENT_DESIGN_SUB_CATEGORY".dt_record');
        $this->fieldsUpd = array("cd_equipment_design_sub_category", "ds_equipment_design_sub_category", "cd_equipment_design_category", "ds_name_code", "dt_deactivated", "dt_record",);

        

        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "stylecond" => "(CASE WHEN \"EQUIPMENT_DESIGN_SUB_CATEGORY\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true,
            'join' => $join
        );

        
        $this->fieldsforPL = array(
            ' "EQUIPMENT_DESIGN_SUB_CATEGORY".cd_equipment_design_sub_category',
            ' "EQUIPMENT_DESIGN_SUB_CATEGORY".ds_equipment_design_sub_category',
            ' "EQUIPMENT_DESIGN_SUB_CATEGORY".cd_equipment_design_category',
            ' ( "EQUIPMENT_DESIGN_SUB_CATEGORY".ds_name_code ) as ds_name_code_sub',
             '"EQUIPMENT_DESIGN_CATEGORY".ds_equipment_design_category',
           ' ( "EQUIPMENT_DESIGN_CATEGORY".ds_name_code ) as ds_name_code_cat',
            
             '"EQUIPMENT_DESIGN_TYPE".ds_equipment_design_type',
           ' ( "EQUIPMENT_DESIGN_TYPE".ds_name_code ) as ds_name_code_type',
             '"EQUIPMENT_DESIGN_TYPE".fl_auto_add_serial',
            
            ' "EQUIPMENT_DESIGN_SUB_CATEGORY".dt_deactivated',
            ' "EQUIPMENT_DESIGN_SUB_CATEGORY".dt_record');

        
        $this->retrOptionsPL = array("fieldrecid" => $this->pk_field,
            //"stylecond" => "(CASE WHEN \"EQUIPMENT_DESIGN_SUB_CATEGORY\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforPL),
            "json" => true,
            "join" => $join
        );

        

        parent::__construct();
    }

}
