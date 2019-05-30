DROP FUNCTION returnmenumb(integer) ;


CREATE OR REPLACE FUNCTION public.returnMenuMB(p_cd_human_resource integer)
  RETURNS TABLE (ds_menu varchar, ds_controller varchar, fl_has_sub char(2), ds_image text, cd_menu integer, fl_always_available char(1), fl_visible char(1), ds_kibana_dashboard text )    AS
$$
DECLARE 
    r record;
    p record;
    q record;
    row1   integer;
    row2   integer;
    row3   integer;
    v_fl_super_user char(1);
    v_cd_system_product_category integer;
BEGIN

    v_cd_system_product_category = get_var('cd_system_product_category');

   create temporary table menuUsers (cd_menu integer, 
                                     ds_menu varchar,  
                                     ds_controller varchar, 
                                     cd_menu_parent integer,
                                     nr_order integer,
                                     ds_image text,
                                     fl_always_available char(1),
                                     fl_visible char(1),
                                     ds_kibana_dashboard text
                                   );


   SELECT fl_super_user
     INTO v_fl_super_user
    FROM "HUMAN_RESOURCE" 
   WHERE cd_human_resource = p_cd_human_resource;


   INSERT INTO menuUsers
   SELECT  "MENU".cd_menu, retDescTranslatedNew("MENU".ds_menu, null), "MENU".ds_controller, "MENU".cd_menu_parent, "MENU".nr_order, coalesce ("MENU".ds_image, '') as ds_image, "MENU".fl_always_available, "MENU".fl_visible, "MENU".ds_kibana_dashboard
     FROM "HUMAN_RESOURCE_MENU",
          "MENU"
    WHERE  "HUMAN_RESOURCE_MENU".cd_human_resource = p_cd_human_resource
      AND "MENU".cd_menu  = "HUMAN_RESOURCE_MENU".cd_menu
      AND ( "MENU".fl_only_for_super_users = 'N' OR v_fl_super_user = 'Y' )

UNION

   SELECT  "MENU".cd_menu, retDescTranslatedNew("MENU".ds_menu, null), "MENU".ds_controller, "MENU".cd_menu_parent, "MENU".nr_order,  coalesce ("MENU".ds_image, '') as ds_image, "MENU".fl_always_available, "MENU".fl_visible, "MENU".ds_kibana_dashboard
     FROM "JOBS_HUMAN_RESOURCE" jh,
           "JOBS_MENU" jm,
           "JOBS" j,
           "MENU"
     WHERE jh.cd_human_resource = P_cd_human_resource
       AND jm.cd_jobs           = jh.cd_jobs
       AND j.cd_jobs            = jh.cd_jobs
       AND "MENU".cd_menu       = jm.cd_menu
       AND j.dt_deactivated IS NULL
       AND ( j.cd_system_product_category = v_cd_system_product_category OR v_fl_super_user = 'Y' )
      AND ( "MENU".fl_only_for_super_users = 'N' OR v_fl_super_user = 'Y' )

UNION 
SELECT  "MENU".cd_menu, retDescTranslatedNew("MENU".ds_menu, null), "MENU".ds_controller, "MENU".cd_menu_parent, "MENU".nr_order, coalesce ("MENU".ds_image, '') as ds_image, "MENU".fl_always_available, "MENU".fl_visible, "MENU".ds_kibana_dashboard
     FROM "MENU"
    WHERE "MENU".fl_always_available = 'Y';

   FOR r IN SELECT m.cd_menu, m.ds_menu, m.ds_controller, ( select count(1) from menuUsers x where x.cd_menu_parent = m.cd_menu ) as countparent, m.ds_image, m.fl_always_available, m.fl_visible, m.ds_kibana_dashboard
              FROM menuUsers m
             WHERE m.cd_menu_parent IS NULL
        ORDER BY nr_order, m.ds_menu
    LOOP


      cd_menu = r.cd_menu;
      ds_menu = r.ds_menu;
      ds_controller = r.ds_controller;
      ds_image      = r.ds_image;
      fl_always_available = r.fl_always_available;
      fl_visible = r.fl_visible;
      ds_kibana_dashboard = r.ds_kibana_dashboard;

 

      if r.countparent > 0 THEN
         fl_has_sub = 'B1';
      ELSE 
         fl_has_sub = 'L';
      end if;    
      
      RETURN NEXT;
     

      FOR p IN SELECT m.cd_menu, m.ds_menu, m.ds_controller, ( select count(1) from menuUsers x where x.cd_menu_parent = m.cd_menu ) as countparent, m.ds_image, m.fl_always_available, m.fl_visible, m.ds_kibana_dashboard
              FROM menuUsers m
             WHERE m.cd_menu_parent = r.cd_menu
              ORDER BY nr_order, m.ds_menu
      LOOP

         cd_menu = p.cd_menu;
         ds_menu = p.ds_menu;
         ds_controller = p.ds_controller;
         ds_image      = p.ds_image;
         fl_always_available = p.fl_always_available;
         fl_visible = p.fl_visible;
         ds_kibana_dashboard = p.ds_kibana_dashboard;


         if p.countparent > 0 THEN
            fl_has_sub = 'B2';
         ELSE 
            fl_has_sub = 'L';
         end if;    

         RETURN NEXT;



         FOR q IN SELECT m.cd_menu, m.ds_menu, m.ds_controller, ( select count(1) from menuUsers x where x.cd_menu_parent = m.cd_menu ) as countparent, m.ds_image, m.fl_always_available, m.fl_visible, m.ds_kibana_dashboard
                    FROM menuUsers m
                   WHERE m.cd_menu_parent = p.cd_menu
              ORDER BY nr_order, m.ds_menu
          LOOP

            cd_menu = q.cd_menu;
            ds_menu = q.ds_menu;
            ds_controller = q.ds_controller;
            fl_has_sub = 'L';
            ds_image      = q.ds_image;
            fl_always_available = q.fl_always_available;
            fl_visible = q.fl_visible;
            ds_kibana_dashboard = q.ds_kibana_dashboard;

            RETURN NEXT;


         END LOOP;

         if p.countparent > 0 THEN
            ds_menu = 'SUB END';
            ds_controller = null;
            fl_has_sub = 'E2';
            RETURN NEXT;

         end if;    


      END LOOP;

      if r.countparent > 0 THEN
         ds_menu = 'SUB END';
         ds_controller = null;
         fl_has_sub = 'E1';
         RETURN NEXT;

      end if;    

   END LOOP;

   drop table menuUsers;


END
$$  LANGUAGE plpgsql;
