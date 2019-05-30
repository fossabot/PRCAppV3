-- faz a carga dos system_relation (necessario para as FK)
select * from loadSysRel();

-- script paa ligacao da trigger!!!!

CREATE TRIGGER zzaudit_insert_update_delete
  AFTER INSERT OR UPDATE OR DELETE
  ON spec."SOLE_EDGE_SHAPE"
  FOR EACH ROW
  EXECUTE PROCEDURE audit.if_tablelog();

