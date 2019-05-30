<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class project_model extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("tti/project_model_model", "mainmodel", TRUE);
        $this->load->model("rfq/general_project_expense_model", "expensemodel", TRUE);
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

//        $fm->addSimpleFilterUpper('Project Model', 'filter_1', '"PROJECT_MODEL".ds_project_model');
//        $fm->addPickListFilter('Project', 'filter_2', 'tti/project', '"PROJECT_MODEL".cd_project');



        $this->setGridParser();
        $grid->setSingleBarControl(false);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("tti/project_model");

        $grid->addColumnKey();

//        $grid->addColumn('ds_project_model', 'Project Model', '150px', $f->retTypeStringUpper(), array('limit' => '64'));
//        $grid->addColumn('ds_project', 'Project', '150px', $f->retTypePickList(), array('model' => 'tti/project_model', 'codeField' => 'cd_project'));
//        $grid->addColumn('ds_tti_project_model', 'Tti Project Model', '150px', $f->retTypeStringUpper(), true);
//        $grid->addColumn('nr_met_project_model', 'Met Project Model', '150px', $f->retTypeInteger(), true);

        

        $filters = $fm->retFiltersWithGroup();
        $javascript = $grid->retGrid();


        $trans = array();
        $trans = $this->cdbhelper->retTranslationDifKeys($trans);



        $send = array("javascript" => $javascript,
            "filters" => $filters,
            "filters_java" => $fm->retJavascript()) + $trans;


        $this->load->view("defaultView", $send);
    }


    function openPL($cdprojectmodel,$cdexpense) {

        $id = $_POST['id'];

        $controller = 'tti/project_model';

        if ($this->cdbhelper->checkMenuRights($controller) != 'Y') {
            $controller = '';
        }

        $grid = $this->w2gridgen;
        $f = $this->cfields;
        $fm = $this->cfiltermaker;

        if (1 == 2) {
            $f = new Cfields();
            $grid = new w2gridgen();
            $fm = new cfiltermaker();
        }

        $fm = $this->cfiltermaker;
        $fm->setColumnBig();

        $filters = $fm->retFiltersWithGroup();

        $grid->setGridToolbarFunction('onGridToolbarPressedPL');

        $grid->setGridVar('varMySpecificPL');
        $grid->setGridName('specificPLSup');
        $grid->setCRUDController($controller);
        $grid->setSingleBarControl(false);


        if($cdprojectmodel==0)
        {
            $grid->addUserCheckToolbar ('useprojectmodel', 'Use Project Model','Use Project Model' , false);
        }
        else
        {
            $grid->addUserCheckToolbar ('useprojectmodel', 'Use Project Model','Use Project Model' , true);
        }

        if($cdexpense==0) {
            $grid->addUserCheckToolbar('useexpense', 'Use General Expense', 'Use General Expense', false);
        }
        else
        {
            $grid->addUserCheckToolbar('useexpense', 'Use General Expense', 'Use General Expense', true);
        }

        $grid->setToolbarSearch(true);

        $grid->addColumnKey();


        $grid->addColumn('ds_tti_project', 'Project TTi#', '30%', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_tti_project_model', 'Model TTi#', '30%', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_project_full_desc', 'Description', '40%', $f->retTypeStringAny(), false);
        $sql  =" WHERE ds_tti_project IS NOT NULL AND ds_tti_project_model IS NOT NULL " ;
        $data = $this->mainmodel->retRetrieveGridJson($sql, "ORDER BY ds_project_full_desc", "", $this->mainmodel->retrOptionsPLPR);
        $grid->addRecords($data);
        $javascript = $grid->retGridVar();
        $grid->resetGrid();
//---------------------------------------------------------------------------------------------------------------------------------------------------------------------

        $id = $_POST['id'];

        $controller = 'tti/general_project_expense_model';

        if ($this->cdbhelper->checkMenuRights($controller) != 'Y') {
            $controller = '';
        }

        $grid = $this->w2gridgen;
        $f = $this->cfields;
        $fm = $this->cfiltermaker;

        if (1 == 2) {
            $f = new Cfields();
            $grid = new w2gridgen();
            $fm = new cfiltermaker();
        }

        $fm = $this->cfiltermaker;
        $fm->setColumnBig();
        $filters = $fm->retFiltersWithGroup();
        $grid->setGridToolbarFunction('onGridToolbarPressedPL');
        $grid->setGridVar('varMySpecificExpense');
        $grid->setGridName('specificExpense');
        $grid->setGridDivName('myExpenseGridx');
        $grid->setCRUDController($controller);
        $grid->setSingleBarControl(false);

        $grid->addColumnKey();

        $grid->addColumn('ds_general_project_expense', 'Description', '30%', $f->retTypeStringAny(), false);
        $grid->addColumn('nr_balance', 'Balance USD', '40%', $f->retTypeNum(), array('precision' => '2', 'readonly' => true));
        $grid->addColumn('ds_general_project_number', 'General Project Number', '40%', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_general_project_model_number', 'General Project Model Number', '40%', $f->retTypeStringAny(), false);
        $grid->addColumn('dt_amount_raised', 'Amount Raised', '40%', $f->retTypeStringAny(), false);
        $sql  =" WHERE dt_deactivated IS NULL " ;
        $data = $this->expensemodel->retRetrieveGridJson($sql, "ORDER BY dt_record desc", "");

        $grid->addRecords($data);
        $javascript = $javascript . $grid->retGridVar();

        $labels = array('title' => 'Project/Model');
        $labels = $this->cdbhelper->retTranslationDifKeys($labels);

        $send = array("javascript" => $javascript,
            "filters" => $filters,
            "filters_java" => $fm->retJavascript(),
            "cd_project_model" => $cdprojectmodel,
            "cd_general_project_expense" => $cdexpense,
//            "descColumn" => 'ds_project_model',
            'retrieveFields' => json_encode($fm->getFilterNames()),
            'controller' => $controller,
            'id' => $id
        );

        $this->load->view("rfq/project_department_pl_view", $send + $labels);
    }    

}
