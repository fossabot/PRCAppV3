<?php

include_once APPPATH . "models/modelBasicExtend.php";

class project_power_type_model extends modelBasicExtend {

    function __construct() {

        $this->table = "PROJECT_POWER_TYPE";

        $this->pk_field = "cd_project_power_type";
        $this->ds_field = "ds_project_power_type";
        $this->prodCatUnique = 'N';

        $this->sequence_obj = '"PROJECT_POWER_TYPE_cd_project_power_type_seq"';

        $this->controller = 'tti/project_power_type';


        $this->fieldsforGrid = array(
            ' "PROJECT_POWER_TYPE".cd_project_power_type',
            ' "PROJECT_POWER_TYPE".ds_project_power_type',
            ' "PROJECT_POWER_TYPE".dt_deactivated',
            ' "PROJECT_POWER_TYPE".dt_record');
        $this->fieldsUpd = array("cd_project_power_type", "ds_project_power_type", "dt_deactivated", "dt_record",);


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "stylecond" => "(CASE WHEN \"PROJECT_POWER_TYPE\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );


        parent::__construct();
    }
    
    

    public function retGridJsonProduct($cd_project_product, $mode = 'B', $fieldsForSelection = false) {
        return $this->retGridJsonWithRelation($cd_project_product, 'PROJECT_PRODUCT_X_PROJECT_POWER_TYPE', 'cd_project_product', $mode, $fieldsForSelection);
    }

    // funcao que recebe o id do type component e atacha os product groups nele
    public function updSBSRelProduct($id, $add, $remove) {
        $msg = $this->updRelationSBS($id, 'PROJECT_PRODUCT_X_PROJECT_POWER_TYPE', "cd_project_product", $add, $remove);
        echo $msg;
    }

}
