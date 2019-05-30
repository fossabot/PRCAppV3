<style>

</style>

<?php
$sqlPrd = ' AND EXISTS (SELECT 1 FROM "PROJECT_PRODUCT_X_PROJECT_POWER_TYPE"   x WHERE x.cd_project_power_type = "PROJECT_POWER_TYPE".cd_project_power_type and x.cd_project_product = %s AND x.dt_deactivated IS NULL) ';
$sqlPwd = ' AND EXISTS (SELECT 1 FROM "PROJECT_POWER_TYPE_X_PROJECT_TOOL_TYPE" x WHERE x.cd_project_tool_type = "PROJECT_TOOL_TYPE".cd_project_tool_type and x.cd_project_power_type = %s AND x.dt_deactivated IS NULL ) ';

$sqlPrd = $this->cdbhelper->getFilterQueryId($sqlPrd);
$sqlPwd = $this->cdbhelper->getFilterQueryId($sqlPwd);
//setPLRelCode
?>

<script>
    // aqui tem os scripts basicos.
    //var controllerName = "country";


    String.prototype.replaceAll = function (search, replacement) {
        var target = this;
        return target.replace(new RegExp(search, 'g'), replacement);
    };

    //$(".ds_hr_type").on( "change",  function() {
    var dsFormPrjSheetObject = new function () {
        // variaveis privadas;


        var thisObj = this;
        thisObj.gridName = undefined;
        thisObj.action = '<?php echo($action); ?>'
        thisObj.lastPrjCode = -20;
        thisObj.gridsAvailable = [];
        thisObj.toolbarCreated = [];
        thisObj.cd_project_model = <?php echo($cd_project_model) ?>;
        thisObj.cd_user = '<?php echo($cd_human_resource) ?>';
        thisObj.ds_user = '<?php hecho($ds_human_resource) ?>';
        thisObj.lastSchCode = -20;
        thisObj.gridWO = <?php echo($gridWO) ?>;
        thisObj.planningLoadData = [];
        thisObj.resizeOnCollapse = true;
        thisObj.resizeTimeout = undefined;

        thisObj.gridWOList = [];
        thisObj.canChangeDetail = '<?php hecho($canChangeDetail) ?>';


        thisObj.existingBuilds = [];
        thisObj.funcQueue = [];

        thisObj.duplicatingCode = -1;
        thisObj.duplicateColumns = ['ds_test_type',
            'ds_test_item',
            'ds_tests',
            'ds_specification',
            'ds_sample_description',
            'ds_extra_instruction',
            'fl_witness',
            'nr_sample_quantity',
            'nr_charger_quantity',
            'nr_power_pack_quantity',
            'nr_accessory_qty',
            'nr_goal',
            'nr_output',
            'fl_eol',
            'ds_location'

        ];

        thisObj.builds = <?php echo($builds) ?>;

        thisObj.fieldsToHide = ['ds_project_form', 'ds_human_resource_prc_pm_form', 'ds_human_resource_eng_form', 'ds_tti_project_form', 'ds_met_project_form', 'ds_project_tool_type_form']
<?php echo($gridCHK); ?>
<?php echo($gridAttachment); ?>



        this.start = function () {

<?php echo($gridPlanning); ?>

            thisObj.gridWOTRMTE = <?php echo($gridWOTRMTE) ?>;


<?php
echo($toolbar);
echo($PrjModelGrid);
echo($gridAttachmentMain);
echo($gridDates);
echo($toolbarTests);
?>



            $().w2grid(vGridDates);
            var vtoolbar = vGridToToolbarPrj.toolbar;

            vtoolbar.onClick = function (a, b) {
                if (a == 'update') {
                    thisObj.updateData();
                }

                if (a == 'downtemp') {
                    window.open('tti/project/downloadMatrixTemplate', '_blank');
                }


                if (a.substr(0, 12) == 'addNewBuild:') {
                    var vbuild = a.split(".")[1];
                    thisObj.addNewBuild(vbuild);
                }
                if (a.substr(0, 5) == 'goto:') {
                    var vschbuild = a.split(".")[1];
                    thisObj.goToBuild(vschbuild);
                }

                if (a == 'openPurFromWI') {

                    thisObj.openPurFromWIForm();
                }

                if (a == 'expand') {
                    $('#prjScheduleArea').find('.box.collapsed-box').find("[data-widget='collapse']").click();
                }

                if (a == 'collapse') {
                    $('#prjScheduleArea').find('.box').not('.collapsed-box').find("[data-widget='collapse']").click();
                }


            };

            this.openPurFromWIForm = function () {
                // var vprj =ds_project_model
                // var vprjmodel = thisObj.Form.getItem('cd_project_model_form');
                var title = 'Project:' + thisObj.Form.getItem('ds_met_project_form') + '/' + 'Model:' + thisObj.Form.getItem('ds_met_project_model_form');
                openFormUiBootstrap(
                        title,
                        'tti/project/openPurFromWIForm/' + thisObj.cd_project_model,
                        'col-lg-12 col-lg-offset-0 col-sm-12 col-sm-offset-0'
                        );

            }

            vtoolbar.name = 'PrjFormToolbar';

            if (w2ui['PrjFormToolbar'] != undefined) {
                w2ui['PrjFormToolbar'].destroy();
            }

            $('#prjToolbar').w2toolbar(vtoolbar);

            thisObj.toolbarCreated.push(vtoolbar.name);


            thisObj.Form = $('#formPrj').cgbForm({
                updController: 'tti/project',
                boxToLock: '.wrapper',
                checkDemandedForInvisible: true
            });


            //console.timeEnd('form');


            this.setScreenPermissions();


            this.addListeners();
            this.addHelper();

            thisObj.gridsAvailable.push('gridMainAttachment');
            thisObj.gridsAvailable.push('gridPrjComments');
            thisObj.gridsAvailable.push('gridPrjRoles');

            thisObj.Form.addGridToControl('gridMainAttachment');
            thisObj.Form.addGridToControl('gridPrjComments');
            thisObj.Form.addGridToControl('gridPrjRoles');
            thisObj.Form.addGridToControl('gridDates');


            thisObj.buildThumbs();


            //this.resizeGrid();

            //thisObj.renderCalendar();

<?php if ($fl_confidential == '1') { ?>
                $('#gridPrjModel').find('.box-tools').prepend('<span data-toggle="tooltip" title="" class="badge bg-red" data-original-title=""><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <?php echo($confidentialtitle); ?> <i class="fa fa-exclamation-triangle" aria-hidden="true"></i></span>');

<?php } ?>


        }


        thisObj.goToBuild = function (schbuild) {
            var id = '#buildScheduleArea' + schbuild;
            var idBottom = '#buildScheduleAreaBottom' + schbuild;
            var vbid = '#prjbuild' + schbuild + 'div';

            $(vbid).find('.box.collapsed-box').removeClass('collapsed-box');
            thisObj.resizeGrid();


            setTimeout(function () {
                var vxid = '';
                if ($(vbid).height() < 400) {
                    vxid = idBottom;
                } else {
                    vxid = id;
                }
                ;

                console.log($(vbid).height(), vxid);

                $('#PrjscrollPart').cgbMakeScrollbar('scrollToY', $(vxid));
            }, 0);
        }

        this.addNewBuild = function (build) {
            var vprj = thisObj.Form.getItem('cd_project_form');
            var vprjmodel = thisObj.Form.getItem('cd_project_model_form');


            var vclass = '.prjBuildClass';
            var vprjarea = '#prjScheduleArea';

            $.myCgbAjax({
                url: 'tti/project/addNewBuild/' + vprj + '/' + vprjmodel + '/' + build,
                success: function (data) {
                    $('body').append(data.html);
                    var vid = '#prjbuild' + data.pk + 'div';

                    var vinto = vprjarea;
                    var vfound = false;
                    $.each($(vprjarea + ' ' + vclass), function () {
                        var vorder = $(this).attr('buildorder');
                        if (vorder > data.order) {
                            vinto = $(this);
                            vfound = true;
                            return false;
                        }

                        //console.log('faz', vorder);
                    });

                    if (vfound) {
                        $(vid).detach().insertBefore(vinto);
                    } else {
                        $(vinto).append($(vid).detach());
                    }
                    $(vid).find("[data-widget='collapse']").click();
                    $('#PrjscrollPart').cgbMakeScrollbar('scrollToY', $(vid).position().top);

                    thisObj.Form.addNewElements();
                    thisObj.makeBuildMenu();
                    thisObj.runFuncQueue();


                }
            });
        }


        this.makeBuildMenu = function () {

            var vmenu = [{
                    type: 'menu',
                    id: 'addNewBuild',
                    text: '<?php echo($newbuild) ?>',
                    icon: 'fa fa-plus',
                    items: []
                },
                {type: 'menu', id: 'goto', text: '<?php echo($goto) ?>', icon: 'fa fa-share', items: []}
            ];
            $.each(thisObj.builds, function (i, v) {
                if (v.fl_allow_multiples == 1 || $.inArray(v.cd_project_build, thisObj.existingBuilds) == -1) {
                    vmenu[0].items.push({
                        text: v.ds_project_build.toLowerCase().capitalize(),
                        id: 'add.' + v.cd_project_build
                    });
                }
            });

            $.each($('.prjBuildClass'), function () {
                var title = $(this).find('.VerticalText').text();
                var schbuild = $(this).attr('schbuild');
                vmenu[1].items.push({text: title, id: 'go.' + schbuild});

            });


            w2ui['PrjFormToolbar'].remove('addNewBuild');
            w2ui['PrjFormToolbar'].remove('goto');
            w2ui['PrjFormToolbar'].add(vmenu);

        }

        this.resize = function () {
            var hAvail = getWorkArea();
            $('#PrjscrollPart').css('height', hAvail - 55);
            $('#PrjscrollPart').cgbMakeScrollbar('resize', hAvail - 85);
            //$('.calData .fc').fullCalendar('option', 'height', 60);

            this.resizeGrid();
        }


        this.addHelper = function () {
            var arrayHelper = [];
            //$.merge(arrayHelper, introAddFilterArea());
            //$.merge(arrayHelper,w2ui[thisObj.gridName].toolbar.getIntroHelp());
            //$.merge(arrayHelper, w2ui[thisObj.gridName].getIntroHelp());

            //introAddNew({steps: arrayHelper});
        }


        this.updatePlanningTabQty = function (sch) {
            var vqty = w2ui['gridPlanData_' + sch].records.length;
            $('#tab_test_overview_' + sch).find('span').text(vqty);
        }

        // adicao de listeners!
        this.addListeners = function () {

            $('#gridPrjModel').on('lte.uncollapsed-box', function () {
                thisObj.resizeGrid();
            })
            $('#areaPrjAttachments').on('lte.uncollapsed-box', function () {
                thisObj.resizeGrid();
            })


            $('#myCarousel').on('slid.bs.carousel', function () {
                var vrecid = $('.carousel-indicators').find('.active').attr('recid');

                w2ui['gridMainAttachment'].ScrollToRow(vrecid);

            });

            w2ui['gridMainAttachment'].on('itemChanging', function (a) {
                if (a.colname == 'fl_main') {
                    $.each(w2ui['gridMainAttachment'].records, function (i, v) {
                        if (v.recid != a.data.recid) {
                            w2ui['gridMainAttachment'].setItem(v.recid, 'fl_main', 0);
                        }
                    })
                }
            });


            w2ui['gridPrjComments'].on('gridChanged', function (a) {
                thisObj.setDemandedAsChanged();
            });

            w2ui['gridPrjComments'].on('pickList', function (event) {

                //console.log(event);

                if (event.columnname == "ds_comments") {

                    thisObj.openProjectComment(event.recid, chkUndefined(w2ui['gridPrjComments'].getItem(event.recid, 'cd_project_comments_answer'), -1), 'gridPrjComments');

                    event.preventDefault();

                }
            });


            w2ui['gridMainAttachment'].on('rowFocusChanging', function (a) {
                var v = $('.carousel-indicators').find("[recid='" + a.recid_new + "']").attr('data-slide-to');
                if (v != undefined) {
                    $('#myCarousel').carousel(parseInt(v));
                }
            })

            w2ui['gridPrjRoles'].on('pickList', function (a) {

                a.onComplete = function (b) {

                    if (b.columnname == "ds_human_resource" && b.newCode != -1) {
                        w2ui['gridPrjRoles'].setItem(b.recid, 'ds_e_mail', b.dataRec.ds_e_mail);
                        if (chkUndefined(b.dataRec.cd_roles, -1) != -1) {
                            w2ui['gridPrjRoles'].setItem(b.recid, 'cd_roles', b.dataRec.cd_roles);
                            w2ui['gridPrjRoles'].setItem(b.recid, 'ds_roles', b.dataRec.ds_roles);

                        }

                    }


                }


            })


            $(window).on('updateDocRep', function () {

                thisObj.loadImgAttachments();
            });

            $('#formPrj').on('pospicklist', function (ev) {
                /*
                 if (ev.fielddata.codefield == 'cd_tests') {
                 var vUnit = 'ds_test_unit_' + ev.fielddata.order + '_form';
                 if (ev.newCode == -1) {
                 thisObj.Form.setItemPL(vUnit, -1, '');
                 } else {
                 thisObj.Form.setItemPL(vUnit, ev.record.cd_test_unit, ev.record.ds_test_unit);
                 }
                 }*/

                if (ev.fielddata.codefield == 'cd_project_product') {
                    thisObj.Form.setItemPL('ds_project_power_type_form', -1, '');
                    thisObj.Form.setItemPL('ds_project_tool_type_form', -1, '');
                }

                if (ev.fielddata.codefield == 'cd_project_power_type') {
                    thisObj.Form.setItemPL('ds_project_tool_type_form', -1, '');
                }

                if (ev.fielddata.addinfo == 'PLAN') {
                    thisObj.setItemPlanning(ev.fielddata.indexRS, ev.fielddata.order, ev.fielddata.name, ev.newDesc, ev.newCode);
                }


            });

            $('#formPrj').on('prepicklist', function (ev) {
                if (ev.fielddata.codefield == 'cd_project_power_type') {
                    var vcode = chkUndefined(thisObj.Form.getItemPLCode('ds_project_product_form'), -1);
                    thisObj.Form.setPLRelCode('ds_project_power_type_form', vcode);
                    ev.options.relation.id = vcode;
                }

                if (ev.fielddata.codefield == 'cd_project_tool_type') {
                    var vcode = chkUndefined(thisObj.Form.getItemPLCode('ds_project_power_type_form'), -1);
                    thisObj.Form.setPLRelCode('ds_project_tool_type_form', vcode);
                    ev.options.relation.id = vcode;
                }

            });


            $('#formPrj').on('itemChanged', function (ev) {
                if (ev.fielddata.name == 'dt_est_start' || ev.fielddata.name == 'dt_est_finish') {
                    thisObj.calculateWorkDays();
                }
                if (ev.fielddata.name == 'dt_start' || ev.fielddata.name == 'dt_finish') {
                    thisObj.calculateWorkDaysAgreed();
                }

                if (ev.fielddata.name == 'dt_actual_start' || ev.fielddata.name == 'dt_actual_finish') {
                    thisObj.calculateWorkDaysActual();
                }

                var vf = ev.fielddata.name;
                thisObj.setDemandedAsChanged();


                if (ev.fielddata.addinfo == 'PLAN') {
                    thisObj.setItemPlanning(ev.fielddata.indexRS, ev.fielddata.order, ev.fielddata.name, ev.new);
                }

            });

            $('#formPrj').on('afterUpdate', function (ev) {
                thisObj.action = 'E';
                $(window).trigger('prjChanged', ev.fullData);


                $.each(thisObj.gridsAvailable, function (i, v) {
                    if (w2ui[v] != undefined) {
                        w2ui[v].destroy();
                    }
                });

                $.each(thisObj.toolbarCreated, function (i, v) {
                    if (w2ui[v] != undefined) {
                        w2ui[v].destroy();
                    }
                });

                thisObj.planningLoadData = [];

                dsMainObject.loadDetails(<?php echo($cd_project . ',' . $cd_project_model) ?>, true);


            });

        }

        this.remove = function (async) {
            w2ui['gridDates'].destroy();
            thisObj.Form.destroy(async);
            $(window).off('updateDocRep');
        }

        // roda antes de fechar (se retornar FALSE o sistema vai perguntar se quer fechar
        this.beforeClose = function () {
            return true;
        }


        // close object (lugar para destruir as coisas//
        this.close = function () {
            //introRemove();

            return true;
        }


        this.updateData = function () {
            thisObj.Form.updateForm();
        }


        this.renderCalendar = function () {
            $(".calcButton").not('loaded').addClass('loaded').droppable({
                drop: function (event, ui) {
                    var vsch = $(event.target).attr('sch');
                    var vds = chkUndefined(thisObj.Form.getItem('dt_est_start_' + vsch + '_form'), '');
                    var vdf = chkUndefined(thisObj.Form.getItem('dt_est_finish_' + vsch + '_form'), '');
                    var vId = '#calendar_' + vsch + '_div';
                    var vevents = $(vId).fullCalendar('clientEvents');
                    var events = {};
                    var eventsIdx = 0;
                    var vdatelast;

                    if (vds == '' || vdf == '') {
                        return;
                    }

                    vds = moment(vds, defaultDateFormatUpper);
                    var vdiff = thisObj.Form.getItem('nr_days_' + vsch + '_form')

                    if (vdiff < 0) {
                        return;
                    }
                    vdiff--;

                    var vdata = JSON.parse($(ui.draggable).attr('data-event'));
                    $.each(vevents, function (i, v) {
                        thisObj.deleteCalendarEvent(v, vId);
                    })

                    var eventList = [];
                    events.recid = thisObj.lastSchCode;
                    events.id = thisObj.lastSchCode;
                    events.start = vds.format('YYYYMMDD');
                    events.end = vds.format('YYYYMMDD');
                    events.nr_workers = Math.abs(vdata.id);
                    events.cd_project_build_schedule_tests = vsch;
                    events.allDay = true;
                    events.title = Math.abs(vdata.id) + ' Worker(s) ';

                    for (var i = 0; i <= vdiff; i++) {
                        if (vds.isoWeekday() == 7) {
                            events.end = vds.subtract(1, 'd').format('YYYYMMDD');
                            eventList.push($.extend({}, events));
                            i++;
                            thisObj.lastSchCode--;
                            vds = vds.add(1, 'd');
                            events.recid = thisObj.lastSchCode;
                            events.id = thisObj.lastSchCode;
                            events.start = vds.format('YYYYMMDD')
                        }

                        vds.add(1, 'd');
                    }

                    events.end = vds.subtract(1, 'd').format('YYYYMMDD');
                    eventList.push($.extend({}, events));

                    thisObj.addToCalendar(eventList, $(vId));
                    thisObj.positionCalendarOnFirst(vsch)
                    i++;
                    thisObj.lastSchCode--;
                }
            });


            $('.calData').not('.fc').fullCalendar({
                defaultView: 'basicWeek',
                height: 60,
                header: false,
                //selectable: true,
                editable: true,
                eventOverlap: false,
                droppable: true,
                //allDay: true,
                //weekNumbers: true,
                /*eventClick: function (calEvent, jsEvent, view) {
                 //console.log('cliecked', calEvent, jsEvent, view);
                 },*/
                eventDrop: function (event, delta, revertFunc, jsEvent, ui, view) {
                    thisObj.updateCalendar(event);

                    //console.log('dropped', vdays, dStart, dEnd);
                },
                eventResize: function (event, delta, revertFunc, jsEvent, ui, view) {
                    thisObj.updateCalendar(event);

                },
                /*select: function (start, end, jsEvent, view) {
                 //console.log('Select', start, end, jsEvent, view);
                 },*/
                drop: function (date, ev, ev2) {
                    //console.log("Dropped on " + date.format(), ev, ev2);
                },
                eventReceive: function (event, x) {
                    var $vCal = $(x.el).parent().parent();
                    var vtst = [{
                            id: thisObj.lastSchCode,
                            recid: thisObj.lastSchCode,
                            title: Math.abs(event.id) + ' Worker(s)',
                            start: event.start,
                            end: event.start,
                            nr_workers: Math.abs(event.id),
                            cd_project_build_schedule_tests: $vCal.attr('sch')
                        }];

                    $vCal.fullCalendar('removeEvents', event.id);
                    setTimeout(function () {
                        thisObj.addToCalendar(vtst, $vCal);
                    }, 0);

                    //console.log(event);
                    thisObj.lastSchCode--;


                },
                eventRender: function (event, element, x) {
                    element.contextmenu(function () {
                        return false;
                    });

                    //element.find('.fc-time').remove();


                    element.bind('mouseup', function (e) {
                        if (e.which == 3) {
                            var $vCal = $(x.el).parent().parent();
                            setTimeout(function () {
                                thisObj.removeEvent(event, element, $vCal);
                            }, 0);

                        }
                    });
                }
            });


            $('.divDrag').not('.loaded').addClass('loaded').draggable({
                revert: true, // immediately snap back to original position
                revertDuration: 0  //
            });


            $('#tab_detail_div').find('[data-toggle="tooltip"]').tooltip({container: 'body'});

        }


        this.setScreenPermissions = function () {
            var vdr = thisObj.Form.getItem("fl_draft_form");
            if (vdr == 0) {
                $.each(thisObj.fieldsToHide, function (i, v) {
                    thisObj.Form.setEnabled(v, false);
                })
            }

            if (thisObj.action == 'I') {

            } else {

            }


        }

        this.resizeInsideDivGrids = function (vdiv) {
            $('#' + vdiv).find('.w2ui-grid').each(function () {
                var vn = $(this).attr('name');
                w2ui[vn].resize();
            });
        }

        this.resizeGrid = function (vfindsomethings) {

            w2ui['gridPrjRoles'].resize();
            w2ui['gridPrjComments'].resize();
            w2ui['gridMainAttachment'].resize();
            $('.calData:visible').fullCalendar('render');
            var $prj = $('#prjScheduleArea');

            $.each(thisObj.gridsAvailable, function (i, v) {

                if (vfindsomethings != undefined) {
                    if (v.indexOf(vfindsomethings) == -1) {
                        return;
                    }
                }


                var isv = $prj.find('[name="' + v + '"]').is(":visible");
                // only resize the visible ones.
                if (isv) {
                    w2ui[v].resize();
                }
            });

            $.each(thisObj.gridWOList, function (i, v) {

                if (vfindsomethings != undefined) {
                    if (v.indexOf(vfindsomethings) == -1) {
                        return;
                    }
                }

                var $div = $prj.find('[name="' + v + '"]');
                var isv = $div.is(":visible");
                // only resize the visible ones.
                if (isv) {
                    w2ui[v].resize();
                }
            });


        }

        this.setGridAsChanged = function () {

        }

        this.setDemandedAsChanged = function () {
            var vp = thisObj.Form.getItem('cd_project_form');
            thisObj.Form.setItem('cd_project_form', vp);
            thisObj.Form.setItem('cd_project_model_form', thisObj.Form.getItem('cd_project_model_form'));
        }

        this.getPlanningData = function () {
            return thisObj.planningLoadData;
        }

        this.tabBuildAfterChanged = function (newTab, oldTab) {

            thisObj.resizeInsideDivGrids(newTab + '_div');
            if (newTab.substring(0, 17) == 'tab_test_overview') {

                var sp = newTab.split('_');
                var vb = sp[sp.length - 1];

                var vover = chkUndefined($('#' + newTab + '_div').attr('loaded'), 'N');

                if (vover == 'N') {
                    $.myCgbAjax({
                        url: 'tti/project/loadPlanning/' + vb,
                        message: javaMessages.loading,
                        success: function (data) {

                            $('#' + newTab + '_div').attr('loaded', 'Y');

                            if (data.pk == -1) {
                                data.gridData = '[]';
                                data.html = '<div class="schtstdiv"></div>';
                            }

                            // added because after saving the return would click on tab to show the planning before the grid was recreated.
                            //while (w2ui['gridPlanData_' + vb] == undefined);

                            thisObj.planningLoadData['mod' + vb] = {
                                htmlLoaded: false,
                                html: $('<div id="xx">' + data.html + '</div>')
                            };
                            w2ui['gridPlanData_' + vb].clear();
                            w2ui['gridPlanData_' + vb].resize();
                            w2ui['gridPlanData_' + vb].add(JSON.parse(data.gridData));


                            //$('#divSchTst_' + vb).append(data.html);
                            //thisObj.Form.addNewElements();
                            thisObj.setPlanningWOColumn(vb);
                            thisObj.showPlanning(vb, false);
                            thisObj.loadPlanGrid(vb);

                        }
                    });
                }

            }

            //w2ui['gridWOTRMTE_' + vb].retrieve({retrFunc: 'getMETData/' + vb})
            //console.log('overview', sp, vb);


            if (newTab.substring(0, 16) == 'tab_test_request') {
                var sp = newTab.split('_');
                var vb = sp[sp.length - 1];
                w2ui['gridWOTRMTE_' + vb].retrieve({retrFunc: 'getMETData/' + vb});
            }


            if (newTab.substring(0, 12) == 'tab_schedule') {

            }


        }

        //

        this.setAreaScheduleNow = function (cdprjbuildsch, hasChk, chkdata, cmtdata, cdbuild) {

            var vd = '#divSchDetails_' + cdprjbuildsch;
            var vt = '#tab_schedule_' + cdprjbuildsch + "_div";
            var vtt = '#tab_test_overview_' + cdprjbuildsch + "_div";
            var vx = '#tabBuildSchedule_' + cdprjbuildsch + "_div";
            var vtst = '#divSchTst_' + cdprjbuildsch;

            $(vx).ctabStart({afterChanged: thisObj.tabBuildAfterChanged});//({afterChanged: thisObj.tabAfterChanged, beforeChange: thisObj.tabBeforeChanged});
            $(vt).append($(vd).detach());

            $(vtt).append($(vtst).detach());
            thisObj.existingBuilds.push(cdbuild);

            $('#buildScheduleArea' + cdprjbuildsch).on('lte.uncollapsed-box', function () {
                // little control to make sure it runs only once, even if I open all together.
                clearTimeout(thisObj.resizeTimeout);
                thisObj.resizeTimeout = setTimeout(function () {
                    thisObj.resizeGrid();
                }, 0);

            });
        }

        this.gridPlanningSearch = function (this1) {
            var vname = 'gridPlanData_' + $(this1).attr('sch');
            console.log(vname, 'xxx');

            var val = this1.value;
            var fld = $(this1).data('w2field');
            var dat = $(this1).data('selected');
            if (fld)
                val = fld.clean(val);
            if (dat != null && $.isPlainObject(dat))
                val = dat.id;
            w2ui[vname].search(w2ui[vname].last.field, val);
        };

        this.setAreaScheduleQueue = function (cdprjbuildsch, hasChk, chkdata, cmtdata, cdbuild, recattach) {
            var vd = '#divSchDetails_' + cdprjbuildsch;
            var vt = '#tab_schedule_' + cdprjbuildsch + "_div";
            var vtt = '#tab_test_overview_' + cdprjbuildsch + "_div";
            var vx = '#tabBuildSchedule_' + cdprjbuildsch + "_div";
            var vtst = '#divSchTst_' + cdprjbuildsch;
            var vdeac = chkUndefined(thisObj.Form.getItem('dt_deactivated_schedule_' + cdprjbuildsch + '_form'), '');


            // Checklist
            if (hasChk == 'Y') {
                var vname = 'gridCheckpoint_' + cdprjbuildsch;
                if (w2ui[vname] != undefined) {
                    w2ui[vname].destroy();
                    //vname = undefined;
                }
                var vg = $.extend({}, vGridCheckpoints);
                vg.name = vname;
                vg.records = chkdata;
                vg.cdbuildsch = cdprjbuildsch;
                $('#gridCheckPoint_' + cdprjbuildsch + "_div").w2grid(vg);
                w2ui[vname].on('gridChanged', function (a) {
                    thisObj.setDemandedAsChanged();
                });
                thisObj.Form.addGridToControl(vname);
                thisObj.gridsAvailable.push(vname);
                if (vdeac != '') {
                    w2ui[vname].readOnly();
                }

            }


            var vname = 'gridAttachment_' + cdprjbuildsch;
            if (w2ui[vname] != undefined) {
                w2ui[vname].destroy();
                //vname = 
            }
            vg = $.extend({}, vGridAttachment);
            vg.name = vname;
            //vg.records = chkdata;
            vg.cdbuildsch = cdprjbuildsch;
            vg.records = recattach;

            $('#gridAttachment_' + cdprjbuildsch + "_div").w2grid(vg);
            thisObj.Form.addGridToControl(vname);
            thisObj.gridsAvailable.push(vname);
            if (vdeac != '') {
                w2ui[vname].readOnly();
            }

            var vname = 'gridPrjBuildComments_' + cdprjbuildsch;
            if (w2ui[vname] != undefined) {
                w2ui[vname].destroy();
                //vname = 
            }
            vg = $.extend({}, vGridBuildComments);
            vg.name = vname;
            vg.records = cmtdata;
            vg.cdbuildsch = cdprjbuildsch;

            $('#gridCommentsBuild_' + cdprjbuildsch + "_div").w2grid(vg);

            w2ui[vname].on('gridChanged', function (a) {
                thisObj.setDemandedAsChanged();
            });

            w2ui[vname].on('pickList', function (event) {
                var thisG = this;

                if (event.columnname == "ds_comments") {

                    thisObj.openProjectBuildComment(event.recid, chkUndefined(w2ui[this.name].getItem(event.recid, 'cd_project_build_schedule_comments_answer'), -1), this.name);

                    event.preventDefault();

                }


            });


            thisObj.Form.addGridToControl(vname);
            thisObj.gridsAvailable.push(vname);
            if (vdeac != '') {
                //w2ui[vname].readOnly();
            }


            // test toolbar
            var vt = $.extend({}, vGridToToolbarTest.toolbar);
            vt.name = 'toolbarTest_' + cdprjbuildsch;
            vt.cdbuildsch = cdprjbuildsch;

            thisObj.toolbarCreated.push(vt.name);

            var vname = 'gridPlanningData' + cdprjbuildsch;



            $.each(vt.items, function (i, v) {

                if (v.id == 'searchGrid') {
                    v.type = 'html';
                    v.html = '<input id="gridPlanningDataSearch' + cdprjbuildsch + '" class="w2ui-search-all" sch="' + cdprjbuildsch + '" placeholder="Filter" value="" onchange="dsFormPrjSheetObject.gridPlanningSearch(this)">';
                    vt.items[i] = v;
                }
                if (v.id == 'shiftDays') {
                    v.type = 'html';
                    v.html = '<input type="number" value="7" id="daystoShift' + cdprjbuildsch + '" step="1" style = "width: 50px; height: 24px; text-align: right ">';
                    vt.items[i] = v;
                }
                return true;
            })




            vt.onClick = function (a, b) {

                if (a == 'insert') {
                    thisObj.insertTestData(cdprjbuildsch);
                }

                if (a == 'shiftRun') {
                    thisObj.shiftDatesAsk(cdprjbuildsch);
                }

                if (a == 'showAsGrid') {

                    setTimeout(function () {
                        thisObj.loadPlanGrid(cdprjbuildsch);
                    }, 0);

                }


                if (a == 'expexcel') {
                    w2ui['gridPlanData_' + cdprjbuildsch].exportTo(1, 'planningExp');
                }


                if (a == 'uploadMatrix') {
                    $('#schdata').val(cdprjbuildsch);
                    $('#fileupload').click();

                }

                if (a.substr(0, 9) == 'copyFrom:') {
                    var vd = a.split('_');
                    $.myCgbAjax({
                        url: 'tti/project/copyFromTests/' + cdprjbuildsch + '/' + vd[1],
                        success: function (data) {


                            if (thisObj.planningLoadData['mod' + cdprjbuildsch].htmlLoaded) {
                                $('#divSchTst_' + cdprjbuildsch).append(data.html);
                                thisObj.Form.addNewElements();
                                thisObj.setAreaTest(data.pk, cdprjbuildsch, []);
                                thisObj.runFuncQueue();
                                var $d = $('#testArea_' + data.pk);
                                $('#PrjscrollPart').cgbMakeScrollbar('scrollToY', $d);
                                thisObj.renderCalendar();
                            } else {
                                thisObj.planningLoadData['mod' + cdprjbuildsch].html.find('.schtstdiv').last().append(data.html);
                            }

                            data.gridData = JSON.parse(data.gridData);
                            w2ui['gridPlanData_' + cdprjbuildsch].add(data.gridData)
                            $.each(data.gridData, function (i, v) {
                                w2ui['gridPlanData_' + cdprjbuildsch].setItem(v.recid, 'cd_project_build_schedule_tests', v.recid);
                                w2ui['gridPlanData_' + cdprjbuildsch].setItem(v.recid, 'recid', v.recid);
                                w2ui['gridPlanData_' + cdprjbuildsch].setItem(v.recid, 'cd_project_build_schedule', cdprjbuildsch);
                                w2ui['gridPlanData_' + cdprjbuildsch].newRecids.push(v.recid);
                                w2ui['gridPlanData_' + cdprjbuildsch].setItemAsChanged(v.recid);
                            });

                            thisObj.showPlanning(cdprjbuildsch, false);
                            thisObj.updatePlanningTabQty(cdprjbuildsch);

                        }
                    });


                }
            };

            if (w2ui[vt.name] != undefined) {
                w2ui[vt.name].destroy();
                //vname = 
            }
            thisObj.toolbarCreated.push(vt.name);

            $('#schTstToolbar_' + cdprjbuildsch).w2toolbar(vt);
            if (vdeac != '') {
                $.each(vt.items, function (i, v) {
                    if (v.id != 'showAsGrid') {
                        w2ui[vt.name].hide(v.id);
                    }
                });

            }


            // grid WO TR MTE
            var vg = $.extend({}, thisObj.gridWOTRMTE);

            var vname = 'gridWOTRMTE_' + cdprjbuildsch;
            if (w2ui[vname] != undefined) {
                w2ui[vname].destroy();
                //vname = undefined;
            }
            var vdiv = '#tab_test_request_' + cdprjbuildsch + '_div';

            vg.name = vname;
            vg.cdbuildsch = cdprjbuildsch;
            $(vdiv).height(350).w2grid(vg);
            thisObj.gridsAvailable.push(vname);
            if (vdeac != '') {
                w2ui[vname].readOnly();
            }

            // grid data

            var vg = $.extend({}, vPlanningGrid);
            var vname = 'gridPlanData_' + cdprjbuildsch;
            if (w2ui[vname] != undefined) {
                w2ui[vname].destroy();
                //vname = undefined;
            }
            var vdiv = '#gridPlanningData_' + cdprjbuildsch + '_div';

            vg.name = vname;
            vg.cdbuildsch = cdprjbuildsch;
            $(vdiv).w2grid(vg);
            thisObj.Form.addGridToControl(vname);


            w2ui[vname].on('itemChanging', function (ev) {

                var sch = this.getItem(ev.data.recid, 'cd_project_build_schedule');
                var vcol = ev.colname + '_' + ev.data.recid + '_form';

                if (thisObj.planningLoadData['mod' + sch].htmlLoaded) {
                    thisObj.Form.setItem(vcol, ev.data.value_new);
                    if (ev.colname == 'dt_est_start' || ev.colname == 'dt_est_finish') {
                        thisObj.calculateWorkDays();
                    }
                    if (ev.colname == 'dt_start' || ev.colname == 'dt_finish') {
                        thisObj.calculateWorkDaysAgreed();
                    }

                    if (ev.colname == 'dt_actual_start' || ev.colname == 'dt_actual_finish') {
                        thisObj.calculateWorkDaysActual();
                    }


                } else {
                    thisObj.planningLoadData['mod' + sch].html.find('#' + vcol).val(ev.data.value_new);
                }


                var vThis = this;
            });

            w2ui[vname].on('pickList', function (ev) {

                ev.onComplete = function (e) {


                    var sch = this.getItem(e.recid, 'cd_project_build_schedule');
                    var vcol = e.columnname + '_' + e.recid + '_form';

                    if (thisObj.planningLoadData['mod' + sch].htmlLoaded) {
                        // text area
                        if (e.columns.internaltype == 13) {
                            thisObj.Form.setItem(vcol, e.newDesc);
                        }

                        if (e.columns.internaltype == 10) {
                            thisObj.Form.setItemPL(vcol, e.dataRec.recid, e.dataRec.description);
                        }


                    } else {
                        if (e.columns.internaltype == 13) {
                            thisObj.planningLoadData['mod' + sch].html.find('#' + vcol).val(e.newDesc);
                        }

                        // picklist
                        if (e.columns.internaltype == 10) {
                            thisObj.planningLoadData['mod' + sch].html.find('#' + vcol).val(e.dataRec.description);
                            thisObj.planningLoadData['mod' + sch].html.find('#' + vcol).attr('plcode', e.dataRec.recid);
                        }
                    }


                    //
                    //thisObj.Form.setItemPLCode(vcol, e.newCode);
                }

                var vThis = this;
            });


            if (vdeac != '') {
                w2ui[vname].readOnly();
            }

            thisObj.gridsAvailable.push(vname);


        }


        this.shiftDatesAsk = function (cdprjbuildsch, tstNumber) {
            var vdays = $('#daystoShift' + cdprjbuildsch).val();


            var vshDays = 'shift Agreed';
            var vsel = 2; // agreed;


            messageBoxYesNo('Confirm ' + vshDays + ' ' + vdays + ' days ?', function () {
                thisObj.shiftDays(cdprjbuildsch, vsel, vdays, tstNumber);
            });

        };

        thisObj.shiftDays = function (cdprjbuildsch, type, days, tstNumber) {
            var vgrid = w2ui['gridPlanData_' + cdprjbuildsch];
            var vrec = vgrid.records;
            $.each(vrec, function (i, v) {

                if (tstNumber != undefined && vgrid.getItem(v.recid, 'cd_project_build_schedule_tests') != tstNumber) {
                    return true;
                }

                // start
                var vd = chkUndefined(vgrid.getItem(v.recid, 'dt_start'), '');
                if (vd == '') {
                    return true;
                }
                var vdf = moment(vd, defaultDateFormatUpper);
                vdf.add(days, 'days');
                var vdfx = vdf.format(defaultDateFormatUpper);

                thisObj.setItemPlanning(cdprjbuildsch, v.recid, 'dt_start', vdfx);

                // finish
                var vd = chkUndefined(vgrid.getItem(v.recid, 'dt_finish'), '');
                if (vd == '') {
                    return true;
                }
                var vdf = moment(vd, defaultDateFormatUpper);
                vdf.add(days, 'days');
                var vdfx = vdf.format(defaultDateFormatUpper);
                vgrid.setItem(v.recid, 'dt_finish', vdfx);
                thisObj.setItemPlanning(cdprjbuildsch, v.recid, 'dt_finish', vdfx);
            });

            thisObj.loadPlanGrid(cdprjbuildsch);
        }


        this.showPlanning = function (sch, vResize) {
            var bShowHide = w2ui['toolbarTest_' + sch].get('showAsGrid').checked;
            if (bShowHide) {
                $('.schNumberClass' + sch).hide();
                $('#gridPlanningData_' + sch + '_div').show();
                if (vResize) {
                    w2ui['gridPlanData_' + sch].resize();
                }

            } else {
                $('.schNumberClass' + sch).show();

                $('#gridPlanningData_' + sch + '_div').hide();
                if (vResize) {
                    setTimeout(function () {
                        thisObj.resizeGrid();
                    }, 0);
                }
            }
            return bShowHide;
        }

        thisObj.loadPlanGrid = function (sch) {
            var vdata = {};

            var vshowing = thisObj.showPlanning(sch, true);

            $('#gridPlanningData_' + sch + '_div').height(270);
            //w2ui['gridPlanData_' + sch].resize();


            if (!vshowing) {
                if (!thisObj.planningLoadData['mod' + sch].htmlLoaded) {

                    $('#divSchTst_' + sch).append(thisObj.planningLoadData['mod' + sch].html);

                    waitMsgON('body');
                    setTimeout(function () {
                        thisObj.Form.addNewElements();
                        thisObj.planningLoadData['mod' + sch].htmlLoaded = true;
                        thisObj.runFuncQueue();
                        thisObj.renderCalendar();

                        thisObj.calculateWorkDays();
                        thisObj.calculateWorkDaysAgreed();
                        thisObj.calculateWorkDaysActual();
                        waitMsgOFF('body');
                    }, 0);


                }
            } else {
                if (thisObj.planningLoadData['mod' + sch].htmlLoaded) {

                }
            }

        }


        thisObj.deactivateScheduleArea = function (sch) {
            var $area = $('#schDataArea_' + sch).find('input');


            var vdata = {};

            $area.each(function () {
                var vid = $(this).attr('id');
                var vorder = $(this).attr('order');
                var vmask = $(this).attr('mask');

                if (vorder == undefined) {
                    return true;
                }


                thisObj.Form.setEnabled(vid, false);

                w2ui['gridPlanData_' + sch].readOnly();


            });

        }


        this.setAreaTest = function (cdprjbuildschtst, cdprjbuildsch, chkdata) {

            $('#testlistview_' + cdprjbuildsch).cgbMultiNestedList();

            var vname = 'gridTestToolData_' + cdprjbuildschtst;
            if (w2ui[vname] != undefined) {
                w2ui[vname].destroy();
                //vname = undefined;
            }
            var vg = $.extend({}, vGridTestToolData);
            vg.name = vname;
            vg.records = chkdata;
            vg.cdbuildschtst = cdprjbuildschtst;
            vg.cdbuildsch = cdprjbuildsch;

            $('#gridTestToolData_' + cdprjbuildschtst + "_div").w2grid(vg);
            thisObj.Form.addGridToControl(vname);
            thisObj.gridsAvailable.push(vname);

            var vg = $.extend({}, thisObj.gridWO);
            var vname = 'gridWO_' + cdprjbuildschtst;
            if (w2ui[vname] != undefined) {
                w2ui[vname].destroy();
                //vname = undefined;
            }
            var vdiv = '#woarea_' + cdprjbuildschtst + '_div';

            var vdx = chkUndefined(w2ui['gridPlanData_' + cdprjbuildsch].getItem(cdprjbuildschtst, 'wodata'), []);

            if (!$.isArray(vdx)) {
                vdx = JSON.parse(vdx);
            }

            vg.name = vname;
            vg.records = vdx;
            vg.cdbuildschtst = cdprjbuildschtst;
            vg.cdbuildsch = cdprjbuildsch;
            $(vdiv).w2grid(vg);
            thisObj.gridWOList.push(vname);


        }


        // toolbar actions
        this.ToolbarGridComments = function (bPressed, bInfo) {

            var vGridName = $('#' + bInfo.idMenu).closest('.w2ui-grid').attr('name');

            if (bPressed == 'expexcel') {
                w2ui[vGridName].exportTo(1, 'commentsModel');
            }


            if (bPressed == 'insert') {
                this.openProjectComment(-1, -1, vGridName)
            }
            if (bPressed == 'replycom') {
                var vpk = w2ui[vGridName].getPk();
                if (vpk == -1) {
                    messageBoxError("You must select one line");
                    return;
                }
                if (w2ui[vGridName].isNewRow(vpk)) {
                    messageBoxError("Cannot Reply a not saved Comment");
                    return;
                }
                ;

                this.openProjectComment(-1, vpk, vGridName)
            }


            if (bPressed == "delete") {
                var vpk = w2ui[vGridName].getPk();
                var vuser = w2ui[vGridName].getItem(vpk, 'cd_human_resource')

                if (w2ui[vGridName].isNewRow(vpk)) {
                    w2ui[vGridName].deleteRow();
                } else {
                    messageBoxError(javaMessages.delnewonly);
                }
            }

        }


        this.openProjectComment = function (cdcomment, cd_comment_answer, vGridName) {
            var vrow = [];
            var isNew = 'Y';
            if (cdcomment != -1) {
                vrow = gridGetItem(vGridName, cdcomment);
                if (!w2ui[vGridName].isNewRow(cdcomment)) {
                    isNew = 'N';
                }
            }


            basicPickListOpen({
                controller: 'tti/project_comments/openCommentPL/' + thisObj.cd_project_model + '/' + cdcomment + '/' + cd_comment_answer,
                title: 'Comment',
                plVarSuffix: 'att',
                columnClass: 'col-md-10 col-md-offset-1',
                sel_id: -1,
                showTitle: true,
                postParam: {rowdata: JSON.stringify(vrow), isNew: isNew},
                plCallBack: function (vdata) {


                    thisObj.setDemandedAsChanged();

                    if (w2ui[vGridName].get(vdata.recid) != null) {
                        $.each(vdata, function (i, v) {
                            w2ui[vGridName].setItem(vdata.recid, i, v);
                        });
                    } else {
                        w2ui[vGridName].newRecids.push(vdata.recid);
                        w2ui[vGridName].add(vdata, true);
                        $.each(vdata, function (i, v) {
                            w2ui[vGridName].setItemAsChanged(vdata.recid, i);
                        });

                    }


                }
            });


        }


        this.openProjectBuildComment = function (cdcomment, cd_comment_answer, vGridName) {

            var vrow = [];
            var isNew = 'Y';
            if (cdcomment != -1) {
                vrow = gridGetItem(vGridName, parseInt(cdcomment));
                if (!w2ui[vGridName].isNewRow(cdcomment)) {
                    isNew = 'N';
                }
            }
            console.log(w2ui[vGridName].cdbuildsch, cdcomment, cd_comment_answer, vGridName, isNew, vrow, w2ui[vGridName]);


            basicPickListOpen({
                controller: 'schedule/project_build_schedule_comments/openCommentPL/' + w2ui[vGridName].cdbuildsch + '/' + cdcomment + '/' + cd_comment_answer,
                title: 'Comment',
                plVarSuffix: 'att',
                columnClass: 'col-md-10 col-md-offset-1',
                sel_id: -1,
                showTitle: true,
                postParam: {rowdata: JSON.stringify(vrow), isNew: isNew},
                plCallBack: function (vdata) {
                    console.log(w2ui[vGridName].cdbuildsch, cdcomment, cd_comment_answer, vdata);
                    thisObj.setDemandedAsChanged();

                    if (w2ui[vGridName].get(vdata.recid) != null) {
                        $.each(vdata, function (i, v) {
                            w2ui[vGridName].setItem(vdata.recid, i, v);
                        });
                    } else {
                        w2ui[vGridName].newRecids.push(vdata.recid);
                        w2ui[vGridName].add(vdata, true);
                        $.each(vdata, function (i, v) {
                            w2ui[vGridName].setItemAsChanged(vdata.recid, i);
                        });

                    }
                }
            });


        }

        this.ToolbarMainAttachment = function (bPressed, bInfo) {
            var vGridName = $('#' + bInfo.idMenu).closest('.w2ui-grid').attr('name');

            if (bPressed == 'expexcel') {
                w2ui[vGridName].exportTo(1, 'attachmentModel');
            }
            ;


            if (bPressed == 'edit') {
                thisObj.attachmentType = 'P';
                openRepository({id: 7, code: thisObj.cd_project_model});
            }
        }

        this.buildThumbs = function () {
            $('.carousel-inner').empty();
            $('.carousel-indicators').empty();
            $('#attachBadge').text(w2ui['gridMainAttachment'].records.length);

            var vinner = '';
            var vindicator = '';
            var vx = 0;
            var vtime = Date.now();
            var vactive = false;
            var vclass = '';

            $.each(w2ui['gridMainAttachment'].records, function (i, v) {
                vclass = '';
                if (v.fl_is_image == 'Y') {
                    if (v.fl_main == 1) {
                        vclass = 'active';
                        vactive = true;
                    }

                    vindicator = vindicator + '<li data-target="#myCarousel" data-slide-to="' + vx + '" class="' + vclass + '" recid="' + v.recid + '"></li>';
                    vinner = vinner + '<div class="item ' + vclass + '"> <img src="docrep/general_document_repository/getDocumentRepositoryThumbsSrc/' + v.cd_document_repository + '/' + vtime + '" alt=""></div>';
                    vx++;
                }
            });

            var $x = $('#myCarousel');


            if (vx == 0) {
                $('#gridMainAttachDiv').parent().removeClass('col-lg-8').removeClass('col-lg-12').addClass('col-lg-12');
                $x.parent().parent().hide();
            } else {
                // means there's no default
                if (!vactive) {
                    vindicator = vindicator.replace('class="', 'class="active ');
                    vinner = vinner.replace('class="', 'class="active ');
                }


                $('.carousel-inner').html(vinner);
                $('.carousel-indicators').html(vindicator);

                $('#gridMainAttachDiv').parent().removeClass('col-lg-8').removeClass('col-lg-12').addClass('col-lg-8');
                $x.carousel();
                $x.parent().parent().show();
            }

            w2ui['gridMainAttachment'].resize();

            $('.carousel-inner img').css('cursor', 'zoom-in').on("click", function () {
                //openImageIdSrc(vid, 'x');

                var vd = $(this).attr('src');
                vd = vd.replace("Thumbs", "");
                var vheight = Math.round(($(window).height() * 0.80));
                var vhtml = '<div style="height: ' + vheight + 'px"> <img src="' + vd + '" class="img-responsive"style="max-height: ' + vheight + 'px;margin: 0 auto"></div>';
                $.dialog({
                    title: false,
                    content: vhtml,
                    columnClass: 'col-md-12 messageBoxCGBClass',
                    theme: 'supervan',
                    backgroundDismiss: true,
                    onOpenBefore: function () {
                        this.$el.css('z-index', '1000000105');
                    },

                    buttons: false
                });


            });


        }

        this.ToolbarBuildAttachment = function (bPressed, bInfo) {


            var vGridName = $('#' + bInfo.idMenu).closest('.w2ui-grid').attr('name');

            if (bPressed == 'expexcel') {
                w2ui[vGridName].exportTo(1, 'attachmentBuild');
            }
            ;


            if (bPressed == 'edit') {
                thisObj.attachmentType = w2ui[vGridName].cdbuildsch;
                openRepository({id: 8, code: w2ui[vGridName].cdbuildsch});
            }


        }


        this.ToolbarBuildGridComments = function (bPressed, bInfo) {
            var vGridName = $('#' + bInfo.idMenu).closest('.w2ui-grid').attr('name');


            if (bPressed == 'expexcel') {
                w2ui[vGridName].exportTo(1, 'commentsBuild');
            }
            ;

            if (bPressed == 'insert') {
                this.openProjectBuildComment(-1, -1, vGridName)
            }
            if (bPressed == 'replycom') {
                var vpk = w2ui[vGridName].getPk();
                if (vpk == -1) {
                    messageBoxError("You must select one line");
                    return;
                }
                if (w2ui[vGridName].isNewRow(vpk)) {
                    messageBoxError("Cannot Reply a not saved Comment");
                    return;
                }
                ;

                this.openProjectBuildComment(-1, vpk, vGridName)
            }


            if (bPressed == "delete") {
                var vpk = w2ui[vGridName].getPk();
                var vuser = w2ui[vGridName].getItem(vpk, 'cd_human_resource')

                if (w2ui[vGridName].isNewRow(vpk)) {
                    w2ui[vGridName].deleteRow();
                } else {
                    messageBoxError(javaMessages.delnewonly);
                }
            }

        }

        this.ToolbarTestTools = function (bPressed, bInfo) {
            var vGridName = $('#' + bInfo.idMenu).closest('.w2ui-grid').attr('name');
        }

        this.ToolbarGridRoles = function (bPressed, bInfo) {
            var vGridName = $('#' + bInfo.idMenu).closest('.w2ui-grid').attr('name');

            if (bPressed == 'expexcel') {
                w2ui[vGridName].exportTo(1, 'projectRoles');
            }
            ;


            if (bPressed == 'insert') {
                basicPickListOpen({
                    model: '<?php echo $this->encodeModel('human_resource_model'); ?>',
                    title: 'User',
                    multiselect: 'Y',
                    //sel_id: vid,
                    //showTitle: true,
                    plCallBack: function (id, desc, rec) {
                        if (id == -1) {
                            return;
                        }

                        $.each(rec, function (i, v) {

                            w2ui[vGridName].insertRow({
                                funcAfter: function (data) {
                                    w2ui[vGridName].setItem(data.recid, 'cd_project_model', thisObj.cd_project_model);
                                    w2ui[vGridName].setItem(data.recid, 'fl_active', 1);

                                    w2ui['gridPrjRoles'].setItem(data.recid, 'ds_e_mail', v.ds_e_mail);
                                    w2ui['gridPrjRoles'].setItem(data.recid, 'ds_human_resource', v.description);
                                    w2ui['gridPrjRoles'].setItem(data.recid, 'cd_human_resource', v.cd_human_resource);

                                    if (chkUndefined(v.cd_roles, -1) != -1) {
                                        w2ui['gridPrjRoles'].setItem(data.recid, 'cd_roles', v.cd_roles);
                                        w2ui['gridPrjRoles'].setItem(data.recid, 'ds_roles', v.ds_roles);
                                    }

                                }
                            });


                        });

                    }
                });


                /*
                 
                 //console.log(data);
                 }});
                 */
            }

            if (bPressed == "delete") {
                w2ui[vGridName].deleteRow();
            }

        }

        // test buttons;:
        this.duplicateTest = function (fromId, sch) {
            thisObj.duplicatingCode = fromId;
            thisObj.insertTestData(sch);
        }

        this.setPLEnableOnlyNEW = function (record, index, column_index) {


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


        this.loadImgAttachments = function () {

            var vcode;
            var vtype;
            var vgrid;


            if (thisObj.attachmentType == 'X') {
                return;
            }

            if (thisObj.attachmentType == 'P') {
                vcode = thisObj.cd_project_model;
                vtype = 'P';
                vgrid = 'gridMainAttachment';
            } else {
                vcode = thisObj.attachmentType;
                vtype = 'B';
                vgrid = 'gridAttachment_' + thisObj.attachmentType;

            }


            $.myCgbAjax({
                url: 'tti/project/retDocRep/' + vcode + '/' + vtype,
                success: function (data) {

                    var vc = w2ui[vgrid].getChanges();
                    w2ui[vgrid].clear();
                    w2ui[vgrid].add(data);

                    if (thisObj.attachmentType == 'P') {
                        thisObj.buildThumbs();
                    }

                }
            });
        }


        this.insertTestData = function (scheduleCode) {

            $.myCgbAjax({
                url: 'tti/project/addNewTestItem/' + scheduleCode,
                success: function (data) {

                    if (thisObj.planningLoadData['mod' + scheduleCode].htmlLoaded) {
                        $('#divSchTst_' + scheduleCode).append(data.html);
                        thisObj.Form.addNewElements();
                        thisObj.setAreaTest(data.pk, scheduleCode, []);
                        thisObj.runFuncQueue();
                        var $d = $('#testArea_' + data.pk);
                        $('#PrjscrollPart').cgbMakeScrollbar('scrollToY', $d);
                        thisObj.renderCalendar();
                    } else {
                        thisObj.planningLoadData['mod' + scheduleCode].html.find('.schtstdiv').last().append(data.html);
                    }

                    data.gridData = JSON.parse(data.gridData);
                    w2ui['gridPlanData_' + scheduleCode].add(data.gridData)
                    w2ui['gridPlanData_' + scheduleCode].newRecids.push(data.gridData[0].recid);


                    w2ui['gridPlanData_' + scheduleCode].setItemAsChanged(data.gridData[0].recid, 'cd_location');
                    w2ui['gridPlanData_' + scheduleCode].setItemAsChanged(data.gridData[0].recid, 'ds_location');
                    w2ui['gridPlanData_' + scheduleCode].setItem(data.gridData[0].recid, 'cd_project_build_schedule', scheduleCode);
                    w2ui['gridPlanData_' + scheduleCode].ScrollToRow(data.gridData[0].recid);


                    thisObj.showPlanning(scheduleCode, false);


                    if (thisObj.duplicatingCode != -1) {
                        thisObj.makeTestDuplicate(data.pk, scheduleCode);
                    }

                    thisObj.updatePlanningTabQty(scheduleCode);


                    //thisObj.loadPlanGrid(scheduleCode);


                }
            });
        }

        this.setPlanningWOData = function (plan, sch, data) {

            var vgrid = 'gridWO_' + plan;
            if (w2ui[vgrid] != undefined) {
                w2ui[vgrid].clear();
                w2ui[vgrid].add(data);
            }
            w2ui['gridPlanData_' + sch].setItem(plan, 'wodata', data);

            thisObj.setPlanningWOColumn(sch)

        }

        this.setPlanningWOColumn = function (sch) {
            $.each(w2ui['gridPlanData_' + sch].records, function (ii, vv) {
                var data = chkUndefined(w2ui['gridPlanData_' + sch].getItem(vv.recid, 'wodata'), '[]');
                if (!$.isArray(data)) {
                    data = JSON.parse(data);
                }

                var vdat = '';
                $.each(data, function (i, v) {
                    vdat = vdat + v.nr_work_order + ', ';
                });


                vdat = vdat.slice(0, -2);
                w2ui['gridPlanData_' + sch].setItemNoChanges(vv.recid, 'ds_workorders', vdat);
            })
        }


        this.deleteTest = function (id, sched) {
            w2ui['gridPlanData_' + sched].ScrollToRow(id);
            w2ui['gridPlanData_' + sched].deleteRow({
                funcAfter: function (v) {
                    $('#testArea_' + v[0]).remove();
                    thisObj.planningLoadData['mod' + sched].html.find('#testArea_' + v[0]).remove();
                    thisObj.updatePlanningTabQty(sched);
                }
            });
        }

        this.makeTestDuplicate = function (toId, scheduleCode) {
            var vCodeFrom = thisObj.duplicatingCode;
            var vCodeTo = toId;
            var vGrid = w2ui['gridPlanData_' + scheduleCode];

            var vdata = gridGetItem('gridPlanData_' + scheduleCode, vCodeFrom);
            vdata.recid = vCodeTo;
            vdata.cd_project_build_schedule_tests = vCodeTo;
            //vGrid.set(vCodeTo, vdata);
            //vGrid.setItemAsChanged(vCodeTo);
            vGrid.newRecids.push(vCodeTo);

            $.each(vGrid.columns, function (i, v) {
                var vcol = v.field;
                var vcolcode = 'cd' + vcol.slice(2);
                var vint = v.internaltype;

                if (vcol == 'recid' || vcol == 'cd_project_build_schedule_tests' || vcol.substring(0, 2) == 'dt' || vcol == 'fl_can_change_dates' || vcol == 'ds_workorders') {
                    return;
                }

                var vcoldata = vGrid.getItem(vCodeFrom, vcol);
                var vcoldatacode = vGrid.getItem(vCodeFrom, vcolcode);
                thisObj.setItemPlanning(scheduleCode, vCodeTo, vcol, vcoldata, vcoldatacode);

            });

            thisObj.duplicatingCode = -1;
        }

        this.setItemPlanning = function (sch, plancode, ds_field, desc, code) {

            var vformField = ds_field + '_' + plancode + '_form';

            if (thisObj.planningLoadData['mod' + sch].htmlLoaded) {
                if (code != undefined) {
                    thisObj.Form.setItemPL(vformField, code, desc);
                } else {
                    thisObj.Form.setItem(vformField, desc);
                }
            } else {
                thisObj.planningLoadData['mod' + sch].html.find('#' + vformField).val(desc);
                if (code != undefined) {
                    thisObj.planningLoadData['mod' + sch].html.find('#' + vformField).attr('plcode', code);
                }

            }

            w2ui['gridPlanData_' + sch].setItem(plancode, ds_field, desc);

            if (code != undefined) {
                w2ui['gridPlanData_' + sch].setItem(plancode, 'cd' + ds_field.slice(2), code);
            }


        }

        // calendar routines
        this.calendarMove = function (direction, code) {
            // direction = 1 - previous, 2-> next
            // code = code of the calendar to find the elemtn.
            var vId = '#calendar_' + code + '_div';
            if (direction == 1) {
                $(vId).fullCalendar('prev');
            } else {
                $(vId).fullCalendar('next');
            }


        }

        this.btnCalculateDays = function (sch) {

            var vdf = 'dt_est_finish_' + sch + '_form';

            var vg = chkUndefined(thisObj.Form.getItem('nr_goal_' + sch + '_form'), 0);
            var vo = chkUndefined(thisObj.Form.getItem('nr_output_' + sch + '_form'), 0);
            var vds = chkUndefined(thisObj.Form.getItem('dt_est_start_' + sch + '_form'), '');
            if (vg == 0 || vo == 0 || vds == '') {
                return;
            }

            var vd = Math.round((vg / vo) + 0.49)

            var vcalc = moment(vds, defaultDateFormatUpper).isoAddWeekdaysFromSet({
                'workdays': vd
            });

            thisObj.Form.setItem(vdf, vcalc.format(defaultDateFormatUpper));
            thisObj.calculateWorkDays();

            this.setDemandedAsChanged();
        }

        this.removeEvent = function (e, element, calendar) {
            //
            element.addClass('toRemove');
            $(element).w2menu({
                items: [{id: 1, text: javaMessages.deleteMsg, icon: 'fa fa-trash'}],
                onSelect: function (event) {
                    thisObj.deleteCalendarEvent(e, calendar);
                }, onHide: function () {
                    element.removeClass('toRemove');
                }

            });

        }


        this.calculateWorkDays = function () {
            var vInfo = this.Form.getInfobyFieldname('nr_goal');

            $.each(vInfo, function (i, v) {
                var vdsf = 'dt_est_start_' + v.order + '_form';
                var vdff = 'dt_est_finish_' + v.order + '_form';
                var vdd = 'nr_days_' + v.order + '_form';
                var $btn = $('.calcButton[sch="' + v.order + '"]');


                var vds = chkUndefined(thisObj.Form.getItem(vdsf), '');
                var vdf = chkUndefined(thisObj.Form.getItem(vdff), '');


                if (vds == '' || vdf == '') {
                    thisObj.Form.setItem(vdd, '0');
                    $btn.css('background-color', '#ddd').css("color", "#000");
                    return true;
                }
                vdf = moment(vdf, defaultDateFormatUpper);
                vds = moment(vds, defaultDateFormatUpper);


                var vdiff = vdf.diff(vds, 'd');
                if (vdiff < 0) {
                    thisObj.Form.setItem(vdd, '0');
                    $btn.css('background-color', '#ddd').css("color", "#000");
                    ;
                    return true;
                }

                var vdays = vdiff;

                /*
                 
                 
                 var vdays = moment().isoWeekdayCalc({
                 rangeStart: vds,
                 rangeEnd: vdf
                 
                 //exclusions: ['6 Apr 2015', '7 Apr 2015']  //public holidays
                 });
                 */

                //console.log('calculo', vds, vdf, vdiff, vdays);

                thisObj.Form.setItem(vdd, vdays);
                $btn.css('background-color', '#337ab7').css("color", "#fff");
            });

        }


        this.calculateWorkDaysAgreed = function () {
            var vInfo = this.Form.getInfobyFieldname('nr_goal');
            $.each(vInfo, function (i, v) {
                var vdsf = 'dt_start_' + v.order + '_form';
                var vdff = 'dt_finish_' + v.order + '_form';
                var vdd = 'nr_days_agreed_' + v.order + '_form';

                var vds = chkUndefined(thisObj.Form.getItem(vdsf), '');
                var vdf = chkUndefined(thisObj.Form.getItem(vdff), '');

                if (vds == '' || vdf == '') {
                    thisObj.Form.setItem(vdd, '0');
                    return true;
                }
                vdf = moment(vdf, defaultDateFormatUpper);
                vds = moment(vds, defaultDateFormatUpper);
                var vdiff = vdf.diff(vds, 'd');
                if (vdiff < 0) {
                    thisObj.Form.setItem(vdd, '0');
                    return true;
                }
                var vdays = vdiff;
                /*
                 var vdays = moment().isoWeekdayCalc({
                 rangeStart: vds,
                 rangeEnd: vdf
                 //exclusions: ['6 Apr 2015', '7 Apr 2015']  //public holidays
                 });
                 */
                thisObj.Form.setItem(vdd, vdays);
            });

        }


        this.calculateWorkDaysActual = function () {
            var vInfo = this.Form.getInfobyFieldname('nr_goal');
            $.each(vInfo, function (i, v) {
                var vdsf = 'dt_actual_start_' + v.order + '_form';
                var vdff = 'dt_actual_finish_' + v.order + '_form';
                var vdd = 'nr_days_actual_' + v.order + '_form';

                var vds = chkUndefined(thisObj.Form.getItem(vdsf), '');
                var vdf = chkUndefined(thisObj.Form.getItem(vdff), '');

                if (vds == '' || vdf == '') {
                    thisObj.Form.setItem(vdd, '0');
                    return true;
                }
                vdf = moment(vdf, defaultDateFormatUpper);
                vds = moment(vds, defaultDateFormatUpper);
                var vdiff = vdf.diff(vds, 'd');
                if (vdiff < 0) {
                    thisObj.Form.setItem(vdd, '0');
                    return true;
                }

                var vdays = vdiff;

                /*
                 var vdays = moment().isoWeekdayCalc({
                 rangeStart: vds,
                 rangeEnd: vdf
                 //exclusions: ['6 Apr 2015', '7 Apr 2015']  //public holidays
                 });
                 */
                thisObj.Form.setItem(vdd, vdays);
            });

        }
        // funcaoes gerais 

        this.fillCalendar = function () {
            var vrec = w2ui['gridDates'].records;
            var $vcal = [];
            //$vCal.fullCalendar('addEventSource', vtst);

            $.each(vrec, function (i, v) {
                // to make only one once the search of the element
                if ($vcal[v.cd_project_build_schedule_tests] == undefined) {
                    $vcal[v.cd_project_build_schedule_tests] = $('#calendar_' + v.cd_project_build_schedule_tests + '_div');
                    $vcal[v.cd_project_build_schedule_tests].fullCalendar('removeEvents');
                    thisObj.positionCalendarOnFirst(v.cd_project_build_schedule_tests)
                }

                var vtst = [{
                        id: v.recid,
                        recid: v.recid,
                        title: v.nr_workers + ' Worker(s)',
                        allDay: true,
                        start: moment(v.dt_start, defaultDateFormatUpper).hour(0).minute(0),
                        end: moment(v.dt_finish, defaultDateFormatUpper).add(1, 'd'),

                        nr_workers: v.nr_workers,
                        cd_project_build_schedule_tests: v.cd_project_build_schedule_tests
                    }];
                $vcal[v.cd_project_build_schedule_tests].fullCalendar('addEventSource', vtst);
            })
        }

        this.updateCalendar = function (item) {
            // here I remove one day for the finish because the calendar always considers one more day.
            w2ui['gridDates'].setItem(item.recid, 'dt_start', moment(item.start).format(defaultDateFormatUpper));
            w2ui['gridDates'].setItem(item.recid, 'dt_finish', moment(item.end).subtract(1, 'd').format(defaultDateFormatUpper));
            thisObj.setDemandedAsChanged();
        }

        this.addToCalendar = function (item, $vCal) {
            $.each(item, function (i, v) {
                w2ui['gridDates'].add([{recid: v.recid}]);

                w2ui['gridDates'].setItem(v.recid, 'dt_start', moment(v.start).format(defaultDateFormatUpper));
                w2ui['gridDates'].setItem(v.recid, 'dt_finish', moment(v.end).format(defaultDateFormatUpper));
                w2ui['gridDates'].setItem(v.recid, 'nr_workers', v.nr_workers);
                w2ui['gridDates'].setItem(v.recid, 'cd_project_build_schedule_tests', parseInt(v.cd_project_build_schedule_tests));
                w2ui['gridDates'].setItem(v.recid, 'ds_order', moment(v.start).format('YYYYMMDD'));
                // add one more to show properly on screen
                item[i].end = moment(item[i].end).add(1, 'd');
            });

            $vCal.fullCalendar('addEventSource', item);
            thisObj.setDemandedAsChanged();

        }

        this.deleteCalendarEvent = function (item, calendar) {
            w2ui['gridDates'].setItem(item.recid, 'fl_to_delete', 'Y');
            $(calendar).fullCalendar('removeEvents', item.id);

            thisObj.setDemandedAsChanged();

        }

        this.positionCalendarOnFirst = function (ptst) {
            var minD = moment('20300101');
            var vhas = false;
            var vId = '#calendar_' + ptst + '_div';
            $.each(w2ui['gridDates'].records, function (i, v) {
                var vd = moment(w2ui['gridDates'].getItem(v.recid, 'dt_start'), defaultDateFormatUpper);
                var vtst = w2ui['gridDates'].getItem(v.recid, 'cd_project_build_schedule_tests');
                var vdel = chkUndefined(w2ui['gridDates'].getItem(v.recid, 'fl_to_delete'), 'N');
                if (vtst == ptst && vd.isBefore(minD) && vdel == 'N') {
                    minD = vd;
                    vhas = true;
                }
            });

            if (vhas) {
                $(vId).fullCalendar('gotoDate', minD);
            }


        }


        thisObj.getScreenStatus = function () {
            var vStatus = [];
            var openedBox = [];
            vStatus['position'] = $('#PrjscrollPart').cgbMakeScrollbar('getScrollPositionY');
            vStatus['tab'] = [];

            $('#PrjscrollPart').find('.box').not('.collapsed-box').find("[data-widget='collapse']").each(function () {
                var vx = $(this).closest('.prjBuildClass').attr('id');
                if (vx == undefined) {
                    return true;
                }
                openedBox.push(vx);
            });


            $.each($('#prjScheduleArea .prjBuildClass').find('li.active'), function () {
                vStatus['tab'].push($(this).attr('id'));
            });

            vStatus['openBox'] = openedBox;

            return vStatus;
        }

        thisObj.setScreenStatus = function (vStatus) {

            $.each(vStatus.tab, function (i, v) {
                //$('#' + v + ' a').click();
            });

            $.each(vStatus['openBox'], function (i, v) {
                $('#' + v).find("[data-widget='collapse']").click();
            });


            $('#PrjscrollPart').cgbMakeScrollbar('scrollToY', vStatus['position']);

        }


        thisObj.runFuncQueue = function (numberToRun) {

            if (numberToRun == undefined) {
                numberToRun = thisObj.funcQueue.length;
            } else {
                if (numberToRun > thisObj.funcQueue.length) {
                    numberToRun = thisObj.funcQueue.length;
                }
            }


            for (var i = 0; i < numberToRun; i++) {

                (thisObj.funcQueue.shift())();
            }


        }

        this.renderTRRemarks = function (record, index, column_index) {
            var vfield = this.columns[column_index].field;
            var vx = this.columns[column_index].field;
            var vdata = chkUndefined(record[vx], '&nbsp');
            var vgrd = this.name;

            if (vdata.indexOf('<tr') > 0) {
                var buttons = '<div style="text-align: left; width: 100%; "><button type="button" class="btn btn-info btn-xs" style="margin-right: 2px;height: 22px;"  onclick="dsFormPrjSheetObject.openTRRemarks(\'' + vgrd + '\', ' + record.recid + ')"><span class="fa fa-table" aria-hidden="true"></span></button></div>';
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
            buttons = buttons + '<button type="button" data-toggle="tooltipa" title="' + vShow + '" data-placement="right"  class="btn ' + vstyle + ' btn-xs" style="margin-right: 2px;height: 22px;"  onclick="dsFormPrjSheetObject.openTRAttachments(' + record.cd_tr_test_request_work_order_sample + ')"><span class="fa fa-edit" aria-hidden="true"></span></button>';

            if (record['ds_file_name'] != '') {
                buttons = buttons + '<button type="button" class="btn btn-info btn-xs" data-placement="right" style="margin-right: 2px;height: 22px;" data-toggle="tooltipa" title="<?php echo($testrep) ?>"  onclick="dsFormPrjSheetObject.openMTR(\'' + record['ds_file_name'] + '\')"><span class="fa fa-list-alt" aria-hidden="true"></span></button>';
            }


            buttons = buttons + '</div>';


            return buttons;
        }

        this.toolbarMTERemarks = function (record, index, column_index) {
            if (typeof record.ds_source == 'undefined' || record.ds_source != 'MTE')
                return;
            var vstyle = 'btn-info';
            var vShow = 'More data';
            var vwo = record.nr_work_order.toLocaleString("en",{useGrouping: false,minimumFractionDigits: 2});
            var buttons = '<div style="text-align: left; width: 100%; ">'
            buttons = buttons + '<button type="button" data-toggle="tooltipa" title="' + vShow + '" data-placement="right"  class="btn ' + vstyle + ' btn-xs" style="margin-right: 2px;height: 22px;"  onclick="dsFormPrjSheetObject.openMTEData(\'' + vwo + '\',' + record.nr_sample + ')"><span class="fa fa-tag" aria-hidden="true"></span></button>';

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
            var buttons = '<div style="text-align: left; width: 100%; "><button type="button" class="btn btn-info btn-xs" style="margin-right: 2px;height: 22px;"  onclick="dsFormPrjSheetObject.duplicateTest(' + record.cd_project_build_schedule_tests + ', ' + record.cd_project_build_schedule + ')"><span class="fa fa-files-o" aria-hidden="true"></span></button>';
            buttons = buttons + '<button type="button" class="btn btn-info  btn-xs" style="margin-right: 2px;height: 22px;"  onclick="dsFormPrjSheetObject.workOrderMaint(' + record.cd_project_build_schedule_tests + ', ' + record.cd_project_build_schedule + ')"><span class="fa fa-link" aria-hidden="true"></span></button>';
            buttons = buttons + '<button type="button" class="btn btn-danger  btn-xs" style="margin-right: 2px;height: 22px;"  onclick="dsFormPrjSheetObject.deleteTest(' + record.cd_project_build_schedule_tests + ', ' + record.cd_project_build_schedule + ')"><span class="fa fa-trash-o" aria-hidden="true"></span></button></div>';

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


        this.renderDisableCommentsPL = function (record, index, column_index) {
            var bcanChange = true;
            var vfield = this.columns[column_index].field;
            var vx = this.columns[column_index].field;
            var vdata = chkUndefined(record[vx], '&nbsp');
            if (vdata == '') {
                vdata = '&nbsp';
            }

            if (vfield == 'ds_comments' && !this.isNewRow(record.recid)) {
                vdata = '<div class="w2ui-data-disabledPL" style="background-color: transparent">' + vdata + '</div>';
            } else {
                vdata = gridMakePLRender.call(this, record, index, column_index);
            }


            return vdata;
        }


        this.renderDisablePlanGridDates = function (record, index, column_index) {
            var bcanChange = true;
            var vfield = this.columns[column_index].field;
            var vx = this.columns[column_index].field;
            var vdata = chkUndefined(this.getItem(record.recid, vfield), '&nbsp');

            if (vdata == '') {
                vdata = '&nbsp';
            }

            if (record.fl_can_change_dates == 'N') {
                vdata = '<div class="w2ui-data-disabled" style="background-color: transparent">' + vdata + '</div>';
            }


            return vdata;
        }


        this.workOrderMaint = function (tstcode, schcode) {

            var visnew = w2ui['gridPlanData_' + schcode].isNewRow(tstcode);

            if (visnew) {
                messageBoxError(javaMessages.saveFirst);
                return;
            }

            var title = '';
            openFormUiBootstrap(
                    title,
                    'schedule/project_build_schedule_tests_wo/openWorkOrders/' + tstcode,
                    'col-md-12'
                    );

        }


    }


    // funcoes iniciais;
    dsFormPrjSheetObject.start();


    // insiro colunas;

</script>

<div id="divPrjForm" class="classdivPrjForm">
    <div class="row">
        <div id="prjToolbar" style="width: 100%;" class="toolbarStyle"></div>
    </div>

    <div id="PrjscrollPart" style="display: block;">

        <form id="formPrj" class="form-horizontal" style="padding-right: 15px;">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info box-solid" id="gridPrjModel">
                        <div class="box-header with-border">
                            <h3 class="box-title" id="prjdatailsgrouptitle"> <?php echo($projectTitle) ?> </h3>
                            <div class="box-tools pull-right">
                                <button type='button' class='btn btn-box-tool' data-widget="collapse"><i
                                        class='fa fa-compress'></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class='col-md-12 no-padding'>
                                <div class="hidden">
                                    <label for="cd_project_form" class="col-sm-1 control-label ">:</label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control input-sm"
                                               value="<?php echo($cd_project) ?>" fieldname="cd_project"
                                               id="cd_project_form" mask="PK" sc="<?php echo($sc); ?>">
                                        <input type="text" class="form-control input-sm"
                                               value="<?php echo($fl_draft) ?>" fieldname="fl_draft" id="fl_draft_form"
                                               mask="c" sc="<?php echo($sc); ?>">
                                        <input type="text" class="form-control input-sm"
                                               value="<?php echo($cd_project_model) ?>" fieldname="cd_project_model"
                                               id="cd_project_model_form" mask="I" sc="<?php echo($sc); ?>">
                                    </div>
                                </div>

                                <div class="row no-padding">

                                    <label for="ds_project_status_form"
                                           class="col-sm-1 control-label "><?php echo($formTrans_cd_project_status) ?>
                                        :</label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control input-sm"
                                               plcode="<?php echo($cd_project_status) ?>"
                                               value="<?php hecho($ds_project_status) ?>" fieldname="ds_project_status"
                                               id="ds_project_status_form" mask="PLD"
                                               model="<?php echo($this->encodeModel('tti/project_status_model')); ?>"
                                               fieldname="ds_project_status" code_field="cd_project_status" relid="-1"
                                               relCode="-1" type="text" ru="cd_project_model_form">
                                    </div>


                                    <label for="ds_met_project_form"
                                           class="col-sm-1 control-label "><?php echo($formTrans_ds_met_project) ?>
                                        :</label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control input-sm"
                                               value="<?php hecho($ds_met_project) ?>" fieldname="ds_met_project"
                                               id="ds_met_project_form" mask="c" ro="<?php hecho($detailsRO) ?>">
                                    </div>

                                    <label for="ds_tti_project_form"
                                           class="col-sm-1 control-label "><?php echo($formTrans_ds_tti_project) ?>
                                        :</label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control input-sm"
                                               value="<?php hecho($ds_tti_project) ?>" fieldname="ds_tti_project"
                                               id="ds_tti_project_form" mask="c" ro="<?php hecho($detailsRO) ?>">
                                    </div>

                                    <label for="ds_project_form"
                                           class="col-sm-1 control-label "><?php echo($formTrans_ds_project) ?>:</label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control input-sm"
                                               value="<?php hecho($ds_project) ?>" fieldname="ds_project"
                                               id="ds_project_form" mask="c" type="text" maxlength="128"
                                               ro="<?php hecho($detailsRO) ?>">
                                    </div>


                                </div>

                                <div class="row no-padding">
                                    <label for="ds_department_form"
                                           class="col-sm-1 control-label "><?php echo($formTrans_cd_department) ?>
                                        :</label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control input-sm"
                                               plcode="<?php echo($cd_department) ?>"
                                               value="<?php hecho($ds_department) ?>" fieldname="ds_department"
                                               id="ds_department_form" mask="PLD"
                                               model="<?php echo($this->encodeModel('job_department_model')); ?>"
                                               fieldname="ds_department" code_field="cd_department" relid="-1"
                                               relCode="-1" type="text" ro="<?php hecho($detailsRO) ?>"
                                               ro="<?php hecho($detailsRO) ?>">
                                    </div>


                                    <label for="ds_met_project_model_form"
                                           class="col-sm-1 control-label "><?php echo($formTrans_ds_met_project_model) ?>
                                        :</label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control input-sm"
                                               value="<?php hecho($ds_met_project_model) ?>"
                                               fieldname="ds_met_project_model" id="ds_met_project_model_form" mask="c"
                                               ru="cd_project_model_form" ro="<?php hecho($detailsRO) ?>"
                                               ro="<?php hecho($detailsRO) ?>">
                                    </div>

                                    <label for="ds_tti_project_model_form"
                                           class="col-sm-1 control-label "><?php echo($formTrans_ds_tti_project_model) ?>
                                        :</label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control input-sm"
                                               value="<?php hecho($ds_tti_project_model) ?>"
                                               fieldname="ds_tti_project_model" id="ds_tti_project_model_form" mask="c"
                                               ru="cd_project_model_form" ro="<?php hecho($detailsRO) ?>"
                                               ro="<?php hecho($detailsRO) ?>">
                                    </div>

                                    <label for="ds_project_model_form"
                                           class="col-sm-1 control-label "><?php echo($formTrans_ds_project_model) ?>
                                        :</label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control input-sm"
                                               value="<?php hecho($ds_project_model) ?>" fieldname="ds_project_model"
                                               id="ds_project_model_form" mask="c" type="text" maxlength="64"
                                               ru="cd_project_model_form" ro="<?php hecho($detailsRO) ?>"
                                               ro="<?php hecho($detailsRO) ?>">
                                    </div>

                                </div>

                                <div class="row no-padding">

                                    <label for="ds_project_product_form"
                                           class="col-sm-1 control-label "><?php echo($formTrans_cd_project_product) ?>
                                        :</label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control input-sm"
                                               plcode="<?php echo($cd_project_product) ?>"
                                               value="<?php hecho($ds_project_product) ?>"
                                               fieldname="ds_project_product" id="ds_project_product_form" mask="PLD"
                                               model="<?php echo($this->encodeModel('tti/project_product_model')); ?>"
                                               fieldname="ds_project_product" code_field="cd_project_product" relid="-1"
                                               relCode="-1" type="text" must="Y" ro="<?php hecho($detailsRO) ?>">
                                    </div>

                                    <label for="ds_project_power_type_form"
                                           class="col-sm-1 control-label "><?php echo($formTrans_cd_project_power_type) ?>
                                        :</label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control input-sm"
                                               plcode="<?php echo($cd_project_power_type) ?>"
                                               value="<?php hecho($ds_project_power_type) ?>"
                                               fieldname="ds_project_power_type" id="ds_project_power_type_form"
                                               mask="PLD"
                                               model="<?php echo($this->encodeModel('tti/project_power_type_model')); ?>"
                                               fieldname="ds_project_power_type" code_field="cd_project_power_type"
                                               relid="<?php echo($sqlPrd) ?>" relCode="-1" type="text" must="Y"
                                               ro="<?php hecho($detailsRO) ?>">
                                    </div>

                                    <label for="ds_project_tool_type_form"
                                           class="col-sm-1 control-label "><?php echo($formTrans_cd_project_tool_type) ?>
                                        :</label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control input-sm"
                                               plcode="<?php echo($cd_project_tool_type) ?>"
                                               value="<?php hecho($ds_project_tool_type) ?>"
                                               fieldname="ds_project_tool_type" id="ds_project_tool_type_form"
                                               mask="PLD"
                                               model="<?php echo($this->encodeModel('tti/project_tool_type_model')); ?>"
                                               fieldname="ds_project_tool_type" code_field="cd_project_tool_type"
                                               relid="<?php echo($sqlPwd) ?>" relCode="-1" type="text"
                                               ro="<?php hecho($detailsRO) ?>">
                                    </div>

                                    <label for="ds_brand_form"
                                           class="col-sm-1 control-label "><?php echo($formTrans_cd_brand) ?>:</label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control input-sm"
                                               plcode="<?php echo($cd_brand) ?>" value="<?php hecho($ds_brand) ?>"
                                               fieldname="ds_brand" id="ds_brand_form" mask="PLD"
                                               model="<?php echo($this->encodeModel('brand_model')); ?>"
                                               fieldname="ds_brand" code_field="cd_brand" relid="-1" relCode="-1"
                                               type="text" ro="<?php hecho($detailsRO) ?>">
                                    </div>


                                </div>

                                <div class="row no-padding">
                                    <label for="ds_project_voltage_form"
                                           class="col-sm-1 control-label "><?php echo($formTrans_cd_project_voltage) ?>
                                        :</label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control input-sm"
                                               plcode="<?php echo($cd_project_voltage) ?>"
                                               value="<?php hecho($ds_project_voltage) ?>"
                                               fieldname="ds_project_voltage" id="ds_project_voltage_form" mask="PLD"
                                               model="<?php echo($this->encodeModel('tti/project_voltage_model')); ?>"
                                               fieldname="ds_project_voltage" code_field="cd_project_voltage" relid="-1"
                                               relCode="-1" type="text" ru="cd_project_model_form"
                                               ro="<?php hecho($detailsRO) ?>">
                                    </div>

                                </div>


                            </div>


                            <div class="row">
                                <div class="col-lg-6 col-sm-12" style="margin-top: 10px;">
                                    <div id="gridPrjCommentsDiv" style="height: 300px;"></div>
                                </div>
                                <div class="col-lg-6 col-sm-12" style="margin-top: 10px;">
                                    <div id="gridPrjRolesDiv" style="height: 300px;"></div>
                                </div>

                            </div>
                        </div>
                    </div>


                    <div class="col-md-12 no-padding">
                        <div class="box box-info box-solid collapsed-box" id="areaPrjAttachments">
                            <div class="box-header with-border">
                                <h3 class="box-title"> <?php echo('Attachment') ?> </h3>
                                <div class="box-tools pull-right">
                                    <span data-toggle="tooltip" title="" id='attachBadge' class="badge bg-yellow"
                                          data-original-title="">3</span>
                                    <button type='button' class='btn btn-box-tool' data-widget="collapse"><i
                                            class='fa fa-expand'></i></button>
                                </div>
                            </div>
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-lg-8 col-sm-12" style="margin-top: 10px;">
                                        <div id="gridMainAttachDiv" style="height: 300px;"></div>
                                    </div>

                                    <div class="col-lg-4 col-sm-12" style="margin-top: 10px;">
                                        <div style="height: 300px;">
                                            <div id="myCarousel" class="carousel slide" data-ride="carousel"
                                                 data-interval="5000" style="border: gray thin solid">
                                                <!-- Indicators -->
                                                <ol class="carousel-indicators">
                                                    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                                                    <li data-target="#myCarousel" data-slide-to="1"></li>
                                                    <li data-target="#myCarousel" data-slide-to="2"></li>
                                                </ol>

                                                <!-- Wrapper for slides -->
                                                <div class="carousel-inner"
                                                     style="width: 100%;height: auto; min-height: 300px">
                                                </div>

                                                <!-- Left and right controls -->
                                                <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                                                    <span class="glyphicon glyphicon-chevron-left"></span>
                                                    <span class="sr-only">Previous</span>
                                                </a>
                                                <a class="right carousel-control" href="#myCarousel" data-slide="next">
                                                    <span class="glyphicon glyphicon-chevron-right"></span>
                                                    <span class="sr-only">Next</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>


            </div>


            <div class="row" id="prjScheduleArea">
<?php echo($htmlsch) ?>
            </div>

        </form>
    </div>
</div>

<script>


    dsFormPrjSheetObject.runFuncQueue(2);


    dsFormPrjSheetObject.renderCalendar();


    $('#fileupload').change(function (img) {
        // select the form and submit
        if (!$('#fileupload').val()) {
            return;
        }

        var a = new FormData();
        var i = 0;
        var vx = $('#schdata').val();
        a.append('file', this.files[0]);
        a.append('scr', vx);
        waitMsgON('body', true, javaMessages.loading);
        var vplan = dsFormPrjSheetObject.getPlanningData();

        $.ajax({
            async: true,
            url: 'tti/project/uploadMatrix',
            type: 'POST',
            data: a,
            dataType: 'json',
            processData: false, // Don't process the files
            contentType: false,
            success: function (data, textStatus, jqXHR) {
                $('#fileupload').val('');
                //console.log('data', data);
                if (data.status == 'OK') {
                    if (vplan['mod' + vx].htmlLoaded) {
                        $('#divSchTst_' + vx).append(data.html);
                        dsFormPrjSheetObject.Form.addNewElements();
                        dsFormPrjSheetObject.setAreaTest(data.pk, vx, []);
                        dsFormPrjSheetObject.runFuncQueue();
                        var $d = $('#testArea_' + data.pk);
                        $('#PrjscrollPart').cgbMakeScrollbar('scrollToY', $d);
                        dsFormPrjSheetObject.renderCalendar();
                    } else {
                        vplan['mod' + vx].html.find('.schtstdiv').last().append(data.html);
                    }

                    data.gridData = JSON.parse(data.gridData);
                    w2ui['gridPlanData_' + vx].add(data.gridData)
                    $.each(data.gridData, function (i, v) {
                        w2ui['gridPlanData_' + vx].setItemAsChanged(v.recid)
                        w2ui['gridPlanData_' + vx].setItem(v.recid, 'cd_project_build_schedule', vx);
                    });

                    dsFormPrjSheetObject.showPlanning(vx, false);
                    dsFormPrjSheetObject.updatePlanningTabQty(vx);

                    /*$('#divSchTst_' + vx).append(data.html);
                     dsFormPrjSheetObject.Form.addNewElements();
                     dsFormPrjSheetObject.runFuncQueue();
                     dsFormPrjSheetObject.renderCalendar();*/
                } else {
                    messageBoxError(data.status);
                }

                //dsFormPrjSheetObject.loadPlanGrid(vx);
                waitMsgOFF('body');


            },
            error: function (jqXHR, textStatus, errorThrown) {
                waitMsgOFF('body');
                $('#fileupload').val('');

                if (checkSessionExpired(jqXHR.responseText)) {
                    return;
                }

                //console.log(jqXHR, textStatus, errorThrown);

                toastErrorBig(jqXHR.responseText);
                //$('#docRepProgressBar').hide();
                //unlockMain();

            }
        });
    });


    setTimeout(function () {

        dsFormPrjSheetObject.runFuncQueue();

        dsFormPrjSheetObject.fillCalendar();
        dsFormPrjSheetObject.calculateWorkDays();
        dsFormPrjSheetObject.calculateWorkDaysAgreed();
        dsFormPrjSheetObject.calculateWorkDaysActual();
        dsFormPrjSheetObject.resize();
        dsFormPrjSheetObject.makeBuildMenu();


    }, 0);

    //dsFormPrjSheetObject.resizeGrid();

    $('#PrjscrollPart').cgbMakeScrollbar({autoWrapContent: false, alwaysShowScrollbar: 0, theme: 'dark'});


</script>

<form id="formMatrix" style="display: none">
    <input type="number" value="7" id="schdata" style="width: 50px; height: 24px; text-align: right ">
    <input type="file" name="fileupload" value="fileupload" id="fileupload">
</form>

