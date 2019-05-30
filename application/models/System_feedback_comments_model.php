<?php

include_once APPPATH . "models/modelBasicExtend.php";

class system_feedback_comments_model extends modelBasicExtend {

    function __construct() {

        $this->table = "SYSTEM_FEEDBACK_COMMENTS";

        $this->pk_field = "cd_system_feedback_comments";
        $this->ds_field = "ds_system_feedback_comments";
        $this->prodCatUnique = 'N';
        $this->hasDeactivate = false;

        $this->sequence_obj = '"SYSTEM_FEEDBACK_COMMENTS_cd_system_feedback_comments_seq"';

        $this->controller = 'system_feedback_comments';
        $this->orderByDefault = ' ORDER BY "SYSTEM_FEEDBACK_COMMENTS".dt_record DESC ';


        $this->fieldsforGrid = array(
            ' "SYSTEM_FEEDBACK_COMMENTS".cd_system_feedback_comments',
            ' "SYSTEM_FEEDBACK_COMMENTS".ds_system_feedback_comments',
            ' "SYSTEM_FEEDBACK_COMMENTS".cd_system_feedback_comments_type',
            '( select ds_system_feedback_comments_type FROM "SYSTEM_FEEDBACK_COMMENTS_TYPE" WHERE cd_system_feedback_comments_type =  "SYSTEM_FEEDBACK_COMMENTS".cd_system_feedback_comments_type) as ds_system_feedback_comments_type',
            ' "SYSTEM_FEEDBACK_COMMENTS".ds_attachment_path',
            ' "SYSTEM_FEEDBACK_COMMENTS".cd_human_resource', 
            '( select ds_human_resource_full FROM "HUMAN_RESOURCE" WHERE cd_human_resource =  "SYSTEM_FEEDBACK_COMMENTS".cd_human_resource) as ds_human_resource', 
            ' "SYSTEM_FEEDBACK_COMMENTS".dt_record');
        $this->fieldsUpd = array("cd_system_feedback_comments", "ds_system_feedback_comments", "cd_system_feedback_comments_type", "ds_attachment_path", "cd_human_resource", "dt_record",);


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            //"stylecond" => "(CASE WHEN \"SYSTEM_FEEDBACK_COMMENTS\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );


        parent::__construct();
    }

}
