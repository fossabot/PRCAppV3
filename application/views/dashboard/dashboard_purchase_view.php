<?php ?>
<style>
    .dashBackground {
        background-color: rgb(33, 31, 36) !important;
        color: white;
    }

    .w2ui-grid tr {
        background-color: black !important;
        color: white !important;
    }

    .w2ui-head-last > div {
        background-color: black !important;
        color: white;
    }

    .graphArea {
        padding-left: 2px;
        padding-right: 2px;
    }
</style>

<script>


<?php echo($javascript); ?>




    var dsMainObject = new function () {


        // variaveis privadas;

        var thisObj = this;
        // thisObj.gridName = gridName;
        thisObj.nrControllers = 0;

        thisObj.machineAgainsPC = [];
        thisObj.dataSupplierPurchaseTimesByYear = <?php echo($dataSupplierPurchaseTimesByYear) ?>;
        thisObj.dataTst3TeamSpend = <?php echo($dataTst3TeamSpend) ?>;
        thisObj.dataTestDepartmentSpend = <?php echo($dataTestDepartmentSpend) ?>;
        thisObj.dataSpendByCostCenterAccountCode = <?php echo($dataSpendByCostCenterAccountCode) ?>;
        thisObj.dataTstMemberSpend = <?php echo($dataTstMemberSpend) ?>;


        // funcao de inicio;
        this.start = function () {

            $('#dd_year').on('change', function () {
                thisObj.getAllData();
            }).select2();

            $('#dd_Language').select2();
            $('#dd_costCenter').select2();

            $('#dd_accountCode').select2();


            thisObj.selectedMachineOrSupplier = 'ALL';
            thisObj.supplierOrPC = 'P';
            $('.content-wrapper').addClass('dashBackground');
            $('.content-header').addClass('hidden');


            this.addListeners();
            this.addHelper();


            thisObj.myChartPiePurchaseTimes = echarts.init(document.getElementById('piePurchaseTimes'));
            thisObj.myChartPieTst3TeamSpend = echarts.init(document.getElementById('pieTst3TeamSpend'));
            thisObj.myChartBarTestTeamSpending = echarts.init(document.getElementById('barTestTeamSpending'));
            thisObj.mylineSpendByCostCenterAccountCode = echarts.init(document.getElementById('lineSpendByCostCenterAccountCode'));
            thisObj.myBarTstMemberSpend = echarts.init(document.getElementById('barTstMemberSpend'));


            this.makeData(true);

            $('#purArea').cgbMakeScrollbar({autoWrapContent: false, alwaysShowScrollbar: 0, theme: 'white'});

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
            $('.content-wrapper').removeClass('dashBackground');
            $('.content-header').removeClass('hidden');

            return true;
        }


        this.makeBarTstMemberSpend = function () {

            var vyear = $('#dd_year').val();
            var xdata = [];
            var ydata = [];

            var CostCenter = $('#dd_costCenter').val();

            var vlegend = [];
            $.each(CostCenter, function (i, v) {
                vlegend.push(v.toString());

            });

            $.each(thisObj.dataTstMemberSpend, function (i, v) {

                // console.log(v.requester);
                if (xdata.indexOf(v.requester) == -1) {
                    xdata.push(v.requester);
                }
                ydata.push(v.total_price);
            });


            function getSeries(data, dataLegend) {
                var serie = [];
                for (var i = 0; i < dataLegend.length; i++) {

                    var item = {
                        name: dataLegend[i],
                        type: 'bar',
                        stack: 'total cost',
                        label: {
                            normal: {
                                show: true,
                                position: 'insideRight',
                                formatter: function (params) {
                                    return w2utils.formatNumber(params.data);
                                }
                            }
                        },
                        data: getSeriesData(data, dataLegend[i].toString())
                    }
                    serie.push(item);
                }
                return serie;
            }


            function getSeriesData(data, legend) {

                currentRequester = 0;
                indexRequester = 0;
                var ydata = [];
                var vydata = [];

                $.each(data, function (i, v) {

                    if (legend == v.ds_department_cost_center_code) {
                        indexRequester = xdata.indexOf(v.requester);
                        while (currentRequester < indexRequester) {
                            ydata.push('');
                            currentRequester++;
                        }
                        ydata.push(parseInt(v.total_price));
                        currentRequester++;
                    }
                });
                return ydata;
            }


            var option = {

                tooltip: {
                    trigger: 'axis',
                    axisPointer: {// 坐标轴指示器，坐标轴触发有效
                        type: 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
                    }
                },
                axisLabel: {
                    textStyle: {
                        color: '#fff'
                    }
                },
                title: {
                    //backgroundColor: '#fff',
                    show: true,
                    text: 'YTD ' + vyear + ' TST Member Spending $(Except for CAR Items)',
                    left: '33%',
                    top: '90%',
                    textStyle: {
                        color: '#ffffff'
                    },

                },
                grid: {
                    top: '10%',
                    left: '3%',
                    right: '4%',
                    bottom: '20%',
                    containLabel: true
                },
                legend: {
                    data: vlegend,
                    top: '5%',
                    textStyle: {
                        color: '#ffffff'
                    },
                },
                xAxis: [{
                        axisLabel: {
                            interval: 0,
                            rotate: 40
                        },
                        type: 'category',
                        left: '10%',
                        data: xdata,

                    }],
                yAxis: {
                    type: 'value'
                },
                series: getSeries(thisObj.dataTstMemberSpend, vlegend)
            };
            thisObj.myBarTstMemberSpend.setOption(option, {notMerge: true});
        }


        this.makeLineSpendByCostCenterAccountCode = function () {


            var xdata = [];
            var ydata = [];

            var CostCenter = $('#dd_costCenter').val();
            var AccountCode = $('#dd_accountCode').val();


            var vcost = CostCenter.join(', ');
            currentMonth = 1;
            $.each(thisObj.dataSpendByCostCenterAccountCode, function (i, v) {
                while (currentMonth < v.nr_month) {
                    xdata.push('');
                    ydata.push('');
                    currentMonth++;
                }
                xdata.push(v.nr_month);
                ydata.push(parseInt(v.sum));
                currentMonth++;
            });


            var vyear = $('#dd_year').val();
            var option = {
                tooltip: {
                    trigger: 'axis',
                    axisPointer: {// 坐标轴指示器，坐标轴触发有效
                        type: 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
                    }
                },
                title: {
                    show: true,
                    text: 'YTD ' + vyear + ' ' + vcost + ' ' + AccountCode + ' TST Spending$',
                    left: '20%',
                    top: '90%',
                    textStyle: {
                        color: '#ffffff'
                    },

                },
                axisLabel: {
                    textStyle: {
                        color: '#fff'
                    }
                },
                grid: {
                    top: '10%',
                    left: '3%',
                    right: '4%',
                    bottom: '15%',
                    containLabel: true
                },
                xAxis: {
                    type: 'category',
                    data: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12']
                            // data: xdata
                },
                yAxis: {
                    type: 'value'
                },
                series: [
                    {
                        data: ydata,
                        type: 'line'
                    },
                            // {
                            //     data: ydataNew,
                            //     type: 'line'
                            // }
                ]


            };

            thisObj.mylineSpendByCostCenterAccountCode.setOption(option, {notMerge: true});
        }


        this.makePieSupplierPurchaseTimes = function () {

            var vyear = $('#dd_year').val();


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
                    text: 'YTD ' + vyear + ' Top 10 Supplier For TST Purchase Times',
                    left: '15%',
                    top: '90%',
                    textStyle: {
                        color: '#ffffff'
                    },

                },
                series: [
                    {
                        name: 'SupplierPurchaseTimes',
                        type: 'pie',
                        radius: '65%',
                        center: ['50%', '50%'],
                        label: {
                            normal: {
                                formatter: "{b}({d}%)",
                            },
                        },
                        data: this.dataSupplierPurchaseTimesByYear,
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
            thisObj.myChartPiePurchaseTimes.setOption(voption, {notMerge: true});
        }

        this.makePieTst3TeamSpend = function () {

            var vyear = $('#dd_year').val();

            // console.log('xxx', thisObj.dataTst3TeamSpend);

            var voption = {
                tooltip: {
                    trigger: 'item',
                    textStyle: {
                        color: '#ffffff',
                        fontSize: 12
                    }
                },
                title: {
                    //backgroundColor: '#fff',
                    show: true,
                    text: 'YTD ' + vyear + ' TST 3 Teams Spending $(Except for CAR Items)',
                    left: '13%',
                    top: '90%',
                    textStyle: {
                        color: '#ffffff'
                    },

                },

                series: [
                    {

                        type: 'pie',
                        radius: '65%',
                        center: ['50%', '50%'],

                        label: {
                            normal: {
                                show: true,
                                formatter: "{b}({d}%)",

                            },
                        },
                        data: thisObj.dataTst3TeamSpend,
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
            thisObj.myChartPieTst3TeamSpend.setOption(voption, {notMerge: true});
        }

        this.makeBarTestTeamSpending = function () {

            var vyear = $('#dd_year').val();
            var CostCenter = $('#dd_costCenter').val();
            var AccountCode = $('#dd_accountCode').val();

            var vcost = CostCenter.join(', ');
            var vlegend = [];
            $.each(CostCenter, function (i, v) {
                vlegend.push(v.toString());

            });


            function getSeries(data, dataLegend) {
                var serie = [];
                for (var i = 0; i < dataLegend.length; i++) {

                    var item = {
                        name: dataLegend[i],
                        type: 'bar',
                        stack: 'total',
                        label: {
                            normal: {
                                show: true,
                                position: 'insideRight',

                                formatter: function (params) {
                                    return w2utils.formatNumber(params.data);
                                }

                            }
                        },
                        data: getSeriesData(data, dataLegend[i].toString())
                    }
                    serie.push(item);
                }
                return serie;
            }


            function getSeriesData(data, legend) {

                currentMonth = 1;
                var ydata = [];
                var vydata = [];


                $.each(data, function (i, v) {

                    if (legend == v.ds_department_cost_center_code) {
                        while (currentMonth < v.nr_month) {
                            ydata.push('');
                            currentMonth++;
                        }
                        ydata.push(parseInt(v.sum));
                        currentMonth++;
                    }
                });
                return ydata;
            }


            option = {
                axisLabel: {
                    textStyle: {
                        color: '#fff'
                    }
                },
                tooltip: {
                    trigger: 'axis',
                    axisPointer: {// 坐标轴指示器，坐标轴触发有效
                        type: 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
                    }
                },
                title: {
                    //backgroundColor: '#fff',
                    show: true,
                    text: 'YTD ' + vyear + ' TST Spending $',
                    left: '35%',
                    top: '90%',
                    textStyle: {
                        color: '#ffffff'
                    },
                },

                legend: {
                    top: '5%',
                    data: vlegend,
                    textStyle: {
                        color: '#ffffff'
                    },
                },
                grid: {
                    top: '10%',
                    left: '3%',
                    right: '4%',
                    bottom: '15%',
                    containLabel: true
                },
                xAxis: {
                    type: 'category',
                    data: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12']
                },
                yAxis: {
                    type: 'value'

                },
                series: getSeries(thisObj.dataTestDepartmentSpend, vlegend)

            };
            thisObj.myChartBarTestTeamSpending.setOption(option, {notMerge: true});

        }

        this.getAllData = function () {

            var year = $('#dd_year').val();
            var CostCenter = $('#dd_costCenter').val();
            var AccountCode = $('#dd_accountCode').val();
            var language = $('#dd_Language').val();
            // console.log(CostCenter);

            var site = 'dashboard/dashboard_purchase/getAllDataByYear/' + year + '/' + AccountCode + '/' + language;
            $.myCgbAjax({
                url: encodeURI(site),
                // message: javaMessages.retrieveData,
                // box: '#content-body',
                data: {'cost': CostCenter},
                success: function (data) {
                    thisObj.dataSupplierPurchaseTimesByYear = data.dataSupplierPurchaseTimesByYear;
                    thisObj.dataSpendByCostCenterAccountCode = data.dataSpendByCostCenterAccountCode;
                    thisObj.dataTstMemberSpend = data.dataTstMemberSpend;
                    thisObj.dataTst3TeamSpend = data.dataTst3TeamSpend;
                    thisObj.dataTestDepartmentSpend = data.dataTestDepartmentSpend;

                    thisObj.makeData(true);


                },
            });
        }


        this.makeData = function (refreshGraphs) {


            thisObj.makePieSupplierPurchaseTimes();
            thisObj.makePieTst3TeamSpend();
            thisObj.makeBarTestTeamSpending();
            thisObj.makeLineSpendByCostCenterAccountCode();
            thisObj.makeBarTstMemberSpend();


        }
    }

    // funcoes iniciais;
    dsMainObject.start();


    // insiro colunas;


    function setGrpGridHeight() {

        dsMainObject.myChartPiePurchaseTimes.resize();
        dsMainObject.myChartPieTst3TeamSpend.resize();
        dsMainObject.myChartBarTestTeamSpending.resize();
        dsMainObject.mylineSpendByCostCenterAccountCode.resize();
        dsMainObject.myBarTstMemberSpend.resize();

        var hAvail = getWorkArea();
        var hFi = $('#purFilter').height();
        $('#purArea').cgbMakeScrollbar('resize', hAvail - hFi - 30);
        //$('#purArea').height(hAvail - hFi - 30 ) ;


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
<div id='purFilter'>

    <div class="row">
        <div class="col-lg-2 col-md-4  col-sm-6 col-xs-6 graphArea">
            <label>Year:</label>
            <select name="yearlist" form="yearform" style="width: 100%" id="dd_year">
                <option value="2017">2017</option>
                <option value="2018" selected>2018</option>
                <option value="2019">2019</option>
                <option value="2020">2020</option>
            </select>
        </div>

        <div class="col-md-2 graphArea">
            <label>Cost Center:</label>
            <select name="costCenterList" form="costCenterform" style="width: 100%" id="dd_costCenter" multiple>
                <option value="6513" selected>6513</option>
                <option value="6514">6514</option>
                <option value="6530">6530</option>
                <option value="6399">6399</option>
            </select>
        </div>

        <div class="col-lg-2 col-md-4  col-sm-6 col-xs-6 graphArea">
            <label>Account Code:</label>
            <select name="accountCodeList" form="accountCodeform" style="width: 100%" id="dd_accountCode">
                <option value="82660" selected>82660</option>
                <option value="82651">82651</option>
                <option value="83110">83110</option>
                <option value="83430">83430</option>
            </select>
        </div>

        <div class="col-lg-2 col-md-4  col-sm-6 col-xs-6 graphArea">
            <label>Language:</label>
            <select name="Languagelist" form="Languageform" style="width: 100%" id="dd_Language">
                <option value="CHN">Chinese</option>
                <option value="ENG" selected>English</option>

            </select>
        </div>

        <div class="col-lg-2 col-md-4  col-sm-6 col-xs-6 graphArea">
            <label>Refresh:</label>
            <span class='input-group-btn'> <button class="btn btn-addon btn-default calcButton" onclick="dsMainObject.getAllData();
                    return false;"><i class='fa fa-refresh' style="padding-top: 4px"></i> </button></span>

        </div>

        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-6" style="font-size: 18px; font-weight: bold" id="selectedarea">

        </div>

    </div>
</div>

<div id='purArea' style='overflow-y: auto; overflow-x: hidden; '>
    <div style='padding-right: 30px'>
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12 graphArea" style="border: 1px   dashed grey">
                <div style="width: 100%;height: 600px;" id="piePurchaseTimes"></div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 graphArea" style="border: 1px   dashed grey">
                <div style="width: 100%;height: 600px;" id="pieTst3TeamSpend"></div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 graphArea" style="border: 1px   dashed grey">
                <div style="width: 100%;height: 600px;" id="barTestTeamSpending"></div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 graphArea" style="border: 1px   dashed grey">
                <div style="width: 100%;height: 600px;" id="lineSpendByCostCenterAccountCode"></div>
            </div>

            <div class="col-lg-12 col-md-12 col-sm-12 graphArea" style="border: 1px   dashed grey">
                <div style="width: 100%;height: 600px;" id="barTstMemberSpend"></div>
            </div>

        </div>
    </div>
</div>

<div class="row">
    <!--    <div class="col-md-12 graphArea" >-->
    <!--        <div id="myGrid" style="height: 800px;width: 100%;color: black"></div>-->
    <!--    </div>-->
</div>



