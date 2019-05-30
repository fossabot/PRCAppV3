
<script>
// aqui tem os scripts basicos. 

    var dsMainObjectDash = new function () {

        // variaveis privadas;

        var thisObj = this;
        thisObj.dashArray = {};
        var vHasHelper = false;
        // funcao de inicio;
        this.start = function () {
            $('#dashScroll').cgbMakeScrollbar({autoWrapContent: false, alwaysShowScrollbar: 0, theme: 'dark'});

            thisObj.vHTMLAddNew = $('#dashNewOptions').html();
            $('#dashNewOptions').remove();
            this.addListeners();
            this.makeTimer();

        }

        this.makeHelper = function () {
            var vHelper = [];

            vHelper.push({element: '#btnDashAddNew', intro: 'Add New Widget'});
            vHelper.push({element: '#btnDashSave', intro: 'Save Settings Widget'});
            vHelper.push({element: '#btnRefreshSave', intro: 'Refresh Data'});

            if (Object.keys(thisObj.dashArray).length > 0) {
                var vkey = Object.keys(thisObj.dashArray)[0];
                var vdata = thisObj.dashArray[vkey];

                vHelper.push({element: '#' + vdata.obj.dashBox, intro: '<?php echo ($introArea) ?>'});
                vHelper.push({element: '#dashLast' + vdata.id, intro: '<?php echo($introLastRef) ?>'});
                vHelper.push({element: '#btnWidRefresh' + vdata.id, intro: '<?php echo($introRef) ?>'});
                vHelper.push({element: '#btnWidSettings' + vdata.id, intro: '<?php echo($introSet) ?>'});
                vHelper.push({element: '#btnWidCollapse' + vdata.id, intro: '<?php echo($introExp) ?>'});
                vHelper.push({element: '#btnWidMove' + vdata.id, intro: '<?php echo($introMove) ?>'});
            }

            if (thisObj.vHasHelper) {
                introRemove();
            }

            thisObj.vHasHelper = true;

            introAddNew({steps: vHelper});

        }

        this.destroyHelper = function () {
            if (thisObj.vHasHelper) {
                introRemove();
            }

            thisObj.vHasHelper = false;

        }

        this.makeTimer = function () {

            thisObj.dashInterval = setInterval(function () {
                thisObj.refreshByTimer();
            }, 60000);

        }

        this.resizeAll = function () {
            $.each(thisObj.dashArray, function (i, v) {
                if (v.obj == undefined) {
                    return true;
                }

                v.obj.resize();

            })
        }

        this.refreshByTimer = function () {
            var vObjToRefresh = [];
            var vNow = moment();
            $.each(thisObj.dashArray, function (i, v) {
                if (v.obj == undefined) {
                    return true;
                }
                //v.obj.refresh();

                var vrefInterval = v.obj.getRefreshInterval();
                var vLastRefresh = $.extend({}, v.obj.lastRefresh);
                var vRefresing = v.obj.refreshing;

                if (vRefresing) {
                    return true;
                }

                if (vLastRefresh == undefined) {
                    vObjToRefresh.push(v.obj);
                    return true;
                }

                if (vNow.diff(vLastRefresh, 'minutes') >= vrefInterval) {
                    vObjToRefresh.push(v.obj);
                }
            });

            if (vObjToRefresh.length == 0) {
                return;
            }


            $.myCgbAjax({url: 'dashboard/dashboard/checkSession',
                message: javaMessages.retrieveInfo,
                box: '#dashboardArea',
                dataType: 'html',
                //data: {'settings': vSettings},
                systemRequest: true,
                success: function (a) {
                    $.each(vObjToRefresh, function (i, v) {
                        v.refresh();
                    });
                },
            });


        }




        this.getDefaultRefreshTime = function () {
            return 120;
        }

        this.getDefaultOption = function () {
            var xoption = {
                tooltip: {
                    trigger: 'axis',
                    axisPointer: {
                        type: 'shadow'
                    }
                },
                legend: {
                    top: 100
                },
                grid: {
                    left: '5%',
                    right: '5%',
                    bottom: 10,
                    containLabel: true
                },
                toolbox: {
                    bottom: 0,
                    left: 0,
                    show: false,
                    orient: 'horizontal',
                    feature: {
                        showTitle: false,
                        magicType: {
                            type: ['line', 'bar', 'stack', 'tiled'],
                            title: {'bar': 'Bar', stack: 'Stack', tiled: 'Tiled', line: 'Line'}
                        },
                        saveAsImage: {
                            show: true,
                            title: 'Save As Image'
                        }
                    }


                }

            }

            return xoption;
        }

        this.addDashBord = function (id, obj) {

            thisObj.dashArray['p' + id] = {obj: obj, id: id}
            obj.start();

            var container = document.getElementById("dashBoardWidgetsArea");

            if (thisObj.sort != undefined) {
                thisObj.sort.destroy();
            }

            thisObj.sort = Sortable.create(container, {
                animation: 150, // ms, animation speed moving items when sorting, `0` — without animation
                handle: ".dashMove", // Restricts sort start click/touch to the specified element
                draggable: ".dragTile", // Specifies which items inside the element should be sortable
                onUpdate: function (evt/**Event*/) {
                    var item = evt.item; // the current dragged HTMLElement

                }
            });


        }

        this.saveDashBoardSettings = function (id) {
            var vSettings = thisObj.dashArray['p' + id].obj.getSettings();
            if (vSettings == undefined) {
                return;
            }

            $.myCgbAjax({url: 'dashboard/dashboard/saveSettings/' + id,
                message: javaMessages.updating,
                box: '#dashboardArea',
                dataType: 'html',
                data: {'settings': vSettings},
                systemRequest: true,
                success: function (a) {
                    if (a != 'OK') {
                        messageBoxError(a);
                    } else {
                        thisObj.dashArray['p' + id].obj.afterSettigsSaved();

                    }

                },
            });


        }

        this.saveGeneralSettings = function (id) {
            var vOrder = thisObj.getScreenOrder();


            $.myCgbAjax({url: 'dashboard/dashboard/saveGeneralSettings/',
                message: javaMessages.updating,
                box: '#dashboardArea',
                dataType: 'html',
                data: {order: vOrder},
                systemRequest: true,
                success: function (a) {
                    if (a != 'OK') {
                        messageBoxError(a);
                    }
                },
            });


        }

        this.getScreenOrder = function () {
            var vScreen = [];
            var vOrder = 1;
            $('.dragTile').each(function () {
                var xdata = {recid: $(this).attr('settingsId'), nr_order: vOrder};
                vOrder++;
                vScreen.push(xdata);
            });

            return vScreen;
        }

        this.removeDashBord = function (id) {
            //thisObj.dashArray[id] = obj;

            messageBoxYesNo('<?php echo($confremove) ?>', function () {
                $.myCgbAjax({url: 'dashboard/dashboard/removeWidget/' + id,
                    message: javaMessages.deleting,
                    box: '#dashboardArea',
                    dataType: 'html',
                    data: [],
                    systemRequest: true,
                    success: function (a) {
                        if (a != 'OK') {
                            messageBoxError(a);
                        } else {
                            var vdiv = thisObj.dashArray['p' + id].obj.getDiv();
                            thisObj.dashArray['p' + id].obj.die();
                            var $vdest = $('#' + vdiv);
                            $vdest.hide('fold', 300, function () {
                                $vdest.remove();
                                thisObj.dashArray['p' + id].obj = undefined;

                            })

                        }

                    },
                });
            });

        }

        this.refreshData = function () {
            $.each(thisObj.dashArray, function (i, v) {
                if (v.obj != undefined) {
                    v.obj.refresh();
                }
            })
        }

        this.addNewWidget = function (id) {
            $.myCgbAjax({url: 'dashboard/dashboard/addNewWidget/' + id,
                message: javaMessages.inserting,
                box: '#dashboardArea',
                dataType: 'json',
                data: [],
                systemRequest: true,
                success: function (a) {
                    $('#dashBoardWidgetsArea').append(a.html);

                },
            });
        }

        this.openPane = function () {

            basicPickListOpenPopOver({
                title: 'DashBoard',
                target: '#btnDashAddNew',
                html: thisObj.vHTMLAddNew,
                showClose: true,
                position: 'auto',
                width: '400px',
                functionOpen: thisObj.prepareReportFrame,
                plCallBack: function (code, desc, data) {

                }
            })
        }


        this.resize = function () {
            $.each(thisObj.dashArray, function (i, v) {
                if (v.obj == undefined) {
                    return true;
                }

                v.obj.resize();

            })
        }


        // funcao de toolbar;


        // adicao de listeners!
        this.addListeners = function () {
            // here only listeners that need to be reinstated after show~
            $("off").on('togglePushMenu.dash');
            
            $("body").on('togglePushMenu.dash', function () {

                setGrpGridHeightDash();
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


        // funcaoes gerais 

    }

// funcoes iniciais;
    dsMainObjectDash.start();

// funcao da toolbar



// insiro colunas;

    function setGrpGridHeightDash() {
        var hAvail = getWorkArea();
        $("#dashScroll").css("height", hAvail - 55);


        $('#dashScroll').cgbMakeScrollbar('resize', hAvail - 55);
        dsMainObjectDash.resize();

    }

    $(window).on('resize.mainResizeDash', function () {
        setGrpGridHeightDash();
    });


    setGrpGridHeightDash();

// insiro colunas;
</script>
<div id="dashBoardAreaWithin" style="height: auto;padding-right: 0px; padding-left: 0px" class='row '>
    <div class='row' style='border-bottom: #000 dashed thin;margin-bottom: 10px;margin-left: 0px;margin-right: 0px;'>
        <div class='col-xs-6'>
            <h4><?php echo ($title); ?></h4>
        </div>
        <div class='col-xs-6'>

            <button type="button" class="btn btn-sm btn-info pull-right" style='font-size: 10px;margin-right: 5px;' aria-label="Left Align" onclick="dsMainObjectDash.refreshData();" id='btnRefreshSave'>
                <span class="fa fa-refresh" aria-hidden="true"></span>
            </button>


            <button type="button" class="btn btn-sm btn-info pull-right" style='font-size: 10px;margin-right: 5px;' aria-label="Left Align" onclick="dsMainObjectDash.saveGeneralSettings();" id='btnDashSave'>
                <span class="fa fa-save" aria-hidden="true"></span>
            </button>

            <button type="button" class="btn btn-sm btn-info pull-right" style='font-size: 10px;margin-right: 5px;' aria-label="Left Align" onclick="dsMainObjectDash.openPane();" id='btnDashAddNew'>
                <span class="fa fa-plus" aria-hidden="true"></span>
            </button>
        </div>
    </div>
    <div id="dashScroll">

        <div class='col-md-12' style="padding-left: 0px;padding-right: 10px;" id='dashBoardWidgetsArea'>
        </div>

    </div>
    <?php echo($html) ?>

    <div style ='width: 400px;display: none; height: 200px; padding: 10px;' id="dashNewOptions">
        <div class="" >
            <ul class="list-group">

                <?php
                $html = '';
                foreach ($avail as $key => $value) {
                    $id = $value['cd_system_dashboard_widget'];
                    $desc = $value['ds_system_dashboard_widget'];
                    ?>

                    <li class="list-group-item" style='vertical-align: top; padding-top: 10px; padding-bottom: 10px;'>

                        <div>  
                            <span style='font-weight: bold'><?php echo ($desc) ?></span>

                            <button type="button" class="btn btn-sm btn-info pull-right " style='font-size: 8px' aria-label="Left Align" onclick='dsMainObjectDash.addNewWidget(<?php echo($id) ?>);'>
                                <span class="fa fa-plus" aria-hidden="true"></span>
                            </button>
                            <div>
                                <span style='font-size: 10px;'><?php echo ($value['ds_comments']); ?></span>

                            </div>
                        </div>
                    </li>



                <?php } ?>

            </ul>


        </div>

    </div>

</div>