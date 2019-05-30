CREATE OR REPLACE FUNCTION tti.hr_get_shift_data(PAR_ds_hr_shift_data_kronos text)
  RETURNS bigint   AS
$$
DECLARE 
r record;
v_cd_hr_shift_data bigint;
vdt_1 text;
vdt_2 text;
vdt_3 text;
vdt_4 text;

vdt_dt1 time without time zone;
vdt_dt2 time without time zone;
vdt_dt3 time without time zone;
vdt_dt4 time without time zone;



BEGIN

    IF PAR_ds_hr_shift_data_kronos IS NULL THEN
        RETURN NULL;
    END IF;
  
        SELECT cd_hr_shift_data INTO v_cd_hr_shift_data FROM "HR_SHIFT_DATA" where ds_hr_shift_data_kronos = PAR_ds_hr_shift_data_kronos;
        IF NOT FOUND THEN
            v_cd_hr_shift_data = nextval('tti."HR_SHIFT_DATA_cd_hr_shift_data_seq"'::regclass);
            
            vdt_1 = split_part(replace (replace(replace (PAR_ds_hr_shift_data_kronos, '(', '~'), ')', '~'), ',', '~'), '~', 2);
            vdt_2 = split_part(replace (replace(replace (PAR_ds_hr_shift_data_kronos, '(', '~'), ')', '~'), ',', '~'), '~', 3);
            vdt_3 = split_part(replace (replace(replace (PAR_ds_hr_shift_data_kronos, '(', '~'), ')', '~'), ',', '~'), '~', 4);
            vdt_4 = split_part(replace (replace(replace (PAR_ds_hr_shift_data_kronos, '(', '~'), ')', '~'), ',', '~'), '~', 5);

            if coalesce(vdt_4 , '') != '' THEN
                IF is_time(vdt_1) AND is_time(vdt_2) AND is_time(vdt_3) AND is_time(vdt_4) THEN
                    vdt_dt1 = vdt_1::time without time zone;
                    vdt_dt2 = vdt_2::time without time zone;
                    vdt_dt3 = vdt_3::time without time zone;
                    vdt_dt4 = vdt_4::time without time zone;
                END IF;


             ELSE
                IF is_time(vdt_1) AND is_time(vdt_2) THEN
                    vdt_dt1 = vdt_1::time without time zone;
                    vdt_dt2 = vdt_2::time without time zone;
                END IF;
            END IF ;


            INSERT INTO tti."HR_SHIFT_DATA"
             ( cd_hr_shift_data, 
               ds_hr_shift_data, 
               ds_hr_shift_data_kronos,
               dt_start_work,
               dt_start_lunch_time,
               dt_end_lunch,
               dt_end_work,
               fl_night_shift,
               fl_must_punch_card
             ) values (
               v_cd_hr_shift_data, 
               PAR_ds_hr_shift_data_kronos,
               PAR_ds_hr_shift_data_kronos,
               vdt_dt1,
               vdt_dt2,
               vdt_dt3,
               vdt_dt4,
               CASE WHEN vdt_dt1 > '18:00'::time without time zone THEN 'Y' ELSE 'N' END,
               CASE WHEN  strpos(PAR_ds_hr_shift_data_kronos, 'NL-M') > 0 THEN 'N' ELSE 'Y' END               
            );

        END IF;


    RETURN v_cd_hr_shift_data;



END
$$  LANGUAGE plpgsql;

ALTER FUNCTION tti.hr_get_shift_data SET search_path=pg_catalog, public, rfq, tti;