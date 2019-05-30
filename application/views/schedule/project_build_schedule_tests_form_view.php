<style>
    .schtstdiv {
        margin-bottom: 10px;
    }
    hr.scttst {
        display: block;
        height: 2px;
        border: 0;
        border-top: 2px solid #ccc;
        margin: 1em 0;
        padding: 0; 
    }    

</style>

<script>
// aqui tem os scripts basicos. 
//var controllerName = "country";



//$(".ds_hr_type").on( "change",  function() {


    var dsFormSchObject = new function () {

        // variaveis privadas;

        var thisObj = this;
        thisObj.gridName = undefined;
        thisObj.duplicatingCode = -1;
        thisObj.duplicateColumns = ['ds_test_type',
            'ds_test_item',
            'ds_tests',
            'ds_specification',
            'ds_sample_description',
            'ds_extra_instruction',
            'fl_witness',
            'nr_sample_quantity',
            'nr_charger_quantity',
            'nr_power_pack_quantity',
            'nr_accessory_qty',
            'nr_goal',
            'nr_output',
            'fl_eol'
        ];

        thisObj.action = '<?php echo($action); ?>'
        thisObj.scheduleCode = <?php echo($cd_project_build_schedule); ?>;
        thisObj.projectCode = <?php echo($project); ?>;
        this.start = function () {

<?php echo($toolbar) ?>

            var vtoolbar = vGridToToolbar.toolbar;

            vtoolbar.onClick = function (a, b) {
                if (a == 'update') {
                    thisObj.updateData();
                }

                if (a == 'insert') {
                    thisObj.insertData();
                }
                if (a.substr(0, 9) == 'copyFrom:') {
                    var vd = a.split('_');
                    $.myCgbAjax({url: 'schedule/project_build_schedule_tests/copyFromTests/' + thisObj.scheduleCode + '/' + vd[1],
                        success: function (data) {
                            $('#formSchTst').append(data.html);
                            $('#testScrollArea').cgbMakeScrollbar('scrollToY', $('#testArea_' + data.pk).position().top);
                            thisObj.Form.addNewElements();
                        }});
                }

            };
            
            vtoolbar.name = 'SCHTSTFormToolbar';

            if (w2ui['SCHTSTFormToolbar'] != undefined) {
                w2ui['SCHTSTFormToolbar'].destroy();
            }

            $('#schTstToolbar').w2toolbar(vtoolbar);

            thisObj.Form = $('#formSchTst').cgbForm({updController: 'schedule/project_build_schedule_tests', updFunction: 'updateDataJsonTest/<?php echo($cd_project_build_schedule); ?>/<?php echo($project); ?>'});
            $('#testScrollArea').cgbMakeScrollbar({maxHeight: 'calc(100vh - 200px)', theme: 'dark'});

            thisObj.makeScreenAdjustments();

            this.setScreenPermissions();

            this.addListeners();
            this.addHelper();
        }

        this.addHelper = function () {
            var arrayHelper = [];
            //$.merge(arrayHelper, introAddFilterArea());
            //$.merge(arrayHelper,w2ui[thisObj.gridName].toolbar.getIntroHelp());
            //$.merge(arrayHelper, w2ui[thisObj.gridName].getIntroHelp());

            //introAddNew({steps: arrayHelper});
        }


        // adicao de listeners!
        this.addListeners = function () {
            $('#formSchTst').on('pospicklist', function (ev) {
                if (ev.fielddata.codefield == 'cd_tests') {
                    var vUnit = 'ds_test_unit_' + ev.fielddata.order + '_form';
                    if (ev.newCode == -1) {
                        thisObj.Form.setItem(vUnit, '');
                    } else {
                        thisObj.Form.setItem(vUnit, ev.record.ds_test_unit);
                    }
                }

            })


            $('#formSchTst').on('itemChanged', function (ev) {

            });

            $('#formSchTst').on('afterUpdate', function (ev) {
                thisObj.action = 'E';
                $('#formSchTst').empty();
                $('#formSchTst').append(ev.fullData.html);
                thisObj.Form.addNewElements();
                $(window).trigger('schChanged', ev.fullData);



            });

            // seto o evendo de fechar!!!
            $(window).on("onCloseForm.schtst", function (a) {
                if (thisObj.Form.isChanged()) {
                    messageBoxOkCancel(javaMessages.info_changed_close, function () {
                        thisObj.Form.destroy();
                        SBSModalFormsVar.close();
                        $(window).off('onCloseForm.schtst');
                    })
                } else {
                    thisObj.Form.destroy();
                    SBSModalFormsVar.close();
                    $(window).off('onCloseForm.schtst');
                }
            });

        }



        // roda antes de fechar (se retornar FALSE o sistema vai perguntar se quer fechar
        this.beforeClose = function () {
            return true;
        }


        // close object (lugar para destruir as coisas//
        this.close = function () {
            //introRemove();
            return true;
        }

        this.updateData = function () {
            thisObj.Form.updateForm();
        }

        this.insertData = function () {

            $.myCgbAjax({url: 'schedule/project_build_schedule_tests/addNewTestItem/' + thisObj.scheduleCode,
                success: function (data) {
                    $('#formSchTst').append(data.html);
                    $('#testScrollArea').cgbMakeScrollbar('scrollToY', $('#testArea_' + data.pk).position().top);
                    thisObj.Form.addNewElements();
                    if (thisObj.duplicatingCode != -1) {
                        thisObj.makeDuplicate(data.pk);
                    }
                }});


        }

        this.setScreenPermissions = function () {
// insert
            if (thisObj.action == 'I') {

            } else {

            }

        }

        this.deleteTest = function (id) {
            $('#testArea_' + id).css('background-color', 'rgb(255,0,0)');

            messageBoxYesNo(javaMessages.conf_delete, function () {
                $('#testArea_' + id).hide('slide', 400);
                thisObj.Form.setItemPL('ds_test_type_' + id + '_form', -2, '');
            }, function () {
                $('#testArea_' + id).css('background-color', '');
            })
        }

        this.duplicateTest = function (fromId) {
            thisObj.duplicatingCode = fromId;
            thisObj.insertData();
        }

        this.makeDuplicate = function (toId) {
            var vsch = thisObj.Form.getPk();
            //thisObj.duplicatingCode;
            $.each(thisObj.duplicateColumns, function (i, v) {
                var colfrom = thisObj.Form.getFieldId(v, thisObj.duplicatingCode, thisObj.scheduleCode);
                var colto = thisObj.Form.getFieldId(v, toId, thisObj.scheduleCode);
                var vinfo = thisObj.Form.getFieldInfo(colto);

                var vdata = '';
                var vcode = -1;

                vdata = thisObj.Form.getItem(colfrom);
                if (vinfo.type == "PLD") {
                    vcode = thisObj.Form.getItemPLCode(colfrom);
                }

                // no value, no reason to set
                if (chkUndefined(vdata, '') == '') {
                    return true;
                }

                if (vinfo.type == "PLD") {
                    thisObj.Form.setItemPL(colto, vcode, vdata);
                } else {
                    thisObj.Form.setItem(colto, vdata);
                }
            });

            thisObj.duplicatingCode = -1;
        }
        
        

        this.resizeGrid = function () {

        }

        this.setGridAsChanged = function () {

        }

        this.makeScreenAdjustments = function () {
            $('#divSchForm').find('[data-toggle="tooltip"]').tooltip({container: 'body'});

        }

        // funcaoes gerais 

    }

// funcoes iniciais;
    dsFormSchObject.start();


// insiro colunas;

</script>

<div id="divSchForm"  > 
    <div class="row">
        <div id="schTstToolbar" style="width: 100%;" class="toolbarStyle" ></div>
    </div>
    <div style="overflow-y: auto; overflow-x: hidden" id="testScrollArea">

        <form id="formSchTst" class="form-horizontal">
            <?php echo ($html) ?>
        </form>
    </div>
</div>



