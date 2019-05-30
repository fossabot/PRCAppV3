<script>
// aqui tem os scripts basicos. 

    $('#myGridPL').css('height', parseInt($(window).height() * 0.45))

    if (w2ui["modalPickList"] != undefined) {
        w2ui["modalPickList"].destroy();
    }

    var selectedid = '<?php echo $selid ?>';
    var plMainModel = '<?php echo $model ?>';

    var plMainController = '<?php
if (isset($controller)) {
    echo $controller;
} else {
    echo "";
}
if (!isset($openmaint)) {
    $openmaint = "";
}
?>';


    var demandFilter = '<?php echo ($demandFilter); ?>';
    var multiSel = '<?php echo($multiselect) ?>';

    var vcolumns = [];
    vcolumns.push({"caption": "<?php echo ($code); ?>", "size": "60px", "field": "recid", "sortable": true, "style": "text-align: center;", hidden: true});
    if (multiSel == 'Y') {
        vcolumns.push({field: 'fl_checked', caption: 'X', size: '30px', sortable: true, resizable: true, style: 'text-align: center', editable: {type: 'checkbox', style: 'text-align: center'}});
    }
    vcolumns.push({"caption": "<?php echo ($description); ?>", "size": "100%", "field": "description", "sortable": true, "style": " //text-transform:uppercase ; "});

    gridVarAgain = {
        name: "modalPickList",
        header: 'Picklist',
        multiSelect: false,

        show: {
            header: false,
            footer: true,
            toolbar: true,
            toolbarSearch: true,
            toolbarReload: false,
            //selectColumn: (multiSel == 'Y'),

        },
        toolbar: {
            items: [
                {type: 'break', id: 'brk03'},

                {id: "retrieve", hint: javaMessages.retrieveInfo, icon: "fa fa-refresh", caption: "", type: "button"},
                {type: 'break', id: 'brk01'},

                {type: 'button', id: 'plSelect', caption: '<?php echo ($select); ?>', icon: "fa fa-arrow-circle-o-down"},
                {type: 'button', id: 'plReset', caption: '<?php echo ($clear); ?>', icon: "fa fa-times-circle-o"},
                {type: 'break', id: 'brk02'},

                {id: "openMaint", icon: "fa fa-external-link", hint: "<?php echo($openmaint); ?>", type: "button"}
            ],
            onClick: function (target, data) {
                if (target == 'plReset') {
                    plresetData();
                }

                if (target == 'plSelect') {
                    plButtonSelectData();
                }
                if (target == 'retrieve') {
                    retrievePL();
                }

                if (target == 'openMaint') {
                    window.open('main/redirect/' + plMainController, '_newtab');
                    //openMaintenanceScreen('main/redirect/' + plMainController);
                }


            }},
        onClick: function (event) {
            if (multiSel == 'Y') {
                return;
                var vactual = chkUndefined(this.getItem(event.recid, 'fl_checked'), 0);
                if (vactual == 0) {
                    vactual = 1;
                } else {
                    vactual = 0;
                }

                this.setItem(event.recid, 'fl_checked', vactual);
            }
        },

        onDblClick: function (event) {
            if (multiSel == 'N') {
                plselectData(event.recid);
            } else {
                plButtonSelectData();
            }
        },
        columns: vcolumns,
        records: <?php echo ($records); ?>
    };

    $('#myGridPL').w2grid(gridVarAgain);

    // parte que posiciona na opcao selecionada, e se for mais que 11 corre.
    if (selectedid != -1) {
        w2ui['modalPickList'].select(selectedid);
        bplRecIdx = w2ui['modalPickList'].get(selectedid, true);
        if (bplRecIdx > 11) {
            w2ui['modalPickList'].scrollIntoView(bplRecIdx);
        }
    }

    if (plMainController == '') {
        w2ui['modalPickList'].toolbar.hide('openMaint');
    }

// retorna os dadosselecionados.!
    function plselectData(recid) {
        var record = w2ui["modalPickList"].get(recid);

        // com o custom callback dah pra chamar qq funcao de selecao. Isso para evitar problemas
        // em telas que tem mais niveis, e tem picklist em todos!! Funcina que eh um charme!!!
        //if (typeof picklistCallBack === "function") {
        picklistCallBack(record.recid, record.description, record);
        //} else {
        //   onPLoptionSelected(record.recid, record.description, record);   
        //}


        SBSModalVar.close()
    }



    function plButtonSelectData() {
        var recids = w2ui["modalPickList"].getSelection();
        if (multiSel == 'N') {
            if (recids.length == 0) {
                return;
            }

            plselectData(recids[0]);
            
        } else {
            var vdata = w2ui["modalPickList"].getChanges();
            
            var record = [], desc = [], code = [];
            
            $.each(vdata, function (index, value) {
                if (value.fl_checked) {
                    var vr = w2ui["modalPickList"].get(value.recid);
                    record.push(vr);
                    code.push(vr.recid);
                    desc.push(vr.description);
                }
            });

            if (record.length == 0 ) {
                return;
            }
            
            picklistCallBack(code, desc, record);
            SBSModalVar.close()


        }


    }



// funcao que reseta os dados. seta -1, que depois os controllers/models vao entender como nulo.
    function plresetData() {
        //onPLoptionSelected(-1,'', null);
        picklistCallBack(-1, '', null);
        SBSModalVar.close()
    }

    function retrievePL() {

        value = $('#plFilter_Description').val();
        if (value == '' && demandFilter == 'Y') {
            messageBoxError('<?php echo ($filterMsg); ?>');
        }

        w2ui['modalPickList'].lock();

        $.post('basicpicklist/plRetrieve',
                {model: '<?php echo($model); ?>',
                    relation: JSON.stringify({idwhere: <?php echo($relId); ?>, id: "<?php echo($relCode); ?>"}),
                    filter: retFilterInformed(1, 'plFilter_Description')
                },
                function (data) {
                    w2ui['modalPickList'].clear();
                    w2ui['modalPickList'].add(data);
                    w2ui['modalPickList'].unlock();

                },
                'json'
                );
    }
// funcoes que vem do controlador.
<?php echo ($javascript); ?>



    $("#plFilter_Description").on('keyup', function (e) {
        if (e.keyCode == 13) {
            retrievePL();
        }
    });



    setTimeout(function () {
        $('#plFilter_Description').focus();
    }, 100);


</script>
<div class="row">
    <div class="col-md-12 small-padding">
        <div class="modal-header-picklist_cgb"> <?php echo ($title) ?> <i class='modal-header-picklist-close_cgb fa fa-close' onclick='SBSModalVar.close();'> </i></div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 small-padding">
        <div class="showfilter" id='showfilter' style="width: 100%; padding-top: 10px;"> 
            <?php echo $filters ?>  
            <script><?php echo $filters_java ?></script>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 small-padding">
        <div id="myGridPL" style="height: 400px;width: 100%;"> </div>
    </div>
</div>
