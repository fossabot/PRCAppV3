<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
include_once APPPATH . 'controllers/controllerBasicExtend.php';

class jobs_maint extends controllerBasicExtend {

    var $arrayIns;

    function __construct() {


        parent::__construct();


        $this->load->model('job_model', 'mainmodel', TRUE);
    }

    public function index() {
        $f = $this->cfields;

        if (1 == 2) {
            $f = new Cfields();
        }

        $fm = $this->cfiltermaker;
        parent::checkMenuPermission();

        //$ret = $tb->returnHtml();

        $fm->addSimpleFilterUpper("Role", "ds_jobs");
        $fm->addPickListFilter("Department", "cd_department", "job_department", "", false);
        $fm->addPickListFilter("Role Responsible", "cd_jobs_responsible", "jobs_maint", "", false);
        $fm->addPickListFilterExists("User Name", "human_resource_controller", "filter_2", "JOBS", "cd_jobs", "JOBS_HUMAN_RESOURCE", "cd_human_resource", "cd_jobs", false);
        $fm->addPickListFilterExists("Permission", "system_permission", "filter_3", "JOBS", "cd_jobs", "JOBS_SYSTEM_PERMISSION", "cd_system_permission", "cd_jobs", false);


        $fm->addFilterYesNo("Active", "dt_deactivated", '', "Y");
        $filters = $fm->retFiltersWithGroup();

        $this->w2gridgen->addBreakToolbar();
        $this->w2gridgen->addInsToolbar();
        $this->w2gridgen->addUpdToolbar();
        //$this->w2gridgen->addEditToolbar();
        $this->w2gridgen->setMultiSelect(false);
        //addCRUDToolbar($retr = true,$ins = true, $upd = true,  $del = true)
        $this->w2gridgen->addCRUDToolbar(true, false, false, true);
        $this->w2gridgen->setDemandedColumns($this->mainmodel->getDemandedColumns());

        $this->w2gridgen->addColumnKey();

        $colid = $this->w2gridgen->addColumn('ds_jobs', 'Role', '100%', $f->retTypeStringUpper(), true);
        $colid = $this->w2gridgen->addColumn('ds_department', 'Department', '150px', $f->retTypePickList(), array('model' => 'job_department_model', 'codeField' => 'cd_department'));
        $colid = $this->w2gridgen->addColumn('ds_jobs_responsible', 'Responsible Role', '150px', $f->retTypePickList(), array('model' => 'job_model', 'codeField' => 'cd_jobs_responsible'));
        $colid = $this->w2gridgen->addColumn('ds_notes', 'Notes', '80px', $f->retTypeTextPL());
        $colid = $this->w2gridgen->addColumn('dt_deactivated', 'Deactivated', '80px', $f->retTypeDeactivated(), true);
        $this->w2gridgen->setMultiSelect(false);
        $this->w2gridgen->addBreakToolbar();

        $this->w2gridgen->addUserBtnToolbar("menu_options", "Menu Options Maintenance", "fa fa-tasks", $caption = "");

        $javascript = $this->w2gridgen->retGridVar();

        // comeco grid do usuario:
        $this->w2gridgen->resetGrid();

        $this->w2gridgen->setToolbarPrefix('hm');
        $this->w2gridgen->addUndoToolbar();
        $this->w2gridgen->addUpdToolbar();


        $this->w2gridgen->setGridVar('varGridHuman');
        $this->w2gridgen->setGridName('gridHuman');
        //$this->w2gridgen->setHeader('Human Resources Related');
        $this->w2gridgen->setToolbarSearch(true);
        //$this->w2gridgen->addCRUDToolbar(false,true, true,  true) ;
        $this->w2gridgen->addColumn('recid', 'Code', '40px', $f->retTypeKey());
        $this->w2gridgen->addColumn('fl_checked', 'X', '50px', $f->retTypeCheckBox(), true);
        $this->w2gridgen->addColumn('ds_human_resource', 'User Name', '100px', $f->retTypeStringAny());
        $this->w2gridgen->addColumn('ds_human_resource_full', 'Full Name', '300px', $f->retTypeStringAny());

        $javascript = $javascript . "  " . $this->w2gridgen->retGridVar();

        // comeco grid do Permission:
        $this->w2gridgen->resetGrid();

        $this->w2gridgen->setToolbarPrefix('pm');
        $this->w2gridgen->addUndoToolbar();
        $this->w2gridgen->addUpdToolbar();


        $this->w2gridgen->setGridVar('varGridPermission');
        $this->w2gridgen->setGridName('gridPermission');
        //$this->w2gridgen->setHeader('Permissions Related');
        $this->w2gridgen->setToolbarSearch(true);
        //$this->w2gridgen->addCRUDToolbar(false,true, true,  true) ;
        $colid = $this->w2gridgen->addColumn('recid', 'Code', '40px', $f->retTypeKey());
        $colid = $this->w2gridgen->addColumn('fl_checked', 'X', '30px', $f->retTypeCheckBox(), true);
        $colid = $this->w2gridgen->addColumn('ds_system_permission', 'System Permission', '200px', $f->retTypeStringUpper());
        $colid = $this->w2gridgen->addColumn('ds_type_sys_permission', 'Type Permission', '300px', $f->retTypeStringUpper());

        $javascript = $javascript . "  " . $this->w2gridgen->retGridVar();

        $labels = array(
            'human' => 'Human Resource',
            'sysperm' => 'System Permission',
            'changes_perm' => 'There are changes on User and/or Permission. Continue action (you might lose the changed information) ?',
            'menu_perm' => 'Menu Permission Maintenance',
            'retr_user_perm' => 'Retrieving User/Permission information...',
            'upd_perm_area' => 'Updating Permission Area.'
        );

        $labels = $this->cdbhelper->retTranslationDifKeys($labels);


        $send = array("javascript" => $javascript,
            "filters" => $filters,
            "filters_java" => $fm->retJavascript()) + $labels;


        $this->load->view("jobs_view", $send);


        //print_r($arrayIns);
    }

    public function newrow() {
        $html = $this->gridg->makeRow($this->arrayIns, null, "I");
        echo $html;
    }

    public function retrievegrid($where = "") {
        $result = $this->mainmodel->selectdb($where, 'order by ds_human_resource asc');
        $html = $this->gridg->mountGrid($this->arrayIns, $result, 900);
        return $html;
    }

    public function echoRetrievedGrid() {
        $where = $_POST['retFilter'];
        echo ( $this->retrievegrid($where) );
    }

    public function retrievePermissionJson($job) {
        echo ($this->mainmodel->retPermissionJson($job));
    }

    public function retrieveHRJson($job) {
        echo ($this->mainmodel->retHRJson($job));
    }

    public function retInsJson() {

        echo ($this->mainmodel->retInsJson());
    }

    public function openForm($recid) {
        $json = '{}';
        if ($recid != -1) {
            $resultset = $this->mainmodel->selectdb(" where cd_jobs = " . $recid);


            if (count($resultset) > 0) {
                $json = json_encode($resultset[0]);
            }
        }

        $labels = array("code" => 'Code',
            "job" => 'Job',
            "job_resp" => 'Job Responsible',
            "department" => 'Department',
            "notes" => 'Notes',
            'deactivated' => 'Deactivated',
            'general' => 'General'
        );


        $labels = $this->cdbhelper->retTranslationDifKeys($labels);

        $this->load->view("jobs_form", array("resultset" => $json) + $labels);
    }

    public function retPickList($way = "", $unionPK = "", $wheretoadd = "") {

        $where = "";
        // 1 - busca apenas os ativos (usado para selecao em forms)
        if ($way == 1) {
            $where = " where dt_deactivated IS NULL ";
        }

//        echo ($where);
//        echo ($way);
        //$arrayret = array("items", $this->hm_type->selectForPL($where));
        $j = json_encode($this->mainmodel->selectForPL($where, $unionPK));
        $j = '{"items": ' . $j . '}';

        echo $j;
    }

    public function updatePermissionJson($code) {

        $upd_array = json_decode($_POST['upd']);

        echo ($this->mainmodel->updatePermissionData($code, $upd_array));
    }

    public function openRightsScreen() {

        //parent::checkMenuPermission();

        $grid = $this->w2gridgen;
        $f = $this->cfields;
        $fm = $this->cfiltermaker;

        if (1 == 2) {
            $f = new Cfields();
            $grid = new w2gridgen();
            $fm = new cfiltermaker();
        }


        $fm = $this->cfiltermaker;

        /* Process Group */

        $grid->resetGrid();
        $grid->setGridToolbarFunction('dsMainObject.ToolBarClick');

        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar(true, false, false, false, false);
        $grid->addBreakToolbar();
        $grid->setToolbarSearch(true);

        $grid->addUserBtnToolbar('openHm', 'Users', 'fa fa-user');

        $grid->addUserBtnToolbar("openmenu", "Menu Options Maintenance", "fa fa-tasks", $caption = "");

        $grid->addBreakToolbar();
        $grid->addUserBtnToolbar('copy', 'Copy', 'fa fa-copy');

        $grid->addUserBtnToolbar('paste', 'Merge Permissions', 'fa fa-paste');
        $grid->addBreakToolbar();

        $grid->addSpacerToolbar();

        $grid->addExportToolbar();

        $grid->setCRUDController("jobs_maint");

        $grid->addHiddenColumn('recid', 'Code', '80px', $f->retTypeInteger());

        $grid->addColumn('ds_jobs', 'Role', '100%', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_department', 'Department', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_jobs_responsible', 'Responsible', '150px', $f->retTypeStringAny(), false);

        $grid->addRecords(json_encode($this->retrieveToRights(true)));
        $grid->setGridName('grdRoles');
        $grid->setGridDivName('grdRolesDiv');

        $javas = $grid->retGrid();



        // prod cat
        $grid->resetGrid();
        $grid->setGridToolbarFunction('dsMainObject.ToolBarClick');

        $grid->setSingleBarControl(true);

        $grid->setToolbarSearch(true);
        $grid->addSpacerToolbar();
        $grid->addExportToolbar();

        $grid->addHiddenColumn('recid', 'Code', '80px', $f->retTypeInteger());

        $grid->addColumn('ds_description', 'Human Resource', '100%', $f->retTypeStringAny(), false);

        $grid->setGridName('grdHM');
        $grid->setGridDivName('grdHMDiv');

        $javas = $javas . $grid->retGrid();


        //$filters = $fm->retFiltersWithGroup();

        $trans = array('vHm' => 'Users',
            'vFact' => 'Factory',
            'vDiv' => 'Division',
            'vCust' => 'Customer',
            'vCat' => 'Product Category',
            'vJob' => 'Roles',
            "menumaint" => 'Menu Permission Maintenance',
            'nouserselected' => 'No User selected to copy the rights from',
            'selectwhat' => 'You must select what you want to paste!',
            'confpaste' => 'Confirm paste data from ',
            'to' => 'to',
            'copying' => 'Merging'
        );

        $trans = $this->cdbhelper->retTranslationDifKeys($trans);

        $filters = $fm->retFiltersWithGroup();


        $send = array("javas" => $javas,
            "jsonParam" => json_encode(array())
                ) + $trans;


        $this->load->view("jobs_rights_view", $send);
    }

    public function retrieveToRights($returnAsVar = false, $where = ' WHERE 1 = 1 ') {
        $this->load->model('Human_resource', 'hm', TRUE);
        $where2 = $where . ' AND dt_deactivated IS NULL AND cd_system_product_category = ' . $this->session->userdata('system_product_category');
        
        $array = $this->mainmodel->retRetrieveGridArray($where2);

        foreach ($array as $key => $value) {

            $jsonHM = $this->hm->retGridJsonByJob($value['recid'], 'R', true);

            $array[$key]['ds_hm'] = json_decode($jsonHM, true);
        }

        if ($returnAsVar) {
            return $array;
        } else {
            echo(json_encode($array));
        }
    }

    public function mergePermissions($hmfrom, $hmto) {

        $sql = 'SELECT public.CopyJobPermissions (%s, %s, \'Y\');';

        $jobfrom = $this->cdbhelper->normalizeDataToSQL('int', $hmfrom);
        $copyjob = $this->cdbhelper->normalizeDataToSQL('char', $copyjob);



        $sql = sprintf($sql, $hmfrom, $hmto);

        $this->getCdbhelper()->basicSQLNoReturn($sql);

        $this->retrieveToRights(false, ' WHERE cd_jobs = ' . $hmto);
    }

    public function retrPermData() {
        //$ret = array('resultset' => $this->retrieveToRights(true));

        echo (json_encode($this->retrieveToRights(true)));
    }

}

?>