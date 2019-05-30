<script>
// aqui tem os scripts basicos. 


// retorna os dadosselecionados.!
    function plselectData() {
        //var record = w2ui["modalPickList"].get(recid);

        // com o custom callback dah pra chamar qq funcao de selecao. Isso para evitar problemas
        // em telas que tem mais niveis, e tem picklist em todos!! Funcina que eh um charme!!!
        //if (typeof picklistCallBack === "function") {
        //picklistCallBack(record.recid, record.description, record);
        //} else {
        //   onPLoptionSelected(record.recid, record.description, record);   
        //}


        //SBSModalVar.close()
        console.log('getvalue',  $('#date-range').val());
        var vdata = $('#date-range').val();
        var vsp = vdata.split('~');
        if (vsp.length != 2) {
            messageBoxError('Error Selecting Data');
            return;
        }
        
        picklistCallBack(vsp[0], vsp[1]);
        SBSModalVar.close();
        
    }






// funcao que reseta os dados. seta -1, que depois os controllers/models vao entender como nulo.
    function plresetData() {
        //onPLoptionSelected(-1,'', null);
        picklistCallBack('', '');
        SBSModalVar.close()
    }


    $('#date-range').dateRangePicker({
        inline: true,
        container: '#date-range-container',
        alwaysOpen: true,
        startOfWeek: 'monday',
        separator: '~',
        format: 'MM/DD/YYYY HH:mm',
        autoClose: false,
        time: {
            enabled: true
        },
        defaultTime: moment().startOf('day').toDate(),
        defaultEndTime: moment().endOf('day').toDate()
    }); 

<?php if ($startDate != '') { ?>
    $('#date-range').data('dateRangePicker').setStart('<?php echo($startDate)?>');
    $('#date-range').data('dateRangePicker').setEnd('<?php echo($endDate)?>');    
<?php }?>


// funcoes que vem do controlador.
<?php //echo ($javascript);  ?>

</script>
<div class="row">
    <div class="col-md-12 small-padding">
        <div class="modal-header-picklist_cgb"> <?php echo ($title) ?> <i class='modal-header-picklist-close_cgb fa fa-close' onclick='SBSModalVar.close();'> </i></div>
    </div>
</div>

<div class="row hidden">
    <div class="col-md-12 small-padding"><input id="date-range" style="width: 100%" ></div>
</div>

<div class="row">
    <div class="col-md-12 no-padding" id="date-range-container"></div>
</div>

<div class="row">  
    
    
    <div class="col-md-4 no-padding" style="padding-right: 5px !important"> 
        <button type="button" class="btn btn-info pull-right" aria-label="Left Align" id="daterangeOK" onclick="plselectData();" style="width: 100%"> 
            <span><?php echo($select)?></span> 
        </button> 
    </div> 
    <div class="col-md-4 no-padding" style="padding-right: 5px !important"> 
        <button type="button" class="btn btn-danger pull-left" aria-label="Left Align" id="daterangeClose" onclick="SBSModalVar.close()" style="width: 100%"> 
            <span><?php echo($cancel)?></span> 
        </button> 
    </div>             
    
    <div class="col-md-4 no-padding" style="padding-right: 5px !important"> 
        <button type="button" class="btn btn-primary pull-left" aria-label="Left Align" id="daterangeReset" onclick="plresetData()" style="width: 100%"> 
            <span><?php echo($reset)?></span> 
        </button> 
    </div>             
    
</div>  
