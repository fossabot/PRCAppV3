<?php
include_once APPPATH . "models/modelBasicExtend.php";

class general_project_expense_model extends modelBasicExtend
{


    function __construct()
    {

        $this->table = "GENERAL_PROJECT_EXPENSE";

        $this->pk_field = "cd_general_project_expense";
        $this->ds_field = "ds_general_project_expense";
        $this->prodCatUnique = 'N';

        $this->sequence_obj = 'general_project_expense_cd_general_project_expense_seq';

        $this->controller = 'rfq/general_project_expense';


        $this->fieldsforGrid = array(
            ' "GENERAL_PROJECT_EXPENSE".cd_general_project_expense',
            ' "GENERAL_PROJECT_EXPENSE".ds_general_project_expense',
            ' "GENERAL_PROJECT_EXPENSE".nr_total_raised_usd',
            '("GENERAL_PROJECT_EXPENSE".nr_total_raised_usd - COALESCE( ( SELECT sum(retRMBtoUSDRate(x.dt_record::date, x.nr_total_price_rmb)) FROM "RFQ_PR_GROUP" x WHERE x.cd_general_project_expense = "GENERAL_PROJECT_EXPENSE".cd_general_project_expense ), 0) ) as nr_balance ',
            
            
            ' "GENERAL_PROJECT_EXPENSE".ds_general_project_number',
            ' "GENERAL_PROJECT_EXPENSE".ds_general_project_model_number',
            ' "GENERAL_PROJECT_EXPENSE".dt_amount_raised',
            
            ' "GENERAL_PROJECT_EXPENSE".dt_deactivated',
            ' "GENERAL_PROJECT_EXPENSE".dt_record');
        $this->fieldsUpd = array("cd_general_project_expense", "ds_general_project_expense", "nr_total_raised_usd", "ds_general_project_number", "ds_general_project_model_number", "dt_amount_raised", "dt_deactivated", "dt_record",);


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "stylecond" => "(CASE WHEN \"GENERAL_PROJECT_EXPENSE\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );


        parent::__construct();


    }
}