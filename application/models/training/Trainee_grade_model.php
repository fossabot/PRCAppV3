<?php
include_once APPPATH . "models/modelBasicExtend.php";

class trainee_grade_model extends modelBasicExtend
{


    function __construct()
    {

        $this->table = "TRAINEE_GRADE";

        $this->pk_field = "cd_trainee_grade";
        $this->ds_field = "ds_course_schedule";
        $this->prodCatUnique = 'N';

//        $this->sequence_obj = ''training . "TRAINEE_GRADE_cd_trainee_grade_seq"'::text';
    $this->sequence_obj = 'training."TRAINEE_GRADE_cd_trainee_grade_seq"';
     $this->controller = 'training/trainee_grade';


     $this->fieldsforGrid = array(


         ' "TRAINEE_GRADE".cd_trainee_grade',
         ' "TRAINEE_GRADE".cd_course_schedule',
         ' "TRAINEE_GRADE".cd_human_resource_trainee',
         '( select ds_human_resource_full FROM "HUMAN_RESOURCE" WHERE cd_human_resource =  "TRAINEE_GRADE".cd_human_resource_trainee) as ds_human_resource_trainee',
         '( select nr_staff_number FROM "HUMAN_RESOURCE" WHERE cd_human_resource =  "TRAINEE_GRADE".cd_human_resource_trainee) as nr_staff_number',
         '( select b.ds_department FROM "HUMAN_RESOURCE" a,"DEPARTMENT" b WHERE a.cd_department=b.cd_department and a.cd_human_resource = "TRAINEE_GRADE".cd_human_resource_trainee) as ds_department',
         '( select b.ds_team FROM "HUMAN_RESOURCE" a,"TEAM" b WHERE a.cd_team=b.cd_team and a.cd_human_resource = "TRAINEE_GRADE".cd_human_resource_trainee) as ds_team',
         '( select b.ds_roles FROM "HUMAN_RESOURCE" a,"tti"."ROLES" b WHERE a.cd_roles=b.cd_roles and a.cd_human_resource = "TRAINEE_GRADE".cd_human_resource_trainee) as ds_roles',
         '( select b.ds_human_resource_title FROM "HUMAN_RESOURCE" a,"HUMAN_RESOURCE_TITLE" b WHERE a.cd_human_resource_title=b.cd_human_resource_title and a.cd_human_resource = "TRAINEE_GRADE".cd_human_resource_trainee) as ds_human_resource_title',
         ' "TRAINEE_GRADE".cd_course_testing_result',
         '( select ds_course_testing_result FROM "COURSE_TESTING_RESULT" WHERE cd_course_testing_result =  "TRAINEE_GRADE".cd_course_testing_result) as ds_course_testing_result',
         ' "TRAINEE_GRADE".dt_deactivated',
         ' "TRAINEE_GRADE".dt_record',
         ' "TRAINEE_GRADE".ds_remark',
         ' "TRAINEE_GRADE".cd_human_resource_recorder',
         '( select ds_human_resource_full FROM "HUMAN_RESOURCE" WHERE cd_human_resource =  "TRAINEE_GRADE".cd_human_resource_recorder) as ds_human_resource_recorder',
         ' "TRAINEE_GRADE".cd_course_attend_confirmation',
        '( select ds_course_attend_confirmation FROM "COURSE_ATTEND_CONFIRMATION" WHERE cd_course_attend_confirmation =  "TRAINEE_GRADE".cd_course_attend_confirmation) as ds_course_attend_confirmation' );

      $this->fieldsUpd = array ( "cd_trainee_grade", "cd_course_schedule", "cd_human_resource_trainee", "cd_course_testing_result", "dt_deactivated", "dt_record", "ds_remark", "cd_human_resource_recorder", "cd_course_attend_confirmation",  );

 
        
                $this->retrOptions = array("fieldrecid" => $this->pk_field,
                    "stylecond" => "(CASE WHEN \"TRAINEE_GRADE\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
                    "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
                    "json" => true
                );
                       

          parent::__construct();
    

    }
}