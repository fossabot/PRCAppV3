<?php

include_once APPPATH . "models/modelBasicExtend.php";

class project_comments_cc_model extends modelBasicExtend {

    function __construct() {

        $this->table = "PROJECT_COMMENTS_CC";

        $this->pk_field = "cd_project_comments_cc";
        $this->ds_field = "ds_project_comments";
        $this->prodCatUnique = 'N';

        $this->sequence_obj = '"PROJECT_COMMENTS_CC_cd_project_comments_cc_seq"';

        $this->controller = 'tti/project_comments_cc';


        $this->fieldsforGrid = array(
            ' "PROJECT_COMMENTS_CC".cd_project_comments_cc',
            ' "PROJECT_COMMENTS_CC".cd_project_comments',
            ' "PROJECT_COMMENTS_CC".cd_human_resource',
            '( select ds_human_resource_full FROM "HUMAN_RESOURCE" WHERE cd_human_resource =  "PROJECT_COMMENTS_CC".cd_human_resource) as ds_human_resource',
            '( select ds_e_mail FROM "HUMAN_RESOURCE" WHERE cd_human_resource =  "PROJECT_COMMENTS_CC".cd_human_resource) as ds_e_mail',
            ' "PROJECT_COMMENTS_CC".dt_record');
        $this->fieldsUpd = array("cd_project_comments_cc", "cd_project_comments", "cd_human_resource", "dt_record",);


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            //"stylecond" => "(CASE WHEN \"PROJECT_COMMENTS_CC\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );

        

        parent::__construct();
    }

}
