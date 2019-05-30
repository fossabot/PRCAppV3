<?php

include_once APPPATH . "models/modelBasicExtend.php";

class session_log_model extends modelBasicExtend {

   function __construct() {

      $this->table = "SESSION_LOG";

      $this->pk_field = "cd_session_log";
      $this->ds_field = "ds_database";

      $this->sequence_obj = '"SESSION_LOG_cd_session_log_seq"';

      $this->fieldsforGrid = array(
         'cd_session_log',
         'ds_database',
         'ds_session',
         'ds_username',
         "(to_char(dt_logged, 'mm/dd/yyyy hh:mi:ss') ) as ds_logged",
         "(to_char(dt_last_access, 'mm/dd/yyyy hh:mi:ss') ) as ds_last_access",
         "(to_char(dt_expired, 'mm/dd/yyyy hh:mi:ss') ) as ds_expired",
         '(NOW() - dt_last_access)::text as ds_interval');
      $this->fieldsExcludeUpd = array();

      $this->orderByDefault = 'ORDER BY ds_database, dt_logged';

      $this->retrOptions = array("fieldrecid" => $this->pk_field,
         //"stylecond" => "(CASE WHEN dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
         "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
         "json" => true
      );
   }

}
