<?php

/*
  require_once APPPATH . 'libraries/minify-master/src/Minify.php';
  require_once APPPATH . 'libraries/minify-master/src/Minify.php';
  require_once APPPATH . 'libraries/minify-master/src/CSS.php';
  require_once APPPATH . 'libraries/minify-master/src/JS.php';
  require_once APPPATH . 'libraries/minify-master/src/Exception.php';
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Main extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('menumodel', '', TRUE);
        $this->load->model('human_resource_model', 'hmmodel', TRUE);
        $this->load->model('docrep/document_repository_type_model', 'doctypemodel', TRUE);
        
        $this->load->helper('cookie');
    }

    public function logout() {
        $this->logincontrol->logout();
    }

    public function index() {
        //$this->load->model("human_resource");
        $cd_human_resource = $this->session->userdata('cd_human_resource');

        if (!$cd_human_resource) {
            $this->load->view('login');
            return;
        }

        if (!$this->logincontrol->isProperLogged()) {
            $this->logout();
            //die('Your Session Expired! Please login again (Err: 1602)');
        }

        $towhere = $this->session->flashdata('redirect');
        $towhereTitle = $this->session->flashdata('redirect_title');
        $towhereId = $this->session->flashdata('redirect_id');
        $toParams = $this->session->flashdata('param');


        if (!isset($towhere)) {
            $towhere = '';
            $towhereTitle = '';
            $towhereId = '';
            $toParams = '';
        } else {
            //die (print_r(array ($towhere, $towhereTitle, $towhereId, $toParams)));
        }

        $welcome = $this->cdbhelper->retTranslation('Welcome');
        $main = $this->cdbhelper->getSystemParameters('SYSTEM_FULL_NAME');
        $fl_demand_profile = $this->cdbhelper->getSystemParameters('DEMAND_PROFILE_DATA_INFORMED');
        $mainAbbrev = $this->cdbhelper->getSystemParameters('SYSTEM_ABRREV_NAME');
        $menu = $this->menumodel->returnMenuSelect();

        $messages = $this->mountJavaLibMessages();

        $datef = $this->cdbhelper->getSettings('fl_date_format');

        $start_on_dashboard = $this->cdbhelper->getSettings('fl_start_on_dashboard');
        // controle de IP.
        $lic = $this->cdbhelper->getSettings('local_ip_control');
        $lips = $this->cdbhelper->getSystemParameters('LOCAL_IP');
        $userPath = $this->cdbhelper->getSystemParameters('PATH_USER_PICTURES');
        $rights_ip = $this->cdbhelper->getUserPermission('fl_allow_connect_remotely');

        if ($lic == 'Y' && $_SERVER['REMOTE_ADDR'] != $lips && $rights_ip == 'N') {
            die('IP NOT Authorized :' . $_SERVER['REMOTE_ADDR']);
        }

        $dateArray = explode(';', $datef);

        $f = $this->cfields;

        if (1 == 2) {
            $f = new Cfields();
        }


        $src = $this->cdbhelper->getUserPicture($cd_human_resource, 'C');


        $date = new DateTime();

        $defprdcat = $this->input->cookie('dfdevshoesPrdCat' . $_SERVER['custinfo'], true);

        if ($defprdcat != NULL) {
            $vAllowed = $this->session->userdata('system_product_category_allowed');
            if (array_search($defprdcat, array_column($vAllowed, 'cd_system_product_category')) !== false) {
                $this->session->set_userdata('system_product_category', $defprdcat);
            }
        } else {
            $defprdcat = $this->session->userdata('system_product_category');
        }


        $this->input->set_cookie('dfdevshoesPrdCat' . $_SERVER['custinfo'], $defprdcat);
        
        $userdata = $this->hmmodel->retRetrieveArray(' WHERE cd_human_resource =  ' . $cd_human_resource)[0];
        
        if($fl_demand_profile == 'Y' && $userdata['cd_roles'] != null && $userdata['cd_team'] != null) {
            $fl_demand_profile = 'N';
        }

        //die (print_r($userdata[0]));

        $sendinfo = array(
            'ds_human_resource_full' => $this->session->userdata('ds_human_resource_full'),
            'userdata' => $userdata,
            'userdataJson' => json_encode($userdata),
            'whatToOpen' => $this->cdbhelper->getSettings('cd_start_sytem_on'),
            'fl_demand_profile' => $fl_demand_profile,
            'jmessages' => $messages,
            'welcome' => $welcome,
            'dateFormat' => $dateArray[0],
            'main' => $main,
            'mainAbbrev' => $mainAbbrev,
            'controllerToOpen' => $towhere,
            'controllerTitle' => $towhereTitle,
            'controllerId' => $towhereId,
            'controllerParms' => $toParams,
            'fstringUpper' => $f->retTypeStringUpper(),
            'fstringLower' => $f->retTypeStringLower(),
            'fPicklist' => $f->retTypePickList(),
            'menu' => $menu,
            'userImage' => $src,
            'home' => $this->cdbhelper->retTranslation('Home'),
            'extUpload' => $this->doctypemodel->getAvailableExtensionsBrowserByMime(''),
            'config' => $this->makeSettingsArea(),
            'skin' => $this->cdbhelper->getSettings('system_theme'),
            'profile' => $this->cdbhelper->retTranslation('Profile'),
            'signout' => $this->cdbhelper->retTranslation('Sign Out'),
            'canprofile' => $this->cdbhelper->getSystemParameters('USER_PROFILE_CHANGEABLE'),
            'timeStamp' => $date->getTimestamp(),
            'hashCommit' => $this->getHashCommit(),
            'companyName' => $this->db->companyName,
            'system_product_category_allowed' => $this->session->userdata('system_product_category_allowed'),
            'system_product_category' => $this->session->userdata('system_product_category'),
            'startOnDash' => $start_on_dashboard
        );

        $this->load->view('main', $sendinfo);
        
    }

    public function mountJavaLibMessages() {
        //javaMessages
        $msgs = array(
            "updated" => 'Update Done!',
            "moment" => 'Just a moment...',
            "delnewonly" => 'Only can delete new records',
            "confirm_retrieve" => 'There are information changed. Confirm Retrieve ?',
            "confirm" => 'Confirm',
            "loading" => 'Loading...',
            'alert' => 'Alert',
            "update_done" => 'Update Done!',
            "updating" => 'Updating...',
            "error_upd" => 'Error Updating:',
            "inserting" => 'Inserting...',
            "conf_delete" => 'Confirm to Delete selected lines ?',
            "deleting" => 'Deleting...',
            "del_done" => 'Delete Done!',
            "error_del" => 'Error Deleting:',
            "ins_line" => 'Insert Line',
            "update" => 'Update Information',
            "deleteMsg" => 'Delete',
            "close_screen" => 'Close Screen',
            "required_info" => 'There are required information missing! Cannot Save!',
            "invalid_date" => 'Invalid Date',
            'info_changed_close' => 'There are information changed. Confirm Close ?',
            'filterPlaceHolderAll' => 'ALL',
            'filterPlaceHolderChoose' => 'CHOOSE',
            'filterOperator' => 'Operator Options',
            'filterLike' => 'Like',
            'fitterStartWith' => 'Start With',
            'filterShowAll' => 'Show All',
            'filterShowActive' => 'Show Only Active',
            'filterShowDeac' => 'Show Only Deactivated',
            'filterClear' => 'Clear',
            'filterWith' => 'With',
            'filterWithout' => 'Without',
            'filterNone' => 'None',
            'filterAny' => 'Any',
            'filterText' => 'Filter',
            'filterRefresh' => 'Reload',
            'edit_line' => 'Edit Selected Line',
            'docrep' => 'Document Repository',
            'retrieveData' => 'Retriving Data',
            'saveFirst' => 'Please Save First',
            'retrieveInfo' => 'Retrieve Information',
            'buttonOK' => 'Okay',
            'buttonCancel' => 'Cancel',
            'buttonYes' => 'Yes',
            'buttonNo' => 'No',
            'ErrorTitle' => 'Error',
            'confirmAction' => 'There are information changed. Confirm Action ?',
            'switchON' => 'ON',
            'switchOFF' => 'OFF',
            'toggleFilter' => 'Toggle Filter',
            'msgMissingInformation' => 'Cannot Update! There are missing information.',
            'selectLineFirst' => 'Please select a Line',
            'filterEqual' => 'Equal',
            'filterBetween' => 'Between',
            'selectAll' => 'Select All',
            'unselectAll' => 'Remove Selection',
            'default' => 'Default',
            'filterErrorCannotFilter' => 'Cannot Filter',
            'filterErrorDemandedFilterMissing' => 'Demanded Filter Missing',
            'filterErrorGroupFilterMissing' => 'Group Filter Missing (selecting one is enough)',
            'gridShowHideColumnLabel' => 'Show/Hide Information',
            'preset' => 'Preset',
            'pleaseinfdescr' => 'Please inform the Description',
            'confirmReplace' => 'Confirm replace existing information ?',
            'hide' => 'Hide',
            'share' => 'Share',
            'userToShareError' => 'You must select an User to share',
            'selectUser' => 'Select User',
            'chooseUserToShare' => 'Choose User to Share',
            'errorSize' => 'The file %1 is bigger than allowed: <br> Max: %2 <br>File: %3 '
        );




        $msgs = $this->cdbhelper->retTranslationDifKeys($msgs);


        $w2uiMessages = array('Required field' => 'Required Field');
        $w2uiMessages = $this->cdbhelper->retTranslationDifKeys($w2uiMessages);

        $msgs = $msgs + array('w2uilang' => $w2uiMessages);


        //echo (json_encode($msgs));

        return json_encode($msgs);
    }

    public function showCache() {
        $mem = new Memcached();
        $mem->addServer('localhost', 11211);
        //$array = $mem->get('user'.$this->session->userdata('cd_human_resource').'_settings');
        $all = $mem->getAllKeys();
        echo print_r($array);
    }

    public function clearCache() {
        $mem = new Memcached();
        $mem->addServer('localhost', 11211);
        $mem->flush();
    }

    public function trans() {
        print_r($this->cdbhelper->retTranslationMem(array('teste' => 'Article', 'teste2' => 'Administration')));
    }

    public function redirect($towhere, $towhere2 = '', $towhere3 = '') {

        if ($towhere2 != '') {
            $towhere = $towhere . '/' . $towhere2;
        }

        if ($towhere3 != '') {
            $towhere = $towhere . '/' . $towhere3;
        }

        if (isset($_GET['param'])) {
            $this->session->set_flashdata('param', $_GET['param']);
        }


        $this->session->set_flashdata('redirect', $towhere);
        $this->session->set_flashdata('redirect_title', $this->menumodel->getMenuName($towhere));
        $this->session->set_flashdata('redirect_id', $this->menumodel->getMenuId($towhere));

        redirect('main');
    }

    public function makeSettingsArea() {
        $this->load->model('settings_model', 'setmodel');
        $html = '';
        $title = '';
        $cd_system_settings = -1;
        $checkbox_info = array();

        $info = $this->setmodel->retConfigInformation();

        foreach ($info as $key => $value) {


            // criado grupo
            if ($value['ds_system_settings_group'] != $title) {
                $title = $value['ds_system_settings_group'];
                $html = $html . ' <h3 class="control-sidebar-heading">' . $title . ' </h3>';
            }

            //criacao do cabecalho do tipo de dado
            if ($value['cd_system_settings'] != $cd_system_settings) {
                $checkbox_info = array();

                $cd_system_settings = $value['cd_system_settings'];

                $html = $html . '
            <div class="form-group">';

                switch ($value['fl_type_selection']) {
                    case 'D':
                        $html = $html . '
                       <label for="p_' . $value['ds_system_settings_id'] . '" class= "control-label xs" style="font-size: 12px;">' . $value['ds_system_settings'] . '</label> 
                       <select id="p_' . $value['ds_system_settings_id'] . '" codess="' . $value['cd_system_settings'] . '" onChange="settingsChanged(this);" class="pull-right mbSettingsDropdown input-sm">';
                        break;

                    CASE 'C':
                        /*
                          $html = $html . '
                          <label for="p_' . $value['ds_system_settings_id'] . '" class= "control-label xs" style="font-size: 12px;">' . $value['ds_system_settings'] . '</label>
                          <input id="p_' . $value['ds_system_settings_id'] . '" codess="' . $value['cd_system_settings'] . '"  type="checkbox" onChange="settingsChanged(this);" class="pull-right  mbSettingsCheckBox" ' . ($value['ds_option_id_selected'] == 'Y' ? 'checked' : '' ) . ' ';
                         * */

                        $html = $html . ' <div class="checkbox  checkbox-slider--b-flat checkbox-slider-info">

                    <label for="p_' . $value['ds_system_settings_id'] . '" class= "" style="font-weight: 700;"> 
                    <input type="checkbox" name="onoffswitch" codess="' . $value['cd_system_settings'] . '" class="mbSettingsCheckBox" id="p_' . $value['ds_system_settings_id'] . '" onChange="settingsChanged(this);return true;"  ' . ($value['ds_option_id_selected'] == 'Y' ? 'checked' : '' ) . ' ';


                    default:
                        break;
                }
            }
            // detalhe: 
            switch ($value['fl_type_selection']) {
                case 'D': // dropdown
                    $canShow = true;
                    if ($value['ds_system_settings_id'] == 'cd_start_sytem_on') {
                        $split = explode(';', $value['ds_option_id']);
                        if ($split[0] > 0) {
                            $ret = $this->cdbhelper->checkMenuRights($split[1]);
                            $canShow = ($ret == 'Y');
                                    
                        }
                    } 
                    
                    if ($canShow) {
                        $html = $html . '<option value="' . $value['cd_system_settings_options'] . '" ' . ($value['cd_system_settings_options_selected'] == $value['cd_system_settings_options'] ? 'selected' : '' ) . ' > ' . $value['ds_system_settings_options'] . ' </option> ';
                    }
                        

                    
                    break;

                case 'C': // checkbox
                    $checkbox_info[$value['ds_option_id']] = $value['cd_system_settings_options'];


                    break;

                default:
                    break;
            }



            if (count($info) == ($key + 1) || (isset($info[$key + 1]) && $info[$key + 1]['cd_system_settings'] != $cd_system_settings)) {

                // fecha os grupos!!!
                // detalhe: 
                switch ($value['fl_type_selection']) {
                    case 'D':
                        $html = $html . ' 
                        </select>';

                        break;

                    case 'C': // checkbox:

                        $html = $html . " codes = '" . json_encode($checkbox_info) . "' >" . '<span>' . $value['ds_system_settings'] . '</span>
                    </label>
                </div>';





                        break;

                    default:
                        break;
                }
                $html = $html . ' 
                        </div>';
            }
        }
        //echo ($html);

        $html = $html . '
                <button class="btn bg-blue-gradient btn-xs" disabled id="settingsButton" onClick="settingsUpdate();return false;">Update</button> ';

        return ($html);
    }

    public function loadJS($nothing) {
        //ob_start("ob_gzhandler");
        header('Content-Type: text/javascript'); //<-- send mime-type header
          header("Cache-Control: cache"); //HTTP 1.1
          header("Pragma: cache"); //HTTP 1.1

        header('Expires: Mon, 26 Jul 2050 05:00:00 GMT'); // Date in the past

        //ob_start("ob_gzhandler");
        //use MatthiasMullie;
        $varpath = base_url();
        $debug = $this->cdbhelper->getSettings('fl_debug_mode');


        //$mini = new MatthiasMullie\Minify/JS;
        //if ($debug == 'Y') {
        //use 'MatthiasMullie';
        //}


        $array_js = array(
            $varpath . 'plugins/w2ui-1.4.3/w2ui-1.4.3.js',
            $varpath . 'application/javascripts/library.js',
            $varpath . 'application/javascripts/select2utils.js',
            $varpath . 'application/javascripts/libraryGrid.js',
            $varpath . 'application/javascripts/libraryFormCtrl.js',
            $varpath . 'application/javascripts/libraryProcess.js',
            $varpath . 'application/javascripts/libraryDocRep.js'
        );


        foreach ($array_js as $key => $value) {
            readfile($value);
            //$fp = fopen($value, 'rb');
            //fpassthru($fp);
            //fclose($fp);
        }
    }

    /*
      <script type="text/javascript" src="<?php echo base_url(); ?>application/javascripts/library.js"></script>
      <script type="text/javascript" src="<?php echo base_url(); ?>application/javascripts/select2utils.js"></script>
      <script type="text/javascript" src="<?php echo base_url(); ?>application/javascripts/libraryGrid.js"></script>
      <script type="text/javascript" src="<?php echo base_url(); ?>application/javascripts/libraryFormCtrl.js"></script>

      <script type="text/javascript" src="<?php echo base_url(); ?>application/javascripts/libraryProcess.js"></script>

     */

    public function changeProdCat($prodCat) {


        $cd_human_resource = $this->session->userdata('cd_human_resource');

        if (!$cd_human_resource) {
            $this->load->view('login');
            return;
        }

        if (!$this->logincontrol->isProperLogged()) {
            $this->logout();
            //die('Your Session Expired! Please login again (Err: 1602)');
        }


        $vAllowed = $this->session->userdata('system_product_category_allowed');

        if (array_search($prodCat, array_column($vAllowed, 'cd_system_product_category')) === false) {
            die('Not Allowed');
        }

        $this->session->set_userdata('system_product_category', $prodCat);
        redirect('main');
    }

    function ae_detect_ie() {
        if (isset($_SERVER['HTTP_USER_AGENT']) &&
                (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false))
            return 'Y';
        else
            return 'N';
    }
    
    function getHashCommit () {
        $commitHash = trim(exec('git log --pretty="%h" -n1 HEAD'));

        //$commitDate = new \DateTime(trim(exec('git log -n1 --pretty=%ci HEAD')));
        //$commitDate->setTimezone(new \DateTimeZone('UTC'));
        return $commitHash;
    }

}

?>