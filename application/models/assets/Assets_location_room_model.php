<?php

include_once APPPATH . "models/modelBasicExtend.php";

class assets_location_room_model extends modelBasicExtend {

    function __construct() {

        $this->table = "ASSETS_LOCATION_ROOM";

        $this->pk_field = "cd_assets_location_room";
        $this->ds_field = "ds_assets_location_room";
        $this->prodCatUnique = 'N';

        $this->sequence_obj = 'assets."ASSETS_LOCATION_ROOM_cd_assets_location_room_seq"';

        $this->controller = 'assets/assets_location_room';


        $this->fieldsforGrid = array(
            ' "ASSETS_LOCATION_ROOM".cd_assets_location_room',
            ' "ASSETS_LOCATION_ROOM".ds_assets_location_room',
            ' "ASSETS_LOCATION_ROOM".cd_assets_location',
            '( select ds_assets_location FROM "ASSETS_LOCATION" WHERE cd_assets_location =  "ASSETS_LOCATION_ROOM".cd_assets_location) as ds_assets_location',
            ' "ASSETS_LOCATION_ROOM".dt_deactivated',
            ' "ASSETS_LOCATION_ROOM".dt_record');
        $this->fieldsUpd = array("cd_assets_location_room", "ds_assets_location_room", "cd_assets_location", "dt_deactivated", "dt_record",);


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "stylecond" => "(CASE WHEN \"ASSETS_LOCATION_ROOM\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );

        $this->fieldsForPLBase = array($this->pk_field, // first always PK
            '( ( select ds_assets_location FROM "ASSETS_LOCATION" WHERE cd_assets_location =  "ASSETS_LOCATION_ROOM".cd_assets_location) || \' - \' || "ASSETS_LOCATION_ROOM".ds_assets_location_room   ) as description '
        );

        $this->fieldsForPLBaseDD = array($this->pk_field, // first always PK
            '( ( select ds_assets_location FROM "ASSETS_LOCATION" WHERE cd_assets_location =  "ASSETS_LOCATION_ROOM".cd_assets_location) || \' - \' || "ASSETS_LOCATION_ROOM".ds_assets_location_room   ) as ds_assets_location_room  '
        );

        parent::__construct();
    }

}
