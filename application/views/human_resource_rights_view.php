
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

            this.setArea();

            this.addListeners();
            var vData = w2ui['grdHmresource'].getSelection();

            if (vData.length > 0) {
                this.setData(vData[0]);
            }
        }

        // funcao de toolbar;
        this.ToolBarClick = function (bPressed, dData) {

            if (bPressed == 'retrieve') {
                w2ui['grdHmresource'].retrieve({controller: 'users_maint', retrFunc: 'retrPermData', ask: true})
            }

            if (bPressed == 'openjob') {
                this.openHMbyJob();
            }
            
            if (bPressed == 'update') {
                w2ui['grdHmresource'].update({updFunc: 'updateData', controller: 'users_maint', retrieveAfter: false});
            }

            if (bPressed == 'paste') {
                this.paste();
            }

            if (bPressed == 'copy') {
                this.copy();
            }

            if (bPressed == "openmenu") {
                ret = w2ui['grdHmresource'].getSelection();

                if (ret.length == 0) {
                    return;
                }


                this.openMenuEdit(ret[0]);
            }

        }

        // adicao de listeners!
        this.addListeners = function () {

            w2ui['grdHmresource'].on('rowFocusChanging', function (event) {
                w2ui['grdJob'].clear();
                w2ui['grdLocation'].clear();

                event.onComplete = function (e) {

                    if (e.recid_new === -1) {
                        return;
                    }

                    //console.log('changing', e.recid_new);
                    thisObj.setData(e.recid_new);

                }

            });

            w2ui['grdLocation'].on('gridChanged', function (a) {
                thisObj.setChangesOnMainGrid();
            });

        }

        this.setChangesOnMainGrid = function () {
            var vpk = w2ui['grdHmresource'].getPk();
            if (vpk == -1) {
                return;
            }

            w2ui['grdLocation'].mergeChanges();
            var vx = w2ui['grdLocation'].getResultsetJson();
            w2ui['grdHmresource'].setItem(vpk, 'ds_loc', vx);

        }

        this.setData = function (recid) {
            w2ui['grdJob'].clear();
            w2ui['grdLocation'].clear();
            thisObj.setDataOnGrid('grdJob', recid, 'ds_job');
            thisObj.setDataOnGrid('grdLocation', recid, 'ds_loc');
        }

        this.copy = function () {

            var vrec = w2ui['grdHmresource'].getPk();
            if (vrec == -1) {
                return;
            }



            this.copyFromRecid = vrec;
            this.copyFromName = w2ui['grdHmresource'].getItem(vrec, 'ds_human_resource_full');

        }

        this.paste = function () {
            var bcopyfact = w2ui['grdHmresource'].toolbar.get('copyfact');
            var bcopydiv = w2ui['grdHmresource'].toolbar.get('copydiv');
            var bcopycust = w2ui['grdHmresource'].toolbar.get('copycust');
            var bcopyjob = w2ui['grdHmresource'].toolbar.get('copyjob');

            var scopyfact = 'N';
            var scopycust = 'N';
            var scopydiv = 'N';
            var scopyjob = 'N';


            var vinformed = 0, vlabels = '';
            var vrecto = w2ui['grdHmresource'].getPk();

            if (this.copyFromRecid == -1) {
                messageBoxError('<?php echo ($nouserselected); ?> ');
                return;
            }

            if (vrecto == this.copyFromRecid) {
                return;
            }


            var vname = w2ui['grdHmresource'].getItem(vrecto, 'ds_human_resource_full');
            var vcopyfrom = this.copyFromRecid;


            var message = '<?php echo($confpaste) ?> ' + this.copyFromName + ' <?php echo($to) ?> ' + vname + ' ?';

            messageBoxYesNo(message, function () {
                thisObj.proceedCopy(vcopyfrom, vrecto)
            });



            //this.copyFromRecid = vrec;
            //this.copyFromName = w

        }

        this.proceedCopy = function (hmfrom, hmto) {
            var vurl = 'users_maint/mergePermissions/' + hmfrom + '/' + hmto;

            $.myCgbAjax({
                url: vurl,
                dataType: 'json',
                success: function (data) {
                    if (data.length === 0) {
                        return;
                    }

                    w2ui['grdHmresource'].set(data[0].recid, data[0]);

                    thisObj.setData(data[0].recid);


                }
            });

        }

        this.setDataOnGrid = function (grname, recid, jsonCol) {
            var vjson = w2ui['grdHmresource'].getItem(recid, jsonCol);

            if (vjson == undefined) {
                vjson = [];
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
            var sit = 'menu/editPermission/H/' + codePK;
            openFormUiBootstrap('<?php echo ($menumaint); ?>', sit, 'col-md-8 col-md-offset-2');
        }


        this.openHMbyJob = function () {
            var relController = '<?php echo ($this->encodeModel('job_model/retGridJsonByHM')); ?>';
            var relUpdSBSModel = '<?php echo ($this->encodeModel('job_model/updSBSRelByHM')) ?>';
            var title = '<?php echo($vJob) ?>';
            this.selectedAreaColumn = 'ds_job';


            var idsel = w2ui['grdHmresource'].getSelection();

            if (idsel.length == 0) {
                return;
            }

            basicSelectSBS(title, relController, relUpdSBSModel, idsel[0]);
        }


        this.refreshData = function (records) {
            var recid = w2ui['grdHmresource'].getPk();
            var vc = (w2ui['grdHmresource'].getChanges() > 0);
            w2ui['grdHmresource'].setItem(recid, this.selectedAreaColumn, records);
            if (!vc) {
                w2ui['grdHmresource'].mergeChanges();
            }
            this.setData(recid);


        }


        this.setArea = function () {
            var vShoe = 3;

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


        $("#grdHmresourceDiv").css("height", vh);
        $("#grdJobDiv").css("height", vh);
        $("#grdLocationDiv").css("height", vh);



        w2ui['grdHmresource'].resize();
        w2ui['grdJob'].resize();
        w2ui['grdLocation'].resize();


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

        <div class='box  box-info box-solid' id='grdHmresourceBox'>
            <div class='box-header with-border'>
                <h4 class='box-title'><?php echo($vHm) ?></h4>
                <div class='box-tools pull-right'>   
                    <button type='button' class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-compress'></i></button>
                </div>
            </div>
            <div class='box-body'>
                <div id="grdHmresourceDiv" style="width: 100%" > </div>
            </div>
        </div>

    </div>



    <div class="col-md-3" style='padding: 5px;'>

        <div class='box  box-info box-solid' style='padding' id="grdJobBox" >
            <div class='box-header with-border'>
                <h4 class='box-title'><?php echo($vJob) ?></h4>
                <div class='box-tools pull-right'>   
                    <button type='button' class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-compress'></i></button>
                </div>
            </div>
            <div class='box-body'>
                <div id="grdJobDiv" style="width: 100%" > </div>
            </div>
        </div>

    </div>




    <div class="col-md-3" style='padding: 5px;'>

        <div class='box  box-info box-solid' style='padding' id="grdJobBox" >
            <div class='box-header with-border'>
                <h4 class='box-title'><?php echo($vLocations) ?></h4>
                <div class='box-tools pull-right'>   
                    <button type='button' class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-compress'></i></button>
                </div>
            </div>
            <div class='box-body'>
                <div id="grdLocationDiv" style="width: 100%" > </div>
            </div>
        </div>

    </div>


</div>
