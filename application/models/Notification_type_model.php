<?php
include_once APPPATH."models/modelBasicExtend.php";

class notification_type_model extends modelBasicExtend{
    

    
    function __construct()
    {
    
     $this->table = "NOTIFICATION_TYPE";

     $this->pk_field = "cd_notification_type";
     $this->ds_field = "ds_notification_type";
     $this->prodCatUnique = 'N';

     $this->sequence_obj = '"NOTIFICATION_TYPE_cd_notification_type_seq"';
    
     $this->controller = 'notification_type';


     $this->fieldsforGrid = array(


' "NOTIFICATION_TYPE".cd_notification_type', 
' "NOTIFICATION_TYPE".ds_notification_type', 
' "NOTIFICATION_TYPE".fl_email_every_change', 
' "NOTIFICATION_TYPE".fl_email_once_a_day', 
' "NOTIFICATION_TYPE".fl_system_notification', 
' "NOTIFICATION_TYPE".dt_deactivated', 
' "NOTIFICATION_TYPE".dt_record' );
      $this->fieldsUpd = array ( "cd_notification_type", "ds_notification_type", "fl_email_every_change", "fl_email_once_a_day", "fl_system_notification", "dt_deactivated", "dt_record",  ); 
 
        
                $this->retrOptions = array ("fieldrecid" => $this->pk_field,
                        "stylecond"  => "(CASE WHEN \"NOTIFICATION_TYPE\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
                        "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
                        "json" => true
                       ); 
                       

          parent::__construct();
    

    }
    }