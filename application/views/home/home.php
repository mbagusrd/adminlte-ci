<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>
        <?php echo $judul_menu ?> |
        <?php echo getenv("APP_NAME") ? getenv("APP_NAME") : "ADMINLTE CI"; ?>
    </title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?php echo base_url('aset/gambar/favicon.ico'); ?>">
    <!-- css -->
    <?php if ($this->session->userdata("username") != "") { ?>
        <link rel="stylesheet" href="<?php echo base_url('aset/plugins/datatables/datatables.min.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url('aset/plugins/select2/css/select2.min.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url('aset/plugins/sweetalert/sweetalert2.min.css'); ?>">
    <?php } ?>
    <link rel="stylesheet" href="<?php echo base_url('aset/plugins/fontawesome-free/css/all.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('aset/plugins/overlayScrollbars/css/OverlayScrollbars.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('aset/plugins/datepicker/css/bootstrap-datepicker.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('aset/adminlte/css/adminlte.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('aset/custom/adminlte-ci.css'); ?>">
    <!-- js -->
    <script type="text/javascript" src="<?php echo base_url('aset/plugins/jquery/jquery.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('aset/plugins/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('aset/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('aset/plugins/jquery.validate/jquery.validate.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('aset/plugins/datepicker/js/bootstrap-datepicker.min.js'); ?>"></script>
    <?php if ($this->session->userdata("username") != "") { ?>
        <script type="text/javascript" src="<?php echo base_url('aset/plugins/datatables/datatables.min.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('aset/plugins/select2/js/select2.min.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('aset/plugins/sweetalert/sweetalert2.min.js'); ?>"></script>
    <?php } ?>
    <script type="text/javascript" src="<?php echo base_url('aset/adminlte/js/adminlte.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('aset/custom/adminlte-ci.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('aset/plugins/phpjs/number_format.js'); ?>"></script>
    <script type="text/javascript">
        var situs = "<?php echo site_url() ?>";
    </script>
</head>

<body class="hold-transition layout-fixed layout-navbar-fixed">
    <!-- Site wrapper -->
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="javascript:void(0)" class="nav-link">
                        <?php echo getenv("APP_NAME") ? getenv("APP_NAME") : "ADMINLTE CI"; ?> </a>
                </li>
            </ul>
            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Notifications Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="far fa-user"></i>&nbsp;
                        <?php
                        echo ($this->session->userdata("username") == "") ? "Login" : $this->session->userdata("username");
                        ?>
                    </a>
                    <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 dropdown-menu-right">
                        <?php if ($this->session->userdata("username") == "") { ?>
                            <li><a href="javascript:void(0)" class="dropdown-item"> Silahkan Login </a></li>
                        <?php } else { ?>
                            <li>
                                <a href="javascript:void(0)" class="dropdown-item text-muted text-center h5">
                                    <?php echo $this->session->userdata("nama"); ?> </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)" class="dropdown-item text-muted text-center"> <small>
                                        <?php echo $this->session->userdata("nm_grup"); ?> </small> </a>
                            </li>
                            <li class="dropdown-divider"></li>
                            <li>
                                <a href="<?php echo site_url('login/keluar'); ?>" class="dropdown-item"> <i class="fas fa-sign-out-alt"></i> Log Out </a>
                            </li>
                        <?php } ?>
                    </ul>
                </li>
                <!-- <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-bell"></i> -->
                <!-- <span class="badge badge-success navbar-badge"><strong>0</strong></span> -->
                <!-- </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-item dropdown-header">0 Notifications</span> -->
                <!-- <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-envelope mr-2"></i> 4 new messages
                            <span class="float-right text-muted text-sm">3 mins</span>
                        </a> -->
                <!-- </div>
                </li> -->
            </ul>
        </nav>
        <!-- /.navbar -->
        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-2">
            <!-- Brand Logo -->
            <a href="<?php echo site_url(); ?>" class="brand-link">
                <img src="<?php echo base_url('aset/gambar/apps-logo.png'); ?>" class="brand-image">
                <span class="brand-text font-weight-light">Admin</span>
            </a>
            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar Menu -->
                <nav class="mt-2 mb-5">
                    <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
                        <li class="nav-item">
                            <a href="<?php echo site_url(); ?>" class="nav-link">
                                <i class="nav-icon fas fa-home"></i>
                                <p>Home</p>
                            </a>
                        </li>
                        <?php if ($this->session->userdata("id_grup") == "1") { ?>
                            <li class="nav-header">Admin Menu</li>
                            <li class="nav-item">
                                <a href="<?php echo site_url('setting/index/grup-user'); ?>" class="nav-link">
                                    <i class="nav-icon fas fa-users"></i>
                                    <p>Grup & User</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo site_url('setting/index/menu'); ?>" class="nav-link">
                                    <i class="nav-icon fas fa-list"></i>
                                    <p>Menu</p>
                                </a>
                            </li>
                        <?php } ?>
                        <?php if ($this->session->userdata("username") != "" and sizeof($sidebar_menu) > 0) { ?>
                            <li class="nav-header">Main Menu</li>
                        <?php } ?>
                        <?php foreach ($sidebar_menu as $key => $value) {
                            $class_treeview = ($value['jns_menu'] == "1") ? "has-treeview" : "";
                            $icon_menu      = ($value['jns_menu'] == "1") ? "fas fa-folder" : "far fa-circle";
                            $icon_panah     = ($value['jns_menu'] == "1") ? "<i class=\"fas fa-angle-left right\"></i>" : "";
                            $menu_link      = ($value['jns_menu'] == "1") ? "javascript:void(0)" : site_url($value['link']);

                            echo "<li class=\"nav-item " . $class_treeview . "\">
                                <a href=\"" . $menu_link . "\" class=\"nav-link\">
                                    <i class=\"nav-icon " . $icon_menu . "\"></i>
                                    <p>" . $value['nm_menu'] . " " . $icon_panah . "</p>
                                </a>";

                            if (sizeof($value['sub_menu']) > 0) {
                                echo "<ul class=\"nav nav-treeview\">";

                                foreach ($value['sub_menu'] as $key1 => $value1) {
                                    $class_treeview = ($value1['jns_menu'] == "1") ? "has-treeview" : "";
                                    $icon_menu      = ($value1['jns_menu'] == "1") ? "far fa-folder" : "far fa-circle";
                                    $icon_panah     = ($value1['jns_menu'] == "1") ? "<i class=\"fas fa-angle-left right\"></i>" : "";
                                    $menu_link      = ($value1['jns_menu'] == "1") ? "javascript:void(0)" : site_url($value1['link']);

                                    echo "<li class=\"nav-item " . $class_treeview . "\">
                                        <a href=\"" . $menu_link . "\" class=\"nav-link\">
                                            <i class=\"nav-icon " . $icon_menu . "\"></i>
                                            <p>" . $value1['nm_menu'] . " " . $icon_panah . "</p>
                                        </a>";

                                    if (sizeof($value1['sub_menu']) > 0) {
                                        echo "<ul class=\"nav nav-treeview\">";

                                        foreach ($value1['sub_menu'] as $key2 => $value2) {
                                            $class_treeview = ($value2['jns_menu'] == "1") ? "has-treeview" : "";
                                            $icon_menu      = ($value2['jns_menu'] == "1") ? "far fa-folder" : "far fa-circle";
                                            $icon_panah     = ($value2['jns_menu'] == "1") ? "<i class=\"fas fa-angle-left right\"></i>" : "";
                                            $menu_link      = ($value2['jns_menu'] == "1") ? "javascript:void(0)" : site_url($value2['link']);

                                            echo "<li class=\"nav-item " . $class_treeview . "\">
                                                <a href=\"" . $menu_link . "\" class=\"nav-link\">
                                                    <i class=\"nav-icon " . $icon_menu . "\"></i>
                                                    <p>" . $value2['nm_menu'] . " " . $icon_panah . "</p>
                                                </a>";

                                            echo "</li>";
                                        }

                                        echo "</ul>";
                                    }

                                    echo "</li>";
                                }

                                echo "</ul>";
                            }

                            echo "</li>";
                        } ?>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper pb-2 pl-2 pr-2">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <h1>
                                <?php echo $judul_menu ?>
                            </h1>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>
            <!-- Main content -->
            <section class="content">
                <div class="preloader">
                    <div class="loading text-center">
                        <img src="<?php echo base_url('aset/gambar/preloader.gif') ?>" class="mb-3">
                        <p>Harap Tunggu</p>
                    </div>
                </div>
                <?php echo $page ?>
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <footer class="main-footer">
            <div class="float-right d-none d-sm-block"> </div>
            <strong>
                Copyright &copy; 2024
                <a href="<?php echo site_url() ?>">
                    <?php echo getenv("APP_NAME") ? getenv("APP_NAME") : "ADMINLTE CI"; ?>
                </a>.
            </strong>
        </footer>
    </div>
    <!-- ./wrapper -->
</body>

</html>