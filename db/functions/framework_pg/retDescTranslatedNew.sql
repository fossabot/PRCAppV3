
CREATE OR REPLACE FUNCTION public.retDescTranslatedNew
(
    p_description text,
    p_cd_system_languages integer
)

RETURNS text AS
$$
DECLARE
v_cd_system_language integer;
v_result text;
v_cd_system_dictionary_main  bigint;
v_fl_default                 char(1);
BEGIN

   IF p_description = '' THEN
      RETURN '';
   END IF;

   SELECT cd_system_dictionary_main
     INTO v_cd_system_dictionary_main
     FROM "SYSTEM_DICTIONARY_MAIN"
    WHERE ds_system_dictionary_main = p_description;

   IF v_cd_system_dictionary_main IS NULL THEN
      INSERT INTO "SYSTEM_DICTIONARY_MAIN" (ds_system_dictionary_main) values ( p_description )  ;
      return p_description;
   END IF;

   IF p_cd_system_languages IS NULL THEN
      SELECT get_var('cd_system_languages')
        INTO v_cd_system_language;
   ELSE
      v_cd_system_language = p_cd_system_languages;
   END IF;

   --RAISE NOTICE '%', v_cd_system_language;

   IF v_cd_system_language IS NULL THEN
      return p_description;
   END IF;

/*
   SELECT fl_default
     INTO v_fl_default
     FROM "SYSTEM_LANGUAGES"
   WHERE cd_system_languages = v_cd_system_language;

   IF v_fl_default = 'Y' THEN
      return p_description;
   END IF;
*/
   -- Busco mensagem de usuarios!
   SELECT d.ds_system_dictionary_text
     INTO v_result
     FROM "SYSTEM_DICTIONARY_USERDEFINED" d
    WHERE d.cd_system_dictionary_main = v_cd_system_dictionary_main
      AND d.cd_system_languages       = v_cd_system_language;

   IF v_result IS NOT NULL THEN
       return v_result;
   END IF;


   -- busca traducao geral !
   SELECT d.ds_system_dictionary_translation
     INTO v_result
     FROM "SYSTEM_DICTIONARY_TRANSLATION" d
    WHERE d.cd_system_dictionary_main = v_cd_system_dictionary_main
      AND d.cd_system_languages       = v_cd_system_language;

   IF v_result IS NULL THEN
       v_result = p_description;
   END IF;


   return v_result;
END;
$$
LANGUAGE plpgsql;
