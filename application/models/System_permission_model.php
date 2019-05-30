<?php
include_once APPPATH.'models/modelBasicExtend.php';

class system_permission_model extends modelBasicExtend{
    

    
    function __construct()
    {
        
        $this->table = "SYSTEM_PERMISSION";
        
        $this->pk_field = "cd_system_permission";
        $this->ds_field = "ds_system_permission";
        
        $this->sequence_obj = '"COUNTRY_cd_country_seq"';
        
        $this->fieldsforGrid = array('cd_system_permission',
                                     'ds_system_permission' );
        
        $this->retrOptions = array ("fieldrecid" => $this->pk_field,
                        'stylecond'  => "",
                        'fields' => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
                        'json' => true
                       ); 
    
      $this->hasDeactivate = false;  
        
      parent::__construct();

        
    }
    
    
    
}



?>