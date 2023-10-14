        <aside class="main-sidebar sidebar-dark-primary elevation-4">
          <a href="<?= base_url() ?>/ad" class="brand-link">
            <img src="<?= base_url() ?>/service/icon/<?= $database[20][0]['LOGO_SISTEM'] ?>" alt="AdminLTE Logo" class="brand-image img-circle elevation-3 bg-white" style="opacity: .8;width: 35px;height: 35px;margin-top: -1.5px;">
            <span class="brand-text font-weight-light" style="margin-left: 2px;"><b><?= $database[20][0]['SINGKATAN'] ?></b></span>
          </a>

          <div class="sidebar">
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
              <a id="fotoAdmin" href="<?= base_url() ?>/akvm?foto=<?= $database[0]['FOTO_KAWAN'] ?>">
                <div class="image">
                  <img src="<?= base_url() ?>/service/profil/<?= $database[0]['FOTO_KAWAN'] ?>" style="width: 35px;height: 35px;" class="img-circle elevation-2" alt="User Image">
                </div>
              </a>
              <div class="info">
                <a href="<?= base_url() ?>/p" class="d-block"><?= ucwords($database[0]['NAMA_KAWAN']) ?></a>
              </div>
            </div>

            <nav class="mt-2">
              <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                  <a href="<?= base_url() ?>/ad" class="nav-link <?php if ($menu_active == "dashboard") {
                                                                    echo "active";
                                                                  } ?>">
                    <i class="fas fa-th-large nav-icon"></i>
                    <p> Dashboard</p>
                  </a>
                </li>
                <li class="nav-item <?php if ($submenu_active == "tambah_mahasiswa" || $submenu_active == "data_mahasiswa") {
                                                                        echo "menu-open";
                                                                      } ?>">
                  <a href="#" class="nav-link <?php if ($menu_active == "mahasiswa") {
                                                echo "active";
                                              } ?>">
                    <i class="nav-icon fas fa-users"></i>
                    <p> Peserta<i class="right fas fa-angle-left"></i></p>
                  </a>
                  <ul class="nav nav-treeview">
                    <li class="nav-item">
                      <a href="<?= base_url() ?>/am" class="nav-link <?php if ($submenu_active == "data_mahasiswa") {
                                                                        echo "active";
                                                                      } ?>" style="padding-left: 40px;">
                        <i class="fas fa-table nav-icon"></i>
                        <p>Data Peserta</p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="<?= base_url() ?>/amt" class="nav-link <?php if ($submenu_active == "tambah_mahasiswa") {
                                                                        echo "active";
                                                                      } ?>" style="padding-left: 40px;">
                        <i class="fas fa-plus nav-icon"></i>
                        <p>Tambah Peserta</p>
                      </a>
                    </li>
                  </ul>
                </li>
                <li class="nav-item <?php if ($submenu_active == "tambah_kampus" || $submenu_active == "data_kampus") {
                                                                        echo "menu-open";
                                                                      } ?>">
                  <a href="#" class="nav-link <?php if ($menu_active == "kampus") {
                                                echo "active";
                                              } ?>">
                    <i class="nav-icon fas fa-graduation-cap"></i>
                    <p>Asal Peserta<i class="right fas fa-angle-left"></i></p>
                  </a>
                  <ul class="nav nav-treeview">
                    <li class="nav-item">
                      <a href="<?= base_url() ?>/ak" class="nav-link <?php if ($submenu_active == "data_kampus") {
                                                                        echo "active";
                                                                      } ?>" style="padding-left: 40px;">
                        <i class="fas fa-table nav-icon"></i>
                        <p>Data Asal Peserta</p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="<?= base_url() ?>/akt" class="nav-link <?php if ($submenu_active == "tambah_kampus") {
                                                                        echo "active";
                                                                      } ?>" style="padding-left: 40px;">
                        <i class="fas fa-plus nav-icon"></i>
                        <p>Tambah Asal Peserta</p>
                      </a>
                    </li>
                  </ul>
                </li>
                <li class="nav-item <?php if ($submenu_active == "tambah_tim" || $submenu_active == "data_tim") {
                                                                        echo "menu-open";
                                                                      } ?>">
                  <a href="#" class="nav-link <?php if ($menu_active == "tim") {
                                                echo "active";
                                              } ?>">
                    <i class="nav-icon fas fa-layer-group"></i>
                    <p>Tim Peserta<i class="right fas fa-angle-left"></i></p>
                  </a>
                  <ul class="nav nav-treeview">
                    <li class="nav-item">
                      <a href="<?= base_url() ?>/at" class="nav-link <?php if ($submenu_active == "data_tim") {
                                                                        echo "active";
                                                                      } ?>" style="padding-left: 40px;">
                        <i class="fas fa-table nav-icon"></i>
                        <p>Data Tim Peserta</p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="<?= base_url() ?>/att" class="nav-link <?php if ($submenu_active == "tambah_tim") {
                                                                        echo "active";
                                                                      } ?>" style="padding-left: 40px;">
                        <i class="fas fa-plus nav-icon"></i>
                        <p>Tambah Tim Peserta</p>
                      </a>
                    </li>
                  </ul>
                </li>
                <li class="nav-item <?php if ($submenu_active == "tambah_karyawan" || $submenu_active == "data_karyawan") {
                                                                        echo "menu-open";
                                                                      } ?>">
                  <a href="#" class="nav-link <?php if ($menu_active == "karyawan") {
                                                echo "active";
                                              } ?>">
                    <i class="nav-icon fas fa-users"></i>
                    <p>Karyawan<i class="right fas fa-angle-left"></i></p>
                  </a>
                  <ul class="nav nav-treeview">
                    <li class="nav-item">
                      <a href="<?= base_url() ?>/akr" class="nav-link <?php if ($submenu_active == "data_karyawan") {
                                                                        echo "active";
                                                                      } ?>" style="padding-left: 40px;">
                        <i class="fas fa-table nav-icon"></i>
                        <p>Data Karyawan</p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="<?= base_url() ?>/akrt" class="nav-link <?php if ($submenu_active == "tambah_karyawan") {
                                                                          echo "active";
                                                                        } ?>" style="padding-left: 40px;">
                        <i class="fas fa-plus nav-icon"></i>
                        <p>Tambah Karyawan</p>
                      </a>
                    </li>
                  </ul>
                </li>
                <li class="nav-item <?php if ($submenu_active == "tambah_jabatan" || $submenu_active == "data_jabatan") {
                                                                        echo "menu-open";
                                                                      } ?>">
                  <a href="#" class="nav-link <?php if ($menu_active == "jabatan") {
                                                echo "active";
                                              } ?>">
                    <i class="nav-icon far fa-star"></i>
                    <p>Jabatan<i class="right fas fa-angle-left"></i></p>
                  </a>
                  <ul class="nav nav-treeview">
                    <li class="nav-item">
                      <a href="<?= base_url() ?>/aj" class="nav-link <?php if ($submenu_active == "data_jabatan") {
                                                                        echo "active";
                                                                      } ?>" style="padding-left: 40px;">
                        <i class="fas fa-table nav-icon"></i>
                        <p>Data Jabatan</p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="<?= base_url() ?>/ajt" class="nav-link <?php if ($submenu_active == "tambah_jabatan") {
                                                                        echo "active";
                                                                      } ?>" style="padding-left: 40px;">
                        <i class="fas fa-plus nav-icon"></i>
                        <p>Tambah Jabatan</p>
                      </a>
                    </li>
                  </ul>
                </li>
                <li class="nav-item <?php if ($submenu_active == "tambah_libur" || $submenu_active == "data_libur") {
                                                                        echo "menu-open";
                                                                      } ?>">
                  <a href="#" class="nav-link <?php if ($menu_active == "libur") {
                                                echo "active";
                                              } ?>">
                    <i class="nav-icon fa fa-calendar"></i>
                    <p>Libur<i class="right fas fa-angle-left"></i></p>
                  </a>
                  <ul class="nav nav-treeview">
                    <li class="nav-item">
                      <a href="<?= base_url() ?>/alni" class="nav-link <?php if ($submenu_active == "data_libur") {
                                                                        echo "active";
                                                                      } ?>" style="padding-left: 40px;">
                        <i class="fas fa-table nav-icon"></i>
                        <p>Data Libur</p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="<?= base_url() ?>/alnt" class="nav-link <?php if ($submenu_active == "tambah_libur") {
                                                                        echo "active";
                                                                      } ?>" style="padding-left: 40px;">
                        <i class="fas fa-plus nav-icon"></i>
                        <p>Tambah Libur</p>
                      </a>
                    </li>
                  </ul>
                </li>
                <!-- <li class="nav-item <?php if ($submenu_active == "presensi" || $submenu_active == "libur") {
                                                                        echo "menu-open";
                                                                      } ?>">
                  <a href="#" class="nav-link <?php if ($menu_active == "konfigurasi") {
                                                                      echo "active";
                                                                    } ?>">
                    <i class="nav-icon fas fa-wrench"></i>
                    <p> Konfigurasi<i class="right fas fa-angle-left"></i></p>
                  </a>
                  <ul class="nav nav-treeview">
                    <li class="nav-item">
                      <a href="<?= base_url() ?>/akgip" class="nav-link <?php if ($submenu_active == "presensi") {
                                                                        echo "active";
                                                                      } ?>" style="padding-left: 40px;">
                        <i class="fa fa-cogs nav-icon"></i>
                        <p>Presensi</p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="<?= base_url() ?>/alni" class="nav-link <?php if ($submenu_active == "libur") {
                                                                        echo "active";
                                                                      } ?>" style="padding-left: 40px;">
                        <i class="fa fa-cogs nav-icon"></i>
                        <p>Libur Nasional</p>
                      </a>
                    </li>
                  </ul>
                </li> -->
                <li class="nav-item">
                  <a href="<?= base_url() ?>/akgip" class="nav-link <?php if ($menu_active == "konfigurasi") {
                                                                    echo "active";
                                                                  } ?>">
                    <i class="fas fas fa-wrench nav-icon"></i>
                    <p> Konfigurasi</p>
                  </a>
                </li>
              </ul>
            </nav>
          </div>
        </aside>

        <div class="content-wrapper">
          <section class="content-header">
            <div class="container-fluid">
              <div class="row mb-2">
                <div class="col-sm-6">
                  <?php if ($title == "Masa PKL Habis") { ?>
                    <h1><?= $title . " (" . $jumlah . ")" ?></h1>
                  <?php } else { ?>
                    <h1><?= $title ?></h1>
                  <?php } ?>
                </div>
                <div class="col-sm-6">
                  <?php if ($title == "Dashboard" || $title == "Profil" || $title == "Masa PKL Habis" || $title == "Pendaftaran" || $title == "Konfigurasi") { ?>
                    <ol class="breadcrumb float-sm-right">
                      <li class="breadcrumb-item active"><?= $title ?></li>
                    </ol>
                  <?php } else { ?>
                    <?php if($layout == "Edit Profil"){ ?>
                      <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active"><?= $layout ?></li>
                      </ol>
                    <?php }else{ ?>
                      <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><?= $layout ?></li>
                        <?php if($sub_layout != '-'){ ?>
                          <li class="breadcrumb-item active"><?= $sub_layout ?></li>
                        <?php } ?>
                      </ol>
                    <?php } ?>
                  <?php } ?>
                </div>
              </div>
            </div>
          </section>

          <section class="content">

            <div class="container-fluid">
              <div class="row">
                <div class="col-12">