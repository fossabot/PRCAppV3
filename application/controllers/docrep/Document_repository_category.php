<?php

if (!defined('BASEPATH'))
   exit('No direct script access allowed');

include_once APPPATH . 'controllers/controllerBasicExtend.php';

class document_repository_category extends controllerBasicExtend {

   var $arrayIns;
   var $fields;

   function __construct() {
      parent::__construct();
      $this->load->model('docrep/document_repository_category_model', 'mainmodel', TRUE);
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

      $fm->addSimpleFilterUpper("Description", "ds_document_repository_category");
      $fm->addFilterYesNo("Active", "dt_deactivated", '', "Y");
      $filters = $fm->retFiltersWithGroup();

      $grid->setSingleBarControl(true);
      $grid->addCRUDToolbar();
      $grid->setCRUDController('docrep/document_repository_category');
      $grid->setToolbarSearch(true);
      $grid->addColumnKey();
      $grid->addColumn('ds_document_repository_category', 'Description', '100%', $f->retTypeStringUpper(), array("limit" => 64));
      $grid->addColumn('ds_system_permission', 'Permission to Handle', '100%', $f->retTypePickList(), array("model" => 'system_permission_model', 'codeField'=> 'cd_system_permission'));
      $grid->addColumn('fl_specific_purpose', 'Specific', '100px', $f->retTypeCheckBox(), true);
      
      
      
      $grid->addColumnDeactivated(true);


      $javascript = $grid->retGrid();


      $send = array("javascript" => $javascript,
         "filters" => $filters,
         "filters_java" => $fm->retJavascript());


      $this->load->view("defaultView", $send);
   }

}

?>
