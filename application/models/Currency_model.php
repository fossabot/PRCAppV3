<?php

include_once APPPATH.'models/modelBasicExtend.php';


class currency_model extends modelBasicExtend {
    
    
    function __construct()
    {
    
        
        $this->table = "CURRENCY";
        
        $this->pk_field = "cd_currency";
        $this->ds_field = "ds_currency";
        
        $this->sequence_obj = '"CURRENCY_cd_currency_seq"';
       
        
        $this->fieldsforGrid = array('cd_currency',
                                     'ds_currency',
                                     'ds_currency_symbol',
                                     'dt_deactivated' );
    
    
        
         $fields = $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid);
         
         
         
         $this->retrOptions = array ("fieldrecid" => $this->pk_field,
                                     'stylecond'  => "(CASE WHEN dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
                                     'fields' => $fields,
                                     'json' => true
                                    );   

      $this->controller = 'currency';

      parent::__construct();

    }
    
}



?>