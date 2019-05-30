<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class project_build extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("schedule/project_build_model", "mainmodel", TRUE);
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

        $fm->addSimpleFilterUpper('Build', 'filter_1', '"PROJECT_BUILD".ds_project_build');
        $fm->addSimpleFilterUpper('Abbreviation', 'filter_2', '"PROJECT_BUILD".ds_project_build_abbreviation');

        $fm->addFilterYesNo("Active", "dt_deactivated", "dt_deactivated", "Y");


        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("schedule/project_build");

        $grid->addColumnKey();

        $grid->addColumn('ds_project_build', 'Project Build', '100%', $f->retTypeStringUpper(), array('limit' => '64'));
        $grid->addColumn('ds_project_build_abbreviation', 'Abbreviation', '100px', $f->retTypeStringUpper(), array('limit' => '10'));
        $grid->addColumn('ds_tr_build_prefix', 'Build Prefix on TR', '120px', $f->retTypeStringAny(), true);
        
        $grid->addColumn('ds_comment', 'Comment', '150px', $f->retTypeTextPL(), array('limit' => '6000'));
        $grid->addColumn('fl_by_model', 'By Model', '80px', $f->retTypeCheckBox(), true);
        $grid->addColumn('fl_allow_multiples', 'Allow Multi', '80px', $f->retTypeCheckBox(), true);
        $grid->addColumn('fl_has_checkpoints', 'Checklist', '80px', $f->retTypeCheckBox(), true);
        $grid->addColumn('fl_has_tests', 'Has Tests', '80px', $f->retTypeCheckBox(), true);
        

        $grid->addColumn('nr_order', 'Order', '80px', $f->retTypeInteger(), true);


        
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
