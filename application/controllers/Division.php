<?php

if (!defined('BASEPATH'))
   exit('No direct script access allowed');

include_once APPPATH . 'controllers/controllerBasicExtend.php';

class division extends controllerBasicExtend {

   var $arrayIns;
   var $fields;

   function __construct() {
      parent::__construct();
      $this->load->model('division_model', 'mainmodel', TRUE);
   }

   public function index() {

   }
   
   
   
   public function retrieveGridJsonDiv($cd_division_brand, $mode = 'B', $json = true) {
      echo ($this->mainmodel->retGridJsonDivBrand($cd_division_brand, $mode, $json, true));
   }


      public function retGridJsonByHM ($cd_hmresource, $mode = 'B',  $fieldsForSelection = false) {
      echo $this->mainmodel->retGridJsonByHM($cd_hmresource, $mode, $fieldsForSelection); 
    }       

   // funcao que recebe o id do type component e atacha os product groups nele
   public function updSBSRelbyHM ($id, $add, $remove) {
      $msg = $this->mainmodel->updSBSRelbyHM($id, $add, $remove);
      echo $msg; 
   }

   
   public function retGridJsonByJob ($cd_jobs, $mode = 'B',  $fieldsForSelection = false) {
      echo $this->mainmodel->retGridJsonByJob($cd_jobs, $mode, $fieldsForSelection); 
    }       

   // funcao que recebe o id do type component e atacha os product groups nele
   public function updSBSRelbyJob ($id, $add, $remove) {
      $msg = $this->mainmodel->updSBSRelbyJob($id, $add, $remove);
      echo $msg; 
   }
   
   
   
}

?>