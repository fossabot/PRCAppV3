<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cjmaker
 *
 * @author dvlpserver
 */
class cjmaker {
    //put your code here
    
    // funcao que retorna o javascript responsavel pelo controle de status de linha
    public function jGridCheckStatus($fieldname) {
    $jscript = '    
    $("body").on( "change",".'.$fieldname.'",  function() {'."\n".'
    var index = $(".'.$fieldname.'").index(this);'."\n".'
    var obj   = $(".'.$fieldname.'").eq(index); '."\n".'
    var stat  = $(".row_status").eq(index);'."\n".'
    obj.val( obj.val().toUpperCase() );'."\n".'
    //alert("teste");
    if (stat.val() == "N") {'."\n".'
        stat.val("M");'."\n".'
        }'."\n".'
    '."\n".'
        if (stat.val() == "I") {'."\n".'
            stat.val("A");'."\n".'
            }'."\n".'
    '."\n".'
    });';
        
    return $jscript;    
        
    }

    
    
    
    }

