CREATE OR REPLACE FUNCTION public.if_human_resource_after()
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
        IF NEW.nr_login_mode = 3 THEN
            INSERT INTO public."HUMAN_RESOURCE_X_SYSTEM_PRODUCT_CATEGORY"  (cd_human_resource, cd_system_product_category)
            VALUES (NEW.cd_human_resource, 1);

            insert into "JOBS_HUMAN_RESOURCE" (cd_human_resource, cd_jobs)
            VALUES ( NEW.cd_human_resource, 95 );


        END IF;

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
ALTER FUNCTION public.if_human_resource_after() SET search_path=pg_catalog, public, rfq, translation;

ALTER FUNCTION public.if_human_resource_after() OWNER TO postgres;

/*

        CREATE TRIGGER t_human_resource_after
          AFTER UPDATE OR INSERT OR DELETE
          ON public."HUMAN_RESOURCE"
          FOR EACH ROW
          EXECUTE PROCEDURE public.if_human_resource_after();


*/

