<?php //include_once APPPATH . 'views/viewIncludeFilter.php';                                      ?>
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

    .w2ui-col-group {
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

<?php echo ($javascript); ?>

//var controllerName = "country";



//$(".ds_hr_type").on( "change",  function() {



    var dsMainObject = new function () {


        // variaveis privadas;

        var thisObj = this;
        thisObj.gridName = gridName;
        thisObj.nrControllers = 0;

        thisObj.machineAgainsPC = [];
        thisObj.dataPlot = <?php echo($dataPlot) ?>;

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



            $('#dates').datepicker({
                autoclose: true,
                format: defaultDateFormat,
                todayBtn: "linked",
                clearBtn: true,
                todayHighlight: true,
                inputs: $('.daterange')
            });

            thisObj.gridName = gridNamePar;
            thisObj.selectedMachineOrSupplier = 'ALL';
            thisObj.supplierOrPC = 'P';
            $('.content-wrapper').addClass('dashBackground');
            $('.content-header').addClass('hidden');




            this.addListeners();
            this.addHelper();
            /*
             setTimeout(function () {
             }, 0);
             */


            thisObj.myChartPieDep = echarts.init(document.getElementById('piedep'));
            thisObj.myChartLaser = echarts.init(document.getElementById('lasergraph'));
            thisObj.myChartDotLaser = echarts.init(document.getElementById('dotlasergraph'));
            thisObj.myChartSupplier = echarts.init(document.getElementById('suppliergraph'));
            thisObj.myChartPlotLine = echarts.init(document.getElementById('plotlinegraph'));




            thisObj.myChartLaser.on('click', function (params) {
                thisObj.selectedMachineOrSupplier = params.name;
                thisObj.supplierOrPC = 'P';
                thisObj.getPlotData();
                thisObj.makeData(true);

            });


            thisObj.myChartDotLaser.on('click', function (params) {
                thisObj.selectedMachineOrSupplier = params.name;
                thisObj.supplierOrPC = 'P';
                thisObj.getPlotData();
                thisObj.makeData(true);
            });

            thisObj.myChartSupplier.on('click', function (params) {
                thisObj.selectedMachineOrSupplier = params.name;
                thisObj.supplierOrPC = 'S';
                thisObj.getPlotData();
                thisObj.makeData(true);
            });


            this.makeData(true);
            this.makePlotLine();

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

        this.makeData = function (refreshGraphs) {
            var vret = [];
            var vdata = [];
            var vdataLaserLT = [];
            var vdataLaserOPEN = [];
            var vdataLaserTitle = [];
            var vdataLaserTotalLine = [];
            var vDataDotLaserTotal = [];
            var vDataDotLaserTotalLabels = [];
            var vTotalAvgOpen = 0;
            var vTotalAvgLT = 0;
            var vTotalAvgTotal = 0;
            var vSupData = {};


            var vSTART_COSTTIME_AVG = 0;
            var vRESET_ADAPTER_COSTTIME_AVG = 0;
            var vOPEN_ACCESSRIGHT_COSTTIME_AVG = 0;
            var vREAD_FWVERSION_COSTTIME_AVG = 0;
            var vREAD_CELLIDENT_COSTTIME_AVG = 0;
            var vREAD_NUMBERSER_COSTTIME_AVG = 0;
            var vREAD_PARALLELC_COSTTIME_AVG = 0;
            var vREAD_OPERATION_COSTTIME_AVG = 0;
            var vWRITE_BOD_COSTTIME_AVG = 0;
            var vWRITE_USERPWD_COSTTIME_AVG = 0;
            var vWRITE_ADMIPWD_COSTTIME_AVG = 0;
            var vWRITE_SERVPWD_COSTTIME_AVG = 0;
            var vWRITE_ENCRYLO_COSTTIME_AVG = 0;
            var vWRITE_ENCRYHI_COSTTIME_AVG = 0;
            var vWRITE_MPBID_COSTTIME_AVG = 0;
            var vWRITE_METCPWD_COSTTIME_AVG = 0;
            var vCOMMAND_FINISH_COSTTIME_AVG = 0;
            var vGEN_SN_COSTTIME_AVG = 0;
            var vSAVE_DATA_COSTTIME_AVG = 0;
            var vLASER_COSTTIME_AVG = 0;
            var vpietotalmachine = 0;

            $('#selectedarea').text(thisObj.selectedMachineOrSupplier);


            $.each(w2ui[gridName].records, function (i, v) {

                if (vSupData[v.LASER_SUPPLIER_NAME] == undefined) {
                    vSupData[v.LASER_SUPPLIER_NAME] = {count: 1, avglaser: v.LASER_COSTTIME_AVG};
                } else {
                    vSupData[v.LASER_SUPPLIER_NAME] = {count: vSupData[v.LASER_SUPPLIER_NAME].count + 1, avglaser: vSupData[v.LASER_SUPPLIER_NAME].avglaser + v.LASER_COSTTIME_AVG};
                }

                if ((v.prod_location == thisObj.selectedMachineOrSupplier && thisObj.supplierOrPC == 'P') || (v.LASER_SUPPLIER_NAME == thisObj.selectedMachineOrSupplier && thisObj.supplierOrPC == 'S') || thisObj.selectedMachineOrSupplier == 'ALL') {

                    vSTART_COSTTIME_AVG = vSTART_COSTTIME_AVG + parseFloat(v.START_COSTTIME_AVG);
                    vRESET_ADAPTER_COSTTIME_AVG = vRESET_ADAPTER_COSTTIME_AVG + v.RESET_ADAPTER_COSTTIME_AVG;
                    vOPEN_ACCESSRIGHT_COSTTIME_AVG = vOPEN_ACCESSRIGHT_COSTTIME_AVG + v.OPEN_ACCESSRIGHT_COSTTIME_AVG;
                    vREAD_FWVERSION_COSTTIME_AVG = vREAD_FWVERSION_COSTTIME_AVG + v.READ_FWVERSION_COSTTIME_AVG;
                    vREAD_CELLIDENT_COSTTIME_AVG = vREAD_CELLIDENT_COSTTIME_AVG + v.READ_CELLIDENT_COSTTIME_AVG;
                    vREAD_NUMBERSER_COSTTIME_AVG = vREAD_NUMBERSER_COSTTIME_AVG + v.READ_NUMBERSER_COSTTIME_AVG;
                    vREAD_PARALLELC_COSTTIME_AVG = vREAD_PARALLELC_COSTTIME_AVG + v.READ_PARALLELC_COSTTIME_AVG;
                    vREAD_OPERATION_COSTTIME_AVG = vREAD_OPERATION_COSTTIME_AVG + v.READ_OPERATION_COSTTIME_AVG;
                    vWRITE_BOD_COSTTIME_AVG = vWRITE_BOD_COSTTIME_AVG + v.WRITE_BOD_COSTTIME_AVG;
                    vWRITE_USERPWD_COSTTIME_AVG = vWRITE_USERPWD_COSTTIME_AVG + v.WRITE_USERPWD_COSTTIME_AVG;
                    vWRITE_ADMIPWD_COSTTIME_AVG = vWRITE_ADMIPWD_COSTTIME_AVG + v.WRITE_ADMIPWD_COSTTIME_AVG;
                    vWRITE_SERVPWD_COSTTIME_AVG = vWRITE_SERVPWD_COSTTIME_AVG + v.WRITE_SERVPWD_COSTTIME_AVG;
                    vWRITE_ENCRYLO_COSTTIME_AVG = vWRITE_ENCRYLO_COSTTIME_AVG + v.WRITE_ENCRYLO_COSTTIME_AVG;
                    vWRITE_ENCRYHI_COSTTIME_AVG = vWRITE_ENCRYHI_COSTTIME_AVG + v.WRITE_ENCRYHI_COSTTIME_AVG;
                    vWRITE_MPBID_COSTTIME_AVG = vWRITE_MPBID_COSTTIME_AVG + v.WRITE_MPBID_COSTTIME_AVG;
                    vWRITE_METCPWD_COSTTIME_AVG = vWRITE_METCPWD_COSTTIME_AVG + v.WRITE_METCPWD_COSTTIME_AVG;
                    vCOMMAND_FINISH_COSTTIME_AVG = vCOMMAND_FINISH_COSTTIME_AVG + v.COMMAND_FINISH_COSTTIME_AVG;
                    vGEN_SN_COSTTIME_AVG = vGEN_SN_COSTTIME_AVG + v.GEN_SN_COSTTIME_AVG;
                    vSAVE_DATA_COSTTIME_AVG = vSAVE_DATA_COSTTIME_AVG + v.SAVE_DATA_COSTTIME_AVG;
                    vLASER_COSTTIME_AVG = vLASER_COSTTIME_AVG + v.LASER_COSTTIME_AVG;


                    vDataDotLaserTotal.push([vpietotalmachine + 1, v.LASER_COSTTIME_AVG, v.LASER_COSTTIME_AVG, v.prod_location, v.LASER_COSTTIME_AVG + ' - ' + v.prod_location]);
                    vDataDotLaserTotalLabels.push(v.prod_location);

                    vpietotalmachine++;
                }

                if (thisObj.supplierOrPC == 'P' || thisObj.selectedMachineOrSupplier == 'ALL' || (v.LASER_SUPPLIER_NAME == thisObj.selectedMachineOrSupplier && thisObj.supplierOrPC == 'S')) {

                    vdataLaserLT.push(v.LASER_COSTTIME_AVG);
                    vdataLaserOPEN.push(v.OPEN_ACCESSRIGHT_COSTTIME_AVG);
                    vdataLaserTitle.push(v.prod_location);

                    vTotalAvgOpen = vTotalAvgOpen + v.OPEN_ACCESSRIGHT_COSTTIME_AVG;
                    vTotalAvgLT = vTotalAvgLT + v.LASER_COSTTIME_AVG;
                    vTotalAvgTotal = vTotalAvgTotal + v.TOTAL_COSTTIME_AVG;

                    vdataLaserTotalLine.push(v.TOTAL_COSTTIME_AVG);
                }

            });

            vSTART_COSTTIME_AVG = (vSTART_COSTTIME_AVG / vpietotalmachine).toFixed(2);
            vRESET_ADAPTER_COSTTIME_AVG = (vRESET_ADAPTER_COSTTIME_AVG / vpietotalmachine).toFixed(2);
            vOPEN_ACCESSRIGHT_COSTTIME_AVG = (vOPEN_ACCESSRIGHT_COSTTIME_AVG / vpietotalmachine).toFixed(2);
            vREAD_FWVERSION_COSTTIME_AVG = (vREAD_FWVERSION_COSTTIME_AVG / vpietotalmachine).toFixed(2);
            vREAD_CELLIDENT_COSTTIME_AVG = (vREAD_CELLIDENT_COSTTIME_AVG / vpietotalmachine).toFixed(2);
            vREAD_NUMBERSER_COSTTIME_AVG = (vREAD_NUMBERSER_COSTTIME_AVG / vpietotalmachine).toFixed(2);
            vREAD_PARALLELC_COSTTIME_AVG = (vREAD_PARALLELC_COSTTIME_AVG / vpietotalmachine).toFixed(2);
            vREAD_OPERATION_COSTTIME_AVG = (vREAD_OPERATION_COSTTIME_AVG / vpietotalmachine).toFixed(2);
            vWRITE_BOD_COSTTIME_AVG = (vWRITE_BOD_COSTTIME_AVG / vpietotalmachine).toFixed(2);
            vWRITE_USERPWD_COSTTIME_AVG = (vWRITE_USERPWD_COSTTIME_AVG / vpietotalmachine).toFixed(2);
            vWRITE_ADMIPWD_COSTTIME_AVG = (vWRITE_ADMIPWD_COSTTIME_AVG / vpietotalmachine).toFixed(2);
            vWRITE_SERVPWD_COSTTIME_AVG = (vWRITE_SERVPWD_COSTTIME_AVG / vpietotalmachine).toFixed(2);
            vWRITE_ENCRYLO_COSTTIME_AVG = (vWRITE_ENCRYLO_COSTTIME_AVG / vpietotalmachine).toFixed(2);
            vWRITE_ENCRYHI_COSTTIME_AVG = (vWRITE_ENCRYHI_COSTTIME_AVG / vpietotalmachine).toFixed(2);
            vWRITE_MPBID_COSTTIME_AVG = (vWRITE_MPBID_COSTTIME_AVG / vpietotalmachine).toFixed(2);
            vWRITE_METCPWD_COSTTIME_AVG = (vWRITE_METCPWD_COSTTIME_AVG / vpietotalmachine).toFixed(2);
            vCOMMAND_FINISH_COSTTIME_AVG = (vCOMMAND_FINISH_COSTTIME_AVG / vpietotalmachine).toFixed(2);
            vGEN_SN_COSTTIME_AVG = (vGEN_SN_COSTTIME_AVG / vpietotalmachine).toFixed(2);
            vSAVE_DATA_COSTTIME_AVG = (vSAVE_DATA_COSTTIME_AVG / vpietotalmachine).toFixed(2);
            vLASER_COSTTIME_AVG = (vLASER_COSTTIME_AVG / vpietotalmachine).toFixed(2);

            vdata.push({value: vSTART_COSTTIME_AVG, name: 'Start '});
            vdata.push({value: vRESET_ADAPTER_COSTTIME_AVG, name: 'Reset Adapter '});
            vdata.push({value: vOPEN_ACCESSRIGHT_COSTTIME_AVG, name: 'Open Access '});
            vdata.push({value: vREAD_FWVERSION_COSTTIME_AVG, name: 'Read Firmware '});
            vdata.push({value: vREAD_CELLIDENT_COSTTIME_AVG, name: 'Cell Ident '});
            vdata.push({value: vREAD_NUMBERSER_COSTTIME_AVG, name: 'Read Number '});
            vdata.push({value: vREAD_PARALLELC_COSTTIME_AVG, name: 'Read Parallel '});
            vdata.push({value: vREAD_OPERATION_COSTTIME_AVG, name: 'Read Operation '});
            vdata.push({value: vWRITE_BOD_COSTTIME_AVG, name: 'Write Bod '});
            vdata.push({value: vWRITE_USERPWD_COSTTIME_AVG, name: 'User Pwd '});
            vdata.push({value: vWRITE_ADMIPWD_COSTTIME_AVG, name: 'Admin Pwd '});

            vdata.push({value: vWRITE_SERVPWD_COSTTIME_AVG, name: 'Serv '});
            vdata.push({value: vWRITE_ENCRYLO_COSTTIME_AVG, name: 'Encry LO '});
            vdata.push({value: vWRITE_ENCRYHI_COSTTIME_AVG, name: 'Encry HI '});
            vdata.push({value: vWRITE_MPBID_COSTTIME_AVG, name: 'MPBID '});
            vdata.push({value: vWRITE_METCPWD_COSTTIME_AVG, name: 'MET PWD '});
            vdata.push({value: vCOMMAND_FINISH_COSTTIME_AVG, name: 'Command Finish '});
            vdata.push({value: vGEN_SN_COSTTIME_AVG, name: 'Gen SN '});
            vdata.push({value: vSAVE_DATA_COSTTIME_AVG, name: 'Save Data '});
            vdata.push({value: vLASER_COSTTIME_AVG, name: 'Laser '});

            vdataLaserLT.unshift((vTotalAvgLT / vdataLaserTotalLine.length).toFixed(2));
            vdataLaserOPEN.unshift((vTotalAvgOpen / vdataLaserTotalLine.length).toFixed(2));
            vdataLaserTitle.unshift('ALL');



            vdataLaserTotalLine.unshift((vTotalAvgTotal / vdataLaserTotalLine.length).toFixed(2));


            // pie
            thisObj.dataPieNow = vdata;
            // laser graph bar/line
            thisObj.dataLaserGraph = {bar1: vdataLaserLT, bar2: vdataLaserOPEN, title: vdataLaserTitle, lineTotal: vdataLaserTotalLine, bar1title: 'Laser Cost', bar2title: 'Open Access Right'};
            // laser graph scatter
            thisObj.dataDotLaserGraph = {};
            vDataDotLaserTotal.unshift([0, vLASER_COSTTIME_AVG, vLASER_COSTTIME_AVG, 'ALL', 'ALL']);
            vDataDotLaserTotalLabels.unshift('ALL');

            thisObj.dataDotLaserGraph.data = vDataDotLaserTotal;
            thisObj.dataDotLaserGraph.title = vDataDotLaserTotalLabels;

            // supplier
            thisObj.dataSupCount = [];
            thisObj.dataSupTitle = [];
            thisObj.dataSupAvg = [];


            var vc = 0;
            var vt = 0;
            $.each(vSupData, function (i, v) {
                vt = vt + v.avglaser;
                vc = vc + v.count;

                thisObj.dataSupCount.push(v.count);
                thisObj.dataSupAvg.push((v.avglaser / v.count).toFixed(2));
                thisObj.dataSupTitle.push(i);
            });

            thisObj.dataSupCount.unshift(vc);
            thisObj.dataSupAvg.unshift((vt / vc).toFixed(2));
            thisObj.dataSupTitle.unshift('ALL');


            if (refreshGraphs) {
                thisObj.makePieDep();
                thisObj.makeLaserTime();
                thisObj.makeDotLaserTime();
                thisObj.makeSupplierGraph();

            }

        }

        this.makePlotLine = function () {
            var data = [];
            var dataline = [];

            $.each(thisObj.dataPlot, function (i, v) {

                data.push([i, v.TOTAL_COSTTIME_AVG, v.ds_legend]);
                if (v.TOTAL_COSTTIME_AVG > 0) {
                    dataline.push([i, v.TOTAL_COSTTIME_AVG]);
                }



            });


            var myRegression = ecStat.regression('linear', dataline);

            myRegression.points.sort(function (a, b) {
                return a[0] - b[0];
            });


            var voption = {
                tooltip: {
                    trigger: 'axis',
                    axisPointer: {
                        type: 'cross'
                    },
                    formatter: function (param) {
                        var vd = param[0].data;
                        return vd[2] + '<BR>' + vd[1];
                    }
                },
                xAxis: {
                    type: 'value',
                    splitLine: {
                        lineStyle: {
                            type: 'dashed'
                        }
                    },
                    axisLabel: {
                        textStyle: {
                            color: '#fff'
                        }
                    }
                },
                yAxis: {
                    type: 'value',
                    min: 0,

                    splitLine: {
                        lineStyle: {
                            type: 'dashed'
                        }
                    },

                    axisLabel: {
                        textStyle: {
                            color: '#fff'
                        }
                    }

                },
                series: [{
                        name: 'scatter',
                        type: 'scatter',
                        symbolSize: 6,
                        label: {
                            emphasis: {
                                show: true,
                                position: 'left',
                                textStyle: {
                                    color: 'white',
                                    fontSize: 16
                                }
                            }
                        },
                        data: data
                    },
                    {
                        name: 'line',
                        type: 'line',
                        showSymbol: false,
                        data: myRegression.points,

                        itemStyle: {
                            normal: {
                                color: 'rgb(255, 255, 255)',

                            }
                        },
                        markPoint: {
                            itemStyle: {
                                normal: {
                                    color: 'transparent'
                                }
                            }, /*
                             label: {
                             normal: {
                             show: true,
                             position: 'left',
                             formatter: myRegression.expression,
                             textStyle: {
                             color: '#333',
                             fontSize: 14
                             }
                             }
                             },*/
                            data: [{
                                    coord: myRegression.points[myRegression.points.length - 1]
                                }]
                        }
                    }]
            };
            thisObj.myChartPlotLine.setOption(voption, {notMerge: true});


        }


        this.makeSupplierGraph = function () {
            //

            var voption = {
                textStyle: {
                    color: '#ffffff'
                },
                "tooltip": {
                    "trigger": "axis",
                    "axisPointer": {
                        "type": "shadow",
                        textStyle: {
                            color: "#fff"
                        }

                    },
                },
                "grid": {
                    "borderWidth": 0,
                    "top": 70,
                    "bottom": 80,
                    textStyle: {
                        color: "#fff"
                    }
                },
                "legend": {
                    x: '4%',
                    top: '11%',
                    textStyle: {
                        color: '#90979c',
                    },
                    "data": ["Laser Cost", 'Machine Count', 'x']
                },

                "calculable": true,
                "xAxis": [{
                        "type": "category",
                        "axisLine": {
                            lineStyle: {
                                color: '#90979c'
                            }
                        },
                        "splitLine": {
                            "show": false
                        },
                        "axisTick": {
                            "show": false
                        },
                        "splitArea": {
                            "show": true
                        },
                        "axisLabel": {
                            "interval": 0,
                            "rotate": 15

                        },
                        "data": thisObj.dataSupTitle,
                    }],
                "yAxis": [{
                        "type": "value",
                        "splitLine": {
                            "show": false
                        },
                        "axisLine": {
                            lineStyle: {
                                color: '#90979c'
                            }
                        },
                        "axisTick": {
                            "show": false
                        },
                        "axisLabel": {
                            "interval": 0


                        },
                        "splitArea": {
                            "show": false
                        },

                    }],
                "series": [{
                        "name": 'Laser Time',
                        "type": "bar",
                        "stack": "总量",
                        "barMaxWidth": 50,
                        "barGap": "10%",
                        "itemStyle": {
                            "normal": {
                                "color": "rgba(255,144,128,1)",
                                "label": {
                                    "show": true,
                                    "textStyle": {
                                        "color": "#fff",
                                        fontSize: 10
                                    },
                                    "position": "insideTop",
                                    formatter: function (p) {
                                        return p.value > 0 ? (p.value) : '';
                                    }
                                }
                            }
                        },
                        "data": thisObj.dataSupAvg
                    },

                    {
                        "name": 'Count',
                        "type": "bar",
                        "stack": "总量",
                        "itemStyle": {
                            "normal": {
                                "color": "rgba(0,191,183,1)",
                                "barBorderRadius": 0,
                                "label": {
                                    "show": true,
                                    "position": "top",

                                    formatter: function (p) {
                                        return p.value > 0 ? (p.value) : '';
                                    }
                                }
                            }
                        },
                        "data": thisObj.dataSupCount
                    }
                ]
            };





            thisObj.myChartSupplier.setOption(voption, {notMerge: true});




        }

        this.makePieDep = function () {
            var vdata = thisObj.dataPieNow;


            var voption = {
                tooltip: {
                    trigger: 'item',
                    formatter: "{b}: {c} ({d}%)",
                    textStyle: {
                        color: '#ffffff',
                        fontSize: 12
                    }
                },
                title: {
                    //backgroundColor: '#fff',
                    show: true,
                    text: 'Steps',
                    left: '10%',
                    top: '6%',
                    textStyle: {
                        color: '#ffffff'
                    },

                }, /*
                 legend: {
                 orient: 'vertical',
                 left: 'left',
                 top: 'center',
                 data: ['服装鞋包', '家用电器', '居家生活', '美食厨房', '美妆洗护', '母婴用品', '其他', '手机数码', '书籍', '运动户外']
                 }*/
                series: [
                    {
                        name: 'OneKey',
                        type: 'pie',
                        radius: '75%',
                        center: ['50%', '50%'],
                        data: vdata,
                        itemStyle: {
                            emphasis: {
                                shadowBlur: 10,
                                shadowOffsetX: 0,
                                shadowColor: 'rgba(0, 0, 0, 0.5)'
                            }
                        }
                    }
                ]
            };
            thisObj.myChartPieDep.setOption(voption, {notMerge: true});



        }

        this.makeDotLaserTime = function () {
            var vdata = thisObj.dataDotLaserGraph.data;
            var vtitle = thisObj.dataDotLaserGraph.title;


            var voption = {
                //backgroundColor: new echarts.graphic.RadialGradient(0.3, 0.3, 0.8, [{offset: 0, color: '#000'}, {offset: 1, color: '#000'
                //  }]),
                title: {
                    text: 'Laser Time',
                    textStyle: {
                        color: '#ffffff'
                    }
                },

                textStyle: {
                    color: '#ffffff'
                },
                /*legend: {
                 right: 10,
                 data: ['Laser Time']
                 },*/
                xAxis: {

                    splitLine: {
                        lineStyle: {
                            type: 'dashed',
                            color: '#FFF'
                        }
                    },

                    "axisLabel": {
                        "interval": 0,
                        "rotate": 45
                    },
                    "data": vtitle,
                    scale: false

                }
                ,
                yAxis: {
                    splitLine: {
                        lineStyle: {
                            type: 'dashed',
                            color: '#FFF'
                        }
                    },

                    scale: true
                },
                series: [{
                        name: 'Not laser dot',
                        data: vdata,
                        type: 'scatter',
                        symbolSize: function (data) {
                            return 10;
                        },
                        label: {
                            emphasis: {
                                show: true,

                                position: 'top'
                            }
                        },

                        itemStyle: {
                            normal: {
                                shadowBlur: 10,
                                shadowColor: 'rgba(120, 36, 50, 0.5)',
                                shadowOffsetY: 5,
                                color: new echarts.graphic.RadialGradient(0.4, 0.3, 1, [{
                                        offset: 0,
                                        color: 'rgb(251, 118, 123)'
                                    }, {
                                        offset: 1,
                                        color: 'rgb(204, 46, 72)'
                                    }])
                            }
                        }
                    },
                ]
            };

            thisObj.myChartDotLaser.setOption(voption, {notMerge: true});



        }


        this.makeLaserTime = function () {
            var vdata = thisObj.dataLaserGraph;
            //thisObj.dataLaserGraph = {bar1: vdataLaserLT, bar2:vdataLaserOPEN, title: vdataLaserTitle};

            var voption = {
                textStyle: {
                    color: '#ffffff'
                },
                "tooltip": {
                    "trigger": "axis",
                    "axisPointer": {
                        "type": "shadow",
                        textStyle: {
                            color: "#fff"
                        }

                    },
                },
                "grid": {
                    "borderWidth": 0,
                    "top": 70,
                    "bottom": 80,
                    textStyle: {
                        color: "#fff"
                    }
                },
                "legend": {
                    x: '4%',
                    top: '11%',
                    textStyle: {
                        color: '#90979c',
                    },
                    "data": [vdata.bar1title, vdata.bar2title, 'Total']
                },

                "calculable": true,
                "xAxis": [{
                        "type": "category",
                        "axisLine": {
                            lineStyle: {
                                color: '#90979c'
                            }
                        },
                        "splitLine": {
                            "show": false
                        },
                        "axisTick": {
                            "show": false
                        },
                        "splitArea": {
                            "show": true
                        },
                        "axisLabel": {
                            "interval": 0,
                            "rotate": 45

                        },
                        "data": vdata.title,
                    }],
                "yAxis": [{
                        "type": "value",
                        "splitLine": {
                            "show": false
                        },
                        "axisLine": {
                            lineStyle: {
                                color: '#90979c'
                            }
                        },
                        "axisTick": {
                            "show": false
                        },
                        "axisLabel": {
                            "interval": 0


                        },
                        "splitArea": {
                            "show": false
                        },

                    }],
                "dataZoom": [{
                        "show": true,
                        "height": 20,
                        "xAxisIndex": [
                            0
                        ],
                        bottom: 10,
                        "start": 0,
                        "end": 100,
                        handleIcon: 'path://M306.1,413c0,2.2-1.8,4-4,4h-59.8c-2.2,0-4-1.8-4-4V200.8c0-2.2,1.8-4,4-4h59.8c2.2,0,4,1.8,4,4V413z',
                        handleSize: '110%',
                        handleStyle: {
                            color: "#d3dee5",

                        },
                        textStyle: {
                            color: "#fff"},
                        borderColor: "#90979c"


                    }, {
                        "type": "inside",
                        "show": true,
                        "height": 15,
                        "start": 1,
                        "end": 35
                    }],
                "series": [{
                        "name": vdata.bar1title,
                        "type": "bar",
                        "stack": "总量",
                        "barMaxWidth": 50,
                        "barGap": "10%",
                        "itemStyle": {
                            "normal": {
                                "color": "rgba(255,144,128,1)",
                                "label": {
                                    "show": true,
                                    "textStyle": {
                                        "color": "#fff",
                                        fontSize: 10
                                    },
                                    "position": "insideTop",
                                    formatter: function (p) {
                                        return p.value > 0 ? (p.value) : '';
                                    }
                                }
                            }
                        },
                        "data": vdata.bar1
                    },

                    {
                        "name": vdata.bar2title,
                        "type": "bar",
                        "stack": "总量",
                        "itemStyle": {
                            "normal": {
                                "color": "rgba(0,191,183,1)",
                                "barBorderRadius": 0,
                                "label": {
                                    "show": true,
                                    "position": "top",

                                    formatter: function (p) {
                                        return p.value > 0 ? (p.value) : '';
                                    }
                                }
                            }
                        },
                        "data": vdata.bar2
                    }, {
                        "name": "Total",
                        "type": "line",
                        "stack": "总量",
                        symbolSize: 10,
                        symbol: 'circle',
                        "itemStyle": {
                            "normal": {
                                "color": "rgba(252,230,48,1)",
                                "barBorderRadius": 0,

                                "label": {
                                    "show": true,
                                    "position": "top",
                                    "textStyle": {
                                        fontSize: 10
                                    },

                                    formatter: function (p) {
                                        return p.value > 0 ? (p.value) : '';
                                    }
                                }
                            }
                        },
                        "data": vdata.lineTotal
                    }
                ]
            };

            thisObj.myChartLaser.setOption(voption, {notMerge: true});


        }



        // funcaoes gerais 
        this.getAllData = function () {
            var vdt_start = $('#dt_start').val();
            var vdt_end = $('#dt_finish').val();
            if (chkUndefined(vdt_start, '') == '' || chkUndefined(vdt_end, '') == '') {
                messageBoxError('You must select a date range');
                return;
            }

            vdt_start = vdt_start.replace('/', '').replace('/', '').replace('/', '');
            vdt_end = vdt_end.replace('/', '').replace('/', '').replace('/', '');


            var site = 'dashboard/dashboard_onekey/getAllData/' + vdt_start + '/' + vdt_end + '/' + thisObj.supplierOrPC + '/' + thisObj.selectedMachineOrSupplier;
            $.myCgbAjax({url: encodeURI(site),
                message: javaMessages.retrieveData,
                box: '#content-body',
                data: [],
                success: function (a) {
                    w2ui[gridName].clear();
                    w2ui[gridName].add(a.gen);
                    thisObj.dataPlot = a.dotLine;
                    thisObj.makeData(true);
                    thisObj.makePlotLine();

                },
            });
        }

        this.getPlotData = function () {
            var vdt_start = $('#dt_start').val();
            var vdt_end = $('#dt_finish').val();
            if (chkUndefined(vdt_start, '') == '' || chkUndefined(vdt_end, '') == '') {
                messageBoxError('You must select a date range');
                return;
            }

            vdt_start = vdt_start.replace('/', '').replace('/', '').replace('/', '');
            vdt_end = vdt_end.replace('/', '').replace('/', '').replace('/', '');


            var site = 'dashboard/dashboard_onekey/getPlotData/' + vdt_start + '/' + vdt_end + '/' + thisObj.supplierOrPC + '/' + thisObj.selectedMachineOrSupplier;
            $.myCgbAjax({url: encodeURI(site),
                message: javaMessages.retrieveData,
                box: '#plotlinegraph',
                data: [],
                success: function (a) {
                    thisObj.dataPlot = a.dotLine;
                    thisObj.makePlotLine();

                },
            });
        }


    }




// funcoes iniciais;
    dsMainObject.start(gridName);

// funcao da toolbar
    function onGridToolbarPressed(bPressed, dData) {
        dsMainObject.ToolBarClick(bPressed, dData);
    }

// insiro colunas;


    function setGrpGridHeight() {
        var hAvail = getWorkArea() - 50;
        /*var vwidth = $('#content-body').width();
         var vheight = $(window).height();
         
         var vaddon = 0;
         if (vwidth < 1170 || vheight < 500) {
         var vhalf = 300;
         } else {
         
         }*/
        var vhalf = (Math.round((hAvail - 130) / 2) - 10);
        if (vhalf < 250) {
            vhalf = 300;
        }

        //console.log(vwidth, vheight);


        $("#myGrid").css("height", 180);
        $("#piedep").css("height", Math.round(vhalf));
        $("#lasergraph").css("height", Math.round(vhalf));
        $("#dotlasergraph").css("height", Math.round(vhalf));
        $("#suppliergraph").css("height", Math.round(vhalf - 50));
        $("#plotlinegraph").css("height", Math.round(vhalf - 50));



        dsMainObject.myChartPieDep.resize();
        dsMainObject.myChartLaser.resize();
        dsMainObject.myChartDotLaser.resize();
        dsMainObject.myChartSupplier.resize();
        dsMainObject.myChartPlotLine.resize();


        w2ui[gridName].resize();




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
<div class="row">
    <div class="col-lg-2 col-md-4  col-sm-6 col-xs-6 graphArea" >
        <div class="input-group " id="dates">
            <input type="text" class="form-control input-sm daterange" id="dt_start" style="text-align: center;"  value="<?php echo($startdate); ?>">
            <div class="input-group-addon input-group-sm " style="padding: 5px;"> - </div>
            <input type="text" class="form-control input-sm daterange" id="dt_finish" style="text-align: center;" value="<?php echo($enddate); ?>">
            <span class='input-group-btn' > <button class="btn btn-addon btn-default calcButton" onclick="dsMainObject.getAllData();
                    return false;"><i class='fa fa-refresh' style="padding-top: 4px"></i> </button></span>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6 col-xs-6" style="font-size: 18px; font-weight: bold" id="selectedarea">

    </div>

</div>

<div class="row">
    <div class="col-lg-4 col-md-6 col-sm-12 graphArea" >
        <div style="width: 100%;height: 600px;" id="piedep"></div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12 graphArea" >
        <div style="width: 100%;height: 600px;" id="lasergraph"></div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12 graphArea" >
        <div style="width: 100%;height: 600px;" id="dotlasergraph"></div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12 graphArea" >
        <div style="width: 100%;height: 600px;" id="suppliergraph"></div>
    </div>

    <div class="col-lg-8 col-md-12 col-sm-12 graphArea" >
        <div style="width: 100%;height: 600px;" id="plotlinegraph"></div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 graphArea" >
        <div id="myGrid" style="height: 800px;width: 100%;color: black"></div>
    </div>
</div>



