<?php
include_once APPPATH."models/modelBasicExtend.php";

class project_product_x_project_power_type_model extends modelBasicExtend{
    

    
    function __construct()
    {
    
     $this->table = "PROJECT_PRODUCT_X_PROJECT_POWER_TYPE";

     $this->pk_field = "cd_project_product_x_project_power_type";
     $this->ds_field = "ds_project_product";
     $this->prodCatUnique = 'N';

     $this->sequence_obj = '"PROJECT_PRODUCT_X_PROJECT_POW_cd_project_product_x_project__seq"';
    
     $this->controller = 'tti/project_product_x_project_power_type';


     $this->fieldsforGrid = array(


' "PROJECT_PRODUCT_X_PROJECT_POWER_TYPE".cd_project_product_x_project_power_type', 
' "PROJECT_PRODUCT_X_PROJECT_POWER_TYPE".cd_project_product', 
 '( select ds_project_product FROM "PROJECT_PRODUCT" WHERE cd_project_product =  "PROJECT_PRODUCT_X_PROJECT_POWER_TYPE".cd_project_product) as ds_project_product', 
' "PROJECT_PRODUCT_X_PROJECT_POWER_TYPE".cd_project_power_type', 
 '( select ds_project_power_type FROM "PROJECT_POWER_TYPE" WHERE cd_project_power_type =  "PROJECT_PRODUCT_X_PROJECT_POWER_TYPE".cd_project_power_type) as ds_project_power_type', 
' "PROJECT_PRODUCT_X_PROJECT_POWER_TYPE".dt_deactivated' );
      $this->fieldsUpd = array ( "cd_project_product_x_project_power_type", "cd_project_product", "cd_project_power_type", "dt_deactivated",  ); 
 
        
                $this->retrOptions = array ("fieldrecid" => $this->pk_field,
                        "stylecond"  => "(CASE WHEN \"PROJECT_PRODUCT_X_PROJECT_POWER_TYPE\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
                        "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
                        "json" => true
                       ); 
                       

          parent::__construct();
    

    }
    }