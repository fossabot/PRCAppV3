<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class equipment_design extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("rfq/equipment_design_model", "mainmodel", TRUE);
        $this->load->model("rfq/equipment_design_sub_category_model", "subcatmodel", TRUE);
        $this->load->model("rfq/equipment_design_type_model", "typemodel", TRUE);

    }

    public function index() {

        parent::checkMenuPermission();


        $grid = $this->w2gridgen;
        $f = $this->cfields;
        $fm = $this->cfiltermaker;

        if (1 == 2) {
            $f = new Cfields();
            $grid = new w2gridgen();
            $fm = new cfiltermaker();
        }

        $fm = $this->cfiltermaker;

        $fm->addSimpleFilterUpper('Description', 'filter_1', '"EQUIPMENT_DESIGN".ds_equipment_design');

        $fl_rfq_only_see_equipment_warehouse = $this->getCdbhelper()->getUserPermission('fl_rfq_only_see_equipment_warehouse');
        if ($fl_rfq_only_see_equipment_warehouse == 'Y') {
            $loadType=$this->typemodel->selectForPL(" WHERE \"EQUIPMENT_DESIGN_TYPE\".cd_equipment_design_type = '4'");
            $fm->addFilter('filter_5', 'Type', array('selectedData' => $loadType, 'controller' => 'rfq/equipment_design_type', 'fieldname' => '"EQUIPMENT_DESIGN_CATEGORY".cd_equipment_design_type','locked'=>true));
        }
        else {
            $fm->addPickListFilter('Type', 'filter_5', 'rfq/equipment_design_type', '"EQUIPMENT_DESIGN_CATEGORY".cd_equipment_design_type');
        }
        $fm->addPickListFilterWithRel('Category', 'filter_6', 'rfq/equipment_design_category', 'filter_5', '"EQUIPMENT_DESIGN_SUB_CATEGORY".cd_equipment_design_category');
        $fm->addPickListFilterWithRel('Sub Category', 'filter_2', 'rfq/equipment_design_sub_category', 'filter_6', '"EQUIPMENT_DESIGN".cd_equipment_design_sub_category');

        $fm->addFilterYesNo("Active", "dt_deactivated", '"EQUIPMENT_DESIGN".dt_deactivated', "Y");


        $this->setGridParser();
        $grid->setSingleBarControl(true);
//
        $fl_rfq_allow_equipment_maintain = $this->getCdbhelper()->getUserPermission('fl_rfq_allow_equipment_maintain');
        $fl_allow_equipment_insert_delete = $this->getCdbhelper()->getUserPermission('fl_allow_equipment_insert_delete');
        $allows = $fl_allow_equipment_insert_delete == 'Y' && $fl_rfq_allow_equipment_maintain == 'Y';
        $grid->addCRUDToolbar(true, $allows, $fl_rfq_allow_equipment_maintain == 'Y', $allows, true);

        $grid->addDocRepToolbar();
        $grid->setToolbarSearch(true);
        $grid->setFilterPresetId('equipfilter');
        //$grid->addUserBtnToolbar('importWH', 'Import from Warehouse', 'fa fa-upload' , 'Import from Warehouse');
        $grid->setCRUDController("rfq/equipment_design");
        $grid->setDocRepId(4);
        if ($allows) $grid->addUserBtnToolbar('insertWithContent', 'Duplicate Selected Line', 'fa fa-files-o');

        $grid->addColumnKey();
        $grid->addHiddenColumn('cd_equipment_design_image', 'Image', '50px', $f->retTypeFirstPicture(), false);
        $grid->addColumn('ds_code', 'Number', '80px', $f->retTypeStringAny(), false);
        $grid->addColumn('ds_equipment_design', 'Description', '300px', $f->retTypeStringAny(), array('limit' => '128'));
        
        if ($fl_allow_equipment_insert_delete == 'Y') {
            $grid->addColumn('ds_equipment_design_code_alternate', 'Alternate Code', '120px', $f->retTypeStringAny(), array('limit' => '128'));
        }
        
        $grid->addColumn('ds_equipment_design_type', 'Type', '100px', $f->retTypePickList(), array('model' => 'rfq/equipment_design_type_model', 'codeField' => 'cd_equipment_design_type'));
        $grid->addColumn('ds_equipment_design_category', 'Category', '100px', $f->retTypePickList(), array('model' => 'rfq/equipment_design_category_model', 'codeField' => 'cd_equipment_design_category'));
        $grid->addColumn('ds_equipment_design_sub_category', 'Sub Category', '150px', $f->retTypePickList(), array('model' => 'rfq/equipment_design_sub_category_model', 'codeField' => 'cd_equipment_design_sub_category'));
        $grid->addColumn('nr_series', 'Series', '80px', $f->retTypeInteger(), true);
        $grid->addColumn('nr_attachment_count', 'Attachment', '60px', $f->retTypeInteger(), false);
        $grid->addColumn('ds_brand', 'Brand', '100px', $f->retTypeStringAny(), array('limit' => '10000'));
        $grid->addColumn('ds_unit_measure', 'Unit', '100px', $f->retTypePickList(), array('model' => 'unit_measure_model', 'codeField' => 'cd_unit_measure'));
        
        
        
        $grid->addColumn('ds_technical_description', 'Technical Description', '200px', $f->retTypeTextPL(), array('limit' => '10000'));
        $grid->addColumn('ds_technical_description_english', 'Technical Description English', '200px', $f->retTypeTextPL(), array('limit' => '10000'));
        
        $grid->addColumn('ds_website', 'Website', '100px', $f->retTypeTextPL(), array('limit' => '10000'));
        $grid->addColumn('ds_remarks', 'Remarks', '100px', $f->retTypeTextPL(), array('limit' => '10000'));
        $grid->addColumn('ds_remarks_english', 'Remarks English', '100px', $f->retTypeTextPL(), array('limit' => '10000'));
        
        $grid->addColumn('nr_grade', 'Grade', '150px', $f->retTypeInteger(), true);

        $grid->setColumnRenderFunc('ds_equipment_design_type', 'dsMainObject.setTypeRender');
        $grid->setColumnRenderFunc('ds_equipment_design_category', 'dsMainObject.setTypeRender');
        $grid->setColumnRenderFunc('ds_equipment_design_sub_category', 'dsMainObject.setTypeRender');

        $grid->setColumnRenderFunc('nr_series', 'dsMainObject.setSeriesRender');
        
        $grid->addColumnDeactivated(true);




        //$grid->addColumn('ds_human_resource_applied_by', 'Applied By', '150px', $f->retTypePickList(), array('model' => 'human_resource_model', 'codeField' => 'cd_human_resource_applied_by'));
        //$grid->addColumn('dt_applied At', 'Applied', '80px', $f->retTypeDate(), true);

        $filters = $fm->retFiltersWithGroup();
        $javascript = $grid->retGrid();

        $trans = array('equipment' => 'Equipment');
        $trans = $this->cdbhelper->retTranslationDifKeys($trans);

        $send = array("javascript" => $javascript,
            "filters" => $filters,
                "allow_equipment_maintain" => $fl_rfq_allow_equipment_maintain,
            "filters_java" => $fm->retJavascript()) + $trans;


        $this->load->view("rfq/equipment_design_view", $send);
    }

    public function importfromWH() {
        $DB = $this->load->database('tr', true);
        $ret = $DB->query("select PartNumber, min(FullDesc) as FullDesc, min(Remark) as Remark from [dbo].[MAS_Material] where PartNumber like '992%' group by PartNumber")->result_array();
        $array_to_add = array();
        $errorMsg = '';
        $count = 0;
        $checkdup = array();
        foreach ($ret as $key => $value) {

            $sub = substr($value['PartNumber'], 0, 6);
            $series = substr($value['PartNumber'], -3);
            $desc = $value['FullDesc'];
            $remark = $value['Remark'];

            $subarray = $this->subcatmodel->retRetrieveGridArray(" WHERE \"EQUIPMENT_DESIGN_SUB_CATEGORY\".ds_name_code = '$sub'");

            if (count($subarray) == 0) {
                $errorMsg = $errorMsg . "<br>Error: Sub Catetory does not exists for $sub - $desc";
                continue;
            }
            
            $x = $subarray[0]['recid'].'-'.$series;
            
            if (array_search($x, $checkdup) !== FALSE) {
                continue;
            }
            
            
            array_push($checkdup, $x);
            

            $equi = $this->mainmodel->retRetrieveGridArray(" WHERE \"EQUIPMENT_DESIGN\".cd_equipment_design_sub_category = '".$subarray[0]['recid']."' AND \"EQUIPMENT_DESIGN\".nr_series = $series");

            // already exist, so skip
            if (count($equi) != 0) {
                continue;
            }

            $count  ++;
            
            if ($count > 400) {
                break;
            }
            
            array_push($array_to_add, array(
                'recid' => $this->mainmodel->getNextCode(),
                'cd_equipment_design_sub_category' => $subarray[0]['recid'],
                'ds_equipment_design' => $desc,
                'ds_remarks' => $remark,
                'nr_series' => $series
            ));
        }

        
        $errordb = $this->mainmodel->updateGridData($array_to_add);
        if ($errordb != "OK") {
           $error = $errordb;
        } else {
           $error = 'Done - Added '. count($array_to_add);   
        }
        
        
        if ($errorMsg != '') {
            $error = $error . ' with messages. See Below:' . $errorMsg ;   
        }
        

        $msg = '{"status":' . json_encode($error) . ', "rs":{} }';

        echo($msg);
    }

}
