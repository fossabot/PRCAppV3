<?php

include_once APPPATH . "models/modelBasicExtend.php";

class project_build_schedule_checkpoints_model extends modelBasicExtend {

    function __construct() {

        $this->table = "PROJECT_BUILD_SCHEDULE_CHECKPOINTS";

        $this->pk_field = "cd_project_build_schedule_checkpoints";
        $this->ds_field = "ds_project_build_schedule";
        $this->prodCatUnique = 'N';
        $this->hasDeactivate = false;
        $this->orderByDefault = 'ORDER BY nr_order';
        
        $this->sequence_obj = '"PROJECT_BUILD_SCHEDULE_CHECKP_cd_project_build_schedule_che_seq"';

        $this->controller = 'schedule/project_build_schedule_checkpoints';


        $this->fieldsforGrid = array(
            ' "PROJECT_BUILD_SCHEDULE_CHECKPOINTS".cd_project_build_schedule_checkpoints',
            ' "PROJECT_BUILD_SCHEDULE_CHECKPOINTS".cd_project_build_schedule',
            ' "PROJECT_BUILD_SCHEDULE_CHECKPOINTS".cd_project_build_checkpoints',
            '"PROJECT_BUILD_CHECKPOINTS".ds_project_build_checkpoints',
            ' "PROJECT_BUILD_SCHEDULE_CHECKPOINTS".dt_deadline',
            ' "PROJECT_BUILD_SCHEDULE_CHECKPOINTS".dt_done',
            ' "PROJECT_BUILD_SCHEDULE_CHECKPOINTS".ds_comment',
            ' "PROJECT_BUILD_CHECKPOINTS".nr_order'
            );
        $this->fieldsUpd = array("cd_project_build_schedule_checkpoints", "cd_project_build_schedule", "cd_project_build_checkpoints", "dt_deadline", "dt_done", "ds_comment",);

        $join = array('JOIN "PROJECT_BUILD_CHECKPOINTS" ON "PROJECT_BUILD_CHECKPOINTS".cd_project_build_checkpoints =  "PROJECT_BUILD_SCHEDULE_CHECKPOINTS".cd_project_build_checkpoints ');
        
        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            //"stylecond" => "(CASE WHEN \"PROJECT_BUILD_SCHEDULE_CHECKPOINTS\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true,
            'join' => $join
        );


        parent::__construct();
    }

}
