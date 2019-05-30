<!-- Confirm -->


<style>
    .imgCropped {
        max-width: 100%; /* This rule is very important, please do not ignore this! */
        max-height: 500px;
        object-fit: contain;
    }

    img.loaded {
        max-width: 100%; /* This rule is very important, please do not ignore this! */
        max-height: 500px;
        object-fit: contain;
    }

    img.img_signature {
        max-width: 100%; /* This rule is very important, please do not ignore this! */
        max-height: 500px;
        object-fit: contain;
    }


    .center {
        margin: 0 auto;
        width: 100%;
    }

    .myNumberInfo {
        width: 100%;
        text-align: right;
        vertical-align: central;

        background-color: transparent;
        border: none;
        text-align: center;
    }

    .myImg {
        width: 100px; 
        height: 100px;
    }

    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; 
        margin: 0; 
    }

    .tableAqlTitle {
        border: black solid thin !important;   
        text-align: center;
        background-color: lightgoldenrodyellow;
    }

    .tableAqlRow {
        border: black solid thin !important;   
        text-align: center;
    }

    .tableAql {
        margin-bottom: 5px !important;
    }

    .selectedStatus {
        border: #000099 thick outset !important;
        font-weight: bold;
    }

    .inspButtons {
        font-size: 1.7em;
        margin-right: 15px;

    }

    .btnDefectArea {
        font-size: 1.5em;

    }

</style>


<script>

    function messageBoxYesNo(description, functionOk, functionCancel) {
        //hideScrollBeforeModal();

        if (functionCancel == undefined) {
            functionCancel = function () {};
        }

        $.confirm({
            title: 'Confirm',
            theme: 'material',
            backgroundDismiss: false,
            content: description,

            buttons: {
                confirm: {
                    text: 'YES',
                    btnClass: 'btn-info',
                    action: functionOk
                },
                cancel: {
                    text: 'NO',
                    btnClass: 'btn-danger',

                    action: functionCancel
                }
            },

            columnClass: 'col-md-6 col-md-offset-3 messageBoxCGBClass'

        });

    }

    var dsInspFormObj = new function () {
        var thisObj = this;

        this.setMain = function () {
            $(thisObj.v_divToForm).append(thisObj.mainForms);


            $('#def_edit_panel').find('.bt-pannel-set-button').on('click', function () {

                var vfld = $(this).attr('fld');
                var vact = $(this).attr('act');
                var vvalue = parseInt($('#' + vfld).val());
                if (vact === '+') {
                    vvalue = vvalue + 1;
                } else {
                    vvalue = vvalue - 1;
                }

                if (vvalue < 0) {
                    vvalue = 0;
                }

                $('#' + vfld).val(vvalue);
            });



        }

        this.start = function (options) {

            opt = $.extend({divToForm: '#', divToHide: '#', funcAfterClose: function () {}}, options);

            thisObj.v_divToForm = opt.divToForm;
            thisObj.v_divToHide = opt.divToHide;
            thisObj.funcAfterClose = opt.funcAfterClose;
            thisObj.mainForms = $('#row_po_info').detach();

            thisObj.v_signature_form = makeDivString('#signature_pad');
            thisObj.URL = window.URL || window.webkitURL;
            thisObj.v_inspClosed = false;
            thisObj.v_isSignatureOn = false;
            thisObj.v_empty_signature;
            thisObj.v_is_changed = false;
            thisObj.v_selected_defect = 0;
            thisObj.v_defectmodal;
            thisObj.v_signature_modal;
            thisObj.v_imgmodal;
            thisObj.v_changes = {};
            thisObj.v_insp_values = {};
            thisObj.v_sel_def_code = -1;
            thisObj.v_sel_def_desc_cn = '';
            thisObj.v_sel_def_desc_en = '';
            thisObj.v_sel_def_group = '';
            thisObj.v_sel_Aql = {};
            thisObj.v_can_approve = true;
            thisObj.cropper;
            thisObj.v_defect_images = [];
            thisObj.v_selected_images = [];
            thisObj.v_def_selected_area = []
            thisObj.v_img_carousel;
            thisObj.v_cd_inspection;
            thisObj.v_others_pk = -1;
            thisObj.strVar = '';
            thisObj.v_img_carousel = '';
            thisObj.v_defect_area_for_others = -99;
            thisObj.v_defect_area_for_inspection = -98;

            thisObj.checkedToClose = false;
            thisObj.v_sigType = '';
            thisObj.v_closed_supervisor = '';
            thisObj.v_pairs_to_inspect;
            thisObj.v_img_carousel = makeDivString('#imgCarousel');
            thisObj.strVar = makeDivString('#row_edit_panel');
            thisObj.strBatchPad = makeDivString('#batchSignature');
            thisObj.strPackingInstruction = makeDivString('#plData');
            thisObj.cfcPath = 'apps/inspection_control_test/';
        };



        this.OpenInspection = function (cd_po, cd_inspection) {

            waitMsgON('body');
            thisObj.v_insp_values = {};
            thisObj.v_changes = {};
            thisObj.v_inspClosed = false;
            thisObj.v_isSignatureOn = false;
            thisObj.v_is_changed = false
            thisObj.v_insp_values = {};
            thisObj.v_can_approve = true;
            thisObj.v_defect_images = [];
            thisObj.v_selected_images = [];
            thisObj.v_def_selected_area = [];
            thisObj.v_readonly = false;
            thisObj.v_role = '';
            thisObj.v_packing_url = '';


            $.ajax({
                type: "post",
                url: thisObj.cfcPath + "inspection.cfc",
                data: {
                    method: "createInspectionDetails",
                    p: cd_po,
                    cd_inspection: cd_inspection
                },
                dataType: "json",
                success: function (response) {

                    $('#btnFailed').removeClass('disabled');
                    $('#btnFailedSup').removeClass('disabled');
                    $('#btnIdle').removeClass('disabled');
                    $('#btnIdleSup').removeClass('disabled');
                    $('#btnPassed').removeClass('disabled');
                    $('#btnPassedSup').removeClass('disabled');

                    $('#ds_tc_comments').prop('readonly', false);
                    $('#ds_factory_actions').prop('readonly', false);

                    thisObj.v_packing_url = response.po.ds_packing_url;
                    thisObj.vPackingInstructionOptions = response.packing_instruction;

                    console.log(response);
                    $form = $('#po_form');
                    $form.find('#nr_cont_form').val(response.po.nr_cont);
                    $form.find('#ds_shoe_box_type_form').val(response.po.ds_shoe_box_type);
                    $form.find('#ds_chiefstyle_form').val(response.po.ds_chiefstyle);
                    $form.find('#ds_customer_form').val(response.po.ds_customer);
                    $form.find('#ds_factory_form').val(response.po.ds_factory);
                    $form.find('#ds_final_customer_form').val(response.po.ds_final_customer);
                    $form.find('#itremarks').val(response.po.remark);
                    $form.find('#itinstrautorization').val(response.po.ds_instruction);
                    $form.find('#ds_style_form').val(response.po.ds_style);
                    $form.find('#nr_cont_form').val(response.po.nr_cont);
                    $form.find('#nr_customer_po_number_form').val(response.po.nr_customer_po_number);
                    $form.find('#ds_color_form').val(response.po.ds_color);
                    $form.find('#nr_qt_pairs_form').val(response.po.nr_total_pairs + ' - ' + response.po.nr_total_boxes);
                    $('#ds_tc_comments').val(response.ds_tc_comments);
                    $('#ds_factory_actions').val();
                    $('#ds_supervisor_comments').val('');

                    $("#imageShoes").attr("src", response.po.ds_name_bmp);
                    var aqlbody = $('#aql_selected_tbody');
                    $('#aql_selected_tbody *').off();
                    aqlbody.empty();
                    $('#row_defect_info').off();
                    $('#row_defect_info').empty();
                    thisObj.v_cd_inspection = response.cd_inspection;
                    thisObj.v_pairs_to_inspect = response.po.nr_pairs_to_inspect;
                    // monto

                    $.each(response.aql_standard, function (index, value) {

                        if (value.cd_aql_standard == response.po.cd_aql_standard) {

                            var aql_info = '<tr>';
                            aql_info += '<td class="tableAqlRow" style="font-size: 1.5em; font-weight: bold; color: green;border-left: black solid thin !important;">' + response.po.nr_pairs_to_inspect + '</td>';
                            aql_info += '<td class="tableAqlRow" style="border-left: black solid thin !important;">' + value.nr_pairs_cr_pass + '</td>';
                            aql_info += '<td class="tableAqlRow">' + value.nr_pairs_cr_fail + '</td>';
                            aql_info += '<td class="tableAqlRow" id="tableAqlActCr" style="border-right: black solid thin !important;">0</td>';
                            aql_info += '<td class="tableAqlRow style="border-left: black solid thin !important;"">' + value.nr_pairs_mj_pass + '</td>';
                            aql_info += '<td class="tableAqlRow">' + value.nr_pairs_mj_fail + '</td>';
                            aql_info += '<td class="tableAqlRow" id="tableAqlActMj" style="border-right: black solid thin !important;">0</td>';
                            aql_info += '<td class="tableAqlRow style="border-left: black solid thin !important;"">' + value.nr_pairs_mi_pass + '</td>';
                            aql_info += '<td class="tableAqlRow">' + value.nr_pairs_mi_fail + '</td>';
                            aql_info += '<td class="tableAqlRow" id="tableAqlActMi" style="border-right: black solid thin !important;">0</td>';
                            aql_info += '</tr>';
                            aqlbody.append(aql_info);
                            thisObj.v_sel_Aql = value;
                        }


                    });
                    //console.log("I Confirm that I've inspected all needed pairs (" + v_sel_Aql.nr_pairs_to_inspect + ') and confirm the Inspection Status selected above');
                    //Esconde o data_box;
                    $(thisObj.v_divToHide).hide();
                    // mostra o #row_po_info
                    $('#row_po_info').slideDown();
                    $.each(response.defect_areas, function (index, value) {

                        var vhtml = '<div class="col-sm-6" > ' +
                                '<div class="box  box-primary box-solid">' +
                                '<div class="box-header with-border">' +
                                '<h4 class="box-title defect_group">' + value.ds_defect_area + '</h4>' +
                                '<div class="box-tools pull-right">';
                        console.log('lendo', value.cd_defect_area, thisObj.v_defect_area_for_others);
                        if (parseInt(value.cd_defect_area) === thisObj.v_defect_area_for_others) {
                            vhtml = vhtml + '<button type="button" class="btn btn-box-tool" code="' + value.cd_defect_area + '"  id="ins_others' + value.cd_defect_area + '" onclick="dsInspFormObj.insertOthers();"><i class="fa fa-server add-others-defect btnDefectArea"></i></button>';
                        }

                        vhtml = vhtml + '<button type="button" class="btn btn-box-tool" code="' + value.cd_defect_area + '"  id="pict' + value.cd_defect_area + '" onclick="dsInspFormObj.openImages(this);"><i class="fa fa-camera cam-defect btnDefectArea"></i></button>' +
                                '</div>' +
                                '</div>' +
                                '<div class="box-body" style="background-color: #ecfcf3;"> ';
                        // Criacao da tabela:

                        vhtml = vhtml + '<table class="table table-condensed table-bordered" style="text-align: center;" id="deftables' + value.cd_defect_area + '">' +
                                '<thead>' +
                                '<tr>' +
                                '<th style="width: 100%;text-align: left;">Defect Description</th>' +
                                '<th style="width: 100px;">CR</th>' +
                                '<th style="width: 100px;">MJ</th>' +
                                '<th style="width: 100px;">MI</th>' +
                                '</tr>' +
                                '</thead>' +
                                '<tbody>';
                        vhtml = vhtml + thisObj.addInspDefect(value);
                        vhtml = vhtml + '</tbody> </table>' +
                                '</div>' +
                                '</div></div>';
                        $('#row_defect_info').append(vhtml);
                    });

                    $('.defect_row').on('click', function () {
                        thisObj.openSetProblems(this);
                    });

                    thisObj.v_defect_images = response.images;
                    $.each(thisObj.v_defect_images, function (index, values) {
                        $('#pict' + values.cd_insp_defect_area).css('color', 'red');
                    });



                    //response.user.role = 'S';
                    thisObj.v_role = response.user.role;

                    thisObj.calcDefects();

                    thisObj.setRights(response);

                    console.log(response);

                    var boxClass = 'box-primary';

                    if (response.cd_inspection_status_tc !== '') {
                        if (response.cd_inspection_status_tc == '1') {
                            boxClass = 'box-info';
                        } else {
                            boxClass = 'box-danger';
                        }
                    }

                    if (response.cd_inspection_status_superviso !== '') {
                        if (response.cd_inspection_status_superviso == '1') {
                            boxClass = 'box-success';
                        } else {
                            boxClass = 'box-danger';
                        }
                    }



                    $('#row_po_info').find('.box').removeClass('box-primary box-success box-danger box-info').addClass(boxClass);


                    waitMsgOFF('body');
                },
                error: function (error1, error2) {
                    messageBoxError('<div class="col-md-12">' + error1.responseText + '</div>');
                    waitMsgOFF('body');
                }
            });



        };

        this.setRights = function (response) {
            // controle dos dados que vao vir referente a status
            // inicio defaults;

            $('#passedInfo').hide();
            $('#supervisor_area').show();
            $('#ds_supervisor_comments').prop('readonly', true);
            $('#btnUpdate').show();

            $('#btnPassed').show();
            $('#btnPanelTC').show();
            $('#supervisor_area').show();

            $sig = $('#signatures');
            //$sig.hide();
            $sig.find("button").hide();
            $sig.find("img").hide();


            thisObj.setInspPassFailedSup($('#btnIdleSup'), false);

            if (response.tc_signature_url !== '') {
                $sig.find("#signature_tc_img").attr('src', response.tc_signature_url);
                $sig.find("#signature_tc_img").show();

            }
            if (response.fact_signature_url !== '') {
                $sig.find("#signature_factory_img").attr('src', response.fact_signature_url);
                $sig.find("#signature_factory_img").show();
            }

            console.log(response.user.role, response.fact_signature_url);

            if (response.user.role !== 'U' && response.fact_signature_url === '') {
                $sig.find('#signature_factory_btn').show();
            }

            if (response.user.role !== 'U' && response.tc_signature_url === '') {
                $sig.find('#signature_tc_btn').show();
            }

            // controle de TC
            if (response.cd_inspection_status_tc !== '') {
                thisObj.v_readonly = true;

                $('#btnPanelTC').hide();
                $('#ds_tc_comments').prop('readonly', true);
                $('#ds_factory_actions').prop('readonly', true);


            } else {
                // mesmo sendo supervisor, se nao tiver OK do TC, o proprio OK ali faz o update dos dois!
                $('#supervisor_area').hide();
            }

            // Controle de Supervisor
            if (response.cd_inspection_status_superviso !== '') {
                $('#supervisor_area').hide();
                thisObj.v_readonly = true;

            }

            // controle conforme usuario
            if (response.user.role === 'S') {
                if (response.cd_inspection_status_superviso === '') {
                    $('#ds_supervisor_comments').prop('readonly', false);
                }
            }
            ;

            if (response.user.role === 'U') {
                thisObj.v_readonly = true;

                $('#btnPanelTC').hide();
                $('#supervisor_area').hide();
                $sig.find("button").hide();

                $('#btnPanelTC').hide();
                $('#ds_tc_comments').prop('readonly', true);
                $('#ds_factory_actions').prop('readonly', true);

            }

            if (response.user.role === 'T') {
                $('#supervisor_area').hide();
            }


            if (response.user.role == 'S' && response.cd_inspection_status_tc == '1' && response.fl_signed == 'N' && response.cd_inspection_status_superviso == '') {


                //$('#btnPanelTC').hide();
                $('#supervisor_area').hide();
                $sig.find("button").hide();

                $('#btnPanelTC').find("button").prop('readonly', true);
                $('#ds_tc_comments').prop('readonly', true);
                $('#ds_factory_actions').prop('readonly', true);
                $('#ds_supervisor_comments').prop('readonly', true);
                $('#btnUpdate').prop('disabled', true);
            }




        };

        this.addInspDefect = function (defect_area) {

            vhtml = '';
            $.each(defect_area.defects, function (index2, value2) {

                vborderStyle = "";
                if (index2 !== defect_area.defects.length - 1) {
                    vborderStyle = 'border-bottom: grey dashed 2pt';
                }
                ;
                vhtml = vhtml + '<tr style="' + vborderStyle + '" class="defect_row" cd_inspection_defect="' + value2.cd_inspection_defect + '" descen="' + value2.ds_defect_en + '" desccn="' + value2.ds_defect_cn + '" grp="' + defect_area.ds_defect_area + '" cdgrp="' + defect_area.cd_defect_area + '">' +
                        '<td style="text-align: left" id="defect_info' + value2.cd_inspection_defect + '">' + value2.ds_defect_en + ' ' + value2.ds_defect_cn + '</td>' +
                        '<td style="" class="myNumberInfo" id="cr' + value2.cd_inspection_defect + '">' + value2.nr_pairs_problem_cr + '</td>' +
                        '<td style="" class="myNumberInfo" id="mj' + value2.cd_inspection_defect + '">' + value2.nr_pairs_problem_mj + '</td>' +
                        '<td style="" class="myNumberInfo" id="mi' + value2.cd_inspection_defect + '">' + value2.nr_pairs_problem_mi + '</td></tr>';
                mi = parseInt(value2.nr_pairs_problem_mi);
                mj = parseInt(value2.nr_pairs_problem_mj);
                cr = parseInt(value2.nr_pairs_problem_cr);
                if (mi > 0 || mj > 0 || cr > 0) {
                    thisObj.v_insp_values['id_' + value2.cd_inspection_defect] = {id: value2.cd_inspection_defect, mi: mi, mj: mj, cr: cr, def: value2.ds_defect_en + ' ' + value2.ds_defect_cn, area: defect_area.ds_defect_area};
                }


            });
            return vhtml;
        };

        this.openSetProblems = function (obj) {
            var teng = $(obj).attr('descen');
            var tcn = $(obj).attr('desccn');
            if (thisObj.v_readonly) {
                return;
            }

            thisObj.v_sel_def_code = $(obj).attr('cd_inspection_defect');
            thisObj.v_sel_def_desc_en = teng;
            thisObj.v_sel_def_desc_cn = tcn;
            thisObj.v_sel_def_group = $(obj).attr('grp');
            var mj_obj = $(obj).find("#mj" + thisObj.v_sel_def_code);
            var cr_obj = $(obj).find("#cr" + thisObj.v_sel_def_code);
            var mi_obj = $(obj).find("#mi" + thisObj.v_sel_def_code);
            thisObj.v_defectmodal = $.dialog({content: thisObj.strVar,
                title: false,
                closeIcon: false,
                backgroundDismiss: false,
                theme: 'supervan',
                columnClass: 'col-md-8 col-md-offset-2',
                onOpen: function () {
                    $("#descTitle").text(teng + ' ' + tcn);
                    $('#nr_minor_set').val(mi_obj.text());
                    $('#nr_major_set').val(mj_obj.text());
                    $('#nr_critical_set').val(cr_obj.text());
                    $('#def_edit_panel').find('.bt-pannel-set-button').on('click', function () {

                        var vfld = $(this).attr('fld');
                        var vact = $(this).attr('act');
                        var vvalue = parseInt($('#' + vfld).val());
                        if (vact == '+') {
                            vvalue = vvalue + 1;
                        } else {
                            vvalue = vvalue - 1;
                        }

                        if (vvalue < 0) {
                            vvalue = 0;
                        }

                        $('#' + vfld).val(vvalue);
                    });
                },
                onClose: function () {
                    thisObj.v_sel_def_code = -1;
                    thisObj.v_sel_def_desc_cn = '';
                    thisObj.v_sel_def_desc_en = '';
                    thisObj.v_sel_def_group = '';
                }


            });
        };


        this.updateImageComments = function () {
            //thisObj.imageComments['id' + values.cd_insp_defect_img] = {id: values.cd_insp_defect_img, ds_comments: values.ds_comments};
            var vcode = parseInt($('#carousel-images').find('div.active').attr('code'));
            var vcomments = $('#imgCommentsInput').val();


            if ($.trim(thisObj.imageComments['id' + vcode].ds_comments) === $.trim(vcomments)) {
                return;
            }

            $.ajax({
                type: "post",
                url: thisObj.cfcPath + "inspection.cfc",
                data: {
                    method: "updateInspectionImageComments",
                    image_id: vcode,
                    ds_comments: vcomments
                },
                dataType: "text",
                success: function (response) {

                    thisObj.imageComments['id' + vcode].ds_comments = $.trim(vcomments);
                    $.each(thisObj.v_defect_images, function (index, values) {
                        if (values.cd_insp_defect_img === vcode) {
                            thisObj.v_defect_images[index].ds_comments = vcomments;
                        }

                        waitMsgOFF('body');


                    });

                    messageBoxAlert('Comment Updated!');

                },
                error: function (error1, error2) {
                    messageBoxError('<div class="col-md-12">' + error1.responseText + '</div>');
                    waitMsgOFF('body');
                }
            });


        };


        this.uploadFinalImage = function () {
            if ($('#carousel-images').find('div.active').index() !== 0) {
                thisObj.updateImageComments();
                return;
            }

            if ($("#imageToUpload").attr('src') == '') {
                messageBoxAlert('Select the Image!');
                return;
            }

            if (thisObj.cropper.getData().width == 0) {
                messageBoxAlert('Select the Image Area!');
                return;
            }

            waitMsgON('body', true, 'Sending');
            var img = thisObj.cropper.getCroppedCanvas().toDataURL('image/jpeg');
            var base64ImageContent = img.replace(/^data:image\/(png|jpeg);base64,/, "");
            var blob = base64toBlob(base64ImageContent, 'image/jpeg', '');
            var vcomments = $('#imgCommentsInput').val();
            var formData = new FormData();
            formData.append('croppedImage', blob);
            formData.append('comments', vcomments);
            formData.append('cd_inspection_defect', thisObj.v_selected_defect);
            formData.append('method', 'uploadInspectionImageForm');
            formData.append('cd_inspection', thisObj.v_cd_inspection);
            formData.append('cd_defect_area', thisObj.v_def_selected_area);

            console.log(formData);

            $.ajax(thisObj.cfcPath + 'inspection.cfc', {
                method: "POST",
                data: formData,
                processData: false,
                cache: false,
                contentType: false,
                dataType: "json",
                success: function (response) {


                    thisObj.imageComments['id' + response[0].cd_insp_defect_img_new] = {};

                    thisObj.imageComments['id' + response[0].cd_insp_defect_img_new]['ds_comments'] = vcomments;


                    var vindicators = '<li data-target="#carousel-images" data-slide-to="0" class=""></li>';
                    var vitems = '<div class="item" code="' + response[0].cd_insp_defect_img_new + '">' +
                            '<div>' +
                            '<img src="' + img + '" alt="' + vcomments + '" class="loaded">' +
                            '<div class="carousel-caption"></div>' +
                            '</div></div>';
                    $('#carousel-images').find('.carousel-indicators').append(vindicators);
                    $('#carousel-images').find('.carousel-inner').append(vitems);
                    waitMsgOFF('body');
                    thisObj.cropper.destroy();
                    $('#imageToUpload').attr("src", "");
                    $('#imageToUpload').height(500);
                    var newimage = document.getElementById('imageToUpload');
                    thisObj.cropper = new Cropper(newimage, {
                        //aspectRatio: 16 / 9,
                        crop: function (e) {
                        }
                    });
                    $('#imgCommentsInput').val('');
                    waitMsgOFF('body');
                    thisObj.v_defect_images = response;
                    thisObj.changeImageStatus(thisObj.v_def_selected_area);
                    messageBoxAlert("Image Uploaded Successfully");
                },
                error: function (error1, error2) {
                    messageBoxError('<div class="col-md-12">' + error1.responseText + '</div>');
                    waitMsgOFF('body');
                }


            });
        };
        this.deleteImage = function () {
            $carousel = $('#carousel-images');
            var idx = $carousel.find('div.active').index();
            var total = $carousel.find('.item').length - 1;
            if (idx == 0) {
                return;
            }

            messageBoxYesNo('Confirm Delete Image', function () {


                var codePK = $carousel.find('.carousel-inner').find('div.active').attr('code');
                waitMsgON('body');
                $.ajax({
                    type: "post",
                    url: thisObj.cfcPath + "inspection.cfc",
                    data: {
                        method: "removeInspectionImage",
                        p: codePK,
                        cd_inspection: thisObj.v_cd_inspection
                    },
                    dataType: "json",
                    success: function (response) {

                        thisObj.v_defect_images = response;
                        thisObj.changeImageStatus(thisObj.v_def_selected_area);
                        var ActiveElement = $carousel.find('.item.active');
                        ActiveElement.remove();
                        var ActiveElement2 = $carousel.find('li.active');
                        ActiveElement2.remove();
                        if (idx === (total)) {
                            togo = idx - 1;
                        } else {
                            togo = idx;
                        }

                        $carousel.find('li').eq(togo).addClass('active');
                        $carousel.find('.item').eq(togo).addClass('active');

                        if (togo !== 0) {
                            var vcode = $carousel.find('div.active').attr('code');
                            $('#imgCommentsInput').val(thisObj.imageComments['id' + vcode].ds_comments);
                        } else {
                            $('#imgCommentsInput').val('');

                        }

                        ;


                        waitMsgOFF('body');




                    },
                    error: function (error1, error2) {
                        messageBoxError('<div class="col-md-12">' + error1.responseText + '</div>');
                        waitMsgOFF('body');
                    }
                });
            });
        };
        this.changeImageStatus = function (cd_insp_defect_area) {

            $('#pict' + cd_insp_defect_area).css('color', '');
            $.each(thisObj.v_defect_images, function (index, values) {

                if (cd_insp_defect_area === values.cd_insp_defect_area) {
                    $('#pict' + values.cd_insp_defect_area).css('color', 'red');
                    return false;
                }
            });
        };
        this.runChangeDefects = function () {
            var mj_obj = $("#mj" + thisObj.v_sel_def_code);
            var cr_obj = $("#cr" + thisObj.v_sel_def_code);
            var mi_obj = $("#mi" + thisObj.v_sel_def_code);
            var $lineObj = $("#defect_info" + thisObj.v_sel_def_code).closest('.defect_row');
            var area = $lineObj.attr('grp')
            var area_code = $lineObj.attr('cdgrp');
            var mi = parseInt($('#nr_minor_set').val());
            var mj = parseInt($('#nr_major_set').val());
            var cr = parseInt($('#nr_critical_set').val());
            if (mi > 0) {
                mi_obj.css('font-weight', 'bold');
            } else {
                mi_obj.css('font-weight', '');
            }
            ;
            if (cr > 0) {
                cr_obj.css('font-weight', 'bold');
            } else {
                cr_obj.css('font-weight', '');
            }
            ;
            if (mj > 0) {
                mj_obj.css('font-weight', 'bold');
            } else {
                mj_obj.css('font-weight', '');
            }
            ;
            mi_obj.text(mi);
            cr_obj.text(cr);
            mj_obj.text(mj);
            if (thisObj.v_changes['defect'] === undefined) {
                thisObj.v_changes['defect'] = {};
            }

            if (thisObj.v_changes['defect']['id_' + thisObj.v_sel_def_code] === undefined) {
                thisObj.v_changes['defect']['id_' + thisObj.v_sel_def_code] = {};
            }

            thisObj.v_changes['defect']['id_' + thisObj.v_sel_def_code]['id'] = thisObj.v_sel_def_code;
            thisObj.v_changes['defect']['id_' + thisObj.v_sel_def_code]['mi'] = mi;
            thisObj.v_changes['defect']['id_' + thisObj.v_sel_def_code]['mj'] = mj;
            thisObj.v_changes['defect']['id_' + thisObj.v_sel_def_code]['cr'] = cr;
            thisObj.v_changes['defect']['id_' + thisObj.v_sel_def_code]['area'] = area_code;
            console.log(thisObj.v_changes);
            thisObj.v_insp_values['id_' + thisObj.v_sel_def_code] = {id: thisObj.v_sel_def_code, mi: mi, mj: mj, cr: cr, def: $lineObj.attr('descen') + ' ' + $lineObj.attr('desccn'), area: area};
            thisObj.v_is_changed = true;


            console.log('runchangeDefect', thisObj.v_changes);

            thisObj.calcDefects();
            thisObj.v_defectmodal.close();
        }
        ;
        this.openFileSelector = function () {
            if ($('#carousel-images').find('div.active').index() !== 0) {
                $('#carousel-images').carousel(0);
            }


            $('#inputImage').click();
        };
        this.calcDefects = function () {
            var mi = 0;
            var mj = 0;
            var cr = 0;
            var htmlSummary = "";
            var cr_sum_style = "";
            var mj_sum_style = "";
            var mi_sum_style = "";
            $.each(thisObj.v_insp_values, function (index, value) {
                mi = mi + value.mi;
                mj = mj + value.mj;
                cr = cr + value.cr;
                // faco as cores!

                var styleCR = {"font-weight": "normal"};
                var styleMI = {"font-weight": "normal"};
                var styleMJ = {"font-weight": "normal"};
                var styleText = {"font-weight": "normal"};
                if (value.cr > 0) {
                    styleCR = {"font-weight": "bold"};
                    styleText = {"font-weight": "bold"};
                }
                ;
                if (value.mi > 0) {
                    styleMI = {"font-weight": "bold"};
                    styleText = {"font-weight": "bold"};
                }
                ;
                if (value.mj > 0) {
                    styleMJ = {"font-weight": "bold"};
                    styleText = {"font-weight": "bold"};
                }
                ;
                $("#mj" + value.id).css(styleMJ);
                $("#cr" + value.id).css(styleCR);
                $("#mi" + value.id).css(styleMI);
                $("#defect_info" + value.id).css(styleText);
                if (value.cr > 0 || value.mi > 0 || value.mj > 0) {
                    htmlSummary = htmlSummary + '<tr><td style="text-align: left;">' + value.area + '</td><td style="text-align: left;">' + value.def + '</td><td>' + value.cr + '</td><td>' + value.mj + '</td><td>' + value.mi + '</td></tr>';
                }


            });


            thisObj.v_can_approve = true;


            if (cr >= thisObj.v_sel_Aql.nr_pairs_cr_fail) {
                $('#aql_table_cr').css('background-color', 'red');
                thisObj.v_can_approve = false;
                cr_sum_style = "color: white;background-color:red;font-size: 1.5em;";
            } else {
                $('#aql_table_cr').css('background-color', '');
            }

            if (mi >= thisObj.v_sel_Aql.nr_pairs_mi_fail) {
                $('#aql_table_mi').css('background-color', 'red');
                thisObj.v_can_approve = false;
                mi_sum_style = "color: white;background-color:red;font-size: 1.5em;";
            } else {
                $('#aql_table_mi').css('background-color', '');
            }

            if (mj >= thisObj.v_sel_Aql.nr_pairs_mj_fail) {
                $('#aql_table_mj').css('background-color', 'red');
                thisObj.v_can_approve = false;
                mj_sum_style = "color: white;background-color:red;font-size: 1.5em;";
            } else {
                $('#aql_table_mj').css('background-color', '');
            }

            $('#tableAqlActCr').text(cr);
            $('#tableAqlActMi').text(mi);
            $('#tableAqlActMj').text(mj);
            $('#tableSummary').find('tbody').empty();
            $('#tableSummary').find('tbody').append(htmlSummary);
            var rows = $('#tableSummary tbody  tr').get();
            rows.sort(function (a, b) {

                var A = $(a).children('td').eq(0).text().toUpperCase();
                var B = $(b).children('td').eq(0).text().toUpperCase();
                if (A < B) {
                    return -1;
                }

                if (A > B) {
                    return 1;
                }

                return 0;
            });
            $.each(rows, function (index, row) {
                $('#tableSummary').children('tbody').append(row);
            });
            if (htmlSummary !== '') {
                $('#tableSummary').find('tbody').append('<tr style="font-weight: bold;"><td></td><td></td><td style="' + cr_sum_style + '">' + cr + '</td><td style="' + mj_sum_style + '">' + mj + '</td><td style="' + mi_sum_style + '">' + mi + '</td></tr>');
            }


            //supervisor pode aprovar mesmo com Fail
            if (thisObj.v_role === 'S') {
                thisObj.v_can_approve = true;
            }

            if (!thisObj.v_can_approve) {
                thisObj.setInspPassFailed($("#btnFailed"));
            } else {
                thisObj.setInspPassFailed($("#btnIdle"));
            }


        };
        this.openImages = function (obj) {

            thisObj.v_def_selected_area = parseInt($(obj).attr('code'));
            thisObj.v_selected_images = [];
            $.each(thisObj.v_defect_images, function (index, values) {

                if (values.cd_insp_defect_area == thisObj.v_def_selected_area) {
                    thisObj.v_selected_images.push(values);
                }
            });
            if (thisObj.v_selected_images.length == 0 && thisObj.v_readonly) {
                messageBoxAlert('There is no pictures for this area');
                return;
            }

            $obj_line = $(obj).closest('.box').find('.defect_row').first();

            if ($obj_line.length == 0) {
                thisObj.v_selected_defect = -1;
            } else {
                thisObj.v_selected_defect = $obj_line.attr('cd_inspection_defect');
            }

            console.log(thisObj.v_selected_defect, thisObj.v_def_selected_area);

            thisObj.v_imgmodal = $.dialog({content: thisObj.v_img_carousel,
                title: false,
                cancelButton: false,
                confirmButton: false,
                closeIcon: false,
                backgroundDismiss: false,
                columnClass: 'col-md-12',
                onOpen: function () {

                    if (thisObj.v_readonly) {
                        $('#imgToolBar').hide();
                        $('#imgCommentsInput').prop('readonly', true);
                    } else {
                        $('#imgToolBar').show();
                        $('#imgCommentsInput').prop('readonly', false);
                    }

                    var vindicators = "";
                    var vitems = "";
                    thisObj.imageComments = {};
                    $.each(thisObj.v_selected_images, function (index, values) {

                        thisObj.imageComments['id' + values.cd_insp_defect_img] = {id: values.cd_insp_defect_img, ds_comments: values.ds_comments};
                        var vsel = '';
                        if (index == 0) {
                            vsel = 'active';
                        }
                        vindicators = vindicators + '<li data-target="#carousel-images" data-slide-to="' + index + 1 + '" class="' + vsel + '"></li>';
                        vitems = vitems + '<div class="item ' + vsel + '"  code="' + values.cd_insp_defect_img + '">' +
                                '<div>' +
                                '<img src="' + values.ds_image_path + '">' +
                                '<div class="carousel-caption"></div>' +
                                '</div></div>';
                    });
                    if (thisObj.v_selected_images.length == 0) {
                        $('#carousel-images').find('.carousel-indicators').find('li').addClass('active');
                        $('#carousel-images').find('.carousel-inner').find('.item').addClass('active');
                    }

                    $('#carousel-images').find('.carousel-indicators').append(vindicators);
                    $('#carousel-images').find('.carousel-inner').append(vitems);
                    var newimage = document.getElementById('imageToUpload');
                    thisObj.cropper = new Cropper(newimage, {
//                        aspectRatio: 16 / 9,
                        crop: function (e) {
                        }
                    });
                    $('#carousel-images').on('slid.bs.carousel', function () {
                        currentIndex = $(this).find('div.active').index();
                        if (currentIndex == 0) {
                            $('#imgCommentsInput').val('');
                        } else {
                            var vcode = $(this).find('div.active').attr('code');
                            console.log(currentIndex);
                            $('#imgCommentsInput').val(thisObj.imageComments['id' + vcode].ds_comments);
                        }



                        // do something…
                    });
                    var inputImage = document.getElementById('inputImage');
                    var blobURL;
                    inputImage.onchange = function () {
                        var files = this.files;
                        var file;
                        if (thisObj.cropper && files && files.length) {
                            file = files[0];
                            if (/^image\/\w+/.test(file.type)) {
                                blobURL = URL.createObjectURL(file);
                                thisObj.cropper.reset().replace(blobURL);
                                inputImage.value = null;
                            } else {
                                window.alert('Please choose an image file.');
                            }
                        }
                    };
                    $('#carousel-images').carousel({
                        interval: 90000000
                    });

                    var vcode = $('#carousel-images').find('div.active').attr('code');
                    if (vcode !== undefined) {
                        $('#imgCommentsInput').val(thisObj.imageComments['id' + vcode].ds_comments);
                    }
                },
                onClose: function () {
                    $('#imgCommentsInput').val('');
                }});
        };

        this.setInspPassFailed = function (obj) {
            if (thisObj.v_readonly) {
                return;
            }
            ;
            var vid = $(obj).prop('id');
            if (!thisObj.v_can_approve && vid != 'btnFailed') {
                return;
            }

            $('#btnPassed').removeClass('disabled');
            $('#btnIdle').removeClass('disabled');
            if (!thisObj.v_can_approve) {
                $('#btnPassed').addClass('disabled');
                $('#btnIdle').addClass('disabled');
            }

            $('#btnFailed').removeClass('selectedStatus');
            $('#btnPassed').removeClass('selectedStatus');
            $('#btnIdle').removeClass('selectedStatus');
            $(obj).addClass('selectedStatus');
            if (vid == 'btnPassed') {
                v_ins_status = 'P';
                thisObj.v_is_changed = true;

            }

            if (vid == 'btnFailed') {
                v_ins_status = 'F';
                thisObj.v_is_changed = true;
            }

            if (vid == 'btnIdle') {
                v_ins_status = '';
            }

            thisObj.v_changes['fl_status'] = v_ins_status;

        };

        this.setInspPassFailedSup = function (obj, setChange) {

            var vid = $(obj).prop('id');

            $('#btnFailedSup').removeClass('selectedStatus');
            $('#btnPassedSup').removeClass('selectedStatus');
            $('#btnIdleSup').removeClass('selectedStatus');

            $(obj).addClass('selectedStatus');
            if (vid == 'btnPassedSup') {
                v_ins_status_l = 'P';
            }

            if (vid == 'btnFailedSup') {
                v_ins_status_l = 'F';
            }

            if (vid == 'btnIdleSup') {
                v_ins_status_l = undefined;
            }

            if (setChange) {
                thisObj.v_changes['supervisor_status'] = v_ins_status_l;
                thisObj.v_is_changed = true;
            }

            console.log(thisObj.v_changes);

        }

        this.addOnResultSet = function (field) {
            vid = $(field).prop('id');
            thisObj.v_changes[vid] = $(field).val();
            thisObj.v_is_changed = true;
        }




        this.updateInspection = function () {

            if (!thisObj.v_is_changed && !thisObj.checkedToClose) {
                messageBoxError('No Changes to be Saved');
                return;
            }

            var vchanges_send = $.extend({}, thisObj.v_changes);
            //vchanges_send['fl_closing'] = 'N';


            if ((vchanges_send['fl_status'] !== undefined && vchanges_send['fl_status'] !== '') ||
                    (thisObj.v_changes.supervisor_status !== undefined && thisObj.v_changes.supervisor_status !== '')
                    ) {
                thisObj.v_inspClosed = true;
                //vchanges_send['fl_closing'] = 'Y';
            }

            vchanges_send['cd_inspection'] = thisObj.v_cd_inspection;
            if (vchanges_send.defect === undefined) {
                vchanges_send.defect = [];
            } else {
                var newdefects = [];
                var berror = false;
                $.each(vchanges_send.defect, function (index, value) {

                    if (parseInt(value.area) === thisObj.v_defect_area_for_others && parseInt(thisObj.id) < 0 && (value.desc === undefined || value.desc === '')) {
                        messageBoxAlert('There new items at Others area without description. Please adjust and try again! ');
                        berror = true;
                        return false;
                    }

                    newdefects.push(value);
                });

                if (berror) {
                    return;
                }



                vchanges_send.defect = newdefects;
            }

            waitMsgON('body');
            $.ajax({
                type: "post",
                url: thisObj.cfcPath + "inspection.cfc",
                data: {
                    method: "saveInspection",
                    p: JSON.stringify(vchanges_send)
                },
                dataType: "json",
                success: function (response) {

                    v_close_after = thisObj.v_inspClosed;

                    thisObj.v_is_changed = false;
                    thisObj.v_changes = {};
                    if (v_close_after) {
                        thisObj.closeInspectNOW(true);
                    } else {
                        // rearranjo os others;
                        $('#deftables' + thisObj.v_defect_area_for_others).find('tbody').off();
                        $('#deftables' + thisObj.v_defect_area_for_others).find('tbody').empty();
                        $('#deftables' + thisObj.v_defect_area_for_others).find('tbody').append(thisObj.addInspDefect(response.others[0]));
                        $('#deftables' + thisObj.v_defect_area_for_others).find('.defect_row').on('click', function () {
                            thisObj.openSetProblems(this);
                        });
                        // removo os indices que sao negaticos dos outros (que sao novos e jah retornaram com as PKs corretas)
                        $.each(thisObj.v_insp_values, function (index, value) {
                            if (parseInt(value.id) < 0) {
                                delete thisObj.v_insp_values[index];
                            }

                        });
                        thisObj.calcDefects();
                    }

                    waitMsgOFF('body');
                },
                error: function (error1, error2) {
                    messageBoxError(error1.responseText);
                    waitMsgOFF('body');
                }
            });
        }

        this.closeInspect = function () {

            if (thisObj.v_is_changed) {
                messageBoxYesNo('There is changes on this Inpection. Confirm Close ?', function () {
                    thisObj.closeInspectNOW(false);
                });
                return;
            }
            ;
            thisObj.closeInspectNOW(false);
        };
        this.closeInspectNOW = function (fromSave) {
            $(thisObj.v_divToHide).show();
            $('#row_po_info').hide();
            ret = {justClosed: (thisObj.v_inspClosed && fromSave)};

            thisObj.v_is_changed = false;
            console.log('closeNow', ret);
            thisObj.funcAfterClose(ret);
        }
        ;
        this.insertOthers = function () {

            if (thisObj.checkedToClose || thisObj.v_readonly) {
                return;
            }

            thisObj.v_others_pk = thisObj.v_others_pk - 1;
            var vhtml = '<tr style="" class="defect_row" cd_inspection_defect="' + thisObj.v_others_pk + '" descen="" desccn="" grp="Others" cdgrp="' + thisObj.v_defect_area_for_others + '" id="othersRow' + thisObj.v_others_pk + '">' +
                    '<td style="text-align: left" id="defect_info' + thisObj.v_others_pk + '"><input type="text" id="inpOthers' + thisObj.v_others_pk + '" class="form-control vOtherInputs" placeholder="Defect" style="padding: 0px !important;height: 24px !important;"></td>' +
                    '<td style="" class="myNumberInfo" id="cr' + thisObj.v_others_pk + '">0</td>' +
                    '<td style="" class="myNumberInfo" id="mj' + thisObj.v_others_pk + '">0</td>' +
                    '<td style="" class="myNumberInfo" id="mi' + thisObj.v_others_pk + '">0</td></tr>';
            $('#deftables' + thisObj.v_defect_area_for_others).find('tbody').append(vhtml);
            // funcao do change no input;
            $('#inpOthers' + thisObj.v_others_pk).on('change', function () {
                var vinfo = $(this).val();
                var $tr = $(this).closest('tr');
                var v_pk = $tr.attr('cd_inspection_defect');


                $tr.attr('descen', vinfo);
                if (thisObj.v_changes['defect'] === undefined) {
                    thisObj.v_changes['defect'] = {};
                }
                ;

                if (thisObj.v_changes['defect']['id_' + v_pk] === undefined) {
                    thisObj.v_changes['defect']['id_' + v_pk] = {id: v_pk, mi: 0, mj: 0, cr: 0, area: thisObj.v_defect_area_for_others, def: vinfo, desc: vinfo};
                }

                if (thisObj.v_insp_values['id_' + v_pk] == undefined) {
                    thisObj.v_insp_values['id_' + v_pk] = {id: v_pk, mi: 0, mj: 0, cr: 0, def: vinfo, area: "Others"};
                }
                ;


                thisObj.v_insp_values['id_' + v_pk]['desc'] = vinfo;
                thisObj.v_insp_values['id_' + v_pk]['def'] = vinfo;
                thisObj.v_changes['defect']['id_' + v_pk]['def'] = vinfo;
                thisObj.v_changes['defect']['id_' + v_pk]['desc'] = vinfo;
                console.log('dentro change input', thisObj.v_changes);
                thisObj.v_is_changed = true;

                console.log('labelChange', thisObj.v_changes);



                thisObj.calcDefects();
            });
            $("#othersRow" + thisObj.v_others_pk).on('click', function (eventdata, handler) {
                v_pk = $(this).attr('cd_inspection_defect');
                if ($('#inpOthers' + v_pk).is(":focus")) {
                    return;
                }

                thisObj.openSetProblems(this);
            });
        };
        this.openSignature = function (sigType) {

            thisObj.v_signature_modal = $.dialog({content: thisObj.v_signature_form,
                title: false,
                cancelButton: false,
                confirmButton: false,
                closeIcon: false,
                backgroundDismiss: true,
                columnClass: 'col-md-12',
                onOpen: function () {
                    var v_wi = thisObj.v_signature_modal.$content.width();
                    $('#signature_pad_obj').width(v_wi - 50);
                    $('#signature_pad_obj').jSignature();
                    thisObj.v_EmpSig = $('#signature_pad_obj').jSignature('getData');
                    if (sigType === 'tc') {
                        thisObj.v_signature_modal.$content.find('#signature_title').html('TC Signature');
                    } else {
                        thisObj.v_signature_modal.$content.find('#signature_title').html('Factory Signature');
                    }

                    $(window).on('resize.Signature', function () {
                        thisObj.resizeSignature();
                    });
                    thisObj.v_sigType = sigType;
                },
                onClose: function () {
                    $(window).off('resize.Signature')
                    thisObj.v_sigType = '';
                    thisObj.v_EmpSig = '';
                }});
        };
        this.closeSignature = function () {
            thisObj.v_signature_modal.close();
        };
        this.resizeSignature = function () {
            var v_wi = thisObj.v_signature_modal.$content.width();
            $('#signature_pad_obj').jSignature('reset');
            $('#signature_pad_obj').empty();
            $('#signature_pad_obj').width(v_wi - 50);
            $('#signature_pad_obj').jSignature();
            thisObj.v_EmpSig = $('#signature_pad_obj').jSignature('getData');
        }

        this.clearPadSignature = function () {
            $('#signature_pad_obj').jSignature('reset');
        };


        this.resetSignature = function () {
            $("#signature_pad_obj").jSignature('reset');
            if (thisObj.v_sigType == 'tc') {
                thisObj.v_changes['tc_signature'] = undefined;
                $('#signature_tc_img').attr('src', '');
                $('#signature_tc_img').hide();
            } else {
                thisObj.v_changes['fact_signature'] = undefined;
                $('#signature_factory_img').attr('src', '');
                $('#signature_factory_img').hide();
            }



            this.closeSignature();
        }


        this.saveSignature = function () {
            var imgdata = $("#signature_pad_obj").jSignature("getData");
            if (thisObj.v_EmpSig == imgdata) {

                messageBoxAlert('Need to Sign in order to Save');
                return;
            }
            thisObj.v_is_changed = true;

            if (thisObj.v_sigType == 'tc') {
                thisObj.v_changes['tc_signature'] = imgdata;
                $('#signature_tc_img').attr('src', imgdata);
                $('#signature_tc_img').show();
            } else {
                imgdata = $("#signature_pad_obj").jSignature("getData");
                thisObj.v_changes['fact_signature'] = imgdata;
                $('#signature_factory_img').attr('src', imgdata);
                $('#signature_factory_img').show();
            }

            this.closeSignature();
        };


        this.openBatchSignature = function (array_insp) {


            thisObj.v_signature_modal = $.dialog({content: thisObj.strBatchPad,
                title: false,
                cancelButton: false,
                confirmButton: false,
                closeIcon: false,
                backgroundDismiss: true,
                columnClass: 'col-md-12',
                onOpen: function () {
                    //var v_wi = thisObj.v_signature_modal.$content.width();
                    //$('#signature_pad_obj').width(v_wi - 50);
                    $('#batch_signature_pad_fact_obj').jSignature();
                    $('#batch_signature_pad_tc_obj').jSignature();

                    thisObj.v_EmpSig = $('#batch_signature_pad_fact_obj').jSignature('getData');

                    $('#btnBatchSignatureSave').on('click', function () {
                        vtcdata = $('#batch_signature_pad_tc_obj').jSignature('getData');
                        vfactdata = $('#batch_signature_pad_fact_obj').jSignature('getData');

                        if (vtcdata == thisObj.v_EmpSig || vfactdata == thisObj.v_EmpSig) {
                            messageBoxError('Please inform Factory and TC Signature');
                            return;
                        }
                        ;

                        tosend = {inspections: array_insp, tcsign: vtcdata, factsign: vfactdata};

                        waitMsgON('body');


                        $.ajax({
                            type: "post",
                            url: thisObj.cfcPath + "inspection.cfc",
                            data: {
                                method: "batchSignInspection",
                                p: JSON.stringify(tosend)

                            },
                            dataType: "text",
                            success: function (response) {

                                waitMsgOFF('body');

                                if (response === 'OK') {
                                    $('.btnFilterReport').click();
                                    thisObj.closeBatchPadSignature()

                                } else {
                                    messageBoxError('<div class="col-md-12">' + response + '</div>');
                                }

                            },
                            error: function (error1, error2) {
                                messageBoxError('<div class="col-md-12">' + error1.responseText + '</div>');
                                waitMsgOFF('body');
                            }
                        });




                    });


                },
                onClose: function () {
                    //$(window).off('resize.Signature')
                    thisObj.v_sigType = '';
                    thisObj.v_EmpSig = '';
                }});
        };


        this.closeBatchPadSignature = function () {
            thisObj.v_signature_modal.close();
        }


        this.openPackingInstruction = function () {
            thisObj.vPLchooseVar = undefined;
            var vOpts = '';

            if (thisObj.vPackingInstructionOptions.length == 0) {
                messageBoxError('Packing Instruction not Found. Please contact Production Team')
                return;
            }

            if (thisObj.vPackingInstructionOptions.length == 1) {
                thisObj.openPLURL(0);
                return;
            }

            $.each(thisObj.vPackingInstructionOptions, function (i, v) {
                vOpts = vOpts + "<tr><td>" + v.ds_desc + "</td><td><button type='button' class='btn btn-primary' onclick='dsInspFormObj.openPLURL(" + i + ");'  ><i class='fa fa-clone'></i></button></td></tr>";
            });

            var res = thisObj.strPackingInstruction.replace("<tbody>", '<tbody>' + vOpts);

            console.log(thisObj.strPackingInstruction);

            thisObj.vPLchooseVar = $.confirm({
                title: 'Packing Instruction related to this PO',
                closeIcon: true,
                content: '' + res + '',
                columnClass: 'col-md-10 col-md-offset-1',
                containerFluid: true,
                buttons: {
                    close: function () {
                    }
                }


            });




        }
        this.openPLURL = function (index) {
            

            var vurl = thisObj.vPackingInstructionOptions[index].ds_url;


                    $.confirm({
                        title: 'Packing Instruction',
                        closeIcon: true,
                        content: '<div style="height: calc(100vh - 200px);"> <iframe style="width: 100%;height: 100%; overflow-x: hidden; overflow-y: hidden;" src="'+vurl+'"></iframe> </div>',
                        columnClass: 'col-md-10 col-md-offset-1',
                        containerFluid: true,
                        backgroundDismiss: true,
                        buttons: {
                            close: function () {
                            }
                        }


                    });

                
            


        }

    };




    function base64toBlob(base64Data, contentType, sliceSize) {

        var byteCharacters,
                byteArray,
                byteNumbers,
                blobData,
                blob;
        contentType = contentType || '';
        byteCharacters = atob(base64Data);
        // Get blob data sliced or not
        blobData = sliceSize ? getBlobDataSliced() : getBlobDataAtOnce();
        blob = new Blob(blobData, {type: contentType});
        return blob;
        /*
         * Get blob data in one slice.
         * => Fast in IE on new Blob(...)
         */
        function getBlobDataAtOnce() {
            byteNumbers = new Array(byteCharacters.length);
            for (var i = 0; i < byteCharacters.length; i++) {
                byteNumbers[i] = byteCharacters.charCodeAt(i);
            }

            byteArray = new Uint8Array(byteNumbers);
            return [byteArray];
        }
        ;
        /*
         * Get blob data in multiple slices.
         * => Slow in IE on new Blob(...)
         */
        function getBlobDataSliced() {

            var slice,
                    byteArrays = [];
            for (var offset = 0; offset < byteCharacters.length; offset += sliceSize) {
                slice = byteCharacters.slice(offset, offset + sliceSize);
                byteNumbers = new Array(slice.length);
                for (var i = 0; i < slice.length; i++) {
                    byteNumbers[i] = slice.charCodeAt(i);
                }

                byteArray = new Uint8Array(byteNumbers);
                // Add slice
                byteArrays.push(byteArray);
            }

            return byteArrays;
        }
        ;
    }
    ;
    function makeDivString(div) {
        var divString = $(div).html();
        $(div).empty();
        return divString;
    }

</script>

<div class="" id="row_po_info" style="display: none;">
    <div class="row">
        <div class="col-md-12" style="font-size: 1.5em" id="passedInfo">
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">

            <div class='box  box-primary box-solid' id="po_info_box" >
                <div class='box-header with-border '>
                    <h4 class='box-title'>PO Information</h4>
                    <div class='box-tools pull-right'>   
                        <button type="button" class="btn btn-box-tool" code="-98"  id="pict-98" onclick="dsInspFormObj.openImages(this);"><i class="fa fa-camera cam-defect btnDefectArea"></i></button>
                        <button type='button' class='btn btn-box-tool'  data-widget='collapse'><i class='fa fa-minus'></i></button>
                        <button type='button' class='btn btn-box-tool' onclick="dsInspFormObj.closeInspect();"><i class='fa fa-close'></i></button>

                    </div>

                </div>
                <div class='box-body' style='background-color: #ecfcf3;'>

                    <form class="form-horizontal">

                        <div class="form-group" id="po_form" style="margin-bottom: 5px;">
                            <div class="col-md-10">
                                <div class="row">

                                    <label for="itfactory" class="col-sm-1 control-label">Factory</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control input-sm" id="ds_factory_form" placeholder="Factory" readonly="readonly">
                                    </div>


                                    <label for="itchiefstyle" class="col-sm-1 control-label">ChiefStyle</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control input-sm" id="ds_chiefstyle_form" readonly="readonly" >
                                    </div>

                                </div>

                                <div class="row">

                                    <label for="itpo" class="col-sm-1 control-label">PO#</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control input-sm" id="nr_cont_form"  readonly="readonly" >
                                    </div>

                                    <label for="itcustomer" class="col-sm-1 control-label">Customer</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control input-sm" id="ds_customer_form" readonly="readonly" >
                                    </div>



                                </div>


                                <div class="row">
                                    <label for="itcustomerpo" class="col-sm-1 control-label">Cust. PO</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control input-sm" id="nr_customer_po_number_form"  readonly="readonly" >
                                    </div>

                                    <label for="itfinalcustomer" class="col-sm-1 control-label">Final Cust</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control input-sm" id="ds_final_customer_form"  readonly="readonly" >
                                    </div>
                                </div>

                                <div class="row">

                                    <label for="ds_color_form" class="col-sm-1 control-label">Color</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control input-sm" id="ds_color_form" readonly="readonly" >
                                    </div>

                                    <label for="nr_qt_pairs_form" class="col-sm-1 control-label">Prs - Ctn</label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control input-sm" id="nr_qt_pairs_form"  readonly="readonly" style="text-align: right;">
                                    </div>


                                </div>


                                <div class="row">

                                    <label for="itstyle" class="col-sm-1 control-label">Style</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control input-sm" id="ds_style_form" readonly="readonly">
                                    </div>



                                    <label for="itpackingdetail" class="col-sm-1 control-label">Packing Detail</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control input-sm" id="ds_shoe_box_type_form"  readonly="readonly">
                                    </div>
                                </div>

                                <div class="row">

                                    <label for="itremarks" class="col-sm-1 control-label">Remarks</label>
                                    <div class="col-sm-5">
                                        <textarea id="itremarks" class="form-control input-sm" readonly="readonly"></textarea>
                                    </div>

                                    <label for="itinstrautorization" class="col-sm-1 control-label">Instr. Auth.</label>
                                    <div class="col-sm-5">
                                        <textarea id="itinstrautorization" class="form-control input-sm"  readonly="readonly"></textarea>
                                    </div>

                                </div>

                            </div>

                            <div class="col-md-2"> 
                                <img src="" id="imageShoes" class="img-thumbnail" style=" width:100%;height: auto;margin-top: 5px; cursor: pointer;" onclick="dsInspFormObj.openPackingInstruction();">
                            </div>



                            <div class="col-md-12">


                                <table class="table table-condensed tableAql"   id="aql_selected" style="padding-top: 5px;">

                                    <tr>
                                        <th class="tableAqlTitle" style="border-left: black solid thin !important; border-right: black solid thin !important; ">Inspected Qty<br></th>
                                        <th class="tableAqlTitle" style="border-left: black solid thin !important; border-right: black solid thin !important; " colspan="3" id="aql_table_cr">Critical</th>
                                        <th class="tableAqlTitle" style="border-left: black solid thin !important; border-right: black solid thin !important; " colspan="3" id="aql_table_mj">Major</th>
                                        <th class="tableAqlTitle" style="border-left: black solid thin !important; border-right: black solid thin !important; " colspan="3" id="aql_table_mi">Minor</th>
                                    </tr>
                                    <tr>
                                        <td class="tableAqlTitle" style="border-left: black solid thin !important;"></td>
                                        <td class="tableAqlTitle" style="border-left: black solid thin !important;" >Passed</td>
                                        <td class="tableAqlTitle" >Failed</td>
                                        <td class="tableAqlTitle" style="border-right: black solid thin !important;">Actual</td>

                                        <td class="tableAqlTitle" style="border-left: black solid thin !important;">Passed</td>
                                        <td class="tableAqlTitle">Failed</td>
                                        <td class="tableAqlTitle" style="border-right: black solid thin !important;">Actual</td>

                                        <td class="tableAqlTitle" style="border-left: black solid thin !important;">Passed</td>
                                        <td class="tableAqlTitle">Failed</td>
                                        <td class="tableAqlTitle" style="border-right: black solid thin !important;">Actual</td>

                                    </tr>

                                    <tbody id="aql_selected_tbody">

                                    </tbody>

                                </table>



                            </div>



                        </div>

                    </form>

                </div>    
            </div>

            <div class="col-sm-12">


                <div class="row">

                    <div class="row" id="row_defect_info">

                    </div>

                </div>


                <div class="row">

                    <div class='box  box-primary box-solid'>
                        <div class='box-header with-border '>
                            <h4 class='box-title'>Defect Summary</h4>
                            <div class='box-tools pull-right'>   
                                <button type='button' class='btn btn-box-tool'  data-widget='collapse'><i class='fa fa-minus'></i></button>
                            </div>

                        </div>
                        <div class='box-body'>

                            <div class="col-md-12" id="summaryArea">


                                <table class="table table-condensed table-bordered" style="text-align: center;" id="tableSummary">

                                    <thead>

                                        <tr>

                                            <th style="width: 50%;">Area</th>

                                            <th style="width: 50%;">Defect Description</th>

                                            <th style="width: 100px;">CR</th>

                                            <th style="width: 100px;">MJ</th>
                                            <th style="width: 100px;">MI</th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                    </tbody>

                                </table>
                            </div>

                        </div>
                    </div>

                </div>

                <div class="row">

                    <div class='box  box-primary box-solid'>
                        <div class='box-header with-border '>
                            <h4 class='box-title'>COMMENTS</h4>
                            <div class='box-tools pull-right'>   
                                <button type='button' class='btn btn-box-tool'  data-widget='collapse'><i class='fa fa-minus'></i></button>
                            </div>

                        </div>
                        <div class='box-body' style='background-color: #ecfcf3;'>

                            <div class="form-group">
                                <label for="ds_tc_comments">TC Comments</label>
                                <textarea class="form-control" id="ds_tc_comments" onchange="dsInspFormObj.addOnResultSet(this);"> </textarea>
                            </div>
                            <div class="form-group">
                                <label for="ds_factory_actions">Factory Corrective Actions</label>
                                <textarea class="form-control" id="ds_factory_actions" onchange="dsInspFormObj.addOnResultSet(this);" > </textarea>
                            </div>


                            <div class="form-group">
                                <label for="ds_supervisor_comments">Supervisor Comments</label>
                                <textarea class="form-control" id="ds_supervisor_comments" onchange="dsInspFormObj.addOnResultSet(this);"> </textarea>
                            </div>


                        </div>
                    </div>                 


                </div>

            </div>
        </div>

        <div class="col-sm-12" id='btnPanelTC' style="margin-top: 15px;margin-bottom: 20px;">

            <div class="col-sm-4">
                <a href="#" class="btn btn-block btn-lg btn-success" id="btnPassed" onClick="dsInspFormObj.setInspPassFailed(this);
                        return false;"><span class="glyphicon glyphicon-thumbs-up" ></span> PASSED</a> 
            </div>        
            <div class="col-sm-4">
                <a href="#" class="btn btn-block btn-lg btn-default selectedStatus" id="btnIdle" onClick="dsInspFormObj.setInspPassFailed(this);
                        return false;"><span class="glyphicon glyphicon-user"></span> </a>
            </div>        
            <div class="col-sm-4">
                <a href="#" class="btn btn-block btn-lg btn-danger"  id="btnFailed" onClick="dsInspFormObj.setInspPassFailed(this);
                        return false;"><span class="glyphicon glyphicon-thumbs-down"></span> FAILED</a>
            </div>        

        </div>

        <div class="col-sm-12">

            <div class="row" id="signatures">

                <div class="col-md-6">

                    <div class='box  box-primary box-solid'>
                        <div class='box-header with-border '>
                            <h4 class='box-title'>TC Signature</h4>
                            <div class='box-tools pull-right'>   
                            </div>

                        </div>
                        <div class='box-body' style='background-color: #ecfcf3;'>
                            <div class="col-md-12">
                                <div id="signature_tc" style="border: 1px solid black; -ms-touch-action:none;width: 100%; min-height: 100px;">

                                    <img src="" id="signature_tc_img" class='img_signature' style='display:none'>


                                </div>
                            </div>

                            <div class="col-md-12" style="padding-top: 5px;">
                                <button type='button' id = 'signature_tc_btn' class='btn inspButtons btn-success' onclick="dsInspFormObj.openSignature('tc');" style="font-size: 1em;" ><i class='fa fa-pencil'></i></button>
                            </div>


                        </div>
                    </div>

                </div>

                <div class="col-md-6">

                    <div class='box  box-primary box-solid'>
                        <div class='box-header with-border '>
                            <h4 class='box-title'>Factory Signature</h4>
                            <div class='box-tools pull-right'>   
                            </div>

                        </div>
                        <div class='box-body' style='background-color: #ecfcf3;'>
                            <div class="col-md-12">

                                <div id="signature_factory" style="border: 1px solid black; -ms-touch-action:none;width: 100%; min-height: 100px;">

                                    <img src="" id="signature_factory_img" class='img_signature' style='display:none'>


                                </div>
                            </div>

                            <div class="col-md-12" style="padding-top: 5px;">
                                <button type='button' id = 'signature_factory_btn' class='btn inspButtons btn-success' onclick="dsInspFormObj.openSignature('fact');" style="font-size: 1em;" ><i class='fa fa-pencil'></i></button>
                            </div>

                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>


    <div class="row" id="supervisor_area" style="display: none">

        <div class="col-md-12">

            <div class='box  box-primary box-solid'>
                <div class='box-header with-border '>
                    <h4 class='box-title'>Supervisor</h4>
                    <div class='box-tools pull-right'>   
                    </div>

                </div>
                <div class='box-body' style='background-color: #ecfcf3;'>

                    <div class="col-sm-12" id='btnPanelSup'>

                        <div class="col-sm-4">
                            <a href="#" class="btn btn-block btn-lg btn-success" id="btnPassedSup" onClick="dsInspFormObj.setInspPassFailedSup(this, true);
                                    return false;"><span class="glyphicon glyphicon-thumbs-up" ></span> PASSED</a> 
                        </div>        
                        <div class="col-sm-4">
                            <a href="#" class="btn btn-block btn-lg btn-default selectedStatus" id="btnIdleSup" onClick="dsInspFormObj.setInspPassFailedSup(this, true);
                                    return false;"><span class="glyphicon glyphicon-user"></span> </a>
                        </div>        
                        <div class="col-sm-4">
                            <a href="#" class="btn btn-block btn-lg btn-danger"  id="btnFailedSup" onClick="dsInspFormObj.setInspPassFailedSup(this, true);
                                    return false;"><span class="glyphicon glyphicon-thumbs-down"></span> FAILED</a>
                        </div>        

                    </div>

                </div>
            </div>

        </div>

    </div>



    <div class="row">

        <div class="col-md-12" id="inspToolbar">
            <div class="col-md-3 col-md-offset-3 col-sm-12">
                <button type='button' class='btn btn-block btn-primary inspButtons' id = 'btnUpdate' onclick="dsInspFormObj.updateInspection();" style="font-size: 1.5em;" ><i class='fa fa-save'></i> Update </button>
            </div>
            <div class="col-md-3 col-sm-12">
                <button type='button' class='btn btn-block btn-primary inspButtons' onclick="dsInspFormObj.closeInspect();" style="font-size: 1.5em;" ><i class='fa fa-close '></i> Close </button>

            </div>
        </div>
    </div>

</div>




<div class="row" id="row_edit_panel">
    <div class="row" id="def_edit_panel">


        <div class="panel panel-info">
            <div class="panel-heading">
                <p class="text-center" style="padding-top: 0px; padding-bottom: 0px;margin-bottom: 2px;font-size: 1.5em;" id="descTitle">   </p>
            </div>


            <div class="panel-body" style="color: grey">

                <div class="row">


                    <div class="col-sm-4">

                        <div class="col-sm-12">

                            <div class="row">
                                <p class="text-center" style="padding-top: 0px; padding-bottom: 0px;margin-bottom: 2px;"> <strong> Critical </strong> </p>
                            </div>


                            <div class="row">
                                <a href="#" class="btn btn-sm btn-danger bt-pannel-set-button" style="width: 100%;" onclick="return false;" fld="nr_critical_set" act="+"><i class="fa fa-plus fa-2x"  id="bt_plus_critical"> </i></a>
                            </div>


                            <div class="row">
                                <input type="number" style="width: 100%;text-align: center;font-size: 2em;" value="0" readonly="readonly" id="nr_critical_set">
                            </div>

                            <div class="row">
                                <a href="#" class="btn btn-sm btn-danger bt-pannel-set-button" style="width: 100%;"  onclick="return false;" fld="nr_critical_set" act="-"><i class="fa fa-minus fa-2x" id="bt_minus_critical"></i>  </a>
                            </div>
                        </div>

                    </div>

                    <div class="col-sm-4">

                        <div class="col-sm-12">



                            <div class="row">
                                <p class="text-center" style="padding-top: 0px; padding-bottom: 0px;margin-bottom: 2px;"> <strong> Major </strong></p>
                            </div>


                            <div class="row">
                                <a href="#" class="btn btn-sm btn-warning bt-pannel-set-button" style="width: 100%;" fld="nr_major_set" act="+" onclick="return false;" id="bt_plus_major"><i class="fa fa-plus fa-2x"> </i> </a>
                            </div>


                            <div class="row">
                                <input type="number" style="width: 100%;text-align: center;font-size: 2em;" value="0" readonly="readonly" id="nr_major_set">
                            </div>

                            <div class="row">
                                <a href="#" class="btn btn-sm btn-warning bt-pannel-set-button" style="width: 100%;" fld="nr_major_set" act="-" onclick="return false;" id="bt_minus_major"><i class="fa fa-minus fa-2x"></i> </a>
                            </div>
                        </div>

                    </div>

                    <div class="col-sm-4">

                        <div class="col-sm-12">


                            <div class="row">
                                <p class="text-center" style="padding-top: 0px; padding-bottom: 0px;margin-bottom: 2px;"><strong> Minor</strong> </p>
                            </div>


                            <div class="row">
                                <a href="#" class="btn btn-sm bg-teal bt-pannel-set-button" style="width: 100%;"  onclick="return false;" fld="nr_minor_set" act="+" id="bt_plus_minor"><i class="fa fa-plus fa-2x"> </i> </a>
                            </div>


                            <div class="row">
                                <input type="number" style="width: 100%;text-align: center;font-size: 2em;" value="0" readonly="readonly" id="nr_minor_set">
                            </div>

                            <div class="row">
                                <a href="#" class="btn btn-sm bg-teal bt-pannel-set-button" style="width: 100%;"  onclick="return false;" id="bt_minus_minor" fld="nr_minor_set" act="-"><i class="fa fa-minus fa-2x"></i>  </a>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

            <div class="panel-footer" style="height: 60px;">
                <div clas="row">

                    <div class="col-md-offset-4 col-md-4 col-sm-6"><a href="#" class="btn btn-block btn-primary btn-danger" style="font-size: 1.5em;" onclick="dsInspFormObj.v_defectmodal.close();
                            return false;"><span class="glyphicon glyphicon-remove"></span> Cancel</a></div>
                    <div class="col-md-4 col-sm-6"><a href="#" class="btn btn-block btn-primary btn-primary" style="font-size: 1.5em;" onclick="dsInspFormObj.runChangeDefects();
                            return false;"><span class="glyphicon glyphicon-ok"></span> Confrm</a></div>
                </div>
            </div>

        </div>
    </div>



</div>



<div class="row" id="imgCarousel">

    <div class="row">

        <div class="col-md-12">

            <div class="row">
                <div class="col-md-12">

                    <span id='imgToolBar'>
                        <button type='button' class='btn btn-success inspButtons' onclick="dsInspFormObj.openFileSelector();"  ><i class='fa fa-plus'></i></button>
                        <button type='button' class='btn btn-success inspButtons' onclick="dsInspFormObj.uploadFinalImage();"><i class='fa fa-save'></i></button>
                        <button type='button' class='btn btn-success inspButtons' onclick="dsInspFormObj.deleteImage();" ><i class='fa fa-trash'></i></button>

                    </span>

                    <button type='button' class='btn btn-danger pull-right' onclick="dsInspFormObj.v_imgmodal.close();" style="font-size: 1.5em;" ><i class='fa fa-close'></i></button>

                </div>


            </div>


            <div class="row">

                <div id="carousel-images" class="carousel slide">
                    <!-- Indicators -->
                    <ol class="carousel-indicators">
                        <li data-target="#carousel-images" data-slide-to="0" class=""></li>
                    </ol>

                    <!-- Wrapper for slides -->
                    <div class="carousel-inner" role="listbox">


                        <div class="item" style="background-color: grey;">
                            <div>
                                <img id="imageToUpload" class = "imgCropped" style="height: 500px; width: auto;" src="">
                                <div class="carousel-caption"></div>
                            </div>

                        </div>
                    </div>

                    <!-- Controls -->
                    <a class="left carousel-control" href="#carousel-images" role="button" data-slide="prev">
                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="right carousel-control" href="#carousel-images" role="button" data-slide="next">
                        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>




            </div>

            <div class="row" id="imgComments" style="padding-top: 5px;">
                <div class="col-md-12">
                    <input type="text" class="form-control" id="imgCommentsInput" style="width: 100%;">
                </div>
            </div>
        </div>
    </div>

</div>



<input type="file" class="sr-only" id="inputImage" name="file" accept="image/jpg" style="display: none">


<div class='row' id='signature_pad' style="">

    <div class='box  box-primary box-solid'>
        <div class='box-header with-border '>
            <h4 class='box-title' id='signature_title'></h4>
            <div class='box-tools pull-right'>   
            </div>

        </div>
        <div class='box-body' style='background-color: #ecfcf3;'>
            <div class="col-md-12">

                <div id="signature_pad_obj" style="border: 1px solid black; -ms-touch-action:none;">
                </div>

                <div class="col-md-12" style="padding-top: 5px;">
                    <div class='row'>
                        <button type='button' class='btn btn-danger inspButtons' onclick="dsInspFormObj.resetSignature();"  ><i class='fa fa-trash'></i></button>

                        <button type='button' class='btn btn-success inspButtons' onclick="dsInspFormObj.clearPadSignature();"  ><i class='fa fa-undo'></i></button>

                        <button type='button' class='btn btn-success inspButtons'  onclick="dsInspFormObj.saveSignature();"  ><i class='fa fa-save'></i></button>


                        <button type='button' class='btn btn-warning inspButtons pull-right' onclick="dsInspFormObj.closeSignature();" ><i class='fa fa-close'></i></button>
                    </div>


                </div>

            </div>
        </div>


    </div>
</div>

<div id="batchSignature">
    <div class="row">
        <div class="col-md-12">

            <div class='box  box-primary box-solid'>
                <div class='box-header with-border '>
                    <h4 class='box-title'>TC Signature</h4>
                    <div class='box-tools pull-right'>   
                    </div>

                </div>
                <div class='box-body' style='background-color: #ecfcf3;'>

                    <div class="col-md-12">
                        <div id="batch_signature_pad_tc_obj" style="border: 1px solid black; -ms-touch-action:none;width: 100%;">
                        </div>
                    </div>


                </div>
            </div>
        </div>

        <div class="col-md-12">

            <div class='box  box-primary box-solid'>
                <div class='box-header with-border '>
                    <h4 class='box-title'>Factory Signature</h4>
                    <div class='box-tools pull-right'>   
                    </div>

                </div>
                <div class='box-body' style='background-color: #ecfcf3;'>


                    <div class="col-md-12">
                        <div id="batch_signature_pad_fact_obj" style="border: 1px solid black; -ms-touch-action:none;width: 100%;">
                        </div>
                    </div>

                </div>
            </div>
        </div>



    </div>
    <div class="row">
        <div class="col-md-12">
            <button type='button' class='btn btn-success' id='btnBatchSignatureSave'  ><i class='fa fa-save'></i></button>
            <button type='button' class='btn btn-danger pull-right' onclick="dsInspFormObj.closeBatchPadSignature();"  ><i class='fa fa-close'></i></button>
        </div>
    </div>

</div>


<div id="plData">
    <div class="row">
        <div class="col-md-12">

            <table class="table table-bordered table-condensed table-responsive table-striped">
                <thead>
                    <tr>
                        <th>
                            Matching Attributes
                        </th>
                        <th style="width: 32px">

                        </th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>


        </div>
    </div>
</div>