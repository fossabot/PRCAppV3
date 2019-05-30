<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

   


<script type="text/javascript" src="<?php echo base_url();?>third_party/jquery/jquery-1.9.1.min.js" > </script>




<!-- SmartMenus core CSS (required) -->
<link href="<?php echo base_url();?>third_party/smartmenus-0.9.6/css/sm-core-css.css" rel="stylesheet" type="text/css" />

<!-- "sm-mint" menu theme (optional, you can use your own CSS, too) -->
<link href="<?php echo base_url();?>third_party/smartmenus-0.9.6/css/sm-cgb/sm-cgb.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url();?>third_party/smartmenus-0.9.6/jquery.smartmenus.js"/> 

<!-- jToolbar -->
<script type="text/javascript" src="<?php echo base_url();?>third_party/jtoolbar/jquery.toolbar.min.js"> </script>
<link rel="stylesheet" href="<?php echo base_url();?>third_party/jtoolbar/jquery.toolbars.css"/>
<link rel="stylesheet" href="<?php echo base_url();?>third_party/jtoolbar/bootstrap.icons.css"/>

<!-- Context MEnu -->

<!-- Block UI -->
<script type="text/javascript" src="<?php echo base_url();?>third_party/blockui/jquery.blockUI.js"> </script>

<!-- W2UI -->
<link rel="stylesheet" href="<?php echo base_url();?>third_party/w2ui-1.4.3/w2ui-1.4.3.css"/>
<script type="text/javascript" src="<?php echo base_url();?>third_party/w2ui-1.4.3/w2ui-1.4.3.js"> </script>

<script type="text/javascript" src="<?php echo base_url();?>application/javascripts/library.js"> </script>
<script type="text/javascript" src="<?php echo base_url();?>application/javascripts/select2utils.js"> </script>
<script type="text/javascript" src="<?php echo base_url();?>application/javascripts/libraryGrid.js"> </script>
<script type="text/javascript" src="<?php echo base_url();?>application/javascripts/libraryFormCtrl.js"> </script>


<!-- checkbox-->
<link rel="stylesheet" href="<?php echo base_url();?>third_party/iCheck-1.x/skins/all.css"/>
<script type="text/javascript" src="<?php echo base_url();?>third_party/iCheck-1.x/icheck.min.js"> </script>


<!-- select -->
<link rel="stylesheet" href="<?php echo base_url();?>third_party/select2-3.5.1/select2.css"/>
<script type="text/javascript" src="<?php echo base_url();?>third_party/select2-3.5.1/select2.min.js"> </script>

<!-- Toast -->
<link href="<?php echo base_url();?>third_party/toastr/toastr.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url();?>third_party/toastr/toastr.min.js"> </script>

<script type="text/javascript" src="<?php echo base_url();?>third_party/sticky/jquery.sticky.js"> </script>


<!-- magic move-->
<script type="text/javascript" src="<?php echo base_url();?>third_party/magicmove/jquery.magicmove.js"> </script>



<!-- Font-Awesome -->
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url();?>third_party/font-awesome/css/font-awesome.css" />


<!-- CSS do MAIN -->
<link rel="stylesheet" href="<?php echo base_url();?>application/css/main.css">
<link rel="stylesheet" href="<?php echo base_url();?>application/css/filter_styles.css">

<!-- JQuery-Ui -->
<link rel="stylesheet" href="<?php echo base_url();?>third_party/jquery-ui/jquery-ui.css"/>
<script type="text/javascript" src="<?php echo base_url();?>third_party/jquery-ui/jquery-ui.min.js"> </script>
   

<!-- contextMenu -->
<link rel="stylesheet" href="<?php echo base_url();?>third_party/contextMenu/jquery.contextmenu.css"/>
<script type="text/javascript" src="<?php echo base_url();?>third_party/contextMenu/jquery.contextmenu.js"> </script>


<style type="text/css">
.ui-widget{font-size:12px;  }

.ui-dialog .ui-dialog-content {
	padding: .1em .1em;
   background-color: #f0f0f0;

}

.fake-link {
    color: blue;
    text-decoration: underline;
    cursor: pointer;
}

.fake-link_button {
    cursor: pointer;
}


#main-menu {
		position:relative;
		z-index:100;
		width:auto;
	}

.w2ui-layout > div .w2ui-panel {
  z-index: 12;
}

.select2-container .select2-choice {
    height: 24px;
}
.select2-container .select2-choice abbr {
    top: 6px;
}
   
</style>    

<?php
// adiciono php com dialogos padroes.
//require_once "application/includes/dialogs.php";
?>

<!-- SmartMenus jQuery init -->
<script type="text/javascript">
	$(function() {
		$('#main-menu').smartmenus({
			mainMenuSubOffsetX: 6,
			mainMenuSubOffsetY: -7,
			subMenusSubOffsetX: 6,
			subMenusSubOffsetY: -8
		});
	});


</script>

<!-- variaveis globais-->
<script type="text/javascript">
var codePK = -1;
var javaMessages = <?php echo ($jmessages); ?>;
var defaultDateFormat = '<?php echo ($dateFormat); ?>';
var controllerToOpen = '<?php echo ($controllerToOpen); ?>';
// configuracao de datas no w2ui !  
//w2ui.settings = {date_format: defaultDateFormat, date_display:defaultDateFormat};
w2utils.settings.date_format = defaultDateFormat;
//w2utils.settings.phrases = javaMessages['w2uilang'];
//w2ui.settings.date_display = defaultDateFormat;

var fStringUpper = <?php echo ($fstringUpper); ?>;
var fStringLower = <?php echo ($fstringLower); ?>;
var fPickList    = <?php echo ($fPicklist); ?>;


// defailt do jspanel
function goLogout () {
   location.href = "<?php echo base_url();?>index.php/main/logout"
}

// abertura do item padrao!
$ (document).ready (function() {
   if (controllerToOpen != '') {
      toOpen = controllerToOpen;
      controllerToOpen = '';      
      $.post ("<?php echo base_url();?>index.php/" +toOpen,
      {},
      function(data) {
        $('#content-body').html(data);
        $('.choption').html('<?PHP echo($controllerTitle) ?>');
        $('#main-menu').remove(); 
        $('#menu-table').remove(); 
      });

   }   
});




</script>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Management Board</title>
</head>

<body>

<table width="95%" height="70px" border="0" id="maintable">
  <tr>
      <td height="24px" colspan="2"> <div class ="choption" id="chopt"><?php echo ($main);?></div> <div class = 'left_header'>
       <?php echo ($welcome);?> <?php echo ($ds_human_resource_full) ?> <spam id='testeip' onclick="openFormUi ('Settings', 'settings', 600, 420);" class="fake-link_button" > <i class="fa fa-cogs" style="padding-left: 10px;"></i>   </spam>  <i id='testeip2' onclick="goLogout();" class="fa fa-unlock fake-link_button" style="padding-left: 10px;"></i>  <br> 
      <?php  //echo anchor('main/logout', 'Logout', 'title="Logout"') ?>
    </div></td>
  </tr>
  <tr>
      <td height="40" align="center" valign="top" id='menu-table'>
      <ul class="sm sm-cgb" id="main-menu" name="main-menu">
        <?php echo $menu; ?>
      </ul></td>
  </tr>
</table>
<div id="content-body"> </div>
</body>
</html>

<div id="main_form_div" style='display: none; z-index:9999;'>  </div>
<div id="main_form_div_picklist" class = "main_form_div_picklist" style='display: none;z-index:9999'> teste222  </div>
