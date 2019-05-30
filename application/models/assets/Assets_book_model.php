<?php

include_once APPPATH . "models/modelBasicExtend.php";

class assets_book_model extends modelBasicExtend {

    function __construct() {

        $this->table = "ASSETS_BOOK";

        $this->pk_field = "cd_assets_book";
        $this->ds_field = "ds_assets_book";
        $this->prodCatUnique = 'N';

        $this->sequence_obj = 'ASSETS_BOOK_cd_assets_book';

        $this->controller = 'assets/assets_book';


        $this->fieldsforGrid = array(
            ' "ASSETS_BOOK".cd_assets_book',
            ' "ASSETS_BOOK".ds_assets_book',
            ' "ASSETS_BOOK".dt_deactivated',
            ' "ASSETS_BOOK".dt_record');
        $this->fieldsUpd = array("cd_assets_book", "ds_assets_book", "dt_deactivated", "dt_record",);


        $this->retrOptions = array("fieldrecid" => $this->pk_field,
            "stylecond" => "(CASE WHEN \"ASSETS_BOOK\".dt_deactivated IS NOT NULL THEN 'color: rgb(255,0,0)' ELSE '' END )",
            "fields" => $this->cdbhelper->setSQLFieldsToGrid($this->fieldsforGrid),
            "json" => true
        );

        
        
        parent::__construct();
    }

}
