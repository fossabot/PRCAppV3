<?php

if (!defined("BASEPATH"))
   exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class project_product_x_project_power_type extends controllerBasicExtend {

   var $arrayIns;
   var $fields;

   function __construct() {
      parent::__construct();
      $this->load->model("tti/project_product_x_project_power_type_model", "mainmodel", TRUE);
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

$fm->addPickListFilter('Project Product', 'filter_1', 'tti/project_product', '"PROJECT_PRODUCT_X_PROJECT_POWER_TYPE".cd_project_product');
$fm->addPickListFilter('Project Power Type', 'filter_2', 'tti/project_power_type', '"PROJECT_PRODUCT_X_PROJECT_POWER_TYPE".cd_project_power_type');
$fm->addFilterYesNo("Active", "dt_deactivated", "", "Y");



$this->setGridParser();
      $grid->setSingleBarControl(true);
      $grid->addCRUDToolbar();
      $grid->setToolbarSearch(true);
      $grid->setCRUDController("tti/project_product_x_project_power_type");

      $grid->addColumnKey();
      
$grid->addColumn('ds_project_product', 'Project Product', '150px', $f->retTypePickList(), array('model' => 'tti/project_product_model', 'codeField' => 'cd_project_product' ) );
$grid->addColumn('ds_project_power_type', 'Project Power Type', '150px', $f->retTypePickList(), array('model' => 'tti/project_power_type_model', 'codeField' => 'cd_project_power_type' ) );
$grid->addColumnDeactivated(true);
 
            
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