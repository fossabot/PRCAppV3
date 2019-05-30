-- FunctionNEW. audit.if_tablelog()

-- DROP FUNCTION audit.if_tablelog();


CREATE OR REPLACE FUNCTION assets.if_assets_before()
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

        IF NEW.cd_assets_location_room       IS DISTINCT FROM OLD.cd_assets_location_room OR
           NEW.ds_department_ref_number      IS DISTINCT FROM OLD.ds_department_ref_number OR
           NEW.cd_human_resource_responsible IS DISTINCT FROM OLD.cd_human_resource_responsible OR
           NEW.ds_assets_number_old          IS DISTINCT FROM OLD.ds_assets_number_old OR
           NEW.ds_remarks                    IS DISTINCT FROM OLD.ds_remarks OR
           NEW.cd_department_cost_center     IS DISTINCT FROM OLD.cd_department_cost_center THEN

        INSERT INTO assets."ASSETS_CHANGES" (cd_assets, dt_record, cd_human_resource, cd_assets_location_room, ds_department_ref_number, cd_human_resource_responsible, ds_assets_number_old, ds_remarks, cd_department_cost_center)
           VALUES (NEW.cd_assets, NOW(), get_var('cd_human_resource')::bigint, NEW.cd_assets_location_room, NEW.ds_department_ref_number, NEW.cd_human_resource_responsible, NEW.ds_assets_number_old, NEW.ds_remarks, NEW.cd_department_cost_center);

    END IF;

    --delete
    ELSIF (TG_OP = 'DELETE') THEN


    --insert
    ELSIF (TG_OP = 'INSERT') THEN

    ELSE
        RAISE EXCEPTION 'Trigger func added as trigger for unhandled caseNEW. %, %',TG_OP, TG_LEVEL;
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
ALTER FUNCTION assets.if_assets_before() SET search_path=pg_catalog, public, spec, rfq;

ALTER FUNCTION assets.if_assets_before() OWNER TO postgres;

/*

        CREATE TRIGGER t_if_assets_before
          BEFORE UPDATE OR INSERT OR DELETE
          ON assets."ASSETS"
          FOR EACH ROW
          EXECUTE PROCEDURE assets.if_assets_before();


*/

