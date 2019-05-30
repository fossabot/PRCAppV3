<?php

if (!defined("BASEPATH"))
   exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class department_account_code_cost_center extends controllerBasicExtend {

   var $arrayIns;
   var $fields;

   function __construct() {
      parent::__construct();
      $this->load->model("rfq/department_account_code_cost_center_model", "mainmodel", TRUE);
   }

   public function index() {

      parent::checkMenuPermission();


      $grid = $this->w2gridgen;
      $f = $this->cfields;
      $fm = $this->cfiltermaker;

      if (1 == 2) {
         $f = new Cfields();
         $grid = new w2gridgen();
         $fm = new cfiltermaker();
      }

      $fm = $this->cfiltermaker;

$fm->addPickListFilter('Department Account Code', 'filter_1', 'rfq/department_account_code', '"DEPARTMENT_ACCOUNT_CODE_COST_CENTER".cd_department_account_code');
$fm->addPickListFilter('Department Cost Center', 'filter_2', 'rfq/department_cost_center', '"DEPARTMENT_ACCOUNT_CODE_COST_CENTER".cd_department_cost_center');



$this->setGridParser();
      $grid->setSingleBarControl(true);
      $grid->addCRUDToolbar();
      $grid->setToolbarSearch(true);
      $grid->setCRUDController("rfq/department_account_code_cost_center");

      $grid->addColumnKey();
      
$grid->addColumn('ds_department_account_code', 'Department Account Code', '150px', $f->retTypePickList(), array('model' => 'rfq/department_account_code_model', 'codeField' => 'cd_department_account_code' ) );
$grid->addColumn('ds_department_cost_center', 'Department Cost Center', '150px', $f->retTypePickList(), array('model' => 'rfq/department_cost_center_model', 'codeField' => 'cd_department_cost_center' ) );
 
            
       $filters = $fm->retFiltersWithGroup();
        $javascript = $grid->retGrid();


        $trans = array();
        $trans = $this->cdbhelper->retTranslationDifKeys($trans);     
        
        

      $send = array("javascript" => $javascript,
         "filters" => $filters,
         "filters_java" => $fm->retJavascript()) + $trans;


      $this->load->view("defaultView", $send);


                }
    }