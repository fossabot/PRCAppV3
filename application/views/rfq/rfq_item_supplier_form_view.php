<?php ?>

<script>
    // aqui tem os scripts basicos.
    //var controllerName = "country";

    //$(".ds_hr_type").on( "change",  function() {


    var dsRFQSupjObject = new function () {

        // variaveis privadas;

        var thisObj = this;
        thisObj.gridName = undefined;
        thisObj.lastCode = -20;
        thisObj.cd_rfq_item = <?php echo($cd_rfq_item) ?>;
        thisObj.cd_currency = <?php echo($cd_currency) ?>;
        thisObj.ds_currency = '<?php echo($ds_currency) ?>';

        thisObj.cd_payment_term = <?php echo($cd_payment_term) ?>;
        thisObj.ds_payment_term = '<?php echo($ds_payment_term) ?>';
        thisObj.ds_kind_default = '<?php echo($kinddefault) ?>';
        thisObj.ds_kind_other = '<?php echo($kindother) ?>';
        thisObj.readOnly = <?php echo($readonly) ?>;
        thisObj.choosingSupplier = <?php echo($choosingSupplier) ?>;
        thisObj.canFinance = <?php echo($canFinance) ?>;
        thisObj.canChangeCost = <?php echo($canChangeCost) ?>;
        thisObj.canSample = <?php echo($canSample) ?>;
        thisObj.checkRemarksBeforeSave = false;


        this.start = function () {

            <?php
            echo($SupModelGrid);
            echo($depGrid);
            ?>


            this.setScreenPermissions();

            this.addListeners();
            this.addHelper();

            this.resizeGrid();
            $('#mainSupTabsDiv').ctabStart({afterChanged: thisObj.tabSupAfterChanged});//({afterChanged: thisObj.tabAfterChanged, beforeChange: thisObj.tabBeforeChanged});

            thisObj.makeScreenInformation();

            w2ui['depCostGrid'].addSummary(['nr_qtty_to_charge']);
            w2ui['depCostGrid'].refreshSummary();

            this.scrollToSupplier();


        }


        this.addHelper = function () {
            var arrayHelper = [];
            //$.merge(arrayHelper, introAddFilterArea());
            //$.merge(arrayHelper,w2ui[thisObj.gridName].toolbar.getIntroHelp());
            //$.merge(arrayHelper, w2ui[thisObj.gridName].getIntroHelp());

            //introAddNew({steps: arrayHelper});
        }


        this.closeScreen = function () {
            var vhaschanges = w2ui['supplierGrid'].getChanges().length > 0 || w2ui['quotationGrid'].getChanges().length > 0 || w2ui['samplesGrid'].getChanges().length > 0;
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

            w2ui['supplierGrid'].on('update', function (e) {
                e.onComplete = function (ev) {
                }
            });

            $(window).on('resize.rfqsupResize', function () {
                thisObj.resizeGrid();
            });

            w2ui['quotationGrid'].on('gridChanged', function (e) {

                if (e.colname == "nr_qtty_to_buy" || e.columnname == "ds_reason_to_choose_supplier") {
                    if (e.data == undefined) {
                        var vrecid = e.recid;
                    } else {
                        var vrecid = e.data.recid;
                    }

                    var vds = chkUndefined(w2ui['quotationGrid'].getItem(vrecid, 'nr_qtty_to_buy'), '0');
                    w2ui['quotationGrid'].setItem(vrecid, 'nr_qtty_to_buy', vds);
                    if (vds == 0) {
                        w2ui['quotationGrid'].setItem(vrecid, 'ds_reason_to_choose_supplier', '');
                    }

                    thisObj.setQttyToDepartment();
                    thisObj.checkRemarksBeforeSave = true;

                }
            });
        }

        this.setQttyToDepartment = function () {
            if (w2ui['depCostGrid'].records.length == 1) {
                var vqqty = 0;

                $.each(w2ui['quotationGrid'].records, function (i, v) {
                    vqqty = vqqty + parseInt(chkUndefined(w2ui['quotationGrid'].getItem(v.recid, 'nr_qtty_to_buy'), 0));
                });

                w2ui['depCostGrid'].setItem(w2ui['depCostGrid'].records[0].recid, 'nr_qtty_to_charge', vqqty)

            }

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
            var vhas = false;
            $.each(w2ui['quotationGrid'].records, function (i, v) {
                var vc = chkUndefined(w2ui['quotationGrid'].getItem(v.recid, 'cd_currency'), thisObj.cd_currency);

                if (vc != thisObj.cd_currency) {
                    vhas = true;
                    return false;
                }
            });

            var vfunc;
            if (vhas) {
                w2ui['quotationGrid'].showColumn('nr_price_default_currency', 'nr_price_with_tax_default_currency');
            } else {
                w2ui['quotationGrid'].hideColumn('nr_price_default_currency', 'nr_price_with_tax_default_currency');
            }

            thisObj.checkLowestPrice();

        }

        this.setScreenPermissions = function () {
            if (thisObj.readOnly) {
                w2ui['quotationGrid'].readOnly();
                w2ui['supplierGrid'].readOnly();
                w2ui['depCostGrid'].readOnly();
                if (!thisObj.canSample) {
                    w2ui['samplesGrid'].readOnly();
                }
            }


            if (thisObj.choosingSupplier) {

                w2ui['quotationGrid'].readOnly(['nr_qtty_to_buy', 'ds_reason_to_choose_supplier', 'ds_remarks_on_reason']);
                w2ui['supplierGrid'].readOnly();
                //w2ui['depCostGrid'].readOnly(['ds_department_cost_center', 'ds_project_number']);
                //w2ui['samplesGrid'].readOnly();
                $('#supplierGridDiv').w2render('quotationGrid');
                $('#supplierGridDiv').resize();
                $().w2render('supplierGrid');
            }

            if (!thisObj.canChangeCost) {
                w2ui['depCostGrid'].readOnly();
            }

            if (!thisObj.canFinance) {
                $('#supplierGridDiv').hide();
                $('#mainTabDiv').hide();
                $('#depCostGriddiv').removeClass('col-md-4').add('col-md-12');
            }


        }


        this.renderBuy = function (record, index, column_index) {
            if (record == undefined) {
                return;
            }

            // check how many  we have

            var vStyle = '';
            var vImage = '';
            var vHigher = false;

            if (w2ui[this.name] == undefined) {
                return;
            }

            var vgrid = w2ui[this.name];


            var vactualBuy = chkUndefined(vgrid.getItem(record.recid, 'nr_qtty_to_buy'), 0);

            if (vactualBuy > 0) {
                vImage = 'fa-handshake-o    ';
                if (record.isLowest) {
                    vStyle = 'color: blue;width: 100%;';
                } else {
                    vStyle = 'color: red;width: 100%;';
                }
            } else {
                if (record.isLowest) {
                    vImage = 'fa-thumbs-o-up';
                    vStyle = 'color: blue;width: 100%;';
                }
            }


            var buttons = '<div style="text-align: center; width: 100%"><span class="fa ' + vImage + '" style="font-size: 14px;' + vStyle + '" aria-hidden="true" ></span></div>';

            return buttons;
        }

        this.tabSupAfterChanged = function () {
            thisObj.resizeGrid();
        }

        this.resizeGrid = function () {
            var hAvail = getWorkArea() - 130;

            $('#tab_quotation_div').height(Math.round(hAvail / 2))
            $('#tab_sample_div').height(Math.round(hAvail / 2))
            $('#supplierGridDiv').height(Math.round(hAvail / 2))
            $('#depCostGriddiv').height(Math.round(hAvail / 2))


            w2ui['supplierGrid'].refresh();
            w2ui['quotationGrid'].refresh();
            w2ui['samplesGrid'].refresh();
            w2ui['depCostGrid'].refresh();


        }

        this.setGridAsChanged = function () {

        }

        this.setDemandedAsChanged = function () {
        }


        this.ToolbarGridCC = function (bPressed) {
            if (bPressed == 'insert') {
                w2ui['depCostGrid'].insertRow({
                    funcAfter: function (a) {
                        w2ui['depCostGrid'].setItem(a.recid, 'cd_rfq_item', thisObj.cd_rfq_item);
                        thisObj.setQttyToDepartment();
                        w2ui['depCostGrid'].openPL(a.recid, 'ds_department_cost_center');
                    }
                });
            }

            if (bPressed == 'delete') {
                w2ui['depCostGrid'].deleteRow();
            }

            if (bPressed == "update") {
                thisObj.updateData();
            }


        }

        this.ToolbarGridSup = function (bPressed) {


            if (bPressed == 'insert') {
                w2ui['supplierGrid'].insertRow({
                    funcAfter: function (a) {
                        var vx = dsFormRfqSheetObject.Form.getItem('ds_equipment_design_' + thisObj.cd_rfq_item + '_form');
                        w2ui['supplierGrid'].setItem(a.recid, 'ds_supplier_equipment_description', vx);
                        w2ui['supplierGrid'].setItem(a.recid, 'cd_rfq_item', thisObj.cd_rfq_item);
                        w2ui['supplierGrid'].setItem(a.recid, 'nr_round', 0);
                    }
                });
            }

            if (bPressed == "update") {
                thisObj.updateData();
            }


            //thisObj.setChildData();
            //w2ui['supplierGrid'].update({updFunc: 'updateDataJsonData/' + thisObj.cd_rfq_item});


            if (bPressed == "copysup") {
                if (w2ui['supplierGrid'].getChanges() > 0 || w2ui['samplesGrid'].getChanges() > 0 || w2ui['quotationGrid'].getChanges() > 0) {
                    messageBoxError(javaMessages.saveFirst);
                    return;
                }

                thisObj.copySupFromItems();

            }


            if (bPressed == "delete") {
                var vk = w2ui['supplierGrid'].getPk();


                if (vk == -1) {
                    return;
                }

                var vRound = w2ui['supplierGrid'].getItem(vk, 'nr_round');
                var vCountSmp = w2ui['samplesGrid'].find({cd_rfq_item_supplier: vk}).length;

                if (vCountSmp > 0 || vRound > 0) {
                    messageBoxError('<?php echo($errordeletesup); ?>')
                    return;
                }

                w2ui['supplierGrid'].deleteRow({
                    funcAfter: function () {
                    }
                });

            }


            if (bPressed == "close") {
                thisObj.closeScreen();
            }

        };


        this.updateData = function () {
            var vMissingColumn = w2ui['supplierGrid'].checkDemanded();

            if (vMissingColumn !== '') {
                messageBoxError(javaMessages.msgMissingInformation + '<br>' + vMissingColumn);
                return;
            }

            var vMissingColumn = w2ui['samplesGrid'].checkDemanded();
            if (vMissingColumn !== '') {
                messageBoxError(javaMessages.msgMissingInformation + '<br>' + vMissingColumn);
                return;
            }


            var vMissingColumn = w2ui['quotationGrid'].checkDemanded();
            if (vMissingColumn !== '') {
                messageBoxError(javaMessages.msgMissingInformation + '<br>' + vMissingColumn);
                return;
            }

            var vMissingColumn = w2ui['depCostGrid'].checkDemanded();
            if (vMissingColumn !== '') {
                messageBoxError(javaMessages.msgMissingInformation + '<br>' + vMissingColumn);
                return;
            }


            /*
             * var cansave = true;
             
             if (thisObj.checkRemarksBeforeSave ) {
             $.each(w2ui['quotationGrid'].records, function(i,v) {
             var vqtty   = chkUndefined(w2ui['quotationGrid'].getItem(v.recid, 'nr_qtty_to_buy'), 0);
             var vreason = chkUndefined(w2ui['quotationGrid'].getItem(v.recid, 'ds_reason_to_choose_supplier'), '').trim();
             
             
             if (vqtty > 0 && vreason == '' && !v.isLowest) {
             messageBoxError('<?php echo($errormustreason) ?>');
             cansave = false;
             return false;                        
             }
             
             });
             
             
             }
             if (!cansave) {
             return;
             }
             */


            $.myCgbAjax({
                url: 'rfq/rfq_item_supplier/updateDataJsonData/' + thisObj.cd_rfq_item,
                data: {
                    gridsup: JSON.stringify(w2ui['supplierGrid'].getChanges()),
                    gridquo: JSON.stringify(w2ui['quotationGrid'].getChanges()),
                    gridsmp: JSON.stringify(w2ui['samplesGrid'].getChanges()),
                    cost: JSON.stringify(w2ui['depCostGrid'].getChanges())
                },
                success: function (data) {

                    if (data.status != 'OK') {
                        messageBoxError(data.status);
                        thisObj.openPK = -1;
                        return;
                    }

                    w2ui['quotationGrid'].clear();
                    w2ui['samplesGrid'].clear();
                    w2ui['supplierGrid'].clear();
                    w2ui['depCostGrid'].clear();


                    w2ui['quotationGrid'].add(data.quo);
                    w2ui['samplesGrid'].add(data.smp);
                    w2ui['supplierGrid'].add(data.rs);
                    w2ui['depCostGrid'].add(data.cost);


                    dsFormRfqSheetObject.Form.setItem('fl_buy_' + data.item.recid + '_form', data.item.fl_buy);
                    dsFormRfqSheetObject.Form.setItem('nr_qtty_to_buy_' + data.item.recid + '_form', data.item.nr_qtty_to_buy);
                    dsFormRfqSheetObject.Form.setItem('ds_sample_info_' + data.item.recid + '_form', data.item.ds_sample_info);


                    if (thisObj.canFinance) {
                        dsFormRfqSheetObject.Form.setItem('ds_dep_cost_' + data.item.recid + '_form', data.item.ds_dep_cost);
                        dsFormRfqSheetObject.Form.setItem('ds_supplier_' + data.item.recid + '_form', data.item.ds_supplier);
                        dsFormRfqSheetObject.Form.setItem('nr_total_default_currency_' + data.item.recid + '_form', data.item.nr_total_default_currency);
                        dsFormRfqSheetObject.Form.setItem('ds_reason_to_choose_supplier_' + data.item.recid + '_form', data.item.ds_reason_to_choose_supplier);
                    }
                    dsFormRfqSheetObject.checkAutoButton(data.item.recid);

                    dsFormRfqSheetObject.Form.resetUpdate();

                    thisObj.makeScreenInformation();

                },
                errorAfter: function () {

                }
            });
        }


        this.ToolbarGridQou = function (bPressed) {
            if (bPressed == 'insertround') {
                thisObj.addQuotationSelected(true, thisObj.ds_kind_default);
            }

            if (bPressed == 'insertselected') {
                this.addQuotationSelected(false, thisObj.ds_kind_default);
            }

            if (bPressed == 'insertother') {
                this.addQuotationSelected(false, thisObj.ds_kind_other);
            }

            if (bPressed == "update") {
                thisObj.updateData('Y');
            }

            if (bPressed == "delete") {
                var vk = w2ui['quotationGrid'].getPk();
                if (vk == -1) {
                    return;
                }

                var vcode = w2ui['quotationGrid'].getItem(vk, 'cd_rfq_item_supplier');
                var vround = w2ui['quotationGrid'].getItem(vk, 'nr_round');
                var vroundlast = w2ui['supplierGrid'].getItem(vcode, 'nr_round');

                if (vroundlast != vround) {
                    messageBoxError('<?php echo($errordeletequo); ?>')
                    return;
                }

                w2ui['quotationGrid'].deleteRow({
                    funcAfter: function () {
                        w2ui['supplierGrid'].setItem(vcode, 'nr_round', vroundlast - 1);
                        w2ui['supplierGrid'].resetUpdateColumn(vcode, 'nr_round');
                    }
                });
            }

            if (bPressed == 'showlast') {
                setTimeout(function () {
                    thisObj.scrollToSupplier();
                }, 0);
            }

        };


        this.scrollToSupplier = function () {

            var vshowlast = w2ui['quotationGrid'].toolbar.get('showlast').checked;
            var searchData = [];

            if (vshowlast) {
                searchData.push({field: 'fl_last', operator: 'is', value: 'Y'})
            }


            w2ui['quotationGrid'].searchData = searchData;
            w2ui['quotationGrid'].localSearch();
            w2ui['quotationGrid'].last.logic = 'AND';

            w2ui['quotationGrid'].refresh();

            if (!vshowlast) {
                w2ui['quotationGrid'].showColumn('nr_round');
            } else {
                w2ui['quotationGrid'].hideColumn('nr_round');
            }


        }

        this.copySupFromItems = function () {
            //

            $.myCgbAjax({
                url: 'rfq/rfq_item_supplier/copySuppliers/' + thisObj.cd_rfq_item,
                message: javaMessages.updating,
                box: '#divSupForm',
                data: [],
                success: function (a) {
                    w2ui['quotationGrid'].clear()
                    w2ui['samplesGrid'].clear();
                    w2ui['supplierGrid'].clear();

                    w2ui['quotationGrid'].add(a.quo)
                    w2ui['samplesGrid'].add(a.smp);
                    w2ui['supplierGrid'].add(a.rs);

                    if (a.rs.length > 0) {
                        w2ui['supplierGrid'].ScrollToRow(a.rs[0].recid, true);
                    }

                    if (a.smp.length > 0) {
                        w2ui['samplesGrid'].ScrollToRow(a.smp[0].recid, true);
                    }

                    if (a.quo.length > 0) {
                        w2ui['quotationGrid'].ScrollToRow(a.quo[0].recid, true);
                    }


                    messageBoxAlert(javaMessages.update_done);
                },
            });

        }

        this.addQuotationSelected = function (all, kindQuo) {
            var v = w2ui['supplierGrid'].getSelectedRow();
            if (v == undefined) {
                return;
            }
            if (chkUndefined(v.cd_supplier, -1) == -1) {
                return;
            }

            if (all) {
                var records = w2ui['supplierGrid'].records.slice(0).reverse();

            } else {

                if (w2ui['supplierGrid'].getPk() == -1) {
                    return;
                }
                var records = [w2ui['supplierGrid'].getSelectedRow()];
            }

            $.each(records, function (i, v) {
                thisObj.lastCode--;

                w2ui['quotationGrid'].add({recid: thisObj.lastCode}, true);
                w2ui['quotationGrid'].setItem(thisObj.lastCode, 'ds_supplier', v.ds_supplier);
                w2ui['quotationGrid'].setItem(thisObj.lastCode, 'cd_rfq_item_supplier', v.recid);
                var vround = w2ui['supplierGrid'].getItem(v.recid, 'nr_round');
                vround++;
                w2ui['supplierGrid'].setItem(v.recid, 'nr_round', vround);
                w2ui['supplierGrid'].resetUpdateColumn(v.recid, 'nr_round');
                w2ui['quotationGrid'].setItem(thisObj.lastCode, 'nr_round', vround);
                w2ui['quotationGrid'].setItem(thisObj.lastCode, 'cd_currency', thisObj.cd_currency);
                w2ui['quotationGrid'].setItem(thisObj.lastCode, 'ds_currency', thisObj.ds_currency);
                w2ui['quotationGrid'].setItem(thisObj.lastCode, 'ds_kind', kindQuo);


                w2ui['quotationGrid'].setItem(thisObj.lastCode, 'cd_payment_term', thisObj.cd_payment_term);
                w2ui['quotationGrid'].setItem(thisObj.lastCode, 'ds_payment_term', thisObj.ds_payment_term);
            });


        }

        this.setChildData = function () {
            if (w2ui['supplierGrid'].records.length == 0) {
                return;
            }
            // first reset all columns (to make sure it goes only once). 
            $.each(w2ui['supplierGrid'].records, function (i, v) {
                w2ui['supplierGrid'].resetUpdateColumn(v.recid, 'ds_smp');
                w2ui['supplierGrid'].resetUpdateColumn(v.recid, 'ds_quo');
            });

            if (w2ui['quotationGrid'].getChanges().length > 0) {
                w2ui['supplierGrid'].setItem(w2ui['supplierGrid'].records[0].recid, 'ds_quo', JSON.stringify(w2ui['quotationGrid'].getChanges()));
            }

            if (w2ui['samplesGrid'].getChanges().length > 0) {
                w2ui['supplierGrid'].setItem(w2ui['supplierGrid'].records[0].recid, 'ds_smp', JSON.stringify(w2ui['samplesGrid'].getChanges()));
            }
        }

        this.ToolbarGridSmp = function (bPressed) {
            if (bPressed == 'insert') {

                if (thisObj.choosingSupplier) {
                    var v = w2ui['quotationGrid'].getSelectedRow();
                    if (v == undefined) {
                        return;
                    }

                    var vrecid = v.cd_rfq_item_supplier;

                } else {
                    var v = w2ui['supplierGrid'].getSelectedRow();
                    if (v == undefined) {
                        return;
                    }
                    var vrecid = v.recid;

                }

                if (chkUndefined(v.cd_supplier, -1) == -1) {
                    return;
                }
                thisObj.lastCode--;

                w2ui['samplesGrid'].add({recid: thisObj.lastCode});
                w2ui['samplesGrid'].setItem(thisObj.lastCode, 'ds_supplier', v.ds_supplier);
                w2ui['samplesGrid'].setItem(thisObj.lastCode, 'cd_rfq_item_supplier', vrecid);
            }

            if (bPressed == "update") {
                thisObj.updateData();
            }


            if (bPressed == "delete") {

                w2ui['samplesGrid'].deleteRow({
                    funcAfter: function () {
                        thisObj.setChildData(true);
                    }
                });
            }

            if (bPressed == 'docrep') {

                var vcode = w2ui['samplesGrid'].getPk();
                // check if something is selected!
                if (vcode == -1) {
                    return;
                }

                // check if the row is new. If it is new, cannot insert child table.
                if (vcode < -10) {
                    messageBoxError(javaMessages.saveFirst);
                    return;
                }

                console.log("test", vcode);
                openRepository({id: 9, code: vcode});
            }


        };

        this.checkLowestPrice = function () {
            var vgrid = w2ui['quotationGrid'];
            var vrec = vgrid.records;


            $.each(vrec, function (i, v) {
                var vmin = true;

                $.each(vrec, function (ii, vv) {
                    if (v.nr_price_default_currency > vv.nr_price_default_currency) {
                        vmin = false;
                        return false;
                    }
                });

                vgrid.set(v.recid, {isLowest: vmin});

            });

        }

    }


    // funcoes iniciais;
    dsRFQSupjObject.start();


    // insiro colunas;

</script>

<div id="divSupForm" style="max-height: calc(100vh - 40px);" class="">
    <div class="row">


        <div class="col-md-8 no-padding" style="height: 250px; min-width: 300px" id="supplierGridDiv"></div>
        <div class="col-md-4 no-padding" style="height: 250px" id="depCostGriddiv"></div>
        <div class="col-md-12 no-padding" id="mainTabDiv"
             style="padding-top: 10px !important;"><?php echo($ctab) ?></div>
    </div>


</div>