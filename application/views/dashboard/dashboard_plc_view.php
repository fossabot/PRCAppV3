<?php //include_once APPPATH . 'views/viewIncludeFilter.php';               ?>
<style> 
    .dashBackground {
        background-color: rgb(33, 31, 36) !important;
        color: white;
    }
    .w2ui-grid tr {
        background-color: black !important;
        color: white !important;
    }
    .w2ui-grid .w2ui-grid-toolbar {
        background-color: black !important;
        color: white !important;
    }

    .w2ui-col-header  {
        background-color: black !important;
        color: white;
    }
    .w2ui-head-last > div{
        background-color: black !important;
        color: white;
    }
    .w2ui-grid-footer {
        background-color: black !important;
        color: white;
        
    }
    
    .graphArea {
        padding-left: 2px;
        padding-right: 2px;
    }
</style>

<script>
// aqui tem os scripts basicos. 
    var gridName = "gridGeneric";
//var controllerName = "country";



//$(".ds_hr_type").on( "change",  function() {



    var dsMainObject = new function () {


        // variaveis privadas;

        var thisObj = this;
        thisObj.gridName = undefined;
        thisObj.nrControllers = 0;

        this.gridProgress = function (record, index, column_index) {

            if (record == undefined) {
                return;
            }

            var vinfo = Math.round(chkUndefined(record[this.columns[column_index].field], 0));

            var vtxt = vinfo + '%';
            if (vinfo > 100) {
                vinfo = 100;
                vtxt = "EOL";
            }

            var vdata = '<div class="progress" style="padding: 0px;height: 22px;margin-bottom: 0px;"  >' +
                    '<div class="progress-bar progress-bar-primary " role="progressbar" style="width: ' + vinfo + '%;">' + vtxt + '</div>' +
                    '<div class="progress-bar  " role="progressbar" style="width:' + (100 - vinfo) + '%; background-color: black "></div>' +
                    '</div>';

            return vdata;
        }


        // funcao de inicio;
        this.start = function (gridNamePar) {
            thisObj.gridName = gridNamePar;

            $('.content-wrapper').addClass('dashBackground');
            $('.content-header').addClass('hidden');

<?php echo ($javascript); ?>


            this.addListeners();
            this.addHelper();
            /*
             setTimeout(function () {
             }, 0);
             */

            thisObj.PlcInterval = setInterval(function () {
                thisObj.refreshData();
            }, 60000);

            thisObj.myChart = echarts.init(document.getElementById('compgauge'));
            thisObj.myChartAlarm = echarts.init(document.getElementById('compgaugealarm'));
            thisObj.myChartOffline = echarts.init(document.getElementById('compgaugeoffline'));
            thisObj.myChartPieDep = echarts.init(document.getElementById('piedep'));

            thisObj.setGaugeData();

            this.makePieDep(<?php echo(json_encode($piedata)); ?>);

        }


        this.refreshData = function () {
            $.myCgbAjax({url: 'dashboard/dashboard_plc/getLifeBrakesControllerData',
                message: javaMessages.retrieveInfo,
                box: '',
                dataType: 'json',
                systemRequest: true,
                success: function (a) {
                    //w2ui[gridName].clear();

                    $.each(a.grid, function (i, v) {
                        w2ui[gridName].set(v.recid, v);
                    })

                    thisObj.setGaugeData();
                    thisObj.makePieDep(a.piedep);

                }
            });
        }


        this.addHelper = function () {
        }

        // funcao de toolbar;
        this.ToolBarClick = function (bPressed, dData) {

        }


        // adicao de listeners!
        this.addListeners = function () {

        }

        // roda antes de fechar (se retornar FALSE o sistema vai perguntar se quer fechar
        this.beforeClose = function () {
            return true;
        }


        // close object (lugar para destruir as coisas//
        this.close = function () {
            //w2ui[thisObj.gridName].destroy();
//            introRemove();
            $('.content-wrapper').removeClass('dashBackground');
            $('.content-header').removeClass('hidden');
            clearInterval(thisObj.PlcInterval);

            return true;
        }

        this.setGaugeData = function () {
            thisObj.nrControllers = w2ui[gridName].records.length;
            
            var vrunning = 0;
            var v_running_and_pause = 0;
            var valarm = 0;
            var vofflinefinished = 0;
            var vofflinenotfinished = 0;
            var vendandnotstrated = 0;



            $.each(w2ui[gridName].records, function (i, v) {
                if (v.nr_id_status == 4) {
                    vrunning++;
                    v_running_and_pause++;
                }

                if (v.nr_id_status == 2) {
                    v_running_and_pause++;
                }

                if (v.nr_id_status == 5) {
                    valarm++;
                }

                if (v.nr_testresult == 1 && v.nr_id_status == 1) {
                    vofflinefinished++;
                }

                if (v.nr_testresult == 0 && v.nr_id_status == 1) {
                    vofflinenotfinished++;
                }
                if (v.nr_id_status == 3 || v.nr_id_status == 6) {
                    vendandnotstrated++;
                }


            });


            thisObj.makeGauge(v_running_and_pause, vrunning);
            thisObj.makeGaugeAlarm(valarm, vendandnotstrated);
            thisObj.makeGaugeOffline(vofflinenotfinished, vofflinefinished);
            


            //var voption = 
        }


        this.makePieDep = function (rs) {
            //{"value": 261, "name": "A供应商"}, {"value": 200, "name": "B供应商"}, {"value": 180, "name": "C供应商"}, {"value": 170, "name": "D供应商"}, {"value": 160, "name": "E供应商"}, {"value": 150, "name": "F供应商"}, {"value": 140, "name": "G供应商"}, {"value": 130, "name": "H供应商"}, {"value": 120, "name": "I供应商"}, {"value": 110, "name": "J供应商"}, {"value": 100, "name": "K供应商"}, {"value": 90, "name": "L供应商"}, {"value": 80, "name": "M供应商"}, {"value": 70, "name": "N供应商"}, {"value": 60, "name": "O供应商"}, {"value": 50, "name": "P供应商"}, {"value": 40, "name": "Q供应商"}, {"value": 30, "name": "R供应商"}, {"value": 20, "name": "S供应商"}, {"value": 10, "name": "T供应商"}
            var data = [];
            var count_num = 0;

            $.each(rs, function (i, v) {
                data.push({value: v.nr_count, name: v.ds_department});
                count_num = count_num + v.nr_count;
            })

            /*for (var n  in data){
             data[n]['name'] = data[n]['name'] + ' '+((data[n]['value']/count_num)*100).toFixed(1) +'%'
             }*/

            var voption = {
                backgroundColor: '#000',
                title: {
                    text: 'Samples by Department',
                    //subtext: 'Total Tools ' + count_num,
                    x: 'center',
                    "center": ["30%", "30%"],

                    textStyle: {color: '#fff', fontSize: 10},

                },
                //显示series中信息，提示框组件
                tooltip: {
                    trigger: 'item',
                    formatter: "{b} : {c} ({d}%)"
                },

                series: [
                    {
                        type: 'pie',
                        radius: '55%', //半径
                        center: ['50%', '50%'],
                        label: {
                            normal: {
                                formatter: '{b}\n{c}'
                            },

                        },

                        data: data,
                        itemStyle: {//itemStyle有正常显示：normal，有鼠标hover的高亮显示：emphasis
                            emphasis: {//normal显示阴影,与shadow有关的都是阴影的设置
                                shadowBlur: 10, //阴影大小
                                shadowOffsetX: 0, //阴影水平方向上的偏移
                                shadowColor: 'rgba(0, 0, 0, 0.5)'  //阴影颜色
                            }
                        }
                    }
                ]
            };

            thisObj.myChartPieDep.setOption(voption, {notMerge: true});
            


        }

        this.makeGauge = function (vrunandidle, vrun) {

            var vradius = '110%';

            var option = {
                backgroundColor: '#000',
                "title": {
                    "show": true,
                    "center": ["30%", "30%"],
                    textStyle: {color: '#fff', fontSize: 10},
                    text: "Running " + vrun + "\nRunning and Paused " + vrunandidle
                },
                "series": [{
                        "title": {
                            "show": false

                        },
                        "data": [{
                                "value": vrun

                            }],

                        "name": "sdas",
                        "type": "gauge",
                        "min": 0,
                        "max": thisObj.nrControllers,
                        "splitNumber": 10,
                        "startAngle": 180,
                        "endAngle": 0,
                        "center": ["50%", "90%"],
                        "radius": vradius,

                        "axisLabel": {
                            "distance": 0,
                            //"fontFamily": "Microsoft YaHei UI",
                            "fontSize": 10,
                            "fontWeight": "normal",
                            "fontStyle": "normal",
                            "color": "#FFFFFF"
                        },
                        "axisLine": {
                            "lineStyle": {
                                "width": 2,
                                "color": [
                                    [1, {
                                            "x": "0.00",
                                            "y": "0.00",
                                            "x2": "1.00",
                                            "y2": "1.00",
                                            "type": "linear",
                                            "global": false,
                                            "colorStops": [{
                                                    "offset": 0,
                                                    "color": "rgba(0,0,0,1)"
                                                }, {
                                                    "offset": 1,
                                                    "color": "rgba(202,95,95,1)"
                                                }, {
                                                    "offset": 0.3579,
                                                    "color": "rgba(34,72,61,1)"
                                                }, {
                                                    "offset": 0.6895,
                                                    "color": "rgba(39,175,88,1)"
                                                }, {
                                                    "offset": 0.1211,
                                                    "color": "rgba(52,225,41,1)"
                                                }, {
                                                    "offset": 0.8105,
                                                    "color": "rgba(218,190,35,1)"
                                                }]
                                        }]
                                ]
                            }
                        },
                        "pointer": {
                            "show": true,
                            width: 3
                        },
                        "itemStyle": {
                            "normal": {
                                "color": {
                                    "x": "0.00",
                                    "y": "0.00",
                                    "x2": "1.00",
                                    "y2": "1.00",
                                    "type": "linear",
                                    "global": false,
                                    "colorStops": [{
                                            "offset": 0,
                                            "color": "rgba(0,0,0,1)"
                                        }, {
                                            "offset": 1,
                                            "color": "rgba(202,95,95,1)"
                                        }, {
                                            "offset": 0.3579,
                                            "color": "rgba(34,72,61,1)"
                                        }, {
                                            "offset": 0.6895,
                                            "color": "rgba(224,229,37,1)"
                                        }]
                                },
                                "borderColor": {
                                    "x": "0.00",
                                    "y": "0.00",
                                    "x2": "1.00",
                                    "y2": "1.00",
                                    "type": "linear",
                                    "global": false,
                                    "colorStops": [{
                                            "offset": 0,
                                            "color": "rgba(0,0,0,1)"
                                        }, {
                                            "offset": 1,
                                            "color": "rgba(202,95,95,1)"
                                        }, {
                                            "offset": 0.3579,
                                            "color": "rgba(34,72,61,1)"
                                        }, {
                                            "offset": 0.6895,
                                            "color": "rgba(224,229,37,1)"
                                        }]
                                },
                                "borderWidth": 1
                            }
                        },
                        "axisTick": {
                            "length": 16,
                            "lineStyle": {
                                "width": 2,
                                "color": {
                                    "x": "0.00",
                                    "y": "0.00",
                                    "x2": "1.00",
                                    "y2": "1.00",
                                    "type": "linear",
                                    "global": false,
                                    "colorStops": [{
                                            "offset": 0,
                                            "color": "rgba(0,0,0,1)"
                                        }, {
                                            "offset": 1,
                                            "color": "rgba(202,95,95,1)"
                                        }, {
                                            "offset": 0.3579,
                                            "color": "rgba(34,72,61,1)"
                                        }, {
                                            "offset": 0.6895,
                                            "color": "rgba(224,229,37,1)"
                                        }]
                                }
                            }
                        },
                        "splitLine": {
                            "length": 32,
                            "lineStyle": {
                                "width": 4,
                                "color": {
                                    "x": "0.00",
                                    "y": "0.00",
                                    "x2": "1.00",
                                    "y2": "1.00",
                                    "type": "linear",
                                    "global": false,
                                    "colorStops": [{
                                            "offset": 0,
                                            "color": "rgba(0,0,0,1)"
                                        }, {
                                            "offset": 1,
                                            "color": "rgba(202,95,95,1)"
                                        }, {
                                            "offset": 0.3579,
                                            "color": "rgba(34,72,61,1)"
                                        }, {
                                            "offset": 0.6895,
                                            "color": "rgba(224,229,37,1)"
                                        }]
                                }
                            }
                        },
                        "detail": {
                            "show": false
                        }

                    }, {
                        "name": "Colorful",
                        "type": "gauge",
                        "min": 0,
                        "max": thisObj.nrControllers,
                        "splitNumber": 1,
                        "startAngle": 180,
                        "endAngle": 0,
                        "center": ["50%", "90%"],
                        "radius": vradius,
                        "axisLine": {
                            "lineStyle": {
                                "width": 10,
                                "color": [
                                    [vrunandidle / 100, {
                                            "x": "0.00",
                                            "y": "0.00",
                                            "x2": "1.00",
                                            "y2": "1.00",
                                            "type": "linear",
                                            "global": false,
                                            "colorStops": [{
                                                    "offset": 0,
                                                    "color": "rgba(0,0,0,1)"
                                                }, {
                                                    "offset": 1,
                                                    "color": "rgba(202,95,95,1)"
                                                }, {
                                                    "offset": 0.3579,
                                                    "color": "rgba(34,72,61,1)"
                                                }, {
                                                    "offset": 0.6895,
                                                    "color": "rgba(224,229,37,1)"
                                                }]
                                        }],
                                    [1, "rgba(0,0,0,0)"]
                                ],
                                shadowColor: '#DFE127', //默认透明
                                shadowBlur: 10
                            }
                        },
                        "pointer": {
                            "show": false
                        },
                        "axisTick": {
                            "show": false
                        },
                        "axisLabel": {
                            "show": false
                        },
                        "splitLine": {
                            "show": false
                        },
                        "detail": {
                            "show": true
                        }
                    }, {
                        "title": {
                            "show": false
                        },
                        "name": "thinarc",
                        "type": "gauge",
                        "min": 0,
                        "max": thisObj.nrControllers,
                        "splitNumber": 1,
                        "startAngle": 180,
                        "endAngle": 0,
                        "center": ["50%", "90%"],
                        "radius": vradius,
                        "axisLine": {
                            "lineStyle": {
                                "width": 0,
                                "color": [
                                    [1, {
                                            "x": "0.00",
                                            "y": "0.00",
                                            "x2": "1.00",
                                            "y2": "1.00",
                                            "type": "linear",
                                            "global": false,
                                            "colorStops": [{
                                                    "offset": 0,
                                                    "color": "rgba(0,0,0,1)"
                                                }, {
                                                    "offset": 1,
                                                    "color": "rgba(202,95,95,1)"
                                                }, {
                                                    "offset": 0.3579,
                                                    "color": "rgba(34,72,61,1)"
                                                }, {
                                                    "offset": 0.6895,
                                                    "color": "rgba(39,175,88,1)"
                                                }, {
                                                    "offset": 0.1211,
                                                    "color": "rgba(52,225,41,1)"
                                                }, {
                                                    "offset": 0.8105,
                                                    "color": "rgba(218,190,35,1)"
                                                }]
                                        }]
                                ]
                            }
                        },
                        "pointer": {
                            "show": false
                        },
                        "axisTick": {
                            "show": false
                        },
                        "axisLabel": {
                            "show": false
                        },
                        "splitLine": {
                            "length": 25.6,
                            "lineStyle": {
                                "width": 2,
                                "color": {
                                    "x": "0.00",
                                    "y": "0.00",
                                    "x2": "1.00",
                                    "y2": "1.00",
                                    "type": "linear",
                                    "global": false,
                                    "colorStops": [{
                                            "offset": 0,
                                            "color": "rgba(0,0,0,1)"
                                        }, {
                                            "offset": 1,
                                            "color": "rgba(202,95,95,1)"
                                        }, {
                                            "offset": 0.3579,
                                            "color": "rgba(34,72,61,1)"
                                        }, {
                                            "offset": 0.6895,
                                            "color": "rgba(39,175,88,1)"
                                        }, {
                                            "offset": 0.1211,
                                            "color": "rgba(52,225,41,1)"
                                        }, {
                                            "offset": 0.8105,
                                            "color": "rgba(218,190,35,1)"
                                        }]
                                }
                            }
                        },
                        "detail": {
                            "show": true
                        }
                    }],
                "grid": {
                    "top": 16,
                    "left": 16,
                    "right": 16,
                    "bottom": 16,
                    "containLabel": false
                }
            };


            thisObj.myChart.setOption(option);

        }

        this.makeGaugeOffline = function (vrunofflinenotfinished, vrunofflinefinished) {
            

            var vradius = '110%';

            var option = {
                backgroundColor: '#000',
                "title": {
                    "show": true,
                    "center": ["30%", "30%"],
                    textStyle: {color: '#fff', fontSize: 10},
                    text: "Offline Finished " + vrunofflinefinished + "\nOffline not Finished " + vrunofflinenotfinished
                },
                "series": [{
                        "title": {
                            "show": false

                        },
                        "data": [{
                                "value": vrunofflinefinished

                            }],

                        "name": "",
                        "type": "gauge",
                        "min": 0,
                        "max": thisObj.nrControllers,
                        "splitNumber": 10,
                        "startAngle": 180,
                        "endAngle": 0,
                        "center": ["50%", "90%"],
                        "radius": vradius,

                        "axisLabel": {
                            "distance": 0,
                            //"fontFamily": "Microsoft YaHei UI",
                            "fontSize": 10,
                            "fontWeight": "normal",
                            "fontStyle": "normal",
                            "color": "#FFFFFF"
                        },
                        "axisLine": {
                            "lineStyle": {
                                "width": 2,
                                "color": [
                                    [1, {
                                            "x": "0.00",
                                            "y": "0.00",
                                            "x2": "1.00",
                                            "y2": "1.00",
                                            "type": "linear",
                                            "global": false,
                                            "colorStops": [{
                                                    "offset": 0,
                                                    "color": "rgba(0,0,0,1)"
                                                }, {
                                                    "offset": 1,
                                                    "color": "rgba(202,95,95,1)"
                                                }, {
                                                    "offset": 0.3579,
                                                    "color": "rgba(34,72,61,1)"
                                                }, {
                                                    "offset": 0.6895,
                                                    "color": "rgba(39,175,88,1)"
                                                }, {
                                                    "offset": 0.1211,
                                                    "color": "rgba(52,225,41,1)"
                                                }, {
                                                    "offset": 0.8105,
                                                    "color": "rgba(218,190,35,1)"
                                                }]
                                        }]
                                ]
                            }
                        },
                        "pointer": {
                            "show": true,
                            width: 3
                        },
                        "itemStyle": {
                            "normal": {
                                "color": {
                                    "x": "0.00",
                                    "y": "0.00",
                                    "x2": "1.00",
                                    "y2": "1.00",
                                    "type": "linear",
                                    "global": false,
                                    "colorStops": [{
                                            "offset": 0,
                                            "color": "rgba(0,0,0,1)"
                                        }, {
                                            "offset": 1,
                                            "color": "rgba(202,95,95,1)"
                                        }, {
                                            "offset": 0.3579,
                                            "color": "rgba(34,72,61,1)"
                                        }, {
                                            "offset": 0.6895,
                                            "color": "rgba(224,229,37,1)"
                                        }]
                                },
                                "borderColor": {
                                    "x": "0.00",
                                    "y": "0.00",
                                    "x2": "1.00",
                                    "y2": "1.00",
                                    "type": "linear",
                                    "global": false,
                                    "colorStops": [{
                                            "offset": 0,
                                            "color": "rgba(0,0,0,1)"
                                        }, {
                                            "offset": 1,
                                            "color": "rgba(202,95,95,1)"
                                        }, {
                                            "offset": 0.3579,
                                            "color": "rgba(34,72,61,1)"
                                        }, {
                                            "offset": 0.6895,
                                            "color": "rgba(224,229,37,1)"
                                        }]
                                },
                                "borderWidth": 1
                            }
                        },
                        "axisTick": {
                            "length": 16,
                            "lineStyle": {
                                "width": 2,
                                "color": {
                                    "x": "0.00",
                                    "y": "0.00",
                                    "x2": "1.00",
                                    "y2": "1.00",
                                    "type": "linear",
                                    "global": false,
                                    "colorStops": [{
                                            "offset": 0,
                                            "color": "rgba(0,0,0,1)"
                                        }, {
                                            "offset": 1,
                                            "color": "rgba(202,95,95,1)"
                                        }, {
                                            "offset": 0.3579,
                                            "color": "rgba(34,72,61,1)"
                                        }, {
                                            "offset": 0.6895,
                                            "color": "rgba(224,229,37,1)"
                                        }]
                                }
                            }
                        },
                        "splitLine": {
                            "length": 32,
                            "lineStyle": {
                                "width": 4,
                                "color": {
                                    "x": "0.00",
                                    "y": "0.00",
                                    "x2": "1.00",
                                    "y2": "1.00",
                                    "type": "linear",
                                    "global": false,
                                    "colorStops": [{
                                            "offset": 0,
                                            "color": "rgba(0,0,0,1)"
                                        }, {
                                            "offset": 1,
                                            "color": "rgba(202,95,95,1)"
                                        }, {
                                            "offset": 0.3579,
                                            "color": "rgba(34,72,61,1)"
                                        }, {
                                            "offset": 0.6895,
                                            "color": "rgba(224,229,37,1)"
                                        }]
                                }
                            }
                        },
                        "detail": {
                            "show": false
                        }

                    }, {
                        "name": "Colorful",
                        "type": "gauge",
                        "min": 0,
                        "max": thisObj.nrControllers,
                        "splitNumber": 1,
                        "startAngle": 180,
                        "endAngle": 0,
                        "center": ["50%", "90%"],
                        "radius": vradius,
                        "axisLine": {
                            "lineStyle": {
                                "width": 10,
                                "color": [
                                    [vrunofflinenotfinished / 100, {
                                            "x": "0.00",
                                            "y": "0.00",
                                            "x2": "1.00",
                                            "y2": "1.00",
                                            "type": "linear",
                                            "global": false,
                                            "colorStops": [{
                                                    "offset": 0,
                                                    "color": "rgba(0,0,0,1)"
                                                }, {
                                                    "offset": 1,
                                                    "color": "rgba(202,95,95,1)"
                                                }, {
                                                    "offset": 0.3579,
                                                    "color": "rgba(34,72,61,1)"
                                                }, {
                                                    "offset": 0.6895,
                                                    "color": "rgba(224,229,37,1)"
                                                }]
                                        }],
                                    [1, "rgba(0,0,0,0)"]
                                ],
                                shadowColor: '#DFE127', //默认透明
                                shadowBlur: 10
                            }
                        },
                        "pointer": {
                            "show": false,
                            width: 3
                        },
                        "axisTick": {
                            "show": false
                        },
                        "axisLabel": {
                            "show": false
                        },
                        "splitLine": {
                            "show": false
                        },
                        "detail": {
                            "show": true
                        }
                    }, {
                        "title": {
                            "show": false
                        },
                        "name": "thinarc",
                        "type": "gauge",
                        "min": 0,
                        "max": thisObj.nrControllers,
                        "splitNumber": 1,
                        "startAngle": 180,
                        "endAngle": 0,
                        "center": ["50%", "90%"],
                        "radius": vradius,
                        "axisLine": {
                            "lineStyle": {
                                "width": 0,
                                "color": [
                                    [1, {
                                            "x": "0.00",
                                            "y": "0.00",
                                            "x2": "1.00",
                                            "y2": "1.00",
                                            "type": "linear",
                                            "global": false,
                                            "colorStops": [{
                                                    "offset": 0,
                                                    "color": "rgba(0,0,0,1)"
                                                }, {
                                                    "offset": 1,
                                                    "color": "rgba(202,95,95,1)"
                                                }, {
                                                    "offset": 0.3579,
                                                    "color": "rgba(34,72,61,1)"
                                                }, {
                                                    "offset": 0.6895,
                                                    "color": "rgba(39,175,88,1)"
                                                }, {
                                                    "offset": 0.1211,
                                                    "color": "rgba(52,225,41,1)"
                                                }, {
                                                    "offset": 0.8105,
                                                    "color": "rgba(218,190,35,1)"
                                                }]
                                        }]
                                ]
                            }
                        },
                        "pointer": {
                            "show": false
                        },
                        "axisTick": {
                            "show": false
                        },
                        "axisLabel": {
                            "show": false
                        },
                        "splitLine": {
                            "length": 25.6,
                            "lineStyle": {
                                "width": 2,
                                "color": {
                                    "x": "0.00",
                                    "y": "0.00",
                                    "x2": "1.00",
                                    "y2": "1.00",
                                    "type": "linear",
                                    "global": false,
                                    "colorStops": [{
                                            "offset": 0,
                                            "color": "rgba(0,0,0,1)"
                                        }, {
                                            "offset": 1,
                                            "color": "rgba(202,95,95,1)"
                                        }, {
                                            "offset": 0.3579,
                                            "color": "rgba(34,72,61,1)"
                                        }, {
                                            "offset": 0.6895,
                                            "color": "rgba(39,175,88,1)"
                                        }, {
                                            "offset": 0.1211,
                                            "color": "rgba(52,225,41,1)"
                                        }, {
                                            "offset": 0.8105,
                                            "color": "rgba(218,190,35,1)"
                                        }]
                                }
                            }
                        },
                        "detail": {
                            "show": true
                        }
                    }],
                "grid": {
                    "top": 16,
                    "left": 16,
                    "right": 16,
                    "bottom": 16,
                    "containLabel": false
                }
            };


            thisObj.myChartOffline.setOption(option);

        }




        this.makeGaugeAlarm = function (valarm, endandnotstarted) {

            var vradius = '110%';

            var option = {
                backgroundColor: '#000',
                "title": {
                    "show": true,
                    "center": ["30%", "30%"],
                    textStyle: {color: '#fff', fontSize: 10},
                    text: "Not Started or Program End " + endandnotstarted + "\nAlarm " + valarm
                },
                "series": [{
                        "title": {
                            "show": false

                        },
                        "data": [{
                                "value": valarm

                            }],

                        "name": "sdas",
                        "type": "gauge",
                        "min": 0,
                        "max": thisObj.nrControllers,
                        "splitNumber": 10,
                        "startAngle": 180,
                        "endAngle": 0,
                        "center": ["50%", "90%"],
                        "radius": vradius,

                        "axisLabel": {
                            "distance": 0,
                            //"fontFamily": "Microsoft YaHei UI",
                            "fontSize": 10,
                            "fontWeight": "normal",
                            "fontStyle": "normal",
                            "color": "#FFFFFF"
                        },
                        "axisLine": {
                            "lineStyle": {
                                "width": 2,
                                "color": [
                                    [1, {
                                            "x": "0.00",
                                            "y": "0.00",
                                            "x2": "1.00",
                                            "y2": "1.00",
                                            "type": "linear",
                                            "global": false,
                                            "colorStops": [{
                                                    "offset": 0,
                                                    "color": "rgba(0,0,0,1)"
                                                }, {
                                                    "offset": 1,
                                                    "color": "rgba(202,95,95,1)"
                                                }, {
                                                    "offset": 0.3579,
                                                    "color": "rgba(34,72,61,1)"
                                                }, {
                                                    "offset": 0.6895,
                                                    "color": "rgba(39,175,88,1)"
                                                }, {
                                                    "offset": 0.1211,
                                                    "color": "rgba(52,225,41,1)"
                                                }, {
                                                    "offset": 0.8105,
                                                    "color": "rgba(218,190,35,1)"
                                                }]
                                        }]
                                ]
                            }
                        },
                        "pointer": {
                            "show": true,
                            width: 3
                        },
                        "itemStyle": {
                            "normal": {
                                "color": {
                                    "x": "0.00",
                                    "y": "0.00",
                                    "x2": "1.00",
                                    "y2": "1.00",
                                    "type": "linear",
                                    "global": false,
                                    "colorStops": [{
                                            "offset": 0,
                                            "color": "rgba(0,0,0,1)"
                                        }, {
                                            "offset": 1,
                                            "color": "rgba(202,95,95,1)"
                                        }, {
                                            "offset": 0.3579,
                                            "color": "rgba(34,72,61,1)"
                                        }, {
                                            "offset": 0.6895,
                                            "color": "rgba(224,229,37,1)"
                                        }]
                                },
                                "borderColor": {
                                    "x": "0.00",
                                    "y": "0.00",
                                    "x2": "1.00",
                                    "y2": "1.00",
                                    "type": "linear",
                                    "global": false,
                                    "colorStops": [{
                                            "offset": 0,
                                            "color": "rgba(0,0,0,1)"
                                        }, {
                                            "offset": 1,
                                            "color": "rgba(202,95,95,1)"
                                        }, {
                                            "offset": 0.3579,
                                            "color": "rgba(34,72,61,1)"
                                        }, {
                                            "offset": 0.6895,
                                            "color": "rgba(224,229,37,1)"
                                        }]
                                },
                                "borderWidth": 1
                            }
                        },
                        "axisTick": {
                            "length": 16,
                            "lineStyle": {
                                "width": 2,
                                "color": {
                                    "x": "0.00",
                                    "y": "0.00",
                                    "x2": "1.00",
                                    "y2": "1.00",
                                    "type": "linear",
                                    "global": false,
                                    "colorStops": [{
                                            "offset": 0,
                                            "color": "rgba(0,0,0,1)"
                                        }, {
                                            "offset": 1,
                                            "color": "rgba(202,95,95,1)"
                                        }, {
                                            "offset": 0.3579,
                                            "color": "rgba(34,72,61,1)"
                                        }, {
                                            "offset": 0.6895,
                                            "color": "rgba(224,229,37,1)"
                                        }]
                                }
                            }
                        },
                        "splitLine": {
                            "length": 32,
                            "lineStyle": {
                                "width": 4,
                                "color": {
                                    "x": "0.00",
                                    "y": "0.00",
                                    "x2": "1.00",
                                    "y2": "1.00",
                                    "type": "linear",
                                    "global": false,
                                    "colorStops": [{
                                            "offset": 0,
                                            "color": "rgba(0,0,0,1)"
                                        }, {
                                            "offset": 1,
                                            "color": "rgba(202,95,95,1)"
                                        }, {
                                            "offset": 0.3579,
                                            "color": "rgba(34,72,61,1)"
                                        }, {
                                            "offset": 0.6895,
                                            "color": "rgba(224,229,37,1)"
                                        }]
                                }
                            }
                        },
                        "detail": {
                            "show": false
                        }

                    }, {
                        "name": "Colorful",
                        "type": "gauge",
                        "min": 0,
                        "max": thisObj.nrControllers,
                        "splitNumber": 1,
                        "startAngle": 180,
                        "endAngle": 0,
                        "center": ["50%", "90%"],
                        "radius": vradius,
                        "axisLine": {
                            "lineStyle": {
                                "width": 10,
                                "color": [
                                    [endandnotstarted / 100, {
                                            "x": "0.00",
                                            "y": "0.00",
                                            "x2": "1.00",
                                            "y2": "1.00",
                                            "type": "linear",
                                            "global": false,
                                            "colorStops": [{
                                                    "offset": 0,
                                                    "color": "rgba(0,0,0,1)"
                                                }, {
                                                    "offset": 1,
                                                    "color": "rgba(202,95,95,1)"
                                                }, {
                                                    "offset": 0.3579,
                                                    "color": "rgba(34,72,61,1)"
                                                }, {
                                                    "offset": 0.6895,
                                                    "color": "rgba(224,229,37,1)"
                                                }]
                                        }],
                                    [1, "rgba(0,0,0,0)"]
                                ],
                                shadowColor: '#DFE127', //默认透明
                                shadowBlur: 10
                            }
                        },
                        "pointer": {
                            "show": false,
                            width: 3
                        },
                        "axisTick": {
                            "show": false
                        },
                        "axisLabel": {
                            "show": false
                        },
                        "splitLine": {
                            "show": false
                        },
                        "detail": {
                            "show": true
                        }
                    }, {
                        "title": {
                            "show": false
                        },
                        "name": "thinarc",
                        "type": "gauge",
                        "min": 0,
                        "max": thisObj.nrControllers,
                        "splitNumber": 1,
                        "startAngle": 180,
                        "endAngle": 0,
                        "center": ["50%", "90%"],
                        "radius": vradius,
                        "axisLine": {
                            "lineStyle": {
                                "width": 0,
                                "color": [
                                    [1, {
                                            "x": "0.00",
                                            "y": "0.00",
                                            "x2": "1.00",
                                            "y2": "1.00",
                                            "type": "linear",
                                            "global": false,
                                            "colorStops": [{
                                                    "offset": 0,
                                                    "color": "rgba(0,0,0,1)"
                                                }, {
                                                    "offset": 1,
                                                    "color": "rgba(202,95,95,1)"
                                                }, {
                                                    "offset": 0.3579,
                                                    "color": "rgba(34,72,61,1)"
                                                }, {
                                                    "offset": 0.6895,
                                                    "color": "rgba(39,175,88,1)"
                                                }, {
                                                    "offset": 0.1211,
                                                    "color": "rgba(52,225,41,1)"
                                                }, {
                                                    "offset": 0.8105,
                                                    "color": "rgba(218,190,35,1)"
                                                }]
                                        }]
                                ]
                            }
                        },
                        "pointer": {
                            "show": false
                        },
                        "axisTick": {
                            "show": false
                        },
                        "axisLabel": {
                            "show": false
                        },
                        "splitLine": {
                            "length": 25.6,
                            "lineStyle": {
                                "width": 2,
                                "color": {
                                    "x": "0.00",
                                    "y": "0.00",
                                    "x2": "1.00",
                                    "y2": "1.00",
                                    "type": "linear",
                                    "global": false,
                                    "colorStops": [{
                                            "offset": 0,
                                            "color": "rgba(0,0,0,1)"
                                        }, {
                                            "offset": 1,
                                            "color": "rgba(202,95,95,1)"
                                        }, {
                                            "offset": 0.3579,
                                            "color": "rgba(34,72,61,1)"
                                        }, {
                                            "offset": 0.6895,
                                            "color": "rgba(39,175,88,1)"
                                        }, {
                                            "offset": 0.1211,
                                            "color": "rgba(52,225,41,1)"
                                        }, {
                                            "offset": 0.8105,
                                            "color": "rgba(218,190,35,1)"
                                        }]
                                }
                            }
                        },
                        "detail": {
                            "show": true
                        }
                    }],
                "grid": {
                    "top": 16,
                    "left": 16,
                    "right": 16,
                    "bottom": 16,
                    "containLabel": false
                }
            };


            thisObj.myChartAlarm.setOption(option);

        }
        // funcaoes gerais 

    }




// funcoes iniciais;
    dsMainObject.start();

// funcao da toolbar
    function onGridToolbarPressed(bPressed, dData) {
        dsMainObject.ToolBarClick(bPressed, dData);
    }

// insiro colunas;


    function setGrpGridHeight() {
        var hAvail = getWorkArea() - 50;
        var vhalf = Math.round(hAvail / 2) - 5;
        $("#myGrid").css("height", vhalf);
        $("#compgauge").css("height", Math.round(vhalf / 2));
        $("#compgaugealarm").css("height", Math.round(vhalf / 2));
        $("#compgaugeoffline").css("height", Math.round(vhalf / 2));
        $("#piedep").css("height", Math.round(vhalf / 2));
        w2ui[gridName].resize();
        dsMainObject.myChart.resize();
        dsMainObject.myChartAlarm.resize();
        dsMainObject.myChartOffline.resize();
        dsMainObject.myChartPieDep.resize();



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

</script>

<div class="row no-padding">
    <div class="col-md-6 graphArea"  style="padding-right: 3px"><div id="myGrid" style="height: 400px;width: 100%;color: black"></div></div>
</div>
<div class="row no-padding" style="padding-top: 2px !important;">
    <div class="col-md-2 graphArea" ><div style="width: 100%;" id="compgauge"></div></div>
    <div class="col-md-2 graphArea" ><div style="width: 100%" id="compgaugealarm"></div></div>
    <div class="col-md-2 graphArea" ><div style="width: 100%" id="compgaugeoffline"></div></div>
</div>

<div class="row no-padding" style="padding-top: 2px !important;">
    <div class="col-md-2 graphArea" ><div style="width: 100%;" id="piedep"></div></div>
    <div class="col-md-2"><div style="width: 100%" id=""></div></div>
    <div class="col-md-2"><div style="width: 100%" id=""></div></div>
</div>


