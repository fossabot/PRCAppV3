

<script>
// aqui tem os scripts basicos. 
    var gridName = "gridGeneric";
//var controllerName = "country";



//$(".ds_hr_type").on( "change",  function() {


    var dsHistoryObject = new function () {

        // variaveis privadas;

        var thisObj = this;
        thisObj.gridName = undefined;

        // funcao de inicio;
        this.start = function (gridNamePar) {
            thisObj.gridName = gridNamePar;

            <?php echo ($javascript); ?>

            w2ui['gridHistory'].resize();

            this.addListeners();
            this.addHelper();

        }

        this.addHelper = function () {
            var arrayHelper = [];
        }

        // adicao de listeners!
        this.addListeners = function () {

        }

        // roda antes de fechar (se retornar FALSE o sistema vai perguntar se quer fechar
        this.beforeClose = function () {
            return true;
        }


        // close object (lugar para destruir as coisas//
        this.close = function () {
            w2ui[thisObj.gridName].destroy();
            introRemove();
            return true;
        }




        // funcaoes gerais 

    }

</script>

<div class = "col-md-12 no-padding" style="display: block;" id="stepsHistory"> 
    <div style="width: 100%; height: 400px" id="gridHistoryDiv"></div>
</div>