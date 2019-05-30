<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class equipment_design_category extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("rfq/equipment_design_category_model", "mainmodel", TRUE);
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

        $fm->addSimpleFilterUpper('Design Category', 'filter_1', '"EQUIPMENT_DESIGN_CATEGORY".ds_equipment_design_category');
        $fm->addPickListFilter('Design Type', 'filter_2', 'rfq/equipment_design_type', '"EQUIPMENT_DESIGN_CATEGORY".cd_equipment_design_type');
        $fm->addSimpleFilterUpper('Code', 'filter_3', '"EQUIPMENT_DESIGN_CATEGORY".ds_name_code');
        $fm->addFilterYesNo("Active", "dt_deactivated",  '"EQUIPMENT_DESIGN_CATEGORY".dt_deactivated', "Y");



        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("rfq/equipment_design_category");

        $grid->addColumnKey();

        $grid->addColumn('ds_equipment_design_category', 'Design Category', '100%', $f->retTypeStringAny(), array('limit' => '64'));
        $grid->addColumn('ds_equipment_design_type', 'Design Type', '100%', $f->retTypePickList(), array('model' => 'rfq/equipment_design_type_model', 'codeField' => 'cd_equipment_design_type'));
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
    
    public function retPlWherePar1($par1) {
        return " AND cd_equipment_design_type = $par1 ";
    }

}
