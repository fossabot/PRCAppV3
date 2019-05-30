<?php
include_once APPPATH . "models/modelBasicExtend.php";

class project_build_schedule_tests_purchase_items_model extends modelBasicExtend
{


    function __construct()
    {

        $this->table = "PROJECT_BUILD_SCHEDULE_TESTS_PURCHASE_ITEMS";

        $this->pk_field = "cd_project_build_schedule_tests_purchase_items";
        $this->ds_field = "ds_equipment_design";
        $this->prodCatUnique = 'N';

        $this->sequence_obj = '"PROJECT_BUILD_SCHEDULE_TESTS__cd_project_build_schedule_te_seq4"';

        $this->controller = 'schedule/project_build_schedule_tests_purchase_items';


        $this->fieldsforGrid = array(

            ' "PROJECT_BUILD_SCHEDULE_TESTS_PURCHASE_ITEMS".cd_project_build_schedule_tests_purchase_items',
            ' "PROJECT_BUILD_SCHEDULE_TESTS_PURCHASE_ITEMS".cd_equipment_design',
            '( select ds_equipment_design FROM "EQUIPMENT_DESIGN" WHERE cd_equipment_design =  "PROJECT_BUILD_SCHEDULE_TESTS_PURCHASE_ITEMS".cd_equipment_design) as ds_equipment_design',
            '( select nr_part_number FROM "RFQ_PART_NUMBER_VIEW" WHERE cd_equipment_design =  "PROJECT_BUILD_SCHEDULE_TESTS_PURCHASE_ITEMS".cd_equipment_design) as nr_part_number',
            ' "PROJECT_BUILD_SCHEDULE_TESTS_PURCHASE_ITEMS".nr_requested_quantity_to_buy',
            '(datetimedbtogrid("PROJECT_BUILD_SCHEDULE_TESTS_PURCHASE_ITEMS".dt_released_to_buy::timestamp)) as dt_released_to_buy',
            ' "PROJECT_BUILD_SCHEDULE_TESTS_PURCHASE_ITEMS".nr_sample_quantity',
            ' "PROJECT_BUILD_SCHEDULE_TESTS_PURCHASE_ITEMS".nr_goal',
            ' "PROJECT_BUILD_SCHEDULE_TESTS_PURCHASE_ITEMS".dt_deadline',
            ' "PROJECT_BUILD_SCHEDULE_TESTS_PURCHASE_ITEMS".cd_project_build_schedule_tests',
            '( select ds_specification FROM "PROJECT_BUILD_SCHEDULE_TESTS" WHERE cd_project_build_schedule_tests =  "PROJECT_BUILD_SCHEDULE_TESTS_PURCHASE_ITEMS".cd_project_build_schedule_tests) as ds_project_build_schedule_tests',
            ' "PROJECT_BUILD_SCHEDULE_TESTS_PURCHASE_ITEMS".cd_tr_wi_data',
            '( select ds_test_procedure_name FROM "TR_WI_DATA" WHERE cd_tr_wi_data =  "PROJECT_BUILD_SCHEDULE_TESTS_PURCHASE_ITEMS".cd_tr_wi_data) as ds_tr_wi_data',
            ' "PROJECT_BUILD_SCHEDULE_TESTS_PURCHASE_ITEMS".nr_calculated_quantity',
            ' "PROJECT_BUILD_SCHEDULE_TESTS_PURCHASE_ITEMS".cd_human_resource_record',
            '( select ds_human_resource_full FROM "HUMAN_RESOURCE" WHERE cd_human_resource =  "PROJECT_BUILD_SCHEDULE_TESTS_PURCHASE_ITEMS".cd_human_resource_record) as ds_human_resource_record',
            '("PROJECT_BUILD".ds_project_build_abbreviation || (CASE WHEN "PROJECT_BUILD".fl_allow_multiples = \'Y\' THEN "PROJECT_BUILD_SCHEDULE".nr_version::text ELSE \'\' END))   as ds_project_build_full',
            '( select ds_test_type FROM "TEST_TYPE" WHERE cd_test_type =  "PROJECT_BUILD_SCHEDULE_TESTS".cd_test_type) as ds_test_type',
            ' "PROJECT_BUILD_SCHEDULE_TESTS".ds_test_item',

            ' "PROJECT_BUILD_SCHEDULE_TESTS_PURCHASE_ITEMS".dt_record',

        );

        $this->joinsForGrid = array(' JOIN "PROJECT_BUILD_SCHEDULE_TESTS"  on "PROJECT_BUILD_SCHEDULE_TESTS".cd_project_build_schedule_tests="PROJECT_BUILD_SCHEDULE_TESTS_PURCHASE_ITEMS".cd_project_build_schedule_tests
                JOIN "PROJECT_BUILD_SCHEDULE" on "PROJECT_BUILD_SCHEDULE".cd_project_build_schedule = "PROJECT_BUILD_SCHEDULE_TESTS".cd_project_build_schedule
                JOIN   "PROJECT_BUILD" on "PROJECT_BUILD".cd_project_build="PROJECT_BUILD_SCHEDULE".cd_project_build');

        $this->fieldsUpd = array("cd_project_build_schedule_tests_purchase_items", "cd_equipment_design", "nr_requested_quantity_to_buy", "dt_released_to_buy", "nr_sample_quantity", "nr_goal", "dt_deadline", "cd_project_build_schedule_tests", "cd_tr_wi_data", "nr_calculated_quantity", "cd_human_resource_record", "dt_record",);


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            //"stylecond" => "(CASE WHEN \"PROJECT_BUILD_SCHEDULE_TESTS_PURCHASE_ITEMS\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true,
            'join' => $this->joinsForGrid
        );


        parent::__construct();


    }


}