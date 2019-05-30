CREATE OR REPLACE FUNCTION menuTrgAfterRow()
  RETURNS trigger AS
$BODY$
DECLARE
BEGIN

    -- quando inserindo um novo menu, eh necessario inserir na tabela de idiomas.
    IF (TG_OP = 'INSERT') THEN
        INSERT INTO MENU_TRANSLATION (cd_menu, cd_system_languages, ds_menu_translation)
        SELECT NEW.cd_menu,  


    END IF;

END;
$BODY$
LANGUAGE plpgsql VOLATILE SECURITY DEFINER
COST 100;
ALTER FUNCTION menuTrgAfterRow()  OWNER TO postgres;

