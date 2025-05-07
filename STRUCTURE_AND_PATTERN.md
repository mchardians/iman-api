# 🧩 Struktur Folder Laravel & Repository Pattern

Dokumentasi ini menjelaskan struktur folder yang digunakan untuk implementasi **Repository-Service Pattern** dalam proyek Laravel, serta relasi antar layer dalam arsitektur tersebut.

---

## 📌 Tujuan

- Memisahkan **business logic** dan **data access** dari controller.
- Membuat kode lebih modular, scalable, dan mudah diuji.
- Menyediakan abstract layer yang fleksibel untuk mengganti cara akses data (misal: dari Eloquent ke API eksternal) tanpa memodifikasi layer lain.

---

## 📂 Struktur Folder dan Penjelasan

**Struktur Folder:**

```text
app/
├── Console/
├── Exceptions/
├── Http/
│   ├── Controllers/
│   ├── Middleware/
│   └── Requests/               # Form Request Validation
│
├── Models/                     
│
├── Repositories/              # Interface dan implementasi repository
│   ├── Contracts/             # Interface-nya
│   └── Eloquent/              # Implementasi Eloquent-nya
│
├── Services/                  # Logika bisnis tingkat lanjut
│
├── Traits/                    # Reusable code dalam bentuk trait
│
├── Helpers/                   # Fungsi procedural (global helpers)
│
├── Libraries/                 # Kelas yang perlu diinstansiasi (misalnya: PDF, CurlClient, dll)
│
└── Integrations/
```

**Penjelasan:**

| Folder                        | Deskripsi                                                                    |
|-------------------------------|------------------------------------------------------------------------------|
| `app/Models/`                 | Menyimpan Eloquent model.                                             |
| `app/Repositories/Contracts/` | Menyimpan interface/kontrak repository (misalnya `RoomRepositoryInterface`). |
| `app/Repositories/Eloquent/`  | Implementasi konkret repository menggunakan Eloquent ORM.                    |
| `app/Services/`               | Menyimpan logika bisnis tingkat lanjut, seperti `RoomService`.               |
| `app/Traits/`                 | Trait PHP untuk reusable logic dalam class.                                  |
| `app/Helpers/`                | Fungsi procedural global yang bisa dipakai di seluruh aplikasi.              |
| `app/Libraries/`              | Kelas instansiasi khusus seperti PDF generator, custom file uploader.        |
| `app/Integrations/`           | Koneksi ke API eksternal seperti WhatsApp, Payment Gateway, dll.             |

---

## 📊 Visualisasi Diagram Repository Service Pattern

```text
┌────────────┐         uses         ┌────────────────┐        calls         ┌────────────────────────────┐
│ Controller │ ─────────────────▶  │ Service Layer   │ ─────────────────▶  │ Repository Interface        │
└────────────┘                      └────────────────┘                      └────────────────────────────┘
                                                                                       ▲
                                                                                       │
                                                                              binds to │
                                                                                       │
                                                                         ┌─────────────┴───────────────┐
                                                                         │ Eloquent Implementation     │
                                                                         └─────────────────────────────┘
```

## 🔃 Penjelasan Alur

- **Controller** hanya menangani validasi HTTP request dan response. Controller menggunakan **Service Layer**.
- **Service Layer** berisi logika bisnis dan memanggil interface repository.
- **Repository Interface** adalah kontrak pengikat dari eloquent.
- **Eloquent Implementation** merupakan data access layer yang sewaktu-waktu dapat diganti (misalnya ke external API atau raw query secara manual) tanpa mengubah kode service.
