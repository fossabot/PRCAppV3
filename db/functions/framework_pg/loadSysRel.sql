CREATE OR REPLACE FUNCTION public.loadSysRel()
    RETURNS text AS
    $BODY$
    declare r record;

BEGIN

   FOR r IN SELECT table_name, 
                   column_name, 
                   foreign_table_name, 
                   foreign_column_name, 
                   foreigner_desc_column 
            FROM foreigner_keys_view
    
   LOOP

   IF r.foreigner_desc_column IS NULL THEN
     CONTINUE;
   END IF;

   IF NOT EXISTS ( select 1 
                     from "SYSTEM_RELATIONS" 
                    where ds_table_name  = r.table_name 
                      and ds_column_name = r.column_name
                 ) THEN


      INSERT INTO "SYSTEM_RELATIONS"
      ( ds_table_name, ds_column_name, ds_foreign_table_name,ds_foreign_column_name, ds_foreign_desc_column_name )    
      VALUES 
      (r.table_name,   r.column_name,  r.foreign_table_name, r.foreign_column_name,  r.foreigner_desc_column);

   END IF;



   end loop;

   return 'OK';


END
$BODY$
LANGUAGE plpgsql
VOLATILE
COST 100;