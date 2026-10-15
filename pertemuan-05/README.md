P5 - JavaScript Dasar, DOM, Event, dan Validasi Sisi Klien

Deskripsi

Dokumentasi pengembangan halaman web profil mahasiswa pada Pertemuan 5, meliputi JavaScript, DOM, event listener, interaksi navigasi, dan validasi formulir sisi klien.

Fitur dan Pengembangan

1. Navigasi Responsif

Menambahkan tombol navigasi menggunakan JavaScript untuk membuka dan menutup menu navigasi. Status menu diperbarui melalui class CSS "nav-open" dan atribut "aria-expanded".

2. Validasi Formulir

Menambahkan validasi formulir menggunakan JavaScript untuk memeriksa nama lengkap, email, semester, tanggal kunjungan, jenis pesan, topik yang diminati, program studi, dan isi pesan.

3. Umpan Balik Formulir

Menampilkan pesan kesalahan ketika data belum sesuai dan pesan keberhasilan ketika seluruh validasi berhasil. Pesan ditampilkan menggunakan elemen "formFeedback".

4. Reset Formulir

Menambahkan event listener untuk menghapus pesan umpan balik ketika formulir direset atau pengguna mulai mengubah input.

Pengujian

1. Pengujian Navigasi

Langkah: Klik tombol navigasi pada tampilan layar kecil.

Hasil yang diharapkan: Menu navigasi dapat dibuka dan ditutup.

2. Pengujian Validasi Formulir

Langkah: Kirim formulir dengan data kosong atau data yang tidak sesuai.

Hasil yang diharapkan: Sistem menampilkan pesan kesalahan sesuai bagian yang belum valid.

3. Pengujian Formulir Valid

Langkah: Isi seluruh kolom wajib dengan data yang sesuai, pilih jenis pesan dan minimal satu topik, lalu klik tombol Kirim.

Hasil yang diharapkan: Pesan "Form berhasil divalidasi." ditampilkan.

4. Pengujian Reset Formulir

Langkah: Isi beberapa kolom formulir, kemudian klik tombol Batal.

Hasil yang diharapkan: Formulir kembali ke kondisi awal dan pesan umpan balik dihapus.

Catatan: Hasil pengujian di atas merupakan hasil yang diharapkan. Sesuaikan dengan hasil aktual setelah menjalankan dan menguji halaman web.

Riwayat Pengembangan

- Menyiapkan struktur HTML profil mahasiswa dari pertemuan sebelumnya.
- Memperbarui HTML untuk mendukung tombol navigasi, formulir dengan validasi sisi klien, dan pemanggilan file JavaScript.
- Memperbarui CSS untuk mendukung navigasi responsif dan tampilan pesan validasi.
- Menambahkan JavaScript untuk interaksi navigasi dan pemeriksaan data formulir.

Contoh Pesan Commit

- "feat: menambahkan navigasi responsif dengan JavaScript"
- "feat: menambahkan validasi formulir sisi klien"
- "style: menyesuaikan CSS untuk navigasi dan umpan balik formulir"
- "docs: memperbarui README pertemuan 05"