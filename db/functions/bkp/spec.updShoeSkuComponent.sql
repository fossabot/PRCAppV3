CREATE OR REPLACE FUNCTION spec.updShoeSkuComponent
(
    p_cd_shoe_sku integer, 
    jsonData      json
)
RETURNS  SETOF "SHOE_SKU_COMPONENT_ATTRIBUTES" AS
$$
DECLARE
    v_level1 json;
    rset       record;
    rec_comp   record;
    rec_attrib record;
    rec_parts  record;
    v_cd_product_component              integer;
    v_nr_part                           integer;
    v_cd_product                        integer;
    v_cd_shoe_level_x_product_component integer;
    v_cd_color                          integer;
    v_cd_shoe_component_attributes      integer;
    v_cd_shoe_component_attribute_items integer;
    v_cd_shoe_prd_component_x_shoe_attributes integer;
    v_cd_shoe_sku_component         integer;
    updPart       record;
    v_nr_part_count integer;
    v_cd_product_component_old integer;
    v_cd_test integer;

    

BEGIN

    select count(1) INTO v_cd_test FROM "SHOE_SKU_COMPONENT" where cd_shoe_sku = p_cd_shoe_sku and  cd_shoe_level_x_product_component = 36;

    --RAISE EXCEPTION 'count %',  v_cd_test;

    CREATE TEMP TABLE dataComponent ON COMMIT DROP AS
    SELECT * 
      FROM "SHOE_SKU_COMPONENT"
     WHERE cd_shoe_sku = p_cd_shoe_sku;


    -- insiro os dados dos inherits caso jah nao esteja (Construcao)!
    INSERT INTO dataComponent (cd_shoe_sku, 
                                   cd_shoe_level_x_product_component, 
                                   nr_part, 
                                   cd_product, 
                                   cd_color)

    SELECT     ss.cd_shoe_sku,
               lss.cd_shoe_level_x_product_component,
               cc.nr_part,
               cc.cd_product,
               cc.cd_color
          FROM "SHOE_SKU" ss,
               "SHOE_SPECIFICATION" sss,
               "CONSTRUCTION_COMPONENT" cc,
               "SHOE_LEVEL_X_PRODUCT_COMPONENT" lcc,
               "SHOE_LEVEL_X_PRODUCT_COMPONENT" lss

         WHERE ss.cd_shoe_sku   		  = p_cd_shoe_sku
           AND sss.cd_shoe_specification          = ss.cd_shoe_specification
           AND cc.cd_construction                 = sss.cd_construction

           AND lcc.cd_shoe_level_x_product_component = cc.cd_shoe_level_x_product_component

           AND lcc.cd_shoe_level                     = lss.cd_shoe_level_inherit
           AND lcc.cd_product_component              = lss.cd_product_component
           AND lss.cd_shoe_level                     = 3
           AND NOT EXISTS ( SELECT 1
                             FROM dataComponent x
                            WHERE x.cd_shoe_sku = ss.cd_shoe_sku
                              AND x.cd_shoe_level_x_product_component = lss.cd_shoe_level_x_product_component
                              AND x.nr_part                           = cc.nr_part
                           );

    -- insiro os dados dos inherits caso jah nao esteja (Construcao)!
    INSERT INTO dataComponent (cd_shoe_sku, 
                                   cd_shoe_level_x_product_component, 
                                   nr_part, 
                                   cd_product, 
                                   cd_color)

    SELECT     ss.cd_shoe_sku,
               lss.cd_shoe_level_x_product_component,
               ssc.nr_part,
               ssc.cd_product,
               ssc.cd_color
          FROM "SHOE_SKU" ss,
               "SHOE_SPECIFICATION_COMPONENT" ssc,
               "SHOE_LEVEL_X_PRODUCT_COMPONENT" lcc,
               "SHOE_LEVEL_X_PRODUCT_COMPONENT" lss

         WHERE ss.cd_shoe_sku   		  = p_cd_shoe_sku
           AND ssc.cd_shoe_specification          = ss.cd_shoe_specification

           AND lcc.cd_shoe_level_x_product_component = ssc.cd_shoe_level_x_product_component

           AND lcc.cd_shoe_level                     = lss.cd_shoe_level_inherit
           AND lcc.cd_product_component              = lss.cd_product_component
           AND lss.cd_shoe_level                     = 3
           AND NOT EXISTS ( SELECT 1
                             FROM dataComponent x
                            WHERE x.cd_shoe_sku = ss.cd_shoe_sku
                              AND x.cd_shoe_level_x_product_component = lss.cd_shoe_level_x_product_component
                              AND x.nr_part                           = ssc.nr_part
                           );



    CREATE TEMP TABLE dataAttributes ON COMMIT DROP AS
    SELECT * 
      FROM "SHOE_SKU_COMPONENT_ATTRIBUTES" 
     WHERE EXISTS ( SELECT 1
                     FROM "SHOE_SKU_COMPONENT" x
                    WHERE x.cd_shoe_sku = p_cd_shoe_sku 
                      AND x.cd_shoe_sku_component = "SHOE_SKU_COMPONENT_ATTRIBUTES".cd_shoe_sku_component
                   ) ;

   
    -- ************************ PRODUCT ************************
    v_level1  = null;

    SELECT value INTO v_level1 FROM json_each (jsonData) where key = 'component';

 
    -- key = cd_product_component
    FOR rec_comp IN select * from json_each ( v_level1 )
    LOOP
        v_cd_product_component = rec_comp.key;

        SELECT cd_shoe_level_x_product_component
          INTO v_cd_shoe_level_x_product_component
          FROM "SHOE_LEVEL_X_PRODUCT_COMPONENT"
         WHERE cd_product_component = v_cd_product_component 
           AND cd_shoe_level        = 3;

        
        FOR rec_parts IN select * from json_each ( rec_comp.value )
        LOOP

            v_cd_product           = rec_parts.value;
            v_nr_part              = rec_parts.key;
           
            UPDATE dataComponent
               SET cd_product = v_cd_product
             WHERE cd_shoe_level_x_product_component = v_cd_shoe_level_x_product_component
               AND nr_part                           = v_nr_part;


            IF NOT FOUND THEN
                INSERT INTO dataComponent (cd_shoe_sku, cd_shoe_level_x_product_component,nr_part, cd_product )
                VALUES (p_cd_shoe_sku, v_cd_shoe_level_x_product_component, v_nr_part, v_cd_product);
            END IF;

            --RAISE NOTICE 'Comp % Part % Prod %', v_cd_product_component, v_nr_part, v_cd_product ;

        END LOOP;

        

    END LOOP;


    -- ************ COLOR ************
    rec_comp = null;
    rec_parts = null;
    v_level1  = null;


    SELECT value INTO v_level1 FROM json_each (jsonData) where key = 'color';

 
    -- key = cd_product_component
    FOR rec_comp IN select * from json_each ( v_level1 )
    LOOP
        v_cd_product_component = rec_comp.key;

        SELECT cd_shoe_level_x_product_component
          INTO v_cd_shoe_level_x_product_component
          FROM "SHOE_LEVEL_X_PRODUCT_COMPONENT"
         WHERE cd_product_component = v_cd_product_component
           AND cd_shoe_level        = 3;
        
        
        FOR rec_parts IN select * from json_each ( rec_comp.value )
        LOOP

            --RAISE NOTICE '% %', v_cd_product_component, rec_parts.key;

            v_cd_color = rec_parts.value;
            v_nr_part  = rec_parts.key;
           
            UPDATE dataComponent
               SET cd_color = v_cd_color
             WHERE cd_shoe_level_x_product_component = v_cd_shoe_level_x_product_component
               AND nr_part                           = v_nr_part;


            --RAISE NOTICE 'Comp % Part % Prod %', v_cd_product_component, v_nr_part, v_cd_product ;

        END LOOP;

        

    END LOOP;


    -- removo todas os part da tabela oficial que esteja marcado para morrer.
    DELETE FROM "SHOE_SKU_COMPONENT" AS a
      USING dataComponent b
     WHERE a.cd_shoe_sku = p_cd_shoe_sku
       AND b.cd_product      = -2
       AND a.cd_shoe_level_x_product_component = b.cd_shoe_level_x_product_component
       AND a.nr_part = b.nr_part;

     
    -- apago a temporaria!
    DELETE FROM dataComponent WHERE cd_product  = -2;

    
    -- atualizo todos que podem ser atualizados
    UPDATE "SHOE_SKU_COMPONENT" a
       SET cd_product                        = CASE WHEN b.cd_product IS NULL THEN a.cd_product
                                                      WHEN b.cd_product = -1 THEN NULL ELSE  b.cd_product END,

           cd_color                          = CASE WHEN b.cd_color IS NULL THEN a.cd_color
                                                      WHEN b.cd_color = -1 THEN NULL ELSE  b.cd_color END
     FROM dataComponent b
    WHERE a.cd_shoe_level_x_product_component = b.cd_shoe_level_x_product_component
      AND a.nr_part                           = b.nr_part;

    
    -- insiro o que nao existe ainda
    INSERT INTO "SHOE_SKU_COMPONENT" (cd_shoe_sku, cd_shoe_level_x_product_component,nr_part, cd_color, cd_product )
    SELECT p_cd_shoe_sku , cd_shoe_level_x_product_component,nr_part, cd_color, cd_product
      FROM dataComponent x
    WHERE NOT EXISTS ( SELECT 1 
                         FROM "SHOE_SKU_COMPONENT" 
                       WHERE cd_shoe_sku                   = p_cd_shoe_sku 
                         AND cd_shoe_level_x_product_component = x.cd_shoe_level_x_product_component 
                         AND nr_part  = x.nr_part);



    -- ************ ATTRIBUTES ************
    rec_comp = null;
    rec_parts = null;
    v_level1  = null;

    SELECT value INTO v_level1 FROM json_each (jsonData) where key = 'attribute';

 
    -- key = cd_product_component
    FOR rec_comp IN select * from json_each ( v_level1 )
    LOOP
        v_cd_product_component = rec_comp.key;

        SELECT cd_shoe_level_x_product_component
          INTO v_cd_shoe_level_x_product_component
          FROM "SHOE_LEVEL_X_PRODUCT_COMPONENT"
         WHERE cd_product_component = v_cd_product_component
           AND cd_shoe_level        = 2;
        
        -- key = cd_shoe_component_attributes
        FOR rec_attrib IN select * from json_each ( rec_comp.value )
        LOOP

            v_cd_shoe_component_attributes = rec_attrib.key;


            SELECT cd_shoe_prd_component_x_shoe_attributes
              INTO v_cd_shoe_prd_component_x_shoe_attributes
           FROM "SHOE_LEVEL_PRD_COMPONENT_X_SHOE_ATTRIBUTES"
          WHERE cd_shoe_level_x_product_component = v_cd_shoe_level_x_product_component
            AND cd_shoe_component_attributes      = v_cd_shoe_component_attributes;

            FOR rec_parts IN select * from json_each ( rec_attrib.value )
            LOOP


                --RAISE NOTICE '% %', v_cd_product_component, rec_parts.key;

                v_cd_shoe_component_attribute_items = rec_parts.value;
                v_nr_part  = rec_parts.key;

                SELECT cd_shoe_sku_component
                  INTO v_cd_shoe_sku_component
                  FROM "SHOE_SKU_COMPONENT"
                WHERE cd_shoe_sku                   = p_cd_shoe_sku
                  AND cd_shoe_level_x_product_component = v_cd_shoe_level_x_product_component
                  AND nr_part                           = v_nr_part;
                
                UPDATE dataAttributes
                   SET cd_shoe_component_attribute_items       = v_cd_shoe_component_attribute_items
                 WHERE cd_shoe_sku_component               = v_cd_shoe_sku_component
                  AND cd_shoe_prd_component_x_shoe_attributes = v_cd_shoe_prd_component_x_shoe_attributes;

                IF NOT FOUND THEN
                    INSERT INTO dataAttributes (cd_shoe_sku_component, cd_shoe_prd_component_x_shoe_attributes, cd_shoe_component_attribute_items )
                    VALUES (v_cd_shoe_sku_component, v_cd_shoe_prd_component_x_shoe_attributes, v_cd_shoe_component_attribute_items);
                END IF;

                    --RAISE NOTICE 'Comp % Part % Prod %', v_cd_product_component, v_nr_part, v_cd_product ;

            END LOOP;

        END LOOP;

        

    END LOOP;


    
    -- atualizo todos que podem ser atualizados
    UPDATE "SHOE_SKU_COMPONENT_ATTRIBUTES" a
       SET cd_shoe_component_attribute_items  = CASE WHEN b.cd_shoe_component_attribute_items = -1 
                                                      THEN NULL 
                                                      ELSE  b.cd_shoe_component_attribute_items 
                                                END
     FROM dataAttributes b
    WHERE a.cd_shoe_sku_component               = b.cd_shoe_sku_component
      AND a.cd_shoe_prd_component_x_shoe_attributes = b.cd_shoe_prd_component_x_shoe_attributes;

    
    -- insiro o que nao existe ainda
    INSERT INTO "SHOE_SKU_COMPONENT_ATTRIBUTES" (cd_shoe_sku_component,cd_shoe_prd_component_x_shoe_attributes, cd_shoe_component_attribute_items )
    SELECT cd_shoe_sku_component , 
           cd_shoe_prd_component_x_shoe_attributes,
            CASE WHEN cd_shoe_component_attribute_items = -1 THEN NULL ELSE  cd_shoe_component_attribute_items END
      FROM dataAttributes a
    WHERE NOT EXISTS ( SELECT 1 
                         FROM "SHOE_SKU_COMPONENT_ATTRIBUTES" b 
                         WHERE a.cd_shoe_sku_component               = b.cd_shoe_sku_component
                           AND a.cd_shoe_prd_component_x_shoe_attributes = b.cd_shoe_prd_component_x_shoe_attributes
                    )
      AND EXISTS ( SELECT 1
                     FROM "SHOE_SKU_COMPONENT" 
                   WHERE cd_shoe_sku_component = a.cd_shoe_sku_component 
          );

    v_nr_part_count = 0;
    v_cd_product_component_old = -1;


    FOR updPart IN SELECT a.cd_shoe_sku_component, b.cd_product_component, a.nr_part
                     FROM "SHOE_SKU_COMPONENT" a,
                          "SHOE_LEVEL_X_PRODUCT_COMPONENT" b
                    WHERE a.cd_shoe_sku                   = p_cd_shoe_sku
                      AND a.cd_shoe_level_x_product_component = b.cd_shoe_level_x_product_component
                   ORDER BY 2, 3
    LOOP

        IF v_cd_product_component_old != updPart.cd_product_component THEN

            v_cd_product_component_old = updPart.cd_product_component;
            v_nr_part_count = 1;
         ELSE
            v_nr_part_count = v_nr_part_count + 1;
        END IF;

        UPDATE "SHOE_SKU_COMPONENT" 
           SET nr_part = v_nr_part_count
         WHERE cd_shoe_sku_component = updPart.cd_shoe_sku_component
          AND nr_part != v_nr_part_count;


    END LOOP;



    RETURN QUERY select * from dataAttributes;



END;
$$
LANGUAGE plpgsql;


/*
SELECT spec.updShoeSkuComponent(57,'{"recid":57,"cd_shoe_specification":116,"cd_ifi":2,"cd_color_stitch_upper":6,"cd_color_stitch_sock":6,"cd_color_stitch_sole":6,"cd_color_sole_edge":4,"ds_shoe_sku":"NEW1",
"component":{"2":{"1":216},"3":{"1":234},"4":{"1":234}},
"color":{"2":{"1":6},"3":{"1":5},"4":{"1":5}}}'::json);

*/