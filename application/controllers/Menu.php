<?php

if (!defined('BASEPATH'))
   exit('No direct script access allowed');

class menu extends CI_Controller {

   var $arrayIns;

   function __construct() {
      parent::__construct();

      // primeira acoisa a fazer eh verificar se esta logado!

      
      
      if (!$this->logincontrol->isProperLogged()) {
         return;
      }
      
      $this->cdbhelper->loginUpdate($this->session->userdata('cd_session_log'));


      $this->load->model('menumodel', '', TRUE);

      $this->load->model('human_resource', '', TRUE);
      $this->load->model('job_model', '', TRUE);

     
   }

   public function index() {
      
   }

   public function editPermission($type, $code) {
      $f = $this->cfields;

      if ($type == "J") {
         $ret = $this->job_model->selectdb(" where cd_jobs = " . $code);
         $header = $ret[0]['ds_jobs'];
      } else {
         $ret = $this->human_resource->selectdb(" where cd_human_resource = " . $code);
         $header = $ret[0]['ds_human_resource_full'];
      }

      $this->w2gridgen->addBreakToolbar();
      $this->w2gridgen->setGridDivName("gridMenuFormDiv");
      $this->w2gridgen->setGridToolbarFunction("onGridToolbarPressedForm");
      $this->w2gridgen->setGridName("gridMenuForm");
      $this->w2gridgen->setGridVar("gridVarForm");
      $this->w2gridgen->setSingleBarControl(true);


      $this->w2gridgen->addCRUDToolbar(true, false, true, false, false);
      $this->w2gridgen->addBreakToolbar();
      $this->w2gridgen->addUserBtnToolbar("copy_merge", "Copy/Merge from Selected", "fa fa-paste", $caption = "");

      $colid = $this->w2gridgen->addColumn('recid', 'Code', '60px', $f->retTypeKey());
      $colid = $this->w2gridgen->addColumn('fl_checked', 'X', '30px', $f->retTypeCheckBox(), true);
      $colid = $this->w2gridgen->addColumn('ds_menu_key', 'Menu Option', '100%', $f->retTypeStringAny());

      $colid = $this->w2gridgen->setHeader($header, false);
      $colid = $this->w2gridgen->setToolbarSearch(true);
      $javascript = $this->w2gridgen->retGridVar();

      $labels = array(
         "confcopyopt" => "Please Select an option before copy/merge",
         "confcopy" => 'Confirm Copy/Merge from',
         "choose" => 'CHOOSE',
         "copyfrom" => 'Copy/Merge From',
         'user' => 'User',
         'job' => 'Role',
         'copyMsg' => 'Copy',
         'mergeMsg' => 'Merge',
         'copyQuestion'  => 'Confirm Copy from ',
         'mergeQuestion' => 'Confirm Merge from ');

      $labels = $this->cdbhelper->retTranslationDifKeys($labels);


      $send = array("javascript" => $javascript,
         "typeMenu" => $type,
         "codeRel" => $code);
      $send = $send + $labels;

      $this->load->view("menu_permission_form", $send);
   }

   public function retrievegrid($where = "") {
      $result = $this->menumodel->retMenusbyUser($where);
      $html = $this->gridg->mountGrid($this->arrayIns, $result, 900);
      return $html;
   }

   public function echoRetrievedGrid() {
      $where = $_POST['retFilter'];
      echo ( $this->retrievegrid($where) );
   }

   public function retrieveGridJson($type, $code) {
      echo ($this->menumodel->retRetrieveJson($type, $code));
   }

   public function updateDataJson($type, $code) {

      $upd_array = json_decode($_POST['upd']);
      echo ($this->menumodel->updateGridData($type, $code, $upd_array));
   }

   public function copyMergeMenu($type, $codefrom, $codeto, $copymerge) {
      $msg = $this->menumodel->copyMergeMenu($type, $codefrom, $codeto, $copymerge);
      echo($msg);
   }

}

?>