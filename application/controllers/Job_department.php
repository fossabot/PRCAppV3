<?php

if (!defined('BASEPATH'))
   exit('No direct script access allowed');
include_once APPPATH . 'controllers/controllerBasicExtend.php';

class job_department extends controllerBasicExtend {

   var $arrayIns;

   function __construct() {
      parent::__construct();


      $this->load->model('job_department_model', 'mainmodel', TRUE);

   }

   public function index() {

      parent::checkMenuPermission();

      $f = $this->cfields;

      $fm = $this->cfiltermaker;

      //$ret = $tb->returnHtml();

      $fm->addSimpleFilterUpper("Department", "ds_department");
      $fm->addPickListFilter('Header', 'filter_4', 'hrms/employee', 'DEPARTMENT.cd_employee_header');

      $fm->addFilterYesNo("Active", "dt_deactivated", '', "Y");
      $filters = $fm->retFiltersWithGroup();

      $this->w2gridgen->addCRUDToolbar();
      $this->w2gridgen->setCRUDController('job_department');

      $this->w2gridgen->addColumnKey();
      $this->w2gridgen->addColumn('ds_department', 'Description', '100%', $f->retTypeStringUpper(), array('limit' => 64));
      $this->w2gridgen->addColumn('ds_department_code', 'Internal Code', '120px', $f->retTypeStringUpper(), array('limit' => 16));

      $colid = $this->w2gridgen->addColumn('dt_deactivated', 'Deactivated', '100px', $f->retTypeDeactivated(), true);

      $javascript = $this->w2gridgen->retGrid();


      $send = array("javascript" => $javascript,
         "filters" => $filters,
         "filters_java" => $fm->retJavascript());


      $this->load->view("defaultView", $send);
   }
  

}

?>