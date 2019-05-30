<?php include_once APPPATH . 'views/viewIncludeFilter.php'; ?>

<script>
// aqui tem os scripts basicos. 
    var gridName = "parameterGrid";
    var selectedGrid = gridName;
    if (w2ui[gridName] != undefined) {
        w2ui[gridName].destroy();
    }


//$(".ds_hr_type").on( "change",  function() {
<?php echo ($javascript); ?>


// funcao da toolbar
    function onGridToolbarPressed(bPressed, dData) {
        if (bPressed == 'insert') {
            w2ui[selectedGrid].insertRow();
        }
        if (bPressed == 'retrieve') {
            console.log('x', selectedGrid);
            
            w2ui[selectedGrid].retrieve();
        }

        if (bPressed == "update") {
            w2ui[selectedGrid].update();
        }

        if (bPressed == "delete") {
            w2ui[selectedGrid].deleteRow();
        }
        if (bPressed == 'filter') {
            hideFilter();
        }



    }


    function sendInformation(area, table, title) {

        if (table == undefined) {
            return true;
        }

        var vareatitle;
        
        if (area == 'G') {
            vareatitle = 'Global';
        } else {
            vareatitle = 'by Specification';
        }
        
        if (area == 'S' && table == 'SHOE_SPECIFICATION' ) {
            messageBoxError('Nothing to Update');
            return;
        }
        
        var parms = {data: {table: table, area: area }};

        var vmsg = 'Confirm Reset the Sequence <strong>' + vareatitle + '</strong> for <strong>' + table + '</strong> Table ?';
        messageBoxYesNo(vmsg, function () {

            $.myCgbAjax({url: 'control_panel/resetSequence',
                data: parms,

                success: function (data) {
                    
                    if (data.status != 'OK') {
                        messageBoxAlert(data.status);
                    } else {
                        messageBoxAlert('Done');
                        
                    }
                    
                }});
        })


        //console.log(area, table, title);
    }


    function setGrpGridHeight() {
        var hAvail = getWorkArea();
        $("#myGrid").css("height", hAvail - 40);
        //$(".cgbTabsTab").height(hAvail - 40);
        //$(".cgbTabsDiv").height(hAvail - 40);

        $("#tab_parameters_div").height(hAvail - 40);
        w2ui['parameterGrid'].resize();
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
    $('.cgbTabsMain').ctabStart();
// insiro colunas;



</script>

<div class="row" id="mySizeScreen">
    <?php echo ($tab); ?>
</div>

