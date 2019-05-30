<?php

include_once APPPATH . "models/modelBasicExtend.php";

class roles_model extends modelBasicExtend {

    function __construct() {

        $this->table = "ROLES";

        $this->pk_field = "cd_roles";
        $this->ds_field = "ds_roles";
        $this->prodCatUnique = 'N';

        $this->sequence_obj = '"ROLES_cd_roles_seq"';

        $this->controller = 'tti/roles';


        $this->fieldsforGrid = array(
            ' "ROLES".cd_roles',
            ' "ROLES".ds_roles',
            ' "ROLES".dt_deactivated',
            ' "ROLES".dt_record',
            ' "ROLES".fl_can_comment_project',
            ' "ROLES".cd_notification_type_default',
            '( select ds_notification_type FROM "NOTIFICATION_TYPE" WHERE cd_notification_type =  "ROLES".cd_notification_type_default) as ds_notification_type_default');
        $this->fieldsUpd = array("cd_roles", "ds_roles", "dt_deactivated", "dt_record", "fl_can_comment_project", "cd_notification_type_default");


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "stylecond" => "(CASE WHEN \"ROLES\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );


        parent::__construct();
    }

}
