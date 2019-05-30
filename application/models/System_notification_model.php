<?php
include_once APPPATH . "models/modelBasicExtend.php";

class system_notification_model extends modelBasicExtend {

    function __construct() {

        $this->table = "SYSTEM_NOTIFICATION";
        $this->pk_field = "cd_system_notification";
        $this->ds_field = "ds_system_notification";
        $this->prodCatUnique = 'N';
        $this->sequence_obj = '"SYSTEM_NOTIFICATION_cd_system_notification_seq"';
        $this->controller = 'system_notification';

        $this->fieldsforGrid = array(
            ' "SYSTEM_NOTIFICATION".cd_system_notification',
            ' "SYSTEM_NOTIFICATION".ds_system_notification',
            ' "SYSTEM_NOTIFICATION".dt_start',
            ' "SYSTEM_NOTIFICATION".dt_end',
            ' "SYSTEM_NOTIFICATION".fl_show_once',
            ' "SYSTEM_NOTIFICATION".fl_acknowledge_require',
            ' "SYSTEM_NOTIFICATION".cd_system_feedback_comments',
            '(select ds_system_feedback_comments FROM "SYSTEM_FEEDBACK_COMMENTS" WHERE cd_system_feedback_comments = "SYSTEM_NOTIFICATION".cd_system_feedback_comments) as ds_system_feedback_comments',
            '(select case when ds_attachment_path is not null then 1 end FROM "SYSTEM_FEEDBACK_COMMENTS" WHERE cd_system_feedback_comments = "SYSTEM_NOTIFICATION".cd_system_feedback_comments) as fl_has_attachment',
        );
        $this->fieldsUpd = array("cd_system_notification", "ds_system_notification", "dt_start", "dt_end", "fl_show_once", "fl_acknowledge_require", 'cd_system_feedback_comments');

        $this->retrOptions = array(
            "fieldrecid" => $this->pk_field,
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );
        parent::__construct();
    }

    /** run sql to find out the system notification
     */
    public function getSysNotification() {
        $notify = $this->retRetrieveArray(' where (now()::date >= dt_start AND now()::date <= dt_end)
                  and not exists( select 1 from public."SYSTEM_NOTIFICATION_USER_ACKNOWLEDGE" x 
                  where x.cd_system_notification = public."SYSTEM_NOTIFICATION".cd_system_notification 
                  AND x.cd_human_resource = get_var(\'cd_human_resource\')::integer )');
        return $notify;
    }

}