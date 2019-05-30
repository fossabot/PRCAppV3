<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class course extends controllerBasicExtend
{

    var $arrayIns;
    var $fields;

    function __construct()
    {
        parent::__construct();
        $this->load->model("training/course_model", "mainmodel", TRUE);
        $this->load->model("training/course_title_model", "titlemodel", TRUE);
    }

    public function index()
    {

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

        $fm->addPickListFilter('Category', 'filter_1', 'training/course_category', '"COURSE".cd_course_category');
        $fm->addSimpleFilterUpper('Number', 'filter_2', '"COURSE".ds_course_number');
        $fm->addSimpleFilterUpper('Name', 'filter_3', '"COURSE".ds_course');
//        $fm->addSimpleFilterUpper('Target Trainee', 'filter_4', '"COURSE".ds_target_trainee');
        $fm->addPickListFilter('Status Material', 'filter_7', 'training/course_status', '"COURSE".cd_course_status_material');
        $fm->addPickListFilter('Status', 'filter_8', 'training/course_status', '"COURSE".cd_course_status');
        $fm->addPickListFilterExists("Title", "human_resource_title", "filter_2", "COURSE", "cd_course", "COURSE_TITLE", "cd_human_resource_title", "cd_course", false);

        $fm->addFilterYesNo("Active", "dt_deactivated", "", "Y");


        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->addUserBtnToolbar('showtitle','show/hide title','fa fa-expand');
        $grid->addDocRepToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("training/course");

        $grid->addColumnKey();

        $grid->addColumn('ds_course_category', 'Category', '100%', $f->retTypePickList(), array('model' => 'training/course_category_model', 'codeField' => 'cd_course_category'));
        $grid->addColumn('ds_course_number', 'Number', '100%', $f->retTypeStringAny(), array('limit' => '16'));
        $grid->addColumn('ds_course', 'Name', '200%', $f->retTypeTextPL(), true);
//        $grid->addColumn('ds_target_trainee', 'Target Trainee', '100%', $f->retTypeTextPL(), true);
        $grid->addColumn('nr_class_duration', 'Class Duration', '100%', $f->retTypeInteger(), true);
        $grid->addColumn('nr_frequency_months', 'Frequency Months', '100%', $f->retTypeInteger(), true);
        $grid->addColumn('ds_course_status_material', 'Status Material', '100%', $f->retTypePickList(), array('model' => 'training/course_status_model', 'codeField' => 'cd_course_status_material'));
        $grid->addColumn('ds_course_status', 'Status', '100%', $f->retTypePickList(), array('model' => 'training/course_status_model', 'codeField' => 'cd_course_status'));
        $grid->addColumn('ds_title_need_attend', 'Title Need Attend', '100%', $f->retTypeStringAny(),false);
        $grid->addColumnDeactivated(true);


        $filters = $fm->retFiltersWithGroup();
        $javascript = $grid->retGrid();


        $trans = array();
        $trans = $this->cdbhelper->retTranslationDifKeys($trans);


        $send = array("javascript" => $javascript,
                "filters" => $filters,
                "filters_java" => $fm->retJavascript()) + $trans;

        //----------------------------------------------------------------------------------------------------------------------------------


        $grid->resetGrid();
        $grid->setToolbarPrefix('title');
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar(false, true, false, true, false);
        $grid->setToolbarSearch(false);
        $grid->setCRUDController("training/Course_title");
        $grid->addToolbarTitle('Title Need Attend');
        $grid->addColumnKey();


        $grid->addColumn('ds_human_resource_title', 'Title', '100%', $f->retTypePickList(), array('model' => 'human_resource_title_model', 'codeField' => 'cd_human_resource_title'));

        $grid->setGridDivName('gridTitleDiv');
        $grid->setGridName('gridTitle');
        $grid->setGridVar('vBenefit');


//        $filters = $fm->retFiltersWithGroup();
        $javascript = $javascript . $grid->retGrid();


        $trans = array();
        $trans = $this->cdbhelper->retTranslationDifKeys($trans);


        $send = array("javascript" => $javascript,
                "filters" => $filters,
                "filters_java" => $fm->retJavascript()) + $trans;
        $this->load->view("training/course_view", $send);


    }

    // function that retrieves the information. here should have the columns that will receive the child tables.检索信息的函数。这里应该有接收子表的列。
    function retrieveGridJson($retrOpt = array())
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

        $data = $this->mainmodel->retRetrieveGridArray($where, '', $retrOpt);
        //$data = json_decode($this->mainmodel->retRetrieveGridJson($where, '', $jsonMapping, $retrOpt), true);

        foreach ($data as $key => $row) {

            $data[$key]['title'] = $this->titlemodel->retRetrieveGridArray(" WHERE cd_course = " . $row['recid']);


        }


        echo('{ "logged": "Y", "resultset": ' . json_encode($data, JSON_NUMERIC_CHECK) . ' }');

    }

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


        $this->cdbhelper->trans_begin();


        $error = $this->mainmodel->updateGridData($upd_array);
        if ($error != 'OK') {
            $msg = '{"status":' . json_encode($error) . ', "rs":{}}';
            $this->cdbhelper->trans_end();
            echo $msg;
            return;
        }



        $error = $this->titlemodel->updateGridDataFromField('title', $upd_array);
        if ($error != 'OK') {
            $msg = '{"status":' . json_encode($error) . ', "rs":{}}';
            $this->cdbhelper->trans_end();
            echo $msg;
            return;
        }

        if ($error != 'OK') {
            $msg = '{"status":' . json_encode($error) . ', "rs":{}}';
            $this->cdbhelper->trans_end();
            echo $msg;
            return;
        }

        $this->cdbhelper->trans_commit();
        $this->cdbhelper->trans_end();

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
}