<style>

    .fc-day-grid-event > .fc-content {
        text-overflow: ellipsis;
        white-space: nowrap;
        cursor: pointer; 
        max-height:20px; 
    }


</style>


<div class="col-sm-12 col-md-6 dragTile" settingsId ="<?php echo($cd_hm_system_dashboard_widget_param) ?>" id='dashboard_<?php echo($cd_hm_system_dashboard_widget_param) ?>' style="height: 550px">
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
                        <div id="dashCal<?php echo($cd_hm_system_dashboard_widget_param) ?>" style="width: 100%"></div>
                    </div>
                    <div style='width: 100%;height:calc(100% - 26px); background-color: rgba(200, 199, 200, 0.9);position: absolute;top:16px; left: 0px;display: none;border: #000 thin dotted; margin-top: 10px; z-index: 99999999' id="dashSettings<?php echo($cd_hm_system_dashboard_widget_param) ?>">

                        <form class="form-horizontal" style='padding-top: 5px;' id='formDash<?php echo($cd_hm_system_dashboard_widget_param) ?>' >
                            <div class='col-md-12 col-xs-12' >
                                <div class="col-md-12" style="padding: 0px;">
                                    <label for="nr_refresh<?php echo($cd_hm_system_dashboard_widget_param) ?>_dash_po_form" class="col-sm-3 control-label "><?php echo($ref) ?>:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control input-sm" id="nr_refresh<?php echo($cd_hm_system_dashboard_widget_param) ?>_dash_po_form" mask="IS;30;150;30" fieldname="nr_refresh" type="text" must="Y">
                                    </div>
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

        // funcao de inicio;
        this.start = function () {
            thisObj.id = <?php echo($cd_system_dashboard_widget) ?>;
            thisObj.dashDiv = "dash<?php echo($cd_hm_system_dashboard_widget_param) ?>";
            thisObj.dashDivCal = "dashCal<?php echo($cd_hm_system_dashboard_widget_param) ?>";
            thisObj.dashFullDiv = 'dashboard_<?php echo($cd_hm_system_dashboard_widget_param) ?>';
            thisObj.dashSettingsDiv = "dashSettings<?php echo($cd_hm_system_dashboard_widget_param) ?>";
            thisObj.options = dsMainObjectDash.getDefaultOption();
            //thisObj.options.toolbox.feature.saveAsImage['name'] = 'POEVentDeadline';
            thisObj.dashBox = 'dashBox<?php echo($cd_hm_system_dashboard_widget_param) ?>';
            thisObj.formDiv = 'formDash<?php echo($cd_hm_system_dashboard_widget_param) ?>';
            thisObj.settingsId = <?php echo($cd_hm_system_dashboard_widget_param) ?>;
            thisObj.formProcessId = "ds_processes<?php echo($cd_hm_system_dashboard_widget_param) ?>_dash_po_form";
            thisObj.formRefreshId = "nr_refresh<?php echo($cd_hm_system_dashboard_widget_param) ?>_dash_po_form";

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


            //thisObj.myChart.setOption(thisObj.options);

            thisObj.myForm = $('#' + thisObj.formDiv).cgbForm({formSuffix: '<?php echo($cd_hm_system_dashboard_widget_param) ?>_dash_po_form'});


            $('#' + thisObj.dashDivCal).fullCalendar({
                defaultDate: moment().format('YYYY-MM-DD'),
                defaultView: 'month',
                events: 'hrms/calendar/getDates',

                eventAfterAllRender: function (view, element) {
                    $(".fc-content").each(function () {
                        $(this).attr("title", $(this).text());
                        $(this).tooltip({container: 'body'});
                    })
                    

                }

            });

            this.addListeners();
            this.setInitialSetup();
            thisObj.refresh(true);

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

            thisObj.myForm.setItem(thisObj.formRefreshId, vref);


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
            $('#' + thisObj.dashDivCal).height(470);
            $('#' + thisObj.dashDivCal).fullCalendar('option', 'height', 470);
            $('#' + thisObj.dashDivCal).fullCalendar('render');

            //this.resizeChart();
            //this.resizeSettings();
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
            }

            thisObj.refreshing = true;

            $('#' + thisObj.dashDivCal).fullCalendar('refetchEvents')
            thisObj.lastRefresh = moment();
            $('#' + thisObj.badgeLast).text(thisObj.lastRefresh.format('HH:mm'));

            thisObj.refreshing = false;

        }
        // funcao de toolbar;


        // adicao de listeners!
        this.addListeners = function () {
            $('#' + thisObj.dashBox).on('lte.uncollapsed-box', function () {
                thisObj.resize();
            });


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

    }
    dsMainObjectDash.addDashBord(<?php echo($cd_hm_system_dashboard_widget_param) ?>, dsDashCalendarEvent<?php echo($cd_hm_system_dashboard_widget_param) ?>);
</script>

