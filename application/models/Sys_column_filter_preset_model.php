<?php

include_once APPPATH . "models/modelBasicExtend.php";

class sys_column_filter_preset_model extends modelBasicExtend {

    function __construct() {

        $this->table = "SYS_COLUMN_FILTER_PRESET";

        $this->pk_field = "cd_sys_column_filter_preset";
        $this->ds_field = "ds_system_product_category";

        $this->sequence_obj = '"SYS_COLUMN_FILTER_PRESET_cd_sys_column_filter_preset_seq"';

        $this->controller = 'sys_column_filter_preset';
        $this->orderByDefault = ' ORDER BY ds_sys_column_filter_preset';


        $this->fieldsforGrid = array(
            ' cd_sys_column_filter_preset',
            ' cd_system_product_category',
            ' cd_human_resource',
            ' ds_grid_id',
            ' ds_sys_column_filter_preset',
            ' jsonb_column_filter_data',
            ' fl_default',
            ' dt_record');
        $this->fieldsExcludeUpd = array();


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            //"stylecond" => "(CASE WHEN \"SYS_COLUMN_FILTER_PRESET\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );


        parent::__construct();
    }

    public function getPresetForID($id) {
        $hm = $this->session->userdata('cd_human_resource');
        $cat = $this->db->cd_system_product_category;

        $where = " WHERE cd_human_resource = %s AND cd_system_product_category = %s AND ds_grid_id = '%s'";
        $where = sprintf($where, $hm, $cat, $id);


        $data = $this->retRetrieveArray($where);
        $tst = '';
        $vIdxModel = 0;
        foreach ($data as $key => $value) {
            $js = json_decode($value['jsonb_column_filter_data']);
            $vIdxModel ++;
            if (!isset($js->filter)) {
                continue;
            }
            foreach ($js->filter as $key2 => $value2) {
                $vIdxModel ++;

                if ($value2->kind == 'PL' && isset($value2->controller)) {
                    $model = $this->getCdbhelper()->getModelFromController($value2->controller);
                    $idx = 'contr'.$vIdxModel;
                    $this->load->model($model,$idx, false );

                    
                    
                    $ret = $this->$idx->selectForPL(' WHERE ' . $this->$idx->pk_field . ' = ' . $value2->id);
                    
                    
                    $tst = $tst . '<br>'.$this->$idx->pk_field . ' - '.$value2->controller;
                    if (count($ret) == 1) {
                        $js->filter[$key2]->description = $ret[0]['description'];
                        $js->filter[$key2]->fl_active = $ret[0]['fl_active'];
                    }
                    
                    
                }
            }
            
            $data[$key]['jsonb_column_filter_data'] = json_encode($js);
            
        }
        

        //die (print_r($data));

        return $data;
    }

}
