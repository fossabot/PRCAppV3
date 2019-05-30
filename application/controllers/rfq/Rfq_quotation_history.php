<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class rfq_quotation_history extends controllerBasicExtend
{

    var $arrayIns;
    var $fields;

    function __construct()
    {
        parent::__construct();
        $this->load->model("rfq/rfq_cost_center_model", "mainmodel", TRUE);
        $this->load->model("rfq/rfq_item_model", "itemmodel", TRUE);
        $this->load->model("rfq/rfq_model", "rfqmodel", TRUE);
//        $this->load->model("rfq/equipment_design_model", "equipmentmodel", TRUE);
        $this->load->model("rfq/rfq_item_supplier_quotation_model", "quomodel", TRUE);
    }

    public function index()
    {

        parent::checkMenuPermission();



    }

    public function duplicateRfqItemSupplierQuotation() {
//        $cd_rfq = $this->getCdbhelper()->normalizeDataToSQL('int', $cd_rfq_item);
        
        $vx = $_POST['toSend'];
        
        $this->db->query("DROP TABLE IF EXISTS datasupplier");
        $this->db->query("CREATE TEMPORARY TABLE datasupplier (item bigint, quot bigint, sup bigint, rfqcopy bigint)");
        $this->db->insert_batch('datasupplier', $vx); 

        $this->getCdbhelper()->basicSQLNoReturn("select rfq.copyRfqSplliers();");
        

        $msg = '{"status": "OK" }';
        echo($msg);
    }

    public function openQuotHisForm($cd_rfq)
    {


        $rights = $this->rfqmodel->checkRights($cd_rfq);

        $grid = $this->w2gridgen;
        $f = $this->cfields;


        if (1 == 2) {
            $f = new Cfields();
            $grid = new w2gridgen();

        }


        // supplier info
        $grid->setSingleBarControl(true);
        $grid->setGridToolbarFunction("dsRFQEqpObject.ToolbarGrid");

        $grid->addBreakToolbar();


        $grid->addUserBtnToolbar('close', 'Close', 'fa fa-close');

        $grid->addSpacerToolbar();
        $grid->addExportToolbar();


        $grid->setToolbarSearch(true);
        $grid->setCRUDController("rfq/rfq_supplier");

        $grid->addColumnKey();
        $grid->addColumn('ds_equipment_design_code', 'Code', '120px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_equipment_design_full', 'Equipment', '100%', $f->retTypeStringAny(), false);



        $grid->setGridName('itemGrid');
        $grid->setGridDivName('itemGridDiv');


        $grid->addRecords($this->itemmodel->retRetrieveGridJson(' WHERE "RFQ_ITEM".cd_rfq = ' . $cd_rfq, ' ORDER BY ds_equipment_design_full'));
        //$this->itemmodel->pk_field="cd_equipment_design";
        //$this->quomodel->pk_field="cd_equipment_design";

//        ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
        $retGrid = $grid->retGrid();
        $grid->resetGrid();

        // quotation
        $this->setGridParser();
        $grid->setInsertNegative(true);
        $grid->setSingleBarControl(true);

        $grid->setToolbarSearch(true);
        $grid->setGridToolbarFunction("dsRFQEqpObject.ToolbarGrid");
        $grid->addUserBtnToolbar('saveQuotation', 'Save as new quotation', 'fa fa-floppy-o', 'Save As New ');

        $grid->addSpacerToolbar();
        $grid->addExportToolbar();



        $grid->addColumnKey();
        
        $grid->setRowHeight('32');
        $grid->addColumn('fl_checked', 'X', '40px', $f->retTypeCheckBox(), true);
        $grid->addColumn('fl_cheapest', 'Sts ', '40px', $f->retTypeStringAny(), true);
        
        
        $grid->addColumn('cd_rfq', 'PUR#', '60px', $f->retTypeStringAny(), false);
        //$grid->addColumn('ds_equipment_design_code', 'Code', '120px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_equipment_design', 'Equipment', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('nr_round', 'Round', '50px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_supplier', 'Supplier', '100px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_unit_measure', 'Unit', '100px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_currency', 'Currency', '100px', $f->retTypeStringAny(), false);
        $grid->addColumn('nr_price', 'Price', '80px', $f->retTypeNum(), array('precision' => '4', 'readonly' => true));
        $grid->addColumn('nr_tax', 'Tax', '60px', $f->retTypeNum(), array('precision' => '2', 'readonly' => true));
        
        
        $grid->addColumn('nr_price_with_tax', 'Price Tax', '80px', $f->retTypeNum(), array('precision' => '4', 'readonly' => true));
        $grid->addColumn('nr_moq', 'MOQ', '80px', $f->retTypeNum(), array('precision' => '0', 'readonly' => true));
        $grid->addColumn('nr_leadtime', 'Leadtime', '80px', $f->retTypeInteger(), false);
        $grid->addColumn('nr_qtty_to_buy', 'Buy Qty', '80px', $f->retTypeInteger(), false);
        $grid->addColumn('ds_remarks', 'Remark', '100px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_kind', 'Kind', '40px', $f->retTypeStringAny(), false);
        
        $grid->addColumn('nr_count_attach', 'Attach Qty', '80px', $f->retTypeNum(), array('precision' => '0', 'readonly' => true));
        
        $grid->addColumn('dt_update', 'Last Update', '80px', $f->retTypeDate(), false);
        
        $grid->setColumnRenderFunc('fl_cheapest', 'dsRFQEqpObject.renderBuy');
        
        $json = $this->quomodel->retRetrieveGridJson(' WHERE sourceItem.cd_rfq = ' . $cd_rfq . ' AND "RFQ_ITEM_SUPPLIER_QUOTATION".nr_price > 0  AND  NOT EXISTS (SELECT 1 FROM "RFQ_SUPPLIER" x WHERE x.cd_rfq = sourceItem.cd_rfq  AND x.cd_supplier = "RFQ_ITEM_SUPPLIER".cd_supplier)       ', 'ORDER BY nr_equal_order ASC, "RFQ_ITEM_SUPPLIER_QUOTATION".dt_update DESC', '',  $this->quomodel->retrOptionsGetOldQuot );
        
       $x = json_decode($json, true);
       foreach ($x as $key => $value) {
           $x[$key]['fl_checked'] = 0;
       };
       
        
       $grid->addRecords(json_encode($x));

        $grid->setGridToolbarFunction("dsRFQDepObject.ToolbarGrid");

        $grid->setGridName('QuotationGrid');
        $grid->setGridDivName('QuotationGridDiv');


        $retGrid = $retGrid . $grid->retGrid();
        $grid->resetGrid();


//        $trans = array('errordeletequo' => 'You Only can delete the last round of the selected supplier!', 'errordeletesup' => 'You Only can delete the supplier if no Quotation or Samples related!');


        $send = array('retGrid' => $retGrid, 'cd_rfq' => $cd_rfq);
        $send['readonly'] = ($rights['canChange'] ? 0 : 1);
        $send['canChangeCost'] = ($rights['canChangeCost'] ? 1 : 0);

        $trans = array('errorSup' => 'Supplier already selected for this item');
        
        $trans = $this->cdbhelper->retTranslationDifKeys($trans);
        

        $this->load->view("rfq/rfq_quotation_history_view",  $send + $trans);
    }

}
