<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class project_comments_type_group extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("tti/project_comments_type_group_model", "mainmodel", TRUE);
        $this->load->model("human_resource_model", "hmmodel", TRUE);
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

        $fm->addSimpleFilterUpper('Description', 'filter_1', '"PROJECT_COMMENTS_TYPE_GROUP".ds_project_comments_type_group');
        $fm->addFilterYesNo("Active", "dt_deactivated", "", "Y");



        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("tti/project_comments_type_group");

        $grid->addColumnKey();

        $grid->addColumn('ds_project_comments_type_group', 'Description', '100%', $f->retTypeStringAny(), array('limit' => '64'));
        $grid->addColumnDeactivated(true);

        $javascript = $grid->retGrid();

        $grid->resetGrid();
        $grid->addColumnKey();
        $grid->addEditToolbar();
        $grid->setToolbarSearch(true);
        //$grid->addColumn('fl_checkbox', 'X', '40px', $f->retTypeCheckBox(), true);
        $grid->addColumn('ds_description', 'User', '100%', $f->retTypeStringAny());
        $grid->setGridName('gridHM');
        $grid->setGridDivName('myGridHM_div');
        $javascript = $javascript . $grid->retGrid();

        $filters = $fm->retFiltersWithGroup();



        $trans = array();
        $trans = $this->cdbhelper->retTranslationDifKeys($trans);



        $send = array("javascript" => $javascript,
            "filters" => $filters,
            "filters_java" => $fm->retJavascript()) + $trans;


        $this->load->view("tti/project_comments_type_group_view", $send);
    }

    public function retrieveGridJson($retrOpt = array()) {


        if (!$this->logincontrol->isProperLogged(false)) {
            echo('{"logged": "N", "resultset": [] }');
            return;
        }


        $where = $this->getWhereToFilter();


        $jsonMapping = $this->getJsonMappingToFilter();

        if (isset($retrOpt['whereToAdd'])) {
            $where = $where . $retrOpt['whereToAdd'];
        }

        $data = json_decode($this->mainmodel->retRetrieveGridJson($where, '', $jsonMapping, $retrOpt), true);

        foreach ($data as $key => $value) {
            $data[$key]['hmdata'] = json_decode($this->hmmodel->retGridJsonByPrjTypeGroup($value['recid'], 'Y', true), true);
        }

        echo('{ "logged": "Y", "resultset": ' . json_encode($data, JSON_NUMERIC_CHECK) . ' }');
    }

    public function retGridJsonByPrjTypeGroup($cd_project_comments_type_group, $mode = 'B', $json = true) {
        echo ($this->hmmodel->retGridJsonByPrjTypeGroup($cd_project_comments_type_group, $mode, $json, true));
    }

}
