<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
//require_once 'cfields.php';
//require_once 'cjmaker.php';

//include 'application/templates/w2grid_template.php';

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of w2gridgen
 *
 * @author dvlpserver
 */
class w2gridgen {

//put your code here

    var $cf;
    var $forceDestroy = true;
    var $gridname = 'gridName';
    var $autoHide = 'Y';
    var $dateFormatPHP = "";
    var $dateFormatJS = "";

    public function setDocRepId($id) {
        $this->docrep = $id;
    }
    
    
    public function addUserBtnToolbar($id, $hint, $img, $caption = "", $index = -1, $imgpng = '') {
        $array = array('id' => $id,
            'hint' => $hint,
            'caption' => $caption,
            'type' => 'button',
            'items' => array()
        );

        if ($imgpng !== '') {
            $array['img'] = $imgpng;
        } else {
            $array['icon'] = $img;
        }


        if ($index != -1) {
            array_push($this->arrayButton[$index]['items'], $array);
            $this->arrayButton[$index]['type'] = 'menu';
            return;
        }

        $numb = count($this->arrayButton);
        $this->arrayButton[$numb] = $array;

        return $numb;

//array_push($this->arrayButton, $array);
    }

    public function setTranslateColumn($bool) {
        $this->translateColumn = $bool;
    }

    public function autoAddToolbarExport($add) {
        $this->addExportButton = $add;
    }

    public function addUserCheckToolbar($id, $hint, $caption, $checked, $icon = 'fa fa-check') {
        $array = array('id' => $id,
            'hint' => $hint,
            'icon' => $icon,
            'caption' => $caption,
            'type' => 'check',
            'checked' => $checked);
        array_push($this->arrayButton, $array);
    }

        public function addUseRadioToolbar($id, $hint, $caption, $checked, $group , $icon = '') {
        $array = array('id' => $id,
            'hint' => $hint,
            'icon' => $icon,
            'caption' => $caption,
            'type' => 'radio',
            'group' => $group,
            'checked' => $checked);
        array_push($this->arrayButton, $array);
    }

    
    
    public function addSpacerToolbar() {
        $array = array('id' => 'spacer' . count($this->arrayButton),
            'type' => 'spacer');

        array_push($this->arrayButton, $array);
    }

    public function addExportToolbar() {

        if ($this->addExportButton) {
            $prefix = 'auto';
        } else {
            $prefix = '';
        }



        $index = $this->addUserBtnToolbar('export', 'Export', '', '', -1, 'img-icon-export');
        $this->addUserBtnToolbar($prefix . 'pdf', 'PDF', 'fa fa-file-pdf-o', 'PDF', $index);
        $this->addUserBtnToolbar($prefix . 'excel', 'Excel', 'fa fa-file-excel-o', 'Excel', $index);
    }

    public function setRowHeight($height) {
        $this->recordHeight = $height;
    }

    public function setExcelDetailed($bool) {
        if ($bool) {
            $this->exportXLSDetailed = 'true';
        } else {
            $this->exportXLSDetailed = 'false';
        }
    }

    public function setExcelDetailedSendResultSet($bool) {
        if ($bool) {
            $this->exportXLSSendResultSet = 'true';
        } else {
            $this->exportXLSSendResultSet = 'false';
        }
    }

    public function setDemandedColumns($array_columns) {
        $this->columnDemanded = $array_columns;
    }

    public function setHeaderCenter($how) {
        $this->centerHeader = true;
    }

    public function addBreakToolbar() {
        $array = array('id' => 'break' . count($this->arrayButton),
            'type' => 'break');

        array_push($this->arrayButton, $array);
    }

    public function addEditToolbar($id = 'edit') {
        return $this->addUserBtnToolbar($this->toolbarNamePrefix . $id, 'Edit Selected Line', 'fa fa-edit');
    }

    public function addUndoToolbar($id = 'undo') {
        return $this->addUserBtnToolbar($this->toolbarNamePrefix . $id, 'Undo Record Changes', 'fa fa-undo');
    }

    public function addDocRepToolbar($id = 'docrep') {
        return $this->addUserBtnToolbar($this->toolbarNamePrefix . $id, 'Document Repository', 'fa fa-file-archive-o');
    }

    public function addInsToolbar($id = 'insert') {
        return $this->addUserBtnToolbar($this->toolbarNamePrefix . $id, 'Insert Line', 'fa fa-plus');
    }

    function addDelToolbar($id = 'delete') {
        return $this->addUserBtnToolbar($this->toolbarNamePrefix . $id, 'Delete Selected Lines', 'fa fa-trash-o');
    }

    public function addToolbarTitle($title) {
        $this->toolbarTitle = $title;

    }

    
    public function addUpdToolbar($id = 'update') {
        return $this->addUserBtnToolbar($this->toolbarNamePrefix . $id, 'Update Information', 'fa fa-floppy-o');
    }

    public function addRetriveToolbar($id = 'retrieve') {
        return $this->addUserBtnToolbar($this->toolbarNamePrefix . $id, 'Retrieve Information', 'fa fa-refresh');
    }

    public function addHideToolbar($id = 'filter') {
        $this->showHide = true;
        return $this->addUserBtnToolbar($this->toolbarNamePrefix . $id, 'Toggle Filter', 'fa fa-bars');
    }
    
    public function setAsSystemGrid() {
        $this->template = str_replace("systemObj: false", "systemObj: true", $this->template);
    }

    public function addCRUDToolbar($retr = true, $ins = true, $upd = true, $del = true, $hide = true) {
        if ($ins || $upd || $del) {
            $this->addBreakToolbar();
        }

        if ($ins) {
            $this->addInsToolbar();
        }

        if ($del) {
            $this->addDelToolbar();
        }
        
        if ($upd) {
            $this->addUpdToolbar();
        }


        if ($retr) {
            $this->addBreakToolbar();
            $this->addRetriveToolbar();
            if ($hide) {


                $this->addHideToolbar();
            }
        }
    }

    public function setInsertNegative($bool) {
        if ($bool) {
            $this->insNeg = '-2';
        } else {
            $this->insNeg = 'undefined';
        }
    }

    public function setFilterLevel($level) {
        if (is_array($level)) {
            $this->defaultLevel = json_encode($level);
        } else {
            $this->defaultLevel = $level;
        }
    }

    

    public function setForceDestroy($bool) {
        $this->forceDestroy = $bool;
    }

    public function setSingleBarControl($bool) {
        $this->singleBarControl = $bool;
    }

    public function setToolbarPrefix($prefix) {
        $this->toolbarNamePrefix = $prefix;
    }

    public function setRenderFunction($bool) {
        $this->makeRenderFunction = $bool;
    }

    public function setGridDivName($div) {
        $this->gridDiv = $div;
    }

    public function setGridVar($var) {
        $this->gridVar = $var;
    }

    public function setFilterPresetId($id) {
        $this->filterPresetID = "'" . $id . "'";
        $data = $this->CI->filterPreset->getPresetForID($id);
                        
        $this->filterPreset = json_encode($this->CI->filterPreset->getPresetForID($id));
        $this->hasFilterId = true;
        $this->makeUsersMenu();
    }
    
    function makeUsersMenu() {
        if (!$this->hasFilterId || !$this->hasController) {
            return;
        }

        $hm = $this->CI->session->userdata('cd_human_resource');

        $sql = "SELECT hmain.cd_human_resource as recid, hmain.ds_human_resource_full as text
    FROM " . $this->CI->db->escape_identifiers('HUMAN_RESOURCE') . "  hmain
    WHERE hmain.dt_deactivated IS NULL
      AND hmain.cd_human_resource != " . $hm . "
      AND ( EXISTS ( SELECT 1
                     FROM " . $this->CI->db->escape_identifiers('HUMAN_RESOURCE_MENU') . " hm, 
                           " . $this->CI->db->escape_identifiers('MENU') . " m
                    WHERE hm.cd_human_resource = hmain.cd_human_resource
                      AND hm.cd_menu           = m.cd_menu
                     AND  m.ds_controller =  '" . $this->selectedController . "'
                 )
       OR EXISTS ( SELECT 1
                    FROM " . $this->CI->db->escape_identifiers('JOBS_HUMAN_RESOURCE') . "  jh,
                        " . $this->CI->db->escape_identifiers('JOBS_MENU') . " jm,
                        " . $this->CI->db->escape_identifiers('JOBS') . "  j, 
                        " . $this->CI->db->escape_identifiers('MENU') . "  m
                   WHERE jh.cd_human_resource = hmain.cd_human_resource
                     AND jm.cd_jobs           = jh.cd_jobs
                     AND j.cd_jobs            = jh.cd_jobs
                     AND j.dt_deactivated IS NULL
                     AND m.cd_menu           = jm.cd_menu
                     AND  m.ds_controller =  '" . $this->selectedController . "'
                    )
       OR hmain.fl_super_user = 'Y' );";

        $this->usersMenu = $this->CI->cdbhelper->basicSQLArray($sql);

        //$this->usersMenu
    }

    public function setGridToolbarFunction($func) {
        $this->template = str_replace("onGridToolbarPressed", $func, $this->template);
    }

    public function setCRUDController($controller, $tryCheckDemand = true) {
        $this->selectedController = $controller;
        $this->template = str_replace("crudController: controllerName", 'crudController:"' . $controller . '"', $this->template);

        if ($tryCheckDemand) {

            $model = '../models/' . $controller . '_model';
            $nameForModel = 'model' . rand(1, 999999999);

            if ($this->CI->load->modelExists($model)) {
                $this->CI->load->model($model, $nameForModel);
                $this->setDemandedColumns($this->CI->$nameForModel->getDemandedColumns());
            };
        }
        
        $this->hasController = true;
        $this->makeUsersMenu();

    }
    
    
    public function setResultParser($json) {
        $this->template = str_replace("parseResult: undefined", 'parseResult:' . $json, $this->template);
    }

    public function setGridName($gridname) {
        $this->template = str_replace("gridName", "'" . $gridname . "'", $this->template);
        $this->gridname = "'" . $gridname . "'";
    }

    public function setColumnToolTip($column, $tooltip) {
        $this->columnToolTip[$column] = $tooltip;
    }

    public function setColumnRenderFunc($column, $func) {
        $this->columnRenderFunction[$column] = $func;
    }

    public function retGrid() {
// grid var tem tudo!!
        $grid = $this->retGridVar();

        $grid = $grid . " $('#" . $this->gridDiv . "').w2grid(" . $this->gridVar . ");";

        return $grid;
    }

    public function showColumnHeader($bool) {
        if ($bool) {
            $this->vcolumnHeader = 'true';
        } else {
            $this->vcolumnHeader = 'false';
        }
    }

    public function showExpandColumn($function) {
        $this->expandColumn = 'true';
        $this->expandColumnFunction = $function;
    }

    public function showLineNumbers($bool) {
        if ($bool) {
            $this->showLineNumbers = 'true';
        } else {
            $this->showLineNumbers = 'false';
        }
    }

    public function retGridVar() {


        $grid = $this->retGridJson();

        if ($this->forceDestroy) {
            $destroy = 'if ( w2ui[' . $this->gridname . '] != undefined ) {
               w2ui[' . $this->gridname . '].destroy();
           };
           ';
        } else {
            $destroy = '';
        }

        return $destroy . $this->gridVar . ' = ' . $grid . ';';
    }

    public function addColumnGroup($span, $caption) {
        return array_push($this->columnGroups, array('span' => $span, 'caption' => $caption));
    }

    public function retGridJson() {
        $CI = & get_instance();

        $this->autoHide = $CI->cdbhelper->getSettings('fl_autohide_filter');


        if ($this->showHide) {
            $this->addSpacerToolbar();
            if ($this->addExportButton) {
                $this->addExportToolbar();
            }

            $this->addUserCheckToolbar('hidefilter', 'Auto Hide Filter', '', ($this->autoHide == 'Y'));
        }


        if ($this->translateColumn) {
            $this->arrayColumn = $this->CI->cdbhelper->retTranslation($this->arrayColumn, 'caption');
        }


        $this->arrayButton = $this->CI->cdbhelper->retTranslation($this->arrayButton, 'caption');
        $this->arrayButton = $this->CI->cdbhelper->retTranslation($this->arrayButton, 'hint');
        $this->columnToolTip = $this->CI->cdbhelper->retTranslation($this->columnToolTip);

        $str_toolbar = json_encode($this->arrayButton);
        $str_columns = $this->getColumnJson();

        $grid = str_replace("<#toolbar#>", $str_toolbar, $this->template);
        $grid = str_replace("<#columns#>", $str_columns, $grid);
        $grid = str_replace("<#multi#>", $this->multiSelect, $grid);

        if ($this->multiSelect == 'true') {
            $grid = str_replace("<#multiselect#>", 'multiSelect: true,', $grid);
        } else {
            $grid = str_replace("<#multiselect#>", '', $grid);
        }

        $grid = str_replace("<#showheader#>", $this->showHeader, $grid);
        $grid = str_replace("<#showfooter#>", $this->vshowFooter, $grid);
        $grid = str_replace("<#showtoolbar#>", $this->vshowToolbar, $grid);
        $grid = str_replace("<#columnheader#>", $this->vcolumnHeader, $grid);
        $grid = str_replace("<#showLineNumbers#>", $this->showLineNumbers, $grid);
        $grid = str_replace("<#insNeg#>", $this->insNeg, $grid);
        $grid = str_replace("<#docrep#>", $this->docrep, $grid);

        
        
        $grid = str_replace("<#expandColumn#>", $this->expandColumn, $grid);
        if ($this->expandColumnFunction !== '') {

            $this->expandColumnFunction = ' onExpand: function (event) {
           ' . $this->expandColumnFunction . '(event); 
        },';
        }
        $grid = str_replace("<#expandColumnFunction#>", $this->expandColumnFunction, $grid);



        //$grid = str_replace('"cgbDateFormat"', 'cgbDateFormat', $grid);

        if (count($this->columnGroups) == 0) {
            $grid = str_replace("<#columngroups#>", '', $grid);
        } else {
            $this->columnGroups = $this->CI->cdbhelper->retTranslation($this->columnGroups, 'caption');

            $columns_group = 'columnGroups: ' . json_encode($this->columnGroups) . ',';
            $grid = str_replace("<#columngroups#>", $columns_group, $grid);
        }
// = array();



        $grid = str_replace("<#txtHeader#>", $this->txtHeader, $grid);
        $grid = str_replace("<#toolbarsearch#>", $this->toolbarsearch, $grid);
        $grid = str_replace("<#records#>", $this->jsonRecords, $grid);
        $grid = str_replace("<#titles#>", $this->getTitleJson(), $grid);
        $grid = str_replace("<#recordHeight#>", $this->recordHeight, $grid);
        $grid = str_replace("<#toolbarTitle#>", $this->toolbarTitle, $grid);
        $grid = str_replace("<#exportXLSDetailed#>", $this->exportXLSDetailed, $grid);
        $grid = str_replace("<#exportXLSSendResultSet#>", $this->exportXLSSendResultSet, $grid);

        $grid = str_replace("<#filterPresetID#>", $this->filterPresetID, $grid);
        $grid = str_replace("<#filterPreset#>", $this->filterPreset, $grid);
        $grid = str_replace("<#defaultLevel#>", $this->defaultLevel, $grid);
        $grid = str_replace("<#usersMenu#>", json_encode($this->usersMenu), $grid);





        $grid = str_replace("<#sbc#>", ($this->singleBarControl == true ? 'true' : 'false'), $grid);

        return $grid;
    }

    public function getColumnJson() {

        $column = $this->arrayColumn;

        foreach ($column as $key => $value) {



            if (isset($value['plCodeField'])) {
                $columnToFind = $value['plCodeField'];
            } else {
                $columnToFind = $value['field'];
            }

            if (array_search($columnToFind, $this->columnDemanded) !== false) {
                $value['caption'] = "<span style='color: blue;'>" . $value['caption'] . '</span>';
                $column[$key]['dem'] = 'Y';
            }

            if (isset($this->columnToolTip[$value['field']])) {
                $value['caption'] = '<span class="w2gridToolTip" rel="tooltip" data-placement="top" data-toggle="tooltip" data-container="body" data-original-title="' . $this->columnToolTip[$value['field']] . '" title="' . $this->columnToolTip[$value['field']] . '">' . $value['caption'] . '</span>';
            }

            if (isset($this->columnRenderFunction[$value['field']])) {
                if (!isset($column[$key]['render'])) {
                    $column[$key]['originalRender'] = 'string';
                } else {
                    $column[$key]['originalRender'] = $column[$key]['render'];
                }
                $column[$key]['render'] = '#!!' . $this->columnRenderFunction[$value['field']] . '!!#';
            }



            if ($this->centerHeader) {
                $value['caption'] = '<div style="text-align: center;">' . $value['caption'] . '</div>';
            }
            $column[$key]['caption'] = $value['caption'];
        }

        $varJson = json_encode($column);
        $varJson = str_replace('"#!!', '', $varJson);
        $varJson = str_replace('!!#"', '', $varJson);
        return $varJson;
    }

    public function getTitleJson() {
        $titles = array();
        foreach ($this->arrayColumn as $key => $value) {
            $titles[$value['field']] = $value['caption'];
        }

        return json_encode($titles);
    }

// funcoes de grid:::::
    public function setMultiSelect($bool) {
        if ($bool) {
            $this->multiSelect = "true";
        } else {
            $this->multiSelect = "false";
        }
    }

// funcoes de grid:::::
    public function setHeader($header, $translate = true) {
        if ($translate) {
            $header = $this->CI->cdbhelper->retTranslation($header);
        }
        $this->txtHeader = $header;
        $this->showHeader = "true";
    }

    function showFooter($show) {
        $this->vshowFooter = $show ? 'true' : 'false';
    }

    function showToolbar($show) {
        $this->vshowToolbar = $show ? 'true' : 'false';
    }

// funcoes de grid:::::
    public function setToolbarSearch($bool) {
        if ($bool) {
            $this->toolbarsearch = "true";
        } else {
            $this->toolbarsearch = "false";
        }
    }

    public function addColumnKey() {

        $this->addHiddenColumn('recid', 'Code', '60px', $this->cf->retTypeKey());
    }

    public function addColumnDate($field, $caption, $canEdit) {

        $this->addColumn($field, $caption, '100px', $this->cf->retTypeDate());
    }

    public function addColumnDeactivated($canEdit) {
        $this->addColumn('dt_deactivated', 'Deactivated', '100px', $this->cf->retTypeDeactivated(), $canEdit);
    }

    public function addHiddenColumn($field, $title, $width, $type, $canEdit = false) {
        $id = $this->addColumn($field, $title, $width, $type, $canEdit);

        $this->arrayColumn[$id - 1]['hidden'] = true;
    }

    public function addColumn($field, $title, $width, $type, $canEdit = false) {

        $cf = $this->cf;

        $array = array();
        $editable = array();


        IF ($type == $cf->retTypeFirstPicture()) {
            $this->setColumnRenderFunc($field, 'gridMakeFirstPicture');
        }

        IF ($type == $cf->retTypeDocFileToolBar()) {
            $this->setColumnRenderFunc($field, 'gridMakeFileToolbar');
        }

        
        

        

        IF ($type == $cf->retTypeImageSpec()) {
            $this->setColumnRenderFunc($field, 'gridMakeImageSpecColumn');
        }

        IF ($type == $cf->retTypeImageSku()) {
            $this->setColumnRenderFunc($field, 'gridMakeImageSkuColumn');
        }
        IF ($type == $cf->retTypeProgressBar()) {
            $this->setColumnRenderFunc($field, 'gridMakeProgressBar');
        }

        IF ($type == $cf->retTypeProgressDonut()) {
            $this->setColumnRenderFunc($field, 'gridMakeProgressDonut');
        }

        IF ($type == $cf->retTypeColor()) {
            $this->setColumnRenderFunc($field, 'gridMakeColorRender');
        }
        
        IF ($type == $cf->retTypePickList() || $type == $cf->retTypeTextPL() ) {
            $this->setColumnRenderFunc($field, 'gridMakePLRender');
        }
        
        if ($type == $cf->retTypeCheckBox() && !$canEdit) {
            $this->setColumnRenderFunc($field, 'gridMakeRenderCB');
        }
        
        

        IF ($type == $cf->retTypeColumnDivider()) {
            $this->setColumnRenderFunc($field, 'gridMakeColumnDivider');
            $array['captionDivider'] = $title;
            $title = '';
            $array['isCaption'] = 'Y';
        }



        if ($this->dateFormatPHP === '') {
            $CI = & get_instance();

            $datef = $CI->cdbhelper->getSettings('fl_date_format');
            $dateArray = explode(';', $datef);


            $this->dateFormatPHP = $dateArray[1];
            $this->dateFormatJS = $dateArray[0];
        }





        $wid = explode('>', $width, 3);

        $array['caption'] = $title;
        $array['size'] = $wid[0];
        $array['field'] = $field;
        $array['sortable'] = true;

        if (count($wid) === 2) {
            $array['min'] = $wid[1];
        }


        switch ($type) {
            case $cf->retTypeKey():
                $array['field'] = 'recid';
                $array['style'] = 'text-align: center;';
                break;

            case $cf->retTypeStringAny():
                $typeform = 'textedit';

                $editable['type'] = 'text';
                $editable['style'] = 'text-align: left;';


                break;

            case $cf->retTypeStringUpper():
                $typeform = 'textedit';
                $array['style'] = ' text-transform:uppercase ; ';

                $editable['type'] = 'text';
                $editable['style'] = 'text-align: left; text-transform: uppercase;';

                break;


            case $cf->retTypeStringLower():
                $typeform = 'textedit';
                $array['style'] = ' text-transform:lowercase ; ';

                $editable['type'] = 'text';
                $editable['style'] = 'text-align: left; text-transform: lowercase;';


                break;

            case $cf->retTypeCheckBox():
                $typeform = 'checkbox';
                $array['style'] = '  ';

                $editable['type'] = 'checkbox';
                $editable['style'] = 'text-align: center; text-transform: lowercase;';


                break;



            case $cf->retTypeDate():
                $array['style'] = 'text-align: center;';

                $editable['type'] = 'date';
                $editable['style'] = 'text-align: center;';
                $editable['format'] = $this->dateFormatJS;


                break;

            case $cf->retTypeDeactivated() :
                $array['style'] = 'text-align: center;';

                $editable['type'] = 'date';
                $editable['style'] = 'text-align: center;';
                $editable['format'] = $this->dateFormatJS;
                $editable['start'] = date($this->dateFormatPHP);

//$editable['start'] = '09/01/2014';
//$editable['end'] = '1/10/2014';

                break;
            case $cf->retTypePickList() :

                $typeform = 'pickListRender';
//$canEdit = false;
                $editable['type'] = 'pickListRender';

                //$array['style'] = 'text-align: left;cursor: pointer; border-style: solid;border-width: 2px; border-color: #C0DCC0;';
//$editable['style'] = 'text-align: left; background-color:"#FEFF9B"; ';
//#FEFF9B
                break;

            case $cf->retTypeTextPL() :
                $typeform = 'pickListRender';
                $canEdit = false;
                $editable['type'] = 'pickListRender';

                //$array['style'] = 'text-align: left;cursor: pointer; border-style: solid;border-width: 2px; border-color: #C0DCC0;';
                //$editable['style'] = 'text-align: left; background-color:"#FEFF9B"; ';
                break;

            case $cf->retTypeInteger() :


                $typeform = 'textedit';
                $array['style'] = 'text-align: right;';
                $array['render'] = 'int';
                $editable['type'] = 'int';
                $editable['style'] = 'text-align: right; ';
                break;

            case $cf->retTypeNum() :

//  { field: 'int', caption: 'int', size: '80px', sortable: true, resizable: true, render: 'int',
//     editable: { type: 'int', min: 0, max: 32756 }
//},

                $typeform = 'textedit';
                $array['style'] = 'text-align: right;';
                $array['render'] = 'float:2';
                $editable['type'] = 'float';
                $editable['style'] = 'text-align: right; ';
                break;


            case $cf->retTypeFloat() :

//  { field: 'int', caption: 'int', size: '80px', sortable: true, resizable: true, render: 'int',
//     editable: { type: 'int', min: 0, max: 32756 }
//},

                $typeform = 'textedit';
                $array['style'] = 'text-align: right;';
                $array['render'] = 'float:4';
                $editable['type'] = 'float';
                $editable['style'] = 'text-align: right; ';
                break;



            case $cf->retTypePercentual():
                $typeform = 'percentual';

                $array['style'] = 'text-align: right;';
                $array['render'] = 'number:2';

                $editable['type'] = 'number:2';
                $editable['style'] = 'text-align: right;';

                break;

            case $cf->retTypeColor():

                $editable['type'] = 'color';
                //$editable['style'] = '';

                break;
        }

        $array['internaltype'] = $type;

// novos controles de $canEdit;

        if (is_array($canEdit)) {
            $editbl = true;
            if (array_key_exists('limit', $canEdit)) {
                $editable['inTag'] = ' maxlength="' . $canEdit['limit'] . '";';
            }

            if (array_key_exists('render', $canEdit)) {
                $array['render'] = "function (record, index, column_index) {" .
                        " var html = " . $canEdit['render'] . "(record, index, column_index); " .
                        " return html; }";
            }

            if (array_key_exists('model', $canEdit) &&
                    array_key_exists('codeField', $canEdit) &&
                    $type = $cf->retTypePickList()
            ) {

                $array['plModel'] = $this->CI->encryption->encrypt($canEdit['model']);
                $array['plCodeField'] = $canEdit['codeField'];

            if (array_key_exists('relationId', $canEdit)) {
                $array['relationId'] = $canEdit['relationId'];
                $array['relationWhere'] = $this->CI->cdbhelper->getFilterQueryId($canEdit['relationWhere']);
            }

                
                $editbl = false;
            }

            if (array_key_exists('precision', $canEdit)) {
                $array['render'] = 'float:' . $canEdit['precision'];
            }

            if (array_key_exists('readonly', $canEdit)) {
                $editbl = !$canEdit['readonly'];
            }


            if ($editbl) {
                $array['editable'] = $editable;
            }
        } else {

            if ($canEdit) {
                $array['editable'] = $editable;
            }
        }

        $array['hideable'] = true;

        return array_push($this->arrayColumn, $array);
    }

    public function __construct() {
        $this->cf = new Cfields();


        $this->showHide = false;
        $this->multiSelect = 'false';
        $this->gridVar = "gridVar";
        $this->gridDiv = "myGrid";
        $this->showHeader = "false";
        $this->vshowFooter = "true";
        $this->vshowToolbar = "true";

        $this->txtHeader = "";
        $this->toolbarsearch = "false";
        $this->vcolumnHeader = "true";
        $this->toolbarNamePrefix = "";
        $this->makeRenderFunction = false;
        $this->singleBarControl = true;
        $this->columnGroups = array();
        $this->showLineNumbers = 'true';
        $this->recordHeight = 24;

        $this->CI = & get_instance();



        $this->template = " { 
	   name: gridName,
    	header  : '<#txtHeader#>', 
      <#multiselect#>
      multiSort: true, 
      singleBarControl: <#sbc#>, 
      singleBarCanUnselect: false,
      reorderColumns: false,
      singleBarSelectedRecId: -1,
      titles: <#titles#>,
      docrep: <#docrep#>,
      freezerow: false,
      defaultLevel: <#defaultLevel#>,
      freezerowRecId: -1,
      presetId: <#filterPresetID#>,
      presetData: <#filterPreset#>,
      crudController: controllerName,
      parseResult: undefined,
      rowRelatedData: [],
      systemObj: false,
      rowRelatedDataControl: undefined,
      redraw: true, 
      usersMenu: <#usersMenu#>,
      lastFilterMounted: undefined,
      toolbarTitle: '<#toolbarTitle#>',
      keyboard: true, 
      recordHeight: <#recordHeight#>,
      exportXLSDetailed: <#exportXLSDetailed#>,
      exportXLSSendResultSet: <#exportXLSSendResultSet#>,
      insNeg: <#insNeg#>,
      <#columngroups#>
      
    show : {
            header : <#showheader#>,
            footer : <#showfooter#>,
            toolbar: <#showtoolbar#>,
            toolbarSearch: <#toolbarsearch#>,
            toolbarReload  : false,
            selectColumn: <#multi#>,
            columnHeaders: <#columnheader#>,
            lineNumbers: <#showLineNumbers#>,
            expandColumn: <#expandColumn#>,
            toolbarInput: <#toolbarsearch#>

        },
        
        columns: <#columns#>,
        records: <#records#>,

	toolbar: {
            systemObj: false,
           items: 
        <#toolbar#>
            ,
            

            onClick: function (target, data) {
                //console.log(target, data);
                //data.idMenu = $(data.originalEvent.target).closest('.w2ui-button').parent().parent().attr('id');
                data.idMenu = $(data.originalEvent.target).closest('.w2ui-button').parent().attr('id');
                if ( target == 'export:autopdf' ) {
                 this.owner.exportTo(2);                 
                };
                
                if ( target == 'export:autoexcel' ) {
                 this.owner.exportTo(1);                 
                };

                if (target == 'w2ui-column-on-off') {
                    dsGridFunctions.showHideColumns(this.owner, '#'+data.idMenu);
                    data.preventDefault();
                }


                onGridToolbarPressed(target, data);
                setTimeout(function () { $().w2tag(); }, 20);

            },
	},
    <#expandColumnFunction#>
    onEditField: function(event) {

      if ( ( this.freezerow && event.recid != this.freezerowRecId ) || $(event.box).hasClass('w2ui-data-disabled') ) {
         event.preventDefault();
      }
      
   },
   
   onDblClick: function (event) {
      var col = event['column'];
      if (this.columns === undefined || this.columns[col] === undefined) {
         return;
      }


      var colname = this.columns[col].field;
      var internaltype = this.columns[col].internaltype;

      if (internaltype == " . $this->cf->retTypePickList() . "  || internaltype == " . $this->cf->retTypeTextPL() . "  ) {       

         if ( this.freezerow && event.recid != this.freezerowRecId ) {
            return;
         }

         gridCallPLEvent(this, event, colname, this.columns[col]);
      }


   },
	onChange: externalOnChange,
        onRestore: externalOnChange, 
        onCopy: function(event) {
            event.preventDefault();
        },

	onDelete: function(event) {
            	event.preventDefault();
	},
   onRender : function (event) {
      event.onComplete = function() {onGridStart(this);} ;
      
   }        
        
        
}";
        $CI = & get_instance();
        $this->arrayButton = array();
        $this->arrayColumn = array();
        $this->arrayTypes = array();
        $this->jsonRecords = '[]';
        $this->addExportButton = true;
        $this->centerHeader = false;
        $this->columnToolTip = array();
        $this->columnRenderFunction = array();
        $this->columnDemanded = array();
        $this->insNeg = 'undefined';
        $this->expandColumn = 'false';
        $this->expandColumnFunction = '';
        $this->exportXLSDetailed = 'false';
        $this->exportXLSSendResultSet = 'false';
        $this->filterPreset = 'undefined';
        $this->filterPresetID = 'undefined';
        $this->translateColumn = true;
        $this->defaultLevel = 1;
        $this->usersMenu = array();
        $this->hasController = false;
        $this->hasFilterId = false;
        $this->selectedController = '';
        $this->toolbarTitle = '';
        $this->docrep = -1;
        
        if ($CI->cdbhelper->getBrowser() == 'safari') {
            $this->addExportButton = false;
        }
    }

    function resetGrid() {
        $this->__construct();
    }

    public function addRecords($jsonRec) {
        $this->jsonRecords = ($jsonRec);
    }

}
