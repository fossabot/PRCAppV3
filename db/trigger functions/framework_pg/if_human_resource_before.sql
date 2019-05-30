-- Function: audit.if_tablelog()

-- DROP FUNCTION audit.if_tablelog();


CREATE OR REPLACE FUNCTION public.if_human_resource_before()
  RETURNS trigger AS
$BODY$
DECLARE
    v_error_text text;
    v_error_code int;
    v_staff_number int;


BEGIN
    -- start without error!
    v_error_code = 0;

   --updates
    IF (TG_OP = 'UPDATE') THEN


    --delete
    ELSIF (TG_OP = 'DELETE') THEN


    --insert
    ELSIF (TG_OP = 'INSERT') THEN

        IF NEW.nr_login_mode = 3 THEN
            NEW.fl_ldap = 'Y';
        END IF;

        IF NEW.ds_human_resource IS NULL THEN
           NEW.ds_human_resource = NEW.ds_human_resource_full;
        END IF;

        IF NEW.ds_password IS NULL THEN
            NEW.ds_password  = MD5(NEW.ds_human_resource_full);
        END IF;



    ELSE
        RAISE EXCEPTION 'Trigger func added as trigger for unhandled case: %, %',TG_OP, TG_LEVEL;
        RETURN NULL;
    END IF;


   --updates ou insert
    IF (TG_OP = 'UPDATE' OR TG_OP = 'INSERT') THEN

        
        IF NEW.nr_staff_number IS NOT NULL AND EXISTS ( SELECT 1 FROM "HUMAN_RESOURCE" where nr_staff_number = NEW.nr_staff_number AND cd_human_resource != NEW.cd_human_resource AND nr_login_mode = NEW.nr_login_mode) THEN
            v_error_text = retDescTranslated('Staff# is already in use.'::text, null::integer);
            RAISE EXCEPTION '% (# %) (%)', v_error_text, NEW.nr_staff_number,15648;
        END IF;

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
ALTER FUNCTION public.if_human_resource_before() SET search_path=pg_catalog, public, rfq, translation;

ALTER FUNCTION public.if_human_resource_before() OWNER TO postgres;

/*

        CREATE TRIGGER t__human_resource_before
          BEFORE UPDATE OR INSERT OR DELETE
          ON public."HUMAN_RESOURCE"
          FOR EACH ROW
          EXECUTE PROCEDURE public.if_human_resource_before();


*/

