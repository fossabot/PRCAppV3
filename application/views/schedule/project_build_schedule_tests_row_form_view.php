<?php
$attrAddon = '';
$menuClassAddon = '';
$sttrAddonPlanning = '';
if ($dt_deactivated_schedule != '') {
    $attrAddon = ' RO="Y" ';
    $menuClassAddon = 'hidden';
}

if ($fl_can_change_dates == 'N') {
    $sttrAddonPlanning = ' RO="Y" ';
}
?>

<div class="row schtstdiv schNumberClass<?php echo($cd_project_build_schedule) ?>" id="testArea_<?php echo($cd_project_build_schedule_tests) ?>">

    <div class="col-lg-10 col-md-9 no-padding">
        <div class="hidden">
            <label for="cd_project_build_schedule_tests_<?php echo($cd_project_build_schedule_tests) ?>_form" class="col-md-1 control-label "><?php echo($formTrans_cd_project_build_schedule_tests) ?>:</label>
            <div class="col-md-2">
                <input type="text" class="form-control input-sm"   value="<?php echo($cd_project_build_schedule_tests) ?>" <?php echo($attrAddon) ?>   order="<?php echo($cd_project_build_schedule_tests) ?>" indexrs = "<?php echo($cd_project_build_schedule) ?>" fieldname="cd_project_build_schedule_tests" id="cd_project_build_schedule_tests_<?php echo($cd_project_build_schedule_tests) ?>_form"  addinfo="PLAN" mask="I" sc="<?php echo($sc) ?>">
            </div>
        </div>

        <div class="col-md-12 no-padding" style="padding-left: 30px !important;">
            <div style="position: absolute; left: 0px; top: 0px;width: 28px;height: 100%" class="<?php echo($menuClassAddon) ?>">
                <div class="btn-group-vertical">
                    <button type="button" class="btn btn-addon btn-default" data-toggle="tooltipa" title="<?php echo($insertTooltip) ?>"  onclick="dsFormPrjSheetObject.insertTestData(<?php echo($cd_project_build_schedule) ?>); return false;"><i class='fa fa-plus' ></i> </button> 
                    <button type="button" class="btn btn-addon btn-default" data-toggle="tooltipa" title="<?php echo($formTrans_work_orders) ?>"  onclick="dsFormPrjSheetObject.workOrderMaint(<?php echo($cd_project_build_schedule_tests) ?>, <?php echo($cd_project_build_schedule) ?>); return false;"><i class='fa fa-link' ></i> </button> 
                    <button type="button" class="btn btn-addon btn-default" data-toggle="tooltipa" title="<?php echo($duplicateTooltip) ?>" onclick="dsFormPrjSheetObject.duplicateTest(<?php echo($cd_project_build_schedule_tests) ?>, <?php echo($cd_project_build_schedule) ?>); return false;"><i class='fa fa-files-o' ></i> </button>
                    <button type="button" class="btn btn-addon btn-default" data-toggle="tooltipa" title="<?php echo($shiftTooltip) ?>" id='buttonshift<?php echo($cd_project_build_schedule_tests) ?>' onclick="dsFormPrjSheetObject.shiftDatesAsk(<?php echo($cd_project_build_schedule) ?>, <?php echo($cd_project_build_schedule_tests) ?>); return false;"><i class='fa fa-calendar' ></i> </button>
                    <button type="button" class="btn btn-addon btn-default" data-toggle="tooltipa" title="<?php echo($deleteTooltip) ?>" onclick="dsFormPrjSheetObject.deleteTest(<?php echo($cd_project_build_schedule_tests) ?>, <?php echo($cd_project_build_schedule) ?>); return false;"><i class='fa fa-trash-o' ></i> </button> 
                </div>

            </div>
            <div class='row' style='margin-left: 0px'>

                <div class="hidden">
                    <label for="ds_schedule_test_status_<?php echo($cd_project_build_schedule_tests) ?>_form" class="col-md-1 control-label "><?php echo($formTrans_cd_schedule_test_status) ?>:</label>
                    <div class="col-md-2">
                        <input type="text" class="form-control input-sm"   plcode="<?php echo($cd_schedule_test_status) ?>"  <?php echo($attrAddon) ?>  order="<?php echo($cd_project_build_schedule_tests) ?>" indexrs = "<?php echo($cd_project_build_schedule) ?>" value="<?php hecho($ds_schedule_test_status) ?>" fieldname="ds_schedule_test_status" id="ds_schedule_test_status_<?php echo($cd_project_build_schedule_tests) ?>_form" addinfo="PLAN" mask="PLD" model = "<?php echo ($this->encodeModel('schedule/schedule_test_status_model')); ?>" fieldname="ds_schedule_test_status" code_field="cd_schedule_test_status"  relid="-1" relCode ="-1" type="text" >
                    </div>
                </div>


                <label for="ds_test_type_<?php echo($cd_project_build_schedule_tests) ?>_form" class="col-md-1 control-label "><?php echo($formTrans_cd_test_type) ?>:</label>
                <div class="col-md-4"  style="padding-right: 42px">
                    <input type="text" class="form-control input-sm"   plcode="<?php echo($cd_test_type) ?>"  value="<?php hecho($ds_test_type) ?>"  <?php echo($attrAddon) ?>  order="<?php echo($cd_project_build_schedule_tests) ?>" indexrs = "<?php echo($cd_project_build_schedule) ?>" fieldname="ds_test_type" id="ds_test_type_<?php echo($cd_project_build_schedule_tests) ?>_form" addinfo="PLAN" mask="PLD" model = "<?php echo ($this->encodeModel('tr/test_type_model')); ?>"  <?php echo($attrAddon) ?>  order="<?php echo($cd_project_build_schedule_tests) ?>" indexrs = "<?php echo($cd_project_build_schedule) ?>" fieldname="ds_test_type" code_field="cd_test_type"  relid="-1" relCode ="-1" type="text"  sc="<?php echo($sc) ?>" must="Y">
                </div>  

                <label for="ds_test_item_<?php echo($cd_project_build_schedule_tests) ?>_form" class="col-md-1 control-label "><?php echo($formTrans_cd_test_item) ?>:</label>
                <div class="col-md-2">
                    <input type="text" class="form-control input-sm"   value="<?php hecho($ds_test_item) ?>" fieldname="ds_test_item"  <?php echo($attrAddon) ?>  order="<?php echo($cd_project_build_schedule_tests) ?>" indexrs = "<?php echo($cd_project_build_schedule) ?>" id="ds_test_item_<?php echo($cd_project_build_schedule_tests) ?>_form" addinfo="PLAN" mask="c" type="text" maxlength="128" sc="<?php echo($sc) ?>">
                </div>

                <label for="ds_location_form" class="col-md-1 control-label "><?php echo($formTrans_cd_location) ?>:</label>
                <div class="col-md-2">
                    <input type="text" class="form-control input-sm"   plcode="<?php echo($cd_location) ?>"  value="<?php hecho($ds_location) ?>" fieldname="ds_location" id="ds_location_<?php echo($cd_project_build_schedule_tests) ?>_form" addinfo="PLAN" mask="PLD"  <?php echo($attrAddon) ?>  order="<?php echo($cd_project_build_schedule_tests) ?>" indexrs = "<?php echo($cd_project_build_schedule) ?>" model = "<?php echo ($this->encodeModel('location_model')); ?>" fieldname="ds_location" code_field="cd_location"   relid="-1" relCode ="-1" type="text" sc="<?php echo($sc) ?>">
                </div>                

                <div class="hidden">
                    <label for="ds_tests_<?php echo($cd_project_build_schedule_tests) ?>_form" class="col-md-1 control-label "><?php echo($formTrans_cd_tests) ?>:</label>
                    <div class="col-md-2">
                        <input type="text" class="form-control input-sm"   plcode="<?php echo($cd_tests) ?>"  value="<?php hecho($ds_tests) ?>"  <?php echo($attrAddon) ?>  order="<?php echo($cd_project_build_schedule_tests) ?>" indexrs = "<?php echo($cd_project_build_schedule) ?>" fieldname="ds_tests" id="ds_tests_<?php echo($cd_project_build_schedule_tests) ?>_form" addinfo="PLAN" mask="PLD" model = "<?php echo ($this->encodeModel('tr/tests_model')); ?>"  <?php echo($attrAddon) ?>  order="<?php echo($cd_project_build_schedule_tests) ?>" indexrs = "<?php echo($cd_project_build_schedule) ?>" fieldname="ds_tests" code_field="cd_tests"  relid="-1" relCode ="-1" type="text"  sc="<?php echo($sc) ?>">
                    </div>
                </div>
            </div>
            <span class='input-group-btn hidden' > <button class="btn btn-addon btn-default calcButton" sch="<?php echo($cd_project_build_schedule_tests) ?>" onclick="dsFormPrjSheetObject.btnCalculateDays(<?php echo($cd_project_build_schedule_tests) ?>); return false;"><i class='fa fa-calculator' ></i> </button> </span>
            <div class='col-md-12 no-padding'>

                <div class='row' style='margin-left: 0px'>

                    <label for="dt_est_start_<?php echo($cd_project_build_schedule_tests) ?>_form" class="col-md-1 control-label "><?php echo($formTrans_dt_est_start) ?>:</label>
                    <div class='col-md-4' style="padding-right: 42px">
                        <div class="input-group ">
                            <input type="text" class="form-control input-sm" typeShift ="1"  value="<?php echo($dt_est_start) ?>"  <?php echo($attrAddon . $sttrAddonPlanning) ?>  order="<?php echo($cd_project_build_schedule_tests) ?>" indexrs = "<?php echo($cd_project_build_schedule) ?>" fieldname="dt_est_start" id="dt_est_start_<?php echo($cd_project_build_schedule_tests) ?>_form"  sc="<?php echo($sc) ?>" addinfo="PLAN" >
                            <div class="input-group-addon" style='padding: 5px;'> - </div>
                            <input type="text" class="form-control input-sm" typeShift ="1"  value="<?php echo($dt_est_finish) ?>" <?php echo($attrAddon . $sttrAddonPlanning) ?> <?php echo($attrAddon) ?>  order="<?php echo($cd_project_build_schedule_tests) ?>" indexrs = "<?php echo($cd_project_build_schedule) ?>" fieldname="dt_est_finish" id="dt_est_finish_<?php echo($cd_project_build_schedule_tests) ?>_form"  sc="<?php echo($sc) ?>" addinfo="PLAN" >
                            <span type="text" class="input-group-addon  input-sm" data-toggle="tooltip" title="<?php echo($formTrans_nr_days) ?>"  <?php echo($attrAddon) ?>  order="<?php echo($cd_project_build_schedule_tests) ?>" indexrs = "<?php echo($cd_project_build_schedule) ?>" id="nr_days_<?php echo($cd_project_build_schedule_tests) ?>_form" addinfo="PLAN" mask="G" style="width: 26px;"> </span>
                        </div>
                    </div>

                    <label for="ds_test_unit_<?php echo($cd_project_build_schedule_tests) ?>_form" class="col-md-1 control-label "><?php echo($formTrans_ds_test_unit) ?>:</label>
                    <div class="col-md-2">
                        <input type="text" class="form-control input-sm"   plcode="<?php echo($cd_test_unit) ?>"  <?php echo($attrAddon) ?>  order="<?php echo($cd_project_build_schedule_tests) ?>" indexrs = "<?php echo($cd_project_build_schedule) ?>"  value="<?php hecho($ds_test_unit) ?>" fieldname="ds_test_unit" id="ds_test_unit_<?php echo($cd_project_build_schedule_tests) ?>_form" addinfo="PLAN" mask="PLD" model = "<?php echo ($this->encodeModel('tr/test_unit_model')); ?>" fieldname="ds_test_unit" code_field="cd_test_unit"  relid="-1" relCode ="-1" type="text" sc="<?php echo($sc) ?>">
                    </div>

                    <label for="ds_wi_<?php echo($cd_project_build_schedule_tests) ?>_form" class="col-md-1 control-label "><?php echo($formTrans_ds_wi) ?>:</label>
                    <div class="col-md-3">
                        <div class="input-group">
                            <input type="text" class="form-control input-sm" value="<?php hecho($ds_wi) ?>" fieldname="ds_wi"  <?php echo($attrAddon) ?>  order="<?php echo($cd_project_build_schedule_tests) ?>" indexrs = "<?php echo($cd_project_build_schedule) ?>" id="ds_wi_<?php echo($cd_project_build_schedule_tests) ?>_form" addinfo="PLAN" mask="c" type="text" maxlength="128" sc="<?php echo($sc) ?>">
                            <div class="input-group-addon" style='padding: 5px;'> / </div>
                            <input type="text" class="form-control input-sm" value="<?php hecho($ds_wi_section) ?>" fieldname="ds_wi_section"  <?php echo($attrAddon) ?>  order="<?php echo($cd_project_build_schedule_tests) ?>" indexrs = "<?php echo($cd_project_build_schedule) ?>" id="ds_wi_section_<?php echo($cd_project_build_schedule_tests) ?>_form" addinfo="PLAN" mask="c" type="text" maxlength="128" sc="<?php echo($sc) ?>">
                        </div>
                    </div>
                </div>

                <div class='row' style='margin-left: 0px'>
                    <label for="dt_start_<?php echo($cd_project_build_schedule_tests) ?>_form" class="col-md-1 control-label "><?php echo($formTrans_dt_start) ?>:</label>
                    <div class='col-md-4' style="padding-right: 42px">
                        <div class="input-group ">
                            <input type="text" class="form-control input-sm col-md-8" typeShift ="2"  value="<?php echo($dt_start) ?>"  <?php echo($attrAddon) ?>  order="<?php echo($cd_project_build_schedule_tests) ?>" indexrs = "<?php echo($cd_project_build_schedule) ?>" fieldname="dt_start" id="dt_start_<?php echo($cd_project_build_schedule_tests) ?>_form"  sc="<?php echo($sc) ?>" addinfo="PLAN" >
                            <div class="input-group-addon" style='padding: 5px;'> - </div>
                            <input type="text" class="form-control input-sm" typeShift ="2"  value="<?php echo($dt_finish) ?>"  <?php echo($attrAddon) ?>  order="<?php echo($cd_project_build_schedule_tests) ?>" indexrs = "<?php echo($cd_project_build_schedule) ?>" fieldname="dt_finish" id="dt_finish_<?php echo($cd_project_build_schedule_tests) ?>_form"  sc="<?php echo($sc) ?>" addinfo="PLAN" >
                            <span type="text" class="input-group-addon  input-sm" data-toggle="tooltip" title="<?php echo($formTrans_nr_days) ?>"  <?php echo($attrAddon) ?>  order="<?php echo($cd_project_build_schedule_tests) ?>" indexrs = "<?php echo($cd_project_build_schedule) ?>" id="nr_days_agreed_<?php echo($cd_project_build_schedule_tests) ?>_form" addinfo="PLAN" mask="G" style="width: 26px;"> </span>

                        </div>
                    </div>

                    <label for="nr_goal_<?php echo($cd_project_build_schedule_tests) ?>_form" class="col-md-1 control-label "><?php echo($formTrans_nr_goal) ?>:</label>
                    <div class="col-md-2">
                        <input type="text" class="form-control input-sm"   value="<?php echo($nr_goal) ?>"  <?php echo($attrAddon) ?>  order="<?php echo($cd_project_build_schedule_tests) ?>" indexrs = "<?php echo($cd_project_build_schedule) ?>" fieldname="nr_goal" id="nr_goal_<?php echo($cd_project_build_schedule_tests) ?>_form" addinfo="PLAN" mask="N;10.2"  sc="<?php echo($sc) ?>">
                    </div>

                    <label for="ds_tr_wi_data_form" class="col-md-1 control-label "><?php echo($formTrans_cd_tr_wi_data) ?>:</label>
                    <div class="col-md-3">
                        <input type="text" class="form-control input-sm"   plcode="<?php echo($cd_tr_wi_data) ?>"  value="<?php hecho($ds_tr_wi_data) ?>" id="ds_tr_wi_data_<?php echo($cd_project_build_schedule_tests) ?>_form" addinfo="PLAN" mask="PLD"  <?php echo($attrAddon) ?>  order="<?php echo($cd_project_build_schedule_tests) ?>" indexrs = "<?php echo($cd_project_build_schedule) ?>" model = "<?php echo ($this->encodeModel('tr/tr_wi_data_model')); ?>" fieldname="ds_tr_wi_data" code_field="cd_tr_wi_data"   relid="-1" relCode ="-1" type="text" sc="<?php echo($sc) ?>">
                    </div>                
                    
                    
                </div>

                <div class='row' style='margin-left: 0px'>

                    <label for="dt_actual_start_<?php echo($cd_project_build_schedule_tests) ?>_form" class="col-md-1 control-label "><?php echo($formTrans_dt_actual_start) ?>:</label>
                    <div class='col-md-4' style="padding-right: 42px">
                        <div class="input-group ">
                            <input type="text" class="form-control input-sm"   value="<?php echo($dt_actual_start) ?>"  <?php echo($attrAddon) ?>  order="<?php echo($cd_project_build_schedule_tests) ?>" indexrs = "<?php echo($cd_project_build_schedule) ?>" fieldname="dt_actual_start" id="dt_actual_start_<?php echo($cd_project_build_schedule_tests) ?>_form"  sc="<?php echo($sc) ?>" ro="Y" addinfo="PLAN" >
                            <div class="input-group-addon" style='padding: 5px;'> - </div>
                            <input type="text" class="form-control input-sm"   value="<?php echo($dt_actual_finish) ?>"  <?php echo($attrAddon) ?>  order="<?php echo($cd_project_build_schedule_tests) ?>" indexrs = "<?php echo($cd_project_build_schedule) ?>" fieldname="dt_actual_finish" id="dt_actual_finish_<?php echo($cd_project_build_schedule_tests) ?>_form"  sc="<?php echo($sc) ?>" ro="Y" addinfo="PLAN" >
                            <span type="text" class="input-group-addon  input-sm" data-toggle="tooltip" title="<?php echo($formTrans_nr_days) ?>"  <?php echo($attrAddon) ?>  order="<?php echo($cd_project_build_schedule_tests) ?>" indexrs = "<?php echo($cd_project_build_schedule) ?>" id="nr_days_actual_<?php echo($cd_project_build_schedule_tests) ?>_form" addinfo="PLAN" mask="G" style="width: 26px;"> </span>
                        </div>
                    </div>

                    <label for="nr_output_<?php echo($cd_project_build_schedule_tests) ?>_form" class="col-md-1 control-label "><?php echo($formTrans_nr_output) ?>:</label>
                    <div class="col-md-2">
                        <input type="text" class="form-control input-sm"   value="<?php echo($nr_output) ?>"  <?php echo($attrAddon) ?>  order="<?php echo($cd_project_build_schedule_tests) ?>" indexrs = "<?php echo($cd_project_build_schedule) ?>" fieldname="nr_output" id="nr_output_<?php echo($cd_project_build_schedule_tests) ?>_form" addinfo="PLAN" mask="N;10.2"  sc="<?php echo($sc) ?>">
                    </div>

                    <label for="nr_sample_quantity_<?php echo($cd_project_build_schedule_tests) ?>_form" class="col-md-1 control-label "><?php echo($formTrans_nr_sample_quantity) ?>:</label>
                    <div class="col-md-2">
                        <input type="text" class="form-control input-sm"   value="<?php echo($nr_sample_quantity) ?>"  <?php echo($attrAddon) ?>  order="<?php echo($cd_project_build_schedule_tests) ?>" indexrs = "<?php echo($cd_project_build_schedule) ?>" fieldname="nr_sample_quantity" id="nr_sample_quantity_<?php echo($cd_project_build_schedule_tests) ?>_form" addinfo="PLAN" mask="I"  sc="<?php echo($sc) ?>">
                    </div>




                </div>
                <div class='row' style='margin-left: 0px'>

                    <label for="nr_priority_<?php echo($cd_project_build_schedule_tests) ?>_form" class="col-md-1 control-label "><?php echo($formTrans_nr_priority) ?>:</label>
                    <div class="col-md-1">
                        <input type="text" class="form-control input-sm"   value="<?php hecho($nr_priority) ?>" fieldname="nr_priority" order="<?php echo($cd_project_build_schedule_tests) ?>" indexrs = "<?php echo($cd_project_build_schedule) ?>" id="nr_priority_<?php echo($cd_project_build_schedule_tests) ?>_form"  addinfo="PLAN" mask="I" ro="Y" >
                    </div>


                    <label for="nr_headcount_requested_day_<?php echo($cd_project_build_schedule_tests) ?>_form" class="col-md-1 control-label "><?php echo($formTrans_nr_headcount_requested_day) ?>:</label>
                    <div class="col-md-1">
                        <input type="text" class="form-control input-sm"   value="<?php hecho($nr_headcount_requested_day) ?>" fieldname="nr_headcount_requested_day" order="<?php echo($cd_project_build_schedule_tests) ?>" indexrs = "<?php echo($cd_project_build_schedule) ?>" id="nr_headcount_requested_day_<?php echo($cd_project_build_schedule_tests) ?>_form"  addinfo="PLAN" mask="N;4.1" >
                    </div>

                    <label for="nr_headcount_allocated_day_<?php echo($cd_project_build_schedule_tests) ?>_form" class="col-md-1 col-md-offset-1 control-label "><?php echo($formTrans_nr_headcount_allocated_day) ?>:</label>
                    <div class="col-md-1">
                        <input type="text" class="form-control input-sm"   value="<?php hecho($nr_headcount_allocated_day) ?>" fieldname="nr_headcount_allocated_day" order="<?php echo($cd_project_build_schedule_tests) ?>" indexrs = "<?php echo($cd_project_build_schedule) ?>" id="nr_headcount_allocated_day_<?php echo($cd_project_build_schedule_tests) ?>_form"  addinfo="PLAN" mask="N;4.1" ro="Y" >
                    </div>

                    <div class='col-md-5 no-padding'>
                        <label for="fl_witness_<?php echo($cd_project_build_schedule_tests) ?>_form" class="col-md-3 control-label "><?php echo($formTrans_fl_witness) ?>:</label>
                        <div class="col-md-3">
                            <input type="checkbox" class="form-control input-sm"   value="<?php echo($fl_witness) ?>"  <?php echo($attrAddon) ?>  order="<?php echo($cd_project_build_schedule_tests) ?>" indexrs = "<?php echo($cd_project_build_schedule) ?>" fieldname="fl_witness" id="fl_witness_<?php echo($cd_project_build_schedule_tests) ?>_form" addinfo="PLAN" mask="CHK"  sc="<?php echo($sc) ?>">
                        </div>
                        <label for="fl_eol_<?php echo($cd_project_build_schedule_tests) ?>_form" class="col-md-3 control-label "><?php echo($formTrans_fl_eol) ?>:</label>
                        <div class="col-md-3">
                            <input type="checkbox" class="form-control input-sm"   value="<?php echo($fl_eol) ?>"  <?php echo($attrAddon) ?>  order="<?php echo($cd_project_build_schedule_tests) ?>" indexrs = "<?php echo($cd_project_build_schedule) ?>" fieldname="fl_eol" id="fl_eol_<?php echo($cd_project_build_schedule_tests) ?>_form" addinfo="PLAN" mask="CHK"  sc="<?php echo($sc) ?>">
                        </div>
                    </div>





                </div>


                <div class='row' style='margin-left: 0px'>
                    <label for="ds_specification_<?php echo($cd_project_build_schedule_tests) ?>_form" class="col-md-1 control-label "><?php echo($formTrans_ds_specification) ?>:</label>
                    <div class="col-md-11">
                        <textarea class="form-control input-sm"  <?php echo($attrAddon) ?>  order="<?php echo($cd_project_build_schedule_tests) ?>" indexrs = "<?php echo($cd_project_build_schedule) ?>" fieldname="ds_specification" id="ds_specification_<?php echo($cd_project_build_schedule_tests) ?>_form" addinfo="PLAN" mask="c" type="text" maxlength=""  sc="<?php echo($sc) ?>"><?php hecho($ds_specification) ?></textarea>
                    </div>

                    <div class='hidden'>
                        <label for="ds_sample_description_<?php echo($cd_project_build_schedule_tests) ?>_form" class="col-md-1 control-label "><?php echo($formTrans_ds_sample_description) ?>:</label>
                        <div class="col-md-3">
                            <textarea class="form-control input-sm"   <?php echo($attrAddon) ?>  order="<?php echo($cd_project_build_schedule_tests) ?>" indexrs = "<?php echo($cd_project_build_schedule) ?>" fieldname="ds_sample_description" id="ds_sample_description_<?php echo($cd_project_build_schedule_tests) ?>_form" addinfo="PLAN" mask="c" type="text" maxlength=""  sc="<?php echo($sc) ?>"><?php hecho($ds_sample_description) ?></textarea>
                        </div>

                        <label for="ds_extra_instruction_<?php echo($cd_project_build_schedule_tests) ?>_form" class="col-md-1 control-label "><?php echo($formTrans_ds_extra_instruction) ?>:</label>
                        <div class="col-md-3">
                            <textarea class="form-control input-sm"    <?php echo($attrAddon) ?>  order="<?php echo($cd_project_build_schedule_tests) ?>" indexrs = "<?php echo($cd_project_build_schedule) ?>" fieldname="ds_extra_instruction" id="ds_extra_instruction_<?php echo($cd_project_build_schedule_tests) ?>_form" addinfo="PLAN" mask="c" type="text" maxlength=""  sc="<?php echo($sc) ?>"><?php hecho($ds_extra_instruction) ?></textarea>
                        </div>
                    </div>

                </div>




                <div class='row' style='margin-left: 0px'>

                    <div class="hidden">

                        <label for="nr_charger_quantity_<?php echo($cd_project_build_schedule_tests) ?>_form" class="col-md-1 control-label "><?php echo($formTrans_nr_charger_quantity) ?>:</label>
                        <div class="col-md-2">
                            <input type="text" class="form-control input-sm"   value="<?php echo($nr_charger_quantity) ?>"  <?php echo($attrAddon) ?>  order="<?php echo($cd_project_build_schedule_tests) ?>" indexrs = "<?php echo($cd_project_build_schedule) ?>" fieldname="nr_charger_quantity" id="nr_charger_quantity_<?php echo($cd_project_build_schedule_tests) ?>_form" addinfo="PLAN" mask="I"  sc="<?php echo($sc) ?>">
                        </div>

                        <label for="nr_power_pack_quantity_<?php echo($cd_project_build_schedule_tests) ?>_form" class="col-md-1 control-label "><?php echo($formTrans_nr_power_pack_quantity) ?>:</label>
                        <div class="col-md-2">
                            <input type="text" class="form-control input-sm"   value="<?php echo($nr_power_pack_quantity) ?>"  <?php echo($attrAddon) ?>  order="<?php echo($cd_project_build_schedule_tests) ?>" indexrs = "<?php echo($cd_project_build_schedule) ?>" fieldname="nr_power_pack_quantity" id="nr_power_pack_quantity_<?php echo($cd_project_build_schedule_tests) ?>_form" addinfo="PLAN" mask="I"  sc="<?php echo($sc) ?>">
                        </div>

                        <label for="nr_accessory_qty_<?php echo($cd_project_build_schedule_tests) ?>_form" class="col-md-1 control-label "><?php echo($formTrans_nr_accessory_qty) ?>:</label>
                        <div class="col-md-2">
                            <input type="text" class="form-control input-sm"   value="<?php echo($nr_accessory_qty) ?>"  <?php echo($attrAddon) ?>  order="<?php echo($cd_project_build_schedule_tests) ?>" indexrs = "<?php echo($cd_project_build_schedule) ?>" fieldname="nr_accessory_qty" id="nr_accessory_qty_<?php echo($cd_project_build_schedule_tests) ?>_form" addinfo="PLAN" mask="I"  sc="<?php echo($sc) ?>">
                        </div>
                    </div>
                </div>

            </div>


            <div class="col-md-12 no-padding" style="padding-left: 10px;display: none"> 
                <div style="width: 100%;height: 160px; " id="gridTestToolData_<?php echo($cd_project_build_schedule_tests) ?>_div"></div>
            </div>
            <div class="hidden">
                <div class="col-md-12" style='display: flex;'>

                    <div style='width: 60px; height: 60px;margin-top: 5px;border:#ddd thin solid; display: flex; flex-direction:row' >
                        <div style="display: flex;flex-direction: column">
                            <div class = 'divDrag dragable' data-event='{"title":"my event", "id": "-1" }'>1</div>
                            <div class = 'divDrag dragable' data-event='{"title":"my event", "id": "-4" }'>4</div>
                            <div class = 'divDrag dragable' data-event='{"title":"my event", "id": "-7" }'>7</div>
                            <div class = 'divDrag dragable' data-event='{"title":"my event", "id": "-10" }'>10</div>
                        </div>
                        <div style="display: flex;flex-direction: column">
                            <div class = 'divDrag dragable' data-event='{"title":"my event", "id": "-2" }' >2</div>
                            <div class = 'divDrag dragable' data-event='{"title":"my event", "id": "-5" }'>5</div>
                            <div class = 'divDrag dragable' data-event='{"title":"my event", "id": "-8" }'>8</div>
                            <div class = 'divDrag dragable' data-event='{"title":"my event", "id": "-11" }'>11</div>
                        </div>
                        <div style="display: flex;flex-direction: column">
                            <div class = 'divDrag dragable' data-event='{"title":"my event", "id": "-3" }'>3</div>
                            <div class = 'divDrag dragable' data-event='{"title":"my event", "id": "-6" }'>6</div>
                            <div class = 'divDrag dragable' data-event='{"title":"my event", "id": "-9" }'>9</div>
                            <div class = 'divDrag dragable' data-event='{"title":"my event", "id": "-12"}'>12</div>
                        </div>

                    </div>
                    <div style='width: 20px; height: 60px;margin-top: 5px;border:#ddd thin solid; text-align: center; line-height: 60px; cursor: pointer; background-color: #ddd' onclick="dsFormPrjSheetObject.calendarMove(1, <?php echo($cd_project_build_schedule_tests) ?>);"><i class="fa fa-angle-double-left" aria-hidden="true" style='display: inline-block;vertical-align: middle;line-height: normal'></i></div>
                    <div id="xcalendar_<?php echo($cd_project_build_schedule_tests) ?>_div" sch="<?php echo($cd_project_build_schedule_tests) ?>" class='calDatax' style="height: 60px;width:calc(100% - 100px);"></div>
                    <div style='width: 20px; height: 60px;margin-top: 5px;border:#ddd thin solid; text-align: center; line-height: 60px; cursor: pointer;background-color: #ddd' onclick="dsFormPrjSheetObject.calendarMove(2, <?php echo($cd_project_build_schedule_tests) ?>);"><i class="fa fa-angle-double-right" aria-hidden="true" style='display: inline-block;vertical-align: middle;line-height: normal'></i></div>
                </div>



            </div>
        </div>

    </div>
    <div class="col-lg-2 col-md-3">
        <div class="col-md-12 no-padding">
            <div id="woarea_<?php echo($cd_project_build_schedule_tests) ?>_div" style="width: 100%;height: 170px;"></div>
        </div>
        <div class="col-md-12 no-padding">

        </div>


    </div>

    <div class="row no-padding" style='padding-top: 10px'><hr style="width: 100%" class='scttst' ></div>
</div>


<script>

    var fun = function () {
        dsFormPrjSheetObject.setAreaTest(<?php echo ($cd_project_build_schedule_tests) ?>, <?php echo ($cd_project_build_schedule) ?>, [], <?php echo($wodata); ?>);
    };

    dsFormPrjSheetObject.funcQueue.push(fun);


</script>
