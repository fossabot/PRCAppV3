CREATE OR REPLACE FUNCTION public.getFilterQuery
(
    p_queryid  integer
)

RETURNS text AS
$$
DECLARE
ds_query text;
BEGIN

   SELECT ds_sys_filter_queries
     INTO ds_query
     FROM "SYS_FILTER_QUERIES"
    WHERE cd_sys_filter_queries = p_queryid;

   RETURN ds_query;
      

END;
$$
LANGUAGE plpgsql;

