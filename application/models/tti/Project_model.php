<?php

include_once APPPATH . "models/modelBasicExtend.php";

class project_model extends modelBasicExtend {

    function __construct() {

        $this->table = "PROJECT";

        $this->pk_field = '"PROJECT".cd_project';
        $this->ds_field = "ds_project";
        $this->prodCatUnique = 'N';
        $this->hasDeactivate = false;
        $this->orderByDefault = ' ORDER BY ds_project, ds_project_model ';

        $this->sequence_obj = '"PROJECT_cd_project_seq"';

        $this->controller = 'tii/project';

        $canSeeAll = $this->getCdbhelper()->getUserPermission('fl_see_all_projects');

        $hmcode = $this->session->userdata('cd_human_resource');

        
        
        $forcedWhere = '';
        if ($canSeeAll == 'N') {
            $forcedWhere = " AND ( fl_confidential = 'N' OR EXISTS ( SELECT 1 FROM \"PROJECT_USER_ROLES\" x WHERE x.cd_project_model = \"PROJECT_MODEL\".cd_project_model AND x.cd_human_resource = $hmcode AND fl_active = 'Y') )";
        }


        $this->fieldsforGrid = array(
            ' "PROJECT".cd_project',
            ' "PROJECT".ds_project',
            ' "PROJECT".cd_human_resource_prc_pm',
            '( select ds_human_resource_full FROM "HUMAN_RESOURCE" WHERE cd_human_resource =  "PROJECT".cd_human_resource_prc_pm) as ds_human_resource_prc_pm',
            ' "PROJECT".cd_human_resource_eng',
            '( select ds_human_resource_full FROM "HUMAN_RESOURCE" WHERE cd_human_resource =  "PROJECT".cd_human_resource_eng) as ds_human_resource_eng',
            ' "PROJECT".ds_tti_project',
            ' "PROJECT".fl_draft',
            ' "PROJECT".ds_met_project',
            ' "PROJECT".cd_project_tool_type',
            '( select ds_project_tool_type FROM "PROJECT_TOOL_TYPE" WHERE cd_project_tool_type =  "PROJECT".cd_project_tool_type) as ds_project_tool_type',
            ' "PROJECT".cd_department',
            '( select ds_department FROM "DEPARTMENT" WHERE cd_department =  "PROJECT".cd_department) as ds_department',
            ' "PROJECT_MODEL".cd_project_model',
            ' "PROJECT_MODEL".ds_project_model',
            ' "PROJECT_MODEL".ds_tti_project_model',
            ' "PROJECT_MODEL".ds_met_project_model',
            ' "PROJECT_MODEL".cd_project_voltage',
            ' "PROJECT_MODEL".cd_project_status',
            '( select ds_project_status FROM "PROJECT_STATUS" WHERE cd_project_status =  "PROJECT_MODEL".cd_project_status) as ds_project_status',
            ' "PROJECT_MODEL".dt_update',
            ' ( to_char("PROJECT_MODEL".dt_update, \'MM/DD/YYYY HH24:MI\') ) as ds_update_formatted  ',
            ' ("PROJECT_MODEL".dt_update) as ds_update_formatted_order  ',
            ' "PROJECT".cd_project_product',
            ' "PROJECT".cd_brand',
            '( select ds_brand FROM "BRAND" WHERE cd_brand =  "PROJECT".cd_brand) as ds_brand',
            '( select ds_project_product FROM "PROJECT_PRODUCT" WHERE cd_project_product =  "PROJECT".cd_project_product) as ds_project_product',
            '( select ds_project_voltage FROM "PROJECT_VOLTAGE" WHERE cd_project_voltage =  "PROJECT_MODEL".cd_project_voltage) as ds_project_voltage',
            ' "PROJECT".cd_project_power_type',
            ' "PROJECT".fl_confidential ',
            '( select ds_project_power_type FROM "PROJECT_POWER_TYPE" WHERE cd_project_power_type =  "PROJECT".cd_project_power_type) as ds_project_power_type',
            '("PROJECT_MODEL".ds_project_model || \' - \' || "PROJECT".ds_project ) as ds_project_full_desc ',
        );

        $this->fieldsUpd = array("cd_brand", "cd_project_product", "cd_project_power_type", "cd_department", "cd_project", "fl_draft", "ds_project", "cd_human_resource_prc_pm", "cd_human_resource_eng", "ds_tti_project", "ds_met_project", "cd_project_tool_type", "fl_confidential");

        $join = array(
            'JOIN "PROJECT_MODEL" ON ("PROJECT_MODEL".cd_project = "PROJECT".cd_project ) '
        );

        $this->retrOptions = array(//"fieldrecid" => 'CONCAT ("PROJECT".cd_project::text, \'-\', "PROJECT_MODEL".cd_project_model::text ) ',
            "fieldrecid" => '"PROJECT_MODEL".cd_project_model ',
            //"stylecond" => "(CASE WHEN \"PROJECT\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true,
            "join" => $join,
            "forcedWhere" => $forcedWhere
        );



        /* with TR/WO Tables */
        $this->fieldsforGridTR = array(
            ' "PROJECT".cd_project',
            ' "PROJECT".ds_project',
            ' "PROJECT".cd_human_resource_prc_pm',
            '( select ds_human_resource_full FROM "HUMAN_RESOURCE" WHERE cd_human_resource =  "PROJECT".cd_human_resource_prc_pm) as ds_human_resource_prc_pm',
            ' "PROJECT".cd_human_resource_eng',
            '( select ds_human_resource_full FROM "HUMAN_RESOURCE" WHERE cd_human_resource =  "PROJECT".cd_human_resource_eng) as ds_human_resource_eng',
            ' "PROJECT".ds_tti_project',
            ' "PROJECT".fl_draft',
            ' "PROJECT".ds_met_project',
            ' "PROJECT".cd_project_tool_type',
            '( select ds_project_tool_type FROM "PROJECT_TOOL_TYPE" WHERE cd_project_tool_type =  "PROJECT".cd_project_tool_type) as ds_project_tool_type',
            ' "PROJECT".cd_department',
            '( select ds_department FROM "DEPARTMENT" WHERE cd_department =  "PROJECT".cd_department) as ds_department',
            ' "PROJECT_MODEL".cd_project_model',
            ' "PROJECT_MODEL".ds_project_model',
            ' "PROJECT_MODEL".ds_tti_project_model',
            ' "PROJECT_MODEL".ds_met_project_model',
            ' "PROJECT_MODEL".cd_project_voltage',
            ' "PROJECT_MODEL".cd_project_status',
            '( select ds_project_status FROM "PROJECT_STATUS" WHERE cd_project_status =  "PROJECT_MODEL".cd_project_status) as ds_project_status',
            ' "PROJECT".cd_project_product',
            ' "PROJECT".cd_brand',
            ' "PROJECT".fl_confidential ',
            ' "PROJECT_MODEL".dt_update',
            ' ( to_char("PROJECT_MODEL".dt_update, \'MM/DD/YYYY HH24:MI\') ) as ds_update_formatted  ',
            '( select ds_brand FROM "BRAND" WHERE cd_brand =  "PROJECT".cd_brand) as ds_brand',
            ' ("PROJECT_MODEL".dt_update) as ds_update_formatted_order  ',
            '( select ds_project_product FROM "PROJECT_PRODUCT" WHERE cd_project_product =  "PROJECT".cd_project_product) as ds_project_product',
            '( select ds_project_voltage FROM "PROJECT_VOLTAGE" WHERE cd_project_voltage =  "PROJECT_MODEL".cd_project_voltage) as ds_project_voltage',
            ' "PROJECT".cd_project_power_type',
            '( select ds_project_power_type FROM "PROJECT_POWER_TYPE" WHERE cd_project_power_type =  "PROJECT".cd_project_power_type) as ds_project_power_type'
        );


        $join = array(
            'JOIN "PROJECT_MODEL" ON ("PROJECT_MODEL".cd_project = "PROJECT".cd_project ) ',
            'JOIN "PROJECT_BUILD_SCHEDULE" ON ("PROJECT_BUILD_SCHEDULE".cd_project = "PROJECT".cd_project AND "PROJECT_BUILD_SCHEDULE".cd_project_model = "PROJECT_MODEL".cd_project_model ) ',
            'JOIN "PROJECT_BUILD_SCHEDULE_TESTS" ON ("PROJECT_BUILD_SCHEDULE_TESTS".cd_project_build_schedule = "PROJECT_BUILD_SCHEDULE".cd_project_build_schedule ) ',
            'JOIN "PROJECT_BUILD_SCHEDULE_TESTS_WO" ON ("PROJECT_BUILD_SCHEDULE_TESTS".cd_project_build_schedule_tests = "PROJECT_BUILD_SCHEDULE_TESTS_WO".cd_project_build_schedule_tests ) ',
            'JOIN "TR_TEST_REQUEST_WORK_ORDER" ON ("TR_TEST_REQUEST_WORK_ORDER".cd_tr_test_request_work_order = "PROJECT_BUILD_SCHEDULE_TESTS_WO".cd_tr_test_request_work_order ) ',
            'JOIN "TR_TEST_REQUEST" ON ("TR_TEST_REQUEST".cd_tr_test_request = "TR_TEST_REQUEST_WORK_ORDER".cd_tr_test_request ) '
        );

        $this->retrOptionsTR = array(//"fieldrecid" => 'CONCAT ("PROJECT".cd_project::text, \'-\', "PROJECT_MODEL".cd_project_model::text ) ',
            "fieldrecid" => '"PROJECT_MODEL".cd_project_model ',
            //"stylecond" => "(CASE WHEN \"PROJECT\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true,
            "join" => $join,
            'distinct' => true,
            "forcedWhere" => $forcedWhere
        );






        $this->fieldsforGridOnlyProject = array(
            ' "PROJECT".cd_project',
            ' "PROJECT".ds_project',
            ' "PROJECT".cd_human_resource_prc_pm',
            '( select ds_human_resource_full FROM "HUMAN_RESOURCE" WHERE cd_human_resource =  "PROJECT".cd_human_resource_prc_pm) as ds_human_resource_prc_pm',
            ' "PROJECT".cd_human_resource_eng',
            '( select ds_human_resource_full FROM "HUMAN_RESOURCE" WHERE cd_human_resource =  "PROJECT".cd_human_resource_eng) as ds_human_resource_eng',
            ' "PROJECT".ds_tti_project',
            ' "PROJECT".ds_met_project',
            ' "PROJECT".cd_project_tool_type',
            ' "PROJECT".cd_brand',
            ' "PROJECT".fl_confidential ',
            '( select ds_brand FROM "BRAND" WHERE cd_brand =  "PROJECT".cd_brand) as ds_brand',
            ' "PROJECT".cd_project_product',
            '( select ds_project_product FROM "PROJECT_PRODUCT" WHERE cd_project_product =  "PROJECT".cd_project_product) as ds_project_product',
            ' "PROJECT".fl_draft',
            ' "PROJECT".cd_project_power_type',
            ' "PROJECT".cd_department',
            ' "PROJECT_MODEL".dt_update',
            ' ( to_char("PROJECT_MODEL".dt_update, \'MM/DD/YYYY HH24:MI\') ) as ds_update_formatted  ',
            ' ("PROJECT_MODEL".dt_update) as ds_update_formatted_order  ',
            '( select ds_department FROM "DEPARTMENT" WHERE cd_department =  "PROJECT".cd_department) as ds_department',
            '( select ds_project_power_type FROM "PROJECT_POWER_TYPE" WHERE cd_project_power_type =  "PROJECT".cd_project_power_type) as ds_project_power_type',
            '( select ds_project_tool_type FROM "PROJECT_TOOL_TYPE" WHERE cd_project_tool_type =  "PROJECT".cd_project_tool_type) as ds_project_tool_type');

        $join = array('JOIN "PROJECT_MODEL" ON ("PROJECT_MODEL".cd_project = "PROJECT".cd_project ) ');

        $this->retrOptionsOnlyProject = array("fieldrecid" => '"PROJECT".cd_project',
            //"stylecond" => "(CASE WHEN \"PROJECT\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGridOnlyProject),
            "json" => true,
            "forcedWhere" => $forcedWhere,
            'join' => $join,
            'distinct' => true
        );


        parent::__construct();

        $this->load->model("schedule/project_build_schedule_comments_model", "schcomments", TRUE);
        $this->load->model("tti/project_user_roles_model", "projuserroles", TRUE);
        $this->load->model("tti/project_comments_model", "projmaincomments", TRUE);
    }

    public function getProjectSheet($cd_project, $cd_model) {
        //$this->fieldsforGrid
        //

        $tstCol = $this->buildschtstmodel->retModelSQL(' WHERE "PROJECT_BUILD_SCHEDULE_TESTS".cd_project_build_schedule = a.cd_project_build_schedule', 'ORDER BY "PROJECT_BUILD_SCHEDULE_TESTS".dt_est_start');
        $buildCol = $this->buildschchkmodel->retModelSQL(' WHERE "PROJECT_BUILD_SCHEDULE_CHECKPOINTS".cd_project_build_schedule = a.cd_project_build_schedule', ' ORDER BY nr_order ');
        $commCol = $this->schcomments->retModelSQL(' WHERE "PROJECT_BUILD_SCHEDULE_COMMENTS".cd_project_build_schedule = a.cd_project_build_schedule', ' ORDER BY "PROJECT_BUILD_SCHEDULE_COMMENTS".dt_record desc ');
        $rolesCol = $this->projuserroles->retModelSQL(' WHERE "PROJECT_USER_ROLES".cd_project_model = ' . $cd_model, " ORDER BY ds_human_resource");
        $CommMainCol = $this->projmaincomments->retModelSQL(' WHERE "PROJECT_COMMENTS".cd_project_model = ' . $cd_model, ' ORDER BY "PROJECT_COMMENTS".dt_record desc');



        $schCol = "(coalesce ( ( select (json_agg( r.* ))::text from (select cd_project_build_schedule, 
           a.cd_project, 
	   COALESCE(a.cd_project_model, -1) as cd_project_model,
	   a.cd_project_build,
	   b.ds_project_build_abbreviation,
           b.ds_project_build,
           genUniqueCodeFromTwo( a.cd_project_build_schedule , $cd_model ) as nr_unique,
           ( datedbtogrid(a.dt_deactivated) ) as dt_deactivated_schedule ,
           ( SELECT ds_human_resource_full FROM \"HUMAN_RESOURCE\" where cd_human_resource = a.cd_human_resource_deactivated) as ds_human_resource_deactivated,
	   a.nr_version,
	   datedbtogrid(a.dt_est_start) as dt_est_start,
	   datedbtogrid(a.dt_est_finish) as dt_est_finish,
           to_char(a.dt_est_start, 'yyyy-mm-dd') as ds_est_start,
	   to_char(a.dt_est_finish, 'yyyy-mm-dd') as ds_est_finish,

           datedbtogrid( (SELECT min(dt_start) FROM \"PROJECT_BUILD_SCHEDULE_TESTS\" x WHERE x.cd_project_build_schedule = a.cd_project_build_schedule)) as dt_start,
	   datedbtogrid( (SELECT max(dt_finish) FROM \"PROJECT_BUILD_SCHEDULE_TESTS\" x WHERE x.cd_project_build_schedule = a.cd_project_build_schedule)) as dt_finish,

           (SELECT COUNT(distinct w.cd_tr_test_request) 
              FROM \"PROJECT_BUILD_SCHEDULE_TESTS\" x,    \"PROJECT_BUILD_SCHEDULE_TESTS_WO\" wt, \"TR_TEST_REQUEST_WORK_ORDER\" w
             WHERE x.cd_project_build_schedule        = a.cd_project_build_schedule 
               AND wt.cd_project_build_schedule_tests = x.cd_project_build_schedule_tests
               AND w.cd_tr_test_request_work_order    = wt.cd_tr_test_request_work_order ) as nr_tr_count,


	   b.nr_order,
           b.fl_by_model,
            b.fl_has_tests,
           (SELECT COUNT(1) FROM \"PROJECT_BUILD_SCHEDULE_TESTS\" x WHERE x.cd_project_build_schedule = a.cd_project_build_schedule ) as nr_test_count,
           b.fl_allow_multiples,
           b.fl_has_checkpoints,
           a.ds_comments,
           a.cd_human_resource_te,
            ( select ds_human_resource_full FROM \"HUMAN_RESOURCE\" WHERE cd_human_resource =  a.cd_human_resource_te) as ds_human_resource_te,
           (select count(1) from \"TEST_REQUEST\" tr WHERE tr.cd_project = a.cd_project AND COALESCE(tr.cd_project_model, -1) = COALESCE(a.cd_project_model, -1) AND a.cd_project_build = tr.cd_project_build) as nr_test_request_count,
           (coalesce ( ( select (json_agg( b.* ))::text from ($buildCol)  as b ) , '[]') ) as ds_chklist,
           (coalesce ( ( select (json_agg( b.* ))::text from ($tstCol)  as b ) , '[]') ) as ds_tst,
           (coalesce ( ( select (json_agg( b.* ))::text from ($commCol)  as b ) , '[]') ) as ds_comm


        from schedule.\"PROJECT_BUILD_SCHEDULE\" a,  \"PROJECT_BUILD\" b

          WHERE cd_project = $cd_project 
            AND ( cd_project_model = $cd_model OR cd_project_model IS NULL )
            AND b.cd_project_build = a.cd_project_build
            ORDER BY nr_order, nr_version
            ) as r ) , '[]') ) as ds_schedules ";




        array_push($this->fieldsforGrid, $schCol);
        array_push($this->fieldsforGrid, "(coalesce ( ( select (json_agg( b.* ))::text from ($rolesCol)  as b ) , '[]') ) as ds_roles");
        array_push($this->fieldsforGrid, "(coalesce ( ( select (json_agg( b.* ))::text from ($CommMainCol)  as b ) , '[]') ) as ds_main_comm");

        $this->retrOptions['json'] = false;
        $this->retrOptions['fields'] = $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid);


        $array = $this->retRetrieveArray(" WHERE \"PROJECT\".cd_project = $cd_project AND \"PROJECT_MODEL\".cd_project_model = $cd_model ");

        return $array;
        
    }

}
