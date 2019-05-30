<table width="95%" height="100%" border="0" id="childtable">
  <tr>
      
    <td width="95%"><div class="showfilter"><script> <?php echo $filters_java ?> </script> <?php echo $filters ?>
    </div>
    </td>

  </tr>
    
  
  
</table>

<script>
// aqui tem os scripts basicos. 
var gridName = "gridHM";
var controllerName = "type_users_maint";

if ( w2ui[gridName] != undefined ) {
    w2ui[gridName].destroy();
}
           

// parte onde o controller vai jogar o javascrip do grid e etc...
<?php echo ($javascript); ?>


// funcao da toolbar
function onGridToolbarPressed(bPressed, dData) {
    if (bPressed == 'insert') {
      w2ui[gridName].insertRow();
    }
    if (bPressed == 'retrieve') {
      w2ui[gridName].retrieve();
    }
    if (bPressed == "update") {
      w2ui[gridName].update();
    }
    
    if (bPressed == "delete") {
      w2ui[gridName].deleteRow();
    }
    if (bPressed == 'filter') {
        hideFilter();
    }
}
// calculo da area livre. TEM QUE EXISTIR
function getAvailHeight(){
    var hAvail = $( window ).height() - $( "#maintable" ).height() - $( "#childtable" ).height();
    return hAvail;
}

// funcao chamada quando o filtro some. tem que existir se existir filtro!
function onFilterHidden (){ 
    setGridHeight();
    }

$(window).on ('resize.mainResize', function () {
    setGridHeight();
});

// ajusta o tamanho do detail.
setGridHeight();

// retrieve automatico na entrada!
w2ui[gridName].retrieve();

</script>
<div id="myGrid" style="height: 500px"> </div>

