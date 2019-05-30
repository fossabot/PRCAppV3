<?php

include_once APPPATH . 'models/modelBasicExtend.php';

class job_department_model extends modelBasicExtend {

    function __construct() {

        $this->table = "DEPARTMENT";
        $this->pk_field = "cd_department";
        $this->ds_field = "ds_department";

        $this->sequence_obj = '"DEPARTMENTS_cd_department"';
                

        $this->fieldsforGrid = array($this->pk_field,
            $this->ds_field,          
            '"DEPARTMENT".ds_department_code',
            'dt_deactivated');

        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            'stylecond' => "(CASE WHEN dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            'fields' => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            'json' => true
        );

        $this->fieldsUpd = array ("ds_department_code", "ds_department", "dt_deactivated", "dt_record"); 

        $this->fieldsForPLBaseDD = array($this->pk_field,
            $this->ds_field,
            'ds_department_code'
        );

        
        
        
        $this->controller = 'job_department';

        parent::__construct();
    }

}

?>