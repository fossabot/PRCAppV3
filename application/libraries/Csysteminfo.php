<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cFields
 *
 * @author dvlpserver
 */
class csysteminfo {
    //put your code here
    
    //
    // changed to everything be any case. Later change the syntax.
    // format: a.b.c.d
    // a = New Server/Technology/Framework big changes
    // b = Major System Release / New Module
    // c = Incremental number.
    // d = Quantity of Jira tasks being released.
    
    const tSystemVersion  = '2.0.9.4';
    const tSystemName     = 'Lab Management System';
    
    
    public function retSystemVersion() {
        return self::tSystemVersion;
    }
    
    public function retSystemName() {
        return self::tSystemName;
;
    }
    
    public function retSystemNameAndVersion() {
        return self::tSystemName . ' ' . self::tSystemVersion;
;
    }

    
}
