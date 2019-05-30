
CREATE OR REPLACE FUNCTION rfq.importCalibrationWithInsertTypes()
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
vseries     integer;
vremarks   text;
vweb       text;
vTypeSplited text;
vtypeGroup text;
vtypeCategory text;
vtypeSubCategory text;


BEGIN


    FOR q IN SELECT * FROM tempexcel
     LOOP

        vTypeSplited  = null;
        vtypeGroup  = null;
        vtypeCategory = null;
        vtypeSubCategory = null;
        vtype     = null;
        vcat      = null;
        vsubcat   = null;
        vunit     = null;
        vgrade    = null;
        vremarks  = null;
        vweb      = null;

        vTypeSplited = split_part(q.ds_a, '-', 1);

        vtypeGroup       = substring( vTypeSplited FROM 1 FOR length(vTypeSplited) - 2) ;
        vtypeCategory    = substring( vTypeSplited FROM length(vTypeSplited) - 1  FOR 1) ;
        vtypeSubCategory = substring( vTypeSplited FROM length(vTypeSplited) FOR 1) ;

        vseries =  split_part(q.ds_a, '-', 2)::integer;


         SELECT cd_equipment_design_type
           INTO vtype 
           FROM "EQUIPMENT_DESIGN_TYPE" 
         WHERE ds_name_code = vtypeGroup;

         IF NOT FOUND THEN
            vtype = nextval('rfq."EQUIPMENT_DESIGN_TYPE_cd_equipment_design_type_seq"'::regclass);
            INSERT INTO rfq."EQUIPMENT_DESIGN_TYPE" (cd_equipment_design_type, ds_equipment_design_type, ds_name_code, ds_name_separator, cd_system_product_category)
            values (vtype, 'Calibration ' || vtypeGroup, vtypeGroup, '-', 1);

            RAISE NOTICE 'Inserting Type % - %, %, %', q.ds_a, vtypeGroup, vtypeCategory , vtypeSubCategory;
         END IF;


         SELECT cd_equipment_design_category
           INTO vcat 
           FROM "EQUIPMENT_DESIGN_CATEGORY" 
         WHERE cd_equipment_design_type = vtype
           AND ds_name_code = vtypeCategory;

         IF NOT FOUND THEN
            vcat = nextval('rfq."EQUIPMENT_DESIGN_CATEGORY_cd_equipment_design_category_seq"'::regclass);
            INSERT INTO rfq."EQUIPMENT_DESIGN_CATEGORY" (cd_equipment_design_category, ds_equipment_design_category, cd_equipment_design_type, ds_name_code)
            values (vcat, 'Calibration ' || vtypeGroup || ' - ' || vtypeCategory, vtype, vtypeCategory);

            RAISE NOTICE 'Inserting Category % - %, %, %', q.ds_a, vtypeGroup, vtypeCategory , vtypeSubCategory;
         END IF;

         SELECT cd_equipment_design_sub_category
           INTO vsubcat 
           FROM "EQUIPMENT_DESIGN_SUB_CATEGORY" 
         WHERE cd_equipment_design_category = vcat
           AND ds_name_code = vtypeSubCategory;

         IF NOT FOUND THEN
            vsubcat = nextval('rfq."EQUIPMENT_SUB_CATEGORY_cd_equipment_sub_category_seq"'::regclass);
            INSERT INTO rfq."EQUIPMENT_DESIGN_SUB_CATEGORY" (cd_equipment_design_sub_category, ds_equipment_design_sub_category, cd_equipment_design_category, ds_name_code)        
            values (vsubcat, 'Calibration ' || vtypeGroup || ' - ' || vtypeCategory || ' - ' || vtypeSubCategory, vcat, vtypeSubCategory);

            RAISE NOTICE 'Inserting Sub % - %, %, %', q.ds_a, vtypeGroup, vtypeCategory , vtypeSubCategory;
         END IF;

        update "EQUIPMENT_DESIGN" 
        set ds_technical_description         = q.ds_g, 
            ds_technical_description_english = q.ds_h,
            ds_website   = q.ds_i,
            cd_unit_measure = (SELECT cd_unit_measure FROM "UNIT_MEASURE" where ds_unit_measure = q.ds_f ),
            ds_brand        = q.ds_e,
            nr_grade        = q.ds_l::integer
         where retEquipmentCode("EQUIPMENT_DESIGN".cd_equipment_design) = q.ds_a;  

         IF NOT FOUND THEN
            INSERT INTO rfq."EQUIPMENT_DESIGN"
                (ds_equipment_design, 
                 ds_technical_description,
                 ds_technical_description_english,
                 cd_equipment_design_sub_category, 
                 ds_website, 
                 cd_unit_measure, 
                 ds_brand,
                 nr_grade,
                 cd_system_product_category,
                 nr_series
                )
           VALUES ( q.ds_b,
                    q.ds_g, 
                    q.ds_h,
                    vsubcat,
                    q.ds_i,
                   (SELECT cd_unit_measure FROM "UNIT_MEASURE" where ds_unit_measure = q.ds_f ),
                    q.ds_e,
                    q.ds_l::integer,
                    1,
                    vseries
                  );

            RAISE NOTICE 'Inserting Equipment % -  % - %, %, %', q.ds_b, q.ds_a, vtypeGroup, vtypeCategory , vtypeSubCategory;

         END IF;

     




    END LOOP;




END
$$  LANGUAGE plpgsql;
ALTER FUNCTION rfq.importCalibrationWithInsertTypes() SET search_path=pg_catalog, public, rfq;