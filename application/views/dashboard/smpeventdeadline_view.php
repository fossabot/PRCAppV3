<div class="col-md-6 dragTile" settingsId ="<?php echo($cd_hm_system_dashboard_widget_param) ?>" style='' id='dashboard_<?php echo($cd_hm_system_dashboard_widget_param) ?>'>
    <div class="row">
        <div class="col-md-12" style='padding-left: 5px; padding-right: 5px;'>
            <div class='box  box-info box-solid widgetShadow' id = 'dashBox<?php echo($cd_hm_system_dashboard_widget_param) ?>'>
                <div class='box-header with-border '>
                    <h4 class='box-title'><?php echo($ds_system_dashboard_widget) ?></h4>
                    <div class='box-tools pull-right'>
                        <span data-toggle="tooltip" title="Last Updated" class="badge bg-blue" id='dashLast<?php echo($cd_hm_system_dashboard_widget_param) ?>'>Last Updated: </span>
                        <button type="button" id='btnWidRefresh<?php echo($cd_hm_system_dashboard_widget_param) ?>' class="btn btn-box-tool" onclick="dsDashPOPrevent<?php echo($cd_hm_system_dashboard_widget_param) ?>.refresh();"><i class="fa fa-refresh"></i></button>
                        <button type="button" id='btnWidSettings<?php echo($cd_hm_system_dashboard_widget_param) ?>' class="btn btn-box-tool" onclick="dsDashPOPrevent<?php echo($cd_hm_system_dashboard_widget_param) ?>.toggleSettings();"><i class="fa fa-gear"></i></button>
                        <button type="button" id='btnWidCollapse<?php echo($cd_hm_system_dashboard_widget_param) ?>' class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-compress"></i></button>
                        <i id='btnWidMove<?php echo($cd_hm_system_dashboard_widget_param) ?>' style="padding-left: 5px;cursor: move;" class="fa fa-arrows-alt dashMove"></i>
                    </div>
                </div>
                <div class='box-body widgetBody' style='padding-top: 0px;'>
                    <div style='width: 100%;height: 300px' id="dash<?php echo($cd_hm_system_dashboard_widget_param) ?>"></div>
                    <div style='width: 100%;height:calc(100% - 26px); background-color: rgba(200, 199, 200, 0.9);position: absolute;top:16px; left: 0px;display: none;border: #000 thin dotted; margin-top: 10px' id="dashSettings<?php echo($cd_hm_system_dashboard_widget_param) ?>">

                        <form class="form-horizontal" style='padding-top: 5px;' id='formDash<?php echo($cd_hm_system_dashboard_widget_param) ?>' >
                            <div class='col-md-12 col-xs-12' >
                                <div class="col-md-12" style="padding: 0px;">
                                    <label for="nr_refresh<?php echo($cd_hm_system_dashboard_widget_param) ?>_dash_po_form" class="col-sm-2 control-label "><?php echo($ref) ?>:</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control input-sm" id="nr_refresh<?php echo($cd_hm_system_dashboard_widget_param) ?>_dash_po_form" mask="IS;30;150;30" fieldname="nr_refresh" type="text" must="Y">
                                    </div>
                                </div>

                                <label for="ds_season<?php echo($cd_hm_system_dashboard_widget_param) ?>_dash_po_form" class="col-sm-2 control-label "><?php echo($season) ?>:</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control input-sm" id="ds_season<?php echo($cd_hm_system_dashboard_widget_param) ?>_dash_po_form" mask="PL" model = "<?php echo ($this->encodeModel('season_model')); ?>" fieldname="ds_season" code_field="cd_season"  type="text" must="Y">
                                </div>

                                <label for="ds_division<?php echo($cd_hm_system_dashboard_widget_param) ?>_dash_po_form" class="col-sm-2 control-label "><?php echo($division) ?>:</label>
                                <div class="col-sm-4">
                                    <input type="text"  class="form-control input-sm" id="ds_division<?php echo($cd_hm_system_dashboard_widget_param) ?>_dash_po_form" mask="PL" model = "<?php echo ($this->encodeModel('division_model')); ?>" fieldname="ds_division" code_field="cd_division" type="text"  must="Y">
                                </div>
                                <label for="ds_processes<?php echo($cd_hm_system_dashboard_widget_param) ?>_dash_po_form" style="display: none" class="col-sm-2 control-label "><?php echo($process) ?>:</label>
                                <input type="text" class="form-control input-sm" style="display: none" id="ds_processes<?php echo($cd_hm_system_dashboard_widget_param) ?>_dash_po_form" mask="t" fieldname="ds_processes" type="text" must="Y">

                                <label for="ds_process<?php echo($cd_hm_system_dashboard_widget_param) ?>_dash_po_form" class="col-sm-2 control-label "><?php echo('Process') ?>:</label>



                                <div class="col-sm-10" style='padding-left: 10px;padding-right: 10px;'>
                                    <input type="hidden" class="form-control input-sm" id="ds_process<?php echo($cd_hm_system_dashboard_widget_param) ?>_dash_po_form" fieldname="ds_processes" type="text" dem="Y">
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

    var dsDashPOPrevent<?php echo($cd_hm_system_dashboard_widget_param) ?> = new function () {

        // variaveis privadas;

        var thisObj = this;

        // funcao de inicio;
        this.start = function () {
            thisObj.id = <?php echo($cd_system_dashboard_widget) ?>;
            thisObj.dashDiv = "dash<?php echo($cd_hm_system_dashboard_widget_param) ?>";
            thisObj.dashFullDiv = 'dashboard_<?php echo($cd_hm_system_dashboard_widget_param) ?>';
            thisObj.dashSettingsDiv = "dashSettings<?php echo($cd_hm_system_dashboard_widget_param) ?>";
            thisObj.options = dsMainObjectDash.getDefaultOption();
            thisObj.options.toolbox.feature.saveAsImage['name'] = 'POEVentDeadline';
            thisObj.dashBox = 'dashBox<?php echo($cd_hm_system_dashboard_widget_param) ?>';
            thisObj.formDiv = 'formDash<?php echo($cd_hm_system_dashboard_widget_param) ?>';
            thisObj.settingsId = <?php echo($cd_hm_system_dashboard_widget_param) ?>;
            thisObj.myChart = echarts.init(document.getElementById(thisObj.dashDiv));
            thisObj.formProcessId = "ds_processes<?php echo($cd_hm_system_dashboard_widget_param) ?>_dash_po_form";
            thisObj.formSeasonId = "ds_season<?php echo($cd_hm_system_dashboard_widget_param) ?>_dash_po_form";
            thisObj.formDivisionId = "ds_division<?php echo($cd_hm_system_dashboard_widget_param) ?>_dash_po_form";
            thisObj.formRefreshId = "nr_refresh<?php echo($cd_hm_system_dashboard_widget_param) ?>_dash_po_form";

            thisObj.selectProcessId = 'ds_process<?php echo($cd_hm_system_dashboard_widget_param) ?>_dash_po_form';
            thisObj.availPrc = <?php echo(json_encode($process)); ?>;
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

            thisObj.myForm = $('#'+thisObj.formDiv).cgbForm({formSuffix: '<?php echo($cd_hm_system_dashboard_widget_param) ?>_dash_po_form'});

            $('#' + thisObj.selectProcessId).select2({data: thisObj.availPrc, multiple: true});

            this.addListeners();
            this.setInitialSetup();
            thisObj.refresh(true);

        }


        this.getRefreshInterval = function () {
            return thisObj.myForm.getItem(thisObj.formRefreshId)
        }

        this.setInitialSetup = function () {
            var vjson = thisObj.jsonSettings;

            if (vjson.cd_division != undefined) {
                thisObj.myForm.setItemPL(thisObj.formDivisionId, vjson.cd_division, vjson.ds_division);
            }

            if (vjson.cd_season != undefined) {
                thisObj.myForm.setItemPL(thisObj.formSeasonId, vjson.cd_season, vjson.ds_season);
            }



            if (vjson.ds_processes != undefined) {
                var vprc = JSON.parse('[' + vjson.ds_processes + ']');
                $('#' + thisObj.selectProcessId).val(vprc).trigger("change");
            }

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
            this.resizeChart();
            this.resizeSettings();
        }

        this.resizeSettings = function () {

        }

        this.resizeChart = function () {
            thisObj.myChart.resize();

        }

        this.afterSettigsSaved = function () {
            this.toggleSettings();
            this.refresh(true);
        }

        this.refresh = function (fisttime) {

            var err = thisObj.myForm.checkDemanded(true);
            if (err.length > 0 && !thisObj.isSettingsVisible()) {
                thisObj.toggleSettings();
            }

            thisObj.refreshing = true;


            $.myCgbAjax({url: 'dashboard/dashboard/refresh/' + thisObj.id + '/' + thisObj.settingsId,
                message: javaMessages.updating,
                box: '#' + thisObj.dashFullDiv,
                data: [],
                systemRequest: true,
                success: function (a) {

                    thisObj.lastRefresh = moment();
                    $('#' + thisObj.badgeLast).text(thisObj.lastRefresh.format('HH:mm'));


                    var vp = thisObj.parseData(a, fisttime);
                    if (fisttime || true) {
                        thisObj.myChart.clear();
                        var vopt = thisObj.options;
                    } else {
                        var vopt = {};
                    }

                    if (Object.keys(vp) == 0) {

                        thisObj.myChart.clear();
                        return;
                    }


                    if (vp.series.length == 0) {
                        thisObj.myChart.clear();
                        return;
                    }


                    var voptions = $.extend({}, vopt, vp);


                    thisObj.myChart.setOption(voptions);
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

<?php if ($canfollowup == 'Y') { ?>

                thisObj.myChart.on('dblclick', function (params) {
                    thisObj.openFollowUp(params);
                });
<?php } ?>

        }

<?php if ($canfollowup == 'Y') { ?>

            this.openFollowUp = function (param) {

                var vdiv = thisObj.myForm.getItemPLCode(thisObj.formDivisionId);
                var vseason = thisObj.myForm.getItemPLCode(thisObj.formSeasonId);
                var vstatus = '';
                var vprocess = '';

                if (param.componentSubType == 'pie') {
                    vstatus = param.name;
                    vprocess = $('#' + thisObj.selectProcessId).val();
                } else {
                    var vx = select2GetData(thisObj.selectProcessId);
                    vstatus = param.seriesName;
                    $.each(vx, function (i, v) {
                        if (v.text == param.name) {
                            vprocess = v.id;
                            return false;
                        }
                    })

                }

                var vsend = {div: vdiv, season: vseason, sts: vstatus, prc: vprocess, type: 'SMP'}

                window.open('main/redirect/spec/shoe_process/shoe_process_status?param=' + JSON.stringify(vsend), '_newtab');



            }
<?php } ?>

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

        this.parseData = function (data, fisttime) {
            if (!data.dataLegend) {
                return {};
            }

            var vdata = select2GetData(thisObj.selectProcessId);
            var vTitle = '';
            thisObj.formProcessId = "ds_processes<?php echo($cd_hm_system_dashboard_widget_param) ?>_dash_po_form";
            thisObj.formSeasonId = "ds_season<?php echo($cd_hm_system_dashboard_widget_param) ?>_dash_po_form";
            thisObj.formDivisionId = "ds_division<?php echo($cd_hm_system_dashboard_widget_param) ?>_dash_po_form";

            var vseason = chkUndefined(thisObj.myForm.getItem(thisObj.formSeasonId), '');
            var vdiv = chkUndefined(thisObj.myForm.getItem(thisObj.formDivisionId), '');


            vTitle = vseason + ' / ' + vdiv;

            if (vdata.length == 1) {
                vTitle = vTitle + ' / ' + vdata[0].text;

                var vLegendFormat = [];

                $.each(data.dataLegend, function (i, v) {
                    vLegendFormat.push({name: v, icon: 'circle'});
                });

                var opt2 = {
                    legend: {
                        orient: 'vertical',
                        x: 'right',
                        align: 'left',
                        data: vLegendFormat,
                        top: 30
                    },
                    title: {
                        text: vTitle,
                        x: 'center',
                        textStyle: {fontSize: 14},

                    },
                    tooltip: {
                        trigger: 'item',
                        formatter: "{b} : {c} ({d}%)"
                    },
                    graphic: {
                        elements: [{
                                type: 'text',
                                style: {
                                    text: '100%',
                                    font: '1.8em "STHeiti", sans-serif',
                                    textAlign: 'center',
                                    //height: 30
                                },
                                left: '39%',
                                top: '45%'
                            },{
                                type: 'text',
                                style: {
                                    text: '<?php echo($total)?>',
                                    font: '1em "STHeiti", sans-serif',
                                    textAlign: 'center',
                                    fill: '#829595',

                                    width: 200
                                    //height: 30
                                },
                                left: '35%',
                                top: '54%'
                            }
                        ]
                    },
                    color: data.dataColor


                };

                var vSeries = [
                    {
                        name: 'Name',
                        type: 'pie',
                        label: {
                            normal: {
                                formatter: '{b}\n{d}%'
                            }},
                        radius: ['40%', '62%'],
                        center: ['40%', '50%'],
                        data: []
                    }
                ];

                var vTotal = 0;
                $.each(data.data, function (i, v) {
                    vSeries[0].data.push({name: data.dataLegend[i], value: v[0]});
                    vTotal =vTotal + v[0];
                });

                opt2.graphic.elements[0].style.text = vTotal;
                


            } else {


                var opt2 = {
                    yAxis: {
                        type: 'value'
                    },
                    xAxis: {
                        type: 'category',
                        data: data.category
                    },
                    color: data.dataColor,
                    title: {
                        text: vTitle,
                        x: 'center',
                        textStyle: {fontSize: 14}
                    },

                };


                //var vlegend = [];
                var vSeries = [];
                var vLegendFormat = [];

                $.each(data.dataLegend, function (i, v) {
                    vSeries[i] = {
                        name: v,
                        type: 'bar',
                        stack: '1',
                        label: {
                            normal: {
                                show: true,
                                formatter: function(param){
                                if (param.value > 0){
                                    return param.value;
                                } else {
                                    return '';
                                }
                                },
                                position: 'inside'
                            }
                        },
                        data: data.data[i]
                    };
                    vLegendFormat.push({name: v, icon: 'roundRect'});
                });
                
                opt2['legend'] = {'data': vLegendFormat, top: 22};
            }

            

            return $.extend({}, opt2, {series: vSeries});

        }

        // funcaoes gerais 

    }
    dsMainObjectDash.addDashBord(<?php echo($cd_hm_system_dashboard_widget_param) ?>, dsDashPOPrevent<?php echo($cd_hm_system_dashboard_widget_param) ?>);
</script>

