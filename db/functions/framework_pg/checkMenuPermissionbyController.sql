
CREATE OR REPLACE FUNCTION public.getUserMenuPermissionbyController
(
    p_var_controller varchar
)
RETURNS SETOF "HUMAN_RESOURCE" AS

$$
DECLARE
    v_cd_system_product_category integer;
BEGIN
    
    v_cd_system_product_category = get_var('cd_system_product_category');


   RETURN QUERY 
    SELECT hmain.*
    FROM "HUMAN_RESOURCE" hmain
    WHERE hmain.dt_deactivated IS NULL
      AND hmain.cd_human_resource != get_var('cd_human_resource')::integer
      AND ( EXISTS ( SELECT 
                     FROM "HUMAN_RESOURCE_MENU" hm, "MENU" m
                    WHERE hm.cd_human_resource = hmain.cd_human_resource
                      AND hm.cd_menu           = m.cd_menu
                     AND  m.ds_controller =  p_var_controller
                 )
       OR EXISTS ( SELECT 1
                    FROM "JOBS_HUMAN_RESOURCE" jh,
                        "JOBS_MENU" jm,
                        "JOBS" j, 
                        "MENU" m
                   WHERE jh.cd_human_resource = hmain.cd_human_resource
                     AND jm.cd_jobs           = jh.cd_jobs
                     AND j.cd_jobs            = jh.cd_jobs
                     AND j.dt_deactivated IS NULL
                     AND m.cd_menu           = jm.cd_menu
                     AND  m.ds_controller =  p_var_controller
                     AND j.cd_system_product_category = v_cd_system_product_category 
                    )
       OR hmain.fl_super_user = 'Y' );

                         




END;
$$
LANGUAGE plpgsql;
