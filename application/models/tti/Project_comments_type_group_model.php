<?php
include_once APPPATH."models/modelBasicExtend.php";

class project_comments_type_group_model extends modelBasicExtend{
    

    
    function __construct()
    {
    
     $this->table = "PROJECT_COMMENTS_TYPE_GROUP";

     $this->pk_field = "cd_project_comments_type_group";
     $this->ds_field = "ds_project_comments_type_group";
     $this->prodCatUnique = 'N';

     $this->sequence_obj = '"PROJECT_COMMENTS_TYPE_GROUP_cd_project_comments_type_group_seq"';
    
     $this->controller = 'tti/project_comments_type_group';


     $this->fieldsforGrid = array(


' "PROJECT_COMMENTS_TYPE_GROUP".cd_project_comments_type_group', 
' "PROJECT_COMMENTS_TYPE_GROUP".ds_project_comments_type_group', 
' "PROJECT_COMMENTS_TYPE_GROUP".dt_deactivated', 
' "PROJECT_COMMENTS_TYPE_GROUP".dt_record' );
      $this->fieldsUpd = array ( "cd_project_comments_type_group", "ds_project_comments_type_group", "dt_deactivated", "dt_record",  ); 
 
        
                $this->retrOptions = array ("fieldrecid" => $this->pk_field,
                        "stylecond"  => "(CASE WHEN \"PROJECT_COMMENTS_TYPE_GROUP\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
                        "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
                        "json" => true
                       ); 
                       

          parent::__construct();
    

    }
    }