<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class ulbs extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("ulbs_model", "mainmodel", TRUE);
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

        $fm->addSimpleFilterUpper('Mesg', 'filter_1', '"ULBS".ds_mesg');
        $fm->addSimpleFilterUpper('Xture Id', 'filter_2', '"ULBS".dsxture_id');
        $fm->addSimpleFilterUpper('Rkorder Number', 'filter_3', '"ULBS".dsrkorder_number');
        $fm->addSimpleFilterUpper('Ol Number', 'filter_4', '"ULBS".dsol_number');
        $fm->addSimpleFilterUpper('Cle Target', 'filter_5', '"ULBS".dscle_target');
        $fm->addSimpleFilterUpper('Cle Completed', 'filter_6', '"ULBS".dscle_completed');
        $fm->addSimpleFilterUpper('Art Count', 'filter_7', '"ULBS".dsart_count');
        $fm->addSimpleFilterUpper('Op Count', 'filter_8', '"ULBS".dsop_count');
        $fm->addSimpleFilterUpper('St Elapse Time', 'filter_9', '"ULBS".dsst_elapse_time');
        $fm->addSimpleFilterUpper('St Status', 'filter_10', '"ULBS".dsst_status');
        $fm->addSimpleFilterUpper('Gpath Local', 'filter_11', '"ULBS".dsgpath_local');
        $fm->addSimpleFilterUpper('Gpath Remote', 'filter_12', '"ULBS".dsgpath_remote');
        $fm->addSimpleFilterUpper('Mark', 'filter_14', '"ULBS".dsmark');

        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("ulbs");

        $grid->addColumnKey();

        $grid->addColumn('tr_mesg', 'Mesg', '150px', $f->retTypeStringAny(), array('limit' => '25'));
        $grid->addColumn('fixture_id', 'Xture Id', '150px', $f->retTypeStringAny(), array('limit' => '25'));
        $grid->addColumn('workorder_number', 'Rkorder Number', '150px', $f->retTypeStringAny(), array('limit' => '45'));
        $grid->addColumn('tool_number', 'Ol Number', '150px', $f->retTypeStringAny(), array('limit' => '45'));
        $grid->addColumn('cycle_target', 'Cle Target', '150px', $f->retTypeStringAny(), array('limit' => '45'));
        $grid->addColumn('cycle_completed', 'Cle Completed', '150px', $f->retTypeStringAny(), array('limit' => '45'));
        $grid->addColumn('start_count', 'Art Count', '150px', $f->retTypeStringAny(), array('limit' => '45'));
        $grid->addColumn('stop_count', 'Op Count', '150px', $f->retTypeStringAny(), array('limit' => '45'));
        $grid->addColumn('test_elapse_time', 'St Elapse Time', '150px', $f->retTypeStringAny(), array('limit' => '45'));
        $grid->addColumn('test_status', 'St Status', '150px', $f->retTypeStringAny(), array('limit' => '45'));
        $grid->addColumn('logpath_local', 'Gpath Local', '150px', $f->retTypeStringAny(), array('limit' => '100'));
        $grid->addColumn('logpath_remote', 'Gpath Remote', '150px', $f->retTypeStringAny(), array('limit' => '100'));
        $grid->addColumn('remark', 'Mark', '150px', $f->retTypeStringAny(), array('limit' => '45'));

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