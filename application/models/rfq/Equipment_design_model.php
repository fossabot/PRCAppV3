<?php

include_once APPPATH . "models/modelBasicExtend.php";

class equipment_design_model extends modelBasicExtend {

    function __construct() {

        $this->table = "EQUIPMENT_DESIGN";

        $this->pk_field = "cd_equipment_design";
        $this->ds_field = "ds_equipment_design";
        $this->prodCatUnique = 'Y';
        //$this->hasDeactivate = true;
        $this->sequence_obj = '"EQUIPMENT_DESIGN_cd_equipment_design_seq"';

        $this->controller = 'rfq/equipment_design';

        $this->fieldsforGrid = array(
            ' "EQUIPMENT_DESIGN".cd_equipment_design',
            ' ( "EQUIPMENT_DESIGN".cd_equipment_design ) as cd_equipment_design_image',
            ' "EQUIPMENT_DESIGN".ds_equipment_design',
            ' "EQUIPMENT_DESIGN".cd_equipment_design_sub_category',
            ' "EQUIPMENT_DESIGN_TYPE".ds_equipment_design_type',
            ' "EQUIPMENT_DESIGN_CATEGORY".ds_equipment_design_category',
            '"EQUIPMENT_DESIGN_SUB_CATEGORY".ds_equipment_design_sub_category',
            ' "EQUIPMENT_DESIGN".nr_series',
            ' "EQUIPMENT_DESIGN".cd_unit_measure',
            ' (SELECT ds_unit_measure FROM "UNIT_MEASURE" where cd_unit_measure = "EQUIPMENT_DESIGN".cd_unit_measure) as ds_unit_measure',
            ' "EQUIPMENT_DESIGN".ds_website',
            ' "EQUIPMENT_DESIGN".ds_remarks',
            ' "EQUIPMENT_DESIGN".nr_grade',
            ' "EQUIPMENT_DESIGN".cd_human_resource_applied_by',
            ' "EQUIPMENT_DESIGN".ds_equipment_design_code_alternate',
            
            ' "EQUIPMENT_DESIGN".ds_remarks_english',
            ' "EQUIPMENT_DESIGN".ds_technical_description',
            ' "EQUIPMENT_DESIGN".ds_technical_description_english',
            ' "EQUIPMENT_DESIGN".ds_brand',
            ' ( select count(1) from  docrep."EQUIPMENT_DESIGN_DOCUMENT_REPOSITORY" x WHERE x.cd_equipment_design = "EQUIPMENT_DESIGN".cd_equipment_design ) nr_attachment_count ',
            ' "EQUIPMENT_DESIGN".dt_applied',
            ' "EQUIPMENT_DESIGN".dt_deactivated',
            ' "EQUIPMENT_DESIGN_TYPE".fl_auto_add_serial',
            '( "EQUIPMENT_DESIGN".ds_equipment_code_full ) as ds_code');

        $this->fieldsUpd = array("cd_equipment_design", "dt_deactivated", "ds_equipment_design_code_alternate", "ds_equipment_design", "cd_equipment_design_sub_category", "nr_series", "cd_unit_measure", "ds_website", "ds_remarks", "nr_grade", "cd_human_resource_applied_by", "dt_applied", "ds_remarks_english", "ds_technical_description",  "ds_brand", "ds_technical_description_english");

        $join = array('INNER JOIN "EQUIPMENT_DESIGN_SUB_CATEGORY" ON ("EQUIPMENT_DESIGN_SUB_CATEGORY".cd_equipment_design_sub_category =  "EQUIPMENT_DESIGN".cd_equipment_design_sub_category) ',
            'INNER JOIN "EQUIPMENT_DESIGN_CATEGORY"     ON ("EQUIPMENT_DESIGN_CATEGORY".cd_equipment_design_category         =  "EQUIPMENT_DESIGN_SUB_CATEGORY".cd_equipment_design_category) ',
            'INNER JOIN "EQUIPMENT_DESIGN_TYPE"         ON ("EQUIPMENT_DESIGN_TYPE".cd_equipment_design_type                 =  "EQUIPMENT_DESIGN_CATEGORY".cd_equipment_design_type) '
        );

        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true,
            "stylecond" => "(CASE WHEN \"EQUIPMENT_DESIGN\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            'join' => $join
        );

        
        $this->fieldsForPLBaseDD = array( $this->pk_field, // first always PK
            ' "EQUIPMENT_DESIGN".ds_equipment_description_full ',            // second is always the description showing up. on the dropdown
            ' "EQUIPMENT_DESIGN".cd_unit_measure',
             ' ( "EQUIPMENT_DESIGN".ds_equipment_description_full ) as ds_equipment_design' ,  
            ' (SELECT ds_unit_measure FROM "UNIT_MEASURE" where cd_unit_measure = "EQUIPMENT_DESIGN".cd_unit_measure) as ds_unit_measure',
            ' "EQUIPMENT_DESIGN".ds_website',
            ' "EQUIPMENT_DESIGN".ds_technical_description',
            ' "EQUIPMENT_DESIGN".ds_brand'
        );


        $this->fieldsForPLBase = array($this->pk_field, // first always PK
            ' ( "EQUIPMENT_DESIGN".ds_equipment_description_full ) as description ',            // second is always the description showing up. on the dropdown
            ' "EQUIPMENT_DESIGN".cd_unit_measure',
            ' (SELECT ds_unit_measure FROM "UNIT_MEASURE" where cd_unit_measure = "EQUIPMENT_DESIGN".cd_unit_measure) as ds_unit_measure',
            ' "EQUIPMENT_DESIGN".ds_website',
            ' "EQUIPMENT_DESIGN".ds_technical_description',
            ' "EQUIPMENT_DESIGN".ds_brand'
        );


        parent::__construct();
    }

}
