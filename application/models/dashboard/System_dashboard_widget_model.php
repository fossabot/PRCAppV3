<?php

include_once APPPATH . "models/modelBasicExtend.php";

class system_dashboard_widget_model extends modelBasicExtend {

    function __construct() {

        $this->table = "SYSTEM_DASHBOARD_WIDGET";

        $this->pk_field = "cd_system_dashboard_widget";
        $this->ds_field = "ds_system_dashboard_widget";

        $this->sequence_obj = '';

        $this->controller = 'system_dashboard_widget';

        $this->fieldsforGrid = array(
            $this->db->escape_identifiers("SYSTEM_DASHBOARD_WIDGET").'.cd_system_dashboard_widget',
            $this->db->escape_identifiers("SYSTEM_DASHBOARD_WIDGET").'.ds_system_dashboard_widget',
            $this->db->escape_identifiers("SYSTEM_DASHBOARD_WIDGET").'.ds_comments',
            $this->db->escape_identifiers("SYSTEM_DASHBOARD_WIDGET").'.ds_comments_system',
            $this->db->escape_identifiers("SYSTEM_DASHBOARD_WIDGET").'.ds_system_permissions',
            $this->db->escape_identifiers("SYSTEM_DASHBOARD_WIDGET").'.dt_deactivated');
        $this->fieldsExcludeUpd = array();
        
        $user = $this->session->userdata('cd_human_resource');
        
        $fixedwhere = " AND ( ds_system_permissions IS NULL OR getUserPermission(ds_system_permissions, $user) = 'Y') ";

        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "stylecond" => "(CASE WHEN ".$this->db->escape_identifiers("SYSTEM_DASHBOARD_WIDGET").".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true,
            'forcedwhere' => $fixedwhere
        );


        
        $this->fieldsforGridByUser = array(
            $this->db->escape_identifiers("SYSTEM_DASHBOARD_WIDGET").'.cd_system_dashboard_widget',
            $this->db->escape_identifiers("SYSTEM_DASHBOARD_WIDGET").'.ds_system_dashboard_widget',
            $this->db->escape_identifiers("SYSTEM_DASHBOARD_WIDGET").'.ds_comments',
            $this->db->escape_identifiers("SYSTEM_DASHBOARD_WIDGET").'.ds_comments_system',
            $this->db->escape_identifiers("SYSTEM_DASHBOARD_WIDGET").'.dt_deactivated', 
            $this->db->escape_identifiers("HR_SYSTEM_DASHBOARD_WIDGET_PARAM").'.cd_hm_system_dashboard_widget_param',
            $this->db->escape_identifiers("HR_SYSTEM_DASHBOARD_WIDGET_PARAM").'.cd_human_resource',
            $this->db->escape_identifiers("HR_SYSTEM_DASHBOARD_WIDGET_PARAM").'.json_parameters',
            $this->db->escape_identifiers("HR_SYSTEM_DASHBOARD_WIDGET_PARAM").'.nr_order');

        $this->joinsForGridUser = array(
            ' INNER JOIN '.$this->db->escape_identifiers("HR_SYSTEM_DASHBOARD_WIDGET_PARAM").' ON ( '.$this->db->escape_identifiers("HR_SYSTEM_DASHBOARD_WIDGET_PARAM").'.cd_system_dashboard_widget = '.$this->db->escape_identifiers("SYSTEM_DASHBOARD_WIDGET").'.cd_system_dashboard_widget ) ',
        );
        
        
        $this->retrOptionsUser = array("fieldrecid" => $this->db->escape_identifiers("HR_SYSTEM_DASHBOARD_WIDGET_PARAM").'.cd_hm_system_dashboard_widget_param',
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGridByUser),
            'join' => $this->joinsForGridUser,
            "json" => false,
            'forcedwhere' => $fixedwhere

        );

        
        parent::__construct();
    }
    
    
    public function getUserDashboard($where = '') {
        return $this->retRetrieveArray(' WHERE '.$this->db->escape_identifiers("HR_SYSTEM_DASHBOARD_WIDGET_PARAM").'.cd_human_resource = ' . $this->db->cd_human_resource . '  AND cd_system_product_category = ' . $this->db->cd_system_product_category . $where, ' ORDER BY nr_order ', $this->retrOptionsUser);
        }
}
