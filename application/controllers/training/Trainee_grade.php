<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class trainee_grade extends controllerBasicExtend
{

    var $arrayIns;
    var $fields;

    function __construct()
    {
        parent::__construct();
        $this->load->model("training/trainee_grade_model", "mainmodel", TRUE);
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

        $fm->addPickListFilter('Trainee', 'filter_2', 'human_resource', '"TRAINEE_GRADE".cd_human_resource_trainee');
        $fm->addPickListFilter('Testing Result', 'filter_3', 'training/course_testing_result', '"TRAINEE_GRADE".cd_course_testing_result');
        $fm->addSimpleFilterUpper('Remark', 'filter_6', '"TRAINEE_GRADE".ds_remark');
        $fm->addPickListFilter('Recorder', 'filter_7', 'human_resource', '"TRAINEE_GRADE".cd_human_resource_recorder');
        $fm->addPickListFilter('Course Attend Confirmation', 'filter_8', 'training/course_attend_confirmation', '"TRAINEE_GRADE".cd_course_attend_confirmation');
        $fm->addFilterYesNo("Active", "dt_deactivated", "", "Y");


        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("training/trainee_grade");

        $grid->addColumnKey();

        $grid->addColumn('cd_course_schedule', 'Course Schedule', '100%', $f->retTypeInteger(), true);
        $grid->addColumn('ds_human_resource_trainee', 'Trainee', '100%', $f->retTypePickList(), array('model' => 'human_resource_model', 'codeField' => 'cd_human_resource_trainee'));
        $grid->addColumn('ds_course_testing_result', 'Course Testing Result', '100%', $f->retTypePickList(), array('model' => 'training/course_testing_result_model', 'codeField' => 'cd_course_testing_result'));
        $grid->addColumn('ds_remark', 'Remark', '100%', $f->retTypeStringAny(), array('limit' => ''));
        $grid->addColumn('ds_human_resource_recorder', 'Recorder', '100%', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_course_attend_confirmation', 'Course Attend Confirmation', '150px', $f->retTypePickList(), array('model' => 'training/course_attend_confirmation_model', 'codeField' => 'cd_course_attend_confirmation' ) );
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