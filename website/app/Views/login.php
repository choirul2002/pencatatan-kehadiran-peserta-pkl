<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $database[0]['SINGKATAN'] ?> | <?= $database[0]['NAMA_SISTEM'] ?></title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url() ?>/plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="<?= base_url() ?>/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url() ?>/dist/css/adminlte.min.css">
    <!-- Sweetalert2 -->
    <link rel="stylesheet" href="<?= base_url() ?>/plugins/sweetalert2/sweetalert2.min.css">
    <link href="<?= base_url() ?>/service/icon/<?= $database[0]['LOGO_SISTEM'] ?>" rel="icon">
    <link href="<?= base_url() ?>/service/icon/<?= $database[0]['LOGO_SISTEM'] ?>" rel="apple-touch-icon">
    <style>

    </style>
</head>

<?php if (!empty(session())) { ?>
    <div class="flash-data" data-flashdata="<?= session()->getflashdata('flash') ?>"></div>
<?php } ?>

<body class="hold-transition d-flex justify-content-center" style="background-color: #e9ecef;">
    <div class="login-box" style="width: 700px;padding: 154px 0px 154px 0px;">
        <div class="card">
            <div class="card-body login-card-body rounded-lg px-2 py-0">
                <div class="row">
                    <div class="col-6 d-flex justify-content-center rounded-left" style="background-color: #f4f6f9;">
                        <div class="pt-3">
                            <div class="d-flex justify-content-center mt-4 mb-3">
                                <img src="<?= base_url() ?>/service/icon/<?= $database[0]['LOGO_SISTEM'] ?>" alt="" width="100" height="100">
                            </div>
                            <div class="d-flex justify-content-center">
                                <p class="mb-0" style="font-size: 25px;"><b>DISKOMINFO Kab. KEDIRI</b></p>
                            </div>
                            <div class="d-flex justify-content-center text-center">
                                <p><b><?= $database[0]['SINGKATAN'] ?> |</b> <?= $database[0]['NAMA_SISTEM'] ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 p-4 rounded-right">
                        <p class="login-box-msg"><b>Sign in</b> to start your session</p>

                        <form action="<?= base_url() ?>/lv" method="post">
                            <?= csrf_field(); ?>
                            <div class="input-group mb-3">
                                <input type="email" name="email" class="form-control" placeholder="Email" required>
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-envelope"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <input id="password" type="password" name="password" class="form-control" placeholder="Password" required>
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-8">
                                    <div class="icheck-primary">
                                        <input type="checkbox" onclick="myFunction()" id="remember">
                                        <label for="remember">
                                            Lihat Password
                                        </label>
                                    </div>
                                </div>
                                <!-- /.col -->
                            </div>
                            <div class="pt-3 text-right d-flex justify-content-end">
                                <!-- <a href="<?= base_url() ?>/" style="width: 100px;" type="submit" class="btn btn-primary">Kembali</a> -->
                                <button style="width: 100px;" type="submit" class="btn btn-primary">Sign In</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="<?= base_url() ?>/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="<?= base_url() ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- sweetalert2 -->
    <script src="<?= base_url() ?>/plugins/sweetalert2/sweetalert2.all.min.js"></script>
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="<?= base_url() ?>/plugins/icheck-bootstrap/icheck-bootstrap.min.css">

    <script>
        // Sweetaler2
        const flashData = $('.flash-data').data('flashdata');
        if (flashData) {
            if (flashData == "user_tidak_ada") {
                Swal.fire({
                    icon: 'error',
                    title: 'User tidak dikenali',
                    showConfirmButton: false,
                    timer: 2000
                })
            } else if (flashData == "tidak_ada_hak_akses") {
                Swal.fire({
                    icon: 'error',
                    title: 'User tidak memiliki hak akses sistem',
                    showConfirmButton: false,
                    timer: 2000
                })
            }
        }

        //lihat password
        function myFunction() {
            var x = document.getElementById("password");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }
    </script>
</body>

</html>