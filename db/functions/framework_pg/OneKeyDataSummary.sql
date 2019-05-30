
CREATE OR REPLACE FUNCTION public.OneKeyDataSummary()
  RETURNS void    AS
$$
DECLARE 
r record;
p record;
q record;
vtype   integer;
vcat   integer;
vsubcat   integer;
vunit     integer;
vgrade     integer;



BEGIN

    TRUNCATE TABLE "ONEKEY_DATA_SUMMARY_BY_MINUTE_SUPPLIER_PC";

    INSERT INTO "ONEKEY_DATA_SUMMARY_BY_MINUTE_SUPPLIER_PC"
    SELECT to_char(x.start_datetime, 'yyyy-mm-dd HH24:MI')::timestamp as dt_start, PCSTATION, LASER_SUPPLIER_NAME, min(SN) as SN, avg(x.TOTAL_COSTTIME) as TOTAL_COSTTIME_avg, prod_location
    from "ONEKEY_RAW_DATA" x
    WHERE x.isfinish = 'Y'
    group by LASER_SUPPLIER_NAME, PCSTATION , prod_location, to_char(x.start_datetime, 'yyyy-mm-dd HH24:MI')::timestamp 
    order by to_char(x.start_datetime, 'yyyy-mm-dd HH24:MI')::timestamp;



    TRUNCATE TABLE "ONEKEY_DATA_SUMMARY_BY_DAY";

    INSERT INTO "ONEKEY_DATA_SUMMARY_BY_DAY"
    select START_DATETIME::date as START_DATE,
        PCSTATION as PCSTATION,  
        MACHINE_NO as MACHINE_NO,
        PC_NO as PC_NO,
        LASER_ASSET_NO as LASER_ASSET_NO,
        LASER_SUPPLIER_NAME as LASER_SUPPLIER_NAME,
        min(LASER_DATE_INSTALLED) as LASER_DATE_INSTALLED,
        min(SN) as recid,


        -- avg
        round(avg(START_COSTTIME             ) , 2)    as START_COSTTIME_AVG,
        round(avg(RESET_ADAPTER_COSTTIME     ) , 2)   as RESET_ADAPTER_COSTTIME_AVG,
        round(avg(OPEN_ACCESSRIGHT_COSTTIME  ) , 2)   as OPEN_ACCESSRIGHT_COSTTIME_AVG,
        round(avg(READ_FWVERSION_COSTTIME    ) , 2)   as READ_FWVERSION_COSTTIME_AVG,
        round(avg(READ_CELLIDENT_COSTTIME    ) , 2)   as READ_CELLIDENT_COSTTIME_AVG,
        round(avg(READ_NUMBERSER_COSTTIME    ) , 2)   as READ_NUMBERSER_COSTTIME_AVG,
        round(avg(READ_PARALLELC_COSTTIME    ) , 2)   as READ_PARALLELC_COSTTIME_AVG,
        round(avg(READ_OPERATION_COSTTIME    ) , 2)   as READ_OPERATION_COSTTIME_AVG,
        round(avg(WRITE_BOD_COSTTIME         ) , 2)   as WRITE_BOD_COSTTIME_AVG,
        round(avg(WRITE_USERPWD_COSTTIME     ) , 2)   as WRITE_USERPWD_COSTTIME_AVG,
        round(avg(WRITE_ADMIPWD_COSTTIME     ) , 2)   as WRITE_ADMIPWD_COSTTIME_AVG,
        round(avg(WRITE_SERVPWD_COSTTIME     ) , 2)   as WRITE_SERVPWD_COSTTIME_AVG,
        round(avg(WRITE_ENCRYLO_COSTTIME     ) , 2)   as WRITE_ENCRYLO_COSTTIME_AVG,
        round(avg(WRITE_ENCRYHI_COSTTIME     ) , 2)   as WRITE_ENCRYHI_COSTTIME_AVG,
        round(avg(WRITE_MPBID_COSTTIME       ) , 2)   as WRITE_MPBID_COSTTIME_AVG,
        round(avg(WRITE_METCPWD_COSTTIME     ) , 2)   as WRITE_METCPWD_COSTTIME_AVG,
        round(avg(COMMAND_FINISH_COSTTIME    ) , 2)   as COMMAND_FINISH_COSTTIME_AVG,
        round(avg(ALL_COMMAND_COSTTIME       ) , 2)   as ALL_COMMAND_COSTTIME_AVG,
        round(avg(GEN_SN_COSTTIME            ) , 2)   as GEN_SN_COSTTIME_AVG,
        round(avg(SAVE_DATA_COSTTIME         ) , 2)   as SAVE_DATA_COSTTIME_AVG,
        round(avg(LASER_COSTTIME             ) , 2)   as LASER_COSTTIME_AVG,
        round(avg(TOTAL_COSTTIME             ) , 2)   as TOTAL_COSTTIME_AVG,
        prod_location

        from "ONEKEY_RAW_DATA"

    where ISFINISH = 'Y' AND PCSTATION IS NOT NULL AND LASER_SUPPLIER_NAME IS NOT NULL
    group by START_DATETIME::date, 
            PCSTATION,  
            MACHINE_NO,
            PC_NO,
            prod_location,
            LASER_ASSET_NO,
            LASER_SUPPLIER_NAME;

END
$$  LANGUAGE plpgsql;

ALTER FUNCTION public.OneKeyDataSummary() SET search_path=pg_catalog, public, rfq;