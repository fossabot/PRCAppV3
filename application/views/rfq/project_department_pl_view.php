<head>
    <style type="text/css">
        .w2ui-toolbar table.w2ui-button.checked {
            border: 1px solid #aaaaaa;
            background-color: #347ab7;
        }
    </style>
</head>

<script>

    // aqui tem os scripts basicos.
    var cdprojectmodel = '<?php echo($cd_project_model); ?>';
    var cdgeneralprojectexpense = '<?php echo($cd_general_project_expense); ?>';
    //var descColumn = '<?php //echo($descColumn); ?>//';
    var retrieveFields = <?php echo($retrieveFields); ?>;
    var plMainController = '<?php echo($controller) ?>';
    var vuseexpense;
    var vuseprojectmodel;



    if(cdgeneralprojectexpense==0) {
        vuseexpense = -1;
    }
    else
    {
        vuseexpense = 1;
    }


    if(cdprojectmodel==0){
        vuseprojectmodel=-1;
    }
    else
    {
        vuseprojectmodel=1;
    }

    <?php echo($javascript); ?>
    // funcao da toolbar
    function onGridToolbarPressedPL(bPressed, dData) {

        if (bPressed == 'useexpense') {
            // thisObj.bolShowTitle = -thisObj.bolShowTitle;
            // $("#myExpenseGridx").toggle();
            // w2ui['myExpenseGridx'].selectAll();

            w2ui['specificExpense'].selectNone();
            vuseexpense=-vuseexpense;
            // $("#myExpenseGridx").disabled = true;

            setGrpGridHeight();
        }

        if (bPressed == 'useprojectmodel') {
            // thisObj.bolShowTitle = -thisObj.bolShowTitle;
            // $("#myExpenseGridx").toggle();
            // w2ui['myPLGridx'].selectAll();
            w2ui['specificPLSup'].selectNone();
            vuseprojectmodel=-vuseprojectmodel;
            // $("#myExpenseGridx").disabled = true;
            setGrpGridHeight();
        }

        if (bPressed == 'clear') {
            plresetData();
        }

        if (bPressed == 'openMaint') {
            window.open('main/redirect/' + plMainController, '_newtab');
        }
    }

    $('#myPLGridx').w2grid(varMySpecificPL);
    $('#myExpenseGridx').w2grid(varMySpecificExpense);

    w2ui["specificPLSup"].ScrollToRow(<?php echo($id)?>);
    //$('#filtersPL').width('700px');


    w2ui.specificPLSup.on('dblClick', function (event) {
        plselectData(event.recid,'PLSup');
    });

    w2ui.specificExpense.on('dblClick', function (event) {
        plselectData(event.recid,'Expense');
    });

    w2ui.specificPLSup.on('select', function (event) {

        if(vuseprojectmodel==-1)
        {
            event.preventDefault();
        }
    });

    w2ui.specificExpense.on('select', function (event) {

        if(vuseexpense==-1)
        {
            event.preventDefault();
        }
    });

    $('#modalPicklistTitle').html("<?php echo($title); ?>");


    // retorna os dadosselecionados.!
    function plselectData(recid,grid) {
        if(grid=='PLSup') {
            var record = w2ui["specificPLSup"].get(recid);
            var recordExpense= w2ui["specificExpense"].get(w2ui['specificExpense'].getSelection()[0]);
        }
        else if(grid=='Expense')
        {
            var recordExpense= w2ui["specificExpense"].get(recid);
            var record =w2ui["specificPLSup"].get(w2ui['specificPLSup'].getSelection()[0]);
        }
        // var record =w2ui["specificPLSup"].get(w2ui['specificPLSup'].getSelection()[0]);
        // var recordExpense = w2ui["specificExpense"].get(w2ui['specificExpense'].getSelection()[0]);
        vuseexpense = w2ui["specificPLSup"].toolbar.get('useexpense').checked;
        vuseprojectmodel= w2ui["specificPLSup"].toolbar.get('useprojectmodel').checked;


        // com o custom callback dah pra chamar qq funcao de selecao. Isso para evitar problemas
        // em telas que tem mais niveis, e tem picklist em todos!! Funcina que eh um charme!!!
        //if (typeof picklistCallBack === "function") {
        picklistCallBack( record, recordExpense, vuseexpense,vuseprojectmodel);

        if(vuseexpense||vuseprojectmodel) {
            $(window).off('resize.depScreen');
            SBSModalVar.close();
        }

        //} else {
    }
    w2ui['specificPLSup'].selectNone();
    w2ui['specificExpense'].selectNone();
    w2ui["specificPLSup"].boxToLock = '#csplid';
    w2ui['specificPLSup'].select(cdprojectmodel);
    w2ui['specificExpense'].select(cdgeneralprojectexpense);
    console.log( w2ui['specificPLSup'].getSelection() );
    console.log( w2ui['specificExpense'].getSelection() );

    resizeWindowArea();

    $(window).on('resize.depScreen', function() {
        resizeWindowArea();
        // console.log(cdprojectmodel);
    })


    function resizeWindowArea () {

        if ( $('#csplid').length == 0 ) {
            $(window).off('resize.depScreen');
            return;
        }

        var vs = $('#csplid').height();

        $('#myPLGridx').height( Math.round(vs * 0.49) - 5 );
        $('#myExpenseGridx').height( Math.round(vs * 0.49) - 5 );
        w2ui['specificPLSup'].resize();
        w2ui['specificExpense'].resize();

    }

</script>
<div id='csplid' style="height: calc(100vh - 120px);">
    <!--    <div class="col-sm-1">-->
    <!--        <label><input type="checkbox" class="form-control input-sm"   value="-->
    <?php //hecho('') ?><!--" fieldname="fl_is_expense" id="fl_is_expense_form" mask="CHK" >Expense</label>-->
    <!--    </div>-->

    <div class="row" style="padding-top: 5px;padding-left: 10px;padding-right: 10px;">
        <div id="myPLGridx" style="height: 380px;background-color: #003399;width: 100%"></div>
    </div>
    <div class="row" style="padding-top: 5px;padding-left: 10px;padding-right: 10px;">
        <div id="myExpenseGridx" style="height: 280px;background-color: #003399;width: 100%"></div>
    </div>
</div>