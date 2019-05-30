<?php

include_once APPPATH . "models/modelBasicExtend.php";

class system_parameters_model extends modelBasicExtend {

    function __construct() {

        $this->table = "SYSTEM_PARAMETERS";
        $this->tableEscaped = $this->db->escape_identifiers($this->table);
        $this->pk_field = "cd_system_parameters";
        $this->ds_field = "ds_system_parameters";

        $this->sequence_obj = '"SYSTEM_PARAMETERS_cd_system_parameters_seq"';

        $this->controller = 'system_parameters';


        $this->fieldsforGrid = array(
            ' cd_system_parameters',
            ' ds_system_parameters',
            ' ds_system_parameters_id',
            ' ds_system_parameters_obs',
            ' ds_system_parameters_value');
        
        $this->fieldsExcludeUpd = array('ds_system_parameters', 'ds_system_parameters_id', 'ds_system_parameters_obs');


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );


        parent::__construct();
    }

    public function getParameter($parameter) {

        //$cd_human_resource = $this->session->userdata('cd_human_resource');


        $sql = 'SELECT ds_system_parameters_value '
                . '    FROM '.$this->tableEscaped.' h '
                . '   where h.ds_system_parameters_id =  ?';

        $query = $this->db->query($sql, array($parameter));

        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row->ds_system_parameters_value;
        }
    }

}
