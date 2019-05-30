-- Function: audit.if_tablelog()

-- DROP FUNCTION audit.if_tablelog();


CREATE OR REPLACE FUNCTION rfq.if_rfq_item_supplier_before()
  RETURNS trigger AS
$BODY$
DECLARE
    v_error_text text;
    v_error_code int;
    v_default_currency int;
    v_new_cd_currency int;
    v_old_cd_currency int;
    v_cd_currency_rate int;


BEGIN
    -- start without error!
    v_error_code = 0;
    v_default_currency = getSysParameter('DEFAULT_CURRENCY_RFQ')::integer;
    v_cd_currency_rate = NULL;
   --updates
    IF (TG_OP = 'UPDATE') THEN

        v_new_cd_currency  = NEW.cd_currency;
        v_old_cd_currency  = OLD.cd_currency;
        

    --delete
    ELSIF (TG_OP = 'DELETE') THEN


    --insert
    ELSIF (TG_OP = 'INSERT') THEN
        NEW.dt_record = NOW();
        v_new_cd_currency  = NEW.cd_currency;
        v_old_cd_currency  = -1;
        
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
ALTER FUNCTION rfq.if_rfq_item_supplier_before() SET search_path=pg_catalog, public, spec, rfq;

ALTER FUNCTION rfq.if_rfq_item_supplier_before() OWNER TO postgres;

/*

    CREATE TRIGGER t_if_rfq_item_supplier_before
      BEFORE UPDATE OR INSERT OR DELETE
      ON rfq."RFQ_ITEM_SUPPLIER"
      FOR EACH ROW
      EXECUTE PROCEDURE rfq.if_rfq_item_supplier_before();

*/