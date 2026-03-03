<body class="hold-transition skin-blue fixed sidebar-collapse">
    <div class="wrapper">
        <header class="main-header">
            <a href="<?php echo base_url(); ?>" class="logo">
                <span class="logo-mini"><b>KW</b>SG</span>
                <span class="logo-lg"><b>KW</b>SG</span>
            </a>
            <nav class="navbar navbar-static-top">
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <div class="navbar-brand"> <?php echo getenv("APP_NAME") ? getenv("APP_NAME") : "ADMINLTE CI"; ?> </div>
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-user"></i>
                                <span class="hidden-xs">
                                    <?php echo $this->session->userdata('nama'); ?>
                                </span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="user-header">
                                    <p class="text-center">
                                        <span class="fa fa-user fa-5x"></span><br> <?php echo $this->session->userdata('username'); ?><br>
                                        <small>
                                            <?php echo $this->session->userdata('nm_grup'); ?>
                                        </small>
                                    </p>
                                </li>
                                <li class="user-body">
                                    <?php if ($this->session->userdata('username')) {?>
                                    <a href="<?php echo site_url('login/keluar'); ?>" class="btn btn-default"><i class="fa fa-sign-out"></i> Log Out</a>
                                    <?php }else { ?>
                                        <center>Silahkan Login</center>
                                    <?php } ?>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>