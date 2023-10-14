</div>
</div>
</div>
</section>
</div>

<!-- View kampus -->
<div class="modal fade" id="modal-pembimbing">
  <div class="modal-dialog modal-dialog-centered" id="modalPembimbing"></div>
</div>

<!-- View kampus -->
<div class="modal fade" id="modal-peserta">
  <div class="modal-dialog modal-dialog-centered" id="modalPeserta"></div>
</div>

<!-- View Notifikasi -->
<div class="modal fade" id="modal-default">
  <div class="modal-dialog modal-dialog-centered" id="modalView"></div>
</div>

<!-- View Notifikasi -->
<div class="modal fade" id="modal-publish">
  <div class="modal-dialog modal-dialog-centered" id="modalViewPublish"></div>
</div>

<!-- View kampus -->
<div class="modal fade" id="modal-kampus">
  <div class="modal-dialog modal-dialog-centered" id="modalKampus"></div>
</div>

<!-- View Notifikasi -->
<div class="modal fade" id="modal-default-notifikasi">
  <div class="modal-dialog modal-dialog-centered" id="modalViewNotifikasi"></div>
</div>

<!-- View foto Person -->
<div class="modal fade" id="modal-foto-person">
  <div class="modal-dialog modal-dialog-centered" id="modalFotoPerson"></div>
</div>

<!-- View Foto Mahasiswa -->
<div class="modal fade" id="modal-mahasiswa">
  <div class="modal-dialog modal-dialog-centered" id="modalMahasiswa"></div>
</div>

<!-- View Foto Mahasiswa -->
<div class="modal fade" id="modal-admin">
  <div class="modal-dialog modal-dialog-centered" id="modalAdmin"></div>
</div>

<footer class="main-footer">
  <div class="float-right d-none d-sm-block">
    <b>Version : </b> <?= $database[20][0]['VERSI'] ?>
  </div>
  <strong>Copyright &copy; 2023 - <?= $database[20][0]['SINGKATAN'] ?></strong> | <?= $database[20][0]['NAMA_SISTEM'] ?>
</footer>

<aside class="control-sidebar control-sidebar-dark"></aside>
</div>

<!-- jQuery -->
<script src="<?= base_url() ?>/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?= base_url() ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- overlayScrollbars -->
<script src="<?= base_url() ?>/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="<?= base_url() ?>/dist/js/adminlte.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="<?= base_url() ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url() ?>/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url() ?>/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?= base_url() ?>/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url() ?>/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="<?= base_url() ?>/plugins/jszip/jszip.min.js"></script>
<script src="<?= base_url() ?>/plugins/pdfmake/pdfmake.min.js"></script>
<script src="<?= base_url() ?>/plugins/pdfmake/vfs_fonts.js"></script>
<script src="<?= base_url() ?>/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="<?= base_url() ?>/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="<?= base_url() ?>/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- sweetalert2 -->
<script src="<?= base_url() ?>/plugins/sweetalert2/sweetalert2.all.min.js"></script>
<script src="<?= base_url() ?>/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
<script src="<?= base_url() ?>/plugins/moment/moment.min.js"></script>
<script src="<?= base_url() ?>/plugins/inputmask/jquery.inputmask.min.js"></script>
<script src="<?= base_url() ?>/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- bs-custom-file-input -->
<script src="<?= base_url() ?>/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<!-- Select2 -->
<script src="<?= base_url() ?>/plugins/select2/js/select2.full.min.js"></script>
<!-- chart online -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<!-- icheck bootstrap -->
<link rel="stylesheet" href="<?= base_url() ?>/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
<!-- Bootstrap Switch -->
<script src="<?= base_url() ?>/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<!-- Jquery website -->

<script>
  document.getElementById("printPDF").addEventListener("submit", function(event) {
    var buttonValue = event.submitter.value;
    if (buttonValue === "pdf") {
      event.target.removeAttribute("target");
    } else if (buttonValue === "print") {
      event.target.setAttribute("target", "_blank");
    }
  });
</script>


<script>
    $("a#tdt").click(function(e) {
        e.preventDefault();
        const href = $(this).attr("href");
        Swal.fire({
            title: 'Apakah anda yakin ?',
            text: "Tidak menerima pendaftaran PKL ini!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yakin'
        }).then((result) => {
            if (result.value) {
                document.location.href = href;
            }
        })
    });
</script>

<script>
  $(function() {
    $('.select2').select2()
    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
  });

  $(function() {
    //Initialize switch
    $("input[data-bootstrap-switch]").each(function(){
      $(this).bootstrapSwitch('state', $(this).prop('checked'));
    })
  });

  // DataTables table
  $(function() {
    $('#table').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });

  // Sweetaler2
  const flashData = $('.flash-data').data('flashdata');
  if (flashData) {
    if (flashData == "berhasil") {
      Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        showConfirmButton: false,
        timer: 2000
      })
    } else if (flashData == "berhasil_login") {
      Swal.fire({
        icon: 'success',
        title: 'Login Berhasil',
        showConfirmButton: false,
        timer: 2000
      })
    } else if(flashData == "sistem_aktif"){
      Swal.fire({
        icon: 'success',
        title: 'Sistem Di Aktifkan',
        showConfirmButton: false,
        timer: 2000
      })
    } else if(flashData == "testimoni"){
      Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        showConfirmButton: false,
        timer: 2000
      })
    } else if(flashData == "terelasi"){
      Swal.fire({
        icon: 'error',
        title: 'Data terelasi',
        showConfirmButton: false,
        timer: 2000
      })
    } else if(flashData == "email"){
      Swal.fire({
        icon: 'error',
        title: 'Email sudah digunakan',
        showConfirmButton: false,
        timer: 2000
      })
    } else if(flashData == "aktif"){
      Swal.fire({
        icon: 'error',
        title: 'User sedang aktif',
        showConfirmButton: false,
        timer: 2000
      })
    } else if(flashData == "kosong"){
      Swal.fire({
        icon: 'error',
        title: 'Anggota kosong',
        showConfirmButton: false,
        timer: 2000
      })
    } else if(flashData == "nip"){
      Swal.fire({
        icon: 'error',
        title: 'NIP sudah digunakan',
        showConfirmButton: false,
        timer: 2000
      })
    } else if(flashData == "cek"){
      Swal.fire({
        icon: 'error',
        title: 'Tanggal sudah ada acara',
        showConfirmButton: false,
        timer: 2000
      })
    } else if(flashData == "masih aktif"){
      Swal.fire({
        icon: 'error',
        title: 'Tim PKL masih aktif',
        showConfirmButton: false,
        timer: 2000
      })
    } else if(flashData == "tidak aktif"){
      Swal.fire({
        icon: 'error',
        title: 'Data tidak bisa dihapus',
        showConfirmButton: false,
        timer: 2000
      })
    } else if(flashData == "data kosong"){
      Swal.fire({
        icon: 'error',
        title: 'Data kosong',
        showConfirmButton: false,
        timer: 2000
      })
    } else if(flashData == "mulaiselesai"){
      Swal.fire({
        icon: 'error',
        title: 'Tanggal mulai lebih besar dari tanggal selesai',
        showConfirmButton: false,
        timer: 2000
      })
    } else if(flashData == "datawaktu"){
      Swal.fire({
        icon: 'error',
        title: 'Data waktu masih salah',
        showConfirmButton: false,
        timer: 2000
      })
    }
    
  }

  //sweetalert log out
  $("a#logout").click(function(e) {
    e.preventDefault();
    const href = $(this).attr("href");
    Swal.fire({
      title: 'Apakah anda yakin ?',
      text: "Ingin keluar dari Sistem Kelola Data!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yakin'
    }).then((result) => {
      if (result.value) {
        document.location.href = href;
      }
    })
  });

  //sweetalert wa
  $("a#hp").click(function(e) {
    e.preventDefault();
    const href = $(this).attr("href");
    let timerInterval
    Swal.fire({
      title: 'Whatshapp system',
      html: 'Open in <strong></strong> seconds.',
      showConfirmButton: false,
      timer: 2000,
      onBeforeOpen: () => {
        Swal.showLoading()
        timerInterval = setInterval(() => {
          Swal.getContent().querySelector('strong')
            .textContent = Swal.getTimerLeft()
        }, 100)
      },
      onClose: () => {
        clearInterval(timerInterval)
      }
    }).then((result) => {
      if (result.dismiss === Swal.DismissReason.timer) {
        window.open(href, "_blank");
      }
    })
  });

  //sweetalert wa
  $("a#rekomendasi").click(function(e) {
    e.preventDefault();
    const href = $(this).attr("href");
    let timerInterval
    Swal.fire({
      title: 'Loading system',
      html: 'Open in <strong></strong> seconds.',
      showConfirmButton: false,
      timer: 2000,
      onBeforeOpen: () => {
        Swal.showLoading()
        timerInterval = setInterval(() => {
          Swal.getContent().querySelector('strong')
            .textContent = Swal.getTimerLeft()
        }, 100)
      },
      onClose: () => {
        clearInterval(timerInterval)
      }
    }).then((result) => {
      if (result.dismiss === Swal.DismissReason.timer) {
        window.open(href);
      }
    })
  });

  //sweetalert wa
  $("a#website").click(function(e) {
    e.preventDefault();
    const href = $(this).attr("href");
    let timerInterval
    Swal.fire({
      title: 'Loading system',
      html: 'Open in <strong></strong> seconds.',
      showConfirmButton: false,
      timer: 2000,
      onBeforeOpen: () => {
        Swal.showLoading()
        timerInterval = setInterval(() => {
          Swal.getContent().querySelector('strong')
            .textContent = Swal.getTimerLeft()
        }, 100)
      },
      onClose: () => {
        clearInterval(timerInterval)
      }
    }).then((result) => {
      if (result.dismiss === Swal.DismissReason.timer) {
        window.open(href);
      }
    })
  });

  //sweetalert hapus
  $("a#hapus").click(function(e) {
    e.preventDefault();
    const href = $(this).attr("href");
    Swal.fire({
      title: 'Apakah anda yakin ?',
      text: "Ingin menghapus data ini!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yakin'
    }).then((result) => {
      if (result.value) {
        document.location.href = href;
      }
    })
  });

    //swet alert konfirmasi
    $("a#notinonaktif").click(function(e) {
        e.preventDefault();
        const href = $(this).attr("href");
        Swal.fire({
            title: 'Apakah anda yakin ?',
            text: "Ingin menonaktifkan tim ini!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yakin'
        }).then((result) => {
            if (result.value) {
                document.location.href = href;
            }
        })
    });

  //sweetalert non aktif
  $("a#nonaktif").click(function(e) {
    e.preventDefault();
    const href = $(this).attr("href");
    Swal.fire({
      title: 'Apakah anda yakin ?',
      text: "Ingin menonaktifkan mahasiswa ini!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yakin'
    }).then((result) => {
      if (result.value) {
        document.location.href = href;
      }
    })
  });

  //input tanggat dialog
  $('#reservationdate').datetimepicker({
    format: 'YYYY-MM-DD'
  });

  //input tanggat dialog
  $('#reservationdate1').datetimepicker({
    format: 'YYYY-MM-DD'
  });

  // view foto mahasiswa
  $("a#foto").click(function(e) {
    e.preventDefault();
    $('#modal-mahasiswa').modal("show");
    $('#modalMahasiswa').load($(this).attr("href"));
  });

    // view foto mahasiswa
    $("a#fotoAdmin").click(function(e) {
    e.preventDefault();
    $('#modal-admin').modal("show");
    $('#modalAdmin').load($(this).attr("href"));
  });

    //view pembimbing
  $("a#pembimbing").click(function(e) {
    e.preventDefault();
    $('#modal-pembimbing').modal("show");
    $('#modalPembimbing').load($(this).attr("href"));
  });

    //view pembimbing
  $("a#pesertaMahasiswa").click(function(e) {
    e.preventDefault();
    $('#modal-peserta').modal("show");
    $('#modalPeserta').load($(this).attr("href"));
  });

    //view kampus
  $("a#kampus").click(function(e) {
    e.preventDefault();
    $('#modal-kampus').modal("show");
    $('#modalKampus').load($(this).attr("href"));
  });

  // view notifikasi
  $("a#view").click(function(e) {
    e.preventDefault();
    $('#modal-default').modal("show");
    $('#modalView').load($(this).attr("href"));
  });

  // view notifikasi
  $("a#publish").click(function(e) {
    e.preventDefault();
    $('#modal-publish').modal("show");
    $('#modalViewPublish').load($(this).attr("href"));
  });

  // view notifikasi
  $("a#viewPendaftaran").click(function(e) {
    e.preventDefault();
    $('#modal-default-notifikasi').modal("show");
    $('#modalViewNotifikasi').load($(this).attr("href"));
  });

  //file input
  $(function() {
    bsCustomFileInput.init();
  });

  //menampilkan file foto
  var loadFile = function(event) {
    const data = document.getElementById("pilihFoto").files[0].name;
    var tempatFoto = document.getElementById('tampilFoto');

    if (tempatFoto) {
      tempatFoto.src = URL.createObjectURL(event.target.files[0]);
      tempatFoto.onload = function() {
        URL.revokeObjectURL(tempatFoto.src)
      }
    }
  };

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

<script>
    function validateFormProfil() {
      var inputField4 = document.getElementById("inputemail");
      var inputField5 = document.getElementById("inputwhatshapp");
      var regex4 = /^[A-Za-z0-9._%+-]+@(gmail|yahoo)\.com$/;
      var regex5 = /^08\d{9,}$/;

      if (!regex4.test(inputField4.value)) {
        Swal.fire({
          icon: 'error',
          title: 'Format email salah',
          showConfirmButton: false,
          timer: 2000
        })
        return false; // Format sesuai, form dapat di-submit
      }

      if (!regex5.test(inputField5.value)) {
        Swal.fire({
          icon: 'error',
          title: 'Format whatshap salah',
          showConfirmButton: false,
          timer: 2000
        })
        return false;
      }

      return true;
    }
  </script>

<script>
    function validateFormPeserta() {
      var inputField4 = document.getElementById("inputemail");
      var inputField5 = document.getElementById("inputwhatshapp");
      var regex4 = /^[A-Za-z0-9._%+-]+@(gmail|yahoo)\.com$/;
      var regex5 = /^08\d{9,}$/;

      if (!regex4.test(inputField4.value)) {
        Swal.fire({
          icon: 'error',
          title: 'Format email salah',
          showConfirmButton: false,
          timer: 2000
        })
        return false; // Format sesuai, form dapat di-submit
      }

      if (!regex5.test(inputField5.value)) {
        Swal.fire({
          icon: 'error',
          title: 'Format whatshap salah',
          showConfirmButton: false,
          timer: 2000
        })
        return false;
      }

      return true;
    }
  </script>

<script>
    function validateFormAsalPeserta() {
      var inputField3 = document.getElementById("inputwebsite");

      var regex3 = /^https:\/\/.*$/;

      if (!regex3.test(inputField3.value)) {
        Swal.fire({
          icon: 'error',
          title: 'Format website salah',
          showConfirmButton: false,
          timer: 2000
        })
        return false;
      }

      return true;
    }
  </script>

<script>
    function validateFormTimPeserta() {
      var inputField = document.getElementById("inputtanggalmulai");
      var inputField2 = document.getElementById("inputtanggalselesai");

      var regex6 = /^\d{4}-\d{2}-\d{2}$/;

      if (!regex6.test(inputField.value)) {
          Swal.fire({
            icon: 'error',
            title: 'Format tanggal mulai salah',
            showConfirmButton: false,
            timer: 2000
          })
          return false; 
        }

        if (!regex6.test(inputField2.value)) {
          Swal.fire({
            icon: 'error',
            title: 'Format tanggal selesai salah',
            showConfirmButton: false,
            timer: 2000
          });
          return false; 
        }

      return true;
    }
  </script>

<script>
    function validateFormLiburNasional() {
      var inputField = document.getElementById("inputtanggal");

      var regex6 = /^\d{4}-\d{2}-\d{2}$/;

      if (!regex6.test(inputField.value)) {
          Swal.fire({
            icon: 'error',
            title: 'Format tanggal salah',
            showConfirmButton: false,
            timer: 2000
          })
          return false; 
        }

      return true;
    }
  </script>

<script>
    function validateFormKaryawan() {
      var inputField4 = document.getElementById("inputemail");
      var inputField5 = document.getElementById("inputwhatshapp");

      var regex4 = /^[A-Za-z0-9._%+-]+@(gmail|yahoo)\.com$/;
      var regex5 = /^08\d{9,}$/;

      if (!regex4.test(inputField4.value)) {
        Swal.fire({
          icon: 'error',
          title: 'Format email salah',
          showConfirmButton: false,
          timer: 2000
        })
        return false; // Format sesuai, form dapat di-submit
      }

      if (!regex5.test(inputField5.value)) {
        Swal.fire({
          icon: 'error',
          title: 'Format whatshap salah',
          showConfirmButton: false,
          timer: 2000
        })
        return false;
      }

      return true;
    }
  </script>

<script>
    function validateFormPresensi() {
      var inputField = document.getElementById("inputsekamin1");
      var inputField2 = document.getElementById("inputsekamin2");
      var inputField3 = document.getElementById("inputjumin1");
      var inputField4 = document.getElementById("inputjumin2");
      var inputField5 = document.getElementById("inputsekamout");
      var inputField6 = document.getElementById("inputjumout");
      var inputField7 = document.getElementById("inputlatitude");
      var inputField8 = document.getElementById("inputlongitude");
      var inputField9 = document.getElementById("inputradius");

      var timeRegex = /^([01]\d|2[0-3]):([0-5]\d):([0-5]\d)$/; 
      var regex = /^\d+f$/;
      var regex2 = /^-?\d+(\.\d+)?$/; 

      if (!regex2.test(inputField7.value)) {
        Swal.fire({
          icon: 'error',
          title: 'Format latitude salah',
          showConfirmButton: false,
          timer: 2000
        })
        return false;
      }

      if (!regex2.test(inputField8.value)) {
        Swal.fire({
          icon: 'error',
          title: 'Format longitude salah',
          showConfirmButton: false,
          timer: 2000
        })
        return false;
      }

      if (!regex.test(inputField9.value)) {
        Swal.fire({
          icon: 'error',
          title: 'Format radius salah',
          showConfirmButton: false,
          timer: 2000
        })
        return false;
      }

      if (!timeRegex.test(inputField.value)) {
        Swal.fire({
          icon: 'error',
          title: 'Format waktu senin - kamis in salah',
          showConfirmButton: false,
          timer: 2000
        })
        return false;
      }

      if (!timeRegex.test(inputField2.value)) {
        Swal.fire({
          icon: 'error',
          title: 'Format waktu senin - kamis in salah',
          showConfirmButton: false,
          timer: 2000
        })
        return false;
      }

      if (!timeRegex.test(inputField3.value)) {
        Swal.fire({
          icon: 'error',
          title: 'Format waktu jumat in salah',
          showConfirmButton: false,
          timer: 2000
        })
        return false;
      }

      if (!timeRegex.test(inputField4.value)) {
        Swal.fire({
          icon: 'error',
          title: 'Format waktu jumat in salah',
          showConfirmButton: false,
          timer: 2000
        })
        return false;
      }

      if (!timeRegex.test(inputField5.value)) {
        Swal.fire({
          icon: 'error',
          title: 'Format waktu senin - kamis out salah',
          showConfirmButton: false,
          timer: 2000
        })
        return false;
      }

      if (!timeRegex.test(inputField6.value)) {
        Swal.fire({
          icon: 'error',
          title: 'Format waktu jumat out salah',
          showConfirmButton: false,
          timer: 2000
        })
        return false;
      }

      return true;
    }
  </script>
</body>

</html>