<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class assets extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("assets/assets_model", "mainmodel", TRUE);

        $this->load->model("assets/assets_changes_model", "changemodel", TRUE);
    }

    public function index() {

        parent::checkMenuPermission();
/*
        require_once APPPATH . 'elasticsearch/vendor/autoload.php';
        $hosts = ['http://10.64.120.187:9200'];

        $client = Elasticsearch\ClientBuilder::create()           // Instantiate a new ClientBuilder
                ->setHosts($hosts)      // Set the hosts
                ->build();              // Build the client object
        //$params = ['index' => 'lmsdeptest'];
        //$response = $client->indices()->delete($params);
        //die ('done remove;');
        ini_set('memory_limit', '-1');
        
        $ret = $this->getCdbhelper()->basicSQLJson('select ds_asset as "Asset", 
       nr_month as "Month Ref",
       nr_year as "Year Ref",
       nr_months_depreciation as "Month Depreciation Already",
       to_char(dt_ref, \'YYYY-MM-DD\') as "Reference Date",
       to_char(dt_start, \'YYYY-MM-DD\') as "Start Depreciation",
       to_char(dt_end, \'YYYY-MM-DD\') as "End Depreciation",
       ds_category as "Category",
       nr_value_month_depreciation as "Monthly Depreciation", 
       nr_value_initial as "Initial Value",
       nr_value_month_balance as "Month Balance for the Asset",
       ds_deparment as "Department", 
       nr_years_depreciation as "Total Years Depreciation"
 from assets.getDepretiation() order by nr_year, nr_month, ds_asset', true);
        
        $ret = json_decode($ret, true);
        
        

        $newArray = array('body' => array());
        foreach ($ret as $key => $value) {
            array_push($newArray['body'], array("index" => array("_index" => "lmsdep400test", "_type" => "dep400test", "_id" => $key )));
            array_push($newArray['body'], $value);
        }

        $responses = $client->bulk($newArray);

        die (print_r($responses));
        
        $this->load->library('ldaphelper');

        if (!$this->ldaphelper->connect(2)) {
            die($this->ldaphelper->errormsg);
        }
        
        $user = $this->ldaphelper->searchUsers();

        die ('connect ' . print_r($user));
*/
        
        $grid = $this->w2gridgen;
        $f = $this->cfields;
        $fm = $this->cfiltermaker;

        if (1 == 2) {
            $f = new Cfields();
            $grid = new w2gridgen();
            $fm = new cfiltermaker();
        }

        $fm = $this->cfiltermaker;

        $fm->addSimpleFilterUpper('Assets', 'filter_1', '"ASSETS".ds_assets');
        $fm->addSimpleFilterUpper('Assets #', 'filter_2', '"ASSETS".ds_assets_number');
        $fm->addPickListFilter('Book', 'filter_3', 'assets/assets_book', '"ASSETS".cd_assets_book');
        $fm->addSimpleFilterUpper('Contract Number', 'filter_5', '"ASSETS".ds_pr_contract_number');
        $fm->addPickListFilter('Room', 'filter_6', 'assets/assets_location_room', '"ASSETS".cd_assets_location_room');
        $fm->addSimpleFilterUpper('Department Ref Number', 'filter_7', '"ASSETS".ds_department_ref_number');
        $fm->addPickListFilter('Department', 'filter_9', 'rfq/department_cost_center', '"ASSETS".cd_department_cost_center');
        $fm->addPickListFilter('Responsible', 'filter_10', 'human_resource', '"ASSETS".cd_human_resource_responsible');
        $fm->addSimpleFilterUpper('Assets Number Old', 'filter_11', '"ASSETS".ds_assets_number_old');
        //$fm->addSimpleFilterUpper('Remarks', 'filter_12', '"ASSETS".ds_remarks');



        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("assets/assets");

        $grid->addColumnKey();


        $grid->addColumn('dt_asset', 'On', '80px', $f->retTypeDate(), true);
        $grid->addColumn('ds_assets', 'Asset', '150px', $f->retTypeStringAny(), array('limit' => ''));
        $grid->addColumn('ds_assets_number', 'Asset #', '150px', $f->retTypeStringAny(), array('limit' => ''));
        $grid->addColumn('ds_assets_book', 'Book', '150px', $f->retTypePickList(), array('model' => 'assets/assets_book_model', 'codeField' => 'cd_assets_book'));

        $grid->addColumn('ds_pr_contract_number', 'PR/Contract #', '150px', $f->retTypeStringAny(), array('limit' => ''));
        $grid->addColumn('ds_assets_location_room', 'Room', '150px', $f->retTypePickList(), array('model' => 'assets/assets_location_room_model', 'codeField' => 'cd_assets_location_room'));
        $grid->addColumn('ds_department_ref_number', 'Department Ref Number', '150px', $f->retTypeStringAny(), array('limit' => ''));
        $grid->addColumn('nr_qty', 'Qty', '80px', $f->retTypeInteger(), true);
        $grid->addColumn('ds_department_cost_center', 'Department', '100px', $f->retTypePickList(), array('model' => 'rfq/department_cost_center_model', 'codeField' => 'cd_department_cost_center'));
        $grid->addColumn('ds_human_resource_responsible', 'Responsible', '100px', $f->retTypePickList(), array('model' => 'human_resource_model', 'codeField' => 'cd_human_resource_responsible'));
        $grid->addColumn('ds_assets_number_old', 'Assets Number Old', '150px', $f->retTypeStringAny(), true);
        $grid->addColumn('ds_remarks', 'Remarks', '150px', $f->retTypeTextPL(), true);
        if( $this->getCdbhelper()->getUserPermission('fl_asset_depreciation_manager')=='Y') {
            $grid->addColumn('nr_initial_value', 'Initial Value', '150px', $f->retTypeNum(), array('precision' => '2', 'readonly' => false));
            $grid->addColumn('nr_monthly_depreciation', 'Monthly Depreciation', '150px', $f->retTypeNum(), array('precision' => '2', 'readonly' => false));
            $grid->addColumn('dt_start_monthly_depreciation', 'Start Monthly Depreciation', '80px', $f->retTypeDate(), true);
        }
        
        $grid->addColumn('dt_asset_scrap', 'Scrap On', '80px', $f->retTypeDate(), true );
        if( $this->getCdbhelper()->getUserPermission('fl_asset_depreciation_manager')=='Y') {
            $grid->addColumn('nr_scrap_value', 'Value when Scrap', '150px', $f->retTypeNum(), array('precision' => '2', 'readonly' => false));
        }

        $javascript = $grid->retGrid();
        $grid->resetGrid();

        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar(false, false, false, false, false);
        $grid->addExportToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("assets/assets_changes");

        $grid->addColumnKey();

        $grid->addHiddenColumn('ds_assets', 'Assets', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_human_resource', 'Changed By', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('dt_record', 'Changed At', '80px', $f->retTypeDate(), false);

        $grid->addColumn('ds_assets_location_room', 'Room', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_department_ref_number', 'Department Ref Number', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_human_resource_responsible', 'Responsible', '150px', false);
        $grid->addColumn('ds_assets_number_old', 'Assets Number Old', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_remarks', 'Remarks', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_department_cost_center', 'Department Cost Center', '150px', $f->retTypeStringAny(), false);

        $grid->setGridName('gridChanges');
        $grid->setGridDivName('gridDiv');

        $javascript = $javascript . $grid->retGrid();
        $filters = $fm->retFiltersWithGroup();


        $trans = array();
        $trans = $this->cdbhelper->retTranslationDifKeys($trans);



        $send = array("javascript" => $javascript,
            "filters" => $filters,
            "filters_java" => $fm->retJavascript()) + $trans;


        $this->load->view("assets/assets_view", $send);
    }

    public function openChange() {

        $grid = $this->w2gridgen;
        $f = $this->cfields;
        $fm = $this->cfiltermaker;

        if (1 == 2) {
            $f = new Cfields();
            $grid = new w2gridgen();
            $fm = new cfiltermaker();
        }


        $trans = array(
            'formTrans_cd_assets' => 'Code',
            'formTrans_ds_assets' => 'Assets',
            'formTrans_ds_assets_number' => 'Assets #',
            'formTrans_cd_assets_book' => 'Book',
            'formTrans_dt_asset' => 'Asset Date',
            'formTrans_ds_pr_contract_number' => 'PR/Contract #',
            'formTrans_cd_assets_location_room' => 'Room',
            'formTrans_ds_department_ref_number' => 'Department Ref Number',
            'formTrans_nr_qty' => 'Qty',
            'formTrans_cd_department_cost_center' => 'Department',
            'formTrans_cd_human_resource_responsible' => 'Responsible',
            'formTrans_ds_assets_number_old' => 'Assets Number Old',
            'formTrans_ds_remarks' => 'Remarks',
            'detailsLabel' => 'Details',
            'revisionLabel' => 'Revision',
            'historyLabel' => 'History',
            'formTrans_nr_initial_value'=> 'Initial Value',
            'formTrans_nr_monthly_depreciation'=> 'Monthly Depreciation',
            'formTrans_dt_start_monthly_depreciation'=> 'Start Monthly Depreciation',
            'formTrans_ds_category'=> 'Category',
            'formTrans_dt_end_monthly_depreciation'=> 'End Monthly Depreciation',
            'formTrans_dt_asset_scrap'=> 'Asset Scrap',
            'formTrans_nr_scrap_value'=> 'Scrap Value',
        );

        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar(false, false, false, false, false);
        $grid->addExportToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("assets/assets_changes");

        $grid->addColumnKey();

        $grid->addHiddenColumn('ds_assets', 'Assets', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_human_resource', 'Changed By', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('dt_record', 'Changed At', '80px', $f->retTypeDate(), false);

        $grid->addColumn('ds_assets_location_room', 'Room', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_department_ref_number', 'Department Ref Number', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_human_resource_responsible', 'Responsible', '150px', false);
        $grid->addColumn('ds_assets_number_old', 'Assets Number Old', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_remarks', 'Remarks', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_department_cost_center', 'Department Cost Center', '150px', $f->retTypeStringAny(), false);

        $grid->setGridName('gridChanges');
        $grid->setGridDivName('gridDiv');

        $javascript = $grid->retGrid();


        $trans = $this->cdbhelper->retTranslationDifKeys($trans);

        $this->load->view("assets/assets_change_view", $trans + array('grid' => $javascript));
    }

    public function getByNumber() {
        //die (print_r($_POST));
        $number = $_POST['number'];
        $number = $this->getCdbhelper()->normalizeDataToSQL('char', $number);



        $retResult = $this->mainmodel->retRetrieveGridArray(" WHERE ds_assets_number = $number ");

        if (count($retResult) == 0) {
            die('Not Found');
        }

        $la = $retResult[0]['recid'];
        $x = $this->changemodel->retRetrieveGridJson(" WHERE cd_assets = $la ");

        $msg = '{"status": "OK", "rs":' . json_encode($retResult[0], JSON_NUMERIC_CHECK) . ', "hist": ' . $x . ' }';

        echo($msg);
    }

}
