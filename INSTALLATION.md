## 📦 Panduan Instalasi

Mulai perjalananmu dengan **Baitana API**, solusi backend untuk Sistem Informasi Manajemen Masjid.  
Ikuti langkah-langkah berikut untuk menjalankan proyek ini secara lokal:

---

### ⚙️ Persyaratan

Pastikan kamu sudah menginstal:

- [PHP >= 8.1](https://www.php.net/)
- [Composer](https://getcomposer.org/)
- [MySQL](https://www.mysql.com/) / MariaDB
- [Node.js & npm](https://nodejs.org/)
- [Laravel](https://laravel.com/)
- [Postman](https://www.postman.com/) *(optional, untuk testing API)*

---

### 📥 Clone Repository

```bash
git clone https://github.com/mchardians/baitana-api.git
cd baitana-api
```

---

### 🔑 Setup Environment

1. Install semua dependecies
```bash
composer install
npm install
```
2. Copy file `.env.example` menjadi `.env`
```bash
cp .env.example .env
```
3. Konfigurasikan database pada file `.env`
```bash
DB_DATABASE=baitana
DB_USERNAME=root
DB_PASSWORD=
```
4. Konfigurasikan Mail pada file `.env`
```bash
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME="yourmail@example.com"
MAIL_PASSWORD="yourapppassword"
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="yourmail@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```
5. Generate app key
```bash
php artisan key:generate
```
6. Generate jwt secret key
```bash
php artisan jwt:secret
```

---

### 🛠️ Jalankan migration dan seeder
```bash
php artisan migrate --seed
```

---

### 💽 Jalankan Storage Link Laravel
```bash
php artisan storage:link
``` 

---

### 🎮 Jalankan Local Server
```bash
php artisan serve
```

---

### ⏰ Jalankan Scheduled Jobs
```bash
php artisan schedule:work
```
