# Perbaikan Sistem Penomoran Foto - Update Histori

## 🐛 **Masalah yang Ditemukan**

Di method `updateHistori` dan `updateHistoriLlg` di `AsetController.php`, kode masih menggunakan:

```php
$aset->foto = json_encode($finalFotos); // ❌ SALAH - mengganti semua foto
```

## ✅ **Perbaikan yang Diterapkan**

Sekarang menggunakan:

```php
// Gabungkan foto lama dengan foto baru
$allFotos = array_merge($existingFotos, $finalFotos);
$aset->foto = json_encode($allFotos); // ✅ BENAR - menambahkan foto baru
```

## 📋 **Lokasi Perbaikan**

### 1. Method `updateHistori` (Baris 623-628)

```php
// Simpan foto baru hasil proses base64 jika ada
if (!empty($finalFotos)) {
    // Gabungkan foto lama dengan foto baru
    $allFotos = array_merge($existingFotos, $finalFotos);
    $aset->foto = json_encode($allFotos); // Simpan sebagai JSON string
}
```

### 2. Method `updateHistoriLlg` (Baris 796-801)

```php
// Simpan foto baru hasil proses base64 jika ada
if (!empty($finalFotos)) {
    // Gabungkan foto lama dengan foto baru
    $allFotos = array_merge($existingFotos, $finalFotos);
    $aset->foto = json_encode($allFotos); // Simpan sebagai JSON string
}
```

## 🎯 **Cara Kerja Setelah Perbaikan**

1. **Ambil foto lama** dari database (`$existingFotos`)
2. **Proses foto baru** dengan penomoran berurutan (`$finalFotos`)
3. **Gabungkan** foto lama + foto baru (`array_merge`)
4. **Simpan** hasil gabungan ke database

## 📊 **Contoh Skenario**

**Sebelum Perbaikan:**

-   Foto lama: [1, 2, 3, 4]
-   Upload foto baru: [5, 6, 7, 8]
-   Hasil di DB: [5, 6, 7, 8] ❌ (foto lama hilang)

**Setelah Perbaikan:**

-   Foto lama: [1, 2, 3, 4]
-   Upload foto baru: [5, 6, 7, 8]
-   Hasil di DB: [1, 2, 3, 4, 5, 6, 7, 8] ✅ (foto lama tetap ada)

## 🔍 **Testing**

1. Create aset dengan 4 foto (nomor 1-4)
2. Update histori dengan 4 foto baru
3. Cek database - seharusnya ada 8 foto (nomor 1-8)
4. Update histori lagi dengan 2 foto
5. Cek database - seharusnya ada 10 foto (nomor 1-10)
