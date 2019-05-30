<?php

include_once APPPATH . "models/modelBasicExtend.php";

class equipment_design_category_model extends modelBasicExtend {

    function __construct() {

        $this->table = "EQUIPMENT_DESIGN_CATEGORY";

        $this->pk_field = "cd_equipment_design_category";
        $this->ds_field = "ds_equipment_design_category";
        $this->prodCatUnique = 'N';


        $this->sequence_obj = 'rfq."EQUIPMENT_DESIGN_CATEGORY_cd_equipment_design_category_seq"';

        $this->controller = 'rfq/equipment_design_category';

        $prdcat = $this->session->userdata('system_product_category');

        $this->basicWhereForPL = ' AND EXISTS ( SELECT 1 FROM "EQUIPMENT_DESIGN_TYPE" x WHERE x.cd_equipment_design_type = "EQUIPMENT_DESIGN_CATEGORY".cd_equipment_design_type AND x.cd_system_product_category = ' . $prdcat . ' )';

        $join = array('JOIN "EQUIPMENT_DESIGN_TYPE" ON ("EQUIPMENT_DESIGN_TYPE".cd_equipment_design_type =  "EQUIPMENT_DESIGN_CATEGORY".cd_equipment_design_type AND cd_system_product_category = ' . $prdcat . ' ) ');

        $this->fieldsforGrid = array(
            ' "EQUIPMENT_DESIGN_CATEGORY".cd_equipment_design_category',
            ' "EQUIPMENT_DESIGN_CATEGORY".ds_equipment_design_category',
            ' "EQUIPMENT_DESIGN_CATEGORY".cd_equipment_design_type',
            '"EQUIPMENT_DESIGN_TYPE".ds_equipment_design_type',
            ' "EQUIPMENT_DESIGN_CATEGORY".ds_name_code',
            ' "EQUIPMENT_DESIGN_CATEGORY".dt_deactivated',
            ' "EQUIPMENT_DESIGN_CATEGORY".dt_record');
        $this->fieldsUpd = array("cd_equipment_design_category", "ds_equipment_design_category", "cd_equipment_design_type", "ds_name_code", "dt_deactivated", "dt_record",);


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "stylecond" => "(CASE WHEN \"EQUIPMENT_DESIGN_CATEGORY\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true,
            'join' => $join
        );


        parent::__construct();
    }

}
