<?php

include_once APPPATH . "models/modelBasicExtend.php";

class system_feedback_comments_type_model extends modelBasicExtend {

    function __construct() {

        $this->table = "SYSTEM_FEEDBACK_COMMENTS_TYPE";

        $this->pk_field = "cd_system_feedback_comments_type";
        $this->ds_field = "ds_system_feedback_comments_type";
        $this->prodCatUnique = 'N';

        $this->sequence_obj = '"SYSTEM_FEEDBACK_COMMENTS_TYPE_cd_system_feedback_comments_t_seq"';

        $this->controller = 'system_feedback_comments_type';


        $this->fieldsforGrid = array(
            ' "SYSTEM_FEEDBACK_COMMENTS_TYPE".cd_system_feedback_comments_type',
            ' "SYSTEM_FEEDBACK_COMMENTS_TYPE".ds_system_feedback_comments_type',
            ' "SYSTEM_FEEDBACK_COMMENTS_TYPE".dt_record',
            ' "SYSTEM_FEEDBACK_COMMENTS_TYPE".dt_deactivated');
        $this->fieldsUpd = array("cd_system_feedback_comments_type", "ds_system_feedback_comments_type", "dt_record", "dt_deactivated",);


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "stylecond" => "(CASE WHEN \"SYSTEM_FEEDBACK_COMMENTS_TYPE\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );


        parent::__construct();
    }

}
