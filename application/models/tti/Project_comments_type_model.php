<?php

include_once APPPATH . "models/modelBasicExtend.php";

class project_comments_type_model extends modelBasicExtend {

    function __construct() {

        $this->table = "PROJECT_COMMENTS_TYPE";

        $this->pk_field = "cd_project_comments_type";
        $this->ds_field = "ds_project_comments_type";
        $this->prodCatUnique = 'N';

        $this->sequence_obj = '"PROJECT_COMMENTS_TYPE_cd_project_comments_type_seq"';

        $this->controller = 'tti/project_comments_type';


        $this->fieldsforGrid = array(
            ' "PROJECT_COMMENTS_TYPE".cd_project_comments_type',
            ' "PROJECT_COMMENTS_TYPE".ds_project_comments_type',
            ' "PROJECT_COMMENTS_TYPE".cd_project_comments_type_group',
            '( select ds_project_comments_type_group FROM "PROJECT_COMMENTS_TYPE_GROUP" WHERE cd_project_comments_type_group =  "PROJECT_COMMENTS_TYPE".cd_project_comments_type_group) as ds_project_comments_type_group',
            ' "PROJECT_COMMENTS_TYPE".dt_deactivated',
            '(COALESCE (( \'Project Team, \' || (select array_to_string(array_agg(h.ds_human_resource_full), \', \') 
     from ( select h.ds_human_resource_full from 
             tti."PROJECT_COMMENTS_TYPE_GROUP_HUMAN_RESOURCE" x, 
              "HUMAN_RESOURCE" h 
          WHERE x.cd_project_comments_type_group = "PROJECT_COMMENTS_TYPE".cd_project_comments_type_group 
            and h.cd_human_resource = x.cd_human_resource
            order by 1) as h)), \'Project Team\')) as ds_users',
            
            ' "PROJECT_COMMENTS_TYPE".dt_record');
        $this->fieldsUpd = array("cd_project_comments_type", "ds_project_comments_type", "dt_deactivated", "dt_record", "cd_project_comments_type_group");


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "stylecond" => "(CASE WHEN \"PROJECT_COMMENTS_TYPE\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );

        $this->fieldsForPLBase = array($this->pk_field, // first always PK
            '(' . $this->ds_field . ') as description ', // second is always the description showing up. on the dropdown,
            '( \'Project Team, \'  (select array_to_string(array_agg(h.ds_human_resource_full), \', \') 
     from ( select h.ds_human_resource_full from 
             tti."PROJECT_COMMENTS_TYPE_GROUP_HUMAN_RESOURCE" x, 
              "HUMAN_RESOURCE" h 
          WHERE x.cd_project_comments_type_group = "PROJECT_COMMENTS_TYPE".cd_project_comments_type_group 
            and h.cd_human_resource = x.cd_human_resource
            order by 1) as h)) as ds_users' // adding users that will receive the e-mail
        );
        
        $this->fieldsForPLBaseDD = array($this->pk_field, // first always PK
            $this->ds_field, // second is always the description showing up. on the dropdown,
            '( \'Project Team, \' || coalesce((select array_to_string(array_agg(h.ds_human_resource_full), \', \') 
     from ( select h.ds_human_resource_full from 
             tti."PROJECT_COMMENTS_TYPE_GROUP_HUMAN_RESOURCE" x, 
              "HUMAN_RESOURCE" h 
          WHERE x.cd_project_comments_type_group = "PROJECT_COMMENTS_TYPE".cd_project_comments_type_group 
            and h.cd_human_resource = x.cd_human_resource
            order by 1) as h), \'\')) as ds_users', // adding users that will receive the e-mail
            '( \'Project Team, \' || coalesce((select array_to_string(array_agg(h.ds_human_resource_full), \', \') 
     from ( select h.ds_human_resource_full from 
             tti."PROJECT_COMMENTS_TYPE_GROUP_HUMAN_RESOURCE" x, 
              "HUMAN_RESOURCE" h 
          WHERE x.cd_project_comments_type_group = "PROJECT_COMMENTS_TYPE".cd_project_comments_type_group 
            and h.cd_human_resource = x.cd_human_resource
            order by 1) as h), \'\')) as ds_second_row' // adding users that will receive the e-mail
        );



        parent::__construct();
    }

}
