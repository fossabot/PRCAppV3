<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class wi extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("schedule/wi_model", "mainmodel", TRUE);

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

        $fm->addSimpleFilterUpper('Wi Code', 'filter_1', '"WI".ds_wi_code');
        $fm->addSimpleFilterUpper('Wi', 'filter_2', '"WI".ds_wi');
        $fm->addFilterYesNo("Active", "dt_deactivated", "", "Y");


        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("schedule/wi");

        $grid->addColumnKey();

        $grid->addColumn('ds_wi_code', 'Wi Code', '150px', $f->retTypeStringAny(), array('limit' => '16'));
        $grid->addColumn('ds_wi', 'Wi', '150px', $f->retTypeStringAny(), array('limit' => ''));
        $grid->addColumnDeactivated(true);


        $filters = $fm->retFiltersWithGroup();
        $javascript = $grid->retGrid();

        $wi_trunk = $this->getWiTrunk('all');
        $wi_trunk = json_encode($wi_trunk);

        $trans = array();
        $trans = $this->cdbhelper->retTranslationDifKeys($trans);


        $send = array(
                "javascript" => $javascript,
                "filters" => $filters,
                "filters_java" => $fm->retJavascript(),
                "wi_trunk" => $wi_trunk,
            ) + $trans;


        $this->load->view("schedule/wi_view", $send);

    }

    public function callWiForm($code) {
        $grid = $this->w2gridgen;
        $f = $this->cfields;
        $fm = $this->cfiltermaker;

        if (1 == 2) {
            $f = new Cfields();
            $grid = new w2gridgen();
            $fm = new cfiltermaker();
        }

        $fl_can_delete_wi = $this->getCdbhelper()->getUserPermission('fl_can_delete_wi');

        $grid->setGridVar('vGridToToolbarWi');
        $grid->setForceDestroy(false);

        $trans = array(
            'formTrans_cd_wi' => 'Code',
            'formTrans_ds_wi_code' => 'Wi Code',
            'formTrans_ds_wi' => 'Wi',
            'formTrans_dt_deactivated' => 'Deactivated',
            'delWi' => 'Confirm Delete the Whole WI ?',
        );

        if ($code == -1) {
            $sc = "Y";
            $line = $this->mainmodel->retRetrieveEmptyNewArray();
            $action = 'I';
            $grid->addCRUDToolbar(false, false, true, false, false);
            $trans['title'] = 'Add new Wi';
        } else {
            $sc = "N";
            $line = $this->mainmodel->retRetrieveArray('WHERE cd_wi = ' . $code);
            $action = 'E';
            $grid->addCRUDToolbar(false, true, true, true, false);
            $trans['title'] = $line[0]['ds_wi_code'];
        }

        $trans = $this->cdbhelper->retTranslationDifKeys($trans);

        $htmlView = $this->load->view("schedule/wi_form_view", $trans + $line[0] + array(
                'sc' => $sc,
                'toolbar' => $grid->retGridVar(),
                'action' => $action,

            ), true);
        $ret = ['html' => $htmlView];
        echo(json_encode($ret));

    }

    public function deleteById($cd_wi) {
        $error = $this->mainmodel->deleteGridData(array($cd_wi));
        echo($error);
    }

    public function getById($cd_wi) {
        $line = $this->mainmodel->retRetrieveArray('WHERE cd_wi = ' . $cd_wi)[0];
        $line['text'] = $line['ds_wi_code'];
        $line['lazyLoad'] = true;//本节点为懒加载节点
        echo json_encode($line);
    }

    public function treeView($option, $ids = '') {
        exit(json_encode($this->getWiTrunk($option, $ids)));
    }

    //find wi with ids or all
    private function getWiTrunk($option, $ids = '') {
        if (empty($option) && empty($ids)) {
            return false;
        }
        if ($option == 'all') {
            $wi_data = $this->mainmodel->retRetrieveGridArray();
        } else if ($ids) {
            $wi_data = $this->mainmodel->retRetrieveArray('where cd_wi in(' . $ids . ')', 'order by ds_wi_code asc');
        }
        $wi_trunk = array();
        foreach ($wi_data as $val) {
            $temp = $val;
            $temp['text'] = $val['ds_wi_code'];
            $temp['lazyLoad'] = true;//本节点为懒加载节点
            $wi_trunk[] = $temp;
        }
        return $wi_trunk;

    }

}