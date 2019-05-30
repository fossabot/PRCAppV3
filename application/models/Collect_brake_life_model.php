<?php

include_once APPPATH . "models/modelBasicExtend.php";

class collect_brake_life_model extends modelBasicExtend {

    function __construct() {

        $this->table = "COLLECT_BRAKE_LIFE";

        $this->pk_field = "recid";
        $this->ds_field = "dsbid";
        $this->prodCatUnique = 'N';

        $this->sequence_obj = '"COLLECT_BRAKE_LIFE_recid_seq"';

        $this->controller = 'collect_brake_life';


        $this->fieldsforGrid = array(
            ' "COLLECT_BRAKE_LIFE".recid',
            ' "COLLECT_BRAKE_LIFE".subid',
            ' "COLLECT_BRAKE_LIFE".taskid',
            ' "COLLECT_BRAKE_LIFE".testmethod',
            ' "COLLECT_BRAKE_LIFE".starttesttime',
            ' "COLLECT_BRAKE_LIFE".alreadytesttime',
            ' "COLLECT_BRAKE_LIFE".endtesttime',
            ' "COLLECT_BRAKE_LIFE".currentstatus',
            ' "COLLECT_BRAKE_LIFE".currentcycle',
            ' "COLLECT_BRAKE_LIFE".testresult',
            ' "COLLECT_BRAKE_LIFE".controllerno',
            ' "COLLECT_BRAKE_LIFE".jigno',
            ' "COLLECT_BRAKE_LIFE".projectno',
            ' "COLLECT_BRAKE_LIFE".ttimodelno',
            ' "COLLECT_BRAKE_LIFE".sampleno',
            ' "COLLECT_BRAKE_LIFE".custommodelno',
            ' "COLLECT_BRAKE_LIFE".requisitionno',
            ' "COLLECT_BRAKE_LIFE".requisitiondate',
            ' "COLLECT_BRAKE_LIFE".requisitionperson',
            ' "COLLECT_BRAKE_LIFE".specification',
            ' "COLLECT_BRAKE_LIFE".specificationunit',
            ' "COLLECT_BRAKE_LIFE".wheremade',
            ' "COLLECT_BRAKE_LIFE".typea',
            ' "COLLECT_BRAKE_LIFE".ebsample',
            ' "COLLECT_BRAKE_LIFE".qualificationbuild',
            ' "COLLECT_BRAKE_LIFE".productdescription',
            ' "COLLECT_BRAKE_LIFE".evaluatedpurpose',
            ' "COLLECT_BRAKE_LIFE".partdescription',
            ' "COLLECT_BRAKE_LIFE".testtype',
            ' "COLLECT_BRAKE_LIFE".modulus',
            ' "COLLECT_BRAKE_LIFE".recordedbycycles',
            ' "COLLECT_BRAKE_LIFE".note',
            ' "COLLECT_BRAKE_LIFE".voltagehienable',
            ' "COLLECT_BRAKE_LIFE".voltagehilimit',
            ' "COLLECT_BRAKE_LIFE".voltageloenable',
            ' "COLLECT_BRAKE_LIFE".voltagelolimit',
            ' "COLLECT_BRAKE_LIFE".alarmtype',
            ' "COLLECT_BRAKE_LIFE".noloadhienable',
            ' "COLLECT_BRAKE_LIFE".noloadhilimit',
            ' "COLLECT_BRAKE_LIFE".noloadloenable',
            ' "COLLECT_BRAKE_LIFE".noloadlolimit',
            ' "COLLECT_BRAKE_LIFE".loadhienable',
            ' "COLLECT_BRAKE_LIFE".loadhilimit',
            ' "COLLECT_BRAKE_LIFE".loadloenable',
            ' "COLLECT_BRAKE_LIFE".loadlolimit',
            ' "COLLECT_BRAKE_LIFE".temp1enable',
            ' "COLLECT_BRAKE_LIFE".temp1location',
            ' "COLLECT_BRAKE_LIFE".temp1hienable',
            ' "COLLECT_BRAKE_LIFE".temp1hilimit',
            ' "COLLECT_BRAKE_LIFE".temp1loenable',
            ' "COLLECT_BRAKE_LIFE".temp1lolimit',
            ' "COLLECT_BRAKE_LIFE".temp2enable',
            ' "COLLECT_BRAKE_LIFE".temp2location',
            ' "COLLECT_BRAKE_LIFE".temp2hienable',
            ' "COLLECT_BRAKE_LIFE".temp2hilimit',
            ' "COLLECT_BRAKE_LIFE".temp2loenable',
            ' "COLLECT_BRAKE_LIFE".temp2lolimit',
            ' "COLLECT_BRAKE_LIFE".temp3enable',
            ' "COLLECT_BRAKE_LIFE".temp3location',
            ' "COLLECT_BRAKE_LIFE".temp3hienable',
            ' "COLLECT_BRAKE_LIFE".temp3hilimit',
            ' "COLLECT_BRAKE_LIFE".temp3loenable',
            ' "COLLECT_BRAKE_LIFE".temp3lolimit',
            ' "COLLECT_BRAKE_LIFE".temp4enable',
            ' "COLLECT_BRAKE_LIFE".temp4location',
            ' "COLLECT_BRAKE_LIFE".temp4hienable',
            ' "COLLECT_BRAKE_LIFE".temp4hilimit',
            ' "COLLECT_BRAKE_LIFE".temp4loenable',
            ' "COLLECT_BRAKE_LIFE".temp4lolimit',
            ' "COLLECT_BRAKE_LIFE".report',
            ' "COLLECT_BRAKE_LIFE".alarm',
            ' "COLLECT_BRAKE_LIFE".voltageprotect',
            ' "COLLECT_BRAKE_LIFE".alarmdelay',
            ' "COLLECT_BRAKE_LIFE".calibrationspeed',
            ' "COLLECT_BRAKE_LIFE".torquesensitivity',
            ' "COLLECT_BRAKE_LIFE".department',
            ' "COLLECT_BRAKE_LIFE".dt_record',
            ' "COLLECT_BRAKE_LIFE".nr_wo_data',
            ' "COLLECT_BRAKE_LIFE".nr_sample');
        $this->fieldsUpd = array("recid", "subid", "taskid", "testmethod", "starttesttime", "alreadytesttime", "endtesttime", "currentstatus", "currentcycle", "testresult", "controllerno", "jigno", "projectno", "ttimodelno", "sampleno", "custommodelno", "requisitionno", "requisitiondate", "requisitionperson", "specification", "specificationunit", "wheremade", "typea", "ebsample", "qualificationbuild", "productdescription", "evaluatedpurpose", "partdescription", "testtype", "modulus", "recordedbycycles", "note", "voltagehienable", "voltagehilimit", "voltageloenable", "voltagelolimit", "alarmtype", "noloadhienable", "noloadhilimit", "noloadloenable", "noloadlolimit", "loadhienable", "loadhilimit", "loadloenable", "loadlolimit", "temp1enable", "temp1location", "temp1hienable", "temp1hilimit", "temp1loenable", "temp1lolimit", "temp2enable", "temp2location", "temp2hienable", "temp2hilimit", "temp2loenable", "temp2lolimit", "temp3enable", "temp3location", "temp3hienable", "temp3hilimit", "temp3loenable", "temp3lolimit", "temp4enable", "temp4location", "temp4hienable", "temp4hilimit", "temp4loenable", "temp4lolimit", "report", "alarm", "voltageprotect", "alarmdelay", "calibrationspeed", "torquesensitivity", "department", "dt_record", "nr_wo_data", "nr_sample",);


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "stylecond" => "(CASE WHEN \"COLLECT_BRAKE_LIFE\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );






        parent::__construct();
    }

    public function getWOForProject($wos) {
        $wosString = implode(',', $wos);

/*
            "(CASE SpecificationUnit
                                 WHEN 'Cycle' THEN CurrentCycle
                                 WHEN 'Hour'
                                 THEN CAST(AlreadyTestTime / 36000.0 AS DECIMAL(9,1)) END) as ds_comp_cycle",

 *  */
        
        
        $this->fieldsforGridShow = array(
            "('FIXTURE LIFE') as ds_source",
            ' ( CASE WHEN "COLLECT_BRAKE_LIFE".starttesttime > \'01/01/1980\' THEN ( datetimedbtogrid( "COLLECT_BRAKE_LIFE".starttesttime) ) ELSE \'\' END) as ds_start_date',
            ' ( CASE WHEN "COLLECT_BRAKE_LIFE".endtesttime > \'01/01/1980\' THEN ( datetimedbtogrid( "COLLECT_BRAKE_LIFE".endtesttime) ) ELSE \'\' END) as ds_actual_complete',
            ' ( "COLLECT_BRAKE_LIFE".currentstatus || \'/\' || "COLLECT_BRAKE_LIFE".testresult ) as ds_tool_status',
            "(CurrentCycle) as ds_comp_cycle",
            "(CAST(AlreadyTestTime / 36000.0 AS DECIMAL(9,1) ) )   as ds_comp_runtime ",
            ' ( "COLLECT_BRAKE_LIFE".controllerno ) as ds_work_station',
            '( "COLLECT_BRAKE_LIFE".requisitionperson ) as ds_test_eng ',
            ' "COLLECT_BRAKE_LIFE".nr_wo_data',
            ' "COLLECT_BRAKE_LIFE".nr_sample'
        );
        
        $join = array(
            "JOIN ( select max(recid) as recidx, x.nr_wo_data, x.nr_sample from \"COLLECT_BRAKE_LIFE\" x WHERE x.nr_wo_data in ($wosString) group by x.nr_wo_data, x.nr_sample ) a ON ( \"COLLECT_BRAKE_LIFE\".recid = a.recidx )"
        );

        $this->retrOptionsShow = array("fieldrecid" => $this->pk_field,
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGridShow),
            'join' => $join
        );
        
        return $this->retRetrieveArray('', '', $this->retrOptionsShow);
        
        
    }

}
