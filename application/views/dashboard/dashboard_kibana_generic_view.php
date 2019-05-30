<?php //include_once APPPATH . 'views/viewIncludeFilter.php';                                      ?>
<style> 
    .dashBackground {
        background-color: rgb(33, 31, 36) !important;
        color: white;
    }
    .w2ui-grid tr {
        background-color: black !important;
        color: white !important;
    }
    .w2ui-grid .w2ui-grid-toolbar {
        background-color: black !important;
        color: white !important;
    }

    .w2ui-col-header  {
        background-color: black !important;
        color: white;
    }

    .w2ui-col-group {
        background-color: black !important;
        color: white;
    }

    .w2ui-head-last > div{
        background-color: black !important;
        color: white;
    }
    .w2ui-grid-footer {
        background-color: black !important;
        color: white;

    }

    .graphArea {
        padding-left: 2px;
        padding-right: 2px;
    }
</style>

<script>
// aqui tem os scripts basicos. 
    var gridName = "gridGeneric";

<?php echo ($javascript); ?>

//var controllerName = "country";



//$(".ds_hr_type").on( "change",  function() {



    var dsMainObject = new function () {


        // variaveis privadas;

        var thisObj = this;
        thisObj.gridName = gridName;
        thisObj.nrControllers = 0;

        // funcao de inicio;
        this.start = function (gridNamePar) {
            $('.content-wrapper').addClass('dashBackground');
            $('.content-header').addClass('hidden');
            
            
            $('.navbar-custom-menu').after('<div id="dashTitleKibana" style="font-weight:bold; width:  100%;text-align: center;font-size: 20px;height: 50px;padding-top: 10px; color: white"> <?php echo($title);?></div>');
             
            

            if ( !$('body').hasClass('sidebar-collapse')) {
                $('.sidebar-toggle').click();
            }

            this.addListeners();
            this.addHelper();

        }




        this.addHelper = function () {
        }

        // funcao de toolbar;
        this.ToolBarClick = function (bPressed, dData) {

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
            //w2ui[thisObj.gridName].destroy();
//            introRemove();
            $('.content-wrapper').removeClass('dashBackground');
            $('.content-header').removeClass('hidden');
            $('#dashTitleKibana').remove();
            

            return true;
        }
    }



// funcoes iniciais;
    dsMainObject.start(gridName);

// funcao da toolbar
    function onGridToolbarPressed(bPressed, dData) {
        dsMainObject.ToolBarClick(bPressed, dData);
    }

// insiro colunas;


    function setGrpGridHeight() {
        var hAvail = getWorkArea();
        $('#idkibana').height(hAvail  - 50);

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
    setGrpGridHeight();
</script>

<div class="row">
    <iframe id = 'idkibana' frameBorder="0" width='100%' src="<?php echo($iframe)?>"></iframe>
</div>



