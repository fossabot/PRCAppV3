<?php

include_once APPPATH . "models/modelBasicExtend.php";

class tests_model extends modelBasicExtend {

    function __construct() {

        $this->table = "TESTS";

        $this->pk_field = "cd_tests";
        $this->ds_field = "ds_tests";
        $this->prodCatUnique = 'N';

        $this->sequence_obj = '"TESTS_cd_tests_seq"';

        $this->controller = 'tr/tests';


        $this->fieldsforGrid = array(
            ' "TESTS".cd_tests',
            ' "TESTS".ds_tests',
            ' "TESTS".dt_deactivated',
            ' "TESTS".dt_record',
            ' "TESTS".cd_test_unit',
            '( select ds_test_unit FROM "TEST_UNIT" WHERE cd_test_unit =  "TESTS".cd_test_unit) as ds_test_unit');
        
        $this->fieldsUpd = array("cd_tests", "ds_tests", "dt_deactivated", "dt_record", 'cd_test_unit');


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "stylecond" => "(CASE WHEN \"TESTS\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );

        $this->fieldsForPLBaseDD = array( $this->pk_field, // first always PK
            $this->ds_field,            // second is always the description showing up. on the dropdown
            '"TESTS".cd_test_unit',
            '( select ds_test_unit FROM "TEST_UNIT" WHERE cd_test_unit =  "TESTS".cd_test_unit) as ds_test_unit' // aditional information to treat on the screen
        );


        parent::__construct();
    }

}
