CREATE OR REPLACE FUNCTION public.retRMBtoUSDRate
(
    PAR_dt_ref date,
    PAR_value  numeric(18,2)
)
    RETURNS numeric(18,2) as
$$
DECLARE
vret numeric(10,4);
BEGIN

    vret = 0;
    
    SELECT nr_currency_rate INTO vret
      FROM "CURRENCY_RATE"
     WHERE dt_currency_rate <= PAR_dt_ref
       AND cd_currency_from = 2
       AND cd_currency_to   = 3
       AND dt_deactivated IS NULL
    ORDER BY dt_currency_rate DESC
    LIMIT 1;

    IF COALESCE(vret, 0) = 0 THEN
        SELECT nr_currency_rate INTO vret
          FROM "CURRENCY_RATE"
         WHERE dt_currency_rate <= PAR_dt_ref
           AND cd_currency_from = 3
           AND cd_currency_to   = 2
           AND dt_deactivated IS NULL
        ORDER BY dt_currency_rate DESC
        LIMIT 1;

        IF vret = 0 THEN
            RETURN 0;
        END IF;

        vret = 1 / vret;
    END IF;

    --return vret;

    RETURN trunc(PAR_value / vret, 2);

END;
$$
LANGUAGE plpgsql;


