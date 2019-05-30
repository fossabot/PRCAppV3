<?php include_once APPPATH . 'views/viewIncludeFilter.php'; ?>


<script>
// aqui tem os scripts basicos. 
//var controllerName = "country";



//$(".ds_hr_type").on( "change",  function() {
    var gridName = "gridTRBrowse";

    var dsMainObject = new function () {

        // variaveis privadas;

        var thisObj = this;
        thisObj.gridName = 'gridTRBrowse';


        // funcao de inicio;
        this.start = function () {


            if (w2ui[thisObj.gridName] !== undefined) {
                w2ui[thisObj.gridName].destroy();
            }

<?php echo ($javascript); ?>

            this.addListeners();
            this.addHelper();


        }

        this.addHelper = function () {
            var arrayHelper = [];
            $.merge(arrayHelper, introAddFilterArea());
            $.merge(arrayHelper, w2ui[thisObj.gridName].toolbar.getIntroHelp());
            $.merge(arrayHelper, w2ui[thisObj.gridName].getIntroHelp());

            introAddNew({steps: arrayHelper});
        }

        // funcao de toolbar;
        this.ToolBarClick = function (bPressed, dData) {

            if (bPressed == 'insert') {
                thisObj.openEdit(-1, dData);
            }
            if (bPressed == 'retrieve') {
                w2ui[thisObj.gridName].retrieve();
            }
            if (bPressed == "edit") {
                var sel = w2ui[thisObj.gridName].getSelection();
                if (sel.length == 0) {
                    messageBoxError('<?php echo($errorNotSel) ?>');
                    return;
                }
                thisObj.openEdit(sel[0], dData);

            }

            if (bPressed == "delete") {
                w2ui[thisObj.gridName].deleteRow();
            }
            if (bPressed == 'filter') {
                hideFilter();
            }
        }

        // adicao de listeners!
        this.addListeners = function () {
            w2ui[thisObj.gridName].on('dblClick', function (event) {
                console.log('on double click');
                var sel = w2ui[thisObj.gridName].getSelection();
                if (sel.length == 0) {
                    messageBoxError('<?php echo($errorNotSel) ?>');
                    return;
                }
                thisObj.openEdit(sel[0]);
                }
                
            );
        };
        

        // roda antes de fechar (se retornar FALSE o sistema vai perguntar se quer fechar
        this.beforeClose = function () {
            return w2ui[thisObj.gridName].getChanges().length == 0;
        }


        // close object (lugar para destruir as coisas//
        this.close = function () {
            w2ui[thisObj.gridName].destroy();
            introRemove();
            return true;
        }

        this.openEdit = function (id, dData) {

            openFormUiBootstrap(
                    '<?php echo ($testForm) ?>',
                    'tr/test_request/callTRForm/' + id,
                    'col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1'
                    );


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
