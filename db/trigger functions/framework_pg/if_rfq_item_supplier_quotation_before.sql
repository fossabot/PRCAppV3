-- Function: audit.if_tablelog()

-- DROP FUNCTION audit.if_tablelog();


CREATE OR REPLACE FUNCTION rfq.if_rfq_item_supplier_quotation_before()
  RETURNS trigger AS
$BODY$
DECLARE
    v_error_text text;
    v_error_code int;
    v_default_currency int;
    v_new_cd_currency int;
    v_old_cd_currency int;
    v_cd_currency_rate int;
    v_nr_price_cheapeast numeric(12,4);
    v_nr_price_actual   numeric(12,4);
    v_cd_rfq_item bigint;


BEGIN
    -- start without error!
    v_error_code = 0;
    v_default_currency = getSysParameter('DEFAULT_CURRENCY_RFQ')::integer;
    v_cd_currency_rate = NULL;
   --updates
    IF (TG_OP = 'UPDATE') THEN

        IF NEW.nr_qtty_to_buy > 0 AND COALESCE(NEW.nr_price, 0) != COALESCE(OLD. nr_price, 0) THEN
            NEW.nr_qtty_to_buy = 0;
            NEW.ds_reason_to_choose_supplier = NULL;
        END IF;

        v_new_cd_currency  = NEW.cd_currency;
        v_old_cd_currency  = OLD.cd_currency;

        SELECT cd_rfq_item INTO v_cd_rfq_item
          FROM  "RFQ_ITEM_SUPPLIER_QUOTATION" x,
                "RFQ_ITEM_SUPPLIER" a 
         WHERE x.cd_rfq_item_supplier_quotation = NEW.cd_rfq_item_supplier_quotation
           AND a.cd_rfq_item_supplier = x.cd_rfq_item_supplier;



        IF COALESCE(NEW.nr_price, 0) > 0 AND COALESCE(OLD.nr_qtty_to_buy, 0) = 0 AND COALESCE(NEW.nr_qtty_to_buy, 0) > 0 THEN

            IF EXISTS ( SELECT 1
                FROM "RFQ_ITEM_SUPPLIER_QUOTATION"
                JOIN "RFQ_ITEM_SUPPLIER" ON ("RFQ_ITEM_SUPPLIER".cd_rfq_item_supplier = "RFQ_ITEM_SUPPLIER_QUOTATION".cd_rfq_item_supplier )
              WHERE "RFQ_ITEM_SUPPLIER_QUOTATION".cd_rfq_item_supplier_quotation = NEW.cd_rfq_item_supplier_quotation
                AND EXISTS     ( SELECT 1 FROM "RFQ_ITEM_SUPPLIER_SAMPLE_REQUEST" x WHERE x.cd_rfq_item_supplier = "RFQ_ITEM_SUPPLIER".cd_rfq_item_supplier )
                AND NOT EXISTS ( SELECT 1 FROM "RFQ_ITEM_SUPPLIER_SAMPLE_REQUEST" x WHERE x.cd_rfq_item_supplier = "RFQ_ITEM_SUPPLIER".cd_rfq_item_supplier and x.cd_approval_status = 1)
                     ) THEN

                    v_error_text = retDescTranslated('You cannot choose this Supplier because there is Sample not Approved'::text, null::integer);
                    RAISE EXCEPTION '% (%)', v_error_text, 15648;
            END IF;
        END IF;
        
 

        -- if the reason is not informed, need to make sure the selected price is the cheapest one.
        IF COALESCE(NEW.ds_reason_to_choose_supplier, '') = '' AND COALESCE(NEW.nr_qtty_to_buy, 0) > 0 AND ( COALESCE(NEW.ds_reason_to_choose_supplier, '') != COALESCE(OLD.ds_reason_to_choose_supplier, '') OR COALESCE(NEW.nr_qtty_to_buy, 0) != COALESCE(OLD.nr_qtty_to_buy, 0) OR COALESCE(NEW.nr_price, 0) != COALESCE(OLD. nr_price, 0) )  THEN

            -- cheapest
            SELECT x.nr_price 
              INTO v_nr_price_cheapeast
            FROM  "RFQ_ITEM" i,         
                  "RFQ_SUPPLIER" s,
                  "RFQ_ITEM_SUPPLIER" a , 
                  "RFQ_ITEM_SUPPLIER_QUOTATION" x
             LEFT OUTER JOIN "CURRENCY_RATE" ON ("CURRENCY_RATE".cd_currency_rate = x.cd_currency_rate )

           WHERE i.cd_rfq_item = v_cd_rfq_item
             AND s.cd_rfq      = i.cd_rfq

             AND a.cd_rfq_item = v_cd_rfq_item
             AND a.cd_supplier = s.cd_supplier

             AND x.cd_rfq_item_supplier = a.cd_rfq_item_supplier 
             AND x.nr_round             = s.nr_round
             AND  x.nr_price > 0

         ORDER BY x.nr_price
         LIMIT 1;

        -- actual

         IF NEW.nr_price > v_nr_price_cheapeast THEN
            v_error_text = retDescTranslated('You are not choosing the Cheapest Supplier, so you need to inform Reason'::text, null::integer);
            RAISE EXCEPTION '% (%)', v_error_text, 15648;
         END IF;


        END IF;
        




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
        NEW.dt_update = NOW();

        IF v_new_cd_currency != v_old_cd_currency THEN

            IF NEW.cd_currency = v_default_currency THEN
                NEW.cd_currency_rate = NULL;
            ELSE
                SELECT cd_currency_rate 
                  INTO v_cd_currency_rate 
                  FROM "CURRENCY_RATE"
                 WHERE cd_currency_from = v_new_cd_currency
                   AND cd_currency_to   = v_default_currency
                   AND dt_currency_rate <= NEW.dt_record
                   ORDER BY dt_currency_rate desc
                   LIMIT 1;
                
                NEW.cd_currency_rate = v_cd_currency_rate;

            END IF;

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
ALTER FUNCTION rfq.if_rfq_item_supplier_quotation_before() SET search_path=pg_catalog, public, spec, rfq, translation;

ALTER FUNCTION rfq.if_rfq_item_supplier_quotation_before() OWNER TO postgres;

/*

    CREATE TRIGGER t_if_rfq_item_supplier_quotation_before
      BEFORE UPDATE OR INSERT OR DELETE
      ON rfq."RFQ_ITEM_SUPPLIER_QUOTATION"
      FOR EACH ROW
      EXECUTE PROCEDURE rfq.if_rfq_item_supplier_quotation_before();

*/