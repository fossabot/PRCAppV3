
CREATE OR REPLACE FUNCTION public.retFkDescription
(
    p_table  text,
    p_column text,
    p_code   text
)
RETURNS text AS
$$
DECLARE
fk_table text;
fk_col   text;
fk_desc  text;
d_sql    text;
v_result text;
BEGIN

  SELECT ds_foreign_table_name, 
         ds_foreign_column_name, 
         ds_foreign_desc_column_name 
   INTO fk_table, fk_col, fk_desc
    FROM "SYSTEM_RELATIONS"
   WHERE ds_table_name = p_table AND ds_column_name = p_column;

   IF fk_desc IS NULL THEN
      RETURN null;
   END IF;

   d_sql = 'SELECT '|| fk_desc || ' FROM "' || fk_table
    || '" WHERE '|| fk_col ||' = $1';


   EXECUTE d_sql
   INTO v_result 
  using p_code::integer;

   return  v_result;
END;
$$
LANGUAGE plpgsql;

ALTER FUNCTION retfkdescription(text, text, text) SET search_path=audit, material, public, translation, docrep, spec;
