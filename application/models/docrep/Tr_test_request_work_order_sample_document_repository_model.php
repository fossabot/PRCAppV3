<?php
include_once APPPATH."models/modelBasicExtend.php";

class tr_test_request_work_order_sample_document_repository_model extends modelBasicExtend{
    

    
    function __construct()
    {
    
     $this->table = "TR_TEST_REQUEST_WORK_ORDER_SAMPLE_DOCUMENT_REPOSITORY";

     $this->pk_field = "cd_tr_test_request_work_order_sample_document_repository";
     $this->ds_field = "ds_tr_test_request_work_order_sample";
     $this->prodCatUnique = 'N';

     $this->sequence_obj = '"TR_TEST_REQUEST_WORK_ORDER_SA_cd_tr_test_request_work_order_seq"';
    
     $this->controller = 'docrep/tr_test_request_work_order_sample_document_repository';


     $this->fieldsforGrid = array(


' "TR_TEST_REQUEST_WORK_ORDER_SAMPLE_DOCUMENT_REPOSITORY".cd_tr_test_request_work_order_sample_document_repository', 
' "TR_TEST_REQUEST_WORK_ORDER_SAMPLE_DOCUMENT_REPOSITORY".cd_tr_test_request_work_order_sample', 
 '( select ds_remarks FROM "TR_TEST_REQUEST_WORK_ORDER_SAMPLE" WHERE cd_tr_test_request_work_order_sample =  "TR_TEST_REQUEST_WORK_ORDER_SAMPLE_DOCUMENT_REPOSITORY".cd_tr_test_request_work_order_sample) as ds_tr_test_request_work_order_sample', 
' "TR_TEST_REQUEST_WORK_ORDER_SAMPLE_DOCUMENT_REPOSITORY".cd_document_repository', 
 '( select ds_document_repository FROM "DOCUMENT_REPOSITORY" WHERE cd_document_repository =  "TR_TEST_REQUEST_WORK_ORDER_SAMPLE_DOCUMENT_REPOSITORY".cd_document_repository) as ds_document_repository', 
' "TR_TEST_REQUEST_WORK_ORDER_SAMPLE_DOCUMENT_REPOSITORY".dt_record', 
' "TR_TEST_REQUEST_WORK_ORDER_SAMPLE_DOCUMENT_REPOSITORY".cd_project_model_document_repository_type', 
 '( select ds_project_model_document_repository_type FROM "PROJECT_MODEL_DOCUMENT_REPOSITORY_TYPE" WHERE cd_project_model_document_repository_type =  "TR_TEST_REQUEST_WORK_ORDER_SAMPLE_DOCUMENT_REPOSITORY".cd_project_model_document_repository_type) as ds_project_model_document_repository_type', 
' "TR_TEST_REQUEST_WORK_ORDER_SAMPLE_DOCUMENT_REPOSITORY".fl_main' );
      $this->fieldsUpd = array ( "cd_tr_test_request_work_order_sample_document_repository", "cd_tr_test_request_work_order_sample", "cd_document_repository", "dt_record", "cd_project_model_document_repository_type", "fl_main",  ); 
 
        
                $this->retrOptions = array ("fieldrecid" => $this->pk_field,
                        "stylecond"  => "(CASE WHEN \"TR_TEST_REQUEST_WORK_ORDER_SAMPLE_DOCUMENT_REPOSITORY\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
                        "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
                        "json" => true
                       ); 
                       

          parent::__construct();
    

    }
    }