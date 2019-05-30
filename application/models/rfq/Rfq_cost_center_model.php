<?php

include_once APPPATH . "models/modelBasicExtend.php";

class rfq_cost_center_model extends modelBasicExtend {

    function __construct() {

        $this->table = "RFQ_COST_CENTER";

        $this->pk_field = "cd_rfq_cost_center";
        $this->ds_field = "ds_rfq_item";
        $this->prodCatUnique = 'N';

        $this->sequence_obj = '"RFQ_COST_CENTER_cd_rfq_cost_center_seq"';

        $this->controller = 'rfq/rfq_cost_center';


        $this->fieldsforGrid = array(
            ' "RFQ_COST_CENTER".cd_rfq_cost_center',
            ' "RFQ_COST_CENTER".cd_rfq_item',
            ' "RFQ_COST_CENTER".cd_department_cost_center',
            '( select ds_department_cost_center FROM "DEPARTMENT_COST_CENTER" WHERE cd_department_cost_center =  "RFQ_COST_CENTER".cd_department_cost_center) as ds_department_cost_center',
            ' "RFQ_COST_CENTER".cd_project_model',
            ' "RFQ_COST_CENTER".cd_general_project_expense',
            ' "RFQ_COST_CENTER".ds_project_number',
            ' (select ds_tti_project from "PROJECT" where cd_project= (select cd_project from "PROJECT_MODEL" where cd_project_model=  "RFQ_COST_CENTER".cd_project_model) ) as ds_project_number_ref',
            ' "RFQ_COST_CENTER".dt_deactivated',
            ' "RFQ_COST_CENTER".nr_qtty_to_charge',
            ' "RFQ_COST_CENTER".ds_project_model_number',
            ' (select ds_tti_project_model from "PROJECT_MODEL" where cd_project_model=  "RFQ_COST_CENTER".cd_project_model ) as ds_project_model_number_ref',
            ' "RFQ_COST_CENTER".ds_project_description',
            ' (select "PROJECT_MODEL".ds_project_model || \' - \' || "PROJECT".ds_project from "PROJECT" join "PROJECT_MODEL" on "PROJECT_MODEL".cd_project_model= "RFQ_COST_CENTER".cd_project_model where "PROJECT".cd_project = "PROJECT_MODEL".cd_project) as ds_project_description_ref',
            ' "RFQ_COST_CENTER".dt_record');

        $join = array('JOIN "RFQ_ITEM" ON ("RFQ_ITEM".cd_rfq_item = "RFQ_COST_CENTER".cd_rfq_item) ');
        
        $this->fieldsUpd = array("cd_rfq_cost_center", "nr_qtty_to_charge", "cd_rfq_item", "cd_department_cost_center", "ds_project_number", "dt_deactivated", "dt_record", "ds_project_model_number", "ds_project_description", "cd_project_model","cd_general_project_expense");

        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "stylecond" => "(CASE WHEN \"RFQ_COST_CENTER\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true,
            'join' => $join
        );


        parent::__construct();
    }

}
