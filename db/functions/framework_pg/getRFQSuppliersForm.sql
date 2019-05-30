
CREATE OR REPLACE FUNCTION rfq.getRFQSuppliersForm(PAR_cd_rfq bigint, PAR_cd_supplier_add integer)
  RETURNS table (  
  cd_rfq                              bitint,
  cd_rfq_item                         bitint,
  cd_rfq_item_supplier_quotation      bigint,
  cd_rfq_item_supplier                bigint,
  ds_supplier                         text,
  ds_equipment_design                 text,
  nr_round                            integer,
  ds_currency                         varchar(128),
  nr_moq                              numeric(18,2),
  nr_leadtime                         integer,
  dt_expiring_date                    text,
  cd_payment_term                     bigint,
  ds_payment_term                     varchar(64),
  nr_warranty                         integer,
  dt_record                           text,
  ds_kind                             text,
  ds_remarks                          text,
  nr_currency_rate                    numeric,
  dt_update                           text,
  nr_tax                              numeric(18,2),
  cd_currency                         integer,
  nr_price                            numeric(18,2),
  nr_price_default_currency           numeric,
  nr_price_with_tax                   numeric,
  nr_price_with_tax_default_currency  numeric,
  recid                               bigint)
$$
DECLARE 
r record;
p record;
v_supp_record record;


BEGIN

select "RFQ_ITEM_SUPPLIER_QUOTATION".cd_rfq_item_supplier_quotation, 
       "RFQ_ITEM_SUPPLIER_QUOTATION".cd_rfq_item_supplier, 
       ("SUPPLIER".ds_vendor_code :: text || ' - ' || "SUPPLIER".ds_supplier || COALESCE(( CASE WHEN "SUPPLIER".ds_supplier != COALESCE(          "SUPPLIER".ds_supplier_alt, "SUPPLIER".ds_supplier        ) THEN ' - ' || "SUPPLIER".ds_supplier_alt ELSE '' END      ),       ''    )  ) as ds_supplier, 
  (select retEquipmentCode("RFQ_ITEM".cd_equipment_design) || ' ' || ds_equipment_design FROM "EQUIPMENT_DESIGN" WHERE cd_equipment_design = "RFQ_ITEM".cd_equipment_design  ) as ds_equipment_design, 
  "RFQ_ITEM_SUPPLIER_QUOTATION".nr_round, 
  (select ds_currency 
    FROM 
      "CURRENCY" 
    WHERE 
      cd_currency = "RFQ_ITEM_SUPPLIER_QUOTATION".cd_currency
  ) as ds_currency, 
  "RFQ_ITEM_SUPPLIER_QUOTATION".nr_moq, 
  "RFQ_ITEM_SUPPLIER_QUOTATION".nr_leadtime, 
  coalesce (
    to_char(
      "RFQ_ITEM_SUPPLIER_QUOTATION".dt_expiring_date, 
      'mm/dd/yyyy'
    ), 
    ''
  ) as dt_expiring_date, 
  "RFQ_ITEM_SUPPLIER_QUOTATION".cd_payment_term, 
  (
    select 
      ds_payment_term 
    FROM 
      "PAYMENT_TERM" 
    WHERE 
      cd_payment_term = "RFQ_ITEM_SUPPLIER_QUOTATION".cd_payment_term
  ) as ds_payment_term, 
  "RFQ_ITEM_SUPPLIER_QUOTATION".nr_warranty, 
  coalesce (
    to_char(
      "RFQ_ITEM_SUPPLIER_QUOTATION".dt_record, 
      'mm/dd/yyyy'
    ), 
    ''
  ) as dt_record, 
  trim(
    coalesce(
      "RFQ_ITEM_SUPPLIER_QUOTATION".ds_kind, 
      ''
    )
  ) as ds_kind, 
  trim(
    coalesce(
      "RFQ_ITEM_SUPPLIER_QUOTATION".ds_remarks, 
      ''
    )
  ) as ds_remarks, 
  COALESCE(
    "CURRENCY_RATE".nr_currency_rate, 
    1
  ) as nr_currency_rate, 
  coalesce (
    to_char(
      "RFQ_ITEM_SUPPLIER_QUOTATION".dt_update, 
      'mm/dd/yyyy'
    ), 
    ''
  ) as dt_update, 
  "RFQ_ITEM_SUPPLIER".nr_tax, 
  "RFQ_ITEM_SUPPLIER_QUOTATION".cd_currency, 
  "RFQ_ITEM_SUPPLIER_QUOTATION".nr_price, 
  (
    ROUND(
      "RFQ_ITEM_SUPPLIER_QUOTATION".nr_price * COALESCE(
        "CURRENCY_RATE".nr_currency_rate, 
        1
      ), 
      2
    )
  ) as nr_price_default_currency, 
  (
    ROUND(
      "RFQ_ITEM_SUPPLIER_QUOTATION".nr_price * (
        (100 + "RFQ_ITEM_SUPPLIER".nr_tax) / 100
      ), 
      2
    )
  ) as nr_price_with_tax, 
  (
    ROUND(
      "RFQ_ITEM_SUPPLIER_QUOTATION".nr_price * COALESCE(
        "CURRENCY_RATE".nr_currency_rate, 
        1
      ) * (
        (100 + "RFQ_ITEM_SUPPLIER".nr_tax) / 100
      ), 
      2
    )
  ) as nr_price_with_tax_default_currency, 
  cd_rfq_item_supplier_quotation as recid 
  /*sqlAddon*/
FROM 
  "RFQ_ITEM_SUPPLIER_QUOTATION" 
  JOIN "RFQ_ITEM_SUPPLIER" ON (
    "RFQ_ITEM_SUPPLIER_QUOTATION".cd_rfq_item_supplier = "RFQ_ITEM_SUPPLIER".cd_rfq_item_supplier
  ) 
  JOIN "RFQ_ITEM" ON (
    "RFQ_ITEM".cd_rfq_item = "RFQ_ITEM_SUPPLIER".cd_rfq_item
  ) 
  JOIN "SUPPLIER" ON (
    "SUPPLIER".cd_supplier = "RFQ_ITEM_SUPPLIER".cd_supplier
  ) 
  LEFT OUTER JOIN "CURRENCY_RATE" ON (
    "CURRENCY_RATE".cd_currency_rate = "RFQ_ITEM_SUPPLIER_QUOTATION".cd_currency_rate
  ) 
ORDER BY 
  2



END
$$  LANGUAGE plpgsql;
