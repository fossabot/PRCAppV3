<?php
include_once APPPATH . "models/modelBasicExtend.php";

class department_cost_center_model extends modelBasicExtend
{


    function __construct()
    {

        $this->table = "DEPARTMENT_COST_CENTER";

        $this->pk_field = "cd_department_cost_center";
        $this->ds_field = "ds_department_cost_center";
        $this->prodCatUnique = 'N';

        $this->sequence_obj = '"DEPARTMENT_COST_CENTER_cd_department_cost_center_seq"';

        $this->controller = 'rfq/department_cost_center';


        $this->fieldsforGrid = array(

            ' "DEPARTMENT_COST_CENTER".cd_department_cost_center',
            ' "DEPARTMENT_COST_CENTER".ds_department_cost_center',
            ' "DEPARTMENT_COST_CENTER".dt_deactivated',
            ' "DEPARTMENT_COST_CENTER".dt_record',
            ' "DEPARTMENT_COST_CENTER".ds_department_cost_center_code',
            ' "DEPARTMENT_COST_CENTER".cd_department',
            ' "DEPARTMENT_COST_CENTER".fl_demand_project',
            '( select ds_department FROM "DEPARTMENT" WHERE cd_department =  "DEPARTMENT_COST_CENTER".cd_department) as ds_department');
        $this->fieldsUpd = array("cd_department_cost_center", "ds_department_cost_center", "dt_deactivated", "dt_record", "ds_department_cost_center_code", "cd_department", 'fl_demand_project');


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "stylecond" => "(CASE WHEN \"DEPARTMENT_COST_CENTER\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );


        parent::__construct();


    }
}