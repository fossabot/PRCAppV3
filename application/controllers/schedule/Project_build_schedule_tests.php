<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class project_build_schedule_tests extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("schedule/project_build_schedule_tests_model", "mainmodel", TRUE);
        
        $this->load->model("schedule/project_build_schedule_model", "prjschmodel", TRUE);
        $this->load->model("tti/project_model", "prjmodel", TRUE);
        
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

        $fm->addPickListFilter('Project Build Schedule', 'filter_1', 'schedule/project_build_schedule', '"PROJECT_BUILD_SCHEDULE_TESTS".cd_project_build_schedule');
        $fm->addPickListFilter('Test Type', 'filter_2', 'tr/test_type', '"PROJECT_BUILD_SCHEDULE_TESTS".cd_test_type');
        $fm->addPickListFilter('Test Item', 'filter_3', 'tr/test_item', '"PROJECT_BUILD_SCHEDULE_TESTS".cd_test_item');
        $fm->addPickListFilter('Tests', 'filter_4', 'tr/tests', '"PROJECT_BUILD_SCHEDULE_TESTS".cd_tests');
        $fm->addSimpleFilterUpper('Specification', 'filter_5', '"PROJECT_BUILD_SCHEDULE_TESTS".ds_specification');
        $fm->addSimpleFilterUpper('Sample Description', 'filter_6', '"PROJECT_BUILD_SCHEDULE_TESTS".ds_sample_description');
        $fm->addSimpleFilterUpper('Extra Instruction', 'filter_7', '"PROJECT_BUILD_SCHEDULE_TESTS".ds_extra_instruction');



        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("schedule/project_build_schedule_tests");

        $grid->addColumnKey();

        $grid->addColumn('ds_project_build_schedule', 'Project Build Schedule', '150px', $f->retTypePickList(), array('model' => 'schedule/project_build_schedule_model', 'codeField' => 'cd_project_build_schedule'));
        $grid->addColumn('ds_test_type', 'Test Type', '150px', $f->retTypePickList(), array('model' => 'tr/test_type_model', 'codeField' => 'cd_test_type'));
        $grid->addColumn('ds_test_item', 'Test Item', '150px', $f->retTypePickList(), array('model' => 'tr/test_item_model', 'codeField' => 'cd_test_item'));
        $grid->addColumn('ds_tests', 'Tests', '150px', $f->retTypePickList(), array('model' => 'tr/tests_model', 'codeField' => 'cd_tests'));
        $grid->addColumn('ds_specification', 'Specification', '150px', $f->retTypeStringUpper(), array('limit' => ''));
        $grid->addColumn('ds_sample_description', 'Sample Description', '150px', $f->retTypeStringUpper(), array('limit' => ''));
        $grid->addColumn('ds_extra_instruction', 'Extra Instruction', '150px', $f->retTypeStringUpper(), array('limit' => ''));
        $grid->addColumn('fl_witness', 'Witness', '150px', $f->retTypeCheckBox(), true);
        $grid->addColumn('nr_sample_quantity', 'Sample Quantity', '150px', $f->retTypeInteger(), true);
        $grid->addColumn('nr_charger_quantity', 'Charger Quantiry', '150px', $f->retTypeInteger(), true);
        $grid->addColumn('nr_power_pack_quantity', 'Power Pack Quantity', '150px', $f->retTypeInteger(), true);
        $grid->addColumn('nr_accessory_qty', 'Accessory Qty', '150px', $f->retTypeInteger(), true);
        $grid->addColumn('nr_goal', 'Goal', '150px', $f->retTypeInteger(), true);
        $grid->addColumn('nr_output', 'Daily Output', '150px', $f->retTypeInteger(), true);
        $grid->addColumn('fl_eol', 'Eol', '150px', $f->retTypeCheckBox(), true);
        $grid->addColumn('dt_start', 'Start', '80px', $f->retTypeDate(), true);
        $grid->addColumn('dt_finish', 'Finish', '80px', $f->retTypeDate(), true);
        $grid->addColumn('dt_est_start', 'Est Start', '80px', $f->retTypeDate(), true);
        $grid->addColumn('dt_est_finish', 'Est Finish', '80px', $f->retTypeDate(), true);
        $grid->addColumn('ds_human_resource_te', 'Human Resource Te', '150px', $f->retTypePickList(), array('model' => 'human_resource_model', 'codeField' => 'cd_human_resource_te'));


        $filters = $fm->retFiltersWithGroup();
        $javascript = $grid->retGrid();

        $trans = array();
        $trans = $this->cdbhelper->retTranslationDifKeys($trans);



        $send = array("javascript" => $javascript,
            "filters" => $filters,
            "filters_java" => $fm->retJavascript()) + $trans;


        $this->load->view("defaultView", $send);
    }

    public function openSchTstForm($cd_project_build_schedule, $project) {
        $grid = $this->w2gridgen;
        $f = $this->cfields;
        $fm = $this->cfiltermaker;

        if (1 == 2) {
            $f = new Cfields();
            $grid = new w2gridgen();
            $fm = new cfiltermaker();
        }


        //get other builds for this project
        
        $rows = $this->prjschmodel->retRetrieveArray(' WHERE "PROJECT_BUILD_SCHEDULE".cd_project = ' . $project, ' ORDER BY COALESCE("PROJECT_MODEL".ds_project_model, \'\'), "PROJECT_BUILD".nr_order');
        
        $copyFrom = array();
        
        foreach ($rows  as $key => $value) {
            if ($value['nr_test_count'] == 0 || $value['cd_project_build_schedule'] == $cd_project_build_schedule) {
                continue;
            }
            $text = $value['ds_project_build_abbreviation'];
            
            if ($value['fl_allow_multiples'] == 1) {
                $text = $text . $value['nr_version'];
            }
            
            if ($value['ds_project_model'] != '') {
                $text = $text . ' - ' . $value['ds_project_model'];
            }
            $text = $text . ' - Available Tests : '.  $value['nr_test_count'];
            
            array_push($copyFrom, array('id' => 'tests_' . $value['cd_project_build_schedule'], 'text' => $text) );
            
            
            
        }

        
        $grid = $this->w2gridgen;
        $f = $this->cfields;
        $fm = $this->cfiltermaker;

        if (1 == 2) {
            $f = new Cfields();
            $grid = new w2gridgen();
            $fm = new cfiltermaker();
        }

        $trans = array();
        $trans = $this->cdbhelper->retTranslationDifKeys($trans);

        // creating toolbar;
        $grid->addCRUDToolbar(false, true, true, false, false);
        $grid->setGridVar('vGridToToolbar');
        $grid->setForceDestroy(false);
        
        if(count($copyFrom)) {
            $grid->addBreakToolbar();
            $idx = $grid->addUserBtnToolbar('copyFrom', 'Copy Lab Tests From', 'fa fa-copy', '');
            foreach ($copyFrom as $key => $value) {
                $grid->addUserBtnToolbar($value['id'], $value['text'], '', $value['text'], $idx);
            }
            
        }
            
            
        $toolbar = $grid->retGridVar();

        $schedulesTests = $this->mainmodel->retRetrieveArray(' WHERE "PROJECT_BUILD_SCHEDULE_TESTS".cd_project_build_schedule = ' . $cd_project_build_schedule);
        $html = $this->makeTestItems($schedulesTests, 'N');

        $this->load->view("schedule/project_build_schedule_tests_form_view", $trans + array('sc' => 'N',
            'toolbar' => $toolbar,
            'action' => 'E',
            'cd_project_build_schedule' => $cd_project_build_schedule,
            'project' => $project,
            'html' => $html
        ));
    }


    public function makeTestItemsOLD($array, $sc) {

        $trans = array('formTrans_cd_project_build_schedule_tests' => 'Code',
            'formTrans_cd_test_type' => 'Type',
            'formTrans_cd_test_item' => 'Item',
            'formTrans_nr_days' => 'Days',
            'formTrans_cd_tests' => 'Procedure',
            'formTrans_ds_specification' => 'Specification',
            'formTrans_ds_sample_description' => 'Description',
            'formTrans_ds_extra_instruction' => 'Instruction',
            'formTrans_fl_witness' => 'Witness',
            'formTrans_ds_test_unit' => 'Unit',
            'formTrans_nr_sample_quantity' => 'Samples',
            'formTrans_nr_charger_quantity' => 'Charger',
            'formTrans_nr_power_pack_quantity' => 'PowerPack',
            'formTrans_nr_accessory_qty' => 'Accessory',
            'formTrans_nr_goal' => 'Goal',
            'formTrans_nr_output' => 'Daily Output',
            'formTrans_fl_eol' => 'EOL',
            'formTrans_dt_start' => 'Agreed',
            //'formTrans_dt_finish' => 'Finish',
            'formTrans_dt_est_start' => 'Planned',
            //'formTrans_dt_est_finish' => 'Est. Finish',
            'formTrans_cd_human_resource_te' => 'TE',
            'formTrans_dt_actual_start' => 'Actual',
            'formTrans_cd_schedule_test_status'=> 'Status',
            'duplicateTooltip' => 'Duplicate this Procedure',
            'deleteTooltip' => 'Delete this Procedure',
            'insertTooltip' => 'Insert new Procedure',
            'formTrans_cd_location' => 'Location',
            'formTrans_cd_test_unit'=> 'Unit',
            'formTrans_ds_test_item'=> 'Test Item'
        );


        $trans = $this->cdbhelper->retTranslationDifKeys($trans);
        $html = '';
        foreach ($array as $key => $value) {
            $html = $html . $this->load->view("schedule/project_build_schedule_tests_row_form_view", array('sc' => $sc,  'showcalendar' => 'N') + $trans + $value, true);
        }

        return $html;
    }

    public function updateDataJsonTestOLD($cd_project_build_schedule, $project) {
        $upd_array = json_decode($_POST['upd']);
        
        
        $fields = array("cd_project_build_schedule_tests" => isset($upd_array->cd_project_build_schedule_tests) ? $upd_array->cd_project_build_schedule_tests : array(),
            "cd_project_build_schedule" => isset($upd_array->cd_project_build_schedule) ? $upd_array->cd_project_build_schedule : array(),
            "cd_test_type" => isset($upd_array->cd_test_type) ? $upd_array->cd_test_type : array(),
            "cd_test_item" => isset($upd_array->cd_test_item) ? $upd_array->cd_test_item : array(),
            "cd_tests" => isset($upd_array->cd_tests) ? $upd_array->cd_tests : array(),
            "ds_specification" => isset($upd_array->ds_specification) ? $upd_array->ds_specification : array(),
            "ds_sample_description" => isset($upd_array->ds_sample_description) ? $upd_array->ds_sample_description : array(),
            "ds_extra_instruction" => isset($upd_array->ds_extra_instruction) ? $upd_array->ds_extra_instruction : array(),
            "fl_witness" => isset($upd_array->fl_witness) ? $upd_array->fl_witness : array(),
            "nr_sample_quantity" => isset($upd_array->nr_sample_quantity) ? $upd_array->nr_sample_quantity : array(),
            "nr_charger_quantity" => isset($upd_array->nr_charger_quantity) ? $upd_array->nr_charger_quantity : array(),
            "nr_power_pack_quantity" => isset($upd_array->nr_power_pack_quantity) ? $upd_array->nr_power_pack_quantity : array(),
            "nr_accessory_qty" => isset($upd_array->nr_accessory_qty) ? $upd_array->nr_accessory_qty : array(),
            "nr_goal" => isset($upd_array->nr_goal) ? $upd_array->nr_goal : array(),
            "nr_output" => isset($upd_array->nr_output) ? $upd_array->nr_output : array(),
            "fl_eol" => isset($upd_array->fl_eol) ? $upd_array->fl_eol : array(),
            "dt_start" => isset($upd_array->dt_start) ? $upd_array->dt_start : array(),
            "dt_finish" => isset($upd_array->dt_finish) ? $upd_array->dt_finish : array(),
            "dt_est_start" => isset($upd_array->dt_est_start) ? $upd_array->dt_est_start : array(),
            "dt_est_finish" => isset($upd_array->dt_est_finish) ? $upd_array->dt_est_finish : array(),
            "dt_actual_start" => isset($upd_array->dt_actual_start) ? $upd_array->dt_actual_start : array(),
            "dt_actual_finish" => isset($upd_array->dt_actual_finish) ? $upd_array->dt_actual_finish : array(),
            "cd_schedule_test_status" => isset($upd_array->cd_schedule_test_status) ? $upd_array->cd_schedule_test_status : array(),
            "cd_human_resource_te" => isset($upd_array->cd_human_resource_te) ? $upd_array->cd_human_resource_te : array()
        );


        $upddata = $this->getCdbhelper()->createGridResultSetFormOrder(array(
            'fields' => $fields,
            //'pkField' => 'cd_project_build_schedule_tests',
            'orderFieldName' => 'recid',
            'indexRSFieldName' => 'cd_project_build_schedule',
            'indexRSFind' => -1,
            'deleteField' => 'cd_test_type'
                )
        );


        $this->getCdbhelper()->trans_begin();

        $error = $this->mainmodel->updateGridData($upddata->upd);
        if ($error != 'OK') {
            $this->getCdbhelper()->trans_rollback();
            $msg = '{"status":' . json_encode($error) . ', "rs":{} }';
            echo($msg);
            return;
        }

        $error = $this->mainmodel->deleteGridData($upddata->del);
        if ($error != 'OK') {
            $this->getCdbhelper()->trans_rollback();
            $msg = '{"status":' . json_encode($error) . ', "rs":{} }';
            echo($msg);
            return;
        }

        
        /*

          $error = $this->buildschchkmodel->updateGridData($gridData);
          if ($error != 'OK') {
          $this->getCdbhelper()->trans_rollback();
          $msg = '{"status":' . json_encode($error) . ', "rs":{} }';
          echo($msg);
          return;
          }
         */

        $this->getCdbhelper()->trans_commit();
        $this->getCdbhelper()->trans_end();

        
        // return of the browser;
        $where = ' AND "PROJECT".cd_project =  ' . $project;
        $data = $this->prjmodel->retRetrieveGridArray($where, $this->prjmodel->orderByDefault);
        $send = $this->getScheduleData($data);
        
        
        $retResult = $this->mainmodel->retRetrieveArray(' WHERE "PROJECT_BUILD_SCHEDULE_TESTS".cd_project_build_schedule = ' . $cd_project_build_schedule);
        $html = $this->makeTestItems($retResult , 'N');
        
        $msg = '{"status":' . json_encode($error) . ', "html": ' . json_encode($html) .  ', "gridData": ' . json_encode($send, JSON_NUMERIC_CHECK) . '}';

        echo $msg;
    }

        function getScheduleData($data) {

        foreach ($data as $key => $value) {
            $sch = $this->prjschmodel->getSchedules($value['cd_project'], $value['cd_project_model']);

            $data[$key]['sch'] = $sch;
        }

        return $data;
    }
    
    
    
    
}
