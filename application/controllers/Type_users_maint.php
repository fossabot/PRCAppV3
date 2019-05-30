<?php

if (!defined('BASEPATH'))
   exit('No direct script access allowed');

include_once APPPATH . 'controllers/controllerBasicExtend.php';

class type_users_maint extends controllerBasicExtend {

   var $arrayIns;

   function __construct() {
      parent::__construct();

      $this->load->model('hm_type', 'mainmodel', TRUE);

   }

   public function index() {
      $f = $this->cfields;
      $fm = $this->cfiltermaker;
      parent::checkMenuPermission();

      //$ret = $tb->returnHtml();

      $fm->addSimpleFilterUpper("Type User", "ds_hr_type");
      $fm->addFilterYesNo("Active", "dt_deactivated", '', "Y");
      $filters = $fm->retFiltersWithGroup();

      $this->w2gridgen->addCRUDToolbar();
      $this->w2gridgen->setCRUDController(get_class());
      $this->w2gridgen->setDemandedColumns($this->mainmodel->getDemandedColumns());


      $this->w2gridgen->addColumnKey();
      $colid = $this->w2gridgen->addColumn('ds_hr_type', 'Description', '100%', $f->retTypeStringUpper(), array('limit' => 64));
      $this->w2gridgen->addColumnDeactivated(true);
      $javascript = $this->w2gridgen->retGrid();

      
      $send = array("javascript" => $javascript,
         "filters" => $filters,
         "filters_java" => $fm->retJavascript());


      $this->load->view("defaultView", $send);

   }

}
?>