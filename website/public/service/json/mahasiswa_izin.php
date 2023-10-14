<?php
include("connect.php");
// $tahun = date("Y") - 1;
$tahun = date("Y");
$sql = mysqli_query($connect, "SELECT * FROM absensi NATURAL JOIN mahasiswa WHERE status_surat = 'waiting' ORDER BY nama_mhs ASC");
$sql1 = mysqli_query($connect, "SELECT COUNT(*) AS jumlah FROM absensi WHERE status_surat = 'waiting'");
$jumlah = mysqli_fetch_assoc($sql1);
$result = array();

while ($row = mysqli_fetch_assoc($sql)) {
    $data['mahasiswa'][] = $row;
}

$data['jumlah'] = $jumlah;

echo json_encode(array("result" => $data));
