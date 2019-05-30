<?php
include_once APPPATH.'models/modelBasicExtend.php';

class document_repository_category_model extends modelBasicExtend{
    

    
    function __construct()
    {
        
        $this->table = "DOCUMENT_REPOSITORY_CATEGORY";
        
        $this->pk_field = "cd_document_repository_category";
        $this->ds_field = "ds_document_repository_category";
        
        $this->sequence_obj = '"DOCUMENT_REPOSITORY_CATEGORY_cd_document_repository_categor_seq"';
        
        $this->fieldsforGrid = array('cd_document_repository_category',
                                     'ds_document_repository_category',
                                     'cd_system_permission',
                                      '(SELECT ds_system_permission from ' . $this->db->escape_identifiers("SYSTEM_PERMISSION") . ' s where s.cd_system_permission = ' . $this->db->escape_identifiers("DOCUMENT_REPOSITORY_CATEGORY") . '.cd_system_permission) as ds_system_permission',
                                      'fl_specific_purpose',
                                     'dt_deactivated' );
        
        $this->retrOptions = array ("fieldrecid" => $this->pk_field,
                        'stylecond'  => "(CASE WHEN dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
                        'fields' => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
                        'json' => true
                       ); 
        $this->fieldsExcludeUpd = array('ds_system_permission');
        
    
      parent::__construct();

        
    }
    
    
    
}



?>