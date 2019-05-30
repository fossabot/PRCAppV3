<?php

if (!defined("BASEPATH"))
   exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class tr_magic_log extends controllerBasicExtend {

   var $arrayIns;
   var $fields;

   function __construct() {
      parent::__construct();
      $this->load->model("tr_magic_log_model", "mainmodel", TRUE);
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

$fm->addSimpleFilterUpper('User', 'filter_1', '"TR_MAGIC_LOG".ds_user');



$this->setGridParser();
      $grid->setSingleBarControl(true);
      $grid->addCRUDToolbar();
      $grid->setToolbarSearch(true);
      $grid->setCRUDController("tr_magic_log");

      $grid->addColumnKey();
      
$grid->addColumn('ds_user', 'User', '150px', $f->retTypeStringAny(), array('limit' => '') );
$grid->addColumn('dt_start', 'Start', '80px', $f->retTypeDate(), true );
$grid->addColumn('dt_finish', 'Finish', '80px', $f->retTypeDate(), true );
$grid->addColumn('nr_count', 'Count', '150px', $f->retTypeInteger(), true );
$grid->addColumn('fl_activated', 'Activated', '150px', $f->retTypeCheckBox(), true );
 
            
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