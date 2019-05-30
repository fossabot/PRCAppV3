<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class equipment_design_sub_category extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("rfq/equipment_design_sub_category_model", "mainmodel", TRUE);
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

        $fm->addSimpleFilterUpper('Description', 'filter_1', '"EQUIPMENT_DESIGN_SUB_CATEGORY".ds_equipment_design_sub_category');
        $fm->addPickListFilter('Design Category', 'filter_2', 'rfq/equipment_design_category', '"EQUIPMENT_DESIGN_SUB_CATEGORY".cd_equipment_design_category');
        $fm->addSimpleFilterUpper('Code', 'filter_3', '"EQUIPMENT_DESIGN_SUB_CATEGORY".ds_name_code');
        $fm->addFilterYesNo("Active", 'p_deac',  '"EQUIPMENT_DESIGN_SUB_CATEGORY".dt_deactivated', "Y");


        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("rfq/equipment_design_sub_category");

        $grid->addColumnKey();

        $grid->addColumn('ds_equipment_design_sub_category', 'Description', '100%', $f->retTypeStringAny(), array('limit' => '64'));
        $grid->addColumn('ds_equipment_design_category', 'Design Category', '100%', $f->retTypePickList(), array('model' => 'rfq/equipment_design_category_model', 'codeField' => 'cd_equipment_design_category'));
        $grid->addColumn('ds_name_code', 'Code', '100%', $f->retTypeStringAny(), array('limit' => '32'));
        $grid->addColumnDeactivated(true);


        $filters = $fm->retFiltersWithGroup();
        $javascript = $grid->retGrid();


        $trans = array();
        $trans = $this->cdbhelper->retTranslationDifKeys($trans);


        $send = array("javascript" => $javascript,
            "filters" => $filters,
            "filters_java" => $fm->retJavascript()) + $trans;


        $this->load->view("defaultView", $send);
    }

    function openPL() {

        $id = $_POST['id'];
        
        $controller = 'rfq/equipment_design';

        $fl_rfq_only_see_equipment_warehouse = $this->getCdbhelper()->getUserPermission('fl_rfq_only_see_equipment_warehouse');

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
        $grid->setGridName('specificPLSup');
        $grid->setCRUDController($controller);
        $grid->setSingleBarControl(true);

        //$grid->addRetriveToolbar();
        //$grid->addBreakToolbar();

        if ($controller !== '') {
            $grid->addUserBtnToolbar('openMaint', 'Open Maintenance', 'fa fa-external-link');
        }
        $grid->addBreakToolbar();



        $grid->setToolbarSearch(true);

        $grid->addColumnKey();

        $grid->addColumn('ds_equipment_design_type', 'Type', '100%', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_equipment_design_category', 'Category', '100%', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_equipment_design_sub_category', 'Sub Category', '100%', $f->retTypeStringAny(), false);

        $sql = " WHERE \"EQUIPMENT_DESIGN_SUB_CATEGORY\".dt_deactivated IS NULL AND \"EQUIPMENT_DESIGN_CATEGORY\".dt_deactivated IS NULL AND \"EQUIPMENT_DESIGN_TYPE\".dt_deactivated IS NULL";

        if ($fl_rfq_only_see_equipment_warehouse == 'Y') {
            $sql= $sql. '  AND  "EQUIPMENT_DESIGN_TYPE".cd_equipment_design_type = 4 ';
        }

        $data = $this->mainmodel->retRetrieveGridJson($sql, "ORDER BY ds_equipment_design_type, ds_equipment_design_category, ds_equipment_design_sub_category", "", $this->mainmodel->retrOptionsPL);

        $grid->addRecords($data);
        $javascript = $grid->retGridVar();

        $labels = array('title' => 'Equipment');
        $labels = $this->cdbhelper->retTranslationDifKeys($labels);

        $send = array("javascript" => $javascript,
            "filters" => $filters,
            "filters_java" => $fm->retJavascript(),
            "keyColumn" => 'cd_equipment_design_sub_category',
            "descColumn" => 'ds_equipment_design_sub_category',
            'retrieveFields' => json_encode($fm->getFilterNames()),
            'controller' => $controller,
            'id' => $id 
        );


        $this->load->view("rfq/equipment_design_sub_category_pl_view", $send + $labels);
    }

    public function retPlWherePar1($par1) {
        return " AND cd_equipment_design_category = $par1 ";
    }
    
}
