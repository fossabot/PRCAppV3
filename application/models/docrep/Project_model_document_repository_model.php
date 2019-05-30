<?php

include_once APPPATH . "models/modelBasicExtend.php";

class project_model_document_repository_model extends modelBasicExtend {

    function __construct() {

        $this->table = "PROJECT_MODEL_DOCUMENT_REPOSITORY";

        $this->pk_field = "cd_project_model_document_repository";
        $this->ds_field = "ds_project_model";
        $this->prodCatUnique = 'N';

        $this->sequence_obj = '"PROJECT_MODEL_DOCUMENT_REPOSI_cd_project_model_document_rep_seq"';

        $this->controller = 'docrep/project_model_document_repository';


        $this->fieldsforGrid = array(
            ' "PROJECT_MODEL_DOCUMENT_REPOSITORY".cd_project_model_document_repository',
            ' "PROJECT_MODEL_DOCUMENT_REPOSITORY".cd_project_model',
            '( select ds_project_model FROM "PROJECT_MODEL" WHERE cd_project_model =  "PROJECT_MODEL_DOCUMENT_REPOSITORY".cd_project_model) as ds_project_model',
            ' "PROJECT_MODEL_DOCUMENT_REPOSITORY".cd_document_repository',
            '( select ds_document_repository FROM "DOCUMENT_REPOSITORY" WHERE cd_document_repository =  "PROJECT_MODEL_DOCUMENT_REPOSITORY".cd_document_repository) as ds_document_repository',
            ' "PROJECT_MODEL_DOCUMENT_REPOSITORY".dt_record',
            ' "PROJECT_MODEL_DOCUMENT_REPOSITORY".cd_project_model_document_repository_type',
            ' "PROJECT_MODEL_DOCUMENT_REPOSITORY".cd_project_comments',
            '( select ds_project_model_document_repository_type FROM "PROJECT_MODEL_DOCUMENT_REPOSITORY_TYPE" WHERE cd_project_model_document_repository_type =  "PROJECT_MODEL_DOCUMENT_REPOSITORY".cd_project_model_document_repository_type) as ds_project_model_document_repository_type',
            ' "PROJECT_MODEL_DOCUMENT_REPOSITORY".fl_main');
        $this->fieldsUpd = array("cd_project_model_document_repository", "cd_project_model", "cd_document_repository", "dt_record", "cd_project_model_document_repository_type", "fl_main", 'cd_project_comments');


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "stylecond" => "(CASE WHEN \"PROJECT_MODEL_DOCUMENT_REPOSITORY\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );


        parent::__construct();
    }

}
