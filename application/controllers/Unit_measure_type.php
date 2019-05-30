<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class unit_measure_type extends controllerBasicExtend
{

    var $arrayIns;
    var $fields;

    function __construct()
    {
        parent::__construct();
        $this->load->model("unit_measure_type_model", "mainmodel", TRUE);
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

        $fm->addSimpleFilterUpper('Unit Measure Type', 'filter_1', '"UNIT_MEASURE_TYPE".ds_unit_measure_type');
        $fm->addPickListFilter('Unit Measure Reference', 'filter_3', 'unit_measure', '"UNIT_MEASURE_TYPE".cd_unit_measure_reference');


        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("unit_measure_type");

        $grid->addColumnKey();

        $grid->addColumn('ds_unit_measure_type', 'Unit Measure Type', '150px', $f->retTypeStringAny(), array('limit' => '64'));
        $grid->addColumn('fl_is_length', 'Is Length', '150px', $f->retTypeCheckBox(), true);
        $grid->addColumn('ds_unit_measure_reference', 'Unit Measure Reference', '150px', $f->retTypePickList(), array('model' => 'unit_measure_model', 'codeField' => 'cd_unit_measure_reference'));


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