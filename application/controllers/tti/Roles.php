<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class roles extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("tti/roles_model", "mainmodel", TRUE);
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

        $fm->addSimpleFilterUpper('Description', 'filter_1', '"ROLES".ds_roles');
        $fm->addPickListFilter('Notification Type Default', 'filter_5', 'notification_type', '"ROLES".cd_notification_type_default');
        $fm->addFilterYesNo("Active", "dt_deactivated", "", "Y");



        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("tti/roles");

        $grid->addColumnKey();

        $grid->addColumn('ds_roles', 'Description', '100%', $f->retTypeStringUpper(), array('limit' => '64'));
        $grid->addColumn('ds_notification_type_default', 'Default Notification Type', '150px', $f->retTypePickList(), array('model' => 'notification_type_model', 'codeField' => 'cd_notification_type_default' ) );
        $grid->addColumn('fl_can_comment_project', 'Can add Comment', '150px', $f->retTypeCheckBox(), true);
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
