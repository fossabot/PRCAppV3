<?php ?>

<script>
    // aqui tem os scripts basicos.
    //var controllerName = "country";

    //$(".ds_hr_type").on( "change",  function() {


    var dsRFQEqpObject = new function () {

        // variaveis privadas;

        var thisObj = this;
        thisObj.gridName = undefined;
        thisObj.lastCode = -20;
        //thisObj.cd_rfq = <?php //echo($cd_rfq)       ?>//;
        thisObj.openPK = -1;
        thisObj.copyFromItem = -1;
        thisObj.cheapTest = [];
        
        //thisObj.readOnly = <?php //echo($readonly)       ?>//;
        //thisObj.canChangeCost = <?php //echo($canChangeCost)       ?>//;

        this.start = function () {

<?php
echo($retGrid);
?>


            this.setScreenPermissions();

            this.addListeners();
            this.addHelper();

            this.resizeGrid();
            //$('#mainSupTabsDiv').ctabStart({afterChanged: thisObj.tabSupAfterChanged});//({afterChanged: thisObj.tabAfterChanged, beforeChange: thisObj.tabBeforeChanged});

            w2ui['QuotationGrid'].last.logic = 'AND';
            w2ui['QuotationGrid'].showNewWhenFiltering = false;
            w2ui['QuotationGrid'].sortData = [{field: 'dt_update', direction: 'desc'}]

            if (w2ui['itemGrid'].records.length > 0) {
                w2ui['itemGrid'].ScrollToRow(w2ui['itemGrid'].records[0].recid, true);
                thisObj.scrollToQuotation(w2ui['itemGrid'].records[0].cd_rfq_item);
            }

            thisObj.makeScreenInformation();

        }


        this.addHelper = function () {
            var arrayHelper = [];

        }


        this.closeScreen = function () {
            var vhaschanges = w2ui['itemGrid'].getChanges().length > 0 || w2ui['QuotationGrid'].getChanges().length > 0;
            //var vhaschanges = false;

            console.log("close window");
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


            w2ui['QuotationGrid'].on('itemChanging', function (e) {
                e.onComplete = function (event) {
                    thisObj.removeSupplierDuplicated(event.data.recid);
                }

            });

            w2ui['itemGrid'].on('rowFocusChanging', function (e) {
                e.onComplete = function (ev) {
                    var Vcd = w2ui['itemGrid'].getItem(ev.recid_new, 'cd_rfq_item');
                    thisObj.scrollToQuotation(Vcd);
                    // console.log('cd_equipment_design', ev.recid_new);
                    //console.log('coisas', w2ui['QuotationGrid'].records, ev);

                }
            });

            w2ui['itemGrid'].on('pickList', function (e) {

                e.onComplete = function (ev) {

                }
            });

            w2ui['QuotationGrid'].on('click', function (e) {
            });

            // w2ui['QuotationGrid'].on('update', function (e) {
            //     e.onComplete = function () {
            //
            //         var vRecid = w2ui['itemGrid'].getPk();
            //         setTimeout(function () {
            //             thisObj.scrollToQuotation(vRecid);
            //         }, 0);
            //     }
            // });

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


            var vactualBuy = record.fl_cheapest;

            if (vactualBuy == 1) {
                vImage = 'fa-thumbs-o-up';
                vStyle = 'color: blue;width: 100%;';
            } else {
                vImage = '';
                vStyle = 'color: blue;width: 100%;';

            }


            var buttons = '<div style="text-align: center; width: 100%"><span class="fa ' + vImage + '" style="font-size: 14px;' + vStyle + '" aria-hidden="true" ></span></div>';

            return buttons;
        }

        this.makeScreenInformation = function () {

            


            var vcomp = 9999999;
            var vrec = w2ui['QuotationGrid'].records;
            var vreccheap = -1;
            var vfound = 0;
            $.each(w2ui['QuotationGrid'].last.searchIds, function (i, v) {
                vfound = vrec[v].cd_rfq_item_source;
                
                if (thisObj.cheapTest[vfound] != undefined) {
                    return false;
                }
                
                console.log('fui',vvalue );
                
                var vvalue = vrec[v].nr_price_default_currency;
                if (vvalue < vcomp) {
                    vreccheap = vrec[v].recid;
                    vcomp = vvalue;
                }
                w2ui['QuotationGrid'].setItemNoChanges(vrec[v].recid, 'fl_cheapest', 0);
            });

            if (vreccheap != -1) {
                w2ui['QuotationGrid'].setItemNoChanges(vreccheap, 'fl_cheapest', 1);
                thisObj.cheapTest[vfound] = vfound;
            }

        }

        this.setScreenPermissions = function () {


        }

        this.tabSupAfterChanged = function () {
            thisObj.resizeGrid();
        }

        this.resizeGrid = function () {

            var hAvail = getWorkArea();

            $('#itemGridDiv').height(hAvail - 50)
            $('#QuotationGridDiv').height(hAvail - 50)
            w2ui['itemGrid'].refresh();
            w2ui['QuotationGrid'].refresh();


        }


        this.removeSupplierDuplicated = function (vrecid) {
            //console.log(vrecid, w2ui['QuotationGrid'].last);

            var vsup = w2ui['QuotationGrid'].getItem(vrecid, 'cd_supplier');
            var vrec = w2ui['QuotationGrid'].records;

            $.each(w2ui['QuotationGrid'].last.searchIds, function (i, v) {
                var vchecked = w2ui['QuotationGrid'].getItem(vrec[v].recid, 'fl_checked');
                var vsupother = w2ui['QuotationGrid'].getItem(vrec[v].recid, 'cd_supplier');

                if (vrec[v].recid == vrecid || vchecked != 1) {
                    return true;
                }





                if (vsup == vsupother) {
                    messageBoxError('<?php echo($errorSup) ?>');
                    w2ui['QuotationGrid'].setItem(vrecid, 'fl_checked', 0);
                    return false;
                }




            });

        }


        this.ToolbarGrid = function (a) {


            if (a == 'saveQuotation') {
                var x = [];

                $.each(w2ui['QuotationGrid'].records, function (i, v) {
                    if (w2ui['QuotationGrid'].getItem(v.recid, 'fl_checked') == 1) {
                        x.push({item: v.cd_rfq_item_source, quot: v.cd_rfq_item_supplier_quotation, sup: v.cd_supplier, rfqcopy: v.cd_rfq});
                    }
                });

                if (x.length == 0) {
                    return;
                }

                var vdata = {toSend: x};

                $.myCgbAjax({
                    url: 'rfq/rfq_quotation_history/duplicateRfqItemSupplierQuotation',
                    data: vdata,
                    success: function (data) {
                        if (data.status == 'OK') {
                            w2ui['QuotationGrid'].mergeChanges();
                            thisObj.closeScreen();
                            $('#tb_RfqFormToolbar_item_opensupplier').find('.w2ui-button').click();
                        }
                    }
                });

            }

        }

        this.scrollToQuotation = function (cdrfqitem) {

            var searchData = [];

            searchData = [{
                    field: 'cd_rfq_item_source',
                    operator: 'is',
                    value: parseInt(cdrfqitem),
                    type: 'int'
                }];

            w2ui['QuotationGrid'].searchData = searchData;
            w2ui['QuotationGrid'].localSearch();


            w2ui['QuotationGrid'].refresh();
            w2ui['QuotationGrid'].localSort(true);


            thisObj.makeScreenInformation();
        }

        // this.pasteFrom = function () {
        //     var vitempast = w2ui['itemGrid'].getPk();
        //
        //     if (thisObj.copyFromItem == -1) {
        //         return;
        //     }
        //
        //     if (thisObj.copyFromItem == vitempast) {
        //         return;
        //     }
        //
        //     var vd = [];
        //
        //     $.each(w2ui['QuotationGrid'].records, function (i, v) {
        //         var vi = w2ui['QuotationGrid'].getItem(v.recid, 'cd_rfq_item');
        //         if (vi == thisObj.copyFromItem) {
        //             var vr = JSON.parse(JSON.stringify(gridGetItem('QuotationGrid', v.recid)));
        //             vr.recid = w2ui['QuotationGrid'].getNextNegCode();
        //             vr.cd_rfq_cost_center = vr.recid;
        //             vr.cd_rfq_item = vitempast;
        //
        //             var vTochange = JSON.parse(JSON.stringify(vr));
        //             vTochange.recid = undefined;
        //             vTochange.cd_rfq_cost_center = undefined;
        //             vr.changes = vTochange;
        //
        //             w2ui['QuotationGrid'].add(vr)
        //
        //
        //             vd.push(vr);
        //         }
        //     });
        //
        // }

        // this.btnPLRender = function (record, index, column_index) {
        //
        //
        //     var bcanChange = true;
        //     var vfield = this.columns[column_index].field;
        //     var vx = this.columns[column_index].field;
        //     var vdata = chkUndefined(record[vx], '&nbsp');
        //
        //     if (vdata == '') {
        //         vdata = '&nbsp';
        //     }
        //
        //     return '<button type="button" class="btn btn-info btn-xs" aria-label="Left Align" id="daterangeOK" onclick="dsRFQDepObject.openPLModel(' + record.recid + ');" style="width: 25px; height: 20px;"> <i class="fa fa-magic" aria-hidden="true"></i> </button>';
        //
        //     /*
        //
        //      if (!bcanChange) {
        //      vdata = '<div class="w2ui-data-disabled" style="background-color: transparent">' + vdata + '</div>';
        //      } else {
        //      vdata = gridMakePLRender.call(this, record, index, column_index);
        //      }
        //      */
        //
        //     return vdata;
        // }

        // this.openPLModel = function (recid) {
        //
        //     basicPickListOpen({controller: 'tti/project_model/openPL',
        //         title: 'Project',
        //         sel_id: -1,
        //         showTitle: true,
        //         plCallBack: function (id, desc, rec) {
        //             console.log('data', rec);
        //
        //             w2ui['QuotationGrid'].setItem(recid, 'ds_project_number', rec.ds_tti_project);
        //             w2ui['QuotationGrid'].setItem(recid, 'ds_project_model_number', rec.ds_tti_project_model);
        //             w2ui['QuotationGrid'].setItem(recid, 'ds_project_description', rec.ds_project_full_desc);
        //             w2ui['QuotationGrid'].setItem(recid, 'cd_project_model', rec.cd_project_model);
        //
        //
        //         }
        //     });
        //
        // }


    }

    // funcoes iniciais;
    dsRFQEqpObject.start();


    // insiro colunas;

</script>

<div id="divSupForm" style="max-height: calc(100vh - 40px);" class="">
    <div class="row">
        <div class="col-md-4 col-lg-3 no-padding">
            <div style="height: 400px;width: 100%" id="itemGridDiv"></div>
        </div>
        <div class="col-md-6 col-lg-9 no-padding">
            <div style="height: 400px;width: 100%" id="QuotationGridDiv"></div>
        </div>
    </div>


</div>