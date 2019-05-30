-- Function: audit.if_tablelog()

-- DROP FUNCTION audit.if_tablelog();

CREATE OR REPLACE FUNCTION public.if_currency_rate()
  RETURNS trigger AS
$BODY$
DECLARE
    v_error        text;
    v_error_text   text;
BEGIN
  
   IF (TG_OP = 'UPDATE') THEN


        IF NEW.cd_currency_from = NEW.cd_currency_to THEN

            SELECT getTriggerError (500039, null)
              INTO v_error_text;

            RAISE EXCEPTION '% (%)', v_error_text, 500039;

        END IF;



      IF NEW.cd_currency_from <> OLD.cd_currency_from OR
         NEW.cd_currency_to   <> OLD.cd_currency_to 
         THEN

         NEW.ds_currency_rate = retCurrencyRateDesc(NEW.cd_currency_from, NEW.cd_currency_to, NEW.dt_currency_rate );

      END IF;

      RETURN NEW;

   ELSIF (TG_OP = 'DELETE') THEN


      RETURN OLD;

        --audit_row.row_data = hstore(OLD.*) - excluded_cols;
   ELSIF (TG_OP = 'INSERT') THEN

        IF NEW.cd_currency_from = NEW.cd_currency_to THEN

            SELECT getTriggerError (500039, null)
              INTO v_error_text;

            RAISE EXCEPTION '% (%)', v_error_text, 500039;

        END IF;

      NEW.ds_currency_rate = retCurrencyRateDesc(NEW.cd_currency_from, NEW.cd_currency_to, NEW.dt_currency_rate );

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
ALTER FUNCTION public.if_currency_rate() SET search_path=pg_catalog, public, material;

ALTER FUNCTION public.if_currency_rate()
  OWNER TO postgres;

   



