<?php

include_once APPPATH . "models/modelBasicExtend.php";

class rfq_item_model extends modelBasicExtend
{

    function __construct()
    {

        $this->table = "RFQ_ITEM";

        $this->pk_field = '"RFQ_ITEM".cd_rfq_item';
        $this->ds_field = 'ds_rfq';
        $this->prodCatUnique = 'N';

        $this->sequence_obj = '"RFQ_ITEM_cd_rfq_item_seq"';

        $this->controller = 'rfq/rfq_item';


        $this->fieldsforGrid = array(
            ' "RFQ_ITEM".cd_rfq_item',
            '(select cast(min(nr_moq) as integer) from rfq."RFQ_ITEM_SUPPLIER_QUOTATION" q,rfq."RFQ_ITEM_SUPPLIER" s,rfq."RFQ_ITEM" i where i.cd_rfq_item=s.cd_rfq_item and s.cd_rfq_item_supplier=q.cd_rfq_item_supplier and i.cd_rfq_item="RFQ_ITEM".cd_rfq_item and COALESCE(nr_qtty_to_buy, 0) > 0) as nr_moq',
            '(select min(nr_leadtime) from rfq."RFQ_ITEM_SUPPLIER_QUOTATION" q,rfq."RFQ_ITEM_SUPPLIER" s,rfq."RFQ_ITEM" i where i.cd_rfq_item=s.cd_rfq_item and s.cd_rfq_item_supplier=q.cd_rfq_item_supplier and i.cd_rfq_item="RFQ_ITEM".cd_rfq_item and COALESCE(nr_qtty_to_buy, 0) > 0) as nr_leadtime',
            ' "RFQ_ITEM".cd_rfq',
            '( select fl_is_urgent FROM "RFQ" WHERE cd_rfq =  "RFQ_ITEM".cd_rfq) as ds_rfq',
            ' "RFQ_ITEM".cd_equipment_design',
            '( "EQUIPMENT_DESIGN".ds_equipment_description_full ) as ds_equipment_design',
            '( "EQUIPMENT_DESIGN".ds_equipment_code_full || ( CASE WHEN "RFQ_ITEM".ds_equipment_design_code_complement IS NOT NULL THEN \'-\' || "RFQ_ITEM".ds_equipment_design_code_complement ELSE \'\' END) ) as ds_equipment_design_code',
            '("EQUIPMENT_DESIGN".ds_equipment_design ) as ds_equipment_design_description',

            '( "EQUIPMENT_DESIGN".ds_equipment_code_full || ( CASE WHEN "RFQ_ITEM".ds_equipment_design_code_complement IS NOT NULL THEN \'-\' || "RFQ_ITEM".ds_equipment_design_code_complement ELSE \'\' END) || \' \' || COALESCE( "RFQ_ITEM".ds_equipment_design_desc_complement,"EQUIPMENT_DESIGN".ds_equipment_design,ds_equipment_design_desc_complement) ) as ds_equipment_design_full',

            ' "RFQ_ITEM".cd_rfq_request_type',
            '"RFQ_REQUEST_TYPE".ds_rfq_request_type',
            ' "RFQ_ITEM".ds_reason_buy',
            ' "RFQ_ITEM".nr_qtty_quote',
            ' ( CASE WHEN EXISTS ( SELECT 1 FROM  "RFQ_ITEM_SUPPLIER" a , "RFQ_ITEM_SUPPLIER_QUOTATION" x  WHERE a.cd_rfq_item = "RFQ_ITEM".cd_rfq_item AND x.cd_rfq_item_supplier = a.cd_rfq_item_supplier  AND COALESCE(x.nr_qtty_to_buy, 0) > 0)  THEN 1 ELSE 0 END) as fl_buy',
            ' "RFQ_ITEM".dt_deadline',
            ' "RFQ_ITEM".ds_website',
            ' "RFQ_ITEM".ds_remarks',
            ' "RFQ_ITEM".ds_attached_image',
            ' "RFQ_ITEM".nr_estimated_annual',
            ' "RFQ_ITEM".ds_po_number',
            '"RFQ_ITEM".cd_unit_measure',
            ' "RFQ_ITEM".fl_need_sample',
            ' "RFQ_ITEM".fl_online',
            ' "RFQ_ITEM".dt_supplier_visit_deadline',
            '"RFQ_REQUEST_TYPE".fl_is_new',
            '"RFQ_REQUEST_TYPE".fl_is_repair',
            ' "RFQ_ITEM".ds_equipment_design_code_complement ',
            ' "RFQ_ITEM".ds_equipment_design_desc_complement ',
            ' ( getRfqItemSampleInformation("RFQ_ITEM".cd_rfq_item)) as ds_sample_info',


            '( select ds_unit_measure FROM "UNIT_MEASURE"WHERE "UNIT_MEASURE".cd_unit_measure =  "RFQ_ITEM".cd_unit_measure) as ds_unit_measure',
            //' "RFQ_ITEM".ds_reason_to_choose_supplier',
            ' "RFQ_ITEM".ds_brand',
            '(  SELECT count(1) FROM "RFQ_ITEM_SUPPLIER" s1, "RFQ_ITEM_SUPPLIER_QUOTATION" q1 WHERE s1.cd_rfq_item = "RFQ_ITEM".cd_rfq_item AND q1.cd_rfq_item_supplier = s1.cd_rfq_item_supplier AND q1.nr_price > 0 ) as nr_count_quote',

            /*PRICE INFORMATION - Will show only if has rights*/
            ' ( SELECT sum(x.nr_qtty_to_buy) FROM  "RFQ_ITEM_SUPPLIER" a , "RFQ_ITEM_SUPPLIER_QUOTATION" x  WHERE a.cd_rfq_item = "RFQ_ITEM".cd_rfq_item AND x.cd_rfq_item_supplier = a.cd_rfq_item_supplier  AND COALESCE(x.nr_qtty_to_buy, 0) > 0)  as nr_qtty_to_buy',
//            ' ( SELECT sum( ROUND( x.nr_price *  COALESCE(c.nr_currency_rate , 1), 2  ) * x.nr_qtty_to_buy ) FROM  "RFQ_ITEM_SUPPLIER" a , "RFQ_ITEM_SUPPLIER_QUOTATION" x LEFT OUTER JOIN "CURRENCY_RATE" c ON (c.cd_currency_rate = x.cd_currency_rate)  WHERE a.cd_rfq_item = "RFQ_ITEM".cd_rfq_item AND x.cd_rfq_item_supplier = a.cd_rfq_item_supplier  AND COALESCE(x.nr_qtty_to_buy, 0) > 0  ) as nr_total_default_currency',
            ' ( SELECT sum( ROUND( x.nr_price *  COALESCE(c.nr_currency_rate , 1) * x.nr_qtty_to_buy , 2)) FROM  "RFQ_ITEM_SUPPLIER" a , "RFQ_ITEM_SUPPLIER_QUOTATION" x LEFT OUTER JOIN "CURRENCY_RATE" c ON (c.cd_currency_rate = x.cd_currency_rate)  WHERE a.cd_rfq_item = "RFQ_ITEM".cd_rfq_item AND x.cd_rfq_item_supplier = a.cd_rfq_item_supplier  AND COALESCE(x.nr_qtty_to_buy, 0) > 0  ) as nr_total_default_currency',
            ' ( SELECT array_to_string(array_agg(s.ds_supplier), \' / \') FROM  "RFQ_ITEM_SUPPLIER" a , "RFQ_ITEM_SUPPLIER_QUOTATION" x , "SUPPLIER" s WHERE a.cd_rfq_item = "RFQ_ITEM".cd_rfq_item AND x.cd_rfq_item_supplier = a.cd_rfq_item_supplier  AND COALESCE(x.nr_qtty_to_buy, 0) > 0 AND s.cd_supplier = a.cd_supplier)  as ds_supplier',
            ' ( SELECT array_to_string(array_agg(x.ds_reason_to_choose_supplier), \' / \') FROM  "RFQ_ITEM_SUPPLIER" a , "RFQ_ITEM_SUPPLIER_QUOTATION" x  WHERE a.cd_rfq_item = "RFQ_ITEM".cd_rfq_item AND x.cd_rfq_item_supplier = a.cd_rfq_item_supplier  AND COALESCE(x.nr_qtty_to_buy, 0) > 0 AND x.ds_reason_to_choose_supplier IS NOT NULL)  as ds_reason_to_choose_supplier',
            '(getRfqItemCostDepartment("RFQ_ITEM".cd_rfq_item) ) as ds_dep_cost'


        );
        $this->fieldsUpd = array("ds_brand", "ds_equipment_design_code_complement", "ds_equipment_design_desc_complement", "cd_rfq_item", "cd_rfq", "cd_equipment_design", "cd_rfq_request_type", "ds_reason_buy", "nr_qtty_quote", "dt_deadline", "ds_website", "ds_remarks", "ds_attached_image", "nr_qtty_to_buy", "nr_estimated_annual", "cd_unit_measure", "ds_po_number", "fl_need_sample", "dt_supplier_visit_deadline", "fl_online",);

        $join = array('JOIN "RFQ_REQUEST_TYPE" ON ("RFQ_REQUEST_TYPE".cd_rfq_request_type =  "RFQ_ITEM".cd_rfq_request_type)',
            'JOIN "EQUIPMENT_DESIGN" ON ("EQUIPMENT_DESIGN".cd_equipment_design =  "RFQ_ITEM".cd_equipment_design)'

        );

        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            //"stylecond" => "(CASE WHEN \"RFQ_ITEM\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true,
            'join' => $join
        );


        parent::__construct();
    }

}
