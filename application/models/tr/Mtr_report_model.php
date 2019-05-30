<?php
include_once APPPATH . "models/modelBasicExtend.php";

class mtr_report_model extends modelBasicExtend {

    function __construct() {

        $this->table = "MTR_REPORT";
        $this->pk_field = "nr_work_order";
        $this->ds_field = "ds_file_name";
        $this->prodCatUnique = 'N';
        $this->sequence_obj = '';
        $this->controller = 'tr/mtr_report';

        $this->fieldsforGrid = array(
            ' "MTR_REPORT".nr_work_order',
            ' "MTR_REPORT".ds_file_name',
            ' "MTR_REPORT".ds_approved_by',
            ' "MTR_REPORT".nr_timestamp');
        $this->fieldsUpd = array("nr_work_order", "ds_file_name", "ds_approved_by", "nr_timestamp",);

        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            //"stylecond" => "(CASE WHEN \"MTR_REPORT\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );
        parent::__construct();


    }

    public function importMTRReportData() {
        $this->DB = $this->load->database('tr', true);
        $lastTimestampMtr = $this->getCdbhelper()->getTableLastTimeStamp($this->table);
        $query = "SELECT convert(numeric(18,2), r.WorkOrderId) as nr_work_order,  --numeric(18,2) PK
            r.AttachmentFileName as ds_file_name , --text
            r.ApproveBy as ds_approved_by, --text
            convert(integer, r.LMSTimeStamp) as nr_timestamp
        FROM UseTest2.dbo.TR_MilTestReport r, UseTest.dbo.ViewTRTechnicianWO v
        where UPPER(v.Brand) in ('MILLWAUKEE', 'MILWAUKEE', 'EMPIRE')
        and r.WorkOrderId=v.WorkOrderID and convert(integer, r.LMSTimeStamp) > $lastTimestampMtr order by LMSTimeStamp";
        $ret = $this->DB->query($query)->result_array();

        if (!empty($ret)) {
            $ret_arr = array_column($ret, null, 'nr_work_order');
            $wos = implode(',', array_keys($ret_arr));
            $wo_exist = $this->retRetrieveArray("WHERE nr_work_order in($wos)");
            $need_update = [];
            foreach ($wo_exist as $val) {
                $need_update[] = $ret_arr[$val['nr_work_order']];
                unset($ret_arr[$val['nr_work_order']]);
            }
            if ($ret_arr) $this->db->insert_batch($this->table, $ret_arr);
            if ($need_update) $this->db->update_batch($this->table, $need_update, 'nr_work_order');

            $lastTimestampMtr = end($ret)['nr_timestamp'];
            $this->getCdbhelper()->setTableLastTimeStamp($this->table, $lastTimestampMtr);
        }
        $rows = count($ret);
        echo($this->table . " Total Affected: $rows");

    }

}