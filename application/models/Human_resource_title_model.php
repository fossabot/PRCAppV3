<?php
include_once APPPATH . "models/modelBasicExtend.php";

class human_resource_title_model extends modelBasicExtend
{


    function __construct()
    {

        $this->table = "HUMAN_RESOURCE_TITLE";

        $this->pk_field = "cd_human_resource_title";
        $this->ds_field = "ds_human_resource_title";
        $this->prodCatUnique = 'N';


        $this->sequence_obj = '"HUMAN_RESOURCE_TITLE_cd_human_resource_title_seq"';
     $this->controller = 'human_resource_title';


     $this->fieldsforGrid = array(


         ' "HUMAN_RESOURCE_TITLE".cd_human_resource_title',
         ' "HUMAN_RESOURCE_TITLE".ds_human_resource_title',
         ' "HUMAN_RESOURCE_TITLE".dt_deactivated',
         ' "HUMAN_RESOURCE_TITLE".dt_record');
      $this->fieldsUpd = array("cd_human_resource_title", "ds_human_resource_title", "dt_deactivated", "dt_record",);
 
        
                $this->retrOptions = array("fieldrecid" => $this->pk_field,
                    "stylecond" => "(CASE WHEN \"HUMAN_RESOURCE_TITLE\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
                    "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
                    "json" => true
                );
                       

          parent::__construct();
    

    }
}