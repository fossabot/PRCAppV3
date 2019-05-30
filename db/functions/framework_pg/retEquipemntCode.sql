
CREATE OR REPLACE FUNCTION rfq.retEquipmentCode(PAR_cd_equipment_design bigint)
  RETURNS text    AS
$$
DECLARE 

    vret text;


BEGIN

    
     SELECT COALESCE( ds_equipment_design_code_alternate ,COALESCE(a.ds_name_code, '') || COALESCE(b.ds_name_code, '') || COALESCE(c.ds_name_code, '') || COALESCE(a.ds_name_separator, '') || rtrim(ltrim((CASE WHEN a.fl_add_zero_left = 'Y' THEN to_char("EQUIPMENT_DESIGN".nr_series, '000') ELSE "EQUIPMENT_DESIGN".nr_series::text END)   )))
       INTO vret 
       FROM "EQUIPMENT_DESIGN", "EQUIPMENT_DESIGN_TYPE" a, "EQUIPMENT_DESIGN_CATEGORY" b, "EQUIPMENT_DESIGN_SUB_CATEGORY" c
      WHERE "EQUIPMENT_DESIGN".cd_equipment_design  = PAR_cd_equipment_design
        AND  c.cd_equipment_design_sub_category = "EQUIPMENT_DESIGN".cd_equipment_design_sub_category 
        AND  b.cd_equipment_design_category     = c.cd_equipment_design_category
        AND  a.cd_equipment_design_type         = b.cd_equipment_design_type;
      
     
    return vret;



END
$$  LANGUAGE plpgsql STABLE;


ALTER FUNCTION rfq.retEquipmentCode(bigint) SET search_path=pg_catalog, public, rfq;