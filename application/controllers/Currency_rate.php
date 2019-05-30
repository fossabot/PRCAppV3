<?php

if (!defined('BASEPATH'))
   exit('No direct script access allowed');
include_once APPPATH . 'controllers/controllerBasicExtend.php';

class currency_rate extends controllerBasicExtend {

   var $arrayIns;
   var $fields;

   function __construct() {
      parent::__construct();


      $this->load->model('currency_rate_model', 'mainmodel', TRUE);

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

      //$fm->addSimpleFilterUpper("Currency", "ds_currency");
      $fm->addPickListFilter('Currency From', 'cur_from', 'currency', 'cd_currency_from');
      $fm->addPickListFilter('Currency To', 'cur_to', 'currency', 'cd_currency_to');
      
      
      $fm->addFilterYesNo("Active", "dt_deactivated", '', "Y");
      $filters = $fm->retFiltersWithGroup();

      $grid->addCRUDToolbar();
      $grid->addColumnKey();
      $grid->setCRUDController('currency_rate');
      $grid->addColumn('ds_currency_rate', 'Description', '40%', $f->retTypeStringUpper(), false);
      
      $grid->addColumn('ds_currency_from', 'From', '30%', $f->retTypePickList(), array('model' => 'currency_model', 'codeField' => 'cd_currency_from' ) );
      $grid->addColumn('ds_currency_to', 'To', '30%', $f->retTypePickList(), array('model' => 'currency_model', 'codeField' => 'cd_currency_to' ) );
      $grid->addColumn('dt_currency_rate', 'Date', '80px', $f->retTypeDate(), true);

      $grid->addColumn('nr_currency_rate', 'Value', '80px', $f->retTypeNum(), array('precision' => '4', 'readonly' => false));
      

      $grid->addColumnDeactivated(true);


      $javascript = $grid->retGrid();


      $send = array("javascript" => $javascript,
         "filters" => $filters,
         "filters_java" => $fm->retJavascript());


      $this->load->view("defaultView", $send);


      //print_r($arrayIns);
   }

   function openPL ($cd_from, $cd_to) {
       
        $controller = 'currency_rate';

        if ($this->cdbhelper->checkMenuRights($controller) != 'Y') {
            $controller = '';
        }

        $grid = $this->w2gridgen;
        $f = $this->cfields;
        $fm = $this->cfiltermaker;

        if (1 == 2) {
            $f = new Cfields();
            $grid = new w2gridgen();
            $fm = new cfiltermaker();
        }

        $fm = $this->cfiltermaker;
        $fm->setColumnBig();
        //$fm->addPickListFilter('Quotation Type', 'pl_filter_17', 'material/product_quotation_type', '"SHOE_COST_SHEET_SKU".cd_product_quotation_type');

        $filters = $fm->retFiltersWithGroup();

        $grid->setGridToolbarFunction('onGridToolbarPressedPL');
        //$grid->addUserBtnToolbar('clear', 'Clear', 'fa fa-times-circle-o', 'Clear');

        $grid->setGridVar('varMySpecificPL');
        $grid->setGridName('specificPLPrc');
        $grid->setCRUDController('currency_rate');
        $grid->setSingleBarControl(true);

        //$grid->addRetriveToolbar();
        //$grid->addBreakToolbar();

        if ($controller !== '') {
            $grid->addUserBtnToolbar('openMaint', 'Open Maintenance', 'fa fa-external-link');
        }
        $grid->addBreakToolbar();



        $grid->setToolbarSearch(true);

        $grid->addColumnKey();

        $grid->addColumn('ds_currency_rate', 'Description', '100%', $f->retTypeStringAny(), false);

        $grid->addColumn('nr_currency_rate', 'Rate', '120px', $f->retTypeNum(), array('precision' => '4', 'readonly' => true));

        $data = $this->mainmodel->retRetrieveGridJson(" WHERE cd_currency_from = $cd_from AND cd_currency_to = $cd_to AND dt_deactivated IS NULL", "ORDER BY dt_currency_rate DESC");
        
        $grid->addRecords($data);
        $javascript = $grid->retGridVar();

        $labels = array('title' => 'Cost Sheet');
        $labels = $this->cdbhelper->retTranslationDifKeys($labels);

        $send = array("javascript" => $javascript,
            "filters" => $filters,
            "filters_java" => $fm->retJavascript(),
            "keyColumn" => 'cd_currency_rate',
            "descColumn" => 'ds_currency_rate',
            'retrieveFields' => json_encode($fm->getFilterNames()),
            //'retrieveController' => 'retrievePLCS/' . $cd_shoe_sku . '/' . $cd_factory . '/' . $cd_type,
            'controller' => $controller
        );


        $this->load->view("currency_rate_pl_view", $send + $labels);
       
       
   }

   

}

?>