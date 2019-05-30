<?php

if (!defined("BASEPATH"))
   exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class project_build_schedule_comments_cc extends controllerBasicExtend {

   var $arrayIns;
   var $fields;

   function __construct() {
      parent::__construct();
      $this->load->model("schedule/project_build_schedule_comments_cc_model", "mainmodel", TRUE);
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

$fm->addPickListFilter('Project Build Schedule Comments', 'filter_1', 'schedule/project_build_schedule_comments', '"PROJECT_BUILD_SCHEDULE_COMMENTS_CC".cd_project_build_schedule_comments');
$fm->addPickListFilter('Human Resource', 'filter_2', 'human_resource', '"PROJECT_BUILD_SCHEDULE_COMMENTS_CC".cd_human_resource');



$this->setGridParser();
      $grid->setSingleBarControl(true);
      $grid->addCRUDToolbar();
      $grid->setToolbarSearch(true);
      $grid->setCRUDController("schedule/project_build_schedule_comments_cc");

      $grid->addColumnKey();
      
$grid->addColumn('ds_project_build_schedule_comments', 'Project Build Schedule Comments', '150px', $f->retTypePickList(), array('model' => 'schedule/project_build_schedule_comments_model', 'codeField' => 'cd_project_build_schedule_comments' ) );
$grid->addColumn('ds_human_resource', 'Human Resource', '150px', $f->retTypePickList(), array('model' => 'human_resource_model', 'codeField' => 'cd_human_resource' ) );
 
            
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