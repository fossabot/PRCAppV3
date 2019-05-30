<?php

if (!defined('BASEPATH'))
   exit('No direct script access allowed');

include_once APPPATH . 'controllers/controllerBasicExtend.php';

class system_languages extends controllerBasicExtend {

   var $arrayIns;
   var $fields;

   function __construct() {
      parent::__construct();
      $this->load->model('system_languages_model', 'mainmodel', TRUE);
   }

   public function index() {

      parent::checkMenuPermission();

   }

}

?>