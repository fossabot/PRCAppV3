<?php

include_once APPPATH . "models/modelBasicExtend.php";

class approval_steps_config_model extends modelBasicExtend {

    function __construct() {

        $this->table = "APPROVAL_STEPS_CONFIG";

        $this->pk_field = "cd_approval_steps_config";
        $this->ds_field = "ds_approval_steps_config";
        $this->prodCatUnique = 'N';

        $this->sequence_obj = '"RFQ_APPROVAL_STEPS_cd_rfq_approval_steps_seq"';

        $this->controller = 'approval_steps_config';


        $this->fieldsforGrid = array(
            ' "APPROVAL_STEPS_CONFIG".cd_approval_steps_config',
            ' "APPROVAL_STEPS_CONFIG".ds_approval_steps_config',
            ' "APPROVAL_STEPS_CONFIG".ds_system_permission_ids',
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
        $this->fieldsUpd = array("cd_approval_steps_config", "ds_approval_steps_config", "ds_system_permission_ids", "nr_order", "fl_send_mail", "dt_deactivated", "dt_record", "ds_instructions", "ds_system_permission_ids_send_mail", "ds_internal_code", "ds_approval_steps_config_type", "fl_approval_all", "fl_show_only_if_has_rights", "fl_show_approve", "fl_show_reject",);


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "stylecond" => "(CASE WHEN \"APPROVAL_STEPS_CONFIG\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );


        parent::__construct();
    }

    public function getApprovalSteps($type, $code) {

        switch ($type) {
            case 'RFQ':
                $table = 'rfq."RFQ_APPROVAL_STEPS"';
                $pk = 'cd_rfq_approval_steps';
                $fk = 'cd_rfq';

                break;

            default:
                die('NO Approval Step Type');
                break;
        }

        $cduser = $this->session->userdata('cd_human_resource');

        $sql = "SELECT 
         b.$pk as recid,
        b.$fk,
        b.cd_approval_status,
        datedbtogrid(b.dt_define) as dt_define,
        b.cd_human_resource_define,
        (SELECT ds_human_resource_full FROM \"HUMAN_RESOURCE\" where cd_human_resource = b.cd_human_resource_define) as ds_human_resource_define,
        a.cd_approval_steps_config,
        a.ds_approval_steps_config,
        a.ds_system_permission_ids,
        a.nr_order,
        a.fl_send_mail,
        a.ds_internal_code,
        getUserPermission(ds_system_permission_ids, $cduser ) as fl_has_rights,
        b.dt_record,
        a.ds_approval_steps_config_type, 
        a.fl_approval_all, 
        a.fl_show_only_if_has_rights, 
        a.fl_show_approve, 
        a.fl_show_reject,
        a.ds_instructions,
        a.ds_system_permission_ids_send_mail,
        COALESCE(b.ds_remakrs, '') as ds_remakrs,
        ( CASE WHEN a.fl_can_jump_here_after_reject = 'Y' THEN 1 ELSE 0 END) as fl_can_jump_here_after_reject,
        ( CASE WHEN b.fl_must_add_reason  = 'Y' THEN 1 ELSE 0 END) as fl_must_add_reason 

      FROM $table b
        INNER JOIN \"APPROVAL_STEPS_CONFIG\" a ON (b.cd_approval_steps_config = a.cd_approval_steps_config AND a.ds_approval_steps_config_type = '$type')
       WHERE b.$fk = $code
         AND b.dt_record = ( SELECT max(x.dt_record) FROM $table x where x.$fk = b.$fk AND x.cd_approval_steps_config = a.cd_approval_steps_config )    

      UNION
      SELECT 
       (  a.cd_approval_steps_config * -10),
        $code,
        null,
        '',
        null,
        null,
        a.cd_approval_steps_config,
        a.ds_approval_steps_config,
        a.ds_system_permission_ids,
        a.nr_order,
        a.fl_send_mail,
        a.ds_internal_code,
        getUserPermission(ds_system_permission_ids, $cduser ) as fl_has_rights,
        a.dt_record,
        a.ds_approval_steps_config_type, 
        a.fl_approval_all, 
        a.fl_show_only_if_has_rights, 
        a.fl_show_approve, 
        a.fl_show_reject,
        a.ds_instructions,
        a.ds_system_permission_ids_send_mail,
        '',
        ( CASE WHEN a.fl_can_jump_here_after_reject = 'Y' THEN 1 ELSE 0 END) as fl_can_jump_here_after_reject,
        ( 0 ) as fl_must_add_reason 
      FROM \"APPROVAL_STEPS_CONFIG\" a
      WHERE a.dt_deactivated IS NULL
      AND a.ds_approval_steps_config_type = '$type'
      AND ( a.fl_show_only_if_has_rights = 'N' OR getUserPermission(ds_system_permission_ids, $cduser ) = 'Y' )
      AND NOT EXISTS ( SELECT 1 FROM $table x WHERE x.$fk = $code AND  x.cd_approval_steps_config = a.cd_approval_steps_config )
      ORDER BY nr_order;
";

        return $this->getCdbhelper()->basicSQLArray($sql);
    }

    
    public function returnHistory ($type, $code) {
        switch ($type) {
            case 'RFQ':
                $table = 'rfq."RFQ_APPROVAL_STEPS"';
                $pk = 'cd_rfq_approval_steps';
                $fk = 'cd_rfq';

                break;

            default:
                die('NO Approval Step Type');
                break;
        }
        
        $cduser = $this->session->userdata('cd_human_resource');
        
        $sql = "SELECT 
         b.$pk as recid,
        b.$fk,
        b.cd_approval_status,
        ( select ds_approval_status from \"APPROVAL_STATUS\" where cd_approval_status = b.cd_approval_status) as ds_approval_status,
        to_char(b.dt_define, 'MM/DD/YYYY HH24:MI') as dt_define,
        b.cd_human_resource_define,
        (SELECT ds_human_resource_full FROM \"HUMAN_RESOURCE\" where cd_human_resource = b.cd_human_resource_define) as ds_human_resource_define,
        a.cd_approval_steps_config,
        a.ds_approval_steps_config,
        a.ds_system_permission_ids,
        a.nr_order,
        a.fl_send_mail,
        a.ds_internal_code,
        getUserPermission(ds_system_permission_ids, $cduser ) as fl_has_rights,
        b.dt_record,
        a.ds_approval_steps_config_type, 
        a.fl_approval_all, 
        a.fl_show_only_if_has_rights, 
        a.fl_show_approve, 
        a.fl_show_reject,
        a.ds_instructions,
        a.ds_system_permission_ids_send_mail,
        COALESCE(b.ds_remakrs, '') as ds_remakrs

      FROM $table b
        INNER JOIN \"APPROVAL_STEPS_CONFIG\" a ON (b.cd_approval_steps_config = a.cd_approval_steps_config AND a.ds_approval_steps_config_type = '$type')
       WHERE b.$fk = $code ORDER BY b.dt_define DESC";
        
        return $this->getCdbhelper()->basicSQLJson($sql, true);

        
    }
    
    public function getStepBefore($type, $id, $code, $onlyWithDates = true) {
        $ret = $this->getApprovalSteps($type, $code);


        foreach ($ret as $key => $value) {
            if ($id == $value['cd_approval_steps_config'] && ( $value['dt_define'] != '' || !$onlyWithDates)) {
                if ($key == 0) {
                    return false;
                } else {
                    return $ret[$key - 1];
                }
            }
        }

        return false;
    }

    public function getStepAfter($type, $id, $code = -1) {
        $ret = $this->getApprovalSteps($type, $code);


        foreach ($ret as $key => $value) {
            if ($id == $value['cd_approval_steps_config']) {
                if (count($ret) - 1 == $key) {
                    return false;
                } else {
                    return $ret[$key + 1];
                }
            }
        }

        return false;
    }

    public function getActualStep($type, $code = -1) {
        $ret = $this->getApprovalSteps($type, $code);


        foreach ($ret as $key => $value) {
            if ($value['dt_define'] == '') {
                return $value;
            }
        }

        return false;
    }

    public function getStepByInternalCode($type,$code = -1, $internal) {
        $ret = $this->getApprovalSteps($type, $code);


        foreach ($ret as $key => $value) {
            if ($value['ds_internal_code'] == $internal) {
                return $value;
            }
        }

        return false;
    }
    
    
}
