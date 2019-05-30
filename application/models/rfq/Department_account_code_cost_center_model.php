<?php

include_once APPPATH . "models/modelBasicExtend.php";

class department_account_code_cost_center_model extends modelBasicExtend {

    function __construct() {

        $this->table = "DEPARTMENT_ACCOUNT_CODE_COST_CENTER";

        $this->pk_field = "cd_department_account_code_cost_center";
        $this->ds_field = "ds_department_cost_center";
        $this->prodCatUnique = 'N';

        $this->sequence_obj = '"DEPARTMENT_ACCOUNT_CODE_COST__cd_department_account_code_co_seq"';

        $this->controller = 'rfq/department_account_code_cost_center';


        $this->fieldsforGrid = array(
            ' "DEPARTMENT_ACCOUNT_CODE_COST_CENTER".cd_department_account_code_cost_center',
            ' "DEPARTMENT_ACCOUNT_CODE_COST_CENTER".cd_department_account_code',
            ' "DEPARTMENT_ACCOUNT_CODE_COST_CENTER".cd_department_cost_center',
            '( select ds_department_cost_center FROM "DEPARTMENT_COST_CENTER" WHERE cd_department_cost_center =  "DEPARTMENT_ACCOUNT_CODE_COST_CENTER".cd_department_cost_center) as ds_department_cost_center',
            ' "DEPARTMENT_ACCOUNT_CODE_COST_CENTER".dt_record');
        $this->fieldsUpd = array("cd_department_account_code_cost_center", "cd_department_account_code", "cd_department_cost_center", "dt_record",);


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            //"stylecond" => "(CASE WHEN \"DEPARTMENT_ACCOUNT_CODE_COST_CENTER\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );


        parent::__construct();
    }

}
