-- Function: audit.if_tablelog()

-- DROP FUNCTION audit.if_tablelog();
                
CREATE OR REPLACE FUNCTION rfq.if_rfq_item_after()
  RETURNS trigger AS
$BODY$
DECLARE
    v_error_text text;
    v_error_code int;
    v_default_currency int;
    v_new_cd_currency int;
    v_old_cd_currency int;
    v_cd_currency_rate int;
    v_cd_default_pay  int;
    v_nr_vlr_min_with_tax numeric(18,2);
    v_nr_vlr_act_with_tax numeric(18,2);

    v_cd_rfq bigint;
    v_record record;
    v_cd_equipment_design_old bigint;
    v_cd_equipment_design_new bigint;
    v_code bigint;





BEGIN
    -- start without error!
    v_error_code = 0;
    v_default_currency = getSysParameter('DEFAULT_CURRENCY_RFQ')::integer;
    v_cd_default_pay = getSysParameter('DEFAULT_PAYMENT_TERM_RFQ')::integer;
    v_cd_currency_rate = NULL;
   --updates

    

    IF (TG_OP = 'UPDATE') THEN
        v_cd_equipment_design_old  = OLD.cd_equipment_design;
        v_cd_equipment_design_new  = NEW.cd_equipment_design;

    --delete
    ELSIF (TG_OP = 'DELETE') THEN

    --insert
    ELSIF (TG_OP = 'INSERT') THEN
        -- if inserting a new one and already have suppliers round.
        v_cd_equipment_design_old  = -1;
        v_cd_equipment_design_new  = NEW.cd_equipment_design;

        -- add all supplier;
        INSERT INTO "RFQ_ITEM_SUPPLIER" (cd_supplier, cd_rfq_item)
            SELECT distinct s.cd_supplier, NEW.cd_rfq_item
         FROM "RFQ_ITEM_SUPPLIER" s, "RFQ_ITEM" i
       WHERE i.cd_rfq               = NEW.cd_rfq
         AND s.cd_rfq_item          = i.cd_rfq_item;
    
        INSERT INTO "RFQ_ITEM_SUPPLIER_QUOTATION" (nr_round, cd_currency, cd_payment_term, nr_price, cd_rfq_item_supplier )
        SELECT distinct nr_round, v_default_currency, v_cd_default_pay, 0, ( SELECT cd_rfq_item_supplier FROM "RFQ_ITEM_SUPPLIER" x WHERE x.cd_rfq_item = NEW.cd_rfq_item AND x.cd_supplier = s.cd_supplier)
         FROM "RFQ_ITEM" i, "RFQ_ITEM_SUPPLIER" s, "RFQ_ITEM_SUPPLIER_QUOTATION" q
       WHERE i.cd_rfq                = NEW.cd_rfq
         AND s.cd_rfq_item          = i.cd_rfq_item
         AND q.cd_rfq_item_supplier = s.cd_rfq_item_supplier;
    
    ELSE
        RAISE EXCEPTION 'Trigger func added as trigger for unhandled case: %, %',TG_OP, TG_LEVEL;
        RETURN NULL;
    END IF;


   --updates ou insert
    IF (TG_OP = 'UPDATE' OR TG_OP = 'INSERT') THEN



        IF v_cd_equipment_design_old != v_cd_equipment_design_new THEN

            -- remove old document repository related to the old equipment.
            DELETE FROM docrep."RFQ_ITEM_DOCUMENT_REPOSITORY"
               WHERE cd_equipment_design = v_cd_equipment_design_old AND cd_rfq_item = NEW.cd_rfq_item;

            FOR v_record IN                 
                SELECT *
                  FROM "EQUIPMENT_DESIGN_DOCUMENT_REPOSITORY", "DOCUMENT_REPOSITORY"
                 WHERE cd_equipment_design = v_cd_equipment_design_new
                   AND "DOCUMENT_REPOSITORY".cd_document_repository = "EQUIPMENT_DESIGN_DOCUMENT_REPOSITORY".cd_document_repository 
                 ORDER BY "EQUIPMENT_DESIGN_DOCUMENT_REPOSITORY".dt_record
            LOOP

                

                -- insert document repository to split the information. So any change on purchase request won't affect data on equipment
                v_code = nextval('docrep."DOCUMENT_REPOSITORY_cd_document_repository_seq"'::regclass);

                INSERT INTO docrep."DOCUMENT_REPOSITORY"
                    (cd_document_repository, ds_document_repository, ds_original_file, cd_document_repository_type, cd_document_file)
                VALUES
                    (v_code, v_record.ds_document_repository, v_record.ds_original_file, v_record.cd_document_repository_type, v_record.cd_document_file);


                -- insert the new equipemnt's document repository in case it exists;
                INSERT INTO docrep."RFQ_ITEM_DOCUMENT_REPOSITORY"  (cd_rfq_item, cd_document_repository, cd_equipment_design)    
                VALUES (NEW.cd_rfq_item, v_code, NEW.cd_equipment_design);

            END LOOP;

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
ALTER FUNCTION rfq.if_rfq_item_after() SET search_path=pg_catalog, public, spec, rfq, translation, docrep;

ALTER FUNCTION rfq.if_rfq_item_after() OWNER TO postgres;

/*

    CREATE TRIGGER t_if_rfq_item_after
      AFTER UPDATE OR INSERT OR DELETE
      ON rfq."RFQ_ITEM"
      FOR EACH ROW
      EXECUTE PROCEDURE rfq.if_rfq_item_after();

*/
