<?php

class controllerBasicExtend extends CI_Controller
{

    public $methodToIgnoreSession = array();

    //put your code here

    /**Checks if the user has permission to the controller. It uses the class name to find inside the Menu options and permission of the system.检查用户是否具有控制器权限。 它使用类名来查找菜单选项和系统权限。
     * @param string $class the class name of the object. If empty will use the class that called the function.对象的类名。 如果为空将使用调用该函数的类。
     * @param string $die Defaults “Y”. If “Y” just stop execution if you don't have right. If “N” returns “Y” if ok and different than “Y” if not ok.默认为“Y”。 如果你没有权利，如果“Y”就停止执行。 如果“N”如果正常则返回“Y”，如果不正确则返回非“Y”。
     * @return mixed String if the parameter $die = “N” then it will return “Y” if OK, and different than “Y” if not ok.如果参数$ die =“N”则为字符串，如果OK则返回“Y”，如果不正确则返回“Y”。
     */
    public function checkMenuPermission($class = '', $die = 'Y')
    {
        if ($class == '') {
            $class = get_class($this);
            $ret = $this->cdbhelper->checkMenuRights($class);

            if ($die !== 'Y') {
                return $ret;
            }

            if ($ret != 'Y') {
                die($ret);
            }
        }
    }

    function __construct()
    {


        parent::__construct();


        // primeira acoisa a fazer eh verificar se esta logado!

        if (!$this->logincontrol->isProperLogged()) {
            die('Your Session Expired! Please login again (Err: 1602)');
        }

        // controle de IP.
        $lic = $this->cdbhelper->getSettings('local_ip_control');
        $lips = $this->cdbhelper->getSystemParameters('LOCAL_IP');
        $rights_ip = $this->cdbhelper->getUserPermission('fl_allow_connect_remotely');


        if (array_search($this->router->method, $this->methodToIgnoreSession) === false) {
            $this->getCdbhelper()->loginUpdate($this->session->userdata('cd_session_log'));
        }


        if ($lic == 'Y' && $_SERVER['REMOTE_ADDR'] != $lips && $rights_ip == 'N') {
            die('IP NOT Authorized :' . $_SERVER['REMOTE_ADDR']);
        }

        // extendido no objeto que tem o 
        //$this->load->model('material/product_group_model', 'mainmodel', TRUE);

        $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
        $this->load->model("sys_column_filter_preset_model", "filterPreset");

    }

    public function retrievegrid($where = "")
    {
        $result = $this->mainmodel->selectdb($where, 'order by ds_hr_type asc');
        $html = $this->gridg->mountGrid($this->arrayIns, $result, 900);
        return $html;
    }

    public function echoRetrievedGrid()
    {
        $where = $_POST['retFilter'];
        echo($this->retrievegrid($where));
    }

    /**Get from the Front End the selected filters and make the SQL statement (where).从前端获取所选过滤器并生成SQL语句（其中）。
     * @param string $filterReceived 默认''。 如果空白系统将从$ _POST变量（由前端发送）获得。 如果没有，将使用参数发送的那个。
     * @return mixed|string     String The where as “ WHERE 1 = 1 AND …”.
     */
    public function getWhereToFilter($filterReceived = '')
    {
        $where = '';
        $where2 = '';


        if ($filterReceived != '') {
            $where = $filterReceived;
        } else {
            if (isset($_POST['filter'])) {
                $where = $_POST['filter'];
            }
        }

        $where = urldecode($where);

        if ($where == '[]') {
            return ' where 1 = 1 ';
        }

        // faco esse teste para saber eu realmente recebi um json!
        $where2 = json_decode($where, true);
        if (!$where2) {
            $where2 = $where;
        }

        if (is_array($where2)) {
            $where2 = " where 1 = 1 " . $this->cdbhelper->mountFilterWhere($where2);
        }

        return $where2;
    }

    public function getJsonMappingToFilter()
    {
        $jsonMapping = '';

        if (isset($_POST['jsonMapping'])) {
            $jsonMapping = $_POST['jsonMapping'];
        }

        return $jsonMapping;
    }

    /**Function called to retrieve the data from the database and return as array.  调用函数从数据库中检索数据并作为数组返回。
     * @param array $retrOpt Default to array(). If empty will use the basic retOptions from the main model. You also can specify a different one.  默认为array（）。 如果为空，将使用主模型中的基本retOptions。 您也可以指定另一个。
     * @echo    Return the records. 返回记录。
     */
    public function retrieveGridArray($retrOpt = array())
    {

        if (!$this->logincontrol->isProperLogged(false)) {
            echo('{"logged": "N", "resultset": [] }');
            return;
        }


        $where = $this->getWhereToFilter();
        $jsonMapping = $this->getJsonMappingToFilter();

        if (isset($retrOpt['whereToAdd'])) {
            $where = $where . $retrOpt['whereToAdd'];
        }


        return $this->mainmodel->retRetrieveGridJson($where, '', $jsonMapping, $retrOpt);
    }

    /**Function called to retrieve the data from the database and return as echo. Used by the front-end to retrieve when the button “retrieve” of the grid is pressed.    You can override to make specific returns for the Grid.
     * 调用函数从数据库中检索数据并作为echo返回。 当按下“检索”网格按钮时，前端用于检索。    您可以覆盖以为Grid生成特定的返回值。
     * @param array $retrOpt Default to array(). If empty will use the basic retOptions from the main model. You also can specify a different one.   默认为array（）。 如果为空，将使用主模型中的基本retOptions。 您也可以指定另一个。
     * @echo    the records.    记录。
     */
    public function retrieveGridJson($retrOpt = array())
    {


        if (!$this->logincontrol->isProperLogged(false)) {
            echo('{"logged": "N", "resultset": [] }');
            return;
        }


        $where = $this->getWhereToFilter();


        $jsonMapping = $this->getJsonMappingToFilter();

        if (isset($retrOpt['whereToAdd'])) {
            $where = $where . $retrOpt['whereToAdd'];
        }

        echo('{ "logged": "Y", "resultset": ' . $this->mainmodel->retRetrieveGridJson($where, '', $jsonMapping, $retrOpt) . ' }');

    }

    /**Function called to retrieve the data from the database and return as echo. Similiar to the retrieveGridJson, but only return one line.    Used for Forms.    You can override to make specific returns for the Form.
     * 调用函数从数据库中检索数据并作为echo返回。 类似于检索Grid Json，但只返回一行。    用于表单。  您可以覆盖以为表单进行特定返回。
     * @param $id    Default to array(). If empty will use the basic retOptions from the main model. You also can specify a different one.  默认为array（）。 如果为空，将使用主模型中的基本retOptions。 您也可以指定另一个。
     * @echo the record.    记录。
     */
    public function retrieveGridJsonForm($id)
    {

        if (!$this->logincontrol->isProperLogged(false)) {
            echo('{"logged": "N", "resultset": [] }');
            return;
        }

        echo('{ "logged": "Y", "resultset": ' . $this->mainmodel->retRetrieveGridJsonForm($id) . ' }');
    }

    /**Called when user press the button insert on a grid. This function can be override to add default columns.    当用户按下网格上的按钮插入时调用。 可以覆盖此函数以添加默认列。
     * @echo    Array/JSON    Returns an empty json with the recid (being the last code).   返回带有recid的空json（是最后一个代码）。
     */
    public function retInsJson()
    {
        echo($this->mainmodel->retInsJson());
        
    }

    /**Function called to update the data and return the update ones as echo.    Used for Grids.    You can override to make specific update/returns.
     * 调用函数来更新数据并将更新的数据作为echo返回。    用于网格。    您可以覆盖以进行特定的更新/返回。
     * @echo    the records that were updated. 已更新的记录。
     */
    public function updateDataJson()
    {

        $msg = '';

        $upd_array = json_decode($_POST['upd']);
        $retResultset = 'N';

        if (isset($_POST['retResultSet'])) {
            $retResultset = $_POST['retResultSet'];
        }
        $jsonMapping = '';
        if (isset($_POST['jsonMapping'])) {
            $jsonMapping = $_POST['jsonMapping'];
        }

        $error = $this->mainmodel->updateGridData($upd_array);
        //die('dentro do basic');

        $msg = '{"status":' . json_encode($error);


        $retResult = '{}';

        if ($retResultset == 'Y' && $error == 'OK') {
            $neg = $this->mainmodel->getNewRecIdsNegative();
            $x = implode(',', $neg);

            $where = ' where ' . $this->mainmodel->pk_field . ' in (';
            foreach ($upd_array as $value) {
                $where = $where . $value->recid . ',';
            }
            if ($x != '') {
                $where = $where . $x . ', ';
            }
            $where = $where . '-1 )';


            $retResult = $this->mainmodel->retRetrieveGridJson($where, '', $jsonMapping);

            $msg = $msg . ', "rs": ' . $retResult;

            if (count($neg) > 0) {
                $msg = $msg . ', "negRS": ' . json_encode($neg);
            }
        }

        $msg = $msg . '}';

        //

        echo $msg;
    }

    /**Function called to update the data and return the update ones as echo.    Used for Forms.    You can override to make specific update/returns.
     * 调用函数来更新数据并将更新的数据作为echo返回。    用于表单。    您可以覆盖以进行特定的更新/返回。
     * @echo    the records that were updated.  已更新的记录。
     *
     */
    public function updateDataJsonForm()
    {
        $upd_array = json_decode($_POST['upd']);
        $arraysend = array($upd_array);
        $error = $this->mainmodel->updateGridData($arraysend);
        if ($error == 'OK') {
            $retResult = $this->mainmodel->retRetrieveGridJsonForm($arraysend[0]->recid);
        } else {
            $retResult = '{}';
        }

        $msg = '{"status":' . json_encode($error) . ', "rs":' . $retResult . '}';

        echo $msg;
    }

    /**Delete a specific record.删除特定记录。
     * @param $pk   Can be the recid or an array with recids.可以是recid或带有recid的数组。
     * @echo JSON, with status key. if Status = 'OK' means the deletion proceed. IF different than 'OK' the content is the error message. JSON，带状态键。 如果Status ='OK'表示删除继续。 如果与“OK”不同，则内容为错误消息。
     */
    public function deleteRecord($pk)
    {
        $error = $this->mainmodel->deleteGridData(array($pk));
        $msg = '{"status":' . json_encode($error) . '}';

        echo $msg;
    }

    /**Function called by the front end grid. It receives an array with the records to delete and proceed.前端网格调用的功能。 它接收一个包含要删除和继续记录的数组。
     *JSON, with status key. if Status = 'OK' means the deletion proceed. IF different than 'OK' the content is the error message.JSON，带状态键。 如果Status ='OK'表示删除继续。 如果与“OK”不同，则内容为错误消息。
     */
    public function deleteDataJson()
    {
        $del_array = json_decode($_POST['del']);
        $error = $this->mainmodel->deleteGridData($del_array);
        $msg = '{"status":' . json_encode($error) . '}';

        echo $msg;
    }

    /** Function called to generate the Picklist data.  调用函数生成Picklist数据。
     * @param string $way Default to “”. If 1, means you want only the active records. If 2, only the deactivated ones. Else ALL.    默认为“”。 如果为1，表示您只想要活动记录。 如果是2，则仅停用。 其他全部。
     * @param string $unionPK The Recid of a record that should always show up. If informed it will make a union on the table grabbing the information for this record.应该总是显示的记录的Recid。 如果得知通知，它将在表上建立联合以获取该记录的信息。
     * @param string $whereadd Default to “”. Parameter to send some additional where statement.   默认为“”。 参数发送一些额外的where语句。
     * @echo    the records that match with the parameters. 与参数匹配的记录。
     */
    public function retPickList($way = "", $unionPK = "-1", $whereadd = "")
    {
        $where = "";
        // 1 - busca apenas os ativos (usado para selecao em forms)
        // 2 = apenas os desativados
        // o resto pega tudo

        if (!$this->mainmodel->hasDeactivate()) {
            $way = -1;
        }

        if (!$this->logincontrol->isProperLogged(false)) {
            echo('{"logged": "N", "items": [] }');
            return;
        }

        switch ($way) {
            case 1:
                $where = " where dt_deactivated IS NULL ";
                break;
            case 2:
                $where = " where dt_deactivated IS NOT NULL ";
                break;

            default:
                $where = " where 1=1 ";
                break;
        }

        if (IsSet($_POST['searchterm'])) {

            if ($this->db->dbdriver == 'postgre') {
                $where = $where . "AND " . $this->mainmodel->ds_field . " ilike '" . $_POST['searchterm'] . "%' ";
            } else {
                $where = $where . "AND lower(" . $this->mainmodel->ds_field . ") like lower('" . $_POST['searchterm'] . "%') ";
            }

        }

        if ($whereadd == 'undefined') {
            $whereadd = '';
        }

        $whereadd = $whereadd . $this->mainmodel->basicWhereForPL;


        if ($unionPK == 'undefined') {
            $unionPK = '-1';
        }

        $where = $where . $whereadd;

        //die ($where);

        $j = json_encode($this->mainmodel->selectForPL($where, $unionPK));
        $j = '{"items": ' . $j . '}';

        echo $j;
    }

    public function retJsonForTag($where = "")
    {

        // faco filtro
        $term = $_GET['q'];
        if ($term != '') {
            $term = strtoupper($term);
            if ($where == '') {
                $where = " WHERE " . $this->mainmodel->ds_field . " like '" . $term . "%' ";
            } else {
                $where = " AND " . $this->mainmodel->ds_field . " like '" . $term . "%' ";
            }
        }


        $j = json_encode($this->mainmodel->retJsonForTag($where));

        echo(json_decode($j));
    }

    /**Function called to generate the Picklist data when it has some relation table together. The parameters $par1, $par2, $par3 will have the recids of the tables that will be related. Each one call a specific function that must be override to work accordingly.
     * 调用函数，当它具有一些关系表时生成Picklist数据。 参数$ par1，$ par2，$ par3将包含与之相关的表的recid。 每个人都调用一个必须覆盖的特定函数来相应地工作。
     * @param string $way Default to “”. If 1, means you want only the active records. If 2, only the deactivated ones. Else ALL.    默认为“”。 如果为1，表示您只想要活动记录。 如果是2，则仅停用。 其他全部。
     * @param string $par1 Default to “”. The Recid of a record that should always show up. If informed it will make a union on the table grabbing the information for this record.    默认为“”。 Recid的记录应始终显示。 如果得知通知，它将在表上建立集合以获取该记录的信息。
     * @param string $par2 Default to “”. Parameter to send some additional where statement.   默认为“”。 参数发送一些额外的where语句。
     * @param string $par3 Default to “”. Parameter to send some additional where statement.   默认为“”。 参数发送一些额外的where语句。
     * return   Array/JSON    ECHO the records that match with the parameters.  与参数匹配的记录。
     */
    public function retPicklistRel($way = "", $par1 = "", $par2 = "", $par3 = "")
    {
        $where = $this->retPlWherePar1($par1) . $this->retPlWherePar2($par2) . $this->retPlWherePar3($par3);

        $this->retPickList($way, "", $where);
    }

    public function retPlWherePar1($par1)
    {
        return "";
    }

    public function retPlWherePar2($par2)
    {
        return "";
    }

    public function retPlWherePar3($par3)
    {
        return "";
    }

    /**Receives an encoded model name and decode.接收编码的模型名称并解码。
     * @param $modelc The encoded model.编码模型
     * @return mixed String Decoded model name.字符串解码的模型名称
     */
    public function decodeModel($modelc)
    {
        return $this->encryption->decrypt($modelc);
    }

    public function setGridParser($model = '', $grid = '')
    {
        return;
        if ($model == '') {
            $model = $this->mainmodel;
        }

        if ($grid == '') {
            $grid = $this->w2gridgen;
        }

        $grid->setResultParser($model->retParseFields());
    }

    public function arrayCHK($array)
    {
        if (!isset($array)) {
            return array();
        } else {
            return $array;
        }
    }

    /**Returns the CDBHelper class. 返回CDBHelper类。
     * @return cdbhelper    CDBHelper Class.
     */
    function getCdbhelper()
    {
        $cdbhelper = $this->cdbhelper;
        if (1 == 2) {
            $cdbhelper = new cdbhelper();
        }


        return $cdbhelper;
    }

    /**Generate Excel file on back end. The front end sends the records, columns, titles, everything from the grid you want to generate and the system generates a default excel. Normally the export to excel is generated on the front end, so this function is only used when the information need to be well formatted and with images.
     *在后端生成Excel文件。 前端从您要生成的网格发送记录，列，标题和所有内容，系统会生成默认的Excel。 通常，导出到excel是在前端生成的，因此只有当信息需要格式良好并且带有图像时才使用此功能。
     * return Excel file.
     */
    public function genXLSDetailed()
    {
        $class = get_class($this);

        $this->load->library('cexcel');

        if (isset($_POST['resultset'])) {
            $resultset = $_POST['resultset'];
        } else {
            $resultset = $this->retrieveGridArray();
        }

        $resultset = json_decode($resultset, true);


        $menuNameSQL = "SELECT ds_menu
     FROM \"MENU\"
    WHERE ds_controller = '$class'
       OR ds_controller like '%/" . $class . "';";

        $ret = $this->getCdbhelper()->basicSQLArray($menuNameSQL);
        $name = $ret[0]['ds_menu'];


        $columns = json_decode($_POST['col']);
        $titlecolumn = json_decode($_POST['title']);
        $group = json_decode($_POST['group']);
        $rowHeight = $_POST['rowHeight'];
        $this->cexcel->setDocRep($_POST['docrep']);

        $this->cexcel->newSpreadSheet();
        $this->cexcel->createExcelByGrid($name, $columns, $titlecolumn, $group, $resultset, $rowHeight);
        $time = time();


        $this->cexcel->saveAsOutput("$name$time.xlsx");
        $this->cexcel->cleanMemory();
    }

    /**Returns the SQL Generated by the system (based on the default model and retrOptions).返回系统生成的SQL（基于默认模型和retrOptions）。
     * @echo    the Formatted SQL
     */
    public function getBasicSQL($retrOption = 'retrOptions')
    {

        include_once APPPATH . 'libraries/sql-formatter/lib/SqlFormatter.php';

        $fl_super_user = $this->session->userdata('fl_super_user');

        if ($fl_super_user != 'Y') {
            echo('...');
            exit;
        }

        $opt = $this->mainmodel->$retrOption;
        $opt['retsql'] = true;

        $ret = $this->mainmodel->retRetrieveGridArray('', "", $opt);
        echo SqlFormatter::format($ret);

    }


}
