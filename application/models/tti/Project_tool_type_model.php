<?php

include_once APPPATH . "models/modelBasicExtend.php";

class project_tool_type_model extends modelBasicExtend {

    function __construct() {

        $this->table = "PROJECT_TOOL_TYPE";

        $this->pk_field = "cd_project_tool_type";
        $this->ds_field = "ds_project_tool_type";
        $this->prodCatUnique = 'N';

        $this->sequence_obj = '"PROJECT_TOOL_TYPE_cd_project_tool_type_seq"';

        $this->controller = 'tti/project_tool_type';


        $this->fieldsforGrid = array(
            ' "PROJECT_TOOL_TYPE".cd_project_tool_type',
            ' "PROJECT_TOOL_TYPE".ds_project_tool_type',
            ' "PROJECT_TOOL_TYPE".dt_deactivated',
            ' "PROJECT_TOOL_TYPE".dt_record');
        $this->fieldsUpd = array("cd_project_tool_type", "ds_project_tool_type", "dt_deactivated", "dt_record",);


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "stylecond" => "(CASE WHEN \"PROJECT_TOOL_TYPE\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );


        parent::__construct();
    }

    
    public function retGridJsonPowerType($cd_project_tool_type, $mode = 'B', $fieldsForSelection = false) {
        return $this->retGridJsonWithRelation($cd_project_tool_type, 'PROJECT_POWER_TYPE_X_PROJECT_TOOL_TYPE', 'cd_project_power_type', $mode, $fieldsForSelection);
    }

    // funcao que recebe o id do type component e atacha os product groups nele
    public function updSBSRelPowerType($id, $add, $remove) {
        $msg = $this->updRelationSBS($id, 'PROJECT_POWER_TYPE_X_PROJECT_TOOL_TYPE', "cd_project_power_type", $add, $remove);
        echo $msg;
    }   
    
}
