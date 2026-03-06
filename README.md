# 📊 Tam Kapsamlı PHP Ön Muhasebe ve Finans Yönetim Sistemi

Bu proje, küçük ve orta ölçekli işletmelerin (KOBİ) tüm finansal süreçlerini, ön muhasebe işlemlerini ve personel yönetimini tek bir noktadan dijital olarak yönetebilmeleri için geliştirilmiş web tabanlı bir muhasebe otomasyonudur.

## 🚀 Projenin Amacı
İşletmelerin gelir-gider dengesini kurmasını, cari hesaplarını takip etmesini, stok durumlarını kontrol altında tutmasını ve personel maaş/performans süreçlerini kolayca yönetmesini sağlamaktır. Kullanıcı dostu arayüzü ve detaylı raporlama özellikleriyle karmaşık muhasebe süreçlerini basitleştirir.

## 💎 Temel Özellikler ve Modüller

Proje, işletmenin tüm ihtiyaçlarını karşılayacak şekilde modüler olarak tasarlanmıştır:

### 👥 Cari ve Müşteri Yönetimi
* **Cari Kartlar (`cariler.php`):** Müşteri ve tedarikçilerin detaylı kayıtları.
* **Cari Hareketler (`cari_hareketler.php`):** Carilere ait borç/alacak ve işlem geçmişi takibi.

### 💰 Finans ve Nakit Akışı
* **Kasa Yönetimi (`kasalar.php`, `kasa_islemleri.php`):** Günlük nakit giriş-çıkışları ve çoklu kasa takibi.
* **Banka Yönetimi (`bankalar.php`, `banka_islemleri.php`):** Banka hesapları, EFT/Havale işlemleri.
* **Gelir & Gider Takibi (`gelir_gider.php`):** İşletmenin genel finansal analizi.
* **Borç Takibi (`borclar.php`):** Ödenecek ve tahsil edilecek hesapların kontrolü.
* **Bütçe Planlama (`butce.php`):** Gelecek dönem finansal planlamalar.
* **Yatırım ve Tasarruf (`yatirimlar.php`, `tasarruflar.php`):** İşletme veya şahsi varlıkların yönetimi.

### 📦 Ticari İşlemler
* **Fatura Yönetimi (`faturalar.php`):** Alış ve satış faturalarının kesilmesi, listelenmesi.
* **Alış ve Satışlar (`alislar.php`, `satislar.php`):** Tedarik ve satış süreçlerinin detaylı loglanması.
* **Stok Takibi (`stoklar.php`):** Ürün envanter durumu, kritik stok uyarıları.

### 🧑‍💼 Personel ve İK Yönetimi
* **Personel Kartları (`personeller.php`):** Çalışan özlük bilgileri.
* **Maaş ve Bordro (`maaslar.php`, `personel_maas.php`):** Personel maaş ödemeleri ve avans takibi.
* **Giriş/Çıkış Takibi (`personel_giris.php`, `giris_kayitlari.php`):** Mesai ve puantaj yönetimi.
* **Personel Raporları (`personel_rapor.php`):** Çalışan bazlı performans ve maliyet analizi.

### 🔒 Güvenlik ve Sistem Yönetimi
* **Kullanıcı Yetkilendirme (`auth/login.php`, `auth/register.php`):** Güvenli giriş ve rol bazlı erişim.
* **Sistem Ayarları (`ayarlar.php`):** Firma bilgileri ve genel sistem konfigürasyonu.
* **Kapsamlı Dashboard (`dashboard.php`):** Tüm finansal durumun özet grafiklerle tek ekranda sunulması.

## 🛠️ Kullanılan Teknolojiler
* **Backend:** PHP
* **Veritabanı:** MySQL / MariaDB (SQL yedeği projeye dahildir)
* **Frontend:** HTML5, CSS3 (`assets/style.css`)
* **Mimari:** Modüler, session tabanlı güvenli yapı (`includes/auth.php`, `includes/db.php`)

## ⚙️ Kurulum ve Çalıştırma

Projeyi yerel sunucunuzda (XAMPP, MAMP, Laragon vb.) veya hosting ortamında çalıştırmak için aşağıdaki adımları izleyin:

1. **Projeyi Klonlayın:**
   ```bash
   git clone [https://github.com/Tiftik37/muhasebe-programi.git](https://github.com/Tiftik37/muhasebe-programi.git)
Veritabanını İçe Aktarın:
Proje ana dizininde bulunan u289106828_muhasebe (1).sql dosyasını phpMyAdmin veya benzeri bir araç üzerinden veritabanınıza import edin.

Veritabanı Bağlantısını Ayarlayın:
includes/db.php dosyasını bir metin editörü ile açın ve veritabanı bilgilerinizi (kullanıcı adı, şifre, db adı) kendi sisteminize göre güncelleyin.

Çalıştırın:
Projeyi yerel sunucunuzun htdocs veya www klasörüne taşıyın ve tarayıcınızdan localhost/muhasebe-programi yoluna giderek projeyi başlatın.
