# Panduan Kontribusi

Terima kasih telah berkontribusi pada **Amerta AI**. Berikut adalah panduan ringkas untuk memudahkan kolaborasi.

## 1. Pelaporan Masalah (Bug Report)
Jika menemukan kesalahan, buat **Issue** baru dengan menyertakan:
*   **Judul Jelas:** (Contoh: *Gagal login via Google*).
*   **Langkah Reproduksi:** Urutan kejadian hingga error muncul.
*   **Bukti:** Screenshot atau pesan error.
*   **Environment:** OS, Browser, dan versi PHP/Node.

## 2. Pengajuan Fitur (Feature Request)
1.  Cek daftar **Issues**, pastikan ide belum pernah diajukan.
2.  Buat Issue baru dengan label `enhancement`.
3.  Jelaskan masalah dan solusi yang ditawarkan.

## 3. Alur Kerja (Workflow)

### A. Fork & Clone
Fork repositori ini, lalu clone ke lokal Anda:
```bash
git clone [https://github.com/USERNAME_ANDA/Amerta-AI.git](https://github.com/USERNAME_ANDA/Amerta-AI.git)
cd Amerta-AI
```

### B. Buat Branch
Gunakan branch baru, jangan edit langsung di main.
*   Fitur: `feature/nama-fitur`
*   Perbaikan: `fix/nama-bug`

```bash
git checkout -b feature/tambah-login
```

### 3. Setup Environment
Pastikan Anda sudah menjalankan instalasi sesuai `README.md`.

### 4. Coding Standards
*   **PHP/Laravel**: Ikuti standar **PSR-12**.
*   **Frontend**: Pastikan kode rapi dan tidak ada *console log* yang tertinggal.
*   **Commit Message**: Gunakan Bahasa Inggris/Indonesia yang jelas.
    *   `fix: memperbaiki tombol login yang tidak responsif`
    *   `benerin tombol`

### 5. Push & Pull Request
Setelah selesai, push branch Anda:
```bash
git push origin feature/nama-fitur
```
Buka repository asli Amerta AI di GitHub dan buat **Pull Request (PR)**. Jelaskan apa yang Anda ubah di deskripsi PR.

## Code of Conduct
Harap bersikap sopan dan saling menghormati kepada sesama kontributor. Kami tidak mentolerir pelecehan atau perilaku ofensif dalam bentuk apapun.

---
Terima kasih telah membantu mengembangkan Amerta AI!
