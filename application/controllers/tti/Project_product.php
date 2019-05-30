<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class project_product extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("tti/project_product_model", "mainmodel", TRUE);
    }

    public function index() {

        parent::checkMenuPermission();


        $grid = $this->w2gridgen;
        $f = $this->cfields;
        $fm = $this->cfiltermaker;
        $ctabs = $this->ctabs;
        if (1 == 2) {
            $f = new Cfields();
            $grid = new w2gridgen();
            $fm = new cfiltermaker();
            $ctabs = new ctabs();
        }


        $ctabs->addTab('Product', 'tab_project_product');
        $ctabs->addTab('Category', 'tab_project_power_type');
        $ctabs->addTab('Tool Type', 'tab_project_tool_type');

        $ctabs->makeContentDiv();
        $tabsc = $ctabs->retTabs();


        $fm = $this->cfiltermaker;

        $fm->addSimpleFilterUpper('Description', 'filter_desc_pp', '"PROJECT_PRODUCT".ds_project_product');
        $fm->addSimpleFilterUpper('Tool Type', 'filter_desc_tt', '"PROJECT_TOOL_TYPE".ds_project_tool_type');
        $fm->addSimpleFilterUpper('Category', 'filter_desc_pt', '"PROJECT_POWER_TYPE".ds_project_power_type');
        $fm->addFilterYesNo("Active", "dt_deactivated", "", "Y");

        $this->setGridParser();

        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("tti/project_product");
        $grid->setGridVar('varprd_project_product');
        $grid->setGridName('varprd_project_product');
        $grid->setHeader('Product');
        $grid->addColumnKey();

        $grid->addColumn('ds_project_product', 'Description', '100%', $f->retTypeStringAny(), array('limit' => '64'));
        $grid->addColumnDeactivated(true);


        $javascript = $grid->retGridVar();


        $grid->resetGrid();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("tti/project_power_type");
        $grid->setGridVar('varprd_project_power_type');
        $grid->setGridName('varprd_project_power_type');
        $grid->setHeader('Category');

        $grid->addColumnKey();

        $grid->addColumn('ds_project_power_type', 'Description', '100%', $f->retTypeStringAny(), array('limit' => '64'));
        $grid->addColumnDeactivated(true);
        $javascript = $javascript . $grid->retGridVar();
        
        $grid->resetGrid();
        
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("tti/project_tool_type");
        $grid->setGridVar('varprd_project_tool_type');
        $grid->setGridName('varprd_project_tool_type');
        $grid->setHeader('Tool Type');

        $grid->addColumnKey();

        $grid->addColumn('ds_project_tool_type', 'Project Tool Type', '100%', $f->retTypeStringAny(), array('limit' => '64'));
        $grid->addColumnDeactivated(true);
        $javascript = $javascript . $grid->retGridVar();

        

        // product items related type
        $grid->resetGrid();
        $grid->setGridVar('varprd_project_power_type_related');
        $grid->setToolbarSearch(true);
        $grid->setGridName('varprd_project_power_type_related');
        $grid->setHeader('Category Related');

        $grid->setToolbarPrefix('side');
        $grid->addEditToolbar();


        $grid->addColumnKey();
        $grid->addColumn('ds_description', 'Description', '100%', $f->retTypeStringAny(), false);

        $javascript = $javascript . $grid->retGridVar();

        // product items related type
        $grid->resetGrid();
        $grid->setGridVar('varprd_project_tool_type_related');
        $grid->setGridName('varprd_project_tool_type_related');
        $grid->setHeader('Tool Type Related');

        $grid->setToolbarPrefix('side');
        $grid->addEditToolbar();

        $grid->addColumnKey();
        $grid->setToolbarSearch(true);

        $grid->addColumn('ds_description', 'Description', '100%', $f->retTypeStringAny(), false);

        $javascript = $javascript . $grid->retGridVar();

        $filters = $fm->retFiltersWithGroup();
        $trans = array();
        $trans = $this->cdbhelper->retTranslationDifKeys($trans);


        $send = array("tab" => $tabsc,
            "javascript" => $javascript,
            "filters" => $filters,
            "filters_java" => $fm->retJavascript()); //+ $label;

        $this->load->view("tti/project_product_view", $send);
    }

    public function retrieveGridJsonPowerType($cd_division_brand, $mode = 'B', $json = true) {
        echo ($this->mainmodel->retGridJsonPowerType($cd_division_brand, $mode, $json, true));
    }

}
