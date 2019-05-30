CREATE OR REPLACE FUNCTION public.retResultSetChangeKeyv8
(
    p_resultset json,
    p_diflabels json
)

RETURNS json AS
$$
   var ret = {};

   for (var key in p_diflabels) {
      ret[p_diflabels[key]] = p_resultset[key]; 
   }
   
   return ret;

$$
LANGUAGE plv8 IMMUTABLE STRICT;