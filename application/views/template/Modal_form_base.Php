<body>
 
    
<div id="form" style="width: 510px;padding: 10px;">
    <form name="formuser" id="formuser">
	<div class="w2ui-page page-0">

            <div style="width: 490px; float: left;">
                <div style="padding: 3px; font-weight: bold; color: #777;">General</div>
                <div class="w2ui-group" style="height: 220px;">

                    <div class="w2ui-field w2ui-span6">
                        <label><?php echo($code);?>:</label>
                        <div>
                            <input name="cd_jobs_form" id="cd_jobs_form" id="cd_human_resource_form" type="text" maxlength="64" style="width: 60px" readonly="readonly"/>
                        </div>
                    </div>
 
                    
                    <div class="w2ui-field w2ui-span6">
                        <label><?php echo($job);?>:</label>
                        <div>
                            <input name="ds_jobs_form" id="ds_jobs_form" onchange="setAsChanged();" type="text" maxlength="64" style="width: 95%;text-transform: uppercase"/>
                        </div>
                    </div>

                    
                    <div class="w2ui-field w2ui-span6">
                        <label><?php echo($department);?>:</label>
                        <div>
                           <input name="ds_department_form" id="ds_department_form" type="text" style="width: 95%" readonly>
                           <input name="cd_department_form" id="cd_department_form" type="hidden"  style="width: 95%">
                        </div>
                    </div>

                    
                    
                    <div class="w2ui-field w2ui-span6">
                        <label><?php echo($job_resp);?>:</label>
                        <div>
                            <input name="ds_jobs_responsible_form" id="ds_jobs_responsible_form" type="text" style="width: 95%" readonly>
                            <input name="cd_jobs_responsible_form" id="cd_jobs_responsible_form" type="hidden" style="width: 95%" readonly>

                        </div>
                    </div>

                    <div class="w2ui-field w2ui-span6">
                        <label><?php echo($notes);?>:</label>
                        <div>
                            <input name="ds_notes_form" id="ds_notes_form" type="text" onchange="setAsChanged();" style="width: 95%"></select>
                        </div>
                    </div>

                    <div class="w2ui-field w2ui-span6">
                        <label><?php echo($deactivated);?>:</label>
                        <div>
                            <input name="dt_deactivated_form" id="dt_deactivated_form" onchange="setAsChanged();" type="text" style="width: 80px; text-align:center"></select>
                        </div>
                    </div>

                

                </div>
                <div id='toolbarform' style="width: 100%;"> </div>

            </div>

        </div>
            
    </form>
</div>

<script type="text/javascript">

var res = <?php echo($resultset);?> ;
var formChanged = false;
var typePickList = '';


if ( w2ui['toolbarform'] != undefined ) {
    w2ui['toolbarform'].destroy();
}

//console.log(res);
function onPopupCreated() {
    $('#dt_deactivated_form').w2field({type:'date', format:'mm/dd/yyyy'});
    //$("#cd_department_form").chosen();
    //$("#cd_jobs_responsible_form").chosen();
    lockFormMainScreen('', true);
    
    // significa que eh update!!
    if (codePK != -1){
        $("#cd_jobs_form").val(codePK);
        $("#ds_jobs_form").val(res.ds_jobs);
        $("#ds_notes_form").val(res.ds_notes);
        $("#dt_deactivated_form").val(dateFormatToForm(res.dt_deactivated));             

        $("#cd_department_form").val(res.cd_department);
        $("#ds_department_form").val(res.ds_department);             

        $("#cd_jobs_responsible_form").val(res.cd_jobs_responsible);
        $("#ds_jobs_responsible_form").val(res.ds_jobs_responsible);             
      }
    
      pickListSet ('ds_department_form', 'openPicklistdep');
      pickListSet ('ds_jobs_responsible_form', 'openPicklistJob');

        
    	$('#toolbarform').w2toolbar({
		name: 'toolbarform',
                onClick: function(event) {
                    if(event.target == "update") {
                        updateForm();
                    }
      }	
      });
      
      toolbarAddSpacer(w2ui['toolbarform']);
      toolbarAddUpd   (w2ui['toolbarform']);

    
    UnlockFormMainScreen();
    
      $( "#main_form_div" ).on( "dialogbeforeclose", function( event, ui ) {
 
      if (picklistIsOpen) {
         return false;
      }

      if (formChanged) {       

         w2confirm('<?php echo($info_changed);?>', '<?php echo($confirm);?>',
         function (btn) { 
                 if (btn == 'Yes') {
                     closePopup();
                 }; 
             });

         return false;
      
      } 
      
   } );
    


}

function updateForm(){
    if (hasRequiredInformation() == false) {
        messageBoxAlert('<?php echo ($reqinfo);?>');
        return;
    }
    
    
    if ($("#dt_deactivated_form").val() != "") {
        if (!w2utils.isDate($("#dt_deactivated_form").val())) {
            messageBoxAlert('<?php echo ($invdate);?>');
            return;
        }
    }
    

    doUpdate();
}

function setAsChanged() {
    formChanged = true;
}

function doUpdate(){
      if (!formChanged) {
          return;
      }
      lockFormMainScreen('<?php echo ($upd);?>', true);
      $.post(
              
        controllerName + "/updateForm",
       $("#formuser").serialize(),
        
        function(data) {
           if (data=="OK") {
               doGridRetrieve(false);
               formChanged = false;
               closePopup()
               toastUpdateSuccess();
               
           } else {
               toastErrorBig("<?php echo ($upderr);?>" + data);
  

           }
            UnlockFormMainScreen();

        },
        "text"
    );

}

function openPicklistdep() {
   lockFormMainScreen('', false);
   id = $("#cd_department_form").val();  

   if (id == '') {
      id = '-1';
   }
   typePickList = 'dep';
   //var varmodel = '<?php echo ($this->encrypt->encode('material/product_supplier_details_model'));?>';
   //basicPickListOpen ('SelectPL', 'job_department_model', id, 400, 400);
}



function openPicklistJob() {
   lockFormMainScreen('', false);
   id = $("#cd_jobs_responsible_form").val();  

   if (id == '') {
      id = '-1';
   }
   typePickList = 'job';
   //basicPickListOpen ('SelectPL', 'job_model', id, 400, 400);
}



// retorno do picklist;
function onPLoptionSelected(id, desc) {
   if (typePickList == 'dep') {
      $("#cd_department_form").val(id);
      $("#ds_department_form").val(desc);             
   } else {
      $("#cd_jobs_responsible_form").val(id);
      $("#ds_jobs_responsible_form").val(desc); 
   }
   setAsChanged();  
}



function closePopup() {
    formChanged = false;
    $( "#main_form_div" ).dialog('close');
    unbindHandlersPL();
    unbindHandlerForm();
}


</script>


</body>
</html>