-- Function: audit.if_tablelog()

-- DROP FUNCTION audit.if_tablelog();


CREATE OR REPLACE FUNCTION schema.func()
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


    --delete
    ELSIF (TG_OP = 'DELETE') THEN


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
ALTER FUNCTION schema.func() SET search_path=pg_catalog, public, spec, rfq;

ALTER FUNCTION schema.func() OWNER TO postgres;

/*

        CREATE TRIGGER t_if_rfq_item_supplier_quotation_before
          BEFORE UPDATE OR INSERT OR DELETE
          ON rfq."RFQ_ITEM_SUPPLIER_QUOTATION"
          FOR EACH ROW
          EXECUTE PROCEDURE schema.func();


*/

