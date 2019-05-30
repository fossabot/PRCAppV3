<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class assets_changes extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("assets/assets_changes_model", "mainmodel", TRUE);
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

        $fm->addPickListFilter('Assets', 'filter_1', 'assets/assets', '"ASSETS_CHANGES".cd_assets');
        $fm->addPickListFilter('Changed By', 'filter_3', 'human_resource_controller', '"ASSETS_CHANGES".cd_human_resource');
        $fm->addFilterDate('Changed At', 'filter_record', '"ASSETS_CHANGES".dt_record');
        $fm->addPickListFilter('Room', 'filter_4', 'assets/assets_location_room', '"ASSETS_CHANGES".cd_assets_location_room');
        $fm->addSimpleFilterUpper('Department Ref Number', 'filter_5', '"ASSETS_CHANGES".ds_department_ref_number');
        $fm->addPickListFilter('Responsible', 'filter_6', 'human_resource_controller', '"ASSETS_CHANGES".cd_human_resource_responsible');
        $fm->addSimpleFilterUpper('Assets Number Old', 'filter_7', '"ASSETS_CHANGES".ds_assets_number_old');
        $fm->addSimpleFilterUpper('Remarks', 'filter_8', '"ASSETS_CHANGES".ds_remarks');
        $fm->addPickListFilter('Department Cost Center', 'filter_9', 'rfq/department_cost_center', '"ASSETS_CHANGES".cd_department_cost_center');

        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar(true, false, false, false, true);
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("assets/assets_changes");

        $grid->addColumnKey();

        $grid->addColumn('ds_assets', 'Assets', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_human_resource', 'Changed By', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('dt_record', 'Changed At', '80px', $f->retTypeDate(), false);
        
        $grid->addColumn('ds_assets_location_room', 'Room', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_department_ref_number', 'Department Ref Number', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_human_resource_responsible', 'Responsible', '150px',false);
        $grid->addColumn('ds_assets_number_old', 'Assets Number Old', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_remarks', 'Remarks', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_department_cost_center', 'Department Cost Center', '150px', $f->retTypeStringAny(), false);


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
