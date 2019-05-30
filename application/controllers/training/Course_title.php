<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class course_title extends controllerBasicExtend
{

    var $arrayIns;
    var $fields;

    function __construct()
    {
        parent::__construct();
        $this->load->model("training/course_title_model", "mainmodel", TRUE);
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

        $fm->addPickListFilter('Course', 'filter_1', 'training/course', '"COURSE_TITLE".cd_course');
        $fm->addPickListFilter('Human Resource Title', 'filter_2', 'human_resource_title', '"COURSE_TITLE".cd_human_resource_title');
        $fm->addFilterYesNo("Active", "dt_deactivated", "", "Y");


        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("training/course_title");

        $grid->addColumnKey();

        $grid->addColumn('ds_course', 'Course', '150px', $f->retTypePickList(), array('model' => 'training/course_model', 'codeField' => 'cd_course'));
        $grid->addColumn('ds_human_resource_title', 'Human Resource Title', '150px', $f->retTypePickList(), array('model' => 'human_resource_title_model', 'codeField' => 'cd_human_resource_title'));
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