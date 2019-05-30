
CREATE OR REPLACE FUNCTION public.checkMenuPermission
(
    p_var_controller varchar
)
RETURNS varchar AS
$$
DECLARE
v_cd_human_resource integer;
v_cd_menu integer;
v_result  varchar;
v_fl_always_available char(1);
v_fl_super_user char(1);
v_cd_system_product_category integer;

BEGIN
   
    v_cd_system_product_category = get_var('cd_system_product_category');

   SELECT get_var('cd_human_resource') 
     INTO v_cd_human_resource;

   SELECT fl_super_user
     INTO v_fl_super_user
    FROM "HUMAN_RESOURCE" 
   WHERE cd_human_resource = v_cd_human_resource;

   SELECT min(cd_menu), min(fl_always_available)
     INTO v_cd_menu, v_fl_always_available
     FROM "MENU"
    WHERE ds_controller = p_var_controller
       AND ( fl_only_for_super_users = 'N' OR v_fl_super_user = 'Y' )
       OR ds_controller like '%/' || p_var_controller;


    IF v_fl_always_available = 'Y' THEN
      RETURN 'Y';
   END IF;




   SELECT CASE 
          WHEN EXISTS ( SELECT 1 
                         FROM "HUMAN_RESOURCE" h
                         WHERE h.cd_human_resource = v_cd_human_resource 
                           AND EXISTS ( SELECT 1
                                          FROM "HUMAN_RESOURCE_MENU" hm
                                         WHERE hm.cd_human_resource = v_cd_human_resource
                                           AND hm.cd_menu              = v_cd_menu
                                       )
                           OR EXISTS ( SELECT 1
                                         FROM "JOBS_HUMAN_RESOURCE" jh,
                                              "JOBS_MENU" jm,
                                              "JOBS" j
                                        WHERE jh.cd_human_resource = v_cd_human_resource
                                          AND jm.cd_jobs           = jh.cd_jobs
                                          AND jm.cd_menu           = v_cd_menu
                                          AND j.cd_jobs            = jh.cd_jobs
                                          AND j.dt_deactivated IS NULL
                                          AND j.cd_system_product_category = v_cd_system_product_category
                                     )

                         ) THEN 'Y' ELSE retDescTranslated ('You have no rights to this Option', null) END
    INTO v_result;





return v_result;
END;
$$
LANGUAGE plpgsql;
