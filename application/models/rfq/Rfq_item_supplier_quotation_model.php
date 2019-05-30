<?php

include_once APPPATH . "models/modelBasicExtend.php";

class rfq_item_supplier_quotation_model extends modelBasicExtend {

    function __construct() {

        $this->table = "RFQ_ITEM_SUPPLIER_QUOTATION";

        $this->pk_field = '"RFQ_ITEM_SUPPLIER_QUOTATION".cd_rfq_item_supplier_quotation';
        $this->ds_field = "ds_rfq_item_supplier";
        $this->prodCatUnique = 'N';

        $this->sequence_obj = '"RFQ_ITEM_SUPPLIER_QUOTATION_cd_rfq_item_supplier_quotation_seq"';

        $this->controller = 'rfq/rfq_item_supplier_quotation';
        $language = $this->getCdbhelper()->getSettings('cd_system_languages');
        $trans = $this->getCdbhelper()->retTranslationDifKeys(array('new' => 'New', 'repair' => 'Repair'));
        $new = $trans['new'];
        $repair = $trans['repair'];
        $fl_rfq_see_price = $this->getCdbhelper()->getUserPermission('fl_rfq_see_price');


        $this->fieldsforGrid = [
            ' "RFQ_ITEM_SUPPLIER_QUOTATION".cd_rfq_item_supplier_quotation',
            ' "RFQ_ITEM_SUPPLIER_QUOTATION".cd_rfq_item_supplier',
            ' "RFQ_SUPPLIER".cd_rfq_supplier ',
            ' ( "SUPPLIER".ds_vendor_code:: text || \' - \' ||  "SUPPLIER".ds_supplier || COALESCE(( CASE WHEN "SUPPLIER".ds_supplier !=  COALESCE("SUPPLIER".ds_supplier_alt, "SUPPLIER".ds_supplier) THEN \' - \' || "SUPPLIER".ds_supplier_alt ELSE \'\' END ), \'\')  )  as ds_supplier ',
            ' "RFQ_ITEM".cd_equipment_design',
            '( "EQUIPMENT_DESIGN".ds_equipment_code_full || ( CASE WHEN "RFQ_ITEM".ds_equipment_design_code_complement IS NOT NULL THEN \'-\' || "RFQ_ITEM".ds_equipment_design_code_complement ELSE \'\' END) || \' \' || COALESCE( "RFQ_ITEM".ds_equipment_design_desc_complement,"EQUIPMENT_DESIGN".ds_equipment_design,ds_equipment_design_desc_complement) ) as ds_equipment_design',
            ' "RFQ_ITEM_SUPPLIER_QUOTATION".nr_round',
            '( select ds_currency FROM "CURRENCY" WHERE cd_currency =  "RFQ_ITEM_SUPPLIER_QUOTATION".cd_currency) as ds_currency',
            ' "RFQ_ITEM_SUPPLIER_QUOTATION".nr_moq',
            ' "RFQ_ITEM_SUPPLIER_QUOTATION".nr_leadtime',
            ' "RFQ_ITEM_SUPPLIER_QUOTATION".dt_expiring_date',
            ' "RFQ_ITEM_SUPPLIER_QUOTATION".cd_payment_term',
            '( select ds_payment_term FROM "PAYMENT_TERM" WHERE cd_payment_term =  "RFQ_ITEM_SUPPLIER_QUOTATION".cd_payment_term) as ds_payment_term',
            ' "RFQ_ITEM_SUPPLIER_QUOTATION".nr_warranty',
            ' "RFQ_ITEM_SUPPLIER_QUOTATION".dt_record',
            ' "RFQ_ITEM_SUPPLIER_QUOTATION".ds_kind',
            " ( CASE WHEN ds_kind = 'N' THEN '$new' ELSE '$repair' END) as ds_kind_description ",
            ' "RFQ_ITEM_SUPPLIER_QUOTATION".ds_remarks',
            '  COALESCE("CURRENCY_RATE".nr_currency_rate , 1) as nr_currency_rate',
            ' "RFQ_ITEM_SUPPLIER_QUOTATION".dt_update',
            ' "RFQ_ITEM_SUPPLIER".nr_tax ',
            ' "RFQ_ITEM_SUPPLIER_QUOTATION".cd_currency',
            ' "RFQ_ITEM_SUPPLIER_QUOTATION".nr_price',
            '"RFQ_ITEM_SUPPLIER".cd_supplier',
            '"RFQ_ITEM_SUPPLIER".ds_supplier_equipment_description',
            '"RFQ_ITEM_SUPPLIER".ds_supplier_equipment_part_number',
            '"RFQ_ITEM".cd_rfq_item',
            '"RFQ_ITEM_SUPPLIER_QUOTATION".nr_qtty_to_buy',
            '"RFQ_ITEM_SUPPLIER_QUOTATION".ds_reason_to_choose_supplier',
            ' "RFQ_ITEM_SUPPLIER".cd_rfq_item_supplier ',
            ' ( CASE WHEN "RFQ_SUPPLIER".nr_round = "RFQ_ITEM_SUPPLIER_QUOTATION".nr_round THEN \'Y\' ELSE \'N\' END) as fl_last  ',
            '( ROUND( "RFQ_ITEM_SUPPLIER_QUOTATION".nr_price *  COALESCE("CURRENCY_RATE".nr_currency_rate , 1), 4 )) as nr_price_default_currency',
            '( ROUND( "RFQ_ITEM_SUPPLIER_QUOTATION".nr_price *  ( ( 100 + "RFQ_ITEM_SUPPLIER".nr_tax ) / 100), 4 ) ) as nr_price_with_tax',
            '( ROUND( "RFQ_ITEM_SUPPLIER_QUOTATION".nr_price *  COALESCE("CURRENCY_RATE".nr_currency_rate , 1) * ( ( 100 + "RFQ_ITEM_SUPPLIER".nr_tax ) / 100), 4) ) as nr_price_with_tax_default_currency',
        ];

        $this->fieldsUpd = array("cd_rfq_item_supplier_quotation", "cd_rfq_item_supplier", "nr_round", "nr_price", "cd_currency", "nr_moq", "nr_leadtime", "dt_expiring_date", "cd_payment_term", "nr_warranty", "dt_record", "ds_kind", "ds_remarks", 'nr_qtty_to_buy', 'ds_reason_to_choose_supplier');

        $join = array('JOIN "RFQ_ITEM_SUPPLIER" ON ("RFQ_ITEM_SUPPLIER_QUOTATION".cd_rfq_item_supplier = "RFQ_ITEM_SUPPLIER".cd_rfq_item_supplier ) ',
            'JOIN "RFQ_ITEM" ON ("RFQ_ITEM".cd_rfq_item = "RFQ_ITEM_SUPPLIER".cd_rfq_item ) ',
            'JOIN "EQUIPMENT_DESIGN" ON ("EQUIPMENT_DESIGN".cd_equipment_design = "RFQ_ITEM".cd_equipment_design) ',
            'JOIN "RFQ_SUPPLIER" ON ("RFQ_SUPPLIER".cd_rfq = "RFQ_ITEM".cd_rfq AND "RFQ_SUPPLIER".cd_supplier = "RFQ_ITEM_SUPPLIER".cd_supplier) ',
            'JOIN "SUPPLIER" ON ("SUPPLIER".cd_supplier = "RFQ_ITEM_SUPPLIER".cd_supplier ) ',
            'LEFT OUTER JOIN "CURRENCY_RATE" ON ("CURRENCY_RATE".cd_currency_rate = "RFQ_ITEM_SUPPLIER_QUOTATION".cd_currency_rate ) ',
        );


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            //"stylecond" => "(CASE WHEN \"RFQ_ITEM_SUPPLIER_QUOTATION\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true,
            'join' => $join
        );




        $fixedwhere = " AND EXISTS ( SELECT 1 FROM getUsersByPermissionReference(cd_human_resource_applicant, 'fl_rfq_create_and_update;fl_rfq_manager;fl_rfq_purchase_department;fl_rfq_quotation_release;fl_rfq_release_pr;fl_rfq_release_to_quote;fl_rfq_team_approval', 'B' ) )";


        /* Quotation Location -- Information from PR view */
        $this->fieldsforPR = array(
            ' "RFQ".cd_rfq',
            ' "RFQ".cd_human_resource_applicant',
            '( "HUMAN_RESOURCE".ds_human_resource_full ) as ds_human_resource_applicant',
            '"HUMAN_RESOURCE".ds_e_mail',
            '"HUMAN_RESOURCE".ds_phone',
            ' "RFQ".dt_request',
            ' (to_char("RFQ".dt_request, \'yyyymmdd\')) as ds_request_date',
            ' "RFQ".dt_requested_complete',
            ' "RFQ".fl_is_urgent',
            ' "RFQ".cd_human_resource_purchase',
            '( select ds_human_resource_full FROM "HUMAN_RESOURCE" WHERE cd_human_resource =  "RFQ".cd_human_resource_purchase) as ds_human_resource_purchase',
            ' "RFQ".ds_comments',
            ' "RFQ".ds_wf_number',
            ' "RFQ".ds_rfq_number',
            ' COALESCE ( (SELECT ds_approval_steps_config from "APPROVAL_STEPS_CONFIG" WHERE "APPROVAL_STEPS_CONFIG".cd_approval_steps_config =  "RFQ_APPROVAL_STEPS".cd_approval_steps_config ), \'FINISHED\')  as ds_approval_steps_config_pending',
            ' "RFQ".dt_deactivated',
            ' "RFQ_ITEM".cd_rfq_item',
            ' "RFQ_ITEM".cd_rfq',
            ' "RFQ_ITEM".cd_equipment_design',
            '( "EQUIPMENT_DESIGN".ds_equipment_description_full ) as ds_equipment_design',
            '( "EQUIPMENT_DESIGN".ds_equipment_code_full || ( CASE WHEN "RFQ_ITEM".ds_equipment_design_code_complement IS NOT NULL THEN \'-\' || "RFQ_ITEM".ds_equipment_design_code_complement ELSE \'\' END) ) as ds_equipment_design_code',
            '("EQUIPMENT_DESIGN".ds_equipment_design ) as ds_equipment_design_description',
            ' "RFQ_ITEM".cd_rfq_request_type',
            '"RFQ_REQUEST_TYPE".ds_rfq_request_type',
            ' "RFQ_ITEM".ds_reason_buy',
            ' "RFQ_ITEM".nr_qtty_quote',
            ' (CASE WHEN COALESCE("RFQ_ITEM_SUPPLIER_QUOTATION".nr_qtty_to_buy, 0)  > 0 THEN 1 ELSE 0 END) as fl_buy',
            ' "RFQ_ITEM".dt_deadline',
            ' "RFQ_ITEM".ds_website',
            ' "RFQ_ITEM".ds_remarks',
            ' "RFQ_ITEM".ds_attached_image',
            ' "RFQ_ITEM".nr_estimated_annual',
            ' "RFQ_ITEM".ds_po_number',
            '"RFQ_ITEM".cd_unit_measure',
            ' "RFQ_ITEM".fl_need_sample',
            ' "RFQ_ITEM".dt_supplier_visit_deadline',
            '"RFQ_REQUEST_TYPE".fl_is_new',
            '"RFQ_REQUEST_TYPE".fl_is_repair',
            ' "RFQ_ITEM".ds_equipment_design_code_complement ',
            ' "RFQ_ITEM".ds_equipment_design_desc_complement ',
            '( select ds_unit_measure FROM "UNIT_MEASURE"WHERE "UNIT_MEASURE".cd_unit_measure =  "RFQ_ITEM".cd_unit_measure) as ds_unit_measure',
            ' "RFQ_ITEM_SUPPLIER_QUOTATION".ds_reason_to_choose_supplier',
            ' "RFQ_ITEM_SUPPLIER_QUOTATION".nr_qtty_to_buy',
            ' "RFQ_ITEM".ds_brand',
            ' "RFQ_ITEM_SUPPLIER_QUOTATION".cd_rfq_item_supplier_quotation',
            ' "RFQ_ITEM_SUPPLIER_QUOTATION".cd_rfq_item_supplier',
            ' ( "SUPPLIER".ds_vendor_code:: text || \' - \' ||  "SUPPLIER".ds_supplier || COALESCE(( CASE WHEN "SUPPLIER".ds_supplier !=  COALESCE("SUPPLIER".ds_supplier_alt, "SUPPLIER".ds_supplier) THEN \' - \' || "SUPPLIER".ds_supplier_alt ELSE \'\' END ), \'\')  )  as ds_supplier ',
            '( "EQUIPMENT_DESIGN".ds_equipment_description_full ) as ds_equipment_design',
            ' "RFQ_ITEM_SUPPLIER_QUOTATION".nr_round',
            '( select ds_currency FROM "CURRENCY" WHERE cd_currency =  "RFQ_ITEM_SUPPLIER_QUOTATION".cd_currency) as ds_currency',
            ' "RFQ_ITEM_SUPPLIER_QUOTATION".nr_moq',
            ' "RFQ_ITEM_SUPPLIER_QUOTATION".nr_leadtime',
            ' "RFQ_ITEM_SUPPLIER_QUOTATION".dt_expiring_date',
            ' "RFQ_ITEM_SUPPLIER_QUOTATION".cd_payment_term',
            '( select ds_payment_term FROM "PAYMENT_TERM" WHERE cd_payment_term =  "RFQ_ITEM_SUPPLIER_QUOTATION".cd_payment_term) as ds_payment_term',
            ' "RFQ_ITEM_SUPPLIER_QUOTATION".nr_warranty',
            ' "RFQ_ITEM_SUPPLIER_QUOTATION".dt_record',
            ' "RFQ_ITEM_SUPPLIER_QUOTATION".ds_kind',
            " ( CASE WHEN ds_kind = 'N' THEN '$new' ELSE '$repair' END) as ds_kind_description ",
            ' ( "RFQ_ITEM_SUPPLIER_QUOTATION".ds_remarks ) as ds_remarks_quotation',
            '  COALESCE("CURRENCY_RATE".nr_currency_rate , 1) as nr_currency_rate',
            ' "RFQ_ITEM_SUPPLIER_QUOTATION".dt_update',
            ' "RFQ_ITEM_SUPPLIER".nr_tax ',
            ' "RFQ_ITEM_SUPPLIER_QUOTATION".cd_currency',
            '"RFQ_ITEM_SUPPLIER".cd_supplier',
            '"RFQ_ITEM_SUPPLIER".ds_supplier_equipment_description',
            '"RFQ_ITEM_SUPPLIER".ds_supplier_equipment_part_number',
            '"RFQ_ITEM".cd_rfq_item',
            ' "RFQ_ITEM_SUPPLIER".cd_rfq_item_supplier ',
            ' "RFQ_ITEM_SUPPLIER_QUOTATION".nr_price',
            '( ROUND( "RFQ_ITEM_SUPPLIER_QUOTATION".nr_price *  COALESCE("CURRENCY_RATE".nr_currency_rate , 1), 4  )) as nr_price_default_currency',
            '( ROUND( "RFQ_ITEM_SUPPLIER_QUOTATION".nr_price *  ( ( 100 + "RFQ_ITEM_SUPPLIER".nr_tax ) / 100), 4 ) ) as nr_price_with_tax',
            '( ROUND( "RFQ_ITEM_SUPPLIER_QUOTATION".nr_price *  COALESCE("CURRENCY_RATE".nr_currency_rate , 1) * ( ( 100 + "RFQ_ITEM_SUPPLIER".nr_tax ) / 100), 4) ) as nr_price_with_tax_default_currency',
            '"RFQ_PR_GROUP_DISTRIBUTION".nr_qtty_to_charge',
            ' ( "RFQ_ITEM_SUPPLIER_QUOTATION".nr_price * "RFQ_PR_GROUP_DISTRIBUTION".nr_qtty_to_charge ) as nr_total_price',
            '( ROUND( "RFQ_ITEM_SUPPLIER_QUOTATION".nr_price *  COALESCE("CURRENCY_RATE".nr_currency_rate , 1), 2  )  * "RFQ_PR_GROUP_DISTRIBUTION".nr_qtty_to_charge ) as nr_total_price_rmb',
            '( ROUND( "RFQ_ITEM_SUPPLIER_QUOTATION".nr_price *  ( ( 100 + "RFQ_ITEM_SUPPLIER".nr_tax ) / 100), 2 )  * "RFQ_PR_GROUP_DISTRIBUTION".nr_qtty_to_charge  ) as nr_total_price_with_tax',
            '( ROUND( "RFQ_ITEM_SUPPLIER_QUOTATION".nr_price *  COALESCE("CURRENCY_RATE".nr_currency_rate , 1) * ( ( 100 + "RFQ_ITEM_SUPPLIER".nr_tax ) / 100), 2 ) * "RFQ_PR_GROUP_DISTRIBUTION".nr_qtty_to_charge  ) as nr_total_price_rmb_with_tax',
            '( select ds_department_cost_center FROM "DEPARTMENT_COST_CENTER" WHERE cd_department_cost_center =  "RFQ_COST_CENTER".cd_department_cost_center) as ds_department_cost_center',
            ' "RFQ_COST_CENTER".ds_project_number',
            ' "RFQ_COST_CENTER".ds_project_model_number',
        );



        $joinPR = array(
            'JOIN "RFQ_ITEM_SUPPLIER" ON ("RFQ_ITEM_SUPPLIER".cd_rfq_item_supplier = "RFQ_ITEM_SUPPLIER_QUOTATION".cd_rfq_item_supplier)',
            'JOIN "RFQ_ITEM" ON ( "RFQ_ITEM".cd_rfq_item = "RFQ_ITEM_SUPPLIER".cd_rfq_item )',
            'JOIN "RFQ_COST_CENTER" ON ( "RFQ_ITEM".cd_rfq_item = "RFQ_COST_CENTER".cd_rfq_item )',
            'JOIN "RFQ" ON ("RFQ".cd_rfq = "RFQ_ITEM".cd_rfq)',
            'JOIN "HUMAN_RESOURCE" ON ( "HUMAN_RESOURCE".cd_human_resource = "RFQ".cd_human_resource_applicant ) ',
            'LEFT OUTER JOIN "RFQ_APPROVAL_STEPS" ON ("RFQ_APPROVAL_STEPS".cd_rfq = "RFQ".cd_rfq AND "RFQ_APPROVAL_STEPS".cd_approval_status IS NULL)   ',
            'JOIN "EQUIPMENT_DESIGN" ON ("EQUIPMENT_DESIGN".cd_equipment_design = "RFQ_ITEM".cd_equipment_design )',
            'JOIN "RFQ_REQUEST_TYPE" ON ("RFQ_REQUEST_TYPE".cd_rfq_request_type =  "RFQ_ITEM".cd_rfq_request_type)',
            'LEFT OUTER JOIN "CURRENCY_RATE" ON ("CURRENCY_RATE".cd_currency_rate = "RFQ_ITEM_SUPPLIER_QUOTATION".cd_currency_rate )',
            'JOIN "SUPPLIER" ON ("SUPPLIER".cd_supplier = "RFQ_ITEM_SUPPLIER".cd_supplier ) ',
            'JOIN "RFQ_PR_GROUP_DISTRIBUTION" ON ("RFQ_ITEM".cd_rfq_item = "RFQ_PR_GROUP_DISTRIBUTION".cd_rfq_item AND  "RFQ_ITEM_SUPPLIER_QUOTATION".cd_rfq_item_supplier_quotation =  "RFQ_PR_GROUP_DISTRIBUTION".cd_rfq_item_supplier_quotation)'
        );

        $this->retrOptionsPR = array("fieldrecid" => '"RFQ_ITEM_SUPPLIER_QUOTATION".cd_rfq_item_supplier_quotation',
            "stylecond" => "(CASE WHEN \"RFQ\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforPR),
            "json" => true,
            'join' => $joinPR,
            'forcedwhere' => $fixedwhere
        );




        /* --------------------------------------------------------------------------------- */

        
        $this->fieldsforGridGetOldQuot = [
            ' sourceItem.cd_rfq_item as cd_rfq_item_source ',
            ' "RFQ_ITEM_SUPPLIER_QUOTATION".cd_rfq_item_supplier_quotation',
            ' "RFQ_ITEM_SUPPLIER_QUOTATION".cd_rfq_item_supplier',
            ' "RFQ_SUPPLIER".cd_rfq_supplier ',
            ' ( "SUPPLIER".ds_vendor_code:: text || \' - \' ||  "SUPPLIER".ds_supplier || COALESCE(( CASE WHEN "SUPPLIER".ds_supplier !=  COALESCE("SUPPLIER".ds_supplier_alt, "SUPPLIER".ds_supplier) THEN \' - \' || "SUPPLIER".ds_supplier_alt ELSE \'\' END ), \'\')  )  as ds_supplier ',
            ' "RFQ_ITEM".cd_equipment_design',
            '( "EQUIPMENT_DESIGN".ds_equipment_code_full || ( CASE WHEN "RFQ_ITEM".ds_equipment_design_code_complement IS NOT NULL THEN \'-\' || "RFQ_ITEM".ds_equipment_design_code_complement ELSE \'\' END) || \' \' || COALESCE( "RFQ_ITEM".ds_equipment_design_desc_complement,"EQUIPMENT_DESIGN".ds_equipment_design,"RFQ_ITEM".ds_equipment_design_desc_complement) ) as ds_equipment_design',
            ' "RFQ_ITEM_SUPPLIER_QUOTATION".nr_round',
            '( select ds_currency FROM "CURRENCY" WHERE cd_currency =  "RFQ_ITEM_SUPPLIER_QUOTATION".cd_currency) as ds_currency',
            ' "RFQ_ITEM_SUPPLIER_QUOTATION".nr_moq',
            ' "RFQ_ITEM_SUPPLIER_QUOTATION".nr_leadtime',
            ' "RFQ_ITEM_SUPPLIER_QUOTATION".dt_expiring_date',
            ' "RFQ_ITEM_SUPPLIER_QUOTATION".cd_payment_term',
            '( select ds_payment_term FROM "PAYMENT_TERM" WHERE cd_payment_term =  "RFQ_ITEM_SUPPLIER_QUOTATION".cd_payment_term) as ds_payment_term',
            ' "RFQ_ITEM_SUPPLIER_QUOTATION".nr_warranty',
            ' "RFQ_ITEM_SUPPLIER_QUOTATION".dt_record',
            ' "RFQ_ITEM_SUPPLIER_QUOTATION".ds_kind',
            ' "RFQ_ITEM_SUPPLIER".nr_tax ',
            " ( CASE WHEN ds_kind = 'N' THEN '$new' ELSE '$repair' END) as ds_kind_description ",
            ' "RFQ_ITEM_SUPPLIER_QUOTATION".ds_remarks',
            '  COALESCE("CURRENCY_RATE".nr_currency_rate , 1) as nr_currency_rate',
            ' "RFQ_ITEM_SUPPLIER_QUOTATION".dt_update',
            ' "RFQ_ITEM_SUPPLIER".nr_tax ',
            ' "RFQ_ITEM_SUPPLIER_QUOTATION".cd_currency',
            ' "RFQ_ITEM_SUPPLIER_QUOTATION".nr_price',
            '"RFQ_ITEM_SUPPLIER".cd_supplier',
            '"RFQ_ITEM_SUPPLIER".ds_supplier_equipment_description',
            '"RFQ_ITEM_SUPPLIER".ds_supplier_equipment_part_number',
            '"RFQ_ITEM".cd_rfq_item',
            '"RFQ_ITEM".cd_rfq',
            '"RFQ_ITEM_SUPPLIER_QUOTATION".nr_qtty_to_buy',
            '"RFQ_ITEM_SUPPLIER_QUOTATION".ds_reason_to_choose_supplier',
            ' "RFQ_ITEM_SUPPLIER".cd_rfq_item_supplier ',
            '(SELECT COUNT(1) FROM "RFQ_QUOTE_DATA_DOCUMENT_REPOSITORY" x WHERE x.cd_rfq = "RFQ_ITEM".cd_rfq ) as nr_count_attach',
            ' (CASE WHEN sourceItem.ds_equipment_design_code_complement IS NOT DISTINCT FROM "RFQ_ITEM".ds_equipment_design_code_complement THEN 1 ELSE 0 END) as nr_equal_order',
            '( select ds_unit_measure FROM "UNIT_MEASURE"WHERE "UNIT_MEASURE".cd_unit_measure =  "RFQ_ITEM".cd_unit_measure) as ds_unit_measure',
            ' ( CASE WHEN "RFQ_SUPPLIER".nr_round = "RFQ_ITEM_SUPPLIER_QUOTATION".nr_round THEN \'Y\' ELSE \'N\' END) as fl_last  ',
            '( ROUND( "RFQ_ITEM_SUPPLIER_QUOTATION".nr_price *  COALESCE("CURRENCY_RATE".nr_currency_rate , 1), 4 )) as nr_price_default_currency',
            '( ROUND( "RFQ_ITEM_SUPPLIER_QUOTATION".nr_price *  ( ( 100 + "RFQ_ITEM_SUPPLIER".nr_tax ) / 100), 4 ) ) as nr_price_with_tax',
            '( ROUND( "RFQ_ITEM_SUPPLIER_QUOTATION".nr_price *  COALESCE("CURRENCY_RATE".nr_currency_rate , 1) * ( ( 100 + "RFQ_ITEM_SUPPLIER".nr_tax ) / 100), 4) ) as nr_price_with_tax_default_currency',
        ];




        $joinGetOldQuot = array('JOIN "RFQ_ITEM_SUPPLIER" ON ("RFQ_ITEM_SUPPLIER_QUOTATION".cd_rfq_item_supplier = "RFQ_ITEM_SUPPLIER".cd_rfq_item_supplier ) ',
            'JOIN "RFQ_ITEM" ON ("RFQ_ITEM".cd_rfq_item = "RFQ_ITEM_SUPPLIER".cd_rfq_item ) ',
            'JOIN "EQUIPMENT_DESIGN" ON ("EQUIPMENT_DESIGN".cd_equipment_design = "RFQ_ITEM".cd_equipment_design) ',
            'JOIN "RFQ_SUPPLIER" ON ("RFQ_SUPPLIER".cd_rfq = "RFQ_ITEM".cd_rfq AND "RFQ_SUPPLIER".cd_supplier = "RFQ_ITEM_SUPPLIER".cd_supplier) ',
            'JOIN "SUPPLIER" ON ("SUPPLIER".cd_supplier = "RFQ_ITEM_SUPPLIER".cd_supplier ) ',
            'LEFT OUTER JOIN "CURRENCY_RATE" ON ("CURRENCY_RATE".cd_currency_rate = "RFQ_ITEM_SUPPLIER_QUOTATION".cd_currency_rate ) ',
            'JOIN "RFQ_ITEM" sourceItem ON ( "RFQ_ITEM".cd_equipment_design = sourceItem.cd_equipment_design ) ',
        );


        $this->retrOptionsGetOldQuot = array("fieldrecid" => '(sourceItem.cd_rfq_item::text || \'-\' || "RFQ_ITEM".cd_rfq_item::text || \'-\' || "RFQ_ITEM_SUPPLIER_QUOTATION".cd_rfq_item_supplier_quotation::text )',
            //"stylecond" => "(CASE WHEN \"RFQ_ITEM_SUPPLIER_QUOTATION\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGridGetOldQuot),
            "json" => true,
            'join' => $joinGetOldQuot,
            'forcedwhere' => " AND '$fl_rfq_see_price' = 'Y' "
        );










        parent::__construct();
    }

}
