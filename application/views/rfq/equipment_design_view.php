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
            this.addHelper();

            setTimeout(function () {
                w2ui[thisObj.gridName].retrieve();
            }, 0);

            var allow_equipment_maintain='<?php echo($allow_equipment_maintain)?>';

            if (allow_equipment_maintain == 'N') {
                w2ui[thisObj.gridName].readOnly();
            }

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
                w2ui[thisObj.gridName].insertRow();
            }
            if (bPressed == 'insertWithContent') {
                var records = w2ui[thisObj.gridName].getSelectedRow();
                w2ui[thisObj.gridName].insertRow({
                    funcAfter: function (data) {
                        $.each(records, function (index, val) {
                            if ($.inArray(index, ['recid', 'cd_equipment_design', 'cd_equipment_design_image', 'nr_series', 'ds_code', 'nr_attachment_count', 'changes']) !== -1 ||
                                val === null || val === '') return true;
                            w2ui[thisObj.gridName].setItem(data.recid, index, val);
                        });
                    }
                });
            }
            if (bPressed == 'retrieve') {
                w2ui[thisObj.gridName].retrieve();
            }
            if (bPressed == "update") {
                w2ui[thisObj.gridName].update();
            }

            if (bPressed == "delete") {
                w2ui[thisObj.gridName].deleteRow();
            }
            if (bPressed == 'filter') {
                hideFilter();
            }

            if (bPressed == 'importWH') {
                thisObj.importWH();
            }
            
            if (bPressed == 'docrep') {
                
                var vcode = w2ui[thisObj.gridName].getPk();
                // check if something is selected!
                if (vcode == -1) {
                    return;
                }
                
                // check if the row is new. If it is new, cannot insert child table.
                if ( w2ui[thisObj.gridName].isNewRow(vcode)) {
                    messageBoxError(javaMessages.saveFirst);
                    return;
                }
                
                openRepository({id: 4, code: vcode });
            }

        }


        this.importWH = function() {
            $.myCgbAjax({url: 'rfq/equipment_design/importfromWH',
                success: function (data) {
                    if (data.status != 'OK') {
                        messageBoxAlert(data.status);
                        return;
                    }
                    
                },
                errorAfter: function () {
                    
                }});
        }

        // adicao de listeners!
        this.addListeners = function () {
            w2ui[thisObj.gridName].on('dblClick', function (event) {
                var col = event.column;
                var colname = w2ui[thisObj.gridName].columns[col].field;
                var vid = chkUndefined(w2ui[thisObj.gridName].getItem(event.recid, 'cd_equipment_design_sub_category'), -1);

                if (colname == 'ds_equipment_design_type' || colname == 'ds_equipment_design_category' || colname == 'ds_equipment_design_sub_category') {
                    event.preventDefault();

                    if (!w2ui[thisObj.gridName].isNewRow(event.recid)) {
                        event.preventDefault();
                        return;
                    }


                    basicPickListOpen({controller: 'rfq/equipment_design_sub_category/openPL',
                        title: '<?php echo($equipment); ?>',
                        sel_id: vid,
                        showTitle: true,
                        plCallBack: function (id, desc, rec) {
                            w2ui[thisObj.gridName].setItem(event.recid, 'cd_equipment_design_sub_category', rec.cd_equipment_design_sub_category);
                            w2ui[thisObj.gridName].setItem(event.recid, 'ds_equipment_design_sub_category', rec.ds_equipment_design_sub_category);
                            w2ui[thisObj.gridName].setItem(event.recid, 'cd_equipment_design_category', rec.cd_equipment_design_category);
                            w2ui[thisObj.gridName].setItem(event.recid, 'ds_equipment_design_category', rec.ds_equipment_design_category);
                            w2ui[thisObj.gridName].setItem(event.recid, 'cd_equipment_design_type', rec.cd_equipment_design_type);
                            w2ui[thisObj.gridName].setItem(event.recid, 'ds_equipment_design_type', rec.ds_equipment_design_type);
                            w2ui[thisObj.gridName].setItem(event.recid, 'fl_auto_add_serial', rec.fl_auto_add_serial);
                        }
                    });


                }

                //basicPickListOpen (, 'getOptionsPL', id, 400, 400, 'settings');

            });
        }

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



        this.setTypeRender = function (record, index, column_index) {


            var bcanChange = this.isNewRow(record.recid);
            var vfield = this.columns[column_index].field;
            var vx = this.columns[column_index].field;
            var vdata = chkUndefined(record[vx], '&nbsp');

            if (vdata == '') {
                vdata = '&nbsp';
            }

            if (!bcanChange) {
                vdata = '<div class="w2ui-data-disabled" style="background-color: transparent">' + vdata + '</div>';
            } else {
                vdata = gridMakePLRender.call(this, record, index, column_index);
            }


            return vdata;
        }

        this.setSeriesRender = function (record, index, column_index) {


            var bcanChange = !record.fl_auto_add_serial;
            var vx = this.columns[column_index].field;
            var vdata =  this.getCellFormated(index, column_index);

            if (vdata == '') {
                vdata = '&nbsp';
            }

            if (!bcanChange) {
                vdata = '<div class="w2ui-data-disabled">' + vdata + '</div>';
            } 


            return vdata;
        }





        // funcaoes gerais 

    }

// funcoes iniciais;
    dsMainObject.start(gridName);

// funcao da toolbar
    function onGridToolbarPressed(bPressed, dData) {
        dsMainObject.ToolBarClick(bPressed, dData);
    }

    makeFilterWithEnter(function () {
        dsMainObject.ToolBarClick('retrieve');
    });

// insiro colunas;

</script>

<?php include_once APPPATH . 'views/includeViewResizeDiv.php'; ?>
