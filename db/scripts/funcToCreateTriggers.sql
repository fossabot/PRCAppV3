CREATE OR REPLACE FUNCTION public.makeTriggers
(
)
RETURNS TABLE (ds_tables_trigger text)

 AS
$$
DECLARE
r record;
vsql text;
BEGIN

   FOR r IN SELECT  
'CREATE TRIGGER zzaudit_insert_update_delete
  AFTER INSERT OR UPDATE OR DELETE
  ON '|| table_schema ||'."'|| t.table_name ||'"
  FOR EACH ROW
  EXECUTE PROCEDURE audit.if_tablelog();' as ds_sql,
  table_schema ||'."'|| t.table_name as ds_table
  
 from information_schema.tables t where t.table_type = 'BASE TABLE' and t.table_name = upper(t.table_name) 
and not exists ( select 1 from information_schema.triggers x where action_statement like '%if_tablelog%' and x.event_object_table = t.table_name)
and t.table_name not in (
'SYSTEM_PARAMETERS',
'SYSTEM_PERMISSION',
'SYSTEM_RELATIONS',
'SYSTEM_SETTINGS',
'SYSTEM_SETTINGS_OPTIONS',
'SYS_FILTER_QUERIES',
'SYS_FLAG_USED',
'HR_SYSTEM_SETTINGS_OPTIONS',
'SYSTEM_REPORTS',
'SYS_COMPANY',
'SYSTEM_COMPANY',
'SYSTEM_REPORTS_AUTHORIZATION',
'TMP_PROCESSES',
 'SYSTEM_DICTIONARY_USERDEFINED',
 'TEMPCOUNTRY',
'TEMPCUR',
'OPERATOR_TABLE_TIMESTAMP',
'SESSION_LOG',
'TR_IMP_TMP_WORK_ORDER_RAW',
'ONEKEY_RAW_DATA',
'ONEKEY_DATA_SUMMARY_BY_MINUTE_SUPPLIER_PC',
'ONEKEY_DATA_SUMMARY_BY_DAY'
)
 and table_name not like '%_X_%'
 and table_schema not in ('audit', 'translation', 'reports')

   LOOP
      vsql         = r.ds_sql;

      EXECUTE vsql;

      ds_tables_trigger = r.ds_table;
      RAISE NOTICE 'Adding Trigger to : %', r.ds_table; 

      RETURN NEXT;

      

   END LOOP;







END;
$$
LANGUAGE plpgsql;



