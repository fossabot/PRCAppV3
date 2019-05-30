<?php
include_once APPPATH . "models/modelBasicExtend.php";

class wi_section_workflow_model extends modelBasicExtend {


    function __construct() {

        $this->table = "WI_SECTION_WORKFLOW";

        $this->pk_field = "cd_wi_section_workflow";
        $this->ds_field = "ds_wi_section_workflow";
        $this->prodCatUnique = 'N';

        $this->sequence_obj = 'schedule."WI_SECTION_WORKFLOW_cd_wi_section_workflow_seq"';

        $this->controller = 'schedule/wi_section_workflow';

        $this->fieldsforGrid = array(
            ' "WI_SECTION_WORKFLOW".cd_wi_section_workflow',
            ' "WI_SECTION_WORKFLOW".ds_wi_section_workflow',
            ' "WI_SECTION_WORKFLOW".ds_wi_section_workflow_code',
            ' "WI_SECTION_WORKFLOW".cd_wi_section_revision_type',
            '( select ds_wi_section_revision_type FROM "WI_SECTION_REVISION_TYPE" WHERE cd_wi_section_revision_type =  "WI_SECTION_WORKFLOW".cd_wi_section_revision_type) as ds_wi_section_revision_type',
            ' "WI_SECTION_WORKFLOW".dt_deactivated',
            ' "WI_SECTION_WORKFLOW".dt_record',
            ' "WI_SECTION_WORKFLOW".cd_wi_section',
            '( select ds_section_code FROM "WI_SECTION" WHERE cd_wi_section =  "WI_SECTION_WORKFLOW".cd_wi_section) as ds_section_code',
            ' "WI_SECTION_WORKFLOW".dt_approval',
            ' "WI_SECTION_WORKFLOW".cd_test_unit',
            '( select ds_test_unit FROM "TEST_UNIT" WHERE cd_test_unit =  "WI_SECTION_WORKFLOW".cd_test_unit) as ds_test_unit',
            ' "WI_SECTION_WORKFLOW".nr_man_power',
            ' "WI_SECTION_WORKFLOW".ds_specification',
            ' "WI_SECTION_WORKFLOW".ds_equipment_description',
            ' "WI_SECTION_WORKFLOW".nr_wi_section_workflow_revision',
            ' "WI_SECTION_WORKFLOW".nr_wi_section_workflow_revision_minor',
            ' "WI_SECTION_WORKFLOW".cd_project_model',
            '( select ds_project_model FROM "PROJECT_MODEL" WHERE cd_project_model =  "WI_SECTION_WORKFLOW".cd_project_model) as ds_project_model',
            ' "WI_SECTION_WORKFLOW".cd_project_product',
            '( select ds_project_product FROM "PROJECT_PRODUCT" WHERE cd_project_product =  "WI_SECTION_WORKFLOW".cd_project_product) as ds_project_product');
        $this->fieldsUpd = array("cd_wi_section_workflow", "ds_wi_section_workflow", "ds_wi_section_workflow_code", "cd_wi_section_revision_type", "dt_deactivated", "dt_record", "cd_wi_section", "dt_approval", "cd_test_unit", "nr_man_power", "ds_specification", "ds_equipment_description", "nr_wi_section_workflow_revision", "nr_wi_section_workflow_revision_minor", "cd_project_model", "cd_project_product",);

        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "stylecond" => "(CASE WHEN \"WI_SECTION_WORKFLOW\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );
        parent::__construct();

    }
}