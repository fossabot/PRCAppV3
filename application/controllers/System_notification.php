<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class system_notification extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    public function __construct() {
        parent::__construct();
        $this->load->model("system_notification_model", "mainmodel", TRUE);
        $this->load->model("system_notification_user_acknowledge_model", "akmodel", TRUE);
    }

    public function index() {
        parent::checkMenuPermission();
        $grid = $this->w2gridgen;
        $f = $this->cfields;
        $fm = $this->cfiltermaker;

        if (1 == 2) {
            $f = new Cfields();
            $grid = new w2gridgen();
            $fm = new cfiltermaker();
        }
        $fm = $this->cfiltermaker;
        $fm->addSimpleFilterUpper('System Notification', 'filter_1', '"SYSTEM_NOTIFICATION".ds_system_notification');

        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("system_notification");
        $grid->addColumnKey();
        $grid->addColumn('ds_system_notification', 'System Notification', '150px', $f->retTypeTextPL(), array('limit' => ''));
        $grid->addColumn('dt_start', 'Start', '80px', $f->retTypeDate(), true);
        $grid->addColumn('dt_end', 'End', '80px', $f->retTypeDate(), true);
        $grid->addColumn('fl_show_once', 'Show Once', '150px', $f->retTypeCheckBox(), true);
        $grid->addColumn('fl_acknowledge_require', 'Acknowledge Require', '150px', $f->retTypeCheckBox(), true);
        $grid->addColumn('ds_system_feedback_comments', 'Feedback Comments', '250px', $f->retTypePickList(), array('model' => 'system_feedback_comments_model', 'codeField' => 'cd_system_feedback_comments'));

        $filters = $fm->retFiltersWithGroup();
        $javascript = $grid->retGrid();

        $trans = array();
        $trans = $this->cdbhelper->retTranslationDifKeys($trans);

        $send = array("javascript" => $javascript,
            "filters" => $filters,
            "filters_java" => $fm->retJavascript()) + $trans;

        $this->load->view("defaultView", $send);
    }

    /** Get system notification if exist
     * if it is show only once, record the received info,
     * so it won't notify next time
     */
    public function getSysNotification() {
        $notify = $this->mainmodel->getSysNotification();
        // check if any notify need to record
        $notifyIds = [];
        foreach ($notify as $item) {
            if ($item['fl_show_once'] == 1 && $item['fl_acknowledge_require'] == 0) {
                $notifyIds[] = $item['cd_system_notification'];
            }
        }
        if ($notifyIds) {
            $this->setSysNotification($notifyIds, false);
        }
        exit(json_encode($notify));
    }

    /** set read status if it shows one time or acknowledged
     * so it won't notify next time
     * @param array|int $notifyIds id of notification
     * @return string
     */
    public function setSysNotification($notifyIds, $return = true) {
        $result = $this->akmodel->setSysNotification($notifyIds);
        if ($return) {
            if ($result)
                exit('1');
            else
                exit('0');
        }
    }

}
