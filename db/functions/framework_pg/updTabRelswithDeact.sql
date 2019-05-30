CREATE OR REPLACE FUNCTION public.updtabrelswithdeact
(
    p_table  text,
    p_column_ak1 text,
    p_column_ak_value integer,
    p_column_ak2  text,
    p_column_ak2_value_to_remove integer[],
    p_column_ak2_value_to_add integer[]
 
)

RETURNS text AS
$$
DECLARE
idak2 integer;
iexists integer;
ds_sql text;
v_result text;
BEGIN

  v_result = 'OK';

  -- primeiro corro os que sao marcados como available (removendo)
   FOREACH idak2 IN ARRAY p_column_ak2_value_to_remove
   LOOP
      EXECUTE 'SELECT 1 FROM "' || p_table || '"'
        || ' WHERE ' || p_column_ak1 || ' = $1 AND ' || p_column_ak2 || ' = $2'
         INTO iexists
        USING p_column_ak_value, idak2;

     IF iexists IS NOT NULL THEN
        ds_sql = 'UPDATE "' || p_table || '" set dt_deactivated = current_timestamp where ' || p_column_ak1 || 
           ' = ' || to_char (p_column_ak_value, '99999999999999999') || ' and ' || p_column_ak2 || 
           ' = ' || to_char (idak2, '99999999999999999');

     EXECUTE ds_sql;
      raise NOTICE '%', ds_sql;

     END IF;



  END LOOP;

  -- depois corro os que sao marcados como available (removendo)
   FOREACH idak2 IN ARRAY p_column_ak2_value_to_add
   LOOP
      raise NOTICE '%', idak2;

      EXECUTE 'SELECT 1 FROM "' || p_table || '"'
        || ' WHERE ' || p_column_ak1 || ' = $1 AND ' || p_column_ak2 || ' = $2'
         INTO iexists
        USING p_column_ak_value, idak2;

     -- se jah existe, atualizo o deactivated!
     IF iexists IS NOT NULL THEN
        ds_sql = 'UPDATE "' || p_table || '" set dt_deactivated = null where ' || p_column_ak1 || 
           ' = ' || to_char (p_column_ak_value, '99999999999999999') || ' and ' || p_column_ak2 || 
           ' = ' || to_char (idak2, '99999999999999999');
      
     -- senao, insiro
     ELSE
        ds_sql = 'INSERT INTO "' || p_table || '"( ' || p_column_ak1 || 
           ', ' || p_column_ak2 ||  ') VALUES ( ' || to_char (p_column_ak_value, '99999999999999999') || ', ' || 
           to_char (idak2, '99999999999999999') || ')';
      --raise NOTICE '%', ds_sql;
     END IF;

      --raise NOTICE '%', ds_sql;
     execute ds_sql;


  END LOOP;

   return  v_result;
END;
$$
LANGUAGE plpgsql;

