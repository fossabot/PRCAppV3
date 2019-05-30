<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class apiv1 extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("brand_model", "mainmodel", TRUE);
    }

    public function index() {

    }
    
    
    public function addUSUser() {
        $time1 = new DateTime();
        
        $this->load->model("human_resource_model", "hmmodel", TRUE);
        $this->load->model("human_resource_title_model", "typemodel", TRUE);
        
        $request_body = file_get_contents('php://input');
        $data = json_decode($request_body, true);
        $ret = array('status' => 'OK');
        $totalins = 0;
        $totalupd = 0;
        
        $recordsetHM = array();
        $recordsetTitle = array();
        
        

        foreach ($data as $key => $value) {
                        
            $title = 'US - ' .  $value['JobTitle'];
            $rowTitle = $this->typemodel->retRetrieveArray(" WHERE ds_human_resource_title = '$title'");
            
            
            if (count($rowTitle) == 0)  
                {
                $titcode = $this->typemodel->getNextCode();
                array_push($recordsetTitle, array(
                    'recid' => $titcode, 
                    'ds_human_resource_title' => $title
                ));
            } else {
                $titcode = $rowTitle[0]['recid'];
            }
            
            if (!isset($value['KnownAs']) || $value['KnownAs'] == NULL) {
                $value['KnownAs'] = $value['PersonnelNumber'];
            }
            
            $recHM = ['recid' => -10,
                    'ds_human_resource'      => $value['KnownAs'],
                    'ds_human_resource_full' => $value['FirstName'] . ' ' .  $value['LastName'],
                    'nr_staff_number'        => $value['PersonnelNumber'],
                    'dt_join'                => $value['DateStarted'],
                    'dt_deactivated'         => $value['DateEnded'],
                    'nr_login_mode'          => 3,
                    'ds_password'            => 'imported by API',
                    'nr_staff_number_responsible' => $value['SupervisorNumber'],
                    'cd_human_resource_title' => $titcode,
                    'ds_location'             => $value['Location'],
                    'ds_e_mail'               => $value['EmailAddress'],
                    'ds_info_1'               => $value['ADPDepartment']
            ];
            
            $rowHM = $this->hmmodel->retRetrieveArray(" WHERE nr_staff_number = " . $value['PersonnelNumber'] . '  AND nr_login_mode = 3');
            if (count($rowHM) > 0) {
                $totalupd ++;
                $recHM ['recid'] = $rowHM[0]['recid'];
            } else {
                $totalins ++;
                
            }
            
            array_push($recordsetHM, $recHM);
        }
        
        $this->getCdbhelper()->trans_begin();
        
        $error  = $this->typemodel->updateGridData($recordsetTitle);
        if ($error != 'OK') {
            $this->getCdbhelper()->trans_rollback();
            $msg = '{"status":' . json_encode($error) . ', "rs":{} }';
            echo($msg);
            return;
        }
        
        $error  = $this->hmmodel->updateGridData($recordsetHM);
        if ($error != 'OK') {
            $this->getCdbhelper()->trans_rollback();
            $msg = '{"status":' . json_encode($error) . ', "rs":{} }';
            echo($msg);
            return;
        }

        $this->getCdbhelper()->trans_commit();
        
        $ret['upd'] = $totalupd;
        $ret['ins'] = $totalins;
        
        $time2 = new DateTime();
        $interval = $time1->diff($time2);
        
         $ret['runtime'] = $interval->format('%s seconds');
        
        die (json_encode($ret, JSON_NUMERIC_CHECK));
        
    }
    

}
