<?php
//setPLRelCode
?>

<script>
// aqui tem os scripts basicos. 
//var controllerName = "country";



//$(".ds_hr_type").on( "change",  function() {

    var gridName = "gridChanges";

    var dsMainObject = new function () {

        // variaveis privadas;

        var thisObj = this;
        thisObj.gridName = 'gridChanges';
        thisObj.lastPrjCode = -20;

        this.start = function () {

<?php echo($grid) ?>

            thisObj.Form = $('#formPrj').cgbForm({updController: 'assets/assets'});

            this.setScreenPermissions();

            this.addListeners();
            this.addHelper();



            thisObj.makeScreenInformation();

        }
        
        this.ToolBarClick = function(a) {
            
        }

        this.findData = function () {
            var vdata = {};
            vdata['number'] = $('#ds_assets_number_search_form').val();


            $.myCgbAjax({url: 'assets/assets/getByNumber',
                message: javaMessages.retrieveData,
                box: '#content-body',
                data: vdata,
                success: function (a) {
                    console.log('a', a);
                    thisObj.Form.recordsetToForm(a.rs);
                    w2ui[thisObj.gridName].clear();
                    w2ui[thisObj.gridName].add(a.hist);

                },
            });
        }


        this.addHelper = function () {
            var arrayHelper = [];
            //$.merge(arrayHelper, introAddFilterArea());
            //$.merge(arrayHelper,w2ui[thisObj.gridName].toolbar.getIntroHelp());
            //$.merge(arrayHelper, w2ui[thisObj.gridName].getIntroHelp());

            //introAddNew({steps: arrayHelper});
        }


        // adicao de listeners!
        this.addListeners = function () {


            $('#ds_assets_number_search_form').keypress(function (e) {
                if (e.which == 13) {  //Enter is key 13
                    thisObj.findData();
                }
            });

            $('#saveData').on('click', function () {
                thisObj.updateData()
            });




            $('#formPrj').on('afterUpdate', function (ev) {
                thisObj.action = 'E';
                //w2ui['gridPrjModel'].clear()
                //w2ui['gridPrjModel'].add(ev.fullData.gridData);
                //$(window).trigger('prjChanged', ev.fullData);

                thisObj.findData();

                thisObj.makeScreenInformation();


            });

        }



        // roda antes de fechar (se retornar FALSE o sistema vai perguntar se quer fechar
        this.beforeClose = function () {
            return true;
        }


        // close object (lugar para destruir as coisas//
        this.close = function () {
            //introRemove();
            return true;
        }


        this.updateData = function () {
            messageBoxYesNo(javaMessages.confirm, function () {
                thisObj.Form.updateForm();
            })

        }

        this.makeScreenInformation = function () {

        }

        this.setScreenPermissions = function () {


        }


        this.setGridAsChanged = function () {

        }

        this.setDemandedAsChanged = function () {
        }


    }

    function setGrpGridHeight() {
        var hAvail = getWorkArea() - $('#historyArea').position().top + 50;

        if (hAvail < 0) {
            hAvail = 100;
        }


        $("#gridDiv").css("height", hAvail);
        w2ui['gridChanges'].resize();

    }


// funcoes iniciais;
    dsMainObject.start();

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





// insiro colunas;

</script>

<div id="divPrjForm" > 

    <form id="formPrj" class="form-horizontal">


        <div class="hidden">
            <label for="cd_assets_form" class="col-sm-1 control-label "><?php echo($formTrans_cd_assets) ?>:</label>
            <div class="col-sm-2">
                <input type="text" class="form-control input-md"   fieldname="cd_assets" id="cd_assets_form"  mask="PK" >
            </div>

            <label for="ds_assets_number_form" class="col-sm-1 control-label "><?php echo($formTrans_ds_assets_number) ?>:</label>
            <div class="col-sm-2">
                <input type="text" class="form-control input-md"   fieldname="ds_assets_number" id="ds_assets_number_form" mask="c" type="text" maxlength="" >
            </div>
        </div>



        <div class="row">
            <div class="col-md-4">    
                <div class="box box-info box-solid" >
                    <div class="box-header with-border">
                        <h3 class="box-title"> <?php echo($formTrans_ds_assets)?> </h3>
                        <div class="box-tools pull-right">
                            <button type='button' class='btn btn-box-tool' data-widget="collapse"> <i class='fa fa-compress'></i> </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="col-sm-12">
                            <input type="text" class="form-control input-md"   fieldname="ds_assets_number_search" id="ds_assets_number_search_form" mask="c" type="text" maxlength="" >
                        </div>
                    </div>
                </div>
            </div>
        </div> 


        <div class="row">
            <div class="col-md-12">    
                <div class="box box-info box-solid" >
                    <div class="box-header with-border">
                        <h3 class="box-title"> <?php echo($detailsLabel)?> </h3>
                        <div class="box-tools pull-right">
                            <button type='button' class='btn btn-box-tool' data-widget="collapse"> <i class='fa fa-compress'></i> </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="col-md-12">

                            <label for="ds_assets_form" class="col-sm-1 control-label "><?php echo($formTrans_ds_assets) ?>:</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control input-md"   fieldname="ds_assets" id="ds_assets_form" mask="c" type="text" maxlength="" ro="Y" >
                            </div>

                            <label for="ds_assets_book_form" class="col-sm-1 control-label "><?php echo($formTrans_cd_assets_book) ?>:</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control input-md"   fieldname="ds_assets_book" id="ds_assets_book_form" mask="PL" model = "<?php echo ($this->encodeModel('assets/assets_book_model')); ?>" fieldname="ds_assets_book" code_field="cd_assets_book"  relid="-1" relCode ="-1" type="text" ro="Y" >
                            </div>

                            <label for="dt_asset_form" class="col-sm-1 control-label "><?php echo($formTrans_dt_asset) ?>:</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control input-md"   fieldname="dt_asset" id="dt_asset_form" ro="Y" >
                            </div>

                            <label for="ds_pr_contract_number_form" class="col-sm-1 control-label "><?php echo($formTrans_ds_pr_contract_number) ?>:</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control input-md"   fieldname="ds_pr_contract_number" id="ds_pr_contract_number_form" mask="c" type="text" maxlength="" ro="Y" >
                            </div>


                            <label for="ds_department_ref_number_form" class="col-sm-1 control-label "><?php echo($formTrans_ds_department_ref_number) ?>:</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control input-md"   fieldname="ds_department_ref_number" id="ds_department_ref_number_form" mask="c" type="text" maxlength=""  ro="Y" >
                            </div>

                            <label for="nr_qty_form" class="col-sm-1 control-label "><?php echo($formTrans_nr_qty) ?>:</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control input-md"   fieldname="nr_qty" id="nr_qty_form" mask="I"  ro="Y" >
                            </div>                            

                            <label for="ds_assets_number_old_form" class="col-sm-1 control-label "><?php echo($formTrans_ds_assets_number_old) ?>:</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control input-md"   fieldname="ds_assets_number_old" id="ds_assets_number_old_form" mask="c" type="text" maxlength=""  ro="Y" >
                            </div>


                        </div>



                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">    
                <div class="box box-info box-solid" >
                    <div class="box-header with-border">
                        <h3 class="box-title"> <?php echo($revisionLabel)?> </h3>
                        <div class="box-tools pull-right">
                            <button type='button' class='btn btn-box-tool' data-widget="collapse"> <i class='fa fa-compress'></i> </button>
                        </div>
                    </div>
                    <div class="box-body">

                        <div class="col-lg-11 col-md-11 col-sm-12" >

                            <label for="ds_department_cost_center_form" class="col-sm-1 control-label "><?php echo($formTrans_cd_department_cost_center) ?>:</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control input-md" style=""  fieldname="ds_department_cost_center" id="ds_department_cost_center_form" mask="PL" model = "<?php echo ($this->encodeModel('rfq/department_cost_center_model')); ?>" fieldname="ds_department_cost_center" code_field="cd_department_cost_center"  relid="-1" relCode ="-1" type="text">
                            </div>

                            <label for="ds_assets_location_room_form" class="col-sm-1 control-label "><?php echo($formTrans_cd_assets_location_room) ?>:</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control input-md"   fieldname="ds_assets_location_room" id="ds_assets_location_room_form" mask="PL" model = "<?php echo ($this->encodeModel('assets/assets_location_room_model')); ?>" fieldname="ds_assets_location_room" code_field="cd_assets_location_room"  relid="-1" relCode ="-1" type="text" >
                            </div>


                            <label for="ds_human_resource_responsible_form" class="col-sm-1 control-label "><?php echo($formTrans_cd_human_resource_responsible) ?>:</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control input-md"   fieldname="ds_human_resource_responsible" id="ds_human_resource_responsible_form" mask="PL" model = "<?php echo ($this->encodeModel('human_resource_model')); ?>" fieldname="ds_human_resource_responsible" code_field="cd_human_resource_responsible"  relid="-1" relCode ="-1" type="text">
                            </div>

                            <label for="ds_remarks_form" class="col-sm-1 control-label "><?php echo($formTrans_ds_remarks) ?>:</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control input-md"   fieldname="ds_remarks" id="ds_remarks_form" mask="t" type="text" maxlength="" >
                            </div>




                        </div> 
                        <button type="button" class="btn btn-primary btn-lg pull-right"  id="saveData"><i class="fa fa-floppy-o" aria-hidden="true"></i></button>
                        <div class="col-md-12 hidden" style="padding-top: 10px;padding-right: 30px;">
                            
                        </div>                        

                    </div>
                </div>
            </div>
        </div>


        <div class="row" id="historyArea">
            <div class="col-md-12">    
                <div class="box box-info box-solid" >
                    <div class="box-header with-border">
                        <h3 class="box-title"> <?php echo($historyLabel)?> </h3>
                        <div class="box-tools pull-right">
                            <button type='button' class='btn btn-box-tool' data-widget="collapse"> <i class='fa fa-compress'></i> </button>
                        </div>
                    </div>

                    <div class="box-body">
                        <div class="col-sm-12">
                            <div id="gridDiv" style="height: auto;" class='row'> </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> 






    </form>
</div>        








</form>
</div>



