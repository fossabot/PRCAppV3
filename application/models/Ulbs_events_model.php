<?php
include_once APPPATH . "models/modelBasicExtend.php";

class ulbs_events_model extends modelBasicExtend {

    function __construct() {

        $this->table = "ULBS_EVENTS";
        $this->pk_field = "ID";
        $this->ds_field = "message";
        $this->prodCatUnique = 'N';
        $this->sequence_obj = 'public."ULBS_EVENTS_ID_seq"::text';
        $this->controller = 'ulbs_events';

        $this->fieldsforGrid = array(
            ' "ULBS_EVENTS".ID',
            ' "ULBS_EVENTS".fixture_id',
            ' "ULBS_EVENTS".workorder_number',
            ' "ULBS_EVENTS".tool_number',
            ' "ULBS_EVENTS".message_type',
            ' "ULBS_EVENTS".message',
            ' "ULBS_EVENTS".update_time',
            ' "ULBS_EVENTS".remark');
        $this->fieldsUpd = array("ID", "fixture_id", "workorder_number", "tool_number", "message_type", "message", "update_time", "remark",);

        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            //"stylecond" => "(CASE WHEN \"ULBS_EVENTS\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );
        parent::__construct();

    }

    //import data from mysql table ulbs_events
    public function importDataFromMysql() {
        $db = $this->load->database('ulbs', true);
        $lastId = $this->getCdbhelper()->getTableLastTimeStamp("ULBS_EVENTS_ID");
        $query = "select * from ulbs_events where id > $lastId";
        $ret = $db->query($query)->result_array();
        if (!empty($ret)) {
            $this->db->insert_batch('ULBS_EVENTS', $ret);
            $lastId = end($ret)['ID'];
            $this->getCdbhelper()->setTableLastTimeStamp("ULBS_EVENTS_ID", $lastId);
        }
        $rows = count($ret);
        echo($this->table." Total Imported: $rows");
    }

}