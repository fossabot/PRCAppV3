<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class human_resource_controller extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("human_resource_model", "mainmodel", TRUE);
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

        $fm->addSimpleFilterUpper('Human Resource Full', 'filter_1', '"HUMAN_RESOURCE".ds_human_resource_full');
        $fm->addSimpleFilterUpper('Human Resource', 'filter_2', '"HUMAN_RESOURCE".ds_human_resource');
        //$fm->addPickListFilter('Hr Type', 'filter_5', 'hr_type', '"HUMAN_RESOURCE".cd_hr_type');
        $fm->addSimpleFilterUpper('Password', 'filter_6', '"HUMAN_RESOURCE".ds_password');
        $fm->addSimpleFilterUpper('E Mail', 'filter_7', '"HUMAN_RESOURCE".ds_e_mail');
        $fm->addFilterYesNo("Active", "dt_deactivated", "", "Y");



        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("human_resource");

        $grid->addColumnKey();

        $grid->addColumn('ds_human_resource_full', 'Human Resource Full', '150px', $f->retTypeStringUpper(), array('limit' => '64'));
        $grid->addColumn('ds_human_resource', 'Human Resource', '150px', $f->retTypeStringUpper(), array('limit' => '16'));
        //$grid->addColumn('ds_hr_type', 'Hr Type', '150px', $f->retTypePickList(), array('model' => 'hr_type_model', 'codeField' => 'cd_hr_type'));
        $grid->addColumn('ds_password', 'Password', '150px', $f->retTypeStringUpper(), array('limit' => '32'));
        $grid->addColumn('ds_e_mail', 'E Mail', '150px', $f->retTypeStringUpper(), array('limit' => '64'));
        $grid->addColumn('fl_super_user', 'Super User', '150px', $f->retTypeCheckBox(), true);
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
    
    
    
    public function retGridJsonByNotif ($cd_notification_group, $mode = 'B',  $fieldsForSelection = true) {
      echo $this->mainmodel->retGridJsonByNotif($cd_notification_group, $mode, $fieldsForSelection); 
    }

    public function retPickListStaff($way = "", $unionPK = "-1", $whereadd = "") {
        if (!$this->mainmodel->hasDeactivate()) {
            $way = -1;
        }
        if (!$this->logincontrol->isProperLogged(false)) {
            echo('{"logged": "N", "items": [] }');
            return;
        }
        switch ($way) {
            case 1:
                $where = " where dt_deactivated IS NULL ";
                break;
            case 2:
                $where = " where dt_deactivated IS NOT NULL ";
                break;
            default:
                $where = " where 1=1 ";
                break;
        }
        if ($whereadd == 'undefined') {
            $whereadd = '';
        }
        $whereadd = $whereadd . $this->mainmodel->basicWhereForPL;

        if ($unionPK == 'undefined') {
            $unionPK = '-1';
        }
        $where = $where . $whereadd;
        $data = $this->mainmodel->selectForPLWithOrder($where, $unionPK, "ORDER BY nr_staff_number");
        //$this->mainmodel = $oldField;
        
        foreach ($data as $key => $val) {
            if (empty($val['staff_number'])) unset($data[$key]);
            else $data[$key]['description'] = $val['staff_number'];
        }
        $j = json_encode(array_values($data));
        $j = '{"items": ' . $j . '}';
        exit($j);
    }

        public function retPickListMail($way = "", $unionPK = "-1", $whereadd = "")
    {
        $where = "";
        // 1 - busca apenas os ativos (usado para selecao em forms)
        // 2 = apenas os desativados
        // o resto pega tudo

        if (!$this->mainmodel->hasDeactivate()) {
            $way = -1;
        }

        if (!$this->logincontrol->isProperLogged(false)) {
            echo('{"logged": "N", "items": [] }');
            return;
        }

        switch ($way) {
            case 1:
                $where = " where dt_deactivated IS NULL ";
                break;
            case 2:
                $where = " where dt_deactivated IS NOT NULL ";
                break;

            default:
                $where = " where 1=1 ";
                break;
        }

        if (IsSet($_POST['searchterm'])) {

            if ($this->db->dbdriver == 'postgre') {
                $where = $where . "AND " . $this->mainmodel->ds_field . " ilike '" . $_POST['searchterm'] . "%' ";
            } else {
                $where = $where . "AND lower(" . $this->mainmodel->ds_field . ") like lower('" . $_POST['searchterm'] . "%') ";
            }

        }

        if ($whereadd == 'undefined') {
            $whereadd = '';
        }

        $whereadd = $whereadd . $this->mainmodel->basicWhereForPL;

        $whereadd = $whereadd . " AND COALESCE(ds_e_mail, '') != '' ";

        if ($unionPK == 'undefined') {
            $unionPK = '-1';
        }

        $where = $where . $whereadd;

        //die ($where);

        $j = json_encode($this->mainmodel->selectForPL($where, $unionPK));
        $j = '{"items": ' . $j . '}';

        echo $j;
    }

}
