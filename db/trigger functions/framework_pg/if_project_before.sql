-- Function: audit.if_tablelog()

-- DROP FUNCTION audit.if_tablelog();


CREATE OR REPLACE FUNCTION tti.if_project_before()
  RETURNS trigger AS
$BODY$
DECLARE
    v_error_text text;
    v_error_code int;


BEGIN
    -- start without error!
    v_error_code = 0;

   --updates ou insert更新or插入
    IF (TG_OP = 'UPDATE' OR TG_OP = 'INSERT') THEN
        IF NEW.ds_tti_project IS NULL AND NEW.ds_met_project IS NULL THEN
            v_error_text = retDescTranslated('You must inform TTI project# or MET Project#'::text, null::integer);
            RAISE EXCEPTION '% (%)', v_error_text, 15648;
            -- RAISE EXCEPTION 'TTI Project & MET Project can not be null together';
            RETURN NULL;
        END IF;
    END IF;


    -- controle de erro!!!!错误控制
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

  RETURN NULL;


END;
$BODY$
  LANGUAGE plpgsql VOLATILE SECURITY DEFINER
  COST 100;

ALTER FUNCTION tti.if_project_before() SET search_path=pg_catalog, public, tti, translation;

ALTER FUNCTION tti.if_project_before() OWNER TO postgres;


CREATE TRIGGER t_project_before
  BEFORE UPDATE OR INSERT OR DELETE
  ON tti."PROJECT"
  FOR EACH ROW
  EXECUTE PROCEDURE tti.if_project_before();


