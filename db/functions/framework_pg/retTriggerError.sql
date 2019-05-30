
CREATE OR REPLACE FUNCTION public.getTriggerError
(
    v_cd_sys_db_messages  integer,
    p_cd_system_languages integer
)
RETURNS text AS
$$
DECLARE
v_cd_system_language integer;
v_cd_system_language_default integer;
v_result text;
v_msg_default text;
BEGIN


   IF p_cd_system_languages IS NULL THEN
      SELECT get_var('cd_system_languages')
        INTO v_cd_system_language;
   ELSE
      v_cd_system_language = p_cd_system_languages;
   END IF;


   SELECT getSysParameter('SYSTEM_LANGUAGE_ENGLISH_CODE')::integer
     INTO v_cd_system_language_default;


   IF v_cd_sys_db_messages > 500000 THEN

      SELECT l.ds_sys_db_messages
        INTO v_msg_default
        FROM translation."SYS_DB_MESSAGES_DEFAULT" l
       WHERE l.cd_sys_db_messages  = v_cd_sys_db_messages;


   ELSE

      SELECT l.ds_sys_db_messages_local
        INTO v_msg_default
        FROM translation."SYS_DB_MESSAGES_LOCAL" l
       WHERE l.cd_sys_db_messages_local  = v_cd_sys_db_messages;

   END IF;



    IF v_cd_sys_db_messages > 500000 THEN

       SELECT l.ds_sys_db_messages_languages
         INTO v_result
         FROM translation."SYS_DB_MESSAGES_LANGUAGES_DEFAULT" l
        WHERE l.cd_sys_db_messages  = v_cd_sys_db_messages
          AND l.cd_system_languages = v_cd_system_language;


    ELSE
       SELECT l.ds_sys_db_messages_languages_local
         INTO v_result
         FROM translation."SYS_DB_MESSAGES_LANGUAGES_LOCAL" l
        WHERE l.cd_sys_db_messages_local  = v_cd_sys_db_messages
          AND l.cd_system_languages = v_cd_system_language;
    END IF;



   IF v_result IS NULL THEN
      v_result = v_msg_default;
   END IF;

   return v_result;
END;
$$
LANGUAGE plpgsql;
