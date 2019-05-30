<?php
/**
 * Created by PhpStorm.
 * User: Taylor.Dong
 * Date: 10/08/2018
 * Time: 4:40 PM
 */
include_once APPPATH . "models/modelBasicExtend.php";

class dashboard_purchase_model extends modelBasicExtend
{

    function __construct()
    {

        parent::__construct();
    }

    public function getRawData($where)
    {

    }

    public function getSupplierPurchaseTimesByYear($Year,$supplierLanguage)
    {

        if($supplierLanguage=="ENG") {
            $sql = "SELECT  ds_supplier as name, COUNT(nr_total_price) AS value
                  FROM rfq.\"RFQ_PR_FINAL_VIEW\"
                  WHERE nr_year = '$Year'
                  GROUP BY name
                  ORDER BY value DESC
                  LIMIT 10";
        }
        elseif($supplierLanguage=="CHN")
        {
            $sql = "SELECT  ds_supplier_alt as name, COUNT(nr_total_price) AS value
                  FROM rfq.\"RFQ_PR_FINAL_VIEW\"
                  WHERE nr_year = '$Year'
                  GROUP BY name
                  ORDER BY value DESC
                  LIMIT 10";
        }

        $ret = $this->getCdbhelper()->basicSQLArray($sql);

        return $ret;
    }

    public function getTestDepartmentSpending($Year, $CostCenter)
    {

        $sql = "SELECT  ds_department_cost_center_code,nr_month, SUM(nr_total_price)
                  FROM rfq.\"RFQ_PR_FINAL_VIEW\"
                  WHERE nr_year = '$Year'
                	AND ds_department_cost_center_code::integer in ($CostCenter)
                  GROUP BY ds_department_cost_center_code,nr_year, nr_month, ds_department_cost_center
                  ORDER BY ds_department_cost_center_code,nr_year, ds_department_cost_center, nr_month";


        $ret = $this->getCdbhelper()->basicSQLArray($sql);

        return $ret;
    }

    public function getSpendByCostCenterAccountCode($Year, $CostCenter, $AccountCode)
    {

        $sql = "SELECT nr_month, SUM(nr_total_price)
                FROM rfq.\"RFQ_PR_FINAL_VIEW\"
                WHERE nr_year = '$Year'
                    AND ds_department_cost_center_code::integer in ($CostCenter)
                    AND LEFT(ds_department_account_code, 5)= '$AccountCode'
                GROUP BY nr_month
                ORDER BY nr_month";


        $ret = $this->getCdbhelper()->basicSQLArray($sql);

        return $ret;
    }

    public function getTstMemberSpendByYear($Year, $CostCenter)
    {


        $sql = "SELECT r.requester, r.ds_department_cost_center_code, SUM(r.nr_total_price) AS total_price
                FROM rfq.\"RFQ_PR_FINAL_VIEW\" r,
                     \"HUMAN_RESOURCE\" h
                WHERE r.ds_department_cost_center_code :: integer in ($CostCenter)
                  AND r.nr_year = '$Year'
                  and r.requester = h.ds_human_resource
                  and h.cd_team in ('5', '6', '8')
                GROUP BY r.requester, r.ds_department_cost_center_code
                order BY r.requester, r.ds_department_cost_center_code";
        $ret = $this->getCdbhelper()->basicSQLArray($sql);

        return $ret;
    }

    public function getTst3TeamSpendByYear($Year)
    {
        $sql = "SELECT cast(SUM(nr_total_price) as int) as value, t.ds_team as name
                FROM rfq.\"RFQ_PR_FINAL_VIEW\" r,
                     \"HUMAN_RESOURCE\" h,
                     \"TEAM\" t
                WHERE nr_year = '$Year'
                  and r.requester = h.ds_human_resource
                  and h.cd_team = t.cd_team
                  and t.ds_team in ('EE', 'ME', 'PM')
                GROUP BY t.ds_team";
        $ret = $this->getCdbhelper()->basicSQLArray($sql);

        return $ret;
    }


}