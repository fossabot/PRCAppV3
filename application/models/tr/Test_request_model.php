<?php

include_once APPPATH . "models/modelBasicExtend.php";

class test_request_model extends modelBasicExtend {

    function __construct() {

        $this->table = "TEST_REQUEST";

        $this->pk_field = "cd_test_request";
        $this->ds_field = "ds_project";
        $this->prodCatUnique = 'N';

        $this->sequence_obj = '"PROJECT_TEST_REQUEST_cd_project_test_request_seq"';

        $this->controller = 'tr/test_request';


        $this->fieldsforGrid = array(
            ' "TEST_REQUEST".cd_test_request',
            ' "TEST_REQUEST".cd_project',
            ' "PROJECT".ds_project ',
            ' "PROJECT".ds_tti_project',
            ' "PROJECT".nr_met_project',

            ' "PROJECT_MODEL".ds_tti_project_model',
            ' "PROJECT_MODEL".ds_met_project_model',
            
            ' "TEST_REQUEST".cd_project_model',
            '( COALESCE( "PROJECT_MODEL".ds_project_model, \'N/A\' ) ) as ds_project_model',
            ' "TEST_REQUEST".cd_project_build',
            ' "PROJECT_BUILD".ds_project_build',
            ' "PROJECT_BUILD".fl_by_model',
            ' "TEST_REQUEST".cd_test_request_type',
            '( select ds_test_request_type FROM "TEST_REQUEST_TYPE" WHERE cd_test_request_type =  "TEST_REQUEST".cd_test_request_type) as ds_test_request_type',
            ' "TEST_REQUEST".cd_test_request_purpose',
            '( select ds_test_request_purpose FROM "TEST_REQUEST_PURPOSE" WHERE cd_test_request_purpose =  "TEST_REQUEST".cd_test_request_purpose) as ds_test_request_purpose',
            ' "TEST_REQUEST".cd_test_request_origin',
            '( select ds_test_request_origin FROM "TEST_REQUEST_ORIGIN" WHERE cd_test_request_origin =  "TEST_REQUEST".cd_test_request_origin) as ds_test_request_origin',
            ' "TEST_REQUEST".ds_description',
            ' "TEST_REQUEST".fl_return_sample',
            ' "TEST_REQUEST".fl_urgent',
            ' "TEST_REQUEST".cd_human_resource_request',
            '( select ds_human_resource_full FROM "HUMAN_RESOURCE" WHERE cd_human_resource =  "TEST_REQUEST".cd_human_resource_request) as ds_human_resource_request',
            ' "TEST_REQUEST".dt_approved',
            ' "TEST_REQUEST".cd_human_resource_approver',
            '( select ds_human_resource_full FROM "HUMAN_RESOURCE" WHERE cd_human_resource =  "TEST_REQUEST".cd_human_resource_approver) as ds_human_resource_approver',
            ' "TEST_REQUEST".dt_record',
            ' "TEST_REQUEST".nr_version');
        
        $this->fieldsUpd = array("cd_test_request", "nr_version",  "cd_project", "cd_project_model", "cd_project_build", "cd_test_request_type", "cd_test_request_purpose", "cd_test_request_origin", "ds_description", "fl_return_sample", "fl_urgent", "cd_human_resource_request", "dt_approved", "cd_human_resource_approver", "dt_record",);

        $join = array('JOIN "PROJECT" ON ("PROJECT".cd_project = "TEST_REQUEST".cd_project)',
         'LEFT OUTER JOIN "PROJECT_MODEL" ON ("PROJECT_MODEL".cd_project_model = "TEST_REQUEST".cd_project_model)',
           'JOIN "PROJECT_BUILD" ON ("PROJECT_BUILD".cd_project_build = "TEST_REQUEST".cd_project_build)'
            );
        
        
        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            //"stylecond" => "(CASE WHEN \"TEST_REQUEST\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true,
            'join' => $join 
        );


        parent::__construct();
    }
    
    public function getLastVersion ($cd_project, $cd_project_model, $cd_project_build) {
        $sql = "SELECT max(nr_version) as nr_version FROM \"TEST_REQUEST\" t WHERE t.cd_project = $cd_project AND COALESCE (t.cd_project_model, -1) = $cd_project_model AND cd_project_build = $cd_project_build ";

        $data = $this->getCdbhelper()->basicSQLArray($sql);
        
        $v = $data[0]['nr_version'];
        
        return $v;
        
        
        
    }

}
