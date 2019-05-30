<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cFilterMaker
 *
 * @author dvlpserver
 */
class cfiltermaker {

    //put your code here
    var $form;
    var $jscript;
    var $level = "1";
    var $all;
    var $filterArray = array();
    var $filterTitle = array();
    var $random;
    var $btColumns = "col-lg-2 col-md-3 col-sm-6 col-xs-6";
    var $btColumnsYesNo = "col-lg-1 col-md-2 col-sm-3 col-xs-3";
    var $tabNames = array();
    var $vClassToKill = 'filterClassToKill';

    //form


    const f_SimpleFilterUpper = '
      <div class="form-group #btSize# cls#rdmlvl#" id="#field#_frame" style="margin-bottom: 0px;margin-top:5px;min-height: 54px;">
        <div class="row" style="height: 18px;padding-right: 10px;">
        <label for="#field#" class="control-label-filter">#title#</label><i class="caret pull-right filterbutton"  id="#field#_context"></i>
        </div>

        <div class="row" style="padding-bottom: 0px;padding-left: 5px; padding-right: 5px;">
        <input name="simple_filter_upper" type="text" class="simple_filter_upper form-control input-sm col-md-12" style="margin-bottom: 1px;" id="#field#" #addon# />
        </div>

        </div>';
    
    const f_SimpleFilterInt = '
      <div class="form-group #btSize# cls#rdmlvl#" id="#field#_frame" style="margin-bottom: 0px;margin-top:5px;min-height: 54px;">
        <div class="row" style="height: 18px;padding-right: 10px;">
        <label for="#field#" class="control-label-filter">#title#</label><i class="caret pull-right filterbutton"  id="#field#_context"></i>
        </div>

        <div class="row" style="padding-bottom: 0px;padding-left: 5px; padding-right: 5px;">
        <input name="simple_filter_int" type="text" class="simple_filter_int form-control input-sm col-md-12" style="margin-bottom: 1px;" id="#field#" #addon# />
        </div>

        </div>';
    
    
    const f_DateFilter = '
      <div class="form-group datefilter #btSize# cls#rdmlvl#" id="#field#_frame" field="#field#" style="margin-bottom: 0px;margin-top:5px; padding-left: 3px !important;;height: 54px;" #addon# >
        <div class="row" style="height: 18px;padding-right: 10px;">
        <label for="#field#" class="control-label-filter" style="padding-left: 15px !important;">#title#</label><i class="caret pull-right filterbutton"  id="#field#_context"></i>
        </div>

        <div class="col-md-6 col-xs-6" style="padding-bottom: 0px;padding-left: 0px; padding-right: 2px;padding-top: 4px;;min-height: 54px;">
        <input name="date_filter" value="#from#" type="text" class="from form-control input-sm col-md-12 #field#_class" style="margin-bottom: 1px;padding-right: 0px !important; text-align: center; height: 29px;" id="#field#_from" />
        </div>

        <div class="col-md-6 col-xs-6" style="padding-left: 2px; padding-right: 0px;padding-top: 4px; padding-bottom: 2px;">
        <input name="date_filter" value="#to#" type="text" class="to form-control input-sm col-md-12 #field#_class" style="margin-bottom: 1px; text-align: center;height: 29px;" id="#field#_to" />
        </div>

        </div>';


    /*
      const f_SimpleFilterUpper = '
      <div class="form-group col-lg-2 col-md-3 col-sm-6" id="#field#_frame" style="margin-bottom: 5px;margin-top:5px;">
      <div class="row" style="height: 18px;padding-right: 10px;">
      <label for="#field#" class="control-label-filter">#title#</label><i class="caret pull-right filterbutton"  id="#field#_context"></i>
      </div>

      <div class="row">
      <input name="simple_filter_upper" type="text" class="simple_filter_upper form-control input-sm col-md-12" style="width:100%;padding: 5px 10px !important; height: 30px; margin-top: 2px;" id="#field#" #addon# />
      </div>

     *      </div>';

     * /

     */
    /*
      const f_PicklistFilter = '

      <div class="picklist_filter_frame form-group col-md-2" id="#field#_frame" style="margin-bottom: 5px;">
      <label for="#field#" >#title#</label><i class="caret filterbutton" id="#field#_context"></i>
      <input type="hidden" name="picklist_filter" id="#field#" class="picklist_filter form-control input-sm col-md-12"  #addon# >
      </div>

      ';
     */
    const f_PicklistFilter = '
      <div class="form-group #btSize# cls#rdmlvl#" id="#field#_frame" style="margin-bottom: 0px;margin-top:5px;;min-height: 54px;">
        <div class="row" style="height: 18px;padding-right: 10px;">
        <label for="#field#" class="control-label-filter">#title#</label><i class="caret pull-right filterbutton"  id="#field#_context"></i>
        </div>
        
        <div class="row" style="height: 36px;">
        <div id="#field#_pin" style="position: absolute;margin-left:50%;margin-right:auto;margin-top:7px;margin-bottom:auto; z-index: 0"> X </div>
        <input type="hidden" name="picklist_filter" id="#field#" class="picklist_filter form-control input-sm col-md-12"  #addon# > 
        </div>
      

        </div>
      
      ';


    /*
      const f_filterYesNo = '<div class="simple_filter_yesno_frame col-md-1 form-group"  id="#field#_frame" style="margin-bottom: 5px;">
      <label for="simple_filter_yesno">#title#</label>
      <select name="simple_filter_yesno" size="1" id="#field#" class="simple_filter_yesno form-control input-sm" style="width: 100%" #addon# >
      <option value="A" #opta#>#ALL#</option>
      <option value="Y" #opty#>#YES#</option>
      <option value="N" #optn#>#NO#</option>
      </select>
      </div>';
     */
    const f_filterYesNo = '
      <div class="form-group #btSizeYesNo# cls#rdmlvl#" id="#field#_frame" style="margin-bottom: 0px;margin-top:5px;;min-height: 54px;">
        <div class="row" style="height: 18px;padding-right: 10px;">
        <label for="#field#" class="control-label-filter">#title#</label>
        </div>

        <div class="row" style="height: 36px;">

        <select name="simple_filter_yesno" size="1" id="#field#" class="simple_filter_yesno form-control input-sm col-md-12"  #addon# >
          <option value="A" #opta#>#ALL#</option>
          <option value="Y" #opty#>#YES#</option>
          <option value="N" #optn#>#NO#</option>
        </select>
        </div>
        
      </div>';

    function __construct() {
        $this->style = "";
        $this->form = array_fill(0, 10, '');
        $this->jscript = "";
        $this->random = rand(1, 999999);
        $this->tabNames = array();

        $this->CI = & get_instance();

        $this->all = '';
    }

    public function setTabNames($array) {
        $this->tabNames = $array;
    }

    public function addSimpleFilterUpper($ds_title, $selector, $ds_sql_field_name = "") {

        if ($ds_sql_field_name == "") {
            $ds_sql_field_name = $selector;
        }

        $options = array(
            'selector' => $selector,
            'fieldname' => $ds_sql_field_name,
        );

        $this->addFilter($selector, $ds_title, $options);
    }

    public function addPickListFilter($ds_title, $selector, $controller, $ds_sql_field_name = "", $retrieveOnLoad = false, $defOption = "A") {

        $this->addFilter($selector, $ds_title, array('controller' => $controller, 'fieldname' => $ds_sql_field_name == '' ? $selector : $ds_sql_field_name));

        /*
          $baseArray = array(
          'selector'        => $selector,
          'controller'      => 'NONE',
          'fieldname'       => $selector,
          'yesno'           => false,
          'default'         => '*default*',
          'level'           => $this->level,
          'hasDeactivated'  => true,
          'retrieveOnLoad'  => false,
          'selectorRelated' => '',
          'multiSelection'  => false,
          'uppercase'       => true,
          'exists'          => array( 'tablemain'            => 'NONE',
          'columnmain'           => 'cd_none',
          'tablerelation'        => 'NONE_RELATION',
          'columnfilter'         => 'cd_other_field',
          'columnmainonrelation' => 'cd_none_with_other_name'
          )
          );
         * 
         */
    }

    public function addPickListFilterWithRel($ds_title, $selector, $controller, $filterRelated, $ds_sql_field_name = "", $retrieveOnLoad = false, $defOption = "A") {

        $this->addFilter($selector, $ds_title, array('controller' => $controller, 'selectorRelated' => $filterRelated, 'fieldname' => $ds_sql_field_name == '' ? $selector : $ds_sql_field_name));
    }

    public function addPickListFilterExists($ds_title, $controller, $selector, $ds_sql_table_name, $ds_sql_field_name, $ds_exists_table, $ds_sql_exists_column, $ds_sql_main_column_on_exists_table = "", $hasDeactivate = true, $retrieveOnLoad = false, $defOption = "A", $specifFunction = 'retPickList', $multi = false) {


        $this->addFilter($selector, $ds_title, array('controller' => $controller,
            'fieldname' => $ds_sql_field_name == '' ? $selector : $ds_sql_field_name,
            'extablemain' => $ds_sql_table_name,
            'excolumnmain' => $ds_sql_field_name,
            'extablerelation' => $ds_exists_table,
            'excolumnfilter' => $ds_sql_exists_column,
            'excolumnmainonrelation' => $ds_sql_main_column_on_exists_table == '' ? $ds_sql_field_name : $ds_sql_main_column_on_exists_table,
            'hasDeactivated' => $hasDeactivate,
            'retrieveOnLoad' => $retrieveOnLoad,
            'specifFunction' => $specifFunction,
            'multi' => $multi
        ));
    }

    public function addFilterYesNo($ds_title, $ds_selector, $ds_sql_field_name = "", $defaut_opt = "A") {

        if ($ds_sql_field_name == "") {
            $ds_sql_field_name = $ds_selector;
        }

        $this->addFilter($ds_selector, $ds_title, array('fieldname' => $ds_sql_field_name,
            'yesno' => true,
            'default' => $defaut_opt
        ));
    }

    public function addFilterDate($ds_title, $ds_selector, $ds_sql_field_name = "", $dt_start = "", $dt_finish = '') {

        $this->addFilter($ds_selector, $ds_title, array('fieldname' => $ds_sql_field_name,
            'dateFilter' => true,
            'dateStart' => $dt_start,
            'dateEnd' => $dt_finish
        ));
    }

    public function addFilterNumber($ds_title, $ds_selector, $ds_sql_field_name = "", $mask="10.0", $value = "", $sep=',') {

        $this->addFilter($ds_selector, $ds_title, array('fieldname' => $ds_sql_field_name,
            'numberFilter' => true,
            'numberMask'   => $mask, 
            'numberSep'    => $sep,
            'numberValue'  => $value
            
        ));
    }
    
    
    public function retFilters() {
        $this->retFiltersNew();

        return implode('', $this->form);
    }

    public function retFiltersWithGroup($text = '---') {
        $tabHtml = '';
        $tabJavascript = '<script>';

        if ($text == '---') {
            $text = 'Search';
        }

        $ctabs = $this->CI->ctabs;
        if (1 == 2) {
            $ctabs = new ctabs();
        }

        if (count($this->tabNames) > 0) {
            $set = true;
            $tabId = 'tab_filter_' . $this->random;
            $ctabs->setTabId($tabId);


            foreach ($this->tabNames as $key => $value) {
                $vid = 'tab' . $this->random . 'l' . $value['level'] . '_div';
                $vclass = 'cls' . $this->random . '-' . $value['level'];

                if ($set) {
                    $ctabs->setContentDivId($vid);
                    $set = false;
                }

                $tabJavascript = $tabJavascript . "$('.$vclass').detach().appendTo('#$vid');";

                $ctabs->addTab($value['title'], 'tab' . $this->random . 'l' . $value['level']);
            }
            $ctabs->makeContentDiv();

            $tabHtml = $ctabs->retTabs();
            $tabJavascript = $tabJavascript . "$('#$tabId').ctabStart();";
        } else {
            $tabJavascript = $tabJavascript . '$("#filterBoxBody' . $this->random . '").cgbMakeScrollbar({alwaysVisible: false, autoWrapContent: true, maxHeight: "calc(40vh)", theme: "inset-3-dark", setLeft: "0px"});';
        }
        $tabJavascript = $tabJavascript . '</script>';


        $this->retFiltersNew();

        $text = $this->CI->cdbhelper->retTranslation($text);

        $group = "<div class='box box-primary box-solid' >";
        $group = $group . " <div class='box-header with-border '>";
        $group = $group . "<h3 class='box-title'><strong>$text</strong></h3>";
        $group = $group . "</div>";
        $group = $group . "<div class='box-body' id='filterBoxBody" . $this->random . "' style='background-color: #f0f0f0;overflow-y: auto;overflow-x: hidden;'>";
        $group = $group . "<div class='row'>";
        $group = $group . "<div class='col-md-12'>";

        $group = $group . $tabHtml;


        $group = $group . implode('', $this->form) . $tabJavascript;
        ;

        $group = $group . "</div>";

        $group = $group . "</div>";
        $group = $group . "</div>";
        $group = $group . "</div>";

        //$group = '<div class="filter_groupbox"> <div class="filter_groupbox_legend">'.$text.'</div>';
        //$group = $group . $this->form;
        //$group = $group . "</div>";
        return $group;
    }

    public function retJavascript() {
        return $this->jscript;
    }

    public function setFilterLevels($level) {
        $this->level = $level;
                
    }

    /**
     * 
     * @param type $selector
     * @param type $title
     * @param type $options
     *     * 'selector'        => $selector,
     *     'controller'      => 'NONE',
     *    'fieldname'       => $selector,
     *    'yesno'           => false,
     *    'default'         => '*default*',
     *    'level'           => $this->level,
     *    'hasDeactivated'  => true,
     *    'retrieveOnLoad'  => false,
     *    'selectorRelated' => '',
     *    'multi'           => false,
     *    'uppercase'       => true,
     *    'plFixedSelect'   => array(),
     *    'extablemain'            => 'NONE',
     *    'excolumnmain'           => 'cd_none',
     *    'extablerelation'        => 'NONE_RELATION',
     *    'excolumnfilter'         => 'cd_other_field',
     *    'excolumnmainonrelation' => '',
     *    'filtersButtons'         => '*defaults*',
     *    'likeIlike'              => 'L'  L ou I,
     *    'forceRelationId'        => 'NONE'
     * 
     */
    function addFilter($selector, $title, $options) {

        $baseArray = array(
            'selector' => $selector,
            'controller' => 'NONE',
            'fieldname' => $selector,
            'yesno' => false,
            'default' => '*default*',
            'level' => $this->level,
            'specifFunction' => 'NONE',
            'hasDeactivated' => true,
            'retrieveOnLoad' => false,
            'selectorRelated' => '',
            'multi' => false,
            'uppercase' => true,
            'plFixedSelect' => array(),
            'extablemain' => 'NONE',
            'excolumnmain' => 'cd_none',
            'extablerelation' => 'NONE_RELATION',
            'excolumnfilter' => 'cd_other_field',
            'excolumnmainonrelation' => '',
            'exwhereaddon' => '',
            'filtersButtons' => '*defaults*',
            'likeIlike' => 'I' /* L ou I */,
            'forceRelationId' => 'NONE',
            'small' => false,
            'locked' => false,
            'selectedData' => array(),
            'dateFilter' => false,
            'numberFilter' => false,
            'numberMask'   => '10.0',
            'numberSep'    => ',',
            'numberValue'  => '',
            'dateStart' => '',
            'dateEnd' => '',
            'translateOptions' => true,
            'demanded' => 'N',
            'startWith' => false // true the simple filter will be by default startwith, false will be like
        );


        $mergedArray = array_merge($baseArray, $options);

        $this->filterArray[$selector] = $mergedArray;
        $this->filterTitle[$selector] = $title;
        
        
        
        
    }

    /**
     * 
     */
    public function resetFilters() {
        $this->filterArray = array();
        $this->filterTitle = array();
        $this->form = array();
        $this->form = array_fill(0, 10, '');
        $this->jscript = '';

        $this->setColumnDefault();
    }

    public function retFiltersNew() {

        $CI = & get_instance();

        $this->filterTitle = $CI->cdbhelper->retTranslationDifKeys($this->filterTitle);


        foreach ($this->filterArray as $key => $value) {
            if ($this->CI->db->dbdriver != 'postgre') { 
                $value['fieldname'] = str_replace('"', '', $value['fieldname']);
            }

            
            if ($value['dateFilter']) {
                $this->makeFilterDate($value);
                continue;
            }

            if ($value['numberFilter']) {
                $this->makeFilterNumber($value);
                continue;
            }

            
            if ($value['yesno']) {
                // funcao que faz isso
                $this->makeFilterYesAll($value);
                continue;
            }

            if ($value['controller'] != 'NONE' || $value['plFixedSelect'] != array()) {
                $value['controller'] != 'FIXED';
                $this->makeFilterPL($value);
            } else {
                $this->makeFilterSimple($value);
            }
        }
    }

    function setColumnDefault() {
        $this->btColumns = "col-lg-2 col-md-3 col-sm-6  col-xs-6";
        $this->btColumnsYesNo = "col-lg-1 col-md-2 col-sm-3 col-xs-3";
    }

    function setColumnBig() {
        $this->btColumns = "col-lg-3 col-md-4 col-sm-6 col-xs-6";
        $this->btColumnsYesNo = "col-lg-2 col-md-3 col-sm-4  col-xs-4";
    }

    function setColumnSuperBig() {
        $this->btColumns = "col-lg-4 col-md-6 col-sm-6  col-xs-6";
        $this->btColumnsYesNo = "col-lg-2 col-md-3 col-sm-4  col-xs-4";
    }

    function makeFilterDate($option) {

        $filter = str_replace("#title#", $this->filterTitle[$option['selector']], self::f_DateFilter);
        $filter = str_replace("#field#", $option['selector'], $filter);
        $filter = str_replace("#btSize#", $this->btColumns, $filter);
        $filter = str_replace("#btSizeYesNo#", $this->btColumnsYesNo, $filter);
        $filter = str_replace("#from#", $option['dateStart'], $filter);
        $filter = str_replace("#to#", $option['dateEnd'], $filter);
        $filter = str_replace("#rdmlvl#", $this->random . '-' . $option['level'], $filter);



        /*         * ** ADDON AREA *** */
        // sqlid = campo para fazer   
        $addon = "";
        

        $id = $this->CI->cdbhelper->getFilterQueryid($option['fieldname']);
        $addon = $addon . ' sqlid="' . $id . '"';

        $addon = $addon . ' lvl="' . $option['level'] . '"';

        $addon = $addon . ' dem="' . $option['demanded'] . '" ';


        // like clausule (S - StartWith, L = Like)
        $addon = $addon . ' dateSearch ="B"';




        // adiciono os addons
        $filter = str_replace("#addon#", $addon, $filter);

        $this->form[$option['level']] = $this->form[$option['level']] . $filter;
        $this->jscript = $this->jscript . ' setDateFilterContextMenu("' . $option['selector'] . '"); ';

        /*
          $baseArray = array(
          'selector'        => $selector,
          'controller'      => 'NONE',
          'fieldname'       => $selector,
          'yesno'           => false,
          'default'         => '*default*',
          'level'           => $this->level,
          'hasDeactivated'  => true,
          'retrieveOnLoad'  => false,
          'selectorRelated' => '',
          'multiSelection'  => false,
          'uppercase'       => true,
          'exists'          => array( 'tablemain'            => 'NONE',
          'columnmain'           => 'cd_none',
          'tablerelation'        => 'NONE_RELATION',
          'columnfilter'         => 'cd_other_field',
          'columnmainonrelation' => 'cd_none_with_other_name'
          )
          );
         * 
         */

        // id="#field#" sqlid = "#sqlfield#"  lvl = "#lvl#" retrieved = "false" controller= "#controller#" relatedFilter="#relatedFilter#"
    }

    /**
     * 
     * @param type $option
     */
    function makeFilterYesAll($option) {

        if ($this->all == '') {

            $this->all = $this->CI->cdbhelper->retTranslation(['ALL', 'YES', 'NO']);
        }

        $addon = "";
        //checo o default;
        //$default = ;
        $opta = "";
        $opty = "";
        $optn = "";

        switch ($option['default'] == '*default*' ? 'A' : $option['default']) {
            case 'A':
                $opta = 'selected="selected"';
                break;
            case 'Y':
                $opty = 'selected="selected"';
                break;
            case 'N':
                $optn = 'selected="selected"';
                break;
        }
        // fim do default
        $filter = str_replace("#title#", $this->filterTitle[$option['selector']], self::f_filterYesNo);
        $filter = str_replace("#field#", $option['selector'], $filter);
        $filter = str_replace("#btSize#", $this->btColumns, $filter);
        $filter = str_replace("#btSizeYesNo#", $this->btColumnsYesNo, $filter);
        $filter = str_replace("#rdmlvl#", $this->random . '-' . $option['level'], $filter);


        // internacionalizacao das opcoes!
        $filter = str_replace(array('#ALL#', '#YES#', '#NO#'), $this->all, $filter);
        // selecao do default;
        $filter = str_replace(array('#opta#', '#opty#', '#optn#'), array($opta, $opty, $optn), $filter);

        /*         * ** ADDON AREA *** */
        // sqlid = campo para fazer       
        $id = $this->CI->cdbhelper->getFilterQueryid($option['fieldname']);
        $addon = $addon . ' sqlid="' . $id . '"';

        $addon = $addon . ' dem="' . $option['demanded'] . '" ';

        //level
        $addon = $addon . ' lvl="' . $option['level'] . '"';
        // adiciono os addons
        $filter = str_replace("#addon#", $addon, $filter);

        $this->form[$option['level']] = $this->form[$option['level']] . $filter;
        $this->jscript = $this->jscript . '$("#' . $option['selector'] . '").select2({classToDestroy: "'.$this->vClassToKill.'"});';
        //$this->jscript = $this->jscript . '$(".filterBoxBody' . $this->random . '").slimScroll({height: "100%", alwaysVisible: true});';
        //slimScroll
    }

    function makeFilterPL($option) {
        $addon = "";
        $multi = $option['multi'];
        $multisrt = $multi ? "Y" : "N";
        $hasDeac = $option['hasDeactivated'] ? 'Y' : 'N';

        if ($option['specifFunction'] == 'NONE') {
            $controller = $option['controller'] . "/retPickList";
        } else {
            $controller = $option['controller'] . "/" . $option['specifFunction'];
        }

        $filter = self::f_PicklistFilter;

        $filter = str_replace("#title#", $this->filterTitle[$option['selector']], $filter);
        $filter = str_replace("#field#", $option['selector'], $filter);
        $filter = str_replace("#btSize#", $this->btColumns, $filter);
        $filter = str_replace("#btSizeYesNo#", $this->btColumnsYesNo, $filter);
        $filter = str_replace("#rdmlvl#", $this->random . '-' . $option['level'], $filter);


        if ($option['small']) {
            $filter = str_replace("picklist_filter_frame", 'picklist_filter_half_frame', $filter);
        }

        /*         * ** ADDON AREA *** */
        //level
        $addon = $addon . ' lvl="' . $option['level'] . '"';
        // controller
        $addon = $addon . ' controller="' . $controller . '"';
        // mutliselection
        if ($multi == 'Y') {
            $addon = $addon . ' multiple = "multiple" ';
        }
        // only active
        if ($hasDeac == 'Y') {
            $addon = $addon . ' deactFilter = "1" ';
        } else {
            $addon = $addon . ' deactFilter = "0" ';
        }

        $addon = $addon . ' dem="' . $option['demanded'] . '" ';
        $filter = str_replace("#rdmlvl#", $this->random . '-' . $option['level'], $filter);


        //hasdeacs
        $addon = $addon . ' hasDeact = "' . $hasDeac . '" ';

        // retrieved:
        if ($option['selectorRelated'] != '') {
            $addon = $addon . ' relatedFilter="' . $option['selectorRelated'] . '"';
            // controler eh diferente para quando tem relacao!

            if ($option['specifFunction'] == 'NONE') {
                $controller = $option['controller'] . "/retPickListRel";
            } else {
                $controller = $option['controller'] . "/" . $option['specifFunction'];
            }


            //$controller = $option['controller'].  "/retPickListRel";
        }

        // selects fixos!!
        if ($option['plFixedSelect'] != array()) {

            $send = array();
            $array_data = array();
            foreach ($option['plFixedSelect'] as $key => $value) {
                $desc = $value['desc'];
                if ($option['translateOptions']) {
                    $desc = $this->CI->cdbhelper->retTranslation($desc);
                }
                if (isset($value['idDesc'])) {
                    $mainId = $value['idDesc'];
                } else {
                    $mainId = $key;
                }
                if (!is_array($value['sql'])) {

                    $id = $this->CI->cdbhelper->getFilterQueryid($value['sql']);

                    if (array_key_exists('default', $value)) {
                        if ($value ['default'] == 'Y') {
                            $option['default'] = $id;
                        }
                    }

                    $array_data = array('id' => $id, 'text' => $desc, 'iddesc' => $mainId);

                    array_push($send, $array_data);
                } else {
                    $array_data['sql'] = array();
                    $array_data['text'] = $desc;
                    
                    $array_data['iddesc'] = $mainId;

                    foreach ($value['sql'] as $keySql => $valueSql) {
                        $xdesc = $value['optDesc'][$keySql];
                        
                        if (isset($value['optId'])) {
                            $xdescid = $value['optId'][$keySql];
                        } else {
                            $xdescid = $keySql;
                        }

                        if ($option['translateOptions']) {
                            $xdesc = $this->CI->cdbhelper->retTranslation($xdesc);
                        }

                        $arrayOpt['id'] = $this->CI->cdbhelper->getFilterQueryid($valueSql);

                        $arrayOpt['optDesc'] = $xdesc;
                        $arrayOpt['optId']   = $xdescid;
                        $arrayOpt['iddesc']  = $mainId;

                        if (isset($value['default'])) {

                            if (isset($value['default'])) {
                                if ($value['default'] == $keySql) {
                                    $array_data['default'] = $arrayOpt['id'];
                                }
                            }
                        }

                        array_push($array_data['sql'], $arrayOpt);
                    }

                    array_push($send, $array_data);
                }
            }

            //die (json_encode($send));
            $addon = $addon . " plFixedSelect='" . json_encode($send) . "'";
        }

        // initial data!!!
        if ($option['selectedData'] != array()) {
            $send = array();

            foreach ($option['selectedData'] as $key => $value) {
                $text = '';
                $optId = '';
                
                if (isset($value['description'])) {
                    $text = $value['description'];
                }
                
                if (isset($value['optId'])) {
                    $optId = $value['optId'];
                }
                
                
                $arr = array('id' => $value['recid'], 'text' => $value['description'], 'optId' => $optId);


                array_push($send, $arr);
            }

            $addon = $addon . " selectedData='" . json_encode($send) . "'";
        }




        // forceRelatedId
        if ($option['forceRelationId'] != 'NONE') {
            $addon = $addon . ' forceRelatedId="' . $option['forceRelationId'] . '"';

            if ($option['specifFunction'] == 'NONE') {
                $controller = $option['controller'] . "/retPickListRel";
            } else {
                $controller = $option['controller'] . "/" . $option['specifFunction'];
            }


            // $controller = $option['controller'].  "/retPickListRel";         
        }


        if ($option['default'] != '*default*') {
            $addon = $addon . ' default="' . $option['default'] . '"';
        }

        if ($option['locked']) {
            $addon = $addon . ' startLocked="Y"';
        }


        $existing = 'N';

        // vejo a questao do existing!!s
        if ($option['extablemain'] != 'NONE') {

            if ($option['hasDeactivated']) {
                $sqladdon = ' and "' . $option['extablerelation'] . '".dt_deactivated is null ';
            } else {
                $sqladdon = '';
            }

            $ds_sql_main_column_on_exists_table = $option['excolumnmainonrelation'] == '' ?
                    $option['excolumnmain'] :
                    $option['excolumnmainonrelation'];

            $tablerelation = $option['extablerelation'];
            $ds_sql_table_name = $option['extablemain'];
            $ds_sql_field_name = $option['excolumnmain'];
            $ds_sql_exists_column = $option['excolumnfilter'];
            if ($multi) {
                $wherecol = ' AND ' . $ds_sql_exists_column . ' in (%s) ' . $sqladdon;
            } else {
                $wherecol = ' AND ' . $ds_sql_exists_column . ' = %s ' . $sqladdon;
            }

            if ($option['exwhereaddon'] !== '') {
                $wherecol = $wherecol . $option['exwhereaddon'];
            }


            $sql = ' AND EXISTS ( SELECT 1'
                    . ' FROM "' . $tablerelation . '"'
                    . ' WHERE ' . $ds_sql_main_column_on_exists_table . ' = "' . $ds_sql_table_name . '".' . $ds_sql_field_name
                    . $wherecol
                    . ' ) ';

            //echo ($sql);

            $id = $this->CI->cdbhelper->getFilterQueryid($sql);
            $addon = $addon . ' sqlid="' . $id . '"';
            $addon = $addon . ' cgbexists="Y"';
        } else {
            // sqlid = campo para fazer
            //$id = $this->CI->cdbhelper->getFilterQueryid($option['fieldname']);
            //$addon = $addon . ' sqlid="id='.$id.'"';
            $id = $this->CI->cdbhelper->getFilterQueryid($option['fieldname']);

            $addon = $addon . ' sqlid="' . $id . '"';
        }

        $filter = str_replace("#addon#", $addon, $filter);

        $this->jscript = $this->jscript . 'select2Start("' . $option['selector'] . '" , "' . $controller . '", "ALL", "'.$this->vClassToKill.'"); ';

        $this->jscript = $this->jscript . ' setPLContextMenu("' . $option['selector'] . '"); ';
        
        $this->form[$option['level']] = $this->form[$option['level']] . $filter;
    }

    function makeFilterSimple($option) {


        $filter = str_replace("#title#", $this->filterTitle[$option['selector']], self::f_SimpleFilterUpper);
        $filter = str_replace("#field#", $option['selector'], $filter);
        $filter = str_replace("#btSize#", $this->btColumns, $filter);
        $filter = str_replace("#btSizeYesNo#", $this->btColumnsYesNo, $filter);
        $filter = str_replace("#rdmlvl#", $this->random . '-' . $option['level'], $filter);

        /*         * ** ADDON AREA *** */
        // sqlid = campo para fazer   
        $addon = "";
        $id = $this->CI->cdbhelper->getFilterQueryid($option['fieldname']);
        $addon = $addon . ' sqlid="' . $id . '"';

        $addon = $addon . ' lvl="' . $option['level'] . '"';

        // like style
        $addon = $addon . ' like="' . $option['likeIlike'] . '"';

        $addon = $addon . ' dem="' . $option['demanded'] . '" ';

        // like clausule (S - StartWith, L = Like)
        if ($option['startWith']) {
            $addon = $addon . ' likeSearch ="S"';
        } else {
            $addon = $addon . ' likeSearch ="L"';
        }

        if ($option['default'] != '*default*') {
            $addon = $addon . ' value="' . $option['default'] . '"';
        }



        // adiciono os addons
        $filter = str_replace("#addon#", $addon, $filter);

        $this->form[$option['level']] = $this->form[$option['level']] . $filter;
        $this->jscript = $this->jscript . ' setSimpleFilterContextMenu("' . $option['selector'] . '"); ';

    }

        function makeFilterNumber($option) {


        $filter = str_replace("#title#", $this->filterTitle[$option['selector']], self::f_SimpleFilterInt);
        $filter = str_replace("#field#", $option['selector'], $filter);
        $filter = str_replace("#btSize#", $this->btColumns, $filter);
        $filter = str_replace("#btSizeYesNo#", $this->btColumnsYesNo, $filter);
        $filter = str_replace("#rdmlvl#", $this->random . '-' . $option['level'], $filter);

        /*         * ** ADDON AREA *** */
        // sqlid = campo para fazer   
        $addon = "";
        $id = $this->CI->cdbhelper->getFilterQueryid($option['fieldname']);
        $addon = $addon . ' sqlid="' . $id . '"';

        $addon = $addon . ' lvl="' . $option['level'] . '"';

        // like style
        $addon = $addon . ' like="' . $option['likeIlike'] . '"';

        $addon = $addon . ' dem="' . $option['demanded'] . '" ';
        $addon = $addon . ' sep="' . $option['numberSep'] . '" ';
        $addon = $addon . ' mask="' . $option['numberMask'] . '" ';

        // like clausule (S - StartWith, L = Like)
        $addon = $addon . ' likeSearch ="S"';

        if ($option['numberValue'] != '') {
            $addon = $addon . ' value="' . $option['numberValue'] . '"';
        }

        // adiciono os addons
        $filter = str_replace("#addon#", $addon, $filter);

        $this->form[$option['level']] = $this->form[$option['level']] . $filter;
        $this->jscript = $this->jscript . ' setSimpleFilterIntContextMenu("' . $option['selector'] . '"); ';

    }


    public function getFilterNames() {
        $arrayRet = array();
        foreach ($this->filterArray as $key => $value) {
            array_push($arrayRet, $key);
        }

        return $arrayRet;
    }

}
