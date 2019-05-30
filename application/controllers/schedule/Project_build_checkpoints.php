<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class project_build_checkpoints extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("schedule/project_build_checkpoints_model", "mainmodel", TRUE);
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

        $fm->addSimpleFilterUpper('Description', 'filter_1', '"PROJECT_BUILD_CHECKPOINTS".ds_project_build_checkpoints');
        $fm->addFilterYesNo("Active", "dt_deactivated", "", "Y");



        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("schedule/project_build_checkpoints");

        $grid->addColumnKey();

        $grid->addColumn('ds_project_build_checkpoints', 'Description', '100%', $f->retTypeStringUpper(), array('limit' => '64'));
        $grid->addColumn('ds_comment', 'Comment', '200px', $f->retTypeTextPL(), true);
        $grid->addColumn('nr_order', 'Order', '80px', $f->retTypeInteger(), true);
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
