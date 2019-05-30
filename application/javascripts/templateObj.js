/*


// aqui tem os scripts basicos. 
var gridName = "gridGeneric";
//var controllerName = "country";

//$(".ds_hr_type").on( "change",  function() {


var dsMainObject = new function () {
    
    // variaveis privadas;
    
    var thisObj = this;
    thisObj.gridName = undefined;
    
    // funcao de inicio;
    this.start = function (gridNamePar) {
        thisObj.gridName = gridNamePar;
        
        if ( w2ui[thisObj.gridName] !== undefined ) {
            w2ui[thisObj.gridName].destroy();
        }
        
        <?php echo ($javascript); ?>
                
                
        this.addListeners();
                
    }

    // funcao de toolbar;
    this.ToolBarClick = function (bPressed, dData) {

        if (bPressed == 'insert') {
            w2ui[thisObj.gridName].insertRow();
        }
        if (bPressed == 'retrieve') {
            w2ui[thisObj.gridName].retrieve();
        }
        if (bPressed == "update") {
            w2ui[thisObj.gridName].update();
        }

        if (bPressed == "delete") {
            w2ui[thisObj.gridName].deleteRow();
        }
        if (bPressed == 'filter') {
            hideFilter();
        }

    }


    // adicao de listeners!
    this.addListeners = function() {
        
    };

    // roda antes de fechar (se retornar FALSE o sistema vai perguntar se quer fechar
    this.beforeClose = function() {
        return true;
    };


    // close object (lugar para destruir as coisas//
    this.close = function() {
        w2ui[thisObj.gridName].destroy();
        return true;
    };


    // funcaoes gerais 

};

// funcoes iniciais;
dsMainObject.start(gridName);

// funcao da toolbar
function onGridToolbarPressed(bPressed, dData) {
    dsMainObject.ToolBarClick(bPressed, dData);
}
*/