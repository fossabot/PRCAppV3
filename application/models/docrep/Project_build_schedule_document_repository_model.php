<?php

include_once APPPATH . "models/modelBasicExtend.php";

class project_build_schedule_document_repository_model extends modelBasicExtend {

    function __construct() {

        $this->table = "PROJECT_BUILD_SCHEDULE_DOCUMENT_REPOSITORY";

        $this->pk_field = "cd_project_build_schedule_document_repository";
        $this->ds_field = "ds_project_build_schedule";
        $this->prodCatUnique = 'N';

        $this->sequence_obj = '"PROJECT_BUILD_SCHEDULE_DOCUME_cd_project_build_schedule_doc_seq"';

        $this->controller = 'docrep/project_build_schedule_document_repository';


        $this->fieldsforGrid = array(
            ' "PROJECT_BUILD_SCHEDULE_DOCUMENT_REPOSITORY".cd_project_build_schedule_document_repository',
            ' "PROJECT_BUILD_SCHEDULE_DOCUMENT_REPOSITORY".cd_project_build_schedule',
            '( select ds_comments FROM "PROJECT_BUILD_SCHEDULE" WHERE cd_project_build_schedule =  "PROJECT_BUILD_SCHEDULE_DOCUMENT_REPOSITORY".cd_project_build_schedule) as ds_project_build_schedule',
            ' "PROJECT_BUILD_SCHEDULE_DOCUMENT_REPOSITORY".cd_document_repository',
            '( select ds_document_repository FROM "DOCUMENT_REPOSITORY" WHERE cd_document_repository =  "PROJECT_BUILD_SCHEDULE_DOCUMENT_REPOSITORY".cd_document_repository) as ds_document_repository',
            ' "PROJECT_BUILD_SCHEDULE_DOCUMENT_REPOSITORY".dt_record',
            ' "PROJECT_BUILD_SCHEDULE_DOCUMENT_REPOSITORY".cd_project_model_document_repository_type',

            ' "PROJECT_BUILD_SCHEDULE_DOCUMENT_REPOSITORY".cd_project_build_schedule_comments',
            
            
            
            '( select ds_project_model_document_repository_type FROM "PROJECT_MODEL_DOCUMENT_REPOSITORY_TYPE" WHERE cd_project_model_document_repository_type =  "PROJECT_BUILD_SCHEDULE_DOCUMENT_REPOSITORY".cd_project_model_document_repository_type) as ds_project_model_document_repository_type',
            ' "PROJECT_BUILD_SCHEDULE_DOCUMENT_REPOSITORY".fl_main');
        $this->fieldsUpd = array("cd_project_build_schedule_document_repository", "cd_project_build_schedule", "cd_document_repository", "dt_record", "cd_project_model_document_repository_type", "fl_main", 'cd_project_build_schedule_comments');


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "stylecond" => "(CASE WHEN \"PROJECT_BUILD_SCHEDULE_DOCUMENT_REPOSITORY\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );


        parent::__construct();
    }

}
