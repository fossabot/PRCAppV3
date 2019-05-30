<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class project_tool_type extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("tti/project_tool_type_model", "mainmodel", TRUE);
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

        $fm->addSimpleFilterUpper('Tool Type', 'filter_1', '"PROJECT_TOOL_TYPE".ds_project_tool_type');
        $fm->addFilterYesNo("Active", "dt_deactivated", "", "Y");



        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("tti/project_tool_type");

        $grid->addColumnKey();

        $grid->addColumn('ds_project_tool_type', 'Project Tool Type', '100%', $f->retTypeStringUpper(), array('limit' => '64'));
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

    public function retrieveGridJsonPowerType($cd_product, $mode = 'B', $json = true) {
        echo ($this->mainmodel->retGridJsonPowerType($cd_product, $mode, $json, true));
    }

}
