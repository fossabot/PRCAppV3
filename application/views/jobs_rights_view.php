
<script>
    var dsMainObject = new function () {

        // variaveis privadas;

        var thisObj = this;

        // funcao de inicio;
        this.start = function () {

<?php echo($javas); ?>

            this.selectedAreaColumn = '';
            this.copyFromRecid = -1;
            this.copyFromName = '';
            this.canEdit = <?php echo ($jsonParam) ?>;


            this.addListeners();
            var vData = w2ui['grdRoles'].getSelection();

            if (vData.length > 0) {
                this.setData(vData[0]);
            }
        }

        // funcao de toolbar;
        this.ToolBarClick = function (bPressed, dData) {
            
            if (bPressed == 'retrieve') {
                 w2ui['grdRoles'].retrieve ({controller: 'jobs_maint', retrFunc: 'retrPermData', ask: false})
            }
            
            if (bPressed == 'openHm') {
                this.openRolebyHM();
            }


            if (bPressed == 'paste') {
                this.paste();
            }

            if (bPressed == 'copy') {
                this.copy();
            }

            if (bPressed == "openmenu") {
                var ret = w2ui['grdRoles'].getSelection();

                if (ret.length == 0) {
                    return;
                }


                this.openMenuEdit(ret[0]);
            }

        }

        // adicao de listeners!
        this.addListeners = function () {

            w2ui['grdRoles'].on('rowFocusChanging', function (event) {
                w2ui['grdHM'].clear();

                event.onComplete = function (e) {

                    if (e.recid_new === -1) {
                        return;
                    }

                    //console.log('changing', e.recid_new);
                    thisObj.setData(e.recid_new);

                }

            });


        }

        this.setData = function (recid) {
            w2ui['grdHM'].clear();

            thisObj.setDataOnGrid('grdHM', recid, 'ds_hm');



        }

        this.copy = function () {

            var vrec = w2ui['grdRoles'].getPk();
            if (vrec == -1) {
                return;
            }



            this.copyFromRecid = vrec;
            this.copyFromName = w2ui['grdRoles'].getItem(vrec, 'ds_jobs');

        }

        this.paste = function () {
            //console.log(bcopyfact, bcopydiv, bcopycust, bcopyjob);


            var vinformed = 0, vlabels = '';
            var vrecto = w2ui['grdRoles'].getPk();

            if (this.copyFromRecid == -1) {
                messageBoxError('<?php echo ($nouserselected); ?> ');
                return;
            }

            if (vrecto == this.copyFromRecid) {
                return;
            }



            var vname = w2ui['grdRoles'].getItem(vrecto, 'ds_jobs');
            var vcopyfrom = this.copyFromRecid;


            var message = '<?php echo($confpaste) ?> ' + this.copyFromName + ' <?php echo($to) ?> ' + vname + ' ?';

            messageBoxYesNo(message, function () {
                thisObj.proceedCopy(vcopyfrom, vrecto)
            });



            //this.copyFromRecid = vrec;
            //this.copyFromName = w

        }

        this.proceedCopy = function (hmfrom, hmto, copyfact, copydiv, copycust, vcopyjob) {
            var vurl = 'jobs_maint/mergePermissions/' + hmfrom + '/' + hmto;

            $.myCgbAjax({
                url: vurl,
                dataType: 'json',
                success: function (data) {
                    if (data.length === 0) {
                        return;
                    }

                    w2ui['grdRoles'].set(data[0].recid, data[0]);

                    thisObj.setData(data[0].recid);


                }
            });

        }

        this.setDataOnGrid = function (grname, recid, jsonCol) {
            var vjson = w2ui['grdRoles'].getItem(recid, jsonCol);

            if (vjson == undefined) {
                vjson = [];
            }

            if (grname == 'grdFactory') {
                //console.log('fact', vjson);
            }
            w2ui[grname].add(vjson);
        }

        // roda antes de fechar (se retornar FALSE o sistema vai perguntar se quer fechar
        this.beforeClose = function () {

            var bHasChanges;



            return true;
        }

        // close object (lugar para destruir as coisas//
        this.close = function () {
            //w2ui[thisObj.gridName].destroy();
            return true;
        }


        // funcaoes gerais 

        this.openMenuEdit = function (recidm) {
            var codePK = recidm;
            var sit = 'menu/editPermission/J/' + codePK;
            openFormUiBootstrap('<?php echo ($menumaint); ?>', sit, 'col-md-8 col-md-offset-2');
        }



        this.openRolebyHM = function () {
            var relController = '<?php echo ($this->encodeModel('Human_resource/retGridJsonByJob')); ?>';
            var relUpdSBSModel = '<?php echo ($this->encodeModel('Human_resource/updSBSRelByJob')) ?>';
            var title = '<?php echo($vHm) ?>';
            this.selectedAreaColumn = 'ds_hm';


            var idsel = w2ui['grdRoles'].getSelection();

            if (idsel.length == 0) {
                return;
            }

            basicSelectSBS(title, relController, relUpdSBSModel, idsel[0]);
        }


        this.refreshData = function (records) {
            var recid = w2ui['grdRoles'].getPk();
            w2ui['grdRoles'].setItem(recid, this.selectedAreaColumn, records);
            this.setData(recid);
        }


    }

// funcoes iniciais;
    dsMainObject.start();

// funcao da toolbar
    function onGridToolbarPressed(bPressed, dData) {
        dsMainObject.ToolBarClick(bPressed, dData);
    }



// insiro colunas;

</script>

<script>

    function setGrpGridHeight() {
        var hAvail = getWorkArea();
        var vh = hAvail * 0.90;
        //vh_bottom = hAvail * 0.41;
        //console.log('teste', vxx1, vxx2 );    


        $("#grdRolesDiv").css("height", vh);
        $("#grdHMDiv").css("height", vh);



        w2ui['grdRoles'].resize();
        w2ui['grdHM'].resize();


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



    function posSBSClosed(hasChanges, records) {
        if (hasChanges) {
            dsMainObject.refreshData(records);
        }
    }

// insiro colunas;

</script>
<div id="myGrid" style="height: auto;" class='row'> 

    <div class="col-md-6" style='padding: 5px;' >

        <div class='box  box-info box-solid' id='grdRolesBox'>
            <div class='box-header with-border'>
                <h4 class='box-title'><?php echo($vJob) ?></h4>
                <div class='box-tools pull-right'>   
                    <button type='button' class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-compress'></i></button>
                </div>
            </div>
            <div class='box-body'>
                <div id="grdRolesDiv" style="width: 100%" > </div>
            </div>
        </div>

    </div>


    <div class="col-md-6" style='padding: 5px;'>

        <div class='box  box-info box-solid' style='padding' id="grdHMBox" >
            <div class='box-header with-border'>
                <h4 class='box-title'><?php echo($vHm) ?></h4>
                <div class='box-tools pull-right'>   
                    <button type='button' class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-compress'></i></button>
                </div>
            </div>
            <div class='box-body'>
                <div id="grdHMDiv" style="width: 100%" > </div>
            </div>
        </div>

    </div>

</div>
