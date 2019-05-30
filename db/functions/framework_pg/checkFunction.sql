
CREATE OR REPLACE FUNCTION public.checkFunction
(
    p_function_name varchar (255)
)
RETURNS SETOF text 
AS
$$
DECLARE
r RECORD;
v_datatype character varying (200); 
v_ret character varying (200); 
v_set text;
BEGIN
   v_datatype = '';
    FOR r IN  select data_type from information_schema.parameters where specific_name in (
                      select specific_name from information_schema.routines where routine_name = lower(p_function_name) )
               and parameter_mode = 'IN'
   LOOP
      IF v_datatype <> '' THEN
         v_datatype = v_datatype || ',';
      END IF;

      v_datatype = v_datatype || r.data_type;
   
   END LOOP;

   v_ret = p_function_name || '('|| v_datatype || ')';

   for r IN select plpgsql_check_function (v_ret::regprocedure) as info
   LOOP
      RETURN NEXT r.info;

   END LOOP;


   --select * from 

   --return 

END;
$$
LANGUAGE plpgsql;
