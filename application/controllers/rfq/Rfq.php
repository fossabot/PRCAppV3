<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class rfq extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("rfq/rfq_model", "mainmodel", TRUE);
        $this->load->model("rfq/rfq_item_model", "itemmodel", TRUE);
        $this->load->model("rfq/rfq_approval_steps_model", "stepsmodel", TRUE);
        $this->load->model("approval_steps_config_model", "stepsconfigmodel", TRUE);
        $this->load->model('docrep/document_repository_model', 'picmodel', TRUE);
        $this->load->model('rfq/rfq_cost_center_model', 'costmodel', TRUE);


        $trans = array(
            'formTrans_cd_human_resource_applicant' => 'Applicant',
            'formTrans_dt_request' => 'Request Date',
            'formTrans_dt_requested_complete' => 'Requested Complete Date',
            'formTrans_fl_is_urgent' => 'Urgent',
            'formTrans_cd_human_resource_purchase' => 'Buyer',
            'formTrans_ds_comments' => 'Comments',
            'formTrans_ds_cancel_reason' => 'Cancel Reason',
            'formTrans_ds_wf_number' => 'WF #',
            'formTrans_ds_rfq_number' => 'RFQ #',
            'formTrans_dt_deactivated' => 'Cancel',
            'itemsTitle' => 'Items',
            'formTrans_cd_rfq_item' => 'Code',
            'formTrans_cd_equipment_design' => 'Equipment',
            'formTrans_ds_equipment_design_code' => 'Code / Code Description',
            'formTrans_ds_brand' => 'Brand',
            'formTrans_cd_rfq_request_type' => 'Type',
            'formTrans_ds_reason_buy' => 'Reason',
            'formTrans_nr_qtty_quote' => 'Qty / Unit',
            'formTrans_fl_buy' => 'Buy',
            'formTrans_fl_online' => 'Online',
            'formTrans_supplier_leadtime' => 'MOQ / LeadTime',
            'formTrans_fl_need_sample' => 'Sample / Visit Deadline / Status',
            'formTrans_cd_supplier_selected' => 'Supplier',
            'formTrans_dt_deadline' => 'Deadline',
            'formTrans_ds_website' => 'Website / Buy Online',
            'formTrans_ds_remarks' => 'Technical Parameter',
            'formTrans_ds_attached_image' => 'Image',
            'formTrans_supplier_info' => 'Supplier / Total / Reason',
            'formTrans_depcost_info' => 'Department / Project / Model',
            'formTrans_nr_qtty_to_buy' => 'Qty Buy',
            'formTrans_nr_estimated_annual' => 'Est Annual Vol',
            'formTrans_ds_po_number' => 'PR#',
            'opensupplier' => 'Open Supplier Details',
            'delete' => 'Delete Line',
            'documents' => "Documents"
        );

        $this->trans = $this->cdbhelper->retTranslationDifKeys($trans);

        $this->isMaster = $this->getCdbhelper()->getUserPermission('fl_rfq_manager');
        $this->canChange = $this->getCdbhelper()->getUserPermission('fl_rfq_create_and_update;fl_rfq_team_approval;fl_rfq_release_to_quote;fl_rfq_quotation_release;fl_rfq_release_pr;fl_rfq_manager;fl_rfq_department_manager');
    }

    public function index() {

        parent::checkMenuPermission();
        $cduser = $this->session->userdata('cd_human_resource');

        $openfirst = 'N';


        $canSeeAll = $this->getCdbhelper()->getUserPermission('fl_rfq_see_all_purchase_request');

        $where = ' WHERE "RFQ".dt_deactivated is null ';
        if ($canSeeAll == 'N') {
            $where = $where . ' AND EXISTS ( SELECT 1 FROM  "APPROVAL_STEPS_CONFIG" where "APPROVAL_STEPS_CONFIG".cd_approval_steps_config = "RFQ_APPROVAL_STEPS".cd_approval_steps_config AND getUserPermission(ds_system_permission_ids, ' . $cduser . ' ) = \'Y\' )  ';
        }

        if (isset($_POST['param'])) {
            $openfirst = 'Y';
            $where = $where . ' AND "RFQ".cd_rfq = ' . json_decode($_POST['param'], true)['rfq'];
        }

       

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

        $ctabs->addTab('Browse', 'tab_browse');
        $ctabs->addTab('Details', 'tab_detail');
        $ctabs->setMainDivId('mainTabsDiv');
        $ctabs->setContentDivId('tab_browse_div');

        $fm->addFilterNumber('Code', 'filter_11', '"RFQ".cd_rfq');
        //$fm->addPickListFilter('Applicant', 'filter_1', 'human_resource_controller', '"RFQ".cd_human_resource_applicant');
        
        $fm->addFilter('filter_1', 'Applicant', array('controller' => 'human_resource_controller', 'fieldname' => '"RFQ".cd_human_resource_applicant', 'multi' => true));
        
        $fm->addPickListFilter('Buyer', 'filter_5', 'human_resource_controller', '"RFQ".cd_human_resource_purchase');
        $fm->addPickListFilterExists("Supplier", "rfq/supplier", "filter_10", "RFQ_ITEM", "cd_rfq_item", "RFQ_ITEM_SUPPLIER", "cd_supplier", "cd_rfq_item", false);



        $fixed = array(
            array('desc' => 'WITH STEP PENDINGS',
                'sql' => ' AND EXISTS ( SELECT 1 FROM "RFQ_APPROVAL_STEPS" where "RFQ_APPROVAL_STEPS".cd_rfq = "RFQ".cd_rfq AND "RFQ_APPROVAL_STEPS".cd_approval_status IS NULL )',
                'idDesc' => 1),
            array('desc' => 'FINISHED',
                'sql' => ' AND NOT EXISTS ( SELECT 1 FROM "RFQ_APPROVAL_STEPS" where "RFQ_APPROVAL_STEPS".cd_rfq = "RFQ".cd_rfq AND "RFQ_APPROVAL_STEPS".cd_approval_status IS NULL )',
                'idDesc' => 2),
            array('desc' => 'WAITING MY ACTION',
                'sql' => ' AND EXISTS ( SELECT 1 FROM  "APPROVAL_STEPS_CONFIG" where "APPROVAL_STEPS_CONFIG".cd_approval_steps_config = "RFQ_APPROVAL_STEPS".cd_approval_steps_config AND getUserPermission(ds_system_permission_ids, ' . $cduser . ' ) = \'Y\' )',
                'idDesc' => 3, 'default' => ($canSeeAll == 'Y' ? 'N' : 'Y' ) )
        );

        $fm->addFilter('filter_status', 'Status', array('plFixedSelect' => $fixed));

        $fixed = array(
            array('desc' => 'MISSING DEFINITION',
                'sql' => ' AND EXISTS ( SELECT 1 FROM "RFQ_ITEM_SUPPLIER_SAMPLE_REQUEST", "RFQ_ITEM_SUPPLIER" where "RFQ_ITEM_SUPPLIER".cd_rfq_item = "RFQ_ITEM".cd_rfq_item AND "RFQ_ITEM_SUPPLIER_SAMPLE_REQUEST".cd_rfq_item_supplier = "RFQ_ITEM_SUPPLIER".cd_rfq_item_supplier AND    "RFQ_ITEM_SUPPLIER_SAMPLE_REQUEST".cd_approval_status IS NULL )',
                'idDesc' => 1
            ),
            array('desc' => 'NEED SAMPLE REQUEST',
                'sql' => ' AND EXISTS ( SELECT 1 FROM "RFQ_ITEM_SUPPLIER" where "RFQ_ITEM_SUPPLIER".cd_rfq_item = "RFQ_ITEM".cd_rfq_item AND "RFQ_ITEM".fl_need_sample = \'Y\' )',
                'idDesc' => 2),
            array('desc' => 'NEED SAMPLE REQUEST AND MISSING REQUEST',
                'sql' => ' AND EXISTS ( SELECT 1 FROM "RFQ_ITEM_SUPPLIER" where "RFQ_ITEM_SUPPLIER".cd_rfq_item = "RFQ_ITEM".cd_rfq_item AND "RFQ_ITEM".fl_need_sample = \'Y\' AND NOT EXISTS ( SELECT 1 FROM "RFQ_ITEM_SUPPLIER", "RFQ_ITEM_SUPPLIER_SAMPLE_REQUEST" where "RFQ_ITEM_SUPPLIER".cd_rfq_item = "RFQ_ITEM".cd_rfq_item AND "RFQ_ITEM_SUPPLIER_SAMPLE_REQUEST".cd_rfq_item_supplier = "RFQ_ITEM_SUPPLIER".cd_rfq_item_supplier )   )',
                'idDesc' => 3)
        );

        $fm->addPickListFilter('Actual Step', 'filter_actual_step', 'approval_steps_config', '"RFQ_APPROVAL_STEPS".cd_approval_steps_config');

        $fm->addFilter('filter_sample', 'Sample Status', array('plFixedSelect' => $fixed));

        $fm->addSimpleFilterUpper('RFQ #', 'filter_8', '"RFQ".ds_rfq_number');
        $fm->addSimpleFilterUpper('W/F #', 'filter_9', '"RFQ".ds_wf_number');
        $fm->addFilterYesNo("Active", "dt_deactivated", '"RFQ".dt_deactivated', "Y");

        $this->setGridParser();
        $grid->setSingleBarControl(true);

        $grid->addCRUDToolbar(true, $this->canChange == 'Y', $this->canChange == 'Y', $this->canChange == 'Y', true);
        $grid->addBreakToolbar();
        $grid->addUserBtnToolbar('excel', 'Generate Excel', 'fa fa-file-excel-o');
        $grid->addUserBtnToolbar('downloaddata', 'Download Data', 'fa fa-download');
        $grid->addBreakToolbar();
        if($this->canChange == 'Y') {
            $grid->addUserBtnToolbar('duplicate', 'Duplicate', 'fa fa-files-o');
        }
        $grid->setToolbarSearch(true);
        $grid->setFilterPresetId('rfqbrowse');
        $grid->setCRUDController("rfq/rfq");

        $grid->addColumn('recid', 'Code', '60px', $f->retTypeStringAny(), false);

        $grid->addColumn('ds_human_resource_applicant', 'Applicant', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('dt_request', 'Request', '80px', $f->retTypeDate(), false);
        $grid->addColumn('dt_requested_complete', 'Requested Complete', '130px', $f->retTypeDate(), false);
        $grid->addColumn('fl_is_urgent', 'Urgent', '80px', $f->retTypeCheckBox(), false);
        $grid->addColumn('ds_human_resource_purchase', 'Buyer', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_comments', 'Comments', '150px', $f->retTypeStringAny(), false);


        $info = $this->stepsconfigmodel->getApprovalSteps('RFQ', -1);

        foreach ($info as $key => $value) {
            $grid->addColumn('ds_step_info_' . $value['cd_approval_steps_config'], $value['ds_approval_steps_config'], '150px', $f->retTypeDate(), false);
        }


        $grid->addColumn('ds_wf_number', 'WF Number', '100px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_rfq_number', 'RFQ Number', '100px', $f->retTypeStringAny(), false);


        $info = $this->stepsconfigmodel->getApprovalSteps('RFQ', -1);





        $grid->addColumn('ds_approval_steps_config_pending', 'Actual Step', '200px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_cancel_reason', 'Cancel Reason', '150px', $f->retTypeStringAny(), false);


        //$grid->addColumnDeactivated(false);

        $grid->setGridDivName('tab_browse_div');
        $grid->addRecords($this->mainmodel->retRetrieveGridJson($where));

        $filters = $fm->retFiltersWithGroup();
        $javascript = $grid->retGrid();

        $trans = array('duplicateconf' => 'Confirm Duplicate Selected Purchase Request');
        $trans = $this->cdbhelper->retTranslationDifKeys($trans);


        $send = array("javascript" => $javascript,
            "filters" => $filters,
            'tab' => $ctabs->retTabs(),
            'openFirst' => $openfirst,
            "filters_java" => $fm->retJavascript()) + $trans;

        $this->load->view("rfq/rfq_view", $send);
    }

    public function callRfqSheetForm($cd_rfq) {

        $grid = $this->w2gridgen;
        $f = $this->cfields;
        $fm = $this->cfiltermaker;

        if (1 == 2) {
            $f = new Cfields();
            $grid = new w2gridgen();
            $fm = new cfiltermaker();
        }

        if ($cd_rfq == -1) {
            $sc = "Y";
            $line = $this->mainmodel->retRetrieveEmptyNewArray();
            // set the requester as the logged user
            $cd_rfq = $line[0]['recid'];
            $line[0]['cd_human_resource_applicant'] = $this->session->userdata('cd_human_resource');
            $line[0]['ds_human_resource_applicant'] = $this->session->userdata('ds_human_resource_full');
            $line[0]['dt_request'] = $this->getCdbhelper()->getNow();
            $line[0]['cd_rfq'] = $cd_rfq;
            $mddata = $this->itemmodel->retRetrieveEmptyNewArray();
            $mddata[0]['cd_rfq'] = $cd_rfq;
            $steps = $this->stepsconfigmodel->getApprovalSteps('RFQ', $cd_rfq);
            if (count($steps)) {
                $steps[0]['recid'] = $this->stepsmodel->getNextCode();
            }

            // add basic model

            $action = 'I';
        } else {
            $sc = "N";
            $line = $this->mainmodel->retRetrieveArray(' WHERE "RFQ".cd_rfq        = ' . $cd_rfq);
            $mddata = $this->itemmodel->retRetrieveArray(' WHERE "RFQ_ITEM".cd_rfq = ' . $cd_rfq, 'ORDER BY "RFQ_ITEM".cd_rfq_item');
            $action = 'E';
            $steps = $this->stepsconfigmodel->getApprovalSteps('RFQ', $cd_rfq);
        }

        //"canChange" => $canChange, 'done' => $done, 'choosingSupplier' => $chosingSuppliersMode, 'canFinance' => $canFinance, 'firstStep'
        $rights = $this->mainmodel->checkRights($cd_rfq);


        //$mddata = '{}';
        // creating toolbar;
        //$grid->addCRUDToolbar(false, false, true, false, false);


        if ($this->canChange == 'Y') {
            if ($rights['firstStep']) {
                $grid->addInsToolbar();
                $grid->addBreakToolbar();
            }

            if(!$rights['done'])
            {
                $grid->addUpdToolbar();
            }



            if ($rights['canFinance']) {
                $grid->addUserBtnToolbar('opensupplier', 'Supplier', 'fa fa-money');
                $grid->addBreakToolbar();
                $grid->addUserBtnToolbar('openprinfo', 'PR Information', 'fa fa-file-text-o');
            }
            $grid->addBreakToolbar();
        }

        
        $grid->addUserBtnToolbar('openDep', 'Open Department Information', 'fa fa-braille');
        
        if ($rights['canChangeCost'] && $rights['canFinance']) {
            $grid->addUserBtnToolbar('openQuotationHistory', 'Quotation History', 'fa fa-history');
        }
        
        $grid->addBreakToolbar();
        

        $grid->addUseRadioToolbar('showall', 'All Items', 'All Items', true, 1);
        $grid->addUseRadioToolbar('showquote', 'Items With Quote', 'Items with Quote', false, 1);
        $grid->addUseRadioToolbar('shownoquote', 'Items W/Out Quote', 'Items W/Out Quote', false, 1);

        $grid->addBreakToolbar();

        $menu = $grid->addUserBtnToolbar('excel', 'Generate Excel', 'fa fa-file-excel-o');
        $grid->addUserBtnToolbar('allitems', 'All Items', '', 'All Items', $menu);
        $grid->addUserBtnToolbar('quoteitems', 'Items With Quote', '', 'Items With Quote', $menu);
        $grid->addUserBtnToolbar('noquoteitems', 'Items W/Out Quote', '', 'Items W/Out Quote', $menu);


        $menu = $grid->addUserBtnToolbar('downloaddata', 'Download Data', 'fa fa-download');
        $grid->addUserBtnToolbar('allitems', 'All Items', '', 'All Items', $menu);
        $grid->addUserBtnToolbar('quoteitems', 'Items With Quote', '', 'Items With Quote', $menu);
        $grid->addUserBtnToolbar('noquoteitems', 'Items W/Out Quote', '', 'Items W/Out Quote', $menu);




        $grid->setGridVar('vGridToToolbarRfq');
        $grid->setForceDestroy(false);
        $toolbar = $grid->retGridVar();

        $grid->resetGrid();
        $grid->setForceDestroy(true);
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("rfq/rfq_approval_steps");
        $grid->setGridVar('vStepsGrid');
        $grid->setGridName('stepsGrid');

        $grid->addColumnKey();
        $grid->addRecords(json_encode($steps, JSON_NUMERIC_CHECK));
        $gridSteps = $grid->retGridVar();


        //$htmlItems = '';
        $htmlItems = $this->getItems($mddata, $sc, $rights);


        $html = $this->load->view("rfq/rfq_sheet_view", $this->trans + $line[0] + array('sc' => $sc,
            'toolbar' => $toolbar,
            'action' => $action,
            'htmlItem' => $htmlItems,
            'gridSteps' => $gridSteps,
            'canChange' => $this->canChange,
            'isMaster' => $this->isMaster,
            'steptype' => "RFQ",
            'canSeeSupplier' => $rights['canFinance'] ? 'Y' : 'N',
            'canFinance' => $rights['canFinance'],
            'actualStep' => $rights['actualStep'],
            'firstStep' => $rights['firstStep'] ? 'Y' : 'N'), true);

        echo (json_encode(array('html' => $html, 'data' => $line), JSON_NUMERIC_CHECK));
    }

    public function updateDataJsonForm() {
        $upd_array = json_decode($_POST['upd']);

        $stepUpdate = json_decode($_POST['additionalData'], true)['stepsGrid'];
        $prj = $upd_array->recid;
        $cdmodel = 0;

        // I force sending the model when changing anything related to Build. I need to create a new one. 
        if (isset($upd_array->cd_project_model)) {
            $cdmodel = $upd_array->cd_project_model;
        }
        $decodedAdditional = json_decode($_POST['additionalData'], true);
        $upddatasch = array();

        $fielditem = array("cd_equipment_design" => isset($upd_array->cd_equipment_design) ? $upd_array->cd_equipment_design : array(),
            "cd_rfq_request_type" => isset($upd_array->cd_rfq_request_type) ? $upd_array->cd_rfq_request_type : array(),
            "ds_reason_buy" => isset($upd_array->ds_reason_buy) ? $upd_array->ds_reason_buy : array(),
            "nr_qtty_quote" => isset($upd_array->nr_qtty_quote) ? $upd_array->nr_qtty_quote : array(),
            "dt_deadline" => isset($upd_array->dt_deadline) ? $upd_array->dt_deadline : array(),
            "ds_website" => isset($upd_array->ds_website) ? $upd_array->ds_website : array(),
            "ds_remarks" => isset($upd_array->ds_remarks) ? $upd_array->ds_remarks : array(),
            "ds_brand" => isset($upd_array->ds_brand) ? $upd_array->ds_brand : array(),
            "fl_need_sample" => isset($upd_array->fl_need_sample) ? $upd_array->fl_need_sample : array(),
            "fl_online" => isset($upd_array->fl_online) ? $upd_array->fl_online : array(),
            "dt_supplier_visit_deadline" => isset($upd_array->dt_supplier_visit_deadline) ? $upd_array->dt_supplier_visit_deadline : array(),
            "ds_equipment_design_code_complement" => isset($upd_array->ds_equipment_design_code_complement) ? $upd_array->ds_equipment_design_code_complement : array(),
            "ds_equipment_design_desc_complement" => isset($upd_array->ds_equipment_design_desc_complement) ? $upd_array->ds_equipment_design_desc_complement : array(),
            "nr_estimated_annual" => isset($upd_array->nr_estimated_annual) ? $upd_array->nr_estimated_annual : array(),
            "ds_po_number" => isset($upd_array->ds_po_number) ? $upd_array->ds_po_number : array(),
            "cd_unit_measure" => isset($upd_array->cd_unit_measure) ? $upd_array->cd_unit_measure : array()
        );

        $upddataitem = $this->getCdbhelper()->createGridResultSetFormOrder(array(
            'fields' => $fielditem,
            'indexRSFieldName' => 'cd_rfq',
            'orderFieldName' => 'recid',
            'indexRSFind' => -1,
            'deleteField' => 'cd_rfq_request_type',
            'fixedData' => array('cd_project' => $prj, 'cd_project_model' => $cdmodel)
                )
        );


        $arraysend = array($upd_array);
        $rowBefore = $this->mainmodel->retRetrieveArray(' WHERE "RFQ".cd_rfq = ' .$prj);



//        echo($oldCancelStatus);
        $this->getCdbhelper()->trans_begin();


        $error = $this->mainmodel->updateGridData($arraysend);
        if ($error != 'OK') {
            $this->getCdbhelper()->trans_rollback();
            $msg = '{"status":' . json_encode($error) . ', "rs":{} }';
            echo($msg);
            return;
        }

        $error = $this->itemmodel->updateGridData($upddataitem->upd);
        if ($error != 'OK') {
            $this->getCdbhelper()->trans_rollback();
            $msg = '{"status":' . json_encode($error) . ', "rs":{} }';
            echo($msg);
            return;
        }

        $error = $this->stepsmodel->updateGridData($stepUpdate);
        if ($error != 'OK') {
            $this->getCdbhelper()->trans_rollback();
            $msg = '{"status":' . json_encode($error) . ', "rs":{} }';
            echo($msg);
            return;
        }



        $error = $this->itemmodel->deleteGridData($upddataitem->del);
        if ($error != 'OK') {
            $this->getCdbhelper()->trans_rollback();
            $msg = '{"status":' . json_encode($error) . ', "rs":{} }';
            echo($msg);
            return;
        }

        $sql = "select rfqRemoveBuyNotRound($prj);";

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

        $this->sendStepMail($stepUpdate);

        $retResult = $this->mainmodel->retRetrieveGridJson(' WHERE "RFQ".cd_rfq = ' . $arraysend[0]->recid);

        // busco o gridChk;

        $msg = '{"status":' . json_encode($error) . ', "rs":' . $retResult . ' }';

        echo $msg;


        $rowAfter = $this->mainmodel->retRetrieveArray(' WHERE "RFQ".cd_rfq = ' .$prj);

        
        if(count($rowBefore) > 0 &&  $rowBefore[0]['dt_deactivated'] != $rowAfter[0]['dt_deactivated'] )
        {
            $this->sendCancelEmail($rowAfter);
        }
//        echo($newCancelStatus);
    }

    function getItems($array, $sc, $rights) {

        $html = '';
        foreach ($array as $key => $value) {
            $html = $html . $this->load->view("rfq/rfq_item_row_view", $value + array('sc' => $sc) + $this->trans + $rights, true);
        }

        return $html;
    }

    function addNewItem($cd_rfq) {

        $rights = $this->mainmodel->checkRights($cd_rfq);

        $mddata = $this->itemmodel->retRetrieveEmptyNewArray();
        $mddata[0]['cd_rfq'] = $cd_rfq;

        $html = $this->getItems($mddata, 'Y', $rights);

        echo(json_encode(array('html' => $html)));
    }

    function sendStepMail($grid) {

        $this->load->library('sendmail');

        if (count($grid) == 0) {
            return;
        }

        $grid = $grid[0];



        $steps = $this->stepsmodel->retRetrieveArray(' WHERE cd_rfq_approval_steps  = ' . $grid['recid'])[0];
        $rfq = $this->mainmodel->retRetrieveArray(' WHERE "RFQ".cd_rfq = ' . $steps['cd_rfq'])[0];
        $stepsconfig = $this->stepsconfigmodel->retRetrieveArray(' WHERE cd_approval_steps_config = ' . $steps['cd_approval_steps_config'])[0];

        // email to the requestor!
//        $subject = 'LMS - RFQ # ' . $rfq['recid'] . ' ' . $stepsconfig['ds_approval_steps_config'] . ' - ' . $steps['ds_approval_status'];
        $subject ='The Purchase#'.$rfq['recid']  .' has been '.  $steps['ds_approval_status'] .' by '. $steps['ds_human_resource_define'] .' - ' . $stepsconfig['ds_approval_steps_config'];
        $reason = $steps['ds_remakrs'];
        ;

        if ($reason != '') {
            $reason = 'Reason: ' . $reason;
        }


        if ($steps['cd_approval_status'] == NULL) {
            return;
        }


        $usefrom = $rfq['cd_human_resource_applicant'];
        $this->sendmail->setSubject($subject);




        $before = $this->stepsconfigmodel->getActualStep('RFQ', $rfq['recid']);
        $after = $this->stepsconfigmodel->getStepAfter('RFQ', $steps['cd_approval_steps_config'], $rfq['recid']);


        $html = $this->load->view('mailtemplates/rfq_default', array(), true);



        // approved
        IF ($steps['cd_approval_status'] == 1) {

            $permission = $steps['ds_system_permission_ids_send_mail'];

            $whatnext = 'Wait for PR/POR approval in Workflow System and Wait for Purchasing issue PO.';
            if ($after) {
                $whatnext = $after['ds_instructions'];
            }
        } else {
            if (!$before) {
                return;
            }

            $permission = $before['ds_system_permission_ids'];
            $whatnext = $before['ds_instructions'];
        }


        $query = "SELECT distinct ds_e_mail FROM getUsersByPermissionReference ($usefrom, '$permission', 'B') where nr_depth != 1 ";

        $mail = $this->getCdbhelper()->basicSQLArray($query);

        $find = array('#code#',
            '#rejected#',
            '#user#',
            '#stepdescription#',
            '#reason#'
        );

        $replaces = array($rfq['recid'],
            $steps['ds_approval_status'],
            $steps['ds_human_resource_define'],
            $whatnext,
            $reason
        );

        $html = str_replace($find, $replaces, $html);

        $this->sendmail->addCC($rfq['ds_e_mail']);

        foreach ($mail as $key => $value) {
            $this->sendmail->addTO($value['ds_e_mail']);
        }

        $this->sendmail->setMessage($html);

        $attach = $this->createFilesAttached($steps['cd_rfq'], 1, false);
        $this->sendmail->addAttachment($attach);
        $this->sendmail->sendToSender(false);
        $this->sendmail->sendMail();

        unlink($attach);
    }

    function sendCancelEmail($row )
    {
        $PR = $row[0]['recid'];

        $mailTO= $row[0]['ds_e_mail'];
        $CancelBy=$this->session->userdata('ds_human_resource_full') ;

        if( $row[0]['dt_deactivated'] =='')
        {
            $CancelStatus="reopened";
        }
        else
        {
            $CancelStatus="closed";
        }

        $this->load->library('sendmail');


        $subject ='The Purchase#'.$PR .' has been '. $CancelStatus .' by '.$CancelBy;
        $this->sendmail->setSubject($subject);


        $this->sendmail->addTO($mailTO);

        $this->sendmail->sendMail();

    }

    public function createFilesAttached($cd_rfq, $excelData = 1, $download = true) {

        $excel = $this->makeExcel($cd_rfq, $excelData, true);

        return $this->rfqmodel->createFilesAttached($cd_rfq, "RFQ-$cd_rfq", array($excel), false, $download);
    }

    public function makeExcel($cd_rfq, $option = 1, $save = false) {

        // $option == 1 = all
        // $option == 2 = quotation
        // $option == 3 = no quotation

        $linesPerPage = 3;
        $lastLine = -1;

        $this->load->library('cexcel');
        $xls = $this->cexcel;
        if (1 == 2) {
            $xls = new cexcel();
        }


        // loading the models that will relate to a table
        $this->load->model("rfq/rfq_model", "rfqmodel", TRUE);
        $this->load->model("rfq/rfq_item_model", "rfqitemmmodel", TRUE);
        $this->load->model('human_resource_model', 'hmmodel');

        $appl = $this->hmmodel->retRetrieveArray(" WHERE cd_human_resource =  " . $this->getCdbhelper()->getSystemParameters('RFQ_APPLICANT_FOR_EXCEL'));
        $fileNameAddOn = 'All Items';



        // retrieve the information of the RFQ table, inside a array
        $header = $this->rfqmodel->retRetrieveArray(' WHERE "RFQ".cd_rfq        = ' . $cd_rfq);
        // retrieve the information of the RFQ_ITEM table, inside a array

        $itemwhere = ' WHERE "RFQ_ITEM".cd_rfq = ' . $cd_rfq;
        if ($option == 2) {
            $itemwhere = $itemwhere . ' AND EXISTS ( SELECT 1 FROM "RFQ_ITEM_SUPPLIER" s1, "RFQ_ITEM_SUPPLIER_QUOTATION" q1 WHERE s1.cd_rfq_item = "RFQ_ITEM".cd_rfq_item AND q1.cd_rfq_item_supplier = s1.cd_rfq_item_supplier AND q1.nr_price > 0 ) ';
            $fileNameAddOn = 'Only Quote';
        }

        if ($option == 3) {
            $itemwhere = $itemwhere . ' AND NOT EXISTS ( SELECT 1 FROM "RFQ_ITEM_SUPPLIER" s1, "RFQ_ITEM_SUPPLIER_QUOTATION" q1 WHERE s1.cd_rfq_item = "RFQ_ITEM".cd_rfq_item AND q1.cd_rfq_item_supplier = s1.cd_rfq_item_supplier AND q1.nr_price > 0 ) ';
            $fileNameAddOn = 'Not Quoted';
        }


        $items = $this->rfqitemmmodel->retRetrieveArray($itemwhere, 'ORDER BY ds_equipment_design ASC');






        // TO KNOW WHAT INFORMATION YOU HAVE YOU CAN CHECK THE models 


        $xls->newSpreadSheet('RFQ');
        $xls->selectActiveSheet('RFQ');
        //$xls->setFontDefault('Arial Unicode MS', 10);
        $xls->setPaperSize('A4');
        $xls->setPaperOrientation('L');
        $xls->setFitToWidth(true);
        $xls->setFitToHeight(false);
        $xls->setShowGridLines(false);

        $xls->selectArea(8, 1, 8, 13);
        $xls->setAlignVTop();

        $xls->setRowHeight(2, 30);
        $xls->setRowHeight(3, 30);
        $xls->setRowHeight(4, 30);
        $xls->setRowHeight(5, 30);



        //to access one field inside a array, keep in mind it is always two dimensions. first is the row index (starting in 0) and then the field name. For example, for the header:

        $xls->setItemString(1, 1, 'MIL LAB RFQ 詢 價 申 請 表');
        $xls->selectArea(1, 1);
        $xls->setFontSize(20);
        $xls->setFontBold(true);
        $xls->setAlignHCenter();


        $xls->selectArea(1, 1, 1, 10);
        $xls->mergeCells();


        $xls->selectArea(2, 1, 2, 2);
        $xls->mergeCells();
        $xls->selectArea(2, 4, 2, 5);
        $xls->mergeCells();
        $xls->selectArea(3, 1, 3, 2);
        $xls->mergeCells();
        $xls->selectArea(3, 4, 3, 5);
        $xls->mergeCells();
        $xls->selectArea(4, 1, 4, 2);
        $xls->mergeCells();
        $xls->selectArea(4, 4, 4, 5);
        $xls->mergeCells();
        $xls->selectArea(5, 1, 5, 2);
        $xls->mergeCells();
        $xls->selectArea(5, 4, 5, 5);
        $xls->mergeCells();

        //$xls->setColumnWidthAuto(2);

        $xls->setColumnWidth(2, 26);
        //$xls->setColumnWidthAuto(3);
        $xls->setColumnWidth(3, 80);
        $xls->setColumnWidth(4, 30);
        $xls->setColumnWidth(5,15);
        $xls->setColumnWidth(6,15);
        $xls->setColumnWidth(7, 24);
        $xls->setColumnWidth(8, 10);
        $xls->setColumnWidth(9, 18);
        $xls->setColumnWidth(10, 6);
        $xls->setColumnWidth(11, 15);
        $xls->setColumnWidth(12, 30);
//        $xls->setColumnWidth(13, 30);

        $xls->selectArea(2, 1, 5, 6);
        $xls->setBorderThin();
//        $phone = explode(' ', $header[0]['ds_phone']);
//        $phoneStr = $phone[count($phone) - 1];


        $xls->setItemString(2, 1, '申請部門 Request Dept');
        $xls->setItemString(2, 3, 'Mil Reliability Lab');
        $xls->setItemString(2, 4, '申請日期 Request Date');
        $xls->setItemDate(2, 6, $header[0]['dt_request']);
        $xls->setItemString(3, 1, '申請人 Applicant');
        $xls->setItemString(3, 3, $appl[0]['ds_human_resource_full']);
        $xls->setItemString(3, 4, '是否緊急情況詢價 Urgent or Not');
        $xls->setItemString(3, 6, $header[0]['fl_is_urgent'] = '1' ? 'Yes' : 'No');
        $xls->setItemString(4, 1, '聯絡電話 Phone');
        $xls->setItemString(4, 3, $appl[0]['ds_phone']);
        $xls->setItemString(4, 4, '要求完成報價日期 Request Complete Date');
        $xls->setItemDate(4, 6, $header[0]['dt_requested_complete']);
        $xls->setItemString(5, 1, '郵箱地址 Email Address');
        $xls->setItemString(5, 3, $appl[0]['ds_e_mail']);
        $xls->setItemString(5, 4, '採購 Buyer');
        $xls->setItemString(5, 6, $header[0]['ds_human_resource_purchase']);
        $xls->setItemString(7, 1, '具體要求:');
        $xls->selectArea(7, 1);
        $xls->setFontBold(true);
        $xls->setItemString(8, 1, "序號\nLine");
        $xls->setItemString(8, 2, "编号\nC/N & F/N");
        $xls->setItemString(8, 3, "物品名稱 \nGoods Name");
        $xls->setItemString(8, 4, "技术参数 /材質/規格尺寸型號\nTechnical Parameter/Size/Material");
        $xls->setItemString(8, 5, "类型\n#R/N/F/I/S/C");
        $xls->setItemString(8, 6, "品牌\nBrand");
//        $xls->setItemString(8, 7, "购买理由/维修/改造事项\nPurchase Reason / Repair & Improvement Issue");
        $xls->setItemString(8, 7, "數碼圖片/圖紙/参考网址\n Picture / Drawing / Website");
        $xls->setItemString(8, 8, "本次需求量\nRequire Qty");
        $xls->setItemString(8, 9, "估計年內需求量 \nEstimated Annual Volume");
        $xls->setItemString(8, 10, "单位\nUnit");
        $xls->setItemString(8, 11, "截止日期\nDeadline");
        $xls->setItemString(8, 12, "備注\nRemark");

        $xls->setRepeatingHeader(1, 8);
        $xls->selectArea(8, 1, 8, 12);
        $xls->setAlignHCenter();


        // loop to run inside the array. The $key is the first dimension, the row index.
        // the $valueItems is already the row, so don't need to refernece the row number to access the data:
        $curentRow = 9;
        $lastLine = $curentRow;
        foreach ($items as $key => $valueItems) {

            $costfdata = $this->costmodel->retRetrieveArray(' WHERE "RFQ_COST_CENTER".cd_rfq_item = ' . $valueItems['recid'], ' ORDER BY ds_department_cost_center');
            $xls->setRowHeight($curentRow, 100);

            // accessing directly from the row information....
//            $design_desc=$valueItems['ds_equipment_design_desc_complement'];
//            $design_code= $valueItems['ds_equipment_design_code'];
//             as $key is a number, according to the rows quanitty, I'm using as row in the system.. but doesn't need. The logic is yours

            $xls->setItemString($curentRow, 1, $key + 1);
            $xls->setItemString($curentRow, 2, $design_desc = $valueItems['ds_equipment_design_code']);
//            $xls->setItemString($curentRow, 3, $valueItems['ds_equipment_design_desc_complement ']);
            $desc = $valueItems['ds_equipment_design_description'];
            $descaddpn = $valueItems['ds_equipment_design_desc_complement'];

            if ($descaddpn != '') {
                $desc = "$descaddpn";
            }


            $xls->setItemString($curentRow, 3, $desc);
            $xls->setItemString($curentRow, 4, $valueItems['ds_remarks']);
            $xls->setItemString($curentRow, 5, $valueItems['ds_rfq_request_type']);
            $xls->setItemString($curentRow, 6, $valueItems['ds_brand']);
//            $xls->setItemString($curentRow, 7, $valueItems['ds_reason_buy']);

            $filename = $this->picmodel->getFirstPicture(1, $valueItems['recid']);
            $sizeImage = 120;
            if ($valueItems['ds_website'] != '') {
                $sizeImage = 100;
                $xls->setItemString($curentRow, 7, $valueItems['ds_website']);
                $xls->selectArea($curentRow, 7);
                $xls->setAlignVBottom();
            }


            If ($filename) {
                $xls->addPicture($curentRow, 7, $filename, $sizeImage, 160);
            }
            $xls->setItemFloat($curentRow, 8, $valueItems['nr_qtty_quote'], 0);
            $xls->setItemFloat($curentRow, 9, $valueItems['nr_estimated_annual'], 0);
            $xls->setItemString($curentRow, 10, $valueItems['ds_unit_measure']);
            $xls->setItemDate($curentRow, 11, $valueItems['dt_deadline']);
            $costDept = "";

            foreach ($costfdata as $key2 => $link) {
                $costDept = $costDept . $link['ds_department_cost_center'] . " ";
            }
            $projectNumber = "";
            foreach ($costfdata as $key3 => $link) {
                $projectNumber = $projectNumber . $link['ds_project_number'] . " " . $link['ds_project_model_number'];
            }

            $needsample = ($valueItems['fl_need_sample'] == 1) ? "Yes" : "No";
            $supplier_visit = ($valueItems['dt_supplier_visit_deadline'] == '') ? 'N/A' : $valueItems['dt_supplier_visit_deadline'];
//            $xls->setItemString($curentRow, 13, "1.COST DPT: $costDept\n" . "2.Project No :$projectNumber\n" . "3.Project Description:\n" . "4.是否需要样品(Yes/No):$needsample.\n5.是否需要供应商来厂看样及看样时间:$supplier_visit");
            $xls->setItemString($curentRow, 12, "1.是否需要样品(Yes/No):$needsample.\n2.是否需要供应商来厂看样及看样时间:$supplier_visit");
            $curentRow = $curentRow + 1;
        }

        $xls->selectArea(1, 1, 8, 12);
        $xls->setFontBold(true);

        $xls->selectArea(8, 1, $curentRow, 12);
        $xls->setBorderThin();


        $xls->selectArea(2, 1, $curentRow, 12);
        $xls->setFontSize(12);

        $xls->setColumnWidthAuto(5);
        $xls->setColumnWidthAuto(10);
        $xls->wrapText('C');
        $xls->wrapText('D');
        $xls->wrapText('G');


        $xls->setItemString($curentRow, 1, "說明: 申請部門不可直接向供應商詢價及議價,任何詢價需求都經采購部跟進. 申請部門如有推薦供應商,只需提供其供應商資料及聯絡方式給采購部.不可直接向供應商聯絡. \n 1. 申請者在發出其詢價申請表時,需抄送給其部門負責人. 2. 申請時,如未指定其品牌,規格,型號.申請者需提供其詳細的資料(包括具體的技術參數,樣板或圖片等).采購部將據此要求進行詢價.");
        $xls->selectArea($curentRow, 1, $curentRow, 12);
        $xls->mergeCells();
        $xls->setFontBold(true);
        $xls->setRowHeight($curentRow, 40);

        $filename = "RFQ-" . $cd_rfq . '-' . $fileNameAddOn . '-' . $header[0]['ds_request_date'] . '-' . $header[0]['ds_human_resource_applicant'];
        $xls->setFooter("Form No: $filename &R Page &P of &N", 'L');



        if ($save) {
            $filename = '/tmp/' . $filename . '.xlsx';
            $xls->saveAsXLSX($filename);
            $xls->cleanMemory();
            return $filename;
        }

        $xls->saveAsOutput($filename . '.xlsx');
        $xls->cleanMemory();
    }

    public function duplicateReq($cd_rfq) {
        $cd_rfq = $this->getCdbhelper()->normalizeDataToSQL('int', $cd_rfq);
        $sql = "select rfq.rfqidupicate($cd_rfq) as recid";


        $ret = $this->getCdbhelper()->basicSQLArray($sql);


        $retResult = $this->mainmodel->retRetrieveGridJson(' WHERE "RFQ".cd_rfq = ' . $ret[0]['recid']);

        $msg = '{"status": "OK", "rs": ' . $retResult . ' }';
        echo($msg);
    }

    public function openQuotationScreen() {
        $cduser = $this->session->userdata('cd_human_resource');

        parent::checkMenuPermission('openQuotationScreen');
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



        $fl_rfq_see_price = $this->getCdbhelper()->getUserPermission('fl_rfq_see_price');

        $ctabs->addTab('Browse', 'tab_browse');
        $ctabs->addTab('Details', 'tab_detail');
        $ctabs->setMainDivId('mainTabsDiv');
        $ctabs->setContentDivId('tab_browse_div');



        $fm->addFilterNumber('Code', 'filter_11', '"RFQ".cd_rfq');
       
        $fm->addFilterDate('Request Date', 'filter_req', '"RFQ".dt_request');
        $fm->addFilterDate('Requested Completion Date', 'filter_req_c', '"RFQ".dt_requested_complete');

        $fm->addFilter('filter_1', 'Applicant', array('controller' => 'human_resource_controller', 'fieldname' => '"RFQ".cd_human_resource_applicant', 'multi' => true));
        $fm->addPickListFilter('Buyer', 'filter_5', 'human_resource_controller', '"RFQ".cd_human_resource_purchase');
        $fm->addSimpleFilterUpper('Equipment Code/Description', 'filter_equp', '(  "EQUIPMENT_DESIGN".ds_equipment_description_full || ( CASE WHEN "RFQ_ITEM".ds_equipment_design_code_complement IS NOT NULL THEN \'-\' || "RFQ_ITEM".ds_equipment_design_code_complement ELSE \'\' END) || \' \' || COALESCE( "RFQ_ITEM".ds_equipment_design_desc_complement,"EQUIPMENT_DESIGN".ds_equipment_design,ds_equipment_design_desc_complement) )');
        $fm->addSimpleFilterUpper('RFQ Comment', 'filter_comment', '"RFQ".ds_comments');
        $fm->addPickListFilter('Supplier', 'filter_10', 'rfq/supplier', '"RFQ_ITEM_SUPPLIER".cd_supplier');

        //$fm->addPickListFilterExists("Supplier", "rfq/supplier", "filter_10", "RFQ_ITEM", "cd_rfq_item", "RFQ_ITEM_SUPPLIER", "cd_supplier", "cd_rfq_item", false);



        $fixed = array(
            array('desc' => 'WITH STEP PENDINGS',
                'sql' => ' AND EXISTS ( SELECT 1 FROM "RFQ_APPROVAL_STEPS" where "RFQ_APPROVAL_STEPS".cd_rfq = "RFQ".cd_rfq AND "RFQ_APPROVAL_STEPS".cd_approval_status IS NULL )',
                'idDesc' => 1),
            array('desc' => 'FINISHED',
                'sql' => ' AND NOT EXISTS ( SELECT 1 FROM "RFQ_APPROVAL_STEPS" where "RFQ_APPROVAL_STEPS".cd_rfq = "RFQ".cd_rfq AND "RFQ_APPROVAL_STEPS".cd_approval_status IS NULL )',
                'idDesc' => 2),
            array('desc' => 'WAITING MY ACTION',
                'sql' => ' AND EXISTS ( SELECT 1 FROM  "APPROVAL_STEPS_CONFIG" where "APPROVAL_STEPS_CONFIG".cd_approval_steps_config = "RFQ_APPROVAL_STEPS".cd_approval_steps_config AND getUserPermission(ds_system_permission_ids, ' . $cduser . ' ) = \'Y\' )',
                'idDesc' => 3),
            array('desc' => 'MISSING QUOTATION',
                'sql' => ' AND "RFQ_ITEM_SUPPLIER_QUOTATION".cd_rfq_item_supplier_quotation IS NULL',
                'idDesc' => 4),
            array('desc' => 'WITH QUOTATION',
                'sql' => ' AND "RFQ_ITEM_SUPPLIER_QUOTATION".cd_rfq_item_supplier_quotation IS NOT NULL AND "RFQ_ITEM_SUPPLIER_QUOTATION".nr_price > 0 ',
                'idDesc' => 5),
            array('desc' => 'WITH QUANTITY TO BUY',
                'sql' => ' AND COALESCE("RFQ_ITEM_SUPPLIER_QUOTATION".nr_qtty_to_buy, 0) > 0',
                'idDesc' => 5),
        );

        $fm->addFilter('filter_status', 'Status', array('plFixedSelect' => $fixed));

        $fixed = array(
            array('desc' => 'MISSING DEFINITION',
                'sql' => ' AND EXISTS ( SELECT 1 FROM "RFQ_ITEM_SUPPLIER_SAMPLE_REQUEST" WHERE "RFQ_ITEM_SUPPLIER_SAMPLE_REQUEST".cd_rfq_item_supplier = "RFQ_ITEM_SUPPLIER".cd_rfq_item_supplier AND    "RFQ_ITEM_SUPPLIER_SAMPLE_REQUEST".cd_approval_status IS NULL )',
                'idDesc' => 1
            ),
            array('desc' => 'NEED SAMPLE REQUEST',
                'sql' => ' AND EXISTS ( SELECT 1 FROM "RFQ_ITEM_SUPPLIER" where "RFQ_ITEM_SUPPLIER".cd_rfq_item = "RFQ_ITEM".cd_rfq_item AND "RFQ_ITEM".fl_need_sample = \'Y\' )',
                'idDesc' => 2),
            array('desc' => 'NEED SAMPLE REQUEST AND MISSING REQUEST',
                'sql' => ' "RFQ_ITEM".fl_need_sample = \'Y\'  AND NOT EXISTS ( SELECT 1 FROM "RFQ_ITEM_SUPPLIER_SAMPLE_REQUEST" where "RFQ_ITEM_SUPPLIER_SAMPLE_REQUEST".cd_rfq_item_supplier = "RFQ_ITEM_SUPPLIER".cd_rfq_item_supplier )   )',
                'idDesc' => 3)
        );

        $fm->addPickListFilter('Actual Step', 'filter_actual_step', 'approval_steps_config', '"RFQ_APPROVAL_STEPS".cd_approval_steps_config');

        $fm->addFilter('filter_sample', 'Sample Status', array('plFixedSelect' => $fixed));

        $fm->addSimpleFilterUpper('RFQ #', 'filter_8', '"RFQ".ds_rfq_number');
        $fm->addSimpleFilterUpper('W/F #', 'filter_9', '"RFQ".ds_wf_number');
        $fm->addFilterYesNo("Active", "dt_deactivated", '"RFQ".dt_deactivated', "Y");

        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar(true, false, false, false, true);
        $grid->addBreakToolbar();
        $grid->addUserBtnToolbar('excel', 'Generate RFQ Excel', 'fa fa-file-excel-o');
        $grid->addUserBtnToolbar('downloaddata', 'Download RFQ Data', 'fa fa-download');
        $grid->setFilterPresetId('rfqquotationsq2');


        $grid->setToolbarSearch(true);
        $grid->setCRUDController("rfq/rfq");
        $grid->setRowHeight('30');
        $grid->setDocRepId(1);
        $grid->setExcelDetailed(true);
        $grid->setExcelDetailedSendResultSet(true);

        //$grid->addColumnKey();
        
        $grid->addColumn('cd_rfq', 'Code', '80px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_human_resource_applicant', 'Applicant', '150px', $f->retTypeStringAny(), false);
        $grid->addHiddenColumn('cd_rfq_item', 'Image', '50px', $f->retTypeFirstPicture(), false);
        $grid->addColumn('ds_equipment_design_code', 'Equipment Code', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_equipment_design', 'Equipment Description', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_rfq_request_type', 'Type', '150px', $f->retTypeStringAny(), false);

        $grid->addColumn('nr_qtty_to_buy', 'Qty to Buy', '80px', $f->retTypeNum(), array('precision' => '0', 'readonly' => true));
        $grid->addColumn('ds_unit_measure', 'Unit', '50px', $f->retTypeStringAny(), false);

        if ($fl_rfq_see_price == 'Y') {
            $grid->addColumn('nr_price', 'Price', '100px', $f->retTypeNum(), array('precision' => '4', 'readonly' => true));
            $grid->addColumn('nr_price_with_tax', 'Price with Tax', '100px', $f->retTypeNum(), array('precision' => '4', 'readonly' => true));
            $grid->addColumn('ds_currency', 'Currency', '100px', $f->retTypeStringAny(), false);
            $grid->addColumn('ds_supplier', 'Supplier', '150px', $f->retTypeStringAny(), false);
            $grid->addColumn('nr_moq', 'MOQ', '80px', $f->retTypeNum(), array('precision' => '0', 'readonly' => true));
            $grid->addColumn('nr_leadtime', 'Leadtime', '100px', $f->retTypeInteger(), false);
            $grid->addColumn('dt_expiring_date', 'Expiring Date', '100px', $f->retTypeDate(), false);
        }

        $grid->addColumn('ds_wf_number', 'RFQ WF Number', '100px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_rfq_number', 'RFQ Number', '100px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_dep_cost', 'Department / Project / Model', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_website', 'Website / Online', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_comments', 'Comments', '150px', $f->retTypeStringAny(), false);
        if ($fl_rfq_see_price == 'Y') {
            $grid->addColumn('ds_reason_buy', 'Reason to Buy', '150px', $f->retTypeStringAny(), false);
            $grid->addColumn('ds_remarks_quotation', 'Remarks', '120px', $f->retTypeStringAny(), false);
        }

        $grid->addColumn('dt_request', 'Request', '80px', $f->retTypeDate(), false);
        $grid->addColumn('dt_requested_complete', 'Requested Complete', '130px', $f->retTypeDate(), false);
        $grid->addColumn('fl_is_urgent', 'Urgent', '80px', $f->retTypeCheckBox(), false);
        $grid->addColumn('dt_deadline', 'Deadline', '80px', $f->retTypeDate(), false);
        $grid->addColumn('ds_human_resource_purchase', 'Buyer', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_approval_steps_config_pending', 'Actual Step', '200px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_brand', 'Brand', '80px', $f->retTypeStringAny(), false);
        $grid->addColumn('nr_qtty_quote', 'Qty to Quote', '100px', $f->retTypeInteger(), false);
        $grid->addColumn('nr_estimated_annual', 'Estimated Annual', '80px', $f->retTypeInteger(), false);
        $grid->addColumn('ds_remarks_item', 'Technical Parameter', '100px', $f->retTypeStringAny(), false);
        $grid->addColumn('fl_need_sample', 'Need Sample', '100px', $f->retTypeCheckBox(), false);
        $grid->addColumn('dt_supplier_visit_deadline', 'Supplier Deadline', '100px', $f->retTypeDate(), false);
        $grid->addColumn('ds_sample_info', 'Sample Status', '100px', $f->retTypeStringAny(), false);
        if ($fl_rfq_see_price == 'Y') {
            $grid->addColumn('nr_round', 'Round', '60px', $f->retTypeInteger(), false);
        }
        $grid->addColumn('ds_kind_description', 'Kind', '60px', $f->retTypeStringAny(), false);
        if ($fl_rfq_see_price == 'Y') {
            $grid->addColumn('ds_reason_to_choose_supplier', 'Remarks on Reason', '120px', $f->retTypeStringAny(), false);
            $grid->addColumn('nr_price_default_currency', 'Price RMB', '100px', $f->retTypeNum(), array('precision' => '4', 'readonly' => true));
            $grid->addColumn('nr_price_with_tax_default_currency', 'Price with Tax RMB', '100px', $f->retTypeNum(), array('precision' => '4', 'readonly' => true));
            $grid->addColumn('ds_payment_term', 'Payment Term', '100px', $f->retTypeStringAny(), false);
            $grid->addColumn('nr_warranty', 'Warranty (Months)', '120px', $f->retTypeInteger(), false);
            $grid->addColumn('nr_total_price', 'Total Price', '150px', $f->retTypeNum(), array('precision' => '4', 'readonly' => false));
            $grid->addColumn('nr_total_price_with_tax', 'Total Price With Tax', '150px', $f->retTypeNum(), array('precision' => '4', 'readonly' => false));
            $grid->addColumn('nr_total_price_rmb', 'Total Price Rmb', '150px', $f->retTypeNum(), array('precision' => '4', 'readonly' => false));
            $grid->addColumn('nr_total_price_rmb_with_tax', 'Total Price Rmb With Tax', '150px', $f->retTypeNum(), array('precision' => '4', 'readonly' => false));
        }

        $info = $this->stepsconfigmodel->getApprovalSteps('RFQ', -1);

        foreach ($info as $key => $value) {
            $grid->addColumn('ds_step_info_' . $value['cd_approval_steps_config'], $value['ds_approval_steps_config'], '150px', $f->retTypeDate(), false);
        }
        
        $grid->setGridDivName('tab_browse_div');

        $filters = $fm->retFiltersWithGroup();
        $javascript = $grid->retGrid();


        $trans = array();
        $trans = $this->cdbhelper->retTranslationDifKeys($trans);

        $send = array("javascript" => $javascript,
            "filters" => $filters,
            'tab' => $ctabs->retTabs(),
            "filters_java" => $fm->retJavascript()) + $trans;


        $this->load->view("rfq/rfq_quote_details_view", $send);
    }

    public function retrQuoData() {
        parent::retrieveGridJson($this->mainmodel->retrOptionsQUOSCR);
    }


//itemmodel    
}
