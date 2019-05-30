<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

include_once APPPATH . 'controllers/controllerBasicExtend.php';

class general_document_repository extends controllerBasicExtend {

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

    public function openRepository($docrep_id, $relcode) {
        switch ($docrep_id) {
            case -1:



                break;

            default:
                $view = 'docrep/general_document_repository_view';
                break;
        }

        $send = array('code' => $relcode, 'id' => $docrep_id);

        $this->load->view($view, $send);
    }

    public function getInformation($docrep_id) {
        $tableData = $this->mainmodel->getTableInfo($docrep_id);
        //parent::checkMenuPermission();
        // busco as extensoes:
        $extensions = $this->doc_type->getDataByUser();

        $label = array('errortitle' => 'You must choose Title before upload',
            'errortype' => 'You must choose Type Document before upload',
            'menuEditTitle' => 'Edit',
            'menuDelete' => 'Delete',
            'menuDownload' => 'Download',
            'documentType' => 'File Type',
            'init' => 'Initializing UI',
            'retrieve' => 'Retrieving',
            'uploading' => 'Uploading Files',
            'doctp' => 'Document Type'
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

        $grid->setGridVar('docRepGridVar');

        $grid->addColumnKey();
        $grid->addColumn('ds_filename', 'File', '100px', $f->retTypeStringAny());
        $grid->addColumn('ds_document_repository', 'Title', '70%', $f->retTypeStringAny(), true);



        //$grid->addColumn('ds_document_repository_type', 'Type Document', '30%', $f->retTypePickList());

        $gridStr = $grid->retGridJson();

        $toolbarStr = "{
      name: 'DocRepToolbar',
      onClick: function (event) {

         if (event.target == 'delete') {
            //deleteCheckedMsg();
         }
         ;

         if (event.target == 'download') {
            //downloadImages();
         }

         if (event.target == 'edit') {
            docRepEditTitle(obj);
         }
      }
   }";

        $array_ret = array('extensionsInfo' => $extensions,
            'extensionsBrowser' => $extensions_browser,
            'labels' => $label
        );




        if (isset($tableData['tp_field'])) {
            $array_ret['tpopt'] = $tableData['tp_options'];
            $array_ret['tppk'] = $tableData['tp_field'];
            $array_ret['tpds'] = $tableData['tp_field_ds'];
        }


        $ret = json_encode($array_ret);
        $ret = substr($ret, 0, strlen($ret) - 1);
        $toolbarStr = json_encode($toolbarStr);
        $toolbarStr = json_decode($toolbarStr);


        $ret = $ret . ', toolbarInfo: ' . $toolbarStr;

        $ret = $ret . ', gridInfo: ' . $gridStr . '}';
        $ret = 'obj.setVars (' . $ret . ');';
        echo ($ret);
    }

    public function sendFiles($id, $code) {

        // crio array que a biblioteca de docrep vai entender!!!

        $tableinfo = $this->mainmodel->getTableInfo($id);


        $return = array();
        $gridInfo = json_decode($_POST['gridInfo'], true);
        $files = $_FILES;

        $array_send = array();
        foreach ($gridInfo as $value_grid) {

            foreach ($files as $value_files) {
                if ($value_files['name'] == $value_grid['ds_filename']) {

                    $line = array('filename' => $value_grid['ds_filename'],
                        'tmp_filename' => $value_files['tmp_name'],
                        'cd_document_repository_category' => $value_grid['cd_document_repository_category'],
                        'ds_document_repository' => $value_grid['ds_document_repository'],
                        'cd_document_repository_type' => $value_grid['cd_document_repository_type']
                    );

                    if (isset($tableinfo['tp_field'])) {
                        $line['addColumns'] = $tableinfo['tp_field'];
                        $line['addColumnsData'] = $value_grid['cd_type'];
                    }

                    array_push($array_send, $line);
                    break;
                }
            }
        }


        $retorno = $this->cdocrep->addToDocumentRepository($array_send, $tableinfo['table'], $tableinfo['rel_field'], $code);

        if (!$retorno) {
            $return['ok'] = false;
            $return['message'] = $this->cdocrep->error;
            echo (json_encode($return));
            return;
        }
        $return['ok'] = true;
        $return['debug'] = json_encode($retorno);
        $return['cells'] = $this->makeCellbyCodes($id, $retorno);


        echo (json_encode($return));
    }

    function makeCellbyCodes($id, $codes) {

        $tableinfo = $this->mainmodel->getTableInfo($id);

        // normalizo para array
        if (!is_array($codes)) {
            $torun = array($codes);
        } else {
            $torun = $codes;
        }

        $where = ' AND EXISTS ( SELECT 1 '
                . '                    FROM ' . $this->db->escape_identifiers($tableinfo['table']) . ' s'
                . '                   WHERE s.cd_document_repository = r.cd_document_repository'
                . '                     AND s.' . $tableinfo['pk_field'] . ' in ( ' . implode(',', $torun) . ' )'
                . ' )  ';

        $orderby = 'order by r.dt_record desc ';

        //echo ($where);

        $docs = $this->mainmodel->retSQLArray($where, $orderby, $tableinfo);



        $cell = '';
        foreach ($docs as $value) {
            $cell = $cell . ' ' . $this->drawCell($value, $tableinfo);
        }

        return $cell;
    }

    function drawCell($line, $tableInfo) {
        if (isSet($tableInfo['tp_field'])) {
            $super = "tpcode='" . $line[$tableInfo['tp_field']] . "'";
            $typeDesc = $line['tp_desc'];
        } else {
            $super = '';
            $typeDesc = '';
        }
        $code = $line['cd_document_repository'];

        $filename = $line['ds_document_file_thumbs_path'] . $line['ds_document_file_hash'] . '.png';
        if (!file_exists($filename) && $line['fl_is_image'] == 'Y') {
            $filename = $line['ds_document_file_path'] . $line['ds_document_file_hash'] . '.' . $line['ds_file_extension'];
            ;
        }

        if (!file_exists($filename)) {



            $src = "#";
            $divimg = "<div class='docrep_cell_image'><i class='" . $line['ds_icon'] . " fa-5x' style='padding-left: 35px; padding-top: 50px'></i></div>";
        } else {
            $image = base64_encode(file_get_contents($filename));

            // Format the image SRC:  data:{mime};base64,{data};
            $src = 'data: ' . mime_content_type($filename) . ';base64,' . $image;

            $divimg = "<div class='docrep_cell_image'><img src='" . $src . "'></div>"
                    . "<div class='docrep_cell_icon'><i class='" . $line['ds_icon'] . "'></i><span style='font-size: 12px;' id='typeDesc" . $code . "'> $typeDesc</span></div>";
        }



        $cell = "<div id='docRep" . $code . "' $super  relpk = '" . $line[$tableInfo['pk_field']] . "' fln = '" . $line['ds_original_file'] . "' class='docrep_cell' code='" . $code . "' cdt='" . $line['cd_document_repository_type'] . "' mainCell='Y'>          
         <div class='docrep_cell_text' needStart='Y' id='docrep_cell_text" . $code . "'><i class='fa fa-caret-down' id='docRepMenu" . $code . "'></i> <span id='docRepTextSpan" . $code . "'  > " . $line['ds_document_repository'] . "</span></div>
         <div class='docrep_cell_checkbox' style='display: block;'><input type='checkbox' id='docRepChk" . $code . "' class='docrepCheckbox' needStart='Y'></div>
         " . $divimg . "
      </div>";

        return $cell;
    }

    public function retrieveByRelation($id, $cd_code) {
        $tableinfo = $this->mainmodel->getTableInfo($id);

        $docs = $this->mainmodel->retrieveByRelation($id, $cd_code);
        $cell = '';

        foreach ($docs as $value) {
            $cell = $cell . ' ' . $this->drawCell($value, $tableinfo);
        }

        echo ($cell);
    }

    public function retrieveByRelationData($id, $cd_code) {

        $tableinfo = $this->mainmodel->getTableInfo($id);

        $where = ' AND EXISTS ( SELECT 1 '
                . '                    FROM ' . $this->db->escape_identifiers($tableinfo['table']) . ' s'
                . '                   WHERE s.cd_document_repository = r.cd_document_repository'
                . '                     AND s.' . $tableinfo['rel_field'] . '  = ' . $cd_code
                . ' )  ';


        $orderby = 'order by r.dt_record desc ';


        $docs = $this->mainmodel->retSqlArrayByUser($where, $orderby, $tableinfo);

        return $docs;
    }

    public function updateData($docrep_id = -1) {

        if ($docrep_id != -1) {
            $dtupd = $this->mainmodel->getTableInfo($docrep_id);
        } else {
            $dtupd = array();
        }

        $data = json_decode($_POST['info'], true);
        $retorno = $this->cdocrep->updateRepositoryData($data, $dtupd);

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

    public function deleteFromRepository($id, $cd_code, $cd_document_repository) {
        $tableinfo = $this->mainmodel->getTableInfo($id);

        $retorno = $this->cdocrep->delFromDocumentRepository($cd_document_repository, $tableinfo['table'], $tableinfo['rel_field'], $cd_code);

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



        $docs = $this->mainmodel->retSQLArray('AND r.cd_document_repository in ( ' . $cd_document_repository . ')');
        if (count($docs) == 1) {
            $line = $docs[0];
            $filename = $line['ds_document_file_path'] . $line['ds_document_file_hash'] . '.' . $line['ds_file_extension'];

            if (!file_exists($filename)) {
                die('File Not Found:' . $filename);
            }

            $fp = @fopen($filename, 'r');

            //header("Content-Type: application/" . $line['ds_file_extension']);
            header('Content-Type: application/download');
            header("Content-Disposition: attachment; filename=\"" . $line['ds_original_file'] . "\"");
            header("Content-Length: " . filesize($filename));
            fpassthru($fp);
            fclose($fp);
            return;
        }
        $ds_human_resource = $this->session->userdata('ds_human_resource');

        $zip = new ZipArchive();
        $filename = '/tmp/' . $codes . '-' . $ds_human_resource . '.zip';
        $zip->open($filename, ZipArchive::CREATE);
        foreach ($docs as $line) {

            $filenameToAdd = $line['ds_document_file_path'] . $line['ds_document_file_hash'] . '.' . $line['ds_file_extension'];

            if (!file_exists($filenameToAdd)) {
                $zip->close();

                die('File Not Found: ' . $filenameToAdd);
            }


            $zip->addFile($filenameToAdd, $line['ds_original_file']);
        }
        $zip->close();

        $fp = @fopen($filename, 'r');
        header("Content-Type: application/zip");
        header("Content-Disposition: attachment; filename=\"download.zip\"");
        header("Content-Length: " . filesize($filename));
        fpassthru($fp);
        fclose($fp);

        unlink($filename);

        //@zip_open($filename)
    }

        public function downloadFile($cd_document_files) {
        $codes = $cd_document_files;
        $cd_document_files = explode('x', $cd_document_files);
        $cd_document_files = implode(',', $cd_document_files);



        $docs = $this->mainmodel->retSQLArray('AND f.cd_document_file in ( ' . $cd_document_files . ')');
        if (count($docs) == 1) {
            $line = $docs[0];
            $filename = $line['ds_document_file_path'] . $line['ds_document_file_hash'] . '.' . $line['ds_file_extension'];

            if (!file_exists($filename)) {
                die('File Not Found:' . $filename);
            }

            $fp = @fopen($filename, 'r');

            //header("Content-Type: application/" . $line['ds_file_extension']);
            header('Content-Type: application/download');
            header("Content-Disposition: attachment; filename=\"" . $line['ds_original_file'] . "\"");
            header("Content-Length: " . filesize($filename));
            fpassthru($fp);
            fclose($fp);
            return;
        }
        $ds_human_resource = $this->session->userdata('ds_human_resource');

        $zip = new ZipArchive();
        $filename = '/tmp/' . $codes . '-' . $ds_human_resource . '.zip';
        $zip->open($filename, ZipArchive::CREATE);
        foreach ($docs as $line) {

            $filenameToAdd = $line['ds_document_file_path'] . $line['ds_document_file_hash'] . '.' . $line['ds_file_extension'];

            if (!file_exists($filenameToAdd)) {
                $zip->close();

                die('File Not Found: ' . $filenameToAdd);
            }


            $zip->addFile($filenameToAdd, $line['ds_original_file']);
        }
        $zip->close();

        $fp = @fopen($filename, 'r');
        header("Content-Type: application/zip");
        header("Content-Disposition: attachment; filename=\"download.zip\"");
        header("Content-Length: " . filesize($filename));
        fpassthru($fp);
        fclose($fp);

        unlink($filename);

        //@zip_open($filename)
    }
    
    
    public function openImageViewer($cd_document_repository) {
        $docs = $this->mainmodel->retSqlArrayByUser('AND r.cd_document_repository = ' . $cd_document_repository . '', '');

        if (count($docs) == 0) {
            die($this->cdbhelper->retTranslation('Sorry! You have no rights to Access this area!'));
        }

        $data = $this->cdocrep->getPictureBase64($cd_document_repository);

        $this->load->view('docrep/imageViewer', array('data' => $data));
    }

    public function getFirstPictureSrc($id, $pk) {
        $filename = $this->mainmodel->getFirstPicture($id, $pk);

        if (!$filename) {
            $resourcePath = $this->cdbhelper->getSystemParameters('FULL_RESOURCE_PATH');
            $filename = $resourcePath . 'missing-image-rect.png';
            //die ($filename);
        }
        $fsize = filesize($filename);

        //$src = 'data: ' . mime_content_type($filename) . ';base64,' . base64_encode(file_get_contents($filename));

        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
        header("Cache-Control: post-check=0, pre-check=0", false);
        //header("Pragma: no-cache"); // HTTP/1.0
        header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

        header('Content-Type: ' . mime_content_type($filename)); //<-- send mime-type header
        header("Content-length: $fsize");
        readfile($filename);
    }

    public function getFirstPictureThumbsSrc($id, $pk) {
        $filename = $this->mainmodel->getFirstPictureThumb($id, $pk);

        if (!$filename) {
            $resourcePath = $this->cdbhelper->getSystemParameters('FULL_RESOURCE_PATH');
            $filename = $resourcePath . 'missing-image-rect.png';
            //die ($filename);
        }
        $fsize = filesize($filename);


        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
        header("Cache-Control: post-check=0, pre-check=0", false);
        //header("Pragma: no-cache"); // HTTP/1.0
        header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

        header('Content-Type: ' . mime_content_type($filename)); //<-- send mime-type header
        header("Content-length: $fsize");
        readfile($filename);
    }

        public function getPictureByFile($cd_file) {
        $filename = $this->mainmodel->getPictureByFile($cd_file);

        if (!$filename) {
            $resourcePath = $this->cdbhelper->getSystemParameters('FULL_RESOURCE_PATH');
            $filename = $resourcePath . 'missing-image-rect.png';
            //die ($filename);
        }
        $fsize = filesize($filename);

        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
        header("Cache-Control: post-check=0, pre-check=0", false);
        //header("Pragma: no-cache"); // HTTP/1.0
        header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

        header('Content-Type: ' . mime_content_type($filename)); //<-- send mime-type header
        header("Content-length: $fsize");
        

        
        
        readfile($filename);
    }

        public function getPictureByFileThumb($cd_file) {
        $filename = $this->mainmodel->getPictureByFileThumb($cd_file);

        if (!$filename) {
            $resourcePath = $this->cdbhelper->getSystemParameters('FULL_RESOURCE_PATH');
            $filename = $resourcePath . 'missing-image-rect.png';
            //die ($filename);
        }
        $fsize = filesize($filename);

        
        
        header('Content-Type: ' . mime_content_type($filename)); //<-- send mime-type header
        header("Content-length: $fsize");
        header("Cache-Control: Cache-Control: public, max-age=31536000"); // HTTP/1.1
        header("Pragma: cache"); // HTTP/1.0
        header("Expires: Sat, 26 Jul 2023 05:00:00 GMT"); // Date in the past

        $this->output->set_header('Content-Type: ' . mime_content_type($filename));
        $this->output->set_header("Content-length: $fsize");
        $this->output->set_header("Cache-Control: Cache-Control: public, max-age=31536000");
        $this->output->set_header("Pragma: cache");
        
        
        readfile($filename);
    }

    
    
    public function getDocumentRepositorySrc($pk, $time) {
        $filename = $this->mainmodel->retDocumentRepository($pk);

        if (!$filename) {
            $resourcePath = $this->cdbhelper->getSystemParameters('FULL_RESOURCE_PATH');
            $filename = $resourcePath . 'missing-image-rect.png';
            //die ($filename);
        }

        $fsize = filesize($filename);

        //$src = 'data: ' . mime_content_type($filename) . ';base64,' . base64_encode(file_get_contents($filename));

        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
        header("Cache-Control: post-check=0, pre-check=0", false);
        //header("Pragma: no-cache"); // HTTP/1.0
        header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

        header('Content-Type: ' . mime_content_type($filename)); //<-- send mime-type header
        header("Content-length: $fsize");
        readfile($filename);
    }

    public function getDocumentRepositoryThumbsSrc($pk, $time) {
        $filename = $this->mainmodel->retDocumentRepositoryThumbs($pk);

        if (!$filename) {
            $resourcePath = $this->cdbhelper->getSystemParameters('FULL_RESOURCE_PATH');
            $filename = $resourcePath . 'missing-image-rect.png';
            //die ($filename);
        }

        $fsize = filesize($filename);

        //$src = 'data: ' . mime_content_type($filename) . ';base64,' . base64_encode(file_get_contents($filename));

        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
        header("Cache-Control: post-check=0, pre-check=0", false);
        //header("Pragma: no-cache"); // HTTP/1.0
        header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

        header('Content-Type: ' . mime_content_type($filename)); //<-- send mime-type header
        header("Content-length: $fsize");
        readfile($filename);
    }

    public function getFirstPictureFilename($id, $pk) {
        $filename = $this->mainmodel->getFirstPicture($id, $pk);

        if (!$filename) {
            $resourcePath = $this->cdbhelper->getSystemParameters('FULL_RESOURCE_PATH');
            $filename = $resourcePath . 'missing-image-rect.png';
            //die ($filename);
        }

        return $filename;
    }

    public function getLastPictureSrc($id, $pk) {
        $filename = $this->mainmodel->getFirstPicture($id, $pk);

        if (!$filename) {
            $resourcePath = $this->cdbhelper->getSystemParameters('FULL_RESOURCE_PATH');
            $filename = $resourcePath . 'missing-image-rect.png';
            //die ($filename);
        }
        $fsize = filesize($filename);

        //$src = 'data: ' . mime_content_type($filename) . ';base64,' . base64_encode(file_get_contents($filename));

        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
        header("Cache-Control: post-check=0, pre-check=0", false);
        //header("Pragma: no-cache"); // HTTP/1.0
        header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

        header('Content-Type: ' . mime_content_type($filename)); //<-- send mime-type header
        header("Content-length: $fsize");
        readfile($filename);
        }


        
        public function upload () {
            
        $this->load->library('uploadhandler', '', 'uphandler');
        $this->load->model('docrep/document_repository_type_model', 'filedoc');
        
        $regex = $this->filedoc->getAvailableExtensionRegEx();
        
        $this->uphandler->setOption('accept_file_types', $regex);
        $this->uphandler->initialize();
        }
  
}

?>