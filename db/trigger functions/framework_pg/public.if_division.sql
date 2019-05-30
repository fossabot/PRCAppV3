-- Function: audit.if_tablelog()

-- DROP FUNCTION audit.if_tablelog();

CREATE OR REPLACE FUNCTION public.if_division()
  RETURNS trigger AS
$BODY$
DECLARE
    v_error_text text;
    v_error_code int;

BEGIN
    -- start without error!
    v_error_code = 0;


   --updates ou insert
    IF (TG_OP = 'UPDATE' OR TG_OP = 'INSERT') THEN
    

    END IF;


   --updates
    IF (TG_OP = 'UPDATE') THEN



    --delete
    ELSIF (TG_OP = 'DELETE') THEN

    --insert
    ELSIF (TG_OP = 'INSERT') THEN




    ELSE
        RAISE EXCEPTION 'Trigger func added as trigger for unhandled case: %, %',TG_OP, TG_LEVEL;
        RETURN NULL;
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
ALTER FUNCTION public.if_division() SET search_path=pg_catalog, public, spec;

ALTER FUNCTION public.if_division()
  OWNER TO postgres;




CREATE TRIGGER aa_insert_update_delete
    BEFORE INSERT OR DELETE OR UPDATE 
    ON public."DIVISION"
    FOR EACH ROW
    EXECUTE PROCEDURE if_division();

