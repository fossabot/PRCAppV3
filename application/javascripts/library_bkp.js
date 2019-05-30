/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


var cgbChosenF = {};
var siteToFormModal = "";
var modalForm;
var picklistIsOpen = false;
//var picklistColor  = '#FEFF9B';
var picklistColor  = '#C0DCC0';
var SBSUpdModel = '';
var SBSHasChanges = false;
var dateMask      = 'mm/dd/yyyy';
var picklistCallBack = undefined;
var tag2InitialData = [];
var select2OnChangeFunction = [];
var w2OnChangeFunction = [];
var w2TagStarted= [];

var oldGridadd = w2obj.grid.prototype.add;


w2obj.grid.prototype.addOld = w2obj.grid.prototype.add;

w2obj.grid.prototype.add = function (record) {
   // evento para uso do sistema!
   var eventDataSystem = this.trigger({ phase: 'before', type: 'addSystem', target: this.name, onComplete: null });
   if (eventDataSystem.isCancelled === true) return;
   
   var eventData = this.trigger({ phase: 'before', type: 'add', target: this.name, onComplete: null });
   if (eventData.isCancelled === true) return;

   this.addOld(record)

   this.trigger($.extend(eventDataSystem, { phase: 'after' }));
   this.trigger($.extend(eventData, { phase: 'after' }));
   
}


w2obj.grid.prototype.retrieve = function (options) {
   defoptions = {level:1, controller: controllerName };
   $.extend(defoptions, options);
   
   checkGridRetrieve(defoptions.level, this.name, defoptions.controller);
}


function openpage (page, title){
    if (page!='#') {
        //frames['content'].location.href = page;
        //document.getElementById("content").innerHTML = page;
        removeTrash();
        $('#content-body').html(javaMessages.loading)          
        $('#content-body').load(page);
        $('.choption').html(title);
    }
}
    
function removeTrash() {

   $('#content-body').empty();
   $('#content-body *').off();
   posGridRetrieve = undefined;
   posSBSClosed    = undefined;
   cgbChosenF = {};
   picklistIsOpen = false;  
   SBSUpdModel = '';
   SBSHasChanges = false;
   picklistCallBack = undefined;
   tag2InitialData = [];
   select2OnChangeFunction = [];
   w2OnChangeFunction = [];
   w2TagStarted= [];
   // removo tudo relacionado ao w2ui.
   for (var o in w2ui) {
      w2ui[o].destroy();
      //console.log(o);
   }
   
}    

function dateFormatToForm(datein) {
    return w2utils.formatDate(datein);
}


function toastUpdateSuccess() {
    toastSuccess( javaMessages.updated );
}

function toastSuccess(message){
    toastr.options.closeButton=false;
    toastr.options.showDuration = 500;
    toastr.options.timeOut = 500;
    toastr.options.extendedTimeOut = 1000;
    toastr.options.positionClass="toast-top-right";
    toastr.success(message);
}

function toastErrorBig(message) {
    toastr.options.closeButton=true;   
    toastr.options.showDuration = 0;
    toastr.options.timeOut = 0;     
    toastr.options.extendedTimeOut = 0;
    toastr.options.positionClass="toast-top-full-width";
    toastr.error(message);

}

function waitMsgON(){
    //w2utils.lock(div, '', true);
    $.blockUI({ message: '<h1> '+javaMessages.moment+'</h1>' });  
}

function waitMsgOFF(){
    $.unblockUI();  
}

function hideFilter() {
    $(".showfilter").slideToggle("slow", function() {
        onFilterHidden();
    });
}

function hasRequiredInformation() {
  var ret = true;
  $( ".w2ui-required :input" ).each(function( ) {
     if ($(this).val() === "" && $(this).attr('id') != undefined) {
         ret = false;
     }
          
  });
  
  return ret;
}

// funcao que seta o tamaanho do grid
function setGridHeight(grname) {
    
    if (grname == undefined) {
        grname = gridName;
    }
    
    // essa funcao tem que existir dentro de cada pagina, pois pode mudar a area livre
    var hAvail = getAvailHeight();
    $("#myGrid").css("height", hAvail - 20); 
    w2ui[grname].resize();   
    //grname = undefined;
}


// funcoes de filtros
function retFilterInformed(level, filterNames) {
    if (level==undefined) {
        level = "1";
    }

       if (filterNames==undefined) {
        filterNames = [];
    }

    retr = "";
    $('.simple_filter_upper').each(function () {
       
        vlrc = $(this).val();
        vlrc = vlrc.toUpperCase();
        itid = $(this).attr('id');
        likesearch = $(this).attr('likeSearch'); 
        likehow = $(this).attr('like'); 
        
        if (likehow == 'I') {
           likehow = "ilike";
        } else {
            likehow = "like"
        }
        

        
        if (filterNames.length > 0 &&  filterNames.indexOf(itid) == -1  ) {
           return true;
        }
        
        if (vlrc != "" && level == $(this).attr('lvl')) {
           if (likesearch == 'S') {
              vl =  "'"+vlrc+"%'";          
           } else {
              vl =  "'%"+vlrc+"%'";                        
           }
            // montagem do SQL;
            sql = " and " + $(this).attr('sqlid') + " " + likehow + ' ' + vl ;
            
            retr = retr + itid+"<AA>"+vlrc+"<AA>"+sql+"<XX>";
        }
    });
    
    $('.simple_filter_yesno').each(function () {

       vdata = $(this).select2('data');

       vlrc = vdata.id;
       itid = $(this).attr('id');
       sqlfilter = $(this).attr('sqlid');

        if (filterNames.length > 0 &&  filterNames.indexOf(itid) == -1  ) {
           return true;
        }


        if (vlrc != "A"  && level == $(this).attr('lvl')) {
                        
            retr = retr + sqlfilter+"<AA>"+vlrc+"<AA>"+"NONE"+"<XX>";
        }
    
    });

    $('.picklist_filter').each(function () {

       //vlrc = $(this).val();
       //vlrc = vlrc.toUpperCase();
       vdata = $(this).select2('val');
       itid = $(this).attr('id');

       if (vdata == '') {
          vlrc = '-1';
       } else {
          vlrc = vdata;          
       }
        if (filterNames.length > 0 &&  filterNames.indexOf(itid) == -1  ) {
           return true;
        }

        
        if (vlrc != "-1"  && level == $(this).attr('lvl')) {
            // montagem do SQL;
            // sendo Y significa que usa exists no where, entao manda diferente!
            if ($(this).attr('cgbexists') == 'Y') {
               sql =  $(this).attr('sqlid');
               retr = retr + itid+"<AA>"+vlrc+"<AA>"+sql+"<XX>";
            } else {
               sql = " and " + $(this).attr('sqlid') + " = "+vlrc;
               retr = retr + itid+"<AA>"+vlrc+"<AA>"+sql+"<XX>";               
            }
            
        }
    });

   
    
    if (retr != "") {
        retr = retr.substr(0, retr.length - 4);   
    }
            
    //alert (retr);        
            
    return retr;

    
}

// funcoes de grid:::::
function checkGridRetrieve(level, grname, cName)
{
    if (grname == undefined) {
        grname = gridName;
    }

    if (cName == undefined) {
       cName = controllerName;
    }
    
    if (level == undefined) {
        level = "1";
    }
    
    if (w2ui[grname].getChanges().length > 0 ){
        w2confirm(javaMessages.confirm_retrieve, javaMessages.confirm,
        function (btn) { 
                if (btn == 'Yes') {
                   
                   doGridRetrieve(true, level, grname, cName);
                   
                   
                }; 
            });
    
    } else {
        doGridRetrieve(true, level,  grname, cName);
    }
}

function doGridRetrieve(checkHideFilter, level, grname, cName) {
       if (level == undefined) {
           level = "1";
       }
       
    if (grname == undefined) {
        grname = gridName;
    }

    if (cName == undefined) {
         cName = controllerName;
    }
    
     w2ui[grname].lock(javaMessages.loading, true);
              
      $.post(
              
        cName + "/retrieveGridJson",
        {filter: retFilterInformed(level)},
        function(data) {
                       
            w2ui[grname].clear();
            w2ui[grname].add(data);
            w2ui[grname].unlock();

            var filt = w2ui[grname].toolbar.get('hidefilter');

             if (filt != null && checkHideFilter){
                 if (filt.checked && $('.showfilter').is(":visible") && w2ui[grname].records.length != 0)    {
                     hideFilter();
                 }
             }
             
             if (typeof posGridRetrieve == 'function') { 
                    posGridRetrieve(data.length); 
                }
        },
        "json"
    );
}

function doGridUpdate(level, grname, cName, funcAfterUpdate){
    if (level == undefined) {
        level = "1";
    }
    
    if (grname == undefined) {
        grname = gridName;
    }

    if (cName == undefined) {
       cName = controllerName;
    }
    
    changes = w2ui[grname].getChanges();
    if (changes.length == 0) {
        return;
    }
    
      w2ui[grname].lock(javaMessages.updating, true);
      $.post(
              
         cName + "/updateDataJson",
       {"upd":JSON.stringify(changes)},
        
        function(data) {
           w2ui[grname].unlock();
           if (data=="OK") {
              
               if (funcAfterUpdate != undefined) {
                  funcAfterUpdate();
               }
           

              
               doGridRetrieve(false);
               toastSuccess(javaMessages.update_done);
           } else {
               toastErrorBig(javaMessages.error_upd + data);
           }
        },
        "text"
    );

}

function doGridInsertRow(grname, cName, funcAfterInsert) {
        if (grname == undefined) {
            grname = gridName;
        }
        
        if (cName == undefined) {
           cName = controllerName;
        }

       w2ui[grname].lock(javaMessages.inserting, true);
      $.post(
              
        cName + "/retInsJson",
        '',
        function(data) {   
           w2ui[grname].add(data);
           w2ui[grname].refresh();
           w2ui[grname].unlock();
           
           if (funcAfterInsert != undefined) {
              funcAfterInsert(data.recid);
           }
           
           w2ui[grname].editField(data.recid, 1);
          //$("#showgrid").html(data);
        },
        "json"
    );
        
}

function checkGridDelete(grname, cName, funcAfterDelete)
{
    if (grname == undefined) {
        grname = gridName;
    }

    var gSel = w2ui[grname].getSelection();
        if (gSel.length == 0) {
        return;
    }

    w2confirm(javaMessages.conf_delete, javaMessages.confirm,
    function (btn) { 
            if (btn == 'Yes') {
                doGridDelete(grname, cName, funcAfterDelete);        
            }; 
        });
}

function doGridDelete(grname, cName, funcAfterDelete) {
    
    if (grname == undefined) {
        grname = gridName;
    }

    if (cName == undefined) {
       cName = controllerName;
    }
    var gSel = w2ui[grname].getSelection();
        if (gSel.length == 0) {
        return;
    }

      w2ui[grname].lock(javaMessages.deleting, true);
      $.post(
              
        cName + "/deleteDataJson",
       {"del":JSON.stringify(gSel)},
        
        function(data) {
           w2ui[grname].unlock();
           if (data=="OK") {
               
                // apago um a um pq nao funciona tudo junto. sei lah
                for (index = 0; index < gSel.length; ++index) {
                    w2ui[grname].remove(gSel[index]);
                }
               

               if (funcAfterDelete != undefined) {
                  funcAfterDelete(gSel);
               }

               
               //w2ui[grname].refresh();
               toastSuccess(javaMessages.del_done);
           } else {
               toastErrorBig(javaMessages.error_del + data);
           }
           
           //alert(data);
        },
        "text"
    );
}

function gridSetItem(rec, field, value, grid) {
   rec.changed = true;
   
   rec.changes = rec.changes || {};

   rec[field] = value;
   
   rec.changes[field] = value;
   
   grid.set(rec.recid, rec);
}

function gridResetItem(rec, grid) {
   rec.changed = false;
   rec.changes = undefined;
   
   grid.set(rec.recid, rec);
}

function gridSetItemBasic(grid, recid, field, value) {
   rec = w2ui[grid].get(recid);
   gridSetItem(rec, field, value, w2ui[grid]);
}            

function gridGetItem(gridname, recid) {
   var changes = w2ui[gridname].getChanges();
   var record  = w2ui[gridname].get(recid);
   var changes_recid = [];
   var ret = [];
   
   // encontrou asalteracoes para o recid
   for (var o in changes) {
      if (changes[o].recid == recid) {
         changes_recid = changes[o];
      }
   }
   
   
   $.extend(ret, record, changes_recid);
      
   return ret;

}


function openFormUi (title, site, widthx, heighty) {
   $('#main_form_div').load(site, function() {
      onPopupCreated();
    });
   
   $( "#main_form_div" ).dialog({
       dialogClass: "dialogClass",
       modal: true,
       height : heighty,
       width  : widthx,
       title: title,
       position: { my: "center", at: "center", of: window },
       resizable: false,
       close: function( event, ui ) { 
         $( "#main_form_div" ).dialog('destroy');
         $( "#main_form_div" ).empty();
    },

    });



}

// funcoes de picklists
function pickListSet (target, functorun) {
   $( "#" + target ).css({'background-color' : picklistColor });
   $( "#" + target ).css({'color' : '#000000' });
   $( "#" + target ).css({'cursor' : 'pointer' });
   $( "#" + target ).prop('readonly' ,true);
   $( "#" + target ).attr('isPL' , 'Y');


   $( "#" + target ).dblclick(function() {
      functorun(target);
    });
    
   $( "#" + target ).bind('keypress', function(e) {
      if (e.keyCode == 13) {
         functorun(target);
      }
   });   
    
}

function basicPickListOpen(options) {
   myOptions = { model: 'NONE',
                 controller: 'NONE',
                 width: 400,
                 height: 400,
                 title: 'Title',
                 sel_id:  -1,                 
                 relation: { id: -1, idwhere: -1 },
                 plCallBack: function(id, text, data) {
                    alert('Missing Callback');
                 }
              };
              
   $.extend(myOptions, options);
   
   
   sitetopen = myOptions.model != 'NONE' ? 'basicpicklist/makePLModal' : myOptions.controller; 
                
   $('#main_form_div_picklist').load(sitetopen, 
   {"id"      : myOptions.sel_id,
    "relation":JSON.stringify(myOptions.relation),
    'model'   :  myOptions.model
   },
   function() {
      picklistIsOpen = true;
      picklistCallBack = myOptions.plCallBack;

      basicPickListOpenDialog(myOptions.title, myOptions.width, myOptions.height);
   });

   
}



function basicTextPLOpen(options) {
   myOptions = { controller: 'basictextpl/makeTextPLModal',
                 width: 400,
                 height: 350,
                 title: 'Title',
                 text:  '',                 
                 plCallBack: function(saved, text) {
                    alert('Missing Callback');
                 }
              };
              
   $.extend(myOptions, options);
   
   
   sitetopen = myOptions.controller; 
                
   $('#main_form_div_picklist').load(sitetopen, 
   {"text"      : myOptions.text
   },
   function() {
      picklistIsOpen = true;
      picklistCallBack = myOptions.plCallBack;

      basicPickListOpenDialog(myOptions.title, myOptions.width, myOptions.height);
   });

   
}



function basicPickListOpenDialog(title,  widthx, heighty) {
      $( "#main_form_div_picklist" ).dialog({
          //dialogClass: "dialogClass",
          dialogClass: "main_form_div_picklist",
          modal: true,
          height : heighty,
          width  : widthx,
          title: title,
          position: { my: "center", at: "center", of: window },
          resizable: false,
          open: function (event, ui) {
             $(".main_form_div_picklist .ui-dialog-titlebar").css('background', picklistColor);

          },
          close: function( event, ui ) { 
            $( "#main_form_div_picklist" ).dialog('destroy');
            $( "#main_form_div_picklist" ).empty();
            // quando ele fecha ele destroy os callbacks e picklistopen
            picklistIsOpen = false;
       },
     });
   
}


function basicSelectSBS (title, model_and_function_retrieve, model_and_function_upds, id ) {
   // quando nao tem controller, o funcORModel eh o model que tem o plselect
   site        = "basicselectsbs/makeSBSmodal";
   SBSUpdModel = "basicselectsbs/updSBSmodal";
   $('#main_form_div_picklist').load(site, 
   
   {"modelRet": model_and_function_retrieve,
    "modelUpd": model_and_function_upds,
    "id":  id
   },
 
   function() {
      //onPicklistPopupCreated();
      picklistIsOpen = true;

      $( "#main_form_div_picklist" ).dialog({
          //dialogClass: "dialogClass",
          dialogClass: "main_form_div_picklist",
          modal: true,
          height : 540,
          width  : 705,
          title: title,
          position: { my: "center", at: "center", of: window },
          resizable: false,
          open: function (event, ui) {
             $(".main_form_div_picklist .ui-dialog-titlebar").css('background-image:', '-webkit-linear-gradient(#dae6f3, #c2d5ed)');

          },
          close: function( event, ui ) { 
            $( "#main_form_div_picklist" ).dialog('destroy');
            $( "#main_form_div_picklist" ).empty();

             if (typeof posSBSClosed == 'function') { 
                    posSBSClosed(SBSHasChanges); 
                    SBSHasChanges = false;
                }


            picklistIsOpen = false;
       },
     });
   });
   
}

function getSBSUpdModel() {
   return SBSUpdModel;
}

// final das funcoes de picklists

// functions de toolbar
function toolbarAddIns(toolbarobj) {
    toolbarobj.add({id:"insert",hint:javaMessages.ins_line,icon:"fa fa-plus",caption:"",type:"button"});
}

function toolbarAddUpd(toolbarobj) {
    toolbarobj.add({id:"update",hint:javaMessages.update ,icon:"fa fa-floppy-o",caption:"",type:"button"});
}

function toolbarAddDel(toolbarobj) {
    toolbarobj.add({id:"delete",hint:javaMessages.deleteMsg,icon:"fa fa-trash-o",caption:"",type:"button"});
    }
function toolbarAddClose(toolbarobj) {
    toolbarobj.add({id:"close",hint:javaMessages.close_screen,icon:"fa fa-times",caption:"",type:"button"});
}

function toolbarAddSpacer(toolbarobj) {
    toolbarobj.add({id:"spacer",hint:"",icon:"",caption:"",type:"spacer"});
    }
    
    
/*
    
// funcoes antigas e desabilitadas. Deixando apenas por questao de conhecimento

function ChosenFilterRetrieve (filterId ){
     var cName;
     var fId;
     fId = "#"+filterId;
     var selec = "";
     
     cName = cgbChosenF[filterId].controller;
          
     if (cgbChosenF[filterId].showDeactivated) {
         cName = cName + "/0";
     } else {
         cName = cName + "/1";
     }
     
     if (cgbChosenF[filterId].selectedId != undefined && cgbChosenF[filterId].selectedId != null) {
        cName = cName +"/" +cgbChosenF[filterId].selectedId;
     }
     
     waitMsgON();
     
     $.post(         
        cName,
        "",
        function(data) {
            //if (!$.isArray(data)) data = [data];
            for (var o in data.items) {
               //$(fId).append($('<option>'), {value: data[o].code, text: data[o].desc });

                
            if (cgbChosenF[filterId].selectedId === data.items[o].recid ) {
                selec = true;
            } else{
                selec = false;    
            }
                
            if(data.items[o].fl_active == 'Y') {
                bcolor = 'black';
            } else {
                bcolor = 'red';
            }
               
            $(fId).append($('<option></option>')
                    .attr('value', data.items[o].recid )
                    .attr("selected", selec)
                    .text(data.items[o].description).css('color', bcolor));
            }


            $(fId).attr('retrieved', 'true');
            $(fId).trigger('chosen:close');
            $(fId).trigger('chosen:updated');
            waitMsgOFF();
            if (cgbChosenF[filterId].reopenDropDown) {
                $(fId).trigger('chosen:open');
            }
            $(fId).trigger('chosen:loadFinished');
            
            // limpo a variavel
            cgbChosenF[filterId] = {};
            
        },
        "json"
    );
    

}


// funcoes de chosen.
function ChosenFilterSet(opts) {
    
    filterId        = opts.filterId;
    
    retrieveonnLoad = opts.retrieveOnLoad;
    selectedId      = opts.selectedId ;
    selectedDesc    = opts.selectedDesc;
    showDeactivated = opts.showDeactivated;
    reopenDropDown  = opts.reopenDropDown;
    
    if (showDeactivated == undefined) {
        showDeactivated = false;
    }
    
    if (reopenDropDown == undefined) {
        reopenDropDown = false;
    }
    
    if (retrieveonnLoad == undefined) {
        retrieveonnLoad = false;
    }
    
    if (selectedId!= undefined && opts.selectedDesc == undefined && !retrieveonnLoad) {
        alert('Cannot have selectedId without desc when choose to not retrieve on load');
    }
    
    var fId;
    fId = "#"+filterId;
    cName = $(fId).attr('controller');

    
    cgbChosenF[opts.filterId] = { retrieveOnLoad  : retrieveonnLoad ,
                                  selectedId      : selectedId,
                                  selectedDesc    : selectedDesc,
                                  showDeactivated : showDeactivated,
                                  reopenDropDown  : reopenDropDown,
                                  controller      : cName
                              }
                                  
    
    

    
    
    $(fId).chosen();
    
    if (!retrieveonnLoad) {
     $(fId).on('chosen:showing_dropdown', function(evt, params) {
         
         var ret = $(fId).attr('retrieved');
         if (ret=='false') {
             ChosenFilterRetrieve(params.chosen.form_field.id);
         }
     });
    } else {
        ChosenFilterRetrieve(filterId );
    }
    
}
*/