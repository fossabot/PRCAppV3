CREATE OR REPLACE FUNCTION rfq.copyRfqSplliers()
  RETURNS void    AS
$$
DECLARE 
v_error_text          text;
vrec                  record;
vbucketrec            record;
vcd_rfq_pr_group      bigint;
vtotal_to_bucket      numeric(18,2);
vtotal_to_return      numeric(18,2);
vcount                bigint;
vcd_rfq_item_supplier bigint;
vcd_rfq_item_supplier_quotation bigint;
vcd_rfq               bigint;
v_code                bigint;

BEGIN

SELECT cd_rfq INTO vcd_rfq
 FROM datasupplier d, "RFQ_ITEM" i
WHERE i.cd_rfq_item = d.item LIMIT 1;

INSERT INTO "RFQ_ITEM_SUPPLIER" (cd_rfq_item, cd_supplier, nr_tax)
SELECT distinct d.item, d.sup, s.nr_tax
  FROM datasupplier d, "RFQ_ITEM_SUPPLIER_QUOTATION" q, "RFQ_ITEM_SUPPLIER" s
WHERE q.cd_rfq_item_supplier_quotation = d.quot
 AND s.cd_rfq_item_supplier = q.cd_rfq_item_supplier;

  -- insert only the new ones
  FOR vrec IN SELECT q.* , d.*
                FROM datasupplier d,
                     "RFQ_ITEM_SUPPLIER_QUOTATION" q,
                     "RFQ_SUPPLIER" s
               WHERE q.cd_rfq_item_supplier_quotation = d.quot
                 AND s.cd_rfq      = vcd_rfq
                 AND s.cd_supplier = d.sup
                 AND s.nr_round = 0
  LOOP

    SELECT cd_rfq_item_supplier INTO vcd_rfq_item_supplier  
      FROM "RFQ_ITEM_SUPPLIER" 
     WHERE cd_rfq_item = vrec.item 
       AND cd_supplier = vrec.sup;

    SELECT cd_rfq_item_supplier INTO vcd_rfq_item_supplier_quotation  
      FROM "RFQ_ITEM_SUPPLIER_QUOTATION" 
     WHERE cd_rfq_item_supplier = vcd_rfq_item_supplier ;

        INSERT INTO rfq."RFQ_ITEM_SUPPLIER_QUOTATION"
         ( cd_rfq_item_supplier, 
           nr_round, 
           nr_price, 
           cd_currency, 
           nr_moq, 
          nr_leadtime, 
          dt_expiring_date, 
          cd_payment_term, 
          nr_warranty, 
          ds_kind, 
          ds_remarks, 
          cd_currency_rate
        )
         VALUES (vcd_rfq_item_supplier,
                 1, 
                 vrec.nr_price, 
                 vrec.cd_currency, 
                 vrec.nr_moq, 
                 vrec.nr_leadtime, 
                 vrec.dt_expiring_date, 
                 vrec.cd_payment_term, 
                 vrec.nr_warranty, 
                 vrec.ds_kind, 
                 vrec.ds_remarks, 
                 vrec.cd_currency_rate);

  END LOOP;


drop table if exists kindQuo;

create temporary table kindQuo (ds_kind char(1));

insert into kindQuo values('N'), ('R');

drop table if exists missingsup;

create temporary table missingsup as 
SELECT i.cd_rfq_item, s.cd_supplier
  FROM "RFQ_ITEM" i,
        "RFQ_SUPPLIER" s
 WHERE i.cd_rfq  = vcd_rfq
   AND s.cd_rfq  = vcd_rfq
   AND NOT EXISTS ( SELECT 1 FROM "RFQ_ITEM_SUPPLIER" x WHERE x.cd_rfq_item = i.cd_rfq_item AND x.cd_supplier = s.cd_supplier );


-- adding the others, just to make sure they are all basic
INSERT INTO "RFQ_ITEM_SUPPLIER" (cd_rfq_item, cd_supplier)
select cd_rfq_item, cd_supplier
  from missingsup;


INSERT INTO "RFQ_ITEM_SUPPLIER_QUOTATION" (cd_rfq_item_supplier, nr_round, cd_currency, ds_kind)
SELECT s.cd_rfq_item_supplier, 1, getSysParameter('DEFAULT_CURRENCY_RFQ')::integer, q.ds_kind
  FROM "RFQ_ITEM_SUPPLIER" s, "RFQ_ITEM" i, rfq."RFQ_REQUEST_TYPE" t, kindQuo q
 WHERE i.cd_rfq = vcd_rfq
   AND s.cd_rfq_item = i.cd_rfq_item
   AND t.cd_rfq_request_type = i.cd_rfq_request_type
   AND ( ( q.ds_kind = 'N' AND  t.fl_is_new = 'Y' ) OR ( q.ds_kind = 'R' AND  t.fl_is_repair = 'Y' ) )
   AND NOT EXISTS ( SELECT 1 FROM "RFQ_ITEM_SUPPLIER_QUOTATION" x WHERE x.cd_rfq_item_supplier = s.cd_rfq_item_supplier );
  



/*SELECT distinct cd_supplier,
  FROM "RFQ_ITEM" i,
       "RFQ_ITEM_SUPPLIER" s,
       "RFQ_ITEM_QUOTATION" q,
       "RFQ_SUPPLIER" rs
WHERE i.cd_rfq      = vcd_rfq
  AND s.cd_rfq_item = i.cd_rfq_item
  AND q.cd_rfq_item_supplier = s.cd_rfq_item_supplier
  AND rs.cd_rfq = vcd_rfq
  AND rs.cd_supplier = s.cd_supplier
  and rs.nr_round    = 0;*/


UPDATE "RFQ_SUPPLIER" 
   SET nr_round = 1 
 WHERE cd_rfq = vcd_rfq
  AND nr_round = 0;


    FOR vrec IN                 
        SELECT distinct ds_document_repository, ds_original_file, cd_document_repository_type, cd_document_file
          FROM docrep."RFQ_QUOTE_DATA_DOCUMENT_REPOSITORY", docrep."DOCUMENT_REPOSITORY", datasupplier d
         WHERE docrep."RFQ_QUOTE_DATA_DOCUMENT_REPOSITORY".cd_rfq = d.rfqcopy
           AND docrep."DOCUMENT_REPOSITORY".cd_document_repository = docrep."RFQ_QUOTE_DATA_DOCUMENT_REPOSITORY".cd_document_repository 
         --ORDER BY "RFQ_QUOTE_DATA_DOCUMENT_REPOSITORY".dt_record
    LOOP


        -- insert document repository to split the information. So any change on purchase request won't affect data on equipment
        v_code = nextval('docrep."DOCUMENT_REPOSITORY_cd_document_repository_seq"'::regclass);

        INSERT INTO docrep."DOCUMENT_REPOSITORY"
            (cd_document_repository, ds_document_repository, ds_original_file, cd_document_repository_type, cd_document_file)
        VALUES
            (v_code, vrec.ds_document_repository, vrec.ds_original_file, vrec.cd_document_repository_type, vrec.cd_document_file);


        -- insert the new equipemnt's document repository in case it exists;
        INSERT INTO docrep."RFQ_QUOTE_DATA_DOCUMENT_REPOSITORY"  (cd_rfq, cd_document_repository)    
        VALUES (vcd_rfq, v_code);

    END LOOP;


END
$$  LANGUAGE plpgsql;

ALTER FUNCTION rfq.copyRfqSplliers() SET search_path=pg_catalog, public, spec, rfq, translation;
