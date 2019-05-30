<?php
//setPLRelCode
?>

<style>
    .tool-container{
        z-index: 10000 !important;
    }
</style>

<div id="user-options" class="hidden"><a href="#"><i class="fa fa-search-plus uptoolbarshow" ></i></a><a href="#"><i class="fa fa-download uptoolbardownload" ></i></a></div>

<script>



// aqui tem os scripts basicos. 
//var controllerName = "country";


    var dsFormPrCMTjObject = new function () {

        // variaveis privadas;

        var thisObj = this;
        thisObj.gridName = undefined;
        thisObj.action = '<?php echo($action); ?>'
        thisObj.lastPrjCode = -20;
        thisObj.cd_project_build_schedule = <?php echo($cd_project_build_schedule); ?>;
        thisObj.cd_project_build_schedule_comments_answer = <?php echo($cd_project_build_schedule_comments_answer_x); ?>;
        thisObj.cc = <?php echo(json_encode($cc, JSON_NUMERIC_CHECK)); ?>;
        thisObj.answer = <?php echo(json_encode($answer, JSON_NUMERIC_CHECK)); ?>;
        thisObj.typeDoc = <?php echo($typeDoc); ?>;
        thisObj.attachChanges = <?php echo(json_encode($attachChanges, JSON_NUMERIC_CHECK)); ?>;
        thisObj.atachFull = <?php echo(json_encode($atachFull, JSON_NUMERIC_CHECK)); ?>;

        this.start = function () {

<?php
echo($toolbar);
echo($attachmentGrid);
?>

            var vtoolbar = vGridToCmtPrj.toolbar;

            vtoolbar.onClick = function (a, b) {
                if (a == 'update') {
                    thisObj.updateData();
                }

            };
            vtoolbar.name = 'PrjCommentsFormToolbar';

            if (w2ui['PrjCommentsFormToolbar'] != undefined) {
                w2ui['PrjCommentsFormToolbar'].destroy();
            }

            $('#prjCmtToolbar').w2toolbar(vtoolbar);

            var func;
            var vcontroller;
            func = 'updateComments/' + thisObj.cd_project_build_schedule;
            vcontroller = 'tti/project_comments';
            thisObj.whichArea = 'M';

            thisObj.Form = $('#formComment').cgbForm({updController: vcontroller, updFunction: func, updRemoveDescFields: false});

            select2Start('ds_human_resource_cc_form', 'human_resource_controller/retPickListMail', '', 'cgbDestroyThis')
            var vcc = [];

            if (!$.isArray(thisObj.cc)) {
                thisObj.cc = JSON.parse(thisObj.cc);
            }

            $.each(thisObj.cc, function (i, v) {
                vcc.push({id: v.cd_human_resource, text: v.ds_human_resource});
            });

            select2Data('ds_human_resource_cc_form', vcc);

            thisObj.uploaderControl = new cCreateGridUploader('gridAttachmentComments');
            thisObj.uploaderControl.makeTypeSelection('tbarTitle', 'cd_project_model_document_repository_type', 'ds_project_model_document_repository_type', thisObj.typeDoc);
            thisObj.uploaderControl.setAfterInsertFunction(thisObj.setCommentsAfterIns);


//this.makeTypeSelection = function(gridname, beforeid, codefield, descfield, list) {

            this.setScreenPermissions();

            this.addListeners();
            this.addHelper();

            thisObj.makeScreenInformation();

        }




        this.addHelper = function () {
            var arrayHelper = [];
            //$.merge(arrayHelper, introAddFilterArea());
            //$.merge(arrayHelper,w2ui[thisObj.gridName].toolbar.getIntroHelp());
            //$.merge(arrayHelper, w2ui[thisObj.gridName].getIntroHelp());

            //introAddNew({steps: arrayHelper});
        }


        // adicao de listeners!
        this.addListeners = function () {

            $(window).on('close.SBSModalVaratt', function () {
                w2ui['gridAttachmentComments'].destroy();
                $(window).off('close.SBSModalVaratt');
            })

            $('#formComment').on('pospicklist', function (ev) {
                if (ev.fielddata.codefield == 'cd_project_comments_type') {
                    thisObj.Form.setItem('ds_send_to_form', ev.record.ds_users);
                }
            })

            $('#formComment').on('prepicklist', function (ev) {
                if (ev.fielddata.codefield == 'cd_project_power_type') {
                }

            });

            $('#formComment').on('itemChanged', function (ev) {
                var vf = ev.fielddata.name;
            });

            $('#formComment').on('afterUpdate', function (ev) {
            });

        }



        // roda antes de fechar (se retornar FALSE o sistema vai perguntar se quer fechar
        this.beforeClose = function () {
            return true;
        }


        // close object (lugar para destruir as coisas//
        this.close = function () {
            $('.cgbDestroyThis').remove();
            
            //introRemove();
            return true;
        }


        this.setCommentsAfterIns = function (recid) {
            w2ui['gridAttachmentComments'].setItem(recid, 'cd_project_build_schedule_comments', thisObj.Form.getItem('cd_project_build_schedule_comments_form'));
            w2ui['gridAttachmentComments'].setItem(recid, 'cd_project_build_schedule', thisObj.cd_project_build_schedule);



        }

        this.updateData = function () {
            if (!thisObj.Form.checkDemandedWithMSG()) {
                return;
            }
            var vnum = -10;
            var vdata = thisObj.Form.getChanges();

            var vccx = select2GetData('ds_human_resource_cc_form');
            var vcc = [];

            $.each(vccx, function (i, v) {
                vnum--;
                vcc.push({cd_project_build_schedule_comments: vdata.recid, cd_human_resource: v.id, ds_human_resource: v.text, recid: vnum});
            });

            vdata.cc = vcc;
            vdata.cd_project_build_schedule_comments_answer = thisObj.cd_project_build_schedule_comments_answer;
            vdata.cd_human_resource = <?php echo($cd_user) ?>;
            vdata.ds_human_resource = '<?php echo($ds_user) ?>';
            vdata.cd_project_build_schedule = '<?php echo($cd_project_build_schedule) ?>';
            vdata.attachChanges = w2ui['gridAttachmentComments'].getChanges();
            w2ui['gridAttachmentComments'].mergeChanges();
            vdata.atachFull = w2ui['gridAttachmentComments'].records;

            picklistCallBackatt(vdata);
            setTimeout(function () {
                SBSModalVaratt.close()

            }, 0);

        }

        this.makeScreenInformation = function () {
            var vnegCode = 0;
            $.each(thisObj.atachFull, function (i, v) {
                if (w2ui['gridAttachmentComments'].get(v.recid) == null) {
                    if (v.recid < vnegCode) {
                        vnegCode = v.recid;
                    }
                    w2ui['gridAttachmentComments'].add(v);
                } else {
                    w2ui['gridAttachmentComments'].set(v.recid, v);
                }
            });

            if (vnegCode < 0) {
                w2ui['gridAttachmentComments'].insNeg = vnegCode - 1;
            }

            $.each(thisObj.attachChanges, function (i, v) {
                $.each(v, function (ii, vv) {
                    w2ui['gridAttachmentComments'].setItem(v.recid, ii, vv);
                });
            });


            if (thisObj.cd_project_build_schedule_comments_answer != -1) {
                $('.commentDivider').removeClass('hidden col-md-12').addClass('col-md-6');
                $('.commentDivider label').removeClass('hidden');
                
                thisObj.Form.setItem('ds_comments_answer_form', thisObj.answer[0].ds_comments);

                if (thisObj.action == 'I') {
                    select2Data('ds_human_resource_cc_form', [{id: thisObj.answer[0].cd_human_resource, text: thisObj.answer[0].ds_human_resource}]);
                }

            }

        }

        this.setScreenPermissions = function () {
            
            if (this.action == 'R') {

                thisObj.Form.disableAll()
                select2ReadOnly('ds_human_resource_cc_form', true);
                w2ui['gridAttachmentComments'].readOnly();
            }
        }

        this.resizeGrid = function () {
        }

        this.setGridAsChanged = function () {

        }

        this.setDemandedAsChanged = function () {
        }

        this.ToolbarBuildAttachment = function (bPressed) {

            if (bPressed == 'insert') {
                //$('#filesUpload').click();
                thisObj.uploaderControl.uploadData();
            }

            if (bPressed == "delete") {
                w2ui['gridAttachmentComments'].deleteRow();
            }

            if (bPressed == "downloadselected") {
                w2ui['gridAttachmentComments'].downloadSelectedDocFiles();
            }

            
        };

    }

// funcoes iniciais;
    dsFormPrCMTjObject.start();


// insiro colunas;

</script>

<div id="divPrjCommentForm" style="max-height: calc(100vh - 40px);" class="" > 
    <div class="row">
        <div id="prjCmtToolbar" style="width: 100%;" class="toolbarStyle" ></div>
    </div>


    <form id="formComment" class="form-horizontal">

        <div class='row'>
            <div class="hidden">
                <div class="col-sm-2">
                    <input type="text" class="form-control input-sm"   value="<?php echo($recid) ?>" fieldname="cd_project_build_schedule_comments" id="cd_project_build_schedule_comments_form"  mask="PK"  >
                </div>
            </div>

            <div class="row">
                <label for="ds_project_comments_type_form" class="col-sm-1 control-label "><?php echo($formTrans_ds_project_comments_type) ?>:</label>
                <div class="col-sm-11">
                    <input type="text" class="form-control input-sm"   plcode="<?php echo($cd_project_comments_type) ?>"  value="<?php hecho($ds_project_comments_type) ?>" fieldname="ds_project_comments_type" id="ds_project_comments_type_form" mask="PLD" model = "<?php echo ($this->encodeModel('tti/project_comments_type_model')); ?>" fieldname="ds_project_comments_type" code_field="cd_project_comments_type"  relid="-1" relCode ="-1" type="text" must="Y">
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 no-padding commentDivider">
                    <label for="ds_comments_form" class="col-sm-12 control-label hidden " style="text-align: left"><?php echo($formTrans_ds_comments) ?>:</label>
                    <div class="col-sm-12">
                        <textarea class="form-control input-sm" rows="6" fieldname="ds_comments" id="ds_comments_form" mask="c" must="Y"><?php echo($ds_comments) ?></textarea>
                    </div>
                </div>

                <div class="col-md-12 no-padding hidden commentDivider">
                    <label for="ds_comments_answer_form" class="col-sm-12 control-label" style="text-align: left"><?php echo($formTrans_ds_comments_answer) ?>:</label>
                    <div class="col-sm-12">
                        <textarea class="form-control input-sm" rows="6" fieldname="ds_comments_answer" id="ds_comments_answer_form" mask="c" ro="Y"></textarea>
                    </div>
                </div>


            </div>

            <div class="row">
                <label for="ds_send_to_form" class="col-sm-1 control-label "><?php echo($formTrans_ds_send_to_form) ?>:</label>
                <div class="col-sm-11">
                    <span type="text" class="form-control input-sm"  fieldname="ds_send_to" id="ds_send_to_form" mask="G"  relid="-1" relCode ="-1" style="background-color: rgb(230,230,230)"><?php echo($ds_send_to); ?></span>
                </div>
            </div>

            <div class="row">
                <label for="ds_human_resource_cc_form" class="col-sm-1 control-label "><?php echo($formTrans_ds_project_human_resource_cc) ?>:</label>
                <div class="col-sm-11">
                    <input type="text" class="form-control input-sm"   fieldname="ds_human_resource_cc" id="ds_human_resource_cc_form" mask="XXX" model = "<?php echo ($this->encodeModel('human_resource_model')); ?>" fieldname="ds_human_resource" code_field="cd_human_resource" multiple="Y"  relid="-1" relCode ="-1" type="text">
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div id="gridAttachmentComments_div" style="height: 150px;width: 100%"></div>
                </div>
            </div>
        </div>




    </form>
</div>



