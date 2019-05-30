<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class wi_section extends controllerBasicExtend {

    var $arrayIns;
    var $fields;
    function __construct() {
        parent::__construct();
        $this->load->model("schedule/wi_section_model", "mainmodel", TRUE);
        $this->load->model("schedule/wi_revision_model", "revisionmodel", TRUE);

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

        $fm->addSimpleFilterUpper('Wi Section', 'filter_1', '"WI_SECTION".ds_wi_section');
        $fm->addSimpleFilterUpper('Section Code', 'filter_2', '"WI_SECTION".ds_section_code');
        $fm->addPickListFilter('Wi Revision', 'filter_3', 'schedule/wi_revision', '"WI_SECTION".cd_wi_revision');
        $fm->addPickListFilter('Test Type', 'filter_6', 'tr/test_type', '"WI_SECTION".cd_test_type');
        $fm->addPickListFilter('Human Resource Approval', 'filter_9', 'human_resource', '"WI_SECTION".cd_human_resource_approval');
        $fm->addFilterYesNo("Active", "dt_deactivated", "", "Y");

        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("schedule/wi_section");

        $grid->addColumnKey();
        $grid->addColumn('ds_wi_section', 'Wi Section', '150px', $f->retTypeStringAny(), array('limit' => ''));
        $grid->addColumn('ds_section_code', 'Section Code', '150px', $f->retTypeStringAny(), array('limit' => '16'));
        $grid->addColumn('ds_wi_revision', 'Wi Revision', '150px', $f->retTypePickList(), array('model' => 'schedule/wi_revision_model', 'codeField' => 'cd_wi_revision'));
        $grid->addColumn('ds_test_type', 'Test Type', '150px', $f->retTypePickList(), array('model' => 'tr/test_type_model', 'codeField' => 'cd_test_type'));
        $grid->addColumn('nr_wi_section_revision', 'Wi Section Revision', '150px', $f->retTypeInteger(), true);
        $grid->addColumn('dt_approval', 'Approval', '80px', $f->retTypeDate(), true);
        $grid->addColumn('ds_human_resource_approval', 'Human Resource Approval', '150px', $f->retTypePickList(), array('model' => 'human_resource_model', 'codeField' => 'cd_human_resource_approval'));
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

    //find wi sections with revision id or pk
    public function treeView($revision_id, $ids = '') {
        if (empty($revision_id) && empty($ids)) {
            return false;
        }
        if ($revision_id) {
            $sql = 'where cd_wi_revision=' . $revision_id;
        } else if ($ids) {
            $sql = 'where cd_wi_section in(' . $ids . ')';
        }
        $sections = $this->mainmodel->retRetrieveArray($sql, 'order by ds_section_code asc');
        $branch_section = [];
        foreach ($sections as $val) {
            $temp = $val;
            $temp['text'] = $val['ds_section_code'] . ($val['ds_test_type'] ? '-' . $val['ds_test_type'] : '');
            $temp['lazyLoad'] = true;//本节点为懒加载节点
            $branch_section[] = $temp;
        }
        exit(json_encode($branch_section));

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

        $fl_can_delete_wi_section = $this->getCdbhelper()->getUserPermission('fl_can_delete_wi_section');

        if ($code == -1) {
            $sc = "Y";
            $readonly = 'N';
            $line = $this->mainmodel->retRetrieveEmptyNewArray();
            if (!empty($upid)) {
                $readonly = 'Y';
                $upWi = $this->revisionmodel->retRetrieveArray('WHERE cd_wi_revision = ' . $upid)[0];
                $line[0]['cd_wi_revision'] = $upWi['cd_wi_revision'];
                $line[0]['nr_wi_revision'] = $upWi['nr_wi_revision'];
            }
            $action = 'I';
            $grid->addCRUDToolbar(false, false, true, false, false);
            $trans['title'] = 'Add new Section';
        } else {
            $sc = "N";
            $readonly = 'Y';
            if ($upid == -1) {
                $sc = "Y";
                $sql = "select schedule.wi_section_duplicate($code) as recid";
                $ret = $this->getCdbhelper()->basicSQLArray($sql);
                $code = $ret[0]['recid'];
            }
            $line = $this->mainmodel->retRetrieveArray('WHERE cd_wi_section = ' . $code);
            $action = 'E';
            $grid->addCRUDToolbar(false, true, true, true, false);
            $trans['title'] = $line[0]['ds_section_code'];
        }

        $grid->setGridVar('vGridToToolbarWi');
        $grid->setForceDestroy(false);

        $trans = array(
            'formTrans_cd_wi_section'=> 'Code',
            'formTrans_ds_wi_section'=> 'Wi Section',
            'formTrans_ds_section_code'=> 'Section Code',
            'formTrans_cd_wi_revision'=> 'Wi Revision',
            'formTrans_dt_deactivated'=> 'Deactivated',
            'formTrans_dt_record'=> 'Record',
            'formTrans_cd_test_type'=> 'Test Type',
            'formTrans_nr_wi_section_revision'=> 'Section Revision',
            'formTrans_dt_approval'=> 'Approval',
            'formTrans_cd_human_resource_approval'=> 'Human Resource Approval',
            'delWi' => 'Confirm Delete the Whole Section ?',

        );
        $trans = $this->cdbhelper->retTranslationDifKeys($trans);

        $htmlView = $this->load->view("schedule/wi_section_form_view", $trans + $line[0] + array(
                'sc' => $sc,
                'readonly' => $readonly,
                'toolbar' => $grid->retGridVar(),
                'action' => $action,

            ), true);
        $ret = ['html' => $htmlView];
        echo(json_encode($ret));

    }

    public function deleteById($cd_wi_section) {
        $error = $this->mainmodel->deleteGridData(array($cd_wi_section));
        echo($error);
    }


}