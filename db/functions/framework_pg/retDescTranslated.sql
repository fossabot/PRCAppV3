
CREATE OR REPLACE FUNCTION public.retDescTranslated
(
    p_description text,
    p_cd_system_languages integer
)
RETURNS text AS
$$
DECLARE
v_cd_system_language integer;
v_result text;
BEGIN

   return retDescTranslatedNew(p_description, p_cd_system_languages);
END;

$$
LANGUAGE plpgsql;

   /*
   IF p_description = '' THEN
      RETURN '';
   END IF;

    -- comeco inserindo em todos os idiomas que ainda nao tem a traducao para o texto recebido
      INSERT INTO "SYSTEM_DICTIONARY" (ds_text_default_language, ds_text_translated, cd_system_languages)
        SELECT p_description, p_description, l.cd_system_languages
          FROM "SYSTEM_LANGUAGES" l
         WHERE NOT EXISTS ( SELECT 1
                              FROM "SYSTEM_DICTIONARY" d
                             WHERE d.cd_system_languages      = l.cd_system_languages
                               AND d.ds_text_default_language = p_description
                          );


   IF p_cd_system_languages IS NULL THEN
      SELECT get_var('cd_system_languages')
        INTO v_cd_system_language;
   ELSE
      v_cd_system_language = p_cd_system_languages;
   END IF;

    IF v_cd_system_language IS NULL THEN
        v_result = p_description;
    ELSE
        SELECT d.ds_text_translated
          INTO v_result
          FROM translation."SYSTEM_DICTIONARY" d, 
               translation."SYSTEM_LANGUAGES"  l
         WHERE d.cd_system_languages      = v_cd_system_language
           AND d.ds_text_default_language = p_description
           AND l.cd_system_languages    = v_cd_system_language;

        IF v_result IS NULL THEN
            v_result = p_description;
        END IF;
     END IF;


    return v_result;
*/