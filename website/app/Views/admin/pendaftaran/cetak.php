<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran PKL</title>
    <link href="<?= base_url() ?>/service/icon/<?= $database[20][0]['logo_sistem'] ?>" rel="icon">
    <link href="<?= base_url() ?>/service/icon/<?= $database[20][0]['logo_sistem'] ?>" rel="apple-touch-icon">
    <style>
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        th {
            border: 1px black solid;
            text-align: left;
            padding: 8px;
        }

        td {
            border: 1px black solid;
            text-align: left;
            padding: 8px;
        }
        @page { size: landscape; }
    </style>
</head>

<body>
    <table border="1">
        <thead>
            <tr style="font-size: 12px;">
                <th style="text-align: center;" width="7%">No</th>
                <th style="text-align: center;" width="22%">Kampus</th>
                <th style="text-align: center;">Mahasiswa</th>
                <th style="text-align: center;" width="8%">Tahun</th>
                <th style="text-align: center;" width="15%">Status</th>
            </tr>
        </thead>
        <tbody>
        <?php $no = 1;
        foreach ($database[1] as $data) { ?>
            <tr style="font-size: 12px;">
                <td style="text-align: center;"><?= $no ?>.</td>
                <td><?= ucwords($data['kampus']) ?></td>
                <td>
                    <?php foreach($database[2] as $mahasiswa){ ?>
                        <?php if($mahasiswa['kd_pendaftaran'] == $data['kd_pendaftaran']){ ?>
                            <?= ucfirst($mahasiswa['nama']) ?>; 
                        <?php } ?>
                    <?php } ?>
                </td>
                <td style="text-align: center;"><?= $data['tahun'] ?></td>
                <td style="text-align: center;"><?= ucwords($data['status_pendaftaran']) ?></td>
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