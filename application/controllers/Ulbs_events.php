<?php
if (!defined("BASEPATH")) exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class ulbs_events extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("ulbs_events_model", "mainmodel", TRUE);
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
        $fm->addSimpleFilterUpper('Xture Id', 'filter_1', '"ULBS_EVENTS".dsxture_id');
        $fm->addSimpleFilterUpper('Rkorder Number', 'filter_2', '"ULBS_EVENTS".dsrkorder_number');
        $fm->addSimpleFilterUpper('Ol Number', 'filter_3', '"ULBS_EVENTS".dsol_number');
        $fm->addSimpleFilterUpper('Ssage Type', 'filter_4', '"ULBS_EVENTS".dsssage_type');
        $fm->addSimpleFilterUpper('Ssage', 'filter_5', '"ULBS_EVENTS".dsssage');
        $fm->addSimpleFilterUpper('Date Time', 'filter_6', '"ULBS_EVENTS".dsdate_time');
        $fm->addSimpleFilterUpper('Mark', 'filter_7', '"ULBS_EVENTS".dsmark');

        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("ulbs_events");

        $grid->addColumnKey();

        $grid->addColumn('fixture_id', 'Xture Id', '150px', $f->retTypeStringAny(), array('limit' => '10'));
        $grid->addColumn('workorder_number', 'Rkorder Number', '150px', $f->retTypeStringAny(), array('limit' => '25'));
        $grid->addColumn('tool_number', 'Ol Number', '150px', $f->retTypeStringAny(), array('limit' => '10'));
        $grid->addColumn('message_type', 'Ssage Type', '150px', $f->retTypeStringAny(), array('limit' => '45'));
        $grid->addColumn('message', 'Ssage', '150px', $f->retTypeStringAny(), array('limit' => '120'));
        $grid->addColumn('update_time', 'Date Time', '150px', $f->retTypeStringAny(), array('limit' => '45'));
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