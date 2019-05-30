<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class hr_attendance_base extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("tti/hr_attendance_base_model", "mainmodel", TRUE);
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

        $fm->addFilterNumber('Staff Number', 'filter_7', '"HR_ATTENDANCE_BASE".nr_staff_number', '10.0', 0, '');
        $fm->addSimpleFilterUpper('Staff Name', 'filter_2', '"HR_ATTENDANCE_BASE".ds_staff_name');
        $fm->addSimpleFilterUpper('Department', 'filter_3', '"HR_ATTENDANCE_BASE".ds_department');
        $fm->addSimpleFilterUpper('Shift', 'filter_9', '"HR_ATTENDANCE_BASE".ds_shift');
        $fm->addSimpleFilterUpper('Abnormal Reason', 'filter_5', '"HR_ATTENDANCE_BASE".ds_abnormal_reason');
        $fm->addFilterDate ('Attend Date', 'filter_6', '"HR_ATTENDANCE_BASE".dt_attend_date', date('m/d/Y', strtotime('-7 days')), date('m/d/Y') );
        $fm->addFilterDate ('Join Date', 'filter_8', '"HR_ATTENDANCE_BASE".dt_join_date');
        $fm->addSimpleFilterUpper('FaceID Reason', 'filter_10', '"HR_ATTENDANCE_BASE".ds_faceid_reason');
        


        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->addUserBtnToolbar('upload', 'Upload from Kronos', 'fa fa-upload');
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("tti/hr_attendance_base");

        $grid->addColumnKey();

        $grid->addColumn('ds_staff_number', 'Staff Number', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_staff_name', 'Staff Name', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_department', 'Department', '120px', $f->retTypeStringAny(), false);
        $grid->addColumn('dt_attend_date', 'AttendDate', '120px', $f->retTypeDate(), false);
        $grid->addColumn('ds_shift', 'Shift', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_abnormal_reason', 'Abnormal Reason', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('dt_start_one', 'Start One', '150px', $f->retTypeDate(), false);
        $grid->addColumn('dt_end_one', 'End One', '150px', $f->retTypeDate(), false);
        $grid->addColumn('dt_start_two', 'Start Two', '150px', $f->retTypeDate(), false);
        $grid->addColumn('dt_end_two', 'End Two', '150px', $f->retTypeDate(), false);
        $grid->addColumn('dt_start_three', 'Start Three', '150px', $f->retTypeDate(), false);
        $grid->addColumn('dt_end_three', 'End Three', '150px', $f->retTypeDate(), false);
        $grid->addColumn('dt_start_four', 'Start Four', '150px', $f->retTypeDate(), false);
        $grid->addColumn('dt_end_four', 'End Four', '150px', $f->retTypeDate(), false);
        $grid->addColumn('dt_join_date', 'Join Date', '150px', $f->retTypeDate(), false);
        $grid->addColumn('ds_faceid_reason', 'FaceID Reason', '150px', $f->retTypeStringAny(), TRUE);


        $filters = $fm->retFiltersWithGroup();
        $javascript = $grid->retGrid();


        $trans = array();
        $trans = $this->cdbhelper->retTranslationDifKeys($trans);



        $send = array("javascript" => $javascript,
            "filters" => $filters,
            "filters_java" => $fm->retJavascript()) + $trans;


        $this->load->view("tti/hr_attendance_base_view", $send);
    }

    public function uploadExcel() {
        //die (print_r($_FILES['file']['tmp_name']));
        /* $this->load->model("tr/test_unit_model", "unitmodel");
          $this->load->model("tr/test_type_model", "typemodel");
         */
        $trans = array('errorFile' => 'This file was not recorgnized. Plese check the format/extension!',
            'noLines' => 'No Information Imported. Check the data or the format',
            'errorReceiving' => 'Error Receiving File, please try again.',
        );

        $trans = $this->cdbhelper->retTranslationDifKeys($trans);

        if (count($_FILES) != 1) {
            die($trans['errorReceiving']);
        }

        $this->load->library('cexcel');
        $xls = $this->cexcel;
        if (1 == 2) {
            $xls = new cexcel();
        }
        $filename = $_FILES['file']['tmp_name'];

        try {
            $xls->loadExcel($filename);
        } catch (Exception $e) {
            die($trans['errorFile']);
        };

        $i = 9;
        $dataGen = array();
        WHILE (true) {

            $Department = $xls->getItem($i, 1);
            $StaffNumber = $xls->getItem($i, 2);
            $StaffName = $xls->getItem($i, 3);
            $AttendDate = $xls->getItemFormatted($i, 4);
            $shift = $xls->getItem($i, 5);
            $AbnormalReason = $xls->getItem($i, 6);

            $xls->setDateTimeFormat($i, 7, 'mm/dd/yyyy hh:mm');
            $StartOne = $xls->getItemFormatted($i, 7);

            $xls->setDateTimeFormat($i, 8, 'mm/dd/yyyy hh:mm');
            $EndOne = $xls->getItemFormatted($i, 8);

            $xls->setDateTimeFormat($i, 9, 'mm/dd/yyyy hh:mm');
            $StartTwo = $xls->getItemFormatted($i, 9);

            $xls->setDateTimeFormat($i, 10, 'mm/dd/yyyy hh:mm');
            $EndTwo = $xls->getItemFormatted($i, 10);

            $xls->setDateTimeFormat($i, 11, 'mm/dd/yyyy hh:mm');
            $StartThree = $xls->getItemFormatted($i, 11);

            $xls->setDateTimeFormat($i, 12, 'mm/dd/yyyy hh:mm');
            $EndThree = $xls->getItemFormatted($i, 12);

            $xls->setDateTimeFormat($i, 13, 'mm/dd/yyyy hh:mm');
            $StartFour = $xls->getItemFormatted($i, 13);

            $xls->setDateTimeFormat($i, 14, 'mm/dd/yyyy hh:mm');
            $EndFour = $xls->getItemFormatted($i, 14);

            $JoinDate = $xls->getItemFormatted($i, 15);

            if ($Department == '') {
                break;
            }

            $retResult = $this->mainmodel->retRetrieveEmptyNewArray()[0];
            //$retResult['nr_staff_number'] = ;
            // starting data input$unit
            $StaffNumber = strtolower($this->cdbhelper->normalizeDataToSQL('nr_staff_number', $StaffNumber));
            //$type = strtolower($this->cdbhelper->normalizeDataToSQL('ds_xx', $type));
            /*
              if ($planStart != '') {
              $ctrl = DateTime::createFromFormat($this->getCdbhelper()->dateFormatPHP, $planStart);
              if (!$ctrl) {
              $planStart = '';
              }
              }


              // if date is not in the right format, remove.
              if ($planEnd != '') {
              $ctrl = DateTime::createFromFormat($this->getCdbhelper()->dateFormatPHP, $planEnd);
              if (!$ctrl) {
              $planEnd = '';
              }
              }
             * 
             * 
             * 
             */
            // adjust data
            if (!is_numeric($StaffNumber)) {
                $StaffNumber = '';
            }

            $retResult['nr_staff_number'] = $StaffNumber;
            $retResult['ds_staff_name'] = $StaffName;

            $retResult['ds_department'] = $Department;

            $retResult['dt_attend_date'] = $AttendDate;
            
            $retResult['ds_shift'] = $shift;

            $retResult['ds_abnormal_reason'] = $AbnormalReason;

            $retResult['dt_start_one'] = $StartOne;

            $retResult['dt_end_one'] = $EndOne;

            $retResult['dt_start_two'] = $StartTwo;

            $retResult['dt_end_two'] = $EndTwo;

            $retResult['dt_start_three'] = $StartThree;

            $retResult['dt_end_three'] = $EndThree;

            $retResult['dt_start_four'] = $StartFour;

            $retResult['dt_end_four'] = $EndFour;

            $retResult['dt_join_date'] = $JoinDate;

            array_push($dataGen, $retResult);

            $i++;
        }



        if (count($dataGen) == 0) {
            $ret = array('status' => $trans['noLines']);
        } else {
            $retDb = $this->mainmodel->updateGridData($dataGen);
            $ret = array('status' => $retDb);
        }

        header('Content-type: application/json');
        echo(json_encode($ret));
    }

    public function openAttendanceCheck() {
        
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

        $fm->addFilterNumber('Staff Number', 'filter_7', '"HR_ATTENDANCE_BASE".nr_staff_number', '10.0', 0, '');
        $fm->addSimpleFilterUpper('Staff Name', 'filter_2', '"HR_ATTENDANCE_BASE".ds_staff_name');
        $fm->addSimpleFilterUpper('Department', 'filter_3', '"HR_ATTENDANCE_BASE".ds_department');
        $fm->addSimpleFilterUpper('Shift', 'filter_9', '"HR_ATTENDANCE_BASE".ds_shift');
        $fm->addSimpleFilterUpper('Abnormal Reason', 'filter_5', '"HR_ATTENDANCE_BASE".ds_abnormal_reason');
        $fm->addFilterDate ('Attend Date', 'filter_6', '"HR_ATTENDANCE_BASE".dt_attend_date', date('m/d/Y', strtotime('-7 days')), date('m/d/Y') );
        $fm->addFilterDate ('Join Date', 'filter_8', '"HR_ATTENDANCE_BASE".dt_join_date');
        $fm->addSimpleFilterUpper('FaceID Reason', 'filter_10', '"HR_ATTENDANCE_BASE".ds_faceid_reason');
        
        $fm->setFilterLevels(2);
        
        $fixed = array(
            array('desc' => 'MISSING FACE ID SCAN',
                'sql' => ' 1 = 1 )',
                'idDesc' => 1),
            array('desc' => 'MISSING KRONOS SCAN',
                'sql' => ' 2 = 2 )',
                'idDesc' => 2),
            array('desc' => 'MISSING BOTH',
                'sql' => ' 3 = 3 )',
                'idDesc' => 3)
                );

        $fm->addFilter('filter_status', 'Status', array('plFixedSelect' => $fixed));
        
        
        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar(true, false, true, false, true);
        $grid->addBreakToolbar();
        $grid->addUserCheckToolbar('showDetails', 'Show Details', 'Show Details', false);
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("tti/hr_attendance_base");

        $grid->addColumnKey();

        $grid->addColumn('ds_staff_number', 'Staff Number', '80px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_human_resource', 'Staff Name', '100px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_department', 'Department', '100px', $f->retTypeStringAny(), false);
        $grid->addColumn('dt_attend_date', 'AttendDate', '120px', $f->retTypeDate(), false);
        $grid->addColumn('ds_shift', 'Shift', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_abnormal_reason', 'Abnormal Reason', '150px', $f->retTypeStringAny(), false);


        $grid->addColumn('ds_first_kronos_status', '1st', '40px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_second_kronos_status', '2nd', '40px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_third_kronos_status', '3rd', '40px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_forth_kronos_status', '4th', '40px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_fifth_kronos_status', '5th', '40px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_sixth_kronos_status', '6th', '40px', $f->retTypeStringAny(), false);
        
        $grid->addColumn('ds_separator', ' ', '20px', $f->retTypeStringAny(), false);
        
        $grid->addColumn('ds_first_faceid_status', '1st', '40px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_second_faceid_status', '2nd', '40px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_third_faceid_status', '3rd', '40px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_forth_faceid_status', '4th', '40px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_fifth_faceid_status', '5th', '40px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_sixth_faceid_status', '6th', '40px', $f->retTypeStringAny(), false);
        
        $grid->setColumnRenderFunc('ds_first_kronos_status', 'dsMainObject.setColumnStatus');
        $grid->setColumnRenderFunc('ds_second_kronos_status', 'dsMainObject.setColumnStatus');
        $grid->setColumnRenderFunc('ds_third_kronos_status', 'dsMainObject.setColumnStatus');
        $grid->setColumnRenderFunc('ds_forth_kronos_status', 'dsMainObject.setColumnStatus');
        $grid->setColumnRenderFunc('ds_fifth_kronos_status', 'dsMainObject.setColumnStatus');
        $grid->setColumnRenderFunc('ds_sixth_kronos_status', 'dsMainObject.setColumnStatus');

        $grid->setColumnRenderFunc('ds_first_faceid_status', 'dsMainObject.setColumnStatus');
        $grid->setColumnRenderFunc('ds_second_faceid_status', 'dsMainObject.setColumnStatus');
        $grid->setColumnRenderFunc('ds_third_faceid_status', 'dsMainObject.setColumnStatus');
        $grid->setColumnRenderFunc('ds_forth_faceid_status', 'dsMainObject.setColumnStatus');
        $grid->setColumnRenderFunc('ds_fifth_faceid_status', 'dsMainObject.setColumnStatus');
        $grid->setColumnRenderFunc('ds_sixth_faceid_status', 'dsMainObject.setColumnStatus');
        $grid->addColumn('ds_faceid_reason', 'Reason', '150px', $f->retTypeStringAny(), TRUE);
        
        $grid->addColumnGroup(6, '');
        $grid->addColumnGroup(6, 'Kronos');
        $grid->addColumnGroup(1, '');
        $grid->addColumnGroup(6, 'Face ID');

        $filters = $fm->retFiltersWithGroup();
        $javascript = $grid->retGrid();


        $trans = array();
        $trans = $this->cdbhelper->retTranslationDifKeys($trans);



        $send = array("javascript" => $javascript,
            "filters" => $filters,
            "filters_java" => $fm->retJavascript()) + $trans;


        $this->load->view("tti/hr_attendance_check_view", $send);
    }
    
    
    public function retrieveGridJsonAttendance($status)
    {


        if (!$this->logincontrol->isProperLogged(false)) {
            echo('{"logged": "N", "resultset": [] }');
            return;
        }
        
        $whereAfter = ' WHERE 1 = 1';
        
        if ($status == 1 || $status == 3 ) {
            $whereAfter = $whereAfter .' AND ( ( ( fl_must_first_faceid = 1 AND dt_first_faceid IS NULL ) OR ( fl_must_second_faceid = 1 AND dt_second_faceid IS NULL ) OR ( fl_must_third_faceid = 1 AND dt_third_faceid IS NULL) OR ( fl_must_forth_faceid = 1 AND dt_forth_faceid IS NULL) OR ( fl_must_fifth_faceid = 1 AND dt_fifth_faceid IS NULL) OR ( fl_must_sixth_faceid = 1 AND dt_sixth_faceid IS NULL) ) AND fl_id_scanned_before = 1 )';
        }
        
        if ($status == 2 || $status == 3) {
            $whereAfter = $whereAfter .' AND ( (fl_must_first = 1 AND dt_first_kronos IS NULL ) OR ( fl_must_second = 1 AND dt_second_kronos IS NULL ) OR ( fl_must_third = 1 AND dt_third_kronos IS NULL) OR ( fl_must_forth = 1 AND dt_forth_kronos IS NULL) OR ( fl_must_fifth = 1 AND dt_fifth_kronos IS NULL) OR ( fl_must_sixth = 1 AND dt_sixth_kronos IS NULL) )';
        }

        

        $where = $this->getWhereToFilter();
        
        $where  = str_replace("'", "''", $where);
        
        
        $sql = "select  recid, 
            nr_staff_number, 
            ds_staff_number,
            ds_department,
        cd_human_resource, 
        ds_human_resource,
        datedbtogrid(dt_attend_date) as dt_attend_date , 
        ds_shift,
        datetimedbtogrid(dt_first_kronos) as dt_first_kronos, 
        datetimedbtogrid(dt_second_kronos) as dt_second_kronos,
        datetimedbtogrid(dt_third_kronos) as dt_third_kronos,
        datetimedbtogrid(dt_forth_kronos) as dt_forth_kronos,
        datetimedbtogrid(dt_fifth_kronos) as dt_fifth_kronos,
        datetimedbtogrid(dt_sixth_kronos) as dt_sixth_kronos,
        datetimedbtogrid(dt_first_faceid) as dt_first_faceid,
        datetimedbtogrid(dt_second_faceid) as dt_second_faceid,
        datetimedbtogrid(dt_third_faceid) as dt_third_faceid,
        datetimedbtogrid(dt_forth_faceid) as dt_forth_faceid,
        datetimedbtogrid(dt_fifth_faceid) as dt_fifth_faceid,
        datetimedbtogrid(dt_sixth_faceid) as dt_sixth_faceid,
        fl_id_scanned_before,
        fl_must_first, 
        fl_must_second, 
        fl_must_third, 
        fl_must_forth ,
        fl_must_fifth ,
        fl_must_sixth ,
        fl_must_first_faceid, 
        fl_must_second_faceid, 
        fl_must_third_faceid, 
        fl_must_forth_faceid ,
        fl_must_fifth_faceid ,
        fl_must_sixth_faceid ,
        

        CASE WHEN fl_must_first = 0 THEN 'NA'
             WHEN fl_must_first = 1 AND dt_first_kronos IS NOT NULL THEN 'OK'
             ELSE 'MISSING' END as ds_first_kronos_status,
             
        CASE WHEN fl_must_second = 0 THEN 'NA'
             WHEN fl_must_second = 1 AND dt_second_kronos IS NOT NULL THEN 'OK'
             ELSE 'MISSING' END as ds_second_kronos_status,
             
        CASE WHEN fl_must_third = 0 THEN 'NA'
             WHEN fl_must_third = 1 AND dt_third_kronos IS NOT NULL THEN 'OK'
             ELSE 'MISSING' END as ds_third_kronos_status,
             
        CASE WHEN fl_must_forth = 0 THEN 'NA'
             WHEN fl_must_forth = 1 AND dt_forth_kronos IS NOT NULL THEN 'OK'
             ELSE 'MISSING' END as ds_forth_kronos_status,  
             
        CASE WHEN fl_must_fifth = 0 THEN 'NA'
             WHEN fl_must_fifth = 1 AND dt_fifth_kronos IS NOT NULL THEN 'OK'
             ELSE 'MISSING' END as ds_fifth_kronos_status, 
             
        CASE WHEN fl_must_sixth = 0 THEN 'NA'
             WHEN fl_must_sixth = 1 AND dt_sixth_kronos IS NOT NULL THEN 'OK'
             ELSE 'MISSING' END as ds_sixth_kronos_status, 
        
        
        CASE WHEN fl_must_first_faceid = 0 THEN 'NA'
             WHEN fl_must_first_faceid = 1 AND dt_first_faceid IS NOT NULL THEN 'OK'
             ELSE 'MISSING' END as ds_first_faceid_status,
             
        CASE WHEN fl_must_second_faceid = 0 THEN 'NA'
             WHEN fl_must_second_faceid = 1 AND dt_second_faceid IS NOT NULL THEN 'OK'
             ELSE 'MISSING' END as ds_second_faceid_status,
             
        CASE WHEN fl_must_third_faceid = 0 THEN 'NA'
             WHEN fl_must_third_faceid = 1 AND dt_third_faceid IS NOT NULL THEN 'OK'
             ELSE 'MISSING' END as ds_third_faceid_status,
             
        CASE WHEN fl_must_forth_faceid = 0 THEN 'NA'
             WHEN fl_must_forth_faceid = 1 AND dt_forth_faceid IS NOT NULL THEN 'OK'
             ELSE 'MISSING' END as ds_forth_faceid_status,
             
        CASE WHEN fl_must_fifth_faceid = 0 THEN 'NA'
             WHEN fl_must_fifth_faceid = 1 AND dt_fifth_faceid IS NOT NULL THEN 'OK'
             ELSE 'MISSING' END as ds_fifth_faceid_status,
             
        CASE WHEN fl_must_sixth_faceid = 0 THEN 'NA'
             WHEN fl_must_sixth_faceid = 1 AND dt_sixth_faceid IS NOT NULL THEN 'OK'
             ELSE 'MISSING' END as ds_sixth_faceid_status,
             
        ds_faceid_reason

             

 from getTimeAttendance(' $where ') $whereAfter";
        
        //die(print_r($sql));

        
        $result = $this->getCdbhelper()->basicSQLJson($sql, true);
        

        echo('{ "logged": "Y", "resultset": ' . $result . ' }');

    }
    
    
    
}
