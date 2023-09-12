<?php

use Dompdf\Dompdf;
use Dompdf\Options;

require 'dompdf/autoload.php';
$dompdf = new Dompdf();

$DB_NAME = "simaptapkl";
$DB_USER = "root";
$DB_PASS = "";
$DB_SERVER_LOC = "localhost";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = mysqli_connect($DB_SERVER_LOC, $DB_USER, $DB_PASS, $DB_NAME);

    $respon = array();
    $pilihan = $_POST['pilihan'];
    $akun = $_POST['id'];
    date_default_timezone_set("Asia/Jakarta");
    date_default_timezone_get();

    switch ($pilihan) {
        case "rekapKehadiranPembimbing":
            $mhs = $_POST['idMhs'];
            $akunMhs = "SELECT*FROM tabel_peserta WHERE KD_PST = '$mhs'";
            $resultAkunMhs = mysqli_query($conn, $akunMhs);
            if(mysqli_num_rows($resultAkunMhs) > 0){
                $dataAkunMhs = mysqli_fetch_assoc($resultAkunMhs);
                $kodeAkunMahasiswa = $dataAkunMhs['KD_AKUN'];
                
                $sqlAkun = "SELECT*FROM tabel_peserta, tabel_dtl_tim_peserta, tabel_tim_peserta, tabel_asal, tabel_karyawan
                WHERE tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST
                AND tabel_dtl_tim_peserta.KD_TIM = tabel_tim_peserta.KD_TIM
                AND tabel_tim_peserta.KD_KAWAN = tabel_karyawan.KD_KAWAN
                AND tabel_tim_peserta.KD_ASAL = tabel_asal.KD_ASAL
                AND tabel_peserta.KD_AKUN = '$kodeAkunMahasiswa'";
                $resultAkun = mysqli_query($conn, $sqlAkun);
                if(mysqli_num_rows($resultAkun) > 0){
                    $dataAkun = mysqli_fetch_assoc($resultAkun);
                    $kodeMahasiswa = $dataAkun['KD_PST'];
                    $namaMahasiswa = ucwords($dataAkun['NAMA_PST']);
                    $namaKampus = ucwords($dataAkun['NAMA_ASAL']);
                    $namaKawan = ucwords($dataAkun['NAMA_KAWAN']);
                    $namaFile = "Daftar-rekap-kehadiran-". $kodeMahasiswa . ".pdf";
                    $tanggalMulai = $dataAkun['TGL_MULAI_TIM'];
                    $tanggalSelesai = $dataAkun['TGL_SELESAI_TIM'];

                    $nip = "";
                    if($dataAkun['NIP_KAWAN']){
                        $nip = $dataAkun['NIP_KAWAN'];
                    }else{
                        $nip = "-";
                    }
        
                    $tanggalsekarang = date("Y-m-d");
                    $mulaisekarang = new DateTime($tanggalsekarang);
                    $tanggalsekarang =  tgl_indo($tanggalsekarang);
    
                    $mulai = new DateTime($tanggalMulai);
                    $hari_masuk = $mulai->format('D');
                    $tanggalMulai = hari($hari_masuk) . ", " . tgl_indo($tanggalMulai);
    
                    $keluar = new DateTime($tanggalSelesai);
                    $hari_keluar = $keluar->format('D');
                    $tanggalSelesai = hari($hari_keluar) . ", " . tgl_indo($tanggalSelesai);
    
                    $masaPKL = $tanggalMulai. ' - '.$tanggalSelesai;
    
                    $konfigurasi = "SELECT*FROM tabel_libur_nasional";
                    $resultKonfigurasi = mysqli_query($conn, $konfigurasi);
    
                    $libur_nasional = array();
                    if (mysqli_num_rows($resultKonfigurasi) > 0) {
                        while ($frm = mysqli_fetch_assoc($resultKonfigurasi)) {
                            array_push($libur_nasional, $frm);
                        }
                    }
    
                    $queryabsen = "SELECT*FROM tabel_absensi WHERE KD_PST = '$kodeMahasiswa' AND STATUS_SURAT != 'disapprove' AND STATUS_SURAT != 'waiting' ORDER BY TGL ASC";
                    $resultabsen = mysqli_query($conn, $queryabsen);
    
                    $absen = array();
                    if (mysqli_num_rows($resultabsen) > 0) {
                        while ($frm = mysqli_fetch_assoc($resultabsen)) {
                            array_push($absen, $frm);
                        }
                    }

                    date_default_timezone_set("Asia/Jakarta");
                    date_default_timezone_get();
    
                    $alpa = 0;
                    $izin = 0;
                    $hadir = 0;
    
                    $html = '<!DOCTYPE html>
                    <html lang="en">
                    <head>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <style>
                            #table1 {
                                border-collapse: collapse;
                                width: 100%;
                            }
                    
                            #table1 th, #table1 td {
                                padding: 8px;
                                text-align: left;
                                border-bottom: 1px solid #ddd;
                            }
                            
                            @media print {
                                footer {page-break-after: always;}
                            }
                    
                            @page { size: portrait; }
                        </style>
                    </head>
                    <body>
                        <table id="table1">
                            <tbody>
                                <tr style="font-size: 12px;">
                                    <th style="text-align: center;" width="20%"><img src="http://192.168.43.57/simaptapkl/public/service/icon/logoKominfo.png" alt="Deskripsi gambar" width="100" height="100"></th>
                                    <td style="text-align: center;"><P style="margin-top:0px;margin-bottom:0px;font-size: 16px;font-weight: bold;">DINAS KOMUNIKASI DAN INFORMATIKA</P><p style="margin-top:0px;font-size: 16px;font-weight: bold;">KABUPATEN KEDIRI</p><p style="margin-top:0px;margin-bottom:0px;font-size: 10px;">Jl. Sekartaji No. 2 Doko, Ngasem, Kediri, No. Telepon	:	 (0354) 682152, 696714, No. Fax	:	 (0354) 692279, Website	:	 https://diskominfo.kedirikab.go.id, Email	:	 diskominfo[at]kedirikab.go.id</p></td>
                                    <th style="text-align: center;" width="20%"></th>
                                </tr>
                            </tbody>
                        </table>
                        <hr style="border-width: 2px">
                        <div style="text-align: center;">
                            <p style="font-weight: bold;">REKAP KEHADIRAN PESERTA PKL</p>
                        </div>
                        <table>
                            <tbody>
                                <tr style="font-size: 14px;">
                                    <td width="15%"><p style="margin-top: 0px;margin-bottom: 5px;padding-right:20px;"><b>Nama Peserta</b></p></td>
                                    <td width="2%" style="padding-right:10px;">:</td>
                                    <td>'.$namaMahasiswa.'</td>
                                </tr>
                                <tr style="font-size: 14px;">
                                    <td width="15%"><p style="margin-top: 5px;margin-bottom: 5px;"><b>Pembimbing</b></p></td>
                                    <td width="2%">:</td>
                                    <td>'.$namaKawan.'</td>
                                </tr>
                                <tr style="font-size: 14px;">
                                    <td width="15%"><p style="margin-top: 5px;margin-bottom: 0px;"><b>Asal</b></p></td>
                                    <td width="2%">:</td>
                                    <td>'.$namaKampus.'</td>
                                </tr>
                                <tr style="font-size: 14px;">
                                    <td width="15%"><p style="margin-top: 5px;margin-bottom: 0px;"><b>Masa PKL</b></p></td>
                                    <td width="2%">:</td>
                                    <td>'.$masaPKL.'</td>
                                </tr>
                            </tbody>
                        </table>
                        <div style="padding-top:20px;"></div>
                        <table border="1" style="font-family: arial, sans-serif;border-collapse: collapse;width: 100%;">
                            <thead>
                                <tr style="font-size: 12px;">
                                    <th style="text-align: center;border: 1px black solid;padding: 8px;" width="5%">No</th>
                                    <th style="text-align: center;border: 1px black solid;padding: 8px;">Tanggal</th>
                                    <th style="text-align: center;border: 1px black solid;padding: 8px; width: 15%;">Check In</th>
                                    <th style="text-align: center;border: 1px black solid;padding: 8px; width: 15%;">Check Out</th>
                                    <th style="text-align: center;border: 1px black solid;padding: 8px; width: 20%;">Kehadiran</th>
                                    <th style="text-align: center;border: 1px black solid;padding: 8px; width: 12%;">Status</th>
                                </tr>
                            </thead>
                            <tbody>';
                                $mulai = new DateTime($dataAkun['TGL_MULAI_TIM']);
                                $selesai = new DateTime($dataAkun['TGL_SELESAI_TIM']);
                                $selisih = $mulai->diff($selesai)->days;
                    
                                $no = 1;
                                for ($i = 0; $i <= $selisih; $i++) {
                                    $tanggal = $mulai->format('Y-m-d');
                                    $check_in = '';
                                    $check_out = '';
                                    $kehadiran = '';
                                    $status = '';
    
                                    if(count($absen) > 0){
                                        foreach($absen as $data){
                                            if($data['TGL'] == $tanggal){
                                                if($data['STATUS'] == 'hadir'){
                                                    $check_in = $data['CHECK_IN'];
                                                    $check_out = $data['CHECK_OUT'];
                                                    $kehadiran = ucwords($data['KEHADIRAN']);
                                                    $status = ucwords($data['STATUS']);
                                                    $hadir = $hadir+1;
                                                }else{
                                                    $check_in = "-";
                                                    $check_out = "-";
                                                    $kehadiran = "-";
                                                    $status = ucwords($data['STATUS']);
                                                    $izin = $izin+1;
                                                }
                                            }else{
                                                if (date('N', strtotime($tanggal)) >= 6) {
                                                    $status = 'Libur Kerja';
                                                }
                                
                                                foreach ($libur_nasional as $libur) {
                                                    if ($tanggal == $libur['TANGGAL_LBR']) {
                                                        $status = 'Libur Nasional';
                                                        break;
                                                    }
                                                }
        
                                                if($status == "Libur Kerja"){
                                                    $check_in = "-";
                                                    $check_out = "-";
                                                    $kehadiran = "-";
                                                }else if($status == "Libur Nasional"){
                                                    $check_in = "-";
                                                    $check_out = "-";
                                                    $kehadiran = "-";
                                                }else if($status == ""){
                                                    if ($mulai < new DateTime(date("Y-m-d"))) {
                                                        $status = "Tidak Hadir";
                                                        $check_in = "-";
                                                        $check_out = "-";
                                                        $kehadiran = "-";
                                                        $alpa = $alpa + 1;
                                                    }
                                                }
                                            }
                                        }
                                    }else{
                                        if (date('N', strtotime($tanggal)) >= 6) {
                                            $status = 'Libur Kerja';
                                        }
                        
                                        foreach ($libur_nasional as $libur) {
                                            if ($tanggal == $libur['TANGGAL_LBR']) {
                                                $status = 'Libur Nasional';
                                                break;
                                            }
                                        }
    
                                        if($status == "Libur Kerja"){
                                            $check_in = "-";
                                            $check_out = "-";
                                            $kehadiran = "-";
                                        }else if($status == "Libur Nasional"){
                                            $check_in = "-";
                                            $check_out = "-";
                                            $kehadiran = "-";
                                        }else if($status == ""){
                                            if ($mulai < new DateTime(date("Y-m-d"))) {
                                                $status = "Tidak Hadir";
                                                $check_in = "-";
                                                $check_out = "-";
                                                $kehadiran = "-";
                                                $alpa = $alpa + 1;
                                            }
                                        }
                                    }
    
                    
                                    $html .= '<tr style="font-size: 12px;">
                                        <td style="border: 1px black solid;text-align: center;padding: 8px;">'.$no.'.</td>
                                        <td width="12%" style="border: 1px black solid;text-align: center;padding: 8px;">'.$tanggal.'</td>
                                        <td width="15%" style="border: 1px black solid;text-align: center;padding: 8px;">'.$check_in.'</td>
                                        <td width="15%" style="border: 1px black solid;text-align: center;padding: 8px;">'.$check_out.'</td>
                                        <td width="10%" style="border: 1px black solid;text-align: center;padding: 8px;">'.$kehadiran.'</td>
                                        <td width="10%" style="border: 1px black solid;text-align: center;padding: 8px;';
                                        if ($status == 'Libur Nasional' || $status == 'Libur Kerja') {
                                            $html .= 'font-weight: bold;';
                                        }
                                        $html .= '">'.$status.'</td>
                                    </tr>';
                                    $mulai->add(new DateInterval('P1D'));
                                    $no++;
                                }
                            $html .= '</tbody>
                        </table>
                        <div style="padding-top:20px;padding-bottom:20px;"></div>
                        <table border="1" style="font-family: arial, sans-serif;border-collapse: collapse;width: 100%;">
                            <thead>
                                <tr style="font-size: 12px;">
                                    <th style="text-align: center;border: 1px black solid;padding: 8px;width:6.3%;">No</th>
                                    <th style="text-align: center;border: 1px black solid;padding: 8px;width:15.2%;">Tanggal</th>
                                    <th style="text-align: center;border: 1px black solid;padding: 8px;">Kegiatan</th>
                                    <th style="text-align: center;border: 1px black solid;padding: 8px;width: 15%">Status</th>
                                </tr>
                            </thead>
                            <tbody>';
                            $mulai = new DateTime($dataAkun['TGL_MULAI_TIM']);
                            $selesai = new DateTime($dataAkun['TGL_SELESAI_TIM']);
                                $selisih = $mulai->diff($selesai)->days;
                    
                                $no = 1;
                                for ($i = 0; $i <= $selisih; $i++) {
                                    $tanggal = $mulai->format('Y-m-d');
                                    $status = '';
                                    $kegiatan = '';
                                    $posisi = '';
    
                                    if(count($absen) > 0){
                                        foreach($absen as $data){
                                            if($data['TGL'] == $tanggal){
                                                if($data['STATUS'] == 'hadir'){
                                                    $kegiatan = ucfirst($data['KEGIATAN']);
                                                    $posisi = "left";
                                                    $status = ucwords($data['STATUS']);
                                                }else{
                                                    $kegiatan = "-";
                                                    $posisi = "center";
                                                    $status = ucwords($data['STATUS']);
                                                }
                                            }else{
                                                if (date('N', strtotime($tanggal)) >= 6) {
                                                    $status = 'Libur Kerja';
                                                }
                                
                                                foreach ($libur_nasional as $libur) {
                                                    if ($tanggal == $libur['TANGGAL_LBR']) {
                                                        $status = 'Libur Nasional';
                                                        break;
                                                    }
                                                }
        
                                                if($status == "Libur Kerja"){
                                                    $status = "Libur Kerja";
                                                    $posisi = "center";
                                                    $kegiatan = "-";
                                                }else if($status == "Libur Nasional"){
                                                    $status = "Libur Nasional";
                                                    $posisi = "center";
                                                    $kegiatan = "-";
                                                }else if($status == ""){
                                                    if ($mulai < new DateTime(date("Y-m-d"))) {
                                                        $status = "Tidak Hadir";
                                                        $posisi = "center";
                                                        $kegiatan = "-";
                                                    }
                                                }
                                            }
                                        }
                                    }else{
                                        if (date('N', strtotime($tanggal)) >= 6) {
                                            $status = 'Libur Kerja';
                                        }
                        
                                        foreach ($libur_nasional as $libur) {
                                            if ($tanggal == $libur['TANGGAL_LBR']) {
                                                $status = 'Libur Nasional';
                                                break;
                                            }
                                        }
    
                                        if($status == "Libur Kerja"){
                                            $status = "Libur Kerja";
                                            $posisi = "center";
                                            $kegiatan = "-";
                                        }else if($status == "Libur Nasional"){
                                            $status = "Libur Nasional";
                                            $posisi = "center";
                                            $kegiatan = "-";
                                        }else if($status == ""){
                                            if ($mulai < new DateTime(date("Y-m-d"))) {
                                                $status = "Tidak Hadir";
                                                $posisi = "center";
                                                $kegiatan = "-";
                                            }
                                        }
                                    }
    
                                    
                                    $html .= '<tr style="font-size: 12px;">
                                        <td style="border: 1px black solid;text-align: center;padding: 8px;">'.$no.'.</td>
                                        <td width="12%" style="border: 1px black solid;text-align: center;padding: 8px;">'.$tanggal.'</td>
                                        <td style="border: 1px black solid;text-align: '.$posisi.';padding: 8px;">'.$kegiatan.'</td>
                                        <td style="border: 1px black solid;text-align: center;padding: 8px;';
                                        if ($status == 'Libur Nasional' || $status == 'Libur Kerja') {
                                            $html .= 'font-weight: bold;';
                                        }
                                        $html .= '">'.$status.'</td>
                                    </tr>';
                                    $mulai->add(new DateInterval('P1D'));
                                    $no++;
                                }
                            $html .= '</tbody>
                        </table>
                        <div style="padding-top:50px;"></div>
                        <table>
                        <tbody>
                            <tr style="font-size: 14px;">
                                <td><strong>Catatan:*</strong></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td style="padding-left:250px;">Pembimbing PKL</td>
                            </tr>
                            <tr style="font-size: 14px;">
                                <td style="padding-left:20px;width:20px;">Alpa</td>
                                <td style="width:20px;text-align:right;"> : </td>
                                <td style="width:40px;text-align:right;">'. $alpa .'</td>
                                <td style="width:40px;text-align:center;">Kali</td>
                                <td style="padding-left:250px;">Kediri, '.$tanggalsekarang.'</td>
                            </tr>
                            <tr style="font-size: 14px;">
                                <td style="padding-left:20px;">Izin</td>
                                <td style="padding-left:20px;text-align:right;"> : </td>
                                <td style="padding-left:20px;text-align:right">'. $izin .'</td>
                                <td style="width:40px;text-align:center;">Kali</td>
                                <td></<td>
                            </tr>
                            <tr style="font-size: 14px;">
                                <td style="padding-left:20px;">Hadir</td>
                                <td style="padding-left:20px;text-align:right;"> : </td>
                                <td style="padding-left:20px;text-align:right">'. $hadir .'</td>
                                <td style="width:40px;text-align:center;">Kali</td>
                                <td></<td>
                            </tr>
                            <tr style="font-size: 14px;">
                                <td style="padding-top:50px;"></td>
                                <td></<td>
                                <td></<td>
                                <td></<td>
                                <td></<td>
                            </tr>
                            <tr style="font-size: 14px;">
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td style="text-decoration:underline;padding-left:250px;"><b>'.$namaKawan.'</b></td>
                            </tr>
                            <tr style="font-size: 14px;">
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td style="padding-left:250px;">NIP. '.$nip.'</td>
                            </tr>
                        </tbody>
                    </table>
                    </body>
                    </html>';
                    if (file_exists('generate/' . $namaFile)) {
                        unlink('generate/'. $namaFile);
    
                        $dompdf->loadHtml($html);
                        $dompdf->setPaper('A4', 'potrait');
                        $dompdf->set_option('isRemoteEnabled', true);
                        $dompdf->render();
        
                        $output = $dompdf->output();
                        file_put_contents('generate/'. $namaFile, $output);
    
                        $respon['file'] = $namaFile;
        
                        echo json_encode($respon);
                        exit();
                    }else{
                        $dompdf->loadHtml($html);
                        $dompdf->setPaper('A4', 'potrait');
                        $dompdf->set_option('isRemoteEnabled', true);
                        $dompdf->render();
        
                        $output = $dompdf->output();
                        file_put_contents('generate/'. $namaFile, $output);
    
                        $respon['file'] = $namaFile;
        
                        echo json_encode($respon);
                        exit();
                    }
                }
            }
            break;
        case "rekapSeluruhKehadiranPembimbing":
            $cariBulan = "";
            if($_POST['bulan'] == "Januari"){
                $cariBulan = "01";
            }else if($_POST['bulan'] == "Februari"){
                $cariBulan = "02";
            }else if($_POST['bulan'] == "Maret"){
                $cariBulan = "03";
            }else if($_POST['bulan'] == "April"){
                $cariBulan = "04";
            }else if($_POST['bulan'] == "Mei"){
                $cariBulan = "05";
            }else if($_POST['bulan'] == "Juni"){
                $cariBulan = "06";
            }else if($_POST['bulan'] == "Juli"){
                $cariBulan = "07";
            }else if($_POST['bulan'] == "Agustus"){
                $cariBulan = "08";
            }else if($_POST['bulan'] == "September"){
                $cariBulan = "09";
            }else if($_POST['bulan'] == "Oktober"){
                $cariBulan = "10";
            }else if($_POST['bulan'] == "November"){
                $cariBulan = "11";
            }else if($_POST['bulan'] == "Desember"){
                $cariBulan = "12";
            }

            $thn = $_POST['tahun'];
            $bln = $cariBulan;
            $cariTgl = $thn.'-'.$bln;
            $hariIni = date("Y-m-d");
            $hariIni =  tgl_indo($hariIni);
            $bulanrekap = "";

            if($bln == "01"){
                $bulanrekap = "Januari";
            }else if($bln == "02"){
                $bulanrekap = "Februari";
            }else if($bln == "03"){
                $bulanrekap = "Maret";
            }else if($bln == "04"){
                $bulanrekap = "April";
            }else if($bln == "05"){
                $bulanrekap = "Mei";
            }else if($bln == "06"){
                $bulanrekap = "Juni";
            }else if($bln == "07"){
                $bulanrekap = "Juli";
            }else if($bln == "08"){
                $bulanrekap = "Agustus";
            }else if($bln == "09"){
                $bulanrekap = "September";
            }else if($bln == "10"){
                $bulanrekap = "Oktober";
            }else if($bln == "11"){
                $bulanrekap = "November";
            }else if($bln == "12"){
                $bulanrekap = "Desember";
            }

            $tanggal = date("Y-m-d");
            $sqlAkun = "SELECT*FROM tabel_akun,tabel_karyawan WHERE tabel_akun.KD_AKUN = tabel_karyawan.KD_AKUN AND tabel_akun.KD_AKUN = '$akun'";
            $resultAkun = mysqli_query($conn, $sqlAkun);
            if(mysqli_num_rows($resultAkun) > 0){
                $dataAkun = mysqli_fetch_assoc($resultAkun);
                $akunKaryawan = $dataAkun['KD_KAWAN'];
                $namaKawan = ucwords($dataAkun['NAMA_KAWAN']);
                $namaFile = "Daftar-rekap-kehadiran.pdf";

                $nip = "";
                if($dataAkun['NIP_KAWAN']){
                    $nip = $dataAkun['NIP_KAWAN'];
                }else{
                    $nip = "-";
                }

                $sqlAkun = "SELECT*FROM tabel_peserta, tabel_dtl_tim_peserta, tabel_tim_peserta, tabel_asal, tabel_karyawan
                WHERE tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST
                AND tabel_dtl_tim_peserta.KD_TIM = tabel_tim_peserta.KD_TIM
                AND tabel_tim_peserta.KD_KAWAN = tabel_karyawan.KD_KAWAN
                AND tabel_tim_peserta.KD_ASAL = tabel_asal.KD_ASAL
                AND tabel_tim_peserta.TGL_SELESAI_TIM >= '$tanggal'
                AND tabel_tim_peserta.KD_KAWAN = '$akunKaryawan'
                ORDER BY tabel_peserta.NAMA_PST ASC";
                $resultAkun = mysqli_query($conn, $sqlAkun);
                if(mysqli_num_rows($resultAkun) > 0){
                    $dataPeserta = array();
    
                    while ($frm = mysqli_fetch_assoc($resultAkun)) {
                        $frm['NAMA_PST'] = ucwords($frm['NAMA_PST']);
                        array_push($dataPeserta, $frm);
                    }

                    $sqlAbsensi = "SELECT*FROM tabel_absensi, tabel_peserta, tabel_dtl_tim_peserta, tabel_tim_peserta, tabel_asal, tabel_karyawan
                    WHERE tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST
                    AND tabel_dtl_tim_peserta.KD_TIM = tabel_tim_peserta.KD_TIM
                    AND tabel_tim_peserta.KD_KAWAN = tabel_karyawan.KD_KAWAN
                    AND tabel_peserta.KD_PST = tabel_absensi.KD_PST
                    AND tabel_tim_peserta.KD_ASAL = tabel_asal.KD_ASAL
                    AND tabel_tim_peserta.TGL_SELESAI_TIM >= '$tanggal'
                    AND STATUS_PST = 'aktif'
                    AND STATUS_SURAT != 'disapprove'
                    AND STATUS_SURAT != 'waiting'
                    AND TGL LIKE '%$cariTgl%'
                    AND tabel_tim_peserta.KD_KAWAN = '$akunKaryawan'";
                    $resultAbsen = mysqli_query($conn, $sqlAbsensi);
                    if(mysqli_num_rows($resultAbsen) > 0){
                        $dataAbsen = array();

                        while ($frm = mysqli_fetch_assoc($resultAbsen)) {
                            array_push($dataAbsen, $frm);
                        }

                        $konfigurasi = "SELECT*FROM tabel_libur_nasional";
                        $resultKonfigurasi = mysqli_query($conn, $konfigurasi);
        
                        $libur_nasional = array();
                        if (mysqli_num_rows($resultKonfigurasi) > 0) {
                            while ($frm = mysqli_fetch_assoc($resultKonfigurasi)) {
                                array_push($libur_nasional, $frm);
                            }
                        }

                        $alpa = 0;
                        $izin = 0;
                        $ada = 0;

                        $html = '<!DOCTYPE html>
                        <html lang="en">
                        <head>
                            <meta charset="UTF-8">
                            <meta name="viewport" content="width=device-width, initial-scale=1.0">
                            <style>
                                #table1 {
                                    border-collapse: collapse;
                                    width: 100%;
                                }
                        
                                #table1 th, #table1 td {
                                    padding: 8px;
                                    text-align: left;
                                    border-bottom: 1px solid #ddd;
                                }
                                
                                @media print {
                                    footer {page-break-after: always;}
                                }
                        
                                @page { size: landscape; }
                            </style>
                        </head>
                        <body>
                            <table id="table1">
                                <tbody>
                                    <tr style="font-size: 12px;">
                                        <th style="text-align: center;" width="20%"><img src="http://192.168.43.57/simaptapkl/public/service/icon/logoKominfo.png" alt="Deskripsi gambar" width="100" height="100"></th>
                                        <td style="text-align: center;"><P style="margin-top:0px;margin-bottom:0px;font-size: 16px;font-weight: bold;">DINAS KOMUNIKASI DAN INFORMATIKA</P><p style="margin-top:0px;font-size: 16px;font-weight: bold;">KABUPATEN KEDIRI</p><p style="margin-top:0px;margin-bottom:0px;font-size: 10px;">Jl. Sekartaji No. 2 Doko, Ngasem, Kediri, No. Telepon	:	 (0354) 682152, 696714, No. Fax	:	 (0354) 692279, Website	:	 https://diskominfo.kedirikab.go.id, Email	:	 diskominfo[at]kedirikab.go.id</p></td>
                                        <th style="text-align: center;" width="20%"></th>
                                    </tr>
                                </tbody>
                            </table>
                            <hr style="border-width: 2px">
                            <div style="text-align: center;">
                                <p style="font-weight: bold;">REKAP KEHADIRAN PESERTA PKL</p>
                            </div>
                            <div>
                                <p style="font-weight: bold;margin-bottom: 0px;">Bulan / Tahun : '.$bulanrekap.' '.$thn.'</p>
                            </div>
                            <div style="padding-top:20px;"></div>
                            <table border="1" style="font-family: arial, sans-serif;border-collapse: collapse;width: 100%;">
                                <thead>
                                    <tr style="font-size: 12px;">
                                        <th style="text-align: center;border: 1px black solid;padding: 8px;" width="5%">No</th>
                                        <th style="text-align: center;border: 1px black solid;padding: 8px; width: 8%;">Tanggal</th>
                                        <th style="text-align: center;border: 1px black solid;padding: 8px; width: 30%;">Nama Peserta</th>
                                        <th style="text-align: center;border: 1px black solid;padding: 8px; width: 13%;">Check In</th>
                                        <th style="text-align: center;border: 1px black solid;padding: 8px; width: 13%;">Check Out</th>
                                        <th style="text-align: center;border: 1px black solid;padding: 8px; width: 13%;">Kehadiran</th>
                                        <th style="text-align: center;border: 1px black solid;padding: 8px; width: 12%;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>';

                                $no = 1;
                                $check_in = '';
                                $check_out = '';
                                $kehadiran = '';
                                $tglrekap = '';
                                $tglSistem = '';
                                date_default_timezone_set('Asia/Jakarta');
                                $tanggalCari = cal_days_in_month(CAL_GREGORIAN, $bln, $thn);
                                for ($i = 1; $i <= $tanggalCari; $i++) { 
                                    if($i < 10){ 
                                        $tglrekap = '0'.$i.'-'.$bln.'-'.$thn;
                                        $tglSistem = $thn.'-'.$bln.'-0'.$i;
                                    }else{ 
                                        $tglrekap = $i.'-'.$bln.'-'.$thn;
                                        $tglSistem = $thn.'-'.$bln.'-'.$i;
                                    }

                                    foreach($dataPeserta as $data){
                                        $isTodayOrFuture = (strtotime($tglSistem) >= strtotime(date('Y-m-d')));
                                        $html .= '<tr style="font-size: 12px;">
                                            <td style="border: 1px black solid;text-align: center;padding: 8px;">'.$no.'.</td>
                                            <td width="12%" style="border: 1px black solid;text-align: center;padding: 8px;">'.$tglrekap.'</td>
                                            <td width="15%" style="border: 1px black solid;padding: 8px;">'.$data['NAMA_PST'].'</td>
                                            <td width="15%" style="border: 1px black solid;text-align: center;padding: 8px;">';
                                            if (date('N', strtotime($tglSistem)) >= 6) {
                                                $html .= '-';
                                            } else {                                
                                                $liburNasionalFound = false;
                                                foreach ($libur_nasional as $libur) {
                                                    if ($tglSistem == $libur['TANGGAL_LBR']) {
                                                        $html .= '-';
                                                        $liburNasionalFound = true;
                                                        break;
                                                    }
                                                }
                                            
                                                if (!$liburNasionalFound) {
                                                    $hadir = false;
                                                    foreach ($dataAbsen as $dataabs) {
                                                        if ($dataabs['TGL'] == $tglSistem && $data['KD_PST'] == $dataabs['KD_PST']) {
                                                            $hadir = true;
                                                            if ($dataabs['STATUS'] == 'hadir') {
                                                                $html .= $dataabs['CHECK_IN'];
                                                            } else {
                                                                $html .= '-';
                                                            }
                                                            break;
                                                        }
                                                    }
                                            
                                                    if (!$hadir && !$isTodayOrFuture) {
                                                        $html .= '-';
                                                    } elseif ($isTodayOrFuture) {
                                                        $html .= '';
                                                    }
                                                }
                                            }
                                            $html .= '</td>
                                            <td width="10%" style="border: 1px black solid;text-align: center;padding: 8px;">';
                                            if (date('N', strtotime($tglSistem)) >= 6) {
                                                $html .= '-';
                                            } else {                                
                                                $liburNasionalFound = false;
                                                foreach ($libur_nasional as $libur) {
                                                    if ($tglSistem == $libur['TANGGAL_LBR']) {
                                                        $html .= '-';
                                                        $liburNasionalFound = true;
                                                        break;
                                                    }
                                                }
                                            
                                                if (!$liburNasionalFound) {
                                                    $hadir = false;
                                                    foreach ($dataAbsen as $dataabs) {
                                                        if ($dataabs['TGL'] == $tglSistem && $data['KD_PST'] == $dataabs['KD_PST']) {
                                                            $hadir = true;
                                                            if ($dataabs['STATUS'] == 'hadir') {
                                                                $html .= $dataabs['CHECK_OUT'];
                                                            } else {
                                                                $html .= '-';
                                                            }
                                                            break;
                                                        }
                                                    }
                                            
                                                    if (!$hadir && !$isTodayOrFuture) {
                                                        $html .= '-';
                                                    } elseif ($isTodayOrFuture) {
                                                        $html .= '';
                                                    }
                                                }
                                            }
                                            $html .= '</td>
                                            <td width="10%" style="border: 1px black solid;text-align: center;padding: 8px;">';
                                            if (date('N', strtotime($tglSistem)) >= 6) {
                                                $html .= '-';
                                            } else {                                
                                                $liburNasionalFound = false;
                                                foreach ($libur_nasional as $libur) {
                                                    if ($tglSistem == $libur['TANGGAL_LBR']) {
                                                        $html .= '-';
                                                        $liburNasionalFound = true;
                                                        break;
                                                    }
                                                }
                                            
                                                if (!$liburNasionalFound) {
                                                    $hadir = false;
                                                    foreach ($dataAbsen as $dataabs) {
                                                        if ($dataabs['TGL'] == $tglSistem && $data['KD_PST'] == $dataabs['KD_PST']) {
                                                            $hadir = true;
                                                            if ($dataabs['STATUS'] == 'hadir') {
                                                                $html .= ucwords($dataabs['KEHADIRAN']);
                                                            } else {
                                                                $html .= '-';
                                                            }
                                                            break;
                                                        }
                                                    }
                                            
                                                    if (!$hadir && !$isTodayOrFuture) {
                                                        $html .= '-';
                                                    } elseif ($isTodayOrFuture) {
                                                        $html .= '';
                                                    }
                                                }
                                            }
                                            $html .= '</td>
                                            <td width="10%" style="border: 1px black solid;text-align: center;padding: 8px;">';
                                            if (date('N', strtotime($tglSistem)) >= 6) {
                                                $html .= '<b>Libur Kerja</b>';
                                            } else {                                
                                                $liburNasionalFound = false;
                                                foreach ($libur_nasional as $libur) {
                                                    if ($tglSistem == $libur['TANGGAL_LBR']) {
                                                        $html .= '<b>Libur Nasional</b>';
                                                        $liburNasionalFound = true;
                                                        break;
                                                    }
                                                }
                                            
                                                if (!$liburNasionalFound) {
                                                    $hadir = false;
                                                    foreach ($dataAbsen as $dataabs) {
                                                        if ($dataabs['TGL'] == $tglSistem && $data['KD_PST'] == $dataabs['KD_PST']) {
                                                            $hadir = true;
                                                            if ($dataabs['STATUS'] == 'hadir') {
                                                                $html .= ucwords($dataabs['STATUS']);
                                                                $ada = $ada+1;
                                                            } else {
                                                                $html .= ucwords($dataabs['STATUS']);
                                                                $izin = $izin + 1;
                                                            }
                                                            break;
                                                        }
                                                    }
                                            
                                                    if (!$hadir && !$isTodayOrFuture) {
                                                        $html .= '<b>Tidak Hadir</b>';
                                                        $alpa = $alpa + 1;
                                                    } elseif ($isTodayOrFuture) {
                                                        $html .= '';
                                                    }
                                                }
                                            }
                                        $html .= '</td></tr>';
                                            $no++;
                                    }
                                }
                                $html .= '</tbody>
                            </table>
                            <div style="padding-top:20px;padding-bottom:20px;"></div>
                            <table border="1" style="font-family: arial, sans-serif;border-collapse: collapse;width: 100%;">
                                <thead>
                                    <tr style="font-size: 12px;">
                                        <th style="text-align: center;border: 1px black solid;padding: 8px;" width="5%">No</th>
                                        <th style="text-align: center;border: 1px black solid;padding: 8px; width: 8%;">Tanggal</th>
                                        <th style="text-align: center;border: 1px black solid;padding: 8px; width: 30%;">Nama Peserta</th>
                                        <th style="text-align: center;border: 1px black solid;padding: 8px; width: 39%;">Kegiatan</th>
                                        <th style="text-align: center;border: 1px black solid;padding: 8px; width: 12%;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>';

                                $no = 1;
                                $check_in = '';
                                $check_out = '';
                                $kehadiran = '';
                                $tglrekap = '';
                                $tglSistem = '';
                                date_default_timezone_set('Asia/Jakarta');
                                $tanggalCari = cal_days_in_month(CAL_GREGORIAN, $bln, $thn);
                                for ($i = 1; $i <= $tanggalCari; $i++) { 
                                    if($i < 10){ 
                                        $tglrekap = '0'.$i.'-'.$bln.'-'.$thn;
                                        $tglSistem = $thn.'-'.$bln.'-0'.$i;
                                    }else{ 
                                        $tglrekap = $i.'-'.$bln.'-'.$thn;
                                        $tglSistem = $thn.'-'.$bln.'-'.$i;
                                    }

                                    foreach($dataPeserta as $data){
                                        $isTodayOrFuture = (strtotime($tglSistem) >= strtotime(date('Y-m-d')));
                                        $html .= '<tr style="font-size: 12px;">
                                            <td style="border: 1px black solid;text-align: center;padding: 8px;">'.$no.'.</td>
                                            <td width="12%" style="border: 1px black solid;text-align: center;padding: 8px;">'.$tglrekap.'</td>
                                            <td width="15%" style="border: 1px black solid;padding: 8px;">'.$data['NAMA_PST'].'</td>
                                            <td width="15%" style="border: 1px black solid;padding: 8px;text-align: center;">';
                                            if (date('N', strtotime($tglSistem)) >= 6) {
                                                $html .= '-';
                                            } else {                                
                                                $liburNasionalFound = false;
                                                foreach ($libur_nasional as $libur) {
                                                    if ($tglSistem == $libur['TANGGAL_LBR']) {
                                                        $html .= '-';
                                                        $liburNasionalFound = true;
                                                        break;
                                                    }
                                                }
                                            
                                                if (!$liburNasionalFound) {
                                                    $hadir = false;
                                                    foreach ($dataAbsen as $dataabs) {
                                                        if ($dataabs['TGL'] == $tglSistem && $data['KD_PST'] == $dataabs['KD_PST']) {
                                                            $hadir = true;
                                                            if ($dataabs['STATUS'] == 'hadir') {
                                                                $html .= $dataabs['KEGIATAN'];
                                                            } else {
                                                                $html .= '-';
                                                            }
                                                            break;
                                                        }
                                                    }
                                            
                                                    if (!$hadir && !$isTodayOrFuture) {
                                                        $html .= '-';
                                                    } elseif ($isTodayOrFuture) {
                                                        $html .= '';
                                                    }
                                                }
                                            }
                                            $html .= '</td>
                                            <td width="10%" style="border: 1px black solid;text-align: center;padding: 8px;">';
                                            if (date('N', strtotime($tglSistem)) >= 6) {
                                                $html .= '<b>Libur Kerja</b>';
                                            } else {                                
                                                $liburNasionalFound = false;
                                                foreach ($libur_nasional as $libur) {
                                                    if ($tglSistem == $libur['TANGGAL_LBR']) {
                                                        $html .= '<b>Libur Nasional</b>';
                                                        $liburNasionalFound = true;
                                                        break;
                                                    }
                                                }
                                            
                                                if (!$liburNasionalFound) {
                                                    $hadir = false;
                                                    foreach ($dataAbsen as $dataabs) {
                                                        if ($dataabs['TGL'] == $tglSistem && $data['KD_PST'] == $dataabs['KD_PST']) {
                                                            $hadir = true;
                                                            if ($dataabs['STATUS'] == 'hadir') {
                                                                $html .= ucwords($dataabs['STATUS']);
                                                            } else {
                                                                $html .= ucwords($dataabs['STATUS']);
                                                            }
                                                            break;
                                                        }
                                                    }
                                            
                                                    if (!$hadir && !$isTodayOrFuture) {
                                                        $html .= '<b>Tidak Hadir</b>';
                                                    } elseif ($isTodayOrFuture) {
                                                        $html .= '';
                                                    }
                                                }
                                            }
                                        $html .= '</td></tr>';
                                            $no++;
                                    }
                                }
                                $html .= '</tbody>
                            </table>
                            <div style="padding-top:50px;"></div>
                            <table>
                            <tbody>
                                <tr style="font-size: 14px;">
                                    <td><strong>Catatan:*</strong></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td style="padding-left:550px;">Pembimbing PKL</td>
                                </tr>
                                <tr style="font-size: 14px;">
                                    <td style="padding-left:20px;width:20px;">Alpa</td>
                                    <td style="width:20px;text-align:right;"> : </td>
                                    <td style="width:40px;text-align:right;">'.$alpa.'</td>
                                    <td style="width:40px;text-align:center;">Kali</td>
                                    <td style="padding-left:550px;">Kediri, '.$hariIni.'</td>
                                </tr>
                                <tr style="font-size: 14px;">
                                    <td style="padding-left:20px;">Izin</td>
                                    <td style="padding-left:20px;text-align:right;"> : </td>
                                    <td style="padding-left:20px;text-align:right">'.$izin.'</td>
                                    <td style="width:40px;text-align:center;">Kali</td>
                                    <td></<td>
                                </tr>
                                <tr style="font-size: 14px;">
                                    <td style="padding-left:20px;">Hadir</td>
                                    <td style="padding-left:20px;text-align:right;"> : </td>
                                    <td style="padding-left:20px;text-align:right">'.$ada.'</td>
                                    <td style="width:40px;text-align:center;">Kali</td>
                                    <td></<td>
                                </tr>
                                <tr style="font-size: 14px;">
                                    <td style="padding-top:50px;"></td>
                                    <td></<td>
                                    <td></<td>
                                    <td></<td>
                                    <td></<td>
                                </tr>
                                <tr style="font-size: 14px;">
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td style="text-decoration:underline;padding-left:550px;"><b>'.$namaKawan.'</b></td>
                                </tr>
                                <tr style="font-size: 14px;">
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td style="padding-left:550px;">NIP. '.$nip.'</td>
                                </tr>
                            </tbody>
                            </table>
                            
                        </body>
                        </html>';

                        if (file_exists('generate/' . $namaFile)) {
                            unlink('generate/'. $namaFile);
        
                            $dompdf->loadHtml($html);
                            $dompdf->setPaper('A4', 'landscape');
                            $dompdf->set_option('isRemoteEnabled', true);
                            $dompdf->render();
            
                            $output = $dompdf->output();
                            file_put_contents('generate/'. $namaFile, $output);
        
                            $respon['file'] = $namaFile;
            
                            echo json_encode($respon);
                            exit();
                        }else{
                            $dompdf->loadHtml($html);
                            $dompdf->setPaper('A4', 'landscape');
                            $dompdf->set_option('isRemoteEnabled', true);
                            $dompdf->render();
            
                            $output = $dompdf->output();
                            file_put_contents('generate/'. $namaFile, $output);
        
                            $respon['file'] = $namaFile;
            
                            echo json_encode($respon);
                            exit();
                        }                  
                    }else{
                        $respon['file'] = "kosong";
            
                        echo json_encode($respon);
                        exit();
                    }

                }
            }
            
            break;
        case "cekrekapSeluruhKehadiranPembimbing":
            $cariBulan = "";
            if($_POST['bulan'] == "Januari"){
                $cariBulan = "01";
            }else if($_POST['bulan'] == "Februari"){
                $cariBulan = "02";
            }else if($_POST['bulan'] == "Maret"){
                $cariBulan = "03";
            }else if($_POST['bulan'] == "April"){
                $cariBulan = "04";
            }else if($_POST['bulan'] == "Mei"){
                $cariBulan = "05";
            }else if($_POST['bulan'] == "Juni"){
                $cariBulan = "06";
            }else if($_POST['bulan'] == "Juli"){
                $cariBulan = "07";
            }else if($_POST['bulan'] == "Agustus"){
                $cariBulan = "08";
            }else if($_POST['bulan'] == "September"){
                $cariBulan = "09";
            }else if($_POST['bulan'] == "Oktober"){
                $cariBulan = "10";
            }else if($_POST['bulan'] == "November"){
                $cariBulan = "11";
            }else if($_POST['bulan'] == "Desember"){
                $cariBulan = "12";
            }

            $thn = $_POST['tahun'];
            $bln = $cariBulan;
            $cariTgl = $thn.'-'.$bln;
            $hariIni = date("Y-m-d");
            $hariIni =  tgl_indo($hariIni);
            $bulanrekap = "";

            if($bln == "01"){
                $bulanrekap = "Januari";
            }else if($bln == "02"){
                $bulanrekap = "Februari";
            }else if($bln == "03"){
                $bulanrekap = "Maret";
            }else if($bln == "04"){
                $bulanrekap = "April";
            }else if($bln == "05"){
                $bulanrekap = "Mei";
            }else if($bln == "06"){
                $bulanrekap = "Juni";
            }else if($bln == "07"){
                $bulanrekap = "Juli";
            }else if($bln == "08"){
                $bulanrekap = "Agustus";
            }else if($bln == "09"){
                $bulanrekap = "September";
            }else if($bln == "10"){
                $bulanrekap = "Oktober";
            }else if($bln == "11"){
                $bulanrekap = "November";
            }else if($bln == "12"){
                $bulanrekap = "Desember";
            }

            $tanggal = date("Y-m-d");
            $sqlAkun = "SELECT*FROM tabel_akun,tabel_karyawan WHERE tabel_akun.KD_AKUN = tabel_karyawan.KD_AKUN AND tabel_akun.KD_AKUN = '$akun'";
            $resultAkun = mysqli_query($conn, $sqlAkun);
            if(mysqli_num_rows($resultAkun) > 0){
                $dataAkun = mysqli_fetch_assoc($resultAkun);
                $akunKaryawan = $dataAkun['KD_KAWAN'];
                $namaKawan = ucwords($dataAkun['NAMA_KAWAN']);
                $namaFile = "Daftar-rekap-kehadiran.pdf";

                $nip = "";
                if($dataAkun['NIP_KAWAN']){
                    $nip = $dataAkun['NIP_KAWAN'];
                }else{
                    $nip = "-";
                }

                $sqlAkun = "SELECT*FROM tabel_peserta, tabel_dtl_tim_peserta, tabel_tim_peserta, tabel_asal, tabel_karyawan
                WHERE tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST
                AND tabel_dtl_tim_peserta.KD_TIM = tabel_tim_peserta.KD_TIM
                AND tabel_tim_peserta.KD_KAWAN = tabel_karyawan.KD_KAWAN
                AND tabel_tim_peserta.KD_ASAL = tabel_asal.KD_ASAL
                AND tabel_tim_peserta.TGL_SELESAI_TIM >= '$tanggal'
                AND tabel_tim_peserta.KD_KAWAN = '$akunKaryawan'
                ORDER BY tabel_peserta.NAMA_PST ASC";
                $resultAkun = mysqli_query($conn, $sqlAkun);
                if(mysqli_num_rows($resultAkun) > 0){
                    $dataPeserta = array();
    
                    while ($frm = mysqli_fetch_assoc($resultAkun)) {
                        $frm['NAMA_PST'] = ucwords($frm['NAMA_PST']);
                        array_push($dataPeserta, $frm);
                    }

                    $sqlAbsensi = "SELECT*FROM tabel_absensi, tabel_peserta, tabel_dtl_tim_peserta, tabel_tim_peserta, tabel_asal, tabel_karyawan
                    WHERE tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST
                    AND tabel_dtl_tim_peserta.KD_TIM = tabel_tim_peserta.KD_TIM
                    AND tabel_tim_peserta.KD_KAWAN = tabel_karyawan.KD_KAWAN
                    AND tabel_peserta.KD_PST = tabel_absensi.KD_PST
                    AND tabel_tim_peserta.KD_ASAL = tabel_asal.KD_ASAL
                    AND tabel_tim_peserta.TGL_SELESAI_TIM >= '$tanggal'
                    AND STATUS_PST = 'aktif'
                    AND STATUS_SURAT != 'disapprove'
                    AND STATUS_SURAT != 'waiting'
                    AND TGL LIKE '%$cariTgl%'
                    AND tabel_tim_peserta.KD_KAWAN = '$akunKaryawan'";
                    $resultAbsen = mysqli_query($conn, $sqlAbsensi);
                    if(mysqli_num_rows($resultAbsen) > 0){
                        $respon['respon'] = "1";
            
                        echo json_encode($respon);
                        exit();                
                    }else{
                        $respon['respon'] = "0";
            
                        echo json_encode($respon);
                        exit();
                    }

                }
            }
            
            break;
        case "rekapKehadiran":
            $sqlAkun = "SELECT*FROM tabel_peserta, tabel_dtl_tim_peserta, tabel_tim_peserta, tabel_asal, tabel_karyawan
            WHERE tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST
            AND tabel_dtl_tim_peserta.KD_TIM = tabel_tim_peserta.KD_TIM
            AND tabel_tim_peserta.KD_KAWAN = tabel_karyawan.KD_KAWAN
            AND tabel_tim_peserta.KD_ASAL = tabel_asal.KD_ASAL
            AND tabel_peserta.KD_AKUN = '$akun'";
            $resultAkun = mysqli_query($conn, $sqlAkun);
            if(mysqli_num_rows($resultAkun) > 0){
                $dataAkun = mysqli_fetch_assoc($resultAkun);
                $kodeMahasiswa = $dataAkun['KD_PST'];
                $namaMahasiswa = ucwords($dataAkun['NAMA_PST']);
                $namaKampus = ucwords($dataAkun['NAMA_ASAL']);
                $namaKawan = ucwords($dataAkun['NAMA_KAWAN']);
                $namaFile = "Daftar-rekap-kehadiran-". $kodeMahasiswa . ".pdf";
                $tanggalMulai = $dataAkun['TGL_MULAI_TIM'];
                $tanggalSelesai = $dataAkun['TGL_SELESAI_TIM'];

                $nip = "";
                if($dataAkun['NIP_KAWAN']){
                    $nip = $dataAkun['NIP_KAWAN'];
                }else{
                    $nip = "-";
                }
    
                $tanggalsekarang = date("Y-m-d");
                $mulaisekarang = new DateTime($tanggalsekarang);
                $tanggalsekarang =  tgl_indo($tanggalsekarang);

                $mulai = new DateTime($tanggalMulai);
                $hari_masuk = $mulai->format('D');
                $tanggalMulai = hari($hari_masuk) . ", " . tgl_indo($tanggalMulai);

                $keluar = new DateTime($tanggalSelesai);
                $hari_keluar = $keluar->format('D');
                $tanggalSelesai = hari($hari_keluar) . ", " . tgl_indo($tanggalSelesai);

                $masaPKL = $tanggalMulai. ' - '.$tanggalSelesai;

                $konfigurasi = "SELECT*FROM tabel_libur_nasional";
                $resultKonfigurasi = mysqli_query($conn, $konfigurasi);

                $libur_nasional = array();
                if (mysqli_num_rows($resultKonfigurasi) > 0) {
                    while ($frm = mysqli_fetch_assoc($resultKonfigurasi)) {
                        array_push($libur_nasional, $frm);
                    }
                }

                $queryabsen = "SELECT*FROM tabel_absensi WHERE KD_PST = '$kodeMahasiswa' AND STATUS_SURAT != 'disapprove' AND STATUS_SURAT != 'waiting' ORDER BY TGL ASC";
                $resultabsen = mysqli_query($conn, $queryabsen);

                $absen = array();
                if (mysqli_num_rows($resultabsen) > 0) {
                    while ($frm = mysqli_fetch_assoc($resultabsen)) {
                        array_push($absen, $frm);
                    }
                }

                date_default_timezone_set("Asia/Jakarta");
                date_default_timezone_get();

                $alpa = 0;
                $izin = 0;
                $hadir = 0;

                $html = '<!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <style>
                        #table1 {
                            border-collapse: collapse;
                            width: 100%;
                        }
                
                        #table1 th, #table1 td {
                            padding: 8px;
                            text-align: left;
                            border-bottom: 1px solid #ddd;
                        }
                        
                        @media print {
                            footer {page-break-after: always;}
                        }
                
                        @page { size: portrait; }
                    </style>
                </head>
                <body>
                    <table id="table1">
                        <tbody>
                            <tr style="font-size: 12px;">
                                <th style="text-align: center;" width="20%"><img src="http://192.168.43.57/simaptapkl/public/service/icon/logoKominfo.png" alt="Deskripsi gambar" width="100" height="100"></th>
                                <td style="text-align: center;"><P style="margin-top:0px;margin-bottom:0px;font-size: 16px;font-weight: bold;">DINAS KOMUNIKASI DAN INFORMATIKA</P><p style="margin-top:0px;font-size: 16px;font-weight: bold;">KABUPATEN KEDIRI</p><p style="margin-top:0px;margin-bottom:0px;font-size: 10px;">Jl. Sekartaji No. 2 Doko, Ngasem, Kediri, No. Telepon	:	 (0354) 682152, 696714, No. Fax	:	 (0354) 692279, Website	:	 https://diskominfo.kedirikab.go.id, Email	:	 diskominfo[at]kedirikab.go.id</p></td>
                                <th style="text-align: center;" width="20%"></th>
                            </tr>
                        </tbody>
                    </table>
                    <hr style="border-width: 2px">
                    <div style="text-align: center;">
                        <p style="font-weight: bold;">REKAP KEHADIRAN PESERTA PKL</p>
                    </div>
                    <table>
                        <tbody>
                            <tr style="font-size: 14px;">
                                <td width="15%"><p style="margin-top: 0px;margin-bottom: 5px;padding-right:20px;"><b>Nama Peserta</b></p></td>
                                <td width="2%" style="padding-right:10px;">:</td>
                                <td>'.$namaMahasiswa.'</td>
                            </tr>
                            <tr style="font-size: 14px;">
                                <td width="15%"><p style="margin-top: 5px;margin-bottom: 5px;"><b>Pembimbing</b></p></td>
                                <td width="2%">:</td>
                                <td>'.$namaKawan.'</td>
                            </tr>
                            <tr style="font-size: 14px;">
                                <td width="15%"><p style="margin-top: 5px;margin-bottom: 0px;"><b>Asal</b></p></td>
                                <td width="2%">:</td>
                                <td>'.$namaKampus.'</td>
                            </tr>
                            <tr style="font-size: 14px;">
                                <td width="15%"><p style="margin-top: 5px;margin-bottom: 0px;"><b>Masa PKL</b></p></td>
                                <td width="2%">:</td>
                                <td>'.$masaPKL.'</td>
                            </tr>
                        </tbody>
                    </table>
                    <div style="padding-top:20px;"></div>
                    <table border="1" style="font-family: arial, sans-serif;border-collapse: collapse;width: 100%;">
                        <thead>
                            <tr style="font-size: 12px;">
                                <th style="text-align: center;border: 1px black solid;padding: 8px;" width="5%">No</th>
                                <th style="text-align: center;border: 1px black solid;padding: 8px;">Tanggal</th>
                                <th style="text-align: center;border: 1px black solid;padding: 8px; width: 15%;">Check In</th>
                                <th style="text-align: center;border: 1px black solid;padding: 8px; width: 15%;">Check Out</th>
                                <th style="text-align: center;border: 1px black solid;padding: 8px; width: 20%;">Kehadiran</th>
                                <th style="text-align: center;border: 1px black solid;padding: 8px; width: 12%;">Status</th>
                            </tr>
                        </thead>
                        <tbody>';
                            $mulai = new DateTime($dataAkun['TGL_MULAI_TIM']);
                            $selesai = new DateTime($dataAkun['TGL_SELESAI_TIM']);
                            $selisih = $mulai->diff($selesai)->days;
                
                            $no = 1;
                            for ($i = 0; $i <= $selisih; $i++) {
                                $tanggal = $mulai->format('Y-m-d');
                                $check_in = '';
                                $check_out = '';
                                $kehadiran = '';
                                $status = '';

                                if(count($absen) > 0){
                                    foreach($absen as $data){
                                        if($data['TGL'] == $tanggal){
                                            if($data['STATUS'] == 'hadir'){
                                                $check_in = $data['CHECK_IN'];
                                                $check_out = $data['CHECK_OUT'];
                                                $kehadiran = ucwords($data['KEHADIRAN']);
                                                $status = ucwords($data['STATUS']);
                                                $hadir = $hadir + 1;
                                            }else{
                                                $check_in = "-";
                                                $check_out = "-";
                                                $kehadiran = "-";
                                                $status = ucwords($data['STATUS']);
                                                $izin = $izin + 1;
                                            }
                                        }else{
                                            if (date('N', strtotime($tanggal)) >= 6) {
                                                $status = 'Libur Kerja';
                                            }
                            
                                            foreach ($libur_nasional as $libur) {
                                                if ($tanggal == $libur['TANGGAL_LBR']) {
                                                    $status = 'Libur Nasional';
                                                    break;
                                                }
                                            }
    
                                            if($status == "Libur Kerja"){
                                                $check_in = "-";
                                                $check_out = "-";
                                                $kehadiran = "-";
                                            }else if($status == "Libur Nasional"){
                                                $check_in = "-";
                                                $check_out = "-";
                                                $kehadiran = "-";
                                            }else if($status == ""){
                                                if ($mulai < new DateTime(date("Y-m-d"))) {
                                                    $status = "Tidak Hadir";
                                                    $check_in = "-";
                                                    $check_out = "-";
                                                    $kehadiran = "-";
                                                    $alpa = $alpa + 1;
                                                }
                                            }
                                        }
                                    }
                                }else{
                                    if (date('N', strtotime($tanggal)) >= 6) {
                                        $status = 'Libur Kerja';
                                    }
                    
                                    foreach ($libur_nasional as $libur) {
                                        if ($tanggal == $libur['TANGGAL_LBR']) {
                                            $status = 'Libur Nasional';
                                            break;
                                        }
                                    }

                                    if($status == "Libur Kerja"){
                                        $check_in = "-";
                                        $check_out = "-";
                                        $kehadiran = "-";
                                    }else if($status == "Libur Nasional"){
                                        $check_in = "-";
                                        $check_out = "-";
                                        $kehadiran = "-";
                                    }else if($status == ""){
                                        if ($mulai < new DateTime(date("Y-m-d"))) {
                                            $status = "Tidak Hadir";
                                            $check_in = "-";
                                            $check_out = "-";
                                            $kehadiran = "-";
                                            $alpa = $alpa + 1;
                                        }
                                    }
                                }

                                $html .= '<tr style="font-size: 12px;">
                                    <td style="border: 1px black solid;text-align: center;padding: 8px;">'.$no.'.</td>
                                    <td width="12%" style="border: 1px black solid;text-align: center;padding: 8px;">'.$tanggal.'</td>
                                    <td width="15%" style="border: 1px black solid;text-align: center;padding: 8px;">'.$check_in.'</td>
                                    <td width="15%" style="border: 1px black solid;text-align: center;padding: 8px;">'.$check_out.'</td>
                                    <td width="10%" style="border: 1px black solid;text-align: center;padding: 8px;">'.$kehadiran.'</td>
                                    <td width="10%" style="border: 1px black solid;text-align: center;padding: 8px;';
                                    if ($status == 'Libur Nasional' || $status == 'Libur Kerja') {
                                        $html .= 'font-weight: bold;';
                                    }
                                    $html .= '">'.$status.'</td>
                                </tr>';
                                $mulai->add(new DateInterval('P1D'));
                                $no++;
                            }
                        $html .= '</tbody>
                    </table>
                    <div style="padding-top:20px;padding-bottom:20px;"></div>
                    <table border="1" style="font-family: arial, sans-serif;border-collapse: collapse;width: 100%;">
                        <thead>
                            <tr style="font-size: 12px;">
                                <th style="text-align: center;border: 1px black solid;padding: 8px;width:6.3%;">No</th>
                                <th style="text-align: center;border: 1px black solid;padding: 8px;width:15.2%;">Tanggal</th>
                                <th style="text-align: center;border: 1px black solid;padding: 8px;">Kegiatan</th>
                                <th style="text-align: center;border: 1px black solid;padding: 8px;width: 15%">Status</th>
                            </tr>
                        </thead>
                        <tbody>';
                        $mulai = new DateTime($dataAkun['TGL_MULAI_TIM']);
                        $selesai = new DateTime($dataAkun['TGL_SELESAI_TIM']);
                            $selisih = $mulai->diff($selesai)->days;
                
                            $no = 1;
                            for ($i = 0; $i <= $selisih; $i++) {
                                $tanggal = $mulai->format('Y-m-d');
                                $status = '';
                                $kegiatan = '';
                                $posisi = '';

                                if(count($absen) > 0){
                                    foreach($absen as $data){
                                        if($data['TGL'] == $tanggal){
                                            if($data['STATUS'] == 'hadir'){
                                                $kegiatan = ucfirst($data['KEGIATAN']);
                                                $posisi = "left";
                                                $status = ucwords($data['STATUS']);
                                            }else{
                                                $kegiatan = "-";
                                                $posisi = "center";
                                                $status = ucwords($data['STATUS']);
                                            }
                                        }else{
                                            if (date('N', strtotime($tanggal)) >= 6) {
                                                $status = 'Libur Kerja';
                                            }
                            
                                            foreach ($libur_nasional as $libur) {
                                                if ($tanggal == $libur['TANGGAL_LBR']) {
                                                    $status = 'Libur Nasional';
                                                    break;
                                                }
                                            }
    
                                            if($status == "Libur Kerja"){
                                                $status = "Libur Kerja";
                                                $posisi = "center";
                                                $kegiatan = "-";
                                            }else if($status == "Libur Nasional"){
                                                $status = "Libur Nasional";
                                                $posisi = "center";
                                                $kegiatan = "-";
                                            }else if($status == ""){
                                                if ($mulai < new DateTime(date("Y-m-d"))) {
                                                    $status = "Tidak Hadir";
                                                    $posisi = "center";
                                                    $kegiatan = "-";
                                                }
                                            }
                                        }
                                    }
                                }else{
                                    if (date('N', strtotime($tanggal)) >= 6) {
                                        $status = 'Libur Kerja';
                                    }
                    
                                    foreach ($libur_nasional as $libur) {
                                        if ($tanggal == $libur['TANGGAL_LBR']) {
                                            $status = 'Libur Nasional';
                                            break;
                                        }
                                    }

                                    if($status == "Libur Kerja"){
                                        $status = "Libur Kerja";
                                        $posisi = "center";
                                        $kegiatan = "-";
                                    }else if($status == "Libur Nasional"){
                                        $status = "Libur Nasional";
                                        $posisi = "center";
                                        $kegiatan = "-";
                                    }else if($status == ""){
                                        if ($mulai < new DateTime(date("Y-m-d"))) {
                                            $status = "Tidak Hadir";
                                            $posisi = "center";
                                            $kegiatan = "-";
                                        }
                                    }
                                }

                                
                                $html .= '<tr style="font-size: 12px;">
                                    <td style="border: 1px black solid;text-align: center;padding: 8px;">'.$no.'.</td>
                                    <td width="12%" style="border: 1px black solid;text-align: center;padding: 8px;">'.$tanggal.'</td>
                                    <td style="border: 1px black solid;text-align: '.$posisi.';padding: 8px;">'.$kegiatan.'</td>
                                    <td style="border: 1px black solid;text-align: center;padding: 8px;';
                                    if ($status == 'Libur Nasional' || $status == 'Libur Kerja') {
                                        $html .= 'font-weight: bold;';
                                    }
                                    $html .= '">'.$status.'</td>
                                </tr>';
                                $mulai->add(new DateInterval('P1D'));
                                $no++;
                            }
                        $html .= '</tbody>
                    </table>
                    <div style="padding-top:50px;"></div>
                    <table>
                        <tbody>
                            <tr style="font-size: 14px;">
                                <td><strong>Catatan:*</strong></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td style="padding-left:250px;">Pembimbing PKL</td>
                            </tr>
                            <tr style="font-size: 14px;">
                                <td style="padding-left:20px;width:20px;">Alpa</td>
                                <td style="width:20px;text-align:right;"> : </td>
                                <td style="width:40px;text-align:right;">'. $alpa .'</td>
                                <td style="width:40px;text-align:center;">Kali</td>
                                <td style="padding-left:250px;">Kediri, '.$tanggalsekarang.'</td>
                            </tr>
                            <tr style="font-size: 14px;">
                                <td style="padding-left:20px;">Izin</td>
                                <td style="padding-left:20px;text-align:right;"> : </td>
                                <td style="padding-left:20px;text-align:right">'. $izin .'</td>
                                <td style="width:40px;text-align:center;">Kali</td>
                                <td></<td>
                            </tr>
                            <tr style="font-size: 14px;">
                                <td style="padding-left:20px;">Hadir</td>
                                <td style="padding-left:20px;text-align:right;"> : </td>
                                <td style="padding-left:20px;text-align:right">'. $hadir .'</td>
                                <td style="width:40px;text-align:center;">Kali</td>
                                <td></<td>
                            </tr>
                            <tr style="font-size: 14px;">
                                <td style="padding-top:50px;"></td>
                                <td></<td>
                                <td></<td>
                                <td></<td>
                                <td></<td>
                            </tr>
                            <tr style="font-size: 14px;">
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td style="text-decoration:underline;padding-left:250px;"><b>'.$namaKawan.'</b></td>
                            </tr>
                            <tr style="font-size: 14px;">
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td style="padding-left:250px;">NIP. '.$nip.'</td>
                            </tr>
                        </tbody>
                    </table>
                </body>
                </html>';
                if (file_exists('generate/' . $namaFile)) {
                    unlink('generate/'. $namaFile);

                    $dompdf->loadHtml($html);
                    $dompdf->setPaper('A4', 'potrait');
                    $dompdf->set_option('isRemoteEnabled', true);
                    $dompdf->render();
    
                    $output = $dompdf->output();
                    file_put_contents('generate/'. $namaFile, $output);

                    $respon['file'] = $namaFile;
    
                    echo json_encode($respon);
                    exit();
                }else{
                    $dompdf->loadHtml($html);
                    $dompdf->setPaper('A4', 'potrait');
                    $dompdf->set_option('isRemoteEnabled', true);
                    $dompdf->render();
    
                    $output = $dompdf->output();
                    file_put_contents('generate/'. $namaFile, $output);

                    $respon['file'] = $namaFile;
    
                    echo json_encode($respon);
                    exit();
                }
            }
            break;
        case "tahun":
            $sqltahun = "SELECT TAHUN_TIM FROM tabel_tim_peserta GROUP BY TAHUN_TIM ORDER BY TAHUN_TIM ASC";
            $resulttahun = mysqli_query($conn, $sqltahun);
            if(mysqli_num_rows($resulttahun) > 0){
                $data = array(
                    array("TAHUN_TIM" => ". . .")
                );
    
                while ($frm = mysqli_fetch_assoc($resulttahun)) {
                    array_push($data, $frm);
                }

                echo json_encode($data);
                exit();
            }else{
                $data = array();
    
                while ($frm = mysqli_fetch_assoc($resulttahun)) {
                    array_push($data, $frm);
                }

                echo json_encode($data);
                exit();
            }
            break;
    }
}

//konversi tgl ke bahasa indonesia
function tgl_indo($tanggal)
{
    $bulan = array(
        1 =>   'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    );
    $pecahkan = explode('-', $tanggal);

    return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
}

//konfersi hari ke bahasa indonesia
function hari($tgl)
{
    $hari = $tgl;

    switch ($hari) {
        case 'Sun':
            $hari_ini = "Minggu";
            break;

        case 'Mon':
            $hari_ini = "Senin";
            break;

        case 'Tue':
            $hari_ini = "Selasa";
            break;

        case 'Wed':
            $hari_ini = "Rabu";
            break;

        case 'Thu':
            $hari_ini = "Kamis";
            break;

        case 'Fri':
            $hari_ini = "Jumat";
            break;

        case 'Sat':
            $hari_ini = "Sabtu";
            break;

        default:
            $hari_ini = "Tidak di ketahui";
            break;
    }

    return $hari_ini;
}

