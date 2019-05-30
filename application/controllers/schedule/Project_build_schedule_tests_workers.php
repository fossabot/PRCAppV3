<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class project_build_schedule_tests_workers extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("schedule/project_build_schedule_tests_workers_model", "mainmodel", TRUE);
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

        $fm->addPickListFilter('Project Build Schedule Tests', 'filter_1', 'schedule/project_build_schedule_tests', '"PROJECT_BUILD_SCHEDULE_TESTS_WORKERS".cd_project_build_schedule_tests');



        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("schedule/project_build_schedule_tests_workers");

        $grid->addColumnKey();

        $grid->addColumn('ds_project_build_schedule_tests', 'Project Build Schedule Tests', '150px', $f->retTypePickList(), array('model' => 'schedule/project_build_schedule_tests_model', 'codeField' => 'cd_project_build_schedule_tests'));
        $grid->addColumn('dt_start', 'Start', '80px', $f->retTypeDate(), true);
        $grid->addColumn('dt_finish', 'Finish', '80px', $f->retTypeDate(), true);
        $grid->addColumn('nr_workers', 'Workers', '150px', $f->retTypeInteger(), true);


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
