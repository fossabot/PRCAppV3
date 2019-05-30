<script>
   
function setGrpGridHeight() {
   var hAvail = getWorkArea();
   $("#myGrid").css("height", hAvail - 30 );
   w2ui[gridName].resize();

}

// funcao chamada quando o filtro some. tem que existir se existir filtro!
function onFilterHidden() {
   setGrpGridHeight();
}

$(window).on ('resize.mainResize', function () {
   setGrpGridHeight();
});

$("body").on('togglePushMenu toggleFilter', function () {
   setGrpGridHeight();
});
setGrpGridHeight();

// insiro colunas;

</script>
<div class="row"> <div id='myGridMenu' class="toolbarStyleXX"> </div></div>
<div id="myGrid" style="height: auto;" class='row'> </div>

