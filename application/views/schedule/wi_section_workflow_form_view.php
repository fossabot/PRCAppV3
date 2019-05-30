
<script type="text/javascript">
    var dsMainObject = new function () {
        var thisObj = this;
        thisObj.gridName = undefined;
        thisObj.action = '<?php echo($action); ?>';
        thisObj.cd_wi_section_workflow = <?php echo($cd_wi_section_workflow); ?>;
        thisObj.level = 4;

        this.start = function () {
            <?php
            echo $toolbar;echo $WiEqpGrid;
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
            thisObj.Form = $('#myWiForm').cgbForm({updController: 'schedule/wi_section_workflow'});

            $('#myWiForm').on('afterUpdate', function (a) {
                dsBasicObject.addUpdateNode(thisObj.level, thisObj.cd_wi_section_workflow, a.recordset[0].cd_wi_section);
            });

            thisObj.Form.addGridToControl('gridWiEqp');
            this.resizeGrid();

        };

        this.updateData = function () {
            thisObj.Form.updateForm();
        };

        //grid function start
        this.resizeGrid = function () {
            var vqqty = w2ui['gridWiEqp'].records.length;
            var size = 100 + (vqqty * 20);
            $('#gridWiEqpDiv').height(size);
            w2ui['gridWiEqp'].refresh();
        };

        this.ToolbarGrid = function (bPressed) {
            if (bPressed == 'update') {
                w2ui['gridWiEqp'].update();
            }
            if (bPressed == 'insert') {
                w2ui['gridWiEqp'].insertRow({funcAfter: function (a) {
                        var vp = thisObj.Form.getItem('cd_wi_section_workflow_form');
                        w2ui['gridWiEqp'].setItem(a.recid, 'cd_wi_section_workflow', vp);
                        thisObj.resizeGrid();
                    }
                });
            }

            if (bPressed == "delete") {
                w2ui['gridWiEqp'].deleteRow();
            }
        };

        this.deleteWi = function () {
            messageBoxYesNo('<?php echo($delWi)?>', function () {
                $.myCgbAjax({
                    url: 'schedule/wi_section_workflow/deleteById/' + thisObj.cd_wi_section_workflow,
                    dataType: 'html',
                    success: function (data) {
                        if (data == 'OK') {
                            thisObj.Form.destroy();
                            $('#myWiForm').remove();
                            dsBasicObject.deleteNode(thisObj.level, thisObj.cd_wi_section_workflow);
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


    };

    // funcoes iniciais;
    dsMainObject.start();
</script>

<form id="myWiForm" class="form-horizontal">
<!--first part-->
    <div class="row" style="margin:auto ">
        <div class="modal-header-form_cgb"><?php echo($title); ?></div>

        <div id="wiToolbar" class="toolbarStyle" style="border-bottom: none !important"></div>

        <input type="hidden" class="form-control input-sm"   value="<?php hecho($cd_wi_section_workflow)?>" fieldname="cd_wi_section_workflow" id="cd_wi_section_workflow_form"  mask="PK" >


        <label for="ds_wi_section_workflow_code_form" class="col-sm-1 control-label "><?php echo($formTrans_ds_wi_section_workflow_code) ?>:</label>
        <div class="col-sm-2">
            <input type="text" class="form-control input-sm"   value="<?php hecho($ds_wi_section_workflow_code)?>" fieldname="ds_wi_section_workflow_code" id="ds_wi_section_workflow_code_form" mask="c" type="text" maxlength="32" >
        </div>

        <label for="ds_wi_section_revision_type_form" class="col-sm-1 control-label "><?php echo($formTrans_cd_wi_section_revision_type) ?>:</label>
        <div class="col-sm-2">
            <input type="text" class="form-control input-sm"   plcode="<?php echo($cd_wi_section_revision_type)?>"  value="<?php hecho($ds_wi_section_revision_type)?>" fieldname="ds_wi_section_revision_type" id="ds_wi_section_revision_type_form" mask="PL" model = "<?php echo ($this->encodeModel('schedule/wi_section_revision_type_model')); ?>" fieldname="ds_wi_section_revision_type" code_field="cd_wi_section_revision_type"  relid="-1" relCode ="-1" type="text">
        </div>

        <label for="ds_wi_section_form" class="col-sm-1 control-label "><?php echo($formTrans_cd_wi_section) ?>:</label>
        <div class="col-sm-2">
            <input type="text" class="form-control input-sm"   plcode="<?php echo($cd_wi_section)?>"  value="<?php hecho($ds_section_code)?>" fieldname="ds_section_code" id="ds_section_code_form" mask="PL" model = "<?php echo ($this->encodeModel('schedule/wi_section_model')); ?>" fieldname="ds_section_code" code_field="cd_wi_section" ro="<?php echo($readonly);?>" sc="<?php echo($sc);?>" relid="-1" relCode ="-1">
        </div>

        <label for="ds_equipment_description_form" class="col-sm-1 control-label "><?php echo($formTrans_ds_equipment_description) ?>:</label>
        <div class="col-sm-2">
            <input type="text" class="form-control input-sm"   value="<?php hecho($ds_equipment_description)?>" fieldname="ds_equipment_description" id="ds_equipment_description_form" mask="c" type="text" maxlength="" >
        </div>

        <label for="nr_wi_section_workflow_revision_form" class="col-sm-1 control-label "><?php echo($formTrans_nr_wi_section_workflow_revision) ?>:</label>
        <div class="col-sm-2">
            <input type="text" class="form-control input-sm"   value="<?php hecho($nr_wi_section_workflow_revision)?>" fieldname="nr_wi_section_workflow_revision" id="nr_wi_section_workflow_revision_form" mask="I" >
        </div>

        <label for="nr_wi_section_workflow_revision_minor_form" class="col-sm-1 control-label "><?php echo($formTrans_nr_wi_section_workflow_revision_minor) ?>:</label>
        <div class="col-sm-2">
            <input type="text" class="form-control input-sm"   value="<?php hecho($nr_wi_section_workflow_revision_minor)?>" fieldname="nr_wi_section_workflow_revision_minor" id="nr_wi_section_workflow_revision_minor_form" mask="I" >
        </div>

        <label for="ds_project_model_form" class="col-sm-1 control-label "><?php echo($formTrans_cd_project_model) ?>:</label>
        <div class="col-sm-2">
            <input type="text" class="form-control input-sm"   plcode="<?php echo($cd_project_model)?>"  value="<?php hecho($ds_project_model)?>" fieldname="ds_project_model" id="ds_project_model_form" mask="PL" model = "<?php echo ($this->encodeModel('tti/project_model_model')); ?>" fieldname="ds_project_model" code_field="cd_project_model"  relid="-1" relCode ="-1" type="text">
        </div>

        <label for="ds_project_product_form" class="col-sm-1 control-label "><?php echo($formTrans_cd_project_product) ?>:</label>
        <div class="col-sm-2">
            <input type="text" class="form-control input-sm"   plcode="<?php echo($cd_project_product)?>"  value="<?php hecho($ds_project_product)?>" fieldname="ds_project_product" id="ds_project_product_form" mask="PL" model = "<?php echo ($this->encodeModel('tti/project_product_model')); ?>" fieldname="ds_project_product" code_field="cd_project_product"  relid="-1" relCode ="-1" type="text">
        </div>

        <label for="dt_approval_form" class="col-sm-1 control-label "><?php echo($formTrans_dt_approval) ?>:</label>
        <div class="col-sm-2">
            <input type="text" class="form-control input-sm"   value="<?php hecho($dt_approval)?>" fieldname="dt_approval" id="dt_approval_form">
        </div>

        <label for="dt_deactivated_form" class="col-sm-1 control-label "><?php echo($formTrans_dt_deactivated) ?>:</label>
        <div class="col-sm-2">
            <input type="text" class="form-control input-sm"   value="<?php hecho($dt_deactivated)?>" fieldname="dt_deactivated" id="dt_deactivated_form">
        </div>

        <div class="col-md-12 no-padding">
            <label for="ds_wi_section_workflow_form" class="col-sm-1 control-label "><?php echo($formTrans_ds_wi_section_workflow) ?>:</label>
            <div class="col-sm-11">
                <textarea class="form-control input-sm" rows="5" fieldname="ds_wi_section_workflow" id="ds_wi_section_workflow_form" mask="c" maxlength=""><?php hecho($ds_wi_section_workflow) ?></textarea>
            </div>
        </div>

    </div>
<!-- second part-->
    <div class="box box-solid box-info">
        <div class="box-header with-border">
            <h3 class="box-title"><?php echo($groupTitleWI); ?></h3>
        </div>
        <div class="box-body">
            <label for="ds_test_unit_form" class="col-sm-1 control-label "><?php echo($formTrans_cd_test_unit) ?>:</label>
            <div class="col-sm-3">
                <input type="text" class="form-control input-sm" plcode="<?php echo($cd_test_unit) ?>" value="<?php hecho($ds_test_unit) ?>" fieldname="ds_test_unit" id="ds_test_unit_form"
                       mask="PLD" model="<?php echo($this->encodeModel('tr/test_unit_model')); ?>" fieldname="ds_test_unit" code_field="cd_test_unit" relid="-1" relCode="-1" type="text">
            </div>

            <label for="nr_man_power_form" class="col-sm-1 control-label "><?php echo($formTrans_nr_man_power) ?>
                :</label>
            <div class="col-sm-3">
                <input type="text" class="form-control input-sm" value="<?php hecho($nr_man_power) ?>" fieldname="nr_man_power" id="nr_man_power_form" mask="N;18.4">
            </div>

            <div class="col-md-12 no-padding">
                <label for="ds_specification_form" class="col-sm-1 control-label "><?php echo($formTrans_ds_specification) ?>:</label>
                <div class="col-sm-11">
                <textarea class="form-control input-sm" rows="5" fieldname="ds_specification" id="ds_specification_form" mask="c" maxlength=""><?php hecho($ds_specification) ?></textarea>
                </div>

            </div>
        </div>

    </div>
<!-- third part-->

    <div class="box box-info box-solid" id="gridWiEqp">
        <div class="box-header with-border">
            <h3 class="box-title"> <?php echo($equipmentTitle) ?> </h3>
            <div class="box-tools pull-right">
                <button type='button' class='btn btn-box-tool' data-widget="collapse"> <i class='fa fa-compress'></i> </button>
            </div>
        </div>
        <div class="box-body">
            <div id="gridWiEqpDiv" style="width: 100%"></div>
        </div>
    </div>


</form>
