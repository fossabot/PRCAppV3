<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class mtr_report extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("tr/mtr_report_model", "mainmodel", TRUE);
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
        $fm->addSimpleFilterUpper('File Name', 'filter_1', '"MTR_REPORT".ds_file_name');
        $fm->addSimpleFilterUpper('Approved By', 'filter_2', '"MTR_REPORT".ds_approved_by');

        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("tr/mtr_report");

        $grid->addColumnKey();
        $grid->addColumn('ds_file_name', 'File Name', '150px', $f->retTypeStringAny(), array('limit' => ''));
        $grid->addColumn('ds_approved_by', 'Approved By', '150px', $f->retTypeStringAny(), array('limit' => ''));
        $grid->addColumn('nr_timestamp', 'Timestamp', '150px', $f->retTypeInteger(), true);

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