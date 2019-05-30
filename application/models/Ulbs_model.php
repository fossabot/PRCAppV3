<?php
include_once APPPATH . "models/modelBasicExtend.php";

class ulbs_model extends modelBasicExtend {

    function __construct() {

        $this->table = "ULBS";
        $this->pk_field = "ID";
        $this->ds_field = "tr_mesg";
        $this->prodCatUnique = 'N';
        $this->sequence_obj = 'public."ULBS_ID_seq"::text';
        $this->controller = 'ulbs';
        $this->fieldsforGrid = array(
            ' "ULBS".ID',
            ' "ULBS".tr_mesg',
            ' "ULBS".fixture_id',
            ' "ULBS".workorder_number',
            ' "ULBS".tool_number',
            ' "ULBS".cycle_target',
            ' "ULBS".cycle_completed',
            ' "ULBS".start_count',
            ' "ULBS".stop_count',
            ' "ULBS".test_elapse_time',
            ' "ULBS".test_status',
            ' "ULBS".logpath_local',
            ' "ULBS".logpath_remote',
            ' "ULBS".date_time',
            ' "ULBS".remark');
        $this->fieldsUpd = array("ID", "tr_mesg", "fixture_id", "workorder_number", "tool_number", "cycle_target", "cycle_completed", "start_count", "stop_count", "test_elapse_time", "test_status", "logpath_local", "logpath_remote", "date_time", "remark",);

        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            //"stylecond" => "(CASE WHEN \"ULBS\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );
        parent::__construct();

    }

    //import data from mysql table ulbs
    public function importDataFromMysql() {
        $db = $this->load->database('ulbs', true);
        $lastId = $this->getCdbhelper()->getTableLastTimeStamp("ULBS_ID");
        $query = "select * from ulbs where id > $lastId order by id";
        $ret = $db->query($query)->result_array();
        if (!empty($ret)) {
            $this->db->insert_batch('ULBS', $ret);
            $lastId = end($ret)['ID'];
            $this->getCdbhelper()->setTableLastTimeStamp("ULBS_ID", $lastId);
        }
        $rows = count($ret);
        echo($this->table." Total Imported: $rows");
    }

}