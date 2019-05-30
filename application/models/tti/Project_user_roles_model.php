<?php

include_once APPPATH . "models/modelBasicExtend.php";

class project_user_roles_model extends modelBasicExtend {

    function __construct() {

        $this->table = "PROJECT_USER_ROLES";

        $this->pk_field = "cd_project_user_roles";
        $this->ds_field = "ds_human_resource";
        $this->prodCatUnique = 'N';
        
        $this->orderByDefault = "ORDER BY fl_active DESC, ds_roles ASC";
        
        $this->sequence_obj = '"PROJECT_USER_ROLES_cd_project_user_roles_seq"';

        $this->controller = 'tti/project_user_roles';


        $this->fieldsforGrid = array(
            ' "PROJECT_USER_ROLES".cd_project_user_roles',
            ' "PROJECT_USER_ROLES".cd_human_resource',
            '( "HUMAN_RESOURCE".ds_human_resource_full ) as ds_human_resource',
            '"HUMAN_RESOURCE".ds_e_mail',
            ' "PROJECT_USER_ROLES".cd_roles',
            '( select ds_roles FROM "ROLES" WHERE cd_roles =  "PROJECT_USER_ROLES".cd_roles) as ds_roles',
            ' "PROJECT_USER_ROLES".fl_active',
            ' "PROJECT_USER_ROLES".cd_notification_type',
            ' "PROJECT_USER_ROLES".cd_project_model',
            '( select ds_notification_type FROM "NOTIFICATION_TYPE" WHERE cd_notification_type =  "PROJECT_USER_ROLES".cd_notification_type) as ds_notification_type');
        
        $join = array(' JOIN "HUMAN_RESOURCE" ON ( "HUMAN_RESOURCE".cd_human_resource = "PROJECT_USER_ROLES".cd_human_resource )');
        
        $this->fieldsUpd = array("cd_project_user_roles", "cd_project_model", "cd_human_resource", "cd_roles", "fl_active", "cd_notification_type");

        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "stylecond" => "(CASE WHEN \"PROJECT_USER_ROLES\".fl_active = 'N' THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true,
            'join' => $join 
        );


        parent::__construct();
    }

}
