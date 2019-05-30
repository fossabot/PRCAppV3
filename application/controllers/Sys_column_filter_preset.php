<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class sys_column_filter_preset extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("sys_column_filter_preset_model", "mainmodel", TRUE);
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




        $this->setGridParser();
        $grid->setSingleBarControl(true);
        $grid->addCRUDToolbar();
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("sys_column_filter_preset");

        $grid->addColumnKey();

        $grid->addColumn('cd_system_product_category', 'System Product Category', '150px', $f->retTypeInteger(), true);
        $grid->addColumn('cd_human_resource', 'Human Resource', '150px', $f->retTypeInteger(), true);
        $grid->addColumn('ds_grid_id', 'Grid Id', '150px', $f->retTypeTextPL(), true);
        $grid->addColumn('ds_sys_column_filter_preset', 'Sys Column Filter Preset', '150px', $f->retTypeTextPL(), true);


        $filters = $fm->retFiltersWithGroup();
        $javascript = $grid->retGrid();


        $trans = array();
        $trans = $this->cdbhelper->retTranslationDifKeys($trans);



        $send = array("javascript" => $javascript,
            "filters" => $filters,
            "filters_java" => $fm->retJavascript()) + $trans;


        $this->load->view("defaultView", $send);
    }

    public function updatePreset($id) {
        $upd = $_POST['upd'];

        //die(print_r((object)$upd));

        $hm = $this->session->userdata('cd_human_resource');
        $cat = $this->db->cd_system_product_category;

        $json = array('cd_system_product_category' => $cat,
            'cd_human_resource' => $hm,
            'ds_grid_id' => $id,
            'recid' => $upd['recid']
        );

        if (isset($upd['colInfo'])) {
            $json['jsonb_column_filter_data'] = json_encode($upd['colInfo']);
        }

        if (isset($upd['title'])) {
            $json['ds_sys_column_filter_preset'] = $upd['title'];
        }

        
        if (isset($upd['fl_default'])) {
            $json['fl_default'] = $upd['fl_default'];
        }

        $row = (object) array((object) $json);

        $error = $this->mainmodel->updateGridData($row);
        //die('dentro do basic');

        $msg = '{"status":' . json_encode($error);


        $retResult = '{}';

        if ($error == 'OK') {



            $retResult = json_encode($this->mainmodel->getPresetForID($id));


            $msg = $msg . ', "rs": ' . $retResult;
        }


        $msg = $msg . '}';
        
        //

        echo $msg;
    }

    
    
    public function deletePreset($id, $recid) {
        $error = $this->mainmodel->deleteGridData(array($recid));
        //die('dentro do basic');

        $msg = '{"status":' . json_encode($error);


        $retResult = '{}';

        if ($error == 'OK') {



            $retResult = json_encode($this->mainmodel->getPresetForID($id));


            $msg = $msg . ', "rs": ' . $retResult;
        }


        $msg = $msg . '}';
        
        //

        echo $msg;
    }
    
    
    public function sharePreset() {
        $hm_to   = $this->getCdbhelper()->normalizeDataToSQL('int', $_POST['user']);
        $hm_from = $this->getCdbhelper()->normalizeDataToSQL('int', $this->session->userdata('cd_human_resource'));
        $preset  = $this->getCdbhelper()->normalizeDataToSQL('int', $_POST['recid']);
        
        $ret = $this->mainmodel->retRetrieveGridArray(" WHERE cd_sys_column_filter_preset = $preset");
        
        $code = $this->mainmodel->getNextCode();
        if (count($ret) == 0) {
            die ('Error getting the Preset');
        }
        
        $desc       = $ret[0]['ds_sys_column_filter_preset'];
        $ds_grid_id = $ret[0]['ds_grid_id'];
        $newdesc = $desc;
        $count = 1;
        
        $select = ' WHERE ds_grid_id = %s AND cd_human_resource = %s AND ds_sys_column_filter_preset = %s ';
        
        $existsRows = $this->mainmodel->retRetrieveGridArray( sprintf($select, 
                    $this->getCdbhelper()->normalizeDataToSQL('char', $ds_grid_id),
                    $this->getCdbhelper()->normalizeDataToSQL('int', $hm_to),
                    $this->getCdbhelper()->normalizeDataToSQL('char', $newdesc) 
                ) );

        $exists  = count($existsRows) > 0;
        while ($exists) {
            $newdesc = $desc.'_'.$count;
            
            $existsRows = $this->mainmodel->retRetrieveGridArray( sprintf($select, 
                        $this->getCdbhelper()->normalizeDataToSQL('char', $ds_grid_id),
                        $this->getCdbhelper()->normalizeDataToSQL('int', $hm_to),
                        $this->getCdbhelper()->normalizeDataToSQL('char', $newdesc) 
                    ) );

            
            $exists  = count($existsRows) > 0;
            
            
            
            $count ++;
            
        }
        
        $ret[0]['recid'] = $code;
        $ret[0]['ds_sys_column_filter_preset'] = $newdesc;
        $ret[0]['cd_human_resource'] = $hm_to;
        $ret[0]['cd_human_resource_shared_from'] = $hm_from;
        $ret[0]['fl_default'] = 'N';
        unset($ret[0]['cd_sys_column_filter_preset']);
        
        $error = $this->mainmodel->updateGridData($ret);
        $msgOk = '';
        if ($error == 'OK') {
            $msgOk = $this->getCdbhelper()->retTranslation('Preset Shared Successfully');
        }
        
        $array = array('status' => $error, 'msg' => $msgOk);
        
        $msg = json_encode($array);

        echo $msg;
        
        

        
    }
    
}
