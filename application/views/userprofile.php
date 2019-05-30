<?php include_once APPPATH . 'views/viewIncludeFilter.php'; ?>

<style type="text/css">

    .row-no-padding {
        margin-left: 0;
        margin-right: 0;
        [class*="col-"] {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }
    }
</style>

<form>

</form>


<div class="row">
    <div class="col-lg-10">
        <form id='formuser'>
            <div class="form-group">
                <input type="hidden" mask="PK" class="form-control" id="cd_human_resource_form" placeholder="">
                <label for="ds_human_resource_full_form"><?php echo ($fullname); ?></label>
                <input type="Text" mask="c" class="form-control" id="ds_human_resource_full_form" placeholder="<?php echo ($fullname); ?>" ro="Y">
            </div>
<div id="mustFields">
            <div class="form-group">
                <label for="ds_roles_form"><?php echo ($projrole); ?></label>
                <input type="Text" class="" mask="PLD" model = "<?php echo ($this->encodeModel('tti/roles_model')); ?>" fieldname="ds_roles" code_field="cd_roles"  relid="-1" relCode ="-1" id="ds_roles_form" placeholder="<?php echo ($projrole); ?>" must="Y">        
            </div>

            <div class="form-group">
                <label for="ds_team_form"><?php echo ($teamdata); ?></label>
                <input type="Text" class="" mask="PLD" model = "<?php echo ($this->encodeModel('team_model')); ?>" fieldname="ds_team" code_field="cd_team"  relid="-1" relCode ="-1" id="ds_team_form" placeholder="<?php echo ($teamdata); ?>" must="Y">
            </div>
</div>


            <div class="form-group">
                <label for="ds_e_mail_form"><?php echo ($email); ?></label>
                <input type="email" class="form-control" mask="c" id="ds_e_mail_form" placeholder="<?php echo ($email); ?>"  ro="Y">
            </div>

        </form>

    </div>

    <div class="col-lg-2"> 
        <img src="<?php echo ($img); ?>" id="imageUser" class="img-thumbnail specImages" style=" width:100%;height: auto;margin-top: 5px; cursor: pointer">

    </div>
    <input type="file" accept="<?php echo ($accept); ?>" data-form-send='{"user": <?php echo($pk); ?>}' name="imgbuttonUser" id="imgbuttonUser" style="width: 40px; height:40px">

</div>
<div class="row">
    <button type="submit" onclick="dsMainObject.updateForm();return false;" class="btn btn-default">Submit</button>
</div>

<script type="text/javascript">

    var dsMainObject = new function () {



        var res = <?php echo($resultset); ?>;

        imgsFormHM = $('#imgbuttonUser').cgbFileUpload({beforeFilesChanges: checkBeforeFileUser,
            afterFilesChanges: afterFilesChangesUser,
            loadData: true,
            cleanOnCancel: false
        });


        $('#imageUser').on('dblclick', function () {
            imgsFormHM.openDialog('imgbuttonUser');
        });

        myFormHM = $('#formuser').cgbForm({recordset: res, formSize: 'original', updFunction: 'updateDataJsonFormProfile'});
        myFormHM.setController('users_maint');
        myFormHM.setCgbFileUploadObj(imgsFormHM);

        var vDataTeamOriginal = chkUndefined(myFormHM.getItemPLCode('ds_team_form'), -1);
        var vDataRolesOriginal = chkUndefined(myFormHM.getItemPLCode('ds_roles_form'), -1);
        var vIntro = false;

        if (vDataTeamOriginal == -1 || vDataRolesOriginal == -1) {
            vIntro = true;
            introAddNew({steps: [{element:'#mustFields', intro: '<?php echo($introData)?>', position: 'bottom'}]});
            setTimeout(function(){showHelper();}, 0);
        }


        $('#formuser').on('afterUpdate', function () {
            myFormHM.setItem('ds_current_password_form', '');
            myFormHM.setItem('ds_password_form', '');
            myFormHM.setItem('ds_retype_password_form', '');
            myFormHM.resetUpdate();
            vDataTeamOriginal = chkUndefined(myFormHM.getItemPLCode('ds_team_form'), -1);
            vDataRolesOriginal = chkUndefined(myFormHM.getItemPLCode('ds_roles_form'), -1);


        });

        function checkBeforeFileUser(id, files) {
            //console.log(files);
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


        function updateForm() {
            if (hasRequiredInformation() == false) {
                messageBoxAlert(javaMessages.required_info);
                return;
            }

            /*
            if ($("#ds_password_form").val() != "" && $("#ds_password_form").val() != $("#ds_retype_password_form").val()) {
                messageBoxAlert('<?php echo ($passnotmatch); ?>');
                return;
            }

            if (!w2utils.isEmail(($("#ds_e_mail_form").val()))) {
                messageBoxAlert('<?php echo ($inv_email); ?>');
                return;
            }*/

            myFormHM.updateForm();
        }


        this.beforeClose = function () {
            return true;
        }

        this.beforeCloseNoMsg = function () {
            if (myFormHM.isChanged() && (vDataTeamOriginal == -1 || vDataRolesOriginal == -1)) {
                messageBoxError(javaMessages.saveFirst);
                return false;
            }

            return myFormHM.checkDemandedWithMSG();
        }

        this.updateForm = updateForm;


        this.close = function () {
            if (vIntro) {
                introRemove();
            }

        }

    }


</script>
