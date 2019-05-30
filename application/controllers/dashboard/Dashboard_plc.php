<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class dashboard_plc extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("tr/test_unit_model", "mainmodel", TRUE);
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

        //$grid->setSingleBarControl(true);
        $grid->addCRUDToolbar(FALSE, false, false, false, false);
        $grid->setToolbarSearch(true);
        $grid->setCRUDController("rfq/supplier");

        $grid->addColumnKey();
        $grid->addColumn('nr_status', 'Status', '60px', $f->retTypeColor(), false);
        $grid->addColumn('jigno', 'Station#', '80px', $f->retTypeStringAny(), false);
        $grid->addColumn('controllerno', 'Controller#', '100px', $f->retTypeStringAny(), false);
        $grid->addColumn('projectno', 'Project#', '100px', $f->retTypeStringAny(), false);
        $grid->addColumn('requisitionno', 'TR#', '100px', $f->retTypeStringAny(), false);
        $grid->addColumn('sampleno', 'Sample#', '70px', $f->retTypeStringAny(), false);

        $grid->addColumn('currentstatus', 'Status Description', '100px', $f->retTypeStringAny(), false);
        $grid->addColumn('testresult', 'Result', '100px', $f->retTypeStringAny(), false);
        $grid->addColumn('nr_perc_done', 'Complete', '100px', $f->retTypeStringAny());
        $grid->setColumnRenderFunc('nr_perc_done', 'dsMainObject.gridProgress');


        $data = $this->getLifeBrakesControllerData(true);

        $grid->addRecords(json_encode($data ['grid']));
        $grid->showColumnHeader(true);
        $grid->showFooter(true);
        $grid->showLineNumbers(false);
        $grid->showToolbar(true);
        $javascript = $grid->retGrid();

        $fm = $this->cfiltermaker;

        $trans = array();

        $trans = $this->cdbhelper->retTranslationDifKeys($trans);

        $send = array("javascript" => $javascript,
            "filters" => '',
            'piedata' => $data['piedep'],
            "filters_java" => $fm->retJavascript()) + $trans;


        $this->load->view("dashboard/dashboard_plc_view", $send);
    }

    public function getLifeBrakesControllerData($ret3 = false) {
        $query = "select 
                x.controllerno  as recid,
                x.controllerno , 
                x.jigno , 
                
                x.projectno,
                ( ( CAST(CASE SpecificationUnit
                                 WHEN 'Cycle' THEN CurrentCycle
                                 WHEN 'Hour'
                                 THEN CAST(AlreadyTestTime / 36000.0 AS DECIMAL(9,
                                                              1))
                               END AS FLOAT) / Specification ) * 100 ) as nr_perc_done,
                (CASE 
                      WHEN x.currentstatus = 'Program Running' THEN '00e600' 
                      WHEN x.currentstatus = 'User Pause' THEN 'ffff00'
                      WHEN x.currentstatus = 'Offline'  THEN 'ffffff'
                      WHEN x.currentstatus = 'Program End' THEN '1a75ff' 
                      WHEN x.currentstatus = 'Alarm' THEN 'ff0000' 
                      END) as nr_status ,
                 x.currentstatus,
                 (CASE 
                      WHEN x.testresult = 'Unfinish'   THEN 0 
                      WHEN x.testresult = 'Finish' THEN 1 
                      END) as nr_testresult ,

                x.testresult  ,
                (CASE 
                      WHEN x.currentstatus = 'Program Running' THEN 4
                      WHEN x.currentstatus = 'User Pause' THEN 2
                      WHEN x.currentstatus = 'Offline'  THEN 1
                      WHEN x.currentstatus = 'Program End' THEN 3
                      WHEN x.currentstatus = 'Alarm' THEN 5
                      WHEN x.currentstatus = 'Not Start' THEN 6
                      
                      END) as nr_id_status ,
                      requisitionno,
                      sampleno
                from public.\"COLLECT_BRAKE_LIFE\" x,
                     ( SELECT max(recid) as recid, controllerno  from   public.\"COLLECT_BRAKE_LIFE\" GROUP BY controllerno ) as y
               WHERE x.recid = y.recid
               ORDER BY split_part(x.controllerno, ' ', 2)::integer ";

        $ret = $this->getCdbhelper()->basicSQLArray($query);

        // query pie

        $query = "
            select ( select ds_department from public.\"DEPARTMENT\" a where a.cd_department = p.cd_department ),
                    count(distinct x.projectno || '-'|| x.sampleno ) as nr_count 

            from public.\"COLLECT_BRAKE_LIFE\" x, tti.\"PROJECT\" p 
            WHERE x.recid = (select max(a.recid) from public.\"COLLECT_BRAKE_LIFE\" a WHERE a.controllerno = x.controllerno )
              AND x.currentstatus = 'Program Running'
              AND ( trim (LEADING '0' FROM p.ds_met_project)  = trim (LEADING '0' FROM x.projectno) OR trim (LEADING '0' FROM p.ds_tti_project) = trim (LEADING '0' FROM x.projectno ))
            group by p.cd_department";

        $retPie = $this->getCdbhelper()->basicSQLArray($query);


        $data = array('grid' => $ret, 'piedep' => $retPie);
        if ($ret3) {
            return $data;
        } else {
            echo json_encode($data);
        }
    }

}
