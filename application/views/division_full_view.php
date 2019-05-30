<div class="showfilter row" id='showfilter'>
   <?php echo $filters ?>  
   <script><?php echo $filters_java ?></script>
</div>

<script>
// aqui tem os scripts basicos. 
   var gridName = "varprd_division";
   var controllerName = "division";
   var filters = [];
   var sideGrids;
   var sideControllers;

// parte onde o controller vai jogar o javascrip do grid e etc...
<?php echo ($javascript); ?>

   $().w2grid(varprd_division);
   $().w2grid(varprd_division_brand);

   $().w2grid(varprd_division_related);
   $().w2grid(varprd_division_brand_related);

   w2ui['varprd_division_related'].hideColumn('recid');
   w2ui['varprd_division_brand_related'].hideColumn('recid');

   w2ui["varprd_division"].on("rowFocusChanging", function (target, event) {
      event.onComplete = function () {
         doSideGridsAction();
      }
   });


   w2ui["varprd_division_brand"].on("rowFocusChanging", function (target, event) {
      event.onComplete = function () {
         doSideGridsAction();
      }
   });


   $('#myGrid').w2layout({
      name: 'myFullLayout',
      panels: [
         {type: 'main', size: 150, minSize: 400, resizable: true, content: w2ui['varprd_division']},
         {type: 'right', size: 150, minSize: 300, resizable: true, content: w2ui['varprd_division_brand_related']},
      ]
   });


   //$('#divisiontab').html(w2ui['varprd_division'].header);
   //$('#brandtab').html(w2ui['varprd_division_brand'].header);


   function onTabChanged(selectedTab) {
      switch (selectedTab) {
         case 'tab_division' :
            w2ui['myFullLayout'].content('main', w2ui['varprd_division']);
            w2ui['myFullLayout'].content('right', w2ui['varprd_division_brand_related']);

            sideGrids = 'varprd_division_brand_related';
            sideControllers = "division_brand/retrieveGridJsonDivBrand";

            gridName = "varprd_division";
            controllerName = "division";

            $('#ds_division_frame').show();
            $('#ds_division_brand_frame').hide();
            $('#cd_division_x_division_brand_frame').hide();
            filters = ['ds_division', 'dt_deactivated'];

            break;
         case 'tab_brand' :
            w2ui['myFullLayout'].content('main', w2ui['varprd_division_brand']);
            w2ui['myFullLayout'].content('right', w2ui['varprd_division_related']);

            sideGrids = 'varprd_division_related';
            sideControllers = "division/retrieveGridJsonDiv";

            gridName = "varprd_division_brand";
            controllerName = "division_brand";
            $('#ds_division_frame').hide();
            $('#ds_division_brand_frame').show();
            $('#cd_division_x_division_brand_frame').show();
            filters = ['ds_division_brand', 'dt_deactivated', 'cd_division_x_division_brand'];

            break;
      }

   }

// funcao da toolbar
   function onGridToolbarPressed(bPressed, dData) {
      if (bPressed == 'insert') {
         doGridInsertRow();
      }
      if (bPressed == 'retrieve') {
         doGrpGridRetrieve();
      }
      if (bPressed == "update") {
         doGridUpdate();
      }

      if (bPressed == "delete") {
         checkGridDelete();
      }
      if (bPressed == 'filter') {
         hideFilter();
      }

      if (bPressed == 'sideedit') {
         if (getSelectedTab() == 'tab_division') {
            openRelationDivision();
         } else {
            openRelationDivBrand();
         }

      }
   }



   function setGrpGridHeight() {

      var hAvail = getWorkArea();
      $("#myGrid").css("height", hAvail - 40);
      w2ui["myFullLayout"].resize();

   }

// funcao chamada quando o filtro some. tem que existir se existir filtro!
   function onFilterHidden() {
      setGrpGridHeight();
   }

   $(window).on('resize.mainResize', function () {
      setGrpGridHeight();
   });

   $("body").on('togglePushMenu toggleFilter', function () {
      setGrpGridHeight();
   });

   onTabChanged('tab_division');

// ajusta o tamanho do detail.
   setGrpGridHeight();

// retrieve automatico na entrada!
   doGrpGridRetrieve();


   function doGrpGridRetrieve() {
      w2ui[gridName].retrieve({filterNames: filters});
   }


   function doSideGridsAction() {

      var selection = w2ui[gridName].getSelection();
      w2ui[sideGrids].clear();

      if (selection.length === 0) {
         return;
      }

      var rec = w2ui[gridName].get(selection[0]);
      var id = rec.recid;


      $.myCgbAjax({url: sideControllers + "/" + id + '/R',
         box: 'none',
         async: false,
         success: function (data) {
            w2ui[sideGrids].add(data);
         }
      });


   }

//$("#myGrid").on("remove", function () {
//    alert("Element was removed");
//})

   function openRelationDivBrand() {

      relController = '<?php echo ($this->encodeModel('division_model/retGridJsonDivBrand')); ?>';
      relUpdSBSModel = '<?php echo ($this->encodeModel('division_model/updSBSRelDivBrand')) ?>';
      title = w2ui['varprd_division_related'].header;


      var idsel = w2ui[gridName].getSelection();

      if (idsel.length == 0) {
         return;
      }

      if (w2ui[gridName].isNewRow(idsel[0])) {
         messageBoxError(javaMessages.saveFirst);
         return;
      }

      basicSelectSBS(title, relController, relUpdSBSModel, idsel[0]);

   }

   function openRelationDivision() {

      relController = '<?php echo ($this->encodeModel('division_brand_model/retGridJsonDivision')); ?>';
      relUpdSBSModel = '<?php echo ($this->encodeModel('division_brand_model/updSBSRelDivision')) ?>';
      title = w2ui['varprd_division_brand_related'].header;


      var idsel = w2ui[gridName].getSelection();

      if (idsel.length == 0) {
         return;
      }
      if (w2ui[gridName].isNewRow(idsel[0])) {
         messageBoxError(javaMessages.saveFirst);
         return;
      }


      basicSelectSBS(title, relController, relUpdSBSModel, idsel[0]);

   }

   function posSBSClosed(hasChanges) {
      doSideGridsAction();
   }

</script>

<div class="row">
   <?php echo ($tab); ?>
</div>



