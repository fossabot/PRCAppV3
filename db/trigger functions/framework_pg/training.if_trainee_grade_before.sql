-- Function: audit.if_tablelog()

-- DROP FUNCTION audit.if_tablelog();


CREATE OR REPLACE FUNCTION training.if_trainee_grade_before()
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
      IF NEW.cd_course_testing_result != OLD.cd_course_testing_result OR NEW.cd_human_resource_trainee != OLD.cd_human_resource_trainee OR NEW.ds_remark IS DISTINCT FROM OLD.ds_remark THEN
        NEW.cd_human_resource_recorder = get_var('cd_human_resource')::integer;
      END IF;

    --delete
    ELSIF (TG_OP = 'DELETE') THEN



    --insert
    ELSIF (TG_OP = 'INSERT') THEN
        NEW.cd_human_resource_recorder = get_var('cd_human_resource')::integer;
    ELSE
        RAISE EXCEPTION 'Trigger func added as trigger for unhandled case: %, %',TG_OP, TG_LEVEL;
        RETURN NULL;
    END IF;


   --updates ou insert
    IF (TG_OP = 'UPDATE' OR TG_OP = 'INSERT') THEN

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
ALTER FUNCTION training.if_trainee_grade_before() SET search_path=pg_catalog, public, training;

ALTER FUNCTION training.if_trainee_grade_before() OWNER TO postgres;


/*
CREATE TRIGGER t_trainee_grade_before
BEFORE UPDATE OR INSERT OR DELETE
ON training."TRAINEE_GRADE"
FOR EACH ROW
EXECUTE PROCEDURE training.if_trainee_grade_before();
*/

