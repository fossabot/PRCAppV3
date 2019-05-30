<style>
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        /* display: none; <- Crashes Chrome on hover */
        -webkit-appearance: none;
        margin: 0; /* <-- Apparently some margin are still there even though it's hidden */
    }
</style>

<script>
// aqui tem os scripts basicos. 

//$(".ds_hr_type").on( "change",  function() {


    var dsMainWOObject = new function () {

        // variaveis privadas;

        var thisObj = this;
        thisObj.gridName = undefined;

        // funcao de inicio;
        this.start = function (gridNamePar) {
            thisObj.gridName = gridNamePar;
            thisObj.cdtst = <?php echo($tst) ?>;
            thisObj.cdsch = <?php echo($cd_project_build_schedule) ?>;

            if (w2ui[thisObj.gridName] !== undefined) {
                w2ui[thisObj.gridName].destroy();
            }

<?php echo ($javascript); ?>



            var vitems = $.extend([], w2ui[thisObj.gridName].toolbar.items);

            // adding as third toolbar index;
            vitems.splice(5, 0, {type: 'html', id: 'item5',
                html: '<div style="padding: 3px 10px;">' +
                        ' <?php echo($specificorder) ?>:' +
                        '    <input size="20" type="number" id="searchNewWO" style="padding: 3px; border-radius: 2px; border: 1px solid silver" onkeyup="dsMainWOObject.onKeyUpWO(event);"/>' +
                        '</div>'
            });


            w2ui[thisObj.gridName].toolbar.items = vitems;


            w2ui[thisObj.gridName].refresh();
            this.addListeners();
        }

        this.onKeyUpWO = function (e) {

            // capture enter.
            if (e.keyCode === 13) {
                var vdata = $('#searchNewWO').val();
                if (chkUndefined(vdata, '') == '') {
                    return;
                }
                thisObj.getSpecificWO(vdata);
            }
        }

        this.getSpecificWO = function (wo) {
            var recs = w2ui[thisObj.gridName].find({nr_work_order: wo});
            if (recs.length > 0) {
                messageBoxError('<?php echo($orderexists) ?>');
                return;
            }

            $.myCgbAjax({url: 'schedule/project_build_schedule_tests_wo/getDataByWorkOrder/' + thisObj.cdtst + '/' + wo,
                success: function (data) {
                    if (data.length == 0) {
                        messageBoxError('<?php echo($ordernotfound) ?>');
                        return;
                    }

                    $.each(data, function (i, v) {
                        w2ui[thisObj.gridName].add([v], true);
                        w2ui[thisObj.gridName].setItemAsChanged(v.recid, 'fl_checked');
                    });
                }
            });
        }


        this.selectAll = function () {

            if (w2ui[thisObj.gridName].last.searchIds.length > 0) {
                $.each(w2ui[thisObj.gridName].last.searchIds, function (i, v) {
                    var vrecid = w2ui[thisObj.gridName].records[v].recid;
                    w2ui[thisObj.gridName].setItem(vrecid, 'fl_checked', 1);
                })
                return;
            }


            $.each(w2ui[thisObj.gridName].records, function (i, v) {
                var vrecid = v.recid;
                w2ui[thisObj.gridName].setItem(vrecid, 'fl_checked', 1);
            })

        }

        this.unselectAll = function () {

            if (w2ui[thisObj.gridName].last.searchIds.length > 0) {
                $.each(w2ui[thisObj.gridName].last.searchIds, function (i, v) {
                    var vrecid = w2ui[thisObj.gridName].records[v].recid;
                    w2ui[thisObj.gridName].setItem(vrecid, 'fl_checked', 0);
                })
                return;
            }


            $.each(w2ui[thisObj.gridName].records, function (i, v) {
                var vrecid = v.recid;
                w2ui[thisObj.gridName].setItem(vrecid, 'fl_checked', 0);
            })

        }

        this.addHelper = function () {

        }

        // funcao de toolbar;
        this.ToolBarClick = function (bPressed, dData) {

            if (bPressed == "update") {
                w2ui[thisObj.gridName].update();
            }

            if (bPressed == 'selectall') {
                thisObj.selectAll();
            }
            if (bPressed == 'unselectall') {
                thisObj.unselectAll();
            }




        }


        // adicao de listeners!
        this.addListeners = function () {
// seto o evendo de fechar!!!
            $(window).on("onCloseForm.wo", function (a) {
                SBSModalFormsVar.close();
                $(window).off('onCloseForm.wo');
            });

            w2ui[thisObj.gridName].on('gridChanged', function (e) {

            });


            w2ui[thisObj.gridName].on('update', function (e) {
                e.onComplete = function (ev) {
                    dsFormPrjSheetObject.setPlanningWOData(thisObj.cdtst, thisObj.cdsch, ev.data.griddata);
                }
            });

        }

        // roda antes de fechar (se retornar FALSE o sistema vai perguntar se quer fechar
        this.beforeClose = function () {
            return true;
        }


        // close object (lugar para destruir as coisas//
        this.close = function () {
            w2ui[this.gridName].destroy();
            introRemove();
            return true;
        }

        // funcaoes gerais 

        this.renderMissingRed = function (record, index, column_index) {


            var bcanChange = this.isNewRow(record.recid);
            var vfield = this.columns[column_index].field;
            var vx = this.columns[column_index].field;
            //var vdata = chkUndefined(record[vx], '&nbsp');
            var vdata = chkUndefined(record[vx], '');

            if (vdata == '') {
                vdata = '<div class="" style="background-color: #da3b27; opacity: 0.7; height: 20px;">&nbsp</div>';
            } else {
                vdata = '<div class="" style="">'+vdata+'</div>';
            }
            return vdata;
        }

    }

// funcoes iniciais;
    dsMainWOObject.start('gridwoselect');

// funcao da toolbar
    

// insiro colunas;
// insiro colunas;

</script>
<div class='row'> 
    <div id="gridwoselectdiv" style="height: 300px;width: 100%"></div>

</div>


