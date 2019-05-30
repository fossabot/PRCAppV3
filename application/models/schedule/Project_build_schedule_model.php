<?php

include_once APPPATH . "models/modelBasicExtend.php";

class project_build_schedule_model extends modelBasicExtend {

    function __construct() {

        $this->table = "PROJECT_BUILD_SCHEDULE";

        $this->pk_field = "cd_project_build_schedule";
        $this->ds_field = "ds_project";
        $this->prodCatUnique = 'N';
        $this->hasDeactivate = false;

        $this->sequence_obj = '"PROJECT_BUILD_SCHEDULE_cd_project_build_schedule_seq"';

        $this->controller = 'schedule/project_build_schedule';
        $this->orderByDefault = ' ORDER BY ds_project, ds_project_model ';

        $this->fieldsforGrid = array(
            ' "PROJECT_BUILD_SCHEDULE".cd_project_build_schedule',
            ' "PROJECT_BUILD_SCHEDULE".cd_project',
            ' "PROJECT".ds_project',
            ' "PROJECT_BUILD_SCHEDULE".cd_project_model',
            ' "PROJECT_MODEL".ds_project_model',
            ' "PROJECT_BUILD_SCHEDULE".cd_project_build',
            ' "PROJECT_BUILD".ds_project_build ',
            ' "PROJECT_BUILD".ds_project_build_abbreviation ',
            ' "PROJECT_BUILD".fl_allow_multiples ',
            ' "PROJECT_BUILD_SCHEDULE".nr_version',
            ' "PROJECT_BUILD_SCHEDULE".dt_est_start',
            ' "PROJECT_BUILD_SCHEDULE".dt_est_finish',
            ' "PROJECT_BUILD_SCHEDULE".ds_comments',
           "datedbtogrid( (SELECT min(dt_start) FROM \"PROJECT_BUILD_SCHEDULE_TESTS\" x WHERE x.cd_project_build_schedule = \"PROJECT_BUILD_SCHEDULE\".cd_project_build_schedule)) as dt_start",
	   "datedbtogrid( (SELECT max(dt_finish) FROM \"PROJECT_BUILD_SCHEDULE_TESTS\" x WHERE x.cd_project_build_schedule = \"PROJECT_BUILD_SCHEDULE\".cd_project_build_schedule)) as dt_finish",
           "(SELECT COUNT(distinct w.cd_tr_test_request) 
              FROM \"PROJECT_BUILD_SCHEDULE_TESTS\" x,    \"PROJECT_BUILD_SCHEDULE_TESTS_WO\" wt, \"TR_TEST_REQUEST_WORK_ORDER\" w
             WHERE x.cd_project_build_schedule        = \"PROJECT_BUILD_SCHEDULE\".cd_project_build_schedule 
               AND wt.cd_project_build_schedule_tests = x.cd_project_build_schedule_tests
               AND w.cd_tr_test_request_work_order    = wt.cd_tr_test_request_work_order ) as nr_tr_count",

            ' ( datedbtogrid("PROJECT_BUILD_SCHEDULE".dt_deactivated) ) as dt_deactivated_schedule',
            '( SELECT ds_human_resource_full FROM "HUMAN_RESOURCE" where cd_human_resource = "PROJECT_BUILD_SCHEDULE".cd_human_resource_deactivated) as ds_human_resource_deactivated',
            ' "PROJECT_BUILD_SCHEDULE".cd_human_resource_te',
            '( select ds_human_resource_full FROM "HUMAN_RESOURCE" WHERE cd_human_resource =  "PROJECT_BUILD_SCHEDULE".cd_human_resource_te) as ds_human_resource_te',
            '(SELECT COUNT(1) FROM "PROJECT_BUILD_SCHEDULE_TESTS" x WHERE x.cd_project_build_schedule = "PROJECT_BUILD_SCHEDULE".cd_project_build_schedule ) as nr_test_count',
            
        );
        
        
        
        
        
        $this->fieldsUpd = array("cd_human_resource_te", "cd_project_build_schedule", "cd_project", "cd_project_model", "cd_project_build", "nr_version", "dt_est_start", "dt_est_finish", "ds_comments");

        $join = array('JOIN "PROJECT" ON ( "PROJECT".cd_project = "PROJECT_BUILD_SCHEDULE".cd_project ) ',
            'JOIN "PROJECT_BUILD" ON ( "PROJECT_BUILD".cd_project_build = "PROJECT_BUILD_SCHEDULE".cd_project_build ) ',
            'LEFT OUTER JOIN "PROJECT_MODEL" ON ( "PROJECT_MODEL".cd_project_model = "PROJECT_BUILD_SCHEDULE".cd_project_model ) ');

        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            //"stylecond" => "(CASE WHEN \"PROJECT_BUILD_SCHEDULE\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true,
            'join' => $join
        );



        parent::__construct();
    }

    function getSchedules($cd_project, $cd_project_model, $union = true) {

        $sql = "select cd_project_build_schedule, 
           a.cd_project, 
	   COALESCE(a.cd_project_model, -1) as cd_project_model,
	   a.cd_project_build,
	   b.ds_project_build_abbreviation,
           b.ds_project_build,
           --genUniqueCodeFromTwo( a.cd_project_build_schedule , $cd_project_model ) as nr_unique,

	   a.nr_version,
	   datedbtogrid(a.dt_est_start) as dt_est_start,
	   datedbtogrid(a.dt_est_finish) as dt_est_finish,
           to_char(a.dt_est_start, 'yyyy-mm-dd') as ds_est_start,
	   to_char(a.dt_est_finish, 'yyyy-mm-dd') as ds_est_finish,
	   b.nr_order,
           b.fl_by_model,
            b.fl_has_tests,
           (SELECT COUNT(1) FROM \"PROJECT_BUILD_SCHEDULE_TESTS\" x WHERE x.cd_project_build_schedule = a.cd_project_build_schedule ) as nr_test_count,
           b.fl_allow_multiples,
           b.fl_has_checkpoints,
           a.ds_comments,
           datedbtogrid(a.dt_deactivated) as dt_deactivated_schedule,
           ( SELECT ds_human_resource_full FROM \"HUMAN_RESOURCE\" where cd_human_resource = a.cd_human_resource_deactivated) as ds_human_resource_deactivated,
           (select count(1) from \"TEST_REQUEST\" tr WHERE tr.cd_project = a.cd_project AND COALESCE(tr.cd_project_model, -1) = COALESCE(a.cd_project_model, -1) AND a.cd_project_build = tr.cd_project_build) as nr_test_request_count

        from schedule.\"PROJECT_BUILD_SCHEDULE\" a,  \"PROJECT_BUILD\" b

          WHERE cd_project = $cd_project 
            AND ( cd_project_model = $cd_project_model OR cd_project_model IS NULL )
            AND b.cd_project_build = a.cd_project_build ";
        if ($union) {
            $sql = $sql . "
    UNION 
    SELECT -1 as cd_project_build_schedule, 
           $cd_project as cd_project, 
               ( CASE WHEN fl_by_model = 'Y' THEN $cd_project_model ELSE -1 END)  as cd_project_model,
               cd_project_build,
               ds_project_build_abbreviation,
               ds_project_build,
               --0 as nr_unique,
               
               null as nr_version,
               '' as dt_est_start,
               '' as dt_est_finish,
                '' as ds_est_start,
                '' as ds_est_finish,
               nr_order,
               fl_by_model,
               fl_has_tests,
               0 as nr_test_count,
               fl_allow_multiples,
               fl_has_checkpoints,
               '' as ds_comments,
               '' as dt_deactivated_schedule,
               '' as ds_human_resource_deactivated,
               null as nr_test_request_count

      FROM schedule.\"PROJECT_BUILD\" 
    WHERE NOT EXISTS ( SELECT 1 
                         FROM schedule.\"PROJECT_BUILD_SCHEDULE\" a 
                        WHERE a.cd_project = $cd_project 
                          AND ( a.cd_project_model = $cd_project_model OR cd_project_model IS NULL )
                          AND a.cd_project_build = schedule.\"PROJECT_BUILD\" .cd_project_build 
                     )";
        }

        $sql = $sql . " ORDER BY nr_order, nr_version";


        return $this->getCdbhelper()->basicSQLArray($sql);
    }

}
