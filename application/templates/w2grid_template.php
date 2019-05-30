<?php
$grid_template = "$('#myGrid').w2grid({ 
	name: gridName,
        show : {
            header : false,
            footer : true,
            toolbar: true,
            toolbarSearch: false,
            toolbarReload  : false
        },
        <#columns#>,
        columns: [
            { field: 'recid', caption: 'Code', size: '50px', sortable: true },
            { field: 'ds_hr_type', caption: 'Description', size: '100%', editable: { type: 'text', style: 'text-align: left; text-transform: uppercase;' }, sortable: true },
            { field: 'dt_deactivated', caption: 'Deactivated', size: '100px', style: 'text-align: center;', editable: { type: 'date', style: 'text-align: center;' } },
        ]
        ,
        
	toolbar: {
           items: [
        <#toolbar#>
            ],
            onClick: function (target, data) {
                onGridToolbarPressed(target, data);
            },
	},
	onChange: function(event) {
                // condicoes genericas do sistema
                var col = event['column'];
                var colname = this.columns[col].field;
                var colType = this.columns[col].editable.type;
        
                // se for tipo data:
                if (colType == 'date') {
                    // primeiro teste. SE a data for invalida, poe de volta o valor anterior
                    if (event['value_new'] != '' && !w2utils.isDate(event['value_new'])) {
                        event['value_new'] = event['value_old'];
                        return;
                    }
                    
                    // segundo teste: se for o dt_deactivate muda o style do backgroud
                    if (colname == 'dt_deactivated') {
                        if (event['value_new'] != '') {
                            this.set(event['recid'], {style:'color:rgb(255,0,0);'}, true);
                        } else {
                            this.set(event['recid'], {style:''} , true);
                        }
                    }
                }
                
                // se for tipo texto
                if (colType == 'text') {
                    if (event['value_new'] != event['value_old']) {
                        event['value_new'] = event['value_new'].toUpperCase();
                    }
                }
                
                console.log(event);
            },
	onDelete: function(event) {
            	event.preventDefault();
	}	

        
});"


/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>