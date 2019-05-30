<?php

include_once APPPATH . "models/modelBasicExtend.php";

class hr_attendance_base_model extends modelBasicExtend {

    function __construct() {

        $this->table = "HR_ATTENDANCE_BASE";

        $this->pk_field = "cd_hr_attendance_base";
        $this->ds_field = "ds_staff_number";
        $this->prodCatUnique = 'N';
        $this->hasDeactivate = 'N';
        $this->sequence_obj = '"HR_ATTENDANCE_BASE_cd_hr_attendance_base_seq"';

        $this->controller = 'tti/hr_attendance_base';


        $this->fieldsforGrid = array(
            ' "HR_ATTENDANCE_BASE".cd_hr_attendance_base',
            ' "HR_ATTENDANCE_BASE".nr_staff_number',
            'LPAD("HR_ATTENDANCE_BASE".nr_staff_number::text,6, \'0\') as ds_staff_number',
            ' "HR_ATTENDANCE_BASE".ds_staff_name',
            ' "HR_ATTENDANCE_BASE".ds_department',
            ' "HR_ATTENDANCE_BASE".dt_attend_date',
            ' "HR_ATTENDANCE_BASE".ds_shift',
            ' "HR_ATTENDANCE_BASE".ds_abnormal_reason',
            ' ( datetimedbtogrid("HR_ATTENDANCE_BASE".dt_start_one::timestamp) ) as dt_start_one',
            ' ( datetimedbtogrid("HR_ATTENDANCE_BASE".dt_end_one::timestamp) ) as dt_end_one',
            ' ( datetimedbtogrid("HR_ATTENDANCE_BASE".dt_start_two::timestamp) ) as dt_start_two',
            ' ( datetimedbtogrid("HR_ATTENDANCE_BASE".dt_end_two::timestamp) ) as dt_end_two',
            ' ( datetimedbtogrid("HR_ATTENDANCE_BASE".dt_start_three::timestamp) ) as dt_start_three',
            ' ( datetimedbtogrid("HR_ATTENDANCE_BASE".dt_end_three::timestamp) ) as dt_end_three',
            ' ( datetimedbtogrid("HR_ATTENDANCE_BASE".dt_start_four::timestamp) ) as dt_start_four',
            ' ( datetimedbtogrid("HR_ATTENDANCE_BASE".dt_end_four::timestamp) ) as dt_end_four',
            ' ( datetimedbtogrid("HR_ATTENDANCE_BASE".dt_start_five::timestamp) ) as dt_start_five',
            ' ( datetimedbtogrid("HR_ATTENDANCE_BASE".dt_end_five::timestamp) ) as dt_end_five',
            ' ( datetimedbtogrid("HR_ATTENDANCE_BASE".dt_end_five::timestamp) ) as dt_start_six',
            ' ( datetimedbtogrid("HR_ATTENDANCE_BASE".dt_end_six::timestamp) ) as dt_end_six',
            ' "HR_ATTENDANCE_BASE".dt_join_date',
            ' "HR_ATTENDANCE_BASE".ds_faceid_reason');
        $this->fieldsUpd = array("cd_hr_attendance_base", "nr_staff_number", "ds_staff_name", "ds_department", "dt_attend_date","ds_shift", "ds_abnormal_reason", "dt_start_one", "dt_end_one", "dt_start_two", "dt_end_two", "dt_start_three", "dt_end_three", "dt_start_four", "dt_end_four", "dt_start_five","dt_end_five","dt_start_six","dt_end_six","dt_join_date","ds_faceid_reason");


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            //"stylecond"  => "(CASE WHEN \"HR_ATTENDANCE_BASE\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );


        parent::__construct();
    }

}
