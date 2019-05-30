<?php

if (!defined('BASEPATH'))
   exit('No direct script access allowed');

include_once APPPATH . 'controllers/controllerBasicExtend.php';

class country extends controllerBasicExtend {

   var $arrayIns;
   var $fields;

   function __construct() {
      parent::__construct();
      $this->load->model('country_model', 'mainmodel', TRUE);
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

      $fm->addSimpleFilterUpper("Country", "ds_country");
      $fm->addFilterYesNo("Active", "dt_deactivated", '', "Y");
      $filters = $fm->retFiltersWithGroup();
      
      $this->setGridParser();
      $grid->setSingleBarControl(true);
      $grid->addCRUDToolbar();
      $grid->setToolbarSearch(true);
      $grid->setCRUDController(get_class());

      $grid->addColumnKey();
      $grid->addColumn('ds_country', 'Description', '100%', $f->retTypeStringUpper(), array("limit" => 64));
      $grid->addColumn('nr_country_number', 'Number', '90px', $f->retTypeInteger(), array("limit" => 3));
      $grid->addColumn('ds_iso_alpha2', 'A2 (ISO)', '90px', $f->retTypeStringUpper(), array("limit" => 2));
      $grid->addColumn('ds_iso_alpha3', 'A3 (UN)', '90px', $f->retTypeStringUpper(), array("limit" => 3));
      $grid->addColumnDeactivated(true);

      
      $javascript = $grid->retGrid();


      $send = array("javascript" => $javascript,
         "filters" => $filters,
         "filters_java" => $fm->retJavascript());


      $this->load->view("defaultView", $send);
   }

}

?>