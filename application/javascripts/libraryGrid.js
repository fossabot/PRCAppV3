/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function onGridStart(grid) {
    if (grid.singleBarControl) {
        setSingleBarControl(grid);
    } else {
        grid.on('search', function (event) {

            event.onComplete = function (e) {
                grid.refreshSummary();
            }

        });

    }

    grid.newRecids = [];

    var id = $(grid.box).attr('id');
    //$(grid.box).find('.w2gridToolTip').tooltip({trigger : 'hover'});

    if (grid.records.length > 0) {
        sbcGoToRecId(grid, grid.records[0].recid, false);
    }

    // coloco render do PK
    var vcol = grid.getColumn('recid', true);

    if (vcol === undefined) {

        grid.columns[vcol].render = function (record) {
            if (record.recid < -2) {
                return '<div style="text-align: center;">NEW</div>';
            } else {
                return '<div style="text-align: right;">' + record.recid + '</div>';

            }
        }
    }

    if (grid.presetData != undefined) {
        $.each(grid.presetData, function (i, v) {
            if (v.fl_default == 1) {
                dsGridFunctions.setGlobalInformation(grid);
                dsGridFunctions.selectPreset(i);
                return false;

            }
        });


    }

    if (grid.toolbarTitle != '') {
        grid.toolbar.setToolbarTitle(grid.toolbarTitle);
    }



}

function gridMakeRenderCB(record, index, column_index) {

    if (record == undefined) {
        return;
    }

    if (record.summary) {
        return '';
    }

    var vfield = this.columns[column_index].field;

    var vImage = '';
    if (record[vfield] == 1) {
        vImage = 'fa-check-square-o';
    } else {
        vImage = 'fa-square-o';
    }

    var buttons = '<div style="text-align: center; width: 100%"><span class="fa ' + vImage + '" style="font-size: 14px;" aria-hidden="true" ></span></div>';

//            var buttons = '<i class="fa fa-arrow-right" style="margin-left: 4px;" aria-hidden="true"></i>';

    return buttons;

}




function gridMakePLRender(record, index, column_index) {


    if (record == undefined) {
        return;
    }

    if (record.summary) {
        return '';
    }

    var vrowheight = this.recordHeight - 6;
    var vpadding = (vrowheight - 12) / 2;
    var vdesc = undefined;
    if (vpadding < 3) {
        vpadding = 3;
    }


    var vchanges = [];
    if (w2utils.version == '1.4.3') {
        vchanges = record.changes;
    } else {
        record.w2ui = record.w2ui || {};
        vchanges = record.w2ui.changes;

    }

    if (vchanges !== undefined) {
        if (vchanges[this.columns[column_index].field] != undefined) {
            vdesc = vchanges[this.columns[column_index].field];
        }
    }

    if (vdesc == undefined) {
        if (chkUndefined(record[this.columns[column_index].field], '') == '') {
            vdesc = '';
        } else {
            vdesc = record[this.columns[column_index].field];
        }

    }

    return '<div style="padding-top: +' + vpadding + 'px; height: ' + vrowheight + 'px;background-color: rgba(0, 190, 0, 0.1);cursor: pointer;line-height: 100%">' + vdesc + '</div>';


}



function gridMakeColumnDivider(record, index, column_index) {
    //if (record == undefined) {
    //    return '';
    // }

    var vheight = this.recordHeight;
    return '<div class="w2ui-data-disabled-no-lock" style="height:' + vheight + 'px" ></div>';


}


function gridMakeColorRender(record, index, column_index) {
    if (record == undefined) {
        return;
    }
    var vcolor = undefined;

    var vchanges = [];
    if (w2utils.version == '1.4.3') {

        vchanges = record.changes;
    } else {
        record.w2ui = record.w2ui || {};
        vchanges = record.w2ui.changes;

    }


    if (vchanges !== undefined) {
        if (vchanges[this.columns[column_index].field] != undefined) {
            vcolor = vchanges[this.columns[column_index].field];
        }
    }

    if (vcolor == undefined) {
        if (chkUndefined(record[this.columns[column_index].field], '') == '') {
            return '';
        } else {
            vcolor = record[this.columns[column_index].field];
        }

    }


    var vrowheight = this.recordHeight - 2;

    var vdata = '<div style="background-color:#' + vcolor + ';height: ' + vrowheight + 'px;"></div>';

    return vdata;
}

function gridMakeImageSpecColumn(record, index, column_index) {

    if (record == undefined) {
        return;
    }

    var d = this.lastRetrieveTimeStamp;


    //console.log(this);  

    var vrowheight = this.recordHeight - 2;

    var vinfo = 'spec/shoe_specification/getPictureToDataTable/' + record[this.columns[column_index].field] + '/-1/' + d + ''

    if (chkUndefined(vinfo, 'xx') != 'xx') {
        var vdata = '<img src="' + vinfo + '" style="max-width: 100%; max-height: ' + vrowheight + 'px;">';
    } else {
        var vdata = '';
    }
    return vdata;

}

function gridMakeFirstPicture(record, index, column_index) {

    if (record == undefined) {
        return;
    }

    var d = this.lastRetrieveTimeStamp;
    var vx = record[this.columns[column_index].field];
    //console.log();


    //console.log(this);  

    var vrowheight = this.recordHeight - 2;

    var vinfo = 'docrep/general_document_repository/getFirstPictureThumbsSrc/' + this.docrep + '/' + vx + '/-1/' + d + ''

    if (chkUndefined(vinfo, 'xx') != 'xx') {
        var vdata = '<div style="width: 100%; text-align: center;"><img src="' + vinfo + '" style="max-width: 100%; max-height: ' + vrowheight + 'px;"></div>';
    } else {
        var vdata = '';
    }
    return vdata;

}



function gridMakeImageSkuColumn(record, index, column_index) {
    if (record == undefined) {
        return;
    }
    var vrowheight = this.recordHeight - 2;
    var vinfo = 'spec/shoe_sku/getPictureToDataTable/' + record[this.columns[column_index].field] + '/-1/' + d + ''
    var d = this.lastRetrieveTimeStamp;

    if (chkUndefined(vinfo, 'xx') != 'xx') {
        var vdata = '<img src="' + vinfo + '" style="max-width: 100%; max-height: ' + vrowheight + 'px;">';
    } else {
        var vdata = '';
    }
    return vdata;

}

function gridMakeProgressBar(record, index, column_index) {

    if (record == undefined) {
        return;
    }

    var vinfo = chkUndefined(record[this.columns[column_index].field], 0);
    var vdata = '<div class="progress" style="padding: 0px;height: 22px;margin-bottom: 0px;"  >' +
            '<div class="progress-bar progress-bar-primary " role="progressbar" style="width: ' + vinfo + '%;">' + vinfo + '%</div>' +
            '<div class="progress-bar  " role="progressbar" style="width:' + (100 - vinfo) + '%; "></div>' +
            '</div>';

    return vdata;
}

function gridMakeFileToolbar(record, index, column_index) {

    if (record == undefined) {
        return;
    }

    var vx = record[this.columns[column_index].field];
    var vnid = this.name + 'gridfile' + vx + record['recid'];

    //console.log();


    //console.log(this);  

    var vrowheight = this.recordHeight - 2;


    if (!this.bDestroyDocToolbar) {
        this.on("destroy", function (e) {
            $('.upbar_' + this.name).remove();
        });
        this.bDestroyDocToolbar = true;
    }


    if (chkUndefined(vx, 'xx') != 'xx') {
        var vinfo = 'docrep/general_document_repository/getPictureByFileThumb/' + vx;
        var vdata = '<div style="width: 100%; text-align: center;cursor:pointer" id="' + vnid + '" started="N" onmouseover="openFileToolbar(\'' + this.name + '\', \'' + vnid + '\' , ' + vx + ', ' + record['recid'] + ');" ><img  src="' + vinfo + '" style="max-width: 100%; max-height:' + vrowheight + 'px; cursor:pointer"></div>';
    } else {
        var vdata = '';
    }
    return vdata;
}


function openFileToolbar(gridname, vid, code, recid) {

    var $g = $('#' + vid);
    var vstarted = $g.attr('started');
    if (vstarted == 'N') {

        $g.toolbar({
            content: '#user-options',
            position: 'top',
            style: 'primary',
            event: 'click',
            hideOnClick: true,
            adjustment: 30,
            zIndex: 1000,
            classToDestroy: 'upbar_' + gridname
        });



        $g.attr('started', 'Y');


        $g.on('toolbarShown',
                function (event) {



                    $('.uptoolbarshow').parent().off('click').on('click', function () {
                        $g.toolbar('hide');
                        //var vheight = 'calc(100vh - 100px)';
                        var vsite = 'docrep/general_document_repository/getPictureByFile/' + code;
                        var vhtml = '<div style="padding: 10px"> <img src="' + vsite + '" class="img-responsive"style="max-height:calc(100vh - 120px);margin: 0 auto"></div>';
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

                    $('.uptoolbardownload').parent().off('click').on('click', function () {
                        $g.toolbar('hide');
                        var vdx = chkUndefined(w2ui[gridname].getItem(recid, 'cd_document_repository'), -1);

                        if (vdx < 0) {
                            messageBoxError('This Document/Image is not saved yet. Cannot Download');
                            return;
                        }
                        var cont = 'docrep/general_document_repository/downloadImages/' + vdx;
                        document.location = cont;

                    });

                }).on('toolbarHidden', function () {

        });


    }
}

w2obj.grid.prototype.downloadSelectedDocFiles = function () {
    var self = this;
    var vr = self.getSelection();
    var vcodes = '';

    $.each(vr, function (i, v) {
        var vc = chkUndefined(self.getItem(v, 'cd_document_repository'), -1);
        if (vc > 0) {
            if (vcodes == '') {
                vcodes = vcodes + vc;
            } else {
                vcodes = vcodes + 'x' + vc;
            }
        }
    });

    if (vcodes != '') {
        var cont = 'docrep/general_document_repository/downloadImages/' + vcodes;
        document.location = cont;
    } else {
        messageBoxError('This Document/Image is not saved yet. Cannot Download');
        return;

    }

}

w2obj.grid.prototype.resetUpdateColumn = function (recid, column) {
    var rec = this.get(recid);

    if (!rec.changes) {
        return;
    }


    if (!rec.changes[column] == undefined) {
        return;
    }


    delete rec.changes[column];

    this.set(recid, rec);
}

w2obj.toolbar.prototype.setToolbarTitle = function (toolbarTitle) {
    this.toolbarTitle = toolbarTitle;


    var vtitle = [{id: 'tbarTitleBreak', type: 'break'}, {id: 'tbarTitle', type: 'html', html: '<div class="toolbarTitle">' + toolbarTitle + '</div>'}, {id: 'tbarTitleBreakEnd', type: 'break'}];

    this.remove('tbarTitle', 'tbarTitleBreak', 'tbarTitleBreakEnd');
    this.add(vtitle);

}


w2obj.grid.prototype.addSummary = function (cols) {
    var vgrid = this;
    if (vgrid.cgbSummary == undefined) {
        vgrid.cgbSummary = [];
    }

    vgrid.cgbSummary.push(cols);
}




w2obj.grid.prototype.openPL = function (recid, column) {
    var vcol = -1;
    $.each(this.columns, function (i, v) {
        if (v.field == column) {
            vcol = i;
            return false;
        }
    })


    var $cn = $(this.box).find('[recid="' + recid + '"] [col="' + vcol + '"]');
    $cn.click();
    $cn.click();
}

w2obj.toolbar.prototype.getIntroHelp = function () {

    var vHelper = [];

    $.each(this.items, function (i, v) {
        if (v.type == 'break' || v.type == 'spacer' || v.hidden || v.htmlId == undefined) {
            return true;
        }

        var vintro = v.hint;

        if (vintro == undefined) {
            vintro = v.id;
        }

        var velemtent = '#' + v.htmlId;
        if (v.id == "w2ui-search") {
            vintro = 'Search on Grid';
        }
        vHelper.push({element: velemtent, intro: vintro, position: 'bottom'})
    })

    return vHelper;

}

w2obj.grid.prototype.getIntroHelp = function () {

    var vHelper = [];
    var id = $(this.box).find('.w2ui-grid-body').attr('id');

    vHelper.push({element: '#' + id, intro: 'Data Area'})

    return vHelper;

}




w2obj.grid.prototype.refreshSummary = function () {
    var vgrid = this;
    var vresultset = [];

    if (chkUndefined(vgrid.cgbSummary, []) == 0) {
        return;
    }

    if (vgrid.searchData.length > 0) {
        $.each(vgrid.last.searchIds, function (i, v) {
            vresultset.push($.extend({}, vgrid.records[v]));
        });

    } else {
        vresultset = vgrid.records;
    }

    //console.log('summary', vgrid.cgbSummary);

    $.each(vgrid.cgbSummary, function (i, v) {
        var vSummaryName = 'sum' + i;
        var exists = vgrid.get(vSummaryName) != null;
        var vrow = {recid: vSummaryName, summary: true};

        $.each(vresultset, function (r, d) {

            $.each(v, function (k, c) {

                vrow[c] = parseFloat(chkUndefined(vrow[c], 0)) + vgrid.getItem(d.recid, c);
            });
        });
        if (exists) {
            vgrid.set(vSummaryName, vrow);

        } else {
            vgrid.add([vrow]);
        }

    });

}




w2obj.grid.prototype.getBoxToLock = function () {
    if (this.boxToLock == undefined) {
        return $('#content-body');
    } else {
        return this.boxToLock;

    }
}

w2obj.grid.prototype.setBoxToLock = function (area) {
    this.boxToLock = area;
}


w2obj.grid.prototype.getCellFormated = function (ind, col_ind, summary) {
    var col = this.columns[col_ind];
    var record = (summary !== true ? this.records[ind] : this.summary[ind]);
    var data = this.getCellValue(ind, col_ind, summary);
    var edit = col.editable;
    var vrender = col.render;
    // various renderers
    if (vrender != null) {
        if (col.originalRender != undefined) {
            vrender = col.originalRender;
        }

        if (typeof vrender == 'function') {
            data = $.trim(vrender.call(this, record, ind, col_ind));
            if (data.length < 4 || data.substr(0, 4).toLowerCase() != '<div')
                data = '<div>' + data + '</div>';
        }


        if (typeof vrender == 'object')
            data = '' + (vrender[data] || '') + '';
        if (typeof vrender == 'string') {
            var tmp = vrender.toLowerCase().split(':');
            var prefix = '';
            var suffix = '';
            if (['number', 'int', 'float', 'money', 'currency', 'percent'].indexOf(tmp[0]) != -1) {
                if (typeof tmp[1] == 'undefined' || !w2utils.isInt(tmp[1]))
                    tmp[1] = 0;
                if (tmp[1] > 20)
                    tmp[1] = 20;
                if (tmp[1] < 0)
                    tmp[1] = 0;
                if (['money', 'currency'].indexOf(tmp[0]) != -1) {
                    tmp[1] = w2utils.settings.currencyPrecision;
                    prefix = w2utils.settings.currencyPrefix;
                    suffix = w2utils.settings.currencySuffix
                }
                if (tmp[0] == 'percent') {
                    suffix = '%';
                    if (tmp[1] !== '0')
                        tmp[1] = 1;
                }
                if (tmp[0] == 'int') {
                    tmp[1] = 0;
                }
                // format
                data = '' + (data !== '' ? prefix + w2utils.formatNumber(Number(data).toFixed(tmp[1])) + suffix : '') + '';
            }
            if (tmp[0] == 'time') {
                if (typeof tmp[1] == 'undefined' || tmp[1] == '')
                    tmp[1] = w2utils.settings.time_format;
                data = '' + prefix + w2utils.formatTime(data, tmp[1] == 'h12' ? 'hh:mi pm' : 'h24:min') + suffix + '';
            }
            if (tmp[0] == 'date') {
                if (typeof tmp[1] == 'undefined' || tmp[1] == '')
                    tmp[1] = w2utils.settings.date_display;
                data = '' + prefix + w2utils.formatDate(data, tmp[1]) + suffix + '';
            }
            if (tmp[0] == 'age') {
                data = '' + prefix + w2utils.age(data) + suffix + '';
            }
            if (tmp[0] == 'toggle') {
                data = '' + prefix + (data ? 'Yes' : '') + suffix + '';
            }
        }
    } else {
        // if editable checkbox
        var addStyle = '';
        if (edit && ['checkbox', 'check'].indexOf(edit.type) != -1) {
            var changeInd = summary ? -(ind + 1) : ind;
            addStyle = 'text-align: center';
            data = '<input type="checkbox" ' + (data ? 'checked' : '') + ' onclick="' +
                    '    var obj = w2ui[\'' + this.name + '\']; ' +
                    '    obj.editChange.call(obj, this, ' + changeInd + ', ' + col_ind + ', event); ' +
                    '">';
        }
        if (!this.show.recordTitles) {
            var data = '<div style="' + addStyle + '">' + data + '</div>';
        } else {
            // title overwrite
            var title = String(data).replace(/"/g, "''");
            if (typeof col.title != 'undefined') {
                if (typeof col.title == 'function')
                    title = col.title.call(this, record, ind, col_ind);
                if (typeof col.title == 'string')
                    title = col.title;
            }
            var data = '<div title="' + w2utils.stripTags(title) + '" style="' + addStyle + '">' + data + '</div>';
        }
    }
    if (data == null || typeof data == 'undefined')
        data = '';
    return data;
};

$().w2field('addType', 'pickListRender', function (options) {
    $(this.el).on('keypress', function (event) {
        if (event.metaKey || event.ctrlKey || event.altKey
                || (event.charCode != event.keyCode && event.keyCode > 0))
            return;
        if (event.stopPropagation)
            event.stopPropagation();
        else
            event.cancelBubble = true;
        return false;
    });
});

/*  MEUS PROTOTYPES */
w2obj.grid.prototype.addOld = w2obj.grid.prototype.add;
w2obj.grid.prototype.clearOld = w2obj.grid.prototype.clear;
w2obj.grid.prototype.resizeOld = w2obj.grid.prototype.resize;

if (w2utils.version != '1.4.3') {

    w2obj.grid.prototype.resize = function (id) {
        this.resizeOld();
        //this.refresh(id);
    }

}


w2obj.grid.prototype.lock = function (message, spinner) {
    waitMsgON(this.box, spinner, message);
}


w2obj.grid.prototype.checkDemanded = function () {

    var grid = this;
    var vMissingColumn = '';
    var bMissing = false;
    var changes = this.getChanges();


    $.each(grid.columns, function (index, values) {
        bMissing = false;

        if (values.dem !== undefined && values.dem === 'Y') {

            $.each(changes, function (idxChanges, vlrChanges) {
                var vvv = grid.getItem(vlrChanges.recid, values.field);

                if (vvv === undefined || vvv === '') {
                    bMissing = true;
                    return false;
                }

            });

            $.each(grid.newRecids, function (idxNew, recid) {


                var recexists = grid.get(recid);

                if (recexists == undefined) {
                    return true;
                }


                vvv = grid.getItem(recid, values.field);
                if (vvv === undefined || vvv === null || vvv === '') {
                    bMissing = true;
                    return false;
                }
            });



        }

        if (bMissing) {
            vMissingColumn = vMissingColumn + grid.titles[values.field] + '<br>';
        }
    });


    return vMissingColumn;

}

w2obj.grid.prototype.isNewRow = function (recid) {
    var bIsNew = false;
    $.each(this.newRecids, function (index, value) {
        if (value == recid) {
            bIsNew = true;
            return false;
        }

    });

    return bIsNew;




}

w2obj.grid.prototype.unlock = function () {
    waitMsgOFF(this.box);
}

w2obj.grid.prototype.addRenderFunction = function (columnTofind, funct) {
    thisGRD = this;

    $.each(this.columns, function (index, value) {

        if (value.field === columnTofind) {
            thisGRD.columns[index].cgbStyleRender = funct;
            return false;
        }

    });


};


w2obj.grid.prototype.exportTo = function (typeexport, filename, askfilename) {

    var thisGrid = this;
    if (typeexport == 1 && thisGrid.exportXLSDetailed) {
        thisGrid.exportExcelBackEnd({sendResultSet: thisGrid.exportXLSSendResultSet});
        return;
    }



    if (askfilename === undefined) {
        if (filename === undefined) {
            askfilename = true;
        } else {
            askfilename = false;
        }
    }

    if (filename === undefined) {
        filename = $('#chopt').text();
    }

    var vcolumnsLabel = [], vdata = [], vcolumnsName = [];

    $.each(thisGrid.columns, function (index, value) {

        if (!value.hidden && value.internaltype != 15 && value.internaltype != 4) {
            vcolumnsLabel.push({title: value.caption, index: index});
            vcolumnsName.push(value.field);
        }
    });

    var vrecord = thisGrid.getResultsetJson();

    if (vrecord.length == 0) {
        return;
    }

    $.each(vrecord, function (index, value) {

        var vdatatmp = [];

        $.each(vcolumnsName, function (indexc, fieldname) {
            var vinfo = value[fieldname];
            //vinfo = thisGrid.getCellFormated(index, vcolumnsLabel[indexc].index);


            if (vinfo == undefined) {
                vinfo = '';
            }

            vdatatmp.push(vinfo);
        });

        vdata.push(vdatatmp);
    });

    if (vcolumnsLabel.length == 0 || vdata.length == 0) {
        return;
    }

    var vDataTableDyn = $('#main_gen_export_datatables').DataTable({data: vdata, columns: vcolumnsLabel, buttons: [{extend: 'excelHtml5', filename: filename}, {extend: 'pdfHtml5', filename: filename, orientation: 'landscape', pagesize: 'A4'}], dom: 'Bfrtip'});

    if (typeexport == 1) {
        $('#main_gen_export_datatables_div').find('.buttons-excel').click();
    } else {
        $('#main_gen_export_datatables_div').find('.buttons-pdf').click();
    }
    vDataTableDyn.destroy();
    $('#main_gen_export_datatables').empty();


}

w2obj.grid.prototype.getItem = function (recid, field) {
    var aa = gridGetItem(this.name, recid);

    return aa[field];
}

w2obj.grid.prototype.getResultsetJson = function () {
    var rec = this.records;
    var retInfo = [];
    var nameGrid = this.name;

    $.each(rec, function (index, value) {
        var info = $.extend({}, gridGetItem(nameGrid, value.recid));
        info['changes'] = undefined;
        info['changed'] = undefined;
        info['style'] = undefined;


        retInfo.push(info);

    });


    return retInfo;

}

w2obj.grid.prototype.ScrollToRow = function (recid, forceupdate) {
    sbcGoToRecId(this, recid, forceupdate);
}

w2obj.grid.prototype.exportExcelBackEnd = function (options) {
    var grid = this;
    if (grid.lastFilterMounted == undefined) {
        return;
    }

    var defoptions = {controller: grid.crudController,
        func: 'genXLSDetailed',
        grid: grid,
        sendResultSet: false
    };

    $.extend(defoptions, options);
    genXLSBkend(defoptions);

};

function genXLSBkend(defoptions) {

    var cName = defoptions.controller;
    var func = defoptions.func;

    var toSend = {};

    toSend['filter'] = defoptions.grid.lastFilterMounted;
    toSend['col'] = JSON.stringify($.extend({}, defoptions.grid.columns));
    toSend['title'] = JSON.stringify($.extend({}, defoptions.grid.titles));
    toSend['group'] = JSON.stringify($.extend({}, defoptions.grid.columnGroups));
    toSend['rowHeight'] = defoptions.grid.recordHeight;
    toSend['docrep'] = defoptions.grid.docrep;

    var vresultset = [];
    if (defoptions.sendResultSet) {

        if (defoptions.grid.searchData.length > 0) {
            $.each(defoptions.grid.last.searchIds, function (i, v) {
                vresultset.push($.extend({}, defoptions.grid.records[v]));
            })
        } else {
            vresultset = defoptions.grid.records;
        }

        toSend['resultset'] = JSON.stringify(vresultset);
    }


//    console.log(defoptions.grid.columns,defoptions.grid.columnGroups );

//    console.log(toSend);

    openAlternate('POST', cName + "/" + func, toSend, '_blank');

}


w2obj.grid.prototype.getToolbar = function (toolbarName, removeToolbar) {
    if (removeToolbar == undefined) {
        removeToolbar = false;
    }

    // selecao de colunas desabilitada!!
    if (this.toolbar.items[0].id === 'w2ui-column-on-off') {
        this.toolbar.hide('w2ui-column-on-off');
        if (this.toolbar.items[1].id === "break0") {
            this.toolbar.hide('break0');
        }
    }

    var vtoolbar = $.extend({}, this.toolbar);
    vtoolbar['name'] = toolbarName;

    if (removeToolbar) {
        this.show.toolbar = false;
        this.show.toolbarColumns = false;
        if (this.header === undefined || this.header === '') {
            this.show.header = false;
        }
    }

    return vtoolbar;
}

w2obj.grid.prototype.canSort = function (can, specificColumn) {
    var grid = this;
    $.each(this.columns, function (index, value) {
        if (specificColumn == undefined) {
            grid.columns[index].sortable = can;
        } else {
            if (grid.columns[index].field == specificColumn) {
                grid.columns[index].sortable = can;
            }
        }
    });

    return can;
}


w2obj.grid.prototype.getSelectedRow = function () {
    var pk = this.getPk();
    if (pk == -1) {
        return undefined;
    }

    return gridGetItem(this.name, pk);
}

w2obj.grid.prototype.setRedraw = function (enabled) {
    if (!this.redraw && enabled) {
        this.refresh();
    }

    this.redraw = enabled;

}

// controles de row related data!
w2obj.grid.prototype.rowRelatedDataSet = function (id, data, recid) {

    if (recid === undefined) {
        var row = this.getSelectedRow();
        if (row === undefined) {
            return false;
        }
        recid = row.recid;
    }

    if (this.rowRelatedDataControl === undefined) {
        this.rowRelatedDataControl = false;
    }

    if (this.rowRelatedData[recid] === undefined) {
        this.rowRelatedData[recid] = [];
    }

    this.rowRelatedData[recid][id] = data;

}

w2obj.grid.prototype.rowRelatedDataGet = function (id, recid) {
    if (recid === undefined) {
        var row = this.getSelectedRow();
        if (row === undefined) {
            return false;
        }
        recid = row.recid;
    }

    if (this.rowRelatedData[recid] === undefined) {
        return undefined;
    }

    return this.rowRelatedData[recid][id];
}

w2obj.grid.prototype.rowRelatedDataDelete = function (id, recid) {
    if (recid === undefined) {
        return false;
    }

    this.rowRelatedData[recid][id] = undefined;
}

w2obj.grid.prototype.rowRelatedDataDeleteById = function (id) {
    var relatedData = this.rowRelatedData;
    $.each(relatedData, function (index, value) {

        if (value == undefined) {
            return;
        }

        relatedData[index][id] = [];
    })

    this.rowRelatedData = relatedData;

}


w2obj.grid.prototype.rowRelatedDataDeleteRow = function (recid) {
    if (recid === undefined) {
        return false;
    }

    this.rowRelatedData[recid] = [];
}


w2obj.grid.prototype.rowRelatedDataClear = function () {
    this.rowRelatedData = [];
}



w2obj.grid.prototype.rowRelatedSetDataControl = function (enabled) {
    this.rowRelatedDataControl = enabled;
}

w2obj.grid.prototype.saveOriginalColumns = function () {
    this.originalColumns = this.columns.slice();
};

w2obj.grid.prototype.restoreOriginalColumns = function () {
    this.columns = this.originalColumns.slice();
    this.refresh();
};



w2obj.grid.prototype.readOnly = function (selcol, except) {
    if (!except) {
        except = true;
    }
    ;
    if (!selcol) {
        selcol = [];
    }
    var that = this;
    $.each(this.columns, function (i, v) {

        if (except && selcol.indexOf(v.field) != -1) {
            return true;
        }

        if (!except && selcol.indexOf(v.field) == -1) {
            return true;
        }


        if (v.internaltype == 10 || v.internaltype == 13) {
            that.columns[i].internaltype = 9;
            that.columns[i].render = undefined;
        }
        ;

        that.columns[i].editable = false;
        that.columns[i].dem = undefined;

    });

    this.refresh();
};

w2obj.grid.prototype.rowRelatedDataMakeChangesJson = function (id) {
    var changes = [];
    var alreadyex = [];
    var grid = this;

    $.each(this.getChanges(), function (index, value) {
        related = grid.rowRelatedDataGet(id, value.recid);
        var mm = [];
        mm[0] = value;
        changes.push({recid: value.recid, main: mm, related: related});
        alreadyex[value.recid] = 'Y';
    });


    $.each(this.rowRelatedData, function (index, value) {
        if (alreadyex[index] === undefined) {
            if (value !== undefined) {
                if (value[id] !== undefined) {
                    changes.push({recid: index, main: value, related: value[id]});
                }
            }
        }
    });


    return changes;

}



// fim do controle de relatedData!


w2obj.grid.prototype.getPk = function () {
    var aa = this.getSelection();

    if (aa.length == 0) {
        return -1;
    }
    return aa[0];
}


w2obj.grid.prototype.rowcount = function () {
    return this.records.length;
}

w2obj.grid.prototype.onItemChanged = function (funct) {
    return this.onItemChanged = funct;
}

w2obj.grid.prototype.setItemMultiple = function (data) {
    var vgrid = this;
    $.each(data, function (index, value) {
        $.each(value, function (field, info) {
            vgrid.setItem(value.recid, field, info);
        });
    });
}

w2obj.grid.prototype.setItem = function (recid, field, value) {
    var rec = this.get(recid);
    //console.log('antes', rec);

    if (rec == null) {
        console.error('Line not Found ', recid, field, this);
        return;
    }


    if (w2utils.version == '1.4.3') {
        rec.changed = true;
        rec.changes = rec.changes || {};

        rec[field] = value;
        rec.changes[field] = value;


    } else {

        if (rec.w2ui == undefined) {
            rec.w2ui = {};
            rec.w2ui.changes = {};
        }

        rec.w2ui.changed = true;

        rec.w2ui.changes = rec.w2ui.changes || {};

        rec[field] = value;
        rec.w2ui.changes[field] = value;


    }



    this.set(recid, rec);

}


w2obj.grid.prototype.setItemAsChanged = function (recid, field) {
    thisgrid = this;

    if (field === undefined) {
        field = [];
        $.each(this.columns, function (index, value) {
            field.push(value.field);

            if (value.plCodeField != undefined) {
                field.push(value.plCodeField);

            }

        });

    } else {
        if (!$.isArray(field)) {
            field = [field];
        }
    }

    if (recid === undefined) {
        recid = [];
        $.each(this.records, function (index, value) {
            recid.push(value.recid);
        });

    } else {
        if (!$.isArray(recid)) {
            recid = [recid];
        }
    }

    // comeco setar como changed!!

    $.each(recid, function (indexr, valuerecid) {

        $.each(field, function (indexf, valuefield) {

            var vlr = thisgrid.getItem(valuerecid, valuefield);
            var vx = {};
            thisgrid.setItem(valuerecid, valuefield, vlr);


        });

    });

//    this.refresh();
}


w2obj.grid.prototype.setItemNoChanges = function (recid, field, value) {
    var rec = this.get(recid);
    rec[field] = value;

    this.set(recid, rec);
//    this.refresh();
}



w2obj.grid.prototype.add = function (record, first) {
    // evento para uso do sistema!
    var eventDataSystem = this.trigger({phase: 'before', type: 'addSystem', target: this.name, onComplete: null});
    if (eventDataSystem.isCancelled === true)
        return;

    var rec = [];

    if (!$.isArray(record)) {
        rec[0] = record;
    } else {
        rec = record;
    }

    var eventData = this.trigger({phase: 'before', type: 'add', target: this.name, onComplete: null, resultset: rec});
    if (eventData.isCancelled === true)
        return;

    this.addOld(rec, first);

    this.trigger($.extend(eventDataSystem, {phase: 'after'}));
    this.trigger($.extend(eventData, {phase: 'after'}));

    this.refreshSummary();

}

w2obj.grid.prototype.clear = function (noRefresh) {
    // evento para uso do sistema!

    var eventData = this.trigger({phase: 'before', type: 'clear', target: this.name, onComplete: null});
    if (eventData.isCancelled === true)
        return;

    this.clearOld(noRefresh);
    this.newRecids = [];

    this.trigger($.extend(eventData, {phase: 'after'}));

}

w2obj.grid.prototype.retrieve = function (options) {


    var defoptions = {level: this.defaultLevel,
        controller: this.crudController,
        ask: true,
        hideFilter: true,
        gridPrototype: this,
        doFilter: true,
        retrFunc: 'retrieveGridJson',
        filterNames: [],
        sqlWhere: '',
        funcstyle: undefined,
        relatedToolBar: undefined,
        useFilter: undefined
    };
    $.extend(defoptions, options);

    if (defoptions.ask && this.getChanges().length > 0) {
        messageBoxYesNo(javaMessages.confirm_retrieve, function () {
            doGridRetrieveProt(defoptions);
        });
    } else {
        doGridRetrieveProt(defoptions);
    }

}



w2obj.grid.prototype.update = function (options) {



    var defoptions = {level: 1,
        controller: this.crudController,
        retrieveAfter: true,
        coldRetrieve: true,
        hideFilter: true,
        gridPrototype: this,
        rowRelatedUpdId: 'NONE',
        updFunc: 'updateDataJson',
        mustSend: false,
        additionalData: []};
    $.extend(defoptions, options);

    doGridUpdateProt(defoptions);
}



w2obj.grid.prototype.insertRow = function (options) {

    var defoptions = {level: 1,
        controller: this.crudController,
        gridPrototype: this,
        insFunc: 'retInsJson',
        filterNames: [],
        insTop: false,
        funcAfter: function (event) {
        }
    };

    $.extend(defoptions, options);

    doGridInsertRowProt(defoptions);
}

w2obj.grid.prototype.getNextNegCode = function () {
    this.insNeg = this.insNeg - 1;
    return this.insNeg;

}

w2obj.grid.prototype.setNegativeCode = function () {
    this.insNeg = -10;

}


w2obj.grid.prototype.deleteRow = function (options) {
    var defoptions = {level: 1,
        controller: this.crudController,
        gridPrototype: this,
        delFunc: 'deleteDataJson',
        ask: true,
        funcAfter: function () {},
        recid: []};
    $.extend(defoptions, options);


    var gSel = this.getSelection();
    if (gSel.length == 0) {
        return;
    }

    if (defoptions.ask && defoptions.recid.length == 0) {

        messageBoxYesNo(javaMessages.conf_delete, function () {
            doGridDeleteRowProt(defoptions);
        });
    } else {
        doGridDeleteRowProt(defoptions);
        ;
    }
}


w2obj.grid.prototype.freezeRows = function (bool) {
    this.freezerow = bool;

    var sel = this.getSelection();
    if (sel.length > 0) {
        this.freezerowRecId = sel[0];
    } else {
        this.freezerowRecId = -1;
    }



}

function doGridDeleteRowProt(options) {

    grid = options.gridPrototype;
    cName = options.controller;
    level = options.level;
    func = options.delFunc;

    var gSel = [];

    if (options.recid.length > 0) {
        gSel = options.recid;
    } else {
        gSel = grid.getSelection();
        if (gSel.length == 0) {
            return;
        }
    }

    var eventData = grid.trigger({phase: 'before', type: 'deleterow', target: grid.name, onComplete: null, recid: gSel});
    if (eventData.isCancelled === true)
        return;

    $.myCgbAjax({url: cName + "/" + func,
        box: grid.getBoxToLock(),
        message: javaMessages.deleting,
        data: {"del": JSON.stringify(gSel)},
        success: function (data) {

            if (data.status == "OK") {
                gSelId = [];

                // apago um a um pq nao funciona tudo junto. sei lah
                for (index = 0; index < gSel.length; ++index) {
                    gSelId[index] = grid.get(gSel[index], true);
                    grid.remove(gSel[index]);

                    if (this.rowRelatedDataControl) {
                        grid.rowRelatedDataDeleteRow(gSel[index]);
                    }


                }

                //w2ui[grname].refresh();
                toastSuccess(javaMessages.del_done);
                grid.trigger($.extend(eventData, {phase: 'after', ids: gSelId}));
                options.funcAfter(gSel);
                grid.trigger({action: 'delete', phase: 'before', type: 'gridChanged', target: grid.name, onComplete: null, recid: gSel});
                grid.refreshSummary();

                if (grid.singleBarControl) {

                    var l = gSelId[0] - 1;
                    var ret = -1;
                    if (l == -1) {
                        if (grid.records.length > 0) {
                            ret = grid.records[0].recid;
                        }
                    } else {
                        ret = grid.records[l].recid;
                    }

                    sbcGoToRecId(grid, ret, true);
                }

            } else {
                toastErrorBig(javaMessages.error_del + data.status);
            }

        }
    });

}



function doGridUpdateProt(options) {


    var grid = options.gridPrototype;
    var cName = options.controller;
    var level = options.level;
    var checkHideFilter = options.hideFilter;
    var func = options.updFunc;
    var additionalData = options.additionalData;
    var mustSend = options.mustSend;

    // pois mando o mesmo options para o retrieve;
    options.ask = false;
    var changes = [];
    if (options.rowRelatedUpdId == 'NONE') {
        changes = grid.getChanges();
    } else {
        changes = grid.rowRelatedDataMakeChangesJson(options.rowRelatedUpdId);
    }


    var vMissingColumn = grid.checkDemanded();

    if (vMissingColumn !== '') {
        messageBoxError(javaMessages.msgMissingInformation + '<br>' + vMissingColumn);
        return;
    }

    if (changes.length == 0 && additionalData.length == 0 && !mustSend) {
        return;
    }


    var eventData = grid.trigger({phase: 'before', type: 'update', target: grid.name, onComplete: null});
    if (eventData.isCancelled === true)
        return;



    var toSend = {"upd": JSON.stringify(changes), "additionalData": JSON.stringify(additionalData)};
    if (grid.parseResult != undefined) {
        toSend['jsonMapping'] = JSON.stringify(grid.parseResult);
    }

    if (options.coldRetrieve && options.retrieveAfter) {
        toSend['retResultSet'] = 'Y';
    } else {
        toSend['retResultSet'] = 'N';
    }

    $.myCgbAjax({url: cName + "/" + func,
        box: grid.getBoxToLock(),
        message: javaMessages.updating,
        data: toSend,
        success: function (data) {
            if (data.status == "OK") {
                grid.newRecids = [];
                if (options.rowRelatedUpdId != 'NONE') {
                    grid.rowRelatedDataDeleteById(options.rowRelatedUpdId);
                }

                grid.trigger($.extend(eventData, {phase: 'after', 'data': data}));

                if (options.retrieveAfter) {

                    if (options.coldRetrieve && data.rs !== undefined) {
                        options.retrResultSet = data.rs;
                        if (data.negRS != undefined) {
                            options.negRS = data.negRS;
                        }
                    }

                    grid.retrieve(options);
                } else {
                    grid.mergeChanges();
                }

                grid.refreshSummary();

                toastSuccess(javaMessages.update_done);

            } else {
                toastErrorBig(javaMessages.error_upd + data.status);
            }
        }

    });
}




function doGridInsertRowProt(options) {

    var grid = options.gridPrototype;
    var cName = options.controller;
    var level = options.level;
    var checkHideFilter = options.hideFilter;
    var func = options.insFunc;

    var eventData = grid.trigger({phase: 'before', type: 'insert', target: grid.name, onComplete: null});
    if (eventData.isCancelled === true)
        return;

    // controle de PKs negativas (que serao inseridas depois)
    if (grid.insNeg !== undefined) {

        var data = [{recid: grid.getNextNegCode()}];
        grid.newRecids.push(data[0].recid);

        grid.add(data, options.insTop);

        grid.refresh();

        if (grid.singleBarControl) {
            sbcGoToRecId(grid, grid.insNeg);
        }

        options.funcAfter(data[0], grid.name);

        grid.trigger($.extend(eventData, {phase: 'after', recid: data.recid, data: data}));
        grid.editField(data[0].recid, 1);



        grid.trigger({phase: 'before', type: 'gridChanged', target: grid.name, onComplete: null, recid: data.recid, data: data, action: 'insert'});



        return;
    }

    // se nao eh o inserido, faz a adicao da informacao por ajax
    $.myCgbAjax({url: cName + "/" + func,
        box: grid.getBoxToLock(),
        message: javaMessages.inserting,
        success: function (data) {
            grid.newRecids.push(data.recid);
            grid.add(data, options.insTop);
            //faco um setitem para garantir que qualquer outro campo que venha no insert seja marcado como changed
            $.each(data, function (index, value) {

                if (index != 'style' && index != 'recid') {
                    grid.setItem(data.recid, index, value);
                }



            });


            grid.refresh();

            if (grid.singleBarControl) {
                sbcGoToRecId(grid, data.recid);
            }

            options.funcAfter(data, grid.name);

            grid.trigger($.extend(eventData, {phase: 'after', recid: data.recid, data: data}));
            grid.editField(data.recid, 1);

            grid.trigger({phase: 'before', type: 'gridChanged', target: grid.name, onComplete: null, recid: data.recid, data: data, action: 'insert'});

        }
    });

}

function makeFilterMessage(vchk) {
    var vMissInfo = '<h4>' + javaMessages.filterErrorCannotFilter + '!!</h4> <br>';

    if (vchk.cannotDemanded) {
        vMissInfo = vMissInfo + '<strong>' + javaMessages.filterErrorDemandedFilterMissing + ':</strong><br>';

        $.each(vchk.demandedMissing, function (i, v) {
            vMissInfo = vMissInfo + v + '<br>';
        })
        vMissInfo = vMissInfo + '<br>';
    }

    if (vchk.cannotGroup) {
        vMissInfo = vMissInfo + '<strong>' + javaMessages.filterErrorGroupFilterMissing + ':</strong><br>';

        $.each(vchk.groupMissing, function (i, v) {
            vMissInfo = vMissInfo + v + '<br>';
        })
    }

    messageBoxError(vMissInfo);

}

function doGridRetrieveProt(options) {
    var grid = options.gridPrototype;
    var cName = options.controller;
    var level = options.level;
    var checkHideFilter = options.hideFilter;
    var func = options.retrFunc;
    var funcstyle = options.funcstyle;
    var relatedToolBar = options.relatedToolBar;

    var eventData = grid.trigger({phase: 'before', type: 'retrieve', target: grid.name, onComplete: null});
    if (eventData.isCancelled === true)
        return;

    if (grid.rowRelatedDataControl) {
        grid.rowRelatedDataClear();
    }

    grid.newRecids = [];

    var d = new Date();

    grid.lastRetrieveTimeStamp = d.getTime();


    // controle de coldRetrieve (se eu recebo o resultset, nao faco o retrieve
    // faco direto o pelo que eu recebi (update retorna)
    if (options.retrResultSet !== undefined) {
        // forco os eventos de retrieve:
        // antes de adicionar.
        var eventData = grid.trigger({phase: 'before', type: 'retrieveOnAdd', target: grid.name, onComplete: null, data: options.retrResultSet});
        if (eventData.isCancelled === true) {
            return;
        }



        if (grid.parseResult != undefined) {
            options.retrResultSet = jsonResulsetMapping(options.retrResultSet, grid.parseResult);
        }


        grid.mergeChanges();

        if (options.negRS != undefined) {
            $.each(options.negRS, function (k, v) {
                grid.set(k, {recid: v});
            });
        }


        $.each(options.retrResultSet, function () {
            grid.set(this['recid'], this);
        });
        // depois de adicionar
        grid.trigger($.extend(eventData, {phase: 'after', data: options.retrResultSet}));

        // rowfocuschanging

        var gorecid = -1;

        if (grid.singleBarControl) {
            var rec = grid.get(grid.singleBarSelectedRecId);
            gorecid = -1;

            if (rec) {
                gorecid = rec.recid;
            } else {
                if (grid.records.length > 0) {
                    gorecid = grid.records[0].recid;
                }
            }


        }

        sbcGoToRecId(grid, gorecid, true);
        grid.refreshSummary();


        return;
    }

    // a partir daqui apenas se nao tem resultset (coldRetrieve);


    var filterSQL;
    if (options.doFilter) {

        if ($.isArray(level)) {
            var vchk = checkFilterMissing(level, options.filterNames);

            if (vchk.cannotDemanded || vchk.cannotGroup) {
                makeFilterMessage(vchk);
                return false;
            }






            filterSQL = [];
            $.each(level, function (i, v) {
                filterSQL[v] = retFilterInformed(v, options.filterNames);
                grid.lastFilterMounted = filterSQL[i];
            })
            filterSQL[0] = 'none';


        } else {
            var vchk = checkFilterMissing(level, options.filterNames);

            if (vchk.cannotDemanded || vchk.cannotGroup) {
                makeFilterMessage(vchk);
                return false;
            }

            filterSQL = retFilterInformed(level, options.filterNames);
            filterSQL = filterSQL + options.sqlWhere;
            grid.lastFilterMounted = filterSQL;
        }

    } else {
        if (options.useFilter != undefined) {
            filterSQL = options.useFilter;
            grid.lastFilterMounted = filterSQL;
        } else {
            filterSQL = '';
        }
    }



    var toSend = {};
    toSend['filter'] = filterSQL;

    if (grid.parseResult != undefined) {
        toSend['jsonMapping'] = JSON.stringify(grid.parseResult);
    }

    //grid.lock(javaMessages.loading, true);


    $.myCgbAjax({url: cName + "/" + func,
        box: grid.getBoxToLock(),
        message: javaMessages.loading,
        data: toSend,
        success: function (datax) {
            grid.clear();
            var data = {};
            // compatibilidade
            if (datax.logged === undefined) {
                data.resultset = datax;
                data.logged = 'Y';
            } else {
                data = datax;
            }

            if (data.logged == 'N') {
                sessionTimeOut();
                return;
            }

            if (grid.parseResult !== undefined) {
                data.resultset = jsonResulsetMapping(data.resultset, grid.parseResult);
            }

            var eventData = grid.trigger({phase: 'before', type: 'retrieveOnAdd', target: grid.name, onComplete: null, data: data});
            if (eventData.isCancelled === false) {

                if (funcstyle !== undefined) {
                    $.each(data.resultset, function (index, datarow) {
                        //console.log();
                        vstyle = funcstyle(datarow);
                        data.resultset[index]['style'] = vstyle;
                    });
                }




                grid.add(data.resultset);
            }


            var gorecid = -1;

            if (grid.singleBarControl) {
                rec = grid.get(grid.singleBarSelectedRecId);
                gorecid = -1;

                if (rec) {
                    gorecid = rec.recid;
                } else {
                    if (grid.records.length > 0) {
                        gorecid = grid.records[0].recid;
                    }
                }

            }


            if (options.relatedToolBar !== undefined) {
                var filt = options.relatedToolBar.get('hidefilter');
            } else {
                var filt = grid.toolbar.get('hidefilter');
            }


            if (filt != null && checkHideFilter) {
                if (filt.checked && $('.showfilter').is(":visible") && grid.records.length != 0) {
                    hideFilter();
                }
            }

            if (grid.rowRelatedDataControl) {
                grid.rowRelatedDataClear();
            }

            sbcGoToRecId(grid, gorecid, true);

            grid.trigger($.extend(eventData, {phase: 'after'}));

            grid.refreshSummary();


        }
    });
}

function jsonResulsetMapping(resultset, mapping) {
    var retResultSet = [];

    for (var row in resultset) {
        var rowToUse = resultset[row];

        var retResultSetRow = {};

        for (var map in mapping) {
            retResultSetRow[ map ] = rowToUse[mapping[map]];
        }

        retResultSet.push(retResultSetRow);

    }
    return retResultSet;


}


// funcao que seta o tamaanho do grid
function setGridHeight(grname) {

    if (grname == undefined) {
        grname = gridName;
    }

    // essa funcao tem que existir dentro de cada pagina, pois pode mudar a area livre
    var hAvail = getAvailHeight();
    $("#myGrid").css("height", hAvail - 20);
    w2ui[grname].resize();

}


function gridResetItem(rec, grid) {
    if (rec.w2ui === undefined) {
        rec.w2ui = {};
    }

    //rec.changed = false;

    if (w2utils.version == '1.4.3') {
        rec.changes = undefined;

    } else {
        rec.w2ui = undefined
    }


    grid.set(rec.recid, rec);
}

function gridSetItemBasic(grid, recid, field, value) {
    var rec = w2ui[grid].get(recid);
    gridSetItem(rec, field, value, w2ui[grid]);
}

function gridGetItem(gridname, recid) {
    var changes = w2ui[gridname].getChanges();
    var record = w2ui[gridname].get(recid);
    var changes_recid = {};
    var ret = {};

    // encontrou asalteracoes para o recid
    for (var o in changes) {
        if (changes[o].recid == recid) {
            changes_recid = changes[o];
        }
    }

    $.extend(ret, record, changes_recid);

    return ret;

}


// funcoes de grid::::: PARA MANTER COMPATIBILIDADE
function checkGridRetrieve(level, grname, cName)
{
    if (grname == undefined) {
        grname = gridName;
    }

    if (cName == undefined) {
        cName = controllerName;
    }

    if (level == undefined) {
        level = "1";
    }

    var options = {level: level, gridPrototype: w2ui[grname], controller: cName};


    w2ui[grname].retrieve(options);

}

function doGridRetrieve(checkHideFilter, level, grname, cName) {
    if (level == undefined) {
        level = "1";
    }

    if (grname == undefined) {
        grname = gridName;
    }

    if (cName == undefined) {
        cName = controllerName;
    }

    var options = {level: level, gridPrototype: w2ui[grname], controller: cName, hideFilter: checkHideFilter, ask: false};


    w2ui[grname].retrieve(options);

}

function doGridUpdate(level, grname, cName) {
    if (level == undefined) {
        level = "1";
    }

    if (grname == undefined) {
        grname = gridName;
    }

    if (cName == undefined) {
        cName = controllerName;
    }
    var options = {level: level, gridPrototype: w2ui[grname], controller: cName};
    w2ui[grname].update(options);

}

function doGridInsertRow(grname, cName) {
    if (grname == undefined) {
        grname = gridName;
    }

    if (cName == undefined) {
        cName = controllerName;
    }

    var options = {gridPrototype: w2ui[grname], controller: cName};
    w2ui[grname].insertRow(options);

}

function checkGridDelete(grname, cName)
{
    if (grname == undefined) {
        grname = gridName;
    }

    if (cName == undefined) {
        cName = controllerName;
    }

    var options = {gridPrototype: w2ui[grname], controller: cName, ask: true};
    w2ui[grname].deleteRow(options);

}

function doGridDelete(grname, cName) {
    if (grname == undefined) {
        grname = gridName;
    }

    if (cName == undefined) {
        cName = controllerName;
    }

    var options = {gridPrototype: w2ui[grname], controller: cName, ask: false};
    w2ui[grname].deleteRow(options);

}



function gridCallPLEvent(gridObj, event, columnname, columns) {
    var eventData = [];

    var vrel = {id: -1, idwhere: -1}

    if (columns.relationId != undefined) {
        vrel = {id: columns.relationId, idwhere: columns.relationWhere}
    }

    eventData = $.extend({}, event, {type: 'pickList', columnname: columnname, columns: columns, relation: vrel});

    eventData = gridObj.trigger(eventData);
    if (eventData.isCancelled === true)
        return;

    if (columns.plModel) {

        if ($(event.columnSelector).find('.w2ui-data-disabled').length > 0) {
            return;
        }

        basicGridPickList(gridObj, event, columns, $.extend(eventData, {phase: 'after'}));
    }

    if (columns.internaltype === 13) {

        var title = columns.caption;
        var record = gridObj.get(event.recid);
        var vtext = record[columns.field];
        var vreadonly = $(event.columnSelector).find('.w2ui-data-disabledPL').length > 0;




        basicTextPLOpen({title: title, text: vtext, readonly: vreadonly,
            plCallBack: function (saved, text) {
                if (saved) {
                    gridObj.setItem(event.recid, columns.field, text);
                    gridObj.trigger($.extend(eventData, {phase: 'after', newDesc: text}));

                    gridObj.trigger($.extend(eventData, {newDesc: text, phase: 'before', type: 'gridChanged', action: 'pickList'}));


                }


            }
        });
    }


}

function basicGridPickList(gridObj, event, columns, eventData, functionafter) {
    var Model = columns.plModel;
    var title = columns.caption;
    var record = gridObj.get(event.recid);
    var id = record[columns.plCodeField];

    if (!id) {
        id = -1;
    }

    if (eventData.relation != undefined) {
        vrel = eventData.relation;
    }

    basicPickListOpen({model: Model,
        title: title,
        sel_id: id,
        target: event.originalEvent.target,
        relation: vrel,

        plCallBack: function (code, desc, data) {

            gridObj.setItem(event.recid, columns.plCodeField, code);
            gridObj.setItem(event.recid, columns.field, desc);

            eventData = $.extend({}, eventData, {newCode: code, newDesc: desc, dataRec: data});

            gridObj.trigger(eventData);
            gridObj.trigger($.extend(eventData, {newCode: code, newDesc: desc, phase: 'before', type: 'gridChanged', action: 'pickList'}));


            if (functionafter !== undefined) {
                functionafter();
            }

        }
    }
    );
}



/* CONTROLES REFERENTES AO SINGLEBARCONTROL */
function setSingleBarControl(gridObj) {
    /* ------ UNSELECT ----------*/

    gridObj.on("unselect", function (target, event) {
        if (this.freezerow) {
            event.preventDefault();
            return;
        }

        if (!this.singleBarCanUnselect) {
            event.preventDefault();
        }
        ;

    });

    /* ------ SELECT ----------*/
    gridObj.on("select", function (target, event) {

        if (this.freezerow) {
            var eventData = gridObj.trigger({phase: 'before', type: 'freezeSelect', target: gridObj.name, onComplete: null, data: event});

            event.preventDefault();
            return;
        }

        event.onComplete = function (event) {
            sbcGoToRecId(this, event.recid);
        }
    });


    /* ------ SEACH ----------*/

    gridObj.on('search', function (event) {


        if (this.freezerow) {
            event.preventDefault();
            return;
        }

        event.onComplete = function (event) {
            if (this.last.searchIds.length == 0) {
                var rec = -1;
            } else {
                var rec = this.records[this.last.searchIds[0]].recid;
            }
            sbcGoToRecId(this, rec);
            this.refreshSummary();

        }
    });

    /* ------ SORT ----------*/

    gridObj.on('sort', function (event) {

        if (this.freezerow) {
            event.preventDefault();
            return;
        }

        var recid_before = this.singleBarSelectedRecId;

        var id = $(w2ui[event.target].box).attr('id');
        //$(w2ui[event.target].box).find('.w2gridToolTip').tooltip("hide");
        //$('#'+id).find('.w2gridToolTip').tooltip("hide");

        //console.log(event);

        event.onComplete = function (event) {
            sbcGoToRecId(this, recid_before, true);

            var eventData = gridObj.trigger({phase: 'before', type: 'afterSort', target: gridObj.name, onComplete: null});



        }
    });

}

function sbcGoToRecId(gridObj, recid, forceUpdate) {

    if (gridObj.freezerow) {
        //var eventData = gridObj.trigger({phase: 'before', type: 'rowFocusChanging', target: gridObj.name, onComplete: null, recid_old: recold, recid_new: recid});

        return;
    }

    if (forceUpdate == undefined) {
        forceUpdate = false;
    }

    var recold = gridObj.singleBarSelectedRecId;
    if (recid == gridObj.singleBarSelectedRecId && !forceUpdate) {
        return false;
    }

    var eventData = gridObj.trigger({phase: 'before', type: 'rowFocusChanging', target: gridObj.name, onComplete: null, recid_old: recold, recid_new: recid});
    if (eventData.isCancelled === true) {
        gridObj.unselect(recid);

        return false;
    }

    gridObj.singleBarSelectedRecId = recid;
    if (recid != -1) {

        gridObj.singleBarCanUnselect = true;
        gridObj.selectNone();
        gridObj.singleBarCanUnselect = false;

        gridObj.select(recid);
        var vind = gridObj.get(recid, true)

        gridObj.scrollIntoView(vind);


    }

    gridObj.trigger($.extend(eventData, {phase: 'after'}));

    return true;

}



function externalOnChange(event) {
    var gridx = w2ui[event.target];

    // condicoes genericas do sistema
    var col = event['column'];
    var colname = gridx.columns[col].field;
    var colType = gridx.columns[col].editable.type;
    var internaltype = gridx.columns[col].internaltype;

    //if ($(event.box).hasClass('w2ui-data-disabled')) 

    var eventData = this.trigger({phase: 'before', type: 'itemChanging', target: this.name, onComplete: null, data: event, colname: colname, coltype: colType, internaltype: internaltype});
    if (eventData.isCancelled === true) {
        event.preventDefault();
        return;
    }


    if (event['value_new'] == '' && event['value_previous'] == undefined) {
        event['value_new'] = undefined;
    }

    // se for tipo data:
    if (colType == 'date') {
        // primeiro teste. SE a data for invalida, poe de volta o valor anterior
        if (event['value_new'] != '' && !w2utils.isDate(event['value_new'])) {
            event['value_new'] = event['value_previous'];
            return;
        }

        // segundo teste: se for o dt_deactivate muda o style do backgroud
        if (colname == 'dt_deactivated') {
            if (event['value_new'] != '') {
                gridx.set(event['recid'], {style: 'color:rgb(255,0,0);'}, true);
            } else {
                gridx.set(event['recid'], {style: ''}, true);
            }
        }
    }

    // se for tipo texto
    if (colType == 'text') {
        if (event['value_new'] != event['value_previous']) {
            if (internaltype == fStringUpper) {
                //event['value_new'] = event['value_new'].toUpperCase();
            }

            if (internaltype == fStringLower) {
                event['value_new'] = event['value_new'].toLowerCase();
            }

        }
    }

    event.onComplete = function (event) {
        if (typeof gridx.onItemChanged == 'function') {
            gridx.onItemChanged(event);
        }

        gridx.trigger($.extend(eventData, {phase: 'after'}));

        gridx.trigger({phase: 'before', type: 'gridChanged', target: this.name, onComplete: null, data: event, colname: colname, coltype: colType, internaltype: internaltype, action: 'itemChanged'});

        this.refreshSummary();

    };

}

/* FIM CONTROLES REFERENTES AO SINGLEBARCONTROL */


// link de grid com os thumbs
jQuery.fn.gridThumbsGen = function (options) {
    var defOptions = {
        urlLoadImage: function () {},
        funcHtml: function (title, resultset) {},
        gridName: '',
        funcDblClick: undefined,
        gridDiv: 'myGrid',
        resizeFunction: function () {},
        menuDiv: 'myGridMenu',
        checkbox: 'NONE',
        showCheckbox: false,
        availableArea: function () {}
    };

    var toolbarVar;
    var mainOptions = {};
    var thisID = '#' + $(this).attr('id');
    var thisIDScroll = thisID + '_scroll';

    var selectedView = '';
    originalMenuFunction = function () {};

    mainOptions = $.extend({}, defOptions, options);

    var showCheckbox = mainOptions.showCheckbox;

    // monto os eventos que lincam com o Grid
    w2ui[mainOptions.gridName].on('add', function (event) {
        addHtml(mainOptions.funcHtml(w2ui[mainOptions.gridName].titles, event.resultset));
    });



    w2ui[mainOptions.gridName].on('rowFocusChanging', function (event) {

        event.onComplete = function (event2) {
            selectThumb(event2.recid_new);
        };

    });

    w2ui[mainOptions.gridName].on('afterSort', function (event) {

        $(thisID).empty();
        addHtml(mainOptions.funcHtml(w2ui[mainOptions.gridName].titles, w2ui[mainOptions.gridName].records));
        var vrecid = w2ui[mainOptions.gridName].getPk();
        if (vrecid !== undefined) {
            selectThumb(vrecid);
        }

    });




    w2ui[mainOptions.gridName].on('clear', function () {
        $(thisID).empty();
    });

    if (mainOptions.checkbox != 'NONE') {
        w2ui[mainOptions.gridName].addColumn(w2ui[mainOptions.gridName].columns[2].id, {"caption": "X", "size": "50px", "field": "fl_checked", "sortable": true, "style": "  ", hidden: !showCheckbox, "internaltype": 4, "editable": {"type": "checkbox", "style": "text-align: left; text-transform: lowercase;"}});


        w2ui[mainOptions.gridName].on('itemChanging', function (event) {


            event.onComplete = function (e) {
                if (e.colname == mainOptions.checkbox) {
                    var vrecid = e.data.recid;
                    $(thisID).find('#chk' + vrecid + '  .' + mainOptions.checkbox).prop('checked', e.data.value_new);
                    w2ui[mainOptions.gridName].mergeChanges();

                }

            }

        });
    }

    w2ui[mainOptions.gridName].on('deleterow', function (event) {

        event.onComplete = function (event2) {

            $.each(event2.recid, function (index, values) {
                var options = {};
                $(thisID).find('#thumbs' + values).hide('explode', options, 300, function () {
                    $(thisID).find('#framethumbs' + values).remove();
                });

            });
        };

    });
    var vid = thisIDScroll.substring(1);

    $(thisID).wrap('<div class="row" id="' + vid + '" style="display: block;overflow-y: auto; overflow-x: none;;"></div>');
    $(thisIDScroll).cgbMakeScrollbar({setHeight: '100%', theme: "dark", autoWrapContent: false});

    // funcoes:
    function selectThumb(recid) {

        $(thisID).find('.thumbnail').removeClass('thumbs-selected');
        $(thisID).find("#thumbs" + recid).addClass('thumbs-selected');

    }

    function selectData(recid) {
        w2ui[mainOptions.gridName].ScrollToRow(recid, true);
        selectThumb(recid);

    }

    function addHtml(html) {
        $(thisID).append(html);


        // ter certeza que todos as classes vao estar no lugar certo
        if (selectedView != 'G') {
            altView(selectedView, true);
        }

        makeListeners();

    }

    function makeListeners() {

        $(thisID).find('.thumbnail').each(function () {

            if ($(this).attr('started') == 'Y') {
                return true;
            }
            var vrecid = $(this).attr('recid');



            $(this).hoverdir();

            $(this).click(function () {
                w2ui[mainOptions.gridName].ScrollToRow(vrecid);
            });

            $(this).dblclick(function () {
                if (mainOptions.funcDblClick !== undefined) {
                    mainOptions.funcDblClick();
                }
            });

            if (mainOptions.checkbox != 'NONE') {
                if (showCheckbox) {
                    var vaddon = 'display: block;';
                } else {
                    var vaddon = 'display: none;';

                }
                var vcheckbox = '<div class="checkbox checkbox-slider--b" id="chk' + vrecid + '" style="' + vaddon + 'padding-top: 0px;position: absolute; top: 10px; z-index: 1000; right: -8px;"><label><input type="checkbox" class="' + mainOptions.checkbox + '" recid="' + vrecid + '"><span></span></label></div>'
                $(this).parent().prepend(vcheckbox);


                $(this).parent().find('.' + mainOptions.checkbox).change(function () {
                    var vrecid = $(this).attr('recid');
                    w2ui[mainOptions.gridName].setItem(vrecid, mainOptions.checkbox, this.checked);
                    w2ui[mainOptions.gridName].mergeChanges();
                });

            }




            $(this).attr('started', 'Y');
        });



        //$(thisID).find('img').unveil();


    }

    function myMenuOptions(bPressed, dData) {
        if (bPressed == 'thumbs') {
            altView('T');
        }

        if (bPressed == 'grid') {
            altView('G');

        }

        if (bPressed == 'thumbsInfo') {
            altView('C');
        }

        if (bPressed == 'selectall') {
            selectAll();
        }
        if (bPressed == 'unselectall') {
            unselectAll();
        }




        originalMenuFunction(bPressed, dData);

    }


    function altView(view, onRetrieve) {

        if (onRetrieve === undefined) {
            onRetrieve = false;
        }

        $(thisID).find('.thumbnail').removeClass('thumbnail-over');
        $(thisID).find('.caption').removeClass('caption-over');

        if (view === 'G') {
            $('#' + mainOptions.gridDiv).show();
            $(thisIDScroll).hide();
            $('#' + mainOptions.menuDiv).removeClass('toolbarStyle');
        }

        if (view === 'T') {
            $(thisIDScroll).show();

            $('#' + mainOptions.gridDiv).hide();
            if (!onRetrieve) {
                $(thisID).find('.caption').slideUp(250, function () {
                    $(thisID).find('.caption').addClass('caption-over');
                });
            } else {
                $(thisID).find('.caption').addClass('caption-over');
            }
            $('#' + mainOptions.menuDiv).addClass('toolbarStyle');
            $(thisID).find('.thumbnail').addClass('thumbnail-over');
            //$(window).trigger('resize.unveil');


        }

        if (view === 'C') {
            $(thisIDScroll).show();
            //$(thisID).find('img').unveil();

            $('#' + mainOptions.gridDiv).hide();
            $('#' + mainOptions.menuDiv).addClass('toolbarStyle');
            $(thisID).find('.caption').slideDown(250);
            //$(window).trigger('resize.unveil');

        }
        selectedView = view;

        mainOptions.resizeFunction();

    }
    function addToolBar(toolbarObj, after, defValue) {

        toolbarObj.insert(after, [{type: 'radio', id: 'thumbs', group: '1', caption: '', icon: 'fa fa-th', checked: (defValue == 'T')}, {type: 'radio', id: 'thumbsInfo', group: '1', caption: '', icon: 'fa fa-th-list', checked: (defValue == 'C')}, {type: 'radio', id: 'grid', group: '1', caption: '', icon: 'fa fa-list-alt', checked: (defValue == 'G')}, {type: 'break', id: 'break01'}]);


        if (mainOptions.checkbox != 'NONE') {

            toolbarObj.insert(after, [{arrow: false, type: 'check', id: 'mselect', hint: '', icon: 'fa fa-toggle-on', checked: showCheckbox}, {type: 'button', id: 'selectall', hint: javaMessages.selectAll, icon: 'fa fa-check-square-o ', hidden: !showCheckbox}, {type: 'button', id: 'unselectall', hint: javaMessages.unselectAll, icon: 'fa fa-square-o', hidden: !showCheckbox}, {type: 'break', id: 'break02'}]);
        }

        originalMenuFunction = toolbarObj.onClick;

        toolbarObj.onClick = function (target, data) {


            if (target == 'mselect') {
                if (data.item.checked) {
                    this.hide('selectall', 'unselectall');
                    hideCheckBox();
                } else {
                    this.show('selectall', 'unselectall');
                    showCheckBox();

                }
            }

            myMenuOptions(target, data);



        };

        this.toolbarVar = toolbarObj;

    }

    function resizeData(height) {
        $(thisIDScroll).height(height);
        $(thisIDScroll).cgbMakeScrollbar('update');
    }

    function selectAll() {
        $(thisID).find('.' + mainOptions.checkbox).prop('checked', true);
        $.each(w2ui[mainOptions.gridName].records, function (i, v) {
            w2ui[mainOptions.gridName].setItem(v.recid, mainOptions.checkbox, true);
        });
        w2ui[mainOptions.gridName].mergeChanges();

    }

    function unselectAll() {
        $(thisID).find('.' + mainOptions.checkbox).prop('checked', false);
        $.each(w2ui[mainOptions.gridName].records, function (i, v) {
            w2ui[mainOptions.gridName].setItem(v.recid, mainOptions.checkbox, false);
        });
        w2ui[mainOptions.gridName].mergeChanges();

    }

    function hideCheckBox() {
        showCheckbox = false;
        w2ui[mainOptions.gridName].hideColumn(mainOptions.checkbox);

        setTimeout(function () {
            $(thisID).find('.checkbox').hide('fade', {}, 500, function () {
                unselectAll();
            });

        }, 1);
    }

    function showCheckBox() {
        showCheckbox = true;
        setTimeout(function () {

            $(thisID).find('.checkbox').show('fade', {}, 500);
        }, 1);

        w2ui[mainOptions.gridName].showColumn(mainOptions.checkbox);
    }
    function getChecked() {
        var vfind = [];

        if (showCheckbox) {

            $.each(w2ui[mainOptions.gridName].records, function (i, v) {
                if (w2ui[mainOptions.gridName].getItem(v.recid, mainOptions.checkbox)) {
                    vfind.push(v.recid);
                }
                ;
            });
        } else {
            vfind = w2ui[mainOptions.gridName].getSelection();
        }


        return vfind;
    }


    // liberados:
    this.selectThumb = selectThumb;
    this.altView = altView;
    this.addToolBar = addToolBar;
    this.resizeData = resizeData;
    this.selectData = selectData;
    this.getChecked = getChecked;

    return this;

}

/* Modernizr 2.6.2 (Custom Build) | MIT & BSD
 * Build: http://modernizr.com/download/#-csstransitions-shiv-cssclasses-testprop-testallprops-domprefixes-load
 */
;
window.Modernizr = function (a, b, c) {
    function x(a) {
        j.cssText = a
    }
    function y(a, b) {
        return x(prefixes.join(a + ";") + (b || ""))
    }
    function z(a, b) {
        return typeof a === b
    }
    function A(a, b) {
        return!!~("" + a).indexOf(b)
    }
    function B(a, b) {
        for (var d in a) {
            var e = a[d];
            if (!A(e, "-") && j[e] !== c)
                return b == "pfx" ? e : !0
        }
        return!1
    }
    function C(a, b, d) {
        for (var e in a) {
            var f = b[a[e]];
            if (f !== c)
                return d === !1 ? a[e] : z(f, "function") ? f.bind(d || b) : f
        }
        return!1
    }
    function D(a, b, c) {
        var d = a.charAt(0).toUpperCase() + a.slice(1), e = (a + " " + n.join(d + " ") + d).split(" ");
        return z(b, "string") || z(b, "undefined") ? B(e, b) : (e = (a + " " + o.join(d + " ") + d).split(" "), C(e, b, c))
    }
    var d = "2.6.2", e = {}, f = !0, g = b.documentElement, h = "modernizr", i = b.createElement(h), j = i.style, k, l = {}.toString, m = "Webkit Moz O ms", n = m.split(" "), o = m.toLowerCase().split(" "), p = {}, q = {}, r = {}, s = [], t = s.slice, u, v = {}.hasOwnProperty, w;
    !z(v, "undefined") && !z(v.call, "undefined") ? w = function (a, b) {
        return v.call(a, b)
    } : w = function (a, b) {
        return b in a && z(a.constructor.prototype[b], "undefined")
    }, Function.prototype.bind || (Function.prototype.bind = function (b) {
        var c = this;
        if (typeof c != "function")
            throw new TypeError;
        var d = t.call(arguments, 1), e = function () {
            if (this instanceof e) {
                var a = function () {};
                a.prototype = c.prototype;
                var f = new a, g = c.apply(f, d.concat(t.call(arguments)));
                return Object(g) === g ? g : f
            }
            return c.apply(b, d.concat(t.call(arguments)))
        };
        return e
    }), p.csstransitions = function () {
        return D("transition")
    };
    for (var E in p)
        w(p, E) && (u = E.toLowerCase(), e[u] = p[E](), s.push((e[u] ? "" : "no-") + u));
    return e.addTest = function (a, b) {
        if (typeof a == "object")
            for (var d in a)
                w(a, d) && e.addTest(d, a[d]);
        else {
            a = a.toLowerCase();
            if (e[a] !== c)
                return e;
            b = typeof b == "function" ? b() : b, typeof f != "undefined" && f && (g.className += " " + (b ? "" : "no-") + a), e[a] = b
        }
        return e
    }, x(""), i = k = null, function (a, b) {
        function k(a, b) {
            var c = a.createElement("p"), d = a.getElementsByTagName("head")[0] || a.documentElement;
            return c.innerHTML = "x<style>" + b + "</style>", d.insertBefore(c.lastChild, d.firstChild)
        }
        function l() {
            var a = r.elements;
            return typeof a == "string" ? a.split(" ") : a
        }
        function m(a) {
            var b = i[a[g]];
            return b || (b = {}, h++, a[g] = h, i[h] = b), b
        }
        function n(a, c, f) {
            c || (c = b);
            if (j)
                return c.createElement(a);
            f || (f = m(c));
            var g;
            return f.cache[a] ? g = f.cache[a].cloneNode() : e.test(a) ? g = (f.cache[a] = f.createElem(a)).cloneNode() : g = f.createElem(a), g.canHaveChildren && !d.test(a) ? f.frag.appendChild(g) : g
        }
        function o(a, c) {
            a || (a = b);
            if (j)
                return a.createDocumentFragment();
            c = c || m(a);
            var d = c.frag.cloneNode(), e = 0, f = l(), g = f.length;
            for (; e < g; e++)
                d.createElement(f[e]);
            return d
        }
        function p(a, b) {
            b.cache || (b.cache = {}, b.createElem = a.createElement, b.createFrag = a.createDocumentFragment, b.frag = b.createFrag()), a.createElement = function (c) {
                return r.shivMethods ? n(c, a, b) : b.createElem(c)
            }, a.createDocumentFragment = Function("h,f", "return function(){var n=f.cloneNode(),c=n.createElement;h.shivMethods&&(" + l().join().replace(/\w+/g, function (a) {
                return b.createElem(a), b.frag.createElement(a), 'c("' + a + '")'
            }) + ");return n}")(r, b.frag)
        }
        function q(a) {
            a || (a = b);
            var c = m(a);
            return r.shivCSS && !f && !c.hasCSS && (c.hasCSS = !!k(a, "article,aside,figcaption,figure,footer,header,hgroup,nav,section{display:block}mark{background:#FF0;color:#000}")), j || p(a, c), a
        }
        var c = a.html5 || {}, d = /^<|^(?:button|map|select|textarea|object|iframe|option|optgroup)$/i, e = /^(?:a|b|code|div|fieldset|h1|h2|h3|h4|h5|h6|i|label|li|ol|p|q|span|strong|style|table|tbody|td|th|tr|ul)$/i, f, g = "_html5shiv", h = 0, i = {}, j;
        (function () {
            try {
                var a = b.createElement("a");
                a.innerHTML = "<xyz></xyz>", f = "hidden"in a, j = a.childNodes.length == 1 || function () {
                    b.createElement("a");
                    var a = b.createDocumentFragment();
                    return typeof a.cloneNode == "undefined" || typeof a.createDocumentFragment == "undefined" || typeof a.createElement == "undefined"
                }()
            } catch (c) {
                f = !0, j = !0
            }
        })();
        var r = {elements: c.elements || "abbr article aside audio bdi canvas data datalist details figcaption figure footer header hgroup mark meter nav output progress section summary time video", shivCSS: c.shivCSS !== !1, supportsUnknownElements: j, shivMethods: c.shivMethods !== !1, type: "default", shivDocument: q, createElement: n, createDocumentFragment: o};
        a.html5 = r, q(b)
    }(this, b), e._version = d, e._domPrefixes = o, e._cssomPrefixes = n, e.testProp = function (a) {
        return B([a])
    }, e.testAllProps = D, g.className = g.className.replace(/(^|\s)no-js(\s|$)/, "$1$2") + (f ? " js " + s.join(" ") : ""), e
}(this, this.document), function (a, b, c) {
    function d(a) {
        return"[object Function]" == o.call(a)
    }
    function e(a) {
        return"string" == typeof a
    }
    function f() {}
    function g(a) {
        return!a || "loaded" == a || "complete" == a || "uninitialized" == a
    }
    function h() {
        var a = p.shift();
        q = 1, a ? a.t ? m(function () {
            ("c" == a.t ? B.injectCss : B.injectJs)(a.s, 0, a.a, a.x, a.e, 1)
        }, 0) : (a(), h()) : q = 0
    }
    function i(a, c, d, e, f, i, j) {
        function k(b) {
            if (!o && g(l.readyState) && (u.r = o = 1, !q && h(), l.onload = l.onreadystatechange = null, b)) {
                "img" != a && m(function () {
                    t.removeChild(l)
                }, 50);
                for (var d in y[c])
                    y[c].hasOwnProperty(d) && y[c][d].onload()
            }
        }
        var j = j || B.errorTimeout, l = b.createElement(a), o = 0, r = 0, u = {t: d, s: c, e: f, a: i, x: j};
        1 === y[c] && (r = 1, y[c] = []), "object" == a ? l.data = c : (l.src = c, l.type = a), l.width = l.height = "0", l.onerror = l.onload = l.onreadystatechange = function () {
            k.call(this, r)
        }, p.splice(e, 0, u), "img" != a && (r || 2 === y[c] ? (t.insertBefore(l, s ? null : n), m(k, j)) : y[c].push(l))
    }
    function j(a, b, c, d, f) {
        return q = 0, b = b || "j", e(a) ? i("c" == b ? v : u, a, b, this.i++, c, d, f) : (p.splice(this.i++, 0, a), 1 == p.length && h()), this
    }
    function k() {
        var a = B;
        return a.loader = {load: j, i: 0}, a
    }
    var l = b.documentElement, m = a.setTimeout, n = b.getElementsByTagName("script")[0], o = {}.toString, p = [], q = 0, r = "MozAppearance"in l.style, s = r && !!b.createRange().compareNode, t = s ? l : n.parentNode, l = a.opera && "[object Opera]" == o.call(a.opera), l = !!b.attachEvent && !l, u = r ? "object" : l ? "script" : "img", v = l ? "script" : u, w = Array.isArray || function (a) {
        return"[object Array]" == o.call(a)
    }, x = [], y = {}, z = {timeout: function (a, b) {
            return b.length && (a.timeout = b[0]), a
        }}, A, B;
    B = function (a) {
        function b(a) {
            var a = a.split("!"), b = x.length, c = a.pop(), d = a.length, c = {url: c, origUrl: c, prefixes: a}, e, f, g;
            for (f = 0; f < d; f++)
                g = a[f].split("="), (e = z[g.shift()]) && (c = e(c, g));
            for (f = 0; f < b; f++)
                c = x[f](c);
            return c
        }
        function g(a, e, f, g, h) {
            var i = b(a), j = i.autoCallback;
            i.url.split(".").pop().split("?").shift(), i.bypass || (e && (e = d(e) ? e : e[a] || e[g] || e[a.split("/").pop().split("?")[0]]), i.instead ? i.instead(a, e, f, g, h) : (y[i.url] ? i.noexec = !0 : y[i.url] = 1, f.load(i.url, i.forceCSS || !i.forceJS && "css" == i.url.split(".").pop().split("?").shift() ? "c" : c, i.noexec, i.attrs, i.timeout), (d(e) || d(j)) && f.load(function () {
                k(), e && e(i.origUrl, h, g), j && j(i.origUrl, h, g), y[i.url] = 2
            })))
        }
        function h(a, b) {
            function c(a, c) {
                if (a) {
                    if (e(a))
                        c || (j = function () {
                            var a = [].slice.call(arguments);
                            k.apply(this, a), l()
                        }), g(a, j, b, 0, h);
                    else if (Object(a) === a)
                        for (n in m = function(){var b = 0, c; for (c in a)a.hasOwnProperty(c) && b++; return b}(), a)
                            a.hasOwnProperty(n) && (!c && !--m && (d(j) ? j = function () {
                                var a = [].slice.call(arguments);
                                k.apply(this, a), l()
                            } : j[n] = function (a) {
                                return function () {
                                    var b = [].slice.call(arguments);
                                    a && a.apply(this, b), l()
                                }
                            }(k[n])), g(a[n], j, b, n, h))
                } else
                    !c && l()
            }
            var h = !!a.test, i = a.load || a.both, j = a.callback || f, k = j, l = a.complete || f, m, n;
            c(h ? a.yep : a.nope, !!i), i && c(i)
        }
        var i, j, l = this.yepnope.loader;
        if (e(a))
            g(a, 0, l, 0);
        else if (w(a))
            for (i = 0; i < a.length; i++)
                j = a[i], e(j) ? g(j, 0, l, 0) : w(j) ? B(j) : Object(j) === j && h(j, l);
        else
            Object(a) === a && h(a, l)
    }, B.addPrefix = function (a, b) {
        z[a] = b
    }, B.addFilter = function (a) {
        x.push(a)
    }, B.errorTimeout = 1e4, null == b.readyState && b.addEventListener && (b.readyState = "loading", b.addEventListener("DOMContentLoaded", A = function () {
        b.removeEventListener("DOMContentLoaded", A, 0), b.readyState = "complete"
    }, 0)), a.yepnope = k(), a.yepnope.executeStack = h, a.yepnope.injectJs = function (a, c, d, e, i, j) {
        var k = b.createElement("script"), l, o, e = e || B.errorTimeout;
        k.src = a;
        for (o in d)
            k.setAttribute(o, d[o]);
        c = j ? h : c || f, k.onreadystatechange = k.onload = function () {
            !l && g(k.readyState) && (l = 1, c(), k.onload = k.onreadystatechange = null)
        }, m(function () {
            l || (l = 1, c(1))
        }, e), i ? k.onload() : n.parentNode.insertBefore(k, n)
    }, a.yepnope.injectCss = function (a, c, d, e, g, i) {
        var e = b.createElement("link"), j, c = i ? h : c || f;
        e.href = a, e.rel = "stylesheet", e.type = "text/css";
        for (j in d)
            e.setAttribute(j, d[j]);
        g || (n.parentNode.insertBefore(e, n), m(c, 0))
    }
}(this, document), Modernizr.load = function () {
    yepnope.apply(window, [].slice.call(arguments, 0))
};

;
(function ($, window, undefined) {

    'use strict';

    $.HoverDir = function (options, element) {

        this.$el = $(element);
        this._init(options);

    };

    // the options
    $.HoverDir.defaults = {
        speed: 200,
        easing: 'ease',
        hoverDelay: 0,
        inverse: false
    };

    $.HoverDir.prototype = {

        _init: function (options) {

            // options
            this.options = $.extend(true, {}, $.HoverDir.defaults, options);
            // transition properties
            this.transitionProp = 'all ' + this.options.speed + 'ms ' + this.options.easing;
            // support for CSS transitions
            this.support = Modernizr.csstransitions;
            // load the events
            this._loadEvents();

        },
        _loadEvents: function () {

            var self = this;

            this.$el.on('mouseenter.hoverdir, mouseleave.hoverdir', function (event) {

                var $el = $(this),
                        $hoverElem = $el.find('.caption'),
                        direction = self._getDir($el, {x: event.pageX, y: event.pageY}),
                        styleCSS = self._getStyle(direction);

                if (!$hoverElem.hasClass('caption-over')) {
                    return;
                }

                if (event.type === 'mouseenter') {

                    $hoverElem.hide().css(styleCSS.from);
                    clearTimeout(self.tmhover);

                    self.tmhover = setTimeout(function () {

                        $hoverElem.show(0, function () {

                            var $el = $(this);
                            if (self.support) {
                                $el.css('transition', self.transitionProp);
                            }
                            self._applyAnimation($el, styleCSS.to, self.options.speed);

                        });


                    }, self.options.hoverDelay);

                } else {

                    if (self.support) {
                        $hoverElem.css('transition', self.transitionProp);
                    }
                    clearTimeout(self.tmhover);
                    self._applyAnimation($hoverElem, styleCSS.from, self.options.speed);

                }

            });

        },
        // credits : http://stackoverflow.com/a/3647634
        _getDir: function ($el, coordinates) {

            // the width and height of the current div
            var w = $el.width(),
                    h = $el.height(),
                    // calculate the x and y to get an angle to the center of the div from that x and y.
                    // gets the x value relative to the center of the DIV and "normalize" it
                    x = (coordinates.x - $el.offset().left - (w / 2)) * (w > h ? (h / w) : 1),
                    y = (coordinates.y - $el.offset().top - (h / 2)) * (h > w ? (w / h) : 1),
                    // the angle and the direction from where the mouse came in/went out clockwise (TRBL=0123);
                    // first calculate the angle of the point,
                    // add 180 deg to get rid of the negative values
                    // divide by 90 to get the quadrant
                    // add 3 and do a modulo by 4  to shift the quadrants to a proper clockwise TRBL (top/right/bottom/left) **/
                    direction = Math.round((((Math.atan2(y, x) * (180 / Math.PI)) + 180) / 90) + 3) % 4;

            return direction;

        },
        _getStyle: function (direction) {

            var fromStyle, toStyle,
                    slideFromTop = {left: '0px', top: '-100%'},
                    slideFromBottom = {left: '0px', top: '100%'},
                    slideFromLeft = {left: '-100%', top: '0px'},
                    slideFromRight = {left: '100%', top: '0px'},
                    slideTop = {top: '0px'},
                    slideLeft = {left: '0px'};

            switch (direction) {
                case 0:
                    // from top
                    fromStyle = !this.options.inverse ? slideFromTop : slideFromBottom;
                    toStyle = slideTop;
                    break;
                case 1:
                    // from right
                    fromStyle = !this.options.inverse ? slideFromRight : slideFromLeft;
                    toStyle = slideLeft;
                    break;
                case 2:
                    // from bottom
                    fromStyle = !this.options.inverse ? slideFromBottom : slideFromTop;
                    toStyle = slideTop;
                    break;
                case 3:
                    // from left
                    fromStyle = !this.options.inverse ? slideFromLeft : slideFromRight;
                    toStyle = slideLeft;
                    break;
            }
            ;

            return {from: fromStyle, to: toStyle};

        },
        // apply a transition or fallback to jquery animate based on Modernizr.csstransitions support
        _applyAnimation: function (el, styleCSS, speed) {

            $.fn.applyStyle = this.support ? $.fn.css : $.fn.animate;
            el.stop().applyStyle(styleCSS, $.extend(true, [], {duration: speed + 'ms'}));

        },

    };

    var logError = function (message) {

        if (window.console) {

            window.console.error(message);

        }

    };

    $.fn.hoverdir = function (options) {

        var instance = $.data(this, 'hoverdir');

        if (typeof options === 'string') {

            var args = Array.prototype.slice.call(arguments, 1);

            this.each(function () {

                if (!instance) {

                    logError("cannot call methods on hoverdir prior to initialization; " +
                            "attempted to call method '" + options + "'");
                    return;

                }

                if (!$.isFunction(instance[options]) || options.charAt(0) === "_") {

                    logError("no such method '" + options + "' for hoverdir instance");
                    return;

                }

                instance[ options ].apply(instance, args);

            });

        } else {

            this.each(function () {

                if (instance) {

                    instance._init();

                } else {

                    instance = $.data(this, 'hoverdir', new $.HoverDir(options, this));

                }

            });

        }

        return instance;

    };

})(jQuery, window);




var dsGridFunctions = new function () {
    var thisObjGridFunctions = this;

    thisObjGridFunctions.strSelect = '<div class="checkbox  checkbox-slider--b-flat checkbox-slider-sm checkbox-slider-info" style="margin-top: 0px; margin-bottom: 0px">' +
            '<label for="lchcol#column#" class="">' +
            '<input type="checkbox" name="onoffswitch" class="" id="lchcol#column#" idx="#idx#" col="#column#" #checked# onchange="dsGridFunctions.toggleColumn(this);"><span class="gridColInfoSpan"><strong>#title#</strong></span>' +
            '</label>' +
            '</div>';

    thisObjGridFunctions.modalSelect = '<div style="max-height:#hh#px; overflow-y: auto;" class="row" id="gridCHGCol"><div class="#class# areaClass" style="font-size: 13px;padding-top: 5px;">#data#</div><div id="gridColPresetArea">#preset#</div>';


    this.setGlobalInformation = function (grid) {
        thisObjGridFunctions.selectedGrid = grid;
        thisObjGridFunctions.gridPresetData = thisObjGridFunctions.selectedGrid.presetData;
        thisObjGridFunctions.gridPresetId = thisObjGridFunctions.selectedGrid.presetId;
        thisObjGridFunctions.presetAsRow = false;

    }

    this.showHideColumns = function (grid, target) {
        this.setGlobalInformation(grid);


        var vcolumns = grid.columns;

        var info = '';
        var vpresetHtml = '';
        var vhwindow = $(window).height() * .60;
        var vwwindow = $(window).width() * .80;
        var vColArea = 'col-sm-12';
        var vwidthTotal = 400;

        var vusePreset = (grid.presetId != undefined);//grid.preset;
        var vColDoubleColumn = vcolumns.length > 10;


        if (!vColDoubleColumn) {
            vwidthTotal = 300;
        }

        if (vusePreset) {
            vwidthTotal = vwidthTotal + 150;
        }



        if (vwwindow > vwidthTotal) {
            var vwidth = (vwidthTotal - 50) + 'px';


            var vclass = 'col-sm-12';
            if (vColDoubleColumn) {
                vclass = 'col-sm-6';
            }



            if (vusePreset) {
                vColArea = 'col-sm-7';
                vpresetHtml = thisObjGridFunctions.makePresetArea();

            }


        } else {
            var vwidth = '300px';
            var vclass = 'col-sm-12';


            thisObjGridFunctions.presetAsRow = true;
            vpresetHtml = thisObjGridFunctions.makePresetArea();
        }

        $.each(vcolumns, function (index, value) {
            if (!value.hideable) {
                return true;
            }

            var vtext = grid.titles[value.field];
            var vCapDiv = value.captionDivider;

            if (vtext == '' && vCapDiv == undefined) {
                return true;
            }


            if (vCapDiv != undefined) {
                info = info + '<div class="col-sm-12" style="padding-bottom: 1px;1px; border-top: thin dashed black; background-color: lightgray;margin-bottom: 5px;font-size: 11px;padding-top: 1px;font-weight: bold;text-align: center">' + vCapDiv + '</div>';
                //info = info + '<div class="col-md-12" style="padding-bottom: 1px; border-bottom: thin solid black ;margin-bottom: 5px;font-size: 11px;padding-top: 1px;font-weight: bold;text-align: center">'+vCapDiv+'</div>';
                return true;
            }

            var vinfo_tmp = '';
            vinfo_tmp = '<div class="' + vclass + '" style="padding-right: 5px;padding-left: 5px;">' + thisObjGridFunctions.strSelect;

            vinfo_tmp = vinfo_tmp.replace(/#title#/g, vtext);
            vinfo_tmp = vinfo_tmp.replace(/#column#/g, value.field);
            vinfo_tmp = vinfo_tmp.replace(/#idx#/g, index);

            if (value.hidden) {
                vinfo_tmp = vinfo_tmp.replace('#checked#', '');
            } else {
                vinfo_tmp = vinfo_tmp.replace('#checked#', 'checked="checked"');
            }

            vinfo_tmp = vinfo_tmp + '</div>';

            info = info + vinfo_tmp;


        });

        //var vframe = "<div style='width: 100%;height:100%; background-color: rgba(200, 199, 200, 0.9);position: absolute;top:-10px; left: 0px;display: block;border: #000 thin dotted;z-index:999999999' id='gridCHGColPresets'></div>"


        var xM = thisObjGridFunctions.modalSelect.replace('#data#', info).replace('#hh#', vhwindow).replace('#class#', vColArea).replace('#preset#', vpresetHtml);


        basicPickListOpenPopOver({
            title: javaMessages.gridShowHideColumnLabel,
            target: target,
            html: xM,
            showClose: true,
            position: 'auto',
            width: vwidth,
            zIndex: '2050',
            plVarSuffix: 'gridModalCOL',

            functionOpen: function () {
                var $v = $('#gridCHGCol');

                $v.cgbMakeScrollbar({autoWrapContent: false, alwaysShowScrollbar: 0, theme: 'dark'});

                if (vusePreset) {
                    if (thisObjGridFunctions.presetAsRow) {
                        $v.find('.areaClass').css('border-bottom', 'darkgrey dashed 1px');

                    } else {
                        $v.find('.areaClass').css('border-right', 'darkgrey dashed 1px');
                    }
                }

                $v.find('.gridColInfoSpan').each(function () {
                    var $ele = $(this);
                    if (this.offsetWidth < this.scrollWidth) {
                        $ele.attr('title', $ele.text());
                        $ele.attr('data-toggle', 'tooltip');
                        $ele.tooltip({container: "#popover_my_content_SBSModalVarPopupgridModalCOL"})

                    }
                })


                if (thisObjGridFunctions.selectedGrid.usersMenu == undefined || thisObjGridFunctions.selectedGrid.usersMenu.length == 0) {
                    $('.presetSharedClass').hide();
                }



            },
            plCallBack: function (code, desc, data) {

            }
        })


    }

    this.toggleColumn = function (obj) {
        var vcol = $(obj).attr('col');
        var vchecked = $(obj).is(':checked');

        if (vchecked) {
            thisObjGridFunctions.selectedGrid.showColumn(vcol);
        } else {
            thisObjGridFunctions.selectedGrid.hideColumn(vcol);
        }
    }

    this.makePresetArea = function () {
        var vclassArea = 'col-sm-5';
        var vUpdTp = 'bottom';
        if (thisObjGridFunctions.presetAsRow) {
            vclassArea = 'col-sm-12';
            vUpdTp = 'top';
        }
        var vUpd = javaMessages.update;
        var vDel = javaMessages.deleteMsg;
        var vDef = javaMessages.default;
        var vIns = javaMessages.ins_line;
        var vHide = javaMessages.hide;
        var vShare = javaMessages.share;
        //thisObjGridFunctions.gridPresetId   = thisObjGridFunctions.selectedGrid.presetId;



        var vhtml = "<div class='" + vclassArea + "' style='font-size: 13px;padding-top: 0px;padding-right: 5px; padding-left:5px;'>";

        // titulo: 
        vhtml = vhtml + "<div class='col-sm-12' style='padding-right: 5px; padding-left:5px;'><strong>" + javaMessages.preset + "</strong></div>";
        //fa fa-plus



        vhtml = vhtml + "<div class='col-sm-12' style='padding-right: 5px; padding-left:5px;'>";
        vhtml = vhtml + "<form>";

        vhtml = vhtml + "<div class='input-group' style='margin-bottom: 5px;'>";

        vhtml = vhtml + "<input class='form-control input-sm' id='inputPreset' type='text' style = 'height: 18px; font-size: 12px;'>";
        vhtml = vhtml + "<span class='input-group-addon' style='padding-top: 0px; padding-right: 0px; padding-left: 2px; padding-bottom: 0px; font-size: 13px;border: 0px;'><i class='fa fa-plus' aria-hidden='true' style='padding-right: 5px;cursor:pointer;' onclick='dsGridFunctions.insertNewPreset();'  title='" + vIns + "' data-toggle='tooltip' data-placement='" + vUpdTp + "'></i></span>";

        vhtml = vhtml + "</div>";

        vhtml = vhtml + "</form>";
        vhtml = vhtml + "</div>";

        vhtml = vhtml + "<table class='table table-hover table-striped'><tbody>";


        $.each(thisObjGridFunctions.gridPresetData, function (i, v) {
            var vdefColor = 'lightgray';
            var vdefOther = 1;
            if (v.fl_default == 1) {
                vdefColor = 'blue';
                vdefOther = 0;
            }



            vhtml = vhtml + "<tr ><td style='padding:0px;'>";

            vhtml = vhtml + "<div class='col-md-12' style='padding-right: 5px; padding-left:5px;'>  \n\
                        <i class='fa fa-bolt' aria-hidden='true' style='padding-right: 5px;cursor:pointer;' onclick='dsGridFunctions.selectPreset(" + i + ");'  title='Select' data-toggle='tooltip'></i>\n\
                        <i class='fa fa-check-square-o' aria-hidden='true' style='padding-right: 5px;cursor:pointer; color: " + vdefColor + "' title='" + vDef + "' data-toggle='tooltip' onclick='dsGridFunctions.setAsDefault(" + vdefOther + ", " + v.recid + ");'></i>\n\
                        <i class='fa fa-gear' id='gridToolbarBtn" + v.recid + "' aria-hidden='true' style='padding-right: 5px;cursor:pointer;' onclick='dsGridFunctions.showToolbar(" + v.recid + ");'  title='' data-toggle='tooltip'></i>  \n\
                        <div id='grdToolbar" + v.recid + "' style='display:none;position: absolute; background-color: lightgrey; top: 0px;left:0px;padding-left: 10px;padding-right: 5px;border: grey solid thin'>\n\
                        <i class='fa fa-floppy-o' aria-hidden='true' style='padding-right: 5px;cursor:pointer;' onclick='dsGridFunctions.updatePreset(" + v.recid + ");'  title='" + vUpd + "' data-toggle='tooltip'></i>  \n\
                        <i class='fa fa-trash' aria-hidden='true' style='padding-right: 5px;cursor:pointer;' onclick='dsGridFunctions.deletePreset(" + v.recid + ");'  title='" + vDel + "' data-toggle='tooltip'></i> \n\
                        <i class='fa fa-share-alt presetSharedClass' id='presetshare" + v.recid + "'  aria-hidden='true' style='padding-right: 5px;cursor:pointer;' onclick='dsGridFunctions.sharePreset(" + v.recid + ");'  title='" + vShare + "' data-toggle='tooltip'></i> \n\
                        <i class='fa fa-arrow-circle-left' aria-hidden='true' style='padding-right: 5px;cursor:pointer;' onclick='dsGridFunctions.hideToolbar(" + v.recid + ");'  title='" + vHide + "' data-toggle='tooltip'></i> \n\
</div>\n\
                        " + v.ds_sys_column_filter_preset + "</div>";
            vhtml = vhtml + "</td></tr>";


        });
        vhtml = vhtml + "</tbody></table>";

        vhtml = vhtml + '</div>'

        return vhtml;
    }

    this.sharePreset = function (rec) {


        var $b = $('#presetshare' + rec);

        basicPickListOpenPopOver({
            title: javaMessages.chooseUserToShare,
            target: '#presetshare' + rec,
            html: "<div style='width: 240px; height: 40px; padding-left: 0px;'><div style='float: left'><select id='selectUser' class='input-sm' style='width: 200px;'><option value='-1'>" + javaMessages.selectUser + "</option></select></div><div><i class='fa fa-share-alt' aria-hidden='true' style='padding-right: 10px; padding-left: 5px;padding-top:9px;cursor:pointer;' data-toggle='tooltip' onclick='dsGridFunctions.ShareToUser(" + rec + ")'></i></div style='float:left;'></div>",
            showClose: true,
            position: 'auto',
            width: '260px',
            zIndex: '2070',
            plVarSuffix: 'gridModalCOLShared',
            titleBackgroundColor: '#48C9B0',
            mini: true,
            functionOpen: function () {
                var toAppend = '';
                $.each(thisObjGridFunctions.selectedGrid.usersMenu, function (i, o) {
                    toAppend += '<option value="' + o.recid + '">' + o.text + '</option>';
                });

                $('#selectUser').append(toAppend);
                $('#selectUser').select2({dropdownAutoWidth: true});
                var vpointe = SBSModalVarPopupgridModalCOL.getPopoverElement().css('pointer-events');
                SBSModalVarPopupgridModalCOL.getPopoverElement().css('pointer-events', 'none');
                SBSModalVarPopupgridModalCOLShared.beforeClose = function () {
                    SBSModalVarPopupgridModalCOL.getPopoverElement().css('pointer-events', vpointe);


                    return true;
                }


            },
            plCallBack: function (code, desc, data) {

            }
        })

    }

    this.ShareToUser = function (recid) {
        var vuser = $('#selectUser').val();
        if (vuser == -1) {
            messageBoxAlert(javaMessages.userToShareError);
            return;
        }

        var vjson = {recid: recid, user: vuser};

        $.myCgbAjax({url: 'sys_column_filter_preset/sharePreset',
            message: javaMessages.deleting,
            box: SBSModalVarPopupgridModalCOLShared.getPopoverElement(),
            data: vjson,
            success: function (a) {

                if (a.status != 'OK') {
                    messageBoxError(a.status);
                } else {
                    messageBoxAlert(a.msg);
                }

            },
        });

    }

    this.showToolbar = function (rec) {
        var vidtoolbar = '#grdToolbar' + rec;
        var vgearButton = '#gridToolbarBtn' + rec;
        var vx = $(vgearButton).position();
        //$(vidtoolbar).css('left', (Math.round(vx.left,0) -1) + 'px');
        $(vidtoolbar).css('width', '100%');
        $(vidtoolbar).css('height', '100%');

        $(vidtoolbar).show('slide', {direction: 'left'}, 200);

    }

    this.hideToolbar = function (rec) {
        var vidtoolbar = '#grdToolbar' + rec;
        $(vidtoolbar).hide('slide', {direction: 'left'}, 200);

    }

    this.setAsDefault = function (vdef, recid) {
        var vdata = {fl_default: vdef, recid: recid};
        this.updateInfo(vdata, false);
    }

    this.selectPreset = function (index) {

        var vinfo = JSON.parse(thisObjGridFunctions.gridPresetData[index].jsonb_column_filter_data);
        var vp = vinfo.col;

        thisObjGridFunctions.selectedGrid.hideColumn.apply(thisObjGridFunctions.selectedGrid, vp.h);
        thisObjGridFunctions.selectedGrid.showColumn.apply(thisObjGridFunctions.selectedGrid, vp.s);
        resetAllFilter(thisObjGridFunctions.selectedGrid.defaultLevel);
        setFiltersData(vinfo.filter);

        if (typeof SBSModalVarPopupgridModalCOL != 'undefined') {
            SBSModalVarPopupgridModalCOL.close();
            SBSModalVarPopupgridModalCOL = undefined;
        }
    }

    this.updatePreset = function (recid) {
        var vx = {recid: recid, colInfo: {col: this.getHiddenColumns(), filter: this.getFilters()}};
        messageBoxYesNo(javaMessages.confirmReplace, function () {
            thisObjGridFunctions.updateInfo(vx);
        });
    }

    this.insertNewPreset = function () {
        var vInfo = chkUndefined($('#inputPreset').val(), '');
        if (vInfo == '') {
            messageBoxError(javaMessages.pleaseinfdescr);
            return;
        }



        var vx = {recid: -4, title: vInfo, colInfo: {col: this.getHiddenColumns(), filter: this.getFilters()}};

        this.updateInfo(vx);

    }

    this.deletePreset = function (recid) {
        $.myCgbAjax({url: 'sys_column_filter_preset/deletePreset/' + thisObjGridFunctions.gridPresetId + '/' + recid,
            message: javaMessages.deleting,
            box: '.webui-popover',
            data: {},
            success: function (a) {

                if (a.status == 'OK') {
                    thisObjGridFunctions.refreshPresetArea(a.rs);
                } else {
                    messageBoxError(a.status);
                }

            },
        });
    }

    this.updateInfo = function (data) {

        $.myCgbAjax({url: 'sys_column_filter_preset/updatePreset/' + thisObjGridFunctions.gridPresetId,
            message: javaMessages.updating,
            box: '.webui-popover',
            data: {'upd': data},
            success: function (a) {

                if (a.status == 'OK') {
                    thisObjGridFunctions.refreshPresetArea(a.rs);
                } else {
                    messageBoxError(a.status);
                }
            },
        });

    };

    this.refreshPresetArea = function (rs) {
        thisObjGridFunctions.gridPresetData = rs;
        var vx = thisObjGridFunctions.makePresetArea();
        $('#gridColPresetArea').empty().append(vx);
        w2ui[thisObjGridFunctions.selectedGrid.name].presetData = rs;

    }

    this.getHiddenColumns = function () {
        var vColsHidden = [];
        var vColsShow = [];
        $.each(thisObjGridFunctions.selectedGrid.columns, function (i, v) {
            if (v.hidden) {
                vColsHidden.push(v.field);
            } else {
                vColsShow.push(v.field);
            }
        });

        return {s: vColsShow, h: vColsHidden};
    }


    this.getFilters = function () {
        var vret = [];
        var vgetdata = [];
        var vlevel = [];

        if ($.isArray(thisObjGridFunctions.selectedGrid.defaultLevel)) {
            vlevel = thisObjGridFunctions.selectedGrid.defaultLevel;
        } else {
            vlevel[0] = thisObjGridFunctions.selectedGrid.defaultLevel;
        }

        $.each(vlevel, function (i, v) {
            vgetdata = retFilterInformed(v, undefined, true);
            vret = vret.concat(vgetdata);
        });



        return vret;

    }

}



