<?php

include_once APPPATH . "models/modelBasicExtend.php";

class project_product_model extends modelBasicExtend {

    function __construct() {

        $this->table = "PROJECT_PRODUCT";

        $this->pk_field = "cd_project_product";
        $this->ds_field = "ds_project_product";
        $this->prodCatUnique = 'N';

        $this->sequence_obj = '"PROJECT_PRODUCT_cd_project_product_seq"';

        $this->controller = 'tti/project_product';


        $this->fieldsforGrid = array(
            ' "PROJECT_PRODUCT".cd_project_product',
            ' "PROJECT_PRODUCT".ds_project_product',
            ' "PROJECT_PRODUCT".dt_deactivated',
            ' "PROJECT_PRODUCT".dt_record');

        $this->fieldsUpd = array("cd_project_product", "ds_project_product", "dt_deactivated", "dt_record",);


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "stylecond" => "(CASE WHEN \"PROJECT_PRODUCT\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );


        parent::__construct();
    }

    public function retGridJsonPowerType($cd_project_power_type, $mode = 'B', $fieldsForSelection = false) {
        return $this->retGridJsonWithRelation($cd_project_power_type, 'PROJECT_PRODUCT_X_PROJECT_POWER_TYPE', 'cd_project_power_type', $mode, $fieldsForSelection);
    }

    // funcao que recebe o id do type component e atacha os product groups nele
    public function updSBSRelPowerType($id, $add, $remove) {
        $msg = $this->updRelationSBS($id, 'PROJECT_PRODUCT_X_PROJECT_POWER_TYPE', "cd_project_power_type", $add, $remove);
        echo $msg;
    }

}
