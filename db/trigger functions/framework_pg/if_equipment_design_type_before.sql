-- Function: audit.if_tablelog()

-- DROP FUNCTION audit.if_tablelog();


CREATE OR REPLACE FUNCTION rfq.if_equipment_design_type_before()
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


    --delete
    ELSIF (TG_OP = 'DELETE') THEN


    --insert
    ELSIF (TG_OP = 'INSERT') THEN

        IF NEW.cd_system_product_category IS NULL THEN
            NEW.cd_system_product_category = get_var('cd_system_product_category');
        END IF;


    ELSE
        RAISE EXCEPTION 'Trigger if_equipment_design_type_before added as trigger for unhandled case: %, %',TG_OP, TG_LEVEL;
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
ALTER FUNCTION rfq.if_equipment_design_type_before() SET search_path=pg_catalog, public, spec, rfq;

ALTER FUNCTION rfq.if_equipment_design_type_before() OWNER TO postgres;

/*

        CREATE TRIGGER t_equipment_design_type_before
          BEFORE UPDATE OR INSERT OR DELETE
          ON rfq."EQUIPMENT_DESIGN_TYPE"
          FOR EACH ROW
          EXECUTE PROCEDURE rfq.if_equipment_design_type_before();


*/

