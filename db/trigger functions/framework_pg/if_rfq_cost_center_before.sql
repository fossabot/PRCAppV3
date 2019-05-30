-- Function: audit.if_tablelog()

-- DROP FUNCTION audit.if_tablelog();


CREATE OR REPLACE FUNCTION rfq.if_rfq_cost_center_before()
  RETURNS trigger AS
$BODY$
DECLARE
    v_error_text text;
    v_error_code int;
    v_cd_approval_steps_config_next int;
    v_nr_order_now int;


BEGIN
    -- start without error!
    v_error_code = 0;
   --updates
    IF (TG_OP = 'UPDATE') THEN

        

    --delete
    ELSIF (TG_OP = 'DELETE') THEN


    --insert
    ELSIF (TG_OP = 'INSERT') THEN
        
    ELSE
        RAISE EXCEPTION 'Trigger func added as trigger for unhandled case: %, %',TG_OP, TG_LEVEL;
        RETURN NULL;
    END IF;


   --updates ou insert
    IF (TG_OP = 'UPDATE' OR TG_OP = 'INSERT') THEN
        
        IF EXISTS ( SELECT 1 
                     FROM "DEPARTMENT_COST_CENTER" a
                    WHERE a.cd_department_cost_center = NEW.cd_department_cost_center
                      AND a.fl_demand_project         = 'Y'
                      AND ( NEW.ds_project_number IS NULL OR NEW.ds_project_model_number IS NULL )
                  ) THEN

                    v_error_text = retDescTranslated('You have Department that demand Project and Model number. Please inform it'::text, null::integer);
                    RAISE EXCEPTION '% (%)', v_error_text, 15648;


                  END IF;

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
ALTER FUNCTION rfq.if_rfq_cost_center_before() SET search_path=pg_catalog, public, spec, rfq, translation;

ALTER FUNCTION rfq.if_rfq_cost_center_before() OWNER TO postgres;

/*

    CREATE TRIGGER t_if_rfq_cost_center_before
      BEFORE UPDATE OR INSERT OR DELETE
      ON rfq."RFQ_COST_CENTER"
      FOR EACH ROW
      EXECUTE PROCEDURE rfq.if_rfq_cost_center_before();

*/