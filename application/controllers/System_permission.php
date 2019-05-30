<?php

if (!defined("BASEPATH"))
   exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class system_permission extends controllerBasicExtend {

   var $arrayIns;
   var $fields;

   function __construct() {
      parent::__construct();
      $this->load->model("system_permission_model", "mainmodel", TRUE);
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

$fm->addSimpleFilterUpper('System Permission', 'filter_1', '"SYSTEM_PERMISSION".ds_system_permission');
$fm->addSimpleFilterUpper('System Permission Id', 'filter_2', '"SYSTEM_PERMISSION".ds_system_permission_id');
$fm->addSimpleFilterUpper('System Parameter Obs', 'filter_3', '"SYSTEM_PERMISSION".ds_system_parameter_obs');
$fm->addPickListFilter('Type Sys Permission', 'filter_4', 'type_sys_permission', '"SYSTEM_PERMISSION".cd_type_sys_permission');



$this->setGridParser();
      $grid->setSingleBarControl(true);
      $grid->addCRUDToolbar();
      $grid->setToolbarSearch(true);
      $grid->setCRUDController("system_permission");

      $grid->addColumnKey();
      
$grid->addColumn('ds_system_permission', 'System Permission', '150px', $f->retTypeStringAny(), array('limit' => '64') );
$grid->addColumn('ds_system_permission_id', 'System Permission Id', '150px', $f->retTypeStringAny(), array('limit' => '64') );
$grid->addColumn('ds_system_parameter_obs', 'System Parameter Obs', '150px', $f->retTypeStringAny(), array('limit' => '64') );
$grid->addColumn('ds_type_sys_permission', 'Type Sys Permission', '150px', $f->retTypePickList(), array('model' => 'type_sys_permission_model', 'codeField' => 'cd_type_sys_permission' ) );
 
            
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