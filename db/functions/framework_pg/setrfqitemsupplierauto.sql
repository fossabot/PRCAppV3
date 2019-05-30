
CREATE OR REPLACE FUNCTION rfq.setrfqitemsupplierauto
(
    p_cd_rfq_item bigint, 
    p_nr_qtty_to_buy numeric(18,2)
)
RETURNS text AS
$$
DECLARE
v_result text;
v_error_text text;
v_cd_rfq_item_supplier_quotation bigint;
v_count int;
BEGIN

   IF EXISTS ( SELECT 1 
                 FROM  "RFQ_ITEM_SUPPLIER" a , 
                       "RFQ_ITEM_SUPPLIER_QUOTATION" x  
                WHERE a.cd_rfq_item = p_cd_rfq_item
                  AND x.cd_rfq_item_supplier = a.cd_rfq_item_supplier  
                  AND COALESCE(x.nr_qtty_to_buy, 0) > 0
             ) THEN

        v_error_text = retDescTranslated('Supplier already selected for this Item'::text, null::integer);
        RAISE EXCEPTION '% (%)', v_error_text, 15648;
    END IF;
    
    SELECT cd_rfq_item_supplier_quotation
      INTO v_cd_rfq_item_supplier_quotation
      FROM  "RFQ_ITEM" i,         
            "RFQ_SUPPLIER" s,
            "RFQ_ITEM_SUPPLIER" a , 
            "RFQ_ITEM_SUPPLIER_QUOTATION" x
       LEFT OUTER JOIN "CURRENCY_RATE" ON ("CURRENCY_RATE".cd_currency_rate = x.cd_currency_rate )

     WHERE i.cd_rfq_item = p_cd_rfq_item
       AND s.cd_rfq      = i.cd_rfq

       AND a.cd_rfq_item = p_cd_rfq_item    
       AND a.cd_supplier = s.cd_supplier

       AND x.cd_rfq_item_supplier = a.cd_rfq_item_supplier 
       AND x.nr_round             = s.nr_round
       AND  x.nr_price > 0

   ORDER BY x.nr_price, x.nr_leadtime, x.nr_warranty desc
   LIMIT 1;


   UPDATE "RFQ_ITEM_SUPPLIER_QUOTATION" SET nr_qtty_to_buy = p_nr_qtty_to_buy WHERE cd_rfq_item_supplier_quotation = v_cd_rfq_item_supplier_quotation;

    

   IF (SELECT count(1) FROM "RFQ_COST_CENTER" WHERE cd_rfq_item = p_cd_rfq_item) = 1 THEN
        UPDATE "RFQ_COST_CENTER" set nr_qtty_to_charge = p_nr_qtty_to_buy WHERE cd_rfq_item = p_cd_rfq_item;
        v_result = 'Done';
    ELSE
        v_result = 'Done BUT the system was not able to set the Quantity to Charge on department';
    END IF;

    v_result = retDescTranslated(v_result, null::integer);

return v_result;
END;
$$
LANGUAGE plpgsql;

ALTER FUNCTION rfq.setrfqitemsupplierauto(bigint, numeric) SET search_path=audit, public, translation, rfq;