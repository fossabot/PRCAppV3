<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class tr_wi_data extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("tr/tr_wi_data_model", "mainmodel", TRUE);
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

        $fm->addSimpleFilterUpper('Test Procedure Name', 'filter_1', '"TR_WI_DATA".ds_test_procedure_name');
        $fm->addSimpleFilterUpper('Goal Units', 'filter_2', '"TR_WI_DATA".ds_goal_units');
        $fm->addSimpleFilterUpper('Responsiblity', 'filter_4', '"TR_WI_DATA".ds_responsiblity');



        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("tr/tr_wi_data");

        $grid->addColumnKey();

        $grid->addColumn('ds_test_procedure_name', 'Test Procedure Name', '150px', $f->retTypeStringAny(), array('limit' => ''));
        $grid->addColumn('ds_goal_units', 'Goal Units', '150px', $f->retTypeStringAny(), array('limit' => ''));
        $grid->addColumn('ds_responsiblity', 'Responsiblity', '150px', $f->retTypeStringAny(), array('limit' => ''));
        $grid->addColumn('dt_update', 'Update', '80px', $f->retTypeDate(), true);


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
