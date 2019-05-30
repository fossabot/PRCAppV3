CREATE OR REPLACE FUNCTION public.getFilterQueryId
(
    p_query  text
)

RETURNS integer AS
$$
DECLARE
cd_sys_filter_queries_ret integer;
BEGIN

   SELECT cd_sys_filter_queries
     INTO cd_sys_filter_queries_ret
     FROM "SYS_FILTER_QUERIES"
    WHERE ds_sys_filter_queries = p_query;

   IF cd_sys_filter_queries_ret IS NULL THEN
      INSERT INTO "SYS_FILTER_QUERIES" (ds_sys_filter_queries) VALUES (p_query);
      SELECT currval(pg_get_serial_sequence('"SYS_FILTER_QUERIES"', 'cd_sys_filter_queries')) INTO cd_sys_filter_queries_ret;

   END IF;

   RETURN cd_sys_filter_queries_ret;
      

END;
$$
LANGUAGE plpgsql;

