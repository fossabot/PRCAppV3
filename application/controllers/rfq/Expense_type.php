<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class expense_type extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("rfq/expense_type_model", "mainmodel", TRUE);
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

        $fm->addSimpleFilterUpper('Description', 'filter_1', '"EXPENSE_TYPE".ds_expense_type');
        $fm->addSimpleFilterUpper('Expense Type ID', 'filter_2', '"EXPENSE_TYPE".ds_workflow_id');
        $fm->addPickListFilter('Expense Type', 'filter_6', 'rfq/expense_type', '"DEPARTMENT_COST_CENTER_ACCOUNT_CODE".cd_expense_type');
        $fm->addFilterYesNo("Active", "dt_deactivated", "", "Y");



        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("rfq/expense_type");

        $grid->addColumnKey();

        $grid->addColumn('ds_expense_type', 'Description', '100%', $f->retTypeStringAny(), array('limit' => ''));
        $grid->addColumn('ds_workflow_id', 'Expense Type ID', '150px', $f->retTypeStringAny(), array('limit' => ''));
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
