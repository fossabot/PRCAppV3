CREATE OR REPLACE FUNCTION public.getMenuOptions(fl_job_or_hm character(1), cd_code integer)
    RETURNS TABLE (cd_menu_key integer,  ds_menu_key text, fl_checked character(1)) AS
    $BODY$
    declare r record;

BEGIN

    FOR r IN SELECT m.cd_menu, m.ds_menu, m.cd_menu_parent
               FROM "MENU" m
        --where m.cd_menu_parent IS NOT NULL AND NOT EXISTS ( SELECT 1 FROM "MENU" n WHERE n.cd_menu_parent = m.cd_menu)

    LOOP
        cd_menu_key :=  r.cd_menu;
        ds_menu_key :=  retDescTranslated(r.ds_menu, null);
        
        IF r.cd_menu_parent IS NOT NULL THEN
            ds_menu_key = concat (retParentMenu(r.cd_menu_parent), ' => ', ds_menu_key );
        END IF;
        


        IF fl_job_or_hm = 'J'THEN
            IF EXISTS (select 1 
                         from "JOBS_MENU"
                        where cd_menu = r.cd_menu
                          and cd_jobs = cd_code
                        ) THEN
                fl_checked :='Y';
            ELSE
                fl_checked :='N';
            END IF;

        ELSE --if do job ou hm

            IF EXISTS (select 1 
                         from "HUMAN_RESOURCE_MENU"
                        where cd_menu = r.cd_menu
                          and cd_human_resource = cd_code
                        ) THEN
                fl_checked :='Y';
            ELSE
                fl_checked :='N';
            END IF;
        END IF;


        return NEXT;
    end loop;

    return;

END
$BODY$
LANGUAGE plpgsql
VOLATILE
COST 100;


select * from plpgsql_check_function ('getMenuOptions(character(1),integer)');
