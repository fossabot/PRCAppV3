<?php

include_once APPPATH . "models/modelBasicExtend.php";

class project_build_schedule_tests_workers_model extends modelBasicExtend {

    function __construct() {

        $this->table = "PROJECT_BUILD_SCHEDULE_TESTS_WORKERS";

        $this->pk_field = "cd_project_build_schedule_tests_workers";
        $this->ds_field = "ds_project_build_schedule_tests";
        $this->prodCatUnique = 'N';
        $this->hasDeactivate = false;
        $this->sequence_obj = '"PROJECT_BUILD_SCHEDULE_TESTS__cd_project_build_schedule_tes_seq"';

        $this->controller = 'schedule/project_build_schedule_tests_workers';


        $this->fieldsforGrid = array(
            ' "PROJECT_BUILD_SCHEDULE_TESTS_WORKERS".cd_project_build_schedule_tests_workers',
            ' "PROJECT_BUILD_SCHEDULE_TESTS_WORKERS".cd_project_build_schedule_tests',
            ' "PROJECT_BUILD_SCHEDULE_TESTS_WORKERS".dt_start',
            ' "PROJECT_BUILD_SCHEDULE_TESTS_WORKERS".dt_finish',
            ' "PROJECT_BUILD_SCHEDULE_TESTS_WORKERS".nr_workers',
            ' "PROJECT_BUILD_SCHEDULE_TESTS_WORKERS".dt_record', 
            ' (to_char("PROJECT_BUILD_SCHEDULE_TESTS_WORKERS".dt_start, \'yyyymmdd\')) as ds_order ' 
            );
        
        $this->orderByDefault = ' ORDER BY cd_project_build_schedule_tests, ds_order';
        
        $this->fieldsUpd = array("cd_project_build_schedule_tests_workers", "cd_project_build_schedule_tests", "dt_start", "dt_finish", "nr_workers", "dt_record",);

        $join = array(' JOIN "PROJECT_BUILD_SCHEDULE_TESTS" ON  "PROJECT_BUILD_SCHEDULE_TESTS".cd_project_build_schedule_tests = "PROJECT_BUILD_SCHEDULE_TESTS_WORKERS".cd_project_build_schedule_tests',
                      ' JOIN "PROJECT_BUILD_SCHEDULE" ON  "PROJECT_BUILD_SCHEDULE".cd_project_build_schedule = "PROJECT_BUILD_SCHEDULE_TESTS".cd_project_build_schedule');

        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            //"stylecond" => "(CASE WHEN \"PROJECT_BUILD_SCHEDULE_TESTS_WORKERS\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true,
            'join' => $join 
        );


        parent::__construct();
    }

}
