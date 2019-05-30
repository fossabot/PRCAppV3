
CREATE OR REPLACE FUNCTION public.datedbtogrid
(
    p_var_name timestamp with timestamptz
)
RETURNS varchar AS
$$
DECLARE
v_result varchar (10);
BEGIN
   IF p_var_name IS NULL THEN
      v_result = '';
   ELSE
      v_result = to_char(p_var_name, 'mm/dd/yyyy');
   END IF;

return v_result;
END;
$$
LANGUAGE plpgsql;

