<?php
include_once APPPATH.'models/modelBasicExtend.php';

class document_repository_file_model extends modelBasicExtend{

    
    function __construct()
    {
        
        $this->table = "DOCUMENT_FILE";
        
        $this->pk_field = "cd_document_file";
        $this->ds_field = "ds_document_file_hash";
        
        $this->sequence_obj = '"DOCUMENT_FILE_cd_document_file_seq"';
        
        $this->fieldsforGrid = array('cd_document_file',
                                     'ds_document_file_hash',
                                     'ds_document_file_path',
                                      'ds_document_file_thumbs_path',
            'dt_record',
            'ds_file_extension'
           );
        
        $this->retrOptions = array ("fieldrecid" => $this->pk_field,
                        //'stylecond'  => '',
                        'fields' => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
                        'json' => true
                       ); 

      $this->hasDeactivate = false;
    
      parent::__construct();

        
    }
    
    
    public function retByHash($hash) {
       $where = " WHERE ds_document_file_hash = '".$hash."'";
       return $this->retRetrieveArray($where);
    }
    
    public function insertdbDR($ds_document_file_hash, 
                             $ds_document_file_path, 
                             $ds_document_file_thumbs_path,
                             $ds_file_extension
                            ) {
       
      $nextcode = intval($this->cdbhelper->getNextCode($this->sequence_obj));

      $data = array(
          'cd_document_file' => $nextcode,
          'ds_document_file_hash' => $ds_document_file_hash,
          'ds_document_file_path' => $ds_document_file_path,
          'ds_document_file_thumbs_path' => $ds_document_file_thumbs_path,
          'ds_file_extension' => $ds_file_extension
      );
      
     $sql = $this->db->insert_string('DOCUMENT_FILE', $data);

     /*
      
      $sql = 'INSERT INTO ' .$this->db->escape_identifiers("DOCUMENT_FILE").' ( cd_document_file, 
                                    ds_document_file_hash, 
                                    ds_document_file_path, 
                                    ds_document_file_thumbs_path,
                                    ds_file_extension
                                    ) VALUES (%s, 
                                              \'%s\', 
                                              \'%s\',
                                              \'%s\',
                                              \'%s\'
                                    )';
         
      $sql = sprintf($sql, $nextcode, $ds_document_file_hash, $ds_document_file_path, $ds_document_file_thumbs_path, $ds_file_extension );   
      die ($sql);
 * */
 
     
      $this->getCdbhelper()->CIBasicQuery($sql);
      
      return $nextcode;
      
   }
    
    
}



?>