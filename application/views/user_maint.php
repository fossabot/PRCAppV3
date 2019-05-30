<?php include_once APPPATH . 'views/viewIncludeFilter.php'; ?>

<script>
    // aqui tem os scripts basicos.
    var gridName = "gridHM";
    var controllerName = "users_maint";

    if (w2ui[gridName] != undefined) {
        w2ui[gridName].destroy();
    }


    //$(".ds_hr_type").on( "change",  function() {
<?php echo($javascript); ?>

    // funcao da toolbar
    function onGridToolbarPressed(bPressed, dData) {

        if (bPressed == 'insert') {
            openEdit(-1);
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

        if (bPressed == 'edit') {
            ret = w2ui[gridName].getSelection();
            openEdit(ret[0]);
        }
        if (bPressed == "menu_options") {
            ret = w2ui[gridName].getSelection();

            if (ret.length == 0) {
                return;
            }

            openMenuEdit(ret[0]);
        }
        if (bPressed == "importAD") {
            
            $.myCgbAjax({
                url: 'users_maint/importAD',
                box: '#myGrid',
                success: function (data) {
                    if (data.status == 'OK') {
                        messageBoxAlert("Imported Successfully");
                        w2ui[gridName].retrieve();
                    }

                }
            });
        }
    }

/*
    w2ui[gridName].on('dblClick', function (event) {
        openEdit(event.recid)
        //console.log(event);
    });
*/
    function openEdit(recidm) {
        codePK = recidm;
        var sit = 'users_maint/openForm/' + codePK;
        openFormUiBootstrap('<?php echo($usermaint); ?>', sit, 'col-md-8 col-md-offset-2');

    }

    function openMenuEdit(recidm) {
        codePK = recidm;
        var sit = 'menu/editPermission/H/' + codePK;
        openFormUiBootstrap('<?php echo($menumaint); ?>', sit, 'col-md-8 col-md-offset-2');
    }

    w2ui[gridName].retrieve();

    // insiro colunas;

</script>
<?php include_once APPPATH . 'views/includeViewResizeDiv.php'; ?>
