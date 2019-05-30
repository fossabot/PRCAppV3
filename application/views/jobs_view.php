<?php include_once APPPATH . 'views/viewIncludeFilter.php'; ?>

<script>
// aqui tem os scripts basicos. 
   var gridName = "gridHM";
   var controllerName = "jobs_maint";
   var mainGridLocked = false;


   if (w2ui[gridName] != undefined) {
      w2ui[gridName].destroy();
   }

   if (w2ui['myLayout'] != undefined) {
      w2ui['myLayout'].destroy();
   }

   if (w2ui['gridHuman'] != undefined) {
      w2ui['gridHuman'].destroy();
   }

   if (w2ui['gridPermission'] != undefined) {
      w2ui['gridPermission'].destroy();
   }

//$(".ds_hr_type").on( "change",  function() {
<?php echo ($javascript); ?>


   $(function () {
      $('#myGrid').w2layout({
         name: 'myLayout',
         panels: [
            {type: 'main', size: 150, minSize: 400, resizable: true},
            {type: 'right', size: 300, minSize: 150, resizable: true,
               tabs: {
                  active: 'tab_hm',
                  tabs: [
                     {id: 'tab_hm', caption: '<?php echo($human) ?>'},
                     {id: 'tab_pm', caption: '<?php echo($sysperm) ?>'},
                  ],
                  onClick: function (event) {
                     //this.owner.content('main', event);
                     setTabs(event.target);

                  }
               }
            }
         ]
      });
   });

   $().w2grid(varGridPermission);
   $().w2grid(varGridHuman);

   w2ui.myLayout.content('main', $().w2grid(gridVar));
   w2ui.myLayout.content('right', w2ui['gridHuman']);


   function setTabs(tabname) {
      if (tabname == "tab_hm") {
         w2ui.myLayout.content('right', w2ui['gridHuman']);
      } else {
         w2ui.myLayout.content('right', w2ui['gridPermission']);
      }
   }

   function posGridRetrieve(retcount) {
      var sel = w2ui[gridName].getSelection();
      if (sel.length == 1) {

         retrieveChild(sel[0]);
      } else {
         retrieveChild(-1);
      }

   }



// funcao da toolbar
   function onGridToolbarPressed(bPressed, dData) {
      if (bPressed == 'insert') {
         w2ui[gridName].insertRow();
      }

      if (bPressed == 'retrieve') {
         w2ui[gridName].retrieve();
      }
      if (bPressed == "update") {
         w2ui[gridName].update();
      }

      if (bPressed == "delete") {
         w2ui[gridName].deleteRow();
      }
      if (bPressed == 'filter') {
         hideFilter();
      }

      if (bPressed == "menu_options") {
         ret = w2ui[gridName].getSelection();

         if (ret.length == 0) {
            return;
         }
         openMenuEdit(ret[0]);
      }

      if (bPressed == "hmundo" || bPressed == "pmundo") {
         childGridUndo();

      }

      if (bPressed == "hmupdate" || bPressed == "pmupdate") {

         doPermissionUpdate();
      }

   }

   w2ui[gridName].on('rowFocusChanging', function (event) {

      event.onComplete = function (event) {

         if (event.recid_new == -1) {
            childGridClear();
         } else {
            doGridHMRetrieve(event.recid_new);
         }
      }

   });


   function retrieveChild(recid) {
      w2ui['gridHuman'].clear();
      w2ui['gridPermission'].clear();

      if (recid != -1) {
         doGridHMRetrieve(recid);
      }


   }


   function openMenuEdit(recidm) {
      codePK = recidm;
      var sit = 'menu/editPermission/J/' + codePK;
      openFormUiBootstrap('<?php echo ($menu_perm); ?>', sit, 'col-md-8 col-md-offset-2');
   }



// calculo da area livre. TEM QUE EXISTIR
   function getAvailHeight() {
      var hAvail = getWorkArea();
      return hAvail;
   }

// funcao chamada quando o filtro some. tem que existir se existir filtro!
   function onFilterHidden() {
      setGridHeightJobs();
   }

   $(window).on('resize.mainResize', function () {
      setGridHeightJobs();
   });

   $("body").on('togglePushMenu toggleFilter', function () {
      setGridHeightJobs();
   });


   setGridHeightJobs();
   w2ui[gridName].retrieve();

// insiro colunas;

// funcao que seta o tamaanho do grid
   function setGridHeightJobs() {

      // essa funcao tem que existir dentro de cada pagina, pois pode mudar a area livre
      var hAvail = getAvailHeight();
      $("#myGrid").css("height", hAvail);
      w2ui[gridName].resize();
      w2ui['gridHuman'].resize();
      w2ui['myLayout'].resize();

   }

   function doGridHMRetrieve(cd_jobs) {

      // tranco a de cima
      w2ui[gridName].lock('<?php echo ($retr_user_perm); ?>', true);

      grname = 'gridHuman';

      w2ui[grname].lock(javaMessages.loading, true);


      $.myCgbAjax({url: controllerName + "/retrieveHRJson/" + cd_jobs,
         box: 'none',
         async: false,
         success: function (data) {
            w2ui[grname].clear();
            w2ui[grname].add(data);
            w2ui[grname].unlock();

            doGridPermRetrieve(cd_jobs);
         }
      });


   }

   function doGridPermRetrieve(cd_jobs) {

      grname = 'gridPermission';

      w2ui[grname].lock(javaMessages.loading, true);

      $.myCgbAjax({url: controllerName + "/retrievePermissionJson/" + cd_jobs,
         box: 'none',
         async: false,
         success: function (data) {
                 w2ui[grname].clear();
                 w2ui[grname].add(data);
                 w2ui[grname].unlock();
                 w2ui[gridName].unlock();
         }
      });

   }


   function childGridClear() {
      w2ui['gridPermission'].clear();
      w2ui['gridHuman'].clear();
   }

   w2ui['gridHuman'].on('change', function (event) {
      if (!mainGridLocked) {
         w2ui[gridName].lock("<?php echo ($upd_perm_area); ?>");
         mainGridLocked = true;
      }
   });

   w2ui['gridPermission'].on('change', function (event) {
      if (!mainGridLocked) {
         w2ui[gridName].lock("<?php echo ($upd_perm_area); ?>");
         mainGridLocked = true;
      }
   });


   function doPermissionUpdate() {

      if (!mainGridLocked) {
         return;
      }

      var sel = w2ui[gridName].getSelection();
      ll_cd_jobs = sel[0];

      var changes = [];
      var chg = w2ui['gridPermission'].getChanges();
      for (index = 0; index < chg.length; ++index) {

         if (chg[index].fl_checked) {
            ls_checked = "Y";
         } else {
            ls_checked = "N";
         }

         changes.push({recid: chg[index].recid, fl_checked: ls_checked, fl_type: "J"});

      }

      var chg = w2ui['gridHuman'].getChanges();

      for (index = 0; index < chg.length; ++index) {

         if (chg[index].fl_checked) {
            ls_checked = "Y";
         } else {
            ls_checked = "N";
         }

         changes.push({recid: chg[index].recid, fl_checked: ls_checked, fl_type: "H"});

      }



      $.post(
              controllerName + "/updatePermissionJson/" + ll_cd_jobs,
              {"upd": JSON.stringify(changes)},
              function (data) {

                 if (data.message == "OK") {
                    toastUpdateSuccess();
                    childGridUndo();
                 } else {
                    toastErrorBig(javaMessages.error_upd + data.message);
                 }
              },
              "json"
              );

   }

   function childGridUndo() {
      // refaz o retrieve e libera o grid principal
      if (mainGridLocked) {
         ret = w2ui[gridName].unlock();
         mainGridLocked = false;
      }
      var sel = w2ui[gridName].getSelection();
      if (sel.length == 0) {
         return;
      }
      doGridHMRetrieve(sel[0]);
   }

    makeFilterWithEnter(function () {
        onGridToolbarPressed('retrieve');
    });
</script>

<?php include_once APPPATH . 'views/includeViewResizeDiv.php'; ?>
