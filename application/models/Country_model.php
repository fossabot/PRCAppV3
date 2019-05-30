<?php
include_once APPPATH.'models/modelBasicExtend.php';

class country_model extends modelBasicExtend{
    

    
    function __construct()
    {
        
        $this->table = "COUNTRY";
        
        $this->pk_field = "cd_country";
        $this->ds_field = "ds_country";
        
        $this->sequence_obj = '"COUNTRY_cd_country_seq"';
        
        $this->fieldsforGrid = array('cd_country',
                                     'ds_country',
                                     'nr_country_number',
                                     'ds_iso_alpha2',
                                     'ds_iso_alpha3',
                                     'dt_deactivated' );
        
        $this->retrOptions = array ("fieldrecid" => $this->pk_field,
                        'stylecond'  => "(CASE WHEN dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
                        'fields' => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
                        'json' => true
                       ); 

      
      $this->controller = 'country';

      parent::__construct();
        
    }
    
    
    
}



?>