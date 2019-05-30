<table width="95%" height="100%" border="0" id="childtable">
  <tr>
      
    <td width="95%"><div class="showfilter"><script>  </script>
    </div>
    </td>

  </tr>
    
</table>

<div class="w2ui-field w2ui-span3">
    <div><input type="file" id='file1' name='file1[]' multiple="" style="width: 600px; height:1px"></div>
    <div><input type="file" id='file2' name='file2[]' multiple="" style="width: 600px; height:1px"></div>
</div>
<br>
<button class="btn-blue" onclick="opentoShow();"> open 1 </button>
<button class="btn-blue" onclick="opentoShow2();"> open 2 </button>

<button class="btn-blue" onclick="send();"> Send </button>

<script>

var form = $('#file1, #file2').cgbFileUpload({controller: 'admin_upload_file/getFiles'});

function send() {
   form.addInfo( {cgbName: "Carlos", cgbLast: 'Blos'} );
   form.send();
}

function opentoShow () {
   form.openDialog('file1');
}

function opentoShow2 () {
   form.openDialog('file2');
}


</script>

