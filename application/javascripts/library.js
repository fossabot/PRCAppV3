/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var cgbChosenF = {};
var siteToFormModal = "";
var modalForm;
//var picklistColor  = '#FEFF9B';
var picklistColor = '#C0fDCC0';
var picklistBkColor = '#000000';
var picklistDisColor = '#f1f1f1';
var picklistDisBkColor = '#000000';

var SBSUpdModel = '';
var SBSHasChanges = false;
var dateMask = 'mm/dd/yyyy';
var picklistCallBack = undefined;
var tag2InitialData = [];
var select2OnChangeFunction = [];
var w2OnChangeFunction = [];
var w2TagStarted = [];
var controllerName = '';
var pickListModalVar, SBSModalVar, SBSModalFormsVar;
var lockBoxesOn = [];
var myCgbAjaxXhrPool = [];
var cgbScrollInformation = [];
var isInternetON = true;
var isSessionExpired = false;
var vIntoJSData = [];
var openingModal = false;

//$(document).ajaxStart(function() { Pace.restart(); });

String.prototype.capitalize = function () {
    return this.replace(/(^|\s)([a-z])/g, function (m, p1, p2) {
        return p1 + p2.toUpperCase();
    });
};


function makeFilterWithEnter(fnc, idfilterarea) {
    if (idfilterarea == undefined) {
        idfilterarea = '#showfilter';
    }

    $(idfilterarea).find('.simple_filter_int').keypress(function (e) {
    if (e.which == 13) {
        fnc();
    }});

    
    $(idfilterarea).find('.simple_filter_upper ').keypress(function (e) {
        if (e.which == 13) {
            fnc();
        }
    });
}

function introAddFilterArea(box) {
    if (box == undefined) {
        box = '#showfilter';
    }

    return [{element: box, intro: 'Filter Area', position: 'bottom'}];


}

function introAddNew(arrayData) {


    vIntoJSData.push({steps: arrayData.steps});

    return vIntoJSData[vIntoJSData.length - 1];
}

function introRemove() {
    vIntoJSData.pop();
}

function introStart() {

    var vpos = vIntoJSData[vIntoJSData.length - 1];
    var vsteps = [];
    $.each(vpos.steps, function (i, v) {
        if (v.element != undefined) {
            if ($(v.element).length > 0 && $(v.element).is(':visible')) {
                vsteps.push(v);
            }

        } else {
            vsteps.push(v);
        }
    });
}

$('body').on('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend',
        function (e) {
            // controle do menu lateral, depois da animacao
            if ($(e.target).hasClass('main-sidebar')) {
                $('body').trigger('togglePushMenu');
            }
        });

$(window).resize(function () {
    if (this.resizeTO)
        clearTimeout(this.resizeTO);
    this.resizeTO = setTimeout(function () {
        $(this).trigger('resizeEnd');
    }, 300);
});

function chkUndefined(vlr, ret) {
    if (vlr == undefined || vlr == null) {
        return ret;
    } else {
        return vlr;
    }
}

function resizeGrid(divHeight) {

    if ($('#myGrid').height() == divHeight) {
        return;
    }

    $('#myGrid').height(divHeight);
    w2ui[gridName].resize();
}

function toggleDashBoard() {
    if ($('#dashboardArea').is(":visible")) {
        hideDashBoard();
    } else {
        showDashBoard();
    }
}

function showDashBoard(withAnimation) {
    $('#dashboardArea').show();
    $('#content-body').hide();
    $('.choption').hide();
    $('.breadcrumb').hide();
    $('.content-header').hide();
    $('#dashHideDashboard').show();
    $('#dashShowDashboard').hide();
    if (typeof dsMainObjectDash == 'object') {
        setGrpGridHeightDash();

        dsMainObjectDash.resizeAll();
        dsMainObjectDash.makeHelper();
        dsMainObjectDash.addListeners();


    }
}

function hideDashBoard(withAnimation) {
    $('#dashboardArea').hide();
    $('#content-body').show();
    $('.choption').show();
    $('.breadcrumb').show();
    $('.content-header').show();
    $('#dashHideDashboard').hide();
    $('#dashShowDashboard').show();
    if (typeof dsMainObjectDash == 'object') {
        dsMainObjectDash.destroyHelper();
    }


}


function openpage(page, title, id, forcePage, vParam) {

    if (forcePage == undefined) {
        forcePage = false;
    }

    // controle do dsMainObject
    if (typeof dsMainObject === 'object') {
        if (!dsMainObject.beforeClose() && !forcePage) {

            messageBoxYesNo(javaMessages.info_changed_close, function () {
                openpage(page, title, id, true);
            });
            return;
        }

        if (typeof dsMainObject.beforeCloseNoMsg !== "undefined" &&  !dsMainObject.beforeCloseNoMsg() && !forcePage) {
            return;
        }

        dsMainObject.close();

    }


    if (page != '#') {

        waitMsgON('body');

        setTimeout(function () {


            removeTrash();
            hideDashBoard();
            $('#content-body').html(javaMessages.loading);


            $('#content-body').load(page, {param: vParam}, function () {


                waitMsgOFF('body');
                checkSessionExpired($(this).html());

            });
            $('.choption').html(title);

        }, 0);

    }

    var bread = '<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>';
    //var menu = $('#'+id).parents('li');
    var toadd = '';

    $('#' + id).parents('li').each(function () {
        toadd = '<li><a href="#"><i></i> ' + $(this).find('span').html() + '</a></li>' + toadd;
    });

    toadd = toadd + '<li><a href="#"><i></i><strong> ' + title + '</strong></a></li>';
    $('.breadcrumb').html(bread + toadd);

}

function removeTrash() {

    $.each(myCgbAjaxXhrPool, function (i, v) {
        if (v != undefined) {
            v.abort();
        }
    });

    Pace.stop();

    myCgbAjaxXhrPool = [];

    $('.loadingoverlay').each(function () {
        $(this).remove();


    });

    $('#content-body *').off();
    $('#content-body').off();

    $('#content-body').empty();
    $('.filterClassToKill').remove();
    $(window).off('resize.mainResize');
    $(window).off('resize.prd');

    $(window).off('resizeEnd.mainResize');

    $(window).off('scroll.unveil');
    $(window).off('resize.unveil');
    $(window).off('lookup.unveil');

    $("body").off('togglePushMenu toggleFilter');

    posGridRetrieve = undefined;
    posSBSClosed = undefined;
    cgbChosenF = {};
    SBSUpdModel = '';
    SBSHasChanges = false;
    picklistCallBack = undefined;
    tag2InitialData = [];
    select2OnChangeFunction = [];
    w2OnChangeFunction = [];
    w2TagStarted = [];
    // removo tudo relacionado ao w2ui.
    for (var o in w2ui) {
        if (w2ui[o].systemObj) {
            continue;
        }
        w2ui[o].destroy();
    }

    dsMainObject = undefined;
    dsFormObject = undefined;
    cgbScrollInformation = [];
    openingModal = false;
}

function getWorkArea() {
    var offset = 0;
    p = $('#showfilter');

    if (!p.is(':visible')) {
        p = $('.content-header');
        offset = 30;
    }

    var position = p.position();

    return ($(window).height() - (p.height() + position.top + 10 + offset));

}

function dateFormatToForm(datein) {
    return w2utils.formatDate(datein);
}


function toastUpdateSuccess() {
    toastSuccess(javaMessages.updated);
}

function toastSuccess(message) {
    toastr.options.closeButton = false;
    toastr.options.showDuration = 500;
    toastr.options.timeOut = 500;
    toastr.options.extendedTimeOut = 1000;
    toastr.options.positionClass = "toast-top-right";
    toastr.success(message);
}

function toastErrorBig(message) {
    messageBoxError(message);
    return;
    toastr.options.closeButton = true;
    toastr.options.showDuration = 0;
    toastr.options.timeOut = 0;
    toastr.options.extendedTimeOut = 0;
    toastr.options.positionClass = "toast-top-full-width";
    toastr.error(message);
}

var notifyIdShown = [];

function toastSysNotify(message, acknowledge, notifyId, hasAttachment, feedbackId) {
    if (notifyIdShown[notifyId] != undefined) {
        return;
    }
    notifyIdShown[notifyId] = 'Y';
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "showDuration": 0,
        "preventDuplicates": true,
        "timeOut": 0,
        "extendedTimeOut": 1000,
        "positionClass": "toast-top-right",
        "onclick": null
    };
    if (hasAttachment == 1) {
        var download_button = '<br /><br /><button type="button" class="btn-success pull-left" title="Download attachment to get new feature in detail" ' +
            'onclick="window.open(\'system_feedback_comments/downloadAttachment/' + feedbackId + '\', \'_self\');"><i class="fa fa-download" aria-hidden="true"></i></button>';
        message += download_button;
    }
    if (acknowledge == 1) {
        toastr.options.tapToDismiss = false;
        toastr.options.extendedTimeOut = 0;
        var br = '<br /><br />';
        if (hasAttachment == 1) br = '';
        var button = br + '<button type="button" id="toastr-okBtn" class="btn-success pull-right" title="Got and close"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i></button>';
        message += button;
    }
    var toastrObj = toastr['success'](message);
    if (toastrObj.find('#toastr-okBtn').length) {
        toastrObj.delegate('#toastr-okBtn', 'click', function () {
            $.myCgbAjax({
                url: 'system_notification/setSysNotification/' + notifyId,
                box: 'none',
                systemRequest: true,
                success: function (data) {
                    // nothing to do, only to set status
                }
            });
            toastrObj.remove();
        });
    }
}


function messageBoxOkCancel(description, functionOk, functionCancel) {

    if (functionCancel == undefined) {
        functionCancel = function () {
        };
    }

    $.confirm({
        title: javaMessages.confirm,
        theme: 'material',
        backgroundDismiss: false,
        onOpenBefore: function () {
            this.$el.css('z-index', '1000000105');

        },

        buttons: {
            confirm: {
                text: javaMessages.buttonOK,
                btnClass: 'btn-info',
                action: function () {
                    functionOk()
                }
            },
            cancel: {
                text: javaMessages.buttonCancel,
                btnClass: 'btn-danger',

                action: function () {
                    functionCancel();
                }
            }
        },

        content: description,
        columnClass: 'col-md-6 col-md-offset-3 messageBoxCGBClass'
    }
    );
}

function messageBoxAlert(description) {
    //hideScrollBeforeModal();


    $.alert({
        title: javaMessages.alert,
        content: description,
        columnClass: 'col-md-6 col-md-offset-3 messageBoxCGBClass',
        onOpenBefore: function () {
            this.$el.css('z-index', '1000000105');

        },

        buttons: {
            confirm: {
                text: javaMessages.buttonOK,
                btnClass: 'btn-info',
                action: function () {
                    //functionOk()
                }
            }
        },

        theme: 'material',
    });

    return;
}

String.prototype.replaceAll = function(search, replacement) {
    var target = this;
    return target.replace(new RegExp(search, 'g'), replacement);
};

function messageBoxError(description, okfunction) {
    
    description = "<div style='max-height: calc(100vh - 200px); overflow-y: auto'>"+description+"</div>"
    
    $.alert({
        title: javaMessages.ErrorTitle,
        icon: 'fa fa-warning',
        content: description,
        columnClass: 'col-md-6 col-md-offset-3 messageBoxCGBClass',
        theme: 'material',
        onOpenBefore: function () {
            this.$el.css('z-index', '1000000105');
        },
        buttons: {
            confirm: {
                text: javaMessages.buttonOK,
                btnClass: 'btn-info',
                action: function () {
                    if (okfunction !== undefined) {
                        okfunction();
                    }
                }
            }
        }
    });

}

function messageBoxYesNo(description, functionOk, functionCancel) {
    //hideScrollBeforeModal();

    if (functionCancel == undefined) {
        functionCancel = function () {
        };
    }

    $.confirm({
        title: javaMessages.confirm,
        theme: 'material',
        backgroundDismiss: false,
        content: description,
        onOpenBefore: function () {
            this.$el.css('z-index', '1000000105');
        },

        buttons: {
            confirm: {
                text: javaMessages.buttonYes,
                btnClass: 'btn-info',
                action: functionOk
            },
            cancel: {
                text: javaMessages.buttonNo,
                btnClass: 'btn-danger',

                action: functionCancel
            }
        },

        columnClass: 'col-md-6 col-md-offset-3 messageBoxCGBClass'

    });

}


function waitMsgON(box, spinner, message) {

    if (!$(box).is(":visible") && box != 'body') {
        return;
    }

    if (spinner === undefined) {
        spinner = true;
    }

    if (message === undefined) {
        message = javaMessages.loading;
    }

    $.LoadingOverlaySetup({color: "rgba(106, 105, 108, 0.6)", zIndex: 999999, fade: false});
    if (!spinner) {
        customElement = $('<div> <span>' + message + '<span> </div>');

    } else {
        customElement = $('<div style="background-color:rgb(128,128,128);padding: 10px;"><div class="loader"><div class="loader-inner line-scale-pulse-out"><div></div><div></div><div></div><div></div><div></div></div></div></div> <div> <span>' + message + '<span> </div>');
    }


    $(box).LoadingOverlay("show", {
        image: "",
        custom: customElement
    });

}

function waitMsgOFF(box) {

    $(box).LoadingOverlay("hide");

    //w2utils.unlock(box);
}

function hideFilter(bFast) {
    if (bFast == undefined || bFast == false) {
        var xSpeed = 'slow';
    } else {
        var xSpeed = 'fast';
    }

    $(".showfilter").slideToggle(xSpeed, function () {
        $('body').trigger('toggleFilter');
    });
}

function isFilterVisible() {
    return $(".showfilter").is(':visible');

}

function hasRequiredInformation() {
    var ret = true;
    $(".w2ui-required :input").each(function () {
        if ($(this).val() === "" && $(this).attr('id') != undefined) {
            ret = false;
        }

    });

    return ret;
}

function resetAllFilter(levelInfo, filterNames) {
    var levelArray = [];
    if (levelInfo == undefined) {
        levelInfo = "1";
    }

    if (filterNames == undefined) {
        filterNames = [];
    }


    if (!$.isArray(levelInfo)) {
        levelArray[0] = levelInfo;
    } else {
        levelArray = levelInfo;
    }

    $.each(levelArray, function (i, level) {

        $('.simple_filter_upper[lvl="' + level + '"]').each(function () {
            $(this).val('');
        });

        $('.simple_filter_int[lvl="' + level + '"]').each(function () {
            $(this).autoNumeric('set', '');
        });



        $('.datefilter[lvl="' + level + '"]').each(function () {
            $(this).find('.from').val('');
            $(this).find('.to').val('');
        });
        $('.picklist_filter[lvl="' + level + '"]').each(function () {
            select2FullReset($(this).attr('id'));
        });
    });
}


function checkFilterMissing(levelInfo, filterNames) {
    var levelArray = [];
    if (levelInfo == undefined) {
        levelInfo = "1";
    }

    if (filterNames == undefined) {
        filterNames = [];
    }


    if (!$.isArray(levelInfo)) {
        levelArray[0] = levelInfo;
    } else {
        levelArray = levelInfo;
    }


    var vCannotbyGroup = true;
    var vHasByGroup = false;
    var vcannot = false;
    var groupMissing = [];
    var demandedMissing = [];

    $.each(levelArray, function (i, level) {

        $('.simple_filter_upper[dem!="N"][lvl="' + level + '"]').each(function () {

            var vlrc = $(this).val();
            vlrc = vlrc.toUpperCase();
            var itid = $(this).attr('id');
            var vdem = $(this).attr('dem');

            var vlabel = $(this).closest('.form-group').find('.control-label-filter').html();

            if ((filterNames.length > 0 && filterNames.indexOf(itid) == -1)) {
                return true;
            }


            if (vdem == 'A') {
                vHasByGroup = true;
                if (vlrc != "") {
                    vCannotbyGroup = false;
                } else {
                    groupMissing.push(vlabel);
                }
            }

            if (vdem == 'Y' && vlrc == '') {
                demandedMissing.push(vlabel);
                vcannot = true;
            }

        });


        $('.simple_filter_int[dem!="N"][lvl="' + level + '"]').each(function () {

            var vlrc = $(this).autoNumeric('get');
            vlrc = vlrc.toUpperCase();
            var itid = $(this).attr('id');
            var vdem = $(this).attr('dem');



            var vlabel = $(this).closest('.form-group').find('.control-label-filter').html();

            if ((filterNames.length > 0 && filterNames.indexOf(itid) == -1)) {
                return true;
            }


            if (vdem == 'A') {
                vHasByGroup = true;
                if (vlrc != "") {
                    vCannotbyGroup = false;
                } else {
                    groupMissing.push(vlabel);
                }
            }

            if (vdem == 'Y' && vlrc == '') {
                demandedMissing.push(vlabel);
                vcannot = true;
            }

        });

        $('.datefilter[dem!="N"][lvl="' + level + '"]').each(function () {

            var itid = $(this).attr('field');
            var vdatafrom = $(this).find('.from').val();
            var vdatato = $(this).find('.to').val();
            var vlabel = $(this).closest('.form-group').find('.control-label-filter').html();
            var vdem = $(this).attr('dem');


            if (vdatato == '' || vdatato == undefined) {
                vdatato = vdatafrom;
            }


            if (filterNames.length > 0 && filterNames.indexOf(itid) == -1) {
                return true;
            }

            if (vdem == 'A') {
                vHasByGroup = true;
                if (vdatafrom !== "" && vdatafrom !== undefined) {
                    vCannotbyGroup = false;
                } else {
                    groupMissing.push(vlabel);
                }
            }

            if (vdem == 'Y' && vdatafrom !== "" && vdatafrom !== undefined) {
                demandedMissing.push(vlabel);
                vcannot = true;
            }
        });


        $('.picklist_filter[dem!="N"][lvl="' + level + '"]').each(function () {

            var vdatax = $(this).select2('val');
            var itid = $(this).attr('id');
            var self = this;
            var vlabel = $(this).closest('.form-group').find('.control-label-filter').html();
            var vdem = $(this).attr('dem');


            if (!$.isArray(vdatax)) {
                if (vdatax == '') {
                    vdatax = -1;
                }
                vdatax = [vdatax];
            }
            var vdata = vdatax[0];

            if (filterNames.length > 0 && filterNames.indexOf(itid) == -1) {
                return true;
            }

            if (vdem == 'A') {
                vHasByGroup = true;
                if (vdatax != "" && vdatax != -1) {
                    vCannotbyGroup = false;
                } else {
                    groupMissing.push(vlabel);
                }
            }

            if (vdem == 'Y' && (vdatax == "" || vdatax == -1)) {
                demandedMissing.push(vlabel);
                vcannot = true;
            }

        });
    });

    return {
        cannotDemanded: vcannot,
        demandedMissing: demandedMissing,
        cannotGroup: (vHasByGroup && vCannotbyGroup),
        groupMissing: groupMissing
    }
}


function setFiltersData(arrayFilter) {
    $.each(arrayFilter, function (i, v) {
        var $vFilter = $('#' + v.idfilter);
        //console.log(v);
        if ($vFilter.length == 0) {
            return true;
        }

        var $vFilterFrame = $('#' + v.idfilter + '_frame');
        var vWithWithout = $vFilter.attr('isWithout');

        switch (v.kind) {
            case 'T':
                $vFilter.attr('likeSearch', v.like);
                $vFilter.val(v.data);
                break;

            case 'PL':
                var vmulti = false;
                if ($vFilter.attr('multiple') == 'multiple') {
                    vmulti = true;
                }


                var vd = {id: v.id, text: v.description, fl_active: v.fl_active};

                select2Data(v.idfilter, vd, vmulti);

                break;

            case 'I':
                $('#' + v.idfilter).autoNumeric('set', v.data);
                break;
                
                
            case 'PLF':
                var vmulti = false;
                if ($vFilter.attr('multiple') == 'multiple') {
                    vmulti = true;
                }

                var voptions = JSON.parse($vFilter.attr('plfixedselect'));

                //

                var vd = [{id: v.iddesc, optId: v.optId}];
                select2DefaultForWithWithout(v.idfilter, voptions, vd, vmulti);


                //select2Data(v.idfilter, vd, vmulti);

                break;


            case 'D':
                $('#' + v.idfilter + "_from").val(v.dataFrom);
                $('#' + v.idfilter + "_to").val(v.dateTo);

                break;

            case 'YN':
                $($vFilter).select2("val", "");

                $vFilter.val(v.data).trigger('change');


                break;

            default:

                break;
        }


    })


}

// funcoes de filtros
// funcoes de filtros
function retFilterInformed(level, filterNames, asJson) {
    if (level == undefined) {
        level = "1";
    }

    if (filterNames == undefined) {
        filterNames = [];
    }

    if (asJson == undefined) {
        asJson = false;
    }


    // comeco a criacao do array!
    // tudo se baseando em ID.... (removendo nome colunas)...
    // como eh o array: 'id' = id, 'oper' = operator (rel se for relacionamento), 'vlr'-valor;


    var retr = [];
    var vDataFilter = [];
    $('.simple_filter_upper').each(function () {

        var vlrc = $(this).val();
        vlrc = vlrc.toUpperCase();
        var itid = $(this).attr('id');
        var likesearch = $(this).attr('likeSearch');
        var likehow = $(this).attr('like');

        if (likehow == 'I') {
            likehow = "ilike";
        } else {
            likehow = "like"
        }

        if (filterNames.length > 0 && filterNames.indexOf(itid) == -1) {
            return true;
        }

        if (vlrc != "" && level == $(this).attr('lvl')) {

            vDataFilter.push({kind: 'T', like: likesearch, data: vlrc, idfilter: itid});

            if (likesearch == 'S') {
                vl = "" + vlrc + "%";
            } else {
                vl = "%" + vlrc + "%";
            }
            // montagem do SQL;
            retr.push(
                {
                    'id': $(this).attr('sqlid'),
                    'oper': likehow,
                    'vlr': vl
                });
        }
    });

    $('.simple_filter_yesno').each(function () {

        var vdata = $(this).select2('data');

        var vlrc = vdata.id;
        var itid = $(this).attr('id');
        var sqlfilter = $(this).attr('sqlid');

        if (filterNames.length > 0 && filterNames.indexOf(itid) == -1) {
            return true;
        }


        if (vlrc != "A" && level == $(this).attr('lvl')) {
            vDataFilter.push({kind: 'YN', data: vlrc, idfilter: itid});


            retr.push(
                {
                    'id': $(this).attr('sqlid'),
                    'oper': 'YESNO',
                    'vlr': vlrc
                });
        }

    });
/*
    $('.numrangefilter').each(function () {

        var itid = $(this).attr('field');
        var vdatafrom = $(this).find('.from').autoNumeric('get');
        var vdatato = $(this).find('.to').autoNumeric('get');

        var sqlfilter = $(this).attr('sqlid');

        if (vdatato == '' || vdatato == undefined) {
            vdatato = vdatafrom;
        }


        if (filterNames.length > 0 && filterNames.indexOf(itid) == -1) {
            return true;
        }

        if (vdatafrom !== "" && vdatafrom !== undefined && level == $(this).attr('lvl')) {

            vDataFilter.push({kind: 'NR', dataFrom: vdatafrom, dateTo: vdatato, idfilter: itid, way: 'A'});


            retr.push(
                {
                    'id': $(this).attr('sqlid'),
                    'oper': 'NR',
                    'from': vdatafrom,
                    'to': vdatato,
                    'vlr': ''

                });
        }

    });
*/
    $('.datefilter').each(function () {

        var itid = $(this).attr('field');
        var vdatafrom = $(this).find('.from').val();
        var vdatato = $(this).find('.to').val();
        var sqlfilter = $(this).attr('sqlid');
        var vdateSearch = $('#' + itid + '_frame').attr('dateSearch')


        if (vdatato == '' || vdatato == undefined) {
            vdatato = vdatafrom;
        }


        if (filterNames.length > 0 && filterNames.indexOf(itid) == -1) {
            return true;
        }

        if (vdatafrom !== "" && vdatafrom !== undefined && level == $(this).attr('lvl')) {

            vDataFilter.push({kind: 'D', dataFrom: vdatafrom, dateTo: vdatato, idfilter: itid, way: vdateSearch});


            retr.push(
                {
                    'id': $(this).attr('sqlid'),
                    'oper': 'DATE',
                    'from': vdatafrom,
                    'to': vdatato,
                    'vlr': ''

                });
        }

    });

    $('.picklist_filter').each(function () {

        var vdatax = chkUndefined($(this).select2('data'), []);

        var itid = $(this).attr('id');
        var self = this;
        var vcontr = $(this).attr('controller');

        if (vcontr != undefined) {
            vcontr = vcontr.split('/');
            vcontr.pop();
            vcontr = vcontr.join('/');
        }

        if (filterNames.length > 0 && filterNames.indexOf(itid) == -1) {
            return true;
        }


        if (!$.isArray(vdatax)) {
            vdatax = [vdatax];
        }
        ;

        var vlrc = vdatax.map(function (elem) {
            return elem.id;
        }).join(",");

        var viddesc = vdatax.map(function (elem) {
            return elem.iddesc;
        }).join(",");

        var voptId = vdatax.map(function (elem) {
            return elem.optId;
        }).join(",");


        if (vlrc == "" || level != $(self).attr('lvl')) {
            return true;
        }

        // montagem do SQL;
        // se tem o fixedselect soh precido do id (que eh a opcao selecionada, e o oper: fixed
        if ($(self).attr('plFixedSelect') != undefined) {
            retr.push(
                {
                    'id': vlrc,
                    'oper': 'FIXED',
                    'vlr': '0'
                });

            vDataFilter.push({kind: 'PLF', idfilter: itid, iddesc: viddesc, optId: voptId});

        } else {

            vDataFilter.push({kind: 'PL', id: vlrc, controller: vcontr, idfilter: itid});

            // sendo Y significa que usa exists no where, entao manda diferente!
            if ($(self).attr('cgbexists') == 'Y') {
                retr.push(
                    {
                        'id': $(self).attr('sqlid'),
                        'oper': 'REL',
                        'vlr': vlrc
                    });
            } else {

                if (vdatax.length > 1) {
                    var voper = 'IN'
                } else {
                    var voper = '='
                }

                retr.push(
                    {
                        'id': $(self).attr('sqlid'),
                        'oper': voper,
                        'vlr': vlrc
                    });
            }
        }
    });

    $('.simple_filter_int').each(function () {
        //var vlrc = parseInt($(this).val().replace(/,/g, ''));
        var vlrc = $(this).autoNumeric('get')
        var itid = $(this).attr('id');
        var sqlfilter = $(this).attr('sqlid');
        if (filterNames.length > 0 && filterNames.indexOf(itid) == -1) {
            return true;
        }

        if (vlrc != ""&& isNaN(vlrc) == false && level == $(this).attr('lvl')) {
            vDataFilter.push({kind: 'I', data: vlrc, idfilter: itid});
            retr.push({
                    'id': sqlfilter,
                    'oper': 'in',
                    'vlr': vlrc
                });
        }
    });

    console.log('dados', vDataFilter, retr);

    if (asJson) {
        return vDataFilter;
    } else {
        return encodeURIComponent(JSON.stringify(retr));
    }
}



function openRepository(option) {

    mainOptions = {
        id: -1,
        code: -1,
        controller: 'docrep/general_document_repository/openRepository',
        columnClass: 'col-md-12',
        title: javaMessages.docrep
    };

    $.extend(mainOptions, option);

    control = mainOptions.controller + '/' + mainOptions.id + '/' + mainOptions.code;

    myOptions = {
        controller: control,
        title: javaMessages.docrep,
        showTitle: true,
        columnClass: 'col-md-10 col-md-offset-1'
    };

    basicPickListOpen(myOptions);

}


function openFormUiBootstrap(title, site, columnClass, options) {

    if (openingModal) {
        return;
    }
    openingModal = true;

    opt = $.extend({showTitle: true, theme: 'white'}, options);

    waitMsgON('body', true, '');

    if (opt.showTitle) {
        titlex = '<div class="modal-header-form_cgb">  ' + title + ' <i class="modal-header-picklist-close_cgb fa fa-times" onclick="$(window).trigger({type: \'onCloseForm\', fromButton: \'Y\' });"> </i></div>';
    } else {
        titlex = '';
    }
    $.ajax({
        url: site,
        dataType: 'html',
        type: 'POST',

        data: {title: title},
        success: function (data) {

            if (checkSessionExpired(data)) {
                waitMsgOFF('body');

                return;
            }


            SBSModalFormsVar = $.dialog({
                content: '  <div style="display: inline-block;width: 100%"> ' + titlex + ' <div class="col-md-12">  ' + data + '</div></div>',
                contentLoaded: function (data, status, xhr) {
                    var self = this;
                },
                columnClass: columnClass,
                containerFluid: true,

                title: false, // hides the title.
                cancelButton: false, // hides the cancel button.
                confirmButton: false, // hides the confirm button.
                closeIcon: false,
                backgroundDismiss: false,
                animation: 'scale',
                theme: opt.theme,
                onOpen: function () {
                    waitMsgOFF('body');
                    openingModal = false;
                },
                onClose: function () {
                    $(window).off("onCloseForm");
                    SBSModalFormsVar = undefined;
                    openingModal = false;
                }
            });

        },
        error: function (jqXHR, textStatus, errorThrown) {
            // Handle it your way
            waitMsgOFF('body');
            openingModal = false;
            if (checkSessionExpired(jqXHR.responseText)) {
                return;
            }

            messageBoxError(jqXHR.responseText);


        }
    });


}

function pickListEnable(target, enabled) {

    if (enabled) {
        $("#" + target).css({'background-color': picklistColor});
        $("#" + target).css({'color': picklistBkColor});
        $("#" + target).attr('PLEnabled', 'Y');
        $("#" + target).css({'cursor': ''});

    } else {
        $("#" + target).css({'background-color': picklistDisColor});

        $("#" + target).css({'color': picklistDisBkColor});
        $("#" + target).attr('PLEnabled', 'N');
        $("#" + target).css({'cursor': 'not-allowed'});


    }
    $("#" + target).prop('disabled', !enabled);


}

// funcoes de picklists
function pickListSet(target, functorun) {
    $("#" + target).css({'background-color': picklistColor});
    $("#" + target).css({'color': picklistBkColor});
    $("#" + target).css({'cursor': 'pointer'});
    $("#" + target).prop('readonly', true);
    $("#" + target).attr('isPL', 'Y');
    $("#" + target).attr('PLEnabled', 'Y');


    $("#" + target).dblclick(function () {
        if ($(this).attr('PLEnabled') == 'N') {
            return;
        }

        functorun(target);
    });

    $("#" + target).bind('keypress', function (e) {
        if (e.keyCode == 13) {
            functorun(target);
        }
    });

}

function basicPickListOpen(options) {

    if (openingModal) {
        return;
    }
    openingModal = true;

    var myOptions = {
        model: 'NONE',
        controller: 'NONE',
        title: 'Title',
        sel_id: -1,
        showTitle: false,
        multiselect: 'N',
        columnClass: 'col-md-8 col-md-offset-2',
        relation: {id: "-1", idwhere: -1},
        startLoaded: 'Y',
        setFilter: 'N', // N -> nao Demanda, Y-Demanda, H->No Description Filter
        plCallBack: function (id, text, data) {
            alert('Missing Callback');
        },
        postParam: {},
        plVarSuffix: '',
        showClose: true,
        containerFluid: false,
        closeFunction: 'none'
    };

    $.extend(myOptions, options);


    var varVariablePL = 'SBSModalVar' + myOptions.plVarSuffix;
    var varCallBack = 'picklistCallBack' + myOptions.plVarSuffix;
    var varPicklistTriggerOpen = 'pickListOpen' + myOptions.plVarSuffix;

    if (myOptions.closeFunction == 'none') {
        var vfunctoClose = varVariablePL + '.close()';
    } else {
        var vfunctoClose = myOptions.closeFunction;
    }

    var titlex = '';
    var titleClose = '';

    if (myOptions.showTitle) {
        if (myOptions.showClose) {
            titleClose = ' <i class="modal-header-picklist-close_cgb fa fa-close" onclick="' + vfunctoClose + '";> </i>';
        }
        titlex = '<div class="modal-header-picklist_cgb"> <span id="modalPicklistTitle' + myOptions.plVarSuffix + '"> ' + myOptions.title + '</span>' + titleClose + '</div>';
    }

    var sitetopen = myOptions.model != 'NONE' ? 'basicpicklist/makePLModal' : myOptions.controller;

    var vcolumnClass = myOptions.columnClass;

    var parms = {
        "id": myOptions.sel_id,
        "relation": JSON.stringify(myOptions.relation),
        'model': myOptions.model,
        'title': myOptions.title,
        'multiselect': myOptions.multiselect
    };

    if (myOptions.postParam.length !== {}) {
        $.extend(parms, myOptions.postParam);
    }

    
    $.myCgbAjax({
        url: sitetopen,
        dataType: 'html',
        data: parms,
        success: function (data) {

            data = '<div style="display: inline-block;width: 100%"> ' + titlex + ' <div class="col-md-12">  ' + data + '</div></div>';

            window[varVariablePL] = $.dialog({
                content: data,
                columnClass: vcolumnClass,
                title: false, // hides the title.
                closeIcon: false,
                centerWidth: true,
                animation: 'scaleY',
                containerFluid: myOptions.containerFluid,
                onOpen: function () {
                    waitMsgOFF('body');
                    openingModal = false;
                    window[varCallBack] = myOptions.plCallBack;

                    $(window).trigger(varPicklistTriggerOpen);

                },
                onClose: function () {
                   $(window).trigger('close.'+varVariablePL);
                }
            });

            // Ahh, success!
        }
        // Handle it your way

    });
}


function basicDateTimeRange(options) {

    if (openingModal) {
        return;
    }
    openingModal = true;

    var myOptions = {
        title: 'Title',
        startDate: '',
        endDate: '',
        columnClass: 'col-md-4 col-md-offset-4',
        plCallBack: function (id, text, data) {
            alert('Missing Callback');
        },
        plVarSuffix: '',
        showClose: true,
        closeFunction: 'none'
    };

    $.extend(myOptions, options);


    var varVariablePL = 'SBSModalVar' + myOptions.plVarSuffix;
    var varCallBack = 'picklistCallBack' + myOptions.plVarSuffix;
    var varPicklistTriggerOpen = 'pickListOpen' + myOptions.plVarSuffix;

    if (myOptions.closeFunction == 'none') {
        var vfunctoClose = varVariablePL + '.close()';
    } else {
        var vfunctoClose = myOptions.closeFunction;
    }

    var titlex = '';
    var titleClose = '';
/*
        if (myOptions.showClose) {
            titleClose = ' <i class="modal-header-picklist-close_cgb fa fa-close" onclick="' + vfunctoClose + '";> </i>';
        }
        titlex = '<div class="modal-header-picklist_cgb"> <span id="modalPicklistTitle' + myOptions.plVarSuffix + '"> ' + myOptions.title + '</span>' + titleClose + '</div>';
*/
    var sitetopen =  'basicpicklist/makePLStartEndDatetime';

    var vcolumnClass = myOptions.columnClass;

    var parms = {
        "startDate": myOptions.startDate,
        "endDate": myOptions.endDate,
        'title': myOptions.title
    };

   


    $.myCgbAjax({
        url: sitetopen,
        dataType: 'html',
        data: parms,
        success: function (data) {

            data = '<div style="display: inline-block;width: 100%"> ' + titlex + ' <div class="col-md-12">  ' + data + '</div></div>';

            window[varVariablePL] = $.dialog({
                content: data,
                boxWidth: '485px',
                useBootstrap: false,
                title: false, // hides the title.
                closeIcon: false,
                centerWidth: true,
                animation: 'scaleY',
                containerFluid: myOptions.containerFluid,
                onOpen: function () {
                    waitMsgOFF('body');
                    openingModal = false;
                    window[varCallBack] = myOptions.plCallBack;

                    $(window).trigger(varPicklistTriggerOpen);

                },
                onClose: function () {
                }
            });

            // Ahh, success!
        }
        // Handle it your way

    });
}


function basicPickListOpenPopOver(options) {

    if (openingModal) {
        return;
    }

    openingModal = true;

    var myOptions = {
        model: 'NONE',
        controller: 'NONE',
        title: 'Title',
        sel_id: -1,
        showTitle: true,
        multiselect: 'N',
        relation: {id: "-1", idwhere: -1},
        mini: false,
        startLoaded: 'Y',
        setFilter: 'N', // N -> nao Demanda, Y-Demanda, H->No Description Filter
        plCallBack: function (id, text, data) {
            alert('Missing Callback');
        },
        postParam: {},
        plVarSuffix: '',
        showClose: true,
        containerFluid: false,
        closeFunction: 'none',
        target: 'none',
        width: 'auto',
        html: '',
        position: 'auto',
        zIndex: 2000,
        titleBackgroundColor: '#3c8dbc',
        animation: undefined,
        titleButtons: 'Teste',
        lockArea: '.wrapper',
        functionOpen: function () {
        }
    };

    $.extend(myOptions, options);

    var varVariablePL = 'SBSModalVarPopup' + myOptions.plVarSuffix;
    var varCallBack = 'picklistCallBackPopup' + myOptions.plVarSuffix;
    var varPicklistTriggerOpen = 'pickListPopup' + myOptions.plVarSuffix;

    if (myOptions.closeFunction == 'none') {
        var vfunctoClose = varVariablePL + '.close()';
    } else {
        var vfunctoClose = myOptions.closeFunction;
    }

    var titlex = '';
    var titleClose = '';

    if (myOptions.showTitle) {
        if (myOptions.showClose) {
            titleClose = ' <i class="modal-header-picklist-close_cgb fa fa-close" style="padding-right:0px;"onclick="' + vfunctoClose + '";> </i>';
        }
        titlex = '<div class="modal-header-picklist_cgb"> <span id="modalPicklistTitle' + myOptions.plVarSuffix + '"> ' + myOptions.title + '</span>' + titleClose + '</div>';
    }


    /*var vhtml = '<div class="popover popover-primary" id="modalID' + myOptions.plVarSuffix + '" style="z-index: 100000;width:' + myOptions.width + '" >\n\
     <div class="arrow"></div>\n\
     <div class="arrow"></div><h3 class="popover-title">' + myOptions.title + titleClose + '</h3>\n\
     <div class="popover-content" >';
     */
    var vhtml = '<div class="" id="popover_my_content_' + varVariablePL + '"  ">';


    var sitetopen = myOptions.model != 'NONE' ? 'basicpicklist/makePLModal' : myOptions.controller;

    var vcolumnClass = myOptions.columnClass;

    var parms = {
        "id": myOptions.sel_id,
        "relation": JSON.stringify(myOptions.relation),
        'model': myOptions.model,
        'title': myOptions.title,
        'multiselect': myOptions.multiselect
    };

    if (myOptions.postParam.length !== {}) {
        $.extend(parms, myOptions.postParam);
    }

    window[varVariablePL] = new function () {

        this.beforeClose = function () {
            return true;
        };

        this.target = undefined;

        this.close = function () {

            if (!this.beforeClose()) {
                return;
            }


            $(this.target).webuiPopover('hide');
            $(this.target).webuiPopover('destroy');

            $(window).off('resizeEnd.' + myOptions.plVarSuffix);


            window[varVariablePL] = undefined;

        }

        this.getPopoverElement = function () {
            return $('#popover_my_content_' + varVariablePL).closest('.webui-popover');
        }

        this.adjustPosition = function () {

            $(myOptions.target).webuiPopover('displayContent');

            var $popup = $('#popover_my_content_' + varVariablePL).closest('.webui-popover');
            $popup.css('z-index', myOptions.zIndex);
            $popup.find('.webui-popover-title').css('background-color', myOptions.titleBackgroundColor);
            var $close = $popup.find('.close');
            //$(myOptions.titleButtons).insertBefore($close)

            if (myOptions.mini) {
                $close.addClass('close-mini');
                $popup.find('.webui-popover-title').addClass('webui-popover-title-mini');
            }

            $close.off('click').on('click', function () {

                window[varVariablePL].close();
            });

        }
    }

    if (myOptions.html != '') {
        var data = vhtml + myOptions.html + '</div>';//</div>
        //$('body').append(data);

        _basicPickListOpenPopOver(data, myOptions, varCallBack, varPicklistTriggerOpen, varVariablePL);
        return;
    }

    $.myCgbAjax({
        url: sitetopen,
        dataType: 'html',
        data: parms,
        success: function (data) {
            waitMsgOFF('body');

            data = vhtml + data + '</div>';//</div>';
            _basicPickListOpenPopOver(data, myOptions, varCallBack, varPicklistTriggerOpen, varVariablePL);


        }
        // Handle it your way


    });
}


function _basicPickListOpenPopOver(vhtml, myOptionsH, varCallBack, varPicklistTriggerOpen, varVariablePL) {


    window[varCallBack] = myOptionsH.plCallBack;

    $(myOptionsH.target).webuiPopover({
        trigger: 'manual', content: vhtml,
        type: 'html', title: myOptionsH.title, closeable: true, multi: true, cache: false, width: myOptionsH.width,
        animation: myOptionsH.animation,
        placement: myOptionsH.position,
        onShow: function () {
            $(window).trigger(varPicklistTriggerOpen);
            myOptionsH.functionOpen(this);
            $(window).on('resizeEnd.' + myOptionsH.plVarSuffix, function () {
                window[varVariablePL].adjustPosition();
            });

            var $popup = $('#popover_my_content_' + varVariablePL).closest('.webui-popover');
            $popup.css('z-index', myOptionsH.zIndex);
            $popup.find('.webui-popover-title').css('background-color', myOptionsH.titleBackgroundColor);
            $(myOptionsH.lockArea).LoadingOverlay("show", {
                zIndex: myOptionsH.zIndex - 1,
                color: "rgba(200, 200, 200, 0.5)"
            });
            var $close = $popup.find('.close');

            if (myOptionsH.mini) {
                $close.addClass('close-mini');
                $popup.find('.webui-popover-title').addClass('webui-popover-title-mini');

            }
            //$(myOptions.titleButtons).insertBefore($close)

            $close.off('click').on('click', function () {
                window[varVariablePL].close();
            });

            openingModal = false;


        },
        onHide: function () {
            $(myOptionsH.lockArea).LoadingOverlay("hide");
        }

    });

    $(myOptionsH.target).webuiPopover('show');

    window[varVariablePL].target = $(myOptionsH.target);

}


function basicTextPLOpen(options) {

    if (openingModal) {
        return;
    }

    openingModal = true;

    var myOptions = {
        controller: 'basictextpl/makeTextPLModal',
        title: 'Title',
        text: '',
        uppercase: false,
        plCallBack: function (saved, text) {
            alert('Missing Callback');
        },
        plLevel: 0,
        readonly: false
    };

    $.extend(myOptions, options);


    $.myCgbAjax({
        url: myOptions.controller,
        dataType: 'html',
        data: {"text": myOptions.text, "title": myOptions.title, "uppercase": myOptions.uppercase, "readonly": myOptions.readonly},
        success: function (data) {
            //console.log('chamou' + sitetopen);
            data = '<div style="display: inline-block;width: 100%"> ' + ' <div class="col-md-12">  ' + data + '</div></div>';


            SBSModalVar = $.dialog({
                content: data,
                columnClass: 'col-md-6 col-md-offset-3',
                title: false, // hides the title.
                closeIcon: false,
                centerWidth: true,
                animation: 'scaleY',
                onOpen: function () {
                    openingModal = false;
                    picklistCallBack = myOptions.plCallBack;

                    if (typeof formPLOpened === 'function') {
                        formPLOpened();
                    }


                },
                onClose: function () {
                }
            });

            // Ahh, success!
        }
        // Handle it your way

    });


    //hideScrollBeforeModal();


}


function basicSelectSBS(title, model_and_function_retrieve, model_and_function_upds, id) {
    // quando nao tem controller, o funcORModel eh o model que tem o plselect
    site = "basicselectsbs/makeSBSmodal";
    SBSUpdModel = "basicselectsbs/updSBSmodal";

    //hideScrollBeforeModal();


    waitMsgON('body', true, '');


    $.ajax({
        url: site,
        dataType: 'html',
        type: 'POST',
        data: {
            "modelRet": model_and_function_retrieve,
            "modelUpd": model_and_function_upds,
            "id": id,
            "title": title
        },
        success: function (data) {
            if (checkSessionExpired(data)) {
                waitMsgOFF('body')

                return;
            }


            data = '<div style="margin-bottom: 0px !important;">' + data + "</div>";

            SBSModalVar = $.dialog({
                content: data,
                columnClass: 'col-md-10 col-md-offset-1',
                title: false, // hides the title.
                closeIcon: false,
                onOpen: function () {
                    waitMsgOFF('body')

                },
                onClose: function () {

                    if (typeof posSBSClosed == 'function') {
                        posSBSClosed(SBSHasChanges, varSBSLastSaved);
                        SBSHasChanges = false;
                        varSBSLastSaved = {};

                    }
                }
            });
            // Ahh, success!
        },
        error: function (jqXHR, textStatus, errorThrown) {
            // Handle it your way
            waitMsgOFF('body');

            if (checkSessionExpired(jqXHR.responseText)) {
                return;
            }

            messageBoxError(textStatus + '<br>' + errorThrown + '<br>' + jqXHR.responseText);


        }
    });
}


function getSBSUpdModel() {
    return SBSUpdModel;
}

// final das funcoes de picklists

// functions de toolbar
function toolbarAddIns(toolbarobj) {
    toolbarobj.add({id: "insert", hint: javaMessages.ins_line, icon: "fa fa-plus", caption: "", type: "button"});
}

function toolbarAddUpd(toolbarobj) {
    toolbarobj.add({id: "update", hint: javaMessages.update, icon: "fa fa-floppy-o", caption: "", type: "button"});
}

function toolbarAddDel(toolbarobj) {
    toolbarobj.add({id: "delete", hint: javaMessages.deleteMsg, icon: "fa fa-trash-o", caption: "", type: "button"});
}

function toolbarAddClose(toolbarobj) {
    toolbarobj.add({id: "close", hint: javaMessages.close_screen, icon: "fa fa-times", caption: "", type: "button"});
}

function toolbarAddEdit(toolbarobj) {
    toolbarobj.add({id: "edit", hint: javaMessages.edit_line, icon: "fa fa-edit", caption: "", type: "button"});
}

// functions de toolbar
function toolbarAddRetrieve(toolbarobj) {
    toolbarobj.add({
        id: "retrieve",
        hint: javaMessages.retrieveInfo,
        icon: "fa fa-refresh",
        caption: "",
        type: "button"
    });
}


function toolbarAddSpacer(toolbarobj) {
    toolbarobj.add({id: "spacer", hint: "", icon: "", caption: "", type: "spacer"});
}

function basicPreview(opt) {

    var defOpt = {tpfile: -1, sendParam: {}, sql: ''};
    var voptions = $.extend({}, defOpt, opt);


    waitMsgON('#content-body');

    $.post('repgenerator/askReportAuth/' + voptions.id + '/' + voptions.tpfile,
            {
                sql: voptions.sql,
                sendParam: JSON.stringify(voptions.sendParam)
            },
            function (data) {
                waitMsgOFF('#content-body');
                var w = window.open('repgenerator/getReport/' + data.id + '/' + data.auth, '_blank');
            },
            'json'
            );

}

function reportPreviewWithParms(controllerAndFunc, id, jsonTosend, tpfile) {
    if (tpfile == undefined) {
        tpfile = -1;
    }

    waitMsgON('#content-body');


    $.post(controllerAndFunc,
            {
                id: id,
                tpfile: tpfile,
                jsonToSend: JSON.stringify(jsonTosend)
            },
            function (data) {
                waitMsgOFF('#content-body');

                var w = window.open('repgenerator/getReport/' + data.id + '/' + data.auth, '_blank');
            },
            'json'
            );

}


// Arguments :
//  verb : 'GET'|'POST'
//  target : an optional opening target (a name, or "_blank"), defaults to "_self"
openAlternate = function (verb, url, data, target) {


    var form = document.createElement("form");

    form.action = url;
    form.method = verb;
    form.target = target || "_self";
    if (data) {
        for (var key in data) {
            var input = document.createElement("textarea");
            input.name = key;
            input.value = typeof data[key] === "object" ? JSON.stringify(data[key]) : data[key];
            form.appendChild(input);
        }
    }
    form.style.display = 'none';
    document.body.appendChild(form);
    form.submit();
    //form.destroy();
};


$.fn.cgbFileUpload = function (opt) {
    var defOption = {
        mainDiv: "#content-body",
        controller: '',
        autoHide: true,
        cleanOnCancel: false,
        beforeUpdate: function () {
            return true;
        },
        afterUpdate: function () {
            return true;
        },
        afterFilesChanges: function (id, files, data) {
            return true;
        },
        beforeFilesChanges: function () {
            return true;
        },
        sizeByExtension: [],
        loadData: false
    };

    var options = $.extend({}, defOption, opt);
    var $selector = $(this);
    var toForm = new FormData();
    var filesToSend = [];
    var filesToSendData = [];
    var vaddInfo = [];
    var dataChanged = {};

    create();

    function resetAllFiles() {
        $selector.each(function (sel) {
            filesToSend[this.id] = [];
            filesToSendData[this.id] = [];
            dataChanged[this.id] = 'N';
        });
    }

    function resetFiles(sel) {
        filesToSend[sel] = [];
        filesToSendData[sel] = [];
        dataChanged[sel] = 'N';

    }

    // cria tudo!
    function create() {
        resetAllFiles();
        $selector.off('change');

        paret = this;

        // parte onde acontece a carga dos dados do que foi selecionado!
        $selector.change(onDoubleClick);

        if (options.autoHide) {
            $selector.hide();
        }

    }
    ;

    function addSelector(selcId) {
        var $sel = $('#' + selcId);
        $selector = $(this);

        $sel.off('change');
        // parte onde acontece a carga dos dados do que foi selecionado!
        $sel.change(onDoubleClick);

        if (options.autoHide) {
            $sel.hide();
        }
    }

    function onDoubleClick(data) {
        var idBtn = this.id;
        var thisImg = this;

        if (!options.beforeFilesChanges(idBtn, this.files)) {
            return;
        }

        // quando se pressiona CANCEL ou ESC, ele retorna zero. Entao faco o controle para zerar...
        if (options.cleanOnCancel && data.files.length == 0) {
            options.afterFilesChanges(this.id, null, null);

            resetFiles(this.id);
        } else {

            var vext = this.files[0].name.substring(this.files[0].name.lastIndexOf('.') + 1).toLowerCase();
            vext = vext.toLowerCase();
            if (options.sizeByExtension[vext] != undefined) {
                if (options.sizeByExtension[vext] < this.files[0].size) {
                    var vx = javaMessages.errorSize.replace('%1', this.files[0].name).replace('%2', options.sizeByExtension[vext]).replace('%3', this.files[0].size);

                    messageBoxError(vx)
                    options.afterFilesChanges(null, null, null);

                    return;
                }
            }

            filesToSend[this.id] = this.files;
            dataChanged[this.id] = 'Y';


            if (options.loadData) {

                var reader = new FileReader();

                reader.onload = function (e) {
                    filesToSendData[idBtn] = e.target.result;
                    //options.afterFilesChanges(idBtn, filesToSend[idBtn], filesToSendData[idBtn]);

                    var url = (window.URL || window.webkitURL).createObjectURL(thisImg.files[0]);
                    options.afterFilesChanges(idBtn, filesToSend[idBtn], url);


                };

                reader.readAsDataURL(this.files[0]);


            } else {
                options.afterFilesChanges(this.id, this.files, null);
            }
        }
    }


    function loadUrlBase64(idBtn, basedata, name, type) {
        dataChanged[idBtn] = 'Y';
        filesToSendData[idBtn] = basedata;

        values = [{"name": name, "type": type, "size": 100}];
        filesToSend[idBtn] = values;

    }

    function setFormSendData(idBtn, data) {
        $('#' + idBtn).attr('data-form-send', JSON.stringify(data));
    }

    function getFormSendData(idBtn) {
        return JSON.parse($('#' + idBtn).attr('data-form-send'));
    }

    function openDialog(selector) {
        $('#' + selector).click();
    }

    function retDataUploadJson() {
        var toSend = [];
        var i = 0;

        $.each($selector, function () {
            var thisid = this.id;
            $.each(filesToSend[thisid], function (key, value) {
                var additionalData = $('#' + thisid).attr('data-form-send');
                var filesInfo = {};

                if (additionalData !== undefined) {
                    additionalData = JSON.parse(additionalData);
                }

                if (options.loadData) {
                    filesInfo['base64data'] = filesToSendData[thisid];
                } else {
                    filesInfo['base64data'] = null;
                }


                filesInfo['name'] = this.name;
                filesInfo['type'] = this.type;
                filesInfo['size'] = this.size;

                toSend.push({fileInfo: filesInfo, additionalData: additionalData});
                i++;
            });
        });

        return toSend;
    }

    function send() {
        if (options.controller == '') {
            alert('set controller');
        }

        // faz uma varredura em todos os objetos que foram alterados... e envia!
        var i = 0;
        var dataSend = [];
        var filesSend = [];

        $.each($selector, function () {

            $.each(filesToSend[this.id], function (key, value) {
                toForm.append(i, value);
                filesSend.push(value);
                i++;
            });

        });

        // adiciono informacoes adicionais que serao enviados ao controller.!
        $.each(vaddInfo, function (key, value) {
            toForm.append(key, value);
            dataSend[key] = value;
        });

        if (options.beforeUpdate(filesSend, dataSend) === false) {
            return;
        }

        makeProgressBar($(options.mainDiv), 'Uploading', true);

        $.ajax({
            async: true,
            url: options.controller,
            type: 'POST',
            data: toForm,
            dataType: 'json',
            processData: false, // Don't process the files
            contentType: false, // Set content type to false as jQuery will tell the server its a query string request
            xhr: function () {
                var xhr = $.ajaxSettings.xhr();

                xhr.upload.onprogress = function (progress) {
                    // calculate upload progress
                    if (progress.lengthComputable) {
                        var percentage = Math.floor((progress.loaded / progress.total) * 90);

                        setProgressValue(percentage);
                    } else {
                        setProgressValue(false);
                    }

                }

                return xhr;

            },
            success: function (data, textStatus, jqXHR) {
                removeProgressBar(options.mainDiv);
                options.afterUpdate(data);

            },
            error: function (jqXHR, textStatus, errorThrown) {
                removeProgressBar(options.mainDiv);
                toastErrorBig(errorThrown + textStatus);

            }
        });
    }

    function addInfo(obj) {
        vaddInfo = obj;
    }

    function resetUpdate() {
        toForm = new FormData();
        vaddInfo = [];
        resetAllFiles();
    }

    function isChanged() {
        var ret = false;

        $.each(dataChanged, function (index, value) {

            if (value == 'Y') {
                ret = true;
            }

        });

        return ret;

    }


    this.send = send;
    this.addInfo = addInfo;
    this.openDialog = openDialog;
    this.resetAllFiles = resetAllFiles;
    this.resetFiles = resetFiles;
    this.retDataUploadJson = retDataUploadJson;
    this.isChanged = isChanged;
    this.loadUrlBase64 = loadUrlBase64;
    this.setFormSendData = setFormSendData;
    this.getFormSendData = getFormSendData;
    this.create = create;
    this.addSelector = addSelector;

    return this;
}

function makeProgressBar(div, message, showSpinner) {

    w2utils.lock(div, message, showSpinner);
    $('.w2ui-lock-msg').append('<br> <br> <div id="cgbProgressBarDiv"> </div>');
    $('.w2ui-lock-msg').height(50);

    $('#cgbProgressBarDiv').progressbar();
    $('#cgbProgressBarDiv').progressbar('option', 'value', 0);
    $('#cgbProgressBarDiv').progressbar('option', 'max', 100);

    progressbar = $("#cgbProgressBarDiv"),
            progressbarValue = progressbar.find(".ui-progressbar-value");
    progressbarValue.css({"background": 'yellow'});

}

function openMaintenanceScreen(url) {
    basicPickListOpen(
            {
                controller: 'basicpicklist/basicMaintenance',
                columnClass: 'col-md-12 col-md-offset-0',
                //sel_id: options.sel_id,
                plVarSuffix: 'maintXXX',
                showTitle: true,
                title: 'temporary',
                plCallBack: function () {
                },
                postParam: {url: url}
            }
    );
}


function setProgressValue(value) {
    $('#cgbProgressBarDiv').progressbar('option', 'value', value);
}

function removeProgressBar(div) {
    w2utils.unlock(div);
}

function productPL(options) {

    mainOptions = {
        cd_product_group: -1,
        cd_product_component: -1,
        sel_id: -1,
        mustbeAuthorized: 'Y',
        onlyPure: 'A',
        plCallBack: function (id, text, data) {
            alert('Missing Callback');
        }
    };

    $.extend(mainOptions, options);

    basicPickListOpen(
            {
                controller: 'material/product/plChooseProductMain/' + options.sel_id,
                columnClass: 'col-md-10 col-md-offset-1',
                sel_id: options.sel_id,
                showTitle: true,
                title: 'temporary',
                plCallBack: options.plCallBack,
                postParam: {
                    "prdgroup": mainOptions.cd_product_group,
                    prdcomp: mainOptions.cd_product_component,
                    'auth': mainOptions.mustbeAuthorized,
                    onlyPure: mainOptions.onlyPure
                }

            }
    );
}

function changeFilterWidth(id, width) {
    $(id).width(width);
    $(id + '_frame').width(width);
}

function hideFilterFrame(id) {
    $('#' + id + '_frame').hide();
}

function showFilterFrame(id) {
    $('#' + id + '_frame').show();
}


function getSelectedTab(tabDiv) {
    if (tabDiv === undefined) {
        tabDiv = '#myGridTab';
    }

    var retId;

    $.each($(tabDiv).find('li'), function () {
        if ($(this).hasClass('active')) {
            retId = $(this).attr('id');
        }
    });

    return retId;

}

function settingsChanged(object) {
    $('#settingsButton').prop('disabled', false);

    $(object).attr('changed', 'true');
}

function settingsUpdate() {
    /*
     array_push($upd_array, array('cd_system_settings_options' => $row->cd_system_settings_options,
     'cd_system_settings' => $row->recid));
     }
     */

    var send = [];

    $('.mbSettingsDropdown').each(function () {
        if ($(this).attr('changed') === undefined) {
            return true;
        }

        send.push({cd_system_settings: $(this).attr('codess'), cd_system_settings_options: $(this).val()});
    });

    $('.mbSettingsCheckBox').each(function () {
        if ($(this).attr('changed') === undefined) {
            return true;
        }

        var opts = JSON.parse($(this).attr('codes'));
        if ($(this).is(':checked')) {
            opt = opts['Y'];
        } else {
            opt = opts['N'];
        }

        send.push({cd_system_settings: $(this).attr('codess'), cd_system_settings_options: opt});
    });

    //w2ui['settingsGrid'].lock(javaMessages.updating, true);

    if (send.length == 0) {
        return;
    }

    $.post(
            "settings/updateDataJson",
            {"upd": JSON.stringify(send)},
            function (data) {
                if (data == "OK") {
                    toastSuccess(javaMessages.update_done);
                    $('#settingsButton').prop('disabled', true);

                    sideBarClose();


                } else {
                    toastErrorBig(javaMessages.error_upd + data);
                }
            },
            "text"
            );


}

function sideBarOpen() {
    $.AdminLTE.controlSidebar.close($($.AdminLTE.options.controlSidebarOptions.selector), $.AdminLTE.options.controlSidebarOptions.slide);
}

function sideBarClose() {
    $.AdminLTE.controlSidebar.close($($.AdminLTE.options.controlSidebarOptions.selector), $.AdminLTE.options.controlSidebarOptions.slide);

}

function sideBarIsOpened() {
    sidebar = $($.AdminLTE.options.controlSidebarOptions.selector);
    return not(!sidebar.hasClass('control-sidebar-open')
            && !$('body').hasClass('control-sidebar-open'));
}

function systemMenuOpen() {
    //$.AdminLTE.pushMenu.expand();
}

function systemMenuClose() {
    //$.AdminLTE.pushMenu.collapse();

}

function docRepShowImage(title, docrep) {

    basicPickListOpen({
        controller: 'docrep/general_document_repository/openImageViewer/' + docrep,
        title: title,
        //columnClass: 'md-col-1',
        showTitle: true,
        plVarSuffix: 'docrep'
    });

}

function openImageIdSrc(id, title) {
    var vd = $('#'+id).attr('src');  
    var vheight = Math.round(($(window).height() * 0.80));
    var vhtml = '<div style="height: '+vheight+'px"> <img src="'+vd+'" class="img-responsive"style="max-height: '+vheight+'px;margin: 0 auto"></div>';    
    $.dialog({
        title: false,
        content: vhtml,
        columnClass: 'col-md-12 messageBoxCGBClass',
        theme: 'supervan',
        backgroundDismiss: true,
        onOpenBefore: function () {
            this.$el.css('z-index', '1000000105');       },

        buttons: false
    });    
}

function hideScrollBeforeModal() {
    var is_chrome = navigator.userAgent.toLowerCase().indexOf('chrome') > -1;
    if (!is_chrome) {
        return;
    }
    for (var o in w2ui) {
        if (w2ui[o].parseField !== undefined) {
            var rec = $('#grid_' + w2ui[o].name + '_records');
            rec.addClass('cgbHidescrolls');

        }
    }

    //

}



/**
 * jQuery.browser.mobile (http://detectmobilebrowser.com/)
 *
 * jQuery.browser.mobile will be true if the browser is a mobile device
 *
 **/
(function (a) {
    (jQuery.browser = jQuery.browser || {}).mobile = /(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0, 4))
})(navigator.userAgent || navigator.vendor || window.opera);


// Funcoes de Tab
jQuery.fn.ctabStart = function (options) {
    var defOptions = {
        beforeChange: function () {
            return true;
        },
        afterChanged: function () {
        },
        selected: undefined
    };

    var mainOptions = {};
    var thisID = '#' + $(this).attr('id');
    var selectedView = '';
    var originalMenuFunction = function () {
    };

    mainOptions = $.extend({}, defOptions, options);


    $(thisID + ' .nav-tabs li a').each(function () {
        var vr = $(this).attr('relatedTab');
        if (vr == undefined) {
            $(this).attr('relatedTab', $(this).parent().attr('id'));
        }

        $(this).attr('href', '#').attr('onclick', 'return false;').attr('data-toggle', 'return false;');

        $(this).click(function (e) {

            var relatedtab = $(this).attr('relatedTab');

            var x = $(thisID).ctabGetSelected();
            if (x == relatedtab) {
                return;
            }


            $(thisID).ctabSelect(relatedtab, {
                beforeChange: mainOptions.beforeChange,
                afterChanged: mainOptions.afterChanged
            });
        });


        if (mainOptions.selected !== undefined) {
            $(thisID).ctabSelect(mainOptions.selected, {
                beforeChange: mainOptions.beforeChange,
                afterChanged: mainOptions.afterChanged
            });
        }


    });
}

// link de grid com os thumbs
jQuery.fn.ctabSelect = function (idTab, options) {

    if (options == undefined) {
        options = {};
    }

    var defOptions = {
        beforeChange: function () {
            return true;
        },
        afterChanged: function () {
        }
    };

    var mainOptions = {};
    var thisID = '#' + $(this).attr('id');
    ;
    var selectedView = '';
    var originalMenuFunction = function () {
    };

    mainOptions = $.extend({}, defOptions, options);

    var oldTab = $(thisID).ctabGetSelected();

    if (!mainOptions.beforeChange(idTab, oldTab)) {
        return;
    }

    $(thisID + ' > ul > li').removeClass('active');
    $('#' + idTab).addClass('active');

    $(thisID + ' > .tab-content > .tab-pane').removeClass('active');
    $('#' + idTab + '_pane').addClass('active');


    var div = $('#' + idTab).attr('divid');

    mainOptions.afterChanged(idTab, oldTab);

};

jQuery.fn.ctabGetSelected = function () {
    var thisID = '#' + $(this).attr('id');
    ;
    return $(thisID + ' .cgb-nav-tabs').children('li.active').attr('id');
}

jQuery.fn.ctabList = function () {
    var thisID = '#' + $(this).attr('id');

    var vlist = [];
    $(thisID + ' .cgb-nav-tabs').children('li').each(function () {
        vlist.push($(thisID).ctabInfo($(this).attr('id')));
    });

    return vlist;
}

jQuery.fn.ctabInfo = function (idTab) {
    var info = {};
    info['name'] = idTab;
    info['text'] = $('#' + idTab + ' a').html();
    info['pane'] = idTab + '_pane';
    info['div'] = $('#' + idTab).attr('divid');

    return info;
};


jQuery.myCgbAjax = function (options) {

    var optDefaults = {
        url: 'none',
        message: javaMessages.updating,
        box: 'auto',
        data: [],
        dataType: "json",
        async: true,
        success: function () {
        },
        timeout: undefined,
        error: undefined,
        systemRequest: false,
        errorAfter: undefined
    };

    var opt = $.extend({}, optDefaults, options);
    var toBlock = '';

    if (opt.box !== 'none') {

        if (opt.box === 'auto') {

            toBlock = 'body';


        } else {
            toBlock = opt.box;
        }
        waitMsgON(toBlock, true, opt.message);

    }

    $.ajax({
        url: opt.url,
        dataType: opt.dataType,
        type: 'POST',
        data: opt.data,
        async: opt.async,
        beforeSend: function (jqXHR) {
            if (!opt.systemRequest) {
                myCgbAjaxXhrPool.push(jqXHR);
            }
        },
        complete: function (jqXHR) {
            if (!opt.systemRequest) {

                var vindex = myCgbAjaxXhrPool.indexOf(jqXHR);
                if (vindex > -1) {
                    myCgbAjaxXhrPool.splice(vindex, 1);
                }
            }
        },
        success: function (data) {
            myCgbAjaxXhr = undefined;

            if (opt.dataType == 'html' || opt.dataType == 'text' || opt.dataType == 'script') {
                if (checkSessionExpired(data)) {
                    waitMsgOFF(toBlock);
                    return;
                }
            }

            if (opt.dataType === 'json' && data.logged !== undefined) {
                if (data.logged === 'N') {

                    if (opt.box !== 'none') {
                        waitMsgOFF(toBlock);
                    }
                    if (opt.timeout !== undefined) {
                        opt.timeout(data);
                    } else {
                        if (checkSessionExpired(jqXHR.responseText)) {
                            return;
                        }
                    }
                }
            }

            opt.success(data);
            if (opt.box !== 'none') {
                waitMsgOFF(toBlock);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR, textStatus, errorThrown);

            // Handle it your way
            if (opt.box !== 'none') {

                if (opt.box !== 'none') {
                    waitMsgOFF(toBlock);
                }
            }

            if (jqXHR.statusText === 'abort') {
                return;
            }

            if (checkSessionExpired(jqXHR.responseText)) {
                return;
            }

            if (opt.error !== undefined) {
                opt.error(jqXHR, textStatus, errorThrown);
            } else {
                messageBoxError(jqXHR.responseText);
                console.log(jqXHR, textStatus, errorThrown);
            }
            
            if (opt.errorAfter != undefined) {
                opt.errorAfter(jqXHR, textStatus, errorThrown);
            }

        }
    });


}

function checkSessionExpired(error) {

    if (isSessionExpired) {
        return;
    }

    if (error == undefined) {
        return false;
    }

    if (error.indexOf('Err: 1602') > 0) {
        isSessionExpired = true;

        if (typeof dsMainObjectDash == 'object') {
            clearInterval(dsMainObjectDash.dashInterval);
        }

        messageBoxError(error, function () {


            window.location = "login";
        });
        return true;

    }

    return false;
}

function sessionTimeOut() {
    checkSessionExpired(' Err: 1602');
}


jQuery.fn.cgbMakeScrollbar = function (option, option2, option3) {
    if (option == undefined) {
        option = {};
    }

    var defoption = {
        scrollButtons: {
            enable: true
        },
        axis: 'y',
        mouseWheel: {
            enable: true,
            scrollAmount: 40
        },
        theme: "rounded-dots",
        alwaysShowScrollbar: 0,
        autoWrapContent: true,
        scrollInertia: 0,
        callbacks: {
            onScroll: function () {
            }
        }
    };


    if (typeof option == 'object') {
        var vopt = $.extend({}, defoption, option);


        if (vopt.autoWrapContent) {
            var vselector = '#' + $(this).attr('id') + 'Scroll';
            var vid = vselector.substring(1);

            var $vselect = $(this);

            $vselect.css('overflow-x', 'hidden').css('overflow-y', 'hidden').css('padding-right', '14px').css('height', 'auto');
            ;
            var vCssAddon = '';

            $vselect.wrap('<div id="' + vid + '" style="display: block;overflow-y: none; overflow-x: none;"></div>');
            var vselbk = $vselect.css('background-color');
            if (vselbk != undefined) {
                $(vselector).css('background-color', vselbk);
            }
        } else {
            var vselector = '#' + $(this).attr('id');
        }
        $(this).attr('divInUse', vselector);

        vopt.callbacks.onScroll = function () {
            cgbScrollInformation[vselector] = this.mcs.top;
        }

        $(vselector).mCustomScrollbar(vopt);
        if (vopt.maxHeight != undefined) {
            $(vselector).css('max-height', vopt.maxHeight);
        }


    } else {

        var vselector = $(this).attr('divInUse');

        switch (option) {
            case 'update':
                $(vselector).mCustomScrollbar('update');
                break;
            case 'destroy':
                $(vselector).mCustomScrollbar('destroy');
                break;
            case 'resize':
                $(vselector).height(option2).mCustomScrollbar('update');
                break;

            case 'scrollToY':
                if (option3 == undefined) {
                    option3 = function () {
                    };
                }
                $(vselector).mCustomScrollbar('scrollTo', option2, {scrollInertia: 0});
                break;

            case 'max-height':
                $(vselector).css('max-height', option2);
                $(vselector).mCustomScrollbar('update');

                break;


            case 'getScrollPositionY':
                return cgbScrollInformation[vselector];
                break;


            default:

                break;
        }

    }
}


function showDays(firstDate, secondDate) {

    var startDay = new Date(firstDate);
    var endDay = new Date(secondDate);
    var millisecondsPerDay = 1000 * 60 * 60 * 24;

    var millisBetween = startDay.getTime() - endDay.getTime();
    var days = Math.floor(millisBetween / millisecondsPerDay);

    return days;

    // Round down.
    //alert(Math.floor(days));

}

function makeJoinForIN(item) {
    var join = item.join('<COMMA>');

    return join.toString();
}


function calcPopOverMaxHeight(target, actualWidth, maxHeight) {
    actualWidth = actualWidth + 50;

    var voffset = $(target).offset();
    var vMainHeight = $(window).height();
    var vMainWidth = $(window).width();

    if (maxHeight == undefined) {
        maxHeight = vMainHeight * 0.8;
    }

    /*
     if (vMainWidth - voffset.left > actualWidth || voffset.left - 50 > actualWidth ) {
     return maxHeight;
     }
     */

    var vbottomh = vMainHeight - voffset.top;
    var vtoph = voffset.top;

    if (vbottomh > vtoph) {
        //console.log('decided pelo bottom maior que top', vbottomh - 50);

        return Math.round(vbottomh - 100, 0);
    } else {
        //console.log('decided pelo top maior que bottom', vtoph - 50);

        return Math.round(vtoph - 100, 0);
    }


}

function isChecked(selector) {
    return $(selector).is(':checked');
}

function isCheckedYN(selector) {
    if (isChecked(selector)) {
        return 'Y';
    } else {
        return 'N';
    }
}

//multinestedlists:
$.fn.cgbMultiNestedList = function () {
    //console.log('dentro', $selector);
    var $selector = $(this).not('loaded');
    // Select the main list and add the class "hasSubmenu" in each LI that contains an UL
    $selector.find('ul.multi-nested-list').each(function () {
        $this = $(this);
        $this.find("li").has("ul").addClass("hasSubmenu");
    });
    // Find the last li in each level
    $selector.find('ul.multi-nested-list li:last-child').each(function () {
        $this = $(this);
        // Check if LI has children
        if ($this.children('ul').length === 0) {
            // Add border-left in every UL where the last LI has not children
            $this.closest('ul').css("border-left", "1px solid gray");
        } else {
            // Add border in child LI, except in the last one
            $this.closest('ul').children("li").not(":last").css("border-left", "1px solid gray");
            // Add the class "addBorderBefore" to create the pseudo-element :defore in the last li
            $this.closest('ul').children("li").last().children("a").addClass("addBorderBefore");
            // Add margin in the first level of the list
            $this.closest('ul').css("margin-top", "20px");
            // Add margin in other levels of the list
            $this.closest('ul').find("li").children("ul").css("margin-top", "20px");
        }
        ;
    });
    // Add bold in li and levels above
    $selector.find('ul.multi-nested-list li').each(function () {
        $this = $(this);
        $this.mouseenter(function () {
            $(this).children("a").css({
                "font-weight": "bold",
                "color": "#336b9b"
            });
        });
        $this.mouseleave(function () {
            $(this).children("a").css({
                "font-weight": "normal",
                "color": "#428bca"
            });
        });
    });
    // Add button to expand and condense - Using FontAwesome
    $selector.find('ul.multi-nested-list li.hasSubmenu').each(function () {
        $this = $(this);
        $this.prepend("<a href='#'><i class='fa fa-minus-circle'></i><i style='display:none;' class='fa fa-plus-circle'></i></a>");
        $this.children("a").not(":last").removeClass().addClass("toogle");
    });
    // Actions to expand and consense
    $selector.find('ul.multi-nested-list li.hasSubmenu a.toogle').click(function () {
        $this = $(this);
        $this.closest("li").children("ul").toggle("slow");
        $this.children("i").toggle();
        return false;
    });

    $selector.addClass('loaded');

}


    var cCreateGridUploader = function (vgrid) {
        var thisObj = this;
        thisObj.grid = vgrid;
        var vidtoolbar = thisObj.grid + '_type_toolbar';
        var vgridattachment = thisObj.grid + '_types';
        var vprogressBar = thisObj.grid + '_pbar';
        var vUploadButttonHidden = 'filesUpload_' + thisObj.grid
        var fAfterIns = function (vrecid) {};
        var fAfterUpload = function (hasError, errorList) {
            if (hasError) {
                console.log(hasError, errorList);
                var vhtml = '<h5><strong>Error(s) when Uploading</strong></h5><table class="table table-condensed table-bordered">';
                vhtml += '<thead> <tr> <th>#</th> <th>File Name</th> <th>Error</th></tr></thead><tbody>';
                $.each(errorList, function (i, v) {
                    vhtml += '<tr> <th scope="row">' + (i + 1) + '</th> <td>' + v.filename + '</td> <td>' + v.error + '</td> </tr>';
                });
                vhtml = vhtml + '<tbody></table>';

                messageBoxError(vhtml);
            }
        };
        var vErrorList = [];
        var vHasError = false;



        w2ui[thisObj.grid].on("itemChanged", function (e) {
            w2ui[thisObj.grid].setItemAsChanged(e.recid, 'cd_document_repository');
        });

        w2ui[thisObj.grid].on("pickList", function (e) {
            w2ui[thisObj.grid].setItemAsChanged(e.recid, 'cd_document_repository');
        });

        this.setAfterInsertFunction = function (ff) {
            fAfterIns = ff;
        }
        this.setAfterAllUploadFinished = function (ff) {
            fAfterUpload = ff;
        }

        this.makeTypeSelection = function (beforeid, codefield, descfield, list) {
            
            var html = "<select id ='" + thisObj.grid + "_types' style='width: 300px' onchange='w2ui[\"" + thisObj.grid + "\"].setToolbarData(this.value)'>";
            var vsel = '-1';
            thisObj.typeCode = codefield;
            thisObj.typeDesc = descfield;


            $.each(list, function (i, v) {

                if (v.fl_default == 1) {
                    vsel = v[codefield];
                }
                html = html + "<option value='" + v[codefield] + "' title='" + v[descfield] + "'>" + v[descfield] + "</option>";
            });
            html = html + "</select>";

            w2ui[thisObj.grid].setToolbarData(vsel);
            w2ui[thisObj.grid].toolbar.insert(beforeid, [{type: 'html', id: vidtoolbar, html: html}]);
            console.log('set default', vsel );
            $('#' + vgridattachment).val(vsel);

            w2ui[thisObj.grid].toolbar.on('refresh', function (event) {
                event.onComplete = function () {
                    if (event.target == vidtoolbar) {
                        $('#' + vgridattachment).val(w2ui[thisObj.grid].getToolbarData());
                    }
                }
            });
        }

        w2ui[thisObj.grid].setToolbarData = function (val) {
            console.log('setToolbar', thisObj.grid);
            w2ui[thisObj.grid].selectedTb = val;
        }

        w2ui[thisObj.grid].getToolbarData = function () {
            console.log('getToolbar', thisObj.grid, w2ui[thisObj.grid].selectedTb );
            return w2ui[thisObj.grid].selectedTb;
        }


        this.initUploader = function () {

            var xb = '<input type="file" title="Click to add Files" accept="' + vExtUpload + '" id="' + vUploadButttonHidden + '" name="files[]" multiple class="hidden">';
            var vp = '<div id="' + vprogressBar + '" style="height: 5px; width: 100%"><div class="progress" style="background-color: white;height: 5px;margin: 0px;"><div class="progress-bar" role="progressbar" style="width: 0%;"></div></div></div>';

            //$(w2ui[thisObj.grid].box).find('div').first().css('margin-top',  '5px');
            $(w2ui[thisObj.grid].box).parent().append(xb + vp);




            $('#' + vUploadButttonHidden).fileupload({
                /* ... */
                progressall: function (e, data) {
                    var progress = parseInt(data.loaded / data.total * 100, 10);

                    if (progress == 100) {
                        console.log($('#' + vprogressBar).find('.progress-bar'));
                        $('#' + vprogressBar).find('.progress-bar').css('width', '0%');
                    } else {
                        $('#' + vprogressBar).find('.progress-bar').css('width', progress + '%');
                    }
                },
                url: 'docrep/general_document_repository/upload',
                replaceFileInput: false,
                singleFileUploads: true,
                sequentialUploads: true,
                dataType: 'json',

                maxChunkSize: 10 * 1024 * 1024,
                progressInterval: 100,
                always: function (e, data) {
                    var activeUploads = $('#' + vUploadButttonHidden).fileupload('active');
                    if (activeUploads == 1) {
                        fAfterUpload(vHasError, vErrorList);
                        $('#' + vprogressBar).find('.progress-bar').css('width', '0%');
                        vErrorList = [];
                        vHasError = false;
                    }



                },
                done: function (e, data) {

                    if (data.result.files[0].error) {
                        vErrorList.push({filename: data.result.files[0].name, error: data.result.files[0].error});
                        vHasError = true;
                        return;
                    }

                    w2ui[thisObj.grid].insertRow({
                        funcAfter: function (a) {
                            w2ui[thisObj.grid].setItem(a.recid, 'ds_original_file', data.result.files[0].name);
                            w2ui[thisObj.grid].setItem(a.recid, 'ds_document_repository', data.result.files[0].name);
                            w2ui[thisObj.grid].setItem(a.recid, 'fl_adding', 'Y');
                            w2ui[thisObj.grid].setItem(a.recid, 'cd_document_file', data.result.files[0].cd_document_file);
                            w2ui[thisObj.grid].setItem(a.recid, 'cd_document_repository', -3);
                            w2ui[thisObj.grid].setItem(a.recid, 'cd_document_repository_type', data.result.files[0].cd_document_repository_type);

                            var $idType = $("#" + vgridattachment + " :selected");
                            console.log('array', w2ui[thisObj.grid].records);


                            w2ui[thisObj.grid].setItem(a.recid, thisObj.typeCode, $idType.val());
                            w2ui[thisObj.grid].setItem(a.recid, thisObj.typeDesc, $idType.text());

                            fAfterIns(a.recid);

                        }
                    });

                },
                fail: function (e, data) {

                    vErrorList.push({filename: data.files[0].name, error: data.jqXHR.responseText});
                    vHasError = true;

                    
                },
                fileuploaddone: function (e, data) {
                    
                }
            });
        }

        this.checkFinished = function () {

        }

        this.uploadData = function () {
            $('#' + vUploadButttonHidden).click();
        }

        this.initUploader();


    }

