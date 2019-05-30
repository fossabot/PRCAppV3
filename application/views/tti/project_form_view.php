<?php
$sqlPrd = ' AND EXISTS (SELECT 1 FROM "PROJECT_PRODUCT_X_PROJECT_POWER_TYPE"   x WHERE x.cd_project_power_type = "PROJECT_POWER_TYPE".cd_project_power_type and x.cd_project_product = %s AND x.dt_deactivated IS NULL) ';
$sqlPwd = ' AND EXISTS (SELECT 1 FROM "PROJECT_POWER_TYPE_X_PROJECT_TOOL_TYPE" x WHERE x.cd_project_tool_type = "PROJECT_TOOL_TYPE".cd_project_tool_type and x.cd_project_power_type = %s AND x.dt_deactivated IS NULL ) ';

$sqlPrd = $this->cdbhelper->getFilterQueryId($sqlPrd);
$sqlPwd = $this->cdbhelper->getFilterQueryId($sqlPwd);
//setPLRelCode
?>

<script>
// aqui tem os scripts basicos. 
//var controllerName = "country";



//$(".ds_hr_type").on( "change",  function() {


    var dsFormPrjObject = new function () {

        // variaveis privadas;

        var thisObj = this;
        thisObj.gridName = undefined;
        thisObj.action = '<?php echo($action); ?>'
        thisObj.lastPrjCode = -20;
        thisObj.cd_project = <?php echo($cd_project); ?>;
        thisObj.fieldsToHide = ['ds_project_form', 'ds_human_resource_prc_pm_form', 'ds_human_resource_eng_form', 'ds_tti_project_form', 'ds_met_project_form', 'ds_project_tool_type_form']


        this.start = function () {

<?php
echo($toolbar);
echo($PrjModelGrid);
?>


            var vtoolbar = vGridToToolbarPrj.toolbar;

            vtoolbar.onClick = function (a, b) {
                if (a == 'update') {
                    thisObj.updateData();
                }

                if (a == 'delete') {
                    thisObj.deleteProject();
                }



            };
            vtoolbar.name = 'PrjFormToolbar';

            if (w2ui['PrjFormToolbar'] != undefined) {
                w2ui['PrjFormToolbar'].destroy();
            }

            $('#prjToolbar').w2toolbar(vtoolbar);

            thisObj.Form = $('#formPrj').cgbForm({updController: 'tti/project'});

            this.setScreenPermissions();

            this.addListeners();
            this.addHelper();

            thisObj.Form.addGridToControl('gridPrjModel');

            this.resizeGrid();

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
            $('#formPrj').on('pospicklist', function (ev) {
                if (ev.fielddata.codefield == 'cd_project_product') {
                    thisObj.Form.setItemPL('ds_project_power_type_form', -1, '');
                    thisObj.Form.setItemPL('ds_project_tool_type_form', -1, '');
                }

                if (ev.fielddata.codefield == 'cd_project_power_type') {
                    thisObj.Form.setItemPL('ds_project_tool_type_form', -1, '');
                }
            })

            $('#formPrj').on('prepicklist', function (ev) {
                if (ev.fielddata.codefield == 'cd_project_power_type') {
                    var vcode = chkUndefined(thisObj.Form.getItemPLCode('ds_project_product_form'), -1);
                    thisObj.Form.setPLRelCode('ds_project_power_type_form', vcode);
                    ev.options.relation.id = vcode;
                }

                if (ev.fielddata.codefield == 'cd_project_tool_type') {
                    var vcode = chkUndefined(thisObj.Form.getItemPLCode('ds_project_power_type_form'), -1);
                    thisObj.Form.setPLRelCode('ds_project_tool_type_form', vcode);
                    ev.options.relation.id = vcode;
                }

            });

            $('#formPrj').on('itemChanged', function (ev) {
                var vf = ev.fielddata.name;
                thisObj.setDemandedAsChanged();
            });

            $('#formPrj').on('afterUpdate', function (ev) {
                thisObj.action = 'E';
                w2ui['gridPrjModel'].clear()
                w2ui['gridPrjModel'].add(ev.fullData.gridData);
                $(window).trigger('prjChanged', ev.fullData);

                thisObj.makeScreenInformation();
                thisObj.resizeGrid();

            });

            // seto o evendo de fechar!!!
            $(window).on("onCloseForm.prj", function (a) {
                if (thisObj.Form.isChanged()) {
                    messageBoxOkCancel(javaMessages.info_changed_close, function () {
                        thisObj.Form.destroy();
                        SBSModalFormsVar.close();
                        $(window).off('onCloseForm.prj');
                    })
                } else {
                    thisObj.Form.destroy();
                    SBSModalFormsVar.close();
                    $(window).off('onCloseForm.prj');
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
            if (this.action == 'I') {
                w2ui['gridPrjModel'].setItemAsChanged(w2ui['gridPrjModel'].records[0].recid, 'ds_project_model');
                w2ui['gridPrjModel'].setItemAsChanged(w2ui['gridPrjModel'].records[0].recid, 'cd_project');
                w2ui['gridPrjModel'].setItemAsChanged(w2ui['gridPrjModel'].records[0].recid, 'cd_project_status');
                w2ui['gridPrjModel'].setItemAsChanged(w2ui['gridPrjModel'].records[0].recid, 'ds_project_status');
            }
        }

        this.setScreenPermissions = function () {
            var vdr = thisObj.Form.getItem("fl_draft_form");
            if (vdr == 0) {
                $.each(thisObj.fieldsToHide, function (i, v) {
                    thisObj.Form.setEnabled(v, false);
                })
            }

            if (thisObj.action == 'I') {
            } else {

            }

        }

        this.resizeGrid = function () {
            var vqqty = w2ui['gridPrjModel'].records.length;
            var size = 100 + (vqqty * 20);
            $('#gridPrjModelDiv').height(size);
            w2ui['gridPrjModel'].refresh();
        }

        this.setGridAsChanged = function () {

        }

        this.setDemandedAsChanged = function () {
            var vp = thisObj.Form.getItem('cd_project_form');
            thisObj.Form.setItem('cd_project_form', vp);
        }

        this.ToolbarGrid = function (bPressed) {
            if (bPressed == 'insert') {
                w2ui['gridPrjModel'].insertRow({funcAfter: function (a) {
                        var vp = thisObj.Form.getItem('cd_project_form');
                        w2ui['gridPrjModel'].setItem(a.recid, 'cd_project', vp);
                        thisObj.resizeGrid();
                    }
                });
            }

            if (bPressed == "delete") {

                if (w2ui['gridPrjModel'].records.length == 1) {
                    messageBoxError('<?php echo ($errorDel) ?>');
                    return;
                }

                w2ui['gridPrjModel'].deleteRow({funcAfter: function (a) {
                        $(window).trigger('modelDeleted', a);
                    }});
            }
        };

        this.deleteProject = function () {

            messageBoxYesNo('<?php echo($delprj)?>', function () {
                $.myCgbAjax({url: 'tti/project/deleteProject/' + thisObj.cd_project,
                    dataType: 'html',
                    success: function (data) {
                        if (data == 'OK') {
                            $(window).trigger('projectDeleted', thisObj.cd_project );
                            thisObj.Form.destroy();
                            SBSModalFormsVar.close();
                            $(window).off('onCloseForm.prj');
                        } else {
                            messageBoxError(data);
                        }
                    }});

            });


        }

    }

// funcoes iniciais;
    dsFormPrjObject.start();


// insiro colunas;

</script>

<div id="divPrjForm" style="max-height: calc(100vh - 40px);" class="" > 
    <div class="row">
        <div id="prjToolbar" style="width: 100%;" class="toolbarStyle" ></div>
    </div>


    <form id="formPrj" class="form-horizontal">

        <div class='row'>
            <div class="hidden">

                <div class="col-sm-2">
                    <input type="text" class="form-control input-sm"   value="<?php echo($cd_project) ?>" fieldname="cd_project" id="cd_project_form"  mask="PK"  sc="<?php echo($sc); ?>">
                    <input type="text" class="form-control input-sm"   value="<?php echo($fl_draft) ?>" fieldname="fl_draft" id="fl_draft_form" mask="c" sc="<?php echo($sc); ?>">

                </div>
            </div>

            <div class="row">

                <label for="ds_project_form" class="col-sm-1 control-label "><?php echo($formTrans_ds_project) ?>:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control input-sm"   value="<?php hecho($ds_project) ?>" fieldname="ds_project" id="ds_project_form" mask="c" type="text" maxlength="128" >
                </div>

                <label for="ds_tti_project_form" class="col-sm-1 control-label "><?php echo($formTrans_ds_tti_project) ?>:</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control input-sm"   value="<?php hecho($ds_tti_project) ?>" fieldname="ds_tti_project" id="ds_tti_project_form" mask="c" >
                </div>

                <label for="ds_met_project_form" class="col-sm-1 control-label "><?php echo($formTrans_ds_met_project) ?>:</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control input-sm"   value="<?php hecho($ds_met_project) ?>" fieldname="ds_met_project" id="ds_met_project_form" mask="c" >
                </div>


                <label for="ds_human_resource_prc_pm_form" class="col-sm-1 control-label "><?php echo($formTrans_cd_human_resource_prc_pm) ?>:</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control input-sm"   plcode="<?php echo($cd_human_resource_prc_pm) ?>"  value="<?php hecho($ds_human_resource_prc_pm) ?>" fieldname="ds_human_resource_prc_pm" id="ds_human_resource_prc_pm_form" mask="PLD" model = "<?php echo ($this->encodeModel('human_resource_model')); ?>" fieldname="ds_human_resource_prc_pm" code_field="cd_human_resource_prc_pm"  relid="-1" relCode ="-1" type="text">
                </div>

                <label for="ds_human_resource_eng_form" class="col-sm-1 control-label "><?php echo($formTrans_cd_human_resource_eng) ?>:</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control input-sm"   plcode="<?php echo($cd_human_resource_eng) ?>"  value="<?php hecho($ds_human_resource_eng) ?>" fieldname="ds_human_resource_eng" id="ds_human_resource_eng_form" mask="PLD" model = "<?php echo ($this->encodeModel('human_resource_model')); ?>" fieldname="ds_human_resource_eng" code_field="cd_human_resource_eng"  relid="-1" relCode ="-1" type="text">
                </div>

                <label for="ds_department_form" class="col-sm-1 control-label "><?php echo($formTrans_cd_department) ?>:</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control input-sm"   plcode="<?php echo($cd_department) ?>"  value="<?php hecho($ds_department) ?>" fieldname="ds_department" id="ds_department_form" mask="PLD" model = "<?php echo ($this->encodeModel('job_department_model')); ?>" fieldname="ds_department" code_field="cd_department"  relid="-1" relCode ="-1" type="text">
                </div>                  

                <label for="ds_brand_form" class="col-sm-1 control-label "><?php echo($formTrans_cd_brand) ?>:</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control input-sm"   plcode="<?php echo($cd_brand) ?>"  value="<?php hecho($ds_brand) ?>" fieldname="ds_brand" id="ds_brand_form" mask="PLD" model = "<?php echo ($this->encodeModel('brand_model')); ?>" fieldname="ds_brand" code_field="cd_brand"  relid="-1" relCode ="-1" type="text" sc="<?php echo($sc); ?>">
                </div>

                <label for="ds_project_product_form" class="col-sm-1 control-label "><?php echo($formTrans_cd_project_product) ?>:</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control input-sm"   plcode="<?php echo($cd_project_product) ?>"  value="<?php hecho($ds_project_product) ?>" fieldname="ds_project_product" id="ds_project_product_form" mask="PLD" model = "<?php echo ($this->encodeModel('tti/project_product_model')); ?>" fieldname="ds_project_product" code_field="cd_project_product"  relid="-1" relCode ="-1" type="text"  must="Y">
                </div>                                    


                <label for="ds_project_power_type_form" class="col-sm-1 control-label "><?php echo($formTrans_cd_project_power_type) ?>:</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control input-sm"   plcode="<?php echo($cd_project_power_type) ?>"  value="<?php hecho($ds_project_power_type) ?>" fieldname="ds_project_power_type" id="ds_project_power_type_form" mask="PLD" model = "<?php echo ($this->encodeModel('tti/project_power_type_model')); ?>" fieldname="ds_project_power_type" code_field="cd_project_power_type"  relid="<?php echo($sqlPrd) ?>" relCode ="-1" type="text" must="Y">
                </div>


                <label for="ds_project_tool_type_form" class="col-sm-1 control-label "><?php echo($formTrans_cd_project_tool_type) ?>:</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control input-sm"   plcode="<?php echo($cd_project_tool_type) ?>"  value="<?php hecho($ds_project_tool_type) ?>" fieldname="ds_project_tool_type" id="ds_project_tool_type_form" mask="PLD" model = "<?php echo ($this->encodeModel('tti/project_tool_type_model')); ?>" fieldname="ds_project_tool_type" code_field="cd_project_tool_type"   relid="<?php echo($sqlPwd) ?>" relCode ="-1" type="text">
                </div>
                
                <label for="fl_confidential_form" class="col-sm-1 control-label "><?php echo($formTrans_fl_confidential) ?>:</label>
                <div class="col-sm-2">
                    <input type="checkbox" class="form-control input-sm"   value="<?php hecho($fl_confidential) ?>" fieldname="fl_confidential" id="fl_confidential_form" mask="CHK" >
                </div>
                
                
            </div>

            <div class="row">
                <div class="col-md-12">    
                    <div class="box box-info box-solid" id="gridPrjModel">
                        <div class="box-header with-border">
                            <h3 class="box-title"> <?php echo($modelTitle) ?> </h3>
                            <div class="box-tools pull-right">
                                <button type='button' class='btn btn-box-tool' data-widget="collapse"> <i class='fa fa-compress'></i> </button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div id="gridPrjModelDiv" style="width: 100%"></div>
                        </div>
                    </div> 
                </div>
            </div>


    </form>
</div>



