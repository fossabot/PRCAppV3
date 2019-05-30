<?php

if (!defined('BASEPATH'))
   exit('No direct script access allowed');
include_once APPPATH . 'controllers/controllerBasicExtend.php';

class dictionary extends controllerBasicExtend {

   var $arrayIns;

   function __construct() {
      parent::__construct();

      $this->load->model('dictionary_model', 'mainmodel', TRUE);

   }

   public function index() {
      parent::checkMenuPermission();

      $f = $this->cfields;
      $fm = $this->cfiltermaker;
      if (1 == 2) {
         $fm = new cfiltermaker();
         $g = new w2gridgen;
      }
      
      $fm->addPickListFilter('Language', 'cd_system_languages', 'system_languages');
      $trans = array ( array( 'desc'=>'PENDING', 'sql' => 'and ds_system_dictionary_main = ds_translated'),
                       array( 'desc'=>'DONE', 'sql' => 'and ds_system_dictionary_main <> ds_translated')
                     );
      
      $fm->addFilter('trans_status', 'Translation Status',array('plFixedSelect' =>   $trans  ));
      
      $filters = $fm->retFiltersWithGroup();
      $this->w2gridgen->setCRUDController(get_class());
      $this->w2gridgen->addCRUDToolbar(true, false, true, false, true);
      $this->w2gridgen->addBreakToolbar();
      $this->w2gridgen->addUserBtnToolbar('apply', 'Apply Changes to Everybody', 'fa fa-flash');
      //$this->w2gridgen->setHeader("Dictionary");
      $this->w2gridgen->setToolbarSearch(true);
      $colid = $this->w2gridgen->addColumn('recid', 'Code', '100px', $f->retTypeKey());
      $colid = $this->w2gridgen->addColumn('ds_system_dictionary_main', 'Text English', '100%', $f->retTypeStringAny(), false);
      $colid = $this->w2gridgen->addColumn('ds_translated', 'Translated', '100%', $f->retTypeStringAny(), true);
      $colid = $this->w2gridgen->addColumn('ds_system_languages', 'Language', '100%', $f->retTypeStringAny(), false);

      $javascript = $this->w2gridgen->retGrid();


      $send = array("javascript" => $javascript,
         "filters" => $filters,
         "filters_java" => $fm->retJavascript());

      $this->load->view("dictionary_view", $send);
   }
   
   
   public function applyLanguage($language) {
      $this->cdbhelper->resetTransFromMemory($language);
      echo ('Done');
   }


}

?>