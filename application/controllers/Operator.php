<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class Operator extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        //$this->load->model("location_model", "mainmodel", TRUE);


        error_reporting(E_ALL);
        ini_set('display_errors', 1);
    }

    public function index() {

        parent::checkMenuPermission();
    }

    public function getDataBrakeLifeDB() {

        $DB = $this->load->database('tr', true);

        $query = 'SELECT [SubID]
      ,[TaskID]
      ,[TestMethod]
      ,[StartTestTime]
      ,[AlreadyTestTime]
      ,[EndTestTime]
      ,[CurrentStatus]
      ,[CurrentCycle]
      ,[TestResult]
      ,[ControllerNo]
      ,[JigNo]
      ,[ProjectNo]
      ,[TTiModelNo]
      ,[SampleNo]
      ,[CustomModelNo]
      ,[RequisitionNo]
      ,[RequisitionDate]
      ,[RequisitionPerson]
      ,[Specification]
      ,[SpecificationUnit]
      ,[WhereMade]
      ,[TypeA]
      ,[EBSample]
      ,[QualificationBuild]
      ,[ProductDescription]
      ,[EvaluatedPurpose]
      ,[PartDescription]
      ,[TestType]
      ,[Modulus]
      ,[RecordedbyCycles]
      ,[Note]
      ,[VoltageHiEnable]
      ,[VoltageHiLimit]
      ,[VoltageLoEnable]
      ,[VoltageLoLimit]
      ,[AlarmType]
      ,[NoLoadHiEnable]
      ,[NoLoadHiLimit]
      ,[NoLoadLoEnable]
      ,[NoLoadLoLimit]
      ,[LoadHiEnable]
      ,[LoadHiLimit]
      ,[LoadLoEnable]
      ,[LoadLoLimit]
      ,[Temp1Enable]
      ,[Temp1Location]
      ,[Temp1HiEnable]
      ,[Temp1HiLimit]
      ,[Temp1LoEnable]
      ,[Temp1LoLimit]
      ,[Temp2Enable]
      ,[Temp2Location]
      ,[Temp2HiEnable]
      ,[Temp2HiLimit]
      ,[Temp2LoEnable]
      ,[Temp2LoLimit]
      ,[Temp3Enable]
      ,[Temp3Location]
      ,[Temp3HiEnable]
      ,[Temp3HiLimit]
      ,[Temp3LoEnable]
      ,[Temp3LoLimit]
      ,[Temp4Enable]
      ,[Temp4Location]
      ,[Temp4HiEnable]
      ,[Temp4HiLimit]
      ,[Temp4LoEnable]
      ,[Temp4LoLimit]
      ,[Report]
      ,[Alarm]
      ,[VoltageProtect]
      ,[AlarmDelay]
      ,[CalibrationSpeed]
      ,[TorqueSensitivity]
      ,[Department]
  FROM [CT4717].[Substation].[dbo].[TestList]
UNION
SELECT [SubID]
      ,[TaskID]
      ,[TestMethod]
      ,[StartTestTime]
      ,[AlreadyTestTime]
      ,[EndTestTime]
      ,[CurrentStatus]
      ,[CurrentCycle]
      ,[TestResult]
      ,[ControllerNo]
      ,[JigNo]
      ,[ProjectNo]
      ,[TTiModelNo]
      ,[SampleNo]
      ,[CustomModelNo]
      ,[RequisitionNo]
      ,[RequisitionDate]
      ,[RequisitionPerson]
      ,[Specification]
      ,[SpecificationUnit]
      ,[WhereMade]
      ,[TypeA]
      ,[EBSample]
      ,[QualificationBuild]
      ,[ProductDescription]
      ,[EvaluatedPurpose]
      ,[PartDescription]
      ,[TestType]
      ,[Modulus]
      ,[RecordedbyCycles]
      ,[Note]
      ,[VoltageHiEnable]
      ,[VoltageHiLimit]
      ,[VoltageLoEnable]
      ,[VoltageLoLimit]
      ,[AlarmType]
      ,[NoLoadHiEnable]
      ,[NoLoadHiLimit]
      ,[NoLoadLoEnable]
      ,[NoLoadLoLimit]
      ,[LoadHiEnable]
      ,[LoadHiLimit]
      ,[LoadLoEnable]
      ,[LoadLoLimit]
      ,[Temp1Enable]
      ,[Temp1Location]
      ,[Temp1HiEnable]
      ,[Temp1HiLimit]
      ,[Temp1LoEnable]
      ,[Temp1LoLimit]
      ,[Temp2Enable]
      ,[Temp2Location]
      ,[Temp2HiEnable]
      ,[Temp2HiLimit]
      ,[Temp2LoEnable]
      ,[Temp2LoLimit]
      ,[Temp3Enable]
      ,[Temp3Location]
      ,[Temp3HiEnable]
      ,[Temp3HiLimit]
      ,[Temp3LoEnable]
      ,[Temp3LoLimit]
      ,[Temp4Enable]
      ,[Temp4Location]
      ,[Temp4HiEnable]
      ,[Temp4HiLimit]
      ,[Temp4LoEnable]
      ,[Temp4LoLimit]
      ,[Report]
      ,[Alarm]
      ,[VoltageProtect]
      ,[AlarmDelay]
      ,[CalibrationSpeed]
      ,[TorqueSensitivity]
      ,[Department]
  FROM [CTTST2].[Substation].[dbo].[TestList]
';

        $ret = $DB->query($query)->result_array();


        $vlrins = 0;
        $vlrupd = 0;
        foreach ($ret as $key => $value) {

            $StartTestTime = $value['StartTestTime'];
            $EndTestTime = $value['EndTestTime'];
            $ControllerNo = $value['ControllerNo'];
            $currentstatus = $value['CurrentStatus'];
            $testresult = $value['TestResult'];
            $currentcycle = $value['CurrentCycle'];




            $sql = "select recid, currentcycle "
                    . "FROM \"COLLECT_BRAKE_LIFE\" WHERE controllerno = '$ControllerNo' "
                    . " AND currentstatus = '$currentstatus' "
                    . " AND testresult = '$testresult' "
                    . " AND startTestTime = '$StartTestTime' "
                    . " AND recid = (select max(a.recid) from public.\"COLLECT_BRAKE_LIFE\" a WHERE a.controllerno = \"COLLECT_BRAKE_LIFE\".controllerno )"
                    . "ORDER by recid DESC LIMIT 1";
            $here = $this->getCdbhelper()->basicSQLArray($sql);

            if (count($here) == 0) {
                $upd = array_change_key_case($value);

                $this->db->reset_query();
                $this->db->set($upd);
                $this->db->insert('COLLECT_BRAKE_LIFE');
                $vlrins ++;
            } else {
                $actual = $here[0]['currentcycle'];

                if ($actual != $currentcycle) {

                    $this->db->reset_query();
                    $this->db->set('currentcycle', $currentcycle);
                    $this->db->where('recid', $here[0]['recid']);
                    $this->db->update('COLLECT_BRAKE_LIFE');
                    $vlrupd ++;
                }
            }
        }

        $this->db->query('REFRESH MATERIALIZED VIEW CONCURRENTLY "VIEW_MTE_WO_SAMPLE"');

        echo(json_encode(array('inserted' => $vlrins, 'updated' => $vlrupd)));
    }

    public function loadWareHouseData() {

        ini_set('memory_limit', '-1');
        ini_set('sqlsrv.ClientBufferMaxKBSize', '2524288'); // Setting to 512M
        ini_set('pdo_sqlsrv.client_buffer_max_kb_size', '2524288'); // Setting to 512M - for pdo_sqlsrv

        $this->load->model("rfq/equipment_design_model", "eqmodel", TRUE);
        $this->load->model("rfq/equipment_design_sub_category_model", "subcatmodel", TRUE);

        $lastTimestamp = $this->getCdbhelper()->getTableLastTimeStamp('MAS_Material');


        $DB = $this->load->database('tr', true);
        $ret = $DB->query("select top 4000 PartNumber, MAX(CAST (DataTimeStamp as bigint)) as DataTimeStamp, min(FullDesc) as FullDesc, min(Remark) as Remark, max(CreateDate) as CreateDate from [dbo].[MAS_Material] where PartNumber like '992%' AND CAST (DataTimeStamp as bigint) > $lastTimestamp GROUP BY PartNumber ORDER BY CreateDate desc")->result_array();
        $array_to_add = array();
        $errorMsg = '';
        $count = 0;
        $checkdup = array();
        foreach ($ret as $key => $value) {

            IF ($value['DataTimeStamp'] > $lastTimestamp) {
                $lastTimestamp = $value['DataTimeStamp'];
            }

            $sub = substr($value['PartNumber'], 0, 6);
            $series = substr($value['PartNumber'], -3);
            $desc = $value['FullDesc'];
            $remark = $value['Remark'];

            $subarray = $this->subcatmodel->retRetrieveGridArray(" WHERE \"EQUIPMENT_DESIGN_SUB_CATEGORY\".ds_name_code = '$sub'");

            if (count($subarray) == 0) {
                $errorMsg = $errorMsg . "<br>Error: Sub Catetory does not exists for $sub - $desc";
                continue;
            }

            $x = $subarray[0]['recid'] . '-' . $series;

            if (array_search($x, $checkdup) !== FALSE) {
                continue;
            }


            array_push($checkdup, $x);


            $equi = $this->eqmodel->retRetrieveGridArray(" WHERE \"EQUIPMENT_DESIGN\".cd_equipment_design_sub_category = '" . $subarray[0]['recid'] . "' AND \"EQUIPMENT_DESIGN\".nr_series = $series");

            // already exist, so skip
            if (count($equi) != 0) {
                continue;
            }

            $count ++;

            if ($count > 400) {
                break;
            }

            array_push($array_to_add, array(
                'recid' => $this->eqmodel->getNextCode(),
                'cd_equipment_design_sub_category' => $subarray[0]['recid'],
                'ds_equipment_design' => $desc,
                'ds_remarks' => $remark,
                'nr_series' => $series
            ));
        }


        $errordb = $this->eqmodel->updateGridData($array_to_add);
        if ($errordb != "OK") {
            $error = $errordb;
        } else {
            $error = 'Done - Added ' . count($array_to_add) . ' - Last TimeStamp: ' . $lastTimestamp;
        }


        if ($errorMsg != '') {
            $error = $error . ' with messages. See Below:' . $errorMsg;
        }

        $this->getCdbhelper()->setTableLastTimeStamp('MAS_Material', $lastTimestamp);


        $msg = '{"status":' . json_encode($error) . ', "rs":{} }';

        echo($msg);
    }

    public function importTRWOData($type) {
        $this->load->model("tr/tr_mssql_model", "trmodel", TRUE);
        ini_set('memory_limit', '-1');
        ini_set('sqlsrv.ClientBufferMaxKBSize', '1524288'); // Setting to 512M
        ini_set('pdo_sqlsrv.client_buffer_max_kb_size', '1524288'); // Setting to 512M - for pdo_sqlsrv



        $this->trmodel->importWorkOrderFromTR($type);
    }

    public function importWiData() {
        $this->load->model("tr/tr_wi_model", "trwimodel", TRUE);
        ini_set('memory_limit', '-1');
        ini_set('sqlsrv.ClientBufferMaxKBSize', '1524288'); // Setting to 512M
        ini_set('pdo_sqlsrv.client_buffer_max_kb_size', '1524288'); // Setting to 512M - for pdo_sqlsrv

        $this->trwimodel->importWiFromTR();
    }

    public function importMTEWOData() {
        $this->load->model("tr/mte_mssql_model", "mtemodel", TRUE);
        ini_set('memory_limit', '-1');
        ini_set('sqlsrv.ClientBufferMaxKBSize', '1524288'); // Setting to 512M
        ini_set('pdo_sqlsrv.client_buffer_max_kb_size', '1524288'); // Setting to 512M - for pdo_sqlsrv

        $this->mtemodel->importWorkOrderFromMTE();
        
        $this->db->query('REFRESH MATERIALIZED VIEW CONCURRENTLY tr."VIEW_MTE_WO_SAMPLE"');
        
    }

    public function importMTRReportData() {
        $this->load->model("tr/mtr_report_model", "reportmodel", TRUE);
        ini_set('memory_limit', '-1');
        $this->reportmodel->importMTRReportData();

    }

    public function importDataFromOracle() {
        ini_set('memory_limit', '-1');
        $this->load->model("oms/onekey_model", "onemodel", TRUE);
        $this->onemodel->importDataFromOracle();
    }

    public function importDataFromULBS() {
        ini_set('memory_limit', '-1');
        
        $lastId = $this->getCdbhelper()->getTableLastTimeStamp("ULBS_ID");
        
        $this->load->model("ulbs_model", "ulbsmodel", TRUE);
        $this->load->model("ulbs_events_model", "eventmodel", TRUE);
        $this->ulbsmodel->importDataFromMysql();
        $this->eventmodel->importDataFromMysql();

        $sql = "select public.ulbsSummary($lastId)";
        $this->getCdbhelper()->basicSQLNoReturn($sql);
        
        
        

    }

    public function importDataFromFaceID() {
        $this->load->model("tti/face_scanner_record_model", "facemodel", TRUE);

        echo (json_encode($this->facemodel->importFID()));
    }

    public function tempImportTraining() {
        /*
          $this->load->model("training/course_model", "coursemodel", TRUE);

          $query = "select a.CourseNo, a.CourseName
          from [UseTest2].[dbo].[AT_TrainRecord] a,
          (
          select CourseNo, max(CreateDate) as CreateDate
          FROM [UseTest2].[dbo].[AT_TrainRecord]
          where COALESCE(CourseNo, '') NOT IN ( 'null', '')
          group by CourseNo ) d
          WHERE d.CourseNo = a.CourseNo AND d.CreateDate = a.CreateDate;";

          $DB = $this->load->database('tr', true);
          $ret = $DB->query($query)->result_array();


          $updArray = array();

          foreach ($ret as $key => $value) {
          array_push($updArray, array(
          'recid' => $this->coursemodel->getNextCode(),
          'cd_course_category' => 14,
          'ds_course_number' => $value['CourseNo'],
          'ds_course'        => $value['CourseName'],
          'cd_course_status_material' => 4,
          'cd_course_status' => 4
          ) );
          }

          //die('<pre>'.print_r($updArray) .'</pre>');

          $this->coursemodel->updateGridData($updArray);
         * */
    }

    public function sendPRToElastic() {
        require_once APPPATH . 'elasticsearch/vendor/autoload.php';
        $hostIP = $this->getCdbhelper()->getSystemParameters('ELASTICSEARCH_SERVER');
        $hosts = ["http://$hostIP:9200"];


        $client = Elasticsearch\ClientBuilder::create()           // Instantiate a new ClientBuilder
                ->setHosts($hosts)      // Set the hosts
                ->build();              // Build the client object

        $this->load->model('rfq/rfq_pr_group_distribution_model', 'distmodel', true);


        $ret = $this->distmodel->retRetrieveGridJson('', '', '', $this->distmodel->retrOptionsElastic);
        $ret = json_decode($ret, true);
        


        $newArray = array('body' => array());
        foreach ($ret as $key => $value) {
            array_push($newArray['body'], array("index" => array("_index" => "purprdata", "_type" => "prdata", "_id" => $value['recid'])));
            array_push($newArray['body'], $value);
        }

        //die (print_r($newArray));

        $params = ['index' => 'purprdata'];
        if ($client->indices()->exists($params)) {
            $response = $client->indices()->delete($params);
        }
        
        $responses = $client->bulk($newArray);

        echo (print_r(count($newArray['body'])));
    }

    public function sendMTEUSsageToElastic() {
        /* get the last time we ran the process */
        $lastTimestamp = $this->getCdbhelper()->getTableLastTimeStamp('ELK_MTE_Usage');
        // load ElasticSearch Classes
        require_once APPPATH . 'elasticsearch/vendor/autoload.php';
        // host
        $hostIP = $this->getCdbhelper()->getSystemParameters('ELASTICSEARCH_SERVER');
        $hosts = ["http://$hostIP:9200"];

        $indexname = 'mteusage';
        // connect
        $client = Elasticsearch\ClientBuilder::create()           // Instantiate a new ClientBuilder
                ->setHosts($hosts)      // Set the hosts
                ->build();              // Build the client object



        $checkIndex = ['index' => $indexname];

        if (!$client->indices()->exists($checkIndex)) {
            // Example Index Mapping
            // Index Settings
            $indexParams['index'] = 'mteusage';
            $indexParams['body']['settings']['number_of_shards'] = 1;
            $indexParams['body']['settings']['number_of_replicas'] = 0;

            $myTypeMapping = array(
                '_source' => array(
                    'enabled' => true
                ),
                'properties' => array(
                    'Creation Date' => array(
                        'type' => 'date',
                        'format' => 'yyyy-MM-dd',
                        "ignore_malformed" => true
                    ),
                    'Start Time' => array(
                        'type' => 'date',
                        'format' => 'yyyy-MM-dd HH:mm',
                        "ignore_malformed" => true
                    ),
                    'End Time' => array(
                        'type' => 'date',
                        'format' => 'yyyy-MM-dd HH:mm',
                        "ignore_malformed" => true
                    ),
                    'Creation Time' => array(
                        'type' => 'date',
                        'format' => 'yyyy-MM-dd HH:mm',
                        "ignore_malformed" => true
                    ),
                    // from here add in 12/26
                    'Request Date' => array(
                        'type' => 'date',
                        'format' => 'yyyy-MM-dd HH:mm:ss',
                        "ignore_malformed" => true
                    ),
                    'Goal' => array(
                        'type' => 'float',
                        "ignore_malformed" => true
                    ),
                    'Sample QTY' => array(
                        'type' => 'float',
                        "ignore_malformed" => true
                    ),
                    'Charget QTY' => array(
                        'type' => 'float',
                        "ignore_malformed" => true
                    ),
                    'PP QTY' => array(
                        'type' => 'float',
                        "ignore_malformed" => true
                    ),
                    'Accessory QTY' => array(
                        'type' => 'float',
                        "ignore_malformed" => true
                    ),
                    // End here add in 12/26
                    'Worker Time(hr)' => array(
                        'type' => 'float',
                        "ignore_malformed" => true
                    ),
                    'System Time(s)' => array(
                        'type' => 'float',
                        "ignore_malformed" => true
                    ),
                    'Time Difference' => array(
                        'type' => 'float',
                        "ignore_malformed" => true
                    ),
                    'Time on Saving' => array(
                        'type' => 'float',
                        "ignore_malformed" => true
                    ),
                    'Applications' => array(
                        'type' => 'float',
                        "ignore_malformed" => true
                    ),
                    'Comp. Cycles' => array(
                        'type' => 'float',
                        "ignore_malformed" => true
                    ),
                    'Comp. Applications' => array(
                        'type' => 'float',
                        "ignore_malformed" => true
                    ),
                    'Comp. discharges' => array(
                        'type' => 'float',
                        "ignore_malformed" => true
                    ),
                    'Worker ID' => array(
                        'type' => 'integer',
                        "ignore_malformed" => true
                    )
                )
            );
            $indexParams['body']['mappings']['mteusage'] = $myTypeMapping;

// Create the index
            $client->indices()->create($indexParams);
        };



        //die($lastTimestamp);
        // Set initial parameters for Database and PHP
        $DB = $this->load->database('mte', true);
        ini_set('memory_limit', '-1');
        ini_set('sqlsrv.ClientBufferMaxKBSize', '22524288'); // Setting to 512M
        ini_set('pdo_sqlsrv.client_buffer_max_kb_size', '22524288'); // Setting to 512M - for pdo_sqlsrv
        // connect to database
        // create the query (staring from the $lastTimestamp);
        $query = '
            select top 50000  r.id, format(r.Creation_date,\'yyyy-MM-dd\') "Creation Date",
                      case when r.Creation_date between convert(varchar(10),r.Creation_date,120) + \' 07:00:00\' and convert(varchar(10),r.Creation_date,120) + \' 19:55:59\'
                           then \'D\' else \'N\' end "Shift",
                                    wi.TTI_Project_no [TTI Project # ],wi.TR_MET_Project_No [Mil Project #],wi.Customer_Model_no [Mil Model],
            wi.TRBrandProjectDesc [Mil Project Desc.],  
            wi.TR_no [TR #],se.F_RealName "Test Engineer",wi.TR_Apply_date [Request Date],wi.TR_Apply_by [Requestor],wi.WO_Goal [Goal],bu.unit_code [Unit],wi.EOL,wi.WO_Sample_Quantity [Sample QTY],
            wi.WO_Charger_Quantity [Charget QTY],wi.WO_PowerPack_Quantity [PP QTY],wi.WO_Accessory_Quantity [Accessory QTY],wif.flow_code [Workflow],wi.TR_Witness [Witness],
            wi.TR_Urgent [Urgent],r.tool_id [Sample ID],
            btp.TestPhase_name[Test Phase ],btt.TestType_name[Test Type],
            wo.WO_code "WO#",wi.TR_Tool_Type"Sample Type",WI.TRBrandProjectDesc"Sample Desc.",WTT.Type_Description"WO Type",BM.model_code"Sample Model",
            format(r.Start_time, \'yyyy-MM-dd HH:mm\') "Start Time",
            format(r.End_time, \'yyyy-MM-dd HH:mm\') "End Time",

            format(r.Creation_date, \'yyyy-MM-dd HH:mm\') "Creation Time",
            wo.WO_code + \'-\' + WT.Tool_code "Sample ID",
            r.Time/3600.00  "Worker Time(hr)",DATEDIFF(ss, r.Start_time,r.End_time) "System Time(s)", 
            r.time -DATEDIFF(ss, r.Start_time,r.End_time) "Time Difference",DATEDIFF(ss,r.End_time, r.Creation_date)"Time on Saving",r.[application]"Applications",R.Completed_Cycle"Comp. Cycles",R.Completed_Application"Comp. Applications",R.Completed_Discharge"Comp. discharges", WT.Tool_code"Sample#",su.F_Account"Worker ID",su.F_RealName "Worker",ss.F_RealName "Modifier",r.Confirm_Leader_Remark"Modify Remarks"
            ,BC.Computer_Name"Computer Code",br.Room_Code"Test Rooms"
            from WO_DataForm_Test_Lab_Report r
            Inner join WO_information wi on r.WO_id = wi.id
            left join WO_information wo on r.WO_ID=wo.ID
            left join Sys_User su on r.Created_by=su.F_Id
            left join Basic_Computer BC on r.Computer_id=BC.id
            left join WO_Tool WT on r.Tool_id=WT.ID
            left join WO_Room_Assignment wra on wt.ID=wra.Tool_ID
            left join Basic_Room br on wra.Room_ID=br.ID
            left join Basic_Purpose bpu on bpu.ID = wi.Pursepose_id 
            left join Basic_Priority bp on wi.Priority = bp.id
            left join Basic_TestType btt on wi.TestType_id = btt.ID
            left join Basic_TestPhase btp on wi.TestPhase_id = btp.ID
            left join Sys_User ss on r.Confirm_Leader_id=ss.F_ID
            left join WO_TestType WTT ON WI.WO_TestType_id=WTT.ID
            left join Basic_Model BM ON WI.WO_Model_id=BM.ID   
            left join Sys_User se on wi.TR_Responsible_Worker_id=se.F_Id
            left join WO_Status_Value wos on wi.WO_Status=wos.id
            left join WO_Status_Value wosv on wt.WO_Status=wosv.id
            left join Basic_Unit bu on wi.WO_Goal_Unite_ID=bu.ID
            left join WI_workflow wif on wi.workflow_id=wif.id

    where r.id >= ' . $lastTimestamp . ' and r.Creation_date >= \'2018-01-01\' 
    order by r.id';

        // run Query and return Array
        $ret = $DB->query($query)->result_array();


        // Create new array to ElasticSearch
        $newArray = array('body' => array());

        // loop into my query's array
        foreach ($ret as $key => $value) {
            // add bulk data do send to elasticsearch
            array_push($newArray['body'], array("index" => array("_index" => $indexname, "_type" => $indexname, "_id" => $value['id'])));
            array_push($newArray['body'], $value);

            //$client->update($newArray);
            // save the timestamp
            $lastTimestamp = $value['id'];
        }

        $responses = $client->bulk($newArray);

        $this->getCdbhelper()->setTableLastTimeStamp('ELK_MTE_Usage', $lastTimestamp);

        //echo (print_r($responses));
        echo('done exporting ' . count($ret));
    }

    public function importAD() {

        $this->load->model('human_resource_model', 'newmodel', TRUE);

        $ret = $this->newmodel->importAD();
        echo(json_encode($ret));
        return;
    }

    public function sendMTEUSsagefailureToElastic() {
        // http://192.168.56.101/index.php/operator/sendMTEUSsagefailureToElastic?key=eae3ba3d23a0bb588f40ae538f66da36
        /* get the last time we ran the process */
        /*  $lastTimestamp = $this->getCdbhelper()->getTableLastTimeStamp('ELK_MTE_Usage'); */
        // load ElasticSearch Classes
        require_once APPPATH . 'elasticsearch/vendor/autoload.php';
        // host
        $hostIP = $this->getCdbhelper()->getSystemParameters('ELASTICSEARCH_SERVER');
        $hosts = ["http://$hostIP:9200"];

        $indexname = 'failureofmteusage';
        // connect
        $client = Elasticsearch\ClientBuilder::create()           // Instantiate a new ClientBuilder
                ->setHosts($hosts)      // Set the hosts
                ->build();              // Build the client object



        $checkIndex = ['index' => $indexname];

        if (!$client->indices()->exists($checkIndex)) {
            // Example Index Mapping
            // Index Settings
            $indexParams['index'] = $indexname;
            $indexParams['body']['settings']['number_of_shards'] = 1;
            $indexParams['body']['settings']['number_of_replicas'] = 0;

            $myTypeMapping = array(
                '_source' => array(
                    'enabled' => true
                ),
                'properties' => array(
                    'Creation Date' => array(
                        'type' => 'date',
                        'format' => 'yyyy-MM-dd',
                        "ignore_malformed" => true
                    ),
                    'Request Date' => array(
                        'type' => 'date',
                        'format' => 'yyyy-MM-dd HH:mm',
                        "ignore_malformed" => true
                    ),
                    'Failure Date' => array(
                        'type' => 'date',
                        'format' => 'yyyy-MM-dd HH:mm:SS',
                        "ignore_malformed" => true
                    ),
                    'Goal' => array(
                        'type' => 'float',
                        "ignore_malformed" => true
                    ),
                    'Accessory QTY' => array(
                        'type' => 'float',
                        "ignore_malformed" => true
                    ),
                    'Step/Item Applications' => array(
                        'type' => 'float',
                        "ignore_malformed" => true
                    ),
                    'Step/Item Applications' => array(
                        'type' => 'float',
                        "ignore_malformed" => true
                    ),
                    'Comp. Cycles' => array(
                        'type' => 'float',
                        "ignore_malformed" => true
                    ),
                    'Comp. Applications' => array(
                        'type' => 'float',
                        "ignore_malformed" => true
                    ),
                    'Comp. Discharges' => array(
                        'type' => 'float',
                        "ignore_malformed" => true
                    ),
                    'Comp. Runtime' => array(
                        'type' => 'float',
                        "ignore_malformed" => true
                    ),
                    'Worker ID' => array(
                        'type' => 'integer',
                        "ignore_malformed" => true
                    )
                )
            );
            $indexParams['body']['mappings'][$indexname] = $myTypeMapping;

// Create the index
            $client->indices()->create($indexParams);
        };

        //die($lastTimestamp);
        // Set initial parameters for Database and PHP
        $DB = $this->load->database('mte', true);
        ini_set('memory_limit', '-1');
        ini_set('sqlsrv.ClientBufferMaxKBSize', '22524288'); // Setting to 512M
        ini_set('pdo_sqlsrv.client_buffer_max_kb_size', '22524288'); // Setting to 512M - for pdo_sqlsrv
        // connect to database
        // create the query (staring from the $lastTimestamp);
        $query = '
            select  convert(varchar(10),f.Creation_date,120) "Creation Date",
          case when f.Creation_date between convert(varchar(10),f.Creation_date,120) + \' 07:00:00\' and convert(varchar(10),f.Creation_date,120) + \' 19:55:59\'
               then \'D\' else \'N\' end "Shift",
		wi.TTI_Project_no [Project #],
		wi.TR_MET_Project_No [Mil Project #],
		wi.Customer_Model_no [Mil Model],
		BM.model_code"Sample Model",
		wi.TRBrandProjectDesc [Mil Project Desc.],
		wi.TR_no [TR #],
		se.F_RealName "Test Engineer",
		wi.TR_Apply_date [Request Date],
		wi.TR_Apply_by [Requestor],
		btp.TestPhase_name[Test Phase],
		btt.TestType_name[Test Type],
		wi.TR_Tool_Type"Sample Type",
		WI.TRBrandProjectDesc"Sample Desc.",
		WTT.Type_Description"WO Type",
		wo.WO_code "WO#",
		wi.WO_Goal [Goal],
		bu.unit_code [Unit],
		wi.EOL,
		wif.flow_code [Workflow],
		wi.TR_Witness [Witness],
		wi.TR_Urgent [Urgent]
      ,f.Failure_No   [Failure ID]
      ,f.Failure_date [Failure Date]
	  ,WT.Tool_code [Sample#]
	  ,f.Tool_id [Sample ID]
      ,f.Failure_description [Failure Description]
      ,f.Before_Failure_decription [ Prior to Failure]
      ,f.Failure_Varification  [ Failure Varification]
      ,f.Completed_Cycle [ Comp. Cycles]
      ,f.Completed_Application [ Comp. Applications]
      ,f.Completed_Discharge  [ Comp. Discharges]
      ,f.Completed_Time/3600 [ Comp. Runtime]
	  ,ps.Power_Supply_name   [ AC Power Supply]
	  ,bcl.Battery_Charge_Level [ Batt. Charge Level]
	  ,brc.Battery_Rated_Capacity_name [ Batt. Capacity]
	  ,brv.Battery_Rate_Voltage [Batt. Voltage]
      ,f.Forward_Reverse [Forward/Reverse]
      ,f.Trigger_status [Trigger]
	  ,wit.Item_code [Name of failed step/item]
	  ,f.Item_Completed_Quantity [ Step/Item Applications]
      ,wit.Item_description [ Step/Item Desc.]
	  ,f.TestItemDescription
	  ,wif.flow_description,

su.F_Account"Worker ID",
su.F_RealName "Worker",
ss.F_RealName "Roomleader",
wos.Status_Code "WO Status",
wosv.Status_Code "Sample Status",
BC.Computer_Name"Computer Code",
br.Room_Code"Test Rooms"

from WO_DataForm_Test_Failure f
Inner join WO_information wi on f.WO_id = wi.id
left join WO_information wo on f.WO_ID=wo.id
left join Sys_User su on f.Created_by=su.F_Id
left join Basic_Computer BC on f.Computer_id=BC.id
left join WO_Tool WT on f.Tool_id=WT.ID
left join WO_Room_Assignment wra on wt.ID=wra.Tool_ID
left join Basic_Room br on wra.Room_ID=br.ID
left join Basic_Purpose bpu on bpu.ID = wi.Pursepose_id 
left join Basic_Priority bp on wi.Priority = bp.id
left join Basic_TestType btt on wi.TestType_id = btt.ID
left join Basic_TestPhase btp on wi.TestPhase_id = btp.ID
left join Sys_User ss on f.RoomLeader_id=ss.F_ID
left join WO_TestType WTT ON WI.WO_TestType_id=WTT.ID
left join Basic_Model BM ON WI.WO_Model_id=BM.ID
left join Sys_User se on wi.TR_Responsible_Worker_id=se.F_Id
left join WO_Status_Value wos on wi.WO_Status=wos.id
left join WO_Status_Value wosv on wt.WO_Status=wosv.id
left join Basic_Unit bu on wi.WO_Goal_Unite_ID=bu.ID
left join WI_workflow wif on wi.workflow_id=wif.id
left join Basic_Power_Supply_Mode ps on f.Power_Supply_Mode_id=ps.ID
left join Basic_Battery_Charge_Level bcl on f.Charge_Level_id=bcl.id
left join Basic_Battery_Rated_Capacity brc on f.Rated_Capacity_id=brc.id
left join Basic_Battery_Rate_Voltage brv on f.Rate_Voltage_id=brv.id  
left join WI_Item wit on f.item_id=wit.id


where f.Creation_Date>= getdate()-365   
    order by f.id';

        // run Query and return Array
        $ret = $DB->query($query)->result_array();


        // Create new array to ElasticSearch
        $newArray = array('body' => array());

        // loop into my query's array
        foreach ($ret as $key => $value) {
            // add bulk data do send to elasticsearch
            array_push($newArray['body'], array("index" => array("_index" => $indexname, "_type" => $indexname, "_id" => $value['Failure ID'])));
            array_push($newArray['body'], $value);
        }

        $responses = $client->bulk($newArray);
        echo('done exporting ' . count($ret));
    }

    public function sendTRToElastic() {
        // http://192.168.56.101/index.php/operator/sendMTEUSsagefailureToElastic?key=eae3ba3d23a0bb588f40ae538f66da36
        /* get the last time we ran the process */
        /*  $lastTimestamp = $this->getCdbhelper()->getTableLastTimeStamp('ELK_MTE_Usage'); */
        // load ElasticSearch Classes
        require_once APPPATH . 'elasticsearch/vendor/autoload.php';
        // host
        $hostIP = $this->getCdbhelper()->getSystemParameters('ELASTICSEARCH_SERVER');
        $hosts = ["http://$hostIP:9200"];

        $indexname = 'trrequestdata';
        // connect
        $client = Elasticsearch\ClientBuilder::create()           // Instantiate a new ClientBuilder
                ->setHosts($hosts)      // Set the hosts
                ->build();              // Build the client object



        $checkIndex = ['index' => $indexname];

        if (!$client->indices()->exists($checkIndex)) {
            // Example Index Mapping
            // Index Settings
            $indexParams['index'] = $indexname;
            $indexParams['body']['settings']['number_of_shards'] = 1;
            $indexParams['body']['settings']['number_of_replicas'] = 0;

            $myTypeMapping = array(
                '_source' => array(
                    'enabled' => true
                ),
                'properties' => array(
                    'WO qty' => array(
                        'type' => 'integer',
                        "ignore_malformed" => true
                    ),
                    'Wo Tool Qty' => array(
                        'type' => 'integer',
                        "ignore_malformed" => true
                    ),
                    'Wo PP Qty' => array(
                        'type' => 'integer',
                        "ignore_malformed" => true
                    ),
                    'Wo Charger Qty' => array(
                        'type' => 'integer',
                        "ignore_malformed" => true
                    ),
                    'Wo Accssory Qty' => array(
                        'type' => 'integer',
                        "ignore_malformed" => true
                    ),
                    'WO qty' => array(
                        'type' => 'integer',
                        "ignore_malformed" => true
                    ),
                    'Wo sample qty' => array(
                        'type' => 'integer',
                        "ignore_malformed" => true
                    ),
                    'TR sample qty' => array(
                        'type' => 'integer',
                        "ignore_malformed" => true
                    )
                )
            );
            $indexParams['body']['mappings'][$indexname] = $myTypeMapping;

// Create the index
            $client->indices()->create($indexParams);
        };

        //die($lastTimestamp);
        // Set initial parameters for Database and PHP
        $DB = $this->load->database('trone', true);
        ini_set('memory_limit', '-1');
        ini_set('sqlsrv.ClientBufferMaxKBSize', '22524288'); // Setting to 512M
        ini_set('pdo_sqlsrv.client_buffer_max_kb_size', '22524288'); // Setting to 512M - for pdo_sqlsrv
        // connect to database
        // create the query (staring from the $lastTimestamp);
        $query = 'SELECT vtrn.*
                ,p.TRResponsible [TE]
		,P.[WO qty] [WO qty]
		,P.[Wo Tool Qty] [Wo Tool Qty]
		,p.[Wo PP Qty] [Wo PP Qty]
		,p.[Wo Charger Qty][Wo Charger Qty]
		,p.[Wo Accssory Qty][Wo Accssory Qty]
		,p.[Wo sample qty][Wo sample qty]
		,case when p.ToolType=\'Battery\' then vtrn.PPQuantities 
			   when p.ToolType=\'Charger\' then vtrn.ChargerQuantities 
			   when p.ToolType=\'Accessory\' then vtrn.AccessoryQuantities 
			   else vtrn.ToolQuantities end as [TR sample qty]
		FROM dbo.ViewTRNew vtrn
		left join (
				  select 
				  TRNumber
                                  ,TRResponsible
				  ,ToolType
				  ,count(distinct WorkOrderID )  as [WO qty]
				  ,sum(vtwo.ToolQuantities) as [Wo Tool Qty]
				  ,sum(vtwo.PPQuantities) as [Wo PP Qty]
				  ,sum(vtwo.ChargerQuantities) as [Wo Charger Qty]
				  ,sum(vtwo.AccessoryQuantities) as [Wo Accssory Qty]
				  ,sum(case 
						  when tooltype=\'Battery\' then vtwo.PPQuantities 
						  when tooltype=\'Charger\' then vtwo.ChargerQuantities 
						  when tooltype=\'Accessory\' then vtwo.AccessoryQuantities 
						  else ToolQuantities end) as [wo sample qty]
				  from dbo.ViewTRTechnicianWO vtwo 
				  where vtwo.Brand like \'%mil%\' or vtwo.Brand is null  or vtwo.brand=\'Empire\' group by vtwo.TRNumber,vtwo.ToolType,vtwo.TRResponsible ) p
	   on ( vtrn.TRNumber =p.TRNumber )
           where p.ToolType is not null and vtrn.RequestDate>\'2014-01-01\' ';


        // run Query and return Array
        $ret = $DB->query($query)->result_array();


        // Create new array to ElasticSearch
        $newArray = array('body' => array());

        // loop into my query's array
        foreach ($ret as $key => $value) {
            // add bulk data do send to elasticsearch
            array_push($newArray['body'], array("index" => array("_index" => $indexname, "_type" => $indexname, "_id" => $value['TRNumber'])));
            array_push($newArray['body'], $value);
        }

        $responses = $client->bulk($newArray);
        echo('done exporting ' . count($ret));
    }

    public function sendQuotationTOELK() {
        // http://192.168.56.101/index.php/operator/sendMTEUSsagefailureToElastic?key=eae3ba3d23a0bb588f40ae538f66da36
        /* get the last time we ran the process */
        /*  $lastTimestamp = $this->getCdbhelper()->getTableLastTimeStamp('ELK_MTE_Usage'); */
        // load ElasticSearch Classes
        require_once APPPATH . 'elasticsearch/vendor/autoload.php';
        // host
        $hostIP = $this->getCdbhelper()->getSystemParameters('ELASTICSEARCH_SERVER');
        $hosts = ["http://$hostIP:9200"];

        $indexname = 'purquotationdata';

        $myTypeMapping = array(
            '_source' => array(
                'enabled' => true
            ),
            'properties' => array(
                'Requested Date' => array(
                    'type' => 'date',
                    'format' => 'MM/dd/yyyy',
                    "ignore_malformed" => true
                ),
                'Purchase Deadline' => array(
                    'type' => 'date',
                    'format' => 'MM/dd/yyyy',
                    "ignore_malformed" => true
                ),
                'Item Deadline' => array(
                    'type' => 'date',
                    'format' => 'MM/dd/yyyy',
                    "ignore_malformed" => true
                ),
                'Quotation Expiring Date' => array(
                    'type' => 'date',
                    'format' => 'MM/dd/yyyy',
                    "ignore_malformed" => true
                ),
                'Release to Approve' => array(
                    'type' => 'date',
                    'format' => 'MM/dd/yyyy HH:mm',
                    "ignore_malformed" => true
                ),
                'Team Approval' => array(
                    'type' => 'date',
                    'format' => 'MM/dd/yyyy HH:mm',
                    "ignore_malformed" => true
                ),
                'Release Purchase Request' => array(
                    'type' => 'date',
                    'format' => 'MM/dd/yyyy HH:mm',
                    "ignore_malformed" => true
                ),
                'Release to Team Approver' => array(
                    'type' => 'date',
                    'format' => 'MM/dd/yyyy HH:mm',
                    "ignore_malformed" => true
                ),
                'Team Approver Check Suppliers' => array(
                    'type' => 'date',
                    'format' => 'MM/dd/yyyy HH:mm',
                    "ignore_malformed" => true
                ),
                'Department Manager' => array(
                    'type' => 'date',
                    'format' => 'MM/dd/yyyy HH:mm',
                    "ignore_malformed" => true
                ),
                'Release to PR/EPOR' => array(
                    'type' => 'date',
                    'format' => 'MM/dd/yyyy HH:mm',
                    "ignore_malformed" => true
                )
            )
        );



        // connect
        $client = Elasticsearch\ClientBuilder::create()           // Instantiate a new ClientBuilder
                ->setHosts($hosts)      // Set the hosts
                ->build();              // Build the client object

        $sql = 'SELECT rfq.ELKQuotation()';
        $this->getCdbhelper()->basicSQLNoReturn($sql);

        $dataJson = $this->getCdbhelper()->basicSQLJson("select * from ELKQuotBasic");
        $dataArray = json_decode($dataJson, true);

        // Create new array to ElasticSearch
        $newArray = array('body' => array());

        // loop into my query's array
        foreach ($dataArray as $key => $value) {
            // add bulk data do send to elasticsearch
            array_push($newArray['body'], array("index" => array("_index" => $indexname, "_type" => $indexname, "_id" => $value['id_rfq_item_supplier_quotation'])));
            array_push($newArray['body'], $value);
        }

        
        $checkIndex = ['index' => $indexname];

        if ($client->indices()->exists($checkIndex)) {
            $response = $client->indices()->delete($checkIndex);
        }

        // drop index and create again with bulk.
        $indexParams['index'] = $indexname;
        $indexParams['body']['settings']['number_of_shards'] = 1;
        $indexParams['body']['settings']['number_of_replicas'] = 0;

        $indexParams['body']['mappings'][$indexname] = $myTypeMapping;

// Create the index
        $client->indices()->create($indexParams);
        
        $responses = $client->bulk($newArray);
        echo('done exporting ' . count($dataArray));

        /*         * ********************** QUERY FOR Approval History  *************************************************** */
        $indexname = 'purquotationapprovaldata';
        $checkIndex = ['index' => $indexname];

        if (!$client->indices()->exists($checkIndex)) {
            // Example Index Mapping
            // Index Settings
            $myTypeMapping['properties']['Date Defined'] = array(
                'type' => 'date',
                'format' => 'MM/dd/yyyy HH:mm',
                "ignore_malformed" => true
            );

            $indexParams['index'] = $indexname;
            $indexParams['body']['settings']['number_of_shards'] = 1;
            $indexParams['body']['settings']['number_of_replicas'] = 0;

            $indexParams['body']['mappings'][$indexname] = $myTypeMapping;

// Create the index
            $client->indices()->create($indexParams);
        };

        // look for the last date on elasticsearch
        $paramsQuery = [
            'index' => $indexname,
            //'type' => $indexname,
            'size' => 0,
            'body' => [
                'aggs' => [
                    'max_date' => [
                        'max' => ['field' => 'Date Defined']
                    ]
                ]
            ]
        ];

        $x = $client->search($paramsQuery);
        
        $date = '01/01/2017 00:01';
        if($x['hits']['total'] != 0) {
            $date = $x['aggregations']['max_date']['value_as_string'];
        }
        $date = $this->getCdbhelper()->dateGridtoDb($date);
        
        //die ($date);
        
        //die ($date);
//value_as_string
        
        $sql = "select * from ELKQuotHist WHERE \"Date Defined\"::timestamp >= '$date'::timestamp ORDER BY \"Date Defined\"::timestamp limit 10000";
        //die($sql);
        $dataJson = $this->getCdbhelper()->basicSQLJson($sql, true);
        $dataArray = json_decode($dataJson, true);


        // Create new array to ElasticSearch
        $newArray = array('body' => array());

        // loop into my query's array
        foreach ($dataArray as $key => $value) {
            // add bulk data do send to elasticsearch
            array_push($newArray['body'], array("index" => array("_index" => $indexname, "_type" => $indexname, "_id" => $value['id_rfq_item_supplier_quotation'])));
            array_push($newArray['body'], $value);
        }

        $responses = $client->bulk($newArray);

        echo('<br>done exporting ' . count($dataArray));
    }

    
      public function sendTRWOToElastic() {
        // http://192.168.56.101/index.php/operator/sendMTEUSsagefailureToElastic?key=eae3ba3d23a0bb588f40ae538f66da36
        /* get the last time we ran the process */
        /*  $lastTimestamp = $this->getCdbhelper()->getTableLastTimeStamp('ELK_MTE_Usage'); */
        // load ElasticSearch Classes
        require_once APPPATH . 'elasticsearch/vendor/autoload.php';
        // host
        $hostIP = $this->getCdbhelper()->getSystemParameters('ELASTICSEARCH_SERVER');
        $hosts = ["http://$hostIP:9200"];

        $indexname = 'trrequestdatawo';
        // connect
        $client = Elasticsearch\ClientBuilder::create()           // Instantiate a new ClientBuilder
                ->setHosts($hosts)      // Set the hosts
                ->build();              // Build the client object



        $checkIndex = ['index' => $indexname];

        if (!$client->indices()->exists($checkIndex)) {
            // Example Index Mapping
            // Index Settings
            $indexParams['index'] = $indexname;
            $indexParams['body']['settings']['number_of_shards'] = 1;
            $indexParams['body']['settings']['number_of_replicas'] = 0;

            
// Create the index
            $client->indices()->create($indexParams);
        };

        //die($lastTimestamp);
        // Set initial parameters for Database and PHP
        $DB = $this->load->database('trone', true);
        ini_set('memory_limit', '-1');
        ini_set('sqlsrv.ClientBufferMaxKBSize', '22524288'); // Setting to 512M
        ini_set('pdo_sqlsrv.client_buffer_max_kb_size', '22524288'); // Setting to 512M - for pdo_sqlsrv
        // connect to database
        // create the query (staring from the $lastTimestamp);
        $query = 'select [ID]
      ,[WOResponsible]
      ,[WOResponsible2]
      ,[TRNumber]
      ,[ProjectNumber]
      ,[TRDraftNumber]
      ,[Requestor]
      ,[PriorityGroup]
      ,[Brand]
      ,[BrandProjectNum]
      ,[BrandModelNum]
      ,[BrandModelDescription]
      ,[BrandProjectDescription]
      ,[MFactory]
      ,[ExpectedCompletionDate]
      ,[TTIModelNumber]
      ,[ToolQuantities]
      ,[ChargerQuantities]
      ,[PPQuantities]
      ,[AccessoryQuantities]
      ,[NumberOfTools]
      ,[RequestDate]
      ,[Urgent]
      ,[TestedSample]
      ,[ReasonForUrgency]
      ,[Volt]
      ,[ACDC]
      ,[ToolType]
      ,[Amps]
      ,[Watt]
      ,[HZ]
      ,[WhereMade]
      ,[SampleProduction]
      ,[TestPhase]
      ,[PurposeofTests]
      ,[SampleDescription]
      ,[AttachmentName]
      ,[Mark]
      ,[RejectReason]
      ,[TRResponsible] as [TE]
      ,[AssignToTechnicianTime]
      ,[TestItem]
      ,[WorkOrderID]
      ,[Status]
      ,[AccessoryDescription]
      ,[WitnessOrNot]
      ,[Goal]
      ,[Eol]
      ,[GoalComments]
      ,[SampleQty]
      ,[AssignToEngineerTime]
  from dbo.ViewTRTechnicianWO vtwo
  where (vtwo.Brand like \'%mil%\' or vtwo.Brand is null  or vtwo.brand=\'Empire\') and  vtwo.RequestDate>\'2014-01-01\'  order by vtwo.ExpectedCompletionDate desc
				 
               ';


        // run Query and return Array
        $ret = $DB->query($query)->result_array();


        // Create new array to ElasticSearch
        $newArray = array('body' => array());

        // loop into my query's array
        foreach ($ret as $key => $value) {
            // add bulk data do send to elasticsearch
            array_push($newArray['body'], array("index" => array("_index" => $indexname, "_type" => $indexname, "_id" => $value['WorkOrderID'])));
            array_push($newArray['body'], $value);
        }

        $responses = $client->bulk($newArray);
        echo('done exporting ' . count($ret));
    }
    
    //////////////////////////////////////////////////////////////////////////
     public function sendTRAToElastic() {
        require_once APPPATH . 'elasticsearch/vendor/autoload.php';
        $hostIP = $this->getCdbhelper()->getSystemParameters('ELASTICSEARCH_SERVER');
        $hosts = ["http://$hostIP:9200"];


        $client = Elasticsearch\ClientBuilder::create()           // Instantiate a new ClientBuilder
                ->setHosts($hosts)      // Set the hosts
                ->build();              // Build the client object

        $this->load->model('training/Course_schedule_model', 'distmodel', true);

        $myTypeMapping = array(
            '_source' => array(
                'enabled' => true
            ),
            'properties' => array(
                      
                'Course Start' => array(
                    'type' => 'date',
                    'format' => 'MM/dd/yyyy HH:mm',
                    "ignore_malformed" => true
                ),
                'Course Finish' => array(
                    'type' => 'date',
                    'format' => 'MM/dd/yyyy HH:mm',
                    "ignore_malformed" => true
                )
            )
        );
             
        $ret = $this->distmodel->retRetrieveGridJson('', '', '', $this->distmodel->retrOptionsElastic);
        $ret = json_decode($ret, true);
       

        $newArray = array('body' => array());
        foreach ($ret as $key => $value) {
            array_push($newArray['body'], array("index" => array("_index" => "trainingdata", "_type" => "prdata", "_id" => $value['recid'])));
            array_push($newArray['body'], $value);
        }

        //die (print_r($newArray));

        $params = ['index' => 'trainingdata'];
        if ($client->indices()->exists($params)) {
            $response = $client->indices()->delete($params);
        }
        
        $responses = $client->bulk($newArray);

        die (print_r(count( $ret)));
    }

    /*
     * When planning is inside a month , and the record date is > than 5 days, send e-mail by Project, advising the Planning Missing.
     */
    public function getMissPlanList()
    {
        $sql='select distinct(cd_project)
                from "PROJECT_BUILD_SCHEDULE"
                where "PROJECT_BUILD_SCHEDULE".dt_est_start > to_date(TO_CHAR(NOW(), \'YYYY-MM\') || \'-01\' , \'yyyy-MM-dd hh24:mi:ss\')
                  and "PROJECT_BUILD_SCHEDULE".dt_est_start  < now()::timestamp + \'-5 day\'
                  and not exists ( select 1 from "PROJECT_BUILD_SCHEDULE_TESTS" x where x.cd_project_build_schedule = "PROJECT_BUILD_SCHEDULE".cd_project_build_schedule)';

        $mailList = $this->getCdbhelper()->basicSQLArray($sql);
    }

    /*
     * Send E-mail by Project when the planning past 3 days, and the WO is not related to the planning.
     */
    public function getMissWOList()
    {
        $sql='';

        $mailList = $this->getCdbhelper()->basicSQLArray($sql);

    }

}
