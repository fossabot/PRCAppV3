<div class="row schtstlistdiv" id="testlistArea_<?php echo($cd_project_build_schedule) ?>">

    <div class="row no-padding" style='padding-top: 10px' id="testlistview_<?php echo($cd_project_build_schedule) ?>" ><hr style="width: 100%" class='scttst'><?php echo($html);?></div>
</div>

<script>

    var fun = function () {
        dsFormPrjSheetObject.setAreaTest(<?php echo ($cd_project_build_schedule) ?>, <?php echo ($cd_project_build_schedule) ?>, []);
    };

    dsFormPrjSheetObject.funcQueue.push(fun);


</script>
