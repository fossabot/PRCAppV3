
CREATE OR REPLACE FUNCTION rfq.getRfqItemSampleInformation
(
    p_cd_rfq_item bigint
)
RETURNS text AS
$$
DECLARE
v_result text;
v_count int;
BEGIN
    
    

    SELECT datedbtogrid(min("RFQ_ITEM_SUPPLIER_SAMPLE_REQUEST".dt_requested)::timestamp) || ' - ' || trim(to_char(COALESCE(sum("RFQ_ITEM_SUPPLIER_SAMPLE_REQUEST".nr_quantity), 0), '9999990')), count(1)
      INTO v_result, v_count 
      FROM "RFQ_ITEM_SUPPLIER" , "RFQ_ITEM_SUPPLIER_SAMPLE_REQUEST"
     WHERE "RFQ_ITEM_SUPPLIER".cd_rfq_item = p_cd_rfq_item 
      AND "RFQ_ITEM_SUPPLIER_SAMPLE_REQUEST".cd_rfq_item_supplier = "RFQ_ITEM_SUPPLIER".cd_rfq_item_supplier ;
    
    IF v_count = 0 THEN
        v_result = null;
    END IF;
      


return v_result;
END;
$$
LANGUAGE plpgsql STABLE;

