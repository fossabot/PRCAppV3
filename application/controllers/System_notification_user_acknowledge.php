<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class system_notification_user_acknowledge extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("system_notification_user_acknowledge_model", "mainmodel", TRUE);
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

        $fm->addPickListFilter('Human Resource', 'filter_1', 'human_resource', '"SYSTEM_NOTIFICATION_USER_ACKNOWLEDGE".cd_human_resource');
        $fm->addPickListFilter('System Notification', 'filter_2', 'system_notification', '"SYSTEM_NOTIFICATION_USER_ACKNOWLEDGE".cd_system_notification');

        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("system_notification_user_acknowledge");
        $grid->addColumnKey();
        $grid->addColumn('ds_human_resource', 'Human Resource', '150px', $f->retTypePickList(), array('model' => 'human_resource_model', 'codeField' => 'cd_human_resource'));
        $grid->addColumn('ds_system_notification', 'System Notification', '150px', $f->retTypePickList(), array('model' => 'system_notification_model', 'codeField' => 'cd_system_notification'));

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