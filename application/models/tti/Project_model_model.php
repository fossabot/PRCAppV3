<?php

include_once APPPATH . "models/modelBasicExtend.php";

class project_model_model extends modelBasicExtend {

    function __construct() {

        $this->table = "PROJECT_MODEL";

        $this->pk_field = "cd_project_model";
        $this->ds_field = "ds_project_model";
        $this->prodCatUnique = 'N';
        $this->hasDeactivate = false;

        $this->sequence_obj = '"PROJECT_MODEL_cd_project_model_seq"';

        $this->controller = 'tti/project_model';

        $canSeeAll = $this->getCdbhelper()->getUserPermission('fl_see_all_projects');
        $hmcode = $this->session->userdata('cd_human_resource');

        $forcedWhere = '';
        if ($canSeeAll == 'N') {
            $forcedWhere = " AND ( fl_confidential = 'N' OR EXISTS ( SELECT 1 FROM \"PROJECT_USER_ROLES\" x WHERE x.cd_project_model = \"PROJECT_MODEL\".cd_project_model AND x.cd_human_resource = $hmcode AND fl_active = 'Y') )";
        }

        $this->fieldsforGrid = array(
            ' "PROJECT_MODEL".cd_project_model',
            ' "PROJECT_MODEL".ds_project_model',
            '("PROJECT_MODEL".ds_project_model || \' - \' || "PROJECT".ds_project ) as ds_project_full_desc ',
            " ( COALESCE(\"PROJECT\".ds_met_project, \"PROJECT\".ds_tti_project, 'Missing Project #')  || ' / ' || COALESCE(\"PROJECT_MODEL\".ds_met_project_model, \"PROJECT_MODEL\".ds_tti_project_model, 'Missing Project Model#')) as ds_project_number ",
            ' "PROJECT_MODEL".cd_project',
            ' "PROJECT_MODEL".dt_record',
            ' "PROJECT_MODEL".dt_update',
            ' "PROJECT_MODEL".ds_tti_project_model',
            ' "PROJECT_MODEL".ds_met_project_model',
            ' "PROJECT_MODEL".cd_project_status',
            '( select ds_project_status FROM "PROJECT_STATUS" WHERE cd_project_status =  "PROJECT_MODEL".cd_project_status) as ds_project_status',
            ' "PROJECT".ds_project',
            ' "PROJECT".ds_tti_project',
            ' "PROJECT".ds_met_project',
            ' "PROJECT_MODEL".cd_project_voltage',
            '( select ds_project_voltage FROM "PROJECT_VOLTAGE" WHERE cd_project_voltage =  "PROJECT_MODEL".cd_project_voltage) as ds_project_voltage');

        $this->fieldsUpd = array("cd_project_model", "ds_project_model", "cd_project", "dt_record", "nr_tti_project_model", "nr_met_project_model", "ds_tti_project_model", "ds_met_project_model", "cd_project_voltage", "dt_update", "cd_project_status");



        $join = array(' JOIN "PROJECT" ON ("PROJECT".cd_project = "PROJECT_MODEL".cd_project )');

        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            //"stylecond" => "(CASE WHEN \"PROJECT_MODEL\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true,
            "join" => $join,
            'forcedWhere' => $forcedWhere
        );

        $this->fieldsforPLPR = array(
            ' "PROJECT_MODEL".cd_project_model',
            ' "PROJECT_MODEL".ds_project_model',
            '("PROJECT_MODEL".ds_project_model || \' - \' || "PROJECT".ds_project ) as ds_project_full_desc ',
            " ( COALESCE(\"PROJECT\".ds_met_project, \"PROJECT\".ds_tti_project, 'Missing Project #')  || ' / ' || COALESCE(\"PROJECT_MODEL\".ds_met_project_model, \"PROJECT_MODEL\".ds_tti_project_model, 'Missing Project Model#')) as ds_project_number ",
            ' "PROJECT_MODEL".cd_project',
            ' "PROJECT_MODEL".dt_record',
            ' "PROJECT_MODEL".dt_update',
            ' ( COALESCE("PROJECT_MODEL".ds_tti_project_model , "PROJECT_MODEL".ds_met_project_model) ) as ds_tti_project_model ',
            ' "PROJECT_MODEL".cd_project_status',
            '( select ds_project_status FROM "PROJECT_STATUS" WHERE cd_project_status =  "PROJECT_MODEL".cd_project_status) as ds_project_status',
            ' "PROJECT".ds_project',
            ' ( COALESCE("PROJECT".ds_tti_project , "PROJECT".ds_met_project) ) as ds_tti_project ', 
            ' "PROJECT_MODEL".cd_project_voltage',
            '( select ds_project_voltage FROM "PROJECT_VOLTAGE" WHERE cd_project_voltage =  "PROJECT_MODEL".cd_project_voltage) as ds_project_voltage');


        $this->retrOptionsPLPR = array("fieldrecid" => $this->pk_field,
            //"stylecond" => "(CASE WHEN \"PROJECT_MODEL\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforPLPR),
            "json" => true,
            "join" => $join,
            'forcedWhere' => $forcedWhere
        );

















        parent::__construct();
    }

    public function getGanntData($where) {
        //include_once APPPATH . 'libraries/sql-formatter/lib/SqlFormatter.php';


        $sql = "select ( ROW_NUMBER() OVER ( ORDER BY \"PROJECT_BUILD_SCHEDULE\".cd_project_build_schedule, 
\"PROJECT_BUILD_SCHEDULE_TESTS\".cd_project_build_schedule_tests) ) as id, 

 COALESCE(\"PROJECT\".ds_met_project, \"PROJECT\".ds_tti_project, 'Missing Project #')  || ' / ' || COALESCE(\"PROJECT_MODEL\".ds_met_project_model, \"PROJECT_MODEL\".ds_tti_project_model, 'Missing Project Model#') as ds_project_number,

( to_char (  MIN(\"PROJECT_BUILD_SCHEDULE\".dt_est_start) OVER (PARTITION BY \"PROJECT_BUILD_SCHEDULE\".cd_project_model), 'YYYY-MM-DD') || ' 00:00' ) as start_plan_prj, 
( to_char (  MAX(\"PROJECT_BUILD_SCHEDULE\".dt_est_finish) OVER (PARTITION BY \"PROJECT_BUILD_SCHEDULE\".cd_project_model), 'YYYY-MM-DD') || ' 23:59' ) as end_plan_prj,

( to_char (  MIN(\"PROJECT_BUILD_SCHEDULE_TESTS\".dt_start) OVER (PARTITION BY \"PROJECT_BUILD_SCHEDULE\".cd_project_model), 'YYYY-MM-DD') || ' 00:00' ) as start_agreed_prj, 
( to_char (  MAX(\"PROJECT_BUILD_SCHEDULE_TESTS\".dt_finish) OVER (PARTITION BY \"PROJECT_BUILD_SCHEDULE\".cd_project_model), 'YYYY-MM-DD') || ' 23:59' ) as end_agreed_prj,

( to_char (  MIN(\"PROJECT_BUILD_SCHEDULE_TESTS\".dt_actual_start) OVER (PARTITION BY \"PROJECT_BUILD_SCHEDULE\".cd_project_model), 'YYYY-MM-DD') || ' 00:00' ) as start_complete_prj, 
( to_char (  MAX(\"PROJECT_BUILD_SCHEDULE_TESTS\".dt_actual_finish) OVER (PARTITION BY \"PROJECT_BUILD_SCHEDULE\".cd_project_model), 'YYYY-MM-DD') || ' 23:59' ) as end_complete_prj,



( to_char (  MIN(\"PROJECT_BUILD_SCHEDULE\".dt_est_start) OVER (PARTITION BY \"PROJECT_BUILD_SCHEDULE\".cd_project_build_schedule), 'YYYY-MM-DD') || ' 00:00' ) as start_plan_build, 
( to_char (  MAX(\"PROJECT_BUILD_SCHEDULE\".dt_est_finish) OVER (PARTITION BY \"PROJECT_BUILD_SCHEDULE\".cd_project_build_schedule), 'YYYY-MM-DD') || ' 23:59' ) as end_plan_build,

( to_char (  MIN(\"PROJECT_BUILD_SCHEDULE_TESTS\".dt_start) OVER (PARTITION BY \"PROJECT_BUILD_SCHEDULE\".cd_project_build_schedule), 'YYYY-MM-DD') || ' 00:00' ) as start_agreed_build, 
( to_char (  MAX(\"PROJECT_BUILD_SCHEDULE_TESTS\".dt_finish) OVER (PARTITION BY \"PROJECT_BUILD_SCHEDULE\".cd_project_build_schedule), 'YYYY-MM-DD') || ' 23:59' ) as end_agreed_build,

( to_char (  MIN(\"PROJECT_BUILD_SCHEDULE_TESTS\".dt_actual_start) OVER (PARTITION BY \"PROJECT_BUILD_SCHEDULE\".cd_project_build_schedule), 'YYYY-MM-DD') || ' 00:00' ) as start_complete_build, 
( to_char (  MAX(\"PROJECT_BUILD_SCHEDULE_TESTS\".dt_actual_finish) OVER (PARTITION BY \"PROJECT_BUILD_SCHEDULE\".cd_project_build_schedule), 'YYYY-MM-DD') || ' 23:59' ) as end_complete_build,


( to_char (\"PROJECT_BUILD_SCHEDULE_TESTS\".dt_est_start, 'YYYY-MM-DD') || ' 00:00') as start_plan_tst, 
( to_char (\"PROJECT_BUILD_SCHEDULE_TESTS\".dt_est_finish, 'YYYY-MM-DD') || ' 23:59') as end_plan_tst, 

( to_char (  \"PROJECT_BUILD_SCHEDULE_TESTS\".dt_start, 'YYYY-MM-DD') || ' 00:00' ) as start_agreed_tst, 
( to_char (  \"PROJECT_BUILD_SCHEDULE_TESTS\".dt_finish, 'YYYY-MM-DD') || ' 23:59' ) as end_agreed_tst,

( to_char (  \"PROJECT_BUILD_SCHEDULE_TESTS\".dt_actual_start, 'YYYY-MM-DD') || ' 00:00' ) as start_complete_tst, 
( to_char (  \"PROJECT_BUILD_SCHEDULE_TESTS\".dt_actual_finish, 'YYYY-MM-DD') || ' 23:59' ) as end_complete_tst,
     
( \"PROJECT_MODEL\".ds_project_model || ' - ' || \"PROJECT\".ds_project) as ds_project_name, ( \"PROJECT_BUILD\".ds_project_build_abbreviation || ( CASE WHEN \"PROJECT_BUILD\".fl_allow_multiples = 'Y' THEN \"PROJECT_BUILD_SCHEDULE\".nr_version::text ELSE '' END ) ) as ds_build, 
COALESCE(\"PROJECT_BUILD_SCHEDULE_TESTS\".ds_test_item, '') as ds_items,
\"PROJECT_MODEL\".cd_project_model, \"PROJECT_BUILD_SCHEDULE\".cd_project_build_schedule, 
( array_agg('b' || \"PROJECT_BUILD_SCHEDULE\".cd_project_build_schedule::text) OVER ( PARTITION BY \"PROJECT_BUILD_SCHEDULE\".cd_project_model) ) as ds_grp_builds,
( select ds_test_type FROM \"TEST_TYPE\" WHERE cd_test_type = \"PROJECT_BUILD_SCHEDULE_TESTS\".cd_test_type) as ds_test_type,

( array_agg('p' || COALESCE(\"PROJECT_BUILD_SCHEDULE_TESTS\".cd_project_build_schedule_tests, -1)::text) OVER ( PARTITION BY COALESCE(\"PROJECT_BUILD_SCHEDULE_TESTS\".cd_human_resource_te, -1)) ) as ds_grp_tst_te,

COALESCE(\"PROJECT_BUILD_SCHEDULE\".cd_human_resource_te, -1) as cd_human_resource_te,
COALESCE(( SELECT ds_human_resource from \"HUMAN_RESOURCE\" where cd_human_resource = \"PROJECT_BUILD_SCHEDULE\".cd_human_resource_te), 'MISSING TE') as ds_human_resource_te,
\"PROJECT_BUILD_SCHEDULE_TESTS\".cd_project_build_schedule_tests,

( to_char (  MIN(\"PROJECT_BUILD_SCHEDULE_TESTS\".dt_est_start) OVER (PARTITION BY COALESCE(\"PROJECT_BUILD_SCHEDULE\".cd_human_resource_te, -1)), 'YYYY-MM-DD') || ' 00:00' ) as start_plan_te, 
( to_char (  MAX( \"PROJECT_BUILD_SCHEDULE_TESTS\".dt_est_finish) OVER (PARTITION BY COALESCE(\"PROJECT_BUILD_SCHEDULE\".cd_human_resource_te, -1)), 'YYYY-MM-DD') || ' 23:59' ) as end_plan_te,

( to_char (  MIN(\"PROJECT_BUILD_SCHEDULE_TESTS\".dt_start) OVER (PARTITION BY COALESCE(\"PROJECT_BUILD_SCHEDULE\".cd_human_resource_te, -1)), 'YYYY-MM-DD') || ' 00:00' ) as start_agreed_te, 
( to_char (  MAX(\"PROJECT_BUILD_SCHEDULE_TESTS\".dt_finish) OVER (PARTITION BY COALESCE(\"PROJECT_BUILD_SCHEDULE\".cd_human_resource_te, -1)), 'YYYY-MM-DD') || ' 23:59' ) as end_agreed_te,

( to_char (  MIN(\"PROJECT_BUILD_SCHEDULE_TESTS\".dt_actual_start) OVER (PARTITION BY COALESCE(\"PROJECT_BUILD_SCHEDULE\".cd_human_resource_te, -1)), 'YYYY-MM-DD') || ' 00:00' ) as start_complete_te, 
( to_char (  MAX(\"PROJECT_BUILD_SCHEDULE_TESTS\".dt_actual_finish) OVER (PARTITION BY COALESCE(\"PROJECT_BUILD_SCHEDULE\".cd_human_resource_te, -1)), 'YYYY-MM-DD') || ' 23:59' ) as end_complete_te,

( array_agg('b' || \"PROJECT_BUILD_SCHEDULE\".cd_project_build_schedule::text) OVER ( PARTITION BY \"PROJECT_BUILD_SCHEDULE\".cd_project_model, COALESCE(\"PROJECT_BUILD_SCHEDULE\".cd_human_resource_te, -1)) ) as ds_grp_builds_te,
( array_agg('p' || \"PROJECT_BUILD_SCHEDULE\".cd_project_model::text) OVER ( PARTITION BY COALESCE(\"PROJECT_BUILD_SCHEDULE\".cd_human_resource_te, -1)) ) as ds_grp_prj_te,



( to_char (  MIN(\"PROJECT_BUILD_SCHEDULE_TESTS\".dt_est_start) OVER (PARTITION BY \"PROJECT_BUILD_SCHEDULE\".cd_project_model, COALESCE(\"PROJECT_BUILD_SCHEDULE\".cd_human_resource_te, -1)), 'YYYY-MM-DD') || ' 00:00' ) as start_plan_prj_te, 
( to_char (  MAX(\"PROJECT_BUILD_SCHEDULE_TESTS\".dt_est_finish) OVER (PARTITION BY \"PROJECT_BUILD_SCHEDULE\".cd_project_model, COALESCE(\"PROJECT_BUILD_SCHEDULE\".cd_human_resource_te, -1)), 'YYYY-MM-DD') || ' 23:59' ) as end_plan_prj_te,

( to_char (  MIN(\"PROJECT_BUILD_SCHEDULE_TESTS\".dt_start) OVER (PARTITION BY \"PROJECT_BUILD_SCHEDULE\".cd_project_model, COALESCE(\"PROJECT_BUILD_SCHEDULE\".cd_human_resource_te, -1)), 'YYYY-MM-DD') || ' 00:00' ) as start_agreed_prj_te, 
( to_char (  MAX(\"PROJECT_BUILD_SCHEDULE_TESTS\".dt_finish) OVER (PARTITION BY \"PROJECT_BUILD_SCHEDULE\".cd_project_model, COALESCE(\"PROJECT_BUILD_SCHEDULE\".cd_human_resource_te, -1)), 'YYYY-MM-DD') || ' 23:59' ) as end_agreed_prj_te,

( to_char (  MIN(\"PROJECT_BUILD_SCHEDULE_TESTS\".dt_actual_start) OVER (PARTITION BY \"PROJECT_BUILD_SCHEDULE\".cd_project_model, COALESCE(\"PROJECT_BUILD_SCHEDULE\".cd_human_resource_te, -1)), 'YYYY-MM-DD') || ' 00:00' ) as start_complete_prj_te, 
( to_char (  MAX(\"PROJECT_BUILD_SCHEDULE_TESTS\".dt_actual_finish) OVER (PARTITION BY \"PROJECT_BUILD_SCHEDULE\".cd_project_model, COALESCE(\"PROJECT_BUILD_SCHEDULE\".cd_human_resource_te, -1)), 'YYYY-MM-DD') || ' 23:59' ) as end_complete_prj_te,

( select ds_project_status FROM \"PROJECT_STATUS\" WHERE cd_project_status =  \"PROJECT_MODEL\".cd_project_status) as ds_project_status

FROM \"PROJECT_MODEL\" 
JOIN \"PROJECT\" ON ( \"PROJECT\".cd_project = \"PROJECT_MODEL\".cd_project ) 
JOIN \"PROJECT_BUILD_SCHEDULE\" ON (\"PROJECT_BUILD_SCHEDULE\".cd_project = \"PROJECT_MODEL\".cd_project AND \"PROJECT_BUILD_SCHEDULE\".cd_project_model = \"PROJECT_MODEL\".cd_project_model ) 
JOIN \"PROJECT_BUILD\" ON ( \"PROJECT_BUILD\".cd_project_build = \"PROJECT_BUILD_SCHEDULE\".cd_project_build ) 
LEFT OUTER JOIN \"PROJECT_BUILD_SCHEDULE_TESTS\" ON (\"PROJECT_BUILD_SCHEDULE_TESTS\".cd_project_build_schedule = \"PROJECT_BUILD_SCHEDULE\".cd_project_build_schedule) 
 $where
  

  ORDER BY start_plan_prj DESC";

        //echo SqlFormatter::format($sql);
        //die;
        
        //die ($sql);

        $jsonMain = $this->getCdbhelper()->basicSQLJson($sql, true);



        return $jsonMain;
    }

    public function retInsJson() {
        $this->load->model('tti/project_status_model', 'prjstatus');
        $status = $this->prjstatus->retRetrieveArray("WHERE fl_default = 'Y' ");

        $ret = array('recid' => $this->getNextCode(), 'style' => '', 'this' => 'Y');

        if (count($status) > 0) {
            $ret['cd_project_status'] = $status[0]['recid'];
            $ret['ds_project_status'] = $status[0]['ds_project_status'];
        }

        echo (json_encode($ret, JSON_NUMERIC_CHECK));
    }

}
