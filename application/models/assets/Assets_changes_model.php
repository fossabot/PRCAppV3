<?php

include_once APPPATH . "models/modelBasicExtend.php";

class assets_changes_model extends modelBasicExtend {

    function __construct() {

        $this->table = "ASSETS_CHANGES";

        $this->pk_field = "cd_assets_changes";
        $this->ds_field = "ds_assets";
        $this->prodCatUnique = 'N';
        $this->hasDeactivate = 'N';
        $this->orderByDefault = ' ORDER BY "ASSETS_CHANGES".dt_record DESC ';

        $this->sequence_obj = '"ASSETS_CHANGES_cd_assets_changes_seq"';

        $this->controller = 'assets/assets_changes';


        $this->fieldsforGrid = array(
            ' "ASSETS_CHANGES".cd_assets_changes',
            ' "ASSETS_CHANGES".cd_assets',
            '( select ds_assets FROM "ASSETS" WHERE cd_assets =  "ASSETS_CHANGES".cd_assets) as ds_assets',
            ' "ASSETS_CHANGES".dt_record',
            ' "ASSETS_CHANGES".cd_human_resource',
            '( select ds_human_resource_full FROM "HUMAN_RESOURCE" WHERE cd_human_resource =  "ASSETS_CHANGES".cd_human_resource) as ds_human_resource',
            ' "ASSETS_CHANGES".cd_assets_location_room',
            '( select ds_assets_location_room FROM "ASSETS_LOCATION_ROOM" WHERE cd_assets_location_room =  "ASSETS_CHANGES".cd_assets_location_room) as ds_assets_location_room',
            ' "ASSETS_CHANGES".ds_department_ref_number',
            ' "ASSETS_CHANGES".cd_human_resource_responsible',
            '( select ds_human_resource_full FROM "HUMAN_RESOURCE" WHERE cd_human_resource =  "ASSETS_CHANGES".cd_human_resource_responsible) as ds_human_resource_responsible',
            ' "ASSETS_CHANGES".ds_assets_number_old',
            ' "ASSETS_CHANGES".ds_remarks',
            ' "ASSETS_CHANGES".cd_department_cost_center',
            '( select ds_department_cost_center FROM "DEPARTMENT_COST_CENTER" WHERE cd_department_cost_center =  "ASSETS_CHANGES".cd_department_cost_center) as ds_department_cost_center');
        $this->fieldsUpd = array("cd_assets_changes", "cd_assets", "dt_record", "cd_human_resource", "cd_assets_location_room", "ds_department_ref_number", "cd_human_resource_responsible", "ds_assets_number_old", "ds_remarks", "cd_department_cost_center",);


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
//            "stylecond" => "(CASE WHEN \"ASSETS_CHANGES\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );


        parent::__construct();
    }

}
