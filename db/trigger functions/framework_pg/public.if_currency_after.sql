-- Function: audit.if_tablelog()

-- DROP FUNCTION audit.if_tablelog();

CREATE OR REPLACE FUNCTION public.if_currency_after()
  RETURNS trigger AS
$BODY$
DECLARE
    v_error        text;
BEGIN

  
   IF (TG_OP = 'UPDATE') THEN
      IF NEW.ds_currency_symbol <> OLD.ds_currency_symbol THEN

         UPDATE "CURRENCY_RATE" 
            SET ds_currency_rate = retCurrencyRateDesc(cd_currency_from, cd_currency_to, dt_currency_rate )
         WHERE cd_currency_from = NEW.cd_currency;


         UPDATE "CURRENCY_RATE" 
            SET ds_currency_rate = retCurrencyRateDesc(cd_currency_from, cd_currency_to, dt_currency_rate )
         WHERE cd_currency_to = NEW.cd_currency;


      END IF;

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
ALTER FUNCTION public.if_currency_after() SET search_path=pg_catalog, public, material;

ALTER FUNCTION public.if_currency_after()
  OWNER TO postgres;

   



