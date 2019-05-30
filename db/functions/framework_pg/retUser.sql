CREATE OR REPLACE FUNCTION public.getUsers()
    RETURNS table (ds_hr text) as
$BODY$

   select ds_human_resource_full from "HUMAN_RESOURCE";

$BODY$
LANGUAGE sql
VOLATILE
COST 100;