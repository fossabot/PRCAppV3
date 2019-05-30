<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ctabs
 *
 * @author dvlpserver
 */
class ctabs {

    //put your code here


    public $tab,
            $options,
            $dropdown,
            $tabName,
            $retDiv,
            $idSelected,
            $divName,
            $addGridDiv,
            $divContentName,
            $tabFunction,
            $tabPosition = 'tabs-above';

    public function __construct() {
        $this->CI = & get_instance();
        $this->ResetTabs();
    }

    public function addTab($title, $id, $selected = false, $image = '', $htmladdon = '') {
        array_push($this->options, array('title' => $title,
            'id' => $id,
            'image' => $image,
            'htmlAddon' => $htmladdon)
        );
        if ($selected) {
            $this->idSelected = $id;
        }
    }

    public function addDropDown($title, $id, $image = '') {

        array_push($this->dropdown, array('title' => $title,
            'id' => $id,
            'image' => $image)
        );
    }

    public function setMainDivId($divname) {
        $this->divName = $divname;
        $this->addGridDiv = true;
    }

    public function setContentDivId($divname) {
        $this->divContentName = $divname;
    }

    public function makeContentDiv($make = true) {
        $this->addGridDiv = $make;
    }

    public function ResetTabs() {
        $this->tab = '';
        $this->options = array();
        $this->dropdown = array();
        $this->tabFunction = 'onTabChanged';
        $this->retDiv = true;
        $this->idSelected = '';
        $this->divName = 'myGridTab';
        $this->addGridDiv = false;
        $this->divContentName = 'myGrid';
    }

    public function setTabId($id) {
        $this->divName = $id;
    }
    
    public function makeDiv($make = true) {
        $this->retDiv = $make;
    }

    public function setFunction($func) {
        $this->tabFunction = $func;
    }
    
    public function setTabBelow() {
        $this->tabPosition = 'tabs-below';
    }
    
    public function setTabAbove() {
        $this->tabPosition = 'tabs-above';
    }

    public function setTabLeft() {
        $this->tabPosition = 'tabs-left';
    }

    public function setTabRight() {
        $this->tabPosition = 'tabs-right';
    }

    public function setTabLeftSideways() {
        $this->tabPosition = 'tabs-left tab-sideways';
    }

    public function setTabRightSideways() {
        $this->tabPosition = 'tabs-right  tab-sideways';
    }

    
    public function retTabs($tabname = '') {

        if ($tabname == '') {
            $tabname = 'myGridTab';
        }

        $this->options = $this->CI->cdbhelper->retTranslation($this->options, 'title');

        if (count($this->dropdown) > 0) {
            $this->dropdown = $this->CI->cdbhelper->retTranslation($this->dropdown, 'title');
        }
        // se nao tah marcado, tranca no primeiro
        if ($this->idSelected == '') {
            $this->idSelected = $this->options[0]['id'];
        }

        if ($this->retDiv) {
            $ret = '<div class="tabs-x '.$this->tabPosition.'  tab-bordered cgbTabsMain" id="' . $this->divName . '">';
        } else {
            $ret = '';
        }

        $ret = $ret . '<ul class="nav nav-tabs cgb-nav-tabs"> ';

        // crio as tabs
        foreach ($this->options as $key => $value) {


            $imgaddon = '';
            $selectedAddon = '';

            if ($value['image'] != '') {
                $imgaddon = '<span class=""></span>';
            }

            if ($this->idSelected == $value['id']) {
                $selectedAddon = 'active';
            }

            if ($key == 0 && $this->addGridDiv) { 
                $divname = $this->divContentName;
            } else {
                $divname = $value['id'].'_div';
            }

            $ret = $ret . '<li class="' . $selectedAddon . '" id="' . $value['id'] . '" divId="'.$divname.'" ><a href="x" onclick="'.$this->tabFunction.'(\'' . $value['id'] . '\');return false;" data-toggle="tab" relatedTab="'. $value['id'] .'" style="padding: 5px 15px;font-size: 13px;"   ><strong>' . $imgaddon . $value['title'] . '</strong>  ' . $value['htmlAddon'] . '</a></li>';
        }


        if (count($this->dropdown) > 0) {
            $this->dropdown = $this->CI->cdbhelper->retTranslation($this->dropdown, 'title');
            $ret = $ret . '<li class="dropdown"> <a class="dropdown-toggle" data-toggle="dropdown" href="#">';
            $ret = $ret . '<li class="dropdown"> <a class="dropdown-toggle" data-toggle="dropdown" href="#">Dropdown <span class="caret"></span>';
            $ret = $ret . '<ul class="dropdown-menu">';
        }
        $ret = $ret . '</ul> ';
        
        // crio as areas
        $ret = $ret . '<div class="tab-content">';
        foreach ($this->options as $key => $value) {
            $selectedAddon = '';
            if ($this->idSelected == $value['id']) {
                $selectedAddon = 'active';
            } 
            if ($key == 0 && $this->addGridDiv) { 
                $divname = $this->divContentName;
            } else {
                $divname = $value['id'].'_div';
            }
                
            
            // div de content
            $ret = $ret . '<div class="tab-pane cgbTabsTab '.$selectedAddon.'" id="' . $value['id'] . '_pane" divId="'.$divname.'" >';

            $ret = $ret . '<div id="' . $divname . '" style="overflow-y: auto; overflow-x: hidden;" class="cgbTabsDiv" relatedTab="'. $value['id'] .'"></div>';
            
            $ret = $ret . '</div>';
            
        }
        
        $ret = $ret . '</div>';

        if ($this->retDiv) {
            $ret = $ret . '</div>';
        }

        return $ret;
    }

}
