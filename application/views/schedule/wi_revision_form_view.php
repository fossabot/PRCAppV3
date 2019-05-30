
<script type="text/javascript">
    var dsMainObject = new function () {
        var thisObj = this;
        thisObj.gridName = undefined;
        thisObj.action = '<?php echo($action); ?>'
        thisObj.cd_wi_revision = <?php echo($cd_wi_revision); ?>;
        thisObj.level = 2;

        this.start = function () {
            <?php
            echo $toolbar;
            ?>
            var vtoolbar = vGridToToolbarWi.toolbar;

            vtoolbar.onClick = function (a, b) {
                if (a == 'update') {
                    thisObj.updateData();
                }

                if (a == 'delete') {
                    thisObj.deleteWi();

                }

                if (a == 'insert') {
                    dsBasicObject.beforeEditWi({}, {level: thisObj.level, recid: -1});
                }

            };
            vtoolbar.name = 'WiFormToolbar';

            if (w2ui['WiFormToolbar'] != undefined) {
                w2ui['WiFormToolbar'].destroy();
            }

            $('#wiToolbar').w2toolbar(vtoolbar);
            thisObj.Form = $('#myWiForm').cgbForm({updController: 'schedule/wi_revision'});
            $('#myWiForm').on('afterUpdate', function (a) {
                dsBasicObject.addUpdateNode(thisObj.level, thisObj.cd_wi_revision, a.recordset[0].cd_wi);
            });

        };

        this.updateData = function () {
            thisObj.Form.updateForm();
        };

        this.deleteWi = function () {
            messageBoxYesNo('<?php echo($delWi)?>', function () {
                $.myCgbAjax({
                    url: 'schedule/wi_revision/deleteById/' + thisObj.cd_wi_revision,
                    dataType: 'html',
                    success: function (data) {
                        if (data == 'OK') {
                            thisObj.Form.destroy();
                            $('#myWiForm').remove();
                            dsBasicObject.deleteNode(thisObj.level, thisObj.cd_wi_revision);
                        } else {
                            messageBoxError(data);
                        }
                    }
                });
            });
        };

        // roda antes de fechar (se retornar FALSE o sistema vai perguntar se quer fechar
        this.beforeClose = function () {
            return !thisObj.Form.isChanged();
        }

        // close object (lugar para destruir as coisas//
        this.close = function () {
            //introRemove();
            return true;
        }

        this.setScreenPermissions = function () {
            // var vdr = thisObj.Form.getItem("fl_draft_form");
            if (vdr == 0) {
                $.each(thisObj.fieldsToHide, function (i, v) {
                    thisObj.Form.setEnabled(v, false);
                })
            }

            if (thisObj.action == 'I') {
            } else {

            }
        }


    }

    // funcoes iniciais;
    dsMainObject.start();
</script>

<form id="myWiForm" class="form-horizontal">
    <div class="row" style="margin:auto ">
        <div class="modal-header-form_cgb"><?php echo $title; ?></div>
        <input type="hidden" class="form-control input-sm"   value="<?php hecho($cd_wi_revision)?>" fieldname="cd_wi_revision" id="cd_wi_revision_form"  mask="PK" >

        <div id="wiToolbar" class="toolbarStyle" style="border-bottom: none !important"></div>


        <label for="nr_wi_revision_form" class="col-sm-1 control-label "><?php echo($formTrans_nr_wi_revision) ?>:</label>
        <div class="col-sm-2">
            <input type="text" class="form-control input-sm"   value="<?php hecho($nr_wi_revision)?>" fieldname="nr_wi_revision" id="nr_wi_revision_form" mask="I" >
        </div>

        <label for="ds_wi_form" class="col-sm-1 control-label "><?php echo($formTrans_cd_wi) ?>:</label>
        <div class="col-sm-2">
            <input type="text" class="form-control input-sm" plcode="<?php echo($cd_wi)?>"  value="<?php hecho($ds_wi_code)?>" fieldname="ds_wi_code" id="ds_wi_code_form" mask="PL" model = "<?php echo ($this->encodeModel('schedule/wi_model')); ?>" ro="<?php echo($readonly);?>" sc="<?php echo($sc);?>" code_field="cd_wi"  relid="-1" relCode ="-1">
        </div>

        <label for="dt_deactivated_form" class="col-sm-1 control-label "><?php echo($formTrans_dt_deactivated) ?>:</label>
        <div class="col-sm-2">
            <input type="text" class="form-control input-sm"   value="<?php hecho($dt_deactivated)?>" fieldname="dt_deactivated" id="dt_deactivated_form">
        </div>

        <label for="cd_human_resource_record_form" class="col-sm-1 control-label "><?php echo($formTrans_cd_human_resource_record) ?>:</label>
        <div class="col-sm-2">
            <input type="text" class="form-control input-sm"   fieldname="ds_human_resource_record" id="ds_human_resource_record_form" mask="PL" plcode="<?php echo($cd_human_resource_record)?>"  value="<?php hecho($ds_human_resource_record)?>"  model = "<?php echo ($this->encodeModel('human_resource_model')); ?>" fieldname="ds_human_resource_record" code_field="cd_human_resource_record"  ro="Y" sc="<?php echo($sc);?>" relid="-1" relCode ="-1" type="text">
        </div>

        <div class="col-md-12 no-padding">
            <label for="ds_comments" class="col-sm-1 control-label "><?php echo($formTrans_ds_comments) ?>:</label>
            <div class="col-sm-11">
                <textarea rows="5" class="form-control input-sm" fieldname="ds_comments" id="ds_comments_form" mask="c"><?php hecho($ds_comments)?></textarea>
            </div>
        </div>

    </div>
</form>