<?php

include_once 'modelBasicExtend.php';

class unit_measure_type_model extends modelBasicExtend {

    function __construct() {
        parent::__construct();
        $this->hasDeactivate = false;


        $this->table = "UNIT_MEASURE_TYPE";

        $this->pk_field = "cd_unit_measure_type";
        $this->ds_field = "ds_unit_measure_type";

        $this->sequence_obj = '"unit_measure_type_cd_unit_measure_type_seq"';

        $this->controller = 'unit_measure_type';

        $this->fieldsforGrid = array('cd_unit_measure_type',
            'ds_unit_measure_type',
            'fl_is_length',
            'cd_unit_measure_reference',
            ' ( SELECT ds_unit_measure from "UNIT_MEASURE" where cd_unit_measure = ' . $this->db->escape_identifiers('UNIT_MEASURE_TYPE') . '.cd_unit_measure_reference ) as ds_unit_measure_reference'
        );

        $this->fieldsExcludeUpd = array('ds_unit_measure_reference');



        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            //'subselects' => '',
            'fields' => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            //'stylecond'  => "(CASE WHEN dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            'json' => true,
        );
    }

}

?>