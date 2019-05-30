<?php

if (!defined('BASEPATH'))
   exit('No direct script access allowed');

include_once APPPATH . 'controllers/controllerBasicExtend.php';

class division_brand extends controllerBasicExtend {

   var $arrayIns;
   var $fields;

   function __construct() {
      parent::__construct();
      $this->load->model('division_brand_model', 'mainmodel', TRUE);
   }

   public function index() {

   }
   
   public function retrieveGridJsonDivBrand($cd_division, $mode = 'B', $json = true) {
      echo ($this->mainmodel->retGridJsonDivision($cd_division, $mode, $json, true));
   }

   
public function retPlWherePar1($par1) {
        return " AND EXISTS ( SELECT 1 FROM " . $this->db->escape_identifiers('DIVISION_X_DIVISION_BRAND') . "  x WHERE x.cd_division_brand =  " . $this->db->escape_identifiers('DIVISION_BRAND') . ".cd_division_brand AND x.cd_division = $par1 AND x.dt_deactivated IS NULL ) ";
    }
   

}

?>