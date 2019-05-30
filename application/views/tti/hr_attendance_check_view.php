
<?php
// PHP page that has the filter area.
include_once APPPATH . 'views/viewIncludeFilter.php';
?>

<script>
// aqui tem os scripts basicos. 
    var gridName = "gridGeneric";

    var dsMainObject = new function () {

        // Private Variables;
        var thisObj = this;
        thisObj.gridName = undefined;
        thisObj.showDetailed = false;

        // The starting function
        this.start = function (gridNamePar) {
            thisObj.gridName = gridNamePar;

            if (w2ui[thisObj.gridName] !== undefined) {
                w2ui[thisObj.gridName].destroy();
            }

// javascript received from controller with the grid.
<?php echo ($javascript); ?>


            this.addListeners();
            this.addHelper();

            setTimeout(function () {
                thisObj.ToolBarClick('retrieve');
            }, 0);

        }

        // add helper on the question mark button. 
        this.addHelper = function () {
            var arrayHelper = [];
            $.merge(arrayHelper, introAddFilterArea());
            $.merge(arrayHelper, w2ui[thisObj.gridName].toolbar.getIntroHelp());
            $.merge(arrayHelper, w2ui[thisObj.gridName].getIntroHelp());

            introAddNew({steps: arrayHelper});
        }

        // Toolbar functions
        this.ToolBarClick = function (bPressed, dData) {

            if (bPressed == 'retrieve') {
                var vf = select2GetData('filter_status');
                var vfd = 0;
                if (vf != null) {
                    vfd = vf.iddesc;
                } 
                w2ui[thisObj.gridName].retrieve({retrFunc: 'retrieveGridJsonAttendance/'+vfd});
            }
            if (bPressed == "update") {
                w2ui[thisObj.gridName].update();
            }

            if (bPressed == 'filter') {
                hideFilter();
            }
            
            if (bPressed == 'showDetails') {
                setTimeout(function(){
                    thisObj.showDetailed = w2ui[thisObj.gridName].toolbar.get(bPressed).checked;
                    var vsize = '40px';
                    if (thisObj.showDetailed) {
                        var vsize = '160px';
                    }
                    $.each(w2ui[thisObj.gridName].columns, function(i,v) {
                        if (v.field.substring(v.field.length-6, v.field.length) == 'status') {
                            w2ui[thisObj.gridName].columns[i].size = vsize;
                        }
                    })
                    
                    w2ui[thisObj.gridName].refresh();
                }, 0);
            }
            

        }


        // Function to add listeners (events). Here is empty but it is part of the basic object structure
        this.addListeners = function () {




        }

        // It runs before close the screen (by choosing another option on the menu, for example). If you return false the system will not leave the screen
        this.beforeClose = function () {
            return w2ui[thisObj.gridName].getChanges().length == 0;
        }


        // Event that will be triggered when the object is being closed. Location to remove listeners, destroy grids, etc....
        this.close = function () {
            w2ui[thisObj.gridName].destroy();
            introRemove();
            return true;
        }

        // Place to add general functions


        this.setColumnStatus = function (record, index, column_index) {

            //debugger;
            var vfield = this.columns[column_index].field;
            var vx = this.columns[column_index].field;
            var vdata = chkUndefined(record[vx], '&nbsp');
            var vdatefield = 'dt' + vfield.substring(2, vfield.length - 7);
            var vdetailed = '';
            var vcolor = '';
            var vshow = '';
            vshow = '';
            
            if (record['fl_id_scanned_before'] == 0 && vfield.indexOf('faceid') > -1 ) {
                return vshow;
            }
            
            if (vdata == 'MISSING') {
                if (thisObj.showDetailed) {
                    vshow = vdata;
                }
                vcolor = 'red';
            }

            if (vdata == 'OK') {
                if (thisObj.showDetailed) {
                    vshow = record[vdatefield];
                }
                vcolor = 'lightgreen';
            }

            if (vdata == 'NA') {
                vcolor = 'lightgrey';

            }
            vdata = '<div style="background-color: ' + vcolor + '; height: 20px; margin-right: 3px; margin-left: 3px;">' + vshow + '</div>';


            return vdata;
        }

    }

// funcoes iniciais;
    dsMainObject.start(gridName);

// funcao da toolbar
    function onGridToolbarPressed(bPressed, dData) {
        dsMainObject.ToolBarClick(bPressed, dData);
    }

    makeFilterWithEnter(function () {
        dsMainObject.ToolBarClick('retrieve');
    });

</script>



<?php
// PHP page that contains the Grid area, and the resize controls for grid.
include_once APPPATH . 'views/includeViewResizeDiv.php';
?>
