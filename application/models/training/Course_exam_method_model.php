<?php
include_once APPPATH . "models/modelBasicExtend.php";

class course_exam_method_model extends modelBasicExtend
{


    function __construct()
    {

        $this->table = "COURSE_EXAM_METHOD";

        $this->pk_field = "cd_course_exam_method";
        $this->ds_field = "ds_course_exam_method";
        $this->prodCatUnique = 'N';

        $this->sequence_obj = 'training."COURSE_EXAM_WAY_cd_course_exam_way_seq"';

        $this->controller = 'training/course_exam_method';


        $this->fieldsforGrid = array(


            ' "COURSE_EXAM_METHOD".cd_course_exam_method',
            ' "COURSE_EXAM_METHOD".ds_course_exam_method',
            ' "COURSE_EXAM_METHOD".dt_deactivated',
            ' "COURSE_EXAM_METHOD".dt_record');
        $this->fieldsUpd = array("cd_course_exam_method", "ds_course_exam_method", "dt_deactivated", "dt_record",);


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "stylecond" => "(CASE WHEN \"COURSE_EXAM_METHOD\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );


        parent::__construct();


    }
}