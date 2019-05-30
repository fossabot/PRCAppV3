<?php include_once APPPATH . 'views/viewIncludeFilter.php'; ?>

<script>

// aqui tem os scripts basicos. 
    var gridName = "gridDefault";

    if (w2ui[gridName] != undefined) {
        w2ui[gridName].destroy();
    }


//$(".ds_hr_type").on( "change",  function() {
<?php echo ($javascript); ?>

    function onGridToolbarPressed(bPressed, dData) {
        if (bPressed == 'insert') {
            w2ui[gridName].insertRow();
        }
        if (bPressed == 'retrieve') {

            var data = select2GetData('cd_system_languages');

            if (data === null) {
                messageBoxAlert('Please select the Language first');
                return;
            }

            w2ui[gridName].retrieve();
        }
        if (bPressed == "update") {
            w2ui[gridName].update({retrieveAfter: false, coldRetrieve: false});
        }

        if (bPressed == "delete") {
            w2ui[gridName].deleteRow();
        }
        if (bPressed == 'filter') {
            hideFilter();
        }
        if (bPressed == 'apply') {
            var data = select2GetData('cd_system_languages');
            if (data == null) {
                messageBoxAlert('Please select the Language first');
                return;
            }

            messageBoxYesNo('Confirm Apply changes on language ' + data.text + ' to all users ?',
                    function () {
                        $.post('dictionary/applyLanguage/' + data.id,
                                {},
                                function (data) {
                                    messageBoxAlert('Done');
                                },
                                'text'
                                );
                    });
        }

    }

    w2ui[gridName].onItemChanged(function (data) {
        rset = this.get(data.recid);

        this.setItem(data.recid, 'cd_system_languages', rset.cd_system_languages);
        //console.log(this.getChanges());

    });


// insiro colunas;

</script>

<?php include_once APPPATH . 'views/includeViewResizeDiv.php'; ?>
