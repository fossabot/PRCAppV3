<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class wi_section_workflow extends controllerBasicExtend {

    var $arrayIns;
    var $fields;
    private $titleFields;

    function __construct() {
        parent::__construct();
        $this->load->model("schedule/wi_section_workflow_model", "mainmodel", TRUE);
        $this->load->model("schedule/wi_section_model", "sectionmodel", TRUE);
        $this->load->model("schedule/wi_section_workflow_equipment_model", "equipmentmodel", TRUE);
        //title combined with these fields
        $this->titleFields = ['ds_wi_section_workflow_code', 'nr_wi_section_workflow_revision', 'nr_wi_section_workflow_revision_minor', 'ds_project_model', 'ds_project_product'];
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

        $fm->addSimpleFilterUpper('Wi Section Workflow', 'filter_1', '"WI_SECTION_WORKFLOW".ds_wi_section_workflow');
        $fm->addSimpleFilterUpper('Wi Section Workflow Code', 'filter_2', '"WI_SECTION_WORKFLOW".ds_wi_section_workflow_code');
        $fm->addPickListFilter('Wi Section Revision Type', 'filter_3', 'schedule/wi_section_revision_type', '"WI_SECTION_WORKFLOW".cd_wi_section_revision_type');
        $fm->addPickListFilter('Wi Section', 'filter_6', 'schedule/wi_section', '"WI_SECTION_WORKFLOW".cd_wi_section');
        $fm->addPickListFilter('Test Unit', 'filter_8', 'tr/test_unit', '"WI_SECTION_WORKFLOW".cd_test_unit');
        $fm->addSimpleFilterUpper('Specification', 'filter_10', '"WI_SECTION_WORKFLOW".ds_specification');
        $fm->addSimpleFilterUpper('Equipment Description', 'filter_11', '"WI_SECTION_WORKFLOW".ds_equipment_description');
        $fm->addPickListFilter('Project Model', 'filter_14', 'tti/project_model', '"WI_SECTION_WORKFLOW".cd_project_model');
        $fm->addPickListFilter('Project Product', 'filter_15', 'tti/project_product', '"WI_SECTION_WORKFLOW".cd_project_product');
        $fm->addFilterYesNo("Active", "dt_deactivated", "", "Y");


        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("schedule/wi_section_workflow");

        $grid->addColumnKey();

        $grid->addColumn('ds_wi_section_workflow', 'Wi Section Workflow', '150px', $f->retTypeStringAny(), array('limit' => ''));
        $grid->addColumn('ds_wi_section_workflow_code', 'Wi Section Workflow Code', '150px', $f->retTypeStringAny(), array('limit' => '32'));
        $grid->addColumn('ds_wi_section_revision_type', 'Wi Section Revision Type', '150px', $f->retTypePickList(), array('model' => 'schedule/wi_section_revision_type_model', 'codeField' => 'cd_wi_section_revision_type'));
        $grid->addColumn('ds_wi_section', 'Wi Section', '150px', $f->retTypePickList(), array('model' => 'schedule/wi_section_model', 'codeField' => 'cd_wi_section'));
        $grid->addColumn('dt_approval', 'Approval', '80px', $f->retTypeDate(), true);
        $grid->addColumn('ds_test_unit', 'Test Unit', '150px', $f->retTypePickList(), array('model' => 'tr/test_unit_model', 'codeField' => 'cd_test_unit'));
        $grid->addColumn('nr_man_power', 'Man Power', '150px', $f->retTypeNum(), array('precision' => '4', 'readonly' => false));
        $grid->addColumn('ds_specification', 'Specification', '150px', $f->retTypeStringAny(), array('limit' => ''));
        $grid->addColumn('ds_equipment_description', 'Equipment Description', '150px', $f->retTypeStringAny(), array('limit' => ''));
        $grid->addColumn('nr_wi_section_workflow_revision', 'Wi Section Workflow Revision', '150px', $f->retTypeInteger(), true);
        $grid->addColumn('nr_wi_section_workflow_revision_minor', 'Wi Section Workflow Revision Minor', '150px', $f->retTypeInteger(), true);
        $grid->addColumn('ds_project_model', 'Project Model', '150px', $f->retTypePickList(), array('model' => 'tti/project_model_model', 'codeField' => 'cd_project_model'));
        $grid->addColumn('ds_project_product', 'Project Product', '150px', $f->retTypePickList(), array('model' => 'tti/project_product_model', 'codeField' => 'cd_project_product'));
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

    //find wi workflow with section id or pk
    public function treeView($section_id, $ids = '') {
        if (empty($section_id) && empty($ids)) {
            return false;
        }
        if ($section_id) {
            $sql = 'where cd_wi_section=' . $section_id;
        } else if ($ids) {
            $sql = 'where cd_wi_section_workflow in(' . $ids . ')';
        }
        $workflows = $this->mainmodel->retRetrieveArray($sql, 'order by nr_wi_section_workflow_revision asc');
        $branch = [];
        foreach ($workflows as $val) {
            $temp = $val;
            $temp['text'] = $this->getFormTitle($val);
            $temp['lazyLoad'] = false;//本节点为懒加载节点
            $branch[] = $temp;
        }
        exit(json_encode($branch));

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

        $fl_can_delete_wi_section_workflow = $this->getCdbhelper()->getUserPermission('fl_can_delete_wi_section_workflow');

        $grid->setGridVar('vGridToToolbarWi');
        $grid->setForceDestroy(false);

        $trans = array(
            'formTrans_cd_wi_section_workflow'=> 'Code',
            'formTrans_ds_wi_section_workflow'=> 'Workflow',
            'formTrans_ds_wi_section_workflow_code'=> 'Workflow Code',
            'formTrans_cd_wi_section_revision_type'=> 'Revision Type',
            'formTrans_dt_deactivated'=> 'Deactivated',
            'formTrans_dt_record'=> 'Record',
            'formTrans_cd_wi_section'=> 'Wi Section',
            'formTrans_dt_approval'=> 'Approval',
            'formTrans_cd_test_unit'=> 'Test Unit',
            'formTrans_nr_man_power'=> 'Man Power',
            'formTrans_ds_specification'=> 'Specification',
            'formTrans_ds_equipment_description'=> 'Equipment Description',
            'formTrans_nr_wi_section_workflow_revision'=> 'Revision',
            'formTrans_nr_wi_section_workflow_revision_minor'=> 'Revision Minor',
            'formTrans_cd_project_model'=> 'Project Model',
            'formTrans_cd_project_product'=> 'Project Product',
            'groupTitleWI' => 'WI Goals/Specifications',
            'delWi' => 'Confirm Delete the Whole Workflow ?',
            'equipmentTitle' => 'Equipments',
            'errorDel' => 'Must have at least one equipment. Cannot Delete',
        );

        if ($code == -1) {
            $sc = "Y";
            $readonly = 'N';
            $line = $this->mainmodel->retRetrieveEmptyNewArray();
            if (!empty($upid)) {
                $readonly = 'Y';
                $upWi = $this->sectionmodel->retRetrieveArray('WHERE cd_wi_section = ' . $upid)[0];
                $line[0]['cd_wi_section'] = $upWi['cd_wi_section'];
                $line[0]['ds_section_code'] = $upWi['ds_section_code'];
            }
            $action = 'I';
            $grid->addCRUDToolbar(false, false, true, false, false);
            $trans['title'] = 'Add new Workflow';

            // add basic model
            $eqpdata = '[]';

        } else {
            $sc = "N";
            $readonly = 'Y';
            if ($upid == -1) {
                $sc = "Y";
                $sql = "select schedule.wi_workflow_duplicate($code) as recid";
                $ret = $this->getCdbhelper()->basicSQLArray($sql);
                $code = $ret[0]['recid'];
                // add basic model
                $eqpdata = $this->equipmentmodel->retRetrieveEmptyNewArray();
                $eqpdata[0]['cd_wi_section_workflow'] = $code;
            }
            $line = $this->mainmodel->retRetrieveArray('WHERE cd_wi_section_workflow = ' . $code);
            $eqpdata = $this->equipmentmodel->retRetrieveGridJson(' WHERE cd_wi_section_workflow = ' . $code);
            $action = 'E';
            $grid->addCRUDToolbar(false, true, true, true, false);
            $trans['title'] = $this->getFormTitle($line[0]);
        }

        $trans = $this->cdbhelper->retTranslationDifKeys($trans);
        $toolbar = $grid->retGridVar();

        // grid for checkpoints
        $grid->resetGrid();
        $grid->setGridToolbarFunction("dsMainObject.ToolbarGrid");

        $grid->setForceDestroy(true);
        $grid->showToolbar(true);
        $grid->addCRUDToolbar(false, true, true, true, false);
        $grid->showFooter(false);
        $grid->setGridDivName('gridWiEqpDiv');
        $grid->setGridName('gridWiEqp');
        $grid->setCRUDController("schedule/wi_section_workflow_equipment");

        $grid->addColumnKey();
        $grid->addColumn('ds_equipment_design', 'Equipment Design', '300px', $f->retTypePickList(), array('model' => 'rfq/equipment_design_model', 'codeField' => 'cd_equipment_design'));
        $grid->addColumn('nr_ratio', 'Ratio', '150px', $f->retTypeNum(), array('precision' => '4', 'readonly' => false));
        $grid->addColumn('ds_notes', 'Notes', '100%', $f->retTypeStringAny(), array('limit' => ''));

        $grid->addRecords($eqpdata);
        $WiEqpGrid = $grid->retGrid();

        $htmlView = $this->load->view("schedule/wi_section_workflow_form_view", $trans + $line[0] + array(
                'sc' => $sc,
                'readonly' => $readonly,
                'toolbar' => $toolbar,
                'action' => $action,
                'WiEqpGrid' => $WiEqpGrid,

            ), true);
        $ret = ['html' => $htmlView];
        echo(json_encode($ret));

    }

    //rewrite the base function to achieve equipment save
    public function updateDataJsonForm() {
        $upd_array = json_decode($_POST['upd']);
        $decodedAdditional = json_decode($_POST['additionalData'], true);

        $arraysend = array($upd_array);

        $this->getCdbhelper()->trans_begin();
        $error = $this->mainmodel->updateGridData($arraysend);
        if ($error != 'OK') {
            $this->getCdbhelper()->trans_rollback();
            $msg = '{"status":' . json_encode($error) . ', "rs":{} }';
            echo($msg);
            return;
        }

        foreach ($decodedAdditional as $key => $value) {
            $model = false;
            switch (substr($key, 0, 10)) {
                // checklist
                case 'gridWiEqp':
                    $model = $this->equipmentmodel;
                    break;

                default:
                    $model = false;
                    break;
            }

            if ($model) {
                $error = $model->updateGridData($value);
                if ($error != 'OK') {
                    $this->getCdbhelper()->trans_rollback();
                    $msg = '{"status":' . json_encode($error) . ', "rs":{} }';
                    echo($msg);
                    break;
                }
            }
        }

        $this->getCdbhelper()->trans_commit();
        $this->getCdbhelper()->trans_end();

        $retResult = $this->mainmodel->retRetrieveGridJson(' WHERE cd_wi_section_workflow = ' . $arraysend[0]->recid, ' ORDER BY 1 ');
        $eqpdata = $this->equipmentmodel->retRetrieveGridJson(' WHERE cd_wi_section_workflow = ' . $arraysend[0]->recid);

        // busco o gridChk;
        $msg = '{"status":' . json_encode($error) . ', "rs":' . $retResult . ', "gridData": ' . $eqpdata . ' }';

        echo $msg;
    }

    public function deleteById($cd_wi_section_workflow) {
        $error = $this->mainmodel->deleteGridData(array($cd_wi_section_workflow));
        echo($error);
    }

    private function getFormTitle($info) {
        $title = [];
        foreach ($this->titleFields as $field) {
            if (!empty($info[$field]) || is_numeric($info[$field])) {
                $title[] = $info[$field];
            }
        }
        return implode('-', $title);
    }
}