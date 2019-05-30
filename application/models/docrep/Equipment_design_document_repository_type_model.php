<?php
include_once APPPATH."models/modelBasicExtend.php";

class equipment_design_document_repository_type_model extends modelBasicExtend{
    

    
    function __construct()
    {
    
     $this->table = "EQUIPMENT_DESIGN_DOCUMENT_REPOSITORY_TYPE";

     $this->pk_field = "cd_equipment_design_document_repository_type";
     $this->ds_field = "ds_equipment_design_document_repository_type";
     $this->prodCatUnique = 'N';

     $this->sequence_obj = '"EQUIPMENT_DESIGN_DOCUMENT_REP_cd_equipment_design_document_seq1"';
    
     $this->controller = 'docrep/equipment_design_document_repository_type';


     $this->fieldsforGrid = array(


' "EQUIPMENT_DESIGN_DOCUMENT_REPOSITORY_TYPE".cd_equipment_design_document_repository_type', 
' "EQUIPMENT_DESIGN_DOCUMENT_REPOSITORY_TYPE".ds_equipment_design_document_repository_type', 
' "EQUIPMENT_DESIGN_DOCUMENT_REPOSITORY_TYPE".fl_default', 
' "EQUIPMENT_DESIGN_DOCUMENT_REPOSITORY_TYPE".fl_send_to_purchase', 
' "EQUIPMENT_DESIGN_DOCUMENT_REPOSITORY_TYPE".dt_deactivated', 
' "EQUIPMENT_DESIGN_DOCUMENT_REPOSITORY_TYPE".dt_record' );
      $this->fieldsUpd = array ( "cd_equipment_design_document_repository_type", "ds_equipment_design_document_repository_type", "fl_default", "fl_send_to_purchase", "dt_deactivated", "dt_record",  ); 
 
        
                $this->retrOptions = array ("fieldrecid" => $this->pk_field,
                        "stylecond"  => "(CASE WHEN \"EQUIPMENT_DESIGN_DOCUMENT_REPOSITORY_TYPE\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
                        "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
                        "json" => true
                       ); 
                       

          parent::__construct();
    

    }
    }