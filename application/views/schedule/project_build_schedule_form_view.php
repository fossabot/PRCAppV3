<style>
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
</style>


<script>
// aqui tem os scripts basicos. 
//var controllerName = "country";



//$(".ds_hr_type").on( "change",  function() {


    var dsFormSchObject = new function () {

        // variaveis privadas;

        var thisObj = this;
        thisObj.gridName = undefined;
        thisObj.action = '<?php echo($action); ?>'
        thisObj.lastSchCode = -20;


        this.start = function () {

<?php
echo($toolbar);

echo($gridDates);
?>



            $().w2grid(vGridDates);


<?php
if ($hasCHK == 1) {
    echo($gridCHK);
};
?>

            var vtoolbar = vGridToToolbar.toolbar;

            vtoolbar.onClick = function (a, b) {
                if (a == 'update') {
                    thisObj.updateData();
                }
            };
            vtoolbar.name = 'SCHFormToolbar';

            if (w2ui['SCHFormToolbar'] != undefined) {
                w2ui['SCHFormToolbar'].destroy();
            }

            $('#schToolbar').w2toolbar(vtoolbar);

            thisObj.Form = $('#formSch').cgbForm({updController: 'schedule/project_build_schedule'});
            thisObj.Form.addGridToControl('gridDates');

            this.setScreenPermissions();

            this.addListeners();
            this.addHelper();

            $(".calcButton").droppable({
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
                    vdiff --;

                    var vdata = JSON.parse($(ui.draggable).attr('data-event'));
                    //console.log($(event.target),  $(ui.draggable)); 
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


            $('.calData').fullCalendar({
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
                    var vtst = [{id: thisObj.lastSchCode, recid: thisObj.lastSchCode, title: Math.abs(event.id) + ' Worker(s)', start: event.start, end: event.start, nr_workers: Math.abs(event.id), cd_project_build_schedule_tests: $vCal.attr('sch')}];

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

            $('.divDrag').draggable({
                revert: true, // immediately snap back to original position
                revertDuration: 0  //
            });


<?php if ($hasCHK == 1) { ?>
                thisObj.Form.addGridToControl('gridCheckPoint');

                this.resizeGrid();
                w2ui['gridCheckPoint'].sort('nr_order', 'asc');
<?php } ?>

            thisObj.makeScreenInformation();

        }




        this.addHelper = function () {
            var arrayHelper = [];
            //$.merge(arrayHelper, introAddFilterArea());
            //$.merge(arrayHelper,w2ui[thisObj.gridName].toolbar.getIntroHelp());
            //$.merge(arrayHelper, w2ui[thisObj.gridName].getIntroHelp());

            //introAddNew({steps: arrayHelper});
        }


        // adicao de listeners!
        this.addListeners = function () {
            $('#formSch').on('pospicklist', function (ev) {
                //console.log(ev);

            })


            $('#formSch').on('itemChanged', function (ev) {
                var vf = ev.fielddata.name;
                if (vf == 'dt_est_finish_tst' || vf == 'dt_est_start_tst') {
                    thisObj.calculateWorkDays();
                }
                
                
                thisObj.setDemandedAsChanged();
            });

            $('#formSch').on('afterUpdate', function (ev) {
                thisObj.action = 'E';
                w2ui['gridDates'].clear()
                w2ui['gridDates'].add(ev.fullData.dates);
                thisObj.makeScreenInformation();


                $(window).trigger('schChanged', ev.fullData);

<?php if ($hasCHK == 1) { ?>
                    w2ui['gridCheckPoint'].clear();
                    w2ui['gridCheckPoint'].add(ev.fullData.checkpoints);
                    thisObj.resizeGrid();
                    w2ui['gridCheckPoint'].sort('nr_order', 'asc');
<?php }; ?>


            });

            // seto o evendo de fechar!!!
            $(window).on("onCloseForm.sch", function (a) {
                if (thisObj.Form.isChanged()) {
                    messageBoxOkCancel(javaMessages.info_changed_close, function () {
                        thisObj.Form.destroy();
                        SBSModalFormsVar.close();
                        $(window).off('onCloseForm.sch');
                    })
                } else {
                    thisObj.Form.destroy();
                    SBSModalFormsVar.close();
                    $(window).off('onCloseForm.sch');
                }
            });


<?php
// codes only related to the grid CheckPooint (that only exists if the setup says so)
if ($hasCHK == 1) {
    ?>
                w2ui['gridCheckPoint'].on('itemChanging', function (ev) {
                    ev.onComplete = function () {
                        this.setItemAsChanged(ev.data.recid, 'cd_project_build_schedule');
                        this.setItemAsChanged(ev.data.recid, 'cd_project_build_checkpoints');

                        thisObj.setDemandedAsChanged();


                    }
                });

                $('#gridChkBox').on('lte.uncollapsed-box', function () {
                    thisObj.resizeGrid();
                })

<?php } ?>




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

        this.makeScreenInformation = function () {
            this.fillCalendar();
            this.calculateWorkDays();
        }

        this.calculateWorkDays = function () {
            var vInfo = this.Form.getInfobyFieldname('nr_goal');
            $.each(vInfo, function (i, v) {
                var vdsf = 'dt_est_start_' + v.order + '_form';
                var vdff = 'dt_est_finish_' + v.order + '_form';
                var vdd = 'nr_days_' + v.order + '_form';
                var $btn = $('.calcButton[sch="'+v.order+'"]'); 



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
                    $btn.css('background-color', '#ddd').css("color", "#000");;
                    return true;
                }

                var vdays = moment().isoWeekdayCalc({
                    rangeStart: vds,
                    rangeEnd: vdf,
                    weekdays: [1, 2, 3, 4, 5, 6], //weekdays Mon to Saturday
                    //exclusions: ['6 Apr 2015', '7 Apr 2015']  //public holidays
                });

                thisObj.Form.setItem(vdd, vdays);
                $btn.css('background-color', '#337ab7').css("color", "#fff");

                

            });

        }

        this.setScreenPermissions = function () {
// insert
            if (thisObj.action == 'I') {

            } else {

            }

        }


        this.resizeGrid = function () {
            var vqqty = w2ui['gridCheckPoint'].records.length;
            var size = 60 + (vqqty * 20);
            $('#gridCheckPointDiv').height(size);
            w2ui['gridCheckPoint'].refresh();
        }

        this.setGridAsChanged = function () {

        }

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
        // funcaoes gerais 

        this.fillCalendar = function () {
            var vrec = w2ui['gridDates'].records;
            console.log(w2ui['gridDates'].records);
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
        
        this.positionCalendarOnFirst = function(ptst) {
            var minD = moment('20300101');
            var vhas = false;
            var vId = '#calendar_' + ptst + '_div';
            $.each(w2ui['gridDates'].records, function(i,v){
                var vd   = moment(w2ui['gridDates'].getItem(v.recid, 'dt_start'), defaultDateFormatUpper);
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


        this.setDemandedAsChanged = function () {
            var vp = thisObj.Form.getItem('cd_project_form');
            thisObj.Form.setItem('cd_project_form', vp);
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
              'workdays': vd,  
              'weekdays': [1,2,3,4,5,6]
            });  

            thisObj.Form.setItem(vdf, vcalc.format(defaultDateFormatUpper));
            thisObj.calculateWorkDays();

            this.setDemandedAsChanged();
        }

    }

// funcoes iniciais;
    dsFormSchObject.start();


// insiro colunas;

</script>

<div id="divSchForm" style="max-height: calc(100vh - 40px);" class="" > 
    <div class="row">
        <div id="schToolbar" style="width: 100%;" class="toolbarStyle" ></div>
    </div>


    <form id="formSch" class="form-horizontal">

        <div class='row'>

            <div class="col-sm-2" style='display: none'>
                <input type="text" class="form-control input-sm"   value="<?php echo($cd_project_build_schedule) ?>" fieldname="cd_project_build_schedule" id="cd_project_build_schedule_form"  mask="PK" >
                <input type="text" class="form-control input-sm"   value="<?php echo($cd_project_build) ?>" fieldname="cd_project_build" id="cd_project_build_form"  mask="I" sc='<?php echo($sc); ?>' >
                <input type="text" class="form-control input-sm"   value="<?php echo($cd_project) ?>" fieldname="cd_project" id="cd_project_form"  mask="I" sc='<?php echo($sc); ?>'>
                <input type="text" class="form-control input-sm"   value="<?php echo($cd_project_model) ?>" fieldname="cd_project_model" id="cd_project_model_form"  mask="I" sc='<?php echo($sc); ?>'>
            </div>

            <label for="nr_version_form" class="col-md-2 col-lg-1 control-label "><?php echo($formTrans_nr_version) ?>:</label>
            <div class="col-md-3 col-lg-3">
                <input type="text" class="form-control input-sm"  value="<?php echo($nr_version) ?>" fieldname="nr_version" id="nr_version_form" mask="I" sc='<?php echo($sc); ?>' ro='Y' ru="cd_project_form" >
            </div>

            <label for="dt_est_start_form" class="col-md-2 col-lg-offset-1 col-lg-1  control-label "><?php echo($formTrans_dt_est_start) ?>:</label>
            <div class='col-md-5  col-lg-6'>
                <div class="input-group ">
                    <input type="text" class="form-control input-sm"   value="<?php echo($dt_est_start) ?>" fieldname="dt_est_start" id="dt_est_start_form" ru="cd_project_form">
                    <div class="input-group-addon" style='padding: 5px;'> - </div>
                    <input type="text" class="form-control input-sm"   value="<?php echo($dt_est_finish) ?>" fieldname="dt_est_finish" id="dt_est_finish_form" ru="cd_project_form">
                </div>
            </div>

            <label for="ds_comments_form" class="col-md-2  col-lg-1  control-label"><?php echo($formTrans_ds_comments) ?>:</label>
            <div class="col-md-10  col-lg-11">
                <textarea class="form-control input-sm"  fieldname="ds_comments" id="ds_comments_form" mask="c" ru="cd_project_form"><?php hecho($ds_comments) ?></textarea>
            </div>
        </div>

        <?php if ($hasCHK == 1) { ?>

            <div class="row">
                <div class="col-md-12">    
                    <div class="box box-info box-solid" id="gridChkBox">
                        <div class="box-header with-border">
                            <h3 class="box-title"> <?php echo($checklist) ?> </h3>
                            <div class="box-tools pull-right">
                                <button type='button' class='btn btn-box-tool' data-widget="collapse"> <i class='fa fa-compress'></i> </button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div id="gridCheckPointDiv" style="width: 100%"></div>
                        </div>
                    </div> 
                </div>
            </div>
        <?php } ?>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-success box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title"> <?php echo($tests) ?> </h3>
                        <div class="box-tools pull-right">
                            <button type='button' class='btn btn-box-tool' > <i class='fa fa-plus'></i> </button>
                            <button type='button' class='btn btn-box-tool' data-widget="collapse"> <i class='fa fa-compress'></i> </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div id="testScheduleDiv" style="width: 100%">
                            <?php echo($htmlTests) ?>
                        </div>
                    </div>
                </div> 
            </div>
        </div>        

    </form>
</div>



