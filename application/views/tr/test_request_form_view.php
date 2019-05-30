<script>
// aqui tem os scripts basicos. 
//var controllerName = "country";



//$(".ds_hr_type").on( "change",  function() {


    var dsFormObject = new function () {

        // variaveis privadas;

        var thisObj = this;
        thisObj.gridName = undefined;
        thisObj.action = '<?php echo($action); ?>'
        thisObj.byModel = <?php echo($fl_by_model) ?>;

        thisObj.fieldsToSearchPrj = ['ds_tti_project_form', 'ds_met_project_form', 'ds_tti_project_model_form', 'ds_met_project_model_form'];

        // funcao de inicio;
        this.start = function () {

<?php echo($toolbar) ?>

            var vtoolbar = vGridToToolbar.toolbar;

            vtoolbar.onClick = function (a, b) {
                if (a == 'update') {
                    thisObj.updateData();
                }
            };
            vtoolbar.name = 'TRFormToolbar';

            if (w2ui['TRFormToolbar'] != undefined) {
                w2ui['TRFormToolbar'].destroy();
            }

            $('#trToolbar').w2toolbar(vtoolbar);

            thisObj.Form = $('#formTRRequest').cgbForm({updController: 'tr/test_request'});

            this.setScreenPermissions();

            this.addListeners();
            this.addHelper();

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
            $('#formTRRequest').on('pospicklist', function (ev) {
                //console.log(ev);
                if (ev.new_code != -1 && ev.id == "ds_project_build_form") {
                    thisObj.showHideModel(ev.record.fl_by_model);
                }
            })


            $('#formTRRequest').on('itemChanged', function (ev) {
                if (thisObj.fieldsToSearchPrj.indexOf(ev.id) != -1) {
                    thisObj.getProject(ev.fielddata.name, ev.new, ev.old);
                    console.log(ev);
                }
            });

            $('#formTRRequest').on('afterUpdate', function (ev) {
                console.log(ev);
                if (w2ui['gridTRBrowse'] != undefined) {
                    if (thisObj.action == 'I') {
                        w2ui['gridTRBrowse'].add(ev.fullData.rs);
                    } else {
                        w2ui['gridTRBrowse'].set(ev.fullData.rs[0].recid, ev.fullData.rs[0]);
                    }
                    w2ui['gridTRBrowse'].ScrollToRow(ev.fullData.rs[0].recid);
                }
                
                

                thisObj.action = 'E';


                thisObj.setScreenPermissions();
            });





            // seto o evendo de fechar!!!
            $(window).on("onCloseForm.tr", function (a) {
                if (thisObj.Form.isChanged()) {
                    messageBoxOkCancel(javaMessages.info_changed_close, function () {
                        thisObj.Form.destroy();
                        SBSModalFormsVar.close();
                        $(window).off('onCloseForm.tr');
                    })
                } else {
                    thisObj.Form.destroy();
                    SBSModalFormsVar.close();
                    $(window).off('onCloseForm.tr');
                }
            });
        }

        this.showHideModel = function (bymodel) {
            if (bymodel == 1) {
                $('#projModelData').slideDown(200);
                thisObj.Form.setDemanded('ds_project_model_form', true);
                thisObj.Form.setDemanded('ds_tti_project_model_form', true);

            } else {
                $('#projModelData').slideUp(200);
                thisObj.Form.setItem('ds_tti_project_model_form', 0);
                thisObj.Form.setItem('ds_met_project_model_form', 0);
                thisObj.Form.setItemPL('ds_project_model_form', -1, '');
                thisObj.Form.setDemanded('ds_project_model_form', false);
                thisObj.Form.setDemanded('ds_tti_project_model_form', false);
            }
            thisObj.byModel = bymodel;
            thisObj.getVersion();
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


        this.getProject = function (column, vlrNew, vlrOld) {
            var colForm = column + '_form';

            $.myCgbAjax({url: '/tti/project/getPrjInfo/' + column + '/' + vlrNew,
                data: [],
                success: function (x) {
                    if (x.data.length == 0) {
                        messageBoxError('<?php echo($errorNotFound) ?>');
                        thisObj.Form.setItem(colForm, vlrOld);
                        return;
                    }

                    thisObj.Form.setItemPL('ds_project_form', x.data[0].cd_project, x.data[0].ds_project);
                    thisObj.Form.setItem('ds_tti_project_form', x.data[0].ds_tti_project);
                    thisObj.Form.setItem('ds_met_project_form', x.data[0].ds_met_project);

                    if (thisObj.byModel == 1) {
                        thisObj.Form.setItem('ds_tti_project_model_form', x.data[0].ds_tti_project_model);
                        thisObj.Form.setItem('ds_met_project_model_form', x.data[0].ds_met_project_model);
                        thisObj.Form.setItemPL('ds_project_model_form', x.data[0].cd_project_model, x.data[0].ds_project_model);
                    }

                    thisObj.getVersion();
                }
            });
        }

        this.setScreenPermissions = function () {
// insert
            if (thisObj.action == 'I') {

            } else {

                thisObj.Form.setEnabled('ds_tti_project_form', false);
                thisObj.Form.setEnabled('ds_met_project_form', false);
                thisObj.Form.setEnabled('ds_tti_project_model_form', false);
                thisObj.Form.setEnabled('ds_met_project_model_form', false);
                thisObj.Form.setEnabled('ds_project_build_form', false);
            }

            if (thisObj.byModel == 1) {
                $('#projModelData').show();
            }
        }

        this.getVersion = function () {
            var vProject = chkUndefined(thisObj.Form.getItemPLCode('ds_project_form'), -1);
            var vModel = chkUndefined(thisObj.Form.getItemPLCode('ds_project_model_form'), -1);
            var vBuild = chkUndefined(thisObj.Form.getItemPLCode('ds_project_build_form'), -1);


            //if missing the demanded information. force to zero
            if (vProject == -1 || vBuild == -1 || (vModel == -1 && thisObj.byModel == 1)) {
                thisObj.Form.setItem('nr_version_form', 0);
                return;
            }

            $.myCgbAjax({url: '/tr/test_request/getLastVersion/' + vProject + '/' + vModel + '/' + vBuild,
                data: [],
                success: function (x) {
                    thisObj.Form.setItem('nr_version_form', x.next);
                }
            });
        }

        // funcaoes gerais 

    }

// funcoes iniciais;
    dsFormObject.start();


// insiro colunas;

</script>

<div id="divTRForm" style="max-height: calc(100vh - 40px);" class="" > 
    <div class="row">
        <div id="trToolbar" style="width: 100%;" class="toolbarStyle" ></div>
    </div>


    <form id="formTRRequest" class="form-horizontal">

        <div style="display: none">
            <label for="cd_test_request_form" class="col-sm-1 control-label "><?php echo($formTrans_cd_test_request) ?>:</label>
            <div class="col-md-3">
                <input type="text" class="form-control input-sm"   value="<?php echo($cd_test_request) ?>" fieldname="cd_test_request" id="cd_test_request_form"  mask="PK" >
            </div>
        </div>

        <div class="row">

            <label for="ds_project_build_form" class="col-sm-1 control-label "><?php echo($formTrans_cd_project_build) ?>:</label>
            <div class="col-md-3">
                <input type="text" class="form-control input-sm"   plcode="<?php echo($cd_project_build) ?>"  value="<?php echo($ds_project_build) ?>" fieldname="ds_project_build" id="ds_project_build_form" mask="PLD" model = "<?php echo ($this->encodeModel('tti/project_build_model')); ?>" fieldname="ds_project_build" code_field="cd_project_build"  relid="-1" relCode ="-1" type="text" must="Y">
            </div>

            <label for="nr_version_form" class="col-sm-1 control-label "><?php echo($formTrans_nr_version) ?>:</label>
            <div class="col-md-1">
                <input type="text" class="form-control input-sm"   value="<?php echo($nr_version) ?>" fieldname="nr_version" id="nr_version_form" mask="I" ro="Y">
            </div>
            <label for="dt_approved_form" class="col-md-1 control-label"><?php echo($formTrans_dt_approved) ?>:</label>
            <div class="col-md-2">
                <input type="text" class="form-control input-sm"   value="<?php echo($dt_approved) ?>" fieldname="dt_approved" id="dt_approved_form">
            </div>

            <label for="ds_human_resource_approver_form" class="col-md-1 control-label "><?php echo($formTrans_cd_human_resource_approver) ?>:</label>
            <div class="col-md-2">
                <input type="text" class="form-control input-sm"   plcode="<?php echo($cd_human_resource_approver) ?>"  value="<?php echo($ds_human_resource_approver) ?>" fieldname="ds_human_resource_approver" id="ds_human_resource_approver_form" mask="PLD" model = "<?php echo ($this->encodeModel('human_resource_model')); ?>" fieldname="ds_human_resource_approver" code_field="cd_human_resource_approver"  relid="-1" relCode ="-1" type="text" >
            </div>

        </div>
        <div class="row"> 
            <hr/>
        </div>

        <div class="row">
            <label for="ds_tti_project_form" class="col-sm-1 control-label "><?php echo($formTrans_ds_tti_project) ?>:</label>
            <div class="col-sm-2">
                <input type="text" class="form-control input-sm"   value="<?php echo($ds_tti_project) ?>" fieldname="ds_tti_project" id="ds_tti_project_form" mask="I" >
            </div>

            <label for="ds_met_project_form" class="col-sm-1 control-label "><?php echo($formTrans_ds_met_project) ?>:</label>
            <div class="col-sm-2">
                <input type="text" class="form-control input-sm"   value="<?php echo($ds_met_project) ?>" fieldname="ds_met_project" id="ds_met_project_form" mask="I" >
            </div>

            <label for="ds_project_form" class="col-sm-1 control-label "><?php echo($formTrans_cd_project) ?>:</label>
            <div class="col-md-5">
                <input type="text" class="form-control input-sm"   plcode="<?php echo($cd_project) ?>"  value="<?php echo($ds_project) ?>" fieldname="ds_project" id="ds_project_form" mask="PLD" model = "<?php echo ($this->encodeModel('tti/project_model')); ?>" fieldname="ds_project" code_field="cd_project"  relid="-1" relCode ="-1" type="text" ro="Y"  must="Y">
            </div>

        </div>

        <div class="row" id="projModelData" style="display: none">
            <label for="ds_tti_project_model_form" class="col-sm-1 control-label "><?php echo($formTrans_ds_tti_project_model) ?>:</label>
            <div class="col-sm-2">
                <input type="text" class="form-control input-sm"   value="<?php echo($ds_tti_project_model) ?>" fieldname="ds_tti_project_model" id="ds_tti_project_model_form" mask="I"  >
            </div>

            <label for="ds_met_project_model_form" class="col-sm-1 control-label "><?php echo($formTrans_ds_met_project_model) ?>:</label>

            <div class="col-sm-2">
                <input type="text" class="form-control input-sm"   value="<?php echo($ds_met_project_model) ?>" fieldname="ds_met_project_model" id="ds_met_project_model_form" mask="I" >
            </div>

            <label for="ds_project_model_form" class="col-sm-1 control-label "><?php echo($formTrans_cd_project_model) ?>:</label>

            <div class="col-md-5">
                <input type="text" class="form-control input-sm"   plcode="<?php echo($cd_project_model) ?>"  value="<?php echo($ds_project_model) ?>" fieldname="ds_project_model" id="ds_project_model_form" mask="PLD" model = "<?php echo ($this->encodeModel('tti/project_model_model')); ?>" fieldname="ds_project_model" code_field="cd_project_model"  relid="-1" relCode ="-1" type="text" ro="Y" must="Y" >
            </div>        

        </div>

        <div class="row"> 
            <hr/>
        </div>


        <div class="row">
            <label for="ds_test_request_type_form" class="col-sm-1 control-label "><?php echo($formTrans_cd_test_request_type) ?>:</label>
            <div class="col-md-3">
                <input type="text" class="form-control input-sm"   plcode="<?php echo($cd_test_request_type) ?>"  value="<?php echo($ds_test_request_type) ?>" fieldname="ds_test_request_type" id="ds_test_request_type_form" mask="PLD" model = "<?php echo ($this->encodeModel('tr/test_request_type_model')); ?>" fieldname="ds_test_request_type" code_field="cd_test_request_type"  relid="-1" relCode ="-1" type="text"  must="Y">
            </div>

            <label for="ds_test_request_purpose_form" class="col-sm-1 control-label "><?php echo($formTrans_cd_test_request_purpose) ?>:</label>
            <div class="col-md-3">
                <input type="text" class="form-control input-sm"   plcode="<?php echo($cd_test_request_purpose) ?>"  value="<?php echo($ds_test_request_purpose) ?>" fieldname="ds_test_request_purpose" id="ds_test_request_purpose_form" mask="PLD" model = "<?php echo ($this->encodeModel('tr/test_request_purpose_model')); ?>" fieldname="ds_test_request_purpose" code_field="cd_test_request_purpose"  relid="-1" relCode ="-1" type="text"  must="Y">
            </div>

            <label for="ds_test_request_origin_form" class="col-sm-1 control-label "><?php echo($formTrans_cd_test_request_origin) ?>:</label>
            <div class="col-md-3">
                <input type="text" class="form-control input-sm"   plcode="<?php echo($cd_test_request_origin) ?>"  value="<?php echo($ds_test_request_origin) ?>" fieldname="ds_test_request_origin" id="ds_test_request_origin_form" mask="PLD" model = "<?php echo ($this->encodeModel('tr/test_request_origin_model')); ?>" fieldname="ds_test_request_origin" code_field="cd_test_request_origin"  relid="-1" relCode ="-1" type="text"  must="Y">
            </div>

        </div>

        <div class="row">

            <label for="ds_description_form" class="col-sm-1 control-label "><?php echo($formTrans_ds_description) ?>:</label>
            <div class="col-md-11">
                <textarea type="text" class="form-control input-sm"   fieldname="ds_description" id="ds_description_form" mask="c" type="text" maxlength="2048" rows="3" style="resize: none" > <?php echo($ds_description) ?> </textarea>
            </div>

        </div>

        <div class="row">
            <label for="fl_return_sample_form" class="col-sm-1 control-label "><?php echo($formTrans_fl_return_sample) ?>:</label>
            <div class="col-sm-3">
                <input type="checkbox" class="form-control input-sm"   value="<?php echo($fl_return_sample) ?>" fieldname="fl_return_sample" id="fl_return_sample_form" mask="CHK">
            </div>

            <label for="fl_urgent_form" class="col-sm-1 control-label "><?php echo($formTrans_fl_urgent) ?>:</label>
            <div class="col-sm-3">
                <input type="checkbox" class="form-control input-sm"   value="<?php echo($fl_urgent) ?>" fieldname="fl_urgent" id="fl_urgent_form" mask="CHK">
            </div>

            <label for="ds_human_resource_request_form" class="col-sm-1 control-label "><?php echo($formTrans_cd_human_resource_request) ?>:</label>
            <div class="col-md-3">
                <input type="text" class="form-control input-sm"   plcode="<?php echo($cd_human_resource_request) ?>"  value="<?php echo($ds_human_resource_request) ?>" fieldname="ds_human_resource_request" id="ds_human_resource_request_form" mask="PLD" model = "<?php echo ($this->encodeModel('human_resource_model')); ?>" fieldname="ds_human_resource_request" code_field="cd_human_resource_request"  relid="-1" relCode ="-1" type="text" sc="<?php echo($sc); ?>"  must="Y">
            </div>
        </div>

    </form>
</div>


