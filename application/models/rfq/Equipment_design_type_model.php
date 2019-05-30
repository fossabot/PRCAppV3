<?php

include_once APPPATH . "models/modelBasicExtend.php";

class equipment_design_type_model extends modelBasicExtend {

    function __construct() {

        $this->table = "EQUIPMENT_DESIGN_TYPE";

        $this->pk_field = "cd_equipment_design_type";
        $this->ds_field = "ds_equipment_design_type";
        $this->prodCatUnique = 'Y';

        $this->sequence_obj = 'rfq."EQUIPMENT_DESIGN_TYPE_cd_equipment_design_type_seq"';

        $this->controller = 'rfq/equipment_design_type';


        $this->fieldsforGrid = array(
            ' "EQUIPMENT_DESIGN_TYPE".cd_equipment_design_type',
            ' "EQUIPMENT_DESIGN_TYPE".ds_equipment_design_type',
            ' "EQUIPMENT_DESIGN_TYPE".ds_name_code',
            ' "EQUIPMENT_DESIGN_TYPE".dt_deactivated',
            ' "EQUIPMENT_DESIGN_TYPE".ds_name_separator',
            ' "EQUIPMENT_DESIGN_TYPE".fl_auto_add_serial',            
            
            
            ' "EQUIPMENT_DESIGN_TYPE".dt_record');
        $this->fieldsUpd = array("cd_equipment_design_type", "ds_equipment_design_type", "ds_name_code", "dt_deactivated", "dt_record", "ds_name_separator", 'fl_auto_add_serial');


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "stylecond" => "(CASE WHEN \"EQUIPMENT_DESIGN_TYPE\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );


        parent::__construct();
    }

}
