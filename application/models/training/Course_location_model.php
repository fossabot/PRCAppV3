<?php
include_once APPPATH . "models/modelBasicExtend.php";

class course_location_model extends modelBasicExtend
{


    function __construct()
    {

        $this->table = "COURSE_LOCATION";

        $this->pk_field = "cd_course_location";
        $this->ds_field = "ds_course_location";
        $this->prodCatUnique = 'N';

//        $this->sequence_obj = ''training . "COURSE_LOCATION_cd_course_location_seq"'::text';
        $this->sequence_obj = 'training."COURSE_LOCATION_cd_course_location_seq"';
     $this->controller = 'training/course_location';


     $this->fieldsforGrid = array(


         ' "COURSE_LOCATION".cd_course_location',
         ' "COURSE_LOCATION".ds_course_location',
         ' "COURSE_LOCATION".dt_deactivated',
         ' "COURSE_LOCATION".dt_record',
         ' "COURSE_LOCATION".nr_seats');
      $this->fieldsUpd = array("cd_course_location", "ds_course_location", "dt_deactivated", "dt_record", "nr_seats",);
 
        
                $this->retrOptions = array("fieldrecid" => $this->pk_field,
                    "stylecond" => "(CASE WHEN \"COURSE_LOCATION\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
                    "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
                    "json" => true
                );
                       

          parent::__construct();
    

    }
}