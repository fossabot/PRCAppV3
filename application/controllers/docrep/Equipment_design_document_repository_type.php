<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class equipment_design_document_repository_type extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("docrep/equipment_design_document_repository_type_model", "mainmodel", TRUE);
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

        $fm->addSimpleFilterUpper('Description', 'filter_1', '"EQUIPMENT_DESIGN_DOCUMENT_REPOSITORY_TYPE".ds_equipment_design_document_repository_type');
        $fm->addFilterYesNo("Active", "dt_deactivated", "", "Y");


        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("docrep/equipment_design_document_repository_type");

        $grid->addColumnKey();

        $grid->addColumn('ds_equipment_design_document_repository_type', 'Description', '100%', $f->retTypeStringAny(), array('limit' => ''));
        $grid->addColumn('fl_default', 'Default', '150px', $f->retTypeCheckBox(), true);
        $grid->addColumn('fl_send_to_purchase', 'Send To Purchase', '150px', $f->retTypeCheckBox(), true);
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
