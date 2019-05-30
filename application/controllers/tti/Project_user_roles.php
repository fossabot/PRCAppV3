<?php

if (!defined("BASEPATH"))
   exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class project_user_roles extends controllerBasicExtend {

   var $arrayIns;
   var $fields;

   function __construct() {
      parent::__construct();
      $this->load->model("tti/project_user_roles_model", "mainmodel", TRUE);
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

$fm->addPickListFilter('Human Resource', 'filter_1', 'human_resource', '"PROJECT_USER_ROLES".cd_human_resource');
$fm->addPickListFilter('Roles', 'filter_2', 'tti/roles', '"PROJECT_USER_ROLES".cd_roles');
$fm->addPickListFilter('Notification Type', 'filter_4', 'notification_type', '"PROJECT_USER_ROLES".cd_notification_type');



$this->setGridParser();
      $grid->setSingleBarControl(true);
      $grid->addCRUDToolbar();
      $grid->setToolbarSearch(true);
      $grid->setCRUDController("tti/project_user_roles");

      $grid->addColumnKey();
      
$grid->addColumn('ds_human_resource', 'Human Resource', '150px', $f->retTypePickList(), array('model' => 'human_resource_model', 'codeField' => 'cd_human_resource' ) );
$grid->addColumn('ds_roles', 'Roles', '150px', $f->retTypePickList(), array('model' => 'tti/roles_model', 'codeField' => 'cd_roles' ) );
$grid->addColumn('fl_active', 'Active', '150px', $f->retTypeCheckBox(), true );
$grid->addColumn('ds_notification_type', 'Notification Type', '150px', $f->retTypePickList(), array('model' => 'notification_type_model', 'codeField' => 'cd_notification_type' ) );
 
            
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