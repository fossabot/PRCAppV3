<?php

include_once APPPATH . "models/modelBasicExtend.php";

class onekey_model extends modelBasicExtend {

    function __construct() {



        parent::__construct();
    }

    public function getRawData($where) {
        
    }

    public function getDataStatisticsByHour($startdate, $totalhours) {

        $sql = "select 
    PCSTATION,  
    MACHINE_NO,
    PC_NO,
    LASER_ASSET_NO,
    LASER_SUPPLIER_NAME,
    
    to_char(START_DATETIME, 'MM/DD/YYYY HH24'),
    
    min(LASER_DATE_INSTALLED) as LASER_DATE_INSTALLED,
    
    -- avg
    round(avg(START_COSTTIME             ) , 2)    as START_COSTTIME_AVG\",
    round(avg(RESET_ADAPTER_COSTTIME     ) , 2)   as RESET_ADAPTER_COSTTIME_AVG\",
    round(avg(OPEN_ACCESSRIGHT_COSTTIME  ) , 2)   as OPEN_ACCESSRIGHT_COSTTIME_AVG\",
    round(avg(READ_FWVERSION_COSTTIME    ) , 2)   as READ_FWVERSION_COSTTIME_AVG\",
    round(avg(READ_CELLIDENT_COSTTIME    ) , 2)   as READ_CELLIDENT_COSTTIME_AVG\",
    round(avg(READ_NUMBERSER_COSTTIME    ) , 2)   as READ_NUMBERSER_COSTTIME_AVG\",
    round(avg(READ_PARALLELC_COSTTIME    ) , 2)   as READ_PARALLELC_COSTTIME_AVG\",
    round(avg(READ_OPERATION_COSTTIME    ) , 2)   as READ_OPERATION_COSTTIME_AVG\",
    round(avg(WRITE_BOD_COSTTIME         ) , 2)   as WRITE_BOD_COSTTIME_AVG\",
    round(avg(WRITE_USERPWD_COSTTIME     ) , 2)   as WRITE_USERPWD_COSTTIME_AVG\",
    round(avg(WRITE_ADMIPWD_COSTTIME     ) , 2)   as WRITE_ADMIPWD_COSTTIME_AVG\",
    round(avg(WRITE_SERVPWD_COSTTIME     ) , 2)   as WRITE_SERVPWD_COSTTIME_AVG\",
    round(avg(WRITE_ENCRYLO_COSTTIME     ) , 2)   as WRITE_ENCRYLO_COSTTIME_AVG\",
    round(avg(WRITE_ENCRYHI_COSTTIME     ) , 2)   as WRITE_ENCRYHI_COSTTIME_AVG\",
    round(avg(WRITE_MPBID_COSTTIME       ) , 2)   as WRITE_MPBID_COSTTIME_AVG\",
    round(avg(WRITE_METCPWD_COSTTIME     ) , 2)   as WRITE_METCPWD_COSTTIME_AVG\",
    round(avg(COMMAND_FINISH_COSTTIME    ) , 2)   as COMMAND_FINISH_COSTTIME_AVG\",
    round(avg(ALL_COMMAND_COSTTIME       ) , 2)   as ALL_COMMAND_COSTTIME_AVG\",
    round(avg(GEN_SN_COSTTIME            ) , 2)   as GEN_SN_COSTTIME_AVG\",
    round(avg(SAVE_DATA_COSTTIME         ) , 2)   as SAVE_DATA_COSTTIME_AVG\",
    round(avg(LASER_COSTTIME             ) , 2)   as LASER_COSTTIME_AVG\",
    round(avg(TOTAL_COSTTIME             ) , 2)   as TOTAL_COSTTIME_AVG\",
    -- max
    MAX(START_COSTTIME             )     as START_COSTTIME_MAX\",
    MAX(RESET_ADAPTER_COSTTIME     )     as RESET_ADAPTER_COSTTIME_MAX\",
    MAX(OPEN_ACCESSRIGHT_COSTTIME  )     as OPEN_ACCESSRIGHT_COSTTIME_MAX\",
    MAX(READ_FWVERSION_COSTTIME    )     as READ_FWVERSION_COSTTIME_MAX\",
    MAX(READ_CELLIDENT_COSTTIME    )     as READ_CELLIDENT_COSTTIME_MAX\",
    MAX(READ_NUMBERSER_COSTTIME    )     as READ_NUMBERSER_COSTTIME_MAX\",
    MAX(READ_PARALLELC_COSTTIME    )     as READ_PARALLELC_COSTTIME_MAX\",
    MAX(READ_OPERATION_COSTTIME    )     as READ_OPERATION_COSTTIME_MAX\",
    MAX(WRITE_BOD_COSTTIME         )     as WRITE_BOD_COSTTIME_MAX\",
    MAX(WRITE_USERPWD_COSTTIME     )     as WRITE_USERPWD_COSTTIME_MAX\",
    MAX(WRITE_ADMIPWD_COSTTIME     )     as WRITE_ADMIPWD_COSTTIME_MAX\",
    MAX(WRITE_SERVPWD_COSTTIME     )     as WRITE_SERVPWD_COSTTIME_MAX\",
    MAX(WRITE_ENCRYLO_COSTTIME     )     as WRITE_ENCRYLO_COSTTIME_MAX\",
    MAX(WRITE_ENCRYHI_COSTTIME     )     as WRITE_ENCRYHI_COSTTIME_MAX\",
    MAX(WRITE_MPBID_COSTTIME       )     as WRITE_MPBID_COSTTIME_MAX\",
    MAX(WRITE_METCPWD_COSTTIME     )     as WRITE_METCPWD_COSTTIME_MAX\",
    MAX(COMMAND_FINISH_COSTTIME    )     as COMMAND_FINISH_COSTTIME_MAX\",
    MAX(ALL_COMMAND_COSTTIME       )     as ALL_COMMAND_COSTTIME_MAX\",
    MAX(GEN_SN_COSTTIME            )     as GEN_SN_COSTTIME_MAX\",
    MAX(SAVE_DATA_COSTTIME         )     as SAVE_DATA_COSTTIME_MAX\",
    MAX(LASER_COSTTIME             )     as LASER_COSTTIME_MAX\",
    MAX(TOTAL_COSTTIME             )     as TOTAL_COSTTIME_MAX\",
    
    -- min
    MIN(START_COSTTIME             )     as START_COSTTIME_MIN\",
    MIN(RESET_ADAPTER_COSTTIME     )     as RESET_ADAPTER_COSTTIME_MIN\",
    MIN(OPEN_ACCESSRIGHT_COSTTIME  )     as OPEN_ACCESSRIGHT_COSTTIME_MIN\",
    MIN(READ_FWVERSION_COSTTIME    )     as READ_FWVERSION_COSTTIME_MIN\",
    MIN(READ_CELLIDENT_COSTTIME    )     as READ_CELLIDENT_COSTTIME_MIN\",
    MIN(READ_NUMBERSER_COSTTIME    )     as READ_NUMBERSER_COSTTIME_MIN\",
    MIN(READ_PARALLELC_COSTTIME    )     as READ_PARALLELC_COSTTIME_MIN\",
    MIN(READ_OPERATION_COSTTIME    )     as READ_OPERATION_COSTTIME_MIN\",
    MIN(WRITE_BOD_COSTTIME         )     as WRITE_BOD_COSTTIME_MIN\",
    MIN(WRITE_USERPWD_COSTTIME     )     as WRITE_USERPWD_COSTTIME_MIN\",
    MIN(WRITE_ADMIPWD_COSTTIME     )     as WRITE_ADMIPWD_COSTTIME_MIN\",
    MIN(WRITE_SERVPWD_COSTTIME     )     as WRITE_SERVPWD_COSTTIME_MIN\",
    MIN(WRITE_ENCRYLO_COSTTIME     )     as WRITE_ENCRYLO_COSTTIME_MIN\",
    MIN(WRITE_ENCRYHI_COSTTIME     )     as WRITE_ENCRYHI_COSTTIME_MIN\",
    MIN(WRITE_MPBID_COSTTIME       )     as WRITE_MPBID_COSTTIME_MIN\",
    MIN(WRITE_METCPWD_COSTTIME     )     as WRITE_METCPWD_COSTTIME_MIN\",
    MIN(COMMAND_FINISH_COSTTIME    )     as COMMAND_FINISH_COSTTIME_MIN\",
    MIN(ALL_COMMAND_COSTTIME       )     as ALL_COMMAND_COSTTIME_MIN\",
    MIN(GEN_SN_COSTTIME            )     as GEN_SN_COSTTIME_MIN\",
    MIN(SAVE_DATA_COSTTIME         )     as SAVE_DATA_COSTTIME_MIN\",
    MIN(LASER_COSTTIME             )     as LASER_COSTTIME_MIN\",
    MIN(TOTAL_COSTTIME             )     as TOTAL_COSTTIME_MIN\",
    
    round(STDDEV(START_COSTTIME             ), 2)     as START_COSTTIME_STDDEV,
    round(STDDEV(RESET_ADAPTER_COSTTIME     ), 2)     as RESET_ADAPTER_COSTTIME_STD,
    round(STDDEV(OPEN_ACCESSRIGHT_COSTTIME  ), 2)     as OPEN_ACCESSRIGHT_COSTTIME_STD,
    round(STDDEV(READ_FWVERSION_COSTTIME    ), 2)     as READ_FWVERSION_COSTTIME_STD,
    round(STDDEV(READ_CELLIDENT_COSTTIME    ), 2)     as READ_CELLIDENT_COSTTIME_STD,
    round(STDDEV(READ_NUMBERSER_COSTTIME    ), 2)     as READ_NUMBERSER_COSTTIME_STD,
    round(STDDEV(READ_PARALLELC_COSTTIME    ), 2)     as READ_PARALLELC_COSTTIME_STD,
    round(STDDEV(READ_OPERATION_COSTTIME    ), 2)     as READ_OPERATION_COSTTIME_STD,
    round(STDDEV(WRITE_BOD_COSTTIME         ), 2)     as WRITE_BOD_COSTTIME_STD,
    round(STDDEV(WRITE_USERPWD_COSTTIME     ), 2)     as WRITE_USERPWD_COSTTIME_STD,
    round(STDDEV(WRITE_ADMIPWD_COSTTIME     ), 2)     as WRITE_ADMIPWD_COSTTIME_STD,
    round(STDDEV(WRITE_SERVPWD_COSTTIME     ), 2)     as WRITE_SERVPWD_COSTTIME_STD,
    round(STDDEV(WRITE_ENCRYLO_COSTTIME     ), 2)     as WRITE_ENCRYLO_COSTTIME_STD,
    round(STDDEV(WRITE_ENCRYHI_COSTTIME     ), 2)     as WRITE_ENCRYHI_COSTTIME_STD,
    round(STDDEV(WRITE_MPBID_COSTTIME       ), 2)     as WRITE_MPBID_COSTTIME_STD,
    round(STDDEV(WRITE_METCPWD_COSTTIME     ), 2)     as WRITE_METCPWD_COSTTIME_STD,
    round(STDDEV(COMMAND_FINISH_COSTTIME    ), 2)     as COMMAND_FINISH_COSTTIME_STD,
    round(STDDEV(ALL_COMMAND_COSTTIME       ), 2)     as ALL_COMMAND_COSTTIME_STD,
    round(STDDEV(GEN_SN_COSTTIME            ), 2)     as GEN_SN_COSTTIME_STD,
    round(STDDEV(SAVE_DATA_COSTTIME         ), 2)     as SAVE_DATA_COSTTIME_STD,
    round(STDDEV(LASER_COSTTIME             ), 2)     as LASER_COSTTIME_STD,
    round(STDDEV(TOTAL_COSTTIME             ), 2)     as TOTAL_COSTTIME_STDDEV90
        
    from CUX_BATTERY_DATA_KPI_LOG,
    (select timestamp '$startdate 00:00:00' + numtodsinterval(rownum*1,'HOUR') as dt_timestamp from dual connect by level <= $totalhours) gens
where to_char(START_DATETIME, 'MM/DD/YYYY HH24') =  to_char(gens.dt_start, 'MM/DD/YYYY HH24')
group by PCSTATION,  
        MACHINE_NO,
        PC_NO,
        LASER_ASSET_NO,
        LASER_SUPPLIER_NAME,
        to_char(START_DATETIME, 'MM/DD/YYYY HH24')";





        $ret = $this->onekeydb->query($sql)->result_array();

        return $ret;
    }

    public function getDataStatisticsByMinutes($startdate, $totalminutes) {

        $sql = "select 
    PCSTATION,  
    MACHINE_NO,
    PC_NO,
    LASER_ASSET_NO,
    LASER_SUPPLIER_NAME,
    
    to_char(START_DATETIME, 'MM/DD/YYYY HH24:MI'),
    
    min(LASER_DATE_INSTALLED) as LASER_DATE_INSTALLED,
    
    -- avg
    round(avg(START_COSTTIME             ) , 2)    as START_COSTTIME_AVG\",
    round(avg(RESET_ADAPTER_COSTTIME     ) , 2)   as RESET_ADAPTER_COSTTIME_AVG\",
    round(avg(OPEN_ACCESSRIGHT_COSTTIME  ) , 2)   as OPEN_ACCESSRIGHT_COSTTIME_AVG\",
    round(avg(READ_FWVERSION_COSTTIME    ) , 2)   as READ_FWVERSION_COSTTIME_AVG\",
    round(avg(READ_CELLIDENT_COSTTIME    ) , 2)   as READ_CELLIDENT_COSTTIME_AVG\",
    round(avg(READ_NUMBERSER_COSTTIME    ) , 2)   as READ_NUMBERSER_COSTTIME_AVG\",
    round(avg(READ_PARALLELC_COSTTIME    ) , 2)   as READ_PARALLELC_COSTTIME_AVG\",
    round(avg(READ_OPERATION_COSTTIME    ) , 2)   as READ_OPERATION_COSTTIME_AVG\",
    round(avg(WRITE_BOD_COSTTIME         ) , 2)   as WRITE_BOD_COSTTIME_AVG\",
    round(avg(WRITE_USERPWD_COSTTIME     ) , 2)   as WRITE_USERPWD_COSTTIME_AVG\",
    round(avg(WRITE_ADMIPWD_COSTTIME     ) , 2)   as WRITE_ADMIPWD_COSTTIME_AVG\",
    round(avg(WRITE_SERVPWD_COSTTIME     ) , 2)   as WRITE_SERVPWD_COSTTIME_AVG\",
    round(avg(WRITE_ENCRYLO_COSTTIME     ) , 2)   as WRITE_ENCRYLO_COSTTIME_AVG\",
    round(avg(WRITE_ENCRYHI_COSTTIME     ) , 2)   as WRITE_ENCRYHI_COSTTIME_AVG\",
    round(avg(WRITE_MPBID_COSTTIME       ) , 2)   as WRITE_MPBID_COSTTIME_AVG\",
    round(avg(WRITE_METCPWD_COSTTIME     ) , 2)   as WRITE_METCPWD_COSTTIME_AVG\",
    round(avg(COMMAND_FINISH_COSTTIME    ) , 2)   as COMMAND_FINISH_COSTTIME_AVG\",
    round(avg(ALL_COMMAND_COSTTIME       ) , 2)   as ALL_COMMAND_COSTTIME_AVG\",
    round(avg(GEN_SN_COSTTIME            ) , 2)   as GEN_SN_COSTTIME_AVG\",
    round(avg(SAVE_DATA_COSTTIME         ) , 2)   as SAVE_DATA_COSTTIME_AVG\",
    round(avg(LASER_COSTTIME             ) , 2)   as LASER_COSTTIME_AVG\",
    round(avg(TOTAL_COSTTIME             ) , 2)   as TOTAL_COSTTIME_AVG\",
    -- max
    MAX(START_COSTTIME             )     as START_COSTTIME_MAX\",
    MAX(RESET_ADAPTER_COSTTIME     )     as RESET_ADAPTER_COSTTIME_MAX\",
    MAX(OPEN_ACCESSRIGHT_COSTTIME  )     as OPEN_ACCESSRIGHT_COSTTIME_MAX\",
    MAX(READ_FWVERSION_COSTTIME    )     as READ_FWVERSION_COSTTIME_MAX\",
    MAX(READ_CELLIDENT_COSTTIME    )     as READ_CELLIDENT_COSTTIME_MAX\",
    MAX(READ_NUMBERSER_COSTTIME    )     as READ_NUMBERSER_COSTTIME_MAX\",
    MAX(READ_PARALLELC_COSTTIME    )     as READ_PARALLELC_COSTTIME_MAX\",
    MAX(READ_OPERATION_COSTTIME    )     as READ_OPERATION_COSTTIME_MAX\",
    MAX(WRITE_BOD_COSTTIME         )     as WRITE_BOD_COSTTIME_MAX\",
    MAX(WRITE_USERPWD_COSTTIME     )     as WRITE_USERPWD_COSTTIME_MAX\",
    MAX(WRITE_ADMIPWD_COSTTIME     )     as WRITE_ADMIPWD_COSTTIME_MAX\",
    MAX(WRITE_SERVPWD_COSTTIME     )     as WRITE_SERVPWD_COSTTIME_MAX\",
    MAX(WRITE_ENCRYLO_COSTTIME     )     as WRITE_ENCRYLO_COSTTIME_MAX\",
    MAX(WRITE_ENCRYHI_COSTTIME     )     as WRITE_ENCRYHI_COSTTIME_MAX\",
    MAX(WRITE_MPBID_COSTTIME       )     as WRITE_MPBID_COSTTIME_MAX\",
    MAX(WRITE_METCPWD_COSTTIME     )     as WRITE_METCPWD_COSTTIME_MAX\",
    MAX(COMMAND_FINISH_COSTTIME    )     as COMMAND_FINISH_COSTTIME_MAX\",
    MAX(ALL_COMMAND_COSTTIME       )     as ALL_COMMAND_COSTTIME_MAX\",
    MAX(GEN_SN_COSTTIME            )     as GEN_SN_COSTTIME_MAX\",
    MAX(SAVE_DATA_COSTTIME         )     as SAVE_DATA_COSTTIME_MAX\",
    MAX(LASER_COSTTIME             )     as LASER_COSTTIME_MAX\",
    MAX(TOTAL_COSTTIME             )     as TOTAL_COSTTIME_MAX\",
    
    -- min
    MIN(START_COSTTIME             )     as START_COSTTIME_MIN\",
    MIN(RESET_ADAPTER_COSTTIME     )     as RESET_ADAPTER_COSTTIME_MIN\",
    MIN(OPEN_ACCESSRIGHT_COSTTIME  )     as OPEN_ACCESSRIGHT_COSTTIME_MIN\",
    MIN(READ_FWVERSION_COSTTIME    )     as READ_FWVERSION_COSTTIME_MIN\",
    MIN(READ_CELLIDENT_COSTTIME    )     as READ_CELLIDENT_COSTTIME_MIN\",
    MIN(READ_NUMBERSER_COSTTIME    )     as READ_NUMBERSER_COSTTIME_MIN\",
    MIN(READ_PARALLELC_COSTTIME    )     as READ_PARALLELC_COSTTIME_MIN\",
    MIN(READ_OPERATION_COSTTIME    )     as READ_OPERATION_COSTTIME_MIN\",
    MIN(WRITE_BOD_COSTTIME         )     as WRITE_BOD_COSTTIME_MIN\",
    MIN(WRITE_USERPWD_COSTTIME     )     as WRITE_USERPWD_COSTTIME_MIN\",
    MIN(WRITE_ADMIPWD_COSTTIME     )     as WRITE_ADMIPWD_COSTTIME_MIN\",
    MIN(WRITE_SERVPWD_COSTTIME     )     as WRITE_SERVPWD_COSTTIME_MIN\",
    MIN(WRITE_ENCRYLO_COSTTIME     )     as WRITE_ENCRYLO_COSTTIME_MIN\",
    MIN(WRITE_ENCRYHI_COSTTIME     )     as WRITE_ENCRYHI_COSTTIME_MIN\",
    MIN(WRITE_MPBID_COSTTIME       )     as WRITE_MPBID_COSTTIME_MIN\",
    MIN(WRITE_METCPWD_COSTTIME     )     as WRITE_METCPWD_COSTTIME_MIN\",
    MIN(COMMAND_FINISH_COSTTIME    )     as COMMAND_FINISH_COSTTIME_MIN\",
    MIN(ALL_COMMAND_COSTTIME       )     as ALL_COMMAND_COSTTIME_MIN\",
    MIN(GEN_SN_COSTTIME            )     as GEN_SN_COSTTIME_MIN\",
    MIN(SAVE_DATA_COSTTIME         )     as SAVE_DATA_COSTTIME_MIN\",
    MIN(LASER_COSTTIME             )     as LASER_COSTTIME_MIN\",
    MIN(TOTAL_COSTTIME             )     as TOTAL_COSTTIME_MIN\",
    
    round(STDDEV(START_COSTTIME             ), 2)     as START_COSTTIME_STDDEV,
    round(STDDEV(RESET_ADAPTER_COSTTIME     ), 2)     as RESET_ADAPTER_COSTTIME_STD,
    round(STDDEV(OPEN_ACCESSRIGHT_COSTTIME  ), 2)     as OPEN_ACCESSRIGHT_COSTTIME_STD,
    round(STDDEV(READ_FWVERSION_COSTTIME    ), 2)     as READ_FWVERSION_COSTTIME_STD,
    round(STDDEV(READ_CELLIDENT_COSTTIME    ), 2)     as READ_CELLIDENT_COSTTIME_STD,
    round(STDDEV(READ_NUMBERSER_COSTTIME    ), 2)     as READ_NUMBERSER_COSTTIME_STD,
    round(STDDEV(READ_PARALLELC_COSTTIME    ), 2)     as READ_PARALLELC_COSTTIME_STD,
    round(STDDEV(READ_OPERATION_COSTTIME    ), 2)     as READ_OPERATION_COSTTIME_STD,
    round(STDDEV(WRITE_BOD_COSTTIME         ), 2)     as WRITE_BOD_COSTTIME_STD,
    round(STDDEV(WRITE_USERPWD_COSTTIME     ), 2)     as WRITE_USERPWD_COSTTIME_STD,
    round(STDDEV(WRITE_ADMIPWD_COSTTIME     ), 2)     as WRITE_ADMIPWD_COSTTIME_STD,
    round(STDDEV(WRITE_SERVPWD_COSTTIME     ), 2)     as WRITE_SERVPWD_COSTTIME_STD,
    round(STDDEV(WRITE_ENCRYLO_COSTTIME     ), 2)     as WRITE_ENCRYLO_COSTTIME_STD,
    round(STDDEV(WRITE_ENCRYHI_COSTTIME     ), 2)     as WRITE_ENCRYHI_COSTTIME_STD,
    round(STDDEV(WRITE_MPBID_COSTTIME       ), 2)     as WRITE_MPBID_COSTTIME_STD,
    round(STDDEV(WRITE_METCPWD_COSTTIME     ), 2)     as WRITE_METCPWD_COSTTIME_STD,
    round(STDDEV(COMMAND_FINISH_COSTTIME    ), 2)     as COMMAND_FINISH_COSTTIME_STD,
    round(STDDEV(ALL_COMMAND_COSTTIME       ), 2)     as ALL_COMMAND_COSTTIME_STD,
    round(STDDEV(GEN_SN_COSTTIME            ), 2)     as GEN_SN_COSTTIME_STD,
    round(STDDEV(SAVE_DATA_COSTTIME         ), 2)     as SAVE_DATA_COSTTIME_STD,
    round(STDDEV(LASER_COSTTIME             ), 2)     as LASER_COSTTIME_STD,
    round(STDDEV(TOTAL_COSTTIME             ), 2)     as TOTAL_COSTTIME_STDDEV90
        
    from CUX_BATTERY_DATA_KPI_LOG,
    (select timestamp '$startdate 00:00:00' + numtodsinterval(rownum*1,'MINUTE') as dt_timestamp from dual connect by level <= $totalminutes) gens
where to_char(START_DATETIME, 'MM/DD/YYYY HH24:MI') =  to_char(gens.dt_start, 'MM/DD/YYYY HH24:MI') AND ISFINISH = 'Y'
group by PCSTATION,  
        MACHINE_NO,
        PC_NO,
        LASER_ASSET_NO,
        LASER_SUPPLIER_NAME,
        to_char(START_DATETIME, 'MM/DD/YYYY HH24:MI')
ORDER BY to_timestamp(to_char(START_DATETIME, 'MM/DD/YYYY HH24:MI'), 'MM/DD/YYYY HH24:MI')";





        $ret = $this->onekeydb->query($sql)->result_array();

        return $ret;
    }

    public function getDataStatisticsByDay($startdate, $totaldays) {

        $sql = "
select 
    PCSTATION,  
    MACHINE_NO,
    PC_NO,
    LASER_ASSET_NO,
    LASER_SUPPLIER_NAME,
    
    to_char(START_DATETIME, 'MM/DD/YYYY'),
    
    min(LASER_DATE_INSTALLED) as LASER_DATE_INSTALLED,
    
    -- avg
    round(avg(START_COSTTIME             ) , 2)    as START_COSTTIME_AVG\",
    round(avg(RESET_ADAPTER_COSTTIME     ) , 2)   as RESET_ADAPTER_COSTTIME_AVG\",
    round(avg(OPEN_ACCESSRIGHT_COSTTIME  ) , 2)   as OPEN_ACCESSRIGHT_COSTTIME_AVG\",
    round(avg(READ_FWVERSION_COSTTIME    ) , 2)   as READ_FWVERSION_COSTTIME_AVG\",
    round(avg(READ_CELLIDENT_COSTTIME    ) , 2)   as READ_CELLIDENT_COSTTIME_AVG\",
    round(avg(READ_NUMBERSER_COSTTIME    ) , 2)   as READ_NUMBERSER_COSTTIME_AVG\",
    round(avg(READ_PARALLELC_COSTTIME    ) , 2)   as READ_PARALLELC_COSTTIME_AVG\",
    round(avg(READ_OPERATION_COSTTIME    ) , 2)   as READ_OPERATION_COSTTIME_AVG\",
    round(avg(WRITE_BOD_COSTTIME         ) , 2)   as WRITE_BOD_COSTTIME_AVG\",
    round(avg(WRITE_USERPWD_COSTTIME     ) , 2)   as WRITE_USERPWD_COSTTIME_AVG\",
    round(avg(WRITE_ADMIPWD_COSTTIME     ) , 2)   as WRITE_ADMIPWD_COSTTIME_AVG\",
    round(avg(WRITE_SERVPWD_COSTTIME     ) , 2)   as WRITE_SERVPWD_COSTTIME_AVG\",
    round(avg(WRITE_ENCRYLO_COSTTIME     ) , 2)   as WRITE_ENCRYLO_COSTTIME_AVG\",
    round(avg(WRITE_ENCRYHI_COSTTIME     ) , 2)   as WRITE_ENCRYHI_COSTTIME_AVG\",
    round(avg(WRITE_MPBID_COSTTIME       ) , 2)   as WRITE_MPBID_COSTTIME_AVG\",
    round(avg(WRITE_METCPWD_COSTTIME     ) , 2)   as WRITE_METCPWD_COSTTIME_AVG\",
    round(avg(COMMAND_FINISH_COSTTIME    ) , 2)   as COMMAND_FINISH_COSTTIME_AVG\",
    round(avg(ALL_COMMAND_COSTTIME       ) , 2)   as ALL_COMMAND_COSTTIME_AVG\",
    round(avg(GEN_SN_COSTTIME            ) , 2)   as GEN_SN_COSTTIME_AVG\",
    round(avg(SAVE_DATA_COSTTIME         ) , 2)   as SAVE_DATA_COSTTIME_AVG\",
    round(avg(LASER_COSTTIME             ) , 2)   as LASER_COSTTIME_AVG\",
    round(avg(TOTAL_COSTTIME             ) , 2)   as TOTAL_COSTTIME_AVG\",
    -- max
    MAX(START_COSTTIME             )     as START_COSTTIME_MAX\",
    MAX(RESET_ADAPTER_COSTTIME     )     as RESET_ADAPTER_COSTTIME_MAX\",
    MAX(OPEN_ACCESSRIGHT_COSTTIME  )     as OPEN_ACCESSRIGHT_COSTTIME_MAX\",
    MAX(READ_FWVERSION_COSTTIME    )     as READ_FWVERSION_COSTTIME_MAX\",
    MAX(READ_CELLIDENT_COSTTIME    )     as READ_CELLIDENT_COSTTIME_MAX\",
    MAX(READ_NUMBERSER_COSTTIME    )     as READ_NUMBERSER_COSTTIME_MAX\",
    MAX(READ_PARALLELC_COSTTIME    )     as READ_PARALLELC_COSTTIME_MAX\",
    MAX(READ_OPERATION_COSTTIME    )     as READ_OPERATION_COSTTIME_MAX\",
    MAX(WRITE_BOD_COSTTIME         )     as WRITE_BOD_COSTTIME_MAX\",
    MAX(WRITE_USERPWD_COSTTIME     )     as WRITE_USERPWD_COSTTIME_MAX\",
    MAX(WRITE_ADMIPWD_COSTTIME     )     as WRITE_ADMIPWD_COSTTIME_MAX\",
    MAX(WRITE_SERVPWD_COSTTIME     )     as WRITE_SERVPWD_COSTTIME_MAX\",
    MAX(WRITE_ENCRYLO_COSTTIME     )     as WRITE_ENCRYLO_COSTTIME_MAX\",
    MAX(WRITE_ENCRYHI_COSTTIME     )     as WRITE_ENCRYHI_COSTTIME_MAX\",
    MAX(WRITE_MPBID_COSTTIME       )     as WRITE_MPBID_COSTTIME_MAX\",
    MAX(WRITE_METCPWD_COSTTIME     )     as WRITE_METCPWD_COSTTIME_MAX\",
    MAX(COMMAND_FINISH_COSTTIME    )     as COMMAND_FINISH_COSTTIME_MAX\",
    MAX(ALL_COMMAND_COSTTIME       )     as ALL_COMMAND_COSTTIME_MAX\",
    MAX(GEN_SN_COSTTIME            )     as GEN_SN_COSTTIME_MAX\",
    MAX(SAVE_DATA_COSTTIME         )     as SAVE_DATA_COSTTIME_MAX\",
    MAX(LASER_COSTTIME             )     as LASER_COSTTIME_MAX\",
    MAX(TOTAL_COSTTIME             )     as TOTAL_COSTTIME_MAX\",
    
    -- min
    MIN(START_COSTTIME             )     as START_COSTTIME_MIN\",
    MIN(RESET_ADAPTER_COSTTIME     )     as RESET_ADAPTER_COSTTIME_MIN\",
    MIN(OPEN_ACCESSRIGHT_COSTTIME  )     as OPEN_ACCESSRIGHT_COSTTIME_MIN\",
    MIN(READ_FWVERSION_COSTTIME    )     as READ_FWVERSION_COSTTIME_MIN\",
    MIN(READ_CELLIDENT_COSTTIME    )     as READ_CELLIDENT_COSTTIME_MIN\",
    MIN(READ_NUMBERSER_COSTTIME    )     as READ_NUMBERSER_COSTTIME_MIN\",
    MIN(READ_PARALLELC_COSTTIME    )     as READ_PARALLELC_COSTTIME_MIN\",
    MIN(READ_OPERATION_COSTTIME    )     as READ_OPERATION_COSTTIME_MIN\",
    MIN(WRITE_BOD_COSTTIME         )     as WRITE_BOD_COSTTIME_MIN\",
    MIN(WRITE_USERPWD_COSTTIME     )     as WRITE_USERPWD_COSTTIME_MIN\",
    MIN(WRITE_ADMIPWD_COSTTIME     )     as WRITE_ADMIPWD_COSTTIME_MIN\",
    MIN(WRITE_SERVPWD_COSTTIME     )     as WRITE_SERVPWD_COSTTIME_MIN\",
    MIN(WRITE_ENCRYLO_COSTTIME     )     as WRITE_ENCRYLO_COSTTIME_MIN\",
    MIN(WRITE_ENCRYHI_COSTTIME     )     as WRITE_ENCRYHI_COSTTIME_MIN\",
    MIN(WRITE_MPBID_COSTTIME       )     as WRITE_MPBID_COSTTIME_MIN\",
    MIN(WRITE_METCPWD_COSTTIME     )     as WRITE_METCPWD_COSTTIME_MIN\",
    MIN(COMMAND_FINISH_COSTTIME    )     as COMMAND_FINISH_COSTTIME_MIN\",
    MIN(ALL_COMMAND_COSTTIME       )     as ALL_COMMAND_COSTTIME_MIN\",
    MIN(GEN_SN_COSTTIME            )     as GEN_SN_COSTTIME_MIN\",
    MIN(SAVE_DATA_COSTTIME         )     as SAVE_DATA_COSTTIME_MIN\",
    MIN(LASER_COSTTIME             )     as LASER_COSTTIME_MIN\",
    MIN(TOTAL_COSTTIME             )     as TOTAL_COSTTIME_MIN\",
    
    round(STDDEV(START_COSTTIME             ), 2)     as START_COSTTIME_STDDEV,
    round(STDDEV(RESET_ADAPTER_COSTTIME     ), 2)     as RESET_ADAPTER_COSTTIME_STD,
    round(STDDEV(OPEN_ACCESSRIGHT_COSTTIME  ), 2)     as OPEN_ACCESSRIGHT_COSTTIME_STD,
    round(STDDEV(READ_FWVERSION_COSTTIME    ), 2)     as READ_FWVERSION_COSTTIME_STD,
    round(STDDEV(READ_CELLIDENT_COSTTIME    ), 2)     as READ_CELLIDENT_COSTTIME_STD,
    round(STDDEV(READ_NUMBERSER_COSTTIME    ), 2)     as READ_NUMBERSER_COSTTIME_STD,
    round(STDDEV(READ_PARALLELC_COSTTIME    ), 2)     as READ_PARALLELC_COSTTIME_STD,
    round(STDDEV(READ_OPERATION_COSTTIME    ), 2)     as READ_OPERATION_COSTTIME_STD,
    round(STDDEV(WRITE_BOD_COSTTIME         ), 2)     as WRITE_BOD_COSTTIME_STD,
    round(STDDEV(WRITE_USERPWD_COSTTIME     ), 2)     as WRITE_USERPWD_COSTTIME_STD,
    round(STDDEV(WRITE_ADMIPWD_COSTTIME     ), 2)     as WRITE_ADMIPWD_COSTTIME_STD,
    round(STDDEV(WRITE_SERVPWD_COSTTIME     ), 2)     as WRITE_SERVPWD_COSTTIME_STD,
    round(STDDEV(WRITE_ENCRYLO_COSTTIME     ), 2)     as WRITE_ENCRYLO_COSTTIME_STD,
    round(STDDEV(WRITE_ENCRYHI_COSTTIME     ), 2)     as WRITE_ENCRYHI_COSTTIME_STD,
    round(STDDEV(WRITE_MPBID_COSTTIME       ), 2)     as WRITE_MPBID_COSTTIME_STD,
    round(STDDEV(WRITE_METCPWD_COSTTIME     ), 2)     as WRITE_METCPWD_COSTTIME_STD,
    round(STDDEV(COMMAND_FINISH_COSTTIME    ), 2)     as COMMAND_FINISH_COSTTIME_STD,
    round(STDDEV(ALL_COMMAND_COSTTIME       ), 2)     as ALL_COMMAND_COSTTIME_STD,
    round(STDDEV(GEN_SN_COSTTIME            ), 2)     as GEN_SN_COSTTIME_STD,
    round(STDDEV(SAVE_DATA_COSTTIME         ), 2)     as SAVE_DATA_COSTTIME_STD,
    round(STDDEV(LASER_COSTTIME             ), 2)     as LASER_COSTTIME_STD,
    round(STDDEV(TOTAL_COSTTIME             ), 2)     as TOTAL_COSTTIME_STDDEV90
        
    from CUX_BATTERY_DATA_KPI_LOG,
    (select timestamp '$startdate 00:00:00' + numtodsinterval(rownum*1,'DAY') as dt_timestamp from dual connect by level <= $totaldays) gens
where to_char(START_DATETIME, 'MM/DD/YYYY') =  to_char(gens.dt_start, 'MM/DD/YYYY') AND ISFINISH = 'Y'
group by PCSTATION,  
        MACHINE_NO,
        PC_NO,
        LASER_ASSET_NO,
        LASER_SUPPLIER_NAME,
        to_char(START_DATETIME, 'MM/DD/YYYY')
ORDER BY to_timestamp(to_char(START_DATETIME, 'MM/DD/YYYY'), 'MM/DD/YYYY')";





        $ret = $this->onekeydb->query($sql)->result_array();

        return $ret;
    }

    public function getDataStatisticsByDayFull($startdatepar, $dateendpar) {
//Y-m-d
        $startdate = DateTime::createFromFormat('m/d/Y', $startdatepar)->format('Y-m-d');
        $enddate = DateTime::createFromFormat('m/d/Y', $dateendpar)->format('Y-m-d');
        /*
          $sql = "
          select
          PCSTATION,
          MACHINE_NO,
          PC_NO,
          LASER_ASSET_NO,
          LASER_SUPPLIER_NAME,

          min(LASER_DATE_INSTALLED) as LASER_DATE_INSTALLED,
          min(SN) as \"recid\",

          -- avg
          round(avg(START_COSTTIME             ) , 2)    as START_COSTTIME_AVG\",
          round(avg(RESET_ADAPTER_COSTTIME     ) , 2)   as RESET_ADAPTER_COSTTIME_AVG\",
          round(avg(OPEN_ACCESSRIGHT_COSTTIME  ) , 2)   as OPEN_ACCESSRIGHT_COSTTIME_AVG\",
          round(avg(READ_FWVERSION_COSTTIME    ) , 2)   as READ_FWVERSION_COSTTIME_AVG\",
          round(avg(READ_CELLIDENT_COSTTIME    ) , 2)   as READ_CELLIDENT_COSTTIME_AVG\",
          round(avg(READ_NUMBERSER_COSTTIME    ) , 2)   as READ_NUMBERSER_COSTTIME_AVG\",
          round(avg(READ_PARALLELC_COSTTIME    ) , 2)   as READ_PARALLELC_COSTTIME_AVG\",
          round(avg(READ_OPERATION_COSTTIME    ) , 2)   as READ_OPERATION_COSTTIME_AVG\",
          round(avg(WRITE_BOD_COSTTIME         ) , 2)   as WRITE_BOD_COSTTIME_AVG\",
          round(avg(WRITE_USERPWD_COSTTIME     ) , 2)   as WRITE_USERPWD_COSTTIME_AVG\",
          round(avg(WRITE_ADMIPWD_COSTTIME     ) , 2)   as WRITE_ADMIPWD_COSTTIME_AVG\",
          round(avg(WRITE_SERVPWD_COSTTIME     ) , 2)   as WRITE_SERVPWD_COSTTIME_AVG\",
          round(avg(WRITE_ENCRYLO_COSTTIME     ) , 2)   as WRITE_ENCRYLO_COSTTIME_AVG\",
          round(avg(WRITE_ENCRYHI_COSTTIME     ) , 2)   as WRITE_ENCRYHI_COSTTIME_AVG\",
          round(avg(WRITE_MPBID_COSTTIME       ) , 2)   as WRITE_MPBID_COSTTIME_AVG\",
          round(avg(WRITE_METCPWD_COSTTIME     ) , 2)   as WRITE_METCPWD_COSTTIME_AVG\",
          round(avg(COMMAND_FINISH_COSTTIME    ) , 2)   as COMMAND_FINISH_COSTTIME_AVG\",
          round(avg(ALL_COMMAND_COSTTIME       ) , 2)   as ALL_COMMAND_COSTTIME_AVG\",
          round(avg(GEN_SN_COSTTIME            ) , 2)   as GEN_SN_COSTTIME_AVG\",
          round(avg(SAVE_DATA_COSTTIME         ) , 2)   as SAVE_DATA_COSTTIME_AVG\",
          round(avg(LASER_COSTTIME             ) , 2)   as LASER_COSTTIME_AVG\",
          round(avg(TOTAL_COSTTIME             ) , 2)   as TOTAL_COSTTIME_AVG

          from CUX_BATTERY_DATA_KPI_LOG

          where START_DATETIME BETWEEN timestamp '$startdate 00:00:00' AND timestamp '$enddate 23:59:59' AND ISFINISH = 'Y' AND PCSTATION IS NOT NULL AND LASER_SUPPLIER_NAME IS NOT NULL
          group by PCSTATION,
          MACHINE_NO,
          PC_NO,
          LASER_ASSET_NO,
          LASER_SUPPLIER_NAME
          ORDER BY PCSTATION";

          $ret = $this->onekeydb->query($sql)->result_array();
         */


        //changed to postgresql;
        /*
          $sql = "
          select
          PCSTATION as \"PCSTATION\",
          MACHINE_NO as \"MACHINE_NO\",
          PC_NO as \"PC_NO\",
          LASER_ASSET_NO as \"LASER_ASSET_NO\",
          LASER_SUPPLIER_NAME as \"LASER_SUPPLIER_NAME\",

          min(LASER_DATE_INSTALLED) as \"LASER_DATE_INSTALLED\",
          min(SN) as recid,

          -- avg
          round(avg(START_COSTTIME             ) , 2)    as \"START_COSTTIME_AVG\",
          round(avg(RESET_ADAPTER_COSTTIME     ) , 2)   as \"RESET_ADAPTER_COSTTIME_AVG\",
          round(avg(OPEN_ACCESSRIGHT_COSTTIME  ) , 2)   as \"OPEN_ACCESSRIGHT_COSTTIME_AVG\",
          round(avg(READ_FWVERSION_COSTTIME    ) , 2)   as \"READ_FWVERSION_COSTTIME_AVG\",
          round(avg(READ_CELLIDENT_COSTTIME    ) , 2)   as \"READ_CELLIDENT_COSTTIME_AVG\",
          round(avg(READ_NUMBERSER_COSTTIME    ) , 2)   as \"READ_NUMBERSER_COSTTIME_AVG\",
          round(avg(READ_PARALLELC_COSTTIME    ) , 2)   as \"READ_PARALLELC_COSTTIME_AVG\",
          round(avg(READ_OPERATION_COSTTIME    ) , 2)   as \"READ_OPERATION_COSTTIME_AVG\",
          round(avg(WRITE_BOD_COSTTIME         ) , 2)   as \"WRITE_BOD_COSTTIME_AVG\",
          round(avg(WRITE_USERPWD_COSTTIME     ) , 2)   as \"WRITE_USERPWD_COSTTIME_AVG\",
          round(avg(WRITE_ADMIPWD_COSTTIME     ) , 2)   as \"WRITE_ADMIPWD_COSTTIME_AVG\",
          round(avg(WRITE_SERVPWD_COSTTIME     ) , 2)   as \"WRITE_SERVPWD_COSTTIME_AVG\",
          round(avg(WRITE_ENCRYLO_COSTTIME     ) , 2)   as \"WRITE_ENCRYLO_COSTTIME_AVG\",
          round(avg(WRITE_ENCRYHI_COSTTIME     ) , 2)   as \"WRITE_ENCRYHI_COSTTIME_AVG\",
          round(avg(WRITE_MPBID_COSTTIME       ) , 2)   as \"WRITE_MPBID_COSTTIME_AVG\",
          round(avg(WRITE_METCPWD_COSTTIME     ) , 2)   as \"WRITE_METCPWD_COSTTIME_AVG\",
          round(avg(COMMAND_FINISH_COSTTIME    ) , 2)   as \"COMMAND_FINISH_COSTTIME_AVG\",
          round(avg(ALL_COMMAND_COSTTIME       ) , 2)   as \"ALL_COMMAND_COSTTIME_AVG\",
          round(avg(GEN_SN_COSTTIME            ) , 2)   as \"GEN_SN_COSTTIME_AVG\",
          round(avg(SAVE_DATA_COSTTIME         ) , 2)   as \"SAVE_DATA_COSTTIME_AVG\",
          round(avg(LASER_COSTTIME             ) , 2)   as \"LASER_COSTTIME_AVG\",
          round(avg(TOTAL_COSTTIME             ) , 2)   as \"TOTAL_COSTTIME_AVG\"

          from \"ONEKEY_RAW_DATA\"

          where START_DATETIME BETWEEN timestamp '$startdate 00:00:00' AND timestamp '$enddate 23:59:59' AND ISFINISH = 'Y' AND PCSTATION IS NOT NULL AND LASER_SUPPLIER_NAME IS NOT NULL
          group by PCSTATION,
          MACHINE_NO,
          PC_NO,
          LASER_ASSET_NO,
          LASER_SUPPLIER_NAME
          ORDER BY PCSTATION"; */

        $sql = "select prod_location,
    PCSTATION as \"PCSTATION\",  
    MACHINE_NO as \"MACHINE_NO\",
    PC_NO as \"PC_NO\",
    LASER_ASSET_NO as \"LASER_ASSET_NO\",
    LASER_SUPPLIER_NAME as \"LASER_SUPPLIER_NAME\",
    
    min(LASER_DATE_INSTALLED) as \"LASER_DATE_INSTALLED\",
    min(recid) as recid,
    
    -- avg
    round(avg(START_COSTTIME_AVG             ) , 2)    as \"START_COSTTIME_AVG\",
    round(avg(RESET_ADAPTER_COSTTIME_AVG     ) , 2)   as \"RESET_ADAPTER_COSTTIME_AVG\",
    round(avg(OPEN_ACCESSRIGHT_COSTTIME_AVG  ) , 2)   as \"OPEN_ACCESSRIGHT_COSTTIME_AVG\",
    round(avg(READ_FWVERSION_COSTTIME_AVG    ) , 2)   as \"READ_FWVERSION_COSTTIME_AVG\",
    round(avg(READ_CELLIDENT_COSTTIME_AVG    ) , 2)   as \"READ_CELLIDENT_COSTTIME_AVG\",
    round(avg(READ_NUMBERSER_COSTTIME_AVG    ) , 2)   as \"READ_NUMBERSER_COSTTIME_AVG\",
    round(avg(READ_PARALLELC_COSTTIME_AVG    ) , 2)   as \"READ_PARALLELC_COSTTIME_AVG\",
    round(avg(READ_OPERATION_COSTTIME_AVG    ) , 2)   as \"READ_OPERATION_COSTTIME_AVG\",
    round(avg(WRITE_BOD_COSTTIME_AVG         ) , 2)   as \"WRITE_BOD_COSTTIME_AVG\",
    round(avg(WRITE_USERPWD_COSTTIME_AVG     ) , 2)   as \"WRITE_USERPWD_COSTTIME_AVG\",
    round(avg(WRITE_ADMIPWD_COSTTIME_AVG     ) , 2)   as \"WRITE_ADMIPWD_COSTTIME_AVG\",
    round(avg(WRITE_SERVPWD_COSTTIME_AVG     ) , 2)   as \"WRITE_SERVPWD_COSTTIME_AVG\",
    round(avg(WRITE_ENCRYLO_COSTTIME_AVG     ) , 2)   as \"WRITE_ENCRYLO_COSTTIME_AVG\",
    round(avg(WRITE_ENCRYHI_COSTTIME_AVG     ) , 2)   as \"WRITE_ENCRYHI_COSTTIME_AVG\",
    round(avg(WRITE_MPBID_COSTTIME_AVG       ) , 2)   as \"WRITE_MPBID_COSTTIME_AVG\",
    round(avg(WRITE_METCPWD_COSTTIME_AVG     ) , 2)   as \"WRITE_METCPWD_COSTTIME_AVG\",
    round(avg(COMMAND_FINISH_COSTTIME_AVG    ) , 2)   as \"COMMAND_FINISH_COSTTIME_AVG\",
    round(avg(ALL_COMMAND_COSTTIME_AVG       ) , 2)   as \"ALL_COMMAND_COSTTIME_AVG\",
    round(avg(GEN_SN_COSTTIME_AVG            ) , 2)   as \"GEN_SN_COSTTIME_AVG\",
    round(avg(SAVE_DATA_COSTTIME_AVG         ) , 2)   as \"SAVE_DATA_COSTTIME_AVG\",
    round(avg(LASER_COSTTIME_AVG             ) , 2)   as \"LASER_COSTTIME_AVG\",
    round(avg(TOTAL_COSTTIME_AVG             ) , 2)   as \"TOTAL_COSTTIME_AVG\"
            
    from \"ONEKEY_DATA_SUMMARY_BY_DAY\"
   
where START_DATE BETWEEN timestamp '$startdate' AND timestamp '$enddate' 
group by prod_location, 
         PCSTATION,  
         MACHINE_NO,
         PC_NO,
         LASER_ASSET_NO,
         LASER_SUPPLIER_NAME
ORDER BY prod_location";


        $ret = $this->getCdbhelper()->basicSQLArray($sql);





        return $ret;
    }

    public function getDataByPeriod($startdatepar, $dateendpar, $SupOrPc, $description) {

        $startdate = DateTime::createFromFormat('m/d/Y', $startdatepar);
        $enddate = DateTime::createFromFormat('m/d/Y', $dateendpar);
        $days = date_diff($startdate, $enddate)->format('%a') + 1;
        $whereaddon = '';
        $whereaddonsup = '';

        //die ($days);


        $startDB = $startdate->format('Y-m-d');
        $endDB = $enddate->format('Y-m-d');

        IF ($description != 'ALL') {
            if ($SupOrPc == 'S') {
                $whereaddonsup = " AND LASER_SUPPLIER_NAME = '$description' ";
            } else {
                $whereaddonsup = " AND prod_location = '$description' ";
            }
        }
        $whereaddon = $whereaddon . "  \"ONEKEY_DATA_SUMMARY_BY_MINUTE_SUPPLIER_PC\".dt_start BETWEEN gens.dt_start and gens.dt_end";


        switch ($days) {
            case 1:
                // by minute = one day = 24*60 minutes:
                $legend = "to_char(gens.dt_start, 'HH24:MI')";
                $period = '1 minute';

                break;

            case 2:
// by 2 minutes = 2 day = 48*30 minutes:
                $legend = "to_char(gens.dt_start, 'MM/DD-HH24:MI')";
                $period = '2 minute';

                break;

            case $days > 2 && $days <= 5:
// by 15 Munutes, means 4 times per hour. 
                $legend = "to_char(gens.dt_start, 'MM/DD-HH24:MI')";
                $period = '15 minute';

                break;

            case $days > 5 && $days <= 20:
// by 30 Munutes, means 2 times per hour. 
                $legend = "to_char(gens.dt_start, 'MM/DD-HH24:MI')";
                $period = '30 minute';
                break;

            case $days > 20 && $days <= 30:
// by 1 hour. 
                $legend = "to_char(gens.dt_start, 'MM/DD-HH24')";
                $period = '1 hour';

                break;

            case $days > 30 && $days <= 60:
// by 12 hours, means 2 times per day. 
                $legend = "to_char(gens.dt_start, 'MM/DD-HH24')";
                $period = '12 hour';

                break;



            default:
// others is by day
                $legend = "to_char(gens.dt_start, 'MM/DD')";
                $period = '1 day';

                break;
        }


        $fromgen = "( SELECT dd as dt_start, dd + '$period' - '1 second' ::interval as dt_end FROM generate_series ( '$startDB 00:00'::timestamp , '$endDB 23:59'::timestamp , '$period'::interval) dd ) as gens";



        $sql = "select 
                min(SN) as \"recid\",
                gens.dt_start,
                $legend as \"ds_legend\",   
                COALESCE(round(avg(TOTAL_COSTTIME_AVG) , 2) , 0)  as \"TOTAL_COSTTIME_AVG\"
                
                from $fromgen
                LEFT OUTER JOIN \"ONEKEY_DATA_SUMMARY_BY_MINUTE_SUPPLIER_PC\" ON ($whereaddon)
                

            where  1 = 1 $whereaddonsup 

            group by gens.dt_start, $legend
            ORDER BY gens.dt_start";

        //die($sql);

        //$ret = $this->onekeydb->query($sql)->result_array();
        $ret = $this->getCdbhelper()->basicSQLArray($sql);

        return $ret;
    }

    public function getDataByPeriodBKP($startdatepar, $dateendpar, $SupOrPc, $description) {

        $startdate = DateTime::createFromFormat('m/d/Y', $startdatepar);
        $enddate = DateTime::createFromFormat('m/d/Y', $dateendpar);
        $days = date_diff($startdate, $enddate)->format('%a') + 1;
        $whereaddon = '';
        $whereaddonsup = '';

        //die ($days);


        $startDB = $startdate->format('Y-m-d');
        $endDB = $enddate->format('Y-m-d');

        IF ($description != 'ALL') {
            if ($SupOrPc == 'S') {
                $whereaddonsup = " AND LASER_SUPPLIER_NAME = '$description' ";
            } else {
                $whereaddonsup = " AND PCSTATION = '$description' ";
            }
        }

        switch ($days) {
            case 1:
                // by minute = one day = 24*60 minutes:
                $totalloops = 1440;
                $legend = "to_char(gens.dt_start, 'HH24:MI')";
                $fromgen = "(select timestamp '$startDB 00:00:00' + numtodsinterval(rownum*1,'MINUTE') as dt_timestamp from dual connect by level <= $totalloops) gens";
                $whereaddon = $whereaddon . " START_DATETIME BETWEEN to_timestamp (to_char(gens.dt_start, 'YYYY-MM-DD HH24:MI') || ':00', 'YYYY-MM-DD HH24:MI:SS')  AND to_timestamp (to_char(gens.dt_start, 'YYYY-MM-DD HH24:MI') || ':59','YYYY-MM-DD HH24:MI:SS') AND ISFINISH = 'Y'";


                break;

            case 2:
// by 2 minutes = 2 day = 48*30 minutes:
                $totalloops = 1440;
                $legend = "to_char(gens.dt_start, 'MM/DD-HH24:MI')";
                $fromgen = "(select timestamp '$startDB 00:00:00' + numtodsinterval(rownum*2,'MINUTE') as dt_timestamp from dual connect by level <= $totalloops) gens";
                $whereaddon = $whereaddon . " START_DATETIME BETWEEN to_timestamp (to_char(gens.dt_start, 'YYYY-MM-DD HH24:MI') || ':00', 'YYYY-MM-DD HH24:MI:SS') - NUMTODSINTERVAL(2, 'MINUTE')  AND to_timestamp (to_char(gens.dt_start, 'YYYY-MM-DD HH24:MI') || ':59','YYYY-MM-DD HH24:MI:SS') - NUMTODSINTERVAL(1, 'SECOND')";


                break;

            case $days > 2 && $days <= 5:
// by 15 Munutes, means 4 times per hour. 
                $totalloops = $days * 24 * 4;
                $legend = "to_char(gens.dt_start, 'MM/DD-HH24:MI')";
                $fromgen = "(select timestamp '$startDB 00:00:00' + numtodsinterval(rownum*15,'MINUTE') as dt_timestamp from dual connect by level <= $totalloops) gens";
                $whereaddon = $whereaddon . " START_DATETIME BETWEEN to_timestamp (to_char(gens.dt_start, 'YYYY-MM-DD HH24:MI') || ':00', 'YYYY-MM-DD HH24:MI:SS') - NUMTODSINTERVAL(15, 'MINUTE')  AND to_timestamp (to_char(gens.dt_start, 'YYYY-MM-DD HH24:MI') || ':59','YYYY-MM-DD HH24:MI:SS') - NUMTODSINTERVAL(1, 'SECOND')";


                break;

            case $days > 5 && $days <= 20:
// by 30 Munutes, means 2 times per hour. 
                $totalloops = $days * 24 * 2;
                $legend = "to_char(gens.dt_start, 'MM/DD-HH24:MI')";
                $fromgen = "(select timestamp '$startDB 00:00:00' + numtodsinterval(rownum*30,'MINUTE') as dt_timestamp from dual connect by level <= $totalloops) gens";
                $whereaddon = $whereaddon . " START_DATETIME BETWEEN to_timestamp (to_char(gens.dt_start, 'YYYY-MM-DD HH24:MI') || ':00', 'YYYY-MM-DD HH24:MI:SS') - NUMTODSINTERVAL(30, 'MINUTE')  AND to_timestamp (to_char(gens.dt_start, 'YYYY-MM-DD HH24:MI') || ':59','YYYY-MM-DD HH24:MI:SS') - NUMTODSINTERVAL(1, 'SECOND')";

                break;

            case $days > 20 && $days <= 30:
// by 1 hour. 
                $totalloops = $days * 24;
                $legend = "to_char(gens.dt_start, 'MM/DD-HH24')";
                $fromgen = "(select timestamp '$startDB 00:00:00' + numtodsinterval(rownum*1,'HOUR') as dt_timestamp from dual connect by level <= $totalloops) gens";
                $whereaddon = $whereaddon . " START_DATETIME BETWEEN to_timestamp (to_char(gens.dt_start, 'YYYY-MM-DD HH24') || ':00:00', 'YYYY-MM-DD HH24:MI:SS') AND to_timestamp (to_char(gens.dt_start, 'YYYY-MM-DD HH24') || ':59:59','YYYY-MM-DD HH24:MI:SS') - NUMTODSINTERVAL(1, 'SECOND')";

                break;

            case $days > 30 && $days <= 60:
// by 12 hours, means 2 times per day. 
                $totalloops = $days * 24 * 2;
                $legend = "to_char(gens.dt_start, 'MM/DD-HH24')";
                $fromgen = "(select timestamp '$startDB 00:00:00' + numtodsinterval(rownum*12,'HOUR') as dt_timestamp from dual connect by level <= $totalloops) gens";
                $whereaddon = $whereaddon . " START_DATETIME BETWEEN to_timestamp (to_char(gens.dt_start, 'YYYY-MM-DD HH24') || ':00:00', 'YYYY-MM-DD HH24:MI:SS') - NUMTODSINTERVAL(12, 'HOUR') AND to_timestamp (to_char(gens.dt_start, 'YYYY-MM-DD HH24') || ':59:59','YYYY-MM-DD HH24:MI:SS') - NUMTODSINTERVAL(1, 'SECOND')";

                break;



            default:
// others is by day
                $totalloops = $days;
                $legend = "to_char(gens.dt_start, 'MM/DD')";
                $fromgen = "(select timestamp '$startDB 00:00:00' + numtodsinterval(rownum*1,'DAY') as dt_timestamp from dual connect by level <= $totalloops) gens";
                $whereaddon = $whereaddon . " START_DATETIME BETWEEN to_timestamp (to_char(gens.dt_start, 'YYYY-MM-DD') || ':00:00:00', 'YYYY-MM-DD HH24:MI:SS') AND to_timestamp (to_char(gens.dt_start, 'YYYY-MM-DD') || ':23:59:59','YYYY-MM-DD HH24:MI:SS')";

                break;
        }





        $sql = "select 
                min(SN) as \"recid\",
                gens.dt_timestamp,
                $legend as \"ds_legend\",   


                COALESCE(round(avg(START_COSTTIME             ) , 2) , 0)   as START_COSTTIME_AVG\",
                COALESCE(round(avg(RESET_ADAPTER_COSTTIME     ) , 2) , 0)  as RESET_ADAPTER_COSTTIME_AVG\",
                COALESCE(round(avg(OPEN_ACCESSRIGHT_COSTTIME  ) , 2) , 0)  as OPEN_ACCESSRIGHT_COSTTIME_AVG\",
                COALESCE(round(avg(READ_FWVERSION_COSTTIME    ) , 2) , 0)  as READ_FWVERSION_COSTTIME_AVG\",
                COALESCE(round(avg(READ_CELLIDENT_COSTTIME    ) , 2) , 0)  as READ_CELLIDENT_COSTTIME_AVG\",
                COALESCE(round(avg(READ_NUMBERSER_COSTTIME    ) , 2) , 0)  as READ_NUMBERSER_COSTTIME_AVG\",
                COALESCE(round(avg(READ_PARALLELC_COSTTIME    ) , 2) , 0)  as READ_PARALLELC_COSTTIME_AVG\",
                COALESCE(round(avg(READ_OPERATION_COSTTIME    ) , 2) , 0)  as READ_OPERATION_COSTTIME_AVG\",
                COALESCE(round(avg(WRITE_BOD_COSTTIME         ) , 2) , 0)  as WRITE_BOD_COSTTIME_AVG\",
                COALESCE(round(avg(WRITE_USERPWD_COSTTIME     ) , 2) , 0)  as WRITE_USERPWD_COSTTIME_AVG\",
                COALESCE(round(avg(WRITE_ADMIPWD_COSTTIME     ) , 2) , 0)  as WRITE_ADMIPWD_COSTTIME_AVG\",
                COALESCE(round(avg(WRITE_SERVPWD_COSTTIME     ) , 2) , 0)  as WRITE_SERVPWD_COSTTIME_AVG\",
                COALESCE(round(avg(WRITE_ENCRYLO_COSTTIME     ) , 2) , 0)  as WRITE_ENCRYLO_COSTTIME_AVG\",
                COALESCE(round(avg(WRITE_ENCRYHI_COSTTIME     ) , 2) , 0)  as WRITE_ENCRYHI_COSTTIME_AVG\",
                COALESCE(round(avg(WRITE_MPBID_COSTTIME       ) , 2) , 0)  as WRITE_MPBID_COSTTIME_AVG\",
                COALESCE(round(avg(WRITE_METCPWD_COSTTIME     ) , 2) , 0)  as WRITE_METCPWD_COSTTIME_AVG\",
                COALESCE(round(avg(COMMAND_FINISH_COSTTIME    ) , 2) , 0)  as COMMAND_FINISH_COSTTIME_AVG\",
                COALESCE(round(avg(ALL_COMMAND_COSTTIME       ) , 2) , 0)  as ALL_COMMAND_COSTTIME_AVG\",
                COALESCE(round(avg(GEN_SN_COSTTIME            ) , 2) , 0)  as GEN_SN_COSTTIME_AVG\",
                COALESCE(round(avg(SAVE_DATA_COSTTIME         ) , 2) , 0)  as SAVE_DATA_COSTTIME_AVG\",
                COALESCE(round(avg(LASER_COSTTIME             ) , 2) , 0)  as LASER_COSTTIME_AVG\",
                COALESCE(round(avg(TOTAL_COSTTIME             ) , 2) , 0)  as TOTAL_COSTTIME_AVG\",

                COALESCE(MAX(START_COSTTIME             ) , 0)    as START_COSTTIME_MAX\",
                COALESCE(MAX(RESET_ADAPTER_COSTTIME     ) , 0)    as RESET_ADAPTER_COSTTIME_MAX\",
                COALESCE(MAX(OPEN_ACCESSRIGHT_COSTTIME  ) , 0)    as OPEN_ACCESSRIGHT_COSTTIME_MAX\",
                COALESCE(MAX(READ_FWVERSION_COSTTIME    ) , 0)    as READ_FWVERSION_COSTTIME_MAX\",
                COALESCE(MAX(READ_CELLIDENT_COSTTIME    ) , 0)    as READ_CELLIDENT_COSTTIME_MAX\",
                COALESCE(MAX(READ_NUMBERSER_COSTTIME    ) , 0)    as READ_NUMBERSER_COSTTIME_MAX\",
                COALESCE(MAX(READ_PARALLELC_COSTTIME    ) , 0)    as READ_PARALLELC_COSTTIME_MAX\",
                COALESCE(MAX(READ_OPERATION_COSTTIME    ) , 0)    as READ_OPERATION_COSTTIME_MAX\",
                COALESCE(MAX(WRITE_BOD_COSTTIME         ) , 0)    as WRITE_BOD_COSTTIME_MAX\",
                COALESCE(MAX(WRITE_USERPWD_COSTTIME     ) , 0)    as WRITE_USERPWD_COSTTIME_MAX\",
                COALESCE(MAX(WRITE_ADMIPWD_COSTTIME     ) , 0)    as WRITE_ADMIPWD_COSTTIME_MAX\",
                COALESCE(MAX(WRITE_SERVPWD_COSTTIME     ) , 0)    as WRITE_SERVPWD_COSTTIME_MAX\",
                COALESCE(MAX(WRITE_ENCRYLO_COSTTIME     ) , 0)    as WRITE_ENCRYLO_COSTTIME_MAX\",
                COALESCE(MAX(WRITE_ENCRYHI_COSTTIME     ) , 0)    as WRITE_ENCRYHI_COSTTIME_MAX\",
                COALESCE(MAX(WRITE_MPBID_COSTTIME       ) , 0)    as WRITE_MPBID_COSTTIME_MAX\",
                COALESCE(MAX(WRITE_METCPWD_COSTTIME     ) , 0)    as WRITE_METCPWD_COSTTIME_MAX\",
                COALESCE(MAX(COMMAND_FINISH_COSTTIME    ) , 0)    as COMMAND_FINISH_COSTTIME_MAX\",
                COALESCE(MAX(ALL_COMMAND_COSTTIME       ) , 0)    as ALL_COMMAND_COSTTIME_MAX\",
                COALESCE(MAX(GEN_SN_COSTTIME            ) , 0)    as GEN_SN_COSTTIME_MAX\",
                COALESCE(MAX(SAVE_DATA_COSTTIME         ) , 0)    as SAVE_DATA_COSTTIME_MAX\",
                COALESCE(MAX(LASER_COSTTIME             ) , 0)    as LASER_COSTTIME_MAX\",
                COALESCE(MAX(TOTAL_COSTTIME             ) , 0)    as TOTAL_COSTTIME_MAX\",

                COALESCE(MIN(START_COSTTIME             ) , 0)    as START_COSTTIME_MIN\",
                COALESCE(MIN(RESET_ADAPTER_COSTTIME     ) , 0)    as RESET_ADAPTER_COSTTIME_MIN\",
                COALESCE(MIN(OPEN_ACCESSRIGHT_COSTTIME  ) , 0)    as OPEN_ACCESSRIGHT_COSTTIME_MIN\",
                COALESCE(MIN(READ_FWVERSION_COSTTIME    ) , 0)    as READ_FWVERSION_COSTTIME_MIN\",
                COALESCE(MIN(READ_CELLIDENT_COSTTIME    ) , 0)    as READ_CELLIDENT_COSTTIME_MIN\",
                COALESCE(MIN(READ_NUMBERSER_COSTTIME    ) , 0)    as READ_NUMBERSER_COSTTIME_MIN\",
                COALESCE(MIN(READ_PARALLELC_COSTTIME    ) , 0)    as READ_PARALLELC_COSTTIME_MIN\",
                COALESCE(MIN(READ_OPERATION_COSTTIME    ) , 0)    as READ_OPERATION_COSTTIME_MIN\",
                COALESCE(MIN(WRITE_BOD_COSTTIME         ) , 0)    as WRITE_BOD_COSTTIME_MIN\",
                COALESCE(MIN(WRITE_USERPWD_COSTTIME     ) , 0)    as WRITE_USERPWD_COSTTIME_MIN\",
                COALESCE(MIN(WRITE_ADMIPWD_COSTTIME     ) , 0)    as WRITE_ADMIPWD_COSTTIME_MIN\",
                COALESCE(MIN(WRITE_SERVPWD_COSTTIME     ) , 0)    as WRITE_SERVPWD_COSTTIME_MIN\",
                COALESCE(MIN(WRITE_ENCRYLO_COSTTIME     ) , 0)    as WRITE_ENCRYLO_COSTTIME_MIN\",
                COALESCE(MIN(WRITE_ENCRYHI_COSTTIME     ) , 0)    as WRITE_ENCRYHI_COSTTIME_MIN\",
                COALESCE(MIN(WRITE_MPBID_COSTTIME       ) , 0)    as WRITE_MPBID_COSTTIME_MIN\",
                COALESCE(MIN(WRITE_METCPWD_COSTTIME     ) , 0)    as WRITE_METCPWD_COSTTIME_MIN\",
                COALESCE(MIN(COMMAND_FINISH_COSTTIME    ) , 0)    as COMMAND_FINISH_COSTTIME_MIN\",
                COALESCE(MIN(ALL_COMMAND_COSTTIME       ) , 0)    as ALL_COMMAND_COSTTIME_MIN\",
                COALESCE(MIN(GEN_SN_COSTTIME            ) , 0)    as GEN_SN_COSTTIME_MIN\",
                COALESCE(MIN(SAVE_DATA_COSTTIME         ) , 0)    as SAVE_DATA_COSTTIME_MIN\",
                COALESCE(MIN(LASER_COSTTIME             ) , 0)    as LASER_COSTTIME_MIN\",
                COALESCE(MIN(TOTAL_COSTTIME             ) , 0)    as TOTAL_COSTTIME_MIN

                from $fromgen
                LEFT OUTER JOIN CUX_BATTERY_DATA_KPI_LOG ON ($whereaddon)
                

            where  1 = 1 $whereaddonsup 

            group by gens.dt_timestamp, $legend
            ORDER BY gens.dt_timestamp
";






        $ret = $this->onekeydb->query($sql)->result_array();

        return $ret;
    }

    public function importDataFromOracle() {
        $this->onekeydb = $this->load->database('onekey', true);
        $this->onekeydb->query("ALTER SESSION SET NLS_TIMESTAMP_FORMAT  = 'MM/DD/YYYY HH24:MI:SS'");


        $this->benchmark->mark('code_start');

        $inserted = 0;
        $sql = 'select to_char(max(START_DATETIME)   -  INTERVAL \'2 DAY\', \'mm/dd/yyyy\') || \' 00:00:00\' as dt_next from "ONEKEY_RAW_DATA"';
        $nextarray = $this->getCdbhelper()->basicSQLArray($sql);
        $dtnext = $nextarray[0]['dt_next'];

        $sqlDel = "delete from \"ONEKEY_RAW_DATA\" WHERE START_DATETIME >= to_timestamp ('$dtnext', 'mm/dd/yyyy HH24:MI:SS')";

        $arrayOracleArray = $this->onekeydb->query("select * from CUX_BATTERY_DATA_KPI_LOG WHERE START_DATETIME >= '$dtnext'")->result_array();
        $this->benchmark->mark('code_after_retrieve');


        $this->getCdbhelper()->trans_begin();

        $this->getCdbhelper()->CIBasicQuery($sqlDel);
        if (!$this->getCdbhelper()->trans_status()) {
            $error = $this->getCdbhelper()->trans_last_error();
            $this->getCdbhelper()->trans_rollback();
            $this->getCdbhelper()->trans_end();

            $msg = '{"status":' . json_encode($error) . ', "rs":{}}';
            $this->cdbhelper->trans_end();
            die($msg);
        }

        $this->benchmark->mark('code_after_delete');


        foreach ($arrayOracleArray as $key => $value) {
            $value = array_change_key_case($value);

            $sql = $this->db->set($value)->get_compiled_insert('ONEKEY_RAW_DATA');

            $this->getCdbhelper()->CIBasicQuery($sql);
            if (!$this->getCdbhelper()->trans_status()) {
                $error = $this->getCdbhelper()->trans_last_error();
                $this->getCdbhelper()->trans_rollback();
                $this->getCdbhelper()->trans_end();

                $msg = '{"status":' . json_encode($error) . ', "rs":{}}';
                $this->cdbhelper->trans_end();
                die($msg);
            }

            $inserted ++;
        }

        $this->benchmark->mark('code_after_insert');
        $this->getCdbhelper()->CIBasicQuery('select 1 from OneKeyDataSummary();');
        $this->benchmark->mark('code_after_summary');
        

        $this->getCdbhelper()->trans_commit();
        $this->getCdbhelper()->trans_end();
        $vardata = "Retrieve Time: " . $this->benchmark->elapsed_time('code_start', 'code_after_retrieve') . "\n";
        $vardata = $vardata . "Delete Time: " . $this->benchmark->elapsed_time('code_after_retrieve', 'code_after_delete') . "\n";
        $vardata = $vardata . "Insert Time: " . $this->benchmark->elapsed_time('code_after_delete', 'code_after_insert') . "\n";
        $vardata = $vardata . "Summary Time: " . $this->benchmark->elapsed_time('code_after_insert', 'code_after_summary') . "\n";
        $vardata = $vardata . "Total Time: " . $this->benchmark->elapsed_time('code_start', 'code_after_summary') . "\n";
        




        $msg = '{"status":"OK" , "imported":"' . $inserted . '", "timings": ' . json_encode($vardata) . '}';
        die($msg);
    }

}
