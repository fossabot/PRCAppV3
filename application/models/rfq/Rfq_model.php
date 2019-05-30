<?php

include_once APPPATH . "models/modelBasicExtend.php";

class rfq_model extends modelBasicExtend {

    function __construct() {

        $this->table = "RFQ";

        $this->pk_field = '"RFQ".cd_rfq';
        $this->ds_field = "ds_human_resource_applicant";
        $this->prodCatUnique = 'Y';
        $this->orderByDefault = 'ORDER BY ds_request_date desc';

        $this->sequence_obj = '"RFQ_cd_rfq_seq"';

        $this->controller = 'rfq/rfq';

        $hmcode = $this->session->userdata('cd_human_resource');
        $language = $this->getCdbhelper()->getSettings('cd_system_languages');
        $trans = $this->getCdbhelper()->retTranslationDifKeys(array('new' => 'New', 'repair' => 'Repair'));
        $new = $trans['new'];
        $repair = $trans['repair'];
        $fl_rfq_see_price = $this->getCdbhelper()->getUserPermission('fl_rfq_see_price');


        if($this->getCdbhelper()->getUserPermission('fl_rfq_see_all_purchase_request') == 'Y')
        {
            $fixedwhere ="";
        }
        else {
            $fixedwhere = " AND EXISTS ( SELECT 1 FROM getUsersByPermissionForSelect(cd_human_resource_applicant, 'fl_rfq_create_and_update;fl_rfq_manager;fl_rfq_purchase_department;fl_rfq_quotation_release;fl_rfq_release_pr;fl_rfq_release_to_quote;fl_rfq_team_approval;fl_rfq_department_manager' ) x WHERE x.cd_human_resource = $hmcode )";
            //die(print_r($fixedwhere));
        }

        $this->fieldsforGrid = array(
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
            ' "RFQ".ds_cancel_reason',
        );

        $this->load->model('approval_steps_config_model', 'approvalmodel');
        $info = $this->approvalmodel->getApprovalSteps('RFQ', -1);

        foreach ($info as $key => $value) {
            array_push($this->fieldsforGrid, ' ( SELECT datedbtogrid(x.dt_define) FROM "RFQ_APPROVAL_STEPS" x WHERE x.cd_rfq = "RFQ".cd_rfq AND x.cd_approval_steps_config = ' . $value['cd_approval_steps_config'] . ' AND x.dt_record = ( SELECT max(y.dt_record) FROM "RFQ_APPROVAL_STEPS" y where y.cd_rfq = x.cd_rfq AND y.cd_approval_steps_config = ' . $value['cd_approval_steps_config'] . ')   ) as ds_step_info_' . $value['cd_approval_steps_config']);
        }





        $this->fieldsUpd = array("cd_rfq", "cd_human_resource_applicant", "dt_request", "dt_requested_complete", "fl_is_urgent", "cd_human_resource_purchase", "ds_comments", "ds_wf_number", "ds_rfq_number", "dt_deactivated", "ds_cancel_reason");

        $join = array('JOIN "RFQ_ITEM"       ON ( "RFQ_ITEM".cd_rfq = "RFQ".cd_rfq ) ',
            'JOIN "HUMAN_RESOURCE" ON ( "HUMAN_RESOURCE".cd_human_resource = "RFQ".cd_human_resource_applicant ) ',
            'LEFT OUTER JOIN "RFQ_APPROVAL_STEPS" ON ("RFQ_APPROVAL_STEPS".cd_rfq = "RFQ".cd_rfq AND "RFQ_APPROVAL_STEPS".cd_approval_status IS NULL)   '
        );





        $this->retrOptions = array("fieldrecid" => '"RFQ".cd_rfq',
            "stylecond" => "(CASE WHEN \"RFQ\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true,
            'join' => $join,
            'distinct' => true,
            'forcedwhere' => $fixedwhere
        );

        /* For Quotation Screen  */
        $this->fieldsforQUOSCR = array(
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
            ' "RFQ".ds_cancel_reason',
            ' "RFQ_ITEM".cd_rfq_item',
            ' "RFQ_ITEM".cd_rfq',
            ' "RFQ_ITEM".cd_equipment_design',
            '(getRfqItemCostDepartment("RFQ_ITEM".cd_rfq_item) ) as ds_dep_cost',
            '( "EQUIPMENT_DESIGN".ds_equipment_code_full || ( CASE WHEN "RFQ_ITEM".ds_equipment_design_code_complement IS NOT NULL THEN \'-\' || "RFQ_ITEM".ds_equipment_design_code_complement ELSE \'\' END) || \' \' || COALESCE( "RFQ_ITEM".ds_equipment_design_desc_complement,"EQUIPMENT_DESIGN".ds_equipment_design,ds_equipment_design_desc_complement) ) as ds_equipment_design',
            '( "EQUIPMENT_DESIGN".ds_equipment_code_full || ( CASE WHEN "RFQ_ITEM".ds_equipment_design_code_complement IS NOT NULL THEN \'-\' || "RFQ_ITEM".ds_equipment_design_code_complement ELSE \'\' END) ) as ds_equipment_design_code',
            '("EQUIPMENT_DESIGN".ds_equipment_design ) as ds_equipment_design_description',
            ' "RFQ_ITEM".cd_rfq_request_type',
            '"RFQ_REQUEST_TYPE".ds_rfq_request_type',
            ' "RFQ_ITEM".ds_reason_buy',
            ' "RFQ_ITEM".nr_qtty_quote',
            ' (CASE WHEN COALESCE("RFQ_ITEM_SUPPLIER_QUOTATION".nr_qtty_to_buy, 0)  > 0 THEN 1 ELSE 0 END) as fl_buy',
            ' "RFQ_ITEM".dt_deadline',
            ' "RFQ_ITEM".ds_website',
            ' ( "RFQ_ITEM".ds_remarks) as ds_remarks_item',
            ' "RFQ_ITEM".ds_attached_image',
            ' "RFQ_ITEM".nr_estimated_annual',
            ' "RFQ_ITEM".ds_po_number',
            '"RFQ_ITEM".cd_unit_measure',
            ' "RFQ_ITEM".fl_need_sample',
            ' "RFQ_ITEM".dt_supplier_visit_deadline',
            //' ( getRfqItemSampleInformation("RFQ_ITEM".cd_rfq_item)) as ds_sample_info',
            //'"RFQ_REQUEST_TYPE".fl_is_new',
            //'"RFQ_REQUEST_TYPE".fl_is_repair',
            ' "RFQ_ITEM".ds_equipment_design_code_complement ',
            ' "RFQ_ITEM".ds_equipment_design_desc_complement ',
            '( select ds_unit_measure FROM "UNIT_MEASURE"WHERE "UNIT_MEASURE".cd_unit_measure =  "RFQ_ITEM".cd_unit_measure) as ds_unit_measure',
            ' "RFQ_ITEM_SUPPLIER_QUOTATION".ds_reason_to_choose_supplier',
            ' "RFQ_ITEM_SUPPLIER_QUOTATION".nr_qtty_to_buy',
            ' "RFQ_ITEM".ds_brand',
            ' "RFQ_ITEM_SUPPLIER_QUOTATION".cd_rfq_item_supplier_quotation',
            ' "RFQ_ITEM_SUPPLIER_QUOTATION".cd_rfq_item_supplier',
            ' ( "SUPPLIER".ds_vendor_code:: text || \' - \' ||  "SUPPLIER".ds_supplier || COALESCE(( CASE WHEN "SUPPLIER".ds_supplier !=  COALESCE("SUPPLIER".ds_supplier_alt, "SUPPLIER".ds_supplier) THEN \' - \' || "SUPPLIER".ds_supplier_alt ELSE \'\' END ), \'\')  )  as ds_supplier ',
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
//            '( select ds_department_cost_center FROM "DEPARTMENT_COST_CENTER" WHERE cd_department_cost_center =  "RFQ_COST_CENTER".cd_department_cost_center) as ds_department_cost_center',
//            ' "RFQ_COST_CENTER".ds_project_number',
//            ' "RFQ_COST_CENTER".ds_project_model_number',
        );

        if ($fl_rfq_see_price == 'Y') {
            // adding price information onlu if user has rights.
            array_push($this->fieldsforQUOSCR, ' "RFQ_ITEM_SUPPLIER_QUOTATION".nr_price');
            array_push($this->fieldsforQUOSCR, '( ROUND( "RFQ_ITEM_SUPPLIER_QUOTATION".nr_price *  COALESCE("CURRENCY_RATE".nr_currency_rate , 1), 4  )) as nr_price_default_currency');
            array_push($this->fieldsforQUOSCR, '( ROUND( "RFQ_ITEM_SUPPLIER_QUOTATION".nr_price *  ( ( 100 + "RFQ_ITEM_SUPPLIER".nr_tax ) / 100), 4 ) ) as nr_price_with_tax');
            array_push($this->fieldsforQUOSCR, '( ROUND( "RFQ_ITEM_SUPPLIER_QUOTATION".nr_price *  COALESCE("CURRENCY_RATE".nr_currency_rate , 1) * ( ( 100 + "RFQ_ITEM_SUPPLIER".nr_tax ) / 100), 4) ) as nr_price_with_tax_default_currency');
            array_push($this->fieldsforQUOSCR, ' ( "RFQ_ITEM_SUPPLIER_QUOTATION".nr_price * "RFQ_ITEM_SUPPLIER_QUOTATION".nr_qtty_to_buy ) as nr_total_price');
            array_push($this->fieldsforQUOSCR, '( ROUND( "RFQ_ITEM_SUPPLIER_QUOTATION".nr_price *  COALESCE("CURRENCY_RATE".nr_currency_rate , 1), 2  )  * "RFQ_ITEM_SUPPLIER_QUOTATION".nr_qtty_to_buy ) as nr_total_price_rmb');
            array_push($this->fieldsforQUOSCR, '( ROUND( "RFQ_ITEM_SUPPLIER_QUOTATION".nr_price *  ( ( 100 + "RFQ_ITEM_SUPPLIER".nr_tax ) / 100), 2 )  * "RFQ_ITEM_SUPPLIER_QUOTATION".nr_qtty_to_buy  ) as nr_total_price_with_tax');
            array_push($this->fieldsforQUOSCR, '( ROUND( "RFQ_ITEM_SUPPLIER_QUOTATION".nr_price *  COALESCE("CURRENCY_RATE".nr_currency_rate , 1) * ( ( 100 + "RFQ_ITEM_SUPPLIER".nr_tax ) / 100), 2 ) * "RFQ_ITEM_SUPPLIER_QUOTATION".nr_qtty_to_buy  ) as nr_total_price_rmb_with_tax');
        }

        foreach ($info as $key => $value) {
            array_push($this->fieldsforQUOSCR, ' ( SELECT datedbtogrid(x.dt_define) FROM "RFQ_APPROVAL_STEPS" x WHERE x.cd_rfq = "RFQ".cd_rfq AND x.cd_approval_steps_config = ' . $value['cd_approval_steps_config'] . ' ORDER BY x.dt_record DESC LIMIT 1 ) as ds_step_info_' . $value['cd_approval_steps_config']);
        }

        $joinQUOSCR = array(
            'JOIN "RFQ_ITEM" ON ("RFQ".cd_rfq = "RFQ_ITEM".cd_rfq)',
            'JOIN "EQUIPMENT_DESIGN" ON ("EQUIPMENT_DESIGN".cd_equipment_design = "RFQ_ITEM".cd_equipment_design )',
            'JOIN "RFQ_REQUEST_TYPE" ON ("RFQ_REQUEST_TYPE".cd_rfq_request_type =  "RFQ_ITEM".cd_rfq_request_type)',
            'JOIN "HUMAN_RESOURCE" ON ( "HUMAN_RESOURCE".cd_human_resource = "RFQ".cd_human_resource_applicant ) ',
//            'JOIN "RFQ_COST_CENTER" ON ( "RFQ_ITEM".cd_rfq_item = "RFQ_COST_CENTER".cd_rfq_item )',
            'LEFT OUTER JOIN "RFQ_ITEM_SUPPLIER" ON ("RFQ_ITEM_SUPPLIER".cd_rfq_item = "RFQ_ITEM".cd_rfq_item)',
            'LEFT OUTER JOIN "RFQ_ITEM_SUPPLIER_QUOTATION" ON ("RFQ_ITEM_SUPPLIER_QUOTATION".cd_rfq_item_supplier = "RFQ_ITEM_SUPPLIER".cd_rfq_item_supplier )',
            'LEFT OUTER JOIN "RFQ_APPROVAL_STEPS" ON ("RFQ_APPROVAL_STEPS".cd_rfq = "RFQ".cd_rfq AND "RFQ_APPROVAL_STEPS".cd_approval_status IS NULL)   ',
            'LEFT OUTER JOIN "CURRENCY_RATE" ON ("CURRENCY_RATE".cd_currency_rate = "RFQ_ITEM_SUPPLIER_QUOTATION".cd_currency_rate )',
            'LEFT OUTER JOIN  "SUPPLIER" ON ("SUPPLIER".cd_supplier = "RFQ_ITEM_SUPPLIER".cd_supplier ) ',
        );

        $this->retrOptionsQUOSCR = array("fieldrecid" => '(COALESCE("RFQ_ITEM_SUPPLIER_QUOTATION".cd_rfq_item_supplier_quotation, -1)::text || \'-\' || "RFQ_ITEM".cd_rfq_item || \'-\' || "RFQ_ITEM".cd_rfq ) ',
            "stylecond" => "(CASE WHEN \"RFQ\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforQUOSCR),
            "json" => true,
            'join' => $joinQUOSCR,
            'forcedwhere' => $fixedwhere
        );

















        parent::__construct();

        $this->load->model("approval_steps_config_model", "stepmodel");
        $this->load->model("rfq/rfq_model", "rfqmodel", TRUE);
        $this->load->model("rfq/rfq_item_model", "rfqitemmodel", TRUE);
        $this->load->model('docrep/document_repository_model', 'picmodel', TRUE);
    }

    public function checkRights($cd_rfq) {
        $actualStep = $this->stepmodel->getActualStep('RFQ', $cd_rfq);
        $done = !$actualStep;
        $chosingSuppliersMode = false;
        $isManager = ($this->getCdbhelper()->getUserPermission('fl_rfq_manager') == 'Y');
        $canChange = false;
        $canFinance = ($this->getCdbhelper()->getUserPermission('fl_rfq_see_price') == 'Y');


        if (!$done) {
            $canChange = $actualStep['fl_has_rights'] == 'Y' || $isManager;
            $chosingSuppliersMode = $actualStep['ds_internal_code'] == 'ToCheckSupplier' && ( $actualStep['fl_has_rights'] == 'Y' || $isManager );
            $firstStep = $actualStep['ds_internal_code'] == 'ToTeamApproval';

            $canChangeCost = ( $actualStep['fl_has_rights'] == 'Y' || $isManager );

            if ($actualStep['ds_internal_code'] == 'toPR') {
                $canChangeCost = false;
            }
            $canSample = ($canFinance && ( $actualStep['ds_internal_code'] == 'To ReleaseToTeam' || $actualStep['ds_internal_code'] == 'ToWorkFlow' || $canChange ) );
        } else {
            $canChange = $isManager;
            $firstStep = false;
            $canChangeCost = false;
            $canSample = false;
        }

        return array("canChange" => $canChange,
            'done' => $done,
            'choosingSupplier' => $chosingSuppliersMode,
            'canFinance' => $canFinance,
            'firstStep' => $firstStep,
            'canChangeCost' => $canChangeCost,
            'actualStep' => $actualStep['ds_internal_code'],
            'canSample' => $canSample);
    }

    public function createFilesAttached($cd_rfq, $filename, $filesToAdd, $showQuotation, $download = true) {
        $originalName = $filename . '.zip';
        $filename = '/tmp/' . $filename . '.zip';


        $zip = new ZipArchive();
        $zip->open($filename, ZipArchive::CREATE);

        foreach ($filesToAdd as $key => $line) {
            $filenameToAdd = $line;

            if (!file_exists($filenameToAdd)) {
                continue;
            }

            $zip->addFile($filenameToAdd, basename($filenameToAdd));
        }

        if ($showQuotation) {

            // quotation documents
            $quoData = $this->picmodel->retrieveByRelation(3, $cd_rfq);
            foreach ($quoData as $key => $line) {
                $filenameToAdd = $line['ds_document_file_path'] . $line['ds_document_file_hash'] . '.' . $line['ds_file_extension'];

                if (!file_exists($filenameToAdd)) {
                    continue;
                }
                $addon = preg_replace("[^\w\s\d\.\-_~,;:\[\]\(\]]", '', $line['ds_document_repository']);

                $zip->addFile($filenameToAdd, "Quotation - $addon - " . $line['ds_original_file']);
            }
        }



        $items = $this->rfqitemmodel->retRetrieveArray(' WHERE "RFQ_ITEM".cd_rfq = ' . $cd_rfq);

        foreach ($items as $key => $value) {
            $itemData = $this->picmodel->retrieveByRelation(1, $value['recid']);

            foreach ($itemData as $key => $line) {

                $filenameToAdd = $line['ds_document_file_path'] . $line['ds_document_file_hash'] . '.' . $line['ds_file_extension'];

                if (!file_exists($filenameToAdd)) {
                    continue;
                }
                $addon = preg_replace("[^\w\s\d\.\-_~,;:\[\]\(\]]", '', $line['ds_document_repository']);

                $zip->addFile($filenameToAdd, $value['ds_equipment_design_code'] . " - $addon -" . $line['ds_original_file']);
            }
        }

        $zip->close();

        if ($download) {

            $fp = @fopen($filename, 'r');
            header("Content-Type: application/zip");
            header("Content-Disposition: attachment; filename=\"$originalName\"");
            header("Content-Length: " . filesize($filename));
            fpassthru($fp);
            fclose($fp);

            unlink($filename);
            return;
        }

        return $filename;
    }

}
