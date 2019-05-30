<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class rfq_item_supplier_sample_request extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("rfq/rfq_item_supplier_sample_request_model", "mainmodel", TRUE);
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

        $fm->addPickListFilter('Rfq Item Supplier', 'filter_1', 'rfq/rfq_item_supplier', '"RFQ_ITEM_SUPPLIER_SAMPLE_REQUEST".cd_rfq_item_supplier');
        $fm->addPickListFilter('Human Resource Request', 'filter_3', 'human_resource', '"RFQ_ITEM_SUPPLIER_SAMPLE_REQUEST".cd_human_resource_request');
        $fm->addPickListFilter('Human Resource Received', 'filter_6', 'human_resource', '"RFQ_ITEM_SUPPLIER_SAMPLE_REQUEST".cd_human_resource_received');
        $fm->addPickListFilter('Approval Status', 'filter_8', 'approval_status', '"RFQ_ITEM_SUPPLIER_SAMPLE_REQUEST".cd_approval_status');



        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("rfq/rfq_item_supplier_sample_request");

        $grid->addColumnKey();

        $grid->addColumn('ds_rfq_item_supplier', 'Rfq Item Supplier', '150px', $f->retTypePickList(), array('model' => 'rfq/rfq_item_supplier_model', 'codeField' => 'cd_rfq_item_supplier'));
        $grid->addColumn('dt_requested', 'Requested', '80px', $f->retTypeDate(), true);
        $grid->addColumn('ds_human_resource_request', 'Human Resource Request', '150px', $f->retTypePickList(), array('model' => 'human_resource_model', 'codeField' => 'cd_human_resource_request'));
        $grid->addColumn('dt_deadline_to_receive', 'Deadline To Receive', '80px', $f->retTypeDate(), true);
        $grid->addColumn('nr_quantity', 'Quantity', '150px', $f->retTypeNum(), array('precision' => '2', 'readonly' => false));
        $grid->addColumn('ds_human_resource_received', 'Human Resource Received', '150px', $f->retTypePickList(), array('model' => 'human_resource_model', 'codeField' => 'cd_human_resource_received'));
        $grid->addColumn('dt_received', 'Received', '80px', $f->retTypeDate(), true);
        $grid->addColumn('ds_approval_status', 'Approval Status', '150px', $f->retTypePickList(), array('model' => 'approval_status_model', 'codeField' => 'cd_approval_status'));
        $grid->addColumn('ds_human_resource_approval', 'Human Resource Request', '150px', $f->retTypePickList(), array('model' => 'human_resource_model', 'codeField' => 'cd_human_resource_request'));


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
