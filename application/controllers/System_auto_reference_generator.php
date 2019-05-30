<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class system_auto_reference_generator extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("system_auto_reference_generator_model", "mainmodel", TRUE);
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

        $fm->addFilterYesNo("Active", "dt_deactivated", "", "Y");



        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("system_auto_reference_generator");

        $grid->addColumnKey();

        $grid->addColumn('ds_system_auto_reference_generator', 'System Auto Reference Generator', '150px', $f->retTypeTextPL(), true);
        $grid->addColumn('ds_table', 'Table', '150px', $f->retTypeTextPL(), true);
        $grid->addColumn('fl_use_generator', 'Use Generator', '150px', $f->retTypeCheckBox(), true);
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
