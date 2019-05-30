<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class course_location extends controllerBasicExtend
{

    var $arrayIns;
    var $fields;

    function __construct()
    {
        parent::__construct();
        $this->load->model("training/course_location_model", "mainmodel", TRUE);
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

        $fm->addSimpleFilterUpper('Location', 'filter_1', '"COURSE_LOCATION".ds_course_location');
        $fm->addFilterYesNo("Active", "dt_deactivated", "", "Y");


        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("training/course_location");

        $grid->addColumnKey();

        $grid->addColumn('ds_course_location', 'Location', '100%', $f->retTypeStringAny(), array('limit' => '64'));
        $grid->addColumn('nr_seats', 'Seats', '100%', $f->retTypeInteger(), true);
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