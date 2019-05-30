<?php
include_once APPPATH."models/modelBasicExtend.php";

class payment_term_model extends modelBasicExtend{
    

    
    function __construct()
    {
    
     $this->table = "PAYMENT_TERM";

     $this->pk_field = "cd_payment_term";
     $this->ds_field = "ds_payment_term";
     $this->prodCatUnique = 'N';

     $this->sequence_obj = '"PAYMENT_TERM_cd_payment_term_seq"';
    
     $this->controller = 'rfq/payment_term';


     $this->fieldsforGrid = array(


' "PAYMENT_TERM".cd_payment_term', 
' "PAYMENT_TERM".ds_payment_term', 
' "PAYMENT_TERM".dt_deactivated', 
' "PAYMENT_TERM".dt_record' );
      $this->fieldsUpd = array ( "cd_payment_term", "ds_payment_term", "dt_deactivated", "dt_record",  ); 
 
        
                $this->retrOptions = array ("fieldrecid" => $this->pk_field,
                        "stylecond"  => "(CASE WHEN \"PAYMENT_TERM\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
                        "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
                        "json" => true
                       ); 
                       

          parent::__construct();
    

    }
    }