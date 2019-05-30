<?php

include_once APPPATH . "models/modelBasicExtend.php";

class assets_model extends modelBasicExtend {

    function __construct() {

        $this->table = "ASSETS";

        $this->pk_field = "cd_assets";
        $this->ds_field = "ds_assets";
        $this->prodCatUnique = 'N';
        $this->hasDeactivate = FALSE;

        $this->load->model("assets/assets_changes_model", "changemodel", TRUE);

        $this->sequence_obj = 'assets."ASSETS_cd_assets_seq"';

        $this->controller = 'assets/assets';


        $this->fieldsforGrid = array(
            ' "ASSETS".cd_assets',
            ' "ASSETS".ds_assets',
            ' "ASSETS".ds_assets_number',
            ' "ASSETS".cd_assets_book',
            '( select ds_assets_book FROM "ASSETS_BOOK" WHERE cd_assets_book =  "ASSETS".cd_assets_book) as ds_assets_book',
            ' "ASSETS".dt_asset',
            ' "ASSETS".ds_pr_contract_number',
            ' "ASSETS".cd_assets_location_room',
            '(  "ASSETS_LOCATION".ds_assets_location || \' - \' || "ASSETS_LOCATION_ROOM".ds_assets_location_room ) as ds_assets_location_room ',
            ' "ASSETS".ds_department_ref_number',
            ' "ASSETS".nr_qty',
            ' "ASSETS".cd_department_cost_center',
            '( select ds_department_cost_center FROM "DEPARTMENT_COST_CENTER" WHERE cd_department_cost_center =  "ASSETS".cd_department_cost_center) as ds_department_cost_center',
            ' "ASSETS".cd_human_resource_responsible',
            '( select ds_human_resource_full FROM "HUMAN_RESOURCE" WHERE cd_human_resource =  "ASSETS".cd_human_resource_responsible) as ds_human_resource_responsible',
            ' "ASSETS".ds_assets_number_old',
            ' "ASSETS".ds_remarks',
            ' "ASSETS".nr_initial_value',
            ' "ASSETS".nr_monthly_depreciation',
            ' "ASSETS".dt_start_monthly_depreciation',
            ' "ASSETS".ds_category',
            ' "ASSETS".dt_end_monthly_depreciation',
            ' "ASSETS".dt_asset_scrap',
            ' "ASSETS".nr_scrap_value' ,
            $this->changemodel->getJsonColumn('ds_history_json', 'WHERE "ASSETS_CHANGES".cd_assets =  "ASSETS".cd_assets ')
        );

        $this->fieldsUpd = array("cd_assets", "ds_assets", "ds_assets_number", "cd_assets_book", "dt_asset", "ds_pr_contract_number", "cd_assets_location_room", "ds_department_ref_number", "nr_qty", "cd_department_cost_center", "cd_human_resource_responsible", "ds_assets_number_old", "ds_remarks",
         "nr_initial_value", "nr_monthly_depreciation", "dt_start_monthly_depreciation", "ds_category", "dt_end_monthly_depreciation", "dt_asset_scrap", "nr_scrap_value",  );

        $join = array(
            'JOIN "ASSETS_LOCATION_ROOM" ON ("ASSETS_LOCATION_ROOM".cd_assets_location_room =  "ASSETS".cd_assets_location_room)',
            'JOIN "ASSETS_LOCATION" ON ("ASSETS_LOCATION".cd_assets_location =  "ASSETS_LOCATION_ROOM".cd_assets_location)'
        );

        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            //"stylecond" => "(CASE WHEN \"ASSETS\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true,
            'join' => $join
        );

        $this->fieldsForPLBase = array($this->pk_field, // first always PK
            '( "ASSETS".ds_assets_number || \' - \' || "ASSETS".ds_assets   ) as description '
        );

        $this->fieldsForPLBaseDD = array($this->pk_field, // first always PK
            ' ( "ASSETS".ds_assets_number || \' - \' || "ASSETS".ds_assets   ) as ds_assets  '
        );



        parent::__construct();
    }

}
