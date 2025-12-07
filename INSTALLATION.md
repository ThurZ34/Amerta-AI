# Panduan Instalasi Amerta AI

## 1. Persiapan Repository
> Unduh source code dan masuk ke direktori proyek.
```bash
git clone https://github.com/ThurZ34/Amerta-AI.git
cd Amerta-AI
```

## 2. Instalasi Dependensi & Environment
> Instal paket backend (Composer) dan frontend (NPM), salin konfigurasi environment, serta generate application key.
```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
```
## 3. Konfigurasi API Key
> Buka file .env dan lengkapi konfigurasi berikut untuk mengaktifkan fitur AI:
```bash
KOLOSAL_API_KEY=...
GEMINI_API_KEY=...
```
## 4. Konfigurasi Google Login (Opsional)
> Fitur ini memungkinkan login otomatis menggunakan akun Google.

Anda perlu mendaftarkan aplikasi Anda ke Google untuk mendapatkan Client ID dan Client Secret.

1. Buat proyek baru di Google Cloud Console.

2. Masuk ke Credentials > Create Credentials > OAuth Client ID.

3. Pilih Web Application dan atur Authorized redirect URIs

4. Salin Client ID dan Client Secret ke dalam file .env:
```bash
GOOGLE_CLIENT_ID=...
GOOGLE_CLIENT_SECRET=...
GOOGLE_REDIRECT_URI=...
(Untuk URL sesuaikan dengan yang sudah di setup di Google Cloud Console)
```

### Menjalankan Aplikasi
```bash
php artisan migrate
npm run dev
php artisan serve
```
