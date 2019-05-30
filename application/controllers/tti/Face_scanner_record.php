<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class face_scanner_record extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("tti/face_scanner_record_model", "mainmodel", TRUE);
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

        $fm->addFilterNumber('Staff Number', 'filter_7', '"FACE_SCANNER_RECORD".nr_staff_number', '10.0', 0, '');
        $fm->addSimpleFilterUpper('Staff Name', 'filter_2', '( COALESCE 
                ( 
                   ( SELECT ds_human_resource_full from "HUMAN_RESOURCE" where nr_staff_number = "FACE_SCANNER_RECORD".nr_staff_number), 
                  \'*\' || ( SELECT max(ds_staff_name) from "HR_ATTENDANCE_BASE" where nr_staff_number = "FACE_SCANNER_RECORD".nr_staff_number), 
                   \'*\' || "FACE_SCANNER_RECORD".nr_staff_number::text
                 )
              )');
        $fm->addSimpleFilterUpper('Department', 'filter_3', '"FACE_SCANNER_RECORD".ds_department');
        $fm->addFilterDate ('Attend', 'filter_6', '"FACE_SCANNER_RECORD".dt_attend_date', date('m/01/Y'), date('m/t/Y'));
        //$fm->addFilterDate ('Attend Time', 'filter_8', '"FACE_SCANNER_RECORD".dt_attend_time');
        $fm->addFilterNumber('Equipment Number', 'filter_8', '"FACE_SCANNER_RECORD".nr_equipment_number', '10.0', 0, '');

        

        
        

        
        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar(); 
        
        $grid->addUserBtnToolbar("importFID", "Fecth from Face ID", "fa fa-cloud-download");
        
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("tti/face_scanner_record");

        $grid->addColumnKey();

        $grid->addColumn('nr_equipment_number', 'Equipment Number', '150px', $f->retTypeInteger(), false);
        $grid->addColumn('ds_staff_number', 'Staff Number', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_human_resource_full', 'Staff Name', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_department', 'Location', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('dt_attend_date', 'Attend', '150px', $f->retTypeDate(), false);
        //$grid->addColumn('dt_attend_time', 'Attend Time', '150px', $f->retTypeDate(), true);


        $filters = $fm->retFiltersWithGroup();
        $javascript = $grid->retGrid();


        $trans = array();
        $trans = $this->cdbhelper->retTranslationDifKeys($trans);


        $send = array("javascript" => $javascript,
            "filters" => $filters,
            "filters_java" => $fm->retJavascript()) + $trans;


        $this->load->view("tti/face_scanner_record_view", $send);
    }
    
    
    // create by ken.jin 2018.10.30
    public function importFID() {

        $ret = $this->mainmodel->importFID();
        echo(json_encode($ret));
        return;
    }

}
