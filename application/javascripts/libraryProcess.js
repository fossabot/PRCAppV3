$.fn.cgbProcess = function (options) {

    var optionsDefault = {formVar: undefined};

    var optionsNow = $.extend({}, optionsDefault, options);


    var vBoxes = [];
    var vFields = [];
    var vCalendar = [];
    var vSelector =  '#' + $(this).attr('id');;

    $(vSelector).each(function () {

        if ($(this).hasClass('fullprcgroupa')) {
            var $tousse = $(this).parent();
        } else {
            var $tousse = $(this).find('.fullprcgroup').parent();
        }


        //console.log('x', $tousse);
        /*
        $($tousse).mCustomScrollbar({
            axis: "x",
            theme: "dark",
            scrollButtons: {
                enable: true
            },
            alwaysShowScrollbar: 0,
            scrollInertia: 0,
        });
        */

    })

    addNewObjects();

    var $vsel = optionsNow.formVar.getSelector();
    $vsel.on('itemChanged', function (e) {

        if (!( e.fielddata.name == 'nr_percent'  ||
                e.fielddata.name == 'dt_finished' ||
                e.fielddata.name == "dt_dead_line" )
        ) {
            return ;
        }

        var vid = e.id;
        var vvlr = e.new;
        var $vObj = $('#' + vid);

        if (e.fielddata.name == "dt_dead_line") {


            if (vvlr == '') {
                setAutoCalculate($vObj, 'Y');
            } else {
                setAutoCalculate($vObj, 'N');
            }

            calculateDeadlinesChanged($vObj);
            checkDeadlineStatus($vObj);
        }

        if (e.fielddata.name == 'nr_percent' || e.fielddata.name == 'dt_finished') {

            if (e.fielddata.name == 'nr_percent' && e.new == '') {
                optionsNow.formVar.setItem(vid, 0);
            }

            checkcanEdit($vObj);
            setProgressAfterChange($vObj);
            var $vRelatedDeadline = getDeadlineObj($vObj);
            calculateDeadlinesChanged($vRelatedDeadline);
            checkDeadlineStatus($vRelatedDeadline, $vObj);

        }

        var ex = jQuery.Event("keypress");
        ex.which = 13; //choose the one you want
        ex.keyCode = 13;
        $($vObj).trigger(ex);

    })


    function setCalendar(vcal) {
        vCalendar = vcal;
    }

    function forceCalculateByCalendar() {

        if (vSelector != '.fullprcgroup') {
            var vs = vSelector + ' .fullprcgroup';
        } else {
            var vs = '.fullprcgroup';
        }

        $(vs).find('[deadby="1"]').each(function () {
            if (getAutoCalculate(this) == 'N') {
                return true;
            }
            optionsNow.formVar.setItem($(this).attr('id'), '');

            calculateDeadlinesChanged($(this));
        });

        $(vs).find('[deadby="2"]').each(function () {
            if (getAutoCalculate(this) == 'N') {
                return true;
            }

            optionsNow.formVar.setItem($(this).attr('id'), '');

            calculateDeadlinesChanged($(this));

        });
    }

    function loadCalendar(div, season, funcAfter) {
        if (funcAfter == undefined) {
            funcAfter = function () {};
        }
        $.myCgbAjax({
            url: 'season_x_season_launch/getCalendar/' + div + '/' + season,
            message: 'Loading Calendar',
            success: function (data) {
                vCalendar = data;
                funcAfter(vCalendar);
            }

        });
    }

    function addNewObjects() {

        var $selector = $(vSelector).find(".prcBox[started='N']");

        var vheight_max = 0;

        $.each($selector, function () {
            var vheight = $(this).height();
            if (vheight > vheight_max) {
                vheight_max = vheight;
            }
            var vId = $(this).attr('id');

            vBoxes.push(vId);

            $.each($(this).find("input"), function () {
                var vIdInput = $(this).attr('id');
                var vInputType = $(this).attr('type');

                vFields.push(vIdInput);
                var $parent = $(this).closest('.fullprcgroup');

                var prcdem = $(this).attr('prcdem');
                var prcdemchangeactual = $(this).attr('prcdemchangeactual');

                if (prcdem !== undefined && prcdem !== '-1' && prcdemchangeactual == 'N') {

                    var $field = $parent.find("input[prcitem='" + prcdem + "'][isprocess='Y']");
                    var vInputTypeDest = $(this).attr('type');

                    var vval = '';
                    if (vInputTypeDest === 'checkbox') {
                        if ($field.is(':checked')) {
                            vval = 'xxx';
                        }

                    } else {
                        vval = $field.val();
                    }

                    if (vval === '' || vval === '0') {
                        if (vInputType === 'checkbox') {
                            $(this).attr('disabled', true);
                        } else {
                            optionsNow.formVar.setEnabled(vIdInput, false);
                        }

                    }

                }

                // se for checkbox, eh toggle
                if ($(this).attr('type') === 'checkbox') {
                    var vRelated = $(this).attr('relatedTo');

                    $(this).on('change', function () {
                        var vchecked = $(this).is(':checked');
                        if (vchecked) {
                            var vdate = w2utils.formatDate((new Date()), defaultDateFormat);
                        } else {
                            var vdate = '';
                        }

                        optionsNow.formVar.setItem(vRelated, vdate);

                        var $vRelatedDeadline = getDeadlineObj(this);
                        checkDeadlineStatus($vRelatedDeadline, $(this));

                        checkcanEdit(this);
                        setProgressAfterChange(this);
                        optionsNow.formVar.forceItemChangedEvent(vRelated);

                    });
                } else {
                    /*
                     $(this).on('change', function () {
                     if ($(this).attr('isperc') == 'Y') {
                     if ($(this).val() == '') {
                     optionsNow.formVar.setItem($(this).attr('id'), 0);
                     }
                     }
                     checkcanEdit(this);
                     setProgressAfterChange(this);
                     var $vRelatedDeadline = getDeadlineObj(this);
                     calculateDeadlinesChanged($vRelatedDeadline);
                     checkDeadlineStatus($vRelatedDeadline, $(this));
                     
                     });
                     */

                }

            });

            checkDeadlineStatusALL(this);


            $(this).attr('started', 'Y');
        });


    }

    function resetObjects() {
        vBoxes = [];
        vFields = [];

    }

    function checkcanEdit(objchanged) {
        var vprcitec = $(objchanged).attr('prcitem');

        var vInputType = $(objchanged).attr('type');


        var vval = '';
        if (vInputType === 'checkbox') {
            if ($(objchanged).is(':checked')) {
                vval = 'xxx';
            }

        } else {
            vval = $(objchanged).val();
        }

        var $parent = $(objchanged).closest('.fullprcgroup');

        $parent.find("input[prcdem='" + vprcitec + "'][isprocess='Y']").each(function () {

            var vInputTypeDest = $(this).attr('type');
            var prcdemchangeactual = $(this).attr('prcdemchangeactual');

            if (prcdemchangeactual == 'Y') {
                $(this).attr('disabled', false);

                return;
            }

            if (vInputTypeDest === 'checkbox') {
                if ($(this).is(':checked')) {
                    $(this).click();
                }

                $(this).attr('disabled', vval === '');


            } else {
                optionsNow.formVar.setItem($(this).attr('id'), '');
                optionsNow.formVar.setEnabled($(this).attr('id'), vval !== '');
            }
            checkDeadlineStatus(getDeadlineObj(this), $(this));

        });

    }

    function adjustBoxSize(outerDiv) {
        var vMaxHeight = 0;
        var vtochange = [];

        $(outerDiv).find('.box-body:not([boxResized])').each(function () {

            vheight = $(this).height();
            vtochange.push(this);

            if (vheight > vMaxHeight) {
                vMaxHeight = vheight;
            }

        });

        $.each(vtochange, function (index, value) {
            $(value).height(vMaxHeight);
            $(value).attr('boxResized', 'Y');

        });



    }

    function getAutoCalculate(obj) {
        var vfl = 'fl_deadline_auto_calculate_' + $(obj).attr('indexrs') + '_' + $(obj).attr('order');
        return optionsNow.formVar.getItem(vfl);
    }

    function setAutoCalculate(obj, vlr) {
        var vfl = 'fl_deadline_auto_calculate_' + $(obj).attr('indexrs') + '_' + $(obj).attr('order');
        optionsNow.formVar.setItem(vfl, vlr);
    }

    function checkDeadlineStatusALL(objParent) {


        $(objParent).find('[isDeadline="Y"]').each(function () {
            /*
             $(this).on('change', function () {
             var vid = $(this).attr('id');
             var vvlr = chkUndefined(optionsNow.formVar.getItem(vid), '');
             if (vvlr == '') {
             setAutoCalculate(this, 'Y');
             } else {
             setAutoCalculate(this, 'N');
             }
             calculateDeadlinesChanged($(this));
             checkDeadlineStatus(this);
             
             });
             */

            var vdead = $(this).val();
            if (vdead == '') {
                return true;
            }
            checkDeadlineStatus($(this));

        })

    }

    function calculateDeadlinesChanged(objchanged) {
        //
        var vid = $(objchanged).attr('id');
        var vdeadby = $(objchanged).attr('deadBy');
        var vprcitem = $(objchanged).attr('prcitem');

        var vautocalc = getAutoCalculate(objchanged);
        var vlr = chkUndefined(optionsNow.formVar.getItem(vid), '');
        if (vlr == '' && vdeadby == -1) {
            return;
        }


        // to calculate itself;
        if (vautocalc == 'Y' && vdeadby != -1 && vlr == '') {
            switch (vdeadby) {
                // calendar
                case '1':
                case '2':
                    calculateDeadlineByMilestones(objchanged);

                    break;

                //checkpoint
                case '3':
                case '4':
                case '5':
                    calculateDeadlineByCheckpoint(objchanged);
                    break;

                default:

                    break;
            }
        }


        $(objchanged).closest('.fullprcgroup').find('[isdeadline="Y"][prcdem="' + vprcitem + '"]').each(function () {
            switch ($(this).attr('deadby')) {
                // calendar
                case '1':
                case '2':
                    calculateDeadlineByMilestones(this);
                    break;


                //checkpoint
                case '3':
                case '4':
                case '5':
                    calculateDeadlineByCheckpoint(this);
                    break;

                default:

                    break;
            }
        });


        var ev = $.extend({}, $.Event('deadLineCalculated'), {obj: $(objchanged)});
        $(vSelector).trigger(ev);


    }

    function calculateDeadlineByCheckpoint(obj) {

        var vautocalc = getAutoCalculate(obj);
        if (vautocalc == 'N') {
            return;
        }

        var vdeadId = $(obj).attr('prcdem');
        var vdeadDaysOffset = $(obj).attr('deadDaysOffset');
        var vid = $(obj).attr('id');

        var $parent = $(obj).closest('.fullprcgroup');

        var vobjprocessparent = $parent.find('[isprocess="Y"][prcitem="' + vdeadId + '"]');
        var vdateProcess = vobjprocessparent.val();
        var vdateDeadline = getDeadlineObj(vobjprocessparent).val();
        var vdeadby = $(obj).attr('deadBy');

        switch (vdeadby) {
            case '3': //Checkpoint Deadline
                if (vdateDeadline == '') {
                    return $(obj).val();
                }

                var dateToEval = vdateDeadline;

                break;
            case '4': //Finalized Date
                if (vdateProcess == '') {
                    return $(obj).val();
                }

                var dateToEval = vdateProcess;

                break;

            case '5': //Finalized Date or Deadline
                if (vdateProcess == '' && vdateDeadline == '') {
                    return $(obj).val();
                }

                if (vdateProcess == "") {
                    var dateToEval = vdateDeadline;
                } else {
                    var dateToEval = vdateProcess
                }

                break;



            default:

                break;
        }

        var vbasedate = moment(dateToEval, defaultDateFormatUpper);
        if (vdeadDaysOffset < 0) {
            var vnewdate = vbasedate.subtract(vdeadDaysOffset, 'days');
        } else {
            var vnewdate = vbasedate.add(vdeadDaysOffset, 'days');
        }

        var vnewdatestr = vnewdate.format(defaultDateFormatUpper);
        if (vnewdatestr != chkUndefined(optionsNow.formVar.getItem(vid), '')) {
            optionsNow.formVar.setItem(vid, vnewdatestr);
        }
        checkDeadlineStatus(obj);
        calculateDeadlinesChanged(obj);

    }


    function calculateDeadlineByMilestones(obj) {
        if (vCalendar.length == 0) {
            return $(obj).val();

        }

        var vautocalc = getAutoCalculate(obj);
        if (vautocalc == 'N') {
            return;
        }

        var vdeadId = $(obj).attr('deadId');
        var vdeadDaysOffset = $(obj).attr('deadDaysOffset');
        var vid = $(obj).attr('id');

        var vindex = -1;

        $.each(vCalendar, function (i, v) {
            if (v.cd_season_launch == vdeadId) {
                vindex = i;
                return false;
            }
        });

        if (vindex == -1) {
            return $(obj).val();
        }

        var vdatestart = vCalendar[vindex].dt_start_date;
        var vdateDeadline = vCalendar[vindex].dt_deadline;
        var vdeadby = $(obj).attr('deadBy');

        switch (vdeadby) {
            case '1': //Checkpoint Deadline
                if (vdatestart == '') {
                    return $(obj).val();
                }

                var dateToEval = vdatestart;

                break;
            case '2': //Finalized Date
                if (vdateDeadline == '') {
                    return $(obj).val();
                }

                var dateToEval = vdateDeadline;

                break;

            default:

                break;
        }

        var vbasedate = moment(dateToEval, defaultDateFormatUpper);
        if (vdeadDaysOffset < 0) {
            var vnewdate = vbasedate.subtract(vdeadDaysOffset, 'days');
        } else {
            var vnewdate = vbasedate.add(vdeadDaysOffset, 'days');
        }

        var vnewdatestr = vnewdate.format(defaultDateFormatUpper);
        if (vnewdatestr != chkUndefined(optionsNow.formVar.getItem(vid), '')) {
            optionsNow.formVar.setItem(vid, vnewdatestr);
        }

        checkDeadlineStatus(obj);
        calculateDeadlinesChanged(obj);

    }

    function isCompleted(obj) {
        var vInputType = $(obj).attr('type');
        var vPect = chkUndefined($(obj).attr('isPerc'), 'N');
        var vval = '';
        if (vInputType === 'checkbox') {
            if ($(obj).is(':checked')) {
                vval = 'xxx';
            }

        } else {
            vval = $(obj).val();
        }

        return ((vPect == 'Y' && vval == 100) || (vPect == 'N' && vval != ''));


    }

    function getDeadlineObj(processObj) {
        return $(processObj).closest('tr').find('[isDeadline="Y"]');
    }

    function getProcessObj(deadlineObj) {
        return $(deadlineObj).closest('tr').find('[isProcess="Y"]');
    }

    function checkDeadlineStatus(obj, processRelated) {
        var $obj = $(obj);
        var vjsonStatus = JSON.parse($obj.attr('deadstatus'));
        var vdateNow = moment().startOf('day');

        if (processRelated == undefined) {
            var $vRelatedProcess = getProcessObj(obj);
        } else {
            var $vRelatedProcess = $(processRelated);
        }



        var vdateDeadLine = $obj.val();
        var $vtr = $obj.closest('td').siblings('.processAreaLabel');
        $vtr.css('background-color', '');
        $vtr.css('color', '');

        if (isCompleted($vRelatedProcess)) {
            return;
        }

        if (vdateDeadLine == '') {
            return;
        }

        vdateDeadLine = moment(vdateDeadLine, defaultDateFormatUpper).startOf('days');

        var dayDiff = vdateDeadLine.diff(vdateNow, 'days');

        $.each(vjsonStatus, function (i, v) {

            if ((v.ds_background_color != '' || v.ds_font_color != '') && dayDiff <= v.nr_days_before_deadline) {
                if (v.ds_background_color != '') {
                    $vtr.css('background-color', '#' + v.ds_background_color);
                }

                if (v.ds_font_color != '') {
                    $vtr.css('color', '#' + v.ds_font_color);
                }

                return false;
            }
        });


    }

    function setProgressAfterChange(objchanged) {
        var $boxParent = $(objchanged).closest('.box');

        var vTotal = 0;
        var vInformed = 0;
        $boxParent.find('input[isProcess="Y"]').each(function () {
            vTotal++;
            var vCompleted = isCompleted($(this));
            if (vCompleted) {
                vInformed++;
            }

        });

        $boxParent.find('.progress-bar').width((vInformed / vTotal) * 100 + '%');


    }



    this.resetObjects = resetObjects;
    this.addNewObjects = addNewObjects;
    this.adjustBoxSize = adjustBoxSize;
    this.setCalendar = setCalendar;
    this.loadCalendar = loadCalendar;
    this.forceCalculateByCalendar = forceCalculateByCalendar;
    return this;

}

var dsProcessModal = new function () {
    var thisObjPrcModal = this;

    this.afterOpen = function () {
        thisObjPrcModal.myForm = $('prcArea' + thisObjPrcModal.options.code).cgbForm();
        thisObjPrcModal.vProcess = $('#prcArea' + thisObjPrcModal.options.code).cgbProcess({formVar: thisObjPrcModal.myForm});
        thisObjPrcModal.vProcess.adjustBoxSize('#prcArea' + thisObjPrcModal.options.code);
        SBSModalVarPopupPrcModal.adjustPosition();

        thisObjPrcModal.vProcess.setCalendar(thisObjPrcModal.setCal);
        thisObjPrcModal.vProcess.forceCalculateByCalendar();

        thisObjPrcModal.options.afterOpen(thisObjPrcModal.options.level, thisObjPrcModal.options.code);





        SBSModalVarPopupPrcModal.beforeClose = function () {


            var vbc = thisObjPrcModal.options.beforeClose(thisObjPrcModal.options.level, thisObjPrcModal.options.code);

            if (!vbc) {
                return false;
            }

            thisObjPrcModal.myForm = undefined;
            thisObjPrcModal.vProcess = undefined;
            thisObjPrcModal.setCal = [];


            return true;

        }
    }

    this.openModal = function (options) {
        var optionsDefault = {target: '',
            width: $(window).width() * 0.8,
            level: 0,
            code: 0,
            afterOpen: function () {},
            beforeClose: function () {
                return true;
            },
            beforeSave: function () {
                return true;
            },
            afterSave: function () {}
        };

        thisObjPrcModal.setCal = [];

        var optionsNow = $.extend({}, optionsDefault, options);
        thisObjPrcModal.options = optionsNow;

        if (optionsNow.level == 0) {
            messageBoxError('Level Missing');
            return;
        }

        if (optionsNow.code == 0) {
            messageBoxError('Code Missing');
            return;
        }

        basicPickListOpenPopOver({
            title: 'Process',
            target: optionsNow.target,
            controller: 'spec/shoe_process/getProcessAreaModal',
            postParam: {code: optionsNow.code, level: optionsNow.level},
            showClose: true,
            position: 'auto',
            width: optionsNow.width + 'px',
            plVarSuffix: 'PrcModal',
            functionOpen: thisObjPrcModal.afterOpen
        })




        this.getFormObj = function () {
            return thisObjPrcModal.myForm;
        }

        this.getProcessObj = function () {
            return thisObjPrcModal.vProcess;
        }

        this.setCalendar = function (vcal) {
            thisObjPrcModal.setCal = vcal;
        }

        this.saveChanges = function () {
            if (!thisObjPrcModal.myForm.isChanged()) {
                return;
            }

            var jdata = {};
            jdata['level'] = thisObjPrcModal.options['level'];
            jdata['code'] = thisObjPrcModal.options['code'];
            jdata['data'] = thisObjPrcModal.myForm.getChanges();

            var vbc = thisObjPrcModal.options.beforeSave(thisObjPrcModal.options.level, thisObjPrcModal.options.code, jdata['data']);
            if (!vbc) {
                return;
            }

            $.myCgbAjax({url: 'spec/shoe_process/saveProcess/',
                message: javaMessages.updating,
                box: '.content-wrapper',
                dataType: 'json',
                data: jdata,
                success: function (a) {
                    if (a.status != 'OK') {
                        messageBoxError(a.status);
                        return;
                    }

                    setTimeout(function () {
                        thisObjPrcModal.options.afterSave(thisObjPrcModal.options.level, thisObjPrcModal.options.code, a.rs);
                    }, 50);

                    SBSModalVarPopupPrcModal.close();

                },
            });


        }

    }




}
