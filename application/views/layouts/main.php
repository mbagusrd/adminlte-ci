<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= isset($title) ? html_escape($title) . ' | AdminLTE 3' : 'AdminLTE 3' ?></title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= base_url('assets/plugins/fontawesome-free/css/all.min.css') ?>">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= base_url('assets/adminlte/css/adminlte.min.css') ?>">
  <?php if (isset($extra_css)): ?>
    <?= $extra_css ?>
  <?php endif; ?>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Navbar -->
  <?php $this->load->view('layouts/navbar'); ?>

  <!-- Main Sidebar Container -->
  <?php $this->load->view('layouts/sidebar'); ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-12">
            <h1><?= isset($title) ? html_escape($title) : 'Page Title' ?></h1>
          </div>
        </div>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <?php
        if (isset($content_view)) {
            $this->load->view($content_view);
        } elseif (isset($content)) {
            echo $content;
        }
        ?>
      </div>
    </section>
  </div>

  <!-- Footer -->
  <?php $this->load->view('layouts/footer'); ?>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
  </aside>
</div>

<!-- jQuery -->
<script src="<?= base_url('assets/plugins/jquery/jquery.min.js') ?>"></script>
<!-- Bootstrap 4 -->
<script src="<?= base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<!-- AdminLTE App -->
<script src="<?= base_url('assets/adminlte/js/adminlte.min.js') ?>"></script>
<?php if (isset($extra_js)): ?>
  <?= $extra_js ?>
<?php endif; ?>
</body>
</html>
