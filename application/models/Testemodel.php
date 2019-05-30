<?php

include_once APPPATH . "models/modelBasicExtend.php";

class country_model extends modelBasicExtend {

    function __construct() {

        $this->table = "SHOE_SPECIFICATION";

        $this->pk_field = "cd_shoe_specification";
        $this->ds_field = "ds_shoe_specification";

        $this->sequence_obj = '"SHOE_SPECIFICATION_cd_shoe_specification_seq"';

        $this->fieldsforGrid = array(
            'cd_shoe_specification',
            'ds_shoe_specification',
            'nr_shoe_specification_build',
            'cd_generic_shoe_specification',
            '( select ds_generic_shoe_specification FROM "GENERIC_SHOE_SPECIFICATION" WHERE cd_generic_shoe_specification =  "SHOE_SPECIFICATION".cd_generic_shoe_specification) as ds_generic_shoe_specification',
            'cd_division',
            '( select  FROM "DIVISION_X_DIVISION_BRAND" WHERE cd_division =  "SHOE_SPECIFICATION".cd_division) as ds_division',
            'cd_division_brand',
            '( select  FROM "DIVISION_X_DIVISION_BRAND" WHERE cd_division =  "SHOE_SPECIFICATION".cd_division_brand) as ds_division_brand',
            'cd_season',
            'cd_construction',
            '( select ds_construction FROM "CONSTRUCTION" WHERE cd_construction =  "SHOE_SPECIFICATION".cd_construction) as ds_construction',
            'cd_last',
            '( select ds_last FROM "CONSTRUCTION_X_LAST" WHERE cd_construction =  "SHOE_SPECIFICATION".cd_last) as ds_last',
            'cd_upper_edge',
            '( select ds_upper_edge FROM "UPPER_EDGE" WHERE cd_upper_edge =  "SHOE_SPECIFICATION".cd_upper_edge) as ds_upper_edge',
            'cd_outsole_design',
            'cd_stitch',
            '( select ds_stitch FROM "STITCH" WHERE cd_stitch =  "SHOE_SPECIFICATION".cd_stitch) as ds_stitch',
            'cd_stitch_sock',
            '( select ds_stitch FROM "STITCH" WHERE cd_stitch =  "SHOE_SPECIFICATION".cd_stitch_sock) as ds_stitch_sock',
            'cd_product_ornament',
            '( select ds_product FROM "PRODUCT" WHERE cd_product =  "SHOE_SPECIFICATION".cd_product_ornament) as ds_product_ornament',
            'ds_product_ornament_comment',
            'cd_sock_logo',
            '( select ds_sock_logo FROM "SOCK_LOGO" WHERE cd_sock_logo =  "SHOE_SPECIFICATION".cd_sock_logo) as ds_sock_logo',
            'dt_deactivated',
            'dt_record',
            'cd_product_type_lining_material',
            '( select ds_product_type FROM "PRODUCT_TYPE" WHERE cd_product_type =  "SHOE_SPECIFICATION".cd_product_type_lining_material) as ds_product_type_lining_material',
            'cd_product_type_upper',
            '( select ds_product_type FROM "PRODUCT_TYPE" WHERE cd_product_type =  "SHOE_SPECIFICATION".cd_product_type_upper) as ds_product_type_upper',
            'cd_product_type_sock_lining',
            '( select ds_product_type FROM "PRODUCT_TYPE" WHERE cd_product_type =  "SHOE_SPECIFICATION".cd_product_type_sock_lining) as ds_product_type_sock_lining',
            'cd_shoe_type',
            '( select ds_shoe_type FROM "SHOE_TYPE" WHERE cd_shoe_type =  "SHOE_SPECIFICATION".cd_shoe_type) as ds_shoe_type',
            'cd_shoe_type_category',
            '( select ds_shoe_type_category FROM "SHOE_TYPE_CATEGORY" WHERE cd_shoe_type =  "SHOE_SPECIFICATION".cd_shoe_type_category) as ds_shoe_type_category');

        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "stylecond" => "(CASE WHEN dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );
    }

}
