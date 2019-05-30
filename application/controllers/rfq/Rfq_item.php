<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class rfq_item extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("rfq/rfq_item_model", "mainmodel", TRUE);
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

        $fm->addPickListFilter('Rfq', 'filter_1', 'rfq/rfq', '"RFQ_ITEM".cd_rfq');
        $fm->addPickListFilter('Equipment Design', 'filter_2', 'rfq/equipment_design', '"RFQ_ITEM".cd_equipment_design');
        $fm->addPickListFilter('Rfq Request Type', 'filter_3', 'rfq/rfq_request_type', '"RFQ_ITEM".cd_rfq_request_type');
        $fm->addSimpleFilterUpper('Reason Buy', 'filter_4', '"RFQ_ITEM".ds_reason_buy');
        $fm->addSimpleFilterUpper('Website', 'filter_9', '"RFQ_ITEM".ds_website');
        $fm->addSimpleFilterUpper('Remarks', 'filter_10', '"RFQ_ITEM".ds_remarks');
        $fm->addSimpleFilterUpper('Attached Image', 'filter_12', '"RFQ_ITEM".ds_attached_image');



        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("rfq/rfq_item");

        $grid->addColumnKey();

        $grid->addColumn('ds_rfq', 'Rfq', '150px', $f->retTypePickList(), array('model' => 'rfq/rfq_model', 'codeField' => 'cd_rfq'));
        $grid->addColumn('ds_equipment_design', 'Equipment Design', '150px', $f->retTypePickList(), array('model' => 'rfq/equipment_design_model', 'codeField' => 'cd_equipment_design'));
        $grid->addColumn('ds_rfq_request_type', 'Rfq Request Type', '150px', $f->retTypePickList(), array('model' => 'rfq/rfq_request_type_model', 'codeField' => 'cd_rfq_request_type'));
        $grid->addColumn('ds_reason_buy', 'Reason Buy', '150px', $f->retTypeStringAny(), array('limit' => ''));
        $grid->addColumn('nr_qtty_quote', 'Qty Quote', '150px', $f->retTypeNum(), array('precision' => '2', 'readonly' => false));
        $grid->addColumn('fl_buy', 'Buy', '150px', $f->retTypeCheckBox(), true);
        $grid->addColumn('cd_supplier_selected', 'Supplier Selected', '150px', $f->retTypeInteger(), true);
        $grid->addColumn('dt_deadline', 'Deadline', '80px', $f->retTypeDate(), true);
        $grid->addColumn('ds_website', 'Website', '150px', $f->retTypeStringAny(), array('limit' => ''));
        $grid->addColumn('ds_remarks', 'Remarks', '150px', $f->retTypeStringAny(), array('limit' => ''));
        $grid->addColumn('cd_rfq_item_quotation', 'Rfq Item Quotation', '150px', $f->retTypeInteger(), true);
        $grid->addColumn('ds_attached_image', 'Attached Image', '150px', $f->retTypeStringAny(), array('limit' => ''));


        $filters = $fm->retFiltersWithGroup();
        $javascript = $grid->retGrid();


        $trans = array();
        $trans = $this->cdbhelper->retTranslationDifKeys($trans);



        $send = array("javascript" => $javascript,
            "filters" => $filters,
            "filters_java" => $fm->retJavascript()) + $trans;


        $this->load->view("defaultView", $send);
    }

    public function autoSupplier($item, $qtty) {
        $item = $this->getCdbhelper()->normalizeDataToSQL('int', $item);
        $qtty = $this->getCdbhelper()->normalizeDataToSQL('num', $qtty);

        $sql = "select setrfqitemsupplierauto($item, $qtty) as ret";

        $ret = $this->getCdbhelper()->basicSQLArray($sql);

        $retResult = $this->mainmodel->retRetrieveGridJson(' WHERE "RFQ_ITEM".cd_rfq_item = ' . $item);

        $msg = '{"status": "OK", "data":' . json_encode($ret[0]['ret']) . ', "rs": ' . $retResult . ' }';
        echo($msg);
    }

}
