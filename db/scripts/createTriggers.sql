select 
'CREATE TRIGGER zzaudit_insert_update_delete
  AFTER INSERT OR UPDATE OR DELETE
  ON '|| table_schema ||'."'|| t.table_name ||'"
  FOR EACH ROW
  EXECUTE PROCEDURE audit.if_tablelog();'
  
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
'SESSION_LOG',
'SYSTEM_REPORTS_AUTHORIZATION',
'TMP_PROCESSES',
 'SYSTEM_DICTIONARY_USERDEFINED',
 'TEMPCOUNTRY',
'ONEKEY_RAW_DATA',
'ONEKEY_DATA_SUMMARY_BY_MINUTE_SUPPLIER_PC',
'ONEKEY_DATA_SUMMARY_BY_DAY',
'TEMPCUR',
'TR_IMP_TMP_WORK_ORDER_RAW',
'OPERATOR_TABLE_TIMESTAMP'
) and t.table_name = upper (t.table_name)
 and table_name not like '%_X_%'
 and table_schema not in ('audit', 'translation', 'reports')


select * from loadSysRel();
