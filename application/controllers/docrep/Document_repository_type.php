<?php

if (!defined('BASEPATH'))
   exit('No direct script access allowed');

include_once APPPATH . 'controllers/controllerBasicExtend.php';

class document_repository_type extends controllerBasicExtend {

   var $arrayIns;
   var $fields;

   function __construct() {
      parent::__construct();
      $this->load->model('docrep/document_repository_type_model', 'mainmodel', TRUE);
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

      $fm->addSimpleFilterUpper("Description", "ds_document_repository_type");
      $fm->addPickListFilter('Category', 'cd_document_repository_category', 'docrep/document_repository_category');
      $fm->addFilterYesNo("Active", "dt_deactivated", '', "Y");
      $filters = $fm->retFiltersWithGroup();

      $grid->setSingleBarControl(true);
      $grid->addCRUDToolbar();
      $grid->setToolbarSearch(true);
      $grid->setCRUDController('docrep/document_repository_type');
      $grid->addColumnKey();
      $grid->addColumn('ds_document_repository_type', 'Description', '100px', $f->retTypeStringUpper(), array("limit" => 64));
      $grid->addColumn('ds_document_repository_category', 'Category', '100px', $f->retTypePickList(), array("model" => 'docrep/document_repository_category_model', 'codeField' => 'cd_document_repository_category'));
      $grid->addColumn('ds_document_repository_extension', 'Extension', '80px', $f->retTypeStringLower(), array("limit" => 16));
      $grid->addColumn('ds_mime_type', 'Mime Type', '120px', $f->retTypeStringAny(), array("limit" => 9999));

      $grid->addColumn('fl_is_image', 'Image' , '80px', $f->retTypeCheckBox(), true);
      
      $grid->addColumn('ds_icon', 'Icon', '120px', $f->retTypeStringLower(), array("limit" => 32));
      
      $grid->addColumn('fl_generate_thumbs', 'Thumbs' , '80px', $f->retTypeCheckBox(), true);
      $grid->addColumn('nr_thumbs_width', 'Height' , '80px', $f->retTypeInteger(), true);
      $grid->addColumn('nr_thumbs_height', 'Width' , '80px', $f->retTypeInteger(), true);
      $grid->addColumn('fl_thumbs_two_step', 'Thumbs 2 Steps' , '120px', $f->retTypeCheckBox(), true);
      $grid->addColumn('fl_thumbs_high_quality', 'HQ Thumbs' , '80px', $f->retTypeCheckBox(), true);      
      $grid->addColumn('nr_max_size_kb', 'Max Size (Kb)' , '100px', $f->retTypeInteger(), true);      
      
      
      
      $grid->addColumnDeactivated(true);

      
      $javascript = $grid->retGrid();


      $send = array("javascript" => $javascript,
         "filters" => $filters,
         "filters_java" => $fm->retJavascript());


      $this->load->view("defaultView", $send);
   }

}

?>