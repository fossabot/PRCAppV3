<?php

include_once APPPATH . 'models/modelBasicExtend.php';

class job_model extends modelBasicExtend {

    function __construct() {
        parent::__construct();

        $this->table = "JOBS";

        $this->pk_field = "cd_jobs";
        $this->ds_field = "ds_jobs";
        $this->prodCatUnique = 'Y';

        $this->sequence_obj = '"JOBS_cd_jobs"';
        $this->fieldsExcludeUpd = array('ds_department', 'ds_jobs_responsible');

        $this->fieldsforGrid = array($this->pk_field,
            $this->ds_field,
            'cd_department',
            'ds_notes',
            '( SELECT ds_department FROM ' . $this->db->escape_identifiers('DEPARTMENT') . ' x where x.cd_department = ' . $this->db->escape_identifiers('JOBS') . '.cd_department ) as ds_department',
            'cd_jobs_responsible',
            '( SELECT ds_jobs FROM ' . $this->db->escape_identifiers('JOBS') . ' x where x.cd_jobs = ' . $this->db->escape_identifiers('JOBS') . '.cd_jobs_responsible  ) as ds_jobs_responsible',
            'dt_deactivated');

        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            'stylecond' => "(CASE WHEN dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            'fields' => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            'json' => true
        );
    }

    // monta o array. Funcao importante que vai garantir o formato dos campos na tela.
    function MakeW2Array($cd_jobs, $ds_jobs = "", $ds_notes = "", $ds_department = "", $ds_jobs_responsible = "", $dt_deactivated = "", $style = "") {

        $newDate = $this->cdbhelper->dateDbtoGrid($dt_deactivated);

        $array = array('recid' => $cd_jobs,
            "ds_jobs" => rtrim($ds_jobs),
            "ds_notes" => $ds_notes,
            "ds_department" => $ds_department,
            "ds_jobs_responsible" => $ds_jobs_responsible,
            'dt_deactivated' => $newDate,
            'style' => $style
        );

        return $array;
    }

    // monta o array. Funcao importante que vai garantir o formato dos campos na tela.
    function MakeW2PermissionArray($cd_system_permission, $ds_system_permission = "", $ds_type_sys_permission = "", $fl_checked) {
        if ($fl_checked == "Y") {
            $fl_checked = true;
        } else {
            $fl_checked = false;
        }


        $array = array('recid' => $cd_system_permission,
            "ds_system_permission" => rtrim($ds_system_permission),
            "ds_type_sys_permission" => $ds_type_sys_permission,
            "fl_checked" => $fl_checked,
            'style' => ""
        );

        return $array;
    }

    function retPermissionJson($job) {
        if ($this->db->dbdriver == 'postgre') {
            $sql = "select * from retPerHMbyJobs('P', " . $job . ") order by fl_checked desc, ds_key asc";
            $ret = $this->getCdbhelper()->basicSQLArray($sql);
        } else {
            $sql = "call retPerHMbyJobs('P', " . $job . ")";
            $ret = $this->getCdbhelper()->basicProcArray($sql);
        }
        $array = array();


        foreach ($ret as $row) {



            $insArray = $this->MakeW2PermissionArray($row['cd_key'], $row['ds_key'], $row['ds_other_info'], $row['fl_checked']
            );

            array_push($array, $insArray);
        }

        return json_encode($array);
    }

    // monta o array. Funcao importante que vai garantir o formato dos campos na tela.
    function MakeW2HRArray($cd_human_resource, $ds_human_resource = "", $ds_human_resource_full = "", $dt_deactivated = "", $fl_checked) {
        $newDate = $this->cdbhelper->dateDbtoGrid($dt_deactivated);

        if ($fl_checked == "Y") {
            $fl_checked = true;
        } else {
            $fl_checked = false;
        }
        $style = "";

        if ($dt_deactivated != null) {
            $style = "color: rgb(255,0,0)";
        }


        $array = array('recid' => $cd_human_resource,
            "ds_human_resource" => rtrim($ds_human_resource),
            "ds_human_resource_full" => $ds_human_resource_full,
            "dt_deactivated" => $newDate,
            "fl_checked" => $fl_checked,
            'style' => $style
        );

        return $array;
    }

    function retHRJson($job) {
        $fl_super_user = $this->session->userdata('fl_super_user');
        $prdcat = $this->session->userdata('system_product_category');
        //$fl_super_user = 'N';

        $cd_hmresource_logged = $this->session->userdata('cd_human_resource');
        if ($this->db->dbdriver == 'postgre') {

            $wherecontrol = " AND ( EXISTS ( select 1 
                               from " . $this->db->escape_identifiers('HUMAN_RESOURCE') . " x 
                              WHERE x.cd_human_resource = $cd_hmresource_logged AND "
                    . "     x.fl_super_user = 'Y' AND " . $this->db->escape_identifiers('HUMAN_RESOURCE') . ".fl_super_user = 'Y' )"
                    . "  OR  " . $this->db->escape_identifiers('HUMAN_RESOURCE') . ".fl_super_user = 'N' ) ";


            IF ($fl_super_user != 'Y') {
                $wherecontrol = $wherecontrol . ' AND ( EXISTS ( SELECT 1 '
                        . '        FROM ' . $this->db->escape_identifiers('HUMAN_RESOURCE_X_SYSTEM_PRODUCT_CATEGORY') . ' x'
                        . '                                WHERE x.cd_system_product_category = ' . $prdcat
                        . '                                  AND x.cd_human_resource              = ' . $this->db->escape_identifiers('HUMAN_RESOURCE') . '.cd_human_resource '
                        . '                                  AND x.dt_deactivated IS NULL )'
                        . '                   OR EXISTS ( SELECT 1 '
                        . '                             FROM ' . $this->db->escape_identifiers('JOBS_HUMAN_RESOURCE') . ' a, '
                        . '                             ' . $this->db->escape_identifiers('JOBS_X_SYSTEM_PRODUCT_CATEGORY') . '  x '
                        . '                  WHERE a.cd_human_resource = ' . $this->db->escape_identifiers('HUMAN_RESOURCE') . '.cd_human_resource '
                        . '                    AND a.dt_deactivated IS NULL '
                        . '                    AND x.cd_jobs = a.cd_jobs '
                        . '                    AND x.cd_system_product_category = ' . $prdcat
                        . '                    AND x.dt_deactivated IS NULL ) ) ';
            }

            $sql = "select * from retPerHMbyJobs('H', " . $job . ") a, " . $this->db->escape_identifiers('HUMAN_RESOURCE') . "  "
                    . " where a.dt_deactivated IS NULL "
                    . " AND " . $this->db->escape_identifiers('HUMAN_RESOURCE') . " .cd_human_resource = a.cd_key "
                    . " $wherecontrol "
                    . "order by a.fl_checked desc, a.ds_key asc";

            $ret = $this->getCdbhelper()->basicSQLArray($sql);
        } else {
            $sql = "call retPerHMbyJobs('H', " . $job . ")";
            $ret = $this->getCdbhelper()->basicProcArray($sql);
        }

        $array = array();


        foreach ($ret as $row) {

            $insArray = $this->MakeW2HRArray($row['cd_key'], $row['ds_key'], $row['ds_other_info'], $row['dt_deactivated'], $row['fl_checked']
            );

            array_push($array, $insArray);
        }

        return json_encode($array);
    }

    public function updatePermissionData($cd_jobs, $array) {


        $this->cdbhelper->trans_begin();
        $bError = false;
        foreach ($array as $row) {
            $array_row = (array) $row;

            $cd_code = $array_row['recid'];
            $fl_checked = $array_row['fl_checked'];
            $fl_type = $array_row['fl_type'];

            if ($fl_type == "H") {
                $table = 'JOBS_HUMAN_RESOURCE';
                $cd_code_table = "cd_human_resource";
            } else {
                $table = 'JOBS_SYSTEM_PERMISSION';
                $cd_code_table = "cd_system_permission";
            }

            $table = $this->db->escape_identifiers($table);

            $exists = $this->checkExists($table, $cd_code_table, $cd_code, $cd_jobs);

            $sql = "nada";

            if ($fl_checked == 'Y' && !$exists) {
                $sql = "insert into " . $table . "( $cd_code_table, cd_jobs ) values ( " . $cd_code . "," . $cd_jobs . ")";
            }

            if ($fl_checked == 'N' && $exists) {
                $sql = "DELETE FROM " . $table . " where " . $cd_code_table . " = " . $cd_code . " and cd_jobs = " . $cd_jobs;
            }

            if ($sql != "nada") {
                $this->getCdbhelper()->CIBasicQuery($sql);

                if (!$this->cdbhelper->trans_status()) {
                    $bError = true;
                    break;
                }
            }
        }

        if ($bError) {
            $error = $this->cdbhelper->trans_last_error();
        } else {
            $error = "OK";
            $this->cdbhelper->trans_commit();
        }

        $this->cdbhelper->trans_end();
        $ret = array("message" => $error);
        return json_encode($ret);
    }

    function checkExists($table, $pk, $code, $cd_jobs) {
        $sql = 'SELECT 1 FROM ' . $table . ' where ' . $pk . ' = ' . $code . ' and cd_jobs = ' . $cd_jobs;
        $query = $this->getCdbhelper()->CIBasicQuery($sql);

        return $query->num_rows() > 0;
    }

    public function hasDeactivate() {
        return $this->hasDeactivate;
    }

    function getController() {
        return 'jobs_maint';
    }

    public function retGridJsonByHM($cd_hmresource, $mode = 'B', $fieldsForSelection = false) {
        return $this->retGridJsonWithRelation($cd_hmresource, 'JOBS_HUMAN_RESOURCE', 'cd_human_resource', $mode, $fieldsForSelection);
    }

    // funcao que recebe o id do type component e atacha os product groups nele
    public function updSBSRelbyHM($id, $add, $remove) {
        $msg = $this->updRelationSBS($id, 'JOBS_HUMAN_RESOURCE', "cd_human_resource", $add, $remove);
        echo $msg;
    }

}

?>