<?php
include_once APPPATH."models/modelBasicExtend.php";

class project_voltage_model extends modelBasicExtend{
    

    
    function __construct()
    {
    
     $this->table = "PROJECT_VOLTAGE";

     $this->pk_field = "cd_project_voltage";
     $this->ds_field = "ds_project_voltage";
     $this->prodCatUnique = 'N';

     $this->sequence_obj = '"PROJECT_VOLTAGE_cd_project_voltage_seq"';
    
     $this->controller = 'tti/project_voltage';


     $this->fieldsforGrid = array(


' "PROJECT_VOLTAGE".cd_project_voltage', 
' "PROJECT_VOLTAGE".ds_project_voltage', 
' "PROJECT_VOLTAGE".dt_deactivated', 
' "PROJECT_VOLTAGE".dt_record' );
      $this->fieldsUpd = array ( "cd_project_voltage", "ds_project_voltage", "dt_deactivated", "dt_record",  ); 
 
        
                $this->retrOptions = array ("fieldrecid" => $this->pk_field,
                        "stylecond"  => "(CASE WHEN \"PROJECT_VOLTAGE\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
                        "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
                        "json" => true
                       ); 
                       

          parent::__construct();
    

    }
    }