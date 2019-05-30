<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
include_once APPPATH . 'controllers/controllerBasicExtend.php';

class users_maint extends controllerBasicExtend {

    var $arrayIns;

    function __construct() {
        parent::__construct();


        $this->load->model('human_resource', '', TRUE);
        $this->load->model('job_department_model', 'dep', TRUE);
        $this->load->model('department_ldap_model', 'depldap', TRUE);
        $this->load->model('human_resource_model', 'newmodel', TRUE);

        $this->load->model('docrep/document_repository_type_model', 'doctype', TRUE);
    }

    public function index() {
        $class = get_class($this);
        $ret = $this->cdbhelper->checkMenuRights($class);
        if ($ret != 'Y') {
            //echo ($ret);
            die($ret);
        }

        if (1 == 2) {
            $f = new Cfields();
            $grid = new w2gridgen();
            $fm = new cfiltermaker();
            $this->cdbhelper = new cdbhelper();
            $cdbhelper = new cdbhelper();
        }

        $f = $this->cfields;
        $fm = $this->cfiltermaker;
        $grid = $this->w2gridgen;

        parent::checkMenuPermission();

        //$ret = $tb->returnHtml();

        $fm->addFilter('ds_human_resource_filter', 'User Name', array('fieldname' => 'ds_human_resource', 'likeIlike' => 'I'));

        //$fm->addSimpleFilterUpper("User Name", "ds_human_resource");
        $fm->addSimpleFilterUpper("Full Name", "ds_human_resource_full_filter", 'ds_human_resource_full');

        $fm->addFilter('ds_e_mail_finter', 'E-Mail', array('fieldname' => 'ds_e_mail', 'likeIlike' => 'I'));
//$fm->addSimpleFilterUpper("E-Mail", "ds_e_mail");
        //$fm->addPickListFilter("Type User", "cd_hr_type_filter", "type_users_maint", "cd_hr_type", false);

        $fm->addPickListFilter('Department', 'filter_5', 'job_department', 'cd_department');
        $fm->addPickListFilter('User Project Roles', 'filter_7', 'tti/roles', 'cd_roles');


        $fm->addFilterYesNo("Active", "dt_deactivated", '', "Y");
        $filters = $fm->retFiltersWithGroup();

        $grid->addBreakToolbar();
        $grid->addInsToolbar();
        $grid->addEditToolbar();
        $grid->addUpdToolbar();


        $grid->addCRUDToolbar(true, false, false, false);
        //$grid->addUserBtnToolbar("copy_merge_from", "Copy/Merge from selected", "fa fa-paste", $caption="") ;
        $grid->addUserBtnToolbar("menu_options", "Menu Options Maintenance", "fa fa-tasks");

        $grid->addUserBtnToolbar("importAD", "Fecth from AD", "fa fa-cloud-download");

        $grid->addColumnKey();
        $grid->addColumn('ds_human_resource', 'User Name', '150px', $f->retTypeStringLower());
        $grid->addColumn('ds_human_resource_full', 'Full Name', '100%', $f->retTypeStringAny());
        $grid->addColumn('ds_e_mail', 'E-Mail', '200px', $f->retTypeStringAny());
        $grid->addColumn('ds_department', 'Department', '150px', $f->retTypePickList(), array('model' => 'job_department_model', 'codeField' => 'cd_department'));
        $grid->addColumn('ds_team', 'Team', '150px', $f->retTypePickList(), array('model' => 'team_model', 'codeField' => 'cd_team'));
        $grid->addColumn('ds_roles', 'Project User Role', '150px', $f->retTypePickList(), array('model' => 'tti/roles_model', 'codeField' => 'cd_roles'));

        //$grid->addColumn('ds_hr_type', 'Type User', '250px', $f->retTypeStringUpper());
        $grid->addColumn('dt_deactivated', 'Deactivated', '80px', $f->retTypeDeactivated());

        $grid->setMultiSelect(false);

        $javascript = $grid->retGrid();

        $labels = array("usermaint" => 'User Maintenance',
            "menumaint" => 'Menu Permission Maintenance'
        );

        $labels = $this->cdbhelper->retTranslationDifKeys($labels);


        $send = array("javascript" => $javascript,
            "filters" => $filters,
            "filters_java" => $fm->retJavascript());

        $send = $send + $labels;
        $this->load->view("user_maint", $send);


        //print_r($arrayIns);
    }

    public function newrow() {
        $html = $this->gridg->makeRow($this->arrayIns, null, "I");
        echo $html;
    }

    public function retrievegrid($where = "") {
        $result = $this->human_resource->selectdb($where, 'order by ds_human_resource asc');
        $html = $this->gridg->mountGrid($this->arrayIns, $result, 900);
        return $html;
    }

    public function echoRetrievedGrid() {
        $where = $_POST['retFilter'];
        echo ( $this->retrievegrid($where) );
    }

    public function retPickList($way = "", $unionPK = "", $whereadd = "") {

        $where = "";
        // 1 - busca apenas os ativos (usado para selecao em forms)
        if ($way == 1) {
            $where = " where dt_deactivated IS NULL ";
        }

        //$arrayret = array("items", $this->hm_type->selectForPL($where));
        $j = json_encode($this->human_resource->selectForPL($where, $unionPK));
        $j = '{"items": ' . $j . '}';

        echo $j;
    }

    public function retrieveGridJson($retrOpt = Array()) {
        $where2 = $this->getWhereToFilter();

        echo ($this->human_resource->retRetrieveJson($where2));
    }

    public function retInsJson() {

        echo ($this->human_resource->retInsJson());
    }

    public function updateDataJson() {
        $upd_array = json_decode($_POST['upd']);
        $error = $this->human_resource->updateGridData($upd_array);

        echo json_encode(array('status' => $error));
    }

    public function deleteDataJson() {
        $del_array = json_decode($_POST['del']);

        $error = $this->human_resource->deleteGridData($del_array);

        echo $error;
    }

    public function openForm($recid) {
        $json = '{}';
        if ($recid != -1) {
            $resultset = $this->human_resource->selectdb(" where cd_human_resource = " . $recid);
            $new = 'false';


            if (count($resultset) > 0) {
                unset($resultset[0]['ds_password']);
                $json = json_encode($resultset[0]);
            }
        } else {
            $recid = $this->human_resource->getNextCode();
            $json = json_encode(array('cd_human_resource' => $recid));
            $new = 'true';
        }

        $labels = array("code" => 'Code',
            "fullname" => 'Full Name',
            "email" => 'E-Mail',
            "typeuser" => 'Type User',
            "deactivated" => 'Deactivated',
            "login" => "Login",
            "retypepassword" => 'Retype Password',
            "newpassword" => 'New Password',
            'general' => 'General',
            'login_info' => 'Login Information',
            "confirm" => 'Confirm',
            'info_changed' => 'There are information changed. Confirm Close ?',
            'passnotmatch' => 'Password Not Matching!',
            'inv_email' => 'Invalid Email Address!',
            'error_size' => 'Size cannot be more than');

        $labels = $this->cdbhelper->retTranslationDifKeys($labels);

        $ret = array("resultset" => $json,
            'pk' => $recid,
            'accept' => $this->doctype->getAvailableExtensionsBrowserByMime('image/jpeg'),
            'img' => $this->cdbhelper->getUserPicture($recid, 'C'),
            'new' => $new
                ) + $labels;

        $this->load->view("human_resource_form", $ret);
    }

    function updateForms($upd_array) {
        $arraysend = (array) $upd_array;

        $files = $this->cdbhelper->cgbFileUploadParse($_POST['cgbFileUpload']);

        if (isset($upd_array[0]['fl_super_user'])) {
            unset($upd_array[0]['fl_super_user']);
        }

        if (count($upd_array) != 0) {


            $error = $this->human_resource->updateGridData($arraysend);
            if ($error !== 'OK') {
                $msg = '{"status":' . json_encode($error) . '}';
                return ($msg);
            } else {
                $retResult = '{}';
            }
        }

        if (count($files) > 0) {
            $this->cdbhelper->saveUserPicture($_POST['recid'], $files[0]['tmp_filename']);
        }

        $retResult = $this->human_resource->retRetrieveGridJsonForm($arraysend[0]['recid']);

        $msg = '{"status": "OK"' . ', "rs":' . $retResult . '}';
        return $msg;
    }

    function updateDataJsonForm() {

        $class = get_class($this);
        $ret = $this->cdbhelper->checkMenuRights($class);
        if ($ret != 'Y') {
            //echo ($ret);
            echo ('{"status":' . json_encode($ret) . '}');
            return;
        }

        $retypePassword = '';
        $newPassword = '';

        $upd_array = json_decode($_POST['upd']);
        $upd_array = (array) $upd_array;

        if (isset($upd_array['ds_retype_password'])) {
            $retypePassword = $upd_array['ds_retype_password'];
            unset($upd_array['ds_retype_password']);
        };

        if (isset($upd_array['ds_password'])) {
            $newPassword = $upd_array['ds_password'];
        };

        if ($retypePassword !== $newPassword) {
            echo ('{"status":' . json_encode($this->cdbhelper->retTranslation('Password Not Matching')) . '}');
            return;
        }

        $newArray = array();
        $newArray[0] = $upd_array;


        echo $this->updateForms($newArray);
    }

    function updateDataJsonFormProfile() {
        $retypePassword = '';
        $newPassword = '';
        $actualPassword = '';

        $upd_array = json_decode($_POST['upd']);
        $upd_array = (array) $upd_array;



        if (isset($upd_array['ds_retype_password'])) {
            $retypePassword = $upd_array['ds_retype_password'];
            unset($upd_array['ds_retype_password']);
        };

        if (isset($upd_array['ds_password'])) {
            $newPassword = $upd_array['ds_password'];
        };


        if (isset($upd_array['ds_current_password'])) {
            $actualPassword = $upd_array['ds_current_password'];
            unset($upd_array['ds_current_password']);
        };


        if ($retypePassword !== $newPassword) {
            echo ('{"status":' . json_encode($this->cdbhelper->retTranslation('Password Not Matching')) . '}');
            return;
        }

        if ($newPassword !== '') {
            $err = $this->human_resource->loginById($upd_array['recid'], $actualPassword);
            if (!$err) {
                echo ('{"status":' . json_encode($this->cdbhelper->retTranslation('Wrong Actual Password')) . '}');
                return;
            }
        }

        $newArray = array();
        $newArray[0] = $upd_array;

        echo $this->updateForms($newArray);
    }

    public function profile() {
        $json = '{}';
        $cd_human_resource = $this->session->userdata('cd_human_resource');
        $resultset = $this->human_resource->selectdb(" where cd_human_resource = " . $cd_human_resource);
        $fl_demand_profile = $this->cdbhelper->getSystemParameters('DEMAND_PROFILE_DATA_INFORMED');
        unset($resultset[0]['ds_password']);

        $json = json_encode($resultset[0]);

        $labels = array(
            "fullname" => 'Full Name',
            "email" => 'E-Mail',
            "retypepassword" => 'Retype Password',
            "newpassword" => 'New Password',
            'currentpassword' => 'Current Password',
            "confirm" => 'Confirm',
            'info_changed' => 'There are information changed. Confirm Close ?',
            'passnotmatch' => 'Password Not Matching!',
            'inv_email' => 'Invalid Email Address!',
            'projrole' => 'Project Role',
            'introData' => 'Role and Team details are required to proceed!',
            'fl_demand_profile' => $fl_demand_profile,
            'teamdata' => 'Team',
            'error_size' => 'Size cannot be more than');

        $labels = $this->cdbhelper->retTranslationDifKeys($labels);

        $this->load->view("userprofile", array("javascript" => '',
            "filters" => '',
            'resultset' => $json,
            'pk' => $cd_human_resource,
            'accept' => $this->doctype->getAvailableExtensionsBrowserByMime('image/jpeg'),
            'img' => $this->cdbhelper->getUserPicture($cd_human_resource, 'C'),
            "filters_java" => '') + $labels);
    }

    public function updateProfileForm() {

        $upd_array = json_decode($_POST['upd']);
        $arraysend = array($upd_array);

        $files = $this->cdbhelper->cgbFileUploadParse($_POST['cgbFileUpload']);

        if (count($upd_array) != 0) {

            $ds_password = $upd_array['ds_current_password'];
            $ds_password_new = $upd_array['ds_new_password'];
            $ds_password_retype = $upd_array['ds_retype_password'];

            $error = $this->human_resource->updateGridData($arraysend);
            if ($error !== 'OK') {
                $msg = '{"status":' . json_encode($error) . ', "rs":{} }';
                echo ($msg);
                return;
            } else {
                $retResult = '{}';
            }
        }

        if (count($files) > 0) {
            $this->cdbhelper->saveUserPicture($_POST['recid'], $files[0]['tmp_filename']);
        }

        $retResult = $this->human_resource->retRetrieveGridJsonForm($arraysend[0]->recid);
        $msg = '{"status": "OK"' . ', "rs":' . $retResult . '}';
        echo $msg;
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


        //$arrayCan = array('factory' => $demFactory, 'customer' => $demCustomer, 'division' => $demDivision );


        $fm = $this->cfiltermaker;

        /* Process Group */

        $grid->resetGrid();
        $grid->setGridToolbarFunction('dsMainObject.ToolBarClick');

        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar(true, false, true, false, false);

        $grid->addBreakToolbar();
        $grid->setToolbarSearch(true);

        $grid->addUserBtnToolbar('openjob', 'Role Permission', 'fa fa-key');

        $grid->addUserBtnToolbar("openmenu", "Menu Options Maintenance", "fa fa-tasks", $caption = "");

        $grid->addBreakToolbar();
        $grid->addUserBtnToolbar('copy', 'Copy', 'fa fa-copy');

        //$grid->addUserCheckToolbar('copyjob', "Role", '', false, 'fa fa-key' );

        $grid->addUserBtnToolbar('paste', 'Merge Permissions', 'fa fa-paste');
        $grid->addUserBtnToolbar('importAD', 'fetch information from AD', 'fa fa-thermometer-half ');

        $grid->addBreakToolbar();

        $grid->addSpacerToolbar();

        $grid->addExportToolbar();

        $grid->setCRUDController("user_maint");

        $grid->addHiddenColumn('recid', 'Code', '80px', $f->retTypeInteger());

        $grid->addColumn('ds_human_resource', 'Username', '100px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_human_resource_full', 'Full Name', '100%', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_hr_type', 'Type', '150px', $f->retTypeStringAny(), false);
        $grid->addRecords(json_encode($this->retrieveToRights(true)));
        $grid->setGridName('grdHmresource');
        $grid->setGridDivName('grdHmresourceDiv');

        $javas = $grid->retGrid();


        // Jobs
        $grid->resetGrid();
        $grid->setGridToolbarFunction('dsMainObject.ToolBarClick');

        $grid->setSingleBarControl(true);

        $grid->setToolbarSearch(true);
        $grid->addSpacerToolbar();
        $grid->addExportToolbar();

        $grid->addHiddenColumn('recid', 'Code', '80px', $f->retTypeInteger());

        $grid->addColumn('ds_description', 'Roles', '100%', $f->retTypeStringAny(), false);

        $grid->setGridName('grdJob');
        $grid->setGridDivName('grdJobDiv');

        $javas = $javas . $grid->retGrid();


        // Jobs
        $grid->resetGrid();
        $grid->setGridToolbarFunction('dsMainObject.ToolBarClick');

        $grid->setSingleBarControl(true);

        $grid->setToolbarSearch(true);
        $grid->addSpacerToolbar();
        $grid->addExportToolbar();

        $grid->addHiddenColumn('recid', 'Code', '80px', $f->retTypeInteger());
        $grid->addColumn('fl_checked', 'X', '30px', $f->retTypeCheckBox(), true);
        $grid->addColumn('ds_description', 'Location', '100%', $f->retTypeStringAny(), false);

        $grid->setGridName('grdLocation');
        $grid->setGridDivName('grdLocationDiv');

        $javas = $javas . $grid->retGrid();




        //$filters = $fm->retFiltersWithGroup();



        $trans = array('vHm' => 'Human Resource',
            'vFact' => 'Factory',
            'vDiv' => 'Division',
            'vCust' => 'Customer',
            'vCat' => 'Product Category',
            'vJob' => 'Roles',
            "menumaint" => 'Menu Permission Maintenance',
            'nouserselected' => 'No User selected to copy the rights from',
            'selectwhat' => 'You must select what you want to paste!',
            'confpaste' => 'Confirm paste data from',
            'to' => 'to',
            'copying' => 'Merging',
            'vLocations' => 'Location'
        );

        $trans = $this->cdbhelper->retTranslationDifKeys($trans);

        $filters = $fm->retFiltersWithGroup();


        $send = array("javas" => $javas,
            "jsonParam" => json_encode(array())
                ) + $trans;


        $this->load->view("human_resource_rights_view", $send);
    }

    public function retrieveToRights($returnAsVar = false, $where = ' WHERE 1 = 1 ') {
        $this->load->model('job_model', 'job', TRUE);
        $this->load->model('system_product_category_model', 'prdcat', TRUE);

        $where2 = $where;

        $array = $this->human_resource->retrieveDataArray($where2);

        foreach ($array as $key => $value) {

            $jsonJob = $this->job->retGridJsonByHM($value['recid'], 'R', true);
            $array[$key]['ds_job'] = json_decode($jsonJob, true);


            $array[$key]['ds_loc'] = json_decode($this->prdcat->retGridJsonByHM($value['recid'], 'B', true), true);
        }

        if ($returnAsVar) {
            return $array;
        } else {
            echo(json_encode($array));
        }
    }

    public function mergePermissions($hmfrom, $hmto) {

        $sql = 'SELECT public.CopyHMPermissions (%s, %s,  \'Y\');';

        $hmfrom = $this->cdbhelper->normalizeDataToSQL('int', $hmfrom);
        $hmto = $this->cdbhelper->normalizeDataToSQL('int', $hmto);



        $sql = sprintf($sql, $hmfrom, $hmto);

        $this->getCdbhelper()->basicSQLNoReturn($sql);

        $this->retrieveToRights(false, ' WHERE cd_human_resource = ' . $hmto);
    }

    public function updateData() {

        $this->load->model('system_product_category_model', 'prdcat', TRUE);
        $upd_array = json_decode($_POST['upd'], true);
        $retResultset = 'N';

        $this->getCdbhelper()->trans_begin();
        foreach ($upd_array as $key => $value) {
            if (isset($value['ds_loc'])) {
                $error = $this->newmodel->updatePrdCatCheckbox($value['recid'], $value['ds_loc']);
                if ($error != 'OK') {
                    $this->getCdbhelper()->trans_rollback();
                    $msg = '{"status":' . json_encode($error) . '}';
                    echo($msg);
                    die();
                }
            }
        }

        $this->getCdbhelper()->trans_commit();

        //die('dentro do basic');
        
        $msg = '{"status":' . json_encode($error) . '}';
        echo($msg);
    }

    public function retrPermData() {
        //$ret = array('resultset' => $this->retrieveToRights(true));

        echo (json_encode($this->retrieveToRights(true)));
    }

    public function importAD() {

        $ret = $this->newmodel->importAD();
        echo(json_encode($ret));
        return;

    }

}
