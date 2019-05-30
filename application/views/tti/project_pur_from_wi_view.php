<?php ?>

<script>
    // aqui tem os scripts basicos.
    //var controllerName = "country";

    //$(".ds_hr_type").on( "change",  function() {


    var dsPurWIObject = new function () {

        // variaveis privadas;

        var thisObj = this;
        thisObj.gridName = 'itemGrid';
        thisObj.lastCode = -20;

        thisObj.timestamp = '<?php echo($datetime);?>';
        thisObj.openPK = -1;
        thisObj.copyFromItem = -1;


        this.start = function () {

            <?php
            echo($javascript);
            ?>


            this.setScreenPermissions();

            this.addListeners();
            this.addHelper();

            this.resizeGrid();
            $('#mainTabsReqDiv').ctabStart({
                afterChanged: thisObj.tabAfterChanged,
                beforeChange: thisObj.tabBeforeChanged
            });

            $('#tab_requests_div').append($('#reqarea').detach());
            $('#tab_release_div').append($('#releaseBuyArea').detach());


            thisObj.tabReleaseRef = $('#tab_release a');
            thisObj.tabReleaseText = thisObj.tabReleaseRef.html();

            if (w2ui['itemGrid'].records.length > 0) {
                w2ui['itemGrid'].ScrollToRow(w2ui['itemGrid'].records[0].recid, true);
                thisObj.scrollToQuotation(w2ui['itemGrid'].records[0].recid);
            }


            thisObj.makeScreenInformation();

        }

        this.releaseStatus = function (record, index, column_index) {

            if(record.dt_released_to_buy!='')
            {
                return '<i class="fa fa-thumbs-o-up" aria-hidden="true"></i>' ;
            }
            return ;


        }

        this.tabBeforeChanged = function (id) {

            w2ui['itemGrid'].refresh();
            w2ui['materialGrid'].refresh();
            w2ui['releaseBuyGrid'].refresh();
            return true;
        }

        this.tabAfterChanged = function (id) {

            if (id == 'tab_release') {

                w2ui['releaseBuyGrid'].refresh();
            }
        }
        this.addHelper = function () {
            var arrayHelper = [];

        }


        this.closeScreen = function () {
            var vhaschanges = w2ui['itemGrid'].getChanges().length > 0;
            var vhaschanges = false;
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


            w2ui['buyGrid'].on('update', function(e){
                e.onComplete = function(){
                    messageBoxAlert("Requests Saved Successfully. You can release to PUR team on release tab");
                    w2ui['buyGrid'].clear();
                    w2ui['releaseBuyGrid'].retrieve({level: -2});
                }

            })

            // seto o evendo de fechar!!!
            $(window).on("onCloseForm.prj", function (a) {
                thisObj.closeScreen();
            });

            $(window).on('resize.rfqsupResize', function () {
                thisObj.resizeGrid();
            });


            w2ui['materialGrid'].on('dblClick', function () {

                copySelected();
            });


            $('#btnMoveRight').on("click", function () {

                copySelected();
            });

            $('#btnBatchMoveRight').on("click", function () {

                copyAll();
                w2ui['materialGrid'].selectNone();
            });

            $('#btnMoveLeft').on("click", function () {

                removeSelected();
            });

            $('#btnBatchMoveLeft').on("click", function () {

                removeAll();
            });




            w2ui['itemGrid'].on('rowFocusChanging', function (a) {


                a.onComplete = function (b) {
                    makeMaterialGrid(b.recid_new);
                }
                w2ui['materialGrid'].refresh();

            });

            w2ui['itemGrid'].on('gridChanged', function (a) {
                makeMaterialGrid(a.data.recid);
                w2ui['itemGrid'].selectNone();

            });

        }


        function makeMaterialGrid(recid) {

            if (recid == -1) {
                return;
            }
            w2ui['materialGrid'].clear();
            var vx = chkUndefined(w2ui['itemGrid'].getItem(recid, 'material'), []);
            var sampleqty = chkUndefined(w2ui['itemGrid'].getItem(recid, 'nr_sample_quantity'), []);
            var goalqty = chkUndefined(w2ui['itemGrid'].getItem(recid, 'nr_goal'), []);
            var deadline = chkUndefined(w2ui['itemGrid'].getItem(recid, 'dt_est_start'), []);


            if (!$.isArray(vx)) {
                vx = JSON.parse(vx);
            }

            w2ui['materialGrid'].add(vx);

            w2ui['materialGrid'].hideColumn('nr_sample_quantity');
            w2ui['materialGrid'].hideColumn('nr_goal');
            w2ui['materialGrid'].hideColumn('UsageRatePerUnits');
            w2ui['materialGrid'].hideColumn('cd_equipment_design');
            $.each(w2ui['materialGrid'].records, function (i, v) {
                var UsageRate = chkUndefined(w2ui['materialGrid'].getItem(v.recid, 'UsageRatePerUnits'), []);
                var budgetQty = Number(goalqty) * Number(sampleqty) * Number(UsageRate);
                w2ui['materialGrid'].setItem(v.recid, 'nr_sample_quantity', sampleqty);
                w2ui['materialGrid'].setItem(v.recid, 'nr_goal', goalqty);
                w2ui['materialGrid'].setItem(v.recid, 'nr_quantity', budgetQty.toFixed(1));
                w2ui['materialGrid'].setItem(v.recid, 'dt_deadline', deadline);
                w2ui['materialGrid'].mergeChanges();
            })
        }


        // roda antes de fechar (se retornar FALSE o sistema vai perguntar se quer fechar
        this.beforeClose = function () {
            return true;
        }


        // close object (lugar para destruir as coisas//
        this.close = function () {
            return true;
        }


        this.makeScreenInformation = function () {

        }

        this.setScreenPermissions = function () {


        }

        this.tabSupAfterChanged = function () {
            thisObj.resizeGrid();
        }

        this.resizeGrid = function () {

            var hAvail = getWorkArea();

            $('#itemGridDiv').height(hAvail * 0.4)
            $('#materialGridDiv').height(hAvail * 0.4)
            $('#buyGridDiv').height(hAvail * 0.4)
            $('#releaseBuyGridDiv').height(hAvail*0.8)

            w2ui['itemGrid'].refresh();
            w2ui['materialGrid'].refresh();
            w2ui['buyGrid'].refresh();
            w2ui['releaseBuyGrid'].refresh();

        }

        this.setGridAsChanged = function () {

        }

        this.setDemandedAsChanged = function () {
        }


        this.ToolbarGridRelease = function (a) {


            if (a == 'update') {

                w2ui['releaseBuyGrid'].update();

            }

            if (a == 'delete') {
                debugger;
                w2ui['releaseBuyGrid'].deleteRow();
            }

            if (a == 'btnRelease') {
                var vpk = w2ui[thisObj.gridName].getPk();
                if (vpk == -1) {
                    return;
                }

                var records = w2ui['releaseBuyGrid'].getSelection();


                $.each(records, function (index, val) {
                    w2ui['releaseBuyGrid'].setItem(val, 'dt_released_to_buy', thisObj.timestamp);

                });

            }

            if (a == 'btnRemoveRelease') {

                var records = w2ui['releaseBuyGrid'].getSelection();

                $.each(records, function (index, val) {

                    w2ui['releaseBuyGrid'].setItem(val, 'dt_released_to_buy','');

                });

            }

            if (a == 'btnSelectAll') {
                w2ui['releaseBuyGrid'].selectAll();

            }

            if (a == 'btnRemoveAll') {

                w2ui['releaseBuyGrid'].selectNone();


            }
        }


        this.ToolbarGrid = function (a) {


            if (a == 'update') {
                w2ui['buyGrid'].update();


            }

            if (a == 'close') {
                thisObj.closeScreen();
            }

            if (a == "delete") {

            }


            if (a == 'insert') {

            }

        }

        this.scrollToQuotation = function (cdrfqsup) {


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


        }

    }

    function copySelected() {

        var selitem = w2ui['itemGrid'].getSelection();
        var selmaterial = w2ui['materialGrid'].getSelection();

        if (selmaterial.lenght == 0) {

            return;
        }


        for (var i in selmaterial) {
            var item = w2ui['itemGrid'].get(selitem);
            var item1 = w2ui['materialGrid'].get(selmaterial[i]);

            let mergedObj = {};
            debugger;
            mergedObj.recid = w2ui['buyGrid'].getNextNegCode();
            mergedObj.cd_project_build_schedule_tests = item.cd_project_build_schedule_tests;
            mergedObj.nr_sample_quantity = item.nr_sample_quantity;
            mergedObj.nr_goal = item.nr_goal;
            mergedObj.dt_deadline = item1.dt_deadline;
            mergedObj.cd_tr_wi_data = item.cd_tr_wi_data;

            mergedObj.cd_human_resource_record =<?php echo($cd_human_resource_record) ?>;
            mergedObj.ds_project_build_full = item.ds_project_build_full;
            mergedObj.ds_test_type = item.ds_test_type;
            mergedObj.ds_test_item = item.ds_test_item;
            mergedObj.nr_calculated_quantity = item1.nr_quantity;
            mergedObj.nr_requested_quantity_to_buy = item1.nr_quantity;
            mergedObj.MaterialPN = item1.MaterialPN;
            mergedObj.cd_equipment_design = item1.cd_equipment_design;
            mergedObj.ds_equipment_design = item1.ds_equipment_design;

            console.info(mergedObj);

            w2ui['buyGrid'].add(mergedObj);
            w2ui['buyGrid'].setItemAsChanged(mergedObj.recid, ['cd_project_build_schedule_tests', 'nr_sample_quantity', 'nr_goal', 'dt_deadline', 'cd_tr_wi_data', 'cd_human_resource_record', 'ds_project_build_full',
                'ds_test_type', 'ds_test_item', 'nr_calculated_quantity', 'nr_requested_quantity_to_buy', 'cd_equipment_design']);

        }
    }

    function copyAll() {

        w2ui['materialGrid'].selectAll();
        copySelected();

        w2ui['materialGrid'].selectNone();
    }

    function removeSelected() {

        var selec = w2ui['buyGrid'].getSelection();
        if (selec.lenght == 0) {
            return;
        }
        for (var i in selec) {


            w2ui['buyGrid'].remove(selec[i]);
        }
    }

    function removeAll() {

        w2ui['buyGrid'].clear();
    }

    // funcoes iniciais;
    dsPurWIObject.start();


    // insiro colunas;

</script>
<div class="row" id='allArea'>

    <?php echo($tab); ?>
</div>


<div id="reqarea">
    <div class="col-md-12">
        <div class="col-md-12 col-lg-12 no-padding">
            <div style="height: 50%;width: 100%;" id="itemGridDiv"></div>
        </div>
        <div class="row">
            <div class="col-md-5 no-padding">
                <div style="height:50%;width: 100%;margin-left:15px;" id="materialGridDiv"></div>
            </div>


            <div class="col-md-7 no-padding">
                <div style="width:35px; float: left; display: block;height: 180px;margin-top: 5% ;margin-left: 30px">
                    <button type="button" class="btn btn-default btn-block" id="btnMoveRight"><i
                                class="fa fa-angle-right"></i></button>
                    <button type="button" class="btn btn-default btn-block" id="btnBatchMoveRight"><i
                                class="fa fa-angle-double-right"></i></button>
                    <button type="button" class="btn btn-default btn-block" id="btnMoveLeft"><i class="fa fa-angle-left"
                        ></i></button>
                    <button type="button" class="btn btn-default btn-block" id="btnBatchMoveLeft"><i
                                class="fa fa-angle-double-left"></i></button>
                </div>

                <div style="height: 50%;width: calc(100% - 100px) ;margin-left:20px; float: left; display: block "
                     id="buyGridDiv"></div>
            </div>
        </div>
    </div>
</div>

<div id="releaseBuyArea">
    <div class="col-md-12">
        <div class="col-md-12 col-lg-12 no-padding">
            <div style="height: 50%;width: 100%;" id="releaseBuyGridDiv"></div>
        </div>
    </div>

</div>
