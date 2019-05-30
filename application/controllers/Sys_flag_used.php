<?php

if (!defined('BASEPATH'))
   exit('No direct script access allowed');
include_once APPPATH . 'controllers/controllerBasicExtend.php';

class sys_flag_used extends controllerBasicExtend {

   var $arrayIns;
   var $fields;

   function __construct() {
      parent::__construct();

      $this->load->model('currency_model', 'mainmodel', TRUE);
   }

   public function index() {

      //print_r($arrayIns);
   }


}

?>