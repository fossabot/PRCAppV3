<?php include_once APPPATH . 'views/viewIncludeFilter.php'; ?>

<style>
    .bxBL {
        position: absolute;
        bottom: 10px;
        left: 10px;
        width: 60px;
    }

    .btnSC {
        font-size: 9px !important;
        opacity: 1  !important;;
    }

    .bxBR {
        position: absolute;
        bottom: 10px;
        right: 10px;
        width: 60px;
    }

    .boxExpHeader{
        font-weight: bold;
        padding-bottom: 3px;
    }

    .boxgb {
        font-size: 9px;
        width: 100%;
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
        thisObj.vGantt = undefined;
        // funcao de inicio;
        this.start = function (gridNamePar) {
            thisObj.gridName = gridNamePar;
            if (w2ui[thisObj.gridName] !== undefined) {
                w2ui[thisObj.gridName].destroy();
            }

            $('#mainTabsDiv').ctabStart({afterChanged: thisObj.tabAfterChanged});
            $('#tab_grant_div').append($('#ganttArea').detach())


<?php echo ($javascript); ?>
            this.addListeners();
            this.addHelper();
        }

        this.startGantt = function (xml) {
            return;
            thisObj.vGantt = new JSGantt.GanttChart(document.getElementById('ganttArea'), 'week');
            if (thisObj.vGantt.getDivId() != null) {
                thisObj.vGantt.setCaptionType('Complete'); // Set to Show Caption (None,Caption,Resource,Duration,Complete)
                thisObj.vGantt.setQuarterColWidth(36);
                thisObj.vGantt.setRowHeight(36);
                thisObj.vGantt.setUseToolTip(0);
                thisObj.vGantt.setUseSort(0);
                thisObj.vGantt.setDateTaskDisplayFormat('day dd month yyyy'); // Shown in tool tip box
                thisObj.vGantt.setDayMajorDateDisplayFormat('mon yyyy - Week ww') // Set format to display dates in the "Major" header of the "Day" view
                thisObj.vGantt.setWeekMinorDateDisplayFormat('dd mon') // Set format to display dates in the "Minor" header of the "Week" view
                thisObj.vGantt.setShowTaskInfoLink(1); // Show link in tool tip (0/1)
                thisObj.vGantt.setShowEndWeekDate(0); // Show/Hide the date for the last day of the week in header for daily view (1/0)
                thisObj.vGantt.setUseSingleCell(10000); // Set the threshold at which we will only use one cell per table row (0 disables).  Helps with rendering performance for large charts.
                thisObj.vGantt.setFormatArr('Day', 'Week', 'Month', 'Quarter'); // Even with setUseSingleCell using Hour format on such a large chart can cause issues in some browsers
                thisObj.vGantt.setShowTaskInfoDur(0);
                thisObj.vGantt.setShowDur(0);

                JSGantt.parseXMLString(xml, thisObj.vGantt);
                thisObj.vGantt.Draw();
            }
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

            if (bPressed == 'retrieve') {
                w2ui[thisObj.gridName].retrieve();
            }
            if (bPressed == 'filter') {
                hideFilter();
            }

        }


        // adicao de listeners!
        this.addListeners = function () {

            $(window).on('schChanged', function (a, b) {
                $.each(b.gridData, function (i, v) {
                    w2ui[thisObj.gridName].set(v.recid, v);
                })

            });


            w2ui[thisObj.gridName].on('retrieveOnAdd', function (e) {

                e.onComplete = function (event) {
                    thisObj.startGantt(event.data.xml);
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
            introRemove();
            $(window).off('schChanged');
            return true;
        }

        this.renderBuilds = function (record, index, column_index) {
            if (record == undefined) {
                return;
            }
            
            var info = record.sch;
            var html = '<div style="display: flex">';
            $.each(info, function (i, v) {

                var vtitle = v.ds_project_build_abbreviation;
                if (v.fl_allow_multiples == 'Y' && v.cd_project_build_schedule != -1) {
                    vtitle = vtitle + v.nr_version;
                }
                var vAddSchBtn = "<button type='button'  title='<?php echo($addBtn) ?>' class='btn btn-box-tool btnSC'titleInf='" + vtitle + "' id = 'btnTests" + v.cd_project + "-" + v.cd_project_model + "-" + v.cd_project_build + "' onclick='dsMainObject.editSch(-1, " + v.cd_project + "," + v.cd_project_model + "," + v.cd_project_build + ", this);'> <i class='fa fa-plus'></i> </button>";
                var vEditSchBtn = "<button type='button' title='<?php echo($editBtn) ?>' class='btn btn-box-tool btnSC' titleInf='" + vtitle + "' id = 'btnEdit" + v.cd_project_build_schedule + "' onclick='dsMainObject.editSch(" + v.cd_project_build_schedule + ", " + v.cd_project + "," + v.cd_project_model + "," + v.cd_project_build + ", this);'> <i class='fa fa-calendar'></i> </button>";
                var vTestsSchBtn = "<button type='button' title='<?php echo($tstBtn) ?>' class='btn btn-box-tool btnSC' titleInf='" + vtitle + "' id = 'btnTests" + v.cd_project_build_schedule + "' onclick='dsMainObject.editLabTests(" + v.cd_project_build_schedule + "," + v.cd_project + ", this);'> <i class='fa fa-wrench'></i> </button>";
                var vBtns = '';
                var internalData = '';
                var addBoxCss = '';
                if (v.cd_project_build_schedule == -1) {
                    internalData = '<?php echo($noinfo) ?>';
                    addBoxCss = addBoxCss + 'opacity: 0.5';
                    vBtns = vBtns + vAddSchBtn;
                } else {
                    internalData = internalData + '<div style="font-size: 10px">'
                    internalData = internalData + '<div class="boxExpHeader"><?php echo($estdate) ?></div>'
                    internalData = internalData + '<div>' + v.dt_est_start + '~ ' + v.dt_est_finish + '</div>';
                    internalData = internalData + '<div class="bxBL"><span class="badge bg-blue boxgb"><?php echo($tstLabel) ?>: ' + v.nr_test_count + '</span></div>';
                    internalData = internalData + '<div class="bxBR"><span class="badge bg-blue boxgb"><?php echo($trLabel) ?>: ' + v.nr_test_request_count + '</span></div>';
                    internalData = internalData + "<br><div style='width: 100%'></hr></div>";
                    internalData = internalData + '  </div>';
                    vBtns = vBtns + vEditSchBtn;
                    vBtns = vBtns + vTestsSchBtn;
                    if (v.fl_allow_multiples == 'Y') {
                        vBtns = vBtns + vAddSchBtn;
                    }
                }


                html = html + ' <div class="box box-info box-solid" style="margin-left: 5px;width: 150px;' + addBoxCss + '">';
                html = html + '    <div class="box-header with-border">';
                html = html + '       <h3 class="box-title">' + vtitle + '</h3>';
                html = html + '       <div class="box-tools pull-right">';
                html = html + vBtns + '</div>';
                html = html + '    </div>';
                html = html + '  <div class="box-body" style="height: 100px;;">';
                html = html + internalData;
                html = html + '  </div>';
                html = html + ' </div> ';
            });
            html = html + ' </div> ';
            return html;
        }



        // funcaoes gerais 

        this.editSch = function (vcd_project_build_schedule, vcd_project, vModel, vcd_project_build, btnObj) {
            var title = '<?php echo ($editSchTitle) ?>';
            var what = $(btnObj).attr('titleInf');
            openFormUiBootstrap(
                    title + ' ' + what,
                    'schedule/project_build_schedule/callSchForm/' + vcd_project_build_schedule + '/' + vcd_project_build + '/' + vcd_project + '/' + vModel,
                    'col-lg-8 col-lg-offset-2 col-sm-10 col-sm-offset-1'
                    );
        }

        this.editLabTests = function (vcd_project_build_schedule, prj, btnObj) {
            var what = $(btnObj).attr('titleInf');
            var title = '<?php echo ($editLabTitle) ?>';
            openFormUiBootstrap(
                    title + ' ' + what,
                    'schedule/project_build_schedule_tests/openSchTstForm/' + vcd_project_build_schedule + '/' + prj,
                    'col-sm-10 col-sm-offset-1'
                    );
        }

        this.tabAfterChanged = function (id) {
            setGrpGridHeight();
        }

    }

// funcoes iniciais;
    dsMainObject.start(gridName);
// funcao da toolbar
    function onGridToolbarPressed(bPressed, dData) {
        dsMainObject.ToolBarClick(bPressed, dData);
    }

    makeFilterWithEnter(function () {
        dsMainObject.ToolBarClick('retrieve', undefined);
    });

// insiro colunas;
    function setGrpGridHeight() {
        var hAvail = getWorkArea();
        $("#tab_default_div").css("height", hAvail - 50);
        $("#ganttArea").css("height", hAvail - 50);
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
// insiro colunas;


</script>

<div id="ganttArea" class="col-md-12 no-padding gantt" style="background-color: white"></div>

<div class="row">
    <?php echo ($tab); ?>
</div>







