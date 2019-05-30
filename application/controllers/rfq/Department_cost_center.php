<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class department_cost_center extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("rfq/department_cost_center_model", "mainmodel", TRUE);
        $this->load->model("rfq/rfq_item_model", "itemmodel", TRUE);
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

        $fm->addSimpleFilterUpper('Description', 'filter_1', '"DEPARTMENT_COST_CENTER".ds_department_cost_center');
        $fm->addSimpleFilterUpper('Code', 'filter_4', '"DEPARTMENT_COST_CENTER".ds_department_cost_center_code');
        $fm->addPickListFilter('Department', 'filter_5', 'job_department', '"DEPARTMENT_COST_CENTER".cd_department');
        $fm->addFilterYesNo("Active", "dt_deactivated", "", "Y");


        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("rfq/department_cost_center");

        $grid->addColumnKey();

        $grid->addColumn('ds_department_cost_center', 'Description', '100%', $f->retTypeStringAny(), array('limit' => '128'));
        $grid->addColumn('ds_department_cost_center_code', 'Code', '150px', $f->retTypeStringAny(), array('limit' => '16'));
        $grid->addColumn('ds_department', 'Department', '150px', $f->retTypePickList(), array('model' => 'job_department_model', 'codeField' => 'cd_department'));
        $grid->addColumn('fl_demand_project', 'Demand Project', '100px', $f->retTypeCheckBox(), true);
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
