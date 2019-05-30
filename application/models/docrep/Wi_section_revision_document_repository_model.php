<?php
include_once APPPATH."models/modelBasicExtend.php";

class wi_section_revision_document_repository_model extends modelBasicExtend{
    

    
    function __construct()
    {
    
     $this->table = "WI_SECTION_REVISION_DOCUMENT_REPOSITORY";

     $this->pk_field = "cd_wi_section_revision_document_repository";
     $this->ds_field = "ds_wi_section_revision";
     $this->prodCatUnique = 'N';

     $this->sequence_obj = '"WI_SECTION_REVISION_DOCUMENT__cd_wi_section_revision_docum_seq1"';
    
     $this->controller = 'docrep/wi_section_revision_document_repository';


     $this->fieldsforGrid = array(


' "WI_SECTION_REVISION_DOCUMENT_REPOSITORY".cd_wi_section_revision_document_repository', 
' "WI_SECTION_REVISION_DOCUMENT_REPOSITORY".cd_wi_section_revision', 
 '( select ds_wi_section_revision FROM "WI_SECTION_REVISION" WHERE cd_wi_section_revision =  "WI_SECTION_REVISION_DOCUMENT_REPOSITORY".cd_wi_section_revision) as ds_wi_section_revision', 
' "WI_SECTION_REVISION_DOCUMENT_REPOSITORY".cd_document_repository', 
' "WI_SECTION_REVISION_DOCUMENT_REPOSITORY".dt_record', 
' "WI_SECTION_REVISION_DOCUMENT_REPOSITORY".cd_wi_section_revision_document_repository_type', 
 '( select ds_wi_section_revision_document_repository_type FROM "WI_SECTION_REVISION_DOCUMENT_REPOSITORY_TYPE" WHERE cd_wi_section_revision_document_repository_type =  "WI_SECTION_REVISION_DOCUMENT_REPOSITORY".cd_wi_section_revision_document_repository_type) as ds_wi_section_revision_document_repository_type' );
      $this->fieldsUpd = array ( "cd_wi_section_revision_document_repository", "cd_wi_section_revision", "cd_document_repository", "dt_record", "cd_wi_section_revision_document_repository_type",  ); 
 
        
                $this->retrOptions = array ("fieldrecid" => $this->pk_field,
                        //"stylecond"  => "(CASE WHEN \"WI_SECTION_REVISION_DOCUMENT_REPOSITORY\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
                        "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
                        "json" => true
                       ); 
                       

          parent::__construct();
    

    }
    }