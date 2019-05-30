<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class supplier extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("rfq/supplier_model", "mainmodel", TRUE);
    }

    public function index() {

        parent::checkMenuPermission();


        //$this->downloadCoordinates();


        $grid = $this->w2gridgen;
        $f = $this->cfields;
        $fm = $this->cfiltermaker;

        if (1 == 2) {
            $f = new Cfields();
            $grid = new w2gridgen();
            $fm = new cfiltermaker();
        }

        $fm = $this->cfiltermaker;

        $fm->addSimpleFilterUpper('Supplier', 'filter_1', '"SUPPLIER".ds_supplier');
        $fm->addSimpleFilterUpper('Address', 'filter_2', '"SUPPLIER".ds_address');
        $fm->addSimpleFilterUpper('Email', 'filter_3', '"SUPPLIER".ds_email');
        $fm->addSimpleFilterUpper('Contact Name', 'filter_4', '"SUPPLIER".ds_contact_name');
        $fm->addPickListFilter('Country', 'filter_5', 'country', '"SUPPLIER".cd_country');
        $fm->addSimpleFilterUpper('Phone Number', 'filter_6', '"SUPPLIER".ds_phone_number');
        $fm->addSimpleFilterUpper('Website', 'filter_7', '"SUPPLIER".ds_website');
        $fm->addSimpleFilterUpper('Vendor Code', 'filter_8', '"SUPPLIER".ds_vendor_code');
        $fm->addFilterYesNo("Active", "dt_deactivated", "", "Y");


        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("rfq/supplier");

        $grid->addColumnKey();
        $grid->addColumn('fl_tti_supplier', 'TTI Vendor', '100px', $f->retTypeCheckBox(), true);
        $grid->addColumn('ds_vendor_code', 'Vendor Code', '80px', $f->retTypeStringAny(), true);
        $grid->addColumn('ds_supplier', 'Supplier', '200px', $f->retTypeStringAny(), array('limit' => ''));
        $grid->addColumn('ds_supplier_alt', 'Supplier Alt', '200px', $f->retTypeStringAny(), array('limit' => ''));
        $grid->addColumn('nr_tax_default', 'Tax Default', '100px', $f->retTypeNum(), array('precision' => '0', 'readonly' => false));
        $grid->addColumn('ds_address', 'Address', '200px', $f->retTypeTextPL(), array('limit' => ''));
        $grid->addColumn('ds_email', 'Email', '150px', $f->retTypeTextPL(), array('limit' => ''));
        $grid->addColumn('ds_contact_name', 'Contact Name', '150px', $f->retTypeStringAny(), array('limit' => ''));
        $grid->addColumn('ds_country', 'Country', '150px', $f->retTypePickList(), array('model' => 'country_model', 'codeField' => 'cd_country'));
        $grid->addColumn('ds_phone_number', 'Phone Number', '150px', $f->retTypeStringAny(), array('limit' => ''));
        $grid->addColumn('ds_website', 'Website', '150px', $f->retTypeStringAny(), array('limit' => ''));


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

    function openPL() {

        $controller = 'rfq/supplier';

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
        //$fm->addPickListFilter('Quotation Type', 'pl_filter_17', 'material/product_quotation_type', '"SHOE_COST_SHEET_SKU".cd_product_quotation_type');

        $filters = $fm->retFiltersWithGroup();

        $grid->setGridToolbarFunction('onGridToolbarPressedPL');
        //$grid->addUserBtnToolbar('clear', 'Clear', 'fa fa-times-circle-o', 'Clear');

        $grid->setGridVar('varMySpecificPL');
        $grid->setGridName('specificPLSup');
        $grid->setCRUDController($controller);
        $grid->setSingleBarControl(true);

        //$grid->addRetriveToolbar();
        //$grid->addBreakToolbar();

        if ($controller !== '') {
            $grid->addUserBtnToolbar('openMaint', 'Open Maintenance', 'fa fa-external-link');
        }
        $grid->addBreakToolbar();



        $grid->setToolbarSearch(true);

        $grid->addColumnKey();

        $grid->addColumn('ds_supplier_full', 'Description', '100%', $f->retTypeStringAny(), false);

        $grid->addColumn('ds_vendor_code', 'Vendor Code', '120px', $f->retTypeStringAny(), false);


        $data = $this->mainmodel->retRetrieveGridJson(" WHERE \"SUPPLIER\".dt_deactivated IS NULL ");

        $grid->addRecords($data);
        $javascript = $grid->retGridVar();

        $labels = array('title' => 'Supplier');
        $labels = $this->cdbhelper->retTranslationDifKeys($labels);

        $send = array("javascript" => $javascript,
            "filters" => $filters,
            "filters_java" => $fm->retJavascript(),
            "keyColumn" => 'cd_supplier',
            "descColumn" => 'ds_supplier_full',
            'retrieveFields' => json_encode($fm->getFilterNames()),
            'controller' => $controller
        );


        $this->load->view("rfq/supplier_pl_view", $send + $labels);
    }

    public function downloadCoordinates() {

        $data = $this->mainmodel->retRetrieveGridArray(' WHERE ds_address IS NOT NULL and nr_latitude IS NULL');
        $toins = array();

        foreach ($data as $key => $value) {
            
            if ($key == 10) {
                break;
            }
            
            
            
            $address = urlencode($value['ds_address']);
            $url = "http://maps.googleapis.com/maps/api/geocode/json?address=$address&sensor=false";

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_PROXY, '10.64.16.212');
            curl_setopt($ch, CURLOPT_PROXYPORT, '8080');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

// The username and password
            curl_setopt($ch, CURLOPT_PROXYUSERPWD, 'Carlos.Blos:password');

// Perform the request, and save content to $result
            $result = curl_exec($ch);
            $result = json_decode($result, true);

            if ($result["status"] != "OK")  {
                die (print_r($result));
            }
            
            if (isset($result["results"][0])) {

                $LatLng = array('recid' => $value['recid'], 'nr_latitude' => $result["results"][0]["geometry"]["location"]["lat"], 'nr_longitude' => $result["results"][0]["geometry"]["location"]["lng"], 'ds_address' => $value['ds_address']);
                array_push($toins, $LatLng);
            }
        }
        
        $this->mainmodel->updateGridData($toins);
        

        
    }

}
