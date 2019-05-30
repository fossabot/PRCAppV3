<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class approval_steps_config extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("approval_steps_config_model", "mainmodel", TRUE);
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

        $fm->addSimpleFilterUpper('Approval Steps Config', 'filter_1', '"APPROVAL_STEPS_CONFIG".ds_approval_steps_config');
        $fm->addSimpleFilterUpper('System Permission Ids', 'filter_2', '"APPROVAL_STEPS_CONFIG".ds_system_permission_ids');
        $fm->addSimpleFilterUpper('Instructions', 'filter_7', '"APPROVAL_STEPS_CONFIG".ds_instructions');
        $fm->addSimpleFilterUpper('System Permission Ids Send Mail', 'filter_8', '"APPROVAL_STEPS_CONFIG".ds_system_permission_ids_send_mail');
        $fm->addSimpleFilterUpper('Internal Code', 'filter_9', '"APPROVAL_STEPS_CONFIG".ds_internal_code');
        $fm->addSimpleFilterUpper('Approval Steps Config Type', 'filter_10', '"APPROVAL_STEPS_CONFIG".ds_approval_steps_config_type');
        $fm->addFilterYesNo("Active", "dt_deactivated", "", "Y");



        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("approval_steps_config");

        $grid->addColumnKey();

        $grid->addColumn('ds_approval_steps_config', 'Approval Steps Config', '150px', $f->retTypeStringAny(), array('limit' => '64'));
        $grid->addColumn('ds_system_permission_ids', 'System Permission Ids', '150px', $f->retTypeStringAny(), array('limit' => ''));
        $grid->addColumn('nr_order', 'Order', '150px', $f->retTypeInteger(), true);
        $grid->addColumn('fl_send_mail', 'Send Mail', '150px', $f->retTypeCheckBox(), true);
        $grid->addColumn('ds_instructions', 'Instructions', '150px', $f->retTypeStringAny(), array('limit' => ''));
        $grid->addColumn('ds_system_permission_ids_send_mail', 'System Permission Ids Send Mail', '150px', $f->retTypeStringAny(), array('limit' => ''));
        $grid->addColumn('ds_internal_code', 'Internal Code', '150px', $f->retTypeStringAny(), array('limit' => ''));
        $grid->addColumn('ds_approval_steps_config_type', 'Approval Steps Config Type', '150px', $f->retTypeStringAny(), array('limit' => ''));
        $grid->addColumn('fl_approval_all', 'Approval All', '150px', $f->retTypeCheckBox(), true);
        $grid->addColumn('fl_show_only_if_has_rights', 'Show Only If Has Rights', '150px', $f->retTypeCheckBox(), true);
        $grid->addColumn('fl_show_approve', 'Show Approve', '150px', $f->retTypeCheckBox(), true);
        $grid->addColumn('fl_show_reject', 'Show Reject', '150px', $f->retTypeCheckBox(), true);
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
    
    
    public function openHistory($type, $code) {
        
        $grid = $this->w2gridgen;
        $f = $this->cfields;
        $fm = $this->cfiltermaker;

        if (1 == 2) {
            $f = new Cfields();
            $grid = new w2gridgen();
            $fm = new cfiltermaker();
        }
        
        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar(false, false, false, false, false);
        $grid->addSpacerToolbar();
        $grid->addExportToolbar();
        $grid->setToolbarSearch(true);

        $grid->addColumnKey();

        $grid->addColumn('ds_approval_steps_config', 'Step', '200px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_approval_status', 'Status', '100px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_human_resource_define', 'By', '100px', $f->retTypeStringAny(), false);
        $grid->addColumn('dt_define', 'Date', '120px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_remakrs', 'Remarks', '100%', $f->retTypeStringAny(), false);
        
        $grid->addRecords($this->mainmodel->returnHistory($type, $code));
        
        $grid->setGridDivName('gridHistoryDiv');
        $grid->setGridName('gridHistory');
        
        
        
        $javascript = $grid->retGrid();
        $send = array("javascript" => $javascript);
        $this->load->view("approval_steps_config_history_view", $send);
        
        

    }
    
    
    

}
