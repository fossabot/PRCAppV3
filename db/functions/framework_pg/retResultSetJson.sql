CREATE OR REPLACE FUNCTION public.retResultSetJson 
(
    p_select  text,
    p_diflabels json
)

RETURNS text AS
$$
DECLARE
retJson text;
sqltxt  text;
r record;
fl_firsttime text;

BEGIN
 
   IF p_diflabels IS NOT NULL
   THEN
      sqltxt = 'select json_agg( retResultSetChangeKeyv8( row_to_json(t) , '''|| p_diflabels::text ||'''::json ) ) as row_to_json from (' || p_select || ') t';
   ELSE
      sqltxt = 'select json_agg( row_to_json(t) ) as row_to_json from (' || p_select || ') t';
   END IF;


  EXECUTE sqltxt INTO retJson;

  return  retJson;
END;
$$
LANGUAGE plpgsql;


