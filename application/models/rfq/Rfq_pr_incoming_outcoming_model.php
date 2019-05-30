<?php
include_once APPPATH . "models/modelBasicExtend.php";

class rfq_pr_incoming_outcoming_model extends modelBasicExtend
{


    function __construct()
    {

        $this->table = "RFQ_PR_INCOMING_OUTCOMING";

        $this->pk_field = "cd_rfq_pr_incoming_outcoming";
        $this->ds_field = "ds_rfq_pr_group";
        $this->prodCatUnique = 'N';

        $this->sequence_obj = '"RFQ_PR_INCOMING_OUTCOMING_cd_rfq_pr_incoming_outcoming_seq"';

        $this->controller = 'rfq/rfq_pr_incoming_outcoming';


        $this->fieldsforGrid = array(


            ' "RFQ_PR_INCOMING_OUTCOMING".cd_rfq_pr_incoming_outcoming',
//            ' "RFQ_PR_INCOMING_OUTCOMING".cd_rfq_pr_group',
//            '( select ds_project_number FROM "RFQ_PR_GROUP" WHERE cd_rfq_pr_group =  "RFQ_PR_INCOMING_OUTCOMING".cd_rfq_pr_group) as ds_rfq_pr_group',
            ' "RFQ_PR_INCOMING_OUTCOMING".cd_rfq_pr_group_distribution',
            '( select cd_rfq_pr_group_distribution FROM "RFQ_PR_GROUP_DISTRIBUTION" WHERE cd_rfq_pr_group_distribution =  "RFQ_PR_INCOMING_OUTCOMING".cd_rfq_pr_group_distribution) as cd_rfq_pr_group_distribution',
            ' "RFQ_PR_INCOMING_OUTCOMING".dt_action',
            ' "RFQ_PR_INCOMING_OUTCOMING".nr_qty',
            ' "RFQ_PR_INCOMING_OUTCOMING".cd_human_resource_receiver',
            '( select ds_human_resource_full FROM "HUMAN_RESOURCE" WHERE cd_human_resource =  "RFQ_PR_INCOMING_OUTCOMING".cd_human_resource_receiver) as ds_human_resource_receiver',
            ' "RFQ_PR_INCOMING_OUTCOMING".ds_comments',
            ' "RFQ_PR_INCOMING_OUTCOMING".cd_rfq_pr_incoming_outcoming_type',
            '( select ds_rfq_pr_incoming_outcoming_type FROM "RFQ_PR_INCOMING_OUTCOMING_TYPE" WHERE cd_rfq_pr_incoming_outcoming_type =  "RFQ_PR_INCOMING_OUTCOMING".cd_rfq_pr_incoming_outcoming_type) as ds_rfq_pr_incoming_outcoming_type');
        $this->fieldsUpd = array("cd_rfq_pr_incoming_outcoming",  "cd_rfq_pr_group_distribution", "dt_action", "nr_qty", "cd_human_resource_receiver", "ds_comments", "cd_rfq_pr_incoming_outcoming_type",);


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
//            "stylecond" => "(CASE WHEN \"RFQ_PR_INCOMING_OUTCOMING\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );


        parent::__construct();


    }
}