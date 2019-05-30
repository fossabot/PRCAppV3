<?php

include_once APPPATH . "models/modelBasicExtend.php";

class approval_status_model extends modelBasicExtend {

    function __construct() {

        $this->table = "APPROVAL_STATUS";

        $this->pk_field = "cd_approval_status";
        $this->ds_field = "ds_approval_status";
        $this->prodCatUnique = 'N';
        $this->hasDeactivate = false;

        $this->sequence_obj = '"APPROVAL_STATUS_cd_approval_status_seq"';

        $this->controller = 'approval_status';


        $this->fieldsforGrid = array(
            ' "APPROVAL_STATUS".cd_approval_status',
            ' "APPROVAL_STATUS".ds_approval_status',
            ' "APPROVAL_STATUS".fl_approved',
            ' "APPROVAL_STATUS".dt_record');
        $this->fieldsUpd = array("cd_approval_status", "ds_approval_status", "fl_approved", "dt_record",);


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            //"stylecond"  => "(CASE WHEN \"APPROVAL_STATUS\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );


        parent::__construct();
    }

}
