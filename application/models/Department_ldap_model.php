<?php
include_once APPPATH."models/modelBasicExtend.php";

class department_ldap_model extends modelBasicExtend{
    

    
    function __construct()
    {
    
     $this->table = "DEPARTMENT_LDAP";

     $this->pk_field = "cd_department_ldap";
     $this->ds_field = "ds_department_ldap";
     $this->prodCatUnique = 'N';

     $this->sequence_obj = '"DEPARTMENT_LDAP_cd_department_ldap_seq"';
    
     $this->controller = 'department_ldap';


     $this->fieldsforGrid = array(


' "DEPARTMENT_LDAP".cd_department_ldap', 
' "DEPARTMENT_LDAP".ds_department_ldap', 
' "DEPARTMENT_LDAP".dt_deactivated', 
' "DEPARTMENT_LDAP".dt_record', 
' "DEPARTMENT_LDAP".ds_department_ldap_code', 
' "DEPARTMENT_LDAP".cd_department', 
 '( select ds_department FROM "DEPARTMENT" WHERE cd_department =  "DEPARTMENT_LDAP".cd_department) as ds_department', 
' "DEPARTMENT_LDAP".cd_jobs', 
 '( select ds_jobs FROM "JOBS" WHERE cd_jobs =  "DEPARTMENT_LDAP".cd_jobs) as ds_jobs', 
' "DEPARTMENT_LDAP".cd_roles', 
 '( select ds_roles FROM "ROLES" WHERE cd_roles =  "DEPARTMENT_LDAP".cd_roles) as ds_roles' );
      $this->fieldsUpd = array ( "cd_department_ldap", "ds_department_ldap", "dt_deactivated", "dt_record", "ds_department_ldap_code", "cd_department", "cd_jobs", "cd_roles",  ); 
 
        
                $this->retrOptions = array ("fieldrecid" => $this->pk_field,
                        "stylecond"  => "(CASE WHEN \"DEPARTMENT_LDAP\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
                        "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
                        "json" => true
                       ); 
                       

          parent::__construct();
    

    }
    }