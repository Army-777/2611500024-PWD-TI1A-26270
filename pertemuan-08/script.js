
// Navigasi responsif.
const navToggle = document.getElementById("navToggle");
const navMenu = document.getElementById("navMenu");

if (navToggle && navMenu) {
    navToggle.addEventListener("click", () => {
        const isOpen = navMenu.classList.toggle("nav-open");

        navToggle.setAttribute("aria-expanded", String(isOpen));
        navToggle.setAttribute(
            "aria-label",
            isOpen ? "Tutup navigasi" : "Buka navigasi"
        );
    });
}

// Fungsi untuk menampilkan pesan kesalahan.
function tampilkanKesalahan(formFeedback, pesanKesalahan) {
    if (formFeedback) {
        formFeedback.textContent = pesanKesalahan;
        formFeedback.className = "form-feedback-error";
    }
}

// Fungsi untuk menghapus pesan kesalahan.
function hapusFeedback(formFeedback) {
    if (formFeedback) {
        formFeedback.textContent = "";
        formFeedback.className = "form-feedback";
    }
}

// VALIDASI FORM KONTAK.
const contactForms = document.querySelectorAll(
    'form[data-validation="contact"]'
);

contactForms.forEach((contactForm) => {
    const formFeedback = contactForm.querySelector("[data-form-feedback]");

    const ambilInput = (nama) =>
        contactForm.elements.namedItem(nama);

    contactForm.addEventListener("submit", (event) => {
        event.preventDefault();

        const namaInput = ambilInput("nama");
        const emailInput = ambilInput("email");
        const semesterInput = ambilInput("semester");
        const tanggalInput = ambilInput("tanggal");
        const prodiInput = ambilInput("prodi");
        const pesanInput = ambilInput("pesan");

        const jenisPesan = contactForm.querySelector(
            'input[name="jenis_pesan"]:checked'
        );

        const topikDipilih = contactForm.querySelectorAll(
            'input[name="minat[]"]:checked'
        );

        const nama = namaInput.value.trim();
        const email = emailInput.value.trim();
        const semester = Number(semesterInput.value);
        const tanggal = tanggalInput.value;
        const prodi = prodiInput.value;
        const pesan = pesanInput.value.trim();

        hapusFeedback(formFeedback);

        if (nama.length < 3 || nama.length > 50) {
            tampilkanKesalahan(
                formFeedback,
                "Nama lengkap harus diisi 3 sampai 50 karakter."
            );
            return;
        }

        // Memeriksa format email.
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!emailPattern.test(email)) {
            tampilkanKesalahan(
                formFeedback,
                "Masukkan alamat email yang valid."
            );
            return;
        }

        if (
            semesterInput.value.trim() === "" ||
            !Number.isInteger(semester) ||
            semester < 1 ||
            semester > 14
        ) {
            tampilkanKesalahan(
                formFeedback,
                "Semester harus diisi dengan angka 1 sampai 14."
            );
            return;
        }

        if (tanggal === "") {
            tampilkanKesalahan(
                formFeedback,
                "Tanggal kunjungan harus diisi."
            );
            return;
        }

        if (!jenisPesan) {
            tampilkanKesalahan(
                formFeedback,
                "Pilih jenis pesan."
            );
            return;
        }

        if (topikDipilih.length === 0) {
            tampilkanKesalahan(
                formFeedback,
                "Pilih minimal satu topik yang diminati."
            );
            return;
        }

        if (prodi === "") {
            tampilkanKesalahan(
                formFeedback,
                "Pilih program studi."
            );
            return;
        }

        if (pesan.length === 0 || pesan.length > 300) {
            tampilkanKesalahan(
                formFeedback,
                "Pesan harus diisi dan maksimal 300 karakter."
            );
            return;
        }

        // Mengirim formulir setelah validasi berhasil.
        contactForm.submit();
    });

    contactForm.addEventListener("reset", () => {
        hapusFeedback(formFeedback);
    });

    contactForm.addEventListener("input", () => {
        hapusFeedback(formFeedback);
    });
});

// VALIDASI FORM RENCANA KEGIATAN.
const rencanaForm = document.getElementById("rencanaForm");

if (rencanaForm) {
    const formFeedback = rencanaForm.querySelector(
        "[data-form-feedback]"
    );

    rencanaForm.addEventListener("submit", (event) => {
        event.preventDefault();

        const namaKegiatan = rencanaForm.elements.namedItem(
            "nama_kegiatan"
        ).value.trim();

        const kategori = rencanaForm.elements.namedItem(
            "kategori"
        ).value;

        const tanggal = rencanaForm.elements.namedItem(
            "tanggal_kegiatan"
        ).value;

        const lokasi = rencanaForm.elements.namedItem(
            "lokasi"
        ).value.trim();

        const deskripsi = rencanaForm.elements.namedItem(
            "deskripsi"
        ).value.trim();

        hapusFeedback(formFeedback);

        if (namaKegiatan.length < 3 || namaKegiatan.length > 100) {
            tampilkanKesalahan(
                formFeedback,
                "Nama kegiatan harus diisi 3 sampai 100 karakter."
            );
            return;
        }

        if (kategori === "") {
            tampilkanKesalahan(
                formFeedback,
                "Silakan pilih kategori kegiatan."
            );
            return;
        }

        if (tanggal === "") {
            tampilkanKesalahan(
                formFeedback,
                "Tanggal pelaksanaan harus diisi."
            );
            return;
        }

        if (lokasi.length < 3 || lokasi.length > 150) {
            tampilkanKesalahan(
                formFeedback,
                "Lokasi harus diisi 3 sampai 150 karakter."
            );
            return;
        }

        if (deskripsi.length === 0 || deskripsi.length > 500) {
            tampilkanKesalahan(
                formFeedback,
                "Deskripsi harus diisi dan maksimal 500 karakter."
            );
            return;
        }

        // Mengirim formulir setelah validasi berhasil.
        rencanaForm.submit();
    });

    rencanaForm.addEventListener("reset", () => {
        hapusFeedback(formFeedback);
    });

    rencanaForm.addEventListener("input", () => {
        hapusFeedback(formFeedback);
    });
}
