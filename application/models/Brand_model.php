<?php
include_once APPPATH."models/modelBasicExtend.php";

class brand_model extends modelBasicExtend{
    

    
    function __construct()
    {
    
     $this->table = "BRAND";

     $this->pk_field = "cd_brand";
     $this->ds_field = "ds_brand";
     $this->prodCatUnique = 'N';

     $this->sequence_obj = '"BRAND_cd_brand_seq"';
    
     $this->controller = 'brand';


     $this->fieldsforGrid = array(


' "BRAND".cd_brand', 
' "BRAND".ds_brand', 
' "BRAND".dt_deactivated', 
' "BRAND".dt_record' );
      $this->fieldsUpd = array ( "cd_brand", "ds_brand", "dt_deactivated", "dt_record",  ); 
 
        
                $this->retrOptions = array ("fieldrecid" => $this->pk_field,
                        "stylecond"  => "(CASE WHEN \"BRAND\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
                        "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
                        "json" => true
                       ); 
                       

          parent::__construct();
    

    }
    }