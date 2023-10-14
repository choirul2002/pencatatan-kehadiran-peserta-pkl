<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Login');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
//$routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
//halaman umum (login)
$routes->get('/', 'Login::index');
$routes->get('/mt', 'Maintenance::index');
$routes->get('/mts', 'Maintenance::sistem_aktif');
$routes->post('/lv', 'Login::validasi');
$routes->get('/ld', 'Login::destroy');

// Start Admin Controller
// kelola dashboard
$routes->get('/ad', 'Admin\Dashboard::index');

//kelola mahasiswa
$routes->get('/am', 'Admin\Mahasiswa::index');
$routes->get('/amt', 'Admin\Mahasiswa::tambah');
$routes->post('/amts', 'Admin\Mahasiswa::simpan');
$routes->post('/amg', 'Admin\Mahasiswa::generate');
$routes->get('/amn', 'Admin\Mahasiswa::notifikasi');
$routes->get('/amna', 'Admin\Mahasiswa::nonAktif');
$routes->get('/amnap', 'Admin\Mahasiswa::nonAktif_person');
$routes->get('/amv', 'Admin\Mahasiswa::viewModal');
$routes->get('/ame', 'Admin\Mahasiswa::edit');
$routes->post('/amse', 'Admin\Mahasiswa::simpanedit');
$routes->get('/amvm', 'Admin\Mahasiswa::viewMahasiswa');
$routes->get('/amh', 'Admin\Mahasiswa::hapus');

//kelola kampus
$routes->get('/ak', 'Admin\Kampus::index');
$routes->get('/akt', 'Admin\Kampus::tambah');
$routes->post('/akts', 'Admin\Kampus::simpan');
$routes->post('/akg', 'Admin\Kampus::generate');
$routes->get('/ake', 'Admin\Kampus::edit');
$routes->post('/akse', 'Admin\Kampus::simpanedit');
$routes->get('/akh', 'Admin\Kampus::hapus');

//kelola tim mahasiswa
$routes->get('/at', 'Admin\TimMahasiswa::index');
$routes->get('/att', 'Admin\TimMahasiswa::tambah');
$routes->post('/atts', 'Admin\TimMahasiswa::simpan');
$routes->post('/atg', 'Admin\TimMahasiswa::generate');
$routes->get('/ate', 'Admin\TimMahasiswa::edit');
$routes->get('/atmvm', 'Admin\TimMahasiswa::viewMahasiswa');
$routes->post('/atse', 'Admin\TimMahasiswa::simpanedit');
$routes->get('/ath', 'Admin\TimMahasiswa::hapus');

//kelola karyawan
$routes->get('/akr', 'Admin\Karyawan::index');
$routes->get('/akrt', 'Admin\Karyawan::tambah');
$routes->post('/akrts', 'Admin\Karyawan::simpan');
$routes->post('/akrg', 'Admin\Karyawan::generate');
$routes->get('/akre', 'Admin\Karyawan::edit');
$routes->get('/akrh', 'Admin\Karyawan::hapus');
$routes->post('/akrse', 'Admin\Karyawan::simpanedit');
$routes->get('/akvm', 'Admin\Karyawan::viewKaryawan');

//kelola jabatan
$routes->get('/aj', 'Admin\Jabatan::index');
$routes->get('/ajt', 'Admin\Jabatan::tambah');
$routes->post('/ajts', 'Admin\Jabatan::simpan');
$routes->get('/ajte', 'Admin\Jabatan::edit');
$routes->post('/ajtse', 'Admin\Jabatan::simpanedit');
$routes->get('/ajth', 'Admin\Jabatan::hapus');

//kelola konfigurasi
$routes->get('/akgi', 'Admin\Konfigurasi::index');
$routes->post('/akgs', 'Admin\Konfigurasi::simpan');
$routes->get('/akgip', 'Admin\Konfpresensi::index');
$routes->post('/akgsp', 'Admin\Konfpresensi::simpan');

//kelola profil
$routes->get('/p', 'Admin\Profil::index');
$routes->post('/ps', 'Admin\Profil::simpan');

//finish Admin Controller

//Karyawan Controller
//Start Karyawan Controller

//kelola dashboard
$routes->get('/kd', 'Karyawan\Dashboard::index');

//kelola profil
$routes->get('/kp', 'Karyawan\Profil::index');
$routes->post('/ks', 'Karyawan\Profil::simpan');

//kelola tim mahasiswa
$routes->get('/kt', 'Karyawan\TimMahasiswa::index');
$routes->post('/ktg', 'Karyawan\TimMahasiswa::generate');
$routes->get('/ktvm', 'Karyawan\TimMahasiswa::viewMahasiswa');
$routes->get('/ktvfi', 'Karyawan\TimMahasiswa::viewFotoIzin');
$routes->get('/ktvk', 'Karyawan\TimMahasiswa::viewKehadiran');
$routes->get('/ktvkm', 'Karyawan\TimMahasiswa::viewKampus');
$routes->get('/ktvp', 'Karyawan\TimMahasiswa::viewPembimbing');
$routes->get('/ktvmhs', 'Karyawan\TimMahasiswa::viewMhs');

//kelola absensi
$routes->get('/ka', 'Karyawan\Absensi::index');
$routes->post('/kag', 'Karyawan\Absensi::generate');
$routes->get('/kn', 'Karyawan\Absensi::notifikasi');
$routes->get('/kav', 'Karyawan\Absensi::viewModal');
$routes->get('/kavc', 'Karyawan\Absensi::viewComment');
$routes->get('/kava', 'Karyawan\Absensi::viewAbsen');
$routes->get('/kavcp', 'Karyawan\Absensi::viewCommentPerson');
$routes->get('/kavs', 'Karyawan\Absensi::viewSurat');
$routes->get('/kavsp', 'Karyawan\Absensi::viewSuratPerson');
$routes->get('/kavas', 'Karyawan\Absensi::viewAbsenSurat');
$routes->get('/kavaf', 'Karyawan\Absensi::viewAbsenFoto');
$routes->get('/kakc', 'Karyawan\Absensi::kirimComment');
$routes->get('/kakcp', 'Karyawan\Absensi::kirimCommentPerson');
$routes->get('/kaa', 'Karyawan\Absensi::approve');
$routes->get('/kaap', 'Karyawan\Absensi::approve_person');
$routes->get('/kad', 'Karyawan\Absensi::disapprove');
$routes->get('/kavm', 'Karyawan\Absensi::viewMahasiswa');

//kelola proyek
$routes->get('/kapi', 'Karyawan\Proyek::index');
$routes->get('/kapv', 'Karyawan\Proyek::view');
/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
