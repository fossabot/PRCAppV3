CREATE OR REPLACE FUNCTION tti.getTimeAttendance(PAR_where text )
  RETURNS TABLE (
  recid bigint,
  dt_attend_date timestamp,
  cd_human_resource integer,
  ds_human_resource text,
  nr_staff_number integer,
  ds_staff_number text,
  ds_shift text,
  ds_department text,

  dt_first_kronos timestamp,
  dt_second_kronos timestamp,
  dt_third_kronos timestamp,
  dt_forth_kronos timestamp,
  dt_fifth_kronos timestamp,
  dt_sixth_kronos timestamp,
  dt_seventh_kronos timestamp,
  dt_eigth_kronos timestamp,

  dt_first_faceid timestamp,
  dt_second_faceid timestamp,
  dt_third_faceid timestamp,
  dt_forth_faceid timestamp,
  dt_fifth_faceid timestamp,
  dt_sixth_faceid timestamp,
  dt_seventh_faceid timestamp,
  dt_eigth_faceid timestamp,

  fl_must_first integer,
  fl_must_second integer,
  fl_must_third integer,
  fl_must_forth integer,
  fl_must_fifth integer,
  fl_must_sixth integer,
  fl_must_seventh integer,
  fl_must_eigth integer,

  fl_must_first_faceid integer,
  fl_must_second_faceid integer,
  fl_must_third_faceid integer,
  fl_must_forth_faceid integer,
  fl_must_fifth_faceid integer,
  fl_must_sixth_faceid integer,
  fl_must_seventh_faceid integer,
  fl_must_eigth_faceid integer,


  ds_abnormal_reason text,
  fl_id_scanned_before integer,
  ds_faceid_reason text

)   AS
$$
DECLARE
   vsql text;
   v_record record;
   vrecid bigint;
   vdt_temp timestamp;
   vdt_temp_2 timestamp;
   v_minutes_range integer;

BEGIN

    v_minutes_range = 30;


    drop table if exists tmptimeattendance ;

    vsql = 'CREATE TEMPORARY TABLE tmptimeattendance AS   SELECT
  "HR_ATTENDANCE_BASE".cd_hr_attendance_base,
  "HR_ATTENDANCE_BASE".nr_staff_number,
  LPAD("HR_ATTENDANCE_BASE".nr_staff_number::text,6, ''0'') as ds_staff_number,
  "HR_ATTENDANCE_BASE".ds_staff_name,
    "HR_ATTENDANCE_BASE".ds_department,
    "HR_ATTENDANCE_BASE".dt_attend_date,
    "HR_ATTENDANCE_BASE".ds_shift,
  trim(
    coalesce(
      "HR_ATTENDANCE_BASE".ds_abnormal_reason,
      ''''
    )
  ) as ds_abnormal_reason,
     "HR_ATTENDANCE_BASE".cd_hr_shift_data,
     "HR_ATTENDANCE_BASE".dt_start_one,
      "HR_ATTENDANCE_BASE".dt_end_one,
      "HR_ATTENDANCE_BASE".dt_start_two,
      "HR_ATTENDANCE_BASE".dt_end_two,
      "HR_ATTENDANCE_BASE".dt_start_three,
      "HR_ATTENDANCE_BASE".dt_end_three,
      "HR_ATTENDANCE_BASE".dt_start_four,
      "HR_ATTENDANCE_BASE".dt_end_four,
      "HR_ATTENDANCE_BASE".dt_join_date,
      ( CASE WHEN EXISTS ( SELECT 1 FROM "FACE_SCANNER_RECORD"  x WHERE x.nr_staff_number = "HR_ATTENDANCE_BASE".nr_staff_number ) THEN 1 ELSE 0 END ) as fl_id_scanned_before,
      (SELECT cd_human_resource FROM "HUMAN_RESOURCE" where nr_staff_number = "HR_ATTENDANCE_BASE".nr_staff_number ) as cd_human_resource,
     "HR_ATTENDANCE_BASE".ds_faceid_reason
    FROM "HR_ATTENDANCE_BASE"
' ||  PAR_where;

    EXECUTE vsql;

    FOR v_record IN select *
    from tmptimeattendance tmp
   JOIN tti."HR_SHIFT_DATA" s ON (s.cd_hr_shift_data = tmp.cd_hr_shift_data)
   LEFT OUTER JOIN public."HUMAN_RESOURCE" h ON (h.nr_staff_number  = tmp.nr_staff_number)
   LOOP

      dt_attend_date    = v_record.dt_attend_date;
      cd_human_resource = v_record.cd_human_resource;
      ds_human_resource = v_record.ds_staff_name;
      nr_staff_number   = v_record.nr_staff_number;
      ds_staff_number   = v_record.ds_staff_number;
      ds_department     = v_record.ds_department;

      dt_first_kronos   = v_record.dt_start_one;
      dt_second_kronos  = v_record.dt_end_one;
      dt_third_kronos   = v_record.dt_start_two;
      dt_forth_kronos   = v_record.dt_end_two;
      dt_fifth_kronos   = v_record.dt_start_three;
      dt_sixth_kronos   = v_record.dt_end_three;
      dt_seventh_kronos = v_record.dt_start_four;
      dt_eigth_kronos   = v_record.dt_end_four;
      ds_shift          = v_record.ds_shift;
      fl_id_scanned_before = v_record.fl_id_scanned_before;

      recid = v_record.cd_hr_attendance_base;

      dt_first_faceid   = NULL;
      dt_second_faceid  = NULL;
      dt_third_faceid   = NULL;
      dt_forth_faceid   = NULL;
      dt_fifth_faceid   = NULL;
      dt_sixth_faceid   = NULL;
      dt_seventh_faceid = NULL;
      dt_eigth_faceid   = NULL;

      ds_abnormal_reason = v_record.ds_abnormal_reason;

      fl_must_first = 0;
      fl_must_second = 0;
      fl_must_third = 0;
      fl_must_forth = 0;
      fl_must_fifth = 0;
      fl_must_sixth = 0;
      fl_must_seventh = 0;
      fl_must_eigth = 0;
      fl_must_first_faceid  = 0;
      fl_must_second_faceid  = 0;
      fl_must_third_faceid  = 0;
      fl_must_forth_faceid  = 0;
      fl_must_fifth_faceid  = 0;
      fl_must_sixth_faceid  = 0;
      fl_must_seventh_faceid  = 0;
      fl_must_eigth_faceid  = 0;

      ds_faceid_reason = v_record.ds_faceid_reason;



      -- start checking what is demanded - 1st Date
      IF v_record.dt_start_work IS NOT NULL THEN
        fl_must_first = 1;
        fl_must_first_faceid = 1;
        vdt_temp = NULL;


        SELECT min(x.dt_attend_date) INTO vdt_temp
          FROM "FACE_SCANNER_RECORD"  x
         WHERE x.nr_staff_number = v_record.nr_staff_number
           AND x.dt_attend_date between dt_first_kronos - (v_minutes_range ||' minutes')::interval AND dt_first_kronos + (v_minutes_range ||' minutes')::interval;

        dt_first_faceid = vdt_temp;
      END IF;

      -- start checking what is demanded - 2nd Date
      IF v_record.dt_start_lunch_time IS NOT NULL THEN
        fl_must_second = 1;
        fl_must_second_faceid  = 1;
        vdt_temp = NULL;

        SELECT min(x.dt_attend_date) INTO vdt_temp
          FROM "FACE_SCANNER_RECORD"  x
         WHERE x.nr_staff_number = v_record.nr_staff_number
           AND x.dt_attend_date between dt_second_kronos - (v_minutes_range ||' minutes')::interval AND dt_second_kronos + (v_minutes_range ||' minutes')::interval;

        dt_second_faceid = vdt_temp;
      END IF;


      -- start checking what is demanded - 3rd Date
      IF v_record.dt_end_lunch IS NOT NULL THEN
        fl_must_third = 1;
        fl_must_third_faceid  = 1;
        vdt_temp = NULL;

        SELECT max(x.dt_attend_date) INTO vdt_temp
          FROM "FACE_SCANNER_RECORD"  x
         WHERE x.nr_staff_number = v_record.nr_staff_number
           AND x.dt_attend_date between dt_third_kronos - (v_minutes_range ||' minutes')::interval AND dt_third_kronos + (v_minutes_range ||' minutes')::interval
           AND x.dt_attend_date != COALESCE (dt_second_faceid, dt_first_faceid);

        dt_third_faceid = vdt_temp;
      END IF;

      -- start checking what is demanded - 4th Date
      IF v_record.dt_end_work IS NOT NULL THEN
        fl_must_forth = 1;
        fl_must_forth_faceid = 1;
        vdt_temp = NULL;

        SELECT max(x.dt_attend_date) INTO vdt_temp
          FROM "FACE_SCANNER_RECORD"  x
         WHERE x.nr_staff_number = v_record.nr_staff_number
           AND x.dt_attend_date between dt_forth_kronos - (v_minutes_range ||' minutes')::interval AND dt_forth_kronos + (v_minutes_range ||' minutes')::interval;

        dt_forth_faceid = vdt_temp;
      END IF;


      IF dt_first_kronos IS NULL AND dt_second_kronos  IS NULL AND dt_third_kronos IS NULL AND dt_forth_kronos IS NULL THEN
        fl_must_first_faceid  = 0;
        fl_must_second_faceid  = 0;
        fl_must_third_faceid  = 0;
        fl_must_forth_faceid  = 0;
      END IF;





      IF  dt_third_faceid IS NULL AND fl_must_third_faceid = 1 AND dt_second_faceid IS NOT NULL THEN
        fl_must_third_faceid = 0;
      END IF;

      IF  dt_second_faceid IS NULL AND fl_must_second_faceid = 1 AND dt_third_faceid IS NOT NULL THEN
        fl_must_second_faceid = 0;
      END IF;


      RETURN NEXT;



   END LOOP;

END
$$  LANGUAGE plpgsql;
ALTER FUNCTION tti.getTimeAttendance(text) SET search_path=audit, public, translation, docrep, rfq, tti;



/*
select  nr_staff_number,
        ds_staff_name,
        ds_department,
        dt_attend_date,
        dt_first_kronos,
        dt_second_kronos,
        dt_third_kronos,
        dt_forth_kronos,
        dt_first_faceid,
        dt_second_faceid,
        dt_third_faceid,
        dt_forth_faceid,

        fl_must_first,
        fl_must_second,
        fl_must_third,
        fl_must_forth ,


        CASE WHEN fl_must_first = 0 THEN 'NA'
             WHEN fl_must_first = 1 AND dt_first_kronos IS NOT NULL THEN 'OK'
             ELSE 'MISSING' END as ds_first_kronos_status,

        CASE WHEN fl_must_second = 0 THEN 'NA'
             WHEN fl_must_second = 1 AND dt_second_kronos IS NOT NULL THEN 'OK'
             ELSE 'MISSING' END as ds_second_kronos_status,

        CASE WHEN fl_must_third = 0 THEN 'NA'
             WHEN fl_must_third = 1 AND dt_third_kronos IS NOT NULL THEN 'OK'
             ELSE 'MISSING' END as ds_third_kronos_status,

        CASE WHEN fl_must_forth = 0 THEN 'NA'
             WHEN fl_must_forth = 1 AND dt_forth_kronos IS NOT NULL THEN 'OK'
             ELSE 'MISSING' END as ds_forth_kronos_status,


        CASE WHEN fl_must_first = 0 THEN 'NA'
             WHEN fl_must_first = 1 AND dt_first_faceid IS NOT NULL THEN 'OK'
             ELSE 'MISSING' END as ds_first_faceid_status,

        CASE WHEN fl_must_second = 0 THEN 'NA'
             WHEN fl_must_second = 1 AND dt_second_faceid IS NOT NULL THEN 'OK'
             ELSE 'MISSING' END as ds_second_faceid_status,

        CASE WHEN fl_must_third = 0 THEN 'NA'
             WHEN fl_must_third = 1 AND dt_third_faceid IS NOT NULL THEN 'OK'
             ELSE 'MISSING' END as ds_third_faceid_status,

        CASE WHEN fl_must_forth = 0 THEN 'NA'
             WHEN fl_must_forth = 1 AND dt_forth_faceid IS NOT NULL THEN 'OK'
             ELSE 'MISSING' END as ds_forth_faceid_status


 from getTimeAttendance(' WHERE 1 = 1 ')

*/
