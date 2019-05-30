<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class rfq_pr_incoming_outcoming_type extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("rfq/rfq_pr_incoming_outcoming_type_model", "mainmodel", TRUE);
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

        $fm->addSimpleFilterUpper('Description', 'filter_1', '"RFQ_PR_INCOMING_OUTCOMING_TYPE".ds_rfq_pr_incoming_outcoming_type');
        $fm->addSimpleFilterUpper('Type', 'filter_2', '"RFQ_PR_INCOMING_OUTCOMING_TYPE".ds_type');
        $fm->addFilterYesNo("Active", "dt_deactivated", "", "Y");



        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("rfq/rfq_pr_incoming_outcoming_type");

        $grid->addColumnKey();

        $grid->addColumn('ds_rfq_pr_incoming_outcoming_type', 'Description', '150px', $f->retTypeStringAny(), array('limit' => '') );
        $grid->addColumn('ds_type', 'Type', '150px', $f->retTypeStringAny(), array('limit' => '') );
        $grid->addColumn('fl_add_to_inventory', 'Add To Inventory', '150px', $f->retTypeCheckBox(), true );
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