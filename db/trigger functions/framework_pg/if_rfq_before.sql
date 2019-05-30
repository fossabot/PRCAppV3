-- Function: audit.if_tablelog()

-- DROP FUNCTION audit.if_tablelog();


CREATE OR REPLACE FUNCTION rfq.if_rfq_before()
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
        IF NEW.dt_deactivated is not NULL AND NEW.ds_cancel_reason is NULL THEN
			v_error_text = retDescTranslated('You must add comments when cancel'::text, null::integer);
        	RAISE EXCEPTION '% (%)', v_error_text, 15648;
--             RAISE EXCEPTION 'You must add comments when cancel';
        END IF;

    --delete
    ELSIF (TG_OP = 'DELETE') THEN



        IF EXISTS ( SELECT 1 
                      FROM "RFQ_APPROVAL_STEPS" r, "APPROVAL_STEPS_CONFIG" c
                     WHERE r.cd_rfq                    = OLD.cd_rfq
                       AND c.cd_approval_steps_config = r.cd_approval_steps_config
                       AND c.ds_internal_code         = 'ToQuote'
                       AND r.cd_approval_status       = 1
                   ) THEN

        v_error_text = retDescTranslated('You cannot Delete because it is already approved to quote'::text, null::integer);
        RAISE EXCEPTION '% (%)', v_error_text, 15648;


    END IF;

    --insert
    ELSIF (TG_OP = 'INSERT') THEN
        IF NEW.cd_system_product_category IS NULL THEN
            NEW.cd_system_product_category = get_var('cd_system_product_category');
        END IF;

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
ALTER FUNCTION rfq.if_rfq_before() SET search_path=pg_catalog, public, spec, rfq, translation;

ALTER FUNCTION rfq.if_rfq_before() OWNER TO postgres;



    CREATE TRIGGER t_if_rfq_before
      BEFORE UPDATE OR INSERT OR DELETE
      ON rfq."RFQ"
      FOR EACH ROW
      EXECUTE PROCEDURE rfq.if_rfq_before();

