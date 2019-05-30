<form class="form-horizontal" name="formTabajara" id="formTabajara">

    <div class="row">

        <div class="col-sm-1">
            <input type="text" class="form-control" value="_form" id="table_name" name="formsufix" >            
        </div>

        <label for="form_values" class="col-sm-1 control-label ">Value:</label>

        <div class="col-sm-1">
            <input type="text" class="form-control" value="N" id="form_values" name="form_values" >            
        </div>

        
        <label for="folder_name" class="col-sm-1 control-label ">Folder:</label>

        <div class="col-sm-2">
            <input type="text" class="form-control" id="folder_name" name ="folder_name" >            
        </div>

        <label for="table_name" class="col-sm-1 control-label ">Table:</label>

        <div class="col-sm-2">
            <input type="text" class="form-control" id="table_name" name="table_name" >            
        </div>

        <div class="col-sm-2">

            <div class="input-group">
                <div class="input-group-addon">
                    <i class="fa fa-lock"></i>
                </div>
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" autocomplete="off">
            </div>
            <!-- /.input group -->
        </div>
        <!-- /.form group -->


        <button type="buttom" class="btn btn-primary col-sm-1" onclick="sendForm();return false;"> Send </button>

    </div>

    <div class="row"> 

        <label for="modelTX" class="col-md-1">Model:</label>
        <textarea class="form-control col-md-12" rows="10" id="modelTX"></textarea>

    </div>

    <div class="row"> 

        <label for="controllerTX" class="col-md-1">Controller:</label>
        <textarea class="form-control col-md-12" rows="10" id="controllerTX"></textarea>

    </div>

    <div class="row"> 


        <label for="transTX" class="col-md-1">Translation:</label>
        <textarea class="form-control col-md-5" rows="10" id="transTX"></textarea>

        <label for="formTX" class="col-md-1">Form:</label>
        <textarea class="form-control col-md-5" rows="10" id="formTX"></textarea>
    </div>



</form>




<script>

    function sendForm() {
        
        //console.log($('#formTabajara').serialize());

        $('#controllerTX').val('');
        $('#modelTX').val('');
        $('#transTX').val('');
        $('#formTX').val('');

        


        $.myCgbAjax({type: "POST", url: 'generator_tabajara_mysql/makeData',
            data: $('#formTabajara').serialize(), dataType: 'json',
            success: function (data) {
                $('#controllerTX').val(data.controller);
                $('#modelTX').val(data.model);
                $('#transTX').val(data.formTrans);
                $('#formTX').val(data.formInfo);
            }
        }
        );

    }





</script>

