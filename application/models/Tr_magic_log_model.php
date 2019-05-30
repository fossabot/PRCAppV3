<?php

include_once APPPATH . "models/modelBasicExtend.php";

class tr_magic_log_model extends modelBasicExtend {

    function __construct() {

        $this->table = "TR_MAGIC_LOG";

        $this->pk_field = "cd_tr_magic_log";
        $this->ds_field = "ds_user";
        $this->prodCatUnique = 'N';
        $this->hasDeactivate = false;

        $this->sequence_obj = '"TR_MAGIC_LOG_cd_tr_magic_log_seq"';

        $this->controller = 'tr_magic_log';


        $this->fieldsforGrid = array(
            ' "TR_MAGIC_LOG".cd_tr_magic_log',
            ' "TR_MAGIC_LOG".ds_user',
            ' "TR_MAGIC_LOG".dt_start',
            ' "TR_MAGIC_LOG".dt_finish',
            ' "TR_MAGIC_LOG".nr_count',
            ' "TR_MAGIC_LOG".fl_activated');
        $this->fieldsUpd = array("cd_tr_magic_log", "ds_user", "dt_start", "dt_finish", "nr_count", "fl_activated",);


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            //"stylecond"  => "(CASE WHEN \"TR_MAGIC_LOG\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );


        parent::__construct();
    }

}
