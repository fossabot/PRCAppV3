
CREATE OR REPLACE FUNCTION rfq.addRfqGroup(vcd_rfq bigint)
  RETURNS void    AS
$$
DECLARE 
v_error_text       text;
vrec               record;
vcd_rfq_pr_group   bigint;
BEGIN

    IF EXISTS ( SELECT 1 FROM  "RFQ_PR_GROUP"  WHERE cd_rfq = vcd_rfq AND ds_pr_number IS NOT NULL) THEN
        v_error_text = retDescTranslated('Cannot Refresh PR information because it already has number. Please contact IT support (Internal Request # '|| vcd_rfq::text ||')'::text, null::integer);
        RAISE EXCEPTION '% (%)', v_error_text, 15648;
        return;
    END IF;

    DELETE FROM "RFQ_PR_GROUP" WHERE cd_rfq = vcd_rfq;


  FOR vrec IN 
        SELECT i.cd_rfq,
                s.cd_supplier,
                q.cd_currency,
                c.cd_department_cost_center,
                
                (CASE WHEN d.fl_demand_project = 'Y' THEN c.ds_project_number ELSE NULL END) as ds_project_number,
                (CASE WHEN d.fl_demand_project = 'Y' THEN c.ds_project_model_number ELSE NULL END) as ds_project_model_number,
                sum(c.nr_qtty_to_charge) as nr_total_qty,
                sum(c.nr_qtty_to_charge * nr_price) as nr_total_price,
                sum( ROUND( ( q.nr_price *  ( ( 100 + s.nr_tax ) / 100) ) * c.nr_qtty_to_charge, 2 ) ) as nr_total_price_with_tax,
                sum( ROUND( (q.nr_price *  COALESCE(r.nr_currency_rate , 1)) * c.nr_qtty_to_charge, 2  )) as nr_total_price_rmb,
                sum( ROUND( (q.nr_price *  COALESCE(r.nr_currency_rate , 1) * ( ( 100 + s.nr_tax ) / 100)) * c.nr_qtty_to_charge, 2 ) ) as nr_total_price_rmb_with_tax,
                array_agg(c.cd_rfq_cost_center) as array_cost_centers


        FROM "RFQ_ITEM" i
        JOIN "RFQ_ITEM_SUPPLIER"           s ON (s.cd_rfq_item                    = i.cd_rfq_item)
        JOIN "RFQ_ITEM_SUPPLIER_QUOTATION" q ON (q.cd_rfq_item_supplier           = s.cd_rfq_item_supplier)
        JOIN "RFQ_COST_CENTER"             c ON (c.cd_rfq_item                    = i.cd_rfq_item)
        JOIN "DEPARTMENT_COST_CENTER"      d ON (d.cd_department_cost_center      = c.cd_department_cost_center)
        
       LEFT OUTER JOIN "CURRENCY_RATE"     r ON (r.cd_currency_rate               = q.cd_currency_rate )
   WHERE i.cd_rfq = vcd_rfq
     AND COALESCE(q.nr_qtty_to_buy, 0)  > 0
   GROUP BY i.cd_rfq,
              s.cd_supplier,
              q.cd_currency,
              c.cd_department_cost_center,
              (CASE WHEN d.fl_demand_project = 'Y' THEN c.ds_project_number ELSE NULL END),
              (CASE WHEN d.fl_demand_project = 'Y' THEN c.ds_project_model_number ELSE NULL END)
     LOOP


     vcd_rfq_pr_group = nextval('"RFQ_PR_GROUP_cd_rfq_pr_group_seq"'::regclass);


    INSERT INTO rfq."RFQ_PR_GROUP" ( cd_rfq_pr_group, 
                                     cd_rfq, 
                                     cd_supplier, 
                                     cd_currency, 
                                     cd_department_cost_center, 
                                     ds_project_number, 
                                     ds_project_model_number, 
                                     nr_total_qty, 
                                     nr_total_price, 
                                     nr_total_price_with_tax, 
                                     nr_total_price_rmb, 
                                     nr_total_price_rmb_with_tax)
    
    VALUES (vcd_rfq_pr_group ,
            vrec.cd_rfq, 
            vrec.cd_supplier, 
            vrec.cd_currency, 
            vrec.cd_department_cost_center, 
            vrec.ds_project_number, 
            vrec.ds_project_model_number, 
            vrec.nr_total_qty, 
            vrec.nr_total_price, 
            vrec.nr_total_price_with_tax, 
            vrec.nr_total_price_rmb, 
            vrec.nr_total_price_rmb_with_tax
          );


    UPDATE "RFQ_COST_CENTER" SET cd_rfq_pr_group = vcd_rfq_pr_group WHERE cd_rfq_cost_center = ANY(vrec.array_cost_centers);


    END LOOP;


INSERT INTO rfq."RFQ_PR_GROUP_DISTRIBUTION"
    (cd_rfq_pr_group_distribution, cd_rfq_pr_group, cd_rfq_item_supplier_quotation, cd_rfq_item, nr_total_qty, nr_total_price, nr_total_price_with_tax, nr_total_price_rmb, nr_total_price_rmb_with_tax)


END
$$  LANGUAGE plpgsql;

ALTER FUNCTION rfq.addRfqGroup(bigint) SET search_path=pg_catalog, public, spec, rfq, translation;
