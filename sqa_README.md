# SQA BUNGA — Panduan Lengkap Pengerjaan
**Proyek:** Form Pendaftaran Mahasiswa Baru USTI  
**Mata Kuliah:** Software Quality Assurance (SQA)  
**Versi:** 2026-2

---

## Daftar Isi
1. [Gambaran Umum](#1-gambaran-umum)
2. [Struktur File](#2-struktur-file)
3. [Prasyarat & Instalasi](#3-prasyarat--instalasi)
4. [Black Box Testing — JMeter](#4-black-box-testing--jmeter)
5. [White Box Testing — SonarQube](#5-white-box-testing--sonarqube)
6. [Tools SQA — Jira](#6-tools-sqa--jira)
7. [CI/CD — GitHub Actions](#7-cicd--github-actions)
8. [Skenario Pengujian](#8-skenario-pengujian)
9. [Troubleshooting](#9-troubleshooting)

---

## 1. Gambaran Umum

Proyek ini menguji aplikasi **Form Pendaftaran Mahasiswa Baru USTI** (`form_mahasiswa_baru.php`) menggunakan 4 tools SQA secara terintegrasi:

| Tools | Jenis Pengujian | File Konfigurasi |
|---|---|---|
| Apache JMeter 5.6.3 | Black Box Testing | `sqa_bunga_jmeter.jmx` |
| SonarQube | White Box Testing | `sqa_bunga_sonarqube.properties` |
| Jira Software | Tools Manajemen SQA | `sqa_bunga_jira.json` |
| GitHub Actions | CI/CD Pipeline | `sqa_bunga_cicd.yml` |

**Alur kerja pipeline:**
```
Push Kode → [1] SonarQube → [2] JMeter → [3] Jira Report → [4] Deploy Staging
```

---

## 2. Struktur File

Tempatkan semua file berikut di **root direktori repositori** GitHub:

```
repository-root/
│
├── form_mahasiswa_baru.php          ← Aplikasi yang diuji
│
├── sqa_bunga_jmeter.jmx             ← Konfigurasi Black Box Testing (JMeter)
├── sqa_bunga_sonarqube.properties   ← Konfigurasi White Box Testing (SonarQube)
├── sqa_bunga_jira.json              ← Konfigurasi Tools SQA (Jira)
├── sqa_bunga_README.md              ← Panduan ini
│
└── .github/
    └── workflows/
        └── sqa_bunga_cicd.yml       ← Konfigurasi CI/CD (GitHub Actions)
```

> **Penting:** File `sqa_bunga_cicd.yml` harus diletakkan di `.github/workflows/`, bukan di root.

---

## 3. Prasyarat & Instalasi

### 3.1 Software yang Diperlukan

| Software | Versi | Link Unduh |
|---|---|---|
| PHP | 8.2+ | https://www.php.net/downloads |
| Java JDK | 17+ | https://adoptium.net |
| Apache JMeter | 5.6.3 | https://jmeter.apache.org/download_jmeter.cgi |
| SonarQube Community | 10.x | https://www.sonarsource.com/products/sonarqube/downloads |
| SonarScanner | Latest | https://docs.sonarsource.com/sonarqube/latest/analyzing-source-code/scanners/sonarscanner |
| Git | 2.x+ | https://git-scm.com/downloads |

### 3.2 Verifikasi Instalasi

Jalankan perintah berikut di terminal untuk memastikan semua software terpasang:

```bash
# Cek PHP
php --version
# Output yang diharapkan: PHP 8.2.x ...

# Cek Java
java --version
# Output yang diharapkan: openjdk 17.x.x ...

# Cek JMeter
apache-jmeter-5.6.3/bin/jmeter --version
# Output yang diharapkan: Version 5.6.3 ...

# Cek SonarScanner
sonar-scanner --version
# Output yang diharapkan: SonarScanner ...

# Cek Git
git --version
# Output yang diharapkan: git version 2.x.x
```

### 3.3 Jalankan Aplikasi Secara Lokal

Sebelum melakukan pengujian, pastikan aplikasi dapat diakses di browser:

```bash
# Masuk ke direktori proyek
cd /path/ke/folder-proyek

# Jalankan PHP built-in server
php -S localhost:8080

# Buka browser dan akses:
# http://localhost:8080/form_mahasiswa_baru.php
```

Pastikan halaman form tampil dengan benar sebelum melanjutkan ke langkah pengujian.

---

## 4. Black Box Testing — JMeter

File: **`sqa_bunga_jmeter.jmx`**

### 4.1 Langkah Persiapan

**a. Unduh dan ekstrak JMeter:**
```bash
# Unduh
wget https://archive.apache.org/dist/jmeter/binaries/apache-jmeter-5.6.3.tgz

# Ekstrak
tar -xzf apache-jmeter-5.6.3.tgz

# Beri izin eksekusi
chmod +x apache-jmeter-5.6.3/bin/jmeter
```

**b. Salin file JMX ke folder proyek:**
```bash
cp sqa_bunga_jmeter.jmx /path/ke/folder-proyek/
```

**c. Pastikan PHP server berjalan:**
```bash
php -S localhost:8080 &
```

### 4.2 Menjalankan Pengujian via GUI (Disarankan untuk Belajar)

```bash
# Buka JMeter GUI
apache-jmeter-5.6.3/bin/jmeter

# Di dalam GUI:
# 1. Klik File > Open
# 2. Pilih file: sqa_bunga_jmeter.jmx
# 3. Periksa User Defined Variables di Test Plan:
#    - BASE_URL = localhost
#    - PORT     = 8080
#    - PROTOCOL = http
#    - ENDPOINT = /form_mahasiswa_baru.php
# 4. Klik tombol Run (segitiga hijau) atau tekan Ctrl+R
```

### 4.3 Menjalankan Pengujian via Command Line (Non-GUI)

```bash
# Jalankan test plan dan hasilkan laporan HTML
apache-jmeter-5.6.3/bin/jmeter \
  -n \
  -t  sqa_bunga_jmeter.jmx \
  -l  sqa_bunga_results.jtl \
  -e \
  -o  laporan-jmeter/ \
  -Jhost=localhost \
  -Jport=8080 \
  -Jprotocol=http \
  -Jendpoint=/form_mahasiswa_baru.php

# Keterangan flag:
# -n  → Non-GUI mode
# -t  → File test plan (.jmx)
# -l  → File output hasil (.jtl)
# -e  → Generate laporan HTML
# -o  → Folder output laporan HTML
# -J  → Override variabel di test plan
```

### 4.4 Melihat Hasil

```bash
# Buka laporan HTML di browser
# Windows: start laporan-jmeter/index.html
# Mac    : open laporan-jmeter/index.html
# Linux  : xdg-open laporan-jmeter/index.html
```

Atau buka file `sqa_bunga_results.jtl` di JMeter GUI melalui **View Results Tree**.

### 4.5 Menyesuaikan Variabel (Opsional)

Jika server berjalan di port/host berbeda, edit bagian **User Defined Variables** di `sqa_bunga_jmeter.jmx`:

```xml
<elementProp name="BASE_URL" elementType="Argument">
    <stringProp name="Argument.value">localhost</stringProp>  <!-- Ganti host di sini -->
</elementProp>
<elementProp name="PORT" elementType="Argument">
    <stringProp name="Argument.value">8080</stringProp>       <!-- Ganti port di sini -->
</elementProp>
```

---

## 5. White Box Testing — SonarQube

File: **`sqa_bunga_sonarqube.properties`**

### 5.1 Langkah Menjalankan SonarQube Server

**a. Jalankan SonarQube via Docker (paling mudah):**
```bash
# Pull dan jalankan SonarQube Community Edition
docker run -d \
  --name sonarqube \
  -p 9000:9000 \
  sonarqube:community

# Tunggu sekitar 2-3 menit, lalu buka:
# http://localhost:9000
# Login default: admin / admin
# Sistem akan meminta ganti password saat pertama login
```

**b. Alternatif — Install manual SonarQube:**
```bash
# Unduh SonarQube
wget https://binaries.sonarsource.com/Distribution/sonarqube/sonarqube-10.4.1.88267.zip
unzip sonarqube-10.4.1.88267.zip

# Jalankan server
# Windows: sonarqube-10.x/bin/windows-x86-64/StartSonar.bat
# Linux  : sonarqube-10.x/bin/linux-x86-64/sonar.sh start
# Mac    : sonarqube-10.x/bin/macosx-universal-64/sonar.sh start
```

### 5.2 Membuat Token Autentikasi

```
1. Buka browser → http://localhost:9000
2. Login dengan akun admin
3. Klik avatar (pojok kanan atas) → My Account
4. Pilih tab: Security
5. Pada bagian "Generate Tokens":
   - Name  : sqa-bunga-token
   - Type  : Global Analysis Token
   - Expire: No expiration
6. Klik "Generate"
7. SALIN token yang muncul (hanya tampil sekali!)
   Contoh: sqa_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
```

### 5.3 Menyiapkan File Konfigurasi

```bash
# Salin file properties ke direktori proyek
cp sqa_bunga_sonarqube.properties /path/ke/folder-proyek/sonar-project.properties

# Set environment variable token (JANGAN tulis token langsung di file!)
# Linux/Mac:
export SONAR_TOKEN=sqa_xxxxxxxxxxxxxxxxxxxx

# Windows (Command Prompt):
set SONAR_TOKEN=sqa_xxxxxxxxxxxxxxxxxxxx

# Windows (PowerShell):
$env:SONAR_TOKEN="sqa_xxxxxxxxxxxxxxxxxxxx"
```

### 5.4 Menjalankan SonarScanner

```bash
# Masuk ke direktori proyek
cd /path/ke/folder-proyek

# Pastikan ada file sonar-project.properties
ls sonar-project.properties

# Jalankan analisis
sonar-scanner \
  -Dsonar.login=$SONAR_TOKEN \
  -Dsonar.host.url=http://localhost:9000

# Tunggu proses analisis selesai (1-3 menit)
# Output akhir yang diharapkan:
# INFO: EXECUTION SUCCESS
```

### 5.5 Melihat Hasil Analisis

```
1. Buka browser → http://localhost:9000
2. Klik menu "Projects"
3. Pilih proyek: "SQA Bunga - Form Pendaftaran Mahasiswa Baru USTI"
4. Periksa dashboard yang menampilkan:
   - Bugs           → Cacat logika pada kode
   - Vulnerabilities → Celah keamanan
   - Security Hotspots → Area yang perlu review
   - Code Smells    → Masalah kualitas kode
   - Coverage       → Persentase kode yang diuji
   - Duplications   → Kode yang terduplikasi
```

### 5.6 Memahami Quality Gate

SonarQube akan menampilkan status **PASSED** atau **FAILED** berdasarkan aturan yang ditetapkan. Jika gagal, periksa tab **Issues** untuk detail masalah yang perlu diperbaiki.

---

## 6. Tools SQA — Jira

File: **`sqa_bunga_jira.json`**

### 6.1 Membuat Akun & Project Jira

**a. Daftar Jira Cloud (gratis untuk tim kecil):**
```
1. Buka https://www.atlassian.com/software/jira
2. Klik "Get it free"
3. Daftar dengan email
4. Buat workspace baru, contoh: sqa-bunga-usti
5. URL Jira Anda akan menjadi: https://sqa-bunga-usti.atlassian.net
```

**b. Buat project baru di Jira:**
```
1. Di dashboard Jira, klik "Create project"
2. Pilih template: "Scrum" atau "Kanban"
3. Isi detail project:
   - Name       : SQA Bunga - Form Mahasiswa Baru USTI
   - Key        : SQAB
   - Type       : Team-managed software
4. Klik "Create project"
```

### 6.2 Membuat API Token

```
1. Buka https://id.atlassian.com/manage-profile/security/api-tokens
2. Klik "Create API token"
3. Label: sqa-bunga-api-token
4. Klik "Create"
5. SALIN token yang muncul (hanya tampil sekali!)
```

### 6.3 Membuat Test Case via REST API

Gunakan isi file `sqa_bunga_jira.json` untuk membuat 4 test case secara otomatis:

```bash
# Set variabel environment
export JIRA_BASE_URL="https://sqa-bunga-usti.atlassian.net"
export JIRA_EMAIL="email-anda@domain.com"
export JIRA_TOKEN="token-api-anda"

# Buat Test Case SK-1: Input Karakter
curl -X POST \
  "$JIRA_BASE_URL/rest/api/3/issue" \
  -u "$JIRA_EMAIL:$JIRA_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "fields": {
      "project":   { "key": "SQAB" },
      "summary":   "[SK-1] Penggunaan Karakter - Input Huruf Saja",
      "issuetype": { "name": "Task" },
      "priority":  { "name": "High" },
      "labels":    ["black-box", "karakter", "positif"],
      "description": {
        "type": "doc", "version": 1,
        "content": [{
          "type": "paragraph",
          "content": [{ "type": "text",
            "text": "Data Entri: BungaAlice | Expected: BERHASIL | Warna: #198754"
          }]
        }]
      }
    }
  }'

# Ulangi untuk SK-2, SK-3, SK-4 dengan data sesuai sqa_bunga_jira.json
```

### 6.4 Membuat Test Case Secara Manual di UI Jira

Jika tidak ingin menggunakan API, buat issue secara manual:

```
Untuk setiap skenario (SK-1 sampai SK-4):
1. Klik tombol "+ Create" di Jira
2. Pilih project: SQAB
3. Issue Type: Task
4. Isi Summary sesuai kolom "summary" di sqa_bunga_jira.json
5. Isi Description dengan detail testSteps dan expectedOutcome
6. Set Priority sesuai field "priority"
7. Tambahkan Labels sesuai field "labels"
8. Klik "Create"
```

### 6.5 Mengisi Hasil Pengujian di Jira

Setelah pengujian selesai dilakukan:

```
1. Buka issue SK-1 / SK-2 / SK-3 / SK-4
2. Tambahkan komentar dengan hasil aktual:
   - hasilSebenarnya : (isi sesuai yang terjadi di layar)
   - statusPengujian : BENAR / SALAH
3. Ubah status issue:
   - Jika berhasil sesuai ekspektasi → "Done"
   - Jika ada ketidaksesuaian        → "In Progress" atau buat Bug baru
4. Lampirkan screenshot hasil pengujian
```

---

## 7. CI/CD — GitHub Actions

File: **`sqa_bunga_cicd.yml`**

### 7.1 Persiapan Repositori GitHub

```bash
# Inisialisasi repositori Git (jika belum ada)
cd /path/ke/folder-proyek
git init

# Buat struktur folder GitHub Actions
mkdir -p .github/workflows

# Salin file CI/CD ke lokasi yang benar
cp sqa_bunga_cicd.yml .github/workflows/

# Salin semua file konfigurasi ke root repositori
cp sqa_bunga_jmeter.jmx .
cp sqa_bunga_sonarqube.properties .
cp form_mahasiswa_baru.php .

# Buat repositori di GitHub.com terlebih dahulu, lalu:
git remote add origin https://github.com/username/nama-repo.git
git branch -M main
```

### 7.2 Menambahkan Secrets di GitHub

```
1. Buka repositori di GitHub.com
2. Klik tab "Settings"
3. Di sidebar kiri, klik "Secrets and variables" → "Actions"
4. Klik "New repository secret" untuk setiap secret berikut:

   ┌──────────────────────┬─────────────────────────────────────────────┐
   │ Name                 │ Value (contoh)                              │
   ├──────────────────────┼─────────────────────────────────────────────┤
   │ SONAR_TOKEN          │ sqa_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx     │
   │ SONAR_HOST_URL       │ http://ip-server-anda:9000                  │
   │ JIRA_BASE_URL        │ https://sqa-bunga-usti.atlassian.net        │
   │ JIRA_USER_EMAIL      │ email-anda@domain.com                       │
   │ JIRA_API_TOKEN       │ ATATT3xFfGF0xxxxxxxxxxxxxxxxxxxxxxxxxx      │
   │ DEPLOY_HOST          │ 192.168.1.100 (opsional)                    │
   │ DEPLOY_USER          │ ubuntu (opsional)                           │
   │ DEPLOY_KEY           │ (isi private key SSH, opsional)             │
   │ DEPLOY_PATH          │ /var/www/html (opsional)                    │
   └──────────────────────┴─────────────────────────────────────────────┘
```

> Secrets untuk DEPLOY hanya diperlukan jika menggunakan Job 4 (deployment staging).

### 7.3 Push Kode untuk Memicu Pipeline

```bash
# Tambahkan semua file
git add .

# Commit
git commit -m "feat: tambahkan konfigurasi SQA Bunga (JMeter, SonarQube, Jira, CI/CD)"

# Push ke branch main — pipeline akan otomatis berjalan
git push -u origin main
```

### 7.4 Memantau Jalannya Pipeline

```
1. Buka repositori di GitHub.com
2. Klik tab "Actions"
3. Pilih workflow: "SQA Bunga - CI/CD Pipeline Form Mahasiswa USTI"
4. Klik run terbaru untuk melihat detail

Pipeline berjalan dalam urutan:
  [Job-1] SonarQube  → Analisis kode PHP (2-5 menit)
       ↓
  [Job-2] JMeter     → Black Box Testing 4 skenario (2-3 menit)
       ↓
  [Job-3] Jira       → Buat laporan run di Jira (< 1 menit)
       ↓
  [Job-4] Deploy     → Deploy ke staging (hanya branch main, opsional)
```

### 7.5 Menjalankan Pipeline Secara Manual

```
1. Buka tab "Actions" di GitHub
2. Pilih workflow: "SQA Bunga - CI/CD Pipeline Form Mahasiswa USTI"
3. Klik tombol "Run workflow" (kanan atas)
4. Atur opsi yang tersedia:
   - skip_sonarqube : true/false
   - skip_jmeter    : true/false
   - skip_jira      : true/false
   - skip_deploy    : true/false (default: true)
5. Klik "Run workflow"
```

### 7.6 Mengunduh Laporan Hasil

```
1. Buka run pipeline yang sudah selesai
2. Scroll ke bawah ke bagian "Artifacts"
3. Unduh laporan yang tersedia:
   - sqa-bunga-jmeter-html-report-runN  → Laporan HTML JMeter
   - sqa-bunga-jmeter-jtl-runN          → Raw data hasil .jtl
   - sqa-bunga-jmeter-log-runN          → Log eksekusi JMeter
```

---

## 8. Skenario Pengujian

Semua tools di atas menguji 4 skenario yang sama, sesuai format tabel pengujian UAS SQA:

| No | Skenario | Data Entri | Tahap Pengujian | Hasil yang Diharapkan | Warna |
|:---:|---|:---:|---|---|:---:|
| 1 | Penggunaan Karakter | `BungaAlice` | Akses → Ketik huruf → Enter/SAVE | Status **BERHASIL**, label Skenario 1 | 🟢 `#198754` |
| 2 | Penggunaan Angka | `123456` | Akses → Ketik angka → Enter | Status **BERHASIL**, label Skenario 2 | 🔵 `#0d6efd` |
| 3 | Kombinasi Karakter+Angka | `Bunga2026` | Akses → Ketik campuran → Klik SAVE | Status **BERHASIL**, label Skenario 3 | 🟠 `#fd7e14` |
| 4 | Input Kosong _(negatif)_ | _(kosong)_ | Akses → Biarkan kosong → Klik SAVE | Status **GAGAL**, pesan penolakan | 🔴 `#dc3545` |

### Logika Pengujian pada Kode PHP

```php
if ($input == "")                              → Skenario 4 : GAGAL
elseif (preg_match('/^[A-Za-z\s]+$/', $input) → Skenario 1 : BERHASIL
elseif (preg_match('/^[0-9]+$/', $input))      → Skenario 2 : BERHASIL
else                                           → Skenario 3 : BERHASIL
```

---

## 9. Troubleshooting

### JMeter

| Masalah | Penyebab | Solusi |
|---|---|---|
| `Connection refused` | PHP server belum jalan | Jalankan `php -S localhost:8080` terlebih dahulu |
| Assertion gagal semua | Port salah | Ubah variabel `PORT` di test plan menjadi port yang benar |
| `java: command not found` | Java belum terinstall | Install Java 17+ dan tambahkan ke PATH |
| Laporan HTML kosong | Folder output sudah ada | Hapus folder `laporan-jmeter/` sebelum jalankan ulang |

### SonarQube

| Masalah | Penyebab | Solusi |
|---|---|---|
| `Not authorized` | Token salah/expired | Buat token baru di SonarQube → My Account → Security |
| `Project not found` | Project key tidak cocok | Pastikan `sonar.projectKey` di `.properties` sama dengan yang di server |
| Server tidak bisa diakses | SonarQube belum berjalan | Tunggu 2-3 menit setelah start, lalu cek `localhost:9000` |
| Quality Gate FAILED | Ada issues kritis | Buka dashboard SonarQube → lihat tab Issues untuk detail |

### Jira

| Masalah | Penyebab | Solusi |
|---|---|---|
| `HTTP 401 Unauthorized` | Token API salah | Buat ulang token di `id.atlassian.com` |
| `HTTP 404 Not Found` | Project key salah | Pastikan `SQAB` sudah ada di Jira, atau sesuaikan key |
| Issue tidak muncul | Filter aktif | Reset filter di halaman board Jira |

### GitHub Actions

| Masalah | Penyebab | Solusi |
|---|---|---|
| Secret tidak terbaca | Nama secret typo | Periksa nama di Settings → Secrets, pastikan sama persis |
| Job gagal di SonarQube | SONAR_HOST_URL tidak bisa diakses dari GitHub | Gunakan SonarCloud atau pastikan server bisa diakses publik |
| JMeter tidak bisa download | Koneksi archive.apache.org timeout | Tambahkan retry atau gunakan mirror lain |
| PHP server gagal start | Port 8080 sudah dipakai | Ubah `APP_PORT` di env section workflow |

---

## Referensi

- Apache JMeter Docs: https://jmeter.apache.org/usermanual/index.html
- SonarQube PHP Docs: https://docs.sonarsource.com/sonarqube/latest/analyzing-source-code/languages/php
- Jira REST API v3: https://developer.atlassian.com/cloud/jira/platform/rest/v3
- GitHub Actions Docs: https://docs.github.com/en/actions

---

*Universitas Sains Dan Teknologi Indonesia (USTI) — TA. 2026*
