<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class rfq_cost_center extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("rfq/rfq_cost_center_model", "mainmodel", TRUE);
        $this->load->model("rfq/rfq_item_model", "itemmodel", TRUE);
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

        $fm->addPickListFilter('Rfq Item', 'filter_1', 'rfq/rfq_item', '"RFQ_COST_CENTER".cd_rfq_item');
        $fm->addPickListFilter('Department Cost Center', 'filter_2', 'rfq/department_cost_center', '"RFQ_COST_CENTER".cd_department_cost_center');
        $fm->addSimpleFilterUpper('Project Number', 'filter_3', '"RFQ_COST_CENTER".ds_project_number');
        $fm->addFilterYesNo("Active", "dt_deactivated", "", "Y");



        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("rfq/rfq_cost_center");

        $grid->addColumnKey();

        $grid->addColumn('ds_rfq_item', 'Rfq Item', '150px', $f->retTypePickList(), array('model' => 'rfq/rfq_item_model', 'codeField' => 'cd_rfq_item'));
        $grid->addColumn('ds_department_cost_center', 'Department Cost Center', '150px', $f->retTypePickList(), array('model' => 'rfq/department_cost_center_model', 'codeField' => 'cd_department_cost_center'));
        $grid->addColumn('ds_project_number', 'Project Number', '150px', $f->retTypeStringAny(), array('limit' => ''));
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

    public function openDepForm($cd_rfq) {


        $rights = $this->rfqmodel->checkRights($cd_rfq);

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


        // supplier info
        $grid->setSingleBarControl(true);
        $grid->setGridToolbarFunction("dsRFQDepObject.ToolbarGrid");

        $grid->addBreakToolbar();

        if ($rights['canChangeCost']) {
            $grid->addUserBtnToolbar('copy', 'Copy Department Data', 'fa fa-copy');
            $grid->addUserBtnToolbar('paste', 'Paste Department Data', 'fa fa-paste');
            $grid->addBreakToolbar();
        }
        $grid->addUserBtnToolbar('close', 'Close', 'fa fa-close');
        $grid->addSpacerToolbar();
        $grid->addExportToolbar();


        $grid->setToolbarSearch(true);
        $grid->setCRUDController("rfq/rfq_supplier");
        $grid->setRowHeight('32');
        $grid->addColumnKey();
        $grid->addColumn('ds_equipment_design_full', 'Equipment', '70%', $f->retTypeStringAny(), false);
        $grid->addColumn('nr_qtty_quote', 'Qty Quote', '15%', $f->retTypeNum(), false);
        $grid->addColumn('nr_qtty_to_buy', 'Qty Buy', '15%', $f->retTypeNum(), false);
        
        //$grid->addColumn('ds_equipment_design_desc_complement', 'Description', '40%', $f->retTypeStringAny(), false);
        //$grid->addColumn('ds_supplier_equipment_part_number', 'Part Number', '100px', $f->retTypeStringAny(), false);

        $grid->setGridName('itemGrid');
        $grid->setGridDivName('itemGridDiv');
        $grid->addRecords($this->itemmodel->retRetrieveGridJson(' WHERE "RFQ_ITEM".cd_rfq = ' . $cd_rfq, ' ORDER BY ds_equipment_design_full'));

        $SupModelGrid = $grid->retGrid();
        $grid->resetGrid();

        // quotation
        $this->setGridParser();
        $grid->setInsertNegative(true);
        $grid->setSingleBarControl(true);



        $grid->setGridToolbarFunction("dsRFQDepObject.ToolbarGrid");
        $grid->setToolbarSearch(false);
        if ($rights['canChangeCost']) {
            $grid->addBreakToolbar();
            $grid->addInsToolbar();
            $grid->addUpdToolbar();
            $grid->addDelToolbar();
        }

        $grid->addSpacerToolbar();
        $grid->addExportToolbar();


        $grid->setCRUDController("rfq/rfq_cost_center");
        $grid->addColumnKey();
        $grid->setRowHeight('32');

        $grid->addColumn('nr_qtty_to_charge', 'Qty Charge', '80px', $f->retTypeNum(), array('precision' => '0', 'readonly' => false));
        $grid->addColumn('ds_department_cost_center', 'Department', '100px', $f->retTypePickList(), array('model' => 'rfq/department_cost_center_model', 'codeField' => 'cd_department_cost_center'));

        if ($rights['canChangeCost']) {
            $grid->addColumn('ds_pl_prj', '', '30px', $f->retTypeStringAny(), false);
            $grid->setColumnRenderFunc('ds_pl_prj', 'dsRFQDepObject.btnPLRender');
        }


        $grid->addColumn('ds_project_number', 'Project#', '100px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_project_model_number', 'Model#', '100px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_project_description', 'Project Description', '100%', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_project_number_ref', 'Project Ref #', '100px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_project_model_number_ref', 'Model Ref #', '100px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_project_description_ref', 'Proj Desc Ref', '100%', $f->retTypeStringAny(), false);


        $grid->addRecords($this->mainmodel->retRetrieveGridJson(' WHERE "RFQ_ITEM".cd_rfq = ' . $cd_rfq, ' ORDER BY 2'));

        $grid->setGridToolbarFunction("dsRFQDepObject.ToolbarGrid");

        $grid->setGridName('departmentGrid');
        $grid->setGridDivName('departmentDivGrid');


        $SupModelGrid = $SupModelGrid . $grid->retGrid();
        $grid->resetGrid();


        $trans = array('errordeletequo' => 'You Only can delete the last round of the selected supplier!', 'errordeletesup' => 'You Only can delete the supplier if no Quotation or Samples related!');

        $trans = $this->cdbhelper->retTranslationDifKeys($trans);
        $send = array('SupModelGrid' => $SupModelGrid, 'cd_rfq' => $cd_rfq);
        $send['readonly'] = ( $rights['canChange'] ? 0 : 1 );
        $send['canChangeCost'] = ( $rights['canChangeCost'] ? 1 : 0 );


        $this->load->view("rfq/rfq_departments_form_view", $trans + $send);
    }

}
