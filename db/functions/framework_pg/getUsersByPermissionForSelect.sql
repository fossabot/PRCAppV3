
CREATE OR REPLACE FUNCTION public.getUsersByPermissionForSelect(PAR_cd_human_resource integer, PAR_permission_find text)
  RETURNS TABLE (
  cd_jobs                 integer,
  cd_jobs_responsible     integer,
  ds_jobs                 varchar(64),
  nr_depth                integer,
  cd_human_resource       integer,
  ds_human_resource_full  varchar(128),
  ds_e_mail               varchar(64)
)   AS
$$
DECLARE 
--r record;
--p record;
--q record;
v_cd_system_product_category  int;


BEGIN

       v_cd_system_product_category = get_var('cd_system_product_category');

    
        RETURN QUERY 
        WITH RECURSIVE childrenUp AS (
           SELECT j.cd_jobs, j.cd_jobs_responsible, j.ds_jobs, 1 AS nr_depth,  h.cd_human_resource, h.ds_human_resource_full, h.ds_e_mail, s.ds_system_permission_id              
             FROM "JOBS" j, "JOBS_HUMAN_RESOURCE" jh, "HUMAN_RESOURCE" h, "JOBS_SYSTEM_PERMISSION" p, "SYSTEM_PERMISSION" s
            WHERE j.dt_deactivated IS NULL
              AND jh.cd_jobs = j.cd_jobs
              AND h.cd_human_resource = jh.cd_human_resource
              --AND h.dt_deactivated IS NULL
              AND h.cd_human_resource = PAR_cd_human_resource
              AND p.cd_jobs = j.cd_jobs
              AND s.cd_system_permission = p.cd_system_permission
              AND j.cd_system_product_category = v_cd_system_product_category
              --AND ';' || PAR_ds_permission_user || ';' ilike '%;' || s.ds_system_permission_id || ';%'

     UNION
           SELECT j.cd_jobs, j.cd_jobs_responsible, j.ds_jobs, b.nr_depth+1, h.cd_human_resource, h.ds_human_resource_full, h.ds_e_mail, s.ds_system_permission_id
             FROM "JOBS" j, "JOBS_HUMAN_RESOURCE" jh, "HUMAN_RESOURCE" h, "JOBS_SYSTEM_PERMISSION" p, "SYSTEM_PERMISSION" s, childrenUp b
            WHERE j.dt_deactivated IS NULL
              AND jh.cd_jobs = j.cd_jobs
              AND h.cd_human_resource = jh.cd_human_resource
              --AND h.dt_deactivated IS NULL
              AND p.cd_jobs = j.cd_jobs
              AND s.cd_system_permission = p.cd_system_permission
              AND b.cd_jobs_responsible  = j.cd_jobs  
              AND j.cd_system_product_category = v_cd_system_product_category
         ) SELECT childrenUp.cd_jobs,
            childrenUp.cd_jobs_responsible,
            childrenUp.ds_jobs,
            childrenUp.nr_depth,
            childrenUp.cd_human_resource  ,
            childrenUp.ds_human_resource_full ,
            childrenUp.ds_e_mail
        FROM childrenUp WHERE ';' || PAR_permission_find || ';' ilike '%;' || ds_system_permission_id || ';%';
    

         
    
        RETURN QUERY
        WITH RECURSIVE childrenUp AS (
           SELECT j.cd_jobs, j.cd_jobs_responsible, j.ds_jobs, 1 AS nr_depth,  h.cd_human_resource, h.ds_human_resource_full, h.ds_e_mail, s.ds_system_permission_id              
             FROM "JOBS" j, "JOBS_HUMAN_RESOURCE" jh, "HUMAN_RESOURCE" h, "JOBS_SYSTEM_PERMISSION" p, "SYSTEM_PERMISSION" s
            WHERE j.dt_deactivated IS NULL
              AND jh.cd_jobs = j.cd_jobs
              AND h.cd_human_resource = jh.cd_human_resource
              --AND h.dt_deactivated IS NULL
              AND h.cd_human_resource = PAR_cd_human_resource
              AND p.cd_jobs = j.cd_jobs
              AND s.cd_system_permission = p.cd_system_permission
              AND j.cd_system_product_category = v_cd_system_product_category

     UNION
           SELECT j.cd_jobs, j.cd_jobs_responsible, j.ds_jobs, b.nr_depth+1, h.cd_human_resource, h.ds_human_resource_full, h.ds_e_mail, s.ds_system_permission_id              
             FROM "JOBS" j, "JOBS_HUMAN_RESOURCE" jh, "HUMAN_RESOURCE" h, "JOBS_SYSTEM_PERMISSION" p, "SYSTEM_PERMISSION" s, childrenUp b
            WHERE j.dt_deactivated IS NULL
              AND jh.cd_jobs = j.cd_jobs
              AND h.cd_human_resource = jh.cd_human_resource
              --AND h.dt_deactivated IS NULL
              AND p.cd_jobs = j.cd_jobs
              AND s.cd_system_permission = p.cd_system_permission
              AND j.cd_jobs_responsible = b.cd_jobs
         ) SELECT childrenUp.cd_jobs,
            childrenUp.cd_jobs_responsible,
            childrenUp.ds_jobs,
            childrenUp.nr_depth,
            childrenUp.cd_human_resource  ,
            childrenUp.ds_human_resource_full ,
            childrenUp.ds_e_mail
        FROM childrenUp WHERE ';' || PAR_permission_find || ';' ilike '%;' || ds_system_permission_id || ';%';


        RETURN QUERY SELECT j.cd_jobs, j.cd_jobs_responsible, j.ds_jobs, 0 as nr_depth, h.cd_human_resource, h.ds_human_resource_full, h.ds_e_mail
             FROM "JOBS" j, "JOBS_HUMAN_RESOURCE" jh, "HUMAN_RESOURCE" h, "JOBS_SYSTEM_PERMISSION" p, "SYSTEM_PERMISSION" s
            WHERE j.dt_deactivated IS NULL
              AND jh.cd_jobs = j.cd_jobs
              AND h.cd_human_resource = jh.cd_human_resource
              --AND h.dt_deactivated IS NULL
              AND p.cd_jobs = j.cd_jobs
              AND s.cd_system_permission = p.cd_system_permission
              AND j.cd_system_product_category = v_cd_system_product_category
              AND  ';' || PAR_permission_find || ';' ilike '%;' || s.ds_system_permission_id || ';%'
              AND EXISTS ( SELECT 1 FROM "JOBS_HUMAN_RESOURCE" x where x.cd_jobs = j.cd_jobs AND x.cd_human_resource = PAR_cd_human_resource AND x.dt_deactivated IS NULL );
              
              



   /*

    FOR q IN SELECT *
               FROM 
         where ds_d IS NOT NULL AND ds_d != 'Series3.'
     LOOP

    
    END LOOP;

*/


END
$$  LANGUAGE plpgsql;
