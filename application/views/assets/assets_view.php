
<?php
// PHP page that has the filter area.
include_once APPPATH . 'views/viewIncludeFilter.php';
?>

<script>
// aqui tem os scripts basicos. 
    var gridName = "gridGeneric";

    var dsMainObject = new function () {

        // Private Variables;
        var thisObj = this;
        thisObj.gridName = undefined;

        // The starting function
        this.start = function (gridNamePar) {
            thisObj.gridName = gridNamePar;

            if (w2ui[thisObj.gridName] !== undefined) {
                w2ui[thisObj.gridName].destroy();
            }

// javascript received from controller with the grid.
<?php echo ($javascript); ?>


            this.addListeners();
            this.addHelper();

            setTimeout(function () {
                w2ui[thisObj.gridName].retrieve();
            }, 0);

        }

        // add helper on the question mark button. 
        this.addHelper = function () {
            var arrayHelper = [];
            $.merge(arrayHelper, introAddFilterArea());
            $.merge(arrayHelper, w2ui[thisObj.gridName].toolbar.getIntroHelp());
            $.merge(arrayHelper, w2ui[thisObj.gridName].getIntroHelp());

            introAddNew({steps: arrayHelper});
        }

        // Toolbar functions
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


        // Function to add listeners (events). Here is empty but it is part of the basic object structure
        this.addListeners = function () {
            w2ui[thisObj.gridName].on("rowFocusChanging", function (target, event) {
                event.onComplete = function (a) {
                    w2ui['gridChanges'].clear();
                    if (a.recid_new != -1) {
                        var vx = chkUndefined(w2ui[thisObj.gridName].getItem(a.recid_new, 'ds_history_json'), '[]');
                        w2ui['gridChanges'].add(  JSON.parse(vx)  );
                    }
                    
                }
            });
        }

        // It runs before close the screen (by choosing another option on the menu, for example). If you return false the system will not leave the screen
        this.beforeClose = function () {
            return w2ui[thisObj.gridName].getChanges().length == 0;
        }


        // Event that will be triggered when the object is being closed. Location to remove listeners, destroy grids, etc....
        this.close = function () {
            w2ui[thisObj.gridName].destroy();
            introRemove();
            return true;
        }

        // Place to add general functions

    }

// funcoes iniciais;
    dsMainObject.start(gridName);

// funcao da toolbar
    function onGridToolbarPressed(bPressed, dData) {
        dsMainObject.ToolBarClick(bPressed, dData);
    }

    makeFilterWithEnter(function () {
        dsMainObject.ToolBarClick('retrieve');
    });

    function setGrpGridHeight() {
        var hAvail = getWorkArea();
        $("#myGrid").css("height", Math.round(hAvail * 0.6));
        $("#gridDiv").css("height", Math.round(hAvail * 0.4));
        w2ui[gridName].resize();
        w2ui['gridChanges'].resize();

    }

// funcao chamada quando o filtro some. tem que existir se existir filtro!
    function onFilterHidden() {
        setGrpGridHeight();
    }

    $(window).on('resize.mainResize', function () {
        setGrpGridHeight();
    });

    $("body").on('togglePushMenu toggleFilter', function () {
        setGrpGridHeight();
    });
    setGrpGridHeight();

// insiro colunas;

</script>
<div id="myGrid" style="height: auto;" class='row'> </div>
<div id="gridDiv" style="height: auto;" class='row'> 
</div>




