
CREATE OR REPLACE FUNCTION public.impcompproc()
  RETURNS void    AS
$$
DECLARE 
r record;
p record;
q record;
vtype   integer;
vcat   integer;
vsubcat   integer;
vunit     integer;
vgrade     integer;
vremarks   text;
vweb       text;


BEGIN

   

    FOR q IN SELECT *
               FROM impcomp 
         where ds_d IS NOT NULL AND ds_c != 'Series No.'
     LOOP

    vtype     = null;
    vcat      = null;
    vsubcat   = null;
    vunit     = null;
    vgrade    = null;
    vremarks  = null;
    vweb      = null;


     SELECT cd_equipment_design_type
       INTO vtype 
       FROM "EQUIPMENT_DESIGN_TYPE" 
     WHERE ds_name_code = substring(q.ds_a FROM 1 FOR 1);

     SELECT cd_equipment_design_category
       INTO vcat 
       FROM "EQUIPMENT_DESIGN_CATEGORY" 
     WHERE cd_equipment_design_type = vtype
       AND ds_name_code = substring(q.ds_a FROM 2 FOR 1);

     SELECT cd_equipment_design_sub_category
       INTO vsubcat 
       FROM "EQUIPMENT_DESIGN_SUB_CATEGORY" 
     WHERE cd_equipment_design_category = vcat
       AND ds_name_code = q.ds_b;

     SELECT cd_unit_measure
       INTO vunit
       FROM "UNIT_MEASURE" 
     WHERE ds_unit_measure = q.ds_f;
     
    if isnumeric(q.ds_g ) THEN
        vgrade = q.ds_g;
    end if;

    if strpos(q.ds_i, 'http')> 0 THEN
        vweb = q.ds_i;
        ELSE
        vremarks = q.ds_i;
    END IF;

    IF vsubcat IS NULL THEN
         RAISE NOTICE '% - %, %, %, %, %, %', q.ds_a, vtype, vcat, vsubcat, vunit, vgrade, q.ds_i;

    ELSE 

        IF NOT EXISTS ( SELECT 1 FROM rfq."EQUIPMENT_DESIGN" WHERE cd_equipment_design_sub_category = vsubcat AND nr_series = q.ds_c::integer) THEN 
            INSERT INTO rfq."EQUIPMENT_DESIGN"
            (ds_equipment_design, cd_equipment_design_sub_category, nr_series, cd_unit_measure,  ds_remarks, nr_grade, ds_website)
            VALUES
            (q.ds_e, vsubcat, q.ds_c::integer, vunit, vremarks, vgrade, vweb );
        END IF;

    END IF;

    END LOOP;




END
$$  LANGUAGE plpgsql;
ALTER FUNCTION public.impcompproc() SET search_path=pg_catalog, public, rfq;