<?php

if (!defined('BASEPATH'))
   exit('No direct script access allowed');

include_once APPPATH . 'controllers/controllerBasicExtend.php';

class repgenerator extends controllerBasicExtend {

   var $arrayIns;
   var $fields;

   function __construct() {
      parent::__construct();
      //$this->load->model('country_model', 'mainmodel', TRUE);
   }

   public function index() {

      //parent::checkMenuPermission();
   }

   public function askReportAuth($id, $tpfile = -1) {

      $sql = urldecode($_POST['sql']);
      //die ($sql);
      
      if ($tpfile == -1) {
         $tpfile = $this->cdbhelper->getSettings('report_default_format');
      }
      
      $rptarray = $this->cdbhelper->getReport($id);
      $rptname = $rptarray['report'];
      $tablename = $rptarray['table'];

      if ($sql == '[]') {
         $sql = '';
      } else {
         $sql = $this->cdbhelper->mountFilterWhere(json_decode($sql, true));
      }

      switch ($tpfile) {
         case 1 :
            $type = 'pdf';
            break;
         case 2:
            $type = 'xls';
            break;
         case 3:
            $type = 'doc';
      }

      $name = str_replace('.rptdesign', '', $rptname) . '.' . $type;
      $ret = $this->cdbhelper->getReportAuth($id, $tpfile, $type, $sql);
      $md5 = $ret['md5'];

      $var = $this->cdbhelper->makeReportString($rptname, $type, $id, $md5);
      //$tst = file_get_contents($var);
      file_put_contents($ret['filename'], file_get_contents($var));

      $rep = $this->cdbhelper->getReport($id);
      
      $title =  $this->cdbhelper->retTranslation($rep['ds_system_reports_title']);
     
      
      
      $return = '{"id": "' . $id . '", "auth":"' . $md5 . '", "title":"' . $title . '"}';
      echo ($return);
   }
   
   
   public function getReport($id, $auth) {
      
      $ds_human_resource = $this->session->userdata('ds_human_resource');


      
      $sql = 'SELECT cd_system_reports_authorization, cd_system_reports, ds_authorization, 
                     ds_where, nr_file_type, dt_record, cd_system_languages, ds_sys_report_auth_filename, ds_sys_report_auth_extension
              FROM SYSTEM_REPORTS_AUTHORIZATION where cd_system_reports = '.$id.' '
         . ' and ds_authorization = '."'".$auth."' and ds_sys_report_auth_username = '".$ds_human_resource."'";
      

      $query = $this->getCdbhelper()->CIBasicQuery($sql);
      
      if ( $query->num_rows() == 0) {
         die ("Sorry, report Expired");
      } 
      
      $result = $query->result_array()[0];
      
      header("Content-Type: application/".$result['ds_sys_report_auth_extension']);
      header("Content-Disposition: inline; filename=\"".$result['ds_authorization'].'.'.$result['ds_sys_report_auth_extension']."\"");
      $var = file_get_contents($result['ds_sys_report_auth_filename']);

      
      header("Content-Length: " . strlen($var));
      
      echo ($var);      
      
   }
   

}

?>