<?php

include_once APPPATH . "models/modelBasicExtend.php";

class assets_location_model extends modelBasicExtend {

    function __construct() {

        $this->table = "ASSETS_LOCATION";

        $this->pk_field = "cd_assets_location";
        $this->ds_field = "ds_assets_location";
        $this->prodCatUnique = 'N';

        $this->sequence_obj = 'assets."ASSETS_LOCATION_cd_assets_location_seq"';

        $this->controller = 'assets/assets_location';


        $this->fieldsforGrid = array(
            ' "ASSETS_LOCATION".cd_assets_location',
            ' "ASSETS_LOCATION".ds_assets_location',
            ' "ASSETS_LOCATION".dt_deactivated',
            ' "ASSETS_LOCATION".dt_record');
        $this->fieldsUpd = array("cd_assets_location", "ds_assets_location", "dt_deactivated", "dt_record",);


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "stylecond" => "(CASE WHEN \"ASSETS_LOCATION\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );


        parent::__construct();
    }

}
