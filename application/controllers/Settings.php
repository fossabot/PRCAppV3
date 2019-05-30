<?php

if (!defined('BASEPATH'))
   exit('No direct script access allowed');

include_once APPPATH . 'controllers/controllerBasicExtend.php';

class settings extends controllerBasicExtend {

   var $arrayIns;

   function __construct() {
      parent::__construct();

      // primeira acoisa a fazer eh verificar se esta logado!

   }

   public function index() {
      $f = $this->cfields;

      $this->w2gridgen->setGridVar('varGridSettings');
      $this->w2gridgen->setGridDivName('varGridSettingsDiv');
      $this->w2gridgen->setGridName('settingsGrid');
      $this->w2gridgen->setGridToolbarFunction('onGridToolbarPressedSettings');
      $this->w2gridgen->addUpdToolbar();
      $this->w2gridgen->setToolbarPrefix('setting');
      $this->w2gridgen->addColumn('recid', 'Code', '50px', $f->retTypeKey());
      $this->w2gridgen->addColumn('ds_system_settings', 'Description', '50%', $f->retTypeStringAny(), false);
      $this->w2gridgen->addColumn('ds_system_settings_options', 'Chosed', '50%', $f->retTypePickList(), false);
      $this->w2gridgen->addRecords($this->settings_model->retRetrieveJson());

      $javascript = $this->w2gridgen->retGrid();

      $send = array("javascript" => $javascript);

      $this->load->view("settings_view", $send);


      //print_r($arrayIns);
   }

   public function getOptionsPL($optionId) {


      $records = $this->cdbhelper->basicSelectForPL("SYSTEM_SETTINGS_OPTIONS", "cd_system_settings_options", "ds_system_settings_options", "WHERE cd_system_settings = " . $optionId, "", false);
      $code = $this->cdbhelper->retTranslation('Code');
      $description = $this->cdbhelper->retTranslation('Description');
      $javascript = "";
      $recordsetJson = json_encode($records);
      $controller    = '';
      $array_trans = array('code' => 'Code', 
                           'description' => 'Description', 
                            'clear' => 'Clear Data',
                            'openmaint' => 'Open Maintenance',
                            'controller' => $controller);


      $this->load->view('basicpicklist_view', array('records' => $recordsetJson,
         
         'javascript' => $javascript,
         'selid' => '-1')+ $array_trans);
   }

   public function updateDataJson() {
      $upd_json = json_decode($_POST['upd'], true);
      $upd_array = array();


      $error = $this->settings_model->updateGridData($upd_json);
      $error = 'OK';
      $this->settings_model->sendSettingsToDb();

      echo ($error);
   }

}

?>