<?php 
$sqlPwd = ' AND ( EXISTS (SELECT 1 FROM "DEPARTMENT_ACCOUNT_CODE_COST_CENTER" x WHERE x.cd_department_account_code = "DEPARTMENT_ACCOUNT_CODE".cd_department_account_code and x.cd_department_cost_center = %s ) OR NOT EXISTS (SELECT 1 FROM "DEPARTMENT_ACCOUNT_CODE_COST_CENTER" x WHERE x.cd_department_account_code = "DEPARTMENT_ACCOUNT_CODE".cd_department_account_code ) ) ';
$sqlPwd = $this->cdbhelper->getFilterQueryId($sqlPwd);
?>

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
        thisObj.openPK = -1;
        this.gridDetail = [];

<?php echo($det) ?>

        this.start = function () {

<?php
echo($SupModelGrid);
?>


            this.setScreenPermissions();

            this.addListeners();
            this.addHelper();

            this.resizeGrid();
            //$('#mainSupTabsDiv').ctabStart({afterChanged: thisObj.tabSupAfterChanged});//({afterChanged: thisObj.tabAfterChanged, beforeChange: thisObj.tabBeforeChanged});

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
            var vhaschanges = w2ui['supplierGrid'].getChanges().length > 0;
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
                }
            });

            w2ui['supplierGrid'].on('gridChanged', function (e) {
            });
            
            
            w2ui['supplierGrid'].on('pickList', function (e) {
                if (e.columnname == 'ds_department_account_code') {
                    var vdep = w2ui['supplierGrid'].getItem(e.recid, 'cd_department_cost_center');
                    
                    var vrel = {id:vdep, idwhere: <?php echo($sqlPwd)?>};
                    e.relation = vrel;

                    e.onComplete = function(event) {
                        
                        w2ui['supplierGrid'].setItem(e.recid, 'cd_expense_type', event.dataRec.cd_expense_type);
                        w2ui['supplierGrid'].setItem(e.recid, 'ds_expense_type', event.dataRec.ds_expense_type);
                    }

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
        }

        this.setScreenPermissions = function () {
        }


        this.resizeGrid = function () {

            var hAvail = getWorkArea();

            $('#supplierGridDiv').height(hAvail - 50)
            w2ui['supplierGrid'].refresh();

        }

        this.setGridAsChanged = function () {

        }

        this.setDemandedAsChanged = function () {
        }

        this.expandData = function (event) {
            $('#' + event.box_id).height(224);

            $('#' + event.box_id).html('<div style="width: 100%;height: 220px; padding-right: 24px; background-color: #CCDCF0"><div style="width: 100%;height: 200px" id="detailarea' + event.recid + '"></div></div>');

            var vx = $.extend({}, vGridDetails);
            vx.name = 'vDetails' + event.recid;
            vx.records = JSON.parse(w2ui['supplierGrid'].getItem(event.recid, 'json_quo'));

            if (w2ui[vx.name] != undefined) {
                w2ui[vx.name].destroy();
            }
            $('#detailarea' + event.recid).w2grid(vx);
            thisObj.gridDetail.push(vx.name);

            setTimeout(function () {
                w2ui['supplierGrid'].resize();
                $.each(thisObj.gridDetail, function (i, v) {
                    if (w2ui[v] != undefined) {
                        w2ui[v].resize();
                    }

                })
            }, 500);



        }

        this.openExcel = function () {
            window.open('rfq/rfq_pr_group/createFilesAttached/' + thisObj.cd_rfq, '_blank');
        }


        this.ToolbarGrid = function (a) {
            if (a == 'update') {
                w2ui['supplierGrid'].update();
            }

            if (a == 'excel') {
                thisObj.openExcel();
            }


            if (a == 'close') {
                thisObj.closeScreen();
            }
        }


    }

// funcoes iniciais;
    dsRFQSupObject.start();


// insiro colunas;

</script>

<div id="divSupForm" style="max-height: calc(100vh - 40px);" class="" > 
    <div class="row">
        <div class="col-md-12 no-padding"> <div style="height: 400px;width: 100%" id="supplierGridDiv"> </div></div>
    </div>


</div>