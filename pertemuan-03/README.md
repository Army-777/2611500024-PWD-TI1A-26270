# Pertemuan 3 - Formulir HTML dan CSS Dasar

## Baseline
- Menggunakan hasil P2 sebagai dasar pengembangan P3.
- Menyalin `index.html` dan `img/foto-profil.jpg` ke `pertemuan-03/`.

## Implementasi Formulir
- Elemen form yang digunakan: `<form>`, `<label>`, `<input>`, `<select>`, `<option>`, `<textarea>`, `<button>`.
- Tipe input yang digunakan: text, email, number, date, radio, checkbox.
- Atribut validasi yang digunakan: `required`, `minlength="3"`, `maxlength="50"`, `min="1"`, `max="14"`, `maxlength="300"`.
## Pengujian GET dan POST
- Hasil pengujian GET: data formulir berhasil dikirim dan ditambahkan langsung pada URL sebagai query string dengan format name=value.
- Contoh URL encoding yang ditemukan: Karakter spasi diubah menjadi + (Army+777) dan karakter @ diubah menjadi %40 (2611500024%40mahasiswa.atmaluhur.ac.id)
- Hasil pengujian POST: Data dikirim melalui HTTP request body tanpa muncul pada bilah URL. Pengujian pada GitHub Pages menghasilkan respons 405 Not Allowed karena GitHub Pages merupakan layanan hosting statis tanpa skrip pemrosesan sisi peladen (PHP).

## CSS Dasar
- Selector elemen: h2, h3, p, ol, label, dan button.
- Selector class: .form-group dan .input-form
- Selector ID: #about dan #contact.
- Properti CSS dasar yang digunakan: color, background-color, font-family, font-size, font-weight, margin, padding, border, border-bottom, dan cursor.

## Pengujian dan Perbaikan
- Galat yang ditemukan: tidak ada galat sintaks maupun tampilan yang ditemukan selama proses pengujian di peramban
- Penyebab galat: seluruh tag HTML, atribut validasi, dan selector CSS sudah disusun sesuai acuan modul praktikum sejak awal
- Perbaikan yang dilakukan: tidak melakukan perubahan struktur kode karena seluruh fungsi formulir sudah berjalan lancar
- Hasil pengujian ulang: seluruh elemen formulir, pengiriman data GET/POST, dan penataan CSS dasar lulus pengujian tanpa kendala.

## GitHub Pages
URL: https://army-777.github.io/2611500024-PWD-TI1A-26270/pertemuan-03/