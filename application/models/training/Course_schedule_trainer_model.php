<?php
include_once APPPATH . "models/modelBasicExtend.php";

class course_schedule_trainer_model extends modelBasicExtend
{


    function __construct()
    {

        $this->table = "COURSE_SCHEDULE_TRAINER";

        $this->pk_field = "cd_course_schedule_trainer";
        $this->ds_field = "ds_human_resource";
        $this->prodCatUnique = 'N';

        $this->sequence_obj = '"COURSE_SCHEDULE_TRAINER_cd_course_schedule_trainer_seq"';

        $this->controller = 'training/course_schedule_trainer';


        $this->fieldsforGrid = array(


            ' "COURSE_SCHEDULE_TRAINER".cd_course_schedule_trainer',
            ' "COURSE_SCHEDULE_TRAINER".cd_human_resource',
            '( select ds_human_resource_full FROM "HUMAN_RESOURCE" WHERE cd_human_resource =  "COURSE_SCHEDULE_TRAINER".cd_human_resource) as ds_human_resource',
            '( select nr_staff_number FROM "HUMAN_RESOURCE" WHERE cd_human_resource =  "COURSE_SCHEDULE_TRAINER".cd_human_resource) as nr_staff_number',
            ' "COURSE_SCHEDULE_TRAINER".dt_record',
            ' "COURSE_SCHEDULE_TRAINER".cd_course_schedule',
            '( select ds_remark FROM "COURSE_SCHEDULE" WHERE cd_course_schedule =  "COURSE_SCHEDULE_TRAINER".cd_course_schedule) as ds_course_schedule',
            ' "COURSE_SCHEDULE_TRAINER".dt_deactivated');
        $this->fieldsUpd = array("cd_course_schedule_trainer", "cd_human_resource", "dt_record", "cd_course_schedule", "dt_deactivated",);


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "stylecond" => "(CASE WHEN \"COURSE_SCHEDULE_TRAINER\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );


        parent::__construct();


    }
}