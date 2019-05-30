<?php

if (!defined("BASEPATH"))
   exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class wi_section_revision_document_repository extends controllerBasicExtend {

   var $arrayIns;
   var $fields;

   function __construct() {
      parent::__construct();
      $this->load->model("docrep/wi_section_revision_document_repository_model", "mainmodel", TRUE);
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

$fm->addPickListFilter('Wi Section Revision', 'filter_1', 'schedule/wi_section_revision', '"WI_SECTION_REVISION_DOCUMENT_REPOSITORY".cd_wi_section_revision');
$fm->addPickListFilter('Wi Section Revision Document Repository Type', 'filter_4', 'docrep/wi_section_revision_document_repository_type', '"WI_SECTION_REVISION_DOCUMENT_REPOSITORY".cd_wi_section_revision_document_repository_type');



$this->setGridParser();
      $grid->setSingleBarControl(true);
      $grid->addCRUDToolbar();
      $grid->setToolbarSearch(true);
      $grid->setCRUDController("docrep/wi_section_revision_document_repository");

      $grid->addColumnKey();
      
$grid->addColumn('ds_wi_section_revision', 'Wi Section Revision', '150px', $f->retTypePickList(), array('model' => 'schedule/wi_section_revision_model', 'codeField' => 'cd_wi_section_revision' ) );
$grid->addColumn('cd_document_repository', 'Document Repository', '150px', $f->retTypeInteger(), true );
$grid->addColumn('ds_wi_section_revision_document_repository_type', 'Wi Section Revision Document Repository Type', '150px', $f->retTypePickList(), array('model' => 'docrep/wi_section_revision_document_repository_type_model', 'codeField' => 'cd_wi_section_revision_document_repository_type' ) );
 
            
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