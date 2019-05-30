<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
include_once APPPATH . 'controllers/controllerBasicExtend.php';

class basicpicklist extends controllerBasicExtend {

    var $arrayIns;

    function __construct() {
        parent::__construct();
    }

    public function makePLStartEndDatetime() {
        $title = $_POST['title'];
        $startDate = $_POST['startDate'];
        $endDate = $_POST['endDate'];

        $array_trans = array('select' => 'Return Selected',
            'cancel' => 'Close',
            'reset' => 'Reset'
        );

        $array_trans = $this->cdbhelper->retTranslationDifKeys($array_trans);

        $this->load->view('basicpicklist_start_end_datetime_view', array('startDate' => $startDate,
            'endDate' => $endDate,
            'title' => $title
                ) + $array_trans);
    }

    public function makePLModal() {
        // opcao para ter ateh 2 niveis de modelo
        // se veio nuemero no model2, e nao tem nada no id, significa que ele eh o id
        //echo (is_nan ($model2) ? 'YES' : 'NO');
        //echo ($id);
        $fm = $this->cfiltermaker;

        if (1 == 2) {
            $f = new Cfields();
            $grid = new w2gridgen();
            $fm = new cfiltermaker();
        }


        $id = $_POST['id'];
        $modelUndecoded = $_POST['model'];
        $title = $_POST['title'];
        $model = $this->decodeModel($modelUndecoded);
        $multiSelect = $_POST['multiselect'];

        $this->load->model($model, 'plModel', TRUE);
        $fm->setColumnSuperBig();
        $fm->addSimpleFilterUpper("Description", 'plFilter_Description', $this->plModel->ds_field);
        $filters = $fm->retFiltersWithGroup();

        $controller = $this->plModel->getController();
        $demandFilter = $this->plModel->PLDemandFilter;

        // verifico se o usuario tem direito a esse controller. nao tendo, limpo a variavel
        if ($controller != '') {
            if ($this->cdbhelper->checkMenuRights($controller) != 'Y') {
                $controller = '';
            }
        }
        $relation = (array) json_decode($_POST['relation']);


        if ($demandFilter == 'N') {
            $records = $this->plRetrieve(false);
        } else {
            $records = '[]';
        }

        $array_trans = array('code' => 'Code',
            'description' => 'Description',
            'clear' => 'Clear Data',
            'openmaint' => 'Open Maintenance',
            'filterMsg' => 'You must select Filter',
            'select' => 'Return Selected'
        );

        $array_trans = $this->cdbhelper->retTranslationDifKeys($array_trans);

        $javascript = "";

        if ($id == "") {
            $id = '-1';
        }

        $this->load->view('basicpicklist_view', array('records' => $records,
            'javascript' => $javascript,
            'selid' => $id,
            'controller' => $controller,
            'model' => $modelUndecoded,
            'relId' => $relation['idwhere'],
            'relCode' => $relation['id'],
            "filters" => $filters,
            "filters_java" => $fm->retJavascript(),
            'demandFilter' => $demandFilter,
            'title' => $title,
            'multiselect' => $multiSelect
                ) + $array_trans);
    }

    public function plRetrieve($echoResultset = true) {

        //print_r($_POST);
        //die();

        $relation = json_decode($_POST['relation'], true);
        $model = $this->decodeModel($_POST['model']);
        $where = $this->getWhereToFilter();


        if ($where == '') {
            $where = ' WHERE 1 = 1 ';
        }

        if ($relation['idwhere'] != '-1') {
            $whereRelation = $this->cdbhelper->getFilterQuery($relation['idwhere']);
            $relationID = $relation['id'];
            $relationID = str_replace('<COMMA>', ',', $relationID);


            $whereRelation = sprintf($whereRelation, $relationID);

            $where = $where . $whereRelation;
        }

        $this->load->model($model, 'plModel', TRUE);

        $where = $where . $this->plModel->basicWhereForPL;


        if ($this->plModel->hasDeactivate) {
            $where = $where . ' AND dt_deactivated IS NULL ';
        }


        $recordset = $this->plModel->selectForPL($where);
        $recordsetJson = json_encode($recordset);
        if ($echoResultset) {
            echo (json_encode($recordset));
        } else {
            return $recordsetJson;
        }
    }

    public function plRetrieveDD($echoResultset = true) {
        //die();
        //sleep(3);


        $relation = json_decode($_POST['relation'], true);
        $model = $this->decodeModel($_POST['model']);
        $where = $this->getWhereToFilter();

        if ($where == '') {
            $where = ' WHERE 1 = 1 ';
        }

        if ($relation['idwhere'] != '-1') {
            $whereRelation = $this->cdbhelper->getFilterQuery($relation['idwhere']);
            $relationID = $relation['id'];
            $relationID = str_replace('<COMMA>', ',', $relationID);


            $whereRelation = sprintf($whereRelation, $relationID);

            $where = $where . $whereRelation;
        }

        $this->load->model($model, 'plModel', TRUE);

        $where = $where . $this->plModel->basicWhereForPL;

        if ($this->plModel->hasDeactivate) {
            $where = $where . ' AND dt_deactivated IS NULL ';
        }



        $controller = $this->plModel->getController();

        // verifico se o usuario tem direito a esse controller. nao tendo, limpo a variavel
        if ($controller != '') {
            if ($this->cdbhelper->checkMenuRights($controller) != 'Y') {
                $controller = '';
            }
        }
        $searchTerm = '';
        if (isset($_POST['searchterm'])) {
            $searchTerm = $_POST['searchterm'];
        }






        $recordset = $this->plModel->selectForPLD($where, '', $searchTerm);


        $data = array('rs' => json_decode($recordset), 'controller' => $controller);

        if ($echoResultset) {
            echo (json_encode($data));
        } else {
            return $data;
        }
    }

    public function index() {

        echo ('');
        //print_r($arrayIns);
    }

    public function basicMaintenance() {
        $url = $_POST['url'];

        $this->load->view('basicmaintscreen_view', array('url' => $url));
    }

}

?>