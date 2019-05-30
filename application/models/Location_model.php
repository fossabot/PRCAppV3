<?php

include_once APPPATH . "models/modelBasicExtend.php";

class location_model extends modelBasicExtend {

    function __construct() {

        $this->table = "LOCATION";

        $this->pk_field = "cd_location";
        $this->ds_field = "ds_location";
        $this->prodCatUnique = 'N';

        $this->sequence_obj = '"LOCATION_cd_location_seq"';

        $this->controller = 'location';


        $this->fieldsforGrid = array(
            ' "LOCATION".cd_location',
            ' "LOCATION".ds_location',
            ' "LOCATION".dt_deactivated',
            ' "LOCATION".dt_record');
        
        $this->fieldsUpd = array("cd_location", "ds_location", "dt_deactivated", "dt_record",);


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "stylecond" => "(CASE WHEN \"LOCATION\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );


        parent::__construct();
    }

}
