-- View: spec."vSHOE_SKU_COMPONENT"

-- DROP VIEW spec."vSHOE_SKU_COMPONENT";

CREATE OR REPLACE VIEW spec."vSHOE_SKU_COMPONENT" AS 
 SELECT c.cd_shoe_sku_component,
    c.cd_shoe_sku,
    c.cd_shoe_level_x_product_component,
    l.cd_shoe_level,
    ( SELECT "SHOE_LEVEL".ds_shoe_level
           FROM "SHOE_LEVEL"
          WHERE "SHOE_LEVEL".cd_shoe_level = l.cd_shoe_level) AS ds_shoe_level,
    l.cd_product_component,
    ( SELECT "PRODUCT_COMPONENT".ds_product_component
           FROM "PRODUCT_COMPONENT"
          WHERE "PRODUCT_COMPONENT".cd_product_component = l.cd_product_component) AS ds_product_component,
    l.nr_order AS nr_order_component,
    c.nr_part,
    c.cd_product,
    ( SELECT "PRODUCT".ds_product
           FROM "PRODUCT"
          WHERE "PRODUCT".cd_product = c.cd_product) AS ds_product,
    a.cd_shoe_sku_component_attributes,
    a.cd_shoe_component_attribute_items,
    ( SELECT "SHOE_COMPONENT_ATTRIBUTE_ITEMS".ds_shoe_component_attribute_items
           FROM "SHOE_COMPONENT_ATTRIBUTE_ITEMS"
          WHERE "SHOE_COMPONENT_ATTRIBUTE_ITEMS".cd_shoe_component_attribute_items = a.cd_shoe_component_attribute_items) AS ds_shoe_component_attribute_items,
    a.cd_shoe_prd_component_x_shoe_attributes,
    sa.cd_system_label_type,
    sl.ds_system_label_type,
    sl.ds_system_identifier,
    sa.ds_shoe_component_attributes_title,
    sa.ds_shoe_component_attributes_abbreviation,
    sa.nr_component_attribute_size_frame,
    sa.nr_title_size_frame,
    sa.nr_order AS nr_order_attribute,
    l.fl_has_color,
    l.fl_demanded,
    sa.cd_shoe_component_attributes,
    c.cd_color,
    ( SELECT "COLOR".ds_color
           FROM "COLOR"
          WHERE "COLOR".cd_color = c.cd_color) AS ds_color,
        CASE
            WHEN l.cd_shoe_level_inherit IS NOT NULL THEN 'N'::text
            ELSE 'Y'::text
        END AS fl_can_change_component,
        CASE
            WHEN (EXISTS ( SELECT 1
               FROM "SHOE_LEVEL_X_PRODUCT_COMPONENT" p
              WHERE p.cd_shoe_level = l.cd_shoe_level_inherit AND p.cd_product_component = l.cd_product_component AND p.fl_has_color = 'Y'::bpchar AND p.dt_deactivated IS NULL)) THEN 'N'::text
            ELSE 'Y'::text
        END AS fl_can_change_color,
    l.fl_has_consumption,
    COALESCE(c.nr_length, 0::numeric(12,4)) AS nr_length,
    c.cd_unit_measure_length,
    uml.ds_unit_measure AS ds_unit_measure_length,
    ( SELECT "UNIT_MEASURE_TYPE".fl_is_length
           FROM "UNIT_MEASURE_TYPE"
          WHERE "UNIT_MEASURE_TYPE".cd_unit_measure_type = uml.cd_unit_measure_type) AS fl_is_length_length,
    COALESCE(c.nr_width, 0::numeric(12,4)) AS nr_width,
    c.cd_unit_measure_width,
    umw.ds_unit_measure AS ds_unit_measure_width,
    ( SELECT "UNIT_MEASURE_TYPE".fl_is_length
           FROM "UNIT_MEASURE_TYPE"
          WHERE "UNIT_MEASURE_TYPE".cd_unit_measure_type = umw.cd_unit_measure_type) AS fl_is_length_width,
    'Y'::character(1) AS fl_has_component_informed,
    c.ds_color_reference,
    a.cd_color_attribute,
    ( SELECT "COLOR".ds_color
           FROM "COLOR"
          WHERE "COLOR".cd_color = a.cd_color_attribute) AS ds_color_attribute,
    a.ds_text_attribute,
    scas.fl_from_attributes_item,
    scas.fl_from_color,
    scas.fl_from_text,
    COALESCE(sa.fl_only_first_part, 'N'::bpchar) AS fl_only_first_part,
    l.cd_system_product_category

   FROM "SHOE_SKU_COMPONENT" c
     JOIN "SHOE_LEVEL_X_PRODUCT_COMPONENT" l ON l.cd_shoe_level_x_product_component = c.cd_shoe_level_x_product_component
     LEFT JOIN "SHOE_SKU_COMPONENT_ATTRIBUTES" a ON a.cd_shoe_sku_component = c.cd_shoe_sku_component
     LEFT JOIN "SHOE_LEVEL_PRD_COMPONENT_X_SHOE_ATTRIBUTES" sa ON sa.cd_shoe_prd_component_x_shoe_attributes = a.cd_shoe_prd_component_x_shoe_attributes
     LEFT JOIN "SYSTEM_LABEL_TYPE" sl ON sl.cd_system_label_type = sa.cd_system_label_type
     LEFT JOIN "UNIT_MEASURE" uml ON uml.cd_unit_measure = c.cd_unit_measure_length
     LEFT JOIN "UNIT_MEASURE" umw ON umw.cd_unit_measure = c.cd_unit_measure_width
     LEFT JOIN "SHOE_COMPONENT_ATTRIBUTES" sca ON sca.cd_shoe_component_attributes = sa.cd_shoe_component_attributes
     LEFT JOIN "SHOE_COMPONENT_ATTRIBUTES_SOURCE" scas ON scas.cd_shoe_component_attributes_source = sca.cd_shoe_component_attributes_source
  WHERE sl.dt_deactivated IS NULL AND l.dt_deactivated IS NULL AND l.cd_shoe_level = 3;

ALTER TABLE spec."vSHOE_SKU_COMPONENT"
  OWNER TO postgres;
GRANT ALL ON TABLE spec."vSHOE_SKU_COMPONENT" TO postgres;
GRANT SELECT ON TABLE spec."vSHOE_SKU_COMPONENT" TO "mbReport";
GRANT ALL ON TABLE spec."vSHOE_SKU_COMPONENT" TO devshoesdemorole;
