CREATE OR REPLACE FUNCTION public.genUniqueCodeFromTwo(a bigint, b bigint)
    RETURNS bigint
AS
$BODY$
DECLARE 
    vret bigint;
BEGIN
    IF a >= b THEN
        vret = a * a + a + b;
    ELSE 
        vret = a + b * b;
    END IF;

    RETURN vret;

END
$BODY$
LANGUAGE plpgsql IMMUTABLE COST 100;