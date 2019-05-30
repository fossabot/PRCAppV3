<?php

include_once APPPATH . "models/modelBasicExtend.php";

class project_build_schedule_comments_cc_model extends modelBasicExtend {

    function __construct() {

        $this->table = "PROJECT_BUILD_SCHEDULE_COMMENTS_CC";

        $this->pk_field = "cd_project_build_schedule_cc";
        $this->ds_field = "ds_project_build_schedule_comments";
        $this->prodCatUnique = 'N';

        $this->sequence_obj = '"PROJECT_BUILD_SCHEDULE_COMMENT_cd_project_build_schedule_cc_seq"';

        $this->controller = 'schedule/project_build_schedule_comments_cc';


        $this->fieldsforGrid = array(
            ' "PROJECT_BUILD_SCHEDULE_COMMENTS_CC".cd_project_build_schedule_cc',
            ' "PROJECT_BUILD_SCHEDULE_COMMENTS_CC".cd_project_build_schedule_comments',
            '( select ds_comments FROM "PROJECT_BUILD_SCHEDULE_COMMENTS" WHERE cd_project_build_schedule_comments =  "PROJECT_BUILD_SCHEDULE_COMMENTS_CC".cd_project_build_schedule_comments) as ds_project_build_schedule_comments',
            ' "PROJECT_BUILD_SCHEDULE_COMMENTS_CC".cd_human_resource',
            '( select ds_human_resource_full FROM "HUMAN_RESOURCE" WHERE cd_human_resource =  "PROJECT_BUILD_SCHEDULE_COMMENTS_CC".cd_human_resource) as ds_human_resource',
            '( select ds_e_mail FROM "HUMAN_RESOURCE" WHERE cd_human_resource =  "PROJECT_BUILD_SCHEDULE_COMMENTS_CC".cd_human_resource) as ds_e_mail',
            ' "PROJECT_BUILD_SCHEDULE_COMMENTS_CC".dt_record');
        $this->fieldsUpd = array("cd_project_build_schedule_cc", "cd_project_build_schedule_comments", "cd_human_resource", "dt_record",);


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            //"stylecond" => "(CASE WHEN \"PROJECT_BUILD_SCHEDULE_COMMENTS_CC\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );


        parent::__construct();
    }

}
