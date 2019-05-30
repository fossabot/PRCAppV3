<?php
include_once APPPATH."models/modelBasicExtend.php";

class project_comments_type_group_human_resource_model extends modelBasicExtend{
    

    
    function __construct()
    {
    
     $this->table = "PROJECT_COMMENTS_TYPE_GROUP_HUMAN_RESOURCE";

     $this->pk_field = "cd_project_comments_type_group_human_resource";
     $this->ds_field = "ds_deactivated";
     $this->prodCatUnique = 'N';

     $this->sequence_obj = '"PROJECT_COMMENTS_TYPE_GROUP_H_cd_project_comments_type_grou_seq"';
    
     $this->controller = 'tti/project_comments_type_group_human_resource';


     $this->fieldsforGrid = array(


' "PROJECT_COMMENTS_TYPE_GROUP_HUMAN_RESOURCE".cd_project_comments_type_group_human_resource', 
' "PROJECT_COMMENTS_TYPE_GROUP_HUMAN_RESOURCE".dt_deactivated', 
' "PROJECT_COMMENTS_TYPE_GROUP_HUMAN_RESOURCE".dt_record', 
' "PROJECT_COMMENTS_TYPE_GROUP_HUMAN_RESOURCE".cd_project_comments_type_group', 
 '( select ds_project_comments_type_group FROM "PROJECT_COMMENTS_TYPE_GROUP" WHERE cd_project_comments_type_group =  "PROJECT_COMMENTS_TYPE_GROUP_HUMAN_RESOURCE".cd_project_comments_type_group) as ds_project_comments_type_group', 
' "PROJECT_COMMENTS_TYPE_GROUP_HUMAN_RESOURCE".cd_human_resource', 
 '( select ds_human_resource_full FROM "HUMAN_RESOURCE" WHERE cd_human_resource =  "PROJECT_COMMENTS_TYPE_GROUP_HUMAN_RESOURCE".cd_human_resource) as ds_human_resource' );
      $this->fieldsUpd = array ( "cd_project_comments_type_group_human_resource", "dt_deactivated", "dt_record", "cd_project_comments_type_group", "cd_human_resource",  ); 
 
        
                $this->retrOptions = array ("fieldrecid" => $this->pk_field,
                        "stylecond"  => "(CASE WHEN \"PROJECT_COMMENTS_TYPE_GROUP_HUMAN_RESOURCE\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
                        "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
                        "json" => true
                       ); 
                       

          parent::__construct();
    

    }
    }