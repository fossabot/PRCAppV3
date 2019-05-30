<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cjtoolbar
 *
 * @author dvlpserver
 */
// toolbar tooltip

class cjtooltipbar {
    //put your code here
    var $menudclass;
    var $buttonmenuclass;
    var $menuOptions;
    
    public function __construct() {
      $this->menudclass      = array();
      $this->buttonmenuclass = array();
      $this->menuOptions     = array();
        ;
    }
    
    public function setMenuClass ($class, $id=null) {
        if ($id==null) {
            $id = $class;
        }
        $this->menudclass = array($class, $id);
    }
    
    public function setButtonOpenMenuClass ($class, $id=null) {
        if ($id==null) {
            $id = $class;
        }

        $this->buttonmenuclass = array($class, $id);
    }
    
    public function setOption ($classname, $title, $id=null) {
        $length = sizeof( $this->menuOptions);
        array_push($this->menuOptions, array ($classname, $title, $id));
    }
    
    public function returnHtml() {
        $html = '<span id="'.$this->buttonmenuclass[1].'"><em class="'.$this->buttonmenuclass[0].'"> </em> </span>' . "\n";
        $html =  $html . '<div id="'.$this->menudclass[1].'" class="'.$this->menudclass[0].'" style="display: none;">'. "\n";
        foreach ($this->menuOptions as $row) {
            $html =  $html . '<a href="#"><em class="'.$row[0].'"></em></a>'. "\n";            
        }
         $html =  $html . '</div>
          </div>
          ';

        
         return $html;
    }
    
    public function returnJavaScript () {
        $java = "
        function loadJT() {
            $('.".$this->buttonmenuclass[1]."').toolbar({
            content: '#".$this->menudclass[0]."',
            position: 'bottom',
            hideOnClick: true
    });
    };
    
loadJT();
    
";
        
        return $java;
    }
    
    
}
