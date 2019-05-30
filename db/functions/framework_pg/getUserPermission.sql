CREATE OR REPLACE FUNCTION public.getUserPermission
(
    p_parameter   text,
    p_cd_username integer
)
RETURNS text AS
$$
DECLARE
v_result text;
v_cd_system_product_category int;
BEGIN

    v_cd_system_product_category = get_var('cd_system_product_category');
   
   SELECT 
     CASE WHEN EXISTS ( SELECT 1
                        FROM "JOBS_HUMAN_RESOURCE" h,
                             "JOBS_SYSTEM_PERMISSION" p,
                             "SYSTEM_PERMISSION" s,
                             "JOBS" j

   WHERE h.cd_human_resource = p_cd_username
    AND p.cd_jobs = h.cd_jobs
    AND j.cd_jobs = h.cd_jobs
    AND j.cd_system_product_category = v_cd_system_product_category 
    AND s.cd_system_permission = p.cd_system_permission
    AND h.dt_deactivated IS NULL
    AND ';' || p_parameter || ';' ilike '%;' || s.ds_system_permission_id || ';%' 
   ) THEN 'Y' ELSE 'N' END
   INTO v_result;   

 
return v_result;
END;
$$
LANGUAGE plpgsql;
    