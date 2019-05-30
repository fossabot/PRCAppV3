<?php

include_once APPPATH . "models/modelBasicExtend.php";

class rfq_pr_group_distribution_model extends modelBasicExtend
{

    function __construct()
    {

        $this->table = "RFQ_PR_GROUP_DISTRIBUTION";

        $this->pk_field = "cd_rfq_pr_group_distribution";
        $this->ds_field = "ds_rfq_pr_group";
        $this->prodCatUnique = 'N';

        $this->sequence_obj = '"RFQ_PR_GROUP_DISTRIBUTION_cd_rfq_pr_group_distribution_seq"';

        $this->controller = 'rfq/rfq_pr_group_distribution';

        $trans = $this->getCdbhelper()->retTranslationDifKeys(array('new' => 'New', 'repair' => 'Repair'));
        $new = $trans['new'];
        $repair = $trans['repair'];
        $hmcode = $this->session->userdata('cd_human_resource');

        $this->load->model("rfq/rfq_pr_incoming_outcoming_model", "incomingmodel");

        $fl_rfq_see_price = $this->getCdbhelper()->getUserPermission('fl_rfq_see_price');

        $this->fieldsforGrid = array(
            ' "RFQ_PR_GROUP_DISTRIBUTION".cd_rfq_pr_group_distribution',
            ' "RFQ_PR_GROUP_DISTRIBUTION".cd_rfq_pr_group',
            '( select ds_project_number FROM "RFQ_PR_GROUP" WHERE cd_rfq_pr_group =  "RFQ_PR_GROUP_DISTRIBUTION".cd_rfq_pr_group) as ds_rfq_pr_group',
            ' "RFQ_PR_GROUP_DISTRIBUTION".cd_rfq_item_supplier_quotation',
            '( select ds_kind FROM "RFQ_ITEM_SUPPLIER_QUOTATION" WHERE cd_rfq_item_supplier_quotation =  "RFQ_PR_GROUP_DISTRIBUTION".cd_rfq_item_supplier_quotation) as ds_rfq_item_supplier_quotation',
            ' "RFQ_PR_GROUP_DISTRIBUTION".cd_rfq_item',
            '( select ds_reason_buy FROM "RFQ_ITEM" WHERE cd_rfq_item =  "RFQ_PR_GROUP_DISTRIBUTION".cd_rfq_item) as ds_rfq_item',
            ' "RFQ_PR_GROUP_DISTRIBUTION".nr_qtty_to_charge',
            ' "RFQ_PR_GROUP_DISTRIBUTION".nr_total_price',
            ' "RFQ_PR_GROUP_DISTRIBUTION".nr_total_price_with_tax',
            ' "RFQ_PR_GROUP_DISTRIBUTION".nr_total_price_rmb',
            ' "RFQ_PR_GROUP_DISTRIBUTION".nr_price',
            ' "RFQ_PR_GROUP_DISTRIBUTION".nr_price_with_tax',
            ' "RFQ_PR_GROUP_DISTRIBUTION".nr_price_with_tax_default_currency',
            ' "RFQ_PR_GROUP_DISTRIBUTION".nr_price_default_currency',
            ' "RFQ_PR_GROUP_DISTRIBUTION".nr_total_price_rmb_with_tax',
            '( "EQUIPMENT_DESIGN".ds_equipment_description_full ) as ds_equipment_design',
            '( "EQUIPMENT_DESIGN".ds_equipment_code_full || ( CASE WHEN "RFQ_ITEM".ds_equipment_design_code_complement IS NOT NULL THEN \'-\' || "RFQ_ITEM".ds_equipment_design_code_complement ELSE \'\' END) ) as ds_equipment_design_code',
            '( "EQUIPMENT_DESIGN".ds_equipment_description_full ) as ds_equipment_design',
            '("EQUIPMENT_DESIGN".ds_equipment_design ) as ds_equipment_design_description',
            ' "RFQ_ITEM".ds_remarks',
            ' "RFQ_ITEM_SUPPLIER_QUOTATION".dt_update',
            ' "RFQ_ITEM".ds_reason_buy',
            ' "RFQ_ITEM_SUPPLIER_QUOTATION".ds_reason_to_choose_supplier',
            ' ( "SUPPLIER".ds_vendor_code:: text || \' - \' ||  "SUPPLIER".ds_supplier || COALESCE(( CASE WHEN "SUPPLIER".ds_supplier !=  COALESCE("SUPPLIER".ds_supplier_alt, "SUPPLIER".ds_supplier) THEN \' - \' || "SUPPLIER".ds_supplier_alt ELSE \'\' END ), \'\')  )  as ds_supplier ',
            " ( CASE WHEN ds_kind = 'N' THEN '$new' ELSE '$repair' END) as ds_kind_description ",
            ' ( "RFQ_ITEM_SUPPLIER_QUOTATION".ds_remarks ) as ds_remarks_quotation',
            '"RFQ_ITEM_SUPPLIER".ds_supplier_equipment_description',
            '"RFQ_ITEM_SUPPLIER".ds_supplier_equipment_part_number',
            '( select ds_payment_term FROM "PAYMENT_TERM" WHERE cd_payment_term =  "RFQ_ITEM_SUPPLIER_QUOTATION".cd_payment_term) as ds_payment_term',
            ' "RFQ_ITEM".ds_equipment_design_desc_complement ',
            '"RFQ_REQUEST_TYPE".ds_rfq_request_type',
            ' "RFQ_ITEM".ds_brand',
            '( select ds_unit_measure FROM "UNIT_MEASURE"WHERE "UNIT_MEASURE".cd_unit_measure =  "RFQ_ITEM".cd_unit_measure) as ds_unit_measure',
        );

        $this->fieldsUpd = array("cd_rfq_pr_group_distribution", "cd_rfq_pr_group", "cd_rfq_item_supplier_quotation", "cd_rfq_item", "nr_qtty_to_charge", "nr_total_price", "nr_total_price_with_tax", "nr_total_price_rmb", "nr_total_price_rmb_with_tax",);

        $join = array(
            'JOIN "RFQ_ITEM_SUPPLIER_QUOTATION" ON ( "RFQ_ITEM_SUPPLIER_QUOTATION".cd_rfq_item_supplier_quotation = "RFQ_PR_GROUP_DISTRIBUTION".cd_rfq_item_supplier_quotation )',
            'JOIN "RFQ_ITEM" ON ( "RFQ_ITEM".cd_rfq_item = "RFQ_PR_GROUP_DISTRIBUTION".cd_rfq_item )',
            'JOIN "RFQ_ITEM_SUPPLIER" ON ("RFQ_ITEM_SUPPLIER".cd_rfq_item_supplier = "RFQ_ITEM_SUPPLIER_QUOTATION".cd_rfq_item_supplier)',
            'JOIN "EQUIPMENT_DESIGN" ON ("EQUIPMENT_DESIGN".cd_equipment_design = "RFQ_ITEM".cd_equipment_design )',
            'JOIN "RFQ_REQUEST_TYPE" ON ("RFQ_REQUEST_TYPE".cd_rfq_request_type =  "RFQ_ITEM".cd_rfq_request_type)',
        );


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            //"stylecond" => "(CASE WHEN \"RFQ_PR_GROUP_DISTRIBUTION\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true,
            'join' => $join
        );


        $this->fieldsforGridFull = array(
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
            ' "RFQ_PR_GROUP".ds_pr_number',
            ' "RFQ_PR_GROUP".ds_por_reference',
            ' "RFQ_PR_GROUP".cd_department_account_code',
            '( select ds_department_account_code FROM "DEPARTMENT_ACCOUNT_CODE" WHERE cd_department_account_code =  "RFQ_PR_GROUP".cd_department_account_code) as ds_department_account_code',
            ' "RFQ_PR_GROUP".cd_expense_type',
            '( select ds_expense_type FROM "EXPENSE_TYPE" WHERE cd_expense_type =  "RFQ_PR_GROUP".cd_expense_type) as ds_expense_type',
            ' "RFQ_PR_GROUP".ds_remarks',
            ' "RFQ_PR_GROUP".ds_po_number',
            ' "RFQ_PR_GROUP_DISTRIBUTION".cd_rfq_pr_group_distribution',
            ' "RFQ_PR_GROUP_DISTRIBUTION".cd_rfq_item_supplier_quotation',
            '( select ds_kind FROM "RFQ_ITEM_SUPPLIER_QUOTATION" WHERE cd_rfq_item_supplier_quotation =  "RFQ_PR_GROUP_DISTRIBUTION".cd_rfq_item_supplier_quotation) as ds_rfq_item_supplier_quotation',
            ' "RFQ_PR_GROUP_DISTRIBUTION".cd_rfq_item',
            '( select ds_reason_buy FROM "RFQ_ITEM" WHERE cd_rfq_item =  "RFQ_PR_GROUP_DISTRIBUTION".cd_rfq_item) as ds_rfq_item',
            ' "RFQ_PR_GROUP_DISTRIBUTION".nr_qtty_to_charge',
            '( "EQUIPMENT_DESIGN".ds_equipment_description_full ) as ds_equipment_design',
            '( "EQUIPMENT_DESIGN".ds_equipment_code_full || ( CASE WHEN "RFQ_ITEM".ds_equipment_design_code_complement IS NOT NULL THEN \'-\' || "RFQ_ITEM".ds_equipment_design_code_complement ELSE \'\' END) ) as ds_equipment_design_code',
            '( "EQUIPMENT_DESIGN".ds_equipment_description_full ) as ds_equipment_design',
            '("EQUIPMENT_DESIGN".ds_equipment_design ) as ds_equipment_design_description',
            ' ("RFQ_ITEM".ds_remarks) as ds_remarks_item',
            ' "RFQ_ITEM_SUPPLIER_QUOTATION".dt_update',
            ' "RFQ_ITEM".ds_reason_buy',
            ' "RFQ_ITEM_SUPPLIER_QUOTATION".ds_reason_to_choose_supplier',
            ' ( "SUPPLIER".ds_vendor_code:: text || \' - \' ||  "SUPPLIER".ds_supplier || COALESCE(( CASE WHEN "SUPPLIER".ds_supplier !=  COALESCE("SUPPLIER".ds_supplier_alt, "SUPPLIER".ds_supplier) THEN \' - \' || "SUPPLIER".ds_supplier_alt ELSE \'\' END ), \'\')  )  as ds_supplier ',
            " ( CASE WHEN \"RFQ_PR_GROUP\".ds_kind = 'N' THEN '$new' ELSE '$repair' END) as ds_kind_description ",
            ' ( "RFQ_ITEM_SUPPLIER_QUOTATION".ds_remarks ) as ds_remarks_quotation',
            '"RFQ_ITEM_SUPPLIER".ds_supplier_equipment_description',
            '"RFQ_ITEM_SUPPLIER".ds_supplier_equipment_part_number',
            '( select ds_payment_term FROM "PAYMENT_TERM" WHERE cd_payment_term =  "RFQ_ITEM_SUPPLIER_QUOTATION".cd_payment_term) as ds_payment_term',
            ' "RFQ_ITEM".ds_equipment_design_desc_complement ',
            '"RFQ_REQUEST_TYPE".ds_rfq_request_type',
            ' "RFQ_ITEM".ds_brand',
            '( select ds_unit_measure FROM "UNIT_MEASURE"WHERE "UNIT_MEASURE".cd_unit_measure =  "RFQ_ITEM".cd_unit_measure) as ds_unit_measure',
            '( "HUMAN_RESOURCE".ds_human_resource_full ) as ds_human_resource_applicant',
            '( select datedbtogrid(a.dt_define)
                    FROM rfq."RFQ_APPROVAL_STEPS" a,
                   (select max(y.cd_rfq_approval_steps) as cd_rfq_approval_steps
                   FROM rfq."RFQ_APPROVAL_STEPS" y, 
                        public."APPROVAL_STEPS_CONFIG" c
                  WHERE c.ds_internal_code = \'toPR\'
                    AND y.cd_rfq           = "RFQ".cd_rfq
                    AND y.cd_approval_steps_config = c.cd_approval_steps_config ) as l
           WHERE a.cd_rfq_approval_steps = l.cd_rfq_approval_steps
             AND a.cd_approval_status    = 1 ) as dt_pr_released',
            '"RFQ_ITEM_SUPPLIER_QUOTATION".nr_leadtime',
            
            ' (COALESCE ( ( select (sum(case t.ds_type when \'I\' then r.nr_qty else 0 end))-(sum(case t.ds_type when \'O\' then r.nr_qty else 0 end)) from rfq."RFQ_PR_INCOMING_OUTCOMING" r,rfq."RFQ_PR_INCOMING_OUTCOMING_TYPE" t where 
                  t.cd_rfq_pr_incoming_outcoming_type=r.cd_rfq_pr_incoming_outcoming_type and r.cd_rfq_pr_group_distribution="RFQ_PR_GROUP_DISTRIBUTION".cd_rfq_pr_group_distribution), 0)) as nr_total_received',

            '(COALESCE ( ( select nr_qtty_to_charge-sum(case t.ds_type when \'I\' then r.nr_qty else 0 end) +sum(case t.ds_type when \'O\' then r.nr_qty else 0 end)
            from rfq."RFQ_PR_INCOMING_OUTCOMING" r,rfq."RFQ_PR_INCOMING_OUTCOMING_TYPE" t where 
            t.cd_rfq_pr_incoming_outcoming_type=r.cd_rfq_pr_incoming_outcoming_type and r.cd_rfq_pr_group_distribution="RFQ_PR_GROUP_DISTRIBUTION".cd_rfq_pr_group_distribution), "RFQ_PR_GROUP_DISTRIBUTION".nr_qtty_to_charge)) as nr_balance',
            

            $this->incomingmodel->getJsonColumn('inputOutput', ' WHERE "RFQ_PR_INCOMING_OUTCOMING".cd_rfq_pr_group_distribution = "RFQ_PR_GROUP_DISTRIBUTION".cd_rfq_pr_group_distribution  ')

        );

        if ($fl_rfq_see_price == 'Y') {
            $this->fieldsforGridFull[] = '( "RFQ_PR_GROUP".nr_total_qty ) as nr_total_qty_pr';
            $this->fieldsforGridFull[] = '( "RFQ_PR_GROUP".nr_total_price ) as nr_total_price_pr';
            $this->fieldsforGridFull[] = '( "RFQ_PR_GROUP".nr_total_price_with_tax ) as nr_total_price_with_tax_pr';
            $this->fieldsforGridFull[] = '( "RFQ_PR_GROUP".nr_total_price_rmb ) as nr_total_price_rmb_pr';
            $this->fieldsforGridFull[] = '( "RFQ_PR_GROUP".nr_total_price_rmb_with_tax ) as nr_total_price_rmb_with_tax_pr';
            $this->fieldsforGridFull[] = '"RFQ_PR_GROUP_DISTRIBUTION".nr_total_price';
            $this->fieldsforGridFull[] = '"RFQ_PR_GROUP_DISTRIBUTION".nr_total_price_with_tax';
            $this->fieldsforGridFull[] = ' "RFQ_PR_GROUP_DISTRIBUTION".nr_total_price_rmb';
            $this->fieldsforGridFull[] = '"RFQ_PR_GROUP_DISTRIBUTION".nr_price';
            $this->fieldsforGridFull[] = '"RFQ_PR_GROUP_DISTRIBUTION".nr_price_with_tax';
            $this->fieldsforGridFull[] = '"RFQ_PR_GROUP_DISTRIBUTION".nr_price_with_tax_default_currency';
            $this->fieldsforGridFull[] = '"RFQ_PR_GROUP_DISTRIBUTION".nr_price_default_currency';
            $this->fieldsforGridFull[] = '"RFQ_PR_GROUP_DISTRIBUTION".nr_total_price_rmb_with_tax';

        }

        $joinFull = array(
            'JOIN "RFQ_ITEM_SUPPLIER_QUOTATION" ON ( "RFQ_ITEM_SUPPLIER_QUOTATION".cd_rfq_item_supplier_quotation = "RFQ_PR_GROUP_DISTRIBUTION".cd_rfq_item_supplier_quotation )',
            'JOIN "RFQ_ITEM" ON ( "RFQ_ITEM".cd_rfq_item = "RFQ_PR_GROUP_DISTRIBUTION".cd_rfq_item )',
            'JOIN "RFQ" ON ( "RFQ".cd_rfq = "RFQ_ITEM".cd_rfq )',
            'JOIN "RFQ_ITEM_SUPPLIER" ON ("RFQ_ITEM_SUPPLIER".cd_rfq_item_supplier = "RFQ_ITEM_SUPPLIER_QUOTATION".cd_rfq_item_supplier)',
            'JOIN "EQUIPMENT_DESIGN" ON ("EQUIPMENT_DESIGN".cd_equipment_design = "RFQ_ITEM".cd_equipment_design )',
            'JOIN "RFQ_REQUEST_TYPE" ON ("RFQ_REQUEST_TYPE".cd_rfq_request_type =  "RFQ_ITEM".cd_rfq_request_type)',
            'JOIN "RFQ_PR_GROUP" ON ("RFQ_PR_GROUP".cd_rfq_pr_group =  "RFQ_PR_GROUP_DISTRIBUTION".cd_rfq_pr_group)',
            'JOIN "SUPPLIER" ON ("SUPPLIER".cd_supplier =  "RFQ_PR_GROUP".cd_supplier)',
            'JOIN "HUMAN_RESOURCE" ON ( "HUMAN_RESOURCE".cd_human_resource = "RFQ".cd_human_resource_applicant ) ',
        );


        $prd = $this->session->userdata('system_product_category');


        if ($this->getCdbhelper()->getUserPermission('fl_rfq_see_all_purchase_request') == 'Y') {
            $fixedwhere = "";
        } else {
            $fixedwhere = " AND EXISTS ( SELECT 1 FROM getUsersByPermissionForSelect(cd_human_resource_applicant, 'fl_rfq_create_and_update;fl_rfq_manager;fl_rfq_purchase_department;fl_rfq_quotation_release;fl_rfq_release_pr;fl_rfq_release_to_quote;fl_rfq_team_approval;fl_rfq_department_manager' ) x WHERE x.cd_human_resource = $hmcode )";
        }

        $this->retrOptionsFull = array("fieldrecid" => $this->pk_field,
            "stylecond" => "(CASE WHEN \"RFQ_PR_GROUP\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGridFull),
            "json" => true,
            'join' => $joinFull,
            'forcedwhere' => $fixedwhere . "  AND  \"RFQ\".cd_system_product_category = $prd   "
        );


        /*Elastic*/
        $this->fieldsforGridElastic = array(
            ' ("RFQ_PR_GROUP".cd_rfq_pr_group)  as id_pr_group',
            ' ("RFQ_PR_GROUP".cd_rfq) as id_rfq ',
            ' "RFQ_PR_GROUP_DISTRIBUTION".cd_rfq_item as id_rfq_item',
            ' ( "SUPPLIER".ds_vendor_code:: text || \' - \' ||  "SUPPLIER".ds_supplier || COALESCE(( CASE WHEN "SUPPLIER".ds_supplier !=  COALESCE("SUPPLIER".ds_supplier_alt, "SUPPLIER".ds_supplier) THEN \' - \' || "SUPPLIER".ds_supplier_alt ELSE \'\' END ), \'\')  )  as "Supplier" ',
            ' "RFQ_PR_GROUP".cd_currency',
            '( select ds_currency FROM "CURRENCY" WHERE cd_currency =  "RFQ_PR_GROUP".cd_currency) as "Currency"',
            '(CASE WHEN "RFQ".fl_is_urgent = \'Y\' THEN 1 ELSE 0 END) as "Is Urgent"',
            '( select ds_department_cost_center FROM "DEPARTMENT_COST_CENTER" WHERE cd_department_cost_center =  "RFQ_PR_GROUP".cd_department_cost_center) as "Department"',
            ' ("RFQ_PR_GROUP".ds_project_number) as "Project #"',
            ' ("RFQ_PR_GROUP".ds_project_model_number) as "Project Model#"',
            ' ( "RFQ_PR_GROUP".nr_total_qty ) as "Total Quantity PR"',
            ' ( "RFQ_PR_GROUP".nr_total_price ) as "Total Price PR"',
            ' ( "RFQ_PR_GROUP".nr_total_price_with_tax ) as "Total Price with Tax PR" ',
            ' ( "RFQ_PR_GROUP".nr_total_price_rmb ) as "Total Price RMB PR"',
            ' ( "RFQ_PR_GROUP".nr_total_price_rmb_with_tax ) as "Total Price RMB With Tax PR"',
            ' ("RFQ_PR_GROUP".ds_pr_number) as "PR #" ',
            ' ("RFQ_PR_GROUP".ds_por_reference) as "POR Reference"',
            '( select ds_department_account_code FROM "DEPARTMENT_ACCOUNT_CODE" WHERE cd_department_account_code =  "RFQ_PR_GROUP".cd_department_account_code) as "Account Code"',
            '( select ds_expense_type FROM "EXPENSE_TYPE" WHERE cd_expense_type =  "RFQ_PR_GROUP".cd_expense_type) as "Expense Type"',
            ' ("RFQ_PR_GROUP".ds_remarks) as "PR Remarks" ',
            ' ("RFQ_PR_GROUP".ds_po_number) as "PO #"',
            ' ("RFQ_PR_GROUP_DISTRIBUTION".nr_qtty_to_charge) as "Quantity to Buy"',
            ' ("RFQ_PR_GROUP_DISTRIBUTION".nr_total_price ) as "Total Price"',
            ' ( "RFQ_PR_GROUP_DISTRIBUTION".nr_total_price_with_tax) as "Total Price with Tax"',
            ' ("RFQ_PR_GROUP_DISTRIBUTION".nr_total_price_rmb_with_tax) as "Total Price RMB With Tax"',
            ' ( "RFQ_PR_GROUP_DISTRIBUTION".nr_total_price_rmb ) as "Total Price RMB"',
            ' ("RFQ_PR_GROUP_DISTRIBUTION".nr_price ) as "Unit Price"',
            ' ("RFQ_PR_GROUP_DISTRIBUTION".nr_price_with_tax) as "Unit Price With Tax" ',
            ' ("RFQ_PR_GROUP_DISTRIBUTION".nr_price_with_tax_default_currency) as "Unit Price with Tax RMB"',
            ' ("RFQ_PR_GROUP_DISTRIBUTION".nr_price_default_currency) as "Unit Price RMB"',

            ' ( "EQUIPMENT_DESIGN".ds_equipment_code_full || ( CASE WHEN "RFQ_ITEM".ds_equipment_design_code_complement IS NOT NULL THEN \'-\' || "RFQ_ITEM".ds_equipment_design_code_complement ELSE \'\' END) ) as "Equipment Code" ',
            ' ( "EQUIPMENT_DESIGN".ds_equipment_code_full || ( CASE WHEN "RFQ_ITEM".ds_equipment_design_code_complement IS NOT NULL THEN \'-\' || "RFQ_ITEM".ds_equipment_design_code_complement ELSE \'\' END) || \' \' || COALESCE( "RFQ_ITEM".ds_equipment_design_desc_complement,"EQUIPMENT_DESIGN".ds_equipment_design,ds_equipment_design_desc_complement) ) as "Equipment"',
            ' ("RFQ_ITEM".ds_remarks) as "Remarks Item"',
            ' ("RFQ_ITEM".ds_reason_buy) as "Reason to Buy"',
            ' ("RFQ_ITEM_SUPPLIER_QUOTATION".ds_reason_to_choose_supplier) as "Reason to Choose Supplier"',
            " ( CASE WHEN \"RFQ_PR_GROUP\".ds_kind = 'N' THEN '$new' ELSE '$repair' END) as \"Kind\" ",
            ' ( "RFQ_ITEM_SUPPLIER_QUOTATION".ds_remarks ) as "Remarks Quotation"',
            '("RFQ_ITEM_SUPPLIER".ds_supplier_equipment_description) as "Supplier Equipment Description"',
            '( "RFQ_ITEM_SUPPLIER".ds_supplier_equipment_part_number) as "Supplier Equipment#"',
            '( select ds_payment_term FROM "PAYMENT_TERM" WHERE cd_payment_term =  "RFQ_ITEM_SUPPLIER_QUOTATION".cd_payment_term) as "Payment Term"',
            '( "RFQ_REQUEST_TYPE".ds_rfq_request_type ) as "Type" ',
            ' ("RFQ_ITEM".ds_brand) as "Brand"',
            '( select ds_unit_measure FROM "UNIT_MEASURE"WHERE "UNIT_MEASURE".cd_unit_measure =  "RFQ_ITEM".cd_unit_measure) as "Unit Measure"',
            '( "HUMAN_RESOURCE".ds_human_resource_full ) as "Applicant"',
            '( select a.dt_define
                    FROM rfq."RFQ_APPROVAL_STEPS" a,
                   (select max(y.cd_rfq_approval_steps) as cd_rfq_approval_steps
                   FROM rfq."RFQ_APPROVAL_STEPS" y, 
                        public."APPROVAL_STEPS_CONFIG" c
                  WHERE c.ds_internal_code = \'toPR\'
                    AND y.cd_rfq           = "RFQ".cd_rfq
                    AND y.cd_approval_steps_config = c.cd_approval_steps_config ) as l
           WHERE a.cd_rfq_approval_steps = l.cd_rfq_approval_steps
             AND a.cd_approval_status    = 1 ) as "PR Released Date"',
            '( "RFQ_ITEM_SUPPLIER_QUOTATION".nr_leadtime ) as "Leadtime"',
            '("RFQ".dt_request) as "Requested Date"',
            '("RFQ".dt_deactivated) as "Cancel Date"',
            '("RFQ".ds_cancel_reason) as "Cancel Reason"',
            '("RFQ".dt_requested_complete) as "Purchase Deadline"',
            '(  CASE WHEN "PROJECT".cd_project IS NULL THEN ( SELECT COALESCE(min(c.ds_project_description), \'MISSING DESCRIPTION\') as ds_project_description FROM rfq."RFQ_COST_CENTER" c WHERE c.cd_rfq_pr_group = "RFQ_PR_GROUP".cd_rfq_pr_group ) ELSE "PROJECT".ds_project || \' - \' || "PROJECT_MODEL".ds_project_model END)  as "Project Description"'
        );

        $this->load->model('approval_steps_config_model', 'approvalmodel');
        $info = $this->approvalmodel->getApprovalSteps('RFQ', -1);

        foreach ($info as $key => $value) {
            array_push($this->fieldsforGridElastic, ' ( SELECT min(x.dt_define) FROM "RFQ_APPROVAL_STEPS" x WHERE x.cd_rfq = "RFQ".cd_rfq AND x.cd_approval_steps_config =  ' . $value['cd_approval_steps_config'] . ' AND x.cd_approval_status = 1 ) as "' . $value['ds_approval_steps_config'] . '" ');
        }


        $joinElastic = array(
            'JOIN "RFQ_ITEM_SUPPLIER_QUOTATION" ON ( "RFQ_ITEM_SUPPLIER_QUOTATION".cd_rfq_item_supplier_quotation = "RFQ_PR_GROUP_DISTRIBUTION".cd_rfq_item_supplier_quotation )',
            'JOIN "RFQ_ITEM" ON ( "RFQ_ITEM".cd_rfq_item = "RFQ_PR_GROUP_DISTRIBUTION".cd_rfq_item )',
            'JOIN "RFQ" ON ( "RFQ".cd_rfq = "RFQ_ITEM".cd_rfq )',
            'JOIN "RFQ_ITEM_SUPPLIER" ON ("RFQ_ITEM_SUPPLIER".cd_rfq_item_supplier = "RFQ_ITEM_SUPPLIER_QUOTATION".cd_rfq_item_supplier)',
            'JOIN "EQUIPMENT_DESIGN" ON ("EQUIPMENT_DESIGN".cd_equipment_design = "RFQ_ITEM".cd_equipment_design )',
            'JOIN "RFQ_REQUEST_TYPE" ON ("RFQ_REQUEST_TYPE".cd_rfq_request_type =  "RFQ_ITEM".cd_rfq_request_type)',
            'JOIN "RFQ_PR_GROUP" ON ("RFQ_PR_GROUP".cd_rfq_pr_group =  "RFQ_PR_GROUP_DISTRIBUTION".cd_rfq_pr_group)',
            'JOIN "SUPPLIER" ON ("SUPPLIER".cd_supplier =  "RFQ_PR_GROUP".cd_supplier)',
            'JOIN "HUMAN_RESOURCE" ON ( "HUMAN_RESOURCE".cd_human_resource = "RFQ".cd_human_resource_applicant ) ',
            'LEFT OUTER JOIN "PROJECT_MODEL" ON ("PROJECT_MODEL".cd_project_model =  "RFQ_PR_GROUP".cd_project_model)',
            'LEFT OUTER JOIN "PROJECT" ON ("PROJECT".cd_project =  "PROJECT_MODEL".cd_project)',
        );


        $this->retrOptionsElastic = array("fieldrecid" => $this->pk_field,
            //"stylecond" => "(CASE WHEN \"RFQ_PR_GROUP\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGridElastic),
            "json" => true,
            'join' => $joinElastic,
            'forcedwhere' => " AND  \"RFQ\".cd_system_product_category = 1  "
        );

        parent::__construct();
    }

}
