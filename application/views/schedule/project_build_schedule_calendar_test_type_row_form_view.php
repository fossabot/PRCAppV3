<div class="row schtstdiv" id="testArea_<?php echo($cd_project_build_schedule_tests) ?>">

    <div class="hidden">
        <label for="cd_project_build_schedule_tests_<?php echo($cd_project_build_schedule_tests) ?>_form" class="col-sm-1 control-label "><?php echo($formTrans_cd_project_build_schedule_tests) ?>:</label>
        <div class="col-sm-2" style='padding-right: 5px'>
            <input type="text" class="form-control input-sm"   value="<?php echo($cd_project_build_schedule_tests) ?>" order="<?php echo($cd_project_build_schedule_tests) ?>" indexrs = "<?php echo($cd_project_build_schedule) ?>" fieldname="cd_project_build_schedule_tests" id="cd_project_build_schedule_tests_<?php echo($cd_project_build_schedule_tests) ?>_form"  mask="I" >
        </div>
    </div>


    <div class="col-sm-2" style='padding-right: 5px'>
        <span class="form-control input-sm" data-toggle="tooltip" title="<?php echo($formTrans_cd_test_type) ?>" plcode="<?php echo($cd_test_type) ?>"  value="<?php hecho($ds_test_type) ?>" order="<?php echo($cd_project_build_schedule_tests) ?>" indexrs = "<?php echo($cd_project_build_schedule) ?>" fieldname="ds_test_type" id="ds_test_type_<?php echo($cd_project_build_schedule_tests) ?>_form" mask="G" ><?php hecho($ds_test_type) ?></span>
    </div>

    <div class="col-sm-1" style='padding-right: 5px'>
        <input type="text" class="form-control input-sm" data-toggle="tooltip" title="<?php echo($formTrans_nr_sample_quantity) ?>"  value="<?php echo($nr_sample_quantity) ?>" order="<?php echo($formTrans_nr_sample_quantity) ?>" indexrs = "<?php echo($cd_project_build_schedule) ?>" fieldname="nr_sample_quantity" id="nr_sample_quantity_<?php echo($cd_project_build_schedule_tests) ?>_form" mask="I" >
    </div>

    <div class="col-sm-2" style='padding-right: 5px'>
        <span type="text" class="form-control input-sm" data-toggle="tooltip" title="<?php echo($formTrans_ds_test_unit) ?>" order="<?php echo($cd_project_build_schedule_tests) ?>" indexrs = "<?php echo($cd_project_build_schedule) ?>" id="ds_test_unit_<?php echo($cd_project_build_schedule_tests) ?>_form" mask="G" > <?php hecho($ds_test_unit) ?> </span>
    </div>

    <div class="col-sm-1" style='padding-right: 5px'>
        <input type="text" class="form-control input-sm" data-toggle="tooltip" title="<?php echo($formTrans_nr_goal) ?>"  value="<?php echo($nr_goal) ?>" order="<?php echo($cd_project_build_schedule_tests) ?>" indexrs = "<?php echo($cd_project_build_schedule) ?>" fieldname="nr_goal" id="nr_goal_<?php echo($cd_project_build_schedule_tests) ?>_form" mask="I" >
    </div>

    <div class="col-sm-1" style='padding-right: 5px'>
        <input type="text" class="form-control input-sm" data-toggle="tooltip" title="<?php echo($formTrans_nr_output) ?>"  value="<?php echo($nr_output) ?>" order="<?php echo($cd_project_build_schedule_tests) ?>" indexrs = "<?php echo($cd_project_build_schedule) ?>" fieldname="nr_output" id="nr_output_<?php echo($cd_project_build_schedule_tests) ?>_form" mask="I" >
    </div>

    <div class="col-sm-2" style='padding-right: 5px'>
        <input type="text" class="form-control input-sm" data-toggle="tooltip" title="<?php echo($formTrans_dt_est_start) ?>"  value="<?php echo($dt_est_start) ?>" order="<?php echo($cd_project_build_schedule_tests) ?>" indexrs = "<?php echo($cd_project_build_schedule) ?>" fieldname="dt_est_start_tst" id="dt_est_start_<?php echo($cd_project_build_schedule_tests) ?>_form">
    </div>

    <div class="col-sm-2" style='padding-right: 5px'>
        <input type="text" class="form-control input-sm" data-toggle="tooltip" title="<?php echo($formTrans_dt_est_finish) ?>"  value="<?php echo($dt_est_finish) ?>" order="<?php echo($cd_project_build_schedule_tests) ?>" indexrs = "<?php echo($cd_project_build_schedule) ?>" fieldname="dt_est_finish_tst" id="dt_est_finish_<?php echo($cd_project_build_schedule_tests) ?>_form">
    </div>    

<div class="col-sm-12" style='display: flex'>

        <div style='width: 60px; height: 60px;margin-top: 5px;border:#ddd thin solid; display: flex; flex-direction:row' >
            <div style="display: flex;flex-direction: column">
                <div class = 'divDrag dragable' data-event='{"title":"my event", "id": "-1"}'>1</div>
                <div class = 'divDrag dragable' data-event='{"title":"my event", "id": "-4"}'>4</div>
                <div class = 'divDrag dragable' data-event='{"title":"my event", "id": "-7"}'>7</div>
                <div class = 'divDrag dragable' data-event='{"title":"my event", "id": "-10"}'>10</div>
            </div>
            <div style="display: flex;flex-direction: column">
                <div class = 'divDrag dragable' data-event='{"title":"my event", "id": "-2"}' >2</div>
                <div class = 'divDrag dragable' data-event='{"title":"my event", "id": "-5"}'>5</div>
                <div class = 'divDrag dragable' data-event='{"title":"my event", "id": "-8"}'>8</div>
                <div class = 'divDrag dragable' data-event='{"title":"my event", "id": "-11"}'>11</div>                
            </div>
            <div style="display: flex;flex-direction: column">
                <div class = 'divDrag dragable' data-event='{"title":"my event", "id": "-3"}'>3</div>                
                <div class = 'divDrag dragable' data-event='{"title":"my event", "id": "-6"}'>6</div>
                <div class = 'divDrag dragable' data-event='{"title":"my event", "id": "-9"}'>9</div>
                <div class = 'divDrag dragable' data-event='{"title":"my event", "id": "-12"}'>12</div>
            </div>



        </div>
        <div style='width: 20px; height: 60px;margin-top: 5px;border:#ddd thin solid; text-align: center; line-height: 60px; cursor: pointer; background-color: #ddd' onclick="dsFormSchObject.calendarMove(1, <?php echo($cd_project_build_schedule_tests) ?>);"><i class="fa fa-angle-double-left" aria-hidden="true" style='display: inline-block;vertical-align: middle;line-height: normal'></i></div>
        <div id="calendar_<?php echo($cd_project_build_schedule_tests) ?>_div" class='calData' style="height: 60px;width:calc(100% - 100px);"></div>
        <div style='width: 20px; height: 60px;margin-top: 5px;border:#ddd thin solid; text-align: center; line-height: 60px; cursor: pointer;background-color: #ddd' onclick="dsFormSchObject.calendarMove(2, <?php echo($cd_project_build_schedule_tests) ?>);"><i class="fa fa-angle-double-right" aria-hidden="true" style='display: inline-block;vertical-align: middle;line-height: normal'></i></div>
    </div>  
    
    
</div>

<div class="col-md-12 no-padding"><hr style="width: 100%" class='scttst'></div>