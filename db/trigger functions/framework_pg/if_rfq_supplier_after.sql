-- Function: audit.if_tablelog()

-- DROP FUNCTION audit.if_tablelog();


CREATE OR REPLACE FUNCTION rfq.if_rfq_supplier_after()
  RETURNS trigger AS
$BODY$
DECLARE
    v_error_text text;
    v_error_code int;
    v_default_currency int;
    v_new_cd_supplier int;
    v_old_cd_supplier int;
    v_cd_currency_rate int;
    v_nr_tax numeric(18,2);


BEGIN
    -- start without error!
    v_error_code = 0;
    v_default_currency = getSysParameter('DEFAULT_CURRENCY_RFQ')::integer;
    v_cd_currency_rate = NULL;
   --updates
    IF (TG_OP = 'UPDATE') THEN
        IF NEW.nr_tax != OLD.nr_tax THEN
            UPDATE "RFQ_ITEM_SUPPLIER"
             SET nr_tax = NEW.nr_tax 
            FROM "RFQ_ITEM" i
           WHERE i.cd_rfq                        = NEW.cd_rfq
             AND "RFQ_ITEM_SUPPLIER".cd_rfq_item = i.cd_rfq_item
             AND "RFQ_ITEM_SUPPLIER".cd_supplier = NEW.cd_supplier
             AND COALESCE("RFQ_ITEM_SUPPLIER".nr_tax, -1) != NEW.nr_tax;

            UPDATE "SUPPLIER" SET nr_tax_default = NEW.nr_tax WHERE cd_supplier = NEW.cd_supplier AND nr_tax_default != NEW.nr_tax  AND COALESCE(NEW.nr_tax, 0) != 0 ;


        END IF;

        v_new_cd_supplier = NEW.cd_supplier;
        v_old_cd_supplier = OLD.cd_supplier;

    --delete
    ELSIF (TG_OP = 'DELETE') THEN
        DELETE FROM  "RFQ_ITEM_SUPPLIER"
        USING "RFQ_ITEM"

         WHERE "RFQ_ITEM".cd_rfq = OLD.cd_rfq
           AND "RFQ_ITEM_SUPPLIER".cd_rfq_item = "RFQ_ITEM".cd_rfq_item
           AND "RFQ_ITEM_SUPPLIER".cd_supplier = OLD.cd_supplier;

    --insert
    ELSIF (TG_OP = 'INSERT') THEN
        v_new_cd_supplier = NEW.cd_supplier;
        v_old_cd_supplier = -1;
        
        UPDATE "SUPPLIER" SET nr_tax_default = NEW.nr_tax WHERE cd_supplier = NEW.cd_supplier AND nr_tax_default != NEW.nr_tax AND COALESCE(NEW.nr_tax, 0) != 0  ;



        
    ELSE
        RAISE EXCEPTION 'Trigger func added as trigger for unhandled case: %, %',TG_OP, TG_LEVEL;
        RETURN NULL;
    END IF;


   --updates ou insert
    IF (TG_OP = 'UPDATE' OR TG_OP = 'INSERT') THEN
        
        IF v_new_cd_supplier != v_old_cd_supplier THEN
            -- update the old with new, in case it existed before.
            UPDATE "RFQ_ITEM_SUPPLIER"
                SET cd_supplier = v_new_cd_supplier
               FROM "RFQ_ITEM"  
             WHERE "RFQ_ITEM".cd_rfq = NEW.cd_rfq
               AND "RFQ_ITEM_SUPPLIER".cd_rfq_item = "RFQ_ITEM".cd_rfq_item
               AND "RFQ_ITEM_SUPPLIER".cd_supplier = v_old_cd_supplier;

        -- insert new supplier for all items
        INSERT INTO "RFQ_ITEM_SUPPLIER" (cd_rfq_item, cd_supplier, nr_tax  ) 
           SELECT i.cd_rfq_item, v_new_cd_supplier, NEW.nr_tax
           FROM "RFQ_ITEM" i
            WHERE i.cd_rfq = NEW.cd_rfq
             AND NOT EXISTS ( SELECT 1 FROM "RFQ_ITEM_SUPPLIER" x where x.cd_rfq_item = i.cd_rfq_item AND x.cd_supplier = v_new_cd_supplier );

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
ALTER FUNCTION rfq.if_rfq_supplier_after() SET search_path=pg_catalog, public, spec, rfq;

ALTER FUNCTION rfq.if_rfq_supplier_after() OWNER TO postgres;

/*

    CREATE TRIGGER t_if_rfq_supplier_after
      AFTER UPDATE OR INSERT OR DELETE
      ON rfq."RFQ_SUPPLIER"
      FOR EACH ROW
      EXECUTE PROCEDURE rfq.if_rfq_supplier_after();

*/