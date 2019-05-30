<?php

include_once APPPATH . "models/modelBasicExtend.php";

class schedule_test_status_model extends modelBasicExtend {

    function __construct() {

        $this->table = "SCHEDULE_TEST_STATUS";

        $this->pk_field = "cd_schedule_test_status";
        $this->ds_field = "ds_schedule_test_status";
        $this->prodCatUnique = 'N';

        $this->sequence_obj = '"SCHEDULE_TEST_STATUS_cd_schedule_test_status_seq"';

        $this->controller = 'schedule/schedule_test_status';


        $this->fieldsforGrid = array(
            ' "SCHEDULE_TEST_STATUS".cd_schedule_test_status',
            ' "SCHEDULE_TEST_STATUS".ds_schedule_test_status',
            ' "SCHEDULE_TEST_STATUS".dt_deactivated',
            ' "SCHEDULE_TEST_STATUS".dt_record');
        $this->fieldsUpd = array("cd_schedule_test_status", "ds_schedule_test_status", "dt_deactivated", "dt_record",);


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "stylecond" => "(CASE WHEN \"SCHEDULE_TEST_STATUS\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );


        parent::__construct();
    }

}
