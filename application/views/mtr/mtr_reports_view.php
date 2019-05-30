<?php include_once APPPATH . 'views/viewIncludeFilter.php'; ?>

    <script>
        // aqui tem os scripts basicos.
        var gridName = "gridGeneric";
        var lastSelectProjNo="";



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

                $('#mainTabsDiv').ctabStart({afterChanged: thisObj.tabAfterChanged, beforeChange: thisObj.tabBeforeChanged});
                //$('#tab_detail_div').append($('#detailArea').detach())

                this.addListeners();
                this.addHelper();

                setTimeout(function () {
                    w2ui[thisObj.gridName].retrieve();
                }, 0);

            }

            this.tabAfterChanged = function( newtab, oldtab) {
                setGrpGridHeight();
                if (newtab == 'tab_detail') {
                    var selection = w2ui[thisObj.gridName].getSelection();


                    if (selection.length === 0) {
                        return;
                    }

                    var rec = w2ui[thisObj.gridName].get(selection[0]);
                    var id = rec.recid;


                    // var ttiPrjnumber =  'x' + w2ui[thisObj.gridName].getItem(id, 'ProjectNumber').toString();
                    // var BrandProjectNum ='x' + w2ui[thisObj.gridName].getItem(id, 'BrandProjectNum').toString();
                    // var BrandModelNum = 'x' +w2ui[thisObj.gridName].getItem(id, 'BrandModelNum').toString();
                    // var TTIModelNumber ='x' + w2ui[thisObj.gridName].getItem(id, 'TTIModelNumber').toString();
                    // TTIModelNumber=TTIModelNumber.replace(/\(/g, '%28').replace(/\)/g, '%29');
                    var rowID=w2ui[thisObj.gridName].getItem(id, 'recid');
                    console.log(rowID);

                    w2ui['detailgrid'].retrieve({retrFunc: 'retrReports/' + rowID});
                }
            }

            this.tabBeforeChanged = function( newtab, oldtab) {
                return true;
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

            }



            // adicao de listeners!
            this.addListeners = function () {
                w2ui['detailgrid'].on('dblClick', function(event) {

                    var vpath =w2ui['detailgrid'].getItem(event.recid, 'path');
                    var toSend = [];
                    toSend['path'] = vpath;
                    openAlternate ('POST', 'mtr/mtr_reports/openFile'  , toSend, '_blank');

                });

                w2ui[thisObj.gridName].on('dblClick', function(event) {

                    $('#tab_detail a').click();


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


        // onTabChanged('tab_detail_div');
        function setGrpGridHeight() {
            var hAvail = getWorkArea();
            $("#tab_browse_div").css("height", hAvail - 50);
            $("#tab_detail_div").css("height", hAvail - 50);

            w2ui[gridName].resize();
            w2ui['detailgrid'].resize();
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

    </script>
    <div class="row">
        <?php echo ($tab); ?>
    </div>
<?php //include_once APPPATH . 'views/includeViewResizeDiv.php'; ?>


