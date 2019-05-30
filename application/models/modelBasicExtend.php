<?php

class modelBasicExtend extends CI_Model {

    public
    // Controller do modelo;
            $controller = '',
            // nome da tabela
            $table,
            // coluna de PK
            $pk_field,
            // coluna de descricao
            $ds_field,
            // objeto de sequencia
            $sequence_obj,
            // se tem deactivate
            $hasDeactivate = true,
            // fields!
            $fieldsforGrid = array(),
            $fieldsForPLBaseDD = array(),
            // opcoes de retrieve
            $retrOptions = array(),
            // array de exclusao de array!
            $fieldsExcludeUpd = array(),
            // array de joins!
            $joinsForGrid = array(),
            //addtablename
            $addTableName = false,
            //picklist demand inform filter;
            $basicWhereForPL = '',
            $PLDemandFilter = 'N',
            $RecIdsNegative = array(),
            $fieldsForPLBase = array(),
            $retOptionsForPLBase = array(),
            $retOptionsForPLBaseDD = array(),
            $prodCatUnique = 'N',
            $orderByDefault = 'ORDER BY 2',
            $orderByDefaultPL = 'ORDER BY 1';

    function __construct() {



        parent::__construct();


        /*
          // informacoes basicas da tabela!
          $this->table = "PRODUCT_ATTRIBUTES";
          $this->pk_field = "cd_product_attributes";
          $this->ds_field = "ds_product_attributes";
          $this->sequence_obj = '"PRODUCT_ATTRIBUTES_cd_product_attributes_seq"';

          // informacoes de fields do grid!!!!
          $this->fieldsforGrid = array($this->pk_field,
          $this->ds_field,
          'dt_deactivated'
          );

          // opcoes de retrieve
          $this->retrOptions = array ("fieldrecid" => $this->pk_field,
          //'subselects' => '',
          'fields' => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
          'stylecond'  => "(CASE WHEN dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
          'json' => true,
          );

          // campos excluidos dos UPDS (especialmente por conta dos PLs)
          $this->fieldsExcludeUpd = array('ds_unit_measure_type', 'ds_unit_measure_lenght_base');
         */
    }

    //
    public function selectdb($where = '', $order = '') {
        return $this->cdbhelper->basicSelectDb($this->table, $where, $order);
    }
    /**Returns an array with the data on the Picklist Format (recid and description fields) 返回包含Picklist格式（recid和description字段）数据的数组
     * @param string $where The where statement (starting with ” WHERE ”).  where语句（以“WHERE”开头）。
     * @param string $unionPK   Add a specific record on the return.    在返回时添加特定记录。
     * @return mixed    String in an array format with the result of the query. 具有查询结果的数组格式的字符串。
     */
    // essa funcao eh especifica para evitar de ter select -> array -> correr para criar outro array
    public function selectForPLWithOrder($where = '', $unionPK = "", $orderBy = "") {
        //die ('TESTE ' . $where);
        if ($this->fieldsForPLBase == array()) {
            $this->fieldsForPLBase = array('(' . $this->ds_field . ') as description');
            if ($this->hasDeactivate) {
                array_push($this->fieldsForPLBase, "( CASE WHEN dt_deactivated IS NULL THEN 'Y' ELSE 'N' END) as fl_active ");
            } else {
                array_push($this->fieldsForPLBase, "( 'Y' ) as fl_active ");
            }
        }

        if ($orderBy == '') {
            $orderBy = $this->orderByDefaultPL;
        }
        
        if ($this->retOptionsForPLBase == array()) {


            $this->retOptionsForPLBase = array("fieldrecid" => $this->pk_field,
                'fields' => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsForPLBase),
                'json' => false
            );
           
            if ($this->hasDeactivate) {
                $this->retOptionsForPLBase['stylecond'] = "(CASE WHEN dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )";
            }
        }


        $options = $this->retOptionsForPLBase;

        $options['json'] = false;
        $options['prodCatUnique'] = $this->prodCatUnique;

        $x = $this->cdbhelper->basicSelectDb($this->table, $where, $orderBy, $options);
        
        
        return $x;

        //return $this->cdbhelper->basicSelectForPL($this->table, $this->pk_field, $this->ds_field, $where, $unionPK, $this->hasDeactivate);
    }
    
    
    /**Returns an array with the data on the Picklist Format (recid and description fields) 返回包含Picklist格式（recid和description字段）数据的数组
     * @param string $where The where statement (starting with ” WHERE ”).  where语句（以“WHERE”开头）。
     * @param string $unionPK   Add a specific record on the return.    在返回时添加特定记录。
     * @return mixed    String in an array format with the result of the query. 具有查询结果的数组格式的字符串。
     */
    // essa funcao eh especifica para evitar de ter select -> array -> correr para criar outro array
    public function selectForPL($where = '', $unionPK = "") {
        //die ('TESTE ' . $where);
        if ($this->fieldsForPLBase == array()) {
            $this->fieldsForPLBase = array('(' . $this->ds_field . ') as description');
            if ($this->hasDeactivate) {
                array_push($this->fieldsForPLBase, "( CASE WHEN dt_deactivated IS NULL THEN 'Y' ELSE 'N' END) as fl_active ");
            } else {
                array_push($this->fieldsForPLBase, "( 'Y' ) as fl_active ");
            }
        }

        
        $orderBy = $this->orderByDefaultPL;

        
        if ($this->retOptionsForPLBase == array()) {


            $this->retOptionsForPLBase = array("fieldrecid" => $this->pk_field,
                'fields' => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsForPLBase),
                'json' => false
            );
           
            if ($this->hasDeactivate) {
                $this->retOptionsForPLBase['stylecond'] = "(CASE WHEN dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )";
            }
        }


        $options = $this->retOptionsForPLBase;

        $options['json'] = false;
        $options['prodCatUnique'] = $this->prodCatUnique;

        $x = $this->cdbhelper->basicSelectDb($this->table, $where, $orderBy, $options);
        
        
        return $x;

        //return $this->cdbhelper->basicSelectForPL($this->table, $this->pk_field, $this->ds_field, $where, $unionPK, $this->hasDeactivate);
    }

    /**Returns an array with the data on the Picklist Dropdown Format (id and text fields)  返回包含Picklist下拉格式（id和文本字段）数据的数组
     * @param string $where The where statement (starting with ” WHERE ”).  where语句（以“WHERE”开头）。
     * @param string $unionPK   Add a specific record on the return.    在返回时添加特定记录。
     * @param string $searchTerm    string to find, in case of demanding a minimum characters quantity. 在要求最小字符数量的情况下找到的字符串。
     * @return mixed    String in an array format with the result of the query. 具有查询结果的数组格式的字符串。
     */

    public function selectForPLD($where = '', $unionPK = "", $searchTerm = '') {
        //die ('TESTE ' . $where);
        if ($this->fieldsForPLBaseDD == array()) {
            $this->fieldsForPLBaseDD = array('(' . $this->ds_field . ') as text',
                '(' . $this->pk_field . ') as id');
            
            $searchfield = $this->ds_field;
            
        } else {
            if ($this->retOptionsForPLBaseDD == array()) {
                $searchfield = $this->fieldsForPLBaseDD[1];
                
                $this->fieldsForPLBaseDD[0] = '(' . $this->fieldsForPLBaseDD[0] . ') as id';
                $this->fieldsForPLBaseDD[1] = '(' . $this->fieldsForPLBaseDD[1] . ') as text';
                
            }
        }
        
        if ($searchTerm != '' ) {
            $where = $where . " AND $searchfield ilike '%$searchTerm%'";
        }


        if ($this->hasDeactivate) {
            array_push($this->fieldsForPLBaseDD, "( CASE WHEN dt_deactivated IS NULL THEN 'Y' ELSE 'N' END) as fl_active ");
        } else {
            array_push($this->fieldsForPLBaseDD, "( 'Y' ) as fl_active ");
        }

                      

        if ($this->retOptionsForPLBaseDD == array()) {


            $this->retOptionsForPLBaseDD = array("fieldrecid" => $this->pk_field,
                'fields' => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsForPLBaseDD),
                'json' => false
            );

            if ($this->hasDeactivate) {
                $this->retOptionsForPLBaseDD['stylecond'] = "(CASE WHEN dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )";
            }
        }


        $options = $this->retOptionsForPLBaseDD;

        $options['json'] = true;
        $options['prodCatUnique'] = $this->prodCatUnique;

        $x = $this->cdbhelper->basicSelectDb($this->table, $where, $this->orderByDefaultPL, $options);
        
        return $x;


        //return $this->cdbhelper->basicSelectForPL($this->table, $this->pk_field, $this->ds_field, $where, $unionPK, $this->hasDeactivate);
    }

    // insercao generico (com desc e deactivate apenas)!
    public function insertdb($desc, $dt_deactivated) {
        return $this->cdbhelper->basicInsertDb($this->table, $this->ds_field, $desc, $dt_deactivated);
    }

    // update basico (soh id/ desc e dt_deactivate)
    public function updatedb($id, $desc, $dt_deactivate) {
        return $this->cdbhelper->basicUpdateDb($this->table, $this->pk_field, $this->ds_field, $id, $desc, $dt_deactivate);
    }
    /** Insert/Update the table with the array  使用数组插入/更新表
     * @param $array    Array with the rows to insert/update. The array will have an array with the key as column name, and the value as the value. 包含要插入/更新的行的数组。 该数组将有一个数组，其中键为列名，值为值。
     * @return mixed    “OK” if OK, if not ok returns the error message.    “OK”如果OK，如果不是ok则返回错误消息。
     */
    // updates genericos do que vem pelo grid!!!
    public function updateGridData($array) {
        // se o codigo for menor que -2, busco o nextcode; 
        $RecIdsNegative = array();
        $newarray = array();
        $onlyField = array();
        $arrayToDelete = array();

        if (isset($this->fieldsUpd)) {
            $onlyField = $this->fieldsUpd;
        }

        foreach ($array as $key => $value) {
            $value = (array) $value;
            
            if (isset($value['fl_to_delete']) && $value['fl_to_delete'] == 'Y') {
                array_push($arrayToDelete, $value['recid']);
            } else {
                if (isset($value['recid']) && $value['recid'] < -2) {
                    $n = $this->getNextCode();
                    $this->RecIdsNegative[$value['recid']] = $n;
                    $value['recid'] = $n;
                }

                array_push($newarray, $value);
            }
        }
        
        return $this->cdbhelper->updateGridData($this->table, $this->pk_field, $newarray, $this->fieldsExcludeUpd, array(), $onlyField, $arrayToDelete);
    }

    public function getNewRecIdsNegative() {
        return $this->RecIdsNegative;
    }
    /**Delete the records received on $array    删除$ array上收到的记录
     * @param $array    Array with the list of PKs to be deleted. Also can be an array with a 'recid' key pointing to the PK.   包含要删除的PK列表的数组。 也可以是一个带有'recid'键的数组，指向PK。
     * @return string   “OK” in case of well deleted.   如果删除得好，“OK”。
     */
     public function deleteGridData($array) {
        $toSend = array();

        
        if (count($array) == 0) {
            return 'OK';
        }
        // if inside the array we have another array, I consider to be receiving the index recid instead of a pure number.
        if (is_array($array[0])) {
            foreach ($array as $key => $value) {
                array_push($toSend, $value['recid']);
            }
        } else {
            $toSend = $array;
        }
        
        
        
        
        return $this->cdbhelper->deleteGridData($this->table, $this->pk_field, $toSend);
    }
    
    

    public function deletedb($pk) {
        $ret = $this->cdbhelper->basicDeleteDb($this->table, $this->pk_field, $pk);
        if ($ret) {
            return 'OK';
        } else {
            return $this->cdbhelper->trans_last_error();
        }
    }

    /**Check if the Record exists   检查记录是否存在
     * @param $id    Primary Key
     * @return mixed    True or False
     */
    // verifica se o registro existe
    public function recordExists($id) {
        return $this->cdbhelper->recordExists($this->table, $this->pk_field, $id);
    }

    /**Retrieve the table information in a json format (string).    以json格式（字符串）检索表信息。
     * @param string $where The where statement (starting with ” WHERE ”).  where语句（以“WHERE”开头）。
     * @param string $orderby   The order by statement (starting with ” ORDER BY ”).    order by语句（以“ORDER BY”开头）。
     * @param string $jsonMapping
     * @param array $retrOpt
     * @return mixed    String in a json format with the result of the query    带有查询结果的json格式的字符串
     */
    // retrieve Json do grid!
    public function retRetrieveGridJson($where = "", $orderby = '', $jsonMapping = '', $retrOpt = array()) {

        if ($orderby == "") {
            $orderby = $this->orderByDefault;
        }

        if ($retrOpt == array()) {
            $retrOpt = $this->retrOptions;
        }

        if ($jsonMapping != '') {
            $retrOpt = $retrOpt + array('jsonMapping' => $jsonMapping);
        }

        $retrOpt['prodCatUnique'] = $this->prodCatUnique;

        $ret = $this->cdbhelper->basicSelectDb($this->table, $where, $orderby, $retrOpt);
        return $ret;
    }

    /**Retrieve the table information in a array format for only one record. Used for form screens. 仅以一个记录的数组格式检索表信息。 用于表单屏幕。
     * @param $id    Primary key of the table . 表的主键。
     * @return mixed    Array with the result of the query. 包含查询结果的数组。
     */
    public function retRetrieveGridJsonForm($id) {
        return $this->retRetrieveGridJson(' WHERE ' . $this->pk_field . " = " . $id);
    }

    // retrieve Json do grid!
    public function retParseFields() {
        $ret = $this->cdbhelper->basicSelectDb($this->table, '', '', $this->retrOptions + array('getParse' => 'Y'));
        return $ret;
    }

    /**Retrieve the table information in a array format.    以数组格式检索表信息。
     * @param string $where  The where statement (starting with ” WHERE ”). where语句（以“WHERE”开头）。
     * @param string $orderby   The order by statement (starting with ” ORDER BY ”).    order by语句（以“ORDER BY”开头）。
     * @param array $options    The array that have all the statement data from model. If not send will use the retrOpt property with the basic one.    包含模型中所有语句数据的数组。 如果不是send将使用retrOpt属性和基本属性。
     * @return mixed    Array with the result of the query  包含查询结果的数组
     */
    // retrieve Json do grid!
    public function retRetrieveGridArray($where = "", $orderby = "", $options = array()) {

        if ($orderby == "") {
            $orderby = $this->orderByDefault;
        }

        if ($options == array()) {
            $options = $this->retrOptions;
        };
        $options['json'] = false;
        $options['prodCatUnique'] = $this->prodCatUnique;

        $ret = $this->cdbhelper->basicSelectDb($this->table, $where, $orderby, $options);
        return $ret;
    }

    /**Returns an empty array with all columns on $options (that is retrOptions). Used to insert new rows specifically to forms.    返回一个空数组，其中包含$ options上的所有列（即retrOptions）。 用于专门向表单插入新行。
     * @param array $options    The array that have all the statement data from model. If not send will use the retrOpt property with the basic one.    包含模型中所有语句数据的数组。 如果不是send将使用retrOpt属性和基本属性。
     * @param bool $getNextCode If system should give a recid with a next code. 如果系统应该给recid下一个代码。
     * @return mixed    Array with a new record for the table model.    包含表模型新记录的数组。
     */
    // retrieve Json do grid!
    public function retRetrieveEmptyNewArray($options = array(), $getNextCode = true) {

        if ($options == array()) {
            $options = $this->retrOptions;
        };

        $options['json'] = false;
        $array_join = array();

        if (isset($options['join'])) {
            $array_join = $options['join'];
        } else {
            $array_join = array();
        }

        $tab = $this->table;
        $pk = $this->pk_field;
        $data = 'RIGHT OUTER JOIN ' . $this->db->escape_identifiers('RECORD_GEN') . ' ON (  ' . $this->db->escape_identifiers($pk) . ' = -1 )';

        array_push($array_join, $data);

        $options['join'] = $array_join;

        //die (print_r($options));
        $ret = $this->cdbhelper->basicSelectDb($this->table, ' ', '', $options);

        $pkField = explode('.', $this->pk_field);
        $pkField = $pkField[count($pkField) - 1];
        
        if ($getNextCode) {
            $code = $this->getNextCode();
            $ret[0]['recid'] = $code;

            $ret[0][$pkField] = $code;
        }

        // coloco valor defaults
        foreach ($ret[0] as $key => $value) {
            if (is_null($value)) {

                switch (substr($key, 0, 3)) {
                    case 'dt_':
                    case 'cd_':
                    case 'ds_':

                        $ret[0][$key] = '';

                        break;
                    case 'nr_':
                    case 'vl_':

                        $ret[0][$key] = 0;

                        break;
                    default:
                        break;
                }
            }
        }



        return $ret;
    }

    // retrieve Json do grid!
    public function retRetrieveArray($where = "", $orderby = "", $options = array()) {

        if ($orderby == "") {
            $orderby = $this->orderByDefault;
        }
        if ($options == array()) {
            $options = $this->retrOptions;
        };

        $options['json'] = false;
        $options['prodCatUnique'] = $this->prodCatUnique;

        $ret = $this->cdbhelper->basicSelectDb($this->table, $where, $orderby, $options);
        return $ret;
    }

    // apenas para manter compatibilidade
    public function retRetrieveJson($where = "") {
        //$ret =  $this->cdbhelper->basicSelectDb($this->table, $where, " order by 2");
        //return json_encode($ret);
        $this->retrOptions = array("json" => true);
        return retRetrieveGridJson($where);
    }

    /**
     * @return mixed    Returns a Json for new records. Only with recid pointing to the new code.   返回新记录的Json。 只有recid指向新代码。
     */
    // retorna uma linha para o Json
    public function retInsJson() {
        if ($this->db->dbdriver == 'postgre') {
            return $this->cdbhelper->basicW2ArrayIns($this->sequence_obj);
        } else {
            return $this->cdbhelper->basicW2ArrayIns($this->table);
        }
    }

    /**Get Next code for the PK 获取PK的下一个代码
     * @return mixed    Return bigint with the code.    用代码返回bigint。
     */
    public function getNextCode() {
        if ($this->db->dbdriver == 'postgre') {
            return $this->cdbhelper->getNextCode($this->sequence_obj);
        } else {
            return $this->cdbhelper->getNextCode($this->table);
        }
    }

    // retorna se tem deactivated (usado em outros objetos)
    public function hasDeactivate() {
        return $this->hasDeactivate;
    }

    // $id -> codigo da PK referente. Ou seja, se a tabela desse extend for "TAGS", eh a pk do tags.
    public function updRelationCheckBox($id, $relationTable, $FieldChanges, $changes) {
        if ($this->db->dbdriver == 'postgre') {
            $seq = $this->sequence_obj;
        } else {
            $seq = $this->table;
        }


        return $this->cdbhelper->basicUpdateFlagGrid($relationTable, $this->pk_field, $id, $FieldChanges, $changes);
    }

    public function updRelationSBS($id, $relationTable, $idField, $add, $remove) {

        if ($this->db->dbdriver == 'postgre') {
            $seq = $this->sequence_obj;
        } else {
            $seq = $this->table;
        }



        return $this->cdbhelper->basicUpdateSBSgrid($relationTable, $idField, $id, $this->pk_field, $add, $remove);
    }

    /**Returns an array with a resultset of a table that relate two tables. Used for checkbox and Side by Side selection. it uses the model table as data source, and use a relation table (by relation table name, relation idfield and cd_id) to find the records.
     * 返回一个数组，其中包含与两个表相关的表的结果集。 用于复选框和并排选择。    它使用模型表作为数据源，并使用关系表（通过关系表名，关系idfield和cd_id）来查找记录。
     * @param $cd_id    Id code that you want to get the information    您想要获取信息的Id代码
     * @param $relationTable    The name of the relation table  关系表的名称
     * @param $idField  Relation field (used to join with the cd_id)    关系字段（用于与cd_id连接）
     * @param string $mode  Defaults to “B”. R: Already related, N: Not Related, B: Means both. 默认为“B”。 R：已经相关，N：不相关，B：两者兼而有之。
     * @param bool $fieldsForSelection  if true will the result the columns on the result will be recid, ds_description and fl_checked. If false will bring all columns from the model table.如果结果为true，则将重新计算结果上的列，ds_description和fl_checked。 如果为false，将带来model表中的所有列。
     * @return mixed    Returns result set from the model table (or if for selection = true as recid, ds_description and fl_checked) based on related table.    根据相关表返回模型表中的结果集（如果对于selection = true，则为recid，ds_description和fl_checked）。
     */
    public function retGridJsonWithRelation($cd_id, $relationTable, $idField, $mode = 'B', $fieldsForSelection = true
    ) {

        $relationTable = $this->db->escape_identifiers($relationTable);
        $mainTable = $this->db->escape_identifiers($this->table);
        $having = '';

        // Product type para mode: R -> "Related", N -> 'Not Related', Both = 'B'
        switch ($mode) {
            case 'B':
                $flag = '( CASE WHEN EXISTS ( SELECT 1 '
                        . '                           FROM ' . $relationTable . ' ps '
                        . '                          WHERE ps.' . $idField . ' =  ' . $cd_id
                        . '                            AND ps.' . $this->pk_field . '  = ' . $mainTable . '.' . $this->pk_field . ' '
                        . '                            AND ps.dt_deactivated  IS NULL ) THEN 1 ELSE 0 END ) as fl_checked';

                $where = '';

                break;

            case 'R':
            case 'Y':

                $flag = '1 as fl_checked';
                $where = ' WHERE EXISTS ( SELECT 1 '
                        . '                           FROM ' . $relationTable . ' ps '
                        . '                          WHERE ps.' . $idField . ' =  ' . $cd_id
                        . '                            AND ps.' . $this->pk_field . '  = ' . $mainTable . '.' . $this->pk_field . ' '
                        . '                            AND ps.dt_deactivated  IS NULL )';

                break;

            case 'N':
                $flag = '0 as fl_checked';

                $where = ' WHERE NOT EXISTS ( SELECT 1 '
                        . '                           FROM ' . $relationTable . ' ps '
                        . '                          WHERE ps.' . $idField . ' =  ' . $cd_id
                        . '                            AND ps.' . $this->pk_field . '  = ' . $mainTable . '.' . $this->pk_field . ' '
                        . '                            AND ps.dt_deactivated  IS NULL )';

                // se eh para aparecer os selecionaveis apenas ativos
                if ($this->hasDeactivate) {
                    $where = $where . ' AND dt_deactivated IS NULL ';
                }


                break;



            default:
                break;
        }

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
            'json' => true,
            'prodCatUnique' => $this->prodCatUnique
        );

        $ret = $this->cdbhelper->basicSelectDb($this->table, $where, " order by " . $this->ds_field, $options);
        return ($ret);
    }

    public function retJsonForTag($where = "") {
        $fields = array($this->pk_field . ' as id', $this->ds_field . ' as text');

        $options = array("fieldrecid" => $this->pk_field,
            //'stylecond'  => '',
            'fields' => $fields,
            'json' => true,
            'prodCatUnique' => $this->prodCatUnique
        );

        if ($where == '') {
            $where = ' WHERE 1 = 1';
        }

        if ($this->hasDeactivate) {
            $where = $where . ' AND dt_deactivated IS NULL';
        }

        $ret = $this->cdbhelper->basicSelectDb($this->table, $where, " order by " . $this->ds_field, $options);
        return ($ret);
    }

    /**Returns the Controller name  返回Controller名称
     * @return string   Returns the Controller name.
     */
    function getController() {
        return $this->controller;
    }

    /**Get the cdbhelper object.    获取cdbhelper对象。
     * @return cdbhelper    cdbhelper object.   cdbhelper对象。
     */
    function getCdbhelper() {
        $cdbhelper = $this->cdbhelper;
        if (1 == 2) {
            $cdbhelper = new cdbhelper();
        }


        return $cdbhelper;
    }

    /**Insert/Update the table with the array inside the field. It is used when a grid has a column with another grid (parent and son).  在字段内插入/更新表格。 当网格具有另一个网格（父级和子级）的列时使用它。
     * @param $dsfield   Column name where the system can find the array with other informations.   系统可以在其中找到包含其他信息的数组的列名。
     * @param $array    Array with the rows to insert/update. The array will have an array with the key as column name, and the value as the value. 包含要插入/更新的行的数组。 该数组将有一个数组，其中键为列名，值为值。
     * @return string   OK” if OK, if not ok returns the error message. 确定“如果确定，如果不正确则返回错误消息。
     */
    // updates genericos do que vem pelo grid!!!
    public function updateGridDataFromField($dsfield, $array) {

        $ret = 'OK';
        $array_tst = array();
        foreach ($array as $key => $value) {
            
            array_push($array_tst, $value);
            if (is_array($value)) {
                $value = (object)$value;
            }
                        
            if (isset($value->$dsfield)) {
                // se for string, converto para json

                if (is_string($value->$dsfield)) {
                    $value->$dsfield = json_decode($value->$dsfield);
                }


                $ret = $this->updateGridData($value->$dsfield);
                if ($ret != 'OK') {
                    return $ret;
                }
            }
        }
        RETURN $ret;
    }

    /** Returns the SQL statement according to the where, orderby and retrOpt parameter.    根据where，orderby和retrOpt参数返回SQL语句。
     * @param string $where Where addon for the statement   其中addon为声明
     * @param string $orderby   Order by statement  按陈述排序
     * @param array $retrOpt    The array that have all the statement data from model. If not send will use the retrOpt property with the basic one.    包含模型中所有语句数据的数组。 如果不是send将使用retrOpt属性和基本属性。
     * @return mixed    返回带有SQL语句的字符串
     */
    // retrieve Json do grid!
    public function retModelSQL($where = "", $orderby = '', $retrOpt = array()) {

        if ($orderby == "") {
            $orderby = $this->orderByDefault;
        }

        if ($retrOpt == array()) {
            $retrOpt = $this->retrOptions;
        }

        $retrOpt['retSQL'] = true;
        $retrOpt['prodCatUnique'] = $this->prodCatUnique;

        $ret = $this->cdbhelper->basicSelectDb($this->table, $where, $orderby, $retrOpt);
        
       
        return $ret;
    }

    /**Return a string with a subselect that will return a column with a json.    Normally used to create a column with a json return for parent-son tables.返回一个带有子选择的字符串，该子选择将返回带有json的列。 通常用于为父子表创建一个带有json返回的列。
     * @param $columnname    Name of the column that will receive the json subject  将接收json主题的列的名称
     * @param string $where  ="": The where statement (starting with ” WHERE ”). Normally with make the join with the main table    where语句（以“WHERE”开头）。 通常使用主表连接
     * @param string $orderby =""  The order by statement (starting with ” ORDER BY ”). order by语句（以“ORDER BY”开头）。
     * @param array $retrOpt The array that have all the statement data from model. If not send will use the retrOpt property with the basic one.   包含模型中所有语句数据的数组。 如果不是send将使用retrOpt属性和基本属性。
     * @return string   Returns a string with a subselect having a json statement.  返回带有带有json语句的子选择的字符串。
     */
    public function getJsonColumn($columnname, $where = "", $orderby = "", $retrOpt = array()) {
        // it works only on postgresql;
        if ($this->db->dbdriver != 'postgre') {
            die('It works only on Postgresql. Using: ' . $this->db->dbdriver);
        }

        if ($retrOpt == array()) {
            $retrOpt = $this->retrOptions;
        }
        
        $sqlTable = $this->retModelSQL($where, $orderby, $retrOpt);

        $sql = '(coalesce ( ( select (json_agg( r.* ))::text from (' . $sqlTable . ') as r ) , \'[]\') ) as ' . $columnname;
        
        return $sql;
    }

    /**Get the Demanded Columns (based on NOT NULL columns on database) 获取所需的列（基于数据库上的NOT NULL列）
     * @return array    Array with Demanded Columns 带有所需列的数组
     */
    public function getDemandedColumns() {
        return $this->getCdbhelper()->getNotNullColumns($this->table);
    }

    public function addNewTags($tags) {
        if (count($tags) == 0) {
            return $tags;
        }

        $tags = (array) $tags;

        $retarray = array();

        foreach ($tags as $value) {
            $value = (array) $value;
            // se o recid comeca com '.' quer dizer que eh novo!
            if (substr($value['recid'], 0, 1) == '.') {
                $idjson = json_decode($this->retInsJson(), true);
                $id = $idjson['recid'];
                $text = substr($value['recid'], 1);
                $sql = 'insert into ' . $this->db->escape_identifiers($this->table) . ' (' . $this->db->escape_identifiers($this->pk_field) . ', ' . $this->db->escape_identifiers($this->ds_field) . ') values (' . $id . ', \'' . $text . '\')';
                $this->cdbhelper->basicSQLNoReturn($sql);
                if (!$this->cdbhelper->trans_status()) {
                    return $tags;
                };

                array_push($retarray, array('recid' => $id, 'fl_checked' => 1));
            } else {
                array_push($retarray, $value);
            }
        }


        return $retarray;
    }

}

?>