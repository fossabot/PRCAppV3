<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class project_build_schedule_checkpoints extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("schedule/project_build_schedule_checkpoints_model", "mainmodel", TRUE);
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

        $fm->addPickListFilter('Project Build Schedule', 'filter_1', 'schedule/project_build_schedule', '"PROJECT_BUILD_SCHEDULE_CHECKPOINTS".cd_project_build_schedule');
        $fm->addPickListFilter('Project Build Checkpoints', 'filter_2', 'schedule/project_build_checkpoints', '"PROJECT_BUILD_SCHEDULE_CHECKPOINTS".cd_project_build_checkpoints');
        $fm->addSimpleFilterUpper('Comment', 'filter_5', '"PROJECT_BUILD_SCHEDULE_CHECKPOINTS".ds_comment');



        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("schedule/project_build_schedule_checkpoints");

        $grid->addColumnKey();

        $grid->addColumn('ds_project_build_schedule', 'Project Build Schedule', '150px', $f->retTypePickList(), array('model' => 'schedule/project_build_schedule_model', 'codeField' => 'cd_project_build_schedule'));
        $grid->addColumn('ds_project_build_checkpoints', 'Project Build Checkpoints', '150px', $f->retTypePickList(), array('model' => 'schedule/project_build_checkpoints_model', 'codeField' => 'cd_project_build_checkpoints'));
        $grid->addColumn('dt_deadline', 'Deadline', '80px', $f->retTypeDate(), true);
        $grid->addColumn('dt_done', 'Done', '80px', $f->retTypeDate(), true);
        $grid->addColumn('ds_comment', 'Comment', '150px', $f->retTypeStringUpper(), array('limit' => ''));


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
