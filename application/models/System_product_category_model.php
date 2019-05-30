<?php

include_once APPPATH . "models/modelBasicExtend.php";

class system_product_category_model extends modelBasicExtend {

    function __construct() {

        $this->table = "SYSTEM_PRODUCT_CATEGORY";

        $this->pk_field = "cd_system_product_category";
        $this->ds_field = "ds_system_product_category";

        $this->sequence_obj = '';

        $this->controller = 'system_product_category';

        $this->fieldsforGrid = array(
            'cd_system_product_category',
            'ds_system_product_category',
            'ds_icon',
            'nr_order');
        
        $this->fieldsExcludeUpd = array();
        $this->hasDeactivate = false;


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );
    }

    
    public function retGridJsonByHM($cd_hmresource, $mode = 'B', $fieldsForSelection = false) {
        return $this->retGridJsonWithRelation($cd_hmresource, 'HUMAN_RESOURCE_X_SYSTEM_PRODUCT_CATEGORY', 'cd_human_resource', $mode, $fieldsForSelection);
    }
    
    public function getProductCategoryByUser($cd_human_resource) {
        $fl_super_user = $this->session->userdata('fl_super_user');
        //$fl_super_user = 'N';
        if ($fl_super_user == 'Y') {
            $sql = ' WHERE 1 = 1';
        } else {
            $sql = ' WHERE EXISTS ( SELECT 1 '
                    . '        FROM ' . $this->db->escape_identifiers('HUMAN_RESOURCE_X_SYSTEM_PRODUCT_CATEGORY') . ' x'
                    . '                                WHERE x.cd_system_product_category = ' . $this->db->escape_identifiers('SYSTEM_PRODUCT_CATEGORY') . '.cd_system_product_category '
                    . '                                  AND x.cd_human_resource              = ' . $cd_human_resource . ' '
                    . '                                  AND x.dt_deactivated IS NULL )'
                    . '                   OR EXISTS ( SELECT 1 '
                    . '                             FROM ' . $this->db->escape_identifiers('JOBS_HUMAN_RESOURCE') . ' a, '
                    . '                             ' . $this->db->escape_identifiers('JOBS_X_SYSTEM_PRODUCT_CATEGORY') . '  x '
                    . '                  WHERE a.cd_human_resource = ' . $cd_human_resource
                    . '                    AND a.dt_deactivated IS NULL '
                    . '                    AND x.cd_jobs = a.cd_jobs '
                    . '                    AND x.cd_system_product_category = ' . $this->db->escape_identifiers('SYSTEM_PRODUCT_CATEGORY') . '.cd_system_product_category '
                    . '                    AND x.dt_deactivated IS NULL ) ';
        }
        
        
        $ret = $this->retRetrieveGridArray($sql, 'ORDER BY  nr_order');
                
        return $ret;

    }

}
