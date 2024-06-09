<?php
include 'db.php';

// Formdan gelen verileri al
$ogrenci_no = $_POST['ogrenci_no'];
$parola = $_POST['parola'];
$ad = $_POST['ad'];
$soyad = $_POST['soyad'];
$profil_fotografi = $_FILES['profil_fotografi'];

// Profil fotoğrafı için hedef dizin
$target_dir = "C:/wamp64/www/2024_YBS_ANDAC/ogrenci_profil_fotograflari/";
$target_file = $target_dir . basename($profil_fotografi["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Dosya bir görüntü dosyası mı kontrol et
$check = getimagesize($profil_fotografi["tmp_name"]);
if($check !== false) {
    $uploadOk = 1;
} else {
    echo "Dosya bir görüntü dosyası değil.";
    $uploadOk = 0;
}

// Dosya zaten mevcut mu kontrol et
if (file_exists($target_file)) {
    echo "Dosya zaten mevcut.";
    $uploadOk = 0;
}

// Dosya boyutunu kontrol et (5MB ile sınırlı)
if ($profil_fotografi["size"] > 5000000) {
    echo "Dosya boyutu çok büyük.";
    $uploadOk = 0;
}

// Belirli dosya türlerine izin ver (JPEG, PNG)
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
    echo "Sadece JPG, JPEG ve PNG dosyalarına izin verilmektedir.";
    $uploadOk = 0;
}

// $uploadOk 0 olarak ayarlanmışsa dosya yüklenmeyecek
if ($uploadOk == 0) {
    echo "Dosyanız yüklenmedi.";
// Her şey yolundaysa dosyayı yükle
// Her şey yolundaysa dosyayı yükle
} else {
    // Yeni dosya adını oluştur
    $newFileName = $ogrenci_no . '.' . $imageFileType;
    $newFilePath = $target_dir . $newFileName;
    if (move_uploaded_file($profil_fotografi["tmp_name"], $newFilePath)) {
        echo "Dosya " . htmlspecialchars($newFileName) . " başarıyla yüklendi.";

        // SQL sorgusunu hazırla ve bağla
        $stmt = $conn->prepare("INSERT INTO ogrenci (ogrenci_no, parola, ad, soyad, profil_fotografi) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sisss", $ogrenci_no, $parola, $ad, $soyad, $newFileName);

        // Sorguyu çalıştır ve sonucu kontrol et
        if ($stmt->execute() === TRUE) {
            echo "Yeni kayıt başarıyla oluşturuldu";
        } else {
            echo "Hata: " . $stmt->error;
        }

        // Bağlantıyı kapat
        $stmt->close();
        $conn->close();
    } else {
        echo "Dosyanız yüklenirken bir hata oluştu.";
    }
}
?>