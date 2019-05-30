-- Function: audit.if_tablelog()

-- DROP FUNCTION audit.if_tablelog();


CREATE OR REPLACE FUNCTION rfq.if_supplier_before()
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

        IF NEW.fl_tti_supplier IS DISTINCT FROM OLD.fl_tti_supplier  OR NEW.ds_vendor_code IS DISTINCT FROM OLD.ds_vendor_code THEN
            IF COALESCE(substring(NEW.ds_vendor_code FROM 1 FOR 1), '') = 'L' AND NEW.fl_tti_supplier = 'Y' THEN
                v_error_text = retDescTranslated('The Vendor code, when TTi, cannot have L in the begining. Please adjust to follow TTi Vendors Code'::text, null::integer);
                RAISE EXCEPTION '% (%)', v_error_text, 15648;
            END IF;

            IF COALESCE(substring(NEW.ds_vendor_code FROM 1 FOR 1), '') != 'L' AND NEW.fl_tti_supplier = 'N' THEN
                NEW.ds_vendor_code = 'L' || rtrim(ltrim(to_char(nextval('rfq."SUPPLIER_ds_vendor_sequence"'::regclass), '000000')));
            END IF;



        END IF;

    --delete
    ELSIF (TG_OP = 'DELETE') THEN


    --insert
    ELSIF (TG_OP = 'INSERT') THEN
        IF NEW.fl_tti_supplier = 'N' THEN
            NEW.ds_vendor_code = 'L' ||  rtrim(ltrim(to_char(nextval('rfq."SUPPLIER_ds_vendor_sequence"'::regclass), '000000')));
        END IF;


    ELSE
        RAISE EXCEPTION 'Trigger if_supplier_before added as trigger for unhandled case: %, %',TG_OP, TG_LEVEL;
        RETURN NULL;
    END IF;


   --updates ou insert
    IF (TG_OP = 'UPDATE' OR TG_OP = 'INSERT') THEN
        IF NEW.ds_vendor_code IS NULL THEN
            v_error_text = retDescTranslated('You must inform Vendor Code'::text, null::integer);
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
ALTER FUNCTION rfq.if_supplier_before() SET search_path=pg_catalog, public, spec, rfq;

ALTER FUNCTION rfq.if_supplier_before() OWNER TO postgres;

ALTER FUNCTION rfq.if_supplier_before() SET search_path=pg_catalog, public, rfq, translation;

/*

        CREATE TRIGGER t_if_supplier_before
          BEFORE UPDATE OR INSERT OR DELETE
          ON rfq."SUPPLIER"
          FOR EACH ROW
          EXECUTE PROCEDURE rfq.if_supplier_before();


*/

