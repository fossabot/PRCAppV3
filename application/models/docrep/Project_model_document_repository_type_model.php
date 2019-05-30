<?php

include_once APPPATH . "models/modelBasicExtend.php";

class project_model_document_repository_type_model extends modelBasicExtend {

    function __construct() {

        $this->table = "PROJECT_MODEL_DOCUMENT_REPOSITORY_TYPE";

        $this->pk_field = "cd_project_model_document_repository_type";
        $this->ds_field = "ds_project_model_document_repository_type";
        $this->prodCatUnique = 'N';

        $this->sequence_obj = '"PROJECT_MODEL_DOCUMENT_REPOSI_cd_project_model_document_re_seq1"';

        $this->controller = 'docrep/project_model_document_repository_type';


        $this->fieldsforGrid = array(
            ' "PROJECT_MODEL_DOCUMENT_REPOSITORY_TYPE".cd_project_model_document_repository_type',
            ' "PROJECT_MODEL_DOCUMENT_REPOSITORY_TYPE".ds_project_model_document_repository_type',
            ' "PROJECT_MODEL_DOCUMENT_REPOSITORY_TYPE".fl_default',
            ' "PROJECT_MODEL_DOCUMENT_REPOSITORY_TYPE".dt_deactivated',
            ' "PROJECT_MODEL_DOCUMENT_REPOSITORY_TYPE".dt_record');
        
        $this->fieldsUpd = array("cd_project_model_document_repository_type", "ds_project_model_document_repository_type", "fl_default", "dt_deactivated", "dt_record");


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "stylecond" => "(CASE WHEN \"PROJECT_MODEL_DOCUMENT_REPOSITORY_TYPE\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );


        parent::__construct();
    }

}
