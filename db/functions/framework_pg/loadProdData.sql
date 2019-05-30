
CREATE OR REPLACE FUNCTION rfq.loadProdData(pcd_rfq_pr_group bigint)
  RETURNS void    AS
$$
DECLARE 
v_error_text       text;
vrec               record;
vbucketrec         record;
vcd_rfq            bigint;  
vtotal_to_bucket numeric(18,2);
vtotal_to_return numeric(18,2);
vcount          bigint;

BEGIN

    SELECT cd_rfq into vcd_rfq FROM "RFQ_PR_GROUP" where cd_rfq_pr_group = pcd_rfq_pr_group;

    DELETE FROM "RFQ_PR_GROUP_DISTRIBUTION" WHERE cd_rfq_pr_group = pcd_rfq_pr_group;

    CREATE TEMPORARY SEQUENCE IF NOT EXISTS tmpGroup;

    drop table if exists tmpBucket;
    drop table if exists tmpTotal;
    drop table if exists tmpDistributed;


    -- total to distribute
    CREATE TEMP TABLE  IF NOT EXISTS tmpTotal  as
    SELECT  nextval('tmpGroup'::regclass) as cd_tmptotal,
            i.cd_rfq_item,
                s.cd_supplier,
                q.cd_currency,
                q.ds_kind,
                sum(q.nr_qtty_to_buy) as nr_qtty_to_buy
    FROM rfq."RFQ_ITEM" i
                JOIN rfq."RFQ_ITEM_SUPPLIER"           s ON (s.cd_rfq_item                    = i.cd_rfq_item)
                JOIN rfq."RFQ_ITEM_SUPPLIER_QUOTATION" q ON (q.cd_rfq_item_supplier           = s.cd_rfq_item_supplier)
    WHERE i.cd_rfq = vcd_rfq AND q.nr_qtty_to_buy > 0 
    group by i.cd_rfq_item,
                 s.cd_supplier,
                 q.cd_currency ,
                 q.ds_kind
    ORDER BY nr_qtty_to_buy;


    -- information of who will receive what
    CREATE TEMP TABLE  IF NOT EXISTS tmpBucket as
    SELECT nextval('tmpGroup'::regclass) as cd_tmpbucket,
            i.cd_rfq_item,
            c.cd_department_cost_center,
            (CASE WHEN d.fl_demand_project = 'Y' THEN c.ds_project_number ELSE NULL END) as ds_project_number,
             (CASE WHEN d.fl_demand_project = 'Y' THEN c.ds_project_model_number ELSE NULL END) as ds_project_model_number,
             sum(c.nr_qtty_to_charge) as nr_qtty_to_charge
            FROM rfq."RFQ_ITEM" i
            JOIN rfq."RFQ_COST_CENTER"             c ON (c.cd_rfq_item                    = i.cd_rfq_item)
            JOIN rfq."DEPARTMENT_COST_CENTER"      d ON (d.cd_department_cost_center      = c.cd_department_cost_center)
       WHERE i.cd_rfq = vcd_rfq
       GROUP BY i.cd_rfq_item,
                  c.cd_department_cost_center,
                  (CASE WHEN d.fl_demand_project = 'Y' THEN c.ds_project_number ELSE NULL END),
                  (CASE WHEN d.fl_demand_project = 'Y' THEN c.ds_project_model_number ELSE NULL END);



    CREATE TEMP TABLE tmpDistributed 
                 ( cd_rfq_item bigint, 
                   cd_supplier bigint, 
                   cd_currency bigint,  
                   ds_kind char(1),
                   cd_department_cost_center bigint,
                   ds_project_number text null,
                   ds_project_model_number text null,
                   nr_qtty_to_charge       numeric(18,2)
                );


    vcount = 0;

    WHILE EXISTS ( SELECT 1 from tmpTotal where nr_qtty_to_buy > 0 ) LOOP
        vcount = vcount+ 1;
        FOR vrec IN 
            SELECT * FROM tmpTotal WHERE nr_qtty_to_buy > 0 ORDER BY nr_qtty_to_buy DESC
        LOOP
            
            SELECT INTO vbucketrec * from tmpBucket WHERE tmpBucket.cd_rfq_item = vrec.cd_rfq_item order by nr_qtty_to_charge DESC LIMIT 1;

            


            --RAISE NOTICE '% Total  % - %',vcount,  vrec.cd_tmptotal, hstore(vrec);
            --RAISE NOTICE '% Bucket % - %',vcount,  vbucketrec.cd_tmpbucket, hstore(vbucketrec);


            IF vbucketrec.nr_qtty_to_charge >= vrec.nr_qtty_to_buy THEN
                vtotal_to_bucket = vrec.nr_qtty_to_buy;
                vtotal_to_return = 0;
            ELSE
                vtotal_to_bucket = vbucketrec.nr_qtty_to_charge;
                vtotal_to_return = vrec.nr_qtty_to_buy  - vbucketrec.nr_qtty_to_charge;
            END IF;

            insert into tmpDistributed 
                 ( cd_rfq_item, 
                   cd_supplier, 
                   cd_currency,
                   cd_department_cost_center,
                   ds_project_number,
                   ds_project_model_number,
                   nr_qtty_to_charge,
                   ds_kind
                )
            values ( vrec.cd_rfq_item,
                     vrec.cd_supplier,
                     vrec.cd_currency, 
                     vbucketrec.cd_department_cost_center,
                     vbucketrec.ds_project_number,
                     vbucketrec.ds_project_model_number,
                     vtotal_to_bucket,
                     vrec.ds_kind
                    );
            
        UPDATE tmpBucket SET nr_qtty_to_charge = nr_qtty_to_charge - vtotal_to_bucket WHERE cd_tmpbucket = vbucketrec.cd_tmpbucket ;
        UPDATE tmpTotal  SET nr_qtty_to_buy = vtotal_to_return WHERE cd_tmptotal = vrec.cd_tmptotal ;

        --RAISE Notice 'Totals Buy %, Total Charge %, Total Charging %, Return to Total %', vrec.nr_qtty_to_buy, vbucketrec.nr_qtty_to_charge, vtotal_to_bucket, vtotal_to_return;

      END LOOP;

      
      

      IF vcount > 200 THEN
        RAISE EXCEPTION  'Stopped wrongly %', vbucketrec;
        exit;
      END IF;   

      --RAISE Notice 'Outer Loop';

    END LOOP ; 
  

   
   -- INSERT THE DISTRIBUTION INFORMATION.

 FOR vrec IN 
        SELECT  g.cd_rfq_pr_group,
                i.cd_rfq_item,
                q.cd_rfq_item_supplier_quotation,
                sum(nr_price) as nr_price,
                sum( ROUND( ( q.nr_price *  ( ( 100 + s.nr_tax ) / 100) ), 2 ) ) as nr_price_with_tax,
                sum(tmp.nr_qtty_to_charge) as nr_total_qty,
                sum(tmp.nr_qtty_to_charge * nr_price) as nr_total_price,
                sum( ROUND( ( q.nr_price *  ( ( 100 + s.nr_tax ) / 100) ) * tmp.nr_qtty_to_charge, 2 ) ) as nr_total_price_with_tax,
                sum( ROUND( (q.nr_price *  COALESCE(r.nr_currency_rate , 1)) * tmp.nr_qtty_to_charge, 2  )) as nr_total_price_rmb,
                sum( ROUND( (q.nr_price *  COALESCE(r.nr_currency_rate , 1) * ( ( 100 + s.nr_tax ) / 100)) * tmp.nr_qtty_to_charge, 2 ) ) as nr_total_price_rmb_with_tax,
                sum( ROUND( q.nr_price *  COALESCE(r.nr_currency_rate , 1), 2  )) as nr_price_default_currency,
                sum( ROUND( q.nr_price *  COALESCE(r.nr_currency_rate , 1) * ( ( 100 + s.nr_tax ) / 100), 2 ) ) as nr_price_with_tax_default_currency

        FROM rfq."RFQ_PR_GROUP" g

        JOIN tmpDistributed              tmp ON (  
                tmp.cd_supplier               = g.cd_supplier  AND
                tmp.cd_currency               = g.cd_currency  AND
                tmp.cd_department_cost_center = g.cd_department_cost_center  AND
                tmp.ds_project_number         IS NOT DISTINCT FROM g.ds_project_number  AND
                tmp.ds_project_model_number   IS NOT DISTINCT FROM  g.ds_project_model_number  AND
                tmp.ds_kind                   =  g.ds_kind  
         )
        JOIN "RFQ_ITEM"                    i ON ( i.cd_rfq_item                   = tmp.cd_rfq_item)
        JOIN "RFQ_ITEM_SUPPLIER"           s ON (s.cd_rfq_item                    = i.cd_rfq_item )
        JOIN "RFQ_ITEM_SUPPLIER_QUOTATION" q ON (q.cd_rfq_item_supplier           = s.cd_rfq_item_supplier)
        JOIN "RFQ_COST_CENTER"             c ON (c.cd_rfq_item                    = i.cd_rfq_item)
        JOIN "DEPARTMENT_COST_CENTER"      d ON (d.cd_department_cost_center      = c.cd_department_cost_center)
        
       LEFT OUTER JOIN "CURRENCY_RATE"     r ON (r.cd_currency_rate               = q.cd_currency_rate )
   WHERE g.cd_rfq_pr_group           =  pcd_rfq_pr_group
     -- basic connections
     AND s.cd_supplier = tmp.cd_supplier 
     AND c.cd_department_cost_center    = tmp.cd_department_cost_center
     AND q.cd_currency                  = tmp.cd_currency
     AND (CASE WHEN d.fl_demand_project = 'Y' THEN c.ds_project_number ELSE NULL END) IS NOT DISTINCT FROM tmp.ds_project_number
     AND (CASE WHEN d.fl_demand_project = 'Y' THEN c.ds_project_model_number ELSE NULL END) IS NOT DISTINCT FROM tmp.ds_project_model_number
     AND q.ds_kind                      = tmp.ds_kind
     AND COALESCE(q.nr_qtty_to_buy, 0)  > 0
     
   GROUP BY g.cd_rfq_pr_group,
                i.cd_rfq_item,
                q.cd_rfq_item_supplier_quotation
    LOOP

        INSERT INTO rfq."RFQ_PR_GROUP_DISTRIBUTION"
            (cd_rfq_pr_group, 
             cd_rfq_item_supplier_quotation, 
             cd_rfq_item, 
             nr_qtty_to_charge, 
             nr_price,
             nr_price_with_tax,


             nr_total_price, 
             nr_total_price_with_tax, 
             nr_total_price_rmb, 
             nr_total_price_rmb_with_tax,

            nr_price_with_tax_default_currency,
            nr_price_default_currency


           )

    VALUES (vrec.cd_rfq_pr_group ,
            vrec.cd_rfq_item_supplier_quotation, 
            vrec.cd_rfq_item, 
            vrec.nr_total_qty, 
             vrec.nr_price,
             vrec.nr_price_with_tax,

            vrec.nr_total_price, 
            vrec.nr_total_price_with_tax, 
            vrec.nr_total_price_rmb, 
            vrec.nr_total_price_rmb_with_tax,
            vrec.nr_price_with_tax_default_currency,
            vrec.nr_price_default_currency
           
          );           

   END LOOP;

END
$$  LANGUAGE plpgsql;

ALTER FUNCTION rfq.loadProdData(bigint) SET search_path=pg_catalog, public, spec, rfq, translation;
