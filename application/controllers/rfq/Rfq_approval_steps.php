<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class rfq_approval_steps extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("rfq/rfq_approval_steps_model", "mainmodel", TRUE);
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

        $fm->addPickListFilter('Rfq', 'filter_1', 'rfq/rfq', '"RFQ_APPROVAL_STEPS".cd_rfq');
        $fm->addPickListFilter('Rfq Approval Steps Config', 'filter_2', 'rfq/rfq_approval_steps_config', '"RFQ_APPROVAL_STEPS".cd_rfq_approval_steps_config');
        $fm->addPickListFilter('Human Resource Define', 'filter_5', 'human_resource', '"RFQ_APPROVAL_STEPS".cd_human_resource_define');
        $fm->addSimpleFilterUpper('Remakrs', 'filter_7', '"RFQ_APPROVAL_STEPS".ds_remakrs');



        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("rfq/rfq_approval_steps");

        $grid->addColumnKey();

        $grid->addColumn('ds_rfq', 'Rfq', '150px', $f->retTypePickList(), array('model' => 'rfq/rfq_model', 'codeField' => 'cd_rfq'));
        $grid->addColumn('ds_rfq_approval_steps_config', 'Rfq Approval Steps Config', '150px', $f->retTypePickList(), array('model' => 'rfq/rfq_approval_steps_config_model', 'codeField' => 'cd_rfq_approval_steps_config'));
        $grid->addColumn('cd_approval_status', 'Approval Status', '150px', $f->retTypeInteger(), true);
        $grid->addColumn('dt_define', 'Define', '80px', $f->retTypeDate(), true);
        $grid->addColumn('ds_human_resource_define', 'Human Resource Define', '150px', $f->retTypePickList(), array('model' => 'human_resource_model', 'codeField' => 'cd_human_resource_define'));
        $grid->addColumn('ds_remakrs', 'Remakrs', '150px', $f->retTypeStringAny(), array('limit' => ''));


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
