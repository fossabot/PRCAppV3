CREATE OR REPLACE FUNCTION public.retCurrencyRateDesc
(
    p_cd_currency_from  integer,
    p_cd_currency_to    integer, 
    p_date              timestamp without time zone
)
    RETURNS text AS
$$
DECLARE
v_fk_text text;
v_currency_from text;
v_currency_to   text;
v_date          text;
v_array         text[];
BEGIN

   SELECT ds_currency_symbol
     INTO v_currency_from
     FROM "CURRENCY"
    WHERE cd_currency =  p_cd_currency_from;

   SELECT ds_currency_symbol
     INTO v_currency_to
     FROM "CURRENCY"
    WHERE cd_currency =  p_cd_currency_to;

    -- configuracao de data:
   select regexp_split_to_array(ds_option_id, ';')
     into v_array
     from "SYSTEM_SETTINGS" a, "SYSTEM_SETTINGS_OPTIONS" b
   WHERE a.ds_system_settings_id = 'fl_date_format'
     AND b.cd_system_settings    = a.cd_system_settings 
     AND b.fl_default = 'Y';

     v_date    = to_char(p_date, v_array[1]);

     v_fk_text = v_currency_from || ' - ' || v_currency_to || ' - ' || v_date;

   return  v_fk_text;
END;
$$
LANGUAGE plpgsql;


