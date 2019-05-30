<?php ?>

<script>
// aqui tem os scripts basicos. 
//var controllerName = "country";

//$(".ds_hr_type").on( "change",  function() {


    var dsRFQDepObject = new function () {

        // variaveis privadas;

        var thisObj = this;
        thisObj.gridName = undefined;
        thisObj.lastCode = -20;
        thisObj.cd_rfq = <?php echo($cd_rfq) ?>;
        thisObj.openPK = -1;
        thisObj.copyFromItem = -1;
        thisObj.readOnly = <?php echo($readonly) ?>;
        thisObj.canChangeCost = <?php echo($canChangeCost) ?>;

        this.start = function () {

<?php
echo($SupModelGrid);
?>


            this.setScreenPermissions();

            this.addListeners();
            this.addHelper();

            this.resizeGrid();
            //$('#mainSupTabsDiv').ctabStart({afterChanged: thisObj.tabSupAfterChanged});//({afterChanged: thisObj.tabAfterChanged, beforeChange: thisObj.tabBeforeChanged});

            w2ui['departmentGrid'].showNewWhenFiltering = false;
            w2ui['departmentGrid'].sortData = [{field: 'ds_department_cost_center', direction: 'asc'}]

            if (w2ui['itemGrid'].records.length > 0) {
                w2ui['itemGrid'].ScrollToRow(w2ui['itemGrid'].records[0].recid, true);
                thisObj.scrollToQuotation(w2ui['itemGrid'].records[0].recid);
            }

            thisObj.makeScreenInformation();

        }




        this.addHelper = function () {
            var arrayHelper = [];

        }


        this.closeScreen = function () {
            var vhaschanges = w2ui['itemGrid'].getChanges().length > 0 || w2ui['departmentGrid'].getChanges().length > 0;
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


            w2ui['itemGrid'].on('rowFocusChanging', function (e) {
                e.onComplete = function (ev) {
                    thisObj.scrollToQuotation(ev.recid_new);
                    console.log('coisas', w2ui['departmentGrid'].records, ev);

                }
            });

            w2ui['itemGrid'].on('pickList', function (e) {

                e.onComplete = function (ev) {

                }
            });

            w2ui['departmentGrid'].on('gridChanged', function (e) {
                if (e.colname == 'ds_supplier_equipment_description' || e.colname == 'ds_supplier_equipment_part_number') {
                }
            });

            w2ui['departmentGrid'].on('update', function (e) {
                e.onComplete = function () {

                    var vRecid = w2ui['itemGrid'].getPk();
                    setTimeout(function () {
                        thisObj.scrollToQuotation(vRecid);
                    }, 0);
                }
            });

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
            if (!thisObj.canChangeCost) {
                w2ui['departmentGrid'].readOnly();
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
            $('#departmentDivGrid').height(hAvail - 50)
            w2ui['itemGrid'].refresh();
            w2ui['departmentGrid'].refresh();



        }

        this.setGridAsChanged = function () {

        }

        this.setDemandedAsChanged = function () {
        }


        this.ToolbarGrid = function (a) {

            if (a == 'copy') {
                thisObj.copyFromItem = w2ui['itemGrid'].getPk();
            }

            if (a == 'paste') {
                thisObj.pasteFrom();
            }


            if (a == 'update') {
                w2ui['departmentGrid'].update();
            }

            if (a == 'close') {
                thisObj.closeScreen();
            }

            if (a == "delete") {

                w2ui['departmentGrid'].deleteRow({funcAfter: function () {
                        //thisObj.scrollToDepartment(w2ui['itemGrid'].getPk());
                    }});

            }


            if (a == 'insert') {
                w2ui['departmentGrid'].insertRow({funcAfter: function (a) {
                        var vRecid = w2ui['itemGrid'].getPk();
                        w2ui['departmentGrid'].setItem(a.recid, 'cd_rfq_item', vRecid);
                        //w2ui['departmentGrid'].mergeChanges();
                        //w2ui['departmentGrid'].refresh();

                        thisObj.scrollToQuotation(vRecid);

                    }
                });
            }

        }

        this.scrollToQuotation = function (cdrfqsup) {

            var searchData = [];

            searchData = [{field: 'cd_rfq_item', operator: 'is', value: parseInt(cdrfqsup), type: 'int'}];

            w2ui['departmentGrid'].searchData = searchData;
            w2ui['departmentGrid'].last.logic = 'AND';
            w2ui['departmentGrid'].localSearch();


            w2ui['departmentGrid'].refresh();
            w2ui['departmentGrid'].localSort(true);




            thisObj.makeScreenInformation();
        }

        this.pasteFrom = function () {
            var vitempast = w2ui['itemGrid'].getPk();

            if (thisObj.copyFromItem == -1) {
                return;
            }

            if (thisObj.copyFromItem == vitempast) {
                return;
            }

            var vd = [];

            $.each(w2ui['departmentGrid'].records, function (i, v) {
                var vi = w2ui['departmentGrid'].getItem(v.recid, 'cd_rfq_item');
                if (vi == thisObj.copyFromItem) {
                    var vr = JSON.parse(JSON.stringify(gridGetItem('departmentGrid', v.recid)));
                    vr.recid = w2ui['departmentGrid'].getNextNegCode();
                    vr.cd_rfq_cost_center = vr.recid;
                    vr.cd_rfq_item = vitempast;

                    var vTochange = JSON.parse(JSON.stringify(vr));
                    vTochange.recid = undefined;
                    vTochange.cd_rfq_cost_center = undefined;
                    vr.changes = vTochange;

                    w2ui['departmentGrid'].add(vr)


                    vd.push(vr);
                }
            });

        }

        this.btnPLRender = function (record, index, column_index) {


            var bcanChange = true;
            var vfield = this.columns[column_index].field;
            var vx = this.columns[column_index].field;
            var vdata = chkUndefined(record[vx], '&nbsp');

            if (vdata == '') {
                vdata = '&nbsp';
            }

            return '<button type="button" class="btn btn-info btn-xs" aria-label="Left Align" id="daterangeOK" onclick="dsRFQDepObject.openPLModel(' + record.recid + ');" style="width: 25px; height: 20px;"> <i class="fa fa-magic" aria-hidden="true"></i> </button>';

            /*
             
             if (!bcanChange) {
             vdata = '<div class="w2ui-data-disabled" style="background-color: transparent">' + vdata + '</div>';
             } else {
             vdata = gridMakePLRender.call(this, record, index, column_index);
             }
             */

            return vdata;
        }

        this.openPLModel = function (recid) {

            var vcdexpense=chkUndefined(w2ui['departmentGrid'].getItem(recid, 'cd_general_project_expense'), 0);
            var vcdmodel=chkUndefined(w2ui['departmentGrid'].getItem(recid, 'cd_project_model'), 0);

            // console.log('cd_general_project_expense:', vcdexpense);
            // console.log('cd_project_model:',w2ui['departmentGrid'].getItem(recid, 'cd_project_model'));
            basicPickListOpen({controller: 'tti/project_model/openPL/'+vcdmodel+'/'+vcdexpense,
                title: 'Project',
                sel_id: -1,
                showTitle: true,
                plCallBack: function ( rec, recExpense,vuseExpense,vuseprojectmodel) {


                    if(vuseexpense)
                    {
                        w2ui['departmentGrid'].setItem(recid, 'ds_project_number', recExpense.ds_general_project_number);
                        w2ui['departmentGrid'].setItem(recid, 'ds_project_model_number', recExpense.ds_general_project_model_number);
                        w2ui['departmentGrid'].setItem(recid, 'ds_project_description', recExpense.ds_general_project_expense);
                        w2ui['departmentGrid'].setItem(recid, 'cd_general_project_expense', recExpense.cd_general_project_expense);
                        if(vuseprojectmodel) {
                            w2ui['departmentGrid'].setItem(recid, 'cd_project_model', rec.cd_project_model);
                            w2ui['departmentGrid'].setItem(recid, 'ds_project_number_ref', rec.ds_tti_project);
                            w2ui['departmentGrid'].setItem(recid, 'ds_project_model_number_ref', rec.ds_tti_project_model);
                            w2ui['departmentGrid'].setItem(recid, 'ds_project_description_ref',  rec.ds_project_full_desc);
                        }
                        else {
                            w2ui['departmentGrid'].setItem(recid, 'cd_project_model', 0);
                            w2ui['departmentGrid'].setItem(recid, 'ds_project_number_ref', '');
                            w2ui['departmentGrid'].setItem(recid, 'ds_project_model_number_ref', '');
                            w2ui['departmentGrid'].setItem(recid, 'ds_project_description_ref',  '');
                        }
                    }
                    else if(vuseprojectmodel)
                    {
                        w2ui['departmentGrid'].setItem(recid, 'ds_project_number', rec.ds_tti_project);
                        w2ui['departmentGrid'].setItem(recid, 'ds_project_model_number', rec.ds_tti_project_model);
                        w2ui['departmentGrid'].setItem(recid, 'ds_project_description', rec.ds_project_full_desc);
                        w2ui['departmentGrid'].setItem(recid, 'cd_project_model', rec.cd_project_model);
                        w2ui['departmentGrid'].setItem(recid, 'cd_general_project_expense', 0);
                        w2ui['departmentGrid'].setItem(recid, 'ds_project_number_ref', rec.ds_tti_project);
                        w2ui['departmentGrid'].setItem(recid, 'ds_project_model_number_ref', rec.ds_tti_project_model);
                        w2ui['departmentGrid'].setItem(recid, 'ds_project_description_ref',  rec.ds_project_full_desc);
                    }


                }
            });

        }

        // Math.round((window).height() * 0.8));
        // $(window).weight();




    }

// funcoes iniciais;
    dsRFQDepObject.start();


// insiro colunas;

</script>

<div id="divSupForm" style="max-height: calc(100vh - 40px);" class="" > 
    <div class="row">
        <div class="col-md-5 col-lg-5 no-padding"> <div style="height: 400px;width: 100%" id="itemGridDiv"> </div></div>
        <div class="col-md-7 col-lg-7 no-padding"> <div style="height: 400px;width: 100%" id="departmentDivGrid"></div></div>
    </div>


</div>