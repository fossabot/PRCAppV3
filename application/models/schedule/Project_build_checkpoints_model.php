<?php

include_once APPPATH . "models/modelBasicExtend.php";

class project_build_checkpoints_model extends modelBasicExtend {

    function __construct() {

        $this->table = "PROJECT_BUILD_CHECKPOINTS";

        $this->pk_field = "cd_project_build_checkpoints";
        $this->ds_field = "ds_project_build_checkpoints";
        $this->prodCatUnique = 'N';

        $this->sequence_obj = '"PROJECT_BUILD_CHECKPOINTS_cd_project_build_checkpoints_seq"';

        $this->controller = 'schedule/project_build_checkpoints';


        $this->fieldsforGrid = array(
            ' "PROJECT_BUILD_CHECKPOINTS".cd_project_build_checkpoints',
            ' "PROJECT_BUILD_CHECKPOINTS".ds_project_build_checkpoints',
            ' "PROJECT_BUILD_CHECKPOINTS".dt_deactivated',
            ' "PROJECT_BUILD_CHECKPOINTS".nr_order',
            ' "PROJECT_BUILD_CHECKPOINTS".ds_comment',
            ' "PROJECT_BUILD_CHECKPOINTS".dt_record');
        $this->fieldsUpd = array("cd_project_build_checkpoints", "ds_project_build_checkpoints", "dt_deactivated", "nr_order", "ds_comment", "dt_record",);


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "stylecond" => "(CASE WHEN \"PROJECT_BUILD_CHECKPOINTS\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );


        parent::__construct();
    }

}
