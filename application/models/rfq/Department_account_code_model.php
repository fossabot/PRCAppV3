<?php

include_once APPPATH . "models/modelBasicExtend.php";

class department_account_code_model extends modelBasicExtend {

    function __construct() {



        $this->table = "DEPARTMENT_ACCOUNT_CODE";

        $this->pk_field = "cd_department_account_code";
        $this->ds_field = "ds_department_account_code";
        $this->prodCatUnique = 'N';


        $this->sequence_obj = '"DEPARTMENT_ACCOUNT_CODE_cd_department_account_code_seq"';

        $this->controller = 'rfq/department_account_code';

        $this->load->model('rfq/department_account_code_cost_center_model', 'acccenter');

        $this->fieldsforGrid = array(
            ' "DEPARTMENT_ACCOUNT_CODE".cd_department_account_code',
            ' "DEPARTMENT_ACCOUNT_CODE".ds_department_account_code',
            ' "DEPARTMENT_ACCOUNT_CODE".ds_department_account_code_for_workflow',
            ' "DEPARTMENT_ACCOUNT_CODE".dt_deactivated',
            ' "DEPARTMENT_ACCOUNT_CODE".cd_expense_type',
            '( select ds_expense_type FROM "EXPENSE_TYPE" WHERE cd_expense_type =  "DEPARTMENT_ACCOUNT_CODE".cd_expense_type) as ds_expense_type',
            $this->acccenter->getJsonColumn('ds_department_cost_json', 'WHERE "DEPARTMENT_ACCOUNT_CODE_COST_CENTER".cd_department_account_code =  "DEPARTMENT_ACCOUNT_CODE".cd_department_account_code '),
            ' "DEPARTMENT_ACCOUNT_CODE".dt_record');

        $this->fieldsUpd = array("cd_department_account_code", "cd_expense_type", "ds_department_account_code", "ds_department_account_code_for_workflow", "dt_deactivated", "dt_record",);


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "stylecond" => "(CASE WHEN \"DEPARTMENT_ACCOUNT_CODE\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );

        $this->fieldsForPLBaseDD = array( $this->pk_field, // first always PK
        $this->ds_field, // second is always the description showing up. on the dropdown,
        ' "DEPARTMENT_ACCOUNT_CODE".cd_expense_type',
        '( select ds_expense_type FROM "EXPENSE_TYPE" WHERE cd_expense_type =  "DEPARTMENT_ACCOUNT_CODE".cd_expense_type) as ds_expense_type'
        );

        $this->fieldsForPLBase = array( $this->pk_field, // first always PK
        '(' .$this->ds_field . ') as description ', // second is always the description showing up. on the dropdown,
        ' "DEPARTMENT_ACCOUNT_CODE".cd_expense_type',
        '( select ds_expense_type FROM "EXPENSE_TYPE" WHERE cd_expense_type =  "DEPARTMENT_ACCOUNT_CODE".cd_expense_type) as ds_expense_type'
        );

        

        parent::__construct();
    }

}
