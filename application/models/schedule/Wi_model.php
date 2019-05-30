<?php
include_once APPPATH . "models/modelBasicExtend.php";

class wi_model extends modelBasicExtend {

    function __construct() {
        $this->table = "WI";
        $this->pk_field = "cd_wi";
        $this->ds_field = "ds_wi_code";
        $this->prodCatUnique = 'N';

        $this->sequence_obj = '"WI_cd_wi_seq"';
        $this->controller = 'schedule/wi';

        $this->fieldsforGrid = array(
            ' "WI".cd_wi',
            ' "WI".ds_wi_code',
            ' "WI".ds_wi',
            ' "WI".dt_deactivated',
            ' "WI".dt_record');
        $this->fieldsUpd = array("cd_wi", "ds_wi_code", "ds_wi", "dt_deactivated", "dt_record",);
        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "stylecond" => "(CASE WHEN \"WI\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );
        parent::__construct();

    }

}