<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ctoolbar
 *
 * @author dvlpserver
 */
class ctoolbar {
    //put your code here
    var $cButtonsIcons,
        $cButtonsTitle,
        $toolbarJItems,  
        $toolbarjScrFull;
    
    public function __construct() {
        
        $this->cButtonsIcons = array( "insert"    => "fa fa-edit" ,
                                       "update"   => "fa fa-floppy-o",
                                       "retrieve" => "fa fa-external-link",
                                       "delete"   => "fa fa-trash-o"
                                     );

        $this->cButtonsTitle = array( "insert"   => "Insert Row" ,
                                       "update"   => "Update Information",
                                       "retrieve" => "Retrieve Information",
                                       "delete"   => "Delete"
                                    );

        $this->toolbarJItems= "";
        $this->toolbarjScrFull = "    $(function () {
	$('#gridtoolbar').w2toolbar({
		name: 'gridtoolbar',
		items: [ <items>
		],
		onClick: function (event) {
			execToolbar(event.target);
		}
	});
});
";
    }
    
    
    
    public function addBaseToolbar ($toobarInfo) {
        foreach($toobarInfo as $line) {
        $this->makeToolbar($line, $this->cButtonsTitle[$line], $this->cButtonsIcons[$line], $line);    
        }
    }
    
    
    public function makeToolbar($id, $title, $icon, $param) {
                
        $this->toolbarJItems = $this->toolbarJItems ."\n". "{ type: 'button',  id: '".$id."',  caption: '', icon: '".$icon."', hint: '".$title."' },";
    }
    
    
    public function getToolbarScript() {
        

                $return = '';


        $this->toolbarJItems =  substr($this->toolbarJItems, 0, -1);
 
        $java = str_replace("<items>", $this->toolbarJItems, $this->toolbarjScrFull);
        
        $return = $return. " <script>  
                             ".$java.
                      '$("#gridtoolbar").sticky({ topSpacing: 50, center:true });'
                . "          </script> ";
        
        
        return $return;
    }
        
}
