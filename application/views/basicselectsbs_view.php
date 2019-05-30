
<style type="text/css">

    #child {
        top: -1px;
        bottom: 0;
        left: 0px;
        right: 0px;
        width: 50px;
        height: 180px;
        margin-top: 170px;
        padding-left: 3px;
    }

    .simpleArrowGo {
        padding-top: 3px; 
        padding-left: 12px;
        border: solid black 1px; 
        width:32px;
        height:34px;
    }

    .simpleArrowBack {
        padding-top: 3px; 
        padding-left: 11px;
        border: solid black 1px; 
        width:32px;
        height:34px;
    }


    .doubleArrowGo {
        padding-top: 3px; 
        padding-left: 10px;
        border: solid black 1px; 
        width:32px;
        height:34px;
    }

    .doubleArrowBack {
        padding-top: 3px; 
        padding-left: 9px;
        border: solid black 1px; 
        width:32px;
        height:34px;
    }


    .btn_pl {
        background: #3498db;
        background-image: -webkit-linear-gradient(top, #3498db, #2980b9);
        background-image: -moz-linear-gradient(top, #3498db, #2980b9);
        background-image: -ms-linear-gradient(top, #3498db, #2980b9);
        background-image: -o-linear-gradient(top, #3498db, #2980b9);
        background-image: linear-gradient(to bottom, #3498db, #2980b9);
        -webkit-border-radius: 7;
        -moz-border-radius: 7;
        border-radius: 7px;
        color: #ffffff;
        text-decoration: none;
    }

    .btn_pl:hover {
        background: #3cb0fd;
        background-image: -webkit-linear-gradient(top, #3cb0fd, #3498db);
        background-image: -moz-linear-gradient(top, #3cb0fd, #3498db);
        background-image: -ms-linear-gradient(top, #3cb0fd, #3498db);
        background-image: -o-linear-gradient(top, #3cb0fd, #3498db);
        background-image: linear-gradient(to bottom, #3cb0fd, #3498db);
        text-decoration: none;
    }

    .btn_pl:active {
        border: solid black 0px; 
    }



</style>
<div style="display: inline-block;width: 100%">
    <div class="col-md-12">
        <div class='row'>
            <div class="modal-header-picklist_cgb"> <?php echo ($title) ?> <i class='modal-header-picklist-close_cgb fa fa-close' onclick='closeSBSScreen();'> </i></div>
        </div>

        <div class='row'>
            <div id='divSBStoolbar' style='height: 30px; width: 100%;background-color: white;'> </div>
        </div>

        <div class='row'>

            <div id='divSBSAvailable' class='col-md-6 small-padding' style='height: 550px; '> </div>  

            <div id='mainSBSPLButtons' class='col-md-1' style='height:270px;' >
                <div id ='SBSPLButtons' style='width:38px;height:270px;' > 
                    <div id='child'>
                        <i class="fa fa-angle-right fa-2x simpleArrowGo btn_pl" onclick="sendtoSelected();"></i>
                        <i class="fa fa-angle-double-right fa-2x doubleArrowGo btn_pl" onclick="sendAllToSelected();"></i>

                        <i class="fa fa-angle-left fa-2x btn_pl simpleArrowBack" onclick="sendtoAvailable();"></i>
                        <i class="fa fa-angle-double-left fa-2x doubleArrowBack btn_pl" onclick="sendAllToAvailable();"></i>
                    </div>
                </div>
            </div>
            <div id='divSBSSelected' class='col-md-5  small-padding' style='height: 550px;'> </div>
        </div>
    </div>
</div>

<script>
// aqui tem os scripts basicos. 
    if (w2ui["modalAvailSide"] !== undefined) {
        w2ui["modalAvailSide"].destroy();
    }

    if (w2ui["modalSelectedSide"] !== undefined) {
        w2ui["modalSelectedSide"].destroy();
    }

    if (w2ui["modalAvailSide"] !== undefined) {
        w2ui["modalAvailSide"].destroy();
    }

    if (w2ui["sbstoolbar"] !== undefined) {
        w2ui["sbstoolbar"].destroy();
    }



    var varSBSisSaving = false;
    var sbsMainId = <?PHP echo ($MainId); ?>;
    var varSBSLastSaved = {};
    gridVarAvailSide = {
        name: "modalAvailSide",
        header: 'Available',
        multiSelect: true,
        show: {
            header: true,
            footer: true,
            toolbar: true,
            toolbarSearch: true,
            toolbarReload: false,
        },
        onDblClick: function (event) {
            this.select(event.recid);
            sendtoSelected();
            event.preventDefault();
            //selectData(event.recid);
        },
        onClick: function (event) {
        },
        columns: [{"caption": "<?php echo ($code); ?>", "size": "80px", "field": "recid", "sortable": true, "style": "text-align: center;", hidden: true},
            {"caption": "<?php echo ($description); ?>", "size": "100%", "field": "ds_description", "sortable": true, "style": " text-transform:uppercase ; "}
        ],
        records: <?php echo ($recordsavail); ?>
    };

    gridVarSelectedSide = {
        name: "modalSelectedSide",
        header: 'Selected',
        multiSelect: true,
        show: {
            header: true,
            footer: true,
            toolbar: true,
            toolbarSearch: true,
            toolbarReload: false,
        },
        onDblClick: function (event) {
            this.select(event.recid);
            sendtoAvailable();
            event.preventDefault();
        },
        columns: [{"caption": "<?php echo ($code); ?>", "size": "80px", "field": "recid", "sortable": true, "style": "text-align: center;", hidden: true},
            {"caption": "<?php echo ($description); ?>", "size": "100%", "field": "ds_description", "sortable": true, "style": " text-transform:uppercase ; "}
        ],
        records: <?php echo ($recordsselected); ?>
    };

    $('#divSBSSelected').w2grid(gridVarSelectedSide);
    $('#divSBSAvailable').w2grid(gridVarAvailSide);


<?php echo ($javascript); ?>


    function sendAllToSelected() {
        w2ui['modalAvailSide'].selectAll();
        sendtoSelected();
    }

    function sendtoSelected() {
        var selec = w2ui['modalAvailSide'].getSelection();
        if (selec.lenght == 0) {
            return;
        }
        for (var i in selec) {

            var item = w2ui['modalAvailSide'].get(selec[i])

            if (item.fl_checked == 1) {
                gridResetItem(item, w2ui['modalAvailSide']);
                item = w2ui['modalAvailSide'].get(selec[i])

            } else {
                w2ui['modalAvailSide'].setItem(item.recid, 'ds_description', item.ds_description);
                //gridSetItem(item, 'ds_description', item.ds_description, w2ui['modalAvailSide']);
                item = w2ui['modalAvailSide'].get(selec[i])
            }


            w2ui['modalSelectedSide'].add(item);
            w2ui['modalAvailSide'].remove(selec[i]);

        }
    }

    function sendAllToAvailable() {
        w2ui['modalSelectedSide'].selectAll();
        sendtoAvailable();
    }


    function sendtoAvailable() {
        var selec = w2ui['modalSelectedSide'].getSelection();
        if (selec.lenght == 0) {
            return;
        }
        for (var i in selec) {

            var item = w2ui['modalSelectedSide'].get(selec[i])

            // se jah estava checked = 0,e tah voltando para avaiable, reseto!
            if (item.fl_checked == 0) {
                gridResetItem(item, w2ui['modalSelectedSide']);
                item = w2ui['modalSelectedSide'].get(selec[i]);

            } else {
                //gridSetItem(item, 'ds_description', item.ds_description, w2ui['modalSelectedSide']);
                w2ui['modalSelectedSide'].setItem(item.recid, 'ds_description', item.ds_description);

                item = w2ui['modalSelectedSide'].get(selec[i]);

            }

            w2ui['modalAvailSide'].add(item);
            w2ui['modalSelectedSide'].remove(selec[i]);
        }
    }

    function updateSBSData() {
        SBSContr = getSBSUpdModel();
        modelUpd = '<?PHP echo ($modelUpd); ?>';

        if (SBSContr == '') {
            return;
        }

        //if (w2ui['modalAvailSide'].getChanged)
        var SBSavailChanges = w2ui['modalAvailSide'   ].getChanges();
        var SBSSelectedChanges = w2ui['modalSelectedSide'].getChanges();

        if (SBSavailChanges.length == 0 && SBSSelectedChanges.length == 0) {
            return;
        }

        varSBSisSaving = true;

        $.post(
                SBSContr,
                {"remove": JSON.stringify(SBSavailChanges),
                    "add": JSON.stringify(SBSSelectedChanges),
                    "modelUpd": modelUpd,
                    "id": sbsMainId,
                },
                function (data) {

                    varSBSisSaving = false;
                    w2utils.unlock($('#main_form_div_picklist'));

                    if (data == "OK") {
                        for (index = 0; index < SBSavailChanges.length; ++index) {
                            w2ui['modalAvailSide'].set(SBSavailChanges[index].recid, {fl_checked: 0});
                        }

                        for (index = 0; index < SBSSelectedChanges.length; ++index) {
                            w2ui['modalSelectedSide'].set(SBSSelectedChanges[index].recid, {fl_checked: 1});
                        }

                        toastSuccess(javaMessages.update_done);

                        w2ui['modalAvailSide'].mergeChanges();
                        w2ui['modalSelectedSide'].mergeChanges();

                        varSBSLastSaved = $.extend([], w2ui['modalSelectedSide'].records);

                        // seto as flags;

                        SBSHasChanges = true;
                    } else {
                        toastErrorBig(javaMessages.error_upd + data);
                    }
                },
                "text"
                );
    }


    $('#divSBStoolbar').w2toolbar({name: 'sbstoolbar',
        items: [
            {id: "SBSupdate", hint: javaMessages.update, icon: "fa fa-floppy-o", caption: "", type: "button"},
            {id: "SBSclose", hint: javaMessages.close_screen, icon: "fa fa-times", caption: "", type: "button"}
        ],
        onClick: function (event) {
            if (event.target == 'SBSclose') {
                closeSBSScreen();
            }
            if (event.target == 'SBSupdate') {
                updateSBSData();
            }
        }

    });

    function closeSBSScreen() {
        var SBSavailChanges = w2ui['modalAvailSide'   ].getChanges();
        var SBSSelectedChanges = w2ui['modalSelectedSide'].getChanges();

        if (SBSavailChanges.length != 0 || SBSSelectedChanges.length != 0) {
            messageBoxOkCancel(javaMessages.info_changed_close, function () {
                $(window).off('resize.SBSPL');
                SBSModalVar.close()
            });
        } else {
            $(window).off('resize.SBSPL');

            SBSModalVar.close();
        }
    }


    $(window).on('resize.SBSPL', function () {
        w2ui["modalAvailSide"].resize();
        w2ui["modalSelectedSide"].resize();
    });


</script>
