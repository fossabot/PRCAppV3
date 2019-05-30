<?php
if ($nr_test_count > 0 && $dt_est_start != '') {
    $rodates = 'Y';
} else {
    $rodates = 'N';
}
$badgeAgreeClass = 'label-primary';

if ($dt_est_start != '') {
    $showOnHeaderDate1 = $dt_est_start . ' ~ ' . $dt_est_finish;
} else {
    $showOnHeaderDate1 = $missingEstDates;
}

if ($dt_start != '') {
    $showOnHeaderDate2 = $dt_start . ' ~ ' . $dt_finish;
} else {
    if ($fl_has_tests == 'Y') {
        $showOnHeaderDate2 = $missingAgreedDates;
    } else {
        $showOnHeaderDate2 = '&nbsp';
        $badgeAgreeClass = 'label-primary label-opacity';
    }
}


if ($dt_deactivated_schedule == '') {
    $bkcolor = 'lightblue';
    $addClassHeader = '';
    $titleStyleAddon = 'display: none';
} else {
    $bkcolor = 'lightcoral';
    $addClassHeader = 'isDeactivated';
    $titleStyleAddon = 'display: inline-block; font-size: 14px; font-weight: bold';
}
?>

<div class="col-md-12 prjBuildClass" style="margin-bottom: 5px;" id="prjbuild<?php echo ($cd_project_build_schedule) ?>div" buildorder="<?php echo ($nr_order) ?>" schbuild="<?php echo ($cd_project_build_schedule) ?>" >
    <div class="box <?php echo($headerclass) ?> collapsed-box" id="buildScheduleArea<?php echo ($cd_project_build_schedule) ?>" schCode ="<?php echo ($cd_project_build_schedule) ?>" style="padding-left: 30px;border-color: black">
        <div style='position: absolute; left: 0px; top: 0px;width: 30px;height: 100%;background-color: <?php echo($bkcolor) ?>'>
            <div class='VerticalText <?php echo($addClassHeader) ?>Vert'> <?php echo($ds_project_build_title) ?> </div>
        </div>

        <div class="box-header with-border <?php echo($addClassHeader) ?>" style='backgaround-color: #dd4b39; color: black'>
            <h3 class="box-title"><span style='<?php echo($titleStyleAddon) ?>;min-width: 40px;'> <?php echo($ds_project_build_title) ?>  </span> </h3> <span data-toggle='tooltip'title="<?php echo($formTrans_dt_est_start) ?>" style='margin-left: 10px;width: 180px;<?php echo($titleStyleAddon) ?>' class='label label-primary'> <?php echo($showOnHeaderDate1) ?></span><span data-toggle='tooltip'title="<?php echo($formTrans_dt_start) ?>" style='margin-left: 10px;width: 180px;<?php echo($titleStyleAddon) ?>' class='label <?php echo($badgeAgreeClass ) ?>'> <?php echo($showOnHeaderDate2) ?></span> <span data-toggle='tooltip'title="<?php echo($Planning) ?>" style='margin-left: 10px;width: 50px;<?php echo($titleStyleAddon) ?>' class='badge bg-blue'><?php echo($nr_test_count) ?></span><span data-toggle='tooltip' title="<?php echo($TRs) ?>" style='margin-left: 10px;width: 50px;<?php echo($titleStyleAddon) ?>' class='badge bg-blue'><?php echo($nr_tr_count) ?></span>
            <div class="box-tools pull-right">
                <button type='button' class='btn btn-box-tool' data-widget="collapse"> <i class='fa fa-compress'></i> </button>
            </div>
        </div>

        <div class="box-body">
            <div style="" id="tabBuildSchedule_<?php echo ($cd_project_build_schedule) ?>_div">
                <?php echo ($tab) ?>
            </div>

            <div id="divSchDetails_<?php echo ($cd_project_build_schedule) ?>" style="" class="" > 

                <?php if ($dt_deactivated_schedule != '' && false) { ?>

                    <div style="position: absolute; left: 50%;top:100px;z-index: 100">
                        <div style="position: relative; left: -50%;transform:rotate(330deg);-webkit-transform:rotate(330deg)">
                            <span style="color:grey;font-size:80px;opacity: 0.3; transform:rotate(340deg);-webkit-transform:rotate(340deg);">REMOVED</span>
                        </div>
                    </div>

                <?php } ?>



                <div class='col-md-12' id='schDataArea_<?php echo ($cd_project_build_schedule) ?>' style='padding-bottom: 10px;'>



                    <div class="row no-padding">
                        <div class="col-sm-2" style='display: none'>
                            <input type="text" class="form-control input-sm"   value="<?php echo($cd_project_build_schedule) ?>" fieldname="cd_project_build_schedule" id="cd_project_build_schedule_<?php echo ($cd_project_build_schedule) ?>_form"  order="<?php echo($cd_project_build_schedule) ?>" indexrs = "<?php echo($cd_project_build_schedule) ?>" mask="I" sc='<?php echo($sc); ?>'>
                            <input type="text" class="form-control input-sm"   value="<?php echo($cd_project_build) ?>" fieldname="cd_project_build" id="cd_project_build_<?php echo ($cd_project_build_schedule) ?>_form"  order="<?php echo($cd_project_build_schedule) ?>" indexrs = "<?php echo($cd_project_build_schedule) ?>" mask="I" sc='<?php echo($sc); ?>' >

                            <input type="text" class="form-control input-sm"  order="<?php echo($cd_project_build_schedule) ?>" indexrs = "<?php echo($cd_project_build_schedule) ?>" value="<?php echo($dt_deactivated_schedule) ?>" fieldname="dt_deactivated_schedule" id="dt_deactivated_schedule_<?php echo ($cd_project_build_schedule) ?>_form" >                            



                            <label for="nr_version_<?php echo ($cd_project_build_schedule) ?>_form" class="col-md-1 control-label "><?php echo($formTrans_nr_version) ?>:</label>
                            <div class="col-md-1">
                                <input type="text" class="form-control input-sm"  value="<?php echo($nr_version) ?>" fieldname="nr_version" id="nr_version_<?php echo ($cd_project_build_schedule) ?>_form" order="<?php echo($cd_project_build_schedule) ?>" indexrs = "<?php echo($cd_project_build_schedule) ?>" mask="I" sc='<?php echo($sc); ?>' ro='Y' ru="cd_project_model_form" >
                            </div>
                        </div>
                        <label for="dt_est_start_<?php echo ($cd_project_build_schedule) ?>_form" class="col-md-1  control-label "><?php echo($formTrans_dt_est_start) ?>:</label>
                        <div class='col-md-3'>
                            <div class="input-group ">
                                <input type="text" class="form-control input-sm"  order="<?php echo($cd_project_build_schedule) ?>" indexrs = "<?php echo($cd_project_build_schedule) ?>" value="<?php echo($dt_est_start) ?>" fieldname="dt_est_start_build" id="dt_est_start_build_<?php echo ($cd_project_build_schedule) ?>_form" ru="cd_project_model_form" ro="<?php echo($rodates) ?>">
                                <div class="input-group-addon" style='padding: 5px;'> - </div>
                                <input type="text" class="form-control input-sm"  order="<?php echo($cd_project_build_schedule) ?>" indexrs = "<?php echo($cd_project_build_schedule) ?>" value="<?php echo($dt_est_finish) ?>" fieldname="dt_est_finish_build" id="dt_est_finish_build_<?php echo ($cd_project_build_schedule) ?>_form" ru="cd_project_model_form" ro="<?php echo($rodates) ?>">
                            </div>
                        </div>

                        <label for="dt_start_<?php echo ($cd_project_build_schedule) ?>_form" class="col-md-1  control-label "><?php echo($formTrans_dt_start) ?>:</label>
                        <div class='col-md-3'>
                            <div class="input-group ">
                                <input type="text" class="form-control input-sm"  order="<?php echo($cd_project_build_schedule) ?>" indexrs = "<?php echo($cd_project_build_schedule) ?>" value="<?php echo($dt_start) ?>" fieldname="dt_start" id="dt_start_<?php echo ($cd_project_build_schedule) ?>_form" ru="cd_project_model_form" ro="Y">
                                <div class="input-group-addon" style='padding: 5px;'> - </div>
                                <input type="text" class="form-control input-sm"  order="<?php echo($cd_project_build_schedule) ?>" indexrs = "<?php echo($cd_project_build_schedule) ?>" value="<?php echo($dt_finish) ?>" fieldname="dt_finish" id="dt_finish_<?php echo ($cd_project_build_schedule) ?>_form" ru="cd_project_model_form" ro="Y">
                            </div>
                        </div>



                        <label for="ds_human_resource_te_form" class="col-sm-1 control-label "><?php echo($formTrans_cd_human_resource_te) ?>:</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control input-sm"   plcode="<?php echo($cd_human_resource_te) ?>"  value="<?php hecho($ds_human_resource_te) ?>" order="<?php echo($cd_project_build_schedule) ?>" indexrs = "<?php echo($cd_project_build_schedule) ?>" fieldname="ds_human_resource_te" id="ds_human_resource_te_<?php echo ($cd_project_build_schedule) ?>_form" mask="PLD" model = "<?php echo ($this->encodeModel('human_resource_model')); ?>" fieldname="ds_human_resource_te" code_field="cd_human_resource_te"  relid="-1" relCode ="-1" type="text" ru="cd_project_model_form">
                        </div>

                    </div>

                    <?php if ($hasCHK == 'Y' && false) { ?>

                        <div class="col-lg-4 col-md-12">
                            <div class="">    
                                <div id="gridCheckPoint_<?php echo ($cd_project_build_schedule) ?>_div" style="width: 100%; height: 200px"></div>
                            </div>
                        </div>
                    <?php } ?>

                    <div class="col-lg-6 col-md-12">
                        <div class="">    
                            <div id="gridAttachment_<?php echo ($cd_project_build_schedule) ?>_div" style="width: 100%; height: 200px"></div>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-12" style="padding-right: 0px">
                        <div class="">    
                            <div id="gridCommentsBuild_<?php echo ($cd_project_build_schedule) ?>_div" style="width: 100%; height: 200px"></div>
                        </div>  

                    </div>

                </div>


                <div class='col-md-12'id="divSchTst_<?php echo ($cd_project_build_schedule) ?>"> 
                    <div class="row">
                        <div id="schTstToolbar_<?php echo ($cd_project_build_schedule) ?>" style="width: 100%;" class="toolbarStyle" ></div>
                    </div>

                    <div class="row">
                        <div id="gridPlanningData_<?php echo ($cd_project_build_schedule) ?>_div" style="width: 100%;height: 100px; display: none"></div>
                    </div>
                    <?php echo($tst) ?>

                </div>

                

            </div>
        </div>
    </div>
    <div class="row" id="buildScheduleAreaBottom<?php echo ($cd_project_build_schedule) ?>"> </div>

</div>
<script>

    if (dsMainObject.firstTimeLoad) {
        //$('#buildScheduleArea<?php echo ($cd_project_build_schedule) ?>').addClass('');
    } else {
        
    }

    dsFormPrjSheetObject.setAreaScheduleNow(<?php echo ($cd_project_build_schedule) ?>, '<?php echo($hasCHK) ?>', <?php echo($chklist) ?>, <?php echo($comments) ?>, <?php echo($cd_project_build) ?>);            
    var fun = function () {
        dsFormPrjSheetObject.setAreaScheduleQueue(<?php echo ($cd_project_build_schedule) ?>, '<?php echo($hasCHK) ?>', <?php echo($chklist) ?>, <?php echo($comments) ?>, <?php echo($cd_project_build) ?>, <?php echo($attach) ?>);
<?php if ($dt_deactivated_schedule != '') { ?>
            dsFormPrjSheetObject.deactivateScheduleArea(<?php echo ($cd_project_build_schedule) ?>);
<?php } ?>
    };

    dsFormPrjSheetObject.funcQueue.push(fun);


</script>

