--drop function tti.makeGantData(PAR_dt_start date, PAR_dt_end date, PAR_where text );

CREATE OR REPLACE FUNCTION tti.makeGantData(PAR_dt_start date, PAR_dt_end date, planned char(1), agreed char(1), complete char(1), PAR_where text, PAR_qty_to_show int )
  RETURNS  table (gantt jsonb, arrayProj integer[], logged char(1)) AS
$$
DECLARE 
   vsql text;
   v_record record;
   v_record_dates record;
   vrecid bigint;
   vdt_temp timestamp;
   vdt_temp_2 timestamp;
   v_minutes_range integer;
   v_gantt       jsonb;
   v_key           text;
   v_str_json_temp text;
   v_json_temp    jsonb;
   v_temp_start date;
   v_temp_end   date;
   v_x          int;
   v_ret_array_json jsonb;
   v_json_make  jsonb;
   v_json_period  jsonb;
   v_ret_text   text;
   v_main_query text;

BEGIN


  v_ret_array_json = '{}'::jsonb;
  v_x = 0;





drop table if exists tmpgannt ;
CREATE TEMPORARY SEQUENCE IF NOT EXISTS tmpGroup;

CREATE TEMPORARY TABLE tmpgannt  (
  id                               bigint,
  ds_project_number                text,
  dt_est_start_build               timestamp with time zone,
  dt_est_finish_build              timestamp with time zone,
  dt_est_start                     timestamp without time zone,
  dt_est_finish                    timestamp without time zone,
  dt_start                         timestamp without time zone,
  dt_finish                        timestamp without time zone,
  dt_actual_start                  timestamp without time zone,
  dt_actual_finish                 timestamp without time zone,
  ds_project_name                  text,
  ds_build                         text,
  ds_items                         text,
  cd_project                       bigint,
  cd_project_model                 bigint,
  cd_project_build_schedule        bigint,
  ds_test_type                     varchar(64),
  cd_human_resource_te             integer,
  ds_human_resource_te             text,
  cd_project_build_schedule_tests  bigint,
  ds_project_status                text,
  
  start_plan_prj    text null,
  end_plan_prj    text null,

  start_agreed_prj    text null,
  end_agreed_prj    text null,

  start_complete_prj    text null,
  end_complete_prj    text null,

  start_plan_build    text null,
  end_plan_build    text null,

  start_agreed_build    text null,
  end_agreed_build    text null,

  start_complete_build    text null,
  end_complete_build    text null,

  start_plan_tst    text null,
  end_plan_tst    text null,

  start_agreed_tst    text null,
  end_agreed_tst    text null,

  start_complete_tst    text null,
  end_complete_tst    text null,

  start_plan_te    text null,
  end_plan_te    text null,

  start_agreed_te    text null,
  end_agreed_te    text null,

  start_complete_te    text null,
  end_complete_te    text null,

  ds_grp_builds    text[] null,
  ds_grp_tst_te    text[] null,
  ds_grp_builds_te   text[] null,
  ds_grp_prj_te    text[] null 
);
CREATE INDEX tmpgannt01
  ON tmpgannt   (cd_project_model, cd_project_build_schedule);

CREATE INDEX tmpgannt02
  ON tmpgannt   (cd_human_resource_te, cd_project_build_schedule_tests);

CREATE INDEX tmpgannt03
  ON tmpgannt   (cd_human_resource_te, cd_project_model);

CREATE INDEX tmpgannt04
  ON tmpgannt   (cd_project_build_schedule);

CREATE INDEX tmpgannt05
  ON tmpgannt   (cd_project_build_schedule_tests);


    v_main_query =  '
INSERT INTO tmpgannt (id, ds_project_number, 
       dt_est_start_build, 
       dt_est_finish_build, 
       dt_est_start, 
       dt_est_finish, 
       dt_start, 
       dt_finish, 
       dt_actual_start, 
       dt_actual_finish, 
       ds_project_name, 
       ds_build, 
       ds_items, 
       cd_project_model, 
       cd_project_build_schedule, 
       ds_test_type, 
       cd_human_resource_te, 
       ds_human_resource_te, 
       cd_project_build_schedule_tests, 
       ds_project_status,
       start_plan_tst, 
       end_plan_tst,
       start_agreed_tst, 
       end_agreed_tst,
       start_complete_tst,
       end_complete_tst,
       cd_project
       )
       select nextval(''tmpGroup''::regclass) as cd_tmptotal, COALESCE("PROJECT".ds_met_project, "PROJECT".ds_tti_project, ''Missing Project #'')  || '' / '' || COALESCE("PROJECT_MODEL".ds_met_project_model, "PROJECT_MODEL".ds_tti_project_model, ''Missing Project Model#'') as ds_project_number,
       "PROJECT_BUILD_SCHEDULE".dt_est_start as dt_est_start_build,
       "PROJECT_BUILD_SCHEDULE".dt_est_finish as dt_est_finish_build,
 
       "PROJECT_BUILD_SCHEDULE_TESTS".dt_est_start,
       "PROJECT_BUILD_SCHEDULE_TESTS".dt_est_finish,

       "PROJECT_BUILD_SCHEDULE_TESTS".dt_start, 
       "PROJECT_BUILD_SCHEDULE_TESTS".dt_finish,

       "PROJECT_BUILD_SCHEDULE_TESTS".dt_actual_start, 
       "PROJECT_BUILD_SCHEDULE_TESTS".dt_actual_finish,
    
       ( "PROJECT_MODEL".ds_project_model || '' - '' || "PROJECT".ds_project) as ds_project_name, 
       ( "PROJECT_BUILD".ds_project_build_abbreviation || ( CASE WHEN "PROJECT_BUILD".fl_allow_multiples = ''Y'' THEN "PROJECT_BUILD_SCHEDULE".nr_version::text ELSE '''' END ) ) as ds_build, 
       COALESCE("PROJECT_BUILD_SCHEDULE_TESTS".ds_test_item, ''MISSING PLANNING ITEM'') as ds_items,
       "PROJECT_MODEL".cd_project_model, 
       "PROJECT_BUILD_SCHEDULE".cd_project_build_schedule,
       COALESCE(( select ds_test_type FROM "TEST_TYPE" WHERE cd_test_type = "PROJECT_BUILD_SCHEDULE_TESTS".cd_test_type), ''USING BUILD DATA'') as ds_test_type,

       COALESCE("PROJECT_BUILD_SCHEDULE".cd_human_resource_te, -1) as cd_human_resource_te,
        COALESCE((SELECT ds_human_resource from "HUMAN_RESOURCE" where cd_human_resource = "PROJECT_BUILD_SCHEDULE".cd_human_resource_te), ''MISSING'') as ds_human_resource_te,
       

       "PROJECT_BUILD_SCHEDULE_TESTS".cd_project_build_schedule_tests,

       ( select ds_project_status FROM "PROJECT_STATUS" WHERE cd_project_status =  "PROJECT_MODEL".cd_project_status) as ds_project_status,

       to_char( COALESCE("PROJECT_BUILD_SCHEDULE_TESTS".dt_est_start, "PROJECT_BUILD_SCHEDULE".dt_est_start)  , ''YYYY-MM-DD'') || '' 00:00'',
       to_char( COALESCE("PROJECT_BUILD_SCHEDULE_TESTS".dt_est_finish, "PROJECT_BUILD_SCHEDULE".dt_est_finish)  , ''YYYY-MM-DD'') || '' 23:59'',
       to_char( "PROJECT_BUILD_SCHEDULE_TESTS".dt_start   , ''YYYY-MM-DD'') || '' 00:00'',
       to_char( "PROJECT_BUILD_SCHEDULE_TESTS".dt_finish  , ''YYYY-MM-DD'') || '' 23:59'',
       to_char( "PROJECT_BUILD_SCHEDULE_TESTS".dt_actual_start   , ''YYYY-MM-DD'') || '' 00:00'',
       to_char( "PROJECT_BUILD_SCHEDULE_TESTS".dt_actual_finish  , ''YYYY-MM-DD'') || '' 23:59'',
       "PROJECT_MODEL".cd_project



FROM "PROJECT_MODEL" 
JOIN "PROJECT" ON ( "PROJECT".cd_project = "PROJECT_MODEL".cd_project ) 
JOIN "PROJECT_BUILD_SCHEDULE" ON ("PROJECT_BUILD_SCHEDULE".cd_project = "PROJECT_MODEL".cd_project AND "PROJECT_BUILD_SCHEDULE".cd_project_model = "PROJECT_MODEL".cd_project_model ) 
JOIN "PROJECT_BUILD" ON ( "PROJECT_BUILD".cd_project_build = "PROJECT_BUILD_SCHEDULE".cd_project_build ) 
LEFT OUTER JOIN "PROJECT_BUILD_SCHEDULE_TESTS" ON ("PROJECT_BUILD_SCHEDULE_TESTS".cd_project_build_schedule = "PROJECT_BUILD_SCHEDULE".cd_project_build_schedule) ' ;


    IF position('TR_TEST_REQUEST' in PAR_where ) > 0 THEN
        v_main_query =  v_main_query ||  ' JOIN "PROJECT_BUILD_SCHEDULE_TESTS_WO" ON ("PROJECT_BUILD_SCHEDULE_TESTS".cd_project_build_schedule_tests = "PROJECT_BUILD_SCHEDULE_TESTS_WO".cd_project_build_schedule_tests ) ' ||
                                         ' JOIN "TR_TEST_REQUEST_WORK_ORDER" ON ("TR_TEST_REQUEST_WORK_ORDER".cd_tr_test_request_work_order = "PROJECT_BUILD_SCHEDULE_TESTS_WO".cd_tr_test_request_work_order ) ' ||
                                         ' JOIN "TR_TEST_REQUEST" ON ("TR_TEST_REQUEST".cd_tr_test_request = "TR_TEST_REQUEST_WORK_ORDER".cd_tr_test_request ) ';
    END IF;



    v_main_query =  v_main_query || PAR_where ;


    IF public.getUserPermission('fl_see_all_projects', get_var('cd_human_resource')::integer) = 'N' AND  get_var('cd_human_resource') IS NOT NULL THEN
        v_main_query = v_main_query || ' AND ( fl_confidential = ''N'' OR EXISTS ( SELECT 1 FROM "PROJECT_USER_ROLES" x WHERE x.cd_project_model = "PROJECT_MODEL".cd_project_model AND x.cd_human_resource = ' || get_var('cd_human_resource') || ' AND fl_active = ''Y'') )';
    END IF;


    

    -- execute for planning: 
    IF planned = 'Y' THEN
        vsql = v_main_query || ' AND "PROJECT_BUILD_SCHEDULE".dt_est_range && daterange('''||to_char(PAR_dt_start, 'YYYYMMDD')||'''::date, '''||to_char(PAR_dt_end, 'YYYYMMDD')||'''::date, ''[]'') AND "PROJECT_BUILD_SCHEDULE_TESTS".cd_project_build_schedule_tests IS NULL';
        EXECUTE vsql;

        vsql = v_main_query || ' AND "PROJECT_BUILD_SCHEDULE_TESTS".dt_est_range && daterange('''||to_char(PAR_dt_start, 'YYYYMMDD')||'''::date, '''||to_char(PAR_dt_end, 'YYYYMMDD')||'''::date, ''[]'')';
        EXECUTE vsql;
    END IF;

    -- agreed: 
    IF agreed = 'Y' THEN
        vsql = v_main_query || ' AND "PROJECT_BUILD_SCHEDULE_TESTS".dt_agreed_range && daterange('''||to_char(PAR_dt_start, 'YYYYMMDD')||'''::date, '''||to_char(PAR_dt_end, 'YYYYMMDD')||'''::date, ''[]'') AND NOT EXISTS ( SELECT 1 FROM tmpgannt x WHERE x.cd_project_build_schedule_tests = "PROJECT_BUILD_SCHEDULE_TESTS".cd_project_build_schedule_tests)   ';
        EXECUTE vsql;
    END IF;

    -- complete: 
    IF complete = 'Y' THEN
        vsql = v_main_query || ' AND "PROJECT_BUILD_SCHEDULE_TESTS".dt_actual_range && daterange('''||to_char(PAR_dt_start, 'YYYYMMDD')||'''::date, '''||to_char(PAR_dt_end, 'YYYYMMDD')||'''::date, ''[]'') AND NOT EXISTS ( SELECT 1 FROM tmpgannt x WHERE x.cd_project_build_schedule_tests = "PROJECT_BUILD_SCHEDULE_TESTS".cd_project_build_schedule_tests)   ';
        EXECUTE vsql;
    END IF;

    -- REMOVE

    -- update dates by model: 
    UPDATE tmpgannt as a
       SET start_plan_prj     = to_char( COALESCE(x.dt_est_start, x.dt_est_start_build)  , 'YYYY-MM-DD') || ' 00:00',
           end_plan_prj       = to_char( COALESCE(x.dt_est_finish, x.dt_est_finish_build)  , 'YYYY-MM-DD') || ' 23:59',
           start_agreed_prj   = to_char( x.dt_start   , 'YYYY-MM-DD') || ' 00:00',
           end_agreed_prj     = to_char( x.dt_finish  , 'YYYY-MM-DD') || ' 23:59',
           start_complete_prj = to_char( x.dt_actual_start   , 'YYYY-MM-DD') || ' 00:00',
           end_complete_prj   = to_char( x.dt_actual_finish  , 'YYYY-MM-DD') || ' 23:59'
    from ( select cd_project_model, 
           min(dt_est_start) as dt_est_start, 
           max(dt_est_finish) as dt_est_finish,
           min(dt_est_start_build) as dt_est_start_build, 
           max(dt_est_finish_build) as dt_est_finish_build,

           min(dt_start) as dt_start, 
           max(dt_finish) as dt_finish,

           min(dt_actual_start) as dt_actual_start, 
           max(dt_actual_finish) as dt_actual_finish
    from tmpgannt 
    group by cd_project_model) as x
    where a.cd_project_model = x.cd_project_model;

    -- update dates by build: 
    UPDATE tmpgannt as a
       SET start_plan_build     = COALESCE(to_char( COALESCE(x.dt_est_start, x.dt_est_start_build)  , 'YYYY-MM-DD') || ' 00:00', ''),
           end_plan_build       = COALESCE(to_char( COALESCE(x.dt_est_finish, x.dt_est_finish_build)  , 'YYYY-MM-DD') || ' 23:59'),
           start_agreed_build   = COALESCE(to_char( x.dt_start   , 'YYYY-MM-DD') || ' 00:00', ''),
           end_agreed_build     = COALESCE(to_char( x.dt_finish  , 'YYYY-MM-DD') || ' 23:59', ''),
           start_complete_build = COALESCE(to_char( x.dt_actual_start   , 'YYYY-MM-DD') || ' 00:00', ''),
           end_complete_build   = COALESCE(to_char( x.dt_actual_finish  , 'YYYY-MM-DD') || ' 23:59', '')
    from ( select cd_project_build_schedule, 
           min(dt_est_start) as dt_est_start, 
           max(dt_est_finish) as dt_est_finish,
           min(dt_est_start_build) as dt_est_start_build, 
           max(dt_est_finish_build) as dt_est_finish_build,

           min(dt_start) as dt_start, 
           max(dt_finish) as dt_finish,

           min(dt_actual_start) as dt_actual_start, 
           max(dt_actual_finish) as dt_actual_finish
    from tmpgannt 
    group by cd_project_build_schedule) as x
    where a.cd_project_build_schedule = x.cd_project_build_schedule;
    

 -- update dates by te: 
    UPDATE tmpgannt as a
       SET start_plan_te     = to_char( COALESCE(x.dt_est_start, x.dt_est_start_build)  , 'YYYY-MM-DD') || ' 00:00',
           end_plan_te       = to_char( COALESCE(x.dt_est_finish, x.dt_est_finish_build)  , 'YYYY-MM-DD') || ' 23:59',
           start_agreed_te   = to_char( x.dt_start   , 'YYYY-MM-DD') || ' 00:00',
           end_agreed_te     = to_char( x.dt_finish  , 'YYYY-MM-DD') || ' 23:59',
           start_complete_te = to_char( x.dt_actual_start   , 'YYYY-MM-DD') || ' 00:00',
           end_complete_te   = to_char( x.dt_actual_finish  , 'YYYY-MM-DD') || ' 23:59'
    from ( select cd_human_resource_te, 
           min(dt_est_start) as dt_est_start, 
           max(dt_est_finish) as dt_est_finish,
           min(dt_est_start_build) as dt_est_start_build, 
           max(dt_est_finish_build) as dt_est_finish_build,

           min(dt_start) as dt_start, 
           max(dt_finish) as dt_finish,

           min(dt_actual_start) as dt_actual_start, 
           max(dt_actual_finish) as dt_actual_finish
    from tmpgannt 
    group by cd_human_resource_te) as x
    where a.cd_human_resource_te = x.cd_human_resource_te;


    if PAR_qty_to_show != -1 THEN 
        delete from tmpgannt where id not in (
        SELECT id FROM tmpgannt ORDER BY dt_est_start ASC LIMIT PAR_qty_to_show );
    END IF;



/****************************************************************/
    
    drop table if exists xtmp;
    create temporary table xtmp as 
    select cd_project_model, 
           array_agg('b'||cd_project_build_schedule::text) as ds_data 
       from tmpgannt 
      group by cd_project_model;  


   UPDATE tmpgannt
       SET ds_grp_builds =  xtmp.ds_data
     FROM xtmp
    WHERE xtmp.cd_project_model = tmpgannt.cd_project_model;


/*
    UPDATE tmpgannt
       SET ds_grp_builds = (
            select array_agg('b' || x.cd_project_build_schedule::text)
              from ( select distinct cd_project_build_schedule 
                      from tmpgannt x 
                      where x.cd_project_model = tmpgannt.cd_project_model 
                   ) as x 
                  );
***************************************************************/




/****************************************************************/

    drop table if exists xtmp;
    create temporary table xtmp as 
    select cd_human_resource_te, 
           array_agg('p'||cd_project_build_schedule_tests::text) as ds_data 
       from tmpgannt where cd_project_build_schedule_tests IS NOT NULL 
      group by cd_human_resource_te;  


   UPDATE tmpgannt
       SET ds_grp_tst_te =  xtmp.ds_data
     FROM xtmp
    WHERE xtmp.cd_human_resource_te = tmpgannt.cd_human_resource_te;

/*
    UPDATE tmpgannt
       SET ds_grp_tst_te = (
            select array_agg('p' || x.cd_project_build_schedule_tests::text)
              from ( select distinct cd_project_build_schedule_tests 
                      from tmpgannt x 
                      where x.cd_human_resource_te = tmpgannt.cd_human_resource_te 
                   ) as x 
                  );


***************************************************************/






/****************************************************************/
    
    drop table if exists xtmp;
    create temporary table xtmp as 
    select cd_human_resource_te, 
           array_agg('p'||cd_project_model::text) as ds_data 
       from tmpgannt 
      group by cd_human_resource_te;  


   UPDATE tmpgannt
       SET ds_grp_prj_te =  xtmp.ds_data
     FROM xtmp
    WHERE xtmp.cd_human_resource_te = tmpgannt.cd_human_resource_te;


/*

    UPDATE tmpgannt
           SET ds_grp_prj_te = (
                select array_agg('p' || x.cd_project_model::text)
                  from ( select distinct cd_project_model 
                          from tmpgannt x 
                          where x.cd_human_resource_te = tmpgannt.cd_human_resource_te 
                       ) as x 
                      );
****************************************************************/

/****************************************************************/

    drop table if exists xtmp;
    create temporary table xtmp as 
    select cd_human_resource_te, cd_project_model ,
           array_agg('b'||cd_project_build_schedule::text) as ds_data 
       from tmpgannt 
      group by cd_human_resource_te, cd_project_model;  


   UPDATE tmpgannt
       SET ds_grp_builds_te =  xtmp.ds_data
     FROM xtmp
    WHERE xtmp.cd_human_resource_te = tmpgannt.cd_human_resource_te
     AND  xtmp.cd_project_model     = tmpgannt.cd_project_model;

/*
    UPDATE tmpgannt
           SET ds_grp_builds_te = (
                select array_agg('b' || x.cd_project_build_schedule::text)
                  from ( select distinct cd_project_build_schedule  
                          from tmpgannt x 
                          where x.cd_project_model      = tmpgannt.cd_project_model 
                            and x.cd_human_resource_te = tmpgannt.cd_human_resource_te 

                       ) as x 
                      );
****************************************************************/

    drop SEQUENCE tmpGroup;


    IF NOT exists ( select 1 from tmpgannt ) THEN 
        gantt = '{}'::jsonb;
    ELSE 

        select json_agg( row_to_json(t) )  into gantt  from (select 
      id                                    ,
      ds_project_number                     ,
      ds_project_name                       ,
      ds_build                              ,
      ds_items                              ,
      cd_project                            ,
      cd_project_model                      ,
      cd_project_build_schedule             ,
      ds_test_type                          ,
      cd_human_resource_te                  ,
      ds_human_resource_te                  ,
      cd_project_build_schedule_tests       ,
      ds_project_status                     ,
      start_plan_prj                        ,
      end_plan_prj                          ,
      start_agreed_prj                      ,
      end_agreed_prj                        ,
      start_complete_prj                    ,
      end_complete_prj                      ,
      start_plan_build                      ,
      end_plan_build                        ,
      start_agreed_build                    ,
      end_agreed_build                      ,
      start_complete_build                  ,
      end_complete_build                    ,
      start_plan_tst                        ,
      end_plan_tst                          ,
      start_agreed_tst                      ,
      end_agreed_tst                        ,
      start_complete_tst                    ,
      end_complete_tst                      ,
      start_plan_te                         ,
      end_plan_te                           ,
      start_agreed_te                       ,
      end_agreed_te                         ,
      start_complete_te                     ,
      end_complete_te                       ,
      ds_grp_builds                         ,
      ds_grp_tst_te                         ,
      ds_grp_builds_te                      ,
      ds_grp_prj_te    
     from tmpgannt) t;
        
    END IF;

    select array_agg(t.cd_project) INTO arrayProj  FROM (select distinct cd_project FROM tmpgannt ) as t;
    logged = 'Y';


    RETURN NEXT;

END


$$  LANGUAGE plpgsql;
ALTER FUNCTION tti.makeGantData(date, date, char, char, char, text, integer) SET search_path=audit, public, translation, docrep, rfq, tti, schedule, tr;

