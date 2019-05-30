<?php

if (!defined("BASEPATH"))
   exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class project_build_schedule_tests_purchase_items extends controllerBasicExtend {

   var $arrayIns;
   var $fields;

   function __construct() {
      parent::__construct();
      $this->load->model("schedule/project_build_schedule_tests_purchase_items_model", "mainmodel", TRUE);
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

$fm->addPickListFilter('Equipment Design', 'filter_1', 'rfq/equipment_design', '"PROJECT_BUILD_SCHEDULE_TESTS_PURCHASE_ITEMS".cd_equipment_design');
$fm->addPickListFilter('Project Build Schedule Tests', 'filter_7', 'schedule/project_build_schedule_tests', '"PROJECT_BUILD_SCHEDULE_TESTS_PURCHASE_ITEMS".cd_project_build_schedule_tests');
$fm->addPickListFilter('Tr Wi', 'filter_8', 'tr/tr_wi_data', '"PROJECT_BUILD_SCHEDULE_TESTS_PURCHASE_ITEMS".cd_tr_wi');
$fm->addPickListFilter('Human Resource Record', 'filter_10', 'human_resource', '"PROJECT_BUILD_SCHEDULE_TESTS_PURCHASE_ITEMS".cd_human_resource_record');



$this->setGridParser();
      $grid->setSingleBarControl(true);
      $grid->addCRUDToolbar();
      $grid->setToolbarSearch(true);
      $grid->setCRUDController("schedule/project_build_schedule_tests_purchase_items");

      $grid->addColumnKey();
      
$grid->addColumn('ds_equipment_design', 'Equipment Design', '150px', $f->retTypePickList(), array('model' => 'rfq/equipment_design_model', 'codeField' => 'cd_equipment_design' ) );
$grid->addColumn('nr_requested_quantity_to_buy', 'Requested Quantity To Buy', '150px', $f->retTypeNum(), array('precision' => '1', 'readonly' => false));
$grid->addColumn('dt_released_to_buy', 'Released To Buy', '80px', $f->retTypeDate(), true );
$grid->addColumn('nr_sample_quantity', 'Sample Quantity', '150px', $f->retTypeInteger(), true );
$grid->addColumn('nr_goal', 'Goal', '150px', $f->retTypeInteger(), true );
$grid->addColumn('dt_deadline', 'Deadline', '80px', $f->retTypeDate(), true );
$grid->addColumn('ds_project_build_schedule_tests', 'Project Build Schedule Tests', '150px', $f->retTypePickList(), array('model' => 'schedule/project_build_schedule_tests_model', 'codeField' => 'cd_project_build_schedule_tests' ) );
$grid->addColumn('ds_tr_wi_data', 'Tr Wi', '150px', $f->retTypePickList(), array('model' => 'tr/tr_wi_data_model', 'codeField' => 'cd_tr_wi' ) );
$grid->addColumn('nr_calculated_quantity', 'Calculated Quantity', '150px', $f->retTypeNum(), array('precision' => '1', 'readonly' => false));
$grid->addColumn('ds_human_resource_record', 'Human Resource Record', '150px', $f->retTypePickList(), array('model' => 'human_resource_model', 'codeField' => 'cd_human_resource_record' ) );
 
            
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