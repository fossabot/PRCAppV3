<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class project_build_schedule_tests_wo extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("schedule/project_build_schedule_tests_wo_model", "mainmodel", TRUE);
        $this->load->model("schedule/project_build_schedule_tests_model", "tstmodel", TRUE);
        $this->load->model("schedule/project_build_schedule_model", "schmodel", TRUE);
        $this->load->model("schedule/project_build_model", "buildmodel", TRUE);
        $this->load->model("tti/project_model", "projmodel", TRUE);
        //$this->load->model("tr/tr_mssql_model", "trdatamodel", TRUE);
        $this->load->model("tr/tr_test_request_work_order_model", "womodelsc", TRUE);
        
        $this->load->model("tr/mte_mssql_model", "mtedatamodel", TRUE);
        
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


        $this->setGridParser();
        $grid->setSingleBarControl(true);

        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("schedule/project_build_schedule_tests_wo");

        $grid->addColumnKey();

        $grid->addColumn('cd_project_build_schedule_tests', 'Project Build Schedule Tests', '150px', $f->retTypeInteger(), true);
        $grid->addColumn('nr_work_order_id', 'Work Order Id', '150px', $f->retTypeNum(), array('precision' => '2', 'readonly' => false));


        $filters = $fm->retFiltersWithGroup();
        $javascript = $grid->retGrid();


        $trans = array();
        $trans = $this->cdbhelper->retTranslationDifKeys($trans);



        $send = array("javascript" => $javascript,
            "filters" => $filters,
            "filters_java" => $fm->retJavascript()) + $trans;


        $this->load->view("defaultView", $send);
    }

    public function openWorkOrders($tst) {


        $grid = $this->w2gridgen;
        $f = $this->cfields;
        $fm = $this->cfiltermaker;

        if (1 == 2) {
            $f = new Cfields();
            $grid = new w2gridgen();
            $fm = new cfiltermaker();
        }

        $testdata = $this->tstmodel->retRetrieveArray(" WHERE cd_project_build_schedule_tests = $tst")[0];
        
        

        $arrayToReturn = $this->getData($tst);


        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->setGridToolbarFunction('dsMainWOObject.ToolBarClick');
        $grid->addUserBtnToolbar('selectall', 'Link All', 'fa fa-link', 'Link All');
        $grid->addUserBtnToolbar('unselectall', 'Unlink All', 'fa fa-chain-broken', 'Unlink All');
        $grid->addBreakToolbar();
        $grid->addCRUDToolbar(false, false, true, false, false);
        $grid->addSpacerToolbar();
        $grid->addExportToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("schedule/project_build_schedule_tests_wo");

        $grid->addColumnKey();
        $grid->addColumn('fl_checked', 'X', '40px', $f->retTypeCheckBox(), true);
        $grid->addColumn('ds_type_test', 'Type', '100px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_test_phase', 'Build on TR', '80px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_tr_test_request_work_order_status', 'Status', '100px', $f->retTypeStringAny(), false);
        $grid->addColumn('nr_work_order', 'WorkOrder', '100px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_test_item', 'Item', '100px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_test_procedure_name', 'Procedure', '100px', $f->retTypeStringAny(), false);
        $grid->addColumn('nr_sample_qtty', 'Tool Qty', '90px', $f->retTypeNum(), array('precision' => '0', 'readonly' => true));
        $grid->addColumn('ds_sample_list', 'Tool# (Text)', '100px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_sample_list_table', 'Tool# (Table)', '100px', $f->retTypeStringAny(), false);

        
        $grid->addColumn('ds_tti_project_number_tr', 'TTi Project TR#', '100px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_met_project_number_tr', 'MET Project# TR', '100px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_tti_project_model_number_tr', 'TTi Model TR#', '100px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_met_project_model_number_tr', 'MET Model TR#', '100px', $f->retTypeStringAny(), false);
        //$grid->addColumn('ds_remarks', 'Remarks', '150px', $f->retTypeStringAny(), false);
        
        
        //$grid->addColumn('ds_tti_project', 'TTi Project #', '100px', $f->retTypeStringAny(), false);
        //$grid->addColumn('ds_met_project', 'MET Project#', '100px', $f->retTypeStringAny(), false);
        //$grid->addColumn('ds_tti_project_model', 'TTi Model#', '100px', $f->retTypeStringAny(), false);
        //$grid->addColumn('ds_met_project_model', 'MET Model#', '100px', $f->retTypeStringAny(), false);

        $grid->addRecords(json_encode($arrayToReturn, JSON_NUMERIC_CHECK));
        $grid->setGridName('gridwoselect');
        $grid->setGridDivName('gridwoselectdiv');
        $grid->setColumnRenderFunc('ds_sample_list', 'dsMainWOObject.renderMissingRed');
        $grid->setColumnRenderFunc('ds_sample_list_table', 'dsMainWOObject.renderMissingRed');

        
        

        $javascript = $grid->retGrid();


        $trans = array('orderexists' => 'Work Order already existing on list', 'ordernotfound' => 'Work Order not found or already linked to another Project/Build', 'specificorder' => 'Specific Work Order');
        $trans = $this->cdbhelper->retTranslationDifKeys($trans);



        $send = array("javascript" => $javascript, 'tst' => $tst) + $trans + $testdata ;

        $this->load->view("schedule/project_build_schedule_tests_wo_select_view", $send);
    }

    public function getDataByWorkOrder($tst, $workorder) {
        
        $where = " WHERE \"TR_TEST_REQUEST_WORK_ORDER\".nr_work_order = $workorder "
                . "  AND NOT EXISTS ( SELECT 1 FROM \"PROJECT_BUILD_SCHEDULE_TESTS_WO\" x WHERE x.cd_tr_test_request_work_order = \"TR_TEST_REQUEST_WORK_ORDER\".cd_tr_test_request_work_order )";
        
        
        //die($this->womodelsc->retModelSQL($where));
        
        $data = $this->womodelsc->retRetrieveArray($where);
       
        
        $arrayToReturn = array();

        foreach ($data as $key => $value) {
           
            $checked = 1;
            $send = array('recid' => $value['cd_tr_test_request_work_order'] . '-' . $tst,
                'ds_type_test' => $value['ds_type_test'],
                'ds_test_item' => $value['ds_test_item'],
                'ds_test_procedure' => $value['ds_test_procedure_name'],
                'fl_checked' => $checked,
                'nr_work_order' => $value['nr_work_order'],
                'nr_sample_qtty' => $value['nr_sample_qtty'],
                'ds_test_phase' => $value['ds_test_phase'],
                'ds_sample_list' => $value['ds_sample_list'],
                'ds_sample_list_table' => $value['ds_sample_list_table'],
                'ds_tti_project_number_tr' => $value['ds_tti_project_number_tr'],
                'ds_met_project_number_tr' => $value['ds_met_project_number_tr'],
                'ds_tti_project_model_number_tr' => $value['ds_tti_project_model_number_tr'],
                'ds_met_project_model_number_tr' => $value['ds_met_project_model_number_tr'],
                'ds_tr_test_request_work_order_status' => $value['ds_tr_test_request_work_order_status']);

            array_push($arrayToReturn, $send);
        }


        echo (json_encode($arrayToReturn, JSON_NUMERIC_CHECK));
    }

    public function getData($tst) {

        $testdata = $this->tstmodel->retRetrieveArray(" WHERE cd_project_build_schedule_tests = $tst")[0];
        $schdata = $this->schmodel->retRetrieveArray(" WHERE cd_project_build_schedule = " . $testdata['cd_project_build_schedule'])[0];
        $builddata = $this->buildmodel->retRetrieveArray(" WHERE cd_project_build = " . $schdata['cd_project_build'])[0];

        if (is_null($schdata['cd_project_model'])) {
            return array();
        }

        $prjmodel = $this->projmodel->retRetrieveArray(" WHERE cd_project_model = " . $schdata['cd_project_model'])[0];

        $existingconnection = $this->mainmodel->retRetrieveArray(" WHERE \"PROJECT_BUILD_SCHEDULE_TESTS_WO\".cd_project_build_schedule_tests = $tst");

        $buildstr = $builddata['ds_tr_build_prefix'];
        $model    = $prjmodel['cd_project_model'];
        
//getWorkOrders($prjmodel['ds_tti_project'], $prjmodel['ds_met_project'], $prjmodel['ds_tti_project_model'], $prjmodel['ds_met_project_model'], $builddata['ds_tr_build_prefix'], $schdata['nr_version'], '', $must);
        $where = " WHERE \"TR_TEST_REQUEST\".cd_project_model = $model "
                . "  AND NOT EXISTS ( SELECT 1 FROM \"PROJECT_BUILD_SCHEDULE_TESTS_WO\" x WHERE x.cd_tr_test_request_work_order = \"TR_TEST_REQUEST_WORK_ORDER\".cd_tr_test_request_work_order )"
                . "  AND \"TR_TEST_REQUEST\".ds_test_phase ilike '%$buildstr%' "        ;
        
        $data = $this->womodelsc->retRetrieveArray($where);


        //die (print_r($data));

        $arrayToReturn = array();

        
        // first connected already;
        foreach ($existingconnection as $key => $value) {
            $checked = 1;
            $send = array('recid' => $value['cd_tr_test_request_work_order'] . '-' . $tst,
                'ds_type_test' => $value['ds_type_test'],
                'ds_test_item' => $value['ds_test_item'],
                'ds_test_procedure_name' => $value['ds_test_procedure_name'],
                'fl_checked' => $checked,
                'nr_work_order' => $value['nr_work_order'],
                'nr_sample_qtty' => $value['nr_sample_qtty'],
                'ds_test_phase' => $value['ds_test_phase'],
                'ds_sample_list' => $value['ds_sample_list'],
                'ds_sample_list_table' => $value['ds_sample_list_table'],
                'ds_tti_project_number_tr' => $value['ds_tti_project_number_tr'],
                'ds_met_project_number_tr' => $value['ds_met_project_number_tr'],
                'ds_tti_project_model_number_tr' => $value['ds_tti_project_model_number_tr'],
                'ds_met_project_model_number_tr' => $value['ds_met_project_model_number_tr'],
                'ds_tr_test_request_work_order_status' => $value['ds_tr_test_request_work_order_status']);

            array_push($arrayToReturn, $send);
        }
        foreach ($data as $key => $value) {
            $checked = 0;
            $send = array('recid' => $value['cd_tr_test_request_work_order'] . '-' . $tst,
                'ds_type_test' => $value['ds_type_test'],
                'ds_test_item' => $value['ds_test_item'],
                'ds_test_procedure' => $value['ds_test_procedure_name'],
                'fl_checked' => $checked,
                'nr_work_order' => $value['nr_work_order'],
                'nr_sample_qtty' => $value['nr_sample_qtty'],
                'ds_test_phase' => $value['ds_test_phase'],
                'ds_sample_list' => $value['ds_sample_list'],
                'ds_sample_list_table' => $value['ds_sample_list_table'],
                'ds_tti_project_number_tr' => $value['ds_tti_project_number_tr'],
                'ds_met_project_number_tr' => $value['ds_met_project_number_tr'],
                'ds_tti_project_model_number_tr' => $value['ds_tti_project_model_number_tr'],
                'ds_met_project_model_number_tr' => $value['ds_met_project_model_number_tr'],
                'ds_tr_test_request_work_order_status' => $value['ds_tr_test_request_work_order_status']);

            array_push($arrayToReturn, $send);
        }


        return $arrayToReturn;
    }

    public function updateDataJson() {


        $msg = '';

        $upd_array = json_decode($_POST['upd']);
        $retResultset = 'N';

        if (isset($_POST['retResultSet'])) {
            $retResultset = $_POST['retResultSet'];
        }
        $jsonMapping = '';
        if (isset($_POST['jsonMapping'])) {
            $jsonMapping = $_POST['jsonMapping'];
        }

        $array_to_add = array();
        $array_to_delete = array();

        foreach ($upd_array as $key => $value) {
            $split = explode('-', $value->recid);
            $wo = $split[0];
            $tst = $split[1];

            $line = $this->mainmodel->retRetrieveArray(" WHERE \"PROJECT_BUILD_SCHEDULE_TESTS_WO\".cd_project_build_schedule_tests = $tst AND \"PROJECT_BUILD_SCHEDULE_TESTS_WO\".cd_tr_test_request_work_order = $wo");

            if ($value->fl_checked == 1) {
                // it is checked but the line already exists, so no action
                if (count($line) > 0) {
                    continue;
                }

                $row = array('recid' => $this->mainmodel->getNextCode(),
                    'cd_tr_test_request_work_order' => $wo,
                    'cd_project_build_schedule_tests' => $tst);

                array_push($array_to_add, $row);
            }

            if ($value->fl_checked == 0) {
                // if removing, but the line doesn't exist. No action
                if (count($line) == 0) {
                    continue;
                }

                array_push($array_to_delete, $line[0]['recid']);
            }
        }


        $this->getCdbhelper()->trans_begin();

        $error = $this->mainmodel->deleteGridData($array_to_delete);
        if ($error != 'OK') {
            $this->getCdbhelper()->trans_rollback();
            $msg = '{"status":' . json_encode($error) . ', "rs":{} }';
            echo($msg);
            return;
        }

        $error = $this->mainmodel->updateGridData($array_to_add);
        if ($error != 'OK') {
            $this->getCdbhelper()->trans_rollback();
            $msg = '{"status":' . json_encode($error) . ', "rs":{} }';
            echo($msg);
            return;
        }

        $this->getCdbhelper()->trans_commit();
        $this->getCdbhelper()->trans_end();


        //die('dentro do basic');

        $msg = '{"status":' . json_encode($error);

        $data = $this->getData($tst);
        $msg = $msg . ', "rs": ' . json_encode($data, JSON_NUMERIC_CHECK);

        $line = $this->mainmodel->retRetrieveArray(" WHERE \"PROJECT_BUILD_SCHEDULE_TESTS_WO\".cd_project_build_schedule_tests = $tst");
        $msg = $msg . ', "griddata": ' . json_encode($line, JSON_NUMERIC_CHECK);

        $msg = $msg . '}';

        //

        echo $msg;
    }
    

    
    
    
}

