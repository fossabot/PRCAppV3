<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class rfq_pr_group_distribution extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("rfq/rfq_pr_group_distribution_model", "mainmodel", TRUE);
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

        $fm->addPickListFilter('Rfq Pr Group', 'filter_1', 'rfq/rfq_pr_group', '"RFQ_PR_GROUP_DISTRIBUTION".cd_rfq_pr_group');
        $fm->addPickListFilter('Rfq Item Supplier Quotation', 'filter_2', 'rfq/rfq_item_supplier_quotation', '"RFQ_PR_GROUP_DISTRIBUTION".cd_rfq_item_supplier_quotation');
        $fm->addPickListFilter('Rfq Item', 'filter_3', 'rfq/rfq_item', '"RFQ_PR_GROUP_DISTRIBUTION".cd_rfq_item');



        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("rfq/rfq_pr_group_distribution");

        $grid->addColumnKey();

        $grid->addColumn('ds_rfq_pr_group', 'Rfq Pr Group', '150px', $f->retTypePickList(), array('model' => 'rfq/rfq_pr_group_model', 'codeField' => 'cd_rfq_pr_group'));
        $grid->addColumn('ds_rfq_item_supplier_quotation', 'Rfq Item Supplier Quotation', '150px', $f->retTypePickList(), array('model' => 'rfq/rfq_item_supplier_quotation_model', 'codeField' => 'cd_rfq_item_supplier_quotation'));
        $grid->addColumn('ds_rfq_item', 'Rfq Item', '150px', $f->retTypePickList(), array('model' => 'rfq/rfq_item_model', 'codeField' => 'cd_rfq_item'));
        $grid->addColumn('nr_qtty_to_charge', 'Qty To Charge', '150px', $f->retTypeNum(), array('precision' => '2', 'readonly' => false));
        $grid->addColumn('nr_total_price', 'Total Price', '150px', $f->retTypeNum(), array('precision' => '2', 'readonly' => false));
        $grid->addColumn('nr_total_price_with_tax', 'Total Price With Tax', '150px', $f->retTypeNum(), array('precision' => '2', 'readonly' => false));
        $grid->addColumn('nr_total_price_rmb', 'Total Price Rmb', '150px', $f->retTypeNum(), array('precision' => '2', 'readonly' => false));
        $grid->addColumn('nr_total_price_rmb_with_tax', 'Total Price Rmb With Tax', '150px', $f->retTypeNum(), array('precision' => '2', 'readonly' => false));


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
