


<?php
// PHP page that has the filter area.
include_once APPPATH . 'views/viewIncludeFilter.php';
?>
<script src="<?php echo base_url(); ?>bootstrap/js/bootstrap-progressbar.min.js"></script>

<style>
    #buttonsArea button {
        margin-left: 5px;
    }

    .w2ui-toolbar table.w2ui-button.checked {
        
    }
    

</style>

<script>
// aqui tem os scripts basicos. 
    var gridName = "gridGeneric";

    var dsMainObject = new function () {

        // Private Variables;
        var thisObj = this;
        thisObj.gridName = undefined;
        // The starting function
        this.start = function (gridNamePar) {
            thisObj.gridName = gridNamePar;
            if (w2ui[thisObj.gridName] !== undefined) {
                w2ui[thisObj.gridName].destroy();
            }

// javascript received from controller with the grid.
<?php echo ($javascript); ?>

            thisObj.varData = $('#addhere').html();
            $('#addhere').empty();

            this.addListeners();
            this.addHelper();
            setTimeout(function () {
                w2ui[thisObj.gridName].retrieve();
            }, 0);

        }

        // add helper on the question mark button. 
        this.addHelper = function () {
            var arrayHelper = [];
            $.merge(arrayHelper, introAddFilterArea());
            $.merge(arrayHelper, w2ui[thisObj.gridName].toolbar.getIntroHelp());
            $.merge(arrayHelper, w2ui[thisObj.gridName].getIntroHelp());

            introAddNew({steps: arrayHelper});
        }

        // Toolbar functions
        this.ToolBarClick = function (bPressed, dData) {

            if (bPressed == 'insert') {
                thisObj.showHideForm();
            }
            if (bPressed == 'retrieve') {
                w2ui[thisObj.gridName].retrieve();
            }
            if (bPressed == 'filter') {
                hideFilter();
            }

        }


        this.update = function () {
            var vt = $('#texthtml').val();
            if (vt == '') {
                messageBoxError('You must add some comment');
                return;
            }

            thisObj.Form.setItem('ds_system_feedback_comments_form', vt);
            thisObj.Form.updateForm();


        }

        // Function to add listeners (events). Here is empty but it is part of the basic object structure
        this.addListeners = function () {

        }

        // It runs before close the screen (by choosing another option on the menu, for example). If you return false the system will not leave the screen
        this.beforeClose = function () {
            return w2ui[thisObj.gridName].getChanges().length == 0;
        }


        // Event that will be triggered when the object is being closed. Location to remove listeners, destroy grids, etc....
        this.close = function () {
            w2ui[thisObj.gridName].destroy();
            introRemove();
            return true;
        }

        this.showHideForm = function () {
            var addhere = $('#addhere');
            if (addhere.html() != '') {
                addhere.empty();
                $('#myGrid').toggleClass('col-md-6 col-md-12');
                w2ui[gridName].resize();
                return true;
            }
            addhere.html(thisObj.varData);


            thisObj.rteObject = $('#texthtml').wysihtml5({
                "link": false, //Button to insert a link. Default true
                "image": false, //Button to insert an image. Default true,
            });

            thisObj.Form = $('#myForm').cgbForm();
            $('#myForm').on('afterUpdate', function (a) {
                thisObj.showHideForm();
                w2ui[thisObj.gridName].add(a.fullData.rs, true);
            });

            $('[data-wysihtml5-command="insertImage"]').hide();

            thisObj.Form.setController('system_feedback_comments');


            $('#myGrid').toggleClass('col-md-6 col-md-12');
            this.Form.setItemPL('ds_system_feedback_comments_type_form', -1, '');
            $('#imgbutton').val('');
            setGrpGridHeight();
            //$('#texthtml').data("wysihtml5").editor.clear();

            //$('#texthtml').setValue('');
            //thisObj.rteObject.setValue('');

        }

        // Place to add general functions
        this.setRenderComment = function (record, index, column_index) {

            var vfield = this.columns[column_index].field;
            var vx = this.columns[column_index].field;
            var vdata = chkUndefined(record[vx], '&nbsp');

            if (vdata == '') {
                vdata = '&nbsp';
            }

            vdata = '<div class="" style="background-color: transparent;max-height: 58px;cursor: pointer;" ondblclick="dsMainObject.openComment(' + record.recid + ')" >' + vdata + '</div>';

            return vdata;
        }


        this.openComment = function (recid) {
            var vx = w2ui[thisObj.gridName].getItem(recid, 'ds_system_feedback_comments');
            // messageBoxAlert(vx);
            $.dialog({
                title: false,
                content: vx,
                columnClass: 'col-md-12 messageBoxCGBClass',
                theme: 'supervan',
                backgroundDismiss: true,
                onOpenBefore: function () {
                    this.$el.css('z-index', '1000000105');
                },

                buttons: false
            });
        }

        this.openAttachment = function (recid) {
            window.open('system_feedback_comments/downloadAttachment/' + recid, '_newtab');

        }

        this.btnPLRender = function (record, index, column_index) {


            var bcanChange = true;
            var vfield = this.columns[column_index].field;
            var vx = this.columns[column_index].field;
            var vdata = chkUndefined(record[vx], '&nbsp');

            if (chkUndefined(record.ds_attachment_path, '') == '') {
                return '&nbsp';
            }

            return '<button type="button" class="btn btn-info btn-xs" aria-label="Left Align" onclick="dsMainObject.openAttachment(' + record.recid + ');" style="width: 30px; height: 30px;"> <i class="fa fa-download" aria-hidden="true"></i> </button>';

            /*
             
             if (!bcanChange) {
             vdata = '<div class="w2ui-data-disabled" style="background-color: transparent">' + vdata + '</div>';
             } else {
             vdata = gridMakePLRender.call(this, record, index, column_index);
             }
             */

            return vdata;
        }


    }

    // funcoes iniciais;
    dsMainObject.start(gridName);

    // funcao da toolbar
    function onGridToolbarPressed(bPressed, dData) {
        dsMainObject.ToolBarClick(bPressed, dData);
    }

    makeFilterWithEnter(function () {
        dsMainObject.ToolBarClick('retrieve');
    });

    function setGrpGridHeight() {
        var hAvail = getWorkArea();
        $("#myGrid").css("height", hAvail);
        $("#texthtml").css("height", hAvail - 130);

        w2ui[gridName].resize();

    }

    // funcao chamada quando o filtro some. tem que existir se existir filtro!
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

    // script for upload file;
    function uploadFile() {
        var file = $("#file")[0].files[0];  //file object文件对象
        if (file == undefined || file.size == 0) {
            messageBoxError('Please choose the file first');
            return;
        }
        $("#btnattachment").attr("disabled", "disabled");
        //fileNum = $("#file")[0].files.length,
        
        

        var shardCount = 20,  //how many shard to divide分成多少片上传
            shardSize = Math.ceil(file.size / 20);    //size of every shard每片大小
        //if each size is equal or greater than maximum upload size of server, set the shard size to 45M
        //如果每片大小超过了服务器充许最大上传大小(我们服务器设置为50M),则每片设置为45M
        if (shardSize >= 50 * 1024 * 1024) {
            shardSize = 45 * 1024 * 1024;
            shardCount = Math.ceil(file.size / shardSize);
        }
        updateProgress(0);
        //asynchronous upload need the last step to deal with the shard pieces
        //异步上传需前面的shard完成后,再在最后一个线程处理所有shard
        for (var i = 1; i < shardCount; ++i) {
            slice_upload(i, shardSize, shardCount, file);
        }
    }

    var upload_succeed = 0;

    function slice_upload(i, shardSize, shardCount, file) {
        
        var name = file.name,        //文件名
            size = file.size;        //总大小
        //计算每一片的起始与结束位置
        var start = (i - 1) * shardSize,
            end = Math.min(size, start + shardSize);
        //构造一个表单，FormData是HTML5新增的
        var form = new FormData();
        form.append("data", file.slice(start, end));  //slice方法用于切出文件的一部分
        form.append("name", name);
        form.append("total", shardCount);  //总片数
        form.append("index", i);        //当前是第几片
        //Ajax提交
        $.ajax({
            url: "file_upload",
            type: "POST",
            data: form,
            async: true,         //异步
            processData: false,  //很重要，告诉jquery不要对form进行处理
            contentType: false,  //很重要，指定为false才能形成正确的Content-Type
            dataType: 'json',
            success: function (res) {
                if (res.success == true) ++upload_succeed;
                else {
                    $("#output").text('Upload failed, please try again!');
                    upload_succeed = 0;
                    return;
                }
                $("#output").text(upload_succeed + " / " + shardCount);
                var percent = ((upload_succeed / shardCount).toFixed(2)) * 100;
                updateProgress(percent);
                if (upload_succeed == shardCount) {
                    $("#btnattachment").removeAttr("disabled");
                    upload_succeed = 0;
                    dsMainObject.Form.setItem('file_name_upload_form', name);
                }
                if (upload_succeed == shardCount - 1) {
                    slice_upload(shardCount, shardSize, shardCount, file);
                }
            }
        });
    }

    function updateProgress(percentage) {
        $('.progress .progress-bar').attr('data-transitiongoal', percentage).progressbar({display_text: 'fill'});
    }
</script>
<div class='row' > 
    <div id="myGrid" style="height: auto;" class='col-md-12 no-padding'> </div>

    <div id="addhere">

        <div id="myForm" style="height: auto;display: block" class='col-md-6 no-padding'>

            <div class="hidden">
                <label for="cd_system_feedback_comments_form" class="col-sm-1 control-label "><?php echo($formTrans_cd_system_feedback_comments) ?>:</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control input-sm"   fieldname="cd_system_feedback_comments" value="-5" id="cd_system_feedback_comments_form"  mask="PK" >
                </div>
            </div>


            <div class="col-sm-12">
                <input type="text" class="form-control input-sm"   fieldname="ds_system_feedback_comments_type"  id="ds_system_feedback_comments_type_form" mask="PLD" model = "<?php echo ($this->encodeModel('system_feedback_comments_type_model')); ?>" fieldname="ds_system_feedback_comments_type" code_field="cd_system_feedback_comments_type"  relid="-1" relCode ="-1" type="text" must="Y">
            </div>

            <div class="col-sm-12 hidden"  style="padding-top: 10px">
                <textarea rows="15" class="form-control input-sm"   fieldname="ds_system_feedback_comments" id="ds_system_feedback_comments_form" mask="c" type="text" maxlength="" ></textarea>
            </div>
            <div class="col-sm-12 hidden">
                <input type="text" class="form-control input-sm"   fieldname="file_name_upload"  id="file_name_upload_form" mask="c"/>
            </div>

            <div class="col-sm-12"  style="padding-top: 10px">
                <textarea rows="15" class="form-control input-sm"   id="texthtml" maxlength="" ></textarea>
            </div>


            <div class="col-sm-12" style="padding-top: 10px" id="buttonsArea">
                <input type="file" id="file" name="file" data-form-send='{"user": 0}' style="display:none" onchange="if($(this)[0].files.length>0){$('#file_name').text($(this)[0].files[0].name);uploadFile();}">
                <button type="button" id='btnClose' onclick="dsMainObject.showHideForm()"  data-toggle="tooltip" title="<?php echo($closeMessage) ?>"  class="btn btn-sm btn-danger pull-right"><i class="fa fa-window-close-o"></i></button>
                <button type="button" id='btnSave'  onclick="dsMainObject.update();" data-toggle="tooltip" title="<?php echo($saveMessage) ?>"  class="btn btn-sm btn-primary  pull-right"><i class="fa fa-paper-plane"></i></button>
                <button type="button" id='btnattachment'  data-toggle="tooltip" title="<?php echo($uploadMessage) ?>" onclick="$('#file').click();"  class="btn btn-sm btn-primary  pull-right"><i class="fa fa-upload"></i></button>
                <span id="file_name" style="font-size:12px;"></span>&nbsp;<span id="output" style="font-size:12px;color: #d9534f;"></span>
                <div class="progress" style="height: 10px;margin-bottom: 0;background-color: transparent;">
                    <div style="line-height: 10px;" id="progressBar" class="progress-bar" role="progressbar" data-transitiongoal=""></div>
                </div>
            </div>


        </div>
    </div>


</div>






