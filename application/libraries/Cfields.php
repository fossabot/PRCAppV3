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
class Cfields {
    //put your code here
    
    //const tSTRING_UPPER  = 1;
    // changed to everything be any case. Later change the syntax.
    const tSTRING_UPPER  = 9;
    const tNUM           = 2;
    const tDATE          = 3;
    const tCBOX          = 4;
    const tKey           = 5;
    const tDeactivated   = 6;
    const tHTML          = 7;
    const tSTRING_LOWER  = 8;
    const tSTRING_ANY    = 9;
    const tPICKLIST      = 10;
    const tINTEGER        = 11;
    const tFLOAT         = 12;
    const tTEXTPL        = 13;
    const tPERCENTUAL    = 14;
    const tIMAGE         = 15;
    const tIMAGESKU      = 16;
    const tPROGRESSBAR   = 17;
    const tPROGRESSDONUT = 18;
    const tCOLOR         = 19;
    const tCOLUMNDIVIDER = 20;
    const tFIRST_PICTURE = 21;
    const tDOCFILE = 22;
    
    public function retTypeDocFileToolBar() {
        return self::tDOCFILE;
    }
    
    public function retTypeColumnDivider() {
        return self::tCOLUMNDIVIDER;
    }
    
    public function retTypeStringUpper() {
        return self::tSTRING_UPPER;
    }
    
    public function retTypeColor() {
        return self::tCOLOR;
        
    }
    
    public function retTypeProgressBar () {
        return self::tPROGRESSBAR; 
    }
    
    public function retTypeProgressDonut () {
        return self::tPROGRESSDONUT; 
    }
    
    
    public function retTypeImageSpec() {
        return self::tIMAGE; 
    }

    public function retTypeImageSku() {
        return self::tIMAGESKU; 
    }
    
    public function retTypeStringLower() {
        return self::tSTRING_LOWER;
    }

    public function retTypeStringAny() {
        return self::tSTRING_ANY;
    }
    
    
    public function retTypeNum() {
        return self::tNUM;
    }

    public function retTypeDate() {
        return self::tDATE;
    }

    public function retTypeCheckBox() {
        return self::tCBOX;
    }

    public function retTypeKey() {
        return self::tKey;
    }
    public function retTypeDeactivated() {
        return self::tDeactivated;
    }
        
    public function retTypeHTML () {
        return self::tHTML;
    }
 
    public function retTypePickList() {
       return self::tPICKLIST;
    }
    
    public function retTypeInteger() {
       return self::tINTEGER;
    }
    
    public function retTypeFloat() {
       return self::tFLOAT;
    }
    
    public function retTypeTextPL() {
       return self::tTEXTPL;
    }
    
    public function retTypePercentual() {
       return self::tPERCENTUAL;
    }
    public function retTypeFirstPicture() {
       return self::tFIRST_PICTURE;
    }

    
}
