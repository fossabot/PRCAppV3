<?php

if (!defined("BASEPATH"))
   exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class tr_test_request extends controllerBasicExtend {

   var $arrayIns;
   var $fields;

   function __construct() {
      parent::__construct();
      $this->load->model("tr/tr_test_request_model", "mainmodel", TRUE);
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

$fm->addSimpleFilterUpper('Tr Number', 'filter_1', '"TR_TEST_REQUEST".ds_tr_number');
$fm->addSimpleFilterUpper('Draft Number', 'filter_2', '"TR_TEST_REQUEST".ds_draft_number');
$fm->addSimpleFilterUpper('Tti Project Number Tr', 'filter_3', '"TR_TEST_REQUEST".ds_tti_project_number_tr');
$fm->addSimpleFilterUpper('Tti Project Model Number Tr', 'filter_4', '"TR_TEST_REQUEST".ds_tti_project_model_number_tr');
$fm->addSimpleFilterUpper('Met Project Number Tr', 'filter_5', '"TR_TEST_REQUEST".ds_met_project_number_tr');
$fm->addSimpleFilterUpper('Met Project Model Number Tr', 'filter_6', '"TR_TEST_REQUEST".ds_met_project_model_number_tr');
$fm->addSimpleFilterUpper('Sample Production', 'filter_7', '"TR_TEST_REQUEST".ds_sample_production');
$fm->addSimpleFilterUpper('Test Phase', 'filter_8', '"TR_TEST_REQUEST".ds_test_phase');
$fm->addSimpleFilterUpper('Sample Description', 'filter_13', '"TR_TEST_REQUEST".ds_sample_description');
$fm->addSimpleFilterUpper('Project Description Tr', 'filter_14', '"TR_TEST_REQUEST".ds_project_description_tr');
$fm->addSimpleFilterUpper('Project Model Description Tr', 'filter_15', '"TR_TEST_REQUEST".ds_project_model_description_tr');
$fm->addPickListFilter('Project Model', 'filter_16', 'tti/project_model', '"TR_TEST_REQUEST".cd_project_model');
$fm->addPickListFilter('Project Build', 'filter_17', 'schedule/project_build', '"TR_TEST_REQUEST".cd_project_build');



$this->setGridParser();
      $grid->setSingleBarControl(true);
      $grid->addCRUDToolbar();
      $grid->setToolbarSearch(true);
      $grid->setCRUDController("tr/tr_test_request");

      $grid->addColumnKey();
      
$grid->addColumn('ds_tr_number', 'Tr Number', '150px', $f->retTypeStringAny(), array('limit' => '') );
$grid->addColumn('ds_draft_number', 'Draft Number', '150px', $f->retTypeStringAny(), array('limit' => '') );
$grid->addColumn('ds_tti_project_number_tr', 'Tti Project Number Tr', '150px', $f->retTypeStringAny(), array('limit' => '') );
$grid->addColumn('ds_tti_project_model_number_tr', 'Tti Project Model Number Tr', '150px', $f->retTypeStringAny(), array('limit' => '') );
$grid->addColumn('ds_met_project_number_tr', 'Met Project Number Tr', '150px', $f->retTypeStringAny(), array('limit' => '') );
$grid->addColumn('ds_met_project_model_number_tr', 'Met Project Model Number Tr', '150px', $f->retTypeStringAny(), array('limit' => '') );
$grid->addColumn('ds_sample_production', 'Sample Production', '150px', $f->retTypeStringAny(), array('limit' => '') );
$grid->addColumn('ds_test_phase', 'Test Phase', '150px', $f->retTypeStringAny(), array('limit' => '') );
$grid->addColumn('dt_start_test', 'Start Test', '80px', $f->retTypeDate(), true );
$grid->addColumn('dt_lab_estimated_completion', 'Lab Estimated Completion', '80px', $f->retTypeDate(), true );
$grid->addColumn('dt_assigned_to_engineer', 'Assigned To Engineer', '80px', $f->retTypeDate(), true );
$grid->addColumn('dt_supervisor_approval', 'Supervisor Approval', '80px', $f->retTypeDate(), true );
$grid->addColumn('ds_sample_description', 'Sample Description', '150px', $f->retTypeStringAny(), array('limit' => '') );
$grid->addColumn('ds_project_description_tr', 'Project Description Tr', '150px', $f->retTypeStringAny(), array('limit' => '') );
$grid->addColumn('ds_project_model_description_tr', 'Project Model Description Tr', '150px', $f->retTypeStringAny(), array('limit' => '') );
$grid->addColumn('ds_project_model', 'Project Model', '150px', $f->retTypePickList(), array('model' => 'tti/project_model_model', 'codeField' => 'cd_project_model' ) );
$grid->addColumn('ds_project_build', 'Project Build', '150px', $f->retTypePickList(), array('model' => 'schedule/project_build_model', 'codeField' => 'cd_project_build' ) );
 
            
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