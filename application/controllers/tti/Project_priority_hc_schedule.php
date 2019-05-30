<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class project_priority_hc_schedule extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("tr/test_type_model", "tstitem", TRUE);
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

        $dateStart = date('m/01/Y');
        $dateEnd = date('m/01/Y');

        $fm->setFilterLevels(1);
        $fm->addFilterDate('Range', 'filter_range', '"FACE_SCANNER_RECORD".dt_attend_date', date('m/01/Y'), date('m/t/Y'));

        $fm->setFilterLevels(2);

        $fm->addPickListFilter('Status', 'filter_25', 'tti/project_status', 'm.cd_project_status');

        $fm->addFilter('filter_1', 'Project Name', array('selector' => 'filter_1', 'fieldname' => 'p.ds_project', 'likeIlike' => 'I', 'startWith' => false));
        $fm->addFilter('filter_8', 'Project#', array('fieldname' => "COALESCE(\"PROJECT\".ds_met_project, '') || '-' || COALESCE(\"PROJECT\".ds_tti_project, '') || '-'", 'likeIlike' => 'I', 'startWith' => false));
        $fm->addFilter('filter_10', 'Model#', array('fieldname' => "COALESCE(m.ds_met_project_model, '') || '-' || COALESCE(m.ds_tti_project_model, '') || '-'", 'likeIlike' => 'I', 'startWith' => false));

        //$fm->addPickListFilter('Test Type', 'filter_16', 'tr/test_type', 't.cd_test_type');
        
        $vS = $this->tstitem->selectForPL(" WHERE ds_test_type like '%USE%' ");
        
        $fm->addFilter('filter_16', 'Test Type', array('controller' => 'tr/test_type', 'fieldname' => 't.cd_test_type', 'multi' => true,'selectedData' => $vS));
        
        $fm->addPickListFilter('Product', 'filter_12', 'tti/project_product', 'p.cd_project_product');
        $fm->addPickListFilter('Category', 'filter_20', 'tti/project_power_type', 'p.cd_project_power_type');
        $fm->addPickListFilter('Tool Type', 'filter_6', 'tti/project_tool_type', 'p.cd_project_tool_type');

        $fm->addPickListFilter('Voltage', 'filter_11', 'tti/project_voltage', 'm.cd_project_voltage');

        $fm->addPickListFilterExists("User", "human_resource_controller", "filter_2", "m", "cd_project_model", "PROJECT_USER_ROLES", "cd_human_resource", "cd_project_model", false);

        //$fm->addPickListFilter('User', 'filter_2', 'human_resource_controller', 'p.cd_human_resource_prc_pm');

        $fm->addPickListFilter('Department', 'filter_15', 'job_department', 'p.cd_department');
        $fm->addPickListFilter('Brand', 'filter_21', 'brand', 'p.cd_brand');

        $fixed = array(array('desc' => '10', 'sql' => ' and 10 = 10', "default"=> "Y", "idDesc" => '10'), array('desc' => '25', 'sql' => ' and 25 = 25', "default"=> "Y", "idDesc" => '25'), array('desc' => '50', 'sql' => ' and 50 = 50', "idDesc" => '50'), array('desc' => '100', 'sql' => ' and 100 = 100', "idDesc" => '100'));
        $fm->addFilter('filter_page', 'Records by Page', array('plFixedSelect' => $fixed));
        
        

        $filters = $fm->retFiltersWithGroup();
        $javascript = $grid->retGrid();


        $trans = array();
        $trans = $this->cdbhelper->retTranslationDifKeys($trans);



        $send = array("javascript" => $javascript,
            "filters" => $filters,
            "fl_change_project_priority" => $this->getCdbhelper()->getUserPermission('fl_change_project_priority'),
            "filters_java" => $fm->retJavascript()) + $trans;


        $this->load->view("tti/project_priority_hc_schedule_view", $send);
    }

    public function retrieveData() {

        if (!$this->logincontrol->isProperLogged(false)) {
            echo ( '{"logged": "N", "resultset": [] }' );
            return;
        }

        $dt_start = $_POST['start'];
        $dt_end   = $_POST['end'];
        $where = $this->getWhereToFilter($_POST['where']);
        
        
        
        $where  = str_replace("'", "''", $where);
        
        
        
        
        $sql = "select * from getprojhc( '$dt_start', '$dt_end', ' $where ')";
        
        $json = $this->getCdbhelper()->basicSQLJson($sql, true);
        
        echo ( '{ "logged": "Y", "resultset": ' . $json . ' }' );
    }

}
