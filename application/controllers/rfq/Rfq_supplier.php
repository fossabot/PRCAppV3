<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class rfq_supplier extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("rfq/rfq_supplier_model", "mainmodel", TRUE);
        $this->load->model("rfq/rfq_item_supplier_model", "supitemmodel", TRUE);

        $this->load->model("rfq/rfq_item_model", "itemmodel", TRUE);
        $this->load->model("rfq/rfq_item_supplier_quotation_model", "quomodel", TRUE);
        $this->load->model("rfq/rfq_item_supplier_sample_request_model", "smpmodel", TRUE);
        $this->load->model("currency_model", "curmodel", TRUE);
        $this->load->model("rfq/payment_term_model", "paymodel");
        $this->load->model("rfq/rfq_model", "rfqmodel", TRUE);
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

        $fm->addPickListFilter('Rfq', 'filter_1', 'rfq/rfq', '"RFQ_SUPPLIER".cd_rfq');
        $fm->addPickListFilter('Supplier', 'filter_2', 'rfq/supplier', '"RFQ_SUPPLIER".cd_supplier');



        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("rfq/rfq_supplier");

        $grid->addColumnKey();

        $grid->addColumn('ds_rfq', 'Rfq', '150px', $f->retTypePickList(), array('model' => 'rfq/rfq_model', 'codeField' => 'cd_rfq'));
        $grid->addColumn('ds_supplier', 'Supplier', '150px', $f->retTypePickList(), array('model' => 'rfq/supplier_model', 'codeField' => 'cd_supplier'));
        $grid->addColumn('nr_tax', 'Tax', '150px', $f->retTypeNum(), array('precision' => '2', 'readonly' => false));


        $filters = $fm->retFiltersWithGroup();
        $javascript = $grid->retGrid();


        $trans = array();
        $trans = $this->cdbhelper->retTranslationDifKeys($trans);



        $send = array("javascript" => $javascript,
            "filters" => $filters,
            "filters_java" => $fm->retJavascript()) + $trans;


        $this->load->view("defaultView", $send);
    }

    public function openRfqSupplier($cd_rfq) {
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
            die ('You dont have rights here');
        }
        
        $cur = $this->getCdbhelper()->getSystemParameters('DEFAULT_CURRENCY_RFQ');
        $cur = $this->curmodel->retRetrieveGridArray(' WHERE cd_currency= ' . $cur);
        $defpay = $this->getCdbhelper()->getSystemParameters('DEFAULT_PAYMENT_TERM_RFQ');
        $defpay = $this->paymodel->retRetrieveGridArray(' WHERE cd_payment_term = ' . $defpay);

        // supplier info
        $grid->setSingleBarControl(true);
        $grid->setGridToolbarFunction("dsRFQSupObject.ToolbarGrid");
        $grid->addCRUDToolbar (false, $rights['canChangeCost'], $rights['canChangeCost'], $rights['canChangeCost'], false);
        $grid->addDocRepToolbar();
        

        $grid->addBreakToolbar();

        $grid->addUserBtnToolbar('close', 'Close', 'fa fa-close');

        $grid->addSpacerToolbar();


        $grid->setToolbarSearch(true);
        $grid->setCRUDController("rfq/rfq_supplier");

        $grid->addColumnKey();

        $grid->addColumn('ds_supplier', 'Supplier', '40%', $f->retTypePickList(), array('model' => 'rfq/supplier_model', 'codeField' => 'cd_supplier'));
        $grid->addColumn('nr_round', 'Last Round', '60px', $f->retTypeInteger(), false);
        $grid->addColumn('nr_tax', 'Tax', '60px', $f->retTypeNum(), array('precision' => '2', 'readonly' => false));

        $grid->setGridName('supplierGrid');
        $grid->setGridDivName('supplierGridDiv');
        $grid->addRecords($this->mainmodel->retRetrieveGridJson(' WHERE "RFQ_SUPPLIER".cd_rfq = ' . $cd_rfq, ' ORDER BY ds_supplier '));

        $SupModelGrid = $grid->retGrid();
        $grid->resetGrid();

        // quotation
        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->setInsertNegative(true);
        /*
          $grid->addUserBtnToolbar('insertround', 'Insert New Round', 'fa fa-cart-plus', 'Insert New Round');
          $grid->addUserBtnToolbar('insertselected', 'Insert For Selected Supplier', 'fa fa-plus', 'Insert Only Selected Supplier');



          if ($qttyOpt > 1) {
          if ($defaultType == 'R') {
          $grid->addUserBtnToolbar('insertother', 'Insert For Selected Supplier - NEW', 'fa fa-plus-circle', 'Insert For Selected Supplier - NEW');
          } else {
          $grid->addUserBtnToolbar('insertother', 'Insert For Selected Supplier - Repair', 'fa fa-plus-circle', 'Insert For Selected Supplier - Repair');
          }
          }

         */

        //$grid->addBreakToolbar();
        //$grid->addDelToolbar();
        $grid->setGridToolbarFunction("dsRFQSupObject.ToolbarGrid");
        $grid->setToolbarSearch(true);
        
        if ($rights['canChangeCost']) {
            $grid->addUserBtnToolbar('newround', 'Add new Round', 'fa fa-plus-square-o', 'Add New Round');
            $grid->addUserBtnToolbar('deleteround', 'Delete Last Round', 'fa fa-trash', 'Delete Last Round');
        }
        
        $grid->addUserCheckToolbar('showlast', 'Show Only Last Round', 'Show Only Last Round', true);
        $grid->addSpacerToolbar();
        $grid->addExportToolbar();


        $grid->setCRUDController("rfq/rfq_item_supplier_quotation");

        $grid->addColumnKey();

        $grid->setGridToolbarFunction("dsRFQSupObject.ToolbarGrid");

        //$grid->addHiddenColumn('fl_last', 'Is Last', '20px', $f->retTypeStringAny(), false);
        //$grid->addHiddenColumn('cd_supplier', 'Supplier Code', '20px', $f->retTypeInteger(), false);
        $grid->addHiddenColumn('ds_supplier', 'Supplier', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_equipment_design', 'Equipment', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_supplier_equipment_description', 'Description', '150px', $f->retTypeStringAny(), array('limit' => '1281'));
        $grid->addColumn('ds_supplier_equipment_part_number', 'Part Number', '100px', $f->retTypeStringAny(), array('limit' => '64'));

        $grid->addColumn('nr_round', 'Round', '60px', $f->retTypeInteger(), false);


        //if ($qttyOpt > 1) {
        $grid->addColumn('ds_kind_description', 'Kind', '60px', $f->retTypeStringAny(), false);
        //}

        $grid->addColumn('nr_price', 'Price', '100px', $f->retTypeNum(), array('precision' => '4', 'readonly' => false));
        $grid->addColumn('nr_price_with_tax', 'Price Tax', '100px', $f->retTypeNum(), array('precision' => '4', 'readonly' => true));

        $grid->addColumn('ds_currency', 'Currency', '100px', $f->retTypePickList(), array('model' => 'currency_model', 'codeField' => 'cd_currency'));

        $grid->addColumn('nr_price_default_currency', 'Price RMB', '100px', $f->retTypeNum(), array('precision' => '4', 'readonly' => true));
        $grid->addColumn('nr_price_with_tax_default_currency', 'Price Tax RMB', '100px', $f->retTypeNum(), array('precision' => '4', 'readonly' => true));

        $grid->addColumn('nr_moq', 'MOQ', '80px', $f->retTypeNum(), array('precision' => '0', 'readonly' => false));
        $grid->addColumn('nr_leadtime', 'Leadtime', '100px', $f->retTypeInteger(), true);
        $grid->addColumn('dt_expiring_date', 'Expiring Date', '100px', $f->retTypeDate(), true);
        $grid->addColumn('ds_payment_term', 'Payment Term', '100px', $f->retTypePickList(), array('model' => 'rfq/payment_term_model', 'codeField' => 'cd_payment_term'));
        $grid->addColumn('nr_warranty', 'Warranty (Months)', '120px', $f->retTypeInteger(), true);
        $grid->addColumn('ds_remarks', 'Remarks', '120px', $f->retTypeTextPL(), true);
        $grid->addColumn('dt_update', 'Last Update', '120px', $f->retTypeDate(), false);
        $grid->addRecords($this->quomodel->retRetrieveGridJson(' WHERE "RFQ_ITEM".cd_rfq = ' . $cd_rfq, ' ORDER BY ds_supplier ASC, "RFQ_ITEM_SUPPLIER_QUOTATION".nr_round DESC, ds_equipment_design ASC, ds_kind ASC '));

        $grid->setGridName('quotationGrid');
        $grid->setGridDivName('quotationDivGrid');


        $SupModelGrid = $SupModelGrid . $grid->retGrid();
        $grid->resetGrid();


        $trans = array('errordeletequo' => 'You Only can delete the last round of the selected supplier!', 'errordeletesup' => 'You Only can delete the supplier if no Quotation or Samples related!');

        $trans = $this->cdbhelper->retTranslationDifKeys($trans);
        $send = array('SupModelGrid' => $SupModelGrid, 'cd_rfq' => $cd_rfq);

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

        $send['canChangeCost'] = $rights['canChangeCost'] ? 1 : 0;

        $this->load->view("rfq/rfq_supplier_form_view", $trans + $send);
    }

    public function updateData($cd_rfq, $nr_round_actual, $cd_supplier, $toDelete = 'N') {
        $upd_array = json_decode($_POST['gridsup']);
        $gridquo = json_decode($_POST['gridquo']);
        $gridsupitem = array();

        foreach ($gridquo as $key => $value) {
            $value = (array) $value;
            if (isset($value['cd_rfq_item_supplier'])) {
                $arrayAdd = array('recid' => $value['cd_rfq_item_supplier']);

                if (isset($value['ds_supplier_equipment_description'])) {
                    $arrayAdd['ds_supplier_equipment_description'] = $value['ds_supplier_equipment_description'];
                }

                if (isset($value['ds_supplier_equipment_part_number'])) {
                    $arrayAdd['ds_supplier_equipment_part_number'] = $value['ds_supplier_equipment_part_number'];
                }

                array_push($gridsupitem, $arrayAdd);
            }
        }




        $this->getCdbhelper()->trans_begin();

        $error = $this->mainmodel->updateGridData($upd_array);
        if ($error != 'OK') {
            $this->getCdbhelper()->trans_rollback();
            $msg = '{"status":' . json_encode($error) . ', "rs":{} }';
            echo($msg);
            return;
        }

        $error = $this->quomodel->updateGridData($gridquo);
        if ($error != 'OK') {
            $this->getCdbhelper()->trans_rollback();
            $msg = '{"status":' . json_encode($error) . ', "rs":{} }';
            echo($msg);
            return;
        }

        $error = $this->supitemmodel->updateGridData($gridsupitem);
        if ($error != 'OK') {
            $this->getCdbhelper()->trans_rollback();
            $msg = '{"status":' . json_encode($error) . ', "rs":{} }';
            echo($msg);
            return;
        }


        if ($nr_round_actual != -1 && $toDelete == 'N') {
            $error = $this->insRound($cd_rfq, $nr_round_actual, $cd_supplier);
            if ($error != 'OK') {
                $this->getCdbhelper()->trans_rollback();
                $msg = '{"status":' . json_encode($error) . ', "rs":{} }';
                echo($msg);
                return;
            }
        }


        if ($nr_round_actual != -1 && $toDelete == 'Y') {
            $quo = $this->quomodel->retRetrieveGridArray(' WHERE "RFQ_ITEM_SUPPLIER_QUOTATION".nr_round = ' . $nr_round_actual . ' AND "RFQ_ITEM_SUPPLIER".cd_supplier = ' . $cd_supplier . ' AND "RFQ_ITEM".cd_rfq = ' .$cd_rfq);
            $error = $this->quomodel->deleteGridData($quo);
            if ($error != 'OK') {
                $this->getCdbhelper()->trans_rollback();
                $msg = '{"status":' . json_encode($error) . ', "rs":{} }';
                echo($msg);
                return;
            }

            $sup = $this->mainmodel->retRetrieveGridArray(' WHERE "RFQ_SUPPLIER".cd_rfq = ' . $cd_rfq . ' AND "RFQ_SUPPLIER".cd_supplier = ' . $cd_supplier, ' ORDER BY ds_supplier ');
            $sup[0]['nr_round'] = $nr_round_actual - 1;
            $error = $this->mainmodel->updateGridData($sup);
            if ($error != 'OK') {
                $this->getCdbhelper()->trans_rollback();
                $msg = '{"status":' . json_encode($error) . ', "rs":{} }';
                echo($msg);
                return;
            }
        }


        $this->getCdbhelper()->trans_commit();
        $this->getCdbhelper()->trans_end();

        $item = $this->itemmodel->retRetrieveGridJson(' WHERE "RFQ_ITEM".cd_rfq = ' . $cd_rfq);
        $sup = $this->mainmodel->retRetrieveGridJson(' WHERE "RFQ_SUPPLIER".cd_rfq = ' . $cd_rfq, ' ORDER BY ds_supplier ');
        $quo = $this->quomodel->retRetrieveGridJson(' WHERE "RFQ_ITEM".cd_rfq = ' . $cd_rfq, ' ORDER BY ds_supplier ASC, "RFQ_ITEM_SUPPLIER_QUOTATION".nr_round DESC, ds_equipment_design ASC, ds_kind ASC ');

        $msg = '{"status":' . json_encode($error) . ', "sup": ' . $sup . ', "quo": ' . $quo . ', "item": '.$item.'}';

        echo($msg);
    }

    public function insRound($cd_rfq, $nr_round_actual, $cd_supplier) {


        $sup = $this->mainmodel->retRetrieveGridArray(' WHERE "RFQ_SUPPLIER".cd_rfq = ' . $cd_rfq . ' AND "RFQ_SUPPLIER".cd_supplier = ' . $cd_supplier, ' ORDER BY ds_supplier ');
        $items = $this->itemmodel->retRetrieveGridArray(' WHERE "RFQ_ITEM".cd_rfq = ' . $cd_rfq, 'ORDER BY ds_equipment_design ASC ');


        //$nr_round_actual ++;

        $toAdd = array();

        foreach ($items as $valueItems) {
            if ($valueItems['fl_is_new'] == 1) {
                $quo = $this->quomodel->retRetrieveGridArray(' WHERE "RFQ_ITEM_SUPPLIER".cd_rfq_item = ' . $valueItems['recid'] . ' AND "RFQ_ITEM_SUPPLIER_QUOTATION".nr_round = ' . $nr_round_actual . ' AND "RFQ_ITEM_SUPPLIER".cd_supplier = ' . $cd_supplier . ' AND "RFQ_ITEM_SUPPLIER_QUOTATION".ds_kind = \'N\' ');
                array_push($toAdd, $this->makeInsArray($nr_round_actual + 1, $cd_supplier, $valueItems, $quo, 'N'));
            }

            if ($valueItems['fl_is_repair'] == 1) {
                $quo = $this->quomodel->retRetrieveGridArray(' WHERE "RFQ_ITEM_SUPPLIER".cd_rfq_item = ' . $valueItems['recid'] . ' AND "RFQ_ITEM_SUPPLIER_QUOTATION".nr_round = ' . $nr_round_actual . ' AND "RFQ_ITEM_SUPPLIER".cd_supplier = ' . $cd_supplier . ' AND "RFQ_ITEM_SUPPLIER_QUOTATION".ds_kind = \'R\' ');
                array_push($toAdd, $this->makeInsArray($nr_round_actual + 1, $cd_supplier, $valueItems, $quo, 'R'));
            }
        }



        $sup[0]['nr_round'] = $nr_round_actual + 1;




        $error = $this->quomodel->updateGridData($toAdd);
        IF ($error != 'OK') {
            return $error;
        }

        // adjust last round;
        $error = $this->mainmodel->updateGridData($sup);

        return $error;
    }

    public function makeInsArray($round, $cd_supplier, $item, $quo, $kind) {
        $cur = $this->getCdbhelper()->getSystemParameters('DEFAULT_CURRENCY_RFQ');
        $defpay = $this->getCdbhelper()->getSystemParameters('DEFAULT_PAYMENT_TERM_RFQ');


        if (count($quo) == 0) {
            $sup = $this->supitemmodel->retRetrieveGridArray(' WHERE "RFQ_ITEM_SUPPLIER".cd_rfq_item = ' . $item['cd_rfq_item'] . ' AND "RFQ_ITEM_SUPPLIER".cd_supplier =  ' . $cd_supplier)[0];

            $array = $this->quomodel->retRetrieveEmptyNewArray()[0];

            $array['nr_round'] = $round;
            $array['cd_rfq_item_supplier'] = $sup['cd_rfq_item_supplier'];
            $array['cd_currency'] = $cur;
            $array['cd_payment_term'] = $defpay;
            $array['nr_price'] = 0;
            $array['ds_kind'] = $kind;
            unset($array['cd_rfq_item_supplier_quotation']);
        } else {

            $array = $quo[0];
            $array['recid'] = $this->quomodel->getNextCode();
            $array['nr_round'] = $round;
            unset($array['cd_rfq_item_supplier_quotation']);
        }

        return $array;
    }

}
