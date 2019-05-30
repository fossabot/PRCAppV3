<?php

if (!defined("BASEPATH"))
   exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class project_model_document_repository_type extends controllerBasicExtend {

   var $arrayIns;
   var $fields;

   function __construct() {
      parent::__construct();
      $this->load->model("docrep/project_model_document_repository_type_model", "mainmodel", TRUE);
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

$fm->addSimpleFilterUpper('Project Model Document Repository Type', 'filter_1', '"PROJECT_MODEL_DOCUMENT_REPOSITORY_TYPE".ds_project_model_document_repository_type');
$fm->addFilterYesNo("Active", "dt_deactivated", "", "Y");



$this->setGridParser();
      $grid->setSingleBarControl(true);
      $grid->addCRUDToolbar();
      $grid->setToolbarSearch(true);
      $grid->setCRUDController("docrep/project_model_document_repository_type");

      $grid->addColumnKey();
      
$grid->addColumn('ds_project_model_document_repository_type', 'Project Model Document Repository Type', '150px', $f->retTypeStringAny(), array('limit' => '') );
$grid->addColumn('fl_default', 'Default', '150px', $f->retTypeCheckBox(), true );
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