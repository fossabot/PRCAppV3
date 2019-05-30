<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class product_document_repository_type extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("docrep/product_document_repository_type_model", "mainmodel", TRUE);
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

        $fm->addSimpleFilterUpper('Product Document Repository Type', 'filter_1', '"PRODUCT_DOCUMENT_REPOSITORY_TYPE".ds_product_document_repository_type');
        $fm->addFilterYesNo("Active", "dt_deactivated", "", "Y");



        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("docrep/product_document_repository_type");

        $grid->addColumnKey();

        $grid->addColumn('ds_product_document_repository_type', 'Description', '150px', $f->retTypeStringUpper(), array('limit' => '64'));
        $grid->addColumn('fl_default', 'Default', '150px', $f->retTypeCheckBox(), true);
        $grid->addColumn('fl_show_on_selection', 'Show On Selection', '150px', $f->retTypeCheckBox(), true);
        $grid->addColumn('fl_show_on_spec_sheet', 'Show On Spec Sheet', '150px', $f->retTypeCheckBox(), true);
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
