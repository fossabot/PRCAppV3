<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class project_build_schedule extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("schedule/project_build_schedule_model", "mainmodel", TRUE);
        $this->load->model("tti/project_model", "prjmodel", TRUE);
        $this->load->model("schedule/project_build_model", "buildmodel", TRUE);
        $this->load->model("schedule/project_build_schedule_checkpoints_model", "buildschchkmodel", TRUE);
        $this->load->model("schedule/project_build_schedule_tests_model", "buildschtstmodel", TRUE);
        $this->load->model("schedule/project_build_checkpoints_model", "buildchkmodel", TRUE);
        $this->load->model("schedule/project_build_schedule_tests_workers_model", "workersmodel", TRUE);
        $this->load->model("tr/tr_mssql_model", "trdatamodel", TRUE);
        
    }

    public function index() {

        parent::checkMenuPermission();


        $grid = $this->w2gridgen;
        $f = $this->cfields;
        $fm = $this->cfiltermaker;
        $ctabs = $this->ctabs;


        if (1 == 2) {
            $f = new Cfields();
            $grid = new w2gridgen();
            $fm = new cfiltermaker();
            $ctabs = new ctabs();
        }

        $ctabs->addTab('Default View', 'tab_default');
        $ctabs->addTab('Gantt', 'tab_grant');
        $ctabs->setMainDivId('mainTabsDiv');
        $ctabs->setContentDivId('tab_default_div');

        $fm = $this->cfiltermaker;

        $fm->addFilter('filter_1', 'Project Name', array('selector' => 'filter_1','fieldname' => '"PROJECT".ds_project', 'likeIlike'=> 'I'));

        $fm->addPickListFilter('PRC PM', 'filter_2', 'human_resource_controller', '"PROJECT".cd_human_resource_prc_pm');
        $fm->addPickListFilter('PRC ENG', 'filter_3', 'human_resource_controller', '"PROJECT".cd_human_resource_eng');
        $fm->addPickListFilter('Tool Type', 'filter_6', 'tti/project_tool_type', '"PROJECT".cd_project_tool_type');
        $fm->addSimpleFilterUpper('TTi #', 'filter_7', '"PROJECT"ds_tti_project');
        $fm->addSimpleFilterUpper('MET #', 'filter_8', '"PROJECT".ds_met_project');
        $fm->addSimpleFilterUpper('TTi Model#', 'filter_9', '"PROJECT_MODEL".ds_tti_project_model');
        $fm->addSimpleFilterUpper('MET Model#', 'filter_10', '"PROJECT_MODEL".ds_met_project_model');

        //$this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addBreakToolbar();
        $grid->addRetriveToolbar();
        $grid->addHideToolbar();
        $grid->setToolbarSearch(true);
        $grid->setFilterPresetId('prj_build_schedule_browse');
        $grid->setCRUDController("schedule/project_build_schedule");

        $grid->addColumnKey();

        $grid->addColumn('ds_project', 'Project', '150px', $f->retTypeStringAny(), false);
        $grid->addHiddenColumn('ds_human_resource_prc_pm', 'PRC PM', '150px', $f->retTypeStringAny(), false);
        $grid->addHiddenColumn('ds_human_resource_eng', 'PRC ENG', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_tti_project', 'TTi #', '80px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_met_project', 'MET #', '80px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_project_tool_type', 'Tool Type', '150px', $f->retTypeStringAny(), false);

        $grid->addColumn('ds_project_model', 'Project Model', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_tti_project_model', 'TTi Model #', '80px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_met_project_model', 'MET Model #', '80px', $f->retTypeStringAny(), false);

        $grid->addColumn("ds_graphic", "Builds", '5000px', $f->retTypeStringAny(), false);
        $grid->setColumnRenderFunc("ds_graphic", "dsMainObject.renderBuilds");

        $grid->setRowHeight(120);

        $grid->setGridDivName('tab_default_div');

        $filters = $fm->retFiltersWithGroup();
        $javascript = $grid->retGrid();


        $trans = array('noinfo' => 'No Information',
            'estdate' => 'Estimated Date',
            'tstLabel' => 'Tests',
            'trLabel' => 'TRs',
            'editSchTitle' => 'Schedule Maintenance for',
            'addBtn' => 'Add new Build',
            'tstBtn' => 'Manage Tests',
            'editBtn' => 'Edit Build Data',
            'editLabTitle' => 'Tests For');

        $trans = $this->cdbhelper->retTranslationDifKeys($trans);

        $send = array("javascript" => $javascript,
            "filters" => $filters,
            'tab' => $ctabs->retTabs(),
            "filters_java" => $fm->retJavascript()) + $trans;


        $this->load->view("schedule/project_build_schedule_view", $send);
    }

    public function retrieveGridJson($retrOpt = array()) {

        if (!$this->logincontrol->isProperLogged(false)) {
            echo ( '{"logged": "N", "resultset": [] }' );
            return;
        }

        $where = $this->getWhereToFilter();

        if (isset($retrOpt['whereToAdd'])) {
            $where = $where . $retrOpt['whereToAdd'];
        }


        $data = $this->prjmodel->retRetrieveGridArray($where, $this->prjmodel->orderByDefault);
        $send = $this->getScheduleData($data);
/*
        // grant generation - Start
        $gantt_action = array();

        foreach ($send as $keyProj => $valueProj) {

            array_push($gantt_action, array(
                'pID' => $valueProj['recid'],
                'pName' => $valueProj['ds_project'] . '/' . $valueProj['ds_project_model'],
                'pStart' => '',
                'pEnd' => '',
                'pClass' => 'ggroupblack',
                'pLink' => '',
                'pMile' => 0,
                'pRes' => '',
                'pComp' => 0,
                'pGroup' => 1,
                'pParent' => 0,
                'pOpen' => 1,
                'pDepend' => ''
            ));

            foreach ($valueProj['sch'] as $keySch => $valueSch) {
                if ($valueSch['cd_project_build_schedule'] == -1) {
                    continue;
                }

                $eb = $valueSch['ds_project_build_abbreviation'];
                if ($valueSch['fl_by_model'] == 'Y') {
                    $eb = $eb . ' ' . $valueSch['nr_version'];
                }

                array_push($gantt_action, array(
                    'pID' => $valueSch['nr_unique'],
                    'pName' => $eb,
                    'pStart' => $valueSch['ds_est_start'],
                    'pEnd' => $valueSch['ds_est_finish'],
                    'pClass' => 'gtaskblue',
                    'pLink' => '',
                    'pMile' => 0,
                    'pRes' => '',
                    'pComp' => 0,
                    'pGroup' => 1,
                    'pParent' => $valueProj['recid'],
                    'pOpen' => 1,
                    'pDepend' => ''
                ));

                // go to the tests:
                $this->buildschtstmodel->changeUnique($valueSch['nr_unique']);
                $tsts = $this->buildschtstmodel->retRetrieveGridArray(' and "PROJECT_BUILD_SCHEDULE_TESTS".cd_project_build_schedule = ' . $valueSch['cd_project_build_schedule'] . ' and "PROJECT_BUILD_SCHEDULE_TESTS".dt_est_start IS NOT NULL', ' ORDER BY "PROJECT_BUILD_SCHEDULE_TESTS".dt_est_start');
                
                foreach ($tsts as $keyTst => $valueTst) {
                    array_push($gantt_action, array(
                        'pID' => $valueTst['nr_unique'],
                        'pName' => $valueTst['ds_test_type'] . ' - ' . $valueTst['ds_tests'],
                        'pStart' => $valueTst['ds_est_start'],
                        'pEnd' => $valueTst['ds_est_finish'],
                        'pClass' => 'gtaskred',
                        'pLink' => '',
                        'pMile' => 0,
                        'pRes' => '',
                        'pComp' => 0,
                        'pGroup' => 0,
                        'pParent' => $valueSch['nr_unique'],
                        'pOpen' => 0,
                        'pDepend' => ''
                    ));
                }
            }
        }


        $xml = $this->getCdbhelper()->array_to_xml($gantt_action, 'project', 'task');
 
 */
        // grant generation - END

        echo('{"resultset": ' . json_encode($send, JSON_NUMERIC_CHECK) . ', "logged":"Y", "xml": ' . json_encode('', JSON_NUMERIC_CHECK) . '}');
    }

    function getScheduleData($data) {

        foreach ($data as $key => $value) {
            $sch = $this->mainmodel->getSchedules($value['cd_project'], $value['cd_project_model']);

            $data[$key]['sch'] = $sch;
        }

        return $data;
    }

    public function callSchForm($cd_project_build_schedule, $cd_project_build, $cd_project, $cd_project_model) {

        $grid = $this->w2gridgen;
        $f = $this->cfields;
        $fm = $this->cfiltermaker;

        if (1 == 2) {
            $f = new Cfields();
            $grid = new w2gridgen();
            $fm = new cfiltermaker();
        }

        $build = $this->buildmodel->retRetrieveArray(' WHERE "PROJECT_BUILD".cd_project_build = ' . $cd_project_build)[0];
        $hasChk = $build['fl_has_checkpoints'];
        $buildschcheckpoints = array();

        $buildtests = $this->buildschtstmodel->retRetrieveArray(' WHERE "PROJECT_BUILD_SCHEDULE_TESTS".cd_project_build_schedule = ' . $cd_project_build_schedule);
        $html = $this->makeTestItems($buildtests, 'N');


        if ($cd_project_build_schedule == -1) {
            $sc = "Y";
            $line = $this->mainmodel->retRetrieveEmptyNewArray();
            // set the requester as the logged user
            $line[0]['cd_project_model'] = $cd_project_model;
            $line[0]['cd_project'] = $cd_project;
            $line[0]['cd_project_build'] = $cd_project_build;
            $cd_project_build_schedule = $line[0]['recid'];
            // availabble checkpoints
            if ($hasChk == 1) {
                $buildschcheckpoints = $this->getCheckPoints($cd_project_build_schedule, $buildschcheckpoints);
            }

            $action = 'I';
        } else {
            $sc = "N";
            $line = $this->mainmodel->retRetrieveArray(' WHERE "PROJECT_BUILD_SCHEDULE".cd_project_build_schedule= ' . $cd_project_build_schedule);
            $action = 'E';

            $buildschcheckpoints = $this->buildschchkmodel->retRetrieveArray(' WHERE "PROJECT_BUILD_SCHEDULE_CHECKPOINTS".cd_project_build_schedule = ' . $cd_project_build_schedule, ' ORDER BY nr_order ');

            // even if NOW the Setup says don't have checkpoint, if in the table we have, I will show. Obviously

            if ($hasChk == 1) {
                $buildschcheckpoints = $this->getCheckPoints($cd_project_build_schedule, $buildschcheckpoints);
            } elseif (count($buildschcheckpoints) > 0) {
                $hasChk = 1;
            }
        }

        // creating toolbar;
        $grid->addCRUDToolbar(false, false, true, false, false);
        $grid->setGridVar('vGridToToolbar');
        $grid->setForceDestroy(false);
        $toolbar = $grid->retGridVar();

        // grid for checkpoints
        $grid->resetGrid();
        $grid->setForceDestroy(true);
        $grid->showToolbar(false);
        $grid->showFooter(false);
        $grid->setGridDivName('gridCheckPointDiv');
        $grid->setGridName('gridCheckPoint');
        $grid->addColumnKey();
        $grid->addColumn('ds_project_build_checkpoints', 'Checklist', '60%', $f->retTypeStringAny(), false);
        $grid->addColumn('dt_deadline', 'Deadline', '80px', $f->retTypeDate(), true);
        $grid->addColumn('dt_done', 'Actual Date', '80px', $f->retTypeDate(), true);
        $grid->addColumn('ds_comment', 'Notes', '40%', $f->retTypeTextPL(), true);
        $grid->addHiddenColumn('nr_order', 'order', '80px', $f->retTypeInteger(), true);

        $grid->addRecords(json_encode($buildschcheckpoints, JSON_NUMERIC_CHECK));
        $gridCHK = $grid->retGrid();

        // grid for the schedule
        $grid->resetGrid();
        $grid->addColumnKey();
        //$grid->addColumn('ds_project_build_schedule_tests', 'Project Build Schedule Tests', '150px', $f->retTypePickList(), array('model' => 'schedule/project_build_schedule_tests_model', 'codeField' => 'cd_project_build_schedule_tests'));
        $grid->addColumn('dt_start', 'Start', '80px', $f->retTypeDate(), true);
        $grid->addColumn('dt_finish', 'Finish', '80px', $f->retTypeDate(), true);
        $grid->addColumn('nr_workers', 'Workers', '150px', $f->retTypeInteger(), true);
        $grid->setGridName('gridDates');
        $grid->setGridVar('vGridDates');

        $workers = $this->workersmodel->retRetrieveGridJson(' WHERE "PROJECT_BUILD_SCHEDULE_TESTS".cd_project_build_schedule = ' . $cd_project_build_schedule);
        $grid->addRecords($workers);

        $gridDates = $grid->retGridVar();


        $trans = array(
            'formTrans_nr_version' => 'Version',
            'formTrans_dt_est_start' => 'Estimated',
            'formTrans_ds_comments' => 'Comments',
            'checklist' => 'Checklists',
            'tests' => 'Test Estimated Schedule'
        );
        $trans = $this->cdbhelper->retTranslationDifKeys($trans);

        $this->load->view("schedule/project_build_schedule_form_view", $trans + $line[0] + array('sc' => $sc,
            'toolbar' => $toolbar,
            'action' => $action,
            'hasCHK' => $hasChk,
            'gridCHK' => $gridCHK,
            'gridDates' => $gridDates,
            'htmlTests' => $html));
    }

    public function getCheckPoints($cd_project_build_schedule, $buildschcheckpoints) {
        $code = -10;

        $checkpoints = $this->buildchkmodel->retRetrieveArray(' WHERE dt_deactivated IS NULL', 'ORDER BY nr_order ');

        foreach ($checkpoints as $key => $value) {
            $exists = false;

            foreach ($buildschcheckpoints as $key_sch => $value_sch) {
                if ($value_sch['cd_project_build_checkpoints'] == $value['recid']) {
                    $exists = true;
                    break;
                }
            }

            if (!$exists) {
                array_push($buildschcheckpoints, array("recid" => $code,
                    "cd_project_build_schedule" => $cd_project_build_schedule,
                    "cd_project_build_checkpoints" => $value['cd_project_build_checkpoints'],
                    "ds_project_build_checkpoints" => $value['ds_project_build_checkpoints'],
                    'nr_order' => $value['nr_order']
                ));
                $code --;
            }
        }
        return $buildschcheckpoints;
    }

    public function updateDataJsonForm() {
        $upd_array = json_decode($_POST['upd']);
        $prj = $upd_array->cd_project;
        $sch = $upd_array->recid;
        $gridData = array();

        $decoded = json_decode($_POST['additionalData'], true);

        if (isset($decoded['gridCheckPoint'])) {
            $gridData = $decoded['gridCheckPoint'];
        }

        $gridDates = $decoded['gridDates'];

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
            "dt_est_start" => isset($upd_array->dt_est_start_tst) ? $upd_array->dt_est_start_tst : array(),
            "dt_est_finish" => isset($upd_array->dt_est_finish_tst) ? $upd_array->dt_est_finish_tst : array(),
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

        $arraysend = array($upd_array);
        $this->getCdbhelper()->trans_begin();


        $error = $this->mainmodel->updateGridData($arraysend);
        if ($error != 'OK') {
            $this->getCdbhelper()->trans_rollback();
            $msg = '{"status":' . json_encode($error) . ', "rs":{} }';
            echo($msg);
            return;
        }


        $error = $this->buildschchkmodel->updateGridData($gridData);
        if ($error != 'OK') {
            $this->getCdbhelper()->trans_rollback();
            $msg = '{"status":' . json_encode($error) . ', "rs":{} }';
            echo($msg);
            return;
        }

        $error = $this->workersmodel->updateGridData($gridDates);
        if ($error != 'OK') {
            $this->getCdbhelper()->trans_rollback();
            $msg = '{"status":' . json_encode($error) . ', "rs":{} }';
            echo($msg);
            return;
        }

        
        
        
        $error = $this->buildschtstmodel->updateGridData($upddata->upd);
        if ($error != 'OK') {
            $this->getCdbhelper()->trans_rollback();
            $msg = '{"status":' . json_encode($error) . ', "rs":{} }';
            echo($msg);
            return;
        }


        $this->getCdbhelper()->trans_commit();
        $this->getCdbhelper()->trans_end();



        $retResult = $this->mainmodel->retRetrieveGridJsonForm($arraysend[0]->recid);
        $workers = $this->workersmodel->retRetrieveGridJson(' WHERE "PROJECT_BUILD_SCHEDULE_TESTS".cd_project_build_schedule = ' . $sch);


        // get the project informationto update the main grid.
        $where = ' AND "PROJECT".cd_project =  ' . $prj;
        $data = $this->prjmodel->retRetrieveGridArray($where, $this->prjmodel->orderByDefault);
        $send = $this->getScheduleData($data);

        // busco o gridChk;

        $buildschcheckpoints = $this->buildschchkmodel->retRetrieveArray(' WHERE "PROJECT_BUILD_SCHEDULE_CHECKPOINTS".cd_project_build_schedule = ' . $sch, ' ORDER BY nr_order ');

        $buildschcheckpoints = $this->getCheckPoints($sch, $buildschcheckpoints);
        $msg = '{"status":' . json_encode($error) . ', "rs":' . $retResult . ', "gridData": ' . json_encode($send, JSON_NUMERIC_CHECK) . ', "checkpoints": ' . json_encode($buildschcheckpoints) . ', "dates": ' . $workers . ' }';

        echo $msg;
    }

    public function makeTestItems($array, $sc) {

        $trans = array('formTrans_cd_project_build_schedule_tests' => 'Code',
            'formTrans_cd_test_type' => 'Type',
            'formTrans_cd_test_item' => 'Item',
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
            'formTrans_dt_start' => 'Agreed Start',
            'formTrans_dt_finish' => 'Agreed Finish',
            'formTrans_dt_est_start' => 'Planned Start',
            'formTrans_dt_est_finish' => 'Planned Finish',
            'formTrans_cd_human_resource_te' => 'TE',
            '$formTrans_nr_days' => 'Days'
        );


        
        
        $transGo = $this->cdbhelper->retTranslationDifKeys($trans);
        //die(print_r($trans));

        $html = '';
        foreach ($array as $key => $value) {
            $array = array('sc' => $sc) + $transGo + $value;
            $html = $html . $this->load->view("schedule/project_build_schedule_calendar_tests_row_form_view",$array  , true);
            //$html = $html . $this->load->view("schedule/project_build_schedule_calendar_test_type_row_form_view", array('sc' => $sc) + $trans + $value, true);
        }

        return $html;

    }
    
        

}
