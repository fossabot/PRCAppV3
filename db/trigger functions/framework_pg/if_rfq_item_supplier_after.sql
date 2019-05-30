-- Function: audit.if_tablelog()

-- DROP FUNCTION audit.if_tablelog();


CREATE OR REPLACE FUNCTION rfq.if_rfq_item_supplier_after()
  RETURNS trigger AS
$BODY$
DECLARE
    v_error_text text;
    v_error_code int;
    v_default_currency int;
    v_new_cd_currency int;
    v_old_cd_currency int;
    v_cd_currency_rate int;
    v_nr_tax numeric(18,2);
    v_cd_rfq bigint;


BEGIN
    -- start without error!
    v_error_code = 0;
    v_default_currency = getSysParameter('DEFAULT_CURRENCY_RFQ')::integer;
    v_cd_currency_rate = NULL;
   --updates

    

    IF (TG_OP = 'UPDATE') THEN
        SELECT i.cd_rfq INTO v_cd_rfq FROM "RFQ_ITEM" i WHERE i.cd_rfq_item = NEW.cd_rfq_item;

    --delete
    ELSIF (TG_OP = 'DELETE') THEN
        SELECT i.cd_rfq INTO v_cd_rfq FROM "RFQ_ITEM" i WHERE i.cd_rfq_item = OLD.cd_rfq_item;

    -- cleaning up
    IF NOT EXISTS ( SELECT 1  FROM "RFQ_ITEM_SUPPLIER", "RFQ_ITEM" WHERE "RFQ_ITEM".cd_rfq = v_cd_rfq AND "RFQ_ITEM_SUPPLIER".cd_rfq_item = "RFQ_ITEM".cd_rfq_item AND "RFQ_ITEM_SUPPLIER".cd_supplier = OLD.cd_supplier) THEN
        DELETE FROM "RFQ_SUPPLIER" WHERE cd_rfq = v_cd_rfq AND cd_supplier = OLD.cd_supplier;
    END IF;


    --insert
    ELSIF (TG_OP = 'INSERT') THEN
        SELECT i.cd_rfq INTO v_cd_rfq FROM "RFQ_ITEM" i WHERE i.cd_rfq_item = NEW.cd_rfq_item;
    ELSE
        RAISE EXCEPTION 'Trigger func added as trigger for unhandled case: %, %',TG_OP, TG_LEVEL;
        RETURN NULL;
    END IF;


   --updates ou insert
    IF (TG_OP = 'UPDATE' OR TG_OP = 'INSERT') THEN
        v_nr_tax  = 0;

        SELECT nr_tax INTO v_nr_tax  FROM "RFQ_SUPPLIER" WHERE cd_rfq = v_cd_rfq AND cd_supplier = NEW.cd_supplier;

        IF NOT FOUND THEN
            INSERT INTO "RFQ_SUPPLIER" (cd_rfq, cd_supplier, nr_tax  ) values (v_cd_rfq, NEW.cd_supplier, NEW.nr_tax );
        END IF;

        IF v_nr_tax  != NEW.nr_tax THEN
            UPDATE "RFQ_SUPPLIER" SET nr_tax = NEW.nr_tax WHERE cd_rfq = v_cd_rfq AND cd_supplier = NEW.cd_supplier;
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
ALTER FUNCTION rfq.if_rfq_item_supplier_after() SET search_path=pg_catalog, public, spec, rfq;

ALTER FUNCTION rfq.if_rfq_item_supplier_after() OWNER TO postgres;

/*

    CREATE TRIGGER t_if_rfq_item_supplier_after
      AFTER UPDATE OR INSERT OR DELETE
      ON rfq."RFQ_ITEM_SUPPLIER"
      FOR EACH ROW
      EXECUTE PROCEDURE rfq.if_rfq_item_supplier_after();

*/