
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

            if (bPressed == 'edit') {
                thisObj.openRelationHM();
            }

        }


        // Function to add listeners (events). Here is empty but it is part of the basic object structure
        this.addListeners = function () {

            w2ui[thisObj.gridName].on('retrieveOnAdd', function (event) {
                event.onComplete = function () {
                    var vData = w2ui[thisObj.gridName].getSelection();

                    if (vData.length > 0) {
                        thisObj.setData(vData[0]);
                    }
                }
            });


            w2ui[thisObj.gridName].on('rowFocusChanging', function (event) {
                w2ui['gridHM'].clear();

                event.onComplete = function (e) {

                    if (e.recid_new === -1) {
                        return;
                    }

                    //console.log('changing', e.recid_new);
                    thisObj.setData(e.recid_new);
                }

            });

        }

        this.setData = function (recid) {
            w2ui['gridHM'].clear();
            thisObj.setDataOnGrid('gridHM', recid, 'hmdata');
        }

        this.setDataOnGrid = function (grname, recid, jsonCol) {
            var vjson = w2ui[thisObj.gridName].getItem(recid, jsonCol);

            if (vjson == undefined) {
                vjson = [];
            }

            w2ui[grname].add(vjson);
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
        this.openRelationHM = function () {

            var relController = '<?php echo ($this->encodeModel('human_resource_model/retGridJsonByPrjTypeGroup')); ?>';
            var relUpdSBSModel = '<?php echo ($this->encodeModel('human_resource_model/updSBSRelPrjTypeGroup')) ?>';
            var title = 'Select';


            var idsel = w2ui[gridName].getSelection();

            if (idsel.length == 0) {
                return;
            }

            if (w2ui[gridName].isNewRow(idsel[0])) {
                messageBoxError(javaMessages.saveFirst);
                return;
            }

            basicSelectSBS(title, relController, relUpdSBSModel, idsel[0]);

        }



    }

    function posSBSClosed(hasChanges) {
        if (hasChanges) {
            var vischanged =  w2ui[gridName].getChanges().length > 0;
            var vpk = w2ui[gridName].getPk();
            if (vpk == -1) {
                return;
            }
            w2ui['gridHM'].clear();
            w2ui['gridHM'].add(varSBSLastSaved);
            
            var vx = w2ui['gridHM'].getResultsetJson();
            w2ui[gridName].setItem(vpk, 'hmdata', vx);            

            if (!vischanged) {
                w2ui[gridName].mergeChanges();
            }
            
        }
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
        $("#myGrid").css("height", hAvail);
        $("#myGridHM_div").css("height", hAvail);
        w2ui[gridName].resize();
        w2ui['gridHM'].resize();

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
<div class='row'>
    <div style="height: auto;" class='col-md-8 no-padding'> <div id='myGrid' style='width: 100%' >  </div> </div>
    <div style="height: auto;" class='col-md-4 no-padding'> <div id='myGridHM_div' style='width: 100%' > </div>
    </div>
