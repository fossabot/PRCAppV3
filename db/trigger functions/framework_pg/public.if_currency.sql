-- Function: audit.if_tablelog()

-- DROP FUNCTION audit.if_tablelog();

CREATE OR REPLACE FUNCTION public.if_currency()
  RETURNS trigger AS
$BODY$
DECLARE
    v_error        text;
BEGIN

  
   IF (TG_OP = 'UPDATE') THEN


      RETURN NEW;

   ELSIF (TG_OP = 'DELETE') THEN

      

      RETURN OLD;

        --audit_row.row_data = hstore(OLD.*) - excluded_cols;
   ELSIF (TG_OP = 'INSERT') THEN

      RETURN NEW;

   ELSE
        RAISE EXCEPTION 'Trigger func added as trigger for unhandled case: %, %',TG_OP, TG_LEVEL;
        RETURN NULL;
   END IF;


   RETURN NULL;

  RETURN NEW;
END;
$BODY$
  LANGUAGE plpgsql VOLATILE SECURITY DEFINER
  COST 100;
ALTER FUNCTION public.if_currency() SET search_path=pg_catalog, public, material;

ALTER FUNCTION public.if_currency()
  OWNER TO postgres;

   



