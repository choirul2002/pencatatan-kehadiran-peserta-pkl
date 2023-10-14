<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peserta | <?= $title ?></title>
    <link href="<?= base_url() ?>/service/icon/<?= $database[20][0]['LOGO_SISTEM'] ?>" rel="icon">
    <link href="<?= base_url() ?>/service/icon/<?= $database[20][0]['LOGO_SISTEM'] ?>" rel="apple-touch-icon">
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

        @page { size: landscape; }
    </style>
</head>

<body>
<table>
                    <tbody>
                        <tr style="font-size: 12px;">
                            <th style="text-align: center;" width="20%">  <img src="<?= base_url() ?>/service/icon/logoKominfo.png" alt="Deskripsi gambar" width="100" height="100"></th>
                            <td style="text-align: center;"><P style="margin-top:0px;margin-bottom:0px;font-size: 16px;font-weight: bold;">DINAS KOMUNIKASI DAN INFORMATIKA</P><p style="margin-top:0px;font-size: 16px;font-weight: bold;">KABUPATEN KEDIRI</p><p style="margin-top:0px;margin-bottom:0px;font-size: 10px;">Jl. Sekartaji No. 2 Doko, Ngasem, Kediri, No. Telepon	:	 (0354) 682152, 696714, No. Fax	:	 (0354) 692279, Website	:	 https://diskominfo.kedirikab.go.id, Email	:	 diskominfo[at]kedirikab.go.id</p></td>
                            <th style="text-align: center;" width="20%"></th>
                        </tr>
                    </tbody>
                </table><hr style="border-width: 2px">
    <table id="table1" border="1">
        <thead>
            <tr style="font-size: 12px;">
                <th style="text-align: center;" width="7%">No</th>
                <th style="text-align: center;" width="18%">Asal</th>
                <th style="text-align: center;" width="27%">Nama Peserta</th>
                <th style="text-align: center;" width="10%">Username</th>
                <th style="text-align: center;" width="10%">Password</th>
                <th style="text-align: center;" width="8%">Tahun</th>
                <th style="text-align: center;" width="10%">Status PKL</th>
            </tr>
        </thead>
        <tbody>
        <?php $no = 1;
        foreach ($database[1] as $data) { ?>
            <tr>
                <td style="font-size: 12px;text-align: center;"><?= $no ?>.</td>
                <td style="font-size: 12px;"><?= ucwords($data['NAMA_ASAL']) ?></td>
                <td style="font-size: 12px;"><?= ucwords($data['NAMA_PST']) ?></td>
                <td style="font-size: 12px;"><?= $data['EMAIL'] ?></td>
                <td style="font-size: 12px;"><?= $data['PASSWORD'] ?></td>
                <td style="text-align: center;font-size: 12px;"><?= $data['TAHUN_PST'] ?></td>
                <td style="text-align: center;font-size: 12px;"><?= ucwords($data['STATUS_PST']) ?></td>
            </tr>
        <?php $no++;
        } ?>
        </tbody>
    </table>
</body>

</html>

<script>
    window.print();
</script>