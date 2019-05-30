<?php

include_once APPPATH . "models/modelBasicExtend.php";

class project_build_tests_model extends modelBasicExtend {

    function __construct() {

        $this->table = "PROJECT_BUILD_TESTS";

        $this->pk_field = "cd_project_build_tests";
        $this->ds_field = "ds_project_build_schedule";
        $this->prodCatUnique = 'N';

        $this->sequence_obj = '"PROJECT_BUILD_TESTS_cd_project_build_tests_seq"';

        $this->controller = 'schedule/project_build_tests';


        $this->fieldsforGrid = array(
            ' "PROJECT_BUILD_TESTS".cd_project_build_tests',
            ' "PROJECT_BUILD_TESTS".cd_project_build_schedule',
            '( select  FROM "PROJECT_BUILD_SCHEDULE" WHERE cd_project_build_schedule =  "PROJECT_BUILD_TESTS".cd_project_build_schedule) as ds_project_build_schedule',
            ' "PROJECT_BUILD_TESTS".cd_test_type',
            '( select ds_test_type FROM "TEST_TYPE" WHERE cd_test_type =  "PROJECT_BUILD_TESTS".cd_test_type) as ds_test_type',
            ' "PROJECT_BUILD_TESTS".cd_tests',
            '( select ds_tests FROM "TESTS" WHERE cd_tests =  "PROJECT_BUILD_TESTS".cd_tests) as ds_tests',
            ' "PROJECT_BUILD_TESTS".cd_test_unit',
            '( select ds_test_unit FROM "TEST_UNIT" WHERE cd_test_unit =  "PROJECT_BUILD_TESTS".cd_test_unit) as ds_test_unit',
            ' "PROJECT_BUILD_TESTS".nr_sample_quantity',
            ' "PROJECT_BUILD_TESTS".nr_goal',
            ' "PROJECT_BUILD_TESTS".nr_output');
        $this->fieldsUpd = array("cd_project_build_tests", "cd_project_build_schedule", "cd_test_type", "cd_tests", "cd_test_unit", "nr_sample_quantity", "nr_goal", "nr_output",);


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "stylecond" => "(CASE WHEN \"PROJECT_BUILD_TESTS\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );


        parent::__construct();
    }

}
