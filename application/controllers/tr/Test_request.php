<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class test_request extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("tr/test_request_model", "mainmodel", TRUE);
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

        $fm->addPickListFilter('Build', 'filter_3', 'tti/project_build', '"TEST_REQUEST".cd_project_build');
        $fm->addPickListFilter('Project', 'filter_1', 'tti/project', '"TEST_REQUEST".cd_project');
        $fm->addSimpleFilterUpper('TTi #', 'filter_7', '"PROJECT".ds_tti_project');
        $fm->addSimpleFilterUpper('MET #', 'filter_8', '"PROJECT".ds_met_project');

        $fm->addPickListFilter('Model', 'filter_2', 'tti/project_model', '"TEST_REQUEST".cd_project_model');
        $fm->addSimpleFilterUpper('TTi Model#', 'filter_9', '"PROJECT_MODEL".ds_tti_project_model');
        $fm->addSimpleFilterUpper('MET Model#', 'filter_10', '"PROJECT_MODEL".ds_met_project_model');


        $fm->addPickListFilter('Type', 'filter_4', 'tr/test_request_type', '"TEST_REQUEST".cd_test_request_type');
        $fm->addPickListFilter('Purpose', 'filter_5', 'tr/test_request_purpose', '"TEST_REQUEST".cd_test_request_purpose');
        $fm->addPickListFilter('Origin', 'filter_6', 'tr/test_request_origin', '"TEST_REQUEST".cd_test_request_origin');
        $fm->addPickListFilter('Requester', 'filter_10', 'human_resource_controller', '"TEST_REQUEST".cd_human_resource_request');
        $fm->addPickListFilter('Approver', 'filter_12', 'human_resource_controller', '"TEST_REQUEST".cd_human_resource_approver');

        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addBreakToolbar();
        $grid->addInsToolbar();
        $grid->addEditToolbar();
        $grid->addDelToolbar();
        //$grid->addUpdToolbar();
        
        $grid->addCRUDToolbar(true, false, false, false, true);
        
        
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("tr/test_request");

        $grid->addColumnKey();


        $grid->addColumn('ds_project', 'Project', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_project_model', 'Model', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_project_build', 'Build', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('nr_version', 'Version', '80px', $f->retTypeInteger(), false);
        
        $grid->addColumn('ds_test_request_type', 'Type', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_test_request_purpose', 'Purpose', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_test_request_origin', 'Origin', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_description', 'Description', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('fl_return_sample', 'Return Sample', '100px', $f->retTypeCheckBox(), false);
        $grid->addColumn('fl_urgent', 'Urgent', '100px', $f->retTypeCheckBox(), false);
        $grid->addColumn('ds_human_resource_request', 'Requester', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_human_resource_approver', 'Approver', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('dt_approved', 'Approved On', '80px', $f->retTypeDate(), false);

        $filters = $fm->retFiltersWithGroup();
        $javascript = $grid->retGrid();


        $trans = array('testForm' =>'Test Request', 
            'errorNotSel' => 'Select a line to Edit'
            );
            
        $trans = $this->cdbhelper->retTranslationDifKeys($trans);



        $send = array("javascript" => $javascript,
            "filters" => $filters,
            "filters_java" => $fm->retJavascript()) + $trans;


        $this->load->view("tr/test_request_view", $send);
    }

    public function callTRForm($code) {

        $grid = $this->w2gridgen;
        $f = $this->cfields;
        $fm = $this->cfiltermaker;

        if (1 == 2) {
            $f = new Cfields();
            $grid = new w2gridgen();
            $fm = new cfiltermaker();
        }

        
        if ($code ==  -1) {
            $sc = "Y";
            $line = $this->mainmodel->retRetrieveEmptyNewArray();
            // set the requester as the logged user
            $line[0]['cd_human_resource_request'] = $this->session->userdata('cd_human_resource');
            $line[0]['ds_human_resource_request'] = $this->session->userdata('ds_human_resource_full');
            $line[0]['ds_project_model'] = '';
            $line[0]['fl_by_model']      = 0;
            
            $action = 'I';
            
        } else {
            $sc = "N";
            $line = $this->mainmodel->retRetrieveArray(' WHERE "TEST_REQUEST".cd_test_request = ' . $code );
            $action = 'E';
        }
        
        // creating toolbar;
        $grid->addCRUDToolbar(false, false, true, false, false);
        $grid->setGridVar('vGridToToolbar');
        $grid->setForceDestroy(false);
        $toolbar = $grid->retGridVar();
        
        $trans = array(
            'formTrans_cd_test_request' => 'Code',
            'formTrans_cd_project' => 'Project',
            'formTrans_cd_project_model' => 'Model',
            'formTrans_cd_project_build' => 'Build',
            'formTrans_cd_test_request_type' => 'Type',
            'formTrans_cd_test_request_purpose' => 'Purpose',
            'formTrans_cd_test_request_origin' => 'Origin',
            'formTrans_ds_description' => 'Description',
            'formTrans_fl_return_sample' => 'Return Sample',
            'formTrans_fl_urgent' => 'Urgent',
            'formTrans_cd_human_resource_request' => 'Requester',
            'formTrans_dt_approved' => 'Approved',
            'formTrans_cd_human_resource_approver' => 'By',
            'formTrans_nr_version'=> 'Version',
            'formTrans_ds_tti_project'=> 'TTi #',
            'formTrans_ds_met_project'=> 'MET #',
            'formTrans_ds_tti_project_model'=> 'TTi Model#',
            'formTrans_ds_met_project_model'=> 'MET Model#',
            'errorNotFound' => 'Project / Model Not Found'
            
            
            
        );
        
        $this->load->view("tr/test_request_form_view", $trans + $line[0] + array ('sc' => $sc, 'toolbar' => $toolbar, 'action' => $action));
        
    }

    public function getLastVersion ($cd_project, $cd_project_model, $cd_project_build) {
        $last = $this->mainmodel->getLastVersion ($cd_project, $cd_project_model, $cd_project_build);
        
        $array = array('next' => $last + 1);
        
        echo (json_encode($array, JSON_NUMERIC_CHECK));
    
    }
    

        //var updFunction = 'updateDataJsonForm';
    //var retFunction = 'retrieveGridJsonForm';

}
