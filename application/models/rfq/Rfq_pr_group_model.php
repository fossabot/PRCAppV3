<?php

include_once APPPATH . "models/modelBasicExtend.php";

class rfq_pr_group_model extends modelBasicExtend {

    function __construct() {

        $this->table = "RFQ_PR_GROUP";

        $this->pk_field = "cd_rfq_pr_group";
        $this->ds_field = "ds_rfq";
        $this->prodCatUnique = 'N';

        $this->sequence_obj = '"RFQ_PR_GROUP_cd_rfq_pr_group_seq"';

        $this->controller = 'rfq/rfq_pr_group';

        $this->hasDeactivate = false;

        $this->load->model("rfq/rfq_pr_group_distribution_model", "distmodel");

        $trans = $this->getCdbhelper()->retTranslationDifKeys(array('new' => 'New', 'repair' => 'Repair'));
        $new = $trans['new'];
        $repair = $trans['repair'];


        $this->fieldsforGrid = array(
            ' "RFQ_PR_GROUP".cd_rfq_pr_group',
            ' "RFQ_PR_GROUP".cd_rfq',
            ' "RFQ_PR_GROUP".cd_supplier',
            ' ( "SUPPLIER".ds_vendor_code:: text || \' - \' ||  "SUPPLIER".ds_supplier || COALESCE(( CASE WHEN "SUPPLIER".ds_supplier !=  COALESCE("SUPPLIER".ds_supplier_alt, "SUPPLIER".ds_supplier) THEN \' - \' || "SUPPLIER".ds_supplier_alt ELSE \'\' END ), \'\')  )  as ds_supplier ',
            ' "RFQ_PR_GROUP".cd_currency',
            '( select ds_currency FROM "CURRENCY" WHERE cd_currency =  "RFQ_PR_GROUP".cd_currency) as ds_currency',
            ' "RFQ_PR_GROUP".cd_department_cost_center',
            '( select ds_department_cost_center FROM "DEPARTMENT_COST_CENTER" WHERE cd_department_cost_center =  "RFQ_PR_GROUP".cd_department_cost_center) as ds_department_cost_center',
            ' "RFQ_PR_GROUP".ds_project_number',
            ' "RFQ_PR_GROUP".ds_project_model_number',
            " ( CASE WHEN ds_kind = 'N' THEN '$new' ELSE '$repair' END) as ds_kind_description ",
            ' "RFQ_PR_GROUP".nr_total_qty',
            ' "RFQ_PR_GROUP".nr_total_price',
            ' "RFQ_PR_GROUP".nr_total_price_with_tax',
            ' "RFQ_PR_GROUP".nr_total_price_rmb',
            ' "RFQ_PR_GROUP".nr_total_price_rmb_with_tax',
            ' "RFQ_PR_GROUP".ds_pr_number',
            ' "RFQ_PR_GROUP".ds_por_reference',
            ' "RFQ_PR_GROUP".cd_department_account_code',
            ' "RFQ_PR_GROUP".ds_po_number',
            
            '( select ds_department_account_code FROM "DEPARTMENT_ACCOUNT_CODE" WHERE cd_department_account_code =  "RFQ_PR_GROUP".cd_department_account_code) as ds_department_account_code',
            ' "RFQ_PR_GROUP".cd_expense_type',
            '( select ds_expense_type FROM "EXPENSE_TYPE" WHERE cd_expense_type =  "RFQ_PR_GROUP".cd_expense_type) as ds_expense_type',
            ' "RFQ_PR_GROUP".ds_remarks',
            ' "RFQ_PR_GROUP".dt_deactivated',
            $this->distmodel->getJsonColumn('json_quo', ' WHERE "RFQ_PR_GROUP_DISTRIBUTION".cd_rfq_pr_group = "RFQ_PR_GROUP".cd_rfq_pr_group  ')
        );
        $this->fieldsUpd = array("cd_rfq_pr_group", "cd_rfq", "cd_supplier", "cd_currency", "cd_department_cost_center", "ds_project_number", "ds_project_model_number", "nr_total_qty", "nr_total_price", "nr_total_price_with_tax", "nr_total_price_rmb", "nr_total_price_rmb_with_tax", "ds_pr_number", "ds_por_reference", "cd_department_account_code", "cd_expense_type", "ds_remarks", "dt_deactivated", "ds_po_number");
        $join = array('JOIN "SUPPLIER" ON ("SUPPLIER".cd_supplier = "RFQ_PR_GROUP".cd_supplier) ');

        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            //    "stylecond" => "(CASE WHEN \"RFQ_PR_GROUP\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true,
            "join" => $join
        );


        parent::__construct();
    }

}
