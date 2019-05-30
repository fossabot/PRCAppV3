<?php

include_once APPPATH . "models/modelBasicExtend.php";

class expense_type_model extends modelBasicExtend {

    function __construct() {

        $this->table = "EXPENSE_TYPE";

        $this->pk_field = "cd_expense_type";
        $this->ds_field = "ds_expense_type";
        $this->prodCatUnique = 'N';

        $this->sequence_obj = '"EXPENSE_TYPE_cd_expense_type_seq"';

        $this->controller = 'rfq/expense_type';


        $this->fieldsforGrid = array(
            ' "EXPENSE_TYPE".cd_expense_type',
            ' "EXPENSE_TYPE".ds_expense_type',
            ' "EXPENSE_TYPE".ds_workflow_id',
            ' "EXPENSE_TYPE".dt_deactivated',
            ' "EXPENSE_TYPE".dt_record',
            );
        ;
        $this->fieldsUpd = array("cd_expense_type", "ds_expense_type", "ds_workflow_id", "dt_deactivated", "dt_record", "cd_expense_type");


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "stylecond" => "(CASE WHEN \"EXPENSE_TYPE\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );


        parent::__construct();
    }

}
