<script>
    // aqui tem os scripts basicos.

    var dsMainRawData = new function () {

        // variaveis privadas;

        var thisObj = this;
        thisObj.gridName = undefined;
        thisObj.allGrid = <?php echo $allGrid; ?>;

        // funcao de inicio;
        this.start = function (gridNamePar) {
            thisObj.gridName = gridNamePar;

            if (w2ui[thisObj.gridName] !== undefined) {
                w2ui[thisObj.gridName].destroy();
            }

            <?php echo($javascript); ?>


            $('#tabMTEData').ctabStart({afterChanged: thisObj.tabAfterChanged});

            thisObj.from = $('input[name="from"]');
            thisObj.sample = $('input[name="sample"]');
            thisObj.workOrder = $('input[name="workOrder"]');
            thisObj.to = $('input[name="to"]');
            thisObj.searchField = $('select[name="searchField"]');

            thisObj.from.autoNumeric('init', {aSep: '', mDec: 0});
            thisObj.sample.autoNumeric('init', {aSep: '', mDec: 0});
            thisObj.to.autoNumeric('init', {aSep: '', mDec: 0});

            thisObj.setTabDisplay();//empty tab no show
            this.addListeners();
            this.resize();
        };

        this.tabAfterChanged = function (newtab, oldtab) {
            thisObj.resize(newtab);

        };

        this.addHelper = function () {

        };

        // funcao de toolbar;
        this.ToolBarClick = function (bPressed, dData) {

        };

        // adicao de listeners!
        this.addListeners = function () {
            $(window).on("onCloseForm", function (a) {
                SBSModalFormsVar.close();
                $(window).off('onCloseForm');
                //viewer plugin do not delete element automatically,do it
                $('div.viewer-container').remove();
            });

            thisObj.searchField.on("change", function (a) {
                thisObj.setFilter();
            });

            //select event already registered, so remain click
            $.each(thisObj.allGrid, function (i, v) {
                w2ui[v].on('click', function (event) {
                    var row = w2ui[v].get(event.recid);
                    thisObj.setFilter(row);
                });
            });

        };

        this.setFilter = function (row) {
            row = chkUndefined(row, '');
            var field = thisObj.searchField.val();
            if (field != '') {
                if (row == '') {
                    var gridActive = $('#tabMTEData').ctabGetSelected();
                    row = w2ui[thisObj.tabToGrid(gridActive)].getSelectedRow();
                }
                if (typeof row != 'undefined') {
                    thisObj.from.val(row[field]);
                    thisObj.workOrder.val($.trim(row['WO_code']));
                    thisObj.sample.val(parseInt(row['Tool_code']));
                }

            } else {
                thisObj.from.val('');
                thisObj.to.val('');
            }
        };

        this.setTabDisplay = function () {
            var fl_active = false;
            var tabs = $('#tabMTEData').ctabList();

            $.each(tabs, function (i, v) {
                var vcount = 0;
                var vgrid = w2ui[thisObj.tabToGrid(v.name)];
                //if (vgrid.searchData.length > 0) {
                //    vcount = vgrid.last.searchIds.length;
                //} else {
                vcount = vgrid.rowcount();
                //}
                if (vcount == 0) {
                    $('#' + v.name).hide();
                } else {
                    $('#' + v.name).show();
                    if (fl_active == false) {
                        $('#tabMTEData').ctabSelect(v.name, {afterChanged: thisObj.tabAfterChanged});
                        fl_active = true;
                    }
                }
            });
        };

        // roda antes de fechar (se retornar FALSE o sistema vai perguntar se quer fechar
        this.beforeClose = function () {
            return true;
        }


        // close object (lugar para destruir as coisas//
        this.close = function () {
            w2ui[this.gridName].destroy();
            introRemove();
            return true;
        }

        this.resize = function (newtab) {
            newtab = chkUndefined(newtab, '<?php echo $firstTab; ?>');
            var vheight = 400, grid = thisObj.tabToGrid(newtab), $el = $('#' + newtab + '_div');
            $el.height(vheight);
            w2ui[grid].resize();
        };

        this.loadRawData = function () {

            var field = thisObj.searchField.val(), from = thisObj.from.autoNumeric('get'),
                to = thisObj.to.autoNumeric('get'), workOrder = $.trim(thisObj.workOrder.val()),
                sample = !thisObj.sample.autoNumeric('get') ? '' : parseInt(thisObj.sample.autoNumeric('get'));

            var workOrderOrigin = $.trim(thisObj.workOrder.attr('origin')),
                sampleOrigin = thisObj.sample.attr('origin');

            if (workOrder == workOrderOrigin && sample == sampleOrigin) {
                thisObj.localFilter(field, from, to);
                return;
            }

            $.myCgbAjax({
                url: 'mte/mte_main/retrieveMTEData',
                message: javaMessages.loading,
                box: '.jconfirm-content-pane',
                data: {workOrder: workOrder, sample: sample, inajax: true},

                success: function (data) {
                    if (data.success == 'true') {
                        thisObj.workOrder.attr('origin', workOrder);
                        thisObj.sample.attr('origin', sample);
                        $.each(data.data, function (i, v) {
                            w2ui[i].clear();
                            w2ui[i].add(v);
                        });
                        thisObj.setTabDisplay();
                        thisObj.localFilter(field, from, to);

                    } else {
                        messageBoxError(data.msg);
                    }

                },
                errorAfter: function () {

                }
            });
        };

        this.localFilter = function (field, from, to) {
            $.each(thisObj.allGrid, function (i, v) {
                if (field && (from || to)) {
                    w2ui[v].searchData = [{
                        field: field,
                        operator: 'between',
                        value: [from == '' ? -999999999 : from, to == '' ? 999999999 : to],
                        type: 'int'
                    }];
                    w2ui[v].localSearch(true);
                    w2ui[v].refresh();
                } else {
                    w2ui[v].reset();
                }
            });
        };

        this.tabToGrid = function (tabName) {
            return tabName.split('tab_')[1] + '_grid';
        };

        this.rawImageList = function (record, index, column_index) {
            if (!record.IV_name_image) return;
            var prefix = 'http://cnsmteproddb01/uploadfile/';
            var el = '<ul style="list-style:none" name="raw_image">';
            $.each(record.IV_name_image.split(';'), function (i, v) {
                el += '<li style="float:left;margin:1px 1px 0 0;"><img src="' + prefix + record.IV_path + '/' + v + '" style="width:auto; height: 32px; "  onclick="dsMainRawData.imageViewer(this)"/></li>';
            });
            el += '</ul>';
            return el;
        };

        this.rawVideoList = function (record, index, column_index) {
            if (!record.IV_name_video) return;
            var prefix = 'http://cnsmteproddb01/uploadfile/';
            var el = '';
            //edge doesn't support play directly
            if (thisObj.IEVersion() >= 9) {
                $.each(record.IV_name_video.split(';'), function (i, v) {
                    el += '<a href="' + prefix + record.IV_path + '/' + v + '" onclick="dsMainRawData.playVideo(this.href);return false;" title="' + v + '" style="margin-right:5px"><i class="fa fa-youtube-play fa-lg"></i></a>';
                });
            } else {
                $.each(record.IV_name_video.split(';'), function (i, v) {
                    el += '<a href="' + prefix + record.IV_path + '/' + v + '" title="' + v + '" style="margin-right:5px"><i class="fa fa-download fa-lg" aria-hidden="true"></i></a>';
                });
            }
            return el;
        };

        this.imageViewer = function (obj) {
            var $ul = $(obj).closest('ul');
            var vstart = chkUndefined($ul.attr('started'), 'N');
            if (vstart == 'N') {
                $ul.viewer();
                $ul.attr('started', 'Y');
                $ul.find('img').removeAttr('onclick');
                setTimeout(function(){$(obj).click();}, 0);// This's important for IE
            }
            return false;
        };

        this.IEVersion = function () {
            var userAgent = navigator.userAgent; //取得浏览器的userAgent字符串
            var isIE = userAgent.indexOf("compatible") > -1 && userAgent.indexOf("MSIE") > -1; //判断是否IE<11浏览器
            var isEdge = userAgent.indexOf("Edge") > -1 && !isIE; //判断是否IE的Edge浏览器
            var isIE11 = userAgent.indexOf('Trident') > -1 && userAgent.indexOf("rv:11.0") > -1;
            if (isIE) {
                var reIE = new RegExp("MSIE (\\d+\\.\\d+);");
                reIE.test(userAgent);
                var fIEVersion = parseFloat(RegExp["$1"]);
                if (fIEVersion == 7) {
                    return 7;
                } else if (fIEVersion == 8) {
                    return 8;
                } else if (fIEVersion == 9) {
                    return 9;
                } else if (fIEVersion == 10) {
                    return 10;
                } else {
                    return 6;//IE版本<=7
                }
            } else if (isEdge) {
                return 'edge';//edge
            } else if (isIE11) {
                return 11; //IE11
            } else {
                return -1;//不是ie浏览器
            }

        };

        this.playVideo = function (href) {
            var x = window.open("", "newwindow", "height=580, width=650, toolbar=no, menubar=no, scrollbars=no, resizable=yes, location=no, status=no");
            x.document.open();
            x.document.write('<object align="middle" classid="CLSID:22d6f312-b0f6-11d0-94ab-0080c74c7e95" class="OBJECT" id="MediaPlayer">\n' +
                '    <param NAME="AUTOSTART" VALUE="true">\n' +
                '    <param name="ShowStatusBar" value="-1"><param name="allowfullscreen" value="true"/>\n' +
                '    <param name="Filename" value="' + href + '">\n' +
                '<embed type="application/x-oleobject" codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=5,1,52,701" filename="mp" src="' + href + '"/></object>');
            x.document.close();
        };

        this.downLoadGrids = function () {
            var allGrid = {};
            $.each(thisObj.allGrid, function (i, v) {
                var vgrid = w2ui[v];
                v = v.split('_data_grid')[0];
                if (vgrid.rowcount() > 0) {
                    allGrid[v] = {};
                    allGrid[v]['col'] = JSON.stringify(vgrid.columns);
                    allGrid[v]['title'] = JSON.stringify(vgrid.titles);
                    allGrid[v]['group'] = JSON.stringify(vgrid.columnGroups);
                    allGrid[v]['rowHeight'] = vgrid.recordHeight;
                    allGrid[v]['docrep'] = JSON.stringify(vgrid.docrep);
                    var vresultset = [];
                    if (vgrid.searchData.length > 0) {
                        $.each(vgrid.last.searchIds, function (j, k) {
                            vresultset.push(vgrid.records[k]);
                        });
                    } else {
                        vresultset = vgrid.records;
                    }
                    $.each(vresultset, function (j, k) {
                        vresultset[j]['IV_images'] = k.IV_name_image;
                        vresultset[j]['IV_videos'] = k.IV_name_video;
                    });
                    allGrid[v]['resultset'] = JSON.stringify(vresultset);
                }

            });
            openAlternate('POST', "mte/mte_main/genXLSDetailedMulti", allGrid, '_blank');
        };

    };

    // funcoes iniciais;
    dsMainRawData.start();

</script>
<div id="rawDataFilter" class="row">
    <form>
        <div class="col-sm-2 col-md-1">
            <div class="form-group">
                <label for="searchField" class="control-label" style="height: 22px;padding-right: 10px !important;margin-bottom: 1px;;"><?php echo($searchbylable)?>:</label>
                <select name="searchField" id="searchField" form="searchField" class="form-control input-sm" style="height: 26px" >
                    <option value="" selected></option>
                    <option value="Completed_Cycle" title="Comp. Cycle">Comp. Cycle</option>
                    <option value="Completed_Application" title="Comp. Application">Comp. Application</option>
                    <option value="Completed_Discharge" title="Comp. Discharge">Comp. Discharge</option>
                    <!--<option value="Completed_Time" title="Comp. Runtime">Comp. Runtime</option>-->
                </select>
            </div>
        </div>
        <div class="col-sm-2 col-md-1">
            <div class="form-group">
                <label for="from" class="control-label" style="height: 22px;padding-right: 10px !important;margin-bottom: 1px;;"><?php echo($fromlabel)?>:</label>
                <input type="text" name="from" id="from" value="" style=";" class="form-control input-sm">
            </div>
        </div>

        <div class="col-sm-2 col-md-1">
            <div class="form-group">
                <label for="to" class="control-label" style="height: 22px;padding-right: 10px !important;margin-bottom: 1px;;"><?php echo($tolabel)?>:</label>
                <input type="text" name="to" id="to" value="" style=";" class="form-control input-sm">
            </div>
        </div>

        <div class="col-sm-2 col-md-1">
            <div class="form-group">
                <label for="workOrder" class="control-label" style="height: 22px;padding-right: 10px !important;margin-bottom: 1px;;"><?php echo($workorderlabel)?>:</label>
                <input type="text" name="workOrder" id="workOrder" value="<?php echo $workOrder; ?>" style=";" class="form-control input-sm" origin="<?php echo $workOrder; ?>" disabled>
            </div>
        </div>


        <div class="col-sm-2 col-md-1">
            <div class="form-group">
                <label for="sample" class="control-label" style="height: 22px;padding-right: 10px !important;margin-bottom: 1px;;"><?php echo($samplelabel)?>:</label>
                <input type="text" name="sample" id="sample" value="<?php echo $sample; ?>" style=";" class="form-control input-sm" origin="<?php echo $sample; ?>">

            </div>
        </div>

        <div class='col-sm-2' style="padding-top: 25px">
            <button title="search" class="btn btn-default btn-sm" onclick="dsMainRawData.loadRawData();return false;"><i class='fa fa-refresh'></i></button>
            <button title="download all tab data" type="button" class="btn btn-default btn-sm" onclick="dsMainRawData.downLoadGrids();return false;"><i class='fa fa-download'></i></button>
        </div>

    </form>


</div>

<div class="row">
    <?php echo($ctabs); ?>
</div>


