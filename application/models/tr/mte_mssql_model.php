<?php

include_once APPPATH . "models/modelBasicExtend.php";

class mte_mssql_model extends modelBasicExtend {
    /**
     * @param string $controller
     */
    public function setController(string $controller): void {
        $this->controller = $controller;
    }

    function __construct() {
        parent::__construct();

        $this->DB = $this->load->database('mte', true);

    }

    public function getWorkOrdersStatus($workOrderList) {
        if (count($workOrderList) == 0) {
            return array();
        }
        foreach ($workOrderList as $key => $value) {
            $workOrderList[$key] = "'" . $value . "'";

        }
        $wo = implode(',', $workOrderList);

        $query = "SELECT  wst.Status_Code [WOstatus] ,bp.Priority_Description [Priority] ,wi.TR_MET_Project_No,wi.Customer_Model_no ,wi.TTI_Project_no [Project],bt.TestPhase_name [TestPhase] ,wi.TR_no [TRNo] ,wi.WO_code [Work_Order_ID]  ,
        bpu.purpose_code ,wt.Tool_code [Tool] ,ws.Status_Code [Tool Status] ,wi.WO_Goal [Goal] ,bu.unit_code [Unit] ,ISNULL(wt.Completed_Cycle, 0) [Comp.Cycles] ,
        CONVERT(VARCHAR(10), wt.Completed_Time / 3600) + ':'+ CONVERT(VARCHAR(10), wt.Completed_Time % 3600 / 60) + ':'
        + CONVERT(VARCHAR(10), wt.Completed_Time % 3600 % 60) AS 'Comp. Runtime' ,wt.Completed_Application AS 'Comp.Apps' ,wt.Completed_Discharge AS 'Comp.Discharge',
        convert(varchar(10), wi.Start_date, 101) [startdate] ,convert(varchar(10), wi.EstimatedCompletionDate, 101) [EstCompDate] ,convert(varchar(10), wi.ActualCompletionDate, 101) [ActualCompDate] ,bc.Computer_Name [WorkStation] ,sw.F_RealName [Operator] ,
        sw1.F_RealName [Asst ENG] ,sw2.F_RealName [Test ENG] ,wr.Room_Code [RoomCode] 
         FROM  WO_Tool wt INNER JOIN WO_information wi ON wt.WO_id = wi.ID LEFT JOIN WO_Status_Value ws ON ISNULL(wt.WO_Status, 1) = ws.ID LEFT JOIN Basic_Priority bp ON wi.Priority = bp.ID
         LEFT JOIN Basic_Computer bc ON wt.Computer_id = bc.id LEFT JOIN Sys_User sw ON wt.user_id = sw.F_Id LEFT JOIN Basic_Model bm ON wi.WO_Model_id = bm.ID
         LEFT JOIN Basic_TestPhase bt ON wi.TestPhase_id = bt.ID LEFT JOIN Sys_User sw1 ON wi.WO_Responsible_Worker_id = sw1.F_Id LEFT JOIN Sys_User sw2 ON wi.Technician = sw2.F_Id
         LEFT JOIN Basic_Purpose bpu ON bpu.ID = wi.Pursepose_id LEFT JOIN WO_Status_Value wst ON wst.ID = ISNULL(wi.WO_Status, 1) LEFT JOIN Basic_Unit bu ON bu.ID = wi.WO_Goal_Unite_ID
         LEFT JOIN WO_Room_Assignment wra ON wra.Tool_ID = wt.ID LEFT JOIN Basic_Room wr ON wr.ID = wra.Room_ID where wi.wo_code in ($wo) ORDER BY wi.WO_code ";


        //$query = 'SELECT DISTINCT t.BrandProjectNum as \'MT Project #\',t.BrandModelNum as \'MT Model #\',t.ProjectNumber as \'TTI Project #\',t.TTIModelNumber as \'TTI Model #\',p.Description, t.PriorityGroup as \'Project Type\',(case t.ACDC when 1 then \'AC Power Tools\' when 2 then \'DC Power Tools\' when 3 then \'Hand Tools\' when 4 then \'Charger\' when 5 then \'Battery\' when 6 then \'Accessory\' when 7 then \'Air Tool\' when 8 then \'T&M Tool\' end) as ToolType ,p.StartDate as ProjStartDate FROM UseTest.dbo.TRNew t,Project.dbo.ProjectRecord p  WHERE UPPER(t.Brand ) in (\'MILLWAUKEE\',\'MILWAUKEE\',\'EMPIRE\') AND t.ProjectNumber=p.prjnum   order by p.StartDate desc'  + $where;
//        $query = ''  + $where;
        /* teste */


        $DB = $this->load->database('mte', true);
        $ret = $DB->query($query)->result_array();

        //die (print_r($ret));

        return $ret;
    }

    public function importWorkOrderFromMTE() {
        $this->db->empty_table('MTE_IMP_TMP_WORK_ORDER_RAW');
        $lastTimestampWi = $this->getCdbhelper()->getTableLastTimeStamp("MTE_WOInfo");
        $lastTimestampWt = $this->getCdbhelper()->getTableLastTimeStamp("MTE_WOTool");

        $query = "SELECT top 10000
/**** WO *******************/
wi.ID [ID_WO],wi.WO_code[Work_Order_Code], wi.TR_no [TRNo] ,bt.TestPhase_name [TestPhase] ,bp.Priority_Description [Priority] ,wi.TR_MET_Project_No,wi.TTI_Project_no [Project],
wi.Customer_Model_no as Customer_Model_No ,wst.ID [ID_Status], wst.Status_Code [WOstatus],sw1.F_RealName [Asst_ENG] ,sw2.F_RealName [Test_ENG] ,
bpu.ID as [ID_Purpose],bpu.purpose_code as [Purpose],wi.WO_Goal [Goal] ,bu.unit_code [Unit] ,wi.Start_date [startDate] ,
wi.EstimatedCompletionDate [EstCompDate] ,wi.ActualCompletionDate [ActualCompDate] ,cast(wi.LMSTimeStamp as bigint) as [WO_Timestamp],
/************** Sample Data ***************/
wt.ID as [ID_Tool], wt.Tool_code [Tool] ,bc.id as [ID_WorkStation],bc.Computer_Name [WorkStation] ,ws.Status_Code [Tool_Status],wt.Completed_Application AS 'Comp_Apps' ,
wt.Completed_Discharge AS 'Comp_Discharge',ISNULL(wt.Completed_Cycle, 0) [Comp_Cycles] ,
CONVERT(VARCHAR(10), wt.Completed_Time / 3600) + ':'+ CONVERT(VARCHAR(10), wt.Completed_Time % 3600 / 60) + ':'+ CONVERT(VARCHAR(10), wt.Completed_Time % 3600 % 60) AS 'Comp_Runtime' ,
sw.F_RealName [Operator] ,wr.ID as [ID_Room],wr.Room_Code [RoomCode] ,cast(wt.LMSTimeStamp as bigint) as [Tool_Timestamp]

FROM WO_Tool wt
INNER JOIN WO_information wi ON wt.WO_id = wi.ID
LEFT JOIN WO_Status_Value ws ON ISNULL(wt.WO_Status, 1) = ws.ID 
LEFT JOIN Basic_Priority bp ON wi.Priority = bp.ID
LEFT JOIN Basic_Computer bc ON wt.Computer_id = bc.id 
LEFT JOIN Sys_User sw ON wt.user_id = sw.F_Id
LEFT JOIN Basic_Model bm ON wi.WO_Model_id = bm.ID
LEFT JOIN Basic_TestPhase bt ON wi.TestPhase_id = bt.ID 
LEFT JOIN Sys_User sw1 ON wi.WO_Responsible_Worker_id = sw1.F_Id
LEFT JOIN Sys_User sw2 ON wi.Technician = sw2.F_Id
LEFT JOIN Basic_Purpose bpu ON bpu.ID = wi.Pursepose_id 
LEFT JOIN WO_Status_Value wst ON wst.ID = ISNULL(wi.WO_Status, 1) 
LEFT JOIN Basic_Unit bu ON bu.ID = wi.WO_Goal_Unite_ID
LEFT JOIN WO_Room_Assignment wra ON wra.Tool_ID = wt.ID 
LEFT JOIN Basic_Room wr ON wr.ID = wra.Room_ID where (cast(wi.LMSTimeStamp as bigint)>= $lastTimestampWi or cast(wt.LMSTimeStamp as bigint)>= $lastTimestampWt) 
order by wi.LMSTimeStamp asc, wt.LMSTimeStamp asc";

        $ret = $this->DB->query($query)->result_array();

        foreach ($ret as $value) {
            IF ($value['WO_Timestamp'] > $lastTimestampWi) {
                $lastTimestampWi = $value['WO_Timestamp'];
            }
            IF ($value['Tool_Timestamp'] > $lastTimestampWt) {
                $lastTimestampWt = $value['Tool_Timestamp'];
            }
        }
        $this->db->insert_batch('MTE_IMP_TMP_WORK_ORDER_RAW', $ret);

        $this->getCdbhelper()->setTableLastTimeStamp("MTE_WOInfo", $lastTimestampWi);
        $this->getCdbhelper()->setTableLastTimeStamp("MTE_WOTool", $lastTimestampWt);
        $rows = count($ret);

        $this->getCdbhelper()->basicSQLNoReturn('select tr.importMTEData()');
        echo("Total Imported: $rows");

    }


    public function getWorkerRawDataFromMTE($where = '') {
        //$this->db->empty_table('MTE_IMP_TMP_WORK_ORDER_RAW');
        // $l//astTimestampWi = $this->getCdbhelper()->getTableLastTimeStamp("MTE_WOInfo");
        // $las?/tTimestampWt = $this->getCdbhelper()->getTableLastTimeStamp("MTE_WOTool");

        $query = "SELECT infor.TR_no as 'TR_no',
        infor.TTI_Project_no as 'TTI_Project_no',
              infor.TR_MET_Project_No as [TR_MET_Project_No],
              infor.Customer_Model_no as [Customer_Model_no],
              infor.TR_no as 'TR_no',
              pu.purpose_code as 'Type of Test',
              infor.TR_Apply_by ,
              infor.WO_Goal as 'Goal',
              ut.unit_code as 'Unit' ,
              tp.TestPhase_name as 'TestPhase_name',
              ty.TestType_name as 'TestType_name',
        infor.WO_code AS 'WO_code' ,
        tool.Tool_code AS 'Tool_code' ,
        Battery.Battery_Code AS 'Battery/Accessory',
        report.Completed_Cycle AS 'Completed_Cycle' ,
        report.Battery_Discharge as 'Battery_Discharge',
        report.Completed_Discharge AS 'Completed_Discharge' ,
        CONVERT(VARCHAR(10), report.Completed_Time / 3600) + ':'
        + CONVERT(VARCHAR(10), report.Completed_Time % 3600 / 60) + ':'
        + CONVERT(VARCHAR(10), report.Completed_Time % 3600 % 60) AS 'Completed_Time' ,
              WI_Item.Item_code AS 'Item_code',
        report.Completed_Application AS 'Completed_Application' ,
        CONVERT(VARCHAR(10), report.Time / 3600) + ':'
        + CONVERT(VARCHAR(10), report.Time % 3600 / 60) + ':'
        + CONVERT(VARCHAR(10), report.Time % 3600 % 60) AS 'Time' ,
        report.application AS 'application' ,
        report.Ambient AS 'Ambient' ,
        report.Comment AS 'Comment' ,
        Sys_User.F_RealName AS 'Operator' ,
        report.Start_time AS 'Start_time' ,
        report.End_time AS 'End_time' ,
        report.Creation_date AS 'Creation_date' ,
        bc.Computer_Name as 'Computer_Name',
        report.id, fiv.IV_path,fiv.IV_name_image,fiv.IV_name_video,
        report.id as recid
        
FROM   WO_DataForm_Test_Lab_Report AS report  
       LEFT JOIN WO_Battery  Battery ON Battery.ID = report.Battery_id
          LEFT JOIN WO_information  infor ON infor.ID = report.WO_ID
       LEFT JOIN WO_Tool tool ON tool.ID = report.Tool_id 
       LEFT JOIN Sys_User  ON report.Created_by = Sys_User.F_Id
          LEFT JOIN WI_Item ON WI_Item.ID=report.Test_Item_id
          left join Basic_Unit ut ON ut.ID=infor.WO_Goal_Unite_ID
          left join Basic_TestPhase tp on tp.ID=infor.TestPhase_id
          left join Basic_TestType ty on ty.ID=infor.TestType_id
          left join Basic_Purpose  pu on pu.ID=infor.Pursepose_id
          left join  Basic_Computer bc ON bc.id = report.computer_id
          left join  
              (SELECT       c1.Test_Report_id,
            --'11343'[Failure_id],
            min(c1.IV_path)[IV_path],
            --'00b8155a-1754-4ab1-bdda-2b46136216d4/12606'[IV_path],
            IV_name_video = STUFF((
             SELECT ';' + md.IV_name
             FROM WO_DataForm_Test_Failure_IV md
             WHERE c1.Test_Report_id = md.Failure_id and md.IV_type='Video' FOR XML PATH(''), TYPE).value('.', 'NVARCHAR(MAX)'), 1, 1, ''),
            IV_name_image = STUFF(( SELECT ';' + md.IV_name
             FROM WO_DataForm_Test_Lab_Report_IV md
             WHERE c1.Test_Report_id = md.Test_Report_id and md.IV_type='Image' FOR XML PATH(''), TYPE).value('.', 'NVARCHAR(MAX)'), 1, 1, '')
             FROM WO_DataForm_Test_Lab_Report_IV c1 group by c1.Test_Report_id) fiv on fiv.Test_Report_id=report.id
where 1=1 $where
ORDER BY  tool.Tool_code,report.Creation_date desc";

        $ret = $this->DB->query($query)->result_array();

        return $ret;

    }

    public function getFailureDataFromMTE($where = '') {

        $query = "SELECT infor.TTI_Project_no as 'TTI_Project_no',
              mo .model_code   as 'model_code',
              infor.TR_MET_Project_No as 'Mil TR_MET_Project_No',
                 infor.Customer_Model_no as 'Customer_Model_no',
              mo.model_description as 'Model Description',
                infor.TR_Tool_Type as 'TR_Tool_Type',
                 infor.TR_no as 'TR_no',
              infor.WO_code as 'WO_code' ,
              pu.purpose_code as 'Type of Test',
              infor.TR_Apply_by as 'Requestor',
              infor.WO_Goal as 'Goal',
              ut.unit_code as 'Unit' ,
              tp.TestPhase_name as 'TestPhase_name',
              ty.TestType_name as 'TestType_name',
              bar.Room_Code as 'Room',
            usr.F_RealName as 'Room leader',
              tool.Tool_code as 'Tool_code',
              wb.Battery_Code as 'Battery/Accessory',
              DataForm.Completed_Cycle as 'Completed_Cycle' ,
              DataForm.Completed_Application as 'Completed_Application'  ,
              DataForm.Failure_description 'Failure_description' ,
              worker.F_RealName as'TE',
              convert(varchar,DataForm.Failure_date,20) as 'Failure_date' ,
              DataForm.Before_Failure_decription as 'Before_Failure_decription' ,
              Failure.Failure_Status_Name as 'Failure_Status_Name',
              WI_Item.Item_code as 'Item_code' ,
              
              DataForm.Failure_Varification as 'Failure_Varification',
              DataForm.Completed_Discharge as 'Completed_Discharge' ,
                CONVERT(VARCHAR(10), DataForm.Completed_Time / 3600) + ':'
        + CONVERT(VARCHAR(10), DataForm.Completed_Time % 3600 / 60) + ':'
        + CONVERT(VARCHAR(10), DataForm.Completed_Time % 3600 % 60) AS 'Completed_Time' ,
              DataForm.Remark as 'Remark',
              DataForm.Failure_No as 'Failure_No'  ,
              convert(varchar,DataForm.Creation_Date,20) AS 'Creation_date' ,
              su.F_RealName as 'Operator' ,  
               dataform.Forward_Reverse as 'Forward_Reverse',
              dataform.Trigger_status as 'Trigger_status',          
                DataForm.Comment as 'Remark',
                bc.Computer_Name as 'Computer_Name',
                infor.TR_Apply_date as 'TR_Apply_date',
                dataform.TestItemDescription as 'TestItemDescription',
                Basic_Battery_Charge_Level.Battery_Charge_Level as 'Battery_Charge_Level',
                  --dataform.Charge_Level_id,
                br.Battery_Rate_Voltage as 'Battery_Rate_Voltage',  
                  -- dataform.Rate_Voltage_id
                brc.Battery_Rated_Capacity_name as 'Battery_Rated_Capacity_name',
                tsp.Description as 'Description',
                dataform.Test_Lab_report_id,fiv.IV_path,
                fiv.IV_name_image, fiv.IV_name_video, dataform.id, dataform.id AS recid
              
FROM    WO_DataForm_Test_Failure AS dataform
        LEFT JOIN Basic_Battery_Charge_Level ON Basic_Battery_Charge_Level.Id = DataForm.Charge_Level_id
        LEFT JOIN Basic_Battery_Model ON Basic_Battery_Model.ID = DataForm.Battery_id
        INNER JOIN WO_information AS infor ON infor.ID = DataForm.wo_id
        INNER JOIN WO_Tool AS tool ON tool.ID = DataForm.Tool_id
        left JOIN Sys_User leader on leader.F_Id=dataform.RoomLeader_id
        INNER JOIN Sys_User worker ON worker.F_Id = infor.TR_Responsible_Worker_id
        INNER JOIN Basic_Computer computer ON computer.id = DataForm.computer_id
        Left JOIN WI_Item ON WI_Item.ID =dataform.item_id  --tool.CurItem
        LEFT JOIN WO_Battery wb ON wb.ID = DataForm.Battery_id
        LEFT JOIN Basic_Battery_Model model ON model.ID = wb.Battery_Model_id
        LEFT JOIN Basic_Battery_Rate_Voltage br ON br.ID = DataForm.Rate_Voltage_id
        LEFT JOIN Basic_Battery_Rated_Capacity brc ON brc.ID = Rated_Capacity_id
        INNER JOIN Sys_User su ON su.F_Id = DataForm.Created_by
      LEFT JOIN Basic_Power_Supply_Mode Power ON DataForm.Power_Supply_Mode_id=Power.ID
      LEFT JOIN Basic_Operaction_Mode Operaction ON Operaction.ID=DataForm.Power_Supply_Mode_id
      LEFT JOIN Basic_Failure_Status Failure ON Failure.ID=DataForm.TE_Confirm_status_id
      Inner join Basic_Computer bc on DataForm.computer_id=bc.id
      left join Basic_TestType ty on infor.TestType_id=ty.id
      left join Basic_Unit ut on infor.WO_Goal_Unite_ID=ut.ID
      left join Basic_TestPhase tp on infor.TestPhase_id=tp.ID
      left join WO_Room_Assignment wt on wt.Tool_ID=dataform.Tool_id
      left join Basic_Room  bar on bar.ID=wt.Room_ID
      left join Basic_Model mo on mo.ID=infor.WO_Model_id
      left join Basic_Purpose pu on pu.ID=infor.Pursepose_id
       left join dbo.Sys_User usr on usr.F_Id =dataform.RoomLeader_id
       left join dbo.Basic_Tool_Speed tsp on tsp.ID=dataform.Operaction_Mode_id
       left join 
              (SELECT
                 c1.Failure_id,
                 --'11343'[Failure_id],
                 min(c1.IV_path)[IV_path],
                 --'00b8155a-1754-4ab1-bdda-2b46136216d4/12606'[IV_path],
                 IV_name_video = STUFF((
				  SELECT ';' + md.IV_name
				  FROM WO_DataForm_Test_Failure_IV md
				  WHERE c1.Failure_id = md.Failure_id and md.IV_type='Video' FOR XML PATH(''), TYPE).value('.', 'NVARCHAR(MAX)'), 1, 1, ''),
				 IV_name_image = STUFF((
				  SELECT ';' + md.IV_name
				  FROM WO_DataForm_Test_Failure_IV md
				  WHERE c1.Failure_id = md.Failure_id and md.IV_type='Image' FOR XML PATH(''), TYPE).value('.', 'NVARCHAR(MAX)'), 1, 1, '')
  
                FROM WO_DataForm_Test_Failure_IV c1 group by c1.Failure_id) fiv on fiv.Failure_id=dataform.id

where isnumeric(dataform.Failure_No)=1 and 1=1 $where order by DataForm.Creation_date desc";
        /* anybody can use this sql to find WO_code by Failure_id
         * $query="select top 100 infor.WO_code,c2.Failure_No,c1.Failure_id from WO_information AS infor,WO_DataForm_Test_Failure c2,WO_DataForm_Test_Failure_IV c1 where c2.Failure_No=c1.Failure_id
          and infor.ID = c2.wo_id and isnumeric(c2.Failure_No)=1 and c1.Failure_id>'1510' and c1.Failure_id<'1789'";
        */

        $ret = $this->DB->query($query)->result_array();

        return $ret;

    }

    public function getBatteryDataFromMTE($where = '') {

        $query = " select
 infor.TTI_Project_no as 'TTI_Project_no',
        infor.TR_MET_Project_No as 'TR_MET_Project_No',
        infor.Customer_Model_no as 'Customer_Model_no',
        infor.TR_no as 'TR_no',
        infor.WO_code AS 'WO_code',
        tool.Tool_code AS 'Tool_code',
        Battery.Battery_Code AS 'Battery/Accessory',
        DataForm.Completed_Application AS 'Completed_Application',
        DataForm.Completed_Cycle AS 'Completed_Cycle',
        DataForm.Completed_Discharge AS 'Completed_Discharge',
        CONVERT(VARCHAR(10), DataForm.Completed_Time/ 3600) + ':'
        + CONVERT(VARCHAR(10), DataForm.Completed_Time % 3600 / 60) + ':'
        + CONVERT(VARCHAR(10), DataForm.Completed_Time % 3600 % 60) AS 'Completed_Time',

		DataForm.voltage as 'Voltage(V)',
		DataForm.Impedance as 'Impedance',
		DataForm.Capacity as 'Capacity',
                DataForm.remark as 'Remark',
		Sys_User.F_RealName AS 'Recorder' ,
      convert(varchar,DataForm.Creation_date,20) AS 'Creation_date' ,

        WI_Reminder.Reminder_Descripton AS 'Reminder_Descripton' ,
        WI_Reminder.Test_Before_After_Cycle AS 'Test_Before_After_Cycle',
		DataForm.Test_Lab_Report_ID,
                    DataForm.id,
                    DataForm.id AS recid
FROM    WO_DataForm_Battery_Capacity AS DataForm left join
        WO_Battery AS Battery on Battery.ID = DataForm.Battery_ID left join
        WO_information AS infor on  infor.ID = DataForm.WO_ID left join
        WO_Tool AS tool on tool.ID = DataForm.Tool_ID left join
        WI_Reminder on WI_Reminder.ID = DataForm.Reminder_id left join
        Sys_User on DataForm.Created_by = Sys_User.F_Id
        WHERE 1=1 $where order by DataForm.Creation_date desc";// $where

        $ret = $this->DB->query($query)->result_array();

        return $ret;

    }

    public function getNoLoadDataFromMTE($where = '') {

        $query = " select infor.TTI_Project_no as 'TTI_Project_no',
        infor.TR_MET_Project_No as 'TR_MET_Project_No',
       infor.Customer_Model_no as 'Customer_Model_no',
        infor.TR_no as 'TR_no',
        infor.WO_code AS 'WO_code',
        tool.Tool_code AS 'Tool_code',
        Battery.Battery_Code AS 'Battery/Accessory',
        DataForm.Completed_Application AS 'Completed_Application',
        DataForm.Completed_Cycle AS 'Completed_Cycle',
        DataForm.Completed_Discharge AS 'Completed_Discharge',
        CONVERT(VARCHAR(10), DataForm.Completed_Time/ 3600) + ':'
        + CONVERT(VARCHAR(10), DataForm.Completed_Time % 3600 / 60) + ':'
        + CONVERT(VARCHAR(10), DataForm.Completed_Time % 3600 % 60) AS 'Completed_Time',
        --DataForm.Cycle_Times AS '电池放电次数',
        DataForm.Forward_Reverse AS 'Forward_Reverse',
        DataForm.High_Low_Speed AS 'High_Low_Speed',
        DataForm.No_load_current AS 'No_load_current',
        DataForm.No_Load_Speed AS 'No_Load_Speed',
		su.F_RealName AS 'Recorder',
        convert(varchar,DataForm.Creation_Date,20) AS 'Creation_date' ,
        DataForm.remark  AS 'Remark',
        wir.Test_Before_After_Cycle AS 'Test_Before_After_Cycle',
        wir.Reminder_Descripton AS 'Reminder_Descripton',
        DataForm.Test_Lab_Report_id,
                    DataForm.id,
                    DataForm.id as recid
                      
FROM    WO_DataForm_No_Load AS DataForm 
        left join WO_Battery AS Battery on Battery.ID = DataForm.Battery_id
        left join WO_information AS infor on infor.ID = DataForm.WO_id
        left join WO_Tool AS tool on tool.ID = DataForm.Tool_id
        left join WI_Reminder wir on wir.ID = DataForm.Reminder_id
        left join Sys_User su on DataForm.Created_by = su.F_Id
        
 WHERE 1=1 $where
ORDER BY DataForm.Creation_date desc";

        $ret = $this->DB->query($query)->result_array();

        return $ret;

    }

    public function getTorqueDataFromMTE($where = '') {

        $query = "select infor.TTI_Project_no as 'TTI_Project_no',
       infor.TR_MET_Project_No as 'TR_MET_Project_No',
       infor.Customer_Model_no as 'Customer_Model_no',
        infor.TR_no as 'TR_no',        
        infor.WO_code AS 'WO_code',
        tool.Tool_code AS 'Tool_code',
        Battery.Battery_Code AS 'Battery/Accessory',
        DataForm.Completed_Application AS 'Completed_Application',
        DataForm.Completed_Cycle AS 'Completed_Cycle',
        DataForm.Completed_Discharge AS 'Completed_Discharge',
        DataForm.Completed_Time AS 'Completed_Time',
	    DataForm.High_Low_speed AS 'Speed',
        DataForm.Forward_Reverse AS 'Forward/Reverse', 
        DataForm.Peak_torque AS 'Torque Value1',
        DataForm.Peak_torque2  AS 'Torque Value2',
        DataForm.Peak_torque3  AS 'Torque Value3',
        DataForm.Peak_torque4  AS 'Torque Value4',
        DataForm.Peak_torque5  AS 'Torque Value5',        
		DataForm.remark AS 'Remark',
        Sys_User.F_RealName AS 'Recorder',
	convert(varchar,DataForm.Creation_Date,20) AS 'Creation_date' ,
        WI_Reminder.Reminder_Descripton AS 'Reminder_Descripton',
        WI_Reminder.Test_Before_After_Cycle AS 'Test_Before_After_Cycle',
		DataForm.Test_Lab_Report_id,
                DataForm.id,DataForm.id as recid
                
FROM    WO_DataForm_Peak_Torque AS DataForm left join
        WO_Battery AS Battery on Battery.ID = DataForm.Battery_id left join
        WO_information AS infor on infor.ID = DataForm.WO_id left join
        WO_Tool AS tool on  tool.ID = DataForm.Tool_id left join
        WI_Reminder on WI_Reminder.ID = DataForm.Reminder_id  left join
        Sys_User on  Sys_User.F_Id=DataForm.Created_by 
 WHERE 1=1 $where
ORDER BY DataForm.Creation_date DESC ";

        $ret = $this->DB->query($query)->result_array();

        return $ret;

    }

    public function getGeneralFormDataFromMTE($where = '') {

        $query = " select infor.TR_no as 'TR_no',
        infor.TTI_Project_no as 'TTI_Project_no',
        infor.TR_MET_Project_No as 'TR_MET_Project_No',
        infor.Customer_Model_no as 'Customer_Model_no',
        infor.WO_code AS 'WO_code' ,
        tool.Tool_code AS 'Tool_code' ,
        Battery.Battery_Code AS 'Battery/Accessory' ,
        su.F_RealName AS 'Recorder' ,
		b.unit_code as 'Unit',
              bc.Common_Description as 'Description',
		DataForm.Test_Data1 as 'Value 1',
		DataForm.Test_Data2 as 'Value 2',
		DataForm.Test_Data3 as 'Value 3',
		DataForm.Test_Data4 as 'Value 4',
		DataForm.Test_Data5 as 'Value 5',
                DataForm.Test_Data6 as 'Value 6',
		DataForm.Test_Data7 as 'Value 7',
		DataForm.Test_Data8 as 'Value 8',
		DataForm.Test_Data9 as 'Value 9',
		DataForm.Test_Data10 as 'Value 10',
                convert(varchar,DataForm.Creation_Date,20) AS 'Creation_date' ,
		DataForm.Completed_Cycle as 'Completed_Cycle',
		DataForm.Completed_Discharge 'Completed_Discharge',
    CONVERT(VARCHAR(10), DataForm.Completed_Time / 3600) + ':'
        + CONVERT(VARCHAR(10), DataForm.Completed_Time % 3600 / 60) + ':'
        + CONVERT(VARCHAR(10), DataForm.Completed_Time % 3600 % 60) as 'Completed_Time',
                DataForm.Completed_Application as 'Completed_Application',
		DataForm.remark as 'Remark',
		DataForm.Test_Lab_Report_id,
                DataForm.id,
                DataForm.id as recid        
               
FROM    WO_DataForm_Common AS DataForm 
        inner join WO_Battery AS Battery on Battery.ID = DataForm.Battery_id
        inner join WO_information AS infor on infor.ID = DataForm.WO_id
        inner join WO_Tool AS tool on tool.ID = DataForm.Tool_id
        inner join Sys_User su on DataForm.Created_by = su.F_Id
		inner join dbo.WI_Item w on w.ID = tool.CurItem
        inner join Basic_Unit b on DataForm.Unit_id = b.ID
        left join Basic_Common_DataForm Bc on bc.ID=DataForm.Common_ID
 WHERE 1=1 $where
    order by DataForm.Creation_date desc ";

        $ret = $this->DB->query($query)->result_array();

        return $ret;

    }

    public function getWODailySumFromMTE($where = '') {

        $query = "select  wi.WO_code,wt.Tool_code,
--wb.Battery_Code,
sum(application) as application,
              right('00'+cast(sum(Time)/3600 as varchar),2)+ ':'+
           right('00'+cast((sum(Time)%3600)/60 as varchar),2)+':'
          +right('00'+cast((sum(Time)%3600)%60 as varchar),2)  as runtime,
          count(DISTINCT wdt.Completed_Cycle) as cycles,
          count(DISTINCT WDT.Completed_Discharge) as discharges,
          count(DISTINCT tf.id ) as 'failure_counts',
          br.Room_Code,
          Convert(varchar(10),wdt.Creation_date,23) as 'Creation_date',
          MAX( wdt.Completed_Cycle) as 'Completed_Cycle',
          MAX( wdt.Completed_Discharge) as 'Completed_Discharge',
          MAX( wdt.Completed_Application) as 'Completed_Application',
          CONVERT(VARCHAR(10), MAX( wdt.Completed_Time)/ 3600) + ':'
        + CONVERT(VARCHAR(10), MAX( wdt.Completed_Time) % 3600 / 60) + ':'
        + CONVERT(VARCHAR(10), MAX( wdt.Completed_Time) % 3600 % 60) AS 'Completed_Time',
          concat(wi.WO_code,wt.Tool_code, Convert(varchar(10),wdt.Creation_date,23)) as recid

from WO_DataForm_Test_Lab_Report wdt
inner join WO_information WI on wdt.WO_ID=wi.ID 
inner join WO_Tool wt on wdt.Tool_id=wt.ID 
inner join WO_Battery wb on wdt.Battery_id=wb.ID 
inner join WO_Room_Assignment wra on wt.ID=wra.Tool_ID
inner join Basic_Room br on wra.Room_ID=br.ID
inner join WO_Status_Value wv on wt.WO_Status=wv.ID
left outer join WO_DataForm_Test_Failure tf on tf.Test_Lab_report_id=wdt.id

WHERE 1=1 $where
                                                       
                                                                                                
group by wi.WO_code,wt.Tool_code,br.Room_Code,wv.Status_Code, Convert(varchar(10),wdt.Creation_date,23)
ORDER BY wt.Tool_code, Convert(varchar(10),wdt.Creation_date,23) DESC";

        $ret = $this->DB->query($query)->result_array();

        return $ret;

    }

    public function getDrillRateFromMTE($where = '') {

        $query = " select 

infor.TTI_Project_no,
infor.Customer_Model_no,
infor.TR_MET_Project_No,
infor.WO_code,
wt.Tool_code,
dataform.Completed_Application,
dataform.Completed_Discharge,
--dataform.Completed_Time,
dataform.Completed_Cycle,
CONVERT(VARCHAR(10), dataform.Completed_Time/ 3600) + ':'
        + CONVERT(VARCHAR(10), dataform.Completed_Time % 3600 / 60) + ':'
        + CONVERT(VARCHAR(10), dataform.Completed_Time % 3600 % 60) AS 'Comp. Runtime',
        dataform.Drill_Rate_Type as 'Drill_Rate_Type',
		dataform.Drill_Bit_Code as 'Drill',
		u.unit_code as 'Unit',
		dataform.Hole1 as 'Hole1',
		dataform.Hole2 as 'Hole2',
		dataform.Hole3 as 'Hole3',
		DataForm.Hole4 as 'Hole4',
		DataForm.Hole5 as 'Hole5',
		DataForm.Hole6 as 'Hole6',
		DataForm.Hole7 as 'Hole7',
		DataForm.Hole8 as 'Hole8',
		DataForm.Hole9 as 'Hole9',
		DataForm.Hole10 as 'Hole10',
		DataForm.remark as 'Remark',
		su.F_RealName,
		convert(varchar,dataform.Creation_date,20) AS 'Creation_date' ,
		rm.Reminder_Descripton,
		rm.Quantity,
                dataform.Test_Lab_report,
		dataform.id,
                dataform.id as recid

from WO_DataForm_DrillRate dataform
left join WO_information infor on infor.id=dataform.WO_ID
left join WO_Tool wt on wt.ID=dataform.Tool_id
LEFT JOIN Sys_User su on su.F_Id=dataform.Created_by
left join Basic_Unit u on u.ID=dataform.Unit_ID
left join WI_Reminder rm on rm.ID=dataform.Reminder_id
left join WI_Reminder rmi on rmi.ID=dataform.Unit_ID

where 1=1 $where
order by dataform.Creation_date";


        $ret = $this->DB->query($query)->result_array();

        return $ret;

    }

    public function getNailerTestDataFromMTE($where = '') {

        $query = "
            select W.WO_code WO,T.Tool_code TOOL#,C.Completed_Cycle CYCLE,
       C.Completed_Application,
       D.Common_Description,
       C.Test_Data1, 
       /*钉子高于木头表面*/
      ,C.Test_Data2 
      /*打空枪*/
	  ,C.Test_Data3 
           /*超大孔*/
	  ,C.Test_Data4 
           /*卡钉*/
	  ,C.Test_Data5
           /*弯钉*/
	  ,C.Test_Data6
           /*打双枪*/
	  ,C.Test_Data7 
           /*撞针不回弹*/
	  ,C.Test_Data8
           /*深度调节*/
	  ,C.remark,
      C.id,
      C.id as recid
from WO_DataForm_Common C
INNER JOIN Basic_Common_DataForm D ON C.Common_ID = D.ID
INNER JOIN WO_information W ON C.WO_id = W.ID
INNER JOIN WO_Tool T ON C.Tool_id = T.ID
 WHERE 1=1 $where
ORDER BY W.WO_code,T.Tool_code, c.creation_date ";

        $ret = $this->DB->query($query)->result_array();
        return $ret;

        $this->getCdbhelper()->basicSQLNoReturn('select tr.importMTEData()');
        echo("Total Imported: $rows");

    }
}
