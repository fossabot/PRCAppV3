<?php
include_once APPPATH . "models/modelBasicExtend.php";

class course_schedule_model extends modelBasicExtend
{

    function __construct()
    {

        $this->table = "COURSE_SCHEDULE";

        $this->pk_field = "cd_course_schedule";
        $this->ds_field = "ds_work_order";
        
        $this->prodCatUnique = 'Y';

        $this->sequence_obj = 'training."COURSE_SCHEDULE_cd_course_schedule_seq"';

        $this->controller = 'training/course_schedule';

        $this->load->model("training/course_schedule_trainer_model", "trainermodel", TRUE);
        $this->load->model("training/trainee_grade_model", "traineegrademodel", TRUE);

        $this->fieldsforGrid = array(

            ' "COURSE_SCHEDULE".cd_course_schedule',
            ' "COURSE_SCHEDULE".cd_course_location',
            '( select ds_course_location FROM "COURSE_LOCATION" WHERE cd_course_location =  "COURSE_SCHEDULE".cd_course_location) as ds_course_location',
            ' ( datetimedbtogrid("COURSE_SCHEDULE".dt_course_start) ) as dt_course_start',
            ' ( datetimedbtogrid("COURSE_SCHEDULE".dt_course_end) ) as dt_course_end',
            ' "COURSE_SCHEDULE".cd_course',
            '( select ds_course_number || \'-\' || ds_course FROM "COURSE" WHERE cd_course =  "COURSE_SCHEDULE".cd_course) as ds_course',
            ' "COURSE_SCHEDULE".ds_remark',
            ' "COURSE_SCHEDULE".dt_deactivated',
            ' "COURSE_SCHEDULE".dt_record',
            ' "COURSE_SCHEDULE".cd_human_resource_recorder',
            '( select ds_human_resource_full FROM "HUMAN_RESOURCE" WHERE cd_human_resource =  "COURSE_SCHEDULE".cd_human_resource_recorder) as ds_human_resource_recorder',
            ' "COURSE_SCHEDULE".cd_course_exam_method',
            '( select ds_course_exam_method FROM "COURSE_EXAM_METHOD" WHERE cd_course_exam_method =  "COURSE_SCHEDULE".cd_course_exam_method) as ds_course_exam_method',
            ' "COURSE_SCHEDULE".ds_work_order',
            ' "COURSE_SCHEDULE".ds_equipment_name',
            ' "COURSE_SCHEDULE".ds_equipment_model',
            ' "COURSE_SCHEDULE".fl_sample_ready',
            ' "COURSE_SCHEDULE".fl_material_ready',
            ' "COURSE_SCHEDULE".fl_fixture_ready',
            ' "COURSE_SCHEDULE".cd_human_resource_contacts',
            '( select ds_human_resource_full FROM "HUMAN_RESOURCE" WHERE cd_human_resource =  "COURSE_SCHEDULE".cd_human_resource_contacts) as ds_human_resource_contacts',
            ' "COURSE_SCHEDULE".cd_human_resource_witnesses_assistant',
            '( select ds_human_resource_full FROM "HUMAN_RESOURCE" WHERE cd_human_resource =  "COURSE_SCHEDULE".cd_human_resource_witnesses_assistant) as ds_human_resource_witnesses_assistant',
            ' "COURSE_SCHEDULE".cd_human_resource_test_engineer',
            '( select ds_human_resource_full FROM "HUMAN_RESOURCE" WHERE cd_human_resource =  "COURSE_SCHEDULE".cd_human_resource_test_engineer) as ds_human_resource_test_engineer',
            ' "COURSE_SCHEDULE".cd_course_schedule_status',
            '( select ds_course_status FROM "COURSE_STATUS" WHERE cd_course_status =  "COURSE_SCHEDULE".cd_course_schedule_status) as ds_course_schedule_status',
            $this->trainermodel->getJsonColumn('trainer', 'WHERE cd_course_schedule = "COURSE_SCHEDULE".cd_course_schedule'),
            $this->traineegrademodel->getJsonColumn('"TraineeGrade"', 'WHERE cd_course_schedule = "COURSE_SCHEDULE".cd_course_schedule'),

        );

        $this->fieldsUpd = array("cd_course_schedule", "cd_course_location", "dt_course_start", "dt_course_end", "cd_course", "ds_remark", "dt_deactivated", "dt_record", "cd_human_resource_recorder", "cd_course_exam_method", "ds_work_order", "ds_equipment_name", "ds_equipment_model", "fl_sample_ready", "fl_material_ready", "fl_fixture_ready","cd_human_resource_contacts", "cd_human_resource_witnesses_assistant", "cd_human_resource_test_engineer", "cd_course_schedule_status");

        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "stylecond" => "(CASE WHEN \"COURSE_SCHEDULE\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );

        parent::__construct();
    /*Elastic*/
        $this->fieldsforGridElastic = array(
         '("COURSE_SCHEDULE".cd_course_schedule) as "Course_Schedule_ID"',
           // ' ("COURSE_SCHEDULE".cd_course_location) as "Location Code"',
            '(select ds_course_category FROM "COURSE_CATEGORY" WHERE cd_course_category="COURSE".cd_course_category) as "Course Category"',
            '( select ds_course_location FROM "COURSE_LOCATION" WHERE cd_course_location =  "COURSE_SCHEDULE".cd_course_location) as "Location"',
            '("COURSE_SCHEDULE".dt_course_start)  as "Course Start"',
            '("COURSE_SCHEDULE".dt_course_end)  as "Course Finish"',         
           ' (ABS(EXTRACT (EPOCH from ("COURSE_SCHEDULE".dt_course_end)) - EXTRACT (EPOCH from ("COURSE_SCHEDULE".dt_course_start)) )/3600) as "Duration"',
            
         //   '("COURSE_SCHEDULE".cd_course ) as"Course Name"',
            '( select ds_course_number || \'-\'|| ds_course FROM "COURSE" WHERE cd_course =  "COURSE_SCHEDULE".cd_course) as "Course Name"',
             '("COURSE_SCHEDULE".ds_remark) as "Remarks"',
            '("COURSE_SCHEDULE".dt_deactivated) as "Active Status"',
           
            '( "COURSE_SCHEDULE".dt_record) as "Record"',
           //  '("COURSE_SCHEDULE".cd_human_resource_recorder as "Resource Recorder"',
            '( select ds_human_resource_full FROM "HUMAN_RESOURCE" WHERE cd_human_resource =  "COURSE_SCHEDULE".cd_human_resource_recorder) as "Resource Recorder"',
            // '("COURSE_SCHEDULE".cd_course_exam_method) as "Exam Method"',
            '( select ds_course_exam_method FROM "COURSE_EXAM_METHOD" WHERE cd_course_exam_method =  "COURSE_SCHEDULE".cd_course_exam_method) as " Exam Method"',
             '("COURSE_SCHEDULE".ds_work_order) as "Work Order" ',
           //  '("COURSE_SCHEDULE".ds_equipment_name)　as "Equipment Name"' ,
             '("COURSE_SCHEDULE".ds_equipment_model) as "Equpment Model"',
             '("COURSE_SCHEDULE".fl_sample_ready)as "Sample Status"',
             '("COURSE_SCHEDULE".fl_material_ready) as "Material Status"',
             '("COURSE_SCHEDULE".fl_fixture_ready) as "Fixture Status"',
           //  '("COURSE_SCHEDULE".cd_human_resource_contacts) as "Contacts" ',
            '( select ds_human_resource_full FROM "HUMAN_RESOURCE" WHERE cd_human_resource =  "COURSE_SCHEDULE".cd_human_resource_contacts) as "Contacts"',
            // '("COURSE_SCHEDULE").cd_human_resource_witnesses_assistant',
            '( select ds_human_resource_full FROM "HUMAN_RESOURCE" WHERE cd_human_resource =  "COURSE_SCHEDULE".cd_human_resource_witnesses_assistant) as "Witness Assistant"',
            //'( "COURSE_SCHEDULE").cd_human_resource_test_engineer',
            '( select ds_human_resource_full FROM "HUMAN_RESOURCE" WHERE cd_human_resource =  "COURSE_SCHEDULE".cd_human_resource_test_engineer) as "Test Engineers"',
            // '("COURSE_SCHEDULE").cd_course_schedule_status',
            '( select ds_course_status FROM "COURSE_STATUS" WHERE cd_course_status =  "COURSE_SCHEDULE".cd_course_schedule_status) as "Schedule Status"',
            '( select ds_human_resource_full FROM "HUMAN_RESOURCE" WHERE cd_human_resource =  "TRAINEE_GRADE".cd_human_resource_trainee) as "Trainee"',
            '( select ds_course_testing_result FROM "COURSE_TESTING_RESULT" WHERE cd_course_testing_result =  "TRAINEE_GRADE".cd_course_testing_result) as"Result"',
			
			 '("TRAINEE_GRADE").ds_remark as " Trainee Remark"',
			 '(SELECT array_to_string(array_agg(h.ds_human_resource), \', \') 
        from "HUMAN_RESOURCE" h, 
                training."COURSE_SCHEDULE_TRAINER" w 
        WHERE w.cd_course_schedule = training."COURSE_SCHEDULE" .cd_course_schedule 
          and h.cd_human_resource = w.cd_human_resource 
          and w.dt_deactivated IS NULL
          and h.dt_deactivated IS NULL) as "Trainers"'
          );

        $this->load->model('approval_steps_config_model', 'approvalmodel');
        $info = $this->approvalmodel->getApprovalSteps('RFQ', -1);

      // foreach ($info as $key => $value) {
      //    array_push($this->fieldsforGridElastic, ' ( SELECT min(x.dt_define) FROM "RFQ_APPROVAL_STEPS" x WHERE x.cd_rfq = "RFQ".cd_rfq AND x.cd_approval_steps_config =  ' . $value['cd_approval_steps_config'] . ' AND x.cd_approval_status = 1 ) as "' . $value['ds_approval_steps_config'] . '" ');
      // }


        $joinElastic = array(
           'JOIN "TRAINEE_GRADE" ON ( "TRAINEE_GRADE".cd_course_schedule = "COURSE_SCHEDULE".cd_course_schedule )',
           'JOIN "COURSE" ON ( "COURSE".cd_course = "COURSE_SCHEDULE".cd_course )',			
           'JOIN "COURSE_TESTING_RESULT"  ON ( "COURSE_TESTING_RESULT".cd_course_testing_result = "TRAINEE_GRADE".cd_course_testing_result )',
        );


        $this->retrOptionsElastic = array("fieldrecid" => '"TRAINEE_GRADE".cd_trainee_grade',
           
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGridElastic),
            "json" => true,
            'join' => $joinElastic,
          //  'forcedwhere' => " AND  \"RFQ\".cd_system_product_category = 1  "
        );

        parent::__construct();
    }  
}