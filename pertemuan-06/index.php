<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Mahasiswa - PWD P6</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="header-top">
            <h1>Profil Mahasiswa</h1>
            <button class="nav-toggle" id="navToggle" type="button"
                aria-label="Buka navigasi" aria-controls="navMenu"
                aria-expanded="false">&#9776;</button>
        </div>
        <nav>
            <ul id="navMenu">
                <li><a href="#home">Beranda</a></li>
                <li><a href="#about">Tentang</a></li>
                <li><a href="#php-dasar">PHP Dasar</a></li>
                <li><a href="#contact">Kontak</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section id="home">
            <h2>Selamat Datang</h2>
            <p>
                <?php
                $nama = "Army Steven Lubis";
                echo "Halo, saya " . $nama .
                    ". Saya sedang mempelajari Pemrograman Web Dasar.";
                ?>
            </p>
            <img src="img/foto-profil.jpg"
                alt="Foto profil Army Steven Lubis">
        </section>

        <section id="about">
            <h2>Tentang Saya</h2>
            <article>
                <h3>Profil Singkat</h3>
                <p>Saya mahasiswa Program Teknik Informatika.</p>
                <h3>Target Belajar</h3>
                <ol>
                    <li>Memahami struktur HTML5.</li>
                    <li>Menyusun halaman web semantik.</li>
                    <li>Memvalidasi dokumen HTML.</li>
                </ol>
            </article>
        </section>

        <section id="php-dasar">
            <h2>Praktik PHP Dasar</h2>

            <h3>1. Echo dan Print</h3>
            <?php
            echo "<p>Ini adalah output menggunakan echo.</p>";
            print "<p>Ini adalah output menggunakan print.</p>";
            ?>

            <h3>2. Variabel dan Tipe Data</h3>
            <?php
            $namaMahasiswa = "Army Steven Lubis";
            $umur = 18;
            $ipk = 3.75;
            $mahasiswaAktif = true;

            echo "<p>Nama: " . $namaMahasiswa . "</p>";
            echo "<p>Umur: " . $umur . " tahun</p>";
            echo "<p>IPK: " . $ipk . "</p>";
            echo "<p>Status aktif: ";
            echo $mahasiswaAktif ? "Ya" : "Tidak";
            echo "</p>";

            echo "<p>Tipe nama: " . gettype($namaMahasiswa) . "</p>";
            echo "<p>Tipe umur: " . gettype($umur) . "</p>";
            echo "<p>Tipe IPK: " . gettype($ipk) . "</p>";
            echo "<p>Tipe status: " . gettype($mahasiswaAktif) . "</p>";
            ?>

            <h3>3. Konstanta</h3>
            <?php
            define("NAMA_KAMPUS", "ISB ATMA LUHUR");
            echo "<p>Konstanta nama kampus: " . NAMA_KAMPUS . "</p>";
            ?>

            <h3>4. Operator Aritmetika</h3>
            <?php
            $angka1 = 10;
            $angka2 = 3;

            echo "<p>Angka pertama: " . $angka1 . "</p>";
            echo "<p>Angka kedua: " . $angka2 . "</p>";
            echo "<p>Penjumlahan: " . ($angka1 + $angka2) . "</p>";
            echo "<p>Pengurangan: " . ($angka1 - $angka2) . "</p>";
            echo "<p>Perkalian: " . ($angka1 * $angka2) . "</p>";
            echo "<p>Pembagian: " . ($angka1 / $angka2) . "</p>";
            echo "<p>Sisa bagi: " . ($angka1 % $angka2) . "</p>";
            ?>

            <h3>5. Operator Perbandingan dan Logika</h3>
            <?php
            $nilai = 85;
            $hadir = true;

            echo "<p>Nilai: " . $nilai . "</p>";
            echo "<p>Nilai >= 75: ";
            var_export($nilai >= 75);
            echo "</p>";

            echo "<p>Lulus dan hadir: ";
            var_export($nilai >= 75 && $hadir);
            echo "</p>";
            ?>

            <h3>6. Percabangan If, Elseif, dan Else</h3>
            <?php
            if ($nilai >= 85) {
                echo "<p>Predikat: A</p>";
            } elseif ($nilai >= 75) {
                echo "<p>Predikat: B</p>";
            } elseif ($nilai >= 60) {
                echo "<p>Predikat: C</p>";
            } else {
                echo "<p>Predikat: D</p>";
            }
            ?>

            <h3>7. Percabangan Switch</h3>
            <?php
            $hari = date("N");

            switch ($hari) {
                case 1:
                    $namaHari = "Senin";
                    break;
                case 2:
                    $namaHari = "Selasa";
                    break;
                case 3:
                    $namaHari = "Rabu";
                    break;
                case 4:
                    $namaHari = "Kamis";
                    break;
                case 5:
                    $namaHari = "Jumat";
                    break;
                case 6:
                    $namaHari = "Sabtu";
                    break;
                default:
                    $namaHari = "Minggu";
            }

            echo "<p>Hari ini: " . $namaHari . "</p>";
            ?>

            <h3>8. Perulangan For</h3>
            <ul>
                <?php
                for ($i = 1; $i <= 5; $i++) {
                    echo "<li>Perulangan for ke-" . $i . "</li>";
                }
                ?>
            </ul>

            <h3>9. Perulangan While</h3>
            <ul>
                <?php
                $i = 1;
                while ($i <= 5) {
                    echo "<li>Perulangan while ke-" . $i . "</li>";
                    $i++;
                }
                ?>
            </ul>

            <h3>10. Fungsi Number Format</h3>
            <?php
            $harga = 1500000;
            echo "<p>Harga: Rp " .
                number_format($harga, 0, ",", ".") . "</p>";
            ?>

            <h3>11. Fungsi Date</h3>
            <?php
            date_default_timezone_set("Asia/Jakarta");
            echo "<p>Tanggal: " . date("d-m-Y") . "</p>";
            echo "<p>Waktu: " . date("H:i:s") . "</p>";
            ?>
        </section>

        <section id="contact">
            <h2>Kontak</h2>
            <p>
                Kunjungi
                <a href="https://github.com/Army-777">profil GitHub saya</a>.
            </p>

            <form id="contactForm" action="index.php" method="get" novalidate>
                <div class="form-group">
                    <label for="nama">Nama Lengkap</label><br>
                    <input class="input-form" type="text" id="nama"
                        name="nama" placeholder="Masukkan nama lengkap"
                        minlength="3" maxlength="50" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label><br>
                    <input class="input-form" type="email" id="email"
                        name="email" placeholder="nama@email.com" required>
                </div>

                <div class="form-group">
                    <label for="semester">Semester</label><br>
                    <input class="input-form" type="number" id="semester"
                        name="semester" min="1" max="14" required>
                </div>

                <div class="form-group">
                    <label for="tanggal">Tanggal Kunjungan</label><br>
                    <input class="input-form" type="date" id="tanggal"
                        name="tanggal" required>
                </div>

                <div class="form-group">
                    <p><strong>Jenis Pesan</strong></p>
                    <input type="radio" id="pertanyaan" name="jenis_pesan"
                        value="pertanyaan" required>
                    <label for="pertanyaan">Pertanyaan</label>
                    <input type="radio" id="saran" name="jenis_pesan"
                        value="saran">
                    <label for="saran">Saran</label>
                </div>

                <div class="form-group">
                    <p><strong>Topik yang Diminati</strong></p>
                    <input type="checkbox" id="minat-html"
                        name="minat[]" value="HTML">
                    <label for="minat-html">HTML</label>
                    <input type="checkbox" id="minat-css"
                        name="minat[]" value="CSS">
                    <label for="minat-css">CSS</label>
                </div>

                <div class="form-group">
                    <label for="prodi">Program Studi</label><br>
                    <select class="input-form" id="prodi" name="prodi" required>
                        <option value="">-- Pilih Program Studi --</option>
                        <option value="SI">Sistem Informasi</option>
                        <option value="BD">Bisnis Digital</option>
                        <option value="TI">Teknik Informatika</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="pesan">Pesan</label><br>
                    <textarea class="input-form" id="pesan" name="pesan"
                        maxlength="300" placeholder="Tulis pesan Anda"
                        required></textarea>
                </div>

                <button type="submit">Kirim</button>
                <button type="reset">Batal</button>
                <p id="formFeedback" role="status" aria-live="polite"></p>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2026 Army Steven Lubis - 2611500024</p>
    </footer>

    <script src="script.js"></script>
</body>
</html>
