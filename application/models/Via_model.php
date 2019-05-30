<?php
include_once APPPATH.'models/modelBasicExtend.php';

class via_model extends modelBasicExtend{
    

    
    function __construct()
    {
        
        $this->table = "VIA";
        
        $this->pk_field = "cd_via";
        $this->ds_field = "ds_via";
        
        $this->sequence_obj = '"VIA_cd_via_seq"';
        
        $this->fieldsforGrid = array($this->pk_field,
                                     $this->ds_field,
                                     'ds_via_short',
                                     'dt_deactivated' );
        
        $this->retrOptions = array ("fieldrecid" => $this->pk_field,
                        'stylecond'  => "(CASE WHEN dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
                        'fields' => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
                        'json' => true
                       ); 
    
      $this->controller = 'via';

      parent::__construct();

        
    }
    
    
    
}



?>