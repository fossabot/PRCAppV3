<?php include_once APPPATH . 'views/viewIncludeFilter.php'; ?>

<script>
    // aqui tem os scripts basicos.
    var gridName = "gridGeneric";
    //var controllerName = "country";


    //$(".ds_hr_type").on( "change",  function() {


    var dsMainObject = new function () {

        // variaveis privadas;

        var thisObj = this;
        thisObj.gridName = undefined;


        // funcao de inicio;
        this.start = function (gridNamePar) {
            thisObj.gridName = gridNamePar;
            thisObj.bolShowTitle = -1;

            if (w2ui[thisObj.gridName] !== undefined) {
                w2ui[thisObj.gridName].destroy();
            }

            <?php echo($javascript); ?>


            this.addListeners();
            this.addHelper();

            setTimeout(function () {
                w2ui[thisObj.gridName].retrieve();
            }, 0);

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

            if (bPressed == 'showtitle') {
                thisObj.bolShowTitle = -thisObj.bolShowTitle;
                $("#gridTitleDiv").toggle();
                setGrpGridHeight();
            }
            if (bPressed == 'insert') {
                w2ui[thisObj.gridName].insertRow();
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

            if (bPressed == 'titleinsert') {
                var vpk = w2ui[thisObj.gridName].getPk();
                if (vpk == -1) {
                    return;
                }

                w2ui['gridTitle'].insertRow({
                    funcAfter: function (a) {

                        var hasC = w2ui[thisObj.gridName].getChanges().length > 0;

                        w2ui['gridTitle'].setItem(a.recid, 'cd_course', vpk);

                        if (!hasC) {
                            w2ui[thisObj.gridName].mergeChanges();

                        }

                    }
                });

            }

            if (bPressed == "titledelete") {
                w2ui['gridTitle'].deleteRow({
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

                openRepository({id: 5, code: vcode});
            }
        }


        // adicao de listeners!
        //添加监听器
        this.addListeners = function () {
            w2ui['gridTitle'].on('gridChanged', function (a) {
                thisObj.setChangesOnMainGrid();
            });

            w2ui[thisObj.gridName].on('rowFocusChanging', function (a) {
                a.onComplete = function (b) {
                    w2ui['gridTitle'].clear();
                    if (b.recid_new == -1) {
                        return;
                    }
                    var vx = chkUndefined(w2ui[thisObj.gridName].getItem(b.recid_new, 'title'), []);
                    w2ui['gridTitle'].add(vx);
                }
            });

            w2ui['gridTitle'].on('pickList', function (event) {
                var vid = chkUndefined(w2ui['gridTitle'].getItem(event.recid, 'cd_human_resource_title'), -1);
                var  vpkmain = w2ui[thisObj.gridName].getPk();
                var dsname = chkUndefined(w2ui[thisObj.gridName].getItem(vpkmain, 'ds_course'), 'NOT INFORMED');

                basicPickListOpen({
                    model: '<?php echo($this->encodeModel('human_resource_title_model')); ?>',
                    title: 'Course Name : '+dsname,
                    sel_id: vid,
                    showTitle: false,
                    plCallBack: function(id, desc, record) {
                        w2ui['gridTitle'].setItem(event.recid, 'cd_human_resource_title', id);
                        w2ui['gridTitle'].setItem(event.recid, 'ds_human_resource_title', desc);
                        thisObj.setChangesOnMainGrid();
                    }
                });
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
            return true;
        }


        // funcaoes gerais
        //一般功能

        this.setChangesOnMainGrid = function () {
            var vpk = w2ui[thisObj.gridName].getPk();
            if (vpk == -1) {
                return;
            }

            w2ui['gridTitle'].mergeChanges();
            var vx = w2ui['gridTitle'].getResultsetJson();
            w2ui[thisObj.gridName].setItem(vpk, 'title', vx);

        }
    }

    // funcoes iniciais;
    dsMainObject.start(gridName);

    // funcao da toolbar
    function onGridToolbarPressed(bPressed, dData) {
        dsMainObject.ToolBarClick(bPressed, dData);
    }

    // insiro colunas;插入列

    function setGrpGridHeight() {
        var showTitle = dsMainObject.bolShowTitle;
        var hAvail = getWorkArea();
        $("#myGrid").css("height", hAvail);
        if (showTitle == 1) {
            $("#myGrid").height(Math.round(hAvail * 0.50));
            $("#gridTitleDiv").height(Math.round(hAvail * 0.50));

        }
        else {
            $("#myGrid").height(Math.round(hAvail));
            $("#gridTitleDiv").height(Math.round(hAvail));
        }


        w2ui[gridName].resize();
        w2ui['gridTitle'].resize();

    }

    makeFilterWithEnter(function () {
        dsMainObject.ToolBarClick('retrieve');
    });

    // insiro colunas;
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
    <div id="gridTitleDiv" style="height: auto;display: none" class='col-md-12  no-padding'></div>

</div>
<?php //include_once APPPATH . 'views/includeViewResizeDiv.php'; ?>
