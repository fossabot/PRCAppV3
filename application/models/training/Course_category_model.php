<?php
include_once APPPATH . "models/modelBasicExtend.php";

class course_category_model extends modelBasicExtend
{


    function __construct()
    {

        $this->table = "COURSE_CATEGORY";

        $this->pk_field = "cd_course_category";
        $this->ds_field = "ds_course_category";
        $this->prodCatUnique = 'N';

        $this->sequence_obj = '"COURSE_CATEGORY_cd_Course_Category_seq"';

        $this->controller = 'training/course_category';


        $this->fieldsforGrid = array(


            ' "COURSE_CATEGORY".cd_course_category',
            ' "COURSE_CATEGORY".ds_course_category',
            ' "COURSE_CATEGORY".dt_deactivated',
            ' "COURSE_CATEGORY".dt_record',
            ' "COURSE_CATEGORY".ds_abbreviation');
        $this->fieldsUpd = array("cd_course_category", "ds_course_category", "dt_deactivated", "dt_record", "ds_abbreviation",);


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "stylecond" => "(CASE WHEN \"COURSE_CATEGORY\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );


        parent::__construct();


    }
}