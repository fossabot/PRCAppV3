<?php

include_once APPPATH . "models/modelBasicExtend.php";

class project_build_schedule_tests_model extends modelBasicExtend {

    function __construct() {

        $this->table = "PROJECT_BUILD_SCHEDULE_TESTS";
        $this->hasDeactivate = false;
        $this->pk_field = '"PROJECT_BUILD_SCHEDULE_TESTS".cd_project_build_schedule_tests';
        $this->ds_field = "ds_project_build_schedule";
        $this->prodCatUnique = 'N';
        $this->mountUnique = 0;
        $this->sequence_obj = '"PROJECT_BUILD_SCHEDULE_TESTS_cd_project_build_schedule_test_seq"';

        $this->controller = 'schedule/project_build_schedule_tests';

        $this->makeSQLFields();

        $this->fieldsUpd = array(
            "nr_priority",
            "nr_headcount_requested_day",
            "nr_headcount_requested_night",
            "nr_headcount_allocated_day",
            "nr_headcount_allocated_night",
            "cd_tr_wi_data",
            "ds_test_item", "cd_test_unit", "cd_schedule_test_status", "dt_actual_start", "dt_actual_finish", "cd_project_build_schedule_tests", "dt_start", "dt_finish", "dt_est_start", "dt_est_finish", "cd_project_build_schedule",
            "cd_test_type", "cd_test_item", "cd_tests", "ds_specification", "ds_sample_description", "ds_extra_instruction", "fl_witness", "nr_sample_quantity", "nr_charger_quantity", "nr_power_pack_quantity", "nr_accessory_qty", "nr_goal",
            "nr_output", "fl_eol", "cd_human_resource_te", "cd_location", "ds_wi_section", "ds_wi");

        $this->joinsForGrid = array(' JOIN "PROJECT_BUILD_SCHEDULE" ON "PROJECT_BUILD_SCHEDULE".cd_project_build_schedule = "PROJECT_BUILD_SCHEDULE_TESTS".cd_project_build_schedule',
            'LEFT OUTER JOIN "TESTS" ON "TESTS".cd_tests = "PROJECT_BUILD_SCHEDULE_TESTS".cd_tests JOIN "PROJECT_BUILD" ON "PROJECT_BUILD".cd_project_build="PROJECT_BUILD_SCHEDULE".cd_project_build ');

        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            // "stylecond" => "(CASE WHEN \"PROJECT_BUILD_SCHEDULE_TESTS\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true,
            'join' => $this->joinsForGrid
        );



        parent::__construct();
    }

    public function makeSQLFields() {
        $this->load->model('schedule/project_build_schedule_tests_wo_model', 'womodel');

        $this->fieldsforGrid = array(
            ' "PROJECT_BUILD_SCHEDULE_TESTS".cd_project_build_schedule_tests',
            ' "PROJECT_BUILD_SCHEDULE_TESTS".cd_project_build_schedule',
            ' "PROJECT_BUILD_SCHEDULE_TESTS".cd_test_type',
            '( select ds_test_type FROM "TEST_TYPE" WHERE cd_test_type =  "PROJECT_BUILD_SCHEDULE_TESTS".cd_test_type) as ds_test_type',
            ' "PROJECT_BUILD_SCHEDULE_TESTS".cd_test_item',
            ' "PROJECT_BUILD_SCHEDULE_TESTS".cd_tests',
            ' "TESTS".ds_tests',
            '"PROJECT_BUILD_SCHEDULE_TESTS".cd_test_unit',
            '( select ds_test_unit FROM "TEST_UNIT" WHERE cd_test_unit =  "PROJECT_BUILD_SCHEDULE_TESTS".cd_test_unit) as ds_test_unit',
            ' "PROJECT_BUILD_SCHEDULE_TESTS".ds_specification',
            ' "PROJECT_BUILD_SCHEDULE_TESTS".ds_sample_description',
            ' "PROJECT_BUILD_SCHEDULE_TESTS".ds_extra_instruction',
            ' "PROJECT_BUILD_SCHEDULE_TESTS".fl_witness',
            ' "PROJECT_BUILD_SCHEDULE_TESTS".nr_sample_quantity',
            ' "PROJECT_BUILD_SCHEDULE_TESTS".nr_charger_quantity',
            ' "PROJECT_BUILD_SCHEDULE_TESTS".nr_power_pack_quantity',
            ' "PROJECT_BUILD_SCHEDULE_TESTS".nr_accessory_qty',
            " ( CASE WHEN EXISTS ( select 1 from schedule.\"PROJECT_BUILD_SCHEDULE_TESTS_DATES_HISTORY\" x where x.cd_project_build_schedule_tests = \"PROJECT_BUILD_SCHEDULE_TESTS\".cd_project_build_schedule_tests and x.ds_type_date = 'P' AND  x.dt_start is not null  and now() - dt_record > '1 day'::interval) THEN 'N' ELSE 'Y' END) as fl_can_change_dates",
            ' "PROJECT_BUILD_SCHEDULE_TESTS".nr_goal',
            ' "PROJECT_BUILD_SCHEDULE_TESTS".nr_output',
            ' "PROJECT_BUILD_SCHEDULE_TESTS".fl_eol',
            ' "PROJECT_BUILD_SCHEDULE_TESTS".dt_start',
            ' "PROJECT_BUILD_SCHEDULE_TESTS".dt_finish',
            ' "PROJECT_BUILD_SCHEDULE_TESTS".dt_est_start',
            ' "PROJECT_BUILD_SCHEDULE_TESTS".dt_est_finish',
            ' "PROJECT_BUILD_SCHEDULE_TESTS".dt_actual_start',
            ' "PROJECT_BUILD_SCHEDULE_TESTS".dt_actual_finish',
            ' "PROJECT_BUILD_SCHEDULE_TESTS".ds_test_item',
            ' "PROJECT_BUILD_SCHEDULE_TESTS".ds_wi',
            ' "PROJECT_BUILD_SCHEDULE_TESTS".ds_wi_section',
            ' "PROJECT_BUILD_SCHEDULE_TESTS".nr_headcount_requested_day',
            ' "PROJECT_BUILD_SCHEDULE_TESTS".nr_headcount_requested_night',
            ' "PROJECT_BUILD_SCHEDULE_TESTS".nr_headcount_allocated_day',
            ' "PROJECT_BUILD_SCHEDULE_TESTS".nr_headcount_allocated_night',
            ' "PROJECT_BUILD_SCHEDULE_TESTS".nr_priority',
            '( datedbtogrid("PROJECT_BUILD_SCHEDULE".dt_deactivated) ) as dt_deactivated_schedule',
            '( SELECT ds_human_resource_full FROM "HUMAN_RESOURCE" WHERE cd_human_resource = "PROJECT_BUILD_SCHEDULE".cd_human_resource_deactivated ) as ds_human_resource_deactivated ',
            ' "PROJECT_BUILD_SCHEDULE_TESTS".cd_location',
            '( select ds_location FROM "LOCATION" WHERE cd_location =  "PROJECT_BUILD_SCHEDULE_TESTS".cd_location) as ds_location',
            '( genUniqueCodeFromTwo( "PROJECT_BUILD_SCHEDULE_TESTS".cd_project_build_schedule_tests , ' . $this->mountUnique . ' ) ) as nr_unique',
            '( to_char("PROJECT_BUILD_SCHEDULE_TESTS".dt_est_start, \'yyyy-mm-dd\')) as ds_est_start',
            '( to_char("PROJECT_BUILD_SCHEDULE_TESTS".dt_est_finish, \'yyyy-mm-dd\')) as ds_est_finish',
            '( select ds_human_resource_full FROM "HUMAN_RESOURCE" WHERE cd_human_resource =  "PROJECT_BUILD_SCHEDULE".cd_human_resource_te) as ds_human_resource_te',
            ' "PROJECT_BUILD_SCHEDULE_TESTS".cd_schedule_test_status',
            '( select ds_schedule_test_status FROM "SCHEDULE_TEST_STATUS" WHERE cd_schedule_test_status =  "PROJECT_BUILD_SCHEDULE_TESTS".cd_schedule_test_status) as ds_schedule_test_status',
            ' "PROJECT_BUILD_SCHEDULE_TESTS".cd_tr_wi_data',
            '( select ds_test_procedure_name FROM "TR_WI_DATA" WHERE cd_tr_wi_data =  "PROJECT_BUILD_SCHEDULE_TESTS".cd_tr_wi_data) as ds_tr_wi_data',
            '("PROJECT_BUILD".ds_project_build_abbreviation || (CASE WHEN "PROJECT_BUILD".fl_allow_multiples = \'Y\' THEN "PROJECT_BUILD_SCHEDULE".nr_version::text ELSE \'\' END))   as ds_project_build_full',
            $this->womodel->getJsonColumn('wodata', ' WHERE "PROJECT_BUILD_SCHEDULE_TESTS_WO".cd_project_build_schedule_tests = "PROJECT_BUILD_SCHEDULE_TESTS".cd_project_build_schedule_tests')
        );
    }

    public function changeUnique($code) {

        $this->mountUnique = $code;
        $this->makeSQLFields();
        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            // "stylecond" => "(CASE WHEN \"PROJECT_BUILD_SCHEDULE_TESTS\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true,
            'join' => $this->joinsForGrid
        );
    }

}
