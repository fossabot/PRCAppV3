CREATE OR REPLACE FUNCTION assets.getDepretiation()
  RETURNS TABLE (
  ds_asset text,
  nr_month integer,
  nr_year  integer,
  nr_months_depreciation integer,
  dt_ref   date,
  dt_start date, 
  dt_end   date,
  ds_category text,
  nr_value_month_depreciation numeric(12,2),
  nr_value_initial numeric(12,2),
  nr_value_month_balance numeric(12,2),
  ds_deparment text,
  nr_years_depreciation integer

)   AS
$$
DECLARE 
    v_record_date record;
    v_record_assets record;
    v_month integer;
    v_value_power numeric(12,2);
    v_date_start date;
    v_date_end date;
BEGIN

    drop table if exists tmpassets;

    create temporary table tmpassets as 
    select a.*, d.ds_department_cost_center from assets."ASSETS" a , "DEPARTMENT_COST_CENTER" d
    where a.dt_start_monthly_depreciation IS NOT NULL and COALESCE(a.nr_initial_value, 0) > 0 
      and d.cd_department_cost_center = a.cd_department_cost_center;
    
    update tmpassets set nr_monthly_depreciation = round(nr_initial_value / (datediff('month', date_trunc('month', dt_start_monthly_depreciation)::date, date_trunc('month', dt_end_monthly_depreciation)::date) + 1), 2);

    FOR v_record_date IN select r.dt_date, ( r.dt_date + '1 month'::interval - '1 day'::interval) as dt_last_date
     from (select generate_series('2000-01-01'::date, '2030-12-01'::date, '1 months'::interval) as dt_date) as r
   LOOP

        nr_month                    = date_part('month', v_record_date.dt_last_date);
        nr_year                     = date_part('year', v_record_date.dt_last_date);

        IF nr_month = 1 AND nr_year > 2018 THEN
            IF (nr_year = 2019) THEN
                v_value_power = 800000;
            ELSE 
                v_value_power = 400000;
            END IF;

            v_date_start = (nr_year::text || '-' || nr_month::text || '-01')::date;

            -- 10% for 10 years;
            insert into tmpassets ( cd_assets, 
                                    ds_assets_number, 
                                    ds_assets, 
                                    ds_category, 
                                    dt_start_monthly_depreciation, 
                                    dt_end_monthly_depreciation, 
                                    nr_initial_value, 
                                    nr_monthly_depreciation,
                                    ds_department_cost_center
                                   )
            SELECT -11 * nr_year, 
                    nr_year::text || '  10%', 
                   'FUTURE ' || nr_year::text || ' 10', 
                    'FUTURE',
                    v_date_start , 
                    v_date_start + '119 months'::interval, 
                    round(v_value_power * 0.10, 2), 
                    round((v_value_power * 0.10) / 120, 2),
                    '6513';

            -- 90% for 5 years;
            insert into tmpassets ( cd_assets, 
                                    ds_assets_number, 
                                    ds_assets, 
                                    ds_category, 
                                    dt_start_monthly_depreciation, 
                                    dt_end_monthly_depreciation, 
                                    nr_initial_value, 
                                    nr_monthly_depreciation,
                                    ds_department_cost_center
                                    
                                   )
            SELECT -10 * nr_year, 
                    nr_year::text || '  90%', 
                   'FUTURE ' || nr_year::text || ' 90', 
                    'FUTURE',
                    v_date_start , 
                    v_date_start + '59 months'::interval, 
                    round(v_value_power * 0.90, 2), 
                    round((v_value_power * 0.90) / 60, 2),
                    '6513';


        END IF;

        

        FOR v_record_assets IN select * from tmpassets where daterange(v_record_date.dt_date::date, v_record_date.dt_last_date::date, '[]') && daterange(dt_start_monthly_depreciation::date, dt_end_monthly_depreciation::date, '[]')

        LOOP

--            v_month = (date_part('month', age(v_record_date.dt_last_date, ( v_record_assets.dt_start_monthly_depreciation + '1 month'::interval - '1 day'::interval)) )  + date_part('year', age(v_record_date.dt_last_date, ( v_record_assets.dt_start_monthly_depreciation + '1 month'::interval - '1 day'::interval)) ) * 12) + 1;
            v_month = datediff('month', date_trunc('month', v_record_assets.dt_start_monthly_depreciation)::date, date_trunc('month', v_record_date.dt_last_date)::date) + 1;

              
            nr_years_depreciation       = round((datediff('month', date_trunc('month', v_record_assets.dt_start_monthly_depreciation)::date, date_trunc('month', v_record_assets.dt_end_monthly_depreciation)::date)::numeric(12,2) / 12), 0);
            ds_asset                    = v_record_assets.ds_assets_number || ' - ' || v_record_assets.ds_assets;
            dt_ref                      = v_record_date.dt_date;
            ds_category                 = v_record_assets.ds_category;
            dt_start                    = v_record_assets.dt_start_monthly_depreciation;
            dt_end                      = v_record_assets.dt_end_monthly_depreciation;
            ds_deparment                = v_record_assets.ds_department_cost_center;

            IF daterange(v_record_date.dt_date::date, v_record_date.dt_last_date::date, '[]') && daterange(v_record_assets.dt_start_monthly_depreciation::date, v_record_assets.dt_end_monthly_depreciation::date, '[]') THEN
                nr_months_depreciation      = v_month;
                nr_value_month_depreciation = v_record_assets.nr_monthly_depreciation;
                nr_value_initial            = v_record_assets.nr_initial_value;
                nr_value_month_balance      = v_record_assets.nr_initial_value - ( v_record_assets.nr_monthly_depreciation * v_month) ; 
            ELSE 
                nr_months_depreciation      = 0;
                nr_value_month_depreciation = 0;
                nr_value_initial            = 0;
                nr_value_month_balance      = 0; 
                nr_years_depreciation = 0;
            END IF;

            RETURN NEXT;

        END LOOP;



   END LOOP;

END
$$  LANGUAGE plpgsql;
ALTER FUNCTION assets.getDepretiation() SET search_path=audit, public, translation, docrep, rfq, tti, assets;


