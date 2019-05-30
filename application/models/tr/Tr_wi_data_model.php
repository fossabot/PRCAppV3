<?php

include_once APPPATH . "models/modelBasicExtend.php";

class tr_wi_data_model extends modelBasicExtend {

    function __construct() {

        $this->table = "TR_WI_DATA";

        $this->pk_field = "cd_tr_wi_data";
        $this->ds_field = "ds_test_procedure_name";
        $this->prodCatUnique = 'N';
        $this->hasDeactivate =false;

        $this->sequence_obj = '';

        $this->controller = 'tr/tr_wi_data';


        $this->fieldsforGrid = array(
            ' "TR_WI_DATA".cd_tr_wi_data',
            ' "TR_WI_DATA".ds_test_procedure_name',
            ' "TR_WI_DATA".ds_goal_units',
            ' "TR_WI_DATA".nr_efficiency',
            ' "TR_WI_DATA".ds_responsiblity',
            ' "TR_WI_DATA".nr_min_goal',
            ' "TR_WI_DATA".nr_max_goal',
            ' "TR_WI_DATA".dt_update');
        $this->fieldsUpd = array("cd_tr_wi_data", "ds_test_procedure_name", "ds_goal_units", "nr_efficiency", "ds_responsiblity", "nr_min_goal", "nr_max_goal", "dt_update",);


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            //"stylecond" => "(CASE WHEN \"TR_WI_DATA\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );


        parent::__construct();
    }

}
