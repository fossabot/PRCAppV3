<?php
include_once APPPATH."models/modelBasicExtend.php";

class test_type_model extends modelBasicExtend{
    

    
    function __construct()
    {
    
     $this->table = "TEST_TYPE";

     $this->pk_field = "cd_test_type";
     $this->ds_field = "ds_test_type";
     $this->prodCatUnique = 'N';

     $this->sequence_obj = '"TEST_TYPE_cd_test_type_seq"';
    
     $this->controller = 'tr/test_type';


     $this->fieldsforGrid = array(


' "TEST_TYPE".cd_test_type', 
' "TEST_TYPE".ds_test_type', 
' "TEST_TYPE".dt_deactivated', 
' "TEST_TYPE".dt_record' );
      $this->fieldsUpd = array ( "cd_test_type", "ds_test_type", "dt_deactivated", "dt_record",  ); 
 
        
                $this->retrOptions = array ("fieldrecid" => $this->pk_field,
                        "stylecond"  => "(CASE WHEN \"TEST_TYPE\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
                        "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
                        "json" => true
                       ); 
                       

          parent::__construct();
    

    }
    }