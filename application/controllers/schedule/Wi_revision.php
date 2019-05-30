<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class wi_revision extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("schedule/wi_revision_model", "mainmodel", TRUE);
        $this->load->model("schedule/wi_model", "wimodel", TRUE);
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

        $fm->addPickListFilter('Wi', 'filter_2', 'schedule/wi', '"WI_REVISION".cd_wi');
        $fm->addSimpleFilterUpper('Comments', 'filter_3', '"WI_REVISION".ds_comments');
        $fm->addFilterYesNo("Active", "dt_deactivated", "", "Y");


        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("schedule/wi_revision");

        $grid->addColumnKey();

        $grid->addColumn('nr_wi_revision', 'Wi Revision', '150px', $f->retTypeInteger(), true);
        $grid->addColumn('ds_wi', 'Wi', '150px', $f->retTypePickList(), array('model' => 'schedule/wi_model', 'codeField' => 'cd_wi'));
        $grid->addColumn('ds_comments', 'Comments', '150px', $f->retTypeStringAny(), array('limit' => ''));
        $grid->addColumn('cd_human_resource_record', 'Human Resource Record', '150px', $f->retTypeInteger(), true);
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

    //find wi revision with wi or pk
    public function treeView($wi_id, $ids = '') {
        if (empty($wi_id) && empty($ids)) {
            return false;
        }
        if ($wi_id) {
            $sql = 'where cd_wi=' . $wi_id;
        } else if ($ids) {
            $sql = 'where cd_wi_revision in(' . $ids . ')';
        }
        $revisions = $this->mainmodel->retRetrieveArray($sql, 'order by nr_wi_revision asc');
        $branch_revision = [];
        foreach ($revisions as $val) {
            $temp = $val;
            $temp['text'] = $val['nr_wi_revision'];
            $temp['lazyLoad'] = true;//本节点为懒加载节点
            $branch_revision[] = $temp;
        }
        exit(json_encode($branch_revision));

    }

    public function callWiForm($code, $upid = null) {

        $grid = $this->w2gridgen;
        $f = $this->cfields;
        $fm = $this->cfiltermaker;

        if (1 == 2) {
            $f = new Cfields();
            $grid = new w2gridgen();
            $fm = new cfiltermaker();
        }

        $fl_can_delete_wi_section_revision = $this->getCdbhelper()->getUserPermission('fl_can_delete_wi_section_revision');

        $grid->setGridVar('vGridToToolbarWi');
        $grid->setForceDestroy(false);

        $trans = array(
            'formTrans_cd_wi_revision'=> 'Code',
            'formTrans_nr_wi_revision'=> 'Revision',
            'formTrans_cd_wi'=> 'Wi',
            'formTrans_ds_comments'=> 'Comments',
            'formTrans_dt_deactivated'=> 'Deactivated',
            'formTrans_dt_record'=> 'Record',
            'formTrans_cd_human_resource_record'=> 'Created By',
            'delWi' => 'Confirm Delete the Whole Revision ?',
        );

        if ($code == -1) {
            $sc = "Y";
            $readonly = 'N';
            $line = $this->mainmodel->retRetrieveEmptyNewArray();
            if (!empty($upid)) {
                $readonly = 'Y';
                $upWi = $this->wimodel->retRetrieveArray('WHERE cd_wi = ' . $upid)[0];
                $line[0]['cd_wi'] = $upWi['cd_wi'];
                $line[0]['ds_wi_code'] = $upWi['ds_wi_code'];
            }
            $line[0]['cd_human_resource_record'] = $this->session->userdata('cd_human_resource');
            $line[0]['ds_human_resource_record'] = $this->session->userdata('ds_human_resource');

            $action = 'I';
            $grid->addCRUDToolbar(false, false, true, false, false);
            $trans['title'] = 'Add new Revision';
        } else {
            $sc = "N";
            $readonly = 'Y';
            if ($upid == -1) {
                $sc = "Y";
                $sql = "select schedule.wi_revision_duplicate($code) as recid";
                $ret = $this->getCdbhelper()->basicSQLArray($sql);
                $code = $ret[0]['recid'];
            }
            $line = $this->mainmodel->retRetrieveArray('WHERE cd_wi_revision = ' . $code);
            $action = 'E';
            $grid->addCRUDToolbar(false, true, true, true, false);
            $trans['title'] = $line[0]['nr_wi_revision'];
        }
        $trans = $this->cdbhelper->retTranslationDifKeys($trans);
        $htmlView = $this->load->view("schedule/wi_revision_form_view", $trans + $line[0] + array(
                'sc' => $sc,
                'readonly' => $readonly,
                'toolbar' => $grid->retGridVar(),
                'action' => $action,

            ), true);
        $ret = ['html' => $htmlView];
        echo(json_encode($ret));

    }

    public function deleteById($cd_wi_revision) {
        $error = $this->mainmodel->deleteGridData(array($cd_wi_revision));
        echo($error);
    }

}