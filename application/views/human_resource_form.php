<div class="row">
    <div id='toolbarform' class="toolbarStyle"> </div>
</div>
<form name="formuser" id="formuser" class="form-horizontal">

    <div class="row">

        <div class="col-md-9 small-padding">

            <div class="box box-default box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo ($general); ?></h3>
                </div>

                <div class="box-body">

                    <div class="row">
                        <!-- /.box-tools -->
                        <label for="cd_human_resource_form" class="col-sm-3 control-label "><?php echo($code) ?>:</label>

                        <div class="col-sm-3">
                            <input type="text" class="form-control input-sm " id="cd_human_resource_form" mask="PK" readonly="readonly">
                        </div>

                        <label for="dt_deactivated_form" class="col-sm-3 control-label "><?php echo($deactivated) ?>:</label>

                        <div class="col-sm-3">
                            <input type="text" class="form-control input-sm " id="dt_deactivated_form">
                        </div>
                    </div>

                    <div class="row"> 
                        <label for="ds_human_resource_full_form"  class="col-sm-3 control-label "><?php echo($fullname) ?>:</label>

                        <div class="col-sm-9">
                            <input type="text" must="Y" class="form-control input-sm " id="ds_human_resource_full_form" mask='c' type="text" maxlength="64">
                        </div>
                    </div>

                    <div class="row"> 

                        <label for="ds_e_mail_form"  class="col-sm-3 control-label "><?php echo($email) ?>:</label>

                        <div class="col-sm-9">
                            <input type="text" must="Y" class="form-control input-sm " id="ds_e_mail_form" mask='c' type="text" maxlength="64">
                        </div>
                    </div>

                    <div class="row" style="display: none;">    
                        <label for="ds_hr_type_form" class="col-sm-3 control-label "><?php echo($typeuser) ?>:</label>

                        <div class="col-sm-9">
                            <input type="text"  class="form-control input-sm " id="ds_hr_type_form" mask="PL" model = "<?php echo($this->encodeModel('Hm_type')); ?>" type="text" maxlength="64">
                        </div>
                    </div>

                </div>
            </div>

            <div class="box box-default box-solid" style="display: none"> //taylor 2018/06/01   hide change password area
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo ($login_info); ?></h3>
                </div>

                <div class="box-body">

                    <div class="row">

                        <label for="ds_human_resource_form"  class="col-sm-3 control-label "><?php echo($login) ?>:</label>

                        <div class="col-sm-9">
                            <input type="text" must="Y" class="form-control input-sm " id="ds_human_resource_form" mask='c' type="text" maxlength="64">
                        </div>

                    </div>

                    <div class="row">

                        <label for="ds_password_form"  class="col-sm-3 control-label "><?php echo($newpassword) ?>:</label>

                        <div class="col-sm-3">
                            <input type="password"  class="form-control input-sm " id="ds_password_form" mask='c' type="text" maxlength="64">
                        </div>


                        <label for="ds_retype_password_form"  class="col-sm-3 control-label "><?php echo($retypepassword) ?>:</label>

                        <div class="col-sm-3">
                            <input type="password"  class="form-control input-sm " id="ds_retype_password_form" mask='c' type="text" maxlength="64">
                        </div>


                    </div>



                </div>

            </div>


        </div>

        <div class="col-md-3 small-padding">
            <img src="<?php echo ($img); ?>" id="imageUser" class="img-thumbnail specImages" style="display: block; float: left; width:100%;height: auto;margin-top: 5px; cursor: pointer">
        </div>

    </div>
</form>



<input type="file" accept="<?php echo ($accept); ?>" name="imgbuttonUser" id="imgbuttonUser" data-form-send='{"user": <?php echo($pk); ?>}' style="width: 40px; height:40px">

<script type="text/javascript">

    var res = <?php echo($resultset); ?>;
    var bnewUser = <?php echo($new); ?>;
    ;
    var codePK = <?php echo($pk); ?>;



    if (w2ui['toolbarform'] != undefined) {
        w2ui['toolbarform'].destroy();
    }


    $('#toolbarform').w2toolbar({
        name: 'toolbarform',
        onClick: function (event) {
            if (event.target == "update") {
                updateForm();
            }
        }
    });

    toolbarAddUpd(w2ui['toolbarform']);

    imgsFormHM = $('#imgbuttonUser').cgbFileUpload({beforeFilesChanges: checkBeforeFileUser,
        afterFilesChanges: afterFilesChangesUser,
        loadData: true,
        cleanOnCancel: false
    });
//grid: w2ui[gridName],
    myFormHM = $('#formuser').cgbForm({recordset: res, formSize: 'original'});
    myFormHM.setController('users_maint');
    myFormHM.setCgbFileUploadObj(imgsFormHM);

    $('#imageUser').on('dblclick', function () {
        imgsFormHM.openDialog('imgbuttonUser');
    });

    //pickListSet('ds_hr_type_form', openPicklistHrType);

    $('#formuser').on('afterUpdate', function (a) {
        var rec = a.recordset;
        $.each(rec, function (i,v) {
            var vexists = w2ui[gridName].get(v['recid']);
            console.log('dentro', vexists, v);
            if (vexists == null) {
                w2ui[gridName].add([v]);

            } else {
                w2ui[gridName].set(v['recid'], v);
            }


            // limpeza dos deletados
        });
    });


    function updateForm() {

        if ($("#ds_password_form").val() != "" && $("#ds_password_form").val() != $("#ds_retype_password_form").val()) {
            messageBoxAlert('<?php echo ($passnotmatch); ?>');
            return;
        }

        if ($("#dt_deactivated_form").val() != "") {
            if (!w2utils.isDate($("#dt_deactivated_form").val())) {
                messageBoxAlert(javaMessages.invalid_date);
                return;
            }
        }

        if (!w2utils.isEmail(($("#ds_e_mail_form").val()))) {
            messageBoxAlert('<?php echo ($inv_email); ?>');
            return;
        }

        doUpdate();
    }

    function doUpdate() {

        myFormHM.updateForm();

    }

    // seto o evendo de fechar!!!
    $(window).on("onCloseForm", function (a) {
        if (myFormHM.isChanged()) {
            messageBoxOkCancel(javaMessages.info_changed_close, function () {
                SBSModalFormsVar.close();
            })
        } else {
            SBSModalFormsVar.close();
        }
    });


    function checkBeforeFileUser(id, files) {
        if (files[0].size > 200000) {
            messageBoxError('<?php echo ($error_size); ?>' + ' 200kb');
            return false;
        }
        return true;
    }

    function afterFilesChangesUser(id, files, data) {

        $('#imageUser').attr('src', data);

        var vhm = myFormHM.getItem('ds_human_resource_full_form');
        myFormHM.setItem('ds_human_resource_full_form', vhm);
        return true;
    }


</script>

</html>
