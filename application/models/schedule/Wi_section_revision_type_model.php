<?php
include_once APPPATH."models/modelBasicExtend.php";

class wi_section_revision_type_model extends modelBasicExtend{
    

    
    function __construct()
    {
    
     $this->table = "WI_SECTION_REVISION_TYPE";

     $this->pk_field = "cd_wi_section_revision_type";
     $this->ds_field = "ds_wi_section_revision_type";
     $this->prodCatUnique = 'N';

     $this->sequence_obj = '"WI_SECTION_REVISION_TYPE_cd_wi_section_revision_type_seq"';
    
     $this->controller = 'schedule/wi_section_revision_type';


     $this->fieldsforGrid = array(


' "WI_SECTION_REVISION_TYPE".cd_wi_section_revision_type', 
' "WI_SECTION_REVISION_TYPE".ds_wi_section_revision_type', 
' "WI_SECTION_REVISION_TYPE".dt_deactivated', 
' "WI_SECTION_REVISION_TYPE".dt_record' );
      $this->fieldsUpd = array ( "cd_wi_section_revision_type", "ds_wi_section_revision_type", "dt_deactivated", "dt_record",  ); 
 
        
                $this->retrOptions = array ("fieldrecid" => $this->pk_field,
                        "stylecond"  => "(CASE WHEN \"WI_SECTION_REVISION_TYPE\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
                        "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
                        "json" => true
                       ); 
                       

          parent::__construct();
    

    }
    }