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
class cdbhelperbase {

    //put your code here
    var $dateFormat, $dateFormatPHP;
    var $mem;
    var $queryByMem;
    var $translateByMem;
    var $memPrefix;
    var $lastSQL = '';
    var $lastNextCode = 0;
    var $modelByMem;

    function __construct() {
        $this->dateFormat = '';
        $this->mem = new Memcached();
        $this->mem->addServer('localhost', 11211);

        $this->queryByMem = true;
        $this->translateByMem = true;
        $this->modelByMem = true;
        $this->memPrefix = $_SERVER['pgsqldb'] . '_';

        $this->CI = & get_instance();
        $this->activeDB = $this->CI->db;
        $this->setInitialVars();
    }

    function setInitialVars() {
        // CGB - Pega variavel de sessao no banco

        $CI = $this->CI;
        //$this->CI->output->enable_profiler(TRUE);

        $CI->load->model('settings_model');

        $cd_human_resource = $CI->session->userdata('cd_human_resource');
        $ds_human_resource = $CI->session->userdata('ds_human_resource');
        $cd_system_languages = $CI->session->userdata('cd_system_languages');
        $system_product_category = $CI->session->userdata('system_product_category');

        $CI->isTest = ($_SERVER['isprod'] == 'N');

        if (!$CI->isTest) {
            error_reporting(E_STRICT);
        }

        //$CI->output->enable_profiler(TRUE);



        $this->setDbVarsArray(array('cd_human_resource' => $cd_human_resource, 'ds_human_resource' => $ds_human_resource, 'cd_system_product_category' => $system_product_category));

        //$this->setDbVars('cd_human_resource', $cd_human_resource);
        //$this->setDbVars('ds_human_resource', $ds_human_resource);
        //$this->setDbVars('cd_system_product_category', $system_product_category);
        //$this->setDbVars('cd_system_languages', $cd_system_languages);
        $sysname = 'x'; //$this->getSystemParameters('SYSTEM_FULL_NAME');
        $q = $CI->db->get('SYSTEM_COMPANY');

        //$q = $CI->db->query('select * from public."SYSTEM_COMPANY"');
        $r = $q->result_array();

        if ($this->CI->db->dbdriver == 'postgre') {

            $CI->db->query("SET SESSION TIME ZONE '" . $r[0]['ds_timezone'] . "';");
            $CI->db->query("SET application_name = '$ds_human_resource - $sysname';");
        } else {
            //$CI->db->query("SET GLOBAL time_zone = '" . $r[0]['ds_timezone'] . "';");
            $CI->db->query("SET @application_name = '$ds_human_resource - $sysname';");
            $CI->db->query("SET sql_mode='PIPES_AS_CONCAT,STRICT_ALL_TABLES';");
            $CI->db->query("SET max_sp_recursion_depth=255;");
        }

        $CI->db->companyName = $r[0]['ds_name'];
        $CI->db->companyAddress = $r[0]['ds_address'];
        $CI->db->companyMaxConnection = $r[0]['nr_max_connections'];
        $CI->db->cd_system_product_category = $system_product_category;
        $CI->db->cd_human_resource = $cd_human_resource;
        $CI->db->ds_human_resource = $ds_human_resource;

        $CI->settings_model->sendSettingsToDb();

        $datef = $this->getSettings('fl_date_format');
        //die ($datef);
        if ($datef != '') {
            $datef = explode(';', $datef);

            $this->dateFormat = $datef[0];

            $this->dateFormatPHP = $datef[1];
        }
        // Setting up to 
        ini_set('memory_limit', '256M'); // This also needs to be increased in some cases. Can be changed to a higher value as per need)
        // Setting up to MSSQL
        ini_set('sqlsrv.ClientBufferMaxKBSize', '524288'); // Setting to 512M
        ini_set('pdo_sqlsrv.client_buffer_max_kb_size', '524288'); // Setting to 512M - for pdo_sqlsrv
        //die('<b>Total Execution Time:</b> '.$execution_time.' Mins');
    }

    public function createGridUpdateSQL($table, $pkfield, $obj, $recExists, $excludearray = array(), $pk2 = '', $includeArray = array()) {

        $fields_sql = "";
        $data_sql = "";
        $array = (array) $obj;
        $where = "";
        $totalFields = 0;
        $array_data = array();
        $position = 0;
        $updFields = array();

        if (isset($includeArray)) {
            $updFields = $includeArray;
        }

        $this->CI->db->reset_query();

        //print_r($excludearray);
        foreach (array_keys($array) as $keys) {
            // se encontrou a chave no exclude, nao faz nada!

            if (array_search($keys, $excludearray) !== false) {
                continue;
            }
            if (count($updFields) > 0 && array_search($keys, $updFields) === false && $keys != 'recid') {
                continue;
            }

            $info = $this->normalizeDataToSQL($keys, $array[$keys], true);

            $data = $info['vlr'];
            $type = $info['type'];

            // se existe, faz update!
            if ($recExists) {

                if ($keys == 'recid') {
                    $this->CI->db->where($pkfield, $data);

                    if ($pk2 != '') {
                        $this->CI->db->where($pk2, $array[$pk2]);
                    }
                } else {
                    $this->CI->db->set($keys, $data, false);
                    $totalFields ++;
                }
                // se nao existe, faz insert
            } else {
                $field = $keys;

                // se o campo for recid, substituo para a pk
                if ($keys === 'recid') {
                    if (strpos($pkfield, '.')) {
                        $field_arr2 = explode('.', $pkfield);
                        $field = $field_arr2[1];
                    } else {
                        $field = $pkfield;
                    }
                } else {
                    $totalFields ++;
                }


                $this->CI->db->set($field, $data, false);
            }
        }

        if ($recExists) {
            $recdone = $this->CI->db->get_compiled_update($table);
        } else {
            $recdone = $this->CI->db->get_compiled_insert($table);
        }


        if ($totalFields == 0) {
            return 'NOTHING';
        }

        return $recdone;
    }

    public function normalizeDataToSQL($field, $value, $retType = false) {
        // nessa funcao e$valueu verifico como vai ficar no banco o dado!
        $vlr = $value;
        $aspas = true;
        $type = '';

        if ($vlr === 'null') {
            return $vlr;
        }
        $vlr = $this->CI->security->xss_clean($vlr);

        if ($field == 'recid') {
            $type = 'int';
            $vlr = $value;
            $aspas = false;
        }

        if (substr($field, 0, 3) === 'dt_' || $field === 'date') {

            if ($vlr == 'now') {
                $vlr = 'now()';
            } else {
                $vlr = $this->dateGridtoDb($value);
            }
            $type = 'timestamptz';
        }

        if (substr($field, 0, 3) == 'fl_') {

            if ($value == 1 || $value === 'Y' || $value === "1" || $value == "1") {
                $vlr = "Y";
            } else {
                $vlr = "N";
            }
            $type = 'char(1)';
        }


        if (substr($field, 0, 3) == 'cd_' || $field == 'int') {
            $aspas = false;
            $vlr = $value == '-1' || $value == '' ? "null" : $value;
            $type = 'int';
        }

        if (substr($field, 0, 3) == 'nr_' || $field == 'num') {
            $aspas = false;
            $vlr = $value == '' && $value !== 0 ? "null" : $value;
            $type = 'numeric';
        }

        if (substr($field, 0, 3) == 'vl_') {
            $aspas = false;
            $vlr = $value == '' && $value !== 0 ? "null" : $value;
            $type = 'numeric';
        }

        if (substr($field, 0, 3) === 'ds_' || $field === 'char' || substr($field, 0, 3) === 'st_') {
            $vlr = html_escape($vlr);
            $aspas = true;
            if (trim($value) === '') {
                $vlr = 'null';
            } else {
                $vlr = $this->CI->db->escape_str(ltrim(rtrim($value)));
            }
            $type = 'character varying (999999)';
        }

        if (substr($field, 0, 4) == 'json') {
            //die('dentro do type');
            $type = 'jsonb';
        }


        if ($vlr != "null" && $aspas) {
            $vlr = "'" . $vlr . "'";
        }

        if (!$retType) {
            return $vlr;
        } else {
            return array('vlr' => $vlr, 'type' => $type);
        }
    }

    public function dateDbtoGrid($date) {
        if ($this->dateFormat == '') {
            $datef = $this->getSettings('fl_date_format');
            $datef = explode(';', $datef);
            $this->dateFormat = $datef[0];

            $this->dateFormatPHP = $datef[1];
        }


        if ($date != null) {
            $myDateTime = DateTime::createFromFormat('Y-m-d', $date);
            $newDate = $myDateTime->format($this->dateFormatPHP);
        } else {
            $newDate = "";
        }

        return $newDate;
    }

    public function dateGridtoDb($date) {

        if ($date == null || $date == "") {
            $newDate = "null";
        } else {

            if ($this->dateFormat == '' || $this->dateFormatPHP == '' ) {
                $datef = $this->getSettings('fl_date_format');
                
                $datef = explode(';', $datef);
                $this->dateFormat = $datef[0];
                
                $this->dateFormatPHP = $datef[1];
            }
            
            if (strlen($date) > 10) {
                $datetousephp = $this->dateFormatPHP. ' H:i';
                $datetousedb  = 'Y-m-d H:i';
            } else {
                $datetousephp = $this->dateFormatPHP;
                $datetousedb  = 'Y-m-d';
            }

            $myDateTime = DateTime::createFromFormat($datetousephp, $date);
            
            if (!$myDateTime) {
                die ("Error Formating Date, ~n Format: $datetousephp - Date: $date ");
            }
            

            $newDate = $myDateTime->format($datetousedb);
        }

        return $newDate;
    }

    public function mountFilterWhere($where, $table = '') {

        if ($table != '') {
            $table = '"' . $table . '".';
        }
        $retwhere = '';

        foreach ($where as $value) {
            $id = $value['id'];
            $oper = $value['oper'];
            $vlr = $value['vlr'];
            $field = $this->getFilterQuery($id);
            $field = $table . $field;

            if (substr($field, 0, 3) == 'ds_' || strpos($field, '.ds_') !== false) {
                $vlr = $this->normalizeDataToSQL('char', $vlr);
            }

            switch ($oper) {
                case 'YESNO':
                    if (strpos($field, 'dt_') === false) {
                        break;
                    }

                    if ($vlr == 'Y') {
                        $retwhere = $retwhere . ' and ' . $field . ' IS NULL';
                    }

                    if ($vlr == 'N') {
                        $retwhere = $retwhere . ' and ' . $field . ' IS NOT NULL';
                    }
                    break;

                case 'REL':


                    if (is_array($vlr)) {
                        $vlr = implode(',', $vlr);
                    };

                    $sql = $this->getFilterQuery($id);

                    $q = sprintf($sql, $vlr);
                    $retwhere = $retwhere . $q;
                    break;

                case 'FIXED' :
                    $sql = $this->getFilterQuery($id);
                    $retwhere = $retwhere . $sql;

                    break;

                CASE 'DATE':
                    $sql = $this->getFilterQuery($id);
                    $datefrom = $this->normalizeDataToSQL('date', $value['from']);
                    $dateto = $this->normalizeDataToSQL('date', $value['to']);


                    $retwhere = $retwhere . ' AND ' . $field . ' BETWEEN ' . $datefrom . ' AND ' . $dateto . ' ';


                    break;

                CASE 'NR':
                    $sql = $this->getFilterQuery($id);
                    $datefrom = $this->normalizeDataToSQL('float', $value['from']);
                    $dateto = $this->normalizeDataToSQL('float', $value['to']);


                    $retwhere = $retwhere . ' AND ' . $field . ' BETWEEN ' . $datefrom . ' AND ' . $dateto . ' ';


                    break;

                default:

                    $retwhere = $retwhere . ' and ' . $field . ' ' . $oper . ' (' . $vlr . ')';
                    break;
            }
        }

        return $retwhere;
    }

// funcoes de transacao:
    public function trans_begin() {
        $this->CI->db->db_debug = FALSE;
        $this->CI->db->trans_begin();
    }

    public function trans_status() {
        return $this->CI->db->trans_status();
    }

    public function trans_last_error() {
        if ($this->CI->db->dbdriver == 'postgre') {
            $errorDb = pg_last_error($this->CI->db->conn_id);
        } else {
            $errorDb = mysqli_error($this->CI->db->conn_id);
        }

        $this->trans_rollback();

        $error = $this->treatDbError($errorDb);

        $strposinit = strpos($error, 'CONTEXT:');
        if ($strposinit !== false) {

            $error = substr($error, 0, $strposinit);
        }

        return $error . '<br>';
    }

    public function trans_rollback() {

        $this->CI->db->trans_rollback();
    }

    public function trans_commit() {

        $this->CI->db->trans_commit();
    }

    public function trans_end() {

        $this->CI->db->db_debug = TRUE;
    }

    public function query($sql) {

        return $this->CIBasicQuery($sql);
    }

    public function query_array($sql) {

        $r = $this->CIBasicQuery($sql);
        return $r->result_array();
    }

    public function basicSelectDb($table, $where = '', $order = '', $options = array()) {

        $hasSpecificFields = false;
        $addtosql = "";
        $retasjson = false;
        $chgColumns = false;
        $getParse = false;
        $retLabels = '';
        $retrieveFunction = '';
        $jointoAdd = array();
        $addTableName = false;
        $colsToadd = '';
        $retSQL = false;
        $distinct = false;
        $whereRigthsControls = '';
        $plQuery = false;
        $plSimpleJoin = true;
        $plCode = '';
        $plDesc = '';
        $plPkUnion = '';
        $retrieveFunctionsendwhere = false;
        $table = $this->CI->db->escape_identifiers($table);
        $limit = -1;

        foreach (array_keys($options) as $key) {

            switch (strtolower($key)) {

                case 'addtablename' :
                    $addTableName = $options[$key];
                    break;

                case 'subselects':
                    $addfilters = "";

                    foreach ($options[$key] as $fields) {
                        $addfilters = $addfilters . $fields . ", ";
                    }
                    $addfilters = substr($addfilters, 0, -2);
                    $hasSpecificFields = true;
                    $addtosql = $addfilters . $addtosql;
                    break;

                case 'fieldrecid':
                    $addtosql = $addtosql . " ," . $options[$key] . " as recid ";
                    break;

                case 'coldynadd' :
                    $colsToadd = ',' . $options[$key];
                    break;


                case 'stylecond':
                    $addtosql = $addtosql . " ," . $options[$key] . " as style ";
                    break;

                case 'distinct':
                    $distinct = $options[$key];
                    break;

                case 'fields':
                    $addfilters = "";
                    $tablename = '';
                    if ($addTableName) {
                        $tablename = $table . '.';
                    }
                    foreach ($options[$key] as $fields) {
                        $addfilters = $addfilters . $tablename . $fields . ", ";
                    }
                    $addfilters = substr($addfilters, 0, -2);
                    $hasSpecificFields = true;
                    $addtosql = $addfilters . $addtosql;
                    break;

                case 'json':
                    $retasjson = $options[$key];
                    break;

                case 'chgcolumns':
                    $chgColumns = true;
                    break;

                case 'getparse' :
                    $getParse = true;
                    break;

                case 'jsonmapping':
                    $retLabels = $options[$key];
                    $chgColumns = true;
                    break;

                case 'retrievefunction' :
                    $retrieveFunction = $options[$key];
                    break;

                case 'limit' :
                    $limit = $options[$key];
                    break;

                
                
                case 'retrievefunctionsendwhere' :
                    $retrieveFunctionsendwhere = $options[$key];
                    break;


                case 'join' :
                    $jointoAdd = $options[$key];
                    break;

                case 'retsql';
                    $retSQL = $options[$key];
                    break;

                case 'divctrlcolumn';
                    $whereRigthsControls .= $this->getSqlForDivision($options[$key]);
                    break;

                case 'custctrlcolumn';
                    $whereRigthsControls .= $this->getSqlForCustomer($options[$key]);
                    break;

                case 'factctrlcolumn';
                    $whereRigthsControls .= $this->getSqlForFactory($options[$key]);
                    break;

                case 'forcedwhere';
                    $whereRigthsControls .= $options[$key];
                    break;

                case 'prodcatunique';
                    IF ($options[$key] == 'Y') {
                        $whereRigthsControls .= " AND $table.cd_system_product_category = " . $this->CI->db->cd_system_product_category;
                    }
                    break;

                case 'plpicklist';
                    $plQuery = $options[$key];
                    break;

                case 'plsimplejoin';
                    $plSimpleJoin = $options[$key];
                    break;


                default:
                    break;
            }
        }

        if (!$hasSpecificFields) {
            $addtosql = "*" . $addtosql;
        }


        $sql = 'SELECT ' . ( $distinct ? ' distinct ' : '' ) . $addtosql . ' /*sqlAddon*/ ' . $colsToadd . ' FROM ' . $table . ' ';

        if (is_array($where)) {
            $where .= 'WHERE true ' . $this->mountFilterWhere($where);
        }

        if ($where == '[]') {
            $where = '';
        }

        if ($whereRigthsControls !== '') {
            if ($where == '') {
                $where = ' WHERE true ' . $whereRigthsControls;
            } else {
                $where = $where . $whereRigthsControls;
            }
        }

        // joins
        if ($jointoAdd != array()) {

            $joins = '';

            foreach ($jointoAdd as $j) {
                $joins = $joins . ' ' . $j;
            };

            $where = $joins . ' ' . $where;
        }

        $sql = $sql . $where . ' ';

        // faz o parse para localizar os nomes das colunas
        if ($getParse || $chgColumns) {
            if ($retLabels == '') {
                if ($where == '') {
                    $w = ' WHERE false';
                } else {
                    $w = ' AND false';
                }
                $retLabels = $this->getFieldNames($sql . $w);
            }

            if ($getParse) {
                return $retLabels;
            }
        }

        $sql = $sql . $order . ' ';
        if ($limit > 0) {
            $sql = $sql . " LIMIT $limit";
        }

        // se tem funcao externa, ela sabe como deve retornar, simplesmente roda

        if ($retrieveFunction != '') {
            if ($retrieveFunctionsendwhere) {
                $sql = str_replace("'", "''", $where);
            } else {
                $sql = str_replace("'", "''", $sql);
            }


            $sql = ' select ' . $retrieveFunction . '(\'' . $sql . '\', \'' . $retLabels . '\' ) as row_to_json';
            $this->lastSQL = $sql;



            $q = $this->CIBasicQuery($sql);
            $row = $q->row();

            //if ($row->row_to_json == '') { return '[]' ; }

            return $row->row_to_json == '' ? '[]' : $row->row_to_json;
        }



        if ($retSQL) {
            //$sql = str_replace("'", "''", $sql);
            return $sql;
        }

        //die ($sql);
        // se eh para retornar como Json, traz isso direto pronto do banco!
        if ($retasjson) {

            if ($this->CI->db->dbdriver == 'postgre') {
                // usando funcao em vez de select direto!!
                $sql = str_replace("'", "''", $sql);
                if ($chgColumns) {
                    //$sql = ' select retResultSetJson8(\'' . $sql . '\', \'' . $retLabels . '\' ) as row_to_json';
                    $sql = ' select retResultSetJson(\'' . $sql . '\', null ) as row_to_json';
                } else {
                    $sql = ' select retResultSetJson(\'' . $sql . '\', null ) as row_to_json';
                }

                $this->lastSQL = $sql;

                $q = $this->CIBasicQuery($sql);
                $row = $q->row();

                return $row->row_to_json == '' ? '[]' : $row->row_to_json;
            } else {
                $this->lastSQL = $sql;

                return json_encode($this->CIBasicQuery($sql)->result_array(), JSON_NUMERIC_CHECK);
            }
        }



        $q = $this->CIBasicQuery($sql);
        return $q->result_array();
    }

    public function basicSQLNoReturn($sql) {
        $q = $this->CIBasicQuery($sql);
    }

    public function basicProcArray($sql) {
        $row = $this->CIBasicProc($sql);
        $row = $row->result_array();

        return $row;
    }

    // only for MysqlI por enquanto
    function CIBasicProc($sql) {

        $ret = $this->CIBasicQuery($sql);
        mysqli_next_result($this->CI->db->conn_id);

        return $ret;
    }

    function CIBasicQuery($sql) {

        $ownControl = $this->CI->db->db_debug;


        if ($ownControl) {
            $this->trans_begin();
        }

        $this->lastSQL = $sql;

        //die (print_r($this->lastSQL));

        $q = $this->CI->db->query($sql);

        if ($ownControl) {
            $bError = false;

            if (!$this->trans_status()) {
                $bError = true;
                $error = $this->trans_last_error();
            }
            if ($ownControl) {
                $this->trans_commit();
                $this->trans_end();
            }

            if ($bError) {
                die($error);
            }
        }


        return $q;
    }

    public function basicSQLJson($sql, $putAspas = false) {

        if ($putAspas) {
            $sql = str_replace("'", "''", $sql);
        }


        $sql = ' select retResultSetJson8(\'' . $sql . '\', \'\' ) as row_to_json';


        $this->lastSQL = $sql;

        $q = $this->CIBasicQuery($sql);
        $row = $q->row();

        return $row->row_to_json == '' ? '[]' : $row->row_to_json;
    }

    public function basicSQLArray($sql) {

        $this->lastSQL = $sql;

        $q = $this->CIBasicQuery($sql);
        IF (!$this->trans_status()) {
            return array();
        }
        $row = $q->result_array();

        return $row;
    }

    // basic insert
    public function basicInsertDb($table, $ds_field_desc, $desc, $dt_deactivated) {

        $desc = $this->normalizeDataToSQL($ds_field_desc, $desc);
        $dt_deactivated = $this->normalizeDataToSQL('dt_deactivated', $dt_deactivated);

        $sql = 'insert into "' . $table . '" (' . $ds_field_desc . ', dt_deactivated) values (%s, %s)';
        $sql = sprintf($sql, $desc, $dt_deactivated);
        $this->lastSQL = $sql;

        $this->CIBasicQuery($sql);

        return $this->trans_status();
    }

    public function basicUpdateDb($table, $ds_field_pk, $ds_field_desc, $id, $desc, $dt_deactivate) {


        $desc = $this->normalizeDataToSQL($ds_field_desc, $desc);
        $dt_deactivate = $this->normalizeDataToSQL('dt_deactivated', $dt_deactivated);


        $sql = 'UPDATE "' . $table . '" set ' . $ds_field_desc . ' = %s, dt_deactivated = $s where ' . $ds_field_pk . ' = %s';
        $sql = sprintf($sql, $desc, $dt_deactivate, $id);
        $this->lastSQL = $sql;

        $this->CIBasicQuery($sql);

        return $this->trans_status();
    }

    public function basicDeleteDb($table, $ds_field_pk, $id) {
        $this->CI->db->reset_query();
        $this->CI->db->where($ds_field_pk, $id);
        $sql = $this->CI->db->get_compiled_delete($table);


        //$sql = 'delete from  "' . $table . '" where ' . $ds_field_pk . ' = $1  ; execute basicDel (' . $id . '); deallocate prepare basicDel;';
        $this->lastSQL = $sql;

        $this->CIBasicQuery($sql);

        return $this->trans_status();
    }

    public function recordExists($table, $ds_pk_field, $id) {

        $this->CI->db->reset_query();
        $this->CI->db->select('1')->where($ds_pk_field, $id);
        $sql = $this->CI->db->get_compiled_select($table);

        $query = $this->CIBasicQuery($sql);
        $this->lastSQL = $sql;

        return $query->num_rows() > 0;
    }

    public function updateGridData($table, $pk_field, $array, $arrayexclude = array(), $options = array(), $onlyField = array(), $toDelete = array()) {


        // segue a configuracao do framework,... 
        $notranscontrol = !$this->CI->db->db_debug;



        $pk2 = '';
        foreach (array_keys($options) as $key) {
            switch ($key) {
                case 'notranscontrol':
                    $notranscontrol = true;
                    break;

                case 'pk2':
                    $pk2 = $options[$key];

                default:
                    break;
            }
        }



        if (!$notranscontrol) {
            $this->trans_begin();
        }

        $bError = false;

        foreach ($array as $row) {
            $array_row = (array) $row;

            $pk_fieldArray = explode('.', $pk_field);
            $pkf = $pk_fieldArray[count($pk_fieldArray) - 1];

            // ajusto o recid caso a coluna venha informada explicitamente (bom para form mais complexos).
            if (isset($array_row[$pkf])) {
                $array_row['recid'] = $array_row[$pkf];
                $row['recid'] = $array_row[$pkf];
            }



            $sql = $this->createGridUpdateSQL($table, $pk_field, $row, $this->recordExists($table, $pk_field, $array_row['recid']), $arrayexclude, $pk2, $onlyField);

            if ($sql != 'NOTHING') {
                $this->lastSQL = $sql;

                $this->CIBasicQuery($sql);

                if (!$this->trans_status()) {
                    $bError = true;
                    break;
                }
            }
        }

        // if no error updating the 
        if (!$bError) {
            foreach ($toDelete as $row) {
                $recid = $row;

                $this->CI->db->reset_query();
                $this->CI->db->where($pk_field, $recid);
                $sql = $this->CI->db->get_compiled_delete($table);

                if ($sql != 'NOTHING') {
                    $this->lastSQL = $sql;

                    $this->CIBasicQuery($sql);

                    if (!$this->trans_status()) {
                        $bError = true;
                        break;
                    }
                }
            }
        }

        if ($bError) {
            $error = $this->trans_last_error();
            $this->trans_rollback();
        } else {
            $error = "OK";
            if (!$notranscontrol) {
                $this->trans_commit();
            }
        }


        if (!$notranscontrol) {
            $this->trans_end();
        }

        return $error;
    }

    public function deleteGridData($table, $pk_field, $array) {

        $ownControl = $this->CI->db->db_debug;

        if ($ownControl) {
            $this->trans_begin();
        }

        $bError = false;
        foreach ($array as $pk) {
            $this->CI->db->reset_query();
            $this->CI->db->where($pk_field, $pk);
            $sql = $this->CI->db->get_compiled_delete($table);

            $this->lastSQL = $sql;
            // rodo a delecao!
            $this->CIBasicQuery($sql);

            if (!$this->trans_status()) {
                $bError = true;
                break;
            }
        }

        if ($bError) {
            $error = $this->trans_last_error();
        } else {
            $error = "OK";
        }

        if ($ownControl) {
            $this->trans_commit();
        }


        return $error;
    }

    function getNextCode($obj_sequence) {
        if ($this->CI->db->dbdriver == 'postgre') {
            $regc = '::regclass';
        } else {
            $regc = '';
        }

        $q = $this->CIBasicQuery("select nextval('" . $obj_sequence . "'$regc) as nextcode");
        $ret = $q->result_array();
        $retz = $ret[0];

        $this->lastNextCode = $retz['nextcode'];

        return $retz['nextcode'];
    }

    public function getActualCode($obj_sequence) {

        if ($this->CI->db->dbdriver == 'postgre') {
            $sql = "select currval('" . $obj_sequence . "'::regclass) as nextcode";
        } else {
            $sql = "select sequence_cur_value as nextcode FROM sequence_data where sequence_name = '" . $obj_sequence . "'";
        }

        $q = $this->CIBasicQuery($sql);
        $ret = $q->result_array();
        $retz = $ret[0];
        return $retz['nextcode'];
    }

    function checkMenuRights($controller) {

        $q = $this->CIBasicQuery("select checkMenuPermission('" . $controller . "') as ds_return");
        $ret = $q->result_array();
        $retz = $ret[0];

        return $retz['ds_return'];
    }

    function basicW2Array($ds_field_desc, $id, $ds_desc = "", $dt_deactivated = "", $style = "", $moreinfotoadd = array()) {
        $arrayToSend = array('dt_deactivated' => $dt_deactivated);
        return $this->basicW2ArrayNoDeac($ds_field_desc, $id, $ds_desc, $style, $arrayToSend + $moreinfotoadd);
    }

    // monta o array. Funcao importante que vai garantir o formato dos campos na tela.
    function basicW2ArrayNoDeac($ds_field_desc, $id, $ds_desc = "", $style = "", $moreinfotoadd = array()) {

        // procuro controles especificos (primeiro eh de data)


        foreach (array_keys($moreinfotoadd) as $key) {
            if (substr($key, 0, 3) == 'dt_') {

                $moreinfotoadd[$key] = $this->dateDbtoGrid($moreinfotoadd[$key]);
            }
        }

        //$newDate = $this->dateDbtoGrid($dt_deactivated);

        $array = array('recid' => $id,
            $ds_field_desc => rtrim($ds_desc),
            //'dt_deactivated' => $newDate,
            'style' => $style
                ) + $moreinfotoadd;

        return $array;
    }

    function basicW2ArrayIns($sequence_obj) {
        $nextcode = intval($this->getNextCode($sequence_obj));
        return json_encode(array('recid' => intval($nextcode), 'style' => ''));
    }

    // essa funcao eh especifica para evitar de ter select -> array -> correr para criar outro array
    public function basicSelectForPL($table, $pk_field, $ds_field, $where = '', $unionPK = "-1", $hasDeactivated = true, $sqladdon = "", $order = "2") {
        $table = $this->CI->db->escape_identifiers($table);

        $this->CI->db->reset_query();


        if ($hasDeactivated) {
            $deacsql = ", ( CASE WHEN dt_deactivated IS NULL THEN 'Y' ELSE 'N' END) as fl_active ";
            $this->CI->db->select("( CASE WHEN dt_deactivated IS NULL THEN 'Y' ELSE 'N' END) as fl_active");
        } else {
            $deacsql = ", 'Y' as fl_active";
            $this->CI->db->select("'Y' as fl_active");
        }

        if ($sqladdon != '') {
            $deacsql = $deacsql . ', ' . $sqladdon;
        }

        $sql_pure = 'SELECT ' . $pk_field . ' as recid, rtrim(' . $ds_field . ') as description ' . $deacsql . ' FROM ' . $table . ' ';
        $sql = $sql_pure;

        $this->CI->db->select($pk_field . ' as recid');
        $this->CI->db->select($ds_field . ' as description');

        //$this->CI->db->order_by($order);

        if ($where != '') {
            $sql = $sql . $where . " ";
        }
        //if ($unionPK != "-1" && $unionPK != "") {
        //    $sql = $sql . " union " . $sql_pure . " where " . $pk_field . " = " . $unionPK;
        //}

        $sql = $sql . ' order by ' . $order;

        $sql = $this->CI->db->get_compiled_select($table) . ' ' . $where . ' order by ' . $order;

        $this->lastSQL = $sql;


        $q = $this->CIBasicQuery($sql);

        return $q->result_array();
    }

    // essa funcao eh especifica para evitar de ter select -> array -> correr para criar outro array
    public function basicSelectForPLNew($sqlinfo) {

        if ($sqlinfo['hasDeactivated']) {
            $deacsql = ", ( CASE WHEN dt_deactivated IS NULL THEN 'Y' ELSE 'N' END) as fl_active ";
        } else {
            $deacsql = ", 'Y' as fl_active";
        }


        if ($sqlinfo['plAddon'] != '') {
            $deacsql = $deacsql . ', ' . $sqladdon;
        }


        $pk_field = $sqlinfo['plPkField'];
        $ds_field = $sqlinfo['plDsField'];
        $table = $sqlinfo['plTable'];
        $where = '';
        if (IsSet($sqlinfo['plWhere'])) {
            $where = $sqlinfo['plWhere'];
        }

        $sql_pure = 'SELECT ' . $pk_field . ' as recid, ' . $ds_field . ' as description ' . $deacsql . ' FROM "' . $table . '" ';
        $sql = $sql_pure;

        if ($where != '') {
            $sql = $sql . $where . " ";
        }
        if ($unionPK != "-1" && $unionPK != "") {
            $sql = $sql . " union " . $sql_pure . " where " . $pk_field . " = " . $unionPK;
        }

        $sql = $sql . ' order by ' . $order;


        $this->lastSQL = $sql;

        $q = $this->CIBasicQuery($sql);

        return $q->result_array();
    }

    public function retTranslation($trans, $key = '') {

        if ($this->CI->isTest) {
            return $trans;
        }


        if ($this->translateByMem) {
            return $this->retTranslationMem($trans, $key);
        } else {
            return $this->retTranslationDB($trans, $key);
        }
    }

    public function resetTransFromMemory($syslang) {
        if ($this->translateByMem) {
            $this->removeMemVar('language' . $syslang);
        }
    }

    public function retTranslationMem($trans, $key = '') {

        //return $trans;

        $syslang = $this->getSettings('cd_system_languages');
        //echo ('language'.$syslang);
        $arrayTrans = $this->mem->get($this->memPrefix . 'language' . $syslang);

        // se o array nao existe, faz o load
        if ($this->mem->getResultCode() == MemCached::RES_NOTFOUND) {
            $arrayTrans = $this->loadDictionaryMem($syslang);
        }
        // se nao eh array, eh soh 1!
        if ((array) $trans !== $trans) {
            //$find = array_search($trans, $arrayTrans);
            if (isset($arrayTrans[$trans])) {
                return $arrayTrans[$trans];
            } else {
                // se nao encontrou significa que preciso inserir!
                $translation = $this->retTranslationDB($trans);
                $arrayTrans[$trans] = $translation;
                $this->mem->set($this->memPrefix . 'language' . $syslang, $arrayTrans);
                return $translation;
            }
        }

        // aqui soh continua se for array.
        foreach ($trans as $newkey => $info) {
            $keyError = false;
            if (!is_array($info)) {
                $line = $info;
                $useKey = false;
            } else {
                // existem casos, como grid, que dependendo o botao tem o key ou nao.
                IF ($key == '') {
                    $key = 0;
                }
                if (isSet($info[$key])) {
                    $line = $info[$key];
                } else {
                    $line = 'error ' . $key;
                    $keyError = true;
                }

                $useKey = true;
            }

            if (!$keyError) {
                // variaveis carregads, hora de fazer a traducao!
                //$find = array_key_exists ($line, $arrayTrans);
                //if ($find !== FALSE){
                if (isset($arrayTrans[$line])) {
                    $line = $arrayTrans[$line];
                } else {
                    // se nao encontrou significa que preciso inserir!
                    //echo ($find);
                    $translation = $this->retTranslationDB($line);
                    $arrayTrans[$line] = $translation;
                    $this->mem->set($this->memPrefix . 'language' . $syslang, $arrayTrans);
                    $line = $translation;
                }

                if ($useKey) {
                    $trans[$newkey][$key] = $line;
                } else {
                    $trans[$newkey] = $line;
                    //array_push($ret, $line);
                }
            }
        }

        return $trans;
    }

    public function loadDictionaryMem($idlang) {

        $this->CI->db->reset_query();
        $this->CI->db->select('ds_system_dictionary_main');
        $this->CI->db->select('ds_translated');
        $this->CI->db->order_by('ds_translated', 'ASC');

        $this->CI->db->where('cd_system_languages', $idlang);

        $sql = $this->CI->db->get_compiled_select('SYSTEM_DICTIONARY_VIEW');



        $arrayTrans = array();
        $ret = $this->CIBasicQuery($sql);

        foreach ($ret->result_array() as $res) {
            $arrayTrans[$res['ds_system_dictionary_main']] = $res['ds_translated'];
        }
        $this->mem->set($this->memPrefix . 'language' . $idlang, $arrayTrans);
        return $arrayTrans;
    }

    function retTranslationDB($trans, $key = '') {


        $useKey = false;

        if (!is_array($trans)) {
            $sql = "select retDescTranslated('" . $trans . "', null) as ds_trans";

            $q = $this->CIBasicQuery($sql);
            $ret = $q->result_array();
            return $ret[0]['ds_trans'];
        }

        // aqui soh continua se for array. dai monto union para fazer apenas uma query!
        $sql = "";
        $nr_count = 0;
        foreach ($trans as $info) {

            if (!is_array($info)) {
                $line = $info;
            } else {
                // existem casos, como grid, que dependendo o botao tem o key ou nao.
                IF ($key == '') {
                    $key = 0;
                }
                if (isSet($info[$key])) {
                    $line = $info[$key];
                } else {
                    $line = '';
                }

                $useKey = true;
            }

            if ($line != '') {
                $sql = $sql . " select " . $nr_count . " as nr_order, retDescTranslated('" . $line . "', null) as ds_trans UNION ";
            }
            $nr_count ++;
        }

        if ($sql == '') {
            return $trans;
        }

        // tiro fora o ultimo union
        $sql = substr($sql, 0, -6) . " ORDER BY 1";
        $q = $this->CIBasicQuery($sql);
        $ret = $q->result_array();

        // corro o retorno e jogo de volta para o array!
        foreach ($ret as $info) {
            if ($useKey) {
                $trans[$info['nr_order']] [$key] = $info['ds_trans'];
            } else {
                $trans[$info['nr_order']] = $info['ds_trans'];
            }
        }

        return $trans;
    }

    // funcao que recebe chaves diferentes e ajusta para traduzir tudo!
    public function retTranslationDifKeys($trans) {

        if ($this->CI->isTest) {
            return $trans;
        }


        $keys = array();
        $values = array();

        foreach (array_keys($trans) as $key) {
            array_push($keys, $key);
            array_push($values, $trans[$key]);
        }

        $values = $this->retTranslation($values);

        for ($i = 0; $i < sizeof($values); $i++) {
            $trans[$keys[$i]] = $values[$i];
        }


        return $trans;
    }

    function setSQLFieldsToGrid($arrayret, $tablename = '') {
        $ret = array();



        foreach ($arrayret as $value) {

            $tabl = '';

            if ($tablename != '') {
                $tabl = $tablename . '.';
            }

            $value = trim($value);
            // se comeca com '(' significa que eh subselect
            if (substr(ltrim($value), 0, 1) == '(') {
                array_push($ret, $value);
                continue;
            }

            $pos = strpos($value, '.');

            if ($pos !== false) {
                $tabl = substr($value, 0, $pos) . '.';
                $value = substr($value, $pos + 1);
            }

            switch (substr($value, 0, 3)) {

                case 'cd_':
                    $value = $tabl . $value;
                    break;

                case 'dt_':


                    if ($this->dateFormat == '') {
                        $datef = $this->getSettings('fl_date_format');
                        if (!$datef) {
                            $this->dateFormat = 'mmddyyyy';
                        } else {
                            $datef = explode(';', $datef);
                            $this->dateFormat = $datef[0];
                            $this->dateFormatPHP = $datef[1];
                        }
                    }

                    if ($this->CI->db->dbdriver == 'postgre') {
                        $value = "coalesce (to_char(" . $tabl . $value . ", '" . $this->dateFormat . "'), '') as " . $value;
                    } else {
                        $value = "datedbtogrid(" . $tabl . $value . ") as " . $value;
                    }

                    break;

                case 'ds_':
                    $value = "trim(coalesce(" . $tabl . $value . ", '')) as " . $value;
                    break;

                case 'fl_':
                    $value = "(CASE WHEN " . $tabl . $value . " = 'Y' THEN 1 ELSE 0 END) as " . $value;
                    break;

                case 'st_':
                    $value = "trim(coalesce(" . $tabl . $value . ", '')) as " . $value;
                    break;


                default:
                    $value = $tabl . $value;
                    break;
            }
            array_push($ret, $value);
        }

        return $ret;
    }

    function pgArrayToPhp($text) {

        if (is_null($text)) {
            return array();
        } else if (is_string($text) && $text != '{}') {
            $text = substr($text, 1, -1); // Removes starting "{" and ending "}"
            if (substr($text, 0, 1) == '"') {
                $text = substr($text, 1);
            }
            if (substr($text, -1, 1) == '"') {
                $text = substr($text, 0, -1);
            }
            // If double quotes are present, we know we're working with a string.
            if (strstr($text, '"')) { // Assuming string array.
                $values = explode('","', $text);
            } else { // Assuming Integer array.
                $values = explode(',', $text);
            }
            $fixed_values = array();
            foreach ($values as $value) {
                $value = str_replace('\\"', '"', $value);
                $fixed_values[] = $value;
            }
            return $fixed_values;
        } else {
            return array();
        }
    }

    public function pgArrayFromPhp($array, $data_type = 'character varying') {
        $array = (array) $array; // Type cast to array.
        $result = array();

        //pgArrayFromPhp($array, $data_type = 'character varying') {
        foreach ($array as $entry) { // Iterate through array.
            if (is_array($entry)) { // Supports nested arrays.
                $result[] = $this->pgArrayFromPhp($entry);
            } else {
                $entry = str_replace('"', '\\"', $entry); // Escape double-quotes.
                $entry = pg_escape_string($entry); // Escape everything else.
                $result[] = '"' . $entry . '"';
            }
        }
        return '\'{' . implode(',', $result) . '}\'::' . $data_type . '[]'; // format
    }

    function basicUpdateSBSgrid($tablename, $ak1_column, $ak1_value, $ak2_column, $gridAddArray, $gridRemoveArray, $options = array()
    ) {


        $notranscontrol = !$this->CI->db->db_debug;

        foreach (array_keys($options) as $key) {
            switch ($key) {
                case 'notranscontrol':
                    $notranscontrol = true;
                    break;

                default:
                    break;
            }
        }



        if (!is_array($gridAddArray)) {
            $gridAddArray = json_decode($gridAddArray, true);

            $add = array();

            foreach ($gridAddArray as $value) {
                array_push($add, $value['recid']);
            }
        } else {
            $add = $gridAddArray;
        }

        if (!is_array($gridRemoveArray)) {
            $gridRemoveArray = json_decode($gridRemoveArray, true);

            $remove = array();
            foreach ($gridRemoveArray as $value) {
                array_push($remove, $value['recid']);
            }
        } else {
            $remove = $gridRemoveArray;
        }

        $sql = array();

        // add
        foreach ($add as $key => $value) {
            //checo se existe
            $this->CI->db->reset_query();
            $query = $this->CI->db->get_where($tablename, array($ak1_column => $ak1_value, $ak2_column => $value), 1);
            $boolean_exists = $this->CI->db->affected_rows() > 0;

            $this->CI->db->reset_query();
            $this->CI->db->where(array($ak1_column => $ak1_value, $ak2_column => $value));

            if ($boolean_exists) {
                $this->CI->db->set('dt_deactivated', 'null', false);
                array_push($sql, $this->CI->db->get_compiled_update($tablename));
                ;
            } else {
                $this->CI->db->set(array($ak1_column => $ak1_value, $ak2_column => $value));
                array_push($sql, $this->CI->db->get_compiled_insert($tablename));
            }
        }

        // remove
        foreach ($remove as $key => $value) {
            //checo se existe
            $this->CI->db->reset_query();
            $this->CI->db->where(array($ak1_column => $ak1_value, $ak2_column => $value));

            $this->CI->db->set('dt_deactivated', 'now()', false);
            array_push($sql, $this->CI->db->get_compiled_update($tablename));
        }

        if (count($sql) == 0) {
            return 'OK';
        }

        $ownControl = $this->CI->db->db_debug;

        if ($ownControl) {
            $this->trans_begin();
        }

        foreach ($sql as $key => $value) {

            $this->CI->db->query($value);

            if (!$this->trans_status()) {
                $error = $this->trans_last_error();
                $this->trans_rollback();

                if ($ownControl) {
                    die($error);
                } else {
                    return $error;
                }
            }
        }

        if ($ownControl) {
            $this->trans_commit();
        }



        return 'OK';
    }

    // funcao para flags que usam tabelas de relacionamento!!!
    function basicUpdateFlagGrid($tablename, $ak1_column, $ak1_value, $ak2_column, $gridChanges, $options = array()
    ) {

        $gridadd = array();
        $gridRemove = array();

        foreach ($gridChanges as $value) {
            if (isset($value['fl_checked'])) {
                if ($value['fl_checked'] == '1') {
                    array_push($gridadd, intval($value['recid']));
                } else {
                    array_push($gridRemove, intval($value['recid']));
                }
            }
        }

        return $this->basicUpdateSBSgrid($tablename, $ak1_column, $ak1_value, $ak2_column, $gridadd, $gridRemove, $options);
    }

    function getFilterQueryId($sql) {
        if ($this->queryByMem) {
            // se achou pela query, manda bala!
            $arrayData = $this->mem->get($this->memPrefix . 'queryArray');

            // se nao achou, coloca o array em memoria e retorna o primeiro indice
            if ($this->mem->getResultCode() == MemCached::RES_NOTFOUND) {
                $arrayData = array('nada', $sql);
                $this->mem->add($this->memPrefix . 'queryArray', $arrayData);
                return 1;
            }

            // se achou o sql, retorna o indice!
            $ret = array_search($sql, $arrayData);
            if ($ret) {
                return $ret;
            }

            // se nao achou, adiciona e retorna o ultimo indice
            array_push($arrayData, $sql);
            $this->mem->set($this->memPrefix . 'queryArray', $arrayData);

            return count($arrayData) - 1;
        } else {

            $query = "select getFilterQueryId('" . str_replace("'", "''", $sql) . "') as id";

            $r = $this->CIBasicQuery($query);
            $this->trans_commit();

            $a = $r->result_array();
            return $a[0]['id'];
        }
    }

    function getFilterQuery($id) {
        if ($this->queryByMem) {
            $array = $this->mem->get($this->memPrefix . 'queryArray');
            return $array[$id];
        }

        $query = "select getFilterQuery(" . $id . ") as query";

        $r = $this->CIBasicQuery($query);
        $this->trans_commit();

        $a = $r->result_array();
        return $a[0]["query"];
    }

    function getSettings($id) {

        return $this->CI->settings_model->getSetting($id);
    }

    function getSystemParameters($id) {

        $this->CI->load->model('system_parameters_model');
        return $this->CI->system_parameters_model->getParameter($id);
    }

    function getUserPermission($id) {

        $id = $this->normalizeDataToSQL('int', $id);

        $cd_human_resource = $this->CI->session->userdata('cd_human_resource');

        $query = "select getUserPermission('" . $id . "', " . $cd_human_resource . ") as id";
        $r = $this->CIBasicQuery($query);

        $a = $r->result_array();
        return $a[0]['id'];
    }

    //mem cache coisas!
    public function getMemVarArray($var) {
        $ret = $this->mem->get($var);
        if ($this->mem->getResultCode() == MemCached::RES_NOTFOUND) {
            $ret = array();
        }

        return $ret;
    }

    public function setMemVar($key, $value) {
        $this->mem->add($this->memPrefix . $key, $value);
    }

    public function removeMemVar($key) {
        $this->mem->delete($this->memPrefix . $key);
    }

    public function setReport($report, $table, $title = '') {

        if ($title == '') {
            $title = 'Report';
        }

        $this->CI->db->reset_query();
        $this->CI->db->select('cd_system_reports');
        $this->CI->db->where('ds_system_reports', $report);

        $sql = $this->CI->db->get_compiled_select('SYSTEM_REPORTS');

        //$sql = 'SELECT cd_system_reports FROM "SYSTEM_REPORTS" where ds_system_reports = ' . "'" . $report . "'";
        $query = $this->CIBasicQuery($sql);

        if ($query->num_rows() > 0) {
            $ret = $query->result_array();
            return $ret[0]['cd_system_reports'];
        }

        // se tah aqui eh pq naao encontrou;

        $id = $this->getNextCode('"SYSTEM_REPORTS_cd_system_reports_seq"');

        $this->CI->db->reset_query();
        $this->CI->db->set('cd_system_reports', $id);
        $this->CI->db->set('ds_system_reports', $report);
        $this->CI->db->set('ds_system_reports_table_join', $table);
        $this->CI->db->set('ds_system_reports_title', $title);
        $sql = $this->CI->db->get_compiled_insert('SYSTEM_REPORTS');

        //$sql = 'insert into "SYSTEM_REPORTS" ( ds_system_reports, ds_system_reports_table_join, ds_system_reports_title ) values (' . "'" . $report . "', '" . $table . "', '" . $title . "' ) returning cd_system_reports";
        $query = $this->CIBasicQuery($sql);

        return $id;
    }

    public function getReport($id) {

        $this->CI->db->reset_query();
        $this->CI->db->select('ds_system_reports as report');
        $this->CI->db->select('ds_system_reports_table_join as table');
        $this->CI->db->select('ds_system_reports_title');
        $this->CI->db->where('cd_system_reports', $id);

        $sql = $this->CI->db->get_compiled_select('SYSTEM_REPORTS');
        //die ($sql);
        //$sql = 'SELECT ds_system_reports as report, ds_system_reports_table_join as table, ds_system_reports_title FROM "SYSTEM_REPORTS" where cd_system_reports = ' . $id;
        $query = $this->CIBasicQuery($sql);
        $ret = $query->result_array();

        return $ret[0];
    }

    public function getReportAuth($id, $filetype, $extension, $sql = '', $filenameprefix = '', $jsonParam = array()) {
        $time = microtime(true);
        //$jsonParam['cd_system_product_category'] = 1;

        $ds_human_resource = $this->CI->session->userdata('ds_human_resource');

        $md5 = md5($ds_human_resource . $time);


        $syslang = $this->getSettings('cd_system_languages');

        $filename = $this->getSystemParameters('TEMP_PATH_SAVE_REPORTS') . $filenameprefix . $md5 . '.' . $extension;

        $jsonx = "'" . json_encode($jsonParam) . "'";

        if ($this->CI->db->dbdriver == 'postgre') {
            $jsonx = $jsonx . '::jsonb';
            $sql = str_replace("'", "''", $sql);
        } else {
            //$sql = str_replace("'", "'", $sql);
        }

        $this->CI->db->reset_query();

        $this->CI->db->set('cd_system_reports', $id);
        $this->CI->db->set('ds_authorization', $md5);
        $this->CI->db->set('nr_file_type', $filetype);
        $this->CI->db->set('ds_where', $sql);
        $this->CI->db->set('cd_system_languages', $syslang);
        $this->CI->db->set('ds_sys_report_auth_username', $ds_human_resource);
        $this->CI->db->set('ds_sys_report_auth_filename', $filename);
        $this->CI->db->set('ds_sys_report_auth_extension', $extension);
        $this->CI->db->set('ds_sys_report_auth_extension', $extension);
        $this->CI->db->set('ds_json_more_parms', $jsonx, false);

        // quando MySQL insere o json na tabela de param

        $sql = $this->CI->db->get_compiled_insert('SYSTEM_REPORTS_AUTHORIZATION');
        //die ($sql);
        //$sql = 'insert into "SYSTEM_REPORTS_AUTHORIZATION" ( cd_system_reports, ds_authorization, nr_file_type, ds_where, cd_system_languages, ds_sys_report_auth_username, ds_sys_report_auth_filename, ds_sys_report_auth_extension, ds_json_more_parms ) values (' . $id . ", '" . $md5 . "'," . $filetype . ", '" . $sql . "', " . $syslang . ", '" . $ds_human_resource . "', '" . $filename . "', '" . $extension . "', '" . json_encode($jsonParam) . "'::jsonb )";
        $query = $this->CIBasicQuery($sql);


        if ($this->CI->db->dbdriver == 'mysqli') {
            foreach ($jsonParam as $key => $value) {
                $this->CI->db->reset_query();
                $this->CI->db->set('cd_system_reports', $id);
                $this->CI->db->set('ds_authorization', $md5);
                $this->CI->db->set('ds_key', $key);
                $this->CI->db->set('ds_value', $value);
                $sql = $this->CI->db->get_compiled_insert('SYSTEM_REPORTS_AUTHORIZATION_PARAM');
                $query = $this->CIBasicQuery($sql);
            }
        }




        return array('md5' => $md5, 'filename' => $filename);
    }

    public function removeReportAuth($id, $md5) {
        $this->CI->db->reset_query();
        $this->CI->db->where('cd_system_reports', $id);
        $this->CI->db->where('ds_authorization', $md5);

        $sql = $this->CI->db->get_compiled_delete('SYSTEM_REPORTS_AUTHORIZATION');


        //$sql = 'delete from "SYSTEM_REPORTS_AUTHORIZATION" where cd_system_reports = ' . $id . ' and ds_authorization = ' . "'" . $md5 . "'";
        //$this->CIBasicQuery($sql);
    }

    public function updReportFileName($id, $md5, $filename, $extension) {
        $this->CI->db->reset_query();
        $this->CI->db->set('ds_sys_report_auth_filename', $filename);
        $this->CI->db->set('ds_sys_report_auth_extension', $extension);
        $this->CI->db->where('cd_system_reports', $id);
        $this->CI->db->where('ds_authorization', $md5);

        $sql = $this->CI->db->get_compiled_update('SYSTEM_REPORTS_AUTHORIZATION');

        $this->CIBasicQuery($sql);
    }

    public function makeReportString($rptname, $type, $id, $md5) {

        $folder = $this->getSettings('mboard_birt_folder');
        $str_connect = $this->getSettings('mboard_birt_webserver');

        $str_connect = str_replace('%1', $folder . urlencode($rptname), $str_connect);
        $str_connect = str_replace('%2', $type, $str_connect);
        $str_connect = str_replace('%3', $id, $str_connect);
        $str_connect = str_replace('%4', $md5, $str_connect);

        return $str_connect;
    }

    public function getFieldNames($sql) {
        $ret = array();
        $id = 'a';

        $query = $this->CI->db->query($sql);

        foreach ($query->list_fields() as $field) {

            if ($this->CI->db->dbdriver == 'postgre') {
                $ret[$field] = $id;
                $id ++;
            } else {
                $ret[$field] = $field;
            }
        }

        $ret = json_encode($ret);

        return $ret;
    }

    public function cgbFileUploadParse($files) {
        $tmpdir = $this->getSystemParameters('TEMP_PATH');
        $files = json_decode($files, true);
        $info = array();


        foreach ($files as $key => $value) {

            $data = array();
            $data['filename'] = $value['fileInfo']['name'];
            $data['size'] = $value['fileInfo']['size'];
            $data['type'] = $value['fileInfo']['type'];
            $data['additional'] = $value['additionalData'];

            $tmpfile = $tmpdir . $key . $data['filename'];

            $decoded = $this->base64url_decode($value['fileInfo']['base64data'], $value['fileInfo']['type']);

            $out = fopen($tmpfile, "w");
            fwrite($out, $decoded);
            fclose($out);
            //die ($tmpfile);
            $data['tmp_filename'] = $tmpfile;
            array_push($info, $data);
        }

        //$tmpdir

        return $info;
    }

    function base64url_decode($base64url, $mimeType) {
        $base64url = str_replace('data:' . $mimeType . ';base64,', '', $base64url);
        //$base64 = $base64url;
        $base64 = strtr($base64url, '-_', '+/=');

        $plainText = base64_decode($base64);
        return ($plainText);
    }

    function base64urd_encode($file) {
        $image = base64_encode(file_get_contents($file));
        $src = 'data: ' . mime_content_type($file) . ';base64,' . $image;
        return $src;
    }

    function getUserPicture($cd_user, $type) {
        //$type = 'F' -> file , 'C' = Content Mime
        $userPath = $this->getSystemParameters('PATH_USER_PICTURES');

        if (file_exists($userPath . $cd_user . '.jpg')) {
            $file = $userPath . $cd_user . '.jpg';
        } else {
            $file = $userPath . 'default.jpg';
        }

        if (strpos(mime_content_type($file), 'png') !== FALSE) {
            $file = $userPath . 'default.jpg';
        }

        if ($type == 'F') {
            return $file;
        }



        $image = new Imagick($file);
        $image->thumbnailImage(100, 0);

        if ($type == 'C') {
            $src = 'data: ' . mime_content_type($file) . ';base64,' . base64_encode($image);

            //$image = base64_encode(file_get_contents($file));
            //= 'data: ' . mime_content_type($file) . ';base64,' . $image;
            return $src;
        }
    }

    function saveUserPicture($cd_user, $picture) {
        $userPath = $this->getSystemParameters('PATH_USER_PICTURES');
        $file = $userPath . $cd_user . '.jpg';
        rename($picture, $file);
    }

    public function createGridResultSetFormOrder($option = array()) {

        $baseArray = array(
            'orderFieldName' => 'NONE',
            'indexRSFind' => '0',
            'indexRSFieldName' => 'NONE',
            'fields' => array(),
            'pkField' => '',
            'fixedData' => array(),
            'deleteField' => 'NONE'
        );


        $mergedArray = array_merge($baseArray, $option);
        $fields = $mergedArray['fields'];
        $resultset = array();
        $resultset_tmp = array();
        $resultset_del = array();


        foreach ($fields as $key => $value) {
            IF ($value == array()) {
                continue;
            }

            $valuearray = json_decode(json_encode($value), true);

            //$resfind = $value[0];
            //echo($resfind);
            //echo('<br>');
            foreach ($valuearray as $keyRS => $valueRS) {

                if ($mergedArray['indexRSFind'] == -1 || $mergedArray['indexRSFind'] == $keyRS) {

                    foreach ($valueRS as $keyi => $valuei) {
                        if ($key == $mergedArray['pkField']) {
                            $resultset_tmp[$keyRS][$keyi]['recid'] = $valuei;
                        } else {
                            $resultset_tmp[$keyRS][$keyi][$key] = $valuei;
                        }

                        if ($mergedArray['orderFieldName'] != 'NONE') {
                            $resultset_tmp[$keyRS][$keyi][$mergedArray['orderFieldName']] = $keyi;
                        }

                        if ($mergedArray['indexRSFieldName'] != 'NONE') {
                            $resultset_tmp[$keyRS][$keyi][$mergedArray['indexRSFieldName']] = $keyRS;
                        }
                    }
                }
            }
        }


        foreach ($resultset_tmp as $keyRS => $valueRS) {

            foreach ($valueRS as $key => $value) {
                // se tem o deleteField e ele eh -2, quer dizer que eh para delecao!
                if (isset($value[$mergedArray['deleteField']])) {
                    if ($value[$mergedArray['deleteField']] == -2) {
                        array_push($resultset_del, $value + $mergedArray['fixedData']);
                        continue;
                    }
                }

                array_push($resultset, $value + $mergedArray['fixedData']);
            }
        }


        $ret = array('upd' => $resultset, 'del' => $resultset_del);



        return (object) $ret;
    }

    public static function getBrowser() {
        // check if IE 8 - 11+
        preg_match('/Trident\/(.*)/', $_SERVER['HTTP_USER_AGENT'], $matches);
        if ($matches) {
            $version = intval($matches[1]) + 4;     // Trident 4 for IE8, 5 for IE9, etc
            return 'Internet Explorer ' . ($version < 11 ? $version : 'Edge');
        }

        // check if IE 6 - 7
        // you don't need this as of 2014, but who knows what's your project specifications.
        /* preg_match('/MSIE (.*)/', $_SERVER['HTTP_USER_AGENT'], $matches);
          if ($matches) {
          return 'Internet Explorer '.intval($matches[1]);
          } */

        // check if Firefox, Opera, Chrome, Safari
        foreach (array('Firefox', 'OPR', 'Chrome', 'Safari') as $browser) {
            preg_match('/' . $browser . '/', $_SERVER['HTTP_USER_AGENT'], $matches);
            if ($matches) {
                return strtolower(str_replace('OPR', 'Opera', $browser));   // we don't care about the version, because this is a modern browser that updates itself unlike IE
            }
        }
    }

    public function treatDbError($error) {

        $original_error = $error;
        $newError = $error;
        if (strpos($error, 'is still referenced from table') !== false) {
            $newError = $this->retTranslation('Cannot delete this record because it is in use by a related table');
        }

        if (strpos($error, 'duplicate key value violates unique') !== false) {
            $inicial = strpos($error, '=(') + 2;
            $final = strpos($error, ')', $inicial);

            $data = substr($error, $inicial, ($final - $inicial));
            $msg = 'Cannot update because the data already exists in the Table';
            $msg = $this->retTranslation($msg);

            $newError = "<br> " . $msg . " <br> Data: " . $data;

            //Key (ds_season)=(FALL 12 S13) already exists.PREPARE gridComm (int,c
        }

        if (strpos($error, 'null value in column') !== false OR strpos($error, 'not-null constraint')) {
            $newError = $this->retTranslation('Cannot Update because Demanded information is missing');
        }

        if (strpos($error, 'retresultsetjson8') !== false) {
            $newError = $this->retTranslation('Error Retrieving Information. Contact System Administrator');
        }



        $this->trans_commit();

        if ($this->getSettings('fl_debug_mode') == 'Y') {
            if ($original_error != $newError) {
                $newError = $newError . '<br>' . $original_error;
            }
            $newError = $newError . '<br>' . $this->lastSQL;
        }

        return $newError;
    }

    public function getNotNullColumns($table) {

        $sql = "select column_name
                from information_schema.columns 
                where table_name = '" . $table . "'
                  and is_nullable = 'NO' and column_name not like 'fl_%'";


        $ret = array();
        $q = $this->basicSQLArray($sql);

        foreach ($q as $key => $value) {
            array_push($ret, $value['column_name']);
        }

        return $ret;
    }

    public function loginSave($user, $session) {

        $database = $_SERVER['pgsqldb'];

        $this->CI->db->reset_query();
        $this->CI->db->select('fl_super_user')->where('ds_human_resource', $user);
        $sql = $this->CI->db->get_compiled_select('HUMAN_RESOURCE');

        $array_user = $this->basicSQLArray($sql);

        $this->CI->db->reset_query();
        $this->CI->db->set('dt_expired', 'now()', false)->where('ds_database', $database)->where('ds_username', $user)->where('dt_expired is null');
        if (count($array_user) > 0) {
            if ($array_user[0]['fl_super_user'] == 'Y') {
                $this->CI->db->where('ds_session', $session);
            }
        }
        

        $sql = $this->CI->db->get_compiled_update('SESSION_LOG');
        // limpo algum log passado que ficou aberto
        $this->basicSQLNoReturn($sql);

        $this->CI->db->reset_query();
        $this->CI->db->set('ds_database', $database)->
                set('ds_session', $session)->
                set('ds_username', $user)->
                set('dt_logged', 'now()', false)->
                set('dt_last_access', 'now()', false)->
                set('dt_expired', 'null', false);

        if ($this->CI->db->dbdriver != 'postgre') {
            $id = $this->getNextCode('SESSION_LOG');
            $this->CI->db->set('cd_session_log', $id);
        }

        $sql = $this->CI->db->get_compiled_insert('SESSION_LOG');



        //die ($sql);
        //die ($sql);

        $this->basicSQLNoReturn($sql);

        // busco o ultimo codigo
        if ($this->CI->db->dbdriver == 'postgre') {
            $this->CI->db->reset_query();

            $sql = $this->CI->db->select('max(cd_session_log) as max', false)
                            ->where('ds_database', $database)
                            ->where('ds_session', $session)
                            ->where('ds_username', $user)->get_compiled_select('SESSION_LOG');

            $ret = $this->basicSQLArray($sql);

            $id = $ret[0]['max'];
        }


        return $id;
    }

    public function loginIsExpired($cd_session_log) {

        RETURN FALSE;

        //die (print_r('x' . $cd_session_log));
        $this->CI->db->reset_query();
        if ($this->CI->db->dbdriver == 'postgre') {
            $intervalSQL = "now() - dt_last_access > '02:00:00'::interval";
        } else {
            $intervalSQL = "abs(TIMESTAMPDIFF(MINUTE,NOW(), dt_last_access)) > 120";
        }

        $sql = $this->CI->db->select('1')
                        ->where('cd_session_log', $cd_session_log)
                        ->where("(dt_expired IS NOT NULL OR $intervalSQL )")->get_compiled_select('SESSION_LOG');


        //$sql = "select 1 from \"SESSION_LOG\" where  cd_session_log = $cd_session_log AND (dt_expired IS NOT NULL OR now() - dt_last_access > '02:00:00'::interval ) ";
        //die ($sql .'<br>'.$sel2);

        $ret = $this->basicSQLArray($sql);

        RETURN count($ret) > 0;
    }

    public function logoutSave($cd_session_log) {
        $this->CI->db->reset_query();

        $sql = $this->CI->db->set('dt_expired', 'now()', false)
                        ->where('cd_session_log', $cd_session_log)->where('dt_expired IS NULL')->get_compiled_update('SESSION_LOG');

        //die ($sql);
        //$sql = "UPDATE \"SESSION_LOG\" set dt_expired = now() WHERE cd_session_log = $cd_session_log and dt_expired IS NULL";
        $this->basicSQLNoReturn($sql);
    }

    public function loginUpdate($cd_session_log) {
        $this->CI->db->reset_query();

        if ($this->CI->db->dbdriver == 'postgre') {
            $intervalSQL = "now() - dt_last_access > '00:10:00'::interval";
        } else {
            $intervalSQL = "abs(TIMESTAMPDIFF(MINUTE,NOW(), dt_last_access)) > 2";
        }


        $sql = $this->CI->db->set('dt_last_access', 'now()', false)
                ->where('cd_session_log', $cd_session_log)
                ->where($intervalSQL)
                ->get_compiled_update('SESSION_LOG');

        $this->basicSQLNoReturn($sql);
    }

    public function checkMaxConnections() {
        $this->CI->db->reset_query();

        $database = $_SERVER['pgsqldb'];

        $this->CI->db->reset_query();
        $sql = $this->CI->db->select('count(1) as nr_count')->join('HUMAN_RESOURCE as h', "h.ds_human_resource = s.ds_username AND h.fl_super_user = 'N'")
                ->where("s.ds_database = '$database' AND s.dt_expired IS  NULL")
                ->get_compiled_select('SESSION_LOG as s');


        // conto soh os nao "super users"
        //$sql = "select count(1) as nr_count from \"SESSION_LOG\" s, \"HUMAN_RESOURCE\" h where s.ds_database = '$database' AND s.dt_expired IS  NULL AND h.ds_human_resource = s.ds_username AND h.fl_super_user = 'N' ";
        $ret = $this->basicSQLArray($sql);

        if ($ret[0]['nr_count'] >= $this->CI->db->companyMaxConnection) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function __array_to_xml($array, &$xml_user_info, $secondLevel = null) {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                if (!is_numeric($key)) {
                    $subnode = $xml_user_info->addChild("$key");
                    $this->__array_to_xml($value, $subnode);
                } else {
                    if ($secondLevel == null) {
                        $subnode = $xml_user_info->addChild("row$key");
                    } else {
                        $subnode = $xml_user_info->addChild($secondLevel);
                    }
                    $this->__array_to_xml($value, $subnode);
                }
            } else {
                $xml_user_info->addChild("$key", htmlspecialchars("$value"));
            }
        }
    }

    public function array_to_xml($array, $rootElement, $secondLevel = null) {
        $xml_user_info = new SimpleXMLElement("<?xml version=\"1.0\"?><$rootElement></$rootElement>");

//function call to convert array to xml
        $this->__array_to_xml($array, $xml_user_info, $secondLevel);

//saving generated xml file
        return $xml_user_info->asXML();
    }

    public function setDbVars($key, $data) {
        // CGB - Seta variavel de sessao no banco
        $this->basicSQLNoReturn("select setvar('" . $key . "','" . $data . "');");
        //$this->CI->db->query("select setvar('" . $key . "','" . $data . "');");
    }

    public function setDbVarsArray($array) {
        $sql = 'select ';

        foreach ($array as $key => $value) {
            $sql = $sql . " setvar('" . $key . "','" . $value . "'), ";
        }

        $sql = $sql . ' 1';
        // CGB - Seta variavel de sessao no banco
        $this->basicSQLNoReturn($sql);
        //$this->CI->db->query("select setvar('" . $key . "','" . $data . "');");
    }

    public function getDbVars($key) {
        // CGB - Pega variavel de sessao no banco
        $sql = "select getvar('" . $key . "') as key";
        $q = $this->CI->db->query($sql);

        $array = $q->result_array();
        $ret = $array[0]['key'];

        return $ret;
    }

    public function getNow() {
        return date($this->dateFormatPHP);
    }

    public function getDBTimeStamp() {
        $sql = "select now() as  nowdate";
        $array = $this->basicSQLArray($sql);
        
        return $array[0]['nowdate'];
    }
    
    public function getDBTimeStampFormatted() {
        $sql = "select to_char(now(), 'MM/DD/YYYY HH24:MI') as  nowdate";
        $array = $this->basicSQLArray($sql);
        
        return $array[0]['nowdate'];
    }
    
    public function encryptModel($model) {
        if ($this->modelByMem) {
            //$this->removeMemVar($this->memPrefix . 'modelArray');
            // se achou pela query, manda bala!
            $arrayData = $this->mem->get($this->memPrefix . 'modelArray');
            //print_r($arrayData);
            // se nao achou, coloca o array em memoria e retorna o primeiro indice
            if ($this->mem->getResultCode() == MemCached::RES_NOTFOUND) {
                $enc = $this->CI->encryption->encrypt($model);
                $arrayData[$model] = $enc;
                $this->mem->add($this->memPrefix . 'modelArray', $arrayData);
                return $enc;
            }

            if (!isset($arrayData[$model])) {
                $enc = $this->CI->encryption->encrypt($model);
                $arrayData[$model] = $enc;
                $this->mem->set($this->memPrefix . 'modelArray', $arrayData);
            }

            return $arrayData[$model];
        } else { // not by memory
            return $this->CI->encryption->encrypt($model);
        }
    }

    function check_database($password, $username = '') {
        //Field validation succeeded.  Validate against database
        $this->CI->load->model('human_resource', '', TRUE);
        $this->CI->load->library('ldaphelper');
        $this->CI->load->model('system_product_category_model', '', TRUE);

        //query the database
        $result = $this->CI->human_resource->login($username, $password);

        if ($result) {
            $sess_array = array();

            foreach ($result as $row) {
                if ($row->fl_ldap == 'Y') {
                    if (!$this->CI->ldaphelper->checkLogin($row->ds_human_resource, $password, $row->nr_login_mode)) {
                        return 'Invalid username or password ' . $this->CI->ldaphelper->errormsg;
                        return false;
                    }
                } else {
                    if ($row->ds_password != md5($password)) {
                        return 'Invalid username or password 2';
                    }
                }


                if ($row->fl_super_user == 'N') {
                    //if (!$this->cdbhelper->checkMaxConnections()) {
                    //  Return 'Max Connection Reached';
                    //}
                }

                $sess_array = array(
                    'cd_human_resource' => $row->cd_human_resource,
                    'ds_human_resource' => $row->ds_human_resource,
                    'ds_human_resource_full' => $row->ds_human_resource_full,
                    'ds_e_mail' => $row->ds_e_mail,
                    'cd_system_languages' => '1',
                    'fl_super_user' => $row->fl_super_user,
                    'password' => $password,
                    'cn' => $row->ds_cn
                );


                $this->CI->session->set_userdata($sess_array);


                $prdcat = $this->CI->system_product_category_model->getProductCategoryByUser($row->cd_human_resource);

                if (count($prdcat) == 0) {
                    $this->CI->session->unset_userdata(array('cd_human_resource', 'ds_human_resource', 'ds_human_resource_full', 'cd_system_languages', 'fl_super_user'));

                    RETURN 'You have no permission to any Location';
                }

                $prdcat_cookie = get_cookie('dfdevshoesPrdCat' . $_SERVER['custinfo']);
                //&& array_search(40489, array_column($prdcat, 'cd_system_product_category'))

                if ($prdcat_cookie !== null && array_search($prdcat_cookie, array_column($prdcat, 'cd_system_product_category')) !== false) {
                    $defprdcat = $prdcat_cookie;
                } else {
                    $defprdcat = $prdcat[0]['cd_system_product_category'];
                }

                $sess_array['system_product_category_allowed'] = $prdcat;
                $sess_array['system_product_category'] = $defprdcat;
                $this->CI->session->set_userdata($sess_array);



                $sess = $this->loginSave($row->ds_human_resource, session_id());
                $sess_array = $sess_array + array('cd_session_log' => $sess);

                $this->CI->session->set_userdata($sess_array);
                set_cookie('dfdevshoesPrdCat' . $_SERVER['custinfo'], $defprdcat);
            }

            $this->setInitialVars();

            return 'OK';
        } else {
            return "Invalid username or password";
        }
    }

    public function getTableLastTimeStamp($tablename) {
        $sql = "select * from \"OPERATOR_TABLE_TIMESTAMP\" where ds_table_name = '$tablename'";
        $array = $this->basicSQLArray($sql);

        if (count($array) == 0) {
            return 0;
        }

        return $array[0]['nr_timestamp_reference'];
    }

    public function setTableLastTimeStamp($tablename, $newTimeStamp) {

        $sql = "select * from \"OPERATOR_TABLE_TIMESTAMP\" where ds_table_name = '$tablename'";
        $array = $this->basicSQLArray($sql);
        $this->CI->db->reset_query();
        $this->CI->db->set('ds_table_name', $tablename);
        $this->CI->db->set('nr_timestamp_reference', $newTimeStamp);
        $this->CI->db->where('ds_table_name', $tablename);




        if (count($array) > 0) {
            $sql = $this->CI->db->get_compiled_update('OPERATOR_TABLE_TIMESTAMP');
        } else {
            $sql = $this->CI->db->get_compiled_insert('OPERATOR_TABLE_TIMESTAMP');
        }

        $this->CIBasicQuery($sql);
    }

}
