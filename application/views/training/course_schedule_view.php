<?php include_once APPPATH . 'views/viewIncludeFilter.php'; ?>
<?php ?>
<script>
    // aqui tem os scripts basicos.
    //这是基本脚本。
    var gridName = "gridGeneric";
    //var controllerName = "country";


    //$(".ds_hr_type").on( "change",  function() {


    var dsMainObject = new function () {

        // variaveis privadas;
        //私有变量

        var thisObj = this;
        thisObj.gridName = undefined;
        // funcao de inicio;
        //启动功能

        this.start = function (gridNamePar) {
            thisObj.gridName = gridNamePar;
            if (w2ui[thisObj.gridName] !== undefined) {
                w2ui[thisObj.gridName].destroy();
            }

<?php echo($javascript); ?>


            this.addListeners();
            this.addHelper();
            setTimeout(function () {
                w2ui[thisObj.gridName].retrieve();
            }, 0);

            w2ui['gridTrainer'].toolbar.add({
                type: 'html', id: 'searhTrainer',
                html: '<div style="padding: 3px 10px;"> <?php echo($SpecificTrainerStaffNumber) ?> <input id="searchTrainerSN" size="10" style="padding: 3px; border-radius: 2px; border: 1px solid silver" onkeyup="dsMainObject.onKeyUpTrainerSN(event);"/></div>'
            });


            w2ui['gridTraineeGrade'].toolbar.add({
                type: 'html', id: 'searhTrainee',
                html: '<div style="padding: 3px 10px;"> <?php echo($SpecificTraineeStaffNumber) ?> <input id="searchTraineeSN" size="10" style="padding: 3px; border-radius: 2px; border: 1px solid silver" onkeyup="dsMainObject.onKeyUpTraineeSN(event);"/></div>'

            });



        }

        this.onKeyUpTrainerSN = function (e) {

            // capture enter.
            if (e.keyCode === 13) {
                var vpk = w2ui[thisObj.gridName].getPk();
                console.log('foi', e.keyCode, vpk);

                if (vpk == -1) {
                    return;
                }


                var vdata = $('#searchTrainerSN').val();

                if (chkUndefined(vdata, '') == '') {
                    return;
                }
                thisObj.getSpecificTrainerSN(vpk, vdata);
            }
        }

        this.getSpecificTrainerSN = function (vpk, sn) {

            $.myCgbAjax({url: 'training/course_schedule/getTrainerByStaffNumber/' + vpk + '/' + sn,
                success: function (data) {
                    if (data.length == 0) {
                        messageBoxError('<?php echo($TrainerStaffNumberNotFound) ?>');
                        return;
                    }

                    var recs = w2ui['gridTrainer'].find({cd_human_resource: data[0].cd_human_resource});
                    if (recs.length > 0) {
                        messageBoxError('<?php echo($TrainerStaffNumberExists) ?>');
                        return;
                    }

                    w2ui['gridTrainer'].add(data);

                    thisObj.setChangesOnMainGrid();

                }
            });
        }

        this.onKeyUpTraineeSN = function (e) {

            // capture enter.
            if (e.keyCode === 13) {
                var vpk = w2ui[thisObj.gridName].getPk();
                console.log('foi', e.keyCode, vpk);

                if (vpk == -1) {
                    return;
                }


                var vdata = $('#searchTraineeSN').val();

                if (chkUndefined(vdata, '') == '') {
                    return;
                }
                thisObj.getSpecificTraineeSN(vpk, vdata);
            }
        }

        this.getSpecificTraineeSN = function (vpk, sn) {

            $.myCgbAjax({url: 'training/course_schedule/getTraineeByStaffNumber/' + vpk + '/' + sn,
                success: function (data) {
                    if (data.length == 0) {
                        messageBoxError('<?php echo($TraineeStaffNumberNotFound) ?>');
                        return;
                    }

                    var recs = w2ui['gridTraineeGrade'].find({cd_human_resource_trainee: data[0].cd_human_resource_trainee});
                    if (recs.length > 0) {
                        messageBoxError('<?php echo($TraineeStaffNumberExists) ?>');
                        return;
                    }

                    w2ui['gridTraineeGrade'].add(data);

                    thisObj.setChangesOnMainGrid();

                }
            });
        }

        this.addHelper = function () {
            var arrayHelper = [];
            $.merge(arrayHelper, introAddFilterArea());
            $.merge(arrayHelper, w2ui[thisObj.gridName].toolbar.getIntroHelp());
            $.merge(arrayHelper, w2ui[thisObj.gridName].getIntroHelp());
            introAddNew({steps: arrayHelper});
        }



        // funcao de toolbar;
        //工具栏功能
        this.ToolBarClick = function (bPressed, dData) {

            console.log('INSIDE', bPressed);

            if (bPressed == 'insert') {
                w2ui[thisObj.gridName].insertRow({
                    funcAfter: function (e) {
                    }
                });
            }
            if (bPressed == 'retrieve') {
                w2ui[thisObj.gridName].retrieve();
            }
            if (bPressed == "update") {
                w2ui[thisObj.gridName].update();
            }

            if (bPressed == "delete") {
                w2ui[thisObj.gridName].deleteRow();
            }
            if (bPressed == 'filter') {
                hideFilter();
            }

            if (bPressed == 'Trainerinsert') {
                var vpk = w2ui[thisObj.gridName].getPk();
                if (vpk == -1) {
                    return;
                }

                w2ui['gridTrainer'].insertRow({
                    funcAfter: function (a) {

                        var hasC = w2ui[thisObj.gridName].getChanges().length > 0;

                        w2ui['gridTrainer'].setItem(a.recid, 'cd_course_schedule', vpk);

                        if (!hasC) {
                            w2ui[thisObj.gridName].mergeChanges();

                        }

                    }
                });

            }

            if (bPressed == "Trainerdelete") {
                w2ui['gridTrainer'].deleteRow({
                    funcAfter: function () {
                        var hasC = w2ui[thisObj.gridName].getChanges().length > 0;
                        thisObj.setChangesOnMainGrid();
                        if (!hasC) {
                            w2ui[thisObj.gridName].mergeChanges();
                        }

                    }
                });
            }

            if (bPressed == 'TraineeGradeinsert') {
                var vpk = w2ui[thisObj.gridName].getPk();
                if (vpk == -1) {
                    return;
                }



                w2ui['gridTraineeGrade'].insertRow({
                    funcAfter: function (a) {

                        var hasC = w2ui[thisObj.gridName].getChanges().length > 0;

                        w2ui['gridTraineeGrade'].setItem(a.recid, 'cd_course_schedule', vpk);

                        if (!hasC) {
                            w2ui[thisObj.gridName].mergeChanges();

                        }

                    }
                });

            }

            if (bPressed == "TraineeGradedelete") {
                w2ui['gridTraineeGrade'].deleteRow({
                    funcAfter: function () {
                        var hasC = w2ui[thisObj.gridName].getChanges().length > 0;
                        thisObj.setChangesOnMainGrid();
                        if (!hasC) {
                            w2ui[thisObj.gridName].mergeChanges();
                        }

                    }
                });
            }

            if (bPressed == 'docrep') {

                var vcode = w2ui[thisObj.gridName].getPk();
                // check if something is selected!
                if (vcode == -1) {
                    return;
                }

                // check if the row is new. If it is new, cannot insert child table.
                if (w2ui[thisObj.gridName].isNewRow(vcode)) {
                    messageBoxError(javaMessages.saveFirst);
                    return;
                }

                openRepository({id: 6, code: vcode});
            }


            if (bPressed == 'sendScheduleEmail' || bPressed == 'sendGradeEmail') {

                var vpk = w2ui[thisObj.gridName].getPk();
                if (vpk == -1) {
                    return;
                }

                if (w2ui[thisObj.gridName].getChanges().length > 0) {
                    messageBoxError(javaMessages.saveFirst);
                    return;
                }

                messageBoxYesNo('<?php echo($confirmSendMail);?>'
                ,function(){
                    thisObj.sendMail();
                })


            }
            if (bPressed == 'exportMergeExcel') {
                var filter = retFilterInformed(1, []);
                window.open('training/course_schedule/retrieveGridJson/0/' + filter, '_self');
            }

            if (bPressed == 'batchUpdate:batchPass') {

                var vpk = w2ui[thisObj.gridName].getPk();
                if (vpk == -1) {
                    return;
                }

                // var selectRowData = w2ui[thisObj.gridName].getSelection();
                //
                // if (selectRowData.length == 0) {
                //     return;
                // }
                // alert(w2ui['gridTraineeGrade'].records.length);
                for (var i = 0;  i < w2ui['gridTraineeGrade'].records.length; i++) {
                    // alert(w2ui['gridTraineeGrade'].records.recId[i]);
                    w2ui['gridTraineeGrade'].setItem(w2ui['gridTraineeGrade'].records[i].recid, 'cd_course_testing_result', '1');
                    w2ui['gridTraineeGrade'].setItem(w2ui['gridTraineeGrade'].records[i].recid, 'ds_course_testing_result', '<?php echo($TestResultPass)?> ');

                }
                thisObj.setChangesOnMainGrid();
            }

            if (bPressed == 'batchUpdate:batchFail') {

                var vpk = w2ui[thisObj.gridName].getPk();
                if (vpk == -1) {
                    return;
                }

                // var selectRowData = w2ui[thisObj.gridName].getSelection();
                //
                // if (selectRowData.length == 0) {
                //     return;
                // }
                // alert(w2ui['gridTraineeGrade'].records.length);
                for (var i = 0;  i < w2ui['gridTraineeGrade'].records.length; i++) {
                    // alert(w2ui['gridTraineeGrade'].records.recId[i]);
                    w2ui['gridTraineeGrade'].setItem(w2ui['gridTraineeGrade'].records[i].recid, 'cd_course_testing_result', '2');
                    w2ui['gridTraineeGrade'].setItem(w2ui['gridTraineeGrade'].records[i].recid, 'ds_course_testing_result', '<?php echo($TestResultFailed)?>');
                }
                thisObj.setChangesOnMainGrid();
            }

        }


        this.sendMail = function() {
            var vpk = w2ui[thisObj.gridName].getPk();
            if (vpk == -1) {
                return;
            }

            var selectRowData = w2ui[thisObj.gridName].getSelection();

            if (selectRowData.length == 0) {
                return;
            }


            $.myCgbAjax({url: 'training/course_schedule/sendClassMail/' + vpk ,
                success: function (data) {
                    messageBoxAlert('<?php echo($EmailSendSucess);?>');
                }
            });
        }

        // adicao de listeners!
        //添加监听器
        this.addListeners = function () {
            w2ui['gridTrainer'].on('gridChanged', function (a) {
                thisObj.setChangesOnMainGrid();
            });


            w2ui[thisObj.gridName].on('rowFocusChanging', function (a) {

                a.onComplete = function (b) {
                    w2ui['gridTrainer'].clear();
                    w2ui['gridTraineeGrade'].clear();

                    if (b.recid_new == -1) {
                        return;
                    }

                    var vx = chkUndefined(w2ui[thisObj.gridName].getItem(b.recid_new, 'trainer'), []);
                    var vt = chkUndefined(w2ui[thisObj.gridName].getItem(b.recid_new, 'TraineeGrade'), []);

                    if (!$.isArray(vx)) {
                        vx = JSON.parse(vx);
                    }

                    if (!$.isArray(vt)) {
                        vt = JSON.parse(vt);
                    }

                    w2ui['gridTrainer'].add(vx);
                    w2ui['gridTraineeGrade'].add(vt);

                }
            });

            w2ui['gridTraineeGrade'].on('gridChanged', function (a) {
                thisObj.setChangesOnMainGrid();
            });


            w2ui[thisObj.gridName].on('rowFocusChanging', function (a) {

                a.onComplete = function (b) {
                    w2ui['gridTraineeGrade'].clear();
                    if (b.recid_new == -1) {
                        return;
                    }

                    var vx = chkUndefined(w2ui[thisObj.gridName].getItem(b.recid_new, 'TraineeGrade'), []);

                    w2ui['gridTraineeGrade'].add(vx);

                }
            });
            w2ui[thisObj.gridName].on('dblClick', function (a) {
                var vcol = this.columns[a.column].field;

                if (vcol == 'dt_course_start' || vcol == 'dt_course_end') {
                    var vstart = chkUndefined(this.getItem(a.recid, 'dt_course_start'), '');
                    var vend = chkUndefined(this.getItem(a.recid, 'dt_course_end'), '');
                    basicDateTimeRange({startDate: vstart, endDate: vend, title: '<?php echo($titledates);?>',
                        plCallBack: function(start, end) {
                            console.log(start, end);
                            
                            w2ui[thisObj.gridName].setItem(a.recid, 'dt_course_start', start);
                            w2ui[thisObj.gridName].setItem(a.recid, 'dt_course_end', end);

                        }
                    });
                }

            });


//            /

        }


        // roda antes de fechar (se retornar FALSE o sistema vai perguntar se quer fechar
        //在关闭之前（如果FALSE返回，系统将询问您是否要关闭
        this.beforeClose = function () {
            return w2ui[thisObj.gridName].getChanges().length == 0;
        }


        // close object (lugar para destruir as coisas
        //关闭对象（摧毁事物的地方
        this.close = function () {
            w2ui[thisObj.gridName].destroy();
            introRemove();
            return true;
        }

        // funcaoes gerais 
        //一般功能

        this.setChangesOnMainGrid = function () {
            var vpk = w2ui[thisObj.gridName].getPk();
            if (vpk == -1) {
                return;
            }

            w2ui['gridTrainer'].mergeChanges();
            var vx = w2ui['gridTrainer'].getResultsetJson();
            w2ui[thisObj.gridName].setItem(vpk, 'trainer', vx);

            w2ui['gridTraineeGrade'].mergeChanges();
            var vx = w2ui['gridTraineeGrade'].getResultsetJson();

            w2ui[thisObj.gridName].setItem(vpk, 'TraineeGrade', vx);
        }
    }

    // funcoes iniciais;初始功能
    dsMainObject.start(gridName);

    // funcao da toolbar工具栏功能
    function onGridToolbarPressed(bPressed, dData) {
        dsMainObject.ToolBarClick(bPressed, dData);
    }


    // insiro colunas;插入列

    function setGrpGridHeight() {
        var hAvail = getWorkArea();
        $("#myGrid").css("height", hAvail);
        $("#myGrid").height(Math.round(hAvail * 0.50));
        $("#gridTrainerDiv").height(Math.round(hAvail * 0.50));
        $("#gridTraineeGradeDiv").height(Math.round(hAvail * 0.50));


        w2ui[gridName].resize();
        w2ui['gridTrainer'].resize();
        w2ui['gridTraineeGrade'].resize();
    }




    // funcao chamada quando o filtro some. tem que existir se existir filtro!当过滤器消失。 如果有过滤器必须存在
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
    // insiro colunas;插入列
    makeFilterWithEnter(function () {
        dsMainObject.ToolBarClick('retrieve');
    });
</script>

<div id="myGrid" style="height: auto;" class='row'></div>
<div class="row">
    <div id="gridTrainerDiv" style="height: auto;" class='col-md-6  no-padding'></div>
    <div id="gridTraineeGradeDiv" style="height: auto;background-color: yellow" class='col-md-6  no-padding'></div>
</div>