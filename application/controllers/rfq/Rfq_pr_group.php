<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class rfq_pr_group extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("rfq/rfq_pr_group_model", "mainmodel", TRUE);
        $this->load->model("rfq/rfq_pr_group_distribution_model", "rfqprdist", TRUE);
        $this->load->model("rfq/rfq_model", "rfqmodel", TRUE);
        $this->load->model("rfq/rfq_item_model", "rfqitemmodel", TRUE);
        $this->load->model('docrep/document_repository_model', 'picmodel', TRUE);
        $this->load->model('rfq/rfq_pr_incoming_outcoming_model', 'inputoutputmodel', TRUE);
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

        $fm->addPickListFilter('Rfq', 'filter_1', 'rfq/rfq', '"RFQ_PR_GROUP".cd_rfq');
        $fm->addPickListFilter('Supplier', 'filter_2', 'rfq/supplier', '"RFQ_PR_GROUP".cd_supplier');
        $fm->addPickListFilter('Currency', 'filter_3', 'currency', '"RFQ_PR_GROUP".cd_currency');
        $fm->addPickListFilter('Department Cost Center', 'filter_4', 'rfq/department_cost_center', '"RFQ_PR_GROUP".cd_department_cost_center');
        $fm->addSimpleFilterUpper('Project Number', 'filter_5', '"RFQ_PR_GROUP".ds_project_number');
        $fm->addSimpleFilterUpper('Project Model Number', 'filter_6', '"RFQ_PR_GROUP".ds_project_model_number');
        $fm->addSimpleFilterUpper('POR/PR Sequence #', 'filter_12', '"RFQ_PR_GROUP".ds_pr_number');


        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("rfq/rfq_pr_group");

        $grid->addColumnKey();

        $grid->addColumn('ds_rfq', 'Rfq', '150px', $f->retTypePickList(), array('model' => 'rfq/rfq_model', 'codeField' => 'cd_rfq'));
        $grid->addColumn('ds_supplier', 'Supplier', '150px', $f->retTypePickList(), array('model' => 'rfq/supplier_model', 'codeField' => 'cd_supplier'));
        $grid->addColumn('ds_currency', 'Currency', '150px', $f->retTypePickList(), array('model' => 'currency_model', 'codeField' => 'cd_currency'));
        $grid->addColumn('ds_department_cost_center', 'Department Cost Center', '150px', $f->retTypePickList(), array('model' => 'rfq/department_cost_center_model', 'codeField' => 'cd_department_cost_center'));


        $grid->addColumn('ds_project_number', 'Project Number', '150px', $f->retTypeStringAny(), array('limit' => ''));
        $grid->addColumn('ds_project_model_number', 'Project Model Number', '150px', $f->retTypeStringAny(), array('limit' => ''));
        $grid->addColumn('nr_total_qty', 'Total Qty', '150px', $f->retTypeNum(), array('precision' => '2', 'readonly' => false));
        $grid->addColumn('nr_total_price', 'Total Price', '150px', $f->retTypeNum(), array('precision' => '4', 'readonly' => false));
        $grid->addColumn('nr_total_price_with_tax', 'Total Price With Tax', '150px', $f->retTypeNum(), array('precision' => '4', 'readonly' => false));
        $grid->addColumn('nr_total_price_rmb', 'Total Price Rmb', '150px', $f->retTypeNum(), array('precision' => '4', 'readonly' => false));
        $grid->addColumn('nr_total_price_rmb_with_tax', 'Total Price Rmb With Tax', '150px', $f->retTypeNum(), array('precision' => '4', 'readonly' => false));
        $grid->addColumn('ds_pr_number', 'POR/PR Sequence #', '150px', $f->retTypeStringAny(), array('limit' => ''));


        $filters = $fm->retFiltersWithGroup();
        $javascript = $grid->retGrid();


        $trans = array();
        $trans = $this->cdbhelper->retTranslationDifKeys($trans);


        $send = array("javascript" => $javascript,
            "filters" => $filters,
            "filters_java" => $fm->retJavascript()) + $trans;


        $this->load->view("defaultView", $send);
    }

    public function openForm($cd_rfq) {

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


        $rights = $this->rfqmodel->checkRights($cd_rfq);

        if (!$rights['canFinance']) {
            die('You dont have rights here');
        }
        // supplier info
        $grid->setSingleBarControl(true);
        $grid->setGridToolbarFunction("dsRFQSupObject.ToolbarGrid");
        $grid->addCRUDToolbar(false, false, true, false, false);
        $grid->addUserBtnToolbar('excel', 'Download Data', 'fa fa-download');

        $grid->addBreakToolbar();

        $grid->addUserBtnToolbar('close', 'Close', 'fa fa-close');

        $grid->addSpacerToolbar();

        $grid->setToolbarSearch(true);
        $grid->setCRUDController("rfq/rfq_pr_group");

        $grid->addColumnKey();
        $grid->addColumn('ds_kind_description', 'Kind', '60px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_supplier', 'Supplier', '150px', $f->retTypeStringAny(), false);

        $grid->addColumn('ds_department_cost_center', 'Department Cost Center', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_department_account_code', 'Account Code', '80px', $f->retTypePickList(), array('model' => 'rfq/department_account_code_model', 'codeField' => 'cd_department_account_code'));
        $grid->addColumn('ds_expense_type', 'Expense Type', '100px', $f->retTypePickList(), array('model' => 'rfq/expense_type_model', 'codeField' => 'cd_expense_type'));

        $grid->addColumn('ds_project_number', 'Project', '100px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_project_model_number', 'Model', '100px', $f->retTypeStringAny(), false);
        $grid->addColumn('nr_total_qty', 'Total Qty', '100px', $f->retTypeNum(), array('precision' => '0', 'readonly' => true));
        $grid->addColumn('ds_currency', 'Currency', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('nr_total_price', 'Total Price', '100px', $f->retTypeNum(), array('precision' => '4', 'readonly' => true));
        $grid->addColumn('nr_total_price_with_tax', 'Total Tax', '100px', $f->retTypeNum(), array('precision' => '4', 'readonly' => true));
        $grid->addColumn('nr_total_price_rmb', 'Total RMB', '100px', $f->retTypeNum(), array('precision' => '4', 'readonly' => true));
        $grid->addColumn('nr_total_price_rmb_with_tax', 'Total RMB TAX', '150px', $f->retTypeNum(), array('precision' => '4', 'readonly' => true));
        $grid->addColumn('ds_pr_number', 'POR/PR Sequence', '150px', $f->retTypeStringAny(), array('limit' => ''));
        $grid->addColumn('ds_por_reference', 'POR/PR Reference', '150px', $f->retTypeStringAny(), array('limit' => ''));
        $grid->addColumn('ds_po_number', 'PO#', '150px', $f->retTypeStringAny(), array('limit' => ''));
        $grid->addColumn('ds_remarks', 'Remarks', '100px', $f->retTypeStringAny(), array('limit' => ''));
        $grid->addColumnDeactivated(true);
        $grid->showExpandColumn('dsRFQSupObject.expandData');

        $grid->addRecords($this->mainmodel->retRetrieveGridJson(' WHERE "RFQ_PR_GROUP".cd_rfq = ' . $cd_rfq, ' ORDER BY ds_supplier '));
        $grid->setGridName('supplierGrid');
        $grid->setGridDivName('supplierGridDiv');


        $SupModelGrid = $grid->retGrid();
        $grid->resetGrid();

        $grid->addColumnKey();

        $grid->setGridToolbarFunction("dsRFQSupjObject.ToolbarGridQou");

        $grid->addColumn('ds_equipment_design_code', 'Equipment Code', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_equipment_design_description', 'Equipment Description', '150px', $f->retTypeStringAny(), false);


        //$grid->addColumn('ds_status', '', '30px', $f->retTypeStringAny(), false);
        //$grid->setColumnRenderFunc('ds_status', 'dsRFQSupjObject.renderBuy');


        $grid->addColumn('nr_qtty_to_charge', 'Qty to Buy', '80px', $f->retTypeNum(), array('precision' => '0', 'readonly' => true));
        $grid->addColumn('ds_reason_to_choose_supplier', 'Remarks on Reason', '120px', $f->retTypeStringAny(), false);


        $grid->addColumn('nr_price', 'Price', '100px', $f->retTypeNum(), array('precision' => '4', 'readonly' => true));
        $grid->addColumn('nr_price_with_tax', 'Price Tax', '100px', $f->retTypeNum(), array('precision' => '4', 'readonly' => true));

        $grid->addColumn('nr_total_price', 'Total Price', '100px', $f->retTypeNum(), array('precision' => '4', 'readonly' => true));
        $grid->addColumn('nr_total_price_with_tax', 'Total Tax', '100px', $f->retTypeNum(), array('precision' => '4', 'readonly' => true));
        $grid->addColumn('nr_total_price_rmb', 'Total RMB', '100px', $f->retTypeNum(), array('precision' => '4', 'readonly' => true));
        $grid->addColumn('nr_total_price_rmb_with_tax', 'Total RMB TAX', '150px', $f->retTypeNum(), array('precision' => '4', 'readonly' => true));


        $grid->addColumn('nr_price_default_currency', 'Price RMB', '100px', $f->retTypeNum(), array('precision' => '4', 'readonly' => true));
        $grid->addColumn('nr_price_with_tax_default_currency', 'Price Tax RMB', '100px', $f->retTypeNum(), array('precision' => '4', 'readonly' => true));

        $grid->addColumn('ds_payment_term', 'Payment Term', '100px', $f->retTypeStringAny(), false);

        $grid->addColumn('ds_remarks', 'Remarks', '120px', $f->retTypeStringAny(), false);
        $grid->addColumn('dt_update', 'Last Update', '120px', $f->retTypeDate(), false);

        $grid->setGridVar('vGridDetails');
        $grid->setGridName('tempxx');
        $grid->showToolbar(false);
        $grid->showFooter(false);
        $vdetails = $grid->retGridVar();


        $trans = array();

        $trans = $this->cdbhelper->retTranslationDifKeys($trans);
        $send = array('SupModelGrid' => $SupModelGrid, 'cd_rfq' => $cd_rfq, 'det' => $vdetails);

        $send['canChangeCost'] = $rights['canChangeCost'] ? 1 : 0;

        $this->load->view("rfq/rfq_pr_group_form_view", $trans + $send);
    }

    public function makeExcel($cd_rfq, $save = false) {

        $totalColumnsDetail = 12;
        $headerbackcolor = 'C8C8C8';
        $detailbackcolor = 'F0F0F0';

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

        // retrieve the information of the RFQ table, inside a array
        $header = $this->rfqmodel->retRetrieveArray(' WHERE "RFQ".cd_rfq        = ' . $cd_rfq);
        // retrieve the information of the RFQ_ITEM table, inside a array
        $group = $this->mainmodel->retRetrieveGridArray(' WHERE "RFQ_PR_GROUP".cd_rfq = ' . $cd_rfq, ' ORDER BY ds_supplier, ds_department_cost_center');


        // TO KNOW WHAT INFORMATION YOU HAVE YOU CAN CHECK THE models 


        $xls->newSpreadSheet('PR');
        $xls->selectActiveSheet('PR');
        //$xls->setFontDefault('Arial Unicode MS', 10);
        $xls->setPaperSize('A4');
        $xls->setPaperOrientation('L');
        $xls->setFitToWidth(true);
        $xls->setFitToHeight(false);
        $xls->setShowGridLines(false);

        $xls->selectArea(1, 1, 9, $totalColumnsDetail);
        $xls->setFontBold(true);

        $xls->selectArea(8, 1, 9, $totalColumnsDetail);
        $xls->setAlignVTop();

        $xls->wrapText('A');
        $xls->wrapText('B');
        $xls->wrapText('C');
        $xls->wrapText('D');
        $xls->wrapText('G');
        $xls->wrapText('L');
        $xls->wrapText('M');
        $xls->wrapText('N');
        $xls->wrapText('O');
        $xls->wrapText('P');

        $xls->setRowHeight(2, 30);
        $xls->setRowHeight(3, 30);
        $xls->setRowHeight(4, 30);
        $xls->setRowHeight(5, 30);

        //to access one field inside a array, keep in mind it is always two dimensions. first is the row index (starting in 0) and then the field name. For example, for the header:

        $xls->setItemString(1, 1, 'PR');
        $xls->selectArea(1, 1);
        $xls->setFontSize(20);
        $xls->setRowHeight(1, 24);
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

        $xls->setColumnWidth(1, 14);
        $xls->setColumnWidth(2, 14);
        //$xls->setColumnWidthAuto(3);
        $xls->setColumnWidth(3, 50);
        $xls->setColumnWidth(4, 30);
        $xls->setColumnWidthAuto(5);
        $xls->setColumnWidthAuto(6);
        $xls->setColumnWidth(7, 30);
        $xls->setColumnWidth(8, 20);
        $xls->setColumnWidth(9, 18);
        $xls->setColumnWidth(10, 18);
        $xls->setColumnWidth(11, 15);
        $xls->setColumnWidth(12, 15);
        $xls->setColumnWidth(13, 15);
        $xls->setColumnWidth(14, 15);
        $xls->setColumnWidth(15, 15);
        $xls->setColumnWidth(16, 15);
        $xls->setColumnWidth(17, 15);


        $xls->selectArea(2, 1, 5, 6);
        $xls->setBorderThin();


        $xls->setItemString(2, 1, 'Request Dept');
        $xls->setItemString(2, 3, 'Mil Reliability Lab');
        $xls->setItemString(2, 4, 'Request Date');
        $xls->setItemDate(2, 6, $header[0]['dt_request']);
        $xls->setItemString(3, 1, 'Applicant');
        $xls->setItemString(3, 3, $appl[0]['ds_human_resource_full']);
        $xls->setItemString(3, 4, 'Urgent or Not');
        $xls->setItemString(3, 6, $header[0]['fl_is_urgent'] = '1' ? 'Yes' : 'No');
        $xls->setItemString(4, 1, 'Phone');
        $xls->setItemString(4, 3, $appl[0]['ds_phone']);
        $xls->setItemString(4, 4, 'Request Complete Date');
        $xls->setItemDate(4, 6, $header[0]['dt_requested_complete']);
        $xls->setItemString(5, 1, 'Email Address');
        $xls->setItemString(5, 3, $appl[0]['ds_e_mail']);
        $xls->setItemString(5, 4, 'Buyer');
        $xls->setItemString(5, 6, $header[0]['ds_human_resource_purchase']);
        $xls->setItemString(7, 1, '具體要求:');
        $xls->selectArea(7, 1);
        $xls->setFontBold(true);

        $xls->setItemString(8, 1, "Department");
        $xls->setItemString(8, 2, "Currency");
        $xls->setItemString(8, 3, "Supplier");

        $xls->setItemString(8, 4, "Project (If Applicable)");
        $xls->setItemString(8, 5, "Model (If Applicable)");
        $xls->setItemString(8, 6, "Qty");
        $xls->setItemString(8, 7, "Total Price");
        $xls->setItemString(8, 8, "RMB Total Price");
        $xls->setItemString(8, 9, "PR #");

        $xls->selectArea(8, 1, 8, $totalColumnsDetail);
        $xls->setBorderMedium();
        $xls->setBackgroundColor($headerbackcolor);

        $xls->selectArea(9, 1, 9, $totalColumnsDetail);
        $xls->setBorderThin();
//        $xls->setBackgroundColor($detailbackcolor);

        $xls->setItemString(9, 1, "Line");
        $xls->setItemString(9, 2, "C/N & F/N");
        $xls->setItemString(9, 3, "Goods Name");
        $xls->setItemString(9, 4, "Technical Parameter/Size/Material");
        $xls->setItemString(9, 5, "R/N/F/I/S/C");
        $xls->setItemString(9, 6, "Brand");
        $xls->setItemString(9, 7, "Purchase Reason / Repair & Improvement Issue");

        $xls->setItemString(9, 8, "Unit");
        $xls->setItemString(9, 9, "Qty to Buy");

        $xls->setItemString(9, 10, "Unit Price");
        $xls->setItemString(9, 11, "Total Price");

        $xls->setItemString(9, 12, "RMB\nTotal Price");


        $xls->setRepeatingHeader(1, 9);
        $xls->selectArea(8, 1, 9, $totalColumnsDetail);
        $xls->setAlignHCenter();


        // loop to run inside the array. The $key is the first dimension, the row index.
        // the $valueItems is already the row, so don't need to refernece the row number to access the data:
        $curentRow = 10;
        $lastLine = $curentRow;


        foreach ($group as $key1 => $value) {

            $curentRow++;
            $xls->setItemString($curentRow, 1, $value['ds_department_cost_center']);
            $xls->setItemString($curentRow, 2, $value["ds_currency"]);
            $xls->setItemString($curentRow, 3, $value["ds_supplier"]);

            $xls->setItemString($curentRow, 4, $value["ds_project_number"]);
            $xls->setItemString($curentRow, 5, $value["ds_project_model_number"], true);
            $xls->setItemFloat($curentRow, 6, $value["nr_total_qty"], 2);
            $xls->setItemFloat($curentRow, 7, $value["nr_total_price"], 4);
            $xls->setItemFloat($curentRow, 8, $value["nr_total_price_rmb_with_tax"], 4);
            $xls->setItemString($curentRow, 9, $value["ds_pr_number"], 2);

            $xls->selectArea($curentRow, 1, $curentRow, $totalColumnsDetail);
            $xls->setBorderThin();
//            $xls->setBorderOuterThick();

            $xls->setFontBold(true);
            $xls->setBackgroundColor($headerbackcolor);


            $items = json_decode($value['json_quo'], true);
            $firstGroupRow = $curentRow;


            foreach ($items as $key => $valueItems) {

                $curentRow++;

                $xls->setItemString($curentRow, 1, $key + 1);
                $xls->setItemString($curentRow, 2, $design_desc = $valueItems['ds_equipment_design_code']);
                $desc = $valueItems['ds_equipment_design_description'];
                $descaddpn = $valueItems['ds_equipment_design_desc_complement'];

                if ($descaddpn != '') {
                    $desc = "$descaddpn";
                }


                $xls->setItemString($curentRow, 3, $desc);
                $xls->setItemString($curentRow, 4, $valueItems['ds_remarks']);
                $xls->setItemString($curentRow, 5, $valueItems['ds_rfq_request_type']);
                $xls->setItemString($curentRow, 6, $valueItems['ds_brand']);
                $xls->setItemString($curentRow, 7, $valueItems['ds_reason_buy']);

                $xls->setItemString($curentRow, 8, $valueItems['ds_unit_measure']);
                $xls->setItemFloat($curentRow, 9, $valueItems['nr_qtty_to_charge'], 0);
                $xls->setItemFloat($curentRow, 10, $valueItems['nr_price'], 4);
                $xls->setItemFloat($curentRow, 11, $valueItems['nr_total_price'], 4);

                $xls->setItemFloat($curentRow, 12, $valueItems['nr_total_price_rmb_with_tax'], 4);

                $xls->selectArea($curentRow, 1, $curentRow, $totalColumnsDetail);
//                $xls->setBackgroundColor($detailbackcolor);

                $xls->setRowHeightAuto($curentRow);
                $xls->setBorderThin();

                $curentRow = $curentRow + 1;
            }


            $xls->selectArea($firstGroupRow, 1, $curentRow - 1, $totalColumnsDetail);
//            $xls->setBorderOuterThick();
        }


        $xls->selectArea(2, 1, $curentRow, $totalColumnsDetail);
        $xls->setFontSize(12);

        $xls->setColumnWidthAuto(5);
        $xls->setColumnWidthAuto(11);


        $filename = "RFQ-" . $cd_rfq . '-PR-' . $header[0]['ds_request_date'] . '-' . $header[0]['ds_human_resource_applicant'];
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

    public function createFilesAttached($cd_rfq) {

        $excel = $this->makeExcel($cd_rfq, true);

        return $this->rfqmodel->createFilesAttached($cd_rfq, "PR-RFQ-$cd_rfq", array($excel), true);
    }

    public function openPOScreen() {
        $cduser = $this->session->userdata('cd_human_resource');
        $fl_rfq_see_price = $this->getCdbhelper()->getUserPermission('fl_rfq_see_price');



        parent::checkMenuPermission('openPOScreen');

//        if ($fl_rfq_see_price == 'N') {
//            die('no rights');
//        }

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


        $fm->addFilterNumber('Purchase Request Code', 'filter_11', '"RFQ".cd_rfq');
        $fm->addPickListFilter('Type', 'filter_req_type', 'rfq/rfq_request_type', '"RFQ_ITEM".cd_rfq_request_type');
        //$fm->addFilterDate('Request Date', 'filter_req', '"RFQ".dt_request');
        $sql = '( select a.dt_define FROM rfq."RFQ_APPROVAL_STEPS" a, (select max(y.cd_rfq_approval_steps) as cd_rfq_approval_steps FROM rfq."RFQ_APPROVAL_STEPS" y, public."APPROVAL_STEPS_CONFIG" c
                  WHERE c.ds_internal_code = \'toPR\'
                    AND y.cd_rfq           = "RFQ".cd_rfq
                    AND y.cd_approval_steps_config = c.cd_approval_steps_config ) as l
           WHERE a.cd_rfq_approval_steps = l.cd_rfq_approval_steps
             AND a.cd_approval_status    = 1 )';

        $fm->addFilterDate('Issue POR/PR', 'filter_issue_pr', $sql);

        $fm->addSimpleFilterUpper('POR/PR Sequence', 'por_seq_filter', '"RFQ_PR_GROUP".ds_pr_number');
        $fm->addSimpleFilterUpper('POR/PR Reference', 'por_seq_ref', '"RFQ_PR_GROUP".ds_por_reference');
        $fm->addSimpleFilterUpper('PO#', 'po_nmumer', '"RFQ_PR_GROUP".ds_po_number');

        $fm->addFilter('filter_1', 'Applicant', array('controller' => 'human_resource_controller', 'fieldname' => '"RFQ".cd_human_resource_applicant', 'multi' => true));
        $fm->addPickListFilter('Buyer', 'filter_5', 'human_resource_controller', '"RFQ".cd_human_resource_purchase');
        $fm->addSimpleFilterUpper('Equipment Code/Description', 'filter_equp', '("EQUIPMENT_DESIGN".ds_equipment_description_full || ( CASE WHEN "RFQ_ITEM".ds_equipment_design_code_complement IS NOT NULL THEN \'-\' || "RFQ_ITEM".ds_equipment_design_code_complement ELSE \'\' END) || \' \' || COALESCE( "RFQ_ITEM".ds_equipment_design_desc_complement,"EQUIPMENT_DESIGN".ds_equipment_design,ds_equipment_design_desc_complement) )');
        $fm->addSimpleFilterUpper('RFQ Comment', 'filter_comment', '"RFQ".ds_comments');
        $fm->addPickListFilter('Supplier', 'filter_10', 'rfq/supplier', '"RFQ_ITEM_SUPPLIER".cd_supplier');

        $fm->addPickListFilter('Department', 'filter_dep', 'rfq/department_cost_center', '"RFQ_PR_GROUP".cd_department_cost_center');
        $fm->addPickListFilter('Account Code', 'filter_account', 'rfq/department_account_code', '"RFQ_PR_GROUP".cd_department_account_code');
        $fm->addPickListFilter('Expense Type', 'filter_expense', 'rfq/expense_type', '"RFQ_PR_GROUP".cd_expense_type');


        //$fm->addPickListFilterExists("Supplier", "rfq/supplier", "filter_10", "RFQ_ITEM", "cd_rfq_item", "RFQ_ITEM_SUPPLIER", "cd_supplier", "cd_rfq_item", false);

        $fm->addSimpleFilterUpper('RFQ #', 'filter_8', '"RFQ".ds_rfq_number');
        $fm->addSimpleFilterUpper('W/F #', 'filter_9', '"RFQ".ds_wf_number');

        $fixed = array(
            array('desc' => 'MISSING PO#',
                'sql' => ' AND "RFQ_PR_GROUP".ds_po_number IS NULL ',
                'idDesc' => 1)
        );

        $fm->addFilter('filter_status', 'Status', array('plFixedSelect' => $fixed));


        $fm->addFilterYesNo("Active", "dt_deactivated", '"RFQ_PR_GROUP".dt_deactivated', "Y");

        $this->setGridParser();
        $grid->setSingleBarControl(true);
        if ($this->getCdbhelper()->getUserPermission('fl_edit_delivery_data') == 'Y') {
            $grid->addCRUDToolbar(true, false, true, false, true);
        } else {
            $grid->addCRUDToolbar(true, false, false, false, true);
        }
        $grid->addBreakToolbar();
        if ($fl_rfq_see_price == 'Y') {
            $grid->addUserBtnToolbar('openprinfo', 'PR Information', 'fa fa-file-text-o');
            $grid->addUserBtnToolbar('downloaddata', 'Download PR Data', 'fa fa-download');
        }
        $grid->setFilterPresetId('prtionsq');


        $grid->setToolbarSearch(true);
        $grid->setCRUDController("rfq/rfq_pr_group");
        $grid->setRowHeight('30');
        $grid->setDocRepId(1);
        $grid->setExcelDetailed(true);
        $grid->setExcelDetailedSendResultSet(true);



        //$grid->addColumnKey();
        $grid->addColumn('cd_rfq', 'Code', '50px', $f->retTypeStringAny(), false);
        $grid->addColumn('dt_pr_released', 'Issue POR/PR', '80px', $f->retTypeDate(), false);
        $grid->addColumn('ds_human_resource_applicant', 'Applicant', '80px', $f->retTypeStringAny(), false);
        $grid->addHiddenColumn('cd_rfq_item', 'Image', '50px', $f->retTypeFirstPicture(), false);
        $grid->addColumn('ds_equipment_design_code', 'Equipment Code', '80px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_equipment_design_description', 'Equipment Description', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_equipment_design_desc_complement', 'Description', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('nr_qtty_to_charge', 'Qty to Buy', '80px', $f->retTypeNum(), array('precision' => '0', 'readonly' => true));
        $grid->addColumn('nr_total_received', 'Total Received', '60px', $f->retTypeNum(), array('precision' => '1', 'readonly' => true));
        $grid->addColumn('nr_balance', 'Balance', '60px', $f->retTypeNum(), array('precision' => '1', 'readonly' => true));
        $grid->addColumn('ds_unit_measure', 'Unit', '50px', $f->retTypeStringAny(), false);
        
        if ($fl_rfq_see_price == 'Y') {
            $grid->addColumn('nr_price', 'Price', '100px', $f->retTypeNum(), array('precision' => '4', 'readonly' => true));
            $grid->addColumn('nr_price_with_tax', 'Price with Tax', '100px', $f->retTypeNum(), array('precision' => '4', 'readonly' => true));
            $grid->addColumn('ds_currency', 'Currency', '70px', $f->retTypeStringAny(), false);
            $grid->addColumn('ds_supplier', 'Supplier', '150px', $f->retTypeStringAny(), false);
        }

        $grid->addColumn('ds_department_cost_center', 'Department', '50px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_department_account_code', 'Account Code', '100px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_expense_type', 'Expense Type', '100px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_project_number', 'Project', '60px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_project_model_number', 'Model', '100px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_rfq_request_type', 'Type', '50px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_pr_number', 'POR/PR Sequence', '100px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_por_reference', 'POR/PR Reference', '100px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_po_number', 'PO#', '80px', $f->retTypeStringAny(), false);
        if ($fl_rfq_see_price == 'Y') {
            $grid->addColumn('nr_price_default_currency', 'Price RMB', '100px', $f->retTypeNum(), array('precision' => '4', 'readonly' => true));
            $grid->addColumn('nr_price_with_tax_default_currency', 'Price with Tax RMB', '100px', $f->retTypeNum(), array('precision' => '4', 'readonly' => true));

            $grid->addColumn('nr_total_price', 'Total Price', '100px', $f->retTypeNum(), array('precision' => '4', 'readonly' => false));
            $grid->addColumn('nr_total_price_with_tax', 'Total Price With Tax', '100px', $f->retTypeNum(), array('precision' => '4', 'readonly' => true));
            $grid->addColumn('nr_total_price_rmb', 'Total Price Rmb', '100px', $f->retTypeNum(), array('precision' => '4', 'readonly' => true));
            $grid->addColumn('nr_total_price_rmb_with_tax', 'Total Price Rmb With Tax', '100px', $f->retTypeNum(), array('precision' => '4', 'readonly' => true));
        }
        $grid->addColumn('nr_moq', 'MOQ', '80px', $f->retTypeNum(), array('precision' => '0', 'readonly' => true));
        $grid->addColumn('nr_leadtime', 'Leadtime', '80px', $f->retTypeInteger(), false);
        $grid->addColumn('ds_remarks', 'Remarks', '100px', $f->retTypeStringAny(), false);


        $grid->setGridDivName('PRGridDiv');


        $filters = $fm->retFiltersWithGroup();
        $javascript = $grid->retGrid();


        $trans = array();
        $trans = $this->cdbhelper->retTranslationDifKeys($trans);

        $send = array("javascript" => $javascript,
            "filters" => $filters,
            'tab' => $ctabs->retTabs(),
            "filters_java" => $fm->retJavascript()) + $trans;

        //------------------------------------------------------------------------------------------------------------------------------------

        $grid->resetGrid();
        $grid->setToolbarPrefix('InputOutput');
        $grid->setSingleBarControl(true);

        $grid->setToolbarSearch(true);
        $grid->setCRUDController("rfq/rfq_pr_incoming_outcoming");

        $grid->addToolbarTitle('Input Output');
        $grid->addColumnKey();


        if ($this->getCdbhelper()->getUserPermission('fl_edit_delivery_data') == 'Y') {
            $grid->addCRUDToolbar(false, true, false, true, false);
            $grid->addColumn('ds_rfq_pr_incoming_outcoming_type', 'Type', '150px', $f->retTypePickList(), array('model' => 'rfq/rfq_pr_incoming_outcoming_type_model', 'codeField' => 'cd_rfq_pr_incoming_outcoming_type'));
            $grid->addColumn('ds_human_resource_receiver', 'Receiver', '150px', $f->retTypePickList(), array('model' => 'human_resource_model', 'codeField' => 'cd_human_resource_receiver'));
            $grid->addColumn('dt_action', 'Action Time', '80px', $f->retTypeDate(), true);
            $grid->addColumn('nr_qty', 'Qty', '150px', $f->retTypeNum(), array('precision' => '1', 'readonly' => false));
            $grid->addColumn('ds_comments', 'Comments', '150px', $f->retTypeStringAny(), array('limit' => ''));
        } else {
            $grid->addCRUDToolbar(false, false, false, false, false);
            $grid->addColumn('ds_rfq_pr_incoming_outcoming_type', 'Type', '150px', $f->retTypeStringAny(), false);
            $grid->addColumn('ds_human_resource_receiver', 'Receiver', '150px', $f->retTypeStringAny(), false);
            $grid->addColumn('dt_action', 'Action Time', '80px', $f->retTypeDate(), false);
            $grid->addColumn('nr_qty', 'Qty', '150px', $f->retTypeNum(), array('precision' => '1', 'readonly' => true));
            $grid->addColumn('ds_comments', 'Comments', '150px', $f->retTypeStringAny(), false);
        }



//        $grid->addRecords($this->inputoutputmodel->retRetrieveGridJson(' WHERE "RFQ_PR_GROUP_DISTRIBUTION".cd_rfq_pr_group = ' . $cd_rfq_pr_group, ' ORDER BY ds_supplier '));
        $grid->setGridDivName('inputOutputGridDiv');
        $grid->setGridName('inputOutputGrid');
        $grid->setGridVar('vBenefit');


//        $filters = $fm->retFiltersWithGroup();
        $javascript = $javascript . $grid->retGrid();


        $trans = array();
        $trans = $this->cdbhelper->retTranslationDifKeys($trans);


        $send = array("javascript" => $javascript,
            "filters" => $filters,
            'tab' => $ctabs->retTabs(),
            "filters_java" => $fm->retJavascript()) + $trans;
        //------------------------------------------------------------------------------------------------------------------------------------
        $this->load->view("rfq/rfq_po_epor_details_view", $send + array('cd_human_resource_receiver' => $this->session->userdata('cd_human_resource'),
            'ds_human_resource_receiver' => $this->session->userdata('ds_human_resource_full'), 'dt_action' => date("m/d/Y")));
    }

    // function that retrieves the information. here should have the columns that will receive the child tables.检索信息的函数。这里应该有接收子表的列。
//    function retrieveGridJson($retrOpt = array())
//    {
//
//        if (!$this->logincontrol->isProperLogged(false)) {
//            echo('{"logged": "N", "resultset": [] }');
//            return;
//        }
//
//
//        $where = $this->getWhereToFilter();
//        $jsonMapping = $this->getJsonMappingToFilter();
//
//        if (isset($retrOpt['whereToAdd'])) {
//            $where = $where . $retrOpt['whereToAdd'];
//        }
//
//        $data = $this->mainmodel->retRetrieveGridArray($where, '', $retrOpt);
//
//        foreach ($data as $key => $row) {
//
//            $data[$key]['inputOutput'] = $this->inputoutputmodel->retRetrieveGridArray(" WHERE cd_rfq_pr_group = " . $row['recid']);
//
//        }
//
//        echo('{ "logged": "Y", "resultset": ' . json_encode($data) . ' }');
//
//    }

    public function retrPRData() {


        if (!$this->logincontrol->isProperLogged(false)) {
            echo('{"logged": "N", "resultset": [] }');
            return;
        }

        $retrOpt = $this->rfqprdist->retrOptionsFull;

        $where = $this->getWhereToFilter();


        $jsonMapping = $this->getJsonMappingToFilter();

        if (isset($retrOpt['whereToAdd'])) {
            $where = $where . $retrOpt['whereToAdd'];
        }

        echo('{ "logged": "Y", "resultset": ' . $this->rfqprdist->retRetrieveGridJson($where, '', $jsonMapping, $retrOpt) . ' }');
    }

    public function genXLSDetailed() {
        $class = get_class($this);

        $this->load->library('cexcel');

        if (isset($_POST['resultset'])) {
            $resultset = $_POST['resultset'];
        } else {
            $resultset = $this->retrieveGridArray();
        }

        $resultset = json_decode($resultset, true);
        $name = 'PRDATA';


        $columns = json_decode($_POST['col']);
        $titlecolumn = json_decode($_POST['title']);
        $group = json_decode($_POST['group']);
        $rowHeight = $_POST['rowHeight'];
        $this->cexcel->setDocRep($_POST['docrep']);

        $this->cexcel->newSpreadSheet();
        $this->cexcel->createExcelByGrid($name, $columns, $titlecolumn, $group, $resultset, $rowHeight);
        $time = time();


        $this->cexcel->saveAsOutput("$name$time.xlsx");
        $this->cexcel->cleanMemory();
    }

    public function updateDataJson() {

        $upd_array = json_decode($_POST['upd']);
        $retResultset = 'N';

        if (isset($_POST['retResultSet'])) {
            $retResultset = $_POST['retResultSet'];
        }
        $jsonMapping = '';
        if (isset($_POST['jsonMapping'])) {
            $jsonMapping = $_POST['jsonMapping'];
        }


        $this->cdbhelper->trans_begin();


        $error = $this->mainmodel->updateGridData($upd_array);
        if ($error != 'OK') {
            $msg = '{"status":' . json_encode($error) . ', "rs":{}}';
            $this->cdbhelper->trans_end();
            echo $msg;
            return;
        }

//        var_dump($upd_array);
        $error = $this->inputoutputmodel->updateGridDataFromField('inputoutput', $upd_array);
        if ($error != 'OK') {
            $msg = '{"status":' . json_encode($error) . ', "rs":{}}';
            $this->cdbhelper->trans_end();
            echo $msg;
            return;
        }


        $this->cdbhelper->trans_commit();
        $this->cdbhelper->trans_end();

        $msg = '{"status":' . json_encode($error);


        $retResult = '{}';

        if ($retResultset == 'Y' && $error == 'OK') {
            $neg = $this->mainmodel->getNewRecIdsNegative();
            $x = implode(',', $neg);

            $where = ' where ' . $this->mainmodel->pk_field . ' in (';
            foreach ($upd_array as $value) {
                $where = $where . $value->recid . ',';
            }
            if ($x != '') {
                $where = $where . $x . ', ';
            }
            $where = $where . '-1 )';


            //$retResult = $this->mainmodel->retRetrieveGridJson($where, '', $jsonMapping);
            //$retrOpt = $this->rfqprdist->retrOptionsFull;
            //$retResult = $this->rfqprdist->retRetrieveGridJson($where, '', $jsonMapping, $retrOpt);

            $msg = $msg . ', "rs": ' . $retResult;

            if (count($neg) > 0) {
                $msg = $msg . ', "negRS": ' . json_encode($neg);
            }
        }

        $msg = $msg . '}';

        //

        echo $msg;
    }

    public function updateDataJsonIO() {

        $upd_array = json_decode($_POST['upd']);
        $retResultset = 'N';

        if (isset($_POST['retResultSet'])) {
            $retResultset = $_POST['retResultSet'];
        }
        $jsonMapping = '';
        if (isset($_POST['jsonMapping'])) {
            $jsonMapping = $_POST['jsonMapping'];
        }


        $this->cdbhelper->trans_begin();


        $error = $this->mainmodel->updateGridData($upd_array);
        if ($error != 'OK') {
            $msg = '{"status":' . json_encode($error) . ', "rs":{}}';
            $this->cdbhelper->trans_end();
            echo $msg;
            return;
        }

//        var_dump($upd_array);
        $error = $this->inputoutputmodel->updateGridDataFromField('inputoutput', $upd_array);
        if ($error != 'OK') {
            $msg = '{"status":' . json_encode($error) . ', "rs":{}}';
            $this->cdbhelper->trans_end();
            echo $msg;
            return;
        }


        $this->cdbhelper->trans_commit();
        $this->cdbhelper->trans_end();

        $msg = '{"status":' . json_encode($error);


        $retResult = '{}';

        if ($retResultset == 'Y' && $error == 'OK') {
            $neg = $this->mainmodel->getNewRecIdsNegative();
            $x = implode(',', $neg);

            $where = ' where ' . $this->rfqprdist->pk_field . ' in (';
            foreach ($upd_array as $value) {
                $where = $where . $value->recid . ',';
            }
            if ($x != '') {
                $where = $where . $x . ', ';
            }
            $where = $where . '-1 )';


            //$retResult = $this->mainmodel->retRetrieveGridJson($where, '', $jsonMapping);
            $retrOpt = $this->rfqprdist->retrOptionsFull;
            $retResult = $this->rfqprdist->retRetrieveGridJson($where, '', $jsonMapping, $retrOpt);

            $msg = $msg . ', "rs": ' . $retResult;

            if (count($neg) > 0) {
                $msg = $msg . ', "negRS": ' . json_encode($neg);
            }
        }

        $msg = $msg . '}';

        //

        echo $msg;
    }

}
