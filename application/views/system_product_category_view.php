<?php include_once APPPATH . 'views/viewIncludeFilter.php';?>

<script>
// aqui tem os scripts basicos. 
var gridName = "gridGeneric";
//var controllerName = "country";

           

//$(".ds_hr_type").on( "change",  function() {


var dsMainObject = new function () {
    
    // variaveis privadas;
    
    var thisObj = this;
    //thisObj.gridName = undefined;
    
    // funcao de inicio;
    this.start = function () {
                
        this.addListeners();
        this.addHelper();
                
    }

    this.addHelper = function() {
        //var arrayHelper = [];
        //$.merge(arrayHelper, introAddFilterArea());
        //$.merge(arrayHelper,w2ui[thisObj.gridName].toolbar.getIntroHelp());
        //$.merge(arrayHelper, w2ui[thisObj.gridName].getIntroHelp());
        
        //introAddNew({steps: arrayHelper});
    }

    // funcao de toolbar;
    this.ToolBarClick = function (bPressed, dData) {

    }


    // adicao de listeners!
    this.addListeners = function() {
        
    }

    // Runs before close (by other option on menu). if return false stop closing
    this.beforeClose = function() {
        return true
    }


    // Run when the close happens (used to clean up objects)
    this.close = function() {
        return true;
    }


    

    // funcaoes gerais 

}

// funcoes iniciais;
dsMainObject.start();

// Adjust height according to the Available space
function setGrpGridHeight() {
   var hAvail = getWorkArea();
   $("#myTiles").css("height", hAvail );
   //w2ui[gridName].resize();

}

// funcao chamada quando o filtro some. tem que existir se existir filtro!
function onFilterHidden() {
   setGrpGridHeight();
}

// resizes event.
$(window).on ('resize.mainResize', function () {
   setGrpGridHeight();
});

// menu hidden/show event
$("body").on('togglePushMenu toggleFilter', function () {
   setGrpGridHeight();
});

setGrpGridHeight();

// insiro colunas;

</script>


<div id="myTiles" style="height: auto;" class='row'> 
<?php echo($htmlTiles);?>
</div>