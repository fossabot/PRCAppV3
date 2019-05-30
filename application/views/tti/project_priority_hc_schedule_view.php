
<?php
// PHP page that has the filter area.
include_once APPPATH . 'views/viewIncludeFilter.php';
?>
<style> 

    .dataTables_filter {
        display: none; 
    }

    .classPriority {
        text-align: center;
    }
    .classProject2 {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 150px;
    }

    .classExistingHC {
        text-align: center;
    }

    .classNoHC {
        text-align: center;
    }

    .leftBorder {
        border-left: #000 solid 1px !important;
    }

    .groupHeader {
        background-color: lightgray;
    }

    .basicHeader {
        background-color: lightgray;
    }


</style>
<script>

// aqui tem os scripts basicos. 
    var gridName = "gridGeneric";



    var dsMainObject = new function () {

        // Private Variables;
        var thisObj = this;
        thisObj.gridName = undefined;
        thisObj.showDetailed = false;
        thisObj.canChange = '<?php echo($fl_change_project_priority) ?>';

        // The starting function
        this.start = function (gridNamePar) {
            thisObj.gridName = gridNamePar;
            thisObj.vHTMLArea = $('#makeAreaEdit').html();
            $('#makeAreaEdit').empty();

            if (w2ui[thisObj.gridName] !== undefined) {
                w2ui[thisObj.gridName].destroy();
            }

// javascript received from controller with the grid.
<?php echo ($javascript); ?>


            this.addListeners();
            this.addHelper();

            $("#ds_search").on('keypress', function (e) {
                if (e.which == 13 && thisObj.table != null) {
                    thisObj.table.search(this.value).draw();
                }
            });


            setTimeout(function () {
                thisObj.retrieveData();
            }, 0);

        }

        // add helper on the question mark button. 
        this.addHelper = function () {
            var arrayHelper = [];
            $.merge(arrayHelper, introAddFilterArea());
            //$.merge(arrayHelper, w2ui[thisObj.gridName].toolbar.getIntroHelp());
            //$.merge(arrayHelper, w2ui[thisObj.gridName].getIntroHelp());

            introAddNew({steps: arrayHelper});
        }

        // Toolbar functions
        this.ToolBarClick = function (bPressed, dData) {

            if (bPressed == 'retrieve') {
            }
            if (bPressed == "update") {
                //w2ui[thisObj.gridName].update();
            }

            if (bPressed == 'filter') {
                hideFilter();
            }
        }


        // Function to add listeners (events). Here is empty but it is part of the basic object structure
        this.addListeners = function () {




        }

        // It runs before close the screen (by choosing another option on the menu, for example). If you return false the system will not leave the screen
        this.beforeClose = function () {
            return w2ui[thisObj.gridName].getChanges().length == 0;
        }


        // Event that will be triggered when the object is being closed. Location to remove listeners, destroy grids, etc....
        this.close = function () {
            introRemove();
            return true;
        }

        // Place to add general functions

        this.openEdit = function (obj) {
            var vid = $(obj).attr('id');
            thisObj.cd_project_model_selected = $(obj).parent().attr('prj');
            thisObj.cd_project_build_schedule_tests_selected = $(obj).parent().attr('tst');
            thisObj.build_selected = $(obj).parent().attr('build');

            basicPickListOpenPopOver({
                title: 'Edit Priority',
                target: '#' + vid,
                html: thisObj.vHTMLArea,
                plVarSuffix: 'xEdit',
                showClose: true,
                position: 'top-right',
                width: '240px',
                functionOpen: function () {

                    var vpr = $('#priorityID' + thisObj.cd_project_build_schedule_tests_selected).text();
                    $('#nr_priority').val(vpr);
                    $('#nr_priority').autoNumeric('init', {aSep: '', mDec: 0});

                    $('#fl_all_model').iCheck({
                        checkboxClass: 'icheckbox_square-blue',
                        radioClass: 'iradio_square',
                        //increaseArea: '%' // optional
                    });

                    $('#nr_priority').focus().select().on('keypress', function (e) {
                        if (e.which == 13 && thisObj.table != null) {
                            thisObj.updateData();
                        }
                    });

                    //$('#nr_priority').autoNumeric('update');

                },
                plCallBack: function (code, desc, data) {

                }
            });


        }


        this.retrieveData = function () {
            var vs = moment($('#filter_range_from').val(), defaultDateFormatUpper);
            var ve = moment($('#filter_range_to').val(), defaultDateFormatUpper);





            var vdate = {where: retFilterInformed(2), start: vs.format('YYYY-MM-DD'), end: ve.format('YYYY-MM-DD')};

            console.time('StartAjax');


            $.myCgbAjax({url: 'tti/project_priority_hc_schedule/retrieveData',
                message: javaMessages.retrieveInfo,
                box: $('.content-wrapper'),
                data: vdate,

                success: function (data) {
                    console.timeEnd('StartAjax');
                    thisObj.mountTable(data.resultset[0]);
                },
                errorAfter: function () {

                }});
        }


        this.updateData = function () {
            //thisObj.cd_project_model_selected =  $(obj).parent().attr('prj');
            //thisObj.cd_project_build_schedule_tests_selected =  $(obj).parent().attr('tst');
            //thisObj.build_selected = $(obj).parent().attr('build');
            var vdata = $('#nr_priority').autoNumeric('get');
            var vselector = '[tst="' + thisObj.cd_project_build_schedule_tests_selected + '"][vdata="Priority"]';
            var vchecked = $('#fl_all_model').is(':checked');

            if (vchecked) {
                vselector = vselector + ',[prj="' + thisObj.cd_project_model_selected + '"][vdata="Priority"][build="' + thisObj.build_selected + '"]';
            }

            //var data = thisObj.table.rows().data();

            var vupd = [];

            var columns = thisObj.table.cells(vselector).each(function (value, index) {
                console.log('value', );
                $.each(value, function (i, v) {
                    var vrowdata = thisObj.table.row(v.row).data()
                    var vd = {recid: vrowdata[0], 'nr_priority': vdata, row: v.row};
                    vupd.push(vd);
                });
            });

            SBSModalVarPopupxEdit.close();
//            var $fiding = $('#tableplace').find(vselector);

            console.log('what', vupd);

            var toSend = {"upd": JSON.stringify(vupd)};

            $.myCgbAjax({url: 'schedule/project_build_schedule_tests/updateDataJson',
                box: $('.content-wrapper'),
                message: javaMessages.updating,
                data: toSend,
                success: function (data) {
                    if (data.status == "OK") {

                        $.each(vupd, function (i, v) {
                            var vrowdata = thisObj.table.row(v.row).data();
                            vrowdata[2] = vdata;
                            thisObj.table.row(v.row).data(vrowdata);
                        });

                        thisObj.table.draw('page');

                        toastSuccess(javaMessages.update_done);

                    } else {
                        toastErrorBig(javaMessages.error_upd + data.status);
                    }
                }
            });







        }

        this.mountTable = function (data) {

            console.time('StartMakingTable');
            var vhtmlrow = '';
            var vstartCol = 13;
            var vtotalDays = data.json_dates.length;
            var vStartDateObj = moment(data.json_dates[0].nr_time, 'MMDDYYYY');
            var vEndDate = moment(data.json_dates[vtotalDays - 1].nr_time, 'MMDDYYYY');
            var vEmptyCol = '<td ></td>';
            var columnDefs = [];
            var vheadertop = '<tr><th colspan="7" style="text-align: center" class="groupHeader">Project Details</th>'
            vheadertop = vheadertop + '<th colspan="14" style="text-align: center" class="groupHeader"></th>'


            var vhtmlheader = '<tr>';
            vhtmlheader = vhtmlheader + '<th class="basicHeader">TST Code</th>';
            columnDefs.push({width: "30px", targets: columnDefs.length, orderable: false, visible: false});

            vhtmlheader = vhtmlheader + '<th class="basicHeader">&nbsp</th>';
            columnDefs.push({width: "30px", targets: columnDefs.length, orderable: false, visible: thisObj.canChange == 'Y'});
            vhtmlheader = vhtmlheader + '<th class="basicHeader">Priority</th>';
            columnDefs.push({width: "50px", targets: columnDefs.length, class: 'classPriority'});
            vhtmlheader = vhtmlheader + '<th class="basicHeader">Status</th>';
            columnDefs.push({width: "50px", targets: columnDefs.length});
            vhtmlheader = vhtmlheader + '<th class="basicHeader">Work Order#</th>';
            columnDefs.push({width: "80px", targets: [columnDefs.length]});

            vhtmlheader = vhtmlheader + '<th class="basicHeader">Project</th>';
            columnDefs.push({width: "130px", targets: [columnDefs.length], class: 'classProject'});

            vhtmlheader = vhtmlheader + '<th class="basicHeader">Department</th>';
            columnDefs.push({width: "60px", targets: [columnDefs.length], class: ''});

            vhtmlheader = vhtmlheader + '<th class="basicHeader">Build</th>';
            columnDefs.push({width: "60px", targets: [columnDefs.length], class: ''});

            vhtmlheader = vhtmlheader + '<th class="basicHeader">Type</th>';
            columnDefs.push({width: "100px", targets: [columnDefs.length]});
            vhtmlheader = vhtmlheader + '<th class="basicHeader">Test Item</th>';
            columnDefs.push({width: "100px", targets: [columnDefs.length]});
            vhtmlheader = vhtmlheader + '<th class="basicHeader">TTI Project#</th>';
            columnDefs.push({width: "100px", targets: [columnDefs.length]});
            vhtmlheader = vhtmlheader + '<th class="basicHeader">MT Project #</th>';
            columnDefs.push({width: "100px", targets: [columnDefs.length]});
            vhtmlheader = vhtmlheader + '<th class="basicHeader">TTI Model#</th>';
            columnDefs.push({width: "100px", targets: [columnDefs.length]});
            vhtmlheader = vhtmlheader + '<th class="basicHeader">MT Model#</th>';
            columnDefs.push({width: "100px", targets: [columnDefs.length]});
            vhtmlheader = vhtmlheader + '<th class="basicHeader">PRC PM</th>';
            columnDefs.push({width: "100px", targets: [columnDefs.length]});
            vhtmlheader = vhtmlheader + '<th class="basicHeader">PRC ENG</th>';
            columnDefs.push({width: "100px", targets: [columnDefs.length]});
            vhtmlheader = vhtmlheader + '<th class="basicHeader">TE</th>';
            columnDefs.push({width: "100px", targets: [columnDefs.length]});
            vhtmlheader = vhtmlheader + '<th class="basicHeader">Qty</th>';
            columnDefs.push({width: "50px", targets: [columnDefs.length]});

            vhtmlheader = vhtmlheader + '<th class="basicHeader">HC</th>';
            columnDefs.push({width: "30px", targets: [columnDefs.length]});
            vhtmlheader = vhtmlheader + '<th class="basicHeader">Start</th>';
            columnDefs.push({width: "80px", targets: [columnDefs.length]});
            vhtmlheader = vhtmlheader + '<th class="basicHeader">End</th>';
            columnDefs.push({width: "80px", targets: [columnDefs.length]});

            var vcont = 0;
            var vold = data.json_dates[0].ds_month;


            $.each(data.json_dates, function (i, v) {
                var vday = moment(v.nr_time, 'MMDDYYYY');

                columnDefs.push({width: "25px", targets: [columnDefs.length], class: 'classExistingHC'});
                var vdayx = vday.format('DD');
                if (i == 0 || vdayx == 1) {
                    vhtmlheader = vhtmlheader + '<th class="leftBorder basicHeader">' + vdayx + '</th>';
                } else {
                    vhtmlheader = vhtmlheader + '<th class="basicHeader">' + vdayx + '</th>';
                }




                data.json_dates[i].obj_date_moment = vday;

                vcont++;
                if (vold != v.ds_month) {
                    vheadertop = vheadertop + '<th colspan="' + (vcont - 1) + '" style="text-align: center" class="leftBorder groupHeader">' + vold + '</th>';
                    vold = v.ds_month;
                    vcont = 1;
                }
            });
            vheadertop = vheadertop + '<th colspan="' + vcont + '" style="text-align: center" class="leftBorder groupHeader">' + data.json_dates[ data.json_dates.length - 1].ds_month + '</th>';

            vhtmlheader = vhtmlheader + '</tr>';
            vheadertop = vheadertop + '</tr>'

            vhtmlheader = '<thead>' + vheadertop + vhtmlheader + '</thead>';


            $.each(data.json_projects, function (ii, vv) {
                var vStPrjObj = moment(vv.dt_start, 'YYYY-MM-DD');
                var vFinPrjObj = moment(vv.dt_finish, 'YYYY-MM-DD');

                var vValueCol = '<td>' + vv.nr_headcount_requested_day + '</td>';

                vhtmlrow = vhtmlrow + '<tr>';


                vhtmlrow = vhtmlrow + '<td>' + vv.cd_project_build_schedule_tests + '</td>';
                vhtmlrow = vhtmlrow + '<td prj="' + vv.cd_project_model + '" tst="' + vv.cd_project_build_schedule_tests + '" build="' + vv.ds_project_build_full + '" "><button type="button" id="btn' + vv.cd_project_build_schedule_tests + '" onClick="dsMainObject.openEdit(this); return false;" class="btn btn-primary btn-xs"><i class="fa fa-pencil" ></i></button> </td>';
                vhtmlrow = vhtmlrow + '<td prj="' + vv.cd_project_model + '" tst="' + vv.cd_project_build_schedule_tests + '" id="priorityID' + vv.cd_project_build_schedule_tests + '" build="' + vv.ds_project_build_full + '" vdata="Priority" >' + vv.nr_priority + '</td>';
                vhtmlrow = vhtmlrow + '<td>' + vv.ds_project_status + '</td>';
                vhtmlrow = vhtmlrow + '<td>' + chkUndefined(vv.ds_work_order, '') + '</td>';
                vhtmlrow = vhtmlrow + '<td>' + vv.ds_project + ' ' + vv.ds_project_model + '</td>';
                vhtmlrow = vhtmlrow + '<td>' + chkUndefined(vv.ds_department, '') + '</td>';
                vhtmlrow = vhtmlrow + '<td>' + chkUndefined(vv.ds_project_build_full, '') + '</td>';
                vhtmlrow = vhtmlrow + '<td>' + chkUndefined(vv.ds_test_type, '') + '</td>';
                vhtmlrow = vhtmlrow + '<td>' + chkUndefined(vv.ds_test_item, '') + '</td>';
                vhtmlrow = vhtmlrow + '<td>' + chkUndefined(vv.ds_tti_project, '') + '</td>';
                vhtmlrow = vhtmlrow + '<td>' + chkUndefined(vv.ds_met_project, '') + '</td>';
                vhtmlrow = vhtmlrow + '<td>' + chkUndefined(vv.ds_tti_project_model, '') + '</td>';
                vhtmlrow = vhtmlrow + '<td>' + chkUndefined(vv.ds_met_project_model, '') + '</td>';
                vhtmlrow = vhtmlrow + '<td>' + chkUndefined(vv.ds_human_resource_prc_pm, '') + '</td>';
                vhtmlrow = vhtmlrow + '<td>' + chkUndefined(vv.ds_human_resource_eng, '') + '</td>';
                vhtmlrow = vhtmlrow + '<td>' + chkUndefined(vv.ds_human_resource_te, '') + '</td>';
                vhtmlrow = vhtmlrow + '<td style="text-align: center;">' + chkUndefined(vv.nr_sample_quantity, '') + '</td>';
                vhtmlrow = vhtmlrow + '<td style="text-align: center;">' + chkUndefined(vv.nr_headcount_requested_day, '') + '</td>';

                vhtmlrow = vhtmlrow + '<td>' + vStPrjObj.format('MM/DD/YYYY') + '</td>';
                vhtmlrow = vhtmlrow + '<td>' + vFinPrjObj.format('MM/DD/YYYY') + '</td>';


                if (vFinPrjObj.isAfter(vEndDate, 'days')) {
                    vFinPrjObj = vEndDate;
                }

                if (vStPrjObj.isBefore(vStartDateObj, 'days')) {
                    vStPrjObj = vStartDateObj;
                }

                var voffsetstart = vStPrjObj.diff(vStartDateObj, 'days');
                var vqttyfilled = vFinPrjObj.diff(vStPrjObj, 'days') + 1;
                var voffsetend = 0;

                voffsetend = vEndDate.diff(vFinPrjObj, 'days');

                if (voffsetstart > 0) {
                    vhtmlrow = vhtmlrow + vEmptyCol.repeat(voffsetstart);
                }

                if (vqttyfilled > 0) {
                    vhtmlrow = vhtmlrow + vValueCol.repeat(vqttyfilled);
                }

                if (voffsetend > 0) {
                    vhtmlrow = vhtmlrow + vEmptyCol.repeat(voffsetend);
                }

                vhtmlrow = vhtmlrow + '</tr>';
            });

            var vhtmlString = '<table id="tableData" class="table  table-condensed table-striped table-bordered order-column">' + vhtmlheader + ' <tbody>' + vhtmlrow + '</tbody></table>';
            $('#tableplace').html(vhtmlString);
            console.timeEnd('StartMakingTable');

            console.time('dataTables');

            var vqttydd = select2GetData('filter_page');
            if (vqttydd == null) {
                var vqtty = 10000;
            } else {
                var vqtty = vqttydd.iddesc;
            }


            var vdatatable = {
                scrollX: true,
                width: '100%',


                scrollY: '100px',
                scrollCollapse: false,

                paging: true,
                "columnDefs": columnDefs,
                //"lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
                fixedColumns: {
                    leftColumns: 7
                },
                select: true,
                "pageLength": parseInt(vqtty),
                order: [[1, 'asc'], [2, 'desc'], [4, 'asc'], [5, 'asc']],
                dom: 'RBfrtip',
                buttons: [
                    'copy', {
                        extend: 'excel',
                        customize: function( xlsx ) {
                            var sheet = xlsx.xl.worksheets['sheet1.xml'];
                            $('row:eq(1) c', sheet).attr( 's', '50' );
                        }
                    }, 'pdf'
                ],
                  "destroy": true


            };

            thisObj.table = $('#tableData').DataTable(vdatatable);
            $('.dt-buttons').hide();
            new $.fn.dataTable.ColReorder(thisObj.table);



            console.timeEnd('dataTables');




            console.time('resize');
            setGrpGridHeight();
            console.timeEnd('resize');


        }


    }

// funcoes iniciais;
    dsMainObject.start(gridName);

// funcao da toolbar
    function onGridToolbarPressed(bPressed, dData) {
        dsMainObject.ToolBarClick(bPressed, dData);
    }

    makeFilterWithEnter(function () {
        dsMainObject.ToolBarClick('retrieve');
    });

    function setGrpGridHeight() {
        var hAvail = getWorkArea();
        //$('#tableplace').height(hAvail - 50);
        //

        if (dsMainObject.table != undefined) {
            $('.dataTables_scrollBody').height(hAvail - 190);
            dsMainObject.table.draw('page');
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
<div class="row" style="background-color: white; border: lightgrey 1px solid; padding: 5px; ">
    <div id="gantFilter" style="height: 40px">
        <div class='col-md-12 no-padding'>

            <div class="btn-group pull-left" role="group" aria-label="">

                <button class="btn btn-default btn-sm" onclick="dsMainObject.retrieveData();return false;"><i class='fa fa-refresh' style="padding-top: 7px"></i> </button>
                <button class="btn btn-default btn-sm" onclick="hideFilter();return false;"><i class='fa fa-bars' style="padding-top: 7px"></i> </button>                
                <button class="btn btn-default btn-sm" onclick="$('.buttons-excel').click();return false;" ><i  class='fa fa-file-excel-o' style="padding-top: 7px"></i> </button>
                <button class="btn btn-default btn-sm" onclick="$('.buttons-copy').click();return false;" ><i class='fa fa-copy' style="padding-top: 7px"></i> </button>
            </div>

            <div class="col-md-3">
                <div class="input-group">
                    <span class="input-group-addon" id="basic-addon3">Search</span>
                    <input type="text" class="form-control" id="ds_search" aria-describedby="basic-addon3">
                </div>
            </div>



        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div id="tableplace" style="background-color: white;"></div>
        </div>
    </div>

</div>
<div id="makeAreaEdit">
    <div class="editArea" style="width: 200px;">
        <div class="row">
            <div class="col-md-12">

                <div class="row">

                    <label for="nr_priority" class="col-md-8 control-label">Priority:</label>
                    <div class="col-md-4">
                        <input type="text" class="form-control input-sm" style="text-align: right" id="nr_priority">
                    </div>
                </div>
                <div class="row">
                    <label for="fl_all_model" class="col-sm-8 control-label">Same for Build:</label>
                    <div class="col-md-4">
                        <input type="checkbox" class="form-control input-sm pull-left"  id="fl_all_model">
                    </div>
                </div>

            </div>
            <div class="col-md-6"><button type="button btn-sm" class="btn btn-primary" onclick="dsMainObject.updateData();">Save</button></div>
            <div class="col-md-6"><button type="button btn-sm" class="btn btn-warning pull-right" onclick="SBSModalVarPopupxEdit.close();">Close</button></div>
        </div>
    </div>
</div>
