
CREATE OR REPLACE FUNCTION onDLLAfter()
 RETURNS event_trigger
 LANGUAGE plpgsql AS
$$
---
--- Implement local Table Rewriting policy:
---   public.foo is not allowed rewriting, ever
---   other tables are only allowed rewriting between 1am and 6am
---   unless they have more than 100 blocks
---
DECLARE

  current_hour integer := extract('hour' from current_time);
  pages integer;
  max_pages integer := 100;
  obj  record;
BEGIN

   for obj IN SELECT * FROM pg_event_trigger_dropped_objects()
   loop
      
      RAISE NOTICE '% (%) %', tg_event, tg_tag;

   end loop;
   

   RAISE NOTICE '% (%)', tg_event, tg_tag;
END;
$$;

CREATE EVENT TRIGGER myDLLControl
                  ON ddl_command_end
   EXECUTE PROCEDURE onDLLAfter();