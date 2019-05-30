<?php
include_once APPPATH . "models/modelBasicExtend.php";

class rfq_pr_incoming_outcoming_type_model extends modelBasicExtend
{


    function __construct()
    {

        $this->table = "RFQ_PR_INCOMING_OUTCOMING_TYPE";

        $this->pk_field = "cd_rfq_pr_incoming_outcoming_type";
        $this->ds_field = "ds_rfq_pr_incoming_outcoming_type";
        $this->prodCatUnique = 'N';

        $this->sequence_obj = '"RFQ_PR_INCOMING_OUTCOMING_TYP_cd_rfq_pr_incoming_outcoming__seq"';

        $this->controller = 'rfq/rfq_pr_incoming_outcoming_type';


        $this->fieldsforGrid = array(


            ' "RFQ_PR_INCOMING_OUTCOMING_TYPE".cd_rfq_pr_incoming_outcoming_type',
            ' "RFQ_PR_INCOMING_OUTCOMING_TYPE".ds_rfq_pr_incoming_outcoming_type',
            ' "RFQ_PR_INCOMING_OUTCOMING_TYPE".ds_type',
            ' "RFQ_PR_INCOMING_OUTCOMING_TYPE".dt_deactivated',
            ' "RFQ_PR_INCOMING_OUTCOMING_TYPE".fl_add_to_inventory' );
        $this->fieldsUpd = array("cd_rfq_pr_incoming_outcoming_type", "ds_rfq_pr_incoming_outcoming_type", "ds_type", "dt_deactivated", "fl_add_to_inventory");


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "stylecond" => "(CASE WHEN \"RFQ_PR_INCOMING_OUTCOMING_TYPE\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );


        parent::__construct();


    }
}