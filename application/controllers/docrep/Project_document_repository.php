<?php

if (!defined("BASEPATH"))
   exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class project_document_repository extends controllerBasicExtend {

   var $arrayIns;
   var $fields;

   function __construct() {
      parent::__construct();
      $this->load->model("docrep/project_document_repository_model", "mainmodel", TRUE);
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

$fm->addPickListFilter('Project', 'filter_1', 'tti/project', '"PROJECT_DOCUMENT_REPOSITORY".cd_project');
$fm->addPickListFilter('Document Repository', 'filter_2', 'docrep/document_repository', '"PROJECT_DOCUMENT_REPOSITORY".cd_document_repository');



$this->setGridParser();
      $grid->setSingleBarControl(true);
      $grid->addCRUDToolbar();
      $grid->setToolbarSearch(true);
      $grid->setCRUDController("docrep/project_document_repository");

      $grid->addColumnKey();
      
$grid->addColumn('ds_project', 'Project', '150px', $f->retTypePickList(), array('model' => 'tti/project_model', 'codeField' => 'cd_project' ) );
$grid->addColumn('ds_document_repository', 'Document Repository', '150px', $f->retTypePickList(), array('model' => 'docrep/document_repository_model', 'codeField' => 'cd_document_repository' ) );
 
            
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