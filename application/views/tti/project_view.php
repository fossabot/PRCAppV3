<?php include_once APPPATH . 'views/viewIncludeFilter.php'; ?>

<style>

    .label-opacity {
        opacity: 0.2 !important;
    }
    
    #watermark {
        height: 450px;
        width: 600px;
        position: absolute;
        overflow: hidden;
    }
    #watermark img {
        max-width: 100%;
    }
    #watermark p {
        position: absolute;
        top: 0;
        left: 0;
        color: #fff;
        font-size: 18px;
        pointer-events: none;
        -webkit-transform: rotate(-45deg);
        -moz-transform: rotate(-45deg);
    }

    .vis-label.vis-nesting-group.collapsed:before {
        content: "\f061";
        font-family: FontAwesome;
        font-style: normal;
        font-weight: normal;
        text-decoration: inherit;
    }

    .vis-label.vis-nesting-group.expanded:before {
        content: "\f063";
        font-family: FontAwesome;
        font-style: normal;
        font-weight: normal;
        text-decoration: inherit;
    }


    @keyframes fill-animation {
        from {background-color: white;}
        to {background-color: lightblue;}
    }

    @keyframes fill-animation-deact {
        from {background-color: white;}
        to {background-color: lightcoral;}
    }


    .carousel-inner > .item > img, .carousel-inner > .item {

    }
    .carousel-inner > .item > img, .carousel-inner > .item > a > img {
        height: 300px;
        margin:auto;

    }    

    .itemProject {

    }

    .itemContentPrjPlan {
        font-size: 10px;
        background-color: #ffffcc;
        border-radius: 15px !important;
    }

    .itemContentPrjAgreed {
        font-size: 10px;
        background-color: #ccebff;
        border-radius: 15px !important;
    }

    .itemContentPrjComplete {
        font-size: 10px;
        background-color: #80ffcc;
        border-radius: 15px !important;
    }



    .itemContentBuildPlan {
        font-size: 10px;
        background-color: #ffffcc;
        border-radius: 15px;
    }

    .itemContentBuildAgreed {
        font-size: 10px;
        background-color: #ccebff;
        border-radius: 15px !important;
    }

    .itemContentBuildComplete {
        font-size: 10px;
        background-color: #80ffcc;
        border-radius: 15px !important;
    }


    .itemContentTstPlan {
        font-size: 10px;
        background-color: #ffffcc;
    }

    .itemContentTstAgreed {
        font-size: 10px;
        background-color: #ccebff;
    }

    .itemContentTstComplete {
        font-size: 10px;
        background-color: #80ffcc;
    }

    .VerticalText{
        transform:  translateX(-50%) translateY(-50%) rotate(-90deg);
        font-weight: bold;
        font-size: 16px;
        position: absolute;
        top: 50%;
        left: 50%;
    }

    .prjBuildClass .collapsed-box .VerticalText {
        display: none;
    }

    .isDeactivatedVert {
        display: none !important;
    }

    .prjBuildClass .collapsed-box .box-header span {
        display: inline-block !important;
        font-size: 14px;
        font-weight: bold;
    }

    
    .prjBuildClass .collapsed-box .box-header{
        background-color: lightblue;
        animation-name: fill-animation;
        animation-duration: 0.5s;
    }

    .isDeactivated{
        background-color: lightcoral !important;
        animation-name: fill-animation-deact !important;
        animation-duration: 0.5s;
    }


    .toRemove {
        background-color: red;
    }
    .calData  .fc-scroller {
        overflow-y: hidden !important;
    }

    .divDrag {
        width: 18px; 
        height: 18px;
        text-align: center;
        font-size: 9px; 
        font-weight: bold;
        cursor: pointer;
        background-color: #337ab7;
        color: white;
        border: gray 1px solid;
    }

    hr.scttst {
        display: block;
        height: 2px;
        border: 0;
        border-top: 1px dashed #FF6347;
        margin: 1em 0;
        padding: 0; 
    }

    .schtstdiv:last-child hr {
        display:none;
    }

</style>

<script>
// aqui tem os scripts basicos. 
    var gridName = "gridGeneric";
//var controllerName = "country";


//$(".ds_hr_type").on( "change",  function() {
    dsFormPrjSheetObject = undefined;

    var dsMainObject = new function () {

        // variaveis privadas;

        var thisObj = this;
        thisObj.isFilterVisible = true;
        thisObj.gridName = undefined;

        // funcao de inicio;
        this.start = function (gridNamePar) {
            thisObj.gridName = gridNamePar;
            thisObj.dataGant = [];
            thisObj.setTimeAxisActual = {};
            thisObj.dataGroup = [];
            thisObj.dataItem = [];
            thisObj.nestedVisible = true;
            thisObj.goToProjectBuild = undefined;
            thisObj.firstTimeLoad = false;

            if (w2ui[thisObj.gridName] !== undefined) {
                w2ui[thisObj.gridName].destroy();
            }

<?php echo ($javascript); ?>


            $('#mainTabsDiv').ctabStart({afterChanged: thisObj.tabAfterChanged, beforeChange: thisObj.tabBeforeChanged});
            $('#tab_detail_div').append($('#detailArea').detach())
            $('#tab_gannt_div').append($('#ganttFullArea').detach())

            this.addListeners();
            this.addHelper();

            var vs = moment().subtract(5, 'days').format(defaultDateFormatUpper);
            var ve = moment().add(5, 'days').format(defaultDateFormatUpper);

            $('#dt_start').val(vs);
            $('#dt_finish').val(ve);

            $('#datesGantt').datepicker({
                autoclose: true,
                format: defaultDateFormat,
                todayBtn: "linked",
                clearBtn: true,
                todayHighlight: true,
                inputs: $('.daterange')
            });

            $('#dd_group').select2();
            $('#dd_gantreturn').select2();
            
            
            $('#dd_group').on('change', function () {
                thisObj.makeGroupAndDraw();
            });


            $('#gantFilter').find('[type="checkbox"]').on('change', function () {
                thisObj.loadGantt();
            })


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
                thisObj.editProject(-1);
            }
            if (bPressed == 'retrieve') {
                w2ui[thisObj.gridName].retrieve();
            }
            if (bPressed == "update") {
                w2ui[thisObj.gridName].update();
            }

            if (bPressed == "edit") {
                var ret = w2ui[thisObj.gridName].getSelection();
                if (ret.length > 0) {
                    var vproj = w2ui[thisObj.gridName].getItem(ret[0], 'cd_project');
                    thisObj.editProject(vproj);
                }
            }
            if (bPressed == 'filter') {
                hideFilter();
            }

        }


        // adicao de listeners!
        this.addListeners = function () {
            var vfirst = false;
            $(window).on('prjChanged', function (a, b) {
                $.each(b.projs, function (i, v) {
                    var vd = w2ui[thisObj.gridName].get(v.recid);
                    if (vd == null) {
                        w2ui[thisObj.gridName].add([v], true);
                        vfirst = true;
                    } else {
                        w2ui[thisObj.gridName].set(v.recid, v);
                    }
                });

                if (vfirst) {
                    w2ui[thisObj.gridName].ScrollToRow(w2ui[thisObj.gridName].records[0].recid, true);
                    w2ui[thisObj.gridName].select(w2ui[thisObj.gridName].records[0].recid);

                }

            });

            $(window).on('modelDeleted', function (event, m) {
                var recs = w2ui[thisObj.gridName].find({cd_project_model: m});
                if (recs.length > 0) {
                    w2ui[thisObj.gridName].remove(recs[0]);
                }
            });

            $(window).on('projectDeleted', function (event, m) {
                var recs = w2ui[thisObj.gridName].find({cd_project: m});
                $.each(recs, function (i, v) {
                    w2ui[thisObj.gridName].remove(v);
                })

            });


            w2ui[thisObj.gridName].on('dblClick', function (event) {
                //thisObj.ToolBarClick('edit');
                $('#tab_detail a').click();
            });

            w2ui[thisObj.gridName].on('retrieveOnAdd', function (event) {
                //thisObj.ToolBarClick('edit');
                event.onComplete = function (e) {

                    thisObj.dataGant = e.data.gannt;
                }


            });



        }

        // roda antes de fechar (se retornar FALSE o sistema vai perguntar se quer fechar
        this.beforeClose = function () {
            return w2ui[thisObj.gridName].getChanges().length == 0;
        }


        // close object (lugar para destruir as coisas//
        this.close = function () {
            w2ui[thisObj.gridName].destroy();
            $(window).off('prjChanged');
            $(window).off('modelDeleted');
            $(window).off('projectDeleted');

            if (dsFormPrjSheetObject != undefined) {
                dsFormPrjSheetObject.remove(true);
                dsFormPrjSheetObject = undefined;

            }

            introRemove();
            return true;
        }


        this.tabBeforeChanged = function (id) {
            if (id == 'tab_detail') {
                if (w2ui[thisObj.gridName].getSelection().length == 0) {
                    return false;
                }
            }

            return true;
        }

        this.loadGantt = function () {
            var vs = moment($('#dt_start').val(), defaultDateFormatUpper);
            var ve = moment($('#dt_finish').val(), defaultDateFormatUpper);

            var vPlanned = $('#fl_planned').is(':checked');
            var vAgreed = $('#fl_agreed').is(':checked');
            var vComplete = $('#fl_completed').is(':checked');

            if (!vPlanned && !vAgreed && !vComplete) {
                return;
            }

            var vdate = {where: retFilterInformed(), start: vs.format('YYYY-MM-DD'), end: ve.format('YYYY-MM-DD')};

            vdate['planned'] = vPlanned ? 'Y' : 'N';
            vdate['agreed'] = vAgreed ? 'Y' : 'N';
            vdate['complete'] = vComplete ? 'Y' : 'N';
            vdate['qtyToRetrieve'] = select2GetData('dd_gantreturn').id;

            $.myCgbAjax({url: 'tti/project/getGanttData',
                message: javaMessages.retrieveInfo,
                box: '#allArea',
                data: vdate,

                success: function (data) {
                    thisObj.dataGant = data.gantt;

                    w2ui[thisObj.gridName].clear();
                    w2ui[thisObj.gridName].add(data.grid);

                    if (data.grid.length > 0) {
                        w2ui[thisObj.gridName].ScrollToRow(data.grid[0].recid, true);
                    }

                    thisObj.makeGroupAndDraw();
                },
                errorAfter: function () {

                }});
        }

        this.makeGroupAndDraw = function () {
            waitMsgON('#ganttFullArea');

            // starting range
            var vs = moment($('#dt_start').val(), defaultDateFormatUpper).subtract(14, 'days');
            var ve = moment($('#dt_finish').val(), defaultDateFormatUpper).add(30, 'days');
            var vx = select2GetData('dd_group');
            
            var vret = [];

            switch (vx.id) {
                case '0':
                    // group by nothing
                    vret = this.ganttGroupNone(thisObj.dataGant)

                    break;

                case '1':
                    // group by project
                    vret = this.ganttGroupProj(thisObj.dataGant)
                    break;

                case '4':
                    // group by project
                    vret = this.ganttGroupTEProj(thisObj.dataGant)
                    break;



                case '5':
                    // group by project
                    vret = this.ganttGroupTE(thisObj.dataGant)
                    break;

                default:

                    break;
            }

            console.log('XX', vret);

            thisObj.setExpandCollapseIcon()


            thisObj.dataItem = new vis.DataSet(vret.vdefItems);
            thisObj.dataGroup = new vis.DataSet(vret.vdefGroups);

            var vh = getWorkArea() - 110;


            var options = {horizontalScroll: true, verticalScroll: true,
                zoomable: true,
                maxHeight: vh,
                zoomKey: 'ctrlKey',
                groupOrder: 'content',

                timeAxis: {scale: 'day', step: 1},
                start: vs.format('YYYY-MM-DD'),
                end: ve.format('YYYY-MM-DD'),
                orientation: "top",
                onInitialDrawComplete: function () {
                    waitMsgOFF('#ganttFullArea');
                }};



            var container = document.getElementById('ganttTimelineArea');

            if (thisObj.timeline != undefined) {
                thisObj.timeline.destroy();
            }

            thisObj.timeline = new vis.Timeline(container, thisObj.dataItem, thisObj.dataGroup, options);


            thisObj.timeline.on('doubleClick', function (a) {

                if (a.what != 'item') {
                    return;
                }

                var view = new vis.DataView(thisObj.dataItem, {
                    filter: function (item) {
                        return (item.id == a.item);
                    }
                });

                var vitem = view.get();
                if (vitem.length == 0) {
                    return;
                }

                if (chkUndefined(vitem[0].prjmodel, -1) == -1) {
                    return;
                }

                w2ui[thisObj.gridName].ScrollToRow(vitem[0].prjmodel);
                thisObj.goToProjectBuild = vitem[0].prjbuild;

                $('#tab_detail a').click();

            });


            thisObj.timeline.on('rangechanged', function (a) {

                var vmin = moment(a.start);
                var vmax = moment(a.end);
                var vdiff = vmax.diff(vmin, 'days');

                var vtimeAxis = {scale: 'day', step: 1};

                if (vdiff > 55) {
                    vtimeAxis = {scale: 'day', step: 3};
                }

                if (vdiff > 90) {
                    vtimeAxis = {scale: 'day', step: 7};
                }

                if (JSON.stringify(thisObj.setTimeAxisActual) == JSON.stringify(vtimeAxis)) {
                    return;
                }

                thisObj.timeline.setOptions({timeAxis: vtimeAxis});
                thisObj.setTimeAxisActual = vtimeAxis;

            });



        }


        this.ganttGroupNone = function (dataret) {

            thisObj.nestedVisible = true

            var vdefItems = [];
            var vdefGroups = [];
            var vg = {};

            var vPlanned = $('#fl_planned').is(':checked');
            var vAgreed = $('#fl_agreed').is(':checked');
            var vComplete = $('#fl_completed').is(':checked');


            vdefGroups.push({
                id: 'pxxx',
                content: '',
                showNested: true,
                className: "itemProject",
                mainGroup: true
            });


            $.each(dataret, function (i, v) {
                var vcont = v.ds_build + ' - ' + v.ds_project_number + '<BR>' + v.ds_project_name + '<br>' + v.ds_test_type + '<br>' + v.ds_items;

                if (chkUndefined(v.start_plan_tst, '') != '' && vPlanned) {

                    vdefItems.push({
                        id: 'pl' + v.id,
                        content: vcont,
                        start: v.start_plan_tst,
                        end: v.end_plan_tst,
                        group: 'pxxx',
                        className: 'itemContentTstPlan',
                        showTooltips: true,
                        title: vcont,
                        prjmodel: v.cd_project_model,
                        prjbuild: v.cd_project_build_schedule
                    });
                }


                if (chkUndefined(v.start_agreed_tst, '') != '' && vAgreed) {

                    vdefItems.push({
                        id: 'pl' + v.id + 'a',
                        content: vcont,
                        start: v.start_agreed_tst,
                        end: v.end_agreed_tst,
                        group: 'pxxx',
                        className: 'itemContentTstAgreed',
                        showTooltips: true,
                        title: vcont,
                        prjmodel: v.cd_project_model,
                        prjbuild: v.cd_project_build_schedule
                    });
                }


                if (chkUndefined(v.start_complete_tst, '') != '' && vComplete) {
                    vdefItems.push({
                        id: 'pl' + v.id + 'c',
                        content: vcont,
                        start: v.start_complete_tst,
                        end: v.end_complete_tst,
                        group: 'pxxx',
                        className: 'itemContentTstComplete',
                        showTooltips: true,
                        title: vcont,
                        prjmodel: v.cd_project_model,
                        prjbuild: v.cd_project_build_schedule
                    });
                }
            });

            return {vdefItems: vdefItems, vdefGroups: vdefGroups};

        }

        this.ganttGroupTE = function (dataret) {
            thisObj.nestedVisible = true

            var vdefItems = [];
            var vdefGroups = [];
            var vg = {};

            var vPlanned = $('#fl_planned').is(':checked');
            var vAgreed = $('#fl_agreed').is(':checked');
            var vComplete = $('#fl_completed').is(':checked');




            $.each(dataret, function (i, v) {
                

                if (vg['p' + v.cd_human_resource_te] == undefined) {
                    v.ds_grp_tst_te = thisObj.makeArrayFromPG(v.ds_grp_tst_te);
                    var filtered = v.ds_grp_tst_te.filter(function (el) {
                        return el != null;
                    })

                    vdefGroups.push({
                        id: 'p' + v.cd_human_resource_te,
                        content: v.ds_human_resource_te,
                        className: "itemProject",
                        nestedGroups: ['pp' + v.cd_human_resource_te],
                        mainGroup: true
                    });


                    /*Header area with totals*/
                    if (chkUndefined(v.start_plan_te, '') != '' && vPlanned) {

                        vdefItems.push({
                            id: 'pt' + v.id,
                            content: v.ds_human_resource_te,
                            start: v.start_plan_te,
                            end: v.end_plan_te,
                            group: 'p' + v.cd_human_resource_te,
                            className: 'itemContentTstPlan',
                            prjmodel: v.cd_project_model,
                            prjbuild: v.cd_project_build_schedule
                        });
                    }


                    if (chkUndefined(v.start_agreed_te, '') != '' && vAgreed) {

                        vdefItems.push({
                            id: 'pt' + v.id + 'a',
                            content: vcont,
                            start: v.start_agreed_te,
                            end: v.end_agreed_te,
                            group: 'p' + v.cd_human_resource_te,
                            className: 'itemContentTstAgreed',
                            prjmodel: v.cd_project_model,
                            prjbuild: v.cd_project_build_schedule
                        });
                    }


                    if (chkUndefined(v.start_complete_te, '') != '' && vComplete) {
                        vdefItems.push({
                            id: 'pt' + v.id + 'c',
                            content: vcont,
                            start: v.start_complete_te,
                            end: v.end_complete_te,
                            group: 'p' + v.cd_human_resource_te,
                            className: 'itemContentTstComplete',
                            prjmodel: v.cd_project_model,
                            prjbuild: v.cd_project_build_schedule
                        });
                    }



                    vdefGroups.push({
                        id: 'pp' + v.cd_human_resource_te,
                        content: '',
                        className: "itemProject"
                    });


                    vg['p' + v.cd_human_resource_te] = 'Y';



                }


                var vcont = v.ds_build + ' - ' + v.ds_project_number + '<BR>' + v.ds_project_name + '<br>' + v.ds_test_type + '<br>' + v.ds_items;
                if (chkUndefined(v.start_plan_tst, '') != '' && vPlanned) {

                    vdefItems.push({
                        id: 'pl' + v.id,
                        content: vcont,
                        start: v.start_plan_tst,
                        end: v.end_plan_tst,
                        group: 'pp' + v.cd_human_resource_te,
                        className: 'itemContentTstPlan',
                        prjmodel: v.cd_project_model,
                        prjbuild: v.cd_project_build_schedule
                    });
                }


                if (chkUndefined(v.start_agreed_tst, '') != '' && vAgreed) {

                    vdefItems.push({
                        id: 'pl' + v.id + 'a',
                        content: vcont,
                        start: v.start_agreed_tst,
                        end: v.end_agreed_tst,
                        group: 'pp' + v.cd_human_resource_te,
                        className: 'itemContentTstAgreed',
                        prjmodel: v.cd_project_model,
                        prjbuild: v.cd_project_build_schedule
                    });
                }


                if (chkUndefined(v.start_complete_tst, '') != '' && vComplete) {
                    vdefItems.push({
                        id: 'pl' + v.id + 'c',
                        content: vcont,
                        start: v.start_complete_tst,
                        end: v.end_complete_tst,
                        group: 'pp' + v.cd_human_resource_te,
                        className: 'itemContentTstComplete',
                        prjmodel: v.cd_project_model,
                        prjbuild: v.cd_project_build_schedule
                    });
                }
            });
            return {vdefItems: vdefItems, vdefGroups: vdefGroups};
        }



        this.ganttGroupProj = function (dataret) {
            thisObj.nestedVisible = true

            var vdefItems = [];
            var vdefGroups = [];
            var vg = {};

            var vPlanned = $('#fl_planned').is(':checked');
            var vAgreed = $('#fl_agreed').is(':checked');
            var vComplete = $('#fl_completed').is(':checked');

            $.each(dataret, function (i, v) {

                if ((vPlanned && chkUndefined(v.start_plan_prj, '') != '') || (chkUndefined(v.start_agreed_prj, '') != '') && vAgreed || (chkUndefined(v.start_complete_prj, '') != '' && vComplete)) {

                } else {
                    return true;
                }
                /*
                 var filtered = v.ds_grp_builds.filter(function (el) {
                 return el != null;
                 });
                 */
                var prjdet = '';
                if (v.ds_project_name.length > 50) {
                    prjdet = v.ds_project_name.substring(0, 50) + '...';
                } else {
                    prjdet = v.ds_project_name;
                }


                v.ds_grp_builds = thisObj.makeArrayFromPG(v.ds_grp_builds);
                if (vg['p' + v.cd_project_model] == undefined) {
                    vdefGroups.push({
                        id: 'p' + v.cd_project_model,
                        content: '<div style="width: 200px; min-height: 40px;">' + prjdet + '</div>',
                        nestedGroups: v.ds_grp_builds,
                        showNested: true,
                        className: "itemProject",
                        mainGroup: true
                    });

                    if (vComplete && chkUndefined(v.start_complete_prj, '') != '') {
                        vdefItems.push({
                            id: 'prj' + v.id + 'c',
                            content: v.ds_project_number + '<BR>' + v.ds_project_name,
                            start: v.start_complete_prj,
                            end: v.end_complete_prj,
                            group: 'p' + v.cd_project_model,
                            className: 'itemContentPrjComplete',
                            type: 'range',
                            limitSize: true,
                            prjmodel: v.cd_project_model
                        });
                    }

                    if (vPlanned && chkUndefined(v.start_plan_prj, '') != '') {
                        vdefItems.push({
                            id: 'prj' + v.id + 'a',
                            content: v.ds_project_number + '<BR>' + v.ds_project_name,
                            start: v.start_plan_prj,
                            end: v.end_plan_prj,
                            group: 'p' + v.cd_project_model,
                            className: 'itemContentPrjPlan',
                            type: 'range',
                            limitSize: true,
                            prjmodel: v.cd_project_model
                        });
                    }

                    if (vAgreed && chkUndefined(v.start_agreed_prj, '') != '') {
                        vdefItems.push({
                            id: 'prj' + v.id,
                            content: v.ds_project_number + '<BR>' + v.ds_project_name,
                            start: v.start_agreed_prj,
                            end: v.end_agreed_prj,
                            group: 'p' + v.cd_project_model,
                            className: 'itemContentPrjAgreed',
                            type: 'range',
                            limitSize: true,
                            prjmodel: v.cd_project_model
                        });
                    }




                    vg['p' + v.cd_project_model] = 'Y';
                }


                if (vg['b' + v.cd_project_build_schedule] == undefined) {

                    vdefGroups.push({
                        id: 'b' + v.cd_project_build_schedule,
                        content: v.ds_build,
                        style: 'padding-left: 20px;'
                    });

                    if (chkUndefined(v.start_plan_build, '') != '' && vPlanned) {
                        vdefItems.push({
                            id: 'bd' + v.id,
                            content: v.ds_build,
                            start: v.start_plan_build,
                            end: v.end_plan_build,
                            group: 'b' + v.cd_project_build_schedule,
                            className: 'itemContentBuildPlan',
                            subgroup: 'bd' + v.id,
                            type: 'range',
                            limitSize: true,
                            prjmodel: v.cd_project_model,
                            prjbuild: v.cd_project_build_schedule
                        });
                    }

                    if (chkUndefined(v.start_agreed_build, '') != '' && vAgreed) {
                        vdefItems.push({
                            id: 'bd' + v.id + 'a',
                            content: v.ds_build,
                            start: v.start_agreed_build,
                            end: v.end_agreed_build,
                            group: 'b' + v.cd_project_build_schedule,
                            className: 'itemContentBuildAgreed',
                            subgroup: 'bd' + v.id,
                            type: 'range',
                            limitSize: true,
                            prjmodel: v.cd_project_model,
                            prjbuild: v.cd_project_build_schedule
                        });
                    }

                    if (chkUndefined(v.start_complete_build, '') != '' && vComplete) {
                        vdefItems.push({
                            id: 'bd' + v.id + 'c',
                            content: v.ds_build,
                            start: v.start_complete_build,
                            end: v.end_complete_build,
                            group: 'b' + v.cd_project_build_schedule,
                            className: 'itemContentBuildComplete',
                            subgroup: 'bd' + v.id,
                            type: 'range',
                            limitSize: true,
                            prjmodel: v.cd_project_model,
                            prjbuild: v.cd_project_build_schedule
                        });
                    }



                    vg['b' + v.cd_project_build_schedule] = 'Y';
                }

                if (chkUndefined(v.start_plan_tst, '') != '' && vPlanned) {

                    vdefItems.push({
                        id: 'pl' + v.id,
                        content: v.ds_test_type + '<br>' + v.ds_items,
                        start: v.start_plan_tst,
                        end: v.end_plan_tst,
                        subgroup: 'pl' + v.id,
                        group: 'b' + v.cd_project_build_schedule,
                        className: 'itemContentTstPlan',
                        prjmodel: v.cd_project_model,
                        prjbuild: v.cd_project_build_schedule

                    });
                }


                if (chkUndefined(v.start_agreed_tst, '') != '' && vAgreed) {

                    vdefItems.push({
                        id: 'pl' + v.id + 'a',
                        content: v.ds_test_type + '<br>' + v.ds_items,
                        start: v.start_agreed_tst,
                        end: v.end_agreed_tst,
                        subgroup: 'pl' + v.id,
                        group: 'b' + v.cd_project_build_schedule,
                        className: 'itemContentTstAgreed',
                        prjmodel: v.cd_project_model,
                        prjbuild: v.cd_project_build_schedule
                    });
                }


                if (chkUndefined(v.start_complete_tst, '') != '' && vComplete) {

                    vdefItems.push({
                        id: 'pl' + v.id + 'c',
                        content: v.ds_test_type + '<br>' + v.ds_items,
                        start: v.start_complete_tst,
                        end: v.end_complete_tst,
                        subgroup: 'pl' + v.id,
                        group: 'b' + v.cd_project_build_schedule,
                        className: 'itemContentTstComplete',
                        prjmodel: v.cd_project_model,
                        prjbuild: v.cd_project_build_schedule
                    });
                }


            });


            return {vdefItems: vdefItems, vdefGroups: vdefGroups};

        }


        this.ganttGroupTEProj = function (dataret) {
            console.log('oinside the code I want');
            thisObj.nestedVisible = true

            var vdefItems = [];
            var vdefGroups = [];
            var vg = {};

            var vPlanned = $('#fl_planned').is(':checked');
            var vAgreed = $('#fl_agreed').is(':checked');
            var vComplete = $('#fl_completed').is(':checked');

            $.each(dataret, function (i, v) {

                if (vg['tt' + v.cd_human_resource_te] == undefined) {
                    v.ds_grp_prj_te = thisObj.makeArrayFromPG(v.ds_grp_prj_te);
                    vdefGroups.push({
                        id: 'tt' + v.cd_human_resource_te,
                        content: v.ds_human_resource_te,
                        className: "itemProject",
                        nestedGroups: v.ds_grp_prj_te, //['pp' + v.cd_human_resource_te],
                        mainGroup: true
                    });

                    /*
                     vdefGroups.push({
                     id: 'pp' + v.cd_human_resource_te,
                     content: '',
                     className: "itemProject",
                     nestedGroups: v.ds_grp_prj_te,
                     });
                     */

                    var vcont = v.ds_human_resource_te;

                    /*Header area with totals*/
                    if (chkUndefined(v.start_plan_te, '') != '' && vPlanned) {

                        vdefItems.push({
                            id: 'pt' + v.id,
                            content: vcont,
                            start: v.start_plan_te,
                            end: v.end_plan_te,
                            group: 'tt' + v.cd_human_resource_te,
                            className: 'itemContentTstPlan',
                            prjmodel: v.cd_project_model,
                            type: 'range'
                        });
                    }

                    if (chkUndefined(v.start_agreed_te, '') != '' && vAgreed) {

                        vdefItems.push({
                            id: 'pt' + v.id + 'a',
                            content: vcont,
                            start: v.start_agreed_te,
                            end: v.end_agreed_te,
                            group: 'tt' + v.cd_human_resource_te,
                            className: 'itemContentTstAgreed',
                            prjmodel: v.cd_project_model,
                            type: 'range'
                        });
                    }


                    if (chkUndefined(v.start_complete_te, '') != '' && vComplete) {
                        vdefItems.push({
                            id: 'pt' + v.id + 'c',
                            content: vcont,
                            start: v.start_complete_te,
                            end: v.end_complete_te,
                            group: 'tt' + v.cd_human_resource_te,
                            className: 'itemContentTstComplete',
                            prjmodel: v.cd_project_model,
                            type: 'range'
                        });
                    }

                    vg['tt' + v.cd_human_resource_te] = 'Y';

                }
                /*
                 var filtered = v.ds_grp_builds.filter(function (el) {
                 return el != null;
                 });
                 */
                var prjdet = '';
                if (v.ds_project_name.length > 50) {
                    prjdet = v.ds_project_name.substring(0, 50) + '...';
                } else {
                    prjdet = v.ds_project_name;
                }

                if (vg['p' + v.cd_project_model] == undefined) {
                    vdefGroups.push({
                        id: 'p' + v.cd_project_model,
                        content: '<div style="width: 200px; min-height: 40px;">' + prjdet + '</div>',
                        //nestedGroups: v.ds_grp_builds_te,
                        showNested: true,
                        className: "itemProject"
                    });

                    if (vComplete && chkUndefined(v.start_complete_prj_te, '') != '') {
                        vdefItems.push({
                            id: 'prj' + v.id + 'c',
                            content: v.ds_project_number + '<BR>' + v.ds_project_name,
                            start: v.start_complete_prj_te,
                            end: v.end_complete_prj_te,
                            group: 'p' + v.cd_project_model,
                            className: 'itemContentPrjComplete',
                            type: 'range',
                            prjmodel: v.cd_project_model
                        });
                    }

                    if (vPlanned && chkUndefined(v.start_plan_prj_te, '') != '') {
                        vdefItems.push({
                            id: 'prj' + v.id + 'a',
                            content: v.ds_project_number + '<BR>' + v.ds_project_name,
                            start: v.start_plan_prj_te,
                            end: v.end_plan_prj_te,
                            group: 'p' + v.cd_project_model,
                            className: 'itemContentPrjPlan',
                            type: 'range',
                            prjmodel: v.cd_project_model
                        });
                    }

                    if (vAgreed && chkUndefined(v.start_agreed_prj_te, '') != '') {
                        vdefItems.push({
                            id: 'prj' + v.id,
                            content: v.ds_project_number + '<BR>' + v.ds_project_name,
                            start: v.start_agreed_prj_te,
                            end: v.end_agreed_prj_te,
                            group: 'p' + v.cd_project_model,
                            className: 'itemContentPrjAgreed',
                            type: 'range',
                            prjmodel: v.cd_project_model
                        });
                    }

                    vg['p' + v.cd_project_model] = 'Y';
                }


                if (vg['b' + v.cd_project_build_schedule] == undefined && false) {

                    vdefGroups.push({
                        id: 'b' + v.cd_project_build_schedule,
                        content: v.ds_build,
                        style: 'padding-left: 20px;'
                    });

                    if (vPlanned) {
                        vdefItems.push({
                            id: 'bd' + v.id,
                            content: v.ds_build,
                            start: v.start_plan_build,
                            end: v.end_plan_build,
                            group: 'b' + v.cd_project_build_schedule,
                            className: 'itemContentBuildPlan',
                            type: 'range',
                            prjmodel: v.cd_project_model,
                            prjbuild: v.cd_project_build_schedule
                        });
                    }

                    if (chkUndefined(v.start_agreed_build, '') != '' && vAgreed) {
                        vdefItems.push({
                            id: 'bd' + v.id + 'a',
                            content: v.ds_build,
                            start: v.start_agreed_build,
                            end: v.end_agreed_build,
                            group: 'b' + v.cd_project_build_schedule,
                            className: 'itemContentBuildAgreed',
                            type: 'range',
                            prjmodel: v.cd_project_model,
                            prjbuild: v.cd_project_build_schedule
                        });
                    }

                    if (chkUndefined(v.start_complete_build, '') != '' && vComplete) {
                        vdefItems.push({
                            id: 'bd' + v.id + 'c',
                            content: v.ds_build,
                            start: v.start_complete_build,
                            end: v.end_complete_build,
                            group: 'b' + v.cd_project_build_schedule,
                            className: 'itemContentBuildComplete',
                            type: 'range',
                            prjmodel: v.cd_project_model,
                            prjbuild: v.cd_project_build_schedule
                        });
                    }



                    vg['b' + v.cd_project_build_schedule] = 'Y';
                }

                if (chkUndefined(v.start_plan_tst, '') != '' && vPlanned) {

                    vdefItems.push({
                        id: 'pl' + v.id,
                        content: v.ds_build + ' - ' + v.ds_test_type + '<br>' + v.ds_items,
                        start: v.start_plan_tst,
                        end: v.end_plan_tst,
                        subgroup: 'pl' + v.id,
                        group: 'p' + v.cd_project_model,
                        className: 'itemContentTstPlan',
                        prjmodel: v.cd_project_model,
                        prjbuild: v.cd_project_build_schedule

                    });
                }


                if (chkUndefined(v.start_agreed_tst, '') != '' && vAgreed) {

                    vdefItems.push({
                        id: 'pl' + v.id + 'a',
                        content: v.ds_build + ' - ' + v.ds_test_type + '<br>' + v.ds_items,
                        start: v.start_agreed_tst,
                        end: v.end_agreed_tst,
                        subgroup: 'pl' + v.id,
                        group: 'p' + v.cd_project_model,
                        className: 'itemContentTstAgreed',
                        prjmodel: v.cd_project_model,
                        prjbuild: v.cd_project_build_schedule
                    });
                }


                if (chkUndefined(v.start_complete_tst, '') != '' && vComplete) {

                    vdefItems.push({
                        id: 'pl' + v.id + 'c',
                        content: v.ds_build + ' - ' + v.ds_test_type + '<br>' + v.ds_items,
                        start: v.start_complete_tst,
                        end: v.end_complete_tst,
                        subgroup: 'pl' + v.id,
                        group: 'p' + v.cd_project_model,
                        className: 'itemContentTstComplete',
                        prjmodel: v.cd_project_model,
                        prjbuild: v.cd_project_build_schedule
                    });
                }


            });

            return {vdefItems: vdefItems, vdefGroups: vdefGroups};
        }



        this.tabAfterChanged = function (id, from) {

            if (id == 'tab_detail') {

                thisObj.isFilterVisible = isFilterVisible();
                if (thisObj.isFilterVisible) {
                    hideFilter(true);
                }
                thisObj.firstTimeLoad = true;
                var rrec = w2ui[thisObj.gridName].getSelection()[0];
                thisObj.loadDetails(w2ui[thisObj.gridName].getItem(rrec, 'cd_project'), w2ui[thisObj.gridName].getItem(rrec, 'cd_project_model'));
            }

            if (from == 'tab_detail') {
                dsFormPrjSheetObject.remove(true);
                $('#detailArea').empty();
                dsFormPrjSheetObject = undefined;

                if (thisObj.isFilterVisible && !isFilterVisible()) {
                    hideFilter(true);
                }

            }

            if (id == 'tab_gannt') {


                if (thisObj.dataItem.length == 0) {
                    thisObj.loadGantt();
                }


            }



            setGrpGridHeight();

        }


        // funcaoes gerais 

        this.editProject = function (vcd_project) {
            var title = '<?php echo ($editProjTitle) ?>';
            openFormUiBootstrap(
                    title,
                    'tti/project/callPrjForm/' + vcd_project,
                    'col-lg-8 col-lg-offset-2 col-sm-10 col-sm-offset-1'
                    );
        }



        thisObj.loadDetails = function (cd_prj, cd_model, reload) {
            
            if (reload) {
                thisObj.firstTimeLoad = false;
            }
            
            $.myCgbAjax({url: 'tti/project/callPrjSheetForm/' + cd_prj + '/' + cd_model,
                message: javaMessages.retrieveData,
                success: function (data) {

                    if (reload) {
                        var vStatus = dsFormPrjSheetObject.getScreenStatus();
                        dsFormPrjSheetObject.remove(false);
                        $('#detailArea').empty();
                        dsFormPrjSheetObject = undefined;
                    }


                    $('#detailArea').append(data.html);

                    if (reload) {
                        dsFormPrjSheetObject.setScreenStatus(vStatus);
                    }

                    if (thisObj.goToProjectBuild != undefined) {
                        var id = '#buildScheduleArea' + thisObj.goToProjectBuild;
                        $('#PrjscrollPart').cgbMakeScrollbar('scrollToY', $(id));
                        thisObj.goToProjectBuild = undefined;
                    }







                    //$('#testScrollArea').cgbMakeScrollbar('scrollToY', $('#testArea_' + data.pk).position().top);
                    //thisObj.Form.addNewElements();
                }});
        }


        this.collapseTimeLine = function () {

            thisObj.nestedVisible = !thisObj.nestedVisible;

            $.each(thisObj.dataGroup.get(), function (i, v) {

                if (thisObj.nestedVisible) {
                    thisObj.dataGroup.update({id: v.id, showNested: true, visible: true});
                } else {
                    if (v.mainGroup) {
                        thisObj.dataGroup.update({id: v.id, showNested: false});
                    } else {
                        thisObj.dataGroup.update({id: v.id, showNested: false, visible: false});
                    }
                }


            });

            thisObj.setExpandCollapseIcon();
        }

        this.setExpandCollapseIcon = function () {
            if (thisObj.nestedVisible) {
                $('#expandbutton').removeClass('fa-expand').addClass('fa-compress');
            } else {
                $('#expandbutton').removeClass('fa-compress').addClass('fa-expand');

            }
        }

        this.makeArrayFromPG = function(data) {
            if ($.isArray(data)) {
                return data;
            }
            data = chkUndefined(data, '{}');
            console.log('data', data);
            return data.replace('{', '').replace('}','').split(',');
        }

    }

// funcoes iniciais;
    dsMainObject.start(gridName);

// funcao da toolbar
    function onGridToolbarPressed(bPressed, dData) {
        dsMainObject.ToolBarClick(bPressed, dData);
    }


    makeFilterWithEnter(function () {
        var vtab = getSelectedTab('#mainTabsDiv');

        if (vtab == 'tab_browse') {
            dsMainObject.ToolBarClick('retrieve', undefined);
        } else {
            dsMainObject.loadGantt();
        }

    });

// insiro colunas;
    function setGrpGridHeight() {
        var hAvail = getWorkArea();
        $("#tab_browse_div").css("height", hAvail - 50);

        $("#ganttTimelineArea").css("height", hAvail - 100);

        if (dsMainObject.timeline != undefined) {
            dsMainObject.timeline.setOptions({maxHeight: hAvail - 110});
            dsMainObject.timeline.redraw();
        }

        //$("#detailArea").css("height", hAvail - 50);
        w2ui[gridName].resize();

        if (dsFormPrjSheetObject != undefined) {
            dsFormPrjSheetObject.resize();
        }
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
// insiro colunas;


</script>

<div id="detailArea" class="col-md-12" style="background-color: white; padding-left: 0px;padding-right: 5px;"></div>

<div class="row" id='allArea'>
    <?php echo ($tab); ?>
</div>


<div id="ganttFullArea"> 
    <div class="row">
        <div id="gantFilter" style="height: 40px">


            <div class="col-lg-2 col-md-4  col-sm-6 col-xs-6 graphArea" style='padding-top:5px;' >
                <div class="input-group " id="datesGantt">
                    <input type="text" class="form-control input-sm daterange" id="dt_start" style="text-align: center;"  value="">
                    <div class="input-group-addon input-group-sm " style="padding: 5px;"> - </div>
                    <input type="text" class="form-control input-sm daterange" id="dt_finish" style="text-align: center;" value="">
                </div>
            </div>

            <div class="col-md-1 col-sm-4" style="max-width: 70px;padding-right: 10px;padding-left: 1px;padding-top: 3px;"   >
                <label for="dd_group" class="control-label" style="float: right; padding: 0px">Group By:</label>
            </div>

            <div class="col-lg-2 col-md-3" >
                <select name="ganttgroup" form="gantGroupp" class="form-control input-sm" style="width: 100%" id="dd_group">
                    <option value="0" selected>No Group</option>
                    <option value="1" selected>Project</option>
                    <option value="5">Test Engineer</option>
                    <option value="4">Test Engineer/Project</option>                    
                    <?php /*



                      <option value="2">Build Phase</option>
                      <option value="3">Project Leader</option>
                      <option value="4">Project Manager</option>

                      <option value="6">Location</option>
                      <option value="7">Priority</option>
                      <option value="8">Sample Qty</option>
                     * 
                     */ ?>
                </select>
            </div>

            <div class="col-md-2 col-sm-4" style="max-width: 70px;padding-right: 10px;padding-left: 1px;padding-top: 3px;"   >
                <label for="dd_gantreturn" class="control-label" style="float: right; padding: 0px" title="Lines Displayed">Lines Displayed</label>
            </div>

            <div class="col-lg-1 col-md-1" >
                <select name="gantreturn" form="gantreturn" class="form-control input-sm" style="width: 100%" id="dd_gantreturn">
                    <option value="100">100</option>
                    <option value="250">250</option>
                    <option value="500">500</option>
                    <option value="-1">All</option>                    
                    
                </select>
            </div>            
            
            
            <div class='col-md-2'>
                <button class="btn btn-default btn-sm" onclick="hideFilter();
                        return false;"><i class='fa fa-bars' style="padding-top: 4px"></i> </button>
                <button class="btn btn-default btn-sm" onclick="dsMainObject.loadGantt();
                        return false;"><i class='fa fa-refresh' style="padding-top: 4px"></i> </button>
                <button class="btn btn-default btn-sm" onclick="dsMainObject.collapseTimeLine();
                        return false;" ><i id='expandbutton' class='fa fa-expand' style="padding-top: 4px"></i> </button>
            </div>


            <div class='col-md-1 no-padding itemContentBuildPlan' style='text-align: center; margin-right: 5px;height: 28px'>
                Planned
                <input type="checkbox" class="chkview" id="fl_planned" style="margin-left: 5px;" checked >            
            </div>

            <div class='col-md-1 no-padding itemContentBuildAgreed' style='text-align: center;;height: 28px'>
                Agreed
                <input type="checkbox" class="chkview" id="fl_agreed" style="margin-left: 5px;" >
            </div>

            <div class='col-md-1 no-padding itemContentBuildComplete' style='text-align: center;;height: 28px'>
                Completed
                <input type="checkbox" class="chkview" id="fl_completed" style="margin-left: 5px;" >
            </div>
        </div>

    </div>

    <div class='row'>
        <div class="col-md-12">
            <div id="ganttTimelineArea" style="background-color: white"></div>
        </div>
    </div>
</div>






