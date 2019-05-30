<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class assets_location_room extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("assets/assets_location_room_model", "mainmodel", TRUE);
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

        $fm->addSimpleFilterUpper('Description', 'filter_1', '"ASSETS_LOCATION_ROOM".ds_assets_location_room');
        $fm->addPickListFilter('Building', 'filter_2', 'assets/assets_location', '"ASSETS_LOCATION_ROOM".cd_assets_location');
        $fm->addFilterYesNo("Active", "dt_deactivated", "", "Y");



        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("assets/assets_location_room");

        $grid->addColumnKey();

        $grid->addColumn('ds_assets_location_room', 'Description', '150px', $f->retTypeStringAny(), array('limit' => '32'));
        $grid->addColumn('ds_assets_location', 'Building', '150px', $f->retTypePickList(), array('model' => 'assets/assets_location_model', 'codeField' => 'cd_assets_location'));
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
