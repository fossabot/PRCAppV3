<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class dashboard_kibana_generic extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
    }

    public function index() {

        parent::checkMenuPermission();

        $this->load->view("dashboard/dashboard_onekey_view", $send);
    }

    public function openKibana($id) {


        $cd_human_resource = $this->session->userdata('cd_human_resource');

        $sql = "select * from public.returnMenuMB($cd_human_resource) WHERE cd_menu = $id and fl_has_sub = 'L'";



        $res = $this->getCdbhelper()->basicSQLArray($sql);
        if (count($res) == 0) {
            die('No Permission for this Option');
        }

        $send = array('javascript' => '', 'iframe' => $res[0]['ds_kibana_dashboard'], 'title' => $res[0]['ds_menu']);

        //if ($this->ae_detect_ie()) {
//            die ('cannot man...');
//        } else {
            $this->load->view("dashboard/dashboard_kibana_generic_view", $send);
//        }
    }

    function ae_detect_ie() {
        if (isset($_SERVER['HTTP_USER_AGENT']) &&
                (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false))
            return true;
        else
            return false;
    }

}
