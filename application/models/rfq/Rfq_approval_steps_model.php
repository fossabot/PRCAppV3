<?php

include_once APPPATH . "models/modelBasicExtend.php";

class rfq_approval_steps_model extends modelBasicExtend {

    function __construct() {

        $this->table = "RFQ_APPROVAL_STEPS";

        $this->pk_field = "cd_rfq_approval_steps";
        $this->ds_field = "ds_rfq";
        $this->prodCatUnique = 'N';
        $this->hasDeactivate = false;
        $this->sequence_obj = '"RFQ_APPROVAL_STEPS_cd_rfq_approval_steps_seq1"';

        $this->controller = 'rfq/rfq_approval_steps';

        $this->fieldsforGrid = array(
            ' "RFQ_APPROVAL_STEPS".cd_rfq_approval_steps',
            ' "RFQ_APPROVAL_STEPS".cd_rfq',
            ' "RFQ_APPROVAL_STEPS".cd_approval_steps_config',
            ' "RFQ_APPROVAL_STEPS".cd_approval_status',
            ' ( SELECT ds_approval_status from "APPROVAL_STATUS" WHERE cd_approval_status = "RFQ_APPROVAL_STEPS".cd_approval_status ) as ds_approval_status',
            ' "RFQ_APPROVAL_STEPS".dt_define',
            ' "RFQ_APPROVAL_STEPS".cd_human_resource_define',
            '( select ds_human_resource_full FROM "HUMAN_RESOURCE" WHERE cd_human_resource =  "RFQ_APPROVAL_STEPS".cd_human_resource_define) as ds_human_resource_define',
            ' "RFQ_APPROVAL_STEPS".dt_record',
            ' "RFQ_APPROVAL_STEPS".ds_remakrs',
            ' "APPROVAL_STEPS_CONFIG".cd_approval_steps_config',
            ' "APPROVAL_STEPS_CONFIG".ds_approval_steps_config',
            ' "APPROVAL_STEPS_CONFIG".ds_system_permission_ids',
            ' "RFQ_APPROVAL_STEPS".cd_approval_steps_config_jump_to',
             
            ' "APPROVAL_STEPS_CONFIG".nr_order',
            ' "APPROVAL_STEPS_CONFIG".fl_send_mail',
            ' "APPROVAL_STEPS_CONFIG".dt_deactivated',
            ' "APPROVAL_STEPS_CONFIG".dt_record',
            ' "APPROVAL_STEPS_CONFIG".ds_instructions',
            ' "APPROVAL_STEPS_CONFIG".ds_system_permission_ids_send_mail',
            ' "APPROVAL_STEPS_CONFIG".ds_internal_code',
            ' "APPROVAL_STEPS_CONFIG".ds_approval_steps_config_type',
            ' "APPROVAL_STEPS_CONFIG".fl_approval_all',
            ' "APPROVAL_STEPS_CONFIG".fl_show_only_if_has_rights',
            ' "APPROVAL_STEPS_CONFIG".fl_show_approve',
            ' "APPROVAL_STEPS_CONFIG".fl_show_reject');
        
        $this->fieldsUpd = array("cd_rfq_approval_steps", "cd_rfq", "cd_rfq_approval_steps_config", "cd_approval_status", "dt_define", "cd_human_resource_define", "dt_record", "ds_remakrs", "cd_approval_steps_config", 'cd_approval_steps_config_jump_to');
        
        $join = array('JOIN "APPROVAL_STEPS_CONFIG" ON ("APPROVAL_STEPS_CONFIG".cd_approval_steps_config = "RFQ_APPROVAL_STEPS".cd_approval_steps_config )');

        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            //"stylecond" => "(CASE WHEN \"RFQ_APPROVAL_STEPS\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true,
            'join' => $join 
        );


        parent::__construct();
    }

    


}
