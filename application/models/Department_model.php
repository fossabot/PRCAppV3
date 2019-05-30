<?php
include_once APPPATH."models/modelBasicExtend.php";

class department_model extends modelBasicExtend{
    

    
    function __construct()
    {
    
     $this->table = "DEPARTMENT";

     $this->pk_field = "cd_department";
     $this->ds_field = "ds_department";
     $this->prodCatUnique = 'N';

     $this->sequence_obj = '"DEPARTMENTS_cd_department"';
    
     $this->controller = 'department';


     $this->fieldsforGrid = array(


' "DEPARTMENT".cd_department', 
' "DEPARTMENT".ds_department', 
' "DEPARTMENT".dt_deactivated', 
' "DEPARTMENT".dt_record', 
' "DEPARTMENT".ds_department_code' );
      $this->fieldsUpd = array ( "cd_department", "ds_department", "dt_deactivated", "dt_record", "ds_department_code",  ); 
 
        
                $this->retrOptions = array ("fieldrecid" => $this->pk_field,
                        "stylecond"  => "(CASE WHEN \"DEPARTMENT\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
                        "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
                        "json" => true
                       ); 
                       

          parent::__construct();
    

    }
    }