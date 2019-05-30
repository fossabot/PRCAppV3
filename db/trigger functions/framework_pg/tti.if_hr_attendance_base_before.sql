-- FunctionNEW. audit.if_tablelog()

-- DROP FUNCTION audit.if_tablelog();


CREATE OR REPLACE FUNCTION tti.if_hr_attendance_base_before()
  RETURNS trigger AS
$BODY$
DECLARE
    v_error_text text;
    v_error_code int;


BEGIN
    -- start without error!
    v_error_code = 0;

   --updates
    IF (TG_OP = 'UPDATE') THEN
        IF NEW.ds_shift != OLD.ds_shift THEN
            NEW.cd_hr_shift_data = tti.hr_get_shift_data(NEW.ds_shift);
        END IF;

    --delete
    ELSEIF (TG_OP = 'DELETE') THEN


    --insert
    ELSEIF (TG_OP = 'INSERT') THEN

        NEW.cd_hr_shift_data = tti.hr_get_shift_data(NEW.ds_shift);

        IF EXISTS ( SELECT 1 
                     FROM tti."HR_ATTENDANCE_BASE" 
                    WHERE nr_staff_number = NEW.nr_staff_number
                      AND dt_attend_date  = NEW.dt_attend_date) THEN

            UPDATE
              tti."HR_ATTENDANCE_BASE"
            SET
              ds_staff_name = NEW.ds_staff_name,
              ds_department = NEW.ds_department,
              ds_shift      = NEW.ds_shift,
              ds_abnormal_reason = NEW.ds_abnormal_reason,
              dt_start_one = NEW.dt_start_one,
              dt_end_one = NEW.dt_end_one,
              dt_start_two = NEW.dt_start_two,
              dt_end_two = NEW.dt_end_two,
              dt_start_three = NEW.dt_start_three,
              dt_end_three = NEW.dt_end_three,
              dt_start_four = NEW.dt_start_four,
              dt_end_four = NEW.dt_end_four,
              dt_join_date = NEW.dt_join_date,
              cd_hr_shift_data = tti.hr_get_shift_data(NEW.ds_shift)
        WHERE nr_staff_number = NEW.nr_staff_number
          AND dt_attend_date  = NEW.dt_attend_date;

          RETURN NULL;
        END IF;


        IF NOT EXISTS(SELECT 1
                      FROM public."HUMAN_RESOURCE" x
                      WHERE x.nr_staff_number = NEW.nr_staff_number)
        THEN
          INSERT INTO public."HUMAN_RESOURCE"
          (ds_human_resource_full,
           ds_human_resource,
           cd_hr_type,
           ds_password,
           fl_ldap,
           nr_staff_number)
          VALUES (NEW.ds_staff_name,
                  NEW.ds_staff_name,
                  133424,
                  NEW.ds_staff_name,
                  'N',
                  NEW.nr_staff_number);
        END IF;

    ELSE
        RAISE EXCEPTION 'Trigger func added as trigger for unhandled caseNEW. %, %',TG_OP, TG_LEVEL;
        RETURN NULL;
    END IF;


   --updates ou insert
    IF (TG_OP = 'UPDATE' OR TG_OP = 'INSERT') THEN
        --NEW.dt_update = NOW();
    END IF;




    -- controle de erro!!!!
    IF v_error_code > 0 THEN

        SELECT getTriggerError (v_error_code, null)
          INTO v_error_text;

        RAISE EXCEPTION '% (%)', v_error_text, v_error_code;
    END IF;


  -- Retorna OK
  IF TG_OP = 'DELETE' THEN 
    RETURN OLD;
  ELSE
    RETURN NEW;
   END IF;

END;
$BODY$
  LANGUAGE plpgsql VOLATILE SECURITY DEFINER
  COST 100;
ALTER FUNCTION tti.if_hr_attendance_base_before() SET search_path=pg_catalog, public, spec, rfq;

ALTER FUNCTION tti.if_hr_attendance_base_before() OWNER TO postgres;

/*

        CREATE TRIGGER t_if_hr_attendance_base_before
          BEFORE UPDATE OR INSERT OR DELETE
          ON tti."HR_ATTENDANCE_BASE"
          FOR EACH ROW
          EXECUTE PROCEDURE tti.if_hr_attendance_base_before();


*/

