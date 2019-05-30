<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class project extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("tti/project_model", "mainmodel", TRUE);
        $this->load->model("brand_model", "brandmodel", TRUE);
        $this->load->model("location_model", "locationmodel", TRUE);
        $this->load->model("tti/project_model_model", "prjmodelmodel", TRUE);
        $this->load->model("schedule/project_build_schedule_model", "schmodel", TRUE);
        $this->load->model("schedule/project_build_model", "buildmodel", TRUE);
        $this->load->model("schedule/project_build_schedule_checkpoints_model", "buildschchkmodel", TRUE);
        $this->load->model("schedule/project_build_checkpoints_model", "buildchkmodel", TRUE);
        $this->load->model("schedule/project_build_schedule_tests_model", "buildschtstmodel", TRUE);
        $this->load->model("schedule/project_build_schedule_tests_purchase_items_model", "purchasemodel", TRUE);
        $this->load->model("schedule/project_build_schedule_comments_model", "schcomments", TRUE);
        $this->load->model("tti/project_user_roles_model", "projuserroles", TRUE);
        $this->load->model("tti/project_comments_model", "projmaincomments", TRUE);
        $this->load->model("schedule/project_build_schedule_tests_workers_model", "workersmodel", TRUE);
        $this->load->model('schedule/project_build_schedule_tests_wo_model', 'womodel');
        $this->load->model('docrep/document_repository_model', 'docrepmodel');
        $this->load->model('docrep/project_model_document_repository_model', 'prjdocrepmodel');
        $this->load->model('docrep/project_build_schedule_document_repository_model', 'prjbuilddocrepmodel');
        $this->load->model('tti/project_status_model', 'prjstatus');
        $this->load->model('tti/project_comments_cc_model', 'prjcommcc');
        $this->load->model('tti/project_build_schedule_comments_cc_model', 'prjbuildcommcc');
        $this->load->model('document_repository_model', 'docrepmodel');
        $this->load->model("tr/tr_wi_model", "trwimodel", TRUE);

        $this->filterByCode = '{}';


        $this->trans = array(
            'goto' => 'Go To',
            'newbuild' => 'New Build',
            'expand' => 'Expand',
            'collapse' => 'Collapse',
            'formTrans_ds_project' => 'Project Name',
            'formTrans_cd_human_resource_prc_pm' => 'PRC PM',
            'formTrans_cd_human_resource_eng' => 'PRC ENG',
            'formTrans_ds_tti_project' => 'TTi Project #',
            'formTrans_ds_met_project' => 'MET Project #',
            'formTrans_cd_project_status' => 'Status',
            'formTrans_cd_project_tool_type' => 'Tool Type',
            'formTrans_cd_project_product' => 'Product',
            'formTrans_ds_project_model' => 'Model Description',
            'formTrans_ds_tti_project_model' => 'TTi Model #',
            'formTrans_ds_met_project_model' => 'MET Model #',
            'projectTitle' => 'Project Details',
            'errorDel' => 'Must have at least one model. Cannot Delete',
            'testrep' => 'Open Test Report',
            'formTrans_nr_version' => 'Version',
            'formTrans_cd_human_resource_te' => 'TE',
            'formTrans_dt_est_start' => 'Planned',
            'formTrans_nr_priority' => 'Priority',
            'formTrans_nr_headcount_requested_day' => 'Required HC',
            //'formTrans_nr_headcount_requested_night' => 'Headcount Requested Night',
            'formTrans_nr_headcount_allocated_day' => 'Allocated HC',
            //'formTrans_nr_headcount_allocated_night' => 'Headcount Allocated Night',
            'formTrans_dt_start' => 'Agreed',
            'formTrans_ds_comments' => 'Comments',
            'checklist' => 'Checklists',
            'tests' => 'Test Estimated Schedule',
            'missingEstDates' => 'Missing',
            'missingAgreedDates' => 'Missing',
            'formTrans_cd_project_power_type' => 'Category',
            'formTrans_cd_project_voltage' => 'Voltage',
            'formTrans_cd_department' => 'Department',
            'formTrans_cd_brand' => 'Brand',
            'formTrans_fl_confidential' => 'Confidential',
            'confidentialtitle' => '-- CONFIDENTIAL PROJECT --',
            'NoAttachment' => 'No Attachment',
            'withAttachment' => 'With Attachment',
            'Planning' => 'Planning',
            'TRs' => 'TRs'
        );

        $this->trans = $this->cdbhelper->retTranslationDifKeys($this->trans);



        $this->transTst = array('formTrans_cd_project_build_schedule_tests' => 'Code',
            'formTrans_cd_test_type' => 'Type',
            'formTrans_cd_test_item' => 'Item',
            'formTrans_cd_tests' => 'Procedure',
            'formTrans_nr_days' => 'Days',
            'formTrans_work_orders' => 'Work Orders',
            'formTrans_ds_specification' => 'Specification',
            'formTrans_ds_sample_description' => 'Description',
            'formTrans_ds_extra_instruction' => 'Instruction',
            'formTrans_fl_witness' => 'Witness',
            'formTrans_cd_tr_wi_data' => 'TR WI',
            'formTrans_ds_test_unit' => 'Unit',
            'formTrans_nr_sample_quantity' => 'Samples',
            'formTrans_nr_charger_quantity' => 'Charger',
            'formTrans_ds_wi' => 'WI/Section',
            'formTrans_ds_wi_section' => 'Section',
            'formTrans_nr_power_pack_quantity' => 'PowerPack',
            'formTrans_nr_accessory_qty' => 'Accessory',
            'formTrans_nr_goal' => 'Goal',
            'formTrans_nr_output' => 'Daily Output',
            'formTrans_fl_eol' => 'EOL',
            'formTrans_dt_start' => 'Agreed',
            //'formTrans_dt_finish' => 'Finish',
            'formTrans_dt_est_start' => 'Planned',
            'formTrans_nr_priority' => 'Priority',
            'formTrans_nr_headcount_requested_day' => 'Required HC',
            //'formTrans_nr_headcount_requested_night' => 'Headcount Requested Night',
            'formTrans_nr_headcount_allocated_day' => 'Allocated HC',
            //'formTrans_nr_headcount_allocated_night' => 'Headcount Allocated Night',
            //'formTrans_dt_est_finish' => 'Est. Finish',
            'formTrans_cd_human_resource_te' => 'TE',
            'formTrans_dt_actual_start' => 'Actual',
            'formTrans_cd_schedule_test_status' => 'Status',
            'duplicateTooltip' => 'Duplicate this Procedure',
            'shiftTooltip' => 'Shift Agreed Date for this item',
            'deleteTooltip' => 'Delete this Procedure',
            'insertTooltip' => 'Insert new Procedure',
            'formTrans_cd_location' => 'Location',
            'formTrans_cd_test_unit' => 'Unit',
            'formTrans_ds_test_item' => 'Test Item'
        );


        $this->transTst = $this->cdbhelper->retTranslationDifKeys($this->transTst);
    }

    public function index() {

        parent::checkMenuPermission();

        $grid = $this->w2gridgen;
        $f = $this->cfields;
        $fm = $this->cfiltermaker;
        $ctabs = $this->ctabs;
        $ctabs->ResetTabs();

        if (1 == 2) {
            $f = new Cfields();
            $grid = new w2gridgen();
            $fm = new cfiltermaker();
            $ctabs = new ctabs();
        }

        $canChangeDetails = $this->getCdbhelper()->getUserPermission('fl_change_project_main_details');


        $ctabs->addTab('Browse', 'tab_browse');
        $ctabs->addTab('Details', 'tab_detail');
        $ctabs->addTab('Timeline', 'tab_gannt');
        $ctabs->setMainDivId('mainTabsDiv');
        $ctabs->setContentDivId('tab_browse_div');

        $fm->addPickListFilter('Status', 'filter_25', 'tti/project_status', '"PROJECT_MODEL".cd_project_status');

        $fm->addFilter('filter_1', 'Project Name', array('selector' => 'filter_1', 'fieldname' => '"PROJECT".ds_project', 'likeIlike' => 'I', 'startWith' => false));
        $fm->addFilter('filter_8', 'Project#', array('fieldname' => "COALESCE(\"PROJECT\".ds_met_project, '') || '-' || COALESCE(\"PROJECT\".ds_tti_project, '') || '-'", 'likeIlike' => 'I', 'startWith' => false));
        $fm->addFilter('filter_10', 'Model#', array('fieldname' => "COALESCE(\"PROJECT_MODEL\".ds_met_project_model, '') || '-' || COALESCE(\"PROJECT_MODEL\".ds_tti_project_model, '') || '-'", 'likeIlike' => 'I', 'startWith' => false));

        $fm->addPickListFilter('Product', 'filter_12', 'tti/project_product', '"PROJECT".cd_project_product');
        $fm->addPickListFilter('Category', 'filter_20', 'tti/project_power_type', '"PROJECT".cd_project_power_type');
        $fm->addPickListFilter('Tool Type', 'filter_6', 'tti/project_tool_type', '"PROJECT".cd_project_tool_type');

        $fm->addPickListFilter('Voltage', 'filter_11', 'tti/project_voltage', '"PROJECT_MODEL".cd_project_voltage');

        $fm->addPickListFilterExists("User", "human_resource_controller", "filter_2", "PROJECT_MODEL", "cd_project_model", "PROJECT_USER_ROLES", "cd_human_resource", "cd_project_model", false);

        //$fm->addPickListFilter('User', 'filter_2', 'human_resource_controller', '"PROJECT".cd_human_resource_prc_pm');

        $fm->addPickListFilter('Department', 'filter_15', 'job_department', '"PROJECT".cd_department');
        $fm->addPickListFilter('Brand', 'filter_21', 'brand', '"PROJECT".cd_brand');

        $fm->addSimpleFilterUpper('TR #', 'filter_tr', '"TR_TEST_REQUEST".ds_tr_number');
        $fm->addFilterNumber('Work Order #', 'filter_Wo', '"TR_TEST_REQUEST_WORK_ORDER".nr_work_order', '10.2', '', '');


        $fixed = array(array('desc' => 'YES', 'sql' => ' AND EXISTS ( SELECT 1 FROM "PROJECT_USER_ROLES" x where x.cd_human_resource = ' . $this->session->userdata('cd_human_resource') . ' AND x.cd_project_model = "PROJECT_MODEL".cd_project_model )'));
        $fm->addFilter('filter_only_mine', 'Only My Projects', array('plFixedSelect' => $fixed));


        $this->setGridParser();
        $grid->setSingleBarControl(true);
        if ($canChangeDetails == 'Y') {
            $grid->addInsToolbar();
            $grid->addEditToolbar();
        }


        $grid->addCRUDToolbar(true, false, false, false, true);

        $grid->setToolbarSearch(true);
        $grid->setCRUDController("tti/project");
        $grid->setFilterPresetId('projectbrowse');
        $grid->addColumnKey();
        $grid->addColumn('ds_project_status', 'Status', '80px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_department', 'Department', '100px', $f->retTypeStringAny(), false);


        $grid->addColumn('ds_met_project', 'MET Project #', '100px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_met_project_model', 'MET Model #', '100px', $f->retTypeStringAny(), false);

        $grid->addColumn('ds_tti_project', 'TTi Project #', '100px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_tti_project_model', 'TTi Model #', '100px', $f->retTypeStringAny(), false);

        $grid->addColumn('ds_project', 'Project Name', '150px', $f->retTypeStringAny(), false);



        $grid->addColumn('ds_project_model', 'Model Description', '150px', $f->retTypeStringAny(), false);

        $grid->addColumn('fl_confidential', 'Confidential', '80px', $f->retTypeCheckBox(), false);

        $grid->addColumn('ds_project_product', 'Product', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_project_power_type', 'Category', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_project_tool_type', 'Tool Type', '150px', $f->retTypeStringAny(), false);

        $grid->addColumn('ds_project_voltage', 'Voltage', '80px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_update_formatted', 'Last Change', '120px', $f->retTypeStringAny(), false);



        $filters = $fm->retFiltersWithGroup();

        $grid->addRecords($this->mainmodel->retRetrieveGridJson('', ' ORDER BY ds_update_formatted_order DESC '));

        $grid->setGridDivName('tab_browse_div');

        $javascript = $grid->retGrid();


        $trans = array('editProjTitle' => 'Edit Project');
        $trans = $this->cdbhelper->retTranslationDifKeys($trans);

        $send = array("javascript" => $javascript,
                "filters" => $filters,
                'tab' => $ctabs->retTabs(),
                "filters_java" => $fm->retJavascript()) + $trans;


        $this->load->view("tti/project_view", $send);
    }

    public function getPrjInfo($field, $data) {

        $info = $this->mainmodel->retRetrieveArray(" AND $field = $data", " ORDER BY cd_project_model ");

        $ret = array('data' => $info);

        echo(json_encode($ret, JSON_NUMERIC_CHECK));
    }

    public function callPrjForm($cd_project) {

        $grid = $this->w2gridgen;
        $f = $this->cfields;
        $fm = $this->cfiltermaker;

        if (1 == 2) {
            $f = new Cfields();
            $grid = new w2gridgen();
            $fm = new cfiltermaker();
        }


        $fl_can_delete_project = $this->getCdbhelper()->getUserPermission('fl_can_delete_project');

        if ($cd_project == -1) {
            $sc = "Y";
            $brandDefault = $this->getCdbhelper()->getSystemParameters('DEFAULT_BRAND_FOR_PROJECT');
            $brandarr = $this->brandmodel->retRetrieveArray('WHERE "BRAND".cd_brand = ' . $brandDefault)[0];



            $locationDefault = $this->getCdbhelper()->getSystemParameters('DEFAULT_LOCATION_FOR_PROJECT');
            $locationarr = $this->locationmodel->retRetrieveArray('WHERE "LOCATION".cd_location = ' . $locationDefault)[0];


            $line = $this->mainmodel->retRetrieveEmptyNewArray($this->mainmodel->retrOptionsOnlyProject);

            $status = $this->prjstatus->retRetrieveArray("WHERE fl_default = 'Y' ");


            // set the requester as the logged user
            $cd_project = $line[0]['recid'];
            $line[0]['cd_project'] = $cd_project;
            $line[0]['fl_draft'] = 1;
            $line[0]['cd_brand'] = $brandarr['recid'];
            $line[0]['ds_brand'] = $brandarr['ds_brand'];

            // add basic model
            $mddata = array(array(
                'recid' => $this->prjmodelmodel->getNextCode(),
                'ds_project_model' => 'TBD',
                'cd_project' => $cd_project
            ));

            if (count($status) > 0) {
                $mddata[0]['cd_project_status'] = $status[0]['recid'];
                $mddata[0]['ds_project_status'] = $status[0]['ds_project_status'];
            }

            $mddata = json_encode($mddata, JSON_NUMERIC_CHECK);
            $action = 'I';
        } else {
            $sc = "N";
            $line = $this->mainmodel->retRetrieveArray(' WHERE "PROJECT".cd_project = ' . $cd_project, ' ORDER BY 1 ', $this->mainmodel->retrOptionsOnlyProject);
            $mddata = $this->prjmodelmodel->retRetrieveGridJson(' WHERE "PROJECT_MODEL".cd_project = ' . $cd_project);
            $action = 'E';
        }
        //$mddata = '{}';
        // creating toolbar;
        $grid->addUpdToolbar();
        if ($fl_can_delete_project == 'Y') {
            $grid->addBreakToolbar();
            $grid->addDelToolbar();
        }
        //$grid->addCRUDToolbar(false, false, true, , false);
        $grid->setGridVar('vGridToToolbarPrj');
        $grid->setForceDestroy(false);
        $toolbar = $grid->retGridVar();

        // grid for checkpoints
        $grid->resetGrid();
        $grid->setGridToolbarFunction("dsFormPrjObject.ToolbarGrid");

        $grid->setForceDestroy(true);
        $grid->showToolbar(true);
        $grid->addCRUDToolbar(false, true, false, true, false);
        $grid->showFooter(false);
        $grid->setGridDivName('gridPrjModelDiv');
        $grid->setGridName('gridPrjModel');
        $grid->setCRUDController("tti/project_model");

        $grid->addColumnKey();

        $grid->addColumn('ds_project_status', 'Status', '80px', $f->retTypePickList(), array('model' => 'tti/project_status_model', 'codeField' => 'cd_project_status'));
        $grid->addColumn('ds_project_model', 'Description', '100%', $f->retTypeStringAny(), array('limit' => '150'));
        $grid->addColumn('ds_tti_project_model', 'TTi Model#', '120px', $f->retTypeStringUpper(), array('limit' => '64'));
        $grid->addColumn('ds_met_project_model', 'MET Model#', '120px', $f->retTypeStringUpper(), array('limit' => '64'));
        $grid->addColumn('ds_project_voltage', 'Voltage', '80px', $f->retTypePickList(), array('model' => 'tti/project_voltage_model', 'codeField' => 'cd_project_voltage'));


        $grid->addRecords($mddata);
        $PrjModelGrid = $grid->retGrid();

        $trans = array(
            'formTrans_ds_project' => 'Project',
            'formTrans_cd_human_resource_prc_pm' => 'PRC PM',
            'formTrans_cd_human_resource_eng' => 'PRC ENG',
            'formTrans_ds_tti_project' => 'TTi #',
            'formTrans_ds_met_project' => 'MET #',
            'formTrans_cd_project_tool_type' => 'Tool Type',
            'formTrans_cd_project_product' => 'Product',
            'tests' => 'Test Estimated Schedule',
            'modelTitle' => 'Models',
            'formTrans_cd_project_power_type' => 'Category',
            'formTrans_cd_project_voltage' => 'Voltage',
            'formTrans_cd_department' => 'Department',
            'formTrans_cd_brand' => 'Brand',
            'formTrans_fl_confidential' => 'Confidential',
            'errorDel' => 'Must have at least one model. Cannot Delete',
            'delprj' => 'Confirm Delete the WHOLE PROJECT ?'
        );

        $trans = $this->cdbhelper->retTranslationDifKeys($trans);

        $this->load->view("tti/project_form_view", $trans + $line[0] + array('sc' => $sc,
                'toolbar' => $toolbar,
                'action' => $action,
                'PrjModelGrid' => $PrjModelGrid));
    }

    public function updateDataJsonForm() {
        $upd_array = json_decode($_POST['upd']);
        $prj = $upd_array->recid;
        $cdmodel = 0;
        $timestamp = $this->getCdbhelper()->getDBTimeStamp();
        $timesampFormatted = $this->getCdbhelper()->getDBTimeStampFormatted();

        // I force sending the model when changing anything related to Build. I need to create a new one.
        if (isset($upd_array->cd_project_model)) {
            $cdmodel = $upd_array->cd_project_model;
        }

        $decodedAdditional = json_decode($_POST['additionalData'], true);
        if (!isset($decodedAdditional['gridPrjModel'])) {
            $gridData = array();
        } else {
            $gridData = $decodedAdditional['gridPrjModel'];
        }


        // information for the tests
        /*
          $fieldstst = array("cd_project_build_schedule_tests" => isset($upd_array->cd_project_build_schedule_tests) ? $upd_array->cd_project_build_schedule_tests : array(),
          //"cd_project_build_schedule" => isset($upd_array->cd_project_build_schedule) ? $upd_array->cd_project_build_schedule : array(),
          "cd_test_type" => isset($upd_array->cd_test_type) ? $upd_array->cd_test_type : array(),
          "cd_test_item" => isset($upd_array->cd_test_item) ? $upd_array->cd_test_item : array(),
          "cd_tests" => isset($upd_array->cd_tests) ? $upd_array->cd_tests : array(),
          "ds_specification" => isset($upd_array->ds_specification) ? $upd_array->ds_specification : array(),
          "ds_sample_description" => isset($upd_array->ds_sample_description) ? $upd_array->ds_sample_description : array(),
          "ds_extra_instruction" => isset($upd_array->ds_extra_instruction) ? $upd_array->ds_extra_instruction : array(),
          "fl_witness" => isset($upd_array->fl_witness) ? $upd_array->fl_witness : array(),
          "nr_sample_quantity" => isset($upd_array->nr_sample_quantity) ? $upd_array->nr_sample_quantity : array(),
          "ds_wi" => isset($upd_array->ds_wi) ? $upd_array->ds_wi : array(),
          "ds_wi_section" => isset($upd_array->ds_wi_section) ? $upd_array->ds_wi_section : array(),
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
          "cd_schedule_test_status" => isset($upd_array->cd_schedule_test_status) ? $upd_array->cd_schedule_test_status : array(),
          //"cd_human_resource_te" => isset($upd_array->cd_human_resource_te) ? $upd_array->cd_human_resource_te : array(),
          "cd_location" => isset($upd_array->cd_location) ? $upd_array->cd_location : array(),
          "cd_test_unit" => isset($upd_array->cd_test_unit) ? $upd_array->cd_test_unit : array(),
          "ds_test_item" => isset($upd_array->ds_test_item) ? $upd_array->ds_test_item : array(),

          "nr_priority" => isset($upd_array->nr_priority) ? $upd_array->nr_priority : array(),
          "nr_headcount_requested_day" => isset($upd_array->nr_headcount_requested_day) ? $upd_array->nr_headcount_requested_day : array(),
          "nr_headcount_allocated_day" => isset($upd_array->nr_headcount_allocated_day) ? $upd_array->nr_headcount_allocated_day : array()

          );
         */

        /*
          $upddatatst = $this->getCdbhelper()->createGridResultSetFormOrder(array(
          'fields' => $fieldstst,
          'pkField' => 'cd_project_build_schedule_tests',
          'orderFieldName' => 'recid',
          'indexRSFieldName' => 'cd_project_build_schedule',
          'indexRSFind' => -1,
          'deleteField' => 'cd_test_type'
          )
          );
         */
        //die (print_r($upddatatst));
        //$this->fieldsUpd = array("cd_project_build_schedule", "cd_project", "cd_project_model", "cd_project_build", "nr_version", "dt_est_start", "dt_est_finish", "ds_comments");
        // information for the tests


        $fieldsch = array("cd_project_build" => isset($upd_array->cd_project_build) ? $upd_array->cd_project_build : array(),
            "nr_version" => isset($upd_array->nr_version) ? $upd_array->nr_version : array(),
            "dt_est_start" => isset($upd_array->dt_est_start_build) ? $upd_array->dt_est_start_build : array(),
            "dt_est_finish" => isset($upd_array->dt_est_finish_build) ? $upd_array->dt_est_finish_build : array(),
            "ds_comments" => isset($upd_array->ds_comments) ? $upd_array->ds_comments : array(),
            "cd_human_resource_te" => isset($upd_array->cd_human_resource_te) ? $upd_array->cd_human_resource_te : array(),
        );

        $upddatasch = $this->getCdbhelper()->createGridResultSetFormOrder(array(
                'fields' => $fieldsch,
                //'pkField' => 'cd_project_build_schedule_tests',
                //'orderFieldName' => 'recid',
                'indexRSFieldName' => 'recid',
                'indexRSFind' => -1,
                'deleteField' => 'cd_project_build',
                'fixedData' => array('cd_project' => $prj, 'cd_project_model' => $cdmodel)
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


        $error = $this->prjmodelmodel->updateGridData($arraysend);
        if ($error != 'OK') {
            $this->getCdbhelper()->trans_rollback();
            $msg = '{"status":' . json_encode($error) . ', "rs":{} }';
            echo($msg);
            return;
        }

        $error = $this->schmodel->updateGridData($upddatasch->upd);
        if ($error != 'OK') {
            $this->getCdbhelper()->trans_rollback();
            $msg = '{"status":' . json_encode($error) . ', "rs":{} }';
            echo($msg);
            return;
        }

        $error = $this->schmodel->deleteGridData($upddatasch->del);
        if ($error != 'OK') {
            $this->getCdbhelper()->trans_rollback();
            $msg = '{"status":' . json_encode($error) . ', "rs":{} }';
            echo($msg);
            return;
        }

        /*
          $error = $this->buildschtstmodel->deleteGridData($upddatatst->del);
          if ($error != 'OK') {
          $this->getCdbhelper()->trans_rollback();
          $msg = '{"status":' . json_encode($error) . ', "rs":{} }';
          echo($msg);
          return;
          }




          $error = $this->buildschtstmodel->updateGridData($upddatatst->upd);
          if ($error != 'OK') {
          $this->getCdbhelper()->trans_rollback();
          $msg = '{"status":' . json_encode($error) . ', "rs":{} }';
          echo($msg);
          return;
          }
         */

        $addedRole = array();

        foreach ($decodedAdditional as $key => $value) {
            $model = false;
            switch (substr($key, 0, 10)) {
                // checklist
                case 'gridCheckp':
                    $model = $this->buildchkmodel;
                    break;

                // attachment by build
                case 'gridAttach':
                    $model = $this->prjdocrepmodel;

                    break;

                case 'gridMainAt':
                    $model = $this->prjdocrepmodel;

                    break;

                case 'gridPlanDa':
                    $model = $this->buildschtstmodel;

                    break;


                // comments by build
                case 'gridPrjBui':
                    $model = $this->schcomments;
                    break;

                // comments main
                case 'gridPrjCom':
                    $model = $this->projmaincomments;


                    break;

                // roles
                case 'gridPrjRol':
                    $addedRole = $value;
                    $model = $this->projuserroles;

                    break;

                // model (compatibility with the insert/update screen.
                case 'gridPrjMod':
                    $model = $this->prjmodelmodel;

                    break;

                case 'gridDates':
                    $model = $this->workersmodel;
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

                if (substr($key, 0, 10) == 'gridPrjCom') {

                    $error = $this->prjcommcc->updateGridDataFromField('cc', $value);
                    //die (print_r($value[0]['cc']));
                    if ($error != 'OK') {
                        $this->getCdbhelper()->trans_rollback();
                        $msg = '{"status":' . json_encode($error) . ', "rs":{} }';
                        echo($msg);
                        break;
                    }



                    $this->docrepmodel->updateGridRelDataFromField('attachChanges', $value, $this->prjdocrepmodel);
                    if ($error != 'OK') {
                        $this->getCdbhelper()->trans_rollback();
                        $msg = '{"status":' . json_encode($error) . ', "rs":{} }';
                        echo($msg);
                        break;
                    }
                }

                if (substr($key, 0, 10) == 'gridPrjBui') {

                    $error = $this->prjbuildcommcc->updateGridDataFromField('cc', $value);
                    //die (print_r($value[0]['cc']));
                    if ($error != 'OK') {
                        $this->getCdbhelper()->trans_rollback();
                        $msg = '{"status":' . json_encode($error) . ', "rs":{} }';
                        echo($msg);
                        break;
                    }



                    $this->docrepmodel->updateGridRelDataFromField('attachChanges', $value, $this->prjbuilddocrepmodel);
                    if ($error != 'OK') {
                        $this->getCdbhelper()->trans_rollback();
                        $msg = '{"status":' . json_encode($error) . ', "rs":{} }';
                        echo($msg);
                        break;
                    }
                }
            }
        }





        if ($error != 'OK') {
            return;
        }

        $sql = "select updateBuildDates($cdmodel);";
        $this->getCdbhelper()->basicSQLNoReturn($sql);
        if (!$this->getCdbhelper()->trans_status()) {
            $error = $this->getCdbhelper()->trans_last_error();
            $this->getCdbhelper()->trans_rollback();
            $msg = '{"status":' . json_encode($error) . ', "rs":{} }';
            echo($msg);
            return;
        }

        $this->getCdbhelper()->trans_commit();
        $this->getCdbhelper()->trans_end();



        $retResult = $this->mainmodel->retRetrieveGridJson(' WHERE "PROJECT".cd_project = ' . $arraysend[0]->recid, ' ORDER BY 1 ', '', $this->mainmodel->retrOptionsOnlyProject);
        $projs = $this->mainmodel->retRetrieveGridJson(' WHERE "PROJECT".cd_project = ' . $arraysend[0]->recid, ' ORDER BY 1 ');
        $mddata = $this->prjmodelmodel->retRetrieveGridJson(' WHERE "PROJECT_MODEL".cd_project = ' . $arraysend[0]->recid);

        $this->sendCommentData($cdmodel, $timestamp);
        $this->sendAddedData($addedRole, $timestamp);

        // busco o gridChk;

        $msg = '{"status":' . json_encode($error) . ', "rs":' . $retResult . ', "gridData": ' . $mddata . ', "projs": ' . $projs . ' }';

        echo $msg;
    }

    /* SHEET------------------------------------------------------------------------------------------ */

    public function sendAddedData($array, $timestamp) {
        if (count($array) == 0) {
            return;
        }
        $cdmodel = -1;
        foreach ($array as $key => $value) {
            if (isset($value['cd_project_model'])) {
                $cdmodel = $value['cd_project_model'];
            }
        }

        if ($cdmodel == -1) {
            return;
        }

        $human_resource = $this->session->userdata('ds_human_resource_full');

        $xroles = $this->projuserroles->retRetrieveArray(" WHERE  \"PROJECT_USER_ROLES\".cd_project_model = $cdmodel and \"PROJECT_USER_ROLES\".dt_record >= '$timestamp'");

        $this->load->library('sendmail');
        $mail = $this->sendmail;
        if (1 == 2) {
            $mail = new sendmail();
        }

        foreach ($xroles as $key => $value) {

            $mail->clear();
            $data = $this->prjmodelmodel->retRetrieveArray(" WHERE  \"PROJECT_MODEL\".cd_project_model = " . $value['cd_project_model'])[0];



            $ds_project_full_desc = $data['ds_project_full_desc'];
            $ds_project_number = $data['ds_project_number'];

            if ($value['fl_active'] == 1 && $value['ds_e_mail'] != '') {
                $mail->sendToSender(false);
                $mail->addTO($value['ds_e_mail']);
                $mail->setSubject("LMS - You have been added to Project/Model $ds_project_full_desc ");
                $mail->setMessage("Hi <br><br> You have been added to a Project/Model  by $human_resource as below: <br>Project/Model #: $ds_project_number <br>Project/Model Description: $ds_project_full_desc");
                $mail->sendMail();
            }
        }
    }

    public function sendCommentData($cdmodel, $timestamp) {

        if ($cdmodel == 0) {
            return;
        }

        $prjData = $this->mainmodel->retRetrieveArray(' WHERE "PROJECT_MODEL".cd_project_model = ' . $cdmodel)[0];
        $commentsBuild = $this->schcomments->retRetrieveArray(' WHERE "PROJECT_BUILD_SCHEDULE".cd_project_model = ' . $cdmodel . ' AND "PROJECT_BUILD_SCHEDULE_COMMENTS".dt_update >= \'' . $timestamp . '\' ', ' ORDER BY "PROJECT_BUILD".nr_order');
        $commentsPrj = $this->projmaincomments->retRetrieveArray(' WHERE "PROJECT_COMMENTS".cd_project_model = ' . $cdmodel . ' AND "PROJECT_COMMENTS".dt_update >= \'' . $timestamp . '\' ', ' ORDER BY "PROJECT_COMMENTS".dt_update');

        $attachments = array();


        if (count($commentsBuild) == 0 && count($commentsPrj) == 0) {
            return;
        }

        $mailToDb = $this->projuserroles->retRetrieveArray(' WHERE "PROJECT_USER_ROLES".cd_project_model = ' . $cdmodel . ' AND "PROJECT_USER_ROLES".fl_active = \'Y\' AND "HUMAN_RESOURCE".dt_deactivated IS NULL ');

        $this->load->library('sendmail');
        $mail = $this->sendmail;
        if (1 == 2) {
            $mail = new sendmail();
        }

        $filesize = 0;

        $comm = '';

        foreach ($commentsPrj as $key => $value) {
            $comment = $value['ds_comments'];
            $by = $value['ds_human_resource'];
            $type = $value['ds_project_comments_type'];
            $dt = $value['ds_update_formatted'];
            $cc = $value['cc'];
            $cc = json_decode($cc, true);

            $build = 'PROJECT';


            foreach ($cc as $keyMail => $valueMail) {
                $mail->addCC($valueMail['ds_e_mail']);
            }



            $attachmentsName = '';
            foreach (json_decode($value['ds_attachments'], true) as $keyAtt => $valueAtt) {
                array_push($attachments, array('file' => $valueAtt['ds_path_file'], 'name' => $valueAtt['ds_original_file']));
                if (file_exists($valueAtt['ds_path_file'])) {
                    $filesize = $filesize + filesize($valueAtt['ds_path_file']);
                }

                if ($attachmentsName != '') {
                    $attachmentsName = $attachmentsName . '<br>';
                }
                $attachmentsName = $attachmentsName . $valueAtt['ds_original_file'];
            }


            foreach (json_decode($value['ds_emails_comments'], true) as $keyMail => $valueMail) {
                $mail->addTO($valueMail['ds_e_mail']);
            }

            $comm = $comm . "<tr><td>$build</td><td>$comment</td><td>$type</td><td>$by</td><td>$dt</td><td>$attachmentsName</td></tr>";
        }



        foreach ($commentsBuild as $key => $value) {
            $comment = $value['ds_comments'];
            $by = $value['ds_human_resource'];
            $type = $value['ds_project_comments_type'];
            $build = $value['ds_project_build_full'];
            $dt = $value['ds_update_formatted'];
            $cc = $value['cc'];
            $cc = json_decode($cc, true);
            //die (print_r($cc));

            foreach ($cc as $keyMail => $valueMail) {
                $mail->addCC($valueMail['ds_e_mail']);
            }


            $attachmentsName = '';
            foreach (json_decode($value['ds_attachments'], true) as $keyAtt => $valueAtt) {
                array_push($attachments, array('file' => $valueAtt['ds_path_file'], 'name' => $valueAtt['ds_original_file']));
                if (file_exists($valueAtt['ds_path_file'])) {
                    $filesize = $filesize + filesize($valueAtt['ds_path_file']);
                }

                if ($attachmentsName != '') {
                    $attachmentsName = $attachmentsName . '<br>';
                }
                $attachmentsName = $attachmentsName . $valueAtt['ds_original_file'];
            }


            foreach (json_decode($value['ds_emails_comments'], true) as $keyMail => $valueMail) {
                $mail->addTO($valueMail['ds_e_mail']);
            }

            $comm = $comm . "<tr><td>$build</td><td>$comment</td><td>$type</td><td>$by</td><td>$dt</td><td>$attachmentsName</td></tr>";

            foreach (json_decode($value['ds_emails_comments'], true) as $keyMail => $valueMail) {
                $mail->addTO($valueMail['ds_e_mail']);
            }
        }



        foreach ($mailToDb as $key => $value) {
            if (isset($value['ds_e_mail'])) {
                $mail->addTO($value['ds_e_mail']);
            }
        }
        $prjdesc = $prjData['ds_project_full_desc'];

        $prj = $prjData['ds_met_project'] != '' ? $prjData['ds_met_project'] : $prjData['ds_tti_project'];
        $prjmodel = $prjData['ds_met_project_model'] != '' ? $prjData['ds_met_project_model'] : $prjData['ds_tti_project_model'];
        $subject = "Project #$prj - Model #$prjmodel - $prjdesc  ";


        $mail->setSubject($subject);

        $x = $this->load->view('mailtemplates/projMailComments', array('comment' => nl2br($comm)), true);



        $par = $this->getCdbhelper()->getSystemParameters('MAX_SIZE_COMMENT_ATTACHMENT');
        if ($filesize <= $par) {
            foreach ($attachments as $keyAtt => $valueAtt) {
                //array_push($attachments, array('file' => $valueAtt['ds_path_file'], 'name' => $valueAtt['ds_original_file'] ));
                $mail->addAttachment($valueAtt['file'], $valueAtt['name']);
            }
        } else {
            $parHuman = $this->getCdbhelper()->readableBytes($par);
            $x = $x . "<br><br> <span style='font-style: italic;font-size: 11px'>* Files were not attached on this e-mail because it exceeded $parHuman </span>";
        }

        $mail->setMessage($x);

        $mail->sendMail();
    }

    public function callPrjSheetForm($cd_project, $cd_project_model) {



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

        $canChangeDetails = $this->getCdbhelper()->getUserPermission('fl_change_project_main_details');


        $builds = $this->buildmodel->retRetrieveArray(' WHERE "PROJECT_BUILD".dt_deactivated IS NULL', ' ORDER BY "PROJECT_BUILD".nr_order');

        //
        $rows = $this->schmodel->retRetrieveArray(' WHERE "PROJECT_BUILD_SCHEDULE".cd_project = ' . $cd_project, ' ORDER BY COALESCE("PROJECT_MODEL".ds_project_model, \'\'), "PROJECT_BUILD".nr_order');



        $copyFrom = array();

        foreach ($rows as $key => $value) {
            if ($value['nr_test_count'] == 0) {
                continue;
            }
            $text = $value['ds_project_build_abbreviation'];

            if ($value['fl_allow_multiples'] == 1) {
                $text = $text . $value['nr_version'];
            }

            if ($value['ds_project_model'] != '') {
                $text = $text . ' - ' . $value['ds_project_model'];
            }
            $text = $text . ' - Available Tests : ' . $value['nr_test_count'];

            array_push($copyFrom, array('id' => 'tests_' . $value['cd_project_build_schedule'], 'text' => $text));
        }

        // creating toolbar;
        $grid->addUserBtnToolbar('searchGrid', 'full text search', 'fa fa-update');
        $grid->addCRUDToolbar(false, true, false, false, false);
        $grid->setGridVar('vGridToToolbarTest');
        $grid->setForceDestroy(false);

        if (count($copyFrom)) {
            $grid->addBreakToolbar();
            $idx = $grid->addUserBtnToolbar('copyFrom', 'Copy Lab Tests From', 'fa fa-copy', '');
            foreach ($copyFrom as $key => $value) {
                $grid->addUserBtnToolbar($value['id'], $value['text'], '', $value['text'], $idx);
            }
        }

        $grid->addUserBtnToolbar('uploadMatrix', 'Upload Matrix', 'fa fa-cloud-upload');

        $grid->addBreakToolbar();
        $grid->addUserBtnToolbar('expexcel', 'Export Excel', 'fa fa-file-excel-o');

        $grid->addBreakToolbar();
        $grid->addUserBtnToolbar('shiftDays', 'by', 'fa fa-update');
        //$grid->addUseRadioToolbar('shiftPlanned', 'Planned', 'Shift Planned', true, 1);
        //$grid->addUseRadioToolbar('shiftAgreed', 'Agreed', 'Shift Agreed', false, 1);
        $grid->addUserBtnToolbar('shiftRun', 'Shift Agreed Dates', 'fa fa-calendar');
        $grid->addBreakToolbar();
        $grid->addUserCheckToolbar('showAsGrid', 'Toggle View', 'Toggle View', true);
        $grid->addBreakToolbar();

        $toolbarTest = $grid->retGridVar();
        $grid->resetGrid();


        $this->checkpoints = $this->buildchkmodel->retRetrieveArray(' WHERE dt_deactivated IS NULL', 'ORDER BY nr_order ');

        $trans = $this->trans;

        $sc = "N";
        $line = $this->mainmodel->getProjectSheet($cd_project, $cd_project_model);
        $mddata = $this->prjmodelmodel->retRetrieveArray(' WHERE "PROJECT_MODEL".cd_project_model = ' . $cd_project_model);
        $action = 'E';


        //$mddata = '{}';
        // creating toolbar;
        $grid->addCRUDToolbar(false, false, true, false, false);
        $grid->addUserBtnToolbar('downtemp', 'Download Matrix Helper Template', 'fa fa-cloud-download');
        $canSeePurFromWI = $this->getCdbhelper()->getUserPermission('fl_request_material_from_project');
        if ($canSeePurFromWI == 'Y') {
            $grid->addUserBtnToolbar('openPurFromWI', 'Buy From WI', 'fa fa-money');
        }
        $grid->addBreakToolbar();
        $grid->addUserBtnToolbar('expand', 'Expand', 'fa fa-expand');
        $grid->addUserBtnToolbar('collapse', 'Collapse', 'fa fa-compress');

        $grid->addBreakToolbar();
        $grid->setGridVar('vGridToToolbarPrj');
        $grid->setForceDestroy(false);
        $toolbar = $grid->retGridVar();

        $grid->resetGrid();
        $grid->addColumnKey();
        //$grid->addColumn('ds_project_build_schedule_tests', 'Project Build Schedule Tests', '150px', $f->retTypePickList(), array('model' => 'schedule/project_build_schedule_tests_model', 'codeField' => 'cd_project_build_schedule_tests'));
        $grid->addColumn('dt_start', 'Start', '80px', $f->retTypeDate(), true);
        $grid->addColumn('dt_finish', 'Finish', '80px', $f->retTypeDate(), true);
        $grid->addColumn('nr_workers', 'Workers', '150px', $f->retTypeInteger(), true);
        $grid->setGridName('gridDates');
        $grid->setGridVar('vGridDates');



        $workers = $this->workersmodel->retRetrieveGridJson(' WHERE "PROJECT_BUILD_SCHEDULE".cd_project = ' . $cd_project . ' AND "PROJECT_BUILD_SCHEDULE".cd_project_model = ' . $cd_project_model);
        $grid->addRecords($workers);

        $gridDates = $grid->retGridVar();

        // work order data.
        $grid->resetGrid();
        $grid->addColumnKey();
        //$grid->addColumn('ds_project_build_schedule_tests', 'Project Build Schedule Tests', '150px', $f->retTypePickList(), array('model' => 'schedule/project_build_schedule_tests_model', 'codeField' => 'cd_project_build_schedule_tests'));
        $grid->addColumn('nr_work_order', 'Work Order', '50%', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_sample_list', 'Tool Number', '50%', $f->retTypeStringAny(), false);

        //$grid->showColumnHeader(false);
        $grid->showFooter(false);
        $grid->showLineNumbers(false);
        $grid->showToolbar(false);
        $grid->setGridName('gridWO');
        $grid->setGridVar('vGridWO');


        //$workers = $this->workersmodel->retRetrieveGridJson(' WHERE "PROJECT_BUILD_SCHEDULE".cd_project = ' . $cd_project . ' AND "PROJECT_BUILD_SCHEDULE".cd_project_model = ' . $cd_project_model);
        //$grid->addRecords($workers);

        $gridWO = $grid->retGridVar();

        // grid TR/MTE
        $grid->resetGrid();
        $grid->setRowHeight(40);
        $grid->addColumnKey();
        $grid->setCRUDController('tti/project', false);
        $grid->setGridToolbarFunction("dsFormPrjSheetObject.ToolbarBuildGridComments");
        $grid->setToolbarPrefix('notrun');
        $grid->setToolbarSearch(true);
        $grid->addSpacerToolbar();
        $grid->addExportToolbar();
        $grid->addColumn('ds_toolbar', '', '60px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_type_test', 'Type', '80px', $f->retTypeStringAny(), false);

        $grid->addColumn('ds_tr_number', 'TR#', '80px', $f->retTypeStringAny(), false);
        $grid->addColumn('dt_supervisor_approval', 'Supervisor Approval', '120px', $f->retTypeDate(), false);
        $grid->addColumn('nr_work_order', 'Work Order', '90px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_tr_test_request_work_order_status', 'WO Status', '80px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_test_item', 'Test Item', '80px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_test_procedure_name', 'Procedure', '80px', $f->retTypeStringAny(), false);
        
        $grid->addColumn('nr_sample', 'Sample#', '70px', $f->retTypeStringAny(), false);
        //$grid->addColumn('ds_sample_status', 'Sample Status', '90px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_goal', 'Goal', '80px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_goal_unit', 'Unit', '80px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_work_order_name', 'Work Order Name', '100px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_remarks', 'Remarks', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_test_result', 'Test Result', '100px', $f->retTypeStringAny(), false);

        $grid->setColumnRenderFunc("ds_remarks", 'dsFormPrjSheetObject.renderTRRemarks');
        $grid->setColumnRenderFunc("ds_toolbar", 'dsFormPrjSheetObject.toolbarTRRemarks');


        //$grid->setColumnRenderFunc("ds_remarks", 'thisObj.renderTRRemarks');
        // MTE

        $grid->addColumn('ds_source', 'Source', '90px', $f->retTypeStringAny(), false);

        $grid->addColumn('ds_mte_toolbar', '', '30px', $f->retTypeStringAny(), false);

        $grid->addColumn('ds_start_date', 'Start Date', '95px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_estimated_complete', 'Est.Complete', '95px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_actual_complete', 'Actual Complete', '95px', $f->retTypeStringAny(), false);

        $grid->addColumn('ds_tool_status', 'Status', '95px', $f->retTypeStringAny(), false);

        $grid->addColumn('ds_comp_cycle', 'Comp.Cycles', '95px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_comp_runtime', 'Comp. Runtime', '100px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_comp_apps', 'Comp.Apps', '80px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_comp_discharge', 'Comp.Discharge', '120px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_work_station', 'Workstation', '100px', $f->retTypeStringAny(), false);


        $grid->addColumn('ds_operator', 'Operator', '100px', $f->retTypeStringAny(), false);
        //$grid->addColumn('ds_assistant_eng', 'Asst ENG', '100px', $f->retTypeStringAny(), false);
        //$grid->addColumn('ds_test_eng', 'Test Eng', '100px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_room_code', 'Room Name', '100px', $f->retTypeStringAny(), false);

        $grid->setColumnRenderFunc("ds_mte_toolbar", 'dsFormPrjSheetObject.toolbarMTERemarks');

        $grid->addColumnGroup(15, 'TR');
        $grid->addColumnGroup(14, 'Test Details');



        //$grid->showColumnHeader(false);
        $grid->showFooter(false);
        $grid->showLineNumbers(false);
        $grid->showToolbar(true);
        $grid->setGridName('gridTR');
        $grid->setGridVar('vGridTR');

        $gridWOTRMTE = $grid->retGridVar();


        // MAIN COMMENTS
        $grid->resetGrid();
        $grid->setGridToolbarFunction("dsFormPrjSheetObject.ToolbarGridComments");
        $grid->setForceDestroy(true);
        $grid->showToolbar(true);
        $grid->addCRUDToolbar(false, false, false, false, false);
        $grid->addBreakToolbar();
        $grid->addInsToolbar();
        $grid->addUserBtnToolbar('replycom', 'Reply Selected Comment', 'fa fa-reply');

        $grid->addBreakToolbar();
        $grid->addDelToolbar();
        $grid->addBreakToolbar();
        $grid->addUserBtnToolbar('expexcel', 'Export Excel', 'fa fa-file-excel-o');


        //$grid->setExcelDetailed(true);

        $grid->showFooter(false);
        $grid->setInsertNegative(true);
        $grid->setGridDivName('gridPrjCommentsDiv');
        $grid->setGridName('gridPrjComments');
        $grid->setCRUDController("tti/project_comments");
        $grid->addRecords($line[0]['ds_main_comm']);
        $grid->addToolbarTitle('Comments');

        //$grid->setDemandedColumns(array("ds_comments"));
        //$grid->setHeader('Comments');

        $grid->addColumnKey();
        $grid->addColumn('ds_human_resource', 'By', '120px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_comments', 'Comments', '100%', $f->retTypeTextPL(), array('limit' => '1000'));
        $grid->addColumn('ds_project_comments_type', 'Type', '120px', $f->retTypeStringAny(), false);
        $grid->addColumn('dt_record', 'Added', '80px', $f->retTypeDate(), false);
        $grid->setColumnRenderFunc("ds_comments", 'dsFormPrjSheetObject.renderDisableCommentsPL');
        //$grid->setColumnRenderFunc("ds_project_comments_type", 'dsFormPrjSheetObject.setPLEnableOnlyNEW');

        $PrjModelGrid = $grid->retGrid();


        // BUILD COMMENTS
        $grid->resetGrid();
        $grid->setGridToolbarFunction("dsFormPrjSheetObject.ToolbarBuildGridComments");
        $grid->setForceDestroy(true);
        $grid->showToolbar(true);
        //$grid->addCRUDToolbar(false, true, false, true, false);

        $grid->addCRUDToolbar(false, false, false, false, false);
        $grid->addBreakToolbar();
        $grid->addInsToolbar();
        $grid->addUserBtnToolbar('replycom', 'Reply Selected Comment', 'fa fa-reply');

        $grid->addBreakToolbar();
        $grid->addDelToolbar();
        $grid->addBreakToolbar();
        $grid->addUserBtnToolbar('expexcel', 'Export Excel', 'fa fa-file-excel-o');


        $grid->showFooter(false);
        $grid->setInsertNegative(true);
        //$grid->addUserBtnToolbar('expexcel', 'Export Excel', 'fa fa-file-excel-o');
        $grid->setGridName('gridPrjBuildComments');
        $grid->setGridVar('vGridBuildComments');
        $grid->addToolbarTitle('Comments');
        $grid->setCRUDController("schedule/project_build_schedule_comments");
        $grid->setDemandedColumns(array("ds_comments", "cd_project_comments_type"));
        //$grid->setHeader('Comments');

        $grid->addColumnKey();
        $grid->addColumn('ds_human_resource', 'By', '20%', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_comments', 'Comments', '40%', $f->retTypeTextPL(), array('limit' => '1000'));
        $grid->addColumn('ds_project_comments_type', 'Type', '20%', $f->retTypeStringAny(), false);
        $grid->addColumn('dt_record', 'Added', '20%', $f->retTypeDate(), false);
        $grid->setColumnRenderFunc("ds_comments", 'dsFormPrjSheetObject.renderDisableCommentsPL');


        $PrjModelGrid = $PrjModelGrid . $grid->retGridVar();

        // Test Tool Main Information
        $grid->resetGrid();
        $grid->setGridToolbarFunction("dsFormPrjSheetObject.ToolbarTestTools");
        $grid->setForceDestroy(true);
        $grid->showToolbar(true);
        $grid->setInsertNegative(true);
        $grid->addCRUDToolbar(false, true, false, true, false);
        $grid->addDocRepToolbar();
        $grid->showFooter(false);
        $grid->setGridName('gridTestToolData');
        $grid->setGridVar('vGridTestToolData');
        $grid->setCRUDController("schedule/project_build_comments");

        //$grid->setHeader('Comments');

        $grid->addColumnKey();
        $grid->addColumn('x', 'Tool#', '50px', $f->retTypeStringAny(), false);
        $grid->addColumn('x', 'Status', '100px', $f->retTypeStringAny(), false);
        $grid->addColumn('x', 'Cycle Count', '100px', $f->retTypeStringAny(), false);
        $grid->addColumn('x', 'Started On', '100px', $f->retTypeStringAny(), false);
        $grid->addColumn('x', 'Complete', '100px', $f->retTypeStringAny(), false);
        $grid->addColumn('x', 'Comments', '100%', $f->retTypeStringAny(), false);

        //$grid->addColumn('x', 'HC', '60px', $f->retTypeStringAny(), false);
        //$grid->addColumn('dt_record', 'Added', '80px', $f->retTypeDate(), true);

        $PrjModelGrid = $PrjModelGrid . $grid->retGridVar();

        // PROJECT ROLES
        $grid->resetGrid();
        $grid->setGridToolbarFunction("dsFormPrjSheetObject.ToolbarGridRoles");
        $grid->setForceDestroy(true);
        $grid->showToolbar(true);
        $grid->setInsertNegative(true);
        $grid->addCRUDToolbar(false, true, false, true, false);
        $grid->addUserBtnToolbar('expexcel', 'Export Excel', 'fa fa-file-excel-o');
        $grid->showFooter(false);
        $grid->setGridDivName('gridPrjRolesDiv');
        $grid->setGridName('gridPrjRoles');
        $grid->setCRUDController("tti/project_user_roles");
        $grid->addRecords($line[0]['ds_roles']);
        $grid->addToolbarTitle('Users/Roles');
        //$grid->setHeader('Users/Roles');

        $grid->addColumnKey();

        $grid->addColumn('ds_human_resource', 'User', '120px', $f->retTypePickList(), array('model' => 'human_resource_model', 'codeField' => 'cd_human_resource'));
        $grid->addColumn('ds_roles', 'Roles', '100% > 100px', $f->retTypePickList(), array('model' => 'tti/roles_model', 'codeField' => 'cd_roles'));
        $grid->addColumn('ds_notification_type', 'Notification', '100px', $f->retTypePickList(), array('model' => 'notification_type_model', 'codeField' => 'cd_notification_type'));
        $grid->addColumn('fl_active', 'Active', '50px', $f->retTypeCheckBox(), true);
        $PrjModelGrid = $PrjModelGrid . $grid->retGrid();

        // checkpoints
        $grid->resetGrid();
        $grid->setForceDestroy(true);
        $grid->showToolbar(false);
        $grid->showFooter(false);
        $grid->setGridName('gridCheckPoint');
        $grid->addColumnKey();
        $grid->addColumn('ds_project_build_checkpoints', 'Checklist', '60%', $f->retTypeStringAny(), false);
        $grid->addColumn('dt_deadline', 'Deadline', '80px', $f->retTypeDate(), true);
        $grid->addColumn('dt_done', 'Actual Date', '80px', $f->retTypeDate(), true);
        $grid->addColumn('ds_comment', 'Notes', '40%', $f->retTypeTextPL(), true);
        $grid->addHiddenColumn('nr_order', 'order', '80px', $f->retTypeInteger(), true);
        $grid->setGridVar('vGridCheckpoints');
        $grid->addToolbarTitle('Checklist');
        $gridCHK = $grid->retGridVar();

        // attachment
        $grid->resetGrid();
        $grid->setForceDestroy(true);
        $grid->showFooter(false);
        $grid->showToolbar(true);
        $grid->setGridToolbarFunction("dsFormPrjSheetObject.ToolbarBuildAttachment");

        //$grid->addCRUDToolbar(false, true, false, true, false);
        $grid->addEditToolbar();
        $grid->addUserBtnToolbar('expexcel', 'Export Excel', 'fa fa-file-excel-o');
        $grid->setGridName('gridApp');
        $grid->addColumnKey();
        $grid->addColumn('tp_desc', 'Type', '60px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_original_file', 'File Name', '100%', $f->retTypeStringAny(), false);
        $grid->addColumn('dt_record', 'Added', '80px', $f->retTypeDate(), true);
        $grid->setInsertNegative(true);
        $grid->setGridVar('vGridAttachment');
        $grid->addToolbarTitle('Attachments');
        $gridAttachment = $grid->retGridVar();


        // Main Attachment
        $grid->resetGrid();
        $grid->setForceDestroy(true);
        $grid->showToolbar(true);
        $grid->setGridToolbarFunction("dsFormPrjSheetObject.ToolbarMainAttachment");
        //$grid->addCRUDToolbar(false, true, false, true, false);
        $grid->addEditToolbar();
        $grid->showFooter(false);
        $grid->setInsertNegative(true);
        $grid->addUserBtnToolbar('expexcel', 'Export Excel', 'fa fa-file-excel-o');
        $grid->setGridName('gridMainAttachment');

        $grid->addColumnKey();
        $grid->addColumn('fl_main', 'Main', '40px', $f->retTypeCheckBox(), true);
        $grid->addColumn('tp_desc', 'Type', '60px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_original_file', 'File Name', '100%', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_document_repository', 'Title', '100%', $f->retTypeStringAny(), false);
        $grid->addColumn('dt_record', 'Added', '80px', $f->retTypeDate(), true);

        $grid->addToolbarTitle('Project Attachments');
        $grid->setGridDivName('gridMainAttachDiv');
        $grid->addRecords(json_encode($this->retDocRep($cd_project_model, 'P', false), JSON_NUMERIC_CHECK));
        $gridAttachmentMain = $grid->retGrid();

        /*         * *******************************  */
        $grid->resetGrid();
        $grid->showToolbar(false);
        $grid->setRowHeight(32);
        $grid->setCRUDController('schedule/project_build_schedule_tests');


        $grid->addColumn('ds_toolbar', '', '85px', $f->retTypeStringAny(), false);
        $grid->setColumnRenderFunc("ds_toolbar", 'dsFormPrjSheetObject.toolbarPlans');

        //$grid->addColumn('ds_test_type', 'Type', '150px',  $f->retTypePickList(), array('model' => 'tr/test_type_model', 'codeField' => 'cd_test_type' ));
        $grid->addColumn('nr_priority', 'Priority', '60px', $f->retTypeInteger(), false);
        //$grid->addColumn('ds_test_type', 'Type', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_test_type', 'Type', '150px', $f->retTypePickList(), array('model' => 'tr/test_type_model', 'codeField' => 'cd_test_type'));



        $grid->addColumn('ds_test_item', 'Item', '150px', $f->retTypeStringAny(), true);
        $grid->addColumn('ds_wi', 'WI', '100px', $f->retTypeStringAny(), true);
        $grid->addColumn('ds_wi_section', 'Section', '100px', $f->retTypeStringAny(), true);
        $grid->addColumn('nr_sample_quantity', 'Quantity', '100px', $f->retTypeInteger(), true);

        $grid->addColumn('dt_est_start', 'Start', '80px', $f->retTypeDate(), true);
        $grid->addColumn('dt_est_finish', 'Finish', '80px', $f->retTypeDate(), true);

        $grid->addColumn('dt_start', 'Start', '80px', $f->retTypeDate(), true);
        $grid->addColumn('dt_finish', 'Finish', '80px', $f->retTypeDate(), true);

        $grid->addColumn('dt_actual_start', 'Start', '80px', $f->retTypeDate(), false);
        $grid->addColumn('dt_actual_finish', 'Finish', '80px', $f->retTypeDate(), false);

        $grid->addColumn('ds_specification', 'Specification', '150px', $f->retTypeTextPL(), true);
        $grid->addColumn('ds_tr_wi_data', 'TR WI', '150px', $f->retTypePickList(), array('model' => 'tr/tr_wi_data_model', 'codeField' => 'cd_tr_wi_data'));


        $grid->addColumn('nr_headcount_requested_day', 'Required HC', '60px', $f->retTypeNum(), array('precision' => '1', 'readonly' => false));
        $grid->addColumn('nr_headcount_allocated_day', 'Allocated HC', '60px', $f->retTypeNum(), array('precision' => '1', 'readonly' => true));


        $grid->addColumn('fl_witness', 'Witness', '80px', $f->retTypeCheckBox(), true);
        $grid->addColumn('fl_eol', 'EOL', '80px', $f->retTypeCheckBox(), true);

        //$grid->addColumn('ds_test_unit', 'Test Unit', '100px', $f->retTypeStringAny(), false);

        $grid->addColumn('ds_test_unit', 'Unit', '100px', $f->retTypePickList(), array('model' => 'tr/test_unit_model', 'codeField' => 'cd_test_unit'));

        $grid->addColumn('nr_goal', 'Goal', '100px', $f->retTypeNum(), array('precision' => '2', 'readonly' => false));
        $grid->addColumn('nr_output', 'Output', '100px', $f->retTypeNum(), array('precision' => '2', 'readonly' => false));


        $grid->addColumn('ds_workorders', 'WO', '100px', $f->retTypeStringAny(), false);


        $grid->addColumn('ds_location', 'Location', '150px', $f->retTypePickList(), array('model' => 'location_model', 'codeField' => 'cd_location'));
        //$grid->addColumn('ds_human_resource_te', 'TE', '150px', $f->retTypeStringAny(), false);
        $grid->setColumnRenderFunc("dt_est_start", 'dsFormPrjSheetObject.renderDisablePlanGridDates');
        $grid->setColumnRenderFunc("dt_est_finish", 'dsFormPrjSheetObject.renderDisablePlanGridDates');




        $grid->addColumnGroup(2, '');
        $grid->addColumnGroup(2, 'Type/Item');
        $grid->addColumnGroup(2, 'WI');
        $grid->addColumnGroup(1, 'Sample');
        $grid->addColumnGroup(2, 'Planned');
        $grid->addColumnGroup(2, 'Agreed');
        $grid->addColumnGroup(2, 'Actual');
        $grid->addColumnGroup(9, '');

        $grid->setGridVar('vPlanningGrid');
        $gridPlanning = $grid->retGridVar();

        /*         * ******************************* */



        // build area
        //$sch = $this->schmodel->getSchedules($cd_project, $cd_project_model, false);
        $sch = json_decode($line[0]['ds_schedules'], true);
        //die (print_r($sch));
        $htmlSchedule = $this->makeBuildArea($sch, 'N');


        $html = $this->load->view("tti/project_sheet_view", $trans + $line[0] + $mddata [0] + array('sc' => $sc,
                'toolbar' => $toolbar,
                'action' => $action,
                'htmlsch' => $htmlSchedule,
                'gridCHK' => $gridCHK,
                'gridAttachment' => $gridAttachment,
                'gridAttachmentMain' => $gridAttachmentMain,
                'gridPlanning' => $gridPlanning,
                'gridWO' => $gridWO,
                'gridWOTRMTE' => $gridWOTRMTE,
                'toolbarTests' => $toolbarTest,
                'canChangeDetail' => $canChangeDetails,
                'detailsRO' => $canChangeDetails == 'Y' ? 'N' : 'Y',
                'cd_human_resource' => $this->session->userdata('cd_human_resource'),
                'ds_human_resource' => $this->session->userdata('ds_human_resource_full'),
                'gridDates' => $gridDates,
                'builds' => json_encode($builds, JSON_NUMERIC_CHECK),
                'PrjModelGrid' => $PrjModelGrid), true);

        $send = array('html' => $html);

        echo (json_encode($send));
    }

    /**
     * @param $cd_project_model
     * Created on 04/02/2019
     * Create by Taylor.Dong
     */
    public function openPurFromWIForm($cd_project_model) {
        $ctabs = $this->ctabs;
        $grid = $this->w2gridgen;
        $f = $this->cfields;

        if (1 == 2) {
            $f = new Cfields();
            $grid = new w2gridgen();
            $ctabs = new ctabs();
        }

        $ctabs->addTab('Requests', 'tab_requests');
        $ctabs->addTab('Release', 'tab_release');
        $ctabs->setMainDivId('mainTabsReqDiv');
        $ctabs->setContentDivId('tab_requests_div');


        $grid->resetGrid();
        $grid->setForceDestroy(true);
        $grid->showToolbar(true);

        $grid->addCRUDToolbar(false, false, false, false, false);
        $grid->showFooter(false);
        $grid->setGridName('itemGrid');
        $grid->setGridDivName('itemGridDiv');
        $grid->setCRUDController('schedule/project_build_schedule_tests');
        $grid->addColumnKey();


        $grid->addColumn('ds_project_build_full', 'Build', '80px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_test_type', 'Test Type', '100px',  $f->retTypeStringAny(), false);
        $grid->addColumn('ds_test_item', 'Test Description', '400px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_tr_wi_data', 'WI on TR', '550px', $f->retTypeStringAny(), false);
        $grid->addColumn('nr_sample_quantity', 'Sample Qty', '80px', $f->retTypeInteger(), true);
        $grid->addColumn('nr_goal', 'Goal', '100px', $f->retTypeInteger(), true);
        $grid->addColumn('dt_est_start', 'Deadline', '100px', $f->retTypeDate(), true);
//        $grid->addColumn('dt_remark', 'Remarks', '300px', $f->retTypeStringAny(), true);

        $data=$this->buildschtstmodel->retRetrieveGridArray(' WHERE "PROJECT_BUILD_SCHEDULE".cd_project_model = '.$cd_project_model.' AND cd_tr_wi_data IS NOT NULL ');

        foreach ($data as $key => $row) {

            $data[$key]['material'] = $this->trwimodel->getMaterialList($row['cd_tr_wi_data']);

        }

        $grid->addRecords(json_encode($data));


        $javascript = $grid->retGrid();

//------------------------------------------------------------------------------------------------------------------------------------------------------------------

        $grid->resetGrid();
        $grid->setForceDestroy(true);
        $grid->showToolbar(true);
        $grid->addCRUDToolbar(false, false, false, false, false);
        $grid->showFooter(false);
        $grid->setGridName('materialGrid');
        $grid->setGridDivName('materialGridDiv');
        $grid->addColumnKey();

        $grid->setSingleBarControl(false);
        $grid->addColumn('nr_quantity', 'Budget Qty', '80px', $f->retTypeNum(), false);
        $grid->addColumn('MaterialPN', 'Material PN', '80px',  $f->retTypeStringAny(), false);
        $grid->addColumn('ds_equipment_design', 'Desc', '400px', $f->retTypeStringAny(), false);


        $javascript = $javascript.$grid->retGrid();



        //------------------------------------------------------------------------------------------------------------------------------------------------------------------------
        $grid->resetGrid();
        $grid->setForceDestroy(true);
        $grid->showToolbar(true);
        $grid->setGridToolbarFunction('dsPurWIObject.ToolbarGrid');
        $grid->addCRUDToolbar(false, false, true, false, false);
        $grid->showFooter(false);
        $grid->setGridName('buyGrid');
        $grid->setGridDivName('buyGridDiv');
        $grid->setCRUDController('schedule/project_build_schedule_tests_purchase_items');
        $grid->addColumnKey();
        $grid->setInsertNegative(true);
        $grid->setSingleBarControl(false);



        $grid->addColumn('ds_project_build_full', 'Build', '80px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_test_type', 'Test Type', '100px',  $f->retTypeStringAny(), false);
        $grid->addColumn('ds_test_item', 'Test Description', '100px', $f->retTypeStringAny(), false);
        $grid->addColumn('nr_calculated_quantity', 'Total Calculated', '80px',  $f->retTypeNum(), false);
        $grid->addColumn('nr_requested_quantity_to_buy', 'Total Required', '80px',  $f->retTypeNum(), true);
        $grid->addColumn('MaterialPN', 'Material PN', '80px',  $f->retTypeStringAny(), false);
        $grid->addColumn('ds_equipment_design', 'Desc', '300px', $f->retTypeStringAny(), false);

        $javascript = $javascript.$grid->retGrid();

        //------------------------------------------------------------------------------------------------------------------------------------------------
        $grid->resetGrid();
        $grid->setForceDestroy(true);
        $grid->showToolbar(true);
        $grid->setGridToolbarFunction('dsPurWIObject.ToolbarGridRelease');
        $grid->addUserBtnToolbar('btnSelectAll', 'Select All', 'fa fa-check-square-o');
        $grid->addUserBtnToolbar('btnRemoveAll', 'Remove All', 'fa fa-square-o');
        $grid->addUserBtnToolbar('btnRelease', 'Release Select', 'fa fa-clock-o');
        $grid->addUserBtnToolbar('btnRemoveRelease', 'Remove Release', 'fa fa-circle-o');
        $grid->addCRUDToolbar(false, false, true, true, false);

        $grid->showFooter(false);
        $grid->setGridName('releaseBuyGrid');
        $grid->setGridDivName('releaseBuyGridDiv');
        $grid->setCRUDController('schedule/project_build_schedule_tests_purchase_items');
        $grid->addColumnKey();
        $grid->setInsertNegative(true);
        $grid->setSingleBarControl(false);


        $grid->addColumn('release_icon', 'Release Status', '20px', $f->retTypeDate(), false);
        $grid->setColumnRenderFunc('release_icon', 'dsPurWIObject.releaseStatus');
        $grid->addColumn('ds_project_build_full', 'Build', '80px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_test_type', 'Test Type', '100px',  $f->retTypeStringAny(), false);
        $grid->addColumn('ds_test_item', 'Test Description', '200px', $f->retTypeStringAny(), false);
        $grid->addColumn('nr_sample_quantity', 'Sample Qty', '80px', $f->retTypeInteger(), false);
        $grid->addColumn('nr_goal', 'Goal', '80px', $f->retTypeInteger(), false);
        $grid->addColumn('nr_calculated_quantity', 'Total Calculated', '80px',  $f->retTypeNum(), false);
        $grid->addColumn('nr_requested_quantity_to_buy', 'Total Required', '80px',  $f->retTypeNum(), false);
        $grid->addColumn('nr_part_number', 'Material PN', '100px',  $f->retTypeStringAny(), false);
        $grid->addColumn('ds_equipment_design', 'Description', '300px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_tr_wi_data', 'WI on TR', '300px', $f->retTypeStringAny(), false);
        $grid->addColumn('dt_released_to_buy', 'Release Time', '120px', $f->retTypeDate(), false);

        $grid->addColumn('ds_human_resource_record', 'Record', '100px', $f->retTypeStringAny(), false);



        $data=$this->purchasemodel->retRetrieveGridJson(' WHERE "PROJECT_BUILD_SCHEDULE".cd_project_model = '.$cd_project_model.' AND "PROJECT_BUILD_SCHEDULE_TESTS_PURCHASE_ITEMS".cd_tr_wi_data IS NOT NULL ');
        $grid->addRecords( $data);


        $trans = array();
        $trans = $this->cdbhelper->retTranslationDifKeys($trans);

        $javascript = $javascript.$grid->retGrid();
        $send = array("javascript" => $javascript,
                'tab' => $ctabs->retTabs(),
                'datetime' => $this->getCdbhelper()->getDBTimeStampFormatted(),
                'cd_human_resource_record' => $this->session->userdata('cd_human_resource') ) + $trans;

        $this->load->view("tti/project_pur_from_wi_view" ,$send);

    }


    public function retDocRep($pk_code, $prjOrBuild = 'P', $echo = true) {
        if ($prjOrBuild == 'P') {
            $code = 7;
            $pk_field = 'cd_project_model_document_repository';
        } else {
            $code = 8;
            $pk_field = 'cd_project_build_schedule_document_repository';
        }

        $data = $this->docrepmodel->retrieveByRelation($code, $pk_code);

        foreach ($data as $key => $value) {
            $data[$key]['recid'] = $value[$pk_field];
        }

        //die (print_r($data));

        if ($echo) {
            echo(json_encode($data, JSON_NUMERIC_CHECK));
        } else {
            return $data;
        }
    }

    public function makeBuildArea($sch, $sc) {

        $transTst = $this->transTst;


        $ctabs = $this->ctabs;

        $htmlSchedule = '';

        foreach ($sch as $key => $value) {
            $toSend = array();

            $headerclass = 'box-info';

            if ($value['dt_deactivated_schedule'] != '') {
                $headerclass = $headerclass . ' collapsed-box';
            }

            $ds_project_build_title = $value['ds_project_build_abbreviation'];

            if ($value['fl_allow_multiples'] == 'Y') {
                //if ($value['nr_version'] == 0) {
                $ds_project_build_title = $ds_project_build_title . $value['nr_version'];
                //} else {
                //$ds_project_build_title = $ds_project_build_title . '*';
                //}
            }

            if ($value['dt_deactivated_schedule'] != '') {
                $ds_project_build_title = $ds_project_build_title . ' - REMOVED ON ' . $value['dt_deactivated_schedule'] . ' by ' . $value['ds_human_resource_deactivated'];
            }

            // create tabs
            $ctabs->ResetTabs();

            $count = $value['nr_test_count'];
            $count_tr = $value['nr_tr_count'];

            $ctabs->addTab('Overview', 'tab_schedule_' . $value['cd_project_build_schedule']);
            if ($value['fl_has_tests'] == 'Y') {
                $ctabs->addTab("Planning", 'tab_test_overview_' . $value['cd_project_build_schedule'], false, '', "<span data-toggle='tooltip' style='margin-left: 10px;' class='badge bg-blue'><i class='fa fa-calendar' aria-hidden='true' style='padding-right: 3px;display:none'></i> $count</span>");
                $ctabs->addTab('TR', 'tab_test_request_' . $value['cd_project_build_schedule'], false, '', "<span data-toggle='tooltip' style='margin-left: 10px;' class='badge bg-blue'><i class='fa fa-calendar' aria-hidden='true' style='padding-right: 3px;display:none'></i> $count_tr</span>");
            }

            $ctabs->setMainDivId('tabBuildSchedule_' . $value['cd_project_build_schedule'] . '_div');
            $ctabs->setContentDivId('tab_schedule_' . $value['cd_project_build_schedule'] . '_div');

            // Checkpoint area!
            $hasChk = $value['fl_has_checkpoints'];
            //$buildschcheckpoints = $this->buildschchkmodel->retRetrieveArray(' WHERE "PROJECT_BUILD_SCHEDULE_CHECKPOINTS".cd_project_build_schedule = ' . $value['cd_project_build_schedule'], ' ORDER BY nr_order ');
            $buildschcheckpoints = json_decode($value['ds_chklist'], true);

            if ($hasChk == 'Y') {
                $buildschcheckpoints = $this->getCheckPoints($value['cd_project_build_schedule'], $buildschcheckpoints);
            } elseif (count($buildschcheckpoints) > 0) {
                $hasChk = 'Y';
            }

            // test Items!!
            $htmlTst = '';
            if ($value['fl_has_tests'] == 'Y') {
                //$buildtests = $this->buildschtstmodel->retRetrieveArray(' WHERE "PROJECT_BUILD_SCHEDULE_TESTS".cd_project_build_schedule = ' .  $value['cd_project_build_schedule']);
                $buildtests = json_decode($value['ds_tst'], true);
                //$htmlTst = $this->makeTestItems($buildtests, 'N', $transTst);
                //$htmlTst = $this->makeTestItems($buildtests, 'N', $transTst, $value['cd_project_build_schedule']);
            }

            $toSend['headerclass'] = $headerclass;
            $toSend['ds_project_build_title'] = $ds_project_build_title;
            $toSend['tab'] = $ctabs->retTabs('tab_schedule_' . $value['cd_project_build_schedule']);
            $toSend['hasCHK'] = $hasChk;
            $toSend['chklist'] = json_encode($buildschcheckpoints, JSON_NUMERIC_CHECK);
            $toSend['tst'] = $htmlTst;
            $toSend['attach'] = json_encode($this->retDocRep($value['cd_project_build_schedule'], 'N', false), JSON_NUMERIC_CHECK);

            $toSend['comments'] = $value['ds_comm'];
            $toSend['hasCHK'] = $hasChk;
            $toSend['sc'] = $sc;
            $htmlSchedule = $htmlSchedule . $this->load->view("tti/project_sheet_builds_view", $value + $toSend + $this->trans, true);
        }

        return $htmlSchedule;
    }

    public function addNewTestItem($cd_project_build_schedule) {
        $new = $line = $this->buildschtstmodel->retRetrieveEmptyNewArray();
        $new[0]['cd_project_build_schedule'] = $cd_project_build_schedule;

        $locationDefault = $this->getCdbhelper()->getSystemParameters('DEFAULT_LOCATION_FOR_PROJECT');
        $locationarr = $this->locationmodel->retRetrieveArray('WHERE "LOCATION".cd_location = ' . $locationDefault)[0];
        $new[0]['cd_location'] = $locationarr['recid'];
        $new[0]['ds_location'] = $locationarr['ds_location'];
        $new[0]['wodata'] = '[]';


        $ret = array('html' => $this->makeTestItems($new, 'Y', $this->transTst), 'pk' => $new[0]['recid'], 'gridData' => json_encode($new, JSON_NUMERIC_CHECK));


        echo(json_encode($ret));
    }

    function copyFromTests($cd_schedule, $cd_schedule_from) {
        $retResult = $this->buildschtstmodel->retRetrieveArray(' WHERE "PROJECT_BUILD_SCHEDULE_TESTS".cd_project_build_schedule = ' . $cd_schedule_from);

        foreach ($retResult as $key => $value) {
            $retResult[$key]['cd_project_build_schedule'] = $cd_schedule;
            $retResult[$key]['cd_project_build_schedule_tests'] = $this->buildschtstmodel->getNextCode();
            $retResult[$key]['recid'] = $retResult[$key]['cd_project_build_schedule_tests'];

            $retResult[$key]['dt_est_start'] = '';
            $retResult[$key]['dt_est_finish'] = '';

            $retResult[$key]['dt_actual_start'] = '';
            $retResult[$key]['dt_actual_finish'] = '';


            $retResult[$key]['dt_start'] = '';
            $retResult[$key]['dt_finish'] = '';
            $retResult[$key]['fl_can_change_dates'] = 'Y';
            $retResult[$key]['wodata'] = '[]';




            $retResult[$key]['recid'] = $retResult[$key]['cd_project_build_schedule_tests'];
        }


        $ret = array('html' => $this->makeTestItems($retResult, 'Y', $this->transTst), 'pk' => $retResult[0]['recid'], 'gridData' => json_encode($retResult, JSON_NUMERIC_CHECK));
        echo(json_encode($ret));
    }

    function loadPlanning($cd_schedule) {
        $retResult = $this->buildschtstmodel->retRetrieveArray(' WHERE "PROJECT_BUILD_SCHEDULE_TESTS".cd_project_build_schedule = ' . $cd_schedule);

        if (count($retResult) == 0) {
            $ret = array('html' => '', 'pk' => -1);
        } else {
            $ret = array('html' => $this->makeTestItems($retResult, 'N', $this->transTst), 'pk' => $retResult[0]['recid'], 'gridData' => json_encode($retResult, JSON_NUMERIC_CHECK));
        }

        echo(json_encode($ret));
    }

    public function addNewBuild($cdprj, $cdmodel, $cdbuild) {
        $this->checkpoints = $this->buildchkmodel->retRetrieveArray(' WHERE dt_deactivated IS NULL', 'ORDER BY nr_order ');
        $line = $this->schmodel->retRetrieveEmptyNewArray();
        $build = $this->buildmodel->retRetrieveArray(" WHERE cd_project_build = $cdbuild")[0];
        $build['fl_has_tests'] = $build['fl_has_tests'] == 1 ? 'Y' : 'N';
        $build['fl_has_checkpoints'] = $build['fl_has_checkpoints'] == 1 ? 'Y' : 'N';
        $build['fl_allow_multiples'] = $build['fl_allow_multiples'] == 1 ? 'Y' : 'N';
        $build['fl_by_model'] = $build['fl_by_model'] == 1 ? 'Y' : 'N';
        unset($build['recid']);
        $line[0] = $build + $line[0];

        $line[0]['cd_project'] = $cdprj;
        $line[0]['cd_project_model'] = $cdmodel;
        $line[0]['cd_project_build'] = $cdbuild;
        $line[0]['ds_chklist'] = '[]';
        $line[0]['ds_tst'] = '[]';
        $line[0]['ds_comm'] = '[]';



        $ret = array('html' => $this->makeBuildArea($line, 'Y'), 'pk' => $line[0]['recid'], 'order' => $line[0]['nr_order']);

        echo(json_encode($ret, JSON_NUMERIC_CHECK));
    }

    public function getCheckPoints($cd_project_build_schedule, $buildschcheckpoints) {
        $code = -10;



        foreach ($this->checkpoints as $key => $value) {
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

    public function makeTestItems($array, $sc, $trans) {

        //$sc = "Y";
        //$line = $this->mainmodel->retRetrieveEmptyNewArray($this->mainmodel->retrOptionsOnlyProject);


        $html = '';
        foreach ($array as $key => $value) {
            //$wodata = $this->womodel->retRetrieveArray(" WHERE \"PROJECT_BUILD_SCHEDULE_TESTS_WO\".cd_project_build_schedule_tests = " . $value['recid']);


            $html = $html . $this->load->view("schedule/project_build_schedule_tests_row_form_view", array('sc' => $sc, 'showcalendar' => 'Y') + $trans + $value, true);
        }

        return $html;
    }

    public function makeTestItemsRequest($sc, $trans, $cd_project_build_schedule) {
        $ul = '<ul class="multi-nested-list">';

        foreach ($array as $key => $value) {
            $ul .= ' <li><a href="#"> ' . $value['recid'] . ' </a><ul>';
            // second level;
            $ul .= '<li><a href="#">LEVEL 2</a></li>';

            // close first level;
            $ul .= '</li></ul>';
        }
        $ul .= '</ul>';

        $html = $this->load->view("tti/project_build_schedule_tests_list_form_view", array('sc' => $sc, 'html' => $ul, 'cd_project_build_schedule' => $cd_project_build_schedule) + $trans, true);

        return $html;
    }

    public function getMETData($cd_project_build_schedule) {
        $datamte = $this->womodel->retTRMETData($cd_project_build_schedule);


        echo (json_encode($datamte));
    }

    public function retrieveGridJson($retrOpt = array(), $echo = true) {

        if (!$this->logincontrol->isProperLogged(false)) {
            echo ( '{"logged": "N", "resultset": [] }' );
            return;
        }



        $where = $this->getWhereToFilter();

        if (strpos($where, 'TR_TEST_REQUEST') !== FALSE) {
            $retrOpt = $this->mainmodel->retrOptionsTR;
        }


        $jsonMapping = $this->getJsonMappingToFilter();

        if (isset($retrOpt['whereToAdd'])) {
            $where = $where . $retrOpt['whereToAdd'];
        }

        if ($this->filterByCode != '{}') {
            $xxx = $this->filterByCode;
            $where = " AND \"PROJECT\".cd_project  = ANY('$xxx'::int[]) ";
        }

        $json = $this->mainmodel->retRetrieveGridJson($where, ' ORDER BY ds_update_formatted_order DESC ', $jsonMapping, $retrOpt);

        if ($echo) {
            echo ( '{ "logged": "Y", "resultset": ' . $json . ' }' );
        } else {
            return $json;
        }
    }

    public function getGanttData() {
        if (!$this->logincontrol->isProperLogged(false)) {
            echo ( '{"logged": "N", "resultset": [] }' );
            return;
        }
        $where = $this->getWhereToFilter($_POST['where']);
        $start = $_POST['start'];
        $end = $_POST['end'];

        $agree = $_POST['agreed'];
        $complete = $_POST['complete'];
        $planned = $_POST['planned'];
        $qtyToRetrieve = $_POST['qtyToRetrieve'];


        //$where =

        $datesFilterStart = '';
        $datesFilterEnd = '';
        $datescheck = '';
        $whereaddon = '';
        $datesControl = '';

        if ($_POST['planned'] == 'Y') {
            //$datesFilterStart = $datesFilterStart . "\"PROJECT_BUILD_SCHEDULE_TESTS\".dt_est_start::date, \"PROJECT_BUILD_SCHEDULE\".dt_est_start::date,";
            //$datesFilterEnd = $datesFilterEnd . "\"PROJECT_BUILD_SCHEDULE_TESTS\".dt_est_finish::date, \"PROJECT_BUILD_SCHEDULE\".dt_est_finish::date, \"PROJECT_BUILD_SCHEDULE\".dt_est_start::date,";
            $datesControl = $datesControl . " ( \"PROJECT_BUILD_SCHEDULE\".dt_est_range && daterange('$start', '$end', '[]') AND NOT EXISTS ( SELECT 1 FROM \"PROJECT_BUILD_SCHEDULE_TESTS\" x WHERE x.cd_project_build_schedule = \"PROJECT_BUILD_SCHEDULE\".cd_project_build_schedule ) ) OR ";


            $datesControl = $datesControl . " ( \"PROJECT_BUILD_SCHEDULE_TESTS\".dt_est_range && daterange('$start', '$end', '[]') ) OR ";

            $datescheck = $datescheck . '"PROJECT_BUILD_SCHEDULE".dt_est_start,';
        }


        if ($_POST['agreed'] == 'Y') {
            //$datesFilterStart = $datesFilterStart . "\"PROJECT_BUILD_SCHEDULE_TESTS\".dt_start::date,";
            //$datesFilterEnd = $datesFilterEnd . "\"PROJECT_BUILD_SCHEDULE_TESTS\".dt_finish::date,";
            $datescheck = $datescheck . '"PROJECT_BUILD_SCHEDULE_TESTS".dt_start,';
            $datesControl = $datesControl . "(  \"PROJECT_BUILD_SCHEDULE_TESTS\".dt_agreed_range && daterange('$start', '$end', '[]') AND \"PROJECT_BUILD_SCHEDULE_TESTS\".dt_agreed_range IS NOT NULL ) OR ";
        }

        if ($_POST['complete'] == 'Y') {
            //$datesFilterStart = $datesFilterStart . "\"PROJECT_BUILD_SCHEDULE_TESTS\".dt_actual_start::date,";
            //$datesFilterEnd = $datesFilterEnd . "\"PROJECT_BUILD_SCHEDULE_TESTS\".dt_actual_finish::date,";
            $datescheck = $datescheck . '"PROJECT_BUILD_SCHEDULE_TESTS".dt_actual_start,';
            $datesControl = $datesControl . " ( \"PROJECT_BUILD_SCHEDULE_TESTS\".dt_actual_range && daterange('$start', '$end', '[]') AND \"PROJECT_BUILD_SCHEDULE_TESTS\".dt_actual_range IS NOT NULL) OR ";
        }

        $datesControl = '(' . substr($datesControl, 0, -3) . ')';

        //$datesFilterStart = substr($datesFilterStart, 0, -1);
        //$datesFilterEnd = substr($datesFilterEnd, 0, -1);
        $datescheck = substr($datescheck, 0, -1);

        $whereaddon = " AND COALESCE( $datescheck) IS NOT NULL AND $datesControl";


        $canSeeAll = $this->getCdbhelper()->getUserPermission('fl_see_all_projects');
        if ($canSeeAll == 'N') {
            $hmcode = $this->session->userdata('cd_human_resource');
            $whereaddon = $whereaddon . " AND ( fl_confidential = 'N' OR EXISTS ( SELECT 1 FROM \"PROJECT_USER_ROLES\" x WHERE x.cd_project_model = \"PROJECT_MODEL\".cd_project_model AND x.cd_human_resource = $hmcode AND fl_active = 'Y') )";
        }



        //die ($whereaddon);


        $where = $where . $whereaddon;

        $where = str_replace("'", "''", $where);


        $sql = "select * from tti.makeGantData('$start', '$end', '$planned', '$agree', '$complete', ' $where ', $qtyToRetrieve)";

        //die ($sql);

        $data = $this->getCdbhelper()->basicSQLArray($sql);
        if ($data[0]['arrayproj'] != '') {
            $this->filterByCode = $data[0]['arrayproj'];
        } else {
            $this->filterByCode = '{-1}';
        }

        $x = $this->retrieveGridJson(array(), false);
        //$data = $this->prjmodelmodel->getGanntData($where);


        echo ( '{ "logged": "Y", "gantt": ' . $data[0]['gantt'] . ', "grid": ' . $x . ' }' );

        //echo($data);
    }

    public function deleteProject($prj) {
        $error = $this->mainmodel->deleteGridData(array($prj));
        echo($error);
    }

    public function downloadMatrixTemplate() {
        $apppath = APPPATH;

        $fileLocation = "$apppath/excelTemplates/LMS Project  planning Helper.xlsx";


        header('Content-Description: File Transfer');
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-Disposition: attachment; filename=\"" . basename($fileLocation) . "\"");
        header("Content-Transfer-Encoding: binary");
        header("Expires: 0");
        header("Pragma: public");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header('Content-Length: ' . filesize($fileLocation)); //Remove

        ob_clean();
        flush();

        readfile($fileLocation);
    }

    public function uploadMatrix() {
        //die (print_r($_FILES['file']['tmp_name']));
        $this->load->model("tr/test_unit_model", "unitmodel");
        $this->load->model("tr/test_type_model", "typemodel");

        $locationDefault = $this->getCdbhelper()->getSystemParameters('DEFAULT_LOCATION_FOR_PROJECT');
        $locationarr = $this->locationmodel->retRetrieveArray('WHERE "LOCATION".cd_location = ' . $locationDefault)[0];


        $trans = array('errorFile' => 'This file was not recorgnized. Plese check the format/extension!',
            'noLines' => 'No Information Imported. Check the data or the format',
            'errorReceiving' => 'Error Receiving File, please try again.',
        );
        $trans = $this->cdbhelper->retTranslationDifKeys($trans);

        if (count($_FILES) != 1) {
            die($trans['errorReceiving']);
        }
        $cd_schedule = $_POST['scr'];

        $this->load->library('cexcel');
        $xls = $this->cexcel;
        if (1 == 2) {
            $xls = new cexcel();
        }
        $filename = $_FILES['file']['tmp_name'];

        try {
            $xls->loadExcel($filename);
        } catch (Exception $e) {
            die($trans['errorFile']);
        };

        $i = 4;
        $dataGen = array();
        WHILE (true) {

            $type = $xls->getItem($i, 1);
            $planStart = $xls->getItemFormatted($i, 2);
            $planEnd = $xls->getItemFormatted($i, 3);
            $wi = $xls->getItem($i, 4);
            $wisection = $xls->getItem($i, 5);
            $item = $xls->getItem($i, 6);

            $smpnumber = $xls->getItem($i, 7);

            $goal = $xls->getItem($i, 8);
            $output = $xls->getItem($i, 9);
            $unit = $xls->getItem($i, 10);
            $spec = $xls->getItem($i, 11);
            $eol = $xls->getItem($i, 12);
            $witness = $xls->getItem($i, 13);

            if ($type == '') {
                break;
            }

            $retResult = $this->buildschtstmodel->retRetrieveEmptyNewArray()[0];
            $retResult['cd_project_build_schedule'] = $cd_schedule;

            //$retResult['cd_project_build_schedule_tests'] = $this->mainmodel->getNextCode();
            $retResult['recid'] = $retResult['cd_project_build_schedule_tests'];

            // starting data input$unit
            $unit = strtolower($this->cdbhelper->normalizeDataToSQL('ds_xx', $unit));
            $type = strtolower($this->cdbhelper->normalizeDataToSQL('ds_xx', $type));

            $unitData = $this->unitmodel->retRetrieveArray(" WHERE lower(ds_test_unit) = $unit");
            $typeData = $this->typemodel->retRetrieveArray(" WHERE lower(ds_test_type) = $type");


            // if date is not in the right format, remove.

            if ($planStart != '') {
                $ctrl = DateTime::createFromFormat($this->getCdbhelper()->dateFormatPHP, $planStart);
                if (!$ctrl) {
                    $planStart = '';
                }
            }


            // if date is not in the right format, remove.
            if ($planEnd != '') {
                $ctrl = DateTime::createFromFormat($this->getCdbhelper()->dateFormatPHP, $planEnd);
                if (!$ctrl) {
                    $planEnd = '';
                }
            }
            // adjust data
            if (!is_numeric($goal)) {
                $goal = '';
            }

            // adjust data
            if (!is_numeric($output)) {
                $output = '';
            }

            if (!is_numeric($smpnumber)) {
                $smpnumber = '';
            }



            IF ($eol == 'Y') {
                $eol = 1;
            } else {
                $eol = 0;
            }

            IF ($witness == 'Y') {
                $witness = 1;
            } else {
                $witness = 0;
            }


            if (count($typeData) == 1) {
                $retResult['cd_test_type'] = $typeData[0]['recid'];
                $retResult['ds_test_type'] = $typeData[0]['ds_test_type'];
            }

            if (count($unitData) == 1) {
                $retResult['cd_test_unit'] = $unitData[0]['recid'];
                $retResult['ds_test_unit'] = $unitData[0]['ds_test_unit'];
            }

            $retResult['dt_est_start'] = $planStart;
            $retResult['dt_est_finish'] = $planEnd;

            $retResult['dt_start'] = $planStart;
            $retResult['dt_finish'] = $planEnd;




            $retResult['ds_wi'] = $wi;
            $retResult['ds_wi_section'] = $wisection;
            $retResult['ds_test_item'] = $item;
            $retResult['nr_sample_quantity'] = $smpnumber;
            $retResult['nr_goal'] = $goal;

            $retResult['nr_output'] = $output;
            $retResult['ds_specification'] = $spec;

            $retResult['fl_eol'] = $eol;
            $retResult['fl_witness'] = $witness;

            $retResult['cd_location'] = $locationarr['recid'];
            $retResult['ds_location'] = $locationarr['ds_location'];



            array_push($dataGen, $retResult);

            $i++;
        }

        if (count($dataGen) == 0) {
            $ret = array('status' => $trans['noLines']);
        } else {
            $ret = array('html' => $this->makeTestItems($dataGen, 'Y', $this->transTst), 'pk' => $dataGen[0]['recid'], 'status' => 'OK', 'gridData' => json_encode($dataGen, JSON_NUMERIC_CHECK));
        }

        header('Content-type: application/json');
        echo(json_encode($ret));
    }

}
