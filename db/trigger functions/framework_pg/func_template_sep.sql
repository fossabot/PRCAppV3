 -- Function: audit.if_tablelog()

-- DROP FUNCTION audit.if_tablelog();

CREATE OR REPLACE FUNCTION schema.funcname()
  RETURNS trigger AS
$BODY$
DECLARE
    v_error_text text;
    v_error_code int;

BEGIN
    -- start without error!
    v_error_code = 0;



    -- controle de erro!!!!
    IF v_error_code > 0 THEN

        SELECT getTriggerError (v_error_code, null)
          INTO v_error_text;

        RAISE EXCEPTION '% (%)', v_error_text, v_error_code;
    END IF;


   RETURN NEW;
   

END;
$BODY$
  LANGUAGE plpgsql VOLATILE SECURITY DEFINER
  COST 100;
ALTER FUNCTION schema.funcname() SET search_path=pg_catalog, public, spec;

ALTER FUNCTION schema.funcname()
  OWNER TO postgres;





