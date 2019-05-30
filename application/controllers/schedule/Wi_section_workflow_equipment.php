<?php

if (!defined("BASEPATH")) exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class wi_section_workflow_equipment extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("schedule/wi_section_workflow_equipment_model", "mainmodel", TRUE);
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

        $fm->addPickListFilter('Equipment Design', 'filter_1', 'rfq/equipment_design', '"WI_SECTION_WORKFLOW_EQUIPMENT".cd_equipment_design');
        $fm->addSimpleFilterUpper('Notes', 'filter_3', '"WI_SECTION_WORKFLOW_EQUIPMENT".ds_notes');
        $fm->addPickListFilter('Wi Section Workflow', 'filter_5', 'schedule/wi_section_workflow', '"WI_SECTION_WORKFLOW_EQUIPMENT".cd_wi_section_workflow');

        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("schedule/wi_section_workflow_equipment");

        $grid->addColumnKey();
        $grid->addColumn('ds_equipment_design', 'Equipment Design', '150px', $f->retTypePickList(), array('model' => 'rfq/equipment_design_model', 'codeField' => 'cd_equipment_design'));
        $grid->addColumn('nr_ratio', 'Ratio', '150px', $f->retTypeNum(), array('precision' => '4', 'readonly' => false));
        $grid->addColumn('ds_notes', 'Notes', '150px', $f->retTypeStringAny(), array('limit' => ''));
        $grid->addColumn('ds_wi_section_workflow', 'Wi Section Workflow', '150px', $f->retTypePickList(), array('model' => 'schedule/wi_section_workflow_model', 'codeField' => 'cd_wi_section_workflow'));

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