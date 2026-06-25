<?php

$showResult = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $input = trim($_POST['nama']);
    $showResult = true;

    if ($input == "") {

        $warna = "#dc3545";
        $bg = "#fdeaea";
        $status = "GAGAL";
        $judul = "Skenario 4 : Input Kosong";

        $hasil = "Data tidak dapat diproses.";
        $keterangan = "Kolom input tidak diisi sehingga sistem menolak data yang dimasukkan.";

    } elseif (preg_match('/^[A-Za-z\s]+$/', $input)) {

        $warna = "#198754";
        $bg = "#eaf7ef";
        $status = "BERHASIL";
        $judul = "Skenario 1 : Penggunaan Karakter";

        $hasil = "Data karakter berhasil diproses: \"$input\"";
        $keterangan = "Input hanya mengandung huruf atau karakter teks tanpa angka.";

    } elseif (preg_match('/^[0-9]+$/', $input)) {

        $warna = "#0d6efd";
        $bg = "#edf4ff";
        $status = "BERHASIL";
        $judul = "Skenario 2 : Penggunaan Angka";

        $hasil = "Data angka berhasil diproses: \"$input\"";
        $keterangan = "Input hanya mengandung angka tanpa karakter huruf.";

    } else {

        $warna = "#fd7e14";
        $bg = "#fff4e8";
        $status = "BERHASIL";
        $judul = "Skenario 3 : Kombinasi Karakter dan Angka";

        $hasil = "Data kombinasi berhasil diproses: \"$input\"";
        $keterangan = "Input mengandung kombinasi huruf dan angka dalam satu data.";
    }

    $waktu = date('d-m-Y H:i:s');
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Form Pendaftaran Mahasiswa Baru USTI</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:'Segoe UI',sans-serif;
    background:linear-gradient(135deg,#f8fafc,#e0f2fe);
    padding:30px 15px;
}

.container{
    max-width:900px;
    margin:auto;
}

.header{
    background:linear-gradient(135deg,#2563eb,#06b6d4);
    color:white;
    padding:25px;
    border-radius:20px 20px 0 0;
    font-size:32px;
    font-weight:bold;
    text-align:center;
    box-shadow:0 5px 20px rgba(37,99,235,.25);
}

.card{
    background:white;
    padding:30px;
    border-radius:0 0 20px 20px;
    box-shadow:0 5px 20px rgba(0,0,0,.08);
}

label{
    display:block;
    margin-bottom:10px;
    font-size:20px;
    font-weight:600;
    color:#1e40af;
}

input{
    width:100%;
    padding:16px;
    border:2px solid #d5dceb;
    border-radius:12px;
    font-size:18px;
    transition:.3s;
}

input:focus{
    outline:none;
    border-color:#2563eb;
    box-shadow:0 0 0 4px rgba(37,99,235,.15);
}

button{
    width:100%;
    margin-top:25px;
    padding:16px;
    border:none;
    border-radius:12px;
    background:linear-gradient(135deg,#2563eb,#06b6d4);
    color:white;
    font-size:22px;
    font-weight:bold;
    cursor:pointer;
    transition:.3s;
}

button:hover{
    transform:translateY(-2px);
    box-shadow:0 5px 15px rgba(37,99,235,.3);
}

.result-card{
    margin-top:30px;
    background:white;
    border-left:7px solid var(--warna);
    border-radius:18px;
    padding:25px;
    box-shadow:0 5px 20px rgba(0,0,0,.08);
}

.badge{
    display:inline-block;
    background:var(--warna);
    color:white;
    padding:10px 18px;
    border-radius:30px;
    font-weight:bold;
    margin-bottom:15px;
}

.scenario{
    color:#475569;
    font-weight:600;
    margin-left:10px;
}

.result-title{
    margin-top:15px;
    color:var(--warna);
    font-size:30px;
    font-weight:bold;
}

.info-box{
    margin-top:20px;
    background:linear-gradient(135deg,#f8fafc,#eef6ff);
    border:1px solid #dbeafe;
    border-left:6px solid var(--warna);
    padding:22px;
    border-radius:16px;
    line-height:1.8;
    box-shadow:0 3px 10px rgba(0,0,0,.05);
}

.info-box h3{
    display:flex;
    align-items:center;
    gap:8px;
    margin-bottom:15px;
    color:#1e3a8a;
    font-size:18px;
    font-weight:700;
    text-transform:uppercase;
    letter-spacing:.5px;
}

.info-box p{
    color:#334155;
    font-size:15px;
    text-align:justify;
    margin-bottom:15px;
}

.table-info{
    margin-top:15px;
    display:grid;
    gap:12px;
}

.table-info div{
    background:white;
    padding:12px 15px;
    border-radius:10px;
    border:1px solid #e2e8f0;
    color:#475569;
}

.status{
    margin-top:20px;
    font-size:28px;
    font-weight:bold;
    color:var(--warna);
}

.time{
    margin-top:10px;
    color:#777;
}


</style>

</head>

<body>

<div class="container">

    <div class="header">
        🏠 Form Pendaftaran Mahasiswa Baru USTI
    </div>

    <div class="card">

        <form method="POST">

            <label>Nama Calon Mahasiswa :</label>

            <input
                type="text"
                name="nama"
                autocomplete="off"
                placeholder="Masukkan data..."
                value="<?= isset($_POST['nama']) ? htmlspecialchars($_POST['nama']) : '' ?>"
            >

            <button type="submit">
                SAVE
            </button>

        </form>

        <?php if($showResult): ?>

        <div class="result-card"
             style="--warna:<?= $warna ?>; --bg:<?= $bg ?>;">

            <span class="badge">
                <?= $status ?>
            </span>

            <span class="scenario">
                <?= $judul ?>
            </span>

            <div class="result-title">
                <?= $hasil ?>
            </div>

            <div class="info-box">

                <h3>🔍 DETAIL HASIL PENGUJIAN</h3>

                <p><?= $keterangan ?></p>

                <div class="table-info">

                    <div>
                        <strong>Cara Uji :</strong>
                        Ketik data pada kolom input kemudian klik tombol SAVE.
                    </div>

                    <div>
                        <strong>Warna Tampilan :</strong>
                        <?= $warna ?>
                    </div>

                </div>

            </div>

            <div class="status">
                ✓ Status Pengujian : <?= $status ?>
            </div>

            <div class="time">
                🕒 Waktu Pengujian : <?= $waktu ?>
            </div>

        </div>

        <?php endif; ?>


    </div>

</div>

</body>
</html>