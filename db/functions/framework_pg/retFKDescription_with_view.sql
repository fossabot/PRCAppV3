
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

   EXECUTE 'SELECT foreign_table_name, foreign_column_name, foreigner_desc_column FROM foreigner_keys_view '
    || ' WHERE table_name = $1 AND column_name = $2'
   INTO fk_table, fk_col, fk_desc
  using p_table, p_column;


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

