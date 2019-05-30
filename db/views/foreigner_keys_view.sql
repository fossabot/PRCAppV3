-- View: foreigner_keys_view

-- DROP VIEW foreigner_keys_view;

CREATE OR REPLACE VIEW foreigner_keys_view AS 
 SELECT tc.table_name,
    kcu.column_name,
    ccu.table_name AS foreign_table_name,
    ccu.column_name AS foreign_column_name,
    ( SELECT cgb.column_name
           FROM information_schema.key_column_usage cgb
          WHERE cgb.table_name::text = ccu.table_name::text AND cgb.constraint_name::text ~~ 'IUN%001'::text) AS foreigner_desc_column
   FROM information_schema.table_constraints tc
     JOIN information_schema.key_column_usage kcu ON tc.constraint_name::text = kcu.constraint_name::text
     JOIN information_schema.constraint_column_usage ccu ON ccu.constraint_name::text = tc.constraint_name::text
  WHERE tc.constraint_type::text = 'FOREIGN KEY'::text
    AND ( SELECT count(1)
           FROM information_schema.key_column_usage cgb
          WHERE cgb.table_name::text = ccu.table_name::text AND cgb.constraint_name::text ~~ 'IUN%001'::text) = 1;

ALTER TABLE foreigner_keys_view
  OWNER TO postgres;

