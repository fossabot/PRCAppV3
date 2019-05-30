<?php
include_once APPPATH . "models/modelBasicExtend.php";

class wi_section_model extends modelBasicExtend {

    function __construct() {

        $this->table = "WI_SECTION";
        $this->pk_field = "cd_wi_section";
        $this->ds_field = "ds_wi_section";
        $this->prodCatUnique = 'N';

        $this->sequence_obj = '"WI_SECTION_cd_wi_section_seq"';
        $this->controller = 'schedule/wi_section';

        $this->fieldsforGrid = array(
            ' "WI_SECTION".cd_wi_section',
            ' "WI_SECTION".ds_wi_section',
            ' "WI_SECTION".ds_section_code',
            ' "WI_SECTION".cd_wi_revision',
            '( select nr_wi_revision FROM "WI_REVISION" WHERE cd_wi_revision = "WI_SECTION".cd_wi_revision) as nr_wi_revision',
            ' "WI_SECTION".dt_deactivated',
            ' "WI_SECTION".dt_record',
            ' "WI_SECTION".cd_test_type',
            '( select ds_test_type FROM "TEST_TYPE" WHERE cd_test_type =  "WI_SECTION".cd_test_type) as ds_test_type',
            ' "WI_SECTION".nr_wi_section_revision',
            ' "WI_SECTION".dt_approval',
            ' "WI_SECTION".cd_human_resource_approval',
            '( select ds_human_resource_full FROM "HUMAN_RESOURCE" WHERE cd_human_resource =  "WI_SECTION".cd_human_resource_approval) as ds_human_resource_approval');
        $this->fieldsUpd = array("cd_wi_section", "ds_wi_section", "ds_section_code", "cd_wi_revision", "dt_deactivated", "dt_record", "cd_test_type", "nr_wi_section_revision", "dt_approval", "cd_human_resource_approval",);

        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "stylecond" => "(CASE WHEN \"WI_SECTION\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );
        parent::__construct();

    }
}