/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/* lista de tags usadas pelo objeto (html tags)
 * isMyFormStarted : Controle interno se o campo jah foi iniciado (tratado pelo makeField)
 * FieldName: Nome do campo no database (necessario especialmente quando o form tem multiplas vezes o mesmo campo)
 * Order: Indice que coloca o resultset dentro do array  (necessario especialmente quando o form tem multiplas vezes o mesmo campo)
 * indexRS: Sub Indice que complementa o Order. Soh necessario quase precise fazer o array com mais uma dimensao
 * must: Demandado!
 * code_field: campo de codigo refernete a picklist (por exemplo, cd_season em um campo onde temos o ds_season_form)
 * model: Modelo (criptografado) do Picklist;
 * relid: Eh o ID para o select de exists, caso exista necessidade de relacionamento.
 * relcode: Codigo que pode ser alterado para fechar o relacionamento (codigo externo do relid)
 * plCode: Codigo existente do picklist (identico ao value para o codigo, jah que em PL o value eh a descricao)
 * ru: Related Update: When something changed on field, it will force the update status of the field
 * ro: ReadOnly (inicia disabled)
 * sc: Start Changed
 * ig: Ignore
 * as: async
 * addinfo: Aditional Info that will be saved on info array.
 * DEFINICAO DO MASK:
 *  mask:
 *  RO: Read Only
 *  N;A.B: Numerico, onde A sao os inteiros e B sao os decimais (quantidade de)
 *  C: Input apenas maiusculo:
 *  c: Input sem case especifico.
 *  T: Campo de Note, apenas maiusculo.
 *  t: Campo de noite, sem case especifico.
 *  I: Integer
 *  D: Date
 *  PK: Codigo
 *  IS (min;max;step): Integer Slider
 *  G : Span
 *  pldbig => Add class for PLD = 80% of the screen
 */

String.prototype.repeat = function (num) {
    return new Array(num + 1).join(this);
}

//(function ($) {
$.fn.cgbForm = function (options) {

    var info = {};
    var namevsid = {};
    var rset = {};
    var plFunctionText = openGenericText;
    var changeTextFunction = changeTextfield;
    var triggerEvent = {};
    var isChangedVar = false;
    var OriginalRecordSet = {};
    var plOpenId = '';
    var originalFromForm = false;
    var pkCode = -1;
    var pkField = -1;
    var updController;
    var updFunction = 'updateDataJsonForm';
    var retFunction = 'retrieveGridJsonForm';
    var formSize = 'original';
    var formSuffix = '_form';
    var cgbFileUploadObj = null;
    var updRemoveDescFields = true;
    var autoCheckDemanded = undefined;
    var gridToControl = [];
    var toSetAsChanged = [];
    var vNameData = {};
    var boxToLock = '';
    var pldData = {};
    var checkDemandedForInvisible = false;
    var runFuncQueue = [];
    var changeByColumn = {};
    // se true (default), depois de atualizar, faz o retrieve e seta novamente.
    var refreshAfterUpdate = true;
    // controle de popup, nesse caso de ser popup ( o default = true).
    var vUniqueId = 'Form' + $.now();

    // se essa variavel tiver setada, faz o form replicar no grid!
    var gridToSetAfterUpd;

    if (options !== undefined) {
        if (options.recordset !== undefined) {
            OriginalRecordSet = $.extend({}, options.recordset);

            originalFromForm = false;
        }
        if (options.recordsetFromForm !== undefined) {
            originalFromForm = options.recordsetFromForm;
        }
        if (options.grid !== undefined) {
            gridToSetAfterUpd = options.grid;
        }

        if (options.refreshAfterUpdate !== undefined) {
            refreshAfterUpdate = options.refreshAfterUpdate;
        }

        if (options.formSuffix !== undefined) {
            formSuffix = options.formSuffix;
        }

        if (options.formSize !== undefined) {
            formSize = options.formSize;
        }

        if (options.cgbFileUploadObj !== undefined) {
            cgbFileUploadObj = options.cgbFileUploadObj;
        }
        if (options.updRemoveDescFields !== undefined) {
            updRemoveDescFields = options.updRemoveDescFields;
        }

        if (options.autoCheckDemanded !== undefined) {
            autoCheckDemanded = options.autoCheckDemanded;
        }

        if (options.updFunction !== undefined) {
            updFunction = options.updFunction;
        }

        if (options.updController !== undefined) {
            updController = options.updController;
        }

        if (options.boxToLock !== undefined) {
            boxToLock = options.boxToLock;
        }

        if (options.checkDemandedForInvisible !== undefined) {
            checkDemandedForInvisible = options.checkDemandedForInvisible;
        }


    }

    var $selector = $(this);

    var $inputs = $selector.find(':input, span[mask="G"], textarea').not(":input[type=button]");

    //console.log(this.selector);

    if (boxToLock == '') {
        var $selectorLock = $(this).closest('.jconfirm-box');

        if ($selectorLock.length === 0) {
            $selectorLock = $selector.parent();
        }



    } else {
        var $selectorLock = $(boxToLock);
    }



    start();


    if (originalFromForm) {
        formToOriginal();
    }


    function start() {
        var vhere = this;
        if (formSize == 'small') {
            $selector.find('.w2ui-field').addClass('cgbSmallForm');
        }
        ;
        if (formSize == 'original') {
            //$selector.find('.w2ui-field').addClass('cgbNormalForm');
        }

        if (formSize == 'normal') {
            $selector.find('.w2ui-field').addClass('cgbNormalForm');
        }

        toSetAsChanged = [];
        $inputs.each(function () {
            var id = $(this).attr('id');

            if (id === undefined) {
                //console.log('UNDEFINED', $(this));
                return true;
            }

            var async = chkUndefined($('#' + id).attr('as'), 'N');

            if (async == 'Y') {
                var fun = function () {
                    runFuncQueue.push(fun);
                };
            } else {
                makeField(id, OriginalRecordSet);
            }


        });

        triggerEvent.info = info;
        triggerEvent.formSelector = $selector.attr('id');

        if (toSetAsChanged.length > 0) {
            setAsChanged(toSetAsChanged);
        }
        setTimeout(function () {
            var numberToRun = runFuncQueue.length;
            for (var i = 0; i < numberToRun; i++) {
                console.log('form async', numberToRun, i);
                (runFuncQueue.shift())();
            }

        }, 0);


    }


    function makeField(id, recordSet) {

        var $input = $('#' + id);

        if ($input.attr('data-toggle') == 'tooltip') {
            $input.tooltip({trigger: 'hover'});
        }

        if ($input.attr('ig') !== undefined) {
            return true;
        }
        ;


        var vStarted = $input.attr('isMyFormStarted');
        if (vStarted === undefined) {
            vStarted = 'N';
        }

        var vaddInfo = $input.attr('addinfo');


        

        // start changed: Faz ele iniciar com o status de changed!
        var vStartChanged = $input.attr('sc');
        if (vStartChanged == undefined) {
            vStartChanged = 'N';
        }


        // iniciado
        $input.attr('isMyFormStarted', 'Y');

        // nome do campo sem o sufixo
        var fieldName = $input.attr('fieldName');

        if (fieldName == undefined) {
            fieldName = id.substring(0, id.length - formSuffix.length);
            $input.attr('fieldName', fieldName);
        }

        // nome do campo sem o sufixo
        var vMax = $input.attr('max-value');
        var vMin = $input.attr('min-value');


        // index do resultset
        var orderField = $input.attr('order');
        if (orderField == undefined) {
            orderField = 'N';
        }


        // outro indice do resultset
        var indexResultSet = $input.attr('indexRS');
        if (indexResultSet == undefined) {
            indexResultSet = 0;
        }

        // demandado
        var demanded = $input.attr('must');
        if (demanded === undefined) {
            demanded = 'N';
        }

        if (demanded === 'Y' && autoCheckDemanded === undefined) {
            autoCheckDemanded = true;
        }

        // ru - Related Update ;
        var ru = $input.attr('ru');
        //console.log('1', OriginalRecordSet, $input);


        // readonly
        var vReadOnly = $input.attr('ro');
        if (vReadOnly == undefined) {
            vReadOnly = 'N';
        }

        namevsid[fieldName] = id;
        var $inputlb = $("label[for='" + id + "']");
        var inputLabel = $inputlb.text();

        if ($inputlb.length > 0 && demanded === 'Y') {
            $inputlb.css('color', 'blue');
        }

        if (inputLabel === '') {
            inputLabel = $input.attr('placeholder');
        }

        if (inputLabel === undefined) {
            inputLabel = $input.attr('title');
        } else {
            $($inputlb).on('mouseenter', function () {
                var $this = $(this);
                if (this.offsetWidth < this.scrollWidth && !$this.attr('title')) {
                    $this.tooltip({
                        title: $this.text(),
                        placement: "bottom"
                    });
                    $this.tooltip('show');
                }
            });

        }

        //info[id] = {formated: false};
        var mask = $input.attr('mask');



        if (recordSet !== undefined) {
            if (recordSet[fieldName] !== undefined) {
                if (orderField === 'N') {
                    if (recordSet[fieldName] !== null) {
                        $input.val(recordSet[fieldName]);
                    }
                } else {
                    // se diferente de "N"
                    if (recordSet[fieldName][indexResultSet] !== undefined && recordSet[fieldName][indexResultSet][orderField] !== undefined && recordSet[fieldName][indexResultSet][orderField] !== null) {
                        $input.val(recordSet[fieldName][indexResultSet][orderField]);
                    }

                }
            }
        } else {
            // se nao esta iniciado, faco a limpeza o rset caso jah exista (resquicios de campos anteriores)
            if (vStarted == 'N') {
                if (orderField === 'N') {
                    if (rset[fieldName] != undefined) {
                        delete rset[fieldName];
                        delete changeByColumn[id];
                    }
                } else {
                    if (rset[fieldName] !== undefined && rset[fieldName][indexResultSet] !== undefined && rset[fieldName][indexResultSet][orderField] !== undefined) {
                        delete rset[fieldName][indexResultSet][orderField];
                        delete changeByColumn[id];
                    }
                }
            }

        }

        var vOriginalData = $input.val();
        //console.log('2', OriginalRecordSet, $input);
        // controles especificos!
        // se data, jah coloca o tipo data!
        if (id.substring(0, 3) == 'dt_' || mask == 'D') {

            //$input.w2field('date');
            $input.attr('placeholder', defaultDateFormat);
            $input.datepicker({
                autoclose: true,
                format: defaultDateFormat,
                todayBtn: "linked",
                clearBtn: true,
                todayHighlight: true,
                forceParse: true
            });


            $input.css('text-align', 'center');
            info[id] = {formated: true, type: 'D', name: fieldName, order: orderField, indexRS: indexResultSet, demanded: demanded, title: inputLabel, relatedUpdate: ru, addinfo: vaddInfo};
            $input.change(function (event) {
                changeTextFunction(id);
            });

        }

        if (mask !== undefined) {

            var maskex = mask.split(';');

            switch (maskex[0]) {
                case 'RO' :
                    $input.prop('disabled', true);
                    info[id] = {formated: false, type: maskex[0], name: fieldName, order: orderField, indexRS: indexResultSet, demanded: demanded, title: inputLabel, relatedUpdate: ru, checkReload: 'Y', addinfo: vaddInfo};

                    break;

                case 'N':
                    var varex = maskex[1].split('.');

                    maxnumber = "9".repeat(parseInt(varex[0])) + '.' + "9".repeat(parseInt(varex[1]));
                    $input.css('text-align', 'right');
                    $input.autoNumeric('init', {aSep: ',', aDec: '.', vMax: "9".repeat(parseInt(varex[0])), mDec: varex[1]});
                    $input.autoNumeric('update');

                    // como ele eh formated, o makeField eh chamado cada vez que existe alguma alteracao!
                    if (info[id] === undefined || vStarted == 'N') {
                        info[id] = {maxnumber: maxnumber, formated: true, type: maskex[0], name: fieldName, order: orderField, indexRS: indexResultSet, demanded: demanded, title: inputLabel, relatedUpdate: ru, checkReload: 'Y', addinfo: vaddInfo};

                        $input.change(function (event) {
                            changeTextFunction(id);
                        });
                    } else {
                        //changeTextFunction(id);
                    }

                    break;

                case 'C':
                case 'c':
                    info[id] = {formated: true, type: maskex[0], name: fieldName, order: orderField, indexRS: indexResultSet, demanded: demanded, title: inputLabel, relatedUpdate: ru, checkReload: 'Y', addinfo: vaddInfo};

                    if (maskex[0] == 'C') {
                        $input.css('text-transform', 'uppercase');
                        info[id].uppercase = true;
                    }

                    if (maskex.length == 1) {
                        maskex[1] = 10000;
                    }

                    $input.attr('maxlength', maskex[1]).attr("autocomplete", "off");

                    $input.change(function (event) {
                        changeTextFunction(id);
                    });

                    break;

                case 'CHK':
                    info[id] = {formated: true, type: maskex[0], name: fieldName, order: orderField, indexRS: indexResultSet, demanded: demanded, title: inputLabel, relatedUpdate: ru, checkReload: 'Y', addinfo: vaddInfo};

                    var vval = $($input).val();

                    $($input).iCheck({
                        checkboxClass: 'icheckbox_square-blue',
                        radioClass: 'iradio_square',
                        //increaseArea: '%' // optional
                    });
                    $($input).parent().height(26).css('margin-top', '1px');

                    if (vval == undefined || vval == 0) {
                        if (vval == undefined) {
                            $($input).val('0');
                        }
                        $($input).iCheck('uncheck');
                    } else {
                        $($input).iCheck('check');
                    }


                    $input.on('ifChanged', function (event) {
                        changeTextFunction(id);
                    });

                    break;

                case 'I':

                    if (maskex.length == 1) {
                        maskex[1] = 14;
                    }
                    var maxnumber = "9".repeat(parseInt(maskex[1]));

                    var param = {aSep: ',', aDec: '.', vMax: maxnumber, mDec: '0'}

                    if (vMax !== undefined) {
                        param['vMax'] = vMax;
                    }
                    if (vMin !== undefined) {
                        param['vMin'] = vMin;
                    }



                    $input.css('text-align', 'right');
                    //$input.w2field('int', {max: parseFloat(maxnumber), silent: false});
                    $input.autoNumeric('init', param);
                    $input.autoNumeric('update');

                    if (info[id] === undefined || vStarted == 'N') {
                        info[id] = {maxnumber: maxnumber, formated: true, type: maskex[0], name: fieldName, order: orderField, indexRS: indexResultSet, demanded: demanded, title: inputLabel, relatedUpdate: ru, checkReload: 'Y', addinfo: vaddInfo};

                        $input.change(function (event) {
                            changeTextFunction(id);
                        });
                    }

                    break;

                case 'IS':

                    var vmin = parseInt(maskex[1]);
                    var vmax = parseInt(maskex[2]);
                    var vstep = parseInt(maskex[3]);
                    $input.css('width', '100%');
                    $input.bootstrapSlider({step: vstep, min: vmin, max: vmax});

                    if (info[id] === undefined || vStarted == 'N') {
                        info[id] = {maxnumber: vmax, formated: true, minnumber: vmin, step: vstep, type: maskex[0], name: fieldName, order: orderField, indexRS: indexResultSet, demanded: demanded, title: inputLabel, relatedUpdate: ru, checkReload: 'Y', addinfo: vaddInfo};

                        $input.change(function (event) {
                            changeTextFunction(id);
                        });
                    }

                    break;
                case 'G':

                    info[id] = {formated: false, type: maskex[0], name: fieldName, order: orderField, indexRS: indexResultSet, demanded: demanded, relatedUpdate: ru, checkReload: 'Y', addinfo: vaddInfo};

                    if (recordSet !== undefined) {
                        var dd = recordSet[fieldName];
                        if (dd !== undefined) {
                            $input.text(dd);
                        }
                    }


                    break;

                case "PK":
                    $input.css('text-align', 'right');
                    $input.attr('readonly', 'readonly');

                    info[id] = {formated: false, type: maskex[0], name: fieldName, order: orderField};
                    info[id] = {formated: false, type: maskex[0], name: fieldName, order: orderField};
                    info['recid_form'] = {formated: false, type: maskex[0], name: fieldName, order: orderField, demanded: demanded, title: inputLabel, relatedUpdate: ru, checkReload: 'N'};

                    pkField = id;
                    pkCode = $input.val();

                    if (recordSet != undefined) {

                        var dd = recordSet[fieldName];
                        if (dd === undefined) {

                            if (recordSet['recid'] == undefined) {
                                rset['recid'] = $input.val();
                            } else {
                                rset['recid'] = recordSet['recid'];

                            }
                            //setItem(id, rset['recid']);
                        } else {
                            rset['recid'] = recordSet[fieldName];
                        }

                        pkCode = rset['recid'];
                    }

                    break;

                case 't':

                case 'T':
                    info[id] = {title: $("label[for='" + id + "']").text(), formated: false, type: maskex[0], name: fieldName, order: orderField, indexRS: indexResultSet, demanded: demanded, title: inputLabel, uppercase: (maskex[0] === 'T'), relatedUpdate: ru, checkReload: 'Y', addinfo: vaddInfo};


                    pickListSet(id, function (id) {
                        plFunctionText(id);
                    });

                    if (jQuery.browser.mobile) {
                        addPlButton(id);
                    }
                    $input.css('resize', 'none');
                    $input.attr('rows', '1');
                    $input.css('overflow-x', 'hidden');
                    $input.css('overflow-y', 'hidden');

                    break;

                case 'DD':
                    info[id] = {title: $("label[for='" + id + "']").text(), formated: false, type: maskex[0], name: fieldName, order: orderField, indexRS: indexResultSet, demanded: demanded, title: inputLabel, relatedUpdate: ru, checkReload: 'Y', id_dropdown: id + '_newdd', 'codefield': fieldName, addinfo: vaddInfo};
                    var vJson;
                    try {
                        vJson = $.parseJSON(maskex[1]);
                    } catch (e) {
                        vJson = window[maskex[1]];
                        // not json
                    }

                    createDropDownDD($input, vJson, info[id].id_dropdown);

                    break;


                case 'PL':
                    var codefield = $input.attr('code_field');

                    if (codefield == undefined) {
                        codefield = retCodeField(fieldName);
                        $input.attr('code_field', codefield);
                    }

                    info[id] = {title: inputLabel,
                        descfield: id,
                        codefield: codefield,
                        type: maskex[0],
                        model: $input.attr('model'),
                        relid: $input.attr('relid'),
                        relcode: $input.attr('relcode'),
                        name: fieldName,
                        order: orderField,
                        indexRS: indexResultSet,
                        demanded: demanded,
                        relatedUpdate: ru,
                        checkReload: 'Y', 
                        addinfo: vaddInfo
                    };

                    pickListSet(id, function (target) {
                        openPicklist(target);
                    });

                    if (jQuery.browser.mobile) {
                        addPlButton(id);
                    }

                    if (recordSet !== undefined) {
                        if (recordSet[codefield] !== undefined) {
                            if (orderField === 'N') {
                                if (recordSet[codefield] !== null) {
                                    $input.attr('plCode', recordSet[codefield]);
                                }
                            } else {
                                // se diferente de "N"
                                if (recordSet[codefield][indexResultSet] !== undefined && recordSet[codefield][indexResultSet][orderField] !== null) {
                                    $input.attr('plCode', recordSet[codefield][indexResultSet][orderField]);

                                }

                            }

                        }
                    } else {

                        if (orderField === 'N') {
                            if (rset[codefield] != undefined) {
                                delete rset[codefield];
                                delete changeByColumn[id];
                            }
                        } else {
                            if (rset[codefield] !== undefined && rset[codefield][indexResultSet] !== undefined && rset[codefield][indexResultSet][orderField] !== undefined) {
                                delete rset[codefield][indexResultSet][orderField];
                                delete changeByColumn[id];
                            }
                        }
                    }


                    break;

                case 'PLD':
                    var codefield = $input.attr('code_field');
                    var vbig = chkUndefined($input.attr("pldbig"), 'N');


                    if (codefield == undefined) {
                        codefield = retCodeField(fieldName);
                        $input.attr('code_field', codefield);
                    }

                    $input.attr('type', 'hidden');


                    info[id] = {title: inputLabel,
                        descfield: id,
                        codefield: codefield,
                        type: maskex[0],
                        model: $input.attr('model'),
                        relid: $input.attr('relid'),
                        relcode: $input.attr('relcode'),
                        name: fieldName,
                        order: orderField,
                        indexRS: indexResultSet,
                        demanded: demanded,
                        relatedUpdate: ru,
                        checkReload: 'Y',
                        canReceivefromSame: true, 
                        addinfo: vaddInfo
                    };

                    if (recordSet !== undefined) {
                        if (recordSet[codefield] !== undefined) {
                            if (orderField === 'N') {
                                if (recordSet[codefield] !== null) {
                                    $input.attr('plCode', recordSet[codefield]);
                                }
                            } else {
                                // se diferente de "N"
                                if (recordSet[codefield][indexResultSet] !== undefined && recordSet[codefield][indexResultSet][orderField] !== null) {
                                    $input.attr('plCode', recordSet[codefield][indexResultSet][orderField]);

                                }

                            }

                        }
                    } else {

                        if (orderField === 'N') {
                            if (rset[codefield] != undefined) {
                                delete rset[codefield];
                            }
                        } else {
                            if (rset[codefield] !== undefined && rset[codefield][indexResultSet] !== undefined && rset[codefield][indexResultSet][orderField] !== undefined) {
                                delete rset[codefield][indexResultSet][orderField];
                            }
                        }
                    }
                    $input.addClass('input-sm');
                    var vdata = {};
                    var vc = $input.attr('plCode');
                    if (vc != undefined) {
                        vdata = {id: $input.attr('plCode'), text: $input.val()};

                    }

                    var vsel2 = {
                        placeholder: inputLabel,
                        data: [],
                        dropdownAutoWidth: true,
                        width: "element",
                        theme: 'bootstrap',
                        target: id,
                        formatResult: select2formatResultPLAny,
                        formatSelection: select2formatSelection,
                        classToDestroy: vUniqueId,
                        dropdownCssClass: 'pldMaxWidth',

                        query: function (query) {
                            ajaxToSelect2(query, this);
                            return;
                        }
                        //containerCssClass: ':all:'
                    };

                    var vmin = $input.attr('min');
                    if (vmin != undefined) {
                        vsel2['minimumInputLength'] = vmin;
                        info[id]['canReceivefromSame'] = false;
                        info[id]['minimumInputLength'] = vmin;

                    }






                    $input.select2(vsel2);


                    $input.on('change', function (a) {
                        plOpenId = id;
                        afterPickList(a.added.id, a.added.text, a.added);
                    });

                    $input.on('select2-opening', function (a) {


                        plOpenId = id;
                        var options = {};

                        if (info[id].relid != undefined) {
                            options.relation = {id: info[id].relcode, idwhere: info[id].relid};
                        } else {
                            options.relation = {id: -1, idwhere: -1};
                        }

                        var ev = {id: id, options: options, fielddata: info[id], pk: codePK}
                        $.extend(ev, $.Event('prepicklist'), triggerEvent);
                        $($selector).trigger(ev);

                        var $vsel = $('#s2id_' + id);
                        $vsel.find('.select2-choice').tooltip('hide');

                        if (ev.isDefaultPrevented()) {
                            a.preventDefault();
                        }
                    });

                    var $vsel = $('#s2id_' + id);
                    //console.log('sel', $vsel.find('.select2-container'));

                    $vsel.find('.select2-search')
                            .append('<button type="button" style="height: 24px;display:none" id="' + id + 'BtnMaint" class="btn btn-default btn-xs ' + vUniqueId + '" aria-label="Left Align"><span class="fa fa-external-link" aria-hidden="true"></span></button>')
                            .append('<button type="button" style="height: 24px" id="' + id + 'BtnRefresh" class="btn btn-default btn-xs ' + vUniqueId + '" aria-label="Left Align"><span class="fa fa-refresh" aria-hidden="true"></span></button>')
                            .css('width', 'calc(100% - 30px)');

                    ;

//css('background-color', picklistColor).
                    $vsel.find('.select2-choice').
                            css('height', '26px').
                            attr('data-toggle', $input.attr('data-toggle')).
                            attr('data-placement', $input.attr('data-placement')).
                            attr('data-original-title', $input.attr('data-original-title')).
                            attr('placeholder', $input.attr('placeholder')).tooltip({trigger: 'hover'}).css('width', '100%');

                    $vsel.find('.select2-chosen').css('padding-right', '0px').css('margin-right', '15px');

                    //$vsel.css("padding-top", '0px').css('padding-left: 0px' );
                    $vsel.css('cssText', 'padding-top: 0px !important;padding-left: 0px !important;padding-right: 0px !important;width: 100%');

                    $input.select2("data", vdata);

                    break;


            }
        }

        //console.log('3', OriginalRecordSet, $input);

        if (vStarted === 'N') {

            if (vReadOnly === 'Y') {
                setEnabled(id, false);
            }


            if (info[id] !== undefined &&
                    getOriginalItem(id) === undefined &&
                    vOriginalData !== undefined &&
                    vOriginalData !== '') {

                setOriginalItem(id, vOriginalData);

                if (maskex !== undefined && (maskex[0] === 'PL' || maskex[0] === 'PLD') && $input.attr('plCode') !== undefined) {
                    setOriginalItemPL(id, $input.attr('plCode'));
                }

            }



            if (vStartChanged == 'Y') {
                toSetAsChanged.push(id);
            }
        }

        if (vNameData[fieldName] == undefined) {
            vNameData[fieldName] = {};
        }

        if (vNameData[fieldName][orderField] == undefined) {
            vNameData[fieldName][orderField] = {};
        }

        if (vNameData[fieldName][orderField][indexResultSet] == undefined) {
            vNameData[fieldName][orderField][indexResultSet] = {};
        }

        vNameData[fieldName][orderField][indexResultSet] = id;

        //console.log('4', OriginalRecordSet, $input);


    }

    function resetPLCache() {
        pldData = {};
    }

    function ajaxToSelect2(query, self) {
        if (query.element == undefined) {
            return;
        }
        var vid = self.target;

        var sitetopen = info[self.target].model != 'NONE' ? 'basicpicklist/plRetrieveDD' : info[self.target].controller;
        var opt = {id: chkUndefined(info[self.target].relcode, -1), idwhere: chkUndefined(info[self.target].relid, -1), siteOpen: sitetopen, model: info[self.target].model};

        if (pldData[JSON.stringify(opt)] != undefined && info[self.target].canReceivefromSame) {

            if (pldData[JSON.stringify(opt)].controller != '' && !info[vid].btnShow) {
                var $vbtn = $('#' + vid + 'BtnMaint');
                $vbtn.show();
                $vbtn.off('click');
                $vbtn.on('click', function () {
                    $('#' + vid).select2('close');
                    window.open('main/redirect/' + pldData[JSON.stringify(opt)].controller, '_newtab');
                })
                var $vsel = $('#select2-drop').find('.select2-search');
                $vsel.css('width', 'calc(100% - 60px)');
                info[vid].btnShow = true;
            }

            var $vbtn2 = $('#' + vid + 'BtnRefresh');
            $vbtn2.off('click');
            $vbtn2.on('click', function () {
                pldData[JSON.stringify(opt)] = undefined;
                $('#' + vid).select2('close');
                $('#' + vid).select2('open');
            })


            query.callback({results: removeSelect2Data(pldData[JSON.stringify(opt)].rs, query, vid)});
            return;
        }

        var options = {model: info[self.target].model,
            title: info[self.target].title,
            sel_id: getItemPLCode(self.target),
            searchterm: query.term};

        //if (info[self.target].relid != undefined) {
        options.relation = JSON.stringify(opt);
        //}

        $.myCgbAjax({url: sitetopen,
            box: 'none',
            //message: javaMessages.inserting,
            data: options,
            success: function (data) {
                if (data.controller != '') {
                    var $vbtn = $('#' + vid + 'BtnMaint');
                    $vbtn.show();
                    $vbtn.off('click');
                    $vbtn.on('click', function () {
                        $('#' + vid).select2('close');
                        window.open('main/redirect/' + pldData[JSON.stringify(opt)].controller, '_newtab');
                    })
                    var $vsel = $('#select2-drop').find('.select2-search');
                    $vsel.css('width', 'calc(100% - 60px)');
                    info[vid].btnShow = true;
                }

                var $vbtn2 = $('#' + vid + 'BtnRefresh');
                $vbtn2.off('click');
                $vbtn2.on('click', function () {
                    pldData[JSON.stringify(opt)] = undefined;
                    $('#' + vid).select2('close');
                    $('#' + vid).select2('open');
                })


                if (info[vid].demanded == 'N') {
                    data.rs.unshift({id: -1, text: ''});
                }

                pldData[JSON.stringify(opt)] = data;

                //info[self.target].cacheDataSource = data;
                info[self.target].relationFiltered = opt;
                query.callback({results: data.rs});

            }
        });
    }

    function removeSelect2Data(data, query, vid) {
        var retf = [];
        var find = query.term.toUpperCase();
        var lle = find.length;

        if (lle == 0 || info[vid].minimumInputLength) {
            return data;
        }

        $.each(data, function (index, item) {

            var desc = item.text.toUpperCase();

            if (desc.indexOf(find) != -1) {
                retf.push(item);
            }

        });

        return retf;
    }



    function createDropDownDD($inp, vjson, id_dropdown) {
        var vvalue = $inp.val();
        var vid = $inp.attr('id');
        var vid_dd = id_dropdown;

        var vsuper = '';
        var selCode, selDesc;

        var vsuper = '<select id="' + vid_dd + '" id_field="' + vid + '" style="width: 100%;" >';

        $.each(vjson, function (index, value) {
            vsuper = vsuper + '<option value="' + value.recid + '">' + value.description + '</option>';
        });

        vsuper = vsuper + '</select>';

        $inp.after(vsuper);

        $('#' + vid_dd).val(vvalue);

        $('#' + vid_dd).on('change', function () {
            var vv = $(this).val();
            var vid = $(this).attr('id_field');
            setItem(vid, vv);
        });

        $inp.hide();


    }

    function retFieldNoForm(field) {
        return field.substring(0, field.length - formSuffix.length);
    }

    function retCodeField(field) {
        return 'cd' + field.substring(2, field.length);
    }

    function openGenericText(id) {
        var vlr = $("#" + id).val();

        basicTextPLOpen({title: info[id].title, text: vlr, uppercase: info[id].uppercase,
            plCallBack: function (saved, text) {
                if (saved) {
                    $('#' + id).val(text);
                    changeTextfield(id);
                    //console.log(teste, id);
                }
            }
        });
    }

    function setCgbFileUploadObj(obj) {
        cgbFileUploadObj = obj;
    }

    function forceItemChangedEvent(id) {
        changeTextfield(id, true);
    }

    function changeTextfield(id, forced) {

        if (forced == undefined) {
            forced = false;
        }

        var ev = {id: id, fielddata: info[id]};
        var vlr = $('#' + id).val(), oldvlr = getItem(id);

        if (info[id].uppercase) {
            vlr = vlr.toUpperCase();
        }

        if (info[id].type == 'I' || info[id].type == 'N') {
            vlr = $('#' + id).autoNumeric('get');
            oldvlr = parseFloat(oldvlr);
        }

        if (info[id].type == 'CHK') {
            if ($('#' + id).is(':checked')) {
                vlr = 1;
                oldvlr = 0;
            } else {
                vlr = 0;
                oldvlr = 1;
            }
        }

        // formato conforme configuracao!
        if (vlr == oldvlr && !forced) {
            return;
        }



        $.extend(ev, $.Event('itemChanging'), triggerEvent, {old: oldvlr, new : vlr, id: id, info: info[id], pk: pkCode});
        $selector.trigger(ev);

        if (ev.isDefaultPrevented()) {
            setItem(id, oldvlr);
            return;
        }




        // not prevented
        setItem(id, vlr);

        $.extend(ev, $.Event('itemChanged'), triggerEvent, {old: oldvlr, new : vlr, id: id, info: info[id], pk: pkCode});
        $selector.trigger(ev);



    }

    function openPicklist(target, runPrePicklist) {

        if (runPrePicklist == undefined) {
            runPrePicklist = true;
        }

        options = {model: info[target].model,
            title: info[target].title,
            sel_id: getItemPLCode(target)};

        if (info[target].relid != undefined) {
            options.relation = {id: info[target].relcode, idwhere: info[target].relid};
        }

        options.plCallBack = afterPickList;

        plOpenId = target;

        if (runPrePicklist) {
            var ev = {id: target, options: options, fielddata: info[target], pk: codePK}
            $.extend(ev, $.Event('prepicklist'), triggerEvent);
            $selector.trigger(ev);

            if (ev.isDefaultPrevented()) {
                return;
            }
        }

        basicPickListOpen(options);
    }

    function afterPickList(id, desc, record) {
        var vlrid = id, oldidvlr = getItemPLCode(plOpenId),
                vlrdesc = desc, olddescvlr = getItem(plOpenId);

        var ev = {id: plOpenId, fielddata: info[plOpenId], record: record, newCode: vlrid, newDesc: vlrdesc, oldCode: oldidvlr, oldDesc: olddescvlr, pk: pkCode};

        setItemPL(plOpenId, id, desc);


        $.extend(ev, $.Event('pospicklist'), triggerEvent);
        $selector.trigger(ev);

    }

    function recordsetToForm(recordset) {
        $.each(recordset, function (key, value) {
            var formKey = '';

            if (key == 'recid') {
                formKey = pkField;
            } else {
                formKey = key + formSuffix;
            }


            if (value === null) {
                return true;
            }

            if (info[formKey] === undefined) {
                return true;
            }

            if (info[formKey]['order'] === 'N') {
                setItem(formKey, value);

                if (info[formKey].type == 'PL' || info[formKey].type == 'PLD') {
                    setItemPL(formKey, recordset[info[formKey]['codefield']], value);
                    // setItemPLCode(formKey, recordset[info[formKey]['codefield']]);
                }
            } else {

                // se for <> 'N'
                if (value[info[formKey]['order']] !== undefined) {
                    setItem(formKey, value[info[formKey]['order']]);

                    if (info[formKey].type == 'PL' || info[formKey].type == 'PLD') {

                        setItemPL(formKey, value[info[formKey]['order']], recordset[info[formKey]['codefield']][info[formKey]['order']]);
                    }
                }
            }

        });


    }

    function getRecordsetField(id) {
        if (info[id] == undefined) {
            //console.log('Error ID NOT FOUND', id, info);
            alert('Error ID NOT FOUND - GetRecordSet - Check Console');
            //console.log('Id Not Found', id);
            return id;
        }
        var value = info[id]['name'];

        return value;
    }

    function resetUpdate(setOriginal) {
        if (setOriginal == null) {
            setOriginal = true;
        }
        if (setOriginal) {

            OriginalRecordSet = $.extend({}, OriginalRecordSet, rset);
        }

        rset = {};
        changeByColumn = {};

        if (pkField !== -1) {
            setItem(pkField, pkCode);
        }

        isChangedVar = false;

    }

// funcoes available para usuarios!!
    function getRecordtSet() {
        return rset;
    }
    ;

    function getOriginal() {
        return OriginalRecordSet;
    }

    function refreshAll() {
        start();
    }

    function refresh(id) {
        toSetAsChanged = [];

        makeField(id);

        if (toSetAsChanged.length > 0) {
            setAsChanged(toSetAsChanged);
            toSetAsChanged = [];

        }
    }

    function setItem(id, vlr) {

        if (id == 'style' || id == 'style' + formSuffix) {
            return;
        }

        if (info[id] === undefined) {
            console.log('Error ID NOT FOUND - SetItem', id, info);
            //alert('Error ID NOT FOUND, CHECK CONSOLE');
            return;
        }

        // Span
        if (info[id].type === 'G') {
            $('#' + id).text(vlr);
            return;
        }

        isChangedVar = true;

        $('#' + id).val(vlr);
        $('#' + id).attr('value', vlr);

        if (id === pkField) {
            pkCode = vlr;
            rset['recid'] = pkCode;

            return;
        }


        if (changeByColumn[id] == undefined) {
            changeByColumn[id] = {};
        }
        changeByColumn[id]['data'] = vlr;
        changeByColumn[id]['type'] = info[id].type;
        changeByColumn[id]['field'] = info[id].name;
        changeByColumn[id]['order']     = info[id]['order'];
        changeByColumn[id]['indexRS']   = info[id]['indexRS'];


        if (info[id]['order'] === 'N') {
            rset[getRecordsetField(id)] = vlr;

        } else {
            // se ele tiver nulo preciso inicializar como array
            // se ele tiver nulo preciso inicializar como array
            if (rset[getRecordsetField(id)] === undefined) {
                rset[getRecordsetField(id)] = {};
            }

            if (rset[getRecordsetField(id)][info[id]['indexRS']] === undefined) {
                rset[getRecordsetField(id)][info[id]['indexRS']] = {};
            }
            rset[getRecordsetField(id)][info[id]['indexRS']][info[id]['order']] = vlr;

        }

        if (info[id].formated) {

            if (info[id].type == 'N') {
                if (isNaN(vlr)) {
                    vlr = 0;
                }
                $('#' + id).autoNumeric('set', vlr);
            } else {
                refresh(id);
            }

            if (info[id].type == 'IS') {
                $('#' + id).bootstrapSlider('setValue', vlr);
            }

        }

        if (autoCheckDemanded !== undefined && autoCheckDemanded) {
            checkDemanded(true, id);
        }

        if (info[id]['relatedUpdate'] !== undefined) {
            setAsChanged([info[id].relatedUpdate]);
        }

        if (info[id].type == 'CHK') {
            if (vlr == 1) {
                $('#' + id).iCheck('check');
            } else {
                $('#' + id).iCheck('uncheck');
            }

        }


    }

    function getItem(id) {
        if (id == pkField) {
            return pkCode;
        }


        if (info[id] == undefined) {
            console.error('ID Does not exist', id);
        }

        // Span
        if (info[id].type === 'G') {
            return $('#' + id).text();
        }

        if (info[id].type === 'DD') {
            return $('#' + info[id].id_dropdown + ' :selected').val();
        }


        var name = getRecordsetField(id);

        if (info[id]['order'] === 'N') {
            if (rset[name] != undefined) {
                return rset[name];
            } else {
                return OriginalRecordSet[name];
            }
            //return rset[name] || OriginalRecordSet[name];
        }

        var line = rset[name];

        // se esta aqui, eh pq tem varios com mesmo nome.. entao!
        if (line !== undefined &&
                line[info[id]['indexRS']] !== undefined &&
                line[info[id]['indexRS']][info[id]['order']] !== undefined) {
            return line[info[id]['indexRS']][info[id]['order']];
        }

        // estando aqui, busca do Original Recordset;

        if (OriginalRecordSet[name] !== undefined &&
                OriginalRecordSet[name][info[id]['indexRS']] !== undefined &&
                OriginalRecordSet[name][info[id]['indexRS']][info[id]['order']] !== undefined) {



            return OriginalRecordSet[name][info[id]['indexRS']][info[id]['order']];
        }

        // estando aqui, retorno  undefined
        return undefined;

    }

    function getItemPLCode(id) {

        if (info[id] == undefined) {
            console.error('ID Does not exist', id);
        }

        var name = info[id]['codefield'];

        if (info[id].type === 'DD') {
            return $('#' + info[id].id_dropdown).val();
        }

        if (info[id]['order'] === 'N') {
            if (rset[name] !== undefined) {
                return rset[name];
            } else {
                return OriginalRecordSet[name];
            }

        }

        var line = rset[name];


        // se esta aqui, eh pq tem varios com mesmo nome.. entao!
        if (line !== undefined &&
                line[info[id]['indexRS']] !== undefined &&
                line[info[id]['indexRS']][info[id]['order']] !== undefined) {
            return line[info[id]['indexRS']][info[id]['order']];
        }

        // estando aqui, busca do Original Recordset;
        if (OriginalRecordSet[name] !== undefined &&
                OriginalRecordSet[name][info[id]['indexRS']] !== undefined &&
                OriginalRecordSet[name][info[id]['indexRS']][info[id]['order']] !== undefined) {
            return OriginalRecordSet[name][info[id]['indexRS']][info[id]['order']];
        }


        // estando aqui, retorno  undefined
        return undefined;

    }

    function setItemPL(id, code, vlr) {
        setItem(id, vlr);
        setItemPLCode(id, code);

        if (info[id].type === 'PLD') {
            $('#' + id).val(vlr);
            $('#' + id).select2('data', {id: code, text: vlr});

        }
    }

    function setItemPLCode(id, vlr) {
        if (id == 'style' || id == 'style' + formSuffix) {
            return;
        }

        if (info[id] == undefined) {
            console.error('Error setItemPLCode - Id Not Found', id, vlr, info);
        }
        var name = info[id]['codefield'];
        //console.log(id, info[id], 'plsetcode');

        if (info[id].type === 'DD') {
            $('#' + info[id].id_dropdown).val(vlr);
        }

        isChangedVar = true;

        if (changeByColumn[id] == undefined) {
            changeByColumn[id] = {};
        }
        changeByColumn[id]['code']      = vlr;
        changeByColumn[id]['type']      = info[id].type;
        changeByColumn[id]['codefield'] = info[id].codefield;
        changeByColumn[id]['order']     = info[id]['order'];
        changeByColumn[id]['indexRS']   = info[id]['indexRS'];
        

        if (info[id]['order'] === 'N') {
            rset[name] = vlr;
        } else {
            // se ele tiver nulo preciso inicializar como array
            if (rset[name] === undefined) {
                rset[name] = {};
            }

            if (rset[name][info[id]['indexRS']] === undefined) {
                rset[name][info[id]['indexRS']] = {};
            }

            rset[name][info[id]['indexRS']][info[id]['order']] = vlr;

        }

        if (info[id]['relatedUpdate'] !== undefined) {
            setAsChanged([info[id].relatedUpdate]);
        }


        $('#' + id).attr('plCode', vlr);

        //console.log(rset, 'dentro');
    }

    function getOriginalItem(id) {
        if (info[id] === undefined) {
            return undefined;
        }

        var name = getRecordsetField(id);

        if (info[id]['order'] === 'N') {
            return OriginalRecordSet[name];
        }

        // estando aqui, busca do Original Recordset;
        if (OriginalRecordSet[name] !== undefined &&
                OriginalRecordSet[name][info[id]['indexRS']] !== undefined &&
                OriginalRecordSet[name][info[id]['indexRS']][info[id]['order']] !== undefined) {
            return OriginalRecordSet[name][info[id]['indexRS']][info[id]['order']];
        }

        return undefined;
    }


    function getOriginalItemPL(id) {
        if (info[id] === undefined) {
            return undefined;
        }

        var name = info[id]['codefield'];

        if (info[id]['order'] === 'N') {
            return OriginalRecordSet[name];
        }

        // estando aqui, busca do Original Recordset;
        if (OriginalRecordSet[name] !== undefined &&
                OriginalRecordSet[name][info[id]['indexRS']] !== undefined &&
                OriginalRecordSet[name][info[id]['indexRS']][info[id]['order']] !== undefined) {
            return OriginalRecordSet[name][info[id]['indexRS']][info[id]['order']];
        }

        return undefined;
    }

    function setOriginalItem(id, vlr) {

        var name = getRecordsetField(id);

        if (info[id]['order'] === 'N') {
            OriginalRecordSet[name] = vlr;
        } else {
            // se ele tiver nulo preciso inicializar como array
            // se ele tiver nulo preciso inicializar como array
            if (OriginalRecordSet[name] === undefined) {
                OriginalRecordSet[name] = {};
            }

            if (OriginalRecordSet[name][info[id]['indexRS']] === undefined) {
                OriginalRecordSet[name][info[id]['indexRS']] = {};
            }
            OriginalRecordSet[name][info[id]['indexRS']][info[id]['order']] = vlr;
        }

    }


    function setOriginalItemPL(id, vlr) {
        if (info[id] === undefined) {
            return undefined;
        }


        var name = info[id]['codefield'];

        if (info[id]['order'] === 'N') {
            OriginalRecordSet[name] = vlr;
        } else {
            // se ele tiver nulo preciso inicializar como array
            // se ele tiver nulo preciso inicializar como array
            if (OriginalRecordSet[name] === undefined) {
                OriginalRecordSet[name] = {};
            }

            if (OriginalRecordSet[name][info[id]['indexRS']] === undefined) {
                OriginalRecordSet[name][info[id]['indexRS']] = {};
            }

            OriginalRecordSet[name][info[id]['indexRS']][info[id]['order']] = vlr;
        }


    }


    function getChanges() {
        var ret = $.extend({}, rset);
        //ret['style'] = undefined;


        return ret;
    }


    function showForm() {
        $selector.show();
    }

    function hideForm() {
        $selector.hide();
    }


    function setEnabled(id, enabled) {
        if (info[id] == undefined) {
            console.log('ID not Found', id);
            return;
        }

        if (info[id].type == 'PL' || info[id].type == 'T' || info[id].type == 't') {
            pickListEnable(id, enabled);
        } else {
            $('#' + id).prop('disabled', !enabled);
        }

        if (info[id].type == 'PLD') {
            $('#' + id).select2('readonly', !enabled);

            var $vsel = $('#s2id_' + id);

            if (enabled) {
                $vsel.find('.select2-choice').css('background-color', picklistColor);
            } else {
                $vsel.find('.select2-choice').css('background-color', '');
            }


        }

        if (info[id].type == 'DD') {
            $('#' + info[id].id_dropdown).prop('disabled', !enabled);
        }

        if (info[id].type == 'CHK') {
            if (enabled) {
                $('#' + id).iCheck('enable');
            } else {
                $('#' + id).iCheck('disable');

            }

        }

    }

    function formToOriginal() {
        OriginalRecordSet = {};

        $.each(info, function (key, data) {
            var id = key;
            var $vkey = $('#' + key);

            // if this class exists means the select2 is running. so get the information from it.
            if (info[id].type === "PLD" && $vkey.hasClass('select2-offscreen')) {
                var vg = $vkey.select2("data");
                var value = vg.text;
            } else {
                var value = $vkey.val();
            }




            if (value == undefined || value == "") {
                return true;
            }

            if (id == pkField || id == 'recid') {
                OriginalRecordSet['recid'] = value;
                pkCode = value;
                return true;
            }

            if (info[id].type == 'I' || info[id].type == 'N') {
                value = $vkey.autoNumeric('get');
                value = parseFloat(value);
            }

            if (data['order'] === 'N') {
                OriginalRecordSet[getRecordsetField(id)] = value;
            } else {
                if (OriginalRecordSet[getRecordsetField(id)] === undefined) {
                    OriginalRecordSet[getRecordsetField(id)] = {};
                }

                if (OriginalRecordSet[getRecordsetField(id)][data['indexRS']] === undefined) {
                    OriginalRecordSet[getRecordsetField(id)][data['indexRS']] = {};
                }

                OriginalRecordSet[getRecordsetField(id)][data['indexRS']][data['order']] = value;
            }

            // se for PL, coloca o valor no codigo tambem!

            if (info[id].type === "PL" || info[id].type === "PLD") {

                var valuecode = $($vkey).attr('plCode');

                if (data['order'] === 'N') {
                    OriginalRecordSet[info[id].codefield] = valuecode;
                } else {
                    if (OriginalRecordSet[data.codefield] === undefined) {
                        OriginalRecordSet[data.codefield] = {};
                    }

                    if (OriginalRecordSet[info[id].codefield][data['indexRS']] === undefined) {
                        OriginalRecordSet[info[id].codefield][data['indexRS']] = {};
                    }


                    OriginalRecordSet[info[id].codefield][data['indexRS']][data['order']] = valuecode;
                }

            }




        });

    }

    function getColumnChanges() {
        return $.extend ({},changeByColumn );
    }

    function formToRecordSet() {
        rset = {};

        $.each(info, function (id, data) {
            var value = $(this).val();

            if (value == undefined || value == "") {
                return true;
            }

            if (id == pkField || id == 'recid') {
                rset['recid'] = value;
                pkCode = value;
                return;
            }

            if (changeByColumn[id] == undefined) {
                changeByColumn[id] = {};
            }
            changeByColumn[id]['data'] = value;
            changeByColumn[id]['type'] = info[id].type;
            changeByColumn[id]['order']     = info[id]['order'];
            changeByColumn[id]['indexRS']   = info[id]['indexRS'];


            if (data['order'] === 'N') {
                rset[getRecordsetField(id)] = value;
                
            } else {
                if (rset[getRecordsetField(id)] === undefined) {
                    rset[getRecordsetField(id)] = [];
                }
                rset[getRecordsetField(id)][data['order']] = value;
                
            }

            // se for PL, coloca o valor no codigo tambem!
            if (info[id].type == "PL" || info[id].type == "PLD") {

                var valuecode = $(this).attr('plCode');

                changeByColumn[id]['code'] = valuecode;

                if (data['order'] === 'N') {
                    rset[info[id].codefield] = valuecode;
                } else {
                    if (rset[info[id].codefield] === undefined) {
                        rset[info[id].codefield] = [];
                    }
                    rset[info[id].codefield][data['order']] = valuecode;
                }
            }

            isChangedVar = true;
        });
    }
    ;

    function setModified(id) {
        var value = $(this).val();
        setItem(id, value);
        // //// INCOMPLETO!

    }

    function isChanged() {
        var isChg = isChangedVar;

        $.each(gridToControl, function (index, value) {

            if (w2ui[value].getChanges().length > 0) {
                isChg = true;
                return false;
            }
        });

        return isChg;
    }

    function setController(contr) {
        updController = contr;
    }

    function checkDemandedWithMSG(changeBorder) {
        var err = checkDemanded(true);
        if (err.length > 0) {

            var errs = '<BR>';
            $.each(err, function (index, value) {
                if (errs.indexOf(value.title + '<br>') == -1) {
                    errs = errs + value.title + '<br>';
                }
            });

            messageBoxError(javaMessages.required_info + errs);
            return false;
        }

        return true;
    }


    function updateForm() {


        var pictHasChanges = false;
        var retFormData = [];

        if (cgbFileUploadObj !== null) {
            retFormData = cgbFileUploadObj.retDataUploadJson();
            pictHasChanges = cgbFileUploadObj.isChanged();

        }

        if (autoCheckDemanded !== undefined && autoCheckDemanded) {
            var vok = checkDemandedWithMSG(true);

            if (!vok) {
                return;
            }
        }

        if (!isChanged() && !pictHasChanges) {

            return;
        }
        var ret = getChanges();

        //console.log(info, namevsid);

        if (updRemoveDescFields) {
            $.each(ret, function (key, value) {
                if (info[namevsid[key]] === undefined) {
                    return true;
                }
                ;

                if (info[namevsid[key]]['type'] === 'PL' || info[namevsid[key]]['type'] === 'PLD') {

                    delete ret[key];
                }
            });

        }

        var additionalData = {};
        var vCanUpdate = true;
        $.each(gridToControl, function (index, value) {
            var chg = w2ui[value].getChanges();

            var vMissingColumn = w2ui[value].checkDemanded();
            if (vMissingColumn !== '') {
                messageBoxError(javaMessages.msgMissingInformation + '<br>' + vMissingColumn);
                vCanUpdate = false;
            }


            if (chg.length > 0) {
                additionalData[value] = chg;
            } else {
                additionalData[value] = [];
            }
        });
        if (!vCanUpdate) {
            return;
        }

        var ev = $.extend({}, $.Event('beforeUpdate'), triggerEvent, {recordset: ret, cgbFileUpload: retFormData, additionalData: additionalData});
        $selector.trigger(ev);

        if (ev.isDefaultPrevented()) {
            return;
        }

        $.myCgbAjax({url: updController + "/" + updFunction,
            box: $selectorLock,
            message: javaMessages.updating,
            data: {"upd": JSON.stringify(ret),
                'cgbFileUpload': JSON.stringify(retFormData),
                'additionalData': JSON.stringify(additionalData),
                'recid': pkCode
            },
            success: function (data) {
                if (data.status == "OK") {
                    toastSuccess(javaMessages.update_done);
                    resetUpdate();
                    resetPLCache();

                    if (cgbFileUploadObj !== null) {
                        cgbFileUploadObj.resetAllFiles();
                    }
                    ;

                    $.each(gridToControl, function (index, value) {
                        var chg = w2ui[value].mergeChanges();
                    });

                    waitMsgOFF($selectorLock);
                    ev = $.extend({}, $.Event('afterUpdate'), triggerEvent, {recordset: data.rs, fullData: data});
                    $selector.trigger(ev);


                    if (refreshAfterUpdate) {
                        retrieveForm(pkCode, true, data.rs);
                    }

                } else {
                    var ev = $.extend({}, $.Event('errorUpdate'), triggerEvent, {recordset: ret, cgbFileUpload: retFormData, additionalData: additionalData});
                    $selector.trigger(ev);

                    toastErrorBig(javaMessages.error_upd + data.status);
                    return;
                }

            },
            errorAfter: function () {
                var ev = $.extend({}, $.Event('errorUpdate'), triggerEvent, {recordset: ret, cgbFileUpload: retFormData, additionalData: additionalData});
                $selector.trigger(ev);
            }
        });


    }


    function addNewElements() {

        var $toAdd = $(this).find(' :input, span[mask="G"]').not(":input[type=button]").not('.select2-container').not('.select2-focusser').not('.select2-input');
        //var $toAdd = $('#' + selector + ' :input').not(":input[type=button]");

        // removo as info daqueles que nao tem mais no form
        $.each(info, function (index, value) {
            if (value.checkReload !== 'Y') {
                return true;
            }
            //console.log($toAdd, index, $toAdd.filter('#'+index).length) ;

            if ($toAdd.filter('#' + index).length == 0) {
                delete info[index];
            }

        });

        toSetAsChanged = [];
        $toAdd.each(function () {
            var id = $(this).attr('id');
            var started;

            if (id === undefined) {
                return true;
            }

            started = $('#' + id).attr('isMyFormStarted');
            if (started === undefined) {
                started = 'N';
            }

            if (info[id] === undefined || started == 'N') {

                makeField(id, {});

            }
        });

        if (toSetAsChanged.length > 0) {
            setAsChanged(toSetAsChanged);
        }

    }

    function retrieveForm(pk, fromUpdate, rset) {
        if (fromUpdate == undefined) {
            fromUpdate = false;
        }

        var ev = $.extend({}, $.Event('beforeRetrieve'), triggerEvent, {});
        $selector.trigger(ev);

        if (ev.isDefaultPrevented()) {
            return;
        }

        // se recebeu o rset, quer dizer que eh pra atualizar dele, nao do retrieve!
        if (rset !== undefined) {
            // adiciono no form!!
            recordsetToForm(rset[0]);
            formToOriginal();
            resetUpdate();
            resetPLCache();

            ev = $.extend({}, $.Event('afterRetrieve'), triggerEvent, {recordset: rset});


            // se vem do update eh esta setado para atualizar o grid!!!
            if (fromUpdate && gridToSetAfterUpd !== undefined) {
                var orig = getOriginal();
                if (gridToSetAfterUpd.get(pkCode) === null) {
                    gridToSetAfterUpd.add(rset[0]);
                } else {
                    gridToSetAfterUpd.set(pkCode, rset[0]);
                }
            }

            return;

        }


        waitMsgON($selectorLock, true, javaMessages.retrieveData);

        $.post(
                updController + "/" + retFunction + '/' + pk,
                {},
                function (data) {

                    if (data.logged == "Y") {
                        recordsetToForm(data.resultset[0]);
                        formToOriginal();
                        resetUpdate();
                    } else {
                        sessionTimeOut();
                        return;
                    }

                    ev = $.extend({}, $.Event('afterRetrieve'), triggerEvent, {recordset: data.resultset[0]});
                    waitMsgOFF($selectorLock);
                    $selector.trigger(ev);

                    // se ele vem do update, e tem grid setado, insiro/atualizo conforme form.
                    if (fromUpdate && gridToSetAfterUpd !== undefined) {
                        var orig = getOriginal();
                        if (gridToSetAfterUpd.get(pkCode) == null) {
                            gridToSetAfterUpd.add(orig);
                        } else {
                            gridToSetAfterUpd.set(pkCode, orig);
                        }
                    }


                },
                "json"
                );
    }

    function getPk() {
        return pkCode;
    }

    function disableAll() {

        $.each(info, function (id, value) {
            if (info[id].type == "PK") {
                return true;
            }

            setEnabled(id, false);
        });
    }

    function enableAll() {

        $.each(info, function (id, value) {
            if (info[id].type == "PK") {
                return true;
            }

            setEnabled(id, true);
        });
    }

    function clearForm() {

        $.each(info, function (id, value) {

            $('#' + id).val('');

            if (value.type == 'G') {
                $('#' + id).text('');
            }

            if (value.type == 'PLD') {
                $('#' + id).attr('plCode', '');
                $('#' + id).select2('val', '');

            }




        });

        OriginalRecordSet = {};

        rset = {};
        changeByColumn = {};
        resetUpdate();
        resetPLCache();
    }

    function setPLRelCode(id, code) {
        $('#' + id).attr('relcode', code);
        info[id].relcode = code;

        return true;
    }

    function setAsChanged(fields) {

        if (fields === undefined) {
            fields = [];
            $.each(info, function (key, value) {

                // ignoro os read only.
                if (value.type === 'RO') {
                    return true;
                }

                fields.push(key);
            });
        }
        ;



        $.each(fields, function (key, value) {


            if (info[value]['type'] === 'PK') {
                return true;
            }


            var myData = getOriginalItem(value);

            if (myData === undefined) {
                myData = getItem(value);
            }


            if (myData != undefined) {
                setItem(value, myData);
            }


            if (info[value]['type'] === 'PL' || info[value]['type'] === 'PLD') {
                myData = getOriginalItemPL(value);
                if (myData === undefined) {
                    myData = getItemPLCode(value);
                }

                if (myData != undefined) {
                    setItemPLCode(value, myData);
                }
            }


        });




    }

    function addPlButton(id) {

        var idButton = id + '_pl';

        if ($('#' + idButton).length) {
            return;
        }

        $inp = $('#' + id);

        $inp.wrap('<div class="input-group"style="width: 100%;cursor: pointer;"></div>');

        html = '<span class="input-group-addon input-sm" style="min-width: 20px;" id="' + idButton + '"><i class="fa fa-clone" style="width: 16px;"></i></span>';

        $inp.after(html);
        if ($inp.css('display') == 'none') {
            $('#' + idButton).hide();
        }


        $('#' + idButton).on('click', function () {
            $('#' + id).dblclick();
        });
    }


    function checkDemanded(changeBorder, id) {
        if (changeBorder === undefined) {
            changeBorder = false;
        }

        var newInfo;

        if (id === undefined) {
            newInfo = info;
        } else {
            newInfo = {};
            newInfo[id] = info[id];
        }



        var errord = [];
        var vHint = [];

        $.each(newInfo, function (key, value) {
            var $vField;
            if (info[key].type == 'PLD') {
                var $vField = $('#' + key).parent().find('.select2-choice');
            } else {
                var $vField = $('#' + key);
            }


            if (value['demanded'] === 'Y' && $vField.length > 0 && ($vField.is(':visible') || checkDemandedForInvisible)) {
                var vl_informed = isInformed(key);

                if (!vl_informed) {
                    errord.push({id: key, title: value['title']});

                    if (changeBorder) {
                        $vField.addClass('alertFormInputMissing');
                        vHint.push({element: '#' + key, hint: 'Demanded'});
                    }
                } else {
                    if ($vField.hasClass('alertFormInputMissing')) {
                        $vField.removeClass('alertFormInputMissing');
                        $vField.addClass('successFormInputMissing');

                        setTimeout(function () {
                            $vField.removeClass("successFormInputMissing");
                        }, 400);


                    }
                    ;
                }


            }
        });

        return errord;

        //$( "selector" ).switchClass( "oldClass", "newClass", 1000, "easeInOutQuad" );

    }

    function getGridFromControl() {
        return gridToControl;
    }

    function clearGridFromControl() {
        gridToControl = [];
    }


    function addGridToControl(gridName) {
        gridToControl.push(gridName);
    }

    function removeGridFromControl(gridName) {

        if (gridName == undefined) {
            gridToControl = [];
        }

        var indexof = gridToControl.indexOf(gridName);
        if (indexof !== -1) {
            gridToControl.splice(indexof, 1);
        }

    }


    function getIdbyFieldname(fieldname) {
        var ids = [];

        $.each(info, function (index, value) {

            if (value.name === fieldname) {
                ids.push(index);
            }


        });

        return ids;

    }

    function getInfobyFieldname(fieldname) {
        var ids = [];

        $.each(info, function (index, value) {

            if (value.name === fieldname) {
                value['id'] = index;
                ids.push(value);
            }


        });

        return ids;

    }


    function isInformed(id) {

        var vl = chkUndefined(getItem(id), '').toString().trim();


        if (vl != -1 && vl != -2 && vl !== '') {
            return true;
        } else {
            return false;
        }


    }

    function setDemanded(id, demanded) {
        if (demanded) {
            var yesno = 'Y';
        } else {
            var yesno = 'N';
        }
        info[id].demanded = yesno;
        $('#' + id).attr('attr', info[id].demanded);
        $('#' + id).removeClass("successFormInputMissing");
        $('#' + id).removeClass("alertFormInputMissing");

    }

    function getFieldInfo(fieldname) {
        return info[fieldname];
    }

    function getSelector() {
        return $selector;
    }

    function getFieldId(fieldname, order, indexRS) {
        if (order == undefined) {
            order = 'N';
        }

        if (indexRS == undefined) {
            indexRS = 0;
        }


        return vNameData[fieldname][order][indexRS];

    }

    function isDisabled(fieldname, order, indexRS) {
        if (order !== undefined || indexRS !== undefined) {
            fieldname = getFieldId(fieldname, order, indexRS);
        }

        return $('#' + fieldname).prop('disabled');

    }

    function destroy(async) {
        if (async == undefined) {
            async = true;
        }

        $('.' + vUniqueId).remove();

        if (async) {
            setTimeout(function () {
                var vsel2dest = '#x';
                var vsel2btndest = '#x';

                $.each(info, function (id, value) {

                    if (value.type == 'PLD') {
                        vsel2dest = vsel2dest + ',#' + id;
                        vsel2btndest = vsel2btndest + ',#' + id + 'BtnRefresh' + ',' + id + 'BtnMaint';
                    }
                });


                var $btn = $(vsel2dest);
                $(vsel2dest).select2('destroy');
                $(vsel2btndest).remove();

                info = {};


            }, 5);



        } else {
            var vsel2dest = '#x';
            var vsel2btndest = '#x';

            $.each(info, function (id, value) {
                if (value.type == 'PLD') {
                    vsel2dest = vsel2dest + ',#' + id;
                    vsel2btndest = vsel2btndest + ',#' + id + 'BtnRefresh' + ',' + id + 'BtnMaint';
                }
            });

            $(vsel2dest).select2('destroy');
            $(vsel2btndest).remove();
            info = {};

        }

        namevsid = {};
        rset = {};
        plFunctionText = openGenericText;
        changeTextFunction = changeTextfield;
        triggerEvent = {};
        isChangedVar = false;
        OriginalRecordSet = {};
        plOpenId = '';
        originalFromForm = true;
        pkCode = -1;
        pkField = -1;
        updFunction = 'updateDataJsonForm';
        retFunction = 'retrieveGridJsonForm';
        formSize = 'original';
        formSuffix = '_form';
        cgbFileUploadObj = null;
        updRemoveDescFields = true;
        autoCheckDemanded = undefined;
        gridToControl = [];
        toSetAsChanged = [];
        vNameData = {};
        boxToLock = '';
        pldData = {};
    }

    this.getFieldInfo = getFieldInfo;
    this.getFieldId = getFieldId;
    this.isDisabled = isDisabled;
    this.refreshAll = refreshAll;
    this.refresh = refresh;
    this.setItem = setItem;
    this.getItem = getItem;
    this.getOriginalItem = getOriginalItem;
    this.getChanges = getChanges;
    this.setEnabled = setEnabled;
    this.formToOriginal = formToOriginal;
    this.getRecordtSet = getRecordtSet;
    this.getOriginal = getOriginal;
    this.resetUpdate = resetUpdate;
    this.recordsetToForm = recordsetToForm;
    this.formToRecordSet = formToRecordSet;
    this.setController = setController;
    this.updateForm = updateForm;
    this.retrieveForm = retrieveForm;
    this.getPk = getPk;
    this.disableAll = disableAll;
    this.enableAll = enableAll;
    this.clearForm = clearForm;
    this.setPLRelCode = setPLRelCode;
    this.isChanged = isChanged;
    this.getItemPLCode = getItemPLCode;
    this.setItemPLCode = setItemPLCode;
    this.setCgbFileUploadObj = setCgbFileUploadObj;
    this.addNewElements = addNewElements;
    this.setAsChanged = setAsChanged;
    this.checkDemanded = checkDemanded;
    this.addGridToControl = addGridToControl;
    this.setOriginalItem = setOriginalItem;
    this.setOriginalItemPL = setOriginalItemPL;
    this.getIdbyFieldname = getIdbyFieldname;
    this.isInformed = isInformed;
    this.removeGridFromControl = removeGridFromControl;
    this.setDemanded = setDemanded;
    this.openPicklist = openPicklist;
    this.rset = rset;
    this.OriginalRecordSet = OriginalRecordSet;
    this.showForm = showForm;
    this.hideForm = hideForm;
    this.getSelector = getSelector;
    this.forceItemChangedEvent = forceItemChangedEvent;
    this.setItemPL = setItemPL;
    this.resetPLCache = resetPLCache;
    this.checkDemandedWithMSG = checkDemandedWithMSG;
    this.destroy = destroy;
    this.getInfobyFieldname = getInfobyFieldname;

    this.getGridFromControl = getGridFromControl;

    this.clearGridFromControl = clearGridFromControl;
    this.getColumnChanges = getColumnChanges;


    return this;
};

