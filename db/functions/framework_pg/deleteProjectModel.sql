
CREATE OR REPLACE FUNCTION public.deleteProjectModel(PAR_cd_project_model bigint)
  RETURNS void    AS
$$
DECLARE 

BEGIN

    UPDATE "TR_TEST_REQUEST" 
    SET cd_project_model = NULL 
    WHERE cd_project_model = PAR_cd_project_model;
    
    DELETE FROM "PROJECT_COMMENTS" where cd_project_model = PAR_cd_project_model;
    DELETE from "PROJECT_BUILD_SCHEDULE" where cd_project_model = PAR_cd_project_model;
    DELETE FROM "PROJECT_MODEL"  where cd_project_model = PAR_cd_project_model;

   



END
$$  LANGUAGE plpgsql;
ALTER FUNCTION public.deleteProjectModel(bigint) SET search_path=pg_catalog, public, tti,tr, schedule;
