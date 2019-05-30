<?php

if (!defined('BASEPATH'))
   exit('No direct script access allowed');

include_once APPPATH . 'controllers/controllerBasicExtend.php';

class division_full extends controllerBasicExtend {

   var $arrayIns;

   function __construct() {
      parent::__construct();
   }

   public function index() {
      $f = $this->cfields;
      $ctabs = $this->ctabs;
      
      // soh pra eu conseguir o auto complete
      if (1 == 2) {
         $grid = new w2gridgen();
         $cdbhelper = new cdbhelper();
         $ctabs = new ctabs();
      }
      
      $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");

      $ctabs->addTab('Division', 'tab_division');
      $ctabs->addTab('Brand', 'tab_brand');
      $ctabs->makeContentDiv();
      $tabsc = $ctabs->retTabs();

      $grid = $this->w2gridgen;
      $cdbhelper = $this->cdbhelper;

      $fm = new cfiltermaker();
      $fm = $this->cfiltermaker;

      $grid->setForceDestroy(true);

      $fm->addSimpleFilterUpper("Division", "ds_division");
      $fm->addSimpleFilterUpper("Brand", "ds_division_brand");


      $fm->addPickListFilterExists("Having Division", 
                                   "division", 
                                   "cd_division_x_division_brand", 
                                   "DIVISION_BRAND", 
                                   "cd_division_brand", 
                                   "DIVISION_X_DIVISION_BRAND", 
                                   "cd_division", "", true);

      $fm->addFilterYesNo("Active", "dt_deactivated", '', "Y");
      $filters = $fm->retFiltersWithGroup();

      $f = $this->cfields;

      if (1 == 2) {
         $grid = new w2gridgen();
         $cdbhelper = new cdbhelper();
         $f = new Cfields();
      }

      $grid = $this->w2gridgen;
      $cdbhelper = $this->cdbhelper;

      $fm = new cfiltermaker();
      $fm = $this->cfiltermaker;

      $grid->setGridVar('varprd_division');
      $grid->setGridName('varprd_division');
      $grid->setHeader('Division');
      $grid->setCRUDController('division');
      $grid->addCRUDToolbar(true, true, true, true, true);

      $grid->addColumnKey();
      $grid->addColumn('ds_division', 'Description', '100%', $f->retTypeStringUpper(), array('limit' => 64));
      $grid->addColumn('ds_division_short', 'Short', '100px', $f->retTypeStringAny(), array('limit' => 6));

      $grid->addColumnDeactivated(true);

      $javascript = $grid->retGridVar();


      // product type
      $grid->resetGrid();
      $grid->setGridVar('varprd_division_brand');
      $grid->setCRUDController('division_brand');

      $grid->setGridName('varprd_division_brand');
      $grid->setHeader('Division Brand');

      $grid->addCRUDToolbar(true, true, true, true, true);

      $grid->addColumnKey();
      $grid->addColumn('ds_division_brand', 'Description', '100%', $f->retTypeStringUpper(), array('limit' => 64));
      $grid->addColumnDeactivated(true);

      $javascript = $javascript . $grid->retGridVar();


      // product items related type
      $grid->resetGrid();
      $grid->setGridVar('varprd_division_brand_related');
      $grid->setToolbarSearch(true);
      $grid->setGridName('varprd_division_brand_related');
      $grid->setHeader('Brand Related');

      $grid->setToolbarPrefix('side');
      $grid->addEditToolbar();


      $grid->addColumnKey();
      $grid->addColumn('ds_description', 'Description', '100%', $f->retTypeStringUpper(), false);

      $javascript = $javascript . $grid->retGridVar();

      // product items related type
      $grid->resetGrid();
      $grid->setGridVar('varprd_division_related');
      $grid->setGridName('varprd_division_related');
      $grid->setHeader('Division Related');

      $grid->setToolbarPrefix('side');
      $grid->addEditToolbar();

      $grid->addColumnKey();
      $grid->setToolbarSearch(true);

      $grid->addColumn('ds_description', 'Description', '100%', $f->retTypeStringUpper(), false);

      $javascript = $javascript . $grid->retGridVar();

      $send = array("tab" => $tabsc,
           "javascript" => $javascript,
         "filters" => $filters,
         "filters_java" => $fm->retJavascript()) ;//+ $label;


      $this->load->view("division_full_view", $send);
   }

}

?>