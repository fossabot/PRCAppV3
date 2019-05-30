<?php
include_once APPPATH.'models/modelBasicExtend.php';

class trading_model extends modelBasicExtend{
    

    
    function __construct()
    {
        
        $this->table = "TRADING";
        
        $this->pk_field = "cd_trading";
        $this->ds_field = "ds_trading";
        
        $this->sequence_obj = '"TRADING_cd_trading_seq"';
        
        $this->fieldsforGrid = array($this->pk_field,
                                     $this->ds_field,
                                     'ds_trading_short',
                                     'ds_trading_on_docs',
                                     'ds_address',
                                     'ds_bank_information',
            'ds_tst',
                                     'dt_deactivated' );
        
        $this->retrOptions = array ("fieldrecid" => $this->pk_field,
                        'stylecond'  => "(CASE WHEN dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
                        'fields' => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
                        'json' => true
                       ); 
    
      $this->controller = 'trading';

        
      parent::__construct();

        
    }
    
    
    
}



?>