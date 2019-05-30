<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class project_build_tests extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("schedule/project_build_tests_model", "mainmodel", TRUE);
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

        $fm->addPickListFilter('Project Build Schedule', 'filter_1', 'schedule/project_build_schedule', '"PROJECT_BUILD_TESTS".cd_project_build_schedule');
        $fm->addPickListFilter('Test Type', 'filter_2', 'tr/test_type', '"PROJECT_BUILD_TESTS".cd_test_type');
        $fm->addPickListFilter('Tests', 'filter_3', 'tr/tests', '"PROJECT_BUILD_TESTS".cd_tests');
        $fm->addPickListFilter('Test Unit', 'filter_4', 'tr/test_unit', '"PROJECT_BUILD_TESTS".cd_test_unit');

        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("schedule/project_build_tests");

        $grid->addColumnKey();

        $grid->addColumn('ds_project_build_schedule', 'Project Build Schedule', '150px', $f->retTypePickList(), array('model' => 'schedule/project_build_schedule_model', 'codeField' => 'cd_project_build_schedule'));
        $grid->addColumn('ds_test_type', 'Test Type', '150px', $f->retTypePickList(), array('model' => 'tr/test_type_model', 'codeField' => 'cd_test_type'));
        $grid->addColumn('ds_tests', 'Tests', '150px', $f->retTypePickList(), array('model' => 'tr/tests_model', 'codeField' => 'cd_tests'));
        $grid->addColumn('ds_test_unit', 'Test Unit', '150px', $f->retTypePickList(), array('model' => 'tr/test_unit_model', 'codeField' => 'cd_test_unit'));
        $grid->addColumn('nr_sample_quantity', 'Sample Quantity', '150px', $f->retTypeNum(), array('precision' => '1', 'readonly' => false));
        $grid->addColumn('nr_goal', 'Goal', '150px', $f->retTypeNum(), array('precision' => '1', 'readonly' => false));
        $grid->addColumn('nr_output', 'Daily Output', '150px', $f->retTypeNum(), array('precision' => '1', 'readonly' => false));


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
