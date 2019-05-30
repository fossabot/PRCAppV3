<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

include_once APPPATH . 'controllers/controllerBasicExtend.php';

class dashboard extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        $this->methodToIgnoreSession = array('refreshDashPOEvent', 'refresh', 'checkSession');

        parent::__construct();
        $this->load->model('dashboard/system_dashboard_widget_model', 'systemdash', TRUE);
        $this->load->model('dashboard/hr_system_dashboard_widget_param_model', 'dashsettings', TRUE);
    }

    public function index() {
        $dashs = $this->systemdash->getUserDashboard();
        //die (print_r($dashs));
        $dashAvailable = $this->systemdash->retRetrieveArray();
        $html = $this->retWidgetHtml($dashs);

        $trans = array("title" => "Dashboard", 'confremove' => 'Confirm Remove Widget ?',
            'introArea' => 'Widget Area',
            'introLastRef' => 'Last time the Widget was updated',
            'introRef' => 'Refresh Information',
            'introSet' => 'Open Settings',
            'introExp' => 'Expand/Collapse Widget Area',
            'introMove' => 'Widget Move'
        );
        
        //die (print_r($dashAvailable));

        $trans = $this->cdbhelper->retTranslationDifKeys($trans);

        $this->load->view("dashboard/dashboard_view", array('html' => $html, 'avail' => $dashAvailable) + $trans);
    }

    public function retWidgetHtml($dashs) {
        $html = '';

        foreach ($dashs as $key => $value) {
            switch ($value['cd_system_dashboard_widget']) {
                case 1:

                    $html = $html . $this->getDashCalendar(false, $value);
                    break;

                case 2:

                    $html = $html . $this->getDashPendingView(false, $value);
                    break;

                case 3:

                    break;


                default:
                    break;
            }
        }
        return $html;
    }

    public function refresh($id, $settingId) {
        switch ($id) {
            case 1:

                $this->refreshCalendarEvent($settingId);

                break;

            case 2:

                $this->refreshPendingEvent($settingId);

                break;

            case 3:

                $this->refreshDashSKUEvent($settingId);

                break;




            default:
                break;
        }
    }

    public function getDashCalendar($echo = true, $value = array()) {

        $trans = array("division" => "Division", 'season' => 'Season', 'total' => 'Total Tasks', 'process' => 'Processes', 'ref' => 'Refresh (Min.)'
        );

        $trans = $this->cdbhelper->retTranslationDifKeys($trans);



        return $this->load->view("dashboard/calendar_widget_view", $value + $trans, true);
    }

    public function getDashPendingView($echo = true, $value = array()) {
//        $this->load->model('hrms/calendar_type_model', 'evmodel');
        $grid = $this->w2gridgen;
        $f = $this->cfields;
        $fm = $this->cfiltermaker;

        if (1 == 2) {
            $f = new Cfields();
            $grid = new w2gridgen();
            $fm = new cfiltermaker();
        }
        $grid->resetGrid();
        $objName = 'dsDashPendingEvent'.$value['cd_hm_system_dashboard_widget_param'];
        
        $grid->setGridToolbarFunction($objName.'.toolbar');
        $grid->setToolbarSearch(false);
        $grid->setCRUDController("hrms/address");
        $grid->setAsSystemGrid();
        $grid->addSpacerToolbar();
        $grid->addExportToolbar();
        $grid->addColumnKey();
        $grid->setRowHeight(35);
        $grid->showLineNumbers(false);

        $grid->addColumn('ds_type', 'Type', '60px', $f->retTypeStringAny(), false);
        $grid->addColumn('nr_key', 'Code', '60px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_pending_action', 'Action', '100%', $f->retTypeStringAny(), false);
        $grid->addColumn('dt_requested', 'Added On', '110px', $f->retTypeDate(), false);
        $grid->addColumn('ds_action', 'Action', '80px', $f->retTypeStringAny(), false);
        
        $grid->setGridName('pendingEventGrid'.$value['cd_hm_system_dashboard_widget_param']);
        $grid->setGridVar('makeGrid');
        $grid->setColumnRenderFunc('ds_action', $objName.'.renderAction');

        $javascript = $grid->retGridVar();     
        
        $trans = array( 'total' => 'Total Tasks', 'process' => 'Events', 'ref' => 'Refresh (Min.)');
        

        //die (print_r($ev));
        
        $trans = $this->cdbhelper->retTranslationDifKeys($trans);
       
        
        $trans['grid'] = $javascript;
        return $this->load->view("dashboard/pending_tasks_widget_view", $value + $trans, true);
    }
    
    
    
    /*
    
    
    public function getDashSMPEventView($echo = true, $value = array()) {
        $canProcessFollowup = $this->cdbhelper->checkMenuRights('shoe_process_status', 'N');

        $trans = array("division" => "Division", 'season' => 'Season', 'total' => 'Total Tasks', 'process' => 'Processes', 'ref' => 'Refresh (Min.)'
        );

        $trans = $this->cdbhelper->retTranslationDifKeys($trans);


        $this->load->library('cprocessmaker');
        if (1 == 2) {
            $vProcMaker = new cProcessMaker();
        }
        $vProcMaker = $this->cprocessmaker;


        $v = $vProcMaker->getProcessByLeveltoSelection($vProcMaker->getProcessLevelSampleSKU());


        return $this->load->view("dashboard/smpeventdeadline_view", array('canfollowup' => $canProcessFollowup, 'process' => $v) + $value + $trans, true);
    }

    public function getDashSKUEventView($echo = true, $value = array()) {

        $canProcessFollowup = $this->cdbhelper->checkMenuRights('shoe_process_status', 'N');

        $trans = array("division" => "Division", 'season' => 'Season', 'total' => 'Total Tasks', 'process' => 'Processes', 'ref' => 'Refresh (Min.)');

        $trans = $this->cdbhelper->retTranslationDifKeys($trans);


        $this->load->library('cprocessmaker');
        if (1 == 2) {
            $vProcMaker = new cProcessMaker();
        }
        $vProcMaker = $this->cprocessmaker;


        $v = $vProcMaker->getProcessByLeveltoSelection($vProcMaker->getProcessLevelSKU());

        return $this->load->view("dashboard/skueventdeadline_view", array('canfollowup' => $canProcessFollowup, 'process' => $v) + $value + $trans, true);
    }
*/
    
    
    public function refreshCalendarEvent($settingId) {
        $this->load->model('hrms/calendar_model', 'calmodel', TRUE);

        $dates = $this->calmodel->getDates('1980-01-01', '2030-01-01');
        $datesAdj = $this->calmodel->makeDatesToCalendar($dates);

        echo (json_encode($datesAdj));
    }

    public function refreshPendingEvent($settingId) {
        
        $data = $this->getCdbhelper()->basicSQLJson("SELECT * FROM getPendingTasks($settingId) order by dt_requested");
        
        echo ($data);
    }

    
    
    

    public function addNewWidget($cd_system_dashboard_widget) {

        $newArray = $this->dashsettings->retRetrieveEmptyNewArray();
        $newArray[0]['cd_human_resource'] = $this->db->cd_human_resource;
        $newArray[0]['cd_system_dashboard_widget'] = $cd_system_dashboard_widget;
        $newArray[0]['cd_system_product_category'] = $this->db->cd_system_product_category;

        unset($newArray[0]['cd_hm_system_dashboard_widget_param']);
        unset($newArray[0]['json_parameters']);

        $nextOrder = $this->dashsettings->getNextOrder($this->db->cd_human_resource);

        $newArray[0]['nr_order'] = $nextOrder;


        $ok = $this->dashsettings->updateGridData($newArray);

        if ($ok = !'OK') {
            echo ($ok);
            return;
        }



        $dashs = $this->systemdash->getUserDashboard(' AND cd_hm_system_dashboard_widget_param = ' . $newArray[0]['recid']);

        $html = $this->retWidgetHtml($dashs);

        $ret = array('html' => $html, 'id' => $newArray[0]['recid']);

        echo(json_encode($ret));
    }

    public function removeWidget($cd_system_dashboard_widget) {
        $ok = $this->dashsettings->deleteGridData(array($cd_system_dashboard_widget));

        echo($ok);
    }

    public function saveSettings($settingId) {
        $set = $_POST['settings'];

        $array_upd = array(array('recid' => $settingId, 'json_parameters' => json_encode($set)));

        $ok = $this->dashsettings->updateGridData($array_upd);

        echo ($ok);
    }

    public function saveGeneralSettings() {
        //die (print_r($_POST));

        if (!isset($_POST['order'])) {
            echo ('OK');
            return;
        }

        $array_upd = $_POST['order'];


        $ok = $this->dashsettings->updateGridData($array_upd);

        echo ($ok);
    }

    public function checkSession() {
        echo ('OK');
    }

}
