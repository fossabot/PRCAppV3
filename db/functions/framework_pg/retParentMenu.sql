CREATE OR REPLACE FUNCTION public.retParentMenu(cd_menu_par integer)
    RETURNS text AS
    $BODY$
    declare r text;
            i integer;
            x text;

BEGIN

    

    SELECT retDescTranslated(m.ds_menu, null), cd_menu_parent
      INTO r,i
      FROM "MENU" m
     WHERE cd_menu = cd_menu_par;

     IF i IS NOT NULL THEN
        x := retParentMenu(i);
 
        r := concat (x, ' => ', r);

     END IF;


     

    return r;

END
$BODY$
LANGUAGE plpgsql
VOLATILE
COST 100;