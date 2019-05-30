<?php

if (!defined("BASEPATH"))
   exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class project_comments_type_group_human_resource extends controllerBasicExtend {

   var $arrayIns;
   var $fields;

   function __construct() {
      parent::__construct();
      $this->load->model("tti/project_comments_type_group_human_resource_model", "mainmodel", TRUE);
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

$fm->addPickListFilter('Project Comments Type Group', 'filter_3', 'tti/project_comments_type_group', '"PROJECT_COMMENTS_TYPE_GROUP_HUMAN_RESOURCE".cd_project_comments_type_group');
$fm->addPickListFilter('Human Resource', 'filter_4', 'human_resource', '"PROJECT_COMMENTS_TYPE_GROUP_HUMAN_RESOURCE".cd_human_resource');
$fm->addFilterYesNo("Active", "dt_deactivated", "", "Y");



$this->setGridParser();
      $grid->setSingleBarControl(true);
      $grid->addCRUDToolbar();
      $grid->setToolbarSearch(true);
      $grid->setCRUDController("tti/project_comments_type_group_human_resource");

      $grid->addColumnKey();
      
$grid->addColumn('ds_project_comments_type_group', 'Project Comments Type Group', '150px', $f->retTypePickList(), array('model' => 'tti/project_comments_type_group_model', 'codeField' => 'cd_project_comments_type_group' ) );
$grid->addColumn('ds_human_resource', 'Human Resource', '150px', $f->retTypePickList(), array('model' => 'human_resource_model', 'codeField' => 'cd_human_resource' ) );
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