CREATE OR REPLACE FUNCTION schedule.retNextVersion(p_cd_project_build bigint, p_cd_project bigint, p_cd_project_model bigint)
    RETURNS integer as

$$
DECLARE
    vnr_version integer;

 
BEGIN

    -- just in case receiving null
    p_cd_project_model = COALESCE(p_cd_project_model, -1);

   UPDATE schedule."PROJECT_BUILD_VERSION" 
      SET nr_version = nr_version + 1 
    WHERE cd_project_build = p_cd_project_build
      AND cd_project = p_cd_project
      AND cd_project_model = p_cd_project_model;

  -- If not found, insert into the table
  IF NOT FOUND THEN
    INSERT INTO  schedule."PROJECT_BUILD_VERSION" (cd_project_build, cd_project, cd_project_model, nr_version)
    VALUES ( p_cd_project_build, p_cd_project, p_cd_project_model, 1 );
  END IF;

  SELECT nr_version INTO vnr_version
     FROM schedule."PROJECT_BUILD_VERSION" 
    WHERE cd_project_build = p_cd_project_build
      AND cd_project = p_cd_project
      AND cd_project_model = p_cd_project_model;

      RETURN vnr_version;

END;

$$
LANGUAGE plpgsql
VOLATILE
COST 100;

