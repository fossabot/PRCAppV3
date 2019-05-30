-- Function: audit.if_tablelog()

-- DROP FUNCTION audit.if_tablelog();


CREATE OR REPLACE FUNCTION public.if_sys_column_filter_preset()
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
        
        IF NEW.fl_default = 'Y' THEN

            UPDATE "SYS_COLUMN_FILTER_PRESET"
               SET fl_default = 'N'
             WHERE cd_system_product_category = NEW.cd_system_product_category
               AND cd_human_resource          = NEW.cd_human_resource
               AND ds_grid_id                 = NEW.ds_grid_id
               AND fl_default = 'Y'
               AND cd_sys_column_filter_preset != NEW.cd_sys_column_filter_preset;

        END IF;

    --delete
    ELSIF (TG_OP = 'DELETE') THEN


    --insert
    ELSIF (TG_OP = 'INSERT') THEN


    ELSE
        RAISE EXCEPTION 'Trigger func added as trigger for unhandled case: %, %',TG_OP, TG_LEVEL;
        RETURN NULL;
    END IF;


   --updates ou insert
    IF (TG_OP = 'UPDATE' OR TG_OP = 'INSERT') THEN
            

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
ALTER FUNCTION public.if_sys_column_filter_preset() SET search_path=pg_catalog, public, spec;

ALTER FUNCTION public.if_sys_column_filter_preset() OWNER TO postgres;




CREATE TRIGGER aa_sys_column_filter_preset
  BEFORE INSERT OR UPDATE OR DELETE
  ON public."SYS_COLUMN_FILTER_PRESET"
  FOR EACH ROW
  EXECUTE PROCEDURE public.if_sys_column_filter_preset();

