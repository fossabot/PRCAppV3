<style>
    .datagrid table { border-collapse: collapse;
                      text-align: left;
    } 
    .datagrid {font: normal 12px/150% Arial, Helvetica, sans-serif;
               background: #fff;
               overflow: hidden;
    }
    .datagrid table td, 
    .datagrid table th { padding: 0px 10px;
    }
    .datagrid table thead th {background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #991821), color-stop(1, #80141C) );
                              background:-moz-linear-gradient( center top, #991821 5%, #80141C 100% );
                              filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#991821', endColorstr='#80141C');
                              background-color:#991821;
                              color:#FFFFFF;
                              font-size: 15px;
                              font-weight: bold;
                              border-left: 1px solid #B01C26;
    } 
    .datagrid table thead th:first-child { border: none;
    }
    .datagrid table tbody td { color: #80141C;
                               border-left: 1px solid #F7CDCD;
                               font-size: 12px;
                               border-bottom: 1px solid #E1EEF4;
                               font-weight: normal;
    }
    .datagrid table tbody td:first-child { border-left: none;
    }
    .datagrid table tbody tr:last-child td { border-bottom: none;
    }
</style>

<!–– http://tablestyler.com/# ––>
<div>
    <div>Please check Comment(s) below</div>
    <div class ="datagrid">
         <table style="width: 700px !important;">
            <thead><tr>
                    <th style="width: 80px">Section</th>
                    <th style="width: 350px">Comment</th>
                    <th style="width: 130px">Type</th>
                    <th style="width: 130px">By</th>
                    <th style="width: 130px">Time (PRC)</th>
                    <th style="width: 130px">Attachments</th>
                </tr>
            </thead>

            <tbody>
                <?php echo ($comment) ?>
            </tbody>
        </table>
    </div>
</div>   