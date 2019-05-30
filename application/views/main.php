<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <title><?php echo ($main . ' - ' . $companyName); ?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.5 -->

        <link rel="stylesheet" href="<?php echo base_url(); ?>plugins/w2ui-1.4.3/w2ui-1.4.3.bt.css"/>

        <link href="<?php echo base_url(); ?>plugins/toastr/toastr.min.css" rel="stylesheet" type="text/css" />
        <!-- contextMenu -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>plugins/contextMenu/jquery.contextmenu.css"/>
        <link rel="stylesheet" href="<?php echo base_url(); ?>plugins/jquery-ui/1.11.4/jquery-ui.css"/>
        <link rel="stylesheet" href="<?php echo base_url(); ?>plugins/jquery-confirm-master-3.0.3/css/jquery-confirm.css"/>


        <link rel="stylesheet" href="<?php echo base_url(); ?>plugins/loaders/loaders.min.css"/>
        <link rel="stylesheet" href="<?php echo base_url(); ?>plugins/popover-x/css/bootstrap-popover-x.min.css"/>
        <link rel="stylesheet" href="<?php echo base_url(); ?>plugins/vis-4.21.0/dist/vis.min.css"/>



        <!-- CSS do MAIN -->

        <!-- select2 -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>plugins/select2-3.5.1/select2.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>plugins/select2-3.5.1/select2.bootstrap.css">

        <link rel="stylesheet" href="<?php echo base_url(); ?>plugins/webui-popover-master/dist/jquery.webui-popover.css">


        <link rel="stylesheet" href="<?php echo base_url(); ?>bootstrap/css/bootstrap.min.css">      

        <link rel="stylesheet" href="<?php echo base_url(); ?>plugins/bootstrap-tabs-x/css/bootstrap-tabs-x.min.css">

        <link rel="stylesheet" href="<?php echo base_url(); ?>plugins/toolbar.js/jquery.toolbar.css">

        <link rel="stylesheet" href="<?php echo base_url(); ?>plugins/fullcalendar/fullcalendar.css">      
<link rel="stylesheet" href="<?php echo base_url(); ?>plugins/imageViewer/viewer.css">


        <!-- Font Awesome -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>plugins/font-awesome/4.7.0/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>plugins/ionicons/2.0.1/css/ionicons.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>dist/css/AdminLTE.css">
        <!-- AdminLTE Skins. Choose a skin from the css/skins
             folder instead of downloading all of them to reduce the load. -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>dist/css/skins/_all-skins.min.css">
        <!-- iCheck -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>plugins/iCheck/all.css">

        <!-- jvectormap -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>plugins/jvectormap/jquery-jvectormap-1.2.2.css">
        <!-- Date Picker -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>plugins/datepicker/datepicker3.css">
        <!-- Daterange picker -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>plugins/daterangepicker/daterangepicker-bs3.css">

        <link rel="stylesheet" href="<?php echo base_url(); ?>plugins/datetimerangepick/dist/daterangepicker.min.css">

        <!-- bootstrap wysihtml5 - text editor -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
        <!--  <link rel="stylesheet" href="<?php echo base_url(); ?>tests/normalize/normalize.css"> -->

        <link rel="stylesheet" href="<?php echo base_url(); ?>application/css/main.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>application/css/filter_styles.css">

        <link rel="stylesheet" href="<?php echo base_url(); ?>plugins/titatoggle/dist/titatoggle-dist-min.css">

        <link rel="stylesheet" href="<?php echo base_url(); ?>plugins/pace/pace.css">

        <link rel="stylesheet" href="<?php echo base_url(); ?>plugins/bootstrap-slider/bootstrap-slider.min.css">

        <!-- datatables -->

        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>plugins/datatables3/datatables.min.css"/>


        <link rel="stylesheet" href="<?php echo base_url(); ?>plugins/malihu-custom-scrollbar/jquery.mCustomScrollbar.css" />

        <style type="text/css">

            .gridColInfoSpan {

                padding-left: 5px  !important;
                text-overflow: ellipsis;
                overflow: hidden;
                white-space: nowrap;
                display: block;
                height: 15px;

            }

            .widgetBody {
                background-color: white !important;
            }
            .widgetShadow {
                -webkit-box-shadow: 10px 10px 5px 0px rgba(158,147,158,1);
                -moz-box-shadow: 10px 10px 5px 0px rgba(158,147,158,1);
                box-shadow: 10px 10px 5px 0px rgba(158,147,158,1);
            }

            .checkNew {
                width: 20px;
                height: 20px;
                position: relative;
                margin: auto;

                input {
                    display: none;
                    &:checked + .box {
                        background-color: #b3ffb7;

                        &:after {
                            top: 0;
                        }
                    }
                }

                .boxCheckNew {
                    width: 100%;
                    height: 100%;
                    transition: all 1.1s cubic-bezier(.19,1,.22,1);
                    border: 2px solid transparent;
                    background-color: white;
                    position: relative;
                    overflow: hidden;
                    cursor: pointer;
                    box-shadow: 0 5px rgba(0,0,0,.2);
                    &:after {
                        width: 50%;
                        height: 20%;
                        content: '';
                        position: absolute;
                        border-left: 4.5px solid;
                        border-bottom: 4.5px solid;
                        border-color: #40c540;
                        transform: rotate(-45deg) translate3d(0,0,0);
                        transform-origin: center center;
                        transition: all 1.1s cubic-bezier(.19,1,.22,1);
                        left: 0;
                        right: 0;
                        top: 200%;
                        bottom: 5%;
                        margin: auto;
                    }
                }
            }



            .mCSB_inside > .mCSB_container{ margin-right: 5px;margin-left: 0px; }
            .mCSB_scrollTools {margin-right: 3px};

            .tooltip {
                position: fixed !important
                    z-index: 9999999999 !important;
            }

            .popover {
                max-width: 100% !important;
                padding-right: 0px !important;
            }

            .w2ui-data-disabled {
                background-color: rgba(210,210,210,0.5);
                height: 100%;
                padding: 5px 5px 6px 5px !important;
            }

            .w2ui-data-disabledPL {
                background-color: rgba(210,210,210,0.5);
                height: 100%;
                padding: 5px 5px 6px 5px !important;
                cursor: pointer;
            }

            .w2ui-selected .w2ui-data-disabled {
                background-color: rgba(220,220,220,0.4);   
            }

            .w2ui-selected .w2ui-data-disabledPL {
                background-color: rgba(220,220,220,0.4);   
            }

            .w2ui-data-disabled-no-lock {
                background-color: rgba(210,210,210,0.5);
                height: 100%;
                padding: 5px 5px 6px 5px !important;


            }
            .w2ui-selected .w2ui-data-disabled-no-lock {
                background-color: rgba(220,220,220,0.4); 
            }

            .ui-dialog { z-index: 1000 !important}
            .ui-widget-overlay { z-index: 900 !important}


            .picklistMin700 {
                min-width: 700px;
                min-width: 700px;

            }

            .picklistMin800 {
                min-width: 800px;
                max-width: 800px;
            }


            .modal-header-picklist_cgb {
                /* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#b4e391+0,61c419+50,b4e391+100;Green+3D */
                background: rgb(180,227,145); /* Old browsers */
                background: -moz-linear-gradient(top, rgba(192,192,206,1) 0%, rgba(208,211,214,1) 50%, rgba(196,196,208,1) 100%); /* FF3.6-15 */
                background: -webkit-linear-gradient(top, rgba(192,192,206,1) 0%,rgba(208,211,214,1) 50%,rgba(196,196,208,1) 100%); /* Chrome10-25,Safari5.1-6 */
                background: linear-gradient(to bottom, rgb(192, 192, 206) 0%,rgb(208, 211, 214) 50%,rgb(196, 196, 208) 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
                
                
                filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#b4e391', endColorstr='#b4e391',GradientType=0 ); /* IE6-9 */
                width: 100%;
                height: 30px;
                padding: 2px 2px;
                margin-bottom: 2px;
                padding-left: 10px;
                padding-top: 4px;
                border-bottom:1px solid #eee;

                font-weight: bold;
                border-top-left-radius: 5px;
                border-top-right-radius: 5px;
                border-bottom-left-radius: 5px;
                border-bottom-right-radius: 5px;


            }


            .modal-header-form_cgb {
                /* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#b3dced+0,29b8e5+50,bce0ee+100;Blue+Pipe */
                background: rgb(179,220,237); /* Old browsers */
                background: -moz-linear-gradient(top, rgba(179,220,237,1) 0%, rgba(41,184,229,1) 50%, rgba(188,224,238,1) 100%); /* FF3.6-15 */
                background: -webkit-linear-gradient(top, rgba(179,220,237,1) 0%,rgba(41,184,229,1) 50%,rgba(188,224,238,1) 100%); /* Chrome10-25,Safari5.1-6 */
                background: linear-gradient(to bottom, rgba(179,220,237,1) 0%,rgba(41,184,229,1) 50%,rgba(188,224,238,1) 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
                filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#b3dced', endColorstr='#bce0ee',GradientType=0 ); /* IE6-9 */


                width: 100%;
                height: 30px;
                padding: 2px 2px;
                margin-bottom: 2px;
                padding-left: 10px;
                padding-top: 4px;

                border-top-left-radius: 5px;
                border-top-right-radius: 5px;
                border-bottom-left-radius: 5px;
                border-bottom-right-radius: 5px;

                font-weight: bold;

            }





            .modal-header-picklist-close_cgb {
                float: right; 
                padding-right: 10px;
                padding-top: 3px;
                cursor: pointer;
            }
            .control-sidebar-bg,
            .control-sidebar {
                right: -300px;
                width: 300px;

            }

            .mbSettingsCheckBox {
                /*width: 30px;*/
                float: right;
            }
            .mbSettingsDropdown {
                width: 130px;
            }


            hr {
                margin-top: 5px;
                margin-bottom: 10px;
            }


        </style>

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="hold-transition <?php echo ($skin); ?> sidebar-mini fixed">
        <div class="wrapper">

            <header class="main-header">
                <!-- Logo -->
                <a href="#" class="logo">
                    <!-- mini logo for sidebar mini 50x50 pixels -->
                    <span class="logo-mini"><?php echo ($mainAbbrev); ?></span>
                    <!-- logo for regular state and mobile devices -->
                    <span class="logo-lg"><b><?php echo ($main); ?></b></span>
                </a>
                <!-- Header Navbar: style can be found in header.less -->
                <nav class="navbar navbar-static-top" role="navigation">
                    <!-- Sidebar toggle button-->
                    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button" id='sidebarToggle'>
                        <span class="sr-only">Toggle navigation</span>
                    </a>

                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">


                            <!-- Tasks: style can be found in dropdown.less -->
                            <li class="dropdown tasks-menu" style="display: none">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-flag-o"></i>
                                    <span class="label label-danger">9</span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="header">You have 9 tasks</li>
                                    <li>
                                        <!-- inner menu: contains the actual data -->
                                        <ul class="menu">
                                            <li><!-- Task item -->
                                                <a href="#">
                                                    <h3>
                                                        Design some buttons
                                                        <small class="pull-right">20%</small>
                                                    </h3>
                                                    <div class="progress xs">
                                                        <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                                                            <span class="sr-only">20% Complete</span>
                                                        </div>
                                                    </div>
                                                </a>
                                            </li>
                                            <!-- end task item -->
                                            <li><!-- Task item -->
                                                <a href="#">
                                                    <h3>
                                                        Create a nice theme
                                                        <small class="pull-right">40%</small>
                                                    </h3>
                                                    <div class="progress xs">
                                                        <div class="progress-bar progress-bar-green" style="width: 40%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                                                            <span class="sr-only">40% Complete</span>
                                                        </div>
                                                    </div>
                                                </a>
                                            </li>
                                            <!-- end task item -->
                                            <li><!-- Task item -->
                                                <a href="#">
                                                    <h3>
                                                        Some task I need to do
                                                        <small class="pull-right">60%</small>
                                                    </h3>
                                                    <div class="progress xs">
                                                        <div class="progress-bar progress-bar-red" style="width: 60%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                                                            <span class="sr-only">60% Complete</span>
                                                        </div>
                                                    </div>
                                                </a>
                                            </li>
                                            <!-- end task item -->
                                            <li><!-- Task item -->
                                                <a href="#">
                                                    <h3>
                                                        Make beautiful transitions
                                                        <small class="pull-right">80%</small>
                                                    </h3>
                                                    <div class="progress xs">
                                                        <div class="progress-bar progress-bar-yellow" style="width: 80%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                                                            <span class="sr-only">80% Complete</span>
                                                        </div>
                                                    </div>
                                                </a>
                                            </li>
                                            <!-- end task item -->
                                        </ul>
                                    </li>
                                    <li class="footer">
                                        <a href="#">View all tasks</a>
                                    </li>
                                </ul>
                            </li>
                            <!-- User Account: style can be found in dropdown.less -->
                            <li class="dropdown user user-menu" id='profileArea'>
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <img src="<?php echo ($userImage) ?>" class="user-image" alt="User Image">
                                    <span class="hidden-xs"><?php echo ($ds_human_resource_full); ?></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <!-- User image -->
                                    <li class="user-header">
                                        <img src="<?php echo ($userImage) ?>" class="img-circle" alt="User Image">

                                        <p>
                                            <?php echo ($ds_human_resource_full); ?>
                                        </p>
                                    </li>
                                    <!-- Menu Body -->
                                    <li class="user-body hidden">
                                        <div class="row">

                                        </div>
                                        <!-- /.row -->
                                    </li>
                                    <!-- Menu Footer-->
                                    <li class="user-footer">

                                        <?php
                                        if ($canprofile == 'Y') {
                                            echo ('                              
                              <div class="pull-left">
                                 <a href="#" class="btn btn-default btn-flat" onclick="goProfile();return false;">' . $profile . '</a>
                              </div>
                              ');
                                        }
                                        ?>


                                        <div class="pull-right">
                                            <a href="#" class="btn btn-default btn-flat" onclick="goLogout(); return false;"><?php echo ($signout); ?></a>
                                        </div>
                                    </li>
                                </ul>
                            </li>

                            <li>
                                <a href="#" onclick="hideDashBoard();return true;" id="dashHideDashboard"><i class="fa fa-file-text-o"></i></a>
                            </li>
                            <li>
                                <a href="#" onclick="showDashBoard();return true;" id="dashShowDashboard"><i class="fa fa-bar-chart"></i></a>
                            </li>
                            <li>
                                <a href="#" onclick="openSystemComments();" id="showHelper"><i class="fa fa-comments"></i></a>
                            </li>

                            <li class="hidden">
                                <a href="#" onclick="showHelper();return true;" id="showHelper"><i class="fa fa-question"></i></a>
                            </li>

                            <!-- Control Sidebar Toggle Button -->
                            <li>
                                <a href="#" data-toggle="control-sidebar" id='sidebarControl'><i class="fa fa-gears"></i></a>

                            </li>

                        </ul>

                    </div>
                </nav>
            </header>
            <!-- Left side column. contains the logo and sidebar -->
            <aside class="main-sidebar">
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar" id="xSidebar" style="height:  calc(100vh - 50px);">
                    <!-- Sidebar user panel -->
                    <div class="user-panel">
                        <div class="pull-left image">
                            <img src="<?php echo ($userImage) ?>" class="img-circle" alt="User Image" style="width: 30px;height: 30px;">
                        </div>
                        <div class="pull-left info">
                            <p><?php echo ($ds_human_resource_full); ?></p>
                        </div>
                    </div>

                    <div class="sidebar-form <?php
                    if (count($system_product_category_allowed) < 2) {
                        echo("hidden");
                    }
                    ?>" style="padding-left: 5px; padding-right: 10px;border: 0px;" id="prdcatSelectArea">
                        <select id="prdcatSelect" class="" style="width: 100%; font-size: 14px;color: black">
                            <?php
                            foreach ($system_product_category_allowed as $key => $value) {
                                if ($value['cd_system_product_category'] == $system_product_category) {
                                    $sel = 'selected';
                                } else {
                                    $sel = '';
                                }

                                $data = '<option value="' . $value['cd_system_product_category'] . '" ' . $sel . '>' . $value['ds_system_product_category'] . '</option>';
                                echo($data);
                            }
                            ?>
                        </select>
                    </div>


                    <!-- search form -->
                    <!--
                    <form action="#" method="get" class="sidebar-form">
                       <div class="input-group">
                          <input type="text" name="q" class="form-control" placeholder="Search...">
                          <span class="input-group-btn">
                             <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                             </button>
                          </span>
                       </div>
                    </form>
                    -->
                    <!-- /.search form -->
                    <!-- sidebar menu: : style can be found in sidebar.less -->

                    <ul class="sidebar-menu">
                        <li class="header">MAIN NAVIGATION</li>

                        <?php
                        foreach ($menu as $value) {
                            switch ($value['fl_has_sub']) {

                                case 'L':

                                    $style = '';

                                    if ($value['fl_visible'] == 'N') {
                                        $style = 'display: none;';
                                    }


                                    echo("<li id='p" . $value['cd_menu'] . "' style='$style'><a href=\"javascript:openpage('" . $value['ds_controller'] . "','" . $value['ds_menu'] . "', 'p" . $value['cd_menu'] . "');\">" . $value['ds_image'] . '<span>' . $value['ds_menu'] . '</span>' . "</a></li>");
                                    break;

                                case 'B1':
                                    echo('<li class="treeview">
           <a href="#">' . $value['ds_image'] . ' <span>' . $value['ds_menu'] . '</span> 
                    <i class="fa fa-angle-left pull-right"></i></a>
	       <ul class="treeview-menu">');
                                    break;

                                case 'B2':
                                    echo('<li class="treeview"> 
                 <a href="#">' . $value['ds_image'] . ' <span>' . $value['ds_menu'] . '</span> 
                             <i class="fa fa-angle-left pull-right"></i></a>
	             <ul class="treeview-menu">');
                                    break;


                                CASE 'E1':
                                CASE 'E2':
                                    echo ('</ul></li>');
                                    break;
                                default:
                                    break;
                            }
                        };
                        ?>

                    </ul>
                </section>
                <!-- /.sidebar -->
            </aside>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1 class="hidden-xs">
                        <div class ="choption" id="chopt"><?php echo ($main); ?></div>

                        <small></small>
                    </h1>

                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>

                    </ol>
                </section>

                <!-- Main content -->
                <section class="content" >

                    <div id ='content-body' class='container-fluid'> </div>
                    <div id="dashboardArea" class="container-fluid" style="display: none;"> </div>

                </section>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->
            <!-- O FOOTER ERA AQUI -->

            <!-- Control Sidebar -->
            <aside class="control-sidebar control-sidebar-dark">
                <!-- Create the tabs -->
                <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
                    <li class="hidden"><a href="#control-sidebar-home-tab" data-toggle="tab"  aria-expanded="true"><div class="hidden"><i class="fa fa-home"></div></i></a></li>
                    <li class="active"><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears hidden"></i></a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <!-- Home tab content -->
                    <div class="tab-pane" id="control-sidebar-home-tab">
                        <h3 class="control-sidebar-heading">Recent Activity</h3>
                        <ul class="control-sidebar-menu">


                    </div>
                    <!-- /.tab-pane -->
                    <!-- Stats tab content -->
                    <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
                    <!-- /.tab-pane -->
                    <!-- Settings tab content -->
                    <div class="tab-pane active" id="control-sidebar-settings-tab">
                        <form method="post">
<?php echo ($config); ?>


                        </form>
                    </div>
                    <!-- /.tab-pane -->
                </div>
            </aside>
            <!-- /.control-sidebar -->
            <!-- Add the sidebar's background. This div must be placed
                 immediately after the control sidebar -->
            <div class="control-sidebar-bg"></div>
        </div>
        <!-- ./wrapper -->

        <!-- jQuery 2.1.4 -->
        <script src="<?php echo base_url(); ?>plugins/jQuery/jquery-2.2.4.js"></script>

        <script type="text/javascript" src="<?php echo base_url(); ?>plugins/jquery-ui/1.11.4/jquery-ui.min.js"></script>
        <script src="<?php echo base_url(); ?>plugins/jQuery-File-Upload/js/jquery.fileupload.js"></script>
        
        <script type="text/javascript" src="<?php echo base_url(); ?>plugins/imageViewer/viewer.js"></script>
        
        <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
        <script>
            $.widget.bridge('uibutton', $.ui.button);

            var vExtUpload = '<?php echo ($extUpload)?>';
                                    
                                    
                                    
        </script>
        <!-- TW GAntt (lixo)
                <script src="<?php echo base_url(); ?>plugins/jQueryGanttTW/libs/jquery/jquery.livequery.1.1.1.min.js"></script>
                <script src="<?php echo base_url(); ?>plugins/jQueryGanttTW/libs/jquery/jquery.timers.js"></script>
                <script src="<?php echo base_url(); ?>plugins/jQueryGanttTW/libs/utilities.js"></script>
                <script src="<?php echo base_url(); ?>plugins/jQueryGanttTW/libs/forms.js"></script>
                <script src="<?php echo base_url(); ?>plugins/jQueryGanttTW/libs/date.js"></script>
                <script src="<?php echo base_url(); ?>plugins/jQueryGanttTW/libs/dialogs.js"></script>
                <script src="<?php echo base_url(); ?>plugins/jQueryGanttTW/libs/layout.js"></script>
                <script src="<?php echo base_url(); ?>plugins/jQueryGanttTW/libs/i18nJs.js"></script>
                <script src="<?php echo base_url(); ?>plugins/jQueryGanttTW/libs/jquery/dateField/jquery.dateField.js"></script>
                <script src="<?php echo base_url(); ?>plugins/jQueryGanttTW/libs/jquery/JST/jquery.JST.js"></script>
        
                <script type="text/javascript" src="<?php echo base_url(); ?>plugins/jQueryGanttTW/libs/jquery/svg/jquery.svg.min.js"></script>
                <script type="text/javascript" src="<?php echo base_url(); ?>plugins/jQueryGanttTW/libs/jquery/svg/jquery.svgdom.1.8.js"></script>
        
        
                <script src="<?php echo base_url(); ?>plugins/jQueryGanttTW/ganttUtilities.js"></script>
                <script src="<?php echo base_url(); ?>plugins/jQueryGanttTW/ganttTask.js"></script>
                <script src="<?php echo base_url(); ?>plugins/jQueryGanttTW/ganttDrawerSVG.js"></script>
                <script src="<?php echo base_url(); ?>plugins/jQueryGanttTW/ganttZoom.js"></script>
                <script src="<?php echo base_url(); ?>plugins/jQueryGanttTW/ganttGridEditor.js"></script>
                <script src="<?php echo base_url(); ?>plugins/jQueryGanttTW/ganttMaster.js"></script>  
        
        -->

        <script src="<?php echo base_url(); ?>plugins/jQuery-FixTableHeader/jQuery.fixTableHeader.js"></script>


        <!-- Bootstrap 3.3.5 -->
        <script src="<?php echo base_url(); ?>bootstrap/js/bootstrap.min.js"></script>

        <script src="<?php echo base_url(); ?>plugins/bootstrap-tabs-x/js/bootstrap-tabs-x.min.js"></script>
        <!-- jvectormap -->
        <script src="<?php echo base_url(); ?>plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
        <script src="<?php echo base_url(); ?>plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
        <!-- select2 -->
        <script src="<?php echo base_url(); ?>plugins/select2-3.5.1/select2.js"></script>

        <!-- jQuery Knob Chart -->
        <script src="<?php echo base_url(); ?>plugins/knob/jquery.knob.js"></script>
        <!-- daterangepicker -->
        <script src="<?php echo base_url(); ?>plugins/moment/2.18/moment.min.js"></script>
        <script src="<?php echo base_url(); ?>plugins/moment/weekday-calc/moment-weekday-calc.min.js"></script>


        <script src="<?php echo base_url(); ?>plugins/daterangepicker/daterangepicker.js"></script>
        <script src="<?php echo base_url(); ?>plugins/datetimerangepick/dist/jquery.daterangepicker.min.js"></script>


        <!-- datepicker -->
        <script src="<?php echo base_url(); ?>plugins/datepicker/bootstrap-datepicker.js"></script>
        <!-- Bootstrap WYSIHTML5 -->
        <script src="<?php echo base_url(); ?>plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
        <!-- Slimscroll -->
        <script src="<?php echo base_url(); ?>plugins/slimScroll/jquery.slimscroll.min.js"></script>



        <script src="<?php echo base_url(); ?>plugins/malihu-custom-scrollbar/jquery.mCustomScrollbar.concat.min.js"></script>

        <script src="<?php echo base_url(); ?>plugins/iCheck/icheck.min.js"></script>

        <!-- FastClick -->
        <script src="<?php echo base_url(); ?>plugins/fastclick/fastclick.js"></script>
        <!-- AdminLTE App -->
        <script src="<?php echo base_url(); ?>dist/js/app.js?6"></script>



        <script type="text/javascript" src="<?php echo base_url(); ?>plugins/jquery-confirm-master-3.0.3/js/jquery-confirm.js"></script>

        <script src="/main/loadJS/<?php echo ($hashCommit); ?>"></script>

        <!--
        <script type="text/javascript" src="<?php echo base_url(); ?>application/javascripts/libraryDocRep.js"></script>
        -->

        <!-- Toast -->
        <script type="text/javascript" src="<?php echo base_url(); ?>plugins/toastr/toastr.min.js"></script>

        <!-- Vis -->
        <script type="text/javascript" src="<?php echo base_url(); ?>plugins/vis-4.21.0/dist/vis.min.js"></script>






        <script type="text/javascript" src="<?php echo base_url(); ?>plugins/contextMenu/jquery.contextmenu.js"></script>

        <script type="text/javascript" src="<?php echo base_url(); ?>plugins/autoNumeric/autoNumeric-min.js"></script>

        <script type="text/javascript" src="<?php echo base_url(); ?>plugins/datatables3/datatables.min.js"></script>

        <script type="text/javascript" src="<?php echo base_url(); ?>plugins/popover-x/js/bootstrap-popover-x.js"></script>

        <script type="text/javascript" src="<?php echo base_url(); ?>plugins/jquery-loading-overlay-1.4.1/src/loadingoverlay.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>plugins/toolbar.js/jquery.toolbar.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>plugins/pace/pace.js"></script>

        <script type="text/javascript" src="<?php echo base_url(); ?>plugins/webui-popover-master/dist/jquery.webui-popover.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>plugins/eCharts/dist/echarts.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>plugins/echarts-stat/dist/ecStat.min.js"></script>


        <script type="text/javascript" src="<?php echo base_url(); ?>plugins/Sortable/Sortable.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>plugins/bootstrap-slider/bootstrap-slider.min.js"></script>

        

        <script type="text/javascript" src="<?php echo base_url(); ?>plugins/fullcalendar/fullcalendar.min.js"></script>


        <!-- variaveis globais-->
        <script type="text/javascript">
<?php if ($fl_demand_profile == 'Y') { ?>

                                        setTimeout(function () {
                                            goProfile();
                                        }, 0);

<?php } ?>


                                    $('#prdcatSelect').select2({minimumResultsForSearch: -1});
                                    var vlastSel = $("#prdcatSelect").val();
                                    $("#prdcatSelect").change(function () {
                                        var $self = $(this);

                                        var $selected = $self.find("option:selected");
                                        messageBoxYesNo('Do you Really want to change the Context to ' + $selected.text(), function () {
                                            window.location.href = 'main/changeProdCat/' + $self.val();
                                        }, function () {
                                            $('#prdcatSelect').select2("val", vlastSel);
                                        });
                                    });

                                    var codePK = -1;
                                    var javaMessages = <?php echo ($jmessages); ?>;
                                    var defaultDateFormat = '<?php echo ($dateFormat); ?>';
                                    var defaultDateFormatUpper = defaultDateFormat.toUpperCase();
                                    var isFirefox = !(window.mozInnerScreenX == null);
                                    w2utils.settings.date_format = defaultDateFormat;
                                    var fStringUpper = <?php echo ($fstringUpper); ?>;
                                    var fStringLower = <?php echo ($fstringLower); ?>;
                                    var fPickList = <?php echo ($fPicklist); ?>;
                                    // defailt do jspanel
                                    function goLogout() {
                                        location.href = "<?php echo base_url(); ?>index.php/main/logout"
                                    }

                                    $(document).ready(function () {
                                        var controllerToOpen = '<?php echo ($controllerToOpen); ?>';
                                        var controllerTitle = '<?php echo ($controllerTitle); ?>';
                                        var controllerId = 'p' + '<?php echo ($controllerId); ?>';
                                        var vControllerParam = '<?php echo ($controllerParms); ?>';

                                        var vDataTableDyn = undefined;

                                        if (controllerToOpen != '') {
                                            openpage(controllerToOpen, controllerTitle, controllerId, false, vControllerParam);
                                            //systemMenuClose();

                                            //$('.main-header').hide();
                                            //$('.main-sidebar').hide();
                                            //$('.content-wrapper').css('padding-top', '0px');
                                            //$('.content-wrapper').css('margin-left', '0px');



                                        }


                                        introAddNew({steps: [
                                                {intro: 'This will show the basic of the system'},
                                                {
                                                    element: '#profileArea',
                                                    intro: 'Profile and Logout Area'

                                                },
                                                {
                                                    element: '#dashShowDashboard',
                                                    intro: 'Show Dashboard'

                                                },
                                                {
                                                    element: '#showHelper',
                                                    intro: 'This Help'

                                                },

                                                {
                                                    element: '#sidebarControl',
                                                    intro: 'Control Sidebar'

                                                },

                                                {element: '#sidebarToggle',
                                                    intro: 'Toggle the Menu'

                                                },

                                                {
                                                    element: '.sidebar-menu',
                                                    intro: "Menu Area",
                                                    position: 'bottom'

                                                },
                                            ]});

                                        $('.mbSettingsDropdown').select2();
                                        $('#dashboardArea').load('dashboard/dashboard', function () {
<?php if ($fl_demand_profile == 'N') { ?>
                                                if (controllerToOpen === '') {
                                                    hideDashBoard();
                                                    var vWhat = '<?php echo($whatToOpen) ?>';
                                                    var vmenu = vWhat.split(';')[0];
                                                    switch (vmenu) {
                                                        case "-2":
                                                            showDashBoard();
                                                            break;

                                                        case "-1":
                                                            //showDashBoard();
                                                            break;

                                                        default:
                                                            var scr = $('#p' + vmenu).children('a').attr('href');
                                                            eval(scr);
                                                            break;
                                                    }
                                                }
<?php } ?>
                                        });
                                        // create a timer to detect system notification, but this may overwhelm the system resources.
                                        getSytNotification();
                                        setInterval(getSytNotification, 600 * 1000);

                                    });

                                    function getSytNotification() {
                                        $.myCgbAjax({
                                            url: 'system_notification/getSysNotification',
                                            box: 'none',
                                            async: false,
                                            systemRequest: true,
                                            success: function (data) {
                                                $.each(data, function (index, value) {
                                                    toastSysNotify(value.ds_system_notification, value.fl_acknowledge_require, value.cd_system_notification, value.fl_has_attachment, value.cd_system_feedback_comments);
                                                });
                                            }
                                        });
                                        if ($('#toast-container').length > 0) {
                                            $('#toast-container').css({top: '52px'});
                                        }
                                    }
                                    function goProfile() {
                                        var scr = $('#p61').children('a').attr('href');
                                        eval(scr);
                                    }

                                    function openSystemComments() {
                                        var scr = $('#p217').children('a').attr('href');
                                        eval(scr);
                                    }

                                    $.AdminLTE.options.animationSpeed = 250;


                                    function showHelper() {

                                        introStart();
                                    }

                                    //languages adjustment:
                                    w2utils.settings.phrases['All Fields'] = javaMessages.filterText;





        </script>

    </body>


    <div id="main_form_div" ></div>
    <div id="main_form_div_picklist"></div>

    <div id="main_gen_export_datatables_div" style="display: none;">
        <table id="main_gen_export_datatables" style="width: 80%"> </table>
    </div>


    <div id="toolbar-options" class="hidden">
        <a href="#"><i class="fa fa-plane"></i>Teste1</a>
        <a href="#"><i class="fa fa-car"></i>teste2</a>
        <a href="#"><i class="fa fa-bicycle"></i>teste2</a>
    </div>

</html>

