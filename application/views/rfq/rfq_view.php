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

            <?php echo($javascript); ?>


            $('#mainTabsDiv').ctabStart({
                afterChanged: thisObj.tabAfterChanged,
                beforeChange: thisObj.tabBeforeChanged
            });
            $('#tab_detail_div').append($('#detailArea').detach())

            this.addListeners();
            this.addHelper();

            thisObj.tabDetailRef = $('#tab_detail a');
            thisObj.tabDetailText = thisObj.tabDetailRef.html();

            if (w2ui[thisObj.gridName].records.length > 0) {
                w2ui[thisObj.gridName].ScrollToRow(w2ui[thisObj.gridName].records[0].recid, true);
            }


            <?php if ($openFirst == 'Y') { ?>
            setTimeout(function () {
                $('#tab_detail a').click();
            }, 0);
            <?php } ?>




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
                thisObj.loadDetails(-1);
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

            if (bPressed == "duplicate") {
                var ret = w2ui[thisObj.gridName].getSelection();

                if (ret.length == 0) {
                    return;
                }

                messageBoxYesNo('<?php echo($duplicateconf)?>', function () {
                    thisObj.duplicateRFQ(ret[0]);
                });


            }

            if (bPressed == 'excel') {
                var ret = w2ui[thisObj.gridName].getSelection();

                if (ret.length == 0) {
                    return;
                }

                window.open('rfq/rfq/makeExcel/' + ret[0], '_blank');
            }


            if (bPressed == 'downloaddata') {
                var ret = w2ui[thisObj.gridName].getSelection();

                if (ret.length == 0) {
                    return;
                }

                window.open('rfq/rfq/createFilesAttached/' + ret[0], '_blank');
            }


            if (bPressed == "edit") {
                var ret = w2ui[thisObj.gridName].getSelection();
                if (ret.length > 0) {
                    thisObj.loadDetails(ret[0]);
                }
            }
            if (bPressed == 'filter') {
                hideFilter();
            }

        }


        // adicao de listeners!
        this.addListeners = function () {
            var vfirst = false;
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

            w2ui[thisObj.gridName].on('dblClick', function (event) {
                //thisObj.ToolBarClick('edit');
                $('#tab_detail a').click();


            });

            w2ui[thisObj.gridName].on('rowFocusChanging', function (e) {
                e.onComplete = function (ev) {
                    thisObj.setTabDetailData(ev.recid_new);
                }
            });


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
                thisObj.loadDetails(rrec);
            }

            if (id == 'tab_browse') {
                dsFormRfqSheetObject.remove(true);
                $('#detailArea').empty();
                dsFormRfqSheetObject = undefined;

                if (thisObj.isFilterVisible && !isFilterVisible()) {
                    hideFilter(true);
                }

                thisObj.setTabDetailData(w2ui[thisObj.gridName].getPk());

            }


            setGrpGridHeight();

        }

        thisObj.loadDetails = function (cd_rfq, reload) {
            $.myCgbAjax({
                url: 'rfq/rfq/callRfqSheetForm/' + cd_rfq,
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
                }
            });
        }

        this.duplicateRFQ = function (cdrfq) {

            $.myCgbAjax({
                url: 'rfq/rfq/duplicateReq/' + cdrfq,
                success: function (data) {
                    w2ui[thisObj.gridName].add(data.rs, true);
                    w2ui[thisObj.gridName].ScrollToRow(data.rs[0].recid, true);

                }
            });
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
        $("#tab_browse_div").css("height", hAvail - 50);

        //$("#detailArea").css("height", hAvail - 50);
        w2ui[gridName].resize();

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
    <?php echo($tab); ?>
</div>








