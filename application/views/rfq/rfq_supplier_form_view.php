<?php ?>

<script>
// aqui tem os scripts basicos. 
//var controllerName = "country";

//$(".ds_hr_type").on( "change",  function() {


    var dsRFQSupObject = new function () {

        // variaveis privadas;

        var thisObj = this;
        thisObj.gridName = undefined;
        thisObj.lastCode = -20;
        thisObj.cd_rfq = <?php echo($cd_rfq) ?>;
        thisObj.cd_currency = <?php echo($cd_currency) ?>;
        thisObj.ds_currency = '<?php echo($ds_currency) ?>';
        thisObj.openPK = -1;
        thisObj.cd_payment_term = <?php echo($cd_payment_term) ?>;
        thisObj.ds_payment_term = '<?php echo($ds_payment_term) ?>';

        this.start = function () {

<?php
echo($SupModelGrid);
?>


            this.setScreenPermissions();

            this.addListeners();
            this.addHelper();

            this.resizeGrid();
            //$('#mainSupTabsDiv').ctabStart({afterChanged: thisObj.tabSupAfterChanged});//({afterChanged: thisObj.tabAfterChanged, beforeChange: thisObj.tabBeforeChanged});

            if (w2ui['supplierGrid'].records.length > 0) {
                w2ui['supplierGrid'].ScrollToRow(w2ui['supplierGrid'].records[0].recid, true);
                thisObj.scrollToSupplier(w2ui['supplierGrid'].records[0].recid);
            }

            thisObj.makeScreenInformation();

        }




        this.addHelper = function () {
            var arrayHelper = [];
            //$.merge(arrayHelper, introAddFilterArea());
            //$.merge(arrayHelper,w2ui[thisObj.gridName].toolbar.getIntroHelp());
            //$.merge(arrayHelper, w2ui[thisObj.gridName].getIntroHelp());

            //introAddNew({steps: arrayHelper});
        }


        this.closeScreen = function () {
            var vhaschanges = w2ui['supplierGrid'].getChanges().length > 0 || w2ui['quotationGrid'].getChanges().length > 0;
            //var vhaschanges = false;
            if (vhaschanges) {
                messageBoxOkCancel(javaMessages.info_changed_close, function () {
                    $(window).off('resize.rfqsupResize');
                    SBSModalFormsVar.close();
                    $(window).off('onCloseForm.prj');
                })
            } else {
                $(window).off('resize.rfqsupResize');
                SBSModalFormsVar.close();
                $(window).off('onCloseForm.prj');
            }
        }

        // adicao de listeners!
        this.addListeners = function () {

            // seto o evendo de fechar!!!
            $(window).on("onCloseForm.prj", function (a) {
                thisObj.closeScreen();
            });

            $(window).on('resize.rfqsupResize', function () {
                thisObj.resizeGrid();
            });


            w2ui['supplierGrid'].on('rowFocusChanging', function (e) {
                e.onComplete = function (ev) {
                    console.log(ev);
                    thisObj.scrollToSupplier(ev.recid_new);
                }
            });
            
            w2ui['supplierGrid'].on('pickList', function (e) {
                
                e.onComplete = function (ev) {
                    if (ev.newCode != -1) {
                        w2ui['supplierGrid'].setItem(ev.recid, 'nr_tax', ev.dataRec.nr_tax_default);
                    }
                    
                }
            });

            w2ui['quotationGrid'].on('gridChanged', function (e) {
                if (e.colname == 'ds_supplier_equipment_description' || e.colname == 'ds_supplier_equipment_part_number') {

                    var vcode = w2ui['quotationGrid'].getItem(e.data.recid, 'cd_rfq_item_supplier');
                    var vds = chkUndefined(w2ui['quotationGrid'].getItem(e.data.recid, e.colname), '');
                    w2ui['quotationGrid'].setItem(e.data.recid, 'cd_rfq_item_supplier', vcode);

                    $.each(w2ui['quotationGrid'].records, function (i, v) {
                        var vdnow = chkUndefined(w2ui['quotationGrid'].getItem(v.recid, e.colname), '');
                        if (v.cd_rfq_item_supplier == vcode && vdnow != vds) {
                            w2ui['quotationGrid'].setItem(v.recid, e.colname, vds);

                            // make it send together so I can save on the table


                        }
                    })


                }
            });



            /*
             w2ui['supplierGrid'].on('update', function (e) {
             e.onComplete = function (ev) {
             w2ui['quotationGrid'].clear()
             w2ui['samplesGrid'].clear();
             
             w2ui['quotationGrid'].add(ev.data.quo);
             w2ui['samplesGrid'].add(ev.data.smp);
             thisObj.makeScreenInformation();
             }
             });
             
             */

            /*
             w2ui['supplierGrid'].on('dblClick', function (event) {
             var col = event.column;
             var colname = w2ui['supplierGrid'].columns[col].field;
             
             if (colname == 'ds_supplier') {
             basicPickListOpen({controller: 'rfq/supplier/openPL',
             title: 'Supplier',
             sel_id: -1,
             plCallBack: function (id, desc, rec) {
             
             }
             });
             event.preventDefault();
             
             }
             
             //basicPickListOpen (, 'getOptionsPL', id, 400, 400, 'settings');
             
             });
             */



        }



        // roda antes de fechar (se retornar FALSE o sistema vai perguntar se quer fechar
        this.beforeClose = function () {
            return true;
        }


        // close object (lugar para destruir as coisas//
        this.close = function () {
            //introRemove();
            return true;
        }



        this.makeScreenInformation = function () {
            var vhas;

            console.log(w2ui['quotationGrid'].last.searchIds);

            $.each(w2ui['quotationGrid'].last.searchIds, function (i, v) {
                var vc = chkUndefined(w2ui['quotationGrid'].getItem(w2ui['quotationGrid'].records[v].recid, 'cd_currency'), thisObj.cd_currency);

                if (vc != thisObj.cd_currency) {
                    vhas = true;
                    return false;
                }
            });

            if (vhas) {
                w2ui['quotationGrid'].showColumn('nr_price_default_currency', 'nr_price_with_tax_default_currency');
            } else {
                w2ui['quotationGrid'].hideColumn('nr_price_default_currency', 'nr_price_with_tax_default_currency');
            }

        }

        this.setScreenPermissions = function () {


        }

        this.tabSupAfterChanged = function () {
            thisObj.resizeGrid();
        }

        this.resizeGrid = function () {

            var hAvail = getWorkArea();

            $('#supplierGridDiv').height(hAvail - 50)
            $('#quotationDivGrid').height(hAvail - 50)
            w2ui['supplierGrid'].refresh();
            w2ui['quotationGrid'].refresh();



        }

        this.setGridAsChanged = function () {

        }

        this.setDemandedAsChanged = function () {
        }

        this.openDocRep = function (pk) {

            thisObj.openPK = -1;
            openRepository({id: 3, code: thisObj.cd_rfq});

        }

        this.ToolbarGrid = function (a) {
            if (a == 'update') {
                thisObj.update();
            }

            if (a == 'close') {
                thisObj.closeScreen();
            }



            if (a == 'docrep') {
                var vk = w2ui['supplierGrid'].getPk();
                thisObj.openDocRep(vk);

            }

            if (a == "delete") {
                
                var vk = w2ui['supplierGrid'].getPk();
                if (vk == -1) {
                    return;
                }
                
                var vRound = w2ui['supplierGrid'].getItem(vk, 'nr_round');

                if (vRound > 0) {
                    messageBoxError('<?php echo($errordeletesup); ?>')
                    return;
                }

                

                w2ui['supplierGrid'].deleteRow({funcAfter: function () {
                        thisObj.scrollToSupplier(w2ui['supplierGrid'].getPk());
                    }});

            }


            if (a == 'insert') {
                w2ui['supplierGrid'].insertRow({funcAfter: function (a) {
                        w2ui['supplierGrid'].setItem(a.recid, 'nr_round', 0);
                        w2ui['supplierGrid'].setItem(a.recid, 'cd_rfq', thisObj.cd_rfq);
                        thisObj.scrollToSupplier(a.recid);


                    }
                });
            }
            if (a == 'newround') {

                var vpk = w2ui['supplierGrid'].getPk();
                if (vpk == -1) {
                    return;
                }
                var vround = w2ui['supplierGrid'].getItem(vpk, 'nr_round');
                var supp = w2ui['supplierGrid'].getItem(vpk, 'cd_supplier');
                var dssupp = w2ui['supplierGrid'].getItem(vpk, 'ds_supplier');

                messageBoxYesNo('Confirm add a new round for Supplier ' + dssupp, function () {
                    thisObj.update(vround, supp, 'N');
                })
            }

            if (a == 'deleteround') {

                var vpk = w2ui['supplierGrid'].getPk();
                if (vpk == -1) {
                    return;
                }
                var vround = w2ui['supplierGrid'].getItem(vpk, 'nr_round');
                var supp = w2ui['supplierGrid'].getItem(vpk, 'cd_supplier');
                var dssupp = w2ui['supplierGrid'].getItem(vpk, 'ds_supplier');

                messageBoxYesNo('Confirm delete Round ' + vround + 'for Supplier ' + dssupp, function () {
                    thisObj.update(vround, supp, 'Y');
                })
            }



            if (a == 'showlast') {
                var vRecid = w2ui['supplierGrid'].getPk();
                if (vRecid != -1) {
                    setTimeout(function () {
                        thisObj.scrollToSupplier(vRecid);
                    }, 0);

                }
            }

        }

        this.scrollToSupplier = function (cdrfqsup) {

            var vshowlast = w2ui['quotationGrid'].toolbar.get('showlast').checked;
            var searchData = [];
            var sup = w2ui['supplierGrid'].getItem(cdrfqsup, 'cd_supplier');

            searchData = [{field: 'cd_supplier', operator: 'is', value: parseInt(sup), 'type': 'int'}];

            if (vshowlast) {
                searchData.push({field: 'fl_last', operator: 'is', value: 'Y'})
            }


            w2ui['quotationGrid'].searchData = searchData;
            w2ui['quotationGrid'].localSearch();
            w2ui['quotationGrid'].last.logic = 'AND';

            w2ui['quotationGrid'].refresh();
            thisObj.makeScreenInformation();

            if (!vshowlast) {
                w2ui['quotationGrid'].showColumn('nr_round');
            } else {
                w2ui['quotationGrid'].hideColumn('nr_round');
            }



        }

        this.update = function (round, supp, vdel) {

            var vMissingColumn = w2ui['quotationGrid'].checkDemanded();
            if (vMissingColumn !== '') {
                messageBoxError(javaMessages.msgMissingInformation + '<br>' + vMissingColumn);
                thisObj.openPK = -1;
                return;
            }

            var vMissingColumn = w2ui['supplierGrid'].checkDemanded();
            if (vMissingColumn !== '') {
                messageBoxError(javaMessages.msgMissingInformation + '<br>' + vMissingColumn);
                return;
                thisObj.openPK = -1;
            }



            if (round == undefined) {
                round = -1;
                supp = -1;
                vdel = 'N'
            }

            var vRecid = w2ui['supplierGrid'].getPk();

            $.myCgbAjax({url: 'rfq/rfq_supplier/updateData/' + thisObj.cd_rfq + '/' + round + '/' + supp + '/' + vdel,
                data: {gridsup: JSON.stringify(w2ui['supplierGrid'].getChanges()), gridquo: JSON.stringify(w2ui['quotationGrid'].getChanges())},
                success: function (data) {

                    if (data.status != 'OK') {
                        messageBoxError(data.status);
                        thisObj.openPK = -1;
                        return;
                    }

                    w2ui['supplierGrid'].clear();
                    w2ui['quotationGrid'].clear();
                    w2ui['supplierGrid'].add(data.sup);
                    w2ui['quotationGrid'].add(data.quo);
                    if (vRecid != -1) {
                        w2ui['supplierGrid'].ScrollToRow(vRecid, true);
                        thisObj.scrollToSupplier(vRecid);
                    } else {
                        thisObj.makeScreenInformation();
                    }
                    
                    var vischanged = dsFormRfqSheetObject.Form.isChanged();
                    
                    $.each(data.item, function(i,v) {
                        var vf = 'nr_count_quote_' + v.recid+ '_form';
                    
                        dsFormRfqSheetObject.Form.setItem(vf, v.nr_count_quote);
                    }) 
                    
                    if (!vischanged) {
                        dsFormRfqSheetObject.Form.resetUpdate();
                    }
                    
                    dsFormRfqSheetObject.changeView();
                    
                    if (thisObj.openPK != -1) {
                        thisObj.openDocRep(thisObj.openPK);
                    }



                },
                errorAfter: function () {
                    thisObj.openPK = -1;
                }});


        }

    }

// funcoes iniciais;
    dsRFQSupObject.start();


// insiro colunas;

</script>

<div id="divSupForm" style="max-height: calc(100vh - 40px);" class="" > 
    <div class="row">
        <div class="col-md-6 col-lg-3 no-padding"> <div style="height: 400px;width: 100%" id="supplierGridDiv"> </div></div>
        <div class="col-md-6 col-lg-9 no-padding"> <div style="height: 400px;width: 100%" id="quotationDivGrid"></div></div>
    </div>


</div>