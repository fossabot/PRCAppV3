<?php

include_once APPPATH . "models/modelBasicExtend.php";

class test_unit_model extends modelBasicExtend {

    function __construct() {

        $this->table = "TEST_UNIT";

        $this->pk_field = "cd_test_unit";
        $this->ds_field = "ds_test_unit";
        $this->prodCatUnique = 'N';

        $this->sequence_obj = '"TEST_UNIT_cd_test_unit_seq"';

        $this->controller = 'tr/test_unit';


        $this->fieldsforGrid = array(
            ' "TEST_UNIT".cd_test_unit',
            ' "TEST_UNIT".ds_test_unit',
            ' "TEST_UNIT".dt_deactivated',
            ' "TEST_UNIT".dt_record');
        $this->fieldsUpd = array("cd_test_unit", "ds_test_unit", "dt_deactivated", "dt_record",);


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "stylecond" => "(CASE WHEN \"TEST_UNIT\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );


        parent::__construct();
    }

}
