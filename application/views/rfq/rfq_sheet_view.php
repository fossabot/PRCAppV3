<style>
    textarea {
        transition: height .3s ease-out;
    }

    textarea:focus {
        height: 10em;


    }


</style>
<?php
$sqlPrd = " AND getUserPermission('fl_rfq_purchase_department', cd_human_resource) = 'Y' ";
$sqlPrd = $this->cdbhelper->getFilterQueryId($sqlPrd);
?>
<script>

    $.fn.cgbApproveControl = function (options) {
        var $selector = $(this);
        var opt = $.extend({
            funcAction: function () {
            }, stepGridName: '', startNew: false, fkColumn: '', master: false, stepType: -1, code: -1
        }, options);
        var vdata = w2ui[opt.stepGridName].records;
        var thisObj = this;
        thisObj.selectReject = [];
        thisObj.basicSelectReject = {};
        thisObj.lastStepRec = undefined;


        var vHTMLArea = '   <div class = "col-md-12 no-padding" style="display: block;" id="cfmAreaCGB">  ' +
                '       <form id="formConfirmCGB">  ' +
                '           <div class="row">  ' +
                '               <div class="col-sm-12">  ' +
                '                   <textarea type="text" class="form-control input-sm" id="ds_notes_form" style="height: 150px;" fieldname="ds_notes" mask="c" maxlength="10000"></textarea>  ' +
                '               </div>  ' +
                '           </div>  ' +
                '          <div class="row no-padding #classjumparea#" > ' +
                '               <div class="#classreject#" style="padding-right: 2px">  ' +
                '                   <button type="button" class="btn btn-danger pull-left" aria-label="Left Align" id="updateRejectCGB" style="width: 100%">  ' +
                '                       <span class="fa fa-thumbs-o-down" aria-hidden="true"></span>  ' +
                '                   </button>  ' +
                '               </div>              ' +
                '               <div class="col-sm-9" style="padding-left: 2px"><input type="hidden" class="form-control" style="padding-right: 0px !important; padding-left: 0px !important;" id="ds_reject_jump_to_cgb"></div>' +
                '          </div>' +
                '           <div class="row">  ' +
                '               <div class="#classapprove#">  ' +
                '                   <button type="button" class="btn btn-info pull-right" aria-label="Left Align" id="updateApproveCGB" style="width: 100%">  ' +
                '                       <span class="fa fa-thumbs-o-up" aria-hidden="true"></span>  ' +
                '                   </button>  ' +
                '               </div>  ' +
                '           </div>  ' +
                '       </form>  ' +
                '  </div>  ';

        this.getActualStepData = function () {
            return thisObj.lastStepRec;
        }

        this.isChanged = function () {
            return w2ui[opt.stepGridName].getChanges().length > 0;
        }

        this.resetChanges = function () {
            w2ui[opt.stepGridName].mergeChanges();
            w2ui[opt.stepGridName].set(thisObj.lastStepRec.recid, thisObj.lastStepRec);
        }


        this.init = function () {

            if (opt.startNew) {
                w2ui[opt.stepGridName].setItem(vdata[0].recid, opt.fkColumn, vdata[0][opt.fkColumn]);
                w2ui[opt.stepGridName].setItem(vdata[0].recid, 'cd_approval_steps_config', vdata[0]['cd_approval_steps_config']);
            }

            // if inserting, first need to be changed

            var $varea = $selector;
            $varea.empty();

            var html = '<ul class="steps"><li class="history" id="myHistory"><div><button class="btn btn-sm btn-info" style="width: 20px; height: 52px;margin-top: -20px;padding-left: 1px; padding-right: 1px;" data-toggle="tooltip" title="History" data-placement="right"  onclick="return false;"><i class="fa fa-bars" aria-hidden="true" style="font-size: 16px;"></i></button></div></li>';
            var vspanstyle = '';
            var vstepsbefore = [];
            var vstepselected = {};
            var vIsFinished = false;
            var vHasRightsFinished = false;

            $.each(vdata, function (i, v) {
                vIsFinished = false;
                vHasRightsFinished = false;
                var vclass = '';
                var vsecondline = '&nbsp';
                var vcommentarea = '';
                var vremarks = chkUndefined(v.ds_remarks, '');
                var vtext = v.ds_approval_steps_config;

                // exists but not defined;
                if (v.recid > 0 && chkUndefined(v.cd_approval_status, -1) == -1) {
                    vclass = 'active';
                    thisObj.lastStepRec = v;
                    if (v.fl_has_rights == 'Y' || opt.master) {
                        vtext = '<a href="#" class="btnCanChangeState" index="' + i + '" return false;">' + vtext + '</a>';
                    }
                }

                if (v.recid > 0 && v.cd_approval_status == 1) {
                    vIsFinished = true;
                    vHasRightsFinished = v.fl_has_rights == 'Y' || opt.master;
                    vclass = 'done';
                    vsecondline = v.dt_define + ' - ' + v.ds_human_resource_define;

                    var vnextisopen = false;
                    if (i < vdata.length - 1) {
                        vnextisopen = vdata[i + 1].recid > 0 && chkUndefined(vdata[i + 1].cd_approval_status, -1) == -1;
                    }

                    if (v.fl_can_jump_here_after_reject == 1 || vnextisopen) {
                        vstepsbefore.push({id: v.cd_approval_steps_config, text: v.ds_approval_steps_config});

                        if (vnextisopen) {
                            vstepselected = {id: v.cd_approval_steps_config, text: v.ds_approval_steps_config};
                        }

                    }

                }

                if (v.recid > 0 && v.cd_approval_status == 2) {
                    vclass = 'rejected';
                    vsecondline = v.dt_define + ' - ' + v.ds_human_resource_define;
                }


                if (vclass == '') {
                    vclass = 'pending';
                }


                html = html + '<li class="' + vclass + '" ><span ' + vspanstyle + '><div>' + vtext + '</div><div style="font-weight: normal;text-align: center">' + vsecondline + '</div></span></li>';

            });

            // if no last step means everything is done... so will be the last one.
            if (thisObj.lastStepRec == undefined) {
                thisObj.lastStepRec = vdata[vdata.length - 1];
            }


            if (vIsFinished && vHasRightsFinished) {
                html = html + '<li class="history" id="myReopenTasks"><div><button class="btn btn-sm btn-success" style="width: 20px; height: 52px;margin-top: -20px;margin-left: 10px;padding-left: 1px; padding-right: 1px;" data-toggle="tooltip" title="Reopen" data-placement="right"  onclick="return false;"><i class="fa fa-recycle" aria-hidden="true" style="font-size: 16px;"></i></button></div></li>';
            }


            thisObj.selectReject = vstepsbefore;
            thisObj.basicSelectReject = vstepselected;

            html = html + '</ul>';

            $varea.append(html);
            $varea.find('.btnCanChangeState').on('click', function () {
                thisObj.openSelector($(this).attr('index'), this);
            });

            $('#myHistory').on('click', function () {
                thisObj.openHistory($(this));
            });
            $('#myReopenTasks').on('click', function () {

                messageBoxYesNo('Do you Confirm Reopen the Process ?', function () {
                    thisObj.updateStatus(true, '', -50);
                });


            });


        }

        this.openSelector = function (i, obj) {
            var vclassapprove = 'col-xs-12';
            var vclassreject = 'col-xs-3';
            var vclassrejectjump = "";

            if (vdata[i].fl_show_approve == 'N') {
                vclassapprove = 'hidden';
                vclassreject = 'col-xs-3';
            }

            if (vdata[i].fl_show_reject == 'N') {
                vclassreject = 'hidden';
                vclassrejectjump = 'hidden';
                vclassapprove = 'col-xs-12';
            }

            var vh = vHTMLArea.replace('#classapprove#', vclassapprove).replace('#classreject#', vclassreject).replace('#classjumparea#', vclassrejectjump);

            basicPickListOpenPopOver({
                title: vdata[i].ds_approval_steps_config,
                target: $(obj).closest('li'),
                html: vh,
                plVarSuffix: 'StepConf',
                showClose: true,
                position: 'auto',
                width: '350px',
                functionOpen: thisObj.afterSelectorOpen,
                plCallBack: function (code, desc, data) {

                }
            });

        }

        this.openHistory = function (obj) {
            var vwidth = Math.round($(window).width() * 0.7);
            basicPickListOpenPopOver({
                title: 'History',
                target: $(obj),
                controller: 'approval_steps_config/openHistory/' + opt.stepType + '/' + opt.code,
                plVarSuffix: 'StepConfHistory',
                showClose: true,
                position: 'auto',
                width: vwidth + 'px',
                functionOpen: function () {
                    dsHistoryObject.start();

                },
                plCallBack: function (code, desc, data) {

                }
            });
        }


        thisObj.afterSelectorOpen = function () {

            thisObj.FormConf = $('#formConfirmCGB').cgbForm();

            $('#updateApproveCGB').on('click', function () {
                var comments = chkUndefined(thisObj.FormConf.getItem('ds_notes_form'), '');

                if (thisObj.getActualStepData().fl_must_add_reason == 1 && comments == '') {
                    messageBoxError('It is comming from a rejection, so you must add the reason');
                    return;
                }

                thisObj.updateStatus(true, comments, -1);
            });

            $('#updateRejectCGB').on('click', function () {
                var comments = chkUndefined(thisObj.FormConf.getItem('ds_notes_form'), '');

                if (comments == '') {
                    messageBoxError('You must add the reason');
                    return;
                }

                var vtowhere = select2GetData('ds_reject_jump_to_cgb');

                thisObj.updateStatus(false, comments, vtowhere.id);
            });

            $('#ds_reject_jump_to_cgb').select2({
                data: thisObj.selectReject,
                theme: 'bootstrap',
                classToDestroy: 'vapprovalreject'
            });
            select2Val('ds_reject_jump_to_cgb', thisObj.basicSelectReject.id);


            //thisObj.selectReject = vstepsbefore;
            //thisObj.basicSelectReject = vstepselected;

        }

        thisObj.updateStatus = function (appr, cmts, vrejectjump) {
            if (thisObj.FormConf != undefined) {
                thisObj.FormConf.destroy();
                $('#ds_reject_jump_to_cgb').select2('destroy');
                $('.vapprovalreject').remove();
                SBSModalVarPopupStepConf.close();
            }

            opt.funcAction(appr, cmts, thisObj.lastStepRec, vrejectjump);
        }

        this.init();

        return this;
    }

    // document.onkeyup=function(event){
    //     event = event || window.event;
    //     kc=event.keyCode||event.charCode;
    //     if (event.altKey && kc == 83){
    //         console.log("update");
    //         // this.updateData();
    //         dsFormRfqSheetObject.Form.updateForm();
    //         // vGridToToolbarRfq.toolbar.updateData();
    //          }
    // }
    $("#formRfq").keyup(function (event) {
        event = event || window.event;
        var kc = event.keyCode || event.charCode;
        if (event.altKey && kc == 83) {
            console.log("update");
            // this.updateData();
            dsFormRfqSheetObject.Form.updateForm();
            // vGridToToolbarRfq.toolbar.updateData();
        }
        // console.log( "Handler for .keyup() called.", event );
    });

</script>

<style>
    table.dataTable tbody tr:hover {
        background-color: lightcyan !important;
    }


</style>

<?php
//setPLRelCode
?>

<script>
    // aqui tem os scripts basicos.
    //var controllerName = "country";


    //$(".ds_hr_type").on( "change",  function() {


    var dsFormRfqSheetObject = new function () {

        // variaveis privadas;

        var thisObj = this;
        thisObj.gridName = undefined;
        thisObj.action = '<?php echo($action); ?>'
        thisObj.lastRfqCode = -20;
        thisObj.cd_item_to_open = -1;
        thisObj.whatToOpen = -1;
<?php // 1 - Open Item Supplier , 2 - Open RFQ Supplier, 3 - Open DOc Repository -- Used for after saving, 4 - Excel file  5-PR Data -6 Download data ; 7-Department               ?>
        thisObj.canChange = '<?php echo($canChange); ?>';
        thisObj.isMaster = '<?php echo($isMaster); ?>';
        thisObj.canSeeSupplier = '<?php echo($canSeeSupplier); ?>';
        thisObj.firstStep = '<?php echo($firstStep); ?>';
        thisObj.excelOption = 1;
        thisObj.returnToBrowse = false;


        thisObj.fullReload = false;
        thisObj.cd_rfq = <?php echo($cd_rfq); ?>;

        this.start = function () {

<?php
echo($toolbar);
echo($gridSteps);
?>

            $().w2grid(vStepsGrid);

            vtoolbar = vGridToToolbarRfq.toolbar;

            vtoolbar.onClick = function (a, b) {
                if (a == 'update') {
                    thisObj.updateData();
                }
                if (a == 'insert') {
                    thisObj.addNewItem();
                }

                if (a == 'opensupplier') {
                    thisObj.openSuppliers();
                }
                if (a == 'openprinfo') {
                    thisObj.openPRData();
                }

                if (a == 'openDep') {
                    thisObj.openDepForm();
                }

                if (a == 'openQuotationHistory') {
                    thisObj.openQuotHisForm();
                }


                if (a == 'excel:allitems') {
                    thisObj.excelOption = 1;
                    if (thisObj.Form.isChanged()) {
                        thisObj.whatToOpen = 4;
                        vtoolbar.onClick('update');
                        return;
                    }

                    thisObj.openExcel();
                }


                if (a == 'excel:quoteitems') {
                    thisObj.excelOption = 2;
                    if (thisObj.Form.isChanged()) {
                        thisObj.whatToOpen = 4;
                        vtoolbar.onClick('update');
                        return;
                    }

                    thisObj.openExcel();
                }

                if (a == 'excel:noquoteitems') {
                    thisObj.excelOption = 3;
                    if (thisObj.Form.isChanged()) {
                        thisObj.whatToOpen = 4;
                        vtoolbar.onClick('update');
                        return;
                    }

                    thisObj.openExcel();
                }


                if (a == 'downloaddata:allitems') {
                    thisObj.excelOption = 1;
                    if (thisObj.Form.isChanged()) {
                        thisObj.whatToOpen = 6;
                        vtoolbar.onClick('update');
                        return;
                    }

                    thisObj.openDownload();
                }

                if (a == 'downloaddata:quoteitems') {
                    thisObj.excelOption = 2;
                    if (thisObj.Form.isChanged()) {
                        thisObj.whatToOpen = 6;
                        vtoolbar.onClick('update');
                        return;
                    }

                    thisObj.openDownload();
                }

                if (a == 'downloaddata:noquoteitems') {
                    thisObj.excelOption = 3;
                    if (thisObj.Form.isChanged()) {
                        thisObj.whatToOpen = 6;
                        vtoolbar.onClick('update');
                        return;
                    }

                    thisObj.openDownload();
                }

                if (a == 'showall' || a == 'showquote' || a == 'shownoquote') {
                    setTimeout(thisObj.changeView, 0);
                }


            };


            vtoolbar.name = 'RfqFormToolbar';

            if (w2ui['RfqFormToolbar'] != undefined) {
                w2ui['RfqFormToolbar'].destroy();
            }

            $('#rfqToolbar').w2toolbar(vtoolbar);

            thisObj.Form = $('#formRfq').cgbForm({updController: 'rfq/rfq', checkDemandedForInvisible: true});

            this.setScreenPermissions();

            this.addListeners();
            this.addHelper();

            thisObj.Form.addGridToControl('stepsGrid');




            thisObj.makeScreenInformation();
            thisObj.setScreenPermissions();
            thisObj.setImages();
            setTimeout(function () {

                thisObj.resize();
            }, 0);

            this.setImageAction();

            $('#divForTable').fixTableHeader();


            this.calculateTotals();


        }

        this.calculateTotals = function () {
            var ids = thisObj.Form.getIdbyFieldname('nr_total_default_currency');
            var rfqTotalCurrency = 0;

            for (var i = 0; i < ids.length; i++) {
                var vx = thisObj.Form.getItem(ids[i]);
                if (!$.isNumeric(vx)) {
                    vx = 0;
                }
                rfqTotalCurrency += parseFloat(vx);
            }

            $("#idRfqTotalCurrency").text(w2utils.formatNumber(rfqTotalCurrency.toFixed(2)));//填充内容
        }


        this.addHelper = function () {
            var arrayHelper = [];
            //$.merge(arrayHelper, introAddFilterArea());
            //$.merge(arrayHelper,w2ui[thisObj.gridName].toolbar.getIntroHelp());
            //$.merge(arrayHelper, w2ui[thisObj.gridName].getIntroHelp());

            //introAddNew({steps: arrayHelper});
        }

        this.setImages = function () {
            var timestamp = $.now();
            $('#tableRfqItemlDiv img').each(function () {
                var actualsrc = $(this).attr('src');
                $(this).attr('src', $(this).attr('originalsrc') + '/' + timestamp);
            })
        }

        this.setImageAction = function () {

            $('#tableRfqItemlDiv img').css('cursor', 'zoom-in').each(function () {
                var vid = $(this).attr('id');
                $('#' + vid).on("click", function () {
                    //openImageIdSrc(vid, 'x');

                    var vd = $('#' + vid).attr('src');
                    vd = vd.replace("Thumbs", "");
                    var vheight = Math.round(($(window).height() * 0.80));
                    var vhtml = '<div style="height: ' + vheight + 'px"> <img src="' + vd + '" class="img-responsive"style="max-height: ' + vheight + 'px;margin: 0 auto"></div>';
                    $.dialog({
                        title: false,
                        content: vhtml,
                        columnClass: 'col-md-12 messageBoxCGBClass',
                        theme: 'supervan',
                        backgroundDismiss: true,
                        onOpenBefore: function () {
                            this.$el.css('z-index', '1000000105');
                        },

                        buttons: false
                    });

                })

            })

        }


        // adicao de listeners!
        this.addListeners = function () {

            $(window).on('updateDocRep', function () {
                thisObj.setImages();
            });

            $('#formRfq').on('pospicklist', function (ev) {

                var vorder = ev.fielddata.order;

                if (ev.fielddata.codefield == 'cd_equipment_design') {

                    var vactremark = chkUndefined(thisObj.Form.getItem('ds_remarks_' + vorder + '_form'), '');
                    var vactbrand = chkUndefined(thisObj.Form.getItem('ds_brand_' + vorder + '_form'), '');
                    var vactunit = chkUndefined(thisObj.Form.getItemPLCode('ds_unit_measure_' + vorder + '_form'), -1);

                    if (vactunit == -1 && chkUndefined(ev.record.cd_unit_measure, -1) != -1) {
                        thisObj.Form.setItemPL('ds_unit_measure_' + vorder + '_form', ev.record.cd_unit_measure, ev.record.ds_unit_measure);
                    }

                    if (vactremark == '' && chkUndefined(ev.record.ds_technical_description, '') != '') {
                        thisObj.Form.setItem('ds_remarks_' + vorder + '_form', ev.record.ds_technical_description);
                    }

                    if (vactbrand == '' && chkUndefined(ev.record.ds_brand, '') != '') {
                        thisObj.Form.setItem('ds_brand_' + vorder + '_form', ev.record.ds_brand);
                    }


                    $('#s2id_' + ev.id + ' .select2-choice').attr('data-original-title', ev.newDesc);
                    $('#s2id_' + ev.id + ' .select2-choice').tooltip({container: 'body'});

                    //record
                }


            }
            );

            $('#formRfq').on('prepicklist', function (ev) {

            });

            $('#formRfq').on('prepicklist', function (ev) {
                if (ev.fielddata.codefield == "cd_equipment_design") {

                }
            });


            $('#formRfq').on('itemChanged', function (ev) {


            });

            $('#formRfq').on('afterUpdate', function (ev) {
                thisObj.action = 'E';

                //thisObj.makeScreenInformation();
                thisObj.resize();

                if (thisObj.cd_item_to_open != -1 && thisObj.whatToOpen == 1) {
                    thisObj.openSuppliersItems(thisObj.cd_item_to_open);
                    thisObj.cd_item_to_open = -1;
                    thisObj.whatToOpen = -1;
                }

                if (thisObj.whatToOpen == 2) {
                    thisObj.whatToOpen = -1;
                    thisObj.openSuppliers();
                }

                if (thisObj.whatToOpen == 5) {
                    thisObj.whatToOpen = -1;
                    thisObj.openPRData();
                }


                if (thisObj.cd_item_to_open != -1 && thisObj.whatToOpen == 3) {
                    thisObj.openDocRep(thisObj.cd_item_to_open);
                    thisObj.cd_item_to_open = -1;
                    thisObj.whatToOpen = -1;
                }

                if (thisObj.whatToOpen == 4) {
                    thisObj.whatToOpen = -1;
                    thisObj.openExcel();
                }

                if (thisObj.whatToOpen == 6) {
                    thisObj.whatToOpen = -1;
                    thisObj.openDownload();
                }

                if (thisObj.whatToOpen == 7) {
                    thisObj.whatToOpen = -1;
                    thisObj.openDepForm();
                }

                if (thisObj.whatToOpen == 8) {
                    thisObj.whatToOpen = -1;
                    thisObj.openQuotHisForm();
                }

                $(window).trigger('rfqChanged', ev.fullData);

                if (thisObj.returnToBrowse) {
                    $('#tab_browse a').click();
                    return;
                }



                if (thisObj.fullReload) {
                    thisObj.fullReload = false;
                    dsMainObject.loadDetails(<?php echo($cd_rfq); ?>, true);
                } else {
                    thisObj.setImages();
                }



            });

            $('#formRfq').on('errorUpdate', function (ev) {
                thisObj.cd_item_to_open = -1;
                thisObj.whatToOpen = -1;

                if (thisObj.stepControl.isChanged()) {
                    thisObj.stepControl.resetChanges();
                }

            });

        }

        this.changeView = function () {
            var vshowall = w2ui['RfqFormToolbar'].get('showall').checked;
            var vshowquotation = w2ui['RfqFormToolbar'].get('showquote').checked;
            var vshownoquotation = w2ui['RfqFormToolbar'].get('shownoquote').checked;

            $('.purallrows').show();
            if (vshowall) {
                return;
            }

            var vfields = thisObj.Form.getInfobyFieldname('nr_count_quote');
            $.each(vfields, function (i, v) {
                var vvalue = chkUndefined(thisObj.Form.getItem(v.id), 0);
                if (vvalue > 0 && vshownoquotation) {
                    $('#rfqitem' + v.order).slideUp(100);
                }

                if (vvalue == 0 && vshowquotation) {
                    $('#rfqitem' + v.order).hide();
                }
            })


        }

        this.openExcel = function () {
            window.open('rfq/rfq/makeExcel/' + thisObj.cd_rfq + '/' + thisObj.excelOption, '_blank');
        }

        this.openDownload = function () {
            window.open('rfq/rfq/createFilesAttached/' + thisObj.cd_rfq + '/' + thisObj.excelOption, '_blank');
        }


        this.addNewItem = function () {
            var vrfq = thisObj.Form.getPk();


            $.myCgbAjax({
                url: 'rfq/rfq/addNewItem/' + vrfq,
                success: function (data) {

                    var $d = $(data.html);
                    //thisObj.table.row.add($d).draw();
                    $('#tableRfqItemlDiv tbody').append($d);
                    $('#divForTable').animate({scrollTop: $('#divForTable').height()}, 'fast');
                    thisObj.Form.addNewElements();
                    $('#tableRfqItemlDiv').find('[data-toggle="tooltip"]').tooltip({container: 'body'});
                    thisObj.setScreenPermissions();

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


        this.updateData = function () {
            thisObj.Form.updateForm();
        }

        this.makeScreenInformation = function () {
            $('#tableRfqItemlDiv').find('[data-toggle="tooltip"]').tooltip({container: 'body'});

            var vbtnexisting = 2;

            if (vbtnexisting == 0) {
                //  thisObj.table.column('toolbar:name').visible(false);

            }

            thisObj.stepControl = $('#stepsArea').cgbApproveControl({
                funcAction: this.updateStatus,
                stepGridName: 'stepsGrid',
                startNew: (this.action == 'I'),
                fkColumn: 'cd_rfq',
                'master': thisObj.isMaster == 'Y',
                stepType: '<?php echo($steptype); ?>',
                code: thisObj.cd_rfq
            });


        }

        this.setScreenPermissions = function () {
            if (thisObj.canChange == 'N') {
                thisObj.Form.disableAll();
                $('.btnrfqdel').hide();
            }

            if (thisObj.firstStep == 'N') {
                $('.btnrfqdel').hide();


            }

            if (thisObj.canSeeSupplier == 'N') {
                //$('.btnrfqsupplier').hide();
            }
        }


        this.setGridAsChanged = function () {

        }

        this.setDemandedAsChanged = function () {
        }

        this.ToolbarGrid = function (bPressed) {
            if (bPressed == 'insert') {
                ;
            }

            if (bPressed == "delete") {

            }
        };

        this.remove = function (async) {
            $(window).off('updateDocRep');
            thisObj.Form.destroy(async);
        }

        this.resize = function () {
            var hAvail = getWorkArea();
            var hHeader = $('#headerArea').height();
            var vsteps = $('#stepsArea').height();

            var vtable = hAvail - hHeader - vsteps - 100;
            if (hAvail - hHeader - vsteps < 100) {
                vtable = 400;
            }

            $('#divForTable').height(vtable);


            //thisObj.table.columns.adjust().draw();
        }


        this.openPRData = function () {
            var vx = thisObj.Form.getPk();
            if (thisObj.Form.isChanged()) {
                thisObj.whatToOpen = 5;
                vtoolbar.onClick('update');
                return;
            }

            var title = 'PR';
            openFormUiBootstrap(
                    title,
                    'rfq/rfq_pr_group/openForm/' + vx,
                    'col-lg-12 col-lg-offset-0 col-sm-12 col-sm-offset-0'
                    );

        }

        this.openDepForm = function () {
            var vx = thisObj.Form.getPk();
            if (thisObj.Form.isChanged()) {
                thisObj.whatToOpen = 7;
                vtoolbar.onClick('update');
                return;
            }

            var title = 'Department';
            openFormUiBootstrap(
                    title,
                    'rfq/rfq_cost_center/openDepForm/' + vx,
                    'col-lg-12 col-lg-offset-0 col-sm-12 col-sm-offset-0'
                    );

        }

        this.openQuotHisForm = function () {
            var vx = thisObj.Form.getPk();
            if (thisObj.Form.isChanged()) {
                thisObj.whatToOpen = 8;
                vtoolbar.onClick('update');
                return;
            }

            var title = 'History Quotation ';
            openFormUiBootstrap(
                    title,
                    // 'rfq/rfq_supplier/openRfqSupplier/' + vx,
                    'rfq/rfq_quotation_history/openQuotHisForm/' + vx,
                    'col-lg-12 col-lg-offset-0 col-sm-12 col-sm-offset-0'
                    );

        }


        this.openSuppliers = function () {
            var vx = thisObj.Form.getPk();
            if (thisObj.Form.isChanged()) {
                thisObj.whatToOpen = 2;
                vtoolbar.onClick('update');
                return;
            }

            var title = 'Suppliers / Quotations';
            openFormUiBootstrap(
                    title,
                    'rfq/rfq_supplier/openRfqSupplier/' + vx,
                    'col-lg-12 col-lg-offset-0 col-sm-12 col-sm-offset-0'
                    );

        }


        this.openSuppliersItems = function (item) {
            var vx = thisObj.Form.getItem('ds_equipment_design_' + item + '_form');
            var vx2 = thisObj.Form.getItem('ds_equipment_design_code_complement_' + item + '_form');
            var vx3 = thisObj.Form.getItem('ds_equipment_design_desc_complement_' + item + '_form');

            if (thisObj.Form.isChanged()) {
                thisObj.whatToOpen = 1;
                thisObj.cd_item_to_open = item;
                vtoolbar.onClick('update');
                return;
            }


            var title = vx;
            if (chkUndefined(vx2, '') != '') {
                title = title + ' - ' + vx2;
            }

            if (chkUndefined(vx3, '') != '') {
                title = title + ' - ' + vx3;
            }

<?php if ($canFinance) { ?>
                var vsize = 'col-lg-10 col-lg-offset-1 col-md-12 col-md-offset-0';
<?php } else { ?>
                var vsize = 'col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1';
<?php } ?>

            openFormUiBootstrap(
                    title,
                    'rfq/rfq_item_supplier/openItemSupplier/' + item,
                    vsize
                    );


        }

        this.openDocRep = function (item) {
            if (thisObj.Form.isChanged()) {
                thisObj.whatToOpen = 3;
                thisObj.cd_item_to_open = item;
                vtoolbar.onClick('update');
                return;
            }

            openRepository({id: 1, code: item});

        }

        this.deleteItem = function (item) {
            var vx = thisObj.Form.getItem('ds_equipment_design_' + item + '_form');
            $('#rfqitem' + item).css('background-color', 'red');

            messageBoxYesNo(javaMessages.deleteMsg + '<br>' + vx, function () {
                $('#rfqitem' + item).slideUp();
                thisObj.Form.setItemPL('ds_rfq_request_type_' + item + '_form', -2, '');
                thisObj.Form.setItemPL('ds_equipment_design_' + item + '_form', -2, '10');
                thisObj.Form.setItemPL('ds_rfq_request_type_' + item + '_form', -2, '10');
            }, function () {
                $('#rfqitem' + item).css('background-color', '');
            });
        }


        this.updateStatus = function (approved, comments, data, vrejectjump) {

            var vrecid = data.recid;
            var vap = 1;

            if (!approved) {
                vap = 2;
            }
            w2ui['stepsGrid'].setItem(vrecid, 'cd_approval_status', vap);
            w2ui['stepsGrid'].setItem(vrecid, 'cd_approval_steps_config_jump_to', vrejectjump);

            if (chkUndefined(comments, '') != '') {
                w2ui['stepsGrid'].setItem(vrecid, 'ds_remakrs', comments);
            }

            thisObj.returnToBrowse = true;
            thisObj.updateData();
        }


        this.autoSupplier = function (item) {
            var vb = chkUndefined(thisObj.Form.getItem('nr_qtty_to_buy_' + item + '_form'), 0);
            if (vb == 0) {
                messageBoxError('You must set the quantity to Buy');
                return;
            }

            $.myCgbAjax({
                url: 'rfq/rfq_item/autoSupplier/' + item + '/' + vb,
                success: function (data) {

                    thisObj.Form.setItem('nr_qtty_to_buy_' + item + '_form', data.rs[0].nr_qtty_to_buy);
                    thisObj.Form.setItem('ds_supplier_' + item + '_form', data.rs[0].ds_supplier);
                    thisObj.Form.setItem('nr_total_default_currency_' + item + '_form', data.rs[0].nr_total_default_currency);
                    thisObj.Form.setItem('ds_dep_cost_' + item + '_form', data.rs[0].ds_dep_cost);
                    thisObj.Form.setItem('fl_buy_' + item + '_form', data.rs[0].fl_buy);

                    thisObj.checkAutoButton(item);
                }
            });
        }

        this.checkAutoButton = function (item) {
            var vbid = '#autosup' + item;
            var $vb = $(vbid);
            if ($vb.length == 0) {
                return;
            }

            var vbuy = thisObj.Form.getItem('fl_buy_' + item + '_form');

            if (vbuy == 1) {
                $vb.hide();
                thisObj.Form.setEnabled('nr_qtty_to_buy_' + item + '_form', false);
            } else {
                $vb.show();
                thisObj.Form.setEnabled('nr_qtty_to_buy_' + item + '_form', true);
            }

        }


    }

    // funcoes iniciais;
    dsFormRfqSheetObject.start();


    // insiro colunas;

</script>

<div id="divRfqForm" class="">
    <div class="col-md-12 no-padding">
        <div id="rfqToolbar" style="width: 100%;" class="toolbarStyle"></div>
    </div>


    <form id="formRfq" class="form-horizontal">

        <div class="row" id="headerArea">


            <div class="col-sm-2" style="display: none">
                <input type="text" class="form-control input-sm" value="<?php hecho($cd_rfq) ?>" fieldname="cd_rfq"
                       id="cd_rfq_form" mask="PK">
            </div>

            <label for="ds_human_resource_applicant_form"
                   class="col-sm-1 control-label "><?php echo($formTrans_cd_human_resource_applicant) ?>:</label>
            <div class="col-sm-2">
                <input type="text" class="form-control input-sm" plcode="<?php echo($cd_human_resource_applicant) ?>"
                       value="<?php hecho($ds_human_resource_applicant) ?>" fieldname="ds_human_resource_applicant"
                       id="ds_human_resource_applicant_form" mask="PLD"
                       model="<?php echo($this->encodeModel('human_resource_model')); ?>"
                       fieldname="ds_human_resource_applicant" code_field="cd_human_resource_applicant" relid="-1"
                       relCode="-1" type="text" must="Y" sc="<?php echo($sc); ?>" ro="Y">
            </div>

            <label for="dt_request_form" class="col-sm-1 control-label "><?php echo($formTrans_dt_request) ?>:</label>
            <div class="col-sm-1">
                <input type="text" class="form-control input-sm" value="<?php hecho($dt_request) ?>"
                       fieldname="dt_request" id="dt_request_form" must="Y" sc="<?php echo($sc); ?>" ro="Y">
            </div>

            <label for="dt_requested_complete_form"
                   class="col-sm-1 control-label "><?php echo($formTrans_dt_requested_complete) ?>:</label>
            <div class="col-sm-1">
                <input type="text" class="form-control input-sm" value="<?php hecho($dt_requested_complete) ?>"
                       fieldname="dt_requested_complete" id="dt_requested_complete_form" must="Y">
            </div>

            <label for="dt_deactivated_form" class="col-sm-1 control-label "><?php echo($formTrans_dt_deactivated) ?>
                :</label>
            <div class="col-sm-1">
                <input type="text" class="form-control input-sm" value="<?php hecho($dt_deactivated) ?>"
                       fieldname="dt_deactivated" id="dt_deactivated_form">
            </div>

            <label for="ds_cancel_reason_form"
                   class="col-sm-1 control-label "><?php echo($formTrans_ds_cancel_reason) ?>:</label>
            <div class="col-sm-2">
                <input type="text" class="form-control input-sm" value="<?php hecho($ds_cancel_reason) ?>"
                       fieldname="ds_cancel_reason" id="ds_cancel_reason_form" mask="c" type="text" maxlength="">
            </div>

            <label for="fl_is_urgent_form" class="col-sm-1 control-label "><?php echo($formTrans_fl_is_urgent) ?>
                :</label>
            <div class="col-sm-1">
                <input type="checkbox" class="form-control input-sm" value="<?php hecho($fl_is_urgent) ?>"
                       fieldname="fl_is_urgent" id="fl_is_urgent_form" mask="CHK">
            </div>


            <label for="ds_human_resource_purchase_form"
                   class="col-sm-1 control-label "><?php echo($formTrans_cd_human_resource_purchase) ?>:</label>
            <div class="col-sm-1">
                <input type="text" class="form-control input-sm" plcode="<?php echo($cd_human_resource_purchase) ?>"
                       value="<?php hecho($ds_human_resource_purchase) ?>" fieldname="ds_human_resource_purchase"
                       id="ds_human_resource_purchase_form" mask="PLD"
                       model="<?php echo($this->encodeModel('human_resource_model')); ?>"
                       fieldname="ds_human_resource_purchase" code_field="cd_human_resource_purchase"
                       relid="<?php echo($sqlPrd) ?>" relCode="1" type="text" must="Y">
            </div>

            <label for="ds_comments_form" class="col-sm-1 control-label "><?php echo($formTrans_ds_comments) ?>:</label>
            <div class="col-sm-2">
                <input type="text" class="form-control input-sm" value="<?php hecho($ds_comments) ?>"
                       fieldname="ds_comments" id="ds_comments_form" mask="c" type="text" maxlength="">
            </div>

            <label for="ds_wf_number_form" class="col-sm-1 control-label "><?php echo($formTrans_ds_wf_number) ?>
                :</label>
            <div class="col-sm-1">
                <input type="text" class="form-control input-sm" value="<?php hecho($ds_wf_number) ?>"
                       fieldname="ds_wf_number" id="ds_wf_number_form" mask="c">
            </div>

            <label for="ds_rfq_number_form" class="col-sm-1 control-label "><?php echo($formTrans_ds_rfq_number) ?>
                :</label>
            <div class="col-sm-2">
                <input type="text" class="form-control input-sm" value="<?php hecho($ds_rfq_number) ?>"
                       fieldname="ds_rfq_number" id="ds_rfq_number_form" mask="c" type="text" maxlength="32">
            </div>

            <div class="hidden">
                <label for="dt_deactivated_form"
                       class="col-sm-1 control-label "><?php echo($formTrans_dt_deactivated) ?>:</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control input-sm" value="<?php hecho($dt_deactivated) ?>"
                           fieldname="dt_deactivated" id="dt_deactivated_form">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-info box-solid" id="gridRfqModel">
                    <div class="box-header with-border">
                        <h3 class="box-title"> <?php echo($itemsTitle) ?> </h3>
                        <div class="box-tools pull-right">
                            <button type='button' class='btn btn-box-tool' data-widget="collapse"><i
                                    class='fa fa-compress'></i></button>
                        </div>
                    </div>


                    <div class="box-body">
                        <div class="col-md-12" style="overflow-x:auto; overflow-y: auto" id="divForTable">
                            <table id="tableRfqItemlDiv" class="compact table table-striped table-bordered nowrap hover"
                                   style="margin: 0 auto;clear: both;border-collapse: collapse;table-layout: fixed; word-wrap:break-word;">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;"></th>
                                        <th style='width: 170px;'><span
                                                style="color: rgb(0, 0, 255)"><?php echo($formTrans_cd_equipment_design); ?></span>
                                            / <?php echo($formTrans_ds_equipment_design_code); ?></th>
                                        <th style='width: 100px;'><span
                                                style="color: rgb(0, 0, 255)"><?php echo($formTrans_cd_rfq_request_type); ?></span>
                                            / <?php echo($formTrans_ds_brand); ?></th>
                                        <th style="width: 100px;"><?php echo($formTrans_nr_qtty_quote); ?>
                                            / <?php echo($formTrans_nr_estimated_annual); ?></th>
                                        <th style="width: 100px;"><?php echo($formTrans_dt_deadline); ?></th>
                                        <th style="width: 130px;"><?php echo($formTrans_ds_website); ?></th>
                                        <th style="width: 150px;"><?php echo($formTrans_ds_reason_buy); ?></th>
                                        <th style="width: 150px;"><?php echo($formTrans_ds_remarks); ?></th>
                                        <th style="width: 100px;"><?php echo($formTrans_ds_attached_image); ?></th>
                                        <th style="width: 120px;"><?php echo($formTrans_fl_need_sample); ?></th>
                                        <th style="width: 100px;"><?php echo($formTrans_fl_buy); ?></th>

                                        <?php if ($canFinance) { ?>
                                            <th style="width: 130px;"><?php echo($formTrans_supplier_info); ?></th>
                                            <th style="width: 200px;"><?php echo($formTrans_depcost_info); ?></th>
                                        <?php } ?>
                                        <th style="width: 100px;"><?php echo($formTrans_supplier_leadtime); ?></th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php echo($htmlItem) ?>
                                </tbody>
                                <?php if ($canFinance) { ?>

                                    <tfoot>
                                        <tr>
                                            <td  style="text-align:right;font-weight:bold">Total</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td style="text-align:right;font-weight:bold">RMB</td>
                                            <td id="idRfqTotalCurrency"  style="text-align:right;font-weight:bold"></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                <?php } ?>

                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12" style="padding-left: 20px;" id="stepsArea">

            </div>
        </div>


    </form>
</div>


