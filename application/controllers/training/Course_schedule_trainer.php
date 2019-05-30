<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class course_schedule_trainer extends controllerBasicExtend
{

    var $arrayIns;
    var $fields;

    function __construct()
    {
        parent::__construct();
        $this->load->model("training/course_schedule_trainer_model", "mainmodel", TRUE);
    }

    public function index()
    {

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

        $fm->addPickListFilter('Trainer', 'filter_1', 'human_resource', '"COURSE_SCHEDULE_TRAINER".cd_human_resource');
        $fm->addPickListFilter('Course Schedule', 'filter_3', 'training/course_schedule', '"COURSE_SCHEDULE_TRAINER".cd_course_schedule');
        $fm->addFilterYesNo("Active", "dt_deactivated", "", "Y");


        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("training/course_schedule_trainer");

        $grid->addColumnKey();

        $grid->addColumn('ds_human_resource', 'Trainer', '100%', $f->retTypePickList(), array('model' => 'human_resource_model', 'codeField' => 'cd_human_resource'));
        $grid->addColumn('ds_course_schedule', 'Course Schedule', '100%', $f->retTypePickList(), array('model' => 'training/course_schedule_model', 'codeField' => 'cd_course_schedule'));
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