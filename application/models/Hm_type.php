<?php
include_once APPPATH.'models/modelBasicExtend.php';

class Hm_type extends modelBasicExtend{
    
    function __construct()
    {
        $this->table = "HR_TYPE";
        
        $this->pk_field = "cd_hr_type";
        $this->ds_field = "ds_hr_type";
        
        $this->sequence_obj = '"HR_TYPE_cd_hr_type"';
        $this->controller = 'type_users_maint';
        
        $this->fieldsforGrid = array($this->pk_field,
                                     $this->ds_field,
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