<?php

if (!defined("BASEPATH"))
   exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class project_model_document_repository extends controllerBasicExtend {

   var $arrayIns;
   var $fields;

   function __construct() {
      parent::__construct();
      $this->load->model("docrep/project_model_document_repository_model", "mainmodel", TRUE);
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

$fm->addPickListFilter('Project Model', 'filter_1', 'tti/project_model', '"PROJECT_MODEL_DOCUMENT_REPOSITORY".cd_project_model');
$fm->addPickListFilter('Document Repository', 'filter_2', 'docrep/document_repository', '"PROJECT_MODEL_DOCUMENT_REPOSITORY".cd_document_repository');
$fm->addPickListFilter('Project Model Document Repository Type', 'filter_4', 'docrep/project_model_document_repository_type', '"PROJECT_MODEL_DOCUMENT_REPOSITORY".cd_project_model_document_repository_type');



$this->setGridParser();
      $grid->setSingleBarControl(true);
      $grid->addCRUDToolbar();
      $grid->setToolbarSearch(true);
      $grid->setCRUDController("docrep/project_model_document_repository");

      $grid->addColumnKey();
      
$grid->addColumn('ds_project_model', 'Project Model', '150px', $f->retTypePickList(), array('model' => 'tti/project_model_model', 'codeField' => 'cd_project_model' ) );
$grid->addColumn('ds_document_repository', 'Document Repository', '150px', $f->retTypePickList(), array('model' => 'docrep/document_repository_model', 'codeField' => 'cd_document_repository' ) );
$grid->addColumn('ds_project_model_document_repository_type', 'Project Model Document Repository Type', '150px', $f->retTypePickList(), array('model' => 'docrep/project_model_document_repository_type_model', 'codeField' => 'cd_project_model_document_repository_type' ) );
$grid->addColumn('fl_main', 'Main', '150px', $f->retTypeCheckBox(), true );
 
            
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