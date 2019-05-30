<?php
include_once APPPATH . "models/modelBasicExtend.php";

class wi_section_workflow_equipment_model extends modelBasicExtend {

    function __construct() {
        $this->table = "WI_SECTION_WORKFLOW_EQUIPMENT";
        $this->pk_field = "cd_wi_section_workflow_equipment";
        $this->ds_field = "ds_equipment_design";
        $this->hasDeactivate = false;
        $this->prodCatUnique = 'N';

        $this->sequence_obj = '"WI_SECTION_WORKFLOW_EQUIPMENT_cd_wi_section_workflow_equipm_seq"';
        $this->controller = 'schedule/wi_section_workflow_equipment';

        $this->fieldsforGrid = array(
            ' "WI_SECTION_WORKFLOW_EQUIPMENT".cd_wi_section_workflow_equipment',
            ' "WI_SECTION_WORKFLOW_EQUIPMENT".cd_equipment_design',
            '( select "EQUIPMENT_DESIGN".ds_equipment_description_full FROM "EQUIPMENT_DESIGN" WHERE cd_equipment_design =  "WI_SECTION_WORKFLOW_EQUIPMENT".cd_equipment_design) as ds_equipment_design',
            ' "WI_SECTION_WORKFLOW_EQUIPMENT".nr_ratio',
            ' "WI_SECTION_WORKFLOW_EQUIPMENT".ds_notes',
            ' "WI_SECTION_WORKFLOW_EQUIPMENT".dt_record',
            ' "WI_SECTION_WORKFLOW_EQUIPMENT".cd_wi_section_workflow',
            '( select ds_wi_section_workflow FROM "WI_SECTION_WORKFLOW" WHERE cd_wi_section_workflow =  "WI_SECTION_WORKFLOW_EQUIPMENT".cd_wi_section_workflow) as ds_wi_section_workflow');
        $this->fieldsUpd = array("cd_wi_section_workflow_equipment", "cd_equipment_design", "nr_ratio", "ds_notes", "dt_record", "cd_wi_section_workflow",);

        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            //"stylecond" => "(CASE WHEN \"WI_SECTION_WORKFLOW_EQUIPMENT\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );
        parent::__construct();

    }
}