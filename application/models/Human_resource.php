<?php

include_once APPPATH . 'models/modelBasicExtend.php';

class Human_resource extends CI_Model
{

    var $hasDeactivate = true;

    function __construct()
    {

        parent::__construct();
    }

    public function login($user, $password)
    {

        $this->db->reset_query();
        $password = $this->db->escape_str($password);
        $user = $this->db->escape_str($user);
        //$user = $this->cdbhelper->normalizeDataToSQL('char', $user);
        //$password = $this->cdbhelper->normalizeDataToSQL('char', $password);

        $query = $this->db->get_where('HUMAN_RESOURCE', array('lower(ds_human_resource)' => strtolower($user)));

        //die (print_r($this->db->last_query()));

        //$sql = 'SELECT * FROM ' . $this->db->escape_identifiers('HUMAN_RESOURCE') . ' where lower(ds_human_resource) = lower(%s) and dt_deactivated IS NULL';


        //$sql = sprintf($sql, $user, $password);

        //$query = $this->cdbhelper->CIBasicQuery($sql);

        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function loginById($cd_user, $password)
    {
        $sql = 'SELECT 1 FROM ' . $this->db->escape_identifiers('HUMAN_RESOURCE') . ' where cd_human_resource = %d AND ds_password = md5(\'%s\')';
        $password = $this->db->escape_str($password);
        $sql = sprintf($sql, $cd_user, $password);


        $query = $this->cdbhelper->CIBasicQuery($sql);

        if ($query->num_rows() == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function selectdb($where = '', $order = '')
    {
        $sql = 'SELECT *, (select ds_hr_type from ' . $this->db->escape_identifiers('HR_TYPE') . ' where cd_hr_type = ' . $this->db->escape_identifiers('HUMAN_RESOURCE') . '.cd_hr_type ) as ds_hr_type, cd_department, ( select ds_department from "DEPARTMENT" where cd_department =  ' . $this->db->escape_identifiers('HUMAN_RESOURCE') . '.cd_department) as ds_department, 
        (select ds_team   from "TEAM"   where cd_team="HUMAN_RESOURCE".cd_team) as ds_team, cd_roles, ( select ds_roles from "ROLES" where cd_roles =  ' . $this->db->escape_identifiers('HUMAN_RESOURCE') . '.cd_roles) as ds_roles   FROM ' . $this->db->escape_identifiers('HUMAN_RESOURCE');


        if (is_array($where)) {
            $where = " where 1 = 1 " . $this->cdbhelper->mountFilterWhere($where);
        }

        if ($where == '[]') {
            $where = '';
        }

        $sql = $sql . $where . ' ';

        $sql = $sql . $order . ' ';

        $q = $this->cdbhelper->CIBasicQuery($sql);

        return $q->result_array();
    }

    // essa funcao eh especifica para evitar de ter select -> array -> correr para criar outro array
    public function selectForPL($where = '', $unionPK = "", $deactivate = 'Y')
    {
        return $this->cdbhelper->basicSelectForPL("HUMAN_RESOURCE", "cd_human_resource", "concat (ds_human_resource, ' - ', ds_human_resource_full)", $where, $unionPK, $deactivate);
    }

    public function updatePasswordDb($cd_human_resource, $ds_password)
    {

        $sql = 'UPDATE ' . $this->db->escape_identifiers('HUMAN_RESOURCE') . ' SET ds_password=md5(%s) where  cd_human_resource=%d';
        $ds_password = $this->cdbhelper->normalizeDataToSQL('ds_password', $ds_password);
        $cd_human_resource = $this->cdbhelper->normalizeDataToSQL('cd_hmresource', $cd_human_resource);

        $sql = sprintf($sql, $ds_password, $cd_human_resource);

        $this->cdbhelper->CIBasicQuery($sql);

        return $this->cdbhelper->trans_status();
    }

    public function updateGridData($array)
    {

        $this->cdbhelper->trans_begin();
        $bError = false;
        foreach ($array as $row) {
            $array_row = (array)$row;


            if (isset($array_row['ds_hr_type'])) {
                unset($array_row['ds_hr_type']);
            };
            if (isset($array_row['ds_department'])) {
                unset($array_row['ds_department']);
            };
            if (isset($array_row['ds_roles'])) {
                unset($array_row['ds_roles']);
            };

            if (isset($array_row['ds_team'])) {
                unset($array_row['ds_team']);
            };

            $sql = $this->cdbhelper->createGridUpdateSQL("HUMAN_RESOURCE", "cd_human_resource", $array_row, $this->recordExists($array_row['recid']));


            $this->cdbhelper->CIBasicQuery($sql);


            if (!$this->cdbhelper->trans_status()) {
                $error = $this->cdbhelper->trans_last_error();
                $bError = true;
                break;
            }


            if (isset($array_row['ds_password'])) {

                if (!$this->updatePasswordDb($array_row['recid'], $array_row['ds_password'])) {
                    $error = $this->cdbhelper->trans_last_error();

                    $bError = true;
                    break;
                }
            }
        }

        if (!$bError) {
            $error = "OK";
            $this->cdbhelper->trans_commit();
        }

        $this->cdbhelper->trans_end();

        return $error;
    }

    public function deleteGridData($array)
    {

        $this->cdbhelper->trans_begin();
        $bError = false;
        foreach ($array as $pk) {

            $sql = 'delete from ' . $this->db->escape_identifiers('HUMAN_RESOURCE') . ' where cd_human_resource = ' . $pk;
            $this->cdbhelper->CIBasicQuery($sql);

            if (!$this->cdbhelper->trans_status()) {
                $bError = true;
                break;
            }
        }

        if ($bError) {
            $error = $this->cdbhelper->trans_last_error();
        } else {
            $error = "OK";
            $this->cdbhelper->trans_commit();
        }

        $this->cdbhelper->trans_end();

        return $error;
    }

    public function recordExists($id)
    {
        $sql = 'SELECT 1 FROM ' . $this->db->escape_identifiers('HUMAN_RESOURCE') . ' where cd_human_resource = ' . $id;
        $query = $this->cdbhelper->CIBasicQuery($sql);
        return $query->num_rows() > 0;
    }

    public function retRetrieveJson($where = "")
    {

        $cd_hmresource_logged = $this->session->userdata('cd_human_resource');
        $fl_super_user = $this->session->userdata('fl_super_user');
        $prdcat = $this->session->userdata('system_product_category');


        //$fl_super_user = 'N';

        $wherecontrol = " AND (  ( EXISTS ( select 1 
                           from " . $this->db->escape_identifiers('HUMAN_RESOURCE') . " x 
                          WHERE x.cd_human_resource = $cd_hmresource_logged AND "
            . "     x.fl_super_user = 'Y' AND " . $this->db->escape_identifiers('HUMAN_RESOURCE') . ".fl_super_user = 'Y' )"
            . "  OR  " . $this->db->escape_identifiers('HUMAN_RESOURCE') . ".fl_super_user = 'N' ) ";

        IF ($fl_super_user != 'Y') {
            $wherecontrol = $wherecontrol . ' AND EXISTS ( SELECT 1 '
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
                . '                    AND x.dt_deactivated IS NULL ) ';
        }

        $wherecontrol = $wherecontrol . ' )';

       
        $ret = $this->selectdb($where . $wherecontrol, " order by 2");

        $array = array();

        foreach ($ret as $row) {
            $style = "";
            if ($row['dt_deactivated'] != null) {
                $style = "color: rgb(255,0,0)";
            }

            $insArray = $this->retW2Array(intval($row['cd_human_resource']), $row['ds_human_resource'], $row['ds_human_resource_full'], $row['ds_hr_type'], $row['dt_deactivated'], $row['ds_e_mail'], $style, $row['ds_department'], $row['ds_team'], $row['ds_roles']);

            array_push($array, $insArray);
        }

        return json_encode($array);
    }

    public function retInsJson()
    {
        $code = '"HUMAN_RESOURCE_cd_human_resource_seq"';
        $q = $this->cdbhelper->CIBasicQuery("select nextval('" . $code . "'::regclass) as nextcode");
        $ret = $q->result_array();
        $retz = $ret[0];
        return json_encode($this->retW2Array(intval($retz['nextcode'])));
    }

    public function getNextCode()
    {
        return $this->cdbhelper->getNextCode('"HUMAN_RESOURCE_cd_human_resource_seq"');
    }

    // monta o array. Funcao importante que vai garantir o formato dos campos na tela.
    function retW2Array($cd_human_resource, $ds_human_resource = "", $ds_human_resource_full = "", $ds_hr_type = '', $dt_deactivated = "", $ds_e_mail = "", $style = "", $ds_department, $ds_team, $ds_roles)
    {

        if ($dt_deactivated != null) {
            $myDateTime = DateTime::createFromFormat('Y-m-d', $dt_deactivated);
            $newDate = $myDateTime->format('m/d/Y');
        } else {
            $newDate = "";
        }

        $array = array('recid' => $cd_human_resource,
            'ds_human_resource' => rtrim($ds_human_resource),
            'ds_human_resource_full' => rtrim($ds_human_resource_full),
            'ds_department' => rtrim($ds_department),
            'ds_team' => rtrim($ds_team),
            'ds_roles' => rtrim($ds_roles),

            'ds_hr_type' => rtrim($ds_hr_type),
            'ds_e_mail' => rtrim($ds_e_mail),
            'dt_deactivated' => $newDate,
            'style' => $style
        );

        return $array;
    }

    public function hasDeactivate()
    {
        return $this->hasDeactivate;
    }

    public function retRetrieveGridJsonForm($id)
    {
        return $this->retRetrieveJson(' WHERE cd_human_resource = ' . $id);
    }

    public function retrieveDataArray($where = '')
    {

        $fl_super_user = $this->session->userdata('fl_super_user');
        $prdcat = $this->session->userdata('system_product_category');
        //$fl_super_user = 'N';

        if ($where == '') {
            $where = ' where 1 = 1 ';
        }
        $cd_hmresource_logged = $this->session->userdata('cd_human_resource');

        $wherecontrol = " AND ( EXISTS ( select 1 
                           from " . $this->db->escape_identifiers('HUMAN_RESOURCE') . " x 
                          WHERE x.cd_human_resource = $cd_hmresource_logged AND "
            . "     x.fl_super_user = 'Y' AND " . $this->db->escape_identifiers('HUMAN_RESOURCE') . ".fl_super_user = 'Y' )"
            . "  OR  " . $this->db->escape_identifiers('HUMAN_RESOURCE') . ".fl_super_user = 'N' ) ";


        IF ($fl_super_user != 'Y') {
            $wherecontrol = $wherecontrol . ' AND EXISTS ( SELECT 1 '
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
                . '                    AND x.dt_deactivated IS NULL ) ';
        }


        $where = $where . $wherecontrol;

        $sql = 'SELECT cd_human_resource as recid, ds_human_resource, ds_human_resource_full, (select ds_hr_type from ' . $this->db->escape_identifiers('HR_TYPE') . ' where cd_hr_type = ' . $this->db->escape_identifiers('HUMAN_RESOURCE') . '.cd_hr_type ) as ds_hr_type  FROM ' . $this->db->escape_identifiers('HUMAN_RESOURCE') . ' ' . $where . ' and dt_deactivated IS NULL order by 2, 3';


        return $this->cdbhelper->basicSQLArray($sql);
    }

    public function retGridJsonByJob($cd_jobs, $mode = 'B', $fieldsForSelection = false)
    {
        return $this->retGridJsonWithRelation($cd_jobs, 'JOBS_HUMAN_RESOURCE', 'cd_jobs', $mode, $fieldsForSelection);
    }

    // funcao que recebe o id do type component e atacha os product groups nele
    public function updSBSRelbyJob($id, $add, $remove)
    {
        $msg = $this->updRelationSBS($id, 'JOBS_HUMAN_RESOURCE', "cd_jobs", $add, $remove);
        echo $msg;
    }

    public function updRelationSBS($id, $relationTable, $idField, $add, $remove)
    {
        $this->pk_field = 'cd_human_resource';
        $this->ds_field = 'ds_human_resource_full';

        $this->table = 'HUMAN_RESOURCE';
        $this->hasDeactivate = true;

        return $this->cdbhelper->basicUpdateSBSgrid($relationTable, $idField, $id, $this->pk_field, $add, $remove);
    }

    public function retGridJsonWithRelation($cd_id, $relationTable, $idField, $mode = 'B', $fieldsForSelection = true
    )
    {

        $fl_super_user = $this->session->userdata('fl_super_user');
        $prdcat = $this->session->userdata('system_product_category');
        //$fl_super_user = 'N';

        $relationTable = $this->db->escape_identifiers($relationTable);
        $having = '';
        $this->pk_field = 'cd_human_resource';
        $this->ds_field = 'ds_human_resource_full';

        $this->table = 'HUMAN_RESOURCE';
        $this->hasDeactivate = true;


        // Product type para mode: R -> "Related", N -> 'Not Related', Both = 'B'
        switch ($mode) {
            case 'B':
                $flag = '( CASE WHEN EXISTS ( SELECT 1 '
                    . '                           FROM ' . $relationTable . ' ps '
                    . '                          WHERE ps.' . $this->db->escape_identifiers($idField) . ' =  ' . $cd_id
                    . '                            AND ps.' . $this->db->escape_identifiers($this->pk_field) . '  = ' . $this->db->escape_identifiers($this->table) . '.' . $this->db->escape_identifiers($this->pk_field) . ' '
                    . '                            AND ps.dt_deactivated  IS NULL ) THEN 1 ELSE 0 END ) as fl_checked';

                $where = '';

                break;

            case 'R':

                $flag = '1 as fl_checked';
                $where = ' WHERE EXISTS ( SELECT 1 '
                    . '                           FROM ' . $this->db->escape_identifiers($relationTable) . ' ps '
                    . '                          WHERE ps.' . $this->db->escape_identifiers($idField) . ' =  ' . $cd_id
                    . '                            AND ps.' . $this->db->escape_identifiers($this->pk_field) . '  = ' . $this->db->escape_identifiers($this->table) . '.' . $this->db->escape_identifiers($this->pk_field) . ' '
                    . '                            AND ps.dt_deactivated  IS NULL )';

                break;

            case 'N':
                $flag = '0 as fl_checked';

                $where = ' WHERE NOT EXISTS ( SELECT 1 '
                    . '                           FROM ' . $this->db->escape_identifiers($relationTable) . ' ps '
                    . '                          WHERE ps.' . $this->db->escape_identifiers($idField) . ' =  ' . $cd_id
                    . '                            AND ps.' . $this->db->escape_identifiers($this->pk_field) . '  = ' . $this->db->escape_identifiers($this->table) . '.' . $this->db->escape_identifiers($this->pk_field) . ' '
                    . '                            AND ps.dt_deactivated  IS NULL )';

                // se eh para aparecer os selecionaveis apenas ativos
                if ($this->hasDeactivate) {
                    $where = $where . ' AND dt_deactivated IS NULL ';
                }


                break;


            default:
                break;
        }

        $cd_hmresource_logged = $this->session->userdata('cd_human_resource');

        $wherecontrol = " AND ( EXISTS ( select 1 
                           from " . $this->db->escape_identifiers('HUMAN_RESOURCE') . " x 
                          WHERE x.cd_human_resource = $cd_hmresource_logged AND "
            . "     x.fl_super_user = 'Y' AND " . $this->db->escape_identifiers('HUMAN_RESOURCE') . ".fl_super_user = 'Y' )"
            . "  OR  " . $this->db->escape_identifiers('HUMAN_RESOURCE') . ".fl_super_user = 'N' ) ";


        IF ($fl_super_user != 'Y') {
            $wherecontrol = $wherecontrol . ' AND EXISTS ( SELECT 1 '
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
                . '                    AND x.dt_deactivated IS NULL ) ';
        }

        $where = $where . $wherecontrol;


        if (!$fieldsForSelection) {
            $fields = $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid);
        } else {
            $fields = array($this->ds_field . ' as ds_description');
        }

        array_push($fields, $flag);

        $stylecond = '';

        if ($this->hasDeactivate) {
            $stylecond = "(CASE WHEN dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )";
        } else {
            $stylecond = "''";
        }

        $options = array("fieldrecid" => $this->pk_field,
            'stylecond' => $stylecond,
            'fields' => $fields,
            'json' => true
        );

        $ret = $this->cdbhelper->basicSelectDb($this->table, $where, " order by " . $this->ds_field, $options);
        return ($ret);
    }


}

?>