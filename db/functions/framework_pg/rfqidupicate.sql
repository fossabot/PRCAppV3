
CREATE OR REPLACE FUNCTION rfq.rfqidupicate
(
    p_cd_rfq bigint
)
RETURNS bigint AS
$$
DECLARE
v_result text;
v_error_text text;
v_cd_rfq bigint;
v_cd_rfq_item bigint;
v_count int;
v_record_item record;
v_record_docrep record;
v_date_request timestamp;
v_code bigint;

BEGIN
/*
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
*/

    v_cd_rfq = nextval('rfq."RFQ_cd_rfq_seq"'::regclass);
    SELECT dt_request INTO v_date_request FROM  "RFQ" WHERE cd_rfq = p_cd_rfq;


    INSERT INTO rfq."RFQ"
        (cd_rfq, cd_human_resource_applicant, dt_request, dt_requested_complete, fl_is_urgent, cd_human_resource_purchase, ds_comments)
    SELECT v_cd_rfq, 
           get_var('cd_human_resource')::integer, 
           now(), 
           (now()::date + ( dt_requested_complete::date - dt_request::date))::timestamp, 
            fl_is_urgent, 
           cd_human_resource_purchase,
            ds_comments 
    FROM "RFQ"
   WHERE cd_rfq = p_cd_rfq;

   FOR v_record_item IN select * from "RFQ_ITEM" where cd_rfq = p_cd_rfq
   LOOP

        v_cd_rfq_item = nextval('rfq."RFQ_ITEM_cd_rfq_item_seq"'::regclass);
        

        INSERT INTO rfq."RFQ_ITEM"
        (cd_rfq_item, 
         cd_rfq, 
         cd_equipment_design, 
         cd_rfq_request_type, 
         ds_reason_buy, 
         nr_qtty_quote, 
         dt_deadline, 
         ds_website, 
         ds_remarks, 
         ds_attached_image, 
         nr_estimated_annual, 
         cd_unit_measure, 
         ds_po_number, 
         fl_need_sample, 
         ds_equipment_design_code_complement, 
         ds_equipment_design_desc_complement, 
         ds_brand, 
         dt_supplier_visit_deadline )
     VALUES (
             v_cd_rfq_item, 
             v_cd_rfq, 
             v_record_item.cd_equipment_design, 
             v_record_item.cd_rfq_request_type, 
             v_record_item.ds_reason_buy, 
             v_record_item.nr_qtty_quote, 
             (now()::date + ( v_record_item.dt_deadline::date - v_date_request::date))::timestamp,
             v_record_item.ds_website, 
             v_record_item.ds_remarks, 
             v_record_item.ds_attached_image, 
             v_record_item.nr_estimated_annual, 
             v_record_item.cd_unit_measure, 
             v_record_item.ds_po_number, 
             v_record_item.fl_need_sample, 
             v_record_item.ds_equipment_design_code_complement, 
             v_record_item.ds_equipment_design_desc_complement, 
             v_record_item.ds_brand, 
             (now()::date + ( v_record_item.dt_supplier_visit_deadline::date - v_date_request::date))::timestamp
            );


            FOR v_record_docrep IN                 
                SELECT *
                  FROM "RFQ_ITEM_DOCUMENT_REPOSITORY", "DOCUMENT_REPOSITORY"
                 WHERE cd_rfq_item  = v_record_item.cd_rfq_item
                   AND "DOCUMENT_REPOSITORY".cd_document_repository = "RFQ_ITEM_DOCUMENT_REPOSITORY".cd_document_repository 
                 ORDER BY "RFQ_ITEM_DOCUMENT_REPOSITORY".dt_record
            LOOP

                v_code = nextval('docrep."DOCUMENT_REPOSITORY_cd_document_repository_seq"'::regclass);

                INSERT INTO docrep."DOCUMENT_REPOSITORY"
                    (cd_document_repository, ds_document_repository, ds_original_file, cd_document_repository_type, cd_document_file)
                VALUES
                    (v_code, v_record_docrep.ds_document_repository, v_record_docrep.ds_original_file, v_record_docrep.cd_document_repository_type, v_record_docrep.cd_document_file);


                -- insert the new equipemnt's document repository in case it exists;
                INSERT INTO docrep."RFQ_ITEM_DOCUMENT_REPOSITORY"  (cd_rfq_item, cd_document_repository)    
                VALUES (v_cd_rfq_item, v_code);

            END LOOP;
   END LOOP;

   INSERT INTO rfq."RFQ_APPROVAL_STEPS"
    (cd_rfq, cd_approval_steps_config)
    SELECT v_cd_rfq, 
           cd_approval_steps_config
      FROM "APPROVAL_STEPS_CONFIG"
     WHERE ds_approval_steps_config_type = 'RFQ'
   ORDER BY nr_order 
   LIMIT 1;
          
          
      








return v_cd_rfq;
END;
$$
LANGUAGE plpgsql;

ALTER FUNCTION rfq.rfqidupicate(bigint) SET search_path=audit, public, translation, rfq, docrep;