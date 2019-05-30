<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class tr_test_request_work_order_sample extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("tr/tr_test_request_work_order_sample_model", "mainmodel", TRUE);
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

        $fm->setFilterLevels(2);

        $fm->addFilterDate('Link Project/WO Date', 'filter_dt_sup', '"PROJECT_BUILD_SCHEDULE_TESTS_WO".dt_record', date('m/d/Y', strtotime('first day of -2 month')), date('m/d/Y', strtotime('+1 day')));
        $fm->addFilter('filter_8', 'Project#', array('fieldname' => "COALESCE(\"PROJECT\".ds_met_project, '') || '-' || COALESCE(\"PROJECT\".ds_tti_project, '') || '-'", 'likeIlike' => 'I', 'startWith' => false));
        $fm->addFilter('filter_10', 'Model#', array('fieldname' => "COALESCE(\"PROJECT_MODEL\".ds_met_project_model, '') || '-' || COALESCE(\"PROJECT_MODEL\".ds_tti_project_model, '') || '-'", 'likeIlike' => 'I', 'startWith' => false));
        $fm->addPickListFilter('Project Status', 'filter_25', 'tti/project_status', '"PROJECT_MODEL".cd_project_status');
        

        $fm->addPickListFilter('Tool Type', 'filter_6', 'tti/project_tool_type', '"PROJECT".cd_project_tool_type');
        

        $fm->addFilter('filter_1', 'Project Name', array('selector' => 'filter_1', 'fieldname' => '"PROJECT".ds_project', 'likeIlike' => 'I', 'startWith' => false));
        $fm->addFilter('filter_test', 'Test Type', array('controller' => 'tr/test_type', 'fieldname' => '"PROJECT_BUILD_SCHEDULE_TESTS".cd_test_type', 'multi' => true));
        

        $fixed = array(
            array('desc' => 'WITH TEST DATA', 'sql' => 'AND (u.nr_workorder_number IS NOT NULL OR bl.nr_wo_data IS NOT NULL OR mte.nr_wo_code IS NOT NULL ) ', 'default' => 'Y'),
            array('desc' => 'MTE DATA', 'sql' => 'AND mte.nr_wo_code IS NOT NULL '),
            array('desc' => 'FIXTURE DATA', 'sql' => 'AND (u.nr_workorder_number IS NOT NULL OR bl.nr_wo_data IS NOT NULL) '),
        );
        
        $fm->addFilter('filter_test_data', 'Test Data', array('plFixedSelect' => $fixed));
        
        $fm->addPickListFilter('Product', 'filter_12', 'tti/project_product', '"PROJECT".cd_project_product');
        $fm->addPickListFilter('Category', 'filter_20', 'tti/project_power_type', '"PROJECT".cd_project_power_type');
        

        $fm->addPickListFilter('Voltage', 'filter_11', 'tti/project_voltage', '"PROJECT_MODEL".cd_project_voltage');

        $fm->addPickListFilterExists("User", "human_resource_controller", "filter_2", "PROJECT_MODEL", "cd_project_model", "PROJECT_USER_ROLES", "cd_human_resource", "cd_project_model", false);

        //$fm->addPickListFilter('User', 'filter_2', 'human_resource_controller', '"PROJECT".cd_human_resource_prc_pm');


        $fm->addSimpleFilterUpper('TR #', 'filter_tr', '"TR_TEST_REQUEST".ds_tr_number');
        $fm->addFilterNumber('Work Order #', 'filter_Wo', '"TR_TEST_REQUEST_WORK_ORDER".nr_work_order', '10.2', '', '');
        
        
        $fixed = array(
            array('desc' => '1000', 'sql' => 'and 1000 = 1000', 'default' => 'Y'),
            array('desc' => '2000', 'sql' => 'and 2000 = 2000'),
            array('desc' => '5000', 'sql' => 'and 5000 = 5000')
        );

        $fm->addFilter('filter_rows_to_show', 'Lines Displayed', array('plFixedSelect' => $fixed));

        $fixed = array(array('desc' => 'YES', 'sql' => ' AND EXISTS ( SELECT 1 FROM "PROJECT_USER_ROLES" x where x.cd_human_resource = ' . $this->session->userdata('cd_human_resource') . ' AND x.cd_project_model = "PROJECT_MODEL".cd_project_model )'));
        $fm->addFilter('filter_only_mine', 'Only My Projects', array('plFixedSelect' => $fixed));

        $fm->addPickListFilter('Department', 'filter_15', 'job_department', '"PROJECT".cd_department');
        $fm->addPickListFilter('Brand', 'filter_21', 'brand', '"PROJECT".cd_brand');

        

        // "( CASE WHEN u.nr_workorder_number IS NOT NULL THEN 'FIXTURE LIFE' WHEN bl.nr_wo_data IS NOT NULL THEN 'FIXTURE LIFE' WHEN mte.nr_wo_code IS NOT NULL THEN 'MTE' END ) as ds_source",



        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setRowHeight(40);
        $grid->setGridName('gridWOShow');
        $grid->setExcelDetailedSendResultSet(true);
        $grid->setExcelDetailed(true);

        $grid->setCRUDController("tr/tr_test_request_work_order_sample");
        $grid->setFilterPresetId('woTestOverview');

        //$grid->addColumn('ds_mte_toolbar', '', '30px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_toolbar', '', '90px', $f->retTypeStringAny(), false);

        $grid->addColumnKey();

        /* PROJECT DATA - Start */
        $grid->addColumn('ds_met_project', 'MET Project #', '100px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_met_project_model', 'MET Model #', '100px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_project_build_full', 'Build', '50px', $f->retTypeStringAny(), false);
        $grid->addColumn('nr_work_order', 'Work Order', '90px', $f->retTypeStringAny(), false);
        $grid->addColumn('nr_sample', 'Sample#', '70px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_test_type', 'Test Type', '80px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_test_item', 'Test Item', '80px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_goal', 'Goal', '80px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_goal_unit', 'Unit', '80px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_comp_cycle', 'Comp.Cycles', '95px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_comp_runtime', 'Comp. Runtime', '100px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_comp_apps', 'Comp.Apps', '80px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_comp_discharge', 'Comp.Discharge', '120px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_tr_test_request_work_order_status', 'TR Status', '80px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_tool_status', 'Test Detail Status', '95px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_project_status', 'Project Status', '80px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_test_procedure_name', 'Procedure', '80px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_source', 'Source', '90px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_tr_number', 'TR#', '80px', $f->retTypeStringAny(), false);
        $grid->addColumn('dt_supervisor_approval', 'Supervisor Approval', '120px', $f->retTypeDate(), false);
        $grid->addColumn('ds_type_test', 'Type', '80px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_work_order_name', 'Work Order Name', '100px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_remarks', 'Remarks', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_test_result', 'Test Result', '100px', $f->retTypeStringAny(), false);

        $grid->addColumn('ds_start_date', 'Start Date', '95px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_estimated_complete', 'Est.Complete', '95px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_actual_complete', 'Actual Complete', '95px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_work_station', 'Workstation', '100px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_operator', 'Operator', '100px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_room_code', 'Room Name', '100px', $f->retTypeStringAny(), false);

        $grid->addColumn('ds_toolbar_2', '', '90px', $f->retTypeStringAny(), false);


        $grid->addColumn('ds_department', 'Department', '100px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_tti_project', 'TTi Project #', '100px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_tti_project_model', 'TTi Model #', '100px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_project', 'Project Name', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_project_model', 'Model Description', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('fl_confidential', 'Confidential', '80px', $f->retTypeCheckBox(), false);
        $grid->addColumn('ds_project_product', 'Product', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_project_power_type', 'Category', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_project_tool_type', 'Tool Type', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_project_voltage', 'Voltage', '80px', $f->retTypeStringAny(), false);

        /* PROJECT DATA - END */







        //$grid->addColumn('ds_sample_status', 'Sample Status', '90px', $f->retTypeStringAny(), false);
        //$grid->setColumnRenderFunc("ds_remarks", 'thisObj.renderTRRemarks');
        // MTE
        //$grid->addColumnGroup(2, '');
        //$grid->addColumnGroup(13, 'LMS');
        //$grid->addColumnGroup(14, 'TR');
        //$grid->addColumnGroup(12, 'Test Details');
        $grid->setColumnRenderFunc("ds_toolbar", 'dsMainObject.toolbarTRRemarks');
        $grid->setColumnRenderFunc("ds_toolbar_2", 'dsMainObject.toolbarTRRemarks');
        $grid->setColumnRenderFunc("ds_remarks", 'dsMainObject.renderTRRemarks');


        $filters = $fm->retFiltersWithGroup();
        $javascript = $grid->retGrid();


        $trans = array('NoAttachment' => 'No Attachment',
            'withAttachment' => 'With Attachment',
            'Planning' => 'Planning',
            'TRs' => 'TRs',
            'testrep' => 'Open Test Report',);
        $trans = $this->cdbhelper->retTranslationDifKeys($trans);



        $send = array("javascript" => $javascript,
            "filters" => $filters,
            "filters_java" => $fm->retJavascript()) + $trans;


        $this->load->view("tr/tr_test_request_work_order_sample_view", $send);
    }

    public function retrieveGridJsonpPower($limit) {
        $this->mainmodel->orderByDefault = 'ORDER BY "PROJECT".dt_update DESC';
        $ar = $this->mainmodel->retrOptionstToBrowse;
        $ar['limit'] = $limit;
        parent::retrieveGridJson($ar);
    }

    public function genXLSDetailed() {
        $class = get_class($this);

        
        
        $array_title = array();

        array_push($array_title, 
            array(
                'color' => 'FFFFFF',
                'Title' => 'Test Data',
                'col'   => array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42)
            )
        );

            array_push($array_title, 
            array(
                'color' => 'F0F0F0',
                'Title' => 'LMS',
                'col'   => array(2,3,4,7,9,10,17,33,34,35,36,37,38,39,40,41,42)
            )
        );
            
          array_push($array_title, 
            array(
                'color' => 'e7fdff',
                'Title' => 'TR',
                'col'   => array(5,6,8,15,18,23,24,25)
            )
        );
        

        $this->load->library('cexcel');
        $xls = $this->cexcel;
        if (1 == 2) {
            $xls = new cexcel;
        }
        
        
        
        if (isset($_POST['resultset'])) {
            $resultset = $_POST['resultset'];
        } else {
            $resultset = $this->retrieveGridArray();
        }

        $resultset = json_decode($resultset, true);
        $name = 'Test Overview';

        $columns = json_decode($_POST['col']);
        $titlecolumn = json_decode($_POST['title']);
        $group = json_decode($_POST['group']);
        $rowHeight = $_POST['rowHeight'];
        $xls->setDocRep($_POST['docrep']);

        $xls->newSpreadSheet();
        $xls->createExcelByGrid($name, $columns, $titlecolumn, $group, $resultset, $rowHeight);
        
        $xls->insertNewRowBefore(2, 1);
        
        $xls->insertNewRowBefore(3, 1);
        // make title: 
        foreach ($array_title as $key => $value) {
            $xls->selectArea(2, $key + 2);
            $xls->setItemString(2, $key + 2, $value['Title']);
            $xls->setBackgroundColor($value['color']);
            $xls->setBorderThin();
            $xls->setAlignHCenter();
            
            foreach ($value['col'] as $keyC => $valueC) {
                $xls->selectArea(4, $valueC);
                $xls->setBackgroundColor($value['color']);
            }
            
        }
        
        $time = time();


        $xls->saveAsOutput("$name$time.xlsx");
        $xls->cleanMemory();
    }

}
