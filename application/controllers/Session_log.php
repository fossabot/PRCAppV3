<?php

if (!defined("BASEPATH"))
   exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class session_log extends controllerBasicExtend {

   var $arrayIns;
   var $fields;

   function __construct() {
      parent::__construct();
      $this->load->model("session_log_model", "mainmodel", TRUE);
   }

   public function index() {

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

      $fm->addSimpleFilterUpper('Database', 'filter_1', 'ds_database');
      $fm->addSimpleFilterUpper('Username', 'filter_3', 'ds_username');

      
      $fm->addFilterYesNo("Logged", 'sp_logged_PL', '"SESSION_LOG".dt_expired', "Y");

      $this->setGridParser();
      $grid->setSingleBarControl(true);

      $grid->addUserBtnToolbar('expire', 'Expire selected session', 'fa fa-chain-broken');
      $grid->addBreakToolbar();
      $grid->addRetriveToolbar();
      $grid->addHideToolbar();
      
      $grid->setToolbarSearch(true);
      $grid->setCRUDController("session_log");

      $grid->addColumnKey();

      $grid->addColumn('ds_database', 'Database', '150px', $f->retTypeStringAny(), false);
      $grid->addColumn('ds_session', 'Session', '250px', $f->retTypeStringAny(), false);
      $grid->addColumn('ds_username', 'Username', '150px', $f->retTypeStringAny(), false);
      $grid->addColumn('ds_logged', 'Logged', '150px', $f->retTypeStringAny(), false);
      $grid->addColumn('ds_last_access', 'Last Access', '150px', $f->retTypeStringAny(), false);
      $grid->addColumn('ds_expired', 'Expired', '150px', $f->retTypeStringAny(), false);
      $grid->addColumn('ds_interval', 'Interval', '120px', $f->retTypeStringAny(), false);


      $filters = $fm->retFiltersWithGroup();
      $javascript = $grid->retGrid();


      $trans = array();
      $trans = $this->cdbhelper->retTranslationDifKeys($trans);



      $send = array("javascript" => $javascript,
         "filters" => $filters,
         "filters_java" => $fm->retJavascript()) + $trans;


      $this->load->view("session_log_view", $send);
   }
   
   
   public function expireSession($id) {
      
      $table = $this->db->escape_identifiers('SESSION_LOG');
       
      $sql = "UPDATE $table SET dt_expired = NOW() WHERE cd_session_log = " .$id;
      
      $array = $this->getCdbhelper()->basicSQLNoReturn($sql);
      
      $where = 'WHERE cd_session_log =' .$id;
      
      $ret = $retResult = $this->mainmodel->retRetrieveGridJson($where);
      
      $msg = '{"status":"OK", "resultset":' .$ret.'}';

      
      

      echo ($msg);
      
      
   }
   

}
