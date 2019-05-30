
<script type="text/javascript">
    var dsMainObject = new function () {
        var thisObj = this;
        thisObj.gridName = undefined;
        thisObj.action = '<?php echo($action); ?>';
        thisObj.cd_wi_section = <?php echo($cd_wi_section); ?>;
        thisObj.level = 3;

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
            thisObj.Form = $('#myWiForm').cgbForm({updController: 'schedule/wi_section'});

            $('#myWiForm').on('afterUpdate', function (a) {
                dsBasicObject.addUpdateNode(thisObj.level, thisObj.cd_wi_section, a.recordset[0].cd_wi_revision);
            });

        };

        this.updateData = function () {
            thisObj.Form.updateForm();
        };

        this.deleteWi = function () {
            messageBoxYesNo('<?php echo($delWi)?>', function () {
                $.myCgbAjax({
                    url: 'schedule/wi_section/deleteById/' + thisObj.cd_wi_section,
                    dataType: 'html',
                    success: function (data) {
                        if (data == 'OK') {
                            thisObj.Form.destroy();
                            $('#myWiForm').remove();
                            dsBasicObject.deleteNode(thisObj.level, thisObj.cd_wi_section);
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
        <div class="modal-header-form_cgb"><?php echo $ds_section_code; ?></div>
        <input type="hidden" class="form-control input-sm" value="<?php hecho($cd_wi_section) ?>" fieldname="cd_wi_section" id="cd_wi_section_form" mask="PK">

        <div id="wiToolbar" class="toolbarStyle" style="border-bottom: none !important"></div>

        <label for="ds_section_code_form" class="col-sm-1 control-label "><?php echo($formTrans_ds_section_code) ?>:</label>
        <div class="col-sm-2">
            <input type="text" class="form-control input-sm"   value="<?php hecho($ds_section_code)?>" fieldname="ds_section_code" id="ds_section_code_form" mask="c" type="text" maxlength="16" >
        </div>

        <label for="ds_wi_revision_form" class="col-sm-1 control-label "><?php echo($formTrans_cd_wi_revision) ?>:</label>
        <div class="col-sm-2">
            <input type="text" class="form-control input-sm"   plcode="<?php echo($cd_wi_revision)?>"  value="<?php hecho($nr_wi_revision)?>" fieldname="nr_wi_revision" id="nr_wi_revision_form" mask="PL" model = "<?php echo ($this->encodeModel('schedule/wi_revision_model')); ?>" fieldname="nr_wi_revision" code_field="cd_wi_revision" ro="<?php echo($readonly);?>" sc="<?php echo($sc);?>"  relid="-1" relCode ="-1">
        </div>

        <label for="ds_test_type_form" class="col-sm-1 control-label "><?php echo($formTrans_cd_test_type) ?>:</label>
        <div class="col-sm-2">
            <input type="text" class="form-control input-sm"   plcode="<?php echo($cd_test_type)?>"  value="<?php hecho($ds_test_type)?>" fieldname="ds_test_type" id="ds_test_type_form" mask="PL" model = "<?php echo ($this->encodeModel('tr/test_type_model')); ?>" fieldname="ds_test_type" code_field="cd_test_type"  relid="-1" relCode ="-1">
        </div>

        <label for="nr_wi_section_revision_form" class="col-sm-1 control-label "><?php echo($formTrans_nr_wi_section_revision) ?>:</label>
        <div class="col-sm-2">
            <input type="text" class="form-control input-sm"   value="<?php hecho($nr_wi_section_revision)?>" fieldname="nr_wi_section_revision" id="nr_wi_section_revision_form" mask="I" >
        </div>

        <label for="dt_approval_form" class="col-sm-1 control-label "><?php echo($formTrans_dt_approval) ?>:</label>
        <div class="col-sm-2">
            <input type="text" class="form-control input-sm"   value="<?php hecho($dt_approval)?>" fieldname="dt_approval" id="dt_approval_form">
        </div>

        <label for="ds_human_resource_approval_form" class="col-sm-1 control-label "><?php echo($formTrans_cd_human_resource_approval) ?>:</label>
        <div class="col-sm-2">
            <input type="text" class="form-control input-sm"   plcode="<?php echo($cd_human_resource_approval)?>"  value="<?php hecho($ds_human_resource_approval)?>" fieldname="ds_human_resource_approval" id="ds_human_resource_approval_form" mask="PL" model = "<?php echo ($this->encodeModel('human_resource_model')); ?>" fieldname="ds_human_resource_approval" code_field="cd_human_resource_approval"  relid="-1" relCode ="-1" type="text">
        </div>

        <label for="dt_deactivated_form" class="col-sm-1 control-label "><?php echo($formTrans_dt_deactivated) ?>:</label>
        <div class="col-sm-2">
            <input type="text" class="form-control input-sm"   value="<?php hecho($dt_deactivated)?>" fieldname="dt_deactivated" id="dt_deactivated_form">
        </div>


        <div class="col-md-12 no-padding">
            <label for="ds_wi_section_form" class="col-sm-1 control-label "><?php echo($formTrans_ds_wi_section) ?>:</label>
            <div class="col-sm-11">
                <textarea rows="5" class="form-control input-sm" fieldname="ds_wi_section" id="ds_wi_section_form" mask="c" must="Y"  ><?php hecho($ds_wi_section)?></textarea>
            </div>
        </div>
    </div>
</form>