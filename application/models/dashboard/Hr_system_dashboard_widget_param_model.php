<?php

include_once APPPATH . "models/modelBasicExtend.php";

class hr_system_dashboard_widget_param_model extends modelBasicExtend {

    function __construct() {

        $this->table = "HR_SYSTEM_DASHBOARD_WIDGET_PARAM";

        $this->pk_field = "cd_hm_system_dashboard_widget_param";
        $this->ds_field = "ds_human_resource";
        $this->prodCatUnique = 'Y';

        $this->sequence_obj = '"HR_SYSTEM_DASHBOARD_WIDGET_PA_cd_hm_system_dashboard_widget_seq"';

        $this->controller = 'hr_system_dashboard_widget_param';


        $this->fieldsforGrid = array(
            $this->db->escape_identifiers("HR_SYSTEM_DASHBOARD_WIDGET_PARAM").'.cd_hm_system_dashboard_widget_param',
            $this->db->escape_identifiers("HR_SYSTEM_DASHBOARD_WIDGET_PARAM").'.cd_human_resource',
            $this->db->escape_identifiers("HR_SYSTEM_DASHBOARD_WIDGET_PARAM").'.cd_system_dashboard_widget',
            $this->db->escape_identifiers("HR_SYSTEM_DASHBOARD_WIDGET_PARAM").'.json_parameters',
            $this->db->escape_identifiers("HR_SYSTEM_DASHBOARD_WIDGET_PARAM").'.nr_order' );
        $this->fieldsExcludeUpd = array();


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );


        parent::__construct();
    }
    
    public function getNextOrder ($cd_human_resource) {
        $sql = 'select COALESCE(max(nr_order), 0) + 1 as nr_next from '.$this->db->escape_identifiers("HR_SYSTEM_DASHBOARD_WIDGET_PARAM").' where cd_human_resource = '.$cd_human_resource .' AND cd_system_product_category = ' . $this->db->cd_system_product_category;
        
       $ret = $this->getCdbhelper()->basicSQLArray($sql);
       
       return $ret[0]['nr_next'];
       
        
    }

}
