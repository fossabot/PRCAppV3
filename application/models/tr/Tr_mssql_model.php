<?php

include_once APPPATH . "models/modelBasicExtend.php";

class tr_mssql_model extends modelBasicExtend
{

    function __construct()
    {
        /*
          $this->table = "BRAND";

          $this->pk_field = "cd_brand";
          $this->ds_field = "ds_brand";
          $this->prodCatUnique = 'N';

          $this->sequence_obj = '"BRAND_cd_brand_seq"';

          $this->controller = 'brand';


          $this->fieldsforGrid = array(
          ' "BRAND".cd_brand',
          ' "BRAND".ds_brand',
          ' "BRAND".dt_deactivated',
          ' "BRAND".dt_record');
          $this->fieldsUpd = array("cd_brand", "ds_brand", "dt_deactivated", "dt_record",);


          $this->retrOptions = array("fieldrecid" => $this->pk_field,
          "stylecond" => "(CASE WHEN \"BRAND\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
          "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
          "json" => true
          );


          parent::__construct();
         * 
         */
    }

    public function getWorkOrders($projtti, $projmet, $modeltti, $modelmet, $buildstart, $version, $findWorkOrder = '', $mustAddWorkOrder = array())
    {

        if ($findWorkOrder == '') {

            $where = " AND ( ( tr.TestPhaseNew like '$buildstart%' ) ";
            $where = $where . ' AND ( 1 = 2 ';
            IF (!is_null($projtti)) {
                $where = $where . "OR  tr.BrandProjectNum + '-' + tr.ProjectNumber like '%$projtti%' ";
            }

            IF (!is_null($projmet)) {
                $where = $where . "OR  tr.BrandProjectNum + '-' + tr.ProjectNumber like '%$projmet%' ";
            }
            $where = $where . ' ) ';

            $where = $where . ' AND ( 1 = 2 ';
            IF (!is_null($modeltti)) {
                $where = $where . "OR  tr.BrandModelNum + '-' + tr.TTIModelNumber like '%$modeltti%' ";
            }


            IF (!is_null($modelmet)) {
                $where = $where . "OR  tr.BrandModelNum + '-' + tr.TTIModelNumber like '%$modelmet%' ";
            }
            $where = $where . ' ) ';

            if (count($mustAddWorkOrder) > 0) {
                $where = $where . ' OR wo.WorkOrderID in (' . implode(',', $mustAddWorkOrder) . ') ';
            }
            $where = $where . ' ) ';
        } else {
            $where = " AND wo.WorkOrderID = $findWorkOrder";
        }


        $query = "select 'UseTest' as TypeTest,  wo.WorkOrderID, wo.TestItem, wo.TestProcedureName, wo.ToolQty, wo.SampleToolsList,   CAST (wo.Goal as nvarchar) as Goal, wo.Status, wo.GoalUnits, wo.AssignToTechnicianTime, tr.ProjectNumber, tr.BrandModelNum, tr.BrandModelDescription, tr.BrandProjectDescription, tr.TTIModelNumber, tr.SampleProduction, tr.TestPhaseNew, tr.StartTestDate,
( CASE wo.Status 
       WHEN  1 THEN 'Backlog'
       WHEN 2 THEN 'OnHold'
	   WHEN 3 THEN 'OnTest'
	   WHEN 4 THEN 'Active'
	   WHEN 5 THEN 'Completed'
	   WHEN 6 THEN 'Canceled'
END ) as StatusDescription,
tr.LabEstCompDate,
tr.BrandProjectNum,
( CASE WHEN tr.TestPhaseNew = '$buildstart' OR tr.TestPhaseNew =  '" . $buildstart . "_$version' OR tr.TestPhaseNew = '$buildstart$version' THEN 1 ELSE 0 END) as fl_is_same


from [dbo].[TRUseTestWorkOrder] wo
INNER JOIN [dbo].[TRNew] tr ON (tr.TRNumber = wo.TRNumber)


where (tr.MFactory like 'MIL%' or MFactory='Other') $where


union
select 'BrakeLife' as TypeTest, wo.WorkOrderID, wo.TestItem, wo.TestProcedureName, wo.ToolQty, wo.ToolList as SampleToolsList, CAST (wo.Goal as nvarchar) as Goal, wo.Status, wo.GoalUnits, wo.AssignToTechnicianTime, tr.ProjectNumber, tr.BrandModelNum, tr.BrandModelDescription, tr.BrandProjectDescription, tr.TTIModelNumber, tr.SampleProduction, tr.TestPhaseNew, tr.StartTestDate,
( CASE wo.Status 
       WHEN  1 THEN 'Backlog'
       WHEN 2 THEN 'OnHold'
	   WHEN 3 THEN 'OnTest'
	   WHEN 4 THEN 'Active'
	   WHEN 5 THEN 'Completed'
	   WHEN 6 THEN 'Canceled'
END ) as StatusDescription,
tr.LabEstCompDate,
tr.BrandProjectNum,
( CASE WHEN tr.TestPhaseNew = '$buildstart' OR tr.TestPhaseNew =  '" . $buildstart . "_$version' OR tr.TestPhaseNew = '$buildstart$version' THEN 1 ELSE 0 END) as fl_is_same


from [dbo].[TRBreakLiftTestWorkOrder] wo
INNER JOIN [dbo].[TRNew] tr ON (tr.TRNumber = wo.TRNumber)
where (tr.MFactory like 'MIL%' or MFactory='Other') $where
 
 union

select 'GeneralTest' as TypeTest, wo.WorkOrderID, wo.TestItem, wo.TestProcedureName, wo.ToolQty, wo.ToolList as SampleToolsList, wo.GoalOrSpec as Goal, wo.Status, '' as GoalUnits, wo.AssignToTechnicianTime, tr.ProjectNumber, tr.BrandModelNum, tr.BrandModelDescription, tr.BrandProjectDescription, tr.TTIModelNumber, tr.SampleProduction, tr.TestPhaseNew, tr.StartTestDate,
( CASE wo.Status 
       WHEN  1 THEN 'Backlog'
       WHEN 2 THEN 'OnHold'
	   WHEN 3 THEN 'OnTest'
	   WHEN 4 THEN 'Active'
	   WHEN 5 THEN 'Completed'
	   WHEN 6 THEN 'Canceled'
END ) as StatusDescription,
tr.LabEstCompDate,
tr.BrandProjectNum,
( CASE WHEN tr.TestPhaseNew = '$buildstart' OR tr.TestPhaseNew =  '" . $buildstart . "_$version' OR tr.TestPhaseNew = '$buildstart$version' THEN 1 ELSE 0 END) as fl_is_same

from [dbo].[TRGeneralTestWorkOrder] wo
INNER JOIN [dbo].[TRNew] tr ON (tr.TRNumber = wo.TRNumber)
where (tr.MFactory like 'MIL%' or MFactory='Other') $where              "
            . " ORDER BY TypeTest, fl_is_same DESC,  WorkOrderID, StatusDescription ";

        //$query = 'SELECT DISTINCT t.BrandProjectNum as \'MT Project #\',t.BrandModelNum as \'MT Model #\',t.ProjectNumber as \'TTI Project #\',t.TTIModelNumber as \'TTI Model #\',p.Description, t.PriorityGroup as \'Project Type\',(case t.ACDC when 1 then \'AC Power Tools\' when 2 then \'DC Power Tools\' when 3 then \'Hand Tools\' when 4 then \'Charger\' when 5 then \'Battery\' when 6 then \'Accessory\' when 7 then \'Air Tool\' when 8 then \'T&M Tool\' end) as ToolType ,p.StartDate as ProjStartDate FROM UseTest.dbo.TRNew t,Project.dbo.ProjectRecord p  WHERE UPPER(t.Brand ) in (\'MILLWAUKEE\',\'MILWAUKEE\',\'EMPIRE\') AND t.ProjectNumber=p.prjnum   order by p.StartDate desc'  + $where;
//        $query = ''  + $where;
        /* teste */


        $DB = $this->load->database('trone', true);
        $ret = $DB->query($query)->result_array();

        return $ret;
    }


    public function importWorkOrderFromTR($type)
    {

        $this->db->empty_table('TR_IMP_TMP_WORK_ORDER_RAW');


        $lastTimestampWo = $this->getCdbhelper()->getTableLastTimeStamp("TRWorkOrder$type");
        $lastTimestampSample = $this->getCdbhelper()->getTableLastTimeStamp("tblTRTestStatusSampleData$type");
        $lastTimestampTR = $this->getCdbhelper()->getTableLastTimeStamp("TRNew$type");

        //$this->db->delete('TR_IMP_TMP_WORK_ORDER_RAW');


        switch ($type) {
            case 1:
                $query = "select top 10000 'UseTest' as TypeTest,  
                    wo.WorkOrderID, 
                    wo.TestItem, 
                    wo.TestProcedureName, 
                    (CASE wo.TestType WHEN 1 THEN wo.ToolQty WHEN 2 THEN wo.PowerPackQty WHEN 3 THEN wo.ChargerQty END) AS ToolQty, 
                    wo.SampleToolsList,   
                    CAST (wo.Goal as nvarchar) as Goal, 
                    wo.Status, wo.GoalUnits, 
                    wo.AssignToTechnicianTime, 
                    tr.ProjectNumber, 
                    tr.BrandModelNum, 
                    tr.BrandModelDescription, 
                    tr.BrandProjectDescription, 
                    tr.TTIModelNumber, 
                    tr.SampleProduction, 
                    tr.TestPhaseNew, 
                    tr.StartTestDate,
            ( CASE wo.Status 
                   WHEN  1 THEN 'Backlog'
                   WHEN 2 THEN 'OnHold'
                       WHEN 3 THEN 'OnTest'
                       WHEN 4 THEN 'Active'
                       WHEN 5 THEN 'Completed'
                       WHEN 6 THEN 'Canceled'
            END ) as StatusDescription,
            tr.LabEstCompDate,
            tr.BrandProjectNum,
            s.SampleNumber,
            s.TestResults,
            s.Remark,
            s.UpdatedTime,
            s.UpdatedBy,
            tr.[TRDraftNumber],
            tr.[TRNumber],
            tr.[AssignToEngineerTime],
            
            tr.[SupervisorApprovedTime],
            tr.[SampleDescription],
            
            A.Status as trStatus,
            A.WorkOrderName,
            A.MSCDRequirement,
            A.StartDate,
            A.CompletionDate,
            o.Status as ToolStatus,
            o.WUCompleted,


            CAST(wo.DataTimeStamp as bigint) as timestampwo,
            CAST(tr.DataTimeStamp as bigint) as timestamptr,
            CAST(s.DataTimeStamp as bigint) as timestampsample
            

            from [dbo].[TRUseTestWorkOrder] wo
            INNER JOIN [dbo].[TRNew] tr ON (tr.TRNumber = wo.TRNumber)
            LEFT OUTER JOIN [dbo].[tblTRTestStatus] A ON (A.WorkOrder = wo.WorkOrderID)
            LEFT OUTER JOIN [dbo].[tblTRTestStatusSampleData] s ON (s.WorkOrder = wo.WorkOrderID)
            LEFT JOIN OnTest o ON CAST(A.WorkOrder AS VARCHAR) = o.TRNumber AND s.SampleNumber = CAST(o.ToolNumber AS VARCHAR)


            WHERE (tr.MFactory like 'MIL%' or MFactory='Other')
              AND ( CAST(wo.DataTimeStamp as bigint) >= $lastTimestampWo OR CAST(tr.DataTimeStamp as bigint) >= $lastTimestampTR OR CAST(s.DataTimeStamp as bigint) >= $lastTimestampSample )"
                    . " ORDER BY  timestampwo, timestamptr, timestampsample ";

                break;

            case 2:
                $query = "
            select  top 10000 'BrakeLife' as TypeTest, wo.WorkOrderID, wo.TestItem, wo.TestProcedureName, wo.ToolQty, wo.ToolList as SampleToolsList, CAST (wo.Goal as nvarchar) as Goal, wo.Status, wo.GoalUnits, wo.AssignToTechnicianTime, tr.ProjectNumber, tr.BrandModelNum, tr.BrandModelDescription, tr.BrandProjectDescription, tr.TTIModelNumber, tr.SampleProduction, tr.TestPhaseNew, tr.StartTestDate,
            ( CASE wo.Status 
                   WHEN  1 THEN 'Backlog'
                   WHEN 2 THEN 'OnHold'
                       WHEN 3 THEN 'OnTest'
                       WHEN 4 THEN 'Active'
                       WHEN 5 THEN 'Completed'
                       WHEN 6 THEN 'Canceled'
            END ) as StatusDescription,
            tr.LabEstCompDate,
            tr.BrandProjectNum,
            tr.[TestPhaseNew],
            s.[SampleNumber],
            s.[TestResults],
            s.[Remark],
            s.[UpdatedTime],
            s.[UpdatedBy],
            tr.[TRDraftNumber],
            tr.[TRNumber],
            tr.[AssignToEngineerTime],

            tr.[SupervisorApprovedTime],
            tr.[SampleDescription],
            A.Status as trStatus,
            A.WorkOrderName,
            A.MSCDRequirement,
            A.StartDate,
            A.CompletionDate,
            o.Status as ToolStatus,
            o.WUCompleted,
            CAST(wo.DataTimeStamp as bigint) as timestampwo,
            CAST(tr.DataTimeStamp as bigint) as timestamptr,
            CAST(s.DataTimeStamp as bigint) as timestampsample

            from [dbo].[TRBreakLiftTestWorkOrder] wo
            INNER JOIN [dbo].[TRNew] tr ON (tr.TRNumber = wo.TRNumber)
            LEFT OUTER JOIN [dbo].[tblTRTestStatus] A ON (A.WorkOrder = wo.WorkOrderID)
            LEFT OUTER JOIN [dbo].[tblTRTestStatusSampleData] s ON (s.WorkOrder = wo.WorkOrderID)
            LEFT JOIN OnTest o ON CAST(A.WorkOrder AS VARCHAR) = o.TRNumber AND s.SampleNumber = CAST(o.ToolNumber AS VARCHAR)
            
            where (tr.MFactory like 'MIL%' or MFactory='Other')
            AND ( CAST(wo.DataTimeStamp as bigint) >= $lastTimestampWo OR CAST(tr.DataTimeStamp as bigint) >= $lastTimestampTR OR CAST(s.DataTimeStamp as bigint) >= $lastTimestampSample )"
                    . " ORDER BY  timestampwo, timestamptr, timestampsample ";


                break;

            case 3:
                $query = "select top 10000 'GeneralTest' as TypeTest, 
                   wo.WorkOrderID, 
                   wo.TestItem,     
                   wo.TestProcedureName, 
                   (CASE tr.ACDC WHEN 4 THEN wo.ChargerQty WHEN 5 THEN wo.PPQty WHEN 6 THEN AccessoryQty ELSE wo.ToolQty END) AS ToolQty,  
                   (CASE tr.ACDC WHEN 4 THEN wo.ChargeList WHEN 5 THEN wo.PPList WHEN 6 THEN AccessoryList ELSE wo.ToolList END) AS SampleToolsList, 
                   wo.GoalOrSpec as Goal, 
                   wo.Status, 
                   '' as GoalUnits, 
                   wo.AssignToTechnicianTime, 
                   tr.ProjectNumber, 
                   tr.BrandModelNum, 
                   tr.BrandModelDescription, 
                   tr.BrandProjectDescription, 
                   tr.TTIModelNumber, 
                   tr.SampleProduction, 
                   tr.TestPhaseNew, 
                   tr.StartTestDate,
            ( CASE wo.Status 
                   WHEN  1 THEN 'Backlog'
                   WHEN 2 THEN 'OnHold'
                       WHEN 3 THEN 'OnTest'
                       WHEN 4 THEN 'Active'
                       WHEN 5 THEN 'Completed'
                       WHEN 6 THEN 'Canceled'
            END ) as StatusDescription,
            tr.LabEstCompDate,
            tr.BrandProjectNum,
            tr.[TestPhaseNew],
            s.[SampleNumber],
            s.[TestResults],
            s.[Remark],
            s.[UpdatedTime],
            s.[UpdatedBy],
            tr.[TRDraftNumber],
            tr.[TRNumber],
            tr.[AssignToEngineerTime],
            

            tr.[SupervisorApprovedTime],
            tr.[SampleDescription],
            A.Status as trStatus,
            A.WorkOrderName,
            A.MSCDRequirement,
            A.StartDate,
            A.CompletionDate,
            o.Status as ToolStatus,
            o.WUCompleted,
            CAST(wo.DataTimeStamp as bigint) as timestampwo,
            CAST(tr.DataTimeStamp as bigint) as timestamptr,
            CAST(s.DataTimeStamp as bigint) as timestampsample


            from [dbo].[TRGeneralTestWorkOrder] wo
            INNER JOIN [dbo].[TRNew] tr ON (tr.TRNumber = wo.TRNumber)
            LEFT OUTER JOIN [dbo].[tblTRTestStatusSampleData] s ON (s.WorkOrder = wo.WorkOrderID)
            LEFT OUTER JOIN [dbo].[tblTRTestStatus] A ON (A.WorkOrder = wo.WorkOrderID)
            LEFT JOIN OnTest o ON CAST(A.WorkOrder AS VARCHAR) = o.TRNumber AND s.SampleNumber = CAST(o.ToolNumber AS VARCHAR)
            where (tr.MFactory like 'MIL%' or MFactory='Other')
            AND ( CAST(wo.DataTimeStamp as bigint) >= $lastTimestampWo OR CAST(tr.DataTimeStamp as bigint) >= $lastTimestampTR OR CAST(s.DataTimeStamp as bigint) >= $lastTimestampSample ) "
                    . " ORDER BY  timestampwo, timestamptr, timestampsample ";

                break;


            default:
                break;
        }

        $DB = $this->load->database('trone', true);
        $ret = $DB->query($query)->result_array();


        /*
          print "<pre>";
          print_r($ret);
          print "</pre>";
          die ();
         */
        foreach ($ret as $key => $value) {


            IF ($value['timestampwo'] > $lastTimestampWo) {
                $lastTimestampWo = $value['timestampwo'];
            }

            IF ($value['timestampsample'] > $lastTimestampSample) {
                $lastTimestampSample = $value['timestampsample'];
            }

            IF ($value['timestamptr'] > $lastTimestampTR) {
                $lastTimestampTR = $value['timestamptr'];
            }

            $this->db->insert('TR_IMP_TMP_WORK_ORDER_RAW', $value);
        }

        $this->getCdbhelper()->setTableLastTimeStamp("TRWorkOrder$type", $lastTimestampWo);
        $this->getCdbhelper()->setTableLastTimeStamp("tblTRTestStatusSampleData$type", $lastTimestampSample);
        $this->getCdbhelper()->setTableLastTimeStamp("TRNew$type", $lastTimestampTR);
        $rows = count($ret);


        $this->getCdbhelper()->basicSQLNoReturn('select tr.importTRData()');

        $ret = $DB->query('execute [dbo].[ProcDelLmsSample];');

        echo("Total Imported: $rows for Type $type");

    }

}
