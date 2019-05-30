<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class general_project_expense extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("rfq/general_project_expense_model", "mainmodel", TRUE);
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

        $fm->addSimpleFilterUpper('Description', 'filter_1', '"GENERAL_PROJECT_EXPENSE".ds_general_project_expense');
        $fm->addSimpleFilterUpper('General Project #', 'filter_3', '"GENERAL_PROJECT_EXPENSE".ds_general_project_number');
        $fm->addSimpleFilterUpper('General Project Model #', 'filter_4', '"GENERAL_PROJECT_EXPENSE".ds_general_project_model_number');
        $fm->addFilterYesNo("Active", "dt_deactivated", "", "Y");



        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("rfq/general_project_expense");

        $grid->addColumnKey();

        $grid->addColumn('ds_general_project_expense', 'Description', '180px', $f->retTypeStringAny(), array('limit' => ''));
        $grid->addColumn('ds_general_project_number', 'General Project #', '180px', $f->retTypeStringAny(), array('limit' => ''));
        $grid->addColumn('ds_general_project_model_number', 'General Project Model #', '180px', $f->retTypeStringAny(), array('limit' => ''));
        $grid->addColumn('dt_amount_raised', 'Raised On', '80px', $f->retTypeDate(), true);
        $grid->addColumn('nr_total_raised_usd', 'Total Raised USD', '180px', $f->retTypeNum(), array('precision' => '2', 'readonly' => false));
        $grid->addColumn('nr_balance', 'Balance', '180px', $f->retTypeNum(), array('precision' => '2', 'readonly' => true));

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
