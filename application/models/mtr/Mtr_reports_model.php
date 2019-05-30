<?php

include_once APPPATH . "models/modelBasicExtend.php";

class mtr_reports_model extends modelBasicExtend {

    function __construct() {
        
    }

    public function retRetrieveTestReport($rowID) {
//        $ttiProjNo=ltrim($ttiProjNo,"x");
//        $BrandProjectNum=ltrim($BrandProjectNum,"x");
//        $BrandModelNum=ltrim($BrandModelNum,"x");
//        $TTIModelNumber=ltrim($TTIModelNumber,"x");
//        die($TTIModelNumber);


        $query = "SELECT  r.WorkOrderId  as recid, 
                        r.AttachmentFileName,
                       r.path,
                       v.TRNumber,
                       v.BrandProjectNum       as 'MT Project #',
                       v.ProjectNumber         as 'TTI Project #',
                       v.WorkOrderID,
                       v.BrandModelDescription as 'Model Description',
                       v.TestItem,
                       v.PriorityGroup         as 'Project Type',
                       v.SampleProduction      as 'Test Build',
                       v.TTIModelNumber        as 'TTI Model #',
                       v.BrandModelNum         as 'MT Model #',
                       v.SampleDescription     as 'Test Purpose',
                       v.Requestor,
                       CONVERT(varchar(100),v.RequestDate , 101)    as RequestDate,                        
                       v.TRResponsible,
                       v.WOResponsible,
                       v.MFactory,
                       v.ToolQuantities,
                       r.UpdateUser,
                       CONVERT(varchar(100),r.UpdateDate , 101)    as UpdateDate,                      
                       r.ApproveBy,
                      CONVERT(varchar(100),r.ApproveDate , 101)    as ApproveDate                       
                FROM UseTest.dbo.ViewTRTechnicianWO v
                       LEFT JOIN [UseTest2].[dbo].[TR_MilTestReport] r ON (v.WorkOrderID = r.WorkOrderID)
                where v.id in (select distinct (id) from (select distinct BrandProjectNum,ProjectNumber,TTIModelNumber,BrandModelNum,ACDC from UseTest.dbo.ViewTRTechnicianWO where id='$rowID') as b where  v.BrandModelNum=b.BrandModelNum and v.BrandModelNum=b.BrandModelNum and v.ProjectNumber=b.ProjectNumber and v.TTIModelNumber=b.TTIModelNumber and v.ACDC=b.ACDC)                 
                  AND v.Status != '6'
                  and r.ApproveBy<>''
                order by r.UpdateDate desc";
//        die($query);
        $DB = $this->load->database('tr', true);
        $ret = $DB->query($query)->result_array();

        return $ret;
    }

    public function retRetrieveTestReportFileByWO($workOrderList) {
//        $ttiProjNo=ltrim($ttiProjNo,"x");
//        $BrandProjectNum=ltrim($BrandProjectNum,"x");
//        $BrandModelNum=ltrim($BrandModelNum,"x");
//        $TTIModelNumber=ltrim($TTIModelNumber,"x");
//        die($TTIModelNumber);

        if (count($workOrderList) == 0) {
            return array();
        }

        foreach ($workOrderList as $key => $value) {
            $workOrderList[$key] = "'" . $value . "'";
        }
        $WOs = implode(',', $workOrderList);

        $query = "SELECT  r.WorkOrderId, 
                        r.AttachmentFileName               
                FROM  [UseTest2].[dbo].[TR_MilTestReport] r
                where r.WorkOrderId in ($WOs)
                  and r.ApproveBy <> ''
                order by r.UpdateDate desc";
        
        //die($query);
        
        $DB = $this->load->database('tr', true);
        $ret = $DB->query($query)->result_array();

        return $ret;
    }

    public function retRetrieveGridJson($where = "", $orderby = '', $jsonMapping = '', $retrOpt = array()) {

//        $query = 'select a.*,b.ReportQty
//                    from (SELECT min(CAST(t.ID as NVARCHAR(50))) as recid,
//                                 t.BrandProjectNum,
//                                 t.BrandModelNum,
//                                 t.ProjectNumber,
//                                 t.TTIModelNumber,
//                                 p.Description,
//                                 t.PriorityGroup,
//                                 (case t.ACDC when 1 then \'AC Power Tools\' when 2 then \'DC Power Tools\' when 3 then \'Hand Tools\' when 4 then \'Charger\' when 5 then \'Battery\' when 6 then \'Accessory\' when 7 then \'Air Tool\' when 8 then \'T&M Tool\' end) as ToolType,
//                                CONVERT(varchar(100),min(p.StartDate) , 101)    as StartDate
//                          FROM UseTest.dbo.TRNew t,
//                               Project.dbo.ProjectRecord p
//                          ' . $where . ' AND UPPER(t.Brand) in (\'MILLWAUKEE\', \'MILWAUKEE\', \'EMPIRE\')
//                            and t.ProjectNumber = p.prjnum
//                          group by t.BrandProjectNum,
//                                   t.BrandModelNum,
//                                   t.ProjectNumber,
//                                   t.TTIModelNumber,
//                                   p.Description,
//                                   t.PriorityGroup,
//                                   t.ACDC
//                          ) a,
//                         (select ProjectNumber,count(WorkOrderId) as ReportQty from UseTest2.dbo.TR_MilTestReport group by ProjectNumber) b
//                    where a.ProjectNumber = b.ProjectNumber order by a.StartDate desc';

        $query = 'SELECT min(CAST(v.ID as NVARCHAR(50)))              as recid,
                       v.BrandProjectNum,
                       v.BrandModelNum,
                       v.ProjectNumber,
                       v.TTIModelNumber,
                       p.Description,
                       v.PriorityGroup,
                       (case v.ACDC
                          when 1 then \'AC Power Tools\'
                          when 2 then \'DC Power Tools\'
                          when 3 then \'Hand Tools\'
                          when 4 then \'Charger\'
                          when 5 then \'Battery\'
                          when 6 then \'Accessory\'
                          when 7 then \'Air Tool\'
                          when 8 then \'T&M Tool\' end)               as ToolType,
                       count(r.WorkOrderId)                         as ReportQty,
                       CONVERT(varchar(100), min(p.StartDate), 101) as StartDate
                FROM Project.dbo.ProjectRecord p,
                     UseTest2.dbo.TR_MilTestReport r,
                     UseTest.dbo.ViewTRTechnicianWO v
                 ' . $where . ' and UPPER(v.Brand) in (\'MILLWAUKEE\', \'MILWAUKEE\', \'EMPIRE\')
                  and v.ProjectNumber = p.prjnum
                  and r.WorkOrderId=v.WorkOrderID
                group by v.BrandProjectNum,
                         v.BrandModelNum,
                         v.ProjectNumber,
                         v.TTIModelNumber,
                         p.Description,
                         v.PriorityGroup,
                         v.ACDC
                order by StartDate desc';

        $DB = $this->load->database('tr', true);
        $ret = $DB->query($query)->result_array();

        return json_encode($ret);
    }

}
