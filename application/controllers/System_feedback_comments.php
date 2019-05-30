<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class system_feedback_comments extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("system_feedback_comments_model", "mainmodel", TRUE);
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

        $fm->addSimpleFilterUpper('Comments', 'filter_1', '"SYSTEM_FEEDBACK_COMMENTS".ds_system_feedback_comments');
        $fm->addPickListFilter('Type', 'filter_2', 'system_feedback_comments_type', '"SYSTEM_FEEDBACK_COMMENTS".cd_system_feedback_comments_type');
        $fm->addPickListFilter('By', 'filter_4', 'human_resource_controller', '"SYSTEM_FEEDBACK_COMMENTS".cd_human_resource');
        //$fm->addSimpleFilterUpper('Attachment Path', 'filter_3', '"SYSTEM_FEEDBACK_COMMENTS".ds_attachment_path');



        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar(true, true, false, false, true);
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("system_feedback_comments");
        $grid->setRowHeight(60);

        $grid->addColumnKey();


        $grid->addColumn('ds_attachment', '', '40px', $f->retTypeStringAny(), false);
        $grid->addColumn('dt_record', 'Added On', '80px', $f->retTypeDate(), false);
        $grid->addColumn('ds_human_resource', 'By', '100px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_system_feedback_comments_type', 'Type', '150px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_system_feedback_comments', 'Comments', '100%', $f->retTypeStringAny(), false);

        $grid->setColumnRenderFunc('ds_system_feedback_comments', 'dsMainObject.setRenderComment');
        $grid->setColumnRenderFunc('ds_attachment', 'dsMainObject.btnPLRender');


        // $grid->addColumn('ds_attachment_path', 'Attachment Path', '150px', $f->retTypeStringAny(), array('limit' => ''));




        $filters = $fm->retFiltersWithGroup();
        $javascript = $grid->retGrid();


        $trans = array('formTrans_cd_system_feedback_comments' => 'Code',
            'formTrans_ds_system_feedback_comments' => 'System Feedback Comments',
            'formTrans_cd_system_feedback_comments_type' => 'System Feedback Comments Type',
            'formTrans_ds_attachment_path' => 'Attachment Path',
            'formTrans_cd_human_resource' => 'Human Resource',
            'uploadMessage' => 'Upload Attachment',
            'saveMessage' => 'Send Message',
            'closeMessage' => 'Close Message',
            'formTrans_dt_record' => 'Record',
        );
        $trans = $this->cdbhelper->retTranslationDifKeys($trans);



        $send = array("javascript" => $javascript,
            "filters" => $filters,
            "filters_java" => $fm->retJavascript()) + $trans;


        $this->load->view("system_feedback_comments_view", $send);
    }

    public function updateDataJsonForm() {

        $this->load->library('sendmail');
        $mail = $this->sendmail;
        if (1 == 2) {
            $mail = new sendmail();
        }

        $mailS = $this->getCdbhelper()->getSystemParameters('LMS_FEEDBACK_RECEIVER');
        $mailArray = explode(';', $mailS);

        $path = $this->getCdbhelper()->getSettings('document_repository_path');

        $path = $path . 'sysComments';

        if (!is_dir($path . '/')) {
            mkdir($path);
        }

        chmod($path, 0777);

        $upd_array = json_decode($_POST['upd']);
        $arraysend = array($upd_array);

        $arraysend[0]->recid = $this->mainmodel->getNextCode();

        if (isset($arraysend[0]->file_name_upload)) {
            $tmp_name = sys_get_temp_dir() . '/' . $arraysend[0]->file_name_upload;
            $file_name = $arraysend[0]->file_name_upload;

            // basename() may prevent filesystem traversal attacks;
            // further validation/sanitation of the filename may be appropriate
            $k = $arraysend[0]->recid;
            $arraysend[0]->ds_attachment_path = "$path/A$k - $file_name";
            rename($tmp_name, $arraysend[0]->ds_attachment_path);
        }

        $error = $this->mainmodel->updateGridData($arraysend);
        if ($error == 'OK') {
            $retResult = $this->mainmodel->retRetrieveGridJsonForm($arraysend[0]->recid);

            //die (print_r(Result));
            $retResultArray = json_decode($retResult, true)[0];

            $mail->setSubject('New Feedback Added on LMS - ' . $retResultArray['ds_human_resource']);
            
            $msg = "User: " . $retResultArray['ds_human_resource'] . '<br><br>';
            $msg .= "Type: " . $retResultArray['ds_system_feedback_comments_type'] . '<br><br>';
            $msg .= "System: " . $this->csysteminfo->retSystemNameAndVersion() . '<br><br>';
            
            $msg .= "Browser: " . $_SERVER['HTTP_USER_AGENT'] . '<br><br>';
            $msg .= "--------- Message  ------------- <br><br>" . $retResultArray['ds_system_feedback_comments'] . '<br>';
            
            
            $mail->setMessage($msg);
            foreach ($mailArray as $key => $value) {
                $mail->addTO($value);
            }


            if ($retResultArray['ds_attachment_path'] != '' && filesize($retResultArray['ds_attachment_path']) < 20 * 1024 * 1024) {
                $mail->addAttachment($retResultArray['ds_attachment_path']);
            }
            $mail->sendMail();
        } else {
            $retResult = '{}';
        }




        $msg = '{"status":' . json_encode($error) . ', "rs":' . $retResult . '}';

        echo $msg;
    }

    public function downloadAttachment($recid) {

        $docs = $this->mainmodel->retRetrieveGridArray(' WHERE cd_system_feedback_comments = ' . $recid . ' AND ds_attachment_path IS NOT NULL');
        if (count($docs) == 0) {
            die ('NO ATTACHMENT');
        }
        
        error_reporting(E_ALL);
        $filename = $docs[0]['ds_attachment_path'];
        
        //echo($filename);
        
        //$fp = fopen($filename, 'r');
        ob_end_clean();//required here or large files will not work        
        //header("Content-Type: application/" . $line['ds_file_extension']);
        header('Content-Type: application/download');
        header("Content-Disposition: attachment; filename=\"" . basename($filename) . "\"");
        header("Content-Length: " . filesize($filename));
        //fpassthru($fp);
        //fclose($fp);

        readfile($filename);
        return;
    }

}
