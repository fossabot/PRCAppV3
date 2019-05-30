CREATE OR REPLACE FUNCTION public.retResultSetChangeKey 
(
    p_resultset hstore,
    p_diflabels json
)

RETURNS json AS
$$
DECLARE 
    r record;
    hrecordset hstore;


BEGIN

   hrecordset = ''::hstore;

   FOR r IN SELECT * FROM json_object_keys (p_diflabels)
   LOOP

      hrecordset = hrecordset || hstore( p_diflabels ->> r.json_object_keys , p_resultset->r.json_object_keys);

   END LOOP;

  return  hstore_to_json( hrecordset );
END;
$$

LANGUAGE plpgsql;

