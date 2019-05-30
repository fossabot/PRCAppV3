<?php include_once APPPATH . 'views/viewIncludeFilter.php'; ?>

<script>
// aqui tem os scripts basicos. 
   var gridName = "gridGeneric";
//var controllerName = "country";



//$(".ds_hr_type").on( "change",  function() {


   var dsMainObject = new function () {

      // variaveis privadas;

      var thisObj = this;
      thisObj.gridName = undefined;
      // funcao de inicio;
      this.start = function (gridNamePar) {
         thisObj.gridName = gridNamePar;
         if (w2ui[thisObj.gridName] !== undefined) {
            w2ui[thisObj.gridName].destroy();
         }

<?php echo ($javascript); ?>


         this.addListeners();
      }

      // funcao de toolbar;
      this.ToolBarClick = function (bPressed, dData) {

         if (bPressed == 'retrieve') {
            w2ui[thisObj.gridName].retrieve();
         }
         if (bPressed == 'filter') {
            hideFilter();
         }

         if (bPressed == 'expire') {

            vPk = w2ui[thisObj.gridName].getPk();
            if (vPk === -1) {
               return;
            }

            messageBoxYesNo('Confirm set selected Session as Expired ?', function () {


               $.myCgbAjax({url: 'session_log/expireSession/' + vPk,
                  message: javaMessages.updating,
                  success: function (data) {

                     $.each(data.resultset, function (index, value) {
                        w2ui[thisObj.gridName].set(value.recid, value);
                        
                        
                     });


                  }

               });


            });
         }
      }


      // adicao de listeners!
      this.addListeners = function () {

      }

      // roda antes de fechar (se retornar FALSE o sistema vai perguntar se quer fechar
      this.beforeClose = function () {
         return w2ui[thisObj.gridName].getChanges().length == 0;
      }


      // close object (lugar para destruir as coisas//
      this.close = function () {
         w2ui[thisObj.gridName].destroy();
         return true;
      }


      // funcaoes gerais 

   }

// funcoes iniciais;
   dsMainObject.start(gridName);
// funcao da toolbar
   function onGridToolbarPressed(bPressed, dData) {
      dsMainObject.ToolBarClick(bPressed, dData);
   }



// insiro colunas;

</script>

<?php include_once APPPATH . 'views/includeViewResizeDiv.php'; ?>
