
<script>
// aqui tem os scripts basicos. 
    var selSettingsRecord;
    if (w2ui['settingsGrid'] != undefined) {
        w2ui['settingsGrid'].destroy();
    }
//$(".ds_hr_type").on( "change",  function() {
<?php echo ($javascript); ?>

// funcao da toolbar
    function onGridToolbarPressedSettings(bPressed, dData) {
        if (bPressed == 'update') {
            updateSettings();
        }
    }

    w2ui['settingsGrid'].on('dblClick', function (event) {
        var col = event.column;
        var colname = w2ui['settingsGrid'].columns[col].field;
        selSettingsRecord = w2ui['settingsGrid'].get(event.recid);
        id = selSettingsRecord.recid;


        basicPickListOpen({controller: 'settings/getOptionsPL/' + id,
            title: 'Select Option',
            sel_id: id,
            plCallBack: onPLSettingsoptionSelected
        }
        );

        //basicPickListOpen (, 'getOptionsPL', id, 400, 400, 'settings');

    });

    function onPopupCreated() {

    }


    function updateSettings() {
        w2ui['settingsGrid'].lock(javaMessages.updating, true);

        $.post(
                "settings/updateDataJson",
                {"upd": JSON.stringify(w2ui['settingsGrid'].records)},
                function (data) {
                    w2ui['settingsGrid'].unlock();
                    if (data == "OK") {
                        toastSuccess(javaMessages.update_done);
                        closePopup();
                    } else {
                        toastErrorBig(javaMessages.error_upd + data);
                    }
                },
                "text"
                );

    }

// opcoes!
    function onPLSettingsoptionSelected(id, desc) {
        w2ui['settingsGrid'].setItem(selSettingsRecord.recid, 'cd_system_settings_options', id);
        w2ui['settingsGrid'].setItem(selSettingsRecord.recid, 'ds_system_settings_options', desc);

    }
// insiro colunas;

    function closePopup() {
        formChanged = false;
        $("#main_form_div").dialog('close');
        unbindHandlersPL();
        unbindHandlerForm();
    }

</script>
<div id="varGridSettingsDiv" style="height: 350px"> </div>

