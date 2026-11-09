<?php
session_start();

// ========================================
// FUNGSI BANTU
// ========================================

function e($data) {
    return htmlspecialchars((string) $data, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
}

function panjangTeks($teks) {
    return function_exists("mb_strlen")
        ? mb_strlen($teks, "UTF-8")
        : strlen($teks);
}

function tanggalValid($tanggal) {
    if (!is_string($tanggal) || $tanggal === "") {
        return false;
    }

    $objek = DateTime::createFromFormat("!Y-m-d", $tanggal);
    $error = DateTime::getLastErrors();

    return $objek !== false &&
        ($error === false ||
            ($error["warning_count"] === 0 && $error["error_count"] === 0)) &&
        $objek->format("Y-m-d") === $tanggal;
}

// ========================================
// MEMPROSES FORM RENCANA KEGIATAN
// ========================================

if (
    $_SERVER["REQUEST_METHOD"] === "POST" &&
    ($_POST["form_type"] ?? "") === "rencana"
) {
    $namaKegiatan = $_POST["nama_kegiatan"] ?? "";
    $kategori = $_POST["kategori"] ?? "";
    $tanggalKegiatan = $_POST["tanggal_kegiatan"] ?? "";
    $lokasi = $_POST["lokasi"] ?? "";
    $deskripsi = $_POST["deskripsi"] ?? "";

    $namaKegiatan = is_string($namaKegiatan) ? trim($namaKegiatan) : "";
    $kategori = is_string($kategori) ? trim($kategori) : "";
    $tanggalKegiatan = is_string($tanggalKegiatan) ? trim($tanggalKegiatan) : "";
    $lokasi = is_string($lokasi) ? trim($lokasi) : "";
    $deskripsi = is_string($deskripsi) ? trim($deskripsi) : "";

    $namaKegiatan = preg_replace('/\s+/u', ' ', $namaKegiatan);
    $lokasi = preg_replace('/\s+/u', ' ', $lokasi);
    $deskripsi = str_replace(["\r\n", "\r"], "\n", $deskripsi);

    $errors = [];

    if (panjangTeks($namaKegiatan) < 3 || panjangTeks($namaKegiatan) > 100) {
        $errors[] = "Nama kegiatan harus 3 sampai 100 karakter.";
    }

    $kategoriValid = ["Akademik", "Organisasi", "Olahraga", "Sosial", "Lainnya"];

    if (!in_array($kategori, $kategoriValid, true)) {
        $errors[] = "Kategori kegiatan tidak valid.";
    }

    if (!tanggalValid($tanggalKegiatan)) {
        $errors[] = "Tanggal kegiatan tidak valid.";
    }

    if (panjangTeks($lokasi) < 3 || panjangTeks($lokasi) > 150) {
        $errors[] = "Lokasi harus 3 sampai 150 karakter.";
    }

    if ($deskripsi === "" || panjangTeks($deskripsi) > 500) {
        $errors[] = "Deskripsi wajib diisi dan maksimal 500 karakter.";
    }

    if (!empty($errors)) {
        $_SESSION["rencana_errors"] = $errors;
        header("Location: index.php#rencana-kegiatan");
        exit;
    }

    $_SESSION["rencana_kegiatan"] = [
        "nama_kegiatan" => $namaKegiatan,
        "kategori" => $kategori,
        "tanggal_kegiatan" => $tanggalKegiatan,
        "lokasi" => $lokasi,
        "deskripsi" => $deskripsi
    ];

    unset($_SESSION["rencana_errors"]);

    header("Location: index.php#rencana-kegiatan");
    exit;
}

// ========================================
// MEMPROSES FORM KONTAK
// ========================================

if (
    $_SERVER["REQUEST_METHOD"] === "POST" &&
    ($_POST["form_type"] ?? "") === "contact"
) {
    $nama = $_POST["nama"] ?? "";
    $email = $_POST["email"] ?? "";
    $semesterForm = $_POST["semester"] ?? "";
    $tanggal = $_POST["tanggal"] ?? "";
    $jenisPesan = $_POST["jenis_pesan"] ?? "";
    $minat = $_POST["minat"] ?? [];
    $prodi = $_POST["prodi"] ?? "";
    $pesan = $_POST["pesan"] ?? "";

    // Memastikan data berupa teks
    $nama = is_string($nama) ? trim($nama) : "";
    $email = is_string($email) ? trim($email) : "";
    $semesterForm = is_string($semesterForm) ? trim($semesterForm) : "";
    $tanggal = is_string($tanggal) ? trim($tanggal) : "";
    $jenisPesan = is_string($jenisPesan) ? trim($jenisPesan) : "";
    $prodi = is_string($prodi) ? trim($prodi) : "";
    $pesan = is_string($pesan) ? trim($pesan) : "";

    // Normalisasi data
    $nama = preg_replace('/\s+/u', ' ', $nama);
    $email = strtolower($email);
    $pesan = str_replace(["\r\n", "\r"], "\n", $pesan);

    // Memeriksa pilihan minat
    $minatValid = ["HTML", "CSS"];

    if (!is_array($minat)) {
        $minat = [];
    }

    $minatBersih = [];

    foreach ($minat as $pilihan) {
        if (is_string($pilihan) && in_array($pilihan, $minatValid, true)) {
            $minatBersih[] = $pilihan;
        }
    }

    $minat = array_values(array_unique($minatBersih));

    $errors = [];

    // Validasi nama
    if (panjangTeks($nama) < 3 || panjangTeks($nama) > 50) {
        $errors[] = "Nama harus 3 sampai 50 karakter.";
    }

    // Validasi email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Format email tidak valid.";
    }

    // Validasi semester
    $semesterAngka = filter_var(
        $semesterForm,
        FILTER_VALIDATE_INT,
        ["options" => ["min_range" => 1, "max_range" => 14]]
    );

    if ($semesterAngka === false) {
        $errors[] = "Semester harus berupa angka 1 sampai 14.";
    }

    // Validasi tanggal
    if (!tanggalValid($tanggal)) {
        $errors[] = "Tanggal kunjungan tidak valid.";
    }

    // Validasi jenis pesan
    if (!in_array($jenisPesan, ["pertanyaan", "saran"], true)) {
        $errors[] = "Silakan pilih jenis pesan.";
    }

    // Validasi minat
    if (empty($minat)) {
        $errors[] = "Pilih minimal satu topik yang diminati.";
    }

    // Validasi program studi
    if (!in_array($prodi, ["SI", "TI"], true)) {
        $errors[] = "Program studi tidak valid.";
    }

    // Validasi pesan
    if ($pesan === "" || panjangTeks($pesan) > 300) {
        $errors[] = "Pesan wajib diisi dan maksimal 300 karakter.";
    }

    // Jika data tidak valid
    if (!empty($errors)) {
        $_SESSION["contact_errors"] = $errors;
        header("Location: index.php#contact");
        exit;
    }

    // SIMPAN HASIL KONTAK DALAM SATU SESSION
    $_SESSION["contact_data"] = [
        "nama" => $nama,
        "email" => $email,
        "semester" => $semesterForm,
        "tanggal" => $tanggal,
        "jenis_pesan" => $jenisPesan,
        "minat" => $minat,
        "prodi" => $prodi,
        "pesan" => $pesan
    ];

    unset($_SESSION["contact_errors"]);

    // PRG (Post - Redirect - Get)
    header("Location: index.php#contact");
    exit;
}

// ========================================
// DATA PROFIL MAHASISWA
// ========================================

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

// Operator
$batasSemester = 8;
$sisaSemester = $batasSemester - $semester;
$semesterValid = $semester >= 1 && $semester <= $batasSemester;

$statusSemester = ($semesterValid && $statusAktif)
    ? "Sesuai rentang"
    : "Perlu diperiksa";

// Percabangan if-elseif-else
if ($semester < 3) {
    $keteranganSemester = "Semester awal";
} elseif ($semester <= 6) {
    $keteranganSemester = "Semester menengah";
} else {
    $keteranganSemester = "Semester akhir";
}

$namaProdi = ($programStudi == "SI")
    ? "Sistem Informasi"
    : (($programStudi == "TI") ? "Teknik Informatika" : "Lainnya");

// Switch-case-default
switch ($programStudi) {
    case "SI":
        $fokusProgramStudi = "Sistem informasi dan proses bisnis";
        break;

    case "TI":
        $fokusProgramStudi = "Komputasi dan pengembangan perangkat lunak";
        break;

    default:
        $fokusProgramStudi = "Belum ditentukan";
}
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

    <!-- BERANDA -->
    <section id="home">
        <h2>Selamat Datang</h2>

        <p>
            Halo, saya <strong><?= e($namaMahasiswa); ?></strong>.
            Saya sedang mempelajari mata kuliah
            <strong><?= e(NAMA_MATA_KULIAH); ?></strong>
            pada tahun akademik <?= e($tahunAkademik); ?>.
        </p>

        <img src="img/foto-profil.jpg" alt="Foto profil Nama Mahasiswa" />
    </section>

    <!-- TENTANG SAYA -->
    <section id="about">
        <h2>Tentang Saya</h2>

        <article>
            <h3>Profil Singkat</h3>

            <p>Nama: <?= e($namaMahasiswa); ?></p>
            <p>NIM: <?= e($nim); ?></p>
            <p>Program Studi: <?= e($namaProdi . " (" . $programStudi . ")"); ?></p>
            <p>Fokus program studi: <?= e($fokusProgramStudi); ?></p>
            <p>Status mahasiswa: <?= $statusAktif ? "Aktif" : "Tidak aktif"; ?></p>
            <p>Semester: <?= e($semester . " (" . $keteranganSemester . ")"); ?></p>
            <p>Sisa semester hingga batas: <?= e($sisaSemester); ?></p>
            <p>Pemeriksaan rentang semester: <?= e($statusSemester); ?></p>
            <p>IPK: <?= e(number_format($ipk, 2, ',', '.')); ?></p>

            <p>
                Catatan tambahan:
                <?= $catatanTambahan === null ? "Belum ada" : e($catatanTambahan); ?>
            </p>

            <h3>Target Belajar (Perulangan for)</h3>
            <ol>
                <?php
                for ($i = 1; $i <= 5; $i++) {
                    echo "<li>Target belajar ke-" . e($i) . ": ";

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
                    echo "<li>Semester " . e($nomorSemester) . "</li>";
                    $nomorSemester++;
                }
                ?>
            </ol>
        </article>
    </section>

    <!-- FORM RENCANA KEGIATAN -->
    <section id="rencana-kegiatan">
        <h2>Rencana Kegiatan Mahasiswa</h2>

        <?php if (!empty($_SESSION["rencana_errors"])): ?>
            <div class="form-feedback">
                <strong>Periksa kembali data Anda:</strong>
                <ul>
                    <?php foreach ($_SESSION["rencana_errors"] as $error): ?>
                        <li><?= e($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php unset($_SESSION["rencana_errors"]); ?>
        <?php endif; ?>

        <form id="rencanaForm" action="index.php" method="post" novalidate>
            <input type="hidden" name="form_type" value="rencana">

            <div class="form-group">
                <label for="nama_kegiatan">Nama Kegiatan</label><br>
                <input class="input-form" type="text" id="nama_kegiatan"
                    name="nama_kegiatan" placeholder="Masukkan nama kegiatan"
                    minlength="3" maxlength="100" required>
            </div>

            <div class="form-group">
                <label for="kategori">Kategori Kegiatan</label><br>
                <select class="input-form" id="kategori" name="kategori" required>
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
                <input class="input-form" type="date" id="tanggal_kegiatan"
                    name="tanggal_kegiatan" required>
            </div>

            <div class="form-group">
                <label for="lokasi">Lokasi Kegiatan</label><br>
                <input class="input-form" type="text" id="lokasi"
                    name="lokasi" placeholder="Masukkan lokasi kegiatan"
                    minlength="3" maxlength="150" required>
            </div>

            <div class="form-group">
                <label for="deskripsi">Deskripsi Kegiatan</label><br>
                <textarea class="input-form" id="deskripsi" name="deskripsi"
                    maxlength="500" placeholder="Jelaskan rencana kegiatan Anda"
                    required></textarea>
            </div>

            <button type="submit">Kirim</button>
            <button type="reset">Batal</button>

            <p class="form-feedback" data-form-feedback aria-live="polite"></p>
        </form>

        <?php if (isset($_SESSION["rencana_kegiatan"])): ?>
            <article id="hasilRencana" class="submission-result" aria-live="polite">
                <h3>Hasil Rencana Kegiatan</h3>

                <p><strong>Nama Kegiatan:</strong>
                    <?= e($_SESSION["rencana_kegiatan"]["nama_kegiatan"]); ?></p>

                <p><strong>Kategori:</strong>
                    <?= e($_SESSION["rencana_kegiatan"]["kategori"]); ?></p>

                <p><strong>Tanggal Pelaksanaan:</strong>
                    <?= e($_SESSION["rencana_kegiatan"]["tanggal_kegiatan"]); ?></p>

                <p><strong>Lokasi:</strong>
                    <?= e($_SESSION["rencana_kegiatan"]["lokasi"]); ?></p>

                <p><strong>Deskripsi:</strong>
                    <?= nl2br(e($_SESSION["rencana_kegiatan"]["deskripsi"])); ?></p>

                <p><strong>Status:</strong> Rencana kegiatan berhasil dikirim.</p>
            </article>
        <?php endif; ?>
    </section>

    <!-- FORM KONTAK -->
    <section id="contact">
        <h2>Kontak</h2>

        <?php if (!empty($_SESSION["contact_errors"])): ?>
            <div class="form-feedback">
                <strong>Periksa kembali data Anda:</strong>
                <ul>
                    <?php foreach ($_SESSION["contact_errors"] as $error): ?>
                        <li><?= e($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php unset($_SESSION["contact_errors"]); ?>
        <?php endif; ?>

        <p>
            Kunjungi
            <a target="_blank" rel="noopener noreferrer"
               href="https://github.com/<?= e(PROFIL_GITHUB); ?>">
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

            <input type="hidden" name="form_type" value="contact">

            <div class="form-group">
                <label for="nama">Nama Lengkap</label><br />
                <input class="input-form" type="text" id="nama" name="nama"
                    placeholder="Masukkan nama lengkap"
                    minlength="3" maxlength="50" required />
            </div>

            <div class="form-group">
                <label for="email">Email</label><br />
                <input class="input-form" type="email" id="email" name="email"
                    placeholder="nama@email.com" required />
            </div>

            <div class="form-group">
                <label for="semester">Semester</label><br />
                <input class="input-form" type="number" id="semester"
                    name="semester" min="1" max="14" required />
            </div>

            <div class="form-group">
                <label for="tanggal">Tanggal Kunjungan</label><br />
                <input class="input-form" type="date" id="tanggal"
                    name="tanggal" required />
            </div>

            <div class="form-group">
                <p>Jenis Pesan</p>

                <input type="radio" id="pertanyaan" name="jenis_pesan"
                    value="pertanyaan" required />
                <label for="pertanyaan">Pertanyaan</label>

                <input type="radio" id="saran" name="jenis_pesan" value="saran" />
                <label for="saran">Saran</label>
            </div>

            <div class="form-group">
                <p>Topik yang Diminati</p>

                <input type="checkbox" id="minat-html" name="minat[]" value="HTML" />
                <label for="minat-html">HTML</label>

                <input type="checkbox" id="minat-css" name="minat[]" value="CSS" />
                <label for="minat-css">CSS</label>
            </div>

            <div class="form-group">
                <label for="prodi">Program Studi</label><br />
                <select class="input-form" id="prodi" name="prodi" required>
                    <option value="">-- Pilih Program Studi --</option>
                    <option value="SI">Sistem Informasi</option>
                    <option value="TI">Teknik Informatika</option>
                </select>
            </div>

            <div class="form-group">
                <label for="pesan">Pesan</label><br />
                <textarea class="input-form" id="pesan" name="pesan"
                    maxlength="300" placeholder="Tulis pesan Anda"
                    required></textarea>
            </div>

            <button type="submit">Kirim</button>
            <button type="reset">Batal</button>

            <p class="form-feedback" data-form-feedback aria-live="polite"></p>
        </form>

        <!-- HASIL FORM KONTAK -->
        <?php if (!empty($_SESSION["contact_data"])): ?>
            <?php $hasilKontak = $_SESSION["contact_data"]; ?>

            <article id="hasilKontak" class="submission-result" aria-live="polite">
                <h3>Data Formulir yang Dikirim</h3>

                <p><strong>Nama Lengkap:</strong>
                    <?= e($hasilKontak["nama"]); ?></p>

                <p><strong>Email:</strong>
                    <?= e($hasilKontak["email"]); ?></p>

                <p><strong>Semester:</strong>
                    <?= e($hasilKontak["semester"]); ?></p>

                <p><strong>Tanggal Kunjungan:</strong>
                    <?= e($hasilKontak["tanggal"]); ?></p>

                <p><strong>Jenis Pesan:</strong>
                    <?= e($hasilKontak["jenis_pesan"]); ?></p>

                <p><strong>Topik yang Diminati:</strong>
                    <?= e(implode(", ", $hasilKontak["minat"])); ?></p>

                <p><strong>Program Studi:</strong>
                    <?= e($hasilKontak["prodi"]); ?></p>

                <p><strong>Pesan:</strong>
                    <?= nl2br(e($hasilKontak["pesan"])); ?></p>

                <p><strong>Status:</strong> Formulir berhasil dikirim.</p>
            </article>
        <?php endif; ?>

    </section>

</main>

<footer>
    <p>
        &copy; <?= date("Y"); ?>
        <?= e($namaMahasiswa); ?>
        [<?= e($nim); ?>]
    </p>
</footer>

<script src="script.js"></script>

</body>
</html>