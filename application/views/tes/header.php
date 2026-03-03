<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>
        <?php echo getenv("APP_NAME") ? getenv("APP_NAME") : "ADMINLTE CI"; ?>
    </title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- favicon -->
    <link rel="icon" type="text/css" href="<?php echo base_url('aset/gambar/favicon.ico'); ?>">
    <!-- css -->
    <link rel="stylesheet" href="<?php echo base_url('aset/adminlte/bootstrap/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('aset/adminlte/css/AdminLTE.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('aset/adminlte/css/skins/skin-blue.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('aset/plugins/fa/css/font-awesome.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('aset/plugins/datatables/datatables.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('aset/plugins/select2/select2.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('aset/plugins/datepicker/css/bootstrap-datepicker.standalone.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('aset/plugins/sweetalert/sweetalert2.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('aset/custom/adminku-lte.css'); ?>">
    <!-- js -->
    <script type="text/javascript" src="<?php echo base_url('aset/plugins/jquery/jquery.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('aset/adminlte/bootstrap/js/bootstrap.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('aset/adminlte/js/adminlte.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('aset/plugins/datatables/datatables.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('aset/plugins/select2/select2.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('aset/plugins/datepicker/js/bootstrap-datepicker.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('aset/plugins/sweetalert/sweetalert2.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('aset/plugins/jquery.validate/jquery.validate.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('aset/plugins/phpjs/json_encode.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('aset/plugins/phpjs/number_format.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('aset/custom/adminku-lte.js'); ?>"></script>
    <script type="text/javascript">
    situs = "<?php echo site_url(); ?>";
    </script>
</head>