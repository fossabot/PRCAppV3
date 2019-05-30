<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class tests extends controllerBasicExtend
{

    var $arrayIns;
    var $fields;

    function __construct()
    {
        parent::__construct();
        $this->load->model("tr/tests_model", "mainmodel", TRUE);
        // Add the model for the document repository; Load models is always at the constructor level.
        $this->load->model('docrep/document_repository_model', 'picmodel', TRUE);
        $this->load->model('rfq/rfq_cost_center_model', 'costmodel', TRUE);

    }

    public function index()
    {

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

        $fm->addSimpleFilterUpper('Tests', 'filter_1', '"TESTS".ds_tests');
        $fm->addPickListFilter('Unit', 'filter_4', 'tr/test_unit', '"TESTS".cd_test_unit');
        $fm->addFilterYesNo("Active", "dt_deactivated", "", "Y");


        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("tr/tests");

        $grid->addColumnKey();
        $grid->addColumn('ds_tests', 'Tests', '100%', $f->retTypeStringUpper(), array('limit' => '64'));
        $grid->addColumn('ds_test_unit', 'Unit', '150px', $f->retTypePickList(), array('model' => 'tr/test_unit_model', 'codeField' => 'cd_test_unit'));
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

    /**
     * @param $cd_rfq
     */
    public function makeExcel($cd_rfq)
    {

        $this->load->library('cexcel');
        $xls = $this->cexcel;
        if (1 == 2) {
            $xls = new cexcel();
        }


        // loading the models that will relate to a table
        $this->load->model("rfq/rfq_model", "rfqmodel", TRUE);
        $this->load->model("rfq/rfq_item_model", "rfqitemmmodel", TRUE);

        // retrieve the information of the RFQ table, inside a array
        $header = $this->rfqmodel->retRetrieveArray(' WHERE "RFQ".cd_rfq        = ' . $cd_rfq);
        // retrieve the information of the RFQ_ITEM table, inside a array
        $items = $this->rfqitemmmodel->retRetrieveArray(' WHERE "RFQ_ITEM".cd_rfq = ' . $cd_rfq);

        // TO KNOW WHAT INFORMATION YOU HAVE YOU CAN CHECK THE models 

        $xls->newSpreadSheet('RFQ');
        $xls->selectActiveSheet('RFQ');


        //to access one field inside a array, keep in mind it is always two dimensions. first is the row index (starting in 0) and then the field name. For example, for the header:

        $xls->setItemString(1, 1, 'MIL LAB RFQ 詢 價 申 請 表');
        $xls->selectArea(1, 1, 1, 10);
        $xls->mergeCells();
        $xls->setFontBold(true);
        $xls->setAlignHCenter();
        $xls->setFontSize(18);
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

        $xls->setColumnWidthAuto(2);
        $xls->setColumnWidthAuto(3);
        $xls->setColumnWidth(4, 30);
        $xls->setColumnWidthAuto(5);
        $xls->setColumnWidthAuto(6);
        $xls->setColumnWidth(7, 30);
        $xls->setColumnWidth(8, 24);
        $xls->setColumnWidth(9, 10);
        $xls->setColumnWidth(10, 15);
        $xls->setColumnWidth(11, 6);
        $xls->setColumnWidth(12, 12);
        $xls->setColumnWidth(13, 30);
        $xls->selectArea(2, 1, 5, 6);
        $xls->setBorderThin();
//        $phone = explode(' ', $header[0]['ds_phone']);
//        $phoneStr = $phone[count($phone) - 1];


        $xls->setItemString(2, 1, '申請部門 Request Dept');
        $xls->setItemString(2, 3, 'Mil Reliability Lab');
        $xls->setItemString(2, 4, '申請日期 Request Date');
        $xls->setItemDate(2, 6, $header[0]['dt_request']);
        $xls->setItemString(3, 1, '申請人 Applicant');
        $xls->setItemString(3, 3, $header[0]['ds_human_resource_applicant']);
        $xls->setItemString(3, 4, '是否緊急情況詢價 Urgent or Not');
        $xls->setItemString(3, 6, $header[0]['fl_is_urgent'] = '1' ? 'Yes' : 'No');
        $xls->setItemString(4, 1, '聯絡電話 Phone');
        $xls->setItemString(4, 3, $header[0]['ds_phone']);
        $xls->setItemString(4, 4, '要求完成報價日期 Request Complete Date');
        $xls->setItemDate(4, 6, $header[0]['dt_requested_complete']);
        $xls->setItemString(5, 1, '郵箱地址 Email Address');
        $xls->setItemString(5, 3, $header[0]['ds_e_mail']);
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
        $xls->setItemString(8, 7, "购买理由/维修/改造事项\nPurchase Reason/Repair & Improvement Issue");
        $xls->setItemString(8, 8, "數碼圖片/圖紙/参考网址\nPicture/Drawing/Website");
        $xls->setItemString(8, 9, "本次需求量\nRequire Qty");
        $xls->setItemString(8, 10, "估計年內需求量 \nEstimated Annual Volume");
        $xls->setItemString(8, 11, "单位\nUnit");
        $xls->setItemString(8, 12, "截止日期\nDeadline");
        $xls->setItemString(8, 13, "備注\nRemark");


        // loop to run inside the array. The $key is the first dimension, the row index.
        // the $valueItems is already the row, so don't need to refernece the row number to access the data:
        $curentRow = 9;

        foreach ($items as $key => $valueItems) {

            $costfdata = $this->costmodel->retRetrieveArray(' WHERE cd_rfq_item = ' . $valueItems['recid'], ' ORDER BY ds_department_cost_center');


            // accessing directly from the row information....
//            $design_desc=$valueItems['ds_equipment_design_desc_complement'];
//            $design_code= $valueItems['ds_equipment_design_code'];
//             as $key is a number, according to the rows quanitty, I'm using as row in the system.. but doesn't need. The logic is yours

            $xls->setItemString($curentRow, 1, $key + 1);
            $xls->setItemString($curentRow, 2, $design_desc = $valueItems['ds_equipment_design_code']);
//            $xls->setItemString($curentRow, 3, $valueItems['ds_equipment_design_desc_complement ']);
            $xls->setItemString($curentRow, 3, $valueItems['ds_equipment_design_desc_complement']);
            $xls->setItemString($curentRow, 4, $valueItems['ds_remarks']);
            $xls->setItemString($curentRow, 5, $valueItems['ds_rfq_request_type']);
            $xls->setItemString($curentRow, 6, $valueItems['ds_brand']);
            $xls->setItemString($curentRow, 7, $valueItems['ds_reason_buy']);

            $filename = $this->picmodel->getFirstPicture(1, $valueItems['recid']);
            If ($filename) {
                $xls->addPicture($curentRow, 8, $filename, 100);
            }
            $xls->setItemString($curentRow, 9, $valueItems['nr_qtty_quote']);
            $xls->setItemString($curentRow, 10, $valueItems['nr_estimated_annual']);
            $xls->setItemString($curentRow, 11, $valueItems['ds_unit_measure']);
            $xls->setItemDate($curentRow, 12, $valueItems['dt_deadline']);
            $costDept = "";

            foreach ($costfdata as $key => $link) {
                $costDept = $costDept . $link['ds_department_cost_center'] . " ";
            }
            $projectNumber = "";
            foreach ($costfdata as $key => $link) {
                $projectNumber = $projectNumber . $link['ds_project_number'] . " ";
            }

            $needsample = ($valueItems['fl_need_sample'] = 0) ? "Yes" : "No";
            $supplier_visit = ($valueItems['dt_supplier_visit_deadline'] = '') ? 'N/A' : $valueItems['dt_supplier_visit_deadline'];
            $xls->setItemString($curentRow, 13, "1.COST DPT: $costDept\n" . "2.Project No :$projectNumber\n" . "3.Project Description:\n" . "4.是否需要样品(Yes/No):$needsample.\n5.是否需要供应商来厂看样及看样时间:$supplier_visit");
            $curentRow = $curentRow + 1;

        }
        $xls->selectArea(8, 1, $curentRow, 13);
        $xls->setBorderThin();
        $xls->setFontBold(true);

        $xls->setItemString($curentRow, 1, '說明: 申請部門不可直接向供應商詢價及議價,任何詢價需求都經采購部跟進.
            申請部門如有推薦供應商,只需提供其供應商資料及聯絡方式給采購部.不可直接向供應商聯絡.');
        $xls->selectArea($curentRow, 1, $curentRow, 13);
        $xls->setFontBold(true);


        $xls->mergeCells();
        $xls->setItemString($curentRow + 1, 1, '1. 申請者在發出其詢價申請表時,需抄送給其部門負責人. 2. 申請時,如未指定其品牌,規格,型號.申請者需提供其詳細的資料(包括具體的技術參數,樣板或圖片等).采購部將據此要求進行詢價.');

        /*


// cabecalho
        $xls->selectArea(1, 4, 5, 4);
        $xls->setBackgroundColor('D3D3D3');
        $xls->setAlignHRight();
        $xls->setFontBold(true);
        

        $xls->selectArea(1, 6, 5, 6);
        $xls->setBackgroundColor('D3D3D3');
        $xls->setAlignHRight();
        $xls->setFontBold(true);


// column 2
        $xls->selectArea(1, 1, 1, 10);
        $xls->mergeCells();
        $xls->setItemString(3, 4, 'Label1:');
        $xls->setItemString(3, 5, 'Information');
         * /
         */

        /*

                $xls->setItemString(2, 4, $trans['spec'] . ':');
                $xls->setItemString(2, 5, $main['ds_shoe_specification'] . ' / ' . $main['nr_shoe_specification_build']);

                $xls->setItemString(3, 4, $trans['constr'] . ':');
                $xls->setItemString(3, 5, $main['ds_construction'] . ' / ' . $main['nr_construction_build']);


                $xls->setItemString(4, 4, $trans['type'] . ':');
                $xls->setItemString(4, 5, $main['ds_shoe_cs_type']);
        */
        /*        $xls->selectArea(1, 9, 1, 10);
                $xls->mergeCells();
                $xls->setBorderThin();
        */


        $xls->saveAsOutput("CS1.xlsx");
        $xls->cleanMemory();
    }

}
