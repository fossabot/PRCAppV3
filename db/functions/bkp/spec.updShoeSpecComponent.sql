CREATE OR REPLACE FUNCTION spec.updShoeSpecComponent
(
    p_cd_shoe_specification integer, 
    jsonData      json
)
RETURNS  SETOF "SHOE_SPECIFICATION_COMPONENT_ATTRIBUTES" AS
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
    v_cd_shoe_specification_component         integer;
    updPart       record;
    v_nr_part_count integer;
    v_cd_product_component_old integer;

    

BEGIN

    CREATE TEMP TABLE dataComponent ON COMMIT DROP AS
    SELECT * 
      FROM "SHOE_SPECIFICATION_COMPONENT"
     WHERE cd_shoe_specification = p_cd_shoe_specification;

    -- insiro os dados dos inherits caso jah nao esteja!
    INSERT INTO dataComponent (cd_shoe_specification, 
                                   cd_shoe_level_x_product_component, 
                                   nr_part, 
                                   cd_product, 
                                   cd_color)

    SELECT     ss.cd_shoe_specification,
               lss.cd_shoe_level_x_product_component,
               cc.nr_part,
               cc.cd_product,
               cc.cd_color
          FROM "SHOE_SPECIFICATION" ss,
               "CONSTRUCTION_COMPONENT" cc,
               "SHOE_LEVEL_X_PRODUCT_COMPONENT" lcc,
               "SHOE_LEVEL_X_PRODUCT_COMPONENT" lss

         WHERE ss.cd_shoe_specification   		  = p_cd_shoe_specification
           AND cc.cd_construction         		  = ss.cd_construction

           AND lcc.cd_shoe_level_x_product_component = cc.cd_shoe_level_x_product_component

           AND lcc.cd_shoe_level                     = lss.cd_shoe_level_inherit
           AND lcc.cd_product_component              = lss.cd_product_component
           AND lss.cd_shoe_level                     = 2
           AND NOT EXISTS ( SELECT 1
                             FROM dataComponent x
                            WHERE x.cd_shoe_specification = ss.cd_shoe_specification
                              AND x.cd_shoe_level_x_product_component = lss.cd_shoe_level_x_product_component
                              AND x.nr_part                           = cc.nr_part
                           );




    CREATE TEMP TABLE dataAttributes ON COMMIT DROP AS
    SELECT * 
      FROM "SHOE_SPECIFICATION_COMPONENT_ATTRIBUTES" 
     WHERE EXISTS ( SELECT 1
                     FROM "SHOE_SPECIFICATION_COMPONENT" x
                    WHERE x.cd_shoe_specification = p_cd_shoe_specification 
                      AND x.cd_shoe_specification_component = "SHOE_SPECIFICATION_COMPONENT_ATTRIBUTES".cd_shoe_specification_component
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
           AND cd_shoe_level        = 2;

        
        FOR rec_parts IN select * from json_each ( rec_comp.value )
        LOOP

            v_cd_product           = rec_parts.value;
            v_nr_part              = rec_parts.key;
           
            UPDATE dataComponent
               SET cd_product = v_cd_product
             WHERE cd_shoe_level_x_product_component = v_cd_shoe_level_x_product_component
               AND nr_part                           = v_nr_part;


            IF NOT FOUND THEN
                INSERT INTO dataComponent (cd_shoe_specification, cd_shoe_level_x_product_component,nr_part, cd_product )
                VALUES (p_cd_shoe_specification, v_cd_shoe_level_x_product_component, v_nr_part, v_cd_product);
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
           AND cd_shoe_level        = 2;
        
        
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
    DELETE FROM "SHOE_SPECIFICATION_COMPONENT" AS a
      USING dataComponent b
     WHERE a.cd_shoe_specification = p_cd_shoe_specification
       AND b.cd_product      = -2
       AND a.cd_shoe_level_x_product_component = b.cd_shoe_level_x_product_component
       AND a.nr_part = b.nr_part;

     
    -- apago a temporaria!
    DELETE FROM dataComponent WHERE cd_product  = -2;

    
    -- atualizo todos que podem ser atualizados
    UPDATE "SHOE_SPECIFICATION_COMPONENT" a
       SET cd_product                        = CASE WHEN b.cd_product IS NULL THEN a.cd_product
                                                      WHEN b.cd_product = -1 THEN NULL ELSE  b.cd_product END,

           cd_color                          = CASE WHEN b.cd_color IS NULL THEN a.cd_color
                                                      WHEN b.cd_color = -1 THEN NULL ELSE  b.cd_color END
     FROM dataComponent b
    WHERE a.cd_shoe_level_x_product_component = b.cd_shoe_level_x_product_component
      AND a.nr_part                           = b.nr_part;

    
    -- insiro o que nao existe ainda
    INSERT INTO "SHOE_SPECIFICATION_COMPONENT" (cd_shoe_specification, cd_shoe_level_x_product_component,nr_part, cd_color, cd_product )
    SELECT p_cd_shoe_specification , cd_shoe_level_x_product_component,nr_part, cd_color, cd_product
      FROM dataComponent x
    WHERE NOT EXISTS ( SELECT 1 
                         FROM "SHOE_SPECIFICATION_COMPONENT" 
                       WHERE cd_shoe_specification                   = p_cd_shoe_specification 
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

                SELECT cd_shoe_specification_component
                  INTO v_cd_shoe_specification_component
                  FROM "SHOE_SPECIFICATION_COMPONENT"
                WHERE cd_shoe_specification                   = p_cd_shoe_specification
                  AND cd_shoe_level_x_product_component = v_cd_shoe_level_x_product_component
                  AND nr_part                           = v_nr_part;
                
                UPDATE dataAttributes
                   SET cd_shoe_component_attribute_items       = v_cd_shoe_component_attribute_items
                 WHERE cd_shoe_specification_component               = v_cd_shoe_specification_component
                  AND cd_shoe_prd_component_x_shoe_attributes = v_cd_shoe_prd_component_x_shoe_attributes;

                IF NOT FOUND THEN
                    INSERT INTO dataAttributes (cd_shoe_specification_component, cd_shoe_prd_component_x_shoe_attributes, cd_shoe_component_attribute_items )
                    VALUES (v_cd_shoe_specification_component, v_cd_shoe_prd_component_x_shoe_attributes, v_cd_shoe_component_attribute_items);
                END IF;

                    --RAISE NOTICE 'Comp % Part % Prod %', v_cd_product_component, v_nr_part, v_cd_product ;

            END LOOP;

        END LOOP;

        

    END LOOP;


    
    -- atualizo todos que podem ser atualizados
    UPDATE "SHOE_SPECIFICATION_COMPONENT_ATTRIBUTES" a
       SET cd_shoe_component_attribute_items  = CASE WHEN b.cd_shoe_component_attribute_items = -1 
                                                      THEN NULL 
                                                      ELSE  b.cd_shoe_component_attribute_items 
                                                END
     FROM dataAttributes b
    WHERE a.cd_shoe_specification_component               = b.cd_shoe_specification_component
      AND a.cd_shoe_prd_component_x_shoe_attributes = b.cd_shoe_prd_component_x_shoe_attributes;

    
    -- insiro o que nao existe ainda
    INSERT INTO "SHOE_SPECIFICATION_COMPONENT_ATTRIBUTES" (cd_shoe_specification_component,cd_shoe_prd_component_x_shoe_attributes, cd_shoe_component_attribute_items )
    SELECT cd_shoe_specification_component , 
           cd_shoe_prd_component_x_shoe_attributes,
            CASE WHEN cd_shoe_component_attribute_items = -1 THEN NULL ELSE  cd_shoe_component_attribute_items END
      FROM dataAttributes a
    WHERE NOT EXISTS ( SELECT 1 
                         FROM "SHOE_SPECIFICATION_COMPONENT_ATTRIBUTES" b 
                         WHERE a.cd_shoe_specification_component               = b.cd_shoe_specification_component
                           AND a.cd_shoe_prd_component_x_shoe_attributes = b.cd_shoe_prd_component_x_shoe_attributes
                    )
      AND EXISTS ( SELECT 1
                     FROM "SHOE_SPECIFICATION_COMPONENT" 
                   WHERE cd_shoe_specification_component = a.cd_shoe_specification_component 
          );

    v_nr_part_count = 0;
    v_cd_product_component_old = -1;


    FOR updPart IN SELECT a.cd_shoe_specification_component, b.cd_product_component, a.nr_part
                     FROM "SHOE_SPECIFICATION_COMPONENT" a,
                          "SHOE_LEVEL_X_PRODUCT_COMPONENT" b
                    WHERE a.cd_shoe_specification                   = p_cd_shoe_specification
                      AND a.cd_shoe_level_x_product_component = b.cd_shoe_level_x_product_component
                   ORDER BY 2, 3
    LOOP

        IF v_cd_product_component_old != updPart.cd_product_component THEN

            v_cd_product_component_old = updPart.cd_product_component;
            v_nr_part_count = 1;
         ELSE
            v_nr_part_count = v_nr_part_count + 1;
        END IF;

        UPDATE "SHOE_SPECIFICATION_COMPONENT" 
           SET nr_part = v_nr_part_count
         WHERE cd_shoe_specification_component = updPart.cd_shoe_specification_component
          AND nr_part != v_nr_part_count;


    END LOOP;



    RETURN QUERY select * from dataAttributes;



END;
$$
LANGUAGE plpgsql;


/*
TESTES

select key, value from json_each ('{"component":{"5":{"1":236,"2":236},"10":{"1":238,"2":237,"3":237},"13":{"1":238,"2":237,"3":237},"19":{"1":242,"2":240}},"attribute":{"10":{"1":{"1":"2","2":"1","3":"2"}},"5":{"5":{"1":"12","2":"8"},"4":{"1":"2","2":"1"},"2":{"1":"2","2":"2"}}},"color":{"5":{"1":"6","2":"5"},"13":{"1":"5","2":"6","3":"4"}},"recid":"3"}'::json)
where key = 'attribute'

select key, value from json_each ('{"c  omponent":{"5":{"1":236,"2":236},"10":{"1":238,"2":237,"3":237},"13":{"1":238,"2":237,"3":237},"19":{"1":242,"2":240}},"attribute":{"10":{"1":{"1":"2","2":"1","3":"2"}},"5":{"5":{"1":"12","2":"8"},"4":{"1":"2","2":"1"},"2":{"1":"2","2":"2"}}},"color":{"5":{"1":"6","2":"5"},"13":{"1":"5","2":"6","3":"4"}},"recid":"3"}'::json)
where key = 'color'

select key, value from json_each ('{"5":{"1":"6","2":"5"},"13":{"1":"5","2":"6","3":"4"}}'::json)
where key = 'color'


select key, value from json_each ('{"component":{"5":{"1":236,"2":236},"10":{"1":238,"2":237,"3":237},"13":{"1":238,"2":237,"3":237},"19":{"1":242,"2":240}},"attribute":{"10":{"1":{"1":"2","2":"1","3":"2"}},"5":{"5":{"1":"12","2":"8"},"4":{"1":"2","2":"1"},"2":{"1":"2","2":"2"}}},"color":{"5":{"1":"6","2":"5"},"13":{"1":"5","2":"6","3":"4"}},"recid":"3"}'::json)
where key = 'component'

select updShoeSpecComponent(3, '{ 
                         "component":{"5":{"1":236,"2":236},"10":{"1":238,"2":237,"3":237},"13":{"1":238,"2":237,"3":237},"19":{"1":242,"2":240}},
                         "attribute":{"10":{"1":{"1":2,"2":1,"3":2}},"5":{"5":{"1":12,"2":8},"4":{"1":2,"2":1},"2":{"1":2,"2":2}}},
                             "color":{"5":{"1":6,"2":5},"13":{"1":5,"2":6,"3":4}},"recid":3}'::json)




*/