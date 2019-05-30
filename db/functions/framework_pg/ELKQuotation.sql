CREATE OR REPLACE FUNCTION rfq.ELKQuotation()
    RETURNS VOID as
$$
DECLARE
v_can varchar;
BEGIN

    drop table if exists ELKQuotBasic;
    create temporary table ELKQuotBasic as
    SELECT "RFQ".cd_rfq as id_rfq, 
           "RFQ_ITEM".cd_rfq_item as id_rfq_item,
           "RFQ_ITEM_SUPPLIER".cd_rfq_item_supplier as id_rfq_item_supplier ,
           "RFQ_ITEM_SUPPLIER_QUOTATION".cd_rfq_item_supplier_quotation as id_rfq_item_supplier_quotation ,

    ( "HUMAN_RESOURCE".ds_human_resource_full ) as "Applicant", 
    coalesce (to_char("RFQ".dt_request, 'mm/dd/yyyy'), '') as "Requested Date", 
    coalesce (to_char("RFQ".dt_requested_complete, 'mm/dd/yyyy'), '') as "Purchase Deadline", 
    (CASE WHEN "RFQ".fl_is_urgent = 'Y' THEN 1 ELSE 0 END) as "Is Urgent", 
    ( select ds_human_resource_full FROM "HUMAN_RESOURCE" WHERE cd_human_resource = "RFQ".cd_human_resource_purchase) as "Buyer", 
    trim(coalesce("RFQ".ds_comments, '')) as "Comments", 
    trim(coalesce("RFQ".ds_wf_number, '')) as "WF #", 
    trim(coalesce("RFQ".ds_rfq_number, '')) as "RFQ #", 
    COALESCE ( (SELECT ds_approval_steps_config from "APPROVAL_STEPS_CONFIG" WHERE "APPROVAL_STEPS_CONFIG".cd_approval_steps_config = "RFQ_APPROVAL_STEPS".cd_approval_steps_config ), 'FINISHED') as "Actual Step", 
    --(getRfqItemCostDepartment("RFQ_ITEM".cd_rfq_item) ) as ds_dep_cost, 
    ( "SUPPLIER".ds_vendor_code:: text || ' - ' ||  "SUPPLIER".ds_supplier || COALESCE(( CASE WHEN "SUPPLIER".ds_supplier !=  COALESCE("SUPPLIER".ds_supplier_alt, "SUPPLIER".ds_supplier) THEN ' - ' || "SUPPLIER".ds_supplier_alt ELSE '' END ), '')  )  as "Supplier", 
    "RFQ_ITEM_SUPPLIER_QUOTATION".nr_round as "Round",
    (retEquipmentCode("RFQ_ITEM".cd_equipment_design) || ( CASE WHEN "RFQ_ITEM".ds_equipment_design_code_complement IS NOT NULL THEN '-' || "RFQ_ITEM".ds_equipment_design_code_complement ELSE '' END) ) as "Equipment Code",
    (retEquipmentCode("RFQ_ITEM".cd_equipment_design) || ( CASE WHEN "RFQ_ITEM".ds_equipment_design_code_complement IS NOT NULL THEN '-' || "RFQ_ITEM".ds_equipment_design_code_complement ELSE '' END) || ' ' || COALESCE( "RFQ_ITEM".ds_equipment_design_desc_complement,"EQUIPMENT_DESIGN".ds_equipment_design,ds_equipment_design_desc_complement) ) as "Equipment",


    "RFQ_ITEM".cd_rfq_request_type, trim(coalesce("RFQ_REQUEST_TYPE".ds_rfq_request_type, '')) as "Type", 
    trim(coalesce("RFQ_ITEM".ds_reason_buy, '')) as "Reason to Buy", 
    "RFQ_ITEM".nr_qtty_quote as "Quantity to Quote", 
    (CASE WHEN COALESCE("RFQ_ITEM_SUPPLIER_QUOTATION".nr_qtty_to_buy, 0) > 0 THEN 1 ELSE 0 END) as "Buy", 
    coalesce (to_char("RFQ_ITEM".dt_deadline, 'mm/dd/yyyy'), '') as "Item Deadline", 
    trim(coalesce("RFQ_ITEM".ds_website, '')) as "WebSite", 
    ( "RFQ_ITEM".ds_remarks) as "Remarks Item", 
    "RFQ_ITEM".nr_estimated_annual as "Annual Estimated", 
    trim(coalesce("RFQ_ITEM".ds_po_number, '')) as "PO #", 

    (CASE WHEN "RFQ_ITEM".fl_need_sample = 'Y' THEN 1 ELSE 0 END) as "Need Sample", 
    coalesce (to_char("RFQ_ITEM".dt_supplier_visit_deadline, 'mm/dd/yyyy'), '') as "Supplier Visit Deadline", 
    ( select ds_unit_measure FROM "UNIT_MEASURE"WHERE "UNIT_MEASURE".cd_unit_measure = "RFQ_ITEM".cd_unit_measure) as "Unit Measure", 
    trim(coalesce("RFQ_ITEM_SUPPLIER_QUOTATION".ds_reason_to_choose_supplier, '')) as "Reason to Choose Supplier", 
    "RFQ_ITEM_SUPPLIER_QUOTATION".nr_qtty_to_buy as "Quantity to Buy",  
    trim(coalesce("RFQ_ITEM".ds_brand, '')) as "Brand",

    ( select ds_currency FROM "CURRENCY" WHERE cd_currency = "RFQ_ITEM_SUPPLIER_QUOTATION".cd_currency) as "Currency", 
    "RFQ_ITEM_SUPPLIER_QUOTATION".nr_moq as "MOQ", 
    "RFQ_ITEM_SUPPLIER_QUOTATION".nr_leadtime as "Leadtime", 
    coalesce (to_char("RFQ_ITEM_SUPPLIER_QUOTATION".dt_expiring_date, 'mm/dd/yyyy'), '') as "Quotation Expiring Date", 
    "RFQ_ITEM_SUPPLIER_QUOTATION".nr_warranty as "Warranty", 
    ( CASE WHEN ds_kind = 'N' THEN 'New' ELSE 'Repair' END) as "Kind", 
    ( "RFQ_ITEM_SUPPLIER_QUOTATION".ds_remarks ) as "Quotation Remarks", 
    COALESCE("CURRENCY_RATE".nr_currency_rate , 1) as "Currency Rate", 
    "RFQ_ITEM_SUPPLIER".nr_tax as "Tax", 
    "RFQ_ITEM_SUPPLIER_QUOTATION".nr_price as "Unit Price", 
    ( ROUND( "RFQ_ITEM_SUPPLIER_QUOTATION".nr_price * COALESCE("CURRENCY_RATE".nr_currency_rate , 1), 4 )) as "Unit Price RMB", 
    ( ROUND( "RFQ_ITEM_SUPPLIER_QUOTATION".nr_price * ( ( 100 + "RFQ_ITEM_SUPPLIER".nr_tax ) / 100), 4 ) ) as "Unit Price With Tax", 
    ( ROUND( "RFQ_ITEM_SUPPLIER_QUOTATION".nr_price * COALESCE("CURRENCY_RATE".nr_currency_rate , 1) * ( ( 100 + "RFQ_ITEM_SUPPLIER".nr_tax ) / 100), 4) ) as "Unit Price with Tax RMB", 
    ( "RFQ_ITEM_SUPPLIER_QUOTATION".nr_price * "RFQ_ITEM_SUPPLIER_QUOTATION".nr_qtty_to_buy ) as "Total Price", 
    ( ROUND( "RFQ_ITEM_SUPPLIER_QUOTATION".nr_price * COALESCE("CURRENCY_RATE".nr_currency_rate , 1), 2 ) * "RFQ_ITEM_SUPPLIER_QUOTATION".nr_qtty_to_buy ) as "Total Price RMB", 
    ( ROUND( "RFQ_ITEM_SUPPLIER_QUOTATION".nr_price * ( ( 100 + "RFQ_ITEM_SUPPLIER".nr_tax ) / 100), 2 ) * "RFQ_ITEM_SUPPLIER_QUOTATION".nr_qtty_to_buy ) as "Total Price with Tax", 
    ( ROUND( "RFQ_ITEM_SUPPLIER_QUOTATION".nr_price * COALESCE("CURRENCY_RATE".nr_currency_rate , 1) * ( ( 100 + "RFQ_ITEM_SUPPLIER".nr_tax ) / 100), 2 ) * "RFQ_ITEM_SUPPLIER_QUOTATION".nr_qtty_to_buy ) as "Total Price RMB with Tax" ,



    to_char(aToTeamApprovala.dt_define, 'mm/dd/yyyy hh24:mm') as "Release to Approve",

    (aToQuote.dt_define::date -aToTeamApprovala.dt_define::date) as "Days Diff Release to Approve - Release Purchase Request" , 

    to_char(aToQuote.dt_define, 'mm/dd/yyyy hh24:mm') as "Team Approval",

    (aToWorkFlow.dt_define::date - aToQuote.dt_define::date) as "Days Diff Team Approval - Release Purchase Request" ,

    to_char(aToWorkFlow.dt_define, 'mm/dd/yyyy hh24:mm') as "Release Purchase Request",

    (aToTeamApprovalb.dt_define::date -aToWorkFlow.dt_define::date) as "Days Diff Release Purchase Request - Release to Team Approver" ,

    to_char(aToTeamApprovalb.dt_define, 'mm/dd/yyyy hh24:mm') as "Release to Team Approver",

    (aToCheckSupplier.dt_define::date - aToTeamApprovalb.dt_define::date) as "Days Diff Release to Team Approver - Team Approver Check Supp" ,

    to_char(aToCheckSupplier.dt_define, 'mm/dd/yyyy hh24:mm') as "Team Approver Check Suppliers",

    (aToDepMan.dt_define::date - aToCheckSupplier.dt_define::date) as "Days Diff Team Approver Check Suppliers - Department Manager" ,

    to_char(aToDepMan.dt_define, 'mm/dd/yyyy hh24:mm') as "Department Manager",

    (atoPR.dt_define::date - aToDepMan.dt_define::date) as "Days Diff Department Manager - Release to PR/EPOR" ,

    to_char(atoPR.dt_define, 'mm/dd/yyyy hh24:mm') as "Release to PR/EPOR",

    -- Total
    (atoPR.dt_define::date - aToTeamApprovala.dt_define::date) as "Days Diff Total"

    FROM "RFQ" 
    JOIN "RFQ_ITEM" ON ("RFQ".cd_rfq = "RFQ_ITEM".cd_rfq) 
    JOIN "EQUIPMENT_DESIGN" ON ("EQUIPMENT_DESIGN".cd_equipment_design = "RFQ_ITEM".cd_equipment_design ) 
    JOIN "RFQ_REQUEST_TYPE" ON ("RFQ_REQUEST_TYPE".cd_rfq_request_type = "RFQ_ITEM".cd_rfq_request_type) 
    JOIN "HUMAN_RESOURCE" ON ( "HUMAN_RESOURCE".cd_human_resource = "RFQ".cd_human_resource_applicant )


    LEFT OUTER JOIN (
            SELECT min(x.dt_define) as dt_define, x.cd_rfq
              FROM "RFQ_APPROVAL_STEPS" x, "APPROVAL_STEPS_CONFIG" a 
            WHERE x.cd_approval_steps_config = a.cd_approval_steps_config
              AND a.cd_approval_steps_config = 1
              AND x.cd_approval_status       = 1
              GROUP BY x.cd_rfq ) aToTeamApprovala ON ( aToTeamApprovala.cd_rfq = "RFQ".cd_rfq) 



    LEFT OUTER JOIN (
            SELECT min(x.dt_define) as dt_define, x.cd_rfq
              FROM "RFQ_APPROVAL_STEPS" x, "APPROVAL_STEPS_CONFIG" a 
            WHERE x.cd_approval_steps_config = a.cd_approval_steps_config
              AND a.ds_internal_code         = 'ToQuote'
              AND x.cd_approval_status       = 1
              GROUP BY x.cd_rfq ) aToQuote ON ( aToQuote.cd_rfq = "RFQ".cd_rfq)

    LEFT OUTER JOIN (
            SELECT min(x.dt_define) as dt_define, x.cd_rfq
              FROM "RFQ_APPROVAL_STEPS" x, "APPROVAL_STEPS_CONFIG" a 
            WHERE x.cd_approval_steps_config = a.cd_approval_steps_config
              AND a.ds_internal_code         = 'ToWorkFlow'
              AND x.cd_approval_status       = 1
              GROUP BY x.cd_rfq ) aToWorkFlow ON ( aToWorkFlow.cd_rfq = "RFQ".cd_rfq) 


    LEFT OUTER JOIN (
            SELECT min(x.dt_define) as dt_define, x.cd_rfq
              FROM "RFQ_APPROVAL_STEPS" x, "APPROVAL_STEPS_CONFIG" a 
            WHERE x.cd_approval_steps_config = a.cd_approval_steps_config
              AND a.cd_approval_steps_config = 4
              AND x.cd_approval_status       = 1
              GROUP BY x.cd_rfq ) aToTeamApprovalb ON ( aToTeamApprovalb.cd_rfq = "RFQ".cd_rfq) 

    LEFT OUTER JOIN (
            SELECT min(x.dt_define) as dt_define, x.cd_rfq
              FROM "RFQ_APPROVAL_STEPS" x, "APPROVAL_STEPS_CONFIG" a 
            WHERE x.cd_approval_steps_config = a.cd_approval_steps_config
              AND a.ds_internal_code         = 'ToCheckSupplier'
              AND x.cd_approval_status       = 1
              GROUP BY x.cd_rfq ) aToCheckSupplier ON ( aToCheckSupplier.cd_rfq = "RFQ".cd_rfq) 

    LEFT OUTER JOIN (
            SELECT min(x.dt_define) as dt_define, x.cd_rfq
              FROM "RFQ_APPROVAL_STEPS" x, "APPROVAL_STEPS_CONFIG" a 
            WHERE x.cd_approval_steps_config = a.cd_approval_steps_config
              AND a.ds_internal_code         = 'toPR'
              AND x.cd_approval_status       = 1
              GROUP BY x.cd_rfq ) atoPR ON ( atoPR.cd_rfq = "RFQ".cd_rfq)

    LEFT OUTER JOIN (
            SELECT min(x.dt_define) as dt_define, x.cd_rfq
              FROM "RFQ_APPROVAL_STEPS" x, "APPROVAL_STEPS_CONFIG" a 
            WHERE x.cd_approval_steps_config = a.cd_approval_steps_config
              AND a.ds_internal_code         = 'ToDepMan'
              AND x.cd_approval_status       = 1
              GROUP BY x.cd_rfq ) aToDepMan ON ( aToDepMan.cd_rfq = "RFQ".cd_rfq)


    LEFT OUTER JOIN "RFQ_ITEM_SUPPLIER" ON ("RFQ_ITEM_SUPPLIER".cd_rfq_item = "RFQ_ITEM".cd_rfq_item) 
    LEFT OUTER JOIN "RFQ_ITEM_SUPPLIER_QUOTATION" ON ("RFQ_ITEM_SUPPLIER_QUOTATION".cd_rfq_item_supplier = "RFQ_ITEM_SUPPLIER".cd_rfq_item_supplier ) 
    LEFT OUTER JOIN "RFQ_APPROVAL_STEPS" ON ("RFQ_APPROVAL_STEPS".cd_rfq = "RFQ".cd_rfq AND "RFQ_APPROVAL_STEPS".cd_approval_status IS NULL) 
    LEFT OUTER JOIN "CURRENCY_RATE" ON ("CURRENCY_RATE".cd_currency_rate = "RFQ_ITEM_SUPPLIER_QUOTATION".cd_currency_rate ) 
    LEFT OUTER JOIN "SUPPLIER" ON ("SUPPLIER".cd_supplier = "RFQ_ITEM_SUPPLIER".cd_supplier )

    WHERE "RFQ".cd_system_product_category = 1
    AND "RFQ_ITEM_SUPPLIER_QUOTATION".nr_price > 0
    and "RFQ".dt_deactivated IS NULL

    ORDER BY 2;



    drop table if exists ELKQuotHist;
    create temporary table ELKQuotHist as
     SELECT  x.*, 
        b.cd_rfq_approval_steps as id_rfq_history,
        ( select ds_approval_status from "APPROVAL_STATUS" where cd_approval_status = b.cd_approval_status) as "Approval Status",
        to_char(b.dt_define, 'MM/DD/YYYY HH24:MI') as "Date Defined",
        (SELECT ds_human_resource_full FROM "HUMAN_RESOURCE" where cd_human_resource = b.cd_human_resource_define) as "Approval User",
        a.ds_approval_steps_config as "Approval Step",
        a.nr_order as "Order",
        a.ds_instructions
      FROM ELKQuotBasic x
       INNER JOIN rfq."RFQ_APPROVAL_STEPS" b ON (b.cd_rfq                   = x.id_rfq)
       INNER JOIN "APPROVAL_STEPS_CONFIG" a ON (b.cd_approval_steps_config = a.cd_approval_steps_config AND a.ds_approval_steps_config_type = 'RFQ')
      ORDER BY id_rfq, "Order";

   
END;
$$
LANGUAGE plpgsql;
