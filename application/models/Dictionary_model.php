<?php

include_once APPPATH.'models/modelBasicExtend.php';

class dictionary_model extends modelBasicExtend{

    
    function __construct()
    {
        
        $this->table = "SYSTEM_DICTIONARY_VIEW";
        
        $this->pk_field = "cd_system_dictionary_main";
        $this->ds_field = "ds_system_dictionary_main";
        
        $this->sequence_obj = '"SYSTEM_DICTIONARY_cd_system_dictionary_seq"';
        
         $this->fieldsforGrid = array($this->pk_field,
                                     $this->ds_field,
                                     'ds_translated',
                                     'cd_system_languages',
                                     '( SELECT ds_system_language FROM ' . $this->db->escape_identifiers("SYSTEM_LANGUAGES") . ' s WHERE s.cd_system_languages = ' . $this->db->escape_identifiers("SYSTEM_DICTIONARY_VIEW") . '.cd_system_languages) as ds_system_languages');
        
        
        $this->retrOptions = array ("fieldrecid" => $this->pk_field,
                        'fields' => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
                        'json' => true
                       ); 
        
         parent::__construct();

         
    }
    
   public function updateGridData($array) {
            
      
      
      return $this->cdbhelper->updateGridData($this->table, $this->pk_field, $array, $this->fieldsExcludeUpd, array ('pk2' => 'cd_system_languages'));
   }

    
    
}



?>