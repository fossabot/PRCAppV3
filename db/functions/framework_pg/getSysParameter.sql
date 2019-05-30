
CREATE OR REPLACE FUNCTION public.getSysParameter
(
    p_parameter text
)
RETURNS text AS
$$
DECLARE
v_result text;
BEGIN

   SELECT ds_system_parameters_value
     INTO v_result
     FROM "SYSTEM_PARAMETERS"
    WHERE ds_system_parameters_id = p_parameter;

return v_result;
END;
$$
LANGUAGE plpgsql;
