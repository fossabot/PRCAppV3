<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class country extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("spec/shoe_sample_request_model", "mainmodel", TRUE);
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

        $fm->addPickListFilter('Sample Type', 'filter_1', 'spec/sample_type_model', '"SHOE_SAMPLE_REQUEST".cd_sample_type');
        $fm->addPickListFilter('Specification', 'filter_2', 'spec/shoe_specification_model', '"SHOE_SAMPLE_REQUEST".cd_shoe_specification');
        $fm->addPickListFilter('Division', 'filter_3', 'division_model', '"SHOE_SAMPLE_REQUEST".cd_division');
        $fm->addPickListFilter('Brand', 'filter_4', 'division_brand_model', '"SHOE_SAMPLE_REQUEST".cd_division_brand');
        $fm->addPickListFilter('Customer', 'filter_5', 'customer_model', '"SHOE_SAMPLE_REQUEST".cd_customer');
        $fm->addPickListFilter('Factory', 'filter_6', 'factory_model', '"SHOE_SAMPLE_REQUEST".cd_factory');
        $fm->addPickListFilter('Division Style Name', 'filter_7', 'spec/shoe_division_style_name_model', '"SHOE_SAMPLE_REQUEST".cd_shoe_division_style_name');
        $fm->addPickListFilter('Sock Logo', 'filter_8', 'spec/sock_logo_model', '"SHOE_SAMPLE_REQUEST".cd_sock_logo');
        $fm->addSimpleFilterUpper('Sample Customer Order', 'filter_11', '"SHOE_SAMPLE_REQUEST".ds_sample_customer_order');
        $fm->addPickListFilter('Season', 'filter_15', 'season_model', '"SHOE_SAMPLE_REQUEST".cd_season');


        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("spec/shoe_sample_request");

        $grid->addColumnKey();

        $grid->addColumn('ds_sample_type', 'Sample Type', '150px', $f->retTypePickList(), array('model' => 'spec/sample_type_model', 'codeField' => 'cd_sample_type'));
        $grid->addColumn('ds_shoe_specification', 'Specification', '150px', $f->retTypePickList(), array('model' => 'spec/shoe_specification_model', 'codeField' => 'cd_shoe_specification'));
        $grid->addColumn('ds_division', 'Division', '150px', $f->retTypePickList(), array('model' => 'division_model', 'codeField' => 'cd_division'));
        $grid->addColumn('ds_division_brand', 'Brand', '150px', $f->retTypePickList(), array('model' => 'division_brand_model', 'codeField' => 'cd_division_brand'));
        $grid->addColumn('ds_customer', 'Customer', '150px', $f->retTypePickList(), array('model' => 'customer_model', 'codeField' => 'cd_customer'));
        $grid->addColumn('ds_factory', 'Factory', '150px', $f->retTypePickList(), array('model' => 'factory_model', 'codeField' => 'cd_factory'));
        $grid->addColumn('ds_shoe_division_style_name', 'Division Style Name', '150px', $f->retTypePickList(), array('model' => 'spec/shoe_division_style_name_model', 'codeField' => 'cd_shoe_division_style_name'));
        $grid->addColumn('ds_sock_logo', 'Sock Logo', '150px', $f->retTypePickList(), array('model' => 'spec/sock_logo_model', 'codeField' => 'cd_sock_logo'));
        $grid->addColumn('dt_requested_by_customer', 'Requested By Customer', '80px', $f->retTypeDate(), true);
        $grid->addColumn('dt_sent_to_customer', 'Sent To Customer', '80px', $f->retTypeDate(), true);
        $grid->addColumn('ds_sample_customer_order', 'Sample Customer Order', '150px', $f->retTypeStringUpper(), array('limit' => '128'));
        $grid->addColumn('ds_comments', 'Comments', '150px', $f->retTypeTextPL(), true);
        $grid->addColumn('nr_total_pairs', 'Total Pairs', '150px', $f->retTypeNum(), array('precision' => '1', 'readonly' => false));
        $grid->addColumn('ds_season', 'Season', '150px', $f->retTypePickList(), array('model' => 'season_model', 'codeField' => 'cd_season'));

        $javascript = $grid->retGrid();


        $trans = array();
        $trans = $this->cdbhelper->retTranslationDifKeys($trans);



        $send = array("javascript" => $javascript,
            "filters" => $filters,
            "filters_java" => $fm->retJavascript()) + $trans;


        $this->load->view("defaultView", $send);
    }

}
