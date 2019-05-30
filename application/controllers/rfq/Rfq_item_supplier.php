<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class rfq_item_supplier extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("rfq/rfq_model", "rfqmodel", TRUE);
        $this->load->model("rfq/rfq_item_model", "itemmodel", TRUE);
        $this->load->model("rfq/rfq_item_supplier_model", "mainmodel", TRUE);
        $this->load->model("rfq/rfq_item_supplier_quotation_model", "quomodel", TRUE);
        $this->load->model("rfq/rfq_item_supplier_sample_request_model", "smpmodel", TRUE);
        $this->load->model("currency_model", "curmodel", TRUE);
        $this->load->model("rfq/payment_term_model", "paymodel");
        $this->load->model("approval_steps_config_model", "stepmodel");
        $this->load->model("rfq/rfq_cost_center_model", "depcostmodel");
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

        $fm->addPickListFilter('Rfq Item', 'filter_1', 'rfq/rfq_item', '"RFQ_ITEM_SUPPLIER".cd_rfq_item');
        $fm->addPickListFilter('Supplier', 'filter_2', 'rfq/supplier', '"RFQ_ITEM_SUPPLIER".cd_supplier');
        $fm->addSimpleFilterUpper('Supplier Equipment Description', 'filter_3', '"RFQ_ITEM_SUPPLIER".ds_supplier_equipment_description');
        $fm->addSimpleFilterUpper('Supplier Equipment Part Number', 'filter_4', '"RFQ_ITEM_SUPPLIER".ds_supplier_equipment_part_number');


        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("rfq/rfq_item_supplier");

        $grid->addColumnKey();

        $grid->addColumn('ds_rfq_item', 'Rfq Item', '150px', $f->retTypePickList(), array('model' => 'rfq/rfq_item_model', 'codeField' => 'cd_rfq_item'));
        $grid->addColumn('ds_supplier', 'Supplier', '150px', $f->retTypePickList(), array('model' => 'rfq/supplier_model', 'codeField' => 'cd_supplier'));
        $grid->addColumn('ds_supplier_equipment_description', 'Supplier Equipment Description', '150px', $f->retTypeStringAny(), array('limit' => '1281'));
        $grid->addColumn('ds_supplier_equipment_part_number', 'Supplier Equipment Part Number', '150px', $f->retTypeStringAny(), array('limit' => '64'));

        $filters = $fm->retFiltersWithGroup();
        $javascript = $grid->retGrid();


        $trans = array();
        $trans = $this->cdbhelper->retTranslationDifKeys($trans);



        $send = array("javascript" => $javascript,
            "filters" => $filters,
            "filters_java" => $fm->retJavascript()) + $trans;


        $this->load->view("defaultView", $send);
    }

    public function openItemSupplier($cd_rfq_item) {
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

        $cur = $this->getCdbhelper()->getSystemParameters('DEFAULT_CURRENCY_RFQ');
        $cur = $this->curmodel->retRetrieveGridArray(' WHERE cd_currency= ' . $cur);
        $defpay = $this->getCdbhelper()->getSystemParameters('DEFAULT_PAYMENT_TERM_RFQ');
        $defpay = $this->paymodel->retRetrieveGridArray(' WHERE cd_payment_term = ' . $defpay);

        $item = $this->itemmodel->retRetrieveGridArray(' WHERE "RFQ_ITEM".cd_rfq_item = ' . $cd_rfq_item)[0];

        //"canChange" => $canChange, 'done' => $done, 'choosingSupplier' => $chosingSuppliersMode, 'canFinance' => $canFinance
        $rights = $this->rfqmodel->checkRights($item['cd_rfq']);
        $acualStep = $this->stepmodel->getActualStep('RFQ', $item['cd_rfq']);
        

        $defaultType = 'N';
        $otherType = 'R';
        if ($item['fl_is_repair'] == 1) {
            $defaultType = 'R';
            $otherType = 'N';
        }
        $qttyOpt = $item['fl_is_repair'] + $item['fl_is_new'];

        
        
        if ($rights['choosingSupplier'] || !$rights['canFinance']) {
            $ctabs->setContentDivId('tab_sample_div');
        } else {
            $ctabs->addTab('Quotation', 'tab_quotation');
            $ctabs->setContentDivId('tab_quotation_div');
        }
        $ctabs->addTab('Sample Request', 'tab_sample');
        $ctabs->setMainDivId('mainSupTabsDiv');



        // supplier info
        $grid->setSingleBarControl(true);
        $grid->setGridToolbarFunction("dsRFQSupjObject.ToolbarGridSup");

        if ($rights['canChange']) {
            $grid->addCRUDToolbar(false, false, true, false, false);
        }

        //$grid->addUserBtnToolbar('copysup', 'Copy Supplier from existing Items ', 'fa fa-copy');
        $grid->addBreakToolbar();
        $grid->addUserBtnToolbar('close', 'Close', 'fa fa-close');

        $grid->addSpacerToolbar();
        $grid->addExportToolbar();

        $grid->setToolbarSearch(true);
        $grid->setCRUDController("rfq/rfq_item_supplier");

        $grid->addColumnKey();

        $grid->addColumn('ds_supplier', 'Supplier', '40%', $f->retTypeStringAny(), false);
        $grid->addColumn('nr_round', 'Last Round', '60px', $f->retTypeInteger(), false);
        if ($rights['canFinance']) {
            $grid->addColumn('nr_tax', 'Tax', '60px', $f->retTypeNum(), array('precision' => '2', 'readonly' => !$rights['canChangeCost']));
        }
        $grid->addColumn('ds_supplier_equipment_description', 'Description', '30%', $f->retTypeStringAny(), array('limit' => '1281'));
        $grid->addColumn('ds_supplier_equipment_part_number', 'Part Number', '30$', $f->retTypeStringAny(), array('limit' => '64'));

        $grid->setGridName('supplierGrid');
        $grid->setGridDivName('supplierGridDiv');
        $grid->addRecords($this->mainmodel->retRetrieveGridJson(' WHERE "RFQ_ITEM_SUPPLIER".cd_rfq_item = ' . $cd_rfq_item, ' ORDER BY ds_supplier '));


        $SupModelGrid = $grid->retGrid();
        $grid->resetGrid();

        // quotation
        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->setInsertNegative(true);
        //$grid->addUserBtnToolbar('insertround', 'Insert New Round', 'fa fa-cart-plus', 'Insert New Round');
        //$grid->addUserBtnToolbar('insertselected', 'Insert For Selected Supplier', 'fa fa-plus', 'Insert Only Selected Supplier');



        if ($qttyOpt > 1) {
            if ($defaultType == 'R') {
                //$grid->addUserBtnToolbar('insertother', 'Insert For Selected Supplier - NEW', 'fa fa-plus-circle', 'Insert For Selected Supplier - NEW');
            } else {
                //$grid->addUserBtnToolbar('insertother', 'Insert For Selected Supplier - Repair', 'fa fa-plus-circle', 'Insert For Selected Supplier - Repair');
            }
        }

        if ($rights['choosingSupplier']) {
            $grid->addUpdToolbar();
        }
        $grid->addUserCheckToolbar('showlast', 'Show Only Last Round', 'Show Only Last Round', true);

        $grid->setToolbarSearch(true);

        $grid->addSpacerToolbar();
        $grid->addExportToolbar();


        $grid->setCRUDController("rfq/rfq_item_supplier_quotation");

        $grid->addColumnKey();

        $grid->setGridToolbarFunction("dsRFQSupjObject.ToolbarGridQou");

        $grid->addColumn('ds_supplier', 'Supplier', '150px', $f->retTypeStringAny(), false);

        
        $grid->addColumn('nr_round', 'Round', '60px', $f->retTypeInteger(), false);
        

        if ($qttyOpt > 1) {
            $grid->addColumn('ds_kind_description', 'Kind', '60px', $f->retTypeStringAny(), false);
        }

        if ($rights['canFinance']) {
            $grid->addColumn('ds_status', '', '30px', $f->retTypeStringAny(), false);
            $grid->setColumnRenderFunc('ds_status', 'dsRFQSupjObject.renderBuy');
        }
            
        $grid->addColumn('nr_qtty_to_buy', 'Qty to Buy', '80px', $f->retTypeNum(), array('precision' => '0', 'readonly' => !$rights['canChangeCost']));
        if ($rights['choosingSupplier']) {
            $grid->addColumn('ds_reason_to_choose_supplier', 'Remarks on Reason', '120px', $f->retTypeTextPL(), $rights['choosingSupplier']);
        } else {
            $grid->addColumn('ds_reason_to_choose_supplier', 'Remarks on Reason', '120px', $f->retTypeStringAny(), $rights['choosingSupplier']);
        }

        if ($rights['canFinance']) {
            
            $grid->addColumn('nr_price', 'Price', '100px', $f->retTypeNum(), array('precision' => '4', 'readonly' => !$rights['canChangeCost']));
            $grid->addColumn('nr_price_with_tax', 'Price Tax', '100px', $f->retTypeNum(), array('precision' => '4', 'readonly' => true));

            if ($rights['canChangeCost']) {
                $grid->addColumn('ds_currency', 'Currency', '100px', $f->retTypePickList(), array('model' => 'currency_model', 'codeField' => 'cd_currency'));
            } else {
                $grid->addColumn('ds_currency', 'Currency', '100px', $f->retTypeStringAny(), false);
            }

            $grid->addColumn('nr_price_default_currency', 'Price RMB', '100px', $f->retTypeNum(), array('precision' => '4', 'readonly' => true));
            $grid->addColumn('nr_price_with_tax_default_currency', 'Price Tax RMB', '100px', $f->retTypeNum(), array('precision' => '4', 'readonly' => true));
        }

        $grid->addColumn('nr_moq', 'MOQ', '80px', $f->retTypeNum(), array('precision' => '0', 'readonly' => false));
        $grid->addColumn('nr_leadtime', 'Leadtime', '100px', $f->retTypeInteger(), true);
        $grid->addColumn('dt_expiring_date', 'Expiring Date', '100px', $f->retTypeDate(), true);
        if ($rights['canFinance']) {
            $grid->addColumn('ds_payment_term', 'Payment Term', '100px', $f->retTypePickList(), array('model' => 'rfq/payment_term_model', 'codeField' => 'cd_payment_term'));
        }
        
        $grid->addColumn('nr_warranty', 'Warranty (Months)', '120px', $f->retTypeInteger(), true);
        $grid->addColumn('ds_remarks', 'Remarks', '120px', $f->retTypeTextPL(), true);
        $grid->addColumn('dt_update', 'Last Update', '120px', $f->retTypeDate(), false);

        $sqlQuo = ' WHERE "RFQ_ITEM_SUPPLIER".cd_rfq_item = ' . $cd_rfq_item . ' AND COALESCE("RFQ_ITEM_SUPPLIER_QUOTATION".nr_price, 0) > 0 ';

        // when choosing supplier only show the last one.
        if ($rights['choosingSupplier']) {
            $sqlQuo = $sqlQuo . ' AND ( ( "RFQ_SUPPLIER".nr_round = "RFQ_ITEM_SUPPLIER_QUOTATION".nr_round AND COALESCE("RFQ_ITEM_SUPPLIER_QUOTATION".nr_price, 0) > 0 ) OR COALESCE("RFQ_ITEM_SUPPLIER_QUOTATION".nr_qtty_to_buy, 0) > 0 ) ';
            
        }

        
        
        $grid->addRecords($this->quomodel->retRetrieveGridJson($sqlQuo, ' ORDER BY nr_round DESC, nr_round, ds_supplier ASC'));

        $grid->setGridName('quotationGrid');
        $grid->setGridDivName('tab_quotation_div');


        $SupModelGrid = $SupModelGrid . $grid->retGrid();
        $grid->resetGrid();

        //sample request:
        $grid->setGridToolbarFunction("dsRFQSupjObject.ToolbarGridSmp");
        $grid->setSingleBarControl(true);
        if ($rights['canSample'] ) {
            $grid->addCRUDToolbar(false, true, !$rights['canChange'], true, false);
            
        }

        $grid->setToolbarSearch(true);
        $grid->addDocRepToolbar();
        $grid->addSpacerToolbar();
        $grid->addExportToolbar();


        $grid->setCRUDController("rfq/rfq_item_supplier_sample_request");

        $grid->addColumnKey();
        $grid->addColumn('ds_supplier', 'Supplier', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('dt_requested', 'Requested', '80px', $f->retTypeDate(), true);
        $grid->addColumn('ds_human_resource_request', 'By', '100px', $f->retTypePickList(), array('model' => 'human_resource_model', 'codeField' => 'cd_human_resource_request'));
        $grid->addColumn('dt_deadline_to_receive', 'Deadline', '80px', $f->retTypeDate(), true);
        $grid->addColumn('nr_quantity', 'Quantity', '100px', $f->retTypeNum(), array('precision' => '0', 'readonly' => false));
        $grid->addColumn('ds_human_resource_received', 'Received by', '150px', $f->retTypePickList(), array('model' => 'human_resource_model', 'codeField' => 'cd_human_resource_received'));
        $grid->addColumn('dt_received', 'Receive At', '120px', $f->retTypeDate(), true);
        $grid->addColumn('ds_approval_status', 'Status', '80px', $f->retTypePickList(), array('model' => 'approval_status_model', 'codeField' => 'cd_approval_status'));
        $grid->addColumn('ds_human_resource_approval', 'By', '100px', $f->retTypePickList(), array('model' => 'human_resource_model', 'codeField' => 'cd_human_resource_approval'));
        $grid->addColumn('ds_comments', 'Comments', '80px',$f->retTypeTextPL(), true);

        $grid->setGridName('samplesGrid');
        $grid->setGridDivName('tab_sample_div');
        $grid->addRecords($this->smpmodel->retRetrieveGridJson(' WHERE "RFQ_ITEM_SUPPLIER".cd_rfq_item  =' . $cd_rfq_item, ' ORDER BY ds_supplier '));
        $SupModelGrid = $SupModelGrid . $grid->retGrid();


        $grid->resetGrid();
        $grid->setCRUDController("rfq/rfq_cost_center");
        $grid->setInsertNegative(true);
        $grid->setGridToolbarFunction("dsRFQSupjObject.ToolbarGridCC");
        $grid->setSingleBarControl(true);
        
        if ($rights['canChangeCost'] && false ) {
            $grid->addCRUDToolbar (false, true, !$rights['canFinance'], true, false);
            //$grid->addCRUDToolbar (false, false, false, false, false);
        }
        
        $grid->setToolbarSearch(true);

        $grid->addSpacerToolbar();
        $grid->addExportToolbar();

        //$grid->addColumn('nr_qtty_to_charge', 'Qty Charge', '80px', $f->retTypeNum(), array('precision' => '0', 'readonly' => false));
        //$grid->addColumn('ds_department_cost_center', 'Department', '100px', $f->retTypePickList(), array('model' => 'rfq/department_cost_center_model', 'codeField' => 'cd_department_cost_center'));
        //$grid->addColumn('ds_project_number', 'Project#', '100px', $f->retTypeStringAny(), array('limit' => '255'));
        //$grid->addColumn('ds_project_model_number', 'Model#', '100px', $f->retTypeStringAny(), array('limit' => '255'));
        //$grid->addColumn('ds_project_description', 'Project Description #', '100px', $f->retTypeStringAny(), array('limit' => '255'));
       
        $grid->addColumn('nr_qtty_to_charge', 'Qty Charge', '80px', $f->retTypeNum(), array('precision' => '0', 'readonly' => true));
        $grid->addColumn('ds_department_cost_center', 'Department', '100px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_project_number', 'Project#', '100px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_project_model_number', 'Model#', '100px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_project_description', 'Project Description #', '100px', $f->retTypeStringAny(), false);
        
        
        
        
        $grid->addRecords($this->depcostmodel->retRetrieveGridJson(' WHERE "RFQ_COST_CENTER".cd_rfq_item = ' . $cd_rfq_item, ' ORDER BY dt_record '));
        $grid->setGridName('depCostGrid');
        $grid->setGridDivName('depCostGriddiv');

        $depGrid = $grid->retGrid();
        $grid->resetGrid();


        $trans = array('errordeletequo' => 'You Only can delete the last round of the selected supplier!', 
            'errordeletesup' => 'You Only can delete the supplier if no Quotation or Samples related!', 'cannottwo' => 'You can only choose one Supplier',
            'errormustreason' => 'As you are not choosing the cheapest one you MUST inform reason'
            );

        $trans = $this->cdbhelper->retTranslationDifKeys($trans);
        $send = array('SupModelGrid' => $SupModelGrid, 'ctab' => $ctabs->retTabs(), 'cd_rfq_item' => $cd_rfq_item, 'kinddefault' => $defaultType, 'kindother' => $otherType, 'depGrid' => $depGrid);

        if (count($cur) > 0) {
            $send['cd_currency'] = $cur[0]['recid'];
            $send['ds_currency'] = $cur[0]['ds_currency'];
        } else {
            $send['cd_currency'] = -1;
            $send['ds_currency'] = '';
        }

        if (count($defpay) > 0) {
            $send['cd_payment_term'] = $defpay[0]['recid'];
            $send['ds_payment_term'] = $defpay[0]['ds_payment_term'];
        } else {
            $send['cd_payment_term'] = -1;
            $send['ds_payment_term'] = '';
        }

        $send['readonly'] = ( $rights['canChange'] ? 0 : 1 );
        $send['choosingSupplier'] = ( $rights['choosingSupplier'] ? 1 : 0 );
        $send['canFinance'] = ( $rights['canFinance'] ? 1 : 0 );
        $send['canChangeCost'] = ( $rights['canChangeCost'] ? 1 : 0 );
        $send['canSample'] = ( $rights['canSample'] ? 1 : 0 );
        

        $this->load->view("rfq/rfq_item_supplier_form_view", $trans + $send);
    }

    public function updateDataJsonData($cd_item) {
        $upd_array = json_decode($_POST['gridsup']);
        $upd_smp = json_decode($_POST['gridsmp']);
        $upd_quo = json_decode($_POST['gridquo']);
        $upd_cost = json_decode($_POST['cost']);

        $this->getCdbhelper()->trans_begin();
        $error = $this->mainmodel->updateGridData($upd_array);
        if ($error != 'OK') {
            $this->getCdbhelper()->trans_rollback();
            $msg = '{"status":' . json_encode($error) . ', "rs":{} }';
            echo($msg);
            return;
        }

        $error = $this->smpmodel->updateGridData($upd_smp);
        if ($error != 'OK') {
            $this->getCdbhelper()->trans_rollback();
            $msg = '{"status":' . json_encode($error) . ', "rs":{} }';
            echo($msg);
            return;
        }
        
        $error = $this->quomodel->updateGridData($upd_quo);
        if ($error != 'OK') {
            $this->getCdbhelper()->trans_rollback();
            $msg = '{"status":' . json_encode($error) . ', "rs":{} }';
            echo($msg);
            return;
        }
        
        $error = $this->depcostmodel->updateGridData($upd_cost);
        if ($error != 'OK') {
            $this->getCdbhelper()->trans_rollback();
            $msg = '{"status":' . json_encode($error) . ', "rs":{} }';
            echo($msg);
            return;
        }

        $this->getCdbhelper()->trans_commit();
        $this->getCdbhelper()->trans_end();

        $itemrow = $this->itemmodel->retRetrieveGridArray(' WHERE "RFQ_ITEM".cd_rfq_item = ' . $cd_item)[0];
        $rights = $this->rfqmodel->checkRights($itemrow['cd_rfq']);

        $sqlQuo = ' WHERE "RFQ_ITEM_SUPPLIER".cd_rfq_item = ' . $cd_item;

        // when choosing supplier only show the last one.
        if ($rights['choosingSupplier']) {
            $sqlQuo = $sqlQuo . ' AND "RFQ_SUPPLIER".nr_round = "RFQ_ITEM_SUPPLIER_QUOTATION".nr_round AND COALESCE("RFQ_ITEM_SUPPLIER_QUOTATION".nr_price, 0) > 0 ';
        }


        $retResult = $this->mainmodel->retRetrieveGridJson(' WHERE "RFQ_ITEM_SUPPLIER".cd_rfq_item = ' . $cd_item, ' ORDER BY ds_supplier ASC');
        $retResultQuo = $this->quomodel->retRetrieveGridJson($sqlQuo, ' ORDER BY nr_round DESC, ds_supplier ASC');
        $retResoultSmp = $this->smpmodel->retRetrieveGridJson(' WHERE "RFQ_ITEM_SUPPLIER".cd_rfq_item  =' . $cd_item, ' ORDER BY  ds_supplier ASC, cd_approval_status');
        $retResoultCost = $this->depcostmodel->retRetrieveGridJson(' WHERE "RFQ_COST_CENTER".cd_rfq_item  =' . $cd_item, ' ORDER BY  dt_record');

        // busco o gridChk;

        $msg = '{"status":' . json_encode($error) . ', "rs":' . $retResult . ', "quo": ' . $retResultQuo . ', "smp": ' . $retResoultSmp . ',"cost": ' . $retResoultCost . ', "item": ' . json_encode($itemrow, JSON_NUMERIC_CHECK) . ' }';

        echo $msg;
    }

    function copySuppliers($cd_rft_item_to) {

        $this->getCdbhelper()->basicSQLNoReturn("select rfqCopySuppliersToItem($cd_rft_item_to)");

        $retResult = $this->mainmodel->retRetrieveGridJson(' WHERE "RFQ_ITEM_SUPPLIER".cd_rfq_item = ' . $cd_rft_item_to, ' ORDER BY ds_supplier ASC');
        $retResultQuo = $this->quomodel->retRetrieveGridJson(' WHERE "RFQ_ITEM_SUPPLIER".cd_rfq_item = ' . $cd_rft_item_to, ' ORDER BY nr_round DESC, ds_supplier ASC');
        $retResoultSmp = $this->smpmodel->retRetrieveGridJson(' WHERE "RFQ_ITEM_SUPPLIER".cd_rfq_item  =' . $cd_rft_item_to, ' ORDER BY  ds_supplier ASC, cd_approval_status');

        $msg = '{"status":' . json_encode('OK') . ', "rs":' . $retResult . ', "quo": ' . $retResultQuo . ', "smp": ' . $retResoultSmp . '  }';

        echo($msg);
    }

}
