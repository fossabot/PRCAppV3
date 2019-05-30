
<div class="row">
    <div class="col-md-12 small-padding">
        <div class="modal-header-picklist_cgb"> <?php echo ($title) ?> <i class='modal-header-picklist-close_cgb fa fa-close' onclick='SBSModalVar.close();'> </i></div>
    </div>
</div>
<div id='textPLdiv' class="row">
    <div class="col-md-12 small-padding">
        <div id='textPlToolbarDiv' name = 'textPlToolbarDiv' style="width:100%; height:30px; background-color: white; border-bottom: gray dashed thin"> </div>
    </div>
</div>

<div id='textPLdiv' class="row">
    <div class="col-md-12 small-padding">
        <textarea name="textPL" id="textPL" style='width:100%; height:270px;' ><?php echo ($text); ?></textarea>
    </div>
</div>

<script>

    $('#textPL').focus();
    var upperCase = <?php echo($uppercase) ?>;
    var vreadonly = <?php echo($readonly) ?>;


    if (w2ui["textPlToolbar"] != undefined) {
        w2ui["textPlToolbar"].destroy();
    }

// aqui tem os scripts basicos. 
    $('#textPlToolbarDiv').w2toolbar({
        name: 'textPlToolbar',
        onClick: function (event) {
            if (event.target == "update") {

                var val = $('#textPL').val();

                if (upperCase) {
                    val = val.toUpperCase();
                }

                picklistCallBack(true, val);
                SBSModalVar.close();
            }
            if (event.target == "close") {
                picklistCallBack(false, '');
                SBSModalVar.close();
            }
        }
    });


if (!vreadonly) {
    toolbarAddUpd(w2ui['textPlToolbar']);
} else {
    $('#textPL').prop('readonly', true)
}
    
toolbarAddClose(w2ui['textPlToolbar']);

if (upperCase) {
    $('#textPL').css('text-transform', 'uppercase')
}

setTimeout(function () {
    $('#textPL') . focus();
}, 100);
//text-transform: uppercase;

</script>
