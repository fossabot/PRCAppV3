<?php
include_once APPPATH."models/modelBasicExtend.php";

class project_document_repository_model extends modelBasicExtend{
    

    
    function __construct()
    {
    
     $this->table = "PROJECT_DOCUMENT_REPOSITORY";

     $this->pk_field = "cd_project_document_repository";
     $this->ds_field = "ds_project";
     $this->prodCatUnique = 'N';

     $this->sequence_obj = '"PROJECT_DOCUMENT_REPOSITORY_cd_project_document_repository_seq"';
    
     $this->controller = 'docrep/project_document_repository';


     $this->fieldsforGrid = array(


' "PROJECT_DOCUMENT_REPOSITORY".cd_project_document_repository', 
' "PROJECT_DOCUMENT_REPOSITORY".cd_project', 
 '( select ds_project FROM "PROJECT" WHERE cd_project =  "PROJECT_DOCUMENT_REPOSITORY".cd_project) as ds_project', 
' "PROJECT_DOCUMENT_REPOSITORY".cd_document_repository', 
 '( select ds_document_repository FROM "DOCUMENT_REPOSITORY" WHERE cd_document_repository =  "PROJECT_DOCUMENT_REPOSITORY".cd_document_repository) as ds_document_repository', 
' "PROJECT_DOCUMENT_REPOSITORY".dt_record' );
      $this->fieldsUpd = array ( "cd_project_document_repository", "cd_project", "cd_document_repository", "dt_record",  ); 
 
        
                $this->retrOptions = array ("fieldrecid" => $this->pk_field,
                        "stylecond"  => "(CASE WHEN \"PROJECT_DOCUMENT_REPOSITORY\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
                        "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
                        "json" => true
                       ); 
                       

          parent::__construct();
    

    }
    }