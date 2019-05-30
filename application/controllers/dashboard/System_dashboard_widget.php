<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class system_dashboard_widget extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("dashboard/system_dashboard_widget_model", "mainmodel", TRUE);
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

        $fm->addSimpleFilterUpper('System Dashboard Widget', 'filter_1', '"SYSTEM_DASHBOARD_WIDGET".ds_system_dashboard_widget');
        $fm->addFilterYesNo("Active", "dt_deactivated", "", "Y");



        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("system_dashboard_widget");

        $grid->addColumnKey();

        $grid->addColumn('ds_system_dashboard_widget', 'System Dashboard Widget', '150px', $f->retTypeStringUpper(), array('limit' => '64'));
        $grid->addColumn('ds_comments', 'Comments', '150px', $f->retTypeTextPL(), true);
        $grid->addColumn('ds_comments_system', 'Comments System', '150px', $f->retTypeTextPL(), true);
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

}
