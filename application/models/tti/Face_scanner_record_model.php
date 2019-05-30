<?php

include_once APPPATH . "models/modelBasicExtend.php";

class face_scanner_record_model extends modelBasicExtend {

    function __construct() {

        $this->table = "FACE_SCANNER_RECORD";

        $this->pk_field = "cd_face_scanner_record";
        $this->ds_field = "ds_staff_number";
        $this->prodCatUnique = 'N';

        $this->sequence_obj = '"FACE_SCANNER_RECORD_cd_face_scanner_record_seq"';

        $this->hasDeactivate = 'N';

        $this->controller = 'tti/face_scanner_record';


        $this->fieldsforGrid = array(
            ' "FACE_SCANNER_RECORD".cd_face_scanner_record',
            ' ( COALESCE 
                ( 
                   ( SELECT ds_human_resource_full from "HUMAN_RESOURCE" where nr_staff_number = "FACE_SCANNER_RECORD".nr_staff_number), 
                  \'*\' || ( SELECT max(ds_staff_name) from "HR_ATTENDANCE_BASE" where nr_staff_number = "FACE_SCANNER_RECORD".nr_staff_number), 
                   \'*\' || "FACE_SCANNER_RECORD".nr_staff_number::text
                 )
              ) as ds_human_resource_full',
            '"FACE_SCANNER_RECORD".nr_staff_number',
            'LPAD("FACE_SCANNER_RECORD".nr_staff_number::text,6, \'0\') as ds_staff_number',
            ' "FACE_SCANNER_RECORD".ds_staff_name',
            ' "FACE_SCANNER_RECORD".ds_department',
            ' ( datetimedbtogrid("FACE_SCANNER_RECORD".dt_attend_date::timestamp) ) as dt_attend_date',
            ' ( datetimedbtogrid("FACE_SCANNER_RECORD".dt_attend_time::timestamp) ) as dt_attend_time',
            ' "FACE_SCANNER_RECORD".nr_equipment_number');
        $this->fieldsUpd = array("cd_face_scanner_record", "nr_staff_number", "ds_staff_name", "ds_department", "dt_attend_date", "dt_attend_time", "nr_equipment_number",);


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            //"stylecond"  => "(CASE WHEN \"FACE_SCANNER_RECORD\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );


        parent::__construct();
    }

    //create by ken.jin 2018-10-30
    public function importFID() {

        //$wo = implode(',', $importFID);
        $dataGen = array();

        $lastPK = $this->getCdbhelper()->getTableLastTimeStamp("FACEID_PK");

        $query = "SELECT a.[CardID]
                 ,b.EmployeeCode
                 ,b.EmployeeName
                 ,c.DevName
                 , ( CONVERT (varchar, a.[CardTime], 101) + ' ' + CONVERT (varchar, a.[CardTime], 8)  ) as AttendDate 
                 , ( CONVERT (varchar, a.[CardTime], 101) + ' ' + CONVERT (varchar, a.[CardTime], 8)  ) as AttendTime 
                 ,a.[EmployeeID]
                 FROM [HWATT].[dbo].[KQZ_Card] a,
                 [dbo].[KQZ_Employee] b,
                 [dbo].[KQZ_DevInfo] c
                 where b.EmployeeID = a.EmployeeID
                 and c.DevID = a.DevClass
                 and a.[CardID] >= $lastPK
                     order by a.[CardID],a.[CardTime]
                ";

        //$query = 'SELECT DISTINCT t.BrandProjectNum as \'MT Project #\',t.BrandModelNum as \'MT Model #\',t.ProjectNumber as \'TTI Project #\',t.TTIModelNumber as \'TTI Model #\',p.Description, t.PriorityGroup as \'Project Type\',(case t.ACDC when 1 then \'AC Power Tools\' when 2 then \'DC Power Tools\' when 3 then \'Hand Tools\' when 4 then \'Charger\' when 5 then \'Battery\' when 6 then \'Accessory\' when 7 then \'Air Tool\' when 8 then \'T&M Tool\' end) as ToolType ,p.StartDate as ProjStartDate FROM UseTest.dbo.TRNew t,Project.dbo.ProjectRecord p  WHERE UPPER(t.Brand ) in (\'MILLWAUKEE\',\'MILWAUKEE\',\'EMPIRE\') AND t.ProjectNumber=p.prjnum   order by p.StartDate desc'  + $where;
//        $query = ''  + $where;
        /* teste */



        $DB = $this->load->database('faceid', true);
        $faceidrecords = $DB->query($query)->result_array();
                
        foreach ($faceidrecords as $key => $value) {
            $retResult = $this->retRetrieveEmptyNewArray(array(), false)[0];

            $retResult['cd_face_scanner_record'] = $value['CardID'];
            $retResult['nr_staff_number'] = $value['EmployeeCode'];
            $retResult['ds_staff_name'] = $value['EmployeeName'];
            $retResult['ds_department'] = $value['DevName'];
            $retResult['dt_attend_date'] = substr($value['AttendDate'], 0, 16);
            $retResult['dt_attend_time'] = substr($value['AttendTime'], 0, 16);
            $retResult['nr_equipment_number'] = $value['EmployeeID'];
            
            $lastPK = $value['CardID'];
            
            array_push($dataGen, $retResult);
        }
        
        $retDb = $this->updateGridData($dataGen);
        $ret = array('status' => $retDb, 'imported' => count($faceidrecords));
        
        $this->getCdbhelper()->setTableLastTimeStamp("FACEID_PK", $lastPK);
        

        return $ret;
    }

}
