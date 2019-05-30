CREATE OR REPLACE FUNCTION audit.table_log_master_insert_trigger()
RETURNS TRIGGER AS $$
BEGIN

----RAISE NOTICE "Rodando %,%", NEW.ds_table_name, '%' || left(NEW.ds_table_name,1) || '%';

IF strpos('ABC', left(NEW.ds_table_name,1)) > 0 THEN
   INSERT INTO audit."TABLES_LOG_ABC_P" VALUES (NEW.*);
   --RAISE NOTICE 'ABC';

ELSEIF strpos('DEF', left(NEW.ds_table_name,1)) > 0 THEN
   INSERT INTO audit."TABLES_LOG_DEF_P" VALUES (NEW.*);
   --RAISE NOTICE 'DEF';


ELSEIF strpos('GHI', left(NEW.ds_table_name,1)) > 0 THEN
   INSERT INTO audit."TABLES_LOG_GHI_P" VALUES (NEW.*);
   --RAISE NOTICE 'GHI';

ELSEIF strpos('JKL', left(NEW.ds_table_name,1)) > 0 THEN
   INSERT INTO audit."TABLES_LOG_JKL_P" VALUES (NEW.*);
   --RAISE NOTICE 'JKL';

ELSEIF strpos('MNO', left(NEW.ds_table_name,1)) > 0 THEN
   INSERT INTO audit."TABLES_LOG_MNO_P" VALUES (NEW.*);
   --RAISE NOTICE 'MNO';


ELSEIF strpos('PQR', left(NEW.ds_table_name,1)) > 0 THEN
   INSERT INTO audit."TABLES_LOG_PQR_P" VALUES (NEW.*);
   --RAISE NOTICE 'PQR';


ELSEIF strpos('STU', left(NEW.ds_table_name,1)) > 0 THEN
   INSERT INTO audit."TABLES_LOG_STU_P" VALUES (NEW.*);
   --RAISE NOTICE 'STU';


ELSEIF strpos('VXYWZ', left(NEW.ds_table_name,1)) > 0 THEN
   INSERT INTO audit."TABLES_LOG_VXYWZ_P" VALUES (NEW.*);
   --RAISE NOTICE 'VXYWZ';


END IF;


RETURN NULL;
END;
$$
LANGUAGE plpgsql;

/*
CREATE TRIGGER tables_log_master_insert_a_trigger
BEFORE INSERT ON audit."TABLES_LOG_MASTER"
FOR EACH ROW EXECUTE PROCEDURE audit.table_log_master_insert_trigger();

*/