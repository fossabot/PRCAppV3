
CREATE OR REPLACE FUNCTION public.cangenreport
(
    p_var_id         int,
    p_var_hash varchar
)
RETURNS varchar AS
$$
DECLARE
v_can varchar;
BEGIN
   
SELECT CASE 
          WHEN EXISTS ( SELECT 1 
                          FROM "SYSTEM_REPORTS_AUTHORIZATION" 
                         WHERE cd_system_reports = p_var_id 
                           AND ds_authorization = p_var_hash ) THEN 'Y' ELSE 'N' END
       INTO v_can;


IF v_can = 'Y' THEN

DELETE FROM "SYSTEM_REPORTS_AUTHORIZATION" 
  WHERE cd_system_reports = p_var_id 
    AND ds_authorization = p_var_hash;
END IF;


return v_can;
END;
$$
LANGUAGE plpgsql;
