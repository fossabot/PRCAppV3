CREATE OR REPLACE FUNCTION rfq.if_rfq_approval_steps_before()
  RETURNS trigger AS
$BODY$
DECLARE
    v_error_text text;
    v_text       text;
    v_error_code int;
    v_cd_approval_steps_config_next int;
    v_cd_approval_steps_config_to_jump int;
    v_nr_order_now int;
    v_ds_internal_code text;
    v_cd_approval_status_new bigint;
    v_cd_approval_status_old bigint;
    r record;


BEGIN
    -- start without error!
    v_error_code = 0;
   --updates
    IF (TG_OP = 'UPDATE') THEN
        v_cd_approval_status_new = NEW.cd_approval_status;
        v_cd_approval_status_old = OLD.cd_approval_status;


    --delete
    ELSIF (TG_OP = 'DELETE') THEN


    --insert
    ELSIF (TG_OP = 'INSERT') THEN
        v_cd_approval_status_new = NEW.cd_approval_status;
        v_cd_approval_status_old = NULL;
        
    ELSE
        RAISE EXCEPTION 'Trigger func added as trigger for unhandled case: %, %',TG_OP, TG_LEVEL;
        RETURN NULL;
    END IF;


   --updates ou insert
    IF (TG_OP = 'UPDATE' OR TG_OP = 'INSERT') THEN
-- only if changed the status to a valid one.

        -- if it is -50 means I'm opening the process again. So insert a new one and return NULL to not apply any update;
        IF NEW.cd_approval_steps_config_jump_to = -50 THEN
            SELECT ds_human_resource_full INTO v_text FROM "HUMAN_RESOURCE" where cd_human_resource = get_var('cd_human_resource')::integer;
            v_text = retDescTranslated('Purchase Request Reopened by '::text, null::integer) || ' ' || v_text;
            INSERT INTO "RFQ_APPROVAL_STEPS" (cd_rfq, cd_approval_steps_config, fl_must_add_reason, ds_remakrs ) values (NEW.cd_rfq, NEW.cd_approval_steps_config, 'Y', v_text);
            RETURN NULL;
        END IF;



        IF v_cd_approval_status_new IS NOT NULL AND v_cd_approval_status_old IS NULL THEN

            NEW.dt_define = NOW();
            NEW.cd_human_resource_define = get_var('cd_human_resource')::integer;

            -- if approved, add text step
            IF NEW.cd_approval_status = 1 THEN

                -- if approved,need to make sure information on the department is matching (quantity).
                IF EXISTS ( SELECT 1 
                              FROM "RFQ_ITEM" i, "APPROVAL_STEPS_CONFIG" a
                             WHERE i.cd_rfq = NEW.cd_rfq 
                              AND COALESCE((SELECT SUM(x.nr_qtty_to_buy) FROM "RFQ_ITEM_SUPPLIER" s, "RFQ_ITEM_SUPPLIER_QUOTATION" x where s.cd_rfq_item = i.cd_rfq_item AND x.cd_rfq_item_supplier = s.cd_rfq_item_supplier ), 0) 
                                    != COALESCE((SELECT SUM(nr_qtty_to_charge) FROM "RFQ_COST_CENTER" x where x.cd_rfq_item = i.cd_rfq_item ), 0)
                              AND a.cd_approval_steps_config = NEW.cd_approval_steps_config 
                              AND a.ds_internal_code         in ('ToCheckSupplier', 'ToDepMan')
                          ) THEN

                    v_error_text = retDescTranslated('You cannot go to the next step because the quantity on Department Cost Center is not matching to the quantity you are buying '::text, null::integer);
                    RAISE EXCEPTION '% (%)', v_error_text, 15648;
               END IF;

                -- if approved,need to make sure information on the department is matching (quantity).
                IF EXISTS ( SELECT 1 
                              FROM "RFQ_ITEM" i, "APPROVAL_STEPS_CONFIG" a
                             WHERE i.cd_rfq = NEW.cd_rfq 
                              AND a.cd_approval_steps_config = NEW.cd_approval_steps_config 
                              AND NOT EXISTS ( select 1 FROM "RFQ_COST_CENTER" x where x.cd_rfq_item = i.cd_rfq_item )
                           ) THEN

                    v_error_text = retDescTranslated('You cannot go to the next step because the Department Cost Center is missing '::text, null::integer);
                    RAISE EXCEPTION '% (%)', v_error_text, 15648;
               END IF;


                v_cd_approval_steps_config_next = NULL;

                SELECT nr_order, ds_internal_code  INTO v_nr_order_now, v_ds_internal_code  FROM "APPROVAL_STEPS_CONFIG" WHERE cd_approval_steps_config = NEW.cd_approval_steps_config;

                SELECT cd_approval_steps_config INTO v_cd_approval_steps_config_next FROM "APPROVAL_STEPS_CONFIG" WHERE nr_order > v_nr_order_now AND fl_approval_all = 'N'  AND ds_approval_steps_config_type = 'RFQ' ORDER BY nr_order LIMIT 1;

                IF v_cd_approval_steps_config_next IS NOT NULL THEN
                    INSERT INTO "RFQ_APPROVAL_STEPS" (cd_rfq, cd_approval_steps_config) values (NEW.cd_rfq, v_cd_approval_steps_config_next);
                END IF;


                -- If was approved the step where we get the buying information, create the table 
                
                --RAISE EXCEPTION 'Step: %',v_ds_internal_code;

                IF trim(v_ds_internal_code) = 'ToDepMan' THEN
                    
                    PERFORM addRfqGroup(NEW.cd_rfq);
                END IF;


            END IF;

            -- if rejected, add text step backwards
            IF NEW.cd_approval_status = 2 THEN
                v_cd_approval_steps_config_next = NULL;

                

                SELECT nr_order INTO v_nr_order_now FROM "APPROVAL_STEPS_CONFIG" WHERE cd_approval_steps_config = NEW.cd_approval_steps_config;
                SELECT cd_approval_steps_config INTO v_cd_approval_steps_config_next FROM "APPROVAL_STEPS_CONFIG" WHERE nr_order < v_nr_order_now  AND fl_approval_all = 'N' AND ds_approval_steps_config_type = 'RFQ' ORDER BY nr_order DESC LIMIT 1;

                RAISE NOTICE 'Next: %, JumpTo %', v_cd_approval_steps_config_next, NEW.cd_approval_steps_config_jump_to;

                
                IF v_cd_approval_steps_config_next = NEW.cd_approval_steps_config_jump_to THEN
                    INSERT INTO "RFQ_APPROVAL_STEPS" (cd_rfq, cd_approval_steps_config, fl_must_add_reason ) values (NEW.cd_rfq, v_cd_approval_steps_config_next, 'Y');
                ELSE
                    --SELECT nr_order, ds_internal_code  INTO v_nr_order_now, v_ds_internal_code  FROM "APPROVAL_STEPS_CONFIG" WHERE cd_approval_steps_config = v_cd_approval_steps_config_next;
                    --SELECT cd_approval_steps_config INTO v_cd_approval_steps_config_to_jump FROM "APPROVAL_STEPS_CONFIG" WHERE nr_order < v_nr_order_now AND fl_approval_all = 'N'  AND ds_approval_steps_config_type = 'RFQ' ORDER BY nr_order DESC LIMIT 1;

                    INSERT INTO "RFQ_APPROVAL_STEPS" (cd_rfq, cd_approval_steps_config, cd_approval_status, ds_remakrs, cd_approval_steps_config_jump_to ) 
                    VALUES (NEW.cd_rfq, v_cd_approval_steps_config_next, NEW.cd_approval_status, NEW.ds_remakrs, NEW.cd_approval_steps_config_jump_to  );
                END IF;
                    
                
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
ALTER FUNCTION rfq.if_rfq_approval_steps_before() SET search_path=pg_catalog, public, spec, rfq, translation;

ALTER FUNCTION rfq.if_rfq_approval_steps_before() OWNER TO postgres;

/*

    CREATE TRIGGER t_if_rfq_approval_steps_before
      BEFORE UPDATE OR INSERT OR DELETE
      ON rfq."RFQ_APPROVAL_STEPS"
      FOR EACH ROW
      EXECUTE PROCEDURE rfq.if_rfq_approval_steps_before();

*/	