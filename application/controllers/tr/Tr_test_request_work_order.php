<?php

if (!defined("BASEPATH"))
   exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class tr_test_request_work_order extends controllerBasicExtend {

   var $arrayIns;
   var $fields;

   function __construct() {
      parent::__construct();
      $this->load->model("tr/tr_test_request_work_order_model", "mainmodel", TRUE);
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

$fm->addSimpleFilterUpper('Test Item', 'filter_2', '"TR_TEST_REQUEST_WORK_ORDER".ds_test_item');
$fm->addSimpleFilterUpper('Test Procedure Name', 'filter_3', '"TR_TEST_REQUEST_WORK_ORDER".ds_test_procedure_name');
$fm->addSimpleFilterUpper('Sample List', 'filter_5', '"TR_TEST_REQUEST_WORK_ORDER".ds_sample_list');
$fm->addSimpleFilterUpper('Goal', 'filter_6', '"TR_TEST_REQUEST_WORK_ORDER".ds_goal');
$fm->addPickListFilter('Tr Test Request Work Order Status', 'filter_7', 'tr/tr_test_request_work_order_status', '"TR_TEST_REQUEST_WORK_ORDER".cd_tr_test_request_work_order_status');
$fm->addPickListFilter('Tr Test Request', 'filter_9', 'tr/tr_test_request', '"TR_TEST_REQUEST_WORK_ORDER".cd_tr_test_request');
$fm->addSimpleFilterUpper('Type Test', 'filter_10', '"TR_TEST_REQUEST_WORK_ORDER".ds_type_test');



$this->setGridParser();
      $grid->setSingleBarControl(true);
      $grid->addCRUDToolbar();
      $grid->setToolbarSearch(true);
      $grid->setCRUDController("tr/tr_test_request_work_order");

      $grid->addColumnKey();
      
$grid->addColumn('nr_work_order', 'Work Order', '150px', $f->retTypeNum(), array('precision' => '2', 'readonly' => false));
$grid->addColumn('ds_test_item', 'Test Item', '150px', $f->retTypeStringAny(), array('limit' => '') );
$grid->addColumn('ds_test_procedure_name', 'Test Procedure Name', '150px', $f->retTypeStringAny(), array('limit' => '') );
$grid->addColumn('nr_sample_qtty', 'Sample Qty', '150px', $f->retTypeNum(), array('precision' => '2', 'readonly' => false));
$grid->addColumn('ds_sample_list', 'Sample List', '150px', $f->retTypeStringAny(), array('limit' => '') );
$grid->addColumn('ds_goal', 'Goal', '150px', $f->retTypeStringAny(), array('limit' => '') );
$grid->addColumn('ds_tr_test_request_work_order_status', 'Tr Test Request Work Order Status', '150px', $f->retTypePickList(), array('model' => 'tr/tr_test_request_work_order_status_model', 'codeField' => 'cd_tr_test_request_work_order_status' ) );
$grid->addColumn('dt_assign_to_technician', 'Assign To Technician', '80px', $f->retTypeDate(), true );
$grid->addColumn('ds_tr_test_request', 'Tr Test Request', '150px', $f->retTypePickList(), array('model' => 'tr/tr_test_request_model', 'codeField' => 'cd_tr_test_request' ) );
$grid->addColumn('ds_type_test', 'Type Test', '150px', $f->retTypeStringAny(), array('limit' => '') );
 
            
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