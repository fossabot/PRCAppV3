<div class="showfilter row" id='showfilter'>
    <?php echo $filters ?>  
    <script><?php echo $filters_java ?></script>
</div>

<script>
// aqui tem os scripts basicos. 
    var gridName = "varprd_project_product";
    var controllerName = "project_product";
    var filters = [];
    var sideGrids;
    var sideControllers;

// parte onde o controller vai jogar o javascrip do grid e etc...
<?php echo ($javascript); ?>

    $().w2grid(varprd_project_product);
    $().w2grid(varprd_project_power_type);
    $().w2grid(varprd_project_tool_type);

    $().w2grid(varprd_project_power_type_related);
    $().w2grid(varprd_project_tool_type_related);

    w2ui["varprd_project_product"].on("rowFocusChanging", function (target, event) {
        event.onComplete = function () {
            doSideGridsAction();
        }
    });


    w2ui["varprd_project_power_type"].on("rowFocusChanging", function (target, event) {
        event.onComplete = function () {
            doSideGridsAction();
        }
    });


    $('#myGrid').w2layout({
        name: 'myFullLayout',
        panels: [
            {type: 'main', size: 150, minSize: 400, resizable: true, content: w2ui['varprd_project_product']},
            {type: 'right', size: 150, minSize: 300, resizable: true, content: w2ui['varprd_project_power_type_related']},
        ]
    });


    

    function onTabChanged(selectedTab) {
        
        
        
        $('#filter_desc_pp_frame').hide();
        $('#filter_desc_tt_frame').hide();
        $('#filter_desc_pt_frame').hide();

        
        switch (selectedTab) {
            case 'tab_project_product' :
                w2ui['myFullLayout'].content('main', w2ui['varprd_project_product']);
                w2ui['myFullLayout'].show('right', true);
                w2ui['myFullLayout'].content('right', w2ui['varprd_project_power_type_related']);

                sideGrids = 'varprd_project_power_type_related';
                sideControllers = "tti/project_power_type/retrieveGridJsonProduct";

                gridName = "varprd_project_product";
                controllerName = "tti/project_product";

                $('#ds_project_product_frame').show();
                $('#ds_project_product_brand_frame').hide();
                $('#cd_project_product_x_project_power_type_frame').hide();
                filters = ['filter_desc_pp', 'dt_deactivated'];
                
                $('#filter_desc_pp_frame').show();

                
                break;
                
            case 'tab_project_power_type' :
                w2ui['myFullLayout'].content('main', w2ui['varprd_project_power_type']);
                w2ui['myFullLayout'].show('right', true);
                w2ui['myFullLayout'].content('right', w2ui['varprd_project_tool_type_related']);

                sideGrids = 'varprd_project_tool_type_related';
                sideControllers = "tti/project_tool_type/retrieveGridJsonPowerType";

                gridName = "varprd_project_power_type";
                controllerName = "tti/project_power_type";
                $('#ds_project_product_frame').hide();
                $('#ds_project_product_brand_frame').show();
                $('#cd_project_product_x_project_product_brand_frame').show();
                filters = ['filter_desc_pt', 'dt_deactivated'];
                $('#filter_desc_pt_frame').show();

                break;
                
                
            case 'tab_project_tool_type' :
                w2ui['myFullLayout'].hide('right', true);
                w2ui['myFullLayout'].content('main', w2ui['varprd_project_tool_type']);

                sideGrids = 'varprd_project_tool_type_related';
                sideControllers = "tti/project_tool_type/retrieveGridJsonPowerType";

                gridName = "varprd_project_tool_type";
                controllerName = "tti/project_tool_type";
                $('#filter_desc_tt_frame').show();

                filters = ['filter_desc_tt', 'dt_deactivated'];

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
            if (getSelectedTab() == 'tab_project_product') {
                openRelationProjectProduct();
            } else {
                openRelationProjectPowerType();
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

    onTabChanged('tab_project_product');

// ajusta o tamanho do detail.
    setGrpGridHeight();

// retrieve automatico na entrada!
    doGrpGridRetrieve();


    function doGrpGridRetrieve() {
        w2ui[gridName].retrieve({filterNames: filters});
    }


    function doSideGridsAction() {
        console.log('dentro', sideGrids);
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

    function openRelationProjectPowerType() {

        relController = '<?php echo ($this->encodeModel('tti/project_tool_type_model/retGridJsonPowerType')); ?>';
        relUpdSBSModel = '<?php echo ($this->encodeModel('tti/project_tool_type_model/updSBSRelPowerType')) ?>';
        title = w2ui['varprd_project_power_type_related'].header;


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

    function openRelationProjectProduct() {

        relController = '<?php echo ($this->encodeModel('tti/project_power_type_model/retGridJsonProduct')); ?>';
        relUpdSBSModel = '<?php echo ($this->encodeModel('tti/project_power_type_model/updSBSRelProduct')) ?>';
        title = w2ui['varprd_project_power_type_related'].header;


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




