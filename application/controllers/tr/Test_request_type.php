<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class test_request_type extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("tr/test_request_type_model", "mainmodel", TRUE);
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

        $fm->addSimpleFilterUpper('Description', 'filter_1', '"TEST_REQUEST_TYPE".ds_test_request_type');
        $fm->addFilterYesNo("Active", "dt_deactivated", "", "Y");

        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("TR/test_request_type");

        $grid->addColumnKey();

        $grid->addColumn('ds_test_request_type', 'Description', '100%', $f->retTypeStringUpper(), array('limit' => '64'));
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
