<?php

include_once APPPATH . "models/modelBasicExtend.php";

class product_document_repository_type_model extends modelBasicExtend {

    function __construct() {

        $this->table = "PRODUCT_DOCUMENT_REPOSITORY_TYPE";

        $this->pk_field = "cd_product_document_repository_type";
        $this->ds_field = "ds_product_document_repository_type";

        $this->sequence_obj = '"PRODUCT_DOCUMENT_REPOSITORY_T_cd_product_document_repositor_seq"';

        $this->controller = 'docrep/product_document_repository_type';
        $this->orderByDefault = ' ORDER BY fl_default ASC, ds_product_document_repository_type ASC';

        $this->fieldsforGrid = array(
            'cd_product_document_repository_type',
            'ds_product_document_repository_type',
            'fl_default',
            'fl_show_on_selection',
            'fl_show_on_spec_sheet',
            'dt_deactivated',
            'dt_record');
        $this->fieldsExcludeUpd = array();


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "stylecond" => "(CASE WHEN dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );


        parent::__construct();
    }

}
