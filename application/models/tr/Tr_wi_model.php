<?php
/**
 * Created by PhpStorm.
 * User: taylor.dong
 * Date: 03/08/2019
 * Time: 4:16 PM
 */


include_once APPPATH . "models/modelBasicExtend.php";

class tr_wi_model extends modelBasicExtend
{

    function __construct()
    {

    }


    public function importWiFromTR()
    {

        $this->db->empty_table('TR_IMP_TMP_WI');


        $lastTimestamp = $this->getCdbhelper()->getTableLastTimeStamp("TestProcedure");


        $query = "SELECT top 10000 ID, 
                           TestProcedureNumber,
                           TestProcedureName,
                           GoalUnits,
                           Efficiency,
                           responsiblity,
                           MinGoal,
                           MaxGoal,
                           UpdatedDate,  
                           FlowType,                         
                           CAST(DataTimeStamp as bigint) as DataTimeStamp
                            FROM TestProcedure
                            where WIType = 2
                            AND CAST(DataTimeStamp as bigint) > $lastTimestamp  ORDER BY  DataTimeStamp ";



        $DB = $this->load->database('trone', true);
        $ret = $DB->query($query)->result_array();


        foreach ($ret as $key => $value) {

            IF ($value['DataTimeStamp'] > $lastTimestamp) {
                $lastTimestamp = $value['DataTimeStamp'];
            }
            $this->db->insert('TR_IMP_TMP_WI', $value);
        }

        $this->getCdbhelper()->setTableLastTimeStamp("TestProcedure", $lastTimestamp);

        $rows = count($ret);


        $this->getCdbhelper()->basicSQLNoReturn('select tr.importTrWiData()');



        echo("Total Imported: $rows ");

    }

    public function getMaterialList($cd_test_procedure)
    {
        $sql = "SELECT      a.detailid as recid,
                            CAST(A.UsageRatePerUnits AS varchar(16)) AS UsageRatePerUnits,
                           A.MaterialPN,
                           A.MaterialDesc,
                           CAST(C.Price AS varchar(16))             AS UnitPrice,
                           B.ProcedureUnits
                    FROM usetest2.dbo.AT_ProcedureMaterial AS A
                             LEFT OUTER JOIN usetest2.dbo.AT_Procedure AS B ON A.MasterID = B.MasterID
                             LEFT OUTER JOIN usetest.dbo.tblPartList AS C ON A.MaterialPN = C.PartNumber
                             LEFT OUTER JOIN  usetest.dbo.TestProcedure T on b.ProcedureName=t.TestProcedureName
                    where T.TestProcedureNumber='$cd_test_procedure'";

        $DB = $this->load->database('trone', true);
        $ret = $DB->query($sql)->result_array();


        foreach ($ret as $k => $v) {
            $MaterialPN = $v["MaterialPN"];
            $sql = "select cd_equipment_design,ds_equipment_design,nr_part_number from rfq.\"RFQ_PART_NUMBER_VIEW\" where nr_part_number='$MaterialPN'";
            $array_pn = $this->getCdbhelper()->basicSQLArray($sql);

            if (!empty($array_pn)) {
                $ret[$k]['MaterialExistsInLMS'] = 'Y';
                $ret[$k]['cd_equipment_design'] = $array_pn[0]['cd_equipment_design'];
                $ret[$k]['ds_equipment_design'] = $array_pn[0]['ds_equipment_design'];
            } else {
                $ret[$k]['MaterialExistsInLMS'] = 'N';
//                array_splice($ret, $k, 1);
            }
        }

        $retLMSexist=array();
        foreach ($ret as $index => $value) {
            if ($value['MaterialExistsInLMS'] == 'Y') {
                array_push($retLMSexist, $ret[$index]);
            }
        }

        return $retLMSexist;
    }

}
