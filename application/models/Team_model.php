<?php
include_once APPPATH."models/modelBasicExtend.php";

class team_model extends modelBasicExtend{
    

    
    function __construct()
    {
    
     $this->table = "TEAM";

     $this->pk_field = "cd_team";
     $this->ds_field = "ds_team";
     $this->prodCatUnique = 'N';

     $this->sequence_obj = '"TEAM_cd_team_seq"';
    
     $this->controller = 'team';


     $this->fieldsforGrid = array(


' "TEAM".cd_team', 
' "TEAM".ds_team', 
' "TEAM".cd_department', 
 '( select ds_department FROM "DEPARTMENT" WHERE cd_department =  "TEAM".cd_department) as ds_department', 
' "TEAM".dt_deactivated', 
' "TEAM".dt_record' );
      $this->fieldsUpd = array ( "cd_team", "ds_team", "cd_department", "dt_deactivated", "dt_record",  ); 
 
        
                $this->retrOptions = array ("fieldrecid" => $this->pk_field,
                        "stylecond"  => "(CASE WHEN \"TEAM\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
                        "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
                        "json" => true
                       ); 
                       

          parent::__construct();
    

    }
    }