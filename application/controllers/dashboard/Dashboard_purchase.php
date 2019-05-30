<?php
/**
 * Created by PhpStorm.
 * User: Taylor.Dong
 * Date: 10/08/2018
 * Time: 1:49 PM
 */


if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class dashboard_purchase extends controllerBasicExtend
{

    var $arrayIns;
    var $fields;

    function __construct()
    {
        parent::__construct();
//        $this->load->model("tr/test_unit_model", "mainmodel", TRUE);

        $this->load->model("dashboard/dashboard_purchase_model", "purchasemodel", TRUE);


    }

    public function index()
    {

        parent::checkMenuPermission();


        $grid = $this->w2gridgen;
        $f = $this->cfields;
        $fm = $this->cfiltermaker;

        if (1 == 2) {
            $f = new Cfields();
            $grid = new w2gridgen();
            $fm = new cfiltermaker();
        }

        $year = date('Y');


        $javascript = '';


        $send = array("javascript" => $javascript,
            "filters" => '',
            "year" => $year,
            "dataSupplierPurchaseTimesByYear" => json_encode($this->getSupplierPurchaseTimesByYear($year,'ENG', false),JSON_NUMERIC_CHECK),
            "dataSpendByCostCenterAccountCode" => json_encode($this->getSpendByCostCenterAccountCode($year, 6513,82660,false),JSON_NUMERIC_CHECK),
            "dataTstMemberSpend" => json_encode($this->getTstMemberSpendByYear($year,6513, false),JSON_NUMERIC_CHECK),
            "dataTestDepartmentSpend" => json_encode($this->getTestDepartmentSpending($year, 6513,false),JSON_NUMERIC_CHECK),
            "dataTst3TeamSpend" => json_encode($this->getTst3TeamSpendByYear($year,false),JSON_NUMERIC_CHECK),

        );

        $this->load->view("dashboard/dashboard_purchase_view", $send);
    }

    public function getAllDataByYear($year,$AccountCode,$language)
    {

        $x = $_POST['cost'];

        $CostCenter = implode(',', $x);

        //die ($AccountCode);
//        echo "<script>alert($CostCenter);</script>";

        $var1 = $this->getSpendByCostCenterAccountCode($year,$CostCenter,$AccountCode, false);
        $var2 = $this->getSupplierPurchaseTimesByYear($year, $language,false );
        $var3 = $this->getTestDepartmentSpending($year, $CostCenter,false);
        $var4 = $this->getTst3TeamSpendByYear($year,false);

        $var7 = $this->getTstMemberSpendByYear($year, $CostCenter,$echo = false);

        echo(json_encode(array(
            'dataSpendByCostCenterAccountCode' => $var1,
            'dataSupplierPurchaseTimesByYear' => $var2,
            'dataTestDepartmentSpend'=>$var3,
            'dataTst3TeamSpend' => $var4,
            'dataTstMemberSpend' => $var7,
            ), JSON_NUMERIC_CHECK));
    }

    public function getSpendByCostCenterAccountCode($year,$CostCenter,$AccountCode, $echo = true)
    {
        $data = $this->purchasemodel->getSpendByCostCenterAccountCode($year,$CostCenter,$AccountCode);

        if ($echo) {
            echo(json_encode(array('linedata' => $data), JSON_NUMERIC_CHECK));
        } else {
            return $data;

        }

    }

    public function getSupplierPurchaseTimesByYear($year,$Language, $echo = true)
    {
        $data = $this->purchasemodel->getSupplierPurchaseTimesByYear($year,$Language);

        if ($echo) {
            echo(json_encode(array('piedata' => $data), JSON_NUMERIC_CHECK));
        } else {
            return $data;

       }

    }

    public function getTestDepartmentSpending($year, $CostCenter, $echo = true)
    {
//        die($CostCenter);

        $data = $this->purchasemodel->getTestDepartmentSpending($year,$CostCenter);

        if ($echo) {
            echo(json_encode(array('bardata' => $data), JSON_NUMERIC_CHECK));
        } else {
            return $data;

        }

    }

    public function getTstMemberSpendByYear($year, $CostCenter,$echo = true)
    {
        $data = $this->purchasemodel->getTstMemberSpendByYear($year, $CostCenter);

        if ($echo) {
            echo(json_encode(array('bartstdata' => $data), JSON_NUMERIC_CHECK));
        } else {
            return $data;

        }

    }

    public function getTst3TeamSpendByYear($year,$echo = true)
    {
        $data = $this->purchasemodel->getTst3TeamSpendByYear($year);

        if ($echo) {
            echo(json_encode(array('piedata' => $data), JSON_NUMERIC_CHECK));
        } else {
            return $data;

        }

    }


}
