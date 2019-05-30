<div class="col-sm-12 col-md-3 dragTile" settingsId ="<?php echo($cd_hm_system_dashboard_widget_param) ?>" id='dashboard_<?php echo($cd_hm_system_dashboard_widget_param) ?>' style="height: 550px">
    <div class="row">
        <div class="col-md-12" style='padding-left: 5px; padding-right: 5px;'>
            <div class='box  box-info box-solid widgetShadow' id = 'dashBox<?php echo($cd_hm_system_dashboard_widget_param) ?>'>
                <div class='box-header with-border '>
                    <h4 class='box-title'><?php echo($ds_system_dashboard_widget) ?></h4>
                    <div class='box-tools pull-right'>
                        <span data-toggle="tooltip" title="Last Updated" class="badge bg-blue" id='dashLast<?php echo($cd_hm_system_dashboard_widget_param) ?>'>Last Updated: </span>
                        <button type="button" id='btnWidRefresh<?php echo($cd_hm_system_dashboard_widget_param) ?>' class="btn btn-box-tool" onclick="dsDashCalendarEvent<?php echo($cd_hm_system_dashboard_widget_param) ?>.refresh();"><i class="fa fa-refresh"></i></button>
                        <button type="button" id='btnWidSettings<?php echo($cd_hm_system_dashboard_widget_param) ?>' class="btn btn-box-tool" onclick="dsDashCalendarEvent<?php echo($cd_hm_system_dashboard_widget_param) ?>.toggleSettings();"><i class="fa fa-gear"></i></button>
                        <button type="button" id='btnWidCollapse<?php echo($cd_hm_system_dashboard_widget_param) ?>' class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-compress"></i></button>
                        <i id='btnWidMove<?php echo($cd_hm_system_dashboard_widget_param) ?>' style="padding-left: 5px;cursor: move;" class="fa fa-arrows-alt dashMove"></i>
                    </div>
                </div>
                <div class='box-body widgetBody' style='padding-top: 0px;'>
                    <div style='width: 100%;height: 500px' id="dash<?php echo($cd_hm_system_dashboard_widget_param) ?>" style="padding-left: 0px;padding-right: 0px;">
                        <div id="dashGrid<?php echo($cd_hm_system_dashboard_widget_param) ?>" style="width: 100%; height: 495px"></div>
                    </div>
                    <div style='width: 100%;height:calc(100% - 26px); background-color: rgba(200, 199, 200, 0.9);position: absolute;top:16px; left: 0px;display: none;border: #000 thin dotted; margin-top: 10px; z-index: 99999999' id="dashSettings<?php echo($cd_hm_system_dashboard_widget_param) ?>">

                        <form class="form-horizontal" style='padding-top: 5px;' id='formDash<?php echo($cd_hm_system_dashboard_widget_param) ?>' >
                            <div class='col-md-12 col-xs-12' >
                                <div class="col-md-12" style="padding: 0px;">
                                    <label style="text-align: left;" for="nr_refresh<?php echo($cd_hm_system_dashboard_widget_param) ?>_dash_po_form" class="col-sm-12 control-label "><?php echo($ref) ?></label>
                                    <div class="col-sm-12">
                                        <input type="text"  class="form-control input-sm" id="nr_refresh<?php echo($cd_hm_system_dashboard_widget_param) ?>_dash_po_form" mask="IS;30;150;30" fieldname="nr_refresh" type="text" must="Y">
                                    </div>
                                </div>

                                
                                <div class="col-md-12" style="padding: 0px;">
                                    <label style="text-align: left;" for="nr_days_ahead<?php echo($cd_hm_system_dashboard_widget_param) ?>_dash_po_form" class="col-sm-12 control-label "><?php echo('Days Ahead') ?></label>
                                    <div class="col-sm-12">
                                        <input type="text"  class="form-control input-sm" id="nr_days_ahead<?php echo($cd_hm_system_dashboard_widget_param) ?>_dash_po_form" mask="IS;3;30;3" fieldname="nr_days_ahead" type="text" must="Y">
                                    </div>
                                </div>
                                
                                <label for="ds_processes<?php echo($cd_hm_system_dashboard_widget_param) ?>_dash_po_form" style="display: none" class="col-sm-2 control-label "><?php echo($process) ?></label>
                                <input type="text" class="form-control input-sm" style="display: none" id="ds_processes<?php echo($cd_hm_system_dashboard_widget_param) ?>_dash_po_form" mask="t" fieldname="ds_events" type="text" must="Y">

                                <label style="text-align: left !important;" for="ds_process<?php echo($cd_hm_system_dashboard_widget_param) ?>_dash_po_form" class="col-sm-12 control-label "><?php echo($process) ?></label>



                                <div class="col-sm-12" style='padding-left: 10px;padding-right: 10px;'>
                                    <input type="hidden" class="form-control input-sm" id="ds_process<?php echo($cd_hm_system_dashboard_widget_param) ?>_dash_po_form">
                                </div>

                            </div>
                        </form>
                        <button type="button" class="btn btn-sm btn-info " style='position: absolute; bottom: 5px;right: 40px;' onclick="dsMainObjectDash.saveDashBoardSettings(<?php echo($cd_hm_system_dashboard_widget_param) ?>);"><i class="fa fa-save"></i></button>
                        <button type="button" class="btn btn-sm btn-warning " style='position: absolute; bottom: 5px;right: 5px;' onclick="dsMainObjectDash.removeDashBord(<?php echo($cd_hm_system_dashboard_widget_param) ?>);"><i class="fa fa-trash"></i></button>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<script>

    var dsDashCalendarEvent<?php echo($cd_hm_system_dashboard_widget_param) ?> = new function () {

        // variaveis privadas;

        var thisObj = this;
            <?php echo($grid)?>

        // funcao de inicio;
        this.start = function () {
            thisObj.id = <?php echo($cd_system_dashboard_widget) ?>;
            thisObj.dashDiv = "dash<?php echo($cd_hm_system_dashboard_widget_param) ?>";
            thisObj.dashDivGrid = "dashGrid<?php echo($cd_hm_system_dashboard_widget_param) ?>";
            thisObj.dashFullDiv = 'dashboard_<?php echo($cd_hm_system_dashboard_widget_param) ?>';
            thisObj.dashSettingsDiv = "dashSettings<?php echo($cd_hm_system_dashboard_widget_param) ?>";
            thisObj.options = dsMainObjectDash.getDefaultOption();
            //thisObj.options.toolbox.feature.saveAsImage['name'] = 'POEVentDeadline';
            thisObj.dashBox = 'dashBox<?php echo($cd_hm_system_dashboard_widget_param) ?>';
            thisObj.formDiv = 'formDash<?php echo($cd_hm_system_dashboard_widget_param) ?>';
            thisObj.settingsId = <?php echo($cd_hm_system_dashboard_widget_param) ?>;
            thisObj.formRefreshId = "nr_refresh<?php echo($cd_hm_system_dashboard_widget_param) ?>_dash_po_form";
            thisObj.daysAheadId = "nr_days_ahead<?php echo($cd_hm_system_dashboard_widget_param) ?>_dash_po_form";

            thisObj.formProcessId = "ds_processes<?php echo($cd_hm_system_dashboard_widget_param) ?>_dash_po_form";
            thisObj.selectProcessId = 'ds_process<?php echo($cd_hm_system_dashboard_widget_param) ?>_dash_po_form';
            thisObj.gridName = 'eventGrid<?php echo($cd_hm_system_dashboard_widget_param) ?>';
            thisObj.mainTitle = '<?php echo($ds_system_dashboard_widget) ?>';
            thisObj.badgeLast = 'dashLast<?php echo($cd_hm_system_dashboard_widget_param) ?>';
            thisObj.jsonSettings = <?php
if ($json_parameters == '') {
    $json_parameters = '{}';
};
echo($json_parameters);
?>;
            
                
            thisObj.refreshing = false;
            thisObj.lastRefresh = undefined;

            $('#dashboard_<?php echo($cd_hm_system_dashboard_widget_param) ?>').detach().appendTo("#dashBoardWidgetsArea");


            $('#' + thisObj.selectProcessId).select2({data: <?php echo(json_encode($options));?>, multiple: true});

            thisObj.myForm = $('#'+thisObj.formDiv).cgbForm({formSuffix: '<?php echo($cd_hm_system_dashboard_widget_param) ?>_dash_po_form', checkDemandedForInvisible: true});


            this.addListeners();
            this.setInitialSetup();
            thisObj.refresh(true);
            $('#'+thisObj.dashDivGrid).w2grid(makeGrid);

        }


        this.getRefreshInterval = function () {
            return thisObj.myForm.getItem(thisObj.formRefreshId)
        }

        this.setInitialSetup = function () {
            var vjson = thisObj.jsonSettings;

            if (vjson.nr_refresh == undefined) {
                var vref = dsMainObjectDash.getDefaultRefreshTime();
            } else {
                var vref = vjson.nr_refresh;
            }

            
            var vdaysheader = chkUndefined(vjson.nr_days_ahead, 3);

            thisObj.myForm.setItem(thisObj.formRefreshId, vref);
            thisObj.myForm.setItem(thisObj.daysAheadId, vdaysheader);

            if (vjson.ds_events != undefined) {
                var vprc = JSON.parse('[' + vjson.ds_events + ']');
                $('#' + thisObj.selectProcessId).val(vprc).trigger("change");
            }



        }

        this.getDiv = function () {
            return thisObj.dashFullDiv;
        }


        this.getSettings = function () {


            var err = thisObj.myForm.checkDemanded(true);

            if (err.length > 0) {

                var errs = '<BR>';
                $.each(err, function (index, value) {
                    if (errs.indexOf(value.title + '<br>') == -1) {
                        errs = errs + value.title + '<br>';
                    }
                });

                messageBoxError(javaMessages.required_info + errs);
                return;
            }


            return thisObj.myForm.getChanges();
        }

        this.die = function () {
            thisObj = undefined;
            //this = undefined;
        }

        this.resize = function () {
            w2ui[thisObj.gridName].resize();

        }

        this.resizeSettings = function () {

        }

        this.afterSettigsSaved = function () {
            this.toggleSettings();
            this.refresh(true);
        }

        this.refresh = function () {
            var err = thisObj.myForm.checkDemanded(true);
            if (err.length > 0 && !thisObj.isSettingsVisible()) {
                thisObj.toggleSettings();
                return;
            }


            thisObj.refreshing = true;

            $.myCgbAjax({url: 'dashboard/dashboard/refresh/' + thisObj.id + '/' + thisObj.settingsId,
                message: javaMessages.updating,
                box: '#' + thisObj.dashFullDiv,
                data: [],
                systemRequest: true,
                success: function (a) {

                    w2ui[thisObj.gridName].clear();
                    w2ui[thisObj.gridName].add(a);
                    

                    thisObj.lastRefresh = moment();
                    $('#' + thisObj.badgeLast).text(thisObj.lastRefresh.format('HH:mm'));

                    thisObj.refreshing = false;

                },
            });


        }
        // funcao de toolbar;


        // adicao de listeners!
        this.addListeners = function () {
            $('#' + thisObj.dashBox).on('lte.uncollapsed-box', function () {
                thisObj.resize();
            });


            $('#' + thisObj.selectProcessId).on('change', function (a) {
                var vdata = select2GetData(thisObj.selectProcessId);
                var varray = [];
                $.each(vdata, function (i, v) {
                    varray.push(v.id);
                })



                thisObj.myForm.setItem(thisObj.formProcessId, varray.toString());
            })


        }

        // roda antes de fechar (se retornar FALSE o sistema vai perguntar se quer fechar
        this.beforeClose = function () {
            return true;
        }


        // close object (lugar para destruir as coisas//
        this.close = function () {
            //w2ui[thisObj.gridName].destroy();
            return true;
        }



        this.isSettingsVisible = function () {
            return $('#' + thisObj.dashSettingsDiv).is(':visible');
        }

        this.toggleSettings = function () {
            //var vshow = $('#'+thisObj.dashSettingsDiv).is(':visible');
            $('#' + thisObj.dashSettingsDiv).slideToggle(200, function () {
                thisObj.resizeSettings();
                ;
            });
            /*
             $('#' + thisObj.dashDiv).slideToggle(200, function () {
             thisObj.resizeChart();
             });
             */
        }
        
        this.toolbar = function() {
            // tem que existir pro export funcaionar.
        }

    }
    dsMainObjectDash.addDashBord(<?php echo($cd_hm_system_dashboard_widget_param) ?>, dsDashCalendarEvent<?php echo($cd_hm_system_dashboard_widget_param) ?>);
</script>

