<?php
include_once APPPATH . "models/modelBasicExtend.php";

class course_title_model extends modelBasicExtend
{


    function __construct()
    {

        $this->table = "COURSE_TITLE";

        $this->pk_field = "cd_course_title";
        $this->ds_field = "ds_course";
        $this->prodCatUnique = 'N';

        $this->sequence_obj = '"COURSE_TITLE_cd_course_title_seq"';

        $this->controller = 'training/course_title';


        $this->fieldsforGrid = array(


            ' "COURSE_TITLE".cd_course_title',
            ' "COURSE_TITLE".cd_course',
            '( select ds_course_number FROM "COURSE" WHERE cd_course =  "COURSE_TITLE".cd_course) as ds_course',
            ' "COURSE_TITLE".cd_human_resource_title',
            '( select ds_human_resource_title FROM "HUMAN_RESOURCE_TITLE" WHERE cd_human_resource_title =  "COURSE_TITLE".cd_human_resource_title) as ds_human_resource_title',
            ' "COURSE_TITLE".dt_record',
            ' "COURSE_TITLE".dt_deactivated');
        $this->fieldsUpd = array("cd_course_title", "cd_course", "cd_human_resource_title", "dt_record", "dt_deactivated",);


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "stylecond" => "(CASE WHEN \"COURSE_TITLE\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );


        parent::__construct();


    }
}