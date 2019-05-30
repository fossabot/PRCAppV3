<?php

include_once APPPATH.'models/modelBasicExtend.php';


class sys_flag_used_model extends modelBasicExtend {
    
    
    function __construct()
    {
            
        $this->table = "SYS_FLAG_USED_VIEW";
        
        $this->pk_field = "cd_sys_flag_used";
        $this->ds_field = "ds_sys_flag_used";
        
        $this->sequence_obj = '';
       
        
        $this->fieldsforGrid = array('cd_sys_flag_used',
                                     'ds_sys_flag_used' );
    
    
        $this->hasDeactivate = false;
        
         $fields = $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid);

         $this->retrOptions = array ("fieldrecid" => $this->pk_field,
                                     'fields' => $fields,
                                     'json' => true
                                    );   

      parent::__construct();

    }
    
}



?>