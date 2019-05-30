<?php include_once APPPATH . 'views/viewIncludeFilter.php'; ?>

<script>
// aqui tem os scripts basicos. 
    var gridName = "gridGeneric";
//var controllerName = "country";


//$(".ds_hr_type").on( "change",  function() {
    dsFormRfqSheetObject = undefined;

    var dsMainObject = new function () {

        // variaveis privadas;

        var thisObj = this;
        thisObj.isFilterVisible = true;
        thisObj.gridName = undefined;

        // funcao de inicio;
        this.start = function (gridNamePar) {
            thisObj.gridName = gridNamePar;

            if (w2ui[thisObj.gridName] !== undefined) {
                w2ui[thisObj.gridName].destroy();
            }

<?php echo ($javascript); ?>


            $('#mainTabsDiv').ctabStart({afterChanged: thisObj.tabAfterChanged, beforeChange: thisObj.tabBeforeChanged});
            $('#tab_detail_div').append($('#detailArea').detach());
            $('#tab_browse_div').append($('#browse_div').detach())


            this.addListeners();
            this.addHelper();

            thisObj.tabDetailRef = $('#tab_detail a');
            thisObj.tabDetailText = thisObj.tabDetailRef.html();

            if (w2ui[thisObj.gridName].records.length > 0) {
                w2ui[thisObj.gridName].ScrollToRow(w2ui[thisObj.gridName].records[0].recid, true);
            }
            
            
            setTimeout(function () {
                w2ui[thisObj.gridName].retrieve({retrFunc: 'retrPRData'});
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


            if (bPressed == 'retrieve') {
                w2ui[thisObj.gridName].retrieve({retrFunc: 'retrPRData'});
            }

            if (bPressed == "delete") {
                w2ui[thisObj.gridName].deleteRow();
            }

            if (bPressed == "update") {
                //w2ui[thisObj.gridName].retrieve({retrFunc: 'retrPRData'});
                w2ui[thisObj.gridName].update({updFunc: 'updateDataJsonIO'});
            }

            if (bPressed == 'downloaddata') {
                var ret = w2ui[thisObj.gridName].getSelection();

                if (ret.length == 0) {
                    return;
                }
                var vc = w2ui[thisObj.gridName].getItem(ret[0], 'cd_rfq');

                window.open('rfq/rfq_pr_group/createFilesAttached/' + vc, '_blank');
            }


            if (bPressed == "edit") {
                var ret = w2ui[thisObj.gridName].getSelection();
                if (ret.length > 0) {
                    var vc = w2ui[thisObj.gridName].getItem(ret[0], 'cd_rfq');
                    thisObj.loadDetails(vc);
                }
            }

            if (bPressed == 'openprinfo') {
                thisObj.openPRData();
            }


            if (bPressed == 'filter') {
                hideFilter();
            }

            if (bPressed == 'InputOutputinsert') {
                var vpk = w2ui[thisObj.gridName].getPk();
                if (vpk == -1) {
                    return;
                }

                w2ui['inputOutputGrid'].insertRow({
                    funcAfter: function (a) {

                        var hasC = w2ui[thisObj.gridName].getChanges().length > 0;

                        w2ui['inputOutputGrid'].setItem(a.recid, 'cd_rfq_pr_group_distribution', vpk);
                        w2ui['inputOutputGrid'].setItem(a.recid, 'cd_human_resource_receiver',  '<?php echo($cd_human_resource_receiver) ?>');
                        w2ui['inputOutputGrid'].setItem(a.recid, 'ds_human_resource_receiver','<?php echo($ds_human_resource_receiver) ?>');
                        w2ui['inputOutputGrid'].setItem(a.recid, 'dt_action', '<?php echo($dt_action) ?>');

                        if (!hasC) {
                            w2ui[thisObj.gridName].mergeChanges();
                        }

                    }
                });

            }

            if (bPressed == "InputOutputdelete") {
                w2ui['inputOutputGrid'].deleteRow({
                    funcAfter: function () {
                        var hasC = w2ui[thisObj.gridName].getChanges().length > 0;
                        thisObj.setChangesOnMainGrid();
                        if (!hasC) {
                            w2ui[thisObj.gridName].mergeChanges();
                        }

                    }
                });
            }

        }

        this.openPRData = function () {
            var ret = w2ui[thisObj.gridName].getSelection();
            if (ret.length > 0) {
                var vc = w2ui[thisObj.gridName].getItem(ret[0], 'cd_rfq');
                var title = 'PR';
                openFormUiBootstrap(
                        title,
                        'rfq/rfq_pr_group/openForm/' + vc,
                        'col-lg-12 col-lg-offset-0 col-sm-12 col-sm-offset-0'
                        );

            }



        }

        // adicao de listeners!
        this.addListeners = function () {
            var vfirst = false;
            /*
             $(window).on('rfqChanged', function (a, b) {
             $.each(b.rs, function (i, v) {
             var vd = w2ui[thisObj.gridName].get(v.recid);
             if (vd == null) {
             w2ui[thisObj.gridName].add([v], true);
             vfirst = true;
             } else {
             w2ui[thisObj.gridName].set(v.recid, v);
             }
             });
             
             if (vfirst) {
             w2ui[thisObj.gridName].ScrollToRow(w2ui[thisObj.gridName].records[0].recid, true);
             w2ui[thisObj.gridName].select(w2ui[thisObj.gridName].records[0].recid);
             
             }
             
             });
             */

            w2ui[thisObj.gridName].on('dblClick', function (event) {
                //thisObj.ToolBarClick('edit');
                $('#tab_detail a').click();


            });

            w2ui[thisObj.gridName].on('update', function (event) {

                event.onComplete = function(e) {
                    // get the selected primary key
                    var vpk = w2ui[thisObj.gridName].getPk();
                    if (vpk == -1) {
                        return;
                    }

                    // get the new inputouput data from backend, that comes from db
                    var vx = chkUndefined(w2ui[thisObj.gridName].getItem(vpk, 'inputoutput'), []);
                    if (!$.isArray(vx)) {
                        vx = JSON.parse(vx);
                    }

                    // clear the input/output grid
                    w2ui['inputOutputGrid'].clear();
                    // add the new data.
                    w2ui['inputOutputGrid'].add(vx);

                }


            });




            w2ui['inputOutputGrid'].on('gridChanged', function (a) {
                thisObj.setChangesOnMainGrid();
            });


            w2ui[thisObj.gridName].on('rowFocusChanging', function (a) {

                a.onComplete = function (b) {
                    w2ui['inputOutputGrid'].clear();


                    if (b.recid_new == -1) {
                        return;
                    }

                    var vx = chkUndefined(w2ui[thisObj.gridName].getItem(b.recid_new, 'inputoutput'), []);
                    if (!$.isArray(vx)) {
                        vx = JSON.parse(vx);
                    }

                    w2ui['inputOutputGrid'].add(vx);


                }
            });
            // w2ui[thisObj.gridName].on('rowFocusChanging', function (e) {
            //     e.onComplete = function (ev) {
            //         var vc = -1;
            //         if (ev.recid_new != -1) {
            //             vc = w2ui[thisObj.gridName].getItem(ev.recid_new, 'cd_rfq');
            //         }
            //         thisObj.setTabDetailData(vc);
            //     }
            // });



        }

        this.setTabDetailData = function (vdata) {
            var vtext = thisObj.tabDetailText;
            if (vdata != -1) {
                vtext = vtext + '<strong> - ' + vdata + '</strong>';
            }
            $(thisObj.tabDetailRef).html(vtext);

        }


        // roda antes de fechar (se retornar FALSE o sistema vai perguntar se quer fechar
        this.beforeClose = function () {
            return w2ui[thisObj.gridName].getChanges().length == 0;
        }


        // close object (lugar para destruir as coisas//
        this.close = function () {
            w2ui[thisObj.gridName].destroy();
            $(window).off('rfqChanged');

            if (dsFormRfqSheetObject != undefined) {
                dsFormRfqSheetObject.remove(true);
                dsFormRfqSheetObject = undefined;
            }

            introRemove();
            return true;
        }


        this.tabBeforeChanged = function (id) {
            if (id == 'tab_detail') {
                if (w2ui[thisObj.gridName].getSelection().length == 0) {
                    return false;
                }
            }

            return true;
        }

        this.tabAfterChanged = function (id) {
            if (id == 'tab_detail') {

                thisObj.isFilterVisible = isFilterVisible();
                if (thisObj.isFilterVisible) {
                    hideFilter(true);
                }

                var rrec = w2ui[thisObj.gridName].getSelection()[0];
                var vc = w2ui[thisObj.gridName].getItem(rrec, 'cd_rfq');
                thisObj.loadDetails(vc);
            }

            if (id == 'tab_browse') {
                dsFormRfqSheetObject.remove(true);
                $('#detailArea').empty();
                dsFormRfqSheetObject = undefined;

                if (thisObj.isFilterVisible && !isFilterVisible()) {
                    hideFilter(true);
                }

                thisObj.setTabDetailData(w2ui[thisObj.gridName].getItem(w2ui[thisObj.gridName].getPk(), 'cd_rfq'));

            }



            setGrpGridHeight();

        }

        thisObj.loadDetails = function (cd_rfq, reload) {
            $.myCgbAjax({url: 'rfq/rfq/callRfqSheetForm/' + cd_rfq,
                message: javaMessages.retrieveData,
                success: function (data) {

                    if (reload) {
                        dsFormRfqSheetObject.remove(false);
                        $('#detailArea').empty();
                        dsFormRfqSheetObject = undefined;
                    }


                    $('#detailArea').append(data.html);


                    if (cd_rfq == -1) {
                        //w2ui[thisObj.gridName].add(data.data, true);
                        //w2ui[thisObj.gridName].ScrollToRow(data.data[0].recid);
                        $('#mainTabsDiv').ctabSelect('tab_detail');
                        thisObj.setTabDetailData('NEW');
                        thisObj.isFilterVisible = isFilterVisible();
                        if (thisObj.isFilterVisible) {
                            hideFilter(true);
                        }

                    }

                    //$('#testScrollArea').cgbMakeScrollbar('scrollToY', $('#testArea_' + data.pk).position().top);
                    //thisObj.Form.addNewElements();
                }});

        }

        // funcaoes gerais
        //一般功能

        this.setChangesOnMainGrid = function () {
            var vpk = w2ui[thisObj.gridName].getPk();
            if (vpk == -1) {
                return;
            }


            w2ui['inputOutputGrid'].mergeChanges();
            var vx = w2ui['inputOutputGrid'].getResultsetJson();
            w2ui[thisObj.gridName].setItem(vpk, 'inputoutput', vx);

        }
    }



// funcoes iniciais;
    dsMainObject.start(gridName);

// funcao da toolbar
    function onGridToolbarPressed(bPressed, dData) {
        dsMainObject.ToolBarClick(bPressed, dData);
    }


    makeFilterWithEnter(function () {
        dsMainObject.ToolBarClick('retrieve', undefined);
    });

// insiro colunas;
    function setGrpGridHeight() {
        var hAvail = getWorkArea();
        // $("#tab_browse_div").css("height", hAvail - 50);
        $("#PRGridDiv").css("height", hAvail*0.5 - 20);
        $("#inputOutputGridDiv").css("height", hAvail*0.5 - 20);
        w2ui[gridName].resize();
        w2ui['inputOutputGrid'].resize();

        if (dsFormRfqSheetObject != undefined) {
            dsFormRfqSheetObject.resize();
        }
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

<div id="detailArea" class="col-md-12" style="background-color: white; padding-left: 0px;padding-right: 5px;"></div>

<div class="row" id='allArea'>
    <?php echo ($tab); ?>
</div>



<div id="browse_div" style="max-height: calc(100vh - 40px);" class="" >
    <div class="row">
        <div class="col-md-12"> <div style="height: 50%;width: 100%" id="PRGridDiv"> </div></div>
        <div class="col-md-12"> <div style="height: 50%;width: 100%" id="inputOutputGridDiv"> </div></div>
    </div>
</div>






