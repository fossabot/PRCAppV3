CREATE OR REPLACE FUNCTION public.retPerHMbyJobs(fl_permission_or_hm character(1), cd_jobs_par integer)
    RETURNS TABLE (cd_key integer,  ds_key text, ds_other_info text, dt_deactivated date, fl_checked character(1)) AS
    $BODY$
    declare r record;

BEGIN

    IF fl_permission_or_hm = 'H' THEN

        FOR r IN SELECT h.cd_human_resource, 
                        h.ds_human_resource,
                        h.ds_human_resource_full, 
                        h.dt_deactivated
                   FROM "HUMAN_RESOURCE" h
            --where m.cd_menu_parent IS NOT NULL AND NOT EXISTS ( SELECT 1 FROM "MENU" n WHERE n.cd_menu_parent = m.cd_menu)

        LOOP

            cd_key         := r.cd_human_resource;
            ds_key         := r.ds_human_resource;
            ds_other_info  := r.ds_human_resource_full;
            dt_deactivated := r.dt_deactivated;

            IF EXISTS (select 1 
                         from "JOBS_HUMAN_RESOURCE"
                        where cd_human_resource = cd_key
                          and cd_jobs           = cd_jobs_par
                        ) THEN
                fl_checked :='Y';
            ELSE
                fl_checked :='N';
            END IF;


            return NEXT;
        END LOOP;

    ELSE

        FOR r IN SELECT h.cd_system_permission, 
                        h.ds_system_permission,
                        ( select ds_type_sys_permission from "TYPE_SYS_PERMISSION" where cd_type_sys_permission = h.cd_type_sys_permission ) as ds_type_sys_permission
                   FROM "SYSTEM_PERMISSION" h
            --where m.cd_menu_parent IS NOT NULL AND NOT EXISTS ( SELECT 1 FROM "MENU" n WHERE n.cd_menu_parent = m.cd_menu)

        LOOP

            cd_key         := r.cd_system_permission;
            ds_key         := r.ds_system_permission;
            ds_other_info  := r.ds_type_sys_permission;
            --dt_deactivated := r.dt_deactivated;

            IF EXISTS (select 1 
                         from "JOBS_SYSTEM_PERMISSION"
                        where cd_system_permission = cd_key
                          and cd_jobs              = cd_jobs_par
                        ) THEN
                fl_checked :='Y';
            ELSE
                fl_checked :='N';
            END IF;


            return NEXT;
        END LOOP;


    END IF;

    return;

END
$BODY$
LANGUAGE plpgsql
VOLATILE
COST 100;