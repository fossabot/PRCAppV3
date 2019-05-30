<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class system_parameters extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("system_parameters_model", "mainmodel", TRUE);
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

        $fm->addSimpleFilterUpper('System Parameters', 'filter_1', '"SYSTEM_PARAMETERS".ds_system_parameters');
        $fm->addSimpleFilterUpper('System Parameters Id', 'filter_2', '"SYSTEM_PARAMETERS".ds_system_parameters_id');
        $fm->addSimpleFilterUpper('System Parameters Value', 'filter_4', '"SYSTEM_PARAMETERS".ds_system_parameters_value');



        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("system_parameters");

        $grid->addColumnKey();

        $grid->addColumn('ds_system_parameters', 'System Parameters', '150px', $f->retTypeStringUpper(), array('limit' => '64'));
        $grid->addColumn('ds_system_parameters_id', 'System Parameters Id', '150px', $f->retTypeStringUpper(), array('limit' => '32'));
        $grid->addColumn('ds_system_parameters_obs', 'System Parameters Obs', '150px', $f->retTypeTextPL(), true);
        $grid->addColumn('ds_system_parameters_value', 'System Parameters Value', '150px', $f->retTypeStringUpper(), array('limit' => '64'));


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
