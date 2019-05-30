<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class department_account_code extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("rfq/department_account_code_model", "mainmodel", TRUE);
        $this->load->model('rfq/department_account_code_cost_center_model', 'accmodel');
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
        $fm->addSimpleFilterUpper('Description', 'filter_2', '"DEPARTMENT_ACCOUNT_CODE".ds_department_account_code');
        $fm->addPickListFilter('Expense Type', 'filter_5', 'rfq/expense_type', '"DEPARTMENT_ACCOUNT_CODE".cd_expense_type');
        $fm->addFilterYesNo("Active", "dt_deactivated", "", "Y");
        $filters = $fm->retFiltersWithGroup();

        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("rfq/department_account_code");

        $grid->addColumnKey();

        $grid->addColumn('ds_department_account_code_for_workflow', 'Account Code', '100px', $f->retTypeStringAny(), array('limit' => ''));
        $grid->addColumn('ds_department_account_code', 'Description', '100%', $f->retTypeStringAny(), array('limit' => ''));
        $grid->addColumn('ds_expense_type', 'Expense Type', '150px', $f->retTypePickList(), array('model' => 'rfq/expense_type_model', 'codeField' => 'cd_expense_type'));

        $grid->addColumnDeactivated(true);
        $javascript = $grid->retGrid();

        // account relation
        $grid->resetGrid();
        $grid->setToolbarPrefix('costdep');
        $grid->setHeader('Cost Department');
        $grid->setGridName('gridCostCenter');
        $grid->setGridDivName('gridCostCenterDiv');
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar(false, true, false, true, false);
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("rfq/department_account_code_cost_center");

        $grid->addColumnKey();

        $grid->addColumn('ds_department_cost_center', 'Cost Center', '100%', $f->retTypePickList(), array('model' => 'rfq/department_cost_center_model', 'codeField' => 'cd_department_cost_center'));

        $javascript = $javascript  . $grid->retGrid();

        $trans = array();
        $trans = $this->cdbhelper->retTranslationDifKeys($trans);



        $send = array("javascript" => $javascript,
            "filters" => $filters,
            "filters_java" => $fm->retJavascript()) + $trans;


        $this->load->view("rfq/department_account_code_view", $send);
    }

    
    // overriding controller update because I have to update two tables.
    public function updateDataJson()
    {

        $msg = '';

        $upd_array = json_decode($_POST['upd']);
        $retResultset = 'N';

        if (isset($_POST['retResultSet'])) {
            $retResultset = $_POST['retResultSet'];
        }
        $jsonMapping = '';


        $this->cdbhelper->trans_begin();


        $error = $this->mainmodel->updateGridData($upd_array);
        if ($error != 'OK') {
            $msg = '{"status":' . json_encode($error) . ', "rs":{}}';
            $this->cdbhelper->trans_end();
            echo $msg;
            return;
        }

        // tax
        $error = $this->accmodel->updateGridDataFromField('ds_department_cost_json', $upd_array);
        if ($error != 'OK') {
            $msg = '{"status":' . json_encode($error) . ', "rs":{}}';
            $this->cdbhelper->trans_end();
            echo $msg;
            return;
        }

        $this->cdbhelper->trans_commit();
        $this->cdbhelper->trans_end();

        $msg = '{"status":' . json_encode($error);


        // get the data from DB  of the updated records.
        $retResult = '{}';

        if ($retResultset == 'Y' && $error == 'OK') {
            $neg = $this->mainmodel->getNewRecIdsNegative();
            $x = implode(',', $neg);

            $where = ' where ' . $this->mainmodel->pk_field . ' in (';
            foreach ($upd_array as $value) {
                $where = $where . $value->recid . ',';
            }
            if ($x != '') {
                $where = $where . $x . ', ';
            }
            $where = $where . '-1 )';


            $retResult = $this->mainmodel->retRetrieveGridJson($where, '', $jsonMapping);

            $msg = $msg . ', "rs": ' . $retResult;

            if (count($neg) > 0) {
                $msg = $msg . ', "negRS": ' . json_encode($neg);
            }
        }

        $msg = $msg . '}';

        //

        echo $msg;
    }
    
}
