<?php
include_once APPPATH . "models/modelBasicExtend.php";

class course_status_model extends modelBasicExtend
{


    function __construct()
    {

        $this->table = "COURSE_STATUS";

        $this->pk_field = "cd_course_status";
        $this->ds_field = "ds_course_status";
        $this->prodCatUnique = 'N';

        $this->sequence_obj = 'training."COURSE_STATUS_cd_course_status_seq"';

        $this->controller = 'training/course_status';


        $this->fieldsforGrid = array(


            ' "COURSE_STATUS".cd_course_status',
            ' "COURSE_STATUS".ds_course_status',
            ' "COURSE_STATUS".dt_deactivated',
            ' "COURSE_STATUS".dt_record');
        $this->fieldsUpd = array("cd_course_status", "ds_course_status", "dt_deactivated", "dt_record",);


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "stylecond" => "(CASE WHEN \"COURSE_STATUS\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );


        parent::__construct();


    }
}