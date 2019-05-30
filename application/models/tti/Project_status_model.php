<?php

include_once APPPATH . "models/modelBasicExtend.php";

class project_status_model extends modelBasicExtend {

    function __construct() {

        $this->table = "PROJECT_STATUS";

        $this->pk_field = "cd_project_status";
        $this->ds_field = "ds_project_status";
        $this->prodCatUnique = 'N';

        $this->sequence_obj = '"PROJECT_STATUS_cd_project_status_seq"';

        $this->controller = 'tti/project_status';


        $this->fieldsforGrid = array(
            ' "PROJECT_STATUS".cd_project_status',
            ' "PROJECT_STATUS".ds_project_status',
            ' "PROJECT_STATUS".dt_deactivated',
            ' "PROJECT_STATUS".fl_default',
            ' "PROJECT_STATUS".dt_record');
        $this->fieldsUpd = array("cd_project_status", "ds_project_status", "dt_deactivated", "fl_default", "dt_record",);


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "stylecond" => "(CASE WHEN \"PROJECT_STATUS\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );


        parent::__construct();
    }

}
