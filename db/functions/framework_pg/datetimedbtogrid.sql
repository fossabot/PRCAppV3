
CREATE OR REPLACE FUNCTION public.datetimedbtogrid
(
    p_var_name timestamp
)
RETURNS varchar AS
$$
DECLARE
v_result varchar (20);
BEGIN
   IF p_var_name IS NULL THEN
      v_result = '';
   ELSE
      v_result = to_char(p_var_name, 'mm/dd/yyyy HH24:MI');
   END IF;

return v_result;
END;
$$
LANGUAGE plpgsql;

