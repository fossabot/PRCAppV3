<?php

include_once APPPATH . 'models/modelBasicExtend.php';

class currency_rate_model extends modelBasicExtend {

    function __construct() {

        $this->table = "CURRENCY_RATE";

        $this->pk_field = "cd_currency_rate";
        $this->ds_field = "ds_currency_rate";

        $this->sequence_obj = '"CURRENCY_RATE_cd_currency_rate_seq"';


        $this->fieldsforGrid = array('cd_currency_rate',
            'ds_currency_rate',
            'cd_currency_from',
            '( select ds_currency from ' . $this->db->escape_identifiers('CURRENCY') . '  where cd_currency = ' . $this->db->escape_identifiers('CURRENCY_RATE') . '.cd_currency_from ) as ds_currency_from ',
            'cd_currency_to',
            '( select ds_currency from  ' . $this->db->escape_identifiers('CURRENCY') . ' where cd_currency = ' . $this->db->escape_identifiers('CURRENCY_RATE') . '.cd_currency_to ) as ds_currency_to ',
            'dt_currency_rate',
            'nr_currency_rate',
            'ds_currency_rate',
            'dt_deactivated');


        $this->fieldsExcludeUpd = array('ds_currency_from', 'ds_currency_to');

        $fields = $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid);

        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            'stylecond' => "(CASE WHEN dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            'fields' => $fields,
            'json' => true
        );



        parent::__construct();
    }

    public function getDemandedColumns() {
        $vlr = parent::getDemandedColumns();
        $vlr2 = array();
        
        foreach ($vlr as $key => $value) {
            if ($value !== 'ds_currency_rate') {
                array_push($vlr2, $value);
            }
        }

        return $vlr2;
    }

}
