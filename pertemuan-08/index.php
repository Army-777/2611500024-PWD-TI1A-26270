<?php
session_start();

// ==========================================
// PEMROSESAN FORM RENCANA KEGIATAN
// ==========================================

if (
    $_SERVER["REQUEST_METHOD"] === "POST" &&
    ($_POST["form_type"] ?? "") === "rencana"
) {
    // Mengambil dan membersihkan input.
    $namaKegiatan = trim((string) ($_POST["nama_kegiatan"] ?? ""));
    $kategori = trim((string) ($_POST["kategori"] ?? ""));
    $tanggalKegiatan = trim((string) ($_POST["tanggal_kegiatan"] ?? ""));
    $lokasi = trim((string) ($_POST["lokasi"] ?? ""));
    $deskripsi = trim((string) ($_POST["deskripsi"] ?? ""));

    $errors = [];

    // Validasi nama kegiatan.
    if (strlen($namaKegiatan) < 3 || strlen($namaKegiatan) > 100) {
        $errors[] = "Nama kegiatan harus 3 sampai 100 karakter.";
    }

    // Validasi kategori kegiatan.
    $kategoriDiizinkan = [
        "Akademik",
        "Organisasi",
        "Olahraga",
        "Sosial",
        "Lainnya"
    ];

    if (!in_array($kategori, $kategoriDiizinkan, true)) {
        $errors[] = "Kategori kegiatan tidak valid.";
    }

    // Validasi tanggal menggunakan checkdate().
    $tanggalValid = false;

    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggalKegiatan)) {
        $bagianTanggal = explode("-", $tanggalKegiatan);

        $tanggalValid = checkdate(
            (int) $bagianTanggal[1],
            (int) $bagianTanggal[2],
            (int) $bagianTanggal[0]
        );
    }

    if (!$tanggalValid) {
        $errors[] = "Tanggal kegiatan tidak valid.";
    }

    // Validasi lokasi.
    if (strlen($lokasi) < 3 || strlen($lokasi) > 150) {
        $errors[] = "Lokasi harus 3 sampai 150 karakter.";
    }

    // Validasi deskripsi.
    if ($deskripsi === "" || strlen($deskripsi) > 500) {
        $errors[] = "Deskripsi wajib diisi dan maksimal 500 karakter.";
    }

    // Menyimpan data jika valid.
    if (empty($errors)) {
        $_SESSION["rencana_kegiatan"] = [
            "nama_kegiatan" => $namaKegiatan,
            "kategori" => $kategori,
            "tanggal_kegiatan" => $tanggalKegiatan,
            "lokasi" => $lokasi,
            "deskripsi" => $deskripsi
        ];

        unset($_SESSION["rencana_error"]);
    } else {
        $_SESSION["rencana_error"] = $errors;
    }

    // PRG: Post / Redirect / Get.
    header("Location: index.php#rencana-kegiatan");
    exit;
}


// ==========================================
// PEMROSESAN FORM KONTAK
// ==========================================

if (
    $_SERVER["REQUEST_METHOD"] === "POST" &&
    ($_POST["form_type"] ?? "") === "contact"
) {
    $nama = trim((string) ($_POST["nama"] ?? ""));
    $email = trim((string) ($_POST["email"] ?? ""));
    $semesterForm = trim((string) ($_POST["semester"] ?? ""));
    $tanggal = trim((string) ($_POST["tanggal"] ?? ""));
    $jenisPesan = trim((string) ($_POST["jenis_pesan"] ?? ""));
    $minat = $_POST["minat"] ?? [];
    $prodi = trim((string) ($_POST["prodi"] ?? ""));
    $pesan = trim((string) ($_POST["pesan"] ?? ""));

    // Checkbox harus berbentuk array.
    if (!is_array($minat)) {
        $minat = [];
    }

    // Menyimpan data ke session.
    $_SESSION["nama"] = $nama;
    $_SESSION["email"] = $email;
    $_SESSION["semester"] = $semesterForm;
    $_SESSION["tanggal"] = $tanggal;
    $_SESSION["jenis_pesan"] = $jenisPesan;
    $_SESSION["minat"] = $minat;
    $_SESSION["prodi"] = $prodi;
    $_SESSION["pesan"] = $pesan;

    // PRG.
    header("Location: index.php#contact");
    exit;
}


// ==========================================
// DATA PROFIL MAHASISWA
// ==========================================

$nim = "0344300002";
$namaMahasiswa = "Cantika Putih";
$programStudi = "SI";
$tahunAkademik = "2026/2027";
$semester = 4;
$ipk = 3.75;
$statusAktif = true;
$catatanTambahan = null;

const NAMA_MATA_KULIAH = "Pemrograman Web Dasar";
const PROFIL_GITHUB = "satpamal";

// Operator.
$batasSemester = 8;
$sisaSemester = $batasSemester - $semester;
$semesterValid = $semester >= 1 && $semester <= $batasSemester;

$statusSemester = ($semesterValid && $statusAktif)
    ? "Sesuai rentang"
    : "Perlu diperiksa";

// Percabangan if-elseif-else.
if ($semester < 3) {
    $keteranganSemester = "Semester awal";
} elseif ($semester <= 6) {
    $keteranganSemester = "Semester menengah";
} else {
    $keteranganSemester = "Semester akhir";
}

// Operator ternary.
$namaProdi = ($programStudi == "SI")
    ? "Sistem Informasi"
    : (($programStudi == "TI") ? "Teknik Informatika" : "Lainnya");

// Switch-case-default.
switch ($programStudi) {
    case "SI":
        $fokusProgramStudi = "Sistem informasi dan proses bisnis";
        break;

    case "TI":
        $fokusProgramStudi = "Komputasi dan pengembangan perangkat lunak";
        break;

    default:
        $fokusProgramStudi = "Belum ditentukan";
        break;
}

// Mengambil hasil dan pesan kesalahan dari session.
$rencanaData = $_SESSION["rencana_kegiatan"] ?? null;
$rencanaErrors = $_SESSION["rencana_error"] ?? [];

// Hapus pesan kesalahan setelah dibaca.
unset($_SESSION["rencana_error"]);

?>

<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Profil Mahasiswa</title>
    <link rel="stylesheet" href="style.css" />
</head>

<body>

    <header>
        <div class="header-top">
            <h1>Profil Mahasiswa</h1>

            <button
                class="nav-toggle"
                id="navToggle"
                type="button"
                aria-label="Buka navigasi"
                aria-controls="navMenu"
                aria-expanded="false">
                &#9776;
            </button>
        </div>

        <nav>
            <ul id="navMenu">
                <li><a href="#home">Beranda</a></li>
                <li><a href="#about">Tentang</a></li>
                <li><a href="#rencana-kegiatan">Rencana Kegiatan</a></li>
                <li><a href="#contact">Kontak</a></li>
            </ul>
        </nav>
    </header>


    <main>

        <!-- ==================================
             BERANDA
        =================================== -->

        <section id="home">
            <h2>Selamat Datang</h2>

            <p>
                Halo, saya
                <strong><?= htmlspecialchars($namaMahasiswa, ENT_QUOTES, "UTF-8") ?></strong>.

                Saya sedang mempelajari mata kuliah
                <strong><?= htmlspecialchars(NAMA_MATA_KULIAH, ENT_QUOTES, "UTF-8") ?></strong>
                pada tahun akademik
                <?= htmlspecialchars($tahunAkademik, ENT_QUOTES, "UTF-8") ?>.
            </p>

            <img
                src="img/foto-profil.jpg"
                alt="Foto profil mahasiswa" />
        </section>


        <!-- ==================================
             TENTANG SAYA
        =================================== -->

        <section id="about">
            <h2>Tentang Saya</h2>

            <article>
                <h3>Profil Singkat</h3>

                <p>
                    Program Studi:
                    <?= htmlspecialchars($namaProdi . " (" . $programStudi . ")", ENT_QUOTES, "UTF-8") ?>
                </p>

                <p>
                    Fokus program studi:
                    <?= htmlspecialchars($fokusProgramStudi, ENT_QUOTES, "UTF-8") ?>
                </p>

                <p>
                    Status mahasiswa:
                    <?= $statusAktif ? "Aktif" : "Tidak aktif" ?>
                </p>

                <p>
                    Semester:
                    <?= $semester . " (" . $keteranganSemester . ")" ?>
                </p>

                <p>Sisa semester hingga batas: <?= $sisaSemester ?></p>

                <p>
                    Pemeriksaan rentang semester:
                    <?= htmlspecialchars($statusSemester, ENT_QUOTES, "UTF-8") ?>
                </p>

                <p>IPK: <?= number_format($ipk, 2, ',', '.') ?></p>

                <p>
                    Catatan tambahan:
                    <?= $catatanTambahan === null
                        ? "Belum ada"
                        : htmlspecialchars($catatanTambahan, ENT_QUOTES, "UTF-8") ?>
                </p>


                <h3>Target Belajar (Perulangan for)</h3>

                <ol>
                    <?php
                    for ($i = 1; $i <= 5; $i++) {
                        echo "<li>Target belajar ke-" . $i . ": ";

                        switch ($i) {
                            case 1:
                                echo "Memahami sintaks dasar PHP.";
                                break;

                            case 2:
                                echo "Menggunakan variabel dan konstanta.";
                                break;

                            case 3:
                                echo "Menerapkan operator dan percabangan.";
                                break;

                            case 4:
                                echo "Menggunakan perulangan for dan while.";
                                break;

                            default:
                                echo "Belum dipetakan";
                                break;
                        }

                        echo "</li>";
                    }
                    ?>
                </ol>


                <h3>Semester (Perulangan while)</h3>

                <ol>
                    <?php
                    $nomorSemester = 1;

                    while ($nomorSemester <= 3) {
                        echo "<li>Semester " . $nomorSemester . "</li>";
                        $nomorSemester++;
                    }
                    ?>
                </ol>

            </article>
        </section>


        <!-- ==================================
             FORM RENCANA KEGIATAN
        =================================== -->

        <section id="rencana-kegiatan">

            <h2>Rencana Kegiatan Mahasiswa</h2>

            <form
                id="rencanaForm"
                action="index.php"
                method="post">

                <input
                    type="hidden"
                    name="form_type"
                    value="rencana">


                <div class="form-group">
                    <label for="nama_kegiatan">Nama Kegiatan</label><br>

                    <input
                        class="input-form"
                        type="text"
                        id="nama_kegiatan"
                        name="nama_kegiatan"
                        placeholder="Masukkan nama kegiatan"
                        minlength="3"
                        maxlength="100"
                        required>
                </div>


                <div class="form-group">
                    <label for="kategori">Kategori Kegiatan</label><br>

                    <select
                        class="input-form"
                        id="kategori"
                        name="kategori"
                        required>

                        <option value="">-- Pilih Kategori --</option>
                        <option value="Akademik">Akademik</option>
                        <option value="Organisasi">Organisasi</option>
                        <option value="Olahraga">Olahraga</option>
                        <option value="Sosial">Sosial</option>
                        <option value="Lainnya">Lainnya</option>

                    </select>
                </div>


                <div class="form-group">
                    <label for="tanggal_kegiatan">Tanggal Pelaksanaan</label><br>

                    <input
                        class="input-form"
                        type="date"
                        id="tanggal_kegiatan"
                        name="tanggal_kegiatan"
                        required>
                </div>


                <div class="form-group">
                    <label for="lokasi">Lokasi Kegiatan</label><br>

                    <input
                        class="input-form"
                        type="text"
                        id="lokasi"
                        name="lokasi"
                        placeholder="Masukkan lokasi kegiatan"
                        minlength="3"
                        maxlength="150"
                        required>
                </div>


                <div class="form-group">
                    <label for="deskripsi">Deskripsi Kegiatan</label><br>

                    <textarea
                        class="input-form"
                        id="deskripsi"
                        name="deskripsi"
                        maxlength="500"
                        placeholder="Jelaskan rencana kegiatan Anda"
                        required></textarea>
                </div>


                <button type="submit">Kirim</button>
                <button type="reset">Batal</button>

                <p
                    class="form-feedback"
                    data-form-feedback
                    aria-live="polite"></p>

            </form>


            <!-- Menampilkan pesan kesalahan PHP -->

            <?php if (!empty($rencanaErrors)): ?>

                <article class="submission-result">
                    <h3>Terjadi Kesalahan</h3>

                    <ul>
                        <?php foreach ($rencanaErrors as $error): ?>
                            <li>
                                <?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </article>

            <?php endif; ?>


            <!-- Menampilkan kembali data Rencana Kegiatan -->

            <?php if (is_array($rencanaData)): ?>

                <article
                    id="hasilRencana"
                    class="submission-result"
                    aria-live="polite">

                    <h3>Hasil Rencana Kegiatan</h3>

                    <p>
                        <strong>Nama Kegiatan:</strong>
                        <?= htmlspecialchars(
                            $rencanaData["nama_kegiatan"],
                            ENT_QUOTES,
                            "UTF-8"
                        ) ?>
                    </p>

                    <p>
                        <strong>Kategori:</strong>
                        <?= htmlspecialchars(
                            $rencanaData["kategori"],
                            ENT_QUOTES,
                            "UTF-8"
                        ) ?>
                    </p>

                    <p>
                        <strong>Tanggal Pelaksanaan:</strong>
                        <?= htmlspecialchars(
                            $rencanaData["tanggal_kegiatan"],
                            ENT_QUOTES,
                            "UTF-8"
                        ) ?>
                    </p>

                    <p>
                        <strong>Lokasi:</strong>
                        <?= htmlspecialchars(
                            $rencanaData["lokasi"],
                            ENT_QUOTES,
                            "UTF-8"
                        ) ?>
                    </p>

                    <p>
                        <strong>Deskripsi:</strong>
                        <?= nl2br(
                            htmlspecialchars(
                                $rencanaData["deskripsi"],
                                ENT_QUOTES,
                                "UTF-8"
                            )
                        ) ?>
                    </p>

                </article>

            <?php endif; ?>

        </section>


        <!-- ==================================
             FORM KONTAK
        =================================== -->

        <section id="contact">

            <h2>Kontak</h2>

            <p>
                Kunjungi
                <a
                    target="_blank"
                    rel="noopener noreferrer"
                    href="https://github.com/<?= rawurlencode(PROFIL_GITHUB) ?>">
                    profil GitHub saya
                </a>.
            </p>


            <form
                id="contactForm"
                class="js-contact-form"
                data-validation="contact"
                action="index.php"
                method="post"
                novalidate>

                <input
                    type="hidden"
                    name="form_type"
                    value="contact">


                <div class="form-group">
                    <label for="nama">Nama Lengkap</label><br>

                    <input
                        class="input-form"
                        type="text"
                        id="nama"
                        name="nama"
                        placeholder="Masukkan nama lengkap"
                        minlength="3"
                        maxlength="50"
                        required>
                </div>


                <div class="form-group">
                    <label for="email">Email</label><br>

                    <input
                        class="input-form"
                        type="email"
                        id="email"
                        name="email"
                        placeholder="nama@email.com"
                        required>
                </div>


                <div class="form-group">
                    <label for="semester">Semester</label><br>

                    <input
                        class="input-form"
                        type="number"
                        id="semester"
                        name="semester"
                        min="1"
                        max="14"
                        required>
                </div>


                <div class="form-group">
                    <label for="tanggal">Tanggal Kunjungan</label><br>

                    <input
                        class="input-form"
                        type="date"
                        id="tanggal"
                        name="tanggal"
                        required>
                </div>


                <div class="form-group">
                    <p>Jenis Pesan</p>

                    <input
                        type="radio"
                        id="pertanyaan"
                        name="jenis_pesan"
                        value="pertanyaan"
                        required>

                    <label for="pertanyaan">Pertanyaan</label>

                    <input
                        type="radio"
                        id="saran"
                        name="jenis_pesan"
                        value="saran">

                    <label for="saran">Saran</label>
                </div>


                <div class="form-group">
                    <p>Topik yang Diminati</p>

                    <input
                        type="checkbox"
                        id="minat-html"
                        name="minat[]"
                        value="HTML">

                    <label for="minat-html">HTML</label>

                    <input
                        type="checkbox"
                        id="minat-css"
                        name="minat[]"
                        value="CSS">

                    <label for="minat-css">CSS</label>
                </div>


                <div class="form-group">
                    <label for="prodi">Program Studi</label><br>

                    <select
                        class="input-form"
                        id="prodi"
                        name="prodi"
                        required>

                        <option value="">-- Pilih Program Studi --</option>
                        <option value="SI">Sistem Informasi</option>
                        <option value="TI">Teknik Informatika</option>

                    </select>