<?php

if (!defined('BASEPATH'))
   exit('No direct script access allowed');

include_once APPPATH . 'controllers/controllerBasicExtend.php';

class trading extends controllerBasicExtend {

   var $arrayIns;
   var $fields;

   function __construct() {
      parent::__construct();
      $this->load->model('trading_model', 'mainmodel', TRUE);
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

      $fm->addSimpleFilterUpper("Trading", "ds_trading");
      $fm->addFilterYesNo("Active", "dt_deactivated", '', "Y");
      $filters = $fm->retFiltersWithGroup();
      $this->setGridParser();
      
      $grid->setSingleBarControl(true);
      $grid->addCRUDToolbar();
      $grid->setToolbarSearch(true);
      $grid->addColumnKey();
      $grid->setCRUDController(get_class());

      $grid->addColumn('ds_trading', 'Trading', '100%', $f->retTypeStringUpper(), array("limit" => 64));
      $grid->addColumn('ds_trading_short', 'Short', '150px', $f->retTypeStringUpper(), array("limit" => 16));
      $grid->addColumn('ds_trading_on_docs', 'Title on Docs', '150px', $f->retTypeStringUpper(), array("limit" => 64));
      $grid->addColumn('ds_address', 'Address', '150px', $f->retTypeTextPL(), true);
      $grid->addColumn('ds_bank_information', 'Bank Information', '150px',$f->retTypeTextPL(), true);
      $grid->addColumnDeactivated(true);

      
      $javascript = $grid->retGrid();


      $send = array("javascript" => $javascript,
         "filters" => $filters,
         "filters_java" => $fm->retJavascript());


      $this->load->view("defaultView", $send);
   }

}

?>