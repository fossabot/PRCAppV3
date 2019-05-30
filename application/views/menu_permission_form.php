<div class="container-fluid">
<div class="showfilter row small-padding" style="padding-top: 10px;" id='filtersPL'>
   <div class="filter_groupbox col-md-12" style="height: 80px;" > 
      <div class="filter_groupbox_legend" style='width:200px;'><?php echo ($copyfrom);?></div>
      <div class="picklist_filter_frame col-md-2" id="cd_hr_type_frame">
         <label for="picklist_filter" id="plLabel"> </label>
            <input type="hidden" name="picklist_filter" id="cd_copy_from" class="picklist_filter input-sm" style="max-width: 200px; min-width: 200px"   lvl="100" controller="usermaint/retPickList" multi = "N"  deactFilter = "1"  hasDeact = "Y" />   
      </div>
      
      <div class="picklist_filter_frame" id="cd_radio_frame" style='width: 100px;  padding-left: 50px; padding-top: 20px;'>
              <div class="form-group">
                <label>
                  <input type="radio" name="r_copymerge" id='r_copy' class="minimal" value='copy' checked> <?php echo ($copyMsg)?>
                </label>
                <label>
                  <input type="radio" name="r_copymerge" class="minimal" id ='r_merge' value='merge'> <?php echo ($mergeMsg)?>
                </label>
              </div>
      </div>
      
   </div>
      

</div>
    <div id="blaBody" style="height: 500px;" class='row'> </div>

</div>
     


<script>
// aqui tem os scripts basicos. 
//var controllertool = "menu";
var menuType       = "<?php echo($typeMenu); ?>"
var menuCodeRel    = "<?php echo($codeRel); ?>"
var woptions;

if ( w2ui['gridMenuForm'] != undefined ) {
    w2ui['gridMenuForm'].destroy();
}
        
    
function checkGridRetrieveMenu()
{
    
    
    if (w2ui['gridMenuForm'].getChanges().length > 0 ){
       
         messageBoxYesNo(javaMessages.confirm_retrieve , function() {doGridRetrieveMenu(true);} );
    
    } else {
        doGridRetrieveMenu(true);
    
    }
}
           
function doGridUpdateMenu(){
 
    changes = w2ui['gridMenuForm'].getChanges();
    if (changes.length == 0) {
        return;
    }

      w2ui['gridMenuForm'].lock(javaMessages.updating, true);
      $.post(
              
        "menu/updateDataJson/"+menuType +"/"+menuCodeRel,
       {"upd":JSON.stringify(changes)},
        
        function(data) {
           w2ui['gridMenuForm'].unlock();
           if (data.message=="OK") {
               doGridRetrieveMenu(true);
               toastUpdateSuccess();
           } else {
               toastErrorBig(javaMessages.error_upd + data.message );              
           }
        },
        "json"
    );

}

function doGridRetrieveMenu(checkHideFilter) {
       w2ui['gridMenuForm'].lock(javaMessages.loading, true);
      $.post(
              
        "menu/retrieveGridJson/"+menuType+"/"+menuCodeRel,
        {filter: retFilterInformed()},
        function(data) {
            w2ui['gridMenuForm'].clear();
            w2ui['gridMenuForm'].add(data);
        //console.log(w2ui['gridMenuForm'].columns);    
            w2ui['gridMenuForm'].unlock();

            var filt = w2ui['gridMenuForm'].toolbar.get('hidefilter');

             if (filt != null && checkHideFilter){
                 if (filt.checked && $('.showfilter').is(":visible") && w2ui['gridMenuForm'].records.length != 0)    {
                     hideFilter();
                 }
             }
        },
        "json"
    );
}
           

<?php echo ($javascript); ?>


// funcao da toolbar
function onGridToolbarPressedForm(bPressed, dData) {
    if (bPressed == 'retrieve') {
        checkGridRetrieveMenu();
    }
    if (bPressed == "update") {
        doGridUpdateMenu();
    }
    
    if (bPressed == 'filter') {
        hideFilter();
    }
    
    if (bPressed == "copy_merge"){
        copymerge();
    }
}

onPopupCreated(); 

function onPopupCreated() {  
    
    if (menuType == "H") {
        $("#cd_copy_from").attr('controller', 'users_maint/retPickList');
        $('#plLabel').text("<?php echo($user); ?>");

    } else {
        $('#plLabel').text("<?php echo($job); ?>");
        $("#cd_copy_from").attr('controller', 'jobs_maint/retPickList');        
    }
    
    
    //ChosenFilterSet({filterId:"cd_copy_from" , retrieveOnLoad: false, showDeactivated:true, reopenDropDown:true});
   

   select2Start('cd_copy_from', $("#cd_copy_from").attr('controller'), javaMessages.filterPlaceHolderChoose);
    $("#blaBody").w2grid(gridVarForm);
    
   $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass: 'iradio_minimal-blue'
    });
    doGridRetrieveMenu();

}

// calculo da area livre. TEM QUE EXISTIR
function getAvailHeight(){
   var hAvail = $( window ).height() - $( "#maintable" ).height() - $( "#childtable" ).height();
   return hAvail;
}

// funcao chamada quando o filtro some. tem que existir se existir filtro!
function onFilterHidden (){ 
    setGridHeight();
    }
    
function copymerge() {
   
   
   //var sel = document.getElementById('cd_copy_from');
   ret = select2GetData('cd_copy_from');
   
   if (ret == null) {
      messageBoxAlert ("<?php echo($confcopyopt); ?>");
      return;
   }
   
   var selvalue = ret.id,
       seltext  = ret.text;
 
   if (selvalue == '-1') {
       messageBoxAlert ("<?php echo($confcopyopt); ?>");
       return;
   }
   
   //console.log($('#r_copy').is(':checked'));
   
   if ($('#r_copy').is(':checked')) {
      message = '<?php echo ($copyQuestion);?>';
      funcToRun = copy_rights;
   } else {
      message = '<?php echo ($mergeQuestion);?>';
      funcToRun = merge_rights;   
   }
   
   messageBoxYesNo(message + seltext, funcToRun);
   
}

function copy_rights(){
   ret = select2GetData('cd_copy_from');
   
   var selvalue = ret.id,
       seltext  = ret.text;

    var varsite = "menu/copyMergeMenu/"+menuType+"/"+selvalue+"/"+menuCodeRel+"/C"
    
    makeCopyMerge(varsite);
    
}

function merge_rights(){
   ret = select2GetData('cd_copy_from');
   
   var selvalue = ret.id,
       seltext  = ret.text;

   var varsite = "menu/copyMergeMenu/"+menuType+"/"+selvalue+"/"+menuCodeRel+"/M"
    
    makeCopyMerge(varsite);
    
}

function makeCopyMerge(site) {
   
   
      w2popup.lock(javaMessages.updating, true);
       $.post(
        site,
        function(data) {
           if (data.message == "OK") {
               doGridRetrieveMenu(false);
               toastUpdateSuccess();
               
           } else {
               toastErrorBig(javaMessages.error_upd + data.message);
           }
            w2popup.unlock();
        },
        "json"
    );    
}

$( window ).on( "onCloseForm", function( ) {
   SBSModalFormsVar.close();
});




</script>
