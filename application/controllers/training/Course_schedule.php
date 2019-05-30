<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class course_schedule extends controllerBasicExtend
{

    var $arrayIns;
    var $fields;

    function __construct()
    {
        parent::__construct();
        $this->load->model("training/course_schedule_model", "mainmodel", TRUE);
        $this->load->model("training/course_schedule_trainer_model", "trainermodel", TRUE);
        $this->load->model("training/trainee_grade_model", "traineegrademodel", TRUE);
        $this->load->model("human_resource_model", "humanresourcemodel", TRUE);
        $this->load->model("training/course_model", "coursemodel", TRUE);
        $this->load->model("training/course_testing_result_model", "testresultmodel", TRUE);
//        $this->load->model("training/course_category_model", "categorymodel", TRUE);

    }

    public function index()
    {

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

        $fm->addPickListFilter('Course Schedule Status', 'filter_20', 'training/course_status', '"COURSE_SCHEDULE".cd_course_schedule_status');
        $fm->addPickListFilter('Location', 'filter_1', 'training/course_location', '"COURSE_SCHEDULE".cd_course_location');
        
        
        //$fm->addPickListFilter('Course', 'filter_4', 'training/course', '"COURSE_SCHEDULE".cd_course');
        
        
        $fm->addSimpleFilterUpper('Remark', 'filter_5', '"COURSE_SCHEDULE".ds_remark');
//        $fm->addPickListFilter('Human Resource Recorder', 'filter_8', 'human_resource', '"COURSE_SCHEDULE".cd_human_resource_recorder');
        $fm->addPickListFilter('Course Exam Method', 'filter_9', 'training/course_exam_method', '"COURSE_SCHEDULE".cd_course_exam_method');
        $fm->addSimpleFilterUpper('Work Order', 'filter_10', '"COURSE_SCHEDULE".ds_work_order');
        $fm->addSimpleFilterUpper('Equipment Name', 'filter_11', '"COURSE_SCHEDULE".ds_equipment_name');
        $fm->addSimpleFilterUpper('Equipment Model', 'filter_12', '"COURSE_SCHEDULE".ds_equipment_model');
        $fm->addPickListFilter('Contacts', 'filter_16', 'Users_maint', '"COURSE_SCHEDULE".cd_human_resource_contacts');
        $fm->addPickListFilter('Witnesses Assistant', 'filter_17', 'Users_maint', '"COURSE_SCHEDULE".cd_human_resource_witnesses_assistant');
        $fm->addPickListFilter('Test Engineer', 'filter_18', 'Users_maint', '"COURSE_SCHEDULE".cd_human_resource_test_engineer');


        $fm->addPickListFilterExists("Trainer", "human_resource_controller", "filter_2", "COURSE_SCHEDULE", "cd_course_schedule", "COURSE_SCHEDULE_TRAINER", "cd_human_resource", "cd_course_schedule", false);
        $fm->addPickListFilterExists("Trainer Staff#", "human_resource_controller", "filter_21", "COURSE_SCHEDULE", "cd_course_schedule", "COURSE_SCHEDULE_TRAINER", "cd_human_resource", "cd_course_schedule", false, false, 'A', 'retPickListStaff', true);
        $fm->addPickListFilterExists("Trainee", "human_resource_controller", "filter_3", "COURSE_SCHEDULE", "cd_course_schedule", "TRAINEE_GRADE", "cd_human_resource_trainee", "cd_course_schedule", false);
        
        $fm->addPickListFilterExists("Trainee Staff#", "human_resource_controller", "filter_22", "COURSE_SCHEDULE", "cd_course_schedule", "TRAINEE_GRADE", "cd_human_resource_trainee", "cd_course_schedule", false, false, 'A', 'retPickListStaff', true);
        $fm->addFilter('filter_4', 'Course', array('controller' => 'training/course', 'fieldname' => '"COURSE_SCHEDULE".cd_course', 'multi' => true));

        
        
        
        $fm->addFilterYesNo("Active", "dt_deactivated", "", "Y");


        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->addDocRepToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("training/course_schedule");
        $grid->addUserBtnToolbar('sendScheduleEmail', 'Send Schedule Email', 'fa fa-envelope');
        $grid->addUserBtnToolbar('exportMergeExcel', 'Export Merge Excel', 'fa fa-file-excel-o');

        $grid->addColumnKey();
        $grid->addColumn('ds_course_schedule_status', 'Course Schedule Status', '150px', $f->retTypePickList(), array('model' => 'training/course_status_model', 'codeField' => 'cd_course_schedule_status'));
        $grid->addColumn('dt_course_start', 'Course Start', '120px', $f->retTypeStringAny(), false);
        $grid->addColumn('dt_course_end', 'Course End', '120px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_course_location', 'Location', '150px', $f->retTypePickList(), array('model' => 'training/course_location_model', 'codeField' => 'cd_course_location'));
        $grid->addColumn('ds_work_order', 'Work Order', '150px', $f->retTypeStringAny(), array('limit' => ''));
        $grid->addColumn('ds_course', 'Course', '150px', $f->retTypePickList(), array('model' => 'training/course_model', 'codeField' => 'cd_course'));
        $grid->addColumn('ds_course_exam_method', 'Course Exam Method', '150px', $f->retTypePickList(), array('model' => 'training/course_exam_method_model', 'codeField' => 'cd_course_exam_method'));
        $grid->addColumn('ds_human_resource_test_engineer', 'Test Engineer', '150px', $f->retTypePickList(), array('model' => 'human_resource_model', 'codeField' => 'cd_human_resource_test_engineer'));
        $grid->addColumn('ds_human_resource_witnesses_assistant', 'Witnesses Assistant', '150px', $f->retTypePickList(), array('model' => 'human_resource_model', 'codeField' => 'cd_human_resource_witnesses_assistant'));
        $grid->addColumn('ds_human_resource_contacts', 'Contacts', '150px', $f->retTypePickList(), array('model' => 'human_resource_model', 'codeField' => 'cd_human_resource_contacts'));
        $grid->addColumn('ds_equipment_name', 'Equipment Name', '150px', $f->retTypeStringAny(), array('limit' => ''));
        $grid->addColumn('ds_equipment_model', 'Equipment Model', '150px', $f->retTypeStringAny(), array('limit' => ''));
        $grid->addColumn('fl_sample_ready', 'Sample Ready', '150px', $f->retTypeCheckBox(), true);
        $grid->addColumn('fl_material_ready', 'Material Ready', '150px', $f->retTypeCheckBox(), true);
        $grid->addColumn('fl_fixture_ready', 'Fixture Ready', '150px', $f->retTypeCheckBox(), true);

        $grid->addColumn('ds_remark', 'Remark', '150px', $f->retTypeStringAny(), array('limit' => ''));
//        $grid->addColumn('ds_human_resource_recorder', 'Recorder', '150px', $f->retTypePickList(), array('model' => 'human_resource_model', 'codeField' => 'cd_human_resource_recorder'));
        $grid->addColumn('ds_human_resource_recorder', 'Recorder', '150px', $f->retTypeStringAny(), false);


        $grid->addColumnDeactivated(true);


        $filters = $fm->retFiltersWithGroup();
        $javascript = $grid->retGrid();


        $trans = array();
        $trans = $this->cdbhelper->retTranslationDifKeys($trans);


        $send = array("javascript" => $javascript,
                "filters" => $filters,
                "filters_java" => $fm->retJavascript()) + $trans;


        //----------------------------------------------------------------------------------------------------------------------------------


        $grid->resetGrid();
        $grid->setToolbarPrefix('Trainer');
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar(false, true, false, true, false);
        $grid->setToolbarSearch(false);
        $grid->setCRUDController("training/course_schedule_trainer");

        $grid->addToolbarTitle('Trainer');
        $grid->addColumnKey();

        $grid->addColumn('nr_staff_number', 'Staff Number', '100%', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_human_resource', 'Trainer', '100%', $f->retTypePickList(), array('model' => 'human_resource_model', 'codeField' => 'cd_human_resource'));

        $grid->setGridDivName('gridTrainerDiv');
        $grid->setGridName('gridTrainer');
        $grid->setGridVar('vBenefit');


//        $filters = $fm->retFiltersWithGroup();
        $javascript = $javascript . $grid->retGrid();


        $trans = array();
        $trans = $this->cdbhelper->retTranslationDifKeys($trans);


        $send = array("javascript" => $javascript,
                "filters" => $filters,
                "filters_java" => $fm->retJavascript()) + $trans;


//----------------------------------------------------------------------------------------------------------------------------------------
        $grid->resetGrid();
        $grid->setToolbarPrefix('TraineeGrade');
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar(false, true, false, true, false);
        $grid->setToolbarSearch(false);
        $grid->setCRUDController("training/trainee_grade");
        $grid->addUserBtnToolbar('sendGradeEmail', 'Send Grade Email', 'fa fa-envelope');
        $grid->addToolbarTitle('Trainee Grade');
        $menu = $grid->addUserBtnToolbar('batchUpdate', 'Batch Update Test Result', 'fa fa-check-square-o');

        $grid->addUserBtnToolbar('batchFail', 'batch update to fail', '', 'Fail', $menu);
        $grid->addUserBtnToolbar('batchPass', 'batch update to pass', '', 'Pass', $menu);
        $recordTestResultPass = $this->testresultmodel->retRetrieveArray(' WHERE "COURSE_TESTING_RESULT".cd_course_testing_result = 1')[0];
        $TestResultPass = $recordTestResultPass['ds_course_testing_result'];

        $recordTestResultFailed = $this->testresultmodel->retRetrieveArray(' WHERE "COURSE_TESTING_RESULT".cd_course_testing_result =2')[0];
        $TestResultFailed = $recordTestResultFailed['ds_course_testing_result'];


        $grid->addColumnKey();


//        $grid->addColumn('nr_staff_number', 'Staff Number', '100%', $f->retTypePickList(), array('model' => 'human_resource_model', 'codeField' => 'cd_human_resource_trainee'));
        $grid->addColumn('nr_staff_number', 'Staff Number', '100%', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_human_resource_trainee', 'Trainee', '100%', $f->retTypePickList(), array('model' => 'human_resource_model', 'codeField' => 'cd_human_resource_trainee'));
        $grid->addColumn('ds_course_testing_result', 'Course Testing Result', '100%', $f->retTypePickList(), array('model' => 'training/course_testing_result_model', 'codeField' => 'cd_course_testing_result'));
        $grid->addColumn('ds_remark', 'Remark', '100%', $f->retTypeStringAny(), array('limit' => ''));
//        $grid->addColumn('ds_human_resource_recorder', 'Recorder', '100%', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_course_attend_confirmation', 'Course Attend Confirmation', '150px', $f->retTypePickList(), array('model' => 'training/course_attend_confirmation_model', 'codeField' => 'cd_course_attend_confirmation'));

        $grid->setGridDivName('gridTraineeGradeDiv');
        $grid->setGridName('gridTraineeGrade');
        $grid->setGridVar('vBenefit');


//        $filters = $fm->retFiltersWithGroup();
        $javascript = $javascript . $grid->retGrid();


//        $trans = array();
        $trans = array('TrainerStaffNumberExists' => 'staff number already existing on list',
            'SpecificTrainerStaffNumber' => 'Specific Staff Number',
            'TrainerStaffNumberNotFound' => 'Staff Number not Found',
            'TraineeStaffNumberNotFound' => 'Staff Number not Found',
            'TraineeStaffNumberExists' => 'staff number already existing on list',
            'titledates' => 'Select Start and End Date/Time',
            'SpecificTraineeStaffNumber' => 'Specific Staff Number',
            'confirmSendMail' => 'Do you confirm send email?',
            'EmailSendSucess' => 'Email sent successfully');
        $trans = $this->cdbhelper->retTranslationDifKeys($trans);


        $send = array("javascript" => $javascript,
                "filters" => $filters,
                "TestResultPass" => $TestResultPass,
                "TestResultFailed" => $TestResultFailed,
                "filters_java" => $fm->retJavascript()) + $trans;


        $this->load->view("training/course_schedule_view", $send);


    }

    function sendClassMail($vpk)
    {


        $this->load->library('sendmail');

        $courseSchedule = $this->mainmodel->retRetrieveArray(' WHERE "COURSE_SCHEDULE".cd_course_schedule = ' . $vpk)[0];
        $course = $this->coursemodel->retRetrieveArray(' where "COURSE".cd_course=' . $courseSchedule['cd_course'])[0];

        $data = $this->trainermodel->retRetrieveArray(" WHERE cd_course_schedule = " . $vpk);

        $traineeGrade = $this->traineegrademodel->retRetrieveArray(" WHERE cd_course_schedule = " . $vpk);
//
        $trainer = "";
        foreach ($data as $key => $row) {
            $trainer = $row['ds_human_resource'] . ',' . $trainer;
        }


        $ds_course_schedule_status = $courseSchedule['ds_course_schedule_status'];
        $ds_course = $courseSchedule['ds_course'];
        $dt_course_start = $courseSchedule['dt_course_start'];
        $dt_course_end = $courseSchedule['dt_course_end'];
        $ds_course_location = $courseSchedule['ds_course_location'];
        $ds_remark = $courseSchedule['ds_remark'];


        $ds_course_number = $course['ds_course_number'];
        $ds_course_category = $course['ds_course_category'];


        $subject = $ds_course_schedule_status . $ds_course . " from MIL Reliability Lab Training Team";
        $this->sendmail->setSubject($subject);


        $query = "SELECT  distinct(ds_e_mail) FROM public.\"HUMAN_RESOURCE\" where cd_human_resource in (SELECT distinct(cd_human_resource_trainee) FROM  training.\"TRAINEE_GRADE\" where cd_course_schedule=$vpk ) and coalesce(ds_e_mail, '') <> ''";

        $mail = $this->getCdbhelper()->basicSQLArray($query);

        $query = "SELECT  distinct(ds_e_mail) FROM public.\"HUMAN_RESOURCE\" where cd_human_resource in (SELECT distinct(cd_human_resource) FROM  training.\"COURSE_SCHEDULE_TRAINER\" where cd_course_schedule=$vpk )  and coalesce(ds_e_mail, '') <> ''";

        $trainerMail = $this->getCdbhelper()->basicSQLArray($query);

        $query = "SELECT  ds_e_mail FROM public.\"HUMAN_RESOURCE\" where cd_human_resource =(SELECT cd_human_resource_recorder FROM  training.\"COURSE_SCHEDULE\" where cd_course_schedule=$vpk )  and coalesce(ds_e_mail, '') <> ''";

        $resultSet = $this->getCdbhelper()->basicSQLArray($query);
        $planerMail = $resultSet[0]['ds_e_mail'];

//        die($planerMail);
        $find = array('#status#',
            '#start#',
            '#end#',
            '#Location#',
            '#courseName#',
            '#courseCategory#',
            '#courseNumber#',
            '#trainer#',
            '#remark#',
            '#planerMail#'
        );

        $replaces = array($ds_course_schedule_status,
            $dt_course_start,
            $dt_course_end,
            $ds_course_location,
            $ds_course,
            $ds_course_category,
            $ds_course_number,
            $trainer,
            $ds_remark,
            $planerMail
        );


        $workers = '';
        $i = 0;
        foreach ($traineeGrade as $key => $row) {
            $i = $i + 1;
            $staffNumber = $row['nr_staff_number'];
            $trainee = $row['ds_human_resource_trainee'];
            $testResult = $row['ds_course_testing_result'];
            $remark = $row['ds_remark'];
            $workers = $workers .
                '<tr height="18" style="height:13.5pt">
                <td class="auto-style26" height="18">　</td>
                <td align="right">' . $i . '</td>
                <td>' . $trainee . '</td>
                <td></td>
                <td class="auto-style27">' . $staffNumber . '</td>
                <td class="auto-style27">' . $testResult . '</td>
                <td class="auto-style27">' . $remark . '</td>
                <td class="auto-style12">　</td>
                </tr>';
        }

        $html = $this->load->view('mailtemplates/training', array('workers' => $workers), true);
        $html = str_replace($find, $replaces, $html);



        $this->sendmail->setMessage($html);

        foreach ($mail as $key => $value) {
            $this->sendmail->addTO($value['ds_e_mail']);
        }

        foreach ($trainerMail as $key => $value) {
            $this->sendmail->addCC($value['ds_e_mail']);
        }
        $this->sendmail->addCC($planerMail);

        $this->sendmail->sendToSender(true);
        $this->sendmail->sendMail();

        echo(json_encode(array('status' => 'OK')));


    }

    public function getTrainerByStaffNumber($cd_course_schedule, $StaffNumber)
    {
        $arrayToReturn = $this->humanresourcemodel->retRetrieveArray(" WHERE nr_staff_number = $StaffNumber");

        if (count($arrayToReturn) == 0) {
            echo('[]');
            return;
        }
        $array_ins = array(
            array(
                'recid' => $this->trainermodel->getNextCode(),
                'cd_human_resource' => $arrayToReturn[0]['cd_human_resource'],
                'ds_human_resource' => $arrayToReturn[0]['ds_human_resource'],
                'nr_staff_number' => $arrayToReturn[0]['nr_staff_number'],
                'cd_course_schedule' => $cd_course_schedule
            )
        );
        echo(json_encode($array_ins, JSON_NUMERIC_CHECK));
    }

    public function getTraineeByStaffNumber($cd_course_schedule, $StaffNumber)
    {
        $arrayToReturn = $this->humanresourcemodel->retRetrieveArray(" WHERE nr_staff_number = $StaffNumber");

        if (count($arrayToReturn) == 0) {
            echo('[]');
            return;
        }
        $array_ins = array(
            array(
                'recid' => $this->traineegrademodel->getNextCode(),
                'cd_human_resource_trainee' => $arrayToReturn[0]['cd_human_resource'],
                'ds_human_resource_trainee' => $arrayToReturn[0]['ds_human_resource'],
                'nr_staff_number' => $arrayToReturn[0]['nr_staff_number'],
                'cd_course_schedule' => $cd_course_schedule
            )
        );
        echo(json_encode($array_ins, JSON_NUMERIC_CHECK));
    }


    // function that retrieves the information. here should have the columns that will receive the child tables.检索信息的函数。这里应该有接收子表的列。
    // filterReceived would trigger excel export
    function retrieveGridJson($retrOpt = array(), $filterReceived = '') {
        if (empty($retrOpt)) $retrOpt = [];
        if (!$this->logincontrol->isProperLogged(false)) {
            echo('{"logged": "N", "resultset": [] }');
            return;
        }
        $where = $this->getWhereToFilter($filterReceived);
        $jsonMapping = $this->getJsonMappingToFilter();

        if (isset($retrOpt['whereToAdd'])) {
            $where = $where . $retrOpt['whereToAdd'];
        }
        $data = $this->mainmodel->retRetrieveGridArray($where, '', $retrOpt);
        if ($filterReceived) {
            $this->load->library('cexcel');
            $xls = $this->cexcel;
            $sheetName = 'Training Course Schedule';
            date_default_timezone_set('PRC');
            $time = date('YmdHis');
            $xls->newSpreadSheet($sheetName);
            $xls->selectActiveSheet($sheetName);
            $courseTitle = [
                'cd_course_schedule' => 'Course Schedule ID', 'ds_course_schedule_status' => 'Course Schedule Status', 'dt_course_start' => 'Course Start', 'dt_course_end' => 'Course End',
                'ds_course_location' => 'Location', 'ds_work_order' => 'Work Order', 'ds_course' => 'Course', 'ds_course_exam_method' => 'Course Exam Method',
                'ds_human_resource_test_engineer' => 'Test Engineer', 'ds_human_resource_witnesses_assistant' => 'Witnesses Assistant', 'ds_human_resource_contacts' => 'Contacts',
                'ds_equipment_name' => 'Equipment Name', 'ds_equipment_model' => 'Equipment Model', 'fl_sample_ready' => 'Sample Ready', 'fl_material_ready' => 'Material Ready',
                'fl_fixture_ready' => 'Fixture Ready', 'ds_remark' => 'Remark', 'ds_human_resource_recorder' => 'Recorder',
            ];
            $trainerTitle = ['nr_staff_number' => 'Trainer Staff Number', 'ds_human_resource' => 'Trainer'];
            $traineeTitle = ['nr_staff_number' => 'Trainee Staff Number', 'ds_human_resource_trainee' => 'Trainee', 'ds_course_testing_result' => 'Course Testing Result', 'ds_remark' => 'Remark',
                'ds_course_attend_confirmation' => 'Course Attend Confirmation', 'ds_department' => 'Department', 'ds_team' => 'Team', 'ds_roles' => 'Project User Role', 'ds_human_resource_title' => 'User Title'];

            $xls->setColumnTitle(array_merge(array_values($courseTitle), array_values($trainerTitle), array_values($traineeTitle)));
            $bgColor = ['b6d5ff','e5f8e5'];
            $flagColor = 0;
            foreach ($data as $value) {
                $trainees = $value['TraineeGrade'];
                $trainers = $value['trainer'];
                $trainerStuff = $trainerDs = '';
                if (is_string($trainers)) {
                    $trainers = json_decode($trainers, true);
                }
                if (is_string($trainees)) {
                    $trainees = json_decode($trainees, true);
                }
                if (!empty($trainers)) {
                    $trainerStuff = implode(PHP_EOL, array_column($trainers, 'nr_staff_number'));
                    $trainerDs = implode(PHP_EOL, array_column($trainers, 'ds_human_resource'));
                }
                if (!empty($trainees)) {
                    foreach ($trainees as $trainee) {
                        foreach ($courseTitle as $keyCourse => $val) {
                            $xls->setCell(trim($value[$keyCourse]));
                        }
                        $xls->setCell($trainerStuff);
                        $xls->setCell($trainerDs);
                        foreach ($traineeTitle as $keyTrainee => $val) {
                            $xls->setCell(trim($trainee[$keyTrainee]));
                        }
                        $xls->nextRow();
                    }
                    $xls->selectArea($xls->rowIndex - count($trainees), 1, $xls->rowIndex - 1, count($courseTitle) + count($trainerTitle) + count($traineeTitle));
                    $xls->setBackgroundColor($bgColor[$flagColor % 2]);
                    $flagColor++;
                } else {
                    foreach ($courseTitle as $keyCourse => $val) {
                        $xls->setCell(trim($value[$keyCourse]));
                    }
                    $xls->setCell($trainerStuff);
                    $xls->setCell($trainerDs);
                    $xls->nextRow();
                }
            }
            $xls->saveAsOutput($sheetName . '-' . $time . '.xlsx');
            $xls->cleanMemory();
        } else {
            echo('{ "logged": "Y", "resultset": ' . json_encode($data, JSON_NUMERIC_CHECK) . ' }');
        }

    }

    public function updateDataJson()
    {

        $msg = '';

        $upd_array = json_decode($_POST['upd']);
        $retResultset = 'N';

        if (isset($_POST['retResultSet'])) {
            $retResultset = $_POST['retResultSet'];
        }
        $jsonMapping = '';
        if (isset($_POST['jsonMapping'])) {
            $jsonMapping = $_POST['jsonMapping'];
        }


        $this->cdbhelper->trans_begin();


        $error = $this->mainmodel->updateGridData($upd_array);
        if ($error != 'OK') {
            $msg = '{"status":' . json_encode($error) . ', "rs":{}}';
            $this->cdbhelper->trans_end();
            echo $msg;
            return;
        }

        // tax
        $error = $this->trainermodel->updateGridDataFromField('trainer', $upd_array);
        if ($error != 'OK') {
            $msg = '{"status":' . json_encode($error) . ', "rs":{}}';
            $this->cdbhelper->trans_end();
            echo $msg;
            return;
        }

        $error = $this->traineegrademodel->updateGridDataFromField('TraineeGrade', $upd_array);
        if ($error != 'OK') {
            $msg = '{"status":' . json_encode($error) . ', "rs":{}}';
            $this->cdbhelper->trans_end();
            echo $msg;
            return;
        }

        $this->cdbhelper->trans_commit();
        $this->cdbhelper->trans_end();

        $msg = '{"status":' . json_encode($error);


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


