<?php

if (!defined("BASEPATH"))
   exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class department extends controllerBasicExtend {

   var $arrayIns;
   var $fields;

   function __construct() {
      parent::__construct();
      $this->load->model("department_model", "mainmodel", TRUE);
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

$fm->addSimpleFilterUpper('Department', 'filter_1', '"DEPARTMENT".ds_department');
$fm->addSimpleFilterUpper('Department Code', 'filter_4', '"DEPARTMENT".ds_department_code');
$fm->addFilterYesNo("Active", "dt_deactivated", "", "Y");



$this->setGridParser();
      $grid->setSingleBarControl(true);
      $grid->addCRUDToolbar();
      $grid->setToolbarSearch(true);
      $grid->setCRUDController("department");

      $grid->addColumnKey();
      
$grid->addColumn('ds_department', 'Department', '150px', $f->retTypeStringAny(), array('limit' => '128') );
$grid->addColumn('ds_department_code', 'Department Code', '150px', $f->retTypeStringAny(), array('limit' => '16') );
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