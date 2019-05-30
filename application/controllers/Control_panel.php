<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

include_once APPPATH . 'controllers/controllerBasicExtend.php';

class control_panel extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
    }

    public function index() {

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
        $ctabs->setContentDivId('tab_parameters_div');
        $ctabs->addTab('Parameters', 'tab_parameters');
        $ctabs->makeContentDiv();
        $tabsc = $ctabs->retTabs();



        $grid->resetGrid();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar(true, false, true, false, true);
        $grid->setToolbarSearch(true);
        $grid->setCRUDController('system_parameters');

        $grid->addColumnKey();

        $grid->setGridDivName('tab_parameters_div');
        $grid->setGridName('parameterGrid');
        $grid->addColumn('ds_system_parameters', 'Parameters', '300px', $f->retTypeStringUpper(), false);
        $grid->addColumn('ds_system_parameters_id', 'Id', '150px', $f->retTypeStringUpper(), false);
        $grid->addColumn('ds_system_parameters_obs', 'Obs', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_system_parameters_value', 'Value', '300px', $f->retTypeStringAny(), array('limit' => '64'));
       
        $javascript =  $grid->retGrid();


        $send = array("javascript" => $javascript,
            "filters" => '',
            "filters_java" => $fm->retJavascript(),
            "tab" => $tabsc);


        $this->load->view("control_panel_view", $send);
    }
    
   

}

?>