delete from "HUMAN_RESOURCE_X_CUSTOMER";
delete from "HUMAN_RESOURCE_X_DIVISION";
delete from "HUMAN_RESOURCE_X_FACTORY";
delete from "SHOE_SEASON_BUDGET";
delete from "SHOE_PURCHASE_ORDER_SKU_PROCESS";
delete from "HUMAN_RESOURCE_X_SYSTEM_PRODUCT_CATEGORY";
delete from spec."SHOE_SAMPLE_REQUEST_CORRECTION_COMPONENT";
delete from "HUMAN_RESOURCE_X_NOTIFICATION_GROUP";

truncate table "CONSTRUCTION" CASCADE;
truncate table "CURRENCY_RATE" CASCADE;
truncate table "FACTORY" CASCADE;
truncate table "CUSTOMER" CASCADE;
truncate table "CUSTOMER_GROUP" CASCADE;

truncate table "SEASON" CASCADE;
truncate table "DIVISION_BRAND" CASCADE;
truncate table "DIVISION" CASCADE;

truncate table "TRADING" CASCADE;
truncate table "BILLING_TO" CASCADE;
truncate table "BILLING_TO_PARTS" CASCADE;

truncate table "SYSTEM_DICTIONARY_USERDEFINED";

truncate table "DOCUMENT_FILE" CASCADE;


truncate table "SHOE_SAMPLE_PRICE" CASCADE;


delete from "TABLES_LOG_MASTER";

-- ajusta variaveis;

update "SYSTEM_PARAMETERS" SET ds_system_parameters_value = '/var/www/devshoes.com/document_repository/hrms/userImage/' WHERE ds_system_parameters_id = 'PATH_USER_PICTURES';

UPDATE public."SYSTEM_SETTINGS_OPTIONS"
   SET ds_option_id = '/var/www/devshoes.com/document_repository/hrms/temp/'
 WHERE ds_system_settings_options = 'Basic Temp Root';


UPDATE public."SYSTEM_SETTINGS_OPTIONS"
   SET ds_option_id = '/var/www/devshoes.com/document_repository/hrms/thumbs/'
 WHERE ds_system_settings_options = 'Basic Thumbs';

UPDATE public."SYSTEM_SETTINGS_OPTIONS"
   SET ds_option_id = '/var/www/devshoes.com/document_repository/hrms/'
 WHERE ds_system_settings_options = 'Basic Root';

UPDATE public."SYSTEM_SETTINGS_OPTIONS"
   SET ds_option_id = 'hrms/'
 WHERE ds_system_settings_options = 'Basic Path';


UPDATE public."SYSTEM_COMPANY"
   SET ds_name='Demo', ds_address='', nr_max_connections=5,ds_timezone='Asia/Hong_Kong', ds_factoryontime_id=null, ds_inspection_id=null ;


-- volto as sequencias:
select setval_max('public', null, true);
select setval_max('material', null, true);
select setval_max('spec', null, true);


delete from "HUMAN_RESOURCE" where ds_human_resource <> 'admin';



