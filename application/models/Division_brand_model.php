<?php

include_once APPPATH . 'models/modelBasicExtend.php';

class division_brand_model extends modelBasicExtend {

   function __construct() {

      $this->table = "DIVISION_BRAND";

      $this->pk_field = "cd_division_brand";
      $this->ds_field = "ds_division_brand";

      $this->sequence_obj = '"DIVISION_BRAND_cd_division_brand_seq"';

      $this->fieldsforGrid = array($this->pk_field,
         $this->ds_field,
         'dt_deactivated');

      $this->retrOptions = array("fieldrecid" => $this->pk_field,
         'stylecond' => "(CASE WHEN dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
         'fields' => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
         'json' => true
      );
      $this->controller = 'division_full';

      parent::__construct();
   }

   public function retGridJsonDivision($cd_division, $mode = 'B', $fieldsForSelection = false) {
      return $this->retGridJsonWithRelation($cd_division, 'DIVISION_X_DIVISION_BRAND', 'cd_division', $mode, $fieldsForSelection);
   }

   // funcao que recebe o id do type component e atacha os product groups nele
   public function updSBSRelDivision($id, $add, $remove) {
      $msg = $this->updRelationSBS($id, 'DIVISION_X_DIVISION_BRAND', "cd_division", $add, $remove);
      echo $msg;
   }

   
   
}

?>