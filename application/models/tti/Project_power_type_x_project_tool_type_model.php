<?php
include_once APPPATH."models/modelBasicExtend.php";

class project_power_type_x_project_tool_type_model extends modelBasicExtend{
    

    
    function __construct()
    {
    
     $this->table = "PROJECT_POWER_TYPE_X_PROJECT_TOOL_TYPE";

     $this->pk_field = "cd_project_power_type_x_project_tool_type";
     $this->ds_field = "ds_project_power_type";
     $this->prodCatUnique = 'N';

     $this->sequence_obj = '"PROJECT_POWER_TYPE_X_PROJECT__cd_project_power_type_x_proje_seq"';
    
     $this->controller = 'tti/project_power_type_x_project_tool_type';


     $this->fieldsforGrid = array(


' "PROJECT_POWER_TYPE_X_PROJECT_TOOL_TYPE".cd_project_power_type_x_project_tool_type', 
' "PROJECT_POWER_TYPE_X_PROJECT_TOOL_TYPE".cd_project_power_type', 
 '( select ds_project_power_type FROM "PROJECT_POWER_TYPE" WHERE cd_project_power_type =  "PROJECT_POWER_TYPE_X_PROJECT_TOOL_TYPE".cd_project_power_type) as ds_project_power_type', 
' "PROJECT_POWER_TYPE_X_PROJECT_TOOL_TYPE".cd_project_tool_type', 
 '( select ds_project_tool_type FROM "PROJECT_TOOL_TYPE" WHERE cd_project_tool_type =  "PROJECT_POWER_TYPE_X_PROJECT_TOOL_TYPE".cd_project_tool_type) as ds_project_tool_type', 
' "PROJECT_POWER_TYPE_X_PROJECT_TOOL_TYPE".dt_deactivated' );
      $this->fieldsUpd = array ( "cd_project_power_type_x_project_tool_type", "cd_project_power_type", "cd_project_tool_type", "dt_deactivated",  ); 
 
        
                $this->retrOptions = array ("fieldrecid" => $this->pk_field,
                        "stylecond"  => "(CASE WHEN \"PROJECT_POWER_TYPE_X_PROJECT_TOOL_TYPE\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
                        "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
                        "json" => true
                       ); 
                       

          parent::__construct();
    

    }
    }