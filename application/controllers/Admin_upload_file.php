<?php

if (!defined('BASEPATH'))
   exit('No direct script access allowed');

include_once APPPATH . 'controllers/controllerBasicExtend.php';

class admin_upload_file extends controllerBasicExtend {

   var $arrayIns;
   var $fields;

   
   function __construct() {
      parent::__construct();
      //$this->load->model('country_model', 'mainmodel', TRUE);
   }

   public function index() {

      parent::checkMenuPermission();




     $this->load->view("admin_upload_file_view");
   }
   
   
   public function getFiles() {
      
      print_r($_FILES);
      
      print_r($_POST);
      
      echo ('{ "status" : "OK"}');
      
   }

}

?>