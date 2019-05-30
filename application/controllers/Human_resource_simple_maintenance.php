<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class human_resource_simple_maintenance extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("human_resource_model", "mainmodel", TRUE);
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

        $fm->addSimpleFilterUpper('Name', 'filter_1', '"HUMAN_RESOURCE".ds_human_resource_full');
        $fm->addFilterNumber('Staff Number', 'filter_2', '"HUMAN_RESOURCE".nr_staff_number', '10.0', '', '');
        $fm->addPickListFilter('Title', 'filter_3', 'human_resource_title', 'cd_human_resource_title');
        $fm->addFilterYesNo("Active", "dt_deactivated", '', "Y");
        


        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("human_resource_simple_maintenance");

        $grid->addColumnKey();

        $grid->addColumn('ds_human_resource_full', 'Full Name', '100%', $f->retTypeStringAny(), array('limit' => '128'));
        $grid->addColumn('ds_e_mail', 'Email', '150px', $f->retTypeStringAny(), array('limit' => '128'));
        $grid->addColumn('nr_staff_number', 'Staff Number', '150px', $f->retTypeInteger(), true);
        $grid->addColumn('ds_human_resource_title', 'Title', '150px', $f->retTypePickList(), array('model' => 'human_resource_title_model', 'codeField' => 'cd_human_resource_title' ) );
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
