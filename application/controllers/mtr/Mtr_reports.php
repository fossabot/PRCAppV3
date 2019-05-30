<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class mtr_reports extends controllerBasicExtend
{

    var $arrayIns;
    var $fields;

    function __construct()
    {
        parent::__construct();
        $this->load->model("mtr/mtr_reports_model", "mainmodel", TRUE);
    }

    public function index()
    {

        parent::checkMenuPermission();


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

        $fm = $this->cfiltermaker;

        $ctabs->addTab('Browse', 'tab_browse');
        $ctabs->addTab('Details', 'tab_detail');
        $ctabs->setMainDivId('mainTabsDiv');
        $ctabs->setContentDivId('tab_browse_div');


        //$fm->addSimpleFilterUpper('BrandProjectNum', 'filter_1', 't.BrandProjectNum');
        $fm->addFilter('filter_1', 'Project#', array('fieldname' => "BrandProjectNum + '-' + ProjectNumber", 'likeIlike' => 'L', 'startWith' => false));
        $fm->addFilter('filter_2', 'Model#', array('fieldname' => "BrandModelNum + '-' + TTIModelNumber", 'likeIlike' => 'L', 'startWith' => false));
        $fm->addFilter('filter_3', 'Description', array('fieldname' => "Description", 'likeIlike' => 'L', 'startWith' => false));
        $fm->addFilter('filter_4', 'Project Type', array('fieldname' => "PriorityGroup", 'likeIlike' => 'L', 'startWith' => false));
        $fixed = array(
            array('desc' => 'AC Power Tools',
                'sql' => ' AND t.ACDC = 1'),
            array('desc' => 'DC Power Tools',
                'sql' => ' AND t.ACDC = 2'),
            array('desc' => 'Hand Tools',
                'sql' => ' AND t.ACDC = 3'),
            array('desc' => 'Charger',
                'sql' => ' AND t.ACDC = 4'),
            array('desc' => 'Battery',
                'sql' => ' AND t.ACDC = 5'),
            array('desc' => 'Accessory',
                'sql' => ' AND t.ACDC = 6'),
            array('desc' => 'Air Tool',
                'sql' => ' AND t.ACDC = 7'),
            array('desc' => 'T&M Tool',
                'sql' => ' AND t.ACDC = 8'),
        );


        $fm->addFilter('filter_tools', 'Tool Type', array('plFixedSelect' => $fixed));


        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("mtr/mtr_reports");

        $grid->addColumnKey();

        $grid->addColumn('BrandProjectNum', 'MT Project #', '100%', $f->retTypeStringAny(), false);
        $grid->addColumn('BrandModelNum', 'MT Model #', '100%', $f->retTypeStringAny(), false);
        $grid->addColumn('ProjectNumber', 'TTI Project #', '100%', $f->retTypeStringAny(), false);
        $grid->addColumn('TTIModelNumber', 'TTI Model #', '100%', $f->retTypeStringAny(), false);
        $grid->addColumn('Description', 'Description', '100%', $f->retTypeStringAny(), false);
        $grid->addColumn('PriorityGroup', 'Project Type', '100%', $f->retTypeStringAny(), false);
        $grid->addColumn('ToolType', 'Tool Type', '100%', $f->retTypeStringAny(), false);
        $grid->addColumn('StartDate', 'Proj Start Date', '100%', $f->retTypeDate(), false);
        $grid->addColumn('ReportQty', 'Report Qty', '100%', $f->retTypeInteger(), false);


//        $grid->addColumnDeactivated(true);
        $grid->setGridDivName('tab_browse_div');

        $filters = $fm->retFiltersWithGroup();
        $javascript = $grid->retGrid();


        $grid->resetGrid();
        $grid->setSingleBarControl(true);
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("mtr/mtr_reports");

        $grid->addColumnKey();

//        $grid->addColumn('AttachmentFileName', 'AttachmentFileName', '100%', $f->retTypeStringAny(), false);
//        $grid->addColumn('path', 'path', '100%', $f->retTypeStringAny(), false);
        $grid->addColumn('TRNumber', 'TR #', '80px', $f->retTypeStringAny(), false);
//        $grid->addColumn('BrandProjectNum', 'MT Project #', '60px', $f->retTypeStringAny(), false);
//        $grid->addColumn('TTI Project #', 'TTI Project #', '60px', $f->retTypeStringAny(), false);
        $grid->addColumn('WorkOrderID', 'WorkOrder', '90px', $f->retTypeStringAny(), false);
        $grid->addColumn('Model Description', 'Model Description', '120px', $f->retTypeStringAny(), false);
        $grid->addColumn('TestItem', 'Test Item', '120px', $f->retTypeStringAny(), false);
        $grid->addColumn('Project Type', 'Project Type', '120px', $f->retTypeStringAny(), false);
        $grid->addColumn('Test Build', 'Test Build', '120px', $f->retTypeStringAny(), false);
//        $grid->addColumn('TTI Model #', 'TTI Model #', '120px', $f->retTypeStringAny(), false);
//        $grid->addColumn('MT Model #', 'MT Model #', '90px', $f->retTypeStringAny(), false);
        $grid->addColumn('Test Purpose', 'Test Purpose', '120px', $f->retTypeStringAny(), false);
        $grid->addColumn('Requestor', 'Requestor', '80px', $f->retTypeStringAny(), false);
        $grid->addColumn('RequestDate', 'Request Date', '80px', $f->retTypeDate(), false);
        $grid->addColumn('TRResponsible', 'TR Responsible', '80px', $f->retTypeStringAny(), false);
        $grid->addColumn('WOResponsible', 'WO Responsible', '80px', $f->retTypeStringAny(), false);
        $grid->addColumn('MFactory', 'MFactory', '80px', $f->retTypeStringAny(), false);
        $grid->addColumn('ToolQuantities', 'Tool Quantities', '80px', $f->retTypeStringAny(), false);
//        $grid->addColumn('UpdateUser', 'UpdateUser', '100%', $f->retTypeStringAny(), false);
        $grid->addColumn('UpdateDate', 'Update Date', '100px', $f->retTypeDate(), false);
//        $grid->addColumn('ApproveBy', 'ApproveBy', '100%', $f->retTypeStringAny(), false);
//        $grid->addColumn('ApproveDate', 'ApproveDate', '100%', $f->retTypeDate(), false);


        $grid->setGridName('detailgrid');
        $grid->setGridDivName('tab_detail_div');


        $javascript = $javascript . $grid->retGrid();


        $trans = array();
        $trans = $this->cdbhelper->retTranslationDifKeys($trans);


        $send = array("javascript" => $javascript,
                "filters" => $filters,
                'tab' => $ctabs->retTabs(),
                "filters_java" => $fm->retJavascript()) + $trans;
        $this->load->view("mtr/mtr_reports_view", $send);
    }

    public function retrReports($rowID)
    {
//        die(urldecode($TTIModelNumber));
       echo(json_encode($this->getTestReport($rowID)));
    }

    public function getTestReport($rowID)
    {
        $data = $this->mainmodel->retRetrieveTestReport($rowID);
        return $data;

    }

    public function openFile()
    {
        $filename = $_POST['path'];
        
        //die ($filename );

        $onlyfile = substr($filename,strpos($filename,"20"));

        $linuxpath = '/media/MilTestReport/' . $onlyfile;
        

        //$filename=  str_replace("\\","\\\\", $filename);

//        die($filename);
        $fp = @fopen($linuxpath, 'r');

        header('Content-Type: application/download');
        header("Content-Disposition: attachment; filename=" . $onlyfile . "");
        header("Content-Length: " . filesize($linuxpath));
        fpassthru($fp);
        fclose($fp);

    }

}
