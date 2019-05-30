<?php

if (!defined('BASEPATH'))
   exit('No direct script access allowed');

include_once APPPATH . 'controllers/controllerBasicExtend.php';

class basicselectsbs extends controllerBasicExtend {

   var $arrayIns;

   function __construct() {
      parent::__construct();

      // primeira acoisa a fazer eh verificar se esta logado!

   }

   public function updSBSmodal() {

      $model = $_POST['modelUpd'];
      $id_key = $_POST['id'];
      $add = $_POST['add'];
      $remove = $_POST['remove'];
      $model = $this->decodeModel($model);

      $lastbar = strrpos($model, '/');
      $func = substr($model, $lastbar + 1);
      $model = substr($model, 0, $lastbar);
      $this->load->model($model, 'plModel', TRUE);


      $ret = call_user_func(array($this->plModel, $func), $id_key, $add, $remove);


      return $ret;
   }

   public function makeSBSModal() {
      // opcao para ter ateh 2 niveis de modelo
      // se veio nuemero no model2, e nao tem nada no id, significa que ele eh o id
      //echo (is_nan ($model2) ? 'YES' : 'NO');
      $model    = $_POST['modelRet'];
      $modelUpd = $_POST['modelUpd'];
      $title    = $_POST['title'];

      $model = $this->decodeModel($model);
      $id_key = $_POST['id'];

      $lastbar = strrpos($model, '/');
      $func = substr($model, $lastbar + 1);
      $model = substr($model, 0, $lastbar);

      //$records = $this->selectPLModal($model);
      $code = $this->cdbhelper->retTranslation('Code');
      $description = $this->cdbhelper->retTranslation('Description');
      $title       =  $this->cdbhelper->retTranslation($title);
      $javascript = "";

      
      $this->load->model($model, 'plModel', TRUE);
      // tem que ser: pk do outro, 'N' not related, 'R' Related, true = json, true=forseleciton
      $recordavail = call_user_func(array($this->plModel, $func), $id_key, 'N', true, true);
      $recordselected = call_user_func(array($this->plModel, $func), $id_key, 'R', true, true);

     
      $this->load->view('basicselectsbs_view', array('recordsavail' => $recordavail,
         'recordsselected' => $recordselected,
         'code' => $code,
         'MainId' => $id_key,
         'description' => $description,
         'javascript' => $javascript,
         'modelUpd' => $modelUpd,
         'title'    => $title
         )
      );
   }

   public function selectPLModal($model, $function, $retasReturn = true) {
      $this->load->model($model, 'plModel', TRUE);

      $where = '';

      if ($this->plModel->hasDeactivate) {
         $where = ' WHERE dt_deactivated IS NULL ';
      }


      $recordset = $this->plModel->selectForPL($where);

      $recordsetJson = json_encode($recordset);
      if ($retasReturn) {
         return $recordsetJson;
      } else {
         echo ($recordsetJson);
      }
   }

   public function index() {

      echo ('');
   }

}

?>