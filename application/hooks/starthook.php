<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of starthook
 *
 * @author cgb
 */
class starthook extends CI_Controller{
    //put your code here
    
    public function __construct() {
        parent::__construct();
        $this->CI = & get_instance();
        $this->CI->load->database();
        
    }
    
       public function setInitialVars() {
       // CGB - Pega variavel de sessao no banco

       $CI = $this->CI;
       
       $cd_human_resource   = $CI->session->userdata('cd_human_resource');
       $ds_human_resource   = $CI->session->userdata('ds_human_resource');
       $cd_system_languages = $CI->session->userdata('cd_system_languages');
       $system_product_category = $CI->session->userdata('system_product_category');
       
       
       $this->setDbVars('cd_human_resource', $cd_human_resource);
       $this->setDbVars('ds_human_resource', $ds_human_resource);
       $this->setDbVars('cd_system_product_category', $system_product_category);
       
       //$this->setDbVars('cd_system_languages', $cd_system_languages);

       $CI->db->query("SET application_name = '".$ds_human_resource." - MBoard System';");

       $q = $CI->db->query('select * from public."SYSTEM_COMPANY"');
       $r = $q->result_array();
       
       $CI->db->query("SET SESSION TIME ZONE '".$r[0]['ds_timezone']."';");
       
       $CI->db->companyName = $r[0]['ds_name'];
       $CI->db->companyAddress = $r[0]['ds_address'];
       $CI->db->companyMaxConnection = $r[0]['nr_max_connections'];
       $CI->db->cd_system_product_category = $system_product_category;
       $CI->db->cd_human_resource = $cd_human_resource;
       $CI->db->ds_human_resource = $ds_human_resource;
       
       $CI->settings_model->sendSettingsToDb();



   }
    
   
    public function setDbVars($key, $data) {
        // CGB - Seta variavel de sessao no banco
        $this->CI->db->query("select set_var('" . $key . "','" . $data . "');");
    }

    public function getDbVars($key) {
        // CGB - Pega variavel de sessao no banco
        $sql = "select public.get_var('" . $key . "') as key";
        $q = $this->CI->db->query($sql);

        $array = $q->result_array();
        $ret = $array[0]['key'];

        return $ret;
    }
   
}
