<?php
include_once APPPATH.'models/modelBasicExtend.php';

class system_label_system_model extends modelBasicExtend{
    

    
    function __construct()
    {
        
        $this->table = "SYSTEM_LABEL_TYPE";
        
        $this->pk_field = "cd_system_label_type";
        $this->ds_field = "ds_system_label_type";
        
        $this->sequence_obj = '"SYS_LABEL_TYPE_cd_sys_label_type_seq"';
        
        $this->fieldsforGrid = array($this->pk_field,
                                     $this->ds_field,
                                     'ds_system_identifier',
                                     'dt_deactivated' );
        
        $this->retrOptions = array ("fieldrecid" => $this->pk_field,
                        'stylecond'  => "(CASE WHEN dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
                        'fields' => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
                        'json' => true
                       ); 
    
      parent::__construct();

        
    }
    
    
    
}



?>