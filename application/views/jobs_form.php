 
<form name="formuser" id="formuser">
 
<div id="form" style="width: 490px; height: 240px;margin-left: -5px;">

            <div style="width: 490px; float: left">

                    <div class="w2ui-field w2ui-span6">
                        <label><?php echo($code);?>:</label>
                        <div>
                           <input name="cd_jobs_form" id="cd_jobs_form" id="cd_human_resource_form" type="text" maxlength="64" style="width: 60px" readonly="readonly" />
                        </div>
                    </div>
 
                    
                    <div class="w2ui-field w2ui-span6">
                        <label><?php echo($job);?>:</label>
                        <div>
                            <input name="ds_jobs_form" id="ds_jobs_form" onchange="setAsChanged();" type="text" maxlength="64" style="width: 95%;text-transform: uppercase" autofocus/>
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
                            <input name="ds_notes_form" id="ds_notes_form" type="textarea" onchange="setAsChanged();" style="width: 95%"></select>
                        </div>
                    </div>

                    <div class="w2ui-field w2ui-span6">
                        <label><?php echo($deactivated);?>:</label>
                        <div>
                            <input name="dt_deactivated_form" id="dt_deactivated_form" onchange="setAsChanged();" type="text" style="width: 100px; text-align:center"></select>
                        </div>
                    </div>

                <div id='toolbarform' style="width: 490px; padding-right: 10px"> </div>

            </div>

            
</div>
</form>
   
<script type="text/javascript">

var res = <?php echo($resultset);?> ;
var formChanged = false;
var typePickList = '';

if ( w2ui['toolbarform'] != undefined ) {
    w2ui['toolbarform'].destroy();
}

onPopupCreated();
//console.log(res);
function onPopupCreated() {
    //$('#dt_deactivated_form').w2field({type:'date', required: true});

    if (w2ui['formJobs'] != undefined ) {
       w2ui['formJobs'].destroy();
    }

       
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


   codePKform = codePK == -1 ? '' : codePK;

    $('#form').w2form({ 
        name  : 'formJobs',
        record: { 'cd_jobs_form': codePKform, 'ds_jobs_form' : res.ds_jobs,
                  'cd_department_form':res.cd_department, 'cd_jobs_responsible_form' : res.cd_jobs_responsible ,
                  'ds_department_form':res.ds_department, 'ds_jobs_responsible_form' : res.ds_jobs_responsible ,
                  'ds_notes_form': res.ds_notes, 'dt_deactivated_form': dateFormatToForm(res.dt_deactivated)},
        fields: [
            { name: 'cd_jobs_form', type: 'int' },
            { name: 'ds_jobs_form', type: 'text', required: true },
            { name: 'cd_department_form',  type: 'int' },
            { name: 'ds_department_form',  type: 'text', required: true },
            { name: 'cd_jobs_responsible_form',   type: 'int'},
            { name: 'ds_jobs_responsible_form',   type: 'text'},

            { name: 'ds_notes_form',   type: 'textarea'},
            { name: 'dt_deactivated_form',   type: 'date'}
        ],
    });
    
 

      pickListSet ('ds_department_form', openPicklistdep);
      pickListSet ('ds_jobs_responsible_form', openPicklistJob);

}

function updateForm(){
   var valid = w2ui.formJobs.validate();
  if (valid.length > 0) {
     return;
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
               toastErrorBig(javaMessages.error_upd + data);
  
           }

        },
        "text"
    );

}

function openPicklistdep() {

   id = $("#cd_department_form").val();  

   if (id == '') {
      id = '-1';
   }
   typePickList = 'dep';
   
   var varmodel = '<?php echo ($this->encodeModel('job_department_model'));?>';

   basicPickListOpen ( { model: varmodel,
                         title: '<?php echo ($department);?>',
                         sel_id : id,
                         plCallBack: onPLoptionSelected 
                        }
                     );
                  
   
}



function openPicklistJob() {
   id = $("#cd_jobs_responsible_form").val();  

   if (id == '') {
      id = '-1';
   }
   typePickList = 'job';
   
   var varmodel = '<?php echo ($this->encodeModel('job_model'));?>';
   basicPickListOpen ( { model: varmodel,
                         title: '<?php echo ($job);?>',
                         sel_id : id,
                         plCallBack: onPLoptionSelected 
                        }
                     );
}

// retorno do picklist;
function onPLoptionSelected(id, desc, record) {
   if (typePickList == 'dep') {
      
      w2ui['formJobs'].record['cd_department_form'] = id; 
      w2ui['formJobs'].record['ds_department_form'] = desc; 
      
      //$("#cd_department_form").val(id);
      //$("#ds_department_form").val(desc);             
   } else {

      w2ui['formJobs'].record['cd_jobs_responsible_form'] = id; 
      w2ui['formJobs'].record['ds_jobs_responsible_form'] = desc; 

      //$("#cd_jobs_responsible_form").val(id);
      //$("#ds_jobs_responsible_form").val(desc); 
   }

   w2ui['formJobs'].refresh();

   setAsChanged();  
}



function closePopup() {
    formChanged = false;
    SBSModalFormsVar.close();
}

$( window ).on( "onCloseForm", function( ) {
  if (formChanged) {
     messageBoxOkCancel(javaMessages.info_changed_close, function() {closePopup();})
  } else {
     closePopup();
  }
});


</script>
