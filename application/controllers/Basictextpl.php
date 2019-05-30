<?php

if (!defined('BASEPATH'))
   exit('No direct script access allowed');

class basictextpl extends CI_Controller {

   var $arrayIns;

   function __construct() {
      parent::__construct();

      // primeira acoisa a fazer eh verificar se esta logado!

      if (!$this->logincontrol->isProperLogged()) {
         return;
      }
   }

   public function makeTextPLModal() {
      // opcao para ter ateh 2 niveis de modelo
      // se veio nuemero no model2, e nao tem nada no id, significa que ele eh o id
      //echo (is_nan ($model2) ? 'YES' : 'NO');
      //echo ($id);
       
       
      $text = $_POST['text'];
      $title = $_POST['title'];
      $uppercase = $_POST['uppercase'];
      
      $readonly = $_POST['readonly'];

      $this->load->view('basictextpl_view', array('text' => $text, 'title' => $title, 'uppercase' => $uppercase, 'readonly' => $readonly ));
   }

   public function index() {

      echo ('');
      //print_r($arrayIns);
   }

}

?>