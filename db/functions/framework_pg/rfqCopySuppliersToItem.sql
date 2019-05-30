
CREATE OR REPLACE FUNCTION rfq.rfqCopySuppliersToItem(vcd_rfq_item_to bigint)
  RETURNS void    AS
$$
DECLARE 

v_cd_rfq bigint;

BEGIN

    SELECT cd_rfq    
      INTO v_cd_rfq 
      FROM "RFQ_ITEM" 
     WHERE cd_rfq_item = vcd_rfq_item_to;

    INSERT INTO rfq."RFQ_ITEM_SUPPLIER"
    (cd_rfq_item, 
     cd_supplier, 
     ds_supplier_equipment_description, 
     ds_supplier_equipment_part_number, 
      nr_tax
   )

    SELECT vcd_rfq_item_to, a.cd_supplier, min(a.ds_supplier_equipment_description), min(a.ds_supplier_equipment_part_number), min(nr_tax)
      FROM rfq."RFQ_ITEM_SUPPLIER" a, rfq."RFQ_ITEM" b
     WHERE b.cd_rfq      = v_cd_rfq
       AND a.cd_rfq_item = b.cd_rfq_item
       AND NOT EXISTS ( SELECT 1 FROM rfq."RFQ_ITEM_SUPPLIER" x WHERE x.cd_rfq_item = vcd_rfq_item_to AND x.cd_supplier = a.cd_supplier )
       GROUP BY vcd_rfq_item_to, a.cd_supplier;

END
$$  LANGUAGE plpgsql;
