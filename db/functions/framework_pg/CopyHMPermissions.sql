
CREATE OR REPLACE FUNCTION public.CopyHMPermissions
(
    p_cd_human_resource_from integer,
    p_cd_human_resource_to integer,
    p_copy_job      char(1)
)
RETURNS void AS
$$
DECLARE
    v_record record;
    v_cd_human_resource_x_factory integer;
    v_dt_deactivated timestamp without time zone;

    
BEGIN


        
    IF p_copy_job = 'Y' THEN
        FOR v_record IN select * from "JOBS_HUMAN_RESOURCE" where cd_human_resource = p_cd_human_resource_from AND dt_deactivated IS NULL
        LOOP

            select dt_deactivated INTO v_dt_deactivated FROM "JOBS_HUMAN_RESOURCE" 
            WHERE cd_human_resource = p_cd_human_resource_to 
              AND cd_jobs       = v_record.cd_jobs;
           
            IF NOT FOUND THEN
                INSERT INTO "JOBS_HUMAN_RESOURCE" (cd_human_resource, cd_jobs)
                values (p_cd_human_resource_to, v_record.cd_jobs);
            
            ELSEIF v_dt_deactivated IS NOT NULL THEN

                UPDATE "JOBS_HUMAN_RESOURCE" set dt_deactivated = NULL
                 WHERE cd_human_resource = p_cd_human_resource_to AND cd_jobs = v_record.cd_jobs;
                

            END IF;
        
        END LOOP;
    END IF;
   


END;
$$
LANGUAGE plpgsql;
