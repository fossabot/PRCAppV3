<?php
include_once APPPATH . "models/modelBasicExtend.php";

class course_testing_result_model extends modelBasicExtend
{


    function __construct()
    {

        $this->table = "COURSE_TESTING_RESULT";

        $this->pk_field = "cd_course_testing_result";
        $this->ds_field = "ds_course_testing_result";
        $this->prodCatUnique = 'N';

        $this->sequence_obj = 'training."COURSE_TESTING_RESULT_cd_course_testing_result_seq"';

        $this->controller = 'training/course_testing_result';


        $this->fieldsforGrid = array(


            ' "COURSE_TESTING_RESULT".cd_course_testing_result',
            ' "COURSE_TESTING_RESULT".ds_course_testing_result',
            ' "COURSE_TESTING_RESULT".dt_deactivated',
            ' "COURSE_TESTING_RESULT".dt_record');
        $this->fieldsUpd = array("cd_course_testing_result", "ds_course_testing_result", "dt_deactivated", "dt_record",);


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "stylecond" => "(CASE WHEN \"COURSE_TESTING_RESULT\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );


        parent::__construct();


    }
}