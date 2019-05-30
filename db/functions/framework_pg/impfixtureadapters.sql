CREATE OR REPLACE FUNCTION public.impfixtureadaptersproc()
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



BEGIN

   

    FOR q IN SELECT *
               FROM impfxt 
         where ds_d IS NOT NULL AND ds_d != 'Fixture description.'
     LOOP

    vtype = null;
    vcat = null;
    vsubcat = null;
    vunit = null;
    vgrade = null;


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
       AND ds_name_code = substring(q.ds_a FROM 3 FOR 1);

/*
     SELECT cd_unit_measure
       INTO vunit
       FROM "UNIT_MEASURE" 
     WHERE ds_unit_measure = q.ds_f;
*/     
    vunit  = NULL;
    vgrade = NULL;

    IF vsubcat IS NULL THEN
         RAISE NOTICE '% - %, %, %, %, %, %', q.ds_a, vtype, vcat, vsubcat, vunit, vgrade, q.ds_i;

    ELSE 

        IF NOT EXISTS ( SELECT 1 FROM rfq."EQUIPMENT_DESIGN" WHERE cd_equipment_design_sub_category = vsubcat AND nr_series = q.ds_b::integer) THEN 
            INSERT INTO rfq."EQUIPMENT_DESIGN"
            (ds_equipment_design, cd_equipment_design_sub_category, nr_series, cd_unit_measure,  ds_remarks, nr_grade)
            VALUES
            (q.ds_d, vsubcat, q.ds_b::integer, vunit, COALESCE(q.ds_f, '') || ' ' || COALESCE(q.ds_k, '')   , vgrade );
        END IF;

    END IF;

    END LOOP;




END
$$  LANGUAGE plpgsql;

ALTER FUNCTION public.impfixtureadaptersproc() SET search_path=pg_catalog, public, rfq;