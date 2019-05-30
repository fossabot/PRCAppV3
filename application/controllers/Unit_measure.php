<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class unit_measure extends controllerBasicExtend
{

    var $arrayIns;
    var $fields;

    function __construct()
    {
        parent::__construct();
        $this->load->model("unit_measure_model", "mainmodel", TRUE);
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

        $fm->addSimpleFilterUpper('Unit Measure', 'filter_1', '"UNIT_MEASURE".ds_unit_measure');
        $fm->addPickListFilter('Unit Measure Type', 'filter_2', 'unit_measure_type', '"UNIT_MEASURE".cd_unit_measure_type');
        $fm->addSimpleFilterUpper('Unit Measure Short', 'filter_3', '"UNIT_MEASURE".ds_unit_measure_short');
        $fm->addSimpleFilterUpper('Unit Measure Symbol', 'filter_4', '"UNIT_MEASURE".ds_unit_measure_symbol');
        $fm->addPickListFilter('Unit Measure Lenght Base', 'filter_6', 'unit_measure', '"UNIT_MEASURE".cd_unit_measure_lenght_base');


        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("unit_measure");

        $grid->addColumnKey();

        $grid->addColumn('ds_unit_measure', 'Unit Measure', '150px', $f->retTypeStringAny(), array('limit' => '64'));
        $grid->addColumn('ds_unit_measure_type', 'Unit Measure Type', '150px', $f->retTypePickList(), array('model' => 'unit_measure_type_model', 'codeField' => 'cd_unit_measure_type'));
        $grid->addColumn('ds_unit_measure_short', 'Unit Measure Short', '150px', $f->retTypeStringAny(), array('limit' => '8'));
        $grid->addColumn('ds_unit_measure_symbol', 'Unit Measure Symbol', '150px', $f->retTypeStringAny(), array('limit' => '8'));
        $grid->addColumn('nr_factor_for_convertion', 'Factor For Convertion', '150px', $f->retTypeNum(), array('precision' => '8', 'readonly' => false));
        $grid->addColumn('ds_unit_measure_lenght_base', 'Unit Measure Lenght Base', '150px', $f->retTypePickList(), array('model' => 'unit_measure_model', 'codeField' => 'cd_unit_measure_lenght_base'));


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