<?php include_once APPPATH . 'views/viewIncludeFilter.php'; ?>

<script>
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

            if (w2ui[thisObj.gridName] !== undefined) {
                w2ui[thisObj.gridName].destroy();
            }

<?php echo ($javascript); ?>


            this.addListeners();
            this.addHelper();

            setTimeout(function () {
                w2ui[thisObj.gridName].retrieve();
            }, 0);

        }

        this.addHelper = function () {
            var arrayHelper = [];
            $.merge(arrayHelper, introAddFilterArea());
            $.merge(arrayHelper, w2ui[thisObj.gridName].toolbar.getIntroHelp());
            $.merge(arrayHelper, w2ui[thisObj.gridName].getIntroHelp());

            introAddNew({steps: arrayHelper});
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

            if (bPressed == 'costdepinsert') {
                var vpk = w2ui[thisObj.gridName].getPk();
                if (vpk == -1) {
                    return;
                }

                w2ui['gridCostCenter'].insertRow({
                    funcAfter: function (a) {

                        var hasC = w2ui[thisObj.gridName].getChanges().length > 0;

                        w2ui['gridCostCenter'].setItem(a.recid, 'cd_department_account_code', vpk);

                        if (!hasC) {
                            w2ui[thisObj.gridName].mergeChanges();

                        }

                    }
                });

            }

            if (bPressed == "costdepdelete") {
                var hasC = w2ui[thisObj.gridName].getChanges().length > 0;
                console.log('haschanges', hasC);
                w2ui['gridCostCenter'].deleteRow({
                    funcAfter: function () {
                        
                        thisObj.setChangesOnMainGrid();
                        if (!hasC) {
                            w2ui[thisObj.gridName].mergeChanges();
                            console.log('merging');
                        }

                    }
                });
            }

        }


        // adicao de listeners!
        this.addListeners = function () {

            w2ui['gridCostCenter'].on('gridChanged', function (a) {
                // when running delete don't neeed to merge because it was done already on the script for delete.
                if (a.action != 'delete') {
                    thisObj.setChangesOnMainGrid();
                    console.log('mergin on gridchangede');
                }
            });


            w2ui[thisObj.gridName].on('rowFocusChanging', function (a) {
                console.log('RowFocusChanging');
                a.onComplete = function (b) {
                    w2ui['gridCostCenter'].clear();
                    if (b.recid_new == -1) {
                        return;
                    }

                    var vx = chkUndefined(w2ui[thisObj.gridName].getItem(b.recid_new, 'ds_department_cost_json'), []);
                    if (!Array.isArray(vx)) {
                        vx = JSON.parse(vx);
                    }

                    w2ui['gridCostCenter'].add(vx);

                }
            });
        }

        // roda antes de fechar (se retornar FALSE o sistema vai perguntar se quer fechar
        this.beforeClose = function () {
            return w2ui[thisObj.gridName].getChanges().length == 0;
        }


        // close object (lugar para destruir as coisas//
        this.close = function () {
            w2ui[thisObj.gridName].destroy();
            introRemove();
            return true;
        }




        // funcaoes gerais 
        this.setChangesOnMainGrid = function () {
            console.log('settingchanges ongrid');
            var vpk = w2ui[thisObj.gridName].getPk();
            if (vpk == -1) {
                return;
            }

            w2ui['gridCostCenter'].mergeChanges();
            var vx = w2ui['gridCostCenter'].getResultsetJson();
            w2ui[thisObj.gridName].setItem(vpk, 'ds_department_cost_json', vx);

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
        $("#gridCostCenterDiv").css("height", hAvail);
        w2ui[gridName].resize();
        w2ui['gridCostCenter'].resize();

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
<div class="row">
    <div class="col-md-9 no-padding">
        <div id="myGrid" style="height: auto; width:100%" > </div>
    </div>
    <div class="col-md-3 no-padding">
        <div id="gridCostCenterDiv" style="height: auto; width:100%" > </div>
    </div>
</div>



