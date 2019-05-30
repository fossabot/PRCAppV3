<?php

if (!defined('BASEPATH'))
   exit('No direct script access allowed');
include_once APPPATH . 'controllers/controllerBasicExtend.php';

class currency extends controllerBasicExtend {

   var $arrayIns;
   var $fields;
   var $start;
   function __construct() {
      
      $this->start = microtime(true); 
       parent::__construct();
      
      // primeira acoisa a fazer eh verificar se esta logado!


      $this->load->model('currency_model', 'mainmodel', TRUE);
      
      $time_end = microtime(true);
      $execution_time = ($time_end - $this->start);
      

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



      $fm->addSimpleFilterUpper("Currency", "ds_currency");
      //$fm->addPickListFilter('Country', 'cd_country', 'country');
      $fm->addFilterYesNo("Active", "dt_deactivated", '', "Y");
      $filters = $fm->retFiltersWithGroup();

      $grid->setCRUDController('currency');
      $grid->addCRUDToolbar();
      $grid->addColumnKey();
      $grid->addColumn('ds_currency', 'Description', '100%', $f->retTypeStringUpper(), array("limit" => 64));
      $grid->addColumn('ds_currency_symbol', 'Symbol', '60px', $f->retTypeStringUpper(), array("limit" => 3));
      $grid->addColumnDeactivated(true);


      $javascript = $grid->retGrid();


      $send = array("javascript" => $javascript,
         "filters" => $filters,
         "filters_java" => $fm->retJavascript());


      $this->load->view("defaultView", $send);


      //print_r($arrayIns);
   }


}

?>