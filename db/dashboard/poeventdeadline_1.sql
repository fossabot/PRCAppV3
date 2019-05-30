
CREATE OR REPLACE FUNCTION spec.poeventdeadline
(
    p_cd_hm_system_dashboard_widget_param integer
)

RETURNS text AS
$$
DECLARE
   v_cd_generic_shoe_specification int;
   v_record_status record;
   v_record_process record;

   v_status_jsonb  jsonb;
   v_process_jsonb jsonb;
   v_data jsonb[];
   v_data_clear jsonb[];
   v_total integer;
   v_jsonb_ret jsonb;
   v_final_data jsonb[];
   v_counter integer;
   v_jsonb_data jsonb;
   v_json_settings jsonb;
   v_json_color jsonb;
   v_cd_division integer;
   v_cd_season   integer;
   v_cd_processes integer[];
   v_cd_system_product_category integer;
   v_label_done text;
   v_label_on_time text;

BEGIN

    DROP TABLE IF EXISTS tmpProcessDash;
    DROP TABLE IF EXISTS tmpProcessStatus;
    DROP TABLE IF EXISTS tmpProcessFinal;

    SELECT json_parameters INTO v_json_settings
      FROM "HR_SYSTEM_DASHBOARD_WIDGET_PARAM"
     WHERE cd_hm_system_dashboard_widget_param = p_cd_hm_system_dashboard_widget_param;

    
     if v_json_settings IS NULL THEN 
        RETURN '{}';
     END IF;

    v_label_done    = retDescTranslatedNew(getSysParameter('LABEL_FOR_PROCESS_DONE'), null);
    v_label_on_time = retDescTranslatedNew(getSysParameter('LABEL_FOR_PROCESS_ON_TYPE'), null);
     

     v_cd_division = v_json_settings->>'cd_division';
     v_cd_season   = v_json_settings->>'cd_season';
     v_cd_processes = string_to_array(v_json_settings->>'ds_processes', ',');
     v_cd_system_product_category = get_var('cd_system_product_category');

     RAISE NOTICE '%', v_cd_processes;

    create temporary table IF NOT EXISTS tmpProcessStatus ON COMMIT DROP
--    create table IF NOT EXISTS public.tmpProcessStatus
    as select gi.cd_shoe_process_item_group,
           ps.ds_shoe_process_status,
           ps.ds_background_color,
           ps.ds_font_color,

           ( CASE WHEN x.nr_days_before_deadline IS NOT NULL THEN x.nr_days_before_deadline ELSE ps.nr_days_before_deadline END) as nr_days_before_deadline           
    FROM "SHOE_PROCESS_GROUP_ITEM" gi
    JOIN "SHOE_PROCESS" p ON ( p.cd_shoe_process = gi.cd_shoe_process )
    JOIN "SHOE_PROCESS_GROUP" g ON ( g.cd_shoe_process_group = gi.cd_shoe_process_group )
    JOIN "SHOE_PROCESS_ITEM" i ON ( i.cd_shoe_process_item = gi.cd_shoe_process_item )
    JOIN "SHOE_PROCESS_STATUS" ps ON (true)
    LEFT OUTER JOIN "SHOE_PROCESS_GROUP_ITEM_STATUS" x ON ( x.cd_shoe_process_status = ps.cd_shoe_process_status AND x.cd_shoe_process_item_group = gi.cd_shoe_process_item_group );

    insert into tmpProcessStatus
    select cd_shoe_process_item_group, v_label_on_time, '337ab7', ds_font_color, 99999999
    from tmpProcessStatus
    group by cd_shoe_process_item_group, ds_background_color, ds_font_color;

    insert into tmpProcessStatus
    select cd_shoe_process_item_group, v_label_done, '00c0ef', ds_font_color, null
    from tmpProcessStatus
    group by cd_shoe_process_item_group, ds_background_color, ds_font_color;



    create temporary table IF NOT EXISTS tmpProcessDash ON COMMIT DROP
    --create table IF NOT EXISTS public.tmpProcessDash
    as select i.ds_shoe_process_item,
           po_sku_prc.cd_shoe_process_item_group,
           
           gi.nr_order,
           ( CAST ( po_sku_prc.dt_dead_line AS date ) ) as dt_dead_line,
           ( CASE WHEN dt_finished IS NOT NULL THEN 'Y' ELSE 'N' END ) as fl_finalized,
           ( CAST(dt_dead_line AS date) - CAST(NOW() AS date) ) as nr_diff,
           count(1) as nr_count
           
    from "SHOE_PURCHASE_ORDER" po
    JOIN "SHOE_PURCHASE_ORDER_SKU" po_sku ON ( po_sku.cd_shoe_purchase_order = po.cd_shoe_purchase_order )
    JOIN "SHOE_PURCHASE_ORDER_SKU_PROCESS" po_sku_prc ON ( po_sku_prc.cd_shoe_purchase_order_sku = po_sku.cd_shoe_purchase_order_sku )
    JOIN "SHOE_PROCESS_GROUP_ITEM" gi ON ( gi.cd_shoe_process_item_group = po_sku_prc.cd_shoe_process_item_group )
    JOIN "SHOE_PROCESS" p ON ( p.cd_shoe_process = gi.cd_shoe_process )
    JOIN "SHOE_PROCESS_GROUP" g ON ( g.cd_shoe_process_group = gi.cd_shoe_process_group )
    JOIN "SHOE_PROCESS_ITEM" i ON ( i.cd_shoe_process_item = gi.cd_shoe_process_item )


    WHERE po.cd_division = v_cd_division
      AND po.cd_season   = v_cd_season
      AND p.cd_shoe_process_level = 2
      AND gi.dt_deactivated IS NULL
      AND p.dt_deactivated IS NULL
      AND p.cd_system_product_category = v_cd_system_product_category
      AND po_sku_prc.dt_dead_line IS NOT NULL
      AND po_sku_prc.cd_shoe_process_item_group = ANY (v_cd_processes)

 GROUP BY i.ds_shoe_process_item,
           po_sku_prc.cd_shoe_process_item_group,
           gi.nr_order,
           CAST ( po_sku_prc.dt_dead_line AS date ),
           ( CASE WHEN dt_finished IS NOT NULL THEN 'Y' ELSE 'N' END );





    create temporary table IF NOT EXISTS tmpProcessFinal ON COMMIT DROP
    --create table IF NOT EXISTS public.tmpProcessFinal

    as select p.ds_shoe_process_item,
              p.nr_order,
           ( CASE WHEN p.fl_finalized = 'Y' THEN v_label_done
                ELSE ( SELECT s.ds_shoe_process_status
                         FROM tmpProcessStatus s
                         WHERE s.cd_shoe_process_item_group = p.cd_shoe_process_item_group
                           AND p.nr_diff <= s.nr_days_before_deadline
                        ORDER BY s.nr_days_before_deadline ASC
                        LIMIT 1 
                     ) END ) as ds_info,

           ( CASE WHEN p.fl_finalized = 'Y' THEN '00c0ef'
                ELSE ( SELECT s.ds_background_color
                         FROM tmpProcessStatus s
                         WHERE s.cd_shoe_process_item_group = p.cd_shoe_process_item_group
                           AND p.nr_diff <= s.nr_days_before_deadline
                        ORDER BY s.nr_days_before_deadline ASC
                        LIMIT 1 
                     ) END ) as ds_background_color,
           ( CASE WHEN p.fl_finalized = 'Y' THEN '' 
                ELSE ( SELECT s.ds_font_color
                         FROM tmpProcessStatus s
                         WHERE s.cd_shoe_process_item_group = p.cd_shoe_process_item_group
                           AND p.nr_diff <= s.nr_days_before_deadline
                        ORDER BY s.nr_days_before_deadline ASC
                        LIMIT 1 
                     ) END ) as ds_font_color,
           ( CASE WHEN p.fl_finalized = 'Y' THEN 999999999 
                ELSE ( SELECT s.nr_days_before_deadline
                         FROM tmpProcessStatus s
                         WHERE s.cd_shoe_process_item_group = p.cd_shoe_process_item_group
                           AND p.nr_diff <= s.nr_days_before_deadline
                        ORDER BY s.nr_days_before_deadline ASC
                        LIMIT 1 
                     ) END ) as nr_days_before_deadline,

                     p.nr_count

    from tmpProcessDash p;


    insert into tmpProcessFinal (ds_shoe_process_item, nr_order, ds_info, ds_background_color, ds_font_color, nr_days_before_deadline, nr_count)
    select distinct ds_shoe_process_item, nr_order, s.ds_shoe_process_status, s.ds_background_color, ds_font_color, s.nr_days_before_deadline, 0
      from (select distinct ds_shoe_process_status, ds_background_color, nr_days_before_deadline FROM tmpProcessStatus) as s, 
           tmpProcessFinal f
    WHERE NOT EXISTS ( select 1 
                         from tmpProcessFinal x
                        WHERE x.ds_info              = s.ds_shoe_process_status
                          AND x.ds_shoe_process_item = f.ds_shoe_process_item ) ;
    

    delete from tmpProcessFinal x
    where not exists ( select 1 from tmpProcessFinal y 
                        WHERE y.ds_info = x.ds_info 
                          AND y.ds_shoe_process_item = y.ds_shoe_process_item 
                          AND y.nr_count > 0 );


    select jsonb_agg(r.ds_info), jsonb_agg('#'||ds_background_color)  INTO v_status_jsonb, v_json_color
    from (
    select ds_info, ds_background_color, min(nr_days_before_deadline) as nr_days_before_deadline 
      from tmpProcessFinal
    GROUP BY ds_info, ds_background_color
    order by ds_info ) as r;



    select jsonb_agg(r.ds_shoe_process_item) INTO v_process_jsonb
    from (
    select ds_shoe_process_item, min(nr_order) as nr_order from tmpProcessFinal
    GROUP BY ds_shoe_process_item
    order by 2 ) as r;

    v_jsonb_ret = jsonb_build_object('dataColor', v_json_color,   'dataLegend', coalesce(v_status_jsonb, '{}'::jsonb)  , 'category', coalesce(v_process_jsonb, '{}'::jsonb));
    v_counter = 1;
    FOR v_record_status IN
    select ds_info, min(COALESCE(nr_days_before_deadline, 0)) as nr_days_before_deadline from tmpProcessFinal
        GROUP BY ds_info
        order by ds_info
    LOOP
        


        select jsonb_agg(r.nr_count) INTO v_jsonb_data
        from (
        select nr_order, sum(nr_count) as nr_count 
        from tmpProcessFinal
        WHERE ds_info = v_record_status.ds_info
        GROUP BY nr_order
        --HAVING sum(nr_count) > 0
        order by 1 ) as r;

        v_final_data = v_final_data || v_jsonb_data;
        --array_append(v_data, v_total);



    END LOOP;
    v_jsonb_ret = v_jsonb_ret || jsonb_build_object('data', COALESCE(v_final_data, '{}'::jsonb[]));


    RETURN v_jsonb_ret::text;

END;
$$
LANGUAGE plpgsql;






/*


  select (  CAST ( dt_dead_line AS date ) - CAST ( NOW() AS date ) ) as DateDifference
  from "SHOE_PURCHASE_ORDER_SKU_PROCESS"

      SELECT CAST($1 AS date) - CAST($2 AS date) as 
  */

/*GROUP BY i.ds_shoe_process_item, gi.nr_order
 order by 2
  */   

/*
      
      "SHOE_PROCESS_GROUP_ITEM_STATUS" is,
      



select * from "SHOE_PROCESS_STATUS"


                  ( select json_agg(bla.*)::text from ( select
                                xx.ds_shoe_process_status,
                                COALESCE(xx.ds_background_color, '\') as ds_background_color,
                                COALESCE(xx.ds_font_color, '\') as ds_font_color,
                                ( CASE WHEN x.cd_shoe_process_item_group_status IS NULL THEN xx.nr_days_before_deadline ELSE x.nr_days_before_deadline END) as nr_days_before_deadline
                            from "SHOE_PROCESS_STATUS" xx
                                LEFT OUTER JOIN "SHOE_PROCESS_GROUP_ITEM_STATUS" x ON (  )
                               ORDER BY nr_days_before_deadline
                            ) as bla ) as ds_process_status_json,

 
             --ORDER BY gi.nr_order*/