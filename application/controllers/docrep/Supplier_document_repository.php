<?php

if (!defined('BASEPATH'))
   exit('No direct script access allowed');

include_once APPPATH . 'controllers/controllerBasicExtend.php';

class supplier_document_repository extends controllerBasicExtend {

   var $arrayIns;
   var $fields;

   function __construct() {
      parent::__construct();
      $this->load->model('docrep/document_repository_model', 'mainmodel', TRUE);
      $this->load->model('docrep/document_repository_type_model', 'doc_type', TRUE);
      $this->load->library('cdocrep');
   }

   public function index() {
      
   }
   
   public function openRepository($supplier) {

      //parent::checkMenuPermission();

      
      // busco as extensoes:
      $extensions = $this->doc_type->getDataByUser();

      $label = array ( 'errortitle' => 'You must choose Title before upload',
                       'errortype'  => 'YYou must choose Type Document before upload',
                       'menuEditTitle' => 'Edit',
                       'menuDelete'    => 'Delete',
                       'menuDownload'  => 'Download',
                       'documentType'  => 'Document Type'
                      );
      
      $label = $this->cdbhelper->retTranslationDifKeys($label);
      $extensions_browser = $this->doc_type->getAvailableExtensionsBrowser();
      
      
      $grid = $this->w2gridgen;
      $f = $this->cfields;
      $fm = $this->cfiltermaker;

      if (1 == 2) {
         $f = new Cfields();
         $grid = new w2gridgen();
         $fm = new cfiltermaker();
      }

      $fm = $this->cfiltermaker;
      $grid->setGridName('DocRepInsGrid');
      
      $grid->addUserBtnToolbar('upload', 'Upload', 'fa fa-upload');
      $grid->addUpdToolbar();
      $grid->addUserBtnToolbar('close', 'Close', 'fa fa-times');
      $grid->setGridToolbarFunction('toolbarDocRep');
      
      $grid->setGridVar('docRepInsGridVar');
      
      $grid->addColumnKey();
      $grid->addColumn('ds_filename', 'File', '100px', $f->retTypeStringAny());
      $grid->addColumn('ds_document_repository', 'Title', '70%', $f->retTypeStringAny(), true );
      //$grid->addColumn('ds_document_repository_type', 'Type Document', '30%', $f->retTypePickList());
      
      $send['javaGrid'] = $grid->retGridVar();
      $send['extensions'] = json_encode($extensions);
      $send['extensions_browser'] = $extensions_browser;
      $send['supplier'] = $supplier;
      $this->load->view("docrep/supplier_document_repository_view", $send + $label);

   }

   
   public function sendFiles($cd_supplier) {
      
      // crio array que a biblioteca de docrep vai entender!!!
      
      $return = array();
      $gridInfo = json_decode($_POST['gridInfo'] , true);
      $files    = $_FILES;

      $array_send = array();
      foreach ($gridInfo as $value_grid) {
         
         foreach ($files as $value_files) {
            if ($value_files['name'] == $value_grid['ds_filename']) {
               
               $line = array('filename'                        => $value_grid['ds_filename'],
                             'tmp_filename'                    => $value_files['tmp_name'],
                             'cd_document_repository_category' => $value_grid['cd_document_repository_category'],
                             'ds_document_repository'          => $value_grid['ds_document_repository'],
                             'cd_document_repository_type'     => $value_grid['cd_document_repository_type']

                  );
               
               array_push($array_send, $line);
               break;
            }
            
         }
         
      }
          
      
      $retorno = $this->cdocrep->addToDocumentRepository($array_send, 'SUPPLIER_DOCUMENT_REPOSITORY', 'cd_supplier', $cd_supplier);
            
      if (!$retorno) {
         $return['ok'] = false;
         $return['message'] = $this->cdocrep->error;
         echo (json_encode($return));
         return;
      }
      $return['ok'] = true;
      $return['cells'] = $this->makeCellbyCodes($retorno);
      echo (json_encode($return));
      
   }
   

   function makeCellbyCodes($codes) {

      // normalizo para array
      if (!is_array($codes)) {
         $torun = array($codes);
      } else {
         $torun = $codes;
      }
      
      $where = ' AND EXISTS ( SELECT 1 '
         . '                    FROM "SUPPLIER_DOCUMENT_REPOSITORY" s'
         . '                   WHERE s.cd_document_repository = r.cd_document_repository'
         . '                     AND s.cd_supplier_document_repository in ( '.implode(',', $torun).' )'
         . ' )  ';

      $orderby = 'order by r.dt_record desc ';
      
      $docs = $this->mainmodel->retSQLArray($where, $orderby);
      
      $cell = '';
      foreach ($docs as $value) {
         $cell = $cell .' ' . $this->drawCell($value);
      }

      return $cell;
   }

   function drawCell($line) {
      $filename = $line['ds_document_file_thumbs_path'].$line['ds_document_file_hash'].'.png';

      if (!file_exists($filename)) {
         $src = "#";
      } else {
         $image = base64_encode(file_get_contents($filename));

         // Format the image SRC:  data:{mime};base64,{data};
         $src = 'data: '.mime_content_type($filename).';base64,'.$image;
      
      }
      $code = $line['cd_document_repository'];

      $cell = "<div id='docRep".$code."' fln = '".$line['ds_original_file']."' class='docrep_cell' code='".$code."' cdt='".$line['cd_document_repository_type']."' mainCell='Y'>          
         <div class='docrep_cell_text' needStart='Y' id='docrep_cell_text".$code."'><i class='fa fa-caret-down' id='docRepMenu".$code."'></i> <span id='docRepTextSpan".$code."'  > ".$line['ds_document_repository']."</span></div>
         <div class='docrep_cell_checkbox'><input type='checkbox' id='docRepChk".$code."' class='docrepCheckbox' needStart='Y'></div>
         <div class='docrep_cell_image'><img src='" .$src."'></div> 
         <div class='docrep_cell_icon'><i class='".$line['ds_icon']."'> </i></div>
      </div>";
      
      return $cell;
      
   }
   
   public function retrieveBySupplier($cd_supplier) {
      
      $where = ' AND EXISTS ( SELECT 1 '
         . '                    FROM "SUPPLIER_DOCUMENT_REPOSITORY" s'
         . '                   WHERE s.cd_document_repository = r.cd_document_repository'
         . '                     AND s.cd_supplier  = '.$cd_supplier
         . ' )  ';

      $orderby = 'order by r.dt_record desc ';

      
      $docs = $this->mainmodel->retSqlArrayByUser($where, $orderby);

      $cell = '';
      foreach ($docs as $value) {
         $cell = $cell .' ' . $this->drawCell($value);
      }

      echo ($cell);
      
   }
   
   
public function updateData() {
   
   $data = json_decode($_POST['info'], true);
   $retorno = $this->cdocrep->updateRepositoryData($data);
   
   if (!$retorno) {
      $return['ok'] = false;
      $return['message'] = $this->cdocrep->error;
      echo (json_encode($return));
      return;
   } else {
      $return['ok'] = true;
      echo (json_encode($return));
      return;
   }  
}
   
   
   
   
public function deleteFromRepository ($cd_supplier, $cd_document_repository) {
   
   $retorno = $this->cdocrep->delFromDocumentRepository($cd_document_repository, 'SUPPLIER_DOCUMENT_REPOSITORY', 'cd_supplier', $cd_supplier);
   
   if ($retorno == 'OK') {
      $return['ok'] = true;
   } else {
      $return['ok'] = false;
      $return['message'] = $retorno;
   }
   echo (json_encode($return));
   
}



public function downloadImages($cd_document_repository) {
   $codes = $cd_document_repository;
   $cd_document_repository = explode('x', $cd_document_repository);
   $cd_document_repository = implode(',', $cd_document_repository);
   
   
   
   $docs = $this->mainmodel->retSQLArray( 'AND r.cd_document_repository in ( '. $cd_document_repository .')');
   if (count($docs) == 1) {
      $line = $docs[0];
      $filename = $line['ds_document_file_path'].$line['ds_document_file_hash'].'.'.$line['ds_file_extension'];
      $fp = @fopen($filename, 'r');

      header("Content-Type: application/".$line['ds_file_extension']);
      header("Content-Disposition: attachment; filename=\"".$line['ds_original_file']."\"");
      header("Content-Length: ".  filesize($filename));
      fpassthru($fp);
      fclose($fp);      
   }
   $ds_human_resource = $this->session->userdata('ds_human_resource');

   $zip = new ZipArchive();
   $filename = '/tmp/'.$codes.'-'.$ds_human_resource.'.zip';
   $zip->open($filename, ZipArchive::CREATE)  ;  
   foreach ($docs as $line) {
      $filenameToAdd = $line['ds_document_file_path'].$line['ds_document_file_hash'].'.'.$line['ds_file_extension'];
      $zip->addFile($filenameToAdd, $line['ds_original_file']);
   }
   $zip->close();
   
   
   $fp = @fopen($filename, 'r');
   header("Content-Type: application/zip");
   header("Content-Disposition: attachment; filename=\"download.zip\"");
   header("Content-Length: ".  filesize($filename));
   fpassthru($fp);
   fclose($fp);    
   
   unlink($filename);
   
   //@zip_open($filename)
   
   
   }

}

?>