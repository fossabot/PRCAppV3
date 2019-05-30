<?php
include_once APPPATH . "models/modelBasicExtend.php";

class course_attend_confirmation_model extends modelBasicExtend
{


    function __construct()
    {

        $this->table = "COURSE_ATTEND_CONFIRMATION";

        $this->pk_field = "cd_course_attend_confirmation";
        $this->ds_field = "ds_course_attend_confirmation";
        $this->prodCatUnique = 'N';

        $this->sequence_obj = '"COURSE_ATTEND_CONFIRMATION_cd_course_attend_confirmation_seq"';

        $this->controller = 'training/course_attend_confirmation';


        $this->fieldsforGrid = array(


            ' "COURSE_ATTEND_CONFIRMATION".cd_course_attend_confirmation',
            ' "COURSE_ATTEND_CONFIRMATION".ds_course_attend_confirmation',
            ' "COURSE_ATTEND_CONFIRMATION".dt_deactivated');
        $this->fieldsUpd = array("cd_course_attend_confirmation", "ds_course_attend_confirmation", "dt_deactivated",);


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "stylecond" => "(CASE WHEN \"COURSE_ATTEND_CONFIRMATION\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );


        parent::__construct();


    }
}