-- Function: audit.if_tablelog()

-- DROP FUNCTION audit.if_tablelog();


CREATE OR REPLACE FUNCTION tr.if_mte_work_order_sample_data()
  RETURNS trigger AS
$BODY$
DECLARE
    v_error_text text;
    v_error_code int;


BEGIN
    -- start without error!
    v_error_code = 0;

   --updates
    IF (TG_OP = 'UPDATE') THEN
        NEW.nr_sample  = COALESCE (REGEXP_REPLACE( split_part(NEW.ds_tool, '.', 1), '[^0-9]', '', 'g'), '')::integer;

    --delete
    ELSIF (TG_OP = 'DELETE') THEN
    

    --insert
    ELSIF (TG_OP = 'INSERT') THEN
        NEW.nr_sample  = COALESCE (REGEXP_REPLACE( split_part(NEW.ds_tool, '.', 1), '[^0-9]', '', 'g'), '')::integer;

    ELSE
        RAISE EXCEPTION 'Trigger func added as trigger for unhandled case: %, %',TG_OP, TG_LEVEL;
        RETURN NULL;
    END IF;


   --updates ou insert
    IF (TG_OP = 'UPDATE' OR TG_OP = 'INSERT') THEN
        --NEW.dt_update = NOW();
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
ALTER FUNCTION tr.if_mte_work_order_sample_data() SET search_path=pg_catalog, public, spec, rfq;

ALTER FUNCTION tr.if_mte_work_order_sample_data() OWNER TO qasystems;

/*

        CREATE TRIGGER t_if_mte_work_order_sample_data
          BEFORE UPDATE OR INSERT OR DELETE
          ON tr."MTE_WORK_ORDER_SAMPLE_DATA"
          FOR EACH ROW
          EXECUTE PROCEDURE tr.if_mte_work_order_sample_data();


*/

