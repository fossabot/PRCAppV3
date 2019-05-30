<?php
include_once APPPATH . "models/modelBasicExtend.php";

class course_model extends modelBasicExtend
{


    function __construct()
    {

        $this->table = "COURSE";

        $this->pk_field = "cd_course";
        $this->ds_field = "ds_course";
        $this->prodCatUnique = 'Y';

        $this->sequence_obj = 'training."COURSE_ITEM_cd_Course_Item_seq"';

        $this->controller = 'training/course';


        $this->fieldsforGrid = array(


            ' "COURSE".cd_course',
            ' "COURSE".cd_course_category',
            '( select ds_course_category FROM "COURSE_CATEGORY" WHERE cd_course_category =  "COURSE".cd_course_category) as ds_course_category',
            ' "COURSE".ds_course_number',
            ' "COURSE".ds_course',
            ' "COURSE".ds_target_trainee',
            ' "COURSE".nr_class_duration',
            ' "COURSE".nr_frequency_months',
            ' "COURSE".cd_course_status_material',
            '( select ds_course_status FROM "COURSE_STATUS" WHERE cd_course_status =  "COURSE".cd_course_status_material) as ds_course_status_material',
            ' "COURSE".cd_course_status',
            '( select ds_course_status FROM "COURSE_STATUS" WHERE cd_course_status =  "COURSE".cd_course_status) as ds_course_status',
            '(select array_to_string(array(SELECT trim(hrt.ds_human_resource_title) FROM public."HUMAN_RESOURCE_TITLE" hrt,training."COURSE_TITLE" ct where ct.cd_course="COURSE".cd_course and  hrt.cd_human_resource_title=ct.cd_human_resource_title order by 1),\'/\')  ) as ds_title_need_attend ',
            ' "COURSE".dt_deactivated',
            ' "COURSE".cd_system_product_category',
            ' "COURSE".dt_record');
        $this->fieldsUpd = array("cd_course", "cd_course_category", "ds_course_number", "ds_course", "ds_target_trainee", "nr_class_duration", "nr_frequency_months", "cd_course_status_material", "cd_course_status", "dt_deactivated", "dt_record",);


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "stylecond" => "(CASE WHEN \"COURSE\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );


        $this->fieldsForPLBase = array( $this->pk_field, // first always PK
            ' (  ds_course_number || \'-\' || ds_course )  as description '
        );        

        
        parent::__construct();


    }
    
    
    
}
