
<?php
// PHP page that has the filter area.
include_once APPPATH . 'views/viewIncludeFilter.php';
?>

<style>
    /*Rest*/
    .legData {width: 60px;display: inline-block;text-align: center;height: 20px;padding-top: 3px;vertical-align: center;border: 1px solid gray; font-size: 10px};

    #myGrid .w2ui-head .w2ui-col-header {
        background-color: white;
    }



    /*LMS*/
    #myGrid .w2ui-head[col="2"] .w2ui-col-header,
    #myGrid .w2ui-head[col="3"] .w2ui-col-header,
    #myGrid .w2ui-head[col="4"] .w2ui-col-header,
    #myGrid .w2ui-head[col="7"] .w2ui-col-header,
    #myGrid .w2ui-head[col="9"] .w2ui-col-header,
    #myGrid .w2ui-head[col="10"] .w2ui-col-header,    
    #myGrid .w2ui-head[col="17"] .w2ui-col-header,    
    #myGrid .w2ui-head[col="33"] .w2ui-col-header,    
    #myGrid .w2ui-head[col="34"] .w2ui-col-header,    
    #myGrid .w2ui-head[col="35"] .w2ui-col-header,    
    #myGrid .w2ui-head[col="36"] .w2ui-col-header,    
    #myGrid .w2ui-head[col="37"] .w2ui-col-header,    
    #myGrid .w2ui-head[col="38"] .w2ui-col-header,    
    #myGrid .w2ui-head[col="39"] .w2ui-col-header,    
    #myGrid .w2ui-head[col="40"] .w2ui-col-header,    
    #myGrid .w2ui-head[col="41"] .w2ui-col-header,
    #myGrid .w2ui-head[col="42"] .w2ui-col-header
    {
        background-color: rgb(240,240,240);
    }

    /*TR*/
    #myGrid .w2ui-head[col="5"] .w2ui-col-header,
    #myGrid .w2ui-head[col="6"] .w2ui-col-header,
    #myGrid .w2ui-head[col="8"] .w2ui-col-header,
    #myGrid .w2ui-head[col="15"] .w2ui-col-header,
    #myGrid .w2ui-head[col="18"] .w2ui-col-header,
    #myGrid .w2ui-head[col="23"] .w2ui-col-header,    
    #myGrid .w2ui-head[col="24"] .w2ui-col-header,    
    #myGrid .w2ui-head[col="25"] .w2ui-col-header
    {
        background-color: #e7fdff;
    }

</style>


<script>
// aqui tem os scripts basicos. 
    var gridName = "gridWOShow";

    var dsMainObject = new function () {

        // Private Variables;
        var thisObj = this;
        thisObj.gridName = undefined;

        // The starting function
        this.start = function (gridNamePar) {
            thisObj.gridName = gridNamePar;

            if (w2ui[thisObj.gridName] !== undefined) {
                w2ui[thisObj.gridName].destroy();
            }

// javascript received from controller with the grid.
<?php echo ($javascript); ?>


            this.makeToolbarLegend();
            this.addListeners();
            this.addHelper();

            setTimeout(function () {
                w2ui[thisObj.gridName].retrieve({retrFunc: 'retrieveGridJsonpPower/1000', level: 2});
            }, 0);

        }

        // add helper on the question mark button. 
        this.addHelper = function () {
            var arrayHelper = [];
            $.merge(arrayHelper, introAddFilterArea());
            $.merge(arrayHelper, w2ui[thisObj.gridName].toolbar.getIntroHelp());
            $.merge(arrayHelper, w2ui[thisObj.gridName].getIntroHelp());

            introAddNew({steps: arrayHelper});
        }

        // Toolbar functions
        this.ToolBarClick = function (bPressed, dData) {

            if (bPressed == 'insert') {
                w2ui[thisObj.gridName].insertRow();
            }
            if (bPressed == 'retrieve') {
                var vd = select2GetData('filter_rows_to_show');
                if (vd == null) {
                    vd = -1;
                } else {
                    vd = vd.text;
                }

                w2ui[thisObj.gridName].retrieve({retrFunc: 'retrieveGridJsonpPower/' + vd, level: 2});
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

        }


        // Function to add listeners (events). Here is empty but it is part of the basic object structure
        this.addListeners = function () {
            w2ui[thisObj.gridName].on('refresha', function () {
                var vcol = w2ui[thisObj.gridName].columns;
                $('#myGrid td.w2ui-head').each(function () {
                    var vc = $(this).attr('col');

                    if (vc == undefined || vc >= vcol.length) {
                        return true;
                    }

                    if (vcol[vc].field == 'ds_project_status') {
                        console.log(vc, vcol, $(this), vcol[vc].field);
                        $(this).find('.w2ui-col-header').css('background-color', 'tomato');
                    }
                });
                ;

            })
        }

        // It runs before close the screen (by choosing another option on the menu, for example). If you return false the system will not leave the screen
        this.beforeClose = function () {
            return w2ui[thisObj.gridName].getChanges().length == 0;
        }


        // Event that will be triggered when the object is being closed. Location to remove listeners, destroy grids, etc....
        this.close = function () {
            w2ui[thisObj.gridName].destroy();
            introRemove();
            return true;
        }

        this.renderTRRemarks = function (record, index, column_index) {
            var vfield = this.columns[column_index].field;
            var vx = this.columns[column_index].field;
            var vdata = chkUndefined(record[vx], '&nbsp');
            var vgrd = this.name;

            if (vdata.indexOf('<tr') > 0) {
                var buttons = '<div style="text-align: left; width: 100%; "><button type="button" class="btn btn-info btn-xs" style="margin-right: 2px;height: 22px;"  onclick="dsMainObject.openTRRemarks(\'' + vgrd + '\', ' + record.recid + ')"><span class="fa fa-table" aria-hidden="true"></span></button></div>';
                return buttons;
            } else {

                vdata = vdata.replace(/(\r\n\t|\n|\r\t)/gm, " ");
                vdata = vdata.replaceAll("<br>", " ");
                vdata = vdata.replace("<BR>", " ");
                vdata = vdata.replace("<Br>", " ");
                vdata = vdata.replace("<br />", " ");


                return '<div style="white-space: nowrap;overflow: hidden;text-overflow: ellipsis;width: 100%;max-height: 20px;">' + vdata + '</div>';
            }

        }

        this.toolbarTRRemarks = function (record, index, column_index) {
            var vfield = this.columns[column_index].field;
            var vx = this.columns[column_index].field;
            var vdata = chkUndefined(record[vx], '&nbsp');
            var vgrd = this.name;
            var vstyle = 'btn-info';
            var vHasAttach = '<?php echo($withAttachment) ?>';
            var vHasNoAttach = '<?php echo($NoAttachment) ?>';

            var vShow = vHasAttach;
            if (record.nr_count_attachmnet == 0) {
                vstyle = 'btn-danger';
                vShow = vHasNoAttach;
            } else {
                vShow = vShow + ' ( ' + record.nr_count_attachmnet + ' )';
            }

            var buttons = '<div style="text-align: left; width: 100%; ">'

            buttons = buttons + '<button type="button" data-toggle="tooltipa" title="' + vShow + '" data-placement="right"  class="btn ' + vstyle + ' btn-xs" style="margin-right: 2px;height: 22px;"  onclick="dsMainObject.openTRAttachments(' + record.cd_tr_test_request_work_order_sample + ')"><span class="fa fa-edit" aria-hidden="true"></span></button>';

            if (record.ds_source == 'MTE') {
                var vwo = record.nr_work_order.toLocaleString("en", {useGrouping: false, minimumFractionDigits: 2});
                buttons = buttons + '<button type="button" data-toggle="tooltipa" title="More Data" data-placement="right"  class="btn btn-info btn-xs" style="margin-right: 2px;height: 22px;"  onclick="dsMainObject.openMTEData(\'' + vwo + '\',' + record.nr_sample + ')"><span class="fa fa-tag" aria-hidden="true"></span></button>';
            }


            if (record['ds_file_name'] != '') {
                buttons = buttons + '<button type="button" class="btn btn-info btn-xs" data-placement="right" style="margin-right: 2px;height: 22px;" data-toggle="tooltipa" title="<?php echo($testrep) ?>"  onclick="dsMainObject.openMTR(\'' + record['ds_file_name'] + '\')"><span class="fa fa-list-alt" aria-hidden="true"></span></button>';
            }


            buttons = buttons + '</div>';


            return buttons;
        }


        this.toolbarMTERemarks = function (record, index, column_index) {
            if (typeof record.ds_source == 'undefined' || record.ds_source != 'MTE')
                return;
            var vstyle = 'btn-info';
            var vShow = 'More data';
            var vwo = record.nr_work_order.toLocaleString("en", {useGrouping: false, minimumFractionDigits: 2});
            var buttons = '<div style="text-align: left; width: 100%; ">'
            buttons = buttons + '<button type="button" data-toggle="tooltipa" title="' + vShow + '" data-placement="right"  class="btn ' + vstyle + ' btn-xs" style="margin-right: 2px;height: 22px;"  onclick="dsMainObject.openMTEData(\'' + vwo + '\',' + record.nr_sample + ')"><span class="fa fa-tag" aria-hidden="true"></span></button>';

            buttons = buttons + '</div>';
            return buttons;
        };

        this.openMTR = function (vpath) {
            var toSend = [];
            toSend['path'] = vpath;
            openAlternate('POST', 'mtr/mtr_reports/openFile', toSend, '_blank');

        }


        this.toolbarPlans = function (record, index, column_index) {
            var vfield = this.columns[column_index].field;
            var vx = this.columns[column_index].field;
            var vdata = chkUndefined(record[vx], '&nbsp');
            var vgrd = this.name;
            var buttons = '<div style="text-align: left; width: 100%; "><button type="button" class="btn btn-info btn-xs" style="margin-right: 2px;height: 22px;"  onclick="dsMainObject.duplicateTest(' + record.cd_project_build_schedule_tests + ', ' + record.cd_project_build_schedule + ')"><span class="fa fa-files-o" aria-hidden="true"></span></button>';
            buttons = buttons + '<button type="button" class="btn btn-info  btn-xs" style="margin-right: 2px;height: 22px;"  onclick="dsMainObject.workOrderMaint(' + record.cd_project_build_schedule_tests + ', ' + record.cd_project_build_schedule + ')"><span class="fa fa-link" aria-hidden="true"></span></button>';
            buttons = buttons + '<button type="button" class="btn btn-danger  btn-xs" style="margin-right: 2px;height: 22px;"  onclick="dsMainObject.deleteTest(' + record.cd_project_build_schedule_tests + ', ' + record.cd_project_build_schedule + ')"><span class="fa fa-trash-o" aria-hidden="true"></span></button></div>';

            return buttons;
        }

        this.openTRAttachments = function (pk) {
            thisObj.attachmentType = 'X';
            openRepository({id: 10, code: pk});
        }

        this.openMTEData = function (wo, sample) {

            // var visnew = w2ui['gridData'].isNewRow(wo);
            //
            // if (visnew) {
            //     messageBoxError(javaMessages.saveFirst);
            //     return;
            // }

            var title = 'TEST DATA';
            openFormUiBootstrap(
                    title,
                    'mte/mte_main/openMTEData/' + wo + '/' + sample,
                    'col-md-12'
                    );

        };


        this.openTRRemarks = function (grdname, recid) {
            var vdata = '<span style="display: inline-block; background-color: white">' + w2ui[grdname].getItem(recid, 'ds_remarks') + '</span>';

            $.dialog({
                title: false,
                content: vdata,
                columnClass: 'col-md-12 messageBoxCGBClass',
                theme: 'supervan',
                backgroundDismiss: true,
                onOpenBefore: function () {
                    this.$el.css('z-index', '1000000105');

                    //this.$el.find('#xxd').css('background-color: white');
                },

                buttons: false
            });
        }

        // Place to add general functions

        this.makeToolbarLegend = function () {

            var html = "<div style='background-color: rgb(240,240,240);' class='legData'> LMS </div><div style='background-color: #e7fdff' class='legData'> TR </div><div style='background-color: white;' class='legData'> Test Data </div>";
            w2ui[thisObj.gridName].toolbar.insert('spacer7', [{type: 'html', id: 'leg', html: html}]);


        }

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

</script>



<?php
// PHP page that contains the Grid area, and the resize controls for grid.
include_once APPPATH . 'views/includeViewResizeDiv.php';
?>