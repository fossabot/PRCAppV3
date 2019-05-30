<?php

include_once APPPATH . "models/modelBasicExtend.php";

class test_item_model extends modelBasicExtend {

    function __construct() {

        $this->table = "TEST_ITEM";

        $this->pk_field = "cd_test_item";
        $this->ds_field = "ds_test_item";
        $this->prodCatUnique = 'N';

        $this->sequence_obj = '"TEST_ITEM_cd_test_item_seq"';

        $this->controller = 'tr/test_item';


        $this->fieldsforGrid = array(
            ' "TEST_ITEM".cd_test_item',
            ' "TEST_ITEM".ds_test_item',
            ' "TEST_ITEM".dt_deactivated',
            ' "TEST_ITEM".dt_record');
        
        $this->fieldsUpd = array("cd_test_item", "ds_test_item", "dt_deactivated", "dt_record",);


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "stylecond" => "(CASE WHEN \"TEST_ITEM\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );


        parent::__construct();
    }

}
