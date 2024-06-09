<?php
include 'db.php';

session_start();

// Formdan gelen verileri al
$ogrenci_no = $_POST['ogrenci_no'];
$parola = $_POST['parola'];

// SQL sorgusunu hazırla ve bağla
$stmt = $conn->prepare("SELECT * FROM ogrenci WHERE ogrenci_no = ? AND parola = ?");
$stmt->bind_param("si", $ogrenci_no, $parola);

// Sorguyu çalıştır
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Kullanıcı doğrulandı, oturum başlat
    $_SESSION['ogrenci_no'] = $ogrenci_no;
    $_SESSION['ad'] = $ad;
    $_SESSION['soyad'] = $soyad;
    header("Location: welcome.php"); // Giriş başarılıysa yönlendirme yapılacak sayfa
    exit();
} else {
    echo "Geçersiz öğrenci numarası veya parola.";
}

// Bağlantıyı kapat
$stmt->close();
$conn->close();
?>
