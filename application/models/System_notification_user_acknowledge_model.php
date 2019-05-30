<?php

include_once APPPATH . "models/modelBasicExtend.php";

class system_notification_user_acknowledge_model extends modelBasicExtend {

    function __construct() {
        $this->table = "SYSTEM_NOTIFICATION_USER_ACKNOWLEDGE";
        $this->pk_field = "cd_system_notification_user_acknowledge";
        $this->ds_field = "ds_human_resource";
        $this->prodCatUnique = 'N';
        $this->sequence_obj = '"SYSTEM_NOTIFICATION_USER_ACKN_cd_system_notification_user_a_seq"';
        $this->controller = 'system_notification_user_acknowledge';
        $this->fieldsforGrid = array(
            ' "SYSTEM_NOTIFICATION_USER_ACKNOWLEDGE".cd_system_notification_user_acknowledge',
            ' "SYSTEM_NOTIFICATION_USER_ACKNOWLEDGE".cd_human_resource',
            '( select ds_human_resource_full FROM "HUMAN_RESOURCE" WHERE cd_human_resource =  "SYSTEM_NOTIFICATION_USER_ACKNOWLEDGE".cd_human_resource) as ds_human_resource',
            ' "SYSTEM_NOTIFICATION_USER_ACKNOWLEDGE".cd_system_notification'
            );
        $this->fieldsUpd = array("cd_system_notification_user_acknowledge", "cd_human_resource", "cd_system_notification",);

        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );
        parent::__construct();
    }

    public function setSysNotification($notifyIds) {
        $userId = $this->session->userdata('cd_human_resource');
        $notifyIds = (array) $notifyIds;
        // check if it has recorded
        $notifyStr = implode(',', $notifyIds);
        // Standard way from Framework
        $exist = $this->retRetrieveArray("where cd_human_resource=$userId and cd_system_notification in ($notifyStr)");

        $needSet = array_diff($notifyIds, array_column($exist, 'cd_system_notification'));
        if ($needSet) {
            $ins = array();
            foreach ($needSet as $id) {
                $rowins = $this->retRetrieveEmptyNewArray()[0];
                $rowins['cd_human_resource'] = $userId;
                $rowins['cd_system_notification'] = $id;
                array_push($ins, $rowins);
            }
            $res = $this->updateGridData($ins);
            if ($res === 'OK') {
                return 1;
            } else {
                return 0;
            }
        }
        return 1;
    }

}
