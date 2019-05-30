<?php

if (!defined('BASEPATH'))
   exit('No direct script access allowed');

class template extends CI_Controller {

   var $arrayIns;
   var $fields;

   function __construct() {
      parent::__construct();

      $this->load->model('country_model', 'mainmodel', TRUE);

   }

   public function index() {

      $grid = $this->w2gridgen;
      $f = $this->cfields;
      $fm = $this->cfiltermaker;

      if (1 == 2) {
         $f = new Cfields();
         $grid = new w2gridgen();
         $fm = new cfiltermaker();
      }


      $fm = $this->cfiltermaker;

      //$ret = $tb->returnHtml();

      $fm->addSimpleFilterUpper("Country", "ds_country");
      $fm->addFilterYesNo("Active", "dt_deactivated", '', "Y");
      $filters = $fm->retFiltersWithGroup();

      $grid->addCRUDToolbar();
      $grid->addColumnKey();
      $grid->addColumn('ds_country', 'Description', '100%', $f->retTypeStringUpper(), array("limit" => 64));
      $grid->addColumn('nr_country_number', 'Number', '60px', $f->retTypeStringUpper(), array("limit" => 3));
      $grid->addColumn('ds_iso_alpha2', 'A2', '60px', $f->retTypeStringUpper(), array("limit" => 2));
      $grid->addColumn('ds_iso_alpha3', 'A3', '60px', $f->retTypeStringUpper(), array("limit" => 3));
      $grid->addColumnDeactivated(true);


      $javascript = $grid->retGrid();


      $send = array("javascript" => $javascript,
         "filters" => $filters,
         "filters_java" => $fm->retJavascript());


      $this->load->view("country_view", $send);


      //print_r($arrayIns);
   }

   public function retrieveGridJson() {
      $where = '';
      if (isset($_POST['filter'])) {
         $where = $_POST['filter'];
      }
      echo ($this->mainmodel->retRetrieveGridJson($where));
   }

   public function retrieveJsonProdGrp($cd_product_group, $mode = 'B', $json = true) {
      echo ($this->mainmodel->retGridJsonPrdGrp($cd_product_group, $mode, $json, true));
   }

   public function retInsJson() {

      echo ($this->mainmodel->retInsJson());
   }

   public function updateDataJson() {
      $upd_array = json_decode($_POST['upd']);
      $error = $this->mainmodel->updateGridData($upd_array);

      echo $error;
   }

   public function deleteDataJson() {
      $del_array = json_decode($_POST['del']);

      $error = $this->mainmodel->deleteGridData($del_array);

      echo $error;
   }

   public function retPickList($way = "", $unionPK = "") {

      $where = "";
      $has_deac = $this->mainmodel->hasDeactivate();
      // 1 - busca apenas os ativos (usado para selecao em forms)
      if ($way == 1 && $has_deac) {
         $where = " where dt_deactivated IS NULL ";
      }

      //$arrayret = array("items", $this->mainmodel->selectForPL($where));
      $j = json_encode($this->mainmodel->selectForPL($where, $unionPK));
      $j = '{"items": ' . $j . '}';

      echo $j;
   }

}

?>