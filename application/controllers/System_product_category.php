<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class system_product_category extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("system_product_category_model", "mainmodel", TRUE);
    }

    public function index() {

        parent::checkMenuPermission();

        // grid object
        $grid = $this->w2gridgen;
        // field type objects
        $f = $this->cfields;
        // filter maker object
        $fm = $this->cfiltermaker;

        if (1 == 2) {
            $f = new Cfields();
            $grid = new w2gridgen();
            $fm = new cfiltermaker();
        }


        // we will not have grid, so I commented this part
        /*
        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("system_product_category_view");

        $grid->addColumnKey();

        $grid->addColumn('ds_system_product_category', 'System Product Category', '150px', $f->retTypeTextPL(), true);
        $grid->addColumn('ds_icon', 'Icon', '150px', $f->retTypeTextPL(), true);
        $grid->addColumn('nr_order', 'Order', '150px', $f->retTypeInteger(), true);


        $filters = $fm->retFiltersWithGroup();
        $javascript = $grid->retGrid();

*/
        
        // for any translation purpose (key => translation)
        $trans = array();
        $trans = $this->cdbhelper->retTranslationDifKeys($trans);

        // return the database data, in a array. If want json directly can use retRetrieveGridJson
        $array_prod = $this->mainmodel->retRetrieveGridArray(" WHERE 1 = 1 ", " ORDER BY nr_order ");
        $html = '';
        foreach ($array_prod as $key => $value) {
            // concatenate the HTML for each tile (when 3rd parameter is true). So they will be side by side. The value is sent to the view, and you can get it as variables.
            //$html = $html . $this->load->view("system_product_category_tiles_view", $value, true);
        }

        // send the tiles and the trans object (to have the labels translated). In this case still no translated information
        $send = array("htmlTiles" => $html) + $trans;

        $this->load->view("tiles/project_tile_view", $send);
    }

}
