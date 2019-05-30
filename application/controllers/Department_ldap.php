<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class department_ldap extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("department_ldap_model", "mainmodel", TRUE);
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

        $fm->addSimpleFilterUpper('Department AD', 'filter_1', '"DEPARTMENT_LDAP".ds_department_ldap');
        $fm->addPickListFilter('Department', 'filter_5', 'job_department', '"DEPARTMENT_LDAP".cd_department');
        $fm->addPickListFilter('System Roles', 'filter_6', 'jobs_maint', '"DEPARTMENT_LDAP".cd_jobs');
        $fm->addPickListFilter('User Project Roles', 'filter_7', 'tti/roles', '"DEPARTMENT_LDAP".cd_roles');
        $fm->addFilterYesNo("Active", "dt_deactivated", "", "Y");

        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar(true, false, true);
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("department_ldap");

        $grid->addColumnKey();

        $grid->addColumn('ds_department_ldap', 'Department AD', '100%', $f->retTypeStringAny(), array('limit' => '128'));
        $grid->addColumn('ds_department', 'Department', '150px', $f->retTypePickList(), array('model' => 'job_department_model', 'codeField' => 'cd_department'));
        $grid->addColumn('ds_jobs', 'System Role', '150px', $f->retTypePickList(), array('model' => 'job_model', 'codeField' => 'cd_jobs'));
        $grid->addColumn('ds_roles', 'Project User Role', '150px', $f->retTypePickList(), array('model' => 'tti/roles_model', 'codeField' => 'cd_roles'));
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
