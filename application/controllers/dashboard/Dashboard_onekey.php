<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class dashboard_onekey extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("tr/test_unit_model", "mainmodel", TRUE);
        
        $this->load->model("oms/onekey_model", "onekeymodel", TRUE);
        
        
        
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
        
        
        $now = new DateTime();
        $enddate =  $now->format('m/d/Y');  
        $x = $now->sub(new DateInterval('P7D'));
        $startdate =  $x->format('m/d/Y');  
        
        
        $data = $this->onekeymodel->getDataStatisticsByDayFull($startdate, $enddate);
        
        
        
        //$grid->setSingleBarControl(true);
        $grid->addCRUDToolbar(FALSE, false, false, false, false);
        $grid->setToolbarSearch(false);
        $grid->showToolbar(false);
        $grid->setCRUDController("rfq/supplier");

        $grid->addColumnKey();
        
        $grid->addColumn('prod_location', 'Line', '60px', $f->retTypeStringAny(), false);
        $grid->addColumn('PCSTATION', 'PC Station', '60px', $f->retTypeStringAny(), false);
        $grid->addColumn('LASER_ASSET_NO', 'Laser Asset', '85px', $f->retTypeStringAny(), false);
        $grid->addColumn('LASER_SUPPLIER_NAME', 'Supplier', '120px', $f->retTypeStringAny(), false);
        $grid->addColumn('LASER_DATE_INSTALLED', 'Installed', '60px', $f->retTypeStringAny(), false);       
        $grid->addColumn('START_COSTTIME_AVG', 'Start',  '85px', $f->retTypeNum(), false);
        
        $grid->addColumn('RESET_ADAPTER_COSTTIME_AVG', 'Reset Adapter', '85px', $f->retTypeNum(), false);
        $grid->addColumn('OPEN_ACCESSRIGHT_COSTTIME_AVG','Open Access Right',  '85px', $f->retTypeNum(), false);
        $grid->addColumn('READ_FWVERSION_COSTTIME_AVG','FW',  '85px', $f->retTypeNum(), false);
        $grid->addColumn('READ_CELLIDENT_COSTTIME_AVG','Cell Ident',  '85px', $f->retTypeNum(), false);
        $grid->addColumn('READ_NUMBERSER_COSTTIME_AVG','Serial',  '85px', $f->retTypeNum(), false);
        $grid->addColumn( 'READ_PARALLELC_COSTTIME_AVG','ParallelC', '85px', $f->retTypeNum(), false);
        $grid->addColumn('READ_OPERATION_COSTTIME_AVG','Total',  '85px', $f->retTypeNum(), false);
        
        $grid->addColumn('WRITE_BOD_COSTTIME_AVG','BOD',  '85px', $f->retTypeNum(), false);
        $grid->addColumn('WRITE_USERPWD_COSTTIME_AVG','User PWD',  '85px', $f->retTypeNum(), false);
        $grid->addColumn('WRITE_ADMIPWD_COSTTIME_AVG','Admin PWD',  '85px', $f->retTypeNum(), false);
        $grid->addColumn('WRITE_SERVPWD_COSTTIME_AVG', 'Server PWD',  '85px', $f->retTypeNum(), false);
        $grid->addColumn('WRITE_ENCRYLO_COSTTIME_AVG','EncryLO',  '85px', $f->retTypeNum(), false);
        $grid->addColumn('WRITE_ENCRYHI_COSTTIME_AVG','EncryHI',  '85px', $f->retTypeNum(), false);       
        $grid->addColumn('WRITE_MPBID_COSTTIME_AVG','MPBID',  '85px', $f->retTypeNum(), false);
        $grid->addColumn('WRITE_METCPWD_COSTTIME_AVG', 'MET PWD',  '85px', $f->retTypeNum(), false);

        $grid->addColumn('COMMAND_FINISH_COSTTIME_AVG', 'Finished', '85px', $f->retTypeNum(), false);
        $grid->addColumn('ALL_COMMAND_COSTTIME_AVG','Total',  '85px', $f->retTypeNum(), false);
        $grid->addColumn('GEN_SN_COSTTIME_AVG','Gen SN',  '85px', $f->retTypeNum(), false);       
        
        $grid->addColumn('SAVE_DATA_COSTTIME_AVG','Save Data',  '85px', $f->retTypeNum(), false);
        $grid->addColumn('LASER_COSTTIME_AVG','Laser',  '85px', $f->retTypeNum(), false);
        $grid->addColumn('TOTAL_COSTTIME_AVG', 'Total', '85px', $f->retTypeNum(), false);
        
        $grid->addColumnGroup(5, 'Information');
        $grid->addColumnGroup(7, 'Read Average');
        $grid->addColumnGroup(8, 'Write  Average');
        $grid->addColumnGroup(2, 'Command  Average');
        $grid->addColumnGroup(4, 'Totals');

        //$data = $this->getLifeBrakesControllerData(true);

        $grid->addRecords(json_encode($data, JSON_NUMERIC_CHECK));
        $grid->showColumnHeader(true);
        $grid->showFooter(false);
        $grid->showLineNumbers(false);
        $grid->showToolbar(false);
        $javascript = $grid->retGrid();

        $fm = $this->cfiltermaker;

        $trans = array();

        $trans = $this->cdbhelper->retTranslationDifKeys($trans);

        $send = array("javascript" => $javascript,
            "filters" => '',
            "startdate" => $startdate, 
            "enddate" => $enddate, 
            
            "dataPlot" => json_encode($this->getDataByPeriod($startdate, $enddate, 'P', 'ALL', true), JSON_NUMERIC_CHECK),
            "filters_java" => $fm->retJavascript()) + $trans;


        $this->load->view("dashboard/dashboard_onekey_view", $send);
    }

    public function getDataByPeriod($startdate, $enddate, $suporpc, $desc, $return = false) {
        $data = $this->onekeymodel->getDataByPeriod($startdate, $enddate, $suporpc, $desc);
        
        if ($return) {
            return $data;
        } else {
            echo (json_encode(array('data' => $data),JSON_NUMERIC_CHECK ));
        }
        
    }
    

    
    public function getAllData($startdate, $enddate, $suporpc, $desc) {
        $startdate = DateTime::createFromFormat('mdY', $startdate)->format('m/d/Y');
        $enddate   = DateTime::createFromFormat('mdY', $enddate)->format('m/d/Y');
        $dataGeneral = $this->onekeymodel->getDataStatisticsByDayFull($startdate, $enddate);
        $dataDotLine = $this->getDataByPeriod($startdate, $enddate, $suporpc, $desc, true);
        
        echo json_encode($ret = array('gen' => $dataGeneral, 'dotLine' => $dataDotLine), JSON_NUMERIC_CHECK);
        
    }
    
    public function getPlotData($startdate, $enddate, $suporpc, $desc) {
        $desc = urldecode($desc);
        
        $startdate = DateTime::createFromFormat('mdY', $startdate)->format('m/d/Y');
        $enddate   = DateTime::createFromFormat('mdY', $enddate)->format('m/d/Y');
        $dataDotLine = $this->getDataByPeriod($startdate, $enddate, $suporpc, $desc, true);
        
        echo json_encode($ret = array('dotLine' => $dataDotLine), JSON_NUMERIC_CHECK);
        
    }
    
    
    

}
