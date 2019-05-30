/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * vamos ver
 */

jQuery.fn.docRepStart = function (options) {

    var mDocRepVars = {

        docRepExtensions: {},
        docRepBrowserExt: '',
        docRepRelCode: -1,
        docRepController: 'docrep/general_document_repository',
        docRepMainDiv: '',
        docRepTitles: [],
        docRepGridObj: {},
        docRepGridName: '',
        docRepFileUploads: [],
        docRepExtArray: [],
        docRepToolbarObj: {},
        docRepToolbarName: '',
        divWidth: 0,
        divHeight: 0,
        docRepUploadData: undefined,
        docRepRelCode: 0,
        docRepRelID: 0,
        docRepInitializing: false,
        alreadyStarted: false,
        // setters
        setExtensions: function (ext) {
            this.docRepExtensions = ext;
        },
        setBrowserExtensions: function (ext2) {
            this.docRepBrowserExt = ext2;

        },
        setRelationCode: function (code) {
            this.docRepRelCode = code;
        },
        setController: function (controller) {
            this.docRepController = controller;
        },
        setDiv: function (div) {
            this.docRepMainDiv = div;

            this.divWidth = $(div).width();
            this.divHeight = $(div).height();

        },
        setTitles: function (titles) {
            this.docRepTitles = titles;
        },
        setGrid: function (gridstr) {
            this.docRepGridObj = gridstr;
        },
        setToolbar: function (toolb) {
            this.docRepToolbarObj = toolb;
        },
        setVars: function (vars) {
            //console.log(vars);
            // aqui comeca a distribuicao das variaveis. 
            this.setTitles(vars.labels);
            this.setExtensions(vars.extensionsInfo);
            this.setBrowserExtensions(vars.extensionsBrowser);
            // monto o grid em memoria:
            vars.gridInfo.name = this.docRepGridName;
            this.TypeOpts = vars.tpopt;

            this.setGrid(docRepCreateGrid(vars.gridInfo, this));
            // monyto toolbar em memoria!!
            vars.toolbarInfo.name = this.docRepToolbarName;
            this.setToolbar(docRepCreateToolbar(vars, this));


        },
        setArrays: function () {
            var obj = this;

            $.each(this.docRepExtensions, function (key, value) {

                if (obj.docRepExtArray[value.ds_document_repository_extension] === undefined) {
                    obj.docRepExtArray[value.ds_document_repository_extension] = [];
                }

                var a = {cd_document_repository_category: value.cd_document_repository_category,
                    ds_document_repository_category: value.ds_document_repository_category,
                    cd_document_repository_type: value.cd_document_repository_type,
                    ds_document_repository_type: value.ds_document_repository_type,
                    id: value.cd_document_repository_type,
                    text: value.ds_document_repository_type,
                    maxBytes: value.nr_max_size_kb * 1024
                };


                obj.docRepExtArray[value.ds_document_repository_extension].push(a);

            });

            //console.log('teste', obj.docRepExtArray);
        },

        retrieve: function () {
            docRepRetrieve(this);
        },

        // funcoes de trabalho!!!!
        init: function () {

            obj = this;

            if (obj.alreadyStarted) {
                return;
            }

            obj.alreadyStarted = true;


            obj.docRepInitializing = true;

            $.myCgbAjax({url: 'docrep/general_document_repository/getInformation/' + obj.docRepRelID,
                box: 'none',
                message: '',
                dataType: 'script',
                success: function (data) {
                    obj.posInit();

                },
                error: function () {
                    docRepUnblockUi();
                }

            });


        },

        addFiles: function (files) {
            obj.docRepUploadData = new FormData();
            obj.docRepGridObj.clear();
            var i = -1;

            $.each(files, function (key, value) {
                //console.log('dentro', value);
                var jsonRec = {recid: key, ds_filename: value.name, ds_document_repository: value.name};
                if (obj.TypeOpts != undefined) {
                    $.each(obj.TypeOpts, function (k, v) {
                        jsonRec.cd_type = v;
                        return false;
                    });
                }

                

                obj.docRepGridObj.add(jsonRec);
                if (!findTypeDocument(key, obj, value)) {
                    obj.docRepGridObj.remove(key);
                    return;
                }
                ;
                i++;
                obj.docRepUploadData.append(i, value);
            });

            docRepShowGrid(true, false, obj);
            //console.log(obj.docRepUploadData);
        },

        posInit: function () {
            var obj = this;
            this.setArrays();

            var divs = docRepReturnDivs(this);

            $(this.docRepMainDiv).html(divs);
            docRepBlockUi(true, obj.docRepTitles.init);




            this.docRepToolbarObj.render($("#DocRepToolbar"));
            this.docRepGridObj.render($("#DocRepInsGrid"));

            $("#DocRepContainer").on("paste", function (event) {

                var items = (event.clipboardData || event.originalEvent.clipboardData).items;

                for (var index in items) {
                    var item = items[index];

                    if (item.kind === 'file' && item.type.substring(0, 5) == 'image') {
                        var blob = item.getAsFile();
                        obj.addFiles([blob]);
                    }
                }
            });

            $('#docRepPlus').click(function () {
                $('.filetosend').click();
            });

            // eventos do botao de filesend
            $('.filetosend').hide();

            $('.filetosend').change(function (data) {
                obj.addFiles(this.files);


            });
            $('#DocRepContainerScroll').cgbMakeScrollbar({autoWrapContent: false, alwaysShowScrollbar: 0, theme: 'dark'});

            // evendos do grid!
            docRepGridEvents(this);


            // retrivo:
            this.retrieve();

        }



    };

    mDocRepVars.setDiv(this);
    mDocRepVars.docRepGridName = $(this).prop('id') + "_grid";
    mDocRepVars.docRepToolbarName = $(this).prop('id') + "_toolbar";
    mDocRepVars.docRepRelCode = options.code;
    mDocRepVars.docRepRelID = options.id;


    return mDocRepVars;
}


function libDocRepSetVars(vars) {
    //console.log(vars);
}

function docRepCreateGrid(str, obj) {

    str.toolbar.onClick = function (target, data) {
        switch (target) {
            case 'close' :

                obj.docRepGridObj.clear();
                $('.filetosend').val('');
                obj.docRepUploadData = new FormData();
                docRepShowGrid(false, false, obj);

                break;
            case 'upload' :
                docRepuploadFiles(obj);

                break;

            case 'update' :
                docRepUpdateData(obj);
                break;

        }
    };


    if (w2ui[str.name] !== undefined) {
        w2ui[str.name].destroy();
    }

    $().w2grid(str);

    if (obj.TypeOpts !== undefined) {
        w2ui[str.name].addColumn({field: 'cd_type',
            caption: obj.docRepTitles.doctp,
            size: '100px',
            sortable: true,
            resizable: true,
            editable: {type: 'list',
                items: obj.TypeOpts,
                showAll: true
            },
            render: function (record, index, col_index) {
                var html = this.getCellValue(index, col_index);
                return html.text || '';
            }
        });
    }


    w2ui[str.name].addColumn({field: 'cd_document_repository_type_list',
        caption: obj.docRepTitles.documentType,
        size: '30%',
        sortable: true,
        resizable: true,
        editable: {type: 'list',
            items: [],
            showAll: true
        },
        render: function (record, index, col_index) {
            var html = this.getCellValue(index, col_index);
            return html.text || '';
        }
    });


    w2ui[str.name].toolbar.hide('update');
    w2ui[str.name].toolbar.hide('upload');


    return w2ui[str.name];
}

function docRepCreateToolbar(vars, obj) {
    // crio toolbar:::
    if (w2ui[vars.toolbarInfo.name] !== undefined) {
        w2ui[vars.toolbarInfo.name].destroy();
    }

    vars.toolbarInfo.onClick = function (event) {

        if (event.target == 'delete') {
            docRepDeleteCheckedMsg(obj);
        }
        ;

        if (event.target == 'download') {
            docRepDownloadImages();
        }

        if (event.target == 'edit') {
            docRepEditTitle(undefined, obj);
        }
    };

    $().w2toolbar(vars.toolbarInfo);

    // adiciono depois para manter a traducao!
    toolbarAddEdit(w2ui[vars.toolbarInfo.name]);
    toolbarAddDel(w2ui[vars.toolbarInfo.name]);
    w2ui[vars.toolbarInfo.name].add({id: "download", hint: 'Download', icon: "fa fa-download", caption: "", type: "button"});


    return w2ui[vars.toolbarInfo.name];

}

function docRepReturnDivs(obj) {
    var ret;
    ret = "<div id='DocRepContainerScroll' style='max-height: calc(100vh - 200px);' >";

    ret = ret + "<div id='DocRepContainer' class='col-md-12' style=';' >";
    ret = ret + "<div class='row'><div id='DocRepToolbar' style='width:100%;height:32px;background-color: rgb(255,255,255)'> </div></div>";
    ret = ret + "  <div class='row'><div id='DocRepInsGrid' style='width:100%;height:auto;min-height: 600px;background-color: #007400; display:none;'> </div></div>";
    ret = ret + "  <div class='row'><div id='DocRepImages' style='width:100%;height:100%;min-height: 600px;overflow-y: hidden;background-color: #e0e0e0; margin-bottom:10px;'>";
    ret = ret + "    <div id='docRepIns' class='docrep_cell'>";
    ret = ret + "      <div class='docrep_cell_image'> <i id='docRepPlus' class='fa fa-plus fa-5x' style='padding-top: 40px;padding-left: 35px; color:gray;cursor: pointer;'> </i> </div>";
    ret = ret + "    </div>";
    ret = ret + "</div></div>";
    ret = ret + "</div>";
    ret = ret + "</div>";

    ret = ret + "<form id='DocRepForm'>";
    ret = ret + "<input type='file' accept='" + obj.docRepBrowserExt + "' class='filetosend' id='filetosend' nane = 'filetosend' multiple='multiple'>";
    ret = ret + "</form>";

    return ret;
}

function findTypeDocument(recid, obj, vFiles) {
    if (vFiles != undefined) {
        var sizeBytes = vFiles.size;
    } else {
        var sizeBytes = -1;
    }
    var record = obj.docRepGridObj.get(recid);
    var extension = record.ds_filename;
    extension = extension.substring(extension.lastIndexOf('.') + 1).toLowerCase();
    var nrfound = 0;
    var extData = [];
    var vMaxSize = 0;


    if (obj.docRepExtArray[extension] === undefined) {
        return false;
    }

    if (sizeBytes != -1) {
        $.each(obj.docRepExtArray[extension], function (i, v) {

            if (sizeBytes <= v.maxBytes) {
                extData.push(v);
            } else {
                if (vMaxSize < v.maxBytes) {
                    vMaxSize = v.maxBytes;
                }
            }
        });
    } else {
        extData = obj.docRepExtArray[extension];
    }

    if (extData.length == 0) {

        var vx = javaMessages.errorSize.replace('%1', vFiles.name).replace('%2', vMaxSize).replace('%3', sizeBytes);


        messageBoxError(vx);
        return false;
    }




    if (obj.docRepExtArray[extension].length === 1) {
        record.cd_document_repository_type = extData[0].cd_document_repository_type;
        record.ds_document_repository_type = extData[0].ds_document_repository_type;
        record.cd_document_repository_category = extData[0].cd_document_repository_category;

        record.cd_document_repository_type_list = {id: record.cd_document_repository_type,
            text: record.ds_document_repository_type,
            cd_document_repository_type: extData[0].cd_document_repository_type,
            ds_document_repository_type: extData[0].ds_document_repository_type,
            cd_document_repository_category: extData[0].cd_document_repository_category
        };

    }

    record.count = nrfound;
    record.extension = extension;
    record.extAvailList = extData;
    obj.docRepGridObj.set(recid, record);
    //console.log(record);

    return true

}

function docRepShowGrid(bool, showUpdate, obj) {

    if (showUpdate == 'undefined') {
        showUpdate = false;
    }

    if (showUpdate) {
        obj.docRepGridObj.toolbar.show('update');
        obj.docRepGridObj.toolbar.hide('upload');
    } else {
        obj.docRepGridObj.toolbar.hide('update');
        obj.docRepGridObj.toolbar.show('upload');
    }


    if (bool) {
        //$('#DocRepInsGrid').height('488px');
        $('#DocRepInsGrid').show();
        $('#DocRepImages').hide();
        obj.docRepGridObj.refresh();

    } else {
        //$('#DocRepImages').height('488px');
        $('#DocRepInsGrid').hide();
        $('#DocRepImages').show();
        obj.docRepGridObj.refresh();
    }
}
;


function docRepGridEvents(obj) {

    obj.docRepGridObj.on('editField', function (event) {
        var colname = this.columns[event.column].field;
        var rec = this.get(event.recid);
        // controle soh vale pro list
        if (colname !== 'cd_document_repository_type_list') {
            return;
        }

        if (rec.count === 1) {
            event.preventDefault();
            return;
        }

        this.columns[event.column].editable.items = rec.extAvailList;

    });


    obj.docRepGridObj.on('change', function (event) {




        var colname = this.columns[event.column].field;
        // controle soh vale pro list
        if (colname != 'cd_document_repository_type_list') {
            return;
        }


        //sellist = this.getItem(event.recid, 'cd_document_repository_type_list');
        sellist = event.value_new;
        //console.log(sellist);

        var rec = {recid: event.recid, cd_document_repository_type: sellist.id, ds_document_repository_type: sellist.text, cd_document_repository_category: sellist.cd_document_repository_category};
        //console.log(rec);
        //this.setItem (event.recid, 'cd_document_repository_type', sellist.cd_document_repository_type );
        //this.setItem (event.recid, 'ds_document_repository_type', sellist.ds_document_repository_type );
        //this.setItem (event.recid, 'cd_document_repository_category', sellist.cd_document_repository_category );

        //this.set(event.recid, rec);
    });


}

function docRepuploadFiles(obj) {
    docRepBlockUi(false, obj.docRepTitles.uploading);
    docRepProgressCreate();

    // COMECA O ENVIO
    // monto o array com os dados pertinentes ao grid
    var gridInfo = [];
    var error = false;
    obj.docRepGridObj.mergeChanges();

    $.each(obj.docRepGridObj.records, function (key, value) {
        var gridInfoRow = {};

        if (value.ds_document_repository == undefined) {
            toastErrorBig(obj.docRepTitles.errortitle);
            error = true;
            return false;
        }


        if (value.cd_document_repository_type_list == undefined) {
            toastErrorBig(obj.docRepTitles.errortype);
            error = true;
            return false;
        }


        if (obj.TypeOpts !== undefined) {

            if (value.cd_type == undefined) {

                toastErrorBig(obj.docRepTitles.errortype);
                error = true;
                return false;

            } else {
                gridInfoRow.cd_type = value.cd_type.id;
                gridInfoRow.ds_type = value.cd_type.text;
            }


        }

        gridInfoRow.ds_filename = value.ds_filename;
        gridInfoRow.cd_document_repository_category = value.cd_document_repository_type_list.cd_document_repository_category;
        gridInfoRow.cd_document_repository_type = value.cd_document_repository_type_list.cd_document_repository_type;
        gridInfoRow.ds_document_repository = value.ds_document_repository;

        gridInfo.push(gridInfoRow);

    });

    if (error) {
        docRepUnblockUi();
        return;
    }


    obj.docRepUploadData.append('gridInfo', JSON.stringify(gridInfo));

    //$('#docRepProgressBar').show();
    //$('#docRepProgressBar').progressbar('option', 'max', 100);
    var cont = obj.docRepController + '/sendFiles/' + obj.docRepRelID + '/' + obj.docRepRelCode;


    $.ajax({
        async: true,
        url: cont,
        type: 'POST',
        data: obj.docRepUploadData,
        dataType: 'json',
        processData: false, // Don't process the files
        contentType: false, // Set content type to false as jQuery will tell the server its a query string request
        xhr: function () {
            var xhr = $.ajaxSettings.xhr();

            xhr.upload.onprogress = function (progress) {
                // calculate upload progress
                if (progress.lengthComputable) {
                    var percentage = Math.floor((progress.loaded / progress.total) * 90);

                    docRepProgressValue(percentage);
                } else {
                    docRepProgressValue(false);

                }
            }

            return xhr;

        },
        success: function (data, textStatus, jqXHR)
        {
            if (data.ok) {
                $('.filetosend').val('');
                //filesToUpload = undefined;
                docRepShowGrid(false, false, obj);

                //$(data.cells).insertAfter('#docRepIns').show('slow');

                $('#docRepIns').after(data.cells).show('slow');


                obj.docRepUploadData = new FormData();
                docRepAfterNewCell(obj);

                docRepUnblockUi();

                $(window).trigger('updateDocRep');


            } else
            {
                toastErrorBig(data.message);
                docRepUnblockUi();

                //$('#docRepProgressBar').hide();


                // Handle errors here
                //console.log('ERRORS: ' + data.error);
            }

            //unlockMain();


        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            docRepUnblockUi();

            if (checkSessionExpired(jqXHR.responseText)) {
                return;
            }

            console.log(jqXHR, textStatus, errorThrown);

            toastErrorBig(jqXHR.responseText);
            //$('#docRepProgressBar').hide();
            //unlockMain();

        }
    });

}


function docRepAfterNewCell(obj) {
    docRepmakeTooltip(false, obj);
    docRepmakeCheckbox();
}

function docRepmakeCheckbox() {

    $(".docrepCheckbox[needStart='Y']").each(function ( ) {
        $(this).iCheck({
            checkboxClass: 'icheckbox_minimal-blue'
        });

        $(this).closest('.icheckbox_minimal-blue').css('margin-top', '-10px');

        $(this).attr('needStart', 'N');

        $(this).on('ifToggled', function (event) {
            var $div = $(this).closest("div[mainCell='Y']");

            if ($(this).is(':checked')) {

                $($div).addClass('docrep_cell_selected');
            } else {
                $($div).removeClass('docrep_cell_selected');

            }

        });

    });

}


function docRepmakeTooltip(onlyToolTip, obj) {

    if (onlyToolTip == undefined) {
        onlyToolTip = false;
    }

    $div = $(".docrep_cell_text[needStart='Y']");
    $($div).each(function ( ) {
        var span = $(this).children('span');

        //console.log(this.offsetWidth, this.scrollWidth, $(this).text());
        // agora eu faco controle apenas se o texto estiver maior
        if (this.offsetWidth < this.scrollWidth) {
            
            
            
            span.attr('title', span.text());
            span.text(span.text().substring(0, 25));
            span.tooltip({show: {effect: 'slideDown', delay: 50}, container: '#DocRepImages', placement: 'bottom'});
        }

        $(this).attr('needStart', 'N');
        if (!onlyToolTip) {
            docRepmakeMenu(this, obj);
        }

    });
}

function docRepmakeMenu(sel, obj) {
    var $menu = $(sel).children("i");
    var id = $menu.attr('id');
    var $main = $(sel).closest("div[mainCell='Y']");
    var code = $main.attr('code');

    $(function () {
        $('#' + id).contextPopup({
            //title: javaMessages.filterOperator,
            rightOrLeft: 'left',
            supplierCode: code,
            items: [
                {label: obj.docRepTitles.menuEditTitle, id: 'X', action: function (e, set) {
                        docRepEditTitle(code, obj);

                    }},
                null,
                {label: obj.docRepTitles.menuDownload, id: 'S', action: function (e, set) {
                        docRepDownloadImages(set.supplierCode);
                    }},
                {label: obj.docRepTitles.menuDelete, id: 'L', action: function (e, set) {
                        messageBoxYesNo(javaMessages.conf_delete, function () {

                            DocRepdeleteFromRep(set.supplierCode, obj);
                        });
                    }}
            ]
        });
    });

}


function docRepRetrieve(obj) {


    if (!obj.docRepInitializing) {
        docRepBlockUi(true, obj.docRepTitles.retrieve);
    }

    var cont = obj.docRepController + '/retrieveByRelation/' + obj.docRepRelID + '/' + obj.docRepRelCode;

    $.myCgbAjax({url: cont,
        box: 'none',
        //message: javaMessages.inserting,
        data: [],
        dataType: 'html',
        success: function (data) {
            $('#docRepIns').after(data);
            docRepAfterNewCell(obj);
            docRepUnblockUi();
            obj.docRepInitializing = false;


        },
        error: function (jqXHR, textStatus, errorThrown) {
            toastErrorBig(textStatus);
            docRepUnblockUi();
            obj.docRepInitializing = false;
        }
    });

}




function docRepEditTitle(code, obj) {
    obj.docRepGridObj.clear();
    var info = [];

    if (code != undefined) {
        var infoline = {};
        infoline.code = code;
        infoline.cdtype = $('#docRep' + code).attr('cdt');
        infoline.title = $('#docRepTextSpan' + code).text();
        infoline.dsfile = $('#docRep' + code).attr('fln');
        infoline.cd_type = $('#docRep' + code).attr('tpcode');

        info.push(infoline);
    } else {
        $(".docrepCheckbox:checked").each(function ( ) {
            var $main = $(this).closest("div[mainCell='Y']");
            var infoline = {};
            infoline.code = $main.attr('code');
            infoline.cdtype = $main.attr('cdt');
            infoline.dsfile = $main.attr('fln');
            infoline.cd_type = $main.attr('tpcode');


            infoline.title = $('#docRepTextSpan' + infoline.code).text();

            infoline.dstype = '';
            info.push(infoline);
        });

    }

    if (info.length === 0) {
        return;
    }
    docRepShowGrid(true, true, obj);


    $.each(info, function (keyTP, valueTP) {

        $.each(obj.docRepExtensions, function (key, value) {
            if (valueTP.cdtype === value.cd_document_repository_type) {
                info[keyTP].dstype = value.ds_document_repository_type;
            }

        });
    });
    // adiciona os dados no grid!!!


    $.each(info, function (keyTP, valueTP) {
        var jsonRec = {recid: valueTP.code,
            ds_document_repository: valueTP.title,
            ds_document_repository_type: valueTP.dstype,
            cd_document_repository_type: valueTP.cdtype,
            ds_filename: valueTP.dsfile,
            cd_document_repository: valueTP.code,
            cd_document_repository_type_list: {id: valueTP.cdtype, text: valueTP.dstype}};

        if (valueTP.cd_type != undefined) {
            $.each(obj.TypeOpts, function (k, v) {
                if (v.id == valueTP.cd_type) {
                    jsonRec.cd_type = v;
                }
            });
        }

        obj.docRepGridObj.add(jsonRec);

        findTypeDocument(valueTP.code, obj);
    });

}

function docRepUpdateData(obj) {

    var gridInfo = [];
    var error = false;
    obj.docRepGridObj.mergeChanges();

    $.each(obj.docRepGridObj.records, function (key, value) {
        var gridInfoRow = {};

        if (value.ds_document_repository == undefined) {
            toastErrorBig(obj.docRepTitles.errortitle);
            error = true;
        }

        if (value.cd_document_repository_type == undefined) {
            toastErrorBig(obj.docRepTitles.errortype);
            error = true;
        }

        gridInfoRow.ds_filename = value.ds_filename;
        gridInfoRow.cd_document_repository_category = value.cd_document_repository_category;
        gridInfoRow.cd_document_repository_type = value.cd_document_repository_type;
        gridInfoRow.ds_document_repository = value.ds_document_repository;
        //gridInfoRow.cd_supplier = obj.docRepRelCode;
        gridInfoRow.cd_document_repository = value.cd_document_repository;


        if (obj.TypeOpts !== undefined) {

            if (value.cd_type == undefined) {

                toastErrorBig(obj.docRepTitles.errortype);
                error = true;

            } else {
                gridInfoRow.cd_type = value.cd_type.id;
                gridInfoRow.ds_type = value.cd_type.text;

            }

        }


        gridInfo.push(gridInfoRow);

    });

    if (error) {
        return;
    }

    var cont = obj.docRepController + '/updateData/' + obj.docRepRelID;

    $.myCgbAjax({url: cont,
        box: 'auto',
        message: javaMessages.updating,
        data: {info: JSON.stringify(gridInfo)},
        success: function (data) {

            if (data.ok) {
                docRepShowGrid(false, false, obj);
                toastUpdateSuccess();
                obj.docRepGridObj.clear();
                docRepUpdateDataOnCell(gridInfo, obj);

                $(window).trigger('updateDocRep');

            }
        }
    });


}

function docRepUpdateDataOnCell(gridinfo, obj) {

    $.each(gridinfo, function (key, value) {
        var div = $("#docRep" + value.cd_document_repository);
        var div_cell = $('#docrep_cell_text' + value.cd_document_repository);
        var span = $('#docRepTextSpan' + value.cd_document_repository);

        $(div).attr('cdt', value.cd_document_repository_type);
        $(div).attr('tpcode', value.cd_type);

        $(div).find('#typeDesc' + value.cd_document_repository).text(' ' + value.ds_type);

        $(span).text(value.ds_document_repository);

        $(span).removeAttr('title');

        $(div_cell).attr('needStart', 'Y');
    });

    docRepmakeTooltip(true, docRepmakeTooltip);

}

function docRepDownloadImages(code) {
    var codes = '';

    if (code != undefined) {
        codes = code;
    } else {
        codes = '';
        $(".docrepCheckbox:checked").each(function ( ) {
            var $main = $(this).closest("div[mainCell='Y']");
            var code = $main.attr('code');
            if (codes == '') {
                codes = code;
            } else {
                codes = codes + 'x' + code;
            }

        });
    }

    if (codes == '') {
        return false;
    }

    var cont = obj.docRepController + '/downloadImages/' + codes;

    document.location = cont;

}

function DocRepdeleteFromRep(cd_document_repository, obj) {

    var cont = obj.docRepController + '/deleteFromRepository/' + obj.docRepRelID + '/' + obj.docRepRelCode + '/' + cd_document_repository;

    $.myCgbAjax({url: cont,
        box: 'auto',
        message: javaMessages.deleting,
        //parameter: toSend,
        dataType: 'json',
        success: function (data) {
            if (data.ok) {
                var options = {};
                var selec = '#docRep' + cd_document_repository;
                $(selec).hide('scale', options, 300, function () {
                    $(selec).remove();
                    $(window).trigger('updateDocRep');
                });
                ;
            } else
            {
                toastErrorBig(data.message);
            }

        }
    });



}

function docRepDeleteCheckedMsg(obj) {
    messageBoxYesNo(javaMessages.conf_delete, function () {
        docRepdeleteChecked(obj);
    })
}

function docRepdeleteChecked(obj) {
    $(".docrepCheckbox:checked").each(function ( ) {
        var $main = $(this).closest("div[mainCell='Y']");
        var code = $main.attr('code');
        DocRepdeleteFromRep(code, obj);
    });
}

function docRepBlockUi(showSpinner, message) {
    w2utils.lock($('#DocRepContainer'), message, showSpinner);
    $('.w2ui-lock-msg').append('<br> <br> <div id="docrepProgressBarDiv"> </div>');
    $('.w2ui-lock-msg').height(50);
}





function docRepUnblockUi() {
    w2utils.unlock($('#DocRepContainer'));
}

function docRepProgressCreate() {
    $('#docrepProgressBarDiv').progressbar();
    $('#docrepProgressBarDiv').progressbar('option', 'value', 0);
    $('#docrepProgressBarDiv').progressbar('option', 'max', 100);

    progressbar = $("#docrepProgressBarDiv"),
            progressbarValue = progressbar.find(".ui-progressbar-value");
    progressbarValue.css({"background": 'yellow'});
}


function docRepProgressValue(value) {
    $('#docrepProgressBarDiv').progressbar('option', 'value', value);
}
