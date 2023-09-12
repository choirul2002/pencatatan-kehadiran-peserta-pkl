<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php if ($title == "Notifikasi") { ?>
    <title><?= $database[20][0]['SINGKATAN'] ?> | <?= $title . " (" . $jumlah . ")" ?></title>
  <?php } else { ?>
    <title><?= $database[20][0]['SINGKATAN'] ?> | <?= $title ?></title>
  <?php } ?>
  <!-- icon judul web -->
  <link href="<?= base_url() ?>/service/icon/<?= $database[20][0]['LOGO_SISTEM'] ?>" rel="icon">
  <link href="<?= base_url() ?>/service/icon/<?= $database[20][0]['LOGO_SISTEM'] ?>" rel="apple-touch-icon">
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= base_url() ?>/plugins/fontawesome-free/css/all.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="<?= base_url() ?>/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= base_url() ?>/dist/css/adminlte.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="<?= base_url() ?>/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="<?= base_url() ?>/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="<?= base_url() ?>/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <!-- Sweetalert2 -->
  <link rel="stylesheet" href="<?= base_url() ?>/plugins/sweetalert2/sweetalert2.min.css">

  <link rel="stylesheet" href="<?= base_url() ?>/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <link rel="stylesheet" href="<?= base_url() ?>/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">

  <!-- Select2 -->
  <link rel="stylesheet" href="<?= base_url() ?>/plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="<?= base_url() ?>/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="<?= base_url() ?>/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
        <!-- fullcalendar css  -->
  <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.8.0/main.css' rel='stylesheet' />
</head>

<?php if (!empty(session())) { ?><?php } ?>
<div class="flash-data" data-flashdata="<?= session()->getflashdata('flash') ?>"></div>

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">