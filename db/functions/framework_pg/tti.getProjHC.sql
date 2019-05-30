--drop function tti.getprojhc(PAR_dt_start date, PAR_dt_end date, PAR_where text );

CREATE OR REPLACE FUNCTION tti.getprojhc(PAR_dt_start date, PAR_dt_end date, PAR_where text )
  RETURNS  table (json_projects jsonb, json_hc_dates jsonb, json_dates jsonb) AS
$$
DECLARE 
   vsql text;
   v_record record;
   v_record_dates record;
   vrecid bigint;
   vdt_temp timestamp;
   vdt_temp_2 timestamp;
   v_minutes_range integer;
   v_json_hc       jsonb;
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


BEGIN

  v_json_hc = '{}'::jsonb;
  v_ret_array_json = '{}'::jsonb;
  v_x = 0;


   drop table if exists tmpprojhc ;

    vsql = 'CREATE TEMPORARY TABLE tmpprojhc AS   
 select s.cd_project_build_schedule,
      t.cd_project_build_schedule_tests,
      m.cd_project_model,
       p.ds_tti_project, 
       p.ds_met_project,
       m.ds_tti_project_model,
       m.ds_met_project_model,
       p.ds_project,
       m.ds_project_model,
       ( b.ds_project_build_abbreviation || ( CASE WHEN b.fl_allow_multiples = ''Y'' THEN s.nr_version::text ELSE '''' END ) ) as ds_project_build_full,
       t.dt_start::date, 
       t.dt_finish::date,
       t.nr_headcount_requested_day,
       t.nr_headcount_allocated_day,
       Coalesce(t.nr_sample_quantity, 0) as nr_sample_quantity,
       COALESCE(t.nr_priority, 0) as nr_priority,
       COALESCE((select ds_test_type from "TEST_TYPE" where cd_test_type = t.cd_test_type), '''') as ds_test_type,
       COALESCE(t.ds_test_item, '''') as  ds_test_item,
       (select ds_project_status from "PROJECT_STATUS" where cd_project_status =  m.cd_project_status) as ds_project_status,
       ( SELECT ds_human_resource_full from "HUMAN_RESOURCE" where cd_human_resource = s.cd_human_resource_te) as ds_human_resource_te,
       ( SELECT ds_human_resource_full from "HUMAN_RESOURCE" where cd_human_resource = p.cd_human_resource_prc_pm) as ds_human_resource_prc_pm,
       ( SELECT ds_human_resource_full from "HUMAN_RESOURCE" where cd_human_resource = p.cd_human_resource_eng) as ds_human_resource_eng,
       ( SELECT ds_department from "DEPARTMENT" where cd_department = p.cd_department) as ds_department,
       ( SELECT string_agg(w.nr_work_order::text,'' '') from schedule."PROJECT_BUILD_SCHEDULE_TESTS_WO" tw, tr."TR_TEST_REQUEST_WORK_ORDER" w
         where w.cd_tr_test_request_work_order = tw.cd_tr_test_request_work_order and tw.cd_project_build_schedule_tests=t.cd_project_build_schedule_tests) as ds_work_order


  from tti."PROJECT_MODEL" m,
       tti."PROJECT"       p,
       schedule."PROJECT_BUILD_SCHEDULE" s,
       schedule."PROJECT_BUILD_SCHEDULE_TESTS" t,
       schedule."PROJECT_BUILD" b
  '|| PAR_where ||' AND t.dt_agreed_range && daterange( '''|| PAR_dt_start::text || '''::date, ''' || PAR_dt_end::text || '''::date, ''[]'') 
    AND t.dt_agreed_range IS NOT NULL
    /*and t.nr_headcount_requested_day > 0 */
    and m.cd_project = p.cd_project
    and s.cd_project_build_schedule = t.cd_project_build_schedule   
    and s.cd_project_model = m.cd_project_model
    and b.cd_project_build = s.cd_project_build ' ;

    IF public.getUserPermission('fl_see_all_projects', get_var('cd_human_resource')::integer) = 'N' THEN
        vsql = vsql || ' AND ( fl_confidential = ''N'' OR EXISTS ( SELECT 1 FROM "PROJECT_USER_ROLES" x WHERE x.cd_project_model = m.cd_project_model AND x.cd_human_resource = ' || get_var('cd_human_resource') || ' AND fl_active = ''Y'') )';
    END IF;




    EXECUTE vsql;
/*
    FOR v_record IN select * from tmpprojhc
    LOOP

        if v_record.dt_start < PAR_dt_start THEN
            v_temp_start = PAR_dt_start;
        ELSE 
             v_temp_start = v_record.dt_start;
        END IF;

        if v_record.dt_finish > PAR_dt_end THEN
             v_temp_end = PAR_dt_end;
        ELSE 
             v_temp_end = v_record.dt_finish;
        END IF;

        FOR v_record_dates in SELECT dd FROM generate_series (v_temp_start, v_temp_end , '1 day'::interval) dd
        LOOP
            v_x = + v_x + 1;

            v_key = 'P' || v_record.cd_project_build_schedule_tests::text || '-' || to_char(v_record_dates.dd, 'mmddyyyy');
            
            v_str_json_temp = '{"'|| v_key || '": ' || v_record.nr_headcount_requested_day::text || '}';

            -- RAISE NOTICE 'data %', v_str_json_temp;

            v_json_temp = v_str_json_temp::jsonb;

            v_json_hc = v_json_hc || v_json_temp;
            --select jsonb_set(v_json_hc, v_key, v_record.nr_headcount_requested_day );
       END LOOP;

   END LOOP;
*/

    RAISE NOTICE 'loops %', v_x;    

    select COALESCE(json_agg(r.*), '{}'::json) into v_json_make from ( select * from tmpprojhc) as r;
    select COALESCE(json_agg(r.*), '{}'::json) into v_json_period from ( SELECT dd as dt_date, to_char(dd, 'mmddyyyy') as nr_time, to_char(dd, 'Month') as ds_month  FROM generate_series (PAR_dt_start, PAR_dt_end , '1 day'::interval) dd ORDER BY dd ) as r;    


    json_projects = v_json_make;
    --json_hc_dates = v_json_hc;
    json_dates = v_json_period;
    
    RETURN NEXT;

    

    


END




$$  LANGUAGE plpgsql;
ALTER FUNCTION tti.getprojhc(date, date, text) SET search_path=audit, public, translation, docrep, rfq, tti, schedule, tr;

