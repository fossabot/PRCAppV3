<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class rfq_pr_incoming_outcoming extends controllerBasicExtend
{

    var $arrayIns;
    var $fields;

    function __construct()
    {
        parent::__construct();
        $this->load->model("rfq/rfq_pr_incoming_outcoming_model", "mainmodel", TRUE);
        $this->load->model("rfq/rfq_pr_group_distribution_model", "rfqprdist", TRUE);
    }

    public function index()
    {

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

        $fm->addPickListFilter('Rfq Pr Group', 'filter_1', 'rfq/rfq_pr_group', '"RFQ_PR_INCOMING_OUTCOMING".cd_rfq_pr_group');
        $fm->addPickListFilter('Rfq Pr Group Distribution', 'filter_2', 'rfq/rfq_pr_group_distribution', '"RFQ_PR_INCOMING_OUTCOMING".cd_rfq_pr_group_distribution');
        $fm->addPickListFilter('Human Resource Receiver', 'filter_5', 'human_resource', '"RFQ_PR_INCOMING_OUTCOMING".cd_human_resource_receiver');
        $fm->addSimpleFilterUpper('Comments', 'filter_6', '"RFQ_PR_INCOMING_OUTCOMING".ds_comments');
        $fm->addPickListFilter('Rfq Pr Incoming Outcoming Type', 'filter_7', 'rfq/rfq_pr_incoming_outcoming_type', '"RFQ_PR_INCOMING_OUTCOMING".cd_rfq_pr_incoming_outcoming_type');


        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("rfq/rfq_pr_incoming_outcoming");

        $grid->addColumnKey();

        $grid->addColumn('ds_rfq_pr_group', 'Rfq Pr Group', '150px', $f->retTypePickList(), array('model' => 'rfq/rfq_pr_group_model', 'codeField' => 'cd_rfq_pr_group'));
        $grid->addColumn('ds_rfq_pr_group_distribution', 'Rfq Pr Group Distribution', '150px', $f->retTypePickList(), array('model' => 'rfq/rfq_pr_group_distribution_model', 'codeField' => 'cd_rfq_pr_group_distribution'));
        $grid->addColumn('dt_action', 'Action', '80px', $f->retTypeDate(), true);
        $grid->addColumn('nr_qty', 'Qty', '150px', $f->retTypeNum(), array('precision' => '1', 'readonly' => false));
        $grid->addColumn('ds_human_resource_receiver', 'Human Resource Receiver', '150px', $f->retTypePickList(), array('model' => 'human_resource_model', 'codeField' => 'cd_human_resource_receiver'));
        $grid->addColumn('ds_comments', 'Comments', '150px', $f->retTypeStringAny(), array('limit' => ''));
        $grid->addColumn('ds_rfq_pr_incoming_outcoming_type', 'Rfq Pr Incoming Outcoming Type', '150px', $f->retTypePickList(), array('model' => 'rfq/rfq_pr_incoming_outcoming_type_model', 'codeField' => 'cd_rfq_pr_incoming_outcoming_type'));


        $filters = $fm->retFiltersWithGroup();
        $javascript = $grid->retGrid();


        $trans = array();
        $trans = $this->cdbhelper->retTranslationDifKeys($trans);


        $send = array("javascript" => $javascript,
                "filters" => $filters,
                "filters_java" => $fm->retJavascript()) + $trans;


        $this->load->view("defaultView", $send);


    }

    public function retrPRData() {


        if (!$this->logincontrol->isProperLogged(false)) {
            echo ( '{"logged": "N", "resultset": [] }' );
            return;
        }

        $retrOpt = $this->rfqprdist->retrOptionsFull;

        $where = $this->getWhereToFilter();


        $jsonMapping = $this->getJsonMappingToFilter();

        if (isset($retrOpt['whereToAdd'])) {
            $where = $where . $retrOpt['whereToAdd'];
        }

        echo ( '{ "logged": "Y", "resultset": ' . $this->rfqprdist->retRetrieveGridJson($where, '', $jsonMapping, $retrOpt) . ' }' );
    }
}