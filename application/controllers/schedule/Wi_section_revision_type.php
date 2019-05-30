<?php

if (!defined("BASEPATH"))
   exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class wi_section_revision_type extends controllerBasicExtend {

   var $arrayIns;
   var $fields;

   function __construct() {
      parent::__construct();
      $this->load->model("schedule/wi_section_revision_type_model", "mainmodel", TRUE);
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

$fm->addSimpleFilterUpper('Wi Section Revision Type', 'filter_1', '"WI_SECTION_REVISION_TYPE".ds_wi_section_revision_type');
$fm->addFilterYesNo("Active", "dt_deactivated", "", "Y");



$this->setGridParser();
      $grid->setSingleBarControl(true);
      $grid->addCRUDToolbar();
      $grid->setToolbarSearch(true);
      $grid->setCRUDController("schedule/wi_section_revision_type");

      $grid->addColumnKey();
      
$grid->addColumn('ds_wi_section_revision_type', 'Wi Section Revision Type', '150px', $f->retTypeStringAny(), array('limit' => '') );
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