<?php

include_once APPPATH.'models/modelBasicExtend.php';

class system_languages_model extends modelBasicExtend{

    
    function __construct()
    {
        $this->table = "SYSTEM_LANGUAGES";
        
        $this->pk_field = "cd_system_languages";
        $this->ds_field = "ds_system_language";
        
        //$this->sequence_obj = '"SYSTEM_DICTIONARY_cd_system_dictionary_seq"';
        
         $this->fieldsforGrid = array($this->pk_field,
                                     $this->ds_field,
                                     'dt_deactivated'
            );
        
        $this->retrOptions = array ("fieldrecid" => $this->pk_field,
                        'fields' => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
                        'json' => true
                       ); 
        
         parent::__construct();

         
    }
    
    
}



?>