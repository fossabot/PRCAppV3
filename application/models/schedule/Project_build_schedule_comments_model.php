<?php

include_once APPPATH . "models/modelBasicExtend.php";

class project_build_schedule_comments_model extends modelBasicExtend {

    function __construct() {

        $this->table = "PROJECT_BUILD_SCHEDULE_COMMENTS";

        $this->pk_field = "cd_project_build_schedule_comments";
        $this->ds_field = "ds_project_build_schedule";
        $this->prodCatUnique = 'N';
        $this->orderByDefault = ' ORDER BY "PROJECT_BUILD_SCHEDULE_COMMENTS".dt_record DESC ';
        $this->hasDeactivate = 'N';

        $this->sequence_obj = '"PROJECT_BUILD_COMMENTS_cd_project_build_comments_seq"';

        $this->controller = 'schedule/project_build_schedule_comments';

        $this->load->model('schedule/project_build_schedule_comments_cc_model', 'ccx');

        $this->fieldsforGrid = array(
            ' "PROJECT_BUILD_SCHEDULE_COMMENTS".cd_project_build_schedule_comments',
            ' "PROJECT_BUILD_SCHEDULE_COMMENTS".cd_project_build_schedule',
            ' "PROJECT_BUILD_SCHEDULE_COMMENTS".cd_human_resource',
            ' "PROJECT_BUILD_SCHEDULE_COMMENTS".cd_project_build_schedule_comments_answer',
            
            
            $this->ccx->getJsonColumn('cc', ' WHERE "PROJECT_BUILD_SCHEDULE_COMMENTS_CC".cd_project_build_schedule_comments = "PROJECT_BUILD_SCHEDULE_COMMENTS".cd_project_build_schedule_comments', ' ORDER BY ds_human_resource'),
            
            
            '( select ds_human_resource_full FROM "HUMAN_RESOURCE" WHERE cd_human_resource =  "PROJECT_BUILD_SCHEDULE_COMMENTS".cd_human_resource) as ds_human_resource',
            ' "PROJECT_BUILD_SCHEDULE_COMMENTS".ds_comments',
             '(select coalesce(json_agg( r.*), \'[]\') 
                    from (SELECT ds_e_mail FROM tti."PROJECT_COMMENTS_TYPE_GROUP_HUMAN_RESOURCE" a, "HUMAN_RESOURCE" h
                    where a.cd_project_comments_type_group = "PROJECT_COMMENTS_TYPE".cd_project_comments_type_group
                      and a.cd_human_resource = h.cd_human_resource
                      and h.ds_e_mail IS NOT NULL) as r) as ds_emails_comments',
            ' "PROJECT_BUILD_SCHEDULE_COMMENTS".cd_project_comments_type',
            ' "PROJECT_COMMENTS_TYPE".ds_project_comments_type',
            ' "PROJECT_BUILD_SCHEDULE_COMMENTS".dt_update',
            
            '(select coalesce(json_agg( r.*), \'[]\') 
                    from (SELECT ds_e_mail FROM tti."PROJECT_COMMENTS_TYPE_GROUP_HUMAN_RESOURCE" a, "HUMAN_RESOURCE" h
                    where a.cd_project_comments_type_group = "PROJECT_COMMENTS_TYPE".cd_project_comments_type_group
                      and a.cd_human_resource = h.cd_human_resource
                      and h.ds_e_mail IS NOT NULL) as r) as ds_emails_comments',

            '(select coalesce(json_agg( r.*), \'[]\') 
                    from (
            SELECT distinct d.ds_original_file, f.ds_document_file_path || f.ds_document_file_hash || \'.\' || f.ds_file_extension as ds_path_file
                FROM docrep."PROJECT_BUILD_SCHEDULE_DOCUMENT_REPOSITORY" df,
                     docrep."DOCUMENT_REPOSITORY" d,
                     docrep."DOCUMENT_FILE" f
               WHERE df.cd_project_build_schedule_comments = "PROJECT_BUILD_SCHEDULE_COMMENTS".cd_project_build_schedule_comments
                 AND d.cd_document_repository = df.cd_document_repository
                 AND f.cd_document_file       = d.cd_document_file 
            ) as r) as ds_attachments',            
            
            
            
            ' ( to_char("PROJECT_BUILD_SCHEDULE_COMMENTS".dt_update, \'MM/DD/YYYY HH:MI\') ) as ds_update_formatted  ',
            
            ' ( "PROJECT_BUILD".ds_project_build_abbreviation || ( CASE WHEN "PROJECT_BUILD".fl_allow_multiples = \'Y\' THEN "PROJECT_BUILD_SCHEDULE".nr_version::text ELSE \'\' END ) ) as ds_project_build_full  ',
            ' "PROJECT_BUILD_SCHEDULE_COMMENTS".dt_record');

        
        $this->fieldsUpd = array("cd_project_comments_type","cd_project_build_schedule_comments", "cd_project_build_schedule", "cd_human_resource", "ds_comments", "dt_update", "dt_record", "cd_project_build_schedule_comments_answer");

        $join = array(' JOIN "PROJECT_BUILD_SCHEDULE" ON ( "PROJECT_BUILD_SCHEDULE".cd_project_build_schedule =  "PROJECT_BUILD_SCHEDULE_COMMENTS".cd_project_build_schedule )  ',
            ' JOIN "PROJECT_BUILD" ON ( "PROJECT_BUILD".cd_project_build =  "PROJECT_BUILD_SCHEDULE".cd_project_build )  ',
            'JOIN "PROJECT_COMMENTS_TYPE" ON ( "PROJECT_COMMENTS_TYPE".cd_project_comments_type =  "PROJECT_BUILD_SCHEDULE_COMMENTS".cd_project_comments_type ) ');
                
        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            //"stylecond" => "(CASE WHEN \"PROJECT_BUILD_SCHEDULE_COMMENTS\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true,
            'join' => $join
        );


        parent::__construct();
    }

}
