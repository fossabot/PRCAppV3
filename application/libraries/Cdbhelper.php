<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cdbhfelper
 *
 * @author dvlpserver
 */
include_once APPPATH . "libraries/CdbhelperBase.php";

class cdbhelper extends cdbhelperbase {

    function __construct() {
        parent::__construct();
        $cd_human_resource = $this->CI->session->userdata('cd_human_resource');

        //$this->load->model('hrms/employee_model', 'emp');
    }

    public function getSqlForDivision($ds_column) {
        $cd_human_resource = $this->CI->session->userdata('cd_human_resource');
        $sql = " SELECT  fl_super_user FROM " . $this->CI->db->escape_identifiers('HUMAN_RESOURCE') . "  where cd_human_resource = " . $cd_human_resource;
        $array_user = $this->basicSQLArray($sql);

        $fl_super_user = $array_user[0]['fl_super_user'];

        if ($fl_super_user == 'Y') {
            return '';
        }

        if ($this->getSystemParameters('DIVISION_PERMISSION_CONTROL') == 'N') {
            return '';
        }

        $sqlControl = " AND ( ( EXISTS ( SELECT 1 FROM " . $this->CI->db->escape_identifiers('HUMAN_RESOURCE_X_DIVISION') . "   WHERE cd_human_resource = $cd_human_resource AND cd_division = $ds_column AND dt_deactivated IS NULL ) ";


        $sqlControl .= " OR EXISTS ( SELECT 1 "
                . "   FROM " . $this->CI->db->escape_identifiers('JOBS_X_DIVISION') . " jd, "
                . "    " . $this->CI->db->escape_identifiers('JOBS_HUMAN_RESOURCE') . "  jh,"
                . "    " . $this->CI->db->escape_identifiers('JOBS') . "  j"
                . "   WHERE jh.cd_human_resource = $cd_human_resource "
                . "     AND jh.dt_deactivated IS NULL "
                . "     AND jd.cd_jobs     = jh.cd_jobs"
                . "     AND jd.dt_deactivated IS NULL"
                . "     AND jd.cd_division = $ds_column "
                . "     AND j.cd_jobs      = jh.cd_jobs"
                . "     AND j.dt_deactivated IS NULL "
                . "  ) "
                . ")  OR $ds_column IS NULL )";


        return $sqlControl;
    }

    public function getSqlForCustomer($ds_column) {
        $cd_human_resource = $this->CI->session->userdata('cd_human_resource');
        $sql = " SELECT  fl_super_user FROM " . $this->CI->db->escape_identifiers('HUMAN_RESOURCE') . " where cd_human_resource = " . $cd_human_resource;
        $array_user = $this->basicSQLArray($sql);

        $fl_super_user = $array_user[0]['fl_super_user'];

        if ($fl_super_user == 'Y') {
            return '';
        }

        if ($this->getSystemParameters('CUSTOMER_PERMISSION_CONTROL') == 'N') {
            return '';
        }

        $sqlControl = " AND ( ( EXISTS ( SELECT 1 FROM " . $this->CI->db->escape_identifiers('HUMAN_RESOURCE_X_CUSTOMER') . " WHERE cd_human_resource = $cd_human_resource AND cd_customer = $ds_column AND dt_deactivated IS NULL ) ";


        $sqlControl .= " OR EXISTS ( SELECT 1 "
                . "   FROM " . $this->CI->db->escape_identifiers('JOBS_X_CUSTOMER') . "  jd, "
                . "    " . $this->CI->db->escape_identifiers('JOBS_HUMAN_RESOURCE') . "  jh,"
                . "    " . $this->CI->db->escape_identifiers('JOBS') . "  j"
                . "   WHERE jh.cd_human_resource = $cd_human_resource "
                . "     AND jh.dt_deactivated IS NULL "
                . "     AND jd.cd_jobs     = jh.cd_jobs"
                . "     AND jd.dt_deactivated IS NULL"
                . "     AND jd.cd_customer = $ds_column "
                . "     AND j.cd_jobs      = jh.cd_jobs"
                . "     AND j.dt_deactivated IS NULL "
                . "  ) "
                . ") OR $ds_column IS NULL ) ";

        return $sqlControl;
    }

    public function getSqlForFactory($ds_column) {
        $cd_human_resource = $this->CI->session->userdata('cd_human_resource');
        $sql = " SELECT  fl_super_user FROM " . $this->CI->db->escape_identifiers('HUMAN_RESOURCE') . "  where cd_human_resource = " . $cd_human_resource;
        $array_user = $this->basicSQLArray($sql);

        $fl_super_user = $array_user[0]['fl_super_user'];

        if ($fl_super_user == 'Y') {
            return '';
        }

        if ($this->getSystemParameters('FACTORY_PERMISSION_CONTROL') == 'N') {
            return '';
        }

        $sqlControl = " AND ( ( EXISTS ( SELECT 1 FROM " . $this->CI->db->escape_identifiers('HUMAN_RESOURCE_X_FACTORY') . "  WHERE cd_human_resource = $cd_human_resource AND cd_factory = $ds_column AND dt_deactivated IS NULL ) ";


        $sqlControl .= " OR EXISTS ( SELECT 1 "
                . "   FROM " . $this->CI->db->escape_identifiers('JOBS_X_FACTORY') . " jd, "
                . "    " . $this->CI->db->escape_identifiers('JOBS_HUMAN_RESOURCE') . "  jh,"
                . "   " . $this->CI->db->escape_identifiers('JOBS') . " j"
                . "   WHERE jh.cd_human_resource = $cd_human_resource "
                . "     AND jh.dt_deactivated IS NULL "
                . "     AND jd.cd_jobs     = jh.cd_jobs"
                . "     AND jd.dt_deactivated IS NULL"
                . "     AND jd.cd_factory  = $ds_column "
                . "     AND j.cd_jobs      = jh.cd_jobs"
                . "     AND j.dt_deactivated IS NULL "
                . "  ) "
                . ") OR $ds_column IS NULL )   ";


        return $sqlControl;
    }

    public function getModelFromController($controller) {
        $exceptions = array(
            'human_resource_controller' => 'human_resource_model'
        );

        if (isset($exceptions[$controller])) {
            return $exceptions[$controller];
        }

        return $controller . '_model';
        //$value2->controller . '_model'
    }
    
    function readableBytes($bytes) {
    $i = floor(log($bytes) / log(1024));

    $sizes = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');

    return sprintf('%.02F', $bytes / pow(1024, $i)) * 1 . ' ' . $sizes[$i];
}


}

function hecho($text) {
    echo htmlentities($text);
}
