<?php
include_once APPPATH.'models/modelBasicExtend.php';

class division_model extends modelBasicExtend{
    

    
    function __construct()
    {
        
        $this->table = "DIVISION";
        
        $this->pk_field = "cd_division";
        $this->ds_field = "ds_division";
        
        $this->sequence_obj = '"DIVISION_cd_division_seq"';
        
        $this->fieldsforGrid = array($this->pk_field,
                                     $this->ds_field,
                                     'ds_division_short',
                                     'dt_deactivated' );
                
        $this->joinsForGrid = array(
            //' INNER JOIN "DIVISION_SHOE_SETUP" s ON ( s.cd_division = "DIVISION".cd_division ) ',
        );
        
        
        $this->retrOptions = array ("fieldrecid" => $this->pk_field,
                        'stylecond'  => "(CASE WHEN dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
                        'fields' => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
                        'json' => true
                       //'join' => $this->joinsForGrid

                       ); 
    
        
        
      $this->basicWhereForPL = $this->getCdbhelper()->getSqlForDivision('"DIVISION".cd_division') ;
      
        $this->fieldsForPLBaseDD = array( $this->pk_field, // first always PK
            $this->ds_field // second is always the description showing up. on the dropdown
                );
      
      parent::__construct();
      $this->controller = 'division_full';

        
    }
    
   public function retGridJsonDivBrand ($cd_division_brand, $mode = 'B',  $fieldsForSelection = false) {
      return $this->retGridJsonWithRelation($cd_division_brand, 'DIVISION_X_DIVISION_BRAND', 'cd_division_brand', $mode, $fieldsForSelection); 
    }       
    

   // funcao que recebe o id do type component e atacha os product groups nele
   public function updSBSRelDivBrand ($id, $add, $remove) {
      $msg = $this->updRelationSBS($id, 'DIVISION_X_DIVISION_BRAND', "cd_division_brand", $add, $remove);
      echo $msg; 
   }

   public function retGridJsonByHM ($cd_hmresource, $mode = 'B',  $fieldsForSelection = false) {
      return $this->retGridJsonWithRelation($cd_hmresource, 'HUMAN_RESOURCE_X_DIVISION', 'cd_human_resource', $mode, $fieldsForSelection); 
    }       

   // funcao que recebe o id do type component e atacha os product groups nele
   public function updSBSRelbyHM ($id, $add, $remove) {
      $msg = $this->updRelationSBS($id, 'HUMAN_RESOURCE_X_DIVISION', "cd_human_resource", $add, $remove);
      echo $msg; 
   }
   
   
    public function retGridJsonByJob ($cd_jobs, $mode = 'B',  $fieldsForSelection = false) {
      return $this->retGridJsonWithRelation($cd_jobs, 'JOBS_X_DIVISION', 'cd_jobs', $mode, $fieldsForSelection); 
    }       

   // funcao que recebe o id do type component e atacha os product groups nele
   public function updSBSRelbyJob ($id, $add, $remove) {
      $msg = $this->updRelationSBS($id, 'JOBS_X_DIVISION', "cd_jobs", $add, $remove);
      echo $msg; 
   }


   // funcao que recebe o id do type component e atacha os product groups nele
   public function updSBSRelbyNotGroup ($id, $add, $remove) {
      $msg = $this->updRelationSBS($id, 'NOTIFICATION_GROUP_X_DIVISION', "cd_notification_group", $add, $remove);
      echo $msg; 
   }

   
    public function retGridJsonbyNotGroup ($cd_notification_group, $mode = 'B',  $fieldsForSelection = false) {
      return $this->retGridJsonWithRelation($cd_notification_group, 'NOTIFICATION_GROUP_X_DIVISION', 'cd_notification_group', $mode, $fieldsForSelection); 
    }       

   
}



?>