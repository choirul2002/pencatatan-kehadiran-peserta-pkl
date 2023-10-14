<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $database[0]['singkatan'] ?> | <?= $database[0]['nama_sistem'] ?></title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url() ?>/vendor/plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="<?= base_url() ?>/vendor/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url() ?>/vendor/dist/css/adminlte.min.css">
    <!-- Sweetalert2 -->
    <link rel="stylesheet" href="<?= base_url() ?>/vendor/plugins/sweetalert2/sweetalert2.min.css">
    <link href="<?= base_url() ?>/public/service/icon/<?= $database[0]['logo_sistem'] ?>" rel="icon">
    <link href="<?= base_url() ?>/public/service/icon/<?= $database[0]['logo_sistem'] ?>" rel="apple-touch-icon">
    <style>

    </style>
</head>

<?php if (!empty(session())) { ?>
    <div class="flash-data" data-flashdata="<?= session()->getflashdata('flash') ?>"></div>
<?php } ?>

<body style="background-color: #e9ecef;">
    <section class="content" style="padding-top: 120px;padding-bottom: 120px;">
        <div class="error-page" style="width: 800px;">
            <div class="headline p-4">
                <img src="<?= base_url() ?>/public/service/icon/maintenance.png" width="300" alt="">
            </div>
          <div class="error-content">
            <p style="font-size: 70px;" class="headline text-warning mb-0"> 404 !!!</p>
            <h3><i class="fas fa-exclamation-triangle text-warning"></i> Oops! <b>Website Maintenance</b>.</h3>
    
            <p>
              The system is still under maintenance and cannot be accessed.
              Meanwhile, you can go back to login.
            </p>
    
            <?php 
            session(); 
            if(isset($_SESSION['maintenance_admin']) == "admin"){ ?>
              <div class="pt-3 d-flex justify-content-between">
                <a href="<?= base_url() ?>/ld" style="width: 130px;" class="btn btn-warning rounded-pill text-white">Back</a>
                <a href="<?= base_url() ?>/mts" style="width: 180px;" class="btn btn-primary rounded-pill text-white">Active System</a>
              </div>
            <?php }else{ ?>
              <div class="pt-3 d-flex justify-content-center"><a href="<?= base_url() ?>/ld" style="width: 130px;" class="btn btn-warning rounded-pill text-white">Back</a></div>
            <?php } ?>
          </div>
        </div> 
    </section>



    <!-- jQuery -->
    <script src="<?= base_url() ?>/vendor/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="<?= base_url() ?>/vendor/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- sweetalert2 -->
    <script src="<?= base_url() ?>/vendor/plugins/sweetalert2/sweetalert2.all.min.js"></script>
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="<?= base_url() ?>/vendor/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
</body>

</html>