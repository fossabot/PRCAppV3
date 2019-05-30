
CREATE OR REPLACE FUNCTION public.CopyJobPermissions
(
    p_cd_jobs_from integer,
    p_cd_jobs_to integer,
    p_copy_hm      char(1)
)
RETURNS void AS
$$
DECLARE
    v_record record;
    v_cd_jobs_x_factory integer;
    v_dt_deactivated timestamp without time zone;

    
BEGIN
        
    IF p_copy_hm = 'Y' THEN
        FOR v_record IN select * from "JOBS_HUMAN_RESOURCE" where cd_jobs = p_cd_jobs_from AND dt_deactivated IS NULL
        LOOP

            select dt_deactivated INTO v_dt_deactivated FROM "JOBS_HUMAN_RESOURCE" 
            WHERE cd_jobs = p_cd_jobs_to 
              AND cd_human_resource       = v_record.cd_human_resource;
           
            IF NOT FOUND THEN
                INSERT INTO "JOBS_HUMAN_RESOURCE" (cd_jobs, cd_human_resource)
                values (p_cd_jobs_to, v_record.cd_human_resource);
            
            ELSEIF v_dt_deactivated IS NOT NULL THEN

                UPDATE "JOBS_HUMAN_RESOURCE" set dt_deactivated = NULL
                 WHERE cd_jobs = p_cd_jobs_to AND cd_human_resource = v_record.cd_human_resource;
                

            END IF;
        
        END LOOP;
    END IF;
   


END;
$$
LANGUAGE plpgsql;
