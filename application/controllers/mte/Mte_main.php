<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

include_once APPPATH . "controllers/controllerBasicExtend.php";

class mte_main extends controllerBasicExtend {

    var $arrayIns;
    var $fields;

    function __construct() {
        parent::__construct();
        $this->load->model("tr/mte_mssql_model", "mainmodel", TRUE);
        $this->allGrid = ['failure_data_grid', 'wodaily_data_grid', 'battery_data_grid', 'no_load_data_grid', 'torque_data_grid', 'drillrate_data_grid', 'general_data_grid', 'worker_raw_data_grid'];

    }

    public function index() {

        parent::checkMenuPermission();
    }

    public function openMTEData($wo, $sample) {
        $grid = $this->w2gridgen;
        $f = $this->cfields;
        $fm = $this->cfiltermaker;
        $ctabs = $this->ctabs;

        $sample = $sample > 9 ? $sample : '0' . $sample;

        if (1 == 2) {
            $f = new Cfields();
            $grid = new w2gridgen();
            $fm = new cfiltermaker();
            $ctabs = new ctabs();
        }
        $ctabs->addTab('Failure', 'tab_failure_data');
        $ctabs->addTab('Daily Output', 'tab_wodaily_data');
        $ctabs->addTab('Battery', 'tab_battery_data');
        $ctabs->addTab('No Load', 'tab_no_load_data');
        $ctabs->addTab('Torque', 'tab_torque_data');
        $ctabs->addTab('Drill Rate', 'tab_drillrate_data');
        $ctabs->addTab('General Form', 'tab_general_data');
        $ctabs->addTab('Worker Raw', 'tab_worker_raw_data');

        $ctabs->setMainDivId('tabMTEData');
        $ctabs->setContentDivId('tab_failure_data_div');
        $ctabs = $ctabs->retTabs();

        /////////////////////////////////New grid  for workerrawdataform
        $firstTab = 'tab_failure_data';
        $allGrid = json_encode($this->allGrid);
        $grid->resetGrid();
        $grid->setSingleBarControl(true);

        $grid->addSpacerToolbar();
        $grid->addExportToolbar();
        $grid->setGridToolbarFunction('dsMainRawData.ToolBarClick');

        $grid->setToolbarSearch(true);
        $grid->setGridName('worker_raw_data_grid');
        $grid->setGridDivName('tab_worker_raw_data_div');

        $grid->addColumnKey();
        $grid->setRowHeight(36);

        $grid->addColumn('WO_code', 'Work Order', '120px', $f->retTypeStringAny());
        $grid->addColumn('Tool_code', 'Sample#', '80px', $f->retTypeStringAny());
        $grid->addColumn('Battery/Accessory', 'Battery/Accy#', '110px', $f->retTypeStringAny());
        // Data Columns
        $grid->addColumn('Item_code', 'Item/Step', '80px', $f->retTypeStringAny());
        $grid->addColumn('application', 'Applications', '100px', $f->retTypeNum(), array('precision' => '1', 'readonly' => true));
        $grid->addColumn('Time', 'Time', '80px', $f->retTypeStringAny());
        $grid->addColumn('Ambient', 'Ambient', '80px', $f->retTypeStringAny());
        $grid->addColumn('Comment', 'Remark', '120px', $f->retTypeStringAny());
        $grid->addColumn('Computer_Name', 'ComputerID', '100px', $f->retTypeStringAny());
        $grid->addColumn('Creation_date', 'Creation Date', '140px', $f->retTypeStringAny());
        $grid->addColumn('Operator', 'Operator', '80px', $f->retTypeStringAny());
        $grid->addColumn('Start_time', 'Start Time', '120px', $f->retTypeStringAny());
        $grid->addColumn('End_time', 'End Time', '120px', $f->retTypeStringAny());
        $grid->addColumn('IV_images', 'Images', '190px', $f->retTypeHTML());
        $grid->addColumn('IV_videos', 'Videos', '100px', $f->retTypeHTML());
        $grid->setColumnRenderFunc('IV_images', 'dsMainRawData.rawImageList');
        $grid->setColumnRenderFunc('IV_videos', 'dsMainRawData.rawVideoList');
        // Common Feilds
        $grid->addColumn('Goal', 'Goal', '80px', $f->retTypeStringAny());
        $grid->addColumn('Unit', 'Goal Unit', '80px', $f->retTypeStringAny());
        $grid->addColumn('EOL', 'EOL', '50px', $f->retTypeStringAny());
        $grid->addColumn('Completed_Cycle', 'Comp. Cycles', '100px', $f->retTypeInteger());
        $grid->addColumn('Completed_Application', 'Comp. Applications', '130px', $f->retTypeNum(), array('precision' => '1', 'readonly' => true));
        $grid->addColumn('Completed_Discharge', 'Comp. Discharges', '125px', $f->retTypeStringAny());
        $grid->addColumn('Completed_Time', 'Comp. Runtime', '115px', $f->retTypeStringAny());
        //$grid->addColumn('Battery_Discharge', 'Batt#/Accy# Discharge', '100px', $f->retTypeStringAny());
        //$grid->addColumn('Creation_date', 'Creation Date', '120px', $f->retTypeStringAny());
        $grid->addColumn('TTI_Project_no', 'TTI Project#', '100px', $f->retTypeStringAny());
        $grid->addColumn('TR_MET_Project_No', 'Mil Project#', '100px', $f->retTypeStringAny());
        $grid->addColumn('TRBrandProjectDesc', 'Project Description', '150px', $f->retTypeStringAny());
        $grid->addColumn('Customer_Model_no', 'Mil Model#', '100px', $f->retTypeStringAny());
        $grid->addColumn('TR_no', 'TR#', '120px', $f->retTypeStringAny());
        $grid->addColumn('Type of Test', 'Type of Test', '120px', $f->retTypeStringAny());
        //  $grid->addColumn('TE', 'TE', '80px', $f->retTypeStringAny());
        $grid->addColumn('TR_Apply_by', 'Requester', '80px', $f->retTypeStringAny());
        $grid->addColumn('TR_Apply_date', 'Request Date', '100px', $f->retTypeStringAny());
        $grid->addColumn('Type of Test', 'Type of Test', '120px', $f->retTypeStringAny());
        $grid->addColumn('TestPhase_name', 'Test Phase', '100px', $f->retTypeStringAny());
        $grid->addColumn('TestType_name', 'Test Type', '150px', $f->retTypeStringAny());
        // recid column must exists on your query (MTE), and must be an unique
        $data = $this->mainmodel->getWorkerRawDataFromMTE("AND WO_code = '$wo' AND Tool_code = '$sample'");
        $grid->addRecords(json_encode($data, JSON_NUMERIC_CHECK));
        $javascript = $grid->retGrid();

///////////////////////////////////New grid  for batterydatagrid
        $grid->resetGrid();
        $grid->setSingleBarControl(true);

        $grid->addSpacerToolbar();
        $grid->addExportToolbar();
        $grid->setGridToolbarFunction('dsMainRawData.ToolBarClick');

        $grid->setToolbarSearch(true);
        $grid->setGridName('battery_data_grid');
        $grid->setGridDivName('tab_battery_data_div');

        $grid->addColumnKey();

        $grid->addColumn('WO_code', 'Work Order', '120px', $f->retTypeStringAny());
        $grid->addColumn('Tool_code', 'Sample#', '80px', $f->retTypeStringAny());
        $grid->addColumn('Battery/Accessory', 'Battery/Accy#', '110px', $f->retTypeStringAny());

        // Data Columns
        $grid->addColumn('Voltage(V)', 'Voltage(V)', '100px', $f->retTypeStringAny());
        $grid->addColumn('Impedance', 'Impedance', '100px', $f->retTypeStringAny());
        $grid->addColumn('Capacity', 'Capacity', '100px', $f->retTypeStringAny());
        $grid->addColumn('Remark', 'Remark', '120px', $f->retTypeStringAny());
        $grid->addColumn('Recorder', 'Recorder', '100px', $f->retTypeStringAny());
        $grid->addColumn('Creation_date', 'Creation Date', '140px', $f->retTypeStringAny());
        $grid->addColumn('Reminder_Descripton', 'Reminder Descripton', '150px', $f->retTypeFloat());
        $grid->addColumn('Test_Before_After_Cycle', 'Reminder Trigger', '120px', $f->retTypeStringAny());


        // Common Feilds
        // $grid->addColumn('Goal', 'Goal', '80px', $f->retTypeStringAny());
        // $grid->addColumn('Unit', 'Goal Unit', '80px', $f->retTypeStringAny());
        // $grid->addColumn('EOL', 'EOL', '50px', $f->retTypeStringAny());
        $grid->addColumn('Completed_Cycle', 'Comp. Cycles', '100px', $f->retTypeInteger());
        $grid->addColumn('Completed_Application', 'Comp. Applications', '130px', $f->retTypeNum(), array('precision' => '1', 'readonly' => true));
        $grid->addColumn('Completed_Discharge', 'Comp. Discharges', '125px', $f->retTypeStringAny());
        $grid->addColumn('Completed_Time', 'Comp. Runtime', '115px', $f->retTypeStringAny());
        //$grid->addColumn('Battery_Discharge', 'Batt#/Accy# Discharge', '100px', $f->retTypeStringAny());
        //$grid->addColumn('Creation_date', 'Creation Date', '120px', $f->retTypeStringAny());
        $grid->addColumn('TTI_Project_no', 'TTI Project#', '100px', $f->retTypeStringAny());
        $grid->addColumn('TR_MET_Project_No', 'Mil Project#', '100px', $f->retTypeStringAny());
        $grid->addColumn('TRBrandProjectDesc', 'Project Description', '150px', $f->retTypeStringAny());
        $grid->addColumn('Customer_Model_no', 'Mil Model#', '100px', $f->retTypeStringAny());
        $grid->addColumn('TR_no', 'TR#', '120px', $f->retTypeStringAny());
        // $grid->addColumn('Type of Test', 'Type of Test', '120px', $f->retTypeStringAny());
        //  $grid->addColumn('TE', 'TE', '80px', $f->retTypeStringAny());
        //$grid->addColumn('TR_Apply_by', 'Requester', '80px', $f->retTypeStringAny());
        //$grid->addColumn('TR_Apply_date', 'Request Date', '100px', $f->retTypeStringAny());
        //$grid->addColumn('Type of Test', 'Type of Test', '120px', $f->retTypeStringAny());
        // $grid->addColumn('TestPhase_name', 'Test Phase', '100px', $f->retTypeStringAny());
        // $grid->addColumn('TestType_name', 'Test Type', '150px', $f->retTypeStringAny());

        $data = $this->mainmodel->getBatteryDataFromMTE("AND infor.WO_code = '$wo' AND tool.Tool_code = '$sample'");

        $grid->addRecords(json_encode($data, JSON_NUMERIC_CHECK));

        //$grid->addRecords( json_encode( $this->mainmodel->retRawData(" AND WO = '$wo' AND Sample = '$sample'") , JSON_NUMERIC_CHECK)  );
        $javascript .= $grid->retGrid();

/////////////////////////////////New grid  for noloaddata

        $grid->resetGrid();
        $grid->setSingleBarControl(true);

        $grid->addSpacerToolbar();
        $grid->addExportToolbar();
        $grid->setGridToolbarFunction('dsMainRawData.ToolBarClick');

        $grid->setToolbarSearch(true);
        $grid->setGridName('no_load_data_grid');
        $grid->setGridDivName('tab_no_load_data_div');

        $grid->addColumnKey();
        // Common Feilds

        $grid->addColumn('WO_code', 'Work Order', '120px', $f->retTypeStringAny());
        $grid->addColumn('Tool_code', 'Sample#', '80px', $f->retTypeStringAny());
        $grid->addColumn('Battery/Accessory', 'Battery/Accy#', '110px', $f->retTypeStringAny());

        // Data Columns
        $grid->addColumn('Forward_Reverse', 'Forward_Reverse', '100px', $f->retTypeStringAny());
        $grid->addColumn('High_Low_Speed', 'High_Low_Speed', '80px', $f->retTypeStringAny());
        $grid->addColumn('No_load_current', 'Forward_Reverse', '100px', $f->retTypeStringAny());
        $grid->addColumn('No_Load_Speed', 'No_Load_Speed', '80px', $f->retTypeStringAny());
        $grid->addColumn('Remark', 'Remark', '120px', $f->retTypeStringAny());
        $grid->addColumn('Recorder', 'Recorder', '100px', $f->retTypeStringAny());
        $grid->addColumn('Creation_date', 'Creation Date', '140px', $f->retTypeStringAny());
        $grid->addColumn('Reminder_Descripton', 'Reminder Descripton', '120px', $f->retTypeStringAny());
        $grid->addColumn('Test_Before_After_Cycle', 'Reminder Trigger', '50px', $f->retTypeStringAny());

        // Common Feilds
        // $grid->addColumn('Goal', 'Goal', '80px', $f->retTypeStringAny());
        //$grid->addColumn('Unit', 'Goal Unit', '80px', $f->retTypeStringAny());
        //$grid->addColumn('EOL', 'EOL', '50px', $f->retTypeStringAny());
        $grid->addColumn('Completed_Cycle', 'Comp. Cycles', '100px', $f->retTypeInteger());
        $grid->addColumn('Completed_Application', 'Comp. Applications', '130px', $f->retTypeNum(), array('precision' => '1', 'readonly' => true));
        $grid->addColumn('Completed_Discharge', 'Comp. Discharges', '125px', $f->retTypeStringAny());
        $grid->addColumn('Completed_Time', 'Comp. Runtime', '115px', $f->retTypeStringAny());
        //$grid->addColumn('Battery_Discharge', 'Batt#/Accy# Discharge', '100px', $f->retTypeStringAny());
        //$grid->addColumn('Creation_date', 'Creation Date', '120px', $f->retTypeStringAny());
        $grid->addColumn('TTI_Project_no', 'TTI Project#', '100px', $f->retTypeStringAny());
        $grid->addColumn('TR_MET_Project_No', 'Mil Project#', '100px', $f->retTypeStringAny());
        $grid->addColumn('TRBrandProjectDesc', 'Project Description', '150px', $f->retTypeStringAny());
        $grid->addColumn('Customer_Model_no', 'Mil Model#', '100px', $f->retTypeStringAny());
        $grid->addColumn('TR_no', 'TR#', '120px', $f->retTypeStringAny());
        //$grid->addColumn('Type of Test', 'Type of Test', '120px', $f->retTypeStringAny());
        //  $grid->addColumn('TE', 'TE', '80px', $f->retTypeStringAny());
        //$grid->addColumn('TR_Apply_by', 'Requester', '80px', $f->retTypeStringAny());
        //$grid->addColumn('TR_Apply_date', 'Request Date', '100px', $f->retTypeStringAny());
        //$grid->addColumn('Type of Test', 'Type of Test', '120px', $f->retTypeStringAny());
        // $grid->addColumn('TestPhase_name', 'Test Phase', '100px', $f->retTypeStringAny());
        //$grid->addColumn('TestType_name', 'Test Type', '150px', $f->retTypeStringAny());

        $data = $this->mainmodel->getNoLoadDataFromMTE("AND infor.WO_code = '$wo' AND tool.Tool_code = '$sample'");

        $grid->addRecords(json_encode($data, JSON_NUMERIC_CHECK));

        $javascript = $javascript . $grid->retGrid();


/////////////////////////////////New grid  for torquedata
        $grid->resetGrid();
        $grid->setSingleBarControl(true);

        $grid->addSpacerToolbar();
        $grid->addExportToolbar();
        $grid->setGridToolbarFunction('dsMainRawData.ToolBarClick');

        $grid->setToolbarSearch(true);
        $grid->setGridName('torque_data_grid');
        $grid->setGridDivName('tab_torque_data_div');

        $grid->addColumnKey();
        // Common Feilds
        $grid->addColumn('WO_code', 'Work Order', '120px', $f->retTypeStringAny());
        $grid->addColumn('Tool_code', 'Sample#', '80px', $f->retTypeStringAny());
        $grid->addColumn('Battery/Accessory', 'Battery/Accy#', '110px', $f->retTypeStringAny());

        // Data Columns
        $grid->addColumn('Speed', 'Speed', '80px', $f->retTypeStringAny());
        $grid->addColumn('Forward/Reverse', 'Forward/Reverse', '80px', $f->retTypeStringAny());
        $grid->addColumn('Torque Value1', 'Torque Value1', '100px', $f->retTypeStringAny());
        $grid->addColumn('Torque Value2', 'Torque Value2', '100px', $f->retTypeStringAny());
        $grid->addColumn('Torque Value3', 'Torque Value3', '100px', $f->retTypeStringAny());
        $grid->addColumn('Torque Value4', 'Torque Value4', '100px', $f->retTypeStringAny());
        $grid->addColumn('Torque Value5', 'Torque Value5', '100px', $f->retTypeStringAny());
        $grid->addColumn('Remark', 'Remark', '120px', $f->retTypeStringAny());
        $grid->addColumn('Recorder', 'Recorder', '100px', $f->retTypeStringAny());
        $grid->addColumn('Creation_date', 'Creation Date', '140px', $f->retTypeStringAny());
        $grid->addColumn('Test_Before_After_Cycle', 'Reminder Trigger', '80px', $f->retTypeStringAny());
        $grid->addColumn('Reminder_Descripton', 'Reminder Descripton', '120px', $f->retTypeStringAny());


        // Common Feilds
        // $grid->addColumn('Goal', 'Goal', '80px', $f->retTypeStringAny());
        // $grid->addColumn('Unit', 'Goal Unit', '80px', $f->retTypeStringAny());
        // $grid->addColumn('EOL', 'EOL', '50px', $f->retTypeStringAny());
        $grid->addColumn('Completed_Cycle', 'Comp. Cycles', '100px', $f->retTypeInteger());
        $grid->addColumn('Completed_Application', 'Comp. Applications', '130px', $f->retTypeNum(), array('precision' => '1', 'readonly' => true));
        $grid->addColumn('Completed_Discharge', 'Comp. Discharges', '125px', $f->retTypeStringAny());
        $grid->addColumn('Completed_Time', 'Comp. Runtime', '115px', $f->retTypeStringAny());
        //$grid->addColumn('Battery_Discharge', 'Batt#/Accy# Discharge', '100px', $f->retTypeStringAny());
        //$grid->addColumn('Creation_date', 'Creation Date', '120px', $f->retTypeStringAny());
        $grid->addColumn('TTI_Project_no', 'TTI Project#', '100px', $f->retTypeStringAny());
        $grid->addColumn('TR_MET_Project_No', 'Mil Project#', '100px', $f->retTypeStringAny());
        $grid->addColumn('TRBrandProjectDesc', 'Project Description', '150px', $f->retTypeStringAny());
        $grid->addColumn('Customer_Model_no', 'Mil Model#', '100px', $f->retTypeStringAny());
        $grid->addColumn('TR_no', 'TR#', '120px', $f->retTypeStringAny());
        //  $grid->addColumn('TE', 'TE', '80px', $f->retTypeStringAny());
        //$grid->addColumn('TR_Apply_by', 'Requester', '80px', $f->retTypeStringAny());
        // $grid->addColumn('TR_Apply_date', 'Request Date', '100px', $f->retTypeStringAny());
        // $grid->addColumn('Type of Test', 'Type of Test', '120px', $f->retTypeStringAny());
        // $grid->addColumn('TestPhase_name', 'Test Phase', '100px', $f->retTypeStringAny());
        //$grid->addColumn('TestType_name', 'Test Type', '150px', $f->retTypeStringAny());

        $data = $this->mainmodel->getTorqueDataFromMTE("AND WO_code = '$wo' AND Tool_code = '$sample'");

        $grid->addRecords(json_encode($data, JSON_NUMERIC_CHECK));

        $javascript = $javascript . $grid->retGrid();

/////////////////////////////////New grid  for generaldataform
        $grid->resetGrid();
        $grid->setSingleBarControl(true);

        $grid->addSpacerToolbar();
        $grid->addExportToolbar();
        $grid->setGridToolbarFunction('dsMainRawData.ToolBarClick');

        $grid->setToolbarSearch(true);
        $grid->setGridName('general_data_grid');
        $grid->setGridDivName('tab_general_data_div');

        $grid->addColumnKey();
        // Common Feilds

        $grid->addColumn('WO_code', 'Work Order', '120px', $f->retTypeStringAny());
        $grid->addColumn('Tool_code', 'Sample#', '80px', $f->retTypeStringAny());
        $grid->addColumn('Battery/Accessory', 'Battery/Accy#', '110px', $f->retTypeStringAny());

        //$grid->addColumn('Reminder_Descripton', 'Reminder Descripton', '120px', $f->retTypeFloat() );
        //$grid->addColumn('Test_Before_After_Cycle', 'Test_Before_After_Cycle', '50px', $f->retTypeStringAny());
        // Data Columns
        $grid->addColumn('Description', 'Measure Description', '150px', $f->retTypeStringAny());
        $grid->addColumn('Unit', 'Unit', '80px', $f->retTypeStringAny());
        $grid->addColumn('Value 1', 'Value 1', '100px', $f->retTypeStringAny());
        $grid->addColumn('Value 2', 'Value 2', '100px', $f->retTypeStringAny());
        $grid->addColumn('Value 3', 'Value 3', '100px', $f->retTypeStringAny());
        $grid->addColumn('Value 4', 'Value 4', '100px', $f->retTypeStringAny());
        $grid->addColumn('Value 5', 'Value 5', '100px', $f->retTypeStringAny());
        $grid->addColumn('Value 6', 'Value 6', '100px', $f->retTypeStringAny());
        $grid->addColumn('Value 7', 'Value 7', '100px', $f->retTypeStringAny());
        $grid->addColumn('Value 8', 'Value 8', '100px', $f->retTypeStringAny());
        $grid->addColumn('Value 9', 'Value 9', '100px', $f->retTypeStringAny());
        $grid->addColumn('Value 10', 'Value 10', '100px', $f->retTypeStringAny());
        $grid->addColumn('Remark', 'Remark', '150px', $f->retTypeStringAny());
        $grid->addColumn('Recorder', 'Recorder', '100px', $f->retTypeStringAny());
        $grid->addColumn('Creation_date', 'Creation Date', '100px', $f->retTypeStringAny());

        // Common Feilds
       // $grid->addColumn('Goal', 'Goal', '80px', $f->retTypeStringAny());
       // $grid->addColumn('Unit', 'Goal Unit', '80px', $f->retTypeStringAny());
       // $grid->addColumn('EOL', 'EOL', '50px', $f->retTypeStringAny());
        $grid->addColumn('Completed_Cycle', 'Comp. Cycles', '100px', $f->retTypeInteger());
        $grid->addColumn('Completed_Application', 'Comp. Applications', '130px', $f->retTypeNum(), array('precision' => '1', 'readonly' => true));
        $grid->addColumn('Completed_Discharge', 'Comp. Discharges', '125px', $f->retTypeStringAny());
        $grid->addColumn('Completed_Time', 'Comp. Runtime', '115px', $f->retTypeStringAny());
        $grid->addColumn('TTI_Project_no', 'TTI Project#', '100px', $f->retTypeStringAny());
        $grid->addColumn('TR_MET_Project_No', 'Mil Project#', '100px', $f->retTypeStringAny());
        $grid->addColumn('TRBrandProjectDesc', 'Project Description', '150px', $f->retTypeStringAny());
        $grid->addColumn('Customer_Model_no', 'Mil Model#', '100px', $f->retTypeStringAny());
        $grid->addColumn('TR_no', 'TR#', '120px', $f->retTypeStringAny());


        $data = $this->mainmodel->getGeneralFormDataFromMTE("AND WO_code = '$wo' AND Tool_code = '$sample'");

        $grid->addRecords(json_encode($data, JSON_NUMERIC_CHECK));

        $javascript = $javascript . $grid->retGrid();


/////////////////////////////////New grid  for failuredataform
        $grid->resetGrid();
        $grid->setSingleBarControl(true);
        $grid->setRowHeight(36);
        $grid->addSpacerToolbar();
        $grid->addExportToolbar();
        $grid->setGridToolbarFunction('dsMainRawData.ToolBarClick');

        $grid->setToolbarSearch(true);
        $grid->setGridName('failure_data_grid');
        $grid->setGridDivName('tab_failure_data_div');

        $grid->addColumnKey();

        $grid->addColumn('WO_code', 'Work Order', '120px', $f->retTypeStringAny());
        $grid->addColumn('Tool_code', 'Sample#', '80px', $f->retTypeStringAny());
        $grid->addColumn('Battery/Accessory', 'Battery/Accy#', '110px', $f->retTypeStringAny());


        // Data Columns
        $grid->addColumn('Failure_No', 'Report ID', '80px', $f->retTypeStringAny());
        $grid->addColumn('Failure_date', 'Failure Date', '130px', $f->retTypeStringAny());
        $grid->addColumn('Failure_description', 'Failure Description', '200px', $f->retTypeStringAny());
        $grid->addColumn('Before_Failure_decription', 'Observations before Failure', '150px', $f->retTypeStringAny());
        $grid->addColumn('IV_images', 'Images', '190px', $f->retTypeHTML());
        $grid->addColumn('IV_videos', 'Videos', '100px', $f->retTypeHTML());
        $grid->addColumn('Item_code', 'Item/Step', '80px', $f->retTypeStringAny());
        $grid->addColumn('Failure_Varification', 'Failure Verification', '120px', $f->retTypeStringAny());
        $grid->addColumn('Speed', 'Speed', '100px', $f->retTypeStringAny());
        $grid->addColumn('Battery_Charge_Level', 'Charge Level', '100px', $f->retTypeStringAny());
        $grid->addColumn('Battery_Rate_Voltage', 'Batt. Rated Voltage', '100px', $f->retTypeStringAny());
        $grid->addColumn('Battery_Rated_Capacity_name', 'Batt. Rated Capacity', '100px', $f->retTypeStringAny());
        $grid->addColumn('Description', 'Gear Setting', '100px', $f->retTypeStringAny());
        $grid->addColumn('Forward_Reverse', 'Forward/Reverse', '100px', $f->retTypeStringAny());
        $grid->addColumn('Trigger_status', 'Partial/Full Trigger', '100px', $f->retTypeStringAny());
        $grid->addColumn('Remark', 'Remark', '120px', $f->retTypeStringAny());
        $grid->addColumn('TestItemDescription', 'TestItemDescription', '120px', $f->retTypeStringAny());
        $grid->addColumn('Operator', 'Operator', '100px', $f->retTypeStringAny());
        $grid->addColumn('Room', 'Room', '100px', $f->retTypeStringAny());
        $grid->addColumn('Room leader', 'Room leader', '80px', $f->retTypeStringAny());
        $grid->addColumn('Computer_Name', 'Computer Name', '100px', $f->retTypeStringAny());
        $grid->addColumn('Failure_Status_Name', 'Report Status', '100px', $f->retTypeStringAny());

        // Common Feilds
        $grid->addColumn('Goal', 'Goal', '80px', $f->retTypeStringAny());
        $grid->addColumn('Unit', 'Goal Unit', '80px', $f->retTypeStringAny());
        $grid->addColumn('EOL', 'EOL', '50px', $f->retTypeStringAny());
        $grid->addColumn('Completed_Cycle', 'Comp. Cycles', '100px', $f->retTypeInteger());
        $grid->addColumn('Completed_Application', 'Comp. Applications', '130px', $f->retTypeNum(), array('precision' => '1', 'readonly' => true));
        $grid->addColumn('Completed_Discharge', 'Comp. Discharges', '125px', $f->retTypeStringAny());
        $grid->addColumn('Completed_Time', 'Comp. Runtime', '115px', $f->retTypeStringAny());
        //$grid->addColumn('Battery_Discharge', 'Batt#/Accy# Discharge', '100px', $f->retTypeStringAny());
        //$grid->addColumn('Creation_date', 'Creation Date', '120px', $f->retTypeStringAny());
        $grid->addColumn('TTI_Project_no', 'TTI Project#', '100px', $f->retTypeStringAny());
        $grid->addColumn('TR_MET_Project_No', 'Mil Project#', '100px', $f->retTypeStringAny());
        $grid->addColumn('TRBrandProjectDesc', 'Project Description', '150px', $f->retTypeStringAny());
        $grid->addColumn('Customer_Model_no', 'Mil Model#', '100px', $f->retTypeStringAny());
        $grid->addColumn('TR_no', 'TR#', '120px', $f->retTypeStringAny());
        $grid->addColumn('Type of Test', 'Type of Test', '120px', $f->retTypeStringAny());
        //  $grid->addColumn('TE', 'TE', '80px', $f->retTypeStringAny());
        $grid->addColumn('TR_Apply_by', 'Requester', '80px', $f->retTypeStringAny());
        $grid->addColumn('TR_Apply_date', 'Request Date', '100px', $f->retTypeStringAny());
        $grid->addColumn('Type of Test', 'Type of Test', '120px', $f->retTypeStringAny());
        $grid->addColumn('TestPhase_name', 'Test Phase', '100px', $f->retTypeStringAny());
        $grid->addColumn('TestType_name', 'Test Type', '150px', $f->retTypeStringAny());

        $grid->setColumnRenderFunc('IV_images', 'dsMainRawData.rawImageList');
        $grid->setColumnRenderFunc('IV_videos', 'dsMainRawData.rawVideoList');

        $data = $this->mainmodel->getFailureDataFromMTE("AND WO_code = '$wo' AND Tool_code = '$sample'");


        $grid->addRecords(json_encode($data, JSON_NUMERIC_CHECK));

        $javascript = $javascript . $grid->retGrid();

        /////////////////////////////////New grid  for wodailysum
        $grid->resetGrid();
        $grid->setSingleBarControl(true);

        $grid->addSpacerToolbar();
        $grid->addExportToolbar();
        $grid->setGridToolbarFunction('dsMainRawData.ToolBarClick');

        $grid->setToolbarSearch(true);
        $grid->setGridName('wodaily_data_grid');
        $grid->setGridDivName('tab_wodaily_data_div');

        $grid->addColumnKey();

        $grid->addColumn('WO_code', 'Work Order', '120px', $f->retTypeStringAny());
        $grid->addColumn('Tool_code', 'Sample#', '80px', $f->retTypeStringAny());
      //  $grid->addColumn('Battery/Accessory', 'Battery/Accy#', '110px', $f->retTypeStringAny());

        // Data Columns
        $grid->addColumn('application', 'Daily Applications', '125px', $f->retTypeNum(), array('precision' => '1', 'readonly' => true));
        $grid->addColumn('runtime', 'Daily Runtime', '100px', $f->retTypeStringAny());
        $grid->addColumn('cycles', 'Daily Cycles', '90px', $f->retTypeInteger());
        $grid->addColumn('discharges', 'Daily Discharges', '120px', $f->retTypeStringAny());
        $grid->addColumn('failure_counts', 'Daily failures', '110px', $f->retTypeStringAny());
        $grid->addColumn('Creation_date', 'Date', '80px', $f->retTypeStringAny());
        $grid->addColumn('Room_Code', 'Test Room', '150px', $f->retTypeStringAny());


        // Common Feilds
        // $grid->addColumn('Goal', 'Goal', '80px', $f->retTypeStringAny());
        //$grid->addColumn('Unit', 'Goal Unit', '80px', $f->retTypeStringAny());
        //$grid->addColumn('EOL', 'EOL', '50px', $f->retTypeStringAny());
        $grid->addColumn('Completed_Cycle', 'Comp. Cycles', '100px', $f->retTypeInteger());
        $grid->addColumn('Completed_Application', 'Comp. Applications', '130px', $f->retTypeNum(), array('precision' => '1', 'readonly' => true));
        $grid->addColumn('Completed_Discharge', 'Comp. Discharges', '125px', $f->retTypeStringAny());
        $grid->addColumn('Completed_Time', 'Comp. Runtime', '115px', $f->retTypeStringAny());
        /*$grid->addColumn('Battery_Discharge', 'Batt#/Accy# Discharge', '100px', $f->retTypeStringAny());
        //$grid->addColumn('Creation_date', 'Creation Date', '120px', $f->retTypeStringAny());
        $grid->addColumn('TTI_Project_no', 'TTI Project#', '100px', $f->retTypeStringAny());
        $grid->addColumn('TR_MET_Project_No', 'Mil Project#', '100px', $f->retTypeStringAny());
        $grid->addColumn('TRBrandProjectDesc', 'Project Description', '150px', $f->retTypeStringAny());
        $grid->addColumn('Customer_Model_no', 'Mil Model#', '100px', $f->retTypeStringAny());
        $grid->addColumn('TR_no', 'TR#', '120px', $f->retTypeStringAny());
        $grid->addColumn('Type of Test', 'Type of Test', '120px', $f->retTypeStringAny());
       //  $grid->addColumn('TE', 'TE', '80px', $f->retTypeStringAny());
        $grid->addColumn('TR_Apply_by', 'Requester', '80px', $f->retTypeStringAny());
         $grid->addColumn('TR_Apply_date', 'Request Date', '100px', $f->retTypeStringAny());
        $grid->addColumn('Type of Test', 'Type of Test', '120px', $f->retTypeStringAny());
        $grid->addColumn('TestPhase_name', 'Test Phase', '100px', $f->retTypeStringAny());
        $grid->addColumn('TestType_name', 'Test Type', '150px', $f->retTypeStringAny());*/

        $data = $this->mainmodel->getWODailySumFromMTE("AND WO_code = '$wo' AND Tool_code = '$sample'");

        $grid->addRecords(json_encode($data, JSON_NUMERIC_CHECK));

        $javascript = $javascript . $grid->retGrid();

        //$grid->setCRUDController("mtr/mtr_reports");
/////////////////////////////////New grid  for  drillratedata
        $grid->resetGrid();
        $grid->setSingleBarControl(true);

        $grid->addSpacerToolbar();
        $grid->addExportToolbar();
        $grid->setGridToolbarFunction('dsMainRawData.ToolBarClick');

        $grid->setToolbarSearch(true);
        $grid->setGridName('drillrate_data_grid');
        $grid->setGridDivName('tab_drillrate_data_div');

        $grid->addColumnKey();

        $grid->addColumn('WO_code', 'Work Order', '120px', $f->retTypeStringAny());
        $grid->addColumn('Tool_code', 'Sample#', '80px', $f->retTypeStringAny());
        $grid->addColumn('Battery/Accessory', 'Battery/Accy#', '110px', $f->retTypeStringAny());

        // Data Columns
        $grid->addColumn('Drill_Rate_Type', 'Drill Rate Type', '80px', $f->retTypeStringAny());
        $grid->addColumn('Drill', 'Drill Bit', '100px', $f->retTypeDate());
        $grid->addColumn('Unit', 'Unit(UOM)', '150px', $f->retTypeStringAny());
        $grid->addColumn('Hole1', 'Hole1', '80px', $f->retTypeStringAny());
        $grid->addColumn('Hole2', 'Hole2', '80px', $f->retTypeStringAny());
        $grid->addColumn('Hole3', 'Hole3', '80px', $f->retTypeStringAny());
        $grid->addColumn('Hole4', 'Hole4', '80px', $f->retTypeStringAny());
        $grid->addColumn('Hole5', 'Hole5', '80px', $f->retTypeStringAny());
        $grid->addColumn('Hole6', 'Hole6', '80px', $f->retTypeStringAny());
        $grid->addColumn('Hole7', 'Hole7', '80px', $f->retTypeStringAny());
        $grid->addColumn('Hole8', 'Hole8', '80px', $f->retTypeStringAny());
        $grid->addColumn('Hole9', 'Hole9', '80px', $f->retTypeStringAny());
        $grid->addColumn('Hole10', 'Hole10', '80px', $f->retTypeStringAny());
        $grid->addColumn('F_RealName', 'Operator', '80px', $f->retTypeStringAny());
        $grid->addColumn('Room_Code', 'Test Room', '100px', $f->retTypeStringAny());
        $grid->addColumn('Creation_date', 'Creation Date', '140px', $f->retTypeStringAny());

        // Common Feilds
        $grid->addColumn('Goal', 'Goal', '80px', $f->retTypeStringAny());
        $grid->addColumn('Unit', 'Goal Unit', '80px', $f->retTypeStringAny());
        $grid->addColumn('EOL', 'EOL', '50px', $f->retTypeStringAny());
        $grid->addColumn('Completed_Cycle', 'Comp. Cycles', '100px', $f->retTypeInteger());
        $grid->addColumn('Completed_Application', 'Comp. Applications', '130px', $f->retTypeNum(), array('precision' => '1', 'readonly' => true));
        $grid->addColumn('Completed_Discharge', 'Comp. Discharges', '125px', $f->retTypeStringAny());
        $grid->addColumn('Completed_Time', 'Comp. Runtime', '115px', $f->retTypeStringAny());
        //$grid->addColumn('Battery_Discharge', 'Batt#/Accy# Discharge', '100px', $f->retTypeStringAny());
        //$grid->addColumn('Creation_date', 'Creation Date', '120px', $f->retTypeStringAny());
        $grid->addColumn('TTI_Project_no', 'TTI Project#', '100px', $f->retTypeStringAny());
        $grid->addColumn('TR_MET_Project_No', 'Mil Project#', '100px', $f->retTypeStringAny());
        $grid->addColumn('TRBrandProjectDesc', 'Project Description', '150px', $f->retTypeStringAny());
        $grid->addColumn('Customer_Model_no', 'Mil Model#', '100px', $f->retTypeStringAny());
        $grid->addColumn('TR_no', 'TR#', '120px', $f->retTypeStringAny());
        $grid->addColumn('Type of Test', 'Type of Test', '120px', $f->retTypeStringAny());
        //  $grid->addColumn('TE', 'TE', '80px', $f->retTypeStringAny());
        $grid->addColumn('TR_Apply_by', 'Requester', '80px', $f->retTypeStringAny());
        $grid->addColumn('TR_Apply_date', 'Request Date', '100px', $f->retTypeStringAny());
        $grid->addColumn('Type of Test', 'Type of Test', '120px', $f->retTypeStringAny());
        $grid->addColumn('TestPhase_name', 'Test Phase', '100px', $f->retTypeStringAny());
        $grid->addColumn('TestType_name', 'Test Type', '150px', $f->retTypeStringAny());

        $data = $this->mainmodel->getDrillRateFromMTE("AND WO_code = '$wo' AND Tool_code = '$sample'");

        $grid->addRecords(json_encode($data, JSON_NUMERIC_CHECK));

        $javascript = $javascript . $grid->retGrid();

        $trans = array(
            'searchbylable' => 'Search By',
            'fromlabel' => 'From',
            'tolabel' => 'To',
            'workorderlabel' => 'Work Order',
            'samplelabel' => 'Sample',
        );
        $trans = $this->cdbhelper->retTranslationDifKeys($trans);

        $send = array(
                'ctabs' => $ctabs,
                'javascript' => $javascript,
                'firstTab' => $firstTab,
                'allGrid' => $allGrid,
                'workOrder' => $wo,
                'sample' => $sample,) + $trans;
        $this->load->view("mte/open_raw_data_view", $send);
    }

    public function retrieveMTEData() {
        $params = ['workOrder', 'sample', 'field', 'from', 'to', 'inajax'];
        foreach ($params as $v) {
            $$v = isset($_POST[$v]) ? $_POST[$v] : '';
        }
        $sample = intval($sample);
        $sample = $sample > 9 || $sample <= 0 ? $sample : '0' . $sample;
        if (empty($workOrder)) {
            exit(json_encode(['success' => 'false', 'msg' => 'Parameters Error!']));
        }

        $where = "AND WO_code = '$workOrder'";
        if ($sample) {
            $where .= " AND Tool_code = '$sample'";
        }
        $res = ['success' => 'true', 'data' => []];
        foreach ($this->allGrid as $key => $grid) {
            $temp_where = $where;
            if (!empty($field)) {
                $prefix = 'DataForm.';
                if ($key == 7) {
                    $prefix = 'report.';
                }
                if ($key == 1) {
                    $prefix = 'wdt.';
                }
                if ($from) $temp_where .= " AND $prefix$field>=$from";
                if ($to && $to >= $from) $temp_where .= " AND $prefix$field<=$to";
            }

            switch ($key) {
                case 0:
                    $res['data'][$grid] = $this->mainmodel->getFailureDataFromMTE($temp_where);
                    break;
                case 1:
                    $res['data'][$grid] = $this->mainmodel->getWODailySumFromMTE($temp_where);
                    break;
                case 2:
                    $res['data'][$grid] = $this->mainmodel->getBatteryDataFromMTE($temp_where);
                    break;
                case 3:
                    $res['data'][$grid] = $this->mainmodel->getNoLoadDataFromMTE($temp_where);
                    break;
                case 4:
                    $res['data'][$grid] = $this->mainmodel->getTorqueDataFromMTE($temp_where);
                    break;
                case 5:
                    $res['data'][$grid] = $this->mainmodel->getDrillRateFromMTE($temp_where);
                    break;
                case 6:
                    $res['data'][$grid] = $this->mainmodel->getGeneralFormDataFromMTE($temp_where);
                    break;
                case 7:
                    $res['data'][$grid] = $this->mainmodel->getWorkerRawDataFromMTE($temp_where);
                    break;

            }
        }
        if ($inajax == true) exit(json_encode($res));
        else return $res;

    }

    public function genXLSDetailedMulti() {
        if (empty($_POST)) {
            exit('No Data To Download');
        };
        $this->load->library('cexcel');
        $flag = 0;
        $domain = 'http://cnsmteproddb01/uploadfile/';
        $medias = [];
        set_time_limit(300);
        foreach ($_POST as $grid => $data) {
            if ($flag == 0) {
                $this->cexcel->newSpreadSheet($grid);
                $flag = 1;
            } else {
                $this->cexcel->newSheet($grid, true);
            }
            $data = json_decode($data, true);
            $resultset = json_decode($data['resultset'], true);
            if ($grid == 'failure' || $grid == 'worker_raw') {
                foreach ($resultset as $k => $val) {
                    if ($val['IV_name_image']) {
                        foreach (explode(';', $val['IV_name_image']) as $file) {
                            $medias[] = $domain . $val['IV_path'] . '/' . rawurlencode($file);
                        }
                    }
                    if ($val['IV_name_video']) {
                        foreach (explode(';', $val['IV_name_video']) as $file) {
                            $medias[] = $domain . $val['IV_path'] . '/' . rawurlencode($file);
                        }
                    }
                }
            }
            $columns = json_decode($data['col']);
            $titlecolumn = json_decode($data['title']);
            $group = json_decode($data['group']);
            $rowHeight = $data['rowHeight'];

            $this->cexcel->setDocRep($data['docrep']);
            $this->cexcel->createExcelByGrid('MTE_TEST_RAW_DATA', $columns, $titlecolumn, $group, $resultset, $rowHeight);
        }

        date_default_timezone_set('PRC');
        $time = date('YmdHis');
        $name = 'MTE_TEST_RAW_DATA_' . $time;
        $filename = '/tmp/' . $name . '.xlsx';
        $this->cexcel->saveAsXLSX($filename);
        $this->cexcel->cleanMemory();

        $originalName = $name . '.zip';
        $zipname = '/tmp/' . $name . '.zip';
        $zip = new ZipArchive();
        $zip->open($zipname, ZipArchive::CREATE);
        $zip->addFile($filename, basename($filename));

        foreach ($medias as $media) {
            $s = file_get_contents($media);
            $path = '/tmp/' . basename($media);
            file_put_contents($path, $s);
            $zip->addFile($path, rawurldecode(basename($media)));
        }
        $zip->close();
        $fp = @fopen($zipname, 'r');
        header("Content-Type: application/zip");
        header("Content-Disposition: attachment; filename=\"$originalName\"");
        header("Content-Length: " . filesize($zipname));
        fpassthru($fp);
        fclose($fp);
        unlink($zipname);
        return;

    }

}
