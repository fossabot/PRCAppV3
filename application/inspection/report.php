<link  href="/plugins/cropperjs/dist/cropper.css" rel="stylesheet">
<script src="/plugins/cropperjs/dist/cropper.js"></script>


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

</style>


<style>
    .productionColumn {
        background-color: #e6fff7;
    }
    .sampleColumn {
        background-color: #ffffb3;
    }

    .dt-body-right {
        text-align: right;
    }


</style>


<div id="row_inspect_general" class="row">
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Search Parameters</h3>
        </div>        
        <div class="box-body filterForm">
            <div class="row">

                <cfinclude template="/components/dropdowns/factoryOnlySelector.cfm" >

                    <cfinclude template="/components/dropdowns/division_division_brandSelector.cfm" >

                        <div class="col-md-4">

                            <div class="box box-primary direct-chat direct-chat-primary">
                                <div class="box-body">
                                    <div class="box-header with-border checkboxes">
                                        <label>Sign Date Range:</label>
                                    </div>
                                    <div class="row">

                                        <div class="form-group">


                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </div>
                                                    <input type="text" class="form-control pull-right searchField" id="signDateFrom">


                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </div>
                                                    <input type="text" class="form-control pull-right searchField" id="signDateTo">


                                                </div>
                                            </div>
                                            <!-- /.input group -->
                                        </div>

                                    </div>

                                </div>
                            </div>  
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">

                                <div class="box box-primary direct-chat direct-chat-primary">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <div class="box-header with-border checkboxes">
                                                <label>Parameters:</label>
                                            </div>

                                            <select class=" form-control input-sm searchField" id="tp_report">
                                                <option value="without_docs">Pending Docs</option>
                                                <option value="with_docs">Docs Inserted</option>
                                                <option value="all">All</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        </div>

                        <div class="row">

                            <div class="col-md-2">
                                <div class="form-group">
                                    <div class="box box-primary direct-chat direct-chat-primary">
                                        <div class="box-body">
                                            <div class="form-group">
                                                <label>PO#:</label>                                 
                                                <input type="text" class="form-control input-sm searchField" id="nr_cont">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <div class="box box-primary direct-chat direct-chat-primary">
                                        <div class="box-body">
                                            <div class="form-group">
                                                <label>Receipt#:</label>                                    
                                                <input type="text" class="form-control input-sm searchField" id="nr_receipt">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>



                        </div>

                        <div class="box-footer">
                            <button type="submit" class="btn btn-default btnShowHideFilter">Show/Hide Form</button>
                            <button type="submit" class="btn btn-info pull-right btnFilterReport" >Filter</button>
                        </div>

                        </div>    

                        <div class="row">
                            <div class="col-xs-12">
                                <div class="box">
                                    <div class="box-body">
                                        <table id="reportTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>Factory</th>
                                                    <th>Receipt</th>
                                                    <th>Signed</th>
                                                    <th>PO</th>
                                                    <th>Chiefstyle</th>
                                                    <th>Division</th>
                                                    <th>Customer</th>
                                                    <th>Documents</th>
                                                </tr>
                                            </thead>
                                            <tbody class="reportMainData">

                                            </tbody>

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>


                        <div class="row" id="imgCarousel">
                            <div class="row" style="margin-left: 1x;margin-right: 1"> 

                                <div class="col-md-12">

                                    <div class="row">
                                        <span id='imgToolBar'>
                                            <button type='button' class='btn btn-success' onclick="openFileSelector();" style="font-size: 1.5em;" ><i class='fa fa-plus'></i></button>
                                            <button type='button' class='btn btn-success' onclick="uploadFinalImage();" style="font-size: 1.5em;" ><i class='fa fa-save'></i></button>
                                            <button type='button' class='btn btn-success' onclick="deleteImage();" style="font-size: 1.5em;" ><i class='fa fa-trash'></i></button>

                                        </span>

                                        <button type='button' class='btn btn-danger pull-right' onclick="v_imgmodal.close();" style="font-size: 1.5em;" ><i class='fa fa-close'></i></button>



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
                                        <div class="col-md-11">
                                            <input type="text" class="form-control" placeholder="Comments" id="imgCommentsInput" style="width: 100%;">
                                        </div>
                                        <div class="col-md-1">
                                            <input type="text" class="form-control" placeholder="Page" id="imgPageInput" style="width: 100%;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <input type="file" class="sr-only" id="inputImage" name="file" accept="image/jpg" style="display: none">




                        <script type="text/javascript">
                            var formModal;

                            var v_imgmodal;
                            var cropper;

                            var v_img_carousel = '';

                            var v_selected_images = [];
                            var v_po_images = [];

                            v_img_carousel = makeDivString('#imgCarousel');

                            var thisObj = this;

                            function makeDivString(div) {
                                var divString = $(div).html();
                                $(div).empty();
                                return divString;
                            }


                            this.openFileSelector = function () {
                                if ($('#carousel-images').find('div.active').index() !== 0) {
                                    $('#carousel-images').carousel(0);
                                }


                                $('#inputImage').click();
                                $('#imgPageInput').val('1');
                            };




                            this.uploadFinalImage = function () {
                                if ($('#carousel-images').find('div.active').index() !== 0) {
                                    thisObj.updateImageComments();
                                    return;
                                }

                                if ($("#imageToUpload").attr('src') == '') {
                                    console.log(thisObj);
                                    messageBoxAlert('Select the Image!' + thisObj.cd_receipt);
                                    return;
                                }

                                if (thisObj.cropper.getData().width == 0) {
                                    messageBoxAlert('Select the Image Area!');
                                    return;
                                }

                                waitMsgON(thisObj.v_imgmodal.$content.parent(), true, 'Sending');
                                var img = thisObj.cropper.getCroppedCanvas().toDataURL('image/jpeg');
                                var base64ImageContent = img.replace(/^data:image\/(png|jpeg);base64,/, "");
                                var blob = base64toBlob(base64ImageContent, 'image/jpeg', '');
                                var vcomments = $('#imgCommentsInput').val();
                                var vpage = $('#imgPageInput').val();
                                var formData = new FormData();
                                formData.append('croppedImage', blob);
                                formData.append('comments', vcomments);
                                formData.append('cd_po', thisObj.cd_po);
                                formData.append('nr_page', vpage);
                                formData.append('method', 'uploadPOImageForm');
                                formData.append('cd_receipt', thisObj.cd_receipt);
                                $.ajax('/apps/receipt_factory_invoice/report.cfc', {
                                    method: "POST",
                                    data: formData,
                                    processData: false,
                                    cache: false,
                                    contentType: false,
                                    dataType: "json",
                                    success: function (response) {

                                        thisObj.imageComments['id' + response[0].cd_rec_fact_invoice_doc_new] = {};

                                        thisObj.imageComments['id' + response[0].cd_rec_fact_invoice_doc_new]['ds_comments'] = vcomments;
                                        thisObj.imageComments['id' + response[0].cd_rec_fact_invoice_doc_new]['nr_page'] = vpage;

                                        console.log('coloca o code');
                                        console.log(response[0]);

                                        var vindicators = '<li data-target="#carousel-images" data-slide-to="0" class=""></li>';
                                        var vitems = '<div class="item" code="' + response[0].cd_rec_fact_invoice_doc_new + '">' +
                                                '<div>' +
                                                '<img src="' + img + '" alt="' + vcomments + '" class="loaded">' +
                                                '<div class="carousel-caption"></div>' +
                                                '</div></div>';
                                        $('#carousel-images').find('.carousel-indicators').append(vindicators);
                                        $('#carousel-images').find('.carousel-inner').append(vitems);
                                        waitMsgOFF(thisObj.v_imgmodal.$content.parent());
                                        thisObj.cropper.destroy();
                                        $('#imageToUpload').attr("src", "");
                                        $('#imageToUpload').height(500);
                                        var newimage = document.getElementById('imageToUpload');
                                        thisObj.cropper = new Cropper(newimage, {
                                            //aspectRatio: 16 / 9,
                                            autoCropArea: 1,
                                            crop: function (e) {
                                            }
                                        });
                                        $('#imgCommentsInput').val('');
                                        waitMsgOFF(thisObj.v_imgmodal.$content.parent());
                                        thisObj.v_po_images = response;

                                        var newDocListText = "";
                                        var newSignData = '';
                                        $.each(response, function (index, value) {
                                            newDocListText = newDocListText + 'Doc. Page: ' + value.nr_page + '<a target="_blank" href="' + value.ds_image_path + '">   Download</a><br>'
                                            newSignData = '<center><button class="btn-sign" onclick="signReceipt(this, ' + value.cd_receipt + ')">Click Here to Sign</button><center>';
                                            //console.log(value);
                                        });



                                        var lineObj = $(thisObj.clicked_button).closest('.po_line');
                                        console.log(lineObj);

                                        var doclist = $(lineObj).find('span.document_list');
                                        var signlist = $(lineObj).find('span.signData');


                                        console.log("set doc list");

                                        var obj = $(lineObj).find('btn-img');




                                        $(thisObj.clicked_button).attr('documents', JSON.stringify(response));


                                        console.log($(thisObj.clicked_button));





                                        doclist.html(newDocListText);
                                        signlist.html(newSignData);

                                        //thisObj.changeImageStatus(thisObj.v_def_selected_area);
                                        messageBoxAlert("Image Uploaded Successfully");
                                    },
                                    error: function (error1, error2) {
                                        messageBoxError('<div class="col-md-12">' + error1.responseText + '</div>');
                                        waitMsgOFF(thisObj.v_imgmodal.$content.parent());
                                    }


                                });
                            };

                            this.signReceipt = function (obj, cd_receipt) {
                                messageBoxYesNo('Confirm Sign Receipt ?', function () {




                                    var codePK = cd_receipt;
                                    waitMsgON('body');
                                    $.ajax({
                                        type: "post",
                                        url: "/apps/receipt_factory_invoice/report.cfc",
                                        data: {
                                            method: "signReceipt",
                                            cd_receipt: codePK
                                        },
                                        dataType: "json",
                                        success: function (response) {

                                            //seta data de sign e remove o botao

                                            $(obj).hide();


                                            $(obj).closest('center').first().text(response[0]);


                                            waitMsgOFF('body');




                                        },
                                        error: function (error1, error2) {
                                            messageBoxError('<div class="col-md-12">' + error1.responseText + '</div>');
                                            waitMsgOFF('body');
                                        }
                                    });

                                });
                            }

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
                                        url: "/apps/receipt_factory_invoice/report.cfc",
                                        data: {
                                            method: "removePOImage",
                                            p: codePK,
                                            cd_inspection: thisObj.v_cd_inspection
                                        },
                                        dataType: "json",
                                        success: function (response) {

                                            thisObj.v_po_images = response;
                                            //thisObj.changeImageStatus(thisObj.v_def_selected_area);
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


                                            var vcode = $carousel.find('div.active').attr('code');
                                            $('#imgCommentsInput').val(thisObj.imageComments['id' + vcode].ds_comments);
                                            $('#imgPageInput').val(thisObj.imageComments['id' + vcode].nr_page);


                                            thisObj.v_po_images = response;

                                            var newDocListText = "";

                                            $.each(response, function (index, value) {
                                                newDocListText = newDocListText + 'Doc. Page: ' + value.nr_page + '<a target="_blank" href="' + value.ds_image_path + '">   Download</a><br>'
                                                //console.log(value);
                                            });

                                            var lineObj = $(thisObj.clicked_button).closest('.po_line');
                                            console.log(lineObj);

                                            var doclist = $(lineObj).find('span.document_list');
                                            console.log(doclist);

                                            $(lineObj).documents = response;


                                            doclist.html(newDocListText);


                                            waitMsgOFF('body');




                                        },
                                        error: function (error1, error2) {
                                            messageBoxError('<div class="col-md-12">' + error1.responseText + '</div>');
                                            waitMsgOFF('body');
                                        }
                                    });
                                });
                            };



                            $("document").ready(function () {
                        //        dsInspFormObj.start({divToForm: '#hereToAddForm', divToHide: '#row_inspect_generalblabla', funcAfterClose: afterCloseForm});

                                $(".select2").select2({
                                    theme: "bootstrap",
                                    width: null
                                });

                                $(".jq-multi-select").select2({
                                    theme: "bootstrap",
                                    width: null
                                });

                                var tabela = $('#reportTable').DataTable({
                                    "bPaginate": false,
                                    dom: 'Bfrtip',
                                    "scrollX": true,
                                    select: {
                                        style: 'single'
                                    },
                                    buttons: [
                                        {
                                            extend: 'excelHtml5',
                                            title: 'SampleAdoptionRate'
                                        },
                                        {
                                            extend: 'pdfHtml5',
                                            title: 'SampleAdoptionRate',
                                            orientation: 'landscape',
                                            pageSize: 'A4'
                                        }
                                    ]
                                });

                                $('#signDateFrom').datepicker({
                                    autoclose: true
                                });
                                $('#signDateTo').datepicker({
                                    autoclose: true
                                });


                                $('.btnShowHideFilter').click(function () {
                                    $('.filterForm').slideToggle(250);
                                });

                                $('.btnFilterReport').click(function () {

                                    waitMsgON('#row_inspect_general');

                                    var filterJson = generateFilterParameters($(".filterForm"));

                                    tabela.clear().draw();

                                    $.ajax({
                                        type: "post",
                                        url: "/apps/receipt_factory_invoice/report.cfc",
                                        data: {
                                            method: "loadReceiptList",
                                            p: JSON.stringify(filterJson)
                                        },
                                        dataType: "json",
                                        success: function (response) {
                                            var rowNode;
                                            $.each(response, function (index, value) {

                                                var dt_signed = "";
                                                if (value.dt_signed_report_received == "" && value.hmresource_allow_release == "Y" && value.rec_allow_release == "Y") {
                                                    dt_signed = '<center><button class="btn-sign" onclick="signReceipt(this, ' + value.cd_receipt + ')">Click Here to Sign</button><center>';
                                                } else {
                                                    dt_signed = value.dt_signed_report_received;
                                                }

                                                var potext = "";
                                                var v_receiptclosed = "N";
                                                if (value.dt_signed != "") {
                                                    v_receiptclosed = "Y";
                                                }
                                                if (value.hmresource_allow_upload_image == "Y" || value.count_docs > 0) {
                                                    potext = "<button style='font-size:0.7em' class='btn-img' user_permission_upload='" + value.hmresource_allow_upload_image + "' documents='" + JSON.stringify(value.documents) + "' cd_receipt = " + value.cd_receipt + " receipt_closed='" + v_receiptclosed + "' cd_po='" + value.cd_cont + "' onclick='openImages(this);'><i class='fa fa-camera cam-defect'></i></button>    " + value.nr_cont;
                                                } else {
                                                    potext = value.nr_cont;
                                                }

                                                var newDocListText = "";

                                                $.each(value.documents, function (index, value) {
                                                    newDocListText = newDocListText + 'Doc. Page: ' + value.nr_page + '<a target="_blank" href="' + value.ds_image_path + '">   Download</a><br>'
                                                });


                                                rowNode = tabela.row.add([
                                                    value.ds_factory, value.nr_receipt, '<span class="signData">' + dt_signed + "</span>", potext, value.ds_chiefstyle, value.ds_division, value.ds_customer, '<span class="document_list">' + newDocListText + '</span>']).draw().node();

                                                $(rowNode).addClass('po_line');


                                            });
                                            console.log(rowNode);

                                            tabela.draw(false);
                                            waitMsgOFF('#row_inspect_general');

                                            $('.filterForm').slideUp(250);

                                        }
                                    });



                                });





                            });

                            /*    function afterCloseForm(opt) {
                             formModal.close();
     
                             if (opt.justClosed) {
                             $('.btnFilterReport').click();
                             }
                             }
     
                             function openInspDetails(cd_inspection) {
     
                             formModal = $.confirm({content: '<div id="hereToAddForm" style="max-height: calc(100vh - 150px);overflow-y: auto;"> </div>',
                             title: false,
                             cancelButton: false,
                             confirmButton: false,
                             closeIcon: false,
                             backgroundDismiss: true,
                             columnClass: 'col-md-12',
                             onOpen: function () {
                             console.log('vai abrir inspection');
                             dsInspFormObj.setMain();
     
                             dsInspFormObj.OpenInspection(-1, cd_inspection);
                             console.log('abriu inspection');
     
                             $('.content-wrapper').addClass('jconfirm-noscroll');
     
                             },
                             onClose: function () {
     
                             $('.content-wrapper').removeClass('jconfirm-noscroll');
     
     
                             }
     
     
     
                             });
                             }*/

                            this.openImages = function (obj) {


                                thisObj.clicked_button = obj;



                                thisObj.cd_po = parseInt($(obj).attr('cd_po'));
                                thisObj.cd_receipt = parseInt($(obj).attr('cd_receipt'));

                                console.log(obj)
                                //thisObj.v_selected_images = thisObj.documents;
                                console.log("doc list");
                                console.log($(obj).attr('documents'));

                                thisObj.v_selected_images = JSON.parse($(obj).attr('documents'));

                                /*            $.each(obj.documents, function (index, values) {
         
                                 if (values.cd_po == thisObj.cd_po) {
                                 thisObj.v_selected_images.push(values);
                                 }
                                 });*/

                                /*            if (thisObj.v_selected_images.length == 0) {
                                 messageBoxAlert('There is no pictures for this area');
                                 return;
                                 }*/

                                /*            $obj_line = $(obj).closest('.box').find('.po_line').first();
         
                                 if ($obj_line.length == 0) {
                                 thisObj.v_selected_po = -1;
                                 } else {
                                 thisObj.v_selected_po = $obj_line.attr('cd_inspection_defect');
                                 }*/


                                thisObj.v_imgmodal = $.dialog({content: thisObj.v_img_carousel,
                                    title: false,
                                    cancelButton: false,
                                    confirmButton: false,
                                    closeIcon: false,
                                    backgroundDismiss: false,
                                    columnClass: 'col-md-12',
                                    onOpen: function () {

                                        if ($(obj).attr('user_permission_upload') == "N") {
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

                                            thisObj.imageComments['id' + values.cd_rec_fact_invoice_doc] = {id: values.cd_rec_fact_invoice_doc, ds_comments: values.ds_comments, nr_page: values.nr_page};
                                            var vsel = '';
                                            if (index == 0) {
                                                vsel = 'active';
                                            }
                                            vindicators = vindicators + '<li data-target="#carousel-images" data-slide-to="' + index + 1 + '" class="' + vsel + '"></li>';
                                            vitems = vitems + '<div class="item ' + vsel + '"  code="' + values.cd_rec_fact_invoice_doc + '">' +
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
                                            autoCropArea: 1,
                                            crop: function (e) {
                                            }
                                        });
                                        $('#carousel-images').on('slid.bs.carousel', function () {
                                            currentIndex = $(this).find('div.active').index();
                                            if (currentIndex == 0) {
                                                $('#imgCommentsInput').val('');
                                                $('#imgPageInput').val('');
                                            } else {
                                                console.log(thisObj);
                                                var vcode = $(this).find('div.active').attr('code');
                                                $('#imgCommentsInput').val(thisObj.imageComments['id' + vcode].ds_comments);
                                                $('#imgPageInput').val(thisObj.imageComments['id' + vcode].nr_page);
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
                                        //$('#imgCommentsInput').val(thisObj.imageComments['id' + vcode].ds_comments);
                                    },
                                    onClose: function () {
                                        $('#imgCommentsInput').val('');
                                    }});
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



                        </script>

