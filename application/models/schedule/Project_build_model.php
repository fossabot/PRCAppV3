<?php

include_once APPPATH . "models/modelBasicExtend.php";

class project_build_model extends modelBasicExtend {

    function __construct() {

        $this->table = "PROJECT_BUILD";

        $this->pk_field = "cd_project_build";
        $this->ds_field = "ds_project_build";
        $this->prodCatUnique = 'N';
        $this->sequence_obj = '"PROJECT_BUILD_cd_project_build_seq"';

        $this->controller = 'schedule/project_build';
        
        $this->fieldsforGrid = array(
            ' "PROJECT_BUILD".cd_project_build',
            ' "PROJECT_BUILD".ds_project_build',
            ' "PROJECT_BUILD".ds_project_build_abbreviation',
            ' "PROJECT_BUILD".ds_comment',
            ' "PROJECT_BUILD".dt_record',
            ' "PROJECT_BUILD".fl_by_model',
            ' "PROJECT_BUILD".fl_allow_multiples',                      
            ' "PROJECT_BUILD".fl_has_checkpoints',    
            ' "PROJECT_BUILD".fl_has_tests',    
            ' "PROJECT_BUILD".ds_tr_build_prefix',    
            ' "PROJECT_BUILD".nr_order');
        
        $this->fieldsUpd = array( "ds_tr_build_prefix", "cd_project_build", "fl_has_tests", "fl_has_checkpoints", "fl_allow_multiples", "ds_project_build", "ds_project_build_abbreviation", "ds_comment", "dt_record", "fl_by_model", "nr_order");


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "stylecond" => "(CASE WHEN \"PROJECT_BUILD\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );

        $this->fieldsForPLBaseDD= array($this->pk_field, $this->ds_field, 'fl_by_model');

        parent::__construct();
    }

}
