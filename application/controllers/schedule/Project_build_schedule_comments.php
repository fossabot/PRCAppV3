<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class project_build_schedule_comments extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("schedule/project_build_schedule_comments_model", "mainmodel", TRUE);
        $this->load->model("tti/project_comments_type_model", "typemodel", TRUE);
        $this->load->model('docrep/project_model_document_repository_type_model', 'typedocrep', true);
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

        $fm->addPickListFilter('Project Build Schedule', 'filter_1', 'schedule/project_build_schedule', '"PROJECT_BUILD_SCHEDULE_COMMENTS".cd_project_build_schedule');
        $fm->addPickListFilter('Human Resource', 'filter_2', 'human_resource', '"PROJECT_BUILD_SCHEDULE_COMMENTS".cd_human_resource');
        $fm->addSimpleFilterUpper('Comments', 'filter_3', '"PROJECT_BUILD_SCHEDULE_COMMENTS".ds_comments');



        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("schedule/project_build_schedule_comments");

        $grid->addColumnKey();

        $grid->addColumn('ds_project_build_schedule', 'Project Build Schedule', '150px', $f->retTypePickList(), array('model' => 'schedule/project_build_schedule_model', 'codeField' => 'cd_project_build_schedule'));
        $grid->addColumn('ds_human_resource', 'Human Resource', '150px', $f->retTypePickList(), array('model' => 'human_resource_model', 'codeField' => 'cd_human_resource'));
        $grid->addColumn('ds_comments', 'Comments', '150px', $f->retTypeStringUpper(), array('limit' => ''));
        $grid->addColumn('dt_update', 'Update', '80px', $f->retTypeDate(), true);


        $filters = $fm->retFiltersWithGroup();
        $javascript = $grid->retGrid();


        $trans = array();
        $trans = $this->cdbhelper->retTranslationDifKeys($trans);



        $send = array("javascript" => $javascript,
            "filters" => $filters,
            "filters_java" => $fm->retJavascript()) + $trans;


        $this->load->view("defaultView", $send);
    }

    public function openCommentPL($cd_project_build_schedule, $cd_project_build_schedule_comments = -1, $cd_project_build_schedule_comments_answer = -1) {

        $this->load->model('docrep/document_repository_model', 'docrep');
        
        //

        $grid = $this->w2gridgen;
        $f = $this->cfields;
        $fm = $this->cfiltermaker;

        if (1 == 2) {
            $f = new Cfields();
            $grid = new w2gridgen();
            $fm = new cfiltermaker();
        }

        //die (print_r($_POST));



        $dataAnswer = $this->mainmodel->retRetrieveArray(" WHERE cd_project_build_schedule_comments = $cd_project_build_schedule_comments_answer");



        if ($cd_project_build_schedule_comments == -1) {
            $line = $this->mainmodel->retRetrieveEmptyNewArray()[0];
            $line['cd_project_build_schedule'] = $cd_project_build_schedule;
            $line['ds_send_to'] = '';
            $line['attachChanges'] = array();
            $line['atachFull'] = array();


            $action = 'I';
        } else {

            $line = json_decode($_POST['rowdata'], true);
          
            $type = $line['cd_project_comments_type'];
            $datatype = $this->typemodel->retRetrieveArray(' WHERE cd_project_comments_type = ' . $type);
            $line['ds_send_to'] = $datatype[0]['ds_users'];

            if (!isset($line['attachChanges'])) {
                $line['attachChanges'] = array();
            }
            if (!isset($line['atachFull'])) {
                $line['atachFull'] = array();
            }

            if ($_POST['isNew'] == 'Y') {
                $action = 'E';
            } else {
                // read only
                $action = 'R';
            }
        };

        //$mddata = '{}';
        // creating toolbar;
        if ($action != 'R') {
            $grid->addUpdToolbar(); 
        }
        //$grid->addCRUDToolbar(false, false, true, , false);
        $grid->setGridVar('vGridToCmtPrj');
        $grid->setForceDestroy(false);
        $toolbar = $grid->retGridVar();


// attachment
        /**** THIS IS THE EXAMPLE OF THE ATTACHMENT */
        $tableinfo = $this->docrep->getTableInfo(8);
        $aattach = $this->docrep->retSQLArray(" AND cd_project_build_schedule_comments = $cd_project_build_schedule_comments", $orderby = 'ORDER BY dt_record', $tableinfo);
        $grid->resetGrid();
        $grid->setForceDestroy(true);
        $grid->showFooter(false);
        $grid->showToolbar(true);
        $grid->setToolbarSearch(true);
        $grid->setInsertNegative(true);
        $grid->setSingleBarControl(false);
        $grid->setGridToolbarFunction("dsFormPrCMTjObject.ToolbarBuildAttachment");
        
        $grid->addCRUDToolbar(false, $action != 'R', false, $action != 'R', false);
        $grid->addUserBtnToolbar('downloadselected', 'Download Selected', 'fa fa-download');
        
        $grid->setCRUDController('docrep/general_document_repository');
        
        //$grid->addUserBtnToolbar('expexcel', 'Export Excel', 'fa fa-file-excel-o');
        $grid->setGridName('gridAttachmentComments');
        $grid->setGridDivName('gridAttachmentComments_div');
        $grid->setGridToolbarFunction('dsFormPrCMTjObject.toolbarAttachment');
        $grid->addColumnKey();
        $grid->addColumn('cd_document_file', '', '40px', $f->retTypeDocFileToolBar() , false);
        $grid->addColumn('ds_project_model_document_repository_type', 'Type', '200px', $f->retTypePickList(), array('model' => 'docrep/project_model_document_repository_type_model', 'codeField' => 'cd_project_model_document_repository_type'));
        

        //$grid->addColumn('ds_original_file', 'Des', '100%', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_document_repository', 'Description', '100%', $f->retTypeStringAny(), true);
        $grid->addColumn('dt_record', 'Added', '80px', $f->retTypeDate(), true);
        $grid->setInsertNegative(true);
        $grid->addToolbarTitle('Attachments');
        $grid->addRecords(json_encode($aattach, JSON_NUMERIC_CHECK));
        $gridAttachment = $grid->retGrid();



        $trans = array(
            'formTrans_ds_project_comments_type' => 'Type',
            'formTrans_ds_project_human_resource_cc' => 'CC',
            'formTrans_ds_send_to_form' => 'TO',
            'formTrans_ds_comments' => "Comment",
            'formTrans_ds_comments_answer' => 'Answer To'
        );

        $trans = $this->cdbhelper->retTranslationDifKeys($trans);
        

        $this->load->view("schedule/project_build_schedule_comments_form_view", $trans + $line + array(
            'toolbar' => $toolbar,
            'action' => $action,
            'cd_project_build_schedule_comments_answer_x' => $cd_project_build_schedule_comments_answer,
            'cd_user' => $this->session->userdata('cd_human_resource'),
            'ds_user' => $this->session->userdata('ds_human_resource_full'),
            'cd_project_build_schedule' => $cd_project_build_schedule,
            'typeDoc' => $this->typedocrep->retRetrieveGridJson(' WHERE dt_deactivated IS NULL '),
            'answer' => $dataAnswer,
            'attachmentGrid' => $gridAttachment));
    }
    
    
}
