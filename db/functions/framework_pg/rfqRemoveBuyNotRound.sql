
CREATE OR REPLACE FUNCTION rfq.rfqRemoveBuyNotRound
(
    p_cd_rfq bigint
)
RETURNS void AS
$$
DECLARE
v_result text;
BEGIN
    
UPDATE "RFQ_ITEM_SUPPLIER_QUOTATION" 
   SET nr_qtty_to_buy = 0
WHERE cd_rfq_item_supplier_quotation in (
    select q.cd_rfq_item_supplier_quotation 
    from "RFQ_ITEM" i, 
         "RFQ_ITEM_SUPPLIER" s, 
         "RFQ_ITEM_SUPPLIER_QUOTATION" q,
         "RFQ_SUPPLIER" rs,
         rfq."RFQ_APPROVAL_STEPS" steps,
         public."APPROVAL_STEPS_CONFIG" stepconf

    where i.cd_rfq    = p_cd_rfq
      and s.cd_rfq_item = i.cd_rfq_item
      and q.cd_rfq_item_supplier = s.cd_rfq_item_supplier
      and q.nr_qtty_to_buy > 0
      and rs.cd_rfq = i.cd_rfq
      and rs.cd_supplier = s.cd_supplier
      and q.nr_round < rs.nr_round
      and steps.cd_rfq = i.cd_rfq
      and stepconf.cd_approval_steps_config = steps.cd_approval_steps_config
      and stepconf.ds_system_permission_ids = 'fl_rfq_team_approval'
      and steps.dt_define IS NULL
    );


END;
$$
LANGUAGE plpgsql;

