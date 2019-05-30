CREATE OR REPLACE FUNCTION public.retCurrencyRate
(
    PAR_cd_currency_rate bigint,
    PAR_nr_rate_default numeric(18,2)
)
    RETURNS numeric(18,2) as
$$
DECLARE
vret numeric(10,4);
BEGIN

    vret = 0;

    if PAR_nr_rate_default IS NULL THEN
        RETURN vret;
    END IF;

    SELECT nr_currency_rate INTO vret
      FROM "CURRENCY_RATE"
     WHERE cd_currency_rate = PAR_cd_currency_rate;

    --return vret;

    RETURN vret;

END;
$$
LANGUAGE plpgsql STABLE;


