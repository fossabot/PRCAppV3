<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class project_comments_type extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("tti/project_comments_type_model", "mainmodel", TRUE);
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

        $fm->addSimpleFilterUpper('Description', 'filter_1', '"PROJECT_COMMENTS_TYPE".ds_project_comments_type');
        $fm->addPickListFilter('Group', 'filter_4', 'tti/project_comments_type_group', '"PROJECT_COMMENTS_TYPE".cd_project_comments_type_group');
        $fm->addFilterYesNo("Active", "dt_deactivated", "", "Y");



        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("tti/project_comments_type");

        $grid->addColumnKey();

        $grid->addColumn('ds_project_comments_type', 'Description', '100%', $f->retTypeStringAny(), array('limit' => '64'));
        $grid->addColumn('ds_project_comments_type_group', 'Group', '150px', $f->retTypePickList(), array('model' => 'tti/project_comments_type_group_model', 'codeField' => 'cd_project_comments_type_group'));
        $grid->addColumnDeactivated(true);
        //$grid->addColumn('location', 'location', '100%', $f->retTypeStringAny(), array('limit' => '64'));

        $filters = $fm->retFiltersWithGroup();
        $javascript = $grid->retGrid();


        $trans = array();
        $trans = $this->cdbhelper->retTranslationDifKeys($trans);



        $send = array("javascript" => $javascript,
            "filters" => $filters,
            "filters_java" => $fm->retJavascript()) + $trans;


        $this->load->view("defaultView", $send);
    }

    function openPL() {

        $id = $_POST['id'];

        $controller = 'tti/project_comments_type';

        $grid = $this->w2gridgen;
        $f = $this->cfields;
        $fm = $this->cfiltermaker;

        if (1 == 2) {
            $f = new Cfields();
            $grid = new w2gridgen();
            $fm = new cfiltermaker();
        }

        $fm = $this->cfiltermaker;
        $fm->setColumnBig();
        //$fm->addPickListFilter('Quotation Type', 'pl_filter_17', 'material/product_quotation_type', '"SHOE_COST_SHEET_SKU".cd_product_quotation_type');

        $filters = $fm->retFiltersWithGroup();

        $grid->setGridToolbarFunction('onGridToolbarPressedPL');
        //$grid->addUserBtnToolbar('clear', 'Clear', 'fa fa-times-circle-o', 'Clear');

        $grid->setGridVar('varMySpecificPL');
        $grid->setGridName('specificPLSup');
        $grid->setCRUDController($controller);
        $grid->setSingleBarControl(true);

        //$grid->addRetriveToolbar();
        //$grid->addBreakToolbar();

        if ($this->cdbhelper->checkMenuRights($controller) == 'Y') {
            $grid->addUserBtnToolbar('openMaint', 'Open Maintenance', 'fa fa-external-link');
        }
        $grid->addBreakToolbar();
        $grid->addSpacerToolbar();
        $grid->addExportToolbar();

        $grid->setToolbarSearch(true);

        $grid->addColumnKey();

        $grid->addColumn('ds_project_comments_type', 'Type', '220px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_users', 'Recipients', '100%', $f->retTypeStringAny(), false);
        

        $data = $this->mainmodel->retRetrieveGridJson(" WHERE \"PROJECT_COMMENTS_TYPE\".dt_deactivated IS NULL");

        $grid->addRecords($data);
        $javascript = $grid->retGridVar();

        $labels = array('title' => 'Comment Type');
        $labels = $this->cdbhelper->retTranslationDifKeys($labels);

        $send = array("javascript" => $javascript,
            "filters" => $filters,
            "filters_java" => $fm->retJavascript(),
            "keyColumn" => 'recid',
            "descColumn" => 'ds_project_comments_type',
            'retrieveFields' => json_encode($fm->getFilterNames()),
            'controller' => $controller,
            'id' => $id
        );


        $this->load->view("tti/project_comments_type_pl_view", $send + $labels);
    }

}
